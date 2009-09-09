<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: global.func.php 17460 2008-12-24 01:46:38Z monkey $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;
	$key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
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
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function clearcookies() {
	global $discuz_uid, $discuz_user, $discuz_pw, $discuz_secques, $adminid, $credits;
	foreach(array('sid', 'auth', 'visitedfid', 'onlinedetail', 'loginuser', 'activationauth') as $k) {
		dsetcookie($k);
	}
	$discuz_uid = $adminid = $credits = 0;
	$discuz_user = $discuz_pw = $discuz_secques = '';
}

function checklowerlimit($creditsarray, $coef = 1) {
	if(is_array($creditsarray)) {
		global $extcredits, $id;
		foreach($creditsarray as $id => $addcredits) {
			$addcredits = $addcredits * $coef;
			if($addcredits < 0 && ($GLOBALS['extcredits'.$id] < $extcredits[$id]['lowerlimit'] || (($GLOBALS['extcredits'.$id] + $addcredits) < $extcredits[$id]['lowerlimit']))) {
				if($coef == 1) {
					showmessage('credits_policy_lowerlimit');
				} else {
					showmessage('credits_policy_num_lowerlimit');
				}
			}
		}
	}
}


function checkmd5($md5, $verified, $salt = '') {
	if(md5($md5.$salt) == $verified) {
		$result = !empty($salt) ? 1 : 2;
	} elseif(empty($salt)) {
		$result = $md5 == $verified ? 3 : ((strlen($verified) == 16 && substr($md5, 8, 16) == $verified) ? 4 : 0);
	} else {
		$result = 0;
	}
	return $result;
}

function checktplrefresh($maintpl, $subtpl, $timecompare, $templateid, $tpldir) {
	global $tplrefresh;
	if(empty($timecompare) || $tplrefresh == 1 || ($tplrefresh > 1 && !($GLOBALS['timestamp'] % $tplrefresh))) {
		if(empty($timecompare) || @filemtime($subtpl) > $timecompare) {
			require_once DISCUZ_ROOT.'./include/template.func.php';
			parse_template($maintpl, $templateid, $tpldir);
			return TRUE;
		}
	}
	return 	FALSE;
}

function cutstr($string, $length, $dot = ' ...') {
	global $charset;

	if(strlen($string) <= $length) {
		return $string;
	}

	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	if(strtolower($charset) == 'utf-8') {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}

function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function datecheck($ymd, $sep='-') {
	if(!empty($ymd)) {
		list($year, $month, $day) = explode($sep, $ymd);
		return checkdate($month, $day, $year);
	} else {
		return FALSE;
	}
}

function debuginfo() {
	if($GLOBALS['debug']) {
		global $db, $discuz_starttime, $debuginfo;
		$mtime = explode(' ', microtime());
		$debuginfo = array('time' => number_format(($mtime[1] + $mtime[0] - $discuz_starttime), 6), 'queries' => $db->querynum);
		return TRUE;
	} else {
		return FALSE;
	}
}

function dexit($message = '') {
	echo $message;
	output();
	exit();
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

function dhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val);
		}
	} else {
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1',
		//$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return $string;
}

function dheader($string, $replace = true, $http_response_code = 0) {
	$string = str_replace(array("\r", "\n"), array('', ''), $string);
	if(empty($http_response_code) || PHP_VERSION < '4.3' ) {
		@header($string, $replace);
	} else {
		@header($string, $replace, $http_response_code);
	}
	if(preg_match('/^\s*location:/is', $string)) {
		exit();
	}
}

function dreferer($default = '') {
	global $referer, $indexname;

	$default = empty($default) ? $indexname : '';
	if(empty($referer) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
		$referer = preg_replace("/([\?&])((sid\=[a-z0-9]{6})(&|$))/i", '\\1', $GLOBALS['_SERVER']['HTTP_REFERER']);
		$referer = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
	} else {
		$referer = dhtmlspecialchars($referer);
	}

	if(!preg_match("/(\.php|[a-z]+(\-\d+)+\.html)/", $referer) || strpos($referer, 'logging.php')) {
		$referer = $default;
	}
	return $referer;
}

function dsetcookie($var, $value = '', $life = 0, $prefix = 1, $httponly = false) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	$var = ($prefix ? $cookiepre : '').$var;
	if($value == '' || $life < 0) {
		$value = '';
		$life = -1;
	}
	$life = $life > 0 ? $timestamp + $life : ($life < 0 ? $timestamp - 31536000 : 0);
	$path = $httponly && PHP_VERSION < '5.2.0' ? "$cookiepath; HttpOnly" : $cookiepath;
	$secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
	if(PHP_VERSION < '5.2.0') {
		setcookie($var, $value, $life, $path, $cookiedomain, $secure);
	} else {
		setcookie($var, $value, $life, $path, $cookiedomain, $secure, $httponly);
	}
}

function dunlink($filename, $havethumb = 0, $remote = 0) {
	global $authkey, $ftp, $attachdir;
	if($remote) {
		require_once DISCUZ_ROOT.'./include/ftp.func.php';
		if(!$ftp['connid']) {
			if(!($ftp['connid'] = dftp_connect($ftp['host'], $ftp['username'], authcode($ftp['password'], 'DECODE', md5($authkey)), $ftp['attachdir'], $ftp['port'], $ftp['ssl']))) {
				return;
			}
		}
		dftp_delete($ftp['connid'], $filename);
		$havethumb && dftp_delete($ftp['connid'], $filename.'.thumb.jpg');
	} else {
		@unlink($attachdir.'/'.$filename);
		$havethumb && @unlink($attachdir.'/'.$filename.'.thumb.jpg');
	}
}

