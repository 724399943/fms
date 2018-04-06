<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
	 */
	protected $_validate = array(

	);

	protected $_auto = [
        ['add_time', 'time', 5, 'function'],
    ];

	/**
     * [getUserInfo 获取用户信息]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getUserInfo($where = [], $field = '', $isField = false) 
    {
    	if ( $isField !== false ) {
    		$userInfo = $this->where($where)->getField($field);
    	} else {
	        $userInfo = $this->where($where)->field($field)->find();
    	}
        return $userInfo;
    }
}
