<?php
namespace Plugins\Controller;
use Think\Controller;

class OdenController extends Controller
{

	/**
	 * 获取商品信息接口
	 * @author sarcasme
	 */
	function returnGoodsInfo()
	{
		if (IS_GET)
		{
			$id = I('get.id');
			$goodsDetail = array();
			if (empty($id))
			{
				//请输入id
				exit(json_encode($goodsDetail));
			}else{
				$dbPrefix = C('DB_PREFIX');
				$where = array('gd.id' => $id);
				$goodsDetail = M('goods')->alias('gd')
					->join("left join {$dbPrefix}brand bd on bd.id = gd.brand_id")
					->join("left join {$dbPrefix}goods_category gc on gc.id = gd.category_id")
					->where($where)
					->field('gd.id, gd.goods_name name, gd.goods_price siteprice, gd.market_price marketprice, bd.brand_name brand, gc.category_name category, gd.goods_image imageurl')
					->find();
				if ($goodsDetail)
				{
					foreach($goodsDetail as $k => $v)
					{
						if (!$v) $goodsDetail[$k] = '';
					}
					$now = strtotime('now');
					$where = array('condition' => ",$id,");
					$is_discount = M('limit_offers')->field('preferential_type, goods_value')->where($now.'between start_time and end_time')->where($where)->find();
					if (!empty($is_discount))
					{
						$goods_value = json_decode($is_discount['goods_value']);
						if ($is_discount['preferential_type'] == 1)
						{
							$siteprice = $goods_value['discount_price'];
						}else{
							$siteprice = bcmul($goods_value['goods_price'], $goods_value['discount']);
						}
						$goodsDetail['siteprice'] = $siteprice;
					}
					exit(json_encode($goodsDetail));
				}else{
					//未查询到该商品，请输入正确的商品id
					exit(json_encode($goodsDetail));
				}

			}
		}

	}
}

?>