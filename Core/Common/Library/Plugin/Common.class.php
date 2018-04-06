<?php
class Common {
	private $lib_wxresponse;
	private $fromUserName;
	private $toUserName;
	private $accessClass;
	private $wxoauthClass;
	private $userModel;
	private $articlesArr = [];
	public function __construct($parameter) {
		$this->setParameter($parameter);
	}

	public function index() {
		$whereData = array(
			'open_id' => $this->fromUserName,
		);
		$userInfo = $this->userModel->getUserInfo($whereData);
		if ( empty( $userInfo ) ) {
			$accessToken = $this->accessClass->getAuthorizerAccessToken();
			$userInfo = $this->wxoauthClass->userinfo($accessToken, $this->fromUserName);
			if ( ! empty ($userInfo['errcode']) ) {
				$addData = array(
					'open_id' => $this->fromUserName,
					'add_time' => time(),
				);
			} else {
				$addData = $this->userModel->create($userInfo, 5);
			}
			$this->userModel->data($addData)->add();
		}
		$response = $this->lib_wxresponse->responseNewsMsg($this->fromUserName, $this->toUserName, $this->articlesArr);
		return $response;
	}

	private function setParameter(array $parameter) {
		foreach ($parameter as $key => $value) {
			$this->$key = $value;
		}
	}
}