<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: forum.func.php 14122 2008-08-20 06:06:33Z cnteacher $
*/

$attachdir = './attachments';
$attachurl = 'attachments';

$cron_pushthread_week = rand(1, 7);
$cron_pushthread_hour = rand(1, 8);

$extcredits = Array
(
	1 => Array
	(
		'title' => $lang['init_credits_karma'],
		'showinthread' => '',
		'available' => 1
	),
	2 => Array
	(
		'title' => $lang['init_credits_money'],
		'showinthread' => '',
		'available' => 1
	)
);

$extrasql = <<<EOT
UPDATE cdb_forumlinks SET name='$lang[init_link]', description='$lang[init_link_note]' WHERE id='1';

UPDATE cdb_forums SET name='$lang[init_default_forum]' WHERE fid='2';

UPDATE cdb_onlinelist SET title='$lang[init_group_1]' WHERE groupid='1';
UPDATE cdb_onlinelist SET title='$lang[init_group_2]' WHERE groupid='2';
UPDATE cdb_onlinelist SET title='$lang[init_group_3]' WHERE groupid='3';
UPDATE cdb_onlinelist SET title='$lang[init_group_0]' WHERE groupid='0';

UPDATE cdb_ranks SET ranktitle='$lang[init_rank_1]' WHERE rankid='1';
UPDATE cdb_ranks SET ranktitle='$lang[init_rank_2]' WHERE rankid='2';
UPDATE cdb_ranks SET ranktitle='$lang[init_rank_3]' WHERE rankid='3';
UPDATE cdb_ranks SET ranktitle='$lang[init_rank_4]' WHERE rankid='4';
UPDATE cdb_ranks SET ranktitle='$lang[init_rank_5]' WHERE rankid='5';

UPDATE cdb_usergroups SET grouptitle='$lang[init_group_1]' WHERE groupid='1';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_2]' WHERE groupid='2';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_3]' WHERE groupid='3';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_4]' WHERE groupid='4';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_5]' WHERE groupid='5';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_6]' WHERE groupid='6';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_7]' WHERE groupid='7';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_8]' WHERE groupid='8';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_9]' WHERE groupid='9';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_10]' WHERE groupid='10';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_11]' WHERE groupid='11';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_12]' WHERE groupid='12';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_13]' WHERE groupid='13';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_14]' WHERE groupid='14';
UPDATE cdb_usergroups SET grouptitle='$lang[init_group_15]' WHERE groupid='15';

UPDATE cdb_crons SET name='$lang[init_cron_1]' WHERE cronid='1';
UPDATE cdb_crons SET name='$lang[init_cron_2]' WHERE cronid='2';
UPDATE cdb_crons SET name='$lang[init_cron_3]' WHERE cronid='3';
UPDATE cdb_crons SET name='$lang[init_cron_4]' WHERE cronid='4';
UPDATE cdb_crons SET name='$lang[init_cron_5]' WHERE cronid='5';
UPDATE cdb_crons SET name='$lang[init_cron_6]' WHERE cronid='6';
UPDATE cdb_crons SET name='$lang[init_cron_7]' WHERE cronid='7';
UPDATE cdb_crons SET name='$lang[init_cron_8]' WHERE cronid='8';
UPDATE cdb_crons SET name='$lang[init_cron_9]' WHERE cronid='9';
UPDATE cdb_crons SET name='$lang[init_cron_10]' WHERE cronid='10';
UPDATE cdb_crons SET name='$lang[init_cron_11]', weekday='$cron_pushthread_week', hour='$cron_pushthread_week' WHERE cronid='11';

UPDATE cdb_settings SET value='$lang[init_dataformat]' WHERE variable='dateformat';
UPDATE cdb_settings SET value='$lang[init_modreasons]' WHERE variable='modreasons';
UPDATE cdb_settings SET value='$lang[init_threadsticky]' WHERE variable='threadsticky';
UPDATE cdb_settings SET value='$lang[init_qihoo_searchboxtxt]' WHERE variable='qihoo_searchboxtxt';

UPDATE cdb_styles SET name='$lang[init_default_style]' WHERE styleid='1';

UPDATE cdb_templates SET name='$lang[init_default_template]', copyright='$lang[init_default_template_copyright]' WHERE templateid='1';

EOT;

?>