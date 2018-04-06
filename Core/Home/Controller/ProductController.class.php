<?php

namespace Home\Controller;


use Plugins\Storage\Product\Product;

class ProductController extends BaseController
{
    public function product()
    {
        $seo = self::getSysSeo('ch_product_index');
        $this->assign('seo', $seo);
        $this->display($this->template . '/product_list');
    }


    public function productDetail()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数丢失！');
        }
        $instance = Product::getInstance();

        $info = $instance::getProductDetail($id);

        $previous = $instance::getPrevious($info['id']);
        $next = $instance::getNext($info['id']);

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
            'previous' => $previous ? $previous : '没有了！',
            'next' => $next ? $next : '没有了！',
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
    public function getProductList($index = 'list', $cid = 0, $page = 1, $catId = 0){
        if($index == 'list'){
            $product = \Plugins\Storage\Product\Product::getInstance();
            $goodsList = $product::getProductList($index,$cid,$page,$catId);

            $goodsList =  $goodsList ? $goodsList : [];
            echo statusCode($goodsList,200000,'成功');
        }
    }
    /********数据***********/
    // public function removeData(){
    //     $nowModel = M('goods');
    //     $beforeModel = M('goods2');
    //     $relModel = M('goods_relevance');
    //     $list = $beforeModel->where('id<2')->select();

    //     $mysql = M();
    //     $mysql->startTrans();
    //     $dataArr = [];

    //     // $goodsImages = $this->goodsModel->addGoodsImages($images);
    //     // $data['goods_image'] = $goodsImages['goods_image'];
    //     // $data['goods_images_id'] = $goodsImages['goods_images_id'];
    //     foreach ($list as $key => $value) {
    //         //商品图片
    //         $images = M('goods_images2')->where(['goods_id'=>$value['id']])->getField('goods_image',true);
    //         $goodsImages = $this->addGoodsImages($images);
    //         $data['goods_image'] = $goodsImages['goods_image'];
    //         $data['goods_images_id'] = $goodsImages['goods_images_id'];
    //         // 添加商品详情
    //         $description = $value['goods_detail'];
    //         $extensionId = $this->addGoodsExtension($description);
    //         $data['goods_ext_id'] = $extensionId;            
    //        //  //商品表
    //        //  $goods_attr = ',' . trim($value['goods_attr'],',') . ',';//,1,2,3,
    //        // $goods_attr = str_replace(',1,','',$goods_attr);
    //        // $attrArr = explode(',',trim($goods_attr));
    //        // // foreach ($attrArr as $k => $v) {
    //        // //      $data['goods_name'] = $value['goods_name'];
    //        // //      $data['goods_image'] = $value['goods_image'];
    //        // //      $data['attr_list'] = '1,'.$v;
    //        // //      $data['add_time'] = time();
    //        // // }
    //     }
    // }

    // public function addGoodsImages($images) {
    //     $goodsImagesModel = M('goods_images2');
    //     $goods_images_id = array();
    //     foreach ($images as $key => $value) {
    //         // $goods_image = str_replace('/600x600', '', $value);
    //         // $goods_image = str_replace('_600x600', '', $goods_image);
    //         $temp = [
    //             'goods_image' => $value,
    //             // 'goods_middle_image' => str_replace('600x600', '350x350', $value),
    //             // 'goods_small_image' => str_replace('600x600', '160x160', $value),
    //             'goods_big_image' => $value,
    //         ];
    //         $goods_images_id[] = $goodsImagesModel->add($temp);
    //     }
        
    //     return array(
    //         'goods_image' => $images[0],
    //         'goods_images_id' => implode(',', $goods_images_id)
    //     );
    // }
    // public function addGoodsExtension($description) {
    //     $goodsExtensionModel = M('goods_extension');
    //     $descriptionData = array(
    //         'goods_desc' => $description,
    //         'add_time'   => time(),
    //     );
    //     $extensionId = $goodsExtensionModel->add($descriptionData);
    //     return $extensionId;
    // }
    /********数据***********/
}