/**
 * Get cookie routine by Shelley Powers
 * (shelley.powers@ne-dev.com)
 */
function getCookie(name) {
	var search = name + "="
	var returnValue = null;
	if (document.cookie.length > 0) {
		offset = document.cookie.indexOf(search)
		// if cookie exists
		if (offset != -1) {
			offset += search.length
			// set index of beginning of value
			end = document.cookie.indexOf(";", offset);
			// set index of end of cookie value
			if (end == -1) end = document.cookie.length;
			returnValue=unescape(document.cookie.substring(offset, end))
		}
	}
	return returnValue;
}

/**
 * set cookie
 * @name cookie name
 * @value cookie value
 * @expires cookie expires
 * @path cookie path
 * @domain cookie domain
 * @secure is cookie secure
 */
function setCookie (name, value) {
	var argv = setCookie.arguments;
	var argc = setCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape (value) +
		((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
		((path == null) ? "" : ("; path=" + path)) +
		((domain == null) ? "" : ("; domain=" + domain)) +
		((secure == true) ? "; secure" : "");
}