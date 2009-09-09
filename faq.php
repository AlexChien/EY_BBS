<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: faq.php 16688 2008-11-14 06:41:07Z cnteacher $
*/

define('CURSCRIPT', 'faq');
require_once './include/common.inc.php';

$discuz_action = 51;
$keyword = isset($keyword) ? dhtmlspecialchars($keyword) : '';

$faqparent = $faqsub = array();
$query = $db->query("SELECT id, fpid, title FROM {$tablepre}faqs ORDER BY displayorder");
while($faq = $db->fetch_array($query)) {
	if(empty($faq['fpid'])) {
		$faqparent[$faq['id']] = $faq;
	} else {
		$faqsub[$faq['fpid']][] = $faq;
	}
}

if($action == 'faq') {

	$id = intval($id);
	if($ffaq = $db->fetch_first("SELECT title FROM {$tablepre}faqs WHERE fpid='$id'")) {

		$navigation = "&raquo; $ffaq[title]";
		$faqlist = array();
		$messageid = empty($messageid) ? 0 : $messageid;
		$query = $db->query("SELECT id,title,message FROM {$tablepre}faqs WHERE fpid='$id' ORDER BY displayorder");
		while($faq = $db->fetch_array($query)) {
			if(!$messageid) {
				$messageid = $faq['id'];
			}
			$faqlist[] = $faq;
		}

	} else {
		showmessage("faq_content_empty", 'faq.php');
	}

} elseif($action == 'search') {

	if(submitcheck('searchsubmit')) {
		$keyword = isset($keyword) ? trim($keyword) : '';
		if($keyword) {
			$sqlsrch = '';
			$searchtype = in_array($searchtype, array('all', 'title', 'message')) ? $searchtype : 'all';
			switch($searchtype) {
				case 'all':
					$sqlsrch = "WHERE title LIKE '%$keyword%' OR message LIKE '%$keyword%'";
					break;
				case 'title':
					$sqlsrch = "WHERE title LIKE '%$keyword%'";
					break;
				case 'message':
					$sqlsrch = "WHERE message LIKE '%$keyword%'";
					break;
			}

			$keyword = stripslashes($keyword);
			$faqlist = array();
			$query = $db->query("SELECT fpid, title, message FROM {$tablepre}faqs $sqlsrch ORDER BY displayorder");
			while($faq = $db->fetch_array($query)) {
				if(!empty($faq['fpid'])) {
					$faq['title'] = preg_replace("/(?<=[\s\"\]>()]|[\x7f-\xff]|^)(".preg_quote($keyword, '/').")(([.,:;-?!()\s\"<\[]|[\x7f-\xff]|$))/siU", "<u><b><font color=\"#FF0000\">\\1</font></b></u>\\2", stripslashes($faq['title']));
					$faq['message'] = preg_replace("/(?<=[\s\"\]>()]|[\x7f-\xff]|^)(".preg_quote($keyword, '/').")(([.,:;-?!()\s\"<\[]|[\x7f-\xff]|$))/siU", "<u><b><font color=\"#FF0000\">\\1</font></b></u>\\2", stripslashes($faq['message']));
					$faqlist[] = $faq;
				}
			}
		} else {
			showmessage('faq_keywords_empty', 'faq.php');
		}
	}

} elseif($action == 'credits') {

	if(empty($extcredits)) {
		showmessage('credits_disabled');
	}

	require_once DISCUZ_ROOT.'./include/forum.func.php';
	$forumlist = forumselect(FALSE, 0, $fid);

	$policyarray = array();
	foreach($creditspolicy as $operation => $policy) {
		!$forum && $policyarray[$operation] = $policy;
		if(in_array($operation, array('post', 'reply', 'digest', 'postattach', 'getattach'))) {
			if($forum) {
				$policyarray[$operation] = $forum[$operation.'credits'] ? $forum[$operation.'credits'] : $creditspolicy[$operation];
			}
		}
	}

	$creditsarray = array();
	for($i = 1; $i <= 8; $i++) {
		if(isset($extcredits[$i])) {
			foreach($policyarray as $operation => $policy) {
				$addcredits = in_array($operation, array('getattach', 'forum_getattach', 'sendpm', 'search')) ? -$policy[$i] : $policy[$i];
				if($operation != 'lowerlimit') {
					$creditsarray[$operation][$i] = empty($policy[$i]) ? 0 : (is_numeric($policy[$i]) ? '<b>'.($addcredits > 0 ? '+'.$addcredits : $addcredits).'</b> '.$extcredits[$i]['unit'] : $policy[$i]);
				} else {
					$creditsarray[$operation][$i] = '<b>'.intval($addcredits).'</b> '.$extcredits[$i]['unit'];
				}
			}
		}
	}

	if(!$forum) {
		$query = $db->query("SELECT * FROM {$tablepre}usergroups WHERE type='member' ORDER BY type");
		while($group = $db->fetch_array($query)) {
			$extgroups[] = $group;
		}
	}	
		
	include template('credits');
	exit;

}

include template('faq');

?>