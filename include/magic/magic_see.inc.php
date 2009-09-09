<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: magic_see.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(submitcheck('usesubmit')) {

	if(empty($pid)) {
		showmessage('magics_info_nonexistence');
	}

	$post = getpostinfo($pid, 'pid', array('p.fid', 'useip'));
	checkmagicperm($magicperm['forum'], $post['fid']);

	usemagic($magicid, $magic['num']);
	updatemagiclog($magicid, '2', '1', '0', '0', $pid);
	showmessage('magics_SEK_message', '', 1);

}

function showmagic() {
	global $pid, $lang;
	magicshowtype($lang['option'], 'top');
	magicshowsetting($lang['target_pid'], 'pid', $pid, 'text');
	magicshowtype('', 'bottom');
}

?>