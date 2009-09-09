<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>
<script type="text/javascript">
var apps = new Array();
var run = 0;
function testlink() {
	if(apps[run]) {
		$('status_' + apps[run]).innerHTML = '正在连接...';
		$('link_' + apps[run]).src = $('link_' + apps[run]).getAttribute('testlink') + '&sid=<?=$sid?>';
	}
	run++;
}
window.onload = testlink;
</script>
<div class="container">
	<? if($a == 'ls') { ?>
		<h3 class="marginbot">应用列表<a href="admin.php?m=app&a=add" class="sgbtn">添加新应用</a></h3>
		<? if(!$status) { ?>
			<div class="note fixwidthdec">
				<p class="i">如果出现“通信失败”，请点击“编辑”尝试设置应用域名对应的 IP。</p>
			</div>
		<? } elseif($status == '2') { ?>
			<div class="correctmsg"><p>应用列表成功更新。</p></div>
		<? } ?>
		<div class="mainbox">
			<? if($applist) { ?>
				<form action="admin.php?m=app&a=ls" method="post">
					<input type="hidden" name="formhash" value="<?=FORMHASH?>">
					<table class="datalist fixwidth" onmouseover="addMouseEvent(this);">
						<tr>
							<th nowrap="nowrap"><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">删除</label></th>
							<th nowrap="nowrap">ID</th>
							<th nowrap="nowrap">应用名称</th>
							<th nowrap="nowrap">应用的 URL</th>
							<th nowrap="nowrap">通信情况</th>
							<th nowrap="nowrap">详情</th>
						</tr>
						<? $i = 0;?>
						<? foreach((array)$applist as $app) {?>
							<tr>
								<td width="50"><input type="checkbox" name="delete[]" value="<?=$app['appid']?>" class="checkbox" /></td>
								<td width="35"><?=$app['appid']?></td>
								<td><a href="admin.php?m=app&a=detail&appid=<?=$app['appid']?>"><strong><?=$app['name']?></strong></a></td>
								<td><a href="<?=$app['url']?>" target="_blank"><?=$app['url']?></a></td>
								<td width="90"><div id="status_<?=$app['appid']?>"></div><script id="link_<?=$app['appid']?>" testlink="admin.php?m=app&a=ping&inajax=1&url=<? echo urlencode($app['url']);?>&ip=<? echo urlencode($app['ip']);?>&appid=<?=$app['appid']?>&random=<? echo rand()?>"></script><script>apps[<?=$i?>] = '<?=$app['appid']?>';</script></td>
								<td width="40"><a href="admin.php?m=app&a=detail&appid=<?=$app['appid']?>">编辑</a></td>
							</tr>
							<? $i++?>
						<? } ?>
						<tr class="nobg">
							<td colspan="9"><input type="submit" value="提 交" class="btn" /></td>
						</tr>
					</table>
					<div class="margintop"></div>
				</form>
			<? } else { ?>
				<div class="note">
					<p class="i">目前没有相关记录!</p>
				</div>
			<? } ?>
		</div>
	<? } elseif($a == 'add') { ?>
		<h3 class="marginbot">添加新应用<a href="admin.php?m=app&a=ls" class="sgbtn">返回应用列表</a></h3>
		<div class="mainbox">
			<table class="opt">
				<tr>
					<th>选择安装方式:</th>
				</tr>
				<tr>
					<td>
						<input type="radio" name="installtype" class="radio" checked="checked" onclick="$('url').style.display='';$('custom').style.display='none';" />URL 安装 (推荐)
						<input type="radio" name="installtype" class="radio" onclick="$('url').style.display='none';$('custom').style.display='';" />自定义安装
					</td>
				</tr>
			</table>
			<div id="url">
				<form method="post" action="" target="_blank" onsubmit="document.appform.action=document.appform.appurl.value;" name="appform">
					<table class="opt">
						<tr>
							<th>应用程序安装地址:</th>
						</tr>
						<tr>
							<td><input type="text" name="appurl" size="50" value="http://domainname/install/index.php" style="width:300px;" /></td>
						</tr>
					</table>
					<div class="opt">
						<input type="hidden" name="ucapi" value="<?=UC_API?>" />
						<input type="hidden" name="ucfounderpw" value="<?=$md5ucfounderpw?>" />
						<input type="submit" name="installsubmit"  value=" 安 装 " class="btn" />
					</div>
				</form>
			</div>
			<div id="custom" style="display:none;">
				<form action="admin.php?m=app&a=add" method="post">
				<input type="hidden" name="formhash" value="<?=FORMHASH?>">
					<table class="opt">
						<tr>
							<th colspan="2">应用名称:</th>
						</tr>
						<tr>
							<td><input type="text" class="txt" name="name" value="" /></td>
							<td>限 20 字节。</td>
						</tr>
						<tr>
							<th colspan="2">应用的 URL:</th>
						</tr>
						<tr>
							<td><input type="text" class="txt" name="url" value="" /></td>
							<td>该应用与 UCenter 通信的接口 URL，结尾请不要加“/” </td>
						</tr>
						<tr>
							<th colspan="2">应用 IP:</th>
						</tr>
						<tr>
							<td><input type="text" class="txt" name="ip" value="" /></td>
							<td>正常情况下留空即可。如果由于域名解析问题导致 UCenter 与该应用通信失败，请尝试设置为该应用所在服务器的 IP 地址。</td>
						</tr>
						<tr>
							<th colspan="2">通信密钥:</th>
						</tr>
						<tr>
							<td><input type="text" class="txt" name="authkey" value="" /></td>
							<td>只允许使用英文字母及数字，限 64 字节。应用端的通信密钥必须与此设置保持一致，否则该应用将无法与 UCenter 正常通信。</td>
						</tr>
						<tr>
							<th colspan="2">应用类型:</th>
						</tr>
						<tr>
							<td>
							<select name="type">
								<? foreach((array)$typelist as $typeid => $typename) {?>
									<option value="<?=$typeid?>"> <?=$typename?> </option>
								<?}?>
							</select>
							</td>
							<td></td>
						</tr>
						<tr>
							<th colspan="2">应用的物理路径:</th>
						</tr>
						<tr>
							<td>
								<input type="text" class="txt" name="apppath" value="" />
							</td>
							<td>默认请留空，如果填写的为相对路径（相对于UC），程序会自动转换为绝对路径，如 ../</td>
						</tr>
						<tr>
							<th colspan="2">查看个人资料页面地址:</th>
						</tr>
						<tr>
							<td>
								<input type="text" class="txt" name="viewprourl" value="" />
							</td>
							<td>URL中域名后面的部分，如：/space.php?uid=%s 这里的 %s 代表uid</td>
						</tr>
						<tr>
							<th colspan="2">应用接口文件名称:</th>
						</tr>
						<tr>
							<td>
								<input type="text" class="txt" name="apifilename" value="uc.php" />
							</td>
							<td>应用接口文件名称，不含路径，默认为uc.php</td>
						</tr>
						<tr>
							<th colspan="2">标签单条显示模板:</th>
						</tr>
						<tr>
							<td><textarea class="area" name="tagtemplates"></textarea></td>
							<td valign="top">当前应用的标签数据显示在其它应用时的单条数据模板。</td>
						</tr>
						<tr>
							<th colspan="2">标签模板标记说明:</th>
						</tr>
						<tr>
							<td><textarea class="area" name="tagfields"><?=$tagtemplates['fields']?></textarea></td>
							<td valign="top">一行一个标记说明条目，用逗号分割标记和说明文字。如：<br />subject,主题标题<br />url,主题地址</td>
						</tr>
						<tr>
							<th colspan="2">是否开启同步登录:</th>
						</tr>
						<tr>
							<td>
								<input type="radio" class="radio" id="yes" name="synlogin" value="1" /><label for="yes">是</label>
								<input type="radio" class="radio" id="no" name="synlogin" value="0" checked="checked" /><label for="no">否</label>
							</td>
							<td>开启同步登录后，当用户在登录其他应用时，同时也会登录该应用。</td>
						</tr>
						<tr>
							<th colspan="2">是否接受通知:</th>
						</tr>
						<tr>
							<td>
								<input type="radio" class="radio" id="yes" name="recvnote" value="1"/><label for="yes">是</label>
								<input type="radio" class="radio" id="no" name="recvnote" value="0" checked="checked" /><label for="no">否</label>
							</td>
							<td></td>
						</tr>
					</table>
					<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
				</form>
			</div>
		</div>
	<? } else { ?>
		<h3 class="marginbot">编辑应用<a href="admin.php?m=app&a=ls" class="sgbtn">返回应用列表</a></h3>
		<? if($updated) { ?>
			<div class="correctmsg"><p>更新成功。</p></div>
		<? } elseif($addapp) { ?>
			<div class="correctmsg"><p>成功添加应用。</p></div>
		<? } ?>
		<div class="mainbox">
			<form action="admin.php?m=app&a=detail&appid=<?=$appid?>" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
				<table class="opt">
					<tr>
						<th colspan="2">ID: <?=$appid?></th>
					</tr>
					<tr>
						<th colspan="2">应用名称:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="name" value="<?=$name?>" /></td>
						<td>限 20 字节。</td>
					</tr>
					<tr>
						<th colspan="2">应用的 URL:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="url" value="<?=$url?>" /></td>
						<td>该应用与 UCenter 通信的接口 URL，结尾请不要加“/” </td>
					</tr>
					<tr>
						<th colspan="2">应用 IP:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="ip" value="<?=$ip?>" /></td>
						<td>正常情况下留空即可。如果由于域名解析问题导致 UCenter 与该应用通信失败，请尝试设置为该应用所在服务器的 IP 地址。</td>
					</tr>
					<tr>
						<th colspan="2">通信密钥:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="authkey" value="<?=$authkey?>" /></td>
						<td>只允许使用英文字母及数字，限 64 字节。应用端的通信密钥必须与此设置保持一致，否则该应用将无法与 UCenter 正常通信。</td>
					</tr>
					<tr>
						<th colspan="2">应用类型:</th>
					</tr>
					<tr>
						<td>
						<select name="type">
							<? foreach((array)$typelist as $typeid => $typename) {?>
							<option value="<?=$typeid?>" <? if($typeid == $type) { ?>selected="selected"<? } ?>> <?=$typename?> </option>
							<?}?>
						</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<th colspan="2">应用的物理路径:</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="txt" name="apppath" value="<?=$apppath?>" />
						</td>
						<td>默认请留空，如果填写的为相对路径（相对于UC），程序会自动转换为绝对路径，如 ../</td>
					</tr>
					<tr>
						<th colspan="2">查看个人资料页面地址:</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="txt" name="viewprourl" value="<?=$viewprourl?>" />
						</td>
						<td>URL中域名后面的部分，如：/space.php?uid=%s 这里的 %s 代表uid</td>
					</tr>
					<tr>
						<th colspan="2">应用接口文件名称:</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="txt" name="apifilename" value="<?=$apifilename?>" />
						</td>
						<td>应用接口文件名称，不含路径，默认为uc.php</td>
					</tr>
					<tr>
						<th colspan="2">标签单条显示模板:</th>
					</tr>
					<tr>
						<td><textarea class="area" name="tagtemplates"><?=$tagtemplates['template']?></textarea></td>
						<td valign="top">当前应用的标签数据显示在其它应用时的单条数据模板。</td>
					</tr>
					<tr>
						<th colspan="2">标签模板标记说明:</th>
					</tr>
					<tr>
						<td><textarea class="area" name="tagfields"><?=$tagtemplates['fields']?></textarea></td>
						<td valign="top">一行一个标记说明条目，用逗号分割标记和说明文字。如：<br />subject,主题标题<br />url,主题地址</td>
					</tr>
					<tr>
						<th colspan="2">是否开启同步登录:</th>
					</tr>
					<tr>
						<td>
							<input type="radio" class="radio" id="yes" name="synlogin" value="1" <?=$synlogin[1]?> /><label for="yes">是</label>
							<input type="radio" class="radio" id="no" name="synlogin" value="0" <?=$synlogin[0]?> /><label for="no">否</label>
						</td>
						<td>开启同步登录后，当用户在登录其他应用时，同时也会登录该应用。</td>
					</tr>
					<tr>
						<th colspan="2">是否接受通知:</th>
					</tr>
					<tr>
						<td>
							<input type="radio" class="radio" id="yes" name="recvnote" value="1" <?=$recvnotechecked[1]?> /><label for="yes">是</label>
							<input type="radio" class="radio" id="no" name="recvnote" value="0" <?=$recvnotechecked[0]?> /><label for="no">否</label>
						</td>
						<td></td>
					</tr>
				</table>
				<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
<? if($isfounder) { ?>
				<table class="opt">
					<tr>
						<th colspan="2">应用的 UCenter 配置信息:</th>
					</tr>
					<tr>
						<th>
<textarea class="area" onFocus="this.select()">
define('UC_CONNECT', 'mysql');
define('UC_DBHOST', '<?=UC_DBHOST?>');
define('UC_DBUSER', '<?=UC_DBUSER?>');
define('UC_DBPW', '<?=UC_DBPW?>');
define('UC_DBNAME', '<?=UC_DBNAME?>');
define('UC_DBCHARSET', '<?=UC_DBCHARSET?>');
define('UC_DBTABLEPRE', '`<?=UC_DBNAME?>`.<?=UC_DBTABLEPRE?>');
define('UC_DBCONNECT', '0');
define('UC_KEY', '<?=$authkey?>');
define('UC_API', '<?=UC_API?>');
define('UC_CHARSET', '<?=UC_CHARSET?>');
define('UC_IP', '');
define('UC_APPID', '<?=$appid?>');
define('UC_PPP', '20');
</textarea>
						</th>
						<td>当应用的 UCenter 配置信息丢失时可复制左侧的代码到应用的配置文件中</td>
					</tr>
				</table>
<? } ?>
			</form>
		</div>
	<? } ?>
</div>

<? include $this->gettpl('footer');?>