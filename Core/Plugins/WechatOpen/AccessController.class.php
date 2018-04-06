<?php
namespace Plugins\WechatOpen;
use Think\Controller;
class AccessController extends Controller {

	private $appSecret;
	private $appID;
	private $dbPrefix;
	private $wx_appid;

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
     * [getAccessToken 获取accessToken]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getAccessToken() {
		$accesstokenData = M('component_accesstoken', $this->dbPrefix)->order('`autoid` DESC')->find();

		if ( empty( $accesstokenData ) || ($accesstokenData['get_time'] + $accesstokenData['expires_in']) < time() ) {
	    	$verify_ticket = $this->getVerifyTicket();
	    	$url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
	    	$data = array(
	    		'component_appsecret' => $this->appSecret,
				'component_appid' => $this->appID,
				'component_verify_ticket' => $verify_ticket,
	    	);
	        $returnStr = curl($url, json_encode($data));
	        $returnData = json_decode($returnStr, true);
	        if ( !empty( $returnData['component_access_token'] ) ) {
				$saveData = array(
					'access_token' => $returnData['component_access_token'], 
					'get_time' => time(),
					'expires_in' => $returnData['expires_in'],
				);
				M('component_accesstoken', $this->dbPrefix)->data($saveData)->add();
				$return = $returnData['component_access_token'];
	        } else {
	        	// dump($returnData);
	        	$return = false;
	        }
		} else {
			$return = $accesstokenData['access_token'];
		}
        return $return;
    }

    /**
     * [getVerifyTicket 获取VerifyTicket]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getVerifyTicket() {
    	$component_verify_ticket = M('component_verify_ticket', $this->dbPrefix)->order('`autoid` DESC')->find();
    	return $component_verify_ticket['verify_ticket'];
    }


	/**
	 * [getAuthorizerAccessToken 获取授权令牌]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getAuthorizerAccessToken() {
		$nowTime = time();
		$authorizerAccessTokenModel = M('component_authorizer_access_token', $this->dbPrefix);
		$whereData = array(
			'authorizer_appid' => $this->wx_appid,
		);
		$authorizerAccessTokenInfo = $authorizerAccessTokenModel->where($whereData)->order('`autoid` DESC')->find();
		if ( empty( $authorizerAccessTokenInfo ) || ($authorizerAccessTokenInfo['get_time'] + $authorizerAccessTokenInfo['expires_in']) < time() ) {
			$return = $this->refreshAuthorizerToken($authorizerAccessTokenInfo['authorizer_appid'], $authorizerAccessTokenInfo['authorizer_refresh_token']);
		} else {
			$return = $authorizerAccessTokenInfo['authorizer_access_token'];
		}
		return $return;
	}

	/**
	 * [getApiTicket 卡券api_ticket]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getApiTicket() {
		$ticketModel = M('api_ticket');
		$ticketInfo = $ticketModel->where($whereData)->order('`autoid` DESC')->find();
		if ( empty( $ticketInfo ) || ($ticketInfo['get_time'] + $ticketInfo['expires_in']) < time() ) {
	        $component_access_token = $this->getAuthorizerAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$component_access_token}&type=wx_card";
	        $code = curl($url, '', 'get');
	        $codeData = json_decode($code, true);
			if ( !empty( $codeData['ticket'] ) ) {
		       	$addData = array(
			        'ticket' => $codeData['ticket'],
			        'expires_in' => $codeData['expires_in'],
			        'get_time' => time(),
		       	);
		       	$ticketModel->data($addData)->add();
		    }
	       	$return = $codeData['ticket'];
		} else {
			$return = $ticketInfo['ticket'];
		}
		return $return;
	}

	
	/**
	 * [refreshAuthorizerToken 刷新AuthorizerToken]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $appId        [description]
	 * @param     [type]        $refreshToken [description]
	 * @return    [type]                      [description]
	 */
	public function refreshAuthorizerToken($appId, $refreshToken) {
		$component_access_token = $this->getAccessToken();
		$authorizerAccessTokenModel = M('component_authorizer_access_token', $this->dbPrefix);
		$nowTime = time();
		$url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token={$component_access_token}";
		$data = array(
			"component_appid" => $this->appID,
			"authorizer_appid" => $appId,
			"authorizer_refresh_token" => $refreshToken
		);
		$returnStr = curl($url, json_encode($data));
		$returnData = json_decode($returnStr,true);
		if ( !empty( $returnData['authorizer_access_token'] ) ) {
			$saveData = array(
				'authorizer_access_token' => $returnData['authorizer_access_token'],
				'expires_in' => $returnData['expires_in'],
				'authorizer_refresh_token' => $returnData['authorizer_refresh_token'],
				'get_time' => $nowTime,
			);
			$whereData = array(
				'authorizer_appid' => $appId,
			);
			$authorizerAccessTokenModel->where($whereData)->data($saveData)->save();
		}
		return $returnData['authorizer_access_token'];
	}
}