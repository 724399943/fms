<?php
namespace Bbs\Controller;
use Think\Controller;
use Plugins\Wechat\WeixinController;
// use Game\Controller\PublicController;
use Common\Library\Pid;
use Plugins\Energy\Energy;
use Plugins\Huanxin\Easemob;
use Plugins\Userlevel\Userlevel;

// 基础控制器
class BaseController extends Controller {
    private $point;
    protected $isMobile = true;
    // protected $wechatAgent = true; // 是否微信客户端访问，默认为true
    protected $limitStar;
    protected $limitStr;
    protected $limit;
    protected $page;
    protected $isWhiteList = false; //是否白名单

    public function __construct() {
        parent::__construct();
    }
    /**
     * [_initialize 初始化]
     * @author kofu <[418382595@qq.com]>
     */
	public function _initialize() {
        $c = M('config')->getField('config_sign, config_value');
        C($c);
        $this->whiteList();
        // $this->is_auth();
        define(IS_AUTH, true);
        // C('DEFAULT_THEME', 'wechat');
        $this->loadWechatInitialize();
	}

    /**
     * [is_interface 判断是否接口]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    boolean       [description]
     */
    public function is_auth() {
        $is_auth = I('request.is_auth');
        $this->$path = I('request.path');
        $state = I('request.state');
        $code = I('request.code');
        if ( ! empty ( $is_auth ) || ! empty( $state ) || ! empty( $code ) ) {
            define(IS_AUTH, true);
        } else {
            define(IS_AUTH, false);
        }
    }

    /**
     * [loadWechatInitialize description]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function loadWechatInitialize() {
        // session_start();
        $online = C('ONLINE');
        if ( !$online ) {
            $userInfo   = M('user')->where(array('id'=> 34))->find();
            // $userInfo   = M('user')->where(array('id'=> 17))->find();
            session('userInfo', $userInfo);
            session('userId', $userInfo['id']);
            $this->isWhiteList = true;
        }
        if ( IS_AUTH && !$this->isWhiteList ) {
            if ( $online ) {
                $userInfo   = session('userInfo');
                // 未登录时
                if ( empty($userInfo) ) {

                    $gUserInfo = $this->weixinLogin();
                    session('wxLoginInfo', $gUserInfo);
                    $user   = M('user');
                    if ( !empty($gUserInfo['openid']) ) {
                        $gUserInfo['subscribe'] = isset($gUserInfo['subscribe']) ? $gUserInfo['subscribe'] : 0;
                        $data  = array(
                            'open_id'           => $gUserInfo['openid'],
                            'nickname'          => $gUserInfo['nickname'],
                            'headimgurl'        => $gUserInfo['headimgurl'],
                            'subscribe'         => $gUserInfo['subscribe'],
                        );
                        $sUserInfo = $user->where(array('open_id' => $gUserInfo['openid']))->find();

                    //     $isOpenBotao = C('IS_OPEN_BOTAO');
                    //     if ( ! $isOpenBotao ) {
                            $isNotHave = empty( $sUserInfo );
                    //     } else {
                    //         $isNotHave = empty( $sUserInfo['user_code'] );
                    //     }
                        if ( $isNotHave ) {
                    //         if ( ! $isOpenBotao ) {
                                $userId = $user->data($data)->add();
                                $sUserInfo = $data;
                                $sUserInfo['id'] = $userId;
                    //             // 注册环信
                    //             $options = imConf();
                    //             $Easemob = new Easemob($options);
                    //             $save = $Easemob->createUser($userId,'pd'.$userId,$data['nickname']);
                    //         } else {
                    //             // 铂涛授权
                    //             $botapLoginUrl = C('BOTAO_LOGIN_URL') . C('webSite') . U('Public/binding');
                    //             header("Location:{$botapLoginUrl}");
                    //             die();
                    //         }
                        } else if ( $sUserInfo['nickname'] == '' || $sUserInfo['open_id'] == '' ) {
                            $updateData = $data;
                        }

                        $sUserInfo['headimgurl'] = $updateData['headimgurl'] = $data['headimgurl'];
                        $userInfo = $sUserInfo;
                    // } else {
                    //     exit(statusCode(array(), 100002));
                    // }
                    // 默认帮其登录
                    session('userInfo', $userInfo);
                    session('userId', $userInfo['id']);
                    $sessionId = session_id();
                    $updateData    = array(
                        'last_login_time' => time(),
                        'session_id'      => $sessionId
                    );
                    $user->where(array('id'=> $userInfo['id']))->data($updateData)->save();

                    // 能量
                    // $energyClass = new Energy();
                    // $energyResult = $energyClass->loadEnergy($userInfo['id']);
                }
            }
        }

        $userId = session('userId');//用户名称

        $controllerName = CONTROLLER_NAME;
        $actionName     = ACTION_NAME;
        $moduleName     = MODULE_NAME;
        $right          = M('controller_power')->where(array('controller_name'=>$controllerName, 'controller_function'=>$actionName, 'controller_module' => $moduleName))->find();
        if( ( count($right) < 1 && !is_numeric($controllerName) ) && !$this->isWhiteList ) {
            die(statusCode(array(), 100001));
        } else {
            // session_set_cookie_params(3600, '/', C('BASE_COOKIE_HOST'), false, true);
            define(NEED_PAGE, $right['need_page']);
            define(PAGE_LIMIT, $right['page_limit']);

            if( $right['need_login'] == '1' ) {
                //用户尚未登录，直接跳到登录界面
                // $isTemp = session('is_temp'); //是否是临时用户
                if( empty($userId) ) {
                    die(statusCode(array(), 100000));
                }
            }
        }
        $this->load_limit();
        // 更新用户的系统消息 签到 
        // if( ! empty($userId) ) {
        //     $this->updateUserInformation($userId, 0);
        // } 
        // else if ( ! $this->isWhiteList ) {
        //     die(statusCode(array(), 100000));
        // }
        }
    }

    /**
     * [updateUserInformation description]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    // public function updateUserInformation($userId, $isTemp) {
        // 更新用户的系统消息 自动取消订单 && 自动确认收货 
        // if (empty($isTemp)) {
            // getMessage($userId); 

            // 检测是否签到
            // $checkIn = session('checkIn');

            // if (empty($checkIn)) {
            //     $publicClass = new Public();
            //     $publicClass->checkUserSign($userId);
            // }
        // }
    // }   

    /**
     * [isWechatAgent 检测是否微信客户端访问]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
     * @return    boolean       [description]
     */
    public function isWechatAgent() {
        $userAgent = addslashes($_SERVER['HTTP_USER_AGENT']);
        // if (strpos($userAgent, 'MicroMessenger') === false && strpos($userAgent, 'Windows Phone') === false) {
        if (strpos($userAgent, 'Mobile') === false) {
            // 非微信浏览器禁止浏览
            // echo "HTTP/1.1 401 Unauthorized";
            return false;
        } else {
            // 微信浏览器，允许访问
            return true;
            // 获取版本号
            // preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $userAgent, $matches);
            // echo '<br>Version:'.$matches[2];
        }
    }

