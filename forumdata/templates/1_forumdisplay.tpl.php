<? if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/forumdisplay.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/header.htm', 1251359951, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/forumdisplay.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/footer.htm', 1251359951, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/forumdisplay.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/css_script.htm', 1251359951, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/forumdisplay.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/jsmenu.htm', 1251359951, '1', './templates/default')
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
<div id="nav"><a id="fjump" href="<?=$indexname?>"<? if($forumjump == 1) { ?> class="dropmenu" onmouseover="showMenu(this.id)"<? } ?>><?=$bbname?></a> <?=$navigation?></div>

<? if($admode && empty($insenz['hardadstatus']) && !empty($advlist['text'])) { ?>
<div id="ad_text" class="ad_text" >
<table summary="Text Ad" cellpadding="0" cellspacing="0"><?=$advlist['text']?></table>
</div>
<? } else { ?>
<div id="ad_text"></div>
<? } ?>

<div id="wrap"<? if($infosidestatus['allow'] < 2) { ?> class="wrap s_clear"<? } else { ?> class="wrap with_side s_clear"<? } ?>>
<? if($infosidestatus['allow'] == 2) { ?>
<a id="sidebar_img" href="javascript:;" onclick="sidebar_collapse(['打开边栏', '关闭边栏']);" class="<?=$collapseimg['sidebar']?>"><? if($collapseimg['sidebar'] == 'collapsed_yes') { ?>打开边栏<? } else { ?>关闭边栏<? } ?></a>
<? } elseif($infosidestatus['allow'] == 1) { ?>
<a id="sidebar_img" href="javascript:;" onclick="sidebar_collapse(['', '关闭边栏']);" class="collapsed_yes">打开边栏</a>
<? } ?>
<div class="main">
<div class="content">
<div id="forumheader" class="s_clear">
<h1><?=$forum['name']?></h1>
<p class="forumstats">[ <strong><?=$forum['threads']?></strong> 主题 / <? echo $forum['posts']-$forum['threads']; ?> 回复 ]</p>
<div class="forumaction">
<a href="my.php?item=favorites&amp;fid=<?=$fid?>" id="ajax_favorite" class="addfav" onclick="ajaxmenu(event, this.id)">收藏</a>
<? if($rssstatus) { ?><a href="rss.php?fid=<?=$fid?>&amp;auth=<?=$rssauth?>" target="_blank" class="feed">RSS</a><? } ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=digest" target="_blank" class="digest">精华</a>
<? if($adminid == 1 && $forum['recyclebin']) { ?>
<a href="admincp.php?action=recyclebin&amp;frames=yes" target="_blank" class="recyclebin">回收站</a>
<? } elseif($forum['ismoderator'] && $forum['recyclebin']) { ?>
<a href="modcp.php?action=recyclebins&amp;&amp;fid=<?=$fid?>" target="_blank" class="recyclebin">回收站</a>
<? } ?>
</div>
<? if($forum['description']) { ?><p class="channelinfo">版块介绍: <?=$forum['description']?></p><? } ?>
<p id="modedby">
版主: <? if($moderatedby) { ?><?=$moderatedby?><? } else { ?>*空缺中*<? } if($forum['ismoderator']) { ?>
&nbsp;
<? if($forum['modworks']) { ?>
<strong>[<a href="modcp.php?fid=<?=$fid?>" target="_blank">当前版块有需要处理的管理事项</a>]</strong>
<? } else { ?>
<strong>[<a href="modcp.php?fid=<?=$fid?>" target="_blank">版主管理</a>]</strong>
<? } } ?>
</p>
</div>

