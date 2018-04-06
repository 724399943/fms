<?php
namespace Admin\Model;
use Think\Model;

class ModuleAttrModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author cdd <[2042536829@qq.com]>
	 */
	protected $_validate = array(
		// // 新增
		array('tree_id','require','请选择所属栏目', 1, 'regex',1),
		array('type_name', 'require','请输入简述文字', 1, 'regex', 1),
		array('type_name', '1,50','标题长度不超过50个字符', 1, 'length', 1),
		array('input_type', 'require','请选择字段类型', 1, 'regex', 1),
		array('attr_name','require','请输入字段名称', 1, 'regex',1),
		array('attr_name','2,40','字段名称长度为2到40间的英文字符', 1, 'length',1),
		array('attr_name', 'checkAttr', '该字段名称已存在，请重新填写', 1, 'callback', 1),
		array('attr_name', 'isAttr', '字段名称含有非法字符', 1, 'callback', 1),
		array('type_remark','0,200','提示文字不能超过200个字符', 1, 'length',1),

		// //编辑
		array('tree_id','require','请选择所属栏目', 1, 'regex',2),
		array('type_name', 'require','请输入简述文字', 1, 'regex', 2),
		array('type_name', '1,50','标题长度不超过50个字符', 1, 'length', 2),
		// array('input_type', 'require','请选择字段类型', 1, 'regex', 2),
		// array('attr_name','require','请输入字段名称', 1, 'regex',2),
		// array('attr_name','2,40','字段名称长度为2到40间的英文字符', 1, 'length',2),
		// array('attr_name', 'checkAttr', '该字段名称已存在，请重新填写', 1, 'callback', 2),
		// array('attr_name', 'isAttr', '字段名称含有非法字符', 1, 'callback', 2),
		array('type_remark','0,200','提示文字不能超过200个字符', 1, 'length',2),
	);
	/**
	 * [checkAttr 检测是否存在属性名]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	protected function checkAttr(){
		$attr_name = I('attr_name','0','string');
		$count = $this->where(['attr_name' => $attr_name])->count();
		if( $count <= 0 ){
			return true;
		}else
			return false;
	}
	//属性名称是否为英文字符
	protected function isAttr(){
		$attr_name = I('attr_name','0','string');
		if( preg_match("/^[a-zA-Z\s]+$/",$attr_name) ){
			return true;
		}else
			return false;
		
	}
	// protected $_auto = array(
	// 	array('add_time', 'time', 1, 'function'),
	// 	array('admin_password', 'encrypt', 1, 'function'),
	// );
}