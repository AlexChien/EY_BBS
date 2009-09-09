<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>
<div class="container">
	<h3 class="marginbot">
		事件列表
		<? if($user['allowadminnote'] || $user['isfounder']) { ?><a href="admin.php?m=note&a=ls" class="sgbtn">通知列表</a><? } ?>
		<? if($user['allowadminlog'] || $user['isfounder']) { ?><a href="admin.php?m=log&a=ls" class="sgbtn">日志列表</a><? } ?>
		<a href="admin.php?m=mail&a=ls" class="sgbtn">邮件队列</a>
	</h3>
	<div class="mainbox">
		<? if($feedlist) { ?>
			<form action="admin.php?m=note&a=ls" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
			<table class="datalist" style="table-layout:fixed">
				<tr>
					<th width="100">时间</th>
					<th>&nbsp;</th>
				</tr>
				<? foreach((array)$feedlist as $feed) {?>
					<tr>
						<td><?=$feed['dateline']?></td>
						<td><?=$feed['title_template']?></td>
					</tr>
				<? } ?>
				<tr class="nobg">
					<td></td>
					<td class="tdpage"><?=$multipage?></td>
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