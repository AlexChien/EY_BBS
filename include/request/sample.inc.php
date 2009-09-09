<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: sample.inc.php 17036 2008-12-04 03:20:00Z monkey $
*/

/*
	模块调用脚本范例程序
	最新主题调用
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

if($requestrun) {

/*
	模块脚本运行代码
	脚本中的设置参数在数组 $settings 中
	脚本中不能有输出语句，所有输出结果赋值给变量 $writedata
	脚本输出结果自动缓存，缓存时间根据数据调用模块设置而定，如不缓存，可令 $nocache = 1
*/

	$settings['fid'] = !empty($settings['sidestatus']) && $specialfid ? $specialfid : $settings['fid'];
	$limit = !empty($settings['limit']) ? intval($settings['limit']) : 10;
	$fid = !empty($settings['fid']) ? 'fid='.intval($settings['fid']) : 'fid>0';

	$query = $db->query("SELECT tid, subject FROM {$tablepre}threads WHERE $fid AND displayorder>=0 ORDER BY dateline DESC LIMIT $limit");

	$writedata = '<ul>';
	while($thread = $db->fetch_array($query)) {
		$writedata .= "
			<li>
			<a href=\"{$boardurl}viewthread.php?tid=$thread[tid]\" target=\"_blank\">$thread[subject]</a>
			</li>
		";
	}
	$writedata .= '</ul>';

} else {

/*
	模块脚本设置参数
	
	版本 $request_version
	名称 $request_name
	描述 $request_description
	版权 $request_copyright
	参数 $request_settings

	变量名 => array(变量含义, 备注说明, 类型, 选项数组, 默认值)
	类型:
		text		单行文本
		textarea	多行文本
		radio		单选(是/否)
		mradio		自定义单选
		mcheckbox	多选
		select		单选下拉
		mselect		多选下拉
*/

	$request_version = '1.0';
	$request_name = '模块调用脚本范例';
	$request_description = '最新主题调用范例，您可以参照本脚本 ./include/request/sample.inc.php 中的说明编写模块脚本';
	$request_copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';
	$request_settings = array(
		'limit' 	=> array('返回条目数', '设置返回的条目数', 'text'),
		'fid' 		=> array('选择版块', '选择显示哪个版块的帖子', 'select', array()),
		'sidestatus' 	=> array('主题列表页面(forumdisplay.php)专用', '设置此数据调用模块为主题列表页面(forumdisplay.php)的专用模块，只调用当前版块的内容', 'radio'),
	);

	include DISCUZ_ROOT.'./forumdata/cache/cache_forums.php';
	$settings['fid'][3][] = array(0, '');
	foreach($_DCACHE['forums'] as $fid => $forum) {
		$settings['fid'][3][] = array($fid, $forum['name']);
	}

}

?>