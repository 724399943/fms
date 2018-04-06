<?php
namespace Plugins\Coupon;
use Think\Controller;
use Plugins\Controller\MutexController;
use Think\Log;
use Plugins\Gift\Gift;

/**
 * 优惠券
 */
class Coupon extends MutexController {
    static $couponDetail;
    private $Gift;
    public function __construct() {
    	parent::__construct();
        $this->Gift = new Gift();
    }

    /**
     * [addCoupon 添加优惠券]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function addCoupon() {
        
    }

    /**
     * [usedCoupon 使用优惠券]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)       2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $couponSn [description]
     * @param     [type]        $userId   [description]
     * @param     [type]        $order_sn [description]
     * @return    [type]                  [description]
     */
    public function usedCoupon($couponSn, $userId, $order_sn) {
    	$nowTime = time();
    	$couponRecord = M('coupon_record');
        $couponId = $couponRecord->where(array('user_id'=>$userId,'coupon_sn'=>$couponSn, 'is_used'=>0))->getField('id');
        M('coupon_record')->where(array('id'=>$couponId))->save(array('is_used'=>1, 'used_time'=>$nowTime, 'order_sn'=>$order_sn));
    }

    /**
     * [cleanExpiredCoupon 清除已过期优惠劵]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function cleanExpiredCoupon($userId) {
    	$db_prefix = C('DB_PREFIX');
    	$nowTime = time();
    	$sql ="UPDATE `{$db_prefix}coupon` AS `c` 
				LEFT JOIN `{$db_prefix}coupon_record` AS `r` ON `c`.`coupon_sn` = `r`.`coupon_sn` 
				SET `r`.`is_overdue` = '1' 
				WHERE `r`.`user_id` = '{$userId}' AND `r`.`is_used` = '0' AND `r`.`is_overdue` = '0' AND `c`.`end_use_time` < {$nowTime}";
	    $data = M()->execute($sql); //清除已过期优惠券
	    if ($data !== false) {
		    return true;
	    } else {
	    	return false;
	    }
    }

    /**
     * [couponInfo 获取当前优惠券信息]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)       2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $couponSn [description]
     * @param     [type]        $userId   [description]
     * @return    [type]                  [description]
     */
    public function couponInfo($couponSn, $userId) {
    	$db_prefix = C('DB_PREFIX');
    	$this->cleanExpiredCoupon($userId);
    	if (!empty($couponSn)) {
		    $sql = "SELECT `c`.`coupon_sn`, `c`.`coupon_value`, `c`.`type`, `c`.`condition`, `c`.`coupon_name`, `c`.`min_cost`, `r`.`user_id` 
					FROM `{$db_prefix}coupon` AS `c` 
					LEFT JOIN {$db_prefix}coupon_record AS `r` ON `c`.`coupon_sn` = `r`.`coupon_sn` 
					WHERE `c`.`coupon_sn` = {$couponSn} AND `c`.`is_delete`= '0' AND `r`.`is_used` = '0' AND `r`.`user_id` = '{$userId}' AND `r`.`is_overdue` = '0'
					LIMIT 1";
			$couponInfo = M()->query($sql); //优惠券详情
			if ( !empty($couponInfo) ) {
				$return = $couponInfo['0'];
			} else {
				$return = false;
			}
			return $return;
		}
    }

    /**
     * [couponList 获取用户优惠券列表]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)     2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $userId [description]
     * @return    [type]                [description]
     */
    public function couponList($userId) {
    	$dbPrefix = C('DB_PREFIX');
    	$this->cleanExpiredCoupon($userId);
    	$sql = "SELECT `c`.`coupon_sn`, `c`.`coupon_name`, `c`.`coupon_value`, `c`.`min_cost`, `c`.`end_use_time`, `c`.`type`, `c`.`condition`, `r`.`id` AS `recoid_id`
				FROM `{$dbPrefix}coupon` AS `c`
				LEFT JOIN `{$dbPrefix}coupon_record` AS `r` ON `r`.`coupon_sn`=`c`.`coupon_sn` 
				WHERE `c`.`is_delete`= '0' AND `r`.`user_id`=' {$userId}' AND `r`.`is_used` = '0' AND `r`.`is_overdue` = '0'";
		$couponList = M()->query($sql); //优惠券详情
		return $couponList;
    }

