<? if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/login.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/header_nofloat.htm', 1254133728, '2', './templates/colors')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/login.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/seccheck.htm', 1254133728, '2', './templates/colors')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/login.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/footer_nofloat.htm', 1254133728, '2', './templates/colors')
;?>
﻿
<? include template('header'); if(!empty($message)) { ?>
<?=$ucsynlogin?>
<script type="text/javascript" reload="1">
<? if($message == 2) { ?>
    window.location.href='http://openid.enjoyoung.cn/account/new';
    // floatwin('close_login');
    // floatwin('open_register', '<?=$location?>', 600, 400, '600,0');
<? } elseif($message == 1) { ?>
display('loginfield_selectinput');
display('loginform');
pagescroll.right(2);
<? if($groupid == 8) { ?>
$('messageleft').innerHTML = '<h1>欢迎您回来 <?=$usergroups?> <?=$discuz_user?></h1>您的帐号处于非激活状态';
$('messageright').innerHTML = '<h1><a href="memcp.php">个人中心</a></h1>';
setTimeout("window.location.href='memcp.php'", <?=$mrefreshtime?>);
<? } else { ?>
$('messageleft').innerHTML = '<h1>欢迎您回来 <?=$usergroups?> <?=$discuz_user?></h1>';
<? if(!empty($floatlogin)) { ?>
$('messageright').innerHTML = '<h1><a href="javascript:;" onclick="location.reload()">如果该页面长时间没有响应，请点这里刷新</a></h1>';
setTimeout('location.reload()', <?=$mrefreshtime?>);
<? } else { ?>
$('messageright').innerHTML = '<h1><a href="<? echo dreferer(); ?>">现在将转入登录前页面</a></h1>';
setTimeout("window.location.href='<? echo dreferer(); ?>'", <?=$mrefreshtime?>);
<? } } if($_DCACHE['settings']['frameon'] && $_DCOOKIE['frameon'] == 'yes') { ?>
if(top != self) {
parent.leftmenu.location.reload();
}
<? } } ?>
floatwinreset = 1;
</script>
<? } else { if(empty($infloat)) { ?><link rel="stylesheet" type="text/css" href="forumdata/cache/style_<?=STYLEID?>_float.css?<?=VERHASH?>" />

<style type="text/css">
.main { overflow: hidden; }
.content { margin: 5px; min-height: 400px; <?=FLOATBGCODE?>; }
* html .content { height: 400px; overflow: auto; }
.fixedbtn { position: static; }
</style>
<div id="nav"><a href="<?=$indexname?>"><?=$bbname?></a> <?=$navigation?></div>
<div id="wrap" class="wrap s_clear">

<div class="main"><div class="content"><div class="nojs">

<div id="floatwinnojs"><? } ?>
<script src="plugins/openid/cookie.js" type="text/javascript"></script>
<script src="plugins/openid/querystring.js" type="text/javascript"></script>
<script src="plugins/openid/openid.js" type="text/javascript"></script>

<div class="float" id="floatlayout_login" style="width: 600px; height: 400px;">
<div style="width: 1800px">
<div class="floatbox floatbox1">
<h3 class="float_ctrl">
<span>
<? if(!empty($infloat)) { ?><a href="javascript:;" class="float_close" onclick="floatwin('close_login')" title="关闭">关闭</a><? } ?>
</span>
</h3>
<form method="post" name="login" id="loginform" class="gateform" onsubmit="submitLogin();" action="logging.php?action=login&amp;loginsubmit=yes<? if(!empty($infloat)) { ?>&amp;floatlogin=yes<? } ?>">
<h3 id="returnmessage">用户登录</h3>
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="referer" value="<?=$referer?>" />
<div class="loginform nolabelform">
<div class="selectinput" style="width:245px;">
<select name="loginfield" style="float:left;width:50px;" id="loginfield" onchange="onLoginTypeChange(this);">
<option value="openid">星尚通行证</option>
<option value="username">用户名</option>
<option value="uid">UID</option>
</select>
<input type="text" id="username" class="openid" name="username" autocomplete="off" size="36" class="txt" tabindex="1" value="<?=$username?>" />
<input type="hidden" name="openid_identifier" id="openid_identifier" /> 
</div>
<p id="password_holder" class="selectinput loginpsw" style="display:none;width:245px;">
<label for="password3" style="width:81px;">密　码　：</label>
<input type="password" id="password3" name="password" onfocus="clearpwd()" onkeypress="detectcapslock(event, this)" size="36" class="txt" tabindex="1" style="width:137px;" />
</p>
<? if($secqaacheck || $seccodecheck) { $seccheckfloat = 'floatlayout_login'; ?><div id="seccodelayer" style="position:relative"><script type="text/javascript" reload="1">
function updateseccode<?=$secchecktype?>(op) {
if(isUndefined(op)) {
var x = new Ajax();
ajaxget('ajax.php?action=updateseccode&secchecktype=<?=$secchecktype?>', 'seccodeverify<?=$secchecktype?>_menu', 'seccodeverify<?=$secchecktype?>_menu');
} else {
window.document.seccodeplayer.SetVariable("isPlay", "1");
}
$('seccodeverify<?=$secchecktype?>').focus();
}

function updatesecqaa<?=$secchecktype?>() {
var x = new Ajax();
ajaxget('ajax.php?action=updatesecqaa', 'secanswer<?=$secchecktype?>_menu', 'secanswer<?=$secchecktype?>_menu');
}

var secclick<?=$secchecktype?> = new Array();
var seccodefocus = 0;
function opensecwin<?=$secchecktype?>(id, type) {
if(!secclick<?=$secchecktype?>[id]) {
$(id).value = '';
secclick<?=$secchecktype?>[id] = 1;
if(type) {
$(id + '_menu').style.top = (parseInt($(id + '_menu').style.top) - parseInt($(id + '_menu').style.height)) + 'px';
updateseccode<?=$secchecktype?>();
} else {
updatesecqaa<?=$secchecktype?>();
}
}
<? if(empty($secchecktype)) { if(!empty($infloat)) { ?>
InFloat='<?=$seccheckfloat?>';
<? } if($seccheckfloat) { ?>
$(id + '_menu').style.position = 'absolute';
$(id + '_menu').style.top = (-parseInt($(id + '_menu').style.height)) + 'px';
<? if($secqaacheck) { ?>
if(type) {
$(id + '_menu').style.left = $(id).offsetLeft + 'px';
} else {
$(id + '_menu').style.left = '0px';
}
<? } else { ?>
$(id + '_menu').style.left = '0px';
<? } } } elseif($secchecktype == 1) { if(!empty($infloat)) { ?>
InFloat='floatlayout_register';
<? } ?>
showMenu(id, 0, 2, 3);
<? } elseif($secchecktype == 3) { ?>
showMenu(id, 0, 2, 3);
<? } ?>

$(id + '_menu').style.display = '';
$(id).focus();
$(id).unselectable = 'off';
}
</script>

<? if($secqaacheck) { ?>
<input name="secanswer" id="secanswer<?=$secchecktype?>" type="text" autocomplete="off" style="width:50px" value="验证问答" class="txt" onfocus="opensecwin<?=$secchecktype?>(this.id, 0)" onclick="opensecwin<?=$secchecktype?>(this.id, 0)" onblur="display(this.id + '_menu');checksecanswer<?=$secchecktype?>();" tabindex="1">
<span id="checksecanswer<?=$secchecktype?>"><img src="images/common/none.gif" width="16" height="16"></span>
<div id="secanswer<?=$secchecktype?>_menu" class="seccodecontent" style="width:200px;height:80px;display:none"></div>
<? } if($seccodecheck) { ?>
<input name="seccodeverify" id="seccodeverify<?=$secchecktype?>" type="text" autocomplete="off" style="width:50px" value="验证码" class="txt" onfocus="opensecwin<?=$secchecktype?>(this.id, 1)" onclick="opensecwin<?=$secchecktype?>(this.id, 1)" onblur="if(!seccodefocus) {display(this.id + '_menu')}checkseccode<?=$secchecktype?>();" tabindex="1">
<a href="javascript:;" onclick="updateseccode<?=$secchecktype?>()">换一个</a>
<span id="checkseccodeverify<?=$secchecktype?>"><img src="images/common/none.gif" width="16" height="16"></span>
<div id="seccodeverify<?=$secchecktype?>_menu" class="seccodecontent" onmouseover="seccodefocus = 1" onmouseout="seccodefocus = 0" style="cursor: pointer;top: 256px;width:<?=$seccodedata['width']?>px;height:<?=$seccodedata['height']?>px;display:none"></div>
<? } ?>

<script type="text/javascript" reload="1">
var profile_seccode_invalid = '验证码输入错误，请重新填写。';
var profile_secanswer_invalid = '验证问答回答错误，请重新填写。';
var lastseccode = lastsecanswer = '';
function checkseccode<?=$secchecktype?>() {
var seccodeverify = $('seccodeverify<?=$secchecktype?>').value;
if(seccodeverify == lastseccode) {
return;
} else {
lastseccode = seccodeverify;
}
var cs = $('checkseccodeverify<?=$secchecktype?>');
<? if($seccodedata['type'] != 1) { ?>
if(!(/[0-9A-Za-z]{4}/.test(seccodeverify))) {
warning<?=$secchecktype?>(cs, profile_seccode_invalid);
return;
}
<? } else { ?>
if(seccodeverify.length != 2) {
warning<?=$secchecktype?>(cs, profile_seccode_invalid);
return;
}
<? } ?>
ajaxresponse<?=$secchecktype?>('checkseccodeverify<?=$secchecktype?>', 'action=checkseccode&seccodeverify=' + (is_ie && document.charset == 'utf-8' ? encodeURIComponent(seccodeverify) : seccodeverify));
}
function checksecanswer<?=$secchecktype?>() {
        var secanswer = $('secanswer<?=$secchecktype?>').value;
if(secanswer == lastsecanswer) {
return;
} else {
lastsecanswer = secanswer;
}
ajaxresponse<?=$secchecktype?>('checksecanswer<?=$secchecktype?>', 'action=checksecanswer&secanswer=' + (is_ie && document.charset == 'utf-8' ? encodeURIComponent(secanswer) : secanswer));
}
function ajaxresponse<?=$secchecktype?>(objname, data) {
var x = new Ajax('XML', objname);
x.get('ajax.php?inajax=1&' + data, function(s){
        var obj = $(objname);
        if(s == 'succeed') {
        	obj.style.display = '';
                obj.innerHTML = '<img src="<?=IMGDIR?>/check_right.gif" width="16" height="16" />';
obj.className = "warning";
} else {
warning(obj, s);
}
});
}
function warning(obj, msg) {
if((ton = obj.id.substr(5, obj.id.length)) != 'password2') {
$(ton).select();
}
obj.style.display = '';
obj.innerHTML = '<img src="<?=IMGDIR?>/check_error.gif" width="16" height="16" />';
obj.className = "warning";
}
</script></div>
<? } ?>
<div id="selecttype" class="selecttype" style="display:none;width:245px;" >
<select id="questionid" name="questionid" change="if($('questionid').value > 0) {$('answer').style.display='';} else {$('answer').style.display='none';}">
<option value="0">安全提问</option>
<option value="1">母亲的名字</option>
<option value="2">爷爷的名字</option>
<option value="3">父亲出生的城市</option>
<option value="4">您其中一位老师的名字</option>
<option value="5">您个人计算机的型号</option>
<option value="6">您最喜欢的餐馆名称</option>
<option value="7">驾驶执照的最后四位数字</option>
</select>
</div>
<p><input type="text" name="answer" id="answer" style="display:none" autocomplete="off" size="36" class="txt" tabindex="1" /></p>
</div>
<div class="logininfo multinfo">
<? if($discuz_uid) { ?>
                    <!-- <h4><?=$discuz_userss?>, <a href="javascript:;" onclick="ajaxget('float_register.php?action=logout&formhash=<?=FORMHASH?>', 'returnmessage', 'returnmessage');doane(event);">退出</a></h4> -->
<h4><?=$discuz_userss?>, <a href="javascript:;" onclick="window.location.href='http://openid.enjoyoung.cn/account/new';">退出</a></h4>
<p><? echo discuz_uc_avatar($discuz_uid, 'small');; ?></p>
<? } else { ?>
<h4>没有帐号？<a href="http://openid.enjoyoung.cn/account/new" onclick="window.location.href=\"http://openid.enjoyoung.cn/account/new\";" title="注册帐号"><?=$reglinkname?></a></h4>
                    <!-- <p>忘记密码, <a href="javascript:;" onclick="display('loginform');display('loginfield_selectinput');<? if($secqaacheck || $seccodecheck) { ?>display('seccodelayer');<? } ?>pagescroll.right()" title="找回密码">找回密码</a></p> -->
<p>忘记密码, <a href="javascript:;" onclick="window.location.href='http://openid.enjoyoung.cn/passwords/troubleshooting';" title="找回密码">找回密码</a></p>
<? } ?>
<p>无法登录，<a href="javascript:;" onclick="ajaxget('float_register.php?action=clearcookies&formhash=<?=FORMHASH?>', 'returnmessage2', 'returnmessage2');doane(event);" title="清除登录状态">清除登录状态</a></p>
</div>
<p class="fsubmit">
<button class="submit" type="submit" name="loginsubmit" value="true" tabindex="1">登录</button>
<input type="checkbox" class="checkbox" checked="checked" name="cookietime" id="cookietime" tabindex="1" value="2592000" <?=$cookietimecheck?> /> <label for="cookietime">记住我的登录状态</label>
</p>
</form>
</div>
<div class="floatbox floatbox1">
<h3 class="float_ctrl">
<span>
<? if(!empty($infloat)) { ?><a href="javascript:;" class="float_close" onclick="floatwin('close_login')" title="关闭">关闭</a><? } ?>
</span>
</h3>
<div class="gateform">
<h3 id="returnmessage3">找回密码</h3>
<div class="loginform">
<form method="post" id="lostpwform" onsubmit="ajaxpost('lostpwform', 'returnmessage3', 'returnmessage3', 'onerror');return false;" action="member.php?action=lostpasswd&amp;lostpwsubmit=yes&amp;infloat=yes">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="handlekey" value="lostpwform" />
<label><em>用户名:</em><input type="text" name="username" size="25" value=""  tabindex="1" class="txt" /></label>
<label><em>Email:</em><input type="text" name="email" size="25" value=""  tabindex="1" class="txt" /></label>
<p class="fsubmit">
<em></em>
<button class="submit" type="submit" name="lostpwsubmit" value="true" tabindex="100">提交</button>
</p>
</form>
</div>
<div class="logininfo multinfo">
<h4>没有帐号？<a href="http://openid.enjoyoung.cn/account/new" onclick="window.location.href=\"http://openid.enjoyoung.cn/account/new\";" title="注册帐号"><?=$reglinkname?></a></h4>
<p><a href="javascript:;" onclick="pagescroll.left(1, 'display(\'loginfield_selectinput\');<? if($secqaacheck || $seccodecheck) { ?>display(\'seccodelayer\');<? } ?>display(\'loginform\');');">返回登录</a></p>
</div>
</div>
<? if($sitemessage['login']) { ?>
<div class="moreconf sitemsg">
<div class="custominfoarea">
<a href="javascript:;" onclick="display('custominfo_login')" onblur="$('custominfo_login').style.display = 'none'"><img src="<?=IMGDIR?>/info.gif" alt="帮助" /></a>
<div id="custominfo_login" class="sitenote">
<div class="cornerlayger"><? echo $sitemessage['login'][array_rand($sitemessage['login'])]; ?></div>
<div class="minicorner"></div>
</div>
</div>
</div>
<? } ?>
</div>
<div class="floatbox floatbox1">
<h3 class="float_ctrl">
<span>
<? if(!empty($infloat)) { ?><a href="javascript:;" class="float_close" onclick="floatwin('close_login')" title="关闭">关闭</a><? } ?>
</span>
</h3>
<div class="validateinfo">
<div id="messageleft"></div>
<div id="messageright"></div>
</div>
</div>
</div>

<script src="include/js/md5.js?<?=VERHASH?>" type="text/javascript" reload="1"></script>

<script type="text/javascript" reload="1">
var pwdclear = 0;
var pagescroll = new pagescroll_class('floatlayout_login', 600, 400);
loadselect('loginfield', 0, 'floatlayout_login', 1);
loadselect('questionid', 0, 'floatlayout_login', 1);
document.body.focus();
$('loginform').username.focus();

<? if($sitemessage['login'] && $sitemessage['time']) { ?>
setTimeout('display_opacity(\'custominfo_login\',100)', <?=$sitemessage['time']?>);
<? } if($pwdsafety) { ?>
var pwmd5log = new Array();
function pwmd5() {
numargs = pwmd5.arguments.length;
for(var i = 0; i < numargs; i++) {
if(!pwmd5log[pwmd5.arguments[i]] || $(pwmd5.arguments[i]).value.length != 32) {
pwmd5log[pwmd5.arguments[i]] = $(pwmd5.arguments[i]).value = hex_md5($(pwmd5.arguments[i]).value);
}
}
}
<? } ?>

function clearpwd() {
if(pwdclear) {
$('password3').value = '';
}
pwdclear = 0;
}

function messagehandle_lostpwform(key) {
if(key == 141) {
pagescroll.right();
$('messageleft').innerHTML = '<h1>取回密码的方法发送到您的信箱中，请在 3 天之内到论坛修改您的密码。</h1>';
$('messageright').innerHTML = '<h1><a href="javascript:;" onclick="floatwin(\'close_login\')">关闭</a></h1>';
}
}

</script>
<? } updatesession(); if(empty($infloat)) { ?></div>

</div></div></div>

</div><? } include template('footer'); ?>
