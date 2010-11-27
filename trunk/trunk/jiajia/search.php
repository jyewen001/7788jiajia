<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * 
 * @version $Revision: 647 $
 */
define('CURSCRIPT', 'search');
require("libraries/common.inc.php");
require("share.inc.php");

uses("search","company","area","product");
include(CACHE_PATH."cache_area.php");
include(PHPB2B_ROOT.'libraries/page.class.php');
$page = new Pages();
$page->pagetpl_dir = $theme_name;
$conditions = array();

	$search = new Searchs();
	$area = new Areas();
	$company = new Companies();
	$product = new Products();

	$type = $_GET['type'];
	$key_words = trim($_GET['key_words']);
	
	if($type == "company_search"){
		$fields = "Company.id,cache_membergroupid,Company.name,Company.cache_spacename AS userid,Company.if_commend,Company.member_id,Company.created as pubdate,Company.main_prod,Company.description,Company.manage_type,Company.picture,Company.area_id1,Company.area_id2,Company.area_id3,Company.industry_id1,Company.industry_id2,Company.industry_id3";
		$joins = array();
		$joins[] = "LEFT JOIN {$tb_prefix}members m ON m.id=Company.member_id";
		$conditions[] = "Company.name like '%".$key_words."%'";
		$amount = $company->findCount($joins, $conditions, "Company.id");
		$page->setPagenav($amount);
		$result = $company->findAll($fields.",m.username,m.credits,m.points,m.membergroup_id,m.space_name",$joins,"Company.name like '%".$key_words."%'",null,$page->firstcount,$page->displaypg);
		$result = $company->formatResult($result);
		
		/*$hot_companies = $search->hot_company_search();
		$hot_companies = $company->formatResult($hot_companies);
		*/
		$fields = "Company.id,cache_membergroupid,Company.name,Company.cache_spacename AS userid,Company.if_commend,Company.member_id,Company.created as pubdate,Company.main_prod,Company.description,Company.manage_type,Company.picture,Company.area_id1,Company.area_id2,Company.area_id3,Company.industry_id1,Company.industry_id2,Company.industry_id3";
		$joins = array();
		$joins[] = "LEFT JOIN {$tb_prefix}members m ON m.id=Company.member_id";
		$hot_companies = $company->findAll($fields.",m.username,m.credits,m.points,m.membergroup_id,m.space_name",$joins,"Company.if_commend=1 and Company.status=1");
		$hot_companies = $company->formatResult($hot_companies);
		
		foreach ($result as $key=>$value){
			if(realpath($value['logo']) === false){
				$result[$key]['logosrc'] = '<img alt="'.$value['name'].'"  src="images/nopicture_small.gif" />';
			}
		}
		
		uaAssign(array(
			"ByPages"=>$page->getPagenav(),
			"Areas"=> $_PB_CACHE['area']
		));
		
		setvar("companies",$result);
		setvar("company_num",$amount);
		setvar("hot_companies",$hot_companies);
		render("index.store.search");
		exit;
	}else{
		$joins[] = 'LEFT JOIN '.$tb_prefix.'members m ON m.id=Product.member_id';
		$conditions[] = "Product.name like '%".$key_words."%'";
		$amount = $product->findCount($joins, $conditions,"Product.id");
		$page->setPagenav($amount);
		$result = $product->findAll("m.username,m.space_name AS userid,m.membergroup_id,m.credits,Product.cache_companyname AS companyname,Product.*", $joins,"Product.name like '%".$key_words."%'",null,$page->firstcount,$page->displaypg);
		$result = $product->formatResult($result);
		
		$joins_hot[] = 'LEFT JOIN '.$tb_prefix.'members m ON m.id=Product.member_id';
		$hot_products = $product->findAll("m.username,m.space_name AS userid,m.membergroup_id,m.credits,Product.cache_companyname AS companyname,Product.*", $joins,"Product.status=1 and Product.ifcommend=1");
		$hot_products = $product->formatResult($hot_products);
		
		setvar("products",$result);
		setvar("hot_products",$hot_products);
		setvar("product_num",$amount);
		uaAssign(array("ByPages"=>$page->pagenav,"Areas"=>$_PB_CACHE['area']));
		
		render("index.product.search");
		exit;
	}

?>