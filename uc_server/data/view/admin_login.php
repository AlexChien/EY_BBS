<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<script type="text/javascript">
function $(id) {
	return document.getElementById(id);
}
</script>

<div class="container">
	<form action="admin.php?m=user&a=login" method="post" id="loginform" <? if($iframe) { ?>target="_self"<? } else { ?>target="_top"<? } ?>>
		<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
		<input type="hidden" name="seccodehidden" value="<?=$seccodeinit?>" />
		<input type="hidden" name="iframe" value="<?=$iframe?>" />
		<table class="mainbox">
			<tr>
				<td class="loginbox">
					<h1>UCenter</h1>
					<p>UCenter 是一个能沟通多个应用的桥梁，使各应用共享一个用户数据库，实现统一登录，注册，用户管理。</p>
				</td>
				<td class="login">
					<? if($errorcode == UC_LOGIN_ERROR_FOUNDER_PW) { ?><div class="errormsg loginmsg"><p>创始人密码错误</p></div>
					<? } elseif($errorcode == UC_LOGIN_ERROR_ADMIN_PW) { ?><div class="errormsg loginmsg"><p><em>登录失败!</em><br />用户名无效，或密码错误。</p></div>
					<? } elseif($errorcode == UC_LOGIN_ERROR_ADMIN_NOT_EXISTS) { ?><div class="errormsg loginmsg"><p>该管理员不存在</p></div>
					<? } elseif($errorcode == UC_LOGIN_ERROR_SECCODE) { ?><div class="errormsg loginmsg"><p>验证码输入错误</p></div>
					<? } elseif($errorcode == UC_LOGIN_ERROR_FAILEDLOGIN) { ?><div class="errormsg loginmsg"><p>密码重试次数过多，请十五分钟后再重新尝试</p></div>
					<? } ?>
					<p>
						<input type="radio" name="isfounder" value="1" class="radio" <? if((isset($_POST['isfounder']) && $isfounder) || !isset($_POST['isfounder'])) { ?>checked="checked"<? } ?> onclick="$('username').value='UCenter Administrator'; $('username').readOnly = true; $('username').disabled = true; $('password').focus();" id="founder" /><label for="founder">创始人</label>
						<input type="radio" name="isfounder" value="0" class="radio" <? if((isset($_POST['isfounder']) && !$isfounder)) { ?>checked="checked"<? } ?> onclick="$('username').value=''; $('username').readOnly = false; $('username').disabled = false; $('username').focus();" id="admin" /><label for="admin">管理员</label>
					</p>
					<p id="usernamediv">用户名:<input type="text" name="username" class="txt" tabindex="1" id="username" value="<?=$username?>" /></p>
					<p>密　码:<input type="password" name="password" class="txt" tabindex="2" id="password" value="<?=$password?>" /></p>
					<p>验证码:<input type="text" name="seccode" class="txt" tabindex="2" id="seccode" value="" style="margin-right:5px;width:85px;" /><img width="70" height="21" src="admin.php?m=seccode&seccodeauth=<?=$seccodeinit?>&<? echo rand();?>" /></p>
					<p class="loginbtn"><input type="submit" name="submit" value="登 录" class="btn" tabindex="3" /></p>
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
<? if((isset($_POST['isfounder']) && $isfounder) || !isset($_POST['isfounder'])) { ?>
	$('username').value='UCenter Administrator';
	$('username').disabled = true;
	$('username').readOnly = true;
	$('password').focus();
<? } else { ?>
	$('username').readOnly = false;
	$('username').readOnly = false;
	$('username').focus();
<? } ?>
</script>
<div class="footer">Powered by UCenter <?=UC_SERVER_VERSION?> &copy; 2001 - 2008 <a href="http://www.comsenz.com/" target="_blank">Comsenz</a> Inc.</div>
<? include $this->gettpl('footer');?>