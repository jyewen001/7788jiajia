<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function 管理后台行业分类信息页面
 * @version $Revision: 1393 $
 */

 //导入初始化 
require("../libraries/common.inc.php");
//分页类类库
require(LIB_PATH. 'page.class.php');
require("session_cp.inc.php");
require(LIB_PATH. "json_config.php");
require(LIB_PATH. "cache.class.php");
//导入类型缓存数据
include(CACHE_PATH. "cache_type.php");
//导入行业缓存数据
include(CACHE_PATH. "cache_industry.php");
uses("industry", "typeoption");
$cache = new Caches();
$typeoption = new Typeoption();
$industry = new Industries();
$condition = null;
$conditions = array();
$tpl_file = "industry";
$page = new Pages();
//向smarty模板设置缓存数据
setvar("Types", $_PB_CACHE['industrytype']);
//copy一份数据
$cache_items = $_PB_CACHE['industry'];
//获取‘是’、‘否’选项
setvar("AskAction", $typeoption->get_cache_type("common_option"));
//行业分类信息删除
if (isset($_POST['del'])) {
	if (!empty($_POST['id'])) {
		$industry->del($_POST['id']);
	}
}
//清理信息
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "clear") {
		foreach ($_POST['data']['level'] as $key=>$val){
			$result = $pdb->Execute("DELETE FROM {$tb_prefix}industries WHERE level='".$val."'");
		}
		if(!$result){
			flash();
		}
	}
}
//行业分类信息修改
if (isset($_POST['update_batch'])) {
	if (!empty($_POST['data']['iname'])) {
		for($i=0; $i<count($_POST['data']['iname']); $i++) {
			$pdb->Execute("UPDATE {$tb_prefix}industries SET name = '".$_POST['data']['iname'][$i]."' WHERE id='".$_POST['iid'][$i]."'");
		}
		for($i=0; $i<count($_POST['data']['iname']); $i++) {
			$pdb->Execute("UPDATE {$tb_prefix}industries SET display_order = '".$_POST['data']['display_order'][$i]."' WHERE id='".$_POST['iid'][$i]."'");
		}
	}
	flash("success","industry.php");
}

//行业分类信息新增数据
if (isset($_POST['save'])) {
	if (isset($_POST['data']['industry']['parent_id'])) {
		$parent_id = $_POST['data']['industry']['parent_id'];
		if ($parent_id == 0) {
			$top_parentid = $_POST['data']['industry']['top_parentid'] = 0;
			$level = $_POST['data']['industry']['level'] = 1;
		}else{
			if (array_key_exists($parent_id, $cache_items[1])) {
				$level = $_POST['data']['industry']['level'] = 2;
				$top_parentid = $_POST['data']['industry']['top_parentid'] = $parent_id;
			}elseif (array_key_exists($parent_id, $cache_items[2])){
				$level = $_POST['data']['industry']['level'] = 3;
				$top_parentid = $_POST['data']['industry']['top_parentid'] = $pdb->GetOne("SELECT parent_id FROM {$tb_prefix}industries WHERE id=".$parent_id);
			}
		}
	}
	if (isset($_POST['id'])) {
		$id = intval($_POST['id']);
		$result = $industry->save($_POST['data']['industry'], "update", $id);
	}elseif (!empty($_POST['data']['names'])){
		$names = explode("\r\n", $_POST['data']['names']);
		$tmp_name = array();
		if (!empty($names)) {
			foreach ($names as $val) {
				$name = $val;
				if(!empty($name)) $tmp_name[] = "('".$name."','".$_POST['data']['industry']['url']."','".$parent_id."','".$top_parentid."','".$level."','".$_POST['data']['industry']['display_order']."','".$_POST['data']['industry']['industrytype_id']."')";
			}
			$values = implode(",", $tmp_name);
			$sql = "INSERT INTO {$tb_prefix}industries (name,url,parent_id,top_parentid,level,display_order,industrytype_id) VALUES ".$values;
			$result = $pdb->Execute($sql);
		}
	}
	if ($result) {
		$cache->writeCache("industry", "industry");
	}
}

