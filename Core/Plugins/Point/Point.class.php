<?php
namespace Plugins\Point;
use Think\Controller;
use Think\Log;

/**
 * 积分
 */
class Point extends Controller {
    public function __construct($data) {
    	parent::__construct();
        $this->agentId = !empty($data) ? $data['agent_id'] : $this->agentId;
    }

    /**
     * [point 赠送/扣除积分
     * @author xu <565657400@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)e
     * @return   $fromId:发起id , $toId:接受id , $num:积分数量 , $type：赠送/扣除（0：扣除  1：赠送） 注：如果$type == 1,赠送到总平台id就填写 0 如果$type = 0的$fromId就填写平台id; $event积分变动行为信息  
     */
    public function point($fromId, $toId, $num, $type = 0, $event, $orderSn = '', $level = 0) {
        $user       = M('user');
        $pointLog   = M('point_log');
        $agentId   = $this->agentId;
        if($type == 0) {
            $state = $user->where(array('id'=>$toId))->setDec('point', $num);
        } else {
            $state = $user->where(array('id'=>$toId))->setInc('point', $num);
            if($fromId != 0) {
                $state = $user->where(array('id'=>$fromId))->setDec('point',$num);
                $pointLog->data(array('agent_id'=>$agentId,'from_id'=>'0', 'to_id'=>$fromId, 'type'=>'0', 'num'=>$num, 'event'=>$event, 'add_time'=>time()))->add();
            }
            if($toId==0){
                //统计捐赠给平台积分
                M('agent')->where(array('id'=>$agentId))->setInc('givepoint',$num);
            }
        }

        if($state) {
            $data = array(
                'agent_id'  => $agentId,
                'from_id'   => $fromId, 
                'to_id'     => $toId, 
                'type'      => $type, 
                'num'       => $num, 
                'event'     => $event, 
                'order_sn'  => $orderSn,
                'level'     => $level,
                'add_time'  => time()
            );
            $pointLog->add($data);
            return true;
        } else {
            return false;
        }
    }

    /**
     * [checkRule 查询积分规则
     * @author StanleyYuen <350204080@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     */
    public function checkRule() {
        $agentId = I('get.agent_id', 1, 'int');
        $userGradeModel = M('user_grade');
        $rules = json_decode($userGradeModel->where(array('agent_id'=>$agentId))->getField('rules'), true);
        dump($rules);
    }

