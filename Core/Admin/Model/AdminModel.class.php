<?php
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
	 */
	protected $_validate = array(
		// 新增
		array('group', 'require','请选择角色', 1, 'regex', 1),
		array('admin_account', 'require', '请输入账号.', 1, 'regex', 1),
		array('admin_account', '', '该账号已经注册.', 1, 'unique', 1),
		array('admin_password', 'require', '请输入密码.', 1, 'regex', 1),

		//编辑
		array('admin_account', 'require', '请输入账号.', 1, 'regex', 2),
		array('admin_account', '', '该账号已经注册.', 1, 'unique', 2),
		array('admin_password', 'require', '请输入密码.', 1, 'regex', 2),
		array('group', 'require', '请选择角色', 1, 'regex', 1),
		array('admin_password', 'require', '密码不能为空', 1, 'regex', 2),
		array('admin_password', 'admin_repassword', '两次输入密码不同！', 1, 'confirm', 2),
	);

	protected $_auto = array(
		array('add_time', 'time', 1, 'function'),
		array('admin_password', 'encrypt', 1, 'function'),
	);
}