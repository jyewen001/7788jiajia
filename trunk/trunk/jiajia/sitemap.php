<?php
 
/**
 * 
 * @function 帮助文件 网站地图 
 * @author叶稳
 * @date 2010-11-25 上午11:10:55 
 * tags
 * company:秦运恒
 */
define('CURSCRIPT', 'sitemap');
require("libraries/common.inc.php");
require("share.inc.php");
$viewhelper->setPosition(L("site_map", "tpl"));

render("sitemap");
?>