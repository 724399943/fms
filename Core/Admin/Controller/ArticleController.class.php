<?php
namespace Admin\Controller;
use Think\Controller;
//文章模型
class ArticleController extends BaseController{

	private $articleModel;
	private $dbPrefix;

	public function __construct(){
		parent::__construct();
		$this->articleModel = D('article');
		$this->dbPrefix = C('DB_PREFIX');
		$firstCat = getFirstCat();
		$this->assign('firstCat',$firstCat);
	}
	/**
	 * [articleList 文章模型列表]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function articleList(){
		$categoryModel = M('category');
		$firstCatId = I('get.firstCatId','');
		$title = I('get.title','','urldecode');
		$cat_id = I('get.cat_id','-1','urldecode');

		if($title != ''){
			$where['`a`.`title`'] = array('LIKE',"%{$title}%");
		}
		if($cat_id != '-1'){
			//所有的子分类集合
			$categoryList = $categoryModel->where(array('tree_id'=>$firstCatId))->select();
			$categoryList = $this->getSubs($categoryList,$cat_id,1);
			$categoryList = implode(',',$categoryList) . ','. $cat_id;
			$where['`a`.`cat_id`'] = array('IN',$categoryList);
		}
		$where['`a`.`tree_id`'] = $firstCatId;

		//分页
		$count = $this->articleModel->alias('`a`')->join("LEFT JOIN `{$this->dbPrefix}category` AS `c` ON `a`.`cat_id` = `c`.`id`")
				->where($where)->count();
		$page = new \Think\Page($count,25);
		$show = $page->show();
		$counting = $page->totalRows;

		//文章内容
		$list = $this->articleModel->alias('`a`')->join("LEFT JOIN `{$this->dbPrefix}category` AS `c` ON `a`.`cat_id` = `c`.`id`")
				->where($where)->field("`a`.*,`c`.`cat_name`")->order('`a`.`id` DESC')->limit($page->firstRow,$page->listRows)->select();

		//文章类型
		$categoryList = $categoryModel->where(array('tree_id' => $firstCatId))->select();
		$categoryList = recursiveCategory($firstCatId,$categoryList);

		$return = array(
			'cat_id' => $cat_id,
			'title'    => $title,
			'cat_name' => $categoryModel->where(array('id'=>$firstCatId))->getField('`cat_name`')
		);
		$this->assign(array(
			'list' => $list,
			'show' => $show,
			'counting' => $counting,
			'categoryList' => $categoryList,
			'return' => $return,
			'firstCatId' => $firstCatId,
		));
		$this->display();
	}


	/**
	 * [addArticle 添加文章内容]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addArticle(){
		$categoryModel = M('category');
		$firstCatId = I('get.firstCatId','');//文章模型 一级分类id
		if(IS_POST){
			$postData = I('post.');
			$data = $this->articleModel->create($postData,1);
			if(empty($data)){
				$this->error($this->articleModel->getError());
			}else{
				// dump($postData);die;
				//处理文章
				$data['content'] = htmlspecialchars_decode($data['content']);
				$data['add_time'] = time();
				$catData = $categoryModel->where(array('id'=>$data['cat_id']))->find();
				$data['modalias'] = $catData['modalias'];
				$data['tree_id'] = $catData['cat_path'];//一级分类的id
				$addId = $this->articleModel->data($data)->add();
				// $addId = 1;
				if( $addId ){
					$articleAttrModel = M('article_attr');
					//处理拓展字段属性
					$returnData = $this->articleModel->addExtendField( $postData['module_attr_id'],$postData['field'] );

					if(!empty($returnData)){
						$articleIdArr = [];
						for( $i=0; $i<=$returnData['dataCount']; $i++){
							$articleIdArr[] = $returnData['goodsId'] + $i;
						}
						foreach ($articleIdArr as $key => $value) {
							$articleAttrModel->where(['id'=>$value])->save(['rel_id'=>$addId]);
						}
						$this->success('添加文章内容成功',U('Article/articleList',array('firstCatId'=>$data['tree_id'])));
					}else{
						$this->error('添加拓展属性失败');
					}

				}else
					$this->error('添加文章内容失败');
			}

		}else{
			$extendFieldList = getExtendFields($firstCatId);
			// dump($extendFieldList);
			//获得模板
			$dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
			$this->assign('dir',$dir);
			$where = array(
				'status' => '1',
				'module_id' => '1',
				'tree_id' => $firstCatId,
			);
			//获得所属分类
			$categoryList = $categoryModel->where($where)->select();
			$categoryList = recursiveCategory($firstCatId,$categoryList);
			$this->assign('categoryList',$categoryList);
			$this->assign('extendFieldList',$extendFieldList);
			$this->assign('firstCatId',$firstCatId);
			$this->display();
		}
		
	}
	/**
	 * [delArticle 删除文章内容]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function delArticle(){
		$ids = I('get.ids','');

		//删除article_attr属性表内容
		$articleAttrModel = M('article_attr');
		if( $articleAttrModel->where(['rel_id' => ['IN',$ids]])->delete() ) {
			$cat_id = $this->articleModel->where(array('id'=>array('IN',$ids)))->getField('tree_id');

			//删除文章内容
			if( $this->articleModel->where(array('id'=>array('IN',$ids)))->delete() !== false ) {
				$this->success('删除文章成功',U('Article/articleList',array('firstCatId'=>$cat_id)));
			} else {
				$this->error('删除文章失败');
			}
		} else {
			$this->error('删除关联属性失败');
		}
		
	}
	// 图片上传
    public function photoUpload() {
    	fileUpload('Article/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
    }

    public function editArticle(){
    	$id = I('get.id','');
    	$categoryModel = M('category');
    	$articleAttrModel = M('article_attr');
    	$moduleAttrModel = M('module_attr');
    	if(IS_POST){
    		$postData = I('post.');
    		$data = $this->articleModel->create($postData,2);
    		if(empty($data))
    			$this->error($this->articleModel->getError());
    		else{
    			//编辑扩展字段
    			$result = $this->articleModel->editExtendField($postData['attr_id'],$postData['field']);
    			if( !$result ){
    				$this->error('编辑扩展字段失败');
    			}else{
    				$data['content'] = htmlspecialchars_decode($data['content']);
    				$data['update_time'] = time();
    				$catData = $categoryModel->where(array('id'=>$data['cat_id']))->find();
    				$data['modalias'] = $catData['modalias'];
    				$data['tree_id'] = $catData['cat_path'];//一级分类的id

    				if( $this->articleModel->data($data)->save() !== false )
    					$this->success('编辑内容成功',U('Article/articleList',array('firstCatId'=>$data['tree_id'])));
    				else
    					$this->error('编辑内容失败');
    			}
    			
    		}
    	}else{
    		//获得模板
			$dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
			$this->assign('dir',$dir);
    		//获得文章信息
    		$articleData = $this->articleModel->where(array('id'=>$id))->find();
    		//获得所属分类
    		$where = array(
				'status' => '1',
				'module_id' => '1',
				'tree_id' => $articleData['tree_id'],

			);
    		$categoryList = $categoryModel->where($where)->select();
    		$categoryList = recursiveCategory($articleData['tree_id'],$categoryList);
    		//获得所有拓展字段
    		$extendFieldList = getExtendFields($articleData['tree_id']);
    		foreach ($extendFieldList as $key => &$value) {
    			$data = $articleAttrModel->where(['module_attr_id' => $value['id'],'rel_id'=>$id])->field('`ext_value`,`id`')->find();
    			if( $value['input_type'] == 'checkbox' ){
    				$value['ext_value'] = explode(',',$data['ext_value']);
    			}else{
    				$value['ext_value'] = $data['ext_value'];
    			}
    			$value['attr_id'] = $data['id'];
    		}
    		$this->assign(array(
    			'categoryList' => $categoryList,
    			'articleData'  => $articleData,
    			'extendData' => $extendData,
    			'extendFieldList' => $extendFieldList,
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
	 * [setTop 置顶文章]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function setTop() {
		if (IS_POST) {
			$isTop = I('post.is_top');
			$id = I('post.id');
			if ($isTop === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('article')->where(array('id'=>$id))->save(array('is_top'=>$isTop)) !== false) {
				echo json_encode(array('error'=>0));
			}
		} else {
			echo json_encode(array('error'=>1,'msg'=>'非法访问'));
		}
	}
}