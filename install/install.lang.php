<?php
define('INSTALL_LANG', 'SC_UTF8');

$lang = array
(
	'SC_GBK' => '简体中文版',
	'TC_BIG5' => '繁体中文版',
	'SC_UTF8' => '简体中文 UTF8 版',
	'TC_UTF8' => '繁体中文 UTF8 版',
	'EN_ISO' => 'ENGLISH ISO8859',
	'EN_UTF8' => 'ENGLIST UTF-8',

	'title_install' => 'Discuz! Board 程序安装',

	'username' => '管理员账号:',
	'username_error' => '帐号不能为空，不能包含特殊字符',
	'password' => '管理员密码:',
	'password_error' => '管理员密码不能为空',
	'repeat_password' => '重复密码',
	'repeat_password_error' => '重复密码应当和管理员密码一样',
	'admin_email' => '管理员 Email:',

	'succeed' => '成功',
	'enabled' => '允许',
	'writeable' => '可写',
	'readable' => '可读',
	'unwriteable' => '不可写',
	'yes' => '可',
	'no' => '不可',
	'unlimited' => '不限',
	'support' => '支持',
	'unsupport' => '<span class="redfont">不支持</span>',
	'old_step' => '上一步',
	'new_step' => '填写完毕，进行下一步',
	'tips_message' => '提示信息',
	'return' => '返回',
	'error_message' => '错误信息',
	'message_return' => '点击这里返回',
	'message_forward' => '点击这里继续',
	'error_quit_msg' => '您必须解决以上问题，安装才可以继续',
	'check_pass_next_step' => '检测通过，跳转到下一步',
	'reg_app_to_ucenter_fail' => '将应用注册到用户中心失败。',

	'env_os' => '操作系统',
	'env_php' => 'PHP 版本',
	'env_mysql' => 'MySQL 支持',
	'env_attach' => '附件上传',
	'env_diskspace' => '磁盘空间',
	'env_dir_writeable' => '目录写入',
	'env_comment' => '提示信息',
	'env' => '提示信息',

	'error_env' => '运行环境检测失败',
	'error_unknow_type ' => '程序运行遇到未知性错误，请到 Discuz! 支持论坛寻求支持',
	'error_env_require_comment' => '您当前的服务器环境无法安装或者运行 Discuz!, 请根据以下提示进行修正，然后重新检测',
	'error_db_config' => '数据库信息设置错误',
	'error_admin_config' => '管理员信息设置错误',
	'error_uc_install' => 'UCenter 设置错误',
	'error_config_vars' => 'config.inc.php 错误',
	'error_config_vars_comment' => '<ul><li>config.inc.php 文件是 Discuz! 论坛运行的必须文件<li>您必须将 config.inc.php 放在论坛的根目录，且设置为可读写状态(777)<li>每个版本的 config.inc.php 文件都可能会不同，请保证您上传的版本和论坛的版本是一致的</ul><p><strong>请正确上传后重新刷新本页</strong>',

	'init_log' => '初始化记录',
	'clear_dir' => '清空目录',
	'select_db' => '选择数据库',
	'create_table' => '建立数据表',

	'install_wizard' => '安装向导',
	'current_process' => '当前状态:',
	'show_license' => 'Discuz! 用户许可协议',
	'agreement_yes' => '我同意',
	'agreement_no' => '我不同意',
	'check_config' => '检查配置文件状态',
	'check_catalog_file_name' => '目录文件名称',
	'check_need_status' => '所需状态',
	'check_currently_status' => '当前状态',
	'edit_config' => '浏览/编辑当前配置',
	'variable' => '设置选项',
	'value' => '当前值',
	'comment' => '注释',
	'dbhost' => '数据库服务器:',
	'dbhost_comment' => '数据库服务器地址, 一般为 localhost',
	'dbuser' => '数据库用户名:',
	'dbuser_error' => '连接数据库失败，请检查用户名',
	'dbpw' => '数据库密码:',
	'dbpw_error' => '密码是否正确',
	'dbname' => '数据库名:',
	//'dbname_comment' => '数据库名称',
	'email' => '信箱 Email:',
	'adminemail' => '系统信箱 Email:',
	'adminemail_comment' => '用于发送程序错误报告',
	'tablepre' => '表名前缀:',
	'tablepre_comment' => '同一数据库运行多个论坛时，请修改前缀',
	'tablepre_prompt' => '除非您需要在同一数据库安装多个 Discuz! \n论坛,否则,强烈建议您不要修改表名前缀。',

	'forceinstall' => '发现旧论坛数据:',
	'forceinstall_error' => '当前数据库当中已经安装过 Discuz! 论坛，您可以修改“表名前缀”来避免删除旧的数据，或者选择强制安装。强制安装会删除旧数据，且无法恢复',
	'agree_forceinstall' => '<span class="red"><b>我要删除数据，强制安装 !!!</b></span>',
	'config_nonexistence' => '您的 config.inc.php 不存在, 无法继续安装, 请用 FTP 将该文件上传后再试。',
	'error_discuz_data_exist' => '当前数据库中已经安装过 Discuz! 论坛，继续安装会清除原有论坛数据。如果您确认要这么做，请选择强制安装。否则请您更换"数据库"或者修改"表名前缀"',

	'recheck_config' => '重新检查设置',
	'check_env' => '检查当前服务器环境',
	'env_required' => 'Discuz! 所需配置',
	'env_best' => 'Discuz! 最佳配置',
	'env_current' => '当前服务器',
	'install_note' => '安装向导提示',
	'add_admin' => '设置管理员账号',
	'start_install' => '开始安装 Discuz!',
	'dbname_invalid' => '数据库名为空，请填写数据库名称',
	'admin_username_invalid' => '非法用户名，用户名长度不应当超过 15 个英文字符，且不能包含特殊字符，一般是中文，字母或者数字',
	'admin_password_invalid' => '密码和上面不一致，请重新输入',
	'admin_email_invalid' => 'Email 地址错误，此邮件地址已经被使用或者格式无效，请更换为其他地址',
	'admin_invalid' => '您的信息管理员信息没有填写完整，请仔细填写每个项目',
	'admin_exist_password_error' => '该用户已经存在，如果您要设置此用户为论坛的管理员，请正确输入该用户的密码，或者请更换论坛管理员的名字',

	'config_comment' => '请在下面填写您的数据库账号信息, 通常情况下不需要修改红色选项内容。',
	'config_unwriteable' => '安装向导无法写入配置文件, 请设置 config.inc.php 程序属性为可写状态(777)',
	'threadcache_unwriteable' => '主题缓存目录 <strong>./forumdata/threadcaches 属性非 777 或无法写入，在线编辑模板功能将无法使用',
	'ucclientcache_unwriteable' => '缓存目录 <strong>./uc_client/data/cache</strong> 无法写入, 请设置属性为可写状态 (777)',

	'database_errno_2003' => '无法连接数据库，请检查数据库是否启动，数据库服务器地址是否正确',
	'database_errno_1044' => '无法创建新的数据库，请检查数据库名称填写是否正确',
	'database_errno_1045' => '无法连接数据库，请检查数据库用户名或者密码是否正确',

	'dbpriv_createtable' => '没有CREATE TABLE权限，无法安装论坛',
	'dbpriv_insert' => '没有INSERT权限，无法安装论坛',
	'dbpriv_select' => '没有SELECT权限，无法安装论坛',
	'dbpriv_update' => '没有UPDATE权限，无法安装论坛',
	'dbpriv_delete' => '没有DELETE权限，无法安装论坛',
	'dbpriv_droptable' => '没有DROP TABLE权限，无法安装论坛',

	'php_version_406' => '服务器 PHP 版本小于 4.0.6, 无法使用 Discuz!',
	'php_version_430' => '服务器 PHP 版本小于 4.3.0, 无法使用 Discuz!。',
	'attach_enabled' => '允许/最大尺寸 ',
	'attach_enabled_info' => '您可以上传附件的最大尺寸: ',
	'attach_disabled' => '不允许上传附件',
	'attach_disabled_info' => '附件上传或相关操作被服务器禁止。',
	'mysql_version_323' => '服务器 MySQL 版本低于 3.23，安装无法继续进行',
	'mysql_unsupport' => '服务器不支持 MySql 数据库，无法安装论坛程序',
	'template_unwriteable' => '模板目录 <strong>./templates 属性非 777 或无法写入，在线编辑模板功能将无法使用',
	'attach_unwriteable' => '附件目录 <strong>默认是 ./attachments</strong> 无法写入，附件上传功能将无法使用',
	'avatar_unwriteable' => '自定义头像目录 <strong>./customavatars</strong> 或无法写入，上传头像功能将无法使用',
	'forumdata_unwriteable' => '数据目录 <strong>./forumdata</strong> 无法写入，请设置属性为可写状态 (777)',
	'ftemplates_unwriteable' => '数据目录 <strong>./forumdata/templates</strong> 无法写入，请设置属性为可写状态 (777)',
	'cache_unwriteable' => '缓存目录 <strong>./forumdata/cache</strong> 无法写入，请设置属性为可写状态 (777)。',
	'uccache_unwriteable' => '缓存目录 <strong>./uc_server/data/cache</strong> 无法写入，请设置属性为可写状态 (777)。',
	//'ucconfig_unwriteable' => '缓存目录 <strong>./uc_server/data/config.inc.php</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdata_unwriteable' => '缓存目录 <strong>./uc_server/data</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdatacache_unwriteable' => '缓存目录 <strong>./uc_server/data/cache</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdataview_unwriteable' => '缓存目录 <strong>./uc_server/data/view</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdataavatar_unwriteable' => '缓存目录 <strong>./uc_server/data/avatar</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdatabackup_unwriteable' => '缓存目录 <strong>./uc_server/data/backup</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdatatmp_unwriteable' => '缓存目录 <strong>./uc_server/data/tmp</strong> 无法写入，请设置属性为可写状态 (777)。',
	'ucdatalogs_unwriteable' => '缓存目录 <strong>./uc_server/data/logs</strong> 无法写入，请设置属性为可写状态 (777)。',
	'log_unwriteable' => '缓存目录 <strong>./forumdata/logs</strong> 无法写入，请设置属性为可写状态 (777)。',
	'tablepre_invalid' => '表名前缀不能包含字符".",不能以数字开头',
	'db_invalid' => '指定的数据库不存在, 系统也无法自动建立, 无法安装 Discuz!。',
	'db_auto_created' => '指定的数据库不存在, 但系统已成功建立, 可以继续安装。',
	'db_not_null' => '数据库中已经安装过 Discuz!, 继续安装会清空原有数据。',
	'db_drop_table_confirm' => '继续安装会清空全部原有数据，您确定要继续吗?',
	'install_in_processed' => '正在安装论坛数据，此过程需要较长时间，请您稍后 ...',
	'install_succeed' => '您现在可以点击这里进入论坛',

	'install_finished' => '论坛安装成功，请按照提示执行最后的安全工作',
	'install_finished_comment' => '<li>恭喜您成功安装了 Discuz! Board.</li>
		<li>为了保障您的论坛数据安全，建议您立即删除 <b>install</b> 目录中的所有文件，以免此文件被他人利用。</li>
		<li>论坛目录中的 <b>config.inc.php</b> 文件记录着您的重要信息，建议您将它做好备份。另外此文件中还有些更高级的设置，您可以参考当中的说明进行配置，从而使您的论坛更加安全。</li>
		<li>Discuz! 为您提供很多新功能，这些新功能的使用可能需要您花费一点时间去学习和使用它，在开始使用之前，我们建议您阅读论坛的使用手册。</li>
		</ul><ul><br><b>最后，感谢您选用 Comsenz 公司的产品，我们会竭诚为您提供更多更好的程序和服务</b>',

	'uc_appname' => '论坛',
	'uc_appreg' => '注册',
	'uc_appreg_succeed' => ' UCenter 信息设置成功，下面将开始下一步',
	'uc_continue' => '点击这里继续',
	'uc_title_ucenter' => '请填写 UCenter 的相关信息',
	'uc_url' => 'UCenter 的 URL',
	'uc_ip' => 'UCenter 的 IP',
	'uc_ip_comment' => '当前服务器无法解析 UCenter 的 IP 地址，请您手工填写',
	'uc_ip_error' => '连接的过程中出了点问题，请您填写服务器 IP 地址，如果您的 UC 与论坛装在同一服务器上，我们建议您尝试填写 127.0.0.1',
	'uc_adminpw' => 'UCenter 创始人密码',
	'uc_app_name' => '论坛的名称',
	'uc_app_name_comment' => '安装完毕后可在后台修改',
	'uc_app_url' => '论坛的安装地址',
	'uc_app_url_comment' => '可手工填写或使用程序检测到的',
	'uc_app_ip' => '的 IP',
	'uc_app_ip_comment' => '当主机 DNS 有问题时需要设置，默认请保留为空',
	'uc_connent_invalid' => '连接服务器失败，请检查 URL ',
	'uc_version_incorrect' => '您的 UCenter 服务端版本 ('.@$ucversion.') 过低，请升级 UCenter 服务端到最新版本，并且升级，下载地址：http://www.comsenz.com/ 。',
	'uc_dbcharset_incorrect' => 'UCenter 服务端字符集与当前应用的字符集不同，请下载 '.@$ucdbcharset.' 编码的 Discuz! 论坛进行安装。',

	'error_config_write' => '读写 config.inc.php 失败，无法将设置写入 config.inc.php，请将论坛 config.inc.php 的属性设置为可读写状态(777)',

	'uc_url_empty' => '您没有填写 UCenter 的 URL，请返回填写。',
	'uc_url_invalid' => 'URL 格式错误',
	'uc_url_unreachable' => 'UCenter 的 URL 地址可能填写错误，请检查',
	'uc_ip_invalid' => '无法解析该域名，请填写站点的 IP</font>',
	'uc_admin_invalid' => '密码错误，请重新填写',
	'uc_data_invalid' => '通信失败，请检查 URL 地址是否正确 ',

	'tagtemplates_subject' => '标题',
	'tagtemplates_uid' => '用户 ID',
	'tagtemplates_username' => '发帖者',
	'tagtemplates_dateline' => '日期',
	'tagtemplates_url' => '主题地址',

	'tips_uc_install' => '本安装程序会检测服务器环境是否达到论坛所需的最低标准',
	'tips_uc_install_comment' => '程序会检测PHP版本号、所依赖的库函数、目录是否可写等信息',

	'tips_env_check' => '本安装程序会检测服务器环境是否达到论坛所需的最低标准',
	'tips_env_check_comment' => '程序会检测PHP版本号、所依赖的库函数、目录是否可写等信息',
	
	'tips_db_config' => '填写论坛数据库信息',
	'tips_db_config_comment' => '',
	'tips_admin_config' => '填写论坛管理员信息',
	'tips_admin_config_comment' => '',

	'tips_install_process' => '安装论坛并设置初始数据',

	'step_1' => '检测运行环境',
	'step_1_comment' => '检测服务器环境是否达到论坛所需的最低标准。',

	'step_2' => '设置基本信息',
	'step_2_comment' => '请填写论坛 数据库 和 管理员 信息',

	'step_3' => '安装初始数据',
	'step_3_comment' => '安装论坛并设置初始数据',

	'step_4' => '安装完成',
	'step_4_comment' => '论坛安装成功，请按照提示执行最后的安全工作',

	'init_credits_karma' => '威望',
	'init_credits_money' => '金钱',

	'init_group_0' => '会员',
	'init_group_1' => '管理员',
	'init_group_2' => '超级版主',
	'init_group_3' => '版主',
	'init_group_4' => '禁止发言',
	'init_group_5' => '禁止访问',
	'init_group_6' => '禁止 IP',
	'init_group_7' => '游客',
	'init_group_8' => '等待验证会员',
	'init_group_9' => '乞丐',
	'init_group_10' => '新手上路',
	'init_group_11' => '注册会员',
	'init_group_12' => '中级会员',
	'init_group_13' => '高级会员',
	'init_group_14' => '金牌会员',
	'init_group_15' => '论坛元老',

	'init_rank_1' => '新生入学',
	'init_rank_2' => '小试牛刀',
	'init_rank_3' => '实习记者',
	'init_rank_4' => '自由撰稿人',
	'init_rank_5' => '特聘作家',

	'init_cron_1' => '清空今日发帖数',
	'init_cron_2' => '清空本月在线时间',
	'init_cron_3' => '每日数据清理',
	'init_cron_4' => '生日统计与邮件祝福',
	'init_cron_5' => '主题回复通知',
	'init_cron_6' => '每日公告清理',
	'init_cron_7' => '限时操作清理',
	'init_cron_8' => '论坛推广清理',
	'init_cron_9' => '每月主题清理',
	'init_cron_10' => '每日 X-Space更新用户',
	'init_cron_11' => '每周主题更新',

	'init_bbcode_1' => '使内容横向滚动，这个效果类似 HTML 的 marquee 标签，注意：这个效果只在 Internet Explorer 浏览器下有效。',
	'init_bbcode_2' => '嵌入 Flash 动画',
	'init_bbcode_3' => '显示 QQ 在线状态，点这个图标可以和他（她）聊天',
	'init_bbcode_4' => '上标',
	'init_bbcode_5' => '下标',
	'init_bbcode_6' => '嵌入 Windows media 音频',
	'init_bbcode_7' => '嵌入 Windows media 音频或视频',

	'init_qihoo_searchboxtxt' =>'输入关键词,快速搜索本论坛',
	'init_threadsticky' =>'全局置顶,分类置顶,本版置顶',

	'init_default_style' => '默认风格',
	'init_default_forum' => '默认版块',
	'init_default_template' => '默认模板套系',
	'init_default_template_copyright' => '康盛创想（北京）科技有限公司',

	'init_dataformat' => 'Y-n-j',
	'init_modreasons' => '广告/SPAM\r\n恶意灌水\r\n违规内容\r\n文不对题\r\n重复发帖\r\n\r\n我很赞同\r\n精品文章\r\n原创内容',
	'init_link' => 'Discuz! 官方论坛',
	'init_link_note' => '提供最新 Discuz! 产品新闻、软件下载与技术交流',

	'license' => '<div class="license"><h1>中文版授权协议 适用于中文用户</h1>

<p>版权所有 (c) 2001-2009，康盛创想（北京）科技有限公司保留所有权利。</p>

<p>感谢您选择 Discuz! 论坛产品。希望我们的努力能为您提供一个高效快速和强大的社区论坛解决方案。</p>

<p>Discuz! 英文全称为 Crossday Discuz! Board，中文全称为 Discuz! 论坛，以下简称 Discuz!。</p>

<p>康盛创想（北京）科技有限公司为 Discuz! 产品的开发商，依法独立拥有 Discuz! 产品著作权（中国国家版权局著作权登记号 2006SR11895）。康盛创想（北京）科技有限公司网址为 http://www.comsenz.com，Discuz! 官方网站网址为 http://www.discuz.com，Discuz! 官方讨论区网址为 http://www.discuz.net。</p>

<p>Discuz! 著作权已在中华人民共和国国家版权局注册，著作权受到法律和国际公约保护。使用者：无论个人或组织、盈利与否、用途如何（包括以学习和研究为目的），均需仔细阅读本协议，在理解、同意、并遵守本协议的全部条款后，方可开始使用 Discuz! 软件。</p>

<p>本授权协议适用且仅适用于 Discuz! 7.x.x 版本，康盛创想（北京）科技有限公司拥有对本授权协议的最终解释权。</p>

<h3>I. 协议许可的权利</h3>
<ol>
<li>您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于非商业用途，而不必支付软件版权授权费用。</li>
<li>您可以在协议规定的约束和限制范围内修改 Discuz! 源代码(如果被提供的话)或界面风格以适应您的网站要求。</li>
<li>您拥有使用本软件构建的论坛中全部会员资料、文章及相关信息的所有权，并独立承担与文章内容的相关法律义务。</li>
<li>获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持期限、技术支持方式和技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。</li>
</ol>

<h3>II. 协议规定的约束和限制</h3>
<ol>
<li>未获商业授权之前，不得将本软件用于商业用途（包括但不限于企业网站、经营性网站、以营利为目或实现盈利的网站）。购买商业授权请登陆http://www.discuz.com参考相关说明，也可以致电8610-51657885了解详情。</li>
<li>不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。</li>
<li>无论如何，即无论用途如何、是否经过修改或美化、修改程度如何，只要使用 Discuz! 的整体或任何部分，未经书面许可，论坛页面页脚处的 Discuz! 名称和康盛创想（北京）科技有限公司下属网站（http://www.comsenz.com、http://www.discuz.com 或 http://www.discuz.net） 的链接都必须保留，而不能清除或修改。</li>
<li>禁止在 Discuz! 的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。</li>
<li>如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。</li>
</ol>

<h3>III. 有限担保和免责声明</h3>
<ol>
<li>本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。</li>
<li>用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。</li>
<li>康盛创想（北京）科技有限公司不对使用本软件构建的论坛中的文章或信息承担责任。</li>
</ol>

<p>有关 Discuz! 最终用户授权协议、商业授权与技术服务的详细内容，均由 Discuz! 官方网站独家提供。康盛创想（北京）科技有限公司拥有在不事先通知的情况下，修改授权协议和服务价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。</p>

<p>电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始安装 Discuz!，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。</p></div>',


	'preparation' => '<li>将压缩包中 Discuz! 目录下全部文件和目录上传到服务器。</li><li>如果您使用非 WINNT 系统请修改以下属性：<br />&nbsp; &nbsp; <b>./attachments</b> 目录 777;&nbsp; &nbsp; <b>./forumdata</b> 目录 777;<br /><b>&nbsp; &nbsp; ./forumdata/cache</b> 目录 777;&nbsp; &nbsp; <b>./forumdata/templates</b> 目录 777;&nbsp; &nbsp; <b>./forumdata/threadcaches</b> 目录 777;<br />&nbsp; &nbsp; <b>./forumdata/logs</b> 目录 777;&nbsp; &nbsp; <br /></li><li>确认 URL 中 /attachments 可以访问服务器目录 ./attachments 内容。</li><li>如果config.inc.php文件不可写，请自行修改该文件上传到论坛根目录下。</li>',


	'install_locked' => '安装程序已经被锁定',
	'install_locked_comment' => '您已经成功安装过论坛，如果想要重新安装，请删除 forumdata 目录下的 install.lock 文件，然后刷新重试',

	'short_open_tag_invalid' => 'PHP 环境问题',
	'short_open_tag_invalid_comment' => '对不起，请将 php.ini 中的 short_open_tag 设置为 On，否则程序无法正常运行',

	'database_nonexistence' => '程序文件丢失',
	'database_nonexistence_comment' => '您的 ./include/db_mysql.class.php 不存在, 无法继续安装, 请用 FTP 将该文件上传后再试。',

	);

