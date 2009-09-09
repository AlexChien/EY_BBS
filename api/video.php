<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: video.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

error_reporting(0);
define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -3));
require_once DISCUZ_ROOT.'./forumdata/cache/cache_settings.php';

$appid = 'c4ca4238a0b923820dcc509a6f75849b';
$siteid = $_DCACHE['settings']['vsiteid'];
$sitekey = $_DCACHE['settings']['vkey'];

define('VIDEO_DEBUG', 0);
define('SERVER_HOST', 'api.v.insenz.com');
define('SERVER_URL', 'http://' . SERVER_HOST . '/video_api.php');
define('PIC_SERVER_HOST', 'p.v.insenz.com');

if($_GET['action'] == 'createcode') {
	echo VideoClient_Util::createCode();
}

class VideoClient_Result {
	var $status;
	var $errMessage;
	var $errCode;
	var $_result;
	function VideoClient_Result($result) {
		$this->status = $result['status'];
		$this->errMessage = $result['errMessage'];
		$this->errCode = $result['errCode'];
		$this->_result = $result;

		if($result['resultData']) {
			foreach($result['resultData'] as $value) {
				$this->_result['results'][] = new VideoClient_Result($value);
			}
		}
	}

	function isError() {
		return VideoClient::isError($this->_result);
	}

	function getCode() {
		return VideoClient::getCode($this->_result);
	}

	function getMessage() {
		return VideoClient::getMessage($this->_result);
	}

	function get($field = false) {
		return $field ? $this->_result[$field] : $this->_result;
	}
}

class VideoClient {
	var $appId;
	var $version = '0.1';
	var $key = 'ComsenzVideoService';
	var $apiServerUrl = SERVER_URL;

	function VideoClient($appId) {
		$this->appId = $appId;
	}

	function getUrl() {
		return $this->apiServerUrl;
	}

	function setUrl($url) {
		$this->url = $url;
	}

	function _sign($args) {
		return md5($args);
	}

	function _encrypt($str) {
		return $this->authcode($str, $this->key);
	}

	function _decrypt($str) {
		return $this->authcode($str, $this->key, 'DECODE');
	}

	function authcode($string, $key, $operation = 'ENCODE') {
		$key_length = strlen($key);
		if($key_length == 0) {
			return false;
		}
		$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
		$string_length = strlen($string);

		$rndkey = $box = array();
		$result = '';

		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
				return substr($result, 8);
			} else {
				return '';
			}
		} else {
			return str_replace('=', '', base64_encode($result));
		}
	}

	function _postCurl($url, $post_data) {
		$cookie = '';
		if($_COOKIE['insenz_session_id']) {
			$cookies .= (session_name()) . "=" . urlencode($_COOKIE['insenz_session_id']) . ";";
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
		$result = curl_exec($ch);
		$errorMsg = curl_error($ch);
		if(!empty($errorMsg)) {
			return new VideoClient_Result(array('status' => -1 , 'errMessage' => $errorMsg ));
		}
		if(is_resource($ch)) {
			curl_close($ch);
		}
		return $result;
	}

	function _postSock($url, $post_data) {
		if(!function_exists('fsockopen')) {
			return new VideoClient_Result(array('status' => -1));
		}
		$urlInfo = array();
		$urlInfo = parse_url($url);
		$port = (0 == strcasecmp($urlInfo['scheme'], 'https')) ? 443 : 80;
		$httpHeader = "POST " . $urlInfo['path'] . '?' . $urlInfo['query'] . ' ' .  strtoupper($urlInfo['scheme']) . "/1.0\r\n";
		$httpHeader .= "Host: " . $urlInfo['host'] . " \r\n";
		$httpHeader .= "Connection: Close\r\n";
		$httpHeader .= "Content-type: application/x-www-form-urlencoded\r\n";
		$httpHeader .= "Content-length: " . strlen($post_data) . "\r\n";
		$httpHeader .= "Referer: " . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"] . "\r\n";
		$cookie = '';
		if($_COOKIE['insenz_session_id']) {
			$cookies .= (session_name()) . "=" . urlencode($_COOKIE['insenz_session_id']) . ";";
		}
		$httpHeader .= "Cookie: " . $cookies . "\r\n";
		$httpHeader .= "\r\n";
		$httpHeader .= $post_data;
		$httpHeader .= "\r\n\r\n";

		$fp = @fsockopen($urlInfo['host'], $port);
		if($fp) {
			if(fwrite($fp, $httpHeader)) {
				$resData = '';
				while(!feof($fp)) {
					$resData .= fread($fp, 8192);
				}
				fclose($fp);
				$resContent =  substr(strstr($resData, "\r\n\r\n"), 4);
				return $resContent;
			} else {
				return new VideoClient_Result(array('status' => -1));
			}
		} else {
			return new VideoClient_Result(array('status' => -1));
		}
	}

	function _doCall($mod, $params) {

		$tmpArgs = array();
		$url = '';
		if($this->appId) {
			$params['appId'] = $this->appId;
		}
		$params['method'] = $mod;
		$params['version'] = $this->version;
		foreach($params as $paramName => $paramValue) {
			$tmpArgs[] = urlencode($paramName) . '=' . urlencode($paramValue);
		}
		$args = join('&', $tmpArgs);
		$args .= '&sign=' . $this->_sign($args);
		$tmpArgs = $args;
		$url = $this->apiServerUrl . '?siteId=' . (($params['siteId']) ? $params['siteId'] : '0');
		$resultString = '';
		$result = array();
		$args = urlencode($this->_encrypt(urlencode($args), $this->key));

		if(function_exists('curl_init')) {
			$resultString = $this->_postCurl($url, $args);
		} else {
			$resultString = $this->_postSock($url, $args);
		}

		if(is_object($resultString)) {
			return $resultString;
		}
		if(empty($resultString)) {
			return new VideoClient_Result(array('status' => -1));
		}
		$tmp = $resultString;
		$decodeString = $this->_decrypt($resultString, $this->key);
		if($decodeString) {
			$resultString = $decodeString;
		}
		$resultString = urldecode($resultString);

		parse_str($resultString, $result);
		if(!is_array($result)) {
			return new VideoClient_Result(array('status' => -1));
		}
		return new VideoClient_Result($result);
	}

	function isError($result) {
		return $result['status'] != 0 || $result['status'] === NULL;
	}

	function getCode($result) {
		return $result['errCode'];
	}

	function getMessage($result) {
		return $result['errMessage'];
	}
}

