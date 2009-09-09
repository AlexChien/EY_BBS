/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: post.js 17506 2009-01-05 04:57:02Z monkey $
*/

var postSubmited = false;

function AddText(txt) {
	obj = $('postform').message;
	selection = document.selection;
	checkFocus();
	if(!isUndefined(obj.selectionStart)) {
		var opn = obj.selectionStart + 0;
		obj.value = obj.value.substr(0, obj.selectionStart) + txt + obj.value.substr(obj.selectionEnd);
	} else if(selection && selection.createRange) {
		var sel = selection.createRange();
		sel.text = txt;
		sel.moveStart('character', -strlen(txt));
	} else {
		obj.value += txt;
	}
}

function checkFocus() {
	var obj = typeof wysiwyg == 'undefined' || !wysiwyg ? $('postform').message : editwin;
	if(!obj.hasfocus) {
		obj.focus();
	}
}

function ctlent(event) {
	if(postSubmited == false && (event.ctrlKey && event.keyCode == 13) || (event.altKey && event.keyCode == 83) && $('postsubmit')) {
		if(in_array($('postsubmit').name, ['topicsubmit', 'replysubmit', 'editsubmit']) && !validate($('postform'))) {
			doane(event);
			return;
		}
		postSubmited = true;
		$('postsubmit').disabled = true;
		$('postform').submit();
	}
}

function ctltab(event) {
	if(event.keyCode == 9) {
		doane(event);
	}
}

function checklength(theform) {
	var message = wysiwyg ? html2bbcode(getEditorContents()) : (!theform.parseurloff.checked ? parseurl(theform.message.value) : theform.message.value);
	var showmessage = postmaxchars != 0 ? '系统限制: ' + postminchars + ' 到 ' + postmaxchars + ' 字节' : '';
	alert('\n当前长度: ' + mb_strlen(message) + ' 字节\n\n' + showmessage);
}

if(!tradepost) {
	var tradepost = 0;
}

function validate(theform) {
	var message = wysiwyg ? html2bbcode(getEditorContents()) : (!theform.parseurloff.checked ? parseurl(theform.message.value) : theform.message.value);
	if(($('postsubmit').name != 'replysubmit' && !($('postsubmit').name == 'editsubmit' && !isfirstpost) && theform.subject.value == "") || !sortid && !special && message == "") {
		dalert('请完成标题或内容栏。');
		return false;
	} else if(mb_strlen(theform.subject.value) > 80) {
		dalert('您的标题超过 80 个字符的限制。');
		return false;
	}
	if(tradepost) {
		if(theform.item_name.value == '') {
			dalert('对不起，请输入商品名称。');
			return false;
		} else if(theform.item_price.value == '') {
			dalert('对不起，请输入商品现价。');
			return false;
		} else if(!parseInt(theform.item_price.value)) {
			dalert('对不起，商品现价必须为有效数字。');
			return false;
		} else if(theform.item_costprice.value != '' && !parseInt(theform.item_costprice.value)) {
			dalert('对不起，商品原价必须为有效数字。');
			return false;
		} else if(theform.item_number.value != '0' && !parseInt(theform.item_number.value)) {
			dalert('对不起，商品数量必须为数字。');
			theform.item_number.focus();
			return false;
		}
	}
	if(in_array($('postsubmit').name, ['topicsubmit', 'editsubmit'])) {
		if(theform.typeid && (theform.typeid.options && theform.typeid.options[theform.typeid.selectedIndex].value == 0) && typerequired) {
			dalert('请选择主题对应的分类。');
			return false;
		}
		if(special == 3 && isfirstpost) {
			if(theform.rewardprice.value == "") {
				dalert('对不起，请输入悬赏积分。');
				return false;
			}
		} else if(special == 4 && isfirstpost) {
			if(theform.activityclass.value == "") {
				dalert('对不起，请输入活动所属类别。');
				return false;
			} else if($('starttimefrom_0').value == "" && $('starttimefrom_1').value == "") {
				dalert('对不起，请输入活动开始时间。');
				return false;
			} else if(theform.activityplace.value == "") {
				dalert('对不起，请输入活动地点。');
				return false;
			}
		} else if(special == 6 && isfirstpost && $('postsubmit').name == 'topicsubmit') {
			if(theform.vid.value == '') {
				dalert('您还没有上传视频，或者视频还在上传中，请稍侯重试。');
				return false;
			} else if(theform.vsubject.value == '') {
				dalert('没有添加视频主题。');
				return false;
			} else if(theform.vtag.value == '') {
				dalert('没有填写视频标签');
				return false;
			} else if($('vclass') == '') {
				dalert('请您选择视频所属分类。');
				return false;
			}
		}
	}

	if(!disablepostctrl && !sortid && !special && ((postminchars != 0 && mb_strlen(message) < postminchars) || (postmaxchars != 0 && mb_strlen(message) > postmaxchars))) {
		alert('您的帖子长度不符合要求。\n\n当前长度: ' + mb_strlen(message) + ' 字节\n系统限制: ' + postminchars + ' 到 ' + postmaxchars + ' 字节');
		return false;
	}
	theform.message.value = message;
	if($('postsubmit').name == 'editsubmit') {
		if(!infloat) {
			return true;
		} else {
			ajaxpost('postform', 'returnmessage', 'returnmessage', 'onerror', $('postsubmit'));
		}
	} else if(in_array($('postsubmit').name, ['topicsubmit', 'replysubmit'])) {
		seccheck(theform, seccodecheck, secqaacheck);
		return false;
	}
}

function seccheck(theform, seccodecheck, secqaacheck) {
	if(seccodecheck || secqaacheck) {
		var url = 'ajax.php?inajax=1&action=';
		if(seccodecheck) {
			var x = new Ajax();
			x.get(url + 'checkseccode&seccodeverify=' + (is_ie && document.charset == 'utf-8' ? encodeURIComponent($('seccodeverify').value) : $('seccodeverify').value), function(s) {
				if(s != 'succeed') {
					dalert(s);
					$('seccodeverify').focus();
				} else if(secqaacheck) {
					checksecqaa(url, theform);
				} else {
					postsubmit(theform);
				}
			});
		} else if(secqaacheck) {
			checksecqaa(url, theform);
		}
	} else {
		postsubmit(theform);
	}
}

function checksecqaa(url, theform) {
	var x = new Ajax();
	var secanswer = $('secanswer').value;
	secanswer = is_ie && document.charset == 'utf-8' ? encodeURIComponent(secanswer) : secanswer;
	x.get(url + 'checksecanswer&secanswer=' + secanswer, function(s) {
		if(s != 'succeed') {
			dalert(s);
			$('secanswer').focus();
		} else {
			postsubmit(theform);
		}
	});
}

function postsubmit(theform) {
	theform.replysubmit ? theform.replysubmit.disabled = true : (theform.editsubmit ? theform.editsubmit.disabled = true : theform.topicsubmit.disabled = true);
	if(!infloat) {
		theform.submit();
	} else {
		ajaxpost('postform', 'returnmessage', 'returnmessage', 'onerror', $('postsubmit'));
	}
}

