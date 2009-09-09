<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: template.func.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

function parse_template($tplfile, $templateid, $tpldir) {
	global $language, $subtemplates, $timestamp;

	$nest = 6;
	$file = basename($tplfile, '.htm');
	$objfile = DISCUZ_ROOT."./forumdata/templates/{$templateid}_$file.tpl.php";

	if(!@$fp = fopen($tplfile, 'r')) {
		dexit("Current template file './$tpldir/$file.htm' not found or have no access!");
	} elseif($language['discuz_lang'] != 'templates' && !include language('templates', $templateid, $tpldir)) {
		dexit("<br />Current template pack do not have a necessary language file 'templates.lang.php' or have syntax error!");
	}

	$template = @fread($fp, filesize($tplfile));
	fclose($fp);

	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

	$subtemplates = array();
	for($i = 1; $i<=3; $i++) {
		if(strexists($template, '{subtemplate')) {
			$template = preg_replace("/[\n\r\t]*\{subtemplate\s+([a-z0-9_]+)\}[\n\r\t]*/ies", "loadsubtemplate('\\1')", $template);
		}
	}

	$template = preg_replace("/([\n\r]+)\t+/s", "\\1", $template);
	$template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
	$template = preg_replace("/\{lang\s+(.+?)\}/ies", "languagevar('\\1')", $template);
	$template = preg_replace("/\{faq\s+(.+?)\}/ies", "faqvar('\\1')", $template);
	$template = str_replace("{LF}", "<?=\"\\n\"?>", $template);

	$template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
	$template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);

	$headeradd = '';
	if(!empty($subtemplates)) {
		$headeradd .= "\n0\n";
		foreach ($subtemplates as $fname) {
			$headeradd .= "|| checktplrefresh('$tplfile', '$fname', $timestamp, '$templateid', '$tpldir')\n";
		}
		$headeradd .=";";
	}
	
	$template = "<? if(!defined('IN_DISCUZ')) exit('Access Denied'); {$headeradd}?>\n$template";
	
	$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include template('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/is", "\n<? include template('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? \\1 ?>','')", $template);
	$template = preg_replace("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<? echo \\1; ?>','')", $template);
	$template = preg_replace("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/ies", "stripvtags('\\1<? } elseif(\\2) { ?>\\3','')", $template);
	$template = preg_replace("/([\n\r\t]*)\{else\}([\n\r\t]*)/is", "\\1<? } else { ?>\\2", $template);

	for($i = 0; $i < $nest; $i++) {
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\\3<? } } ?>')", $template);
		$template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\\4<? } } ?>')", $template);
		$template = preg_replace("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r]*)(.+?)([\n\r]*)\{\/if\}([\n\r\t]*)/ies", "stripvtags('\\1<? if(\\2) { ?>\\3','\\4\\5<? } ?>\\6')", $template);
	}

	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);

	if(!@$fp = fopen($objfile, 'w')) {
		dexit("Directory './forumdata/templates/' not found or have no access!");
	}

	$template = preg_replace("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/e", "transamp('\\0')", $template);
	$template = preg_replace("/\<script[^\>]*?src=\"(.+?)\"(.*?)\>\s*\<\/script\>/ise", "stripscriptamp('\\1', '\\2')", $template);

	$template = preg_replace("/[\n\r\t]*\{block\s+([a-zA-Z0-9_]+)\}(.+?)\{\/block\}/ies", "stripblock('\\1', '\\2')", $template);

	flock($fp, 2);
	fwrite($fp, $template);
	fclose($fp);
}

function loadsubtemplate($file, $templateid = 0, $tpldir = '') {
	global $subtemplates;
	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$tplfile = DISCUZ_ROOT.'./'.$tpldir.'/'.$file.'.htm';
	if($templateid != 1 && !file_exists($tplfile)) {
		$tplfile = DISCUZ_ROOT.'./templates/default/'.$file.'.htm';
	}
	$content = @implode('', file($tplfile));
	$subtemplates[] = $tplfile;
	return $content;
}

function transamp($str) {
	$str = str_replace('&', '&amp;', $str);
	$str = str_replace('&amp;amp;', '&amp;', $str);
	$str = str_replace('\"', '"', $str);
	return $str;
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function languagevar($var) {
	if(isset($GLOBALS['language'][$var])) {
		return $GLOBALS['language'][$var];
	} else {
		return "!$var!";
	}
}

function faqvar($var) {
	global $_DCACHE;
	include_once DISCUZ_ROOT.'./forumdata/cache/cache_faqs.php';

	if(isset($_DCACHE['faqs'][$var])) {
		return '<a href="faq.php?action=faq&id='.$_DCACHE['faqs'][$var]['fpid'].'&messageid='.$_DCACHE['faqs'][$var]['id'].'" target="_blank">'.$_DCACHE['faqs'][$var]['keyword'].'</a>';
	} else {
		return "!$var!";
	}
}

function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

function stripscriptamp($s, $extra) {
	$extra = str_replace('\\"', '"', $extra);
	$s = str_replace('&amp;', '&', $s);
	return "<script src=\"$s\" type=\"text/javascript\"$extra></script>";
}

function stripblock($var, $s) {
	$s = str_replace('\\"', '"', $s);
	$s = preg_replace("/<\?=\\\$(.+?)\?>/", "{\$\\1}", $s);
	preg_match_all("/<\?=(.+?)\?>/e", $s, $constary);
	$constadd = '';
	$constary[1] = array_unique($constary[1]);
	foreach($constary[1] as $const) {
		$constadd .= '$__'.$const.' = '.$const.';';
	}
	$s = preg_replace("/<\?=(.+?)\?>/", "{\$__\\1}", $s);
	$s = str_replace('?>', "\n\$$var .= <<<EOF\n", $s);
	$s = str_replace('<?', "\nEOF;\n", $s);
	return "<?\n$constadd\$$var = <<<EOF\n".$s."\nEOF;\n?>";
}

?>