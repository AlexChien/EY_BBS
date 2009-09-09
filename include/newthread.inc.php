<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: newthread.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$discuz_action = 11;

if(($special == 1 && !$allowpostpoll) || ($special == 2 && !$allowposttrade) || ($special == 3 && !$allowpostreward) || ($special == 4 && !$allowpostactivity) || ($special == 5 && !$allowpostdebate) || ($special == 6 && !$allowpostvideo)) {
	showmessage('group_nopermission', NULL, 'NOPERM');
}

if($special == 6) {
	require_once DISCUZ_ROOT.'./api/video.php';
	require_once DISCUZ_ROOT.'./include/insenz.func.php';
}

if(!$discuz_uid && !((!$forum['postperm'] && $allowpost) || ($forum['postperm'] && forumperm($forum['postperm'])))) {
	showmessage('group_nopermission', NULL, 'NOPERM');
} elseif(empty($forum['allowpost'])) {
	if(!$forum['postperm'] && !$allowpost) {
		showmessage('group_nopermission', NULL, 'NOPERM');
	} elseif($forum['postperm'] && !forumperm($forum['postperm'])) {
		showmessage('post_forum_newthread_nopermission', NULL, 'HALTED');
	}
} elseif($forum['allowpost'] == -1) {
	showmessage('post_forum_newthread_nopermission', NULL, 'HALTED');
}

if($url && !empty($qihoo['relate']['webnum'])) {
	$from = in_array($from, array('direct', 'iframe')) ? $from : '';
	if($data = @implode('', file("http://search.qihoo.com/sint/content.html?surl=$url&md5=$md5&ocs=$charset&ics=$charset&from=$from"))) {
		preg_match_all("/(\w+):([^\>]+)/i", $data, $data);
		if(!$data[2][1]) {
			$subject = trim($data[2][3]);
			$message = !$editormode ? str_replace('[br]', "\n", trim($data[2][4])) : str_replace('[br]', '<br />', trim($data[2][4]));
		} else {
			showmessage('reprint_invalid');
		}
	}
}

checklowerlimit($postcredits);

