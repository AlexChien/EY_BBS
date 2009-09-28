<? if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
#modlayer { position: absolute; right: 0; padding: 12px; width: 275px; background-color: #FFF; border: 4px solid #7FCAE2; }
#modlayer a { color: #09C; }
#modlayer label { float: right; margin-top: 5px; }
#modcount { padding: 0 2px; font-size: 24px; font-weight: 400; color: #F60; }
#modlayer .collapse { position: absolute; right: 0; top: 5px; padding: 0 5px; }
#modlayer.collapsed { margin-right: -23px; padding: 0; width: 27px; height: 35px; border: none; background: #F60; overflow: hidden; }
#modlayer.collapsed #modcount { display: block; position: absolute; left: 0; top: 0; z-index: 999; width: 27px; height: 35px; border-color: #09C; background-color: #09C; color: #FFF; font-size: 12px; text-align: center; line-height: 35px; cursor: pointer; }

.listtopicadmin { height: 200px; border-top: 1px solid #C5DAEB; }
.listtopicadmin li { *overflow:hidden; *margin-bottom: -2px; height: 28px; line-height: 18px; border-top: 1px solid #FFF; border-bottom: 1px solid #C5DAEB; zoom: 1; }
.listtopicadmin .currentopt { height: auto; }
.detailopt { visibility: hidden; overflow: hidden; white-space: nowrap; }
.currentopt .detailopt { visibility: visible; }
.detailopt p .txt { width: 100px; }
.detailopt span .txt { width: 18px; border-right: none; }
.detailopt a { float: left; text-indent: -999px; margin-right: 3px; width: 22px; height: 20px; text-align: center; }
.detailopt_bold, .detailopt_italic, .detailopt_underline { border: 1px solid #F1F5FA; outline: none; }
.detailopt .current { border: 1px solid #999; background-color: #FFF; }

.listtopicadmin table { width: 100%; }
.listtopicadmin td { vertical-align: top; }
.listtopicadmin .labeltxt { display: block; cursor: pointer; width: 100%; background: url(<?=IMGDIR?>/arrow_down.gif) no-repeat 100% 8px; }
.currentopt .labeltxt { float: left; cursor: default; width: 45px; background: none; color: #09C; }
.listtopicadmin .checkbox { margin-top: 3px; *margin-top: -2px; }
.listtopicadmin img { vertical-align: middle; }
.tah_fixiesel { overflow: hidden; white-space: nowrap; width: 217px; *border-right: 1px solid <?=INPUTBORDER?>; }
.tah_fixiesel select { width: 160px; *width: expression(this.offsetWidth > 180 ? 'auto':'180'); }