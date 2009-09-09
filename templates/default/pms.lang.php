<?php

// P.M. Pack for Discuz! Version 1.0
// Translated by Crossday

// ATTENTION: Please add slashes(\) before (') and (")

$language = array
(

	'reason_moderate_subject' => '您发表的主题被执行管理操作',
	'reason_moderate_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的主题被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]

[b]主题:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]发表时间:[/b] {$thread[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'reason_merge_subject' => '您发表的主题被执行合并操作',
	'reason_merge_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的主题被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]

[b]主题:[/b] {$thread[subject]}
[b]发表时间:[/b] {$thread[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]合并后的主题:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$other[subject]}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',
	'reason_delete_post_subject' => '您发表的回复被执行管理操作',
	'reason_delete_post_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的回复被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]

[b]发表时间:[/b] {$post[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'reason_ban_post_subject' => '您发表的回复被执行管理操作',
	'reason_ban_post_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]

[b]发表时间:[/b] {$post[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'reason_warn_post_subject' => '您发表的帖子被执行管理操作',
	'reason_warn_post_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]
[quote]{$post[message]}[/quote]
连续 $warningexpiration 天内累计 $warninglimit 次警告，您将被自动禁止发帖 $warningexpiration 天。
截至目前，您已被警告 $authorwarnings 次，请注意！

[b]发表时间:[/b] {$post[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'reason_move_subject' => '您发表的主题被执行管理操作',
	'reason_move_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的主题被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 移动 操作。[/b]

[b]主题:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]发表时间:[/b] {$thread[dateline]}
[b]原论坛:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]目标论坛:[/b] [url={$boardurl}forumdisplay.php?fid={$toforum[fid]}]{$toforum[name]}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'rate_reason_subject' => '您发表的帖子被评分',
	'rate_reason_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的帖子被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 评分。[/b]
[quote]{$post[message]}[/quote]

[b]发表时间:[/b] {$post[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]所在主题:[/b] [url={$boardurl}viewthread.php?tid={$tid}&page={$page}#pid{$pid}]{$thread[subject]}[/url]

[b]评分分数:[/b] {$ratescore}
[b]操作理由:[/b] {$reason}',

	'rate_removereason_subject' => '您发表的帖子的评分被撤销',
	'rate_removereason_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表帖子的评分被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 撤销。[/b]
[quote]{$post[message]}[/quote]

[b]发表时间:[/b] {$post[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]
[b]所在主题:[/b] [url={$boardurl}viewthread.php?tid={$tid}&page={$page}#pid{$pid}]{$thread[subject]}[/url]

[b]评分分数:[/b] {$ratescore}
[b]操作理由:[/b] {$reason}',

	'transfer_subject' => '您收到一笔积分转账',
	'transfer_message' => '这是由论坛系统自动发送的通知短消息。

[b]您收到一笔来自他人的积分转账。[/b]

[b]来自:[/b] [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url]
[b]时间:[/b] {$transfertime}
[b]积分:[/b] {$extcredits[$creditstrans][title]} {$amount} {$extcredits[$creditstrans][unit]}
[b]净收入:[/b] {$extcredits[$creditstrans][title]} {$netamount} {$extcredits[$creditstrans][unit]}

[b]附言:[/b] {$transfermessage}

详情请[url={$boardurl}memcp.php?action=creditslog]点击这里[/url]访问您的积分转账与兑换记录。',

	'reportpost_subject'	=> '$discuz_userss 向您报告一篇帖子',
	'reportpost_message'	=> '[i]{$discuz_userss}[/i] 向您报告以下的帖子，详细内容请访问:
[url]{$posturl}[/url]

他/她的报告理由是: {$reason}',

	'addfunds_subject' => '积分充值成功完成',
	'addfunds_message' => '这是由论坛系统自动发送的通知短消息。

[b]您提交的积分充值请求已成功完成，相应数额的积分已经存入您的积分账户。[/b]

[b]订单号:[/b] {$order[orderid]}
[b]提交时间:[/b] {$submitdate}
[b]确认时间:[/b] {$confirmdate}

[b]支出:[/b] 人民币 {$order[price]} 元
[b]收入:[/b] {$extcredits[$creditstrans][title]} {$order[amount]} {$extcredits[$creditstrans][unit]}

详情请[url={$boardurl}memcp.php?action=creditslog]点击这里[/url]访问您的积分转账与兑换记录。',

	'trade_seller_send_subject' => '有买家购买您的商品',
	'trade_seller_send_message' => '这是由论坛系统自动发送的通知短消息。

买家 {$user} 购买您的商品 {$itemsubject}

买家已付款，等待您发货，请[url={$boardurl}trade.php?orderid={$orderid}]点击这里[/url]查看详情。',

	'trade_buyer_confirm_subject' => '您购买的商品已经发货',
	'trade_buyer_confirm_message' => '这是由论坛系统自动发送的通知短消息。

您购买的商品 {$itemsubject}

卖家 {$user} 已发货，等待您的确认，请[url={$boardurl}trade.php?orderid={$orderid}]点击这里[/url]查看详情。',

	'trade_fefund_success_subject' => '您购买的商品已成功退款',
	'trade_fefund_success_message' => '这是由论坛系统自动发送的通知短消息。

商品 {$itemsubject} 已退款成功，请[url={$boardurl}trade.php?orderid={$orderid}]点击这里[/url]给对方评分。',

	'trade_success_subject' => '商品交易已成功完成',
	'trade_success_message' => '这是由论坛系统自动发送的通知短消息。

商品 {$itemsubject} 已交易成功，请[url={$boardurl}trade.php?orderid={$orderid}]点击这里[/url]给对方评分。',

	'activity_apply_subject' => '活动的申请已通过批准',
	'activity_apply_message' => '这是由论坛系统自动发送的通知短消息。

活动 [b]{$activity_subject}[/b] 的发起者已批准您参加此活动，请[url={$boardurl}viewthread.php?tid={$tid}]点击这里[/url]查看详情。',

	'activity_delete_subject' => '您申请的活动被发起者拒绝',
	'activity_delete_message' => '这是由论坛系统自动发送的通知短消息。

您申请的活动 [b]{$activity_subject}[/b] 已被发起者拒绝，请[url={$boardurl}viewthread.php?tid={$tid}]点击这里[/url]查看详情。',

	'reward_question_subject' => '您发表的悬赏被设置了最佳答案',
	'reward_question_message' => '这是由论坛系统自动发送的通知短消息。

[b]您发表的悬赏被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 设置了 最佳答案。[/b]

[b]悬赏:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]发表时间:[/b] {$thread[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forum[name]}[/url]

如果您对本操作有异议，请与作者取得联系。',

	'reward_bestanswer_subject' => '您发表的回复被选为最佳答案',
	'reward_bestanswer_message' => '这是由论坛系统自动发送的通知短消息。

[b]您的回复被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 选为悬赏最佳答案。[/b]

[b]悬赏:[/b] [url={$boardurl}viewthread.php?tid={$thread[tid]}]{$thread[subject]}[/url]
[b]发表时间:[/b] {$thread[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forum[name]}[/url]

如果您对本操作有异议，请与作者取得联系。',

	'modthreads_delete_subject' => '您发表的主题审核失败',
	'modthreads_delete_message' => '这是由论坛系统自动发送的通知短消息。

[b]审核失败:[/b] 您发表的主题 [b][u] {$threadsubject} [/u][/b] 没有通过审核，现已被删除!
[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'modthreads_validate_subject' => '您发表的主题已审核通过',
	'modthreads_validate_message' => '这是由论坛系统自动发送的通知短消息。

[b]审核通过:[/b] 您发表的主题 [url={$boardurl}viewthread.php?tid={$tid}]{$threadsubject}[/url] 已经审核通过!
[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'modreplies_delete_subject' => '您发表的回复审核失败',
	'modreplies_delete_message' => '这是由论坛系统自动发送的通知短消息。

[b]审核失败:[/b] 您发表回复没有通过审核，现已被删除!
[b]所在主题:[/b] [url={$boardurl}viewthread.php?tid={$tid}]点此查看[/url]
[b]回复内容:[/b]
[quote]
	$post
[/quote]
[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'modreplies_validate_subject' => '您发表的回复已审核通过',
	'modreplies_validate_message' => '这是由论坛系统自动发送的通知短消息。

[b]审核通过:[/b] 您发表的回复已经审核通过。
[b]所在主题:[/b] [url={$boardurl}viewthread.php?tid={$tid}]点此查看[/url]
[b]回复内容:[/b]
[quote]
	$post
[/quote]
[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'magics_sell_subject' => '您的道具成功出售',
	'magics_sell_message' => '您的 {$magic[name]} 道具被 {$discuz_userss} 购买，获得收益 {$totalcredit}',

	'magics_receive_subject' => '您收到好友送来的道具',
	'magics_receive_message' => '你收到 {$discuz_userss} 送给你 {$magicarray[$magicid][name]} 道具，请到[url={$boardurl}magic.php]我的道具箱[/url]查收',

	'reason_copy_subject' => '您发表的主题被执行复制操作',
	'reason_copy_message' => '这是由论坛系统自动发送的通知短消息。

[b]以下您所发表的主题被 [url={$boardurl}space.php?uid={$discuz_uid}][i]{$discuz_userss}[/i][/url] 执行 {$modaction} 操作。[/b]

[b]主题:[/b] {$thread[subject]}
[b]发表时间:[/b] {$thread[dateline]}
[b]所在版块:[/b] [url={$boardurl}forumdisplay.php?fid={$fid}]{$forumname}[/url]

[b]复制后的主题:[/b] [url={$boardurl}viewthread.php?tid=$threadid]{$thread[subject]}[/url]

[b]操作理由:[/b] {$reason}

如果您对本管理操作有异议，请与我取得联系。',

	'eccredit_subject' => '商品交易的对方已经评价，请回评',
	'eccredit_message' => '这是由论坛系统自动发送的通知短消息。

[url={$boardurl}trade.php?orderid=$orderid]查看交易单[/url]

与您交易的 $discuz_userss 已经给您作了评价，请尽快评价对方。',

	'buddy_new_subject' => '{$discuz_userss} 添加您为好友',
	'buddy_new_message' => '这是由系统自动发送的通知短消息。

{$discuz_userss} 添加您为好友。
您可以[url={$boardurl}space.php?uid=$discuz_uid]点击这里[/url]查看他(她)的个人资料，或者[url={$boardurl}my.php?item=buddylist&newbuddyid={$discuz_uid}&buddysubmit=yes]把他(她)加为您的好友[/url]。',
	'buddy_new_uch_message' => '这是由系统自动发送的通知短消息。

{$discuz_userss} 添加您为好友。
您可以[url={$boardurl}space.php?uid=$discuz_uid]点击这里[/url]查看他(她)的个人资料，或者[url={$boardurl}my.php?item=buddylist&newbuddyid={$discuz_uid}&buddysubmit=yes]把他(她)加为您的好友[/url]。
也可以[url={$uchomeurl}/space.php?uid={$discuz_uid}]点击这里[/url]查看他(她)的个人空间。',

	'task_reward_subject' => '完成任务奖励通知',
	'task_reward_credit_message' => '这是由论坛系统自动发送的通知短消息。
{$discuz_userss} 恭喜您完成任务 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的积分奖励如下：
	{$extcredits[$task[prize]][title]} {$task[bonus]} {$extcredits[$task[prize]][unit]}
	[url={$boardurl}memcp.php?action=credits]点这里查看我的积分[/url] [url={$boardurl}memcp.php?action=creditslog&operation=creditslog]点这里查看积分收益记录[/url]
希望您再接再厉！',
	'task_reward_magic_message' => '这是由论坛系统自动发送的通知短消息。
{$discuz_userss} 恭喜您完成任务 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的道具奖励如下：
	[url={$boardurl}magic.php]{$magicname}[/url] {$task[bonus]} 枚
希望您再接再厉！',
	'task_reward_medal_message' => '这是由论坛系统自动发送的通知短消息。
{$discuz_userss} 恭喜您完成任务 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的勋章奖励如下：
	[url={$boardurl}medal.php]{$medalname}[/url] 有效期 {$task[bonus]} 天
希望您再接再厉！',
	'task_reward_invite_message' => '这是由论坛系统自动发送的通知短消息。
{$discuz_userss} 恭喜您完成任务 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的邀请码奖励如下：
	共 [url={$boardurl}invite.php]{$task[prize]}[/url] 个 有效期 {$task[bonus]} 天
	{$rewards}
希望您再接再厉！',
	'task_reward_group_message' => '这是由论坛系统自动发送的通知短消息。
{$discuz_userss} 恭喜您完成任务 [url={$boardurl}task.php?action=view&id={$task[taskid]}]{$task[name]}[/url]
您已得到的特殊用户组奖励如下：
	用户组 {$grouptitle} 有效期 {$task[bonus]} 天  [url={$boardurl}memcp.php?action=usergroups]点这里查看当前用户组[/url]
希望您再接再厉！',

);

?>