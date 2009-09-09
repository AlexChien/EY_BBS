<? if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/header.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread_node.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread_fastpost.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/footer.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/css_script.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread_pay.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/seditor.htm', 1251359954, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/viewthread.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/jsmenu.htm', 1251359954, '1', './templates/default')
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
<script src="include/js/viewthread.js?<?=VERHASH?>" type="text/javascript"></script>
<script type="text/javascript">zoomstatus = parseInt(<?=$zoomstatus?>);var imagemaxwidth = '<?=IMAGEMAXWIDTH?>';var aimgcount = new Array();</script>

<div id="nav">
<? if($forumjump == 1) { ?><a href="<?=$indexname?>" id="fjump" onmouseover="showMenu(this.id)" class="dropmenu"><?=$bbname?></a><? } else { ?><a href="<?=$indexname?>"><?=$bbname?></a><? } ?><?=$navigation?>
</div>

<? if($admode && empty($insenz['hardadstatus']) && !empty($advlist['text'])) { ?><div class="ad_text" id="ad_text"><table summary="Text Ad" cellpadding="0" cellspacing="1"><?=$advlist['text']?></table></div><? } else { ?><div id="ad_text"></div><? } ?>
<div id="wrap" class="wrap s_clear threadfix">
<div class="forumcontrol">
<table cellspacing="0" cellpadding="0">
<tr>
<td class="modaction">
<? if($forum['ismoderator']) { ?>
<script type="text/javascript">
function modaction(action, pid, extra) {
if(!action) {
return;
}
var extra = !extra ? '' : '&' + extra;
if(!pid && in_array(action, ['delpost', 'banpost'])) {
var checked = 0;
var pid = '';
for(var i = 0; i < $('modactions').elements.length; i++) {
if($('modactions').elements[i].name.match('topiclist')) {
checked = 1;
break;
}
}
} else {
var checked = 1;
}
if(!checked) {
alert('请选择需要操作的帖子');
} else {
floatwinreset = 1;
$('modactions').action = 'topicadmin.php?action='+ action +'&fid=<?=$fid?>&tid=<?=$tid?>&infloat=yes&nopost=yes' + (!pid ? '' : '&topiclist[]=' + pid) + extra;
floatwin('open_mods', '', 250, action != 'split' ? 215 : 365);
$('floatwin_mods').innerHTML = '';
ajaxpost('modactions', 'floatwin_mods', '');
if(is_ie) {
doane(event);
}
hideMenu();
}
}
function modthreads(optgroup, operation) {
var operation = !operation ? '' : operation;
floatwinreset = 1;
$('modactions').action = 'topicadmin.php?action=moderate&fid=<?=$fid?>&moderate[]=<?=$tid?>&infloat=yes&nopost=yes' + (optgroup != 3 && optgroup != 2 ? '&from=<?=$tid?>' : '');
floatwin('open_mods', '', 250, optgroup < 2 ? 365 : 215);
$('modactions').optgroup.value = optgroup;
$('modactions').operation.value = operation;
$('floatwin_mods').innerHTML = '';
ajaxpost('modactions', 'floatwin_mods', '');
if(is_ie) {
doane(event);
}
}
function pidchecked(obj) {
if(obj.checked) {
if(is_ie && !is_opera) {
var inp = document.createElement('<input name="topiclist[]" />');
} else {
var inp = document.createElement('input');
inp.name = 'topiclist[]';
}
inp.id = 'topiclist_' + obj.value;
inp.value = obj.value;
inp.style.display = 'none';
$('modactions').appendChild(inp);
} else {
$('modactions').removeChild($('topiclist_' + obj.value));
}
}
var modclickcount = 0;
function modclick(obj, pid) {
if(obj.checked) {
modclickcount++;
} else {
modclickcount--;
}
$('modcount').innerHTML = modclickcount;
if(modclickcount > 0) {
var offset = fetchOffset(obj);				
$('modlayer').style.top = offset['top'] - 65 + 'px';
$('modlayer').style.left = offset['left'] - 215 + 'px';
$('modlayer').style.display = '';
} else {
$('modlayer').style.display = 'none';
}
}
</script>
<span id="modopt" onclick="$('modopt').id = 'modopttmp';this.id = 'modopt';showMenu(this.id)" class="dropmenu">主题管理</span>
<? } ?>
</td>
<td>
<?=$multipage?>
<span class="pageback"<? if($visitedforums) { ?> id="visitedforums" onmouseover="$('visitedforums').id = 'visitedforumstmp';this.id = 'visitedforums';showMenu(this.id)"<? } ?>><a href="<?=$upnavlink?>">返回列表</a></span>
<span class="replybtn"><a href="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>" onclick="floatwin('open_reply', this.href, 600, 410, '600,0');return false;">回复</a></span>
<span class="postbtn" id="newspecial" onmouseover="$('newspecial').id = 'newspecialtmp';this.id = 'newspecial';showMenu(this.id)"><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');return false;">发帖</a></span>
</td>
</tr>
</table>
</div>

