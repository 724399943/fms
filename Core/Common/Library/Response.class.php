<?php
namespace Common\Library;
use Think\Controller;
use Authorize\Model\KeywordsModel;

class Response extends Controller {

	private $keywordsmodel;
	private $lib_wxresponse;

	public function __construct() {
		$this->keywordsmodel = D("Keywords");
		// $this->ci->load->library("lib_wxresponse");
		// import('@.Library.lib_wxresponse');
		$this->lib_wxresponse = new \Common\Library\Lib_wxresponse();
	}

	/**
	 * [responseMsg 处理消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]			 [description]
	 */
	public function responseMsg($requestMsg) {
		// $checkList = $this->checkLibraryList(); //检测默认开启功能
		// if($checkList !== TRUE) { //没有打开默认功能
		// 	$libJson = json_encode($checkList);
		// } else { //已开启默认功能
		// 	
		// }
		// file_put_contents('requestMsg', json_encode($requestMsg));
		$msgType = $requestMsg["MsgType"]; // 消息类型
		$toUserName = $requestMsg["ToUserName"]; // 发送方帐号（一个OpenID）
		$fromUserName = $requestMsg["FromUserName"]; // 开发者微信号
		// file_put_contents('msgType', $msgType);
		switch ($msgType) {
			case 'event':
				$event = $requestMsg["Event"];
				$eventKey = $requestMsg["EventKey"];
				// $eventArr = array(
				// 	"event" => $event,
				// );
				// if(!empty($eventKey)) {
				// 	$eventArr["eventKey"] = $eventKey;
				// }
				// $jsonEvent = json_encode($eventArr);
				// $result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $jsonEvent);
				$eventJson = json_encode(array('event'=>$event, 'eventKey'=>$eventKey));
				switch ($event) {
					case 'subscribe':
						$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $event);
						break;
					case 'unsubscribe':
						$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $event);
						break;
					case 'SCAN':
						$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $eventJson);
						break;
					case 'CLICK':
						$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $eventJson);
						break;
					default:
						$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "event", 1, $eventJson);
						break;
				}
				if(!empty($result)) {
					$keywordId = $result["autoId"]; //自增id
					$repeatTime = $result["repeatTime"]; //重复间隔时间
					$responseType = $result["responseType"]; //回复类型
					$responseMsg = $result["responseMsg"]; //回复内容
					// break;
				} else {
					$responseType = "";
				}
				break;
			case 'voice':
				$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "voice", 1);
				if(!empty($result)) {
					// $requestMsg["Recognition"];
					$keywordId = $result["autoId"]; //自增id
					$repeatTime = $result["repeatTime"]; //重复间隔时间
					$responseType = $result["responseType"]; //回复类型
					$responseMsg = $result["responseMsg"]; //回复内容
					break;
				} else {
					$requestMsg["Content"] = $requestMsg["Recognition"];
				}
			case 'text':
				$result = $this->keywordsmodel->selectKeyowords(APPINFOID, "text", 1);
				if( ! empty ( $result ) ) {
					foreach ($result as $key => $value) {
						if($value["match"] == 1 && $requestMsg["Content"] == $value["keyword"]) {
							$keywordId = $value["id"]; //自增id
							$repeatTime = $value["repeatTime"]; //重复间隔时间
							$responseType = $value["responseType"]; //回复类型
							$responseMsg = $value["responseMsg"]; //回复内容
							break;
						} elseif($value["match"] == 2 && stristr($requestMsg["Content"], $value["keyword"])) {
							$keywordId = $value["id"]; //自增id
							$repeatTime = $value["repeatTime"]; //重复间隔时间
							$responseType = $value["responseType"]; //回复类型
							$responseMsg = $value["responseMsg"]; //回复内容
							break;
						}
					}
					if(!empty($responseType) && !empty($responseMsg)) {
						break;
					}
				}
				// $getmessage = $requestMsg["Content"]; // 发送消息内容
				// $content = "欢迎关注,功能正在研发中,敬请等待..."; // 需要返回的内容
				// $response = $this->lib_wxresponse->responseTextMsg($fromUserName, $toUserName, $content);
				// echo $response;
				// break;
			default:
				// $content = $this->lang->line('public_default_response'); // 需要返回的内容
				// $response = $this->lib_wxresponse->responseTextMsg($fromUserName, $toUserName, $content);
				// echo $response;
				$default = $this->keywordsmodel->selectKeyowords(APPINFOID, "default", 1, "default");
				if(!empty($default)) {
					$keywordId = $default["autoId"]; //自增id
					$repeatTime = $default["repeatTime"]; //重复间隔时间
					$responseType = $default["responseType"]; //默认 //回复类型值
					$responseMsg = $default["responseMsg"]; //默认值 //回复内容
				} else {
					$repeatTime = 0;
					$responseType = "";
				}
				break;
		}
		if($repeatTime > 0 || $repeatTime == -1) {
			$log = $this->keywordsmodel->selectResponselog($fromUserName, $keywordId, '' , 1);
			if(!empty($log)) {
				if($repeatTime == -1) {
					die("");
				}
				$expire = $log["inTime"] + $repeatTime;
				if($expire >= time()) {
					die("");
				}
			}
			$this->keywordsmodel->insertResponselog($fromUserName, $keywordId, time());
		}
		switch ($responseType) {
			case 'text': //返回文字信息
				$response = $this->lib_wxresponse->responseTextMsg($fromUserName, $toUserName, $responseMsg);
				break;
			case 'news': //返回图文信息
				$responseArr = json_decode($responseMsg, TRUE);
				$response = $this->lib_wxresponse->responseNewsMsg($fromUserName, $toUserName, $responseArr);
				break;
			case 'transfer': //返回多客服
				$response = $this->lib_wxresponse->responseTransferMsg($fromUserName, $toUserName, $responseMsg);
				break;
			case 'iamge': //返回图片信息
				$responseArr = json_decode($responseMsg, TRUE);
				$response = $this->lib_wxresponse->responseImageMsg($fromUserName, $toUserName, $responseArr);
				break;
			case 'music': //返回音乐信息
				$responseArr = json_decode($responseMsg, TRUE);
				$response = $this->lib_wxresponse->responseMusicMsg($fromUserName, $toUserName, $responseArr);
				break;
			case 'video': //返回视频信息
				$responseArr = json_decode($responseMsg, TRUE);
				$response = $this->lib_wxresponse->responseVideoMsg($fromUserName, $toUserName, $responseArr);
				break;
			case 'voice': //返回语音信息
				$responseArr = json_decode($responseMsg, TRUE);
				$response = $this->lib_wxresponse->responseVoiceMsg($fromUserName, $toUserName, $responseArr);
				break;
			case 'library': //类
				// $response = $this->lib_wxresponse->responseVoiceMsg($fromUserName, $toUserName, $responseMsg);
				$responseArr = json_decode($responseMsg, TRUE);
				// $data = array(
				// 	'fromUserName' => $fromUserName,
				// 	'toUserName' => $toUserName,
				// );
				$responseArr["library"] = ucfirst($responseArr["library"]);

				import('Common.Library.Plugin.' . $responseArr["library"]);
				// $library = new \Common\Library\Plugin\$responseArr["library"]($data);
				// eval()
				$library = new $responseArr["library"]($requestMsg);
				// $this->ci->load->library("plugin/" . $responseArr["library"], $data);
				$response = $library->$responseArr["function"]();
				break;
			default:
				// $response = $this->lib_wxresponse->responseTextMsg($fromUserName, $toUserName, $responseMsg);
				$response = "";
				break;
		}
		return $response;
	}
}