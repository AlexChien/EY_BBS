<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: admingroups.inc.php 17415 2008-12-19 04:34:58Z liuqiang $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

cpheader();

if(!$operation) {
	
	if(submitcheck('groupsubmit') && $ids = implodeids($delete)) {
		$gids = array();
		$query = $db->query("SELECT groupid FROM {$tablepre}usergroups WHERE groupid IN ($ids) AND type='special' AND radminid>'0'");
		while($g = $db->fetch_array($query)) {
			$gids[] = $g['groupid'];
		}
		if($ids = implodeids($gids)) {
			$db->query("DELETE FROM {$tablepre}usergroups WHERE groupid IN ($ids)");
			$db->query("DELETE FROM {$tablepre}admingroups WHERE admingid IN ($ids)");
			$db->query("DELETE FROM {$tablepre}adminactions WHERE admingid IN ($ids)");
			$newgroupid = $db->result_first("SELECT groupid FROM {$tablepre}usergroups WHERE type='member' AND creditslower>'0' ORDER BY creditslower LIMIT 1");
			$db->query("UPDATE {$tablepre}members SET groupid='$newgroupid', adminid='0' WHERE groupid IN ($ids)", 'UNBUFFERED');
			deletegroupcache($gids);
		}
	}
	
	$grouplist = array();
	$query = $db->query("SELECT a.*, u.groupid, u.radminid, u.grouptitle, u.stars, u.color, u.groupavatar, u.type FROM {$tablepre}admingroups a
			LEFT JOIN {$tablepre}usergroups u ON u.groupid=a.admingid
			ORDER BY u.type, u.radminid, a.admingid");
	while ($group = $db->fetch_array($query)) {
		$grouplist[$group['groupid']] = $group;
	}
	
	if(!submitcheck('groupsubmit')) {

		shownav('user', 'nav_admingroups');
		showsubmenu('nav_admingroups');
		showtips('admingroups_tips');

		showformheader('admingroups');
		showtableheader('', 'fixpadding');
		showsubtitle(array('', 'name', 'type', 'admingroups_level', 'usergroups_stars', 'usergroups_color', 'usergroups_avatar', '', ''));

		foreach ($grouplist as $gid => $group) {
			showtablerow('', array('', '', 'class="td25"', '', 'class="td25"'), array(
				$group['type'] == 'system' ? '' : "<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$group[groupid]\">",
				$group['grouptitle'],
				$group['type'] == 'system' ? lang('inbuilt') : lang('costom'),
				$lang['usergroups_system_'.$group['radminid']],
				"<input type=\"text\" class=\"txt\" size=\"2\"name=\"group_stars[$group[groupid]]\" value=\"$group[stars]\">",
				"<input type=\"text\" class=\"txt\" size=\"6\"name=\"group_color[$group[groupid]]\" value=\"$group[color]\">",
				"<input type=\"text\" class=\"txt\" size=\"12\" name=\"group_avatar[$group[groupid]]\" value=\"$group[groupavatar]\">",
				"<a href=\"$BASESCRIPT?action=usergroups&operation=edit&id={$group[admingid]}&return=admin\">$lang[admingroups_settings_user]</a>",
				"<a href=\"$BASESCRIPT?action=admingroups&operation=edit&id=$group[admingid]\">$lang[admingroups_settings_admin]</a>"
			));
		}

		showtablerow('', array('class="td25"', '', '', 'colspan="6"'), array(
			lang('add_new'),
			'<input type="text" class="txt" size="12" name="grouptitlenew">',
			lang('costom'),
			"<select name=\"radminidnew\"><option value=\"1\">$lang[usergroups_system_1]</option><option value=\"2\">$lang[usergroups_system_2]</option><option value=\"3\" selected=\"selected\">$lang[usergroups_system_3]</option>",
		));
		showsubmit('groupsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {
		
		foreach ($grouplist as $gid => $group) {
			$stars = intval($group_stars[$gid]);
			$color = dhtmlspecialchars($group_color[$gid]);
			$avatar = dhtmlspecialchars($group_avatar[$gid]);
			if($group['color'] != $color || $group['stars'] != $stars || $group['groupavatar'] != $avatar) {
				$db->query("UPDATE {$tablepre}usergroups SET stars='$stars', color='$color', groupavatar='$avatar' WHERE groupid='$gid'");
			}
		}
		
		$grouptitlenew = dhtmlspecialchars(trim($grouptitlenew));
		$radminidnew = intval($radminidnew);
		
		if($grouptitlenew && in_array($radminidnew, array(1, 2, 3))) {

			$ufields = '';
			$usergroup = $db->fetch_first("SELECT * FROM {$tablepre}usergroups WHERE groupid='$radminidnew'");
			foreach ($usergroup as $key => $val) {
				if(!in_array($key, array('groupid', 'radminid', 'type', 'system', 'grouptitle'))) {
					$val = addslashes($val);
					$ufields .= ", `$key`='$val'";
				}
			}
			
			$afields = '';
			$admingroup = $db->fetch_first("SELECT * FROM {$tablepre}admingroups WHERE admingid='$radminidnew'");
			foreach ($admingroup as $key => $val) {
				if(!in_array($key, array('admingid'))) {
					$val = addslashes($val);
					$afields .= ", `$key`='$val'";
				}
			}
			
			$db->query("INSERT INTO {$tablepre}usergroups SET radminid='$radminidnew', type='special', grouptitle='$grouptitlenew' $ufields");
			if($newgroupid = $db->insert_id()) {
				$db->query("REPLACE INTO {$tablepre}admingroups SET admingid='$newgroupid' $afields");
				if($radminidnew == 1) {
					$dactionarray = array('members_edit', 'members_group', 'db_runquery', 'db_import', 'usergroups', 'admingroups', 'templates', 'plugins');
					$db->query("REPLACE INTO {$tablepre}adminactions (admingid, disabledactions)
						VALUES ('$newgroupid', '".addslashes(serialize($dactionarray))."')");
				}
			}
		}

		cpmsg('admingroups_edit_succeed', $BASESCRIPT.'?action=admingroups', 'succeed');

	}

} elseif($operation == 'edit') {

	$actionarray = array(
		'settings' => array('basic', 'access', 'seo', 'functions', 'permissions', 'credits', 'mail', 'sec', 'datetime', 'attach', 'wap', 'uc', 'uchome'),
		'forums' => array('edit', 'moderators', 'delete', 'merge', 'copy'),
		'threadtypes' => array(),
		'members' => array('add', 'group', 'access', 'credit', 'medal', 'edit', 'ban', 'ipban', 'reward', 'newsletter', 'confermedal', 'clean'),
		'profilefields' => array(),
		'usergroups' => array(),
		'admingroups' => array(),
		'ranks' => array(),
		'styles' => array(),
		'templates' => array('add', 'edit'),
		'moderate' => array('members', 'threads', 'replies'),
		'threads' => array(),
		'prune' => array(),
		'recyclebin' => array(),
		'announce' => array(),
		'smilies' => array(),
		'misc' => array('link', 'onlinelist', 'censor', 'bbcode', 'tag', 'icon', 'attachtype', 'cron'),
		'adv' => array('config', 'advadd'),
		'db' => array('runquery', 'optimize', 'export', 'import', 'dbcheck'),
		'tools' => array('updatecache', 'fileperms', 'relatedtag'),
		'attach' => array(),
		'counter' => array(),
		'jswizard' => array(),
		'creditwizard' => array(),
		'google' => array('config'),
		'qihoo' => array('config', 'topics'),
		'tasks' => array(),
		'ec' => array('alipay', 'orders', 'credit'),
		'medals' => array(),
		'plugins' => array('config', 'edit', 'hooks', 'vars'),
		'logs' => array('illegal', 'rate', 'mods', 'medals', 'ban', 'cp', 'credits', 'error')
	);

	if(!submitcheck('groupsubmit')) {

		$id = isset($id) ? intval($id) : 0;

		if(empty($id)) {
			$grouplist = "<select name=\"id\" style=\"width: 150px\">\n";
			$query = $db->query("SELECT groupid, grouptitle FROM {$tablepre}usergroups WHERE radminid>'1' OR (radminid='1' AND type='special')");
			while($group = $db->fetch_array($query)) {
				$grouplist .= "<option value=\"$group[groupid]\">$group[grouptitle]</option>\n";
			}
			$grouplist .= '</select>';
			cpmsg('admingroups_edit_nonexistence', $BASESCRIPT.'?action=admingroups&operation=edit'.(!empty($highlight) ? "&highlight=$highlight" : ''), 'form', $grouplist);
		}

		$group = $db->fetch_first("SELECT a.*, aa.disabledactions, u.radminid, u.grouptitle FROM {$tablepre}admingroups a
			LEFT JOIN {$tablepre}usergroups u ON u.groupid=a.admingid
			LEFT JOIN {$tablepre}adminactions aa ON aa.admingid=a.admingid
			WHERE a.admingid='$id'");

		if(!$group) {
			cpmsg('undefined_action', '', 'error');
		}

		showsubmenu($lang['admingroups_edit'].' - '.$group['grouptitle']);
		showformheader("admingroups&operation=edit&id=$id");
		showtableheader();

		if($group['radminid'] == 1) {

			$group['disabledactions'] = $group['disabledactions'] ? unserialize($group['disabledactions']) : array();

			foreach($actionarray as $actionstr => $operationstr) {
				showsetting('admingroups_edit_action_'.$actionstr, 'disabledactionnew['.$actionstr.']', !in_array($actionstr, $group['disabledactions']), 'radio', $id == 1, $operationstr);
				foreach($operationstr as $opstr) {
					$str = $actionstr.'_'.$opstr;
					showsetting('admingroups_edit_action_'.$str, 'disabledactionnew['.$str.']', !in_array($str, $group['disabledactions']), 'radio', $id == 1);
				}
				$operationstr && showtagfooter('tbody');
			}

		} else {

			$checkstick = array($group['allowstickthread'] => 'checked');

			showsetting('admingroups_edit_edit_post', 'alloweditpostnew', $group['alloweditpost'], 'radio');
			showsetting('admingroups_edit_edit_poll', 'alloweditpollnew', $group['alloweditpoll'], 'radio');
			showsetting('admingroups_edit_stick_thread', '', '', '<input class="radio" type="radio" name="allowstickthreadnew" value="0" '.$checkstick[0].'> '.$lang['admingroups_edit_stick_thread_none'].'<br /><input class="radio" type="radio" name="allowstickthreadnew" value="1" '.$checkstick[1].'> '.$lang['admingroups_edit_stick_thread_1'].'<br /><input class="radio" type="radio" name="allowstickthreadnew" value="2" '.$checkstick[2].'> '.$lang['admingroups_edit_stick_thread_2'].'<br /><input class="radio" type="radio" name="allowstickthreadnew" value="3" '.$checkstick[3].'> '.$lang['admingroups_edit_stick_thread_3'].'');
			showsetting('admingroups_edit_mod_post', 'allowmodpostnew', $group['allowmodpost'], 'radio');
			showsetting('admingroups_edit_del_post', 'allowdelpostnew', $group['allowdelpost'], 'radio');
			showsetting('admingroups_edit_mass_prune', 'allowmassprunenew', $group['allowmassprune'], 'radio');
			showsetting('admingroups_edit_ban_post', 'allowbanpostnew', $group['allowbanpost'], 'radio');
			showsetting('admingroups_edit_refund', 'allowrefundnew', $group['allowrefund'], 'radio');
			showsetting('admingroups_edit_view_ip', 'allowviewipnew', $group['allowviewip'], 'radio');
			showsetting('admingroups_edit_ban_ip', 'allowbanipnew', $group['allowbanip'], 'radio');
			showsetting('admingroups_edit_edit_user', 'alloweditusernew', $group['allowedituser'], 'radio');
			showsetting('admingroups_edit_mod_user', 'allowmodusernew', $group['allowmoduser'], 'radio');
			showsetting('admingroups_edit_ban_user', 'allowbanusernew', $group['allowbanuser'], 'radio');
			showsetting('admingroups_edit_post_announce', 'allowpostannouncenew', $group['allowpostannounce'], 'radio');
			showsetting('admingroups_edit_disable_postctrl', 'disablepostctrlnew', $group['disablepostctrl'], 'radio');

		}

		if($id > 1) {
			showsubmit('groupsubmit');
		}
		showtablefooter();
		showformfooter();

	} else {

		$group = $db->fetch_first("SELECT groupid, radminid FROM {$tablepre}usergroups WHERE groupid='$id'");
		if(!$group) {
			cpmsg('undefined_action', '', 'error');
		}

		if($group['radminid'] == 1) {

			$actions = array();
			foreach ($actionarray as $key => $val) {
				$actions[] = $key;
				if(!empty($val) && is_array($val)) {
					foreach ($val as $temp) {
						$actions[] = "{$key}_{$temp}";
					}
				}
			}

			$dactionarray = array();
			if(is_array($disabledactionnew)) {
				foreach($disabledactionnew as $key => $value) {
					if(in_array($key, $actions) && !$value) {
						$dactionarray[] = $key;
					}
				}
			}

			$db->query("REPLACE INTO {$tablepre}adminactions (admingid, disabledactions)
				VALUES ('$group[groupid]', '".addslashes(serialize($dactionarray))."')");

		} else {

			$db->query("UPDATE {$tablepre}admingroups SET alloweditpost='$alloweditpostnew', alloweditpoll='$alloweditpollnew',
				allowstickthread='$allowstickthreadnew', allowmodpost='$allowmodpostnew', allowbanpost='$allowbanpostnew', allowdelpost='$allowdelpostnew',
				allowmassprune='$allowmassprunenew', allowrefund='$allowrefundnew', allowcensorword='$allowcensorwordnew',
				allowviewip='$allowviewipnew', allowbanip='$allowbanipnew', allowedituser='$alloweditusernew', allowbanuser='$allowbanusernew',
				allowmoduser='$allowmodusernew', allowpostannounce='$allowpostannouncenew', allowviewlog='$allowviewlognew',
				disablepostctrl='$disablepostctrlnew' WHERE admingid='$group[groupid]' AND admingid<>'1'");

		}

		updatecache('usergroups');
		updatecache('admingroups');
		cpmsg('admingroups_edit_succeed', $BASESCRIPT.'?action=admingroups', 'succeed');
	}
}

function deletegroupcache($groupidarray) {
	if(!empty($groupidarray) && is_array($groupidarray)) {
		foreach ($groupidarray as $id) {
			if(is_numeric($id) && $id = intval($id)) {
				@unlink(DISCUZ_ROOT.'./forumdata/cache/usergroup_'.$id.'.php');
				@unlink(DISCUZ_ROOT.'./forumdata/cache/admingroup_'.$id.'.php');
			}
		}
	}
}

?>