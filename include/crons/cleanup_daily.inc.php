<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: cleanup_daily.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$db->query("UPDATE {$tablepre}advertisements SET available='0' WHERE endtime>'0' AND endtime<='$timestamp'", 'UNBUFFERED');
if($db->affected_rows()) {
	require_once DISCUZ_ROOT.'./include/cache.func.php';
	updatecache(array('settings', 'advs_archiver', 'advs_register', 'advs_index', 'advs_forumdisplay', 'advs_viewthread'));
}
$db->query("TRUNCATE {$tablepre}searchindex");
$db->query("DELETE FROM {$tablepre}threadsmod WHERE dateline<'$timestamp'-31536000", 'UNBUFFERED');
$db->query("DELETE FROM {$tablepre}subscriptions WHERE lastpost<'$timestamp'-7776000", 'UNBUFFERED');
$db->query("DELETE FROM {$tablepre}forumrecommend WHERE expiration<'$timestamp'", 'UNBUFFERED');

if($qihoo['status'] && $qihoo['relatedthreads']) {
	$db->query("DELETE FROM {$tablepre}relatedthreads WHERE expiration<'$timestamp'", 'UNBUFFERED');
}

$db->query("UPDATE {$tablepre}trades SET closed='1' WHERE expiration<>0 AND expiration<'$timestamp'", 'UNBUFFERED');
$db->query("DELETE FROM {$tablepre}tradelog WHERE status=0 AND lastupdate<'".($timestamp - 5 * 86400)."'", 'UNBUFFERED');

if($cachethreadon) {
	removedir($cachethreaddir, TRUE);
}

if($regstatus > 1) {
	$db->query("UPDATE {$tablepre}invites SET status='4' WHERE expiration<'$timestamp' AND status IN ('1', '3')");
}

function removedir($dirname, $keepdir = FALSE) {

	$dirname = wipespecial($dirname);

	if(!is_dir($dirname)) {
		return FALSE;
	}
	$handle = opendir($dirname);
	while(($file = readdir($handle)) !== FALSE) {
		if($file != '.' && $file != '..') {
			$dir = $dirname . DIRECTORY_SEPARATOR . $file;
			is_dir($dir) ? removedir($dir) : unlink($dir);
		}
	}
	closedir($handle);
	return !$keepdir ? (@rmdir($dirname) ? TRUE : FALSE) : TRUE;
}
?>