$msglang = array(

	'config_nonexistence' => '您的 config.inc.php 不存在, 无法继续安装, 请用 FTP 将该文件上传后再试。',
);

$videoinfo = array(
	'open' => 0,
	'vtype' => "新闻\t军事\t音乐\t影视\t动漫",
	'bbname' => '',
	'url' => '',
	'email' => '',
	'logo' => '',
	'sitetype' => "新闻\t军事\t音乐\t影视\t动漫\t游戏\t美女\t娱乐\t交友\t教育\t艺术\t学术\t技术\t动物\t旅游\t生活\t时尚\t电脑\t汽车\t手机\t摄影\t戏曲\t外语\t公益\t校园\t数码\t电脑\t历史\t天文\t地理\t财经\t地区\t人物\t体育\t健康\t综合",
	'vsiteid' => '',
	'vpassword' => '',
	'vkey' => '',
	'vclasses' => array (
		22 => '新闻',
		15 => '体育',
		27 => '教育',
		28 => '明星',
		26 => '美色',
		1 => '搞笑',
		29 => '另类',
		18 => '影视',
		12 => '音乐',
		8 => '动漫',
		7 => '游戏',
		24 => '综艺',
		11 => '广告',
		19 => '艺术',
		5 => '时尚',
		21 => '居家',
		23 => '旅游',
		25 => '动物',
		14 => '汽车',
		30 => '军事',
		16 => '科技',
		31 => '其他'
	),
	'vclassesable' => array (22, 15, 27, 28, 26, 1, 29, 18, 12, 8, 7, 24, 11, 19, 5, 21, 23, 25, 14, 30, 16, 31)
);



