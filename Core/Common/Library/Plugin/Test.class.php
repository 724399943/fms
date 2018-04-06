<?php
use Think\Controller;

class Test extends Controller {
	private $lib_wxresponse;
	private $fromUserName;
	private $toUserName;
	public function __construct($data) {
		$this->fromUserName = $data['fromUserName'];
		$this->toUserName = $data['toUserName'];
		$this->lib_wxresponse = new \Common\Library\Lib_wxresponse();
	}
	
	public function index() {
		$responseMsg = "这是一个的控制器";
		$response = $this->lib_wxresponse->responseTextMsg($this->fromUserName, $this->toUserName, $responseMsg);
		echo $response;
	}
}