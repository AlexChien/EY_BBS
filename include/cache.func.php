<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: cache.func.php 17540 2009-01-21 01:20:42Z cnteacher $
*/

define('DISCUZ_KERNEL_VERSION', '7.0.0');
define('DISCUZ_KERNEL_RELEASE', '20090121');


function updatecache($cachename = '') {
	global $db, $bbname, $tablepre, $maxbdays;


	static $cachescript = array
		(

		'settings'	=> array('settings'),
		'forums'	=> array('forums'),
		'icons'		=> array('icons'),
		'ranks'		=> array('ranks'),
		'usergroups'	=> array('usergroups'),
		'request'	=> array('request'),
		'medals'	=> array('medals'),
		'magics'	=> array('magics'),
		'topicadmin'	=> array('modreasons'),
		'archiver'      => array('advs_archiver'),
		'register'      => array('advs_register'),
		'faqs'		=> array('faqs'),
		'secqaa'	=> array('secqaa'),
		'censor'	=> array('censor'),
		'ipbanned'	=> array('ipbanned'),
		'smilies'	=> array('smilies_js'),

		'index'		=> array('announcements', 'onlinelist', 'forumlinks', 'advs_index'),
		'forumdisplay'	=> array('smilies', 'announcements_forum', 'globalstick', 'floatthreads', 'forums', 'icons', 'onlinelist', 'advs_forumdisplay'),
		'viewthread'	=> array('smilies', 'smileytypes', 'forums', 'usergroups', 'ranks', 'bbcodes', 'smilies', 'advs_viewthread', 'tags_viewthread', 'custominfo', 'groupicon'),
		'post'		=> array('bbcodes_display', 'bbcodes', 'smileycodes', 'smilies', 'smileytypes', 'icons'),
		'profilefields'	=> array('fields_required', 'fields_optional'),
		'viewpro'	=> array('fields_required', 'fields_optional', 'custominfo'),
		'bbcodes'	=> array('bbcodes', 'smilies', 'smileytypes'),
		);

	if($maxbdays) {
		$cachescript['birthdays'] = array('birthdays');
		$cachescript['index'][]   = 'birthdays_index';
	}

	$updatelist = empty($cachename) ? array_values($cachescript) : (is_array($cachename) ? array('0' => $cachename) : array(array('0' => $cachename)));
	$updated = array();
	foreach($updatelist as $value) {
		foreach($value as $cname) {
			if(empty($updated) || !in_array($cname, $updated)) {
				$updated[] = $cname;
				getcachearray($cname);
			}
		}
	}

	foreach($cachescript as $script => $cachenames) {
		if(empty($cachename) || (!is_array($cachename) && in_array($cachename, $cachenames)) || (is_array($cachename) && array_intersect($cachename, $cachenames))) {
			$cachedata = '';
			$query = $db->query("SELECT data FROM {$tablepre}caches WHERE cachename in(".implodeids($cachenames).")");
			while($data = $db->fetch_array($query)) {
				$cachedata .= $data['data'];
			}
			writetocache($script, $cachenames, $cachedata);
		}
	}

	if(!$cachename || $cachename == 'styles') {
		$stylevars = $styledata = $styleicons = array();
		$defaultstyleid = $db->result_first("SELECT value FROM {$tablepre}settings WHERE variable = 'styleid'");
		list(, $imagemaxwidth) = explode("\t", $db->result_first("SELECT value FROM {$tablepre}settings WHERE variable = 'zoomstatus'"));
		$imagemaxwidth = $imagemaxwidth ? $imagemaxwidth : 600;
		$imagemaxwidthint = intval($imagemaxwidth);
		$query = $db->query("SELECT sv.* FROM {$tablepre}stylevars sv LEFT JOIN {$tablepre}styles s ON s.styleid = sv.styleid AND (s.available=1 OR s.styleid='$defaultstyleid')");
		while($var = $db->fetch_array($query)) {
			$stylevars[$var['styleid']][$var['variable']] = $var['substitute'];
		}
		$query = $db->query("SELECT s.*, t.directory AS tpldir FROM {$tablepre}styles s LEFT JOIN {$tablepre}templates t ON s.templateid=t.templateid WHERE s.available=1 OR s.styleid='$defaultstyleid'");
		while($data = $db->fetch_array($query)) {
			$data = array_merge($data, $stylevars[$data['styleid']]);
			$datanew = array();
			$data['imgdir'] = $data['imgdir'] ? $data['imgdir'] : 'images/default';
			$data['styleimgdir'] = $data['styleimgdir'] ? $data['styleimgdir'] : $data['imgdir'];
			foreach($data as $k => $v) {
				if(substr($k, -7, 7) == 'bgcolor') {
					$newkey = substr($k, 0, -7).'bgcode';
					$datanew[$newkey] = setcssbackground($data, $k);
				}
			}
			$data = array_merge($data, $datanew);
			$styleicons[$data['styleid']] = $data['menuhover'];
			if(strstr($data['boardimg'], ',')) {
				$flash = explode(",", $data['boardimg']);
				$flash[0] = trim($flash[0]);
				$flash[0] = preg_match('/^http:\/\//i', $flash[0]) ? $flash[0] : $data['styleimgdir'].'/'.$flash[0];
				$data['boardlogo'] = "<embed src=\"".$flash[0]."\" width=\"".trim($flash[1])."\" height=\"".trim($flash[2])."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\"></embed>";
			} else {
				$data['boardimg'] = preg_match('/^http:\/\//i', $data['boardimg']) ? $data['boardimg'] : $data['styleimgdir'].'/'.$data['boardimg'];
				$data['boardlogo'] = "<img src=\"$data[boardimg]\" alt=\"$bbname\" border=\"0\" />";
			}
			$data['bold'] = $data['nobold'] ? 'normal' : 'bold';
			$contentwidthint = intval($data['contentwidth']);
			$contentwidthint = $contentwidthint ? $contentwidthint : 600;
			if(substr(trim($data['contentwidth']), -1, 1) != '%') {
				if(substr(trim($imagemaxwidth), -1, 1) != '%') {
					$data['imagemaxwidth'] = $imagemaxwidthint > $contentwidthint ? $contentwidthint : $imagemaxwidthint;
				} else {
					$data['imagemaxwidth'] = intval($contentwidthint * $imagemaxwidthint / 100);
				}
			} else {
				if(substr(trim($imagemaxwidth), -1, 1) != '%') {
					$data['imagemaxwidth'] = '%'.$imagemaxwidthint;
				} else {
					$data['imagemaxwidth'] = ($imagemaxwidthint > $contentwidthint ? $contentwidthint : $imagemaxwidthint).'%';
				}
			}
			$data['verhash'] = random(3);
			$styledata[] = $data;
		}
		foreach($styledata as $data) {
			$data['styleicons'] = $styleicons;
			writetocache($data['styleid'], '', getcachevars($data, 'CONST'), 'style_');
			writetocsscache($data);
		}
	}

	if(!$cachename || $cachename == 'usergroups') {
		$query = $db->query("SELECT * FROM {$tablepre}usergroups u
					LEFT JOIN {$tablepre}admingroups a ON u.groupid=a.admingid");
		while($data = $db->fetch_array($query)) {
			$ratearray = array();
			if($data['raterange']) {
				foreach(explode("\n", $data['raterange']) as $rating) {
					$rating = explode("\t", $rating);
					$ratearray[$rating[0]] = array('min' => $rating[1], 'max' => $rating[2], 'mrpd' => $rating[3]);
				}
			}
			$data['raterange'] = $ratearray;
			$data['grouptitle'] = $data['color'] ? '<font color="'.$data['color'].'">'.$data['grouptitle'].'</font>' : $data['grouptitle'];
			$data['grouptype'] = $data['type'];
			$data['grouppublic'] = $data['system'] != 'private';
			$data['groupcreditshigher'] = $data['creditshigher'];
			$data['groupcreditslower'] = $data['creditslower'];
			unset($data['type'], $data['system'], $data['creditshigher'], $data['creditslower'], $data['color'], $data['groupavatar'], $data['admingid']);
			writetocache($data['groupid'], '', getcachevars($data), 'usergroup_');
		}
	}


	if(!$cachename || $cachename == 'admingroups') {
		$query = $db->query("SELECT * FROM {$tablepre}admingroups");
		while($data = $db->fetch_array($query)) {
			writetocache($data['admingid'], '', getcachevars($data), 'admingroup_');
		}
	}

	if(!$cachename || $cachename == 'plugins') {
		$query = $db->query("SELECT pluginid, available, adminid, name, identifier, datatables, directory, copyright, modules FROM {$tablepre}plugins");
		while($plugin = $db->fetch_array($query)) {
			$data = array_merge($plugin, array('modules' => array()), array('vars' => array()));
			$plugin['modules'] = unserialize($plugin['modules']);
			if(is_array($plugin['modules'])) {
				foreach($plugin['modules'] as $module) {
					$data['modules'][$module['name']] = $module;
				}
			}
			$queryvars = $db->query("SELECT variable, value FROM {$tablepre}pluginvars WHERE pluginid='$plugin[pluginid]'");
			while($var = $db->fetch_array($queryvars)) {
				$data['vars'][$var['variable']] = $var['value'];
			}
			writetocache($plugin['identifier'], '', "\$_DPLUGIN['$plugin[identifier]'] = ".arrayeval($data), 'plugin_');
		}
	}

	if(!$cachename || $cachename == 'threadsorts') {
		$sortlist = $templatedata = array();
		$query = $db->query("SELECT t.typeid AS sortid, tt.optionid, tt.title, tt.type, tt.rules, tt.identifier, tt.description, tv.required, tv.unchangeable, tv.search
			FROM {$tablepre}threadtypes t
			LEFT JOIN {$tablepre}typevars tv ON t.typeid=tv.sortid
			LEFT JOIN {$tablepre}typeoptions tt ON tv.optionid=tt.optionid
			WHERE t.special='1' AND tv.available='1'
			ORDER BY tv.displayorder");
		while($data = $db->fetch_array($query)) {
			$data['rules'] = unserialize($data['rules']);
			$sortid = $data['sortid'];
			$optionid = $data['optionid'];
			$sortlist[$sortid][$optionid] = array(
				'title' => dhtmlspecialchars($data['title']),
				'type' => dhtmlspecialchars($data['type']),
				'identifier' => dhtmlspecialchars($data['identifier']),
				'description' => dhtmlspecialchars($data['description']),
				'required' => intval($data['required']),
				'unchangeable' => intval($data['unchangeable']),
				'search' => intval($data['search'])
				);

			if(in_array($data['type'], array('select', 'checkbox', 'radio'))) {
				if($data['rules']['choices']) {
					$choices = array();
					foreach(explode("\n", $data['rules']['choices']) as $item) {
						list($index, $choice) = explode('=', $item);
						$choices[trim($index)] = trim($choice);
					}
					$sortlist[$sortid][$optionid]['choices'] = $choices;
				} else {
					$typelist[$sortid][$optionid]['choices'] = array();
				}
			} elseif(in_array($data['type'], array('text', 'textarea'))) {
				$sortlist[$sortid][$optionid]['maxlength'] = intval($data['rules']['maxlength']);
			} elseif($data['type'] == 'image') {
				$sortlist[$sortid][$optionid]['maxwidth'] = intval($data['rules']['maxwidth']);
				$sortlist[$sortid][$optionid]['maxheight'] = intval($data['rules']['maxheight']);
			} elseif($data['type'] == 'number') {
				$sortlist[$sortid][$optionid]['maxnum'] = intval($data['rules']['maxnum']);
				$sortlist[$sortid][$optionid]['minnum'] = intval($data['rules']['minnum']);
			}
		}
		$query = $db->query("SELECT typeid, description, template FROM {$tablepre}threadtypes WHERE special='1'");
		while($data = $db->fetch_array($query)) {
			$templatedata[$data['typeid']] = $data['template'];
			$threaddesc[$data['typeid']] = dhtmlspecialchars($data['description']);
		}

		foreach($sortlist as $sortid => $option) {
			writetocache($sortid, '', "\$_DTYPE = ".arrayeval($option).";\n\n\$_DTYPETEMPLATE = \"".str_replace('"', '\"', $templatedata[$sortid])."\";\n", 'threadsort_');
		}
	}

}

function setcssbackground(&$data, $code) {
	$codes = explode(' ', $data[$code]);
	$css = $codevalue = '';
	for($i = 0; $i < count($codes); $i++) {
		if($i < 2) {
			if($codes[$i] != '') {
				if($codes[$i]{0} == '#') {
					$css .= strtoupper($codes[$i]).' ';
					$codevalue = strtoupper($codes[$i]);
				} elseif(preg_match('/^http:\/\//i', $codes[$i])) {
					$css .= 'url(\"'.$codes[$i].'\") ';
				} else {
					$css .= 'url("'.$data['styleimgdir'].'/'.$codes[$i].'") ';
				}
			}
		} else {
			$css .= $codes[$i].' ';
		}
	}
	$data[$code] = $codevalue;
	$css = trim($css);
	return $css ? 'background: '.$css : '';
}

function updatesettings() {
	global $_DCACHE;
	if(isset($_DCACHE['settings']) && is_array($_DCACHE['settings'])) {
		writetocache('settings', '', '$_DCACHE[\'settings\'] = '.arrayeval($_DCACHE['settings']).";\n\n");
	}
}

function writetocache($script, $cachenames, $cachedata = '', $prefix = 'cache_') {
	global $authkey;
	if(is_array($cachenames) && !$cachedata) {
		foreach($cachenames as $name) {
			$cachedata .= getcachearray($name, $script);
		}
	}

	$dir = DISCUZ_ROOT.'./forumdata/cache/';
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if($fp = @fopen("$dir$prefix$script.php", 'wb')) {
		fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!".
			"\n//Created: ".date("M j, Y, G:i").
			"\n//Identify: ".md5($prefix.$script.'.php'.$cachedata.$authkey)."\n\n$cachedata?>");
		fclose($fp);
	} else {
		exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
	}
}

function writetocsscache($data) {
	$cssdata = '';
	foreach(array('_common' => array('css_common', 'css_append', 'css_editor', 'css_editor_append'),
			'_editor' => array('css_editor', 'css_editor_append'),
			'_seditor' => array('css_seditor', 'css_seditor_append'),
			'_viewthread' => array('css_viewthread', 'css_viewthread_append'),
			'_calendar' => array('css_calendar', 'css_calendar_append'),
			'_float' => array('css_float', 'css_float_append')
		) as $extra => $cssfiles) {
		$cssdata = '';
		foreach($cssfiles as $css) {
			$cssfile = DISCUZ_ROOT.'./'.$data['tpldir'].'/'.$css.'.htm';
			!file_exists($cssfile) && $cssfile = DISCUZ_ROOT.'./templates/default/'.$css.'.htm';
			if(file_exists($cssfile)) {
				$fp = fopen($cssfile, 'r');
				$cssdata .= @fread($fp, filesize($cssfile))."\n\n";
				fclose($fp);
			}
		}
		$cssdata = preg_replace("/\{([A-Z0-9]+)\}/e", '\$data[strtolower(\'\1\')]', $cssdata);
		$cssdata = preg_replace("/<\?.+?\?>\s*/", '', $cssdata);
		$cssdata = !preg_match('/^http:\/\//i', $data['styleimgdir']) ? str_replace("url(\"$data[styleimgdir]", "url(\"../../$data[styleimgdir]", $cssdata) : $cssdata;
		$cssdata = !preg_match('/^http:\/\//i', $data['styleimgdir']) ? str_replace("url($data[styleimgdir]", "url(../../$data[styleimgdir]", $cssdata) : $cssdata;
		$cssdata = !preg_match('/^http:\/\//i', $data['imgdir']) ? str_replace("url(\"$data[imgdir]", "url(\"../../$data[imgdir]", $cssdata) : $cssdata;
		$cssdata = !preg_match('/^http:\/\//i', $data['imgdir']) ? str_replace("url($data[imgdir]", "url(../../$data[imgdir]", $cssdata) : $cssdata;
		$cssdata = preg_replace(array('/\s*([,;:\{\}])\s*/', '/[\t\n\r]/', '/\/\*.+?\*\//'), array('\\1', '',''), $cssdata);
		if(@$fp = fopen(DISCUZ_ROOT.'./forumdata/cache/style_'.$data['styleid'].$extra.'.css', 'w')) {
			fwrite($fp, $cssdata);
			fclose($fp);
		} else {
			exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
		}
	}
}

function getcachearray($cachename, $script = '') {
	global $db, $timestamp, $tablepre, $timeoffset, $maxbdays, $smcols, $smrows, $charset;

	$cols = '*';
	$conditions = '';
	switch($cachename) {
		case 'settings':
			$table = 'settings';
			$conditions = "WHERE variable NOT IN ('siteuniqueid', 'mastermobile', 'bbrules', 'bbrulestxt', 'closedreason', 'creditsnotify', 'backupdir', 'custombackup', 'jswizard', 'maxonlines', 'modreasons', 'newsletter', 'welcomemsg', 'welcomemsgtxt', 'postno', 'postnocustom', 'customauthorinfo')";
			break;
		case 'custominfo':
			$table = 'settings';
			$conditions = "WHERE variable IN ('extcredits', 'customauthorinfo', 'postno', 'postnocustom')";
			break;
		case 'request':
			$table = 'request';
			$conditions = '';
			break;
		case 'usergroups':
			$table = 'usergroups';
			$cols = 'groupid, type, grouptitle, creditshigher, creditslower, stars, color, groupavatar, readaccess, allowcusbbcode';
			$conditions = "ORDER BY creditslower";
			break;
		case 'ranks':
			$table = 'ranks';
			$cols = 'ranktitle, postshigher, stars, color';
			$conditions = "ORDER BY postshigher DESC";
			break;
		case 'announcements':
			$table = 'announcements';
			$cols = 'id, subject, type, starttime, endtime, displayorder, groups, message';
			$conditions = "WHERE starttime<='$timestamp' AND (endtime>='$timestamp' OR endtime='0') ORDER BY displayorder, starttime DESC, id DESC";
			break;
		case 'announcements_forum':
			$table = 'announcements a';
			$cols = 'a.id, a.author, m.uid AS authorid, a.subject, a.message, a.type, a.starttime, a.displayorder';
			$conditions = "LEFT JOIN {$tablepre}members m ON m.username=a.author WHERE a.type!=2 AND a.groups = '' AND a.starttime<='$timestamp' ORDER BY a.displayorder, a.starttime DESC, a.id DESC LIMIT 1";
			break;
		case in_array($cachename, array('globalstick', 'floatthreads')):
			$table = 'forums';
			$cols = 'fid, type, fup';
			$conditions = "WHERE status>0 AND type IN ('forum', 'sub') ORDER BY type";
			break;
		case 'forums':
			$table = 'forums f';
			$cols = 'f.fid, f.type, f.name, f.fup, f.simple, ff.viewperm, ff.formulaperm, a.uid';
			$conditions = "LEFT JOIN {$tablepre}forumfields ff ON ff.fid=f.fid LEFT JOIN {$tablepre}access a ON a.fid=f.fid AND a.allowview>'0' WHERE f.status>0 ORDER BY f.type, f.displayorder";
			break;
		case 'onlinelist':
			$table = 'onlinelist';
			$conditions = "ORDER BY displayorder";
			break;
		case 'groupicon':
			$table = 'onlinelist';
			$conditions = "ORDER BY displayorder";
			break;
		case 'forumlinks':
			$table = 'forumlinks';
			$conditions = "ORDER BY displayorder";
			break;
		case 'bbcodes':
			$table = 'bbcodes';
			$conditions = "WHERE available>'0' AND type='0'";
			break;
		case 'bbcodes_display':
			$table = 'bbcodes';
			$cols = 'type, tag, icon, explanation, params, prompt';
			$conditions = "WHERE available='2' AND icon!='' ORDER BY displayorder";
			break;
		case 'smilies':
			$table = 'smilies s';
			$cols = 's.id, s.code, s.url, t.typeid';
			$conditions = "LEFT JOIN {$tablepre}imagetypes t ON t.typeid=s.typeid WHERE s.type='smiley' AND s.code<>'' AND t.available='1' ORDER BY LENGTH(s.code) DESC";
			break;
		case 'smileycodes':
			$table = 'imagetypes';
			$cols = 'typeid, directory';
			$conditions = "WHERE type='smiley' AND available='1' ORDER BY displayorder";
			break;
		case 'smileytypes':
			$table = 'imagetypes';
			$cols = 'typeid, name, directory';
			$conditions = "WHERE type='smiley' AND available='1' ORDER BY displayorder";
			break;
		case 'smilies_js':
			$table = 'imagetypes';
			$cols = 'typeid, name, directory';
			$conditions = "WHERE type='smiley' AND available='1' ORDER BY displayorder";
			break;
		case 'icons':
			$table = 'smilies';
			$cols = 'id, url';
			$conditions = "WHERE type='icon' ORDER BY displayorder";
			break;
		case 'fields_required':
			$table = 'profilefields';
			$cols = 'fieldid, invisible, title, description, required, unchangeable, selective, choices';
			$conditions = "WHERE available='1' AND required='1' ORDER BY displayorder";
			break;
		case 'fields_optional':
			$table = 'profilefields';
			$cols = 'fieldid, invisible, title, description, required, unchangeable, selective, choices';
			$conditions = "WHERE available='1' AND required='0' ORDER BY displayorder";
			break;
		case 'ipbanned':
			$db->query("DELETE FROM {$tablepre}banned WHERE expiration<'$timestamp'");
			$table = 'banned';
			$cols = 'ip1, ip2, ip3, ip4, expiration';
			break;
		case 'censor':
			$table = 'words';
			$cols = 'find, replacement';
			break;
		case 'medals':
			$table = 'medals';
			$cols = 'medalid, name, image';
			$conditions = "WHERE available='1'";
			break;
		case 'magics':
			$table = 'magics';
			$cols = 'magicid, available, identifier, name, description, weight, price';
			break;
		case 'birthdays_index':
			$table = 'members';
			$cols = 'uid, username, email, bday';
			$conditions = "WHERE RIGHT(bday, 5)='".gmdate('m-d', $timestamp + $timeoffset * 3600)."' ORDER BY bday LIMIT $maxbdays";
			break;
		case 'birthdays':
			$table = 'members';
			$cols = 'uid';
			$conditions = "WHERE RIGHT(bday, 5)='".gmdate('m-d', $timestamp + $timeoffset * 3600)."' ORDER BY bday";
			break;
		case 'modreasons':
			$table = 'settings';
			$cols = 'value';
			$conditions = "WHERE variable='modreasons'";
			break;
		case 'faqs':
			$table = 'faqs';
			$cols = 'fpid, id, identifier, keyword';
			$conditions = "WHERE identifier!='' AND keyword!=''";
			break;
		case 'tags_viewthread':
			global $viewthreadtags;
			$taglimit = intval($viewthreadtags);
			$table = 'tags';
			$cols = 'tagname, total';
			$conditions = "WHERE closed=0 ORDER BY total DESC LIMIT $taglimit";
			break;
	}

	$data = array();
	if(!in_array($cachename, array('secqaa')) && substr($cachename, 0, 5) != 'advs_') {
		if(empty($table) || empty($cols)) return '';
		$query = $db->query("SELECT $cols FROM {$tablepre}$table $conditions");
	}
	switch($cachename) {
		case 'settings':
			while($setting = $db->fetch_array($query)) {
				if($setting['variable'] == 'extcredits') {
					if(is_array($setting['value'] = unserialize($setting['value']))) {
						foreach($setting['value'] as $key => $value) {
							if($value['available']) {
								unset($setting['value'][$key]['available']);
							} else {
								unset($setting['value'][$key]);
							}
						}
					}
				} elseif($setting['variable'] == 'creditsformula') {
					if(!preg_match("/^([\+\-\*\/\.\d\(\)]|((extcredits[1-8]|digestposts|posts|pageviews|oltime)([\+\-\*\/\(\)]|$)+))+$/", $setting['value']) || !is_null(@eval(preg_replace("/(digestposts|posts|pageviews|oltime|extcredits[1-8])/", "\$\\1", $setting['value']).';'))) {
						$setting['value'] = '$member[\'extcredits1\']';
					} else {
						$setting['value'] = preg_replace("/(digestposts|posts|pageviews|oltime|extcredits[1-8])/", "\$member['\\1']", $setting['value']);
					}
				} elseif($setting['variable'] == 'maxsmilies') {
					$setting['value'] = $setting['value'] <= 0 ? -1 : $setting['value'];
				} elseif($setting['variable'] == 'threadsticky') {
					$setting['value'] = explode(',', $setting['value']);
				} elseif($setting['variable'] == 'attachdir') {
					$setting['value'] = preg_replace("/\.asp|\\0/i", '0', $setting['value']);
					$setting['value'] = str_replace('\\', '/', substr($setting['value'], 0, 2) == './' ? DISCUZ_ROOT.$setting['value'] : $setting['value']);
				} elseif($setting['variable'] == 'onlinehold') {
					$setting['value'] = $setting['value'] * 60;
				} elseif($setting['variable'] == 'userdateformat') {
					if(empty($setting['value'])) {
						$setting['value'] = array();
					} else {
						$setting['value'] = dhtmlspecialchars(explode("\n", $setting['value']));
						$setting['value'] = array_map('trim', $setting['value']);
					}
				} elseif(in_array($setting['variable'], array('creditspolicy', 'ftp', 'secqaa', 'ec_credit', 'qihoo', 'insenz', 'spacedata', 'infosidestatus', 'uc', 'outextcredits', 'relatedtag', 'sitemessage', 'msn', 'uchome'))) {
					$setting['value'] = unserialize($setting['value']);
				}
				$GLOBALS[$setting['variable']] = $data[$setting['variable']] = $setting['value'];
			}

			$data['sitemessage']['time'] = !empty($data['sitemessage']['time']) ? $data['sitemessage']['time'] * 1000 : 0;
			$data['sitemessage']['register'] = !empty($data['sitemessage']['register']) ? explode("\n", $data['sitemessage']['register']) : '';
			$data['sitemessage']['login'] = !empty($data['sitemessage']['login']) ? explode("\n", $data['sitemessage']['login']) : '';
			$data['sitemessage']['newthread'] = !empty($data['sitemessage']['newthread']) ? explode("\n", $data['sitemessage']['newthread']) : '';
			$data['sitemessage']['reply'] = !empty($data['sitemessage']['reply']) ? explode("\n", $data['sitemessage']['reply']) : '';
			$GLOBALS['version'] = $data['version'] = DISCUZ_KERNEL_VERSION;
			$GLOBALS['totalmembers'] = $data['totalmembers'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}members");
			$GLOBALS['lastmember'] = $data['lastmember'] = $db->result_first("SELECT username FROM {$tablepre}members ORDER BY uid DESC LIMIT 1");
			$data['cachethreadon'] = $db->result_first("SELECT COUNT(*) FROM {$tablepre}forums WHERE status>0 AND threadcaches>0") ? 1 : 0;
			$data['cronnextrun'] = $db->result_first("SELECT nextrun FROM {$tablepre}crons WHERE available>'0' AND nextrun>'0' ORDER BY nextrun LIMIT 1");

			$data['ftp']['connid'] = 0;
			$data['indexname'] = empty($data['indexname']) ? 'index.php' : $data['indexname'];
			if(!$data['imagelib']) {
				unset($data['imageimpath']);
			}

			if(is_array($data['relatedtag']['order'])) {
				asort($data['relatedtag']['order']);
				$relatedtag = array();
				foreach($data['relatedtag']['order'] AS $k => $v) {
					$relatedtag['status'][$k] = $data['relatedtag']['status'][$k];
					$relatedtag['name'][$k] = $data['relatedtag']['name'][$k];
					$relatedtag['limit'][$k] = $data['relatedtag']['limit'][$k];
					$relatedtag['template'][$k] = $data['relatedtag']['template'][$k];
				}
				$data['relatedtag'] = $relatedtag;

				foreach((array)$data['relatedtag']['status'] AS $appid => $status) {
					if(!$status) {
						unset($data['relatedtag']['limit'][$appid]);
					}
				}
				unset($data['relatedtag']['status'], $data['relatedtag']['order'], $relatedtag);
			}

			$data['seccodedata'] = $data['seccodedata'] ? unserialize($data['seccodedata']) : array();
			if($data['seccodedata']['type'] == 2) {
				if(extension_loaded('ming')) {
					unset($data['seccodedata']['background'], $data['seccodedata']['adulterate'],
						$data['seccodedata']['ttf'], $data['seccodedata']['angle'],
						$data['seccodedata']['color'], $data['seccodedata']['size'],
						$data['seccodedata']['animator']);
				} else {
					$data['seccodedata']['animator'] = 0;
				}
			}

			$secqaacheck = sprintf('%03b', $data['secqaa']['status']);
			$data['secqaa']['status'] = array(
				1 => $secqaacheck{2},
				2 => $secqaacheck{1},
				3 => $secqaacheck{0}
			);
			if(!$data['secqaa']['status'][2] && !$data['secqaa']['status'][3]) {
				unset($data['secqaa']['minposts']);
			}

			if($data['watermarktype'] == 2 && $data['watermarktext']) {
				$data['watermarktext'] = unserialize($data['watermarktext']);
				if($data['watermarktext']['text'] && strtoupper($charset) != 'UTF-8') {
					require_once DISCUZ_ROOT.'include/chinese.class.php';
					$c = new Chinese($charset, 'utf8');
					$data['watermarktext']['text'] = $c->Convert($data['watermarktext']['text']);
				}
				$data['watermarktext']['text'] = bin2hex($data['watermarktext']['text']);
				$data['watermarktext']['fontpath'] = 'images/fonts/'.$data['watermarktext']['fontpath'];
				$data['watermarktext']['color'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", $data['watermarktext']['color']);
				$data['watermarktext']['shadowcolor'] = preg_replace('/#?([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/e', "hexdec('\\1').','.hexdec('\\2').','.hexdec('\\3')", $data['watermarktext']['shadowcolor']);
			} else {
				$data['watermarktext'] = array();
			}

			$tradetypes = implodeids(unserialize($data['tradetypes']));
			$data['tradetypes'] = array();
			if($tradetypes) {
				$query = $db->query("SELECT typeid, name FROM {$tablepre}threadtypes WHERE typeid in ($tradetypes)");
				while($type = $db->fetch_array($query)) {
					$data['tradetypes'][$type['typeid']] = $type['name'];
				}
			}

			$data['styles'] = array();
			$query = $db->query("SELECT styleid, name FROM {$tablepre}styles WHERE available='1'");
			while($style = $db->fetch_array($query)) {
				$data['styles'][$style['styleid']] = dhtmlspecialchars($style['name']);
			}
			$data['stylejumpstatus'] = $data['stylejump'] && count($data['styles']) > 1;

			$globaladvs = advertisement('all');
			$data['globaladvs'] = $globaladvs['all'] ? $globaladvs['all'] : array();
			$data['redirectadvs'] = $globaladvs['redirect'] ? $globaladvs['redirect'] : array();

			$data['invitecredit'] = '';
			if($data['inviteconfig'] = unserialize($data['inviteconfig'])) {
				$data['invitecredit'] = $data['inviteconfig']['invitecredit'];
			}
			unset($data['inviteconfig']);

			$data['videoopen'] = $data['videotype'] = $data['vsiteid'] = $data['vkey'] = $data['vsiteurl'] = '';
			if($data['videoinfo'] = unserialize($data['videoinfo'])) {
				$data['videoopen'] = intval($data['videoinfo']['open']);
				$data['videotype'] = explode("\n", $data['videoinfo']['vtype']);
				$data['vsiteid'] = $data['videoinfo']['siteid'];
				$data['vkey'] = $data['videoinfo']['authkey'];
				$data['vsiteurl'] = $data['videoinfo']['url'];
			}
			unset($data['videoinfo']);

			$outextcreditsrcs = $outextcredits = array();
			foreach((array)$data['outextcredits'] as $value) {
				$outextcreditsrcs[$value['creditsrc']] = $value['creditsrc'];
				$key = $value['appiddesc'].'|'.$value['creditdesc'];
				if(!isset($outextcredits[$key])) {
					$outextcredits[$key] = array('title' => $value['title'], 'unit' => $value['unit']);
				}
				$outextcredits[$key]['ratiosrc'][$value['creditsrc']] = $value['ratiosrc'];
				$outextcredits[$key]['ratiodesc'][$value['creditsrc']] = $value['ratiodesc'];
				$outextcredits[$key]['creditsrc'][$value['creditsrc']] = $value['ratio'];
			}
			$data['outextcredits'] = $outextcredits;

			$exchcredits = array();
			$allowexchangein = $allowexchangeout = FALSE;
			foreach((array)$data['extcredits'] as $id => $credit) {
				$data['extcredits'][$id]['img'] = $credit['img'] ? '<img style="vertical-align:middle" src="'.$credit['img'].'" />' : '';
				if(!empty($credit['ratio'])) {
					$exchcredits[$id] = $credit;
					$credit['allowexchangein'] && $allowexchangein = TRUE;
					$credit['allowexchangeout'] && $allowexchangeout = TRUE;
				}
				$data['creditnotice'] && $data['creditnames'][] = str_replace("'", "\'", htmlspecialchars($id.'|'.$credit['title'].'|'.$credit['unit']));
			}
			$data['creditnames'] = $data['creditnotice'] ? implode(',', $data['creditnames']) : '';

			$creditstranssi = explode(',', $data['creditstrans']);
			$data['creditstrans'] = $creditstranssi[0];
			unset($creditstranssi[0]);
			$data['creditstransextra'] = $creditstranssi;
			for($i = 1;$i < 5;$i++) {
				$data['creditstransextra'][$i] = !$data['creditstransextra'][$i] ? $data['creditstrans'] : $data['creditstransextra'][$i];
			}
			$data['exchangestatus'] = $allowexchangein && $allowexchangeout;
			$data['transferstatus'] = isset($data['extcredits'][$data['creditstrans']]);

			list($data['zoomstatus']) = explode("\t", $data['zoomstatus']);

			if($data['insenz']['status'] && $data['insenz']['authkey']) {
				$insenz = $data['insenz'];
				$softadstatus = intval($insenz['softadstatus']);
				$hardadstatus = is_array($insenz['hardadstatus']) && $insenz['jsurl'] ? implode(',', $insenz['hardadstatus']) : '';
				$relatedadstatus = intval($insenz['relatedadstatus']);
				$insenz_cronnextrun = intval($db->result_first("SELECT nextrun FROM {$tablepre}campaigns ORDER BY nextrun LIMIT 1"));

				if(!$softadstatus && !$hardadstatus && !$relatedadstatus && !$insenz['virtualforumstatus'] && !$insenz_cronnextrun) {
					$data['insenz']['status'] = $data['insenz']['cronnextrun'] = 0;
					$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('insenz', '".addslashes(serialize($insenz))."')");
					$data['insenz'] = array();
				} else {
					$vfstatus = 0;
					if($insenz['virtualforumstatus']) {
						$vfstatus = $db->result_first("SELECT COUNT(*) FROM {$tablepre}virtualforums WHERE status=1 AND type='forum'");
					}
					$data['insenz'] = array(
						'siteid' => $insenz['siteid'],
						'uid' => intval($insenz['uid']),
						'username' => addslashes($insenz['username']),
						'hardadstatus' => $hardadstatus,
						'vfstatus' => $vfstatus,
						'vfpos' => in_array($insenz['vfpos'], array('first', 'rand', 'last')) ? $insenz['vfpos'] : 'first',
						'topicrelatedad' => $relatedadstatus && $insenz['topicrelatedad'] ? $insenz['topicrelatedad'] : '',
						'traderelatedad' => $relatedadstatus && $insenz['traderelatedad'] ? $insenz['traderelatedad'] : '',
						'relatedtrades' => $relatedadstatus && $insenz['traderelatedad'] && $insenz['relatedtrades'] ? $insenz['relatedtrades'] : '',
						'cronnextrun' => $insenz_cronnextrun,
						'statsnextrun' => intval($insenz['statsnextrun']),
						'jsurl' => $insenz['jsurl'],
						'hash' => $insenz['hash']
					);
				}
			} else {
				$data['insenz'] = array();
			}

			$data['msn']['on'] = $data['msn']['on'] && $data['msn']['domain'] ? 1 : 0;
			$data['msn']['domain'] = $data['msn']['on'] ? $data['msn']['domain'] : 'discuz.org';

			if($data['qihoo']['status']) {
				$qihoo = $data['qihoo'];
				$data['qihoo']['links'] = $data['qihoo']['relate'] = array();
				foreach(explode("\n", trim($qihoo['keywords'])) AS $keyword) {
					if($keyword = trim($keyword)) {
						$data['qihoo']['links']['keywords'][] = '<a href="search.php?srchtype=qihoo&amp;srchtxt='.rawurlencode($keyword).'&amp;searchsubmit=yes" target="_blank">'.dhtmlspecialchars(trim($keyword)).'</a>';
					}
				}
				foreach((array)$qihoo['topics'] AS $topic) {
					if($topic['topic'] = trim($topic['topic'])) {
						$data['qihoo']['links']['topics'][] = '<a href="topic.php?topic='.rawurlencode($topic['topic']).'&amp;keyword='.rawurlencode($topic['keyword']).'&amp;stype='.$topic['stype'].'&amp;length='.$topic['length'].'&amp;relate='.$topic['relate'].'" target="_blank">'.dhtmlspecialchars(trim($topic['topic'])).'</a>';
					}
				}
				if(is_array($qihoo['relatedthreads'])) {
					if($data['qihoo']['relate']['bbsnum'] = intval($qihoo['relatedthreads']['bbsnum'])) {
						$data['qihoo']['relate']['position'] = intval($qihoo['relatedthreads']['position']);
						$data['qihoo']['relate']['validity'] = intval($qihoo['relatedthreads']['validity']);
						if($data['qihoo']['relate']['webnum'] = intval($qihoo['relatedthreads']['webnum'])) {
							$data['qihoo']['relate']['banurl'] = $qihoo['relatedthreads']['banurl'] ? '/('.str_replace("\r\n", '|', $qihoo['relatedthreads']['banurl']).')/i' : '';
							$data['qihoo']['relate']['type'] = implode('|', (array)$qihoo['relatedthreads']['type']);
							$data['qihoo']['relate']['order'] = intval($qihoo['relatedthreads']['order']);
						}
					} else {
						$data['qihoo']['relate'] = array();
					}
				}
				unset($qihoo, $data['qihoo']['keywords'], $data['qihoo']['topics'], $data['qihoo']['relatedthreads']);
			} else {
				$data['qihoo'] = array();
			}

			$data['plugins'] = $data['pluginlinks'] = array();
			$query = $db->query("SELECT available, name, identifier, directory, datatables, modules FROM {$tablepre}plugins");
			while($plugin = $db->fetch_array($query)) {
				$plugin['modules'] = unserialize($plugin['modules']);
				if(is_array($plugin['modules'])) {
					foreach($plugin['modules'] as $module) {
						if($plugin['available'] && isset($module['name'])) {

							switch($module['type']) {
								case 1:
									$data['plugins']['links'][] = array('displayorder' => $module['displayorder'], 'adminid' => $module['adminid'], 'url' => "<a href=\"$module[url]\">$module[menu]</a>");
									break;
								case 2:
									$data['plugins']['links'][] = array('displayorder' => $module['displayorder'], 'adminid' => $module['adminid'], 'url' => "<a href=\"plugin.php?identifier=$plugin[identifier]&module=$module[name]\">$module[menu]</a>");
									$data['pluginlinks'][$plugin['identifier']][$module['name']] = array('adminid' => $module['adminid'], 'directory' => $plugin['directory']);
									break;
								case 4:
									$data['plugins']['include'][] = array('displayorder' => $module['displayorder'], 'adminid' => $module['adminid'], 'script' => $plugin['directory'].$module['name']);
									break;
								case 5:
									$data['plugins']['jsmenu'][] = array('displayorder' => $module['displayorder'], 'adminid' => $module['adminid'], 'url' => "<a href=\"$module[url]\">$module[menu]</a>");
									break;
								case 6:
									$data['plugins']['jsmenu'][] = array('displayorder' => $module['displayorder'], 'adminid' => $module['adminid'], 'url' => "<a href=\"plugin.php?identifier=$plugin[identifier]&module=$module[name]\">$module[menu]</a>");
									$data['pluginlinks'][$plugin['identifier']][$module['name']] = array('adminid' => $module['adminid'], 'directory' => $plugin['directory']);
									break;
							}
						}
					}
				}
			}

			$data['tradeopen'] = $db->result_first("SELECT count(*) FROM {$tablepre}usergroups WHERE allowposttrade='1'") ? 1 : 0;

			if(is_array($data['plugins']['links'])) {
				usort($data['plugins']['links'], 'pluginmodulecmp');
				foreach($data['plugins']['links'] as $key => $module) {
					unset($data['plugins']['links'][$key]['displayorder']);
				}
			}
			if(is_array($data['plugins']['include'])) {
				usort($data['plugins']['include'], 'pluginmodulecmp');
				foreach($data['plugins']['include'] as $key => $module) {
					unset($data['plugins']['include'][$key]['displayorder']);
				}
			}
			if(is_array($data['plugins']['jsmenu'])) {
				usort($data['plugins']['jsmenu'], 'pluginmodulecmp');
				foreach($data['plugins']['jsmenu'] as $key => $module) {
					unset($data['plugins']['jsmenu'][$key]['displayorder']);
				}
			}

			$data['hooks'] = array();
			$query = $db->query("SELECT ph.title, ph.code, p.identifier FROM {$tablepre}plugins p
				LEFT JOIN {$tablepre}pluginhooks ph ON ph.pluginid=p.pluginid AND ph.available='1'
				WHERE p.available='1' ORDER BY p.identifier");
			while($hook = $db->fetch_array($query)) {
				if($hook['title'] && $hook['code']) {
					$data['hooks'][$hook['identifier'].'_'.$hook['title']] = $hook['code'];
				}
			}

			$data['navs'] = $data['subnavs'] = $data['navmns'] = array();
			list($mnid) = explode('.', basename($data['indexname']));
			$data['navmns'][] = $mnid;$mngsid = 1;
			$query = $db->query("SELECT * FROM {$tablepre}navs WHERE available='1' AND parentid='0' ORDER BY displayorder");
			while($nav = $db->fetch_array($query)) {
				if($nav['type'] == '0' && (($nav['url'] == 'member.php?action=list' && !$data['memliststatus']) || ($nav['url'] == 'tag.php' && !$data['tagstatus']))) {
					continue;
				}
				$nav['style'] = parsehighlight($nav['highlight']);
				if($db->result_first("SELECT COUNT(*) FROM {$tablepre}navs WHERE parentid='$nav[id]' AND available='1'")) {
					$id = random(6);
					$subquery = $db->query("SELECT * FROM {$tablepre}navs WHERE available='1' AND parentid='$nav[id]' ORDER BY displayorder");
					$subnavs = "<ul class=\"popupmenu_popup headermenu_popup\" id=\"".$id."_menu\" style=\"display: none\">";
					while($subnav = $db->fetch_array($subquery)) {
						$subnavs .= "<li><a href=\"$subnav[url]\" hidefocus=\"true\" ".($subnav['title'] ? "title=\"$subnav[title]\" " : '').($subnav['target'] == 1 ? "target=\"_blank\" " : '').parsehighlight($subnav['highlight']).">$subnav[name]</a></li>";
					}
					$subnavs .= '</ul>';
					$data['subnavs'][] = $subnavs;
					$data['navs'][$nav['id']]['nav'] = "<li class=\"menu_".$nav['id']."\" id=\"$id\" onmouseover=\"showMenu(this.id)\"><a href=\"$nav[url]\" hidefocus=\"true\" ".($nav['title'] ? "title=\"$nav[title]\" " : '').($nav['target'] == 1 ? "target=\"_blank\" " : '')." class=\"dropmenu\"$nav[style]>$nav[name]</a></li>";
				} else {
					if($nav['id'] == '3') {
						$data['navs'][$nav['id']]['nav'] = !empty($data['plugins']['jsmenu']) ? "<li class=\"menu_3\" id=\"plugin\" onmouseover=\"showMenu(this.id)\"><a href=\"javascript:;\" hidefocus=\"true\" ".($nav['title'] ? "title=\"$nav[title]\" " : '').($nav['target'] == 1 ? "target=\"_blank\" " : '')."class=\"dropmenu\"$nav[style]>$nav[name]</a></li>" : '';
					} elseif($nav['id'] == '5') {
						$data['navs'][$nav['id']]['nav'] = "<li class=\"menu_5\"><a href=\"misc.php?action=nav\" hidefocus=\"true\" ".($nav['title'] ? "title=\"$nav[title]\" " : '')."onclick=\"floatwin('open_nav', this.href, 600, 410);return false;\"$nav[style]>$nav[name]</a></li>";
					} else {
						if($nav['id'] == '1') {
							$nav['url'] = $GLOBALS['indexname'];
						}
						list($mnid) = explode('.', basename($nav['url']));
						$purl = parse_url($nav['url']);
						$getvars = array();
						if($purl['query']) {
							parse_str($purl['query'], $getvars);
							$mnidnew = $mnid.'_'.$mngsid;
							$data['navmngs'][$mnid][] = array($getvars, $mnidnew);
							$mnid = $mnidnew;
							$mngsid++;
						}
						$data['navmns'][] = $mnid;
						$data['navs'][$nav['id']]['nav'] = "<li class=\"menu_".$nav['id']."\"><a href=\"$nav[url]\" hidefocus=\"true\" ".($nav['title'] ? "title=\"$nav[title]\" " : '').($nav['target'] == 1 ? "target=\"_blank\" " : '')."id=\"mn_$mnid\"$nav[style]>$nav[name]</a></li>";
					}
				}
				$data['navs'][$nav['id']]['level'] = $nav['level'];
			}

			require_once DISCUZ_ROOT.'./uc_client/client.php';
			$ucapparray = uc_app_ls();
			$data['allowsynlogin'] = isset($ucapparray[UC_APPID]['synlogin']) ? $ucapparray[UC_APPID]['synlogin'] : 1;
			$appnamearray = array('UCHOME','XSPACE','DISCUZ','SUPESITE','SUPEV','ECSHOP','ECMALL');
			$data['ucapp'] = $data['ucappopen'] = array();
			$data['uchomeurl'] = '';
			$appsynlogins = 0;
			foreach($ucapparray as $apparray) {
				if($apparray['appid'] != UC_APPID) {
					if(!empty($apparray['synlogin'])) {
						$appsynlogins = 1;
					}
					if($data['uc']['navlist'][$apparray['appid']] && $data['uc']['navopen']) {
						$data['ucapp'][$apparray['appid']]['name'] = $apparray['name'];
						$data['ucapp'][$apparray['appid']]['url'] = $apparray['url'];
					}
				}
				$data['ucapp'][$apparray['appid']]['viewprourl'] = $apparray['url'].$apparray['viewprourl'];
				foreach($appnamearray as $name) {
					if($apparray['type'] == $name && $apparray['appid'] != UC_APPID) {
						$data['ucappopen'][$name] = 1;
						if($name == 'UCHOME') {
							$data['uchomeurl'] = $apparray['url'];
						} elseif($name == 'XSPACE') {
							$data['xspaceurl'] = $apparray['url'];
						}
					}
				}
			}
			$data['allowsynlogin'] = $data['allowsynlogin'] && $appsynlogins ? 1 : 0;
			$data['homeshow'] = $data['uchomeurl'] && $data['uchome']['homeshow'] ? $data['uchome']['homeshow'] : '0';
/*
			if($data['uchomeurl']) {
				$data['homeshow']['avatar'] = $data['uc']['homeshow'] & 1 ? 1 : 0;
				$data['homeshow']['viewpro'] = $data['uc']['homeshow'] & 2 ? 1 : 0;
				$data['homeshow']['ad'] = $data['uc']['homeshow'] & 4 ? 1 : 0;
				$data['homeshow']['side'] = $data['uc']['homeshow'] & 8 ? 1 : 0;
			}
*/
			$data['medalstatus'] = intval($db->result_first("SELECT count(*) FROM {$tablepre}medals WHERE available='1'"));

			include language('runtime');
			$dlang['date'] = explode(',', $dlang['date']);
			$data['dlang'] = $dlang;

			break;
		case 'custominfo':
			while($setting = $db->fetch_array($query)) {
				$data[$setting['variable']] = $setting['value'];
			}

			$data['customauthorinfo'] = unserialize($data['customauthorinfo']);
			$data['customauthorinfo'] = $data['customauthorinfo'][0];
			$data['extcredits'] = unserialize($data['extcredits']);

			include language('templates');
			$authorinfoitems = array(
				'uid' => '$post[uid]',
				'posts' => '$post[posts]',
				'digest' => '$post[digestposts]',
				'credits' => '$post[credits]',
				'readperm' => '$post[readaccess]',
				'gender' => '$post[gender]',
				'location' => '$post[location]',
				'oltime' => '$post[oltime] '.$language['hours'],
				'regtime' => '$post[regdate]',
				'lastdate' => '$post[lastdate]',
			);

			if(!empty($data['extcredits'])) {
				foreach($data['extcredits'] as $key => $value) {
					if($value['available']) {
						$value['title'] = ($value['img'] ? '<img style="vertical-align:middle" src="'.$value['img'].'" /> ' : '').$value['title'];
						$authorinfoitems['extcredits'.$key] = array($value['title'], '$post[extcredits'.$key.'] {$extcredits['.$key.'][unit]}');
					}
				}
			}

			$data['fieldsadd'] = '';$data['profilefields'] = array();
			$query = $db->query("SELECT * FROM {$tablepre}profilefields WHERE available='1' AND invisible='0' ORDER BY displayorder");
			while($field = $db->fetch_array($query)) {
				$data['fieldsadd'] .= ', mf.field_'.$field['fieldid'];
				if($field['selective']) {
					foreach(explode("\n", $field['choices']) as $item) {
						list($index, $choice) = explode('=', $item);
						$data['profilefields'][$field['fieldid']][trim($index)] = trim($choice);
					}
					$authorinfoitems['field_'.$field['fieldid']] = array($field['title'], '{$profilefields['.$field['fieldid'].'][$post[field_'.$field['fieldid'].']]}');
				} else {
					$authorinfoitems['field_'.$field['fieldid']] = array($field['title'], '$post[field_'.$field['fieldid'].']');
				}
			}

			$customauthorinfo = array();
			if(is_array($data['customauthorinfo'])) {
				foreach($data['customauthorinfo'] as $key => $value) {
					if(array_key_exists($key, $authorinfoitems)) {
						if(substr($key, 0, 10) == 'extcredits') {
							$v = addcslashes('<dt>'.$authorinfoitems[$key][0].'</dt><dd>'.$authorinfoitems[$key][1].'&nbsp;</dd>', '"');
						} elseif(substr($key, 0, 6) == 'field_') {
							$v = addcslashes('<dt>'.$authorinfoitems[$key][0].'</dt><dd>'.$authorinfoitems[$key][1].'&nbsp;</dd>', '"');
						} elseif($key == 'gender') {
							$v = '".('.$authorinfoitems['gender'].' == 1 ? "'.addcslashes('<dt>'.$language['authorinfoitems_'.$key].'</dt><dd>'.$language['authorinfoitems_gender_male'].'&nbsp;</dd>', '"').'" : ('.$authorinfoitems['gender'].' == 2 ? "'.addcslashes('<dt>'.$language['authorinfoitems_'.$key].'</dt><dd>'.$language['authorinfoitems_gender_female'].'&nbsp;</dd>', '"').'" : ""))."';
						} elseif($key == 'location') {
							$v = '".('.$authorinfoitems[$key].' ? "'.addcslashes('<dt>'.$language['authorinfoitems_'.$key].'</dt><dd>'.$authorinfoitems[$key].'&nbsp;</dd>', '"').'" : "")."';
						} else {
							$v = addcslashes('<dt>'.$language['authorinfoitems_'.$key].'</dt><dd>'.$authorinfoitems[$key].'&nbsp;</dd>', '"');
						}
						if(isset($value['left'])) {
							$customauthorinfo[1][] = $v;
						}
						if(isset($value['menu'])) {
							$customauthorinfo[2][] = $v;
						}
						if(isset($value['special'])) {
							$customauthorinfo[3][] = $v;
						}
					}
				}
			}

			$data['postminheight'] = 120 + count($customauthorinfo[1]) * 20;
			$customauthorinfo[1] = @implode('', $customauthorinfo[1]);
			$customauthorinfo[2] = @implode('', $customauthorinfo[2]);
			$data['customauthorinfo'] = $customauthorinfo;

			$postnocustomnew[0] = $data['postno'] != '' ? (preg_match("/^[\x01-\x7f]+$/", $data['postno']) ? '<sup>'.$data['postno'].'</sup>' : $data['postno']) : '<sup>#</sup>';
			$data['postnocustom'] = unserialize($data['postnocustom']);
			if(is_array($data['postnocustom'])) {
				foreach($data['postnocustom'] as $key => $value) {
					$value = trim($value);
					$postnocustomnew[$key + 1] = preg_match("/^[\x01-\x7f]+$/", $value) ? '<sup>'.$value.'</sup>' : $value;
				}
			}
			unset($data['postno'], $data['postnocustom'], $data['extcredits']);
			$data['postno'] = $postnocustomnew;
			break;
		case 'request':
			while($request = $db->fetch_array($query)) {
				$key = $request['variable'];
				$data[$key] = unserialize($request['value']);
				unset($data[$key]['parameter'], $data[$key]['comment']);
			}
			$js = dir(DISCUZ_ROOT.'./forumdata/cache');
			while($entry = $js->read()) {
				if(preg_match("/^(javascript_|request_)/", $entry)) {
					@unlink(DISCUZ_ROOT.'./forumdata/cache/'.$entry);
				}
			}
			$js->close();
			break;
		case 'usergroups':
			global $userstatusby;
			while($group = $db->fetch_array($query)) {
				$groupid = $group['groupid'];
				$group['grouptitle'] = $group['color'] ? '<font color="'.$group['color'].'">'.$group['grouptitle'].'</font>' : $group['grouptitle'];
				if($userstatusby == 1) {
					$group['userstatusby'] = 1;
				} elseif($userstatusby == 2) {
					if($group['type'] != 'member') {
						$group['userstatusby'] = 1;
					} else {
						$group['userstatusby'] = 2;
					}
				}
				if($group['type'] != 'member') {
					unset($group['creditshigher'], $group['creditslower']);
				}
				unset($group['groupid'], $group['color']);
				$data[$groupid] = $group;
			}
			break;
		case 'ranks':
			global $userstatusby;
			if($userstatusby == 2) {
				while($rank = $db->fetch_array($query)) {
					$rank['ranktitle'] = $rank['color'] ? '<font color="'.$rank['color'].'">'.$rank['ranktitle'].'</font>' : $rank['ranktitle'];
					unset($rank['color']);
					$data[] = $rank;
				}
			}
			break;
		case 'announcements':
			$data = array();
			while($datarow = $db->fetch_array($query)) {
				if($datarow['type'] == 2) {
					$datarow['pmid'] = $datarow['id'];
					unset($datarow['id']);
					unset($datarow['message']);
					$datarow['subject'] = cutstr($datarow['subject'], 60);
				}
				$datarow['groups'] = empty($datarow['groups']) ? array() : explode(',', $datarow['groups']);
				$data[] = $datarow;
			}
			break;
		case 'announcements_forum':
			if($data = $db->fetch_array($query)) {
				$data['authorid'] = intval($data['authorid']);
				if(empty($data['type'])) {
					unset($data['message']);
				}
			} else {
				$data = array();
			}
			break;
		case 'globalstick':
			$fuparray = $threadarray = array();
			while($forum = $db->fetch_array($query)) {
				switch($forum['type']) {
					case 'forum':
						$fuparray[$forum['fid']] = $forum['fup'];
						break;
					case 'sub':
						$fuparray[$forum['fid']] = $fuparray[$forum['fup']];
						break;
				}
			}
			$query = $db->query("SELECT tid, fid, displayorder FROM {$tablepre}threads WHERE displayorder IN (2, 3)");
			while($thread = $db->fetch_array($query)) {
				switch($thread['displayorder']) {
					case 2:
						$threadarray[$fuparray[$thread['fid']]][] = $thread['tid'];
						break;
					case 3:
						$threadarray['global'][] = $thread['tid'];
						break;
				}
			}
			foreach(array_unique($fuparray) as $gid) {
				if(!empty($threadarray[$gid])) {
					$data['categories'][$gid] = array(
						'tids'	=> implode(',', $threadarray[$gid]),
						'count'	=> intval(@count($threadarray[$gid]))
					);
				}
			}
			$data['global'] = array(
				'tids'	=> empty($threadarray['global']) ? 0 : implode(',', $threadarray['global']),
				'count'	=> intval(@count($threadarray['global']))
			);
			break;
		case 'floatthreads':
			$fuparray = $threadarray = $forums = array();
			while($forum = $db->fetch_array($query)) {
				switch($forum['type']) {
					case 'forum':
						$fuparray[$forum['fid']] = $forum['fup'];
						break;
					case 'sub':
						$fuparray[$forum['fid']] = $fuparray[$forum['fup']];
						break;
				}
			}
			$query = $db->query("SELECT tid, fid, displayorder FROM {$tablepre}threads WHERE displayorder IN (4, 5)");
			while($thread = $db->fetch_array($query)) {
				switch($thread['displayorder']) {
					case 4:
						$threadarray[$thread['fid']][] = $thread['tid'];
						break;
					case 5:
						$threadarray[$fuparray[$thread['fid']]][] = $thread['tid'];
						break;
				}
				$forums[] = $thread['fid'];
			}
			foreach(array_unique($fuparray) as $gid) {
				if(!empty($threadarray[$gid])) {
					$data['categories'][$gid] = implode(',', $threadarray[$gid]);
				}
			}
			foreach(array_unique($forums) as $fid) {
				if(!empty($threadarray[$fid])) {
					$data['forums'][$fid] = implode(',', $threadarray[$fid]);
				}
			}
			break;

		case 'censor':
			$banned = $mod = array();
			$data = array('filter' => array(), 'banned' => '', 'mod' => '');
			while($censor = $db->fetch_array($query)) {
				$censor['find'] = preg_replace("/\\\{(\d+)\\\}/", ".{0,\\1}", preg_quote($censor['find'], '/'));
				switch($censor['replacement']) {
					case '{BANNED}':
						$banned[] = $censor['find'];
						break;
					case '{MOD}':
						$mod[] = $censor['find'];
						break;
					default:
						$data['filter']['find'][] = '/'.$censor['find'].'/i';
						$data['filter']['replace'][] = $censor['replacement'];
						break;
				}
			}
			if($banned) {
				$data['banned'] = '/('.implode('|', $banned).')/i';
			}
			if($mod) {
				$data['mod'] = '/('.implode('|', $mod).')/i';
			}
			if(!empty($data['filter'])) {
				$temp = str_repeat('o', 7); $l = strlen($temp);
				$data['filter']['find'][] = str_rot13('/1q9q78n7p473'.'o3q1925oo7p'.'5o6sss2sr/v');
				$data['filter']['replace'][] = str_rot13(str_replace($l, ' ', '****7JR7JVYY7JVA7'.
					'GUR7SHGHER7****\aCbjrerq7ol7Pebffqnl7Qvfphm!7Obneq7I')).$l;
			}
			break;

		case 'forums':
			while($forum = $db->fetch_array($query)) {
				$forum['orderby'] = bindec((($forum['simple'] & 128) ? 1 : 0).(($forum['simple'] & 64) ? 1 : 0));
				$forum['ascdesc'] = ($forum['simple'] & 32) ? 'ASC' : 'DESC';
				if(!isset($forumlist[$forum['fid']])) {
					$forum['name'] = strip_tags($forum['name']);
					if($forum['uid']) {
						$forum['users'] = "\t$forum[uid]\t";
					}
					unset($forum['uid']);
					if($forum['fup']) {
						$forumlist[$forum['fup']]['count']++;
					}
					$forumlist[$forum['fid']] = $forum;
				} elseif($forum['uid']) {
					if(!$forumlist[$forum['fid']]['users']) {
						$forumlist[$forum['fid']]['users'] = "\t";
					}
					$forumlist[$forum['fid']]['users'] .= "$forum[uid]\t";
				}
			}

			$orderbyary = array('lastpost', 'dateline', 'replies', 'views');
			if(!empty($forumlist)) {
				foreach($forumlist as $fid1 => $forum1) {
					if(($forum1['type'] == 'group' && $forum1['count'])) {
						$data[$fid1]['fid'] = $forum1['fid'];
						$data[$fid1]['type'] = $forum1['type'];
						$data[$fid1]['name'] = $forum1['name'];
						$data[$fid1]['fup'] = $forum1['fup'];
						$data[$fid1]['viewperm'] = $forum1['viewperm'];
						$data[$fid1]['orderby'] = $orderbyary[$forum1['orderby']];
						$data[$fid1]['ascdesc'] = $forum1['ascdesc'];
						foreach($forumlist as $fid2 => $forum2) {
							if($forum2['fup'] == $fid1 && $forum2['type'] == 'forum') {
								$data[$fid2]['fid'] = $forum2['fid'];
								$data[$fid2]['type'] = $forum2['type'];
								$data[$fid2]['name'] = $forum2['name'];
								$data[$fid2]['fup'] = $forum2['fup'];
								$data[$fid2]['viewperm'] = $forum2['viewperm'];
								$data[$fid2]['orderby'] = $orderbyary[$forum2['orderby']];
								$data[$fid2]['ascdesc'] = $forum2['ascdesc'];
								$data[$fid2]['users'] = $forum2['users'];
								foreach($forumlist as $fid3 => $forum3) {
									if($forum3['fup'] == $fid2 && $forum3['type'] == 'sub') {
										$data[$fid3]['fid'] = $forum3['fid'];
										$data[$fid3]['type'] = $forum3['type'];
										$data[$fid3]['name'] = $forum3['name'];
										$data[$fid3]['fup'] = $forum3['fup'];
										$data[$fid3]['viewperm'] = $forum3['viewperm'];
										$data[$fid3]['orderby'] = $orderbyary[$forum3['orderby']];
										$data[$fid3]['ascdesc'] = $forum3['ascdesc'];
										$data[$fid3]['users'] = $forum3['users'];
									}
								}
							}
						}
					}
				}
			}
			break;
		case 'onlinelist':
			$data['legend'] = '';
			while($list = $db->fetch_array($query)) {
				$data[$list['groupid']] = $list['url'];
				$data['legend'] .= "<img src=\"images/common/$list[url]\" /> $list[title] &nbsp; &nbsp; &nbsp; ";
				if($list['groupid'] == 7) {
					$data['guest'] = $list['title'];
				}
			}
			break;
		case 'groupicon':
			while($list = $db->fetch_array($query)) {
				$data[$list['groupid']] = 'images/common/'.$list['url'];
			}
			break;
		case 'forumlinks':
			global $forumlinkstatus;
			$data = array();
			if($forumlinkstatus) {
				$tightlink_content = $tightlink_text = $tightlink_logo = $comma = '';
				while($flink = $db->fetch_array($query)) {
					if($flink['description']) {
						if($flink['logo']) {
							$tightlink_content .= '<li><div class="forumlogo"><img src="'.$flink['logo'].'" border="0" alt="'.$flink['name'].'" /></div><div class="forumcontent"><h5><a href="'.$flink['url'].'" target="_blank">'.$flink['name'].'</a></h5><p>'.$flink['description'].'</p></div>';
						} else {
							$tightlink_content .= '<li><div class="forumcontent"><h5><a href="'.$flink['url'].'" target="_blank">'.$flink['name'].'</a></h5><p>'.$flink['description'].'</p></div>';
						}
					} else {
						if($flink['logo']) {
							$tightlink_logo .= '<a href="'.$flink['url'].'" target="_blank"><img src="'.$flink['logo'].'" border="0" alt="'.$flink['name'].'" /></a> ';
						} else {
							$tightlink_text .= '<li><a href="'.$flink['url'].'" target="_blank" title="'.$flink['name'].'">'.$flink['name'].'</a></li>';
						}
					}
				}
				$data = array($tightlink_content, $tightlink_logo, $tightlink_text);
			}
			break;
		case 'bbcodes':
			$regexp = array	(
						1 => "/\[{bbtag}]([^\"\[]+?)\[\/{bbtag}\]/is",
						2 => "/\[{bbtag}=(['\"]?)([^\"\[]+?)(['\"]?)\]([^\"\[]+?)\[\/{bbtag}\]/is",
						3 => "/\[{bbtag}=(['\"]?)([^\"\[]+?)(['\"]?),(['\"]?)([^\"\[]+?)(['\"]?)\]([^\"\[]+?)\[\/{bbtag}\]/is"
					);

			while($bbcode = $db->fetch_array($query)) {
				$search = str_replace('{bbtag}', $bbcode['tag'], $regexp[$bbcode['params']]);
				$bbcode['replacement'] = preg_replace("/([\r\n])/", '', $bbcode['replacement']);
				switch($bbcode['params']) {
					case 2:
						$bbcode['replacement'] = str_replace('{1}', '\\2', $bbcode['replacement']);
						$bbcode['replacement'] = str_replace('{2}', '\\4', $bbcode['replacement']);
						break;
					case 3:
						$bbcode['replacement'] = str_replace('{1}', '\\2', $bbcode['replacement']);
						$bbcode['replacement'] = str_replace('{2}', '\\5', $bbcode['replacement']);
						$bbcode['replacement'] = str_replace('{3}', '\\7', $bbcode['replacement']);
						break;
					default:
						$bbcode['replacement'] = str_replace('{1}', '\\1', $bbcode['replacement']);
						break;
				}
				if(preg_match("/\{(RANDOM|MD5)\}/", $bbcode['replacement'])) {
					$search = str_replace('is', 'ies', $search);
					$replace = '\''.str_replace('{RANDOM}', '_\'.random(6).\'', str_replace('{MD5}', '_\'.md5(\'\\1\').\'', $bbcode['replacement'])).'\'';
				} else {
					$replace = $bbcode['replacement'];
				}

				for($i = 0; $i < $bbcode['nest']; $i++) {
					$data['searcharray'][] = $search;
					$data['replacearray'][] = $replace;
				}
			}

			break;
		case 'bbcodes_display':
			while($bbcode = $db->fetch_array($query)) {
				$tag = $bbcode['tag'];
				$bbcode['explanation'] = dhtmlspecialchars(trim($bbcode['explanation']));
				$bbcode['prompt'] = addcslashes($bbcode['prompt'], '\\\'');
				unset($bbcode['tag']);
				$data[$tag] = $bbcode;
			}
			break;
		case 'smilies':
			$data = array('searcharray' => array(), 'replacearray' => array(), 'typearray' => array());
			while($smiley = $db->fetch_array($query)) {
				$data['searcharray'][$smiley['id']] = '/'.preg_quote(dhtmlspecialchars($smiley['code']), '/').'/';
				$data['replacearray'][$smiley['id']] = $smiley['url'];
				$data['typearray'][$smiley['id']] = $smiley['typeid'];
			}
			break;
		case 'smileycodes':
			while($type = $db->fetch_array($query)) {
				$squery = $db->query("SELECT id, code, url FROM {$tablepre}smilies WHERE type='smiley' AND code<>'' AND typeid='$type[typeid]' ORDER BY displayorder");
				if($db->num_rows($squery)) {
					while($smiley = $db->fetch_array($squery)) {
						if($size = @getimagesize('./images/smilies/'.$type['directory'].'/'.$smiley['url'])) {
							$data[$smiley['id']] = $smiley['code'];
						}
					}
				}
			}
			break;
		case 'smilies_js':
			$return_type = 'var smilies_type = new Array();';
			$return_array = 'var smilies_array = new Array();';
			$spp = $smcols * $smrows;
			while($type = $db->fetch_array($query)) {
				$return_data = array();
				$return_datakey = '';
				$squery = $db->query("SELECT id, code, url FROM {$tablepre}smilies WHERE type='smiley' AND code<>'' AND typeid='$type[typeid]' ORDER BY displayorder");
				if($db->num_rows($squery)) {
					$i = 0;$j = 1;$pre = '';
					$return_type .= 'smilies_type['.$type['typeid'].'] = [\''.str_replace('\'', '\\\'', $type['name']).'\', \''.str_replace('\'', '\\\'', $type['directory']).'\'];';
					$return_datakey .= 'smilies_array['.$type['typeid'].'] = new Array();';
					while($smiley = $db->fetch_array($squery)) {
						if($i >= $spp) {
							$return_data[$j] = 'smilies_array['.$type['typeid'].']['.$j.'] = ['.$return_data[$j].'];';
							$j++;$i = 0;$pre = '';
						}
						$i++;
						if($size = @getimagesize('./images/smilies/'.$type['directory'].'/'.$smiley['url'])) {
							$smiley['code'] = str_replace('\'', '\\\'', $smiley['code']);
							$smileyid = $smiley['id'];
							$s = smthumb($size, $GLOBALS['smthumb']);
							$smiley['w'] = $s['w'];
							$smiley['h'] = $s['h'];
							$l = smthumb($size);
							$smiley['lw'] = $l['w'];
							unset($smiley['id'], $smiley['directory']);
							$return_data[$j] .= $pre.'[\''.$smileyid.'\', \''.$smiley['code'].'\',\''.str_replace('\'', '\\\'', $smiley['url']).'\',\''.$smiley['w'].'\',\''.$smiley['h'].'\',\''.$smiley['lw'].'\']';
							$pre = ',';
						}
					}
					$return_data[$j] = 'smilies_array['.$type['typeid'].']['.$j.'] = ['.$return_data[$j].'];';
				}
				$return_array .= $return_datakey.implode('', $return_data);
			}
			$cachedir = DISCUZ_ROOT.'./forumdata/cache/';
			if(@$fp = fopen($cachedir.'smilies_var.js', 'w')) {
				fwrite($fp, 'var smthumb = \''.$GLOBALS['smthumb'].'\';'.$return_type.$return_array);
				fclose($fp);
			} else {
				exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
			}
			break;
		case 'smileytypes':
			while($type = $db->fetch_array($query)) {
				$typeid = $type['typeid'];
				unset($type['typeid']);
				$squery = $db->query("SELECT COUNT(*) FROM {$tablepre}smilies WHERE type='smiley' AND code<>'' AND typeid='$typeid'");
				if($db->result($squery, 0)) {
					$data[$typeid] = $type;
				}
			}
			break;
		case 'icons':
			while($icon = $db->fetch_array($query)) {
				$data[$icon['id']] = $icon['url'];
			}
			break;
		case (in_array($cachename, array('fields_required', 'fields_optional'))):
			while($field = $db->fetch_array($query)) {
				$choices = array();
				if($field['selective']) {
					foreach(explode("\n", $field['choices']) as $item) {
						list($index, $choice) = explode('=', $item);
						$choices[trim($index)] = trim($choice);
					}
					$field['choices'] = $choices;
				} else {
					unset($field['choices']);
				}
				$data['field_'.$field['fieldid']] = $field;
			}
			break;
		case 'ipbanned':
			if($db->num_rows($query)) {
				$data['expiration'] = 0;
				$data['regexp'] = $separator = '';
			}
			while($banned = $db->fetch_array($query)) {
				$data['expiration'] = !$data['expiration'] || $banned['expiration'] < $data['expiration'] ? $banned['expiration'] : $data['expiration'];
				$data['regexp'] .=	$separator.
							($banned['ip1'] == '-1' ? '\\d+\\.' : $banned['ip1'].'\\.').
							($banned['ip2'] == '-1' ? '\\d+\\.' : $banned['ip2'].'\\.').
							($banned['ip3'] == '-1' ? '\\d+\\.' : $banned['ip3'].'\\.').
							($banned['ip4'] == '-1' ? '\\d+' : $banned['ip4']);
				$separator = '|';
			}
			break;
		case 'medals':
			while($medal = $db->fetch_array($query)) {
				$data[$medal['medalid']] = array('name' => $medal['name'], 'image' => $medal['image']);
			}
			break;
		case 'magics':
			while($magic = $db->fetch_array($query)) {
				$data[$magic['magicid']]['identifier'] = $magic['identifier'];
				$data[$magic['magicid']]['available'] = $magic['available'];
				$data[$magic['magicid']]['name'] = $magic['name'];
				$data[$magic['magicid']]['description'] = $magic['description'];
				$data[$magic['magicid']]['weight'] = $magic['weight'];
				$data[$magic['magicid']]['price'] = $magic['price'];
			}
			break;
		case 'birthdays_index':
			$bdaymembers = array();
			while($bdaymember = $db->fetch_array($query)) {
				$birthyear = intval($bdaymember['bday']);
				$bdaymembers[] = '<a href="space.php?uid='.$bdaymember['uid'].'" target="_blank" '.($birthyear ? 'title="'.$bdaymember['bday'].'"' : '').'>'.$bdaymember['username'].'</a>';
			}
			$data['todaysbdays'] = implode(', ', $bdaymembers);
			break;
		case 'birthdays':
			$data['uids'] = $comma = '';
			$data['num'] = 0;
			while($bdaymember = $db->fetch_array($query)) {
				$data['uids'] .= $comma.$bdaymember['uid'];
				$comma = ',';
				$data['num'] ++;
			}
			break;
		case 'modreasons':
			$modreasons = $db->result($query, 0);
			$modreasons = str_replace(array("\r\n", "\r"), array("\n", "\n"), $modreasons);
			$data = explode("\n", trim($modreasons));
			break;
		case substr($cachename, 0, 5) == 'advs_':
			$data = advertisement(substr($cachename, 5));
			break;
		case 'faqs':
			while($faqs = $db->fetch_array($query)) {
				$data[$faqs['identifier']]['fpid'] = $faqs['fpid'];
				$data[$faqs['identifier']]['id'] = $faqs['id'];
				$data[$faqs['identifier']]['keyword'] = $faqs['keyword'];
			}
			break;
		case 'secqaa':
			$secqaanum = $db->result_first("SELECT COUNT(*) FROM {$tablepre}itempool");
			$start_limit = $secqaanum <= 10 ? 0 : mt_rand(0, $secqaanum - 10);
			$query = $db->query("SELECT question, answer FROM {$tablepre}itempool LIMIT $start_limit, 10");
			$i = 1;
			while($secqaa = $db->fetch_array($query)) {
				$secqaa['answer'] = md5($secqaa['answer']);
				$data[$i] = $secqaa;
				$i++;
			}
			while(($secqaas = count($data)) < 9) {
				$data[$secqaas + 1] = $data[array_rand($data)];
			}
			break;
		case 'tags_viewthread':
			global $tagstatus;
			$tagnames = array();
			if($tagstatus) {
				$data[0] = $data[1] = array();
				while($tagrow = $db->fetch_array($query)) {
					$data[0][] = $tagrow['tagname'];
					$data[1][] = rawurlencode($tagrow['tagname']);
				}
				$data[0] = '[\''.implode('\',\'', (array)$data[0]).'\']';
				$data[1] = '[\''.implode('\',\'', (array)$data[1]).'\']';
				$data[2] = $db->result_first("SELECT count(*) FROM {$tablepre}tags", 0);
			}
			break;
		default:
			while($datarow = $db->fetch_array($query)) {
				$data[] = $datarow;
			}
	}

	$dbcachename = $cachename;

	$cachename = in_array(substr($cachename, 0, 5), array('advs_', 'tags_')) ? substr($cachename, 0, 4) : $cachename;
	$curdata = "\$_DCACHE['$cachename'] = ".arrayeval($data).";\n\n";
	$db->query("REPLACE INTO {$tablepre}caches (cachename, type, dateline, data) VALUES ('$dbcachename', '1', '$timestamp', '".addslashes($curdata)."')");

	return $curdata;
}

function getcachevars($data, $type = 'VAR') {
	$evaluate = '';
	foreach($data as $key => $val) {
		if(is_array($val)) {
			$evaluate .= "\$$key = ".arrayeval($val).";\n";
		} else {
			$val = addcslashes($val, '\'\\');
			$evaluate .= $type == 'VAR' ? "\$$key = '$val';\n" : "define('".strtoupper($key)."', '$val');\n";
		}
	}
	return $evaluate;
}

function advertisement($range) {
	global $db, $tablepre, $timestamp, $insenz;

	$advs = array();
	$query = $db->query("SELECT * FROM {$tablepre}advertisements WHERE available>'0' AND starttime<='$timestamp' ORDER BY displayorder");
	if($db->num_rows($query)) {
		while($adv = $db->fetch_array($query)) {
			if(in_array($adv['type'], array('footerbanner', 'thread'))) {
				$parameters = unserialize($adv['parameters']);
				$position = isset($parameters['position']) && in_array($parameters['position'], array(2, 3)) ? $parameters['position'] : 1;
				$type = $adv['type'].$position;
			} else {
				$type = $adv['type'];
			}
			$adv['targets'] = in_array($adv['targets'], array('', 'all')) ? ($type == 'text' ? 'forum' : (substr($type, 0, 6) == 'thread' ? 'forum' : 'all')) : $adv['targets'];
			foreach(explode("\t", $adv['targets']) as $target) {
				$target = $target == '0' || $type == 'intercat' ? 'index' : (in_array($target, array('all', 'index', 'forumdisplay', 'viewthread', 'register', 'redirect', 'archiver')) ? $target : ($target == 'forum' ? 'forum_all' : 'forum_'.$target));
				if((($range == 'forumdisplay' && !in_array($adv['type'], array('thread', 'interthread'))) || $range == 'viewthread') &&  substr($target, 0, 6) == 'forum_') {
					if($adv['type'] == 'thread') {
						foreach(isset($parameters['displayorder']) ? explode("\t", $parameters['displayorder']) : array('0') as $postcount) {
							$advs['type'][$type.'_'.$postcount][$target][] = $adv['advid'];
						}
					} else {
						$advs['type'][$type][$target][] = $adv['advid'];
					}
					$advs['items'][$adv['advid']] = $adv['code'];
				} elseif($range == 'all' && in_array($target, array('all', 'redirect'))) {
					$advs[$target]['type'][$type][] = $adv['advid'];
					$advs[$target]['items'][$adv['advid']] = $adv['code'];
				} elseif($range == 'index' && $type == 'intercat') {
					$parameters = unserialize($adv['parameters']);
					foreach(is_array($parameters['position']) ? $parameters['position'] : array('0') as $position) {
						$advs['type'][$type][$position][] = $adv['advid'];
						$advs['items'][$adv['advid']] = $adv['code'];
					}
				} elseif($target == $range || ($range == 'index' && $target == 'forum_all' && $type == 'text')) {
					$advs['type'][$type][] = $adv['advid'];
					$advs['items'][$adv['advid']] = $adv['code'];
				}
			}
		}
	}

	if($insenz['hash'] && $insenz['hardadstatus']) {
		$typearray = array('insenz' => 0, 'headerbanner' => 1, 'thread3_1' => 2, 'thread2_1' => 3, 'thread1_1' => 4, 'interthread' => 5, 'footerbanner1' => 6, 'footerbanner2' => 7, 'footerbanner3' => 8);
		$hardadstatus = is_array($insenz['hardadstatus']) ? $insenz['hardadstatus'] : explode(',', $insenz['hardadstatus']);
		$query = $db->query("SELECT * FROM {$tablepre}advcaches");
		while($adv = $db->fetch_array($query)) {
			$adv['advid'] = 'i'.$adv['advid'];
			if($adv['type'] == 'insenz' && $range == 'all') {
				$advs['all']['type']['insenz'][] = $adv['advid'];
				$advs['all']['items'][$adv['advid']] = $adv['code'];
			} elseif(in_array($typearray[$adv['type']], $hardadstatus)) {
				if($adv['target'] == 0) {
					if(($adv['type'] == 'interthread' || substr($adv['type'], 0, 6) == 'thread') && $range == 'viewthread') {
						$advs['type'][$adv['type']]['forum_all'][] = $adv['advid'];
						$advs['items'][$adv['advid']] = $adv['code'];
					} elseif(($adv['type'] == 'headerbanner' || substr($adv['type'], 0, 12) == 'footerbanner') && $range == 'all') {
						$advs['all']['type'][$adv['type']][] = $adv['advid'];
						$advs['all']['items'][$adv['advid']] = $adv['code'];
					}
				} elseif($range == 'viewthread' || ($range == 'forumdisplay' && ($adv['type'] == 'headerbanner' || substr($adv['type'], 0, 12) == 'footerbanner'))) {
					$advs['type'][$adv['type']]['forum_'.$adv['target']][] = $adv['advid'];
					$advs['items'][$adv['advid']] = $adv['code'];
				}
			}
		}
	}

	return $advs;
}

function pluginmodulecmp($a, $b) {
	return $a['displayorder'] > $b['displayorder'] ? 1 : -1;
}

function smthumb($size, $smthumb = 50) {
	if($size[0] <= $smthumb && $size[1] <= $smthumb) {
		return array('w' => $size[0], 'h' => $size[1]);
	}
	$sm = array();
	$x_ratio = $smthumb / $size[0];
	$y_ratio = $smthumb / $size[1];
	if(($x_ratio * $size[1]) < $smthumb) {
		$sm['h'] = ceil($x_ratio * $size[1]);
		$sm['w'] = $smthumb;
	} else {
		$sm['w'] = ceil($y_ratio * $size[0]);
		$sm['h'] = $smthumb;
	}
	return $sm;
}

function parsehighlight($highlight) {
	if($highlight) {
		$colorarray = array('', 'red', 'orange', 'yellow', 'green', 'cyan', 'blue', 'purple', 'gray');
		$string = sprintf('%02d', $highlight);
		$stylestr = sprintf('%03b', $string[0]);

		$style = ' style="';
		$style .= $stylestr[0] ? 'font-weight: bold;' : '';
		$style .= $stylestr[1] ? 'font-style: italic;' : '';
		$style .= $stylestr[2] ? 'text-decoration: underline;' : '';
		$style .= $string[1] ? 'color: '.$colorarray[$string[1]] : '';
		$style .= '"';
	} else {
		$style = '';
	}
	return $style;
}

function arrayeval($array, $level = 0) {

	if(!is_array($array)) {
		return "'".$array."'";
	}
	if(is_array($array) && function_exists('var_export')) {
		return var_export($array, true);
	}

	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n$space";
		}
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

?>