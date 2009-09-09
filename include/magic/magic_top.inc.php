<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: magic_top.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(submitcheck('usesubmit')) {

	if(empty($tid)) {
		showmessage('magics_info_nonexistence');
	}

	$post = getpostinfo($tid, 'tid', array('fid'));
	checkmagicperm($magicperm['forum'], $post['fid']);
	magicthreadmod($tid);

	$db->query("UPDATE {$tablepre}threads SET displayorder='1', moderated='1' WHERE tid='$tid'");
	$expiration = $timestamp + 86400;

	usemagic($magicid, $magic['num']);
	updatemagiclog($magicid, '2', '1', '0', $tid);
	updatemagicthreadlog($tid, $magicid, $magic['identifier'], $expiration);
	showmessage('magics_operation_succeed', '', 1);

}

function showmagic() {
	global $tid, $lang;
	magicshowtype($lang['option'], 'top');
	magicshowsetting($lang['target_tid'], 'tid', $tid, 'text');
	magicshowtype('', 'bottom');
}

?>