<?php
class WordsConvert{
	
	var $convert_string;
	var $pinyin_table = array(
		-10254 => 'zuo',
		-10256 => 'zun',
		-10260 => 'zui',
		-10262 => 'zuan',
		-10270 => 'zu',
		-10274 => 'zou',
		-10281 => 'zong',
		-10296 => 'zi',
		-10307 => 'zhuo',
		-10309 => 'zhun',
		-10315 => 'zhui',
		-10322 => 'zhuang',
		-10328 => 'zhuan',
		-10329 => 'zhuai',
		-10331 => 'zhua',
		-10519 => 'zhu',
		-10533 => 'zhou',
		-10544 => 'zhong',
		-10587 => 'zhi',
		-10764 => 'zheng',
		-10780 => 'zhen',
		-10790 => 'zhe',
		-10800 => 'zhao',
		-10815 => 'zhang',
		-10832 => 'zhan',
		-10838 => 'zhai',
		-11014 => 'zha',
		-11018 => 'zeng',
		-11019 => 'zen',
		-11020 => 'zei',
		-11024 => 'ze',
		-11038 => 'zao',
		-11041 => 'zang',
		-11045 => 'zan',
		-11052 => 'zai',
		-11055 => 'za',
		-11067 => 'yun',
		-11077 => 'yue',
		-11097 => 'yuan',
		-11303 => 'yu',
		-11324 => 'you',
		-11339 => 'yong',
		-11340 => 'yo',
		-11358 => 'ying',
		-11536 => 'yin',
		-11589 => 'yi',
		-11604 => 'ye',
		-11781 => 'yao',
		-11798 => 'yang',
		-11831 => 'yan',
		-11847 => 'ya',
		-11861 => 'xun',
		-11867 => 'xue',
		-12039 => 'xuan',
		-12058 => 'xu',
		-12067 => 'xiu',
		-12074 => 'xiong',
		-12089 => 'xing',
		-12099 => 'xin',
		-12120 => 'xie',
		-12300 => 'xiao',
		-12320 => 'xiang',
		-12346 => 'xian',
		-12359 => 'xia',
		-12556 => 'xi',
		-12585 => 'wu',
		-12594 => 'wo',
		-12597 => 'weng',
		-12607 => 'wen',
		-12802 => 'wei',
		-12812 => 'wang',
		-12829 => 'wan',
		-12831 => 'wai',
		-12838 => 'wa',
		-12849 => 'tuo',
		-12852 => 'tun',
		-12858 => 'tui',
		-12860 => 'tuan',
		-12871 => 'tu',
		-12875 => 'tou',
		-12888 => 'tong',
		-13060 => 'ting',
		-13063 => 'tie',
		-13068 => 'tiao',
		-13076 => 'tian',
		-13091 => 'ti',
		-13095 => 'teng',
		-13096 => 'te',
		-13107 => 'tao',
		-13120 => 'tang',
		-13138 => 'tan',
		-13147 => 'tai',
		-13318 => 'ta',
		-13326 => 'suo',
		-13329 => 'sun',
		-13340 => 'sui',
		-13343 => 'suan',
		-13356 => 'su',
		-13359 => 'sou',
		-13367 => 'song',
		-13383 => 'si',
		-13387 => 'shuo',
		-13391 => 'shun',
		-13395 => 'shui',
		-13398 => 'shuang',
		-13400 => 'shuan',
		-13404 => 'shuai',
		-13406 => 'shua',
		-13601 => 'shu',
		-13611 => 'shou',
		-13658 => 'shi',
		-13831 => 'sheng',
		-13847 => 'shen',
		-13859 => 'she',
		-13870 => 'shao',
		-13878 => 'shang',
		-13894 => 'shan',
		-13896 => 'shai',
		-13905 => 'sha',
		-13906 => 'seng',
		-13907 => 'sen',
		-13910 => 'se',
		-13914 => 'sao',
		-13917 => 'sang',
		-14083 => 'san',
		-14087 => 'sai',
		-14090 => 'sa',
		-14092 => 'ruo',
		-14094 => 'run',
		-14097 => 'rui',
		-14099 => 'ruan',
		-14109 => 'ru',
		-14112 => 'rou',
		-14122 => 'rong',
		-14123 => 'ri',
		-14125 => 'reng',
		-14135 => 'ren',
		-14137 => 're',
		-14140 => 'rao',
		-14145 => 'rang',
		-14149 => 'ran',
		-14151 => 'qun',
		-14159 => 'que',
		-14170 => 'quan',
		-14345 => 'qu',
		-14353 => 'qiu',
		-14355 => 'qiong',
		-14368 => 'qing',
		-14379 => 'qin',
		-14384 => 'qie',
		-14399 => 'qiao',
		-14407 => 'qiang',
		-14429 => 'qian',
		-14594 => 'qia',
		-14630 => 'qi',
		-14645 => 'pu',
		-14654 => 'po',
		-14663 => 'ping',
		-14668 => 'pin',
		-14670 => 'pie',
		-14674 => 'piao',
		-14678 => 'pian',
		-14857 => 'pi',
		-14871 => 'peng',
		-14873 => 'pen',
		-14882 => 'pei',
		-14889 => 'pao',
		-14894 => 'pang',
		-14902 => 'pan',
		-14908 => 'pai',
		-14914 => 'pa',
		-14921 => 'ou',
		-14922 => 'o',
		-14926 => 'nuo',
		-14928 => 'nue',
		-14929 => 'nuan',
		-14930 => 'nv',
		-14933 => 'nu',
		-14937 => 'nong',
		-14941 => 'niu',
		-15109 => 'ning',
		-15110 => 'nin',
		-15117 => 'nie',
		-15119 => 'niao',
		-15121 => 'niang',
		-15128 => 'nian',
		-15139 => 'ni',
		-15140 => 'neng',
		-15141 => 'nen',
		-15143 => 'nei',
		-15144 => 'ne',
		-15149 => 'nao',
		-15150 => 'nang',
		-15153 => 'nan',
		-15158 => 'nai',
		-15165 => 'na',
		-15180 => 'mu',
		-15183 => 'mou',
		-15362 => 'mo',
		-15363 => 'miu',
		-15369 => 'ming',
		-15375 => 'min',
		-15377 => 'mie',
		-15385 => 'miao',
		-15394 => 'mian',
		-15408 => 'mi',
		-15416 => 'meng',
		-15419 => 'men',
		-15435 => 'mei',
		-15436 => 'me',
		-15448 => 'mao',
		-15454 => 'mang',
		-15625 => 'man',
		-15631 => 'mai',
		-15640 => 'ma',
		-15652 => 'luo',
		-15659 => 'lun',
		-15661 => 'lue',
		-15667 => 'luan',
		-15681 => 'lv',
		-15701 => 'lu',
		-15707 => 'lou',
		-15878 => 'long',
		-15889 => 'liu',
		-15903 => 'ling',
		-15915 => 'lin',
		-15920 => 'lie',
		-15933 => 'liao',
		-15944 => 'liang',
		-15958 => 'lian',
		-15959 => 'lia',
		-16155 => 'li',
		-16158 => 'leng',
		-16169 => 'lei',
		-16171 => 'le',
		-16180 => 'lao',
		-16187 => 'lang',
		-16202 => 'lan',
		-16205 => 'lai',
		-16212 => 'la',
		-16216 => 'kuo',
		-16220 => 'kun',
		-16393 => 'kui',
		-16401 => 'kuang',
		-16403 => 'kuan',
		-16407 => 'kuai',
		-16412 => 'kua',
		-16419 => 'ku',
		-16423 => 'kou',
		-16427 => 'kong',
		-16429 => 'keng',
		-16433 => 'ken',
		-16448 => 'ke',
		-16452 => 'kao',
		-16459 => 'kang',
		-16465 => 'kan',
		-16470 => 'kai',
		-16474 => 'ka',
		-16647 => 'jun',
		-16657 => 'jue',
		-16664 => 'juan',
		-16689 => 'ju',
		-16706 => 'jiu',
		-16708 => 'jiong',
		-16733 => 'jing',
		-16915 => 'jin',
		-16942 => 'jie',
		-16970 => 'jiao',
		-16983 => 'jiang',
		-17185 => 'jian',
		-17202 => 'jia',
		-17417 => 'ji',
		-17427 => 'huo',
		-17433 => 'hun',
		-17454 => 'hui',
		-17468 => 'huang',
		-17482 => 'huan',
		-17487 => 'huai',
		-17496 => 'hua',
		-17676 => 'hu',
		-17683 => 'hou',
		-17692 => 'hong',
		-17697 => 'heng',
		-17701 => 'hen',
		-17703 => 'hei',
		-17721 => 'he',
		-17730 => 'hao',
		-17733 => 'hang',
		-17752 => 'han',
		-17759 => 'hai',
		-17922 => 'ha',
		-17928 => 'guo',
		-17931 => 'gun',
		-17947 => 'gui',
		-17950 => 'guang',
		-17961 => 'guan',
		-17964 => 'guai',
		-17970 => 'gua',
		-17988 => 'gu',
		-17997 => 'gou',
		-18012 => 'gong',
		-18181 => 'geng',
		-18183 => 'gen',
		-18184 => 'gei',
		-18201 => 'ge',
		-18211 => 'gao',
		-18220 => 'gang',
		-18231 => 'gan',
		-18237 => 'gai',
		-18239 => 'ga',
		-18446 => 'fu',
		-18447 => 'fou',
		-18448 => 'fo',
		-18463 => 'feng',
		-18478 => 'fen',
		-18490 => 'fei',
		-18501 => 'fang',
		-18518 => 'fan',
		-18526 => 'fa',
		-18696 => 'er',
		-18697 => 'en',
		-18710 => 'e',
		-18722 => 'duo',
		-18731 => 'dun',
		-18735 => 'dui',
		-18741 => 'duan',
		-18756 => 'du',
		-18763 => 'dou',
		-18773 => 'dong',
		-18774 => 'diu',
		-18783 => 'ding',
		-18952 => 'die',
		-18961 => 'diao',
		-18977 => 'dian',
		-18996 => 'di',
		-19003 => 'deng',
		-19006 => 'de',
		-19018 => 'dao',
		-19023 => 'dang',
		-19038 => 'dan',
		-19212 => 'dai',
		-19218 => 'da',
		-19224 => 'cuo',
		-19227 => 'cun',
		-19235 => 'cui',
		-19238 => 'cuan',
		-19242 => 'cu',
		-19243 => 'cou',
		-19249 => 'cong',
		-19261 => 'ci',
		-19263 => 'chuo',
		-19270 => 'chun',
		-19275 => 'chui',
		-19281 => 'chuang',
		-19288 => 'chuan',
		-19289 => 'chuai',
		-19467 => 'chu',
		-19479 => 'chou',
		-19484 => 'chong',
		-19500 => 'chi',
		-19515 => 'cheng',
		-19525 => 'chen',
		-19531 => 'che',
		-19540 => 'chao',
		-19715 => 'chang',
		-19725 => 'chan',
		-19728 => 'chai',
		-19739 => 'cha',
		-19741 => 'ceng',
		-19746 => 'ce',
		-19751 => 'cao',
		-19756 => 'cang',
		-19763 => 'can',
		-19774 => 'cai',
		-19775 => 'ca',
		-19784 => 'bu',
		-19805 => 'bo',
		-19976 => 'bing',
		-19982 => 'bin',
		-19986 => 'bie',
		-19990 => 'biao',
		-20002 => 'bian',
		-20026 => 'bi',
		-20032 => 'beng',
		-20036 => 'ben',
		-20051 => 'bei',
		-20230 => 'bao',
		-20242 => 'bang',
		-20257 => 'ban',
		-20265 => 'bai',
		-20283 => 'ba',
		-20292 => 'ao',
		-20295 => 'ang',
		-20304 => 'an',
		-20317 => 'ai',
		-20319 => 'a');

