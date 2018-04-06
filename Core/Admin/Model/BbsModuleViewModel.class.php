<?php
namespace Admin\Model;

use Think\Model\ViewModel;

class BbsModuleViewModel extends ViewModel
{
    public $viewFields = array(
        'BbsModule'=>array('id', 'module_name','icon','is_lock','is_recommend','is_post'),

    );
}

