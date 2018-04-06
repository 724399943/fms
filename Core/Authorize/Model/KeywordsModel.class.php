<?php
namespace Authorize\Model;
use Think\Model;

class KeywordsModel extends Model {

	public function __construct() {
		parent::__construct();
		// $this->load->database();
	}

	public function index() {
		return FALSE;
	}

	/**
	 * [selectKeyowords 查找关键词]
	 * @param  [string] $appinfoId [微信号]
	 * @param  [string] $type      [消息类型]
	 * @param  [string] $isUse     [是否启用]
	 * @param  [string] $keyword   [关键词]
	 * @return [array]             [description]
	 */
	public function selectKeyowords($appinfoId, $type="", $isUse="", $keyword="") {
		// $table = $this->db->dbprefix . 'keywords';
		// $data = array(
			// $appinfoId,
		// );
		// $whereStr = "";
		$whereData = array(
			'appinfoId' => $appinfoId,
		);
		if(!empty($type)) {
			$whereData['getType'] = $type;
			// $whereStr .= " AND `getType` = ?";
			// $data[] = $type;
		}
		if(!empty($isUse)) {
			$whereData['isUse'] = $isUse;
			// $whereStr .= " AND `isUse` = ?";
			// $data[] = $isUse;
		}
		if(!empty($keyword)) {
			$whereData['keyword'] = $keyword;
			// $whereStr .= " AND `keyword` = ?";
			// $data[] = $keyword;
		}
		// $selectSql = "SELECT * FROM `$table` WHERE `appinfoId` = ? $whereStr";
		// $query = $this->db->query($selectSql, $data);
		if( !empty($keyword) ) {
			// $result = $query->row_array();
			$result = M('keywords')->where($whereData)->find();
		} else {
			// $result = $query->result_array();
			$result = M('keywords')->where($whereData)->select();
		}
		return $result;
	}

	/**
	 * [insertResponselog 记录回复历史]
	 * @param  [type] $openId    [description]
	 * @param  [type] $keywordId [description]
	 * @param  [type] $inTime    [description]
	 * @return [type]            [description]
	 */
	public function insertResponselog($openId, $keywordId, $inTime) {
		// $table = $this->db->dbprefix . 'responselog';
		// $data = array(
		// 	$openId,
		// 	$keywordId,
		// 	$inTime,
		// );
		// $insertSql = "INSERT INTO `$table`(`openId`, `keywordId`, `inTime`) VALUES(?, ?, ?)";
		// $query = $this->db->query($insertSql, $data);
		// $result = $this->db->insert_id();
		$addData = array(
			'openId' => $openId,
			'keywordId' => $keywordId,
			'inTime' => $inTime,
		);
		$result = M('responselog')->data($addData)->add();
		return $result;
	}

	/**
	 * [selectResponselog 读取回复历史]
	 * @param  [type] $openId    [description]
	 * @param  [type] $keywordId [description]
	 * @param  [type] $inTime    [description]
	 * @return [type]            [description]
	 */
	public function selectResponselog($openId, $keywordId, $inTime="", $limit="") {
		// $table = $this->db->dbprefix . 'responselog';
		// $data = array(
		// 	$openId,
		// 	$keywordId,
		// );
		$whereData = array(
			'openId' => $openId,
			'keywordId' => $keywordId,
		);
		// $whereStr = "";
		// $limitStr = "";
		if(!empty($inTime)) {
			$whereData['inTime'] = array('egt', $inTime);
			// $whereStr .= " AND `inTime` >= ?";
			// $data[] = $inTime;
		}
		// if(!empty($limit)) {
		// 	$limitStr .= " LIMIT $limit";
		// }
		// $selectSql = "SELECT * FROM `$table` WHERE `openId` = ? AND `keywordId` = ?$whereStr ORDER BY `autoId` DESC$limitStr";
		// $query = $this->db->query($selectSql, $data);
		if($limit == 1) {
			$result = M('responselog')->where($whereData)->limit($limit)->find();
			// $result = $query->row_array();
		} else {
			$result = M('responselog')->where($whereData)->limit($limit)->select();
			// $result = $query->result_array();
		}
		return $result;
	}
}