$optionlist = array (
	8 => array (
		'classid' => '1',
		'displayorder' => '2',
		'title' => '性别',
		'identifier' => 'gender',
		'type' => 'radio',
		'rules' => array (
			      'required' => '0',
			      'unchangeable' => '0',
			      'choices' => "1=男\r\n2=女",
			   ),
		),
	16 => array (
		'classid' => '2',
		'displayorder' => '0',
		'title' => '房屋类型',
		'identifier' => 'property',
		'type' => 'select',
		'rules' => array (
			      'choices' => "1=写字楼\r\n2=公寓\r\n3=小区\r\n4=平房\r\n5=别墅\r\n6=地下室",
			   ),
		),
	17 => array (
		'classid' => '2',
		'displayorder' => '0',
		'title' => '座向',
		'identifier' => 'face',
		'type' => 'radio',
	    	'rules' => array (
	      			'required' => '0',
	      			'unchangeable' => '0',
	      			'choices' => "1=南向\r\n2=北向\r\n3=西向\r\n4=东向",
	    		),
	  	),
      18 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '装修情况',
        	'identifier' => 'makes',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=无装修\r\n2=简单装修\r\n3=精装修",
        		),
      	),
      19 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '居室',
        	'identifier' => 'mode',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=独居\r\n2=两居室\r\n3=三居室\r\n4=四居室\r\n5=别墅",
        		),
      	),
      23 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '屋内设施',
        	'identifier' => 'equipment',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=水电\r\n2=宽带\r\n3=管道气\r\n4=有线电视\r\n5=电梯\r\n6=电话\r\n7=冰箱\r\n8=洗衣机\r\n9=热水器\r\n10=空调\r\n11=暖气\r\n12=微波炉\r\n13=油烟机\r\n14=饮水机",
       		),
      	),
      25 => array (
        	'classid' => '2',
        	'displayorder' => '0',
        	'title' => '是否中介',
        	'identifier' => 'bool',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=是\r\n2=否",
        		),
      	),
      27 => array (
        	'classid' => '3',
       	'displayorder' => '0',
        	'title' => '星座',
        	'identifier' => 'Horoscope',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=白羊座\r\n2=金牛座\r\n3=双子座\r\n4=巨蟹座\r\n5=狮子座\r\n6=处女座\r\n7=天秤座\r\n8=天蝎座\r\n9=射手座\r\n10=摩羯座\r\n11=水瓶座\r\n12=双鱼座",
        		),
      	),
      30 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '婚姻状况',
        	'identifier' => 'marrige',
        	'type' => 'radio',
        	'rules' => array (
          			'choices' => "1=已婚\r\n2=未婚",
        		),
      	),
      31 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '爱好',
        	'identifier' => 'hobby',
        	'type' => 'checkbox',
        	'rules' => array (
          			'choices' => "1=美食\r\n2=唱歌\r\n3=跳舞\r\n4=电影\r\n5=音乐\r\n6=戏剧\r\n7=聊天\r\n8=拍托\r\n9=电脑\r\n10=网络\r\n11=游戏\r\n12=绘画\r\n13=书法\r\n14=雕塑\r\n15=异性\r\n16=阅读\r\n17=运动\r\n18=旅游\r\n19=八卦\r\n20=购物\r\n21=赚钱\r\n22=汽车\r\n23=摄影",
        		),
      	),
      32 => array (
        	'classid' => '3',
        	'displayorder' => '0',
        	'title' => '收入范围',
        	'identifier' => 'salary',
        	'type' => 'select',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=保密\r\n2=800元以上\r\n3=1500元以上\r\n4=2000元以上\r\n5=3000元以上\r\n6=5000元以上\r\n7=8000元以上",
        		),
      	),
      34 => array (
        	'classid' => '1',
        	'displayorder' => '0',
        	'title' => '学历',
        	'identifier' => 'education',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=文盲\r\n2=小学\r\n3=初中\r\n4=高中\r\n5=中专\r\n6=大专\r\n7=本科\r\n8=研究生\r\n9=博士",
        		),
      	),
      38 => array (
        	'classid' => '5',
        	'displayorder' => '0',
        	'title' => '席别',
        	'identifier' => 'seats',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=站票\r\n2=硬座\r\n3=软座\r\n4=硬卧\r\n5=软卧",
        		),
      	),
      44 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '是否应届',
        	'identifier' => 'recr_term',
        	'type' => 'radio',
        	'rules' => array (
    		      	'required' => '0',
    		      	'unchangeable' => '0',
    		      	'choices' => "1=应届\r\n2=非应届",
        		),
      	),
      48 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '薪金',
        	'identifier' => 'recr_salary',
        	'type' => 'select',
        	'rules' => array (
          			'choices' => "1=面议\r\n2=1000以下\r\n3=1000~1500\r\n4=1500~2000\r\n5=2000~3000\r\n6=3000~4000\r\n7=4000~6000\r\n8=6000~8000\r\n9=8000以上",
        		),
      	),
      50 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '工作性质',
        	'identifier' => 'recr_work',
        	'type' => 'radio',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=全职\r\n2=兼职",
        		),
      	),
      53 => array (
        	'classid' => '4',
        	'displayorder' => '0',
        	'title' => '性别要求',
        	'identifier' => 'recr_sex',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=男\r\n2=女",
        		),
      	),
      62 => array (
        	'classid' => '5',
        	'displayorder' => '0',
        	'title' => '付款方式',
        	'identifier' => 'pay_type',
        	'type' => 'checkbox',
        	'rules' => array (
          			'required' => '0',
          			'unchangeable' => '0',
          			'choices' => "1=电汇\r\n2=支付宝\r\n3=现金\r\n4=其他",
        		),
      	),
);