function loadData(quiet) {
	var message = '';
	if(is_ie) {
		try {
			textobj.load('Discuz!');
			var oXMLDoc = textobj.XMLDocument;
			var nodes = oXMLDoc.documentElement.childNodes;
			message = nodes.item(nodes.length - 1).getAttribute('message');
		} catch(e) {}
	} else if(window.sessionStorage) {
		try {
			message = sessionStorage.getItem('Discuz!');
		} catch(e) {}
	}
	message = message.toString();

	if(in_array((message = trim(message)), ['', 'null', 'false', null, false])) {
		if(!quiet) {
			alert('没有可以恢复的数据！');
		}
		return;
	}

	if(!quiet && !confirm('此操作将覆盖当前帖子内容，确定要恢复数据吗？')) {
		return;
	}

	var formdata = message.split(/\x09\x09/);
	for(var i = 0; i < $('postform').elements.length; i++) {
		var el = $('postform').elements[i];
		if(el.name != '' && (el.tagName == 'TEXTAREA' || el.tagName == 'INPUT' && (el.type == 'text' || el.type == 'checkbox' || el.type == 'radio'))) {
			for(var j = 0; j < formdata.length; j++) {
				var ele = formdata[j].split(/\x09/);
				if(ele[0] == el.name) {
					elvalue = !isUndefined(ele[3]) ? ele[3] : '';
					if(ele[1] == 'INPUT') {
						if(ele[2] == 'text') {
							el.value = elvalue;
						} else if((ele[2] == 'checkbox' || ele[2] == 'radio') && ele[3] == el.value) {
							el.checked = true;
							evalevent(el);
						}
					} else if(ele[1] == 'TEXTAREA') {
						if(ele[0] == 'message') {
							if(typeof wysiwyg == 'undefined' || !wysiwyg) {
								textobj.value = elvalue;
							} else {
								editdoc.body.innerHTML = bbcode2html(elvalue);
							}
						} else {
							el.value = elvalue;
						}
					}
					break
				}
			}
		}
	}
}

var autosaveDatai, autosaveDatatime;
function autosaveData(op) {
	var autosaveInterval = 60;
	obj = $(editorid + '_cmd_autosave');
	if(op) {
		if(op == 2) {
			saveData(wysiwyg ? editdoc.body.innerHTML : textobj.value);
		} else {
			setcookie('disableautosave', '', -2592000);
		}
		autosaveDatatime = autosaveInterval;
		autosaveDatai = setInterval(function() {
			autosaveDatatime--;
			if(autosaveDatatime == 0) {
				saveData(wysiwyg ? editdoc.body.innerHTML : textobj.value);
				autosaveDatatime = autosaveInterval;
			}
			if($('autsavet')) {
				$('autsavet').innerHTML = '(' + autosaveDatatime + '秒' + ')';
			}
		}, 1000);
		obj.onclick = function() { autosaveData(0); }
	} else {
		setcookie('disableautosave', 1, 2592000);
		clearInterval(autosaveDatai);
		$('autsavet').innerHTML = '(已停止)';
		obj.onclick = function() { autosaveData(1); }
	}
}

function evalevent(obj) {
	var script = obj.parentNode.innerHTML;
	var re = /onclick="(.+?)["|>]/ig;
	var matches = re.exec(script);
	if(matches != null) {
		matches[1] = matches[1].replace(/this\./ig, 'obj.');
		eval(matches[1]);
	}
}

function saveData(data, del) {
	if(!data && isUndefined(del)) {
		return;
	}
	if(typeof wysiwyg != 'undefined' && typeof editorid != 'undefined' && $(editorid + '_mode') && $(editorid + '_mode').value == 1) {
		data = html2bbcode(data);
	}
	var formdata = '';
	if(isUndefined(del)) {
		for(var i = 0; i < $('postform').elements.length; i++) {
			var el = $('postform').elements[i];
			if(el.name != '' && (el.tagName == 'TEXTAREA' || el.tagName == 'INPUT' && (el.type == 'text' || el.type == 'checkbox' || el.type == 'radio')) && el.name.substr(0, 6) != 'attach') {
				var elvalue = el.name == 'message' ? data : el.value;
				if((el.type == 'checkbox' || el.type == 'radio') && !el.checked) {
					continue;
				}
				formdata += el.name + String.fromCharCode(9) + el.tagName + String.fromCharCode(9) + el.type + String.fromCharCode(9) + elvalue + String.fromCharCode(9, 9);
			}
		}
	}
	if(is_ie) {
		try {
			var oXMLDoc = textobj.XMLDocument;
			var root = oXMLDoc.firstChild;
			if(root.childNodes.length > 0) {
				root.removeChild(root.firstChild);
			}
			var node = oXMLDoc.createNode(1, 'POST', '');
			var oTimeNow = new Date();
			oTimeNow.setHours(oTimeNow.getHours() + 24);
			textobj.expires = oTimeNow.toUTCString();
			node.setAttribute('message', formdata);
			oXMLDoc.documentElement.appendChild(node);
			textobj.save('Discuz!');
		} catch(e) {}
	} else if(window.sessionStorage) {
		try {
			sessionStorage.setItem('Discuz!', formdata);
		} catch(e) {}
	}
}

function deleteData() {
	if(is_ie) {
		saveData('', 'delete');
	} else if(window.sessionStorage) {
		try {
			sessionStorage.removeItem('Discuz!');
		} catch(e) {}
	}
}

function setCaretAtEnd() {
	if(typeof wysiwyg != 'undefined' && wysiwyg) {
		editdoc.body.innerHTML += '';
	} else {
		editdoc.value += '';
	}
}

function relatekw(subject, message, recall) {
	if(isUndefined(recall)) recall = '';
	if(isUndefined(subject) || subject == -1) subject = $('subject').value;
	if(isUndefined(message) || message == -1) message = getEditorContents();
	subject = (is_ie && document.charset == 'utf-8' ? encodeURIComponent(subject) : subject);
	message = (is_ie && document.charset == 'utf-8' ? encodeURIComponent(message) : message);
	message = message.replace(/&/ig, '', message).substr(0, 500);
	ajaxget('relatekw.php?subjectenc=' + subject + '&messageenc=' + message, 'tagselect', '', '', '', recall);
}

function dalert(s) {
	if(!infloat) {
		alert(s);
	} else {
		$('returnmessage').className = 'onerror';
		$('returnmessage').innerHTML = s;
		messagehandle();
	}
}

function pagescrolls(op) {
	if(!infloat && op.substr(0, 6) == 'credit') {
		window.open('faq.php?action=credits&fid=' + fid);
		return;
	}
	switch(op) {
		case 'credit1':hideMenu();$('moreconf').style.display = 'none';$('extcreditbox1').innerHTML = $('extcreditbox').innerHTML;pagescroll.left();break;
		case 'credit2':$('moreconf').style.display = 'none';$('extcreditbox2').innerHTML = $('extcreditbox').innerHTML;pagescroll.left();break;
		case 'credit3':hideMenu();$('moreconf').style.display = 'none';$('extcreditbox3').innerHTML = $('extcreditbox').innerHTML;pagescroll.left();break;
		case 'return':if(!Editorwin) {hideMenu();$('custominfoarea').style.display=$('more_2').style.display='none';pagescroll.up(1, '$(\'more_1\').style.display=\'\'');}break;
		case 'creditreturn':pagescroll.right(1, '$(\'moreconf\').style.display = \'\';');break;
		case 'swf':hideMenu();$('moreconf').style.display = 'none';swfHandler(3);break;
		case 'swfreturn':$('swfbox').style.display = 'none';if(!Editorwin) {pagescroll.left(1, '$(\'moreconf\').style.display = \'\';');}swfHandler(2);break;
		case 'more':hideMenu();pagescroll.down(1, '$(\'more_1\').style.display=$(\'more_2\').style.display=$(\'custominfoarea\').style.display=\'none\'');break;
		case 'editorreturn':$('more_1').style.display='none';pagescroll.up(1, '$(\'more_2\').style.display=$(\'custominfoarea\').style.display=\'\'');break;
		case 'editor':$('more_1').style.display='none';pagescroll.down(1, '$(\'more_2\').style.display=\'\';$(\'custominfoarea\').style.display=\'\'');break;
	}
}

function switchicon(iconid, obj) {
	$('iconid').value = iconid;
	$('icon_img').src = obj.src;
	hideMenu();
}

var swfuploaded = 0;
function swfHandler(action) {
	if(action == 1) {
		swfuploaded = 1;
	} else if(action == 2) {
		if(Editorwin || !infloat) {
			swfuploadwin();
		} else {
			$('swfbox').style.display = 'none';
			pagescroll.left(1, '$(\'moreconf\').style.display=\'\';');
		}
		if(swfuploaded) {
			swfattachlistupdate(action);
		}
	} else if(action == 3) {
		swfuploaded = 0;
		pagescroll.right(1, '$(\'swfuploadbox\').style.display = $(\'swfbox\').style.display = \'\';');
	}
}

