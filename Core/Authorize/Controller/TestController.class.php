<?php
namespace Authorize\Controller;
use Think\Controller;
use Think\Controller\JssdkController;

class TestController extends Controller {

    public function test() {
        sort($data);
        $string = implode('', $data);
        $signature = sha1($string);
    }
}