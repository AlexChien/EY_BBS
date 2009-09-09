<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: forum.func.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function checkautoclose() {
	global $timestamp, $forum, $thread;

	if(!$forum['ismoderator'] && $forum['autoclose']) {
		$closedby = $forum['autoclose'] > 0 ? 'dateline' : 'lastpost';
		$forum['autoclose'] = abs($forum['autoclose']);
		if($timestamp - $thread[$closedby] > $forum['autoclose'] * 86400) {
			return 'post_thread_closed_by_'.$closedby;
		}
	}
	return FALSE;
}

function forum(&$forum) {
	global $_DCOOKIE, $timestamp, $timeformat, $dateformat, $discuz_uid, $groupid, $lastvisit, $moddisplay, $timeoffset, $hideprivate, $onlinehold;

	if(!$forum['viewperm'] || ($forum['viewperm'] && forumperm($forum['viewperm'])) || !empty($forum['allowview']) || (isset($forum['users']) && strstr($forum['users'], "\t$discuz_uid\t"))) {
		$forum['permission'] = 2;
	} elseif(!$hideprivate) {
		$forum['permission'] = 1;
	} else {
		return FALSE;
	}

	if($forum['icon']) {
		if(strstr($forum['icon'], ',')) {
			$flash = explode(",", $forum['icon']);
			$forum['icon'] = "<a href=\"forumdisplay.php?fid=$forum[fid]\"><embed style=\"float:left; margin-right: 10px\" src=\"".trim($flash[0])."\" width=\"".trim($flash[1])."\" height=\"".trim($flash[2])."\" type=\"application/x-shockwave-flash\" align=\"left\"></embed></a>";
		} else {
			$forum['icon'] = "<a href=\"forumdisplay.php?fid=$forum[fid]\"><img style=\"float:left; margin-right: 10px\" src=\"$forum[icon]\" align=\"left\" alt=\"\" border=\"0\" /></a>";
		}
	}

	$lastpost = array('tid' => 0, 'dateline' => 0, 'subject' => '', 'author' => '');
	list($lastpost['tid'], $lastpost['subject'], $lastpost['dateline'], $lastpost['author']) = is_array($forum['lastpost']) ? $forum['lastpost'] : explode("\t", $forum['lastpost']);
	$forum['folder'] = (isset($_DCOOKIE['fid'.$forum['fid']]) && $_DCOOKIE['fid'.$forum['fid']] > $lastvisit ? $_DCOOKIE['fid'.$forum['fid']] : $lastvisit) < $lastpost['dateline'] ? ' class="new"' : '';

	if($lastpost['tid']) {
		$lastpost['dateline'] = dgmdate("$dateformat $timeformat", $lastpost['dateline'] + $timeoffset * 3600);
		$lastpost['authorusername'] = $lastpost['author'];
		if($lastpost['author']) {
			$lastpost['author'] = '<a href="space.php?username='.rawurlencode($lastpost['author']).'">'.$lastpost['author'].'</a>';
		}
		$forum['lastpost'] = $lastpost;
	} else {
		$forum['lastpost'] = $lastpost['authorusername'] = '';
	}

	$forum['moderators'] = moddisplay($forum['moderators'], $moddisplay, !empty($forum['inheritedmod']));

	if(isset($forum['subforums'])) {
		$forum['subforums'] = implode(', ', $forum['subforums']);
	}

	return TRUE;
}

