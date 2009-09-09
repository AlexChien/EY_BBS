<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: menu.inc.php 17261 2008-12-11 07:21:49Z liuqiang $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

showmenu('global', array(
	array('menu_settings_basic', 'settings&operation=basic'),
	array('menu_settings_access', 'settings&operation=access'),
	array('menu_settings_optimize', 'settings&operation=seo'),
	array('menu_settings_functions', 'settings&operation=functions'),
	array('menu_settings_user', 'settings&operation=permissions'),
	array('menu_settings_credits', 'settings&operation=credits'),
	$isfounder ? array('menu_settings_mail', 'settings&operation=mail') :array(),
	array('menu_settings_sec', 'settings&operation=sec'),
	array('menu_settings_datetime', 'settings&operation=datetime'),
	array('menu_settings_attachments', 'settings&operation=attach'),
	array('menu_settings_wap', 'settings&operation=wap'),
	$isfounder ? array('menu_settings_uc', 'settings&operation=uc') : array(),
	array('menu_settings_uchome', 'settings&operation=uchome'),
));
showmenu('forum', array(
	array('menu_forums', 'forums'),
	array('menu_forums_merge', 'forums&operation=merge'),
	array('menu_forums_threadtypes', 'threadtypes'),
	array('menu_forums_infotypes', 'threadtypes&special=1'),
	array('menu_forums_infomodel', 'threadtypes&operation=typemodel'),
	array('menu_forums_infooption', 'threadtypes&operation=typeoption')
));
showmenu('user', array(
	array('menu_members_add', 'members&operation=add'),
	array('menu_members_edit', 'members'),
	array('menu_members_edit_ban_user', 'members&operation=ban'),
	array('menu_members_ipban', 'members&operation=ipban'),
	array('menu_members_credits', 'members&operation=reward'),
	array('menu_moderate_modmembers', 'moderate&operation=members'),
	array('menu_profilefields', 'profilefields'),
	array('menu_admingroups', 'admingroups'),
	array('menu_usergroups', 'usergroups'),
	array('menu_ranks', 'ranks')
));
showmenu('topic', array(
	array('menu_moderate_posts', 'moderate&operation=threads'),
	array('menu_maint_threads', 'threads'),
	array('menu_maint_prune', 'prune'),
	array('menu_maint_attaches', 'attach'),
	array('menu_moderate_recyclebin', 'recyclebin'),
	array('menu_posting_tags', 'misc&operation=tag'),
	array('menu_posting_censors', 'misc&operation=censor'),
	array('menu_posting_attachtypes', 'misc&operation=attachtype')
));
showmenu('extended', array(
	array('menu_plugins', 'plugins&operation=config'),
	array('menu_tasks', 'tasks'),
	array('menu_magics', 'magics&operation=config'),
	array('menu_medals', 'medals'),
	array('menu_tools_relatedtag', 'tools&operation=tag'),
	array('menu_misc_help', 'faq&operation=list'),
	array('menu_qihoo', 'qihoo&operation=config'),
	array('menu_video', 'video&operation=config'),
	array('menu_ec', 'settings&operation=ec'),
	array('menu_settings_msn', 'settings&operation=msn')
));
showmenu('style', array(
	array('menu_settings_styles', 'settings&operation=styles'),
	array('menu_styles', 'styles'),
	$isfounder ? array('menu_styles_templates', 'templates') : array(),
	array('menu_posting_smilies', 'smilies'),
	array('menu_thread_icon', 'misc&operation=icon'),
	array('menu_posting_editor', 'settings&operation=editor'),
	array('menu_misc_onlinelist', 'misc&operation=onlinelist'),
));
showmenu('tool', array(
	array('menu_members_newsletter', 'members&operation=newsletter'),
	array('menu_misc_announce', 'announce'),
	array('menu_tools_updatecaches', 'tools&operation=updatecache'),
	array('menu_tools_updatecounters', 'counter'),
	array('menu_tools_javascript', 'jswizard'),
	array('menu_tools_creditwizard', 'creditwizard'),
	array('menu_forum_scheme', 'project'),
	$isfounder ? array('menu_db', 'db&operation=export') : array(),
	array('menu_logs', 'logs&operation=illegal'),
	array('menu_custommenu_manage', 'misc&operation=custommenu'),
	array('menu_misc_cron', 'misc&operation=cron'),
	array('menu_tools_fileperms', 'tools&operation=fileperms'),
	array('menu_tools_filecheck', 'checktools&operation=filecheck')
));
$insenz = ($insenz = $db->result_first("SELECT value FROM {$tablepre}settings WHERE variable='insenz'")) ? unserialize($insenz) : array();
showmenu('adv', array(
	array('menu_misc_link', 'misc&operation=link'),
	array('menu_adv_custom', 'adv'),
	array('menu_insenz_settings', 'insenz&operation=settings&do=basic'),
	array('menu_insenz_softad', 'insenz&operation=campaignlist&c_status=2'),
	$insenz['topicstatus'] ? array('menu_insenz_virtualforum', 'insenz&operation=virtualforum&c_status=2') : array(),
	array('menu_insenz_tools_myinsenz', 'http://www.insenz.com/publishers/', '_blank'),
	array('menu_insenz_tools_faq', 'http://www.insenz.com/publishers/faq/', '_blank')
));
showmenu('uc', array());
$historymenus = array(array('menu_home', 'home'));
$query = $db->query("SELECT title, url FROM {$tablepre}admincustom WHERE uid='$discuz_uid' AND sort='0' ORDER BY dateline DESC LIMIT 0, 10");
while($custom = $db->fetch_array($query)) {
	$historymenus[] = array($custom['title'], substr($custom['url'], 19));
}
if(count($historymenus) > 1) {
	$historymenus[] = array('menu_home_clearhistorymenus', 'misc&operation=custommenu&do=clean', 'main', 'class="menulink"');
}
showmenu('index', $historymenus);

?>