<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8/008
 * Time: 10:54
 */

namespace Home\Controller;

class GuestBookController extends BaseController
{
    public function guestBookMessage()
    {
        $seo = self::getSysSeo('ch_guestbook');
        $this->assign('seo', $seo);
        $this->display($this->template . '/guestbook_message');
    }


    /**
     * 验证码生成
     */
    public function verify()
    {
        $Verify = new \Think\Verify();
        $Verify->fontSize = 10;
        $Verify->length = 4;
        $Verify->useNoise = false;
        $Verify->useCurve = false;
        $Verify->codeSet = '0123456789';
        $Verify->imageW = 75;
        $Verify->imageH = 20;
        $Verify->entry();
    }

    /**
     * addGuestBookMessage  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function addGuestBookMessage()
    {
        $guestBookModel = M('guest_book');
        $postData = I('post.');

        if (!isEmail($postData['email'])) {
            $this->error('邮箱格式不正确！');
        }

        if (!isPhone($postData['mobile'])) {
            $this->error('手机格式不正确！');
        }

        if (array_key_exists('checkcode', $postData)) {
            $verify_c = $postData['checkcode'];
            $verify = new \Think\Verify();
            $isVerify = $verify->check($verify_c);
            if (!$isVerify) {
                $this->error('请输入正确的验证码！');
            }
        }

        $data = [
            'username' => $postData['username'],
            'title' => $postData['title'],
            'email' => $postData['email'],
            'content' => $postData['content'],
            'qq' => $postData['qq'],
            'mobile' => $postData['mobile'],
            'add_time' => time(),
            'ip' => getIP(),
        ];
        if (!$guestBookModel->add($data)) {
            $this->error('提交失败！');
        } else {
            $this->success('成功！', U('Index/index'));
        }
    }
}