$request_data = array (
  '边栏模块_版块树形列表' => 
  array (
    'url' => 'function=module&module=forumtree.inc.php&settings=N%3B&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'forumtree.inc.php',
      'cachelife' => '864000',
      'jscharset' => '0',
    ),
    'comment' => '边栏版块树形列表模块',
    'type' => '5',
  ),
  '边栏模块_版主排行' => 
  array (
    'url' => 'function=module&module=modlist.inc.php&settings=N%3B&jscharset=0&cachelife=3600',
    'parameter' => 
    array (
      'module' => 'modlist.inc.php',
      'cachelife' => '3600',
      'jscharset' => '0',
    ),
    'comment' => '边栏版主排行模块',
    'type' => '5',
  ),
  '聚合模块_版块列表' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A58%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%C5%C5%D0%D0%2C%B0%E6%BF%E9%C5%C5%D0%D0%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%CA%F7%D0%CE%C1%D0%B1%ED%2C%B0%E6%BF%E9%C1%D0%B1%ED%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'data' => '边栏模块_版块排行,版块排行
边栏模块_版块树形列表,版块列表',
      ),
      'jscharset' => '0',
    ),
    'comment' => '热门版块、版块树形聚合模块',
    'type' => '5',
  ),
  '边栏模块_版块排行' => 
  array (
    'url' => 'function=forums&startrow=0&items=0&newwindow=1&orderby=posts&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B0%E6%BF%E9%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%3Cimg%20style%3D%5C%22vertical-align%3Amiddle%5C%22%20src%3D%5C%22images%2Fdefault%2Ftree_file.gif%5C%22%20%2F%3E%20%7Bforumname%7D%28%7Bposts%7D%29%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>版块排行</h4>
<ul class=\\"textinfolist\\">
[node]<li><img style=\\"vertical-align:middle\\" src=\\"images/default/tree_file.gif\\" /> {forumname}({posts})</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '0',
      'newwindow' => 1,
      'orderby' => 'posts',
      'jscharset' => '0',
    ),
    'comment' => '边栏版块排行模块',
    'type' => '1',
  ),
  '聚合模块_热门主题' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A89%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%BD%F1%C8%D5%2C%BD%F1%C8%D5%C8%C8%C3%C5%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%D6%DC%2C%B1%BE%D6%DC%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%D4%C2%2C%B1%BE%D4%C2%22%3B%7D&jscharset=0&cachelife=1800',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '1800',
      'settings' => 
      array (
        'data' => '边栏模块_热门主题_今日,今日热门
边栏模块_热门主题_本周,本周
边栏模块_热门主题_本月,本月',
      ),
      'jscharset' => '0',
    ),
    'comment' => '今日、本周、本月热门主题聚合模块',
    'type' => '5',
  ),
  '边栏模块_热门主题_本月' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=720&jscharset=0&cachelife=86400&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D4%C2%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本月热门</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '86400',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '720',
      'jscharset' => '0',
    ),
    'comment' => '边栏本月热门主题模块',
    'type' => '0',
  ),
  '聚合模块_会员排行' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A89%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%BD%F1%C8%D5%2C%BD%F1%C8%D5%C5%C5%D0%D0%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%B1%BE%D6%DC%2C%B1%BE%D6%DC%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%BB%E1%D4%B1%C5%C5%D0%D0_%B1%BE%D4%C2%2C%B1%BE%D4%C2%22%3B%7D&jscharset=0&cachelife=3600',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '3600',
      'settings' => 
      array (
        'data' => '边栏模块_会员排行_今日,今日排行
边栏模块_会员排行_本周,本周
边栏模块_会员排行_本月,本月',
      ),
      'jscharset' => '0',
    ),
    'comment' => '今日、本周、本月会员排行聚合模块',
    'type' => '5',
  ),
  '边栏模块_推荐主题' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=1&newwindow=1&threadtype=0&highlight=0&orderby=lastpost&hours=48&jscharset=0&cachelife=3600&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%CD%C6%BC%F6%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>推荐主题</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '3600',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '1',
      'newwindow' => 1,
      'orderby' => 'lastpost',
      'hours' => '48',
      'jscharset' => '0',
    ),
    'comment' => '边栏推荐主题模块',
    'type' => '0',
  ),
  '边栏模块_最新图片' => 
  array (
    'url' => 'function=images&sidestatus=0&isimage=1&threadmethod=1&maxwidth=140&maxheight=140&startrow=0&items=5&orderby=dateline&hours=0&digest=0&newwindow=1&jscharset=0&jstemplate=%3Cdiv%20%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%CD%BC%C6%AC%3C%2Fh4%3E%0D%0A%3Cscript%20type%3D%5C%22text%2Fjavascript%5C%22%3E%0D%0Avar%20slideSpeed%20%3D%202500%3B%0D%0Avar%20slideImgsize%20%3D%20%5B140%2C140%5D%3B%0D%0Avar%20slideTextBar%20%3D%200%3B%0D%0Avar%20slideBorderColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0Avar%20slideBgColor%20%3D%20%5C%27%23FFF%5C%27%3B%0D%0Avar%20slideImgs%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgLinks%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgTexts%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideSwitchBar%20%3D%201%3B%0D%0Avar%20slideSwitchColor%20%3D%20%5C%27black%5C%27%3B%0D%0Avar%20slideSwitchbgColor%20%3D%20%5C%27white%5C%27%3B%0D%0Avar%20slideSwitchHiColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0A%5Bnode%5D%0D%0AslideImgs%5B%7Border%7D%5D%20%3D%20%5C%22%7Bimgfile%7D%5C%22%3B%0D%0AslideImgLinks%5B%7Border%7D%5D%20%3D%20%5C%22%7Blink%7D%5C%22%3B%0D%0AslideImgTexts%5B%7Border%7D%5D%20%3D%20%5C%22%7Bsubject%7D%5C%22%3B%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fscript%3E%0D%0A%3Cscript%20language%3D%5C%22javascript%5C%22%20type%3D%5C%22text%2Fjavascript%5C%22%20src%3D%5C%22include%2Fjs%2Fslide.js%5C%22%3E%3C%2Fscript%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div  class=\\"sidebox\\">
<h4>最新图片</h4>
<script type=\\"text/javascript\\">
var slideSpeed = 2500;
var slideImgsize = [140,140];
var slideTextBar = 0;
var slideBorderColor = \\\'#C8DCEC\\\';
var slideBgColor = \\\'#FFF\\\';
var slideImgs = new Array();
var slideImgLinks = new Array();
var slideImgTexts = new Array();
var slideSwitchBar = 1;
var slideSwitchColor = \\\'black\\\';
var slideSwitchbgColor = \\\'white\\\';
var slideSwitchHiColor = \\\'#C8DCEC\\\';
[node]
slideImgs[{order}] = \\"{imgfile}\\";
slideImgLinks[{order}] = \\"{link}\\";
slideImgTexts[{order}] = \\"{subject}\\";
[/node]
</script>
<script language=\\"javascript\\" type=\\"text/javascript\\" src=\\"include/js/slide.js\\"></script>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'isimage' => '1',
      'maxwidth' => '140',
      'maxheight' => '140',
      'threadmethod' => '1',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏最新图片展示模块',
    'type' => '4',
  ),
  '边栏模块_最新主题' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=dateline&hours=0&jscharset=0&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>最新主题</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏最新主题模块',
    'type' => '0',
  ),
  '边栏模块_活跃会员' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=12&newwindow=1&extcredit=1&orderby=posts&hours=0&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%BB%EE%D4%BE%BB%E1%D4%B1%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22avt_list%20s_clear%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bavatarsmall%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>活跃会员</h4>