function swfattachlistupdate(action) {
	ajaxget('ajax.php?action=swfattachlist', 'swfattachlist', 'swfattachlist', 'swfattachlist', null, '$(\'uploadlist\').scrollTop=10000');
	attachlist('open');
	$('postform').updateswfattach.value = 1;
}

function appendreply() {
	newpos = fetchOffset($('post_new'));
	document.documentElement.scrollTop = newpos['top'];
	$('post_new').style.display = '';
	$('post_new').id = '';
	div = document.createElement('div');
	div.id = 'post_new';
	div.style.display = 'none';
	div.className = '';
	$('postlistreply').appendChild(div);
	$('postform').replysubmit.disabled = false;
	creditnoticewin();
}

var Editorwin = 0;
function resizeEditorwin() {
	var obj = $('resizeEditorwin');
	floatwin('size_' + editoraction);
	$('editorbox').style.height = $('floatlayout_' + editoraction).style.height = $('floatwin_' + editoraction).style.height;
	if(!Editorwin) {
		obj.className = 'float_min';
		obj.title = obj.innerHTML = '还原大小';
		$('editorbox').style.width = $('floatlayout_' + editoraction).style.width = (parseInt($('floatwin_' + editoraction).style.width) - 10)+ 'px';
		$('editorbox').style.left = '0px';
		$('editorbox').style.top = '0px';
		$('swfuploadbox').style.display = $('custominfoarea').style.display = $('creditlink').style.display = $('morelink').style.display = 'none';
		if(wysiwyg) {
			$('e_iframe').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 150)+ 'px';
		}
		$('e_textarea').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 150)+ 'px';
		attachlist('close');
		Editorwin = 1;
	} else {
		obj.className = 'float_max';
		obj.title = obj.innerHTML = '最大化';
		$('editorbox').style.width = $('floatlayout_' + editoraction).style.width = '600px';
		$('swfuploadbox').style.display = $('custominfoarea').style.display = $('creditlink').style.display = $('morelink').style.display = '';
		if(wysiwyg) {
			$('e_iframe').style.height = '';
		}
		$('e_textarea').style.height = '';
		swfuploadwin();
		Editorwin = 0;
	}
}

function closeEditorwin() {
	if(Editorwin) {
		resizeEditorwin();
	}
	floatwin('close_' + editoraction);
}

function editorwindowopen(url) {
	data = wysiwyg ? editdoc.body.innerHTML : textobj.value;
	saveData(data);
	url += '&cedit=' + (data !== '' ? 'yes' : 'no');
	window.open(url);
}

function swfuploadwin() {
	if(Editorwin) {
		if($('swfuploadbox').style.display == 'none') {
			$('swfuploadbox').className = 'floatbox floatbox1 floatboxswf floatwin swfwin';
			$('swfuploadbox').style.position = 'absolute';
			width = (parseInt($('floatlayout_' + editoraction).style.width) - 604) / 2;
			$('swfuploadbox').style.left = width + 'px';
			$('swfuploadbox').style.display = $('swfclosebtn').style.display = $('swfbox').style.display = '';

		} else {
			$('swfuploadbox').className = 'floatbox floatbox1 floatboxswf';
			$('swfuploadbox').style.position = $('swfuploadbox').style.left = '';
			$('swfuploadbox').style.display = $('swfclosebtn').style.display = 'none';
		}
	} else {
		if(infloat) {
			pagescrolls('swf');
		} else {
			if($('swfuploadbox').style.display == 'none') {
				$('swfuploadbox').style.display = $('swfbox').style.display = $('swfclosebtn').style.display = '';
			} else {
				$('swfuploadbox').style.display = $('swfbox').style.display = $('swfclosebtn').style.display = 'none';
			}
		}
	}
}


var editbox = editwin = editdoc = editcss = null;
var cursor = -1;
var stack = new Array();
var initialized = false;

function newEditor(mode, initialtext) {
	wysiwyg = parseInt(mode);
	if(!(is_ie || is_moz || (is_opera >= 9))) {
		allowswitcheditor = wysiwyg = 0;
	}
	if(!allowswitcheditor) {
		$(editorid + '_switcher').style.display = 'none';
	}

	$(editorid + '_cmd_table').style.display = wysiwyg ? '' : 'none';

	if(wysiwyg) {
		if($(editorid + '_iframe')) {
			editbox = $(editorid + '_iframe');
		} else {
			var iframe = document.createElement('iframe');
			editbox = textobj.parentNode.appendChild(iframe);
			editbox.id = editorid + '_iframe';
		}

		editwin = editbox.contentWindow;
		editdoc = editwin.document;
		writeEditorContents(isUndefined(initialtext) ?  textobj.value : initialtext);
	} else {
		editbox = editwin = editdoc = textobj;
		if(!isUndefined(initialtext)) {
			writeEditorContents(initialtext);
		}
		addSnapshot(textobj.value);
	}
	setEditorEvents();
	initEditor();
}

function initEditor() {
	var buttons = $(editorid + '_controls').getElementsByTagName('a');
	for(var i = 0; i < buttons.length; i++) {
		if(buttons[i].id.indexOf(editorid + '_cmd_') != -1) {
			buttons[i].href = 'javascript:;';
			buttons[i].onclick = function(e) {discuzcode(this.id.substr(this.id.lastIndexOf('_cmd_') + 5))};
		} else if(buttons[i].id == editorid + '_popup_media') {
			buttons[i].href = 'javascript:;';
			buttons[i].onclick = function(e) {discuzcode('media')};
		} else if(buttons[i].id.indexOf(editorid + '_popup_') != -1) {
			buttons[i].href = 'javascript:;';
			buttons[i].onclick = function(e) {InFloat = InFloat_Editor;showMenu(this.id, true, 0, 2)};
		}
	}
	setUnselectable($(editorid + '_controls'));
	textobj.onkeydown = function(e) {ctlent(e ? e : event)};
}

function setUnselectable(obj) {
	if(is_ie && is_ie > 4 && typeof obj.tagName != 'undefined') {
		if(obj.hasChildNodes()) {
			for(var i = 0; i < obj.childNodes.length; i++) {
				setUnselectable(obj.childNodes[i]);
			}
		}
		obj.unselectable = 'on';
	}
}

function writeEditorContents(text) {
	if(wysiwyg) {
		if(text == '' && is_moz) {
			text = '<br />';
		}
		if(initialized && !(is_moz && is_moz >= 3)) {
			editdoc.body.innerHTML = text;
		} else {
			editdoc.designMode = 'on';
			editdoc = editwin.document;
			editdoc.open('text/html', 'replace');
			editdoc.write(text);
			editdoc.close();
			editdoc.body.contentEditable = true;
			initialized = true;
		}
	} else {
		textobj.value = text;
	}

	setEditorStyle();

}

function getEditorContents() {
	return wysiwyg ? editdoc.body.innerHTML : editdoc.value;
}

function setEditorStyle() {
	if(wysiwyg) {
		textobj.style.display = 'none';
		editbox.style.display = '';
		editbox.className = textobj.className;

		var headNode = editdoc.getElementsByTagName("head")[0];
		if(!headNode.getElementsByTagName('link').length) {
			editcss = editdoc.createElement('link');
			editcss.type = 'text/css';
			editcss.rel = 'stylesheet';
			editcss.href = editorcss;
			headNode.appendChild(editcss);
		}

		if(is_moz || is_opera) {
			editbox.style.border = '0px';
		} else if(is_ie) {
			editdoc.body.style.border = '0px';
			editdoc.body.addBehavior('#default#userData');
		}
		editbox.style.width = textobj.style.width;
		editbox.style.height = textobj.style.height;
		editdoc.firstChild.style.background = 'none';
		editdoc.body.style.backgroundColor = TABLEBG;
		editdoc.body.style.textAlign = 'left';
		editdoc.body.id = 'wysiwyg';
		if(is_ie) {
			try{$('subject').focus();} catch(e) {editwin.focus();}
		}
	} else {
		var iframe = textobj.parentNode.getElementsByTagName('iframe')[0];
		if(iframe) {
			textobj.style.display = '';
			textobj.style.width = iframe.style.width;
			textobj.style.height = iframe.style.height;
			iframe.style.display = 'none';
		}
		if(is_ie) {
			try{$('subject').focus();} catch(e) {textobj.focus();}
		}
	}
}

