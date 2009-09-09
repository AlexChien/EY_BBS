<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>
<? if($a == 'ls') { ?>
<div class="container">
	<? if($status) { ?>
		<div class="correctmsg"><p><? if($status == 1) { ?>成功删除公共消息<? } ?></p></div>
	<? } ?>
	<h3 class="marginbot">
		公共消息管理
		<a href="admin.php?m=pm&a=send" class="sgbtn">发送公共消息</a>
		<a href="admin.php?m=pm&a=clear" class="sgbtn">清理短消息</a>
	</h3>
	<div class="mainbox">
	<? if($pmlist) { ?>
		<form action="admin.php?m=pm&a=ls" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
			<table class="datalist fixwidth" onmouseover="addMouseEvent(this);">
				<tr>
					<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">删除</label></th>
					<th>标题</th>
					<th>发件人</th>
					<th>时间</th>
				</tr>
				<? foreach((array)$pmlist as $pm) {?>
					<tr>
						<td class="option"><input type="checkbox" name="delete[]" value="<?=$pm['pmid']?>" class="checkbox" /></td>
						<td><a href="admin.php?m=pm&a=view&pmid=<?=$pm['pmid']?>&<?=$extra?>"><? if($pm['subject']) { ?><?=$pm['subject']?><? } else { ?>[无标题]<? } ?></a></td>
						<td><?=$pm['msgfrom']?></td>
						<td><?=$pm['dateline']?></td>
					</tr>
				<? } ?>
				<tr class="nobg">
					<td><input type="submit" value="提 交" class="btn" /></td>
					<td class="tdpage" colspan="4"><?=$multipage?></td>
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
<? } elseif($a == 'view') { ?>
<div class="container">
	<h3 class="marginbot">公共消息管理<a href="admin.php?m=pm&a=ls&<?=$extra?>" class="sgbtn">返回</a></h3>
	<div class="mainbox">
	<? if($pms) { ?>
		<table class="datalist fixwidth">
			<tr><th>发件人</th><td><?=$pms['msgfrom']?></td></tr>
			<tr><th>时间</th><td><?=$pms['dateline']?></td></tr>
			<tr><th>标题</th><td><? if($pms['subject']) { ?><?=$pms['subject']?><? } else { ?>[无标题]<? } ?></td></tr>
		<tr class="nobg"><td colspan="2"><?=$pms['message']?></td></tr>
		</table>
	<? } else { ?>
		<div class="note">
			<p class="i">目前没有相关记录!</p>
		</div>
	<? } ?>
	</div>
</div>
<? } ?>

<? include $this->gettpl('footer');?>