function forumselect($groupselectable = FALSE, $tableformat = 0, $selectedfid = 0) {
	global $_DCACHE, $discuz_uid, $groupid, $fid, $gid, $indexname;

	if(!isset($_DCACHE['forums'])) {
		require_once DISCUZ_ROOT.'./forumdata/cache/cache_forums.php';
	}

	$forumlist = $tableformat ? '<dl><dd><ul>' : '<optgroup label="&nbsp;">';
	foreach($_DCACHE['forums'] as $forum) {
		if($forum['type'] == 'group') {
			if($tableformat) {
				$forumlist .= '</ul></dd></dl><dl><dt><a href="'.$indexname.'?gid='.$forum['fid'].'">'.$forum['name'].'</a></dt><dd><ul>';
			} else {
				$forumlist .= $groupselectable ? '<option value="'.$forum['fid'].'">'.$forum['name'].'</option>' : '</optgroup><optgroup label="--'.$forum['name'].'">';
			}
			$visible[$forum['fid']] = true;
		} elseif($forum['type'] == 'forum' && isset($visible[$forum['fup']]) && (!$forum['viewperm'] || ($forum['viewperm'] && forumperm($forum['viewperm'])) || strstr($forum['users'], "\t$discuz_uid\t"))) {
			if($tableformat) {
				$forumlist .= '<li'.($fid == $forum['fid'] ? ' class="current"' : '').'><a href="forumdisplay.php?fid='.$forum['fid'].'">'.$forum['name'].'</a></li>';
			} else {
				$forumlist .= '<option value="'.$forum['fid'].'"'.($selectedfid && $selectedfid == $forum['fid'] ? ' selected' : '').'>'.$forum['name'].'</option>';
			}
			$visible[$forum['fid']] = true;
		} elseif($forum['type'] == 'sub' && isset($visible[$forum['fup']]) && (!$forum['viewperm'] || ($forum['viewperm'] && forumperm($forum['viewperm'])) || strstr($forum['users'], "\t$discuz_uid\t"))) {
			if($tableformat) {
				$forumlist .=  '<li class="sub'.($fid == $forum['fid'] ? ' current' : '').'"><a href="forumdisplay.php?fid='.$forum['fid'].'">'.$forum['name'].'</a></li>';
			} else {
				$forumlist .= '<option value="'.$forum['fid'].'"'.($selectedfid && $selectedfid == $forum['fid'] ? ' selected' : '').'>&nbsp; &nbsp; &nbsp; '.$forum['name'].'</option>';
			}
		}
	}
	$forumlist .= $tableformat ? '</ul></dd></dl>' : '</optgroup>';
	$forumlist = str_replace($tableformat ? '<dl><dd><ul></ul></dd></dl>' : '<optgroup label="&nbsp;"></optgroup>', '', $forumlist);

	return $forumlist;
}

function visitedforums() {
	global $_DCACHE, $_DCOOKIE, $forum, $sid;

	$count = 0;
	$visitedforums = '';
	$fidarray = array($forum['fid']);
	foreach(explode('D', $_DCOOKIE['visitedfid']) as $fid) {
		if(isset($_DCACHE['forums'][$fid]) && !in_array($fid, $fidarray)) {
			$fidarray[] = $fid;
			if($fid != $forum['fid']) {
				$visitedforums .= '<li><a href="forumdisplay.php?fid='.$fid.'&amp;sid='.$sid.'">'.$_DCACHE['forums'][$fid]['name'].'</a></li>';
				if(++$count >= $GLOBALS['visitedforums']) {
					break;
				}

			}
		}
	}
	if(($visitedfid = implode('D', $fidarray)) != $_DCOOKIE['visitedfid']) {
		dsetcookie('visitedfid', $visitedfid, 2592000);
	}
	return $visitedforums;
}

function moddisplay($moderators, $type, $inherit = 0) {
	if($type == 'selectbox') {
		if($moderators) {
			$modlist = '';
			foreach(explode("\t", $moderators) as $moderator) {
				$modlist .= '<li><a href="space.php?username='.rawurlencode($moderator).'">'.($inherit ? '<strong>'.$moderator.'</strong>' : $moderator).'</a></li>';
			}
		} else {
			$modlist = '';
		}

		return $modlist;
	} else {
		if($moderators) {
			$modlist = $comma = '';
			foreach(explode("\t", $moderators) as $moderator) {
				$modlist .= $comma.'<a class="notabs" href="space.php?username='.rawurlencode($moderator).'">'.($inherit ? '<strong>'.$moderator.'</strong>' : $moderator).'</a>';
				$comma = ', ';
			}
		} else {
			$modlist = '';
		}
		return $modlist;
	}
}

