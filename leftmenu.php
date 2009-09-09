<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: leftmenu.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

define('NOROBOT', TRUE);
require_once './include/common.inc.php';
require_once DISCUZ_ROOT.'./include/forum.func.php';

$forumlist = $collapse = $gid = array();
$newthreads = round(($timestamp - $lastvisit + 600) / 1000) * 1000;

$sql = !empty($accessmasks) ?
	"SELECT f.fid, f.fup, f.type, f.name, ff.viewperm, a.allowview FROM {$tablepre}forums f
		LEFT JOIN {$tablepre}forumfields ff ON ff.fid=f.fid
		LEFT JOIN {$tablepre}access a ON a.uid='$discuz_uid' AND a.fid=f.fid
		WHERE f.status>'0' ORDER BY f.type, f.displayorder"
	: "SELECT f.fid, f.fup, f.type, f.name, ff.viewperm FROM {$tablepre}forums f
		LEFT JOIN {$tablepre}forumfields ff USING(fid)
		WHERE f.status>'0' ORDER BY f.type, f.displayorder";
$query = $db->query($sql);

$forumdata = $forumlist = $haschild = array();
while($forumdata = $db->fetch_array($query)) {
	if(!$forumdata['viewperm'] || ($forumdata['viewperm'] && forumperm($forumdata['viewperm'])) || !empty($forumdata['allowview']) || (isset($forumdata['users']) && strstr($forumdata['users'], "\t$discuz_uid\t"))) {
		$forumdata['name'] = addslashes($forumdata['name']);
		$forumdata['type'] != 'group' && $haschild[$forumdata['fup']] = true;
		$forumlist[] = $forumdata;
	}
}

$query = $db->query("SELECT COUNT(*) FROM {$tablepre}sessions");
$onlinenum = $db->result($query , 0);

include template('leftmenu');
?>