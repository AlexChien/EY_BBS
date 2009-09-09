<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: magic_color.inc.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(submitcheck('usesubmit')) {

	if(empty($highlight_color)) {
		showmessage('magics_info_nonexistence');
	}

	$thread = getpostinfo($tid, 'tid', array('fid'));
	checkmagicperm($magicperm['forum'], $thread['fid']);
	magicthreadmod($tid);

	$db->query("UPDATE {$tablepre}threads SET highlight='$highlight_color', moderated='1' WHERE tid='$tid'", 'UNBUFFERED');
	$expiration = $timestamp + 86400;

	usemagic($magicid, $magic['num']);
	updatemagiclog($magicid, '2', '1', '0', $tid);
	updatemagicthreadlog($tid, $magicid, $magic['identifier'], $expiration);
	showmessage('magics_operation_succeed', '', 1);

}

function showmagic() {
	global $tid, $lang;
	magicshowsetting('', '', '', '
	<table cellspacing="0" cellpadding="2" style="margin-top:-20px;">
		<tr>
			<td>'.$lang['target_tid'].'</td>
			<td>'.$lang['CCK_color'].'</td>
		</tr>
		<tr>
			<td><input type="text" value="'.$tid.'" name="tid" size="12" class="txt" /></td>
			<td class="hasdropdownbtn" style="position: relative;">
				<input type="hidden" id="highlight_color" name="highlight_color" />
				<input type="text" readonly="readonly" class="txt" id="color_bg" style="width: 18px; border-right: none;" />
				<a class="dropdownbtn" onclick="display(\'color_menu\')" href="javascript:;">^</a>
				<div id="color_menu" class="color_menu" style="display: none; clear: both; left: auto; top: auto;">
					<a href="javascript:;" onclick="switchhl(this, 1)" style="float:left;background:#EE1B2E;color:#EE1B2E;">#EE1B2E</a>
					<a href="javascript:;" onclick="switchhl(this, 2)" style="float:left;background:#EE5023;color:#EE5023;">#EE5023</a>
					<a href="javascript:;" onclick="switchhl(this, 3)" style="float:left;background:#996600;color:#996600;">#996600</a>
					<a href="javascript:;" onclick="switchhl(this, 4)" style="float:left;background:#3C9D40;color:#3C9D40;">#3C9D40</a>
					<a href="javascript:;" onclick="switchhl(this, 5)" style="float:left;background:#2897C5;color:#2897C5;">#2897C5</a>
					<a href="javascript:;" onclick="switchhl(this, 6)" style="float:left;background:#2B65B7;color:#2B65B7;">#2B65B7</a>
					<a href="javascript:;" onclick="switchhl(this, 7)" style="float:left;background:#8F2A90;color:#8F2A90;">#8F2A90</a>
					<a href="javascript:;" onclick="switchhl(this, 8)" style="float:left;background:#EC1282;color:#EC1282;">#EC1282</a>
				</div>
	 		</td>
	 	</tr>
	</table>
	<script type="text/javascript" reload="1">
		function switchhl(obj, v) {
			$(\'highlight_color\').value = v;
			$(\'color_bg\').style.backgroundColor = obj.style.backgroundColor;
			$(\'color_menu\').style.display = \'none\';
		}
	</script>
	');
}

?>