<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>
<div class="container">
	<? if($status) { ?>
		<div class="<? if($status > 0) { ?>correctmsg<? } else { ?>errormsg<? } ?>"><p><? if($status == 2) { ?>域名解析列表成功更新。<? } elseif($status == 1) { ?>域名解析添加成功。<? } ?></p></div>
	<? } ?>
	<div class="hastabmenu">
		<ul class="tabmenu">
			<li class="tabcurrent"><a href="#" class="tabcurrent">添加域名解析</a></li>
		</ul>
		<div class="tabcontentcur">
			<form action="admin.php?m=domain&a=ls" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
			<table>
				<tr>
					<td>域名:</td>
					<td><input type="text" name="domainnew" class="txt" /></td>
					<td>IP:</td>
					<td><input type="text" name="ipnew" class="txt" /></td>
					<td><input type="submit" value="提 交"  class="btn" /></td>
				</tr>
			</table>
			</form>
		</div>
	</div>
	<h3>域名解析列表</h3>
	<div class="mainbox">
		<? if($domainlist) { ?>
			<form action="admin.php?m=domain&a=ls" method="post">
				<input type="hidden" name="formhash" value="<?=FORMHASH?>">
				<table class="datalist fixwidth">
					<tr>
						<th width="10%"><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">删除</label></th>
						<th width="60%">域名</th>
						<th width="30%">IP</th>
					</tr>
					<? foreach((array)$domainlist as $domain) {?>
					<tr>
						<td><input type="checkbox" name="delete[]" value="<?=$domain['id']?>" class="checkbox" /></td>
						<td><input type="text" name="domain[<?=$domain['id']?>]" value="<?=$domain['domain']?>" title="点击编辑，提交后保存" class="txtnobd" onblur="this.className='txtnobd'" onfocus="this.className='txt'" style="text-align:left;" /></td>
						<td><input type="text" name="ip[<?=$domain['id']?>]" value="<?=$domain['ip']?>" title="点击编辑，提交后保存" class="txtnobd" onblur="this.className='txtnobd'" onfocus="this.className='txt'" style="text-align:left;" /></td>
					</tr>
					<? } ?>
					<tr class="nobg">
						<td><input type="submit" value="提 交" class="btn" /></td>
						<td class="tdpage" colspan="2"><?=$multipage?></td>
					</tr>
				</table>
			</form>
		<? } else { ?>
			<div class="note">
				<p class="i">目前没有相关记录!</p>
			</div>
		<? } ?>
	</div>
</div>

<? include $this->gettpl('footer');?>