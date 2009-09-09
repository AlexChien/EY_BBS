<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: global.func.php 17538 2009-01-20 07:39:49Z liuqiang $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

@set_time_limit(0);

function istpldir($dir) {
	return is_dir(DISCUZ_ROOT.'./'.$dir) && !in_array(substr($dir, -1, 1), array('/', '\\')) &&
		 strpos(realpath(DISCUZ_ROOT.'./'.$dir), realpath(DISCUZ_ROOT.'./templates')) === 0;
}

function isplugindir($dir) {
	return !$dir || (!preg_match("/(\.\.|[\\\\]+$)/", $dir) && substr($dir, -1) =='/');
}

function ispluginkey($key) {
	return preg_match("/^[a-z]+[a-z0-9_]*$/i", $key);
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function filemtimesort($a, $b) {
	if($a['filemtime'] == $b['filemtime']) {
		return 0;
	}
	return ($a['filemtime'] > $b['filemtime']) ? 1 : -1;
}

function checkpermission($action, $break = 1) {
	if(!isset($GLOBALS['admincp'])) {
		cpmsg('action_access_noexists', '', 'error');
	} elseif($break && !$GLOBALS['admincp'][$action]) {
		cpmsg('action_noaccess_config', '', 'error');
	} else {
		return $GLOBALS['admincp'][$action];
	}
}

function bbsinformation() {

	global $db, $timestamp, $tablepre, $charset, $bbname, $_SERVER, $siteuniqueid, $save_mastermobile, $msn;
	$update = array('uniqueid' => $siteuniqueid, 'version' => DISCUZ_VERSION, 'release' => DISCUZ_RELEASE, 'php' => PHP_VERSION, 'mysql' => $db->version(), 'charset' => $charset, 'bbname' => $bbname, 'mastermobile' => $save_mastermobile);

	$updatetime = @filemtime(DISCUZ_ROOT.'./forumdata/updatetime.lock');
	if(empty($updatetime) || ($timestamp - $updatetime > 3600 * 4)) {
		@touch(DISCUZ_ROOT.'./forumdata/updatetime.lock');
		$update['members'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}members");
		$update['threads'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}threads");
		$update['posts'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}posts");
		$query = $db->query("SELECT special, count(*) AS spcount FROM {$tablepre}threads GROUP BY special");
		while($thread = $db->fetch_array($query)) {
			$thread['special'] = intval($thread['special']);
			$update['spt_'.$thread['special']] = $thread['spcount'];
		}
		if($msn['on'] && $msn['domain']) {
			$update['msn_domain'] = $msn['domain'];
		}
	}

	$data = '';
	foreach($update as $key => $value) {
		$data .= $key.'='.rawurlencode($value).'&';
	}

	return 'update='.rawurlencode(base64_encode($data)).'&md5hash='.substr(md5($_SERVER['HTTP_USER_AGENT'].implode('', $update).$timestamp), 8, 8).'&timestamp='.$timestamp;
}

function isfounder($user = '') {
	$user = empty($user) ? array('uid' => $GLOBALS['discuz_uid'], 'adminid' => $GLOBALS['adminid'], 'username' => $GLOBALS['discuz_userss']) : $user;
	$founders = str_replace(' ', '', $GLOBALS['forumfounders']);
	if($user['adminid'] <> 1) {
		return FALSE;
	} elseif(empty($founders)) {
		return TRUE;
	} elseif(strexists(",$founders,", ",$user[uid],")) {
		return TRUE;
	} elseif(!is_numeric($user['username']) && strexists(",$founders,", ",$user[username],")) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function lang($name, $force = true) {
	global $lang;
	return isset($lang[$name]) ? $lang[$name] : ($force ? $name : '');
}

function admincustom($title, $url, $sort = 0) {
	global $db, $tablepre, $discuz_uid, $timestamp, $BASESCRIPT;
	$url = $BASESCRIPT.'?'.$url;
	$id = $db->result_first("SELECT id FROM {$tablepre}admincustom WHERE uid='$discuz_uid' AND sort='$sort' AND url='$url'");
	if($id) {
		$db->query("UPDATE {$tablepre}admincustom SET title='$title', clicks=clicks+1, dateline='$timestamp' WHERE id='$id'");
	} else {
		$db->query("INSERT INTO {$tablepre}admincustom (title, url, sort, uid, dateline) VALUES ('$title', '$url', '$sort', '$discuz_uid', '$timestamp')");
	}
}

function cpurl($type = 'parameter', $filters = array('sid', 'frames')) {
	parse_str($_SERVER['QUERY_STRING'], $getarray);
	$extra = $and = '';
	foreach($getarray as $key => $value) {
		if(!in_array($key, $filters)) {
			@$extra .= $and.$key.($type == 'parameter' ? '%3D' : '=').rawurlencode($value);
			$and = $type == 'parameter' ? '%26' : '&';
		}
	}
	return $extra;
}


function showheader($key, $url) {
	echo '<li><em><a href="javascript:;" id="header_'.$key.'" hidefocus="true" onclick="toggleMenu(\''.$key.'\', \''.$url.'\');">'.lang('header_'.$key).'</a></em></li>';
}

function shownav($header = '', $menu = '', $nav = '') {
	global $action, $operation, $BASESCRIPT;

	$title = 'cplog_'.$action.($operation ? '_'.$operation : '');
	if(in_array($action, array('home', 'custommenu'))) {
		$customtitle = '';
	} elseif(lang($title, false)) {
		$customtitle = $title;
	} else {
		$customtitle = rawurlencode($nav ? $nav : ($menu ? $menu : ''));
	}

	echo '<script type="text/JavaScript">if(parent.$(\'admincpnav\')) parent.$(\'admincpnav\').innerHTML=\''.lang('nav_'.($header ? $header : 'index')).
		($menu ? '&nbsp;&raquo;&nbsp;'.lang($menu) : '').
		($nav ? '&nbsp;&raquo;&nbsp;'.lang($nav) : '').'\';'.
		'if(parent.$(\'add2custom\')) parent.$(\'add2custom\').innerHTML='.($customtitle ? '\'<a href="'.$BASESCRIPT.'?action=misc&operation=custommenu&do=add&title='.$customtitle.'&url='.cpurl().'" target="main"><img src="images/admincp/btn_add2menu.gif" title="'.lang('custommenu_add').'" width="19" height="18" /></a>\';' : '\'\'').
	'</script>';
}

function showmenu($key, $menus) {
	global $BASESCRIPT;
	echo '<ul id="menu_'.$key.'" style="display: none">';
	if(is_array($menus)) {
		foreach($menus as $menu) {
			if($menu[0] && $menu[1]) {
				echo '<li><a href="'.(substr($menu[1], 0, 4) == 'http' ? $menu[1] : $BASESCRIPT.'?action='.$menu[1]).'" hidefocus="true" target="'.($menu[2] ? $menu[2] : 'main').'"'.($menu[3] ? $menu[3] : '').'>'.lang($menu[0]).'</a></li>';
			}
		}
	}
	echo '</ul>';
}

function cpmsg($message, $url = '', $type = '', $extra = '', $halt = TRUE) {
	extract($GLOBALS, EXTR_SKIP);
	include language('admincp.msg');
	eval("\$message = \"".(isset($msglang[$message]) ? $msglang[$message] : $message)."\";");
	switch($type) {
		case 'succeed': $classname = 'infotitle2';break;
		case 'error': $classname = 'infotitle3';break;
		case 'loading': $classname = 'infotitle1';break;
		default: $classname = 'marginbot normal';break;

	}
	$message = "<h4 class=\"$classname\">$message</h4>";

	if($type == 'form') {
		$message = "<form method=\"post\" action=\"$url\"><input type=\"hidden\" name=\"formhash\" value=\"".FORMHASH."\">".
			"<br />$message$extra<br />".
			"<p class=\"margintop\"><input type=\"submit\" class=\"btn\" name=\"confirmed\" value=\"$lang[ok]\"> &nbsp; \n".
			"<input type=\"button\" class=\"btn\" value=\"$lang[cancel]\" onClick=\"history.go(-1);\"></p></form><br />";
	} elseif($type == 'loadingform') {
		$message = "<form method=\"post\" action=\"$url\" id=\"loadingform\"><input type=\"hidden\" name=\"formhash\" value=\"".FORMHASH."\"><br />$message$extra<img src=\"images/admincp/ajax_loader.gif\" class=\"marginbot\" /><br />".
			'<p class="marginbot"><a href="###" onclick="$(\'loadingform\').submit();" class="lightlink">'.lang('message_redirect').'</a></p></form><br /><script type="text/JavaScript">setTimeout("$(\'loadingform\').submit();", 2000);</script>';
	} else {
		$message .= $extra.($type == 'loading' ? '<img src="images/admincp/ajax_loader.gif" class="marginbot" />' : '');
		if($url) {
			if($type == 'button') {
				$message = "<br />$message<br /><p class=\"margintop\"><input type=\"submit\" class=\"btn\" name=\"submit\" value=\"$lang[start]\" onclick=\"location.href='$url'\" />";
			} else {
				$message .= '<p class="marginbot"><a href="'.$url.'" class="lightlink">'.lang('message_redirect').'</a></p>';
				$url = transsid($url);
				$message .= "<script type=\"text/JavaScript\">setTimeout(\"redirect('$url');\", 2000);</script>";
			}
		} elseif(strpos($message, $lang['return'])) {
			$message .= '<p class="marginbot"><a href="javascript:history.go(-1);" class="lightlink">'.lang('message_return').'</a></p>';
		}
	}

	if($halt) {
		echo '<h3>'.lang('discuz_message').'</h3><div class="infobox">'.$message.'</div>';
		cpfooter();
		dexit();
	} else {
		echo '<div class="infobox">'.$message.'</div>';
	}
}

function cpheader() {
	global  $charset, $frame, $BASESCRIPT;

	if(!defined('DISCUZ_CP_HEADER_OUTPUT')) {
		define('DISCUZ_CP_HEADER_OUTPUT', true);
	} else {
		return true;
	}

	$IMGDIR = IMGDIR;
	$STYLEID = STYLEID;
	$VERHASH = VERHASH;
	echo <<< EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$charset">
<link href="./images/admincp/admincp.css" rel="stylesheet" type="text/css" />
</head>
<body>
<script type="text/JavaScript">
	function redirect(url) {
		window.location.replace(url);
	}
	var admincpfilename = '$BASESCRIPT';
	if('$frame' != 'no' && !parent.document.getElementById('leftmenu')) {
		redirect(admincpfilename + '?frames=yes&' + document.URL.substr(document.URL.indexOf(admincpfilename) + 12));
	}
	var IMGDIR = '$IMGDIR';
	var STYLEID = '$STYLEID'
	var VERHASH = '$VERHASH';
	var IN_ADMINCP = true;
</script>
<script src="include/js/common.js" type="text/javascript"></script>
<script type="text/JavaScript">

	function checkAll(type, form, value, checkall, changestyle) {
		var checkall = checkall ? checkall : 'chkall';
		for(var i = 0; i < form.elements.length; i++) {
			var e = form.elements[i];
			if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
				e.checked = true;
			} else if(type == 'value' && e.type == 'checkbox' && e.getAttribute('chkvalue') == value) {
				e.checked = form.elements[checkall].checked;
			} else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
				e.checked = form.elements[checkall].checked;
				if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
					e.parentNode.className = e.checked ? 'checked' : '';
				}
			}
		}
	}

	function altStyle(obj) {
		function altStyleClear(obj) {
			var input, lis, i;
			lis = obj.parentNode.getElementsByTagName('li');
			for(i=0; i < lis.length; i++){
				lis[i].className = '';
			}
		}

		var input, lis, i, cc, o;
		cc = 0;
		lis = obj.getElementsByTagName('li');
		for(i=0; i < lis.length; i++){
			lis[i].onclick = function(e) {
				o = is_ie ? event.srcElement.tagName : e.target.tagName;
				if(cc) {
					return;
				}
				cc = 1;
				input = this.getElementsByTagName('input')[0];
				if(input.getAttribute('type') == 'checkbox' || input.getAttribute('type') == 'radio') {
					if(input.getAttribute('type') == 'radio') {
						altStyleClear(this);
					}

					if(is_ie || o != 'INPUT' && input.onclick) {
						input.click();
					}
					if(this.className != 'checked') {
						this.className = 'checked';
						input.checked = true;
					} else {
						this.className = '';
						input.checked = false;
					}
				}
			}
			lis[i].onmouseup = function(e) {
				cc = 0;
			}
		}
	}

	var addrowdirect = 0;
	function addrow(obj, type) {
		var table = obj.parentNode.parentNode.parentNode.parentNode;
		if(!addrowdirect) {
			var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex);
		} else {
			var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex + 1);
		}
		var typedata = rowtypedata[type];
		for(var i = 0; i <= typedata.length - 1; i++) {
			var cell = row.insertCell(i);
			cell.colSpan = typedata[i][0];
			var tmp = typedata[i][1];
			if(typedata[i][2]) {
				cell.className = typedata[i][2];
			}
			tmp = tmp.replace(/\{(\d+)\}/g, function($1, $2) {return addrow.arguments[parseInt($2) + 1];});
			cell.innerHTML = tmp;
		}
		addrowdirect = 0;
	}

	function dropmenu(obj){
		obj.className = obj.className == 'hasdropmenu' ? 'current' : 'hasdropmenu';
		$(obj.id + 'child').style.display = $(obj.id + 'child').style.display == 'none' ? '' : 'none';
	}

	function textareasize(obj) {
		if(obj.scrollHeight > 70) {
			obj.style.height = obj.scrollHeight + 'px';
		}
	}

	if('$frame' != 'no') _attachEvent(document.documentElement, 'keydown', parent.resetEscAndF5);

	function display_detail(objname) {
		obj = $(objname);
		if(obj.style.display == 'none') {
			obj.style.display = '';
		} else {
			obj.style.display = 'none'
		}
	}
