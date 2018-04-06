<?php
namespace Plugins\LimitOffers;
use Think\Controller;
use Think\Log;

/**
 * 优惠券
 */
class LimitOffers extends Controller {
    public function __construct() {
    	parent::__construct();
    }

    /**
     * [limitOffersList 限时活动列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function limitOffersList() {
        $offers  = M("limit_offers");
        $count = $offers->where(array('is_delete'=> 0))->count();
        $Page = new \Think\Page($count, 25);
        $show = $Page->show();
        $counting=$Page->totalRows;
        $offersList = $offers->where(array('is_delete'=> 0))->limit($Page->firstRow , $Page->listRows)->order('add_time desc')->select();
        $this->assign('offersList', $offersList);
        $this->assign('show', $show);
        $this->assign('counting', $counting);
        $this->display('limitOffersList');
    }

    /**
     * [addLimitOffers 添加限时活动]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function addLimitOffers() {
        if (IS_POST) {
            $offers = D('LimitOffers');
            $data = $offers->create(I('post.'), 1);

            if (empty($data)) {
                $this->error($offers->getError());
            } else {
                $activities = I('post.incompatible_activities', '');  /*互斥活动*/
                if (!empty($activities)) $activities = implode(',', $activities);   
                $data['incompatible_activities'] = $activities;

                /*商品列表*/
                $goodsId  = I('post.goodsId', '');
                $discount = I('post.discount', '');
                $price    = I('post.discountPrice', '');
                $now = time();

                $offersList = M('limit_offers')->where(array('end_time'=>array('gt', $now), 'is_delete'=>0))->select();
                $goodsArr = '';
                foreach($offersList as $offers_key => $offers_value) {
                    $goodsArr .= $offers_value['condition'];
                }

                $list = array();
                switch ($data['preferential_type']) {
                    case '1':
                        foreach ($price as $price_key => $price_value) {
                            if (empty($price_value)) {
                                $this->error('商品ID：'. $goodsId[$price_key] .'未填写促销价，请重新填写！');
                            } elseif (strpos($goodsArr, $goodsId[$price_key]) !== false) {
                                $this->error('商品ID：'. $goodsId[$price_key] .'在同限时优惠时间不可重复！');
                            } else {
                                $goodsInfo = M('goods')->where(array('id'=> $goodsId[$price_key]))->field('id, goods_name, goods_price')->find();
                                $list[$goodsId[$price_key]]['discount_price'] = $price_value;
                                $list[$goodsId[$price_key]]['goods_name']   = $goodsInfo['goods_name'];
                                $list[$goodsId[$price_key]]['goods_price']  = $goodsInfo['goods_price'];
                            }
                        }
                        break;
                    default:
                        foreach ($discount as $dic_key => $dic_value) {
                            if (empty($dic_value)) {
                                $this->error('商品ID：'. $goodsId[$dic_key] .'未填写折扣，请重新填写！');
                            } elseif (strpos($goodsArr, $goodsId[$dic_key]) !== false) {
                                $this->error('商品ID：'. $goodsId[$dic_key] .'在同限时优惠时间不可重复！');
                            } else {
                                $goodsInfo = M('goods')->where(array('id'=> $goodsId[$dic_key]))->field('id, goods_name, goods_price')->find();
                                $list[$goodsId[$dic_key]]['discount'] = $dic_value;
                                $list[$goodsId[$dic_key]]['goods_name']   = $goodsInfo['goods_name'];
                                $list[$goodsId[$dic_key]]['goods_price']  = $goodsInfo['goods_price'];
                            }
                        }
                        break;
                }

