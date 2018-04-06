<?php
namespace Authorize\Controller;
use Think\Controller;
use Plugins\Wechat\WeixinController;
// use Common\Library\Lib_access;
use Plugins\WechatOpen\AccessController;

class JssdkController extends Controller {
    private $lib_access;
    // public function _initialize($wx_appid) {
    //     $this->lib_access = new OpenController();
    //     define(WX_APPID, $wx_appid);
    // }

    public function __construct($wx_appid) {
        parent::__construct();
        $this->lib_access = new AccessController();
        define(WX_APPID, $wx_appid);
    }

    public function getSignPackage() {

      $jsapiTicket = $this->getJsApiTicket();

      // 注意 URL 一定要动态获取，不能 hardcode.
      $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

      $timestamp = time();
      $nonceStr = $this->createNonceStr();

      // 这里参数的顺序要按照 key 值 ASCII 码升序排序
      $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

      $signature = sha1($string);

      $signPackage = array(
        "appId"     => WX_APPID,
        "nonceStr"  => $nonceStr,
        "timestamp" => $timestamp,
        "url"       => $url,
        "signature" => $signature,
        "rawString" => $string
      );
      // file_put_contents('signPackage', json_encode($signPackage));
      return $signPackage; 
    }

    private function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }

    private function getJsApiTicket() {
      $data = S('jsapi_ticket_'.'1');
      $data = json_decode($data, true);
      // 未过期
      if ( $data['expire_time'] < time() ) {

        $accessToken = $this->lib_access->getAuthorizerAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";

        $res = json_decode(curl($url, '', 'get'), true);
        $ticket = $res['ticket'];

        if ( $ticket ) {
          $data['expire_time']  = time() + 7000;
          $data['jsapi_ticket'] = $ticket;
          S('jsapi_ticket_'.'1', json_encode($data));
        }
      } else {
        $ticket = $data['jsapi_ticket'];
      }

      return $ticket;
    }

    // private function getOpJsApiTicket() {
    //     $url = "http://custom.yangyue.com.cn/oppein/wx/WXparam.php?param=ticket";
    //     $request = curl($url, '', 'get');
    //     $res = json_decode($request, true);
    //     return $res['jssdk_ticket'];
    // }
    // private function getOpJsApiTicket1() {
    //     $url = "http://custom.yangyue.com.cn/oppein/wx/WXparam.php?param=api_ticket";
    //     $request = curl($url, '', 'get');
    //     $res = json_decode($request, true);
    //     return $res['api_ticket'];
    // }
}

