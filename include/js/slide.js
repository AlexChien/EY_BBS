/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: slide.js 16929 2008-11-28 00:59:14Z monkey $
*/

/*
var slideSpeed = 2500;
var slideImgsize = [140,140];
var slideTextBar = 0;
var slideBorderColor = '#C8DCEC';
var slideBgColor = '#FFF';
var slideImgs = new Array();
var slideImgLinks = new Array();
var slideImgTexts = new Array();
var slideSwitchBar = 1;
var slideSwitchColor = 'black';
var slideSwitchbgColor = 'white';
var slideSwitchHiColor = '#C8DCEC';
*/
var slide_curImg = 1;
var slide_timerId = -1;
var slide_interval = slideSpeed;
var slide_imgIsLoaded = false;
var slide_preload = new Array();
var slide_imgsize = new Array();
var slide_inited = 0;
var slide_opacity = 0;
var slide_st;

document.write('<style type="text/css">');
document.write('#slidetable { border: 1px solid ' + slideBorderColor + '; background: ' + slideBgColor + '; width: 100%; }');
document.write('#slidearea { height:' + (slideImgsize[1] + 6) + 'px; overflow: hidden; margin: 0 auto; text-align: center; }');
document.write('#slidefooter { ' + (slideSwitchBar != 0 ? '': '') + 'text-align: center; width:' + slideImgsize[0] + 'px; line-height: 22px; height: 21px; overflow: hidden; }');
if(slideSwitchBar != 0) {
	document.write('#slideswitchtd { padding: 0; }');
	document.write('#slideswitch div { filter: alpha(opacity=90);opacity: 0.9; float: left; width: 17px; height: 12px; font-weight: bold; text-align: center; cursor: pointer; font-size: 9px; padding: 0 0 5px; color: ' + slideSwitchColor + '; border-right: 1px solid ' + slideBorderColor + '; border-top: 1px solid ' + slideBorderColor + '; background-color: ' + slideSwitchbgColor + '; }');
	document.write('#slideswitch div.current { background-color: ' + slideSwitchHiColor + '; }');
}
document.write('</style>');
document.write('<table cellspacing="0" cellpadding="2" id="slidetable"><tr><td id="slidearea" valign="middle"><img src="images/common/none.gif" width="' + slideImgsize[0] + '" height="' + slideImgsize[1] + '" />');

if(slideSwitchBar != 0) {
	document.write('</td></tr><tr><td id="slideswitchtd"><div id="slideswitch">');
	for(i = 1;i < slideImgs.length;i++) {
		document.write('<div id="slide_' + i + '" onclick="slide_forward(' + i + ')">' + i + '</div>');
		document.getElementById('slide_' + i).title = slideImgTexts[i];
	}
	document.write('</div>');
}
document.write('</td></tr></table>');

if(slideTextBar != 0) {
	document.write('<div id="slidefooter"><p id="slidetext"><a href="' + slideImgLinks[slideImgs.length - 1] + '" target="_blank">' + slideImgTexts[slideImgs.length - 1] + '</a></div>')
}

function slide_init() {
	if(slide_inited) {
		return;
	}
	slide_inited = 1;
	slide_imgPreload(1, slideImgs.length - 1);
	slide_changeSlide();
	slide_play();
}

function slide_imgPreload(intPic, intRange) {
	for(var i=intPic; i<(intPic+intRange); i++) {
		slide_preload[i] = new Image();
		slide_preload[i].src = slideImgs[i];
		slide_preload[i].i = i;
		slide_preload[i].onload = function() {
			slide_imgResize(this);
			slide_imgsize[this.i] = 'width="' + this.width + '" height="' + this.height + '" ';
		}
	}
	return false;
}

function slide_imgResize(obj) {
	zr = obj.width / obj.height;
	if(obj.width > slideImgsize[0]) {
		obj.width = slideImgsize[0];
		obj.height = obj.width / zr;
	}
	if(obj.height > slideImgsize[1]) {
		obj.height = slideImgsize[1];
		obj.width = obj.height * zr;
	}
}

function slide_imgLoadNotify(obj, n) {
	if(!slide_imgsize[n]) {
		slide_imgResize(obj);
	}
	slide_imgIsLoaded = true;
}

function slide_changeSlide(n) {
	if(!slide_preload[slide_curImg]) {
		return;
	}
	if(!slide_preload[slide_curImg].complete) {
		return;
	}
	if(slide_opacity <= 100) {
		slide_changeopacity(1, n);
		return;
	}
	slide_imgIsLoaded = false;
	if(slideImgs.length !=0) {
		var slideImage = '<a href="' + slideImgLinks[slide_curImg] + '" target="_blank"><img src="' + slideImgs[slide_curImg] + '" ' + (slide_imgsize[slide_curImg] ? slide_imgsize[slide_curImg] : '') + 'onload="slide_imgLoadNotify(this, ' + slide_curImg + ')" /></a>';
		document.getElementById('slidearea').innerHTML = slideImage ;

		if(slideTextBar != 0) {
			var slideText = '<a href="' + slideImgLinks[slide_curImg] + '" target="_blank">' + slideImgTexts[slide_curImg] + '</a>';
			document.getElementById('slidetext').innerHTML = slideText;
		}
		slide_opacity = 0;
		slide_changeopacity(0);
		if(slideSwitchBar != 0) {
			document.getElementById('slide_' + slide_curImg).className = 'current';
		}
	}
}

function slide_changeopacity(op, n) {
	if(slide_opacity <= 100) {
		slide_opacity += 10;
		setopacity = op ? 100 - slide_opacity : slide_opacity;
		document.getElementById('slidearea').style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + setopacity + ')';
		document.getElementById('slidearea').style.opacity = setopacity / 100;
		slide_st = setTimeout('slide_changeopacity(' + op + ',' + n + ')', op ? 10 : 50);
	} else {
		if(op) {
			slide_changeSlide(n);
		} else {
			slide_opacity = 0;
		}
	}
}

function slide_forward(slide_n) {
	slide_imgIsLoaded = false;
	if(!document.getElementById('slide_' + slide_curImg)) {
		return;
	}
	if(slideSwitchBar != 0) {
		document.getElementById('slide_' + slide_curImg).className = '';
	}
	if(!slide_n) {
		slide_curImg++;
		if(slide_curImg >= slideImgs.length) {
			slide_curImg = 1;
		}
	} else {
		clearInterval(slide_timerId);
		clearTimeout(slide_st);
		slide_timerId = window.setInterval('slide_forward()', slide_interval);
		slide_curImg = slide_n;
	}
	slide_changeSlide();
}

function slide_play() {
	if(slide_timerId == -1) {
		slide_timerId = window.setInterval('slide_forward()', slide_interval);
	}
}

slide_init();