    /**
     * [load_limit 获取分页]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function load_limit() {
        if ( NEED_PAGE ) {
            $page  = I('request.page', 0, 'int');
            $page  = $page < 0 ? 0 : $page;
            $this->limit = PAGE_LIMIT;
            $this->page         = $page;
            $this->limitStar    = $this->limit * $page;
            $this->limitStr     = "LIMIT {$this->limitStar} , {$this->limit}";
        } else {
            $this->limit = 0;
            $this->limitStr = "";
        }
    }

    /**
     * [whiteList 白名单判断]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function whiteList() {
        $whiteList = C('WHITE_LIST');
        if ( in_array( CONTROLLER_NAME, $whiteList['controllerName'] ) || in_array( CONTROLLER_NAME . '-' . ACTION_NAME, $whiteList['controllerName-actionName'] ) ) {
            $this->isWhiteList = true;
        } else {
            $this->isWhiteList = false;
        }
    }

    public function weixinLogin() {
        $weixinAuthType = C('weixinAuthType');
        switch ( $weixinAuthType ) {
            case '2':
                $return = $this->codingLogin();
                break;
            case '3':
                $return = $this->openLogin();
                break;
            default:
                $return = $this->wxLogin();
                break;
        }
        return $return;
    }

    /**
     * [wxLogin 微信自己登陆方法]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function wxLogin() {
        // 无码 跳转
        $code   = I('get.code', '');
        $wx     = new WeixinController();
        if ( empty($code) && !IS_AJAX && !IS_POST ) {
            $wx->authjumpOauth();
        }
        // 错码 跳转
        $OauthCode   = $wx->OauthCode($code);
        if ( !empty($OauthCode['errcode']) ) {
            $wx->authjumpOauth();
        }
        // 得到用户信息
        $gUserInfo = $wx->authGetUserInfo($OauthCode['access_token'], $OauthCode['openid']);
        return $gUserInfo;
    }

    /**
     * [codingLogin 酷顶替代登陆方法]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function codingLogin() {
        $wx     = new WeixinController();
        $openid = I('request.openId');
        if ( empty($openid) ) {
            $wx->authjumpCodingOauth();
        }
        $data['openid'] = $openid;
        $data['id'] = C("codingId");
        $data['key'] = C("codingKey");
        $url = "http://coding.prettymi.com/Open/Api/getUserInfo.html";
        $return = curl($url, $data);
        $gUserInfo = json_decode($return, true);
        return $gUserInfo;
    }

    /**
     * [openLogin 第三方授权替代登陆方法]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function openLogin() {
        return ;
    }
}