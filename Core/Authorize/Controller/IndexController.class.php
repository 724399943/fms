<?php
namespace Authorize\Controller;
use Think\Controller;
use Plugins\Wechat\WeixinController;
use Authorize\Controller\OpenController;

class IndexController extends BaseController {

    // public $limit;
    // public $page;
    // public $limitStar;
    // public $limitStr;

    // public function __construct() {
        // parent::__construct();
        // echo '<meta charset="utf-8">';
        // echo '欧派首发会发声的儿童房，六一焕新上市';
        // die();
        // $whiteList = array(
        // );
        // $controllerName = CONTROLLER_NAME;
        // $actionName     = ACTION_NAME;
        // $right          = M('controller_power')->where(array('controller_name'=>$controllerName, 'controller_function'=>$actionName))->find();
        // if( count($right) < 1 && !in_array($controllerName, $whiteList) ) {
        //     die(statusCode(array(), 100001));
        // } else {
        //     session_set_cookie_params(3600, '/', C('BASE_COOKIE_HOST'), false, true);
        //     define(NEED_PAGE, $right['need_page']);
        //     define(PAGE_LIMIT, $right['page_limit']);
        //     $this->load_limit();
        // }
    // }
    // public function __destruct() {
    //     parent::__destruct();
    // }

    /**
     * [load_limit 读取分页]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    // private function load_limit() {
    //     if ( NEED_PAGE ) {
    //         $page  = I('request.page', 0, 'int');
    //         $page  = $page < 0 ? 0 : $page;
    //         $this->limit = PAGE_LIMIT;
    //         $this->page         = $page;
    //         $this->limitStar    = $this->limit * $page;
    //         $this->limitStr     = "LIMIT {$this->limitStar} , {$this->limit}";
    //     } else {
    //         $this->limit = 0;
    //         $this->limitStr = "";
    //     }
    // }
     public function index(){

        $userInfo = session('userInfo');
        // $signPackage = $this->signPackage();
        // $this->assign('signPackage', $signPackage);
        $this->assign('userInfo', $userInfo);
        $this->display();
    }
    //每日一诗
    public function dailyApoem(){
        $article = M('article');

        // $where = array();
        $where = array(
            'is_recommend'=>'1',
            );
        $list = $article->where($where)->order('add_time DESC')->select();
        $collect = M('user_article_collect');
        foreach ($list as $key => $value) {
            $list[$key]['collect']=$collect->where(array('user_id'=>$userId,'article_id'=>$value['id']))->count();
        }
        // dump($list);
        if(IS_POST){
            exit(statusCode($list));
        } else {
            $isWhiteList = $this->isWhiteList;
            $this->assign('isWhiteList',$isWhiteList);
            $this->assign('list',$list);
            $this->display();
        }
    }
    //诗集分组
    public function poetryGroup(){
        if(IS_POST){
            $group = M('article_group');
            $limit = PAGE_LIMIT;
            $list = $group->where(array('pid'=>'1','level'=>'1'))->order('add_time DESC')->limit("{$this->limitStar} , {$limit}")->select();
            exit(statusCode($list));
        } else {
            $this->display();
        }
    }
    //诗集分类
    public function poetryCategory(){
        $gid = I('request.gid',0);
        if(IS_POST){
            $dbPrefix = C('DB_PREFIX');
            
            $article = M('article');
            $limit = PAGE_LIMIT;
            $where = array(
                // 'group_path'=>array('LIKE',"%,{$gid},%");
                'group_id'=>$gid,
                );
            $list = $article->where($where)->order('add_time DESC')->limit("{$this->limitStar} , {$limit}")->select();
            $recording = M('recording');
            foreach ($list as $key => $value) {
                $list[$key]['count'] = $recording->where(array('article_id'=>$value['id']))->count();
            }

            $image = $group->where(array('id'=>$gid))->getField('image');

            // $list = $group->where(array('pid'=>$gid,'level'=>'2'))->order('add_time DESC')->select();


            exit(statusCode(array('list'=>$list,'image'=>$image)));
        } else {
            $this->display();
        }
    } 
    //诗集列表
    public function poetryList(){
        $aid = I('request.aid',0);
        if(IS_POST){
            $userId = session('userId');
            $article = M('article');
            // $where = array(
            //     'group_path'=>array('LIKE',"%,{$gid},%"),
            //     );
            if(!empty($aid)){
                $gid = $article->where(array('id'=>$aid))->getField('related_id');
                if(!empty($gid)){
                    $where['id'] = trim($related_id,',').','.$aid;
                } else {
                    $where['id'] = $aid;
                }
            } else {
                $where['id'] = -1;
            }

            $list = $article->where($where)->order('add_time DESC')->select();
            $collect = M('user_article_collect');
            foreach ($list as $key => $value) {
                $list[$key]['collect']=$collect->where(array('user_id'=>$userId,'article_id'=>$value['id']))->count();
            }
            
            exit(statusCode($list));
        } else {
            $this->display();
        }
    }
    //诗集详情
    public function poetryDetail(){
        if(IS_POST){
            $id = I('post.id','');
            $article  = M('article');
            $detail = $article->where(array('id'=>$id))->find();
            if(empty($id) || empty($detail)){
                exit(statusCode(array(),400000,'该诗集不存在！'));
            }

            exit(statusCode($detail));
        } else {
            $this->display();
        }
    }
    //收藏诗集
    public function addCollect(){
        if(IS_POST){
            $userId = session('userId');
            $aid = I('post.aid','');
            $article = M('article');
            $collect = M('user_article_collect');
            if(empty($aid)){
                exit(statusCode(array(),400000,'请选择收藏的id'));
            }
            $data = array(
                    'user_id' => $userId,
                    'article_id' => $aid
                    );
            $list = $article->where($data)->count();
            if($count > 0){
                $data['add_time'] = time();
                $i = $collect->add($data);
                $msg = '收藏';
            } else {
                $i = $collect->where($data)->delete();
                $msg = '取消收藏';
            }

            if($i !== false){
                exit(statusCode(array(),200000,$msg.'成功！'));
            } else {
                exit(statusCode(array(),200000,$msg.'失败！'));
            }
        } else {
            exit(statusCode('',100001));
        }
    }
    //当前诗集
    public function currentPoetry(){
        if(IS_POST){
            $dbPrefix = C('DB_PREFIX');
            $aid = I('post.aid','');
            $limit = PAGE_LIMIT;
            if(empty($aid)){
                exit(statusCode(array(),400000,'诗集id不能为空！'));
            }

            $sql = "SELECT `r`.*, `u`.`nickname`,`u`.`headimgurl`  
                    FROM {$dbPrefix}recording AS `r` LEFT JOIN
                    {$dbPrefix}user AS `u` ON `u`.`id` = `r`.`user_id`
                    WHERE `r`.`article_id` = {$aid}
                    ORDER BY `r`.`like_number` DESC
                    LIMIT {$this->limitStar} , {$limit}";
            $list = M()->query($sql);
            $list = empty($list)?array():$list;
            exit(statusCode(array('list'=>$list)));
        } else {
            $this->display();
        }
    }
    //作品
    public function poetryWorks(){
        if(IS_POST){
            $userId = session('userId');
            $user = M('user');
            $limit = PAGE_LIMIT;
            $userInfo = $user->where(array('id'=>$userId))->find();

            $userList = $user->where(array('id'=>array('neq',$userId)))->order('works_number DESC')->limit("{$this->limitStar} , {$limit}")->select();


            exit(statusCode(array('userList'=>$userList,'userInfo'=>$userInfo)));

        } else {
            exit(statusCode('',100001));
        }
    }
    //个人中心
    public function userInfo(){
        if(IS_POST){
            $userId = session('userId');
            $userInfo = M('user')->where(array('id'=>$userId))->find();
            exit(statusCode($userInfo));
        } else {
            $this->display();
        }
    }
    //修改用户昵称
    public function changeNickname(){
        $userId = session('userId');
        if(IS_POST){
            $nickname = I('post.nickname','');

            $i = M('user')->where(array('id'=>$userId))->save(array('nickname'=>$nickname));
            if($i !== false){
                exit(statusCode('',200000,'修改成功！'));
            } else {
                exit(statusCode('',400000,'修改失败！'));
            }
        } else{ 
            $this->display();
        }
    }
    //用户协议
    public function agreement(){
        $agreement = C('agreement');
        if(IS_POST){
            exit(statusCode(array('content'=>$agreement)));
        } else {
            $this->display();
        }
    }
    /**
     * [ajaxChangeHeadimg ajax修改个人头像]
     * @author Fu <[418382595@qq.com]>
     */
    public function ajaxChangeHeadimg()
    {
        if (IS_POST) {
            $userId = session('userId');
            // $webSite    = trim(C('webSite'), '/');
            $user = M('user');
            $headimgurl = I('post.headimgurl', '');
            $data = array(
                'id' => $userId,
                'headimgurl' => $headimgurl
            );
            // $webSite .
            if ($user->save($data) !== false) {
                session('userInfo', $user->where(array('id' => $userId))->find());
                exit(statusCode(array('headimgurl' => $headimgurl)));
                // $webSite . 
            } else {
                exit(statusCode(array(), 400000, '保存失败！'));
            }
        } else {
            $this->error('非法访问！');
        }
    }
    public function photoSave()
    {
        // 图片保存路径
        fileUpload('User/', function ($e) {
            echo json_encode(array('error' => 0, 'url' => trim($e['filePath'], '.')));
        });
    }
    //我的作品
    public function myWorks(){
            // $userId = session('userId');
            // $limit = PAGE_LIMIT;
            // $dbPrefix = C('DB_PREFIX');

            // $sql = "SELECT `r`.*, `a`.`title`,`a`.`image`,`a`.`id` AS `aid`  
            //         FROM {$dbPrefix}recording AS `r` LEFT JOIN
            //         {$dbPrefix}article AS `a` ON `a`.`id` = `r`.`article_id`
            //         WHERE `r`.`user_id` = {$userId}
            //         ORDER BY `r`.`add_time` DESC
            //         LIMIT {$this->limitStar} , {$limit}";
            // $list = M()->query($sql);

            // $list = empty($list)?array():$list;
        if(IS_POST){
            // exit(statusCode(array('list'=>$list)));
        } else{
            // $this->assign('list',$list);
            $this->display();
            // exit(statusCode('',100001));
        }
    }
    //添加按钮记录
    public function addButton(){
        $userId = session('userId');
        if(IS_POST){
            $button_name = I('post.button_name','');
            $data = array(
                'button'=>$button_name,
                'user_id'=>$userId,
                'add_time'=>time(),
                );
            M('user_button')->add($data);
            exit(statusCode('',200000,'成功！'));
        } else {
            $this->error('非法访问！');
        }
    }
    public function message(){
        $userId = session('userId');
        if(IS_POST){
            $limit = PAGE_LIMIT;
            $dbPrefix = C('DB_PREFIX');
            $sql = "SELECT * FROM {$dbPrefix}message AS `m`
                    LEFT JOIN {$dbPrefix}message_relavance AS `mr`
                    ON `mr`.`message_id` = `m`.`id`
                    WHERE `m`.`is_delete` = 0
                    AND `mr`.`receiver_id` = {$userId}
                    ORDER BY `m`.`add_time`
                    LIMIT {$this->limitStar} , {$limit}";
            $list = M()->query($sql);
            $list = empty($list)?array():$list;
            exit(statusCode(array('list'=>$list)));

        } else {
            $this->display();
        }

    }

