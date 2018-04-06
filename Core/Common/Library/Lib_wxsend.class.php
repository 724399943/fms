<?php
namespace Common\Library;
class Lib_wxsend {
	/**
	 * [postTextMsg 发送文本消息]
	 * @param  [type] $accessToken [调用接口凭证]
	 * @param  [type] $openId      [普通用户openid]
	 * @param  [type] $content     [文本消息内容]
	 * @return [type]              [description]
	 */
	public function postTextMsg($accessToken, $openId, $content) {
		$data["touser"] = $openId;
		$data["msgtype"] = "text";
		$data["text"]["content"] = $content;
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

	/**
	 * [postImageMsg 发送图片消息]
	 * @param  [type] $accessToken [调用接口凭证]
	 * @param  [type] $openId      [普通用户openid]
	 * @param  [type] $mediaId     [发送的图片的媒体ID]
	 * @return [type]              [description]
	 */
	public function postImageMsg($accessToken, $openId, $mediaId) {
		$data["touser"] = $openId;
		$data["msgtype"] = "image";
		$data["image"]["media_id"] = $mediaId;
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

	/**
	 * [postVoiceMsg 发送语音消息]
	 * @param  [type] $accessToken [调用接口凭证]
	 * @param  [type] $openId      [普通用户openid]
	 * @param  [type] $mediaId     [发送的语音的媒体ID]
	 * @return [type]              [description]
	 */
	public function postVoiceMsg($accessToken, $openId, $mediaId) {
		$data["touser"] = $openId;
		$data["msgtype"] = "voice";
		$data["voice"]["media_id"] = $mediaId;
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

	/**
	 * [postVideoMsg 发送视频消息]
	 * @param  [type] $accessToken  [调用接口凭证]
	 * @param  [type] $openId       [普通用户openid]
	 * @param  [type] $mediaId      [发送的视频的媒体ID]
	 * @param  [type] $thumbMediaId [缩略图的媒体ID]
	 * @param  string $title        [视频消息的标题]
	 * @param  string $description  [视频消息的描述]
	 * @return [type]               [description]
	 */
	public function postVideoMsg($accessToken, $openId, $mediaId, $thumbMediaId, $title="", $description="") {
		$data["touser"] = $openId;
		$data["msgtype"] = "video";
		$data["video"]["media_id"] = $mediaId; //发送的视频的媒体ID
		$data["video"]["thumb_media_id"] = $thumbMediaId; //缩略图的媒体ID
		$data["video"]["title"] = $title; //视频消息的标题
		$data["video"]["description"] = $description; //视频消息的描述
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

	/**
	 * [postMusicMsg 发送音乐消息]
	 * @param  [type] $accessToken    [调用接口凭证]
	 * @param  [type] $openId         [普通用户openid]
	 * @param  [type] $musicurl       [音乐标题]
	 * @param  [type] $hqmusicurl     [音乐描述]
	 * @param  [type] $thumb_media_id [音乐链接]
	 * @param  string $title          [高品质音乐链接，wifi环境优先使用该链接播放音乐]
	 * @param  string $description    [缩略图的媒体ID]
	 * @return [type]                 [description]
	 */
	public function postMusicMsg($accessToken, $openId, $musicurl, $hqmusicurl, $thumb_media_id, $title="", $description="") {
		$data["touser"] = $openId;
		$data["msgtype"] = "music";
		$data["music"]["title"] = $title;//音乐标题
		$data["music"]["description"] = $description;//音乐描述
		$data["music"]["musicurl"] = $musicurl;//音乐链接
		$data["music"]["hqmusicurl"] = $hqmusicurl;//高品质音乐链接，wifi环境优先使用该链接播放音乐
		$data["music"]["thumb_media_id"] = $thumb_media_id;//缩略图的媒体ID
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

	/**
	 * [postNewsMsg 发送图文消息]
	 * @param  [type] $accessToken  [调用接口凭证]
	 * @param  [type] $openId       [普通用户openid]
	 * @param  [type] $array        [description]
		* @param  [type] $title        [标题]
		* @param  [type] $description  [描述]
		* @param  [type] $url          [点击后跳转的链接]
		* @param  [type] $picurl       [图文消息的图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80]
	 * @return [type]               [description]
	 */
	public function postNewsMsg($accessToken, $openId, $array) {
		$data["touser"] = $openId;
		$data["msgtype"] = "news";
		$i = 0;
		foreach ($array as $key => $value) {
			$tempArr = array();
			$tempArr["title"] = $value["title"];
			$tempArr["description"] = $value["description"];
			$tempArr["url"] = $value["url"];
			$tempArr["picurl"] = $value["picurl"];
			$data["news"]["articles"][] = $tempArr;
			$i++;
			if($i = 10) {
				break;
			}
		}
		$jsonStr = urldecode(json_encode(url_encode($data)));
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accessToken";
		return curlPost($url, $jsonStr);
	}

}