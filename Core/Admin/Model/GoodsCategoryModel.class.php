<?php
namespace Admin\Model;
use Think\Model;
class GoodsCategoryModel extends Model{
	//自动验证
  protected $_validate = array(
    array('pid','require','请选择所属的父类级别',1,'regex',1),
    // array('sort','require','请输入分类的排列数字',1,'regex',1),
    // array('sort','number','请输入正确分类的排列数字',1,'regex',1),
    // array('category_name','require','请输入类别名称',1,'regex',1),
    // array('category_name','1,20','类别名称最多20个字符',1,'length',1),
    // array('app_icon','require','请上传分类图片',1,'regex',1),
    // array('content','require','请输入分类描述',1,'regex',1),

    // array('pid','require','请选择所属的父类级别',1,'regex',2),
    // array('sort','require','请输入分类的排列数字',1,'regex',2),
    // array('sort','number','请输入正确分类的排列数字',1,'regex',2),
    // array('category_name','require','请输入类别名称',1,'regex',2),
    // array('category_name','1,20','类别名称最多20个字符',1,'length',2),
    // array('app_icon','require','请上传分类图片',1,'regex',2),
    // array('content','require','请输入分类描述',1,'regex',2),
    );
  // //删除前的回调函数
  // protected function _before_delete($options) {
  //   //删除商品分类图片
  //   $ids = I('get.ids');
  //   $idData = explode(',',trim($ids,','));
  //   foreach ($idData as $value) {
  //       $v = $this->field('app_icon')->find($value);
  //       $img = $_SERVER['DOCUMENT_ROOT'] . $v['app_icon'];
  //       if(!unlink($img))
  //           $this->error = $this->getError();
  //   }
  // }
  public function getParent($id,$field = ''){
    $return = $this->where(['id'=>$id])->getField($field);
    return $return;
  }
}