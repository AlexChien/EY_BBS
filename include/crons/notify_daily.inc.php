<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: notify_daily.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$subscriptions = array();
$query = $db->query("SELECT t.tid, t.subject, t.author, t.lastpost, t.lastposter, t.views, t.replies, m.username, m.email
	FROM {$tablepre}subscriptions s, {$tablepre}members m, {$tablepre}threads t
	WHERE s.lastpost>s.lastnotify AND m.uid=s.uid AND t.tid=s.tid");

while($sub = $db->fetch_array($query)) {
	if(empty($subscriptions[$sub['tid']])) {
		$subscriptions[$sub['tid']] = array
			(
			'thread' => array
				(
				'tid'		=> $sub['tid'],
				'subject'	=> $sub['subject'],
				'author'	=> ($sub['author'] ? $sub['author'] : 'Guest'),
				'lastpost'	=> gmdate($_DCACHE['settings']['dateformat'].' '.$_DCACHE['settings']['timeformat'], $sub['lastpost'] + $_DCACHE['settings']['timeoffset'] * 3600),
				'lastposter'	=> ($sub['lastposter'] ? $sub['lastposter'] : 'Guest'),
				'views'		=> $sub['views'],
				'replies'	=> $sub['replies']
				),
			'emails' => array("$sub[username] <$sub[email]>")
			);
	} else {
		$subscriptions[$sub['tid']]['emails'][] = $sub['email'];
	}
}

global $thread;
foreach($subscriptions as $sub) {
	$thread = $sub['thread'];
	sendmail(implode(',', $sub['emails']), 'email_notify_subject', 'email_notify_message');
}

$db->query("UPDATE {$tablepre}subscriptions SET lastnotify='$timestamp' WHERE lastpost>lastnotify");

?>