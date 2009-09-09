<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: advcache.php 16698 2008-11-14 07:58:56Z cnteacher $
*/

error_reporting(0);

define('IN_DISCUZ', TRUE);
define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -3));
$timestamp = time();

@set_time_limit(1000);
@ignore_user_abort(TRUE);

require_once DISCUZ_ROOT.'./config.inc.php';
require_once DISCUZ_ROOT.'./include/db_'.$database.'.class.php';
require_once DISCUZ_ROOT.'./include/insenz.func.php';

$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

$query = $db->query("SELECT value FROM {$tablepre}settings WHERE variable='insenz'");
$insenz = ($insenz = $db->result($query, 0)) ? unserialize($insenz) : array();
$insenz['host'] = empty($insenz['host']) ? 'api.insenz.com' : $insenz['host'];
$insenz['url'] = empty($insenz['url']) ? 'api.insenz.com' : $insenz['url'];
$insenz['siteid'] = intval($insenz['siteid']);

if(empty($insenz['authkey'])) {
	exit;
}

$type = intval($_GET['type']);
$cid = intval($_GET['cid']);
if(empty($type)) {

	if(empty($insenz['hardadstatus']) || (!empty($insenz['lastupdated']) && ($timestamp - $insenz['lastupdated'] < 600))) {
		exit;
	}

	$response = insenz_request('adv.php', '<cmd id="getSiteAdJs"><site_id>'.$insenz['siteid'].'</site_id></cmd>');
	$hash = $response[0]['hash'][0]['VALUE'];
	$updateadvcache = FALSE;

	if($insenz['hash'] != $hash) {

		$updateadvcache = TRUE;
		$db->query("TRUNCATE TABLE {$tablepre}advcaches");

		if(is_array($response[0]['ads'][0]['ad'])) {

			$discuz_chs = '';
			$typearray = array(0 => 'insenz', 1 => 'headerbanner', 2 => 'thread3_1', 3 => 'thread2_1', 4 => 'thread1_1', 5 => 'interthread', 6 => 'footerbanner1', 7 => 'footerbanner2', 8 => 'footerbanner3');

			foreach($response[0]['ads'][0]['ad'] AS $k => $v) {
				$type = intval($v['typeid'][0]['VALUE']);
				$target = intval($v['target'][0]['VALUE']);
				$code = insenz_convert($v['code'][0]['VALUE'], 0);
				$db->query("INSERT INTO {$tablepre}advcaches (type, target, code) VALUES ('".$typearray[$type]."', '$target', '$code')");
			}

		}

	}

	$insenz['lastupdated'] = $timestamp;
	$insenz['hash'] = $hash;
	$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");

	require_once DISCUZ_ROOT.'./include/global.func.php';
	require_once DISCUZ_ROOT.'./include/cache.func.php';
	updatecache($updateadvcache ? array('settings', 'advs_forumdisplay', 'advs_viewthread') : 'settings');

} elseif($type == 4 && $cid) {

	$campaign = $db->fetch_first("SELECT autoupdate, lastupdated FROM {$tablepre}campaigns WHERE id='$cid' AND type=4");
	if(!$campaign || !$campaign['autoupdate'] || ($timestamp - $campaign['lastupdated'] < 600)) {
		exit;
	}

	$response = insenz_request('forum.php', '<cmd id="getForumDetails"><c_id>'.$cid.'</c_id></cmd>');
	$threads = intval($response[0]['threads'][0]['VALUE']);
	$posts = intval($response[0]['posts'][0]['VALUE']);
	$lastpost = insenz_convert($response[0]['lastpost'][0]['VALUE'], 0);
	$db->query("UPDATE {$tablepre}virtualforums SET threads='$threads', posts='$posts', lastpost='$lastpost' WHERE cid='$cid'");
	$db->query("UPDATE {$tablepre}campaigns SET lastupdated='$timestamp' WHERE id='$cid'");

} elseif(in_array($type, array(1, 2, 3)) && $cid) {

	$campaign = $db->fetch_first("SELECT tid, autoupdate, lastupdated FROM {$tablepre}campaigns WHERE id='$cid' AND type='$type'");
	if(!$campaign || !$campaign['autoupdate'] || ($timestamp - $campaign['lastupdated'] < 600)) {
		exit;
	}

	$response = insenz_request('campaign.php', '<cmd id="getTopicDetails"><c_id>'.$cid.'</c_id></cmd>');
	$replies = intval($response[0]['replies'][0]['VALUE']);
	$views = intval($response[0]['views'][0]['VALUE']);
	$lastpost = intval($response[0]['lastpost'][0]['VALUE']);
	$lastposter = insenz_convert($response[0]['lastposter'][0]['VALUE'], 0);
	$db->query("UPDATE {$tablepre}threads SET replies='$replies', views='$views', lastpost='$lastpost', lastposter='$lastposter' WHERE tid='$campaign[tid]'");
	$db->query("UPDATE {$tablepre}campaigns SET lastupdated='$timestamp' WHERE id='$cid'");

}

function insenz_request($script, $data) {
	global $timestamp, $insenz;

	@include_once DISCUZ_ROOT.'./discuz_version.php';

	$authkey = $insenz['authkey'];
	$t_hex = sprintf("%08x", $timestamp);
	$postdata = '<?xml version="1.0" encoding="UTF'.'-8"?><request insenz_version="'.INSENZ_VERSION.'" discuz_version="'.DISCUZ_VERSION.' - '.DISCUZ_RELEASE.'">'.$data.'</request>';

	$postdata = insenz_authcode($t_hex.md5($authkey.$postdata.$t_hex).$postdata, 'ENCODE', $authkey);

	if(!$fp = @fsockopen($insenz['host'], 80)) {
		exit;
	}

	@fwrite($fp, "POST http://$insenz[url]/$script?s_id=$insenz[siteid] HTTP/1.0\r\n");
	@fwrite($fp, "Host: $insenz[host]\r\n");
	@fwrite($fp, "Content-Type: file\r\n");
	@fwrite($fp, "Content-Length: " . strlen($postdata) ."\r\n\r\n");
	@fwrite($fp, $postdata);

	$res = '';
	$isheader = 1;
	while(!feof($fp)) {
		$buffer = @fgets($fp, 1024);
		if(!$isheader) {
			$res .= $buffer;
		} elseif(trim($buffer) == '') {
			$isheader = 0;
		}
	}

	@fclose($fp);

	if(empty($res)) {
		exit;
	}

	if(!$response = insenz_authcode($res, 'DECODE', $authkey)) {
		exit;
	}

	$checkKey = substr($response, 0, 40);
	$response = substr($response, 40);
	$t_hex = substr($checkKey, 0, 8);
	$t = base_convert($t_hex, 16, 10);

	if(abs($timestamp - $t) > 1200) {
		exit;
	} elseif($checkKey != $t_hex.md5($authkey.$response.$t_hex)) {
		exit;
	}

	require_once DISCUZ_ROOT.'./include/xmlparser.class.php';

	$xmlparse = new XMLParser;
	$xmlparseddata = $xmlparse->getXMLTree($response);
	unset($response);

	if(!is_array($xmlparseddata) || !is_array($xmlparseddata['response'])) {
		exit;
	}
	return $xmlparseddata['response'];
}

?>