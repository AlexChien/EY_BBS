<?php

/*
[Discuz!] (C)2001-2009 Comsenz Inc.
This is NOT a freeware, use is subject to license terms

$Id$
*/

if(!function_exists('file_put_contents')) {
	function file_put_contents($filename, $s) {
		$fp = @fopen($filename, 'w');
		@fwrite($fp, $s);
		@fclose($fp);
		return TRUE;
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
	(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	$writeable = 0;
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.txt", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function dir_clear($dir) {
	global $lang;
	showjsmessage($lang['clear_dir'].' '.$dir);
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			@unlink($filename);
		}
	}
	$directory->close();
	@touch($dir.'/index.htm');
}

function show_header() {
	define('SHOW_HEADER', TRUE);
	global $charset, $step;
	$version = DISCUZ_VERSION;
	$release = DISCUZ_RELEASE;
	$install_lang = lang(INSTALL_LANG);
	$title = lang('title_install');
	echo <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=$charset" />
<title>$title</title>
<link rel="stylesheet" href="style.css" type="text/css" media="all" />
<script type="text/javascript">
	function $(id) {
		return document.getElementById(id);
	}

	function showmessage(message) {
		$('notice').value += message + "\\r\\n";
	}
</script>
<meta content="Comsenz Inc." name="Copyright" />
</head>
<div class="container">
	<div class="header">
		<h1>$title</h1>
		<span>V$version $install_lang $release</span>
EOT;

	$step > 0 && show_step($step);
}

function show_footer($quit = true) {

	echo <<<EOT
		<div class="footer">&copy;2001 - 2008 <a href="http://www.comsenz.com/">Comsenz</a> Inc.</div>
	</div>
</div>
</body>
</html>
EOT;
	$quit && exit();
}

function loginit($logfile) {
	global $lang;
	showjsmessage($lang['init_log'].' '.$logfile);
	if($fp = @fopen('./forumdata/logs/'.$logfile.'.php', 'w')) {
		fwrite($fp, '<'.'?PHP exit(); ?'.">\n");
		fclose($fp);
	}
}

function showjsmessage($message) {
	echo '<script type="text/javascript">showmessage(\''.addslashes($message).' \');</script>'."\r\n";
	flush();
	ob_flush();
}

function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function redirect($url) {

	echo "<script>".
	"function redirect() {window.location.replace('$url');}\n".
	"setTimeout('redirect();', 0);\n".
	"</script>";
	exit();

}

function runquery($sql, $showmessage = TRUE) {
	global $lang, $dbcharset, $tablepre, $db;

	$sql = str_replace("\r", "\n", str_replace(' cdb_', ' '.$tablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			@$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {

			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				$showmessage && showjsmessage($lang['create_table'].' '.$name.' ... '.$lang['succeed']);
				$db->query(createtable($query, $dbcharset));

			} else {
				$db->query($query);
			}

		}
	}
}

function charcovert($string) {
	if(!get_magic_quotes_gpc()) {
		$string = str_replace('\'', '\\\'', $string);
	} else {
		$string = str_replace('\"', '"', $string);
	}
	return $string;
}

function insertconfig($s, $find, $replace) {
	if(preg_match($find, $s)) {
		$s = preg_replace($find, $replace, $s);
	} else {
		// 插入到最后一行
		$s .= "\r\n".$replace;
	}
	return $s;
}

