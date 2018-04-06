<?php
namespace Common\Library;
class Lib_wxoauth {

	/**
	 * [getCode 用户同意授权，获取code]
	 * @param  [type] $appid        [description]
	 * @param  [type] $redirect_uri [description]
	 * @param  [string] $state        [description]
	 * @param  [string] $scope        [snsapi_base/snsapi_userinfo]
	 * 1、以snsapi_base为scope发起的网页授权，是用来获取进入页面的用户的openid的，并且是静默授权并自动跳转到回调页的。用户感知的就是直接进入了回调页（往往是业务页面）
	 * 2、以snsapi_userinfo为scope发起的网页授权，是用来获取用户的基本信息的。但这种授权需要用户手动同意，并且由于用户同意过，所以无须关注，就可在授权后获取该用户的基本信息。
	 * 3、用户管理类接口中的“获取用户基本信息接口”，是在用户和公众号产生消息交互或关注后事件推送后，才能根据用户OpenID来获取用户基本信息。这个接口，包括其他微信接口，都是需要该用户（即openid）关注了公众号后，才能调用成功的。
	 * @return [type]               [description]
	 */
	public function getCode($appid, $redirect_uri, $state="", $scope="snsapi_base") {
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=$scope&state=$state#wechat_redirect";
		// $returnData = array(
		// 	'url' => $url,
		// 	'state' => $state,
		// );
		return $url;
	}

	/**
	 * [codeAccessToken 通过code换取网页授权access_token]
	 * @param  [type] $appid     [description]
	 * @param  [type] $appsecret [description]
	 * @param  [type] $code      [description]
	 * @return [type]            [description]
	 */
	public function codeAccessToken($appid, $appsecret, $code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
		//{"access_token":"ACCESS_TOKEN","expires_in":7200,"refresh_token":"REFRESH_TOKEN","openid":"OPENID","scope":"SCOPE"}
		$jsonStr = curlGet($url);
		$result = json_decode($jsonStr, TRUE);
		return $result;
	}

	public function userinfo($accessToken, $openId) {
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$accessToken}&openid={$openId}&lang=zh_CN";
		$jsonStr = curlGet($url);
		$result = json_decode($jsonStr, TRUE);
		return $result;
	}
}