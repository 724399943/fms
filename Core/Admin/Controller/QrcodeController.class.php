<?php
namespace Admin\Controller;
use Think\Controller;
class QrcodeController extends BaseController {
    private $signModel;
    private $adminId;
    public function __construct()
    {
        parent::__construct();
        $this->signModel = D('Sign');
        $this->adminId = session('adminId');
    }

    /**
     * [createQrCode 生成静态二维码]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function createQrCode()
    {
        if ( IS_POST ) {
            $addData = $this->signModel->create(I('post.'), 1);
            $addData['url'] = filter_var($addData['url'], FILTER_VALIDATE_URL);
            $addData['url'] OR exit(statusCode([], 400000, '请输入正确的地址'));
            $addData['tags'] OR exit(statusCode([], 400000, '请输入标识'));
            $addData['agent_id'] = $this->adminId;
            ( $this->signModel->add($addData) !== false ) ?
                exit(statusCode(['sign'=>trim(C('webSite'), '/') . "/{$addData['sign']}"])) :
                exit(statusCode([], 400000, '生成失败'));
        } else {
            $this->display();
        }
    }

    /**
     * [qrcodeList 二维码列表]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function qrcodeList() 
    {
        $where = [];
        $link_parameter = '';
        $tags = I('tags', '', 'urldecode');
        $where['agent_id'] = $this->adminId;
        $where = [
            'agent_id' => $this->adminId,
            'tags' => ['NEQ', ''],
        ];

        if( !empty($tags) ){
            $where['tags'] = ['LIKE', "%{$tags}%"];
            $link_parameter .= '/tags/' . $tags;
        }

        $count = $this->signModel->where($where)->count();
        $page = new \Think\Page($count, 15);    //实例化分页类 传入总记录数和每页显示的记录数(15)
        $page->setConfig('link', '/Admin/Qrcode/qrcodeList/p/zz' . $link_parameter);
        $show = $page->show();    //分页显示输出
        $counting = $page->totalRows;
        $list = $this->signModel->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('`id` DESC')->select();    //分页查询
        $adminData = session('adminData');
        foreach ($list as $key => &$value) {
            $value['qrcode'] = trim(C('UPLOAD_PATH'), '.') . "ActivityQrCode/activityQrCode_{$value['id']}.png";
            // $value['link'] = trim($adminData['web_links'], '/') . "/index/{$value['id']}";
            $value['link'] = trim(C('webSite'), '/') . "/{$value['sign']}";
        }
        $return = [
            'tags' => $tags,
        ];
        $assign = [
            'show' => $show,
            'return' => $return,
            'list' => $list,
            'counting' => $counting,
        ];

        $this->assign($assign);    //赋值分页输出
        $this->display();
    }

    public function qrcodeAnalyze()
    {
        $signLogModel = M('sign_log');
        $yAxisData = json_encode([date('Y/m/d')]);

        $where = [
            'sign' => I('sign/s'),
            'agent_id' => session('adminId'),
        ];
        $list = $signLogModel->where($where)->field(' `os`, `browser`, `language`')->select();
        $returnData = analyze($list);
        
        $this->assign('yAxisData', $yAxisData);
        $this->assign('langSeriesData', $returnData['langSeriesData']);
        $this->assign('osSeriesData', $returnData['osSeriesData']);
        $this->assign('browserSeriesData', $returnData['browserSeriesData']);
        $this->display();
    }
}