<?

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: plugin.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

require_once './include/common.inc.php';

$pluginmodule = isset($pluginlinks[$identifier][$module]) ? $pluginlinks[$identifier][$module] : '';

if(empty($identifier) || empty($module) || !preg_match("/^[a-z0-9_\-]+$/i", $module) || !$pluginmodule) {
	showmessage('undefined_action');
} elseif($pluginmodule['adminid'] && ($adminid < 1 || ($adminid > 0 && $pluginmodule['adminid'] < $adminid))) {
	showmessage('plugin_nopermission');
} elseif(@!file_exists(DISCUZ_ROOT.($modfile = './plugins/'.$pluginmodule['directory'].((!empty($pluginmodule['directory']) && substr($pluginmodule['directory'], -1) != '/') ? '/' : '') .$module.'.inc.php'))) {
	showmessage('plugin_module_nonexistence');
}

include DISCUZ_ROOT.$modfile;

?>