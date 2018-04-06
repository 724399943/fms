<?php
use Think\Controller;

class Event extends Controller {

	private $lib_access;
	private $postObj;
	public function __construct($data) {
		$this->postObj = $data;
        $accessData = array('appinfoId' => APPINFOID);
		$this->lib_access = new \Common\Library\Lib_access($accessData);
	}

    public function location() {
        $data = array(
            'latitude'  => "{$this->postObj['Latitude']}",
            'longitude' => "{$this->postObj['Longitude']}"
        );
        M('agent')->where(array('open_id'=>"{$this->postObj['FromUserName']}"))->data($data)->save();
    }

	public function unsubscribe() {
        //取消关注
        $openid = $this->postObj['FromUserName'];
        $agent = M('agent');

        // 若用户取消关注，其下线将跟回其上级
        $ids = $agent->field('id, pid, session_id')->where(array('open_id'=>$openid))->find();
        $agent->where(array('pid'=>$ids['id']))->data(array('pid'=>$ids['pid']))->save();

        // 更新我的信息
        $agent->where(array('open_id'=>$openid))->data(array('is_subscribe'=>'0', 'session_id'=>''))->save();

        // 上级减少下级数量
        $agentRank = M('UserConf')->where(array('leaderid'=>APPINFOID))->getField('agentRank');
        $this->reduceCustomer($ids['pid'], $agentRank);

        // 帮其退出
        session_id($ids['session_id']);
        session_start();
        session_unset();
        session_write_close();
        break;
    }

    public function subscribe() {
		$level = 0;
        $userInfo = $this->lib_access->getUserdata($this->postObj['FromUserName']);
        $agent = M('agent');
        $unsubscription = M('unsubscription');
        $unsubData = $unsubscription->where(array('open_id'=>$userInfo['openid']))->find();

        if ( empty($unsubData) ) {
            if(!empty($this->postObj['EventKey'])) {
                $unsubData['pid'] = str_replace('qrscene_', '', $this->postObj['EventKey']);
            } else {
                $unsubData['pid'] = '0';
            }
        }
        // 检查是否已加入
        $pidInfo = $agent->field('pid, leaderid, level, open_id, is_subscribe')->where(array('id'=>$unsubData['pid']))->find();
        if(!empty($pidInfo)) {
            // 推荐人已经取消关注
            $level = $pidInfo['level'];
            if ( $pidInfo['is_subscribe'] == '0' ) {
                $unsubData['pid'] = $pidInfo['pid'];
            }
        }
        $level ++;

        $data  = array(
            'pid'               => $unsubData['pid'],
            'leaderid'          => APPINFOID,
            'level'             => $level,
            'open_id'           => $userInfo['openid'],
            'nickname'          => $userInfo['nickname'],
            'sex'               => $userInfo['sex'],
            'language'          => $userInfo['language'],
            'city'              => $userInfo['city'],
            'province'          => $userInfo['province'],
            'country'           => $userInfo['country'],
            'headimgurl'        => $userInfo['headimgurl'],
            'money'             => '0',
            'customer_num'      => '',
            'consignee'         => '',
            'telephone'         => '',
            'zipcode'           => '',
            'address'           => '',
            'is_lock'           => '0',
            'is_boss'           => '0',
            'is_subscribe'      => '1',
            'last_login_time'   => '0',
            'add_time'          => time()
        );

        if ( $agent->where(array('open_id'=>$userInfo['openid']))->count() <= 0 ) {
            $agentId = $agent->data($data)->add();
            if ( $agentId ) {
                $img = file_get_contents($userInfo['headimgurl']);
                file_put_contents('./Uploads/headImg/' . $agentId . '.jpg', $img);
                $unsubscription->where(array('open_id'=>$userInfo['openid']))->delete();
            }
        } else {
            // 从新关注可以改pid
            unset($data['money']);
            unset($data['add_time']);
            if ( $agent->where(array('open_id'=>$userInfo['openid']))->data($data)->save() ) {
                $agentId = $agent->where(array('open_id'=>$userInfo['openid']))->getField('id');
                if (!file_exists('./Uploads/headImg/' . $agentId . '.jpg')) {
                    $img = file_get_contents($userInfo['headimgurl']);
                    file_put_contents('./Uploads/headImg/' . $agentId . '.jpg', $img);
                }
                $unsubscription->where(array('open_id'=>$userInfo['openid']))->delete();
            }
        }

        // 上级添加下级数量
        $agentRank = M('UserConf')->where(array('leaderid'=>APPINFOID))->getField('agentRank');
        $this->calcCustomer($unsubData['pid'], $agentRank);

        $postData = array(
            'appinfoId' => APPINFOID,
            'openid' => $userInfo['openid'],
            'nickname' => $userInfo['nickname'],
        );
        socketPost('http://'.$_SERVER['HTTP_HOST'].U('Template/subTem'), $postData, FALSE);
	}

    /**
     * [calcCustomer 上级添加用户数]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function calcCustomer($pid, $counter) {
        $agent = M('Agent');
        if ( $counter < 1 ) {
            return;
        } else {
            $agent->where(array('id'=>$pid))->setInc('customer_num');
            $sub_pid = $agent->where(array('id'=>$pid))->getField('pid');
            $counter --;
            $this->calcCustomer($sub_pid, $counter);
        }
    }

    /**
     * [reduceCustomer 取消关注后上级减少用户数]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function reduceCustomer($pid, $counter) {
        $agent = M('Agent');
        if ( $counter < 1 ) {
            return;
        } else {
            $agent->where(array('id'=>$pid))->setDec('customer_num');
            $sub_pid = $agent->where(array('id'=>$pid))->getField('pid');
            $counter --;
            $this->reduceCustomer($sub_pid, $counter);
        }
    }
}