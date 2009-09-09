<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>
<? if($iframe) { ?>
<script type="text/javascript">
	var uc_menu_data = new Array();
	o = document.getElementById('header_menu_menu');
	elems = o.getElementsByTagName('A');
	for(i = 0; i<elems.length; i++) {
		uc_menu_data.push(elems[i].innerHTML);
		uc_menu_data.push(elems[i].href);
	}
	try {
		parent.uc_left_menu(uc_menu_data);
		parent.uc_modify_sid('<?=$sid?>');
	} catch(e) {}
</script>
<? } ?>
<div class="container">
	<h3>UCenter 统计信息</h3>
	<ul class="memlist fixwidth">
		<li><em><? if($user['allowadminapp'] || $user['isfounder']) { ?><a href="admin.php?m=app&a=ls">应用总数</a><? } else { ?>应用总数<? } ?>:</em><?=$apps?></li>
		<li><em><? if($user['allowadminuser'] || $user['isfounder']) { ?><a href="admin.php?m=user&a=ls">用户总数</a><? } else { ?>用户总数<? } ?>:</em><?=$members?></li>
		<li><em><? if($user['allowadminpm'] || $user['isfounder']) { ?><a href="admin.php?m=pm&a=ls">短消息数</a><? } else { ?>短消息数<? } ?>:</em><?=$pms?></li>
		<li><em>好友记录数:</em><?=$friends?></li>
	</ul>
	
	<h3>通知状态</h3>
	<ul class="memlist fixwidth">
		<li><em><? if($user['allowadminnote'] || $user['isfounder']) { ?><a href="admin.php?m=note&a=ls">未发送的通知数</a><? } else { ?>未发送的通知数<? } ?>:</em><?=$notes?></li>
		<? if($errornotes) { ?>
			<li><em><? if($user['allowadminnote'] || $user['isfounder']) { ?><a href="admin.php?m=note&a=ls">通知失败的应用</a><? } else { ?>通知失败的应用<? } ?>:</em>		
			<? foreach((array)$errornotes as $appid => $error) {?>
				<?=$applist[$appid]['name']?>&nbsp;
			<?}?>
		<? } ?>
	</ul>
	
	<h3>系统信息</h3>
	<ul class="memlist fixwidth">
		<li><em>UCenter 程序版本:</em>UCenter <?=UC_SERVER_VERSION?> Release <?=UC_SERVER_RELEASE?> <a href="http://www.discuz.net/forumdisplay.php?fid=151" target="_blank">查看最新版本</a> 
		<li><em>操作系统及 PHP:</em><?=$serverinfo?></li>
		<li><em>服务器软件:</em><?=$_SERVER['SERVER_SOFTWARE']?></li>
		<li><em>MySQL 版本:</em><?=$dbversion?></li>
		<li><em>上传许可:</em><?=$fileupload?></li>
		<li><em>当前数据库尺寸:</em><?=$dbsize?></li>		
		<li><em>主机名:</em><?=$_SERVER['SERVER_NAME']?> (<?=$_SERVER['SERVER_ADDR']?>:<?=$_SERVER['SERVER_PORT']?>)</li>
		<li><em>magic_quote_gpc:</em><?=$magic_quote_gpc?></li>
		<li><em>allow_url_fopen:</em><?=$allow_url_fopen?></li>		
	</ul>
	<h3>UCenter 开发团队</h3>
	<ul class="memlist fixwidth">
		<li>
			<em>版权所有:</em>
			<em class="memcont"><a href="http://www.comsenz.com" target="_blank">&#x5eb7;&#x76db;&#x521b;&#x60f3;(&#x5317;&#x4eac;)&#x79d1;&#x6280;&#x6709;&#x9650;&#x516c;&#x53f8; (Comsenz Inc.)</a></em>
		</li>
		<li>
			<em>总策划兼项目经理:</em>
			<em class="memcont"><a href="http://www.discuz.net/space.php?uid=1" target="_blank">&#x6234;&#x5FD7;&#x5EB7; (Kevin 'Crossday' Day)</a></em>
		</li>
		<li>
			<em>开发团队:</em>
			<em class="memcont">
				<a href="http://www.discuz.net/space.php?uid=859" target="_blank">Hypo 'cnteacher' Wang</a>,
				<a href="http://www.discuz.net/space.php?uid=16678" target="_blank">Yang 'Dokho' Song</a>,
				<a href="http://www.discuz.net/space.php?uid=10407" target="_blank">Qiang Liu</a>,
				<a href="http://www.discuz.net/space.php?uid=80629" target="_blank">Ning 'Monkey' Hou</a>,				
				<a href="http://www.discuz.net/space.php?uid=15104" target="_blank">Xiongfei 'Redstone' Zhao</a>
			</em>
		</li>
		<li>
			<em>安全团队:</em>
			<em class="memcont">
				<a href="http://www.discuz.net/space.php?uid=859" target="_blank">Hypo 'cnteacher' Wang</a>,
				<a href="http://www.discuz.net/space.php?uid=210272" target="_blank">XiaoDun 'Kenshine' Fang</a>,
				<a href="http://www.discuz.net/space.php?uid=492114" target="_blank">Liang 'Metthew' Xu</a>,
				<a href="http://www.discuz.net/space.php?uid=285706" target="_blank">Wei (Sniffer) Yu</a>
			</em>
		</li>
		<li>
			<em>支持团队:</em>
			<em class="memcont">
				<a href="http://www.discuz.net/space.php?uid=2691" target="_blank">Liang 'Readme' Chen</a>,
				<a href="http://www.discuz.net/space.php?uid=1519" target="_blank">Yang 'Summer' Xia</a>,
				<a href="http://www.discuz.net/space.php?uid=1904" target="_blank">Tao 'FengXue' Cheng</a>
			</em>
		</li>
		<li>
			<em>界面与用户体验团队:</em>
			<em class="memcont">
				<a href="http://www.discuz.net/space.php?uid=294092" target="_blank">Fangming 'Lushnis' Li</a>,
				<a href="http://www.discuz.net/space.php?uid=717854" target="_blank">Ruitao 'Pony.M' Ma</a>
			</em>
		</li>
		<li>
			<em>感谢贡献者:</em>
			<em class="memcont">
				<a href="http://www.discuz.net/space.php?uid=122246" target="_blank">Heyond</a>
			</em>
		</li>
		<li>
			<em>公司网站:</em>
			<em class="memcont"><a href="http://www.comsenz.com" target="_blank">http://www.Comsenz.com</a></em>
		</li>
		<li>
			<em>产品官方网站:</em>
			<em class="memcont"><a href="http://www.discuz.com" target="_blank">http://www.Discuz.com</a></em>
		</li>
		<li>
			<em>产品官方论坛:</em>
			<em class="memcont"><a href="http://www.discuz.net" target="_blank">http://www.Discuz.net</a></em>
		</li>
	</ul>
</div>

<?=$ucinfo?>

<? include $this->gettpl('footer');?>