<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: magic_up.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(submitcheck('usesubmit')) {

	if(empty($tid)) {
		showmessage('magics_info_nonexistence');
	}

	$thread = getpostinfo($tid, 'tid', array('fid'));
	checkmagicperm($magicperm['forum'], $thread['fid']);

	$db->query("UPDATE {$tablepre}threads SET lastpost='$timestamp', moderated='1' WHERE tid='$tid'");

	usemagic($magicid, $magic['num']);
	updatemagiclog($magicid, '2', '1', '0', $tid);
	updatemagicthreadlog($tid, $magicid, $magic['identifier']);
	showmessage('magics_operation_succeed', '', 1);

}

function showmagic() {
	global $tid, $lang;
	magicshowtype($lang['option'], 'top');
	magicshowsetting($lang['target_tid'], 'tid', $tid, 'text');
	magicshowtype('', 'bottom');
}

?>