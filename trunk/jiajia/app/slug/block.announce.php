<?php
/**
 * author:叶稳 
 * function : Smarty自定义标签，公告单独数据显示功能模板。
 * @param $params  自定义标签参数，例 type、titlelen、infolen、row
 * @param $content 内容
 * @param $smarty 模板对象
 * date:2010-10-23 15:15
 */
function smarty_block_announce($params, $content, &$smarty) {
	if ($content === null) return;
	$conditions = array();
	
	//验证公告类是否存在
	if (!class_exists("Announcements")) { //非存在
		
		//引入公告类创建实例对象
		uses("announcement");
		
		$announce = new Announcements();
		$announce_controller = new Announcement();
	}else{
		//存在，创建实例对象 
	    $announce = new Announcements();
		$announce_controller = new Announcement();
	}
	
	//初始化数据显示行数
	$i_count = 1;
	
	//公告行数取整
	if (isset($params['row'])) {
		$i_count = intval($params['row']);
	}
	
	//公告类型条件设置 
	if (isset($params['typeid'])) {
		$conditions[] = "announcetype_id=".$params['typeid'];
	}
	//类型
	if (isset($params['type'])) {
		$type = explode(",", $params['type']);
		$type = array_unique($type);
		foreach ($type as $val) {
			switch ($val) {
				case 'new':
					$conditions[] = "display_expiration>".$announce->timestamp;
					break;
				default:
					break;
			}
		}		
	}
	//公告sql 设置条件
	$announce->setCondition($conditions);
	$row = $col = 0;
	$orderby = null;
	
	//排序
	if (isset($params['orderby'])) {
		$orderby = " ORDER BY ".trim($params['orderby'])." ";
	}else{
		$orderby = " ORDER BY id DESC ";
	}
	if (isset($params['row'])) {
		$row = $params['row'];
	}
	if (isset($params['col'])) {
		$col = $params['col'];
	}
	//公告sql 设置显示行数
	$announce->setLimitOffset($row, $col);	
	
	//sql语句
	$orderby = " ORDER BY display_order ASC,id DESC";
	$sql = "SELECT id,subject AS title,message,message AS content FROM {$announce->table_prefix}announcements ".$announce->getCondition()."{$orderby}".$announce->getLimitOffset()."";
	
	//查询数据
	$result = $announce->dbstuff->GetArray($sql);
	$return = $style = null;
	
	if (!empty($result)) {
		for ($i=0; $i<$i_count; $i++){
			//获取标题，html解析数据字段title值
			$result[$i]['title'] = strip_tags($result[$i]['title']);
			$lens = strlen($result[$i]['title']);
			if(isset($result[$i]['message'])) $result[$i]['content'] = strip_tags($result[$i]['message']);
			
			//设置标题长度
			if (isset($params['titlelen'])) {
	    		$result[$i]['title'] = mb_substr($result[$i]['title'], 0, $params['titlelen']);
	    		if($params['titlelen'] < $lens/3){
	    			$result[$i]['title'] .= '...';
	    		}
	    	}		
	    	//设置内容显示长度
	    	if (isset($params['infolen'])) {
	    		if(isset($result[$i]['content'])) {
	    		$result[$i]['content'] = mb_substr($result[$i]['content'],0, $params['infolen']);
	    		}
	    	}
	    	//公告文字链接设置 
			$url = $announce_controller->rewrite($result[$i]['id'], $result[$i]['title']);
			if(!empty($result[$i]['title'])) {
				//转换文字信息显示
				$return.= str_replace(array("[link:title]", "[field:title]", "[field:content]"), array($url, $result[$i]['title'], pb_strip_spaces($result[$i]['content'])), $content);
			}
		}
	}else{
		return;
	}
	return $return;
}
?>