<?php
namespace Admin\Controller;
class SinglePageController extends BaseController{

	private $aboutModel;

	private $dbPrefix;

	private $categoryModel;

	public function __construct(){
		parent::__construct();
		$this->aboutModel = D('about');
		$this->categoryModel = M('category');
		$this->dbPrefix = C('DB_PREFIX');
		$firstCat = getFirstCat();
		$this->assign('firstCat',$firstCat);
	}

	// public function editSingle(){
	// 	if(IS_POST){
			
	// 	}else{
	// 		$id = I('get.id');
	// 		//单页信息
	// 		$data = $this->aboutModel->where(['id'=>$id])->find();
	// 		$this->assign([
	// 			'data'=>$data
	// 		]);

	// 		$this->display();
	// 	}
	// }

	public function editSingle(){
    	if(IS_POST){
    		$postData = I('post.');
    		$data = $this->aboutModel->create($postData,2);
    		if(empty($data)){
    			$this->error($this->aboutModel->getError());
    		}else{
    			$result = $this->aboutModel->where(['id'=>$data['id']])->save();
    			if( $result === false){
    				$this->error('编辑内容失败');
    			}else{
    				$this->success('编辑内容成功');
    			}
    		}
    		// $postData = I('post.');
    		// $data = $this->articleModel->create($postData,2);
    		// if(empty($data))
    		// 	$this->error($this->articleModel->getError());
    		// else{
    		// 	//编辑扩展字段
    		// 	$result = $this->articleModel->editExtendField($postData['attr_id'],$postData['field']);
    		// 	if( !$result ){
    		// 		$this->error('编辑扩展字段失败');
    		// 	}else{
    		// 		$data['content'] = htmlspecialchars_decode($data['content']);
    		// 		$data['update_time'] = time();
    		// 		$catData = $categoryModel->where(array('id'=>$data['cat_id']))->find();
    		// 		$data['modalias'] = $catData['modalias'];
    		// 		$data['tree_id'] = $catData['cat_path'];//一级分类的id

    		// 		if( $this->articleModel->data($data)->save() !== false )
    		// 			$this->success('编辑内容成功',U('Article/articleList',array('firstCatId'=>$data['tree_id'])));
    		// 		else
    		// 			$this->error('编辑内容失败');
    		// 	}
    			
    		// }
    	}else{
	    	$id = I('get.id','');
    		//获得模板
			$dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
			$this->assign('dir',$dir);
    		//获得单页信息
    		$singleData = $this->aboutModel->where(array('cat_id'=>$id))->find();
    		//获得所属分类
    		$this->assign(array(
    			'categoryList' => $categoryList,
    			'singleData'  => $singleData,
    			'extendData' => $extendData,
    			'extendFieldList' => $extendFieldList,
    			'firstCatId' => $singleData['tree_id'],
    		));
    		$this->display();
    	}
    }
	// 图片上传
    public function photoUpload() {
    	fileUpload('SinglePage/', function($e) {
            echo json_encode(array('error'=>0, 'src'=>trim($e['filePath'], '.')));
        });
    }
}