    public function addMessage(){
        
    }
    //我的收藏
    public function myCollect(){
        if(IS_POST){
            $userId = session('userId');
            $dbPrefix = C('DB_PREFIX');
            $limit = PAGE_LIMIT;
            $sql = "SELECT `uc`.*,`a`.`title`,`a`.`id` AS `aid` 
                    FROM {$dbPrefix}user_article_collect AS `uc`
                    LEFT JOIN {$dbPrefix}article AS `a` 
                    ON `a`.`id` = `uc`.`article_id`
                    WHERE `uc`.`user_id` = {$userId}
                    ORDER BY `uc`.`add_time`
                    LIMIT {$this->limitStar} , {$limit}";
            $list = M()->query($sql);

            $list = empty($list)?array():$list;
            exit(statusCode(array('list'=>$list)));

        } else {
            exit(statusCode('',100001));
        }
    }
    //点赞
    public function thumbUp(){
        $userId = session('userId');
        if(IS_POST){
            $id = I('post.id','');
            $recording = M('recording');
            $like = M('like_click');
            $list = $recording->where(array('id'=>$id))->find();
            if(empty($id)) {
                exit(statusCode(array(),400000,'参数丢失！'));
            }

            $list = $like->where(array('user_id'=>$userId,'recording_id'=>$id))->order('add_time DESC')->find();
            $now = date('Y-m-d');
            if($list['date'] == $now){
                exit(statusCode(array(),400000,'今日已点赞'));
            } else {
                $data = array(
                    'user_id'=>$userId,
                    'recording_id'=>$id,
                    'date'=>date('Y-m-d'),
                    'add_time'=>time(),
                    );
                $i = $like->add($data);
                if($i !== false){
                    $recording->where(array('id'=>$id))->setInc('like_number',1);

                    exit(statusCode(array(),200000,'点赞成功！'));
                } else {
                    exit(statusCode(array(),400000,'点赞失败！'));
                }
            }

        } else {
            exit(statusCode('',100001));
        }
    }

