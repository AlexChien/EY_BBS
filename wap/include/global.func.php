<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: global.func.php 16718 2008-11-17 03:48:41Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

function wapheader($title) {
	global $action, $_SERVER;
	header("Content-type: text/vnd.wap.wml; charset=utf-8");
	/*
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	*/
	echo "<?xml version=\"1.0\"?>\n".
		"<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">\n".
		"<wml>\n".
		"<head>\n".
		"<meta http-equiv=\"cache-control\" content=\"max-age=180,private\" />\n".
		"</head>\n".
		"<card id=\"discuz_wml\" title=\"$title\">\n";
		// newcontext=\"true\"
}

function wapfooter() {
	global $discuz_uid, $discuz_user, $lang, $action, $settings, $timestamp, $timeoffset, $wapdateformat, $timeformat;
	echo 	"<p>".gmdate("$wapdateformat $timeformat", $timestamp + ($timeoffset * 3600))."<br />".
		($action != 'home' ? "<anchor title=\"confirm\"><prev/>$lang[return]</anchor> <a href=\"index.php\">$lang[home_page]</a><br />" : '').
		($discuz_uid ? "<a href=\"index.php?action=login&amp;logout=yes&amp;formhash=".FORMHASH."\">$discuz_user:$lang[logout]</a>" : "<a href=\"index.php?action=login\">$lang[login]</a> <a href=\"index.php?action=register\">$lang[register]</a>")."<br /><br />\n".
		"<small>Powered by Discuz!</small></p>\n".
		//"<do type=\"prev\" label=\"$lang[return]\"><exit /></do>\n".
		"</card>\n".
		"</wml>";

	updatesession();
	wmloutput();
}

function wapmsg($message, $forward = array()) {
	extract($GLOBALS, EXTR_SKIP);
	if(isset($lang[$message])) {
		eval("\$message = \"".$lang[$message]."\";");
	}
	echo "<p>$message".
		($forward ? "<br /><a href=\"$forward[link]\">".(isset($lang[$forward['title']]) ? $lang[$forward['title']] : $forward['title'])."</a>" : '').
		"</p>\n";

	wapfooter();
	exit();
}

function wapmulti($num, $perpage, $curpage, $mpurl) {
	global $lang;
	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&amp;' : '?';
	if($num > $perpage) {
		$page = 3;
		$offset = 2;

		$realpages = @ceil($num / $perpage);
		$pages = $realpages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="'.$mpurl.'page=1">'.$lang['home_page'].'</a>' : '').
			($curpage > 1 ? ' <a href="'.$mpurl.'page='.($curpage - 1).'">'.$lang['last_page'].'</a>' : '');

		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? ' '.$i : ' <a href="'.$mpurl.'page='.$i.'">'.$i.'</a>';
		}

		$multipage .= ($curpage < $pages ? ' <a href="'.$mpurl.'page='.($curpage + 1).'">'.$lang['next_page'].'</a>' : '').
			($to < $pages ? ' <a href="'.$mpurl.'page='.$pages.'">'.$lang['end_page'].'</a>' : '');

		$multipage .= $realpages > $page ?
			'<br />'.$curpage.'/'.$realpages.$lang['page'].'<input type="text" name="page" size="2" emptyok="true" /> '.
			'<anchor title="submit">'.$lang['turn_page'].'<go method="post" href="'.$mpurl.'">'.
			'<postfield name="page" value="$(page)" />'.
			'</go></anchor>' : '';

	}
	return $multipage;
}

function wapcutstr($string, &$length) {
	$strcut = '';
	if(strlen($string) > $length) {
		for($i = 0; $i < $length - 3; $i++) {
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
		$length = $i;
		return $strcut.' ..';
	} else {
		return $string;
	}
}

function wapcode($string) {
	global $lang;
	$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
	$string = preg_replace("/\[hide\](.+?)\[\/hide\]/is", $lang['post_hide_reply_hidden'], $string);
	$string = preg_replace("/\[hide=(\d+)\]\s*(.+?)\s*\[\/hide\]/ies", $lang['post_hide_reply_hidden'], $string);
	for($i = 0; $i < 5; $i++) {
		$string = preg_replace("/\[(\w+)[^\]]*?\](.*?)\[\/\\1\]/is", "\\2", $string);
	}
	return  $string;
}

function wmloutput() {
	global $sid, $charset, $wapcharset;
	static $chs;
	$content = preg_replace("/\<a(\s*[^\>]+\s*)href\=([\"|\']?)([^\"\'\s]+)/ies", "transsid('\\3','<a\\1href=\\2',1)", ob_get_contents());
	ob_end_clean();

	if($charset != 'utf-8') {

		$target = $wapcharset == 1 ? 'UTF-8' : 'UNICODE';

		if(empty($chs)) {
			$chs = new Chinese($charset, $target);
		} else {
			$chs->config['SourceLang'] = $chs->_lang($charset);
			$chs->config['TargetLang'] = $target;
		}

		echo ($wapcharset == 1 ? $chs->Convert($content) : str_replace(array('&#x;', '&#x0;'), array('??', ''), $chs->Convert($content)));

	} else {
		echo $content;
	}
}

function wapconvert($str) {
	static $chs;
	if($str != '' && !is_numeric($str) && $GLOBALS['charset'] != 'utf-8') {
		
		$chs = empty($chs) ? new Chinese('UTF-8', $GLOBALS['charset']) : $chs;		
	
		if(is_array($str)) {
			foreach($str as $key => $val) {
				$str[$key] = wapconvert($val);
			}
		} else {
			$str = addslashes($chs->Convert(stripslashes($str)));
		}

	}
	return $str;
}

?>