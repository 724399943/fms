<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8/008
 * Time: 11:51
 */

namespace Plugins\Storage\Product;

class Product implements InterfaceProduct
{
    /**
     * @var 单例
     */
    private static $instance;

    private static $categoryModel;

    private static $productModel;

    private static $productAttrModel;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct()
    {
        self::$categoryModel = M('category');
        self::$productModel = D('product');
        self::$productAttrModel = M('product_attr');
    }


    /**
     * getProductList  [description]
     * @param string $index
     * @param int $cid 一级分类id
     * @param int $catId 直属分类id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @modify cdd <2042536829@qq.com>
     * @return array
     */
    static public function getProductList($index = 'index', $cid = 0, $page = 1, $catId = 0)
    {

        $params = self::$categoryModel->where(['module_id' => 2])->field('id,limit,sort,root_id')->find();


        $cate = recursiveCategory('0', $params);

        if ($cid != 0) {
            $where['tree_id'] = $cid;
        }
        //产品直属分类
        $catId = I('get.catId') ? I('get.catId') : $catId;
        if( $catId != '0'){
            //所有的子分类集合
            $categoryList = self::$categoryModel->where(array('tree_id'=>$cid))->select();
            $categoryList = self::getSubs($categoryList,$catId,1);
            $categoryList = implode(',',$categoryList) . ','. $catId;
            $where['cat_id'] = array('IN',trim($categoryList,','));
        }
        switch ($params['sort']) {
            case 1:
                $sort = ' add_time DESC ';
                break;
            case 2:
                $sort = ' update_time DESC ';
                break;
            case 3:
                $sort = ' add_time DESC ';
                break;
            case 4:
                $sort = ' hits DESC ';
                break;
            case 5:
                $sort = ' id DESC ';
                break;
            case 6:
                $sort = ' id ASC ';
                break;
            default : {
                $sort = ' add_time DESC ';
            }
        }


        $field = 'id,tree_id,cat_id,product_sn,product_name,oprice,bprice,thumb_files,upload_files,add_time,update_time,hits';
        if ($index == 'index') {
            $goodsList['list'] = self::$productModel->order(' add_time DESC ')
                ->field($field)
                ->limit(6)
                ->select();
        } else {
            if ($params['limit'] != 0) {
                $limit = $params['limit'];
            } else {
                $limit = C('wapProductPage');
            }

            $page = $page - 1;
            $page = $page < 0 ? 0 : $page;
            $limitStart = $page * $limit;
            $count = self::$productModel->where($where)->count();

            $page = new \Think\Page($count, $limit);
            $goodsList['list'] = self::$productModel->where($where)
                                ->order($sort)
                                ->field($field)
                                ->limit($limitStart, $page->listRows)
                                ->select();
            $goodsList['page'] = $page->show();
        }
        return $goodsList ? $goodsList : [];
    }

    /**
     * getProductDetail  [description]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @modify cdd <2042536829@qq.com>
     * @return mixed
     */
    static public function getProductDetail($id)
    {
        // TODO: Implement getProductDetail() method.
        $proInfo = self::$productModel->where(['id' => $id])->find();
        /**获得拓展属性值(la_product_attr)**/
        // $attrInfo = self::$productAttrModel->where(['rel_id'=>$id])->field('`module_attr_id`,`ext_value`')->select();
        // $moduleAttrModel = M('module_attr');
        // foreach ($attrInfo as $key => &$value) {
        //     $value['attr_name'] = $moduleAttrModel->where(['id'=>$value['module_attr_id']])->getField('`type_name`');
        // }
        // $proInfo['attr_info'] = $attrInfo;
        /**获得拓展属性值(la_product_attr)**/

        /***获得属性值(la_goods_attr)*/
        // $model = D('product');
        $attrInfo = self::$productModel->getAttrNameData($proInfo);
        $proInfo['attrInfo'] = $attrInfo;
        /***获得属性值(la_goods_attr)*/
        
        return $proInfo;
    }
    /**
     * [getProductCategory 获得产品一级分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)  2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $cid [description]
     * @return    [type]             [description]
     */
    static public function getProductCategory($cid){
        // dump($cid);
        $categoryModel = M('category');
        $productCategory = $categoryModel->where(['tree_id'=>$cid,'depth'=>'1'])->select();
        return $productCategory ? $productCategory : [];
    }

    /**
     * getPrevious  [上一条]
     * @param $treeid
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getPrevious($id)
    {
        // TODO: Implement getPrevious() method.
        $id = intval($id);

        $info = self::$productModel->where(" id < {$id} ")
            ->field('id,product_name')
            ->limit(1)
            ->order(' id DESC ')
            ->find();
        return $info ? $info : [];
    }

    /**
     * getNext  [下一条]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getNext($id)
    {
        // TODO: Implement getNext() method.

        $id = intval($id);

        $info = self::$productModel->where(" id > {$id} ")
            ->field('id,product_name')
            ->limit(1)
            ->order(' id ASC ')
            ->find();
        return $info ? $info : [];
    }

    /**
     * [getSubs 获得产品子类id集]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)        2017          Xcrozz (http://www.xcrozz.com)
     * @param     [array]        $categorys [一级分类下的所有数组]
     * @param     integer       $catId     [description]
     * @param     integer       $level     [description]
     * @return    [type]                   [description]
     */
    public function getSubs($categorys,$catId=0,$level=1){  
        $subs=array();  
        foreach($categorys as $item){  
            if($item['root_id'] == $catId){  
                $item['level'] = $level;  
                $subs[] = $item['id'];  
                $subs = array_merge($subs,$this->getSubs($categorys,$item['id'],$level+1));  
                  
            }  
                  
        }
        return $subs;  
    }  

}