    public function checkCanUse($couponInfo, $total_price, $goodsArr=array()) {
    	if ($total_price >= $couponInfo['min_cost']) {
            $mutexPrice = 0;
            switch ($couponInfo['type']) {
	            //类型（1：按用户发放，2：按商品发放，3：按品牌发放，4：按订单金额发放）
                case '4':
                    $condition  = $couponInfo['condition'];
                    foreach ($goodsArr as $goods_key => $goods_value) {
                        $mutexPrice = $this->returnMutexPrice($couponInfo, $goods_key, $goods_value['total'], $goodIdArr); 
                        if (strpos($condition, ','. $goods_key .',') !== false && ($goods_value['total'] - $mutexPrice) >= $couponInfo['min_cost']) {
                            $isCanUse = true;
                            break;
                        } else {
                            $isCanUse = false;
                        }
                    }
                    break;
                case '3':
                    $condition = $couponInfo['condition'];
                    foreach ($brandArr as $brand_key => $brand_value) {
                        $mutexPrice = $this->returnMutexPrice($couponInfo, $brand_key, $brand_value['total'], $goodIdArr); 
                        if (strpos($condition, ','. $brand_key . ',') !== false && ($brand_value['total'] - $mutexPrice) >= $couponInfo['min_cost']) {
                            $isCanUse = true;
                            break;
                        } else {
                            $isCanUse = false;
                        }
                    }
                    break;
                case '2':
                    $condition = ',' . $couponInfo['condition'] . ',';
                    foreach ($catesArr as $cates_key => $cates_value) {
                        $mutexPrice = $this->returnMutexPrice($couponInfo, $cates_key, $cates_value['total'], $goodIdArr); 
                        
                        if (strpos($cates_key, $condition) !== false && ($cates_value['total'] - $mutexPrice) >= $couponInfo['min_cost']) {
                            $isCanUse = true;
                            break;
                        } else {
                            $isCanUse = false;
                        }
                    }
                    break;
                default:
                    $mutexPrice = $this->returnMutexPrice($couponInfo, $goodsArr, $goodsArr, $goodIdArr);

                    if (($total_price - $mutexPrice) >= $couponInfo['min_cost']) {
                        $isCanUse = true;
                    } else {
                        $isCanUse = false;
                    }
                    break;
            }
            
            if ($isCanUse !== false) {
                $couponPrice = $couponInfo['coupon_value'];
                $total_price = $total_price - $couponPrice;
                $result = $total_price;   
            } else {
                $result = false;    
            }
        } else {
            $result = false;
        }
        return $result;
    }
    //更新优惠卷
    public function setCoupon($couponSn) {
        if (!empty($couponSn)) {
            $sql ="UPDATE `{$db_prefix}coupon` AS `c` LEFT JOIN `{$db_prefix}coupon_record` AS `r` ON `c`.`coupon_sn` = `r`.`coupon_sn` SET `r`.`is_overdue` = 1 WHERE `r`.`user_id` = {$userId} AND `r`.`is_used` = 0 AND `r`.`is_overdue` = 0 AND `c`.`end_use_time` < {$now}";
            M()->execute($sql);

            $sql = "SELECT `c`.`coupon_sn`, `c`.`coupon_value`, `c`.`type`, `c`.`condition`, `c`.`coupon_name`, `c`.`min_cost`, `r`.`user_id` FROM {$db_prefix}coupon AS `c` LEFT JOIN {$db_prefix}coupon_record AS `r` ON `c`.`coupon_sn` = `r`.`coupon_sn` WHERE `c`.`coupon_sn` = {$couponSn} AND `c`.`is_delete`=0 AND `r`.`is_used` = 0 AND `r`.`user_id` = {$userId} AND `r`.`is_overdue` = 0";
            $couponInfo = M()->query($sql);
        }
        return $couponInfo;
    }
    //检查判断优惠劵
    public function checkCoupon($couponSn,$couponInfo,$total_price,$goodsArr,$brandArr,$catesArr) {
        self::$couponDetail = empty($couponInfo[0]) ? null : $couponInfo[0];
        // 如果有选择优惠券进行判断
        if (!empty($couponSn)) {

            if (!empty($couponInfo)) {
                if ($total_price >= $couponInfo[0]['min_cost']) {
                    $mutexPrice = 0;
                    switch ($couponInfo[0]['type']) {
                        case '4':
                            $condition  = $couponInfo[0]['condition'];
                            foreach ($goodsArr as $goods_key => $goods_value) {
                                $mutexPrice = $this->returnMutexPrice($couponInfo[0], $goods_key, $goods_value['total'], $goodIdArr); 
                                if (strpos($condition, ','. $goods_key .',') !== false && ($goods_value['total'] - $mutexPrice) >= $couponInfo[0]['min_cost']) {
                                    $isCanUse = true;
                                    break;
                                } else {
                                    $isCanUse = false;
                                }
                            }
                            break;
                        case '3':
                            $condition = $couponInfo[0]['condition'];
                            foreach ($brandArr as $brand_key => $brand_value) {
                                $mutexPrice = $this->returnMutexPrice($couponInfo[0], $brand_key, $brand_value['total'], $goodIdArr); 
                                if (strpos($condition, ','. $brand_key . ',') !== false && ($brand_value['total'] - $mutexPrice) >= $couponInfo[0]['min_cost']) {
                                    $isCanUse = true;
                                    break;
                                } else {
                                    $isCanUse = false;
                                }
                            }
                            break;
                        case '2':
                            $condition = ',' . $couponInfo[0]['condition'] . ',';
                            foreach ($catesArr as $cates_key => $cates_value) {
                                $mutexPrice = $this->returnMutexPrice($couponInfo[0], $cates_key, $cates_value['total'], $goodIdArr); 
                                
                                if (strpos($cates_key, $condition) !== false && ($cates_value['total'] - $mutexPrice) >= $couponInfo[0]['min_cost']) {
                                    $isCanUse = true;
                                    break;
                                } else {
                                    $isCanUse = false;
                                }
                            }
                            break;
                        default:
                            $mutexPrice = $this->returnMutexPrice($couponInfo[0], $goodsArr, $goodsArr, $goodIdArr);

                            if (($total_price - $mutexPrice) >= $couponInfo[0]['min_cost']) {
                                $isCanUse = true;
                            } else {
                                $isCanUse = false;
                            }
                            break;
                    }
                    
                    if ($isCanUse !== false) {
                        $couponPrice = $couponInfo[0]['coupon_value'];
                        $total_price = $total_price - $couponPrice;
                    } else {
                        $this->error('未能使用该优惠券，请重新选择优惠劵！');
                        $result = false;   
                        // return array('err'=>false,'str'=>'未能使用该优惠券，请重新选择优惠劵！');
                    }
                } else {
                    $this->error('未能使用该优惠券，请重新选择优惠劵！');
                    $result = false;
                    // return array('err'=>false,'str'=>'未能使用该优惠券，请重新选择优惠劵！');
                }
            } else {
                $this->error('该优惠券已失效，请重新选择优惠券！');
                $resutl = false;
                // return array('err'=>false,'str'=>'该优惠券已失效，请重新选择优惠券！');
            }
        }
    }

