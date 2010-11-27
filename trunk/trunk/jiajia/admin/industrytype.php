<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function 管理后台行业分类类别页面
 * @version $Revision: 1393 $
 */

 //导入初始化  
require("../libraries/common.inc.php");

//导入当前管理员信息、后台言标题信息
require("session_cp.inc.php");

//导入类型缓存数据
require(LIB_PATH. "cache.class.php");

//导入 缓存文件管理类
include(CACHE_PATH. "cache_type.php");

//实例缓存对象
$cache = new Caches();

//初始化行业类别文件名
$tpl_file = "industrytype";

//行业类别保存
if (isset($_POST['do'])) {
	$do = trim($_POST['do']);
	if ($do == "save") {
		$ins_arr = array();
		$tmp_arr = explode("\r\n", $_POST['data']['sort']);
		array_filter($tmp_arr);
		$i = 1;
		foreach ($tmp_arr as $key=>$val) {
			$ins_arr[$i] = "(".$i.",'".$val."')";
			$i++;
		}
		if (!empty($ins_arr)) {
			$ins_str = "REPLACE INTO {$tb_prefix}industrytypes (id,name) VALUES ".implode(",", $ins_arr).";";
			$pdb->Execute($ins_str);
		}		
		if($cache->updateTypes()){
			flash("success");
		}else{
			flash();
		}
	}
}
//赋值行业类别
if (!empty($_PB_CACHE['industrytype'])) {
	setvar("sorts", implode("\r\n", $_PB_CACHE['industrytype']));
}
//显示行业类型
template($tpl_file);
?>