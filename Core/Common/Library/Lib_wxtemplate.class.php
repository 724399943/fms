<?php
namespace Common\Library;
use Think\Controller;
class Lib_wxtemplate extends Controller {

	// public function selectTemplate() {
	// 	$this->lib_access = new \Common\Library\Lib_access();
	// 	$this->Accessmodel = D('Accesstoken');
	// }

	/**
	 * [checkTemplate 检测模板id]
	 * @author [Nic] [317384591@qq.com]
	 * @version  [version]
	 * @DateTime 2015-08-09T11:04:30+0800
	 * @param    [type]                   $appinfoId   [description]
	 * @param    [type]                   $key         [description]
	 * @param    [type]                   $templateNum [description]
	 * @return   [type]                                [description]
	 */
	public function checkTemplate($appinfoId, $key, $templateNum) {
        $data["appinfoId"] = $appinfoId;
        $lib_access = new \Common\Library\Lib_access($data);
        $accessToken = $lib_access->seachAccessToken();
        
		$templateModel = M('template');
        $c = $templateModel->where(array('appinfoId' => $appinfoId))->getField('template_id_short, template_id', true);
        // file_put_contents('config',json_encode($c),FILE_APPEND);
        // file_put_contents('key',$key,FILE_APPEND);
        C($c);
        $templateKey = C($templateNum);
        // file_put_contents('templateKey',json_encode($templateKey),FILE_APPEND);
        if(empty($templateKey)) {
        	$templateData = $this->getTemplateId($accessToken, $templateNum);
        	if($templateData['errcode'] != 0) {
        		return FALSE;
        	}
            $templateModel->data(array('template_id' => $templateData['template_id'], 'key' => $key, 'appinfoId' => $appinfoId, 'template_id_short' => $templateNum))->add();
            // echo $templateModel->getLastSql();
            $return = $templateData['template_id'];
        } else {
        	$return = $templateKey;
        }
        return $return;
	}

	/**
	 * [getTemplateId 获取模板id]
	 * @param  [type] $accessToken [description]
	 * @param  [type] $templateNum [description]
	 * @return [type]              [description]
	 */
	public function getTemplateId($accessToken, $templateNum) {
		$url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=$accessToken";
		$data = array(
			"template_id_short" => $templateNum,
		);
		$jsonStr = json_encode($data);
		$return = curlPost($url, $jsonStr);
		$result = json_decode($return, TRUE);
		return $result;
	}

