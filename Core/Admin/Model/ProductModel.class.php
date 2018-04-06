<?php
namespace Admin\Model;
use Think\Model;

class ProductModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author cdd <[2042536829@qq.com]>
	 */
	protected $_validate = array(
		// // 新增
		array('cat_id','require','请选择所属子分类', 1, 'regex',1),
		array('product_name', 'require','请输入产品名称', 1, 'regex', 1),
		array('product_name', '1,200','标题长度不超过200个字符', 1, 'length', 1),
		// array('upload_files','require','请上传商品图片', 1, 'regex',1),
		array('content','require','请输入内容', 1, 'regex',1),


		// // //编辑
		// array('cat_id','require','请选择所属子分类', 1, 'regex',2),
		// array('product_name', 'require','请输入产品名称', 1, 'regex', 2),
		// array('product_name', '1,200','标题长度不超过200个字符', 1, 'length', 2),
		// array('upload_files','require','请上传商品图片', 1, 'regex',2),
		// array('content','require','请输入内容', 1, 'regex',2),
	);

	/**
	 * [addExtendField 添加扩展字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)             2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $module_attr_id [description]
	 * @param     [type]        $field          [description]
	 */
	public function addExtendField($module_attr_id,$field){
		$model = M('product_attr');
		$moduleAttrModel = M('module_attr');
		
		
		// $dataArr = [];
		// foreach ($module_attr_id as $key => $value) {
		// 	$addData = [];
		// 	$fieldKey = 'attr_' . $value;
		// 	$addData['module_attr_id'] = $value;
		// 	$addData['ext_value'] = ( !$field[$fieldKey] ) ? '' : $field[$fieldKey];
		// 	//如果是复选框
		// 	if( is_array($addData['ext_value']) ){
		// 		$addData['ext_value'] = implode(',',$addData['ext_value']);
		// 	}

		// 	$addData['modalias'] = $moduleAttrModel->where(['id' => $value])->getField('`modalias`');
		// 	$dataArr[] = $addData;
		// }
		// // dump($dataArr);
		// $dataCount = count($dataArr);
		// $goodsId = $model->addAll($dataArr);
		// return ['dataCount'=>$dataCount,'goodsId'=>$goodsId];

	}
	/**
	 * [addGoodsImages 添加商品图集]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)      2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $goodsId [商品id]
	 * @param     array         $images  [图片索引数组]
	 */
	public function addGoodsImages($goodsId,$images = []){
		$dataArr = [];
		foreach ($images as $key => $value) {
			$data = [
				'goods_image' => $value,
				'goods_id' => $goodsId,
			];
			$dataArr[] = $data;
		}
		$goodsImageModel = M('goods_images');
		$i = $goodsImageModel->addAll($dataArr);
		return $i;

	}
	/**
	 * [editGoodsImages 编辑商品图集]
	 * @author kofu <418382595@qq.com>
	 * @modify cdd <2042536829@qq.com>
	 * @copyright Copyright (c)        2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $images    [description]
	 * @param     [type]        $goodsData [description]
	 * @return    [type]                   [description]
	 */
	public function editGoodsImages($goodsId, $images = []) {

		$goodsImagesModel = M('goods_images');
		$imagesList = $goodsImagesModel->where(['goods_id'=>$goodsId])->select();
		$goods_images_id = array();
		//处理旧图
		foreach ($imagesList as $key => $value) {
			if ( !in_array($value['goods_image'], $images) ) {
				$goodsImagesModel->delete($value['id']);
			} else {
				// $goods_images_id[] = $value['id'];
			}
		
		}
		// 处理新图
		$imagesData = array_column($imagesList, 'goods_image');
		$dataArr = [];
		foreach ($images as $key => $value) {
			if ( !in_array($value, $imagesData) ) {
				$data = [
					'goods_id' => $goodsId,
					'goods_image' => $value,
				];
				$dataArr[] = $data;
			}
		}
		$dataArr = array_column($dataArr,'goods_image');
		$result = $this->addGoodsImages($goodsId,$dataArr);
		return array(
        	'goods_image' => $images[0],
        	'result' => $result,
        );
	}

	/**
	 * [editExtendField 编辑扩展字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)      2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $attr_id [description]
	 * @param     [type]        $field   [description]
	 * @return    [type]                 [description]
	 */
	public function editExtendField($attr_id,$field){
		$model = M('product_attr');
		$moduleAttrModel = M('module_attr');
		
		$fieldArr = [];
		foreach ($field as $key => $value) {
			$fieldArr[] = $value;
		}
		$result = true;
		foreach ($fieldArr as $key => $value) {
			$saveData = [];
			$saveData['id'] = $attr_id[$key];
			if( is_array($value)){
				$saveData['ext_value'] = implode(',',$value);
			}else{
				$saveData['ext_value'] = $value;
			}

			if( $model->data( $saveData )->save() === false ){
				$result = false;
				break;
			}
		}
		return $result;
	}

	public function getAttrData($where = []){
		// $goodsAttrNameModel  = M('goods_attr_name');
        $goodsAttrValueModel = M('goods_attr_value');
        $moduleAttrModel = M('module_attr');
        
        //获得属性值
        
        $attrValueData = $goodsAttrValueModel->where($where)->select();
        foreach ($attrValueData as $key => &$value) {
        	//系统属性不输出
        	$data = $moduleAttrModel->where(['id'=>$value['attr_name_id']])->field('`is_system`,`type_name`')->find();
        	if( $data['is_system'] == '0'){
        		$value['attr_name'] = $data['type_name'];
        	}else{
        		array_splice($attrValueData,$key,1);
        	}
        }
        //获得拓展字段
        // $where = [
        //     'module_id' => '2',//默认产品模型
        //     'tree_id' => ['IN','0,5'] //默认模块为产品介绍
        // ];
        // $attrNameData = $moduleAttrModel->where($where)->field('`id`,`type_name`')->select();
        // foreach ($attrNameData as $key => &$value) {
        // 	$value['attrValueData'] = $goodsAttrValueModel->where(['attr_name_id'=>$value['id']])->select();
        // }
        // $attrArr = explode(',',$proInfo['goods_attr']);//1,4,7
        // $attrListArr = explode(';',$proInfo['goods_attr_list']);//xxx;yyy;zzz
        // $attrInfo = [];
        // if ( !empty($attrNameData) ) {
        //     foreach ($attrArr as $key => $value) {
        //         $info = $goodsAttrValueModel->where(['id'=>$value])->find();
        //         foreach ($attrNameData as $k => &$v) {
        //             if( $info['attr_name_id'] == $v['id']){
        //                 $v['attrValue'][] = $info;
        //             }
        //         }
        //     }
        //     // dump($attrNameData);
        // } else {
        //     $attrNameData = array();
        // }
        // dump($attrValueData);
        return $attrValueData;
    
	}

	/**
	 * [getAttrField 重组商品属性字段 goods_attr goods_attr_list]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)             2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $attr_value_ids [description]
	 * @return    [type]                        [description]
	 */
	public function reAttrField($attr_value_ids,$firstCatId){
		//系统属性字段
		$where = [
			'tree_id' => $firstCatId,
			'is_system' => '1'
		];
		$sysFields = M('module_attr')->where($where)->field('`id`')->select();
		$sysIds = '';
		foreach ($sysFields as $key => $value) {
			//属性值ID
			$valueId = M('goods_attr_value')->where(['attr_name_id'=>$value['id']])->getField('`id`');
			$sysIds .= $valueId['id'] .',';
		}
		//系统和添加的属性字段
		// $attr_value_ids .= trim($sysIds,',');
		// dump($sysIds);
		$data['goods_attr'] = implode(',',$attr_value_ids);
		$data['goods_attr'] = $sysIds . $data['goods_attr'];
		$attr_value_ids = explode(',',$data['goods_attr']);
		$goods_attr_list = '';
		foreach ($attr_value_ids as $key => $value) {
			$goods_attr_list .= getAttrValue($value) . ';';
		}
		$data['goods_attr_list'] = trim($goods_attr_list,';');
		return $data;
	}
	
}