<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: insenz.inc.php 17419 2008-12-19 07:09:05Z liuqiang $
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

define('INSENZ_CHECKIP', TRUE);
define('INSENZ_CHECKFILES', FALSE);
define('INSENZ_SAFEMODE', FALSE);

cpheader();
echo '<script type="text/JavaScript">var charset=\''.$charset.'\'</script>';

if(!$isfounder) {
	cpmsg('noaccess_isfounder', '', 'error');
}

require_once DISCUZ_ROOT.'./include/insenz.func.php';
@include_once DISCUZ_ROOT.'./discuz_version.php';
require_once DISCUZ_ROOT.'./admin/insenz.func.php';

$discuz_chs = $insenz_chs = '';
$query = $db->query("SELECT value FROM {$tablepre}settings WHERE variable='insenz'");
$insenz = ($insenz = $db->result($query, 0)) ? unserialize($insenz) : array();
$insenz['host'] = empty($insenz['host']) ? 'api.insenz.com' : $insenz['host'];
$insenz['url'] = empty($insenz['url']) ? 'api.insenz.com' : $insenz['url'];
$msn = $_POST['msn'];

if(empty($insenz['authkey']) && !($operation == 'settings' && $do == 'host')) {

	if(in_array($operation, array('binding', 'register'))) {

		checkip();

		if(empty($agreelicense)) {

			shownav('adv', 'insenz', 'insenz_nav_license');
			showsubmenu('insenz_register_license');
			echo <<<EOT
<div style="border-style: dotted; border-width: 1px; border-color: #86B9D6; padding: 6px 10px; float: none; overflow: auto; overflow-y: scroll; height:320px; word-break: break-all; background-color: #FFFFFF;" id="license">$lang[insenz_loading]</div>
<br /><div id="licensesubmit"></div>
<script type="text/javascript" src="http://$insenz[url]/misc/license.js" charset="utf-8"></script>
<script type="text/JavaScript">
	if(typeof license != 'undefined') {
		$('license').innerHTML = license;
		$('licensesubmit').innerHTML = '<input onclick="window.location=\'$BASESCRIPT?action=insenz&operation=$operation&agreelicense=yes\'" type="button" class="btn" value="$lang[insenz_register_agree]"> &nbsp; <input onclick="javascript:history.go(-1);" type="button" class="btn" value="$lang[insenz_register_disagree]">';
	} else {
		$('license').innerHTML = '$lang[insenz_disconnect]';
		$('licensesubmit').innerHTML = '<input onclick="javascript:history.go(-1);" type="button" class="btn" value="$lang[return]">';
	}
</script>
EOT;

		} else {

			if($operation == 'register') {

				$step = isset($step) ? intval($step) : (isset($insenz['step']) ? intval($insenz['step']) : 1);

				$query = $db->query("SELECT value FROM {$tablepre}settings WHERE variable='videoinfo'");
				$video = ($video = $db->result($query, 0)) ? unserialize($video) : array();
				if(!empty($video['authkey'])) {
					cpmsg('insenz_forcebinding', $BASESCRIPT.'?action=insenz&operation=binding&agreelicense=yes&type=2');
				}

				if($step == 1) {

					$items = array('username', 'password', 'name', 'idcard', 'email1', 'email2', 'qq', 'msn', 'tel1', 'tel2', 'tel3', 'mobile', 'fax1', 'fax2', 'fax3', 'country', 'province', 'city', 'address', 'postcode', 'alipay');

					if(!submitcheck('regsubmit')) {

						$response = insenz_request('<cmd id="checkSite"><s_url>'.urlencode($boardurl).'</s_url><s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key></cmd>');
						if($response['status']) {
							cpmsg($response['data'], '', 'error');
						} else {
							$response = $response['data']['response'][0]['data'][0]['VALUE'];
						}
						if($response == 'site_exists') {
							cpmsg('insenz_forcebinding', $BASESCRIPT.'?action=insenz&operation=binding&agreelicense=yes&type=2');
						}

						foreach($items as $item) {
							$$item = '';
						}
						if(isset($insenz['profile'])) {
							@extract($insenz['profile']);
							foreach($items as $item) {
								$$item = stripslashes($$item);
							}
						}
						$country = intval($country) ? intval($country) : 0;
						$province = intval($province) ? intval($province) : 0;
						$city = intval($city) ? intval($city) : 0;
						$tel1 = intval($tel1) ? $tel1 : $lang['insenz_register_zone'];
						$tel2 = intval($tel2) ? intval($tel2) : $lang['insenz_register_exchange'];
						$tel3 = intval($tel3) ? intval($tel3) : $lang['insenz_register_extension'];

						shownav('adv', 'insenz', 'insenz_register');
						echo <<<EOT
<body onload="list($country, $province, $city)">
<script type="text/javascript" src="http://$insenz[url]/misc/city.js" charset="utf-8"></script>
<script type="text/JavaScript">
	function clearinput(obj, defaultvalue) {
		if(obj.value == defaultvalue) obj.value = '';
	}
</script>
<script type="text/javascript" src="./include/js/insenz_reg.js"></script>
EOT;
						showsubmenu('insenz_register_step1');
						showformheader('insenz&operation=register&agreelicense=yes&step=1&frame=no', ' onSubmit="return validate(this);" target="register"', 'form1');
						showtableheader();
						showtitle('insenz_register_profile');
						showsetting('insenz_register_username', 'username', $username, 'text');
						showsetting('insenz_register_password', 'password', $password, 'password');
						showsetting('insenz_register_password2', 'password2', '', 'password');
						showsetting('insenz_register_name', 'name', $name, 'text');
						showsetting('insenz_register_idcard', 'idcard', $idcard, 'text');
						showtitle('insenz_register_contact');
						showsetting('insenz_register_email1', 'email1', $email1 ? $email1 : $email, 'text');
						showsetting('insenz_register_email2', 'email2', $email2 ? $email2 : $adminemail, 'text');
						showsetting('insenz_register_qq', 'qq', $qq, 'text');
						showsetting('insenz_register_msn', 'msn', $msn, 'text');
						showsetting('insenz_register_tel', '', '', '<input type="text" class="txt" name="tel1" size="3" value="'.$tel1.'" onmousedown="clearinput(this, \''.$lang['insenz_register_zone'].'\')" style="width: 63px;" /> - <input type="text" class="txt" name="tel2" size="8" value="'.$tel2.'" onmousedown="clearinput(this,\''.$lang['insenz_register_exchange'].'\')" style="width: 63px;" /> - <input type="text" class="txt" name="tel3" size="5" value="'.$tel3.'" onmousedown="clearinput(this, \''.$lang['insenz_register_extension'].'\')" style="width: 63px;" />');
						showsetting('insenz_register_mobile', 'mobile', $mobile, 'text');
						showsetting('insenz_register_fax', '', '', '<input type="text" class="txt" name="fax1" size="3" value="'.$fax1.'" style="width: 63px;" /> - <input type="text" class="txt" name="fax2" size="8" value="'.$fax2.'" style="width: 63px;" /> - <input type="text" class="txt" name="fax3"size="5" value="'.$fax3.'" style="width: 63px;" />');
						showsetting('insenz_register_country', '', '', '<select name="country" onChange="changeseleccountry(this.value)"><option value="0">'.$lang['select'].'</option></select>');
						showsetting('insenz_register_province', '', '', '<select name="province" onChange="changeseleccity(this.value)"><option value="0">'.$lang['select'].'</option></select>');
						showsetting('insenz_register_city', '', '', '<select name="city"><option value="0">'.$lang['select'].'</option></select>');
						showsetting('insenz_register_address', 'address', $address, 'text');
						showsetting('insenz_register_postcode', 'postcode', $postcode, 'text');
						showtitle('insenz_register_account');
						showsetting('insenz_register_alipay', 'alipay', $alipay, 'text');
						showtablerow('', 'colspan="2"', '<input type="submit" class="btn" name="regsubmit" value="'.$lang['submit'].'" /><iframe name="register" style="display: none"></iframe> &nbsp; <input type="button" class="btn" value="'.$lang['cancel'].'" onclick="window.location=\''.$BASESCRIPT.'?action=insenz\'">');
						showtablefooter();
						showformfooter();

					} else {

						$username = checkusername($username);
						$password = checkpassword($password, $password2);
						$name = checkname($name);
						$idcard = checkidcard($idcard);
						$email1 = checkemail($email1, 'email1');
						$email2 = $email2 ? checkemail($email2, 'email2') : '';
						$qq = checkqq($qq);
						$msn = $msn ? checkemail($msn, 'msn') : '';
						$tel3 = $tel3 != $lang['insenz_register_extension'] ? intval($tel3) : '';
						$tel = checktel($tel1, $tel2, $tel3, 'tel');
						$fax = $fax2 ? checktel($fax1, $fax2, $fax3, 'fax') : '';
						$mobile = checkmobile($mobile);
						$cpc = checkcpc($country, $province, $city);
						$country = $cpc[0];
						$province = $cpc[1];
						$city = $cpc[2];
						$address = checkaddress($address);
						$postcode = checkpostcode($postcode);
						$alipay = checkemail($alipay, $lang['insenz_register_alipay']);

						$response = insenz_request('<cmd id="checkHandle"><handle>'.$username.'</handle></cmd>');
						if($response['status']) {
							insenz_alert($response['data']);
						} else {
							$response = $response['data']['response'][0]['data'][0]['VALUE'];
						}

						if($response == 'handle_exists') {
							insenz_alert('insenz_usernameexists', 'username');
						}

						foreach($items as $item) {
							$insenz['profile'][$item] = $$item;
						}

						$insenz['step'] = 2;

						$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");

						insenz_cpmsg('insenz_regstep2', $BASESCRIPT.'?action=insenz&operation=register&agreelicense=yes&step=2');

					}

				} else {

					if(!submitcheck('regsubmit')) {

						shownav('adv', 'insenz', 'insenz_register');
						showsubmenu('insenz_register_step2');
						showformheader('insenz&operation=register&agreelicense=yes&step=3&frame=no', 'target="register"');
						showtableheader();
						insenz_showsettings();
						showtablerow('', 'colspan="2"', '<input type="button" class="btn" value="'.$lang['laststep'].'" onclick="window.location=\''.$BASESCRIPT.'?action=insenz&operation=register&agreelicense=yes&step=1\'"> &nbsp; <input type="submit" class="btn" name="regsubmit" value="'.$lang['submit'].'" /><iframe name="register" style="display: none"></iframe>');
						showtablefooter();
						showformfooter();

					} else {

						$softadstatus = $softadstatus1 ? ($softadstatus2 ? 2 : 1) : 0;
						$softadstatus && checkmasks();
						$hardadstatus = is_array($hardadstatus) ? array_keys($hardadstatus) : array();
						$msgtoid = 0;
						if($softadstatus && is_array($notify) && $notify[1]) {
							if(empty($msgto)) {
								insenz_alert('insenz_msgtonone', 'msgto');
							} else {
								$query = $db->query("SELECT uid FROM {$tablepre}members WHERE username='$msgto'");
								if(!$msgtoid = $db->result($query, 0)) {
									insenz_alert('insenz_msgtonone', 'msgto');
								}
							}

						}
						foreach(array('softadstatus', 'hardadstatus', 'relatedadstatus', 'notify', 'msgtoid', 'autoextend', 'virtualforumstatus') as $item) {
							$insenz[$item] = $$item;
						}
						insenz_register('1');

					}
				}

			} elseif($operation == 'binding') {

				if(!submitcheck('bindingsubmit')) {

					shownav('adv', 'insenz', 'insenz_binding');
					showsubmenu('insenz_binding_top');
					showformheader('insenz&operation=binding&agreelicense=yes&frame=no', 'target="binding"', 'form1');
					showtableheader('insenz_binding_verify');
					showsetting('insenz_binding_username', 'username', '', 'text');
					showsetting('insenz_binding_password', 'password', '', 'password');
					insenz_showsettings();
					showtablerow('', 'colspan="2"', '<input type="submit" class="btn" name="bindingsubmit" value="'.$lang['insenz_binding_top'].'" /><iframe name="binding" style="display: none"></iframe> &nbsp; <input type="button" class="btn" value="'.$lang['cancel'].'" onclick="window.location=\''.$BASESCRIPT.'?action=insenz\'">');
					showtablefooter();
					showformfooter();

				} else {

					$insenz['profile']['username'] = htmlspecialchars($username);
					$insenz['profile']['password'] = htmlspecialchars($password);

					$softadstatus = $softadstatus1 ? ($softadstatus2 ? 2 : 1) : 0;
					$softadstatus && checkmasks();
					$hardadstatus = is_array($hardadstatus) ? array_keys($hardadstatus) : array();
					$msgtoid = 0;
					if($softadstatus && is_array($notify) && $notify[1]) {
						if(empty($msgto)) {
							insenz_alert('insenz_msgtonone', 'msgto');
						} else {
							$query = $db->query("SELECT uid FROM {$tablepre}members WHERE username='$msgto'");
							if(!$msgtoid = $db->result($query, 0)) {
								insenz_alert('insenz_msgtonone', 'msgto');
							}
						}

					}
					foreach(array('softadstatus', 'hardadstatus', 'relatedadstatus', 'notify', 'msgtoid', 'autoextend', 'virtualforumstatus')AS $item) {
						$insenz[$item] = $$item;
					}

					insenz_register(!empty($type) && $type == 2 ? '2' : '3');

				}
			}
		}

	} else {

		shownav('adv', 'insenz', 'insenz_nav_regorbind');
		showsubmenu('insenz_nav_regorbind');
		showformheader('insenz&operation=register');
		showtableheader();
		showtablerow('', '', lang('insenz_register_description'));
		showsubmit('submit', 'insenz_register', '', '<input type="button" class="btn" value="'.$lang['insenz_binding'].'" onclick="window.location=\''.$BASESCRIPT.'?action=insenz&operation=binding\'">');
		showtablefooter();
		showformfooter();

	}

} elseif(empty($operation) || $operation == 'campaignlist') {

	shownav('adv', 'insenz', 'insenz_nav_softad');
	showsubmenu('nav_insenz_softad', array(
		array('nav_insenz_softad_new', 'insenz&operation=campaignlist&c_status=2', $c_status == 2),
		array('nav_insenz_softad_online', 'insenz&operation=campaignlist&c_status=6', $c_status == 6),
		array('nav_insenz_softad_offline', 'insenz&operation=campaignlist&c_status=7', $c_status == 7)
	));
	showtips('insenz_tips_softad');

	$baseurl = $BASESCRIPT.'?action=insenz&operation=campaignlist';
	$c_status = isset($c_status) && in_array($c_status, array(0, 2, 6, 7)) ? $c_status : ($insenz['softadstatus'] == 2 ? 6 : 2);
	$page = isset($page) ? max(1, intval($page)) : 1;

	if($c_status == 6) {
		$onlineids = $offlineids = 0;
		$query = $db->query("SELECT id, status FROM {$tablepre}campaigns WHERE type<>'4' AND status IN ('2','3')");
		while($c = $db->fetch_array($query)) {
			if($c['status'] == 2) {
				$onlineids .= ','.$c['id'];
			} else {
				$offlineids .= ','.$c['id'];
			}
		}
	}

	$campaignslist = array(0 => $lang['insenz_campaign_all'], 2 => $lang['insenz_campaign_new'], 6 => $lang['insenz_campaign_playing'], 7 => $lang['insenz_campaign_over']);

	showtableheader($campaignslist[$c_status], 'fixpadding');
	showsubtitle(array('insenz_campaign_id', 'insenz_campaign_name', 'insenz_campaign_class', 'insenz_campaign_type', 'insenz_campaign_forum', 'insenz_campaign_starttime', 'insenz_campaign_endtime', 'insenz_campaign_price', in_array($c_status, array(0, 6)) ? 'insenz_campaign_status' : NULL, ''));
	showtagheader('tbody', 'campaignlist', TRUE);
	showtablerow('id="campaignlist_loading"', 'colspan="10"', '<img src="'.IMGDIR.'/loading.gif" border="0"> '.$lang['insenz_loading']);
	showtagfooter('tbody');
	showsubmit('', '', '', '', '<div id="multi"></div>');
	showtablefooter();

?>

	<script src="http://<?=$insenz[url]?>/campaignlist.php?id=<?=$insenz[siteid]?>&t=<?=$timestamp?>&k=<?=md5($insenz[authkey].$insenz[siteid].$timestamp.'Discuz!')?>&insenz_version=<?=INSENZ_VERSION?>&discuz_version=<?=DISCUZ_VERSION.' - '.DISCUZ_RELEASE?>&c_status=<?=$c_status?>&page=<?=$page?>&random=<?=random(4)?>" type="text/javascript" charset="UTF-8"></script>

	<script type="text/JavaScript">
		if(typeof Campaigns != 'undefined' && error_msg != '') {
			alert(error_msg);
		}
		var c_status = parseInt(<?=$c_status?>);
		var c_statuss = {1:'<font color="red"><?=$lang['insenz_campaign_status_new']?></font>', 2:'<font color="red"><?=$lang['insenz_campaign_status_new']?></font>', 3:'<?=$lang['insenz_campaign_status_send']?>', 6:'<?=$lang['insenz_campaign_status_playing']?>', 7:'<?=$lang['insenz_campaign_status_end']?>'};
		var c_types = {1 : '<?=$lang['insenz_campaign_type_normal']?>', 2 : '<?=$lang['insenz_campaign_type_top']?>', 3 : '<?=$lang['insenz_campaign_type_float']?>'};
		var s = '';
		if(typeof Campaigns == 'undefined') {
			s += '<tr><td colspan="10"><?=$lang['insenz_disconnect']?></td></tr>';
		} else if(!Campaigns.length) {
			s += '<tr><td colspan="10"><?=$lang['insenz_campaign_none']?></td></tr>';
		} else {
			for(var i in Campaigns) {
				s += '<tr>'
					+ '<td>' + Campaigns[i].c_id + '</td>'
					+ '<td><a href="<?=$BASESCRIPT?>?action=insenz&operation=campaigndetails&c_id=' + Campaigns[i].c_id + '&c_status=' + Campaigns[i].c_status + '">' + Campaigns[i].c_name + '</a></td>'
					+ '<td>' + c_types[Campaigns[i].c_type] + (Campaigns[i].c_auto ? '(<?=$lang['insenz_campaign_auto_push']?>)' : '') + '</td>'
					+ '<td>' + (Campaigns[i].c_url ? '<?=$lang['insenz_campaign_type_iframe']?>' : '<?=$lang['insenz_campaign_type_topic']?>') + '</td>'
					+ '<td><a href="' + (Campaigns[i].b_type == 'group' ? '<?=$indexname?>?gid=' : 'forumdisplay.php?fid=') + Campaigns[i].b_id + '" target="_blank">' + Campaigns[i].b_name + '</a></td>'
					+ '<td>' + Campaigns[i].c_begindate + '</td>'
					+ '<td>' + (Campaigns[i].c_type == 1 ? '----' : Campaigns[i].c_enddate) + '</td>'
					+ '<td>' + Campaigns[i].c_price + ' <?=$lang['rmb_yuan']?></td>'
					+ (c_status != 2 && c_status != 6 && c_status != 7 ? '<td>' + c_statuss[Campaigns[i].c_status] + '</td>' : (c_status == 6 ? '<td>' + (in_array(Campaigns[i].c_id, [<?=$onlineids?>]) ? '<span class="diffcolor2"><?=$lang['insenz_campaign_status_online']?></span>' : (in_array(Campaigns[i].c_id, [<?=$offlineids?>]) ? '<span class="lightfont"><?=$lang['insenz_campaign_status_offline']?></span>' : '<span class="diffcolor3"><?=$lang['insenz_campaign_status_waiting']?></span>')) + '</td>' : ''))
					+ '<td><a href="<?=$BASESCRIPT?>?action=insenz&operation=campaigndetails&c_id=' + Campaigns[i].c_id + '&c_status=' + Campaigns[i].c_status + '"><?=$lang['detail']?></a></td></tr>';
			}
		}
		document.write('<table id="campaignlist_none" style="display: none">' + s + '</table>');
		var trs = $('campaignlist_none').getElementsByTagName('tr');
		var len = trs.length;
		for(var i = 0; i < len; i++) {
			$('campaignlist').appendChild(trs[0]);
		}
		$('campaignlist').removeChild($('campaignlist_loading'));
		$('campaignlist_none').parentNode.removeChild($('campaignlist_none'));
		if(typeof c_nums != 'undefined' && c_nums > 10) {
			$('multi').innerHTML = multi();
		}
		function multi() {
			var page = parseInt(<?=$page?>);
			var pages = Math.ceil(c_nums / 10);
			page = page < pages ? page : pages;
			var multi = '<div class="pages"><em>&nbsp;' + c_nums + '&nbsp;</em>';
			for(var i = 1; i <= pages; i++) {
				multi += page == i ? '<strong>' + page + '</strong>' : '<a href=<?=$baseurl?>&c_status=<?=$c_status?>&page=' + i + '>' + i + '</a>';
			}
			multi += '</div>';
			return multi;
		}
	</script>

<?php

} elseif($operation == 'campaigndetails') {

	shownav('adv', 'insenz', 'insenz_nav_softad');

	$c_id = intval($c_id);
	$c_status = intval($c_status);
	$campaign = array();

	if($c_status == 3) {
		$query = $db->query("SELECT c.id, t.tid, t.displayorder FROM {$tablepre}campaigns c LEFT JOIN {$tablepre}threads t ON t.tid=c.tid WHERE c.id='$c_id' AND c.type<>4");
		if(!($campaign = $db->fetch_array($query)) || empty($campaign['tid'])) {
			echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb tb2 nobdb">'.
			'<tr class="partition"><td><div style="float:left; margin-left:0px; padding-top:8px">'.$lang['insenz_note'].'</div>'.
			'</td></tr><tr><td><font color="red">'.$lang['insenz_nomatchedcampdata'].'</font></td></tr></table><br />';
		}
	}

	showsubmenu('insenz_campaign_detail');
	showformheader("insenz&operation=admin&c_id=$c_id");
	showtableheader('', 'fixpadding');
	showtagheader('tbody', 'campaigndetails', TRUE);
	showtablerow('id="campaigndetails_loading"', '', '<img src="'.IMGDIR.'/loading.gif" border="0"> '.lang('insenz_loading'));
	showtagfooter('tbody');
	showsubmit('', '', '', '<span id="campaigndetails_submit" style="display: none"><input type="submit" class="btn" onclick="return confirmmessage(\''.$lang['insenz_push_message_tips'].'\');" name="pushsubmit" value="'.$lang['insenz_campaign_pass'].'"> &nbsp; <input type="submit" class="btn" name="ignoresubmit" value="'.$lang['insenz_campaign_ignore'].'"> &nbsp; </span><span id="campaigndetails_drop" style="display: none"><input type="submit" class="btn"'.($c_status == 3 ? ' onclick="return confirmmessage(\''.$lang['insenz_delete_message_tips'].'\');"' : '').' name="dropsubmit" value="'.$lang['delete'].'"> &nbsp; </span><input type="button" class="btn" onClick="history.go(-1)" value="'.$lang['return'].'">');
	showtablefooter();
	showformfooter();

?>

<script src="http://<?=$insenz[url]?>/campaigndetails.php?id=<?=$insenz[siteid]?>&t=<?=$timestamp?>&k=<?=md5($insenz[authkey].$insenz[siteid].$timestamp.'Discuz!')?>&c_id=<?=$c_id?>&insenz_version=<?=INSENZ_VERSION?>&discuz_version=<?=DISCUZ_VERSION.' - '.DISCUZ_RELEASE?>&random=<?=random(4)?>" type="text/javascript" charset="UTF-8"></script>

<script src="./include/js/bbcode.js" type="text/javascript"></script>

<script type="text/JavaScript">

	if(typeof error_msg != 'undefined' && error_msg != '') {
		alert(error_msg);
	}

	var s = '';
	if(typeof Campaigndetails == 'undefined') {
		s += '<tr><td colspan="8"><?=$lang['insenz_disconnect']?></td></tr>';
	} else if(Campaigndetails == '') {
		s += '<tr><td colspan="8"><?=$lang['insenz_campaign_deleted']?></td></tr>';
	} else {

		var allowbbcode = 1;
		var allowhtml = 1;
		var forumallowhtml = 1;
		var allowsmilies = 0;
		var allowimgcode = 1;
		var c_statuss = {1:'<font color="red"><?=$lang['insenz_campaign_new']?></font>', 2:'<font color="red"><?=$lang['insenz_campaign_new']?></font>', 3:'<?=$lang['insenz_campaign_send']?>', 6:'<?=$lang['insenz_campaign_playing']?>', 7:'<?=$lang['insenz_campaign_over']?>'};

		var t_style = '';
		t_style += Campaigndetails.t_bold ? 'font-weight: bold;' : '';
		t_style += Campaigndetails.t_italic ? 'font-style: italic;' : '';
		t_style += Campaigndetails.t_underline ? 'text-decoration: underline;' : '';
		t_style += Campaigndetails.t_color ? 'color: ' + Campaigndetails.t_color : '';

		var t_title = parseInt('<?=$c_status?>') == 3 && parseInt('<?=$campaign['tid']?>') && parseInt('<?=$campaign['displayorder']?>') >= 0  ? '<a href="viewthread.php?tid=<?=$campaign['tid']?>" target="_blank"><span style="' + t_style + '">' + Campaigndetails.t_title + '</span></a>' : '<span style="' + t_style + '">' + Campaigndetails.t_title + '</span>';

		var t_type = '<?=$lang['insenz_campaign_normal']?>';
		if(Campaigndetails.c_type == 2) {
			t_type = Campaigndetails.b_type == 'group' ? '<?=$lang['insenz_campaign_forum_top']?>' : '<?=$lang['insenz_campaign_currentforum_top']?>';
		} else if(Campaigndetails.c_type == 3) {
			t_type = Campaigndetails.b_type == 'group' ? '<?=$lang['insenz_campaign_forum_float']?>' : '<?=$lang['insenz_campaign_currentforum_float']?>';
		}

		s += '<tr><td><?=$lang['insenz_campaign_name']?>:' + Campaigndetails.c_name + '</td><td><?=$lang['insenz_campaign_push_forum']?>:<a href="' + (Campaigndetails.b_type == 'group' ? '<?=$indexname?>?gid=' : 'forumdisplay.php?fid=') + Campaigndetails.b_id + '" target="_blank">' + Campaigndetails.b_name + '</a></td></tr>'
		+ '<tr><td><?=$lang['insenz_campaign_starttime']?>:' + Campaigndetails.c_begindate + '</td><td><?=$lang['insenz_campaign_endtime']?>:' + (Campaigndetails.c_type == 1 ? '----' : Campaigndetails.c_enddate) + '</td></tr>'
		+ '<tr><td><?=$lang['insenz_campaign_price']?>:<font color="red">' + Campaigndetails.c_price + '</font> <?=$lang['rmb_yuan']?></td><td><?=$lang['insenz_campaign_status']?>:' + c_statuss[Campaigndetails.c_status] + '</td></tr>'
		+ '<tr><td colspan="2"><?=$lang['insenz_campaign_note']?>:' + bbcode2html(Campaigndetails.c_notes) + '</td></tr>'
		+ '<tr><td><?=$lang['insenz_campaign_post_subject']?>:' + t_title + ' (' + t_type + ')</td><td><?=$lang['insenz_campaign_post_username']?>:' + (Campaigndetails.t_authortype == 1? '<?=$lang['insenz_campaign_post_admin']?>' : '<?=$lang['insenz_campaign_post_normal_user']?>') + '</td></tr>'
		+ '<tr><td colspan="2"><?=$lang['insenz_campaign_post_message']?>:</td></tr>'
		+ '<tr><td colspan="2"><div style="overflow: auto; overflow-x: hidden; max-height:300px; height:auto !important; height:300px; word-break: break-all;" id="t_content"></div>'
		+ '<input type="hidden" name="c_id" value="' + parseInt(Campaigndetails.c_id) + '">'
		+ '<input type="hidden" name="subject" value="' + htmlspecialchars(Campaigndetails.t_title)+ '">'
		+ '<input type="hidden" name="message" value="' + htmlspecialchars(Campaigndetails.t_content)+ '">'
		+ '<input type="hidden" name="authortype" value="' + parseInt(Campaigndetails.t_authortype) + '">'
		+ '<input type="hidden" name="b_id" value="' + parseInt(Campaigndetails.b_id) + '">'
		+ '<input type="hidden" name="f_id" value="' + parseInt(Campaigndetails.f_id) + '">'
		+ '<input type="hidden" name="begintime" value="' + parseInt(Campaigndetails.c_begintime) + '">'
		+ '<input type="hidden" name="endtime" value="' + parseInt(Campaigndetails.c_endtime) + '">'
		+ '<input type="hidden" name="c_type" value="' + parseInt(Campaigndetails.c_type) + '">'
		+ '<input type="hidden" name="highlight" value="' + Campaigndetails.t_highlight + '">'
		+ '<input type="hidden" name="c_url" value="' + htmlspecialchars(Campaigndetails.c_url) + '">'
		+ '<input type="hidden" name="c_autoupdate" value="' + parseInt(Campaigndetails.c_autoupdate) + '">'
		+ '</td></tr>'

	}

	document.write('<table id="campaigndetails_none" style="display: none">' + s + '</table>');
	var trs = $('campaigndetails_none').getElementsByTagName('tr');
	var len = trs.length;
	for(var i = 0; i < len; i++) {
		$('campaigndetails').appendChild(trs[0]);
	}
	$('t_content').innerHTML = bbcode2html(Campaigndetails.t_content);
	$('campaigndetails').removeChild($('campaigndetails_loading'));
	$('campaigndetails_none').parentNode.removeChild($('campaigndetails_none'));

	if(typeof Campaigndetails != 'undefined' && Campaigndetails != '') {
		if(Campaigndetails.c_status < 3) {
			$('campaigndetails_submit').style.display = '';
		} else if(Campaigndetails.c_status == 3 || Campaigndetails.c_status == 6) {
			var currenttime = parseInt('<?=$timestamp?>');
			if(currenttime - Campaigndetails.c_begintime < 172800) {
				$('campaigndetails_drop').style.display = '';
			}
		}
	}

	function confirmmessage(msg) {
		return confirm(msg);
	}

</script>

<?php

	if($c_status < 2) {
		$data = '<cmd id="markread">'.
			'<c_id>'.$c_id.'</c_id>'.
			'</cmd>';
		insenz_request($data, false);
	}

} elseif($operation == 'admin') {

	insenz_checkfiles();

	if(submitcheck('pushsubmit')) {

		$b_id = intval($b_id);
		$f_id = intval($f_id);
		$fid = $f_id ? $f_id : $b_id;
		$query = $db->query("SELECT f.type, f.status, f.simple, ff.redirect FROM {$tablepre}forums f LEFT JOIN {$tablepre}forumfields ff ON ff.fid=f.fid WHERE f.fid='$fid'");
		if(!$forum = $db->fetch_array($query)) {
			cpmsg('insenz_invalidforum', '', 'error');
		} elseif($f_id) {
			if(!$globalstick) {
				cpmsg('insenz_globalstickoff', '', 'error');
			}
		} elseif($forum['status'] == '0' || $forum['simple'] == '1' || !empty($forum['redirect'])) {
			cpmsg('insenz_invalidforums', '', 'error');
		}

		if(!$fp = @fsockopen($insenz['host'], 80)) {
			cpmsg('insenz_disconnect', '', 'error');
		}

		$c_id = intval($c_id);
		$c_type = intval($c_type);
		$subject = dhtmlspecialchars(trim($subject));
		$query = $db->query("SELECT id FROM {$tablepre}campaigns WHERE id='$c_id' AND type='$c_type'");
		if($db->result($query, 0)) {
			cpmsg('insenz_campaign_dumplicate', '', 'error');
		}

		$top = $c_type == 2 ? 1 : ($c_type == 3 ? 4 : 0);
		if($forum['type'] == 'group' && $top) {
			$top += 1;
		}
		$displayorder = -10 - $top;
		$highlight = intval($highlight);
		$masks = $authortype == 1 ? $insenz['admin_masks'] : $insenz['member_masks'];
		$authorid = $masks[array_rand($masks)];
		$author = addslashes($db->result_first("SELECT username FROM {$tablepre}members WHERE uid='$authorid'"));
		$dateline = intval($begintime);
		$endtime = intval($endtime);
		$expiration = $endtime + 60*86400;
		$lastpost = $dateline;
		$lastposter = $author;
		$moderated = in_array($displayorder, array(1, 2)) ? 1 : 0;
		$c_url = empty($c_url) ? '' : $c_url.(strpos($c_url, '?') !== FALSE ? '&' : '?');
		$digest = $c_url ? -2 : -1;

		$db->query("INSERT INTO {$tablepre}threads (fid, author, authorid, subject, dateline, lastpost, lastposter, displayorder, digest, highlight, moderated)
			VALUES ('$fid', '$author', '$authorid', '$subject', '$dateline', '$lastpost', '$lastposter', '$displayorder', '$digest', '$highlight', '$moderated')");
		$tid = $db->insert_id();

		$data = '<cmd id="acceptCampaign">'.
			'<c_id>'.$c_id.'</c_id>'.
			'<topic_id>'.$tid.'</topic_id>'.
			'</cmd>';
		$response = insenz_request($data, TRUE, $fp);

		if($response['status']) {
			$db->query("DELETE FROM {$tablepre}threads WHERE tid='$tid'");
			cpmsg($response['data'], '', 'error');
		} else {
			$response = $response['data'];
			if($response['response'][0]['status'][0]['VALUE'] == 1) {
				$db->query("DELETE FROM {$tablepre}threads WHERE tid='$tid'");
				cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
			}
		}

		$db->query("INSERT INTO {$tablepre}posts (fid, tid, first, author, authorid, subject, dateline, message, useip,invisible, anonymous, usesig, htmlon, bbcodeoff, smileyoff, parseurloff)
			VALUES ('$fid', '$tid', '1', '$author', '$authorid', '$subject', '$dateline', '$message', '', '0', '0', '1', '1', '0', '0', '0')");

		$db->query("INSERT INTO {$tablepre}campaigns (id, type, fid, tid, status, begintime, endtime, expiration, nextrun, url, autoupdate)
			VALUES ('$c_id', '$c_type', '$fid', '$tid', '1', '$dateline', '$endtime', '$expiration', '$dateline', '$c_url', '$c_autoupdate')");

		insenz_cronnextrun($dateline);

		cpmsg('insenz_campaign_pushed', $BASESCRIPT.'?action=insenz', 'succeed');

	} elseif(submitcheck('ignoresubmit')) {

		if(!$confirmed) {

		showformheader("insenz&operation=admin&c_id=$c_id&confirmed=yes");
		showtableheader('discuz_message');
		showsetting('insenz_campaign_input_ignore_reson', array('reason', array(
			array(1, $lang['insenz_campaign_ignore_reson_more_threads']),
			array(2, $lang['insenz_campaign_reson_price']),
			array(3, $lang['insenz_campaign_reson_content_unsuitable']),
			array(4, $lang['insenz_campaign_reson_subject_notmathched'])
		)), '', 'mradio');
		showsubmit('', '', '', '<input type="submit" class="btn" name="ignoresubmit" onclick="return checkform(this.form);" value="'.$lang['ok'].'"> &nbsp;<input type="button" class="btn" value="'.$lang['cancel'].'" onClick="history.go(-1)">');
		showtablefooter();
		showformfooter();
		echo '<script type="text/JavaScript">
			function checkform(theform) {
				for(var i = 0; i < 4; i++) {
					if(theform.reason[i].checked) return true;
				}
				alert(\''.$lang['insenz_campaign_input_reason'].'\');
				return false;
			}
		</script>';

		} else {

			if(!$reason = intval($reason)) {
				cpmsg('insenz_campaign_input_reason', '', 'error');
			}
			$data = '<cmd id="ignoreCampaign">'.
				'<c_id>'.$c_id.'</c_id>'.
				'<reason>'.$reason.'</reason>'.
				'</cmd>';
			$response = insenz_request($data);
			if($response['status']) {
				cpmsg($response['data'], '', 'error');
			} else {
				$response = $response['data'];
				if($response['response'][0]['status'][0]['VALUE'] == 1) {
					cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
				}
			}
			cpmsg('insenz_campaign_specified_ignored', $BASESCRIPT.'?action=insenz', 'succeed');

		}

	} elseif(submitcheck('dropsubmit')) {

		if(!$confirmed) {

		showformheader("insenz&operation=admin&c_id=$c_id&c_type=$c_type&confirmed=yes");
		showtableheader('discuz_message');
		showsetting('insenz_campaign_input_delete_reason', 'reason', '', 'textarea');
		showsubmit('', '', '', '<input type="submit" class="btn" name="dropsubmit" onclick="return checkform(this.form);" value="'.$lang['ok'].'"> &nbsp;<input type="button" class="btn" value="'.$lang['cancel'].'" onClick="history.go(-1)">');
		showtablefooter();
		showformfooter();
		echo '<script type="text/JavaScript">
			function checkform(theform) {
				if(trim(theform.reason.value) == \'\') {
					alert(\''.$lang['insenz_campaign_input_reason'].'\');
					return false;
				} else if(theform.reason.value.length > 255) {
					alert(\''.$lang['insenz_campaign_reson_words_too_many'].'\');
					return false;
				}
			}
		</script>';

		} else {

			if(!$reason = trim($reason)) {
				cpmsg('insenz_campaign_input_reason', '', 'error');
			} elseif(strlen($reason) > 255) {
				cpmsg('insenz_campaign_reson_words_too_many', '', 'error');
			}

			$query = $db->query("SELECT tid, begintime FROM {$tablepre}campaigns WHERE id='$c_id' AND type='$c_type'");
			if(!$c = $db->fetch_array($query)) {
				cpmsg('insenz_campaign_deleted', '', 'error');
			} elseif($timestamp - $c['begintime'] > 172800) {
				cpmsg('insenz_campaign_cant_delete_after_2_days', '', 'error');
			}

			$data = '<cmd id="dropCampaign">'.
				'<c_id>'.$c_id.'</c_id>'.
				'<reason>'.insenz_convert($reason).'</reason>'.
				'</cmd>';
			$response = insenz_request($data);
			if($response['status']) {
				cpmsg($response['data'], '', 'error');
			} else {
				$response = $response['data'];
				if($response['response'][0]['status'][0]['VALUE'] == 1) {
					cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
				}
			}
			$db->query("DELETE FROM {$tablepre}threads WHERE tid='$c[tid]'");
			$db->query("DELETE FROM {$tablepre}posts WHERE tid='$c[tid]'");
			$db->query("DELETE FROM {$tablepre}campaigns WHERE id='$c_id' AND type='$c_type'");
			cpmsg('insenz_campaign_specified_deleted', $BASESCRIPT.'?action=insenz', 'succeed');

		}

	}

} elseif($operation == 'settings') {

	insenz_checkfiles();

	$baseurl = $BASESCRIPT.'?action=insenz&operation=settings';
	if(!submitcheck('settingssubmit')) {
		shownav('adv', 'insenz', 'insenz_nav_settings');
		showsubmenu('nav_insenz_config', array(
			array('nav_insenz_config_basic', 'insenz&operation=settings&do=basic', $do == 'basic'),
			array('nav_insenz_config_softad', 'insenz&operation=settings&do=softad', $do == 'softad'),
			array('nav_insenz_config_hardad', 'insenz&operation=settings&do=hardad', $do == 'hardad'),
			array('nav_insenz_config_virtualforum', 'insenz&operation=settings&do=virtualforum', $do == 'virtualforum')
		));
	}

	if($do == 'basic') {

		if(!submitcheck('settingssubmit')) {

			showformheader('insenz&operation=settings&do=basic');
			insenz_showsettings($do);

		} else {

			$msgtoid = 0;
			if(is_array($notify) && $notify[1]) {
				if(empty($msgto)) {
					cpmsg('insenz_campaign_message_user_not_exists', '', 'error');
				} else {
					$query = $db->query("SELECT uid FROM {$tablepre}members WHERE username='$msgto'");
					if(!$msgtoid = $db->result($query, 0)) {
						cpmsg('insenz_campaign_message_user_not_exists', '', 'error');
					}
				}
			}

			$notify = is_array($notify) ? $notify : array(2 => 1);
			if($insenz['notify'] != $notify) {
				$data = '<cmd id="editbasicsettings">'.
					'<notify>'.implode(',', $notify).'</notify>'.
					'<s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key>'.
					'</cmd>';
				$response = insenz_request($data);
				if($response['status']) {
					cpmsg($response['data'], '', 'error');
				} else {
					$response = $response['data'];
					if($response['response'][0]['status'][0]['VALUE'] == 1) {
						cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
					}
				}
				insenz_updatesettings();
			}
			foreach(array('notify', 'msgtoid') as $item) {
				$insenz[$item] = $$item;
			}
			$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
			require_once DISCUZ_ROOT.'./include/cache.func.php';
			updatecache('settings');
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}

	} elseif($do == 'softad') {

		if(!submitcheck('settingssubmit')) {

			showtips('insenz_tips_softadsetting');
			showformheader('insenz&operation=settings&do=softad');
			insenz_showsettings($do);

		} else {

			$softadstatus = $softadstatus1 ? ($softadstatus2 ? 2 : 1) : 0;
			if($softadstatus) {
				checkmasks(TRUE);
			}

			if($insenz['softadstatus'] != $softadstatus) {
				$data = '<cmd id="editsoftadstatus">'.
					'<softadstatus>'.$softadstatus.'</softadstatus>'.
					'<autoextend>'.intval($autoextend).'</autoextend>'.
					'<s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key>'.
					'</cmd>';
				$response = insenz_request($data);
				if($response['status']) {
					cpmsg($response['data'], '', 'error');
				} else {
					$response = $response['data'];
					if($response['response'][0]['status'][0]['VALUE'] == 1) {
						cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
					}
				}
				insenz_updatesettings();
			}
			foreach(array('softadstatus', 'autoextend') as $item) {
				$insenz[$item] = $$item;
			}
			$insenz['uid'] = $discuz_uid;
			$insenz['username'] = $discuz_userss;
			$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
			require_once DISCUZ_ROOT.'./include/cache.func.php';
			updatecache('settings');
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}

	} elseif($do == 'hardad') {

		if(!submitcheck('settingssubmit')) {

			showtips('insenz_tips_hardadsetting');
			showformheader('insenz&operation=settings&do=hardad');
			insenz_showsettings($do);

		} else {

			$hardadstatus = is_array($hardadstatus) ? array_keys($hardadstatus) : array();
			if($insenz['hardadstatus'] != $hardadstatus) {
				$insenz['hardadstatus'] = $hardadstatus;
				$data = '<cmd id="edithardadstatus">'.
					'<hardadstatus>'.implode(',', (array)$hardadstatus).'</hardadstatus>'.
					'<s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key>'.
					'</cmd>';
				$response = insenz_request($data);
				if($response['status']) {
					cpmsg($response['data'], '', 'error');
				} else {
					$response = $response['data'];
					if($response['response'][0]['status'][0]['VALUE'] == 1) {
						cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
					}
				}
				insenz_updatesettings();
				$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
				require_once DISCUZ_ROOT.'./include/cache.func.php';
				updatecache(array('settings', 'advs_forumdisplay', 'advs_viewthread'));

			}
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}

	} elseif($do == 'relatedad') {

		if(!submitcheck('settingssubmit')) {

			showtips('insenz_tips_relatedadsetting');
			showformheader('insenz&operation=settings&do=relatedad');
			insenz_showsettings($do);

		} else {
			$relatedadstatus = in_array($relatedadstatus, array(0, 1)) ? $relatedadstatus : 1;
			if($insenz['relatedadstatus'] != $relatedadstatus) {
				$insenz['relatedadstatus'] = $relatedadstatus;
				$data ='<cmd id="editrelatedadstatus">'.
					'<relatedadstatus>'.$relatedadstatus.'</relatedadstatus>'.
					'<s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key>'.
					'</cmd>';
				$response = insenz_request($data);
				if($response['status']) {
					cpmsg($response['data'], '', 'error');
				} else {
					$response = $response['data'];
					if($response['response'][0]['status'][0]['VALUE'] == 1) {
						cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
					}
				}
				insenz_updatesettings();
				$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
				require_once DISCUZ_ROOT.'./include/cache.func.php';
				updatecache('settings');
			}
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}

	} elseif($do == 'virtualforum') {

		if(!submitcheck('settingssubmit')) {

			showtips('insenz_tips_virtualforumsetting');
			showformheader('insenz&operation=settings&do=virtualforum');
			insenz_showsettings($do);

		} else {
			$virtualforumstatus = in_array($virtualforumstatus, array(0, 1)) ? $virtualforumstatus : 1;
			if($insenz['virtualforumstatus'] != $virtualforumstatus) {
				$insenz['virtualforumstatus'] = $virtualforumstatus;
				$data ='<cmd id="editvirtualforumstatus">'.
					'<virtualforumstatus>'.$virtualforumstatus.'</virtualforumstatus>'.
					'<s_key>'.md5($authkey.'Discuz!INSENZ').'</s_key>'.
					'</cmd>';
				$response = insenz_request($data);
				if($response['status']) {
					cpmsg($response['data'], '', 'error');
				} else {
					$response = $response['data'];
					if($response['response'][0]['status'][0]['VALUE'] == 1) {
						cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
					}
				}
				insenz_updatesettings();
				$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
				require_once DISCUZ_ROOT.'./include/cache.func.php';
				updatecache('settings');
			}
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}
	} elseif($do == 'host') {
		if(!submitcheck('settingssubmit')) {
			if(!function_exists('fsockopen')) {
				cpmsg('insenz_fsockopen_notavailable', '', 'error');
			}
			showformheader('insenz&operation=settings&do=host');
			showtableheader();
			showtitle('insenz_settings_host');
			showsetting('insenz_settings_domain', 'host', $insenz['host'], 'text');
		} else {
			if($host && $insenz['host'] != $host && (preg_match("/\w{1,8}\.insenz\.com/i", $host) || strcmp(long2ip(sprintf('%u', ip2long($host))), $host) == 0)) {
				$insenz['host'] = $host;
				$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
			}
			cpmsg('insenz_settings_updated_succeed', $baseurl.'&do='.$do, 'succeed');
		}
	}

	if(!submitcheck('settingssubmit')) {
		showsubmit('settingssubmit');
		showtablefooter();
		showformfooter();
	}

} elseif($operation == 'virtualforum') {

	if(submitcheck('acceptsubmit')) {

		insenz_checkfiles();

		$c_id = intval($c_id);
		$subject = dhtmlspecialchars(trim($c_name));
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}campaigns WHERE id='$c_id' AND type='4'");
		if($db->result($query, 0)) {
			cpmsg('insenz_campaign_dumplicate', '', 'error');
		}

		$gid = $db->result_first("SELECT fid FROM {$tablepre}virtualforums WHERE type='group' LIMIT 1");
		if(!$gid) {
			$db->query("INSERT INTO {$tablepre}virtualforums (cid, fup, type, name, status, displayorder) VALUES ('$c_id', '0', 'group', '".$lang['insenz_vf_init_forumname']."', '1', '0')");
			$gid = $db->insert_id();
		}

		$c_forumlink = strpos($c_forumlink, '?') !== FALSE ? $c_forumlink.'&' : $c_forumlink.'?';
		$db->query("INSERT INTO {$tablepre}virtualforums (cid, fup, type, name, description, logo, status, displayorder, threads, posts, lastpost) VALUES ('$c_id', '$gid', 'forum', '$c_forumname', '$c_forumnote', '$icon', '0', '0', '$threads', '$posts', '$vflastpost')");
		$fid = $db->insert_id();

		$data = '<cmd id="acceptVirtualForum">'.
			'<c_id>'.$c_id.'</c_id>'.
			'<boardid>'.$fid.'</boardid>'.
			'</cmd>';
		$response = insenz_request($data, TRUE, $fp);

		if($response['status']) {
			$db->query("DELETE FROM {$tablepre}virtualforums WHERE fid='$fid'");
			cpmsg($response['data'], '', 'error');
		} else {
			$response = $response['data'];
			if($response['response'][0]['status'][0]['VALUE'] == 1) {
				$db->query("DELETE FROM {$tablepre}virtualforums WHERE fid='$fid'");
				cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
			}
		}

		$query = $db->query("REPLACE INTO {$tablepre}campaigns(id, fid, tid, type, status, begintime, starttime, endtime, expiration, nextrun, url, autoupdate)
			VALUES ('$c_id', '$fid', '0', '4', '1', '$c_begintime', '$c_starttime', '$c_endtime', '0', '$c_begintime', '$c_forumlink', '$c_autoupdate')");

		insenz_cronnextrun($c_begintime);

		cpmsg('insenz_vf_send', $BASESCRIPT.'?action=insenz&operation=virtualforum&c_status=2', 'succeed');

	} elseif(submitcheck('ignoresubmit')) {

		insenz_checkfiles();

		$c_id = intval($c_id);
		$subject = dhtmlspecialchars(trim($c_name));
		$query = $db->query("SELECT COUNT(*) FROM {$tablepre}campaigns WHERE id='$c_id' AND type='4'");
		if($db->result($query, 0)) {
			cpmsg('insenz_campaign_dumplicate', '', 'error');
		}

		if(!$confirmed) {
			showformheader("insenz&operation=virtualforum&c_id=$c_id&confirmed=yes");
			showtableheader('discuz_message');
			showsetting('insenz_campaign_input_ignore_reson', array('reason', array(
				array(1, $lang['insenz_campaign_ignore_reson_more_threads']),
				array(2, $lang['insenz_campaign_reson_price']),
				array(3, $lang['insenz_campaign_reson_content_unsuitable']),
				array(4, $lang['insenz_campaign_reson_subject_notmathched'])
			)), '', 'mradio');
			showsubmit('', '', '', '<input type="submit" class="btn" name="ignoresubmit" onclick="return checkform(this.form);" value="'.$lang['ok'].'"> &nbsp;<input type="button" class="btn" value="'.$lang['cancel'].'" onClick="history.go(-1)">');
			showtablefooter();
			showformfooter();
			echo '<script type="text/JavaScript">
				function checkform(theform) {
					for(var i = 0; i < 4; i++) {
						if(theform.reason[i].checked) return true;
					}
					alert(\''.$lang['insenz_campaign_input_reason'].'\');
					return false;
				}
			</script>';

		} else {

			if(!$reason = intval($reason)) {
				cpmsg('insenz_campaign_input_reason', '', 'error');
			}
			$data = '<cmd id="ignoreVirtualForum">'.
				'<c_id>'.$c_id.'</c_id>'.
				'<reason>'.$reason.'</reason>'.
				'</cmd>';
			$response = insenz_request($data);
			if($response['status']) {
				cpmsg($response['data'], '', 'error');
			} else {
				$response = $response['data'];
				if($response['response'][0]['status'][0]['VALUE'] == 1) {
					cpmsg(insenz_convert($response['response'][0]['reason'][0]['VALUE'], 0), '', 'error');
				}
			}
			cpmsg('insenz_campaign_specified_ignored', $BASESCRIPT.'?action=insenz&operation=virtualforum&c_status=2', 'succeed');

		}

	} else {

		shownav('adv', 'insenz', 'insenz_nav_virtualforum');
		showsubmenu('nav_insenz_vf', array(
			array('nav_insenz_vf_new', 'insenz&operation=virtualforum&c_status=2', $c_status == 2),
			array('nav_insenz_vf_online', 'insenz&operation=virtualforum&c_status=6', $c_status == 6),
			array('nav_insenz_vf_offline', 'insenz&operation=virtualforum&c_status=7', $c_status == 7)
		));

		if($c_status == 6) {
			$onlineids = $offlineids = 0;
			$query = $db->query("SELECT id, status FROM {$tablepre}campaigns WHERE type='4' AND status IN ('2','3')");
			while($c = $db->fetch_array($query)) {
				if($c['status'] == 2) {
					$onlineids .= ','.$c['id'];
				} else {
					$offlineids .= ','.$c['id'];
				}
			}
		}
		showtips('insenz_tips_virtualforum');
		$statuslist = array('2'=>$lang['insenz_campaign_new'], '6'=>$lang['insenz_campaign_playing'], '7'=>$lang['insenz_campaign_over']);
		showtableheader($statuslist[$c_status], 'fixpadding');
		showsubtitle(array('insenz_campaign_id', 'insenz_campaign_name', 'insenz_campaign_price', 'insenz_campaign_starttime', 'insenz_campaign_endtime', 'insenz_vf_name', $c_status == 6 ? 'insenz_campaign_status' : NULL, ''));
		showtagheader('tbody', 'tbody1', TRUE);
		showtablerow('', 'colspan="8" id="loading"', '<img src="'.IMGDIR.'/loading.gif" border="0"> '.$lang['insenz_loading']);
		showtagfooter('tbody');
		showtablefooter();

		echo '	<script src="./include/js/bbcode.js" type="text/javascript"></script>
			<script src="http://'.$insenz['url'].'/virtualforum.php?action=list&c_status='.$c_status.'&id='.$insenz['siteid'].'&t='.$timestamp.'&k='.md5($insenz['authkey'].$insenz['siteid'].$timestamp.'Discuz!').'&insenz_version='.INSENZ_VERSION.'&discuz_version='.DISCUZ_VERSION.'-'.DISCUZ_RELEASE.'&c_status='.$c_status.'&page='.$page.'&random='.random(4).'" type="text/javascript" charset="UTF-8"></script>
			<script type="text/JavaScript">
			if(typeof error_msg != "undefined" && error_msg) {
				$("loading").innerHTML = error_msg;
				alert(error_msg);
			} else {
				var s = "";
				for(k in Camps) {
					var camp = Camps[k];
					s += "<tr><td align=\"left\">"+camp.c_id+"</td>";
					s += "<td align=\"left\">"+camp.c_name+"</td>";
					s += "<td>"+camp.c_price+"</td>";
					s += "<td>"+camp.c_begindate+"</td>";
					s += "<td>"+camp.c_enddate+"</td>";
					s += "<td>"+camp.c_forumname+"</td>";
					'.($c_status == 6 ? 's += "<td>"+(in_array(camp.c_id, ['.$onlineids.']) ? "<span class=\"diffcolor2\">'.$lang[insenz_campaign_status_online].'</span>" : (in_array(camp.c_id, ['.$offlineids.']) ? "<span class=\"lightfont\">'.$lang[insenz_campaign_status_offline].'</span>" : "<span class=\"diffcolor3\">'.$lang[insenz_campaign_status_waiting].'</span>"))+"</td>";' : '').'
					s += "<td><a href=\"javascript:showdetail("+camp.c_id+")\">'.$lang['detail'].'</a></td></tr>";
					s += "<tr><td colspan=\"8\" id=\"detail_"+camp.c_id+"\" style=\"display: none;\"></td></tr>";
				}
				$("loading").style.display = "none";
				ajaxinnerhtml($("tbody1"), s);
			}
			function showdetail(id) {
				var camp = Camps[id];
				var obj = $("detail_" + id);
				obj.style.display = obj.style.display == "" ? "none" : "";
				obj.style.padding = "10px";

				s = "<b>'.$lang['insenz_vf_note'].':</b><br>" + camp.c_forumnote;
				s += "<br><b>'.$lang['insenz_vf_camp_note'].':</b><br>" + camp.c_note;
				s += "<form name=\"form\" action=\"'.$BASESCRIPT.'?action=insenz&operation=virtualforum\" method=\"post\">";
				s += "<input type=\"hidden\" name=\"formhash\" value=\"'.FORMHASH.'\">";
				s += "<input type=\"hidden\" name=\"c_id\" value=\""+parseInt(camp.c_id)+"\">";
				s += "<input type=\"hidden\" name=\"c_name\" value=\""+htmlspecialchars(camp.c_name)+"\">";
				s += "<input type=\"hidden\" name=\"c_note\" value=\""+htmlspecialchars(camp.c_note)+"\">";
				s += "<input type=\"hidden\" name=\"c_price\" value=\""+parseInt(camp.c_price)+"\">";
				s += "<input type=\"hidden\" name=\"c_begintime\" value=\""+parseInt(camp.c_begintime)+"\">";
				s += "<input type=\"hidden\" name=\"c_endtime\" value=\""+parseInt(camp.c_endtime)+"\">";
				s += "<input type=\"hidden\" name=\"c_forumname\" value=\""+htmlspecialchars(camp.c_forumname)+"\">";
				s += "<input type=\"hidden\" name=\"c_forumlink\" value=\""+htmlspecialchars(camp.c_forumlink)+"\">";
				s += "<input type=\"hidden\" name=\"c_forumnote\" value=\""+htmlspecialchars(camp.c_forumnote)+"\">";
				s += "<input type=\"hidden\" name=\"threads\" value=\""+parseInt(camp.threads)+"\">";
				s += "<input type=\"hidden\" name=\"posts\" value=\""+parseInt(camp.posts)+"\">";
				s += "<input type=\"hidden\" name=\"vflastpost\" value=\""+htmlspecialchars(camp.lastpost)+"\">";
				s += "<input type=\"hidden\" name=\"c_autoupdate\" value=\""+parseInt(camp.c_autoupdate)+"\">";
				s += "<input type=\"hidden\" name=\"icon\" value=\""+htmlspecialchars(camp.c_icon)+"\">";
				s += '.($c_status == 2 ? "'<input type=\"submit\" class=\"btn\" name=\"acceptsubmit\" value=\"".$lang['insenz_campaign_pass']."\"> &nbsp; <input type=\"submit\" class=\"btn\" name=\"ignoresubmit\" value=\"".$lang['insenz_campaign_ignore']."\">'" : "''").';
				obj.innerHTML = s;

			}
			</script>';

	}

} else {

	cpmsg('noaccess', '', 'error');

}

?>
