<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: video.inc.php 16993 2008-12-02 00:58:13Z liuqiang $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

cpheader();

$video = unserialize($db->result_first("SELECT value FROM {$tablepre}settings WHERE variable='videoinfo'"));

if($operation == 'config') {

	if(empty($video['authkey'])) {
		cpmsg('insenz_invalidvideo');
	}

	$video['bbname'] = $video['bbname'] ? $video['bbname'] : $bbname;
	$video['url'] = $video['url'] ? $video['url'] : $boardurl;
	$videonew['sitetype'] = $video['sitetype'];

	$sitetypeselect = $br = '';
	if($sitetypearray = explode("\t", $video['sitetype'])) {
		foreach($sitetypearray as $key => $sitetype) {
			$br = ($key + 1) % 6 == 0 ? '<br />' : '';
			$selected = $video['type'] == $key + 1 ? 'checked' : '';
			$sitetypeselect .= '<input type="radio" class="radio" name="videonew[type]" value="'.($key + 1).'" '.$selected.'> '.$sitetype.'&nbsp;&nbsp;&nbsp;'.$br;
		}
	}

	if(!submitcheck('configsubmit')) {

		shownav('extended', 'nav_video');
		showsubmenu('nav_video', array(
			array('nav_video_config', 'video&operation=config', 1),
			array('nav_video_class', 'video&operation=class', 0)
		));
		showtips('video_tips');
		showformheader('video&operation=config');
		showtableheader();
		showsetting('video_open', 'videonew[open]', $video['open'], 'radio');
		showsetting('video_site_name', 'videonew[bbname]', $video['bbname'], 'text');
		showsetting('video_site_url', 'videonew[url]', $video['url'], 'text');
		showsetting('video_site_email', 'videonew[email]', $video['email'], 'text');
		showsetting('video_site_logo', 'videonew[logo]', $video['logo'], 'text');
		showsetting('video_site_type', array('videonew[sitetype]', array(
			array(0, $lang['video_site_type_none']),
			array(1, $lang['video_site_type_1']),
			array(2, $lang['video_site_type_2']),
			array(3, $lang['video_site_type_3']),
			array(5, $lang['video_site_type_5']),
			array(6, $lang['video_site_type_6']),
			array(7, $lang['video_site_type_7']),
			array(8, $lang['video_site_type_8']),
			array(9, $lang['video_site_type_9']),
			array(10, $lang['video_site_type_10']),
			array(11, $lang['video_site_type_11']),
			array(12, $lang['video_site_type_12']),
			array(13, $lang['video_site_type_13']),
			array(14, $lang['video_site_type_14']),
			array(15, $lang['video_site_type_15']),
			array(16, $lang['video_site_type_16']),
			array(17, $lang['video_site_type_17']),
			array(18, $lang['video_site_type_18']),
			array(19, $lang['video_site_type_19']),
			array(20, $lang['video_site_type_20']),
			array(21, $lang['video_site_type_21']),
			array(22, $lang['video_site_type_22']),
			array(23, $lang['video_site_type_23']),
			array(24, $lang['video_site_type_24']),
			array(25, $lang['video_site_type_25']),
			array(26, $lang['video_site_type_26']),
			array(27, $lang['video_site_type_27']),
			array(28, $lang['video_site_type_28']),
			array(29, $lang['video_site_type_29']),
			array(30, $lang['video_site_type_30']),
			array(31, $lang['video_site_type_31']),
			array(32, $lang['video_site_type_32']),
			array(33, $lang['video_site_type_33']),
			array(34, $lang['video_site_type_34']),
			array(35, $lang['video_site_type_35']),
			array(36, $lang['video_site_type_36']),
			array(37, $lang['video_site_type_37']),
			array(38, $lang['video_site_type_38']),
			array(39, $lang['video_site_type_39']),
			array(40, $lang['video_site_type_40']),
			array(41, $lang['video_site_type_41'])
		)), $video['sitetype'], 'select');
		showsubmit('configsubmit');
		showtablefooter();
		showformfooter();

	} else {

		require_once DISCUZ_ROOT.'./include/insenz.func.php';
		require_once DISCUZ_ROOT.'./api/video.php';

		$videoAccount = new VideoClient_SiteService($appid, $video['siteid'], $video['authkey']);
		$result = $videoAccount->edit(insenz_convert($videonew['bbname']), $videonew['url'], $videonew['logo'], $videonew['sitetype']);

		if ($result->isError()) {

			cpmsg(insenz_convert($result->getMessage(), 0), '', 'error');

		} else {

			$video['open'] = intval($videonew['open']);
			$video['bbname'] = $videonew['bbname'];
			$video['url'] = $videonew['url'];
			$video['email'] = $videonew['email'];
			$video['logo'] = $videonew['logo'];
			$video['sitetype'] = $videonew['sitetype'];
			$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('videoinfo', '".addslashes(serialize($video))."')");
			updatecache('settings');
			cpmsg('video_config_succeed', $BASESCRIPT.'?action=video&operation=config', 'succeed');

		}

	}

} elseif($operation == 'class') {

	$videodefault = array(
			1 => array('name' => $lang['video_video_type_1']),
			5 => array('name' => $lang['video_video_type_5']),
			7 => array('name' => $lang['video_video_type_7']),
			8 => array('name' => $lang['video_video_type_8']),
			11 => array('name' => $lang['video_video_type_11']),
			12 => array('name' => $lang['video_video_type_12']),
			14 => array('name' => $lang['video_video_type_14']),
			15 => array('name' => $lang['video_video_type_15']),
			16 => array('name' => $lang['video_video_type_16']),
			18 => array('name' => $lang['video_video_type_18']),
			19 => array('name' => $lang['video_video_type_19']),
			21 => array('name' => $lang['video_video_type_21']),
			22 => array('name' => $lang['video_video_type_22']),
			23 => array('name' => $lang['video_video_type_23']),
			24 => array('name' => $lang['video_video_type_24']),
			25 => array('name' => $lang['video_video_type_25']),
			26 => array('name' => $lang['video_video_type_26']),
			27 => array('name' => $lang['video_video_type_27']),
			28 => array('name' => $lang['video_video_type_28']),
			29 => array('name' => $lang['video_video_type_29']),
			30 => array('name' => $lang['video_video_type_30']),
			31 => array('name' => $lang['video_video_type_31']),
			32 => array('name' => $lang['video_video_type_32'])
			);

	if(!submitcheck('classsubmit')) {
		$videotype = !$video['videotype'] ? $videodefault : $video['videotype'];
		$videotypelist = '<ul class="dblist" onmouseover="altStyle(this);">';
		foreach($videotype as $id => $type) {
			$checked = $type['able'] ? ' checked="checked"' : '';
			$videotypelist .= '<li><input type="checkbox" name="videotypenew['.$id.'][able]" class="radio"'.$checked.' value="1"> <input type="text" class="txt" name="videotypenew['.$id.'][name]" value="'.$type['name'].'" size="8"></li>';
		}
		$videotypelist .= '</ul>';
		shownav('extended', 'nav_video');
		showsubmenu('nav_video', array(
			array('nav_video_config', 'video&operation=config', 0),
			array('nav_video_class', 'video&operation=class', 1)
		));
		showformheader('video&operation=class');
		showtableheader();
		showtablerow('', 'class="td27"', lang('video_class').'('.lang('video_class_comment').')');
		showtablerow('', '', $videotypelist);
		showsubmit('classsubmit');
		showtablefooter();
		showformfooter();

	} else {

		$video['videotype'] = $videotypenew;
		$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('videoinfo', '".addslashes(serialize($video))."')");
		updatecache('settings');
		cpmsg('video_class_update_succeed', $BASESCRIPT.'?action=video&operation=class', 'succeed');
	}

}

?>