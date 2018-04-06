<?php
/**
 * Created by PhpStorm.
 * User: lk
 * Date: 2015/11/4
 * Time: 17:12
 */

//set_time_limit(0);
date_default_timezone_set("Asia/Shanghai");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors','on');
ini_set('memory_limit', '512M');

// include_once "libs/CacheRedis.php";

class Express
{
   //快递100订阅接口
    private static $expressUrl='http://www.kuaidi100.com/poll';

    //回调接口
    private static $callBackUrl='http://www.baiyangwang.com/Shop/Shop/getShippingStatus';

    //快递１００接口连接超时时间
    private static $connTime=20;

    //快递１００授权码
    private static $authKey='PgVVYynJ4846';

    //发货地址
    // private static $fromAddress='山东青岛';

    //快递单号缓存时间
    private static $expireTime=604800;

    //开始时间
    // private  static $startTime="2015-11-1";

    /**
     * 订单状态
     * @var array
     */
    protected static $orderState=array(
        'paying', //待付款
        'shipping', //待发货
        'shipped', //待收货
        'evaluating', //待评价
        'refund', //退款/售后
        'canceled', //取消订单
        'finshed',  //订单完成,
        'all'  //所以
    );

    public static $list=array(
        'yuantong' => '圆通快递',
        'yunda' => '韵达快运',
        'huitongkuaidi' => '百世汇通',
        'ems' => 'EMS',
        'shunfeng' => '顺丰快递',
        'shentong'=>'申通快递'
    );

    /**
     * @param $param
     * 得到快递编码
     */
    protected static function getExp($param)
    {
       foreach(static::$list as $k=>$v){
           if(ord($param)==ord($v)){
               return $k;
           }
       }
    }

    /**
     *  获取已经订阅成功的快递单号，不再进行重复订阅
     *
     */
    protected static function getSubed()
    {
        // CacheRedis::selectDb(15);
        // $data=CacheRedis::getAllKeys();
        $str='';
        if(is_array($data)&&!empty($data)) {
            foreach ($data as $k => $v) {
                $str .= "'{$v}'" . ',';
            }
            $str=rtrim($str,',');
            return $str;
        }
    }

    // /**
    //  * 获得未签收订单信息
    //  */
    // protected static function getOrderAll()
    // {
    //     $startTime=strtotime(static::$startTime);
    //     $expressId=static::getSubed();
    //     $orderInfo=Db::selects(array(
    //         'table'=>'by_order_master',
    //         'field'=>'order_id,logistics_id,logistics_com,logistics_msg,status,address_districtid,address_cityid,address_areaid',
    //         'where'=>"status not in('evaluating','shipping','canceled','paying','finished') and express_status!=1 and logistics_id IS NOT NULL and logistics_id not in({$expressId}) and payment_time>{$startTime} and logistics_com!='ZPS'"
    //     ));
    //     return $orderInfo;
    // }

    // /**
    //  * @param $param=array()
    //  * 获得发送参数，并组装数据
    //  */
    // protected static function getSendData($param)
    // {
    //    $postData=array();
    //     $postParam=array();
    //     $postData['company']=$param['logistics_com'];
    //     $postData['number']=$param['logistics_id'];
    //     $capital=Db::getOne(array(
    //         'field'=>'name',
    //         'table'=>'settings_location_sheng',
    //         'where'=>'id=?'
    //     ),array($param['address_districtid']));
    //     $city=Db::getOne(array(
    //         'field'=>'name',
    //         'table'=>'settings_location_city',
    //         'where'=>'id=?'
    //     ),array($param['address_cityid']));
    //     $postData['from']=static::$fromAddress;
    //     $postData['to']=$capital['name'].$city['name'];
    //     $postData['key']=static::$authKey;
    //     $postData['parameters']=array(
    //         'callbackurl'=>static::$callBackUrl,
    //         'salt'=>'',
    //         'resultv2'=>'1'
    //     );
    //     $postParam["schema"]='json';
    //     $postParam['param']=json_encode($postData,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    //     $postInfo=static::createPostParam($postParam);
    //     unset($postData);
    //     unset($postParam);
    //     return $postInfo;
    // }

    /**
     * @return string
     * 测试使用
     */
    protected static function testPost($company="", $number="", $city="") {
        $postData=array();
        $postParam=array();
        $postData['company'] = $company;
        $postData['number'] = $number;
        // $capital='广东省';
        // $city='广州市';
        // $postData['from']=static::$fromAddress;
        $postData['to']=$city;
        $postData['key']=static::$authKey;
        $postData['parameters']=array(
            'callbackurl'=>static::$callBackUrl,
            'salt'=>'',
            'resultv2'=>'1'
        );
        $postParam["schema"]='json';
        $postParam['param']=json_encode($postData,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $postInfo=static::createPostParam($postParam);
        unset($postData);
        unset($postParam);
        return $postInfo;
    }

    /**
     * 测试使用
     */
    public static function testStart($company="", $number="", $city="")
    {
        $postData=static::testPost($company, $number, $city);
        $logTime=date("Y-m-d H:i:s",time());
        $result=static::sendExpressId($postData);
        $isSend=json_decode($result);
        if($isSend->result){
            // CacheRedis::selectDb(15);
            // CacheRedis::set(3944230018147,'1',static::$expireTime);
            file_put_contents('express.log', $logTime." ".$result . " company:{$company} number:{$number}" . ' success'.PHP_EOL,FILE_APPEND);
        }else{
            file_put_contents('express.log', $logTime." ".$result . " company:{$company} number:{$number}" . ' faild'.PHP_EOL,FILE_APPEND);
        }
    }

    /**
     *  推送数据到快递100
     * @param=array()
     * @return json
     */
    protected static function sendExpressId($param)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::$expressUrl);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //ipv4
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, static::$connTime);
        curl_setopt($ch, CURLOPT_TIMEOUT, static::$connTime);
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        return $result;
    }

    /**
     *  创建post param
     * @param $param=array()
     * return bool|string
     */
    protected static function createPostParam($param)
    {
        $o="";
        foreach ($param as $k=>$v) {
            $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data=substr($o,0,-1);
        return $post_data;
    }

    /**
     *  订单物流订阅开始
     */
    public static function start()
    {
        $orderInfo=static::getOrderAll();
        $logTime=date("Y-m-d H:i:s",time());
        if(is_array($orderInfo)&&!empty($orderInfo)){
           foreach($orderInfo as $key=>$val){
               $postData=static::getSendData($val);
               $result=static::sendExpressId($postData);
                $isSend=json_decode($result);
                if($isSend->result){
                    // CacheRedis::selectDb(15);
                    // CacheRedis::set($val['logistics_id'],'1',static::$expireTime);
                    // file_put_contents('express.log',$logTime." ".$val['logistics_id'].' sub success'.PHP_EOL,FILE_APPEND);
                }else{
                    // file_put_contents('express.log',$logTime." ".$val['logistics_id'].' sub faild'.PHP_EOL,FILE_APPEND);
                }
                sleep(1);
           }
        }
    }
}
// Db::init();
// CacheRedis::init();
$company = empty($_REQUEST['a']) ? '' : $_REQUEST['a'];
$number = empty($_REQUEST['b']) ? '' : $_REQUEST['b'];
$city = empty($_REQUEST['c']) ? '' : $_REQUEST['c'];
Express::testStart($company, $number, $city);