function getcacheinfo($tid) {
	global $timestamp, $cachethreadlife, $cachethreaddir;
	$tid = intval($tid);
	$cachethreaddir2 = DISCUZ_ROOT.'./'.$cachethreaddir;
	$cache = array('filemtime' => 0, 'filename' => '');
	$tidmd5 = substr(md5($tid), 3);
	$fulldir = $cachethreaddir2.'/'.$tidmd5[0].'/'.$tidmd5[1].'/'.$tidmd5[2].'/';
	$cache['filename'] = $fulldir.$tid.'.htm';
	if(file_exists($cache['filename'])) {
		$cache['filemtime'] = filemtime($cache['filename']);
	} else {
		if(!is_dir($fulldir)) {
			for($i=0; $i<3; $i++) {
				$cachethreaddir2 .= '/'.$tidmd5{$i};
				if(!is_dir($cachethreaddir2)) {
					@mkdir($cachethreaddir2, 0777);
					@touch($cachethreaddir2.'/index.htm');
				}
			}
		}
	}
	return $cache;
}

function recommendupdate($fid, $modrecommend, $force = '') {
	global $db, $tablepre, $timestamp;

	$recommendlist = array();
	$num = $modrecommend['num'] ? intval($modrecommend['num']) : 10;
	$query = $db->query("SELECT * FROM {$tablepre}forumrecommend WHERE fid='$fid' ORDER BY displayorder LIMIT 0, $num");
	while($recommend = $db->fetch_array($query)) {
		if(($recommend['expiration'] && $recommend['expiration'] > $timestamp) || !$recommend['expiration']) {
			$recommendlist[] = $recommend;
		}
	}

	if($modrecommend['sort'] && (!$recommendlist || $timestamp - $modrecommend['updatetime'] > $modrecommend['cachelife'] || $force)) {
		if($recommendlist) {
			$db->query("DELETE FROM {$tablepre}forumrecommend WHERE fid='$fid'");
		}
		$orderby = 'dateline';
		$conditions = $modrecommend['dateline'] ? 'AND dateline>'.($timestamp - $modrecommend['dateline'] * 3600) : '';
		switch($modrecommend['orderby']) {
			case '1':	$orderby = 'lastpost';	break;
			case '2':	$orderby = 'views';	break;
			case '3':	$orderby = 'replies';	break;
			case '4':	$orderby = 'digest';	break;
		}

		$addthread = $comma = $i = '';
		$recommendlist = array();
		$query = $db->query("SELECT fid, tid, author, authorid, subject FROM {$tablepre}threads WHERE fid='$fid' AND displayorder>='0' $conditions ORDER BY $orderby DESC LIMIT 0, $num");
		while($thread = $db->fetch_array($query)) {
			$recommendlist[] = $thread;
			$addthread .= $comma."('$thread[fid]', '$thread[tid]', '$i', '".addslashes($thread['subject'])."', '".addslashes($thread['author'])."', '$thread[authorid]', '0', '0')";
			$comma = ', ';
			$i++;
		}

		if($addthread) {
			$db->query("REPLACE INTO {$tablepre}forumrecommend (fid, tid, displayorder, subject, author, authorid, moderatorid, expiration) VALUES $addthread");
			$modrecommend['updatetime'] = $timestamp;
			$modrecommendnew = addslashes(serialize($modrecommend));
			$db->query("UPDATE {$tablepre}forumfields SET modrecommend='$modrecommendnew' WHERE fid='$fid'");
		}
	}

	$recommendlists = array();
	if($recommendlist) {
		foreach($recommendlist as $thread) {
			$recommendlists[$thread['tid']]['author'] = $thread['author'];
			$recommendlists[$thread['tid']]['authorid'] = $thread['authorid'];
			$recommendlists[$thread['tid']]['subject'] = $modrecommend['maxlength'] ? cutstr($thread['subject'], $modrecommend['maxlength']) : $thread['subject'];
		}
	}

	return $recommendlists;
}

function showstars($num) {
	global $starthreshold;
	$alt = 'alt="Rank: '.$num.'"';
	if(empty($starthreshold)) {
		for($i = 0; $i < $num; $i++) {
			echo '<img src="'.IMGDIR.'/star_level1.gif" '.$alt.' />';
		}
	} else {
		for($i = 3; $i > 0; $i--) {
			$numlevel = intval($num / pow($starthreshold, ($i - 1)));
			$num = ($num % pow($starthreshold, ($i - 1)));
			for($j = 0; $j < $numlevel; $j++) {
				echo '<img src="'.IMGDIR.'/star_level'.$i.'.gif" '.$alt.' />';
			}
		}
	}
}

?>