//级别分组排序
if (isset($_GET['do'])) {
	$do = trim($_GET['do']);
	if (!empty($_GET['id'])) {
		$id = intval($_GET['id']);
	}
		//行业分类信息刷新
	if ($do == "level") {
		if(!empty($id)){
			if ($_GET['action']=="up") {
				$pdb->Execute("UPDATE {$tb_prefix}industries SET display_order=display_order-1 WHERE id=".$id);
			}elseif ($_GET['action']=="down"){
				$pdb->Execute("UPDATE {$tb_prefix}industries SET display_order=display_order+1 WHERE id=".$id);
			}
		}
	}
	if ($do == "refresh") {
		$cache->writeCache("industry", "industry"); 
		$industry->updateCache();
		flash("success");
	}
	//行业分类信息搜索
	if ($do == "search") {
		if (!empty($_GET['name'])) {
			$conditions[] = "name LIKE '%".$_GET['name']."%'";
		}
		if (isset($_GET['parentid'])) {
			$conditions[] = "parent_id=".intval($_GET['parentid']);
		}
		if (isset($_GET['level'])) {
			$conditions[] = "level=".intval($_GET['level']);
		}
		if (isset($_GET['typeid'])) {
			$conditions[] = "industrytype_id=".intval($_GET['typeid']);
		}
	}
	//行业分类信息编辑
	if ($do == "edit") {
		setvar("CacheItems", $industry->getTypeOptions());
		if (!empty($id)) {
			$res = $pdb->GetRow("SELECT * FROM {$tb_prefix}industries WHERE id=".$id);
			setvar("item", $res);
		}
		$tpl_file = "industry.edit";
		template($tpl_file);
		exit;
	}
	//清理所有数据，进入清理完空页面
	if ($do == "clear") {
		$tpl_file = "industry.clear";
		template($tpl_file);
		exit;
	}
}
//获取记录总数
$amount = $industry->findCount(null, $conditions);
//设置总页数
$page->setPagenav($amount);
//获取当前页数的记录数
$result = $industry->findAll("id,name,name as title,highlight,parent_id,industrytype_id,top_parentid,level,display_order", null, $conditions, "level ASC,display_order ASC,id ASC", $page->firstcount, $page->displaypg);
if (!empty($result)) {
	for($i=0; $i<count($result); $i++){
		$tmp_name = array();
		if($result[$i]['level']>1){
			if($result[$i]['level']>2){
				$tmp_name[] = $result[$i]['name'];
				if($_PB_CACHE['industry'][2][$result[$i]['parent_id']]) $tmp_name[] = "<a href='industry.php?do=search&parentid=".$result[$i]['parent_id']."'>".$_PB_CACHE['industry'][2][$result[$i]['parent_id']]."</a>";
				if($_PB_CACHE['industry'][1][$result[$i]['top_parentid']]) $tmp_name[] = "<a href='industry.php?do=search&parentid=".$result[$i]['top_parentid']."'>".$_PB_CACHE['industry'][1][$result[$i]['top_parentid']]."</a>";
			}else{
				$tmp_name[] = "<a href='industry.php?do=search&parentid=".$result[$i]['id']."'>".$result[$i]['name']."</a>";
				if($_PB_CACHE['industry'][1][$result[$i]['parent_id']]) $tmp_name[] = "<a href='industry.php?do=search&parentid=".$result[$i]['parent_id']."'>".$_PB_CACHE['industry'][1][$result[$i]['parent_id']]."</a>";
			}
		}else{
			$tmp_name[] = "<a href='industry.php?do=search&parentid=".$result[$i]['id']."'>".$result[$i]['name']."</a>";
		}
		$result[$i]['title'] = implode("&laquo;", $tmp_name);
	}
	setvar("Items", $result);
	setvar("ByPages", $page->pagenav);
}
$stats = $pdb->GetArray("SELECT level,count(id) as amount FROM ".$tb_prefix."industries GROUP BY level");
setvar("LevelStats", $stats);
//显示行业分类信息
template($tpl_file);
?>