<?php

namespace Home\Controller;


use Plugins\Storage\Goods\Goods;

class GoodsController extends BaseController
{
    public function goods()
    {
        $seo = self::getSysSeo('ch_product_index');
        $this->assign('seo', $seo);
        $this->display($this->template . '/product_list');
    }


    public function goodsDetail()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数丢失！');
        }
        $instance = Goods::getInstance();
        $info = $instance::getGoodsDetail($id);


        $seo = [];
        if (empty($info['tags']) || empty($info['meta_keyword']) || empty($info['meta_description'])) {

            $tags = !empty($info['tags']) ? $seo['tags'] = $info['tags'] : 'tags';
            $meta_keyword = !empty($info['meta_keyword']) ? $seo['meta_keyword'] = $info['meta_keyword'] : 'meta_keyword';
            $meta_description = !empty($info['meta_description']) ? $seo['meta_description'] = $info['meta_description'] : 'meta_description';


            $data = [
                $tags, $meta_keyword, $meta_description
            ];
            $seo = $seo + self::getLevelSeo('ch_product_index', $info['cat_id'], $data);
        }

        $result = [
            'info' => $info,
            'seo' => $seo,
        ];
        $this->assign($result);
        $this->display(C('wapTemplet') . '/product_detail');
    }

    /**
     * [getProductList 产品列表]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)    2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $index [标识]
     * @param     integer       $cid   [分类id]
     * @param     integer       $page  [页码]
     * @return    [type]               [description]
     */
    public function getGoodsList($index = 'list', $page = 1, $category_id = 0){
        if($index == 'list'){
            $param = [
                'sign' => $index,
                'category_id' => $category_id,
                'page' => $page,
            ];
            $goods = \Plugins\Storage\Goods\Goods::getInstance();
            $goodsList = $goods::getGoodsList($param);

            $goodsList =  $goodsList ? $goodsList : [];
            echo statusCode($goodsList,200000,'成功');
        }
    }
    public function test(){

    }

}