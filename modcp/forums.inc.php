<?php

/*
[Discuz!] (C)2001-2009 Comsenz Inc.
This is NOT a freeware, use is subject to license terms

$Id: forums.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ') || !defined('IN_MODCP')) {
	exit('Access Denied');
}

$forumupdate = $listupdate = false;

$op = !in_array($op , array('editforum', 'recommend')) ? 'editforum' : $op;

if(empty($fid)) {
	if(!empty($_DCOOKIE['modcpfid'])) {
		$fid = $_DCOOKIE['modcpfid'];
	} else {
		list($fid) = array_keys($modforums['list']);
	}
	dheader("Location: {$cpscript}?action=$action&op=$op&fid=$fid");
}

if($fid && $forum['ismoderator']) {

	if($op == 'editforum') {

		require_once DISCUZ_ROOT.'./include/editor.func.php';

		$alloweditrules = $adminid == 1 || $forum['alloweditrules'] ? true : false;

		if(!submitcheck('editsubmit')) {

			$forum['description'] = html2bbcode($forum['description']);
			$forum['rules'] = html2bbcode($forum['rules']);

		} else {

			require_once DISCUZ_ROOT.'./include/discuzcode.func.php';
			$forumupdate = true;
			$descnew = addslashes(preg_replace('/on(mousewheel|mouseover|click|load|onload|submit|focus|blur)="[^"]*"/i', '', discuzcode(stripslashes($descnew), 1, 0, 0, 0, 1, 1, 0, 0, 1)));
			$rulesnew = $alloweditrules ? addslashes(preg_replace('/on(mousewheel|mouseover|click|load|onload|submit|focus|blur)="[^"]*"/i', '', discuzcode(stripslashes($rulesnew), 1, 0, 0, 0, 1, 1, 0, 0, 1))) : addslashes($forum['rules']);
			$db->query("UPDATE {$tablepre}forumfields SET description='$descnew', rules='$rulesnew' WHERE fid='$fid'");

			$forum['description'] = html2bbcode(stripslashes($descnew));
			$forum['rules'] = html2bbcode(stripslashes($rulesnew));

		}

	} elseif($op == 'recommend') {

		$useradd = $adminid == 3 ? "AND moderatorid='$discuz_uid'" : '';

		$ordernew = !empty($ordernew) && is_array($ordernew) ? $ordernew : array();

		if(submitcheck('editsubmit')) {
			if($ids = implodeids($delete)) {
				$listupdate = true;
				$db->query("DELETE FROM {$tablepre}forumrecommend WHERE fid='$fid' AND tid IN($ids) $useradd");
			}
		}

		$page = max(1, intval($page));
		$start_limit = ($page - 1) * $tpp;

		$threadcount = $db->result_first("SELECT COUNT(*) FROM {$tablepre}forumrecommend WHERE fid='$fid' $useradd");
		$multipage = multi($threadcount, $tpp, $page, "$cpscript?action=$action&fid=$fid&page=$page");

		$threadlist = array();
		$query = $db->query("SELECT f.*, m.username as moderator
				FROM {$tablepre}forumrecommend f
				LEFT JOIN {$tablepre}members m ON f.moderatorid=m.uid
				WHERE f.fid='$fid' $useradd LIMIT $start_limit,$tpp");
		while($thread = $db->fetch_array($query)) {
			$thread['author'] =$thread['authorid'] ? "<a href=\"space.php?uid=$thread[authorid]\" target=\"_blank\">$thread[author]</a>" : 'Guest';
			$thread['moderator'] = $thread['moderator'] ? "<a href=\"space.php?uid=$thread[moderatorid]\" target=\"_blank\">$thread[moderator]</a>" : 'System';
			$thread['expiration'] = $thread['expiration'] ? gmdate("$dateformat $timeformat", $thread['expiration'] + ($timeoffset * 3600)) : '';
			if(isset($ordernew[$thread['tid']]) && $ordernew[$thread['tid']] != $thread['displayorder']) {
				$listupdate = true;
				$thread['displayorder'] = intval($ordernew[$thread['tid']]);
				$db->query("UPDATE {$tablepre}forumrecommend SET displayorder='$thread[displayorder]' WHERE fid='$fid' AND tid='$thread[tid]' $useradd", "UNBUFFERED");
			}
			$threadlist[]  = $thread;
		}

	}
}

?>