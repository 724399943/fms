<?php

namespace Home\Controller;


class IndexController extends BaseController
{
    public function index()
    {

        $seo = self::getSysSeo();
        $this->assign('seo', $seo);
        $this->display($this->template . '/index');
    }
}