<ul class=\\"avt_list s_clear\\">
[node]<li>{avatarsmall}</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '12',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'posts',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏活跃会员模块',
    'type' => '2',
  ),
  '边栏模块_热门主题_本版' => 
  array (
    'url' => 'function=threads&sidestatus=1&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=replies&hours=0&jscharset=0&cachelife=1800&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%B0%E6%C8%C8%C3%C5%D6%F7%CC%E2%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本版热门主题</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '1800',
      'sidestatus' => '1',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'replies',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏本版热门主题模块',
    'type' => '0',
  ),
  '边栏模块_热门主题_今日' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=24&jscharset=0&cachelife=1800&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%BD%F1%C8%D5%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>今日热门</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '1800',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '24',
      'jscharset' => '0',
    ),
    'comment' => '边栏今日热门主题模块',
    'type' => '0',
  ),
  '边栏模块_最新回复' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=lastpost&hours=0&jscharset=0&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%BB%D8%B8%B4%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>最新回复</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'lastpost',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏最新回复模块',
    'type' => '0',
  ),
  '边栏模块_最新图片_本版' => 
  array (
    'url' => 'function=images&sidestatus=1&isimage=1&threadmethod=1&maxwidth=140&maxheight=140&startrow=0&items=5&orderby=dateline&hours=0&digest=0&newwindow=1&jscharset=0&jstemplate=%3Cdiv%20%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%D7%EE%D0%C2%CD%BC%C6%AC%3C%2Fh4%3E%0D%0A%3Cscript%20type%3D%5C%22text%2Fjavascript%5C%22%3E%0D%0Avar%20slideSpeed%20%3D%202500%3B%0D%0Avar%20slideImgsize%20%3D%20%5B140%2C140%5D%3B%0D%0Avar%20slideTextBar%20%3D%200%3B%0D%0Avar%20slideBorderColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0Avar%20slideBgColor%20%3D%20%5C%27%23FFF%5C%27%3B%0D%0Avar%20slideImgs%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgLinks%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideImgTexts%20%3D%20new%20Array%28%29%3B%0D%0Avar%20slideSwitchBar%20%3D%201%3B%0D%0Avar%20slideSwitchColor%20%3D%20%5C%27black%5C%27%3B%0D%0Avar%20slideSwitchbgColor%20%3D%20%5C%27white%5C%27%3B%0D%0Avar%20slideSwitchHiColor%20%3D%20%5C%27%23C8DCEC%5C%27%3B%0D%0A%5Bnode%5D%0D%0AslideImgs%5B%7Border%7D%5D%20%3D%20%5C%22%7Bimgfile%7D%5C%22%3B%0D%0AslideImgLinks%5B%7Border%7D%5D%20%3D%20%5C%22%7Blink%7D%5C%22%3B%0D%0AslideImgTexts%5B%7Border%7D%5D%20%3D%20%5C%22%7Bsubject%7D%5C%22%3B%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fscript%3E%0D%0A%3Cscript%20language%3D%5C%22javascript%5C%22%20type%3D%5C%22text%2Fjavascript%5C%22%20src%3D%5C%22include%2Fjs%2Fslide.js%5C%22%3E%3C%2Fscript%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div  class=\\"sidebox\\">
<h4>最新图片</h4>
<script type=\\"text/javascript\\">
var slideSpeed = 2500;
var slideImgsize = [140,140];
var slideTextBar = 0;
var slideBorderColor = \\\'#C8DCEC\\\';
var slideBgColor = \\\'#FFF\\\';
var slideImgs = new Array();
var slideImgLinks = new Array();
var slideImgTexts = new Array();
var slideSwitchBar = 1;
var slideSwitchColor = \\\'black\\\';
var slideSwitchbgColor = \\\'white\\\';
var slideSwitchHiColor = \\\'#C8DCEC\\\';
[node]
slideImgs[{order}] = \\"{imgfile}\\";
slideImgLinks[{order}] = \\"{link}\\";
slideImgTexts[{order}] = \\"{subject}\\";
[/node]
</script>
<script language=\\"javascript\\" type=\\"text/javascript\\" src=\\"include/js/slide.js\\"></script>
</div>',
      'cachelife' => '',
      'sidestatus' => '1',
      'startrow' => '0',
      'items' => '5',
      'isimage' => '1',
      'maxwidth' => '140',
      'maxheight' => '140',
      'threadmethod' => '1',
      'newwindow' => 1,
      'orderby' => 'dateline',
      'hours' => '',
      'jscharset' => '0',
    ),
    'comment' => '边栏本版最新图片展示模块',
    'type' => '4',
  ),
  '边栏模块_标签' => 
  array (
    'url' => 'function=module&module=tag.inc.php&settings=a%3A1%3A%7Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2220%22%3B%7D&jscharset=0&cachelife=900',
    'parameter' => 
    array (
      'module' => 'tag.inc.php',
      'cachelife' => '900',
      'settings' => 
      array (
        'limit' => '20',
      ),
      'jscharset' => '0',
    ),
    'comment' => '边栏标签模块',
    'type' => '5',
  ),
  '边栏模块_会员排行_本月' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=720&jscharset=0&cachelife=86400&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D4%C2%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本月排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />发帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '86400',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '720',
      'jscharset' => '0',
    ),
    'comment' => '边栏会员本月发帖排行模块',
    'type' => '2',
  ),
  '边栏模块_会员排行_本周' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本周排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />发帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '边栏会员本周发帖排行模块',
    'type' => '2',
  ),
  '边栏方案_主题列表页默认' => 
  array (
    'url' => 'function=side&jscharset=&jstemplate=%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%CE%D2%B5%C4%D6%FA%CA%D6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2_%B1%BE%B0%E6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%B0%E6%BF%E9%C5%C5%D0%D0%5B%2Fmodule%5D',
    'parameter' => 
    array (
      'selectmodule' => 
      array (
        1 => '边栏模块_我的助手',
        2 => '边栏模块_热门主题_本版',
        3 => '边栏模块_版块排行',
      ),
      'cachelife' => 0,
      'jstemplate' => '[module]边栏模块_我的助手[/module]<hr class="shadowline"/>[module]边栏模块_热门主题_本版[/module]<hr class="shadowline"/>[module]边栏模块_版块排行[/module]',
    ),
    'comment' => NULL,
    'type' => '-2',
  ),
  '边栏方案_首页默认' => 
  array (
    'url' => 'function=side&jscharset=&jstemplate=%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%CE%D2%B5%C4%D6%FA%CA%D6%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%BE%DB%BA%CF%C4%A3%BF%E9_%D0%C2%CC%FB%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%BE%DB%BA%CF%C4%A3%BF%E9_%C8%C8%C3%C5%D6%F7%CC%E2%5B%2Fmodule%5D%3Chr%20class%3D%22shadowline%22%2F%3E%5Bmodule%5D%B1%DF%C0%B8%C4%A3%BF%E9_%BB%EE%D4%BE%BB%E1%D4%B1%5B%2Fmodule%5D',
    'parameter' => 
    array (
      'selectmodule' => 
      array (
        1 => '边栏模块_我的助手',
        2 => '聚合模块_新帖',
        3 => '聚合模块_热门主题',
        4 => '边栏模块_活跃会员',
      ),
      'cachelife' => 0,
      'jstemplate' => '[module]边栏模块_我的助手[/module]<hr class="shadowline"/>[module]聚合模块_新帖[/module]<hr class="shadowline"/>[module]聚合模块_热门主题[/module]<hr class="shadowline"/>[module]边栏模块_活跃会员[/module]',
    ),
    'comment' => NULL,
    'type' => '-2',
  ),
  '聚合模块_新帖' => 
  array (
    'url' => 'function=module&module=rowcombine.inc.php&settings=a%3A1%3A%7Bs%3A4%3A%22data%22%3Bs%3A50%3A%22%B1%DF%C0%B8%C4%A3%BF%E9_%D7%EE%D0%C2%D6%F7%CC%E2%2C%D7%EE%D0%C2%D6%F7%CC%E2%0D%0A%B1%DF%C0%B8%C4%A3%BF%E9_%D7%EE%D0%C2%BB%D8%B8%B4%2C%BB%D8%B8%B4%22%3B%7D&jscharset=0',
    'parameter' => 
    array (
      'module' => 'rowcombine.inc.php',
      'cachelife' => '',
      'settings' => 
      array (
        'data' => '边栏模块_最新主题,最新主题
边栏模块_最新回复,回复',
      ),
      'jscharset' => '0',
    ),
    'comment' => '最新主题、最新回复聚合模块',
    'type' => '5',
  ),
  '边栏模块_热门主题_本周' => 
  array (
    'url' => 'function=threads&sidestatus=0&maxlength=20&fnamelength=0&messagelength=&startrow=0&picpre=images%2Fcommon%2Fslisticon.gif&items=5&tag=&tids=&special=0&rewardstatus=&digest=0&stick=0&recommend=0&newwindow=1&threadtype=0&highlight=0&orderby=hourviews&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%C8%C8%C3%C5%3C%2Fh4%3E%0D%0A%3Cul%20class%3D%5C%22textinfolist%5C%22%3E%0D%0A%5Bnode%5D%3Cli%3E%7Bprefix%7D%7Bsubject%7D%3C%2Fli%3E%5B%2Fnode%5D%0D%0A%3C%2Ful%3E%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox\\">
<h4>本周热门</h4>
<ul class=\\"textinfolist\\">
[node]<li>{prefix}{subject}</li>[/node]
</ul>
</div>',
      'cachelife' => '43200',
      'sidestatus' => '0',
      'startrow' => '0',
      'items' => '5',
      'maxlength' => '20',
      'fnamelength' => '0',
      'messagelength' => '',
      'picpre' => 'images/common/slisticon.gif',
      'tids' => '',
      'keyword' => '',
      'tag' => '',
      'threadtype' => '0',
      'highlight' => '0',
      'recommend' => '0',
      'newwindow' => 1,
      'orderby' => 'hourviews',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '边栏本周热门主题模块',
    'type' => '0',
  ),
  '边栏模块_会员排行_今日' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=5&newwindow=1&extcredit=1&orderby=hourposts&hours=24&jscharset=0&cachelife=3600&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%BD%F1%C8%D5%C5%C5%D0%D0%3C%2Fh4%3E%0D%0A%5Bnode%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3Bmargin%3A%200%2016px%205px%200%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%7Bmember%7D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>今日排行</h4>
