<?php
namespace Admin\Model;
use Think\Model;

class AdGroupModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author cdd <[2042536829@qq.com]>
	 */
	protected $_validate = array(
		// // 新增
		array('group_name','require','请输入版位名称', 1, 'regex',1),
		array('group_sign', 'require','请输入版位标识', 1, 'regex', 1),
		array('width', 'number','请输入合法的长度值', 2, 'regex', 1),
		array('height','number','请输入合法的高度值', 2, 'regex',1),
		// array('type_name', '1,50','标题长度不超过50个字符', 1, 'length', 1),
		// array('attr_name','2,40','字段名称长度为2到40间的英文字符', 1, 'length',1),
		// array('attr_name', 'checkAttr', '该字段名称已存在，请重新填写', 1, 'callback', 1),
		// array('attr_name', 'isAttr', '字段名称含有非法字符', 1, 'callback', 1),
		// array('type_remark','0,200','提示文字不能超过200个字符', 1, 'length',1),

		// //编辑
		array('group_name','require','请输入版位名称', 1, 'regex',2),
		array('group_sign', 'require','请输入版位标识', 1, 'regex', 2),
		array('width', 'number','请输入合法的长度值', 2, 'regex', 2),
		array('height','number','请输入合法的高度值', 2, 'regex',2),
	);
}