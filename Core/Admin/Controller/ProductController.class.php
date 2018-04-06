<?php
namespace Admin\Controller;
use Think\Controller;
//文章模型
class ProductController extends BaseController{

	private $productModel;

	private $categoryModel;

	private $dbPrefix;

	private $moduleAttrModel;

	public function __construct(){
		parent::__construct();
		$this->productModel = D('product');
		$this->categoryModel = D('category');
		$this->dbPrefix = C('DB_PREFIX');
		$this->moduleAttrModel = M('module_attr');

		$firstCat = getFirstCat();
		$firstCatId = I('get.firstCatId');
		$this->assign([
			'firstCatId' => $firstCatId,
			'firstCat' => $firstCat,
			]);
	}
	/**
	 * [articleList 文章模型列表]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function productList(){
		// $categoryModel = M('category');
		// $firstCatId = I('get.firstCatId','');
		$product_name = I('get.product_name','','urldecode');
		$cat_id = I('get.cat_id','-1','urldecode');

		if($product_name != ''){
			$where['`a`.`product_name`'] = array('LIKE',"%{$product_name}%");
		}
		if($cat_id != '-1'){
			//所有的子分类集合
			$categoryList = $this->categoryModel->where(array('tree_id'=>$this->firstCatId))->select();
			$categoryList = $this->getSubs($categoryList,$cat_id,1);
			$categoryList = implode(',',$categoryList) . ','. $cat_id;
			$where['`a`.`cat_id`'] = array('IN',trim($categoryList,','));
		}
		$where['`a`.`tree_id`'] = $this->firstCatId;

		//分页
		$count = $this->productModel->alias('`a`')->join("LEFT JOIN `{$this->dbPrefix}category` AS `c` ON `a`.`cat_id` = `c`.`id`")
				->where($where)->count();
		$page = new \Think\Page($count,25);
		$show = $page->show();
		$counting = $page->totalRows;

		//文章内容
		$list = $this->productModel->alias('`a`')->join("LEFT JOIN `{$this->dbPrefix}category` AS `c` ON `a`.`cat_id` = `c`.`id`")
				->where($where)->field("`a`.*,`c`.`cat_name`")->order('`a`.`id` DESC')->limit($page->firstRow,$page->listRows)->select();

		//文章类型
		$categoryList = $this->categoryModel->where(array('tree_id' => $this->firstCatId))->select();
		$categoryList = recursiveCategory($this->firstCatId,$categoryList);
		// dump($this->firstCatId);
		$return = array(
			'cat_id' => $cat_id,
			'product_name'    => $product_name,
			'cat_name' => $this->categoryModel->where(array('id'=>$this->firstCatId))->getField('`cat_name`')
		);
		$this->assign(array(
			'list' => $list,
			'show' => $show,
			'counting' => $counting,
			'categoryList' => $categoryList,
			'return' => $return,
		));
		$this->display();
	}


	/**
	 * [addArticle 添加产品内容]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addProduct(){
		// $categoryModel = M('category');
		// $firstCatId = I('get.firstCatId','');//文章模型 一级分类id
		if(IS_POST){
			$postData = I('post.');
			// dump($postData);die;
			$data = $this->productModel->create($postData,1);
			if(empty($data)){
				$this->error($this->productModel->getError());
			}else{
				//处理文章
				$data['product_sn'] = 'SN' . time();
				$data['content'] = htmlspecialchars_decode($data['content']);
				$data['upload_files'] = $postData['upload_files']['0'];
				// $data['thumb_files'] = $data['upload_files'];
				$data['add_time'] = time();
				$catData = $this->categoryModel->where(array('id'=>$data['cat_id']))->find();
				$data['modalias'] = $catData['modalias'];
				$data['tree_id'] = $catData['cat_path'];//一级分类的id
				// dump($data['tree_id']);
				$attrData = $this->productModel->reAttrField($postData['attr_value_ids'],$data['tree_id']);
				$data['goods_attr'] = $attrData['goods_attr'];
				$data['goods_attr_list'] = $attrData['goods_attr_list'];

				//开启事务
				$mysql = M();
				$mysql->startTrans();
				//添加属性
				$addId = $this->productModel->data($data)->add();
				if( $addId ){
					//添加商品图集
					$i = $this->productModel->addGoodsImages($addId,$postData['upload_files']);
					if(!$i){
						$mysql->rollback();
						$this->error('添加产品图集失败');
					}else{
						$mysql->commit();
						$this->success('添加产品成功',U('Product/productList',array('firstCatId'=>$data['tree_id'])));
					}

				}else{
					$mysql->rollback();
					$this->error('添加产品失败');

				}
			}

		}else{
			$extendFieldList = getExtendFields($this->firstCatId);
			$attrValueData = $this->productModel->getAttrData();
			// dump($extendFieldList);
			//获得模板
			$dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
			$this->assign('dir',$dir);
			$where = array(
				'status' => '1',
				// 'module_id' => '',
				'tree_id' => $this->firstCatId,
			);
			//获得所属分类
			$categoryList = $this->categoryModel->where($where)->select();
			$categoryList = recursiveCategory($this->firstCatId,$categoryList);
			$this->assign('categoryList',$categoryList);
			$this->assign('extendFieldList',$extendFieldList);
			$this->assign('attrValueData',$attrValueData);
			// $this->assign('firstCatId',$firstCatId);
			$this->display();
		}
		
	}
	/**
	 * [delArticle 删除文章内容]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function delProduct(){
		$ids = I('get.ids','');

		//删除article_attr属性表内容
		$articleAttrModel = M('product_attr');
		$data = $articleAttrModel->where(['rel_id' => ['IN',$ids]])->find();
		if( ( $articleAttrModel->where(['rel_id' => ['IN',$ids]])->delete() ) || empty($data) ){
			$cat_id = $this->productModel->where(array('id'=>array('IN',$ids)))->getField('tree_id');

			//删除文章内容
			if( $this->productModel->where(array('id'=>array('IN',$ids)))->delete() !== false )
				$this->success('删除成功',U('Product/productList',array('firstCatId'=>$cat_id)));
			else
				$this->error('删除失败');
		}else{
			$this->error('删除关联属性失败');
		}
		
	}
	// 图片上传
    public function photoUpload() {
    	fileUpload('Product/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
    }
    public function editProduct(){
    	$id = I('get.id','');
    	// $categoryModel = M('category');
    	$articleAttrModel = M('product_attr');
    	$moduleAttrModel = M('module_attr');
    	if(IS_POST){
    		$postData = I('post.');
    		// dump($postData);die;
    		$data = $this->productModel->create($postData,2);
    		if(empty($data))
    			$this->error($this->productModel->getError());
    		else{
    			$mysql = M();
    			$mysql->startTrans();
				$data['content'] = htmlspecialchars_decode($data['content']);
				$data['update_time'] = time();
				$catData = $this->categoryModel->where(array('id'=>$data['cat_id']))->find();
				$data['modalias'] = $catData['modalias'];
				$data['tree_id'] = $catData['cat_path'];//一级分类的id

				$attrData = $this->productModel->reAttrField($postData['attr_value_ids'],$data['tree_id']);
				$data['goods_attr'] = $attrData['goods_attr'];
				$data['goods_attr_list'] = $attrData['goods_attr_list'];

				//编辑图集
				$images = $this->productModel->editGoodsImages($data['id'],$postData['upload_files']);
				$data['upload_files'] = $images['goods_image'];

				if( $this->productModel->data($data)->save() !== false ){
					$mysql->commit();
					$this->success('编辑成功',U('Product/productList',array('firstCatId'=>$data['tree_id'])));
				}
				else{
					$this->rollback();
					$this->error('编辑失败');
				}
    			
    		}
    	}else{
    		//获得模板
			$dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
			$this->assign('dir',$dir);
    		//获得文章信息
    		$articleData = $this->productModel->where(array('id'=>$id))->find();
    		// 商品图片集
    		$articleData['images'] = M('goods_images')->where(['goods_id'=>$id])->select();
    		// dump($articleData);
    		//获得所属分类
    		$where = array(
				'status' => '1',
				'tree_id' => $articleData['tree_id'],

			);
    		$categoryList = $this->categoryModel->where($where)->select();
    		$categoryList = recursiveCategory($articleData['tree_id'],$categoryList);
    		//属性值列表
    		$attrValueData = $this->productModel->getAttrData();
    		//商品属性
    		$attrValueModel = M('goods_attr_value');
    		$articleData['attr_value'] = $this->productModel->getAttrData(['id'=>['IN',$articleData['goods_attr']]]);
    		$this->assign(array(
    			'categoryList' => $categoryList,
    			'articleData'  => $articleData,
    			'extendData' => $extendData,
    			'extendFieldList' => $extendFieldList,
    			'attrValueData' => $attrValueData,
    			'firstCatId' => $articleData['tree_id'],
    		));
    		$this->display();
    	}
    }
    /**
     * [getSubs 获得子类id集]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)        2017          Xcrozz (http://www.xcrozz.com)
     * @param     [array]        $categorys [数组]
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

	/**
	 * [searchAttr 搜索属性名 属性值]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function searchAttr(){
		// dump($this->firstCatId);
		$searchInfo = I('post.searchInfo','','urldecode');
		$firstCatId = I('post.firstCatId');
		//搜索属性名称
		$where = [
			'tree_id' => $firstCatId,
			'module_id' => getModuleId($firstCatId),
			'type_name' => ['LIKE',"%{$searchInfo}%"],
		];
		$attrName = $this->moduleAttrModel->where($where) ->select();
		if(!$attrName){
			//搜索属性值
			$where = ['attr_value'=>['LIKE',"%{$searchInfo}%"]];
		}else{
			//查询有关此属性名称的属性值列表
			$whereStr = '';
			foreach ($attrName as $key => $value) {
				$whereStr .= $value['id'] . ',';
			}
			$where = ['attr_name_id'=>['IN',$whereStr]];
			
		}
		$attrValueData = $this->productModel->getAttrData($where);
		echo statusCode(array('attrValueData'=>$attrValueData), 200000, '成功');
	}
}