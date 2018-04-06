<?php
namespace Admin\Model;
use Think\Model;

class SignModel extends Model {
    /**
     * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
     */
    protected $_validate = array(
        // 新增
        
    );

    /**
     * [_auto 自动完成]
     * @author kofu <[418382595@qq.com]>
     */
    protected $_auto = array(
        array('sign', 'createToken', 1, 'callback'),
    );

    protected function createToken()
    {
        $sign = createToken();
        $signId = $this->where(['sign'=>$sign])->getField('`id`');
        if ( !empty($signId) ) {
            $this->createToken();
        } else {
            return $sign;
        }
    }
}