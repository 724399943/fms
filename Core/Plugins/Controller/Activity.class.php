<?php
namespace Plugins\Controller;
use Think\Controller;

class Activity extends Controller {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 活动顺序:
	 * 限时特价
	 * 组合商品
	 * 满赠
	 * 优惠券
	 * 包邮
	 */

	public function doRun() {
		$goodsList = array();
		$activityModel = M('activity');
		$activityData = $activityModel->where(array('is_open'=>'1'))->select();
		foreach ($activityData as $key => $value) {
			$value['plugin'] = ucfirst($value['plugin']);
			if(is_file(APP_PATH . "Plugins/{$value['plugin']}/{$value['class']}.class.php")) {
				import("Plugins.{$value['plugin']}.{$value['class']}");
				$value['class'] = new $class($goodsList);
				if(method_exists($value['class'], $value['function'])) {
					$value['class']->$value['function']();
				}
			}
		}
	}

	/**
	 * [favourable 组合商品]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function favourable() {

	}

	/**
	 * [limit 限时优惠]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function limit() {

	}


}