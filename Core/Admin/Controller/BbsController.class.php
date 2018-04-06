<?php
namespace Admin\Controller;

use Think\Controller;

class BbsController extends BaseController
{
	/**
     * [BbsArticleList 帖子列表]
     * @author StanleyYuen <[350204080@qq.com]>
    */
    public function BbsArticleList(){
    	$where = array();
    	$link_parameter = '';
        $article_name = I('article_name','','urldecode');
        if (!empty($article_name)) {
            $where['article_name'] = array('LIKE', "%{$article_name}%");
            $link_parameter .= '/article_name/' .$article_name;
        }
        $author = I('author', -1);
        if ($author != '-1') {
            $where['author'] = $author;
            $link_parameter .= '/author/' .$author;
        }
        $module_id = I('module_id', -1);
        if ($module_id != '-1') {
            $where['module_id'] = $module_id;
            $link_parameter .= '/module_id/' .$module_id;
        }
        $is_top = I('is_top', -1);
        if ($is_top != '-1') {
            $where['is_top'] = $is_top;
            $link_parameter .= '/is_top/' .$is_top;
        }
        $is_recommend = I('is_recommend', -1);
        if ($is_recommend != '-1') {
            $where['is_recommend'] = $is_recommend;
            $link_parameter .= '/is_recommend/' .$is_recommend;
        }
        $startTime = I('startTime');
        $endTime   = I('endTime');

        if (!empty($startTime) && !empty($endTime)) {
            $where['add_time'] = array('BETWEEN', "{$startTime},{$endTime}");
            $link_parameter .= '/startTime/' . $startTime . '/endTime/' . $endTime;
        } else {
            $startTime = strtotime('2015-1-1');
            $endTime = strtotime('+1 days');
        }
        $where['is_delete'] = 0;
		$BbsArticleViewModel = D('BbsArticle');
		$count = $BbsArticleViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/Bbs/BbsArticleList/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出
		$list = $BbsArticleViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->select();	//分页查询
		$return = array();
        $return['article_name'] = $article_name;
        $return['author'] = $author;
        $return['module_id'] = $module_id;
        $return['is_top'] = $is_top;
        $return['is_recommend'] = $is_recommend;
        $return['startTime'] = $startTime;
        $return['endTime'] = $endTime;
		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);	
		$this->assign('list', $list);

		$module = M('bbs_module')->select();
		$this->assign('module', $module);

		$user = M('user')->select();
		$this->assign('user', $user);
		