function getgpc($k, $var='GP') {
	$var = strtoupper($var);
	switch($var) {
		case 'GP' : isset($_POST[$k]) ? $var = &$_POST : $var = &$_GET; break;
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : '';
}

function var_to_hidden($k, $v) {
	return "<input type=\"hidden\" name=\"$k\" value=\"$v\" />\n";
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}

function show_license() {
	global $lang, $self, $uchidden, $step;
	$next = $step + 1;
	$discuz_license = str_replace('  ', '&nbsp; ', $lang['license']);
	echo <<<EOT

</div>
<div class="main" style="margin-top:-123px;">
	<div class="licenseblock">$discuz_license</div>
	<div class="btnbox marginbot">
		<form method="post" action="$self">
		<input type="hidden" name="action" value="uc">
		<input type="hidden" name="step" value="$next">
		$uchidden
		<input type="submit" name="submit" value="{$lang['agreement_yes']}" style="padding: 2px">&nbsp;
		<input type="button" name="exit" value="{$lang['agreement_no']}" style="padding: 2px" onclick="javascript: window.close(); return false;">
		</form>
	</div>
EOT;
	show_footer();
}

function check_env() {

	global $lang, $attachdir;

	$errors = array('quit' => false);
	$quit = false;

	if(!function_exists('mysql_connect')) {
		$errors[] = 'mysql_unsupport';
		$quit = true;
	}

	if(PHP_VERSION < '4.3') {
		$errors[] = 'php_version_430';
		$quit = true;
	}

	if(!file_exists(DISCUZ_ROOT.'./config.inc.php')) {
		$errors[] = 'config_nonexistence';
		$quit = true;
	} elseif(!is_writeable(DISCUZ_ROOT.'./config.inc.php')) {
		$errors[] = 'config_unwriteable';
		$quit = true;
	}

	$checkdirarray = array(
		'attach' => $attachdir,
		'forumdata' => './forumdata',
		'cache' => './forumdata/cache',
		'ftemplates' => './forumdata/templates',
		'threadcache' => './forumdata/threadcaches',
		'log' => './forumdata/logs',
		'ucclientcache' => './uc_client/data/cache',
		//'ucconfig' => './data/config.inc.php',
		'ucdata' => './uc_server/data',
		'ucdatacache' => './uc_server/data/cache',
		'ucdataview' => './uc_server/data/view',
		'ucdataavatar' => './uc_server/data/avatar',
		'ucdatalogs' => './uc_server/data/logs',
		'ucdatabackup' => './uc_server/data/backup',
		'ucdatatmp' => './uc_server/data/tmp'
	);

	foreach($checkdirarray as $key => $dir) {
		if(!dir_writeable(DISCUZ_ROOT.$dir)) {
			$langkey = $key.'_unwriteable';
			$errors[] = $key.'_unwriteable';
			if(!in_array($key, array('ftemplate'))) {
				$quit = TRUE;
			}
		}
	}

	$errors['quit'] = $quit;
	return $errors;

}

function show_error($type, $errors = '', $quit = false) {

	global $lang, $step;

	$title = lang($type);
	$comment = lang($type.'_comment', false);
	$errormsg = '';
	if($errors) {
		if(!empty($errors)) {
			foreach ((array)$errors as $k => $v) {
				if(is_numeric($k)) {
					$comment .= "<li><em class=\"red\">".lang($v)."</em></li>";
				}
			}
		}
	}

	if($step > 0) {
		echo "<div class=\"desc\"><b>$title</b><ul>$comment</ul>";
	} else {
		echo "</div><div class=\"main\" style=\"margin-top: -123px;\"><b>$title</b><ul style=\"line-height: 200%; margin-left: 30px;\">$comment</ul>";
	}

	if($quit) {
		echo '<br /><span class="red">'.$lang['error_quit_msg'].'</span><br /><br /><br /><br /><br /><br />';
	}

	echo '</div>';

	$quit && show_footer();
}

function show_tips($tip, $title = '', $comment = '', $style = 1) {
	global $lang;
	$title = empty($title) ? lang($tip) : $title;
	$comment = empty($comment) ? lang($tip.'_comment', FALSE) : $comment;
	if($style) {
		echo "<div class=\"desc\"><b>$title</b>";
	} else {
		echo "</div><div class=\"main\" style=\"margin-top: -123px;\">$title<div class=\"desc1 marginbot\"><ul>";
	}
	$comment && print('<br>'.$comment);
	echo "</div>";
}

function show_setting($setname, $varname = '', $value = '', $type = 'text|password|checkbox', $error = '') {
	global $self;
	if($setname == 'start') {
		echo "<form method=\"post\" action=\"$self\">\n<table class=\"tb2\">\n";
		return;
	} elseif($setname == 'end') {
		echo "\n</table>\n</form>\n";
		return;
	} elseif($setname == 'hidden') {
		echo "<input type=\"hidden\" name=\"$varname\" value=\"$value\">\n";
		return;
	}

	echo "\n".'<tr><th class="tbopt'.($error ? ' red' : '').'">&nbsp;'.lang($setname)."</th>\n<td>";
	if($type == 'text' || $type == 'password') {
		$value = htmlspecialchars($value);
		echo "<input type=\"$type\" name=\"$varname\" value=\"$value\" size=\"35\" class=\"txt\">";
	} elseif($type == 'submit') {
		$value = empty($value) ? 'next_step' : $value;
		echo "<input type=\"submit\" name=\"$varname\" value=\"".lang($value)."\" class=\"btn\">\n";
	} elseif($type == 'checkbox') {
		if(!is_array($varname) && !is_array($value)) {
			echo'<input type="checkbox" name="'.$varname.'" value="'.$value."\" style=\"border: 0\">\n";
		}
	} else {
		echo $value;
	}

	echo "</td>\n<td>&nbsp;";
	if($error) {
		$comment = '<span class="red">'.(is_string($error) ? lang($error) : lang($setname.'_error')).'</span>';
	} else {
		$comment = lang($setname.'_comment', false);
	}
	echo "$comment</td>\n</tr>\n";
	return true;
}

function show_step($step) {

	$laststep = 4;
	$title = lang('step_'.$step);
	$comment = lang('step_'.$step.'_comment');

	$stepclass = array();
	for($i = 1; $i <= $laststep; $i++) {
		$stepclass[$i] = $i == $step ? 'current' : ($i < $step ? '' : 'unactivated');
	}
	$stepclass[$laststep] .= ' last';

	echo <<<EOT
	<div class="setup step{$step}">
		<h2>$title</h2>
		<p>$comment</p>
	</div>
	<div class="stepstat">
		<ul>
			<li class="$stepclass[1]">1</li>
			<li class="$stepclass[2]">2</li>
			<li class="$stepclass[3]">3</li>
			<li class="$stepclass[4]">4</li>
		</ul>
		<div class="stepstatbg stepstat1"></div>
	</div>
</div>
<div class="main">
EOT;

}

function lang($lang_key, $force = true) {
	return isset($GLOBALS['lang'][$lang_key]) ? $GLOBALS['lang'][$lang_key] : ($force ? $lang_key : '');
}

function check_adminuser($username, $password, $email) {

	@include DISCUZ_ROOT.'./config.inc.php';
	include DISCUZ_ROOT.'./uc_client/client.php';
	$error = '';

	$uid = uc_user_register($username, $password, $email);
	/*
	-1 : 用户名不合法
	-2 : 包含不允许注册的词语
	-3 : 用户名已经存在
	-4 : email 格式有误
	-5 : email 不允许注册
	-6 : 该 email 已经被注册
	>1 : 表示成功，数值为 UID
	*/
	if($uid == -1 || $uid == -2) {
		$error = 'admin_username_invalid';
	} elseif($uid == -4 || $uid == -5 || $uid == -6) {
		$error = 'admin_email_invalid';
	} elseif($uid == -3) {
		$ucresult = uc_user_login($username, $password);
		list($tmp['uid'], $tmp['username'], $tmp['password'], $tmp['email']) = uc_addslashes($ucresult);
		$ucresult = $tmp;
		if($ucresult['uid'] <= 0) {
			$error = 'admin_exist_password_error';
		} else {
			$uid = $ucresult['uid'];
			$email = $ucresult['email'];
			$password = $ucresult['password'];
		}
	}

	if(!$error && $uid > 0) {
		$password = md5($password);
		uc_user_addprotected($username, '');
	} else {
		$uid = 0;
		$error = empty($error) ? 'error_unknow_type' : $error;
	}
	
	return array('uid' => $uid, 'username' => $username, 'password' => $password, 'email' => $email, 'error' => $error);
}

function save_uc_config($config, $file) {

	$success = false;

	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);

	if($content = file_get_contents($file)) {
		$content = trim($content);
		$content = substr($content, -2) == '?>' ? substr($content, 0, -2) : $content;
		$link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
		$uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : '';
		$content = insertconfig($content, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$uc_connnect');");
		$content = insertconfig($content, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
		$content = insertconfig($content, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
		$content = insertconfig($content, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
		$content = insertconfig($content, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
		$content = insertconfig($content, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
		$content = insertconfig($content, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
		$content = insertconfig($content, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
		$content = insertconfig($content, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
		$content = insertconfig($content, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
		$content = insertconfig($content, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
		$content = insertconfig($content, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
		$content = insertconfig($content, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
		$content = insertconfig($content, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");

		if(@file_put_contents($file, $content)) {
			$success = true;
		}
	}

	return $success;
}

function _generate_key() {
	$random = random(32);
	$info = md5($_SERVER['SERVER_SOFTWARE'].$_SERVER['SERVER_NAME'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT'].time());
	$return = array();
	for($i=0; $i<32; $i++) {
		$return[$i] = $random[$i].$info[$i];
	}
	return implode('', $return);
}

function uc_write_config($config, $file, $password) {
	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);
	$ucauthkey = _generate_key();
	$ucsiteid = _generate_key();
	$ucmykey = _generate_key();
	$salt = substr(_generate_key(), 0, 6);
	$pw = md5(md5($password).$salt);
	$config = "<?php \r\ndefine('UC_DBHOST', '$ucdbhost');\r\n";
	$config .= "define('UC_DBUSER', '$ucdbuser');\r\n";
	$config .= "define('UC_DBPW', '$ucdbpw');\r\n";
	$config .= "define('UC_DBNAME', '$ucdbname');\r\n";
	$config .= "define('UC_DBCHARSET', '$ucdbcharset');\r\n";
	$config .= "define('UC_DBTABLEPRE', '$uctablepre');\r\n";
	$config .= "define('UC_COOKIEPATH', '/');\r\n";
	$config .= "define('UC_COOKIEDOMAIN', '');\r\n";
	$config .= "define('UC_DBCONNECT', 0);\r\n";
	$config .= "define('UC_CHARSET', '".$uccharset."');\r\n";
	$config .= "define('UC_FOUNDERPW', '$pw');\r\n";
	$config .= "define('UC_FOUNDERSALT', '$salt');\r\n";
	$config .= "define('UC_KEY', '$ucauthkey');\r\n";
	$config .= "define('UC_SITEID', '$ucsiteid');\r\n";
	$config .= "define('UC_MYKEY', '$ucmykey');\r\n";
	$config .= "define('UC_DEBUG', false);\r\n";
	$config .= "define('UC_PPP', 20);\r\n";
	$fp = fopen($file, 'w');
	fwrite($fp, $config);
	fclose($fp);
	
}
?>