<? if($forum['ismoderator']) { ?>
<ul class="popupmenu_popup headermenu_popup inlinelist" id="modopt_menu" style="width: 180px; display: none">
<? if($thread['digest'] >= 0) { if($allowdelpost) { ?><li class="wide"><a href="javascript:;" onclick="modthreads(3, 'delete')">删除主题</a></li><? } ?>
<li class="wide"><a href="javascript:;" onclick="modthreads(3, 'down')">提升下沉</a></li>
<? if($allowstickthread) { ?>
<li class="wide"><a href="javascript:;" onclick="modthreads(1, 'stick')">主题置顶</a></li>
<? } ?>
<li class="wide"><a href="javascript:;" onclick="modthreads(1, 'highlight')">高亮显示</a></li>
<li class="wide"><a href="javascript:;" onclick="modthreads(1, 'digest')">设置精华</a></li>
<? if($forum['modrecommend']['open'] && $forum['modrecommend']['sort'] != 1) { ?>
<li class="wide"><a href="javascript:;" onclick="modthreads(1, 'recommend')">推荐主题</a></li>
<? } ?>
<li class="wide"><a href="javascript:;" onclick="modthreads(4)">关闭打开</a></li>
<li class="wide"><a href="javascript:;" onclick="modthreads(2, 'move')">移动主题</a></li>
<li class="wide"><a href="javascript:;" onclick="modthreads(2, 'type')">主题分类</a></li>
<? if(!$thread['special']) { ?>
<li class="wide"><a href="javascript:;" onclick="modaction('copy')">复制主题</a></li>
<li class="wide"><a href="javascript:;" onclick="modaction('merge')">合并主题</a></li>
<? if($thread['price'] > 0 && $allowrefund) { ?>
<li class="wide"><a href="javascript:;" onclick="modaction('refund')">强制退款</a></li>
<? } } ?>
<li class="wide"><a href="javascript:;" onclick="modaction('split')">分割主题</a></li>
<li class="wide"><a href="javascript:;" onclick="modaction('repair')">修复主题</a></li>
<? } if($thread['special'] == 3) { ?>		
<li class="wide"><a href="javascript:;" onclick="modaction('removereward')">取消悬赏</a></li>
<? } ?>
</ul>
<? if($allowbanpost || $allowdelpost) { ?>
<div id="modlayer" style="display:none;position:position;width:165px;">
<span>选中</span><strong id="modcount"></strong><span>篇: </span>
<? if($allowbanpost) { ?>
<a href="javascript:;" onclick="modaction('warn')">警告</a>
<a href="javascript:;" onclick="modaction('banpost')">屏蔽</a>
<? } if($allowdelpost) { ?>
<a href="javascript:;" onclick="modaction('delpost')">删除</a>
<? } ?>
</div>
<? } } if($allowposttrade || $allowpostpoll || $allowpostreward || $allowpostactivity || $allowpostdebate || $allowpostvideo || $forum['threadsorts'] || $forum['threadtypes'] || !$discuz_uid) { ?>
<ul class="popupmenu_popup postmenu" id="newspecial_menu" style="display: none">
<? if(!$forum['allowspecialonly']) { ?><li><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');doane(event)">发新话题</a></li><? } if($allowpostpoll || !$discuz_uid) { ?><li class="poll"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=1">发布投票</a></li><? } if($allowpostreward || !$discuz_uid) { ?><li class="reward"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=3">发布悬赏</a></li><? } if($allowpostdebate || !$discuz_uid) { ?><li class="debate"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=5">发布辩论</a></li><? } if($allowpostactivity || !$discuz_uid) { ?><li class="activity"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=4">发布活动</a></li><? } if($allowpostvideo || !$discuz_uid) { ?><li class="video"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=6">发布视频</a></li><? } if($allowposttrade || !$discuz_uid) { ?><li class="trade"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;special=2">发布商品</a></li><? } if($forum['threadsorts'] && !$forum['allowspecialonly']) { if(is_array($forum['threadsorts']['types'])) { foreach($forum['threadsorts']['types'] as $id => $threadsorts) { if($forum['threadsorts']['show'][$id]) { ?>
<li class="popupmenu_option"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;extra=<?=$extra?>&amp;sortid=<?=$id?>"><?=$threadsorts?></a></li>
<? } } } if(is_array($forum['typemodels'])) { foreach($forum['typemodels'] as $id => $model) { ?><li class="popupmenu_option"><a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;extra=<?=$extra?>&amp;modelid=<?=$id?>"><?=$model['name']?></a></li><? } } } ?>
</ul>
<? } ?>

<div id="postlist" class="mainbox viewthread"><? $postcount = 0; if(is_array($postlist)) { foreach($postlist as $post) { ?><div id="post_<?=$post['pid']?>">
<table id="pid<?=$post['pid']?>" summary="pid<?=$post['pid']?>" cellspacing="0" cellpadding="0">
<tr>
<td class="postauthor">
<? if($post['authorid'] && $post['username'] && !$post['anonymous']) { if($authoronleft) { ?>
<div class="postinfo">
<a target="_blank" href="space.php?uid=<?=$post['authorid']?>" style="margin-left: 20px; font-weight: 800"><?=$post['author']?></a>
</div>
<? } ?>
<div class="popupmenu_popup userinfopanel" id="userinfo<?=$post['pid']?>" style="display: none; position: absolute;<? if($authoronleft) { ?>margin-top: -11px;<? } ?>">
<div class="popavatar">
<div id="userinfo<?=$post['pid']?>_ma"></div>
<ul class="profile_side">
<li class="pm"><a href="pm.php?action=new&amp;uid=<?=$post['authorid']?>" onclick="floatwin('open_sendpm', this.href, 600, 410);return false;" title="发短消息">发短消息</a></li>
<? if($post['msn']['1']) { ?>
<li style="text-indent:0"><a target="_blank" href="http://settings.messenger.live.com/Conversation/IMMe.aspx?invitee=<?=$post['msn']['1']?>@apps.messenger.live.com&amp;mkt=zh-cn" title="MSN 聊天"><img style="border-style: none; margin-right: 5px; vertical-align: middle;" src="http://messenger.services.live.com/users/<?=$post['msn']['1']?>@apps.messenger.live.com/presenceimage?mkt=zh-cn" width="16" height="16" />MSN 聊天</a></li>
<? } ?>
<li class="buddy"><a href="my.php?item=buddylist&amp;newbuddyid=<?=$post['authorid']?>&amp;buddysubmit=yes" target="_blank" id="ajax_buddy_<?=$post['count']?>" title="加为好友" onclick="ajaxmenu(event, this.id, 3000, 0)">加为好友</a></li>
</ul>
</div>
<div class="popuserinfo">
<p>
<a href="space.php?uid=<?=$post['authorid']?>" target="_blank"><?=$post['author']?></a>
<? if($post['nickname']) { ?><em>(<?=$post['nickname']?>)</em><? } if($vtonlinestatus && $post['authorid']) { if(($vtonlinestatus == 2 && $onlineauthors[$post['authorid']]) || ($vtonlinestatus == 1 && ($timestamp - $post['lastactivity'] <= 10800) && !$post['invisible'])) { ?>
<em>当前在线
<? } else { ?>
<em>当前离线
<? } ?>
</em>
<? } ?>
</p>
<? if($post['customstatus']) { ?><p class="customstatus"><?=$post['customstatus']?></p><? } ?>

<dl class="s_clear"><? @eval('echo "'.$customauthorinfo['2'].'";'); ?></dl>
<div class="imicons">
<? if($post['qq']) { ?><a href="http://wpa.qq.com/msgrd?V=1&amp;Uin=<?=$post['qq']?>&amp;Site=<?=$bbname?>&amp;Menu=yes" target="_blank" title="QQ"><img src="<?=IMGDIR?>/qq.gif" alt="QQ" /></a><? } if($post['icq']) { ?><a href="http://wwp.icq.com/scripts/search.dll?to=<?=$post['icq']?>" target="_blank" title="ICQ"><img src="<?=IMGDIR?>/icq.gif" alt="ICQ" /></a><? } if($post['yahoo']) { ?><a href="http://edit.yahoo.com/config/send_webmesg?.target=<?=$post['yahoo']?>&amp;.src=pg" target="_blank" title="Yahoo"><img src="<?=IMGDIR?>/yahoo.gif" alt="Yahoo!"  /></a><? } if($post['taobao']) { ?><a href="javascript:;" onclick="window.open('http://amos.im.alisoft.com/msg.aw?v=2&uid='+encodeURIComponent('<?=$post['taobaoas']?>')+'&site=cntaobao&s=2&charset=utf-8')" title="taobao"><img src="<?=IMGDIR?>/taobao.gif" alt="阿里旺旺" /></a><? } if($ucappopen['UCHOME']) { ?>
<a href="<?=$uchomeurl?>/space.php?uid=<?=$post['authorid']?>" target="_blank" title="个人空间"><img src="<?=IMGDIR?>/home.gif" alt="个人空间"  /></a>
<? } elseif($ucappopen['XSPACE']) { ?>
<a href="<?=$xspaceurl?>/?uid-<?=$post['authorid']?>" target="_blank" title="个人空间"><img src="<?=IMGDIR?>/home.gif" alt="个人空间"  /></a>
<? } if($post['site']) { ?><a href="<?=$post['site']?>" target="_blank" title="查看个人网站"><img src="<?=IMGDIR?>/forumlink.gif" alt="查看个人网站"  /></a><? } ?>
<a href="space.php?uid=<?=$post['authorid']?>" target="_blank" title="查看详细资料"><img src="<?=IMGDIR?>/userinfo.gif" alt="查看详细资料"  /></a>
</div>
<div id="avatarfeed"><span id="threadsortswait"></span></div>
</div>
</div>
<? } ?>
<?=$post['newpostanchor']?> <?=$post['lastpostanchor']?>
<? if($post['authorid'] && $post['username'] && !$post['anonymous']) { ?>
<div id="userinfo<?=$post['pid']?>_a">
<? if($bannedmessages & 2 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5) || ($post['status'] & 1))) { ?>
<div class="avatar">头像被屏蔽</div>
<? } elseif($post['avatar'] && $showavatars) { ?>
<div class="avatar" onmouseover="showauthor(this, 'userinfo<?=$post['pid']?>')"><a href="space.php?uid=<?=$post['authorid']?>" target="_blank"><?=$post['avatar']?></a></div>
<? } ?>
<p><em><?=$post['authortitle']?></em></p>
</div>
<p><? showstars($post['stars']); ?></p>
<? if($customauthorinfo['1']) { ?><dl class="profile s_clear"><? @eval('echo "'.$customauthorinfo['1'].'";'); ?></dl><? } if($post['medals']) { ?><p><? if(is_array($post['medals'])) { foreach($post['medals'] as $medal) { ?><img src="images/common/<?=$medal['image']?>" alt="<?=$medal['name']?>" title="<?=$medal['name']?>" /><? } } ?></p>
<? } } else { ?>
<div class="avatar">
<? if(!$post['authorid']) { ?>
<a href="javascript:;">游客 <em><?=$post['useip']?></em></a>
<? } elseif($post['authorid'] && $post['username'] && $post['anonymous']) { if($forum['ismoderator']) { ?><a href="space.php?uid=<?=$post['authorid']?>" target="_blank">匿名</a><? } else { ?>匿名<? } } else { ?>
<?=$post['author']?> <em>该用户已被删除</em>
<? } ?>
</div>
<? } if($allowedituser || $allowbanuser || ($forum['ismoderator'] && $allowviewip && ($thread['digest'] >= 0 || !$post['first']))) { ?>
<hr class="shadowline" />
<p>
<? if($forum['ismoderator'] && $allowviewip && ($thread['digest'] >= 0 || !$post['first'])) { ?>
<a href="javascript:;" onclick="ajaxget('topicadmin.php?action=getip&fid=<?=$fid?>&tid=<?=$tid?>&pid=<?=$post['pid']?>', 'ajax_getip_<?=$post['count']?>');doane(event)" title="查看 IP" class="lightlink">IP</a>&nbsp;&nbsp;
<? } if($allowedituser) { ?>
<a href="<? if($adminid == 1) { ?>admincp.php?action=members&username=<?=$post['usernameenc']?>&submit=yes&frames=yes<? } else { ?>modcp.php?action=members&op=edit&uid=<?=$post['authorid']?><? } ?>" target="_blank" class="lightlink">编辑此人</a>&nbsp;&nbsp;
<? } if($allowbanuser) { if($adminid == 1) { ?><a href="admincp.php?action=members&amp;operation=ban&amp;username=<?=$post['usernameenc']?>&amp;frames=yes" target="_blank" class="lightlink">禁止此人</a>
<? } else { ?><a href="modcp.php?action=members&amp;op=ban&amp;uid=<?=$post['authorid']?>" target="_blank" class="lightlink">禁止此人</a>
<? } } ?>
</p>
<p id="ajax_getip_<?=$post['count']?>"></p>
<? } ?>
</td>
<td class="postcontent">
<div class="postinfo">
<strong><a title="复制本帖链接" id="postnum<?=$post['pid']?>" href="javascript:;" onclick="setcopy('<?=$boardurl?>viewthread.php?tid=<?=$tid?>&amp;page=<?=$page?><?=$fromuid?>#pid<?=$post['pid']?>', '帖子地址已经复制到剪贴板')"><? if(!empty($postno[$post['number']])) { ?><?=$postno[$post['number']]?><? } else { ?><em><?=$post['number']?></em><?=$postno['0']?><? } ?></a></strong>
<div class="posterinfo">
<div class="pagecontrol">
<? if($post['first']) { ?>
<a href="viewthread.php?action=printable&amp;tid=<?=$tid?>" target="_blank" class="print left">打印</a>
<? if(MSGBIGSIZE) { ?>
<div class="msgfsize right">
<label>字体大小: </label><small onclick="$('postlist').className='mainbox viewthread'" title="正常">t</small><big onclick="$('postlist').className='mainbox viewthread t_bigfont'" title="放大">T</big>
</div>
<? } } elseif($thread['special'] == 5) { ?>
<span class="debatevote poststand_<? echo intval($post['stand']); ?>">
<label><? if($post['stand'] == 1) { ?><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=1" title="只看正方">正方</a>
<? } elseif($post['stand'] == 2) { ?><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=2" title="只看反方">反方</a>
<? } else { ?><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=0" title="只看中立">中立</a><? } ?>
</label>
<? if($post['stand']) { ?>
<span><a href="misc.php?action=debatevote&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" id="voterdebate_<?=$post['pid']?>" onclick="ajaxmenu(event, this.id)">支持我</a><?=$post['voters']?></span>
<? } ?>
</span>
<? } ?>
</div>
<div class="authorinfo">
<? if($_DCACHE['groupicon'][$post['groupid']]) { ?>
<img class="authicon" id="authicon<?=$post['pid']?>" src="<?=$_DCACHE['groupicon'][$post['groupid']]?>" onclick="showauthor(this, 'userinfo<?=$post['pid']?>')" />
<? } else { ?>
<img class="authicon" id="authicon<?=$post['pid']?>" src="images/common/online_member.gif" onclick="showauthor(this, 'userinfo<?=$post['pid']?>');" />
<? } if($post['authorid'] && !$post['anonymous']) { if(!$authoronleft) { ?><a href="space.php?uid=<?=$post['authorid']?>" class="posterlink" target="_blank"><?=$post['author']?></a><? } ?><em id="authorposton<?=$post['pid']?>">发表于 <?=$post['dateline']?></em>
<? if(!$authorid) { ?>
 | <a href="viewthread.php?tid=<?=$post['tid']?>&amp;page=<?=$page?>&amp;authorid=<?=$post['authorid']?>" rel="nofollow">只看该作者</a>
<? } else { ?>
 | <a href="viewthread.php?tid=<?=$post['tid']?>&amp;page=<?=$page?>" rel="nofollow">显示全部帖子</a>
<? } } elseif($post['authorid'] && $post['username'] && $post['anonymous']) { ?>
匿名 <em id="authorposton<?=$post['pid']?>">发表于 <?=$post['dateline']?></em>
<? } elseif(!$post['authorid'] && !$post['username']) { ?>
游客 <em id="authorposton<?=$post['pid']?>">发表于 <?=$post['dateline']?></em>
<? } ?>
</div>
</div>
</div>
<div class="defaultpost">
<? if($admode && empty($insenz['hardadstatus']) && !empty($advlist['thread2'][$post['count']])) { ?><div class="ad_textlink2" id="ad_thread2_<?=$post['count']?>"><?=$advlist['thread2'][$post['count']]?></div><? } else { ?><div id="ad_thread2_<?=$post['count']?>"></div><? } if($admode && empty($insenz['hardadstatus']) && !empty($advlist['thread3'][$post['count']])) { ?><div class="ad_pip" id="ad_thread3_<?=$post['count']?>"><?=$advlist['thread3'][$post['count']]?></div><? } else { ?><div id="ad_thread3_<?=$post['count']?>"></div><? } ?><div id="ad_thread4_<?=$post['count']?>"></div>
<div class="postmessage <? if($post['first']) { ?>firstpost<? } ?>">
<? if($post['warned']) { ?>
<span class="postratings"><a href="misc.php?action=viewwarning&amp;tid=<?=$tid?>&amp;uid=<?=$post['authorid']?>" title="受到警告" onclick="floatwin('open_viewwarning', this.href, 600, 410);return false;"><img src="<?=IMGDIR?>/warning.gif" border="0" /></a></span>
<? } if($thread['special'] == 3 && $post['first']) { if($thread['price'] > 0) { ?>
<cite class="re_unsolved">未解决</cite>
<? } elseif($thread['price'] < 0) { ?>
<cite class="re_solved">已解决</cite>
<? } if($activityclose) { ?><cite class="re_solved">活动已结束</cite><? } } if($post['first']) { ?>
<div id="threadtitle">
<? if($thread['readperm']) { ?><em>所需阅读权限 <?=$thread['readperm']?></em><? } ?>
<h1><?=$thread['subject']?></h1>
<? if($thread['tags'] || $relatedkeywords) { ?>
<div class="threadtags">
<? if($thread['tags']) { ?><?=$thread['tags']?><? } if($relatedkeywords) { ?><span class="postkeywords"><?=$relatedkeywords?></span><? } ?>
</div>
<? } ?>
</div>
<? if($thread['special'] == 2 && !$post['message'] && $post['authorid'] == $discuz_uid) { ?>
<p>
<a href="post.php?action=edit&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_edit', this.href, 600, 410, '600,0');return false;">添加柜台介绍</a>
</p>
<? } } elseif($post['subject']) { ?>
<h2><?=$post['subject']?></h2>
<? } if($adminid != 1 && $bannedmessages & 1 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5))) { ?>
<div class="locked">提示: <em>作者被禁止或删除 内容自动屏蔽</em></div>
<? } elseif($adminid != 1 && $post['status'] & 1) { ?>
<div class="locked">提示: <em>该帖被管理员或版主屏蔽</em></div>
<? } elseif($post['first'] && $threadpay) { if($thread['freemessage']) { ?>
<div id="postmessage_<?=$pid?>" class="t_msgfont"><?=$thread['freemessage']?></div>
<? } ?>
<div class="locked">
<a href="javascript:;" class="right viewpay" title="购买主题" onclick="floatwin('open_pay', 'misc.php?action=pay&tid=<?=$tid?>&pid=<?=$post['pid']?>', 600, 410)">购买主题</a>
<em class="right">
已购买人数:<?=$thread['payers']?>&nbsp; <a href="misc.php?action=viewpayments&amp;tid=<?=$tid?>" onclick="floatwin('open_pay', this.href, 600, 410);return false;">记录</a>
</em>
<? if($thread['price'] > 0) { ?>本主题需向作者支付 <strong><?=$thread['price']?> <?=$extcredits[$creditstransextra['1']]['title']?> </strong> 才能浏览<? } if($thread['endtime']) { ?>本主题购买截止日期为 <?=$thread['endtime']?>，到期后将免费<? } ?>
</div>
</div><? } else { if($bannedmessages & 1 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5))) { ?>
<div class="locked">提示: <em>作者被禁止或删除 内容自动屏蔽，只有管理员可见</em></div>
<? } elseif($post['status'] & 1) { ?>
<div class="locked">提示: <em>该帖被管理员或版主屏蔽，只有管理员可见</em></div>
<? } if($post['first']) { if($thread['price'] > 0 && $thread['special'] == 0) { ?>
<div class="locked"><em class="right"><a href="misc.php?action=viewpayments&amp;tid=<?=$tid?>" onclick="floatwin('open_pay', this.href, 600, 410);return false;">记录</a></em>付费主题, 价格:<strong><?=$extcredits[$creditstransextra['1']]['title']?> <?=$thread['price']?> <?=$extcredits[$creditstransextra['1']]['unit']?> </strong></div>
<? } if($typetemplate) { ?>
<?=$typetemplate?>
<? } elseif($optionlist && !($post['status'] & 1) && !$threadpay) { ?>
<div class="typeoption">
<h4><?=$forum['threadsorts']['types'][$thread['sortid']]?></h4>
<table summary="分类信息" cellpadding="0" cellspacing="0" class="formtable datatable"><? if(is_array($optionlist)) { foreach($optionlist as $option) { ?><tr class="<? echo swapclass('colplural'); ?>">
<th><?=$option['title']?></th>
<td><? if($option['value']) { ?><?=$option['value']?><? } else { ?>-<? } ?></td>
</tr><? } } ?></table>
</div>
<? } if($thread['special'] == 1) { include template('viewthread_poll'); } elseif($thread['special'] == 3) { include template('viewthread_reward_price'); } elseif($thread['special'] == 4) { include template('viewthread_activity_info'); } elseif($thread['special'] == 5) { include template('viewthread_debate_umpire'); } elseif($thread['special'] == 6) { include template('viewthread_video'); } } ?>
<div class="<? if(!$thread['special']) { ?>t_msgfontfix<? } else { ?>specialmsg<? } ?>">
<table cellspacing="0" cellpadding="0"><tr><td class="t_msgfont" id="postmessage_<?=$post['pid']?>"><?=$post['message']?></td></tr></table>
<? if($post['first']) { if($thread['special'] == 2) { include template('viewthread_trade'); } elseif($thread['special'] == 3) { if($bapid) { $bestpost = $postlist[$bapid];unset($postlist[$bapid]); } include template('viewthread_reward'); } elseif($thread['special'] == 4) { include template('viewthread_activity'); } elseif($thread['special'] == 5) { include template('viewthread_debate'); } } if($post['attachment']) { ?>
<div class="locked">附件: <em>您所在的用户组无法下载或查看附件</em></div>
<? } elseif($hideattach[$post['pid']] && $post['attachments']) { ?>
<div class="locked">附件: <em>本帖附件需要回复才可下载或查看</em></div>
<? } elseif($post['imagelist'] || $post['attachlist']) { ?>
<div class="postattachlist">
<? if($post['imagelist']) { ?>
<?=$post['imagelist']?>
<? } if($post['attachlist']) { ?>
<?=$post['attachlist']?>
<? } ?>
</div>
<? } if($relatedthreadlist && !$qihoo['relate']['position'] && $post['first']) { ?>
<div class="tagrelated">
<h3><em><a href="http://search.qihoo.com/sint/qusearch.html?kw=<?=$searchkeywords?>&amp;sort=rdate&amp;ics=<?=$charset?>&amp;domain=<?=$site?>&amp;tshow=1" target="_blank">更多相关主题</a></em>相关主题</h3>
<ul><? if(is_array($relatedthreadlist)) { foreach($relatedthreadlist as $key => $threads) { if($threads['tid'] != $tid) { ?>
<li>
<? if(!$threads['insite']) { ?>
[站外] <a href="topic.php?url=<? echo urlencode($threads['tid']); ?>&amp;md5=<? echo md5($threads['tid']); ?>&amp;statsdata=<?=$fid?>||<?=$tid?>" target="_blank"><?=$threads['title']?></a>&nbsp;&nbsp;&nbsp;
[ <a href="post.php?action=newthread&amp;fid=<?=$fid?>&amp;extra=<?=$extra?>&amp;url=<? echo urlencode($threads['tid']); ?>&amp;md5=<? echo md5($threads['tid']); ?>&amp;from=direct" style="color: #090" target="_blank">转帖</a> ]
<? } else { ?>
<a href="viewthread.php?tid=<?=$threads['tid']?>&amp;statsdata=<?=$fid?>||<?=$tid?>" target="_blank"><?=$threads['title']?></a>
<? } ?>
</li>
<? } } } ?></ul>
</div>
<? } if($post['first'] && $relatedtagstatus) { ?>
<div id="relatedtags"></div>
<script src="tag.php?action=relatetag&rtid=<?=$tid?>" type="text/javascript" reload="1"></script>
<? } ?>
</div>

