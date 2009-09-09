<? if(!defined('IN_DISCUZ')) exit('Access Denied'); function tpl_hide_credits_hidden($creditsrequire) {
global $hideattach;
 ?><?
$return = <<<EOF
<div class="locked">本帖隐藏的内容需要积分高于 {$creditsrequire} 才可浏览</div>
EOF;
?><? return $return; }

function tpl_hide_credits($creditsrequire, $message) {
global $hideattach;
 ?><?
$return = <<<EOF
<div class="locked">以下内容需要积分高于 {$creditsrequire} 才可浏览</div>
{$message}<br /><br />
<br />
EOF;
?><? return $return; }

function tpl_codedisp($discuzcodes, $code) {
 ?><?
$return = <<<EOF
<div class="blockcode"><div id="code{$discuzcodes['codecount']}"><ol><li>{$code}</ol></div><em onclick="copycode($('code{$discuzcodes['codecount']}'));">复制代码</em></div>
EOF;
?><? return $return; }

function tpl_quote() {
 ?><?
$return = <<<EOF
<div class="quote"><blockquote>\\1</blockquote></div>
EOF;
?><? return $return; }

function tpl_free() {
 ?><?
$return = <<<EOF
<div class="quote"><blockquote>\\1</blockquote></div>
EOF;
?><? return $return; }

function tpl_hide_reply() {
global $hideattach;
 ?><?
$return = <<<EOF
<div class="showhide"><h4>本帖隐藏的内容需要回复才可以浏览</h4>\\1</div>

EOF;
?><? return $return; }

function tpl_hide_reply_hidden() {
 ?><?
$return = <<<EOF
<div class="locked">本帖隐藏的内容需要回复才可以浏览</div>
EOF;
?><? return $return; }

function attachlist($attach, $sidauth) {
global $attachrefcheck, $extcredits, $creditstrans, $creditstransextra, $ftp, $thumbstatus, $authkey, $timestamp, $attachimgpost, $fid;
$k = md5($attach['aid'].md5($authkey).$timestamp);
$hideurl = $attach['remote'] && ($ftp['hideurl'] || ($attach['isimage'] && $attachimgpost && strtolower(substr($ftp['attachurl'], 0, 3)) == 'ftp'));
 ?><?
$return = <<<EOF


EOF;
 if($attach['attachimg']) { if(!IS_ROBOT) { 
$return .= <<<EOF

<dl class="t_attachlist attachimg">
<dt>
</dt>
<dd>
<p class="imgtitle">
<a href="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;fid={$fid}&amp;nothumb=yes&amp;sid={$sidauth}" onmouseover="showMenu(this.id,false,2)" id="aid{$attach['aid']}" class="bold" target="_blank">{$attach['filename']}</a>
<em>({$attach['attachsize']})</em>
</p>
<div  class="attach_popup" id="aid{$attach['aid']}_menu" style="display: none">
<div class="cornerlayger">
<p>下载次数:{$attach['downloads']}</p>
<p>{$attach['dateline']}</p>
</div>
<div class="minicorner"></div>
</div>
<p>

EOF;
 if($attach['readperm']) { 
$return .= <<<EOF
阅读权限: <strong>{$attach['readperm']}</strong>
EOF;
 } if($attach['price']) { 
$return .= <<<EOF
售价: <strong>{$extcredits[$creditstransextra['1']]['title']} {$attach['price']} {$extcredits[$creditstransextra['1']]['unit']}</strong> &nbsp;[<a href="misc.php?action=viewattachpayments&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 410);return false;" target="_blank">记录</a>]

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;[<a href="misc.php?action=attachpay&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 250);return false;" target="_blank">购买</a>]

EOF;
 } } 
$return .= <<<EOF

</p>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } if(!$attach['price'] || $attach['payed']) { 
$return .= <<<EOF

<p>

EOF;
 if($thumbstatus && $attach['thumb']) { if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<a href="javascript:;"><img onclick="zoom(this, 'attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;fid={$fid}&amp;noupdate=yes&amp;nothumb=yes&sid={$sidauth}')" src="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;sid={$sidauth}" alt="{$attach['filename']}" /></a>

EOF;
 } else { 
$return .= <<<EOF

<a href="javascript:;"><img onclick="zoom(this, '{$attach['url']}/{$attach['attachment']}')" src="{$attach['url']}/{$attach['attachment']}.thumb.jpg" alt="{$attach['filename']}" /></a>

EOF;
 } } else { $GLOBALS['aimgs'][$attach['pid']][] = $attach['aid']; $widthcode = attachwidth($attach['width']); if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<img src="images/common/none.gif" file="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;fid={$fid}&amp;noupdate=yes&amp;sid={$sidauth}" {$widthcode} id="aimg_{$attach['aid']}" alt="{$attach['filename']}" />

EOF;
 } else { 
$return .= <<<EOF

<img src="images/common/none.gif" file="{$attach['url']}/{$attach['attachment']}" id="aimg_{$attach['aid']}" {$widthcode} alt="{$attach['filename']}" />

EOF;
 } } 
