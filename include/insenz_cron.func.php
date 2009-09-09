<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: insenz_cron.func.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function insenz_runcron() {
	global $timestamp, $db, $tablepre, $_DCACHE;

	$lockfile = DISCUZ_ROOT.'./forumdata/insenzcron_'.gmdate('ymdH', $timestamp + 8 * 3600).'.lock';
	if(is_writable($lockfile) && filemtime($lockfile) > $timestamp - 180) {
		return NULL;
	} else {
		@touch($lockfile);
	}
	@set_time_limit(1000);
	@ignore_user_abort(TRUE);

	$globalstick = $floatthreads = 0;
	$query = $db->query("SELECT c.id, c.type, c.fid AS vfid, c.tid, c.status, c.begintime, c.starttime, c.endtime, t.fid, t.authorid, t.author, t.subject, t.lastpost, t.displayorder, t.digest FROM {$tablepre}campaigns c LEFT JOIN {$tablepre}threads t ON t.tid=c.tid WHERE c.nextrun<='$timestamp'");
	while($c = $db->fetch_array($query)) {

		$moderated = in_array($c['displayorder'], array(-11, -12, 1, 2)) ? 1 : 0;
		$moderatedadd = $moderated ? ", moderated=1" : '';
		if($c['status'] == 1) {
			if($c['type'] != 4) {
				$db->query("UPDATE {$tablepre}threads SET displayorder=-displayorder-10 $moderatedadd WHERE tid='$c[tid]'", 'UNBUFFERED');
				$db->query("UPDATE {$tablepre}campaigns SET status=2, starttime='$timestamp', nextrun=endtime+starttime-begintime, expiration=expiration+starttime-begintime WHERE id='$c[id]' AND type='$c[type]'", 'UNBUFFERED');
				$lastpost = addslashes("$c[tid]\t$c[subject]\t$c[lastpost]\t$c[author]");
				$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost', threads=threads+1, posts=posts+1, todayposts=todayposts+1 WHERE fid='$c[fid]'", 'UNBUFFERED');

				if($moderated) {
					$expiration = $c['endtime'] + $c['starttime'] - $c['begintime'];
					$db->query("INSERT INTO {$tablepre}threadsmod (tid, uid, username, dateline, action, expiration, status) VALUES ('$c[tid]', '".$_DCACHE[settings][insenz][uid]."', '".$_DCACHE[settings][insenz][username]."', '$timestamp', 'EST', '$expiration', '0')", 'UNBUFFERED');
				}

				if($c['displayorder'] < -13) {
					$floatthreads = 1;
				} elseif($c['displayorder'] < -11) {
					$globalstick = 1;
				}

				require_once DISCUZ_ROOT.'./include/post.func.php';
				updatepostcredits('+', $c['authorid'], $_DCACHE['settings']['creditspolicy']['post']);

			} else {
				$db->query("UPDATE {$tablepre}campaigns SET status=2, starttime='$timestamp', nextrun=endtime+starttime-begintime, expiration=expiration+starttime-begintime WHERE id='$c[id]' AND type=4", 'UNBUFFERED');
				$db->query("UPDATE {$tablepre}virtualforums SET status='1' WHERE fid='$c[vfid]'", 'UNBUFFERED');
				$_DCACHE['settings']['insenz']['vfstatus'] += 1;
			}
		} elseif($c['status'] == 2) {

			if($c['type'] != 4) {
				if($c['displayorder'] > 0) {
					$db->query("UPDATE {$tablepre}threads SET displayorder=0 $moderatedadd WHERE tid='$c[tid]'", 'UNBUFFERED');
					if($moderated) {
						$db->query("INSERT INTO {$tablepre}threadsmod (tid, uid, username, dateline, action, expiration, status) VALUES ('$c[tid]', '".$_DCACHE[settings][insenz][uid]."', '".$_DCACHE[settings][insenz][username]."', '$timestamp', 'UES', '0', '0')", 'UNBUFFERED');
					}

					if($c['displayorder'] > 3) {
						$floatthreads = 1;
					} elseif($c['displayorder'] > 1) {
						$globalstick = 1;
					}
				}
				if($c['digest'] == -2) {
					$db->query("UPDATE {$tablepre}threads SET views='0', replies='0', digest='-1' WHERE tid='$c[tid]'", 'UNBUFFERED');
				}
			} else {
				$db->query("UPDATE {$tablepre}virtualforums SET status='0' WHERE fid='$c[vfid]'", 'UNBUFFERED');
				$_DCACHE['settings']['insenz']['vfstatus'] -= 1;
			}
			$db->query("UPDATE {$tablepre}campaigns SET status=3, nextrun=expiration WHERE id='$c[id]' AND type='$c[type]'", 'UNBUFFERED');
		} elseif($c['status'] == 3) {
			if($c['type'] != 4) {
				$db->query("UPDATE {$tablepre}threads SET digest=0 WHERE tid='$c[tid]'", 'UNBUFFERED');
			} else {
				$db->query("DELETE FROM {$tablepre}virtualforums WHERE fid='$c[vfid]'", 'UNBUFFERED');
			}
			$db->query("DELETE FROM {$tablepre}campaigns WHERE id='$c[id]' AND type='$c[type]'", 'UNBUFFERED');
		}

	}

	require_once DISCUZ_ROOT.'./include/cache.func.php';
	$query = $db->query("SELECT nextrun FROM {$tablepre}campaigns WHERE nextrun>'$timestamp' ORDER BY nextrun LIMIT 1");
	$_DCACHE['settings']['insenz']['cronnextrun'] = $db->result($query, 0);
	$_DCACHE['settings']['insenz']['vfstatus'] = max(0, intval($_DCACHE['settings']['insenz']['vfstatus']));
	updatesettings();
	$globalstick && updatecache('globalstick');
	$floatthreads && updatecache('floatthreads');

	@unlink($lockfile);

}

function insenz_onlinestats() {
	global $timestamp, $db, $tablepre, $_DCACHE;

	require_once DISCUZ_ROOT.'./include/cache.func.php';
	$_DCACHE['settings']['insenz']['statsnextrun'] = $timestamp + 3600;
	updatesettings();

	$onlinenum = $db->result_first("SELECT COUNT(*) FROM {$tablepre}sessions WHERE lastactivity>=($timestamp-900)");
	$db->query("REPLACE INTO {$tablepre}statvars (type, variable, value) VALUES ('houronlines', '".gmdate('ymdH', $timestamp + 8 * 3600)."', '$onlinenum')");

}

?>