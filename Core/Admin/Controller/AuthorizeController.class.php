<?php
namespace Admin\Controller;
use Think\Controller;

class AuthorizeController extends Controller {

	public $wx_appid; //公众号id
	public $appID;
	public $appSecret;

	public function __construct($data=array()) {
		parent::__construct();
		if ( !empty( $data ) ) {
			$this->appSecret = empty($data['appSecret']) ? C('appSecret') : $data['appSecret'];
			$this->appID = empty($data['appID']) ? C('appID') : $data['appID'];
			$this->wx_appid = empty($data['wx_appid']) ? C('wx_appid') : $data['wx_appid'];
		} else {
	        $this->appSecret = C('appSecret');
			$this->appID = C('appID');
			$this->wx_appid = C('wx_appid');
		}
		$this->dbPrefix = C('DB_OPEN_PREFIX');
	}

	/**
	 * [authorization_code 收取授权码]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function authorization_code() {
		$auth_code = I('get.auth_code');
		$expires_in = I('get.expires_in');
		$addData = array(
			'auth_code' => $auth_code,
			'get_time' => time(),
			'expires_in' => $expires_in,
		);
		M('component_authorization_code', $this->dbPrefix)->data($addData)->add();

		$AuthorizeController = new \Plugins\WechatOpen\AuthorizeController([
			'appSecret' => $this->appSecret,
			'appID' => $this->appID,
			'wx_appid' => $this->wx_appid,
			'accessHelper' => new \Plugins\WechatOpen\AccessController,
		]);
		$AuthorizeController->createAuthorizerToken($auth_code);
		header('Location:'.U('Index/statistics'));
	}
}