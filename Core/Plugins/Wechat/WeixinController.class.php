<?php
namespace Plugins\Wechat;
use Think\Controller;
class WeixinController extends Controller {
    public function _initialize() {
        session_start();
        $config = M('config')->getField('config_sign, config_value');
        // C($c);
        // $config = M('wxaccount')->where(array('agent_id'=> 1))->find();
        // $config = C();
        define(WX_APPID, $config['appid']);
        define(WX_MCHID, $config['mchid']);
        define(WX_KEY, $config['wxpaykey']);
        define(WX_APPSECRET, $config['appsecret']);
        define(WX_NOTIFY_URL, rtrim(C('webSite'), '/') . U('WxPay/notify_url'));
        define(WX_SSLCERT_PATH, C('AGENT_CACERT_PATH') . 1 . "/cacert/apiclient_cert.pem");
        define(WX_SSLKEY_PATH, C('AGENT_CACERT_PATH') . 1 . "/cacert/apiclient_key.pem");
    }

    static function whiteList($preCA, $userInfo) {
        // 未登录的白名单
        $whiteList = array(
            'Main-index',
            'Goods-singleProduct',
        );

        if ( !in_array($preCA, $whiteList) && empty($userInfo) ) {
          return false;
        } else {
          return true;
        }
    }

    static function shareUrl($preCA, $pid="") {
        if(empty($pid)) {
            $userInfo = session('userInfo');
            $pid = $userInfo['id'];
        }
        switch ($preCA) {
            case 'Goods-productDetail':
                $goodsId = I('get.id', '');
                if ( empty($goodsId) ) {
                    $url   = trim(C('webSite'), '/') . "/Shop/{$pid}";
                } else {
                    $url   = C("webSite") . U("Shop/Goods/productDetail", array('id'=> $goodsId, 'pid' => $pid));
                }
                break;
            default:
                $url   = trim(C('webSite'), '/') . "/Shop/{$pid}";
                break;
        }
        $link = self::mkUrl($url);
        return $link;
  }

  static function getSubscribedPid($pid) {
        if ( empty($pid) ) return '0';

        $pidExist = M('agent')->field('id, pid, is_subscribe')->where(array('id'=>$pid))->find();
        if ( empty($pidExist) ) {
            return '0';
        } else {
            if ( $pidExist['is_subscribe'] == '1' ) {
                return $pidExist['id'];
            } else {
                return self::getSubscribedPid($pidExist['pid']);
            }
        }
    }

    static function getLastPid($openid) {
        $pid     = self::getPid();
        if ( empty($pid) ) {
            $tempPid = M('unsubscription')->where(array('open_id'=>$openid))->getField('pid');
            $lastPid = self::getSubscribedPid($tempPid);
        } else {
            $lastPid = self::getSubscribedPid($pid);
            if ( $lastPid == '0' ) {
                $tempPid = M('unsubscription')->where(array('open_id'=>$openid))->getField('pid');
                $lastPid = self::getSubscribedPid($tempPid);
            }
        }

        return self::getPid($lastPid);
    }

    static function budget($unsubData) {
        // 加入预订阅表
        $unsubscription = M('unsubscription');
        if ( $unsubscription->where(array('open_id'=>$unsubData['open_id']))->count() <= 0 ) {
            $unsubscription->data($unsubData)->add();
        } else {
            unset($unsubData['add_time']);
            $unsubscription->where(array('open_id'=>$unsubData['open_id']))->data($unsubData)->save();
        } 
    }


    static function access_token() {
        $result = S('czm_access_token');
        $result = json_decode($result, true);
        
        if ( $result['expire_time'] <= time() ) {
            $weixinAppID     = WX_APPID;
            $weixinAppSecret = WX_APPSECRET;

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$weixinAppID}&secret={$weixinAppSecret}";

            $result = curl($url, '', 'post');
            $result = json_decode($result, true);

            if ( $result['access_token'] ) {
                $result['expire_time'] = time() + 7000;
                // 进行缓存
                S('czm_access_token', json_encode($result));
            }
        }