<? if(!empty($post['ratelog'])) { ?>
<dl class="newrate">
<dt>
<? if(!empty($postlist[$post['pid']]['totalrate'])) { ?>
<strong><a href="misc.php?action=viewratings&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_viewratings', this.href, 600, 410);return false;" title="本帖最近评分记录"><? echo count($postlist[$post['pid']]['totalrate']);; ?></a></strong>
<p>评分次数</p>
<? } ?>
</dt>
<dd>
<ul class="s_clear">
<div id="post_rate_<?=$post['pid']?>"></div><? if(is_array($post['ratelog'])) { foreach($post['ratelog'] as $uid => $ratelog) { ?><li>
<div id="rate_<?=$post['pid']?>_<?=$uid?>_menu" class="attach_popup" style="display: none;">
<p class="cornerlayger"><?=$ratelog['reason']?> &nbsp;&nbsp;<? if(is_array($ratelog['score'])) { foreach($ratelog['score'] as $id => $score) { if($score > 0) { ?>
<em><?=$extcredits[$id]['title']?> + <?=$score?> <?=$extcredits[$id]['unit']?></em>
<? } else { ?>
<?=$extcredits[$id]['title']?> <?=$score?> <?=$extcredits[$id]['unit']?>
<? } } } ?></p>
<p class="minicorner"></p>
</div>
<p id="rate_<?=$post['pid']?>_<?=$uid?>" onmouseover="showMenu(this.id,false,2)" class="rateavatar"><a href="space.php?uid=<?=$uid?>" target="_blank"><? echo discuz_uc_avatar($uid, 'small');; ?></a></p>
<p><a href="space.php?uid=<?=$uid?>" target="_blank"><?=$ratelog['username']?></a></p>
</li><? } } ?></ul>
</dd>
</dl>
<? } else { ?>
<div id="post_rate_div_<?=$post['pid']?>"></div>
<? } } if($post['first']) { if($lastmod['modaction']) { ?><div class="modact"><a href="misc.php?action=viewthreadmod&amp;tid=<?=$tid?>" title="主题操作记录" onclick="floatwin('open_viewthreadmod', this.href, 600, 410);return false;">本主题由 <?=$lastmod['modusername']?> 于 <?=$lastmod['moddateline']?> <?=$lastmod['modaction']?></a></div><? } if($lastmod['magicname']) { ?><div class="modact"><a href="misc.php?action=viewthreadmod&amp;tid=<?=$tid?>" title="主题操作记录" onclick="floatwin('open_viewthreadmod', this.href, 600, 410);return false;">本主题由 <?=$lastmod['modusername']?> 于 <?=$lastmod['moddateline']?> 使用 <?=$lastmod['magicname']?> 道具</a></div><? } ?>
<div class="useraction">
<a id="ajax_favorite" <? if($discuz_uid) { ?>href="my.php?item=favorites&amp;tid=<?=$tid?>" onclick="ajaxmenu(event, this.id, 3000, 0)"<? } else { ?>href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;"<? } ?>>收藏</a>
<a id="emailfriend" href="misc.php?action=emailfriend&amp;tid=<?=$tid?>" onclick="floatwin('open_emailfriend', this.href, 250, <? if($discuz_uid) { ?>380<? } else { ?>200<? } ?>);return false;">分享</a>
<a id="ratelink" <? if($discuz_uid) { ?>href="misc.php?action=rate&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_rate', this.href, 250, 270);return false;"<? } else { ?>href="logging.php?action=login" onclick="floatwin('open_login', this.href, 600, 400);return false;"<? } ?>>评分</a>
</div>
<? } ?>
</div>

</div>
<? if($post['signature'] && ($bannedmessages & 4 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5) || ($post['status'] & 1)))) { ?>
<div class="signatures">
<table cellspacing="0" cellpadding="0">
<tr>
<td>签名被屏蔽</td>
</tr>
</table>
</div>
<? } elseif($post['signature'] && !$post['anonymous'] && $showsignatures) { ?>
<div class="signatures" style="maxHeightIE: <?=$maxsigrows?>px;">
<table cellspacing="0" cellpadding="0">
<tr>
<td>
<?=$post['signature']?>
</td>
</tr>
</table>
</div>
<? } if($admode && empty($insenz['hardadstatus']) && !empty($advlist['thread1'][$post['count']])) { ?><div class="ad_textlink1" id="ad_thread1_<?=$post['count']?>"><?=$advlist['thread1'][$post['count']]?></div><? } else { ?><div id="ad_thread1_<?=$post['count']?>"></div><? } ?>
</td>
</tr>
<tr>
<td class="postauthor"></td>
<td class="postcontent">
<div class="postactions">
<? if($forum['ismoderator'] && ($allowdelpost || $allowbanpost)) { ?>
<span class="right">
<label for="manage<?=$post['pid']?>">
<? if($post['first'] && $thread['digest'] == -1) { ?>
<input type="checkbox" id="manage<?=$post['pid']?>" disabled="disabled" />
<? } else { ?>
<input type="checkbox" id="manage<?=$post['pid']?>" <? if(!empty($modclick)) { ?>checked="checked" <? } ?>onclick="pidchecked(this);modclick(this, <?=$post['pid']?>)" value="<?=$post['pid']?>" />
<? } ?>
管理
</label>
</span>
<? } ?>
<div class="postact s_clear">
<em>
<a class="fastreply" href="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;reppost=<?=$post['pid']?>&amp;extra=<?=$extra?>&amp;page=<?=$page?>" onclick="floatwin('open_reply', this.href, 600, 410, '600,0');return false;">回复</a>
<a class="repquote" href="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;repquote=<?=$post['pid']?>&amp;extra=<?=$extra?>&amp;page=<?=$page?>" onclick="floatwin('open_reply', this.href, 600, 410, '600,0');return false;">引用</a>
<? if((($forum['ismoderator'] && $alloweditpost && !(in_array($post['adminid'], array(1, 2, 3)) && $adminid > $post['adminid'])) || ($forum['alloweditpost'] && $discuz_uid && $post['authorid'] == $discuz_uid)) && ($thread['digest'] >= 0 || !$post['first'])) { ?>
<a class="editpost" href="post.php?action=edit&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>"<? if(!$post['first'] || !$threadsort && !$thread['special']) { ?> onclick="floatwin('open_edit', this.href, 600, 410, '600,0');return false;"<? } ?>>编辑</a>
<? } ?>
</em>
<p>
<? if($thread['special'] == 3 && ($forum['ismoderator'] || $thread['authorid'] == $discuz_uid) && $discuz_uid != $post['authorid'] && $post['authorid'] != $thread['authorid'] && $post['first'] == 0 && $thread['price'] > 0) { ?>
<a href="javascript:;" onclick="setanswer(<?=$post['pid']?>)">最佳答案</a>
<? } if($post['first']) { ?>
<a href="my.php?item=subscriptions&amp;subadd=<?=$tid?>" id="ajax_subscription" onclick="ajaxmenu(event, this.id, 3000, null, 0)">订阅</a>
<? } elseif($raterange && $post['authorid']) { ?>
<a href="misc.php?action=rate&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_rate', this.href, 250, 270);return false;">评分</a>
<? } if($post['rate'] && $forum['ismoderator']) { ?>
<a href="misc.php?action=removerate&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>&amp;page=<?=$page?>" onclick="floatwin('open_rate', this.href, 600, 410);return false;">撤销评分</a>
<? } if(!$forum['ismoderator'] && $discuz_uid && $reportpost && $discuz_uid != $post['authorid']) { ?>
<a href="misc.php?action=report&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_report', this.href, 250, 215);doane(event);">报告</a>
<? } if($discuz_uid && $magicstatus) { ?>
<a href="magic.php?action=usemagic&amp;type=1&amp;pid=<?=$post['pid']?>" onclick="floatwin('open_magics', this.href, 250, 215);doane(event);">道具</a>
<? } ?>
<a href="javascript:;" onclick="scrollTo(0,0);">TOP</a>
</p>
</div>
</div>
</td>
</tr>
<tr class="threadad">
<td class="postauthor"></td>
<td class="adcontent">
<? if($post['first'] && $thread['replies']) { if($admode && empty($insenz['hardadstatus']) && !empty($advlist['interthread'])) { ?><div class="ad_column" id="ad_interthread"><?=$advlist['interthread']?><? } else { ?><div id="ad_interthread"><? } ?></div><? } ?>
</td>
</tr>
<? if($post['first'] && $thread['special'] == 5 && $stand != '') { ?>
<tr class="threadad stand_select">
<td class="postauthor" style="background: #EBF2F8;"></td>
<td>
<div class="itemtitle s_clear">
<h2>按立场筛选: </h2>
<ul>
<li><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>" hidefocus="true"><span>全部</span></a></li>
<li <? if($stand == 1) { ?>class="current"<? } ?>><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=1" hidefocus="true"><span>正方</span></a></li>
<li <? if($stand == 2) { ?>class="current"<? } ?>><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=2" hidefocus="true"><span>反方</span></a></li>
<li <? if($stand == 0) { ?>class="current"<? } ?>><a href="viewthread.php?tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;stand=0" hidefocus="true"><span>中立</span></a></li>
</ul>
</div>
<hr class="solidline" />
</td>
</tr>
<? } ?>
</table>
<? if($aimgs[$post['pid']]) { ?>
<script type="text/javascript" reload="1">aimgcount[<?=$post['pid']?>] = [<? echo implode(',', $aimgs[$post['pid']]);; ?>];attachimgshow(<?=$post['pid']?>);</script>
<? } ?>
</div><? } } ?></div>