</script>
<div id="append_parent"></div>
<div class="container" id="cpcontainer">
EOT;

}

function showsubmenu($title, $menus = array()) {
	global $BASESCRIPT;
	if(empty($menus)) {
		$s = '<div class="itemtitle"><h3>'.lang($title).'</h3></div>';
	} elseif(is_array($menus)) {
		$s = '<div class="itemtitle"><h3>'.lang($title).'</h3>';
		if(is_array($menus)) {
			$s .= '<ul class="tab1">';
			foreach($menus as $k => $menu) {
				if(is_array($menu[0])) {
					$s .= '<li id="addjs'.$k.'" class="'.($menu[2] ? ' current' : 'hasdropmenu').'" onclick="dropmenu(this);"><a href="#"><span>'.lang($menu[0]['menu']).'<em>&nbsp;&nbsp;</em></span></a><div id="addjs'.$k.'child" class="dropmenu" style="display:none;">';
					if(is_array($menu[0]['submenu'])) {
						foreach($menu[0]['submenu'] as $submenu) {
							$s .= '<a href="'.$BASESCRIPT.'?action='.$submenu[1].'">'.lang($submenu[0]).'</a>';
						}
					}
					$s .= '</div></li>';
				} else {
					$s .= '<li'.($menu[2] ? ' class="current"' : '').'><a href="'.$BASESCRIPT.'?action='.$menu[1].'"'.($menu[3] ? ' target="_blank"' : '').'><span>'.lang($menu[0]).'</span></a></li>';
				}
			}
			$s .= '</ul>';
		}
		$s .= '</div>';
	}
	echo $s;
}

