<?php 
/**
 * @function 首页数据显示 
 * @author叶稳
 * @date 2010-11-12 上午11:47:38 
 * tags
 * company:秦运恒
 */
define('CURSCRIPT', 'index');

//初始化
require("libraries/common.inc.php");
require("share.inc.php");
require(CACHE_PATH. "cache_industry.php");
require(CACHE_PATH. "cache_setting1.php");
require(PHPB2B_ROOT. 'data'.DS. 'cache'.DS. 'zh-cn'.DS. 'cache_type.php');

if (!empty($_PB_CACHE['setting']['redirect_url'])) {
	if(isset($_SERVER['REQUEST_URI']) && !strstr($_SERVER['REQUEST_URI'], ".php")){
		$url = $_PB_CACHE['setting']['redirect_url'];
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:$url");
	}
}
	//显示助手开启
	$viewhelper->Start();
	
	//导类库
	uses("industry","trade","product","company","news","expo","typeoption","job");
	$typeoption = new Typeoption();
	$job = new Jobs();
	$salary = $typeoption->get_cache_type("salary");
	$sql = "SELECT j.id,j.name,j.peoples,j.salary_id,j.name as fullname,c.name AS companyname,c.cache_spacename AS userid FROM {$job->table_prefix}jobs j LEFT JOIN {$job->table_prefix}companies c ON c.id=j.company_id ".$job->getCondition()."{$orderby}".$job->getLimitOffset();
	$result = $job->dbstuff->GetArray($sql);
	foreach($result as $key=>$value){
		$sa = $value['salary_id'];
		$result[$key]['salary'] = $salary[$sa];
		
		if(empty($value['name'])){
			$result[$key]['name'] ="不选择";
		}
		if(empty($value['peoples'])){
			$result[$key]['peoples'] ="不限";
		}
	}

	setvar("new_job",$result);
	unset($resutl);
	
	//设置行业类别 
	$industry = new Industries();
	setvar("IndustryList", $industry->getCacheIndustry());
	$viewhelper->setMetaDescription($_PB_CACHE['setting1']['site_description']);
    
  	//贸易信息
	$trade = new Trades();
	foreach($_PB_CACHE['offertype'] as $key=>$value){  
		$arr_name = null;
	 
	 	$arr_name = trade_type($key)."_arr";
	 	   
		//将值存入smarty框架中
		$trade_list = $trade->getNewTradeDate($key);
		foreach ($trade_list as $key=>$value){
			if(realpath('attachment/'.$value['picture']) === false){
				$trade_list[$key]['picture'] = '../images/nopicture_small.gif';
			}
		}
	 	$smarty->assign($arr_name,$trade_list);
	}
	
 	//实例商品对象 
	$product = new Products();
	//获取商品推荐信息 
	$product_result = $product->hot_recommend(); 
	
	$p_count = count($product_result);
	if($p_count < 4){
		for($i=$p_count; $i<4;$i++){
			$product_result[$i]['name'] ="暂无数据" ;
			$product_result[$i]['picture'] ="images/nopicture_big.gif" ;
			$product_result[$i]['price'] ="" ;
		}
	}
	$smarty->assign("hot_recommend", $product_result); 
	unset($product_result); 
	
	//实例店铺对象
	$company = new Companies();
	//获取店铺信息
	$company_result = $company->getAllCompany();
	foreach ($company_result as $key=>$value){
		if(realpath('attachment/'.$value['picture']) === false){
			$company_result[$key]['picture'] = '../images/nopicture_small.gif';
		}
	}
	//获取新店铺
	$new_company = $company->getAllCompany(6,true);
	foreach ($new_company as $key=>$value){
		if(realpath('attachment/'.$value['picture']) === false){
			$new_company[$key]['picture'] = '../images/nopicture_small.gif';
		}
	}
	$smarty->assign("company_list", array_reverse($company_result)); 
	
	$smarty->assign("new_company",$new_company);
	unset($new_company);
	unset($company_result);
	
	//实例新闻对象 
	$news = new Newses();
	
	//获取新闻结果集（行业动态）
	$new_result = $news->getNews();
	$smarty->assign("industry_news",$new_result);
	unset($new_result);
	
	//获取展会对象
	$fair= new Expoes();
	$fail_list = $fair->getExist(8,true);
	
	foreach ($fail_list as $key=>$value){
		if(realpath('attachment/'.$value['picture']) === false){
			$fail_list[$key]['picture'] = '../images/nopicture_small.gif';
		}
	}
	$smarty->assign("fails",$fail_list);
	unset($fail_list);
	
	formhash(); 

	//进入首页页面 
	render("index");    
?>