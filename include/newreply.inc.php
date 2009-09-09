<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: newreply.inc.php 17198 2008-12-09 09:27:24Z zhaoxiongfei $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$discuz_action = 12;

if($special == 5) {
	$debate = array_merge($thread, $db->fetch_first("SELECT * FROM {$tablepre}debates WHERE tid='$tid'"));
	$firststand = $db->result_first("SELECT stand FROM {$tablepre}debateposts WHERE tid='$tid' AND uid='$discuz_uid' AND stand<>'0' ORDER BY dateline LIMIT 1");

	if($debate['endtime'] && $debate['endtime'] < $timestamp) {
		showmessage('debate_end');
	}
}

if(!$discuz_uid && !((!$forum['replyperm'] && $allowreply) || ($forum['replyperm'] && forumperm($forum['replyperm'])))) {
	showmessage('group_nopermission', NULL, 'NOPERM');
} elseif(empty($forum['allowreply'])) {
	if(!$forum['replyperm'] && !$allowreply) {
		showmessage('group_nopermission', NULL, 'NOPERM');
	} elseif($forum['replyperm'] && !forumperm($forum['replyperm'])) {
		showmessage('post_forum_newreply_nopermission', NULL, 'HALTED');
	}
} elseif($forum['allowreply'] == -1) {
	showmessage('post_forum_newreply_nopermission', NULL, 'HALTED');
}

if(empty($thread)) {
	showmessage('thread_nonexistence');
} elseif($thread['price'] > 0 && $thread['special'] == 0 && !$discuz_uid) {
	showmessage('group_nopermission', NULL, 'NOPERM');
}

checklowerlimit($replycredits);

