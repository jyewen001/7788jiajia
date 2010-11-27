<?php
 /**
  * @function 网站公告信息接收处理类 
  * @author叶稳
  * @date 2010-11-25 下午04:39:10 
  * tags
  * company:秦运恒
  */

	define('CURSCRIPT', 'announce');
	require("libraries/common.inc.php");
	require("share.inc.php");
	include(CACHE_PATH. "cache_type.php");
	
	uses("announcement");
	$announce = new Announcements();
	$viewhelper->setTitle(L("announce", "tpl"));
	$viewhelper->setPosition(L("announce", "tpl"), "announce.php");
	
	
	if (isset($_GET['title'])) {
		$title = rawurldecode(trim($_GET['title']));
		$res = $announce->findBySubject($title);
		$id = $res['id'];
	}
	
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
	}
	if(!empty($id)){
		$result = $announce->findById($id);
		if (!empty($result)) {
			$result['message'] = nl2br($result['message']);
			$viewhelper->setTitle($result['subject']);
			$viewhelper->setPosition($result['subject']);
			$viewhelper->setMetaDescription($result['message']);
			setvar("item", $result);
			setvar("PageTitle", strip_tags($result['subject']));
			render("announce.detail", true);
		}
	}
	//获取公告类型
	$type = isset($_GET['annnounce_id'])?$_GET['annnounce_id']:0;
	//查询条件
	$condition = null; 
	if($type != 0){
		$condition = "announcetype_id = $type";
		setvar("_announce", $_PB_CACHE['announcementtype'][$type]."公告"); 
	}
	$result = $announce->findAll("*", null, $condition, "display_order ASC,id DESC");
	if (!empty($result)) {
		for($i=0; $i<count($result); $i++){
			if (!empty($result[$i]['created'])) {
				$result[$i]['pubdate'] = date("Y-m-d", $result[$i]['created']);
			}
		}
		setvar("Items", $result);
	} 
	render("announce");
?>