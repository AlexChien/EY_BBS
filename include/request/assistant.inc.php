<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: assistant.inc.php 16697 2008-11-14 07:36:51Z monkey $
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

if($requestrun) {

	$nocache = 1;
	$avatar = discuz_uc_avatar($GLOBALS['discuz_uid'], 'small');
	$fidadd = isset($_GET['fid']) ? '&srchfid='.$_GET['fid'] : '';
	include template('request_assistant');

} else {

	$request_version = '1.0';
	$request_name = $requestlang['assistant_name'];
	$request_description = $requestlang['assistant_desc'];
	$request_copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';
	$request_settings = array();

}

?>