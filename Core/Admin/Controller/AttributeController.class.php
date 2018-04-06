<?php
namespace Admin\Controller;
class AttributeController extends BaseController {

    private $goodsAttrValueModel;

    private $firstCatId;
    private $moduleAttrModel;

    public function __construct(){
        parent::__construct();
        $this->goodsAttrValueModel = D('goods_attr_value');
        $this->moduleAttrModel = M('module_attr');

        $firstCat = getFirstCat();
        $this->firstCatId = I('get.firstCatId');
        $this->assign([
            'firstCatId' => $this->firstCatId,
            'firstCat' => $firstCat,
            ]);
    }

    /**
     * [attrList 字段值列表]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function attrList(){
        $attr_value = I('get.attr_value','','urldecode');
        if( $attr_value != ''){
            $where['attr_value'] = array('LIKE',"%{$attr_value}%");
        }

        $count = $this->goodsAttrValueModel->where($where)->count();
        $page = new \Think\Page($count,25);
        $show = $page->show();

        $attrValueData = $this->goodsAttrValueModel->where($where)->limit($page->firstRow,$page->listRows)->select();
        foreach ($attrValueData as $key => &$value) {
            $value['attr_name'] = getAttrName($value['attr_name_id'],'`type_name`');
        }

        $return = [
            'attr_value' => $attr_value,
        ];
        $this->assign([
            'list' => $attrValueData,
            'show' => $show,
            'counting' => $page->listRows,
            'return' => $return,
            ]);
        $this->display();
    }

    /**
     * [addAttr 添加属性]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function addAttr(){
       if(IS_POST){
            $postData = I('post.');
            $data = $this->goodsAttrValueModel->create($postData,1);
            if(empty($data)){
                $this->error($this->goodsAttrValueModel->getError());
            }else{
                $i = $this->goodsAttrValueModel->data($data)->add();
                (!$i) ? $this->error('添加失败') : $this->success('添加成功',U('Attribute/attrList',array('firstCatId'=>$postData['firstCatId'])));
            }
        }else{
            $this->display();
        }
    }

    public function editAttr(){
        if(IS_POST){
            $postData = I('post.');
            $data = $this->goodsAttrValueModel->create($postData,2);
            if(empty($data)){
                $this->error($this->goodsAttrValueModel->getError());
            }else{
                $i = $this->goodsAttrValueModel->data($data)->save();
                ($i === false) ? $this->error('编辑失败') : $this->success('编辑成功',U('Attribute/attrList',array('firstCatId'=>$postData['firstCatId'])));
            }

        }else{
            $id = I('get.id');
            $firstCatId = I('get.firstCatId');
            $attrData = $this->goodsAttrValueModel->where(['id'=>$id])->find();
            // dump($attrData);
            $this->assign([
                'attrData'=> $attrData,
                'firstCatId' => $firstCatId,
            ]);
            $this->display();
        }
    }

    public function delAttr(){
        $ids = I('get.ids','');
        if(empty($ids)){
            $this->error('参数丢失');
        }elseif( $this->goodsAttrValueModel->where(['id'=>['IN',$ids]])->delete()) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}