function setEditorEvents() {
	if(wysiwyg) {
		if(is_moz || is_opera) {
			editwin.addEventListener('focus', function(e) {this.hasfocus = true;}, true);
			editwin.addEventListener('blur', function(e) {this.hasfocus = false;}, true);
			editwin.addEventListener('keydown', function(e) {ctlent(e);ctltab(e);}, true);
		} else {
			if(editdoc.attachEvent) {
				editdoc.body.attachEvent("onkeydown", ctlent);
				editdoc.body.attachEvent("onkeydown", ctltab);
			}
		}
	}
	editwin.onfocus = function(e) {this.hasfocus = true;};
	editwin.onblur = function(e) {this.hasfocus = false;};
}

function wrapTags(tagname, useoption, selection) {
	if(isUndefined(selection)) {
		var selection = getSel();
		if(selection === false) {
			selection = '';
		} else {
			selection += '';
		}
	}

	if(useoption !== false) {
		var opentag = '[' + tagname + '=' + useoption + ']';
	} else {
		var opentag = '[' + tagname + ']';
	}

	var closetag = '[/' + tagname + ']';
	var text = opentag + selection + closetag;

	insertText(text, strlen(opentag), strlen(closetag), in_array(tagname, ['code', 'quote', 'free', 'hide']) ? true : false);
}

function applyFormat(cmd, dialog, argument) {
	if(wysiwyg) {
		editdoc.execCommand(cmd, (isUndefined(dialog) ? false : dialog), (isUndefined(argument) ? true : argument));
		return;
	}
	switch(cmd) {
		case 'bold':
		case 'italic':
		case 'underline':
			wrapTags(cmd.substr(0, 1), false);
			break;
		case 'justifyleft':
		case 'justifycenter':
		case 'justifyright':
			wrapTags('align', cmd.substr(7));
			break;
		case 'floatleft':
		case 'floatright':
			wrapTags('float', cmd.substr(5));
			break;
		case 'indent':
			wrapTags(cmd, false);
			break;
		case 'fontname':
			wrapTags('font', argument);
			break;
		case 'fontsize':
			wrapTags('size', argument);
			break;
		case 'forecolor':
			wrapTags('color', argument);
			break;
		case 'createlink':
			var sel = getSel();
			if(sel) {
				wrapTags('url', argument);
			} else {
				wrapTags('url', argument, argument);
			}
			break;
		case 'insertimage':
			wrapTags('img', false, argument);
			break;
	}
}

function getCaret() {
	if(wysiwyg) {
		var obj = editdoc.body;
		var s = document.selection.createRange();
		s.setEndPoint("StartToStart", obj.createTextRange());
		return s.text.replace(/\r?\n/g, ' ').length;
	} else {
		var obj = editbox;
		var wR = document.selection.createRange();
		obj.select();
		var aR = document.selection.createRange();
		wR.setEndPoint("StartToStart", aR);
		var len = wR.text.replace(/\r?\n/g, ' ').length;
		wR.collapse(false);
		wR.select();
		return len;
	}
}

function setCaret(pos) {
	var obj = wysiwyg ? editdoc.body : editbox;
	var r = obj.createTextRange();
	r.moveStart('character', pos);
	r.collapse(true);
	r.select();
}

function insertlink(cmd) {
	var sel;
	if(is_ie) {
		sel = wysiwyg ? editdoc.selection.createRange() : document.selection.createRange();
		var pos = getCaret();
	}
	var selection = sel ? (wysiwyg ? sel.htmlText : sel.text) : getSel();
	if(cmd == 'createlink' && is_ie && wysiwyg && selection === undefined) {
		applyFormat("createlink", true, null);
		return;
	}
	var ctrlid = editorid + '_cmd_' + cmd;
	var tag = cmd == 'createlink' ? 'url' : 'email';
	var str = (tag == 'url' ? '请输入链接的地址:' : '请输入此链接的邮箱地址:') + '<br /><input type="text" id="' + ctrlid + '_param_1" style="width: 98%" value="" class="txt" />';
	var div = editorMenu(ctrlid, str);
	$(ctrlid + '_param_1').focus();
	$(ctrlid + '_param_1').onkeydown = editorMenuEvent_onkeydown;
	$(ctrlid + '_submit').onclick = function() {
		checkFocus();
		if(is_ie) {
			setCaret(pos);
		}
		var input = $(ctrlid + '_param_1').value;
		if(input != '') {
			var v = selection ? selection : input;
			var href = tag != 'email' && /^(www\.)/.test(input) ? 'http://' + input : input;
			var text = wysiwyg ? ('<a href="' + (tag == 'email' ? 'mailto:' : '') + href + '">' + v + '</a>') : '[' + tag + '=' + href + ']' + v + '[/' + tag + ']';
			var closetaglen = tag == 'email' ? 8 : 6;
			if(wysiwyg) insertText(text, text.length - v.length, 0, (selection ? true : false), sel);
			else insertText(text, text.length - v.length - closetaglen, closetaglen, (selection ? true : false), sel);
		}
		hideMenu();
		div.parentNode.removeChild(div);
	}
}

function insertimage() {
	InFloat = InFloat_Editor;
	if(is_ie) $(editorid + '_cmd_insertimage_param_url').pos = getCaret();
	showMenu(editorid + '_cmd_insertimage', true, 0, 3);
}

function insertimagesubmit() {
	checkFocus();
	if(is_ie) setCaret($(editorid + '_cmd_insertimage_param_url').pos);
	if(wysiwyg) {
		insertText('<img src='+$(editorid + '_cmd_insertimage_param_url').value+' border=0 /> ', false);
	} else {
		insertText('[img]'+$(editorid + '_cmd_insertimage_param_url').value+'[/img]');
	}
	hideMenu();
	$(editorid + '_cmd_insertimage_param_url').value = '';
}

function insertSmiley(smilieid) {
	checkFocus();
	var src = $('smilie_' + smilieid).src;
	var code = $('smilie_' + smilieid).alt;
	if(typeof wysiwyg != 'undefined' && wysiwyg && allowsmilies && (!$('smileyoff') || $('smileyoff').checked == false)) {
		if(is_moz) {
			applyFormat('InsertImage', false, src);
			var smilies = editdoc.body.getElementsByTagName('img');
			for(var i = 0; i < smilies.length; i++) {
				if(smilies[i].src == src && smilies[i].getAttribute('smilieid') < 1) {
					smilies[i].setAttribute('smilieid', smilieid);
					smilies[i].setAttribute('border', "0");
				}
			}
		} else {
			insertText('<img src="' + src + '" border="0" smilieid="' + smilieid + '" alt="" /> ', false);
		}
	} else {
		code += ' ';
		AddText(code);
	}
	hideMenu();
}

function editorMenuEvent_onkeydown(e) {
	e = e ? e : event;
	try {
		obj = is_ie ? event.srcElement : e.target;
		var ctrlid = obj.id.substr(0, obj.id.lastIndexOf('_param_'));
		if((obj.type == 'text' && e.keyCode == 13) || (obj.type == 'textarea' && e.ctrlKey && e.keyCode == 13)) {
			$(ctrlid + '_submit').click();
			doane(e);
		} else if(e.keyCode == 27) {
			hideMenu();
			$(ctrlid + '_menu').parentNode.removeChild($(ctrlid + '_menu'));
		}
	} catch(e) {}
}