        $access_token = $result['access_token'];
        return $access_token;
    }

    static function OauthCode($code) {
        $weixinAppID     = WX_APPID;
        $weixinAppSecret = WX_APPSECRET;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$weixinAppID}&secret={$weixinAppSecret}&code={$code}&grant_type=authorization_code";
        // file_put_contents('aaaaaa.txt', $weixinAppID . '---' . $weixinAppSecret . '---' . $url . '\n');
        $code = curl($url, '', 'get');
        $code = json_decode($code, true);
        return $code;
    }

    static function getOpAccessToken() {
        $url = "http://custom.yangyue.com.cn/oppein/wx/WXparam.php?param=token";
        $code = curl($url, '', 'get');
        $code = json_decode($code, true);
        return $code;
    }

    static function refresh_token($refresh_token) {
        $weixinAppID = WX_APPID;
        $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$weixinAppID}&grant_type=refresh_token&refresh_token={$refresh_token}";
        $result = curl($url, '', 'GET');
        return json_decode($result);
    }

    static function clearAccessToken() {
        $result = S('czm_access_token');
        $result = json_decode($result, true);
        $result['expire_time'] = time() - 1;
        S('czm_access_token', json_encode($result));
    }

    static function getUserInfo($openid) {
        $access_token = self::access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $result = curl($url, '', 'GET');
        $resultArray = json_decode($result, true);
        if ( !empty($resultArray['errcode']) ) {
            self::clearAccessToken();
            return self::getUserInfo($openid);
        }
        // file_put_contents('userInfo.txt', $result . "\n", FILE_APPEND);
        return $resultArray;
    }

    static function authGetUserInfo($access_token, $openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $result = curl($url, '', 'GET');
        $resultArray = json_decode($result, true);
        return $resultArray;
    }

    static function jumpOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = self::mkUrl($uri);
        header("LOCATION:{$url}");
        exit();
    }

    static function authjumpOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = self::authMkUrl($uri);
        header("LOCATION:{$url}");
        exit();
    }

    static function authjumpOpOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = self::authMkOpUrl($uri);
        header("LOCATION:{$url}");
        exit();
    }

    static function getPid($id = '-1') {
        static $pid;

        if ( $id == '-1' && !isset($pid) ) {
            $state = I('get.state');
            if ( !empty($state) ) {
                $pid = intval(substr($state, 2));
            } else {
                $pid = '0';
            }

        } else if ( $id != '-1' ) {
            $pid = $id;
        }

        return $pid;
    }


    static function setIndustry() {
        $post = <<<EOF
        {
        "industry_id1":"1",
        "industry_id2":"2"
        }
EOF;
        $access_token = self::access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$access_token}";
        curl($url, $post, 'post');
    }


    static function getTemplate($config_sign, $template_id_short) {
        $value = C($config_sign);
        if ( empty($value) ) {
            $access_token = self::access_token();
            $post = <<<EOF
            {
             "template_id_short":"{$template_id_short}"
            }
EOF;
            $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$access_token}";
            $result = curl($url, $post, 'post');
            $result = json_decode($result, true);
            $value = $result['template_id'];
            M('config')->where(array('config_sign'=>$config_sign))->data(array('config_value'=>$value))->save();
        }

        return $value;
    }

    static function postToUser($msg) {
        $access_token = self::access_token();

        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
        $result = curl($url, $msg, 'post');
    }

    static function mkUrl($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }

        $appId  = WX_APPID;
        $pid  = self::getPid();
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$url}&response_type=code&scope=snsapi_base&state=p_{$pid}#wechat_redirect";
    }

    static function authMkUrl($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }

        $appId  = WX_APPID;
        $pid  = self::getPid();
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appId}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=p_{$pid}#wechat_redirect";
    }

    static function authMkOpUrl($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }
        return "http://custom.yangyue.com.cn/oppein/wx/oauth.php?redirect_uri={$url}&scope=snsapi_userinfo&state=";
    }

    public function createMenu() {
    $string = <<<EOF
    {
    "button":[{
        "type":"view",
        "name":"尖叫云商",
        "url":"http://www.hzjianjiao.com/Agent/Index/index.html"
        }]
    }
EOF;
    $token = self::access_token();
    $result = curl("https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}", $string);
    dump($result);
    }
}
