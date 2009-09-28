<? if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
<title><?=$navtitle?> <?=$bbname?> <?=$seotitle?> - Powered by Discuz!</title>
<meta name="keywords" content="<?=$metakeywords?>Discuz!,Board,Comsenz,forums,bulletin board,<?=$seokeywords?>" />
<meta name="description" content="<?=$bbname?> <?=$seodescription?> - Discuz! Board" />
<meta name="generator" content="Discuz! <?=$version?> with Templates 5.0.0" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="archives" title="<?=$bbname?>" href="<?=$boardurl?>archiver/" />
<?=$extrahead?>
<link rel="stylesheet" type="text/css" id="css" href="forumdata/cache/style_<?=STYLEID?>_common.css?<?=VERHASH?>" />
<style type="text/css">
html { background: #FFF; }
body {
margin: 0px;
padding: 2px;
background: #FFF;
/*background-image: url(<?=IMGDIR?>/frame_bg.gif);
background-attachment: fixed;
background-position: right;
background-repeat: repeat-y;*/
}
.out {
padding: 0.2em;
width: 96%;
height: 100%;
overflow: auto;
text-align: left;
overflow-x: hidden;
}

.tree {
font: <?=FONTSIZE?> <?=FONT?>;
color: <?=MIDTEXT?>;
white-space: nowrap;
padding-left: 0.4em;
}
.tree img {
border: 0px;
vertical-align: middle;
}
.tree a.node, .tree a.checked {
white-space: nowrap;
padding: 1px 2px 1px 2px;
}
.tree a.node:hover, .tree a.checked:hover {
text-decoration: underline;
}
.tree a.checked {
background: <?=FRAMEBGCOLOR?>;
color: <?=TABLETEXT?>;
}
.tree .clip {
overflow: hidden;
}
h1 { padding-left: 10px; font-weight: bold; height: 2.4em; line-height: 2.4em; }
h2 { padding-left: 10px; border-bottom: 1px solid #DDD; font-weight: normal; margin-bottom: 2em;}
h3 { border: 1px solid <?=CATBORDER?>; padding: 3px 5px; background: <?=CATCOLOR?> no-repeat 3px 50%; margin-top: 2em; padding-top: 0.4em; font-weight: normal;}
strong { font-weight: bold; }
</style>
<script type="text/javascript" />var IMGDIR = '<?=IMGDIR?>';var attackevasive = '<?=$attackevasive?>';</script>
<script src="include/js/common.js?<?=VERHASH?>" type="text/javascript" /></script>
<script src="include/js/tree.js?<?=VERHASH?>" type="text/javascript" /></script>
<script src="include/js/iframe.js?<?=VERHASH?>" type="text/javascript" /></script>

</head>
<body>
<div class="out">
<h1><?=$bbname?></h1>
<h2>
<? if($discuz_uid) { ?>
<span style="font-weight: bold;"><a href="space.php?uid=<?=$discuz_uid?>" target="_blank"><?=$discuz_userss?></a>&nbsp;</span> <a href="###" onClick="parent.main.location = 'logging.php?action=logout&formhash=<?=FORMHASH?>&referer=' + parent.main.location">[退出]</a>
<? } else { ?>
<strong>游客:&nbsp;</strong><a href="<?=$regname?>" target="main" onClick="this.href += '?referer='+ escape(parent.main.location)">[<?=$reglinkname?>]</a> <a href="logging.php?action=login" target="main" onClick="this.href += '&referer='+ escape(parent.main.location)">[登录]</a>
<? } ?>
</h2>

<script type="text/javascript">
var tree = new dzTree('tree');
tree.addNode(0, -1, '论坛首页', '<?=$indexname?>', 'main', true);
tree.addNode(99999999, 0, '查看新帖', 'search.php?srchfrom=87000&searchsubmit=yes', 'main', true);<? if(is_array($forumlist)) { foreach($forumlist as $forumdata) { if($forumdata['type'] == 'group') { if($haschild[$forumdata['fid']]) { ?>
tree.addNode(<?=$forumdata['fid']?>, <?=$forumdata['fup']?>, '<?=$forumdata['name']?>', 'index.php?gid=<?=$forumdata['fid']?>', 'main', false);
<? } } else { ?>
tree.addNode(<?=$forumdata['fid']?>, <?=$forumdata['fup']?>, '<?=$forumdata['name']?>', 'forumdisplay.php?fid=<?=$forumdata['fid']?>', 'main', false);
<? } } } ?>tree.show();
</script>

<h3>在线用户: <a href="member.php?action=online" target="main"><strong id="onlinenum"></strong>&nbsp;人</a></h3>
</div>
<script type="text/javascript">
ajaxget('misc.php?action=getonlines&inajax=1', 'onlinenum');
window.setInterval("ajaxget('misc.php?action=getonlines&inajax=1', 'onlinenum', '')", 180000);
</script>
</body></html><? output(); ?>