function customTags(tagname, params) {
	var sel;
	if(is_ie) {
		sel = wysiwyg ? editdoc.selection.createRange() : document.selection.createRange();
		var pos = getCaret();
	}
	var selection = sel ? (wysiwyg ? sel.htmlText : sel.text) : getSel();
	var opentag = '[' + tagname + ']';
	var closetag = '[/' + tagname + ']';
	var haveSel = selection == null || selection == false || in_array(trim(selection), ['', 'null', 'undefined', 'false']) ? 0 : 1;
	if(params == 1 && haveSel) {
		return insertText((opentag + selection + closetag), strlen(opentag), strlen(closetag), true, sel);
	}
	var ctrlid = editorid + '_cmd_custom' + params + '_' + tagname;
	var promptlang = custombbcodes[tagname]['prompt'].split("\t");
	var str = '';
	for(var i = 1; i <= params; i++) {
		if(i != params || !haveSel) {
			str += (promptlang[i - 1] ? promptlang[i - 1] : '请输入第 ' + i + ' 个参数:') + '<br /><input type="text" id="' + ctrlid + '_param_' + i + '" style="width: 98%" value="" class="txt" />' + (i < params ? '<br />' : '');
		}
	}
	var div = editorMenu(ctrlid, str);
	$(ctrlid + '_param_1').focus();
	for(var i = 1; i <= params; i++) {if(i != params || !haveSel) $(ctrlid + '_param_' + i).onkeydown = editorMenuEvent_onkeydown;}
	$(ctrlid + '_submit').onclick = function() {
		var first = $(ctrlid + '_param_1').value;
		if($(ctrlid + '_param_2')) var second = $(ctrlid + '_param_2').value;
		if($(ctrlid + '_param_3')) var third = $(ctrlid + '_param_3').value;
		checkFocus();
		if(is_ie) {
			setCaret(pos);
		}
		if((params == 1 && first) || (params == 2 && first && (haveSel || second)) || (params == 3 && first && second && (haveSel || third))) {
			var text;
			if(params == 1) {
				text = first;
			} else if(params == 2) {
				text = haveSel ? selection : second;
				opentag = '[' + tagname + '=' + first + ']';
			} else {
				text = haveSel ? selection : third;
				opentag = '[' + tagname + '=' + first + ',' + second + ']';
			}
			insertText((opentag + text + closetag), strlen(opentag), strlen(closetag), true, sel);
		}
		hideMenu();
		div.parentNode.removeChild(div);
	};
}

function editorMenu(ctrlid, str) {
	var div = document.createElement('div');
	div.id = ctrlid + '_menu';
	div.style.display = 'none';
	div.className = 'popupmenu_popup popupfix';
	div.style.width = '300px';
	$(editorid + '_controls').appendChild(div);
	div.innerHTML = '<div class="popupmenu_option" unselectable="on">' + str + '<br /><center><input type="button" id="' + ctrlid + '_submit" value="提交" /> &nbsp; <input type="button" onClick="hideMenu();try{div.parentNode.removeChild(' + div.id + ')}catch(e){}" value="取消" /></center></div>';
	InFloat = InFloat_Editor;
	showMenu(ctrlid, true, 0, 3);
	return div;
}

