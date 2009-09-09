<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: moderation.inc.php 17453 2008-12-23 00:36:47Z monkey $
*/

if(!empty($tid)) {
	$moderate = array($tid);
}

if(!defined('IN_DISCUZ') || CURSCRIPT != 'topicadmin') {
	exit('Access Denied');
}

if($operations && $operations != array_intersect($operations, array('delete', 'highlight', 'open', 'close', 'stick', 'digest', 'bump', 'down', 'recommend', 'type', 'move')) || (!$allowdelpost && in_array('delete', $operations)) || (!$allowstickthread && in_array('stick', $operations))) {
	showmessage('admin_moderate_invalid');
}

$threadlist = $loglist = array();
if($tids = implodeids($moderate)) {
	$query = $db->query("SELECT * FROM {$tablepre}threads WHERE tid IN ($tids) AND fid='$fid' AND displayorder>='0' AND digest>='0' LIMIT $tpp");
	while($thread = $db->fetch_array($query)) {
		$thread['lastposterenc'] = rawurlencode($thread['lastposter']);
		$thread['dblastpost'] = $thread['lastpost'];
		$thread['lastpost'] = dgmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
		$threadlist[$thread['tid']] = $thread;
		$tid = empty($tid) ? $thread['tid'] : $tid;
	}
}

if(empty($threadlist)) {
	showmessage('admin_moderate_invalid');
}

$modpostsnum = count($threadlist);
$single = $modpostsnum == 1 ? TRUE : FALSE;

if($frommodcp) {
	$referer = "modcp.php?action=threads&fid=$fid&op=threads&do=".($frommodcp == 1 ? '' : 'list');
} else {
	$referer = "forumdisplay.php?fid=$fid&".rawurldecode($listextra);
}

