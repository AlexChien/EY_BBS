<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: task.func.php 17385 2008-12-17 05:05:02Z liuqiang $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function task_apply($task = array()) {
	global $db, $tablepre, $discuz_uid, $discuz_user, $timestamp;

	require_once DISCUZ_ROOT.'./include/tasks/'.$task['scriptname'].'.inc.php';
	if(!isset($task['newbie'])) {
		task_condition();
	}
	$db->query("REPLACE INTO {$tablepre}mytasks (uid, username, taskid, csc, dateline)
		VALUES ('$discuz_uid', '$discuz_user', '$task[taskid]', '0\t$timestamp', '$timestamp')");
	$db->query("UPDATE {$tablepre}tasks SET applicants=applicants+1 WHERE taskid='$task[taskid]'", 'UNBUFFERED');
	$db->query("UPDATE {$tablepre}members SET prompt=prompt|2 WHERE uid='$discuz_uid'", 'UNBUFFERED');
	task_preprocess($task);
}

function task_reward($task = array()) {
	switch($task['reward']) {
		case 'credit': return task_reward_credit($task['prize'], $task['bonus']); break;
		case 'magic': return task_reward_magic($task['prize'], $task['bonus']); break;
		case 'medal': return task_reward_medal($task['prize'], $task['bonus']); break;
		case 'invite': return task_reward_invite($task['bonus'], $task['prize']); break;
		case 'group': return task_reward_group($task['prize'], $task['bonus']); break;
	}
}

function task_reward_credit($extcreditid, $credits) {
	global $db, $tablepre, $discuz_uid, $timestamp;

	$creditsarray[$extcreditid] = $credits;
	updatecredits($discuz_uid, $creditsarray);
	$db->query("INSERT INTO {$tablepre}creditslog (uid, fromto, sendcredits, receivecredits, send, receive, dateline, operation) VALUES ('$discuz_uid', 'TASK REWARD', '$extcreditid', '$extcreditid', '0', '$credits', '$timestamp', 'RCV')");
}

function task_reward_magic($magicid, $num) {
	global $db, $tablepre, $discuz_uid;

	if($db->result_first("SELECT COUNT(*) FROM {$tablepre}membermagics WHERE magicid='$magicid' AND uid='$discuz_uid'")) {
		$db->query("UPDATE {$tablepre}membermagics SET num=num+'$num' WHERE magicid='$magicid' AND uid='$discuz_uid'", 'UNBUFFERED');
	} else {
		$db->query("INSERT INTO {$tablepre}membermagics (uid, magicid, num) VALUES ('$discuz_uid', '$magicid', '$num')");
	}
}

function task_reward_medal($medalid, $day) {
	global $db, $tablepre, $discuz_uid, $timestamp;

	$medals = $db->result_first("SELECT medals FROM {$tablepre}memberfields WHERE uid='$discuz_uid'");
	$medalsnew = $medals ? $medals."\t".$medalid : $medalid;
	$db->query("UPDATE {$tablepre}memberfields SET medals='$medalsnew' WHERE uid='$discuz_uid'", 'UNBUFFERED');
	$db->query("INSERT INTO {$tablepre}medallog (uid, medalid, type, dateline, expiration, status) VALUES ('$discuz_uid', '$medalid', '0', '$timestamp', '".($day ? $timestamp + $day * 86400 : '')."', '1')");
}

function task_reward_invite($day, $num) {
	global $db, $tablepre, $discuz_uid, $timestamp, $onlineip;

	$expiration = $timestamp + $day * 86400;
	$invitecodes = $comma = '';
	for($i = 1; $i <= $num; $i++) {
		$invitecode = substr(md5($discuz_uid.$timestamp.random(6)), 0, 10).random(6);
		$db->query("INSERT INTO {$tablepre}invites (uid, dateline, expiration, inviteip, invitecode) VALUES ('$discuz_uid', '$timestamp', '$expiration', '$onlineip', '$invitecode')", 'UNBUFFERED');
		$invitecodes .= $comma.'[b]'.$invitecode.'[/b]';
		$comma = "\n\t";
	}
	return $invitecodes;
}

function task_reward_group($gid, $day = 0) {
	global $db, $tablepre, $discuz_uid, $timestamp;

	$exists = FALSE;
	if($extgroupids) {
		$extgroupids = explode("\t", $extgroupids);
		if(in_array($gid, $extgroupids)) {
			$exists = TRUE;
		} else {
			$extgroupids[] = $gid;
		}
		$extgroupids = implode("\t", $extgroupids);
	} else {
		$extgroupids = $gid;
	}

	$db->query("UPDATE {$tablepre}members SET extgroupids='$extgroupids' WHERE uid='$discuz_uid'", 'UNBUFFERED');

	if($day) {
		$groupterms = $db->result_first("SELECT groupterms FROM {$tablepre}memberfields WHERE uid='$discuz_uid'");
		$groupterms = $groupterms ? unserialize($groupterms) : array();
		$groupterms['ext'][$gid] = $exists && $groupterms['ext'][$gid] ? max($groupterms['ext'][$gid], $timestamp + $day * 86400) : $timestamp + $day * 86400;
		$db->query("UPDATE {$tablepre}memberfields SET groupterms='".addslashes(serialize($groupterms))."' WHERE uid='$discuz_uid'", 'UNBUFFERED');
	}
}

?>