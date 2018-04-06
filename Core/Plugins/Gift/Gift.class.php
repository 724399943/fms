<?php
namespace Plugins\Gift;
use Think\Controller;
use Plugins\Controller\MutexController;
use Think\Log;
// use Shop\Controller\ShopController AS Shop;

/**
 * 赠品
 */
class Gift extends MutexController {
    static $couponDetail;
    public function __construct() {
    	parent::__construct();
    }

    /**
     * [checkGift 检测满赠商品]
     * @author Fu <[418382595@qq.com]>
     * $parameter['goodsId']    [商品id] 
     * $parameter['brandid']    [品牌id] 
     * $parameter['categoryId'] [分类id] 
     * $parameter['total']      [总价]
     */
    public function checkGift($parameter, $total, $number, $goodsArr) {
        $userId = session('userId');
        $now = time();
        $gift   = M('gift');
        // $goods  = M('goods');
        // $shoppingCart  = M('goods_shopping_cart');
        $giftList  = $gift->where(array('start_time'=> array('lt', $now), 'end_time'=> array('gt', $now), 'is_delete'=> 0,'agent_id'=>$this->agentId))->select();

        foreach ($giftList as $gift_key => $gift_value) {
            switch ($gift_value['type']) {
                case '4':
                    /*单品满赠*/
                    foreach ($parameter['goodsArr'] as $goods_key => $goods_value) {
                        if (strpos($gift_value['condition'], ','. $goods_key .',') !== false) {
                            $giftTemp = $this->checkGiftRuleType($gift_value, $goods_value['total'], 4, $goods_key ,$goods_value['number'], $goodsArr);
                            
                            if (!empty($giftTemp)) {
                                $giftArr[$gift_key] = $giftTemp;       
                            }
                        }
                    }
                    break;
                case '3':
                    /*品牌满赠*/
                    foreach ($parameter['brandArr'] as $brand_key => $brand_value) {
                        if (strpos($gift_value['condition'], ','. $brand_key .',') !== false) {
                            $giftTemp = $this->checkGiftRuleType($gift_value, $brand_value['total'], 3, $brand_key ,$brand_value['number'], $goodsArr);
                            
                            if (!empty($giftTemp)) {
                                $giftArr[$gift_key] = $giftTemp;       
                            }
                        }
                    }
                    break;
                case '2':
                    /*分类满赠*/
                    foreach ($parameter['catesArr'] as $category_key => $category_value) {
                        if (strpos($category_key, ','. $gift_value['condition'] .',') !== false) {
                            $giftTemp = $this->checkGiftRuleType($gift_value, $category_value['total'], 2, $category_key , $category_value['number'], $goodsArr);
                            
                            if (!empty($giftTemp)) {
                                $giftArr[$gift_key] = $giftTemp;       
                            }   
                        }    
                    }
                    break;
                default:
                    /*全场满赠*/
                    $giftTemp = $this->checkGiftRuleType($gift_value, $total, 1, $parameter['goodsArr'], $number, $goodsArr);
                            
                    if (!empty($giftTemp)) {
                        $giftArr[$gift_key] = $giftTemp;       
                    } 
                    break;
            }   
        }

        $goodsList = array();
        foreach ($giftArr as $gift_key => $gift_value) {
            foreach ($gift_value as $goods_key => $goods_value) {
                $goodsList[] = $goods_value;
            }
        }

        // 将相同id合并
        $tempId = 0;
        $tempKey = 0;
        $tempStr = array();
        $goodsList = array_column_sort($goodsList, 'goodsId', 'SORT_DESC');  // 按照字段排序
        foreach ($goodsList as $goods_key => $goods_value) {
            if ($goods_value['goodsId'] == $tempId) {
                $tempStr[$tempKey]['goodsNum'] += $goods_value['goodsNum'];
                $tempStr[$tempKey]['gift_name'] = $goods_value['gift_name'] . ";";
            } else {
                $tempId = $goods_value['goodsId'];
                $tempKey = $goods_key;
                $tempStr[$tempKey]['goodsNum'] = $goods_value['goodsNum'];
                $tempStr[$tempKey]['goodsId'] = $goods_value['goodsId'];
                $tempStr[$tempKey]['gift_name'] = $goods_value['gift_name'];
            }
        }
        return $tempStr;
    }


