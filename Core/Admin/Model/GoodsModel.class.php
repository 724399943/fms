<?php
namespace Admin\Model;
use Think\Model;

class GoodsModel extends Model {
	private $dbPrefix;
	public function __construct() {
		parent::__construct();
		$this->dbPrefix = C('DB_PREFIX');
	}
	/**
	 * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
	 */
	protected $_validate = array(
		// 新增
		array('goods_name', 'require', '请输入商品名称', 1, 'regex', 1),
		// array('introduction', 'require', '请输入商品副标题', 1, 'regex', 1),
		array('category_id', 'require', '请选择商品分类', 1, 'regex', 1),
		array('goods_code', 'require', '请输入商品编码', 1, 'regex', 1),
		array('description', 'require', '请输入产品介绍', 1, 'regex', 1),
		// array('images', 'require', '请上传商品图片', 1, 'regex', 1),

		// 修改
		array('goods_name', 'require', '请输入商品名称', 1, 'regex', 2),
		// array('introduction', 'require', '请输入商品副标题', 1, 'regex', 2),
		array('category_id', 'require', '请选择商品分类', 1, 'regex', 2),
		array('description', 'require', '请输入产品介绍', 1, 'regex', 2),
		// array('images', 'require', '请上传商品图片', 1, 'regex', 2),
	);

