<?php
namespace Admin\Controller;
use Think\Controller;
//文章模型
class AdController extends BaseController{

	private $adGroupModel;
	private $adModel;

	public function __construct(){
		parent::__construct();
		$this->adGroupModel = D('ad_group');
		$this->adModel = D('ad');
	}
	public function adGroupList(){
		$list = $this->adGroupModel->select();
		foreach ($list as $key => &$value) {
			$value['ad_count'] = $this->adModel->where(['group_id'=>$value['id']])->count();
		}
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * [addAdGroup 添加版位]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addAdGroup(){
		if(IS_POST){
			$data = $this->adGroupModel->create(I('post.'),1);
			if(empty($data)){
				$this->error($this->adGroupModel->getError());
			}else{
				$data['add_time'] = time();
				$i = $this->adGroupModel->data($data)->add();
				(!$i) ? $this->error('添加失败') : $this->success('添加成功',U('Ad/adGroupList'));
			}
		}else{

			$this->display();
		}
	}
	/**
	 * [editAdGroup 编辑版位]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function editAdGroup(){
		if(IS_POST){
			$data = $this->adGroupModel->create(I('post.'),2);
			if(empty($data)){
				$this->error($this->adGroupModel->getError());
			}else{
				$data['add_time'] = time();
				$i = $this->adGroupModel->data($data)->save();
				($i === false) ? $this->error('编辑失败') : $this->success('编辑成功',U('Ad/adGroupList'));
			}
		}else{
			$id = I('get.id','');
			$data = $this->adGroupModel->where(['id'=>$id])->find();
			$this->assign('data',$data);
			$this->display();
		}
	}
	/**
	 * [delAdGroup 删除广告版位]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function delAdGroup(){
		$id = I('get.id','');
		if(empty($id)){
			$this->error('参数丢失');
		}elseif( $this->adGroupModel->where(['id'=>$id])->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
	/**
	 * [setAdGroupStatus 设置广告位状态]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function setAdGroupStatus(){
		if(IS_POST){
			$id = I('post.id');
			dump(I('post.'));
			$status = I('post.status');
			$result = $this->adGroupModel->where(['id'=>$id])->data(array('is_delete' => $status))->save();
			(!$result) ? exit(statusCode(array(),400000,'更改失败')) : exit(statusCode(array(),200000,'更改成功'));
		}else{
			exit(statusCode('','非法',100001));
		}
		
	}
	/**
	 * [adList 广告列表]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function adList(){
		$group_id = I('get.group_id','-1');
		$ad_name = I('get.ad_name','','urldecode');
		if($group_id != '-1'){
			$where['group_id'] = $group_id;
		}
		if($ad_name != ''){
			$where['ad_name'] = ['LIKE',"%{$ad_name}%"];
		}
		$list = $this->adModel->where($where)->order('sort')->select();
		foreach ($list as $key => &$value) {
			$value['group_name'] = $this->adGroupModel->where(['id'=>$value['group_id']])->getField('`group_name`');
		}
		$adGroupList = $this->adGroupModel->field('`id`,`group_name`')->select();
		$return = [
			'group_id' => $group_id,
			'ad_name' => $ad_name,
		];
		$this->assign([
			'list'=>$list,
			'adGroupList' => $adGroupList,
			'return' => $return,
		]);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * [addAd 添加广告]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
	 */
	public function addAd(){
		if(IS_POST){
			$data = $this->adModel->create( I('post.',''),1 );
			if(empty($data)){
				$this->error($this->adModel->getError());
			}else{
				$data['add_time'] = time();
				$result = $this->adModel->data($data)->add();
				(!$result) ? $this->error('添加失败') : $this->success('添加成功',U('Ad/adList'));
			}
		}else{
			//获得广告版位
			$adGroupList = $this->adGroupModel->where(['is_delete'=>'0'])->field('`id`,`group_name`')->select();
			$this->assign('adGroupList',$adGroupList);
			$this->display();
		}
	}
	public function editAd(){
		if(IS_POST){
			$data = $this->adModel->create( I('post.',''),2 );
			if(empty($data)){
				$this->error($this->adModel->getError);
			}else{
				$data['add_time'] = time();
				$result = $this->adModel->data($data)->save();
				($result === false) ? $this->error('编辑失败') : $this->success('编辑成功',U('Ad/adList'));
			}
		}else{
			$id = I('get.id','');
			//获得广告版位
			$adGroupList = $this->adGroupModel->where(['is_delete'=>'0'])->field('`id`,`group_name`')->select();
			$this->assign('adGroupList',$adGroupList);
			//广告信息
			$data = $this->adModel->where(['id'=>$id])->find();
			$this->assign('data',$data);
			$this->display();
		}
	}
	/**
	 * [photoUpload 上传图片]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function photoUpload() {
        fileUpload('Ad/', function($e) {
            echo json_encode(array('error'=>'', 'src'=>trim($e['filePath'], '.')));
        }, $parameters);
    }
    /**
     * [setAdGroupStatus 设置广告状态]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function setAdStatus(){
		if(IS_POST){
			$id = I('post.id');
			$status = I('post.status');
			$result = $this->adModel->where(['id'=>$id])->data(array('is_delete' => $status))->save();
			($result=== false) ? exit(statusCode(array(),400000,'更改失败')) : exit(statusCode(array(),200000,'更改成功'));
		}else{
			exit(statusCode('','非法',100001));
		}
		
	}
	public function delAd(){
		$ids = I('get.ids');
		if(empty($ids)){
			$this->error('参数丢失');
		}elseif( $this->adModel->where(['id'=>['IN',$ids]])->delete() ){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}