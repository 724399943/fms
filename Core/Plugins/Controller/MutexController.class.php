<?php
namespace Plugins\Controller;
use Think\Controller;

class MutexController extends Controller {
	public function __construct() {
		parent::__construct();
	}

    /**
     * [returnMutexPrice 返回互斥价格]
     * @author Fu <[418382595@qq.com]>
     */
    public function returnMutexPrice($coupon, $parameter, $total, $goodsArr) {
        return 0;
        $offersList = $this->returnOffers($goodsArr);// 互斥商品
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

    
    /**
     * [returnOffers 返回互斥商品]
     * @author Fu <[418382595@qq.com]>
     */
    public function returnOffers($goodsArr) {
        $now = time();
        $incompatibleArr = array();
        $offers = M('limit_offers');
        $offersList = $offers->where(array('start_time'=> array('lt', $now), 'end_time'=> array('gt', $now), 'is_delete'=> 0))->select();
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