function showsubmenusteps($title, $menus = array()) {
	$s = '<div class="itemtitle">'.($title ? '<h3>'.lang($title).'</h3>' : '');
	if(is_array($menus)) {
		$s .= '<ul class="stepstat">';
			$i = 0;
		foreach($menus as $menu) {
			$i++;
			$s .= '<li'.($menu[1] ? ' class="current"' : '').' id="step'.$i.'">'.$i.'.'.lang($menu[0]).'</li>';
		}
		$s .= '</ul>';
	}
	$s .= '</div>';
	echo $s;
}

function showsubmenuanchors($title, $menus = array()) {
	if(!$title || !$menus || !is_array($menus)) {
		return;
	}
	echo <<<EOT
<script type="text/JavaScript">
	function showanchor(obj) {
		var navs = $('submenu').getElementsByTagName('li');
		for(var i = 0; i < navs.length; i++) {
			if(navs[i].id != obj.id) {
				navs[i].className = '';
				$(navs[i].id.substr(4)).style.display = 'none';
				if($(navs[i].id.substr(4) + '_tips')) $(navs[i].id.substr(4) + '_tips').style.display = 'none';
			}
		}
		obj.className = 'current';
		$(obj.id.substr(4)).style.display = '';
		if($(obj.id.substr(4) + '_tips')) $(obj.id.substr(4) + '_tips').style.display = '';
		if($(obj.id.substr(4) + 'form')) {
			$(obj.id.substr(4) + 'form').anchor.value = obj.id.substr(4);
		} else if($('cpform')) {
			$('cpform').anchor.value = obj.id.substr(4);
		}
	}
</script>
EOT;
	$s = '<div class="itemtitle"><h3>'.lang($title).'</h3>';
	$s .= '<ul class="tab1" id="submenu">';
	foreach($menus as $menu) {
		if($menu && is_array($menu)) {
			$s .= '<li id="nav_'.$menu[1].'" onclick="showanchor(this)"'.($menu[2] ? ' class="current"' : '').'><a href="#"><span>'.lang($menu[0]).'</span></a></li>';
		}
	}
	$s .= '</ul>';
	$s .= '</div>';
	echo $s;
}

