<?php
class McDonald {

	private $fromUserName;
	private $toUserName;
	private $url = 'http://open.65.xcrozz.com/Gqhl';
	private $title = '广汽汇理车神接力赛';
	private $description = '地表最强福利！好友聚力赛车赢壕礼';
	private $picurl = 'http://open.65.xcrozz.com/Static/Gqhl/response.jpg';

	public function __construct($data) {
		import('Common.Library.Plugin.Common');
		$this->fromUserName = $data['fromUserName'];
		$this->toUserName = $data['toUserName'];
	}

	public function index() {
		$lib_wxresponse = new \Common\Library\Lib_wxresponse();
		$accessClass = new \Plugins\WechatOpen\AccessController();
		$wxoauthClass = new \Common\Library\Lib_wxoauth();
		$userModel = D('Home/User');
		$parameter = [
			'lib_wxresponse' => $lib_wxresponse,
			'fromUserName' => $this->fromUserName,
			'toUserName' => $this->toUserName,
			'accessClass' => $accessClass,
			'wxoauthClass' => $wxoauthClass,
			'userModel' => $userModel,
			'articlesArr' => [
				[
					'parameter' => true,
					'url' => $this->url,
					'title' => $this->title,
					'description' => $this->description,
					'picurl' => $this->picurl,
				]
			],
		];
		$commonClass = new \Common($parameter);
		$response = $commonClass->index();
		return $response;
	}
}