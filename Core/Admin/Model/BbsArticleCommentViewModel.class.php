<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class BbsArticleCommentViewModel extends ViewModel
{
    public $viewFields = array(
        'BbsArticleComment'=>array('id', 'user_id', 'nickname','headimgurl','level','article_id','content','click_number','add_time','status'),

    );
}
