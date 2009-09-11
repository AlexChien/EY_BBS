<?php
/**
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
require_once "common.php";
include_once language('openid');
require_once DISCUZ_ROOT.'./forumdata/cache/cache_profilefields.php';
require_once DISCUZ_ROOT.'./uc_client/client.php';

session_start();

// 处理第一次登录论坛的openid
function handleNewOpenid($openid, $sreg) {
	global $_DPLUGIN;

	$handle_type = $_DPLUGIN['openid4discuz']['vars']['new_openid_handle_type'];
	if (empty($handle_type) || $handle_type == 1) {
		// register($openid, $sreg);// 插件提供的注册逻辑在enjoyoung的环境下有bug
	 	regiter_user_to_ucenter($openid, $sreg);
	} 
	// else {
	// 	gotoRegOrBind($openid, $sreg);
	// }
}

// 重写的注册逻辑
function regiter_user_to_ucenter($openid, $sreg){
	
	// 原插件的逻辑是先用openid的nickname注册再用email的@前字符注册，再用openid里的用户id注册
	// $username = generateUsername(obtainNickname($openid_identifier, $sreg));
	
	// 现和uchome里修改的逻辑保持一致
	$pieces=explode("http://openid.enjoyoung.cn/", $openid);//线上运营
	// $pieces=explode("http://localhost/", $openid);//本地开发
	if ($login = $pieces[1]) {
		$username = $login;
	} else {
		showmessage(
			"暂不支持非星尚通信证的OpenID。"
		);
	}
	
	$email = $sreg['email'];
	
	$uid = uc_user_register($username, $password, $email);
	if($uid <= 0) {
		if($uid == -1) {
			showmessage('profile_username_illegal');
		} elseif($uid == -2) {
			showmessage('profile_username_protect');
		} elseif($uid == -3) {
			// showmessage('profile_username_duplicate');
			// 如果已经在ucenter存在先通过uchome注册的用户,则为他开通discuz帐户
		
			//同步获取用户源
			if(!$passport = uc_user_info_by_login($username)) {
				showmessage('profile_username_duplicate');
			}
			// echo var_dump($passport)."--passport<br/>";
			$uid = $passport[0];
			regiter_user_to_discuz($uid,$username,$email,$openid);
			
		} elseif($uid == -4) {
			showmessage('profile_email_illegal');
		} elseif($uid == -5) {
			showmessage('profile_email_domain_illegal');
		} elseif($uid == -6) {
			showmessage('profile_email_duplicate');
		} else {
			showmessage('undefined_action', NULL, 'HALTED');
		}
	} else {
		regiter_user_to_discuz($uid,$username,$email,$openid);
	}
}

function regiter_user_to_discuz($uid,$username,$email,$openid){
	global $tablepre, $db, $query, $timestamp;
	
	$openid_identifier = $openid
	$plain_password = GetSID(24);
	$password = md5($plain_password);		
	$secques = "";
	// 1：男，2：女，0：保密
	$gendernew = 0;
	$onlineip = get_ip();
	$bday = "0000-00-00";
	if ($sreg['dob']) {
		$bday = $sreg['dob'];
	}
	$sigstatus = 0;
	$tppnew = 0;
	$pppnew = 0;
	$styleidnew = 0;
	$dateformatnew = 0;
	$timeformatnew = 0;
	$pmsoundnew = 1;
	$showemailnew = 1;
	$newsletter = 1;
	$invisiblenew = 0;
	$timeoffsetnew = 9999;
	$nickname = "";
	$site = "";
	$icq = "";
	$qq = "";
	$yahoo = "";
	$msn = "";
	$taobao = "";
	$alipay = "";
	$locationnew = "";
	$bio = "";
	$sightml = "";
	$cstatus = "";
	$authstr = "";
	$avatar = "";
	$avatarwidth = 0;
	$avatarheight = 0;

	$db->query("INSERT INTO {$tablepre}members (uid, username, password, secques, gender, adminid, groupid, regip, regdate, lastvisit, lastactivity, posts, email, bday, sigstatus, tpp, ppp, styleid, dateformat, timeformat, pmsound, showemail, newsletter, invisible, timeoffset)
		VALUES ('$uid', '$username', '$password', '$secques', '$gendernew', '0', '10', '$onlineip', '$timestamp', '$timestamp', '$timestamp', '0', '$email', '$bday', '$sigstatus', '$tppnew', '$pppnew', '$styleidnew', '$dateformatnew', '$timeformatnew', '$pmsoundnew', '$showemailnew', '$newsletter', '$invisiblenew', '$timeoffsetnew')");
	$uid = $db->insert_id();

	$db->query("INSERT INTO {$tablepre}memberfields (uid, nickname, site, icq, qq, yahoo, msn, taobao, alipay, location, bio, sightml, customstatus, authstr, avatar, avatarwidth, avatarheight)
		VALUES ('$uid', '$nickname', '$site', '$icq', '$qq', '$yahoo', '$msn', '$taobao', '$alipay', '$locationnew', '$bio', '$sightml', '$cstatus', '$authstr', '$avatar', '$avatarwidth', '$avatarheight')");

	bindOpenID($uid, $openid_identifier);

	// Set login.
	setLogin($uid);
}

///////////////////////////////////////////////////////////
// 先有uid后绑定openid的情况，enjoyoung不存在这种情况
function gotoRegOrBind($openid, $sreg) {
	global $_COOKIE, $cookiepre, $sid;

	updateOpenIDSession($sid, $openid);
	setcookie('openid4discuz_openid_sreg_nickname', $sreg['nickname']);
	setcookie('openid4discuz_openid_sreg_email', $sreg['email']);
	/*
	showmessage($GLOBALS['language']['openid_no_bind_before']
		.'<a href="'.$openid.'">'.$openid.'</a>'.$GLOBALS['language']['openid_no_bind_after'],
		'register.php');
	*/
	showmessage(
		"你的OpenID（<a href=\"$openid\">$openid</a>）尚未和任何论坛账号绑定。"
		."<br /><br />你可以将你的OpenID<a href=\"logging.php?action=login&amp;openid_action=bind\">"
		."和你已有的论坛账号绑定</a>或者<a href=\"http://openid.enjoyoung.cn/account/new\">新注册一个论坛账号并绑定</a>。"
	);
}

