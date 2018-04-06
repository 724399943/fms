<?php
namespace Plugins\WechatOpen;
use Think\Controller;

class AuthorizeController extends Controller {

	public $appID;
	public $appSecret;
	private $accessHelper;

	public function __construct($data=array()) {
		parent::__construct();
		if ( !empty( $data ) ) {
			$this->appSecret = empty($data['appSecret']) ? C('appSecret') : $data['appSecret'];
			$this->appID = empty($data['appID']) ? C('appID') : $data['appID'];
		} else {
	        $this->appSecret = C('appSecret');
			$this->appID = C('appID');
		}
		$this->dbPrefix = C('DB_OPEN_PREFIX');
		$this->accessHelper = $data['accessHelper'];
	}


	/**
	 * [pre_auth_code 获取pre_auth_code]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function pre_auth_code(){
		// $accesstokenData = M('component_auth_code', $this->dbPrefix)->order('`autoid` DESC')->find();

		// if ( empty( $accesstokenData ) || ($accesstokenData['get_time'] + $accesstokenData['expires_in']) < time() ) {
	    	$accessToken = $this->accessHelper->getAccessToken();
	    	$url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token={$accessToken}";
	    	$data = array(
	    		'component_appid' => $this->appID,
	    	);
	        $returnStr = curl($url, json_encode($data));
	        $returnData = json_decode($returnStr, true);
			if ( !empty( $returnData['pre_auth_code'] ) ) {
		        $addData = array(
			        'pre_auth_code' => $returnData['pre_auth_code'],
			        'expires_in' => $returnData['expires_in'],
			        'get_time' => time(),
				);
		        M('component_auth_code', $this->dbPrefix)->data($addData)->add();
		    }
	        $return = $returnData['pre_auth_code'];
		// } else {
		// 	$return = $accesstokenData['pre_auth_code'];
		// }
        return $return;
    }

    /**
     * [authorize 授权页]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
	public function authorize() {
		$webSite = C('webSite');
		$redirect_uri = trim($webSite, '/') . U('Authorize/authorization_code');
		$pre_auth_code = $this->pre_auth_code();		
		$url = "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->appID}&pre_auth_code={$pre_auth_code}&redirect_uri={$redirect_uri}";
		// redirect($url);
		echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';
		echo "<a href='{$url}'' title='授权'>进入授权</a>";
	}

	/**
	 * [getAuthCode 查找授权码]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getAuthCode() {
		$authCodeInfo = M('component_authorization_code', $this->dbPrefix)->order('`autoid` DESC')->find();
		return $authCodeInfo['auth_code'];
	}

	/**
	 * [createAuthorizerToken 授权完成获取AuthorizerToken]
	 * @author NicFung <13502462404@qq.com>
	 * @modify kofu <[418382595@qq.com]>
	 * @copyright Copyright (c)                 2017          Xcrozz (http://www.xcrozz.com)
	 * @param     string        $authorization_code [description]
	 * @return    [type]                            [description]
	 */
	public function createAuthorizerToken($authorization_code="") {
		$nowTime = time();
		$authorizerAccessTokenModel = M('component_authorizer_access_token', $this->dbPrefix);
		$component_access_token = $this->accessHelper->getAccessToken();
		$authorization_code = empty($authorization_code) ? $this->getAuthCode() : $authorization_code;
		$url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$component_access_token}";
		$data = array(
			'component_appid' => $this->appID,
			'authorization_code' => $authorization_code,
		);
		$returnStr = curl($url, json_encode($data));
		$returnData = json_decode($returnStr, true);
		if ( !empty ( $returnData['authorization_info'] ) ) {
			$whereData = array(
				'authorizer_appid' => $returnData['authorization_info']['authorizer_appid'],
			);
			$authorizerAccessTokenInfo = $authorizerAccessTokenModel->where($whereData)->find();
			$addData = array(
				'authorizer_appid' => $returnData['authorization_info']['authorizer_appid'],
				'authorizer_access_token' => $returnData['authorization_info']['authorizer_access_token'],
				'authorizer_refresh_token' => $returnData['authorization_info']['authorizer_refresh_token'],
				'get_time' => $nowTime,
				'expires_in' => $returnData['authorization_info']['expires_in'],
				'json' => $returnStr
			);
			// added
			$authorizerInfo = $this->getAuthorizerInfo($returnData['authorization_info']['authorizer_appid']);
			if ( !empty($authorizerInfo) ) {
				$addData['user_name'] = $authorizerInfo['authorizer_info']['user_name'];
			}
			if ( !empty( $authorizerAccessTokenInfo ) ) {
				$authorizerAccessTokenModel->data($addData)->where($whereData)->save();
			} else {
				$authorizerAccessTokenModel->data($addData)->add();
			}
			$return = $returnData['authorization_info']['authorizer_access_token'];
		} else {
			dump($data);
			dump($returnData);
		}
	}

    /**
     * [getAuthorizerInfo 获取授权方的帐号基本信息]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getAuthorizerInfo($authorizer_appid) {
    	$accessToken = $this->accessHelper->getAccessToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$accessToken}";
    	$data = array(
    		'component_appid' => $this->appID,
    		'authorizer_appid' => $authorizer_appid,
    	);
        $returnStr = curl($url, json_encode($data));
        $returnData = json_decode($returnStr, true);
        file_put_contents('getAuthorizerInfo.txt', $returnStr);
        return $returnData;
    }
}