<?php
namespace Admin\Controller;
use Think\Controller;
// 系统控制器
class SystemController extends BaseController {

    private $categoryModel;
    private $dbPrefix;

    public function __construct(){
        parent::__construct();
        $this->categoryModel = M('category');
        $this->dbPrefix = C('DB_PREFIX');
    }

    /**
     * [setting 站点设置]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function setting() {
        $configModel = M('config');
        if(IS_POST){
            $is_end = true;
            $postData = I('post.');
            foreach ($postData as $key => $value) {
                $saveData = array();
                $saveData['config_value'] = $value;
                if( $configModel->where(array('config_sign'=>$key))->data($saveData)->save() === false ){
                    $is_end = false;
                    break;
                }
            }
            (!$is_end) ? $this->error('更新失败') : $this->success('更新成功', U('System/setting'));
        }else{
            $this->display();
        }
    }

    /**
     * [photoSave 上传图片]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function photoSave() {
        // 图片保存路径
        fileUpload('System/', function($e) {
            echo json_encode(array('error'=>'', 'src'=>trim($e['filePath'], '.')));
        });
    }
    
    /**
     * [seoSetting seo设置]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function seoSetting(){
        $seoModel = M('seo');
        if(IS_POST){
            $postData  = I('post.');
            $count = $seoModel->count();
            $success = true;
            for($i = 0; $i<$count; $i++){
                $data = array();
                foreach ($postData as $key => $value) {
                    $data[$key] = $value[$i];
                }
                $saveData = $seoModel->create($data, 2);
                if( $seoModel->data($saveData)->save() === false ){
                    $success = false;
                    break;
                }
            }
            (!$success) ? $this->error('更新失败') : $this->success('更新成功',U('seoSetting'));
        }else{
            $list = $seoModel->select();
            $this->assign('list',$list);
            $this->display();
        }
    }
    public function editSeo(){
        $id = I('get.id','');
        $seoModel = M('seo');
        if(IS_POST){
            $data = $seoModel->create(I('post.'),2);
            if( empty($data) ){
                $this->error($seoModel->getError());
            }else{
                ( $seoModel->data($data)->save() === false ) ? 
                $this->error('编辑失败') : $this->success('编辑成功',U('System/seoSetting'));
            }
        }else{
            
            $seoData = $seoModel->where(array('id'=>$id))->find();
            $this->assign('seoData',$seoData);
            $this->display();
        }
    }
}