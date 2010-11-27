<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function 管理后台客服中心页面
 * @version $Revision: 1393 $
 */

 //导入初始化 
require("../libraries/common.inc.php");

//分页类类库
require(PHPB2B_ROOT.'./libraries/page.class.php');

//导入当前管理员信息、后台言标题信息
require("session_cp.inc.php");

//导入缓存文件管理类
require(LIB_PATH. "cache.class.php");

//使用客服中心、网站基本信息操作对象、类型选项管理对象
uses("service","typeoption","setting");

$page = new Pages();
$cache = new Caches();
$setting = new Settings();
$typeoption = new Typeoption();
$service = new Services();
$conditions = null;
$tpl_file = "service";

//向smarty模板添加数据
setvar("Status", $typeoption->get_cache_type("common_status"));
setvar("ServiceTypes", $typeoption->get_cache_type("service_type"));

//保存服务条款
if (isset($_POST['save_client'])) {
	if (!empty($_POST['data']['setting1'])) {
		$updated = $setting->replace($_POST['data']['setting1'], 1);
		if($updated) {
			$cache->writeCache("setting1", "setting1");
			flash("success");
		}
	}
	flash();
}
//保存修改客服中心客户提交信息
if (isset($_POST['save']) && !empty($_POST['data']['service'])) {
	$vals = array();
	$vals = $_POST['data']['service'];
	$vals['modified'] = $time_stamp;
	$result = $service->save($vals, "update", $_POST['id']);
	if (!empty($vals['revert_content'])) {
		$datas = array(
		"actor"=>$adminer_info['last_name'],
		"action"=> L("feed_revert", "tpl"),
		"do"=> L("feed_problem", "tpl"),
		"subject"=> '<a href="service/detail.php?id='.$_POST['id'].'">'.$vals['title'].'</a>',
		);
		$sql = "INSERT INTO {$tb_prefix}feeds (type_id,type,member_id,username,data,created,modified,revert_date) VALUE ('1','service',".$current_adminer_id.",'".$adminer_info['last_name']."','".serialize($datas)."',".$time_stamp.",".$time_stamp.",".$time_stamp.")";
		$pdb->Execute($sql);
	}
	if (!$result) {
		flash();
	}
}

//get请求服务条款
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if ($do == "client") {
		$item = $setting->getValues();
		$tpl_file = "service.client";
		setvar("item", $item);
		template($tpl_file);
		exit;
	}

	//编辑
	if ($do == "edit" && !empty($id)) {
		$sql = "SELECT * FROM {$tb_prefix}services WHERE id=".$id;
		$res = $pdb->GetRow($sql);
		if (empty($res)) {
			flash();
		}else {
			setvar("item",$res);
		}
		$tpl_file = "service.edit";
		template($tpl_file);
		exit;
	}

	//搜索
	if ($do == "search") {
		if (!empty($_GET['type_id'])) {
			$conditions[] = "Service.type_id=".$_GET['type_id'];
		}
		if (!empty($_GET['q'])) {
			$conditions[] = "Service.title like '%".$_GET['q']."%' OR Service.content like '%".$_GET['q']."%'";
		}
	}
}

//获取记录总数
$amount = $service->findCount(null, $conditions,"Service.id");
//设置总页数
$page->setPagenav($amount);
//获取当前页数的记录数
setvar("Items",$service->findAll("*", null, $conditions, "Service.id DESC ",$page->firstcount,$page->displaypg));
//向smarty模板设置数据
setvar("ByPages",$page->pagenav);
if (isset($_REQUEST['del'])){
	$deleted = false;
	if(!empty($_POST['id'])) {
		$deleted = $service->del($_POST['id']);
	}
	if(!empty($_GET['id'])){
		$deleted = $service->del($_GET['id']);
	}
	if($deleted) {
		pheader("location:service.php");
	}
	else
	{
		flash();
	}
}
//显示
template($tpl_file);
?>