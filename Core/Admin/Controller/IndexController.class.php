<?php
namespace Admin\Controller;
// use Plugins\WechatOpen\AuthorizeController;
// use Plugins\WechatOpen\AccessController;
// 控制台控制器
class IndexController extends BaseController 
{
    private $adminId;
    private $dbPrefix;
    public function __construct() 
    {
        parent::__construct();
        $this->adminId = session('adminId');
        $this->dbPrefix = C('DB_PREFIX');
    }

  /**
     * [systemSetting 系统设置]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function systemSetting(){
        //获得模型数量
        $sql = "SELECT count(*) as count,`module_id` from `{$this->dbPrefix}category` group by `module_id`";
        $modList = M()->query($sql);
        $return = array();
        foreach ($modList as $key => $value) {
            switch ($value['module_id']) {
                case '1':
                    $return['articleMod'] = $value['count'];
                    break;
                case '2':
                    $return['productMod'] = $value['count'];
                    break;
                case '3':
                    $return['photoMod'] = $value['count'];
                    break;
                case '4':
                    $return['downloadMod'] = $value['count'];
                    break;
                case '5':
                    $return['hrMod'] = $value['count'];
                    break;
                case '6':
                    $return['aboutMod'] = $value['count'];
                    break;
            }
        }
        //获得服务器信息
        if(function_exists('gd_info')){
            $gd_info = gd_info();
            $return['gd_info'] = $gd_info['GD Version'];
        }else{
            $return['gd_info'] = '未知';
        }
        $icon = function_exists('iconv');
        // $info = curl_getinfo();dump($info);
        $this->assign('return',$return);
        $this->display();
    }

    /**
     * [wapSetting WAP手机版设置]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function wapSetting(){
        if(IS_POST){
            $postData = I('post.');
            $configModel = M('config');
            $result = true;
            foreach ($postData as $key => $value) {
                $saveData = [];
                $saveData['config_value'] = $value;
                if( $configModel->where(['config_sign'=>$key])->data($saveData)->save() === false){
                    $result = false;
                    break;
                }
            }
            ( !$result ) ? $this->error('更新失败') : $this->success('更新成功');
        }else{
            $this->display();
        }
    }
}