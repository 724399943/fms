<?php
namespace Admin\Controller;
use Think\Controller;

class GoodsController extends BaseController{

	private $goodsModel;

	private $categoryModel;

    public function __construct() {
        parent::__construct();

        $this->goodsModel = D('Goods');

        $this->categoryModel = M('goods_category');
    }

    /**
     * [goodsList 商品列表]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function goodsList() {
        $goods_code = I('get.goods_code', '', 'urldecode');
        $goods_name = I('get.goods_name', '', 'urldecode');
        $is_on_sale = I('get.is_on_sale', '-1', 'string');
        $category_path = I('get.category_path', '-1', 'string');
        $where = array(
            // 'agent_id' => $this->agentId,
            'is_delete' => '0',
            'goods_main_id' => '0'
        );
        $link_parameter = '';
        // 商品编码
        if ( !empty($goods_code) ) {
            $where['goods_code'] = array('LIKE', "%{$goods_code}%");
            $link_parameter .= '/goods_code/' . $goods_code;
        }

        //商品名称
        if ( !empty($goods_name) ) {
            $where['goods_name'] = array('LIKE', "%{$goods_name}%");
            $link_parameter .= '/goods_name/' . $goods_name;
        }

        //所有分类
        if ( $category_path != '-1' ) {
            $where['category_path'] = array('LIKE', "{$category_path}%");
            $link_parameter .= '/category_path/' . $category_path;
        }

        //商品状态
        if ( $is_on_sale != '-1' ) {
            $where['is_on_sale'] = $is_on_sale;
            $link_parameter .= '/is_on_sale/' . $is_on_sale;
        }

        $count = $this->goodsModel->where($where)->count();
        $page = new \Think\Page($count, 25);
        $page->setConfig('link', '/Admin/Goods/goodsList/p/zz' . $link_parameter);
        $show = $page->show();
        $counting = $page->totalRows;
        $list = $this->goodsModel->where($where)->order('`id` DESC')->limit($page->firstRow.','.$page->listRows)->select();
        foreach ($list as $key => &$value) {
            $path = explode(',', trim($value['category_path']));
            $name = '';
            foreach ($path as $vo) {
                $name .= getGoodsCategoryName($vo) . '->';
            }
            $value['category_name'] = trim($name, '->');
        }

        $return = array(
            'goods_code' => $goods_code,
            'goods_name' => $goods_name,
            'is_on_sale' => $is_on_sale,
            'category_path' => $category_path,
        );

        //获得商品分类
        $categoryData = $this->categoryModel->order('sort desc')->select();
        $categoryData = $this->recursiveCategory('0',$categoryData);

        $this->assign('show', $show);
        $this->assign('list', $list);
        $this->assign('return', $return);
        $this->assign('counting', $counting);
        $this->assign('categoryData', $categoryData);
        $this->display();
    }

    /**
     * [addGoods 发布商品]
     * @author kofu <418382595@qq.com>
     * @modify cdd <2042536829@qq.com>
     * @copyright Copyright (c) 2016 Xcrozz (http://www.xcrozz.com)
     */
    public function addGoods() {
        if ( IS_POST ) {
            // dump($_POST);die;
            $data = $this->goodsModel->create(I('post.'), 1);
            $SKUattr = I('post.SKUattr');
            $SKUprice = I('post.SKUprice');
            $SKUnumber = I('post.SKUnumber');
            $SKUimage = I('post.SKUimage');
            //开启事务
            $mysql = M();
            $mysql->startTrans();
            if ( !empty($data) ) {
                // $data['agent_id'] = $this->agentId;
                // 保存商品图片
                $images = I('images', '', 'urldecode');
                if (!empty($images)) {
                    $goodsImages = $this->goodsModel->addGoodsImages($images);
                    $data['goods_image'] = $goodsImages['goods_image'];
                    $data['goods_images_id'] = $goodsImages['goods_images_id'];
                }
                // 添加商品详情
                $description = I('description', '', 'htmlspecialchars_decode');
                $extensionId = $this->goodsModel->addGoodsExtension($description);
                $data['goods_ext_id'] = $extensionId;
                // 分类路径
                $where['id'] = $data['category_id'];
                $data['category_path'] = M('goods_category')->where($where)->getField('category_path');
                // 处理SKU
                if ($SKUattr) {
                    $dataArr = array();
                    foreach ($SKUattr as $key => $value) {
                        $addData = array();
                        $value = trim($value,',');
                        $data['attr_list'] = ','. $value .',';
                        $relevanceAttr[] = str_replace(',', '-', $value);
                        $addData['goods_price'] = $SKUprice[$key];
                        $addData['goods_number'] = $SKUnumber[$key];
                        if( empty($SKUimage[$key]) ){
                            //没有属性图片
                            $addData['goods_image'] = $data['goods_image'];
                        }else{
                            $addData['goods_image'] = $SKUimage[$key];
                        }
                        $addData = array_merge($data, $addData);
                        $dataArr[] = $addData;
                    }
                    $dataCount = count($dataArr);
                    $goodsId = $this->goodsModel->addAll($dataArr);
                    if ( $goodsId ) {
                        for( $i=0; $i<$dataCount; $i++ ){
                            $goodsIdArr[] = $goodsId + $i;
                        }
                        foreach ($goodsIdArr as $key => $value) {
                            if ($goodsIdArr[$key] != $goodsId) {
                                $this->goodsModel->where(array('id'=> $goodsIdArr[$key]))->save(array('goods_main_id'=> $goodsId));
                            }
                        }
                        // 添加商品关联
                        $i = $this->goodsModel->addGoodsRelevance($goodsIdArr, $relevanceAttr);

                        if($i !== false){
                            $mysql->commit();
                            echo statusCode();
                        }else{
                            $mysql->rollback();
                        }
                    } else {
                        $mysql->rollback();
                        echo statusCode(array(), 100002);
                    }
                } else {
                    ( $this->goodsModel->add($data) ) ?
                        exit( statusCode() )  :
                        exit( statusCode(array(), 100002) );
                }
            } else {
                $mysql->rollback();
                echo (statusCode(array(), 400000, $this->goodsModel->getError()) );
            }
        } else {
            $goodsCategoryModel = M('goods_category');
            $attrNameModel = M('goods_attr_name');
            $attrValueModel = M('goods_attr_value');


            $categoryData = $goodsCategoryModel->where(array('pid'=> '0'))->select();
            // 属性
            $attrNameData = $attrNameModel->select();
            foreach ($attrNameData as $key => &$value) {
                $where = array(
                    'agent_id' => $this->agentId,
                    'attr_name_id' => $value['id']
                );
                $value['attrValueData'] = $attrValueModel->where($where)->select();
            }
            $this->assign('categoryData', $categoryData);
            $this->assign('attrNameData', $attrNameData);
            $this->display();
        }
    }  