function showtips($tips, $id = 'tips', $display = TRUE) {
	extract($GLOBALS, EXTR_SKIP);
	if(lang($tips, false)) {
		eval('$tips = "'.str_replace('"', '\\"', $lang[$tips]).'";');
	}
	$tmp = explode('</li><li>', substr($tips, 4, -5));
	if(count($tmp) > 4) {
		$tips = '<li>'.$tmp[0].'</li><li>'.$tmp[1].'</li><li id="'.$id.'_more" style="border: none; background: none; margin-bottom: 6px;"><a href="###" onclick="var tiplis = $(\''.$id.'lis\').getElementsByTagName(\'li\');for(var i = 0; i < tiplis.length; i++){tiplis[i].style.display=\'\'}$(\''.$id.'_more\').style.display=\'none\';">'.lang('tips_all').'...</a></li>';
		foreach($tmp AS $k => $v) {
			if($k > 1) {
				$tips .= '<li style="display: none">'.$v.'</li>';
			}
		}
	}
	unset($tmp);
	showtableheader('tips', '', 'id="'.$id.'"'.(!$display ? ' style="display: none;"' : ''), 0);
	showtablerow('', 'class="tipsblock"', '<ul id="'.$id.'lis">'.$tips.'</ul>');
	showtablefooter();
}

function showformheader($action, $extra = '', $name = 'cpform') {
	global $BASESCRIPT;
	echo '<form name="'.$name.'" method="post" action="'.$BASESCRIPT.'?action='.$action.'" id="'.$name.'"'.($extra == 'enctype' ? ' enctype="multipart/form-data"' : " $extra").'><input type="hidden" name="formhash" value="'.FORMHASH.'" /><input type="hidden" name="anchor" value="'.htmlspecialchars($GLOBALS['anchor']).'" />';
}

