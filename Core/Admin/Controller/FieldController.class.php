<?php
namespace Admin\Controller;
class FieldController extends BaseController{

	private $moduleModel;
	private $modAttrModel;

	public function __construct(){
		parent::__construct();
		$this->moduleModel = M('module');
        $this->categoryModel = M('category');
        $firstCat = getFirstCat();
        $this->assign('firstCat', $firstCat);
        $this->modAttrModel = D('module_attr');
	}

	/**
	 * [fieldList 模块字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function fieldList(){
		$firstCatId = I('get.firstCatId');
		$tree_id = I('get.tree_id',$firstCatId);
		if($tree_id != '-1'){
			$where['tree_id'] = $tree_id;
		}

		//所属模块的一级分类
		$firstCatId = I('get.firstCatId');
		$module_id = $this->categoryModel->where(['id'=>$firstCatId])->getField('`module_id`');
		$categoryList = $this->categoryModel->where(['module_id'=>$module_id,'tree_id'=>0])->select();

		
		//获得模块字段列表
		$where['module_id'] = $module_id;
		$list = $this->modAttrModel->where($where)->select();
		foreach ($list as $key => &$value) {
			if( !$value['tree_id'] )
				$value['tree_name'] = '所有栏目';
			else
				$value['tree_name'] = $this->categoryModel->where(['id'=>$value['tree_id']])->getField('`cat_name`');
		}

		$return = [
			'tree_id' => $tree_id,
		];
		$this->assign([
			'categoryList' => $categoryList,
			'list' 		   => $list,
			'firstCatId'   => $firstCatId,
			'return'	   => $return,
		]);
		$this->display();
	}

	/**
	 * [addField 添加字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addField(){
		if(IS_POST){
			$data = $this->modAttrModel->create(I('post.'));
			if( empty($data) ){
				$this->error($this->modAttrModel->getError());
			}else{
				$data['modalias'] = M('module')->where(['id'=>$data['module_id']])->getField('`alias`');
				$data['attr_value'] = htmlspecialchars($data['attr_value']);
				$result = $this->modAttrModel->data($data)->add();
				( !$result ) ? $this->error('添加字段失败') : $this->success('添加字段成功',U('Field/fieldList',array('firstCatId'=>I('post.firstCatId'))));
			}

		}else{
			//所属模块的一级分类
			$firstCatId = I('get.firstCatId');
			$module_id = $this->categoryModel->where(['id'=>$firstCatId])->getField('`module_id`');
			$categoryList = $this->categoryModel->where(['module_id'=>$module_id,'tree_id'=>0])->select();

			$this->assign([
				'categoryList'	=>	$categoryList,
				'firstCatId' => $firstCatId,
				'module_id' => $module_id,
			]);
			$this->display();
		}
	}

	/**
	 * [changeFieldStatus 改变字段状态]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function changeFieldStatus(){
		if(IS_POST){
			$id = I('post.id');
			$status = I('post.status');
			$field = I('post.field');
			$result = $this->modAttrModel->where(['id'=>$id])->data(array($field => $status))->save();
			(!$result) ? exit(statusCode(array(),400000,'更改失败')) : exit(statusCode(array(),200000,'更改成功'));
		}else{
			exit(statusCode('','非法',100001));
		}
	}
	/**
	 * [delField 删除字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function delField(){
		$ids = I('get.ids');
		//获得模型别名
		$data = $this->modAttrModel->where(['id' => ['IN',$ids]])->field('`module_id`')->find();
		$alias = $this->moduleModel->where(['id' => $data['module_id']])->getField('`alias`');
		$model_name = $alias.'_attr';
		$attrModel = M("{$model_name}");
		if(empty($ids)){
			$this->error('参数丢失');
		}elseif ( $attrModel->where(['module_attr_id'=>['IN',$ids]])->delete() !== false ) {
			$result = $this->modAttrModel->where(['id' => ['IN',$ids]])->delete();
			( !$result ) ? $this->error('删除失败') : $this->success('删除成功');
		}else{
			$this->error('删除失败');
		}

	}

	/**
	 * [editField 编辑字段]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function editField(){
		if(IS_POST){
			$data = $this->modAttrModel->create(I('post.',2));
			$data['modalias'] = M('module')->where(['id'=>$data['module_id']])->getField('`alias`');
			$data['attr_value'] = htmlspecialchars($data['attr_value']);
			$result = $this->modAttrModel->data($data)->save();
			if($result === false)
				$this->error('编辑字段失败');
			else
				$this->success('编辑字段成功',U('Field/fieldList',array('firstCatId'=>I('post.firstCatId'))));

		}else{
			//字段信息
			$id = I('get.id','');
			$data = $this->modAttrModel->where(['id'=>$id])->find();
			// dump($data);
			//所属模块的一级分类
			$categoryList = $this->categoryModel->where(['module_id'=>$data['module_id'],'tree_id'=>0])->select();
			$this->assign([
				'fieldData'	=>	$data,
				'categoryList' => $categoryList,
				'firstCatId' => $data['tree_id'],
			]);
			// dump($data['tree_id']);
			$this->display();
		}
	}
}