<div id="postlistreply" class="mainbox viewthread"><div id="post_new" style="display: none"></div></div>

<form method="post" name="modactions" id="modactions">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="optgroup" />
<input type="hidden" name="operation" />
<input type="hidden" name="listextra" value="<?=$extra?>" />
</form>

<?=$tagscript?>

<div class="forumcontrol s_clear">
<table cellspacing="0" cellpadding="0" <? if($fastpost) { ?>class="narrow"<? } ?>>
<tr>
<td class="modaction">
<? if($forum['ismoderator']) { ?>
<span id="modopttmp" onclick="$('modopt').id = 'modopttmp';this.id = 'modopt';showMenu(this.id)" class="dropmenu">主题管理</span>
<? } ?>
</td>
<td>
<?=$multipage?>
<span class="pageback"<? if($visitedforums) { ?> id="visitedforums" onmouseover="$('visitedforums').id = 'visitedforumstmp';this.id = 'visitedforums';showMenu(this.id)"<? } ?>><a href="<?=$upnavlink?>">返回列表</a></span>
<? if(!$fastpost) { ?>
<span class="replybtn"><a href="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>" onclick="floatwin('open_reply', this.href, 600, 410, '600,0');return false;">回复</a></span>
<span class="postbtn" id="newspecialtmp" onmouseover="$('newspecial').id = 'newspecialtmp';this.id = 'newspecial';showMenu(this.id)"><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');return false;">发帖</a></span>
<? } ?>
</td>
</tr>
</table>
</div>

