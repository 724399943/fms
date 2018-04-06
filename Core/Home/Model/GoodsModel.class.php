<?php
namespace Home\Model;
use Think\Model;

class GoodsModel extends Model {

	private $dbPrefix;

	public function __construct(){
		parent::__construct();
		$this->dbPrefix = C('DB_PREFIX');
	}
	/**
	 * [getGoodsImages 获取商品相册]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getGoodsImages($goods_images_id) {
		$goods_images_id = trim($goods_images_id, ',');
		$goodsImagesModel = M('goods_images');
		$images = $goodsImagesModel->where(array('id'=> array('IN', $goods_images_id)))->field('`goods_image`')->select();
		$images = array_column($images, 'goods_image');
		if (!empty($images)) {
			return $images;
		} else {
			return array();
		}
	}

	/**
	 * [getGoodsExtension 商品详情内容]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getGoodsExtension($goods_ext_id) {
		$goodsExtension = M('goods_extension');
		$goodsDesc = $goodsExtension->where(array('id'=> $goods_ext_id))->getField('`goods_desc`');
		return $goodsDesc;
	}

	/**
	 * [getGoodsInfo 商品信息]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function getGoodsInfo($goodsId) {
        // 商品基本信息
        $goodsInfo = $this->find($goodsId);
        $goodsInfo['attr_list'] = $goodsInfo['attr_list'];
        // 分类
        // $categoryInfo = $this->recursiveFindCategory($goodsInfo['category_id']);
        // 商品图片
        $goodsImages = $this->getGoodsImages($goodsInfo['goods_images_id']);
        // 商品详情
        $goodsDesc = $this->getGoodsExtension($goodsInfo['goods_ext_id']);
        $attrData = $this->getSkuAttr($goodsInfo['category_id'],$goodsInfo['relevance_id']);
        $attrData = $attrData['attrNameInfo'];
        //商品属性展示
        // $goodsRelevanceModel = M('goods_relevance');
        // $relevanceData = $goodsRelevanceModel->find($goodsInfo['relevance_id']);
        // $relevanceGoods = array();
        // if( !empty($relevanceData) ) {
        //     $relevanceData['relevance_id'] = explode(',', $relevanceData['relevance_id']);
        //     $attrArr = explode(',', $relevanceData['relevance_attr']);

        //     foreach ($relevanceData['relevance_id'] as $key => $value) {
        //         $relevanceGoods[$value] = $this->field('`goods_price`,`goods_number`, `goods_image`')->find($value);
        //         $relevanceGoods[$value]['attr'] = $attrArr[$key];
        //     }
        //     $relevanceData['relevance_id'] = $relevanceGoods;
        //     $relevanceData['relevance_attr'] = preg_split('/[-,]/is', $relevanceData['relevance_attr']);
        //     $relevanceData['relevance_attr'] = array_merge(array_unique($relevanceData['relevance_attr']), array());
        // } else {
        //     $relevanceData = array();
        // }

        $returnData = array(
        	'attrData' 		=> $attrData,
            'goodsImages' 	=> $goodsImages,
            'goodsInfo' 	=> $goodsInfo,
            'goodsDesc' 	=> $goodsDesc,
            // 'categoryInfo' 	=> $categoryInfo,
            'relevanceData' => json_encode($relevanceData),
        );
        return $returnData;
	}

	/**
     * [getSkuAttr 获得商品SKU]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getSkuAttr($category_id, $relevance_id) 
    {
        $goodsRelevanceModel = M('goods_relevance');
            //找到关联商品属性表
            $relevanceData = $goodsRelevanceModel->where(['id'=>$relevance_id])->find();

            $goodsArr = explode(',', $relevanceData['relevance_id']);
            $attrArr  = explode(',', $relevanceData['relevance_attr']);

            $attrArray = ',';
            foreach ($attrArr as $key => $value) {
                $attrArray .= str_replace('-', ',', $value).',';
            }
            $attrArray = explode(',', trim($attrArray,','));
            $attrArray = ','.implode(',',array_unique($attrArray)).',';
            //获取相关分类的商品model
            $attrNameInfo = $this->getAttrNameData($category_id, $attrArray);
            sort($attrNameInfo);
            $attrOn = array_combine($goodsArr, $attrArr);

            //获取商品库存
            // $goodsOn = array(); 
            // $shoppingModel = M('goods_shopping_cart');
            // foreach ($attrOn as $key => &$value) {
            //     $goodsDetail = $this->goodsModel->field('`id`, `goods_number`, `goods_price`, `goods_image`, `goods_name`, `attr_list`')->find($key);
            //     $goodsDetail['attr_list'] = trim($goodsDetail['attr_list'], ',');
            //     $shopNumber = $shoppingModel->where(array('user_id'=>$userId, 'goods_id'=>$key))->getField('SUM(`goods_number`)');
            //     $surplus = $goodsDetail['goods_number']- $shopNumber;
                
            //     $goodsDetail['surplus'] = $surplus;
            //     $skuId['skuid'] = $value;
            //     $skuId['skuArr']= explode('-',$value);
            //     $goodsOn[] = array_merge($goodsDetail,$skuId);
            // }
            $result = array(
                'attrNameInfo'  => $attrNameInfo,
                // 'relevanceData' => $relevanceData,
                'goodsOn'       => $goodsOn,
            );
            return $result;
    }

    public function getAttrNameData($category_id, $attrArray = '') {
    	// dump($attrArray);
		$goodsAttrNameModel  = M('goods_attr_name');
        $goodsAttrValueModel = M('goods_attr_value');
		$attrNameData = $goodsAttrNameModel->where(['category_id'=>$category_id])->select();
        if ( !empty($attrNameData) ) {
            $attrId = array_column($attrNameData, 'id');
            $where = array(
            	// 'agent_id' => $agent_id,
            	'name_id' => array('IN', $attrId),
            	// 'category_id' => $category_id,
            );
            $attrValueData = $goodsAttrValueModel->where($where)->select();
            foreach ($attrNameData as $key => &$value) {
                foreach ($attrValueData as $v) {
                    if ( $v['name_id'] == $value['id'] ) {
                    	if ( !empty($attrArray) && strstr($attrArray, ','.$v['id'].',') !== false ) {
	                        $value['attrValue'][] = $v;
                    	} elseif ( empty($attrArray) ) {
                    		$value['attrValue'][] = $v;
                    	}
                    }
                }
                if (empty($attrNameData[$key]['attrValue'])) {
                    unset($attrNameData[$key]);
                }
            }
        } else {
            $attrNameData = array();
        }
        return $attrNameData;
	}




}