$return .= <<<EOF

</p>

EOF;
 } 
$return .= <<<EOF

</dd>

EOF;
 } else { 
$return .= <<<EOF

<dl class="t_attachlist attachimg">

EOF;
 if(!$attach['price'] || $attach['payed']) { if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<img src="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;fid={$fid}&amp;noupdate=yes&amp;sid={$sidauth}" alt="{$attach['filename']}" />

EOF;
 } else { 
$return .= <<<EOF

<img src="{$attach['url']}/{$attach['attachment']}" alt="{$attach['filename']}" />

EOF;
 } } } } else { 
$return .= <<<EOF

<dl class="t_attachlist">
<dt>
{$attach['attachicon']}
</dt>
<dd>
<p class="attachname">
<a href="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;fid={$fid}&amp;sid={$sidauth}"  onmouseover="showMenu(this.id,false,2)" id="aid{$attach['aid']}" target="_blank" title="">{$attach['filename']}</a> ({$attach['attachsize']})
<div  class="attach_popup" id="aid{$attach['aid']}_menu" style="display: none">
<div class="cornerlayger">
<p>下载次数:{$attach['downloads']}</p>
<p>{$attach['dateline']}</p>
</div>
<div class="minicorner"></div>
</div>
</p>
<p>

EOF;
 if($attach['readperm']) { 
$return .= <<<EOF
阅读权限: <strong>{$attach['readperm']}</strong>
EOF;
 } if($attach['price']) { 
$return .= <<<EOF

售价: <strong>{$extcredits[$creditstransextra['1']]['title']} {$attach['price']} {$extcredits[$creditstransextra['1']]['unit']}</strong> &nbsp;[<a href="misc.php?action=viewattachpayments&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 410);return false;" target="_blank">记录</a>]

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;[<a href="misc.php?action=attachpay&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 250);return false;" target="_blank">购买</a>]

EOF;
 } } 
$return .= <<<EOF

</p>

EOF;
 if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } 
$return .= <<<EOF

</dd>

EOF;
 } 
$return .= <<<EOF

</dl>

EOF;
?><? return $return; }

