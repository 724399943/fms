<?php

namespace Home\Controller;

use Think\Controller;
use Plugins\WechatOpen\OauthController;
use Plugins\WechatOpen\AccessController;
use Authorize\Controller\JssdkController;
use Common\Library\Pid;

// 基础控制器
class BaseController extends Controller
{
    use SeoController;

    private $isMobile = false;

    private $agentId; // 代理商ID

    private $wechatAgent = false; // 是否微信客户端访问，默认为false

    protected $limitStar;

    protected $limitStr;

    protected $page;

    private $isWhiteList = false; //是否白名单

    private $isLoginWhiteList = false; //是否登录白名单

    private $oauthHelper;

    protected $template;

    public function __construct()
    {
        parent::__construct();
        $c = M('config')->getField('config_sign, config_value');
        C($c);
        $this->whiteList();
        $this->wechatAgent = $this->isWechatAgent();

        define(NEED_PAGE, 1);
        define(PAGE_LIMIT, 10);

        if ($this->wechatAgent === true) {
            C('DEFAULT_THEME', 'Wap');
            $this->template = C('wapTemplet');
            $this->loadWap();
        } else {
            $this->template = C('templet');
            $this->loadWebInitialize();
        }

    }

    public function loadWap()
    {
        session_start();
        $online = C('ONLINE');
        if ($online) {
//            $userInfo = $userModel->getUserInfo(['id' => 2]);
//            session('userInfo', $userInfo);
//            session('userId', $userInfo['id']);
//            session('is_temp', 0);
        } else {
            $openWap = C('openWap');
            if(!$openWap){
                exit('Mobile version is unopened');
            }else{
               $wapName = C('wapName');

               $result = [
                   'wapName' => $wapName
               ];
               $this->load_limit();
               $this->assign($result); 
            }

            
        }
    }

    public function loadWebInitialize(){
        session_start();
        $online = C('ONLINE');
        if ($online) {
//            $userInfo = $userModel->getUserInfo(['id' => 2]);
//            session('userInfo', $userInfo);
//            session('userId', $userInfo['id']);
//            session('is_temp', 0);
        } else {
            $openWap = C('openWeb');
            if(!$openWap){
                exit('PC version is unopened');
            }else{
               $wapName = C('webName');

               $result = [
                   'webName' => $webName
               ];
               $this->load_limit();
               $this->assign($result); 
            }

            // $wapName = C('wapName');

            // $result = [
            //     'wapName' => $wapName
            // ];
            // $this->assign($result);
        }
    }

    /**
     * isWechatAgent  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return bool
     */
    public function isWechatAgent()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser = 0;
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if ($mobile_browser > 0)
            return true;
        else
            return false;
    }

    public function load_limit()
    {
        if (NEED_PAGE) {
            $page = I('request.page', 1, 'int') - 1;
            $page = $page < 0 ? 0 : $page;
            $limit = PAGE_LIMIT;
            $this->page = $page;
            $this->limitStar = $limit * $page;
            $this->limitStr = "LIMIT {$this->limitStar} , {$limit}";
        } else {
            $limit = PAGE_LIMIT;
            $this->limitStr = "";
        }
    }

    /**
     * [whiteList 白名单判断]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function whiteList()
    {
        $whiteList = C('WHITE_LIST');
        if (in_array(CONTROLLER_NAME, $whiteList['controllerName']) || in_array(CONTROLLER_NAME . '-' . ACTION_NAME, $whiteList['controllerName-actionName'])) {
            $this->isWhiteList = true;
        } else {
            $this->isWhiteList = false;
        }
    }

    public function getSignature($timestamp, $nonce_str)
    {
        $userInfo = session('userInfo');
        $api_ticket = $this->accessHelper->getApiTicket();
        $timestamp = (string)$timestamp;
        $nonce_str = random_string();
        $card_id = C('WX_CARD_ID');
        $data = array(
            'api_ticket' => $api_ticket,
            'timestamp' => $timestamp,
            'nonce_str' => $nonce_str,
        );
        if (!empty($userInfo['open_id'])) {
            $data['openid'] = $userInfo['open_id'];
        }
        if (!empty($card_id)) {
            $data['card_id'] = $card_id;
        }
        if (!empty($code)) {
            $data['code'] = $code;
        }
        $temp = $data;
        sort($temp);
        $string = implode('', $temp);
        asort($data);
        $data['signature'] = sha1($string);
        unset($data['api_ticket']);
        return $data;
        // $this->assign('signature', json_encode($data));
    }

    /**
     * [loginWhiteList 登录白名单判断]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function loginWhiteList()
    {
        $whiteList = C('LOGIN_WHITE_LIST');
        if (in_array(CONTROLLER_NAME, $whiteList['controllerName']) || in_array(CONTROLLER_NAME . '-' . ACTION_NAME, $whiteList['controllerName-actionName'])) {
            $this->isLoginWhiteList = true;
        } else {
            $this->isLoginWhiteList = false;
        }
    }

    public function panduan($openid)
    {
        $access_token = $this->accessHelper->getAuthorizerAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN ";
        $returnData = curl($url, '', 'get');
        $returnArr = json_decode($returnData, true);
        return $returnArr;
    }
}