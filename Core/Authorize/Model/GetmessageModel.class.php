<?php
namespace Authorize\Model;
use Think\Model;
// use Common\Library\EncodingAES\WXBizMsgCrypt;

class GetmessageModel extends Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * [checkMessage 查找消息是否已经存在]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */  
	public function checkMessage($msgId) {
		// $table = $this->db->dbprefix . 'message';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $query = $this->db->query($selectSql, $data);
		// return $query->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('message')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgImage 检测图片消息是否重复]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */
	public function checkMsgImage($msgId) {
		// $table = $this->db->dbprefix . 'msgimage';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('msgimage')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgLink 检测链接消息是否重复]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */
	public function checkMsgLink($msgId) {
		// $table = $this->db->dbprefix . 'msglink';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('msglink')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgLocation 检测地图消息是否重复]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */
	public function checkMsgLocation($msgId) {
		// $table = $this->db->dbprefix . 'msglocation';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('msglocation')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgText 检测文本消息是否重复]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */
	public function checkMsgText($msgId) {
		// $table = $this->db->dbprefix . 'msgtext';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('msgtext')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgVoice 检测语音消息是否重复]
	 * @param  [type] $msgId [description]
	 * @return [type]        [description]
	 */
	public function checkMsgVoice($msgId) {
		// $table = $this->db->dbprefix . 'msgvoice';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `MsgId` = ? LIMIT 1";
		// $data = array(
		// 	$msgId
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'MsgId' => $msgId
		);
		$result = M('msgvoice')->where($whereData)->count();
		return $result;
	}

	/**
	 * [checkMsgEvent 检测关注是否重复]
	 * @param  [integer] $createTime   [消息时间]
	 * @param  [string] $fromUserName [发送者]
	 * @return [integer]               [消息行数]
	 */
	public function checkMsgEvent($createTime, $fromUserName) {
		// $table = $this->db->dbprefix . 'msgevent';
		// $selectSql = "SELECT `autoId` FROM `$table` WHERE `CreateTime` = ? AND `FromUserName` = ? LIMIT 1";
		// $data = array(
		// 	$createTime,
		// 	$fromUserName
		// );
		// $res = $this->db->query($selectSql, $data);
		// // echo $sql = $this->db->last_query();
		// return $res->num_rows();
		$whereData = array(
			'CreateTime' => $createTime,
			'FromUserName' => $fromUserName
		);
		$result = M('msgevent')->where($whereData)->count();
		return $result;
	}

	/**
	 * [insertMsgEvent 保存关注消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgEvent($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msgevent';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'Event' => empty($requestMsg["Event"]) ? '' : $requestMsg["Event"], 
			'EventKey' => empty($requestMsg["EventKey"]) ? '' : $requestMsg["EventKey"], 
			'Ticket' => empty($requestMsg["Ticket"]) ? '' : $requestMsg["Ticket"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Event`, `EventKey`, `Ticket`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msgevent')->data($data)->add();
		return $addResult;
	}

	/**
	 * [insertMsgImage 保存图片消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgImage($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msgimage';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'PicUrl' => empty($requestMsg["PicUrl"]) ? '' : $requestMsg["PicUrl"], 
			'MediaId' => empty($requestMsg["MediaId"]) ? '' : $requestMsg["MediaId"], 
			'MsgId' => empty($requestMsg["MsgId"]) ? '' : $requestMsg["MsgId"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `PicUrl`, `MediaId`, `MsgId`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msgimage')->data($data)->add();
		return $addResult;
	}

	/**
	 * [insertMsgLink 保存链接消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgLink($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msglink';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'Title' => empty($requestMsg["Title"]) ? '' : $requestMsg["Title"], 
			'Description' => empty($requestMsg["Description"]) ? '' : $requestMsg["Description"], 
			'Url' => empty($requestMsg["Url"]) ? '' : $requestMsg["Url"], 
			'MsgId' => empty($requestMsg["MsgId"]) ? '' : $requestMsg["MsgId"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Title`, `Description`, `Url`, `MsgId`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msglink')->data($data)->add();
		return $addResult;
	}
	
	/**
	 * [insertMsgLocation 保存地图消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgLocation($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msglocation';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'Location_X' => empty($requestMsg["Location_X"]) ? '' : $requestMsg["Location_X"], 
			'Location_Y' => empty($requestMsg["Location_Y"]) ? '' : $requestMsg["Location_Y"], 
			'Scale' => empty($requestMsg["Scale"]) ? '' : $requestMsg["Scale"], 
			'Label' => empty($requestMsg["Label"]) ? '' : $requestMsg["Label"], 
			'MsgId' => empty($requestMsg["MsgId"]) ? '' : $requestMsg["MsgId"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Location_X`, `Location_Y`, `Scale`, `Label`, `MsgId`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msglocation')->data($data)->add();
		return $addResult;
	}
	
	/**
	 * [insertMsgText 保存文本消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgText($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msgtext';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'Content' => empty($requestMsg["Content"]) ? '' : $requestMsg["Content"], 
			'MsgId' => empty($requestMsg["MsgId"]) ? '' : $requestMsg["MsgId"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `Content`, `MsgId`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msgtext')->data($data)->add();
		return $addResult;
	}

	/**
	 * [insertMsgVoice 保存语音消息]
	 * @param  [type] $requestMsg [description]
	 * @return [type]             [description]
	 */
	public function insertMsgVoice($requestMsg, $appinfoId) {
		// $table = $this->db->dbprefix . 'msgvoice';
		$data = array(
			'ToUserName' => empty($requestMsg["ToUserName"]) ? '' : $requestMsg["ToUserName"], 
			'FromUserName' => empty($requestMsg["FromUserName"]) ? '' : $requestMsg["FromUserName"], 
			'CreateTime' => empty($requestMsg["CreateTime"]) ? '' : $requestMsg["CreateTime"], 
			'MsgType' => empty($requestMsg["MsgType"]) ? '' : $requestMsg["MsgType"], 
			'MediaId' => empty($requestMsg["MediaId"]) ? '' : $requestMsg["MediaId"], 
			'Format' => empty($requestMsg["Format"]) ? '' : $requestMsg["Format"], 
			'MsgID' => empty($requestMsg["MsgID"]) ? '' : $requestMsg["MsgID"], 
			'Recognition' => empty($requestMsg["Recognition"]) ? '' : $requestMsg["Recognition"], 
			'appinfoId' => $appinfoId
		);
		// $insertSql = "INSERT INTO `$table`(`ToUserName`, `FromUserName`, `CreateTime`, `MsgType`, `MediaId`, `Format`, `MsgID`, `Recognition`, `appinfoId`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('msgvoice')->data($data)->add();
		return $addResult;
	}

	/**
	 * [insertMessage 新增消息记录]
	 * @param  [type] $appinfoId   [description]
	 * @param  [type] $msgType [description]
	 * @param  [type] $msgId   [description]
	 * @return [type]          [description]
	 */
	public function insertMessage($appinfoId, $msgType, $msgId) {
		// $table = $this->db->dbprefix . 'message';
		$data = array(
			'appinfoId' => $appinfoId,
			'msgType' => (string)$msgType,
			'msgId' => (string)$msgId
		);
		// $insertSql = "INSERT INTO `$table`(`appinfoId`, `msgType`, `msgId`) VALUES(?, ?, ?)";
		// $this->db->query($insertSql, $data);
		// return $this->db->insert_id();
		$addResult = M('message')->data($data)->add();
		return $addResult;
	}

	/**
	 * [updateAppinfo 更新微信app信息]
	 * @param  [type] $userToken [description]
	 * @param  [type] $userId    [description]
	 * @param  [type] $weixinId  [description]
	 * @return [type]            [description]
	 */
	public function updateAppinfo($userToken, $userId, $weixinId="", $appId="", $appSecret="") {
		// $table = $this->db->dbprefix . 'appinfo';
		$set = "";
		if(!empty($weixinId)) {
			$saveData['weixinId'] = $weixinId;
			// if(empty($set)) {
			// 	$set .= "`weixinId` = ?";
			// } else {
			// 	$set .= " AND `weixinId` = ?";
			// }
			// $data[] = $weixinId;
		}
		if(!empty($appId)) {
			$saveData['appId'] = $appId;
			// if(empty($set)) {
			// 	$set .= "`appId` = ?";
			// } else {
			// 	$set .= " AND `appId` = ?";
			// }
			// $data[] = $appId;
		}
		if(!empty($appSecret)) {
			$saveData['appSecret'] = $appSecret;
			// if(empty($set)) {
			// 	$set .= "`appSecret` = ?";
			// } else {
			// 	$set .= " AND `appSecret` = ?";
			// }
			// $data[] = $appSecret;
		}
		if(empty($saveData)) {
			return FALSE;
		}
		$whereData[] = $userToken;
		$whereData[] = $userId;
		// $updateSql = "UPDATE `$table` SET $set WHERE `userToken` = ? AND `userId` = ?";
		// $query = $this->db->query($updateSql, $data);
		// $result = $this->db->affected_rows();
		$result = M('appinfo')->where($whereData)->data($saveData)->save();
		return $result;
	}
	
	/**
	 * [selectAppinfoToken 搜索用户token]
	 * @param  [string] $token [用户token]
	 * @return [integer]        [结果行数]
	 */
	public function selectAppinfoToken($token) {
		// $table = $this->db->dbprefix . 'appinfo';
		$data = array(
			'userToken' => $token,
		);
		// $selectSql = "SELECT `autoId`, `userId`, `userToken`, `weixinId` FROM `$table` WHERE `userToken` = ? LIMIT 1";
		// $query = $this->db->query($selectSql, $data);
		// echo $this->db->last_query();
		// $result = $query->row_array();
		$result = M('appinfo')->where($data)->select();
		return $result;
	}
	
	/**
	 * [selectAppinfoAppId 搜索用户appid]
	 * @param  [string] $token [用户token]
	 * @return [integer]        [结果行数]
	 */
	public function selectAppinfoAppId($appId) {
		$result = M('component_authorizer_access_token')->where(array('authorizer_appid'=>$appId))->select();
		return $result;
	}

	/**
	 * [uodateAppInfo 添加/更新微信原始Id]
	 * @param  [type] $token    [description]
	 * @param  [type] $weixinId [原始Id]
	 * @return [type]           [description]
	 */
	public function uodateAppInfo($token, $weixinId) {
		// $table = $this->db->dbprefix . 'appinfo';
		$data = array(
			'weixinId' => $weixinId,
		);
		$whereData = array(
			'userToken' => $token,
		)
		// $selectSql = "UPDATE `$table` SET `weixinId` = ? WHERE `userToken` = ?";
		// $query = $this->db->query($selectSql, $data);
		// $result = $this->db->insert_id();
		$result = M('appinfo')->data($data)->where($whereData)->save();
		return $result;
	}
}

/* End of file getmessagemodel.php */
/* Location: ./application/models/getmessagemodel.php */