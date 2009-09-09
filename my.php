<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: my.php 17336 2008-12-15 06:54:54Z monkey $
*/

define('NOROBOT', TRUE);
define('CURSCRIPT', 'my');
require_once './include/common.inc.php';
require_once DISCUZ_ROOT.'./forumdata/cache/cache_forums.php';

$discuz_action = 8;
if(!$discuz_uid) {
	showmessage('not_loggedin', NULL, 'NOPERM');
}

$page = max(1, intval($page));
$start_limit = ($page - 1) * $tpp;

$threadlist = $postlist = array();
$tids = $comma = $threadadd = $postadd = $forumname = $extrafid = $extra = $multipage = '';

if($srchfid = empty($srchfid) ? 0 : intval($srchfid)) {
	$threadadd = "AND t.fid='$srchfid'";
	$postadd = "AND p.fid='$srchfid'";
	$forumname = $_DCACHE['forums'][$srchfid]['name'];
	$extrafid = '&amp;srchfid='.$srchfid;
}

$item = isset($item) ? trim($item) : '';

if(empty($item)) {

	$query = $db->query("SELECT m.*, t.subject, t.fid, t.displayorder, t.lastposter, t.lastpost, t.closed FROM {$tablepre}mythreads m, {$tablepre}threads t
		WHERE m.uid='$discuz_uid' AND m.tid=t.tid $threadadd ORDER BY m.dateline DESC LIMIT 5");
	while($thread = $db->fetch_array($query)) {
		$thread['lastpost'] = dgmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
		$thread['forumname'] = $_DCACHE['forums'][$thread['fid']]['name'];
		$thread['lastposterenc'] = rawurlencode($thread['lastposter']);
		$threadlist[] = $thread;
	}

	$query = $db->query("SELECT m.*, p.fid, p.invisible FROM {$tablepre}myposts m
		INNER JOIN {$tablepre}posts p ON p.pid=m.pid $postadd
		INNER JOIN {$tablepre}threads t ON t.tid=m.tid
		WHERE m.uid = '$discuz_uid' ORDER BY m.dateline DESC LIMIT 5");
	while($post = $db->fetch_array($query)) {
		$post['forumname'] = $_DCACHE['forums'][$post['fid']]['name'];
		$postlist[$post['tid']] = $post;
		$tids .= $comma.$post['tid'];
		$comma = ', ';
	}

	if($tids) {
		$query = $db->query("SELECT tid, subject, lastposter, lastpost FROM {$tablepre}threads WHERE tid IN ($tids)");
		while($thread = $db->fetch_array($query)) {
			$postlist[$thread['tid']]['subject'] = $thread['subject'];
			$postlist[$thread['tid']]['lastposter'] = $thread['lastposter'];
			$postlist[$thread['tid']]['lastpost'] = dgmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
			$postlist[$thread['tid']]['lastposterenc'] = rawurlencode($thread['lastposter']);
		}
	}

} elseif($item == 'grouppermission') {

	require_once './include/forum.func.php';

	$searchgroupid = isset($searchgroupid) ? intval($searchgroupid) : $groupid;
	$grouplist = array();
	$query = $db->query("SELECT groupid, type, grouptitle FROM {$tablepre}usergroups ORDER BY (creditshigher<>'0' || creditslower<>'0'), creditslower");
	while($group = $db->fetch_array($query)) {
		$grouplist[$group['type']] .= '<li><a href="my.php?item=grouppermission&amp;type='.$group['type'].'&amp;searchgroupid='.$group['groupid'].'">'.$group['grouptitle'].'</a></li>';
	}

	$group = $db->fetch_first("SELECT * FROM {$tablepre}usergroups u LEFT JOIN {$tablepre}admingroups a ON u.groupid=a.admingid WHERE u.groupid='$searchgroupid'");
	if(!$group) {
		showmessage('usergroups_nonexistence');
	}
	$group['maxattachsize'] = $group['maxattachsize'] / 1024;
	$group['maxsizeperday'] = $group['maxsizeperday'] / 1024;
	$group['maxbiosize'] = $group['maxbiosize'] ? $group['maxbiosize'] : 200;

	include template('my_grouppermission');
	exit;

} elseif($item == 'threads') {

	if($filter == 'recyclebin') {
		$threadadd .= " AND t.displayorder='-1'";
	} elseif($filter == 'aduit') {
		$threadadd .= " AND t.displayorder='-2'";
	} elseif($filter == 'close') {
		$threadadd .= " AND t.closed='1'";
	} elseif($filter == 'common') {
		$threadadd .= " AND t.displayorder>='0' AND t.closed='0'";
	}
	$fidadd = $srchfid ? "&amp;srchfid=$srchfid" : '';

	$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}mythreads m, {$tablepre}threads t WHERE m.uid='$discuz_uid' $threadadd AND m.tid=t.tid");
	$multipage = multi($num, $tpp, $page, 'my.php?item=threads'.$fidadd.$extrafid);

	$query = $db->query("SELECT m.*, t.subject, t.fid, t.displayorder, t.closed, t.lastposter, t.lastpost FROM {$tablepre}mythreads m, {$tablepre}threads t
		WHERE m.uid = '$discuz_uid' $threadadd AND m.tid=t.tid ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
	while($thread = $db->fetch_array($query)) {
		$thread['lastpost'] = dgmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
		$thread['forumname'] = $_DCACHE['forums'][$thread['fid']]['name'];
		$thread['lastposterenc'] = rawurlencode($thread['lastposter']);
		$threadlist[] = $thread;
	}

} elseif($item == 'posts') {

	if($filter == 'recyclebin') {
		$postadd .= " AND p.invisible='-1'";
	} elseif($filter == 'aduit') {
		$postadd .= " AND p.invisible='-2'";
	} elseif($filter == 'close') {
		$threadadd .= " AND t.closed='1'";
	} elseif($filter == 'common') {
		$postadd .= " AND p.invisible='0'";
		$threadadd .= " AND t.displayorder>='0' AND t.closed='0'";
	}
	$fidadd = $srchfid ? "&amp;srchfid=$srchfid" : '';

	$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}myposts m
		INNER JOIN {$tablepre}posts p ON p.pid=m.pid $postadd
		INNER JOIN {$tablepre}threads t ON t.tid=m.tid $threadadd
		WHERE m.uid = '$discuz_uid'");
	$multipage = multi($num, $tpp, $page, 'my.php?item=posts'.$fidadd.$extrafid);

	$query = $db->query("SELECT m.uid, m.tid, m.pid, p.fid, p.invisible, p.dateline FROM {$tablepre}myposts m
		INNER JOIN {$tablepre}posts p ON p.pid=m.pid $postadd
		INNER JOIN {$tablepre}threads t ON t.tid=m.tid $threadadd
		WHERE m.uid = '$discuz_uid' ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
	while($post = $db->fetch_array($query)) {
		$post['forumname'] = $_DCACHE['forums'][$post['fid']]['name'];
		$postlist[$post['tid']] = $post;
		$tids .= $comma.$post['tid'];
		$comma = ', ';
	}

	if($tids) {
		$query = $db->query("SELECT tid, subject AS tsubject, lastposter, lastpost, closed FROM {$tablepre}threads WHERE tid IN ($tids)");
		while($thread = $db->fetch_array($query)) {
			$postlist[$thread['tid']]['tsubject'] = $thread['tsubject'];
			$postlist[$thread['tid']]['lastposter'] = $thread['lastposter'];
			$postlist[$thread['tid']]['closed'] = $thread['closed'];
			$postlist[$thread['tid']]['lastpost'] = dgmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
			$postlist[$thread['tid']]['lastposterenc'] = rawurlencode($thread['lastposter']);
		}
	}

} elseif(in_array($item, array('favorites', 'subscriptions'))) {

	if($fid && empty($forum['allowview'])) {
		if(!$forum['viewperm'] && !$readaccess) {
			showmessage('group_nopermission', NULL, 'NOPERM');
		} elseif($forum['viewperm'] && !forumperm($forum['viewperm'])) {
			showmessage('forum_nopermission', NULL, 'NOPERM');
		}
	}

	if($item == 'favorites') {

		$ftid = $type == 'thread' || $tid ? 'tid' : 'fid';
		$type = $type == 'thread' || $tid ? 'thread' : 'forum';
		$extra .= $srchfid ? '&amp;type='.$type : '';

		if(($fid || $tid) && !submitcheck('favsubmit')) {

			if($db->result_first("SELECT COUNT(*) FROM {$tablepre}favorites WHERE uid='$discuz_uid' AND $ftid>'0'") >= $maxfavorites) {
				showmessage('favorite_is_full', 'my.php?item=favorites&type='.$type);
			}

			if($db->result_first("SELECT $ftid FROM {$tablepre}favorites WHERE uid='$discuz_uid' AND $ftid='${$ftid}' LIMIT 1")) {
				showmessage('favorite_exists');
			} else {
				$db->query("INSERT INTO {$tablepre}favorites (uid, $ftid) VALUES ('$discuz_uid', '${$ftid}')");
				showmessage('favorite_add_succeed', dreferer());
			}

		} elseif(!$fid && !$tid) {

			if(!submitcheck('favsubmit')) {

				$favlist = array();
				if($type == 'forum') {
					$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}favorites fav, {$tablepre}forums f
						WHERE fav.uid = '$discuz_uid' AND fav.fid=f.fid");
					$multipage = multi($num, $tpp, $page, "my.php?item=favorites&amp;type=forum$extrafid");

					$query = $db->query("SELECT f.fid, f.name, f.threads, f.posts, f.todayposts, f.lastpost
						FROM {$tablepre}favorites fav, {$tablepre}forums f
						WHERE fav.fid=f.fid AND fav.uid='$discuz_uid' ORDER BY f.lastpost DESC LIMIT $start_limit, $tpp");

					while($fav = $db->fetch_array($query)) {
						$fav['lastposterenc'] = rawurlencode($fav['lastposter']);
						$fav['lastpost'] = dgmdate("$dateformat $timeformat", $fav['lastpost'] + $timeoffset * 3600);
						$favlist[] = $fav;
					}
				} else {
					$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}favorites fav, {$tablepre}threads t
						WHERE fav.uid = '$discuz_uid' AND fav.tid=t.tid AND t.displayorder>='0' $threadadd");
					$multipage = multi($num, $tpp, $page, "my.php?item=favorites&amp;type=thread$extrafid");

					$query = $db->query("SELECT t.tid, t.fid, t.subject, t.replies, t.lastpost, t.lastposter, f.name
						FROM {$tablepre}favorites fav, {$tablepre}threads t, {$tablepre}forums f
						WHERE fav.tid=t.tid AND t.displayorder>='0' AND fav.uid='$discuz_uid' AND t.fid=f.fid $threadadd
						ORDER BY t.lastpost DESC LIMIT $start_limit, $tpp");

					while($fav = $db->fetch_array($query)) {
						$fav['lastposterenc'] = rawurlencode($fav['lastposter']);
						$fav['lastpost'] = dgmdate("$dateformat $timeformat", $fav['lastpost'] + $timeoffset * 3600);
						$favlist[] = $fav;
					}
				}

			} else {

				if($ids = implodeids($delete)) {
					$db->query("DELETE FROM {$tablepre}favorites WHERE uid='$discuz_uid' AND $ftid IN ($ids)", 'UNBUFFERED');
				}
				showmessage('favorite_update_succeed', dreferer());
			}

		}
	} else {

		if(isset($subadd) && !submitcheck('subsubmit')) {

			$subadd = intval($subadd);

			if($pricethread = $db->result_first("SELECT price FROM {$tablepre}threads WHERE tid='$subadd'")) {
				$query = $db->query("SELECT tid FROM {$tablepre}paymentlog WHERE tid='$subadd' AND uid='$discuz_uid'");
				if(!$db->num_rows($query)) {
					showmessage('subscription_nopermission');
				}
			}

			if($db->result_first("SELECT COUNT(*) FROM {$tablepre}subscriptions WHERE uid='$discuz_uid'") >= $maxsubscriptions) {
				showmessage('subscription_is_full', 'my.php?item=subscriptions');
			}

			if($db->result_first("SELECT tid FROM {$tablepre}subscriptions WHERE tid='$subadd' AND uid='$discuz_uid' LIMIT 1")) {
				showmessage('subscription_exists');
			} else {
				$db->query("UPDATE {$tablepre}threads SET subscribed='1' WHERE tid='$subadd'");
				$db->query("INSERT INTO {$tablepre}subscriptions (uid, tid, lastnotify) VALUES ('$discuz_uid', '$subadd', '')");
				showmessage('subscription_add_succeed', dreferer());
			}

		} elseif(empty($subadd)) {

			if(!submitcheck('subsubmit')) {

				$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}subscriptions s, {$tablepre}threads t
					WHERE s.uid = '$discuz_uid' AND s.tid=t.tid $threadadd");
				$multipage = multi($num, $tpp, $page, "my.php?item=subscriptions$extrafid");

				$subslist = array();
				$query = $db->query("SELECT t.tid, t.fid, t.subject, t.replies, t.lastpost, t.lastposter, f.name
					FROM {$tablepre}subscriptions s, {$tablepre}threads t, {$tablepre}forums f
					WHERE t.tid=s.tid AND t.displayorder>='0' AND f.fid=t.fid AND s.uid='$discuz_uid' $threadadd
					ORDER BY t.lastpost DESC LIMIT $start_limit, $tpp");

				while($subs = $db->fetch_array($query)) {
					$subs['lastposterenc'] = rawurlencode($subs['lastposter']);
					$subs['lastpost'] = dgmdate("$dateformat $timeformat", $subs['lastpost'] + $timeoffset * 3600);
					$subslist[] = $subs;
				}
			} else {

				if($ids = implodeids($delete)) {
					$db->query("DELETE FROM {$tablepre}subscriptions WHERE uid='$discuz_uid' AND tid IN ($ids)", 'UNBUFFERED');
				}
				showmessage('subscription_update_succeed', dreferer());

			}
		}
	}
} elseif($item == 'selltrades' || $item == 'buytrades') {

	require_once DISCUZ_ROOT.'./include/trade.func.php';
	include_once language('misc');

	$sqlfield = $item == 'selltrades' ? 'sellerid' : 'buyerid';
	$sqlfilter = '';
	switch($filter) {
		case 'attention': $typestatus = $item; break;
		case 'eccredit'	: $typestatus = 'eccredittrades';
				  $sqlfilter .= $item == 'selltrades' ? 'AND (tl.ratestatus=0 OR tl.ratestatus=1) ' : 'AND (tl.ratestatus=0 OR tl.ratestatus=2) ';
				  break;
		case 'all'	: $typestatus = ''; break;
		case 'success'	: $typestatus = 'successtrades'; break;
		case 'closed'	: $typestatus = 'closedtrades'; break;
		case 'refund'	: $typestatus = 'refundtrades'; break;
		case 'unstart'	: $typestatus = 'unstarttrades'; break;
		default		: $typestatus = 'tradingtrades'; $filter = '';
	}

	$sqlfilter .= $typestatus ? 'AND tl.status IN (\''.trade_typestatus($typestatus).'\')' : '';

	if(!empty($srchkey)) {
		$sqlkey = 'AND tl.subject like \'%'.str_replace('*', '%', addcslashes($srchkey, '%_')).'%\'';
		$extrasrchkey = '&srchkey='.rawurlencode($srchkey);
		$srchkey = dhtmlspecialchars($srchkey);
	} else {
		$sqlkey = $extrasrchkey = $srchkey = '';
	}

	$pid = intval($pid);
	$sqltid = $tid ? 'AND tl.tid=\''.$tid.'\''.($pid ? ' AND tl.pid=\''.$pid.'\'' : '') : '';
	$extra .= $srchfid ? '&amp;filter='.$filter : '';
	$extratid = $tid ? "&amp;tid=$tid".($pid ? "&amp;pid=$pid" : '') : '';

	$num = $db->result_first("SELECT COUNT(*)
			FROM {$tablepre}tradelog tl, {$tablepre}threads t
			WHERE tl.tid=t.tid AND tl.$sqlfield='$discuz_uid'
			$threadadd $sqltid $sqlkey $sqlfilter");

	$multipage = multi($num, $tpp, $page, "my.php?item=$item$extratid$extrafid".($filter ? "&amp;filter=$filter" : '').$extrafid.$extrasrchkey);

	$query = $db->query("SELECT tl.*, tr.aid, t.subject AS threadsubject
			FROM {$tablepre}tradelog tl, {$tablepre}threads t, {$tablepre}trades tr
			WHERE tl.tid=t.tid AND tr.pid=tl.pid AND tr.tid=tl.tid AND tl.$sqlfield='$discuz_uid'
			$threadadd $sqltid $sqlkey $sqlfilter
			ORDER BY tl.lastupdate DESC LIMIT $start_limit, $tpp");

	$tradeloglist = array();
	while($tradelog = $db->fetch_array($query)) {
		$tradelog['lastupdate'] = dgmdate("$dateformat $timeformat", $tradelog['lastupdate'] + $timeoffset * 3600);
		$tradelog['attend'] = trade_typestatus($item, $tradelog['status']);
		$tradelog['status'] = trade_getstatus($tradelog['status']);
		$tradeloglist[] = $tradelog;
	}
} elseif($item	== 'tradestats') {

	$extrasrchkey = $extratid = '';

	require_once DISCUZ_ROOT.'./include/trade.func.php';

	$buystats = $db->fetch_first("SELECT COUNT(*) AS totalitems, SUM(price) AS tradesum FROM {$tablepre}tradelog WHERE buyerid='$discuz_uid' AND status IN ('".trade_typestatus('successtrades')."')");

	$sellstats = $db->fetch_first("SELECT COUNT(*) AS totalitems, SUM(price) AS tradesum FROM {$tablepre}tradelog WHERE sellerid='$discuz_uid' AND status IN ('".trade_typestatus('successtrades')."')");

	$query = $db->query("SELECT status FROM {$tablepre}tradelog WHERE buyerid='$discuz_uid' AND status IN ('".trade_typestatus('buytrades')."')");
	$buyerattend = $db->num_rows($query);
	$attendstatus = array();
	while($status = $db->fetch_array($query)) {
		@$attendstatus[$status['status']]++;
	}

	$query = $db->query("SELECT status FROM {$tablepre}tradelog WHERE sellerid='$discuz_uid' AND status IN ('".trade_typestatus('selltrades')."')");
	$sellerattend = $db->num_rows($query);
	while($status = $db->fetch_array($query)) {
		@$attendstatus[$status['status']]++;
	}

	$goodsbuyer = $db->result_first("SELECT COUNT(*) FROM {$tablepre}tradelog WHERE buyerid='$discuz_uid' AND status IN ('".trade_typestatus('tradingtrades')."')");
	$goodsseller = $db->result_first("SELECT COUNT(*) FROM {$tablepre}trades WHERE sellerid='$discuz_uid' AND closed='0'");
	$eccreditbuyer = $db->result_first("SELECT COUNT(*) FROM {$tablepre}tradelog WHERE status IN ('".trade_typestatus('eccredittrades')."') AND buyerid='$discuz_uid' AND (ratestatus=0 OR ratestatus=2)");
	$eccreditseller = $db->result_first("SELECT COUNT(*) FROM {$tablepre}tradelog WHERE status IN ('".trade_typestatus('eccredittrades')."') AND sellerid='$discuz_uid' AND (ratestatus=0 OR ratestatus=1)");

} elseif($item == 'tradethreads') {

	if(!empty($srchkey)) {
		$sqlkey = 'AND subject like \'%'.str_replace('*', '%', addcslashes($srchkey, '%_')).'%\'';
		$extrasrchkey = '&srchkey='.rawurlencode($srchkey);
		$srchkey = dhtmlspecialchars($srchkey);
	} else {
		$sqlkey = $extrasrchkey = $srchkey = '';
	}

	$sqltid = $tid ? 'AND tid ='.$tid : '';
	$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}trades WHERE sellerid='$discuz_uid' $sqltid $sqlkey");
	$extratid = $tid ? "&amp;tid=$tid" : '';
	$multipage = multi($num, $tpp, $page, "my.php?item=tradethreads$extratid$extrafid$extrasrchkey");

	$tradelist = array();
	$query = $db->query("SELECT * FROM {$tablepre}trades WHERE sellerid='$discuz_uid' $sqltid $sqlkey ORDER BY tradesum DESC, totalitems DESC LIMIT $start_limit, $tpp");
	while($tradethread = $db->fetch_array($query)) {
		$tradethread['lastupdate'] = dgmdate("$dateformat $timeformat", $tradethread['lastupdate'] + $timeoffset * 3600);
		$tradethread['lastbuyer'] = rawurlencode($tradethread['lastbuyer']);
		if($tradethread['expiration']) {
			$tradethread['expiration'] = ($tradethread['expiration'] - $timestamp) / 86400;
			if($tradethread['expiration'] > 0) {
				$tradethread['expirationhour'] = floor(($tradethread['expiration'] - floor($tradethread['expiration'])) * 24);
				$tradethread['expiration'] = floor($tradethread['expiration']);
			} else {
				$tradethread['expiration'] = -1;
			}
		}
		$tradelist[] = $tradethread;
	}
} elseif($item == 'reward') {

	$rewardloglist = Array();

	if($type == 'stats') {

		$questions = $db->fetch_first("SELECT COUNT(*) AS total, SUM(ABS(netamount)) AS totalprice FROM {$tablepre}rewardlog WHERE authorid='$discuz_uid'");
		$questions['total'] = $questions['total'] ? $questions['total'] : 0;
		$questions['totalprice'] = $questions['totalprice'] ? $questions['totalprice'] : 0;
		$questions['solved'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}rewardlog WHERE authorid='$discuz_uid' and answererid>0");
		$questions['percent'] = number_format(($questions['total'] != 0) ? round($questions['solved'] / $questions['total'] * 100, 2) : 0, 2, '.', '');
		$answers['total'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}rewardlog WHERE answererid='$discuz_uid'");
		$answeradopted = $db->fetch_first("SELECT COUNT(*) AS tids, SUM(ABS(t.price)) AS totalprice
			FROM {$tablepre}rewardlog r
			LEFT JOIN {$tablepre}threads t USING(tid)
			WHERE r.authorid>0 and r.answererid='$discuz_uid'");
		$answers['adopted'] = $answeradopted['tids'];
		$answers['totalprice'] = $answeradopted['totalprice'] ? $answeradopted['totalprice'] : 0;
		$answers['percent'] = number_format(($answers['total'] != 0) ? round($answers['adopted'] / $answers['total'] * 100, 2) : 0, 2, '.', '');

	} elseif($type == 'answer') {

		$extra .= $srchfid ? '&amp;type=answer' : '';
		$filter = isset($filter) && in_array($filter, array('adopted', 'unadopted')) ? $filter : '';
		$sqlfilter = empty($filter) ? '' : ($filter == 'adopted' ? 'AND r.authorid>0' : 'AND r.authorid=0');

		$page = max(1, intval($page));
		$start_limit = ($page - 1) * $tpp;
		$multipage = multi($db->result_first("SELECT COUNT(*) FROM {$tablepre}rewardlog r WHERE answererid='$discuz_uid'"), $tpp, $page, "my.php?item=reward&amp;type=answer$extrafid");

		$query = $db->query("SELECT r.*, t.subject, t.price, m.uid, m.username, f.fid, f.name
			FROM {$tablepre}rewardlog r
			LEFT JOIN {$tablepre}threads t ON t.tid=r.tid
			LEFT JOIN {$tablepre}forums f ON f.fid=t.fid
			LEFT JOIN {$tablepre}members m ON m.uid=t.authorid
			WHERE r.answererid='$discuz_uid' $threadadd $sqlfilter
			ORDER BY r.dateline DESC
			LIMIT $start_limit, $tpp");

		while($rewardlog = $db->fetch_array($query)) {
			$rewardlog['dateline'] = dgmdate("$dateformat $timeformat", $rewardlog['dateline'] + $timeoffset * 3600);
			$rewardlog['price'] = abs($rewardlog['price']);
			$rewardloglist[] = $rewardlog;
		}

	} elseif($type == 'question') {

		$extra .= $srchfid ? '&amp;type=question' : '';
		$filter = in_array($filter, array('solved', 'unsolved')) ? $filter : '';
		$sqlfilter = empty($filter) ? '' : ($filter == 'solved' ? 'AND r.answererid>0' : 'AND r.answererid=0');

		$page = max(1, intval($page));
		$start_limit = ($page - 1) * $tpp;
		$multipage = multi($db->result_first("SELECT COUNT(*) FROM {$tablepre}rewardlog r WHERE authorid='$discuz_uid' $sqlfilter"), $tpp, $page, "my.php?item=reward&amp;type=question&amp;filter=$filter$extrafid");

		$query = $db->query("SELECT r.*, t.subject, t.price, m.uid, m.username, f.fid, f.name
			FROM {$tablepre}rewardlog r
			LEFT JOIN {$tablepre}threads t ON t.tid=r.tid
			LEFT JOIN {$tablepre}forums f ON f.fid=t.fid
			LEFT JOIN {$tablepre}members m ON m.uid=r.answererid
			WHERE r.authorid='$discuz_uid' $threadadd $sqlfilter
			ORDER BY r.dateline DESC
			LIMIT $start_limit, $tpp");

		while($rewardlog = $db->fetch_array($query)) {
			$rewardlog['dateline'] = dgmdate("$dateformat $timeformat", $rewardlog['dateline'] + $timeoffset * 3600);
			$rewardlog['price'] = abs($rewardlog['price']);
			$rewardloglist[] = $rewardlog;
		}

	} else {
		showmessage('undefined_action');
	}

} elseif($item == 'activities') {

	$ended = isset($ended) && in_array($ended, array('yes', 'no')) ? $ended : '';
	$sign = $ascadd = '';
	$activity = array();

	if($type == 'orig') {

		if($ended == 'yes') {
			$sign = " AND starttimefrom<'$timestamp'";
			$ascadd = 'DESC';
		} elseif($ended == 'no') {
			$sign = " AND starttimefrom>='$timestamp'";
		}
		
		$extra .= $srchfid ? '&amp;type=orig' : '';

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}activities a LEFT JOIN {$tablepre}threads t USING(tid) WHERE a.uid='$discuz_uid' AND t.special='4' $threadadd $sign");
		$multipage = multi($num, $tpp, $page, "my.php?item=activities&amp;type=orig&amp;ended=$ended$extrafid");

		$query = $db->query("SELECT a.*, t.subject FROM {$tablepre}activities a LEFT JOIN {$tablepre}threads t USING(tid) WHERE a.uid='$discuz_uid' AND t.special='4' $threadadd $sign ORDER BY starttimefrom $ascadd LIMIT $start_limit, $tpp");
		while($tempact = $db->fetch_array($query)) {
			$tempact['starttimefrom'] = dgmdate("$dateformat $timeformat", $tempact['starttimefrom'] + $timeoffset * 3600);
			$activity[] = $tempact;
		}
	} else {
		
		if($ended == 'yes') {
			$sign = " AND verified='1'";
		} elseif($ended == 'no') {
			$sign = " AND verified='0'";
		}

		$extra .= $srchfid ? '&amp;type='.$type : '';

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}activityapplies aa LEFT JOIN {$tablepre}activities a USING(tid) LEFT JOIN {$tablepre}threads t USING(tid) WHERE a.uid='$discuz_uid' $threadadd$sign");
		$multipage = multi($num, $tpp, $page, "my.php?item=activities&amp;type=apply&amp;ended=$ended$extrafid");

		$query = $db->query("SELECT aa.verified, aa.tid, starttimefrom, a.place, a.cost, t.subject FROM {$tablepre}activityapplies aa LEFT JOIN {$tablepre}activities a USING(tid) LEFT JOIN {$tablepre}threads t USING(tid) WHERE aa.uid='$discuz_uid' $threadadd$sign ORDER BY starttimefrom $ascadd LIMIT $start_limit, $tpp");
		while($tempact = $db->fetch_array($query)) {
			$tempact['starttimefrom'] = dgmdate("$dateformat $timeformat", $tempact['starttimefrom'] + $timeoffset * 3600);
			$activity[] = $tempact;
		}
	}

} elseif($item == 'polls'){

	$polllist = array();

	if($type == 'poll') {

		if($srchfid = intval($srchfid)) {
			$threadadd = "AND fid='$srchfid'";
		}

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}mythreads m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='1' $threadadd AND m.tid=t.tid");

		$multipage = multi($num, $tpp, $page, "my.php?item=polls&amp;type=poll$extrafid");

		$query = $db->query("SELECT t.tid, t.subject, t.fid, t.displayorder, t.closed, t.lastposter, t.lastpost
			FROM {$tablepre}threads t, {$tablepre}mythreads m
			WHERE m.uid='$discuz_uid' AND m.special='1' AND m.tid=t.tid $threadadd
			ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
		while($poll = $db->fetch_array($query)) {
			$poll['lastpost'] = dgmdate("$dateformat $timeformat", $poll['lastpost'] + $timeoffset * 3600);
			$poll['lastposterenc'] = rawurlencode($poll['lastposter']);
			$poll['forumname'] = $_DCACHE['forums'][$poll['fid']]['name'];
			$polllist[] = $poll;
		}

	} else {

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}myposts m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='1' $threadadd AND m.tid=t.tid");
		$multipage = multi($num, $tpp, $page, "my.php?item=polls&amp;type=join$extrafid");

		$query = $db->query("SELECT m.dateline, t.tid, t.fid, t.subject, t.displayorder, t.closed
			FROM {$tablepre}myposts m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='1' AND m.tid=t.tid $threadadd
			ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
		while($poll = $db->fetch_array($query)) {
			$poll['dateline'] = dgmdate("$dateformat $timeformat", $poll['dateline'] + $timeoffset * 3600);
			$poll['forumname'] = $_DCACHE['forums'][$poll['fid']]['name'];
			$polllist[] = $poll;
		}

	}

} elseif($item == 'promotion' && ($creditspolicy['promotion_visit'] || $creditspolicy['promotion_register'])) {

	$promotion_visit = $promotion_register = $space = '';
	foreach(array('promotion_visit', 'promotion_register') as $val) {
		if(!empty($creditspolicy[$val]) && is_array($creditspolicy[$val])) {
			foreach($creditspolicy[$val] as $id => $policy) {
				$$val .= $space.$extcredits[$id]['title'].' +'.$policy;
				$space = '&nbsp;';
			}
		}
	}

} elseif($item == 'debate') {

	$debatelist = array();

	if($type == 'orig') {

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}mythreads m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='5' $threadadd AND m.tid=t.tid");
		$multipage = multi($num, $tpp, $page, "my.php?item=debate&amp;type=orig$extrafid");

		$query = $db->query("SELECT t.tid, t.subject, t.fid, t.displayorder, t.closed, t.lastposter, t.lastpost
			FROM {$tablepre}threads t, {$tablepre}mythreads m
			WHERE m.uid='$discuz_uid' AND m.special='5' AND m.tid=t.tid $threadadd
			ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
		while($debate = $db->fetch_array($query)) {
			$debate['lastpost'] = dgmdate("$dateformat $timeformat", $debate['lastpost'] + $timeoffset * 3600);
			$debate['lastposterenc'] = rawurlencode($debate['lastposter']);
			$debate['forumname'] = $_DCACHE['forums'][$debate['fid']]['name'];
			$debatelist[] = $debate;
		}

	} elseif($type == 'reply') {

		$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}myposts m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='5' $threadadd AND m.tid=t.tid");
		$multipage = multi($num, $tpp, $page, "my.php?item=debate&amp;type=reply$extrafid");

		$query = $db->query("SELECT m.dateline, t.tid, t.fid, t.subject, t.displayorder, t.closed
			FROM {$tablepre}myposts m, {$tablepre}threads t
			WHERE m.uid='$discuz_uid' AND m.special='5' AND m.tid=t.tid $threadadd
			ORDER BY m.dateline DESC LIMIT $start_limit, $tpp");
		while($debate = $db->fetch_array($query)) {
			$debate['dateline'] = dgmdate("$dateformat $timeformat", $debate['dateline'] + $timeoffset * 3600);
			$debate['forumname'] = $_DCACHE['forums'][$debate['fid']]['name'];
			$debatelist[] = $debate;
		}

	}

}  elseif($item == 'video') {

	require_once DISCUZ_ROOT.'./api/video.php';
	$videolist = array();

	$num = $db->result_first("SELECT COUNT(*) FROM {$tablepre}videos WHERE uid='$discuz_uid'");
	$multipage = multi($num, $tpp, $page, "my.php?item=video");

	$query = $db->query("SELECT * FROM {$tablepre}videos WHERE uid='$discuz_uid' ORDER BY dateline DESC LIMIT $start_limit, $tpp");
	while($video = $db->fetch_array($query)) {
		$video['vthumb'] = !$videoinfo['vthumb'] ? VideoClient_Util::getThumbUrl($video['vid'], 'small') : $videoinfo['vthumb'];
		$video['dateline'] = dgmdate("$dateformat $timeformat", $video['dateline'] + $timeoffset * 3600);
		$video['vtime'] = $video['vtime'] ? sprintf("%02d", intval($video['vtime'] / 60)).':'.sprintf("%02d", intval($video['vtime'] % 60)) : '';
		$videolist[] = $video;
	}

	$videonum = count($videolist);
	$videoendrows = '';
	if($colspan = $videonum % 2) {
		while(($colspan - 2) < 0) {
			$videoendrows .= '<td></td>';
			$colspan ++;
		}
		$videoendrows .= '</tr>';
	}

} elseif($item == 'buddylist') {

	include_once DISCUZ_ROOT.'./uc_client/client.php';

	$buddynum = uc_friend_totalnum($discuz_uid);
	if(!submitcheck('buddysubmit', 1)) {

		$buddylist = array();
		$buddies = $buddynum ? uc_friend_ls($discuz_uid, $page, $tpp, $buddynum) : array();
		$multipage = multi($buddynum, $tpp, $page, "my.php?item=buddylist");

		if($buddies) {
			foreach($buddies as $key => $buddy) {
				$buddylist[$buddy['friendid']] = $buddy;
			}
			unset($buddies);
			$query = $db->query("SELECT m.uid, m.gender, mf.msn, s.uid AS online FROM {$tablepre}members m 
				LEFT JOIN {$tablepre}sessions s ON s.uid=m.uid
				LEFT JOIN {$tablepre}memberfields mf ON mf.uid=m.uid
				WHERE m.uid IN (".implodeids(array_keys($buddylist)).")");
			while($member = $db->fetch_array($query)) {
				$uid = $member['uid'];
				if(isset($buddylist[$uid])) {
					$buddylist[$uid]['avatar'] = discuz_uc_avatar($uid, 'small');
					$buddylist[$uid]['gender'] = $member['gender'];
					$buddylist[$uid]['online'] = $member['online'];
					$buddylist[$uid]['msn'] = explode("\t", $member['msn']);
				} else {
					unset($buddylist[$uid]);
				}
			}
		}

	} else {

		$buddyarray = $buddynum ? uc_friend_ls($discuz_uid, 1, $buddynum, $buddynum) : array();

		if($action == 'edit') {

			if($comment = cutstr(dhtmlspecialchars($comment), 255)) {
				$friendid = intval($friendid);
				uc_friend_delete($discuz_uid, array($friendid));
				uc_friend_add($discuz_uid, $friendid, $comment);
			}

		} elseif($action == 'delete') {

			$friendid = intval($friendid);
			uc_friend_delete($discuz_uid, array($friendid));

		} else {

			$buddyarraynew = array();
			if($buddyarray) {
				foreach($buddyarray as $buddy) {
					$buddyarraynew[$buddy['friendid']] = $buddy;
				}
			}
			$buddyarray = $buddyarraynew;unset($buddyarraynew);

			if(($newbuddy && $newbuddy != $discuz_userss) || ($newbuddyid && $newbuddyid != $discuz_uid)) {
				$newbuddyid && $newbuddy = $db->result_first("SELECT username FROM {$tablepre}members WHERE uid='$newbuddyid'", 0);

				if($buddyid = uc_get_user($newbuddy)) {
					if(isset($buddyarray[$buddyid[0]])) {
						showmessage('buddy_add_invalid');
					}
					if(uc_friend_add($discuz_uid, $buddyid[0], cutstr(dhtmlspecialchars($newdescription), 255))) {
						if($ucappopen['UCHOME']) {
							sendpm($buddyid[0], 'buddy_new_subject', 'buddy_new_uch_message', 0);
						} else {
							sendpm($buddyid[0], 'buddy_new_subject', 'buddy_new_message', 0);
						}
					} else {
						showmessage('buddy_add_ignore');
					}
				} else {
					showmessage('username_nonexistence');
				}
			}
		}

		showmessage('buddy_update_succeed', 'my.php?item=buddylist');

	}

} else {

	showmessage('undefined_action', NULL, 'HALTED');

}

include template('my');

?>