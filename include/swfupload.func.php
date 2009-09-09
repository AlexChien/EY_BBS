<?php

function getswfattach($getcount = 1) {
	global $db, $tablepre, $discuz_uid, $dateformat, $timeformat, $timeoffset;

	require_once DISCUZ_ROOT.'./include/attachment.func.php';

	$swfattachs = array();
	if($getcount) {
		$swfattachs = $db->result_first("SELECT count(*) FROM {$tablepre}attachments WHERE tid='0' AND pid='0' AND uid='$discuz_uid' ORDER BY dateline");
	} else {
		$query = $db->query("SELECT aid, filename, description, isimage, thumb, attachment, dateline, filesize, width FROM {$tablepre}attachments WHERE tid='0' AND pid='0' AND uid='$discuz_uid' ORDER BY dateline");
		while($swfattach = $db->fetch_array($query)) {
			$swfattach['filenametitle'] = $swfattach['filename'];
			$swfattach['filename'] = cutstr($swfattach['filename'], 30);
			$swfattach['attachsize'] = sizecount($swfattach['filesize']);
			$swfattach['dateline'] = gmdate("$dateformat $timeformat", $swfattach['dateline'] + $timeoffset * 3600);
			$swfattach['filetype'] = attachtype(fileext($swfattach['attachment'])."\t".$swfattach['filetype']);
			$swfattachs[] = $swfattach;
		}
	}
	return $swfattachs;
}

function updateswfattach() {
	global $db, $tablepre, $attachsave, $attachdir, $discuz_uid, $postattachcredits, $tid, $pid, $swfattachnew, $swfattachdel, $allowsetattachperm, $maxprice, $updateswfattach, $watermarkstatus;

	$imageexists = 0;
	$swfattachnew = (array)$swfattachnew;
	$query = $db->query("SELECT * FROM {$tablepre}attachments WHERE tid='0' AND pid='0' AND uid='$discuz_uid'");
	if($db->num_rows($query) && $updateswfattach) {
		$swfattachcount = 0;
		$delaids = array();
		while($swfattach = $db->fetch_array($query)) {
			if(in_array($swfattach['aid'], $swfattachdel)) {
				dunlink($swfattach['attachment'], $swfattach['thumb']);
				$delaids[] = $swfattach['aid'];
				continue;
			}
			$extension = strtolower(fileext($swfattach['filename']));
			$attach_basename = basename($swfattach['attachment']);
			$attach_src = $attachdir.'/'.$swfattach['attachment'];
			if($attachsave) {
				switch($attachsave) {
					case 1: $attach_subdir = 'forumid_'.$GLOBALS['fid']; break;
					case 2: $attach_subdir = 'ext_'.$extension; break;
					case 3: $attach_subdir = 'month_'.date('ym'); break;
					case 4: $attach_subdir = 'day_'.date('ymd'); break;
				}
				$attach_descdir = $attachdir.'/'.$attach_subdir;
				$swfattachnew[$swfattach['aid']]['attachment'] = $attach_subdir.'/'.$attach_basename;
			} else {
				$attach_descdir = $attachdir;
				$swfattachnew[$swfattach['aid']]['attachment'] = $attach_basename;
			}
			$swfattachnew[$swfattach['aid']]['thumb'] = $swfattach['thumb'];
			$attach_desc = $attach_descdir.'/'.$attach_basename;

			if($swfattach['isimage'] && $watermarkstatus) {
				require_once DISCUZ_ROOT.'./include/image.class.php';

				$image = new Image($attach_src, $swfattach);

				if($image->imagecreatefromfunc && $image->imagefunc) {
					$image->Watermark();
					$swfattach = $image->attach;
				}
			}

			if(!is_dir($attach_descdir)) {
				@mkdir($attach_descdir, 0777);
				@fclose(fopen($attach_descdir.'/index.htm', 'w'));
			}
			if($swfattach['thumb'] == 1) {
				if(!@rename($attach_src.'.thumb.jpg', $attach_desc.'.thumb.jpg') && @copy($attach_src.'.thumb.jpg', $attach_desc.'.thumb.jpg')) {
					@unlink($attach_src.'.thumb.jpg');
				}
			}
			if(!@rename($attach_src, $attach_desc) && @copy($attach_src, $attach_desc)) {
				@unlink($attach_src);
			}
			if($swfattach['isimage']) {
				$imageexists = 1;
			}
			$attachnew = $swfattachnew[$swfattach['aid']];
			$attachnew['remote'] = ftpupload($attach_desc, $attachnew);
			$attachnew['perm'] = $allowsetattachperm ? $attachnew['perm'] : 0;
			$attachnew['description'] = cutstr(dhtmlspecialchars($attachnew['description']), 100);
			$attachnew['price'] = $maxprice ? (intval($attachnew['price']) <= $maxprice ? intval($attachnew['price']) : $maxprice) : 0;
			$db->query("UPDATE {$tablepre}attachments SET tid='$tid', pid='$pid', attachment='$attachnew[attachment]', description='$attachnew[description]', readperm='$attachnew[readperm]', price='$attachnew[price]', remote='$attachnew[remote]' WHERE aid='$swfattach[aid]'");
			$swfattachcount++;
		}
		if($delaids) {
			$db->query("DELETE FROM {$tablepre}attachments WHERE aid IN (".implodeids($delaids).")", 'UNBUFFERED');
		}
		$attachment = $imageexists ? 2 : 1;
		if($swfattachcount) {
			$db->query("UPDATE {$tablepre}threads SET attachment='$attachment' WHERE tid='$tid'", 'UNBUFFERED');
			$db->query("UPDATE {$tablepre}posts SET attachment='$attachment' WHERE pid='$pid'", 'UNBUFFERED');
			updatecredits($discuz_uid, $postattachcredits, $swfattachcount);
		}
	}
}