if(!submitcheck('modsubmit')) {
	if($optgroup == 1 && $single) {
		$stickcheck  = $digestcheck = array();
		empty($threadlist[$tid]['displayorder']) ? $stickcheck[0] ='selected="selected"' : $stickcheck[$threadlist[$tid]['displayorder']] = 'selected="selected"';
		empty($threadlist[$tid]['digest']) ? $digestcheck[0] = 'selected="selected"' : $digestcheck[$threadlist[$tid]['digest']] = 'selected="selected"';
		$string = sprintf('%02d', $threadlist[$tid]['highlight']);
		$stylestr = sprintf('%03b', $string[0]);
		for($i = 1; $i <= 3; $i++) {
			$stylecheck[$i] = $stylestr[$i - 1] ? 1 : 0;
		}
		$colorcheck = $string[1];
		$forum['modrecommend'] = $forum['modrecommend'] ? unserialize($forum['modrecommend']) : array();
	} elseif($optgroup == 2) {
		require_once DISCUZ_ROOT.'./include/forum.func.php';
		$forumselect = forumselect(FALSE, 0, $single ? $threadlist[$tid]['fid'] : 0);
		$typeselect = typeselect($single ? $threadlist[$tid]['typeid'] : 0);
	} elseif($optgroup == 4 && $single) {
		$closecheck = array();
		empty($threadlist[$tid]['closed']) ? $closecheck[0] = 'checked="checked"' : $closecheck[1] = 'checked="checked"';
	}

	$defaultcheck[$operation] = 'checked="checked"';

	include template('topicadmin');

} else {

	$moderatetids = implodeids(array_keys($threadlist));
	checkreasonpm();

	if(empty($operations)) {
		showmessage('admin_nonexistence');
	} else {
		foreach($operations as $operation) {

			$updatemodlog = TRUE;
			if($operation == 'stick') {
				$expiration = checkexpiration($expirationstick);
				$sticklevel = intval($sticklevel);
				if($sticklevel < 0 || $sticklevel > 3 || $sticklevel > $allowstickthread) {
					showmessage('undefined_action');
				}
				$expirationstick = $sticklevel ? $expirationstick : 0;

				$db->query("UPDATE {$tablepre}threads SET displayorder='$sticklevel', moderated='1' WHERE tid IN ($moderatetids)");

				$stickmodify = 0;
				foreach($threadlist as $thread) {
					$stickmodify = (in_array($thread['displayorder'], array(2, 3)) || in_array($sticklevel, array(2, 3))) && $sticklevel != $thread['displayorder'] ? 1 : $stickmodify;
				}

				if($globalstick && $stickmodify) {
					require_once DISCUZ_ROOT.'./include/cache.func.php';
					updatecache('globalstick');
				}

				$modaction = $sticklevel ? ($expiration ? 'EST' : 'STK') : 'UST';
				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('STK', 'UST', 'EST', 'UES')", 'UNBUFFERED');
			} elseif($operation == 'highlight') {
				$expiration = checkexpiration($expirationhighlight);
				$stylebin = '';
				for($i = 1; $i <= 3; $i++) {
					$stylebin .= empty($highlight_style[$i]) ? '0' : '1';
				}

				$highlight_style = bindec($stylebin);
				if($highlight_style < 0 || $highlight_style > 7 || $highlight_color < 0 || $highlight_color > 8) {
					showmessage('undefined_action', NULL, 'HALTED');
				}

				$db->query("UPDATE {$tablepre}threads SET highlight='$highlight_style$highlight_color', moderated='1' WHERE tid IN ($moderatetids)", 'UNBUFFERED');

				$modaction = ($highlight_style + $highlight_color) ? ($expiration ? 'EHL' : 'HLT') : 'UHL';
				$expiration = $modaction == 'UHL' ? 0 : $expiration;
				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('HLT', 'UHL', 'EHL', 'UEH')", 'UNBUFFERED');
			} elseif($operation == 'digest') {
				$expiration = checkexpiration($expirationdigest);
				$db->query("UPDATE {$tablepre}threads SET digest='$digestlevel', moderated='1' WHERE tid IN ($moderatetids)");

				foreach($threadlist as $thread) {
					if($thread['digest'] != $digestlevel) {
						$digestpostsadd = ($thread['digest'] > 0 && $digestlevel == 0) || ($thread['digest'] == 0 && $digestlevel > 0) ? 'digestposts=digestposts'.($digestlevel == 0 ? '-' : '+').'1' : '';
						updatecredits($thread['authorid'], $digestcredits, $digestlevel - $thread['digest'], $digestpostsadd);
					}
				}

				$modaction = $digestlevel ? ($expiration ? 'EDI' : 'DIG') : 'UDG';
				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('DIG', 'UDI', 'EDI', 'UED')", 'UNBUFFERED');
			} elseif($operation == 'recommend') {
				$expiration = checkexpiration($expirationrecommend);
				$db->query("UPDATE {$tablepre}threads SET moderated='1' WHERE tid IN ($moderatetids)");
				$modaction = $isrecommend ? 'REC' : 'URE';
				$thread = daddslashes($thread, 1);

				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('REC')", 'UNBUFFERED');
				if($isrecommend) {
					$addthread = $comma = '';
					foreach($threadlist as $thread) {
						$addthread .= $comma."('$thread[fid]', '$thread[tid]', '0', '".addslashes($thread['subject'])."', '".addslashes($thread['author'])."', '$thread[authorid]', '$discuz_uid', '$expiration')";
						$comma = ', ';
					}
					if($addthread) {
						$db->query("REPLACE INTO {$tablepre}forumrecommend (fid, tid, displayorder, subject, author, authorid, moderatorid, expiration) VALUES $addthread");
					}
				} else {
					$db->query("DELETE FROM {$tablepre}forumrecommend WHERE fid='$fid' AND tid IN ($moderatetids)");
				}
			} elseif($operation == 'bump') {
				$modaction = 'BMP';
				$thread = $threadlist;
				$thread = array_pop($thread);
				$thread['subject'] = addslashes($thread['subject']);
				$thread['lastposter'] = addslashes($thread['lastposter']);

				$db->query("UPDATE {$tablepre}threads SET lastpost='$timestamp', moderated='1' WHERE tid IN ($moderatetids)");
				$db->query("UPDATE {$tablepre}forums SET lastpost='$thread[tid]\t$thread[subject]\t$timestamp\t$thread[lastposter]' WHERE fid='$fid'");

				$forum['threadcaches'] && deletethreadcaches($thread['tid']);
			} elseif($operation == 'down') {
				$modaction = 'DWN';
				$downtime = $timestamp - 86400 * 730;
				$db->query("UPDATE {$tablepre}threads SET lastpost='$downtime', moderated='1' WHERE tid IN ($moderatetids)");

				$forum['threadcaches'] && deletethreadcaches($thread['tid']);
			} elseif($operation == 'delete') {
				$stickmodify = 0;
				foreach($threadlist as $thread) {
					if($thread['digest']) {
						updatecredits($thread['authorid'], $digestcredits, -$thread['digest'], 'digestposts=digestposts-1');
					}
					if(in_array($thread['displayorder'], array(2, 3))) {
						$stickmodify = 1;
					}
				}

				$losslessdel = $losslessdel > 0 ? $timestamp - $losslessdel * 86400 : 0;

				//Update members' credits and post counter
				$uidarray = $tuidarray = $ruidarray = array();
				$query = $db->query("SELECT first, authorid, dateline FROM {$tablepre}posts WHERE tid IN ($moderatetids)");
				while($post = $db->fetch_array($query)) {
					if($post['dateline'] < $losslessdel) {
						$uidarray[] = $post['authorid'];
					} else {
						if($post['first']) {
							$tuidarray[] = $post['authorid'];
						} else {
							$ruidarray[] = $post['authorid'];
						}
					}
				}

				if($uidarray) {
					updatepostcredits('-', $uidarray, array());
				}
				if($tuidarray) {
					updatepostcredits('-', $tuidarray, $postcredits);
				}
				if($ruidarray) {
					updatepostcredits('-', $ruidarray, $replycredits);
				}
				$modaction = 'DEL';

				if($forum['recyclebin']) {

					$db->query("UPDATE {$tablepre}threads SET displayorder='-1', digest='0', moderated='1' WHERE tid IN ($moderatetids)");
					$db->query("UPDATE {$tablepre}posts SET invisible='-1' WHERE tid IN ($moderatetids)");

				} else {

					$auidarray = array();

					$query = $db->query("SELECT uid, attachment, dateline, thumb, remote FROM {$tablepre}attachments WHERE tid IN ($moderatetids)");
					while($attach = $db->fetch_array($query)) {
						dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
						if($attach['dateline'] > $losslessdel) {
							$auidarray[$attach['uid']] = !empty($auidarray[$attach['uid']]) ? $auidarray[$attach['uid']] + 1 : 1;
						}
					}

					if($auidarray) {
						updateattachcredits('-', $auidarray, $postattachcredits);
					}

					$videoopen && videodelete($moderate, TRUE);

					foreach(array('threads', 'threadsmod', 'relatedthreads', 'posts', 'polls', 'polloptions', 'trades', 'activities', 'activityapplies', 'debates', 'videos', 'debateposts', 'attachments', 'favorites', 'mythreads', 'myposts', 'subscriptions', 'typeoptionvars', 'forumrecommend') as $value) {
						$db->query("DELETE FROM {$tablepre}$value WHERE tid IN ($moderatetids)", 'UNBUFFERED');
					}
					$updatemodlog = FALSE;
				}

				if($globalstick && $stickmodify) {
					require_once DISCUZ_ROOT.'./include/cache.func.php';
					updatecache('globalstick');
				}

				updateforumcount($fid);
			} elseif($operation == 'close') {
				$expiration = checkexpiration($expirationclose);
				$modaction = $expiration ? 'ECL' : 'CLS';

				$db->query("UPDATE {$tablepre}threads SET closed='1', moderated='1' WHERE tid IN ($moderatetids)");
				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('CLS','OPN','ECL','UCL','EOP','UEO')", 'UNBUFFERED');
			} elseif($operation == 'open') {
				$expiration = checkexpiration($expirationopen);
				$modaction = $expiration ? 'EOP' : 'OPN';

				$db->query("UPDATE {$tablepre}threads SET closed='0', moderated='1' WHERE tid IN ($moderatetids)");
				$db->query("UPDATE {$tablepre}threadsmod SET status='0' WHERE tid IN ($moderatetids) AND action IN ('CLS','OPN','ECL','UCL','EOP','UEO')", 'UNBUFFERED');
			} elseif($operation == 'move') {
				$toforum = $db->fetch_first("SELECT fid, name, modnewposts, allowpostspecial FROM {$tablepre}forums WHERE fid='$moveto' AND status>0 AND type<>'group'");
				if(!$toforum) {
					showmessage('admin_move_invalid');
				} elseif($fid == $toforum['fid']) {
					continue;
				} else {
					$moveto = $toforum['fid'];
					$modnewthreads = (!$allowdirectpost || $allowdirectpost == 1) && $toforum['modnewposts'] ? 1 : 0;
					$modnewreplies = (!$allowdirectpost || $allowdirectpost == 2) && $toforum['modnewposts'] ? 1 : 0;
					if($modnewthreads || $modnewreplies) {
						showmessage('admin_move_have_mod');
					}
				}

				if($adminid == 3) {
					if($accessmasks) {
						$accessadd1 = ', a.allowview, a.allowpost, a.allowreply, a.allowgetattach, a.allowpostattach';
						$accessadd2 = "LEFT JOIN {$tablepre}access a ON a.uid='$discuz_uid' AND a.fid='$moveto'";
					}
					$priv = $db->fetch_first("SELECT ff.postperm, m.uid AS istargetmod $accessadd1
							FROM {$tablepre}forumfields ff
							$accessadd2
							LEFT JOIN {$tablepre}moderators m ON m.fid='$moveto' AND m.uid='$discuz_uid'
							WHERE ff.fid='$moveto'");
					if((($priv['postperm'] && !in_array($groupid, explode("\t", $priv['postperm']))) || ($accessmasks && ($priv['allowview'] || $priv['allowreply'] || $priv['allowgetattach'] || $priv['allowpostattach']) && !$priv['allowpost'])) && !$priv['istargetmod']) {
						showmessage('admin_move_nopermission');
					}
				}

				$moderate = array();
				$stickmodify = 0;
				foreach($threadlist as $tid => $thread) {
					if(!$thread['special'] || substr(sprintf('%04b', $toforum['allowpostspecial']), -$thread['special'], 1)) {
						$moderate[] = $tid;
						if(in_array($thread['displayorder'], array(2, 3))) {
							$stickmodify = 1;
						}
						if($type == 'redirect') {
							$thread = daddslashes($thread, 1);
							$db->query("INSERT INTO {$tablepre}threads (fid, readperm, iconid, author, authorid, subject, dateline, lastpost, lastposter, views, replies, displayorder, digest, closed, special, attachment)
								VALUES ('$thread[fid]', '$thread[readperm]', '$thread[iconid]', '".addslashes($thread['author'])."', '$thread[authorid]', '".addslashes($thread['subject'])."', '$thread[dateline]', '$thread[dblastpost]', '".addslashes($thread['lastposter'])."', '0', '0', '0', '0', '$thread[tid]', '0', '0')");
						}
					}
				}

				if(!$moderatetids = implode(',', $moderate)) {
					showmessage('admin_moderate_invalid');
				}

				$displayorderadd = $adminid == 3 ? ', displayorder=\'0\'' : '';
				$db->query("UPDATE {$tablepre}threads SET fid='$moveto', moderated='1' $displayorderadd WHERE tid IN ($moderatetids)");
				$db->query("UPDATE {$tablepre}posts SET fid='$moveto' WHERE tid IN ($moderatetids)");

				if($globalstick && $stickmodify) {
					require_once DISCUZ_ROOT.'./include/cache.func.php';
					updatecache('globalstick');
				}
				$modaction = 'MOV';

				updateforumcount($moveto);
				updateforumcount($fid);
			} elseif($operation == 'type') {
				if(!isset($forum['threadtypes']['types'][$typeid]) && ($typeid != 0 || $forum['threadtypes']['required'])) {
					showmessage('admin_type_invalid');
				}

				$db->query("UPDATE {$tablepre}threads SET typeid='$typeid', moderated='1' WHERE tid IN ($moderatetids)");
				$modaction = 'TYP';
			}

			if($updatemodlog) {
				updatemodlog($moderatetids, $modaction, $expiration);
			}

			updatemodworks($modaction, $modpostsnum);
			foreach($threadlist as $thread) {
				modlog($thread, $modaction);
			}

			if($sendreasonpm) {
				include_once language('modactions');
				$modaction = $modactioncode[$modaction];
				foreach($threadlist as $thread) {
					sendreasonpm('thread', $operation == 'move' ? 'reason_move' : 'reason_moderate');
				}
			}

			procreportlog($moderatetids, '', $operation == 'delete');

		}

		showmessage('admin_succeed', $referer);
	}

}

function checkexpiration($expiration) {
	global $operation, $timestamp, $timeoffset;
	if(!empty($expiration) && in_array($operation, array('recommend', 'stick', 'digest', 'highlight', 'close'))) {
		$expiration = strtotime($expiration) - $timeoffset * 3600 + date('Z');
		if(gmdate('Ymd', $expiration + $timeoffset * 3600) <= gmdate('Ymd', $timestamp + $timeoffset * 3600) || ($expiration > $timestamp + 86400 * 180)) {
			showmessage('admin_expiration_invalid');
		}
	} else {
		$expiration = 0;
	}
	return $expiration;
}

?>