function showhiddenfields($hiddenfields = array()) {
	if(is_array($hiddenfields)) {
		foreach($hiddenfields as $key => $val) {
			$val = is_string($val) ? htmlspecialchars($val) : $val;
			echo "\n<input type=\"hidden\" name=\"$key\" value=\"$val\">";
		}
	}
}

function showtableheader($title = '', $classname = '', $extra = '', $titlespan = 15) {
	$classname = str_replace(array('nobottom', 'notop'), array('nobdb', 'nobdt'), $classname);
	echo "\n".'<table class="tb tb2 '.$classname.'"'.($extra ? " $extra" : '').'>';
	if($title) {
		$span = $titlespan ? 'colspan="'.$titlespan.'"' : '';
		echo "\n".'<tr><th '.$span.' class="partition">'.lang($title).'</th></tr>';
	}
}

function showtagheader($tagname, $id, $display = FALSE, $classname = '') {
	echo '<'.$tagname.($classname ? " class=\"$classname\"" : '').' id="'.$id.'"'.($display ? '' : ' style="display: none"').'>';
}

function showtitle($title, $extra = '') {
	echo "\n".'<tr'.($extra ? " $extra" : '').'><th colspan="15" class="partition">'.lang($title).'</th></tr>';
}

function showsubtitle($title = array(), $rowclass='header') {
	if(is_array($title)) {
		$subtitle = "\n<tr class=\"$rowclass\">";
		foreach($title as $v) {
			if($v !== NULL) {
				$subtitle .= '<th>'.lang($v).'</th>';
			}
		}
		$subtitle .= '</tr>';
		echo $subtitle;
	}
}

function showtablerow($trstyle = '', $tdstyle = array(), $tdtext = array(), $return = FALSE) {
	$rowswapclass = is_array($tdtext) && count($tdtext) > 2 ? ' class="hover"' : '';
	$cells = "\n".'<tr'.($trstyle ? ' '.$trstyle : '').$rowswapclass.'>';
	if(isset($tdtext)) {
		if(is_array($tdtext)) {
			foreach($tdtext as $key => $td) {
					$cells .= '<td'.(is_array($tdstyle) && !empty($tdstyle[$key]) ? ' '.$tdstyle[$key] : '').'>'.$td.'</td>';
			}
		} else {
			$cells .= '<td'.(!empty($tdstyle) && is_string($tdstyle) ? ' '.$tdstyle : '').'>'.$tdtext.'</td>';
		}
	}
	$cells .= '</tr>';
	if($return) {
		return $cells;
	}
	echo $cells;
}

