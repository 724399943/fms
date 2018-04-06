<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class BbsArticleViewModel extends ViewModel
{
    public $viewFields = array(
        'BbsArticle'=>array('id', 'article_name','author','module_id','like_number','comment_number','view_number','is_top','is_recommend','add_time'),

    );
}