                $data['goods_value'] = json_encode($list);
                $offersId = $offers->add($data);
                if ($offersId) {
                    $this->success('添加成功！', U('Promotion/limitOffersList'));
                } else {
                    $this->error('添加失败！');
                }
            }
        } else {
            $goodsList = M('goods')->where(array('is_on_sale'=>1))->field('id, goods_name, goods_price')->order('id DESC')->select();
            $category = M('goods_category')->where(array('level'=>1))->field('id, category_name')->select();

            $this->assign('category', $category);
            $this->assign('goodsList',$goodsList);
            $this->display();
        }
    }

    /**
     * [editLimitOffers 编辑限时优惠]
     * @author Fu <[418382595@qq.com]>
     */
    public function editLimitOffers() {
        if (IS_POST) {
            $offersId = I('post.offersId', '');
            $offers = D('LimitOffers');
            $data = $offers->create(I('post.'), 2);

            if (empty($data)) {
                $this->error($offers->getError());
            } else {
                $offersInfo = $offers->where(array('id'=> $offersId))->field('start_time, end_time')->find();
                $now = time();
                if ($offersInfo['start_time'] < $now && $offersInfo['end_time'] > $now) {
                    $this->error('活动运营期间不能编辑！');
                }

                $activities = I('post.incompatible_activities', '');  /*互斥活动*/
                if (!empty($activities)) $activities = implode(',', $activities);   
                $data['incompatible_activities'] = $activities;

                /*商品列表*/
                $goodsId  = I('post.goodsId', '');
                $discount = I('post.discount', '');
                $price    = I('post.discountPrice', '');

                $list = array();
                switch ($data['preferential_type']) {
                    case '1':
                        foreach ($price as $price_key => $price_value) {
                            if (empty($price_value)) {
                                $this->error('商品ID：'. $goodsId[$price_key] .'未填写优惠价，请重新填写！');
                            } else {
                                $goodsInfo = M('goods')->where(array('id'=> $goodsId[$price_key]))->field('id, goods_name, goods_price')->find();
                                $list[$goodsId[$price_key]]['discount_price'] = $price_value;
                                $list[$goodsId[$price_key]]['goods_name']   = $goodsInfo['goods_name'];
                                $list[$goodsId[$price_key]]['goods_price']  = $goodsInfo['goods_price'];
                            }
                        }
                        break;
                    default:
                        foreach ($discount as $dic_key => $dic_value) {
                            if (empty($dic_value)) {
                                $this->error('商品ID：'. $goodsId[$dic_key] .'未填写折扣，请重新填写！');
                            } else {
                                $goodsInfo = M('goods')->where(array('id'=> $goodsId[$dic_key]))->field('id, goods_name, goods_price')->find();
                                $list[$goodsId[$dic_key]]['discount'] = $dic_value;
                                $list[$goodsId[$dic_key]]['goods_name']   = $goodsInfo['goods_name'];
                                $list[$goodsId[$dic_key]]['goods_price']  = $goodsInfo['goods_price'];
                            }
                        }
                        break;
                }

                $data['goods_value'] = json_encode($list);
                if ($offers->where(array('id'=> $offersId))->save($data) !== false) {
                    $this->success('编辑成功！', U('Promotion/limitOffersList'));
                } else {
                    $this->error('编辑失败！');
                }
            }
        } else {
            $id = I('get.id', '');
            if (empty($id)) {
                $this->error('参数丢失！');
            }
            $offersInfo = M('limit_offers')->where(array('id'=> $id))->find();
            $offersInfo['condition']    = explode(',', trim($offersInfo['condition'], ','));
            $offersInfo['goods_value']  = json_decode($offersInfo['goods_value'], true); 
            $offersInfo['incompatible_activities']    = explode(',', $offersInfo['incompatible_activities']);

            $goodsList = M('goods')->where(array('is_on_sale'=>1))->field('id, goods_name, goods_price')->order('id DESC')->select();
            $category = M('goods_category')->where(array('level'=>1))->field('id, category_name')->select();
            $this->assign('offersInfo', $offersInfo);
            $this->assign('goodsList', $goodsList);
            $this->assign('category', $category);
            $this->display();
        }
    }

    /**
     * [delLimitOffers 删除限时优惠]
     * @author Fu <[418382595@qq.com]>
     */
    public function delLimitOffers() {
        $id       = I('get.id', '');
        $offers   = M("limit_offers");

        if (empty($id)) {
            $this->error('参数丢失！');
        }

        if($offers->where(array('id'=>$id))->save(array('is_delete' =>1)) !== false) {
            $this->success('删除成功', U('Promotion/limitOffersList'));
        } else {
            $this->error('删除失败', U('Promotion/limitOffersList'));
        } 
    }

    //获取限时优惠信息
    public function getOffersGoodsList() {
        $offers = M('limit_offers');
        $agentId   = $this->agentId;
        $offersList = $offers->where(array('start_time'=> array('lt',time()), 'end_time'=> array('gt', time()), 'is_delete'=> 0,'agent_id'=>$agentId))->select();
        // dump($offersList);
        $offersGoodsList = array();
        if (!empty($offersList)) {
            foreach ($offersList as $offers_key => &$offers_value) {
                // $offers_value['condition']      = explode(',', trim($offers_value['condition'], ','));
                $offers_value['goods_value']    = json_decode($offers_value['goods_value'], true);
                foreach ($offers_value['goods_value'] as $goods_key => $goods_value) {
                    $offersGoodsList[$goods_key] = $goods_value;      
                    $offersGoodsList[$goods_key]['preferential_type'] = $offers_value['preferential_type'];
                    $offersGoodsList[$goods_key]['start_time'] =  $offers_value['start_time'];    
                    $offersGoodsList[$goods_key]['end_time'] =  $offers_value['end_time'];    
                }
            }
        }
        return $offersGoodsList;
    }
    //得到限时优惠价格（循环）
    public function getLimitOffers($offersGoodsList,$goodsId,$goodsPrice){
        if ($offersGoodsList[$goodsId]['preferential_type'] == 1) {
            $discount = $offersGoodsList[$goodsId]['discount_price'];
        } else {
            $price = $offersGoodsList[$goodsId]['discount'] * $goodsPrice * 0.1;
            $discount = is_float($price) ? substr_replace($price, '', strpos($price, '.') + 3) : $price.'.00';
        }
        return $discount;
    }
    //获取限购信息
    public function getLtdPurList () {
        $agentId = $this->agentId;
        $ltdPurList = M('limited_purchasing')->where("`start_time`<{$now} AND `end_time`>{$now} AND `is_delete`='0' AND `agent_id` = {$agentId}")->select();
        return $ltdPurList;
    }
    // 获取限购活动（循环）
    public function getLimitedLog ($ltdPurList,$value,$sidStr,$isCheckTotal,$activityLog) {
        $userId = session('userId');
        $agentId = $this->agentId;
        $limitedLog = M('limited_log');
        $db_prefix = C('DB_PREFIX');
        // $ltdPurList = M('limited_purchasing')->where("`start_time`<{$now} AND `end_time`>{$now} AND `is_delete`='0' AND `agent_id` = {$agentId}")->select();
        // $isCheckTotal = array(); 已经检验过的限购活动
        // $activityLog = array();

        foreach ($ltdPurList as $lkey => $lvalue) {
            $condition = json_decode($lvalue['condition'], true);
            // 限购商品购买记录
            // $limitedBuyLog = $limitedLog->where(array('activity_id'=>$lvalue['id'], 'user_id'=>$userId, 'is_delete'=>0))->getField('goods_number');
            // $limitedBuyLog = $limitedBuyLog ? $limitedBuyLog : 0;

            switch ($lvalue['type']) {
                case '3':
                    // 品牌限购
                    if ($condition[$value['brand_id']]) {
                        // echo $key.'-'.$lkey;
                        // dump($value['goods_number']);
                        // dump($limitedBuyLog);
                        // dump($condition[$value['brand_id']]['limit_number']);
                        $limitedBuyLog = $limitedLog->where(array('activity_id'=>$lvalue['id'], 'user_id'=>$userId, 'is_delete'=>0, 'brand_id'=>$value['brand_id'], 'add_time'=>array('BETWEEN', array($lvalue['add_time'],$lvalue['end_time'],'agent_id'=>$agentId))))->getField('SUM(goods_number)');
                        $limitedBuyLog = $limitedBuyLog ? $limitedBuyLog : 0;
                        if (($value['goods_number'] + $limitedBuyLog) > $condition[$value['brand_id']]['limit_number']) {
                            $this->error("{$value['goods_name']}超出了限购数量！活动期间限购{$condition[$value['brand_id']]['limit_number']}件");
                        }
                        // $ltdSign = true;
                        // $activityLog[] = $lvalue['id'];
                        // modify by Fu
                        $ltdTemp = array(
                            'activity_id'   => $lvalue['id'],
                            'goods_id'      => $value['goods_id'],
                            'goods_number'  => $value['goods_number'],
                            'brand_id'      => $value['brand_id']
                        );
                        $activityLog[] = $ltdTemp;
                    }

                    if (!$isCheckTotal[$lvalue['id']]) {
                        // 如果设置了活动总限购数量
                        if ($lvalue['limit_number'] != 0) {
                            $brandIds = array_keys($condition);
                            $brandIds = implode(',', $brandIds);
                            if ($lvalue['limit_type'] == 1) {
                                // 按件限制
                                if ($brandIds) {
                                    // $sql = "SELECT SUM(goods_number) AS total FROM {$db_prefix}goods_shopping_cart WHERE brand_id IN({$brandIds}) AND user_id={$userId}";
                                    // modify by Fu
                                    $limitedBuyLog = $limitedLog->where(array('activity_id'=>$lvalue['id'], 'user_id'=>$userId, `agent_id` => $agentId, 'is_delete'=>0, 'brand_id'=> array('IN', $brandIds), 'add_time'=>array('BETWEEN', array($lvalue['add_time'],$lvalue['end_time']))))->getField('SUM(goods_number)');
                                    $limitedBuyLog = $limitedBuyLog ? $limitedBuyLog : 0;

                                    $sql = 'SELECT `SUM(goods_number)` AS `total` FROM '.$db_prefix.'goods_shopping_cart WHERE `brand_id` IN ('.$brandIds.') AND `user_id` = '.$userId.' AND `id` IN ('.$sidStr.') AND `agent_id` = '.$agentId;
                                    $limitTotal = M()->query($sql);
                                    if ($limitTotal[0]['total'] && ($limitTotal[0]['total'] + $limitedBuyLog) > $lvalue['limit_number']) {
                                        $this->error("限购商品数量超出限购活动限制");
                                    }
                                }
                                $isCheckTotal[$lvalue['id']] = true;
                            } else if ($lvalue['limit_type'] == 2) {
                                $sql = 'SELECT `distinct(brand_id)` AS `total` FROM '.$db_prefix.'limited_log WHERE `activity_id` = '.$lvalue['id'].' AND `is_delete` = 0 AND `user_id` = '.$userId.' AND `add_time` BETWEEN '.$lvalue['add_time'].' AND '.$lvalue['end_time'].' AND `agent_id` = '.$agentId;
                                $limitTotal = M()->query($sql);
                                // $sql2 = "SELECT distinct(brand_id) AS total FROM `{$db_prefix}goods_shopping_cart` WHERE brand_id IN({$brandIds}) AND user_id={$userId}";
                                // modify by Fu
                                $sql2 = 'SELECT `distinct(brand_id)` AS total FROM '.$db_prefix.'goods_shopping_cart WHERE `brand_id` IN ('.$brandIds.') AND `user_id` = '.$userId.' AND `id` IN ('.$sidStr.') AND `agent_id` = '.$agentId;
                                $shoppingCartTotal = M()->query($sql2);

                                $limitTotal = array_column($limitTotal, 'total');
                                $shoppingCartTotal = array_column($shoppingCartTotal, 'total');
                                $limitTotal = array_merge($limitTotal, $shoppingCartTotal);
                                $limitTotal = array_unique($limitTotal);
                                if (count($limitTotal) > $lvalue['limit_number']) {
                                    $this->error("限购商品种数超出限购活动限制");
                                }
                            }
                        } else {
                            $isCheckTotal[$lvalue['id']] = true;
                        }
                    }
                    break;
                case '4':
                    // 单品限购
                    if ($condition[$value['goods_id']]) {
                        $limitedBuyLog = $limitedLog->where(array('activity_id'=>$lvalue['id'], 'user_id'=>$userId, `agent_id` => $agentId, 'is_delete'=>0, 'goods_id'=>$value['goods_id'], 'add_time'=>array('BETWEEN', array($lvalue['add_time'], $lvalue['end_time']))))->getField('SUM(goods_number)');
                        $limitedBuyLog = $limitedBuyLog ? $limitedBuyLog : 0;
                        if (($value['goods_number'] + $limitedBuyLog) > $condition[$value['goods_id']]['limit_number']) {
                            $this->error("{$value['goods_name']}超出了限购数量！活动期间限购{$condition[$value['goods_id']]['limit_number']}件");
                        }
                        // $ltdSign = true;
                        // $activityLog[] = $lvalue['id'];
                        // modify by Fu
                        $ltdTemp = array(
                            'activity_id'   => $lvalue['id'],
                            'goods_id'      => $value['goods_id'],
                            'goods_number'  => $value['goods_number'],
                            'brand_id'      => $value['brand_id']
                        );
                        $activityLog[] = $ltdTemp;
                    }

                    if (!$isCheckTotal[$lvalue['id']]) {
                        // 如果设置了活动总限购数量
                        if ($lvalue['limit_number'] != 0) {
                            $goodsIds = array_keys($condition);
                            $goodsIds = implode(',', $goodsIds);

                            if ($lvalue['limit_type'] == 1) {
                                // 按件限制
                                if ($goodsIds) {
                                    // $sql = "SELECT SUM(goods_number) AS total FROM {$db_prefix}goods_shopping_cart WHERE goods_id IN({$goodsIds}) AND user_id={$userId}";
                                    // modify by Fu
                                    $sql = 'SELECT `SUM(goods_number)` AS `total` FROM '.$db_prefix.'goods_shopping_cart WHERE `goods_id` IN ('.$goodsIds.') AND `user_id` = '.$userId.' AND `id` IN ('.$sidStr.') AND `agent_id` = '.$agentId;
                                    $limitTotal = M()->query($sql);
                                    if ($limitTotal[0]['total'] && $limitTotal[0]['total'] > $lvalue['limit_number']) {
                                        $this->error("限购商品数量超出限购活动限制");
                                    }
                                }
                                $isCheckTotal[$lvalue['id']] = true;
                            } else if ($lvalue['limit_type'] == 2) {
                                // 按种限制
                                $sql = 'SELECT `distinct(goods_id)` AS `total` FROM '.$db_prefix.'limited_log WHERE `activity_id` = '.$lvalue['id'].' AND `is_delete` = 0 AND `user_id` = '.$userId.' AND `add_time` BETWEEN '.$lvalue['add_time'].' AND '.$lvalue['end_time'].' AND `agent_id` = '.$agentId;
                                $limitTotal = M()->query($sql);
                                // $sql2 = "SELECT distinct(goods_id) AS total FROM `{$db_prefix}goods_shopping_cart` WHERE goods_id IN({$goodsIds}) AND user_id={$userId}";
                                // modify by Fu 
                                $sql2 = 'SELECT `distinct(goods_id)` AS `total` FROM '.$db_prefix.'goods_shopping_cart` WHERE `goods_id` IN ('.$goodsIds.') AND `user_id` = '.$userId.' AND `id` IN ('.$sidStr.') AND `agent_id` = '.$agentId;
                                $shoppingCartTotal = M()->query($sql2);

                                $limitTotal = array_column($limitTotal, 'total');
                                $shoppingCartTotal = array_column($shoppingCartTotal, 'total');
                                $limitTotal = array_merge($limitTotal, $shoppingCartTotal);
                                $limitTotal = array_unique($limitTotal);
                                if (count($limitTotal) > $lvalue['limit_number']) {
                                    $this->error("限购商品种数超出限购活动限制");
                                }
                            }
                        } else {
                            $isCheckTotal[$lvalue['id']] = true;
                        }
                    }
                    break;
                default:
                    break;
            }                            
        }
        return array($isCheckTotal,$activityLog);
    }
}