    /**
     * [editGoods 编辑商品]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function editGoods() {
        if ( IS_POST ) {
            $data = $this->goodsModel->create(I('post.'), 2);
            $SKUattr = I('post.SKUattr');
            $SKUprice = I('post.SKUprice');
            $SKUnumber = I('post.SKUnumber');
            $SKUimage = I('post.SKUimage');
            if ( !empty($data) ) {
                $goodsId = $data['id'];
                // $data['agent_id'] = $this->agentId;
                // 获取商品信息
                $goodsData = $this->goodsModel->find($goodsId);
                // 保存商品图片
                $images = I('images', '', 'urldecode');
                if (!empty($images)) {
                    $goodsImages = $this->goodsModel->editGoodsImages($images, $goodsData);
                    $data['goods_image'] = $goodsImages['goods_image'];
                    $data['goods_images_id'] = $goodsImages['goods_images_id'];
                } else{
                    exit(statusCode(array(), 400000, '请上传商品图片'));
                }
                // 添加商品详情
                $description = I('description', '', 'htmlspecialchars_decode');
                $extensionId = $this->goodsModel->editGoodsExtension($description, $goodsData);
                $data['goods_ext_id'] = $extensionId;
                // 分类路径
                $where['id'] = $data['category_id'];
                $data['category_path'] = M('goods_category')->where($where)->getField('category_path');
                // 处理SKU
                if ($SKUattr) {
                    $skuGoods = $this->goodsModel->where(array('goods_main_id'=> $goodsId))->select();
                    //往第一键插入数组
                    array_unshift($skuGoods, $goodsData);
                    if ( !empty($skuGoods) ) {
                        $goodsArr = array();
                        $skuCount = count($SKUattr);
                        $this->goodsModel->where(array('goods_main_id'=>$goodsId))->save(array('is_delete'=>'1'));
                        $this->goodsModel->save(array('id'=> $goodsId, 'is_delete'=> '1'));
                        $goodsMainId = $goodsId;
                        foreach ($SKUattr as $key => $value) {
                            $isRecover = false;
                            $thisGoods = '';
                            $value = trim($value,',');
                            $data['attr_list'] = ','. $value .',';
                            $relevanceAttr[] = str_replace(',', '-', $value);
                            foreach ($skuGoods as $v) {
                                if( strpos($v['attr_list'], $data['attr_list']) !== false ) {
                                    $isRecover = true;
                                    $thisGoods = $v['id'];
                                    break;
                                }
                            }
                            $editData = array();
                            $editData['goods_price'] = $SKUprice[$key];
                            $editData['goods_number'] = $SKUnumber[$key];
                            $editData['goods_image'] = $SKUimage[$key];
                            $editData = array_merge($data, $editData);
                            // 是否恢复已删除数据
                            if ($isRecover === false) {
                                unset($editData['id']);
                                $editData['add_time'] = time();
                                $goodsArr[$key] = $this->goodsModel->add($editData);
                            } else {
                                $goodsArr[$key] = $thisGoods;
                                $editData['id'] = $thisGoods;
                                $editData['is_delete'] = 0;
                                $this->goodsModel->save($editData);
                            }
                            $goodsMainId = ( strpos($data['attr_list'], $goodsData['attr_list']) !== false) ? $goodsMainId : 0; 
                        }
                        $goodsCount  = count($goodsArr);
                        for( $i=0; $i<$goodsCount; $i++ ) {
                            $saveData['id'] = $goodsArr[$i];
                            if ( !empty($goodsMainId) ) {
                                $saveData['goods_main_id'] = ( $saveData['id'] == $goodsMainId ) ? 0 : $goodsMainId;
                            } else {
                                $saveData['goods_main_id'] = ( $i == 0 ) ? 0 : $goodsArr[0];
                            }
                            $this->goodsModel->save($saveData);
                        }
                        // 编辑商品关联
                        $this->goodsModel->editGoodsRelevance($goodsArr, $relevanceAttr, $goodsData);
                        echo statusCode();
                    }
                } else {
                    $data['relevance_id'] = 0;
                    $this->goodsModel->where(array('goods_main_id'=> $goodsId))->save(array('relevance_id'=>'0'));
                    echo ( $this->goodsModel->save($data) !== false ) ?
                         (statusCode()) :
                         (statusCode(array(), 100002));
                }
            } else {
                echo statusCode(array(), 400000, $this->goodsModel->getError());
            }
        } else {
            $attrNameModel = M('goods_attr_name');
            $attrValueModel = M('goods_attr_value');
            $goodsId = I('id');
            $data = D('Goods')->getGoodsInfo($goodsId);
            $goodsInfo = $data['goodsInfo'];
            // 平台分类
            $goodsCategoryModel = M('goods_category');
            $categoryData = $goodsCategoryModel->where(array('pid'=> '0'))->select();
            $goodsInfo['categoryData'] = explode(',', trim($goodsInfo['category_path'], ','));
            if ( count($goodsInfo['categoryData']) > 1 ) {
                $childCategoryData = $goodsCategoryModel->where(array('pid'=> $goodsInfo['categoryData'][0]))->select();
                $this->assign('childCategoryData', $childCategoryData);
            }
            // 属性
            $attrNameData = $attrNameModel->where(['category_id'=>$goodsInfo['categoryData'][1]])->select();
            foreach ($attrNameData as $key => &$value) {
                $where = array(
                    // 'agent_id' => $this->agentId,
                    'name_id' => $value['id']
                );
                $value['attrValueData'] = $attrValueModel->where($where)->select();
            }
            // dump($relevanceData);
            // dump($attrNameData);
            $this->assign('goodsDesc', $data['goodsDesc']);
            $this->assign('goodsInfo', $goodsInfo);
            $this->assign('goodsImages', $data['goodsImages']);
            $this->assign('relevanceData', $data['relevanceData']);
            $this->assign('categoryData', $categoryData);
            $this->assign('attrNameData', $attrNameData);
            $this->display();
        }
    }

    /**
     * [setOnSale 商品上下架]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function setOnSale() {
        $ids = I('get.ids', '', 'urldecode');
        $is_on_sale = I('get.is_on_sale');
        if ( empty($ids) ) {
            $this->error('请选择商品');
        } elseif ( $this->goodsModel->where(array('id' => array('IN', $ids)))->save(array('is_on_sale'=> $is_on_sale)) !== false )  {
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
    }

    /**
     * [delGoods 删除商品]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function delGoods() {
        $ids = I('get.ids', '', 'urldecode');
        if ( empty($ids) ) {
            $this->error('请选择商品');
        } elseif ( $this->goodsModel->deleteGoods($ids) !== false )  {
            $this->success('编辑成功');
        } else {
            $this->error('编辑失败');
        }
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
     * [getChildGoodsCategory 得到下级分类]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function getChildGoodsCategory() {
        $pid = I('pid', '', 'string');
        $goodsCategoryModel = M('goods_category');
        $categoryData = $goodsCategoryModel->where(array('pid'=> $pid))->select();
        echo statusCode(array('categoryData'=> $categoryData));
    }
    /**
     * [categoryAttr 获取分类属性]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function categoryAttr($category_id) {
        $categoryId = empty($category_id) ? I('post.category_id', '') : $category_id;
        $goodsModel = D('Goods');
        $attrData = $goodsModel->getCategoryAttr($categoryId);
        if ( IS_POST ) {
            echo statusCode(array('list'=> $attrData));
        } else {
            echo statusCode(array(), 100001);
        }
    }

    /**
     * [photoUpload 上传商品图片]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function photoUpload() {
        // 图片保存路径
        $parameters['multi'] = true;
        $parameters['size'][0] = array('width'=>600, 'height'=>600);
        // $parameters['size'][1] = array('width'=>350, 'height'=>350);
        // $parameters['size'][2] = array('width'=>160, 'height'=>160);

        fileUpload('Goods_pic/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        }, $parameters);
    }
    /**
     * [photoUpload 上传商品描述图片]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function goodsUpload() {
        // 图片保存路径
        $parameters['multi'] = true;
        $parameters['size'][0] = array('width'=>600, 'height'=>600);
        // $parameters['size'][1] = array('width'=>350, 'height'=>350);
        // $parameters['size'][2] = array('width'=>160, 'height'=>160);

        fileUpload('GoodsDesc/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        }, $parameters);
    }

    /**
     * [Uploadfile 上传视频]
     * @author xu <[565657400@qq.com]>
     */
    public function Uploadfile(){
        $uploadPath = C('UPLOAD_PATH');
        $savePath = $uploadPath . 'videofile/';
        if ( !file_exists($savePath) ) {
            mkdir($savePath, 0700, true);
        }

        $upload            = new \Think\Upload();
        $upload->maxSize   = 90145728 ;
        $upload->exts      = array('mp4','rm','avi','mov','mpeg4','rmvb','mkv','mp3','wma','aac','wav','flac','ogg','ape' );
        $upload->rootPath  = $savePath; 
        $info              = $upload->upload();
        if ( !$info ) {
           echo $upload->getError();
        }else{
           $keys = array_keys($info);
           $key  = $keys[0];
           $one  = $info[$key];
           $src  = $savePath . $one['savepath'] . $one['savename'];
           $one['src']  = trim($src,'.');
           echo json_encode($one);
        }

    }
    /*********迁移数据****************/
    public function test(){
        die;
        //处理商品属性值
        $attrValueList = M('goods_attr_value2')->select();
        foreach ($attrValueList as $key => $value) {
            $data['name_id'] = $value['attr_name_id'];
            $data['attr_value'] = $value['attr_value'];
            M('goods_attr_value')->data($data)->add();
        }
        die;
        //检查商品表的图片途径
        $list = M('goods')->where(['goods_main_id'=>0])->field('id,goods_image')->select();
        $idArr = [];
        foreach ($list as $key => $value) {
           if( !strstr($value['goods_image'],"/Static/Uploads/Goods/") ){
            $idArr[] = $value['id'];
            M('goods')->where(['id'=>$value['id']])->data(['goods_image'=>'/Static/Uploads/Goods/'.$value['goods_image']])->save();
           }
        }
        dump($idArr);
        die;
        //修改商品图片表路径
        $this->changePath();die;
        //添加商品
        $list = M('goods2')->select();
        $dataArr = [];
        foreach ($list as $key => $value) {
            $dataArr[$key]['is_on_sale'] = '1';
            $dataArr[$key]['images'] = M('goods_images2')->where(['goods_id'=>$value['id']])->getField('goods_image',true);
            $dataArr[$key]['SKUattr'] = $this->getSKUattr($value['goods_attr']);
            $dataArr[$key]['goods_name'] = $value['goods_name'];
            $dataArr[$key]['description'] = $value['goods_detail'] ? $value['goods_detail'] : '';
            $dataArr[$key]['category_id'] = '2';
            $dataArr[$key]['SKUimage'] = [0=>$value['goods_image']];
        }
        foreach ($dataArr as $key => $value) {
            $this->removeGoods($value);
        }
    }
    public function changePath(){
        $model = M('goods_images');
        $list = $model->limit('1000,1000')->select();
        // dump($list);die;
        foreach ($list as $key => $value) {
            $data['goods_image'] = '/Static/Uploads/Goods/' . $value['goods_image'];
            $data['goods_big_image'] = '/Static/Uploads/Goods/' . $value['goods_big_image'];
            $model->where(['id'=>$value['id']])->data($data)->save();
        }
    }
    public function getSKUattr($goods_attr){//1,4,5
        if(empty($goods_attr)){
            return '';
        }
        $attr = ','.trim($goods_attr) . ',';
        if( strstr($attr,",1,") ){
            $attr = str_replace(',1,','',$attr);
            $arr = explode(',',trim($attr,','));
            foreach ($arr as $key => &$value) {
               $value = '1,' . $value;
            }
            
        }else{
            $arr = explode(',', trim($attr,','));
        }
        return $arr;
    }
    public function removeGoods($value) {
            $data = $this->goodsModel->create($value, 1);
            $SKUattr = $value['SKUattr'];
            $SKUprice = $value['SKUprice'];
            $SKUnumber = $value['SKUnumber'];
            $SKUimage = $value['SKUimage'];
            //开启事务
            $mysql = M();
            $mysql->startTrans();
            if ( !empty($data) ) {
                // $data['agent_id'] = $this->agentId;
                // 保存商品图片
                $images = $value['images'];
                // $images = I('images', '', 'urldecode');
                if (!empty($images)) {
                    $goodsImages = $this->goodsModel->addGoodsImages($images);
                    $data['goods_image'] = $goodsImages['goods_image'];
                    $data['goods_images_id'] = $goodsImages['goods_images_id'];
                }
                // 添加商品详情
                // $description = I('description', '', 'htmlspecialchars_decode');
                $description = htmlspecialchars_decode($value['description']);
                $extensionId = $this->goodsModel->addGoodsExtension($description);
                $data['goods_ext_id'] = $extensionId;
                // 分类路径
                $where['id'] = $data['category_id'];
                $data['category_path'] = M('goods_category')->where($where)->getField('category_path');
                // 处理SKU
                if ($SKUattr) {
                    $dataArr = array();
                    foreach ($SKUattr as $key => $value) {
                        $addData = array();
                        $value = trim($value,',');
                        $data['attr_list'] = ','. $value .',';
                        $relevanceAttr[] = str_replace(',', '-', $value);
                        $addData['goods_price'] = 0;
                        $addData['goods_number'] = 0;
                        $addData['goods_image'] = $SKUimage[$key];
                        // $addData['goods_image'] = $SKUimage[$key];
                        $addData = array_merge($data, $addData);
                        $dataArr[] = $addData;
                    }
                    $dataCount = count($dataArr);
                    $goodsId = $this->goodsModel->addAll($dataArr);
                    if ( $goodsId ) {
                        for( $i=0; $i<$dataCount; $i++ ){
                            $goodsIdArr[] = $goodsId + $i;
                        }
                        foreach ($goodsIdArr as $key => $value) {
                            if ($goodsIdArr[$key] != $goodsId) {
                                $this->goodsModel->where(array('id'=> $goodsIdArr[$key]))->save(array('goods_main_id'=> $goodsId));
                            }
                        }
                        // 添加商品关联
                        $i = $this->goodsModel->addGoodsRelevance($goodsIdArr, $relevanceAttr);

                        if($i !== false){
                            $mysql->commit();
                            echo statusCode();
                        }else{
                            $mysql->rollback();
                        }
                    } else {
                        $mysql->rollback();
                        echo statusCode(array(), 100002);
                    }
                } else {
                    if( $this->goodsModel->add($data) ){
                        $mysql->commit();
                        // exit( statusCode() );
                    }else{
                         // exit( statusCode(array(), 100002) );
                    }
                    // ( $this->goodsModel->add($data) ) ?
                    //     exit( statusCode() )  :
                    //     exit( statusCode(array(), 100002) );
                }
            } else {
                $mysql->rollback();
                echo (statusCode(array(), 400000, $this->goodsModel->getError()) );
            }
        }
        /*********迁移数据****************/
        /**
         * [setTop 置顶文章]
         * @author cdd <2042536829@qq.com>
         * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
         */
        public function setTop() {
            if (IS_POST) {
                $isTop = I('post.is_top');
                $id = I('post.id');
                dump(I('post.'));
                if ($isTop === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

                if (M('goods')->where(array('id'=>$id))->save(array('is_top'=>$isTop)) !== false) {
                    echo json_encode(array('error'=>0));
                }
            } else {
                echo json_encode(array('error'=>1,'msg'=>'非法访问'));
            }
        }
}