    /**
     * [checkGiftRuleType 对满赠规则进行筛选返回数据]
     * @author Fu <[418382595@qq.com]>
     */
    public function checkGiftRuleType($giftValue, $total, $type, $parameter, $number, $goodsArr) {
        $userId = session('userId');
        $mutexPrice  = 0;
        $mutexNumber = 0;
        $offersList  = $this->returnOffers($goodsArr);   // 获取互斥商品
        
        if (!empty($offersList) && !empty($offersList['giftGoods'])) {
            $offersArr = array();
            foreach ($offersList['giftGoods'] as $offers_key => $offers_value) {
                $offersArr['goodsArr'][] = $offers_key;
                $offersArr['brandArr'][] = $offers_value['brand_id'];
                $offersArr['cateArr'][]  = $offers_value['category_path'];
            }

            switch ($type) {
                case '1':
                    foreach ($parameter as $p_key => $p_value) {
                        if (in_array($p_key, $offersArr['goodsArr'])) {
                            $goodsNumber = M('goods_shopping_cart')->where(array('user_id'=> $userId, 'goods_id'=> $p_key))->getField('goods_number');
                            $mutexPrice  += ($offersList['giftGoods'][$p_key]['discount'] * $goodsNumber);
                            $mutexNumber += $p_value['number'];
                        }
                    }
                    break;
                case '2':
                    foreach ($offersArr['cateArr'] as $cate_key => $cate_value) {
                        if (','. $cate_value . ',' == $parameter) {
                            $goodsId = $offersArr['goodsArr'][$cate_key];
                            $goodsNumber = M('goods_shopping_cart')->where(array('user_id'=> $userId, 'goods_id'=> $goodsId))->getField('goods_number');
                            $mutexPrice  = $offersList['giftGoods'][$goodsId]['discount'] * $goodsNumber;
                            $mutexNumber = $number;
                        }
                    }
                    break;
                case '3':
                    if (in_array($parameter, $offersArr['brandArr'])) {
                        $goodsId = $offersArr['goodsArr'][array_search($parameter, $offersArr['brandArr'])];
                        $goodsNumber = M('goods_shopping_cart')->where(array('user_id'=> $userId, 'goods_id'=> $goodsId))->getField('goods_number');
                        $mutexPrice = $offersList['giftGoods'][$goodsId]['discount'] * $goodsNumber;   
                        $mutexNumber = $number;
                    }
                    break;    
                default:
                    if (in_array($parameter, $offersArr['goodsArr'])) {
                        $goodsNumber = M('goods_shopping_cart')->where(array('user_id'=> $userId, 'goods_id'=> $parameter))->getField('goods_number');
                        $mutexPrice  = $offersList['giftGoods'][$parameter]['discount'] * $goodsNumber;
                        $mutexNumber = $number;
                    }
                    break;
            }
        }

        $ruleValue = json_decode($giftValue['rule_value'], true);
        if ($giftValue['rule_type'] == 0) {
            // 多买多送
            foreach ($ruleValue as $rule_key => $rule_value) {
                if ($rule_value['price_type'] == 'jian') {
                    if (($number - $mutexNumber) >= $rule_value['enough_price']) {
                        foreach ($rule_value['gift_group'] as $group_key => $group_value) {
                            $goodsStock = M('goods')->where(array('id'=> $group_value['gift_goodsId']))->getField('goods_number');

                            if ($goodsStock > 0) {
                                $goodsDetail[$group_key]['goodsId']     = $group_value['gift_goodsId'];
                                $goodsDetail[$group_key]['goodsNum']    = $group_value['gift_num'];      
                                $goodsDetail[$group_key]['gift_name']    = $giftValue['gift_name'];
                            }
                        }
                        break;
                    }
                } else {
                    if (($total - $mutexPrice) >= $rule_value['enough_price']) {
                        foreach ($rule_value['gift_group'] as $group_key => $group_value) {
                            $goodsStock = M('goods')->where(array('id'=> $group_value['gift_goodsId']))->getField('goods_number');

                            if ($goodsStock > 0) {
                                $goodsDetail[$group_key]['goodsId']     = $group_value['gift_goodsId'];
                                $goodsDetail[$group_key]['goodsNum']    = $group_value['gift_num'];      
                                $goodsDetail[$group_key]['gift_name']    = $giftValue['gift_name'];
                            }
                        }
                        break;
                    }
                }
            }   
        } else {
            // 满多少送多少
            if ($ruleValue[0]['price_type'] == 'jian') {
                if (($number - $mutexNumber) >= $ruleValue[0]['enough_price']) {
                    $giftNumber = floor(($number - $mutexNumber) / $ruleValue[0]['enough_price']);
                    foreach($ruleValue[0]['gift_group'] as $group_key => $group_value) {
                        $goodsStock = M('goods')->where(array('id'=> $group_value['gift_goodsId']))->getField('goods_number');

                        if ($goodsStock > 0) {
                            $goodsDetail[$group_key]['goodsId']     = $group_value['gift_goodsId'];
                            $goodsDetail[$group_key]['goodsNum']    = $giftNumber;      
                            $goodsDetail[$group_key]['gift_name']    = $giftValue['gift_name'];
                        }      
                    }
                }
            } else {
                if (($total - $mutexPrice) >= $ruleValue[0]['enough_price']) {
                    $giftNumber = floor(($total - $mutexNumber) / $ruleValue[0]['enough_price']);
                    foreach($ruleValue[0]['gift_group'] as $group_key => $group_value) {
                        $goodsStock = M('goods')->where(array('id'=> $group_value['gift_goodsId']))->getField('goods_number');

                        if ($goodsStock > 0) {
                            $goodsDetail[$group_key]['goodsId']     = $group_value['gift_goodsId'];
                            $goodsDetail[$group_key]['goodsNum']    = $giftNumber;      
                            $goodsDetail[$group_key]['gift_name']    = $giftValue['gift_name'];
                        }      
                    }
                }
            }
        }
        return $goodsDetail;
    }
   