<? if($forum['recommendlist'] || $forum['rules']) { ?>
<div id="modarea">
<div class="list">
<span class="headactions"><img onclick="toggle_collapse('modarea_c');" alt="收起/展开" title="收起/展开" src="<?=IMGDIR?>/<?=$collapseimg['modarea_c']?>.gif" id="modarea_c_img" class="toggle" /></span>
<h3>
<? if($forum['recommendlist']) { ?><a href="javascript:;" id="tab_1" class="current" <? if($forum['rules']) { ?> onclick="switchtab(2, 1)"<? } ?>>推荐主题</a><? } if($forum['recommendlist'] &&  $forum['rules']) { ?><span class="pipe">|</span><? } if($forum['rules']) { ?><a href="javascript:;" id="tab_2"<? if(!$forum['recommendlist']) { ?> class="current"<? } ?> <? if($forum['recommendlist']) { ?> onclick="switchtab(2, 2)"<? } ?>>本版规则</a><? } ?>
</h3>
</div>
<div id="modarea_c" style="<?=$collapse['modarea_c']?>">
<? if($forum['recommendlist']) { ?>
<div id="tabc_1" class="inlinelist titlelist s_clear">
<ul><? if(is_array($forum['recommendlist'])) { foreach($forum['recommendlist'] as $tid => $thread) { ?><li class="wide"><a href="viewthread.php?tid=<?=$tid?>" <?=$thread['subjectstyles']?> target="_blank"><?=$thread['subject']?></a><cite>-<a href="space.php?uid=<?=$thread['authorid']?>" target="_blank"><?=$thread['author']?></a></cite></li><? } } ?></ul>
</div>
<? } if($forum['rules']) { ?>
<div id="tabc_2"<? if($forum['recommendlist']) { ?> style="display:none"<? } ?> class="rule"><?=$forum['rules']?></div>
<? } ?>
</div>
</div>
<? } if($subexists) { ?>
<div id="subforum" class="mainbox list">
<? include template('forumdisplay_subforum'); ?>
</div>
<? } ?>

<div class="pages_btns s_clear">
<?=$multipage?>
<span <? if($visitedforums) { ?>id="visitedforums" onmouseover="$('visitedforums').id = 'visitedforumstmp';this.id = 'visitedforums';showMenu(this.id)"<? } ?> class="pageback"><a href="<?=$indexname?>">返回首页</a></span>
<span class="postbtn" id="newspecial" onmouseover="$('newspecial').id = 'newspecialtmp';this.id = 'newspecial';showMenu(this.id)"><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');return false;">发帖</a></span>
</div>

