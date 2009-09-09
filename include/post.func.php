<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: post.func.php 17503 2009-01-05 02:21:45Z monkey $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function attach_upload($varname = 'attach', $swfupload = 0) {

	global $db, $tablepre, $extension, $typemaxsize, $allowsetattachperm, $attachperm, $maxprice, $attachprice, $attachdesc, $attachsave, $attachdir, $thumbstatus, $thumbwidth, $thumbheight,
		$maxattachsize, $maxsizeperday, $attachextensions, $watermarkstatus, $watermarktype, $watermarktrans, $watermarkquality, $watermarktext, $_FILES, $discuz_uid, $imageexists;

	$attachments = $attacharray = array();
	$imageexists = 0;

	static $safeext  = array('jpg', 'jpeg', 'gif', 'png', 'swf', 'bmp', 'txt', 'zip', 'rar', 'doc', 'mp3');
	static $imgext  = array('jpg', 'jpeg', 'gif', 'png', 'bmp');

	if(!$swfupload) {
		if(isset($_FILES[$varname]) && is_array($_FILES[$varname])) {
			foreach($_FILES[$varname] as $key => $var) {
				foreach($var as $id => $val) {
					$attachments[$id][$key] = $val;
				}
			}
		}
	} else {
		$attachments[0] = $_FILES[$varname];
	}

	if(empty($attachments)) {
		return FALSE;
	}

	foreach($attachments as $key => $attach) {

		$attach_saved = false;

		$attach['uid'] = $discuz_uid;
		if(!disuploadedfile($attach['tmp_name']) || !($attach['tmp_name'] != 'none' && $attach['tmp_name'] && $attach['name'])) {
			continue;
		}

		$filename = daddslashes($attach['name']);

		$attach['ext'] = strtolower(fileext($attach['name']));
		$extension = in_array($attach['ext'], $safeext) ? $attach['ext'] : 'attach';

		if(in_array($attach['ext'], $imgext)) {
			$attach['isimage'] = 1;
			$imageexists = 1;
		}else{
			$attach['isimage'] = 0;
		}

		$attach['thumb'] = 0;

		$attach['name'] = htmlspecialchars($attach['name'], ENT_QUOTES);
		if(strlen($attach['name']) > 90) {
			$attach['name'] = 'abbr_'.md5($attach['name']).'.'.$attach['ext'];
		}

		if($attachextensions && (!preg_match("/(^|\s|,)".preg_quote($attach['ext'], '/')."($|\s|,)/i", $attachextensions) || !$attach['ext'])) {
			upload_error('post_attachment_ext_notallowed', $attacharray);
		}

		if(empty($attach['size'])) {
			upload_error('post_attachment_size_invalid', $attacharray);
		}

		if($maxattachsize && $attach['size'] > $maxattachsize) {
			upload_error('post_attachment_toobig', $attacharray);
		}

		if($type = $db->fetch_first("SELECT maxsize FROM {$tablepre}attachtypes WHERE extension='".addslashes($attach['ext'])."'")) {
			if($type['maxsize'] == 0) {
				upload_error('post_attachment_ext_notallowed', $attacharray);
			} elseif($attach['size'] > $type['maxsize']) {
				require_once DISCUZ_ROOT.'./include/attachment.func.php';
				$typemaxsize = sizecount($type['maxsize']);
				upload_error('post_attachment_type_toobig', $attacharray);
			}
		}

		if($attach['size'] && $maxsizeperday) {
			if(!isset($todaysize)) {
				$todaysize = intval($db->result_first("SELECT SUM(filesize) FROM {$tablepre}attachments
					WHERE uid='$GLOBALS[discuz_uid]' AND dateline>'$GLOBALS[timestamp]'-86400"));
			}
			$todaysize += $attach['size'];
			if($todaysize >= $maxsizeperday) {
				$maxsizeperday = $maxsizeperday / 1048576 >= 1 ? round(($maxsizeperday / 1048576), 1).'M' : round(($maxsizeperday / 1024)).'K';
				upload_error('post_attachment_quota_exceed', $attacharray);
			}
		}

		if($attachsave) {
			if(!$swfupload) {
				switch($attachsave) {
					case 1: $attach_subdir = 'forumid_'.$GLOBALS['fid']; break;
					case 2: $attach_subdir = 'ext_'.$extension; break;
					case 3: $attach_subdir = 'month_'.date('ym'); break;
					case 4: $attach_subdir = 'day_'.date('ymd'); break;
				}
			} else {
				$attach_subdir = 'swfupload';
			}
			$attach_dir = $attachdir.'/'.$attach_subdir;
			if(!is_dir($attach_dir)) {
				@mkdir($attach_dir, 0777);
				@fclose(fopen($attach_dir.'/index.htm', 'w'));
			}
			$attach['attachment'] = $attach_subdir.'/';
		} else {
			$attach['attachment'] = '';
		}

		$attach['attachment'] .= preg_replace("/(php|phtml|php3|php4|jsp|exe|dll|asp|cer|asa|shtml|shtm|aspx|asax|cgi|fcgi|pl)(\.|$)/i", "_\\1\\2",
			date('ymdHi').substr(md5($filename.microtime().random(6)), 8, 16).'.'.$extension);
		$target = $attachdir.'/'.$attach['attachment'];

		if(@copy($attach['tmp_name'], $target) || (function_exists('move_uploaded_file') && @move_uploaded_file($attach['tmp_name'], $target))) {
			@unlink($attach['tmp_name']);
			$attach_saved = true;
		}

		if(!$attach_saved && @is_readable($attach['tmp_name'])) {
			@$fp = fopen($attach['tmp_name'], 'rb');
			@flock($fp, 2);
			@$attachedfile = fread($fp, $attach['size']);
			@fclose($fp);

			@$fp = fopen($target, 'wb');
			@flock($fp, 2);
			if(@fwrite($fp, $attachedfile)) {
				@unlink($attach['tmp_name']);
				$attach_saved = true;
			}
			@fclose($fp);
		}

		if($attach_saved) {

			@chmod($target, 0644);

			$width = $height = $type = 0;

			if($attach['isimage'] || $attach['ext'] == 'swf') {
				$imagesize = @getimagesize($target);
				list($width, $height, $type) = (array)$imagesize;
				$size = $width * $height;
				if($size > 16777216 || $size < 4 || empty($type) || ($attach['isimage'] && !in_array($type, array(1,2,3,6,13)))) {
					@unlink($target);
					upload_error('post_attachment_image_checkerror', $attacharray);
				}
			}

			if($attach['isimage'] && ($thumbstatus || $watermarkstatus)) {
				require_once DISCUZ_ROOT.'./include/image.class.php';

				$image = new Image($target, $attach);

				if($image->imagecreatefromfunc && $image->imagefunc) {
					$image->Thumb($thumbwidth, $thumbheight);
					!$swfupload && $image->Watermark();
					$attach = $image->attach;
				}
			}

			$attach['width'] = 0;
			if($attach['isimage'] || $attach['ext'] == 'swf') {
				$imagesize = @getimagesize($target);
				list($width) = (array)$imagesize;
				$attach['width'] = $width;
			}
			$attach['remote'] = !$swfupload ? ftpupload($target, $attach) : 0;
			$attach['perm'] = $allowsetattachperm ? intval($attachperm[$key]) : 0;
			$attach['description'] = cutstr(dhtmlspecialchars($attachdesc[$key]), 100);
			$attach['price'] = $maxprice ? (intval($attachprice[$key]) <= $maxprice ? intval($attachprice[$key]) : $maxprice) : 0;
			$attacharray[$key] = $attach;

		} else {

			upload_error('post_attachment_save_error', $attacharray);
		}
	}

	return !empty($attacharray) ? $attacharray : false;
}

function upload_error($message, $attacharray = array()) {
	if(!empty($attacharray)) {
		foreach($attacharray as $attach) {
			@unlink($GLOBALS['attachdir'].'/'.$attach['attachment']);
		}
	}
	showmessage($message);
}

function ftpupload($source, $attach) {
	global $authkey, $ftp;
	$ftp['pwd'] = isset($ftp['pwd']) ? $ftp['pwd'] : FALSE;
	$dest = $attach['attachment'];
	if($ftp['on'] && ((!$ftp['allowedexts'] && !$ftp['disallowedexts']) || ($ftp['allowedexts'] && in_array($attach['ext'], explode("\n", strtolower($ftp['allowedexts'])))) || ($ftp['disallowedexts'] && !in_array($attach['ext'], explode("\n", strtolower($ftp['disallowedexts']))))) && (!$ftp['minsize'] || $attach['size'] >= $ftp['minsize'] * 1024)) {
		require_once DISCUZ_ROOT.'./include/ftp.func.php';
		if(!$ftp['connid']) {
			if(!($ftp['connid'] = dftp_connect($ftp['host'], $ftp['username'], authcode($ftp['password'], 'DECODE', md5($authkey)), $ftp['attachdir'], $ftp['port'], $ftp['ssl']))) {
				if($ftp['mirror'] == 1) {
					ftpupload_error($source, $attach);
				} else {
					return 0;
				}
			}
			$ftp['pwd'] = FALSE;
		}
		$tmp = explode('/', $dest);
		if(count($tmp) > 1) {
			if(!$ftp['pwd'] && !dftp_chdir($ftp['connid'], $tmp[0])) {
				if(!dftp_mkdir($ftp['connid'], $tmp[0])) {
					errorlog('FTP', "Mkdir '$ftp[attachdir]/$tmp[0]' error.", 0);
					if($ftp['mirror'] == 1) {
						ftpupload_error($source, $attach);
					} else {
						return 0;
					}
				}
				if(!function_exists('ftp_chmod') || !dftp_chmod($ftp['connid'], 0777, $tmp[0])) {
					dftp_site($ftp['connid'], "'CHMOD 0777 $tmp[0]'");
				}
				if(!dftp_chdir($ftp['connid'], $tmp[0])) {
					errorlog('FTP', "Chdir '$ftp[attachdir]/$tmp[0]' error.", 0);
					if($ftp['mirror'] == 1) {
						ftpupload_error($source, $attach);
					} else {
						return 0;
					}
				}
				dftp_put($ftp['connid'], 'index.htm', $GLOBALS['attachdir'].'/index.htm', FTP_BINARY);
			}
			$dest = $tmp[1];
			$ftp['pwd'] = TRUE;
		}
		if(dftp_put($ftp['connid'], $dest, $source, FTP_BINARY)) {
			if($attach['thumb']) {
				if(dftp_put($ftp['connid'], $dest.'.thumb.jpg', $source.'.thumb.jpg', FTP_BINARY)) {
					if($ftp['mirror'] != 2) {
						@unlink($source);
						@unlink($source.'.thumb.jpg');
					}
					return 1;
				} else {
					dftp_delete($ftp['connid'], $dest);
				}
			} else {
				if($ftp['mirror'] != 2) {
					@unlink($source);
				}
				return 1;
			}
		}
		errorlog('FTP', "Upload '$source' error.", 0);
		$ftp['mirror'] == 1 && ftpupload_error($source, $attach);
	}
	return 0;
}

function ftpupload_error($source, $attach) {
	@unlink($source);
	if($attach['thumb']) {
		@unlink($source.'.thumb.jpg');
	}
	showmessage('post_attachment_remote_save_error');
}

function checkflood() {
	global $db, $tablepre, $disablepostctrl, $floodctrl, $maxpostsperhour, $discuz_uid, $timestamp, $lastpost, $forum;
	if(!$disablepostctrl && $discuz_uid) {
		$floodmsg = $floodctrl && ($timestamp - $floodctrl <= $lastpost) ? 'post_flood_ctrl' : '';

		if(empty($floodmsg) && $maxpostsperhour) {
			$query = $db->query("SELECT COUNT(*) from {$tablepre}posts WHERE authorid='$discuz_uid' AND dateline>$timestamp-3600");
			$floodmsg = ($userposts = $db->result($query, 0)) && ($userposts >= $maxpostsperhour) ? 'thread_maxpostsperhour_invalid' : '';
		}

		if(empty($floodmsg)) {
			return FALSE;
		} elseif(CURSCRIPT != 'wap') {
			showmessage($floodmsg);
		} else {
			wapmsg($floodmsg);
		}
	}
	return FALSE;
}

function checkpost($special = 0) {
	global $subject, $message, $disablepostctrl, $minpostsize, $maxpostsize;
	if(strlen($subject) > 80) {
		return 'post_subject_toolong';
	}
	if(!$disablepostctrl && !$special) {
		if($maxpostsize && strlen($message) > $maxpostsize) {
			return 'post_message_toolong';
		} elseif($minpostsize && strlen(preg_replace("/\[quote\].+?\[\/quote\]/is", '', $message)) < $minpostsize) {
			return 'post_message_tooshort';
		}
	}
	return FALSE;
}

function checkbbcodes($message, $bbcodeoff) {
	return !$bbcodeoff && !strpos($message, '[/') ? -1 : $bbcodeoff;
}

function checksmilies($message, $smileyoff) {
	global $_DCACHE;

	if($smileyoff) {
		return 1;
	} else {
		if(!empty($_DCACHE['smileycodes']) && is_array($_DCACHE['smileycodes'])) {
			$message = stripslashes($message);
			foreach($_DCACHE['smileycodes'] as $id => $code) {
				if(strpos($message, $code) !== FALSE) {
					return 0;
				}
			}
		}
		return -1;
	}
}

function updatepostcredits($operator, $uidarray, $creditsarray) {
	global $db, $tablepre, $discuz_uid, $timestamp, $creditnotice, $cookiecredits;

	$membersarray = $postsarray = array();
	$self = $creditnotice && $uidarray == $discuz_uid;
	foreach((is_array($uidarray) ? $uidarray : array($uidarray)) as $id) {
		$membersarray[intval(trim($id))]++;
	}
	foreach($membersarray as $uid => $posts) {
		$postsarray[$posts][] = $uid;
	}
	$lastpostadd = $self ? ", lastpost='$timestamp'" : '';
	$creditsadd1 = '';
	if(is_array($creditsarray)) {
		if($self && !isset($cookiecredits)) {
			$cookiecredits = !empty($_COOKIE['discuz_creditnotice']) ? explode('D', $_COOKIE['discuz_creditnotice']) : array_fill(0, 9, 0);
		}
		foreach($creditsarray as $id => $addcredits) {
			$creditsadd1 .= ", extcredits$id=extcredits$id$operator($addcredits)*\$posts";
			if($self) {
				eval("\$cookiecredits[$id] += $operator($addcredits)*\$posts;");
			}
		}
		if($self) {
			dsetcookie('discuz_creditnotice', implode('D', $cookiecredits).'D'.$discuz_uid, 43200, 0);
		}
	}
	foreach($postsarray as $posts => $uidarray) {
		$uids = implode(',', $uidarray);
		eval("\$creditsadd2 = \"$creditsadd1\";");
		$db->query("UPDATE {$tablepre}members SET posts=posts+('$operator$posts') $lastpostadd $creditsadd2 WHERE uid IN ($uids)", 'UNBUFFERED');
	}
}

function updateattachcredits($operator, $uidarray, $creditsarray) {
	global $db, $tablepre, $discuz_uid;
	$creditsadd1 = '';
	if(is_array($creditsarray)) {
		foreach($creditsarray as $id => $addcredits) {
			$creditsadd1[] = "extcredits$id=extcredits$id$operator$addcredits*\$attachs";
		}
	}
	if(is_array($creditsadd1)) {
		$creditsadd1 = implode(', ', $creditsadd1);
		foreach($uidarray as $uid => $attachs) {
			eval("\$creditsadd2 = \"$creditsadd1\";");
			$db->query("UPDATE {$tablepre}members SET $creditsadd2 WHERE uid = $uid", 'UNBUFFERED');
		}
	}
}

function updateforumcount($fid) {
	global $db, $tablepre, $lang;

	extract($db->fetch_first("SELECT COUNT(*) AS threadcount, SUM(t.replies)+COUNT(*) AS replycount
		FROM {$tablepre}threads t, {$tablepre}forums f
		WHERE f.fid='$fid' AND t.fid=f.fid AND t.displayorder>='0'"));

	$thread = $db->fetch_first("SELECT tid, subject, author, lastpost, lastposter FROM {$tablepre}threads
		WHERE fid='$fid' AND displayorder>='0' ORDER BY lastpost DESC LIMIT 1");

	$thread['subject'] = addslashes($thread['subject']);
	$thread['lastposter'] = $thread['author'] ? addslashes($thread['lastposter']) : $lang['anonymous'];

	$db->query("UPDATE {$tablepre}forums SET posts='$replycount', threads='$threadcount', lastpost='$thread[tid]\t$thread[subject]\t$thread[lastpost]\t$thread[lastposter]' WHERE fid='$fid'", 'UNBUFFERED');
}

function updatethreadcount($tid, $updateattach = 0) {
	global $db, $tablepre, $lang;

	$replycount = $db->result_first("SELECT COUNT(*) FROM {$tablepre}posts WHERE tid='$tid' AND invisible='0'") - 1;

	$lastpost = $db->fetch_first("SELECT author, anonymous, dateline FROM {$tablepre}posts WHERE tid='$tid' AND invisible='0' ORDER BY dateline DESC LIMIT 1");
	$lastpost['author'] = $lastpost['anonymous'] ? $lang['anonymous'] : addslashes($lastpost['author']);

	if($updateattach) {
		$query = $db->query("SELECT attachment FROM {$tablepre}posts WHERE tid='$tid' AND invisible='0' AND attachment>0 LIMIT 1");
		$attachadd = ', attachment=\''.($db->num_rows($query)).'\'';
	} else {
		$attachadd = '';
	}

	$db->query("UPDATE {$tablepre}threads SET replies='$replycount', lastposter='$lastpost[author]', lastpost='$lastpost[dateline]' $attachadd WHERE tid='$tid'", 'UNBUFFERED');
}

function updatemodlog($tids, $action, $expiration = 0, $iscron = 0) {
	global $db, $tablepre, $timestamp;

	$uid = empty($iscron) ? $GLOBALS['discuz_uid'] : 0;
	$username = empty($iscron) ? $GLOBALS['discuz_user'] : 0;
	$expiration = empty($expiration) ? 0 : intval($expiration);

	$data = $comma = '';
	foreach(explode(',', str_replace(array('\'', ' '), array('', ''), $tids)) as $tid) {
		if($tid) {
			$data .= "{$comma} ('$tid', '$uid', '$username', '$timestamp', '$action', '$expiration', '1')";
			$comma = ',';
		}
	}

	!empty($data) && $db->query("INSERT INTO {$tablepre}threadsmod (tid, uid, username, dateline, action, expiration, status) VALUES $data", 'UNBUFFERED');

}

function isopera() {
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if(strpos($useragent, 'opera') !== false) {
		preg_match('/opera(\/| )([0-9\.]+)/', $useragent, $regs);
		return $regs[2];
	}
	return FALSE;
}

function deletethreadcaches($tids) {
	global $cachethreadon;
	if(!$cachethreadon) {
		return FALSE;
	}
	include_once DISCUZ_ROOT.'./include/forum.func.php';
	if(!empty($tids)) {
		foreach(explode(',', $tids) as $tid) {
			$fileinfo = getcacheinfo($tid);
			@unlink($fileinfo['filename']);
		}
	}
	return TRUE;
}

function threadsort_checkoption($unchangeable = 1, $trade = 0) {
	global $selectsortid, $optionlist, $trade_create, $tradetypeid, $sortid, $_DTYPE, $checkoption, $forum, $action;

	if($trade) {
		$selectsortid = $tradetypeid ? intval($tradetypeid) : '';
	} else {
		$selectsortid = $sortid ? intval($sortid) : '';
	}
	@include_once DISCUZ_ROOT.'./forumdata/cache/threadsort_'.$selectsortid.'.php';

	$optionlist = $_DTYPE;

	foreach($_DTYPE as $optionid => $option) {
		$checkoption[$option['identifier']]['optionid'] = $optionid;
		$checkoption[$option['identifier']]['title'] = $option['title'];
		$checkoption[$option['identifier']]['type'] = $option['type'];
		$checkoption[$option['identifier']]['required'] = $option['required'] ? 1 : 0;
		$checkoption[$option['identifier']]['unchangeable'] = $action == 'edit' && $unchangeable && $option['unchangeable'] ? 1 : 0;
		$checkoption[$option['identifier']]['maxnum'] = $option['maxnum'] ? intval($option['maxnum']) : '';
		$checkoption[$option['identifier']]['minnum'] = $option['minnum'] ? intval($option['minnum']) : '';
		$checkoption[$option['identifier']]['maxlength'] = $option['maxlength'] ? intval($option['maxlength']) : '';
	}
}

function threadsort_optiondata() {
	global $db, $tablepre, $tid, $pid, $tradetype, $_DTYPE, $_DTYPEDESC, $optiondata, $optionlist, $thread;
	$optiondata = array();
	if(!$tradetype) {
		$id = $tid;
		$field = 'tid';
		$table = 'typeoptionvars';
	} else {
		$id = $pid;
		$field = 'pid';
		$table = 'tradeoptionvars';
	}
	if($id) {
		$query = $db->query("SELECT optionid, value FROM {$tablepre}$table WHERE $field='$id'");
		while($option = $db->fetch_array($query)) {
			$optiondata[$option['optionid']] = $option['value'];
		}

		foreach($_DTYPE as $optionid => $option) {
			$optionlist[$optionid]['unchangeable'] = $_DTYPE[$optionid]['unchangeable'] ? 'readonly' : '';
			if($_DTYPE[$optionid]['type'] == 'radio') {
				$optionlist[$optionid]['value'] = array($optiondata[$optionid] => 'checked="checked"');
			} elseif($_DTYPE[$optionid]['type'] == 'select') {
				$optionlist[$optionid]['value'] = array($optiondata[$optionid] => 'selected="selected"');
			} elseif($_DTYPE[$optionid]['type'] == 'checkbox') {
				foreach(explode("\t", $optiondata[$optionid]) as $value) {
					$optionlist[$optionid]['value'][$value] = array($value => 'checked="checked"');
				}
			} else {
				$optionlist[$optionid]['value'] = $optiondata[$optionid];
			}
			if(!isset($optiondata[$optionid])) {
				$db->query("INSERT INTO {$tablepre}$table (sortid, $field, optionid)
				VALUES ('$thread[sortid]', '$id', '$optionid')");
			}
		}
	}
}

function threadsort_validator($sortoption) {
	global $checkoption, $var, $selectsortid, $fid, $tid, $pid;
	$postaction = $tid && $pid ? "edit&tid=$tid&pid=$pid" : 'newthread';
	$optiondata = array();
	foreach($checkoption as $var => $option) {
		if($checkoption[$var]['required'] && !$sortoption[$var]) {
			showmessage('threadtype_required_invalid', "post.php?action=$postaction&fid=$fid&sortid=$selectsortid");
		} elseif($sortoption[$var] && ($checkoption[$var]['type'] == 'number' && !is_numeric($sortoption[$var]) || $checkoption[$var]['type'] == 'email' && !isemail($sortoption[$var]))){
			showmessage('threadtype_format_invalid', "post.php?action=$postaction&fid=$fid&sortid=$selectsortid");
		} elseif($sortoption[$var] && $checkoption[$var]['maxlength'] && strlen($typeoption[$var]) > $checkoption[$var]['maxlength']) {
			showmessage('threadtype_toolong_invalid', "post.php?action=$postaction&fid=$fid&sortid=$selectsortid");
		} elseif($sortoption[$var] && (($checkoption[$var]['maxnum'] && $sortoption[$var] >= $checkoption[$var]['maxnum']) || ($checkoption[$var]['minnum'] && $sortoption[$var] < $checkoption[$var]['minnum']))) {
			showmessage('threadtype_num_invalid', "post.php?action=$postaction&fid=$fid&sortid=$selectsortid");
		} elseif($sortoption[$var] && $checkoption[$var]['unchangeable'] && !($tid && $pid)) {
			showmessage('threadtype_unchangeable_invalid', "post.php?action=$postaction&fid=$fid&sortid=$selectsortid");
		}
		if($checkoption[$var]['type'] == 'checkbox') {
			$sortoption[$var] = $sortoption[$var] ? implode("\t", $sortoption[$var]) : '';
		} elseif($checkoption[$var]['type'] == 'url') {
			$sortoption[$var] = $sortoption[$var] ? (substr(strtolower($sortoption[$var]), 0, 4) == 'www.' ? 'http://'.$sortoption[$var] : $sortoption[$var]) : '';
		}

		$sortoption[$var] = dhtmlspecialchars(censor(trim($sortoption[$var])));
		$optiondata[$checkoption[$var]['optionid']] = $sortoption[$var];
	}
	return $optiondata;
}

function videodelete($ids, $writelog = FALSE) {
	global $db, $tablepre, $vsiteid, $vkey;
	$ids = implode("','", (array)$ids);
	$query = $db->query("SELECT t.tid, v.vid FROM {$tablepre}threads t LEFT JOIN {$tablepre}videos v ON t.tid=v.tid WHERE t.tid IN('$ids') AND t.special='6'");
	$data = $datas = array();
	while($data = $db->fetch_array($query)) {
		$datas[] = $data['vid'];
	}
	$ids = implode("','", (array)$datas);
	$vids = implode(",", (array)$datas);
	$db->query("DELETE FROM {$tablepre}videos WHERE tid IN ('$ids')");
}

function disuploadedfile($file) {
	return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
}

function postfeed($feed) {
	global $discuz_uid, $discuz_user;

	require_once DISCUZ_ROOT.'./templates/default/feed.lang.php';
	require_once DISCUZ_ROOT.'./uc_client/client.php';

	$feed['title_template'] = $feed['title_template'] ? $language[$feed['title_template']] : '';
	$feed['body_template'] = $feed['title_template'] ? $language[$feed['body_template']] : '';

	uc_feed_add($feed['icon'], $discuz_uid, $discuz_user, $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '', $feed['images']);
}

?>