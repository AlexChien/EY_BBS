<?php
define('NOROBOT', TRUE);
define('CURSCRIPT', 'openid_install');

require_once './include/common.inc.php';

include_once language('openid_install');

if ($adminid != 1) {
	showmessage("Your are not the administrator of this forum.");
}

if ($_POST["installsubmit"]) {
	$sql = "CREATE TABLE IF NOT EXISTS `{$tablepre}openid` ("
		. "`uid` mediumint(8) unsigned NOT NULL,"
		. "`openid_url` varchar(255) NOT NULL,"
		. "PRIMARY KEY (`openid_url`),"
		. "UNIQUE KEY `uid` (`uid`)"
		. ");";
	$db->query($sql);

	$sql = "CREATE TABLE IF NOT EXISTS `{$tablepre}openid_sessions` ("
		. "`sid` char(6) binary NOT NULL,"
		. "`openid_url` varchar(255) NOT NULL,"
		. "PRIMARY KEY (`sid`)"
		. ") ;";
	$db->query($sql);

	$sql = "CREATE TABLE IF NOT EXISTS `{$tablepre}openid_username_cache` (" .
			"`username` char(15) NOT NULL DEFAULT ''," .
			"`last_number` mediumint(8) DEFAULT 0," .
			"PRIMARY KEY (`username`)" .
			")";
	$db->query($sql);

	showmessage("Install OK.");
}

include template('openid_install');
?>