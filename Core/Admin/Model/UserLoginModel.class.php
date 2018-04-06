<?php
namespace Admin\Model;
use Think\Model;

class UserLoginModel extends Model {
	/**
	 * [getVisitNumber description]
	 * @author kofu <418382595@qq.com>
	 * @copyright Copyright (c)         2017          Xcrozz (http://www.xcrozz.com)
	 * @param     array         $where      [description]
	 * @param     string        $field      [description]
	 * @param     string        $selectType [description]
	 * @return    [type]                    [description]
	 */
	public function getVisitNumber($where=array(), $field="",$selectType="0") {
		if ( empty($selectType) ) {
			$data = $this->where($where)->field($field)->find();
		} else {
			$data = $this->where($where)->field($field)->select();
		}
		return $data;
	}
}