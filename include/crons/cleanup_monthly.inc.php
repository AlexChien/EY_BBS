<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: cleanup_monthly.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$myrecordtimes = $timestamp - $_DCACHE['settings']['myrecorddays'] * 86400;
$db->query("DELETE FROM {$tablepre}mythreads WHERE dateline<'$myrecordtimes'", 'UNBUFFERED');
$db->query("DELETE FROM {$tablepre}myposts WHERE dateline<'$myrecordtimes'", 'UNBUFFERED');

$db->query("DELETE FROM {$tablepre}invites WHERE dateline<'$timestamp'-2592000 AND status='4'", 'UNBUFFERED');
$db->query("TRUNCATE {$tablepre}relatedthreads");
$db->query("DELETE FROM {$tablepre}mytasks WHERE status='-1' AND dateline<'$timestamp'-2592000", 'UNBUFFERED');

?>