<? if($forum['threadtypes'] && $forum['threadtypes']['listable'] || $forum['threadsorts'] && $forum['threadsorts']['listable']) { ?>
<div class="threadtype">
<? if($forum['threadtypes'] && $forum['threadtypes']['listable']) { ?>
<p>
<? if($typeid || $sortid) { ?><a href="forumdisplay.php?fid=<?=$fid?>">全部</a><? } else { ?><strong>全部</strong><? } if(is_array($forum['threadtypes']['flat'])) { foreach($forum['threadtypes']['flat'] as $id => $name) { if($typeid != $id) { ?><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=type&amp;typeid=<?=$id?><?=$sortadd?>"><?=$name?></a><? } else { ?><strong><?=$name?></strong><? } ?> <? } } if($forum['threadtypes']['selectbox']) { ?>
<span id="threadtypesmenu" class="dropmenu" onmouseover="showMenu(this.id)">更多分类</span>
<div class="popupmenu_popup" id="threadtypesmenu_menu" style="display: none">
<ul><? if(is_array($forum['threadtypes']['selectbox'])) { foreach($forum['threadtypes']['selectbox'] as $id => $name) { ?><li>
<? if($typeid != $id) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=type&amp;typeid=<?=$id?><?=$sortadd?>"><?=$name?></a>
<? } else { ?>
<strong><?=$name?></strong>
<? } ?>
</li><? } } ?></ul>
</div>
<? } ?>
</p>
<? } if($forum['threadsorts'] && $forum['threadsorts']['listable']) { ?>
<p>
<? if(!$forum['threadtypes']['listable']) { if($sortid) { ?><a href="forumdisplay.php?fid=<?=$fid?>">全部</a><? } else { ?><strong>全部</strong><? } } if(is_array($forum['threadsorts']['flat'])) { foreach($forum['threadsorts']['flat'] as $id => $name) { if($sortid != $id) { ?><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=sort&amp;sortid=<?=$id?><?=$typeadd?>"><?=$name?></a><? } else { ?><strong><?=$name?></strong><? } ?> <? } } if($forum['threadsorts']['selectbox']) { ?>
<span id="threadsortsmenu" class="dropmenu" onmouseover="showMenu(this.id)">更多分类</span>
<div class="popupmenu_popup" id="threadsortsmenu_menu" style="display: none">
<ul><? if(is_array($forum['threadsorts']['selectbox'])) { foreach($forum['threadsorts']['selectbox'] as $id => $name) { ?><li>
<? if($sortid != $id) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=sort&amp;sortid=<?=$id?><?=$typeadd?>"><?=$name?></a>
<? } else { ?>
<strong><?=$name?></strong>
<? } ?>
</li><? } } ?></ul>
</div>
<? } ?>
</p>
<? } ?>
</div>
<? } ?>

<div id="threadlist" class="threadlist datalist" style="position: relative;">
<form method="post" name="moderate" id="moderate" action="topicadmin.php?action=moderate&amp;fid=<?=$fid?>&amp;infloat=yes&amp;nopost=yes">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="listextra" value="<?=$extra?>" />
<table summary="forum_<?=$fid?>" <? if(!$separatepos) { ?>id="forum_<?=$fid?>"<? } ?> cellspacing="0" cellpadding="0" class="datatable">
<thead class="colplural">
<tr>
<td class="folder">&nbsp;</td>
<td class="icon">&nbsp;</td>
<? if($forum['ismoderator']) { ?><td class="icon">&nbsp;</td><? } ?>
<th>标题</th>
<td class="author">作者</td>
<td class="nums">回复/查看</td>
<td class="lastpost"><cite>最后发表</cite></td>
</tr>
</thead>

<? if($page == 1 && !empty($announcement)) { ?>
<tbody>
<tr>
<td class="folder"><img src="<?=IMGDIR?>/ann_icon.gif" alt="公告" /></td>
<td class="icon">&nbsp;</td>
<? if($forum['ismoderator']) { ?><td class="icon">&nbsp;</td><? } ?>
<th class="subject"><strong>公告: <? if(empty($announcement['type'])) { ?><a href="announcement.php?id=<?=$announcement['id']?>#<?=$announcement['id']?>" target="_blank"><?=$announcement['subject']?></a><? } else { ?><a href="<?=$announcement['message']?>" target="_blank"><?=$announcement['subject']?></a><? } ?></strong></th>
<td class="author">
<cite><a href="space.php?uid=<?=$announcement['authorid']?>"><?=$announcement['author']?></a></cite>
<em><?=$announcement['starttime']?></em>
</td>
<td class="nums">&nbsp;</td>
<td class="lastpost">&nbsp;</td>
</tr>
</tbody>
<? } if($threadcount) { if(is_array($threadlist)) { foreach($threadlist as $key => $thread) { if($separatepos == $key + 1) { ?>
<tbody>
<tr>
<td class="folder"></td><td>&nbsp;</td>
<? if($forum['ismoderator']) { ?><td>&nbsp;</td><? } ?>
<th class="subject">版块主题</th><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr>
</tbody>
<? } ?>
<tbody id="<?=$thread['id']?>" <? if(in_array($thread['displayorder'], array(4, 5))) { ?>style="display: none"<? } ?>>
<tr>
<td class="folder">
<a href="viewthread.php?tid=<?=$thread['tid']?>&amp;extra=<?=$extra?>" title="新窗口打开" target="_blank">
<? if($thread['folder'] == 'lock') { ?>
<img src="<?=IMGDIR?>/folder_lock.gif" alt="Lock" />
<? } elseif(in_array($thread['displayorder'], array(1, 2, 3))) { ?>
<img src="<?=IMGDIR?>/pin_<?=$thread['displayorder']?>.gif" alt="<?=$threadsticky[3-$thread['displayorder']]?>" />
<? } else { ?>
<img src="<?=IMGDIR?>/folder_<?=$thread['folder']?>.gif" alt="<?=$thread['folder']?>" />
<? } ?>
</a>
</td>
<td class="icon">
<? if($thread['special'] == 1) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=poll"><img src="<?=IMGDIR?>/pollsmall.gif" alt="投票" /></a>
<? } elseif($thread['special'] == 2) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=trade"><img src="<?=IMGDIR?>/tradesmall.gif" alt="商品" /></a>
<? } elseif($thread['special'] == 3) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=reward"><img src="<?=IMGDIR?>/rewardsmall.gif" alt="悬赏" <? if($thread['price'] < 0) { ?>class="solved"<? } ?> /></a>
<? } elseif($thread['special'] == 4) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=activity"><img src="<?=IMGDIR?>/activitysmall.gif" alt="活动" /></a>
<? } elseif($thread['special'] == 5) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=debate"><img src="<?=IMGDIR?>/debatesmall.gif" alt="辩论" /></a>
<? } elseif($thread['special'] == 6) { ?>
<a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=video"><img src="<?=IMGDIR?>/videosmall.gif" alt="视频" /></a>
<? } else { ?>
<?=$thread['icon']?>
<? } ?>
</td>
<? if($forum['ismoderator']) { ?>
<td class="icon">
<? if($thread['fid'] == $fid && $thread['digest'] >= 0) { ?>
<input onclick="modclick(this)" class="checkbox" type="checkbox" name="moderate[]" value="<?=$thread['tid']?>" />
<? } else { ?>
<input class="checkbox" type="checkbox" disabled="disabled" />
<? } ?>
</td>
<? } ?>
<th class="subject <?=$thread['folder']?>">
<label>
<? if($thread['rate'] > 0) { ?>
<img src="<?=IMGDIR?>/agree.gif" alt="帖子被加分" title="帖子被加分" />
<? } elseif($thread['rate'] < 0) { ?>
<img src="<?=IMGDIR?>/disagree.gif" alt="帖子被减分" title="帖子被减分" />
<? } if($thread['digest'] > 0) { ?>
<img src="<?=IMGDIR?>/digest_<?=$thread['digest']?>.gif" alt="精华 <?=$thread['digest']?>" title="精华 <?=$thread['digest']?>" />
<? } ?>
&nbsp;</label>
<? if($thread['moved']) { if($forum['ismoderator']) { ?>
<a href="topicadmin.php?action=moderate&amp;optgroup=3&amp;operation=delete&amp;tid=<?=$thread['moved']?>" onclick="floatwinreset = 1;floatwin('open_mods', this.href, 250, 215);return false">移动:</a>
<? } else { ?>
移动:
<? } } ?>
<?=$thread['sortid']?> <?=$thread['typeid']?>
<span id="thread_<?=$thread['tid']?>"><a href="viewthread.php?tid=<?=$thread['tid']?>&amp;extra=<?=$extra?>"<?=$thread['highlight']?>><?=$thread['subject']?></a></span>
<? if($thread['readperm']) { ?> - [阅读权限 <span class="bold"><?=$thread['readperm']?></span>]<? } if($thread['price'] > 0) { if($thread['special'] == '3') { ?>
- <span style="color: #C60">[悬赏<?=$extcredits[$creditstransextra['2']]['title']?> <span class="bold"><?=$thread['price']?></span> <?=$extcredits[$creditstransextra['2']]['unit']?>]</span>
<? } else { ?>
- [售价 <?=$extcredits[$creditstransextra['1']]['title']?> <span class="bold"><?=$thread['price']?></span> <?=$extcredits[$creditstransextra['1']]['unit']?>]
<? } } elseif($thread['special'] == '3' && $thread['price'] < 0) { ?>
- <span style="color: #269F11">[已解决]</span>
<? } if($thread['attachment'] == 2) { ?>
<img src="images/attachicons/image_s.gif" alt="图片附件" class="attach" />
<? } elseif($thread['attachment'] == 1) { ?>
<img src="images/attachicons/common.gif" alt="附件" class="attach" />
<? } if($thread['multipage']) { ?>
<span class="threadpages"><?=$thread['multipage']?></span>
<? } if($thread['new']) { ?>
<a href="redirect.php?tid=<?=$thread['tid']?>&amp;goto=newpost<?=$highlight?>#newpost" class="new">New</a>
<? } ?>
</th>
<td class="author">
<cite>
<? if($thread['authorid'] && $thread['author']) { ?>
<a href="space.php?uid=<?=$thread['authorid']?>"><?=$thread['author']?></a>
<? } else { if($forum['ismoderator']) { ?>
<a href="space.php?uid=<?=$thread['authorid']?>">匿名</a>
<? } else { ?>
匿名
<? } } ?>
</cite>
<em><?=$thread['dateline']?></em>
</td>
<td class="nums"><strong><?=$thread['replies']?></strong>/<em><?=$thread['views']?></em></td>
<td class="lastpost">
<cite><? if($thread['lastposter']) { ?><a href="<? if($thread['digest'] != -2) { ?>space.php?username=<?=$thread['lastposterenc']?><? } else { ?>viewthread.php?tid=<?=$thread['tid']?>&amp;page=<? echo max(1, $thread['pages']);; } ?>"><?=$thread['lastposter']?></a><? } else { ?>匿名<? } ?></cite>
<em><a href="<? if($thread['digest'] != -2) { ?>redirect.php?tid=<?=$thread['tid']?>&amp;goto=lastpost<?=$highlight?>#lastpost<? } else { ?>viewthread.php?tid=<?=$thread['tid']?>&amp;page=<? echo max(1, $thread['pages']);; } ?>"><?=$thread['lastpost']?></a></em>
</td>
</tr>
</tbody><? } } ?><tfoot class="colplural">
<tr>
<td class="folder">&nbsp;</td>
<td class="icon">&nbsp;</td>
<? if($forum['ismoderator']) { ?><td class="icon">&nbsp;</td><? } ?>
<th>
<a href="javascript:;" id="filtertype" class="dropmenu" onclick="showMenu(this.id);">类型</a>
<a href="javascript:;" id="filterorder" class="dropmenu" onclick="showMenu(this.id);">排序方式</a>
</th>
<td class="author">
<a href="javascript:;" id="filtertime" class="dropmenu" onclick="showMenu(this.id);">时间范围</a>
</td>
<td class="nums">&nbsp;</td>
<td class="lastpost">&nbsp;</td>
</tr>
</tfoot>
<? } else { ?>
<tbody><tr><th colspan="6"><p class="nodata">本版块或指定的范围内尚无主题。</p></th></tr></tbody>
<? } ?>
</table>
<? if($forum['ismoderator'] && $threadcount) { include template('topicadmin_modlayer'); } ?>

