<?php
namespace Plugins\AlidayuSMS;
use Think\Controller;

class AlidayuSMS extends Controller {
    private $alidayu_config;
    public function __construct() {
    	parent::__construct();
        $alidayu_config = M('alidayuaccount')->where(array('agent_id'=> $this->agentId))->field('sms_app_key, sms_app_secret, template_code')->find();
        $this->alidayu_config = $alidayu_config;
    }

    /**
     * [sendAlidayuSMS 发送短信]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)    2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $code  [验证码]
     * @param     [type]        $phone [手机号]
     * @param     string        $str   [description]
     * @return    [type]               [description]
     */
    function sendAlidayuSMS($code, $phone, $str="身份验证") {
        $sendData = array(
            'appkey' => $this->alidayu_config['sms_app_key'],
            'secret' => $this->alidayu_config['sms_app_secret'],
            'free_sign_name' => $str,
            'template_code' => $this->alidayu_config['template_code'],
            'param' => json_encode( array('code' => $code, 'product' => C('systemName') . '商城', ) ),
            'phone' => $phone,
        );
        $url = trim(C('webSite'), '/'). "/Core/Api/alidayu/sendSMS.php";
        // $url = "http://100.agent.qisiju.com/Core/Api/alidayu/sendSMS.php";
        $return = curl($url, $sendData);
        $returnData = json_decode($return, TRUE);
        if (isset($returnData['code'])) {
            // file_put_contents('filename', $return);
            return array('err' => 1, 'statusMsg' => $returnData['msg']);
        } else {
            return array('err' => 0);
        }
    }
}