function dgmdate($format, $timestamp, $convert = 1) {
	$s = gmdate($format, $timestamp);
	if($GLOBALS['dateconvert'] && $convert) {
		if($GLOBALS['discuz_uid']) {
			if(!isset($GLOBALS['disableddateconvert'])) {
				$customshow = str_pad(base_convert($GLOBALS['customshow'], 10, 3), 4, '0', STR_PAD_LEFT);
				$GLOBALS['disableddateconvert'] = $customshow{0};
			}
			if($GLOBALS['disableddateconvert']) {
				return $s;
			}
		}
		if(!isset($GLOBALS['todaytimestamp'])) {
			$GLOBALS['todaytimestamp'] = $GLOBALS['timestamp'] - ($GLOBALS['timestamp'] + $GLOBALS['timeoffset'] * 3600) % 86400 + $GLOBALS['timeoffset'] * 3600;
		}
		$lang = $GLOBALS['dlang']['date'];
		$time = $GLOBALS['timestamp'] + $GLOBALS['timeoffset'] * 3600 - $timestamp;
		if($timestamp >= $GLOBALS['todaytimestamp']) {
			if($time > 3600) {
				return '<span title="'.$s.'">'.intval($time / 3600).'&nbsp;'.$lang[4].$lang[0].'</span>';
			} elseif($time > 1800) {
				return '<span title="'.$s.'">'.$lang[5].$lang[4].$lang[0].'</span>';
			} elseif($time > 60) {
				return '<span title="'.$s.'">'.intval($time / 60).'&nbsp;'.$lang[6].$lang[0].'</span>';
			} elseif($time > 0) {
				return '<span title="'.$s.'">'.$time.'&nbsp;'.$lang[7].$lang[0].'</span>';
			} elseif($time == 0) {
				return '<span title="'.$s.'">'.$lang[8].'</span>';
			} else {
				return $s;
			}
		} elseif(($days = intval(($GLOBALS['todaytimestamp'] - $timestamp) / 86400)) >= 0 && $days < 7) {
			if($days == 0) {
				return '<span title="'.$s.'">'.$lang[2].'&nbsp;'.gmdate($GLOBALS['timeformat'], $timestamp).'</span>';
			} elseif($days == 1) {
				return '<span title="'.$s.'">'.$lang[3].'&nbsp;'.gmdate($GLOBALS['timeformat'], $timestamp).'</span>';
			} else {
				return '<span title="'.$s.'">'.($days + 1).'&nbsp;'.$lang[1].$lang[0].'&nbsp;'.gmdate($GLOBALS['timeformat'], $timestamp).'</span>';
			}
		} else {
			return $s;
		}
	} else {
		return $s;
	}
}

