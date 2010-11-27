<?php
/**
 * PHPB2B :  Opensource B2B Script (http://www.phpb2b.com/)
 * Copyright (C) 2007-2010, Ualink. All Rights Reserved.
 * 
 * Licensed under The Languages Packages Licenses.
 * Support : phpb2b@hotmail.com
 * @function  初始化验证码图片
 * @version $Revision: 1212 $
 */
//导入生成图片类 
include 'libraries/captcha/securimage.php';
//导入公共初始化类
include 'libraries/common.inc.php';
//导入管理文件类
include 'libraries/file.class.php';

$img = new securimage();
$file = new Files();
 
if (isset($_GET['do'])) { 
	$do = trim($_GET['do']);
	if ($do == "play") {
		$img->audio_format = (isset($_GET['format']) && in_array(strtolower($_GET['format']), array('mp3', 'wav')) ? strtolower($_GET['format']) : 'wav');
		$img->setAudioPath(DATA_PATH.'audio/');
		$img->outputAudioFile();
	}
}else{ 
	//导入会话类
	include 'libraries/session_php.class.php';
	$session = new PbSessions();
	
	//初始化图片设置
	$img->image_width = 110;
	$img->image_height = 45;
	$img->use_wordlist = true;
	$img->wordlist_file = 'data/words/words.txt';
	$img->audio_path = 'data/audio/';
	$img->ttf_file = 'data/fonts/'.$file->fontFace;
	$img->gd_font_file = 'data/fonts/automatic.gdf';
	$img->perturbation = 0.65;
	$img->image_bg_color = new Securimage_Color("#009900");
	$img->text_color = new Securimage_Color("#000000");
	$img->num_lines = 0;
	
	if ($handle = @opendir('data/background/'))
	{
	    while ($bgfile = @readdir($handle))
	    {
	        if (preg_match('/\.jpg$/i', $bgfile))
	        {
	            $backgrounds[] = 'data/background/'.$bgfile;
	        }
	    }
	    //关闭由 dir_handle 指定的目录流
	    @closedir($handle);
	}
	srand ((float) microtime() * 10000000);
	
	$rand_keys = array_rand ($backgrounds);
	$background = $backgrounds[$rand_keys];
 
	$img->show($background);
}
?>