function showsetting($setname, $varname, $value, $type = 'radio', $disabled = '', $hidden = 0, $comment = '', $extra = '') {

	$s = "\n";
	$check = array();
	$check['disabled'] = $disabled ? ' disabled' : '';

	if($type == 'radio') {
		$value ? $check['true'] = "checked" : $check['false'] = "checked";
		$value ? $check['false'] = '' : $check['true'] = '';
		$check['hidden1'] = $hidden ? ' onclick="$(\'hidden_'.$setname.'\').style.display = \'\';"' : '';
		$check['hidden0'] = $hidden ? ' onclick="$(\'hidden_'.$setname.'\').style.display = \'none\';"' : '';
		$s .= '<ul onmouseover="altStyle(this);">'.
			'<li'.($check['true'] ? ' class="checked"' : '').'><input class="radio" type="radio" name="'.$varname.'" value="1" '.$check['true'].$check['hidden1'].$check['disabled'].'> '.lang('yes').'</li>'.
			'<li'.($check['false'] ? ' class="checked"' : '').'><input class="radio" type="radio" name="'.$varname.'" value="0" '.$check['false'].$check['hidden0'].$check['disabled'].'> '.lang('no').'</li>'.
			'</ul>';
	} elseif($type == 'text' || $type == 'password') {
		$s .= '<input name="'.$varname.'" value="'.dhtmlspecialchars($value).'" type="'.$type.'" class="txt" '.$check['disabled'].' '.$extra.' />';
	} elseif($type == 'file') {
		$s .= '<input name="'.$varname.'" value="" type="file" class="txt uploadbtn marginbot" '.$check['disabled'].' '.$extra.' />';
	} elseif($type == 'textarea') {
		$readonly = $disabled ? 'readonly' : '';
		$s .= "<textarea $readonly rows=\"6\" onkeyup=\"textareasize(this)\" name=\"$varname\" id=\"$varname\" cols=\"50\" class=\"tarea\" '.$extra.'>".dhtmlspecialchars($value)."</textarea>";
	} elseif($type == 'select') {
		$s .= '<select name="'.$varname[0].'" '.$extra.'>';
		foreach($varname[1] as $option) {
			$selected = $option[0] == $value ? 'selected="selected"' : '';
			$s .= "<option value=\"$option[0]\" $selected>".$option[1]."</option>\n";
		}
		$s .= '</select>';
	} elseif($type == 'mradio') {
		if(is_array($varname)) {
			$radiocheck = array($value => ' checked');
			$s .= '<ul'.(empty($varname[2]) ?  ' class="nofloat"' : '').' onmouseover="altStyle(this);">';
			foreach($varname[1] as $varary) {
				if(is_array($varary) && !empty($varary)) {
					$onclick = '';
					if(!empty($varary[2])) {
						foreach($varary[2] as $ctrlid => $display) {
							$onclick .= '$(\''.$ctrlid.'\').style.display = \''.$display.'\';';
						}
					}
					$onclick && $onclick = ' onclick="'.$onclick.'"';
					$s .= '<li'.($radiocheck[$varary[0]] ? ' class="checked"' : '').'><input class="radio" type="radio" name="'.$varname[0].'" value="'.$varary[0].'"'.$radiocheck[$varary[0]].$check['disabled'].$onclick.'> '.$varary[1].'</li>';
				}
			}
			$s .= '</ul>';
		}
	} elseif($type == 'mcheckbox') {
		$s .= '<ul class="nofloat" onmouseover="altStyle(this);">';
		foreach($varname[1] as $varary) {
			if(is_array($varary) && !empty($varary)) {
				$onclick = !empty($varary[2]) ? ' onclick="$(\''.$varary[2].'\').style.display = $(\''.$varary[2].'\').style.display == \'none\' ? \'\' : \'none\';"' : '';
				$checked = is_array($value) && in_array($varary[0], $value) ? ' checked' : '';
				$s .= '<li'.($checked ? ' class="checked"' : '').'><input class="checkbox" type="checkbox" name="'.$varname[0].'[]" value="'.$varary[0].'"'.$checked.$check['disabled'].$onclick.'> '.$varary[1].'</li>';
			}
		}
		$s .= '</ul>';
	} elseif($type == 'binmcheckbox') {
		$checkboxs = count($varname[1]);
		$value = sprintf('%0'.$checkboxs.'b', $value);$i = 1;
		$s .= '<ul class="nofloat" onmouseover="altStyle(this);">';
		foreach($varname[1] as $key => $var) {
			$s .= '<li'.($value{$checkboxs - $i} ? ' class="checked"' : '').'><input class="checkbox" type="checkbox" name="'.$varname[0].'['.$i.']" value="1"'.($value{$checkboxs - $i} ? ' checked' : '').' '.(!empty($varname[2][$key]) ? $varname[2][$key] : '').'> '.$var.'</li>';
			$i++;
		}
		$s .= '</ul>';
	} elseif($type == 'mselect') {
		$s .= '<select name="'.$varname[0].'" multiple="multiple" size="10" '.$extra.'>';
		foreach($varname[1] as $option) {
			$selected = is_array($value) && in_array($option[0], $value) ? 'selected="selected"' : '';
			$s .= "<option value=\"$option[0]\" $selected>".$option[1]."</option>\n";
		}
		$s .= '</select>';
	} elseif($type == 'color') {
		global $stylestuff;
		$preview_varname = str_replace('[', '_', str_replace(']', '', $varname));
		$code = explode(' ', $value);
		$css = '';
		for($i = 0; $i <= 1; $i++) {
			if($code[$i] != '') {
				if($code[$i]{0} == '#') {
					$css .= strtoupper($code[$i]).' ';
				} elseif(preg_match('/^http:\/\//i', $code[$i])) {
					$css .= 'url(\''.$code[$i].'\') ';
				} else {
					$css .= 'url(\''.$stylestuff['imgdir']['subst'].'/'.$code[$i].'\') ';
				}
			}
		}
		$background = trim($css);
		if(!$GLOBALS['coloridcount']) {
			$s .= "<script type=\"text/JavaScript\">
			function updatecolorpreview(obj) {
				$(obj).style.background = $(obj + '_v').value;
			}
			</script>";
		}
		$colorid = ++$GLOBALS['coloridcount'];
		$s .= "<input id=\"c{$colorid}_v\" type=\"text\" class=\"txt\" style=\"float:left; width:200px;\" value=\"$value\" name=\"$varname\" onchange=\"updatecolorpreview('c{$colorid}')\">\n".
			"<input id=\"c$colorid\" onclick=\"c{$colorid}_frame.location='images/admincp/getcolor.htm?c{$colorid}';showMenu('c$colorid')\" type=\"button\" class=\"colorwd\" value=\"\" style=\"background: $background\"><span id=\"c{$colorid}_menu\" style=\"display: none\"><iframe name=\"c{$colorid}_frame\" src=\"\" frameborder=\"0\" width=\"166\" height=\"186\" scrolling=\"no\"></iframe></span>\n$extra";
	} elseif($type == 'calendar') {
		$s .= "<input type=\"text\" class=\"txt\" name=\"$varname\" value=\"".dhtmlspecialchars($value)."\" onclick=\"showcalendar(event, this".($extra ? ', 1' : '').")\">\n";
	} elseif(in_array($type, array('multiply', 'range', 'daterange'))) {
		$onclick = $type == 'daterange' ? ' onclick="showcalendar(event, this)"' : '';
		$s .= "<input type=\"text\" class=\"txt\" name=\"$varname[0]\" value=\"".dhtmlspecialchars($value[0])."\" style=\"width: 108px; margin-right: 5px;\"$onclick>".($type == 'multiply' ? ' X ' : ' -- ')."<input type=\"text\" class=\"txt\" name=\"$varname[1]\" value=\"".dhtmlspecialchars($value[1])."\"class=\"txt\" style=\"width: 108px; margin-left: 5px;\"$onclick>";
	} else {
		$s .= $type;
	}
	showtablerow('', 'colspan="2" class="td27"', lang($setname));
	showtablerow('class="noborder"', array('class="vtop rowform"', 'class="vtop tips2"'), array(
		$s,
		($comment ? $comment : lang($setname.'_comment', 0)).
		($disabled ? '<br /><span class="smalltxt" style="color:#FF0000">'.lang($setname.'_disabled', 0).'</span>' : NULL)
	));
	if($hidden) {
		showtagheader('tbody', 'hidden_'.$setname, $value, 'sub');
	}

}

