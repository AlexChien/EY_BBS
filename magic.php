<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: magic.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

define('CURSCRIPT', 'magic');

require_once './include/common.inc.php';

if(!$discuz_uid) {
	showmessage('not_loggedin', NULL, 'HALTED');
} elseif(!$creditstransextra[3]) {
	showmessage('credits_transaction_disabled');
} elseif(!$magicstatus && $adminid != 1) {
	showmessage('magics_close');
} elseif(!$allowmagics) {
	showmessage('magics_perm');
}

require_once DISCUZ_ROOT.'./include/magic.func.php';
require_once DISCUZ_ROOT.'./forumdata/cache/cache_magics.php';

$magiclist = array();
$page = max(1, intval($page));
$start_limit = ($page - 1) * $tpp;

$action = empty($action) ? 'index' : $action;
$comma = $typeadd = $filteradd = $forumperm = $targetgroupperm = '';
$magicarray = is_array($_DCACHE['magics']) ? $_DCACHE['magics'] : array();

$operationarray = array('use', 'sell', 'drop', 'give', 'my', 'buy', 'down', 'uselog', 'buylog', 'givelog', 'receivelog', 'marketlog');
$operation = !empty($operation) && in_array($operation, $operationarray) ? $operation : '';

$totalweight = getmagicweight($discuz_uid, $magicarray);
$magicid = intval($magicid);

if(!empty($typeid)) {
	$typeadd = '&amp;typeid='.intval($typeid);
	$filteradd = "AND type='".intval($typeid)."'";
}

