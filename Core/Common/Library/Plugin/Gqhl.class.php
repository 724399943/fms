<?php
use Think\Controller;
// use Plugins\WechatOpen\AccessController;

class Gqhl extends Controller {

	private $lib_wxresponse;
	private $fromUserName;
	private $toUserName;

	public function __construct($data) {
		$this->fromUserName = $data['fromUserName'];
		$this->toUserName = $data['toUserName'];
		// $this->appinfoId = APPINFOID;
		$this->lib_wxresponse = new \Common\Library\Lib_wxresponse();
	}

	public function index() {
		// $responStr = $this->fromUserName;
		// $response = $this->lib_wxresponse->responseTextMsg($this->fromUserName, $this->toUserName, $responStr);
		$whereData = array(
			'open_id' => $this->fromUserName,
		);
		$userModel = M('user');
		$userInfo = $userModel->where($whereData)->find();
		if ( empty( $userInfo ) ) {
			$accessClass = new \Plugins\WechatOpen\AccessController();
			$accessToken = $accessClass->getAuthorizerAccessToken();
			// file_put_contents('accessToken', $accessToken);
			$wxoauthClass = new \Common\Library\Lib_wxoauth();
			$userInfo = $wxoauthClass->userinfo($accessToken, $this->fromUserName);
			if ( ! empty ($userInfo['errcode']) ) {
				$addData = array(
					'open_id' => $this->fromUserName,
					'add_time' => time(),
				);
			} else {
				$addData = array(
					"open_id" => $userInfo['openid'],
					"subscribe" => $userInfo['subscribe'],
					"nickname" => $userInfo['nickname'],
					"sex" => $userInfo['sex'],
					"language" => $userInfo['language'],
					"city" => $userInfo['city'],
					"province" => $userInfo['province'],
					"country" => $userInfo['country'],
					"headimgurl" => $userInfo['headimgurl'],
					"subscribe_time" => $userInfo['subscribe_time'],
					"unionid" => $userInfo['unionid'],
					"remark" => $userInfo['remark'],
					"groupid" => $userInfo['groupid'],
					'add_time' => time(),
				);
			}
			$userModel->data($addData)->add();
		}
		$articlesArr = array(
			array(
				'parameter' => true,
				'url' => 'http://open.65.xcrozz.com/Gqhl',
				'title' => '广汽汇理车神接力赛',
				'description' => '地表最强福利！好友聚力赛车赢壕礼',
				'picurl' => 'http://open.65.xcrozz.com/Static/Gqhl/response.jpg',
			),
		);

		$response = $this->lib_wxresponse->responseNewsMsg($this->fromUserName, $this->toUserName, $articlesArr);
		return $response;
	}
}