class VideoClient_AccountService extends VideoClient {
	var $handle;
	var $passwd;

	function VideoClient_AccountService($appId, $handle = false, $passwd = false) {
		parent::VideoClient($appId);
		$this->handle = $handle;
		$this->passwd = $passwd;
	}

	function handleIsExists($handle) {
		if(!$handle) {
			$handle = $this->handle;
		}
		return $this->_doCall('account_check', array('handle' => $handle));
	}

	function register($handle, $passwd, $code, $email = '', $name = '', $qq = '', $msn = '', $tel= '', $mobile = '', $addr = '' , $postcode = '') {
		$result = $this->_doCall('account_register', array('handle' => $handle, 'passwd' => $passwd, 'code' => $code, 'email' => $email, 'name' => $name, 'qq' => $qq, 'msn' => $msn, 'tel' => $tel, 'mobile' => $mobile, 'addr' => $addr, 'postcode' => $postcode));
		if(!$result->isError()) {
			$this->handle = $handle;
			$this->passwd = $passwd;
		}
		return $result;
	}

	function bind($uniqKey, $siteName, $siteUrl, $logoUrl, $cateId) {
		return $this->_doCall('account_bind', array('handle' => $this->handle, 'passwd' => $this->passwd, 'uniqKey' => $uniqKey, 'siteName' => $siteName, 'siteUrl' => $siteUrl, 'logoUrl' => $logoUrl, 'cateId' => $cateId));
	}

	function getPmNum(){
		$secuKey = '1oidjoqfuikj3487vl8k3lsfdo399ckl4kvzp';
		$sk = md5($secuKey.$this->handle);
		return $this->_doCall('pm_number', array('handle' => $this->handle, 'sk' => $sk, 'appid' => $this->appId));
	}
}

class VideoClient_SiteService extends VideoClient {
	var $siteId = '';
	var $siteKey = '';

	function VideoClient_SiteService($appId, $siteId, $siteKey) {
		parent::VideoClient($appId);
		$this->siteId = $siteId;
		$this->siteKey = $siteKey;
		$this->key = $siteKey;
	}

	function edit($siteName, $siteUrl, $logoUrl, $cateId) {
		return $this->_doCall('site_edit', array('siteId' => $this->siteId, 'siteKey' => $this->siteKey, 'siteName' => $siteName, 'siteUrl' => $siteUrl, 'logoUrl' => $logoUrl, 'cateId' => $cateId));
	}

	function getInfo($uniqKey) {
		return $this->_doCall('site_info', array('siteId' => $this->siteId, 'siteKey' => $this->siteKey, 'uniqKey' => $uniqKey));
	}
}

class VideoClient_VideoService extends VideoClient {
	var $siteId = '';
	var $siteKey = '';