if(!submitcheck('replysubmit', 0, $seccodecheck, $secqaacheck)) {

	if($thread['special'] == 2 && ((!isset($addtrade) || $thread['authorid'] != $discuz_uid) && !$tradenum = $db->result_first("SELECT count(*) FROM {$tablepre}trades WHERE tid='$tid'"))) {
		showmessage('trade_newreply_nopermission', NULL, 'HALTED');
	}

	include_once language('misc');
	if(isset($repquote)) {

		$thaquote = $db->fetch_first("SELECT tid, fid, author, authorid, first, message, useip, dateline, anonymous, status FROM {$tablepre}posts WHERE pid='$repquote' AND invisible='0'");
		if($thaquote['tid'] != $tid) {
			showmessage('undefined_action', NULL, 'HALTED');
		}

		if(!($thread['price'] && !$thread['special'] && $thaquote['first'])) {
			$quotefid = $thaquote['fid'];
			$message = $thaquote['message'];

			if($bannedmessages && $thaquote['authorid']) {
				$author = $db->fetch_first("SELECT groupid FROM {$tablepre}members WHERE uid='$thaquote[authorid]'");
				if(!$author['groupid'] || $author['groupid'] == 4 || $author['groupid'] == 5) {
					$message = $language['post_banned'];
				} elseif($thaquote['status'] & 1) {
					$message = $language['post_single_banned'];
				}
			}

			$time = gmdate("$dateformat $timeformat", $thaquote['dateline'] + ($timeoffset * 3600));
			$bbcodes = 'b|i|u|color|size|font|align|list|indent|url|email|code|free|table|tr|td|img|swf|attach|payto|float'.($_DCACHE['bbcodes_display'] ? '|'.implode('|', array_keys($_DCACHE['bbcodes_display'])) : '');
			$message = cutstr(strip_tags(preg_replace(array(
					"/\[hide=?\d*\](.+?)\[\/hide\]/is",
					"/\[quote](.*)\[\/quote]/siU",
					$language['post_edit_regexp'],
					"/\[($bbcodes)=?.*\]/iU",
					"/\[\/($bbcodes)\]/i",
				), array(
					"[b]$language[post_hidden][/b]",
					'',
					'',
					'',
					''
				), $message)), 200);

			$thaquote['useip'] = substr($thaquote['useip'], 0, strrpos($thaquote['useip'], '.')).'.x';
			if($thaquote['author'] && $thaquote['anonymous']) {
			    $thaquote['author'] = 'Anonymous';
			} elseif(!$thaquote['author']) {
			    $thaquote['author'] = 'Guest from '.$thaquote['useip'];
			} else {
			    $thaquote['author'] = $thaquote['author'];
			}

			eval("\$language['post_reply_quote'] = \"$language[post_reply_quote]\";");
			$message = "[quote]$message\n[size=2][color=#999999]$language[post_reply_quote][/color] [url={$boardurl}redirect.php?goto=findpost&pid=$repquote&ptid=$tid][img]{$boardurl}images/common/back.gif[/img][/url][/size][/quote]\n";
		}

	} elseif(isset($reppost)) {

		$thapost = $db->fetch_first("SELECT tid, author, authorid, useip, dateline, anonymous, status FROM {$tablepre}posts WHERE pid='$reppost' AND invisible='0'");
		if($thapost['tid'] != $tid) {
			showmessage('undefined_action', NULL, 'HALTED');
		}

		$thapost['useip'] = substr($thapost['useip'], 0, strrpos($thapost['useip'], '.')).'.x';
		if($thapost['author'] && $thapost['anonymous']) {
		    $thapost['author'] = '[i]Anonymous[/i]';
		} elseif(!$thapost['author']) {
		    $thapost['author'] = '[i]Guest[/i] from '.$thapost['useip'];
		} else {
		    $thapost['author'] = '[i]'.$thapost['author'].'[/i]';
		}
		$thapost['number'] = $db->result_first("SELECT count(*) FROM {$tablepre}posts WHERE tid='$thapost[tid]' AND dateline<='$thapost[dateline]'");
		$message = "[b]$lang[post_reply] [url={$boardurl}redirect.php?goto=findpost&pid=$reppost&ptid=$thapost[tid]]$thapost[number]#[/url] $thapost[author] $lang[post_thread][/b]\n\n\n";

	}

	if(isset($addtrade) && $thread['special'] == 2 && $allowposttrade && $thread['authorid'] == $discuz_uid) {
		$expiration_7days = date('Y-m-d', $timestamp + 86400 * 7);
		$expiration_14days = date('Y-m-d', $timestamp + 86400 * 14);
		$trade['expiration'] = $expiration_month = date('Y-m-d', mktime(0, 0, 0, date('m')+1, date('d'), date('Y')));
		$expiration_3months = date('Y-m-d', mktime(0, 0, 0, date('m')+3, date('d'), date('Y')));
		$expiration_halfyear = date('Y-m-d', mktime(0, 0, 0, date('m')+6, date('d'), date('Y')));
		$expiration_year = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+1));
	}

	if($thread['replies'] <= $ppp) {
		$postlist = array();
		$query = $db->query("SELECT p.* ".($bannedmessages ? ', m.groupid ' : '').
			"FROM {$tablepre}posts p ".($bannedmessages ? "LEFT JOIN {$tablepre}members m ON p.authorid=m.uid " : '').
			"WHERE p.tid='$tid' AND p.invisible='0' ".($thread['price'] > 0 && $thread['special'] == 0 ? 'AND p.first = 0' : '')." ORDER BY p.dateline DESC");
		while($post = $db->fetch_array($query)) {

			$post['dateline'] = dgmdate("$dateformat $timeformat", $post['dateline'] + $timeoffset * 3600);

			if($bannedmessages && ($post['authorid'] && (!$post['groupid'] || $post['groupid'] == 4 || $post['groupid'] == 5))) {
				$post['message'] = $language['post_banned'];
			} elseif($post['status'] & 1) {
				$post['message'] = $language['post_single_banned'];
			} else {
				$post['message'] = preg_replace("/\[hide=?\d*\](.+?)\[\/hide\]/is", "[b]$language[post_hidden][/b]", $post['message']);
				$post['message'] = discuzcode($post['message'], $post['smileyoff'], $post['bbcodeoff'], $post['htmlon'] & 1, $forum['allowsmilies'], $forum['allowbbcode'], $forum['allowimgcode'], $forum['allowhtml'], $forum['jammer']);
			}

			$postlist[] = $post;
		}
	}

	if($special == 2 && isset($addtrade) && $thread['authorid'] == $discuz_uid) {
		$tradetypeselect = '';
		$forum['tradetypes'] = $forum['tradetypes'] == '' ? -1 : unserialize($forum['tradetypes']);
		if($tradetypes && !empty($forum['tradetypes'])) {
			$tradetypeselect = '<select name="tradetypeid" onchange="ajaxget(\'post.php?action=threadsorts&tradetype=yes&sortid=\'+this.options[this.selectedIndex].value+\'&sid='.$sid.'\', \'threadtypes\', \'threadtypeswait\')"><option value="0">&nbsp;</option>';
			foreach($tradetypes as $typeid => $name) {
				if($forum['tradetypes'] == -1 || @in_array($typeid, $forum['tradetypes'])) {
					$tradetypeselect .= '<option value="'.$typeid.'">'.strip_tags($name).'</option>';
				}
			}
			$tradetypeselect .= '</select><span id="threadtypeswait"></span>';
		}
	}

	include template('post');

} else {

	require_once DISCUZ_ROOT.'./include/forum.func.php';

	if($subject == '' && $message == '' && $thread['special'] != 2) {
		showmessage('post_sm_isnull');
	} elseif($thread['closed'] && !$forum['ismoderator']) {
		showmessage('post_thread_closed');
	} elseif($post_autoclose = checkautoclose()) {
		showmessage($post_autoclose);
	} elseif($post_invalid = checkpost($special == 2 && $allowposttrade)) {
		showmessage($post_invalid);
	} elseif(checkflood()) {
		showmessage('post_flood_ctrl');
	}

	if(!empty($trade) && $thread['special'] == 2 && $allowposttrade) {

		$item_price = floatval($item_price);
		if(!trim($item_name)) {
			showmessage('trade_please_name');
		} elseif($maxtradeprice && ($mintradeprice > $item_price || $maxtradeprice < $item_price)) {
			showmessage('trade_price_between');
		} elseif(!$maxtradeprice && $mintradeprice > $item_price) {
			showmessage('trade_price_more_than');
		} elseif($item_number < 1) {
			showmessage('tread_please_number');
		}

		threadsort_checkoption(1, 1);

		$optiondata = array();
		if($tradetypes && $typeoption && $checkoption) {
			$optiondata = threadsort_validator($typeoption);
		}

		if(!empty($_FILES['tradeattach']['tmp_name'][0])) {
			$_FILES['attach'] = array_merge_recursive((array)$_FILES['attach'], $_FILES['tradeattach']);
		}

	}

	$attachnum = 0;
	if($allowpostattach && !empty($_FILES['attach']) && is_array($_FILES['attach'])) {
		foreach($_FILES['attach']['name'] as $attachname) {
			if($attachname != '') {
				$attachnum ++;
			}
		}
		$attachnum && checklowerlimit($postattachcredits, $attachnum);
	} else {
		$_FILES = array();
	}

	$attachments = $attachnum ? attach_upload() : array();
	$attachment = empty($attachments) ? 0 : ($imageexists ? 2 : 1);

	$subscribed = $thread['subscribed'] && $timestamp - $thread['lastpost'] < 7776000;
	$newsubscribed = !empty($emailnotify) && $discuz_uid;

	if($subscribed && !$modnewreplies) {
		$db->query("UPDATE {$tablepre}subscriptions SET lastpost='$timestamp' WHERE tid='$tid' AND uid<>'$discuz_uid'", 'UNBUFFERED');
	}

	if($newsubscribed) {
		$db->query("REPLACE INTO {$tablepre}subscriptions (uid, tid, lastpost, lastnotify)
			VALUES ('$discuz_uid', '$tid', '".($modnewreplies ? $thread['lastpost'] : $timestamp)."', '$timestamp')", 'UNBUFFERED');
	}

	$bbcodeoff = checkbbcodes($message, !empty($bbcodeoff));
	$smileyoff = checksmilies($message, !empty($smileyoff));
	$parseurloff = !empty($parseurloff);
	$htmlon = $allowhtml && !empty($htmlon) ? 1 : 0;
	$usesig = !empty($usesig) ? 1 : 0;

	$isanonymous = $allowanonymous && !empty($isanonymous)? 1 : 0;
	$author = empty($isanonymous) ? $discuz_user : '';

	$pinvisible = $modnewreplies ? -2 : 0;
	$message = preg_replace('/\[attachimg\](\d+)\[\/attachimg\]/is', '[attach]\1[/attach]', $message);
	$db->query("INSERT INTO {$tablepre}posts (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment)
			VALUES ('$fid', '$tid', '0', '$discuz_user', '$discuz_uid', '$subject', '$timestamp', '$message', '$onlineip', '$pinvisible', '$isanonymous', '$usesig', '$htmlon', '$bbcodeoff', '$smileyoff', '$parseurloff', '$attachment')");
	$pid = $db->insert_id();
	$db->query("REPLACE INTO {$tablepre}myposts (uid, tid, pid, position, dateline, special) VALUES ('$discuz_uid', '$tid', '$pid', '".($thread['replies'] + 1)."', '$timestamp', '$special')", 'UNBUFFERED');

	if($special == 3 && $thread['authorid'] != $discuz_uid && $thread['price'] > 0) {

		$rewardlog = $db->fetch_first("SELECT * FROM {$tablepre}rewardlog WHERE tid='$tid' AND answererid='$discuz_uid'");
		if(!$rewardlog) {
			$db->query("INSERT INTO {$tablepre}rewardlog (tid, answererid, dateline) VALUES ('$tid', '$discuz_uid', '$timestamp')");
		}

	} elseif($special == 5) {

		$stand = $firststand ? $firststand : intval($stand);

		if(!$db->num_rows($standquery)) {
			if($stand == 1) {
				$db->query("UPDATE {$tablepre}debates SET affirmdebaters=affirmdebaters+1 WHERE tid='$tid'");
			} elseif($stand == 2) {
				$db->query("UPDATE {$tablepre}debates SET negadebaters=negadebaters+1 WHERE tid='$tid'");
			}
		} else {
			$stand = $firststand;
		}
		if($stand == 1) {
			$db->query("UPDATE {$tablepre}debates SET affirmreplies=affirmreplies+1 WHERE tid='$tid'");
		} elseif($stand == 2) {
			$db->query("UPDATE {$tablepre}debates SET negareplies=negareplies+1 WHERE tid='$tid'");
		}
		$db->query("INSERT INTO {$tablepre}debateposts (tid, pid, uid, dateline, stand, voters, voterids) VALUES ('$tid', '$pid', '$discuz_uid', '$timestamp', '$stand', '0', '')");
	}

	$tradeaid = 0;
	if($attachment) {
		$searcharray = $pregarray = $replacearray = array();
		foreach($attachments as $key => $attach) {
			$db->query("INSERT INTO {$tablepre}attachments (tid, pid, dateline, readperm, price, filename, description, filetype, filesize, attachment, downloads, isimage, uid, thumb, remote, width)
				VALUES ('$tid', '$pid', '$timestamp', '$attach[perm]', '$attach[price]', '$attach[name]', '$attach[description]', '$attach[type]', '$attach[size]', '$attach[attachment]', '0', '$attach[isimage]', '$attach[uid]', '$attach[thumb]', '$attach[remote]', '$attach[width]')");
			$searcharray[] = '[local]'.$localid[$key].'[/local]';
			$pregarray[] = '/\[localimg=(\d{1,3}),(\d{1,3})\]'.$localid[$key].'\[\/localimg\]/is';
			$insertid = $db->insert_id();
			$replacearray[] = '[attach]'.$insertid.'[/attach]';
		}
		if(!empty($trade) && $thread['special'] == 2 && !empty($_FILES['tradeattach']['tmp_name'][0])) {
			$tradeaid = $insertid;
		}
		$message = str_replace($searcharray, $replacearray, preg_replace($pregarray, $replacearray, $message));
		$db->query("UPDATE {$tablepre}posts SET message='$message' WHERE pid='$pid'");
		updatecredits($discuz_uid, $postattachcredits, count($attachments));
	}
	if($swfupload) {
		updateswfattach();
	}

	$replymessage = 'post_reply_succeed';

	if($special == 2 && $allowposttrade && $thread['authorid'] == $discuz_uid && !empty($trade) && !empty($item_name) && !empty($item_price)) {

		if($tradetypes && $optiondata) {
			foreach($optiondata as $optionid => $value) {
				$db->query("INSERT INTO {$tablepre}tradeoptionvars (sortid, pid, optionid, value)
					VALUES ('$tradetypeid', '$pid', '$optionid', '$value')");
			}
		}

		require_once DISCUZ_ROOT.'./include/trade.func.php';
		trade_create(array(
			'tid' => $tid,
			'pid' => $pid,
			'aid' => $tradeaid,
			'typeid' => $tradetypeid,
			'item_expiration' => $item_expiration,
			'thread' => $thread,
			'discuz_uid' => $discuz_uid,
			'author' => $author,
			'seller' => $seller,
			'item_name' => $item_name,
			'item_price' => $item_price,
			'item_number' => $item_number,
			'item_quality' => $item_quality,
			'item_locus' => $item_locus,
			'transport' => $transport,
			'postage_mail' => $postage_mail,
			'postage_express' => $postage_express,
			'postage_ems' => $postage_ems,
			'item_type' => $item_type,
			'item_costprice' => $item_costprice
		));

		$replymessage = 'trade_add_succeed';

	}

	$forum['threadcaches'] && deletethreadcaches($tid);

	if($modnewreplies) {
		$db->query("UPDATE {$tablepre}forums SET todayposts=todayposts+1 WHERE fid='$fid'", 'UNBUFFERED');

		if($newsubscribed) {
			$db->query("UPDATE {$tablepre}threads SET subscribed='1' WHERE tid='$tid'", 'UNBUFFERED');
		}
		showmessage('post_reply_mod_succeed', "forumdisplay.php?fid=$fid");
	} else {

		$db->query("UPDATE {$tablepre}threads SET lastposter='$author', lastpost='$timestamp', replies=replies+1 ".($attachment ? ", attachment='$attachment'" : '').", subscribed='".($subscribed || $newsubscribed ? 1 : 0)."' WHERE tid='$tid'", 'UNBUFFERED');

		updatepostcredits('+', $discuz_uid, $replycredits);

		$lastpost = "$thread[tid]\t".addslashes($thread['subject'])."\t$timestamp\t$author";
		$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost', posts=posts+1, todayposts=todayposts+1 WHERE fid='$fid'", 'UNBUFFERED');
		if($forum['type'] == 'sub') {
			$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost' WHERE fid='$forum[fup]'", 'UNBUFFERED');
		}

		$feed = array();
		if($addfeed && $forum['allowfeed'] && $thread['authorid'] != $discuz_uid) {
			if($special == 2 && !empty($trade) && !empty($item_name) && !empty($item_price)) {
				$feed['icon'] = 'goods';
				$feed['title_template'] = 'feed_thread_goods_title';
				$feed['body_template'] = 'feed_thread_goods_message';
				$feed['body_data'] = array(
					'itemname'=> "<a href=\"{$boardurl}viewthread.php?do=tradeinfo&tid=$tid&pid=$pid\">$item_name</a>",
					'itemprice'=> $item_price
				);
				$attachurl = preg_match("/^((https?|ftps?):\/\/|www\.)/i", $attachurl) ? $attachurl : $boardurl.$attachurl;
				$imgurl = $boardurl.$attachurl.'/'.$attachments[2]['attachment'].($attachments[2]['thumb'] && $attachments[2]['type'] != 'image/gif' ? '.thumb.jpg' : '');
				$feed['images'][] = array('url' => $imgurl, 'link' => "{$boardurl}viewthread.php?do=tradeinfo&tid=$tid&pid=$pid");
			} elseif($special == 3) {
				$feed['icon'] = 'reward';
				$feed['title_template'] = 'feed_reply_reward_title';
				$feed['title_data'] = array(
					'subject' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$thread[subject]</a>",
					'author' => "<a href=\"space.php?uid=$thread[authorid]\">$thread[author]</a>"
				);
			} elseif($special == 5) {
				$feed['icon'] = 'debate';
				$feed['title_template'] = 'feed_thread_debatevote_title';
				$feed['title_data'] = array(
					'subject' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$thread[subject]</a>",
					'author' => "<a href=\"space.php?uid=$thread[authorid]\">$thread[author]</a>"
				);
			} else {
				$feed['icon'] = 'post';
				$feed['title_template'] = 'feed_reply_title';
				$feed['title_data'] = array(
					'subject' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$thread[subject]</a>",
					'author' => "<a href=\"space.php?uid=$thread[authorid]\">$thread[author]</a>"
				);
			}
			postfeed($feed);
		}

		showmessage($replymessage, "viewthread.php?tid=$tid&pid=$pid&page=".(@ceil(($thread['special'] ? $thread['replies'] + 1 : $thread['replies'] + 2) / $ppp))."&extra=$extra#pid$pid");
	}

}

?>