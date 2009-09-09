<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: editpost.inc.php 17400 2008-12-17 09:59:30Z liuqiang $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if($special == 6) {
	require_once DISCUZ_ROOT.'./api/video.php';
	require_once DISCUZ_ROOT.'./include/insenz.func.php';
}

$discuz_action = 13;

$orig = $db->fetch_first("SELECT m.adminid, p.first, p.authorid, p.author, p.dateline, p.anonymous, p.invisible, p.htmlon FROM {$tablepre}posts p
	LEFT JOIN {$tablepre}members m ON m.uid=p.authorid
	WHERE pid='$pid' AND tid='$tid' AND fid='$fid'");

if($magicstatus) {
	$magicid = $db->result_first("SELECT magicid FROM {$tablepre}threadsmod WHERE tid='$tid' AND magicid='10'");
	$allowanonymous = $allowanonymous || $magicid ? 1 : $allowanonymous;
}

$isfirstpost = $orig['first'] ? 1 : 0;
$isorigauthor = $discuz_uid && $discuz_uid == $orig['authorid'];
$isanonymous = $isanonymous && $allowanonymous ? 1 : 0;
$audit = $orig['invisible'] == -2 || $thread['displayorder'] == -2 ? $audit : 0;

if(empty($orig)) {
	showmessage('undefined_action');
} elseif((!$forum['ismoderator'] || !$alloweditpost || (in_array($orig['adminid'], array(1, 2, 3)) && $adminid > $orig['adminid'])) && !($forum['alloweditpost'] && $isorigauthor)) {
	showmessage('post_edit_nopermission', NULL, 'HALTED');
} elseif($thread['digest'] == '-1' && $isfirstpost) {
	showmessage('special_noaction');
} elseif($isorigauthor && !$forum['ismoderator']) {
	if($edittimelimit && $timestamp - $orig['dateline'] > $edittimelimit * 60) {
		showmessage('post_edit_timelimit', NULL, 'HALTED');
	} elseif(($isfirstpost && $modnewthreads) || (!$isfirstpost && $modnewreplies)) {
		showmessage('post_edit_moderate');
	}
}

if($swfattachs && $orig['authorid'] != $discuz_uid) {
	$swfattachs = 0;
}

$thread['pricedisplay'] = $thread['price'] == -1 ? 0 : $thread['price'];

if($tagstatus) {
	$query = $db->query("SELECT tagname FROM {$tablepre}threadtags WHERE tid='$tid'");
	$threadtagary = array();
	while($tagname = $db->fetch_array($query)) {
		$threadtagary[] = $tagname['tagname'];
	}
	$threadtags = dhtmlspecialchars(implode(' ',$threadtagary));
}

if($special == 5) {
	$debate = array_merge($thread, daddslashes($db->fetch_first("SELECT * FROM {$tablepre}debates WHERE tid='$tid'"), 1));
	$firststand = $db->result_first("SELECT stand FROM {$tablepre}debateposts WHERE tid='$tid' AND uid='$discuz_uid' AND stand<>'0' ORDER BY dateline LIMIT 1");

	if(!$isfirstpost && $debate['endtime'] && $debate['endtime'] < $timestamp && !$forum['ismoderator']) {
		showmessage('debate_end');
	}
	if($isfirstpost && $debate['umpirepoint'] && !$forum['ismoderator']) {
		showmessage('debate_umpire_comment_invalid');
	}
}

if(!submitcheck('editsubmit')) {

	include_once language('misc');


	$icons = '';
	if(!$special && is_array($_DCACHE['icons']) && $isfirstpost) {
		$key = 1;
		foreach($_DCACHE['icons'] as $id => $icon) {
			$icons .= ' <input class="radio" type="radio" name="iconid" value="'.$id.'" '.($thread['iconid'] == $id ? 'checked="checked"' : '').' /><img src="images/icons/'.$icon.'" alt="" />';
			$icons .= !(++$key % 10) ? '<br />' : '';
		}
	}

	$postinfo = $db->fetch_first("SELECT * FROM {$tablepre}posts WHERE pid='$pid' AND tid='$tid' AND fid='$fid'");

	$usesigcheck = $postinfo['usesig'] ? 'checked="checked"' : '';
	$urloffcheck = $postinfo['parseurloff'] ? 'checked="checked"' : '';
	$smileyoffcheck = $postinfo['smileyoff'] == 1 ? 'checked="checked"' : '';
	$codeoffcheck = $postinfo['bbcodeoff'] == 1 ? 'checked="checked"' : '';
	$tagoffcheck = $postinfo['htmlon'] & 2 ? 'checked="checked"' : '';
	$htmloncheck = $postinfo['htmlon'] & 1 ? 'checked="checked"' : '';
	$showthreadsorts = ($thread['sortid'] || !empty($sortid)) && $isfirstpost;
	$sortid = empty($sortid) ? $thread['sortid'] : $sortid;

	$poll = $temppoll = '';
	if($isfirstpost) {
		$thread['freecharge'] = $maxchargespan && $timestamp - $thread['dateline'] >= $maxchargespan * 3600 ? 1 : 0;
		if($thread['special'] == 1 && ($alloweditpoll || $thread['authorid'] == $discuz_uid)) {
			$query = $db->query("SELECT polloptionid, displayorder, polloption, multiple, visible, maxchoices, expiration, overt FROM {$tablepre}polloptions AS polloptions LEFT JOIN {$tablepre}polls AS polls ON polloptions.tid=polls.tid WHERE polls.tid ='$tid' ORDER BY displayorder");
			while($temppoll = $db->fetch_array($query)) {
				$poll['multiple'] = $temppoll['multiple'];
				$poll['visible'] = $temppoll['visible'];
				$poll['maxchoices'] = $temppoll['maxchoices'];
				$poll['expiration'] = $temppoll['expiration'];
				$poll['overt'] = $temppoll['overt'];
				$poll['polloptionid'][] = $temppoll['polloptionid'];
				$poll['displayorder'][] = $temppoll['displayorder'];
				$poll['polloption'][] = stripslashes($temppoll['polloption']);
			}
			$maxpolloptions = $maxpolloptions - $db->num_rows($query);
		} elseif($thread['special'] == 3) {
			$rewardprice = abs($thread['price']);
		} elseif($thread['special'] == 4) {
			$activitytypelist = $activitytype ? explode("\n", trim($activitytype)) : '';
			$activity = $db->fetch_first("SELECT * FROM {$tablepre}activities WHERE tid='$tid'");
			$activity['starttimefrom'] = gmdate("Y-m-d H:i", $activity['starttimefrom'] + $timeoffset * 3600);
			$activity['starttimeto'] = $activity['starttimeto'] ? gmdate("Y-m-d H:i", $activity['starttimeto'] + $timeoffset * 3600) : '';
			$activity['expiration'] = $activity['expiration'] ? gmdate("Y-m-d H:i", $activity['expiration'] + $timeoffset * 3600) : '';
		} elseif($thread['special'] == 5 ) {
			$debate['endtime'] = $debate['endtime'] ? gmdate("Y-m-d H:i", $debate['endtime'] + $timeoffset * 3600) : '';
		}
	}

	if($thread['special'] == 2 && $allowposttrade) {
		$query = $db->query("SELECT * FROM {$tablepre}trades WHERE pid='$pid'");
		$tradetypeselect = '';
		if($db->num_rows($query)) {
			$trade = $db->fetch_array($query);
			$trade['expiration'] = $trade['expiration'] ? date('Y-m-d', $trade['expiration']) : '';
			$trade['costprice'] = $trade['costprice'] > 0 ? $trade['costprice'] : '';
			$trade['message'] = dhtmlspecialchars($trade['message']);
			$tradetypeid = $trade['typeid'];
			$forum['tradetypes'] = $forum['tradetypes'] == '' ? -1 : unserialize($forum['tradetypes']);
			if((!$tradetypeid || !isset($tradetypes[$tradetypeid]) && !empty($forum['tradetypes']))) {
				$tradetypeselect = '<select name="tradetypeid" onchange="ajaxget(\'post.php?action=threadsorts&tradetype=yes&sortid=\'+this.options[this.selectedIndex].value+\'&sid='.$sid.'\', \'threadtypes\', \'threadtypeswait\')"><option value="0">&nbsp;</option>';
				foreach($tradetypes as $typeid => $name) {
					if($forum['tradetypes'] == -1 || @in_array($typeid, $forum['tradetypes'])) {
						$tradetypeselect .= '<option value="'.$typeid.'">'.strip_tags($name).'</option>';
					}
				}
				$tradetypeselect .= '</select><span id="threadtypeswait"></span>';
			} else {
				$tradetypeselect = '<select disabled><option>'.$tradetypes[$trade['typeid']].'</option></select>';
			}
			$expiration_7days = date('Y-m-d', $timestamp + 86400 * 7);
			$expiration_14days = date('Y-m-d', $timestamp + 86400 * 14);
			$expiration_month = date('Y-m-d', mktime(0, 0, 0, date('m')+1, date('d'), date('Y')));
			$expiration_3months = date('Y-m-d', mktime(0, 0, 0, date('m')+3, date('d'), date('Y')));
			$expiration_halfyear = date('Y-m-d', mktime(0, 0, 0, date('m')+6, date('d'), date('Y')));
			$expiration_year = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')+1));
		} else {
			$tradetypeid = $special = 0;
			$trade = array();
		}
	}

	if($thread['special'] == 6 && $allowpostvideo) {
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

		$videolist = array();
		$query = $db->query("SELECT vid, vthumb, vtitle, displayorder FROM {$tablepre}videos WHERE tid='$tid'");
		while($videoinfo = $db->fetch_array($query)) {
			$videolist[] = $videoinfo;
		}
	}

	if($postinfo['attachment']) {
		require_once DISCUZ_ROOT.'./include/attachment.func.php';

		$attachfind = $attachreplace = $attachments = array();
		$query = $db->query("SELECT * FROM {$tablepre}attachments WHERE pid='$postinfo[pid]'");
		while($attach = $db->fetch_array($query)) {
			$attach['filenametitle'] = $attach['filename'];
			$attach['filename'] = cutstr($attach['filename'], 30);
			$attach['dateline'] = gmdate("$dateformat $timeformat", $attach['dateline'] + $timeoffset * 3600);
			$attach['filesize'] = sizecount($attach[filesize]);
			$attach['filetype'] = attachtype(fileext($attach['attachment'])."\t".$attach['filetype']);
			if($attach['isimage']) {
				$attach['url'] = $attach['remote'] ? $ftp['attachurl'] : $attachurl;
				$attachfind[] = "/\[attach\]$attach[aid]\[\/attach\]/i";
				$attachreplace[] = '[attachimg]'.$attach['aid'].'[/attachimg]';
			}
			if($special == 2 && $trade['aid'] == $attach['aid']) {
				$tradeattach = $attach;
				continue;
			}
			$attachments[] = $attach;
		}
	}

	$postinfo['subject'] = str_replace('"', '&quot;', $postinfo['subject']);
	$postinfo['message'] = dhtmlspecialchars($postinfo['message']);
	$postinfo['message'] = preg_replace($language['post_edit_regexp'], '', $postinfo['message']);

	if($postinfo['attachment'] && $attachfind) {
		$postinfo['message'] = preg_replace($attachfind, $attachreplace, $postinfo['message']);
	}

	if($special == 5) {
		$standselected = array($firststand => 'selected="selected"');
	}

	include template('post');

} else {

	$redirecturl = "viewthread.php?tid=$tid&page=$page&extra=$extra".($vid && $isfirstpost ? "&vid=$vid" : '')."#pid$pid";

	if(empty($delete)) {

		if($post_invalid = checkpost($isfirstpost && $special)) {
			showmessage($post_invalid);
		}

		if($allowpostattach && is_array($_FILES['attach'])) {
			foreach($_FILES['attach']['name'] as $attachname) {
				if($attachname != '') {
					checklowerlimit($creditspolicy['postattach']);
					break;
				}
			}
		}

		if(!$isorigauthor && !$allowanonymous) {
			if($orig['anonymous'] && !$isanonymous) {
				$isanonymous = 0;
				$authoradd = ', author=\''.addslashes($orig['author']).'\'';
				$anonymousadd = ', anonymous=\'0\'';
			} else {
				$isanonymous = $orig['anonymous'];
				$authoradd = $anonymousadd = '';
			}
		} else {
			$authoradd = ', author=\''.($isanonymous ? '' : addslashes($orig['author'])).'\'';
			$anonymousadd = ", anonymous='$isanonymous'";
		}

		if($isfirstpost) {

			if($subject == '') {
				showmessage('post_sm_isnull');
			}

			if(!$sortid && !$thread['special'] && $message == '') {
				showmessage('post_sm_isnull');
			}

			$typeid = isset($forum['threadtypes']['types'][$typeid]) ? $typeid : 0;
			$sortid = isset($forum['threadsorts']['types'][$sortid]) ? $sortid : 0;
			$iconid = isset($_DCACHE['icons'][$iconid]) ? $iconid : 0;

			if(!$typeid && $forum['threadtypes']['required'] && !$thread['special']) {
				showmessage('post_type_isnull');
			}

			$readperm = $allowsetreadperm ? intval($readperm) : ($isorigauthor ? 0 : 'readperm');
			$price = intval($price);
			$price = $thread['price'] < 0 && !$thread['special']
				?($isorigauthor || !$price ? -1 : $price)
				:($maxprice ? ($price <= $maxprice ? ($price > 0 ? $price : 0) : $maxprice) : ($isorigauthor ? 0 : $thread['price']));

			if($price > 0 && floor($price * (1 - $creditstax)) == 0) {
				showmessage('post_net_price_iszero');
			}

			$polladd = '';
			if($thread['special'] == 1 && ($alloweditpoll || $isorigauthor) && !empty($polls)) {
				$pollarray = '';
				foreach($polloption as $key => $value) {
					if($value === '') {
						unset($polloption[$key], $displayorder[$key]);
					}
				}
				$pollarray['options'] = $polloption;
				if($pollarray['options']) {
					if(count($pollarray['options']) > $maxpolloptions) {
						showmessage('post_poll_option_toomany');
					}
					foreach($pollarray['options'] as $key => $value) {
						if(!trim($value)) {
							$db->query("DELETE FROM {$tablepre}polloptions WHERE polloptionid='$key' AND tid='$tid'");
							unset($pollarray['options'][$key]);
						}
					}
					$polladd = ', special=\'1\'';
					foreach($displayorder as $key => $value) {
						if(preg_match("/^-?\d*$/", $value)) {
							$pollarray['displayorder'][$key] = $value;
						}
					}
					$pollarray['multiple'] = !empty($multiplepoll);
					$pollarray['visible'] = empty($visibilitypoll);
					$pollarray['expiration'] = $expiration;
					$pollarray['overt'] = !empty($overt);
					foreach($polloptionid as $key => $value) {
						if(!preg_match("/^\d*$/", $value)) {
							showmessage('submit_invalid');
						}
					}
					$maxchoices = !empty($multiplepoll) ? (!$maxchoices || $maxchoices >= count($pollarray['options']) ? count($pollarray['options']) : $maxchoices) : '';
					if(preg_match("/^\d*$/", $maxchoices)) {
						if(!$pollarray['multiple']) {
							$pollarray['maxchoices'] = 1;
						} elseif(empty($maxchoices)) {
							$pollarray['maxchoices'] = 0;
						} else {
							$pollarray['maxchoices'] = $maxchoices;
						}
					}
					$expiration = intval($expiration);
					if($close) {
						$pollarray['expiration'] = $timestamp;
					} elseif($expiration) {
						if(empty($pollarray['expiration'])) {
							$pollarray['expiration'] = 0;
						} else {
							$pollarray['expiration'] = $timestamp + 86400 * $expiration;
						}
					}
					$optid = '';
					$query = $db->query("SELECT polloptionid FROM {$tablepre}polloptions WHERE tid='$tid'");
					while($tempoptid = $db->fetch_array($query)) {
						$optid[] = $tempoptid['polloptionid'];
					}
					foreach($pollarray['options'] as $key => $value) {
						$value = dhtmlspecialchars(trim($value));
						if(in_array($polloptionid[$key], $optid)) {
							if($alloweditpoll) {
								$db->query("UPDATE {$tablepre}polloptions SET displayorder='".$pollarray['displayorder'][$key]."', polloption='$value' WHERE polloptionid='$polloptionid[$key]' AND tid='$tid'");
							} else {
								$db->query("UPDATE {$tablepre}polloptions SET displayorder='".$pollarray['displayorder'][$key]."' WHERE polloptionid='$polloptionid[$key]' AND tid='$tid'");
							}
						} else {
							$db->query("INSERT INTO {$tablepre}polloptions (tid, displayorder, polloption) VALUES ('$tid', '".$pollarray['displayorder'][$key]."', '$value')");
						}
					}
					$db->query("UPDATE {$tablepre}polls SET multiple='$pollarray[multiple]', visible='$pollarray[visible]', maxchoices='$pollarray[maxchoices]', expiration='$pollarray[expiration]', overt='$pollarray[overt]' WHERE tid='$tid'", 'UNBUFFERED');
				} else {
					$polladd = ', special=\'0\'';
					$db->query("DELETE FROM {$tablepre}polls WHERE tid='$tid'");
					$db->query("DELETE FROM {$tablepre}polloptions WHERE tid='$tid'");
				}

			} elseif($thread['special'] == 3 && $allowpostreward) {

				if($thread['price'] > 0 && $thread['price'] != $rewardprice) {
					$rewardprice = intval($rewardprice);
					if($rewardprice <= 0){
						showmessage("reward_credits_invalid");
					}
					$addprice = ceil(($rewardprice - $thread['price']) + ($rewardprice - $thread['price']) * $creditstax);
					if(!$forum['ismoderator']) {
						if($rewardprice < $thread['price']) {
							showmessage("reward_credits_fall");
						} elseif($rewardprice < $minrewardprice || ($maxrewardprice > 0 && $rewardprice > $maxrewardprice)) {
							showmessage("reward_credits_between");
						} elseif($addprice > $_DSESSION["extcredits$creditstransextra[2]"]) {
							showmessage('reward_credits_shortage');
						}
					}
					$realprice = ceil($thread['price'] + $thread['price'] * $creditstax) + $addprice;

					$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[2]=extcredits$creditstransextra[2]-$addprice WHERE uid='$thread[authorid]'");
					$db->query("UPDATE {$tablepre}rewardlog SET netamount='$realprice' WHERE tid='$tid' AND authorid='$thread[authorid]'");
				}

				if(!$forum['ismoderator']) {

					if($thread['replies'] > 1) {
						$subject = addslashes($thread['subject']);
					}

					if($thread['price'] < 0) {
						$rewardprice = abs($thread['price']);
					}
				}

				$price = $thread['price'] > 0 ? $rewardprice : -$rewardprice;

			} elseif($thread['special'] == 4 && $allowpostactivity) {

				$activitytime = intval($activitytime);
				if(empty($starttimefrom[$activitytime])) {
					showmessage('activity_fromtime_please');
				} elseif(strtotime($starttimefrom[$activitytime]) === -1 || @strtotime($starttimefrom[$activitytime]) === FALSE) {
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

				$db->query("UPDATE {$tablepre}activities SET cost='$activity[cost]', starttimefrom='$activity[starttimefrom]', starttimeto='$activity[starttimeto]', place='$activity[place]', class='$activity[class]', gender='$activity[gender]', number='$activity[number]', expiration='$activity[expiration]' WHERE tid='$tid'", 'UNBUFFERED');

			} elseif($thread['special'] == 5 && $allowpostdebate) {

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
				$db->query("UPDATE {$tablepre}debates SET affirmpoint='$affirmpoint', negapoint='$negapoint', endtime='$endtime', umpire='$umpire' WHERE tid='$tid' AND uid='$discuz_uid'");
			}

			$optiondata = array();
			if($forum['threadsorts']['types'][$sortid] && $checkoption) {
				$optiondata = threadsort_validator($typeoption);
			}

			if($forum['threadsorts']['types'][$sortid] && $optiondata && is_array($optiondata)) {
				foreach($optiondata as $optionid => $value) {
					$db->query("UPDATE {$tablepre}typeoptionvars SET value='$value' WHERE tid='$tid' AND optionid='$optionid'");
				}
			}

			$db->query("UPDATE {$tablepre}threads SET iconid='$iconid', typeid='$typeid', sortid='$sortid', subject='$subject', readperm='$readperm', price='$price' $authoradd $polladd ".($auditstatuson && $audit == 1 ? ",displayorder='0', moderated='1'" : '')." WHERE tid='$tid'", 'UNBUFFERED');

			if($tagstatus) {
				$tags = str_replace(array(chr(0xa3).chr(0xac), chr(0xa1).chr(0x41), chr(0xef).chr(0xbc).chr(0x8c)), ',', censor($tags));
				if(strexists($tags, ',')) {
					$tagarray = array_unique(explode(',', $tags));
				} else {
					$tags = str_replace(array(chr(0xa1).chr(0xa1), chr(0xa1).chr(0x40), chr(0xe3).chr(0x80).chr(0x80)), ' ', $tags);
					$tagarray = array_unique(explode(' ', $tags));
				}
				$threadtagsnew = array();
				$tagcount = 0;
				foreach($tagarray as $tagname) {
					$tagname = trim($tagname);
					if(preg_match('/^([\x7f-\xff_-]|\w|\s){3,20}$/', $tagname)) {
						$threadtagsnew[] = $tagname;
						if(!in_array($tagname, $threadtagary)) {
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
								$db->query("INSERT {$tablepre}threadtags (tagname, tid) VALUES ('$tagname', '$tid')", 'UNBUFFERED');
							}
						}
					}
					$tagcount++;
					if($tagcount > 4) {
						unset($tagarray);
						break;
					}
				}
				foreach($threadtagary as $tagname) {
					if(!in_array($tagname, $threadtagsnew)) {
						if($db->result_first("SELECT count(*) FROM {$tablepre}threadtags WHERE tagname='$tagname' AND tid!='$tid'")) {
							$db->query("UPDATE {$tablepre}tags SET total=total-1 WHERE tagname='$tagname'", 'UNBUFFERED');
						} else {
							$db->query("DELETE FROM {$tablepre}tags WHERE tagname='$tagname'", 'UNBUFFERED');
						}
						$db->query("DELETE FROM {$tablepre}threadtags WHERE tagname='$tagname' AND tid='$tid'", 'UNBUFFERED');
					}
				}
			}

		} else {

			if($subject == '' && $message == '') {
				showmessage('post_sm_isnull');
			}

		}

		if($editedby && ($timestamp - $orig['dateline']) > 60 && $adminid != 1) {
			include_once language('misc');

			$editor = $isanonymous && $isorigauthor ? $language['anonymous'] : $discuz_user;
			$edittime = gmdate($_DCACHE['settings']['dateformat'].' '.$_DCACHE['settings']['timeformat'], $timestamp + $timeoffset * 3600);
			eval("\$message = \"$language[post_edit]\".\$message;");
		}

		$bbcodeoff = checkbbcodes($message, !empty($bbcodeoff));
		$smileyoff = checksmilies($message, !empty($smileyoff));
		$tagoff = $isfirstpost ? !empty($tagoff) : 0;
		$htmlon = bindec(($tagstatus && $tagoff ? 1 : 0).($allowhtml && !empty($htmlon) ? 1 : 0));

		$tattachment = 0;

		if($special == 2 && !empty($_FILES['tradeattach']['tmp_name'][0])) {
			$_FILES['attach'] = array_merge_recursive($_FILES['attach'], $_FILES['tradeattach']);
			$deleteaid[] = $tradeaid;
		}

		$pattachment = ($allowpostattach && $attachments = attach_upload()) ? 1 : 0;
		$uattachment = ($allowpostattach && $uattachments = attach_upload('attachupdate')) ? 1 : 0;

		$query = $db->query("SELECT aid, readperm, price, attachment, description, thumb, remote FROM {$tablepre}attachments WHERE pid='$pid'");

		$attachdescnew = is_array($attachdescnew) ? $attachdescnew : array();
		$attachpermnew = is_array($attachpermnew) ? $attachpermnew : array();
		$attachpricenew = is_array($attachpricenew) ? $attachpricenew : array();

		while($attach = $db->fetch_array($query)) {

			$attachpermnew[$attach['aid']] = intval($attachpermnew[$attach['aid']]);
			$attachpermadd = $allowsetattachperm && $attach['readperm'] != $attachpermnew[$attach['aid']] ? ", readperm='{$attachpermnew[$attach['aid']]}'" : '' ;

			$attachpricenew[$attach['aid']] = intval($attachpricenew[$attach['aid']]);
			$attachpriceadd = $maxprice && $attach['price'] != $attachpricenew[$attach['aid']] && $attachpricenew[$attach['aid']] <= $maxprice ? ", price='{$attachpricenew[$attach['aid']]}'" : '' ;

			$attachdescnew[$attach['aid']] = cutstr(dhtmlspecialchars($attachdescnew[$attach['aid']]), 100);
			$attachdescadd = $attach['description'] != $attachdescnew[$attach['aid']] ? 1 : 0;

			if($uattachment || $attachpermadd || $attachdescadd || $attachpriceadd) {

				$paid = 'paid'.$attach['aid'];
				$attachfileadd = '';
				if($uattachment && isset($uattachments[$paid])) {
					dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
					$attachfileadd = ', dateline=\''.$timestamp.'\',
							filename=\''.$uattachments[$paid]['name'].'\',
							filetype=\''.$uattachments[$paid]['type'].'\',
							filesize=\''.$uattachments[$paid]['size'].'\',
							attachment=\''.$uattachments[$paid]['attachment'].'\',
							thumb=\''.$uattachments[$paid]['thumb'].'\',
							isimage=\''.$uattachments[$paid]['isimage'].'\',
							remote=\''.$uattachments[$paid]['remote'].'\',
							width=\''.$uattachments[$paid]['width'].'\'';
					unset($uattachments[$paid]);
				}

				$db->query("UPDATE {$tablepre}attachments SET description='{$attachdescnew[$attach['aid']]}' $attachpermadd $attachpriceadd $attachfileadd WHERE aid='$attach[aid]'");
			}
		}

		if($uattachment && !empty($uattachments)) {
			foreach($uattachments as $attach) {
				dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
			}
		}

		if(!empty($deleteaid) || $pattachment) {

			if(!empty($deleteaid) && is_array($deleteaid)) {

				$deleteaids = '\''.implode("','", $deleteaid).'\'';
				$query = $db->query("SELECT aid, attachment, thumb, remote FROM {$tablepre}attachments WHERE aid IN ($deleteaids) AND pid='$pid'");

				while($attach = $db->fetch_array($query)) {
					dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
				}

				$db->query("DELETE FROM {$tablepre}attachments WHERE aid IN ($deleteaids) AND pid='$pid'");
				updatecredits($orig['authorid'], $postattachcredits, -($db->affected_rows()));
			}

			if($pattachment) {
				$searcharray = $pregarray = $replacearray = array();
				foreach($attachments as $key => $attach) {
					$db->query("INSERT INTO {$tablepre}attachments (tid, pid, dateline, readperm, price, filename, description, filetype, filesize, attachment, downloads, isimage, uid, thumb, remote, width)
						VALUES ('$tid', '$pid', '$timestamp', '$attach[perm]', '$attach[price]', '$attach[name]', '$attach[description]', '$attach[type]', '$attach[size]', '$attach[attachment]', '0', '$attach[isimage]', '$attach[uid]', '$attach[thumb]', '$attach[remote]', '$attach[width]')");
					$searcharray[] = '[local]'.$localid[$key].'[/local]';
					$pregarray[] = '/\[localimg=(\d{1,3}),(\d{1,3})\]'.$localid[$key].'\[\/localimg\]/is';
					$insertid = $db->insert_id();
					$replacearray[] = '[attach]'.$insertid.'[/attach]';
				}
				if($special == 2 && !empty($_FILES['tradeattach']['tmp_name'][0])) {
					$tradeaid = $insertid;
				}
				$message = str_replace($searcharray, $replacearray, preg_replace($pregarray, $replacearray, $message));
				updatecredits($orig['authorid'], $postattachcredits, count($attachments));
			} else {
				$pattachment = $db->result_first("SELECT aid FROM {$tablepre}attachments WHERE pid='$pid' LIMIT 1") ? 1 : 0;
			}

			$tattachment = $db->result_first("SELECT count(*) FROM {$tablepre}posts p, {$tablepre}attachments a WHERE a.tid='$tid' AND a.isimage='1' AND a.pid=p.pid AND p.invisible='0' LIMIT 1") ? 2 : 
				($db->result_first("SELECT count(*) FROM {$tablepre}posts p, {$tablepre}attachments a WHERE a.tid='$tid' AND a.pid=p.pid AND p.invisible='0' LIMIT 1") ? 1 : 0);

			$db->query("UPDATE {$tablepre}threads SET attachment='$tattachment' WHERE tid='$tid'");

		}

		if($swfupload) {
			updateswfattach();
		}

		if($special == 2 && $allowposttrade) {

			$oldtypeid = $db->result_first("SELECT typeid FROM {$tablepre}trades WHERE pid='$pid'");
			$oldtypeid = isset($tradetypes[$oldtypeid]) ? $oldtypeid : 0;
			$tradetypeid = !$tradetypeid ? $oldtypeid : $tradetypeid;
			$optiondata = array();
			threadsort_checkoption($oldtypeid, 1);
			$optiondata = array();
			if($tradetypes && $typeoption && is_array($typeoption) && $checkoption) {
				$optiondata = threadsort_validator($typeoption);
			}

			if($tradetypes && $optiondata && is_array($optiondata)) {
				foreach($optiondata as $optionid => $value) {
					if($oldtypeid) {
						$db->query("UPDATE {$tablepre}tradeoptionvars SET value='$value' WHERE pid='$pid' AND optionid='$optionid'");
					} else {
						$db->query("INSERT INTO {$tablepre}tradeoptionvars (sortid, pid, optionid, value)
							VALUES ('$tradetypeid', '$pid', '$optionid', '$value')");
					}
				}
			}

			if(!$oldtypeid) {
				$db->query("UPDATE {$tablepre}trades SET typeid='$tradetypeid' WHERE pid='$pid'");
			}

			$query = $db->query("SELECT * FROM {$tablepre}trades WHERE tid='$tid' AND pid='$pid'");
			if($db->num_rows($query)) {
				$seller = dhtmlspecialchars(trim($seller));
				$item_name = dhtmlspecialchars(trim($item_name));
				$item_price = floatval($item_price);
				$item_locus = dhtmlspecialchars(trim($item_locus));
				$item_number = intval($item_number);
				$item_quality = intval($item_quality);
				$item_transport = intval($item_transport);
				$postage_mail = intval($postage_mail);
				$postage_express = intval(trim($postage_express));
				$postage_ems = intval($postage_ems);
				$item_type = intval($item_type);
				$item_costprice = floatval($item_costprice);

				if(!$item_name) {
					showmessage('trade_please_name');
				} elseif($maxtradeprice && ($mintradeprice > $item_price || $maxtradeprice < $item_price)) {
					showmessage('trade_price_between');
				} elseif(!$maxtradeprice && $mintradeprice > $item_price) {
					showmessage('trade_price_more_than');
				} elseif($item_number < 0) {
					showmessage('tread_please_number');
				}

				$expiration = $item_expiration ? @strtotime($item_expiration) : 0;
				$closed = $expiration > 0 && @strtotime($item_expiration) < $timestamp ? 1 : $closed;

				switch($transport) {
					case 'seller':$item_transport = 1;break;
					case 'buyer':$item_transport = 2;break;
					case 'virtual':$item_transport = 3;break;
					case 'logistics':$item_transport = 4;break;
				}
				$tradeaidadd = $special == 2 && !empty($_FILES['tradeattach']['tmp_name'][0]) ? "aid='$tradeaid'," : '';
				$db->query("UPDATE {$tablepre}trades SET $tradeaidadd account='$seller', subject='$item_name', price='$item_price', amount='$item_number', quality='$item_quality', locus='$item_locus',
					transport='$item_transport', ordinaryfee='$postage_mail', expressfee='$postage_express', emsfee='$postage_ems', itemtype='$item_type', expiration='$expiration', closed='$closed',
					costprice='$item_costprice' WHERE tid='$tid' AND pid='$pid'", 'UNBUFFERED');

				if(!empty($infloat)) {
					$viewpid = $db->result_first("SELECT pid FROM {$tablepre}posts WHERE tid='$tid' AND first='1' LIMIT 1");
					$redirecturl = "viewthread.php?tid=$tid&viewpid=$viewpid#pid$viewpid";
				} else {
					$redirecturl = "viewthread.php?do=tradeinfo&tid=$tid&pid=$pid";
				}
			}

		}

		$feed = array();
		if($special == 6 && $allowpostvideo) {
			$videoAccount = new VideoClient_VideoService($appid, $siteid, $sitekey);
			if($vid && $isfirstpost) {
				if(empty($vsubject) || empty($vtag)) {
					showmessage('video_required_invalid');
				}
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

				if($customaddfeed & 4) {
					$feed['icon'] = 'video';
					$feed['title_template'] = 'feed_thread_video_title';
					$feed['body_template'] = 'feed_thread_video_message';
					$feed['body_data'] = array(
						'subject'=> "<a href=\"{$boardurl}viewthread.php?tid=$tid&vid=$vid&autoplay=1\">$vsubject</a>",
						'play' => "<a href=\"{$boardurl}viewthread.php?tid=$tid&vid=$vid&autoplay=1\" class=\"playbutton\">Play</a>",
						'message' => cutstr(strip_tags(preg_replace(array("/\[hide=?\d*\].+?\[\/hide\]/is", "/\[.+?\]/is"), array('', ''), $message)), 150),
						'vlength'=> sprintf("%02d", intval($vlength / 60)).':'.sprintf("%02d", intval($vlength % 60)),
					);
					$feed['images'][] = array('url' => VideoClient_Util::getThumbUrl($vid, 'small'), 'link' => "{$boardurl}viewthread.php?tid=$tid");
					postfeed($feed);
				}
			}
			if($deletevideo && is_array($deletevideo)) {
				$toapivideo = array();
				foreach($deletevideo as $videoid) {
					$toapivideo[] = array('videoid' => $videoid);
				}
				$query = $db->query("DELETE FROM {$tablepre}videos WHERE vid IN (".implodeids($deletevideo).")");
				$videoAccount->removeMulti($toapivideo);
			}

			if(is_array($displayorder) && is_array($vtitle)) {
				foreach($displayorder as $vid => $val) {
					$db->query("UPDATE {$tablepre}videos SET displayorder='$displayorder[$vid]', vtitle='$vtitle[$vid]' WHERE vid='$vid'");
				}
			}
			$thread['author'] = addslashes($thread['author']);
			$db->query("UPDATE {$tablepre}threads SET lastposter='$thread[author]', lastpost='$timestamp' WHERE tid='$tid'", 'UNBUFFERED');
			$lastpost = "$thread[tid]\t".addslashes($thread['subject'])."\t$timestamp\t$thread[author]";
			$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost' WHERE fid='$fid'", 'UNBUFFERED');
			if($forum['type'] == 'sub') {
				$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost' WHERE fid='$forum[fup]'", 'UNBUFFERED');
			}

		}

		if($auditstatuson && $audit == 1) {
			updatepostcredits('+', $orig['authorid'], ($isfirstpost ? $postcredits : $replycredits));
			updatemodworks('MOD', 1);
			updatemodlog($tid, 'MOD');
		}

		$message = preg_replace('/\[attachimg\](\d+)\[\/attachimg\]/is', '[attach]\1[/attach]', $message);
		$db->query("UPDATE {$tablepre}posts SET message='$message', usesig='$usesig', htmlon='$htmlon', bbcodeoff='$bbcodeoff', parseurloff='$parseurloff',
			smileyoff='$smileyoff', subject='$subject' ".($pattachment ? ", attachment='1'" : '')." $anonymousadd ".($auditstatuson && $audit == 1 ? ",invisible='0'" : '')." WHERE pid='$pid'");

		$forum['lastpost'] = explode("\t", $forum['lastpost']);

		if($orig['dateline'] == $forum['lastpost'][2] && ($orig['author'] == $forum['lastpost'][3] || ($forum['lastpost'][3] == '' && $orig['anonymous']))) {
			$lastpost = "$tid\t".($isfirstpost ? $subject : addslashes($thread['subject']))."\t$orig[dateline]\t".($isanonymous ? '' : addslashes($orig['author']));
			$db->query("UPDATE {$tablepre}forums SET lastpost='$lastpost' WHERE fid='$fid'", 'UNBUFFERED');
		}

		if($thread['lastpost'] == $orig['dateline'] && ((!$orig['anonymous'] && $thread['lastposter'] == $orig['author']) || ($orig['anonymous'] && $thread['lastposter'] == '')) && $orig['anonymous'] != $isanonymous) {
			$db->query("UPDATE {$tablepre}threads SET lastposter='".($isanonymous ? '' : addslashes($orig['author']))."' WHERE tid='$tid'", 'UNBUFFERED');
		}

		if(!$isorigauthor) {
			updatemodworks('EDT', 1);
			require_once DISCUZ_ROOT.'./include/misc.func.php';
			modlog($thread, 'EDT');
		}

	} else {

		if($isfirstpost && $thread['replies'] > 0) {
			showmessage(($thread['special'] == 3 ? 'post_edit_reward_already_reply' : 'post_edit_thread_already_reply'), NULL, 'HALTED');
		}

		if($thread['special'] == 3) {
			if($thread['price'] < 0 && ($thread['dateline'] + 1 == $orig['dateline'])) {
				showmessage('post_edit_reward_nopermission', NULL, 'HALTED');
			}
		} elseif($thread['special'] == 6 && $isfirstpost && $videoopen) {
			videodelete($tid);
		}

		updatepostcredits('-', $orig['authorid'], ($isfirstpost ? $postcredits : $replycredits));

		if($thread['special'] == 3 && $isfirstpost) {
			$db->query("UPDATE {$tablepre}members SET extcredits$creditstransextra[2]=extcredits$creditstransextra[2]+$thread[price] WHERE uid='$orig[authorid]'", 'UNBUFFERED');
			$db->query("DELETE FROM {$tablepre}rewardlog WHERE tid='$tid'", 'UNBUFFERED');
		}

		$thread_attachment = $post_attachment = 0;
		$query = $db->query("SELECT pid, attachment, thumb, remote FROM {$tablepre}attachments WHERE tid='$tid'");
		while($attach = $db->fetch_array($query)) {
			if($attach['pid'] == $pid) {
				$post_attachment ++;
				dunlink($attach['attachment'], $attach['thumb'], $attach['remote']);
			} else {
				$thread_attachment = 1;
			}
		}

		if($post_attachment) {
			$db->query("DELETE FROM {$tablepre}attachments WHERE pid='$pid'", 'UNBUFFEREED');
			updatecredits($orig['authorid'], $postattachcredits, -($post_attachment));
		}

		$db->query("DELETE FROM {$tablepre}posts WHERE pid='$pid'");
		if($thread['special'] == 2) {
			$db->query("DELETE FROM {$tablepre}trades WHERE pid='$pid'");
		}

		if($isfirstpost) {
			$forumadd = 'threads=threads-\'1\', posts=posts-\'1\'';
			$tablearray = array('threadsmod','relatedthreads','threads','debates','debateposts','polloptions','polls','mythreads','typeoptionvars');
			foreach ($tablearray as $table) {
				$db->query("DELETE FROM {$tablepre}$table WHERE tid='$tid'", 'UNBUFFERED');
			}
			if($globalstick && in_array($thread['displayorder'], array(2, 3))) {
				require_once DISCUZ_ROOT.'./include/cache.func.php';
				updatecache('globalstick');
			}
		} else {
			$forumadd = 'posts=posts-\'1\'';
			$query = $db->query("SELECT author, dateline, anonymous FROM {$tablepre}posts WHERE tid='$tid' AND invisible='0' ORDER BY dateline DESC LIMIT 1");
			$lastpost = $db->fetch_array($query);
			$lastpost['author'] = !$lastpost['anonymous'] ? addslashes($lastpost['author']) : '';
			$db->query("UPDATE {$tablepre}threads SET replies=replies-'1', attachment='$thread_attachment', lastposter='$lastpost[author]', lastpost='$lastpost[dateline]' WHERE tid='$tid'", 'UNBUFFERED');
		}

		$forum['lastpost'] = explode("\t", $forum['lastpost']);
		if($orig['dateline'] == $forum['lastpost'][2] && ($orig['author'] == $forum['lastpost'][3] || ($forum['lastpost'][3] == '' && $orig['anonymous']))) {
			$lastthread = daddslashes($db->fetch_first("SELECT tid, subject, lastpost, lastposter FROM {$tablepre}threads
				WHERE fid='$fid' AND displayorder>='0' ORDER BY lastpost DESC LIMIT 1"), 1);
			$forumadd .= ", lastpost='$lastthread[tid]\t$lastthread[subject]\t$lastthread[lastpost]\t$lastthread[lastposter]'";
		}

		$db->query("UPDATE {$tablepre}forums SET $forumadd WHERE fid='$fid'", 'UNBUFFERED');

	}

	// debug: update thread caches ?
	if($forum['threadcaches']) {
		if($isfirstpost || $page == 1 || $thread['replies'] < $_DCACHE['pospperpage'] || !empty($delete)) {
			$forum['threadcaches'] && deletethreadcaches($tid);
		} else {
			if($db->result_first("SELECT COUNT(*) FROM {$tablepre}posts WHERE tid='$tid' AND pid<'$pid'") < $_DCACHE['settings']['postperpage']) {
				$forum['threadcaches'] && deletethreadcaches($tid);
			}
		}
	}

	if($auditstatuson) {
		if($audit == 1) {
			showmessage('auditstatuson_succeed', $redirecturl);
		} else {
			showmessage('audit_edit_succeed');
		}
	} else {
		if(!empty($delete) && $isfirstpost) {
			showmessage('post_edit_delete_succeed', "forumdisplay.php?fid=$fid");
		} elseif(!empty($delete)) {
			showmessage('post_edit_delete_succeed', "viewthread.php?tid=$tid&page=$page&extra=$extra".($vid && $isfirstpost ? "&vid=$vid" : ''));
		} else {
			showmessage('post_edit_succeed', $redirecturl);
		}
	}

}

?>