    // 获取包邮优惠
    // public function getExpressFree($value){
    //     $expressFree = 0; /*是否包邮*/
    //     // 包邮活动构成------开始
    //     $expressInfo = $this->expressInfo();
    //     // 包邮活动构成------结束
    //     if (empty($expressFree)) {
    //         //dump($value['multiple_total']);
    //         $expressInfo = $this->expressFree($expressInfo, $value);
    //         if ($expressInfo === TRUE) {
    //             $expressFree = 1;
    //             // return $expressFree;
    //             // $this->assign('expressFree', $expressFree);
    //         }
    //         // return null;
    //     }
    // }
    // 获取包邮优惠
    public function getexpressInfo() {
        $expressInfo = $this->expressInfo();
        return $expressInfo;
    }
    //判断检查包邮（循环）
    public function getExpressFree($expressFree,$value,$couponDetail){
        self::$couponDetail = $couponDetail;
        if (empty($expressFree)) {
            //dump($value['multiple_total']);
            $expressInfo = $this->expressFree($expressInfo, $value);
            if ($expressInfo === TRUE) {
                $expressFree = 1;
                // return $expressFree;
                $this->assign('expressFree', $expressFree);
            }
            // return null;
        }
    }
    /**
     * [expressInfo 包邮结构]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function expressInfo() {
        // 读取包邮活动表
        $now = time();
        $agentId = $this->agentId;
        $expressFreeData = M('express_free')->where(array('start_time' => array('elt', $now), 'end_time' => array('gt', $now),'is_delete'=>0),'agent_id'=>$agentId)->select();
        $expressInfo = array(
            '1' => array(),
            '2' => array(),
            '3' => array(),
            '4' => array(),
        );
        foreach ($expressFreeData as $key => $value) {
            switch ($value['type']) {
                case '4':
                    // 单品包邮
                    $temp = array('data'=>explode(',', $value['rule_value']));
                    $temp['condition'] = $value['condition'];
                    $temp['count'] = 0;
                    $expressInfo['4'][$value['unit']][] = $temp;
                    break;
                case '3':
                    // 品牌包邮
                    $temp = array('data'=>explode(',', $value['rule_value']));
                    $temp['condition'] = $value['condition'];
                    $temp['count'] = 0;
                    $expressInfo['3'][$value['unit']][] = $temp;
                    break;
                case '2':
                    // 分类包邮
                    $temp = array('data'=>explode(',', $value['rule_value']));
                    $temp['condition'] = $value['condition'];
                    $temp['count'] = 0;
                    $expressInfo['2'][$value['unit']][] = $temp;
                    break;
                case '1':
                    // 全场包邮
                    if ( empty($temp) || $value['condition'] < $temp ) {
                        $temp = array('data'=>$value['condition']);
                    }
                    $temp['condition'] = $value['condition'];
                    $temp['count'] = 0;
                    $expressInfo['1'][$value['unit']] = $temp;
                    break;
            }
        }
        // die();
        return $expressInfo;
    }
    /**
     * [expressFree 包邮逻辑]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)          2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $expressInfo  [description]
     * @param     [type]        $goodsInfo    [description]
     * @return    [type]                      [description]
     */
    public function expressFree($expressInfo, $goodsInfo) {
        //单品满钱
        if ( isset($expressInfo['4']['0']) ) {
            foreach ($expressInfo['4']['0'] as $key => $value) {
                $checked = in_array($goodsInfo['id'], $value['data']);
                if ( $checked ) {
                    $expressInfo['4']['0'][$key]['count'] += $goodsInfo['single_total'];
                    // 是否有使用优惠券 start
                    $couponPrice = 0;
                    if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 4) && (strpos(self::$couponDetail['condition'], ','.$goodsInfo['id'].',') !== false)) {
                        $couponPrice = self::$couponDetail['coupon_value'];
                    } else if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 1)) {
                        $couponPrice = self::$couponDetail['coupon_value'];
                    }

