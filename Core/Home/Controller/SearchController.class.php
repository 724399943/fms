<?php

namespace Home\Controller;

class SearchController extends BaseController
{
    private $type = NULL;

    private $keyword = NULL;

    private $tplName = NULL;

    private $moduleType = [
        'product',
        'photo',
        'article',
        'download',
        'hr',
    ];

    /**
     * getSearchItems  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    private function getSearchItems()
    {
        $this->type = I('request.type', '');
        $this->keyword = I('request.keyword', '');
        $this->tplName = '/' . $this->type . '_search';
        if (!in_array($this->type, $this->moduleType)) {
            $this->error('请选择要搜索的类型');
        }
        if (empty($this->keyword)) {
            $this->error('请输入要搜索的关键字');
        }

    }


    /**
     * search  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function search()
    {
        $this->getSearchItems();

        if($this->type == 'product'){
            $model = M('goods');
        }else{

            $model = M($this->type);
        }
        $where = [];
        switch ($this->type) {
            case 'article':
                $where['title'] = ['LIKE', $this->keyword];
                break;
            case 'product':
                $where['goods_name'] = ['LIKE', "%{$this->keyword}%"];
                // $where['goods_code'] = ['LIKE', "%{$this->keyword}%"];
                // $where['_logic'] = 'OR';
                break;
        }
        $where['goods_main_id'] = '0';
        $count = $model->where($where)->count();
        $page = new \Think\Page($count, 10);
        $keyword = '/keyword/' . $this->keyword;
        $type = '/type/' . $this->type;
        $page->setConfig('link', '/Search/search/p/zz' . $keyword . $type);
        $info = $model->where($where)->limit($page->firstRow, $page->listRows)->select();
        $result = [
            'search' => $info,
            'page' => $page->show(),
            'keyword' => $this->keyword,
            'type' => $this->type,
            'seo' => self::getSysSeo('ch_' . $this->type . '_search')
        ];
        // dump($this->tplName);
        $this->assign($result);
        $this->display(C('wapTemplet') . $this->tplName);
    }

}