	function WordsConvert($str = ''){
		if (!empty($str)) {
			$this->setConvertString($str);
		}
	}

	function search($charcode){
		if($charcode > 0 && $charcode < 160){
			return chr($charcode);
		}
		if($charcode < -20319 || $charcode > -10247){
			return '';
		}
		foreach($this->pinyin_table as $code => $pinyin){
			if($code <= $charcode){
				return $pinyin;
			}
		}
		return '';
	}

	function isChinese($str){
		return preg_match('/([\x80-\xFE][\x40-\x7E\x80-\xFE])+/', $str); 
	}
	
	function setConvertString($str)
	{
		$str = iconv("utf-8", "gbk", $str);
		$this->convert_string = $str;
	}
	
	function getConvertString()
	{
		return $this->convert_string;
	}

	function convert($sep = ' '){
		$result = '';
		$str = $this->getConvertString();
		$l = strlen($str);
		$is_ascii = false;
		for($i = 0;$i < $l;$i++){
			$p = ord($str[$i]);
			if($p > 160){
				$q = ord($str[++$i]);
				$p <<= 8;
				$p += $q - 65536;
				if($is_ascii){
					$result .= $sep;
				}
				$result .= $this->search($p);
				$result .= $sep;
				$is_ascii = false;
			}else{
				$result .= $str[$i];
				$is_ascii = true;
			}
		}
		return rtrim($result);
	}
}
?>