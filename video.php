<?

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: my.php 10920 2007-10-19 00:53:10Z monkey $
*/

define('NOROBOT', TRUE);
define('CURSCRIPT', 'video');
require_once './include/common.inc.php';
require_once DISCUZ_ROOT.'./api/video.php';

if($action == 'player') {
	
	$ivid = $ivid ? $ivid : $vid;
	$tid = $db->result_first("SELECT tid FROM {$tablepre}videos WHERE vid='$ivid'");
	$tid = $tid ? intval($tid) : '';
	dheader("Location: {$boardurl}viewthread.php?tid=$tid&vid=$ivid");

} elseif($action == 'updatevideoinfo') {

	$db->query("UPDATE {$tablepre}videos SET vview=vview+'1' WHERE vid='$vid'");
	showmessage('ok');

} elseif($action == 'flv') {	

	$client = new VideoClient_Util($appid, $siteid, $sitekey);
	$flvurl = $client->createReferPlayer(array('ivid' => $ivid, 'm' => 'play', 'site' => $boardurl));
	dheader("Location: $flvurl");

} else {

	showmessage('undefined_action', NULL, 'HALTED');

}

?>