function errorlog($type, $message, $halt = 1) {
	global $timestamp, $discuz_userss, $onlineip, $_SERVER;
	$user = empty($discuz_userss) ? '' : $discuz_userss.'<br />';
	$user .= $onlineip.'|'.$_SERVER['REMOTE_ADDR'];
	writelog('errorlog', dhtmlspecialchars("$timestamp\t$type\t$user\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($message))));
	if($halt) {
		exit();
	}
}

function fileext($filename) {
	return trim(substr(strrchr($filename, '.'), 1, 10));
}

function formhash($specialadd = '') {
	global $discuz_user, $discuz_uid, $discuz_pw, $timestamp, $discuz_auth_key;
	$hashadd = defined('IN_ADMINCP') ? 'Only For Discuz! Admin Control Panel' : '';
	return substr(md5(substr($timestamp, 0, -7).$discuz_user.$discuz_uid.$discuz_pw.$discuz_auth_key.$hashadd.$specialadd), 8, 8);
}

function forumperm($permstr) {
	global $groupid, $extgroupids;

	$groupidarray = array($groupid);
	foreach(explode("\t", $extgroupids) as $extgroupid) {
		if($extgroupid = intval(trim($extgroupid))) {
			$groupidarray[] = $extgroupid;
		}
	}
	return preg_match("/(^|\t)(".implode('|', $groupidarray).")(\t|$)/", $permstr);
}

function formulaperm($formula, $type = 0) {
	global $_DSESSION, $extcredits, $formulamessage, $usermsg, $forum, $language;

	if((!$formula || $_DSESSION['adminid'] == 1 || $forum['ismoderator']) && !$type) {
		return;
	}
	$formula = unserialize($formula);$formula = $formula[1];
	if(!$formula) {
		return;
	}
	@eval("\$formulaperm = ($formula) ? TRUE : FALSE;");
	if(!$formulaperm || $type == 2) {
		include_once language('misc');
		$search = array('$_DSESSION[\'digestposts\']', '$_DSESSION[\'posts\']', '$_DSESSION[\'oltime\']', '$_DSESSION[\'pageviews\']');
		$replace = array($language['formulaperm_digestposts'], $language['formulaperm_posts'], $language['formulaperm_oltime'], $language['formulaperm_pageviews']);
		for($i = 1; $i <= 8; $i++) {
			$search[] = '$_DSESSION[\'extcredits'.$i.'\']';
			$replace[] = $extcredits[$i]['title'] ? $extcredits[$i]['title'] : $language['formulaperm_extcredits'].$i;
		}
		$i = 0;$usermsg = '';
		foreach($search as $s) {
			$usermsg .= strexists($formula, $s) ? $replace[$i].' = '.(@eval('return intval('.$s.');')).'&nbsp;&nbsp;&nbsp;' : '';
			$i++;
		}
		$search = array_merge($search, array('and', 'or', '>=', '<='));
		$replace = array_merge($replace, array('&nbsp;&nbsp;'.$language['formulaperm_and'].'&nbsp;&nbsp;', '&nbsp;&nbsp;'.$language['formulaperm_or'].'&nbsp;&nbsp;', '&ge;', '&le;'));
		$formulamessage = str_replace($search, $replace, $formula);

		if($type == 1) {
			showmessage('medal_permforum_nopermission', NULL, 'NOPERM');
		} elseif($type == 2) {
			return $formulamessage;
		} else {
			showmessage('forum_permforum_nopermission', NULL, 'NOPERM');
		}
	}
	return TRUE;
}

function getgroupid($uid, $group, &$member) {
	global $creditsformula, $db, $tablepre;

	if(!empty($creditsformula)) {
		$updatearray = array();
		eval("\$credits = round($creditsformula);");

		if($credits != $member['credits']) {
			$updatearray[] = "credits='$credits'";
			$member['credits'] = $credits;
		}
		if($group['type'] == 'member' && !($member['credits'] >= $group['creditshigher'] && $member['credits'] < $group['creditslower'])) {
			$query = $db->query("SELECT groupid FROM {$tablepre}usergroups WHERE type='member' AND $member[credits]>=creditshigher AND $member[credits]<creditslower LIMIT 1");
			if($db->num_rows($query)) {
				$member['groupid'] = $db->result($query, 0);
				$updatearray[] = "groupid='$member[groupid]'";
			}
		}

		if($updatearray) {
			$db->query("UPDATE {$tablepre}members SET ".implode(', ', $updatearray)." WHERE uid='$uid'");
		}
	}

	return $member['groupid'];
}

function getrobot() {
	if(!defined('IS_ROBOT')) {
		$kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
		$kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
		if(!strexists($_SERVER['HTTP_USER_AGENT'], 'http://') && preg_match("/($kw_browsers)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', FALSE);
		} elseif(preg_match("/($kw_spiders)/i", $_SERVER['HTTP_USER_AGENT'])) {
			define('IS_ROBOT', TRUE);
		} else {
			define('IS_ROBOT', FALSE);
		}
	}
	return IS_ROBOT;
}

function get_home($uid) {
	$uid = sprintf("%05d", $uid);
	$dir1 = substr($uid, 0, -4);
	$dir2 = substr($uid, -4, 2);
	$dir3 = substr($uid, -2, 2);
	return $dir1.'/'.$dir2.'/'.$dir3;
}

function groupexpiry($terms) {
	$terms = is_array($terms) ? $terms : unserialize($terms);
	$groupexpiry = isset($terms['main']['time']) ? intval($terms['main']['time']) : 0;
	if(is_array($terms['ext'])) {
		foreach($terms['ext'] as $expiry) {
			if((!$groupexpiry && $expiry) || $expiry < $groupexpiry) {
				$groupexpiry = $expiry;
			}
		}
	}
	return $groupexpiry;
}

function ipaccess($ip, $accesslist) {
	return preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($accesslist, '/')).")/", $ip);
}

function implodeids($array) {
	if(!empty($array)) {
		return "'".implode("','", is_array($array) ? $array : array($array))."'";
	} else {
		return '';
	}
}

function ipbanned($onlineip) {
	global $ipaccess, $timestamp, $cachelost;

	if($ipaccess && !ipaccess($onlineip, $ipaccess)) {
		return TRUE;
	}

	$cachelost .= (@include DISCUZ_ROOT.'./forumdata/cache/cache_ipbanned.php') ? '' : ' ipbanned';
	if(empty($_DCACHE['ipbanned'])) {
		return FALSE;
	} else {
		if($_DCACHE['ipbanned']['expiration'] < $timestamp) {
			@unlink(DISCUZ_ROOT.'./forumdata/cache/cache_ipbanned.php');
		}
		return preg_match("/^(".$_DCACHE['ipbanned']['regexp'].")$/", $onlineip);
	}
}

function isemail($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

function language($file, $templateid = 0, $tpldir = '') {
	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$languagepack = DISCUZ_ROOT.'./'.$tpldir.'/'.$file.'.lang.php';
	if(file_exists($languagepack)) {
		return $languagepack;
	} elseif($templateid != 1 && $tpldir != './templates/default') {
		return language($file, 1, './templates/default');
	} else {
		return FALSE;
	}
}

function modthreadkey($tid) {
	global $adminid, $discuz_user, $discuz_uid, $discuz_pw, $timestamp, $discuz_auth_key;
	return $adminid > 0 ? md5($discuz_user.$discuz_uid.$discuz_auth_key.substr($timestamp, 0, -7).$tid) : '';
}

function multi($num, $perpage, $curpage, $mpurl, $maxpages = 0, $page = 10, $autogoto = TRUE, $simple = FALSE) {
	global $maxpage;
	$ajaxtarget = !empty($_GET['ajaxtarget']) ? " ajaxtarget=\"".dhtmlspecialchars($_GET['ajaxtarget'])."\" " : '';

	if(defined('IN_ADMINCP')) {
		$shownum = $showkbd = TRUE;
		$lang['prev'] = '&lsaquo;&lsaquo;';
		$lang['next'] = '&rsaquo;&rsaquo;';
	} else {
		$shownum = $showkbd = FALSE;
		$lang['prev'] = '&nbsp';
		$lang['next'] = $GLOBALS['dlang']['nextpage'];
	}

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;

		$realpages = @ceil($num / $perpage);
		$pages = $maxpages && $maxpages < $realpages ? $maxpages : $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1" class="first"'.$ajaxtarget.'>1 ...</a>' : '').
			($curpage > 1 && !$simple ? '<a href="'.$mpurl.'page='.($curpage - 1).'" class="prev"'.$ajaxtarget.'>'.$lang['prev'].'</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a href="'.$mpurl.'page='.$i.($ajaxtarget && $i == $pages && $autogoto ? '#' : '').'"'.$ajaxtarget.'>'.$i.'</a>';
		}

		$multipage .= ($to < $pages ? '<a href="'.$mpurl.'page='.$pages.'" class="last"'.$ajaxtarget.'>... '.$realpages.'</a>' : '').
			($curpage < $pages && !$simple ? '<a href="'.$mpurl.'page='.($curpage + 1).'" class="next"'.$ajaxtarget.'>'.$lang['next'].'</a>' : '').
			($showkbd && !$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$mpurl.'page=\'+this.value; return false;}" /></kbd>' : '');

		$multipage = $multipage ? '<div class="pages">'.($shownum && !$simple ? '<em>&nbsp;'.$num.'&nbsp;</em>' : '').$multipage.'</div>' : '';
	}
	$maxpage = $realpages;
	return $multipage;
}

function output() {
	if(defined('DISCUZ_OUTPUTED')) {
		return;
	}
	define('DISCUZ_OUTPUTED', 1);
	global $sid, $transsidstatus, $rewritestatus, $ftp, $advlist, $insenz, $queryfloat, $thread, $inajax;

	if(($advlist || !empty($insenz['hardadstatus']) || $queryfloat) && !defined('IN_ADMINCP') && !(CURSCRIPT == 'viewthread' && $thread['digest'] == '-1') && !$inajax) {
		include template('adv');
	}

	if(($transsidstatus = empty($GLOBALS['_DCOOKIE']['sid']) && $transsidstatus) || $rewritestatus) {
		if($transsidstatus) {
			$searcharray = array
				(
				"/\<a(\s*[^\>]+\s*)href\=([\"|\']?)([^\"\'\s]+)/ies",
				"/(\<form.+?\>)/is"
				);
			$replacearray = array
				(
				"transsid('\\3','<a\\1href=\\2')",
				"\\1\n<input type=\"hidden\" name=\"sid\" value=\"$sid\" />"
				);
		} else {
			$searcharray = $replacearray = array();
			if($rewritestatus & 1) {
				$searcharray[] = "/\<a href\=\"forumdisplay\.php\?fid\=(\d+)(&amp;page\=(\d+))?\"([^\>]*)\>/e";
				$replacearray[] = "rewrite_forum('\\1', '\\3', '\\4')";
			}
			if($rewritestatus & 2) {
				$searcharray[] = "/\<a href\=\"viewthread\.php\?tid\=(\d+)(&amp;extra\=page\%3D(\d+))?(&amp;page\=(\d+))?\"([^\>]*)\>/e";
				$replacearray[] = "rewrite_thread('\\1', '\\5', '\\3', '\\6')";
			}
			if($rewritestatus & 4) {
				$searcharray[] = "/\<a href\=\"space\.php\?(uid\=(\d+)|username\=([^&]+?))\"([^\>]*)\>/e";
				$replacearray[] = "rewrite_space('\\2', '\\3', '\\4')";
			}
			if($rewritestatus & 8) {
				$searcharray[] = "/\<a href\=\"tag\.php\?name\=([^&]+?)\"([^\>]*)\>/e";
				$replacearray[] = "rewrite_tag('\\1', '\\2')";
			}
		}

		$content = preg_replace($searcharray, $replacearray, ob_get_contents());
		ob_end_clean();
		$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();

		echo $content;
	}
	if($ftp['connid']) {
		@ftp_close($ftp['connid']);
	}
	$ftp = array();

	if(defined('CACHE_FILE') && CACHE_FILE && !defined('CACHE_FORBIDDEN')) {
		global $cachethreaddir;
		if(diskfreespace(DISCUZ_ROOT.'./'.$cachethreaddir) > 1000000) {
			if($fp = @fopen(CACHE_FILE, 'w')) {
				flock($fp, LOCK_EX);
				fwrite($fp, empty($content) ? ob_get_contents() : $content);
			}
			@fclose($fp);
			chmod(CACHE_FILE, 0777);
		}
	}
}

function periodscheck($periods, $showmessage = 1) {
	global $timestamp, $disableperiodctrl, $_DCACHE, $banperiods;

	if(!$disableperiodctrl && $_DCACHE['settings'][$periods]) {
		$now = gmdate('G.i', $timestamp + $_DCACHE['settings']['timeoffset'] * 3600);
		foreach(explode("\r\n", str_replace(':', '.', $_DCACHE['settings'][$periods])) as $period) {
			list($periodbegin, $periodend) = explode('-', $period);
			if(($periodbegin > $periodend && ($now >= $periodbegin || $now < $periodend)) || ($periodbegin < $periodend && $now >= $periodbegin && $now < $periodend)) {
				$banperiods = str_replace("\r\n", ', ', $_DCACHE['settings'][$periods]);
				if($showmessage) {
					showmessage('period_nopermission', NULL, 'NOPERM');
				} else {
					return TRUE;
				}
			}
		}
	}
	return FALSE;
}

function quescrypt($questionid, $answer) {
	return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
}

function rewrite_thread($tid, $page = 0, $prevpage = 0, $extra = '') {
	return '<a href="thread-'.$tid.'-'.($page ? $page : 1).'-'.($prevpage && !IS_ROBOT ? $prevpage : 1).'.html"'.stripslashes($extra).'>';
}

function rewrite_forum($fid, $page = 0, $extra = '') {
	return '<a href="forum-'.$fid.'-'.($page ? $page : 1).'.html"'.stripslashes($extra).'>';
}

function rewrite_space($uid, $username, $extra = '') {
	$GLOBALS['rewritecompatible'] && $username = rawurlencode($username);
	return '<a href="space-'.($uid ? 'uid-'.$uid : 'username-'.$username).'.html"'.stripslashes($extra).'>';
}

function rewrite_tag($name, $extra = '') {
	$GLOBALS['rewritecompatible'] && $name = rawurlencode($name);
	return '<a href="tag-'.$name.'.html"'.stripslashes($extra).'>';
}

function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
	$seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

function request($cachekey, $fid = 0, $type = 0, $return = 0) {
	global $timestamp, $_DCACHE;
	$datalist = '';
	if($fid && CURSCRIPT == 'forumdisplay') {
		$specialfid = $GLOBALS['forum']['fid'];
		$key = $cachekey = empty($GLOBALS['infosidestatus']['f'.$specialfid][$type]) ? $GLOBALS['infosidestatus'][$type] : $GLOBALS['infosidestatus']['f'.$specialfid][$type];
		$cachekey .= '_fid'.$specialfid;
	} else {
		$specialfid = 0;
		if(!$type) {
			$key = $cachekey;
		} else {
			$key = $cachekey = $cachekey[$type];
		}
	}
	$cachefile = DISCUZ_ROOT.'./forumdata/cache/request_'.md5($cachekey).'.php';
	if((@!include($cachefile)) || $expiration < $timestamp) {
		include_once DISCUZ_ROOT.'./forumdata/cache/cache_request.php';
		require_once DISCUZ_ROOT.'./include/request.func.php';
		parse_str($_DCACHE['request'][$key]['url'], $requestdata);
		$datalist = parse_request($requestdata, $cachefile, 0, $specialfid, $key);
	}
	if(!empty($nocachedata)) {
		include_once DISCUZ_ROOT.'./forumdata/cache/cache_request.php';
		require_once DISCUZ_ROOT.'./include/request.func.php';
		foreach($nocachedata as $key => $v) {
			$cachefile = DISCUZ_ROOT.'./forumdata/cache/request_'.md5($key).'.php';
			parse_str($_DCACHE['request'][$key]['url'], $requestdata);
			$datalist = str_replace($v, parse_request($requestdata, $cachefile, 0, $specialfid, $key), $datalist);
		}
	}
	if(!$return) {
		echo $datalist;
	} else {
		return $datalist;
	}
}

function sendmail($email_to, $email_subject, $email_message, $email_from = '') {
	extract($GLOBALS, EXTR_SKIP);
	require DISCUZ_ROOT.'./include/sendmail.inc.php';
}

function sendpm($toid, $subject, $message, $fromid = '') {
	extract($GLOBALS, EXTR_SKIP);
	include language('pms');

	require_once DISCUZ_ROOT.'./uc_client/client.php';

	if(isset($language[$subject])) {
		eval("\$subject = addslashes(\"".$language[$subject]."\");");
	}
	if(isset($language[$message])) {
		eval("\$message = addslashes(\"".$language[$message]."\");");
	}

	if($fromid === '') {
		$fromid = $discuz_uid;
	}
	uc_pm_send($fromid, $toid, $subject, $message);
}

function showmessage($message, $url_forward = '', $extra = '', $forwardtype = 0) {
	extract($GLOBALS, EXTR_SKIP);
	global $extrahead, $discuz_uid, $discuz_action, $debuginfo, $seccode, $seccodestatus, $fid, $tid, $charset, $show_message, $inajax, $_DCACHE, $advlist;
	define('CACHE_FORBIDDEN', TRUE);
	$show_message = $message;$messagehandle = 0;
	$msgforward = unserialize($_DCACHE['settings']['msgforward']);
	$refreshtime = intval($msgforward['refreshtime']);
	$refreshtime = empty($forwardtype) ? $refreshtime : ($refreshtime ? $refreshtime : 3);
	$msgforward['refreshtime'] = $refreshtime * 1000;
	$url_forward = empty($url_forward) ? '' : (empty($_DCOOKIE['sid']) && $transsidstatus ? transsid($url_forward) : $url_forward);
	$seccodecheck = $seccodestatus & 2;

	if($url_forward && (!empty($quickforward) || empty($inajax) && $msgforward['quick'] && $msgforward['messages'] && @in_array($message, $msgforward['messages']))) {
		updatesession();
		dheader("location: ".str_replace('&amp;', '&', $url_forward));
	}
	if(!empty($infloat)) {
		if($extra) {
			$messagehandle = $extra;
		}
		$extra = '';
	}
	if(in_array($extra, array('HALTED', 'NOPERM'))) {
		$fid = $tid = 0;
		$discuz_action = 254;
	} else {
		$discuz_action = 255;
	}

	include language('messages');

	if(isset($language[$message])) {
		$pre = $inajax ? 'ajax_' : '';
		eval("\$show_message = \"".(isset($language[$pre.$message]) ? $language[$pre.$message] : $language[$message])."\";");
		unset($pre);
	}

	if(empty($infloat)) {
		$show_message .= $url_forward && empty($inajax) ? '<script>setTimeout("window.location.href =\''.$url_forward.'\';", '.$msgforward['refreshtime'].');</script>' : '';
	} elseif($handlekey) {
		$show_message = str_replace("'", "\'", $show_message);
		if($url_forward) {
			$show_message = "<script type=\"text/javascript\" reload=\"1\">\nif($('return_$handlekey')) $('return_$handlekey').className = 'onright';\nif(typeof submithandle_$handlekey =='function') {submithandle_$handlekey('$url_forward', '$show_message');} else {location.href='$url_forward'}\n</script>";
		} else {
			$show_message .= "<script type=\"text/javascript\" reload=\"1\">\nif(typeof messagehandle_$handlekey =='function') {messagehandle_$handlekey('$messagehandle', '$show_message');}\n</script>";
		}
	}

	if($advlist = array_merge($globaladvs ? $globaladvs['type'] : array(), $redirectadvs ? $redirectadvs['type'] : array())) {
		$advitems = ($globaladvs ? $globaladvs['items'] : array()) + ($redirectadvs ? $redirectadvs['items'] : array());
		foreach($advlist AS $type => $redirectadvs) {
			$advlist[$type] = $advitems[$redirectadvs[array_rand($redirectadvs)]];
		}
	}

	if($extra == 'NOPERM') {
		include template('nopermission');
	} else {
		include template('showmessage');
	}
	dexit();
}

function site() {
	return $_SERVER['HTTP_HOST'];
}

function strexists($haystack, $needle) {
	return !(strpos($haystack, $needle) === FALSE);
}

function seccodeconvert(&$seccode) {
	global $seccodedata, $charset;
	$seccode = substr($seccode, -6);
	if($seccodedata['type'] == 1) {
		include_once language('seccode');
		$len = strtoupper($charset) == 'GBK' ? 2 : 3;
		$code = array(substr($seccode, 0, 3), substr($seccode, 3, 3));
		$seccode = '';
		for($i = 0; $i < 2; $i++) {
			$seccode .= substr($lang['chn'], $code[$i] * $len, $len);
		}
		return;
	} elseif($seccodedata['type'] == 3) {
		$s = sprintf('%04s', base_convert($seccode, 10, 20));
		$seccodeunits = 'CEFHKLMNOPQRSTUVWXYZ';
	} else {
		$s = sprintf('%04s', base_convert($seccode, 10, 24));
		$seccodeunits = 'BCEFGHJKMPQRTVWXY2346789';
	}
	$seccode = '';
	for($i = 0; $i < 4; $i++) {
		$unit = ord($s{$i});
		$seccode .= ($unit >= 0x30 && $unit <= 0x39) ? $seccodeunits[$unit - 0x30] : $seccodeunits[$unit - 0x57];
	}
}

function submitcheck($var, $allowget = 0, $seccodecheck = 0, $secqaacheck = 0) {
	if(empty($GLOBALS[$var])) {
		return FALSE;
	} else {
		global $_SERVER, $seclevel, $seccode, $seccodedata, $seccodeverify, $secanswer, $_DCACHE, $_DCOOKIE, $timestamp, $discuz_uid;
		if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && $GLOBALS['formhash'] == formhash() && empty($_SERVER['HTTP_X_FLASH_VERSION']) && (empty($_SERVER['HTTP_REFERER']) ||
			preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
        		if($seccodecheck) {
        			if(!$seclevel) {
        				$key = $seccodedata['type'] != 3 ? '' : $_DCACHE['settings']['authkey'].date('Ymd');
        				list($seccode, $expiration, $seccodeuid) = explode("\t", authcode($_DCOOKIE['secc'], 'DECODE', $key));
        				if($seccodeuid != $discuz_uid || $timestamp - $expiration > 600) {
        					showmessage('submit_seccode_invalid');
        				}
        				dsetcookie('secc', '');
        			} else {
        				$tmp = substr($seccode, 0, 1);
        			}
        			seccodeconvert($seccode);
        			if(strtoupper($seccodeverify) != $seccode) {
        				showmessage('submit_seccode_invalid');
        			}
				$seclevel && $seccode = random(6, 1) + $tmp * 1000000;
        		}
			if($secqaacheck) {
        			if(!$seclevel) {
        				list($seccode, $expiration, $seccodeuid) = explode("\t", authcode($_DCOOKIE['secq'], 'DECODE'));
        				if($seccodeuid != $discuz_uid || $timestamp - $expiration > 600) {
        					showmessage('submit_secqaa_invalid');
        				}
        				dsetcookie('secq', '');
        			}
        			require_once DISCUZ_ROOT.'./forumdata/cache/cache_secqaa.php';
        			if(md5($secanswer) != $_DCACHE['secqaa'][substr($seccode, 0, 1)]['answer']) {
        			        showmessage('submit_secqaa_invalid');
        			}
				$seclevel && $seccode = random(1, 1) * 1000000 + substr($seccode, -6);
        		}
			return TRUE;
		} else {
			showmessage('submit_invalid');
		}
	}
}

function template($file, $templateid = 0, $tpldir = '') {
	global $inajax;
	$file .= $inajax && ($file == 'header' || $file == 'footer') ? '_ajax' : '';
	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$tplfile = DISCUZ_ROOT.'./'.$tpldir.'/'.$file.'.htm';
	$objfile = DISCUZ_ROOT.'./forumdata/templates/'.$templateid.'_'.$file.'.tpl.php';
	if($templateid != 1 && !file_exists($tplfile)) {
		$tplfile = DISCUZ_ROOT.'./templates/default/'.$file.'.htm';
	}
	@checktplrefresh($tplfile, $tplfile, filemtime($objfile), $templateid, $tpldir);

	return $objfile;
}

function transsid($url, $tag = '', $wml = 0) {
	global $sid;
	$tag = stripslashes($tag);
	if(!$tag || (!preg_match("/^(http:\/\/|mailto:|#|javascript)/i", $url) && !strpos($url, 'sid='))) {
		if($pos = strpos($url, '#')) {
			$urlret = substr($url, $pos);
			$url = substr($url, 0, $pos);
		} else {
			$urlret = '';
		}
		$url .= (strpos($url, '?') ? ($wml ? '&amp;' : '&') : '?').'sid='.$sid.$urlret;
	}
	return $tag.$url;
}

function typeselect($curtypeid = 0) {
	if($threadtypes = $GLOBALS['forum']['threadtypes']) {
		$html = '<select name="typeid" id="typeid"><option value="0">&nbsp;</option>';
		foreach($threadtypes['types'] as $typeid => $name) {
			$html .= '<option value="'.$typeid.'" '.($curtypeid == $typeid ? 'selected' : '').'>'.strip_tags($name).'</option>';
		}
		$html .= '</select>';
		return $html;
	} else {
		return '';
	}
}

function sortselect($cursortid = 0, $modelid = 0, $onchange = '') {
	global $fid, $sid, $extra;
	if($threadsorts = $GLOBALS['forum']['threadsorts']) {
		$onchange = $onchange ? $onchange : "onchange=\"ajaxget('post.php?action=threadsorts&sortid='+this.options[this.selectedIndex].value+'&fid=$fid&sid=$sid', 'threadsorts', 'threadsortswait')\"";
		$selecthtml = '';
		foreach($threadsorts['types'] as $sortid => $name) {
			$sorthtml = '<option value="'.$sortid.'" '.($cursortid == $sortid ? 'selected="selected"' : '').' class="special">'.strip_tags($name).'</option>';
			$selecthtml .= $modelid ? ($threadsorts['modelid'][$sortid] == $modelid ? $sorthtml : '') : $sorthtml;
		}
		$hiddeninput = $cursortid ? '<input type="hidden" name="sortid" value="'.$cursortid.'" />' : '';
		$html = '<select name="sortid" '.$onchange.'><option value="0">&nbsp;</option>'.$selecthtml.'</select><span id="threadsortswait"></span>'.$hiddeninput;
		return $html;
	} else {
		return '';
	}
}

function updatecredits($uids, $creditsarray, $coef = 1, $extrasql = '') {
	if($uids && ((!empty($creditsarray) && is_array($creditsarray)) || $extrasql)) {
		global $db, $tablepre, $discuz_uid, $creditnotice, $cookiecredits;
		$self = $creditnotice && $uids == $discuz_uid;
		if($self && !isset($cookiecredits)) {
			$cookiecredits = !empty($_COOKIE['discuz_creditnotice']) ? explode('D', $_COOKIE['discuz_creditnotice']) : array_fill(0, 9, 0);
		}
		$creditsadd = $comma = '';
		foreach($creditsarray as $id => $addcredits) {
			$creditsadd .= $comma.'extcredits'.$id.'=extcredits'.$id.'+('.intval($addcredits).')*('.$coef.')';
			$comma = ', ';
			if($self) {
				$cookiecredits[$id] += intval($addcredits) * $coef;
			}
		}
		if($self) {
			dsetcookie('discuz_creditnotice', implode('D', $cookiecredits).'D'.$discuz_uid, 43200, 0);
		}

		if($creditsadd || $extrasql) {
			$db->query("UPDATE {$tablepre}members SET $creditsadd ".($creditsadd && $extrasql ? ', ' : '')." $extrasql WHERE uid IN ('$uids')", 'UNBUFFERED');
		}
	}
}

function updatesession() {
	if(!empty($GLOBALS['sessionupdated'])) {
		return TRUE;
	}

	global $db, $tablepre, $sessionexists, $sessionupdated, $sid, $onlineip, $discuz_uid, $discuz_user, $timestamp, $lastactivity, $seccode,
		$pvfrequence, $spageviews, $lastolupdate, $oltimespan, $onlinehold, $groupid, $styleid, $invisible, $discuz_action, $fid, $tid;

	$fid = intval($fid);
	$tid = intval($tid);

	if($oltimespan && $discuz_uid && $lastactivity && $timestamp - ($lastolupdate ? $lastolupdate : $lastactivity) > $oltimespan * 60) {
		$lastolupdate = $timestamp;
		$db->query("UPDATE {$tablepre}onlinetime SET total=total+'$oltimespan', thismonth=thismonth+'$oltimespan', lastupdate='$timestamp' WHERE uid='$discuz_uid' AND lastupdate<='".($timestamp - $oltimespan * 60)."'");
		if(!$db->affected_rows()) {
			$db->query("INSERT INTO {$tablepre}onlinetime (uid, thismonth, total, lastupdate)
				VALUES ('$discuz_uid', '$oltimespan', '$oltimespan', '$timestamp')", 'SILENT');
		}
	} else {
		$lastolupdate = intval($lastolupdate);
	}

	if($sessionexists == 1) {
		if($pvfrequence && $discuz_uid) {
			if($spageviews >= $pvfrequence) {
				$pageviewsadd = ', pageviews=\'0\'';
				$db->query("UPDATE {$tablepre}members SET pageviews=pageviews+'$spageviews' WHERE uid='$discuz_uid'", 'UNBUFFERED');
			} else {
				$pageviewsadd = ', pageviews=pageviews+1';
			}
		} else {
			$pageviewsadd = '';
		}
		$db->query("UPDATE {$tablepre}sessions SET uid='$discuz_uid', username='$discuz_user', groupid='$groupid', styleid='$styleid', invisible='$invisible', action='$discuz_action', lastactivity='$timestamp', lastolupdate='$lastolupdate', seccode='$seccode', fid='$fid', tid='$tid' $pageviewsadd WHERE sid='$sid'");
	} else {
		$ips = explode('.', $onlineip);

		$db->query("DELETE FROM {$tablepre}sessions WHERE sid='$sid' OR lastactivity<($timestamp-$onlinehold) OR ('$discuz_uid'<>'0' AND uid='$discuz_uid') OR (uid='0' AND ip1='$ips[0]' AND ip2='$ips[1]' AND ip3='$ips[2]' AND ip4='$ips[3]' AND lastactivity>$timestamp-60)");
		$db->query("INSERT INTO {$tablepre}sessions (sid, ip1, ip2, ip3, ip4, uid, username, groupid, styleid, invisible, action, lastactivity, lastolupdate, seccode, fid, tid)
			VALUES ('$sid', '$ips[0]', '$ips[1]', '$ips[2]', '$ips[3]', '$discuz_uid', '$discuz_user', '$groupid', '$styleid', '$invisible', '$discuz_action', '$timestamp', '$lastolupdate', '$seccode', '$fid', '$tid')", 'SILENT');
		if($discuz_uid && $timestamp - $lastactivity > 21600) {
			if($oltimespan && $timestamp - $lastactivity > 86400) {
				$query = $db->query("SELECT total FROM {$tablepre}onlinetime WHERE uid='$discuz_uid'");
				$oltimeadd = ', oltime='.round(intval($db->result($query, 0)) / 60);
			} else {
				$oltimeadd = '';
			}
			$db->query("UPDATE {$tablepre}members SET lastip='$onlineip', lastvisit=lastactivity, lastactivity='$timestamp' $oltimeadd WHERE uid='$discuz_uid'", 'UNBUFFERED');
		}
	}

	$sessionupdated = 1;
}
function updatemodworks($modaction, $posts = 1) {
	global $modworkstatus, $db, $tablepre, $discuz_uid, $timestamp, $_DCACHE;
	$today = gmdate('Y-m-d', $timestamp + $_DCACHE['settings']['timeoffset'] * 3600);
	if($modworkstatus && $modaction && $posts) {
		$db->query("UPDATE {$tablepre}modworks SET count=count+1, posts=posts+'$posts' WHERE uid='$discuz_uid' AND modaction='$modaction' AND dateline='$today'");
		if(!$db->affected_rows()) {
			$db->query("INSERT INTO {$tablepre}modworks (uid, modaction, dateline, count, posts) VALUES ('$discuz_uid', '$modaction', '$today', 1, '$posts')");
		}
	}
}

function writelog($file, $log) {
	global $timestamp, $_DCACHE;
	$yearmonth = gmdate('Ym', $timestamp + $_DCACHE['settings']['timeoffset'] * 3600);
	$logdir = DISCUZ_ROOT.'./forumdata/logs/';
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);

		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		$log = is_array($log) ? $log : array($log);
		foreach($log as $tmp) {
			fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
		}
		fclose($fp);
	}
}

function wipespecial($str) {
	return str_replace(array( "\n", "\r", '..'), array('', '', ''), $str);
}

function discuz_uc_avatar($uid, $size = '', $returnsrc = FALSE) {
	if($uid > 0) {
		$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
		$uid = abs(intval($uid));
		if(empty($GLOBALS['avatarmethod'])) {
			return $returnsrc ? UC_API.'/avatar.php?uid='.$uid.'&size='.$size : '<img src="'.UC_API.'/avatar.php?uid='.$uid.'&size='.$size.'" />';
		} else {
			$uid = sprintf("%09d", $uid);
			$dir1 = substr($uid, 0, 3);
			$dir2 = substr($uid, 3, 2);
			$dir3 = substr($uid, 5, 2);
			$file = UC_API.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).'_avatar_'.$size.'.jpg';
			return $returnsrc ? $file : '<img src="'.$file.'" onerror="this.onerror=null;this.src=\''.UC_API.'/images/noavatar_'.$size.'.gif\'" />';
		}
	} else {
		$file = $GLOBALS['boardurl'].IMGDIR.'/syspm.gif';
		return $returnsrc ? $file : '<img src="'.$file.'" />';
	}
}


function loadmultiserver($type = '') {
	global $db, $dbcharset, $multiserver;
	$type = empty($type) && defined('CURSCRIPT') ? CURSCRIPT : $type;
	static $sdb = null;
	if($type && !empty($multiserver['enable'][$type])) {
		if(!is_a($sdb, 'dbstuff')) $sdb = new dbstuff();
		if($sdb->link > 0) {
			return $sdb;
		} elseif($sdb->link === null && (!empty($multiserver['slave']['dbhost']) || !empty($multiserver[$type]['dbhost']))) {
			$setting = !empty($multiserver[$type]['host']) ? $multiserver[$type] : $multiserver['slave'];
			$sdb->connect($setting['dbhost'], $setting['dbuser'], $setting['dbpw'], $setting['dbname'], $setting['pconnect'], false, $dbcharset);
			if($sdb->link) {
				return $sdb;
			} else {
				$sdb->link = -32767;
			}
		}
	}
	return $db;
}

function swapclass($classname) {
	global $swapc;
	$swapc = isset($swapc) && $swapc != $classname ? $classname : '';
	return $swapc;
}

?>