<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function �����̨��ҵ�������ҳ��
 * @version $Revision: 1393 $
 */

 //�����ʼ��  
require("../libraries/common.inc.php");

//���뵱ǰ����Ա��Ϣ����̨�Ա�����Ϣ
require("session_cp.inc.php");

//�������ͻ�������
require(LIB_PATH. "cache.class.php");

//���� �����ļ�������
include(CACHE_PATH. "cache_type.php");

//ʵ���������
$cache = new Caches();

//��ʼ����ҵ����ļ���
$tpl_file = "industrytype";

//��ҵ��𱣴�
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
//��ֵ��ҵ���
if (!empty($_PB_CACHE['industrytype'])) {
	setvar("sorts", implode("\r\n", $_PB_CACHE['industrytype']));
}
//��ʾ��ҵ����
template($tpl_file);
?>