if($action == 'index') {

	$discuz_action = 170;

	if(empty($operation)) {

		include language('magics');
		$operation = $magicendrows = $mymagicendrows = '';
		$magiccount = $db->result_first("SELECT COUNT(*) FROM {$tablepre}magics WHERE available='1' $filteradd");
		$multipage = multi($magiccount, $tpp, $page, "magic.php?action=index$typeadd");

		$query = $db->query("SELECT magicid, name, identifier, description, price, num, salevolume, weight, type FROM {$tablepre}magics WHERE available='1' $filteradd ORDER BY displayorder LIMIT $start_limit,$tpp");
		while($magic = $db->fetch_array($query)) {
			$magic['price'] = $magicsdiscount ? intval($magic['price'] * ($magicsdiscount / 10)) : intval($magic['price']);
			$magic['pic'] = strtolower($magic['identifier']).'.gif';
			$magic['type'] = $lang['magics_type_'.$magic['type']];
			$magiclist[] = $magic;
		}

		$magicnum = count($magiclist);
		if($colspan = $magicnum % 2) {
			while(($colspan - 2) < 0) {
				$magicendrows .= '<td class="altbg2"></td>';
				$colspan ++;
			}
			$magicendrows .= '</tr>';
		}

		$pid = !empty($pid) ? intval($pid) : 0;
		$mymagiccount = $db->result_first("SELECT COUNT(*) FROM {$tablepre}membermagics mm, {$tablepre}magics m WHERE mm.uid='$discuz_uid' AND mm.magicid=m.magicid");

		$mymultipage = multi($magiccount, $tpp, $page, "magic.php?action=index&pid=$pid$typeadd");
		$query = $db->query("SELECT mm.num, m.magicid, m.name, m.identifier, m.description, m.weight
				FROM {$tablepre}membermagics mm
				LEFT JOIN {$tablepre}magics m ON mm.magicid=m.magicid
				WHERE mm.uid='$discuz_uid' LIMIT $start_limit,$tpp");
		while($mymagic = $db->fetch_array($query)) {
			$mymagic['pic'] = strtolower($mymagic['identifier']).'.gif';
			$mymagic['weight'] = intval($mymagic['weight'] * $mymagic['num']);
			$mymagiclist[] = $mymagic;
		}

		$mymagicnum = count($mymagiclist);
		if($colspan = $mymagicnum % 2) {
			while(($colspan - 2) < 0) {
				$mymagicendrows .= '<td class="altbg2"></td>';
				$colspan ++;
			}
			$mymagicendrows .= '</tr>';
		}

		include template('magic_index');

	} elseif($operation == 'buy') {

		$magic = $db->fetch_first("SELECT * FROM {$tablepre}magics WHERE magicid='$magicid'");
		if(!$magic) {
			showmessage('magics_nonexistence');
		}

		if(!$magic['available']) {
			showmessage('magics_nonexistence');
		}

		$magic['price'] = $magicsdiscount ? intval($magic['price'] * ($magicsdiscount / 10)) : intval($magic['price']);

		if(!submitcheck('operatesubmit')) {

			$magic['pic'] = strtolower($magic['identifier']).'.gif';
			$magicperm = unserialize($magic['magicperm']);
			$useperm = (strstr($magicperm['usergroups'], "\t$groupid\t") || !$magicperm['usergroups']) ? '1' : '0';

			if($magicperm['targetgroups']) {
				require_once DISCUZ_ROOT.'./forumdata/cache/cache_usergroups.php';
				foreach(explode("\t", $magicperm['targetgroups']) as $groupid) {
					if(isset($_DCACHE['usergroups'][$groupid])) {
						$targetgroupperm .= $comma.$_DCACHE['usergroups'][$groupid]['grouptitle'];
						$comma = '&nbsp;';
					}
				}
			}

			if($magicperm['forum']) {
				require_once DISCUZ_ROOT.'./forumdata/cache/cache_forums.php';
				foreach(explode("\t", $magicperm['forum']) as $fid) {
					if(isset($_DCACHE['forums'][$fid])) {
						$forumperm .= $comma.'<a href="forumdisplay.php?fid='.$fid.'" target="_blank">'.$_DCACHE['forums'][$fid]['name'].'</a>';
						$comma = '&nbsp;';
					}
				}
			}

			include template('magic_opreation');

		} else {

			$magicnum = intval($magicnum);
			$magic['weight'] = $magic['weight'] * $magicnum;
			$totalprice = $magic['price'] * $magicnum;
			$toname = dhtmlspecialchars(trim($tousername));

			if(${'extcredits'.$creditstransextra[3]} < $totalprice) {
				showmessage('magics_credits_no_enough');
			} elseif($magic['num'] < $magicnum) {
				showmessage('magics_num_no_enough');
			} elseif(!$magicnum || $magicnum < 0) {
				showmessage('magics_num_invalid');
			}

			if(!empty($toname) && $allowmagics > 1 && $checkgive) {
				givemagic($toname, $magic['magicid'], $magicnum, $magic['num'], $totalprice);
			} else {
				getmagic($magic['magicid'], $magicnum, $magic['weight'], $totalweight, $discuz_uid, $maxmagicsweight);
				updatemagiclog($magic['magicid'], '1', $magicnum, $magic['price'], '0', $discuz_uid);
			}

			$db->query("UPDATE {$tablepre}magics SET num=num+(-'$magicnum'), salevolume=salevolume+'$magicnum' WHERE magicid='$magicid'");
			$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[3]=extcredits$creditstransextra[3]+(-'$totalprice') WHERE uid='$discuz_uid'");
			showmessage('magics_succeed', 'magic.php?action=index');

		}

	} else {

		$magic = $db->fetch_first("SELECT m.*, mm.num
				FROM {$tablepre}membermagics mm
				LEFT JOIN {$tablepre}magics m ON mm.magicid=m.magicid
				WHERE mm.uid='$discuz_uid' AND mm.magicid='$magicid'");
		if(!$magic) {
			showmessage('magics_nonexistence','magic.php?action=index');
		} elseif(!$magic['num']) {
			$db->query("DELETE FROM {$tablepre}membermagics WHERE uid='$discuz_uid' AND magicid='$magic[magicid]'");
			showmessage('magics_nonexistence','magic.php?action=index');
		}
		$magicperm = unserialize($magic['magicperm']);

		if(!submitcheck('operatesubmit')) {

			$magic['pic'] = strtolower($magic['identifier']).".gif";
			$useperm = (strstr($magicperm['usergroups'], "\t$groupid\t") || empty($magicperm['usergroups'])) ? '1' : '0';
			$magic['discountprice'] = intval($magic['price'] * ($magicdiscount / 100));

			if($magic['num'] <= 0) {
				$db->query("DELETE FROM {$tablepre}membermagics WHERE uid='$discuz_uid' AND magicid='$magic[magicid]'");
				showmessage('magics_nopermission','magic.php?action=index');
			}

			if($magicperm['targetgroups']) {
				require_once DISCUZ_ROOT.'./forumdata/cache/cache_usergroups.php';
				foreach(explode("\t", $magicperm['targetgroups']) as $groupid) {
					if(isset($_DCACHE['usergroups'][$groupid])) {
						$targetgroupperm .= $comma.$_DCACHE['usergroups'][$groupid]['grouptitle'];
						$comma = '&nbsp;';
					}
				}
			}

			if($magicperm['forum']) {
				require_once DISCUZ_ROOT.'./forumdata/cache/cache_forums.php';
				foreach(explode("\t", $magicperm['forum']) as $fid) {
					if(isset($_DCACHE['forums'][$fid])) {
						$forumperm .= $comma.'<a href="forumdisplay.php?fid='.$fid.'" target="_blank">'.$_DCACHE['forums'][$fid]['name'].'</a>';
						$comma = '&nbsp;';
					}
				}
			}

			$magic['weight'] = intval($magicarray[$magic['magicid']]['weight'] * $magic['num']);

			if($operation == 'use') {

				include language('magics');
				$username = dhtmlspecialchars($username);

				if(!submitcheck('usesubmit')) {
					$magicselect = array($magicid => 'selected="selected"');
					$magiclist = magicselect($discuz_uid, $typeid, array('magic' => array('magicid', 'name', 'description')));
				}

				if(!@include_once DISCUZ_ROOT.($magicfile = "./include/magic/$magic[filename]")) {
					showmessage('magics_filename_nonexistence');
				}

			}

			include template('magic_opreation');

		} else {

			$magicnum = intval($magicnum);
			$price = intval($price);

			if(!$magicnum || $magicnum < 0) {
				showmessage('magics_num_invalid');
			} elseif($magicnum > $magic['num']) {
				showmessage('magics_amount_no_enough');
			}

			$magic['weight'] = intval($magic['weight'] * $magicnum);

			if($operation == 'sell') {

				if($price) {
					$action = 'market';
					if(empty($magicmarket)) {
						showmessage('magics_market_close');
					} elseif($price < 0) {
						showmessage('magics_price_invalid');
					} elseif(!empty($maxmagicprice) && $price > $magic['price'] * (1 + $maxmagicprice / 100)) {
						showmessage('magics_price_high');
					} elseif(floor($price * (1 - $creditstax)) == 0) {
						showmessage('magics_price_iszero');
					}

					$db->query("INSERT INTO {$tablepre}magicmarket (magicid, uid, username, price, num) VALUES ('$magicid', '$discuz_uid', '$discuz_user', '$price', '$magicnum')", 'UNBUFFERED');
					updatemagiclog($magic['magicid'], '4', $magicnum, $price);
				} else {
					$action = 'index';
					$discountprice = intval($magic['price'] * ($magicdiscount / 100)) * $magicnum;
					$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[3]=extcredits$creditstransextra[3]+'$discountprice' WHERE uid='$discuz_uid'");
				}

				usemagic($magic['magicid'], $magic['num'], $magicnum);
				showmessage('magics_succeed', '', 1);

			} elseif($operation == 'drop') {

				usemagic($magic['magicid'], $magic['num'], $magicnum);
				updatemagiclog($magic['magicid'], '2', $magicnum, $price);
				showmessage('magics_succeed', '', 1);

			} elseif($operation == 'give') {

				if($allowmagics < 2) {
					showmessage('magics_nopermission');
				}

				$toname = dhtmlspecialchars(trim($tousername));
				$magicnum = intval($magicnum);
				if(empty($tousername)) {
					showmessage('magics_username_nonexistence');
				}
				givemagic($toname, $magic['magicid'], $magicnum, $magic['num'], '0');

			}
		}
	}

} elseif($action =='market') {

	$discuz_action = 172;

	if(empty($magicmarket)) {
		showmessage('magics_market_close');
	}

	if(empty($operation) || $operation == 'my' || submitcheck('searchsubmit')) {

		$ascdesc = isset($ascdesc) && in_array(strtoupper($ascdesc), array('ASC', 'DESC')) ? strtoupper($ascdesc) : 'DESC';
		$orderby = isset($orderby) && in_array(strtolower($orderby), array('price', 'num')) ? strtolower($orderby) : '';
		$magicid = isset($magicid) && is_numeric($magicid) ? intval($magicid) : 0;

		$magicadd = !empty($magicid) ? '&amp;magicid='.$magicid : '';
		$magicadd .= !empty($orderby) ? '&amp;orderby='.$orderby : '';
		$magicadd .= !empty($ascdesc) ? '&amp;ascdesc='.$ascdesc : '';

		$filteradd = $operation == 'my' ? 'WHERE uid=\''.$discuz_uid.'\'' : '';
		$filteradd .= $magicid && empty($operation) ? 'WHERE magicid=\''.intval($magicid).'\'' : '';
		$filteradd .= $orderby ? " ORDER BY $orderby $ascdesc" : '';

		$check = array();
		$check[$magicid] = $check[$orderby] = $check[$ascdesc] = 'selected="selected"';

		$magiccount = $db->result_first("SELECT COUNT(*) FROM {$tablepre}magicmarket $filteradd");

		$multipage = multi($magiccount, $tpp, $page, "magic.php?action=market$magicadd");
		$query = $db->query("SELECT * FROM {$tablepre}magicmarket $filteradd LIMIT $start_limit,$tpp");
		while($magic = $db->fetch_array($query)) {
			$magic['weight'] = $magicarray[$magic['magicid']]['weight'];
			$magic['name'] = $magicarray[$magic['magicid']]['name'];
			$magic['description'] = $magicarray[$magic['magicid']]['description'];
			$magiclist[] = $magic;
		}

		$magicselect = '';
		foreach($magicarray as $id => $magic) {
			if($magic['available']) {
				$magicselect .= '<option value="'.$id.'" '.$check[$id].'>'.$magic['name'].'</option>';
			}
		}

		include template('magic_market');

	} elseif($operation == 'buy' || $operation == 'down') {

		$magicnum = intval($magicnum);

		if($magic = $db->fetch_first("SELECT mid, magicid, uid, username, price, num FROM {$tablepre}magicmarket WHERE mid='$mid'")) {
			$magic['pic'] = strtolower($magicarray[$magic['magicid']]['identifier']).".gif";
			$magic['name'] = $magicarray[$magic['magicid']]['name'];
			$magic['marketprice'] = $magicarray[$magic['magicid']]['price'];
			$magic['description'] = $magicarray[$magic['magicid']]['description'];
			$magic['weight'] = $magicarray[$magic['magicid']]['weight'];
		}

		if(($operation == 'buy' && $magic['uid'] == $discuz_uid) || ($operation == 'down' && $magic['uid'] != $discuz_uid)) {
			showmessage('magics_market_operation_error');
		}

		if(submitcheck('buysubmit')) {

			$magicnum = intval($magicnum);
			$magicprice = $magic['price'] * $magicnum;
			$magicweight = $magic['weight'] * $magicnum;

			if(!$magicnum || $magicnum < 0) {
				showmessage('magics_num_invalid');
			} elseif($magic['num'] < $magicnum) {
				showmessage('magics_amount_no_enough');
			}

			if(${'extcredits'.$creditstransextra[3]} < $magicprice) {
				showmessage('magics_credits_no_enough');
			}

			getmagic($magic['magicid'], $magicnum, $magicweight, $totalweight, $discuz_uid, $maxmagicsweight);

			$totalcredit = floor($magicprice * (1 - $creditstax));
			$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[3]=extcredits$creditstransextra[3]+'$totalcredit' WHERE uid='$magic[uid]'");
			$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[3]=extcredits$creditstransextra[3]+(-'$magicprice') WHERE uid='$discuz_uid'");
			sendpm($magic['uid'], 'magics_sell_subject', 'magics_sell_message', 0);

			updatemagiclog($magic['magicid'], '5', $magicnum, $magic['price'], '0', $discuz_uid);
			marketmagicnum($magic['mid'], $magic['num'], $magicnum);
			showmessage('magics_succeed', 'magic.php?action=index');

		}

		if(submitcheck('downsubmit')) {

			if($magic['num'] < $magicnum || $magicnum < 0) {
				showmessage('magics_amount_no_enough');
			}

			$magic['weight'] = $magic['weight'] * $magicnum;
			getmagic($magic['magicid'], $magicnum, $magic['weight'], $totalweight, $discuz_uid, $maxmagicsweight);
			updatemagiclog($magic['magicid'], '6', $magicnum, '0', '0', $discuz_uid);
			marketmagicnum($magic['mid'], $magic['num'], $magicnum);
			showmessage('magics_succeed', '', 1);

		}

		include template('magic_market');

	}

} elseif($action == 'log') {

	$discuz_action = 173;

	$loglist = array();
	if($operation == 'uselog') {
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}magiclog WHERE action='2' AND uid='$discuz_uid'");
		$multipage = multi($db->result($query, 0), $tpp, $page, 'magic.php?action=log&amp;operation=uselog');

		$query = $db->query("SELECT ml.*, me.username FROM {$tablepre}magiclog ml
			LEFT JOIN {$tablepre}members me ON me.uid=ml.uid
			WHERE ml.action='2' AND ml.uid='$discuz_uid' ORDER BY ml.dateline DESC
			LIMIT $start_limit, $tpp");
		while($log = $db->fetch_array($query)) {
			$log['dateline'] = dgmdate("$dateformat $timeformat", $log['dateline'] + $timeoffset * 3600);
			$log['name'] = $magicarray[$log['magicid']]['name'];
			if($log['targettid'] || $log['targetpid']) {
				$log['target'] = 'viewthread.php?tid='.$log['targettid'];
			} elseif($log['targetuid']) {
				$log['target'] = 'space.php?uid='.$log['targetuid'];
			} else {
				$log['target'] = '';
			}
			$loglist[] = $log;
		}

	} elseif($operation == 'buylog') {
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}magiclog WHERE uid='$discuz_uid' AND action='1'");
		$multipage = multi($db->result($query, 0), $tpp, $page, 'magic.php?action=log&amp;operation=buylog');

		$query = $db->query("SELECT * FROM {$tablepre}magiclog
			WHERE uid='$discuz_uid' AND action='1' ORDER BY dateline DESC
			LIMIT $start_limit, $tpp");
		while($log = $db->fetch_array($query)) {
			$log['dateline'] = dgmdate("$dateformat $timeformat", $log['dateline'] + $timeoffset * 3600);
			$log['name'] = $magicarray[$log['magicid']]['name'];
			$loglist[] = $log;
		}

	} elseif($operation == 'givelog') {
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}magiclog WHERE uid='$discuz_uid' AND action='3'");
		$multipage = multi($db->result($query, 0), $tpp, $page, 'magic.php?action=log&amp;operation=givelog');

		$query = $db->query("SELECT ml.*, me.username FROM {$tablepre}magiclog ml
			LEFT JOIN {$tablepre}members me ON me.uid=ml.targetuid
			WHERE ml.uid='$discuz_uid' AND ml.action='3' ORDER BY ml.dateline DESC
			LIMIT $start_limit, $tpp");
		while($log = $db->fetch_array($query)) {
			$log['dateline'] = dgmdate("$dateformat $timeformat", $log['dateline'] + $timeoffset * 3600);
			$log['name'] = $magicarray[$log['magicid']]['name'];
			$loglist[] = $log;
		}

	} elseif($operation == 'receivelog') {
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}magiclog WHERE targetuid='$discuz_uid' AND action='3'");
		$multipage = multi($db->result($query, 0), $tpp, $page, 'magic.php?action=log&amp;operation=receivelog');

		$query = $db->query("SELECT ml.*, me.username FROM {$tablepre}magiclog ml
			LEFT JOIN {$tablepre}members me ON me.uid=ml.uid
			WHERE ml.targetuid='$discuz_uid' AND ml.action='3' ORDER BY ml.dateline DESC
			LIMIT $start_limit, $tpp");
		while($log = $db->fetch_array($query)) {
			$log['dateline'] = dgmdate("$dateformat $timeformat", $log['dateline'] + $timeoffset * 3600);
			$log['name'] = $magicarray[$log['magicid']]['name'];
			$loglist[] = $log;
		}

	} elseif($operation == 'marketlog') {
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}magiclog WHERE uid='$discuz_uid' AND action IN ('4','5')");
		$multipage = multi($db->result($query, 0), $tpp, $page, 'magic.php?action=log&amp;operation=receivelog');

		$query = $db->query("SELECT * FROM {$tablepre}magiclog
			WHERE uid='$discuz_uid' AND action IN ('4','5','6') ORDER BY dateline DESC
			LIMIT $start_limit, $tpp");
		while($log = $db->fetch_array($query)) {
			$log['dateline'] = dgmdate("$dateformat $timeformat", $log['dateline'] + $timeoffset * 3600);
			$log['name'] = $magicarray[$log['magicid']]['name'];
			$loglist[] = $log;
		}

	}

	include template('magic_log');

} elseif($action == 'usemagic') {

	$magiclist = $magicselect = array();
	$typeid = intval($type);
	$magicid = intval($magicid);

	$magiclist = magicselect($discuz_uid, $typeid, array('magic' => array('magicid', 'name', 'description')));

	if($magicid) {
		$magicselect = array($magicid => 'selected="selected"');

		$magic = $db->fetch_first("SELECT m.*, mm.num
				FROM {$tablepre}membermagics mm
				LEFT JOIN {$tablepre}magics m ON mm.magicid=m.magicid
				WHERE mm.uid='$discuz_uid' AND mm.magicid='$magicid'");
		if(!$magic) {
			showmessage('magics_nonexistence');
		} elseif($magic['num'] <= 0) {
			$db->query("DELETE FROM {$tablepre}membermagics WHERE uid='$discuz_uid' AND magicid='$magic[magicid]'");
			showmessage('magics_nonexistence');
		}
		$magicperm = unserialize($magic['magicperm']);

		if(!submitcheck('usesubmit')) {

			include language('magics');
			$username = dhtmlspecialchars($username);
			$useperm = (strstr($magicperm['usergroups'], "\t$groupid\t") || empty($magicperm['usergroups'])) ? '1' : '0';

			if(!$useperm) {
				showmessage('magics_use_nopermission');
			}

			if(in_array($magic['type'], array(1, 2)) && $pid) {
				$pid = intval($pid);
				$perm = $db->fetch_first("SELECT tid, author, anonymous FROM {$tablepre}posts WHERE pid='$pid'");
				if($magic['type'] == 2 && $perm['anonymous']) {
					showmessage('magics_post_anonymous');
				}
				$tid = $perm['tid'];
				$username = $perm['author'];
			}

			if(!@include_once DISCUZ_ROOT.($magicfile = "./include/magic/$magic[filename]")) {
				showmessage('magics_filename_nonexistence');
			}

		}
	}

	include template('magic_use');
} else {
	showmessage('undefined_action', NULL, 'HALTED');
}

?>