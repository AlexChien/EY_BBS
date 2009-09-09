<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: index.php 918 2009-01-21 01:24:29Z zhaoxiongfei $
*/

define('UC_SERVER_VERSION', '1.5.0');
define('UC_SERVER_RELEASE', '20090121');

error_reporting(0);
set_magic_quotes_runtime(0);

$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];

define('IN_UC', TRUE);
define('UC_ROOT', dirname(__FILE__).'/');
define('UC_API', strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/'))));
define('UC_DATADIR', UC_ROOT.'data/');
define('UC_DATAURL', UC_API.'/data');
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

unset($GLOBALS, $_ENV, $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS);

$_GET		= daddslashes($_GET, 1, TRUE);
$_POST		= daddslashes($_POST, 1, TRUE);
$_COOKIE	= daddslashes($_COOKIE, 1, TRUE);
$_SERVER	= daddslashes($_SERVER);
$_FILES		= daddslashes($_FILES);
$_REQUEST	= daddslashes($_REQUEST, 1, TRUE);

if(!@include UC_DATADIR.'config.inc.php') {
	exit('The file <b>data/config.inc.php</b> does not exist, perhaps because of UCenter has not been installed, <a href="install/index.php"><b>Please click here to install it.</b></a>.');
}

$m = getgpc('m');
$a = getgpc('a');
$release = getgpc('release');
if(empty($m) && empty($a)) {
	header('Location: admin.php');
	exit;
}

if(empty($release)) {
	define('RELEASE_ROOT', "release/20080429/");
} elseif(intval($release) != UC_SERVER_RELEASE) {
	define('RELEASE_ROOT', "release/$release/");
} else {
	define('RELEASE_ROOT', '');
}

if(file_exists(UC_ROOT.RELEASE_ROOT.'model/base.php')) {
	require UC_ROOT.RELEASE_ROOT.'model/base.php';
} else {
	require UC_ROOT.'model/base.php';
}

if(in_array($m, array('app', 'frame', 'user', 'pm', 'pm_client', 'tag', 'feed', 'friend', 'domain', 'credit', 'mail', 'version'))) {

	if(file_exists(UC_ROOT.RELEASE_ROOT."control/$m.php")) {
		include UC_ROOT.RELEASE_ROOT."control/$m.php";
	} else {
		include UC_ROOT."control/$m.php";
	}

	$classname = $m.'control';
	$control = new $classname();
	$method = 'on'.$a;
	if(method_exists($control, $method) && $a{0} != '_') {
		$data = $control->$method();
		echo is_array($data) ? $control->serialize($data, 1) : $data;
		exit;
	} elseif(method_exists($control, '_call')) {
		$data = $control->_call('on'.$a, '');
		echo is_array($data) ? $control->serialize($data, 1) : $data;
		exit;
	} else {
		exit('Action not found!');
	}

} else {

	exit('Module not found!');

}

$mtime = explode(' ', microtime());
$endtime = $mtime[1] + $mtime[0];

function daddslashes($string, $force = 0, $strip = FALSE) {
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}

function getgpc($k, $var='R') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

?>