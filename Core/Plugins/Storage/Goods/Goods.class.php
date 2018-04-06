<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8/008
 * Time: 11:51
 */

namespace Plugins\Storage\Goods;

class Goods implements InterfaceGoods
{
    /**
     * @var 单例
     */
    private static $instance;

    private static $categoryModel;

    private static $productModel;

    private static $productAttrModel;

    private static $goodsModel;

    private static $page;

    private static $limitStart;

    private static $limitStr;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct()
    {
        // self::$categoryModel = M('category');
        // self::$productModel = M('product');
        // self::$productAttrModel = M('product_attr');
        self::$goodsModel = D('goods');
        // self::load_limit();
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
    static public function getGoodsList($params)
    {
        $index = $params['sign'] ? $params['sign'] : 'index';
        $page = $params['page'] ? $params['page'] : '1';
        $category_id = $params['category_id'] ? $params['category_id'] : '';

        if($category_id != ''){
            $where['category_id'] = $category_id;
        }
        $where['goods_main_id'] = '0';
        $where['is_delete'] = '0';
        $field = '`id`,`category_id`,`category_path`,`goods_image`,`goods_name`';

        if($index == 'index'){
            $goodsList['list'] = self::$goodsModel->order('is_top DESC, add_time DESC ')
                                ->field($field)->where($where)
                                ->limit(9)
                                ->select();
        }else{
            $limit = C('wapProductPage');
            $page = intval($page) - 1;
            $page = $page < 0 ? 0 : $page;
            $limitStart = $page * $limit;

            $goodsList['list'] = self::$goodsModel->where($where)
                                ->order('is_top DESC,add_time DESC')
                                ->field($field)
                                ->limit($limitStart . ',' . $limit)
                                ->select();
            // dump(self::$goodsModel->getLastSql());
        }
        // dump($goodsList);
        return $goodsList ? $goodsList : [];
    }



    
    static public function getGoodsDetail($id)
    {
        $proInfo = self::$goodsModel->getGoodsInfo($id);
        $proInfo['imgCount']  = count($proInfo['goodsImages']);
        
        return $proInfo;
    }
    /**
     * [getProductCategory 获得产品一级分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)  2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $cid [description]
     * @return    [type]             [description]
     */
    static public function getGoodsCategory($pid = '0'){
        // dump($cid);
        $categoryModel = M('goods_category');

        $category = $categoryModel->where(['pid'=>$pid])->select();
        return $category ? $category : [];
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