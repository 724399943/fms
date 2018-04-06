<?php
namespace Bbs\Controller;
use Think\Controller;
use Bbs\Controller\BaseController;
class IndexController extends BaseController {

	public function __construct() {
		parent::__construct();
	}

	/**
     * [index 论坛首页]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function index() {
		$this->display();
	}

	/**
     * [module 论坛模块]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function module() {
		if (IS_POST) {
			$id = I('post.id');
			$moduleModel = M('bbs_module');
			$moduleDetail = $moduleModel->where(array('id'=>$id))->find();
			echo statusCode(array('moduleDetail'=>$moduleDetail)) ;
		} else {
			$this->display();	
		}
	}

	/**
     * [module_list 版块列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function module_list() {
		if (IS_POST) {
			$dbPrefix = C('DB_PREFIX');
			$whereStr = "";
			$moduleListSql = "SELECT `id`, `module_name`, `introduction`, `icon` 
							  FROM `{$dbPrefix}bbs_module` 
							  WHERE 1{$where}";
			$moduleListData = M()->query($moduleListSql);
			echo statusCode(array('moduleList'=>$moduleListData));
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [article_detail 帖子详情]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_detail() {
		if (IS_POST) {
			$id = I('post.id');
			$userModel = M('user');
			$articleModel = M('bbs_article');
			$articlePhotoModel = M('bbs_article_photo');
			$data = array();
			$data['articleDetail'] = $articleModel->where(array('id'=>$id))->find();
			$data['articleDetail']['photos'] = $articlePhotoModel->where(array('article_id'=>$id))->select();
			$data['authorDetail'] = $userModel->where(array('id'=>$data['articleDetail']['author']))->field('headimgurl, nickname')->find();
			$data['quickrespone'] = M('bbs_quickrespone')->select();
			echo statusCode(array('data'=>$data)) ;
		} else {
			$this->display();
		}
	}

	/**
     * [article_list 文章列表]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_list() {
		if (IS_POST) {
			// $articleModel = M('bbs_article');
			$dbPrefix = C('DB_PREFIX');
			$whereStr = "";
			$orderStr = "ORDER BY `a`.`id` DESC";

			$moduleId = I('post.module_id');
			if (!empty($moduleId)) $whereStr .= " AND module_id={$moduleId}";

			$type = I('post.type');
			if (!empty($type)) {
				switch ($type) {
					case 'top':
						// 置顶
						$whereStr .= ' AND `a`.`is_top`=1';
						break;
					case 'hot':
						// 热门
						$orderStr = ' ORDER BY `a`.`view_number` DESC';
						break;
					case 'reco':
						// 精华
						$whereStr .= ' AND `a`.`is_recommend`=1';
						break;
					case 'new':
						// 最新
						$orderStr = ' ORDER BY `a`.`add_time` DESC';
						break;
					default:
						break;
				}
			}

			$articleSql = "SELECT `a`.*, `u`.`nickname`, `u`.`headimgurl` 
							FROM {$dbPrefix}bbs_article AS `a` 
							LEFT JOIN {$dbPrefix}user AS `u` ON `a`.`author`=`u`.`id` 
							WHERE 1 {$whereStr}
							{$orderStr}
							{$this->limitStr}";
			$articleList = M()->query($articleSql);
			echo statusCode(array('articleList'=>$articleList));
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [article_comment 文章评论]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_comment() {
		if (IS_POST) {
			$id = I('post.id');
			$commentModel = M('bbs_article_comment');
			$commentList = $commentModel->where(array('article_id'=>$id, 'status'=>1))->order('add_time DESC')->select();
			$commentPhotoModel = M('bbs_comment_photo');
			foreach ($commentList as $key => &$value) {
				$photos = $commentPhotoModel->where(array('article_id'=>$value['id']))->select();
				$value['photos'] = $photos;
			}
			echo statusCode(array('commentList'=>$commentList));
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [article_collect 收藏帖子]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_collect() {
		if (IS_POST) {
			$userId = session('userId');
			// $type = I('post.type', 1);
			$articleId = I('post.id');
			$articleModel = M('bbs_article');
			$collectModel = M('bbs_article_collect');

			if ($collectModel->where(array('user_id'=>$userId, 'article_id'=>$articleId))->count() > 0) {
				echo statusCode(array(), 400000, '您已经收藏过这个帖子了');
			} else {
				if ($collectModel->add(array('user_id'=>$userId, 'article_id'=>$articleId))) {
					$articleModel->where(array('id'=>$articleId))->setInc('collect_number');
					echo statusCode();
				}
			}
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [module_collect 收藏帖子]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function module_collect() {
		if (IS_POST) {
			$userId = session('userId');
			// $type = I('post.type', 1);
			$moduleId = I('post.id');
			$moduleModel = M('bbs_module');
			$collectModel = M('bbs_module_collect');

			if ($collectModel->where(array('user_id'=>$userId, 'module_id'=>$moduleId))->count() > 0) {
				echo statusCode(array(), 400000, '您已经收藏过这个版块了');
			} else {
				if ($collectModel->add(array('user_id'=>$userId, 'module_id'=>$moduleId))) {
					echo statusCode();
				}
			}
		} else {
			statusCode(array(), 400000, '非法访问');
		}
	}

	/**
     * [article_like 点赞帖子]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_like() {
		if (IS_POST) {
			$userId = session('userId');
			// $type = I('post.type', 1);
			$articleId = I('post.id');
			$articleModel = M('bbs_article');
			$likeModel = M('bbs_article_like');

			if ($likeModel->where(array('user_id'=>$userId, 'article_id'=>$articleId))->count() > 0) {
				echo statusCode(array(), 400000, '您已经点赞过这个帖子了');
			} else {
				if ($likeModel->add(array('user_id'=>$userId, 'article_id'=>$articleId))) {
					$articleModel->where(array('id'=>$articleId))->setInc('like_number');
					echo statusCode() ;
				}
			}
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [article_like 点赞帖子]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_view() {
		if (IS_POST) {
			$articleModel = M('bbs_article');
			$userId = session('userId');
			$articleId = I('post.id');
			if (empty($articleId)) exit(statusCode(array(), 400000, '参数缺失'));
			$articleModel->where(array('id'=>$articleId))->setInc('view_number');
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [article_post 发表帖子]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_post() {
		if (IS_POST) {
			$userId = session('userId');
			$articleModel = D('bbs_article');
			$data = $articleModel->create();
			$data['author'] = $userId;
			$photos = I('post.photos', '');

			$articleId = $articleModel->add($data);
			if ($articleId) {
				if (!empty($photos)) {
					$commentPhotoModel = M('bbs_article_photo');
					foreach ($photos as $key => $photo) {
						$photoData['article_id'] = $articleId;
						$photoData['url'] = $photo;
						$photoData['add_time'] = time();
						$commentPhotoModel->add($photoData);
					}
				}
				$articleModel->where(array('id'=>$articleId))->data(array('image'=>$photos[0]))->save();
				echo statusCode();
			} else {
				echo statusCode(array(), 400000, '帖子发布失败');
			}
		} else {
			$this->display();
		}
	}

	/**
     * [article_response 发表回复]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function article_response() {
		if (IS_POST) {
			$userId = session('userId');
			$userDetail = M('user')->where(array('id'=>$userId))->field('`id` AS  `user_id`, `nickname`, `headimgurl`, `level`')->find();
			$commentModel = D('bbs_article_comment');
			$data = $commentModel->create();
			$data = array_merge($data, $userDetail);
			$photos = I('post.photos', '');

			$articleId = $commentModel->add($data);
			if ($articleId) {
				if (!empty($photos)) {
					$commentPhotoModel = M('bbs_comment_photo');
					foreach ($photos as $key => $photo) {
						$photoData['article_id'] = $articleId;
						$photoData['url'] = $photo;
						$photoData['add_time'] = time();
						$commentPhotoModel->add($photoData);
					}
				}
				// M('bbs_article')->where(array('id'=>$data['article_id']))->setInc('comment_number');
				// echo statusCode();
			} else {
				exit(statusCode(array(), 400000, '回复失败'));
			}
		} else {
			exit(statusCode(array(), 400000, '非法访问'));
		}
	}

	/**
     * [search 发表回复]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function search() {
		if (IS_POST) {

		} else {
			$this->display();
		}
	}

	/**
     * [uploadImg 上传图片]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function uploadImg() {
		fileUpload('Article/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
	}

	/**
     * [uploadPostImg 上传帖子图片]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function uploadPostImg() {
		fileUpload('Article/', function($e) {
            echo json_encode(array('error'=>0, 'url'=>trim($e['filePath'], '.')));
        });
	}
}