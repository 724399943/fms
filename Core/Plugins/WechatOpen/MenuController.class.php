<?php
namespace Plugins\WechatOpen;
use Think\Controller;
class MenuController extends Controller {

	private $appSecret;
	private $appID;

	public function __construct($data=array()) {
		parent::__construct();
		// if ( !empty( $data ) ) {
		// 	$this->appSecret = $data['appSecret'];
		// 	$this->appID = $data['appID'];
		// 	$this->wx_appid = $data['wx_appid'];
		// } else {
	 //        $this->appSecret = C('appSecret');
		// 	$this->appID = C('appID');
		// 	$this->wx_appid = C('wx_appid');
		// }
	}

	/**
	 * [getMenu 获取现有菜单]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     string        $accessToken [description]
	 * @return    [type]                     [description]
	 */
	public function getMenu($accessToken="") {
		if ( empty( $accessToken ) ) {
			$lib_access = new \Plugins\WechatOpen\AccessController();
			$accessToken = $lib_access->getAuthorizerAccessToken();
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$accessToken}";

		$jsonStr = curlGet($url);
		// file_put_contents('getMenu', $jsonStr);
		$result = json_decode($jsonStr, TRUE);
		return $result;
	}

	/**
	 * [setMenu 获取现有菜单]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     string        $accessToken [description]
	 * @return    [type]                     [description]
	 */
	public function setMenu($accessToken="", $data=array()) {
		if ( empty( $accessToken ) ) {
			$lib_access = new \Plugins\WechatOpen\AccessController();
			$accessToken = $lib_access->getAuthorizerAccessToken();
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$accessToken}";

		$jsonStr = curlPost($url, $data);
		// file_put_contents('getMenu', $jsonStr);
		$result = json_decode($jsonStr, TRUE);
		return $result;
	}

	/**
	 * [delMenu 删除自定义菜单]
	 * @param  [type] $accessToken [description]
	 * @return [type]              [description]
	 */
	public function delMenu($accessToken) {
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$accessToken";
		$result = curlGet($url);
		return $result;
	}

	/**
	 * [assemble 执行数组处理]
	 * @param  [type] $array [description]
	 * @return [type]        [description]
	 */
	public function assemble($array) {
		$i = 0;
		foreach ($array as $key => $value) {
			if(isset($value["child"])) {
				$value['sub_button'] = $value['child'];
				unset($value['child']);
				$button["button"][]  = $value;
				// $button["button"][] = array(
				// 	"name" => $value["name"],
				// 	"sub_button" => $value["child"],
				// );
			} else {
				$button["button"][] = $value;
			}
			$i++;
			if($i >= 3) break;
		}
		arrayRecursive($button, 'urlencode');
		$jsonStr = json_encode($button);
		$result = urldecode($jsonStr);
		return $result;
	}

	/**
	 * [click 点击推事件]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function click($name, $key) {
		$click = array(
			"type" => "click",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [view 跳转URL]
	 * @param  [type] $name [description]
	 * @param  [type] $url  [description]
	 * @return [type]       [description]
	 */
	public function view($name, $url) {
		$click = array(
			"type" => "view",
			"name" => $name,
			"url" => $url,
		);
		return $click;
	}
	
	/**
	 * [scancode_push 扫码推事件]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function scancode_push($name, $key) {
		$click = array(
			"type" => "scancode_push",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [scancode_waitmsg 扫码推事件且弹出“消息接收中”提示框]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function scancode_waitmsg($name, $key) {
		$click = array(
			"type" => "scancode_waitmsg",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [pic_sysphoto 弹出系统拍照发图]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function pic_sysphoto($name, $key) {
		$click = array(
			"type" => "pic_sysphoto",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [pic_photo_or_album 弹出拍照或者相册发图]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function pic_photo_or_album($name, $key) {
		$click = array(
			"type" => "pic_photo_or_album",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [pic_weixin 弹出微信相册发图器]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function pic_weixin($name, $key) {
		$click = array(
			"type" => "pic_weixin",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}

	/**
	 * [location_select 弹出地理位置选择器]
	 * @param  [type] $name [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function location_select($name, $key) {
		$click = array(
			"type" => "location_select",
			"name" => $name,
			"key" => $key,
		);
		return $click;
	}
}