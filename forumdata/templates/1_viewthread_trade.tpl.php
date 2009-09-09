<? if(!defined('IN_DISCUZ')) exit('Access Denied'); if(count($trades) > 1 || $discuz_uid == $thread['authorid']) { ?>
<div class="tradectrl">
<em>商品数: <?=$tradenum?></em>
<? if($discuz_uid == $thread['authorid']) { ?>
<a href="javascript:;" onclick="floatwin('open_tradeorder', 'misc.php?action=tradeorder&tid=<?=$tid?>', 600, 410);return false;">柜台商品管理</a>
<? if($allowposttrade) { ?>
<a href="javascript:;" onclick="floatwin('open_reply', 'post.php?action=reply&fid=<?=$fid?>&tid=<?=$tid?>&firstpid=<?=$post['pid']?>&addtrade=yes', 600, 410, '600,0');return false;">+ 添加商品</a>
<? } ?>
<span class="pipe">|</span>
<a href="javascript:;" onclick="window.open('my.php?item=selltrades&tid=<?=$tid?>','','');return false;">交易记录</a>				
<? } ?>
</div>
<? } if($tradenum) { if($trades) { ?>
<div class="tradethumblist s_clear"><? if(is_array($trades)) { foreach($trades as $key => $trade) { ?><div class="treadbox">
<dl id="trade<?=$trade['pid']?>">
<dt class="thumblist" onclick="display('trade<?=$trade['pid']?>');ajaxget('viewthread.php?do=tradeinfo&amp;tid=<?=$tid?>&amp;pid=<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>')">
<? if($trade['displayorder'] > 0) { ?><label style="display: none;">推荐商品</label><? } if($trade['thumb']) { ?>
<img src="<?=$trade['thumb']?>" onload="thumbImg(this, 1)" _width="96" _height="96" alt="<? if($trade['typeid']) { ?>[<?=$tradetypes[$trade['typeid']]?>] <? } ?><?=$trade['subject']?>" />
<? } else { ?>
<img src="<?=IMGDIR?>/trade_nophoto.gif" width="96" height="96" alt="<? if($trade['typeid']) { ?>[<?=$tradetypes[$trade['typeid']]?>] <? } ?><?=$trade['subject']?>" />
<? } ?>
</dt>
<dd>
<span class="infoview"><a href="javascript:;" onclick="display('trade<?=$trade['pid']?>');ajaxget('viewthread.php?do=tradeinfo&amp;tid=<?=$tid?>&amp;pid=<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>')">展开</a></span>
<h2 class="tradename"><a href="javascript:;" onclick="display('trade<?=$trade['pid']?>');ajaxget('viewthread.php?do=tradeinfo&amp;tid=<?=$tid?>&amp;pid=<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>')"><?=$trade['subject']?></a></h2>
<p>
<? if($trade['closed']) { ?>
<em>成交结束</em>
<? } elseif($trade['expiration'] > 0) { ?>
<?=$trade['expiration']?>天<?=$trade['expirationhour']?>小时
<? } elseif($trade['expiration'] == -1) { ?>
<em>成交结束</em>
<? } ?>
</p>
<? if($trade['costprice'] > 0) { ?>
<p>原价 <del><?=$trade['costprice']?></del> 元</p>
<? } ?>
<p>现价 <strong class="price"><?=$trade['price']?></strong> 元</p>
</dd>
</dl>
<div id="tradeinfo<?=$trade['pid']?>"></div>
</div>
<? if(count($trades) == 1) { ?>
<script type="text/javascript" onload="1">display('trade<?=$trade['pid']?>');ajaxget('viewthread.php?do=tradeinfo&tid=<?=$tid?>&pid=<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>','tradeinfo<?=$trade['pid']?>')</script>
<? } } } ?></div>				
<? } ?>

<div id="postmessage_<?=$post['pid']?>"><?=$post['counterdesc']?></div>
<? } else { ?>
<div class="locked">*** 本商品主题尚未开放 ***</div>
<? if($discuz_uid == $thread['authorid'] && $allowposttrade) { ?>
<br /><button type="button" onclick="floatwin('open_reply', 'post.php?infloat=yes&action=reply&fid=<?=$fid?>&tid=<?=$tid?>&addtrade=yes', 600, 410, '600,0');return false;">添加商品</button>
<? } } ?>
</div>