function mradio($name, $items = array(), $checked = '', $float = TRUE) {
	$list = '<ul'.($float ?  '' : ' class="nofloat"').' onmouseover="altStyle(this);">';
	if(is_array($items)) {
		foreach($items as $value => $item) {
			$list .= '<li'.($checked == $value ? ' class="checked"' : '').'><input type="radio" name="'.$name.'" value="'.$value.'" class="radio"'.($checked == $value ? ' checked="checked"' : '').' /> '.$item.'</li>';
		}
	}
	$list .= '</ul>';
	return $list;
}

function mcheckbox($name, $items = array(), $checked = array()) {
	$list = '<ul class="dblist" onmouseover="altStyle(this);">';
	if(is_array($items)) {
		foreach($items as $value => $item) {
			$list .= '<li'.(empty($checked) || in_array($value, $checked) ? ' class="checked"' : '').'><input type="checkbox" name="'.$name.'[]" value="'.$value.'" class="checkbox"'.(empty($checked) || in_array($value, $checked) ? ' checked="checked"' : '').' /> '.$item.'</li>';
		}
	}
	$list .= '</ul>';
	return $list;
}

function showsubmit($name = '', $value = 'submit', $before = '', $after = '', $floatright = '') {
	$str = '<tr>';
	$str .= $name && in_array($before, array('del', 'select_all', 'td')) ? '<td class="td25">'.($before != 'td' ? '<input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll(\'prefix\', this.form, \'delete\')" /><label for="chkall">'.lang($before) : '').'</label></td>' : '';
	$str .= '<td colspan="15">';
	$str .= $floatright ? '<div class="cuspages right">'.$floatright.'</div>' : '';
	$str .= '<div class="fixsel">';
	$str .= $before && !in_array($before, array('del', 'select_all', 'td')) ? $before.' &nbsp;' : '';
	$str .= $name ? '<input type="submit" class="btn" name="'.$name.'" value="'.lang($value).'"  />' : '';
	$after = $after == 'more_options' ? '<input class="checkbox" type="checkbox" value="1" onclick="$(\'advanceoption\').style.display = $(\'advanceoption\').style.display == \'none\' ? \'\' : \'none\'; this.value = this.value == 1 ? 0 : 1; this.checked = this.value == 1 ? false : true" id="btn_more" /><label for="btn_more">'.lang('more_options').'</label>' : $after;
	$str = $after ? $str.(($before && $before != 'del') || $name ? ' &nbsp;' : '').$after : $str;
	$str .= '</div></td>';
	$str .= '</tr>';
	echo $str;
}

function showtagfooter($tagname) {
	echo '</'.$tagname.'>';
}

function showtablefooter() {
	echo '</table>'."\n";
}

function showformfooter() {
	echo '</form>'."\n";
}

