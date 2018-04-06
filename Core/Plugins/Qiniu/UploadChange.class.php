<?php
namespace Plugins\Qiniu;
use Plugins\Qiniu\Auth;
use Plugins\Qiniu\Storage\UploadManager;
use Plugins\Qiniu\Storage\BucketManager;
use Think\Controller;
class UploadChange extends Controller{

	public function __construct() {
		parent::__construct();
	}

	/**
	 * [uploadChange 上传至七牛转码]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)       2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [string]      $filename 	[文件名称含amr后缀]
	 * @param     string        $path       [上传的文件路径]
	 * @param     boolean       $is_store 	[是否保存在本地]
	 * @param     string        $to_path  	[保存在本地的路径]
	 * @return    [string]                	[返回文件地址]
	 * @return    [array]                   [baseUrl=>返回文件地址,file=>存储在本地的文件]
	 */
	public function uploadChange($filename,$path = './Static/Uploads/Wxvoice/',$is_store = false,$to_path='./Static/Uploads/Wxvoice/'){
	    import('Plugins.Qiniu.functions');
	   //公匙
	    $accessKey = C('ACCESS_KEY');
	    //私匙
	    $secretKey = C('SECRET_KEY');
	    $auth = new Auth($accessKey, $secretKey);
	    //空间名称和队列名称
	    $bucket = C('BUCKET');
		$pipeline = C('PIPELINE');
	    //转码成功后的名字，可随程序进行改变也可自定义。不带后缀
	    $houzhui = substr(strrchr($filename, '.'), 1);
	    $successkey = basename($filename,".".$houzhui);
	    //不指定默认保存在当前空间，bucket为目标空间，后一个参数为转码之后文件名     
	    $savekey = \Plugins\Qiniu\base64_urlSafeEncode($bucket.':'.$successkey.'.mp3');  
	    // $savekey = \Qiniu\base64_urlSafeEncode($bucket.':'.$successkey.'.mp3');  
	    //设置转码参数此处为将文件转码为音频文件且为mp3格式。其他类型格式说明请见（https://developer.qiniu.com/dora）   
	    $fops = "avthumb/mp3/ab/320k/ar/44100/acodec/libmp3lame";    
	    // $fops = "avthumb/mp3/ab/64k/ar/44100/acodec/amr_nb";    
	    $fops = $fops.'|saveas/'.$savekey;  
	    if(!empty($pipeline)){  //使用私有队列    
	        $policy = array(    
	            'persistentOps' => $fops,    
	            'persistentPipeline' => $pipeline    
	        );    
	    }else{                  //使用公有队列    
	        $policy = array(    
	            'persistentOps' => $fops    
	        );    
	    }   

	     //指定上传转码命令    
	    $uptoken = $auth->uploadToken($bucket, null, 3600, $policy);    
	    //$key = $mediaid.'.amr'; //七牛云中保存的amr文件名（本处原需求为将微信上传录音下载到本地服务器然后上传到七牛云进行.amr=>.mp3格式转化操作） 
	    // 要上传文件的本地路径
	    if( !file_exists($path) ){
	        return false;
	    }
	    $filePath = $path . $filename;
	    // 上传到七牛后保存的文件名
	    $key = $filename; 
	    $uploadMgr = new UploadManager();    
	    //上传文件并转码$filePath为本地文件路径   
	    list($ret, $err) = $uploadMgr->putFile($uptoken, $key, $filePath);
	    //查询状态
	    //'https://api.qiniu.com/status/get/prefop?id='. $persistentId;       
	    if ($err !== null) { //失败   
	        // var_dump($savekey); 
	        return false;   
	    }else {//成功 
	        //此时七牛云中同一段音频文件有amr和MP3两个格式的两个文件同时存在，为节省空间,删除amr格式文件   
	        $bucketMgr = new BucketManager($auth);     
	        $bucketMgr->delete($bucket, $key); 
	        // var_dump($savekey); 
	        //持久化存储
	        //此处需要查看你的空间是私有空间还是公有空间，如果是公有直接拼接使用，如果是私有，构造私有空间的需要生成的下载的链接，你绑定定在空间的域名 加 要下载的文件名
	        $baseUrl = 'http://ov4itoyi1.bkt.clouddn.com/'.$successkey.'.mp3';
	        if(!$is_store){
	            return $baseUrl;
	        }else{
            	//私有空间处理方法
	            // $authUrl = $auth->privateDownloadUrl($baseUrl);
	            //将七牛云转码好的下载到本地
	            if( !file_exists($to_path) ){
	                $result = mkdir($to_path,0777,true);
	                if(!$result) return false;
	            }
	            if( $this->saveFile($to_path . $successkey.'.mp3',$baseUrl) ){
	            	$return = array(
	            		'baseUrl' => $baseUrl,
	            		'file' 	  => $to_path . $successkey.'.mp3',
	            	);
	            	return $return;
	            }else{
	            	return false;
	            }
	        }
	   
	    }
	}
	/**
	 * [saveFile 将录音下载到本地]
	 * @author cdd <2042536829@qq.com>
	 * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
	 * @param     [type]        $filename    [文件名称]
	 * @param     [type]        $filecontent [文件内容]
	 * @return    [bool]                     [存储文件是否成功]
	 */
	private function saveFile($filename, $filecontent){  
        $local_file = fopen($filename, 'w');  
        if (false !== $local_file){ 
            if (false !== fwrite($local_file, $filecontent)) {  
                fclose($local_file);  
                return true;
            }else
            	return false;  
        }else  
        	return false;
    }
}