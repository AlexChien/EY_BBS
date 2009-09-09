<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: plugins.inc.php 17025 2008-12-03 07:37:56Z monkey $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

cpheader();

if(!empty($identifier) && !empty($mod)) {
	$operation = 'config';
}

if(!$operation) {

	if(!submitcheck('submit')) {

		shownav('extended', 'nav_plugins');
		showsubmenu('nav_plugins', array(
			array('config', 'plugins&operation=config', 0),
			array('admin', 'plugins', 1),
			array('import', 'plugins&operation=import', 0)
		));
		showtips('plugins_config_tips');
		showformheader('plugins');
		showtableheader();
		showsubtitle(array('', 'available', 'plugins_name', 'plugins_identifier', 'plugins_directory', ''));

		$query = $db->query("SELECT * FROM {$tablepre}plugins");
		while($plugin = $db->fetch_array($query)) {
			showtablerow('', array('class="td25"', 'class="td25"', 'class="bold"', 'width="20%"', 'width="30%"', 'width="10%"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$plugin[pluginid]\">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$plugin[pluginid]]\" value=\"1\" ".(!$plugin['name'] || !$plugin['identifier'] ? 'disabled' : ($plugin['available'] ? 'checked' : '')).">",
				$plugin['name'],
				$plugin['identifier'],
				$plugin['directory'],
				"<a href=\"$BASESCRIPT?action=plugins&operation=export&pluginid=$plugin[pluginid]\" class=\"act\">$lang[export]</a>&nbsp;<a href=\"$BASESCRIPT?action=plugins&operation=edit&pluginid=$plugin[pluginid]\" target=\"_blank\" class=\"act\">$lang[detail]</a>"
			));
		}

?>
<script type="text/JavaScript">
	var rowtypedata = [
		[
			[1,''],
			[1,''],
			[1,'<input type="text" class="txt" name="newname[]" size="12">'],
			[1,'<input type="text" class="txt" name="newidentifier[]" size="8">'],
			[1,''],
			[1,'']
		]
	];
</script>
<?

		echo '<tr><td></td><td colspan="5"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['plugins_add'].'</a></div></td></tr>';
		showsubmit('submit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		$db->query("UPDATE {$tablepre}plugins SET available='0'");
		if(is_array($availablenew)) {
			foreach($availablenew as $id => $available) {
				$db->query("UPDATE {$tablepre}plugins SET available='$available' WHERE pluginid='$id'");
			}
		}

		if($ids = implodeids($delete)) {
			$db->query("DELETE FROM {$tablepre}plugins WHERE pluginid IN ($ids)");
			$db->query("DELETE FROM {$tablepre}pluginvars WHERE pluginid IN ($ids)");
		}

		if(is_array($newname)) {

			foreach($newname as $key => $value) {
				$newname1 = trim($value);
				$newidentifier1 = trim($newidentifier[$key]);
				if($newname1 || $newidentifier1) {
					if(!$newname1) {
						cpmsg('plugins_edit_name_invalid', '', 'error');
					}
					$query = $db->query("SELECT pluginid FROM {$tablepre}plugins WHERE identifier='$newidentifier1' LIMIT 1");
					if($db->num_rows($query) || !$newidentifier1 || !ispluginkey($newidentifier1)) {
						cpmsg('plugins_edit_identifier_invalid', '', 'error');
					}
					$db->query("INSERT INTO {$tablepre}plugins (name, identifier, available) VALUES ('".dhtmlspecialchars(trim($newname1))."', '$newidentifier1', '0')");
				}
			}
		}

		updatecache('plugins');
		updatecache('settings');
		cpmsg('plugins_edit_succeed', $BASESCRIPT.'?action=plugins', 'succeed');

	}

} elseif($operation == 'export' && $pluginid) {

	$plugin = $db->fetch_first("SELECT * FROM {$tablepre}plugins WHERE pluginid='$pluginid'");
	if(!$plugin) {
		cpheader();
		cpmsg('undefined_action', '', 'error');
	}

	unset($plugin['pluginid']);

	$pluginarray = array();
	$pluginarray['plugin'] = $plugin;
	$pluginarray['version'] = strip_tags($version);

	$time = gmdate("$dateformat $timeformat", $timestamp + $timeoffset * 3600);

	$query = $db->query("SELECT * FROM {$tablepre}pluginhooks WHERE pluginid='$pluginid'");
	while($hook = $db->fetch_array($query)) {
		unset($hook['pluginhookid'], $hook['pluginid']);
		$pluginarray['hooks'][] = $hook;
	}

	$query = $db->query("SELECT * FROM {$tablepre}pluginvars WHERE pluginid='$pluginid'");
	while($var = $db->fetch_array($query)) {
		unset($var['pluginvarid'], $var['pluginid']);
		$pluginarray['vars'][] = $var;
	}

	$plugin_export = "# Discuz! Plugin Dump\n".
		"# Version: Discuz! $version\n".
		"# Time: $time  \n".
		"# From: $bbname ($boardurl) \n".
		"#\n".
		"# Discuz! Community: http://www.Discuz.net\n".
		"# Please visit our website for latest news about Discuz!\n".
		"# --------------------------------------------------------\n\n\n".
		wordwrap(base64_encode(serialize($pluginarray)), 60, "\n", 1);

	ob_end_clean();
	dheader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	dheader('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	dheader('Cache-Control: no-cache, must-revalidate');
	dheader('Pragma: no-cache');
	dheader('Content-Encoding: none');
	dheader('Content-Length: '.strlen($plugin_export));
	dheader('Content-Disposition: attachment; filename=discuz_plugin_'.$plugin['identifier'].'.txt');
	dheader('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
	echo $plugin_export;
	dexit();

} elseif($operation == 'import') {

	if(!submitcheck('importsubmit')) {

		shownav('extended', 'nav_plugins');
		showsubmenu('nav_plugins', array(
			array('config', 'plugins&operation=config', 0),
			array('admin', 'plugins', 0),
			array('import', 'plugins&operation=import', 1)
		));
		showformheader('plugins&operation=import', 'enctype');
		showtableheader('plugins_import', 'fixpadding');
		showimportdata();
		showtablerow('', '', '<input type="checkbox" name="ignoreversion" value="1" class="checkbox" /> '.lang('plugins_import_ignore_version'));
		showsubmit('importsubmit');
		showtablefooter();
		showformfooter();

	} else {

		$pluginarray = getimportdata();

		if(empty($ignoreversion) && strip_tags($pluginarray['version']) != strip_tags($version)) {
			cpmsg('plugins_import_version_invalid', '', 'error');
		}

		$query = $db->query("SELECT pluginid FROM {$tablepre}plugins WHERE identifier='{$pluginarray[plugin][identifier]}' LIMIT 1");
		if($db->num_rows($query)) {
			cpmsg('plugins_import_identifier_duplicated', '', 'error');
		}

		$sql1 = $sql2 = $comma = '';
		foreach($pluginarray['plugin'] as $key => $val) {
			if($key == 'directory') {
				//compatible for old versions
				$val .= (!empty($val) && substr($val, -1) != '/') ? '/' : '';
			}
			$sql1 .= $comma.$key;
			$sql2 .= $comma.'\''.$val.'\'';
			$comma = ',';
		}
		$db->query("INSERT INTO {$tablepre}plugins ($sql1) VALUES ($sql2)");
		$pluginid = $db->insert_id();

		foreach(array('hooks', 'vars') as $pluginconfig) {
			if(is_array($pluginarray[$pluginconfig])) {
				foreach($pluginarray[$pluginconfig] as $config) {
					$sql1 = 'pluginid';
					$sql2 = '\''.$pluginid.'\'';
					foreach($config as $key => $val) {
						$sql1 .= ','.$key;
						$sql2 .= ',\''.$val.'\'';
					}
					$db->query("INSERT INTO {$tablepre}plugin$pluginconfig ($sql1) VALUES ($sql2)");
				}
			}
		}

		updatecache('plugins');
		updatecache('settings');
		cpmsg('plugins_import_succeed', $BASESCRIPT.'?action=plugins', 'succeed');

	}

} elseif($operation == 'config') {

	if(!$pluginid && !$identifier) {

		shownav('extended', 'nav_plugins');
		showsubmenu('nav_plugins', array(
			array('config', 'plugins&operation=config', 1),
			array('admin', 'plugins', 0),
			array('import', 'plugins&operation=import', 0)
		));
		$plugins = '';
		$query = $db->query("SELECT p.*, pv.pluginvarid FROM {$tablepre}plugins p
			LEFT JOIN {$tablepre}pluginvars pv USING(pluginid)
			GROUP BY p.pluginid
			ORDER BY p.available DESC, p.pluginid");

		if(!$db->num_rows($query)) {
			dheader("location: $BASESCRIPT?action=plugins");
		}
		while($plugin = $db->fetch_array($query)) {
			if(!$plugin['adminid'] || $plugin['adminid'] >= $adminid) {
				$plugin['edit'] = $plugin['pluginvarid'] ? "<a href=\"$BASESCRIPT?action=plugins&operation=config&pluginid=$plugin[pluginid]\" class=\"act\">$lang[plugins_config]</a> " : '';
				if(is_array($plugin['modules'] = unserialize($plugin['modules']))) {
					foreach($plugin['modules'] as $module) {
						if($module['type'] == 3 && (!$module['adminid'] || $module['adminid'] >= $adminid)) {
							$plugin['edit'] .= "<a href=\"$BASESCRIPT?action=plugins&operation=config&identifier=$plugin[identifier]&mod=$module[name]\" class=\"act\">$module[menu]</a> ";
						}
					}
				}
			} else {
				$plugin['edit'] = lang('detail');
			}
			echo '<div class="colorbox"><h4>'.$plugin['name'].(!$plugin['available'] ? ' ('.$lang['plugins_unavailable'].')' : '').'</h4>'.nl2br($plugin['description']).'<br><div style="width:95%" style="clear:both"><div style="float:right">'.$plugin['copyright'].'</div>'.$plugin['edit'].'</div></div><br /><br />';
		}

	} else {

		$plugin = $db->fetch_first("SELECT * FROM {$tablepre}plugins WHERE ".($identifier ? "identifier='$identifier'" : "pluginid='$pluginid'"));
		if(!$plugin) {
			cpmsg('undefined_action', '', 'error');
		} else {
			$pluginid = $plugin['pluginid'];
		}

		$pluginvars = array();
		$query = $db->query("SELECT * FROM {$tablepre}pluginvars WHERE pluginid='$pluginid' ORDER BY displayorder");
		while($var = $db->fetch_array($query)) {
			$pluginvars[$var['variable']] = $var;
		}

		if(empty($mod)) {

			if(($plugin['adminid'] && $adminid > $plugin['adminid']) || !$pluginvars) {
				cpmsg('noaccess', '', 'error');
			}

			if(!submitcheck('editsubmit')) {
				shownav('extended', 'nav_plugins');
				showsubmenu('nav_plugins', array(
					array('config', 'plugins&operation=config', 0),
					array('admin', 'plugins', 0),
					array('import', 'plugins&operation=import', 0)
				));
				showformheader("plugins&operation=config&pluginid=$pluginid");
				showtableheader();
				showtitle($lang['plugins_config'].' - '.$plugin['name']);

				$extra = array();
				foreach($pluginvars as $var) {
					$var['variable'] = 'varsnew['.$var['variable'].']';
					if($var['type'] == 'number') {
						$var['type'] = 'text';
					} elseif($var['type'] == 'select') {
						$var['type'] = "<select name=\"$var[variable]\">\n";
						foreach(explode("\n", $var['extra']) as $key => $option) {
							$option = trim($option);
							if(strpos($option, '=') === FALSE) {
								$key = $option;
							} else {
								$item = explode('=', $option);
								$key = trim($item[0]);
								$option = trim($item[1]);
							}
							$var['type'] .= "<option value=\"".dhtmlspecialchars($key)."\" ".($var['value'] == $key ? 'selected' : '').">$option</option>\n";
						}
						$var['type'] .= "</select>\n";
						$var['variable'] = $var['value'] = '';
					} elseif($var['type'] == 'date') {
						$var['type'] = 'calendar';
						$extra['date'] = '<script type="text/javascript" src="include/js/calendar.js"></script>';
					} elseif($var['type'] == 'datetime') {
						$var['type'] = 'calendar';
						$var['extra'] = 1;
						$extra['date'] = '<script type="text/javascript" src="include/js/calendar.js"></script>';
					} elseif($var['type'] == 'forum') {
						require_once DISCUZ_ROOT.'./include/forum.func.php';
						$var['type'] = '<select name="'.$var['variable'].'">'.forumselect(FALSE, 0, $var['value']).'</select>';
						$var['variable'] = $var['value'] = '';
					} elseif($var['type'] == 'forums') {
						$var['description'] = $lang['plugins_edit_vars_multiselect_comment'].'<br />'.$var['comment'];
						$var['value'] = unserialize($var['value']);
						$var['value'] = is_array($var['value']) ? $var['value'] : array();
						require_once DISCUZ_ROOT.'./include/forum.func.php';
						$var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple">'.forumselect().'</select>';
						foreach($var['value'] as $v) {
							$var['type'] = str_replace('<option value="'.$v.'">', '<option value="'.$v.'" selected>', $var['type']);
						}
						$var['variable'] = $var['value'] = '';
					} elseif(substr($var['type'], 0, 5) == 'group') {
						if($var['type'] == 'groups') {
							$var['description'] = $lang['plugins_edit_vars_multiselect_comment'].'<br />'.$var['comment'];
							$var['value'] = unserialize($var['value']);
							$var['type'] = '<select name="'.$var['variable'].'[]" size="10" multiple="multiple">';
						} else {
							$var['type'] = '<select name="'.$var['variable'].'">';
						}
						$var['value'] = is_array($var['value']) ? $var['value'] : array($var['value']);

						$query = $db->query("SELECT groupid, grouptitle FROM {$tablepre}usergroups ORDER BY groupid");
						while($group = $db->fetch_array($query)) {
							$var['type'] .= '<option value="'.$group['groupid'].'"'.(in_array($group['groupid'], $var['value']) ? ' selected' : '').'>'.$group['grouptitle'].'</option>';
						}
						$var['type'] .= '</select>';
						$var['variable'] = $var['value'] = '';
					} elseif($var['type'] == 'extcredit') {
						$var['type'] = '<select name="'.$var['variable'].'">';
						foreach($extcredits as $id => $credit) {
							$var['type'] .= '<option value="'.$id.'"'.($var['value'] == $id ? ' selected' : '').'>'.$credit['title'].'</option>';
						}
						$var['type'] .= '</select>';
						$var['variable'] = $var['value'] = '';
					}

					showsetting(isset($lang[$var['title']]) ? $lang[$var['title']] : $var['title'], $var['variable'], $var['value'], $var['type'], '', 0, isset($lang[$var['description']]) ? $lang[$var['description']] : $var['description'], $var['extra']);
				}
				showsubmit('editsubmit');
				showtablefooter();
				showformfooter();
				echo implode('', $extra);

			} else {

				if(is_array($varsnew)) {
					foreach($varsnew as $variable => $value) {
						if(isset($pluginvars[$variable])) {
							if($pluginvars[$variable]['type'] == 'number') {
								$value = (float)$value;
							} elseif(in_array($pluginvars[$variable]['type'], array('forums', 'groups'))) {
								$value = addslashes(serialize($value));
							}
							$db->query("UPDATE {$tablepre}pluginvars SET value='$value' WHERE pluginid='$pluginid' AND variable='$variable'");
						}
					}
				}

				updatecache('plugins');
				cpmsg('plugins_settings_succeed', $BASESCRIPT.'?action=plugins&operation=config', 'succeed');

			}

		} else {

			$modfile = '';
			if(is_array($plugin['modules'] = unserialize($plugin['modules']))) {
				foreach($plugin['modules'] as $module) {
					if($module['type'] == 3 && $module['name'] == $mod && (!$module['adminid'] || $module['adminid'] >= $adminid)) {
						$plugin['directory'] .= (!empty($plugin['directory']) && substr($plugin['directory'], -1) != '/') ? '/' : '';
						$modfile = './plugins/'.$plugin['directory'].$module['name'].'.inc.php';
						break;
					}
				}
			}

			if($modfile) {
				if(!@include DISCUZ_ROOT.$modfile) {
					cpmsg('plugins_settings_module_nonexistence', '', 'error');
				} else {
					dexit();
				}
			} else {
				cpmsg('undefined_action', '', 'error');
			}

		}

	}

} elseif($operation == 'edit') {

	if(empty($pluginid) ) {
		$pluginlist = '<select name="pluginid">';
		$query = $db->query("SELECT pluginid, name FROM {$tablepre}plugins");
		while($plugin = $db->fetch_array($query)) {
			$pluginlist .= '<option value="'.$plugin['pluginid'].'">'.$plugin['name'].'</option>';
		}
		$pluginlist .= '</select>';
		cpmsg('plugins_nonexistence', $BASESCRIPT.'?action=plugins&operation=edit'.(!empty($highlight) ? "&highlight=$highlight" : ''), 'form', $pluginlist);
	} else {
		$condition = !empty($uid) ? "uid='$uid'" : "username='$username'";
	}

	$plugin = $db->fetch_first("SELECT * FROM {$tablepre}plugins WHERE pluginid='$pluginid'");
	if(!$plugin) {
		cpmsg('undefined_action', '', 'error');
	}

	$plugin['modules'] = unserialize($plugin['modules']);

	if(!submitcheck('editsubmit')) {

		$adminidselect = array($plugin['adminid'] => 'selected');

		shownav('extended', 'nav_plugins');
		$anchor = in_array($anchor, array('config', 'modules', 'hooks', 'vars')) ? $anchor : 'config';
		showsubmenuanchors($lang['plugins_edit'].' - '.$plugin['name'], array(
			array('config', 'config', $anchor == 'config'),
			array('plugins_config_module', 'modules', $anchor == 'modules'),
			array('plugins_config_hooks', 'hooks', $anchor == 'hooks'),
			array('plugins_config_vars', 'vars', $anchor == 'vars'),
		));
		showtips('plugins_edit_tips');

		showtagheader('div', 'config', $anchor == 'config');
		showformheader("plugins&operation=edit&type=common&pluginid=$pluginid", '', 'configform');
		showtableheader();
		showsetting('plugins_edit_name', 'namenew', $plugin['name'], 'text');
		if(!$plugin['copyright']) {
			showsetting('plugins_edit_copyright', 'copyrightnew', $plugin['copyright'], 'text');
		}
		showsetting('plugins_edit_identifier', 'identifiernew', $plugin['identifier'], 'text');
		showsetting('plugins_edit_adminid', '', '', '<select name="adminidnew"><option value="1" '.$adminidselect[1].'>'.$lang['usergroups_system_1'].'</option><option value="2" '.$adminidselect[2].'>'.$lang['usergroups_system_2'].'</option><option value="3" '.$adminidselect[3].'>'.$lang['usergroups_system_3'].'</option></select>');
		showsetting('plugins_edit_directory', 'directorynew', $plugin['directory'], 'text');
		showsetting('plugins_edit_datatables', 'datatablesnew', $plugin['datatables'], 'text');
		showsetting('plugins_edit_description', 'descriptionnew', $plugin['description'], 'textarea');
		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();
		showtagfooter('div');

		showtagheader('div', 'modules', $anchor == 'modules');
		showformheader("plugins&operation=edit&type=modules&pluginid=$pluginid", '', 'modulesform');
		showtableheader('plugins_edit_modules');
		showsubtitle(array('', 'display_order', 'plugins_edit_modules_name', 'plugins_edit_modules_menu', 'plugins_edit_modules_menu_url', 'plugins_edit_modules_type', 'plugins_edit_modules_adminid'));

		if(is_array($plugin['modules'])) {
			foreach($plugin['modules'] as $moduleid => $module) {
				$adminidselect = array($module['adminid'] => 'selected');
				$includecheck = empty($val['include']) ? $lang['no'] : $lang['yes'];

				$typeselect = '';
				for($i = 1; $i <= 6; $i++) {
					$typeselect .= "<option value=\"$i\" ".($module['type'] == $i ? 'selected' : '').">".$lang['plugins_edit_modules_type_'.$i]."</option>";
				}
				showtablerow('', array('class="td25"', 'class="td28"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$moduleid]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"ordernew[$moduleid]\" value=\"$module[displayorder]\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"namenew[$moduleid]\" value=\"$module[name]\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"menunew[$moduleid]\" value=\"$module[menu]\">",
					"<input type=\"text\" class=\"txt\" size=\"15\" name=\"urlnew[$moduleid]\" value=\"".dhtmlspecialchars($module['url'])."\">",
					"<select name=\"typenew[$moduleid]\">$typeselect</select>",
					"<select name=\"adminidnew[$moduleid]\">\n".
					"<option value=\"0\" $adminidselect[0]>$lang[usergroups_system_0]</option>\n".
					"<option value=\"1\" $adminidselect[1]>$lang[usergroups_system_1]</option>\n".
					"<option value=\"2\" $adminidselect[2]>$lang[usergroups_system_2]</option>\n".
					"<option value=\"3\" $adminidselect[3]>$lang[usergroups_system_3]</option>\n".
					"</select>"
				));
			}
		}
		showtablerow('', array('class="td25"', 'class="td28"'), array(
			lang('add_new'),
			'<input type="text" class="txt" size="2" name="neworder">',
			'<input type="text" class="txt" size="15" name="newname">',
			'<input type="text" class="txt" size="15" name="newmenu">',
			'<input type="text" class="txt" size="15" name="newurl">',
			'<select name="newtype">
				<option value="1">'.lang('plugins_edit_modules_type_1').'</option>
				<option value="2">'.lang('plugins_edit_modules_type_2').'</option>
				<option value="3">'.lang('plugins_edit_modules_type_3').'</option>
				<option value="4">'.lang('plugins_edit_modules_type_4').'</option>
				<option value="5">'.lang('plugins_edit_modules_type_5').'</option>
				<option value="6">'.lang('plugins_edit_modules_type_6').'</option>
			</select>',
			'<select name="newadminid">
				<option value="0">'.lang('usergroups_system_0').'</option>
				<option value="1" selected>'.lang('usergroups_system_1').'</option>
				<option value="2">'.lang('usergroups_system_2').'</option>
				<option value="3">'.lang('usergroups_system_3').'</option>
			</select>'
		));
		showsubmit('editsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		showtagfooter('div');

		showtagheader('div', 'hooks', $anchor == 'hooks');
		showformheader("plugins&operation=edit&type=hooks&pluginid=$pluginid", '', 'hooksform');
		showtableheader('plugins_edit_hooks');
		showsubtitle(array('', 'available', 'plugins_hooks_title', 'plugins_hooks_callback', 'plugins_hooks_description', ''));
		$query = $db->query("SELECT pluginhookid, title, description, available FROM {$tablepre}pluginhooks WHERE pluginid='$plugin[pluginid]'");
		while($hook = $db->fetch_array($query)) {
			$hook['description'] = nl2br(cutstr($hook['description'], 50));
			$hook['evalcode'] = 'eval($hooks[\''.$plugin['identifier'].'_'.$hook['title'].'\']);';
			showtablerow('', '', array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[$hook[pluginhookid]]\">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$hook[pluginhookid]]\" value=\"1\" ".($hook['available'] ? 'checked' : '')." onclick=\"if(this.checked) {\$('hookevalcode{$hook[pluginhookid]}').value='".addslashes($hook[evalcode])."';}else{\$('hookevalcode{$hook[pluginhookid]}').value='N/A';}\">",
				"<input type=\"text\" class=\"txt\" name=\"titlenew[$hook[pluginhookid]]\" size=\"15\" value=\"$hook[title]\"></td>\n".
				"<td><input type=\"text\" class=\"txt\" name=\"hookevalcode{$hook[pluginhookid]}\" id=\"hookevalcode{$hook[pluginhookid]}\"size=\"30\" value=\"".($hook['available'] ? $hook[evalcode] : 'N/A')."\" readonly>",
				$hook[description],
				"<a href=\"$BASESCRIPT?action=plugins&operation=hooks&pluginid=$plugin[pluginid]&pluginhookid=$hook[pluginhookid]\" class=\"act\">$lang[edit]</a>"
			));
		}
		showtablerow('', array('', '', '', 'colspan="3"'), array(
			lang('add_new'),
			'',
			'<input type="text" class="txt" name="newtitle" size="15">',
			''
		));
		showsubmit('editsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		showtagfooter('div');

		showtagheader('div', 'vars', $anchor == 'vars');
		showformheader("plugins&operation=edit&type=vars&pluginid=$pluginid", '', 'varsform');
		showtableheader('plugins_edit_vars');
		showsubtitle(array('', 'display_order', 'plugins_vars_title', 'plugins_vars_variable', 'plugins_vars_type', ''));
		$query = $db->query("SELECT * FROM {$tablepre}pluginvars WHERE pluginid='$plugin[pluginid]' ORDER BY displayorder");
		while($var = $db->fetch_array($query)) {
			$var['type'] = $lang['plugins_edit_vars_type_'. $var['type']];
			$var['title'] .= isset($lang[$var['title']]) ? '<br />'.$lang[$var['title']] : '';
			showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$var[pluginvarid]\">",
				"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayordernew[$var[pluginvarid]]\" value=\"$var[displayorder]\">",
				$var['title'],
				$var['variable'],
				$var['type'],
				"<a href=\"$BASESCRIPT?action=plugins&operation=vars&pluginid=$plugin[pluginid]&pluginvarid=$var[pluginvarid]\" class=\"act\">$lang[detail]</a>"
			));
		}
		showtablerow('', array('class="td25"', 'class="td28"'), array(
			lang('add_new'),
			'<input type="text" class="txt" size="2" name="newdisplayorder" value="0">',
			'<input type="text" class="txt" size="15" name="newtitle">',
			'<input type="text" class="txt" size="15" name="newvariable">',
			'<select name="newtype">
				<option value="number">'.lang('plugins_edit_vars_type_number').'</option>
				<option value="text" selected>'.lang('plugins_edit_vars_type_text').'</option>
				<option value="textarea">'.lang('plugins_edit_vars_type_textarea').'</option>
				<option value="radio">'.lang('plugins_edit_vars_type_radio').'</option>
				<option value="select">'.lang('plugins_edit_vars_type_select').'</option>
				<option value="color">'.lang('plugins_edit_vars_type_color').'</option>
				<option value="date">'.lang('plugins_edit_vars_type_date').'</option>
				<option value="datetime">'.lang('plugins_edit_vars_type_datetime').'</option>
				<option value="forum">'.lang('plugins_edit_vars_type_forum').'</option>
				<option value="forums">'.lang('plugins_edit_vars_type_forums').'</option>
				<option value="group">'.lang('plugins_edit_vars_type_group').'</option>
				<option value="groups">'.lang('plugins_edit_vars_type_groups').'</option>
				<option value="extcredit">'.lang('plugins_edit_vars_type_extcredit').'</option>
			</seletc>',
			''
		));
		showsubmit('editsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		showtagfooter('div');

	} else {

		if($type == 'common') {

			$namenew	= dhtmlspecialchars(trim($namenew));
			$directorynew	= dhtmlspecialchars($directorynew);
			$identifiernew	= trim($identifiernew);
			$datatablesnew	= dhtmlspecialchars(trim($datatablesnew));
			$descriptionnew	= dhtmlspecialchars($descriptionnew);
			$copyrightnew	= $plugin['copyright'] ? addslashes($plugin['copyright']) : dhtmlspecialchars($copyrightnew);
			$adminidnew	= ($adminidnew > 0 && $adminidnew <= 3) ? $adminidnew : 1;

			if(!$namenew) {
				cpmsg('plugins_edit_name_invalid', '', 'error');
			} elseif(!isplugindir($directorynew)) {
				cpmsg('plugins_edit_directory_invalid', '', 'error');
			} elseif($identifiernew != $plugin['identifier']) {
				$query = $db->query("SELECT pluginid FROM {$tablepre}plugins WHERE identifier='$identifiernew' LIMIT 1");
				if($db->num_rows($query) || !ispluginkey($identifiernew)) {
					cpmsg('plugins_edit_identifier_invalid', '', 'error');
				}
			}

			$db->query("UPDATE {$tablepre}plugins SET adminid='$adminidnew', name='$namenew', identifier='$identifiernew', description='$descriptionnew', datatables='$datatablesnew', directory='$directorynew', copyright='$copyrightnew' WHERE pluginid='$pluginid'");

		} elseif($type == 'modules') {

			$modulesnew = array();
			$newname = trim($newname);
			if(is_array($plugin['modules'])) {
				foreach($plugin['modules'] as $moduleid => $module) {
					if(!isset($delete[$moduleid])) {
						$modulesnew[] = array
							(
							'name'		=> $namenew[$moduleid],
							'menu'		=> $menunew[$moduleid],
							'url'		=> $urlnew[$moduleid],
							'type'		=> $typenew[$moduleid],
							'adminid'	=> ($adminidnew[$moduleid] >= 0 && $adminidnew[$moduleid] <= 3) ? $adminidnew[$moduleid] : $module['adminid'],
							'displayorder'	=> intval($ordernew[$moduleid]),
							);
					}
				}
			}

			$newmodule = array();
			if(!empty($newname)) {
				$modulesnew[] = array
					(
					'name'		=> $newname,
					'menu'		=> $newmenu,
					'url'		=> $newurl,
					'type'		=> $newtype,
					'adminid'	=> $newadminid,
					'displayorder'	=> intval($neworder),
					);
			}

			usort($modulesnew, 'modulecmp');

			$namesarray = array();
			foreach($modulesnew as $key => $module) {
				if(!ispluginkey($module['name'])) {
					cpmsg('plugins_edit_modules_name_invalid', '', 'error');
				} elseif(in_array($module['name'], $namesarray)) {
					cpmsg('plugins_edit_modules_duplicated', '', 'error');
				}

				$namesarray[] = $module['name'];

				$module['menu'] = trim($module['menu']);
				$module['url'] = trim($module['url']);
				$module['adminid'] = $module['adminid'] >= 0 && $module['adminid'] <= 3 ? $module['adminid'] : 1 ;

				switch($module['type']) {
					case 5:
					case 1:
						if(empty($module['url'])) {
							cpmsg('plugins_edit_modules_url_invalid', '', 'error');
						}
						break;
					case 6:
					case 2:
					case 3:
						if(empty($module['menu'])) {
							cpmsg('plugins_edit_modules_menu_invalid', '', 'error');
						}
						unset($module['url']);
						break;
					case 4:
						unset($module['menu'], $module['url']);
						break;
					default:
						cpmsg('undefined_action', '', 'error');
				}

				$modulesnew[$key] = $module;

			}

			$db->query("UPDATE {$tablepre}plugins SET modules='".addslashes(serialize($modulesnew))."' WHERE pluginid='$pluginid'");

		} elseif($type == 'hooks') {

			if(is_array($delete)) {
				$ids = $comma = '';
				foreach($delete as $id => $val) {
					$ids .= "$comma'$id'";
					$comma = ',';
				}
				$db->query("DELETE FROM {$tablepre}pluginhooks WHERE pluginid='$pluginid' AND pluginhookid IN ($ids)");
			}

			if(is_array($titlenew)) {
				$titlearray = array();
				foreach($titlenew as $id => $val) {
					if(!ispluginkey($val) || in_array($val, $titlearray)) {
						cpmsg('plugins_edit_hooks_title_invalid', '', 'error');
					}
					$titlearray[] = $val;
					$db->query("UPDATE {$tablepre}pluginhooks SET title='".dhtmlspecialchars($titlenew[$id])."', available='".intval($availablenew[$id])."' WHERE pluginid='$pluginid' AND pluginhookid='$id'");
				}
			}

			if($newtitle) {
				if(!ispluginkey($newtitle) || (is_array($titlenew) && in_array($newtitle, $titlenew))) {
					cpmsg('plugins_edit_hooks_title_invalid', '', 'error');
				}
				$db->query("INSERT INTO {$tablepre}pluginhooks (pluginid, title, description, code, available)
					VALUES ('$pluginid', '".dhtmlspecialchars($newtitle)."', '', '', 0)");
			}

		} elseif($type == 'vars') {

			if($ids = implodeids($delete)) {
				$db->query("DELETE FROM {$tablepre}pluginvars WHERE pluginid='$pluginid' AND pluginvarid IN ($ids)");
			}

			if(is_array($displayordernew)) {
				foreach($displayordernew as $id => $displayorder) {
					$db->query("UPDATE {$tablepre}pluginvars SET displayorder='$displayorder' WHERE pluginid='$pluginid' AND pluginvarid='$id'");
				}
			}

			$newtitle = dhtmlspecialchars(trim($newtitle));
			$newvariable = trim($newvariable);
			if($newtitle && $newvariable) {
				$query = $db->query("SELECT pluginvarid FROM {$tablepre}pluginvars WHERE pluginid='$pluginid' AND variable='$newvariable' LIMIT 1");
				if($db->num_rows($query) || strlen($newvariable) > 40 || !ispluginkey($newvariable)) {
					cpmsg('plugins_edit_var_invalid', '', 'error');
				}

				$db->query("INSERT INTO {$tablepre}pluginvars (pluginid, displayorder, title, variable, type)
					VALUES ('$pluginid', '$newdisplayorder', '$newtitle', '$newvariable', '$newtype')");
			}

		}

		updatecache('plugins');
		updatecache('settings');
		cpmsg('plugins_edit_succeed', "$BASESCRIPT?action=plugins&operation=edit&pluginid=$pluginid&anchor=$anchor", 'succeed');

	}

} elseif($operation == 'hooks') {

	$pluginhook = $db->fetch_first("SELECT * FROM {$tablepre}plugins p, {$tablepre}pluginhooks ph WHERE p.pluginid='$pluginid' AND ph.pluginid=p.pluginid AND ph.pluginhookid='$pluginhookid'");
	if(!$pluginhook) {
		cpmsg('undefined_action', '', 'error');
	}

	if(!submitcheck('hooksubmit')) {
		shownav('extended', 'nav_plugins');
		showsubmenu('nav_plugins', array(
			array('config', 'plugins&operation=config', 1),
			array('admin', 'plugins', 0),
			array('import', 'plugins&operation=import', 0)
		));
		showtips('plugins_edit_hooks_tips');
		showformheader("plugins&operation=hooks&pluginid=$pluginid&pluginhookid=$pluginhookid");
		showtableheader();
		showtitle($lang['plugins_edit_hooks'].' - '.$pluginhook['title']);
		showsetting('plugins_edit_hooks_description', 'descriptionnew', $pluginhook['description'], 'textarea');
		showsetting('plugins_edit_hooks_code', 'codenew', $pluginhook['code'], 'textarea');
		showsubmit('hooksubmit');
		showtablefooter();
		showformfooter();

	} else {

		$descriptionnew	= dhtmlspecialchars(trim($descriptionnew));
		$codenew	= trim($codenew);

		$db->query("UPDATE {$tablepre}pluginhooks SET description='$descriptionnew', code='$codenew' WHERE pluginid='$pluginid' AND pluginhookid='$pluginhookid'");

		updatecache('settings');
		cpmsg('plugins_edit_hooks_succeed', "$BASESCRIPT?action=plugins&operation=edit&pluginid=$pluginid", 'succeed');
	}

} elseif($operation == 'vars') {

	$pluginvar = $db->fetch_first("SELECT * FROM {$tablepre}plugins p, {$tablepre}pluginvars pv WHERE p.pluginid='$pluginid' AND pv.pluginid=p.pluginid AND pv.pluginvarid='$pluginvarid'");
	if(!$pluginvar) {
		cpmsg('undefined_action', '', 'error');
	}

	if(!submitcheck('varsubmit')) {
		shownav('extended', 'nav_plugins');
		showsubmenu('nav_plugins', array(
			array('config', 'plugins&operation=config', 1),
			array('admin', 'plugins', 0),
			array('import', 'plugins&operation=import', 0)
		));

		$typeselect = '<select name="typenew">';
		foreach(array('number', 'text', 'radio', 'textarea', 'select', 'color', 'date', 'datetime', 'forum', 'forums', 'group', 'groups', 'extcredit') as $type) {
			$typeselect .= '<option value="'.$type.'" '.($pluginvar['type'] == $type ? 'selected' : '').'>'.$lang['plugins_edit_vars_type_'.$type].'</option>';
		}
		$typeselect .= '</select>';

		showformheader("plugins&operation=vars&pluginid=$pluginid&pluginvarid=$pluginvarid");
		showtableheader();
		showtitle($lang['plugins_edit_vars'].' - '.$pluginvar['title']);
		showsetting('plugins_edit_vars_title', 'titlenew', $pluginvar['title'], 'text');
		showsetting('plugins_edit_vars_description', 'descriptionnew', $pluginvar['description'], 'textarea');
		showsetting('plugins_edit_vars_type', '', '', $typeselect);
		showsetting('plugins_edit_vars_variable', 'variablenew', $pluginvar['variable'], 'text');
		showsetting('plugins_edit_vars_extra', 'extranew',  $pluginvar['extra'], 'textarea');
		showsubmit('varsubmit');
		showtablefooter();
		showformfooter();

	} else {

		$titlenew	= cutstr(dhtmlspecialchars(trim($titlenew)), 25);
		$descriptionnew	= cutstr(dhtmlspecialchars(trim($descriptionnew)), 255);
		$variablenew	= trim($variablenew);
		$extranew	= dhtmlspecialchars(trim($extranew));

		if(!$titlenew) {
			cpmsg('plugins_edit_vars_title_invalid', '', 'error');
		} elseif($variablenew != $pluginvar['variable']) {
			$query = $db->query("SELECT pluginvarid FROM {$tablepre}pluginvars WHERE variable='$variablenew'");
			if($db->num_rows($query) || !$variablenew || strlen($variablenew) > 40 || !ispluginkey($variablenew)) {
				cpmsg('plugins_edit_vars_invalid', '', 'error');
			}
		}

		$db->query("UPDATE {$tablepre}pluginvars SET title='$titlenew', description='$descriptionnew', type='$typenew', variable='$variablenew', extra='$extranew' WHERE pluginid='$pluginid' AND pluginvarid='$pluginvarid'");

		updatecache('plugins');
		cpmsg('plugins_edit_vars_succeed', "$BASESCRIPT?action=plugins&operation=edit&pluginid=$pluginid", 'succeed');
	}

}

function modulecmp($a, $b) {
	return $a['displayorder'] > $b['displayorder'] ? 1 : -1;
}

?>