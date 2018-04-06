<?php
return array(
	'AUTH_CONFIG'=>array(
		'AUTH_ON' 			=> true, 						//认证开关
		'AUTH_TYPE' 		=> 1, 							// 认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' 		=> 'la_admin_auth_group', 		//用户组数据表名
		'AUTH_GROUP_ACCESS' => 'la_admin_auth_group_access', //用户组明细表
		'AUTH_RULE' 		=> 'la_admin_auth_rule', 		//权限规则表
		'AUTH_USER' 		=> 'la_admin'				//用户信息表
    ),

	'DEFAULT_CONTROLLER'	=> 'Login',
	'DEFAULT_ACTION'		=> 'login',
	'DB_PREFIX' 		 	=> 'la_',

	'appID' => 'wx780fdbf8db00e880',
	'appSecret' => 'f7275480c4574bce4537f982f83128cf',
	'token' => '123',
	'encodingaesKey' => 'WqeHlkQyDB8hJ95BWygp0Yb6xYehAGxo2hmYqiltF9C',
);