function attachinpost($attach, $sidauth) {
global $attachrefcheck, $extcredits, $creditstrans, $creditstransextra, $ftp, $thumbstatus, $authkey, $timestamp, $attachimgpost;
$k = md5($attach['aid'].md5($authkey).$timestamp);
$hideurl = $attach['remote'] && ($ftp['hideurl'] || ($attach['isimage'] && $attachimgpost && strtolower(substr($ftp['attachurl'], 0, 3)) == 'ftp'));
 ?><?
$__IMGDIR = IMGDIR;$return = <<<EOF


EOF;
 if(!isset($attach['unpayed'])) { if($attach['attachimg']) { if(!IS_ROBOT) { 
$return .= <<<EOF

<span style="position: absolute; display: none" id="attach_{$attach['aid']}" onmouseover="showMenu(this.id, 0, 1)"><img src="{$__IMGDIR}/attachimg.gif" border="0"></span>

EOF;
 if($thumbstatus && $attach['thumb']) { if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<a href="javascript:;"><img onclick="zoom(this, 'attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;noupdate=yes&amp;nothumb=yes&sid={$sidauth}')" src="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;sid={$sidauth}" border="0" id="aimg_{$attach['aid']}" onmouseover="showMenu(this.id, false, 2)" /></a>

EOF;
 } else { 
$return .= <<<EOF

<a href="javascript:;"><img onclick="zoom(this, '{$attach['url']}/{$attach['attachment']}')" src="{$attach['url']}/{$attach['attachment']}.thumb.jpg" border="0" id="aimg_{$attach['aid']}" onmouseover="showMenu(this.id, false, 2)" /></a>

EOF;
 } } else { $GLOBALS['aimgs'][$attach['pid']][] = $attach['aid']; $widthcode = attachwidth($attach['width']); if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<img src="images/common/none.gif" file="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;noupdate=yes&amp;sid={$sidauth}" {$widthcode} id="aimg_{$attach['aid']}" onmouseover="showMenu(this.id, false, 2)" alt="{$attach['filename']}" />

EOF;
 } else { 
$return .= <<<EOF

<img src="images/common/none.gif" file="{$attach['url']}/{$attach['attachment']}" {$widthcode} id="aimg_{$attach['aid']}" onmouseover="showMenu(this.id, false, 2)" alt="{$attach['filename']}" />

EOF;
 } } 
$return .= <<<EOF

<div class="t_attach" id="aimg_{$attach['aid']}_menu" style="position: absolute; display: none">
<a href="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;nothumb=yes&amp;sid={$sidauth}" title="{$attach['filename']}" target="_blank"><strong>下载</strong></a> ({$attach['attachsize']})<br />

EOF;
 if($attach['description']) { 
$return .= <<<EOF
{$attach['description']}<br />
EOF;
 } } else { 
$return .= <<<EOF

<dl class="t_attachlist attachimg">

EOF;
 if(!$attach['price'] || $attach['payed']) { if($attach['description']) { 
$return .= <<<EOF
<p>{$attach['description']}</p>
EOF;
 } if($attachrefcheck || $hideurl) { 
$return .= <<<EOF

<img src="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;noupdate=yes&amp;sid={$sidauth}" alt="{$attach['filename']}" />

EOF;
 } else { 
$return .= <<<EOF

<img src="{$attach['url']}/{$attach['attachment']}" alt="{$attach['filename']}" />

EOF;
 } } 
$return .= <<<EOF

<div style="display: none">

EOF;
 } } else { 
$return .= <<<EOF

{$attach['attachicon']} <span style="white-space: nowrap" id="attach_{$attach['aid']}" onmouseover="showMenu(this.id, false, 2)"><a href="attachment.php?aid={$attach['aid']}&amp;k={$k}&amp;t={$timestamp}&amp;sid={$sidauth}" target="_blank"><strong>{$attach['filename']}</strong></a></span> ({$attach['attachsize']})
<div class="t_attach" id="attach_{$attach['aid']}_menu" style="position: absolute; display: none">

EOF;
 if($attach['description']) { 
$return .= <<<EOF
{$attach['description']}<br />
EOF;
 } 
$return .= <<<EOF

下载次数: {$attach['downloads']}<br />

EOF;
 if($attach['readperm']) { 
$return .= <<<EOF
阅读权限: {$attach['readperm']}<br />
EOF;
 } } if($attach['price']) { 
$return .= <<<EOF

售价: {$extcredits[$creditstransextra['1']]['title']} {$attach['price']} {$extcredits[$creditstransextra['1']]['unit']} &nbsp;<a href="misc.php?action=viewattachpayments&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 410);return false;" target="_blank">[记录]</a>

EOF;
 if(!$attach['payed']) { 
$return .= <<<EOF

&nbsp;<a href="misc.php?action=attachpay&amp;aid={$attach['aid']}" onclick="floatwin('open_attachpay', this.href, 600, 250);return false;" target="_blank">[购买]</a>

EOF;
 } } 
$return .= <<<EOF

<div class="t_smallfont">{$attach['dateline']}</div></div>

EOF;
 } else { 
$return .= <<<EOF

{$attach['attachicon']} <strong>收费附件: {$attach['filename']}</strong>

EOF;
 } 
$return .= <<<EOF


EOF;
?><? return $return; }

 ?>