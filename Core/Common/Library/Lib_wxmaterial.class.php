<?php
namespace Common\Library;
use Think\Controller;
use Think\GetErrorMsg;
use Common\Library\Lib_access;
class Lib_wxmaterial extends Controller {
	private $lib_access;
	private $access_token;
	private $getErrorMsg;
	public function __construct() {
		parent::__construct();
		session_start();
		$agentId = session('agentId');
		// $agentId = empty($agentId) ? 1 : $agentId;
		$agentId = 100;
		$this->getErrorMsg      = new GetErrorMsg();
		$this->lib_access 		= new Lib_access(array('agentId'=> $agentId));
		$this->access_token 	= $this->lib_access->seachAccessToken();
	}

	/**
	 * [createImageMeterial 添加图片缩略图永久素材]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function createImageMeterial($file_info, $type = 'image') {
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token.'&type='.$type;

		// $timeout = 5;
		$real_path= "{$_SERVER['DOCUMENT_ROOT']}{$file_info['filename']}";
		$data= array("media"=>"@{$real_path}",'form-data'=>$file_info);
		$result = curl($url, $data);
		$result = json_decode($result, true);
		if (!empty($result['errcode'])){
            $errmsg = $this->getErrorMsg->wx_error_msg($result['errcode']);
            $result['errmsg'] = $errmsg;
            $result['status'] = '1';
        }else {
        	$result['status'] = '0';
            return $result;
        }
	}

	/**
	 * [deleteMeterial 删除永久素材]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)       2016          Xcrozz (http://www.xcrozz.com)
	 * @param     array         $media_id [description]
	 * @return    [type]                  [description]
	 */
	public function deleteMeterial($media_id = array()) {
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$access_token;
		foreach ($media_id as $media_key => $media_value) {
			$data['media_id'] = $media_value;
			$result = curl($url, $data);
			$result = json_decode($result, true);
			if (!empty($result['errcode'])) {
				$errmsg = $this->getErrorMsg->wx_error_msg($result['errcode']);
				$result['status'] 	= '1';
				$result['errmsg']	= $errmsg;
				break;
			} else {
				$result['status'] = '0';
			}
		}
		return $result;
	}

	/**
	 * [createNewsMeterial 添加图文永久素材]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2016          Xcrozz (http://www.xcrozz.com)
	 * @param     array         $news_meterial 	[图文信息]
	 * @return    [type]                      	[description]
	 */
	public function createNewsMeterial($news_meterial = array()) {
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$access_token;
		foreach ($news_meterial as $news_key => $news_value) {
			
		}
	}
}
