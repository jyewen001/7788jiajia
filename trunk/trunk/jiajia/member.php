<?php
 
define('CURSCRIPT', 'index');
require("libraries/common.inc.php");
require("share.inc.php");
  
$setting = new Settings();
setvar("SiteDescription", $setting->getValue("site_description"));
render("member");

?>