    /**
     * [deductPoint 积分抵扣]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $shoppingCart [description]
     * @return    [type]                      [description]
     */
    public function deductPoint($shoppingCart) {
        $userId     = session('userId');
        $userInfo   = M('user')->where(array('id'=> $userId, 'agent_id'=> $this->agentId))->field('point, level')->find();

        $deductPrice = 0;
        $deductPoint = 0;
        if (!empty($userInfo['point'])) {
            // 积分抵扣倍率
            $deduct_point_ratio = 0;
            // 每个ID限制使用积分抵扣同一个产品件数
            // $deduct_same_good   = 0;
            $userGrade = M('user_grade');
            $rules = $userGrade->where(array('agent_id'=> $this->agentId))->getField('rules');
            if (!empty($rules)) {
                $rules = json_decode($rules, true);
                foreach ($rules as $rkey => $rvalue) {
                    if ($rvalue['grade_level'] == $userInfo['level']) {
                        // $deduct_same_good   = $rvalue['deduct_same_good'];
                        $deduct_point_ratio = $rvalue['deduct_point_ratio'];
                    }
                }
            }
            if (!empty($deduct_point_ratio)) {
                $total = 0;
                foreach ($shoppingCart as $key => $value) {
                    // if (!empty($deduct_same_good) && $value['goods_number'] > $deduct_same_good) {
                    //     $total += $deduct_same_good * $value['goods_price'];
                    // } else {
                        $total += $value['goods_number'] * $value['goods_price'];
                    // }
                }

                // 平台预抵扣价
                $preDeductPrice = bcmul($total, $deduct_point_ratio, 2);
                // 抵扣1元所需积分
                $discountCoin   = C('discountCoin');
                // 抵扣所需积分
                $needCoin = bcmul($preDeductPrice, $discountCoin);
                $needCoin = $needCoin == 0 ? 1 : $needCoin;
                // 如用户积分不满足抵扣所需积分
                if ($needCoin > $userInfo['point']) {
                    // 可抵扣价 
                    $deductPrice = (double) floor($userInfo['point'] / C('discountCoin') * 100) / 100;
                    $deductPoint = $userInfo['point'];
                } else {
                    $deductPrice = $preDeductPrice;
                    $deductPoint = $needCoin;
                }
            }
        }

        return array(
            'deductPrice' =>  sprintf("%.2f",$deductPrice),
            'deductPoint' =>  $deductPoint,
        );
    }
    /**
     * [getPointMoney 下级消费获取积分]
     * @author wulong <1191540273@qq.com>
     * @modify kofu <[418382595@qq.com]>
     * @copyright Copyright (c)      2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $orderSn [description]
     * @return    [type]                 [description]
     */
    public function getPointMoney($orderSn, $userId){
        // $orderSn = '1609300000002127';
        $agentId = $this->agentId;

        // $userId = session('userId');
        // $userInfo = session('userInfo');
        // if (empty($userInfo)) {
            $userInfo = M('user')->where(array('id'=>$userId,'agent_id'=>$agentId))->find();
        // }
        $pid = $userInfo['pid'];
        if ($pid == '1') return true;

        $level = M('user')->where(array('id'=>$pid,'agent_id'=>$agentId))->getField('level');
        if (empty($level)) return true;

        $rules = M('user_grade')->where(array('agent_id'=>$agentId))->getField('rules');
        if (empty($rules)) return true;

        $rules = json_decode($rules,true);
        // dump($rules);die;
        foreach ($rules as $key => $value) {
            if ($value['grade_level'] == $level) {
                //消费获取积分倍率
                $setProbability  = $value['sub_get_point_ratio'];
            }
        }
        // $total = M('order')->where(array('order_sn'=>$orderSn))->getField('total');
        $total = 0;
        $total = M('order')->where(array('order_sn'=>$orderSn))->getField('real_pay');
        // $point = number_format($total*$setProbability,2,'.','');
        $point = round($total*$setProbability);
        if (empty($point)) return true;

        // if (M('user')->where(array('id'=>$pid,'agent_id'=>$agentId))->setInc('point',$point)===false){
        // edit by kofu 2016/11/12 
        if ($this->point(0, $pid, $point, 1, '下级消费获得', $orderSn, $level) === false){
            return false;
        }
        return true;

    }
    /**
     * [getSalePointMoney 自己消费获取积分]
     * @author wulong <1191540273@qq.com>
     * @copyright Copyright (c)      2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $orderSn [description]
     * @return    [type]                 [description]
     */
    // public function getSalePointMoney($orderSn){
    //     // get_point_ratio
    //     $agentId = $this->agentId;
    //     $userId = session('userId');
    //     // $userInfo = session('userInfo');
    //     // if (empty($userInfo)) {
    //         $userInfo = M('user')->where(array('id'=>$userId,'agent_id'=>$agentId))->find();
    //     // }
    //     $level = $userInfo['level'];
    //     $rules = M('user_grade')->where(array('agent_id'=>$agentId))->getField('rules');
    //     $rules = json_decode($rules,true);
    //     $pointRatio = 0;
    //     foreach ($rules as $key => $value) {
    //         if ($value['grade_level'] == $level) {
    //             $pointRatio = $value['get_point_ratio'];
    //         }
    //     }
    //     $total = M('order')->where(array('order_sn'=>$orderSn))->getField('total');
    //     $point = round($total*$pointRatio);
    //     if (M('user')->where(array('id'=>$userId,'agent_id'=>$agentId))->setInc('point',$point)===false){
    //         return false;
    //     }
    //     return true;
    // }
    public function getSalePointMoney($goodsArr, $userId=''){
        // $agentId = $this->agentId;
        $agentId = '1';
        
        // $userId = session('userId');
        $point = 0;
        // $userInfo = M('user')->where(array('id'=>$userId,'agent_id'=>$agentId))->find();
        // $level = $userInfo['level'];
        // $level = M('user')->where(array('id'=>$userId,'agent_id'=>$agentId))->getField('level');
        $level = '1';
        if (empty($level)) {
            return $point;
        }
        $rules = M('user_grade')->where(array('agent_id'=>$agentId))->getField('rules');
        if (empty($rules)) {
            return $point;
        }
        $rules = json_decode($rules,true);
        return $rules;
        // $pointRatio = 0;
        foreach ($rules as $key => $value) {
            if ($value['grade_level'] == $level) {
                $pointRatio = $value['get_point_ratio'];
            }
        }
        if (is_array($goodsArr)) {
            foreach ($goodsArr as $key => $value) {
                if (is_array($value)) {
                    $point += $value['goods_price']*$value['goods_number']*$pointRatio;
                } else {
                    $point += $goodsArr['goods_price']*$goodsArr['goods_number']*$pointRatio;
                    break;
                }
                // dump($point);
            }
        } else {
            $point += $goodsArr*$pointRatio*1;//商品获取积分
        }
        // $point = number_format($point,2,'.','');
        $point = round($point);
        $point = empty($point) ? 1 : intval($point);
        return $point;
    }

