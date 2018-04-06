<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8/008
 * Time: 11:52
 */

namespace Plugins\Storage\Product;

interface InterfaceProduct
{
    /**
     * getProductList  [description]
     * @param string $index
     * @param int $cid
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getProductList($index = 'index', $cid = 0);


    /**
     * getProductDetail  [description]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getProductDetail($id);
    /**
     * [getProductCategory 获得产品一级分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)    2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $index [description]
     * @param     [type]        $cid   [description]
     * @return    [type]               [description]
     */
    static public function getProductCategory($cid);
    /**
     * [getProductCategory 获得商品一级分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)    2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $index [description]
     * @param     integer       $cid   [description]
     * @return    [type]               [description]
     */
    // static public function getProductCategory($index = 'index', $cid = 0);

    /**
     * getPrevious  [上一条]
     * @param $treeid
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getPrevious($id);


    /**
     * getNext  [下一条]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getNext($id);
}