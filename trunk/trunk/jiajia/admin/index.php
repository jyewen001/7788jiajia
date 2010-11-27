<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function ��̨��ҳ����
 * @version $Revision: 1393 $
 */
define('CURSCRIPT', 'index');
require("../libraries/common.inc.php");
//��֤��¼����Ա�Ự�Ƿ���ڻ��Ƿ���admin.
if(empty($_COOKIE[$cookiepre.'admin']) || !($_COOKIE[$cookiepre.'admin'])){
	//�Ự��ʧ
	pheader("location:login.php");
}
//��ʼ��session
require("session_cp.inc.php");
//��ʼ��$menus�˵�����Ϣ
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
//����ģ��·��
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
//���ò˵���Ϣֵ
$smarty->assign("ActionMenus", json_encode($menus));
//Smarty ��ʾģ�� 
$tpl_file = "index";
template($tpl_file);
?>