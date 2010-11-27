<?php 
/**
 * author:叶稳
 * function Smarty自定义标签，广告单独数据显示功能模板。
 * @param $params  自定义标签参数，例 type、titlelen、infolen、row
 * @param $content 内容
 * @param $smarty 模板对象
 * date 2010-10-31 15:34
 */
function smarty_block_ads($params, $content, &$smarty){
	if ($content === null) return;
	$conditions = array();
	extract($params);
	if (!class_exists("Adses")) {
		uses("ad");
		$ad = new Adses();
	}else{
		$ad = new Adses();
	}
	 
	$conditions[] = "status='1' AND state='1'";
	if(isset($params['id'])){
		$result = $ad->read("*", intval($params['id']));
	}else{
		if (isset($params['type'])) {
			echo $ad->getFocus($params);
			return;
		}
		//广告类型验证
		if (isset($params['typeid'])) {
			//类型值取值
			$typeid = intval($params['typeid']);
			
			$conditions[] = "adzone_id=".$typeid;
			$zone_res = $ad->dbstuff->GetRow("select * from {$ad->table_prefix}adzones where id=".$typeid);
			
			if (isset($params['groupid'])) {

				if (!empty($zone_res['membergroup_ids'])) {
					$membergroup_ids = explode(",", $zone_res['membergroup_ids']);
					
					if (!in_array($params['groupid'], $membergroup_ids)) {
						return;
					}
				}
			}
			if ($zone_res['what']==2) {
				
				echo stripslashes($zone_res['additional_adwords']);
				return;
			}
			if (isset($zone_res['style']) && $zone_res['style'] == 1) {
				
				//ads roll
			    $ad->getAds($zone_res,$typeid);
				return;
			} 
			
			
			$adzone_name = $zone_res['name'];
			$adzone_id = $zone_res['id'];
			$max_width = intval($zone_res['width']);
			$max_height = intval($zone_res['height']);
			$max_ad = intval($zone_res['max_ad']);
			
			unset($zone_res);
		}
		if (isset($params['keyword'])) {
			$conditions[] = "title like '%".$params['keyword']."%'";
		}
		$row = $col = 0;
		$orderby = null;
		if (isset($params['row'])) {
			$row = $params['row'];
		}elseif ($max_ad){
			$row = $max_ad;
		}
		if (isset($params['col'])) {
			$col = $params['col'];
		}
		$ad->setLimitOffset($row, $col);
		if (isset($params['orderby'])) {
			$orderby = " ORDER BY ".trim($params['orderby']);
		}else{
			$orderby = " ORDER BY priority ASC";
		}
		$ad->setCondition($conditions);
		$sql = "SELECT * FROM {$ad->table_prefix}adses ".$ad->getCondition()."{$orderby}".$ad->getLimitOffset()."";
		$result = $ad->dbstuff->GetArray($sql);
	}
	$return = null;
	if(!empty($result)){
		$count = count($result);
		for($i=0; $i<$count; $i++) {
			$url = $result[$i]['target_url'];
			if (!empty($result[$i]['end_date']) && $result[$i]['end_date']<$ad->timestamp) {
				if (!empty($result[$i]['picture_replace'])) {
					$result[$i]['source_url'] = $result[$i]['picture_replace'];
					$result[$i]['title'] = L("ads_on_sale");
					$return .= str_replace(array("[link:url]", "[field:src]", "[field:title]"), array($url, $ad->getCode($result[$i], $max_width, $max_height), $result[$i]['title']), $content);
				}
			}else{
				$return .= str_replace(array("[link:url]", "[field:src]", "[field:title]"), array($url, $ad->getCode($result[$i], $max_width, $max_height), $result[$i]['title']), $content);
			}
		}
	}else{
		$return = $adzone_name.'#ID-'.$adzone_id;
	}
	return $return;
}
?>