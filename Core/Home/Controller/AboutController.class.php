<?php
namespace Home\Controller;
class AboutController extends BaseController{

    public function aboutUs(){
        
        $this->display($this->template . '/about_detail');
    }
}