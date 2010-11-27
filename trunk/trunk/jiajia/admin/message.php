<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * 
 * @version $Revision: 1393 $
 */
require("../libraries/common.inc.php");
uses("message");
require(PHPB2B_ROOT.'libraries/page.class.php');
require("session_cp.inc.php");
$message = new Messages();
$page = new Pages();
$conditions = array();
$tpl_file = "message";
if (isset($_POST['del']) && is_array($_POST['id'])) {
	$deleted = $message->del($_POST['id']);
	if (!$deleted) {
		flash();
	}
}
if (isset($_POST['save'])) {
	$sended = $message->SendToUser($current_adminer, $_POST['to_username'], $_POST['data']['message']);
	if (!$sended) {
		flash(null, null, 0);
	}else{
		pheader("location:message.php");
	}
}
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == 'search') {
		if (!empty($_GET['q'])) $conditions[]= "title like '%".trim($_GET['q'])."%'";
	}
	if ($do == "send") {
		$tpl_file = "message.send";
		template($tpl_file);
		exit;
	}
	if ($do=="del" && !empty($id)) {
		$message->del($id);
	}
}
$amount = $pdb->GetOne("select count(id) from {$tb_prefix}messages");
$page->setPagenav($amount);
$result = $message->findAll("id,cache_from_username,cache_to_username,title,content,created", null, $conditions, "id DESC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$result[$i]['pubdate'] = date("Y-m-d", $result[$i]['created']);
	}
	setvar("Items", $result);
}
setvar("ByPages", $page->getPagenav());
template($tpl_file);
?>