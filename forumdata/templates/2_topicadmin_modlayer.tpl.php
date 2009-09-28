<? if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
	<div id="modlayer" style="display: none;position:position">
<input type="hidden" name="optgroup" />
<input type="hidden" name="operation" />
<a class="collapse" href="javascript:;" onclick="$('modlayer').className='collapsed'"><img src="<?=IMGDIR?>/collapsed_yes.gif" alt="缩小" title="缩小" /></a>
<label><input class="checkbox" type="checkbox" name="chkall" onclick="if(!($('modcount').innerHTML = modclickcount = checkall(this.form, 'moderate'))) {$('modlayer').style.display = 'none';}" /> 全选</label>
<span>选中</span><strong onmouseover="$('moremodoption').style.display='block'" onclick="$('modlayer').className=''" id="modcount"></strong><span>篇: </span>
<strong><a href="javascript:;" onclick="modthreads(3, 'delete');return false;">删除</a></strong>
<span class="pipe">|</span>
<strong><a href="javascript:;" onclick="modthreads(2, 'move');return false;">移动</a></strong>
<span class="pipe">|</span>
<strong><a href="javascript:;" onclick="modthreads(2, 'type');return false;">分类</a></strong>

<div id="moremodoption">
<hr class="solidline" />
<a href="javascript:;" onclick="modthreads(1, 'stick');return false;">置顶</a>
<a href="javascript:;" onclick="modthreads(1, 'highlight');return false;">高亮</a>
<a href="javascript:;" onclick="modthreads(1, 'digest');return false;">精华</a>
<? if($forum['modrecommend']['open'] && $forum['modrecommend']['sort'] != 1) { ?>
<a href="javascript:;" onclick="modthreads(1, 'recommend');return false;">推荐</a>
<? } ?>
<span class="pipe">|</span>
<a href="javascript:;" onclick="modthreads(3, 'bump');return false;">提升下沉</a>
&nbsp;
<a href="javascript:;" onclick="modthreads(4);return false;">关闭打开</a>
</div>
</div>
<script type="text/javascript">
modclickcount = 0;
function modclick(obj) {
if(obj.checked) {
modclickcount++;
} else {
modclickcount--;
}
$('modcount').innerHTML = modclickcount;
if(modclickcount > 0) {
var top_offset = obj.offsetTop;
while((obj = obj.offsetParent).id != 'threadlist') {
top_offset += obj.offsetTop;
}
$('modlayer').style.top = top_offset - 7 + 'px';
$('modlayer').style.display = '';
} else {
$('modlayer').style.display = 'none';
}
}
function modthreads(optgroup, operation) {
var checked = 0;
var operation = !operation ? '' : operation;
for(var i = 0; i < $('moderate').elements.length; i++) {
if($('moderate').elements[i].name.match('moderate') && $('moderate').elements[i].checked) {
checked = 1;
break;
}
}
if(!checked) {
alert('请选择需要操作的帖子');
} else {
floatwinreset = 1;
floatwin('open_mods', '', 250, optgroup < 2 ? 365 : 215);
$('moderate').optgroup.value = optgroup;
$('moderate').operation.value = operation;
$('floatwin_mods').innerHTML = '';
ajaxpost('moderate', 'floatwin_mods', '');
}
}
</script>
