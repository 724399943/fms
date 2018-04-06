<?php
namespace Admin\Controller;
use Think\Controller;
// 登录控制器
class LoginController extends Controller {
    /**
     * [login 管理员登录]
     * @author StanleyYuen <[350204080@qq.com]>
     */
	public function login() {
        if ( IS_POST ) {
	        if(!$_SESSION['admin_login']) {
		        die('非法请求');
	        }
            // 记住密码
            $rememberPassword = I('post.rememberPassword');
            if ( $rememberPassword == '1' ) {
                $nextWeekTime = 3600 * 24 * 7;
                session_cache_expire($nextWeekTime / 60);
                session_set_cookie_params($nextWeekTime);
            }
            session_start();
			$postData = I('post.');
			if (array_key_exists('verify_c', $postData))
	        {
		        $verify_c = I("post.verify_c");
		        $verify    = new \Think\Verify();
		        $isVerify  = $verify->check($verify_c);
		        if (!$isVerify)
		        {
			        $this->error('请输入正确的验证码！');
		        }
	        }
            // 采用系统加密
            $adminModel = M('admin');
            $data = $adminModel->create();
            $data['admin_password'] = encrypt($data['admin_password']);
            $adminData =$adminModel->where($data)->find();

            if ($adminData['is_lock'] == '1') {
            	$this->error('账户被锁定！');
            }

    		if ( !empty($adminData) ) {
    			session('adminId',      $adminData['id']);
                session('adminAccount', $adminData['admin_account']);
                session('adminData', $adminData);

			    $_SESSION['login_fail'] = 0;
                if ( $rememberPassword == '1' ) {
                    session('rememberPassword', 1);
                }

                R('Public/auth', array($adminData['id']));
            } else {
			    $login_fail = $_SESSION['login_fail'] +1;
			    $_SESSION['login_fail'] = $login_fail;
			    if ($login_fail > 2)
			    {

					$_SESSION['is_show'] = 1;
			    }
    			$this->error('密码或用户出错！', U('Login/login'));
            }
    	} else {
	        $_SESSION['admin_login'] =1;
	        $is_show = $_SESSION['is_show'];
	        $this->assign('is_show',$is_show);
			$this->display('login');
    	}
	}

    /**
     * [logout 管理员退出]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    public function logout() {
        session_start();
        session('adminId', null);
        session('adminAccount', null);
        session('rememberPassword', null);

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie( session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"] );
        }

        $this->success('退出成功！', U('Login/login'));
    }

	/**
	 * 验证码生成
	 */
	public function verify_c() {
		$Verify = new \Think\Verify();
		$Verify->fontSize = 18;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->codeSet = '0123456789';
		$Verify->imageW = 130;
		$Verify->imageH = 42;
		$Verify->expire = 600;
		$Verify->entry();
	}

    /**
     * [register 注册后台账号]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function register() 
    {
        if ( IS_POST ) {
            $adminModel = D('Admin');
            $adminAuthGroupModel = M('admin_auth_group');
            $adminAuthRuleModel = M('admin_auth_rule');
            $adminAuthGroupAccessModel = M('admin_auth_group_access');
            $addData = $adminModel->create(I('post.'), 1);
            if ( !empty($addData) ) {
                $result = true;
                $adminModel->startTrans();
                try {
                    // 添加后台账号
                    $adminId = $adminModel->add($addData);
                    if ( $adminId === false ) {
                        throw new \Exception('创建后台账号失败');
                    }

                    // 添加后台账号管理角色
                    $rules = $adminAuthRuleModel->field('`id`')->select();
                    $rules = array_column($rules, 'id');
                    $ids = implode(',', $rules);
                    $addData = [
                        'agent_id'  => $adminId,
                        'title'     => '超级管理员',
                        'status'    => '1',
                        'rules'     => $ids
                    ];
                    $groupId = $adminAuthGroupModel->add($addData);
                    if ( $groupId === false ) {
                        throw new \Exception('创建后台账号管理角色失败');
                    }

                    // 添加后台账号权限
                    $addData = [
                        'uid'       => $adminId,
                        'group_id'  => $groupId
                    ];
                    if ( $adminAuthGroupAccessModel->add($addData) === false ) {
                        throw new \Exception('创建后台账号权限失败');
                    }

                    $adminModel->commit();
                    $this->success('注册成功', U('Login/login'));
                } catch(\Exception $e) {
                    $adminModel->rollback();
                    $this->error($e->getMessage());
                }
            } else {
                $this->error($adminModel->getError());
            }
        } else {
            $this->display();
        }
    }
}