    /**
     * [returnMutexPrice 返回互斥价格]
     * @author Fu <[418382595@qq.com]>
     */
    public function returnMutexPrice($coupon, $parameter, $total, $goodsArr) {
        $offersList = $this->Gift->returnOffers($goodsArr);// 互斥商品
        $mutexPrice = 0;

        if (!empty($offersList) && !empty($offersList['couponGoods'])) {
            switch ($coupon['type']) {
                case '4' :
                    if (array_key_exists($parameter, $offersList['couponGoods'])) {
                        $mutexPrice = $total;
                    } else {
                        $mutexPrice = 0;
                    }
                    break;
                case '3' :
                    foreach ($offersList['couponGoods'] as $offers_key => $offers_value) {
                        if ($offers_value['brand_id'] == $parameter) {
                            $mutexPrice += $total;
                        } else {
                            $mutexPrice = 0;
                        }
                    }
                    break;
                case '2' :
                    foreach ($offersList['couponGoods'] as $offers_key => $offers_value) {
                        if (',' . $offers_value['category_path'] . ',' == $parameter) {
                            $mutexPrice += $total;
                        } else {
                            $mutexPrice = 0;
                        }
                    }
                    break;
                default:
                    foreach ($parameter as $p_key => $p_value) {
                        if (array_key_exists($p_key, $offersList['couponGoods'])) {
                            $mutexPrice += $p_value['total'];
                        } else {
                            $mutexPrice += 0;
                        }
                    }
                    break;
            } 
        } else {
            $mutexPrice = 0;
        }
        return $mutexPrice;
    }
}