                    $expressInfo['4']['0'][$key]['count'] -= $couponPrice;
                    self::$couponDetail = null;
                    // end
                    // echo '4m';
                    if ( $expressInfo['4']['0'][$key]['count'] >= $expressInfo['4']['0'][$key]['condition']) {
                        $this->assign('exprePrice', $expressInfo['1']['0']['condition']);
                        return TRUE;
                    }
                }
            }
        }
        //单品满件
        if ( isset($expressInfo['4']['1']) ) {
            foreach ($expressInfo['4']['1'] as $key => $value) {
                $checked = in_array($goodsInfo['id'], $value['data']);
                if ( $checked ) {
                    $expressInfo['4']['1'][$key]['count'] += $goodsInfo['goods_number'];
                    if ( $expressInfo['4']['1'][$key]['count'] >= $expressInfo['4']['1'][$key]['condition']){
                        return TRUE;
                    }
                }
            }
        }
        //品牌满钱
        if ( isset($expressInfo['3']['0']) ) {
            foreach ($expressInfo['3']['0'] as $key => $value) {
                $checked = in_array($goodsInfo['brand_id'], $value['data']);
                // 是否有使用优惠券 start
                $couponPrice = 0;
                if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 3) && (strpos(self::$couponDetail['condition'], ','.$goodsInfo['brand_id'].',') !== false)) {
                    $couponPrice = self::$couponDetail['coupon_value'];
                } else if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 1)) {
                    $couponPrice = self::$couponDetail['coupon_value'];
                }
                // end
                // echo '3m';
                if ( $checked ) {
                    $expressInfo['3']['0'][$key]['count'] += $goodsInfo['single_total'];
                    $expressInfo['3']['0'][$key]['count'] -= $couponPrice;
                    self::$couponDetail = null;
                    if ( $expressInfo['3']['0'][$key]['count'] >= $expressInfo['3']['0'][$key]['condition']) {
                        return TRUE;
                    }
                }
            }
        }
        //品牌满件
        if ( isset($expressInfo['3']['1']) ) {
            foreach ($expressInfo['3']['1'] as $key => $value) {
                $checked = in_array($goodsInfo['brand_id'], $value['data']);
                if ( $checked ) {
                    $expressInfo['3']['1'][$key]['count'] += $goodsInfo['goods_number'];
                    if ( $expressInfo['3']['1'][$key]['count'] >= $expressInfo['3']['1'][$key]['condition']) {
                        return TRUE;
                    }
                }
            }
        }
        //分类满钱
        if ( isset($expressInfo['2']['0']) ) {
            foreach ($expressInfo['2']['0'] as $key => $value) {
                $category_arr = explode(',', $goodsInfo['category_path']);
                $checked = array_intersect($category_arr, $value['data']);
                // 是否有使用优惠券 start
                $couponPrice = 0;
                if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 2) && (in_array(self::$couponDetail['condition'], $category_arr))) {
                    $couponPrice = self::$couponDetail['coupon_value'];
                } else if (!empty(self::$couponDetail) && (self::$couponDetail['type'] == 1)) {
                    $couponPrice = self::$couponDetail['coupon_value'];
                }

                // echo '2m';
                if ( !empty($checked) ) {
                    $expressInfo['2']['0'][$key]['count'] += $goodsInfo['single_total'];
                    $expressInfo['2']['0'][$key]['count'] -= $couponPrice;
                    self::$couponDetail = null;
                    if ( $expressInfo['2']['0'][$key]['count'] >= $expressInfo['2']['0'][$key]['condition']) {
                        return TRUE;
                    }
                }
            }
        }
        //分类满件
        if ( isset($expressInfo['2']['1']) ) {
            foreach ($expressInfo['2']['1'] as $key => $value) {
                $checked = array_intersect(explode(',', $goodsInfo['category_path']), $value['data']);
                if ( !empty($checked) ) {
                    $expressInfo['2']['1'][$key]['count'] += $goodsInfo['goods_number'];
                    if ( $expressInfo['2']['1'][$key]['count'] >= $expressInfo['2']['1'][$key]['condition']) {
                        return TRUE;
                    }
                }
            }
        }
        //全场满钱
        if (isset($expressInfo['1']['0'])) {
            $expressInfo['1']['0']['count'] += $goodsInfo['multiple_total'];
            // 是否有使用优惠券 start
            $couponPrice = 0;
            if (!empty(self::$couponDetail)) {
                $couponPrice = self::$couponDetail['coupon_value'];
            }
            // end
            // echo "1m\n";
            $expressInfo['1']['0']['count'] -= $couponPrice;
            self::$couponDetail = null;
            if ( $expressInfo['1']['0']['count'] >= $expressInfo['1']['0']['condition']) {
                $this->assign('exprePrice',$expressInfo['1']['0']['condition']);
                return TRUE;
            }
        }
        //全场满件
        if (isset($expressInfo['1']['1'])) {
            $expressInfo['1']['1']['count'] += $goodsInfo['goods_number'];
            if ( $expressInfo['1']['1']['count'] >= $expressInfo['1']['1']['condition']) {
                return TRUE;
            }
        }
        return $expressInfo;
    }

    //获取购物车信息
    public function getParameter($goodsId,$goodsNumber,$brandId,$category_path,$total){
        // $goodsIdArr = array();
        // $goodsArr = array();  /*商品信息*/
        // $brandArr = array();  /*品牌信息*/
        // $catesArr = array();  /*分类信息*/
        // 购物车里面所有商品的总价
        if ($goodsArr[$goodsId]) {
            $goodsArr[$goodsId]['total']    += $total;
            $goodsArr[$goodsId]['number']   += $goodsNumber;
        } else {
            $goodsArr[$goodsId]['total']    = $total; 
            $goodsArr[$goodsId]['number']   = $goodsNumber;
        }

        // 购物车里面所有品牌的总价
        if ($brandArr[$brandId]) {
            $brandArr[$brandId]['total']  += $total;
            $brandArr[$brandId]['number'] += $goodsNumber;
        } else {
            $brandArr[$brandId]['total']  = $total; 
            $brandArr[$brandId]['number'] = $goodsNumber;
        }

        // 购物车里面所有商品分类的总价
        if ($catesArr[',' .$category_path. ',']) {
            $catesArr[',' .$category_path. ',']['total']   += $total;
            $catesArr[',' .$category_path. ',']['number']  += $goodsNumber;
        } else {
            $catesArr[',' .$category_path. ',']['total']   = $total; 
            $catesArr[',' .$category_path. ',']['number']  = $goodsNumber;
        }
        $goodsIdArr[] = $goodsId;
        return array($goodsArr,$brandArr,$catesArr,$goodsIdArr);
    }


    public function getGiftList ($goodsArr,$brandArr,$catesArr,$single_price,$number='0',$goodIdArr) {
        $parameter['goodsArr'] = $goodsArr;
        $parameter['brandArr'] = $brandArr;
        $parameter['catesArr'] = $catesArr;
        $giftList = $this->checkGift($parameter, $single_price, $number, $goodIdArr);  // 获取有效的满赠商品
        return $giftList;

    }
    
   
    /**
     * [returnOffers 返回互斥商品]
     * @author Fu <[418382595@qq.com]>
     */
    public function returnOffers($goodsArr) {
        $now = time();
        $incompatibleArr = array();
        $offers = M('limit_offers');
        $offersList = $offers->where(array('start_time'=> array('lt', $now), 'end_time'=> array('gt', $now), 'is_delete'=> 0,'agent_id'=>$this->agentId))->select();
        if (!empty($offersList)) {
            foreach ($offersList as $offers_key => &$offers_value) {
                if ($offers_value['incompatible_activities'] !== '') {
                    $offers_value['goods_value']    = json_decode($offers_value['goods_value'], true);
                    if (strpos($offers_value['incompatible_activities'], '0') !== false && strpos($offers_value['incompatible_activities'], '1') !== false) {
                        foreach ($offers_value['goods_value'] as $goods_key => &$goods_value) {
                            if (in_array($goods_key, $goodsArr)) {
                                $goodsInfo = M('goods')->where(array('id'=> $goods_key))->field('brand_id, category_path')->find();
                                $goods_value['brand_id']        = $goodsInfo['brand_id'];
                                $goods_value['category_path']   = $goodsInfo['category_path'];

                                if ($offers_value['preferential_type'] == 1) {
                                    $goods_value['discount'] = $goods_value['discount_price'];
                                } else {
                                    $goods_value['discount'] = sprintf("%.2f", $goods_value['discount'] / 100 * $goods_value['goods_price']);
                                }

                                $incompatibleArr['couponGoods'][$goods_key] = $goods_value;        
                                $incompatibleArr['giftGoods'][$goods_key] = $goods_value;        
                            }
                        }    
                    } elseif (strpos($offers_value['incompatible_activities'], '0') !== false) {
                        foreach ($offers_value['goods_value'] as $goods_key => &$goods_value) {
                            if (in_array($goods_key, $goodsArr)) {
                                $goodsInfo = M('goods')->where(array('id'=> $goods_key))->field('brand_id, category_path')->find();
                                $goods_value['brand_id']        = $goodsInfo['brand_id'];
                                $goods_value['category_path']   = $goodsInfo['category_path'];

                                if ($offers_value['preferential_type'] == 1) {
                                    $goods_value['discount'] = $goods_value['discount_price'];
                                } else {
                                    $goods_value['discount'] = sprintf("%.2f", $goods_value['discount'] / 100 * $goods_value['goods_price']);
                                }

                                $incompatibleArr['couponGoods'][$goods_key] = $goods_value;  
                            }      
                        }
                    } elseif (strpos($offers_value['incompatible_activities'], '1') !== false) {
                        foreach ($offers_value['goods_value'] as $goods_key => &$goods_value) {
                            if (in_array($goods_key, $goodsArr)) {
                                $goodsInfo = M('goods')->where(array('id'=> $goods_key))->field('brand_id, category_path')->find();
                                $goods_value['brand_id']        = $goodsInfo['brand_id'];
                                $goods_value['category_path']   = $goodsInfo['category_path'];

                                if ($offers_value['preferential_type'] == 1) {
                                    $goods_value['discount'] = $goods_value['discount_price'];
                                } else {
                                    $goods_value['discount'] = sprintf("%.2f", $goods_value['discount'] / 100 * $goods_value['goods_price']);
                                }

                                $incompatibleArr['giftGoods'][$goods_key] = $goods_value;   
                            }     
                        }
                    }
                }
            }
        }
        return $incompatibleArr;
    }


}