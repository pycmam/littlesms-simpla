<?php
function is_mobile_browser(){

	$user_agent = $_SERVER['HTTP_USER_AGENT']; 
	$http_accept = $_SERVER['HTTP_ACCEPT'];
	
	if(stristr($user_agent, 'windows') && !stristr($user_agent, 'windows ce'))
		return false;
	
	if(eregi('windows ce|iemobile|mobile|symbian|mini|wap|pda|psp|up.browser|up.link|mmp|midp|phone|pocket', $user_agent))
		return true;

	if(stristr($http_accept, 'text/vnd.wap.wml') || stristr($http_accept, 'application/vnd.wap.xhtml+xml'))
		return true;
		
	if(!empty($_SERVER['HTTP_X_WAP_PROFILE']) || !empty($_SERVER['HTTP_PROFILE']) || !empty($_SERVER['X-OperaMini-Features']) || !empty($_SERVER['UA-pixels']))
		return true;

	$agents = array(
	'acs-'=>'acs-',
	'alav'=>'alav',
	'alca'=>'alca',
	'amoi'=>'amoi',
	'audi'=>'audi',
	'aste'=>'aste',
	'avan'=>'avan',
	'benq'=>'benq',
	'bird'=>'bird',
	'blac'=>'blac',
	'blaz'=>'blaz',
	'brew'=>'brew',
	'cell'=>'cell',
	'cldc'=>'cldc',
	'cmd-'=>'cmd-',
	'dang'=>'dang',
	'doco'=>'doco',
	'eric'=>'eric',
	'hipt'=>'hipt',
	'inno'=>'inno',
	'ipaq'=>'ipaq',
	'java'=>'java',
	'jigs'=>'jigs',
	'kddi'=>'kddi',
	'keji'=>'keji',
	'leno'=>'leno',
	'lg-c'=>'lg-c',
	'lg-d'=>'lg-d',
	'lg-g'=>'lg-g',
	'lge-'=>'lge-',
	'maui'=>'maui',
	'maxo'=>'maxo',
	'midp'=>'midp',
	'mits'=>'mits',
	'mmef'=>'mmef',
	'mobi'=>'mobi',
	'mot-'=>'mot-',
	'moto'=>'moto',
	'mwbp'=>'mwbp',
	'nec-'=>'nec-',
	'newt'=>'newt',
	'noki'=>'noki',
	'opwv'=>'opwv',
	'palm'=>'palm',
	'pana'=>'pana',
	'pant'=>'pant',
	'pdxg'=>'pdxg',
	'phil'=>'phil',
	'play'=>'play',
	'pluc'=>'pluc',
	'port'=>'port',
	'prox'=>'prox',
	'qtek'=>'qtek',
	'qwap'=>'qwap',
	'sage'=>'sage',
	'sams'=>'sams',
	'sany'=>'sany',
	'sch-'=>'sch-',
	'sec-'=>'sec-',
	'send'=>'send',
	'seri'=>'seri',
	'sgh-'=>'sgh-',
	'shar'=>'shar',
	'sie-'=>'sie-',
	'siem'=>'siem',
	'smal'=>'smal',
	'smar'=>'smar',
	'sony'=>'sony',
	'sph-'=>'sph-',
	'symb'=>'symb',
	't-mo'=>'t-mo',
	'teli'=>'teli',
	'tim-'=>'tim-',
	'tosh'=>'tosh',
	'treo'=>'treo',
	'tsm-'=>'tsm-',
	'upg1'=>'upg1',
	'upsi'=>'upsi',
	'vk-v'=>'vk-v',
	'voda'=>'voda',
	'wap-'=>'wap-',
	'wapa'=>'wapa',
	'wapi'=>'wapi',
	'wapp'=>'wapp',
	'wapr'=>'wapr',
	'webc'=>'webc',
	'winw'=>'winw',
	'winw'=>'winw',
	'xda-'=>'xda-'
	);
	
	if(!empty($agents[substr($_SERVER['HTTP_USER_AGENT'], 0, 4)]))
    	return true;
}
?>