    public function share(){
        $type = I('request.type',0);
        $userId = session('userId');
        $id = I('request.id','');
        $dbPrefix = C('DB_PREFIX');
        if(empty($type)){
            $recording = M('recording');
            $sql = "SELECT `r`.*, `u`.`nickname`,`u`.`headimgurl` 
                FROM {$dbPrefix}recording AS `r`
                LEFT JOIN {$dbPrefix}user AS `u`
                ON `u`.`id` = `r`.`user_id`
                WHERE `r`.`id` = '{$id}'
                LIMIT 1";
            $list = M()->query($sql);
        } else {
            $sql = "SELECT `r`.*, `u`.`nickname`,`u`.`headimgurl` 
                FROM {$dbPrefix}pz_recording AS `r`
                LEFT JOIN {$dbPrefix}user AS `u`
                ON `u`.`id` = `r`.`user_id`
                WHERE `r`.`id` = '{$id}'
                LIMIT 1";
            $list = M()->query($sql);
        }
        if(IS_POST){
            if(empty($id) || empty($list)){
                exit(statusCode(array(),400000,'该录音不存在！'));
            }
            $list = $list[0];
            exit(statusCode(array('list'=>$list)));
        } else {
            if(empty($id) || empty($list)){
                $this->error('该录音不存在！');
                // exit(statusCode(array(),400000,'该作品不存在！'));
            }
            $list = $list[0];
            $this->assign('list',$list);
            $this->assign('nowTime',time());
            $this->assign('userId',$userId);
            $this->display();
        }
    }
    
