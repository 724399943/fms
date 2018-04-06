<?php

namespace Home\Controller;

use Think\Template\TagLib;

class TagController extends TagLib
{

    protected $tags = [
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'Product' => ['attr' => 'name, sign, cid', 'close' => 0],
        'category' => ['attr' => 'name, moduleId, rootId', 'close' => 0],
        'information' => ['attr' => 'name, sign, cid', 'close' => 0],
        'article' => ['attr' => 'name, sign, cid', 'close' => 0],
        'swiper' => ['attr' => 'name, sign, group_id', 'close' => 0],
        'productCategory' => ['attr' => 'name, cid', 'close' => 0],
        'about' => ['attr' => 'name, sign,tree_id,cat_id', 'close' => 0],
        'adgroup' => array('attr' => 'name, sign', 'close' => 0),
        'goods' => ['attr' => 'name, sign, category_id', 'close' => 0],
        'goodsCategory' => ['attr' => 'name, pid', 'close' => 0],
        // 'goodslist' => array('attr' => 'name, ids', 'close' => 0),
        // 'brandlist' => array('attr' => 'name, ids', 'close' => 0),
        // 'tagList' => array('attr' => 'name', 'close' => 0),
        // 'linkList' => array('attr' => 'name', 'close' => 0),
    ];


    public function _product($tag)
    {

        $name = $this->autoBuildVar($tag['name']);

        $sign = $tag['sign'];
        $cid = $tag['cid'];
        $value = 'getProduct(' . $sign . ', ' . $cid . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _goods($tag)
    {

        $name = $this->autoBuildVar($tag['name']);

        $sign = $tag['sign'];
        $category_id = $tag['category_id'];
        $value = 'getGoods(' . $sign . ', ' . $category_id . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _information($tag)
    {
        $name = $this->autoBuildVar($tag['name']);

        $sign = $tag['sign'];
        $cid = $tag['cid'];

        $value = 'getInformation(' . $sign . ', ' . $cid . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _category($tag)
    {
        $name = $this->autoBuildVar($tag['name']);


        $moduleId = $tag['moduleid'];
        $rootId = $tag['rootid'];

        $value = 'getCategory(' . $moduleId . ', ' . $rootId . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _article($tag)
    {
        $name = $this->autoBuildVar($tag['name']);


        $sign = $tag['sign'];
        $catid = $tag['catid'];

        $value = 'getArticle(' . $sign . ', ' . $catid . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _swiper($tag)
    {
        $name = $this->autoBuildVar($tag['name']);

        $sign = $tag['sign'];
        $group_id = $tag['group_id'];

        $value = 'getSwiper(' . $sign . ', ' . $group_id . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    public function _productCategory($tag)
    {
        $name = $this->autoBuildVar($tag['name']);

        $cid = $tag['cid'];

        $value = 'getProductCategory(' . $cid . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        // dump($parseStr);
        return $parseStr;
    }

    public function _goodsCategory($tag)
    {
        $name = $this->autoBuildVar($tag['name']);

        $pid = $tag['pid'];

        $value = 'getGoodsCategory(' . $pid . ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        // dump($parseStr);
        return $parseStr;
    }

    public function _about($tag)
    {
        $name = $this->autoBuildVar($tag['name']);

        $sign = $tag['sign'];
        $tree_id = $tag['tree_id'];
        $cat_id = $tag['cat_id'];

        $value = 'getAbout(' . $sign . ',' . $tree_id . ',' . $cat_id. ')';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        // dump($parseStr);die;
        // dump($parseStr);
        return $parseStr;
    }

    /**
     * [_ad 广告位]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function _ad($tag, $content)
    {
        $name = $this->autoBuildVar($tag['name']);
        if (empty($tag['sign'])) {
            return;
        }

        $value = 'getAdBox("' . $tag['sign'] . '")';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    /**
     * [_adgroup 分组广告位]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function _adgroup($tag, $content)
    {
        $name = $this->autoBuildVar($tag['name']);
        if (empty($tag['sign'])) {
            return;
        }
        // echo 123;die;
        $value = 'getAdGroup("' . $tag['sign'] . '")';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    /**
     * [_goodslist 商品列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function _goodslist($tag, $content)
    {
        $name = $this->autoBuildVar($tag['name']);
        // if ( empty($tag['ids']) ) {
        //     $tag['ids'] = getIds($tag['name']);
        // }

        $value = 'getGoodsList("' . $tag['name'] . '")';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    /**
     * [_linkList 链接列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function _linkList($tag, $content)
    {
        $name = $this->autoBuildVar($tag['name']);

        $value = 'getLinkList("' . $tag['name'] . '")';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }

    /**goodslist
     * [_tagList description]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)      2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $tag     [description]
     * @param     [type]        $content [description]
     * @return    [type]                 [description]
     */
    public function _tagList($tag, $content)
    {
        $name = $this->autoBuildVar($tag['name']);

        $value = 'getGoodsTag("' . $tag['name'] . '")';
        $parseStr = '<?php echo ' . $value . '; ?>';
        return $parseStr;
    }

    /**
     * [_brandlist 品牌列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function _brandlist($tag, $centent)
    {
        $name = $this->autoBuildVar($tag['name']);
        if (empty($tag['ids'])) {
            return;
        }

        $value = 'getGoodsList("' . $tag['ids'] . '")';
        $parseStr = '<?php ' . $name . ' = ' . $value . '; ?>';
        return $parseStr;
    }
}