function discuzcode(cmd, arg) {
	if(cmd != 'redo') {
		addSnapshot(getEditorContents());
	}

	checkFocus();

	if(in_array(cmd, ['quote', 'code', 'free', 'hide'])) {
		var sel;
		if(is_ie) {
			sel = wysiwyg ? editdoc.selection.createRange() : document.selection.createRange();
			var pos = getCaret();
		}
		var selection = sel ? (wysiwyg ? sel.htmlText : sel.text) : getSel();
		var opentag = '[' + cmd + ']';
		var closetag = '[/' + cmd + ']';
		if(cmd != 'hide' && selection) {
			return insertText((opentag + selection + closetag), strlen(opentag), strlen(closetag), true, sel);
		}
		var ctrlid = editorid + '_cmd_' + cmd;
		var str = '';
		lang['e_quote'] = '请输入要插入的引用';
		lang['e_code'] = '请输入要插入的代码';
		lang['e_free'] = '请输入要插入的免费信息';
		lang['e_hide'] = '请输入要插入的隐藏内容';
		if(cmd != 'hide' || !selection) {
			str += lang['e_' + cmd] + ':<br /><textarea id="' + ctrlid + '_param_1" style="width: 98%" cols="50" rows="5"></textarea>';
		}
		str += cmd == 'hide' ? '<br /><input type="radio" name="' + ctrlid + '_radio" id="' + ctrlid + '_radio_1" class="txt" checked="checked" />只有当浏览者回复本帖时才显示<br /><input type="radio" name="' + ctrlid + '_radio" id="' + ctrlid + '_radio_2" class="txt" />只有当浏览者积分高于 <input type="text" size="3" id="' + ctrlid + '_param_2" class="txt" /> 时才显示' : '';
		var div = editorMenu(ctrlid, str);
		$(ctrlid + '_param_' + (cmd == 'hide' && selection ? 2 : 1)).focus();
		$(ctrlid + '_param_' + (cmd == 'hide' && selection ? 2 : 1)).onkeydown = editorMenuEvent_onkeydown;
		$(ctrlid + '_submit').onclick = function() {
			checkFocus();
			if(is_ie) {
				setCaret(pos);
			}
			if(cmd == 'hide' && $(ctrlid + '_radio_2').checked) {
				var mincredits = parseInt($(ctrlid + '_param_2').value);
				opentag = mincredits > 0 ? '[hide=' + mincredits + ']' : '[hide]';
			}
			var text = selection ? selection : $(ctrlid + '_param_1').value;
			if(wysiwyg) {
				if(cmd == 'code') {
					text = preg_replace(['<', '>'], ['&lt;', '&gt;'], text);
				}
				text = text.replace(/\r?\n/g, '<br />');
			}
			text = opentag + text + closetag;
			insertText(text, strlen(opentag), strlen(closetag), false, sel);
			hideMenu();
			div.parentNode.removeChild(div);
		}
		return;
	} else if(cmd.substr(0, 6) == 'custom') {
		var ret = customTags(cmd.substr(8), cmd.substr(6, 1));
	} else if(!wysiwyg && cmd == 'removeformat') {
		var simplestrip = new Array('b', 'i', 'u');
		var complexstrip = new Array('font', 'color', 'size');

		var str = getSel();
		if(str === false) {
			return;
		}
		for(var tag in simplestrip) {
			str = stripSimple(simplestrip[tag], str);
		}
		for(var tag in complexstrip) {
			str = stripComplex(complexstrip[tag], str);
		}
		insertText(str);
	} else if(!wysiwyg && cmd == 'undo') {
		addSnapshot(getEditorContents());
		moveCursor(-1);
		if((str = getSnapshot()) !== false) {
			editdoc.value = str;
		}
	} else if(!wysiwyg && cmd == 'redo') {
		moveCursor(1);
		if((str = getSnapshot()) !== false) {
			editdoc.value = str;
		}
	} else if(!wysiwyg && in_array(cmd, ['insertorderedlist', 'insertunorderedlist'])) {
		var listtype = cmd == 'insertorderedlist' ? '1' : '';
		var opentag = '[list' + (listtype ? ('=' + listtype) : '') + ']\n';
		var closetag = '[/list]';

		if(txt = getSel()) {
			var regex = new RegExp('([\r\n]+|^[\r\n]*)(?!\\[\\*\\]|\\[\\/?list)(?=[^\r\n])', 'gi');
			txt = opentag + trim(txt).replace(regex, '$1[*]') + '\n' + closetag;
			insertText(txt, strlen(txt), 0);
		} else {
			insertText(opentag + closetag, opentag.length, closetag.length);

			while(listvalue = prompt('输入一个列表项目.\r\n留空或者点击取消完成此列表.', '')) {
				if(is_opera > 8) {
					listvalue = '\n' + '[*]' + listvalue;
					insertText(listvalue, strlen(listvalue) + 1, 0);
				} else {
					listvalue = '[*]' + listvalue + '\n';
					insertText(listvalue, strlen(listvalue), 0);
				}
			}
		}
	} else if(!wysiwyg && cmd == 'outdent') {
		var sel = getSel();
		sel = stripSimple('indent', sel, 1);
		insertText(sel);
	} else if(cmd == 'createlink') {
		insertlink('createlink');
	} else if(!wysiwyg && cmd == 'unlink') {
		var sel = getSel();
		sel = stripSimple('url', sel);
		sel = stripComplex('url', sel);
		insertText(sel);
	} else if(cmd == 'email') {
		insertlink('email');
	} else if(cmd == 'insertimage') {
		insertimage();
	} else if(cmd == 'media') {
		insertmedia();
	} else if(cmd == 'table') {
		if(wysiwyg) {
			var selection = getSel();
			if(is_ie) {
				var pos = getCaret();
			}
			var ctrlid = editorid + '_cmd_table';
			var str = '<p>表格行数: <input type="text" id="' + ctrlid + '_param_rows" size="2" value="2" class="txt" /> &nbsp; 表格列数: <input type="text" id="' + ctrlid + '_param_columns" size="2" value="2" class="txt" /></p><p>表格宽度: <input type="text" id="' + ctrlid + '_param_width" size="2" value="" class="txt" /> &nbsp; 背景颜色: <input type="text" id="' + ctrlid + '_param_bgcolor" size="2" class="txt" /></p>';
			var div = editorMenu(ctrlid, str);
			$(ctrlid + '_param_rows').focus();
			var params = ['rows', 'columns', 'width', 'bgcolor'];
			for(var i = 0; i < 4; i++) {$(ctrlid + '_param_' + params[i]).onkeydown = editorMenuEvent_onkeydown;}
			$(ctrlid + '_submit').onclick = function() {
				checkFocus();
				if(is_ie) {
					setCaret(pos);
				}
				var rows = $(ctrlid + '_param_rows').value;
				var columns = $(ctrlid + '_param_columns').value;
				var width = $(ctrlid + '_param_width').value;
				var bgcolor = $(ctrlid + '_param_bgcolor').value;
				rows = /^[-\+]?\d+$/.test(rows) && rows > 0 && rows <= 30 ? rows : 2;
				columns = /^[-\+]?\d+$/.test(columns) && columns > 0 && columns <= 30 ? columns : 2;
				width = width.substr(width.length - 1, width.length) == '%' ? (width.substr(0, width.length - 1) <= 98 ? width : '98%') : (width <= 560 ? width : '98%');
				bgcolor = /[\(\)%,#\w]+/.test(bgcolor) ? bgcolor : '';
				var html = '<table cellspacing="0" cellpadding="0" width="' + (width ? width : '50%') + '" class="t_table"' + (bgcolor ? ' bgcolor="' + bgcolor + '"' : '') + '>';
				for (var row = 0; row < rows; row++) {
					html += '<tr>\n';
					for (col = 0; col < columns; col++) {
						html += '<td>&nbsp;</td>\n';
					}
					html+= '</tr>\n';
				}
				html += '</table>\n';
				insertText(html);
				hideMenu();
				div.parentNode.removeChild(div);
			}
		}
		return false;
	} else if(cmd == 'floatleft' || cmd == 'floatright') {
		if(wysiwyg) {
			var selection = getSel();
			if(selection) {
				var ret = insertText('<br style="clear: both"><span style="float: ' + cmd.substr(5) + '">' + selection + '</span>', true);
			}
		} else {
			var ret = applyFormat(cmd, false);
		}
	} else if(cmd == 'loadData') {
		loadData();hideMenu();
	} else if(cmd == 'saveData') {
		autosaveData(2);hideMenu();
	} else if(cmd == 'autosave') {
		if(getcookie('disableautosave')) {
			autosaveData(1);
		} else {
			autosaveData(0);
		}
	} else if(cmd == 'checklength') {
		checklength($('postform'));hideMenu();
	} else if(cmd == 'clearcontent') {
		clearcontent();hideMenu();
	} else {
		try {
			var ret = applyFormat(cmd, false, (isUndefined(arg) ? true : arg));
		} catch(e) {
			var ret = false;
		}
	}

	if(cmd != 'undo') {
		addSnapshot(getEditorContents());
	}
	if(in_array(cmd, ['bold', 'italic', 'underline', 'fontname', 'fontsize', 'forecolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'floatleft', 'floatright', 'removeformat', 'unlink', 'undo', 'redo'])) {
		hideMenu();
	}
	return ret;
}

function getSel() {
	if(wysiwyg) {
		if(is_moz || is_opera) {
			selection = editwin.getSelection();
			checkFocus();
			range = selection ? selection.getRangeAt(0) : editdoc.createRange();
			return readNodes(range.cloneContents(), false);
		} else {
			var range = editdoc.selection.createRange();
			if(range.htmlText && range.text) {
				return range.htmlText;
			} else {
				var htmltext = '';
				for(var i = 0; i < range.length; i++) {
					htmltext += range.item(i).outerHTML;
				}
				return htmltext;
			}
		}
	} else {
		if(!isUndefined(editdoc.selectionStart)) {
			return editdoc.value.substr(editdoc.selectionStart, editdoc.selectionEnd - editdoc.selectionStart);
		} else if(document.selection && document.selection.createRange) {
			return document.selection.createRange().text;
		} else if(window.getSelection) {
			return window.getSelection() + '';
		} else {
			return false;
		}
	}
}

function insertText(text, movestart, moveend, select, sel) {
	if(wysiwyg) {
		if(is_moz || is_opera) {
			applyFormat('removeformat');
			var fragment = editdoc.createDocumentFragment();
			var holder = editdoc.createElement('span');
			holder.innerHTML = text;

			while(holder.firstChild) {
				fragment.appendChild(holder.firstChild);
			}
			insertNodeAtSelection(fragment);
		} else {
			checkFocus();
			if(!isUndefined(editdoc.selection) && editdoc.selection.type != 'Text' && editdoc.selection.type != 'None') {
				movestart = false;
				editdoc.selection.clear();
			}

			if(isUndefined(sel)) {
				sel = editdoc.selection.createRange();
			}

			sel.pasteHTML(text);

			if(text.indexOf('\n') == -1) {
				if(!isUndefined(movestart)) {
					sel.moveStart('character', -strlen(text) + movestart);
					sel.moveEnd('character', -moveend);
				} else if(movestart != false) {
					sel.moveStart('character', -strlen(text));
				}
				if(!isUndefined(select) && select) {
					sel.select();
				}
			}
		}
	} else {
		checkFocus();
		if(!isUndefined(editdoc.selectionStart)) {
			var opn = editdoc.selectionStart + 0;
			editdoc.value = editdoc.value.substr(0, editdoc.selectionStart) + text + editdoc.value.substr(editdoc.selectionEnd);

			if(!isUndefined(movestart)) {
				editdoc.selectionStart = opn + movestart;
				editdoc.selectionEnd = opn + strlen(text) - moveend;
			} else if(movestart !== false) {
				editdoc.selectionStart = opn;
				editdoc.selectionEnd = opn + strlen(text);
			}
		} else if(document.selection && document.selection.createRange) {
			if(isUndefined(sel)) {
				sel = document.selection.createRange();
			}
			sel.text = text.replace(/\r?\n/g, '\r\n');
			if(!isUndefined(movestart)) {
				sel.moveStart('character', -strlen(text) +movestart);
				sel.moveEnd('character', -moveend);
			} else if(movestart !== false) {
				sel.moveStart('character', -strlen(text));
			}
			sel.select();
		} else {
			editdoc.value += text;
		}
	}
}

function stripSimple(tag, str, iterations) {
	var opentag = '[' + tag + ']';
	var closetag = '[/' + tag + ']';

	if(isUndefined(iterations)) {
		iterations = -1;
	}
	while((startindex = stripos(str, opentag)) !== false && iterations != 0) {
		iterations --;
		if((stopindex = stripos(str, closetag)) !== false) {
			var text = str.substr(startindex + opentag.length, stopindex - startindex - opentag.length);
			str = str.substr(0, startindex) + text + str.substr(stopindex + closetag.length);
		} else {
			break;
		}
	}
	return str;
}

function stripComplex(tag, str, iterations) {
	var opentag = '[' + tag + '=';
	var closetag = '[/' + tag + ']';

	if(isUndefined(iterations)) {
		iterations = -1;
	}
	while((startindex = stripos(str, opentag)) !== false && iterations != 0) {
		iterations --;
		if((stopindex = stripos(str, closetag)) !== false) {
			var openend = stripos(str, ']', startindex);
			if(openend !== false && openend > startindex && openend < stopindex) {
				var text = str.substr(openend + 1, stopindex - openend - 1);
				str = str.substr(0, startindex) + text + str.substr(stopindex + closetag.length);
			} else {
				break;
			}
		} else {
			break;
		}
	}
	return str;
}

function stripos(haystack, needle, offset) {
	if(isUndefined(offset)) {
		offset = 0;
	}
	var index = haystack.toLowerCase().indexOf(needle.toLowerCase(), offset);

	return (index == -1 ? false : index);
}

function switchEditor(mode) {
	mode = parseInt(mode);
	if(mode == wysiwyg || !allowswitcheditor)  {
		return;
	}
	if(!mode) {
		var controlbar = $(editorid + '_controls');
		var controls = new Array();
		var buttons = controlbar.getElementsByTagName('a');
		var buttonslength = buttons.length;
		for(var i = 0; i < buttonslength; i++) {
			if(buttons[i].id) {
				controls[controls.length] = buttons[i].id;
			}
		}
		var controlslength = controls.length;
		for(var i = 0; i < controlslength; i++) {
			var control = $(controls[i]);

			if(control.id.indexOf(editorid + '_cmd_') != -1) {
				control.className = control.id.indexOf(editorid + '_cmd_custom') == -1 ? '' : 'plugeditor';
				control.state = false;
				control.mode = 'normal';
			} else if(control.id.indexOf(editorid + '_popup_') != -1) {
				control.state = false;
			}
		}
	}
	cursor = -1;
	stack = new Array();
	var parsedtext = getEditorContents();
	parsedtext = mode ? bbcode2html(parsedtext) : html2bbcode(parsedtext);
	wysiwyg = mode;
	$(editorid + '_mode').value = mode;

	newEditor(mode, parsedtext);
	editwin.focus();
	setCaretAtEnd();
}

function insertNodeAtSelection(text) {
	checkFocus();

	var sel = editwin.getSelection();
	var range = sel ? sel.getRangeAt(0) : editdoc.createRange();
	sel.removeAllRanges();
	range.deleteContents();

	var node = range.startContainer;
	var pos = range.startOffset;

	switch(node.nodeType) {
		case Node.ELEMENT_NODE:
			if(text.nodeType == Node.DOCUMENT_FRAGMENT_NODE) {
				selNode = text.firstChild;
			} else {
				selNode = text;
			}
			node.insertBefore(text, node.childNodes[pos]);
			add_range(selNode);
			break;

		case Node.TEXT_NODE:
			if(text.nodeType == Node.TEXT_NODE) {
				var text_length = pos + text.length;
				node.insertData(pos, text.data);
				range = editdoc.createRange();
				range.setEnd(node, text_length);
				range.setStart(node, text_length);
				sel.addRange(range);
			} else {
				node = node.splitText(pos);
				var selNode;
				if(text.nodeType == Node.DOCUMENT_FRAGMENT_NODE) {
					selNode = text.firstChild;
				} else {
					selNode = text;
				}
				node.parentNode.insertBefore(text, node);
				add_range(selNode);
			}
			break;
	}
}

function add_range(node) {
	checkFocus();
	var sel = editwin.getSelection();
	var range = editdoc.createRange();
	range.selectNodeContents(node);
	sel.removeAllRanges();
	sel.addRange(range);
}

function readNodes(root, toptag) {
	var html = "";
	var moz_check = /_moz/i;

	switch(root.nodeType) {
		case Node.ELEMENT_NODE:
		case Node.DOCUMENT_FRAGMENT_NODE:
			var closed;
			if(toptag) {
				closed = !root.hasChildNodes();
				html = '<' + root.tagName.toLowerCase();
				var attr = root.attributes;
				for(var i = 0; i < attr.length; ++i) {
					var a = attr.item(i);
					if(!a.specified || a.name.match(moz_check) || a.value.match(moz_check)) {
						continue;
					}
					html += " " + a.name.toLowerCase() + '="' + a.value + '"';
				}
				html += closed ? " />" : ">";
			}
			for(var i = root.firstChild; i; i = i.nextSibling) {
				html += readNodes(i, true);
			}
			if(toptag && !closed) {
				html += "</" + root.tagName.toLowerCase() + ">";
			}
			break;

		case Node.TEXT_NODE:
			html = htmlspecialchars(root.data);
			break;
	}
	return html;
}

function moveCursor(increment) {
	var test = cursor + increment;
	if(test >= 0 && stack[test] != null && !isUndefined(stack[test])) {
		cursor += increment;
	}
}

function addSnapshot(str) {
	if(stack[cursor] == str) {
		return;
	} else {
		cursor++;
		stack[cursor] = str;

		if(!isUndefined(stack[cursor + 1])) {
			stack[cursor + 1] = null;
		}
	}
}

function getSnapshot() {
	if(!isUndefined(stack[cursor]) && stack[cursor] != null) {
		return stack[cursor];
	} else {
		return false;
	}
}

function insertmedia() {
	InFloat = InFloat_Editor;
	if(is_ie) $(editorid + '_mediaurl').pos = getCaret();
	showMenu(editorid + '_popup_media', true, 0, 2);
}

function setmediacode(editorid) {
	checkFocus();
	if(is_ie) setCaret($(editorid + '_mediaurl').pos);
	insertText('[media='+$(editorid + '_mediatype').value+
		','+$(editorid + '_mediawidth').value+
		','+$(editorid + '_mediaheight').value+']'+
		$(editorid + '_mediaurl').value+'[/media]');
	$(editorid + '_mediaurl').value = '';
	hideMenu();
}

function setmediatype(editorid) {
	var ext = $(editorid + '_mediaurl').value.lastIndexOf('.') == -1 ? '' : $(editorid + '_mediaurl').value.substr($(editorid + '_mediaurl').value.lastIndexOf('.') + 1, $(editorid + '_mediaurl').value.length).toLowerCase();
	if(ext == 'rmvb') {
		ext = 'rm';
	}
	if($(editorid + '_mediatyperadio_' + ext)) {
		$(editorid + '_mediatyperadio_' + ext).checked = true;
		$(editorid + '_mediatype').value = ext;
	}
}

function clearcontent() {
	if(wysiwyg) {
		editdoc.body.innerHTML = is_moz ? '<br />' : '';
	} else {
		textobj.value = '';
	}
}

var aid = 1;
var attachexts = new Array();
var attachwh = new Array();

function delAttach(id) {
	$('attachbody').removeChild($('localno_' + id).parentNode.parentNode);
	$('attachbtn').removeChild($('attachnew_' + id).parentNode);
	$('attachbody').innerHTML == '' && addAttach();
	$('localimgpreview_' + id) ? document.body.removeChild($('localimgpreview_' + id)) : null;
}

function delSWFAttach(id) {
	$('swfattach_' + id).style.display = 'none';
	$('delswfattach_' + id).checked = true;
}

function delEditAttach(id) {
	$('attach_' + id).style.display = 'none';
	$('delattach_' + id).checked = true;
}

function addAttach() {
	var id = aid;
	var tags, newnode, i;
	newnode = $('attachbtnhidden').firstChild.cloneNode(true);
	tags = newnode.getElementsByTagName('input');
	for(i in tags) {
		if(tags[i].name == 'attach[]') {
			tags[i].id = 'attachnew_' + id;
			tags[i].onchange = function() {insertAttach(id)};
			tags[i].unselectable = 'on';
		}
	}
	$('attachbtn').appendChild(newnode);
	newnode = $('attachbodyhidden').firstChild.cloneNode(true);
	tags = newnode.getElementsByTagName('input');
	for(i in tags) {
		if(tags[i].name == 'localid[]') {
			tags[i].value = id;
		}
	}
	tags = newnode.getElementsByTagName('span');
	for(i in tags) {
		if(tags[i].id == 'localfile[]') {
			tags[i].id = 'localfile_' + id;
		} else if(tags[i].id == 'cpadd[]') {
			tags[i].id = 'cpadd_' + id;
		} else if(tags[i].id == 'cpdel[]') {
			tags[i].id = 'cpdel_' + id;
		} else if(tags[i].id == 'localno[]') {
			tags[i].id = 'localno_' + id;
		} else if(tags[i].id == 'deschidden[]') {
			tags[i].id = 'deschidden_' + id;
		}
	}
	aid++;
	newnode.style.display = 'none';
	$('attachbody').appendChild(newnode);
	$('uploadlist').scrollTop = 10000;
}

function insertAttach(id) {
	var localimgpreview = '';
	var path = $('attachnew_' + id).value;
	var extpos = path.lastIndexOf('.');
	var ext = extpos == -1 ? '' : path.substr(extpos + 1, path.length).toLowerCase();
	var re = new RegExp("(^|\\s|,)" + ext + "($|\\s|,)", "ig");
	var localfile = $('attachnew_' + id).value.substr($('attachnew_' + id).value.replace(/\\/g, '/').lastIndexOf('/') + 1);
	var filename = mb_cutstr(localfile, 30);

	if(path == '') {
		return;
	}
	if(extensions != '' && (re.exec(extensions) == null || ext == '')) {
		alert('对不起，不支持上传此类扩展名的附件。');
		return;
	}
	attachexts[id] = is_ie && is_ie < 8 && in_array(ext, ['gif', 'jpeg', 'jpg', 'png', 'bmp']) ? 2 : 1;

	if(attachexts[id] == 2) {
		$('img_hidden').alt = id;
		$('img_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'image';
		try {
			$('img_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = $('attachnew_' + id).value;
		} catch (e) {
			alert('无效的图片文件。');
			delAttach(id);
			return;
		}
		var wh = {'w' : $('img_hidden').offsetWidth, 'h' : $('img_hidden').offsetHeight};
		var aid = $('img_hidden').alt;
		if(wh['w'] >= 180 || wh['h'] >= 150) {
			wh = attachthumbImg(wh['w'], wh['h'], 180, 150);
		}
		attachwh[id] = wh;
		$('img_hidden').style.width = wh['w']
		$('img_hidden').style.height = wh['h'];
		$('img_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'scale';
		div = document.createElement('div');
		div.id = 'localimgpreview_' + id;
		div.style.display = 'none';
		document.body.appendChild(div);
		div.innerHTML = '<img style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=\'scale\',src=\'' + $('attachnew_' + id).value+'\');width:'+wh['w']+';height:'+wh['h']+'" src=\'images/common/none.gif\' border="0" aid="attach_'+ aid +'" alt="" />';
	}

	$('cpdel_' + id).innerHTML = '<a href="###" class="deloption" onclick="delAttach(' + id + ')">删除</a>';
	$('cpadd_' + id).innerHTML = '<a href="###" title="点击这里将本附件插入帖子内容中当前光标的位置"' + 'onclick="insertAttachtext(' + id + ');return false;">插入</a>';
	$('localfile_' + id).innerHTML = '<span' + (attachexts[id] == 2 ? ' onmouseover="showpreview(this, \'localimgpreview_' + id + '\')" ' : '') + '>' + filename + '</span>';
	$('attachnew_' + id).style.display = 'none';
	$('deschidden_' + id).style.display = '';
	$('deschidden_' + id).title = localfile;
	$('localno_' + id).parentNode.parentNode.style.display = '';
	addAttach();
	attachlist('open');
}

function attachlist(op) {
	if(!op) {
		op = textobj.className == 'autosave' ? 'close' : 'open';
	}
	if(op == 'open') {
		textobj.className = 'autosave';
		if(editbox) {
			editbox.className = 'autosave';
		}
		$('attachlist').style.display = '';
		if(Editorwin) {
			if(wysiwyg) {
				$('e_iframe').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 329)+ 'px';
			}
			$('e_textarea').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 329)+ 'px';
		}
	} else {
		textobj.className = 'autosave max';
		if(editbox) {
			editbox.className = 'autosave max';
		}
		$('attachlist').style.display = 'none';
		if(Editorwin) {
			if(wysiwyg) {
				$('e_iframe').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 150)+ 'px';
			}
			$('e_textarea').style.height = (parseInt($('floatwin_' + editoraction).style.height) - 150)+ 'px';
		}
	}
}

lastshowpreview = null;
function showpreview(ctrlobj, showid) {
	if(lastshowpreview) {
		lastshowpreview.id = '';
	}
	if(!ctrlobj.onmouseout) {
		 ctrlobj.onmouseout = function() { hideMenu(); }
	}
	ctrlobj.id = 'imgpreview';
	lastshowpreview = ctrlobj;
	$('imgpreview_menu').innerHTML = '<table width="100%" height="100%"><tr><td align="center" valign="middle">' + $(showid).innerHTML + '</td></tr></table>';
	InFloat='floatlayout_' + editoraction;
	showMenu('imgpreview', false, 2, 1, 0);
	$('imgpreview_menu').style.top = (parseInt($('imgpreview_menu').style.top) - $('uploadlist').scrollTop) + 'px';
}

function attachpreview(obj, preview, width, height) {
	if(is_ie) {
		$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'image';
		try {
			$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = obj.value;
		} catch (e) {
			alert('无效的图片文件。');
			return;
		}
		var wh = {'w' : $(preview + '_hidden').offsetWidth, 'h' : $(preview + '_hidden').offsetHeight};
		var aid = $(preview + '_hidden').alt;
		if(wh['w'] >= width || wh['h'] >= height) {
			wh = attachthumbImg(wh['w'], wh['h'], width, height);
		}
		$(preview + '_hidden').style.width = wh['w']
		$(preview + '_hidden').style.height = wh['h'];
		$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'scale';
		$(preview).style.width = 'auto';
		$(preview).innerHTML = '<img style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=\'scale\',src=\'' + obj.value+'\');width:'+wh['w']+';height:'+wh['h']+'" src=\'images/common/none.gif\' border="0" alt="" />';
	}
}

function insertAttachtext(id) {
	if(!attachexts[id]) {
		return;
	}
	if(attachexts[id] == 2) {
		wysiwyg ? insertText($('localimgpreview_' + id).innerHTML, false) : AddText('[localimg=' + attachwh[id]['w'] + ',' + attachwh[id]['h'] + ']' + id + '[/localimg]');
	} else {
		wysiwyg ? insertText('[local]' + id + '[/local]', false) : AddText('[local]' + id + '[/local]');
	}
}

function attachthumbImg(w, h, twidth, theight) {
	twidth = !twidth ? thumbwidth : twidth;
	theight = !theight ? thumbheight : theight;
	var x_ratio = twidth / w;
	var y_ratio = theight / h;
	var wh = new Array();
	if((x_ratio * h) < theight) {
		wh['h'] = Math.ceil(x_ratio * h);
		wh['w'] = twidth;
	} else {
		wh['w'] = Math.ceil(y_ratio * w);
		wh['h'] = theight;
	}
	return wh;
}

function attachupdate(aid, ctrlobj) {
	objupdate = $('attachupdate'+aid);
	obj = $('attach'+aid);
	if(!objupdate.innerHTML) {
		obj.style.display = 'none';
		objupdate.innerHTML = '<input type="file" name="attachupdate[paid' + aid + ']">';
		ctrlobj.innerHTML = '取消';
	} else {
		obj.style.display = '';
		objupdate.innerHTML = '';
		ctrlobj.innerHTML = '更新';
	}
}

function insertAttachTag(aid) {
	if(wysiwyg) {
		insertText('[attach]' + aid + '[/attach]', false);
	} else {
		AddText('[attach]' + aid + '[/attach]');
	}
}

function insertAttachimgTag(aid) {
	if(wysiwyg) {
		eval('var attachimg = $(\'preview_' + aid + '\')');
		insertText('<img src="' + attachimg.src + '" border="0" aid="attachimg_' + aid + '" width="180" alt="" />', false);
	} else {
		AddText('[attachimg]' + aid + '[/attachimg]');
	}
}

openEditor();

if(allowpostattach) {
	addAttach();
}