<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsCatController extends BaseController{

    private $categoryModel;

    private $goodsAttrName;

    private $goodsAttrValue;

    public function __construct(){
        parent::__construct();
        $this->categoryModel = D('goods_category');
        $this->goodsAttrName = M('goods_attr_name');
        $this->goodsAttrValue = M('goods_attr_value');
    }
	/**
     * [goodsCategory 商品分类管理]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
	public function goodsCatList(){

        $categoryList = $this->categoryModel->order('sort desc')->select();
        $categoryList = $this->recursiveCategory('0',$categoryList);
        // dump($categoryList);
        $this->assign(array(
            'categoryList' => $categoryList,
        ));

        $this->display();
	}
	/**
    * [recursiveCategory 递归分类信息]
    * @author TF <[2281551151@qq.com]>
    */
    private function recursiveCategory($pid, $list = '') {
        static $categoryList;
        if ( !empty($list) ) {
            $categoryList = $list;
        }

        $childList = array();
        foreach ($categoryList as $key => $value) {
            if ( $value['pid'] == $pid ) {
                $childList[]    = $value;
                unset($categoryList[$key]);
            }
        }

        if (empty($childList)) {
            return false;
        } else {
            $result = array();
            foreach ($childList as $key => &$value) {
                $result[] = $value;

                $tempResult = $this->recursiveCategory($value['id']);
                if (!empty($tempResult)) {
                    $result = array_merge($result, $tempResult);
                } 
            }
            return $result;
        }
    }

    /**
     * [addGoodsCategory 添加商品分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function addGoodsCat() {
        if(IS_POST){
            $data = $this->categoryModel->create(I('post.'),1);
            if(empty($data)){
                $this->error($this->categoryModel->getError());
            }else{
                $data['add_time'] = time();
                if($data['pid'] == 0){
                    $data['level'] = 1;
                }else{
                    $pLevel = $this->categoryModel->getParent($data['pid'],'`level`');
                    $data['level'] = $pLevel + 1;
                }
                $addID = $this->categoryModel->add($data);
                if($addID){
                    //category_path字段
                    if($data['pid'] == 0){
                        $category_path = $addID .',';
                    }else{
                        $pCategoryPath = $this->categoryModel->getParent($data['pid'],'`category_path`');
                        $category_path = $pCategoryPath  . $addID . ',';
                    }
                    $this->categoryModel->where(array('id'=>$addID))->save(array('category_path'=>$category_path)) !== false ?
                    $this->success('添加商品分类成功',U('GoodsCat/goodsCatList')) : $this->error('添加商品失败'.$this->categoryModel->getError());    
                }else
                    $this->error($this->categoryModel->getError());
            }
        }else{
            $id = I('get.id','');
            //获得所有分类
            $catsList = $this->categoryModel->where(['is_delete'=>'0'])->order('sort desc')->select();
            $catsList = $this->recursiveCategory('0',$catsList);
            $v = $this->categoryModel->find($id);

            $this->assign([
                'v' => $v,
                'catsList' => $catsList,
            ]);
            $this->display();
        }
        
    }
    /**
     * [editGoodsCategory 编辑商品分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function editGoodsCat() {
        if(IS_POST){
            $data = $this->categoryModel->create(I('post.'),2);
            //判断是否有子分类
            $cateCount = $this->categoryModel->where(['pid'=>$data['id']])->count();
            if($cateCount > 0){
                $this->error('对不起，该分类下有子分类，请重新编辑');
            }
            if(!empty($data)){
                $data['add_time'] = time();
                if($data['pid'] == 0){
                    $data['level'] = 1;
                }else{
                    $pLevel = $this->categoryModel->getParent($data['pid'],'`level`');
                    $data['level'] = $pLevel + 1;
                }
                if($this->categoryModel->save($data) !== false){
                    //category_path字段
                    if($data['pid'] == 0){
                        $category_path = $data['id'] .',';
                    }else{
                        $pCategoryPath = $this->categoryModel->getParent($data['pid'],'`category_path`');
                        $category_path = $pCategoryPath  . $data['id'] .',';
                    }
                    $this->categoryModel->where(array('id'=>$data['id']))->save(array('category_path'=>$category_path)) !== false ?
                    $this->success('编辑商品分类成功',U('GoodsCat/goodsCatList')) : 
                    $this->error('编辑商品分类失败');   
                }
            }else
                $this->error($this->categoryModel->getError());
        }else{
            $id = I('get.id','');
            $v = $this->categoryModel->where(['id'=>$id])->find();
            //获得所有分类
            $catsList = $this->categoryModel->where(['is_delete'=>'0'])->order('sort desc')->select();
            $catsList = $this->recursiveCategory('0',$catsList);

            $this->assign(array(
                'v' => $v,
                'catsList' => $catsList,
            ));
            $this->display();
        }     
    }
   // 上传图片
    public function photoUpload() {
        // 图片保存路径
        fileUpload('GoodsCategory/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
    }
    /**
     * [delCat 删除分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function delGoodsCat()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数丢失！');
        }
        $cateCount = $this->categoryModel->where(['pid' => $id])->count();
        if ($cateCount > 0) {
            $this->error('对不起，该分类下有子分类，请从子分类删除。');
        } else {
            if ($this->categoryModel->where(['id' => $id])->delete()) {
                $this->success('成功！');
            } else {
                $this->error('失败！');
            }
        }

    }

   /**
     * [goodsModel 商品模型]
     * @author kofu <418382595@qq.com>
     * @modify cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function goodsModel() {
        if ( IS_POST ) {
            // dump($_POST);die;
            $id = I('post.id', '', 'int');
            if ( empty($id) ) {
                $this->error('参数丢失！');
            }

            // $goodsAttrName  = M('goods_attr_name');
            // $goodsAttrValue = M('goods_attr_value');

            $attrId        = I('post.attr_id');
            $attrName      = I('post.attr_name');
            // $isFilter      = I('post.is_filter');
            // $isRelation    = I('post.is_relation');

            $attrValue     = I('post.attr_value');
            foreach ($attrValue as $vkey => $vvalue) {
                $this->goodsAttrValue->where(array('name_id'=>$vkey, 'id'=>array('not in', array_keys($vvalue))))->delete();
                foreach ($vvalue as $kkey => $kvalue) {
                    $this->goodsAttrValue->where(array('name_id'=>$vkey, 'id'=>$kkey))->data(array('attr_value'=>$kvalue))->save();
                }
            }
            
            $newAttrValue  = I('post.newValue');
            foreach ($newAttrValue as $newKey => $newValue) {
                foreach ($newValue as $nvvalue) {
                    if ( empty($nvvalue) ) {
                        continue;
                    }

                    $data = array(
                        'name_id'  => $newKey,
                        'attr_value'    => $nvvalue,
                    );
                    $this->goodsAttrValue->data($data)->add();
                }
            }

            //删除属性名称
            $deleteIds = $this->goodsAttrName->where(array('category_id'=>$id, 'id'=>array('not in', $attrId)))->getField('id');
            if ( !empty($deleteIds) ) {
                $this->goodsAttrName->where(array('category_id'=>$id, 'id'=>array('not in', $attrId)))->delete();
                $this->goodsAttrValue->where(array('name_id'=>array('in', $deleteIds)))->delete();
            }

            foreach ($attrId as $key => $value) {
                $data = array();
                // if ( !empty($isFilter[$value]) ) {
                //     $data['is_filter'] = 1;
                // }

                // if ( !empty($isRelation[$value]) ) {
                    $data['is_relation'] = 1;
                // }

                if ( !empty($attrName[$value]) ) {
                    $data['attr_name'] = $attrName[$value];
                }

                if ( !empty($data) ) {
                    $this->goodsAttrName->where(array('category_id'=>$id, 'id'=>$value))->data($data)->save();
                }
            }

            $this->success('保存成功');
        } else {
            $id = I('get.id', '', 'int');
            if ( empty($id) ) {
                $this->error('参数丢失！');
            } 

            $attrName       = $this->goodsAttrName->where(['category_id'=>$id])->select();
            if ( !empty($attrName) ) {
                foreach ($attrName as &$attrValue) {
                    $attrValue['attrValue'] = $this->goodsAttrValue->where(array('name_id'=>$attrValue['id']))->select();
                }
            }
            // dump($attrName);
            $this->assign('attrInfo', $attrName);
            $this->display();
        }
    }

    
    /**
     * [addModelAttr 添加分类属性]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function addModelAttr() {
        if ( IS_POST ) {
            $id = I('post.id', '', 'int');
            if (empty($id)) {
                $this->error('参数丢失！');
            }

            $attrName   = I('post.attr_name');
            $attrValue  = I('post.attr_value');
            // $isFilter   = I('post.is_filter');
            // $isRelation = I('post.is_relation');

            // $goodsAttrName  = M('goods_attr_name');
            // $goodsAttrValue = M('goods_attr_value');
            // dump($_POST);die;
            foreach ($attrName as $namekey => $namevalue) {
                if ( empty($namevalue) ) {
                    continue;
                }

                $data = array(
                    // 'is_filter'   => empty($isFilter[$namekey]) ? 0 : 1,
                    // 'is_relation' => empty($isRelation[$namekey]) ? 0 : 1,
                    'is_relation' => 1,
                    'category_id' => $id,
                    'attr_name'   => $namevalue,
                );

                $attrNameId = $this->goodsAttrName->data($data)->add();
                if ( !empty($attrNameId) ) {
                    $dataList = array();
                    if ( !empty($attrValue[$namekey]) ) {
                        $attrValueRow = explode("\n", $attrValue[$namekey]);
                        foreach ($attrValueRow as $key => $value) {
                            $dataList[] = array(
                                'name_id' => $attrNameId,
                                'attr_value'   => $value,
                            );
                        }
                        $this->goodsAttrValue->addAll($dataList);
                    }
                }
            }

            $this->success('添加成功', U('GoodsCat/goodsModel', array('id'=>$id)));
        } else {
            $this->display();
        }
    }
}