[node]<div style=\\"clear:both\\"><div style=\\"float:left;margin: 0 16px 5px 0\\">{avatarsmall}</div>{member}<br />发帖 {value} 篇</div>[/node]
</div>',
      'cachelife' => '3600',
      'startrow' => '0',
      'items' => '5',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '24',
      'jscharset' => '0',
    ),
    'comment' => '边栏会员今日发帖排行模块',
    'type' => '2',
  ),
  '边栏模块_论坛之星' => 
  array (
    'url' => 'function=memberrank&startrow=0&items=3&newwindow=1&extcredit=1&orderby=hourposts&hours=168&jscharset=0&cachelife=43200&jstemplate=%3Cdiv%20class%3D%5C%22sidebox%20s_clear%5C%22%3E%0D%0A%3Ch4%3E%B1%BE%D6%DC%D6%AE%D0%C7%3C%2Fh4%3E%0D%0A%5Bnode%5D%0D%0A%5Bshow%3D1%5D%3Cdiv%20style%3D%5C%22clear%3Aboth%5C%22%3E%3Cdiv%20style%3D%5C%22float%3Aleft%3B%20margin-right%3A%2016px%3B%5C%22%3E%7Bavatarsmall%7D%3C%2Fdiv%3E%5B%2Fshow%5D%7Bmember%7D%20%5Bshow%3D1%5D%3Cbr%20%2F%3E%B7%A2%CC%FB%20%7Bvalue%7D%20%C6%AA%3C%2Fdiv%3E%3Cdiv%20style%3D%5C%22clear%3Aboth%3Bmargin-top%3A2px%5C%22%20%2F%3E%3C%2Fdiv%3E%5B%2Fshow%5D%0D%0A%5B%2Fnode%5D%0D%0A%3C%2Fdiv%3E',
    'parameter' => 
    array (
      'jstemplate' => '<div class=\\"sidebox s_clear\\">
<h4>本周之星</h4>
[node]
[show=1]<div style=\\"clear:both\\"><div style=\\"float:left; margin-right: 16px;\\">{avatarsmall}</div>[/show]{member} [show=1]<br />发帖 {value} 篇</div><div style=\\"clear:both;margin-top:2px\\" /></div>[/show]
[/node]
</div>',
      'cachelife' => '43200',
      'startrow' => '0',
      'items' => '3',
      'newwindow' => 1,
      'extcredit' => '1',
      'orderby' => 'hourposts',
      'hours' => '168',
      'jscharset' => '0',
    ),
    'comment' => '边栏论坛之星模块',
    'type' => '2',
  ),
  '边栏模块_我的助手' => 
  array (
    'url' => 'function=module&module=assistant.inc.php&settings=N%3B&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'assistant.inc.php',
      'cachelife' => '0',
      'jscharset' => '0',
    ),
    'comment' => '边栏我的助手模块',
    'type' => '5',
  ),
  '边栏模块_Google搜索' => 
  array (
    'url' => 'function=module&module=google.inc.php&settings=a%3A2%3A%7Bs%3A4%3A%22lang%22%3Bs%3A0%3A%22%22%3Bs%3A7%3A%22default%22%3Bs%3A1%3A%221%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'google.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'lang' => '',
        'default' => '1',
      ),
      'jscharset' => '0',
    ),
    'comment' => '边栏 Google 搜索模块',
    'type' => '5',
  ),
  'UCHome_最新动态' => 
  array (
    'url' => 'function=module&module=feed.inc.php&settings=a%3A6%3A%7Bs%3A5%3A%22title%22%3Bs%3A8%3A%22%D7%EE%D0%C2%B6%AF%CC%AC%22%3Bs%3A4%3A%22uids%22%3Bs%3A0%3A%22%22%3Bs%3A6%3A%22friend%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A54%3A%22%3Cdiv%20style%3D%5C%22padding-left%3A2px%5C%22%3E%7Btitle_template%7D%3C%2Fdiv%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'feed.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新动态',
        'uids' => '',
        'friend' => '0',
        'start' => '0',
        'limit' => '10',
        'template' => '<div style=\\"padding-left:2px\\">{title_template}</div>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '获取UCHome的最新动态',
    'type' => '5',
  ),
  'UCHome_最新更新空间' => 
  array (
    'url' => 'function=module&module=space.inc.php&settings=a%3A17%3A%7Bs%3A5%3A%22title%22%3Bs%3A12%3A%22%D7%EE%D0%C2%B8%FC%D0%C2%BF%D5%BC%E4%22%3Bs%3A3%3A%22uid%22%3Bs%3A0%3A%22%22%3Bs%3A14%3A%22startfriendnum%22%3Bs%3A0%3A%22%22%3Bs%3A12%3A%22endfriendnum%22%3Bs%3A0%3A%22%22%3Bs%3A12%3A%22startviewnum%22%3Bs%3A0%3A%22%22%3Bs%3A10%3A%22endviewnum%22%3Bs%3A0%3A%22%22%3Bs%3A11%3A%22startcredit%22%3Bs%3A0%3A%22%22%3Bs%3A9%3A%22endcredit%22%3Bs%3A0%3A%22%22%3Bs%3A6%3A%22avatar%22%3Bs%3A2%3A%22-1%22%3Bs%3A10%3A%22namestatus%22%3Bs%3A2%3A%22-1%22%3Bs%3A8%3A%22dateline%22%3Bs%3A1%3A%220%22%3Bs%3A10%3A%22updatetime%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22order%22%3Bs%3A10%3A%22updatetime%22%3Bs%3A2%3A%22sc%22%3Bs%3A4%3A%22DESC%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A267%3A%22%3Ctable%3E%0D%0A%3Ctr%3E%0D%0A%3Ctd%20width%3D%5C%2250%5C%22%20rowspan%3D%5C%222%5C%22%3E%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20target%3D%5C%22_blank%5C%22%3E%3Cimg%20src%3D%5C%22%7Bphoto%7D%5C%22%20%2F%3E%3C%2Fa%3E%3C%2Ftd%3E%0D%0A%3Ctd%3E%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20%20target%3D%5C%22_blank%5C%22%20style%3D%5C%22text-decoration%3Anone%3B%5C%22%3E%7Busername%7D%3C%2Fa%3E%3C%2Ftd%3E%0D%0A%3C%2Ftr%3E%0D%0A%3Ctr%3E%3Ctd%3E%7Bupdatetime%7D%3C%2Ftd%3E%3C%2Ftr%3E%0D%0A%3C%2Ftable%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'space.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新更新空间',
        'uid' => '',
        'startfriendnum' => '',
        'endfriendnum' => '',
        'startviewnum' => '',
        'endviewnum' => '',
        'startcredit' => '',
        'endcredit' => '',
        'avatar' => '-1',
        'namestatus' => '-1',
        'dateline' => '0',
        'updatetime' => '0',
        'order' => 'updatetime',
        'sc' => 'DESC',
        'start' => '0',
        'limit' => '10',
        'template' => '<table>
<tr>
<td width=\\"50\\" rowspan=\\"2\\"><a href=\\"{userlink}\\" target=\\"_blank\\"><img src=\\"{photo}\\" /></a></td>
<td><a href=\\"{userlink}\\"  target=\\"_blank\\" style=\\"text-decoration:none;\\">{username}</a></td>
</tr>
<tr><td>{updatetime}</td></tr>
</table>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '获取UCHome最新更新会员空间',
    'type' => '5',
  ),
  'UCHome_最新记录' => 
  array (
    'url' => 'function=module&module=doing.inc.php&settings=a%3A6%3A%7Bs%3A5%3A%22title%22%3Bs%3A8%3A%22%D7%EE%D0%C2%BC%C7%C2%BC%22%3Bs%3A3%3A%22uid%22%3Bs%3A0%3A%22%22%3Bs%3A4%3A%22mood%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22start%22%3Bs%3A1%3A%220%22%3Bs%3A5%3A%22limit%22%3Bs%3A2%3A%2210%22%3Bs%3A8%3A%22template%22%3Bs%3A360%3A%22%0D%0A%3Cdiv%20style%3D%5C%22padding%3A0%200%205px%200%3B%5C%22%3E%0D%0A%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20target%3D%5C%22_blank%5C%22%3E%3Cimg%20src%3D%5C%22%7Bphoto%7D%5C%22%20width%3D%5C%2218%5C%22%20height%3D%5C%2218%5C%22%20align%3D%5C%22absmiddle%5C%22%3E%3C%2Fa%3E%20%3Ca%20href%3D%5C%22%7Buserlink%7D%5C%22%20%20target%3D%5C%22_blank%5C%22%3E%7Busername%7D%3C%2Fa%3E%A3%BA%0D%0A%3C%2Fdiv%3E%0D%0A%3Cdiv%20style%3D%5C%22padding%3A0%200%205px%2020px%3B%5C%22%3E%0D%0A%3Ca%20href%3D%5C%22%7Blink%7D%5C%22%20style%3D%5C%22color%3A%23333%3Btext-decoration%3Anone%3B%5C%22%20target%3D%5C%22_blank%5C%22%3E%7Bmessage%7D%3C%2Fa%3E%0D%0A%3C%2Fdiv%3E%22%3B%7D&jscharset=0&cachelife=0',
    'parameter' => 
    array (
      'module' => 'doing.inc.php',
      'cachelife' => '0',
      'settings' => 
      array (
        'title' => '最新记录',
        'uid' => '',
        'mood' => '0',
        'start' => '0',
        'limit' => '10',
        'template' => '
<div style=\\"padding:0 0 5px 0;\\">
<a href=\\"{userlink}\\" target=\\"_blank\\"><img src=\\"{photo}\\" width=\\"18\\" height=\\"18\\" align=\\"absmiddle\\"></a> <a href=\\"{userlink}\\"  target=\\"_blank\\">{username}</a>：
</div>
<div style=\\"padding:0 0 5px 20px;\\">
<a href=\\"{link}\\" style=\\"color:#333;text-decoration:none;\\" target=\\"_blank\\">{message}</a>
</div>',
      ),
      'jscharset' => '0',
    ),
    'comment' => '获取UCHome的最新记录',
    'type' => '5',
  ),
  'UCHome_竞价排名' => 
  array (
    'url' => 'function=module&module=html.inc.php&settings=a%3A3%3A%7Bs%3A4%3A%22type%22%3Bs%3A1%3A%220%22%3Bs%3A4%3A%22code%22%3Bs%3A27%3A%22%3Cdiv%20id%3D%5C%22sidefeed%5C%22%3E%3C%2Fdiv%3E%22%3Bs%3A4%3A%22side%22%3Bs%3A1%3A%220%22%3B%7D&jscharset=0&cachelife=864000',
    'parameter' => 
    array (
      'module' => 'html.inc.php',
      'cachelife' => '864000',
      'settings' => 
      array (
        'type' => '0',
        'code' => '<div id=\\"sidefeed\\"></div>',
        'side' => '0',
      ),
      'jscharset' => '0',
    ),
    'comment' => '获取UCHome的竞价排名信息',
    'type' => '5',
  ),
);

$tasktypes = array(
  'promotion' => 
  array (
    'name' => '论坛推广任务',
    'version' => '1.0',
  ),
  'gift' => 
  array (
    'name' => '红包类任务',
    'version' => '1.0',
  ),
  'avatar' => 
  array (
    'name' => '头像类任务',
    'version' => '1.0',
  )
);

?>