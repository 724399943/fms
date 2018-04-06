<?php
namespace Authorize\Controller;
use Think\Controller;
use Authorize\Controller\OpenController;
// use Plugins\Wechat\WeixinController;
use Authorize\Controller\JssdkController;
// use Plugins\Detection\Detection;
// use Plugins\Point\Point;
use Common\Library\Pid;

// 基础控制器
class BaseController extends Controller {
    // private $point;
    protected $isMobile = false;
    private $goodsCache;
    // protected $agentId; // 代理商ID
    protected $wechatAgent = false; // 是否微信客户端访问，默认为false
    protected $limitStar;
    protected $limitStr;
    protected $page;
    protected $isWhiteList = false; //是否白名单
    protected $isLoginWhiteList = false; //是否登录白名单

    public function __construct() {
        parent::__construct();
        // $this->goodsCache = new GoodsCache();
    }
    /**
     * [_initialize 初始化]
     * @author kofu <[418382595@qq.com]>
     */
    public function _initialize() {
        // $this->point = new Point();
        
        $c = M('config')->getField('config_sign, config_value');
        C($c);
        $this->whiteList();
        $this->loginWhiteList();
        // $this->wechatAgent = $this->isWechatAgent();
        
        $controllerName = CONTROLLER_NAME;
        $array = array(
            'WxPay'
        );
        if ( !in_array($controllerName, $array) ) {
            // if ($this->wechatAgent === true) {
                // C('DEFAULT_THEME', 'wechat');
                $this->loadWechatInitialize();
            // } else {
            //     $this->loadWebInitialize();
            // }
        }
    }

