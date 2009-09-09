<? if(!defined('UC_ROOT')) exit('Access Denied');?>
<? include $this->gettpl('header');?>

<script src="js/common.js" type="text/javascript"></script>

<div class="container">
	<? if($updated) { ?>
		<div class="correctmsg"><p>更新成功。</p></div>
	<? } elseif($a == 'register') { ?>
		<div class="note fixwidthdec"><p class="i">允许/禁止的 Email 地址只需填写 Email 的域名部分，每行一个域名，例如 @hotmail.com</p></div>
	<? } ?>
	<? if($a == 'ls') { ?>
		<div class="mainbox nomargin">
			<form action="admin.php?m=setting&a=ls" method="post">
				<input type="hidden" name="formhash" value="<?=FORMHASH?>">
				<table class="opt">
					<tr>
						<th colspan="2">日期格式:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="dateformat" value="<?=$dateformat?>" /></td>
						<td>使用 yyyy(yy) 表示年，mm 表示月，dd 表示天。如 yyyy-mm-dd 表示 2000-1-1</td>
					</tr>
					<tr>
						<th colspan="2">时间格式:</th>
					</tr>
					<td>
						<input type="radio" id="hr24" class="radio" name="timeformat" value="1" <?=$timeformat[1]?> /><label for="hr24">24 小时制</label>
						<input type="radio" id="hr12" class="radio" name="timeformat" value="0" <?=$timeformat[0]?> /><label for="hr12">12 小时制</label>
					</td>
					</tr>
					<tr>
						<th colspan="2">时区:</th>
					</tr>
					<tr>
						<td>
							<select name="timeoffset">
								<option value="-12" <?=$checkarray?>['-12']>(GMT -12:00) Eniwetok, Kwajalein</option>
								<option value="-11" <?=$checkarray?>['-11']>(GMT -11:00) Midway Island, Samoa</option>
								<option value="-10" <?=$checkarray?>['-10']>(GMT -10:00) Hawaii</option>
								<option value="-9" <?=$checkarray?>['-9']>(GMT -09:00) Alaska</option>
								<option value="-8" <?=$checkarray?>['-8']>(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>
								<option value="-7" <?=$checkarray?>['-7']>(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>
								<option value="-6" <?=$checkarray?>['-6']>(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>
								<option value="-5" <?=$checkarray?>['-5']>(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
								<option value="-4" <?=$checkarray?>['-4']>(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>
								<option value="-3.5" <?=$checkarray?>['-3.5']>(GMT -03:30) Newfoundland</option>
								<option value="-3" <?=$checkarray?>['-3']>(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>
								<option value="-2" <?=$checkarray?>['-2']>(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>
								<option value="-1" <?=$checkarray?>['-1']>(GMT -01:00) Azores, Cape Verde Islands</option>
								<option value="0" <?=$checkarray['0']?>>(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
								<option value="1" <?=$checkarray['1']?>>(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>
								<option value="2" <?=$checkarray['2']?>>(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>
								<option value="3" <?=$checkarray['3']?>>(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>
								<option value="3.5" <?=$checkarray['3.5']?>>(GMT +03:30) Tehran</option>
								<option value="4" <?=$checkarray['4']?>>(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>
								<option value="4.5" <?=$checkarray['4.5']?>>(GMT +04:30) Kabul</option>
								<option value="5" <?=$checkarray['5']?>>(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
								<option value="5.5" <?=$checkarray['5.5']?>>(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>
								<option value="5.75" <?=$checkarray['5.75']?>>(GMT +05:45) Katmandu</option>
								<option value="6" <?=$checkarray['6']?>>(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>
								<option value="6.5" <?=$checkarray['6.5']?>>(GMT +06:30) Rangoon</option>
								<option value="7" <?=$checkarray['7']?>>(GMT +07:00) Bangkok, Hanoi, Jakarta</option>
								<option value="8" <?=$checkarray['8']?>>(GMT +08:00) &#x5317;&#x4eac;(Beijing), Hong Kong, Perth, Singapore, Taipei</option>
								<option value="9" <?=$checkarray['9']?>>(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
								<option value="9.5" <?=$checkarray['9.5']?>>(GMT +09:30) Adelaide, Darwin</option>
								<option value="10" <?=$checkarray['10']?>>(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>
								<option value="11" <?=$checkarray['11']?>>(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>
								<option value="12" <?=$checkarray['12']?>>(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>
							</select>
						</td>
						<td>默认为: GMT +08:00</td>
					</tr>

					<tr>
						<th colspan="2">发短消息最少注册天数:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pmsendregdays" value="<?=$pmsendregdays?>" /></td>
						<td>注册天数少于次设置的，不允许发送短消息，0为不限制，此举为了限制机器人发广告</td>
					</tr>

					<tr>
						<th colspan="2">同一用户在 24 小时允许发送短消息的最大数目:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pmlimit1day" value="<?=$pmlimit1day?>" /></td>
						<td>	同一用户在 24 小时内可以发送的短消息的极限，建议在 30 - 100 范围内取值，0 为不限制，此举为了限制通过机器批量发广告</td>
					</tr>

					<tr>
						<th colspan="2">发短消息灌水预防:</th>
					</tr>
					<tr>
						<td><input type="text" class="txt" name="pmfloodctrl" value="<?=$pmfloodctrl?>" /></td>
						<td>两次发短消息间隔小于此时间，单位秒，0 为不限制，此举为了限制通过机器批量发广告</td>
					</tr>

					<tr>
						<th colspan="2">启用短消息中心:</th>
					</tr>
					<tr>
					<td>
						<input type="radio" id="pmcenteryes" class="radio" name="pmcenter" value="1" <?=$pmcenter[1]?> onclick="$('hidden1').style.display=''"  /><label for="pmcenteryes">是</label>
						<input type="radio" id="pmcenterno" class="radio" name="pmcenter" value="0" <?=$pmcenter[0]?> onclick="$('hidden1').style.display='none'" /><label for="pmcenterno">否</label>
					</td>
					<td>是否启用短消息中心功能，不影响使用短消息接口应用程序的使用</td>
					</tr>
					<tbody id="hidden1" <?=$pmcenter['display']?>>
					<tr>
						<th colspan="2">开启发送短消息验证码:</th>
					</tr>
					<tr>
						<td>
							<input type="radio" id="sendpmseccodeyes" class="radio" name="sendpmseccode" value="1" <?=$sendpmseccode[1]?> /><label for="sendpmseccodeyes">是</label>
							<input type="radio" id="sendpmseccodeno" class="radio" name="sendpmseccode" value="0" <?=$sendpmseccode[0]?> /><label for="sendpmseccodeno">否</label>
						</td>
						<td>是否开启短消息中心发送短消息验证码，可以防止使用机器狂发短消息</td>
					</tr>
					</tbody>
				</table>
				<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
			</form>
		</div>
	<? } elseif($a == 'register') { ?>
		<div class="mainbox nomargin">
			<form action="admin.php?m=setting&a=register" method="post">
				<input type="hidden" name="formhash" value="<?=FORMHASH?>">
				<table class="opt">
					<tr>
						<th colspan="2">是否允许同一 Email 地址注册多个用户:</th>
					</tr>
					<tr>
						<td>
							<input type="radio" id="yes" class="radio" name="doublee" value="1" <?=$doublee[1]?> /><label for="yes">是</label>
							<input type="radio" id="no" class="radio" name="doublee" value="0" <?=$doublee[0]?> /><label for="no">否</label>
						</td>
					</tr>
					<tr>
						<th colspan="2">允许的 Email 地址:</th>
					</tr>
					<tr>
						<td><textarea class="area" name="accessemail"><?=$accessemail?></textarea></td>
						<td valign="top">只允许使用这些域名结尾的 Email 地址注册。</td>
					</tr>
					<tr>
						<th colspan="2">禁止的 Email 地址:</th>
					</tr>
					<tr>
						<td><textarea class="area" name="censoremail"><?=$censoremail?></textarea></td>
						<td valign="top">禁止使用这些域名结尾的 Email 地址注册。</td>
					</tr>
					<tr>
						<th colspan="2">禁止的用户名:</th>
					</tr>
					<tr>
						<td><textarea class="area" name="censorusername"><?=$censorusername?></textarea></td>
						<td valign="top">可以设置通配符，每个关键字一行，可使用通配符 "*" 如 "*版主*"(不含引号)。</td>
					</tr>
				</table>
				<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
			</form>
		</div>
	<? } else { ?>
		<div class="mainbox nomargin">
			<form action="admin.php?m=setting&a=mail" method="post">
				<input type="hidden" name="formhash" value="<?=FORMHASH?>">
				<table class="opt">
					<tr>
						<th colspan="2">邮件来源地址:</th>
					</tr>
					<tr>
						<td><input name="maildefault" value="<?=$maildefault?>" type="text"></td>
						<td>当发送邮件不指定邮件来源时，默认使用此地址作为邮件来源</td>
					<tr>
						<th colspan="2">邮件发送方式:</th>
					</tr>
					<tr>
						<td colspan="2">
							<label><input class="radio" name="mailsend" value="1"<? if($mailsend == 1) { ?> checked="checked"<? } ?> onclick="$('hidden1').style.display = 'none';$('hidden2').style.display = 'none';" type="radio"> 通过 PHP 函数的 sendmail 发送(推荐此方式)</label><br />
							<label><input class="radio" name="mailsend" value="2"<? if($mailsend == 2) { ?> checked="checked"<? } ?> onclick="$('hidden1').style.display = '';$('hidden2').style.display = '';" type="radio"> 通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)</label><br />
							<label><input class="radio" name="mailsend" value="3"<? if($mailsend == 3) { ?> checked="checked"<? } ?> onclick="$('hidden1').style.display = '';$('hidden2').style.display = 'none';" type="radio"> 通过 PHP 函数 SMTP 发送 Email(仅 Windows 主机下有效, 不支持 ESMTP 验证)</label>
						</td>
					</tr>
					<tbody id="hidden1"<? if($mailsend == 1) { ?> style="display:none"<? } ?>>
					<tr>
						<td colspan="2">SMTP 服务器:</td>
					</tr>
					<tr>
						<td>
							<input name="mailserver" value="<?=$mailserver?>" class="txt" type="text">
						</td>
						<td valign="top">设置 SMTP 服务器的地址</td>
					</tr>
					<tr>
						<td colspan="2">SMTP 端口:</td>
					</tr>
					<tr>
						<td>
							<input name="mailport" value="<?=$mailport?>" type="text">
						</td>
						<td valign="top">设置 SMTP 服务器的端口，默认为 25</td>
					</tr>
					</tbody>
					<tbody id="hidden2"<? if($mailsend == 1 || $mailsend == 3) { ?> style="display:none"<? } ?>>
					<tr>
						<td colspan="2">SMTP 服务器要求身份验证:</td>
					</tr>
					<tr>
						<td>
							<label><input type="radio" class="radio" name="mailauth"<? if($mailauth == 1) { ?> checked="checked"<? } ?> value="1" />是</label>
							<label><input type="radio" class="radio" name="mailauth"<? if($mailauth == 0) { ?> checked="checked"<? } ?> value="0" />否</label>
						</td>
						<td valign="top">如果 SMTP 服务器要求身份验证才可以发信，请选择“是”</td>
					</tr>
					<tr>
						<td colspan="2">发信人邮件地址:</td>
					</tr>
					<tr>
						<td>
							<input name="mailfrom" value="<?=$mailfrom?>" class="txt" type="text">
						</td>
						<td valign="top">如果需要验证, 必须为本服务器的邮件地址。邮件地址中如果要包含用户名，格式为“username &lt;user@domain.com&gt;”</td>
					</tr>
					<tr>
						<td colspan="2">SMTP 身份验证用户名:</td>
					</tr>
					<tr>
						<td>
							<input name="mailauth_username" value="<?=$mailauth_username?>" type="text">
						</td>
						<td valign="top"></td>
					</tr>
					<tr>
						<td colspan="2">SMTP 身份验证密码:</td>
					</tr>
					<tr>
						<td>
							<input name="mailauth_password" value="<?=$mailauth_password?>" type="text">
						</td>
						<td valign="top"></td>
					</tr>
					</tbody>
					<tr>
						<th colspan="2">邮件头的分隔符:</th>
					</tr>
					<tr>
						<td>
							<label><input class="radio" name="maildelimiter"<? if($maildelimiter == 1) { ?> checked="checked"<? } ?> value="1" type="radio"> 使用 CRLF 作为分隔符</label><br />
							<label><input class="radio" name="maildelimiter"<? if($maildelimiter == 0) { ?> checked="checked"<? } ?> value="0" type="radio"> 使用 LF 作为分隔符</label><br />
							<label><input class="radio" name="maildelimiter"<? if($maildelimiter == 2) { ?> checked="checked"<? } ?> value="2" type="radio"> 使用 CR 作为分隔符</label>
						</td>
						<td>
							请根据您邮件服务器的设置调整此参数
						</td>
					</tr>
					<tr>
						<th colspan="2">收件人地址中包含用户名:</th>
					</tr>
					<tr>
						<td>
							<label><input type="radio" class="radio" name="mailusername"<? if($mailusername == 1) { ?> checked="checked"<? } ?> value="1" />是</label>
							<label><input type="radio" class="radio" name="mailusername"<? if($mailusername == 0) { ?> checked="checked"<? } ?> value="0" />否</label>
						</td>
						<td valign="top">选择“是”将在收件人的邮件地址中包含论坛用户名</td>
					</tr>
					<tr>
						<th colspan="2">屏蔽邮件发送中的全部错误提示:</th>
					</tr>
					<tr>
						<td>
							<label><input type="radio" class="radio" name="mailsilent"<? if($mailsilent == 1) { ?> checked="checked"<? } ?> value="1" />是</label>
							<label><input type="radio" class="radio" name="mailsilent"<? if($mailsilent == 0) { ?> checked="checked"<? } ?> value="0" />否</label>
						</td>
						<td valign="top">&nbsp;</td>
					</tr>
				</table>
				<div class="opt"><input type="submit" name="submit" value=" 提 交 " class="btn" tabindex="3" /></div>
			</form>
		</div>
	<? } ?>
</div>

<? include $this->gettpl('footer');?>