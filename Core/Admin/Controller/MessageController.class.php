<?php
namespace Admin\Controller;
use Think\Controller;

class MessageController extends BaseController{

	private $guestModel;

	public function __construct(){
		parent::__construct();
		$this->guestModel = M('guest_book');
	}

	/**
	 * [messageList 留言列表]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function messageList(){
		$title = I('title','','urldecode');
		if( $title != ''){
			$where['title'] = ['LIKE',"%{$title}%"];
		}
		$list = $this->guestModel->where($where)->select();
		$return = [
			'title' => $title,
		];
		$this->assign([
			'list' => $list,
			'return' => $return,
		]);
		$this->display();
	}
	/**
	 * [replyMessage 回复留言]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function replyMessage(){
		if(IS_POST){
			$data = $this->guestModel->create(I('post.',''));
			if( empty($data) ){
				$this->error($this->guestModel->getError());
			}else{
				$data['reply_flag'] = '1';
				$data['reply_time'] = time();
				$result = $this->guestModel->data($data)->save();
				( $result === false) ? $this->error('回复失败') : $this->success('回复成功',U('Message/messageList'));
			}
		}else{
			$id = I('get.id','');
			$data = $this->guestModel->where(['id'=>$id])->find();
			$this->assign([
				'data' => $data,
			]);
			$this->display();
		}
	}

	public function delMessage(){
		$ids = I('get.ids','');
		if( empty($ids) ){
			$this->error('参数丢失');
		}elseif( $this->guestModel->where(['id'=>['IN',$ids]])->delete() !== false ){
			$this->success('删除留言成功');
		}else{
			$this->error('删除留言失败');
		}
	}
}