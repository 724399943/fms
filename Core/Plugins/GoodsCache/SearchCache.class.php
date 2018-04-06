<?php
namespace Plugins\GoodsCache;
use Think\Controller;

class SearchCache extends Controller {
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
}
