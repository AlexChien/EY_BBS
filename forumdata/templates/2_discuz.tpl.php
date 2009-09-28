<? if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/discuz.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/header.htm', 1254139600, '2', './templates/colors')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/discuz.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/footer.htm', 1254139600, '2', './templates/colors')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/discuz.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/css_script.htm', 1254139600, '2', './templates/colors')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/discuz.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/./templates/default/jsmenu.htm', 1254139600, '2', './templates/colors')
;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$charset?>" />
<title><?=$navtitle?> <?=$bbname?> <?=$seotitle?> - Powered by Discuz!</title>
<?=$seohead?>
<meta name="keywords" content="<?=$metakeywords?><?=$seokeywords?>" />
<meta name="description" content="<?=$metadescription?> <?=$bbname?> <?=$seodescription?> - Discuz! Board" />
<meta name="generator" content="Discuz! <?=$version?>" />
<meta name="author" content="Discuz! Team and Comsenz UI Team" />
<meta name="copyright" content="2001-2009 Comsenz Inc." />
<meta name="MSSmartTagsPreventParsing" content="True" />
<meta http-equiv="MSThemeCompatible" content="Yes" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<link rel="archives" title="<?=$bbname?>" href="<?=$boardurl?>archiver/" />
<?=$rsshead?>
<?=$extrahead?>
<style type="text/css">
@import url(forumdata/cache/style_<?=STYLEID?>_common.css?<?=VERHASH?>);
<? if(CURSCRIPT == 'viewthread') { ?>
@import url(forumdata/cache/style_<?=STYLEID?>_viewthread.css?<?=VERHASH?>);
<? } if(CURSCRIPT == 'forumdisplay' && $forum['ismoderator']) { include template('css_topicadmin'); } elseif(CURSCRIPT == 'viewthread') { if($forum['ismoderator']) { include template('css_topicadmin'); } ?>
#f_post td { padding-top: 15px; padding-bottom: 20px; vertical-align: top; }
#f_post p, .fastcheck { margin: 5px 0; }
#f_post .txtarea { margin: -1px 0 0; width: 596px; height: 120px; border-color: <?=INPUTBORDERDARKCOLOR?> <?=INPUTBORDER?> <?=INPUTBORDER?> <?=INPUTBORDERDARKCOLOR?>; border-top: none; overflow: auto; }
.defaultpost { height: auto !important; height:<?=$_DCACHE['custominfo']['postminheight']?>px; min-height:<?=$_DCACHE['custominfo']['postminheight']?>px !important; }
.signatures { max-height: <?=$maxsigrows?>px; }
* html .signatures { height: expression(signature(this)); }
.t_msgfontfix { min-height: 100px; }
* html .t_msgfontfix { height: 100px; overflow: visible; }
<? if($thread['special'] == 5 && $stand != '') { ?>
.stand_select .postauthor { background: #EBF2F8; }
.stand_select .itemtitle, .stand_select .solidline { margin: 15px 15px 10px; }
.stand_select h2 { float: left; }
<? } } elseif(CURSCRIPT == 'pm') { ?>
#pm_content h1 { float: left; font-size: 14px; }
.postpm { float: right; color: <?=HIGHLIGHTLINK?>; font-weight: 700; }

.pm_header { padding: 6px; border: solid <?=COMMONBORDER?>; border-width: 1px 0; }
.pm_header #pm_search { float: right; }
.pm_list {  }
.pm_list li { position: relative; *margin-top: -2px; padding: 10px 140px 10px 75px; min-height: 48px; _height: 48px; border-bottom: 1px solid <?=COMMONBORDER?>; }
.pm_list li .avatar { position: absolute; left: 5px; top: 8px; }
.pm_list .self .avatar { left: auto; right: 75px; }
.pm_list p cite, .pm_list p .time  { color: <?=MIDTEXT?>; }
.pm_list p cite { display: block; width: 260px; height: 1.6em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.pm_list p cite.new { padding-left: 25px; background: url(<?=IMGDIR?>/notice_newpm.gif) no-repeat 0 3px; }
.pm_list p cite a { margin-right: 5px; }
.pm_list li .action { position: absolute; right: 10px; top: 50%; margin-top: -18px; display: block; width: 50px; height: 36px; }
.pm_list li .action a { display: block; text-decoration: underline; }
.pm_list li .action a.delete { color: <?=HIGHLIGHTLINK?>; }
.pm_list li .topact { top: 8px; margin-top: 0; }
.sticky { display: block !important; }
.pm_list li.pm_date { padding: 5px 0 5px 75px; min-height: 0; }
#pm_list ul.onerror { padding: 20px 0 0 95px; background-position: 75px 22px; }
#pmlist .summary a { color: <?=HIGHLIGHTLINK?>; }
.pmreply { margin: 1em 0; padding-left: 75px; }
.pmreply textarea { width: 510px; }
.pmback { width: 70px; padding-left: 15px; w\idth: 55px; background: url(<?=IMGDIR?>/arrow_left.gif) no-repeat 0 50%; }
#buddies li { float: left; width: 8em; height: 1.6em; overflow: hidden; white-space: nowrap; }
.newpm_notice { padding: 0 0 10px 75px; border-bottom: 1px solid <?=COMMONBORDER?>; }
.newpm_notice a { color: <?=HIGHLIGHTLINK?>; }
.quote { margin: 10px 0; padding: 10px 10px 10px 65px; }
.quote { padding-bottom: 5px; background: #F9F9F9 url(<?=IMGDIR?>/icon_quote_s.gif) no-repeat 20px 6px; }
.quote blockquote { margin: 0; padding: 0 65px 5px 0; background: url(<?=IMGDIR?>/icon_quote_e.gif) no-repeat 100% 100%; line-height: 1.6em; }

.blockall { margin-bottom: 10px; padding: 10px 0; background: <?=SPECIALBG?>; text-align: center; color: <?=LIGHTTEXT?>; }
.blockall a { color: <?=HIGHLIGHTLINK?>; font-size: 1.17em; }
.blacklist li a, .blacklist li strong { float: left; }
.blacklist .remove { margin: 4px 0 0 5px; width: 12px; height: 12px; background: url(<?=IMGDIR?>/close.gif) no-repeat 100% 50px; line-height: 100px; overflow: hidden; }
.blacklist .hover .remove { background-position: 100% 0; }
.blacklist .remove:hover { background-position: 100% -12px; }
.allblocked { padding-bottom: 10px; width: 100%!important; border-bottom: 1px solid <?=COMMONBORDER?>; }
.allblocked a { margin-left: 10px; color: <?=HIGHLIGHTLINK?>; }
<? } elseif(CURSCRIPT == 'magic') { ?>
.magicprice { color: <?=NOTICETEXT?>; font-weight: 700; font-size: 16px; }
.magic_yes { color: #080; font-weight: 700; }
.magic_no { color: #F00; font-weight: 700; }
.magicmain { height: 180px; overflow-y: auto; }
.magicinfo { margin-bottom: 10px; width: 100%; }
.magicinfo td { padding: 3px 0; }
.magicinfo h5 { font-size: 14px; }
.mymagic { margin-bottom: 30px; }
.mymagic .inlinelist li { float: left; margin-top: 10px; width: 50%; }
.mymagic .magicimg { float:left; margin-right: 10px; width: 25%; }
.mymagic .magicdetail { float:left; width: 65%; }
.mymagic h5 a { text-decoration: underline; color: <?=HIGHLIGHTLINK?>; }
.mymagic p { margin: 3px 0; }
.mymagic .inlinelist .clear { float: none; clear: both; margin: 0; width: 100%; }
.magicnum { margin: 10px 0; }
.magicnum p { margin-top: 5px; }
.fixedbtn { position: absolute; bottom: 15px; }
<? } elseif(CURSCRIPT == 'medal') { ?>
.medallist { overflow: hidden; margin-bottom: 30px; }
.medallist li { margin-bottom: 15px; width: 24.9%; }
.medalimg { float: left; margin: 5px; width: 15%; }
.medalinfo { float: left; width: 75%; }
.medalinfo p { color: <?=MIDTEXT?>; }
.medalexp { margin-left: 8px; color: <?=LIGHTTEXT?>; font-weight: 400; }
.medallist .clear { overflow: hidden; float: none; clear: both; margin: 0; width: 100%; height: 1px; }
.medalp { margin-bottom: 10px; }
<? } elseif(CURSCRIPT == 'invite') { ?>
.invitecode { border: none !important; background-color: transparent; font-family: "Courier New", Courier, monospace, serif; }
input.marked { background: transparent url(<?=IMGDIR?>/check_right.gif) no-repeat 100% 50%; }
.expiration .invitecode { text-decoration: line-through; color: red; }
<? } elseif(CURSCRIPT == 'search') { ?>
.searchform { position: relative; margin: 0 5px 10px; padding: 30px 10px 20px 160px; border-top: 5px solid <?=WRAPBG?>; background: <?=SPECIALBG?>; }
.searchlabel { position: absolute; left: 0; _left: -160px; top: 30px; width: 150px; text-align: center; font-weight: 700; font-size: 26px; color: <?=HIGHLIGHTLINK?>; line-height: 30px; }
.searchlabel strong { display: block; font-size: 14px; color: <?=MIDTEXT?>; font-weight: 400; text-align: center; }
.searchform h3 { margin: 10px 2px; font-size: 14px; }
.searchkey { margin-bottom: 1em; zoom: 1; }
.searchkey #searchsubmit { margin: 0 16px 0 10px; }
* html #searchsubmit { height: 21px; line-height: 19px; }
*+html #searchsubmit { height: 21px; line-height: 19px; }
.searchkey a { background: url(images/common/editor.gif) 0 -181px no-repeat; padding:2px 0 2px 22px; color: <?=HIGHLIGHTLINK?>; }

.searchlist { margin: 5px 20px; }
.searchlist h1 { font-size: 14px; }
.searchlist h1 em { font-weight: 400; }
#srchfid optgroup, #moveto optgroup { font-style: normal; font-weight: 400; font-size: 12px; }
<? } elseif(CURSCRIPT == 'faq' || CURSCRIPT == 'announcement') { ?>
.faqmessage, .announcemsg { margin-bottom: 10px; font-size: 14px; padding-bottom: 20px; border-bottom: 1px solid <?=COMMONBORDER?>; }
.faqmessage ul, .announcemsg ul { padding-left: 1.6em; }
.faq li { margin-left: 2em; }
.faq ul li { list-style-type: disc; }
.t_msgfont ul.o li, .faq ol li { list-style-type: decimal; }
.author { margin-bottom: 5px; font-size: 12px; color: <?=LIGHTTEXT?>; }
<? } elseif(CURSCRIPT == 'memcp' || CURSCRIPT == 'credits') { if(CURSCRIPT == 'memcp') { ?>
.avatararea { float: left; margin-right: 20px; width: 120px; }
.avatararea a { display:block; margin: 15px 0; width: 119px; height: 31px; line-height: 31px; text-align: center; background: url(<?=IMGDIR?>/bigbtn.gif) no-repeat; font-size: 14px; }
.avatararea a:hover { text-decoration: none; }
#avatarctrl { float: left; }
<? } ?>
.creditstable {}
.creditstable td { padding: 10px 5px !important; }
.cre_title { width: 50px; }
.cre_opt { width: 150px; }
.cre_arrow { width: 30px; }
.cre_btn { margin-top: 10px; }
<? } elseif(CURSCRIPT == 'my' && $item == 'buddylist') { ?>
.friendavatar { float: left; margin: 10px 10px 10px 0; }
.friendinfo { overflow: hidden; margin: 10px 10px 10px 0; }
* html .friendinfo { margin-left: 70px; }
.friendinfo p { color: <?=LIGHTTEXT?> }
.friendinfo a { text-decoration: none !important; }
.friendinfo a:hover { text-decoration: underline !important; }
.friendinfo h5 a { font-size: 14px; color: <?=HIGHLIGHTLINK?>; }
.friendctrl { margin-top :5px; }
.friendctrl a { color: <?=TABELTEXT?>; }
.buddyname { margin-bottom: 8px; line-height: 16px; }
.online_buddy { margin-left: 8px; vertical-align: middle; }
<? } elseif(CURSCRIPT == 'modcp') { if($forum['ismoderator']) { include template('css_topicadmin'); } ?>
.notelist { margin-top: -1px; border-top: 1px solid <?=COMMONBORDER?>; border-bottom: 1px solid <?=COMMONBORDER?>; zoom: 1; }
.notelist .c_header, .notelistsubmit { margin: 10px; }
.notelistbg, .notelistbg .c_header h3, .notelistbg .closenode .c_header_ctrlbtn { background-color: <?=INTERLEAVECOLOR?>; }
.notelistmsg { overflow: hidden; margin: 0 10px 10px 33px; }
.notelistmsg .submit { margin-top: 10px; }
.notelistmsg h3 { margin: 30px 0 5px; color: <?=HIGHLIGHTLINK?>; }
.datalist .announcetable { width: 600px; }
.datalist .announcetable, .datalist .announcetable th, .datalist .announcetable td { padding: 3px 0; border: none; }
.datalist .announcetable th { font-weight: 700; }
.anno_subject .txt { width: 270px; *width: 220px; }
.anno_type select { width: 80px; }
.anno_time .txt { width: 80px; }
.anno_msg .txtarea, .anno_msg .txt { width: 590px; }
.anno_msg .txtarea { margin-top: -1px; border-top-color: <?=INPUTBORDER?>; }
.schresult .formtable th { padding: 5px 10px 5px 20px; width: 60px; }
.schresult .formtable td a{ color: <?=HIGHLIGHTLINK?>; font-weight: 700; }
<? } elseif(CURSCRIPT == 'stats' || CURSCRIPT == 'member') { ?>
.hasscrolltable { overflow: hidden; zoom: 1; * padding-bottom: 9px; }
.datalist .scrollfixedtable { float: left; margin-top: 1px; margin-left: 1px; width: 20%; }
.scrolltable { float:left; width: 79%; overflow-x: scroll; }
.scrolltable table { table-layout: fixed; margin: 1px 0 0; width: 1600px; }
.datalist .fixedtable { margin-bottom: 10px; width: 99%; text-align: right; }
.datalist .fixedtable a { text-decoration: underline; }
.datalist table a { text-decoration: underline; }
.datalist table a:hover { color: <?=HIGHLIGHTLINK?>; }
<? } elseif(CURSCRIPT == 'profile') { ?>
#profile .itemtitle h1 { color: <?=HIGHLIGHTLINK?>; }
#profile p { margin: 5px 0; }
.profile_side .avatar { margin: 25px 0; text-align: center; }
.profile_side ul { margin: 5px 30px; line-height: 1.6em; overflow: hidden; }
.profile_side li { margin: 5px 0; background-position: 0 50%; background-repeat: no-repeat; text-indent: 22px; }
.profile_side li.pm { background-image: url(<?=IMGDIR?>/pmto.gif); }
.profile_side li.buddy { background-image: url(<?=IMGDIR?>/addbuddy.gif); }
.profile_side li.space { background-image: url(<?=IMGDIR?>/home.gif); }
.profile_side li.searchpost { background-image: url(<?=IMGDIR?>/fastreply.gif); }
.profile_side li.magic { background-image: url(<?=IMGDIR?>/magic.gif);}
#profile_stats li { text-indent: 0; }
#profile_stats li img { float: left; margin-right: 6px; }
#uch_feed { float: right; width: 50%; }
#uch_feed li { width: 100%; height: 1.6em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#uch_feed img { margin-bottom: -4px; }
#uch_feed a { color: <?=HIGHLIGHTLINK?>; }
<? } elseif(CURSCRIPT == 'post') { ?>
* html .content { height: auto !important; }
.nojs { position: relative; left: auto; }
* html .nojs { left: 0; }
* html .nojs .sim_upfile .sitenote { bottom: 161px; }
* html .nojs .sim_upfile .upfile_special { bottom: 301px; }
* html .nojs .sim_upfile .upfile { bottom: 356px; }
.nojs .float { margin: 0 auto; zoom: 1; }
.nojs .moreconf { position: relative; left: 0; width: auto; }
#floatwinnojs { position: static; }
.nojs .floatboxswf { width: 600px !important; }
.nojs .floatboxswf div { margin: 0 0 10px 11px; }
.nojs .floatbox1 { width: 100%; height: auto; }
#custominfoarea { right: 10px; left: auto; }
.nojs .specialinfo, .nojs .upfilelist { overflow-y: visible; height: auto !important; }
.popupmenu_popup, #e_popup_smilies_menu { *margin-top: 22px; }
* html .popupmenu_popup li { clear: both; float: left; }
* html .fontstyle_menu { overflow: hidden; width: 35px; }
* html .fontstyle_menu ul { width: 70px; }
* html .fontname_menu li { float: none; }
<? } if(CURSCRIPT == 'register') { ?>
* html .content { overflow-y: visible !important; }
.nojs { position: relative; left: auto; }
* html .nojs { left: 20%; }
.nojs .float { margin: 0 auto; zoom: 1; }
.nojs .moreconf { position: relative; right: -15px; left: auto; width: auto; }
* html .nojs .moreconf { position: absolute; left: 0; bottom: 55px; }
*+html .nojs .moreconf { position: absolute; left: 165px; }
#floatwinnojs { position: static; }
.nojs .loginform, .nojs .regform, .nojs .floatbox, .nojs .floatbox1 { height: auto; }
.nojs .gateform { padding-bottom: 50px; }
<? if($secqaacheck) { ?>
.regform #secanswer_menu { left: 260px; top: 176px; }
* html .regform #secanswer_menu { left: 113px; }
.regform #seccodeverify_menu { left: 340px; }
* html .regform #seccodeverify_menu { left: 193px;}
<? } else { ?>
.regform #seccodeverify_menu { left: 260px; }
* html .regform #seccodeverify_menu { left: 113px; }
<? } } ?>
</style><script type="text/javascript">var STYLEID = '<?=STYLEID?>', IMGDIR = '<?=IMGDIR?>', VERHASH = '<?=VERHASH?>', charset = '<?=$charset?>', discuz_uid = <?=$discuz_uid?>, cookiedomain = '<?=$cookiedomain?>', cookiepath = '<?=$cookiepath?>', attackevasive = '<?=$attackevasive?>', allowfloatwin = '<?=$allowfloatwin?>', creditnotice = '<? if($creditnotice) { ?><?=$creditnames?><? } ?>', <? if(in_array(CURSCRIPT, array('viewthread', 'forumdisplay'))) { ?>gid = parseInt('<?=$thisgid?>')<? } elseif(CURSCRIPT == 'index') { ?>gid = parseInt('<?=$gid?>')<? } else { ?>gid = 0<? } ?>, fid = parseInt('<?=$fid?>'), tid = parseInt('<?=$tid?>')</script>
<script src="include/js/common.js?<?=VERHASH?>" type="text/javascript"></script>
</head>

<body id="<?=CURSCRIPT?>" onkeydown="if(event.keyCode==27) return false;">

<div id="append_parent"></div><div id="ajaxwaitid"></div>
<div id="header">
<div class="wrap s_clear">
<h2><a href="<?=$indexname?>" title="<?=$bbname?>"><?=BOARDLOGO?></a></h2>
<div id="umenu">
<? if($discuz_uid) { ?>
<cite><a href="space.php?uid=<?=$discuz_uid?>" class="noborder"><?=$discuz_userss?></a><? if($allowinvisible) { ?><span id="loginstatus"><? if(!empty($invisible)) { ?><a href="member.php?action=switchstatus" onclick="ajaxget(this.href, 'loginstatus');doane(event);">隐身</a><? } else { ?><a href="member.php?action=switchstatus" title="我要隐身" onclick="ajaxget(this.href, 'loginstatus');doane(event);">在线</a><? } ?></span><? } ?></cite>
<span class="pipe">|</span>
<a href="my.php?item=threads<? if($forum) { ?>&amp;srchfid=<?=$forum['fid']?><? } ?>">我的帖子</a>
<? if($ucappopen['UCHOME']) { ?>
<a href="<?=$uchomeurl?>/space.php?uid=<?=$discuz_uid?>" target="_blank">空间</a>
<? } elseif($ucappopen['XSPACE']) { ?>
<a href="<?=$xspaceurl?>/?uid-<?=$discuz_uid?>" target="_blank">空间</a>
<? } ?>
<a href="pm.php" id="pm_ntc"<? if($newpm && $_DCOOKIE['pmnum']) { ?> onmouseover="pmviewnew()" class="new" title="您有新短消息"<? } ?> target="_blank">短消息<? if($newpm && $_DCOOKIE['pmnum']) { ?><span>(<?=$_DCOOKIE['pmnum']?>)</span><? } ?></a>
<? if($taskon) { ?>
<a id="task_ntc" <? if($doingtask) { ?>href="task.php?item=doing" class="new" title="您有未完成的任务"<? } else { ?>href="task.php"<? } ?> target="_blank">论坛任务</a>
<? } ?>
<span class="pipe">|</span>
<a href="memcp.php">个人中心</a>
<? if($discuz_uid && $adminid > 1) { ?><a href="modcp.php?fid=<?=$fid?>" target="_blank">版主管理</a><? } if($discuz_uid && $adminid == 1) { ?><a href="admincp.php" target="_blank">系统设置</a><? } ?>
<a href="logging.php?action=logout&amp;formhash=<?=FORMHASH?>">退出</a>
<? } elseif(!empty($_DCOOKIE['loginuser'])) { ?>
<cite><a id="loginuser" class="noborder"><?=$_DCOOKIE['loginuser']?></a></cite>
<a href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;">激活</a>
<a href="logging.php?action=logout&amp;formhash=<?=FORMHASH?>">退出</a>
<? } else { ?>
                <!-- <a href="<?=$regname?>" onclick="floatwin('open_register', this.href, 600, 400, '600,0');return false;" class="noborder"><?=$reglinkname?></a> -->
<a href="http://openid.enjoyoung.cn/account/new" class="noborder"><?=$reglinkname?></a>
<a href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;">登录</a>
<? } ?>
</div>
<div id="ad_headerbanner"><? if($admode && empty($insenz['hardadstatus']) && !empty($advlist['headerbanner'])) { ?><?=$advlist['headerbanner']?><? } ?></div>
<div id="menu">
<ul>
<? if($_DCACHE['settings']['frameon'] > 0) { ?>
<li>
<span class="frameswitch">
<script type="text/javascript">
if(top == self) {
<? if(($_DCACHE['settings']['frameon'] == 2 && !defined('CACHE_FILE') && in_array(CURSCRIPT, array('index', 'forumdisplay', 'viewthread')) && (($_DCOOKIE['frameon'] == 'yes' && $_GET['frameon'] != 'no') || (empty($_DCOOKIE['frameon']) && empty($_GET['frameon']))))) { ?>
top.location = 'frame.php?frameon=yes&referer='+escape(self.location);
<? } ?>
document.write('<a href="frame.php?frameon=yes" target="_top" class="frameon">分栏模式<\/a>');
} else {
document.write('<a href="frame.php?frameon=no" target="_top" class="frameoff">平板模式<\/a>');
}
</script>
</span>
</li>
<? } if(is_array($navs)) { foreach($navs as $id => $nav) { if($id == 3) { if(!empty($plugins['jsmenu'])) { ?>
<?=$nav['nav']?>
<? } if(!empty($plugins['links'])) { if(is_array($plugins['links'])) { foreach($plugins['links'] as $module) { if(!$module['adminid'] || ($module['adminid'] && $adminid > 0 && $module['adminid'] >= $adminid)) { ?><li><?=$module['url']?></li><? } } } } } else { if(!$nav['level'] || ($nav['level'] == 1 && $discuz_uid) || ($nav['level'] == 2 && $adminid > 0) || ($nav['level'] == 3 && $adminid == 1)) { ?><?=$nav['nav']?><? } } } } if(in_array($BASEFILENAME, $navmns)) { $mnid = $BASEFILENAME; } elseif($navmngs[$BASEFILENAME]) { if(is_array($navmngs[$BASEFILENAME])) { foreach($navmngs[$BASEFILENAME] as $navmng) { if($navmng['0'] == array_intersect_assoc($navmng['0'], $_GET)) { $mnid = $navmng['1']; } } } } ?>
</ul>
<script type="text/javascript">
var currentMenu = $('mn_<?=$mnid?>') ? $('mn_<?=$mnid?>') : $('mn_<?=$navmns['0']?>');
currentMenu.parentNode.className = 'current';
</script>				
</div>
<? if(!empty($stylejumpstatus)) { ?>
<script type="text/javascript">
function setstyle(styleid) {
str = unescape('<? echo str_replace("'", "\\'", urlencode($_SERVER['QUERY_STRING'])); ?>');
str = str.replace(/(^|&)styleid=\d+/ig, '');
str = (str != '' ? str + '&' : '') + 'styleid=' + styleid
location.href = '<?=$BASESCRIPT?>?' + str;
}
</script>
<ul id="style_switch"><? if(is_array($styles)) { foreach($styles as $id => $stylename) { ?><li<? if($id == STYLEID) { ?> class="current"<? } ?>><a href="###" onclick="setstyle(<?=$id?>)" title="<?=$stylename?>" style="background: <?=$styleicons[$id]?>;"><?=$stylename?></a></li><? } } ?></ul>
<? } ?>
</div>
</div>
<div id="nav"><a href="<?=$indexname?>"><?=$bbname?></a> &raquo; 首页</div>
<? if($admode && empty($insenz['hardadstatus']) && !empty($advlist['text'])) { ?><div class="ad_text" id="ad_text"><table summary="Text Ad" cellpadding="0" cellspacing="1"><?=$advlist['text']?></table></div><? } else { ?><div id="ad_text"></div><? } ?>
<div id="wrap"<? if($infosidestatus['allow'] < 2) { ?> class="wrap s_clear"<? } else { ?> class="wrap with_side s_clear"<? } ?>>
<? if($infosidestatus['allow'] == 2) { ?>
<a id="sidebar_img" href="javascript:;" onclick="sidebar_collapse(['打开边栏', '关闭边栏']);" class="<?=$collapseimg['sidebar']?>"><? if($collapseimg['sidebar'] == 'collapsed_yes') { ?>打开边栏<? } else { ?>关闭边栏<? } ?></a>
<? } elseif($infosidestatus['allow'] == 1) { ?>
<a id="sidebar_img" href="javascript:;" onclick="sidebar_collapse(['', '关闭边栏']);" class="collapsed_yes">打开边栏</a>
<? } ?>
<div class="main"><div class="content">

<div class="pages_btns s_clear">
<span class="postbtn"><a href="misc.php?action=nav" onclick="floatwin('open_nav', this.href, 600, 410);return false;">发帖</a></span>
<? if(!$discuz_uid) { ?>
<p>你可以<a href="http://openid.enjoyoung.cn/account/new"  class="lightlink">注册</a>一个帐号，并以此<a href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;" class="lightlink">登录</a>，以浏览更多精彩内容，并随时发布观点，与大家交流。</p>
<? } else { ?>
欢迎回来 <?=$discuz_userss?>, <? if($lastvisit > 0) { ?>你上次访问时间是在 <?=$lastvisit?>, <? } ?><a href="search.php?srchfrom=<?=$newthreads?>&amp;searchsubmit=yes" class="lightlink">查看新帖</a>, <a href="member.php?action=markread" id="ajax_markread" onclick="ajaxmenu(event, this.id)" class="lightlink">标记已读</a>
<? } ?>
</div>

<div class="foruminfo s_clear">
<p class="right forumcount">
今日: <em><?=$todayposts?></em>, 昨日: <em><?=$postdata['0']?></em>, 会员: <em><?=$totalmembers?></em>
</p>

<? if(empty($gid) && $announcements) { ?>
<div id="ann" onmouseover="annstop = 1" onmouseout="annstop = 0">
<dl>
<dt>公告:</dt>
<dd>
<div id="annbody"><ul id="annbodylis"><?=$announcements?></ul></div>
</dd>
</dl>
</div>
<script type="text/javascript">
var anndelay = 3000;
var annst = 0;
var annstop = 0;
var annrowcount = 0;
var anncount = 0;
var annlis = $('annbody').getElementsByTagName("LI");
var annrows = new Array();
var annstatus;

function announcementScroll() {
if(annstop) {
annst = setTimeout('announcementScroll()', anndelay);
return;
}
if(!annst) {
var lasttop = -1;
for(i = 0;i < annlis.length;i++) {

if(lasttop != annlis[i].offsetTop) {
if(lasttop == -1) {
lasttop = 0;
}
annrows[annrowcount] = annlis[i].offsetTop - lasttop;
annrowcount++;
}
lasttop = annlis[i].offsetTop;
}

if(annrows.length == 1) {
$('ann').onmouseover = $('ann').onmouseout = null;
} else {
annrows[annrowcount] = annrows[1];
$('annbodylis').innerHTML += $('annbodylis').innerHTML;
annst = setTimeout('announcementScroll()', anndelay);
}
annrowcount = 1;
return;
}

if(annrowcount >= annrows.length) {
$('annbody').scrollTop = 0;
annrowcount = 1;
annst = setTimeout('announcementScroll()', anndelay);
} else {
anncount = 0;
announcementScrollnext(annrows[annrowcount]);
}
}

function announcementScrollnext(time) {
$('annbody').scrollTop++;
anncount++;
if(anncount != time) {
annst = setTimeout('announcementScrollnext(' + time + ')', 10);
} else {
annrowcount++;
annst = setTimeout('announcementScroll()', anndelay);
}
}
</script>
<? } ?>
</div>

<style type="text/css" media="screen">
    .forum_block {
        width:24%; float:left;; margin-right;20px; margin-bottom:10px; padding-bottom:5px; height:130px; overflow:hidden;
    }
    .forums_container {
        padding:10px; width:100%; overflow:hidden;
    }
    .forumnums{ width:auto; text-align:left; }
    .forumlast{ width:auto; }
    .forum_block div.icon {
        padding-left:45px !important;
        -moz-background-clip:border;
        -moz-background-inline-policy:continuous;
        -moz-background-origin:padding;
        background:transparent url(../../images/default/forum.gif) no-repeat scroll 5px 10px;
    }
    .forum_block div.new {
        padding-left:45px !important;
        -moz-background-clip:border;
        -moz-background-inline-policy:continuous;
        -moz-background-origin:padding;
        background:transparent url(../../images/default/forum_new.gif)  no-repeat scroll 5px 10px !important;
    }
</style>

<? if(!empty($insenz['vfstatus']) && $insenz['vfpos'] == 'first') { ?><script src="campaign.php?action=list" type="text/javascript"></script><? } $rkey=array_rand($catlist); if(is_array($catlist)) { foreach($catlist as $key => $cat) { if($cat['forumscount']) { ?>
<div class="mainbox list">
<span class="headactions">
<? if($cat['moderators']) { ?>分区版主: <?=$cat['moderators']?><? } ?>
<img id="category_<?=$cat['fid']?>_img" src="<?=IMGDIR?>/<?=$cat['collapseimg']?>" title="收起/展开" alt="收起/展开" onclick="toggle_collapse('category_<?=$cat['fid']?>');" />
</span>
<h3><a href="<?=$indexname?>?gid=<?=$cat['fid']?>"><?=$cat['name']?></a></h3>
<div id="category_<?=$cat['fid']?>" class="forums_container" summary="category<?=$cat['fid']?>" style="<?=$collapse['category_'.$cat['fid']]?>">
<? if(!$cat['forumcolumns']) { if(is_array($cat['forums'])) { foreach($cat['forums'] as $forumid) { $forum=$forumlist[$forumid]; ?><div id="forum<?=$forum['fid']?>" class="forum_block">
<div<?=$forum['folder']?> class="icon">
<?=$forum['icon']?>
<h2><a href="forumdisplay.php?fid=<?=$forum['fid']?>" <? if($forum['redirect']) { ?>target="_blank"<? } ?>><?=$forum['name']?></a><? if($forum['todayposts'] && !$forum['redirect']) { ?><em> (今日: <strong><?=$forum['todayposts']?></strong>)</em><? } ?></h2>
<? if($forum['description']) { ?><p><?=$forum['description']?></p><? } if($forum['subforums']) { ?><p>子版块：<?=$forum['subforums']?></p><? } if($forum['moderators']) { if($moddisplay == 'flat') { ?><p>版主：<?=$forum['moderators']?></p><? } else { ?><span class="dropmenu" id="mod<?=$forum['fid']?>" onmouseover="showMenu(this.id)">版主</span><ul class="moderators popupmenu_popup" id="mod<?=$forum['fid']?>_menu" style="display: none"><?=$forum['moderators']?></ul><? } } ?>

                                <div class="forumnums">
                                    <? if($forum['redirect']) { ?>N/A<? } else { ?><em>主题：<?=$forum['threads']?></em> / 帖数：<?=$forum['posts']?><? } ?>
                                </div>
                                <div class="forumlast">
                                    <? if($forum['permission'] == 1) { ?>
                                    私密版块
                                    <? } else { ?>
                                    <? if($forum['redirect']) { ?>
                                    <a href="forumdisplay.php?fid=<?=$forum['fid']?>">链接到外部地址</a>
                                    <? } elseif(is_array($forum['lastpost'])) { ?>
                                    <p><a href="redirect.php?tid=<?=$forum['lastpost']['tid']?>&amp;goto=lastpost#lastpost"><? echo cutstr($forum['lastpost']['subject'], 30); ?></a></p>
                                    <cite><? if($forum['lastpost']['author']) { ?>最后发帖：<?=$forum['lastpost']['author']?><? } else { ?>匿名<? } ?> - <?=$forum['lastpost']['dateline']?></cite>
                                    <? } else { ?>
                                    从未
                                    <? } ?>
                                    <? } ?>
                                </div>
</div>


</div><? } } } else { ?>
<div class="narrowlist"><? if(is_array($cat['forums'])) { foreach($cat['forums'] as $forumid) { $forum=$forumlist[$forumid]; if($forum['orderid'] && ($forum['orderid'] % $cat['forumcolumns'] == 0)) { ?>
</div></div>
<? if($forum['orderid'] < $cat['forumscount']) { ?>
<div class="forum_block"><div>
<? } } ?>
<div style="<?=$cat['forumcolwidth']?>" <?=$forum['folder']?>>
<h2><a href="forumdisplay.php?fid=<?=$forum['fid']?>" <? if($forum['redirect']) { ?>target="_blank"<? } ?>><?=$forum['name']?></a><? if($forum['todayposts']) { ?><em> (今日: <strong><?=$forum['todayposts']?></strong>)</em><? } ?></h2>
<? if(!$forum['redirect']) { ?>
<p>主题: <?=$forum['threads']?>, 帖数: <?=$forum['posts']?></p>
<? if($forum['permission'] == 1) { ?>
<p>私密版块
<? } else { ?>
<p>最后发表:
<? if(is_array($forum['lastpost'])) { ?>
<a href="redirect.php?tid=<?=$forum['lastpost']['tid']?>&amp;goto=lastpost#lastpost" title="<? echo cutstr($forum['lastpost']['subject'], 30); ?> by <? if($forum['lastpost']['author']) { ?><?=$forum['lastpost']['authorusername']?><? } else { ?>匿名<? } ?>  "><?=$forum['lastpost']['dateline']?></a>
<? } else { ?>
从未
<? } ?>
</p>
<? } } else { ?>
<p>链接到外部地址</p>
<? } ?>
</div><? } } ?><?=$cat['endrows']?>
<? } ?>
</div>
</div>
<? if(!empty($insenz['vfstatus']) && $insenz['vfpos'] == 'rand' && $key == $rkey) { ?><script src="campaign.php?action=list" type="text/javascript"></script><? } if($admode && empty($insenz['hardadstatus']) && !empty($advlist['intercat']) && ($advlist['intercat'][$key] = array_merge(($advlist['intercat']['0'] ? $advlist['intercat']['0'] : array()), ($advlist['intercat'][$key] ? $advlist['intercat'][$key] : array())))) { ?><div class="ad_column" id="ad_intercat_<?=$key?>"><? echo $advitems[$advlist['intercat'][$key][array_rand($advlist['intercat'][$key])]]; ?></div><? } else { ?><div id="ad_intercat_<?=$key?>"></div><? } } } } if(!empty($insenz['vfstatus']) && $insenz['vfpos'] == 'last') { ?><script src="campaign.php?action=list" type="text/javascript"></script><? } if($_DCACHE['forumlinks']['0'] || $_DCACHE['forumlinks']['1'] || $_DCACHE['forumlinks']['2']) { ?>
<div class="mainbox list">
<span class="headactions"><img id="forumlinks_img" src="<?=IMGDIR?>/<?=$collapseimg['forumlinks']?>.gif" alt="" onclick="toggle_collapse('forumlinks');" /></span>
<h3>友情链接</h3>
<div id="forumlinks" style="<?=$collapse['forumlinks']?>">
<? if($_DCACHE['forumlinks']['0']) { ?>
<div class="forumlinks">
<ul class="s_clear"><?=$_DCACHE['forumlinks']['0']?></ul>
</div>
<? } if($_DCACHE['forumlinks']['1']) { ?>
<div class="forumimglink">
<?=$_DCACHE['forumlinks']['1']?>
</div>
<? } if($_DCACHE['forumlinks']['2']) { ?>
<div class="forumtxtlink">
<ul class="s_clear">
<?=$_DCACHE['forumlinks']['2']?>
</ul>
</div>
<? } ?>
</div>
</div>
<? } if(empty($gid) && $maxbdays &&$_DCACHE['birthdays_index']['todaysbdays']) { ?>
<div class="mainbox list" id="bdays">
<h3 id="bdayslist">
<a href="member.php?action=list&amp;type=birthdays">生日快乐</a>: <?=$_DCACHE['birthdays_index']['todaysbdays']?>
</h3>
</div>
<? } if(empty($gid) && $whosonlinestatus) { ?>
<div class="mainbox list" id="online">
<? if($whosonlinestatus) { if($detailstatus) { ?>
<span class="headactions"><a href="<?=$indexname?>?showoldetails=no#online" title="关闭"><img src="<?=IMGDIR?>/collapsed_no.gif" alt="关闭" /></a></span>
<h3>
<strong><a href="member.php?action=online">在线会员</a></strong>
- <em><?=$onlinenum?></em> 人在线
- <em><?=$membercount?></em> 会员(<em><?=$invisiblecount?></em> 隐身),
<em><?=$guestcount?></em> 位游客
- 最高记录是 <em><?=$onlineinfo['0']?></em> 于 <em><?=$onlineinfo['1']?></em>.
</h3>
<? } else { ?>
<span class="headactions"><a href="<?=$indexname?>?showoldetails=yes#online" class="nobdr"><img src="<?=IMGDIR?>/collapsed_yes.gif" alt="" /></a></span>
<h3>
<strong><a href="member.php?action=online">在线会员</a></strong>
- 总计 <em><?=$onlinenum?></em> 人在线
- 最高记录是 <em><?=$onlineinfo['0']?></em> 于 <em><?=$onlineinfo['1']?></em>.
</h3>
<? } } else { ?>
<h4><strong><a href="member.php?action=online">在线会员</a></strong></h4>
<? } if($whosonlinestatus && $detailstatus) { ?>
<dl id="onlinelist">
<dt><?=$_DCACHE['onlinelist']['legend']?></dt>
<? if($detailstatus) { ?>
<dd>
<ul class="s_clear">
<? if($whosonline) { if(is_array($whosonline)) { foreach($whosonline as $key => $online) { ?><li title="时间: <?=$online['lastactivity']?><?="\n"?>操作: <?=$online['action']?> <? if($online['fid']) { ?><?="\n"?>版块: <?=$online['fid']?><? } ?>">
<img src="images/common/<?=$online['icon']?>" alt="" />
<? if($online['uid']) { ?>
<a href="space.php?uid=<?=$online['uid']?>"><?=$online['username']?></a>
<? } else { ?>
<?=$online['username']?>
<? } ?>
</li><? } } } else { ?>
<li style="width: auto">当前只有游客或隐身会员在线</li>
<? } ?>
</ul>
</dd>
<? } ?>
</dl>
<? } ?>
</div>
<? } if(empty($gid) && $announcements) { ?>
<script type="text/javascript">announcementScroll();</script>
<? } ?>

</div></div>

<? if($infosidestatus['allow'] == 2) { ?>
<div id="sidebar" class="side" style="<?=$collapse['sidebar']?>">
<? if(!empty($qihoo['status']) && ($qihoo['searchbox'] & 1)) { ?>
<div id="qihoosearch" class="sidebox">
<? if(!empty($qihoo['status']) && ($qihoo['searchbox'] & 1)) { ?>
<form method="post" action="search.php?srchtype=qihoo" onSubmit="this.target='_blank';">
<input type="hidden" name="searchsubmit" value="yes" />
<input type="text" class="txt" name="srchtxt" value="<?=$qihoo_searchboxtxt?>" size="20" />
<select name="stype">
<option value="" selected="selected">全文</option>
<option value="1">标题</option>
<option value="2">作者</option>
</select>
&nbsp;<button name="searchsubmit" type="submit" value="true">搜索</button>
</form>

<? if(!empty($qihoo['links']['keywords'])) { ?>
<strong>热门搜索</strong><? if(is_array($qihoo['links']['keywords'])) { foreach($qihoo['links']['keywords'] as $link) { ?><?=$link?>&nbsp;<? } } } if($customtopics) { ?>
<strong>用户专题</strong>&nbsp;&nbsp;<?=$customtopics?> [<a href="javascript:;" onclick="floatwin('open_customtopics', 'misc.php?action=customtopics', 600, 410)">编辑</a>]<br />
<? } if(!empty($qihoo['links']['topics'])) { ?>
<strong>论坛专题</strong>&nbsp;<? if(is_array($qihoo['links']['topics'])) { foreach($qihoo['links']['topics'] as $url) { ?><?=$url?> &nbsp;<? } } } } ?>
</div>
<? } if($infosidestatus['2']) { if(!empty($qihoo['status']) && ($qihoo['searchbox'] & 1)) { ?>
<hr class="shadowline"/>
<? } ?>
<div id="infoside">
<? if(empty($gid)) { request($infosidestatus, 0, 2); } else { request($infosidestatus, 1, 2); } ?>
</div>
<? } ?>
</div>
<? } ?>
</div><? if(!empty($plugins['jsmenu'])) { ?>
<ul class="popupmenu_popup headermenu_popup" id="plugin_menu" style="display: none"><? if(is_array($plugins['jsmenu'])) { foreach($plugins['jsmenu'] as $module) { ?>     <? if(!$module['adminid'] || ($module['adminid'] && $adminid > 0 && $module['adminid'] >= $adminid)) { ?>
     <li><?=$module['url']?></li>
     <? } } } ?></ul>
<? } if(is_array($subnavs)) { foreach($subnavs as $subnav) { ?><?=$subnav?><? } } if($admode && empty($insenz['hardadstatus']) && !empty($advlist)) { ?>
<div class="ad_footerbanner" id="ad_footerbanner1"><?=$advlist['footerbanner1']?></div><? if($advlist['footerbanner2']) { ?><div class="ad_footerbanner" id="ad_footerbanner2"><?=$advlist['footerbanner2']?></div><? } if($advlist['footerbanner3']) { ?><div class="ad_footerbanner" id="ad_footerbanner3"><?=$advlist['footerbanner3']?></div><? } } else { ?>
<div id="ad_footerbanner1"></div><div id="ad_footerbanner2"></div><div id="ad_footerbanner3"></div>
<? } ?>

<div id="footer">
<div class="wrap s_clear">
<div id="footlink">
<p>
<strong><a href="<?=$siteurl?>" target="_blank"><?=$sitename?></a></strong>
<? if($icp) { ?>( <a href="http://www.miibeian.gov.cn/" target="_blank"><?=$icp?></a>)<? } ?>
<span class="pipe">|</span><a href="mailto:<?=$adminemail?>">联系我们</a>
<? if($allowviewstats) { ?><span class="pipe">|</span><a href="stats.php">论坛统计</a><? } if($archiverstatus) { ?><span class="pipe">|</span><a href="archiver/" target="_blank">Archiver</a><? } if($wapstatus) { ?><span class="pipe">|</span><a href="wap/" target="_blank">WAP</a><? } if($statcode) { ?><span class="pipe">| <?=$statcode?></span><? } ?>
</p>
<p class="smalltext">
GMT<?=$timenow['offset']?>, <?=$timenow['time']?>
<? if(debuginfo()) { ?>, <span id="debuginfo">Processed in <?=$debuginfo['time']?> second(s), <?=$debuginfo['queries']?> queries<? if($gzipcompress) { ?>, Gzip enabled<? } ?></span><? } ?>.
</p>
</div>
<div id="rightinfo">
<p>Powered by <strong><a href="http://www.discuz.net" target="_blank">Discuz!</a></strong> <em><?=$version?></em><? if(!empty($boardlicensed)) { ?> <a href="http://license.comsenz.com/?pid=1&amp;host=<?=$_SERVER['HTTP_HOST']?>" target="_blank">Licensed</a><? } ?></p>
<p class="smalltext">&copy; 2001-2009 <a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a></p>
</div><? updatesession(); ?></div>
</div>
<? if($_DCACHE['settings']['frameon'] && in_array(CURSCRIPT, array('index', 'forumdisplay', 'viewthread')) && $_DCOOKIE['frameon'] == 'yes') { ?>
<script src="include/js/iframe.js?<?=VERHASH?>" type="text/javascript"></script>
<? } output(); if($_DCACHE['settings']['uchomeurl'] && $discuz_uid) { ?>
<script src="<?=$uchomeurl?>/api/discuz.php?pagetype=<?=CURSCRIPT?>&status=<?=$_DCACHE['settings']['homeshow']?>&uid=<?=$discuz_uid?>&infosidestatus=<?=$infosidestatus['allow']?><? if(CURSCRIPT == 'viewthread') { ?>&feedpostnum=<?=$feedpostnum?><? if($page == '1') { ?>&updateuid=<?=$feeduid?>&pid=<?=$firstpid?><? } } elseif(CURSCRIPT == 'profile') { ?>&updateuid=<?=$profileuid?><? } ?>" type="text/javascript"></script>
<? } ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9488330-1");
pageTracker._trackPageview();
} catch(err) {}
</script>

</body>
</html>