/////////////////////////////////////////////////////////////
// 插件提供的注册逻辑，没有考虑到ucenter里有用户而bbs里没有的情况,注册顺序应该先是ucenter，再是本地
function register($openid_identifier, $sreg) {
	global $tablepre, $db, $query, $timestamp;

	$username = generateUsername(obtainNickname($openid_identifier, $sreg));
	$plain_password = GetSID(24);
	$password = md5($plain_password);
	$secques = "";
	// 1：男，2：女，0：保密
	$gendernew = 0;
	$onlineip = get_ip();
	$email = $sreg['email'];
	$bday = "0000-00-00";
	if ($sreg['dob']) {
		$bday = $sreg['dob'];
	}
	$sigstatus = 0;
	$tppnew = 0;
	$pppnew = 0;
	$styleidnew = 0;
	$dateformatnew = 0;
	$timeformatnew = 0;
	$pmsoundnew = 1;
	$showemailnew = 1;
	$newsletter = 1;
	$invisiblenew = 0;
	$timeoffsetnew = 9999;
	$nickname = "";
	$site = "";
	$icq = "";
	$qq = "";
	$yahoo = "";
	$msn = "";
	$taobao = "";
	$alipay = "";
	$locationnew = "";
	$bio = "";
	$sightml = "";
	$cstatus = "";
	$authstr = "";
	$avatar = "";
	$avatarwidth = 0;
	$avatarheight = 0;

	$db->query("INSERT INTO {$tablepre}members (username, password, secques, gender, adminid, groupid, regip, regdate, lastvisit, lastactivity, posts, email, bday, sigstatus, tpp, ppp, styleid, dateformat, timeformat, pmsound, showemail, newsletter, invisible, timeoffset)
		VALUES ('$username', '$password', '$secques', '$gendernew', '0', '10', '$onlineip', '$timestamp', '$timestamp', '$timestamp', '0', '$email', '$bday', '$sigstatus', '$tppnew', '$pppnew', '$styleidnew', '$dateformatnew', '$timeformatnew', '$pmsoundnew', '$showemailnew', '$newsletter', '$invisiblenew', '$timeoffsetnew')");
	$uid = $db->insert_id();

	$db->query("INSERT INTO {$tablepre}memberfields (uid, nickname, site, icq, qq, yahoo, msn, taobao, alipay, location, bio, sightml, customstatus, authstr, avatar, avatarwidth, avatarheight)
		VALUES ('$uid', '$nickname', '$site', '$icq', '$qq', '$yahoo', '$msn', '$taobao', '$alipay', '$locationnew', '$bio', '$sightml', '$cstatus', '$authstr', '$avatar', '$avatarwidth', '$avatarheight')");
		
	$db->query("INSERT INTO {$tablepre}uc_members (uid, username, password, email, myid, myidkey, regip, regdate, lastloginip, lastlogintime, salt, secques)
		VALUES ('$uid', '$username', '$password', '$email', '', '', '$onlineip', '$timestamp', '$onlineip', '$timestamp', '', '$secques')");

	bindOpenID($uid, $openid_identifier);
	
	// Set login.
	setLogin($uid);
}

function setLogin($uid) {
	global $tablepre, $query, $db, $_DCACHE, $_DCOOKIE;

	global $discuz_uid, $discuz_user, $discuz_pw, $discuz_secques, $adminid, $groupid, $styleidmem, 
		$lastvisit, $lastpost, $allowinvisible;
	global $discuz_userss;

	$query = $db->query("SELECT m.uid AS discuz_uid, m.username AS discuz_user, m.password AS discuz_pw, m.secques AS discuz_secques,
						m.adminid, m.groupid, m.styleid AS styleidmem, m.lastvisit, m.lastpost, u.allowinvisible
						FROM {$tablepre}members m LEFT JOIN {$tablepre}usergroups u USING (groupid)
						WHERE m.uid='" . $uid . "'");
	$member = $db->fetch_array($query);

	if ($member['discuz_uid']) {

		extract($member);

		$discuz_userss = $discuz_user;
		$discuz_user = addslashes($discuz_user);

//		if (($allowinvisible && $loginmode == 'invisible') || $loginmode == 'normal') {
//			$db->query("UPDATE {$tablepre}members SET invisible='" . ($loginmode == 'invisible' ? 1 : 0) . "' WHERE uid='$member[discuz_uid]'", 'UNBUFFERED');
//		}

		$styleid = intval(empty($_POST['styleid']) ? ($styleidmem ? $styleidmem :
				$_DCACHE['settings']['styleid']) : $_POST['styleid']);

		$cookietime = intval(isset($_POST['cookietime']) ? $_POST['cookietime'] :
				($_DCOOKIE['cookietime'] ? $_DCOOKIE['cookietime'] : 0));

		dsetcookie('cookietime', $cookietime, 31536000);
		dsetcookie('auth', authcode("$discuz_pw\t$discuz_secques\t$discuz_uid", 'ENCODE'), $cookietime);

		$sessionexists = 0;

		if ($groupid == 8) {
			showmessage('login_succeed_inactive_member', 'memcp.php');
		} else {
			showmessage('login_succeed', dreferer());
		}
	}
}

function runDiscuz($openid, $sreg) {
	global $tablepre, $query, $db, $_DCACHE, $_DCOOKIE;

	global $discuz_uid, $discuz_user, $discuz_pw, $discuz_secques, $adminid, $groupid, $styleidmem, 
		$lastvisit, $lastpost, $allowinvisible;
	global $discuz_userss;

	// 检查是否已有该此登录的openid对应uid
	$query = $db->query("SELECT uid, openid_url FROM {$tablepre}openid WHERE openid_url='".$openid."'");
	$member_openid = $db->fetch_array($query);
	if (!$member_openid['uid']) {//如果没有openid和uid的关联关系，注册新用户
		handleNewOpenid($openid, $sreg);
	} else {//有openid和uid的关联则直接登录
		$uid = $member_openid['uid'];

		// Set login
		setLogin($uid);
	}
}

function run() {
    $consumer = getConsumer();

    // Complete the authentication process using the server's
    // response.
    $return_to = getReturnTo('finish_auth');
    $response = $consumer->complete($return_to);

    // Check the response status.
    if ($response->status == Auth_OpenID_CANCEL) {
        // This means the authentication was cancelled.
        $msg = 'Verification cancelled.';
    } else if ($response->status == Auth_OpenID_FAILURE) {
        // Authentication failed; display the error message.
        $msg = "OpenID authentication failed: " . $response->message;
    } else if ($response->status == Auth_OpenID_SUCCESS) {
        // This means the authentication succeeded; extract the
        // identity URL and Simple Registration data (if it was
        // returned).
        $openid = $response->getDisplayIdentifier();
        $esc_identity = htmlspecialchars($openid, ENT_QUOTES);

        $success = sprintf('You have successfully verified ' .
                           '<a href="%s">%s</a> as your identity.',
                           $esc_identity, $esc_identity);

        if ($response->endpoint->canonicalID) {
            $success .= '  (XRI CanonicalID: '.$response->endpoint->canonicalID.') ';
        }

        $sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

        $sreg = $sreg_resp->contents();

        if (@$sreg['email']) {
            $success .= "  You also returned '".$sreg['email']."' as your email.";
        }

        if (@$sreg['nickname']) {
            $success .= "  Your nickname is '".$sreg['nickname']."'.";
        }

        if (@$sreg['fullname']) {
            $success .= "  Your fullname is '".$sreg['fullname']."'.";
        }

	$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

	if ($pape_resp) {
	  if ($pape_resp->auth_policies) {
	    $success .= "<p>The following PAPE policies affected the authentication:</p><ul>";

	    foreach ($pape_resp->auth_policies as $uri) {
	      $success .= "<li><tt>$uri</tt></li>";
	    }

	    $success .= "</ul>";
	  } else {
	    $success .= "<p>No PAPE policies affected the authentication.</p>";
	  }

	  if ($pape_resp->auth_age) {
	    $success .= "<p>The authentication age returned by the " .
	      "server is: <tt>".$pape_resp->auth_age."</tt></p>";
	  }

	  if ($pape_resp->nist_auth_level) {
	    $success .= "<p>The NIST auth level returned by the " .
	      "server is: <tt>".$pape_resp->nist_auth_level."</tt></p>";
	  }

	} else {
	  $success .= "<p>No PAPE response was sent by the provider.</p>";
	}

		runDiscuz($openid, $sreg);
    }

    include 'message.php';
}

run();
?>