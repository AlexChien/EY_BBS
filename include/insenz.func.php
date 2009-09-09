<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: insenz.func.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('INSENZ_VERSION', '1.1');

function insenz_authcode($string, $operation, $key = '') {

	$key = md5($key);
	$key_length = strlen($key);

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

function insenz_convert($str, $type = 1) {
	global $charset, $discuz_chs, $insenz_chs;

	if($charset != 'utf-8') {
		require_once DISCUZ_ROOT.'./include/chinese.class.php';
		if($type) {
			if(!$insenz_chs) {
				$insenz_chs = new Chinese($charset, 'utf-8', TRUE);
			}
			$str = $insenz_chs->convert($str);
		} else {
			if(!$discuz_chs) {
				$discuz_chs = new Chinese('utf-8', $charset, TRUE);
			}
			$str = $discuz_chs->convert($str);
		}
	}

	return $type ? htmlspecialchars($str) : addslashes($str);

}

function insenz_respond($data, $status = 1, $force = 0) {
	global $insenz, $timestamp;

	@include_once DISCUZ_ROOT.'./discuz_version.php';
	$authkey = !empty($insenz['authkey']) && !$force ? $insenz['authkey'] : 'Discuz!INSENZ';
	$t_hex = sprintf("%08x", $timestamp);
	$postdata = '<?xml version="1.0" encoding="UTF'.'-8"?>'.
		'<response insenz_version="'.INSENZ_VERSION.'" discuz_version="'.DISCUZ_VERSION.' - '.DISCUZ_RELEASE.'">'.
		($status ? "<status>1</status><reason>$data</reason>" : $data).
		'</response>';
	echo insenz_authcode($t_hex.md5($authkey.$postdata.$t_hex).$postdata, 'ENCODE', $authkey);
	exit;

}

function insenz_cronnextrun($cronnextrun) {
	global $_DCACHE;

	if(empty($_DCACHE['settings']['insenz']['cronnextrun']) || $cronnextrun < $_DCACHE['settings']['insenz']['cronnextrun']) {
		require_once DISCUZ_ROOT.'./include/cache.func.php';
		$_DCACHE['settings']['insenz']['cronnextrun'] = $cronnextrun;
		updatesettings();
	}

}

?>