<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: uchome.php 17407 2008-12-18 03:25:16Z liuqiang $
*/

@chdir('../');

require './include/common.inc.php';

if(!$ucappopen['UCHOME']) {
	showmessage('uchome_not_installed');
}

if($action == 'getalbums' && $discuz_uid) {

	$albums = @unserialize(dfopen("$uchomeurl/api/discuz.php?ac=album&uid=$discuz_uid"));

	if($albums && is_array($albums)) {
		$str = '<ul>';
		foreach($albums as $album) {
			$str .= "<li><a href=\"javascript:;\" id=\"uch_album_$album[albumid]\" onclick=\"$('insertimage_www_div').style.display='none';$('insertimage_album_div').style.display='';ajaxget('api/uchome.php?action=getphotoes&inajax=1&aid=$album[albumid]&photonum=$album[picnum]', 'uch_photoes', 'uch_photoes');this.style.fontWeight='bold';if(prealbum){prealbum.style.fontWeight='normal'};prealbum=this;$('uch_createalbum').style.display='';hideMenu(1);\">$album[albumname]</a></li>";
		}
		$str .= '</ul>';
		showmessage($str);
	} else {
		showmessage('NOALBUM');
	}


} elseif($action == 'getphotoes' && $discuz_uid && $aid) {

	$page = max(1, intval($page));
	$perpage = 8;
	$start = ($page - 1) * $perpage;
	$aid = intval($aid);
	$photonum = intval($photonum);
	$photoes = @unserialize(dfopen("$uchomeurl/api/discuz.php?ac=album&uid=$discuz_uid&start=$start&count=$photonum&perpage=$perpage&aid=$aid"));

	if($photoes && is_array($photoes)) {
		$i = 0;
		$str = '<table cellspacing="2" cellpadding="2"><tr>';
		foreach($photoes as $photo) {
			if($i++ == $perpage) {
				break;
			}
			$picurl = substr(strtolower($photo['bigpic']), 0, 7) == 'http://' ? '' : $uchomeurl.'/';
			$str .= '<td><img src="'.$picurl.$photo['pic'].'" title="'.$photo['filename'].'" onclick="wysiwyg ? insertText(\'<img src='.$picurl.$photo[bigpic].' border=0 /> \', false) : insertText(\'[img]'.$picurl.$photo[bigpic].'[/img]\');hideMenu();" onload="thumbImg(this, 1)" _width="80" _height="80" style="cursor: pointer;"></td>'.
			($i % 4 == 0 && isset($photoes[$i]) ? '</tr><tr>' : '');
		}
		$str .= '</tr></table>'.multi($photonum, $perpage, $start / $perpage + 1, "api/uchome.php?action=getphotoes&aid=$aid&photonum=$photonum");
		showmessage($str);
	} else {
		showmessage('NOPHOTO');
	}

}