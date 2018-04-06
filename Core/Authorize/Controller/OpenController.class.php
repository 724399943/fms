<?php
namespace Authorize\Controller;
use Think\Controller;
use Common\Library\EncodingAES\WXBizMsgCrypt;

class OpenController extends Controller {

	public $wx_appid; //公众号id
	public $appID;
	public $appSecret;
	public $token;
	public $encodingaesKey;

	public function _empty() {
		// echo 'hello world';
		$postStr = @$GLOBALS["HTTP_RAW_POST_DATA"];
		// file_put_contents('post', json_encode($_POST));
		// file_put_contents('get', json_encode($_GET));
		// file_put_contents('postStr', $postStr);
	}

	public function __construct() {
		parent::__construct();
        $c = M('config')->getField('config_sign, config_value');
        C($c);
        $this->appSecret = C('appSecret');
		$this->appID = C('appID');
		$this->token = C('token');
		$this->encodingaesKey = C('encodingaesKey');
		$this->wx_appid = C('wx_appid');
	}

	/**
	 * [checkEncoding 检测是否加密]
	 * @return [type] [description]
	 */
	private function checkEncoding() {
		$encrypt_type = I("get.encrypt_type");
		$msg_signature = I("get.msg_signature");
		if ($encrypt_type == "aes") {
			$return = $msg_signature;
		} elseif($encrypt_type == "raw") {
			$return = FALSE;
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * [jurisdiction 被动接收verify_ticket]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function jurisdiction() {
		$postStr = @$GLOBALS["HTTP_RAW_POST_DATA"];
		// file_put_contents('ge', json_encode($_GET));
		// file_put_contents('po', $postStr);

		$isEncoding = $this->checkEncoding();
		$postObj = $this->requestMsg($isEncoding);
		echo "success";
		if ( !empty( $postObj['ComponentVerifyTicket'] ) ) {
			$saveData = array(
				'verify_ticket' => $postObj['ComponentVerifyTicket'], 
				'get_time' => $postObj['CreateTime'],
				'expires_in' => 600,
			);
			M('component_verify_ticket')->data($saveData)->add();
		}
	}

	/**
	 * [requestMsg 获取收到信息]
	 * @return [object] [消息对象]
	 */
	public function requestMsg($isEncoding=1) {
		$postStr = @$GLOBALS["HTTP_RAW_POST_DATA"];
		if($isEncoding) {
			// $this->load->model("consoles/Appinfomodel");
			// $appInfo = $this->Appinfomodel->selectEncodingaeskey(USERID, APPINFOID);
			// foreach ($appInfo as $key => $value) {
				$data = array(
					"appId" => $this->appID,
					"token" => $this->token,
					"encodingAesKey" => $this->encodingaesKey,
				);
				// $this->load->library("EncodingAES/wxbizmsgcrypt", $data, "wxbizmsgcrypt");
				$wxbizmsgcryptClass = new WXBizMsgCrypt($data);
				$msgSignature = I("get.msg_signature");
				$timeStamp = I("get.timestamp");
				$nonce = I("get.nonce");
				$errCode = $wxbizmsgcryptClass->decryptMsg($msgSignature, $timeStamp, $nonce, $postStr, $msg);
				if ($errCode == 0) {
					// print("解密后: " . $msg . "\n");
					$postStr = $msg;
				}
			// }
		}
		$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
		if( !empty($postObj) ) {
			$return = (array)$postObj;
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * [pre_auth_code 获取pre_auth_code]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function pre_auth_code(){
		// $accesstokenData = M('component_auth_code')->order('`autoid` DESC')->find();

		// if ( empty( $accesstokenData ) || ($accesstokenData['get_time'] + $accesstokenData['expires_in']) < time() ) {
	    	$accessToken = $this->getAccessToken();
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
		        M('component_auth_code')->data($addData)->add();
		    }
	        $return = $returnData['pre_auth_code'];
		// } else {
		// 	$return = $accesstokenData['pre_auth_code'];
		// }
        return $return;
    }

    /**
     * [getAccessToken 获取accessToken]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getAccessToken() {
		$accesstokenData = M('component_accesstoken')->order('`autoid` DESC')->find();

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
				M('component_accesstoken')->data($saveData)->add();
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
    	$component_verify_ticket = M('component_verify_ticket')->order('`autoid` DESC')->find();
    	return $component_verify_ticket['verify_ticket'];
    }

    /**
     * [index 授权页面]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
	public function index() {
		$webSite = C('webSite');
		$redirect_uri = trim($webSite, '/') . U('Open/authorization_code');
		$pre_auth_code = $this->pre_auth_code();
		$url = "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->appID}&pre_auth_code={$pre_auth_code}&redirect_uri={$redirect_uri}";
		// redirect($url);
		
		echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';
		echo "<a href='{$url}'' title='授权'>进入授权</a>";
	}

	/**
	 * [authorization_code 收取授权码]
	 * @author NicFung <13502462404@qq.com>
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
		M('component_authorization_code')->data($addData)->add();

		$this->createAuthorizerToken($auth_code);
	}

	/**
	 * [getAuthCode 查找授权码]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getAuthCode() {
		$authCodeInfo = M('component_authorization_code')->order('`autoid` DESC')->find();
		return $authCodeInfo['auth_code'];
	}

	/**
	 * [getAuthorizerAccessToken 获取授权令牌]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getAuthorizerAccessToken() {
		$nowTime = time();
		$authorizerAccessTokenModel = M('component_authorizer_access_token');
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
	 * [refreshAuthorizerToken 刷新AuthorizerToken]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $appId        [description]
	 * @param     [type]        $refreshToken [description]
	 * @return    [type]                      [description]
	 */
	public function refreshAuthorizerToken($appId, $refreshToken) {
		$component_access_token = $this->getAccessToken();
		$authorizerAccessTokenModel = M('component_authorizer_access_token');
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
		$authorizerAccessTokenModel = M('component_authorizer_access_token');
		$component_access_token = $this->getAccessToken();
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

	public function checkCoupon() {
        $component_access_token = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$component_access_token}";
		$data = array(
			'component_appid' => 'wx794f109f77f279ba',
			'authorizer_appid' => 'wx20dd315e5d04a413',
		);
        $code = curl($url, json_encode($data));
        $code = json_decode($code, true);
        dump($code);
	}


	/**
	 * [authjumpAuthOauth 构造授权地址]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function authjumpAuthOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = $this->authMkAuthUrl($uri, $this->appID);
        header("LOCATION:{$url}");
        exit();
    }

    /**
     * [authMkAuthUrl 网页授权]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)     2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $url    [description]
     * @param     boolean       $encode [description]
     * @return    [type]                [description]
     */
    public function authMkAuthUrl($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }
        $appId  = $this->wx_appid;
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=p&component_appid={$this->appID}#wechat_redirect";
    }

	/**
	 * [authjumpAuthOauth 构造授权地址]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function authjumpAuthOauth1() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = $this->authMkAuthUrl1($uri, $this->appID);
        header("LOCATION:{$url}");
        exit();
    }

    /**
     * [authMkAuthUrl 网页授权]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)     2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $url    [description]
     * @param     boolean       $encode [description]
     * @return    [type]                [description]
     */
    public function authMkAuthUrl1($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }
        $appId  = $this->wx_appid;
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=p&component_appid={$this->appID}#wechat_redirect";
    }

    /**
     * [OauthAuthCode 获取用户授权信息]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)   2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $code [description]
     */
    public function OauthAuthCode($code) {
        $weixinAppID     = $this->wx_appid;
        $component_access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$weixinAppID}&code={$code}&grant_type=authorization_code&component_appid={$this->appID}&component_access_token={$component_access_token}";
        $code = curl($url, '', 'get');
        $code = json_decode($code, true);
        return $code;
    }

    /**
     * [authGetUserInfo 获取用户详细信息]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $access_token [description]
     * @param     [type]        $openid       [description]
     * @return    [type]                      [description]
     */
    public function authGetUserInfo($access_token, $openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $result = curl($url, '', 'GET');
        $resultArray = json_decode($result, true);
        return $resultArray;
    }

    /**
     * [getAuthorizerInfo 获取授权方的帐号基本信息]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getAuthorizerInfo($authorizer_appid) {
    	$accessToken = $this->getAccessToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token={$accessToken}";
    	$data = array(
    		'component_appid' => $this->appID,
    		'authorizer_appid' => $authorizer_appid,
    	);
        $returnStr = curl($url, json_encode($data));
        $returnData = json_decode($returnStr, true);
        return $returnData;
    }
}