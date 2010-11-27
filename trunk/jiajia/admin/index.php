<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function 后台首页加载
 * @version $Revision: 1393 $
 */
define('CURSCRIPT', 'index');
require("../libraries/common.inc.php");
//验证登录管理员会话是否存在或是否是admin.
if(empty($_COOKIE[$cookiepre.'admin']) || !($_COOKIE[$cookiepre.'admin'])){
	//会话消失
	pheader("location:login.php");
}
//初始化session
require("session_cp.inc.php");
//初始化$menus菜单项信息
require("menu.php");

if (!empty($adminer->info['permissions']) && $adminer->info['member_id']!=$administrator_id) {
	$allowed_permissions = explode(",", $adminer->info['permissions']);
	foreach ($menus as $key=>$val) {
		if (!in_array($key, $allowed_permissions)) {
			unset($menus[$key]);
		}else{
			foreach ($val['children'] as $key1=>$val1) {
				if (!in_array($key1, $allowed_permissions)) {
					unset($menus[$key]['children'][$key1]);
				}
			}
		}
	}
}

require(LIB_PATH. "json_config.php");
//设置模板路径
$smarty->template_dir = "template/";

if ($charset!="utf-8") {
	$menus = iconv_all($charset, "utf-8", $menus);
}

function iconv_all($in_charset,$out_charset,$in)
{
    if(is_string($in))
    {
        $in=iconv($in_charset,$out_charset,$in);
    }
    elseif(is_array($in))
    {
        foreach($in as $key=>$value)
        {
            $in[$key]=iconv_all($in_charset,$out_charset,$value);
        }
    }
    elseif(is_object($in))
    {
        foreach($in as $key=>$value)
        {
            $in->$key=iconv_all($in_charset,$out_charset,$value);
        }
    }
 
    return $in;
}
//设置菜单信息值
$smarty->assign("ActionMenus", json_encode($menus));
//Smarty 显示模板 
$tpl_file = "index";
template($tpl_file);
?>