	/**
	 * [sendTemplate 发送模板信息]
	 * @param  [type] $accessToken  [description]
	 * @param  [type] $templateData [description]
	 * @return [type]               [description]
	 */
	public function sendTemplate($accessToken, $templateData, $isJson=FALSE) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accessToken";
		if(!$isJson) {
			$jsonStr = json_encode($templateData, JSON_UNESCAPED_UNICODE);
		} else {
			$jsonStr = $templateData;
		}
		$return = curlPost($url, $jsonStr);
		$result = json_decode($return, TRUE);
		return $result;
	}

	/**
	 * [sellerTemplate 发送商家类型编码]
	 * @param  [type] $accessToken [description]
	 * @param  [type] $data        [description]
	 * @return [type]              [description]
	 */
	public function sellerTemplate($accessToken, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=$accessToken";
		$jsonStr = json_encode($data);
		$return = curlPost($url, $jsonStr);
		$result = json_decode($return);
		return $result;
	}

	/**
	 * [getTemplateList 获取模板列表]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)          2016          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $accessToken [description]
	 * @return    [type]                     [description]
	 */
	public function getTemplateList($accessToken) {
		$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=$accessToken";
		$result = curlGet($url);
		$result = json_decode($result, true);
		return $result['template_list'];
	}

	/**
	 * [getData 构造文本内容]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)                2016          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $template_id_short [description]
	 * @param     [type]        $dataArr           [description]
	 * @param     [type]        $textcolor         [description]
	 * @return    [type]                           [description]
	 */
	public function getData($template_id_short, $dataArr, $textcolor){
		$tempsArr = $this->templates();

		$data = $tempsArr["$template_id_short"]['vars'];
		$data = array_flip($data);
		foreach($dataArr as $key => $val){
			if(in_array($key, array_flip($data))){
				$returnData[$key]['value'] = $val;
				$returnData[$key]['color'] = $textcolor;
			}
		}
		// dump($returnData);
		// $returnData = json_encode($returnData, JSON_UNESCAPED_UNICODE);
		return $returnData; 
	}

	/**
	 * [createAndSendTemplateData 构造模板并发送]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)                2016          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $template_id_short [description]
	 * @param     [type]        $dataArr           [description]
	 * @param     [type]        $accessToken       [description]
	 * @return    [type]                           [description]
	 */
	public function createAndSendTemplateData($template_id_short, $dataArr, $accessToken = '') {
		/* //example
			$template_id_short = 'TM00130';
			$dataArr = array('href' => 'http://www.baidu.com' , 'wecha_id' => 'oLA6VjgLrB3qPspOBRMYZZJpVkGQ' , 'first' => '您好，您已成功预约看房。' , 'apartmentName' => '丽景华庭' , 'roomNumber' => 'A栋534' , 'address' => '广州市微信路88号', 'time' => '2013年10月30日 15:32', 'remark' => '请您准时到达看房。');
		*/
		$template = M('template');
		$templateInfo = $template->where(array('template_id_short'=>"{$template_id_short}"))->find();

		// 构造文本内容 start
		$data = $this->getData($template_id_short, $dataArr, $templateInfo["textcolor"]);
		
		// 构造文本内容 end
		// 接受者open_id
		$templateData['touser'] 		= $dataArr["touser"];                   	
		// 模板ID
		$templateData['template_id'] 	= $templateInfo["template_id"];				
		// 跳转地址
		$templateData['url'] 			= $dataArr["url"];							
		// 标题颜色
		$templateData['topcolor'] 		= $templateInfo["topcolor"];				
		// 发送内容
		$templateData['data'] 			= $data;									
		// $templateData = '{"touser":"'.$dataArr["wecha_id"].'","template_id":"'.$templateInfo["tempid"].'","url":"'.$dataArr["href"].'","topcolor":"'.$templateInfo["topcolor"].'","data":'.$data.'}';
		// dump($templateData);
		// dump($templateData);
		// dump($accessToken);die;
		$result = $this->sendTemplate($accessToken, $templateData, false);
		return $result;
	}

	/**
	 * [templates 模板]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function templates(){
		return array(
		'OPENTM207873448' =>
			array(
				'title'=>'用户提问通知',
				'vars'=>array('first','keyword1','keyword2','keyword3','remark'),
				'content'=>	
'{{first.DATA}} 

订单金额：{{keyword1.DATA}}
订单编号：{{keyword2.DATA}}
订单详情：{{keyword3.DATA}}
 {{remark.DATA}}'
				),
			'OPENTM207498127' =>
			array(
				'title'=>'收到回复通知',
				'vars'=>array('first','keyword1','keyword2','keyword3','keyword4','remark'),
				'content'=>	
'{{first.DATA}} 

订单编号：{{keyword1.DATA}}
商品名称：{{keyword2.DATA}}
订单金额：{{keyword3.DATA}}
完成时间：{{keyword4.DATA}}
 {{remark.DATA}}'
				),
			'OPENTM408170479' =>
			array(
				'title'=>'结算通知',
				'vars'=>array('first','keyword1','keyword2','remark'),
				'content'=>	
'{{first.DATA}} 

订单编号：{{keyword1.DATA}}
结算金额：{{keyword2.DATA}}
 {{remark.DATA}}'
				),
			'OPENTM202037583' =>
			array(
				'title'=>'即将过期提醒',
				'vars'=>array('first','keyword1','keyword2','remark'),
				'content'=>	
'{{first.DATA}} 

过期事项：{{keyword1.DATA}}
即将过期时间：{{keyword2.DATA}}
 {{remark.DATA}}'
				),

			'OPENTM400509826' =>
			array(
				'title'=>'退款通知',
				'vars'=>array('first','keyword1','keyword2','remark'),
				'content'=>	
'{{first.DATA}} 

退款金额：{{keyword1.DATA}}
退款时间：{{keyword2.DATA}}
 {{remark.DATA}}'
				),


		);
	}
}