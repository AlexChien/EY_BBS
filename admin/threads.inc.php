<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: threads.inc.php 16775 2008-11-19 03:18:09Z monkey $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

require_once DISCUZ_ROOT.'./include/post.func.php';

cpheader();

$page = max(1, intval($page));

if(!$operation) {

	require_once DISCUZ_ROOT.'./include/forum.func.php';

	$forumselect = '<select name="inforum"><option value="all">&nbsp;&nbsp;> '.$lang['all'].'</option>'.
		'<option value="">&nbsp;</option>'.forumselect().'</select>';
	if(isset($inforum)) {
		$forumselect = preg_replace("/(\<option value=\"$inforum\")(\>)/", "\\1 selected=\"selected\" \\2", $forumselect);
	}

	$typeselect = $sortselect = '';
	$query = $db->query("SELECT * FROM {$tablepre}threadtypes ORDER BY displayorder");
	while($type = $db->fetch_array($query)) {
		if($type['special']) {
			$sortselect .= '<option value="'.$type['typeid'].'">&nbsp;&nbsp;> '.$type['name'].'</option>';
		} else {
			$typeselect .= '<option value="'.$type['typeid'].'">&nbsp;&nbsp;> '.$type['name'].'</option>';
		}
	}

	if(isset($insort)) {
		$sortselect = preg_replace("/(\<option value=\"$insort\")(\>)/", "\\1 selected=\"selected\" \\2", $sortselect);
	}

	if(isset($intype)) {
		$typeselect = preg_replace("/(\<option value=\"$intype\")(\>)/", "\\1 selected=\"selected\" \\2", $typeselect);
	}

	echo <<<EOT
<script src="include/js/calendar.js"></script>
<script type="text/JavaScript">
	function page(number) {
		$('threadforum').page.value=number;
		$('threadforum').searchsubmit.click();
	}
</script>
EOT;
	shownav('topic', 'nav_maint_threads');
	showsubmenusteps('nav_maint_threads', array(
		array('threads_search', !$searchsubmit),
		array('nav_maint_threads', $searchsubmit)
	));
	showtips('threads_tips');
	showtagheader('div', 'threadsearch', !submitcheck('searchsubmit'));
	showformheader('threads', '', 'threadforum');
	showhiddenfields(array('page' => $page));
	showtableheader();
	showsetting('threads_search_detail', 'detail', $detail, 'radio');
	showsetting('threads_search_forum', '', '', $forumselect);
	showsetting('threads_search_time', array('starttime', 'endtime'), array($starttime, $endtime), 'daterange');
	showsetting('threads_search_user', 'users', $users, 'text');
	showsetting('threads_search_keyword', 'keywords', $keywords, 'text');

	showtagheader('tbody', 'advanceoption');
	showsetting('threads_search_type', '', '', '<select name="intype"><option value="all">&nbsp;&nbsp;> '.$lang['all'].'</option><option value="">&nbsp;</option><option value="0">&nbsp;&nbsp;> '.$lang['threads_search_type_none'].'</option>'.$typeselect.'</select>');
	showsetting('threads_search_sort', '', '', '<select name="insort"><option value="all">&nbsp;&nbsp;> '.$lang['all'].'</option><option value="">&nbsp;</option><option value="0">&nbsp;&nbsp;> '.$lang['threads_search_type_none'].'</option>'.$sortselect.'</select>');
	showsetting('threads_search_viewrange', array('viewsmore', 'viewsless'), array($viewsmore, $viewsless), 'range');
	showsetting('threads_search_replyrange', array('repliesmore', 'repliesless'), array($repliesmore, $repliesless), 'range');
	showsetting('threads_search_readpermmore', 'readpermmore', $readpermmore, 'text');
	showsetting('threads_search_pricemore', 'pricemore', $pricemore, 'text');
	showsetting('threads_search_noreplyday', 'noreplydays', $noreplydays, 'text');
	showsetting('threads_search_type', array('specialthread', array(
		array(0, lang('unlimited'), array('showspecial' => 'none')),
		array(1, lang('threads_search_include_yes'), array('showspecial' => '')),
		array(2, lang('threads_search_include_no'), array('showspecial' => '')),
	), TRUE), $specialthread, 'mradio');
	showtablerow('id="showspecial" style="display:'.($specialthread ? '' : 'none').'"', 'class="sub" colspan="2"', mcheckbox('special', array(
		1 => lang('thread_poll'),
		2 => lang('thread_trade'),
		3 => lang('thread_reward'),
		4 => lang('thread_activity'),
		5 => lang('thread_debate'),
		6 => lang('thread_video')
	), $special ? $special : array(0)));
	showsetting('threads_search_sticky', array('sticky', array(
		array(0, lang('unlimited')),
		array(1, lang('threads_search_include_yes')),
		array(2, lang('threads_search_include_no')),
	), TRUE), $sticky, 'mradio');
	showsetting('threads_search_digest', array('digest', array(
		array(0, lang('unlimited')),
		array(1, lang('threads_search_include_yes')),
		array(2, lang('threads_search_include_no')),
	), TRUE), $digest, 'mradio');
	showsetting('threads_search_attach', array('attach', array(
		array(0, lang('unlimited')),
		array(1, lang('threads_search_include_yes')),
		array(2, lang('threads_search_include_no')),
	), TRUE), $attach, 'mradio');
	showsetting('threads_rate', array('rate', array(
		array(0, lang('unlimited')),
		array(1, lang('threads_search_include_yes')),
		array(2, lang('threads_search_include_no')),
	), TRUE), $rate, 'mradio');
	showsetting('threads_highlight', array('highlight', array(
		array(0, lang('unlimited')),
		array(1, lang('threads_search_include_yes')),
		array(2, lang('threads_search_include_no')),
	), TRUE), $highlight, 'mradio');
	showtagfooter('tbody');

	showsubmit('searchsubmit', 'submit', '', 'more_options');
	showtablefooter();
	showformfooter();
	showtagfooter('div');

	if(submitcheck('searchsubmit')) {

		$sql = '';
		$sql .= $inforum != '' && $inforum != 'all' ? " AND fid='$inforum'" : '';
		$sql .= $intype != '' && $intype != 'all' ? " AND typeid='$intype'" : '';
		$sql .= $insort != '' && $insort != 'all' ? " AND sortid='$insort'" : '';
		$sql .= $viewsless != '' ? " AND views<'$viewsless'" : '';
		$sql .= $viewsmore != '' ? " AND views>'$viewsmore'" : '';
		$sql .= $repliesless != '' ? " AND replies<'$repliesless'" : '';
		$sql .= $repliesmore != '' ? " AND replies>'$repliesmore'" : '';
		$sql .= $readpermmore != '' ? " AND readperm>'$readpermmore'" : '';
		$sql .= $pricemore != '' ? " AND price>'$pricemore'" : '';
		$sql .= $beforedays != '' ? " AND dateline<'$timestamp'-'$beforedays'*86400" : '';
		$sql .= $noreplydays != '' ? " AND lastpost<'$timestamp'-'$noreplydays'*86400" : '';
		$sql .= $starttime != '' ? " AND dateline>'".strtotime($starttime)."'" : '';
		$sql .= $endtime != '' ? " AND dateline<='".strtotime($endtime)."'" : '';

		if(trim($keywords)) {
			$sqlkeywords = '';
			$or = '';
			$keywords = explode(',', str_replace(' ', '', $keywords));
			for($i = 0; $i < count($keywords); $i++) {
				$sqlkeywords .= " $or subject LIKE '%".$keywords[$i]."%'";
				$or = 'OR';
			}
			$sql .= " AND ($sqlkeywords)";
		}

		$sql .= trim($users) ? " AND author IN ('".str_replace(',', '\',\'', str_replace(' ', '', trim($users)))."')" : '';

		if($sticky == 1) {
			$sql .= " AND displayorder>'0'";
		} elseif($sticky == 2) {
			$sql .= " AND displayorder='0'";
		}
		if($digest == 1) {
			$sql .= " AND digest>'0'";
		} elseif($digest == 2) {
			$sql .= " AND digest='0'";
		}
		if($attach == 1) {
			$sql .= " AND attachment>'0'";
		} elseif($attach == 2) {
			$sql .= " AND attachment='0'";
		}
		if($rate == 1) {
			$sql .= " AND rate>'0'";
		} elseif($rate == 2) {
			$sql .= " AND rate='0'";
		}
		if($highlight == 1) {
			$sql .= " AND highlight>'0'";
		} elseif($highlight == 2) {
			$sql .= " AND highlight='0'";
		}
		if(!empty($special)) {
			$specials = $comma = '';
			foreach($special as $val) {
				$specials .= $comma.'\''.$val.'\'';
				$comma = ',';
			}
			if($specialthread == 1) {
				$sql .=  " AND special IN ($specials)";
			} elseif($specialthread == 2) {
				$sql .=  " AND special NOT IN ($specials)";
			}
		}

		$fids = array();
		$tids = $threadcount = '0';
		if($sql) {
			$sql = "digest>='0' AND displayorder>='0' $sql";
			if($detail) {
				$pagetmp = $page;
				do {
					$query = $db->query("SELECT fid, tid, readperm, price, subject, authorid, author, views, replies, lastpost FROM {$tablepre}threads WHERE $sql LIMIT ".(($pagetmp - 1) * $tpp).",$tpp");
					$pagetmp--;
				} while(!$db->num_rows($query) && $pagetmp);
				$threads = '';
				while($thread = $db->fetch_array($query)) {
					$fids[] = $thread['fid'];
					$thread['lastpost'] = gmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
					$threads .= showtablerow('', array('class="td25"', '', '', '', 'class="td25"', 'class="td25"'), array(
						"<input class=\"checkbox\" type=\"checkbox\" name=\"tidarray[]\" value=\"$thread[tid]\" checked />",
						"<a href=\"viewthread.php?tid=$thread[tid]\" target=\"_blank\">$thread[subject]</a>".($thread['readperm'] ? " - [$lang[threads_readperm] $thread[readperm]]" : '').($thread['price'] ? " - [$lang[threads_price] $thread[price]]" : ''),
						"<a href=\"forumdisplay.php?fid=$thread[fid]\" target=\"_blank\">{$_DCACHE[forums][$thread[fid]][name]}</a>",
						"<a href=\"space.php?uid=$thread[authorid]\" target=\"_blank\">$thread[author]</a>",
						$thread['replies'],
						$thread['views'],
						$thread['lastpost']
					), TRUE);
				}
				$threadcount = $db->result_first("SELECT count(*) FROM {$tablepre}threads WHERE $sql");
				$multi = multi($threadcount, $tpp, $page, "$BASESCRIPT?action=threads");
				$multi = preg_replace("/href=\"$BASESCRIPT\?action=threads&amp;page=(\d+)\"/", "href=\"javascript:page(\\1)\"", $multi);
				$multi = str_replace("window.location=$BASESCRIPT.'?action=threads&amp;page='+this.value", "page(this.value)", $multi);
			} else {
				$query = $db->query("SELECT fid, tid FROM {$tablepre}threads WHERE $sql");
				while($thread = $db->fetch_array($query)) {
					$fids[] = $thread['fid'];
					$tids .= ','.$thread['tid'];
				}
				$threadcount = $db->result_first("SELECT count(*) FROM {$tablepre}threads WHERE $sql");
				$multi = '';
			}
		}
		$fids = implode(',', array_unique($fids));

		showtagheader('div', 'threadlist', TRUE);
		showformheader('threads&frame=no', 'target="threadframe"');
		showhiddenfields($detail ? array('fids' => $fids) : array('fids' => $fids, 'tids' => $tids));
		showtableheader(lang('threads_result').' '.$threadcount.' <a href="###" onclick="$(\'threadlist\').style.display=\'none\';$(\'threadsearch\').style.display=\'\';" class="act lightlink normal">'.lang('research').'</a>', 'nobottom');
		showsubtitle(array('', 'operation', 'option'));

		if(!$threadcount) {

			showtablerow('', 'colspan="3"', lang('threads_thread_nonexistence'));

		} else {

			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="moveforum" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_move_forum'],
				'<select name="toforum">'.forumselect().'</select>'
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="movetype" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_move_type'],
				'<select name="totype"><option value="0">&nbsp;&nbsp;> '.$lang['threads_search_type_none'].'</option>'.$typeselect.'</select>'
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="movesort" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_move_sort'],
				'<select name="tosort"><option value="0">&nbsp;&nbsp;> '.$lang['threads_search_type_none'].'</option>'.$sortselect.'</select>'
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="delete" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_delete'],
				'<input class="checkbox" type="checkbox" name="donotupdatemember" id="donotupdatemember" value="1" checked="checked" /><label for="donotupdatemember"> '.$lang['threads_delete_no_update_member'].'</label>'
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="stick" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_stick'],
				'<input class="radio" type="radio" name="stick_level" value="0" checked> '.$lang['threads_remove'].' &nbsp; &nbsp;<input class="radio" type="radio" name="stick_level" value="1"> '.$lang['threads_stick_one'].' &nbsp; &nbsp;<input class="radio" type="radio" name="stick_level" value="2"> '.$lang['threads_stick_two'].' &nbsp; &nbsp;<input class="radio" type="radio" name="stick_level" value="3"> '.$lang['threads_stick_three']
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="adddigest" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_add_digest'],
				'<input class="radio" type="radio" name="digest_level" value="0" checked> '.$lang['threads_remove'].' &nbsp; &nbsp;<input class="radio" type="radio" name="digest_level" value="1"> '.$lang['threads_digest_one'].' &nbsp; &nbsp;<input class="radio" type="radio" name="digest_level" value="2"> '.$lang['threads_digest_two'].' &nbsp; &nbsp;<input class="radio" type="radio" name="digest_level" value="3"> '.$lang['threads_digest_three']
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="addstatus" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_open_close'],
				'<input class="radio" type="radio" name="status" value="0" checked> '.$lang['open'].' &nbsp; &nbsp;<input class="radio" type="radio" name="status" value="1"> '.$lang['closed']
			));
			showtablerow('', array('class="td25"', 'class="td24"', 'class="rowform" style="width:auto;"'), array(
				'<input class="radio" type="radio" name="operation" value="deleteattach" onclick="this.form.modsubmit.disabled=false;">',
				$lang['threads_delete_attach'],
				''
			));

			if($detail) {

				showtablefooter();
				showtableheader('threads_list', 'notop');
				showsubtitle(array('', 'subject', 'forum', 'author', 'threads_replies', 'threads_views', 'threads_lastpost'));
				echo $threads;

			}

		}

		showsubmit('modsubmit', 'submit', '<input name="chkall" id="chkall" type="checkbox" class="checkbox" checked="checked" onclick="checkAll(\'prefix\', this.form, \'tidarray\', \'chkall\')" /><label for="chkall">'.lang('select_all').'</label>', '', $multi);
		showtablefooter();
		showformfooter();
		echo '<iframe name="threadframe" style="display:none"></iframe>';
		showtagfooter('div');

	}

} else {

	$tidsadd = isset($tids) ? 'tid IN ('.$tids.')' : 'tid IN ('.implodeids($tidarray).')';

	if($operation == 'moveforum') {

		if(!$db->result_first("SELECT fid FROM {$tablepre}forums WHERE type<>'group' AND fid='$toforum'")) {
			cpmsg('threads_move_invalid', '', 'error');
		}

		$db->query("UPDATE {$tablepre}threads SET fid='$toforum' WHERE $tidsadd");
		$db->query("UPDATE {$tablepre}posts SET fid='$toforum' WHERE $tidsadd");

		foreach(explode(',', $fids.','.$toforum) as $fid) {
			updateforumcount(intval($fid));
		}

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'movetype') {

		if($totype != 0) {
			if(!$db->result_first("SELECT typeid FROM {$tablepre}threadtypes WHERE typeid='$totype' AND special='0'")) {
				cpmsg('threads_move_invalid', '', 'error');
			}
		}

		$db->query("UPDATE {$tablepre}threads SET typeid='$totype' WHERE $tidsadd");

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'movesort') {

		if($tosort != 0) {
			if(!$db->result_first("SELECT typeid FROM {$tablepre}threadtypes WHERE typeid='$tosort' AND special='1'")) {
				cpmsg('threads_move_invalid', '', 'error');
			}
		}

		$db->query("UPDATE {$tablepre}threads SET sortid='$tosort' WHERE $tidsadd");

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'delete') {

		$query = $db->query("SELECT attachment, thumb, remote FROM {$tablepre}attachments WHERE $tidsadd");
		while($attach = $db->fetch_array($query)) {
			dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
		}

		if(!$donotupdatemember) {
			$tuidarray = $ruidarray = array();
			$query = $db->query("SELECT first, authorid FROM {$tablepre}posts WHERE $tidsadd");
			while($post = $db->fetch_array($query)) {
				if($post['first']) {
					$tuidarray[] = $post['authorid'];
				} else {
					$ruidarray[] = $post['authorid'];
				}
			}
			if($tuidarray) {
				updatepostcredits('-', $tuidarray, $creditspolicy['post']);
			}
			if($ruidarray) {
				updatepostcredits('-', $ruidarray, $creditspolicy['reply']);
			}
		}

		$db->query("DELETE FROM {$tablepre}attachments WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}posts WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}threads WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}polloptions WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}polls WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}rewardlog WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}trades WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}rewardlog WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}activities WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}activityapplies WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}debates WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}debateposts WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}threadsmod WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}relatedthreads WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}typeoptionvars WHERE $tidsadd", 'UNBUFFERED');
		$db->query("DELETE FROM {$tablepre}videos WHERE $tidsadd", 'UNBUFFERED');

		if($globalstick) {
			updatecache('globalstick');
		}

		foreach(explode(',', $fids) as $fid) {
			updateforumcount(intval($fid));
		}

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'deleteattach') {

		$query = $db->query("SELECT attachment, thumb, remote FROM {$tablepre}attachments WHERE $tidsadd");
		while($attach = $db->fetch_array($query)) {
			dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
		}
		$db->query("DELETE FROM {$tablepre}attachments WHERE $tidsadd");
		$db->query("UPDATE {$tablepre}threads SET attachment='0' WHERE $tidsadd");
		$db->query("UPDATE {$tablepre}posts SET attachment='0' WHERE $tidsadd");

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'stick') {

		$db->query("UPDATE {$tablepre}threads SET displayorder='$stick_level' WHERE $tidsadd");
		if($globalstick) {
			updatecache('globalstick');
		}

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'adddigest') {

		$query = $db->query("SELECT tid, authorid, digest FROM {$tablepre}threads WHERE $tidsadd");
		while($thread = $db->fetch_array($query)) {
			updatecredits($thread['authorid'], $creditspolicy['digest'], $digest_level - $thread['digest'], 'digestposts=digestposts-1');
		}
		$db->query("UPDATE {$tablepre}threads SET digest='$digest_level' WHERE $tidsadd");

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	} elseif($operation == 'addstatus') {

		$db->query("UPDATE {$tablepre}threads SET closed='$status' WHERE $tidsadd");

		eval("\$cpmsg = \"".lang('threads_succeed')."\";");

	}

	$tids && deletethreadcaches($tids);
	$cpmsg = $cpmsg ? "alert('$cpmsg');" : '';

	echo '<script type="text/JavaScript">'.$cpmsg.'parent.$(\'threadforum\').searchsubmit.click();</script>';

}

?>