    /**
     * [loadWechatInitialize description]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function loadWechatInitialize() {
        session_start();
        $online = C('ONLINE');
        $online = 0;
        if ( !$online ) {
            $userInfo   = M('user')->where(array('id'=> 17))->find();
            session('userInfo', $userInfo);
            session('userId', $userInfo['id']);
            session('is_temp', 0);
        } else {
            $userInfo   = session('userInfo');
            if ( ! $this->isLoginWhiteList ) {
                // 未登录时
                if ( empty($userInfo) ) {
                    // 无码 跳转
                    $code   = I('get.code', '');
                    // $wx     = new WeixinController();
                    $openClass = new OpenController();
                    if ( empty($code) && !IS_AJAX && !IS_POST ) {
                        // $wx->authjumpOpOauth();
                        $openClass->authjumpAuthOauth();
                    }
                    // 错码 跳转
                    $OauthCode   = $openClass->OauthAuthCode($code);
                    if ( !empty($OauthCode['errcode']) ) {
                        // $wx->authjumpOpOauth();
                        $openClass->authjumpAuthOauth();
                    }
                    // 得到用户信息
                    // $gUserInfo = $wx->authGetUserInfo($OauthCode['access_token'], $OauthCode['openid']);
                    $gUserInfo = $openClass->authGetUserInfo($OauthCode['access_token'], $OauthCode['openid']);

                    // $gUserInfo1 = $this->panduan($gUserInfo['openid']);
                    // file_put_contents('gUserInfo1', json_encode($gUserInfo1), FILE_APPEND);
                    $user   = M('user');
                    if ( !empty($gUserInfo['openid']) ) {
                        // && !empty($gUserInfo['unionid'])
                        $gUserInfo['subscribe'] = isset($gUserInfo['subscribe']) ? $gUserInfo['subscribe'] : 0;
                        $data  = array(
                            
                            'open_id'           => $gUserInfo['openid'],
                            // 'unionid'           => $gUserInfo['unionid'],
                            'nickname'          => $gUserInfo['nickname'],
                            'sex'               => $gUserInfo['sex'],
                            // 'language'          => $gUserInfo['language'],
                            // 'city'              => $gUserInfo['city'],
                            // 'province'          => $gUserInfo['province'],
                            // 'country'           => $gUserInfo['country'],
                            'headimgurl'        => $gUserInfo['headimgurl'],
                            'subscribe'         => $gUserInfo['subscribe'],
                        );
                      
                        // $sUserInfo = $user->where(array('unionid' => $gUserInfo['unionid']))->find();
                        $sUserInfo = $user->where(array('open_id' => $gUserInfo['openid']))->find();

                        if ( empty($sUserInfo) ) {
                            // $data['source'] = 2;
                            $data['add_time'] = time();
                            $userId = $user->data($data)->add();
                            $this->isWhiteList = true;
                            // 检测系统用户同步
                            // $Detection = new Detection();
                            // $return = $Detection->createOrUpdateCustomer(array('operation'=>'create', 'id'=>$userId, 'name'=>$userId . ':' . $data['open_id'], 'code'=>$userId));
                            // $return = $Detection->createOrUpdateCustomer(array('operation'=>'create', 'id'=>$userId, 'name'=>'xcrozz' . $userId, 'code'=>$userId));
                            // 检测系统用户同步 end

                            // 新用户注册赠送积分
                            // $registerCoin = C('registerCoin');
                            // if (!empty($registerCoin)) {
                            //     $this->point->point(0, $userId, $registerCoin, 1, '注册');
                            // }
                        } else if ( $sUserInfo['nickname'] == '' || $sUserInfo['open_id'] == '' ) {
                            $user->where(array('open_id'=> $gUserInfo['openid']))->save($data);
                            // $this->isWhiteList = true;
                        } else {
                            $gUserInfo = array();
                            // $this->isWhiteList = true;
                        }

                        // 看是否已订阅，或者曾经订阅
                        $ids = $user->where(array('open_id' => $data['open_id']))->find();
                        // 已订阅
                        $userInfo   = array_merge($ids, $gUserInfo);

                        // 保存用户头像
                        // $userDir            = C('UPLOAD_PATH') . "userPic/" . $userInfo['id'];
                        // $userHeadimgDir     = $userDir . "/user_headimg";
                        // $userQRcodeDir      = $userDir . "/user_qrcode"; 
                        // $headimgurl         = $userHeadimgDir . "/{$userInfo['id']}.png";
                        // $imgData['headimgurl'] = trim($headimgurl, '.');
                        // 生成用户目录
                        // if(!is_dir($userDir)) {
                        //     mkdir($userDir, 0777);
                        //     mkdir($userHeadimgDir, 0777);
                        //     mkdir($userQRcodeDir, 0777);
                        // }
                        
                        // 写入图片
                        // putFileFromUrlContent($data['headimgurl'], $headimgurl);
                        // if (!file_exists($headimgurl)) $user->where(array('id' => $userInfo['id']))->save($imgData);
                        // $userInfo = array_merge($userInfo, $imgData);
                        $user->where(array('id' => $userInfo['id']))->save(array('headimgurl'=> $data['headimgurl']));
                        $imgData['headimgurl'] = $data['headimgurl'];
                        $userInfo = array_merge($userInfo, $imgData);
                    } else {
                        if ( $user->where(array('open_id' => $OauthCode['openid']))->count() <= 0) {
                            $data  = array(
                                'open_id'           => $OauthCode['openid'],
                                'last_login_time'   => time(),
                                'add_time'          => time(),
                            );
                            $userId = $user->data($data)->add();
                            $this->isWhiteList = true;
                            // 检测系统用户同步
                            // $Detection = new Detection();
                            // $return = $Detection->createOrUpdateCustomer(array('operation'=>'create', 'id'=>$userId, 'name'=>$userId . ':' . $data['open_id'], 'code'=>$userId));
                            // $return = $Detection->createOrUpdateCustomer(array('operation'=>'create', 'id'=>$userId, 'name'=>'xcrozz' . $userId, 'code'=>$userId));
                            // 检测系统用户同步 end
                        }
                        $userInfo = $user->where(array('open_id'=>$OauthCode['openid']))->find();
                    }

                    // $userInfo['level'] = getUserLevelName($userInfo['level']);
                    // $userInfo['level_name'] = getUserLevelName($userInfo['level']);
                    // 默认帮其登录
                    session('userInfo', $userInfo);
                    session('userId', $userInfo['id']);
                    session('is_temp', 0);
                    $sessionId = session_id();
                    $update    = array(
                        'last_login_time' => time(),
                        'session_id'      => $sessionId
                    );
                    $user->where(array('id'=> $userInfo['id']))->save($update);
                }

            } else {
                $userInfo['id'] = 0;
            }
        }
        
        // 微信端success && error 返回页面设置
        C('TMPL_ACTION_ERROR', './Static/Public/MsgPage/Wechat/dispatch_jump.html');
        C('TMPL_ACTION_SUCCESS', './Static/Public/MsgPage/Wechat/dispatch_jump.html');
        C('TMPL_EXCEPTION_FILE', './Static/Public/MsgPage/Wechat/think_exception.html');

        $controllerName = CONTROLLER_NAME;
        $actionName     = ACTION_NAME;
        $right          = M('controller_power')->where(array('controller_name'=>$controllerName, 'controller_function'=>$actionName))->find();
        if( ( count($right) < 1 && (!is_numeric($controllerName) || ($controllerName == 'Binding' && !is_numeric($actionName))) ) ) {
            // && !$this->isWhiteList
            die(statusCode(array(), 100001));
        } else {
            session_set_cookie_params(3600, '/', C('BASE_COOKIE_HOST'), false, true);
            define(NEED_PAGE, $right['need_page']);
            define(PAGE_LIMIT, $right['page_limit']);

            if($right['need_login'] == '1') {
                //用户尚未登录，直接跳到登录界面
                $userId = session('userId');//用户名称
                $isTemp = session('is_temp'); //是否是临时用户
                if( empty($userId) || $isTemp == '1') {
                    die(statusCode(array(), 100000));
                }
            }
        }
        $this->load_limit();
        
        $data = array(
            'user_id' => $userInfo['id'],
            'controller' => $controllerName.'-'.$actionName,
            'add_time'  => time(),
        );
        M('user_login')->add($data);

        // 更新用户的系统消息 自动取消订单 && 自动确认收货 签到 
        // $this->updateUserInformation($userInfo['id'], 0);

        // 分享按钮
        $pid = new Pid();
        $preCA = CONTROLLER_NAME . '-' . ACTION_NAME;
        $link = $pid->sharesUrl($preCA, $userInfo['id']);
        $this->assign('link', $link);
        // dump($link);die;
        // $link = $wx->shareUrl($preCA,  $userInfo['id']);
        // file_put_contents('link', json_encode($link));
        $getAppid = I('get.appid');
        $appid = empty($getAppid) ? session('appid') : $getAppid;
        
        $appid = empty($appid) ? C('wx_appid') : $appid;
        session('appid', $appid);
        $Jssdk = new JssdkController($appid);
        $signPackage = $Jssdk->getSignPackage();
        $this->assign('signPackage', $signPackage);

        $signatureData = $this->getSignature($signPackage['timestamp'], $signPackage['nonceStr']);
        $this->assign('signature', json_encode($signatureData));
        // $this->assign('shareImage', trim(C('webSite'), '/') . C('shareImage'));
        
    }

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
     * [__destruct 析构函数]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    function __destruct() {
        $controllerName = CONTROLLER_NAME;
        $actionName     = ACTION_NAME;
        $right          = M('controllerPower')->where(array('controller_name'=>$controllerName, 'controller_function'=>$actionName))->find();
        // 判断页面是否需要缓存 
        if ($right['need_cached'] == 1) {
            $pageContent = $this->fetch($controllerName . '/' . $actionName);
            if($actionName == 'goodsDetail') {
                $goodsId = I('get.id', '');
                if($this->isMobile) {
                    file_put_contents("./Static/Temp/MobileGoodsTemp/goods{$goodsId}.html", $pageContent);
                } else {
                    if(!empty($goodsId)) {
                        // $goodsBase = M ('goods')->where (array('id' => $goodsId))
                        //     ->field ('is_on_sale, goods_image, goods_price')
                        //     ->find ();
                        $goodsBase = $this->goodsCache->getGoodsCache($goodsId);
                        // 商品详情
                        // $goodsDesc = M ('goods_extension')->where (array('goods_id' => $goodsId))->getField ('goods_desc');
                        if (!$goodsBase[ 'is_on_sale' ] && (!$goodsBase[ 'goods_image' ] || $goodsBase[ 'goods_price' ] <= '0')) {

                        } else {
                            file_put_contents ("./Static/Temp/PcGoodsTemp/goods{$goodsId}.html", $pageContent);
                        }
                    }
                }
            } else if($controllerName == 'Index' && $actionName == 'index') {
                if($this->isMobile) {
                    file_put_contents("./Static/Temp/MobileGoodsTemp/index.html", $pageContent);
                } else {
                    file_put_contents("./Static/Temp/PcGoodsTemp/index.html", $pageContent);
                }
            } else {
                 //缓存的键
                // $pageCacheKey = C('pageCachePrefix') . 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                //读取缓存
                // switch ($pageInfo['cache_type']) {
                //     case 'memcache':
                //         $cache = diyS($pageCacheKey, '', array('type'=>'Memcache'));
                //         break;
                //    case 'file':
                //         $cache = diyS($pageCacheKey, '', array('type'=>'file'));
                //         break;
                // }

                // if(!empty($cache)) exit($cache);
            }
        }
    }

    public function load_limit() {
        if ( NEED_PAGE ) {
            $page  = I('request.page', 1, 'int') - 1;
            $page  = $page < 0 ? 0 : $page;
            $limit = PAGE_LIMIT;
            $this->page         = $page;
            $this->limitStar    = $limit * $page;
            $this->limitStr     = "LIMIT {$this->limitStar} , {$limit}";
        } else {
            $limit = PAGE_LIMIT;
            $this->limitStr = "";
        }
    }

    /**
    * [recursiveCategory 递归分类信息]
    * @author StanleyYuen <[350204080@qq.com]>
    */
    public function recursiveCategory($pid, $list = '') {
        static $categoryList;
        if ( !empty($list) ) {
            $categoryList = $list;
        }

        $childList = array();
        foreach ($categoryList as $key => $value) {
            if ( $value['pid'] == $pid ) {
                $childList[]    = $value;
                unset($categoryList[$key]);
            }
        }

        if (empty($childList)) {
            return false;
        } else {
            $result = array();
            foreach ($childList as $cvalue) {
                $tempResult = $this->recursiveCategory($cvalue['id']);
                if (!empty($tempResult)) {
                    foreach ($tempResult as &$value) {
                        $value['path'] = $pid . '-' . $value['path'];
                    }
                    $cvalue['childCategory'] = $tempResult;
                }
                $result[] = $cvalue;
            }

            return $result;
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

    public function getSignature($timestamp, $nonce_str) {
        $userInfo = session('userInfo');
        $openClass = new OpenController();
        $api_ticket = $openClass->getApiTicket();
        $timestamp = (string)$timestamp;
        $nonce_str = random_string();
        $card_id = C('WX_CARD_ID');
        $data = array(
            'api_ticket' => $api_ticket,
            'timestamp' => $timestamp,
            'nonce_str' => $nonce_str,
        );
        if ( !empty( $userInfo['open_id'] ) ) {
            $data['openid'] = $userInfo['open_id'];
        }
        if ( !empty( $card_id ) ) {
            $data['card_id'] = $card_id;
        }
        if ( !empty( $code ) ) {
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
    public function loginWhiteList() {
        $whiteList = C('LOGIN_WHITE_LIST');
        if ( in_array( CONTROLLER_NAME, $whiteList['controllerName'] ) || in_array( CONTROLLER_NAME . '-' . ACTION_NAME, $whiteList['controllerName-actionName'] ) ) {
            $this->isLoginWhiteList = true;
        } else {
            $this->isLoginWhiteList = false;
        }
    }

    public function panduan($openid) {
        $openClass = new OpenController();
        $access_token = $openClass->getAuthorizerAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN ";
        $returnData = curl($url, '' , 'get');
        $returnArr = json_decode($returnData, true);
        return $returnArr;
    }
}