<? if($fastpost && $allowpostreply) { ?><script type="text/javascript">
var postminchars = parseInt('<?=$minpostsize?>');
var postmaxchars = parseInt('<?=$maxpostsize?>');
var disablepostctrl = parseInt('<?=$disablepostctrl?>');
</script>

<div id="f_post" class="mainbox viewthread">
<form method="post" id="fastpostform" action="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>&amp;extra=<?=$extra?>&amp;replysubmit=yes&amp;infloat=yes&amp;handlekey=fastpost" onSubmit="return fastpostvalidate(this)">
<table cellspacing="0" cellpadding="0">
<tr>
<td class="postauthor">
<? if($discuz_uid) { ?><div class="avatar"><? echo discuz_uc_avatar($discuz_uid); ?></div><? } ?>
</td>
<td class="postcontent">
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<input type="hidden" name="subject" value="" />
<input type="hidden" name="usesig" value="<?=$usesigcheck?>" />
<? if($uchome['addfeed'] && $ucappopen['UCHOME'] && $forum['allowfeed']) { ?>
<input type="hidden" name="addfeed" value="1" />
<? } ?>

<span id="fastpostreturn"></span>
<? if($thread['special'] == 5 && empty($firststand)) { ?>
<div class="s_clear">
<label class="left">选择观点: </label>
<div class="float_typeid short_select">
<select id="stand" name="stand">
<option value="0">中立</option>
<option value="1">正方</option>
<option value="2">反方</option>
</select>
</div>
<script type="text/javascript">
loadselect('stand', 0, '', 0, 1);
</script>
</div>
<? } ?>
<div class="editor_tb">
<span class="right">
<a href="post.php?action=reply&amp;fid=<?=$fid?>&amp;tid=<?=$tid?>" onclick="floatwin('open_reply', this.href, 600, 410, '600,0');return false;">高级回复</a>
<span class="pipe">|</span>
<span id="newspecialtmp" onmouseover="$('newspecial').id = 'newspecialtmp';this.id = 'newspecial';showMenu(this.id)"><a href="post.php?action=newthread&amp;fid=<?=$fid?>" onclick="floatwin('open_newthread', this.href, 600, 410, '600,0');return false;">发新话题</a></span>
</span><? $seditor = array('fastpost', array('bold', 'color', 'img', 'link', 'quote', 'code', 'smilies')); ?><link href="forumdata/cache/style_<?=STYLEID?>_seditor.css?<?=VERHASH?>" rel="stylesheet" type="text/css" />
<div>
<? if(in_array('bold', $seditor['1'])) { ?>
<a href="javascript:;" title="粗体" class="tb_bold" onclick="seditor_insertunit('<?=$seditor['0']?>', '[b]', '[/b]')">B</a>
<? } if(in_array('color', $seditor['1'])) { ?>
<a href="javascript:;" title="颜色" class="tb_color" id="<?=$seditor['0']?>forecolor" onclick="showMenu(this.id, true, 0, 2)">Color</a><? $coloroptions = array('Black', 'Sienna', 'DarkOliveGreen', 'DarkGreen', 'DarkSlateBlue', 'Navy', 'Indigo', 'DarkSlateGray', 'DarkRed', 'DarkOrange', 'Olive', 'Green', 'Teal', 'Blue', 'SlateGray', 'DimGray', 'Red', 'SandyBrown', 'YellowGreen','SeaGreen', 'MediumTurquoise','RoyalBlue', 'Purple', 'Gray', 'Magenta', 'Orange', 'Yellow', 'Lime', 'Cyan', 'DeepSkyBlue', 'DarkOrchid', 'Silver', 'Pink', 'Wheat', 'LemonChiffon', 'PaleGreen', 'PaleTurquoise', 'LightBlue', 'Plum', 'White') ?><div class="popupmenu_popup tb_color" id="<?=$seditor['0']?>forecolor_menu" style="display: none"><? if(is_array($coloroptions)) { foreach($coloroptions as $key => $colorname) { ?><input type="button" style="background-color: <?=$colorname?>" onclick="seditor_insertunit('<?=$seditor['0']?>', '[color=<?=$colorname?>]', '[/color]')" /><? if(($key + 1) % 8 == 0) { ?><br /><? } } } ?></div>
<? } if(in_array('img', $seditor['1'])) { ?>
<a href="javascript:;" title="插入图片" class="tb_img" onclick="seditor_insertunit('<?=$seditor['0']?>', '[img]', '[/img]')">Image</a>
<? } if(in_array('link', $seditor['1'])) { ?>
<a href="javascript:;" title="插入链接" class="tb_link" onclick="seditor_insertunit('<?=$seditor['0']?>', '[url]', '[/url]')">Link</a>
<? } if(in_array('quote', $seditor['1'])) { ?>
<a href="javascript:;" title="插入引用" class="tb_quote" onclick="seditor_insertunit('<?=$seditor['0']?>', '[quote]', '[/quote]')">Quote</a>
<? } if(in_array('code', $seditor['1'])) { ?>
<a href="javascript:;" title="插入代码" class="tb_code" onclick="seditor_insertunit('<?=$seditor['0']?>', '[code]', '[/code]')">Code</a>
<? } if(in_array('smilies', $seditor['1'])) { ?>
<a href="javascript:;" class="tb_smilies" id="<?=$seditor['0']?>smilies" onclick="showMenu(this.id, true, 0, 2)">Smilies</a>
<script type="text/javascript">smilies_show('<?=$seditor['0']?>smiliesdiv', <?=$smcols?>, 0, '<?=$seditor['0']?>');</script>
<? } ?>
</div></div>
<textarea rows="5" cols="80" name="message" id="fastpostmessage" onKeyDown="seditor_ctlent(event, 'fastpostvalidate($(\'fastpostform\'))');" tabindex="2" class="txtarea"></textarea>
<? if($secqaacheck || $seccodecheck) { ?><div class="fastcheck"><? $secchecktype = 3; include template('seccheck'); ?>
</div><? } ?>
<p><button type="submit" name="replysubmit" id="fastpostsubmit" value="replysubmit" tabindex="3">发表回复</button></p>
</td>
</tr>
</table>
</form>
</div><? } if($relatedthreadlist && $qihoo['relate']['position']) { include template('viewthread_relatedthread'); } if($visitedforums) { ?>
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
window.location = 'viewthread.php?tid=<?=$tid?>&page=<? echo $page+1; ?>';
}
<? } if($page > 1) { ?>
if(actualCode == 37) {
window.location = 'viewthread.php?tid=<?=$tid?>&page=<? echo $page-1; ?>';
}
<? } ?>
}
}
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
</html><? if($relatedthreadupdate) { ?>
<script src="relatethread.php?tid=<?=$tid?>&subjectenc=<?=$thread['subjectenc']?>&tagsenc=<?=$thread['tagsenc']?>&verifykey=<?=$verifykey?>&up=<?=$qihoo_up?>" type="text/javascript"></script>
<? } if($tagupdate) { ?>
<script src="relatekw.php?tid=<?=$tid?>" type="text/javascript"></script>
<? } if($qihoo['relate']['bbsnum'] && $statsdata) { ?>
<img style="display:none;" src="http://pvstat.qihoo.com/dimana.gif?_pdt=discuz&amp;_pg=s100812&amp;_r=<?=$randnum?>&amp;_dim_k=orgthread&amp;_dim_v=<? echo urlencode($boardurl);; ?>||<?=$statsdata?>||0" width="1" height="1" alt="" />
<img style="display:none;" src="http://pvstat.qihoo.com/dimana.gif?_pdt=discuz&amp;_pg=s100812&amp;_r=<?=$randnum?>&amp;_dim_k=relthread&amp;_dim_v=<?=$statskeywords?>||<?=$statsurl?>" width="1" height="1" alt="" />
<? } ?>