</form>
</div>
<div class="pages_btns s_clear">
<?=$multipage?>
<span <? if($visitedforums) { ?>id="visitedforums" onmouseover="$('visitedforums').id = 'visitedforumstmp';this.id = 'visitedforums';showMenu(this.id)"<? } ?> class="pageback"><a href="<?=$indexname?>">返回首页</a></span>
<span class="postbtn" id="newspecialtmp" onmouseover="$('newspecial').id = 'newspecialtmp';this.id = 'newspecial';showMenu(this.id)"><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');return false;">发帖</a></span>
</div>

<? if($whosonlinestatus) { ?>
<dl id="onlinelist">
<? if($detailstatus) { ?>
<dd>
<span class="headactions"><a href="forumdisplay.php?fid=<?=$fid?>&amp;page=<?=$page?>&amp;showoldetails=no#online"><img src="<?=IMGDIR?>/collapsed_no.gif" alt="" /></a></span>
<h3>正在浏览此版块的会员</h3>
</dd>
<dd>
<ul class="s_clear"><? if(is_array($whosonline)) { foreach($whosonline as $key => $online) { ?><li title="时间: <?=$online['lastactivity']?><?="\n"?> 操作: <?=$online['action']?><?="\n"?> 版块: <?=$forumname?>">
<img src="images/common/<?=$online['icon']?>"  alt="" />
<? if($online['uid']) { ?>
<a href="space.php?uid=<?=$online['uid']?>"><?=$online['username']?></a>
<? } else { ?>
<?=$online['username']?>
<? } ?>
</li><? } } ?></ul>
</dd>
<? } else { ?>
<dt>
<span class="headactions"><a href="forumdisplay.php?fid=<?=$fid?>&amp;page=<?=$page?>&amp;showoldetails=yes#online" class="nobdr"><img src="<?=IMGDIR?>/collapsed_yes.gif" alt="" /></a></span>
<h3>正在浏览此版块的会员</h3>
</dt>
<? } ?>
</dl>
<? } ?>

