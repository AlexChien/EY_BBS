<? if(!defined('IN_DISCUZ')) exit('Access Denied'); ?><?
$__UC_API = UC_API;$writedata = <<<EOF

<div class="sidebox s_clear">

EOF;
 if($GLOBALS['discuz_uid']) { 
$writedata .= <<<EOF

<div style="float:left; margin-right: 16px;">{$avatar}</div>
<a href="space.php?uid={$GLOBALS['discuz_uid']}" target="_blank">{$GLOBALS['discuz_userss']}</a>
<br />
<span title="总计在线 {$GLOBALS['oltime']} 小时">
EOF;
 if(!empty($GLOBALS['invisible'])) { 
$writedata .= <<<EOF
隐身
EOF;
 } else { 
$writedata .= <<<EOF
在线
EOF;
 } 
$writedata .= <<<EOF
</span>
<br />
<ul id="mycredits_menu" class="popupmenu_popup headermenu_popup" style="width:137px;display:none">
EOF;
 if(is_array($GLOBALS['extcredits'])) { foreach($GLOBALS['extcredits'] as $id => $credit) { 
$writedata .= <<<EOF
<li>{$credit['title']}: {$GLOBALS['extcredits'.$id]} {$credit['unit']}</li>
EOF;
 } } 
$writedata .= <<<EOF
</ul>
<div style="clear: both">	
<div style="float:right; width:60%">
<span id="mycredits_hover" class="dropmenu" onmouseover="showMenu(this.id, 0, 0, 2, 250, 0, 'mycredits')">积分: {$GLOBALS['_DSESSION']['credits']}</span><br />
帖子: {$GLOBALS['_DSESSION']['posts']}<br />
精华: {$GLOBALS['_DSESSION']['digestposts']}<br />
</div>
<div style="float:left; text-align:left; width:40%;">
<a id="mycredits" href="my.php?item=threads{$fidadd}" target="_blank">我的帖子</a><br />
<a href="my.php?item=favorites&amp;type=thread{$fidadd}" target="_blank">我的收藏</a><br />
<a href="my.php?item=subscriptions{$fidadd}" target="_blank">我的订阅</a><br />
</div>
</div>

EOF;
 } else { 
$writedata .= <<<EOF

<div style="float:left; text-align:left; width:40%;"><img src="{$__UC_API}/images/noavatar_small.gif"></div>
<a href="http://openid.enjoyoung.cn/account/new" onclick="window.location.href='http://openid.enjoyoung.cn/account/new';">{$GLOBALS['reglinkname']}</a><br />
<a href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;">登录</a>

EOF;
 } 
$writedata .= <<<EOF

</div>

EOF;
?>