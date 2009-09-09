<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>
<div class="container">

	<div class="note">
		<p class="i">点击“同步应用的积分设置”可以获取应用的积分设置，并且把当前设置结果通知给应用</p>
	</div>

	<? if($status) { ?>
		<div class="<? if($status > 0) { ?>correctmsg<? } else { ?>errormsg<? } ?>"><p><? if($status == 1) { ?>成功更新积分兑换方案。<? } elseif($status == -1) { ?>兑换前后应用相同，请重新设置。<? } ?></p></div>
	<? } ?>
	<div class="hastabmenu">
		<ul class="tabmenu">
			<li class="tabcurrent"><a href="#" class="tabcurrent">更新积分兑换方案</a></li>
		</ul>
		<div class="tabcontentcur">
			<form id="creditform" action="admin.php?m=credit&a=ls&addexchange=yes" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
			<table class="dbtb">
				<tr>
					<td class="tbtitle">兑换方向:</td>
					<td>
						<select onchange="switchcredit('src', this.value)" name="appsrc">
							<option>请选择</option><?=$appselect?>
						</select><span id="src"></span>
						&nbsp;&gt;&nbsp;
						<select onchange="switchcredit('desc', this.value)" name="appdesc">
							<option>请选择</option><?=$appselect?>
						</select><span id="desc"></span>
					</td>
				</tr>
				<tr>
					<td class="tbtitle">兑换比率:</td>
					<td>
						<input name="ratiosrc" size="3" value="<?=$ratiosrc?>" class="txt" style="margin-right:0" />
						&nbsp;:&nbsp;
						<input name="ratiodesc" size="3" value="<?=$ratiodesc?>" class="txt" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" value="提 交" class="btn" /> &nbsp;
						<input type="button" value="同步应用的积分设置" class="btn" onclick="location.href='admin.php?m=credit&a=sync&sid=<?=$sid?>'" />
					</td>
				</tr>
			</table>
			<div style="display: none">
			<script type="text/javascript">
			var credit = new Array();
			<? foreach((array)$creditselect as $select) {?><?=$select?><? } ?>
			<? if($appsrc) { ?>
				setselect($('creditform').appsrc, <?=$appsrc?>);
				switchcredit('src', <?=$appsrc?>);
			<? } ?>
			<? if($appdesc) { ?>
				setselect($('creditform').appdesc, <?=$appdesc?>);
				switchcredit('desc', <?=$appdesc?>);
			<? } ?>
			<? if($creditsrc) { ?>
				setselect($('creditform').creditsrc, <?=$creditsrc?>);
			<? } ?>
			<? if($creditdesc) { ?>
				setselect($('creditform').creditdesc, <?=$creditdesc?>);
			<? } ?>
			</script>
			</div>
			</form>
		</div>
	</div>
	<br />
	<h3>积分兑换</h3>
	<div class="mainbox">
		<? if($creditexchange) { ?>
			<form action="admin.php?m=credit&a=ls&delexchange=yes" method="post">
			<input type="hidden" name="formhash" value="<?=FORMHASH?>">
			<table class="datalist fixwidth" onmouseover="addMouseEvent(this);">
				<tr>
					<th><input type="checkbox" name="chkall" id="chkall" onclick="checkall('delete[]')" class="checkbox" /><label for="chkall">删除</label></th>
					<th style="padding-right: 11px; text-align: right">兑换方向</th>
					<th></th>
					<th style="text-align: center">兑换比率</th>
				</tr>
				<? foreach((array)$creditexchange as $key => $exchange) {?>
					<tr>
						<td class="option"><input type="checkbox" name="delete[]" value="<?=$key?>" class="checkbox" /></td>
						<td align="right"><?=$exchange['appsrc']?> <?=$exchange['creditsrc']?></td>
						<td>&nbsp;&gt;&nbsp;<?=$exchange['appdesc']?> <?=$exchange['creditdesc']?></td>
						<td align="center"><?=$exchange['ratiosrc']?> : <?=$exchange['ratiodesc']?></td>
					</tr>
				<?}?>
				<tr class="nobg">
					<td><input type="submit" value="提 交" class="btn" /></td>
				</tr>
			</table>
			</form>
		<? } else { ?>
			<div class="note">
				<p class="i">目前没有相关记录!</p>
			</div>
		<? } ?>
</div>

<? include $this->gettpl('footer');?>