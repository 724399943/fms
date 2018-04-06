<?php
namespace Common\Library;
use Think\Controller;
class Lib_access extends Controller {
	private $Accessmodel;

	public function __construct() {
		parent::__construct();
		// 读取数据库配置
        $c = M('config')->getField('config_sign, config_value');
        C($c);
        define(WX_APPID, C('weixinAppID'));
        define(WX_APPSECRET, C('weixinAppSecret'));
		$this->Accessmodel 	= D('Accesstoken');
	}

	/**
	 * [seachAccessToken 查找Access Token]
	 * @return [type]         [description]
	 */
	public function seachAccessToken() {
		$accessRes = $this->Accessmodel->selectAccess();
		// $appInfo = $this->getAppinfo();
		if(empty($accessRes)) {
			$return = $this->getAccessToken(WX_APPID, WX_APPSECRET);
		} else {
			$expiresTime = $accessRes["get_time"] + $accessRes["expires_in"];
			if (time() >= $expiresTime) {
				$return = $this->getAccessToken(WX_APPID, WX_APPSECRET);
			} else {
				$return = $accessRes["access_token"];
			}
		}
		return $return;
	}

	/**
	 * [getAppinfo 获取appinfo信息]
	 * @return [type] [description]
	 */
	public function getAppinfo() {
		$appInfo = $this->Accessmodel->selectAppinfo();
		return $appInfo;
	}

	/**
	 * [getAccessToken 获取Access Token数据]
	 * @param  [type] $appid      [description]
	 * @param  [type] $appsecret  [description]
	 * @return [type]             [description]
	 */
	public function getAccessToken($appid, $appsecret) {
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		// $jsonStr = file_get_contents($url);
		$getTime = time();
		$jsonStr = curlGet($url);
		$result = json_decode($jsonStr, TRUE);
		if(!empty($result["access_token"])) {
			$result["expires_in"] = $result["expires_in"] - 3600;
			$this->Accessmodel->insertAccess($result["access_token"], $getTime, $result["expires_in"]);
			$return = $result["access_token"];
		} else {
			$return = FALSE;
		}
		// {"access_token":"ACCESS_TOKEN","expires_in":7200}
		// {"errcode":40013,"errmsg":"invalid appid"}
		return $return;
	}

	/**
	 * [getUserdata 获取用户信息]
	 * @param  [type] $openId      [普通用户的标识，对当前公众号唯一]
	 * @return [type]              [description]
	 */
	public function getUserdata($openId) {
		$accessToken = $this->seachAccessToken();
		if(!$accessToken) {
			return 'error';
		}
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openId&lang=zh_CN";
		$jsonStr = curlGet($url);
		$result = json_decode($jsonStr, TRUE);
		if(!empty($result["subscribe"])) {
			$return = $result;
		} elseif(empty($result["errcode"])) {
			$return = $result;
		} else {
			$return = FALSE;
		}
		return $return;
	}

	/**
	 * [getIpList 获取微信服务器IP]
	 * @return [type]              [description]
	 */
	public function getIpList() {
		$accessToken = $this->seachAccessToken();
		if(!$accessToken) {
			return 'error';
		}
		$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=$accessToken";
		$jsonStr = curlGet($url);
		$result = json_decode($jsonStr, TRUE);
		return $result;
	}

	/**
	 * [setMedia 上传多媒体文件]
	 * @method post
	 * @param [type] $type [description]
	 */
	public function setMedia($type) {
		$accessToken = $this->seachAccessToken();
		if(!$accessToken) {
			return 'error';
		}
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$accessToken&type=$type";
		
	}

	/**
	 * [getMedia 获取多媒体文件]
	 * @method get
	 * @param  [type] $mediaId [description]
	 * @return [type]          [description]
	 */
	public function getMedia($mediaId) {
		$accessToken = $this->seachAccessToken();
		if(!$accessToken) {
			return 'error';
		}
		$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$accessToken&media_id=$mediaId";
		
	}

	/**
	 * [createQRcode 生成带参数的二维码]
	 * @param  string $scene_id  [description]
	 * @param  string $scene_str [description]
	 * @param  string $qrScene   [QR_SCENEÁÙÊ±,QR_LIMIT_SCENEÓÀ¾Ã]
	 * @return [type]            [description]
	 */
	public function createQRcode($scene_id="", $scene_str="", $qrScene="QR_LIMIT_SCENE") {
		$accessToken = $this->seachAccessToken();
		if(!$accessToken) {
			return 'error';
		}
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$accessToken";
		if(!empty($scene_id)) {
			$scene = array(
				"scene_id" => $scene_id,
			);
		} else {
			$scene = array(
				"scene_str" => $scene_str,
			);
		}
		$array = array(
			"action_name" => $qrScene,
			"action_info" => array(
				"scene" => $scene,
			),
		);
		$result = curlPost($url, json_encode($array));
		return $result;
	}

}