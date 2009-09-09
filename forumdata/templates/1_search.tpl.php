<? if(!defined('IN_DISCUZ')) exit('Access Denied'); 
0
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/header.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search_threads.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search_sort.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/footer.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/css_script.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/header.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/footer.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/jsmenu.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/css_script.htm', 1251359984, '1', './templates/default')
|| checktplrefresh('/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/search.htm', '/Users/AChien/Projects/ey_discuz_bbs_full/././templates/default/jsmenu.htm', 1251359984, '1', './templates/default')
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
<div id="nav"><a href="<?=$indexname?>"><?=$bbname?></a> &raquo; 搜索</div>
<div id="wrap" class="wrap">
<form class="searchform" method="post" action="search.php"<? if($qihoo['status']) { ?> target="_blank"<? } ?>>
<input type="hidden" name="formhash" value="<?=FORMHASH?>" />
<? if(!empty($srchtype)) { ?><input type="hidden" name="srchtype" value="<?=$srchtype?>" /><? } ?>

<label for="srchtxt" class="searchlabel">
搜索
<strong>
<? if($srchtype == 'threadsort') { ?>
分类信息
<? } elseif($srchtype == 'trade') { ?>
商品
<? } elseif($srchtype == 'qihoo') { ?>
奇虎全文
<? } else { ?>
帖子
<? } ?>
</strong>
</label>

<? if($srchtype != 'threadsort') { ?>
<p class="searchkey">
<input type="text" id="srchtxt" name="srchtxt" size="45" maxlength="40" value="<?=$keyword?>" class="txt" tabindex="1" />
<script type="text/javascript">$('srchtxt').focus();</script>
<? if($checkarray['posts']) { ?>
<select name='srchtype'>
<option value="title">标题</option>
<? if(!$disabled['fulltext']) { ?><option value="fulltext">全文</option><? } ?>
</select>
<? } ?>
<button type="submit" name="searchsubmit" id="searchsubmit" value="true">搜索</button>
<? if($checkarray['posts']) { ?>
<a href="javascript:;" onclick="display('search_option')">高级</a>
<? } ?>
</p>
<? } ?>

