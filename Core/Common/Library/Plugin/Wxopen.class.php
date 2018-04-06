<?php
use Think\Controller;
use Plugins\WechatOpen\AccessController;

class Wxopen extends Controller {

	private $lib_wxresponse;
	private $requestMsg;
	// private $fromUserName;
	// private $toUserName;

	public function __construct($data) {
		// $this->fromUserName = $data['fromUserName'];
		// $this->toUserName = $data['toUserName'];
		$this->requestMsg = $data;
		$this->lib_wxresponse = new \Common\Library\Lib_wxresponse();
	}
	public function getaccessToken($authorization_code) {
		$authorizerAccessTokenModel = M('component_authorizer_access_token');
		$accessClass = new AccessController();
		$getAccessToken = $accessClass->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token={$getAccessToken}";
		$data = array(
			'component_appid' => 'wx570bc396a51b8ff8',
			'authorization_code' => $authorization_code,
		);
		$nowTime = time();
		$returnStr = curl($url, json_encode($data));
		file_put_contents('component_authorizer_access_token', $returnStr);
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
			$authorizerAccessTokenModel->data($addData)->where($whereData)->save();
		}
		// return $returnData;
		return $returnData['authorization_info']['authorizer_access_token'];
	}

	public function kefu($openid, $content) {
		$openid = empty($openid) ? I('openid', '') : $openid;
		$content = empty($content) ? I('content', '') : $content;
		$content .= '_from_api';
		$accessClass = new AccessController();
		$getAccessToken = $accessClass->getAuthorizerAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$getAccessToken}";
		$data = array(
			'touser' => $openid,
			'msgtype' => 'text',
			'text' => array(
				'content' => $content
			)
		);
		$returnStr = curl($url, json_encode($data));
		file_put_contents('kefu', $returnStr);
		// $returnData = json_decode($returnStr, true);
	}

	public function index() {
		if ( ! empty ( $this->requestMsg["Event"] ) ) {
			$responStr = $this->requestMsg["Event"] . "from_callback";
		} elseif ( $this->requestMsg["Content"] == 'TESTCOMPONENT_MSG_TYPE_TEXT' ) {
			$responStr = 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
		} elseif ( strpos( $this->requestMsg["Content"], 'QUERY_AUTH_CODE:') !== false ) {
			$authorization_code = str_replace('QUERY_AUTH_CODE:', '', $this->requestMsg["Content"]);
			$this->getaccessToken($authorization_code);
			$this->kefu($this->requestMsg['FromUserName'], $authorization_code);
			$responStr = '';
		} else {
			
		}
		file_put_contents('requestMsg.log', json_encode($this->requestMsg) . "\n", FILE_APPEND);
		$response = $this->lib_wxresponse->responseTextMsg($this->requestMsg['FromUserName'], $this->requestMsg['ToUserName'], $responStr);
		file_put_contents('response.log', $response . "\n", FILE_APPEND);

		// if ( empty( $userInfo ) ) {
		// 	$accessClass = new \Plugins\WechatOpen\AccessController();
		// 	$accessToken = $accessClass->getAuthorizerAccessToken();
		// 	$wxoauthClass = new \Common\Library\Lib_wxoauth();
		// 	$userInfo = $wxoauthClass->userinfo($accessToken, $this->fromUserName);
		// 	if ( ! empty ($userInfo['errcode']) ) {
		// 		$addData = array(
		// 			'open_id' => $this->fromUserName,
		// 			'add_time' => time(),
		// 		);
		// 	} else {
		// 		$addData = array(
		// 			"open_id" => $userInfo['openid'],
		// 			"subscribe" => $userInfo['subscribe'],
		// 			"nickname" => $userInfo['nickname'],
		// 			"sex" => $userInfo['sex'],
		// 			"language" => $userInfo['language'],
		// 			"city" => $userInfo['city'],
		// 			"province" => $userInfo['province'],
		// 			"country" => $userInfo['country'],
		// 			"headimgurl" => $userInfo['headimgurl'],
		// 			"subscribe_time" => $userInfo['subscribe_time'],
		// 			"unionid" => $userInfo['unionid'],
		// 			"remark" => $userInfo['remark'],
		// 			"groupid" => $userInfo['groupid'],
		// 			'add_time' => time(),
		// 		);
		// 	}
		// 	$userModel->data($addData)->add();
		// }
		// $articlesArr = array(
		// 	array(
		// 		'parameter' => true,
		// 		'url' => 'http://open.65.xcrozz.com/Gqhl',
		// 		'title' => '广汽汇理车神接力赛',
		// 		'description' => '地表最强福利！好友聚力赛车赢壕礼',
		// 		'picurl' => 'http://open.65.xcrozz.com/Static/Gqhl/response.jpg',
		// 	),
		// );
		// $response = $this->lib_wxresponse->responseNewsMsg($this->fromUserName, $this->toUserName, $articlesArr);
		
		return $response;
	}
}