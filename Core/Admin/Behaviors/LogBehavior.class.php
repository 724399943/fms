<?php
namespace Admin\Behaviors;  
use Think\Behavior;  
class LogBehavior extends Behavior{  
    public function run(&$params){  
        // $data['url'] = substr(__ACTION__, strpos(__ACTION__, 'index.php')+strlen('index.php'));  
        // $data['operator'] = intval(session('admin_id'));  
        // $data['operate_time'] = time();  
        // $node = M('data_node')->where(array('m_c_a'=>$data['url']))->find();  
        // $data['description'] = $node['node_name'];  
        // // var_dump(APP_DEBUG);  
        // M('logs')->add($data);  
    }  
}