<p>
<input type="radio" name="st" onclick="window.location=('search.php')" <?=$checkarray['posts']?> id="srchtypeposts"/> <label for="srchtypeposts">帖子</label>
<input type="radio" name="st" onclick="window.location=('search.php?srchtype=trade')" <?=$checkarray['trade']?> id="srchtypetrade"/> <label for="srchtypetrade">商品</label>
<? if($qihoo['status']) { ?><input type="radio" name="st" onclick="window.location=('search.php?srchtype=qihoo')" <?=$checkarray['qihoo']?> id="srchtypeqihoo" /> <label for="srchtypeqihoo">奇虎全文</label><? } ?>
<input type="radio" name="st" onclick="window.location=('search.php?srchtype=threadsort')" <?=$checkarray['threadsort']?> id="srchtypesort"/> <label for="srchtypesort">分类信息</label>
</p><? $policymsgs = $p = ''; if(is_array($creditspolicy['search'])) { foreach($creditspolicy['search'] as $id => $policy) { ?><?
$policymsg = <<<EOF

EOF;
 if($extcredits[$id]['img']) { 
$policymsg .= <<<EOF
{$extcredits[$id]['img']} 
EOF;
 } 
$policymsg .= <<<EOF
{$extcredits[$id]['title']} {$policy} {$extcredits[$id]['unit']}
EOF;
?><? $policymsgs .= $p.$policymsg;$p = ', '; } } if($policymsgs) { ?><p>每进行一次搜索将扣除 <?=$policymsgs?></p><? } if($srchtype != 'qihoo') { ?>
<div id="search_option" <? if($checkarray['posts']) { ?>style="display: none;"<? } ?>>
<hr class="shadowline"/>
<h3>搜索选项</h3>
<table summary="搜索" cellspacing="0" cellpadding="0" class="formtable">
<? if($srchtype == 'threadsort') { ?>
<tr>
<th><label for="typeid">分类信息</label></th>
<td>
<select name="sortid" onchange="ajaxget('post.php?action=threadsorts&sortid='+this.options[this.selectedIndex].value+'&operate=1&sid=<?=$sid?>', 'threadsorts', 'threadsortswait')">
<option value="0">无</option><?=$threadsorts?>
</select>
<span id="threadsortswait"></span>
</td>
</tr>
<tbody id="threadsorts"></tbody>
<? } if($checkarray['posts'] || $srchtype == 'trade') { ?>
<tr>
<td>作者</th>
<td><input type="text" id="srchname" name="srchuname" size="45" maxlength="40" class="txt" /></td>
</tr>

<tr>
<td>主题范围</td>
<td>
<label><input type="radio" name="srchfilter" value="all" checked="checked" /> 全部主题</label>
<label><input type="radio" name="srchfilter" value="digest" /> 精华主题</label>
<label><input type="radio" name="srchfilter" value="top" /> 置顶主题</label>
</td>
</tr>
<? } if($checkarray['posts']) { ?>
<tr>
<td>特殊主题</td>
<td>
<label><input type="checkbox" name="special[]" value="1" /> 投票主题</label>
<label><input type="checkbox" name="special[]" value="2" /> 商品主题</label>
<label><input type="checkbox" name="special[]" value="3" /> 悬赏主题</label>
<label><input type="checkbox" name="special[]" value="4" /> 活动主题</label>
<label><input type="checkbox" name="special[]" value="5" /> 辩论主题</label>
<label><input type="checkbox" name="special[]" value="6" /> 视频主题</label>
</td>
</tr>
<? } if($srchtype == 'trade') { ?>
<tr>
<td>商品类别</td>
<td>
<select name="srchtypeid"><option value="">全部</option><? if(is_array($tradetypes)) { foreach($tradetypes as $typeid => $typename) { ?><option value="<?=$typeid?>"><?=$typename?></option><? } } ?></select>
</td>
</tr>
<? } if($checkarray['posts'] || $srchtype == 'trade') { ?>
<tr>
<th><label for="srchfrom">搜索时间</label></th>
<td>
<select id="srchfrom" name="srchfrom">
<option value="0">全部时间</option>
<option value="86400">1 天</option>
<option value="172800">2 天</option>
<option value="604800">1 周</option>
<option value="1296000">1 个月</option>
<option value="5184000">3 个月</option>
<option value="8640000">6 个月</option>
<option value="31536000">1 年</option>
</select>
<label><input type="radio" name="before" value="" checked="checked" /> 以内</label>
<label><input type="radio" name="before" value="1" /> 以前</label>
</td>
</tr>

<tr>
<td><label for="orderby">排序类型</label></td>
<td>
<select id="orderby1" name="orderby">
<option value="lastpost" selected="selected">回复时间</option>
<option value="dateline">发布时间</option>
<option value="replies">回复数量</option>
<option value="views">浏览次数</option>
</select>
<select id="orderby2" name="orderby" style="position: absolute; display: none" disabled>
<option value="dateline" selected="selected">发布时间</option>
<option value="price">商品现价</option>
<option value="expiration">剩余时间</option>
</select>
<label><input type="radio" name="ascdesc" value="asc" /> 按升序排列</label>
<label><input type="radio" name="ascdesc" value="desc" checked="checked" /> 按降序排列</label>
</td>
</tr>
<? } ?>

<tr>
<td valign="top"><label for="srchfid">搜索范围</label></td>
<td>
<select id="srchfid" name="srchfid[]" multiple="multiple" size="10" style="width: 26em;">
<option value="all"<? if(!$srchfid) { ?> selected="selected"<? } ?>>全部版块</option>
<?=$forumselect?>
</select>
</td>
</tr>

<? if($srchtype == 'threadsort') { ?>
<tr>
<th>&nbsp;</th>
<td><button class="submit" type="submit" name="searchsubmit" value="true">搜索</button></td>
</tr>
<? } ?>
</table>
</div>
<? } if(empty($srchtype) && empty($keyword)) { ?>
<hr class="shadowline"/>
<h3>便捷搜索</h3>
<table cellspacing="4" cellpadding="0" width="100%">
<tr>
<td><a href="search.php?srchfrom=3600&amp;searchsubmit=yes">1 小时以内的新帖</a></td>
<td><a href="search.php?srchfrom=14400&amp;searchsubmit=yes">4 小时以内的新帖</a></td>
<td><a href="search.php?srchfrom=28800&amp;searchsubmit=yes">8 小时以内的新帖</a></td>
<td><a href="search.php?srchfrom=86400&amp;searchsubmit=yes">24 小时以内的新帖</a></td>
</tr>
<tr>
<td><a href="search.php?srchfrom=604800&amp;searchsubmit=yes">1 周内帖子</a></td>
<td><a href="search.php?srchfrom=2592000&amp;searchsubmit=yes">1 月内帖子</a></td>
<td><a href="search.php?srchfrom=15552000&amp;searchsubmit=yes">6 月内帖子</a></td>
<td><a href="search.php?srchfrom=31104000&amp;searchsubmit=yes">1 年内帖子</a></td>
</tr>
</table>
<? } ?>
</form>