		$this->display();
	}

	/**
     * [addBbsArticle 添加帖子]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function addBbsArticle() {
		if(IS_POST){
			$BbsArticle = D('BbsArticle');
			$data = $BbsArticle->create();
			if($BbsArticle->add($data)){
				$this->success('新建成功', U('BbsArticle/index')); 
			}else{
				$this->error('新建失败', U('BbsArticle/index')); 
			}
		}else{
			$module = M('bbs_module')->select();
			$this->assign('module', $module);
			$this->display(); 
		}
	}
	/**
	 * [BbsArticleDetail 帖子详情]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function BbsArticleDetail(){
		$BbsArticle = D('BbsArticle');
		$commentModel = M('bbs_article_comment');
		$id = I('id','','string');
		$articleData = $BbsArticle->where(['id'=>$id])->find();
		$commentData = $commentModel->where(['article_id'=>$id])->select();
		$this->assign('articleData',$articleData);
		$this->assign('commentData',$commentData);
		// dump($commentData);
		$this->display();
	}
	/**
     * [BbsEditArticle 编辑帖子]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function BbsEditArticle() {
		$BbsArticle = D('BbsArticle');
		if(IS_POST){
			$data = $BbsArticle->create();
			if($BbsArticle->save($data) !== false){
				$this->success('编辑成功', U('BbsArticle/index')); 
			}else{
				$this->error('编辑失败', U('BbsArticle/index')); 
			}
		}else{
			$id = I('id');
			$vo = $BbsArticle->find($id);
			$this->assign('vo', $vo);
			$this->display();
		}
	}

	/**
     * [deleteBbsArticle 删除帖子]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function deleteBbsArticle() {
		$BbsArticle = D('BbsArticle');
		$id = I('id');
		if($BbsArticle->where(array('id'=>$id))->save(array('is_delete'=>1)) !== false) {
			// M('bbs_article_photo')->where(array('article_id'=>$id))->delete();
			exit(json_encode(array('error'=>0)));
			// $this->success('删除成功', U('BbsArticle/index'));
		}else{
			exit(json_encode(array('error'=>1, 'msg'=>'删除失败')));
			// $this->error('删除失败', U('BbsArticle/index'));
		}
	}

	/**
     * [BbsModuleList 版块列表]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function BbsModuleList(){
    	$where = array();
    	$link_parameter = '';
        $module_name = I('module_name');
        if (!empty($module_name)) {
            $where['module_name'] = array('LIKE', "%{$module_name}%");
            $link_parameter .= '/module_name/' .$module_name;
        }
        $is_lock = I('is_lock', -1);
        if ($is_lock != '-1') {
            $where['is_lock'] = $is_lock;
            $link_parameter .= '/is_lock/' .$is_lock;
        }
        $is_recommend = I('is_recommend', -1);
        if ($is_recommend != '-1') {
            $where['is_recommend'] = $is_recommend;
            $link_parameter .= '/is_recommend/' .$is_recommend;
        }
		$BbsModuleViewModel = D('BbsModuleView');
		$count = $BbsModuleViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/BbsModule/index/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出
		$list = $BbsModuleViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->select();	//分页查询
		$return = array();
        $return['module_name'] = $module_name;
        $return['is_lock'] = $is_lock;
        $return['is_recommend'] = $is_recommend;
		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);	
		$this->assign('list', $list);
		$this->display();
	}

	/**
     * [BbsModuleList 添加版块]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function addBbsModule() {
		if(IS_POST){
			$BbsModule = D('BbsModule');
			$data = $BbsModule->create();
			if($BbsModule->add($data)){
				$this->success('新建成功', U('BbsModule/index')); 
			}else{
				$this->error('新建失败', U('BbsModule/index')); 
			}
		}else{
			$this->display(); 
		}
	}

	/**
     * [editBbsModule 编辑版块]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function editBbsModule() {
		$BbsModule = D('BbsModule');
		if(IS_POST){
			$data = $BbsModule->create();
			if($BbsModule->save($data) !== false){
				$this->success('编辑成功', U('Bbs/BbsModuleList')); 
			}else{
				$this->error('编辑失败', U('Bbs/BbsModuleList')); 
			}
		}else{
			$id = I('id'); 
			$vo = $BbsModule->find($id);
			$this->assign('vo', $vo);
			$this->display();
		}
	}

	/**
     * [deleteBbsModule 删除版块]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function deleteBbsModule() {
		$BbsModule = D('BbsModule');
		$id = I('id'); 
		if($BbsModule->delete($id)){
			$this->success('删除成功', U('BbsModule/index'));
		}else{
			$this->error('删除失败', U('BbsModule/index'));
		}
	}

	/**
     * [setTop 设置是否置顶]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function setTop() {
		if (IS_POST) {
			$isTop = I('post.is_top');
			$articleId = I('post.id');
			if ($isTop === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('bbs_article')->where(array('id'=>$articleId))->save(array('is_top'=>$isTop)) !== false) {
				exit(json_encode(array('error'=>0)));
			}
		} else {
			exit(json_encode(array('error'=>1,'msg'=>'非法访问')));
		}
	}

	/**
     * [setReco 设置是否推荐]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function setReco() {
		if (IS_POST) {
			$isReco = I('post.is_recommend');
			$articleId = I('post.id');
			if ($isReco === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('bbs_article')->where(array('id'=>$articleId))->save(array('is_recommend'=>$isReco)) !== false) {
				exit(json_encode(array('error'=>0)));
			}
		} else {
			exit(json_encode(array('error'=>1,'msg'=>'非法访问')));
		}
	}

	/**
     * [setModuleLock 设置模块是否锁定]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function setModuleLock() {
		if (IS_POST) {
			$isTop = I('post.is_lock');
			$articleId = I('post.id');
			if ($isTop === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('bbs_module')->where(array('id'=>$articleId))->save(array('is_lock'=>$isTop)) !== false) {
				exit(json_encode(array('error'=>0)));
			}
		} else {
			exit(json_encode(array('error'=>1,'msg'=>'非法访问')));
		}
	}

	/**
     * [setReco 设置模块是否推荐]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function setModuleReco() {
		if (IS_POST) {
			$isReco = I('post.is_recommend');
			$articleId = I('post.id');
			if ($isReco === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('bbs_module')->where(array('id'=>$articleId))->save(array('is_recommend'=>$isReco)) !== false) {
				exit(json_encode(array('error'=>0)));
			}
		} else {
			exit(json_encode(array('error'=>1,'msg'=>'非法访问')));
		}
	}
	/**
	 * [setModulePost 是否允许用户发帖]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function setModulePost() {
		if (IS_POST) {
			$isPost = I('post.is_post');
			$articleId = I('post.id');
			if ($isPost === null) exit(json_encode(array('error'=>1,'msg'=>'参数丢失')));

			if (M('bbs_module')->where(array('id'=>$articleId))->save(array('is_post'=>$isPost)) !== false) {
				exit(json_encode(array('error'=>0)));
			}
		} else {
			exit(json_encode(array('error'=>1,'msg'=>'非法访问')));
		}
	}

	/**
     * [BbsCommentList 评论列表]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function BbsCommentList() {
    	$where = array();
    	$link_parameter = '';

		$BbsArticleCommentViewModel = D('BbsArticleCommentView');
		// $where['status'] = 1;
		$count = $BbsArticleCommentViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/BbsArticleComment/index/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出
		$list = $BbsArticleCommentViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->select();	//分页查询
		$return = array();

		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);	
		$this->assign('list', $list);
		$this->display();
	}

	/**
     * [delBbsComment 删除评论]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function delBbsComment() {
		if (IS_POST) {
			$BbsArticleComment = D('BbsArticleComment');
			$id = I('id'); 
			if($BbsArticleComment->where(array('id'=>$id))->save(array('status'=>2)) !== false) {
				exit(json_encode(array('error'=>0)));
				// $this->success('删除成功', U('BbsArticleComment/index'));
			}else{
				exit(json_encode(array('error'=>1, 'msg'=>'删除失败')));
				// $this->error('删除失败', U('BbsArticleComment/index'));
			}
		} else {
			exit(json_encode(array('error'=>1, 'msg'=>'非法访问')));
		}
	}

	/**
     * [approveBbsComment 审核评论]
     * @author StanleyYuen <[350204080@qq.com]>
    */
    public function approveBbsComment() {
    	if (IS_POST) {
			$BbsArticleComment = D('BbsArticleComment');
			$id = I('id');
			if($BbsArticleComment->where(array('id'=>$id))->save(array('status'=>1)) !== false) {
				$articleId = M('bbs_article_comment')->where(array('id'=>$id))->getField('article_id');
				M('bbs_article')->where(array('id'=>$articleId))->setInc('comment_number');
				exit(json_encode(array('error'=>0)));
			}else{
				exit(json_encode(array('error'=>1, 'msg'=>'审核失败')));
			}
		} else {
			exit(json_encode(array('error'=>1, 'msg'=>'非法访问')));
		}
    }

	/**
     * [quickRespone 快捷回复列表]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function quickRespone() {
		$data = M('bbs_quickrespone')->select();

		$this->assign('quickrespone', $data);
		$this->display();
	}

	/**
     * [moduleIconSave 上传论坛模块图标]
     * @author StanleyYuen <[350204080@qq.com]>
    */
	public function moduleIconSave() {
		fileUpload('Bbs/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
	}

	public function photoSave() {
		fileUpload('Bbs/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
	}
}
