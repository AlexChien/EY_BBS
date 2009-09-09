/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: iframe.js 17449 2008-12-22 08:58:53Z cnteacher $
*/

function refreshmain(e) {
	e = e ? e : window.event;
	actualCode = e.keyCode ? e.keyCode : e.charCode;
	if(actualCode == 116 && parent.main) {
		parent.main.location.reload();
		if(document.all) {
			e.keyCode = 0;
			e.returnValue = false;
		} else {
			e.cancelBubble = true;
			//e.calcelable = true;
			e.preventDefault();
		}
	}
}

_attachEvent(document.documentElement, "keydown", refreshmain);