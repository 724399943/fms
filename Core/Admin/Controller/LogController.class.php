<?php
namespace Admin\Controller;
use Think\Controller;

class LogController extends BaseController{

	private $logModel;

	public function __construct(){
		parent::__construct();
		$this->logModel = M('log');
	}
	/**
	 * [logList 日志列表]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function logList(){
		$count = $this->logModel->count();
		$page = new \Think\Page($count,25);
		$show = $page->show();
		$list = $this->logModel->limit($page->firstRow,$page->listRows)->select();
		$counting = $page->listRows;
		$this->assign('counting',$counting);
		$this->assign('list',$list);
		$this->assign('show',$show);
		$this->display();
	}

	public function delLog(){
		$ids = I('get.ids','');
		if(empty($ids)){
			$this->error('参数丢失');
		}elseif( $this->logModel->where(['id'=>['IN',$ids]])->delete() !== false ){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}