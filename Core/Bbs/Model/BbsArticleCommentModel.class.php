<?php
namespace Bbs\Model;
use Think\Model;

/**
 * 用户信息模型
 */
class BbsArticleCommentModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author StanleyYuen <[350204080@qq.com]>
	 */
	protected $_validate = array(
		array('content', 'require', '内容不能为空！', 1, 'regex', 1),
		array('article_id', 'require', '参数丢失！', 1, 'regex', 1),
	);

	protected $_auto = array(
		array('add_time', 'time', 3, 'function'),
	);
}