	function VideoClient_VideoService($appId, $siteId, $siteKey) {
		parent::VideoClient($appId);
		$this->siteId = $siteId;
		$this->siteKey = $siteKey;
		$this->key = $siteKey;
	}

	function upload($videoId, $tid, $isup = VIDEO_ISUP_UPLOAD, $subject, $tags, $description, $cateId, $autoplay = VIDEO_AUOT_PLAY_TRUE, $share = VIDEO_SHARE_FALSE) {
		$videoData[] = array( 'videoId' => $videoId, 'tid' => $tid,'isup' => $isup, 'subject' => $subject, 'tags' => $tags, 'description' => $description,
							'category' => $cateId, 'autoplay' => $autoplay, 'share' => $share);
		$results = $this->uploadMulti($videoData);
		return ($result = $results->get('results')) ? $result[0] : $results;
	}

	function getInfo($videoId) {
		return $this->_doCall('video_info', array('siteId' => $this->siteId, 'siteKey' => $this->siteKey, 'videoId' => $videoId));
	}

	function uploadMulti($videoArr = array()) {
		$data = array('siteId' => $this->siteId, 'siteKey' => $this->siteKey);
		foreach($videoArr as $index => $video) {
			foreach($video as $key => $item) {
				$data["videoData[$index][$key]"] = $item;
			}
		}
		return $this->_doCall('video_uploads', $data);
	}

	function edit($videoId, $tid, $subject, $tags, $description, $cateId, $autoplay = VIDEO_AUOT_PLAY_TRUE, $share = VIDEO_SHARE_FALSE) {
		$videoData[] = array( 'videoId' => $videoId, 'tid' => $tid, 'subject' => $subject, 'tags' => $tags, 'description' => $description,
							'category' => $cateId, 'autoplay' => $autoplay, 'share' => $share);
		$results = $this->editMulti($videoData);
		return ($result = $results->get('results')) ? $result[0] : $results;
	}

	function editMulti($videoArr) {
		$data = array('siteId' => $this->siteId, 'siteKey' => $this->siteKey);
		foreach($videoArr as $index => $video) {
			foreach($video as $key => $item) {
				$data["videoData[$index][$key]"] = $item;
			}
		}
		return $this->_doCall('video_edit', $data);
	}

	function remove($videoId) {
		$videoData[] = array( 'videoId' => $videoId);
		$results = $this->removeMulti($videoData);
		return ($result = $results->get('results')) ? $result[0] : $results;
	}

	function removeMulti($videoArr) {
		$data = array('siteId' => $this->siteId, 'siteKey' => $this->siteKey);
		foreach($videoArr as $index => $video) {
			foreach($video as $key => $item) {
				$data["videoData[$index][$key]"] = $item;
			}
		}
		return $this->_doCall('video_remove', $data);
	}
}

class VideoClient_Util extends VideoClient {
	var $siteId = '';
	var $siteKey = '';
	var $flashKey ='87ed8f664329817c99bb02e08db9eeb9ade7981d44f2b50a5ac6461dfde3d95d';

	function VideoClient_Util($appId, $siteId = '', $siteKey = '') {
		parent::VideoClient($appId);
		$this->siteId  =  $siteId;
		$this->siteKey = $siteKey;
	}

	function createUploadFrom($options, $flashVars) {
		if(!$this->siteId || !$this->siteKey) {
			return FALSE;
		}
		$time = time();
		$flashVars['sid'] = $this->siteId;
		$flashVars['ts']  = $time;
		$flashVars['sk']  = md5($this->siteId . $this->siteKey . $this->flashKey . $time);
		$js = '';
		foreach($flashVars as $key => $value) {
			$js .= "\t\tso.addVariable('" . $key . "', '" . $value . "');\n";
			$varArr[] =  $key . '=' . $value;
		}
		$flashVarStr = join('&', $varArr);
		(!$options['width']) && $options['width'] = 480;
		(!$options['height']) && $options['height'] = 60;
		(!$options['id']) && $options['id'] = 'flashUpload';
		$SERVER_HOST = SERVER_HOST;
		$PIC_SERVER_HOST = PIC_SERVER_HOST;
$html = <<<EOF
<div id="video_uploader_wrap">
<div id="video_uploader_div"></div>
<script type="text/javascript" reload="1">
function video_uploader_show() {
	var so = new SWFObject('http://$SERVER_HOST/flash/video_uploader.swf','video_uploader','$options[width]','$options[height]','9.0.0', '#ffffff', 'high', 'http://www.macromedia.com/go/getflashplayer', 'http://www.macromedia.com/go/getflashplayer');
	so.addParam('allowfullscreen','true');
	so.addParam('allowscriptaccess','always');
	so.addParam('wmode','transparent');
	so.addParam('scale', 'noScale');
	$js
	if(!so.write('video_uploader_div'))document.getElementById("video_uploader_div").innerHTML='<span align="center" valign="middle"><a href="http://www.macromedia.com/go/getflashplayer" target=_blank><img src="http://$PIC_SERVER_HOST/flashdownload.jpg" width="468" height="370" border="0" /></a></span>';
	window.focus();
	if(document.getElementById('video_uploader')) {window.video_uploader = document.getElementById('video_uploader');}
}
</script>
</div>
EOF;
		return $html;
	}

