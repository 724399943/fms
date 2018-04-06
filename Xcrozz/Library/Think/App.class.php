<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;
/**
 * ThinkPHP 应用程序类 执行应用过程管理
 */
class App {

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    static public function init() {
        // 加载动态应用公共文件和配置
        load_ext_file(COMMON_PATH);

        // 日志目录转换为绝对路径 默认情况下存储到公共模块下面
        C('LOG_PATH',   realpath(LOG_PATH).'/Common/');

        // 定义当前请求的系统常量
        define('NOW_TIME',      $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
        define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
        define('IS_PUT',        REQUEST_METHOD =='PUT' ? true : false);
        define('IS_DELETE',     REQUEST_METHOD =='DELETE' ? true : false);

        // URL调度
        Dispatcher::dispatch();

        if(C('REQUEST_VARS_FILTER')){
			// 全局安全过滤
			array_walk_recursive($_GET,		'think_filter');
			array_walk_recursive($_POST,	'think_filter');
			array_walk_recursive($_REQUEST,	'think_filter');
		}

        // URL调度结束标签
        Hook::listen('url_dispatch');         

        define('IS_AJAX',       ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')])) ? true : false);

        // TMPL_EXCEPTION_FILE 改为绝对地址
        C('TMPL_EXCEPTION_FILE',realpath(C('TMPL_EXCEPTION_FILE')));
        return ;
    }

    /**
     * 执行应用程序
     * @access public
     * @return void
     */
    static public function exec() {
    
        if(!preg_match('/^[A-Za-z](\/|\w)*$/',CONTROLLER_NAME)){ // 安全检测
            $module  =  false;
        }elseif(C('ACTION_BIND_CLASS')){
            // 操作绑定到类：模块\Controller\控制器\操作
            $layer  =   C('DEFAULT_C_LAYER');
            if(is_dir(MODULE_PATH.$layer.'/'.CONTROLLER_NAME)){
                $namespace  =   MODULE_NAME.'\\'.$layer.'\\'.CONTROLLER_NAME.'\\';
            }else{
                // 空控制器
                $namespace  =   MODULE_NAME.'\\'.$layer.'\\_empty\\';                    
            }
            $actionName     =   strtolower(ACTION_NAME);
            if(class_exists($namespace.$actionName)){
                $class   =  $namespace.$actionName;
            }elseif(class_exists($namespace.'_empty')){
                // 空操作
                $class   =  $namespace.'_empty';
            }else{
                E(L('_ERROR_ACTION_').':'.ACTION_NAME);
            }
            $module  =  new $class;
            // 操作绑定到类后 固定执行run入口
            $action  =  'run';
        }else{
            //创建控制器实例
            $module  =  controller(CONTROLLER_NAME,CONTROLLER_PATH);                
        }

        if(!$module) {
            // 是否定义Empty控制器
            $module = A('Empty');
            if(!$module){
                E(L('_CONTROLLER_NOT_EXIST_').':'.CONTROLLER_NAME);
            }
        }

        // 获取当前操作名 支持动态路由
        if(!isset($action)){
            $action    =   ACTION_NAME.C('ACTION_SUFFIX');  
        }
        try{
            self::invokeAction($module,$action);
        } catch (\ReflectionException $e) { 
            // 方法调用发生异常后 引导到__call方法处理
            $method = new \ReflectionMethod($module,'__call');
            $method->invokeArgs($module,array($action,''));
        }
        return ;
    }
    public static function invokeAction($module,$action){
	if(!preg_match('/^[A-Za-z](\w)*$/',$action)){
		// 非法操作
		throw new \ReflectionException();
	}
	//执行当前操作
	$method =   new \ReflectionMethod($module, $action);
	if($method->isPublic() && !$method->isStatic()) {
		$class  =   new \ReflectionClass($module);
		// 前置操作
		if($class->hasMethod('_before_'.$action)) {
			$before =   $class->getMethod('_before_'.$action);
			if($before->isPublic()) {
				$before->invoke($module);
			}
		}
		// URL参数绑定检测
		if($method->getNumberOfParameters()>0 && C('URL_PARAMS_BIND')){
			switch($_SERVER['REQUEST_METHOD']) {
				case 'POST':
					$vars    =  array_merge($_GET,$_POST);
					break;
				case 'PUT':
					parse_str(file_get_contents('php://input'), $vars);
					break;
				default:
					$vars  =  $_GET;
			}
			$params =  $method->getParameters();
			$paramsBindType     =   C('URL_PARAMS_BIND_TYPE');
			foreach ($params as $param){
				$name = $param->getName();
				if( 1 == $paramsBindType && !empty($vars) ){
					$args[] =   array_shift($vars);
				}elseif( 0 == $paramsBindType && isset($vars[$name])){
					$args[] =   $vars[$name];
				}elseif($param->isDefaultValueAvailable()){
					$args[] =   $param->getDefaultValue();
				}else{
					E(L('_PARAM_ERROR_').':'.$name);
				}   
			}
			// 开启绑定参数过滤机制
			if(C('URL_PARAMS_SAFE')){
				$filters     =   C('URL_PARAMS_FILTER')?:C('DEFAULT_FILTER');
				if($filters) {
					$filters    =   explode(',',$filters);
					foreach($filters as $filter){
						$args   =   array_map_recursive($filter,$args); // 参数过滤
					}
				}                        
			}
			array_walk_recursive($args,'think_filter');
			$method->invokeArgs($module,$args);
		}else{
			$method->invoke($module);
		}
		// 后置操作
		if($class->hasMethod('_after_'.$action)) {
			$after =   $class->getMethod('_after_'.$action);
			if($after->isPublic()) {
				$after->invoke($module);
			}
		}
	}else{
		// 操作方法不是Public 抛出异常
		throw new \ReflectionException();
	}
    }
    /**
     * 运行应用实例 入口文件使用的快捷方法
     * @access public
     * @return void
     */
    static public function run() {
        // 应用初始化标签
        Hook::listen('app_init');
        App::init();
        // 应用开始标签
        Hook::listen('app_begin');
        // Session初始化
        if(!IS_CLI){
            session(C('SESSION_OPTIONS'));
        }
        // 记录应用初始化时间
        G('initTime');
        App::exec();
        // 应用结束标签
        Hook::listen('app_end');
        return ;
    }

    /**
     * [logo Trace logo]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    static public function logo() 
    {
        return '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';
    }
}
