<?php
namespace Admin\Model;
use Think\Model;

class QuestionnaireModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
	 */
	protected $_validate = array(
		// 新增
		array('title', 'require', '请输入问卷标题.', 1, 'regex', 1),
		array('logo', 'require', '请上传LOGO.', 1, 'regex', 1),
		array('background_image', 'require', '请上传背景图片.', 1, 'regex', 1),

		// 编辑
		array('title', 'require', '请输入问卷标题.', 1, 'regex', 2),
		array('logo', 'require', '请上传LOGO.', 1, 'regex', 2),
		array('background_image', 'require', '请上传背景图片.', 1, 'regex', 2),
	);

	protected $_auto = array(
		array('add_time', 'time', 1, 'function'),
	);
}