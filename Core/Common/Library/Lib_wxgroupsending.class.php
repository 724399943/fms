<?php
namespace Common\Library;
use Think\Controller;
use Common\Library\Lib_access;
class Lib_wxgroupsending extends Controller {
	private $lib_access;
	private $access_token;
	public function __construct() {
		parent::__construct();
		session_start();
		$agentId = session('agentId');
		$agentId = empty($agentId) ? 1 : $agentId;
		$this->lib_access = new Lib_access(array('agentId'=> $agentId));
		$this->access_token = $this->lib_access->seachAccessToken();
	}

	
	public function createAndsendGroupMsg($data = array(),$type='text') {
		$access_token = $this->access_token;
		// $this->lib_access = $this->lib_access->seachAccessToken();
		if (!empty($access_token)) {
			// dump($access_token);die;
			$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.$access_token;
			switch ($type) {
				case 'text':
						$sendData = array(
				            'filter' => array(),
				            'text' => array(
				                'content' => $data['content'],
				                ),
				            'msgtype'=> 'text',
				            );
					break;
				case 'mpnews':
						$media = $this->sendUploadNews($data);
						if ($media === false) {
							$this->error('上传失败');die;
						} else {
							if ($media['type'] == 'news' && $media['type']) {
								$data['media_id'] = $media['media_id'];
							} else {
								// return false;
								$this->error('错误编码：'.$json['errcode'].'——'.$json['errmsg']);die;
							}
						}
						$sendData = array(
							'filter' => array(),
							'mpnews' => array(
								'media_id'=>$data['media_id'],
								),
							'msgtype'=> 'mpnews',
							);
					break;

			}
			if (!empty($data['tag_id'])) {
                $sendData['filter']['is_to_all'] = false; 
                $sendData['filter']['tag_id'] = (int) $data['tag_id']; 
            } else {
                $sendData['filter']['is_to_all'] = 'true';
            }
			// $sendData = urldecode(json_encode(url_encode($sendData),true));
			// dump($sendData);die;
			$sendData = json_encode($sendData);
			// $sendData = json_encode($sendData,1);
			// dump($sendData);die;
			// exit();
            $result = curlPost($url,$sendData);
            // dump($result);die;
            $result = json_decode($result,1);
		} else {
			// $result = false;
			$this->error('获取access_token发生错误');die;
		}
		return $result;
	}
	/**
	 * [sendUploadNews 上传图文消息素材]
	 * @author wulong <1191540273@qq.com>
	 * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function sendUploadNews ($data = array()) {
		$access_token = $this->access_token;
		if (!empty($access_token)) {
			$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token='.$access_token;
			$jsonStr = array(
				'articles' =>array(),
				);
			// dump($data);die;
			foreach ($data['newsDetail'] as $key => $value) {
				if (is_array($value)) {
					$json = $this->getOnceMediaId($value['thumb_media_id'],'thumb');
					if ($json === false) {
						$this->error('上传失败');die;
					} else {
						if ($json['type'] && $json['type'] == 'thumb') {
							$value['thumb_media_id'] = $json['thumb_media_id'];
						} else {
							$this->error('错误编码：'.$json['errcode'].'——'.$json['errmsg']);die;
							// return false;
						}
					}
					$jsonStr['articles'][] = array(
						'thumb_media_id' => $value['thumb_media_id'],
						'author' => $value['author'],
						'title' => $value['title'],
						'content_source_url' => $value['content_source_url'],
						'content' => $value['content'],
						'digest' => $value['digest'],
						'show_cover_pic' => $value['show_cover_pic'],
						);
				}
			}
			$jsonStr = urldecode(json_encode(url_encode($jsonStr),true));
			$result = curlPost($url,$jsonStr);
			// dump($result);
	        $result = json_decode($result,1);
	        // dump($result);
	        return $result;
        } else {
			$this->error('获取access_token发生错误');die;
		}
	}
	/**
	 * [getOnceMediaId 新增临时素材]
	 * @author wulong <1191540273@qq.com>
	 * @copyright Copyright (c)           2015          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $this->lib_access [description]
	 * @param     string        $type         [description]
	 * @return    [type]                      [description]
	 */
	public function getOnceMediaId($media,$type="thumb") {
		$access_token = $this->access_token;
		if (!empty($access_token)) {
			$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
			$media = $_SERVER['DOCUMENT_ROOT'].$media;
			$data = array('media'=>'@'.$media);
			$result = curlPost1($url,$data);
			// dump($result);die;
			$result = json_decode($result,1);
			unlink($media);
			return $result;
		} else {
			$this->error('获取access_token发生错误');die;
		}
	}
	/**
	 * [getSendImgUrl 上传图片获取url]
	 * @author wulong <1191540273@qq.com>
	 * @copyright Copyright (c)           2015          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $this->lib_access [description]
	 * @param     [type]        $image        [description]
	 * @return    [type]                      [description]
	 */
	public function getSendImgUrl($image){
		$access_token = $this->access_token;
		if (!empty($access_token)) {
			$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token;
			$image = $_SERVER['DOCUMENT_ROOT'].$image;
			$data = array('media'=>'@'.$image);
			$result = curlPost1($url,$data);
			$result = json_decode($result,1);
			unlink($image);
			return $result;
		} else {
			$this->error('获取access_token发生错误');die;
		}
		// dump($result);die;
		// if ($result['url'] && $result['url'] != '') {
		// 	return $result;
		// } else {
		// 	// $this->error('上传错误！');die;
		// 	return false;
		// }

	}
	/**
	 * [getUserTags 获取微信用户标签]
	 * @author wulong <1191540273@qq.com>
	 * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getUserTags() {
		$access_token = $this->access_token;
		// $this->lib_access = $this->lib_access->seachAccessToken();
		if (!empty($access_token)) {
			if (!empty($this->lib_access)) {
				$url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
		        $result = curlGet($url);
		        $result = json_decode($result,1);
			} else {
				$result = false;
			}
			return $result;
		} else {
			$this->error('获取access_token发生错误');die;
		}

	}

}
