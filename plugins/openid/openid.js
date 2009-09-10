/**
 * @author sutra
 * @copyright Copyright &copy; 2001-2007, Redv Soft
 * @license http://openid4discuz.redv.com/LICENSE.txt BSD
 */
var OPENID4DISCUZ_COOKIE_EXPIRES = new Date();
OPENID4DISCUZ_COOKIE_EXPIRES.setTime(OPENID4DISCUZ_COOKIE_EXPIRES.getTime() + 365 * (24 * 60 * 60 * 1000)); //+365 day

var OPENID4DISCUZ_OPENID_IDENTIFIER_COOKIE_NAME = "openid4discuz_cookie_openid_identifier";

/**
 * Set openid textbox visiable, while user choosing using openid to login.
 */
function setOpenIdLogin(isOpenIdLogin){
	if (isOpenIdLogin){
		$('username').className = 'openid';
		//$('password3').disabled = true;
		$('password_holder').style.display = 'none';
		$('selecttype').style.display = 'none';
		$('questionid_selectinput').style.display = 'none';
	} else {
		$('username').className = 'txt';
		$('password_holder').style.display = 'block';
		$('selecttype').style.display = 'block';
		$('questionid_selectinput').style.display = 'block';
	}
	$('username').focus();
	
	var saveCookie = true;
	var argv = setOpenIdLogin.arguments;
	var argc = argv.length;
	if (argc >= 2) {
		saveCookie = argv[1];
	}

	if (saveCookie) {
		setCookie("loginfield_openid", isOpenIdLogin, OPENID4DISCUZ_COOKIE_EXPIRES);
	}
}


/*
function setOpenIdLogin(isOpenIdLogin) {
	document.getElementById("username").style.display = (isOpenIdLogin ? "none" : "inline");
	document.getElementById("openid_identifier").style.display = (isOpenIdLogin ? "inline" : "none");
	document.getElementById("password").disabled = isOpenIdLogin;
	document.getElementById("password").style.backgroundColor = isOpenIdLogin ? '#eee' : '';
	document.getElementById("questionid").disabled = isOpenIdLogin;
	document.getElementById("answer").disabled = isOpenIdLogin;
	document.getElementById("answer").style.backgroundColor = isOpenIdLogin ? '#eee' : '';
	
	if (isOpenIdLogin) {
		document.getElementById("openid_identifier").focus();
	} else {
		document.getElementById("username").focus();
	}

	var saveCookie = true;
	var argv = setOpenIdLogin.arguments;
	var argc = argv.length;
	if (argc >= 2) {
		saveCookie = argv[1];
	}

	if (saveCookie) {
		setCookie("loginfield_openid", isOpenIdLogin, OPENID4DISCUZ_COOKIE_EXPIRES);
	}
}
*/

/**
 * Save openid identifier that user inputted into cookie while the login form be submmitted.
 */
function submitLogin() {
	if ($('loginfield').value == 'openid') {
		var un = $('username').value;
		if (un.indexOf('http')==-1) un = 'http://openid.enjoyoung.cn/' + un;
		$('openid_identifier').value = un;
	}
	setCookie(OPENID4DISCUZ_OPENID_IDENTIFIER_COOKIE_NAME,
	document.getElementById("openid_identifier").value, OPENID4DISCUZ_COOKIE_EXPIRES);
}

/**
 * Set the value of the textbox as openid identifier in cookie if present.
 */
function setOpenIDIdentifierFromCookie() {
	var openid_identifier_in_cookie = getCookie(OPENID4DISCUZ_OPENID_IDENTIFIER_COOKIE_NAME);
	if (openid_identifier_in_cookie != null && openid_identifier_in_cookie != "") {
		var argv = setOpenIDIdentifierFromCookie.arguments;
		var argc = setOpenIDIdentifierFromCookie.arguments.length;
		var textboxId = (argc > 0) ? argv[0] : "openid_identifier";
		document.getElementById(textboxId).value = openid_identifier_in_cookie;
	}
}

/**
 * Initialize the login form by the value saved in cookie.
 */
function initLoginFormFromCookie() {
	// About querystring, see http://adamv.com/dev/javascript/querystring
	// Parse the current page's querystring
	var qs = new Querystring();
	var bind = false;
	if (qs.get("openid_action") === "bind") {
		bind = true;
	}

	// Retrive openid identifier from cookie.
	setOpenIDIdentifierFromCookie();

	// Set openid login.
	if (!bind && getCookie("loginfield_openid") == "true") {
		document.getElementById("loginfield_openid").checked = true;
	}
	setOpenIdLogin(document.getElementById("loginfield_openid").checked, !bind);
}