<?php
namespace Common\Library;
use Think\Controller;
class Pid extends Controller {
	public function __construct() {
		parent::__construct();
	}

	public function getPid($id = '-1') {
        $pid = session('pid');
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
        session('pid', $pid);
        return $pid;
    }

	public function budget($unsubData) {
        // 加入预订阅表
        $unsubscription = M('unsubscription');
        if ( $unsubscription->where(array('open_id'=>$unsubData['open_id']))->count() <= 0 ) {
            $unsubscription->data($unsubData)->add();
        } else {
            unset($unsubData['add_time']);
            $unsubscription->where(array('open_id'=>$unsubData['open_id']))->data($unsubData)->save();
        } 
    }

	public function getSubscribedPid($pid) {
        if ( empty($pid) ) return '0';
        $pidExist = M('agent')->field('id, pid, is_subscribe')->where(array('id'=>$pid))->find();
        if ( empty($pidExist) ) {
            return '0';
        } else {
            if ( $pidExist['is_subscribe'] == '1' ) {
                return $pidExist['id'];
            } else {
                return $this->getSubscribedPid($pidExist['pid']);
            }
        }
    }

    public function getLastPid($openid) {
        $pid     = $this->getPid();
        if ( empty($pid) ) {
            $tempPid = M('unsubscription')->where(array('open_id'=>$openid))->getField('pid');
            $lastPid = $this->getSubscribedPid($tempPid);
        } else {
            $lastPid = $this->getSubscribedPid($pid);
            if ( $lastPid == '0' ) {
                $tempPid = M('unsubscription')->where(array('open_id'=>$openid))->getField('pid');
                $lastPid = $this->getSubscribedPid($tempPid);
            }
        }
        return $this->getPid($lastPid);
    }

    public function whiteList($preCA, $userInfo) {
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

    public function shareUrl($preCA, $pid) {
        $state = "p_" . $this->getPid();
        $userInfo = session('userInfo');
        switch ($preCA) {
            case 'Answer-answerIndex':
                $goodsId = I('get.id', '');
                if ( empty($goodsId) ) {
                    $url   = C("webSite") . U("Shop/Main/index");
                } else {
                    $url   = C("webSite") . U("Shop/Goods/singleProduct", array('id'=>$goodsId));
                }
                break;
            default:
                $url   = C("webSite") . U("Shop/Main/index", array('id'=>$userInfo['leaderid']));
                break;
        }
        $libOauth = new \Common\Library\Lib_wxoauth();
        $link = $libOauth->getCode(C("weixinAppID"), $url, $state);
        return $link;
    }
   public function sharesUrl($preCA, $pid){
        $userInfo = session('userInfo');

        switch ($preCA) {
            case 'Index-finish':
                $id = I('get.id','');
                
                // $userDetail = M('user')->where(array('id'=>$id))->find();
                
                $title = C('shareTitle');
                if ( MODULE_NAME == C('DEFAULT_MODULE') ) {
                    $url = trim(C('webSite'),'/').'/Index/share.html?id='.$id.'&type=1';
                } else {
                    $url = trim(C('webSite'),'/').'/' . MODULE_NAME . '/Index/share.html?id='.$id.'&type=1';
                }
                $icon = C('shareIcon');
                $desc = C('shareDesc');

                break;
            case 'Index-recordingDetail':
                $id = I('get.id','');
                
                // $userDetail = M('user')->where(array('id'=>$id))->find();
                
                $title = C('shareTitle');
                if ( MODULE_NAME == C('DEFAULT_MODULE') ) {
                    $url = trim(C('webSite'),'/').'/Index/share.html?id='.$id;
                } else {
                    $url = trim(C('webSite'),'/').'/' . MODULE_NAME . '/Index/share.html?id='.$id;
                }
                $icon = C('shareIcon');
                $desc = C('shareDesc');

                break;
            case 'Index-share':
                $id = I('get.id','');
                $type = I('get.id','');
                if(empty($type)){
                    $str = '';
                } else {
                    $str = '&type=1';
                }
                // $userDetail = M('user')->where(array('id'=>$id))->find();
                
                $title = C('shareTitle');
                if ( MODULE_NAME == C('DEFAULT_MODULE') ) {
                    $url = trim(C('webSite'),'/').'/Index/share.html?id='.$id.$str;
                } else {
                    $url = trim(C('webSite'),'/').'/' . MODULE_NAME . '/Index/share.html?id='.$id.$str;
                }
                $icon = C('shareIcon');
                $desc = C('shareDesc');

                break;
            default:
                $title = C('shareTitle');
                if ( MODULE_NAME == C('DEFAULT_MODULE') ) {
                    $url = trim(C('webSite'),'/').'/Index';
                } else {
                    $url = trim(C('webSite'),'/').'/' . MODULE_NAME . '/Index';
                }
                $icon = C('shareIcon');
                $desc = C('shareDesc');
                break;
        }
        $link = array(
            'title'=>$title,
            'icon'=>$icon,
            'url'=>$url,
            'desc'=>$desc,
            );
        // dump($link);die;
        return $link;
    }
    /**
     * [jumpOauth 跳转授权]
     * @author [Nic] [317384591@qq.com]
     * @version  [version]
     * @DateTime 2015-08-12T11:57:16+0800
     * @return   [type]                   [description]
     */
    public function jumpOauth() {
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $urlData = parse_url($uri);
        parse_str($urlData['query'], $data);
        unset($data['code']);
        $uri = 'http://'.$_SERVER['HTTP_HOST'].$urlData['path'].'?'.http_build_query($data);

        $state = "p_" . $this->getPid();
        // $userInfo = session('userInfo');
        // $data["appinfoId"] = $userInfo['leaderid'];
        // $lib_access = new \Common\Library\Lib_access($data);
        // $access_token = $lib_access->seachAccessToken();
        $libOauth = new \Common\Library\Lib_wxoauth();
        $url = $libOauth->getCode(C('weixinAppID'), $uri, $state);
        header("LOCATION:{$url}");
        exit();
    }
}