<?php
namespace Home\Model;
use Think\Model;

class ProductModel extends Model {
	/**
	 * [_validate 自动验证]
     * @author kofu <[418382595@qq.com]>
	 */
	protected $_validate = array(

	);

	protected $_auto = [
        // ['add_time', 'time', 5, 'function'],
    ];

	public function getAttrNameData($proInfo) {

        $goodsAttrNameModel  = M('goods_attr_name');
        $goodsAttrValueModel = M('goods_attr_value');
        $moduleAttrModel = M('module_attr');
        //获得拓展字段
        $where = [
            'module_id' => '2',//默认产品模型
            'tree_id' => ['IN','0,5'] //默认模块为产品介绍
        ];
        $attrNameData = $moduleAttrModel->where($where)->field('`id`,`type_name` as `attr_name`')->select();
        $attrArr = explode(',',$proInfo['goods_attr']);//1,4,7
        $attrListArr = explode(';',$proInfo['goods_attr_list']);//xxx;yyy;zzz
        $attrInfo = [];
        if ( !empty($attrNameData) ) {
            foreach ($attrArr as $key => $value) {
                $info = $goodsAttrValueModel->where(['id'=>$value])->find();
                foreach ($attrNameData as $k => &$v) {
                    if( $info['attr_name_id'] == $v['id']){
                        $v['attrValue'][] = $info;
                    }
                }
            }
            // dump($attrNameData);
        } else {
            $attrNameData = array();
        }
        return $attrNameData;
    }
}