	function createPlayer($options, $flashVars) {
		if(!$this->siteId || !$this->siteKey || !$this->appId || !$flashVars['site']) {
			return FALSE;
		}

		$time = time();
		$flashVars['m'] = 'play';
		$flashVars['istyle'] = 'shallow_blue';
		$flashVars['sid'] = $this->siteId;
		$flashVars['ts'] = $time;
		$flashVars['sk'] = md5($this->siteId . $this->siteKey . $this->flashKey . $time);
		$flashVars['site'] = trim($flashVars['site'], '/') . '/';
		$js = '';
		foreach($flashVars as $key => $value) {
			$js .= "\t\tso.addVariable('" . $key . "', '" . $value . "');\n";
			$varArr[] =  $key . '=' .$value;
		}
		$flashVarStr = join('&', $varArr);
		(!$options['width']) && $options['width'] = 480;
		(!$options['height']) && $options['height'] = 410;
		(!$options['id']) && $options['id'] = 'flashPlayer';
		$SERVER_HOST = SERVER_HOST;
		$PIC_SERVER_HOST = PIC_SERVER_HOST;
$html = <<<EOF
<div id="video_Player_wrap">
<div id='video_player_div'></div>
<script type="text/javascript" reload="1">
	var so = new SWFObject('http://$SERVER_HOST/flash/FLVPlayer2.swf','video_player','$options[width]','$options[height]','8.0.0', '#ffffff', 'high', 'http://www.macromedia.com/go/getflashplayer', 'http://www.macromedia.com/go/getflashplayer');
	so.addParam('allowfullscreen','true');
	so.addParam('allowscriptaccess','always');
	so.addParam('scale', 'noScale');
	$js
	if(!so.write('video_player_div'))document.getElementById("video_player_div").innerHTML='<span align="center" valign="middle"><a href="http://www.macromedia.com/go/getflashplayer" target=_blank><img src="http://$PIC_SERVER_HOST/flashdownload.jpg" width="468" height="370" border="0" /></a></span>';
	window.focus();
	if(document.getElementById('video_player')) {window.video_uploader = document.getElementById('video_player');}
</script>
</div>
EOF;
	return $html;
	}

	function createReferPlayer($params = array()) {
		unset($params['ts']);
		unset($params['ks']);
		unset($params['sid']);
		$params['refer'] = 1;
		foreach($params as $key => $value) {
			$query[] = urlencode($key).'='.urlencode($value);
		}
		$time = time();
		$sk   = md5($this->siteId . $this->siteKey . $this->flashKey . $time);
		return 'http://'.SERVER_HOST.'/flash/FLVPlayer2.swf?'.join('&', $query).'&ts='.$time.'&sid='.$this->siteId.'&sk='.$sk;
	}

	function createCode() {
		$httpHeader = "GET /video_api.php?m=seccode HTTP/1.0\r\n";
		$httpHeader .= "Host: " . SERVER_HOST . " \r\n";
		$httpHeader .= "Connection: Close\r\n\r\n";
		$fp = fsockopen(SERVER_HOST, 80);
		if($fp) {
			if(fwrite($fp, $httpHeader)) {
				$resData = '';
				while(!feof($fp)) {
					$resData .= fread($fp, 8192);
				}
				fclose($fp);
				$sessionstr = strstr($resData, "insenz_session_id=");
				$sessionid = substr($sessionstr, 18, 32);
				setCookie('insenz_session_id', $sessionid, (time() + 600), '/');
				$resContent =  substr(strstr($resData, "\r\n\r\n"), 4);
				return $resContent;
			}
		}
	}

	function getThumbUrl($vid, $type = '') {
		$queryStr = 'id=' . $vid;
		if($type == 'small') {
			$queryStr .= '&type=small';
		}
		$url = 'http://' . PIC_SERVER_HOST . '/thumb?%s';
		return sprintf($url, $queryStr);
	}

}

?>