<?php
return array(
	//'配置项'=>'配置值'
	'ONLINE'  => true,
	'appID' => 'wx780fdbf8db00e880',
	'appSecret' => 'f7275480c4574bce4537f982f83128cf',
	'token' => '123',
	'encodingaesKey' => 'WqeHlkQyDB8hJ95BWygp0Yb6xYehAGxo2hmYqiltF9C',
	// 'WX_CARD_ID' => 'p9xx0vzaifYAJwDXyhXihQQ0M96Y', //优惠券id
	// 'WX_CARD_ID' => 'pkYCus-gXZys0YaiTM62XqD8vawc', //正式优惠券id
	// 'wx_appid' => 'wx96124f78c4ed9af5', //正式优惠券id

	'LOGIN_WHITE_LIST' => array(
		'controllerName' => array(
			// 白名单控制器
			'Test',
			'Public',
		),
		'controllerName-actionName' => array(
			// 白名单方法
			'Index-share',
		),
	),
);