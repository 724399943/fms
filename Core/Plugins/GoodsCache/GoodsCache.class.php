<?php
namespace Plugins\GoodsCache;
use Think\Controller;

class GoodsCache extends Controller {
	private $redis;

    public function __construct() {
        parent::__construct();
        $this->redis = redis();
    }

    /**
     * [getGoodsCache 获取商品缓存]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function getGoodsCache($goodsId) {
        if ($goodsId) {
            $goodsCache = $this->redis->hgetall($goodsId);
            if (empty($goodsCache)) {
                $goodsDetail = M('goods')->where(array('id'=> $goodsId))->find();
                foreach ($goodsDetail as $key => $value) {
                    $this->redis->hSet($goodsId, $key, $value);
                }
                $goodsCache = $this->redis->hgetall($goodsId);
            }
            return $goodsCache;
        }
        return false;
    }

    /**
     * [getGoodsArrCache 获取多个商品缓存]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function getGoodsArrCache($goodsArr) {
        if (is_array($goodsArr)) {
            $goodsCache = array();
            foreach ($goodsArr as $value) {
                $goodsCache[] = $this->getGoodsCache($value);
            }
            return $goodsCache;
        }
        return false;
    }

    /**
     * [setGoodsCache 重置商品缓存]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function setGoodsCache($goodsId, $key, $value) {
        if ($goodsId) {
            $goodsCache = $this->redis->hgetall($goodsId);
            if (!empty($goodsCache)) {
                $this->redis->hSet($goodsId, $key, $value);
            }
        }
        return false;
    }

    /**
     * [inc 商品缓存值递增]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function inc($goodsId, $key, $value) {
        if ($goodsId) {
            $goodsCache = $this->redis->hgetall($goodsId);
            if (!empty($goodsCache)) {
                $this->redis->hincrby($goodsId, $key, $value);
            }
        }
    }
}