    //作品详情
    public function recordingDetail(){

        $userId = session('userId');
        $id = I('request.id','');
        $dbPrefix = C('DB_PREFIX');
        $recording = M('recording');
        $list = $recording->where(array('id'=>$id,'user_id'=>$userId))->find();
        // $sql = "SELECT `r`.*, `u`.`nickname`,`u`.`headimgurl` 
        //         FROM {$dbPrefix}recording AS `r`
        //         LEFT JOIN {$dbPrefix}user AS `u`
        //         ON `u`.`id` = `r`.`user_id`
        //         WHERE `r`.`id` = '{$id}'
        //         LIMIT 1";
        // $list = M()->query($sql);
        if(IS_POST){
            if(empty($id) || empty($list)){
                exit(statusCode(array(),400000,'该作品不存在！'));
            }
            // $list = $list[0];
            exit(statusCode(array('list'=>$list)));
        } else {
            if(empty($id) || empty($list)){
                $this->error('该作品不存在！');
                // exit(statusCode(array(),400000,'该作品不存在！'));
            }
            // $list = $list[0];
            $this->assign('list',$list);
            $this->assign('userId',$userId);
            $this->display();
        }
    }
    //录音
    public function recording(){
        $userId = session('userId');
        if(IS_POST){
            $aid = I('post.id','');
            $serverId = I('post.serverId','');
            $serverTime = I('post.time',0);
            if(empty($aid)) {
                exit(statusCode(array(),400000,'参数丢失！'));
            }

            // file_put_contents('serverId.txt', $serverId);
            $result = $this->getRecording($serverId);
            if ($result === false || !file_exists('.'.$result)) {
                exit(statusCode(array(),400000,'上传录音失败，请刷新重新录音！'));
            }

            $data = array(
                'user_id'=>$userId,
                'article_id'=>$aid,
                'like_number'=>0,
                'recording_time'=>$serverTime,
                'recording_url'=>$result,
                'server_d'=>$serverId,
                'expires_in' => time() + 259000,
                'add_time'=>time(),
            );
            $i = M('recording')->add($data);
            if($i !== false){
                exit(statusCode(array('id'=>$i),200000,'上传录音成功！'));
            } else {
                exit(statusCode(array(),400000,'上传录音失败，请刷新重新录音！'));
            }
        } else {
            // $this->display();
            exit(statusCode('',100001));
        }
    }
    //下载录音
    public function getRecording($serverId){
        // $weixin = new \Common\Library\Lib_access();
        $weixin = new OpenController();

        // $access_token = $weixin->seachAccessToken();
        $access_token = $weixin->getAuthorizerAccessToken();
        // $access_token = $access_token['access_token'];
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$serverId;
        $fileInfo = $this->downloadWeixinFile($url,$serverId);
        // die;
        // $type = $this->header_byte($fileInfo['header']['content_type']);
        $filename = 'wxupload_' .time(). rand(1111,9999).'.amr';
        
        // file_put_contents('fileInfo.txt', json_encode($filename));
        $webSite = C('webSite');
        $this->saveFile('./Static/Uploads/Wxvoice/'.$filename, $fileInfo['body']);
        if (file_exists('./Static/Uploads/Wxvoice/'.$filename)) {
            $amr = './Static/Uploads/Wxvoice/'.$filename;
            $mp3 = './Static/Uploads/Wxvoice/'.str_replace('.amr', '.mp3', $filename);
            $command = "/usr/local/bin/ffmpeg -i {$amr} {$mp3}";
            exec($command,$error);
            // sleep(3);
            // dump(system($command,$error));
            // if (file_exists($mp3)) {
            // unlink($amr);
            return trim($mp3,'.');
            // } else {
                // return false;
            // }
        } else {
            return false;
        }
        
        // return '/Static/Uploads/Wxvoice/'.$filename;
        // return trim($webSite,'/').'/Static/Uploads/Wxvoice/'.$filename;
    }
    /**
     * [saveFile 生成文件]
     * @author wulong <1191540273@qq.com>
     * @copyright Copyright (c)          2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $filename    [description]
     * @param     [type]        $filecontent [description]
     * @return    [type]                     [description]
     */
    public function saveFile($filename, $filecontent){  
        $local_file = fopen($filename, 'w');  
        if (false !== $local_file){//不恒等于（恒等于=== 就是false只能等于false，而不等于0）  
            if (false !== fwrite($local_file, $filecontent)) {  
                fclose($local_file);  
            }  
        }  
    }
    public function downloadWeixinFile($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package));
        // file_put_contents('package.txt', json_encode($package));
        // file_put_contents('httpinfo.txt', json_encode($httpinfo));
        // file_put_contents('imageAll.txt', json_encode($imageAll));
        
        return $imageAll;
    }

    public function weixin() {
        $signPackage = R('Jssdk/getSignPackage');
        $this->assign('signPackage', $signPackage);
    }

    /**
     * [signPackage 获取js_api接口]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)  2016          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $id  [description]
     * @param     [type]        $key [description]
     * @return    [type]             [description]
     */
    public function signPackage() {
        $url = "http://coding.prettymi.com/Open/Api/getSignPackage.html";
        $data = array(
            'id' => C('APP_ID'),
            'key' => C('APP_KEY'),
            'url' => $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI],
        );
        $jsonData = curl($url, $data);
        // file_put_contents('jsonData', $jsonData);
        return json_decode($jsonData, TURE);
    }

   

    public function preferentialShop() {
        $signPackage = $this->signPackage();
        $this->assign('signPackage', $signPackage);
    	$this->display();
    }

    public function moreStores() {
        $signPackage = $this->signPackage();
        $this->assign('signPackage', $signPackage);
    	$this->display();
    }

    public function storeDetail() {
        $signPackage = $this->signPackage();
        $this->assign('signPackage', $signPackage);
    	$this->display();
    }

    /**
     * [apiCity 获取城市列表]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function apiCity() {
        $dbPrefix = C('DB_PREFIX');
        // $data = M('city')->where(array('is_delete'=>0))->limit($this->limitStar, $this->limit)->select();
    	// $data = M('region')->where(array('is_show'=>1))->limit($this->limitStar, $this->limit)->select();
        $sql = "SELECT * 
                FROM `{$dbPrefix}region` AS `a`
                LEFT JOIN (SELECT count(*) AS `count`, `city_id` FROM `{$dbPrefix}discount` GROUP BY `city_id`) AS `b` ON `a`.`id` = `b`.`city_id`
                WHERE `a`.`is_show` = '1'
                {$this->limitStr}";
        $data = M()->query($sql);
		echo statusCode(array('cityList'=>$data));
    }

    /**
     * [apiDiscount 获取优惠列表]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function apiDiscount() {
        $dbPrefix = C('DB_PREFIX');
        $city = I('request.city', '-1');
        $region = I('request.region', '-1');
        $type = I('request.type', '-1');
        $name = I('request.name', '');

        if ( $city != '-1' ) {
            session('city', $city);
        } else {
            $city = session('city');
        }
        // if ( $region != '-1' ) {
        //     $region = session('region', $region);
        // }
        // if ( $type != '-1' ) {
        //     $type = session('type', $type);
        // }
        
    	$nowTime = time();
    	$whereArr = array(
    		'start_time'=>array('elt', $nowTime)
    	);
        $whereStr = 'WHERE 1';
        if ( $city != '-1' ) {
            $whereArr['city_id'] = $city;
            $whereStr .= " AND `city_id` = '{$city}'";
        }
        if ( $name != '-1' ) {
            $whereStr .= " AND `discount` like '%{$name}%'";
        }
        if ( $region != '-1' ) {
            // $whereArr['area'] = $region;
            // $whereStr .= " AND `a`.`area` = '{$region}'";
        }
    	if ( $type != '-1' ) {
    		// $whereArr['type'] = $type;
            $whereStr .= " AND `a`.`type` = '{$type}'";
    	}
        // $data = M('discount')->where($whereArr)->limit($this->limitStar, $this->limit)->select();
        $sql = "SELECT `a`.*, `b`.`type` AS `type_name`
                FROM `{$dbPrefix}discount` AS `a`
                LEFT JOIN `{$dbPrefix}type` AS `b` ON `a`.`type` = `b`.`id`
                {$whereStr}
                {$this->limitStr}";
        $data = M()->query($sql);
		echo statusCode(array('storeList'=>$data));
    }

    /**
     * [apiDiscountDetail 优惠详情]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function apiDiscountDetail() {
        $dbPrefix = C('DB_PREFIX');
        $id = I('request.id');
        $whereStr = 'WHERE 1';
    	if ( !empty($id) ) {
    		$whereArr['id'] = $id;
            $whereStr .= " AND `a`.`id` = '{$id}'";
    	}
    	// $data = M('discount')->where($whereArr)->limit($this->limitStar, $this->limit)->select();
        $sql = "SELECT `a`.*, `b`.`type` AS `type_name`
                FROM `{$dbPrefix}discount` AS `a`
                LEFT JOIN `{$dbPrefix}type` AS `b` ON `a`.`type` = `b`.`id`
                {$whereStr}
                {$this->limitStr}";
        $data = M()->query($sql);
		echo statusCode(array('storeList'=>$data));
    }

    /**
     * [apiRegion 获取当前城市分区列表]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function apiRegion() {
    	$city = I('request.city');
    	$whereArr = array(
    		'city_id'=>$city
    	);
    	$data = M('region')->where($whereArr)->limit($this->limitStar, $this->limit)->select();
    	echo statusCode(array('regionList'=>$data));
    }

    /**
     * [apiClassification 分类接口]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function apiClassification() {
        $data = M('type')->where(array('is_delete'=>0))->limit($this->limitStar, $this->limit)->select();
        echo statusCode(array('typeList'=>$data));
    }

    /**
     * [apiStore 更多门店]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
	public function apiStore() {
        $shop_id = I('request.id');
        $dbPrefix = C('DB_PREFIX');
        $whereStr = "WHERE 1 AND `a`.`shop_id` = '{$shop_id}'";

        // $data = M('discount')->where(array('shop_id'=>$shop_id))->limit($this->limitStar, $this->limit)->select();
        $sql = "SELECT `a`.*, `b`.`type` AS `type_name`
                FROM `{$dbPrefix}discount` AS `a`
                LEFT JOIN `{$dbPrefix}type` AS `b` ON `a`.`type` = `b`.`id`
                {$whereStr}
                {$this->limitStr}";
        $data = M()->query($sql);
        echo statusCode(array('storeList'=>$data));
	}

    /**
     * [HtmlDom 构造]
     * @author NicFung <13502462404@qq.com>
     * @copyright Copyright (c) 2016 Xcrozz (http://www.xcrozz.com)
     */
    public function HtmlDom() {
        $city = I('request.city', '-1');
        if ( $city != '-1' ) {
            session('city', $city);
        } else {
            $city = session('city');
        }
        // if ( empty($city) ) {
        //     header("LOCATION:/");
        // }
        $cityList = M('region')->where(array('is_show'=>1))->select();
        $typeList = M('type')->where(array('is_delete'=>0))->select();
        $regionList = M('region')->where(array('pid'=>$city))->select();
        $return = array(
            'cityList' => $cityList,
            'typeList' => $typeList,
            'regionList' => $regionList,
            'city' => $city
        );
        echo statusCode($return);
    }

    public function baiduMap() {
        $dbPrefix = C('DB_PREFIX');
        $id = I('request.id');
        $whereStr = 'WHERE 1';
        if ( !empty($id) ) {
            $whereArr['id'] = $id;
            $whereStr .= " AND `a`.`id` = '{$id}'";
        }
        // $data = M('discount')->where($whereArr)->limit($this->limitStar, $this->limit)->select();
        $sql = "SELECT `a`.*
                FROM `{$dbPrefix}discount` AS `a`
                
                {$whereStr}
                {$this->limitStr}";
        $data = M()->query($sql);
        $this->assign('data', $data['0']);
        $this->display();
    }
    //披萨录音
	public function record() {
        if(IS_POST){
            $userId = session('userId');
            $serverId = I('post.serverId','');
            // $serverTime = I('post.time',0);
          

            // file_put_contents('serverId.txt', $serverId);
            $result = $this->getRecording($serverId);
            if ($result === false || !file_exists('.'.$result)) {
                exit(statusCode(array(),400000,'上传录音失败，请刷新重新录音！'));
            }

            $data = array(
                'user_id'=>$userId,
                'like_number'=>0,
                // 'recording_time'=>$serverTime,
                'recording_url'=>$result,
                'server_d'=>$serverId,
                'expires_in' => time() + 259000,
                'add_time'=>time(),
            );
            $i = M('pz_recording')->add($data);
            if($i !== false){
                exit(statusCode(array('id'=>$i),200000,'上传录音成功！'));
            } else {
                exit(statusCode(array(),400000,'上传录音失败，请刷新重新录音！'));
            }
        } else {
		  $this->display();  
        }
	}

	public function finish() {
        $userId = session('userId');
        $id = I('request.id','');
        $dbPrefix = C('DB_PREFIX');
        $recording = M('pz_recording');
        $list = $recording->where(array('id'=>$id,'user_id'=>$userId))->find();
        if(IS_POST){
            if(empty($id) || empty($list)){
                exit(statusCode(array(),400000,'该录音不存在！'));
            }
            // $list = $list[0];
            exit(statusCode(array('list'=>$list)));
        } else {
            if(empty($id) || empty($list)){
                $this->error('该录音不存在！');
                // exit(statusCode(array(),400000,'该作品不存在！'));
            }
            // $list = $list[0];
            $this->assign('list',$list);
            $this->assign('userId',$userId);
            $this->display();
        }
	}
}