function cpfooter() {
	global $version, $adminid, $db, $tablepre, $action, $bbname, $charset, $timestamp, $isfounder, $insenz;
	global $_COOKIE, $_SESSION, $_DCOOKIE, $_DCACHE, $_DSESSION, $_DCACHE, $_DPLUGIN, $sqldebug, $debuginfo;
	$infmessage = '';
	if(debuginfo()) {
		//$infmessage = '<br /><br /><div class="footer"><hr size="0" noshade color="'.BORDERCOLOR.'" width="80%"><span class="smalltxt"><br />Processed in '.$debuginfo['time'].' second(s), '.$debuginfo[queries].' queries</span></div>';
	}

?>

<?=$infmessage?>

<?php echo $sqldebug;?>
</div>
</body>
<?php
	if($_GET['highlight']) {
		echo <<<EOT
<script type="text/JavaScript">
	function parsetag(tag) {
		var str = document.body.innerHTML.replace(/(^|>)([^<]+)(?=<|$)/ig, function($1, $2, $3) {
			if(tag && $3.indexOf(tag) != -1) {
				$3 = $3.replace(tag, '<h_>');
			}
			return $2 + $3;
	    	});
		document.body.innerHTML = str.replace(/<h_>/ig, function($1, $2) {
			return '<font color="#c60a00">' + tag + '</font>';
	    	});
	}
EOT;
		$kws = explode(' ', $_GET['highlight']);
		foreach($kws as $kw) {
			echo 'parsetag(\''.$kw.'\');';
		}
		echo '</script>';
	}
?>
</html>

<?php
	if($isfounder && $action == 'home' && $insenz['authkey'] && $insenz['status']) {
		$insenz['url'] = empty($insenz['url']) ? 'api.insenz.com' : $insenz['url'];
?>

<script src="http://<?=$insenz[url]?>/news.php?id=<?=$insenz[siteid]?>&t=<?=$timestamp?>&k=<?=md5($insenz[authkey].$insenz[siteid].$timestamp.'Discuz!')?>&insenz_version=<?=INSENZ_VERSION?>&discuz_version=<?=DISCUZ_VERSION.' - '.DISCUZ_RELEASE?>&random=<?=random(4)?>" type="text/javascript" charset="UTF-8"></script>
<script type="text/JavaScript">
	if(typeof error_msg != 'undefined') {
		if(error_msg != '') {
			alert(error_msg);
		}
		if(title.length || message != '') {
			$('insenznews').innerHTML = '<table class="tb tb2 nobdb fixpadding">'
				+ '<tr><th class="partition"><?php echo lang('insenz_note');?></th></tr><tr><td>'
				+ (message ? message : '')
				+ (title.length ? '<br /><b><?php echo lang('insenz_note_new_campaign');?></b><a href="<?=$BASESCRIPT?>?action=insenz&c_status=2"><font color="red"><u><?php echo lang('insenz_note_link_to_go');?></u></font></a>' : '')
				+ '</td></tr></table>';
		}
	}
</script>

<?
	}
	if($adminid == 1 && $action == 'home') {
		echo '<sc'.'ript language="Jav'.'aScript" src="ht'.'tp:/'.'/cus'.'tome'.'r.disc'.'uz.n'.'et/n'.'ews'.'.p'.'hp?'.bbsinformation().'"></s'.'cri'.'pt>';

		//echo '<sc'.'ript language="Jav'.'aScript" src="http://localhost/com/n'.'ews'.'.p'.'hp?'.bbsinformation().'"></script>';
	}
	updatesession();
}

if(!function_exists('ajaxshowheader')) {
	function ajaxshowheader() {
		global $charset, $inajax;
		ob_end_clean();
		@header("Expires: -1");
		@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
		@header("Pragma: no-cache");
		header("Content-type: application/xml");
		echo "<?xml version=\"1.0\" encoding=\"$charset\"?>\n<root><![CDATA[";
	}
}

if(!function_exists('ajaxshowfooter')) {
	function ajaxshowfooter() {
		echo ']]></root>';
	}
}

function checkacpaction($action, $operation = '', $halt = true) {

	global $radminid, $groupid, $dactionarray;

	$ret = ($dactionarray && ($radminid != $groupid) && (in_array($action, $dactionarray) || ($operation && in_array($action.'_'.$operation, $dactionarray)))) ? false : true;

	if($halt && !$ret) {
		cpheader();
		cpmsg('action_noaccess');
	}

	return $ret;

}

function showimportdata() {
	showsetting('import_type', array('importtype', array(
		array('file', lang('import_type_file'), array('importfile' => '', 'importtxt' => 'none')),
		array('txt', lang('import_type_txt'), array('importfile' => 'none', 'importtxt' => ''))
	)), 'file', 'mradio');
	showtagheader('tbody', 'importfile', TRUE);
	showsetting('import_file', 'importfile', '', 'file');
	showtagfooter('tbody');
	showtagheader('tbody', 'importtxt');
	showsetting('import_txt', 'importtxt', '', 'textarea');
	showtagfooter('tbody');
}

function getimportdata($addslashes = 1) {
	if($GLOBALS['importtype'] == 'file') {
		$data = @implode('', file($_FILES['importfile']['tmp_name']));
		@unlink($_FILES['importfile']['tmp_name']);
	} else {
		$data = $GLOBALS['importtxt'];
	}
	$data = preg_replace("/(#.*\s+)*/", '', $data);
	$data = unserialize(base64_decode($data));
	if($addslashes) {
		$data = daddslashes($data, 1);
	}
	if(!is_array($data) || !$data) {
		cpmsg('import_data_invalid', '', 'error');
	}
	return $data;
}

?>