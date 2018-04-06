<?php
namespace Admin\Model;
use Think\Model;

class ArticleModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author cdd <[2042536829@qq.com]>
	 */
	protected $_validate = array(
		// // 新增
		array('cat_id','require','请选择所属子分类', 1, 'regex',1),
		array('title', 'require','请输入标题', 1, 'regex', 1),
		array('title', '1,200','标题长度不超过200个字符', 1, 'length', 1),
		array('content','require','请输入文章内容', 1, 'regex',1),


		// //编辑
		array('cat_id','require','请选择所属子分类', 1, 'regex',2),
		array('title', 'require','请输入标题', 1, 'regex', 2),
		array('title', '1,200','标题长度不超过200个字符', 1, 'length', 2),
		array('content','require','请输入文章内容', 1, 'regex',2),
	);

	/**
	 * [addExtendField 添加扩展字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)             2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $module_attr_id [description]
	 * @param     [type]        $field          [description]
	 */
	public function addExtendField($module_attr_id,$field){
		$model = M('article_attr');
		$moduleAttrModel = M('module_attr');
		
		$dataArr = [];
		foreach ($module_attr_id as $key => $value) {
			$addData = [];
			$fieldKey = 'attr_' . $value;
			$addData['module_attr_id'] = $value;
			$addData['ext_value'] = ( !$field[$fieldKey] ) ? '' : $field[$fieldKey];
			//如果是复选框
			if( is_array($addData['ext_value']) ){
				$addData['ext_value'] = implode(',',$addData['ext_value']);
			}

			$addData['modalias'] = $moduleAttrModel->where(['id' => $value])->getField('`modalias`');
			$dataArr[] = $addData;
		}
		// dump($dataArr);
		$dataCount = count($dataArr);
		$goodsId = $model->addAll($dataArr);
		return ['dataCount'=>$dataCount,'goodsId'=>$goodsId];

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
		$model = M('article_attr');
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

	
}