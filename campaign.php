<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: campaign.php 12695 2008-03-06 09:46:12Z liuqiang $
*/

define('CURSCRIPT', 'campaign');
require_once './include/common.inc.php';

if($action == 'list') {

	$query = $db->query("SELECT * FROM {$tablepre}virtualforums WHERE status='1'");
	$catlist = $forumlist = array();
	while($forum = $db->fetch_array($query)) {
		if($forum['type'] == 'group') {
			$catlist[$forum['fid']] = $forum;
		} elseif($forum['type'] == 'forum') {
			if($forum['lastpost']) {
				list($forum['lp']['tid'], $forum['lp']['subject'], $forum['lp']['dateline'], $forum['lp']['author']) = explode("\t", $forum['lastpost']);
				$forum['lp']['dateline'] = @dgmdate("$dateformat $timeformat", $forum['lp']['dateline']);
			}
			$forum['description'] = addslashes($forum['description']);
			$forumlist[$forum['fup']][] = $forum;
		}
	}

} elseif($action == 'view' && $cid) {

	$campaign = $db->fetch_first("SELECT status, url, autoupdate, lastupdated FROM {$tablepre}campaigns WHERE id='$cid' AND type='4'");
	if(!$campaign || $campaign['status'] != 2) {
		showmessage('forum_nonexistence');
	}

	$insenz = $db->result_first("SELECT value FROM {$tablepre}settings WHERE variable='insenz'");
	$insenz = $insenz ? unserialize($insenz) : array();
	if(empty($insenz['authkey'])) {
		showmessage('forum_nonexistence');
	}

	require_once DISCUZ_ROOT.'./include/insenz.func.php';
	$iframeurl = $campaign['url']."siteid=$insenz[siteid]&cid=$cid&s=".urlencode(insenz_authcode("sitename=$bbname&siteurl=$boardurl&username=$discuz_userss&uid=$discuz_uid&email=$email&grouptitle=$grouptitle", 'ENCODE', $insenz['authkey'])).'&'.$_SERVER['QUERY_STRING'];

	$update = $campaign['autoupdate'] && ($timestamp - $campaign['lastupdated']) < 600 ? FALSE : TRUE;

} else {
	showmessage('undefined_action');
}

include template('campaign');

?>