if(!submitcheck('topicsubmit', 0, $seccodecheck, $secqaacheck)) {
	$modelid = $modelid ? intval($modelid) : '';
	$isfirstpost = 1;
	$tagoffcheck = '';
	$showthreadsorts = !empty($sortid); 

	$icons = '';
	if(!$special && is_array($_DCACHE['icons'])) {
		$key = 1;
		foreach($_DCACHE['icons'] as $id => $icon) {
			$icons .= ' <input class="radio" type="radio" name="iconid" value="'.$id.'" /><img src="images/icons/'.$icon.'" alt="" />';
			$icons .= !(++$key % 10) ? '<br />' : '';
		}
	}

	if($special == 2 && $allowposttrade) {
		$expiration_7days = date('Y-m-d', $timestamp + 86400 * 7);
		$expiration_14days = date('Y-m-d', $timestamp + 86400 * 14);
		$trade['expiration'] = $expiration_month = date('Y-m-d', mktime(0, 0, 0, date('m')+1, date('d'), date('Y')));
		$expiration_3months = date('Y-m-d', mktime(0, 0, 0, date('m')+3, date('d'), date('Y')));
		$expiration_halfyear = date('Y-m-d', mktime(0, 0, 0, date('m')+6, date('d'), date('Y')));
		$expiration_year = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+1));

		$forum['tradetypes'] = $forum['tradetypes'] == '' ? -1 : unserialize($forum['tradetypes']);		

	} elseif($special == 6 && $allowpostvideo) {

		$videoAccount = new VideoClient_Util($appid, $siteid, $sitekey);
		$videoupload = $videoAccount->createUploadFrom($option, array('url' => 'data.php'));

		$query = $db->query("SELECT value FROM {$tablepre}settings WHERE variable='videoinfo'");
		$settings = unserialize($db->result($query, 0));

		if($settings['videotype'] && is_array($settings['videotype'])) {
			$vtypeselect = '<select name="vclass">';
			foreach($settings['videotype'] as $key => $type) {
				if($type['able']) {
					$vtypeselect .= '<option value="'.$key.'"> '.$type['name'].'</option>';
				}
			}
			$vtypeselect .= '</select>';
		}

	}

	if($special == 4) {
		$activitytypelist = $activitytype ? explode("\n", trim($activitytype)) : '';
	}

	include template('post');

} else {
	
	if($subject == '') {
		showmessage('post_sm_isnull');
	}

	if(!$sortid && !$special && $message == '') {
		showmessage('post_sm_isnull');
	}

	if($post_invalid = checkpost($special)) {
		showmessage($post_invalid);
	}

	if(checkflood()) {
		showmessage('post_flood_ctrl');
	}

	if($allowpostattach && is_array($_FILES['attach'])) {
		foreach($_FILES['attach']['name'] as $attachname) {
			if($attachname != '') {
				checklowerlimit($postattachcredits);
				break;
			}
		}
	}

	$typeid = isset($typeid) && isset($forum['threadtypes']['types'][$typeid]) ? $typeid : 0;
	$iconid = !empty($iconid) && isset($_DCACHE['icons'][$iconid]) ? $iconid : 0;
	$displayorder = $modnewthreads ? -2 : (($forum['ismoderator'] && !empty($sticktopic)) ? 1 : 0);
	$digest = ($forum['ismoderator'] && !empty($addtodigest)) ? 1 : 0;
	$readperm = $allowsetreadperm ? $readperm : 0;
	$isanonymous = $isanonymous && $allowanonymous ? 1 : 0;
	$price = intval($price);
	$price = $maxprice && !$special ? ($price <= $maxprice ? $price : $maxprice) : 0;

	if(!$typeid && $forum['threadtypes']['required'] && !$special) {
		showmessage('post_type_isnull');
	}

	if(!$sortid && $forum['threadsorts']['required'] && !$special) {
		showmessage('post_sort_isnull');
	}

	if($price > 0 && floor($price * (1 - $creditstax)) == 0) {
		showmessage('post_net_price_iszero');
	}

	if($special == 1) {

		$pollarray = array();
		foreach($polloption as $key => $value) {
			if(trim($value) === '') {
				unset($polloption[$key]);
			}
		}

		if(count($polloption) > $maxpolloptions) {
			showmessage('post_poll_option_toomany');
		} elseif(count($polloption) < 2) {
			showmessage('post_poll_inputmore');
		}

		$maxchoices = !empty($multiplepoll) ? (!$maxchoices || $maxchoices >= count($polloption) ? count($polloption) : $maxchoices) : '';
		$pollarray['options'] = $polloption;
		$pollarray['multiple'] = !empty($multiplepoll);
		$pollarray['visible'] = empty($visibilitypoll);
		$pollarray['overt'] = !empty($overt);

		if(preg_match("/^\d*$/", trim($maxchoices)) && preg_match("/^\d*$/", trim($expiration))) {
			if(!$pollarray['multiple']) {
				$pollarray['maxchoices'] = 1;
			} elseif(empty($maxchoices)) {
				$pollarray['maxchoices'] = 0;
			} elseif($maxchoices == 1) {
				$pollarray['multiple'] = 0;
				$pollarray['maxchoices'] = $maxchoices;
			} else {
				$pollarray['maxchoices'] = $maxchoices;
			}
			if(empty($expiration)) {
				$pollarray['expiration'] = 0;
			} else {
				$pollarray['expiration'] = $timestamp + 86400 * $expiration;
			}
		} else {
			showmessage('poll_maxchoices_expiration_invalid');
		}

	} elseif($special == 3) {

		$rewardprice = intval($rewardprice);
		if($rewardprice < 1) {
			showmessage('reward_credits_please');
		} elseif($rewardprice > 32767) {
			showmessage('reward_credits_overflow');
		} elseif($rewardprice < $minrewardprice || ($maxrewardprice > 0 && $rewardprice > $maxrewardprice)) {
			if($maxrewardprice > 0) {
				showmessage('reward_credits_between');
			} else {
				showmessage('reward_credits_lower');
			}
		} elseif(($realprice = $rewardprice + ceil($rewardprice * $creditstax)) > $_DSESSION["extcredits$creditstransextra[2]"]) {
			showmessage('reward_credits_shortage');
		}

		$price = $rewardprice;

		$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[2]=extcredits$creditstransextra[2]-$realprice WHERE uid='$discuz_uid'");

	} elseif($special == 4) {

		$activitytime = intval($activitytime);
		if(empty($starttimefrom[$activitytime])) {
			showmessage('activity_fromtime_please');
		} elseif(@strtotime($starttimefrom[$activitytime]) === -1 || @strtotime($starttimefrom[$activitytime]) === FALSE) {
			showmessage('activity_fromtime_error');
		} elseif($activitytime && ((@strtotime($starttimefrom) > @strtotime($starttimeto) || !$starttimeto))) {
			showmessage('activity_fromtime_error');
		} elseif(!trim($activityclass)) {
			showmessage('activity_sort_please');
		} elseif(!trim($activityplace)) {
			showmessage('activity_address_please');
		} elseif(trim($activityexpiration) && (@strtotime($activityexpiration) === -1 || @strtotime($activityexpiration) === FALSE)) {
			showmessage('activity_totime_error');
		}

		$activity = array();
		$activity['class'] = dhtmlspecialchars(trim($activityclass));
		$activity['starttimefrom'] = @strtotime($starttimefrom[$activitytime]);
		$activity['starttimeto'] = $activitytime ? @strtotime($starttimeto) : 0;
		$activity['place'] = dhtmlspecialchars(trim($activityplace));
		$activity['cost'] = intval($cost);
		$activity['gender'] = intval($gender);
		$activity['number'] = intval($activitynumber);

		if($activityexpiration) {
			$activity['expiration'] = @strtotime($activityexpiration);
		} else {
			$activity['expiration'] = 0;
		}
		if(trim($activitycity)) {
			$subject .= '['.dhtmlspecialchars(trim($activitycity)).']';
		}

	} elseif($special == 5) {

		if(empty($affirmpoint) || empty($negapoint)) {
			showmessage('debate_position_nofound');
		} elseif(!empty($endtime) && (!($endtime = @strtotime($endtime)) || $endtime < $timestamp)) {
			showmessage('debate_endtime_invalid');
		} elseif(!empty($umpire)) {
			if(!$db->result_first("SELECT COUNT(*) FROM {$tablepre}members WHERE username='$umpire'")) {
				$umpire = dhtmlspecialchars($umpire);
				showmessage('debate_umpire_invalid');
			}
		}
		$affirmpoint = dhtmlspecialchars($affirmpoint);
		$negapoint = dhtmlspecialchars($negapoint);
		$stand = intval($stand);

	} elseif($special == 6) {
		if(empty($vid) || empty($vsubject) || empty($vtag)) {
			showmessage('video_required_invalid');
		}
	}

	$sortid = $special && $forum['threadsorts']['types'][$sortid] ? 0 : $sortid;
	$typeexpiration = intval($typeexpiration);

	if($forum['threadsorts']['expiration'][$typeid] && !$typeexpiration) {
		showmessage('threadtype_expiration_invalid');
	}

	$optiondata = array();
	if($forum['threadsorts']['types'][$sortid] && !$forum['allowspecialonly']) {
		$optiondata = threadsort_validator($typeoption);
	}

	$author = !$isanonymous ? $discuz_user : '';

	$moderated = $digest || $displayorder > 0 ? 1 : 0;

	$attachment = ($allowpostattach && $attachments = attach_upload()) ? ($imageexists ? 2 : 1) : 0;

	$subscribed = !empty($emailnotify) && $discuz_uid ? 1 : 0;

	$db->query("INSERT INTO {$tablepre}threads (fid, readperm, price, iconid, typeid, sortid, author, authorid, subject, dateline, lastpost, lastposter, displayorder, digest, special, attachment, subscribed, moderated)
		VALUES ('$fid', '$readperm', '$price', '$iconid', '$typeid', '$sortid', '$author', '$discuz_uid', '$subject', '$timestamp', '$timestamp', '$author', '$displayorder', '$digest', '$special', '$attachment', '$subscribed', '$moderated')");
	$tid = $db->insert_id();

	if($subscribed) {
		$db->query("REPLACE INTO {$tablepre}subscriptions (uid, tid, lastpost, lastnotify)
			VALUES ('$discuz_uid', '$tid', '$timestamp', '$timestamp')", 'UNBUFFERED');
	}

	if($special == 3 && $allowpostreward) {
		$db->query("INSERT INTO {$tablepre}rewardlog (tid, authorid, netamount, dateline) VALUES ('$tid', '$discuz_uid', $realprice, '$timestamp')");
	}

	$db->query("REPLACE INTO {$tablepre}mythreads (uid, tid, dateline, special) VALUES ('$discuz_uid', '$tid', '$timestamp', '$special')", 'UNBUFFERED');

	if($moderated) {
		updatemodlog($tid, ($displayorder > 0 ? 'STK' : 'DIG'));
		updatemodworks(($displayorder > 0 ? 'STK' : 'DIG'), 1);
	}

	if($special == 1) {
		$db->query("INSERT INTO {$tablepre}polls (tid, multiple, visible, maxchoices, expiration, overt)
			VALUES ('$tid', '$pollarray[multiple]', '$pollarray[visible]', '$pollarray[maxchoices]', '$pollarray[expiration]', '$pollarray[overt]')");
		foreach($pollarray['options'] as $polloptvalue) {
			$polloptvalue = dhtmlspecialchars(trim($polloptvalue));
			$db->query("INSERT INTO {$tablepre}polloptions (tid, polloption) VALUES ('$tid', '$polloptvalue')");
		}

	} elseif($special == 4 && $allowpostactivity) {
		$db->query("INSERT INTO {$tablepre}activities (tid, uid, cost, starttimefrom, starttimeto, place, class, gender, number, expiration)
			VALUES ('$tid', '$discuz_uid', '$activity[cost]', '$activity[starttimefrom]', '$activity[starttimeto]', '$activity[place]', '$activity[class]', '$activity[gender]', '$activity[number]', '$activity[expiration]')");

	} elseif($special == 5 && $allowpostdebate) {

		$db->query("INSERT INTO {$tablepre}debates (tid, uid, starttime, endtime, affirmdebaters, negadebaters, affirmvotes, negavotes, umpire, winner, bestdebater, affirmpoint, negapoint, umpirepoint)
			VALUES ('$tid', '$discuz_uid', '$timestamp', '$endtime', '0', '0', '0', '0', '$umpire', '', '', '$affirmpoint', '$negapoint', '')");

	} elseif($special == 6 && $allowpostvideo) {

		$vid = dhtmlspecialchars($vid);
		$vsubject = dhtmlspecialchars($vsubject);
		$vclass = intval($vclass);
		$visup = intval($visup);
		$vlength = intval($vlength);
		$vautoplay = $vautoplay ? intval($vautoplay) : 2;
		$vshare = $vshare ? intval($vshare) : 1;

		$videoAccount = new VideoClient_VideoService($appid, $siteid, $sitekey);
		$result = $videoAccount->upload($vid, $tid, $visup, insenz_convert($vsubject, 1), insenz_convert($vtag, 1), '', $vclass, $vautoplay, $vshare);
		$query = $db->query("INSERT INTO {$tablepre}videos (vid, tid, uid, dateline, vthumb, vtitle, vclass, vtime, visup, vautoplay)
			VALUES ('$vid', '$tid', '$discuz_uid', '$timestamp', '', '$vsubject', '$vclass', '$vlength', '$visup', '$vautoplay')", 'SILENT');

	}

	if($forum['threadsorts']['types'][$sortid] && !empty($optiondata) && is_array($optiondata)) {
		foreach($optiondata as $optionid => $value) {
			$db->query("INSERT INTO {$tablepre}typeoptionvars (sortid, tid, optionid, value, expiration)
				VALUES ('$sortid', '$tid', '$optionid', '$value', '".($typeexpiration ? $timestamp + $typeexpiration : 0)."')");
		}
	}

	$bbcodeoff = checkbbcodes($message, !empty($bbcodeoff));
	$smileyoff = checksmilies($message, !empty($smileyoff));
	$parseurloff = !empty($parseurloff);
	$htmlon = bindec(($tagstatus && !empty($tagoff) ? 1 : 0).($allowhtml && !empty($htmlon) ? 1 : 0));

	$pinvisible = $modnewthreads ? -2 : 0;
	$message = preg_replace('/\[attachimg\](\d+)\[\/attachimg\]/is', '[attach]\1[/attach]', $message);
	$db->query("INSERT INTO {$tablepre}posts (fid, tid, first, author, authorid, subject, dateline, message, useip, invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff, attachment)
		VALUES ('$fid', '$tid', '1', '$discuz_user', '$discuz_uid', '$subject', '$timestamp', '$message', '$onlineip', '$pinvisible', '$isanonymous', '$usesig', '$htmlon', '$bbcodeoff', '$smileyoff', '$parseurloff', '$attachment')");
	$pid = $db->insert_id();

	if($tagstatus && $tags != '') {
		$tags = str_replace(array(chr(0xa3).chr(0xac), chr(0xa1).chr(0x41), chr(0xef).chr(0xbc).chr(0x8c)), ',', censor($tags));
		if(strexists($tags, ',')) {
			$tagarray = array_unique(explode(',', $tags));
		} else {
			$tags = str_replace(array(chr(0xa1).chr(0xa1), chr(0xa1).chr(0x40), chr(0xe3).chr(0x80).chr(0x80)), ' ', $tags);
			$tagarray = array_unique(explode(' ', $tags));
		}
		$tagcount = 0;
		foreach($tagarray as $tagname) {
			$tagname = trim($tagname);
			if(preg_match('/^([\x7f-\xff_-]|\w|\s){3,20}$/', $tagname)) {
				$query = $db->query("SELECT closed FROM {$tablepre}tags WHERE tagname='$tagname'");
				if($db->num_rows($query)) {
					if(!$tagstatus = $db->result($query, 0)) {
						$db->query("UPDATE {$tablepre}tags SET total=total+1 WHERE tagname='$tagname'", 'UNBUFFERED');
					}
				} else {
					$db->query("INSERT INTO {$tablepre}tags (tagname, closed, total)
						VALUES ('$tagname', 0, 1)", 'UNBUFFERED');
					$tagstatus = 0;
				}
				if(!$tagstatus) {
					$db->query("INSERT {$tablepre}threadtags (tagname, tid) VALUES ('$tagname', $tid)", 'UNBUFFERED');
				}
				$tagcount++;
				if($tagcount > 4) {
					unset($tagarray);
					break;
				}
			}
		}
	}

	$tradeaid = 0;
	if($attachment) {
		$searcharray = $pregarray = $replacearray = array();
		foreach($attachments as $key => $attach) {
			$db->query("INSERT INTO {$tablepre}attachments (tid, pid, dateline, readperm, price, filename, description, filetype, filesize, attachment, downloads, isimage, uid, thumb, remote, width)
				VALUES ('$tid', '$pid', '$timestamp', '$attach[perm]', '$attach[price]', '$attach[name]', '$attach[description]', '$attach[type]', '$attach[size]', '$attach[attachment]', '0', '$attach[isimage]', '$attach[uid]', '$attach[thumb]', '$attach[remote]', '$attach[width]')");
			$searcharray[] = '[local]'.$localid[$key].'[/local]';
			$pregarray[] = '/\[localimg=(\d{1,3}),(\d{1,3})\]'.$localid[$key].'\[\/localimg\]/is';
			$replacearray[] = '[attach]'.$db->insert_id().'[/attach]';
		}
		$message = str_replace($searcharray, $replacearray, preg_replace($pregarray, $replacearray, $message));
		$db->query("UPDATE {$tablepre}posts SET message='$message' WHERE pid='$pid'");
		updatecredits($discuz_uid, $postattachcredits, count($attachments));
	}
	if($swfupload) {
		updateswfattach();
	}

	if($modnewthreads) {
		$db->query("UPDATE {$tablepre}forums SET todayposts=todayposts+1 WHERE fid='$fid'", 'UNBUFFERED');
		showmessage('post_newthread_mod_succeed', "forumdisplay.php?fid=$fid");
	} else {

		$feed = array(
			'icon' => '',
			'title_template' => '',
			'title_data' => array(),
			'body_template' => '',
			'body_data' => array(),
			'title_data'=>array(),
			'images'=>array()
		);
		if($addfeed && $forum['allowfeed']) {
			if($special == 0) {
				$feed['icon'] = 'thread';
				$feed['title_template'] = 'feed_thread_title';
				$feed['body_template'] = 'feed_thread_message';
				$feed['body_data'] = array(
					'subject' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
					'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150)
				);
			} elseif($special > 0) {
				if($special == 1) {
					$feed['icon'] = 'poll';
					$feed['title_template'] = 'feed_thread_poll_title';
					$feed['body_template'] = 'feed_thread_poll_message';
					$feed['body_data'] = array(
						'subject' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150)
					);
				} elseif($special == 3) {
					$feed['icon'] = 'reward';
					$feed['title_template'] = 'feed_thread_reward_title';
					$feed['body_template'] = 'feed_thread_reward_message';
					$feed['body_data'] = array(
						'subject'=> "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
						'rewardprice'=> $rewardprice,
						'extcredits' => $extcredits[$creditstransextra[2]]['title'],
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150)
					);
				} elseif($special == 4) {
					$feed['icon'] = 'activity';
					$feed['title_template'] = 'feed_thread_activity_title';
					$feed['body_template'] = 'feed_thread_activity_message';
					$feed['body_data'] = array(
						'subject'=> "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
						'starttimefrom' => $starttimefrom[$activitytime],
						'activityplace'=> $activityplace,
						'cost'=> $cost,
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150)
					);
				} elseif($special == 5) {
					$feed['icon'] = 'debate';
					$feed['title_template'] = 'feed_thread_debate_title';
					$feed['body_template'] = 'feed_thread_debate_message';
					$feed['body_data'] = array(
						'subject'=> "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150),
						'affirmpoint'=> cutstr(strip_tags(preg_replace("/\[.+?\]/is", '', $affirmpoint)), 150),
						'negapoint'=> cutstr(strip_tags(preg_replace("/\[.+?\]/is", '', $negapoint)), 150)
					);
				} elseif($special == 6) {
					$feed['icon'] = 'video';
					$feed['title_template'] = 'feed_thread_video_title';
					$feed['body_template'] = 'feed_thread_video_message';
					$feed['body_data'] = array(
						'subject'=> "<a href=\"{$boardurl}viewthread.php?tid=$tid\">$subject</a>",
						'play' => "<a href=\"{$boardurl}viewthread.php?tid=$tid\" class=\"playbutton\">Play</a>",
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150),
						'vlength'=> sprintf("%02d", intval($vlength / 60)).':'.sprintf("%02d", intval($vlength % 60)),
					);
				}
			}

			if($special == 6) {
				$feed['images'][] = array('url' => VideoClient_Util::getThumbUrl($vid, 'small'), 'link' => "{$boardurl}viewthread.php?tid=$tid");
			} else {
				if(in_array($attachments[1]['type'], array('image/gif', 'image/jpeg', 'image/png'))) {
					$attachurl = preg_match("/^((https?|ftps?):\/\/|www\.)/i", $attachurl) ? $attachurl : $boardurl.$attachurl;
					$imgurl = $attachurl.'/'.$attachments[1]['attachment'].($attachments[1]['thumb'] && $attachments[1]['type'] != 'image/gif' ? '.thumb.jpg' : '');
					$feed['images'][] = $attachments[1]['attachment'] ? array('url' => $imgurl, 'link' => "{$boardurl}viewthread.php?tid=$tid") : array();
				}
			}

			if($feed) {
				postfeed($feed);
			}
		}

		if($digest) {
			foreach($digestcredits as $id => $addcredits) {
				$postcredits[$id] = (isset($postcredits[$id]) ? $postcredits[$id] : 0) + $addcredits;
			}
		}
		updatepostcredits('+', $discuz_uid, $postcredits);

		$subject = str_replace("\t", ' ', $subject);
		$lastpost = "$tid\t$subject\t$timestamp\t$author";
		$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost', threads=threads+1, posts=posts+1, todayposts=todayposts+1 WHERE fid='$fid'", 'UNBUFFERED');
		if($forum['type'] == 'sub') {
			$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost' WHERE fid='$forum[fup]'", 'UNBUFFERED');
		}

		showmessage('post_newthread_succeed', "viewthread.php?tid=$tid&extra=$extra");

	}
}

?>