</div>
</div>
<? if($infosidestatus['allow'] == 2) { ?>
<div id="sidebar" class="side" style="<?=$collapse['sidebar']?>">
<? if(!empty($qihoo['status']) && ($qihoo['searchbox'] & 2)) { ?>
<div id="qihoosearch" class="sidebox">
<form method="post" action="search.php?srchtype=qihoo" onSubmit="this.target='_blank';">
<input type="hidden" name="searchsubmit" value="yes" />
<input type="text" class="txt" name="srchtxt" size="20" value="<?=$qihoo_searchboxtxt?>" />
&nbsp;<button type="submit">搜索</button>
</form>
</div>
<? } if($infosidestatus['0']) { if(!empty($qihoo['status']) && ($qihoo['searchbox'] & 2)) { ?>
<hr class="shadowline"/>
<? } ?>
<div id="infoside"><? request($infosidestatus, 1, 0); ?></div>
<? } ?>
</div>
<? } ?>


<ul class="popupmenu_popup postmenu" id="newspecial_menu" style="display: none">
<? if(!$forum['allowspecialonly']) { ?><li><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');doane(event)">发新话题</a></li><? } if($allowpostpoll || !$discuz_uid) { ?><li class="poll"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=1">发布投票</a></li><? } if($allowpostreward || !$discuz_uid) { ?><li class="reward"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=3">发布悬赏</a></li><? } if($allowpostdebate || !$discuz_uid) { ?><li class="debate"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=5">发布辩论</a></li><? } if($allowpostactivity || !$discuz_uid) { ?><li class="activity"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=4">发布活动</a></li><? } if($allowpostvideo || !$discuz_uid) { ?><li class="video"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=6">发布视频</a></li><? } if($allowposttrade || !$discuz_uid) { ?><li class="trade"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=2">发布商品</a></li><? } if($forum['threadsorts'] && !$forum['allowspecialonly']) { if(is_array($forum['threadsorts']['types'])) { foreach($forum['threadsorts']['types'] as $id => $threadsorts) { if($forum['threadsorts']['show'][$id]) { ?>
<li class="popupmenu_option"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;extra=<?=$extra?>&amp;sortid=<?=$id?>"><?=$threadsorts?></a></li>
<? } } } if(is_array($forum['typemodels'])) { foreach($forum['typemodels'] as $id => $model) { ?><li class="popupmenu_option"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;extra=<?=$extra?>&amp;modelid=<?=$id?>"><?=$model['name']?></a></li><? } } } ?>
</ul>

<ul class="popupmenu_popup headermenu_popup filter_popup" id="filtertype_menu" style="display: none;">
<li><a href="forumdisplay.php?fid=<?=$fid?>">全部</a></li>
<? if($showpoll) { ?><li <? if($filter == 'poll') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=poll">投票</a></li><? } if($showtrade) { ?><li <? if($filter == 'trade') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=trade">商品</a></li><? } if($showreward) { ?><li <? if($filter == 'reward') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=reward">悬赏</a></li><? } if($showactivity) { ?><li <? if($filter == 'poll') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=activity">活动</a></li><? } if($showdebate) { ?><li <? if($filter == 'activity') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=debate">辩论</a></li><? } if($showvideo) { ?><li <? if($filter == 'video') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=video">视频</a></li><? } ?>
</ul>
<ul class="popupmenu_popup headermenu_popup filter_popup" id="filterorder_menu" style="display: none;">
<li <? if($orderby == 'lastpost') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=<?=$filter?>&amp;orderby=lastpost<?=$typeadd?><?=$sortadd?>">回复时间</a></li>
<li <? if($orderby == 'dateline') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=<?=$filter?>&amp;orderby=dateline<?=$typeadd?><?=$sortadd?>">发布时间</a></li>
<li <? if($orderby == 'replies') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=<?=$filter?>&amp;orderby=replies<?=$typeadd?><?=$sortadd?>">回复数量</a></li>
<li <? if($orderby == 'views') { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;filter=<?=$filter?>&amp;orderby=views<?=$typeadd?><?=$sortadd?>">浏览次数</a></li>
</ul>
<ul class="popupmenu_popup headermenu_popup filter_popup" id="filtertime_menu" style="display: none;">
<li <? if($filter == 0) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=0<?=$typeadd?>" <?=$check['0']?>>全部</a></li>
<li <? if($filter == 86400) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=86400<?=$typeadd?><?=$sortadd?>" <?=$check['86400']?>>1 天</a></li>
<li <? if($filter == 172800) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=172800<?=$typeadd?><?=$sortadd?>" <?=$check['172800']?>>2 天</a></li>
<li <? if($filter == 604800) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=604800<?=$typeadd?><?=$sortadd?>" <?=$check['604800']?>>1 周</a></li>
<li <? if($filter == 2592000) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=2592000<?=$typeadd?><?=$sortadd?>" <?=$check['2592000']?>>1 个月</a></li>
<li <? if($filter == 7948800) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=7948800<?=$typeadd?><?=$sortadd?>" <?=$check['7948800']?>>3 个月</a></li>
<li <? if($filter == 15897600) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=15897600<?=$typeadd?><?=$sortadd?>" <?=$check['15897600']?>>6 个月</a></li>
<li <? if($filter == 31536000) { ?>class="active"<? } ?>><a href="forumdisplay.php?fid=<?=$fid?>&amp;orderby=<?=$orderby?>&amp;filter=31536000<?=$typeadd?><?=$sortadd?>" <?=$check['31536000']?>>1 年</a></li>
</ul>


<? if($visitedforums) { ?>
<ul class="popupmenu_popup" id="visitedforums_menu" style="display: none">
<?=$visitedforums?>
</ul>
<? } if($forumjump) { ?>
<div class="popupmenu_popup" id="fjump_menu" style="display: none">
<?=$forummenu?>
</div>
<? } ?>

<script type="text/javascript">
var maxpage = <? if($maxpage) { ?><?=$maxpage?><? } else { ?>1<? } ?>;
if(maxpage > 1) {
document.onkeyup = function(e){
e = e ? e : window.event;
var tagname = is_ie ? e.srcElement.tagName : e.target.tagName;
if(tagname == 'INPUT' || tagname == 'TEXTAREA') return;
actualCode = e.keyCode ? e.keyCode : e.charCode;
<? if($page < $maxpage) { ?>
if(actualCode == 39) {
window.location = 'forumdisplay.php?fid=<?=$fid?><?=$forumdisplayadd?>&page=<? echo $page+1;; ?>';
}
<? } if($page > 1) { ?>
if(actualCode == 37) {
window.location = 'forumdisplay.php?fid=<?=$fid?><?=$forumdisplayadd?>&page=<? echo $page-1;; ?>';
}
<? } ?>
}
}
function switchtab(total, current) {
for(i = 1; i <= total;i++) {
$('tab_' + i).className = '';
$('tabc_' + i).style.display = 'none';
}
$('tab_' + current).className = 'current';
$('tabc_' + current).style.display = '';
}
//loadselect('filtertype');
//loadselect('filterorder');
//loadselect('filtertime');
</script>
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