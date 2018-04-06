<?php
return array(
	'MODULE_ALLOW_LIST'  => array('Admin', 'Home', 'TPH', 'Authorize','Bbs'),
	// 'DEFAULT_MODULE'     => 'Authorize',  // 默认模块
	
	'DB_TYPE'  			 => 'mysql',
	'DB_PARAMS'			 => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
	// 内网测试
	'DB_HOST' 		     => '192.168.1.161', 
	'DB_NAME'  			 => 'la',
	'DB_USER'  			 => 'root',
	'DB_PWD'   			 => 'xcrozz###',
	
	// 外网正式
	// 'DB_HOST' 		     => '121.40.148.111', 
	// 'DB_NAME'  			 => 'poetry',
	// 'DB_USER'  			 => 'poetry',
	// 'DB_PWD'   			 => 'poetry###',
	
	// 本机测试
	// 'DB_HOST' 		     => 'localhost', 
	// 'DB_NAME'  			 => 'wxopen',
	// 'DB_USER'  			 => 'root',
	// 'DB_PWD'   			 => '123456',
	
	//农业银行服务器
	// 'DB_HOST'                    => '39.108.161.65',
 //    'DB_NAME'                    => 'wxopen',
 //    'DB_USER'                    => 'wxopen',
 //    'DB_PWD'                     => 'wxopen###',
	
	// 本机测试
	// 'DB_HOST' 		     => '192.168.146.1', 
	// 'DB_NAME'  			 => 'abchina',
	// 'DB_USER'  			 => 'root',
	// 'DB_PWD'   			 => 'root',
	
	'DB_PORT'  			 => 3306,
	'DB_PREFIX' 		 => 'la_',
	'DB_OPEN_PREFIX'  	 => 'la_',
	'DB_CHARSET'		 => 'utf8',


    'URL_MODEL'			 => 2,
    'TMPL_PARSE_STRING'  => array(
        '__PUBLIC__' 	 => '/Static/Public',
    ),
    'TAGLIB_BUILD_IN'    => 'Cx,Home\Controller\TagController',

    'URL_404_REDIRECT'   =>  '/Static/Public/Shop/404.html',
    'UPLOAD_PATH'		 => './Static/Uploads/',
    'TEMPLET_PATH'		     => './Core/Home/View/Web',

	'SESSION_AUTO_START' => TRUE,

	// 'appID' => 'wx780fdbf8db00e880',
	// 'appSecret' => 'f7275480c4574bce4537f982f83128cf',
	// 'token' => 'pcwDK53a',
	// 'encodingaesKey' => 'WqeHlkQyDB8hJ95BWygp0Yb6xYehAGxo2hmYqiltF9C',
	'webSite' => 'http://open.65.xcrozz.com/'
);