<?php
/**
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
include_once language('openid_setting');
require_once 'common.php';

if (!$discuz_uid) {
	showmessage($GLOBALS['language']['openid_login_first'], 'logging.php?action=login');
}

require_once DISCUZ_ROOT . './plugins/openid/openid.func.php';
require_once DISCUZ_ROOT . './plugins/openid/class.openid.php';

$this_url = 'plugin.php?identifier=openid4discuz&module=openid_setting';

if ($_POST['formhash'] != '' && $_POST['openid_identifier'] == '') {
	// Remove the binding between member account and openid
	//$query=$db->query("SELECT count(*) FROM {$tablepre}openid WHERE uid =".$discuz_uid);
	$query = $db->query("DELETE FROM {$tablepre}openid WHERE uid = " . $discuz_uid);
	$rows = $db->affected_rows($query);
	if ($rows) {
		showmessage($GLOBALS['language']['openid_delete_success'], $this_url);
	} else {
		showmessage($GLOBALS['language']['openid_delete_failed'], $this_url);
	}
}
elseif ($_POST['formhash'] != '' && $_POST['openid_identifier'] != '') {
	$query = $db->query("SELECT openid_url as openid_identifier FROM {$tablepre}openid WHERE uid<>".$discuz_uid
		." AND openid_url='".$_POST['openid_identifer']."'");
	$member_openid = $db->fetch_array($query);
	if ($member_openid['openid_identifier']) {
		showmessage($GLOBALS['language']['openid_bind_failed'], $this_url);
	}

	$query = $db->query("SELECT openid_url as openid_identifier FROM {$tablepre}openid WHERE uid=".$discuz_uid);
	$old_openid = $db->fetch_array($query);

	if ($old_openid['openid_identifier'] == $_POST['openid_identifier']) {
		showmessage($GLOBALS['language']['openid_update_noupdate_before']
			."<a href='".$old_openid['openid_identifier']."'>".$old_openid['openid_identifier']."</a>"
			.$GLOBALS['language']['openid_update_noupdate_after'], $this_url);
	}

	// Redirect user to OpenID Server
	session_start();
	tryAuth($openid_identifier, getReturnTo('finish_auth_openid_setting'));
	exit;
}
//elseif ($_GET['openid_mode'] == 'id_res') {
//	// Perform HTTP Request to OpenID server to validate key
//	$openid = new SimpleOpenID;
//	$openid->SetIdentity($_GET['openid_identity']);
//	$openid_validation_result = $openid->ValidateWithServer();
//	if ($openid_validation_result == true) { // OK HERE KEY IS VALID
//		$query = $db->query("SELECT openid_url FROM {$tablepre}openid WHERE uid <> " . $discuz_uid . " AND  openid_url='" . $openid->GetIdentity() . "'");
//		$member_openid = $db->fetch_array($query);
//		if ($member_openid['openid_url']) {
//			showmessage($GLOBALS['language']['openid_bind_failed'], $this_url);
//		}
//
//		$query = $db->query("SELECT openid_url FROM {$tablepre}openid WHERE uid=" . $discuz_uid);
//		$old_openid = $db->fetch_array($query);
//		if (!$old_openid['openid_url']) {
//			$db->query("INSERT {$tablepre}openid(uid, openid_url) VALUES(" . $discuz_uid . ", '" . $openid->GetIdentity() . "')");
//			showmessage($GLOBALS['language']['openid_insert_success_before'] . '<a href="' . $openid->GetIdentity() . '">' . $openid->GetIdentity() . '</a>' . $GLOBALS['language']['openid_insert_success_after'], $this_url);
//		}
//		elseif ($old_openid['openid_url'] != $openid->GetIdentity()) {
//			$query = $db->query("UPDATE {$tablepre}openid SET openid_url = '" . $openid->GetIdentity() . "' WHERE uid = " . $discuz_uid);
//			showmessage($GLOBALS['language']['openid_update_success_before'] . '<a href="' . $openid->GetIdentity() . '">' . $openid->GetIdentity() . '</a>' . $GLOBALS['language']['openid_update_success_after'], $this_url);
//		} else {
//			showmessage($GLOBALS['language']['openid_update_noupdate_before'] . "<a href='" . $old_openid['openid_url'] . "'>" . $old_openid['openid_url'] . "</a>" . $GLOBALS['language']['openid_update_noupdate_after'], $this_url);
//		}
//	}
//	elseif ($openid->IsError() == true) { // ON THE WAY, WE GOT SOME ERROR
//		$error = $openid->GetError();
//		showmessage($error['description'] . "(" . $error['code'] . ")", $this_url);
//	} else { // Signature Verification Failed
//		showmessage("INVALID AUTHORIZATION", $this_url);
//	}
//}
//elseif ($_GET['openid_mode'] == 'cancel') { // User Canceled your Request
//	echo "USER CANCELED REQUEST";
//}

// Obtain current binding from datbase to display
$query = $db->query("SELECT openid_url as openid_identifier FROM {$tablepre}openid WHERE uid = " . $discuz_uid);
$openid = $db->fetch_array($query);

include template('openid_setting');
?>