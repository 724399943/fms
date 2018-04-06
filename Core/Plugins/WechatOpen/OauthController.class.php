<?php
namespace Plugins\WechatOpen;
use Think\Controller;
class OauthController extends Controller {
	private $accessHelper;
	private $appID;
	private $wx_appid;
	public function __construct($parameter) {
		$this->setParameter($parameter);
	}

	/**
	 * [authjumpAuthOauth 构造授权地址]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function authjumpAuthOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = $this->authMkAuthUrl($uri, $this->appID);
        header("LOCATION:{$url}");
        exit();
    }

    /**
     * [OauthAuthCode 获取用户授权信息]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)   2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $code [description]
     */
    public function OauthAuthCode($code) {
        $component_access_token = $this->accessHelper->getAccessToken();
        $url = "https://api.weixin.qq.com/sns/oauth2/component/access_token?appid={$this->wx_appid}&code={$code}&grant_type=authorization_code&component_appid={$this->appID}&component_access_token={$component_access_token}";
        $code = curl($url, '', 'get');
        $code = json_decode($code, true);
        return $code;
    }

    public function authMkAuthUrl($url, $encode = true) {
        if ( $encode ) {
            $url = urlencode($url);
        }
        return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->wx_appid}&redirect_uri={$url}&response_type=code&scope=snsapi_userinfo&state=p&component_appid={$this->appID}#wechat_redirect";
    }

    /**
     * [authGetUserInfo description]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $access_token [description]
     * @param     [type]        $openid       [description]
     * @return    [type]                      [description]
     */
    public function authGetUserInfo($access_token, $openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        $result = curl($url, '', 'GET');
        $resultArray = json_decode($result, true);
        return $resultArray;
    }

    private function setParameter(array $parameter) {
		foreach ($parameter as $key => $value) {
			$this->$key = $value;
		}
	}
}