	protected $_auto = array(
		array('add_time', 'time', 1, 'function'),
	);

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
	 * [addGoodsImages 添加商品图片]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)     2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $images [description]
	 */
	public function addGoodsImages($images) {
		$goodsImagesModel = M('goods_images');
		$goods_images_id = array();
		foreach ($images as $key => $value) {
			$goods_image = str_replace('/600x600', '', $value);
			$goods_image = str_replace('_600x600', '', $goods_image);
			$temp = [
				'goods_image' => $goods_image,
				// 'goods_middle_image' => str_replace('600x600', '350x350', $value),
				// 'goods_small_image' => str_replace('600x600', '160x160', $value),
				'goods_big_image' => $value,
			];
            $goods_images_id[] = $goodsImagesModel->add($temp);
        }
        
        return array(
        	'goods_image' => $images[0],
        	'goods_images_id' => implode(',', $goods_images_id)
        );
	}

	/**
	 * [editGoodsImages 编辑商品图片]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)        2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $images    [description]
	 * @param     [type]        $goodsData [description]
	 * @return    [type]                   [description]
	 */
	public function editGoodsImages($images, $goodsData) {
		$goodsImagesModel = M('goods_images');
		$where['id'] = array('IN', $goodsData['goods_images_id']);
		$imagesList = $goodsImagesModel->where($where)->select();
		$goods_images_id = array();
		// 处理旧图
		foreach ($imagesList as $key => $value) {
			if ( !in_array($value['goods_image'], $images) ) {
				$goodsImagesModel->delete($value['id']);
			} else {
				$goods_images_id[] = $value['id'];
			}
		}
		// 处理新图
		$imagesData = array_column($imagesList, 'goods_image');
		foreach ($images as $key => $value) {
			if ( !in_array($value, $imagesData) ) {
				$goods_image = str_replace('/600x600', '', $value);
				$goods_image = str_replace('_600x600', '', $goods_image);
				$temp = [
					'goods_image' => $goods_image,
					// 'goods_middle_image' => str_replace('600x600', '350x350', $value),
					// 'goods_small_image' => str_replace('600x600', '160x160', $value),
					'goods_big_image' => $value,
				];
            	$goods_images_id[] = $goodsImagesModel->add($temp);
			}
		}
		return array(
        	'goods_image' => $images[0],
        	'goods_images_id' => implode(',', $goods_images_id)
        );
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
	 * [addGoodsExtension 添加商品详情]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $description [description]
	 */
	public function addGoodsExtension($description) {
		$goodsExtensionModel = M('goods_extension');
		$descriptionData = array(
            'goods_desc' => $description,
            'add_time'   => time(),
        );
        $extensionId = $goodsExtensionModel->add($descriptionData);
        return $extensionId;
	}

	/**
	 * [editGoodsExtension 编辑商品详情]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $description [description]
	 * @param     [type]        $goodsData   [description]
	 * @return    [type]                     [description]
	 */
	public function editGoodsExtension($description, $goodsData) {
		$goodsExtensionModel = M('goods_extension');
		$map['id'] = $goodsData['goods_ext_id'];
		if( $goodsExtensionModel->where($map)->count() > 0 ){
			$descData['goods_desc'] = $description;
            $goodsExtensionModel->where($map)->data($descData)->save();
            $extensionId = $goodsData['goods_ext_id'];
        } else {
            $extensionId = $this->addGoodsExtension($description);
        }
		return $extensionId;
	}

	/**
	 * [deleteGoods 删除店铺商品]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)      2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $goodsId 	[description]
	 * @param     [type]        $agentId  	[description]
	 * @return    [type]                 	[description]
	 */
	public function deleteGoods($goodsId) {
		$where 	= array(
			'id'        => array('IN', $goodsId),
            // 'agent_id'	=> $agentId
		);
		if ( $this->where($where)->save(array('is_delete'=> '1')) !== false ) {
			return true;
		} else {
			return false;
		}
	}

	public function getGoodsInfo($goodsId) {
        // 商品基本信息
        $goodsInfo = $this->find($goodsId);
        // 商品图片
        $goodsImages = $this->getGoodsImages($goodsInfo['goods_images_id']);
        // 商品详情
        $goodsDesc = $this->getGoodsExtension($goodsInfo['goods_ext_id']);

        // SKU
        $goodsRelevanceModel = M('goods_relevance');
        $relevanceData = $goodsRelevanceModel->find($goodsInfo['relevance_id']);
        $relevanceGoods = array();
        if( !empty($relevanceData) ) {
            $relevanceData['relevance_id'] = explode(',', $relevanceData['relevance_id']);
            $attrArr = explode(',', $relevanceData['relevance_attr']);

            foreach ($relevanceData['relevance_id'] as $key => $value) {
                $relevanceGoods[$value] = $this->field('`goods_price`,`goods_number`, `goods_image`')->find($value);
                $relevanceGoods[$value]['attr'] = $attrArr[$key];
            }
            $relevanceData['relevance_id'] = $relevanceGoods;
            $relevanceData['relevance_attr'] = preg_split('/[-,]/is', $relevanceData['relevance_attr']);
            $relevanceData['relevance_attr'] = array_merge(array_unique($relevanceData['relevance_attr']), array());
        } else {
            $relevanceData = array();
        }

        $returnData = array(
            'goodsImages' 	=> $goodsImages,
            'goodsInfo' 	=> $goodsInfo,
            'goodsDesc' 	=> $goodsDesc,
            'relevanceData' => json_encode($relevanceData),
        );
        return $returnData;
	}

	/**
	 * [addGoodsRelevance 添加商品关联]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addGoodsRelevance($goodsIdArr, $relevanceAttr) {
		$goodsRelevanceModel = M('goods_relevance');
		$allGoodsId = implode(',', $goodsIdArr);
        $relevanceAttr = implode(',', $relevanceAttr);
        $goodsRelevanceData = array(
            'relevance_id' => $allGoodsId,
            'relevance_attr'=> $relevanceAttr,
            'add_time' => time(),
        );
        $relevanceId = $goodsRelevanceModel->data($goodsRelevanceData)->add();
        $i = $this->where(array('id'=>array('IN', $allGoodsId)))->save(array('relevance_id'=>$relevanceId));
        return $i;
	}

	/**
	 * [editGoodsRelevance 编辑商品关联]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)            2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $goodsIdArr    [description]
	 * @param     [type]        $relevanceAttr [description]
	 */
	public function editGoodsRelevance($goodsIdArr, $relevanceAttr, $goodsData) {
		$goodsRelevanceModel = M('goods_relevance');
		$allGoodsId = implode(',', $goodsIdArr);
        $relevanceAttr = implode(',', $relevanceAttr);
        $goodsRelevanceData = array(
            'relevance_id' => $allGoodsId,
            'relevance_attr'=> $relevanceAttr,
            'add_time' => time(),
        );
        $map['id'] = $goodsData['relevance_id'];
		if( $goodsRelevanceModel->where($map)->count() > 0 ){
            $goodsRelevanceModel->where($map)->data($goodsRelevanceData)->save();
            $relevanceId = $map['id'];
        } else {
            $relevanceId = $goodsRelevanceModel->add($goodsRelevanceData);
        }
        $this->where(array('id'=>array('IN', $allGoodsId)))->save(array('relevance_id'=> $relevanceId));
   	}

   	public function getCategoryAttr($categoryId, $attrArray = '') {
		$goodsAttrNameModel  = M('goods_attr_name');
        $goodsAttrValueModel = M('goods_attr_value');
		$attrData = $goodsAttrNameModel->where(array('category_id'=> $categoryId))->field('`id`, `attr_name`')->select();

        if ( !empty($attrData) ) {
            $attrId = array_column($attrData, 'id');
            $goodsAttrValueList = $goodsAttrValueModel->where(array('name_id'=>array('in', $attrId)))->select();
            foreach ($attrData as $key => &$value) {
                foreach ($goodsAttrValueList as $gvalue) {
                    if ( $gvalue['name_id'] == $value['id'] ) {
                    	if ( !empty($attrArray) && strstr($attrArray, ','.$gvalue['id'].',') !== false ) {
	                        $value['attrValue'][] = $gvalue;
                    	} elseif ( empty($attrArray) ) {
                    		$value['attrValue'][] = $gvalue;
                    	}
                    }
                }
                if (empty($attrData[$key]['attrValue'])) {
                    unset($attrData[$key]);
                }
            }
        } else {
            $attrData = array();
        }
        return $attrData;
	}
}