<? if(!empty($searchid) && submitcheck('searchsubmit', 1)) { if($checkarray['posts']) { ?><div class="searchlist threadlist datalist">
<div class="itemtitle s_clear">
<h1><? if($keyword) { ?>结果: <em>找到 “<span class="emfont"><?=$keyword?></span>” 相关主题 <?=$index['threads']?> 个</em><? } else { ?>结果: <em>找到相关主题 <?=$index['threads']?> 个</em><? } ?></h1>
<? if(!empty($multipage)) { ?><?=$multipage?><? } ?>
</div>
<table summary="搜索" cellspacing="0" cellpadding="0" width="100%" class="datatable">
<thead>
<tr class="colplural">
<td class="icon">&nbsp;</td>
<th>标题</th>
<td class="forum">版块</td>
<td class="author">作者</td>
<td class="nums">回复/查看</td>
<td class="lastpost"><cite>最后发表</cite></td>
</tr>
</thead><? if(is_array($threadlist)) { foreach($threadlist as $thread) { ?><tbody>
<tr>
<td class="icon">
<? if($thread['special'] == 1) { ?>
<img src="<?=IMGDIR?>/pollsmall.gif" alt="投票" />
<? } elseif($thread['special'] == 2) { ?>
<img src="<?=IMGDIR?>/tradesmall.gif" alt="商品" />
<? } elseif($thread['special'] == 3) { ?>
<img src="<?=IMGDIR?>/rewardsmall.gif" alt="悬赏" />
<? } elseif($thread['special'] == 4) { ?>
<img src="<?=IMGDIR?>/activitysmall.gif" alt="活动" />
<? } elseif($thread['special'] == 5) { ?>
<img src="<?=IMGDIR?>/debatesmall.gif" alt="辩论" />
<? } elseif($thread['special'] == 6) { ?>
<img src="<?=IMGDIR?>/videosmall.gif" alt="视频" />
<? } else { ?>
<?=$thread['icon']?>
<? } ?>
</td>
<th class="subject">
<label>
<? if($thread['digest'] > 0) { ?>
<img src="<?=IMGDIR?>/digest_<?=$thread['digest']?>.gif" alt="精华 <?=$thread['digest']?>" />
<? } ?>
&nbsp;
</label>
<a href="viewthread.php?tid=<?=$thread['tid']?>&amp;highlight=<?=$index['keywords']?>" target="_blank" <?=$thread['highlight']?>><?=$thread['subject']?></a>
<? if($thread['attachment'] == 2) { ?>
<img src="images/attachicons/image_s.gif" alt="图片附件" class="attach" />
<? } elseif($thread['attachment'] == 1) { ?>
<img src="images/attachicons/common.gif" alt="附件" class="attach" />
<? } if($thread['multipage']) { ?><span class="threadpages"><?=$thread['multipage']?></span><? } ?>
</th>
<td class="forum"><a href="forumdisplay.php?fid=<?=$thread['fid']?>"><?=$thread['forumname']?></a></td>
<td class="author">
<cite>
<? if($thread['authorid'] && $thread['author']) { ?>
<a href="space.php?uid=<?=$thread['authorid']?>"><?=$thread['author']?></a>
<? } else { if($forum['ismoderator']) { ?><a href="space.php?uid=<?=$thread['authorid']?>">匿名</a><? } else { ?>匿名<? } } ?>
</cite>
<em><?=$thread['dateline']?></em>
</td>
<td class="nums"><strong><?=$thread['replies']?></strong> / <em><?=$thread['views']?></em></td>
<td class="lastpost">
<cite><? if($thread['lastposter']) { ?><a href="space.php?username=<?=$thread['lastposterenc']?>"><?=$thread['lastposter']?></a><? } else { ?>匿名<? } ?></cite>
<em><a href="redirect.php?tid=<?=$thread['tid']?>&amp;goto=lastpost<?=$highlight?>#lastpost"><?=$thread['lastpost']?></a></em>
</td>
</tr>
</tbody><? } } if(empty($threadlist)) { ?>
<tbody><tr><th colspan="6">对不起，没有找到匹配结果。</th></tr></tbody>
<? } ?>

</table>
<? if(!empty($multipage)) { ?><div class="pages_btns s_clear"><?=$multipage?></div><? } ?>
</div>
<? } elseif($checkarray['threadsort']) { ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<div id="nav">
<a href="<?=$indexname?>"><?=$bbname?></a> &raquo; 分类信息
</div>

<div id="wrap" class="wrap s_clear">
<div class="main">
<div class="content">
<div class="searchlist threadlist datalist">
<div class="itemtitle s_clear">
<h1>结果: <em>找到相关主题 <?=$index['threads']?> 个</em></h1>
<? if(!empty($multipage)) { ?><?=$multipage?><? } ?>
</div>
<table summary="搜索" cellspacing="0" cellpadding="0" width="100%" class="datatable">
<thead>
<tr class="colplural">
<td class="icon">&nbsp;</td>
<td>标题</td><? if(is_array($optionlist)) { foreach($optionlist as $var) { ?><td style="width: 10%"><?=$var?></td><? } } ?><td style="width: 15%">发布时间</td>
</tr>
</thead>
<? if($resultlist) { if(is_array($resultlist)) { foreach($resultlist as $tid => $value) { ?><tbody>
<tr>
<td class="icon"><?=$value['icon']?></td>
<th><a href="viewthread.php?tid=<?=$tid?>" target="_blank"><?=$value['subject']?></a></th><? if(is_array($value['option'])) { foreach($value['option'] as $var) { ?><td style="width: 10%"><? if($var) { ?><?=$var?><? } else { ?>&nbsp;<? } ?></td><? } } ?><td style="width: 15%"><?=$value['dateline']?></td>
</tr>
</tbody><? } } } else { ?>
<tr><td colspan="<?=$colspan?>">对不起，没有找到匹配结果。</td></tr>
<? } ?>
</table>
<? if(!empty($multipage)) { ?><div class="pages_btns s_clear"><?=$multipage?></div><? } ?>
</div>
</div>
</div>
</div>
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
</html><? } } ?>

</div>


<script type="text/javascript">
<? if($sortid) { ?>
ajaxget('post.php?action=threadsorts&sortid=<?=$sortid?>&operate=1&inajax=1', 'threadsorts', 'threadsortswait');
<? } ?>
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