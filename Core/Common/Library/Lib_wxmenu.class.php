<?php
namespace Common\Library;
class Lib_wxmenu {
	/*
	 * 1、click：点击推事件
	 * 用户点击click类型按钮后，微信服务器会通过消息接口推送消息类型为event	的结构给开发者（参考消息接口指南），并且带上按钮中开发者填写的key值，开发者可以通过自定义的key值与用户进行交互；
	 * 2、view：跳转URL
	 * 用户点击view类型按钮后，微信客户端将会打开开发者在按钮中填写的网页URL，可与网页授权获取用户基本信息接口结合，获得用户基本信息。
	 * 3、scancode_push：扫码推事件
	 * 用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后显示扫描结果（如果是URL，将进入URL），且会将扫码的结果传给开发者，开发者可以下发消息。
	 * 4、scancode_waitmsg：扫码推事件且弹出“消息接收中”提示框
	 * 用户点击按钮后，微信客户端将调起扫一扫工具，完成扫码操作后，将扫码的结果传给开发者，同时收起扫一扫工具，然后弹出“消息接收中”提示框，随后可能会收到开发者下发的消息。
	 * 5、pic_sysphoto：弹出系统拍照发图
	 * 用户点击按钮后，微信客户端将调起系统相机，完成拍照操作后，会将拍摄的相片发送给开发者，并推送事件给开发者，同时收起系统相机，随后可能会收到开发者下发的消息。
	 * 6、pic_photo_or_album：弹出拍照或者相册发图
	 * 用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”。用户选择后即走其他两种流程。
	 * 7、pic_weixin：弹出微信相册发图器
	 * 用户点击按钮后，微信客户端将调起微信相册，完成选择操作后，将选择的相片发送给开发者的服务器，并推送事件给开发者，同时收起相册，随后可能会收到开发者下发的消息。
	 * 8、location_select：弹出地理位置选择器
	 * 用户点击按钮后，微信客户端将调起地理位置选择工具，完成选择操作后，将选择的地理位置发送给开发者的服务器，同时收起位置选择工具，随后可能会收到开发者下发的消息。
	 */
	/**
	 * [postMenu 创建自定义菜单]
	 * @param  [type] $accessToken [description]
	 * @param  [type] $data        [description]
	 * @return [type]              [description]
	 */
	public function postMenu($accessToken, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
		$result = curlPost($url, $data);
		return $result;
	}

	/**
	 * [getMenu 获取自定义菜单]
	 * @param  [type] $accessToken [description]
	 * @return [type]              [description]
	 */
	public function getMenu($accessToken) {
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$accessToken";
		$result = curlGet($url);
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
