<?php
namespace Plugins\TemplateMsg;
use Think\Controller;
//发送模板消息
class TemplateMsgController extends Controller{

	private $access_token;
	private $template_id_short;	//模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
	private $industry_id1;		//公众号模板消息所属行业编号
	private $industry_id2;		//公众号模板消息所属行业编号
	private $username;			//用户名称
	private $open_id;			//用户open_id
	private $template_id;		//模板id
	private $templateData;		//发送消息的消息体
	private $dbPrefix;

	public function __construct($data = array()){
		parent::__construct();

		$this->access_token = empty($data['access_token']) ? '' : $data['access_token'];
		$this->template_id_short = empty($data['template_id_short']) ? 'OPENTM406785381' : $data['template_id_short'];
		$this->industry_id1 = empty($data['industry_id1']) ? '1' : $data['industry_id1'];
		$this->industry_id2 = empty($data['industry_id2']) ? '4' : $data['industry_id2'];
		$this->username = empty($data['username']) ? '' : $data['username'];
		$this->open_id = empty($data['open_id']) ? '' : $data['open_id'];
		$this->templateData = empty($data['templateData']) ? '' : $data['templateData'];

		$this->dbPrefix = C('DATEBASE_PREFIX');
		$this->template_id = $this->getTemplateId();
	}
	/**
     * [getTemplateId 获得模板id]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)                2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $template_id_short [模板库中模板的编号 有“TM**”和“OPENTMTM**”等形式]
     * @param     string        $industry_id1      [公众号模板消息所属行业编号]
     * @param     string        $industry_id2      [公众号模板消息所属行业编号]
     * @return    [string]                           [模板id]
     */
	private function getTemplateId(){
		// $access_token = $this->lib_access->getAuthorizerAccessToken();
		//查找数据库中是否存在该模板
		$templateModel = M('template',$this->dbPrefix);
		$templateData = $templateModel->where(array('template_id_short'=>$this->template_id_short))->find();
		if(!empty($templateData)){
		    //存在该模板
		    return $templateData['template_id'];
		}else{
		    //不存在该模板,请求生成新模板
		    //1.设置行业信息
		    $industry = array(
		        'industry_id1' => $this->industry_id1,
		        'industry_id2' => $this->industry_id2,
		    );
		    $industryUrl = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=".$this->access_token;
		    $industryResult = curl($industryUrl,json_encode($industry));
		    $industryResult = json_decode( $industryResult,true );
		    if( $industryResult){
		        //2.获取模板id
		        $template_id_short = array('template_id_short'=>$this->template_id_short);
		        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=".$this->access_token;
		        $tempResult = curl($url,json_encode($template_id_short));
		        $tempResult2 = json_decode($tempResult,true);
		        if( $tempResult2['errcode'] == '0'){
		            //保存在数据库
		            $addData = array(
		                'template_id' => $tempResult2['template_id'],
		                'template_id_short' => $this->template_id_short,
		                'industry_id1' => $this->industry_id1,
		                'industry_id2' => $this->industry_id2,
		                'add_time' => time(),
		            );
		            $i = $templateModel->data($addData)->add();
		            if($i !== false){
		                return $tempResult2['template_id'];
		            }
		            else
		                return false;
		        }
		        else
		            return false;
		        
		    }else
		        return false;
		}
	}
	/**
	 * [getTemplateData 获得消息体]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $username    [用户名]
	 * @param     [type]        $open_id     [用户open_id]
	 * @param     [type]        $template_id [模板id]
	 * @return    [type]                     [发送模板消息的消息内容]
	 */
	private function getTemplateData(){
		if(!$this->templateData){

	        $this->templateData = array(
	            'first' => array(
	                'value' => '亲爱的'.$this->username.'，赠人玫瑰。手有余香，您已成功捐赠1.00元，感谢有你！',
	                'color' => '#000000'
	            ),
	            'keyword1' => array(
	                'value' => '用艺术点亮生命',
	                'color' => '#000000'
	            ),
	            'keyword2' => array(
	                'value' => date('Y-m-d H:i:s'),
	                'color' => '#000000'
	            ),
	            'keyword3' => array(
	                'value' => '',
	                'color' => '#000000'
	            ),
	            'remark' => array(
	                'value' => '我们会实时公布善款执行明细，敬请关注',
	                'color' => '#000000'
	            )
	        );
		}
        $arr = array(
            'touser'      => $this->open_id,
            'template_id' => $this->template_id,
            'data'        => $this->templateData,
        );
        return $arr;
    }
    /**
     * [sendMsg 发送模板消息]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [模板消息返回结果(数组)]
     */
    public function sendMsg(){
        // //获得模板id
        // $template_id = $this->getTemplateId();
        //获得模板消息体
        // $userInfo = session('userInfo');
        
        if(!$this->template_id){
            $result['errmsg'] = '模板id不存在';
        }
        $template = $this->getTemplateData($this->username,$this->open_id,$this->template_id);
        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->access_token;
        $result = curl($url,urldecode($json_template));
        $result = json_decode($result,true);
        return $result;
    }

}