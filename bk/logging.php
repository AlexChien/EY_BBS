<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: logging.php 17399 2008-12-17 09:13:08Z monkey $
*/

define('NOROBOT', TRUE);
define('CURSCRIPT', 'logging');

require_once './include/common.inc.php';
require_once DISCUZ_ROOT.'./include/misc.func.php';
require_once DISCUZ_ROOT.'./uc_client/client.php';


if($action == 'logout' && !empty($formhash)) {

	if($_DCACHE['settings']['frameon'] && $_DCOOKIE['frameon'] == 'yes') {
		$extrahead .= '<script>if(top != self) {parent.leftmenu.location.reload();}</script>';
	}

	if($formhash != FORMHASH) {
		showmessage('logout_succeed', dreferer());
	}

	$ucsynlogout = $allowsynlogin ? uc_user_synlogout() : '';

	clearcookies();
	$groupid = 7;
	$discuz_uid = 0;
	$discuz_user = $discuz_pw = '';
	$styleid = $_DCACHE['settings']['styleid'];

	showmessage('logout_succeed', dreferer());

} elseif($action == 'login') {

	if($discuz_uid) {
		$ucsynlogin = '';
		showmessage('login_succeed', $indexname);
	}

	$field = $loginfield == 'uid' ? 'uid' : 'username';

	if(!($loginperm = logincheck())) {
		showmessage('login_strike');
	}

	$seccodecheck = $seccodestatus & 2;

	if($seccodecheck && $seccodedata['loginfailedcount']) {
		$seccodecheck = $db->result_first("SELECT count(*) FROM {$tablepre}failedlogins WHERE ip='$onlineip' AND count>='$seccodedata[loginfailedcount]' AND $timestamp-lastupdate<=900");
	}

	if(!submitcheck('loginsubmit', 1, $seccodecheck)) {

		$discuz_action = 6;

		$referer = dreferer();

		$thetimenow = '(GMT '.($timeoffset > 0 ? '+' : '').$timeoffset.') '.
			dgmdate("$dateformat $timeformat", $timestamp + $timeoffset * 3600).

		$styleselect = '';
		$query = $db->query("SELECT styleid, name FROM {$tablepre}styles WHERE available='1'");
		while($styleinfo = $db->fetch_array($query)) {
			$styleselect .= "<option value=\"$styleinfo[styleid]\">$styleinfo[name]</option>\n";
		}

		$cookietimecheck = !empty($_DCOOKIE['cookietime']) ? 'checked="checked"' : '';

		if($seccodecheck) {
			$seccode = random(6, 1) + $seccode{0} * 1000000;
		}

		$username = !empty($_DCOOKIE['loginuser']) ? htmlspecialchars($_DCOOKIE['loginuser']) : '';
		include template('login');

	} else {

		if(isset($loginauth)) {
			list($username, $password) = daddslashes(explode("\t", authcode($loginauth, 'DECODE')), 1);
		}

		$ucresult = uc_user_login($username, $password, $loginfield == 'uid', 1, $questionid, $answer);
		list($tmp['uid'], $tmp['username'], $tmp['password'], $tmp['email'], $duplicate) = daddslashes($ucresult, 1);
		$ucresult = $tmp;

		if($duplicate && $ucresult['uid'] > 0) {
			if($olduid = $db->result_first("SELECT uid FROM {$tablepre}members WHERE username='".addslashes($ucresult['username'])."'")) {
				require_once DISCUZ_ROOT.'./include/membermerge.func.php';
				membermerge($olduid, $ucresult['uid']);
				uc_user_merge_remove($ucresult['username']);
			} else {
				$ucresult['uid'] = -1;
			}
		}

		$discuz_uid = 0;
		$discuz_user = $discuz_pw = $discuz_secques = '';
		$member = array();

		if($ucresult['uid'] > 0) {

			$member = $db->fetch_first("SELECT m.uid AS discuz_uid, m.username AS discuz_user, m.password AS discuz_pw, m.secques AS discuz_secques,
				m.email, m.adminid, m.groupid, m.styleid AS styleidmem, m.lastvisit, m.lastpost, u.allowinvisible
				FROM {$tablepre}members m LEFT JOIN {$tablepre}usergroups u USING (groupid)
				WHERE m.uid='$ucresult[uid]'");

			if(!$member) {
				$ucresult['username'] = addslashes($ucresult['username']);
				$auth = authcode("$ucresult[username]\t".FORMHASH, 'ENCODE');
				if($inajax) {
					$message = 2;
					$location = $regname.'?action=activation&auth='.rawurlencode($auth);
					include template('login');
				} else {
					showmessage('login_activation', $regname.'?action=activation&auth='.rawurlencode($auth));
				}
			}

			extract($member);

			$discuz_userss = $discuz_user;
			$discuz_user = addslashes($discuz_user);

			if(addslashes($email) != $ucresult['email']) {
				$db->query("UPDATE {$tablepre}members SET email='$ucresult[email]' WHERE uid='$ucresult[uid]'");
			}

			if($questionid > 0 && empty($discuz_secques)) {
				$discuz_secques = random(8);
				$db->query("UPDATE {$tablepre}members SET secques='$discuz_secques' WHERE uid='$ucresult[uid]'");
			}

			$styleid = intval(empty($_POST['styleid']) ? ($styleidmem ? $styleidmem :
					$_DCACHE['settings']['styleid']) : $_POST['styleid']);

			$cookietime = intval(isset($_POST['cookietime']) ? $_POST['cookietime'] : 0);

			dsetcookie('cookietime', $cookietime, 31536000);
			dsetcookie('auth', authcode("$discuz_pw\t$discuz_secques\t$discuz_uid", 'ENCODE'), $cookietime, 1, true);
			dsetcookie('loginuser');
			dsetcookie('activationauth');
			dsetcookie('pmnum');

			$sessionexists = 0;

			if($_DCACHE['settings']['frameon'] && $_DCOOKIE['frameon'] == 'yes') {
				$extrahead .= '<script>if(top != self) {parent.leftmenu.location.reload();}</script>';
			}

			$ucsynlogin = $allowsynlogin ? uc_user_synlogin($discuz_uid) : '';

			if(!empty($inajax)) {
				$msgforward = unserialize($msgforward);
				$mrefreshtime = intval($msgforward['refreshtime']) * 1000;
				include_once DISCUZ_ROOT.'./forumdata/cache/cache_usergroups.php';
				$usergroups = $_DCACHE['usergroups'][$groupid]['grouptitle'];
				$message = 1;
				include template('login');
			} else {
				if($groupid == 8) {
					showmessage('login_succeed_inactive_member', 'memcp.php');
				} else {
					showmessage('login_succeed', dreferer());
				}
			}

		} else {

			$password = preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
			$errorlog = dhtmlspecialchars(
				$timestamp."\t".
				($ucresult['username'] ? $ucresult['username'] : stripslashes($username))."\t".
				$password."\t".
				($secques ? "Ques #".intval($questionid) : '')."\t".
				$onlineip);
			writelog('illegallog', $errorlog);
			loginfailed($loginperm);
			$fmsg = $ucresult['uid'] == '-3' ? (empty($questionid) || $answer == '' ? 'login_question_empty' : 'login_question_invalid') : 'login_invalid';
			showmessage($fmsg, 'logging.php?action=login');
		}

	}

} else {
	showmessage('undefined_action');
}

?>