    /**
     * [upgradeLevel 升级会员]
     * @author wulong <1191540273@qq.com>
     * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function upgradeLevel($userId, $end=0) {
        // $userId = session('userId');
        // edit by kofu 2016/11/13
        $agentId = $this->agentId;
        $orderModel = M('order');
        $where = array(
            'agent_id'=>$agentId,
            'user_id' =>$userId,
            'is_pay'  =>'1',
            'is_out_date'=>'0',
            'is_return'=>'0',
            'delivery_status'=>'1',
            'received'=>'1',
            'is_split'=> array('IN', '0,2')
            );
        $total = $orderModel->where($where)->getField('SUM(real_pay)');
        $total = $total?$total:0;
        
        //获取下级消费额
        $userModel = M('user');
        $lowerId=$userModel->where(array('pid'=>$userId,'agent_id'=>$agentId))->field('id')->select();
        if (!empty($lowerId)) {
            foreach ($lowerId as $key => $value) {
                $where = array(
                        'agent_id'=>$agentId,
                        'user_id' =>$value['id'],
                        'is_pay'  =>'1',
                        'is_out_date'=>'0',
                        'is_return'=>'0',
                        'delivery_status'=>'1',
                        'received'=>'1',
                        'is_split'=> array('IN', '0,2')
                    );
                $price = $orderModel->where($where)->getField('SUM(real_pay)');
                $price = $price?$price:0;
                $total += $price;
            }
        }
        //获取升级条件
        $level = $userModel->where(array('id'=>$userId,'agent_id'=>$agentId))->getField('level');
        if (empty($level)) {
            return true;
        }
        $rules = M('user_grade')->where(array('agent_id'=>$agentId))->getField('rules');
        if (empty($rules)) {
            return true;
        }
        $rules = json_decode($rules,true);
        $count = count($rules);
        $upgradeCondition = 0;
        foreach ($rules as $key => $value) {
            if ($value['grade_level'] == ($level + 1)) {
                //升级条件
                $upgradeCondition  = $value['upgrade_condition'];
            }
        }
        
        if ($upgradeCondition <= $total) {
            if ($count > $level) {
                if($userModel->where(array('id'=>$userId,'agent_id'=>$agentId))->setInc('level',1)===false){
                    return false;
                }
            }
        }

        // 判断上级时候升级
        if ($end == 1) {
            return true;
        } else {
            $pid = M('user')->where(array('id'=>$userId))->getField('pid');
            if ($pid != 1 && $pid != 0) {
                $this->upgradeLevel($pid, 1);
                return true;
            }
        }
    }

    /**
     * [checkPointLimit 检测积分商品限购]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function checkPointLimited($goods_id, $goods_number) {
        $userId = session('userId');
        $level = M('user')->where(array('id'=> $userId))->getField('level');
        $deduct_same_good = 0;
        $userGrade = M('user_grade');
        $rules = $userGrade->where(array('agent_id'=> $this->agentId))->getField('rules');
        if (!empty($rules)) {
            $rules = json_decode($rules, true);
            foreach ($rules as $rkey => $rvalue) {
                if ($rvalue['grade_level'] == $level) {
                    $deduct_same_good = $rvalue['deduct_same_good'];
                }
            }
        }
        $pointLimitLog = M('point_limited_log');
        $hasNumber = $pointLimitLog->where(array('user_id'=> $userId, 'goods_id'=> $goods_id))->getField('goods_number');
        $hasNumber = !empty($hasNumber) ? $hasNumber : 0;
        $totalNumber = $hasNumber + $goods_number;
        if ($totalNumber > $deduct_same_good) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * [insertPointLimited 记录积分商品限购log]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function insertPointLimited($goods_id, $goods_number) {
        $user_id = session('userId');
        $pointLimitLog = M('point_limited_log');
        $id = $pointLimitLog->where(array('user_id'=> $user_id, 'goods_id'=> $goods_id))->getField('id');
        if ( !empty($id) )
        {
            $pointLimitLog->where(array('id'=> $id))->setInc('goods_number', $goods_number);
        }
        else
        {
            $data['user_id']        = $user_id;
            $data['goods_id']       = $goods_id;
            $data['goods_number']   = $goods_number;
            $data['add_time']       = time();
            $pointLimitLog->add($data);
        }
        return $result;
    }
}