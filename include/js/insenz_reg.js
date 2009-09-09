
function validate() {
	return checkusername($('form1').username.value)
		&& checkpassword($('form1').password.value, $('form1').password2.value)
		&& checkname($('form1').name.value)
		&& checkidcard($('form1').idcard.value)
		&& checkemail($('form1').email1.value, 'email1')
		&& ($('form1').email2.value ? checkemail($('form1').email2.value, 'email2') : true)
		&& checkqq($('form1').qq.value)
		&& checktel($('form1').tel1.value, $('form1').tel2.value, $('form1').tel3.value, '电话号码')
		&& ($('form1').fax2.value ? checktel($('form1').fax1.value, $('form1').fax2.value, $('form1').fax3.value, '传真号码') : true)
		&& ($('form1').msn.value ? checkemail($('form1').msn.value, 'msn') : true)
		&& checkmobile($('form1').mobile.value)
		&& checkcpc($('form1').country.value, $('form1').province.value, $('form1').city.value)
		&& checkaddress($('form1').address.value)
		&& checkpostcode($('form1').postcode.value)
		&& checkemail($('form1').alipay.value, 'alipay');
}

function checkusername(username) {
	username = trim(username);
	if(mb_strlen(username) < 4 || mb_strlen(username) > 20) {
		return dalert('用户名长度不少于 4 字节不超过 20 字节！请重新填写', $('form1').username);
	} else if(!preg_match(/^\w+$/i, username)) {
		return dalert('用户名不合法！请重新填写', $('form1').username);
	}
	return true;
}

function checkpassword(password, password2) {
	if(mb_strlen(password) < 6 || mb_strlen(password) > 20) {
		return dalert('密码长度范围 6~20！请重新填写', $('form1').password);
	} else if(!preg_match(/^\w+$/i, password)) {
		return dalert('密码不能包含特殊字符！请重新填写', $('form1').password);
	} else if(password != password2) {
		return dalert('两次输入的密码不一致！请重新填写', $('form1').password2);
	}
	return true;
}

function checkname(name) {
	name = trim(name);
	if(mb_strlen(name) < 4 || mb_strlen(name) > 30) {
		return dalert('姓名长度不少于 4 字节不超过 30 字节！请重新填写', $('form1').name);
	}
	return true;
}

function checkemail(email, en) {
	email = trim(email);
	if(mb_strlen(email) < 7 || !preg_match(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/, email)) {
		var s = {'email1':'E-mail','email2':'备用 E-mail','msn':'MSN','alipay':'支付宝帐号'};
		return dalert(s[en] + ' 不合法！请重新填写', en == 'email1' ? $('form1').email1 : (en == 'email2' ? $('form1').email2 : (en == 'msn' ? $('form1').msn : $('form1').alipay)));
	}
	return true;
}

function checkidcard(idcard) {
	idcard = trim(idcard);
	len = mb_strlen(idcard);
	if(len == 18 && preg_match(/^\d{17}[\dX]$/i, idcard)) {
		return true;
	}
	return dalert('身份证号码不合法！请重新填写', $('form1').idcard);
}

function checktel(tel1, tel2, tel3, telname) {
	if(!preg_match(/^\d{2,4}$/, tel1) || !preg_match(/^\d{5,10}$/, tel2) || (tel3 && tel3 != '分机号码' && !preg_match(/^\d{1,5}$/, tel3))) {
		return dalert(telname + ' 不合法！请重新填写', $('form1').tel1);
	}
	return true;
}

function checkqq(qq) {
	if(!(preg_match(/^([0-9]+)$/, qq) && mb_strlen(qq) >= 5 && mb_strlen(qq) <= 12)) {
		return dalert('QQ 号码不合法！请重新填写', $('form1').qq);
	}
	return true;
}

function checkmobile(mobile) {
	if(!preg_match(/^1(3|5)\d{9}$/, mobile)) {
		return dalert('手机号码不合法！请重新填写', $('form1').mobile);
	}
	return true;
}

function checkcpc(country, province, city) {
	country = parseInt(country);
	if(country < 10000 || country > 70300) {
		return dalert('请选择国家！', $('form1').country);
	}
	province = parseInt(province);
	if(country == 10000 && (province < 10100 || province > 13100)) {
		return dalert('请选择省份！', $('form1').province);
	}
	city = parseInt(city);
	if(country == 10000 && (city < 10101 || city > 13107)) {
		return dalert('请选择城市！', $('form1').city);
	}
	return true;
}

function checkaddress(address) {
	address = trim(address);
	if(mb_strlen(address) < 8) {
		return dalert('请填写您的真实地址！', $('form1').address);
	}
	return true;
}

function checkpostcode(postcode) {
	if(!preg_match(/^\d{6}$/, postcode)) {
		return dalert('邮政编码不合法！请重新填写', $('form1').postcode);
	}
	return true;
}

function preg_match(re, str) {
	var matches = re.exec(str);
	return matches != null;
}

function dalert(str, focusobj) {
	alert(str);
	focusobj.focus();
	return false;
}