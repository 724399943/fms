<?php

namespace Home\Controller;


use Plugins\Storage\Article\Article;

class ArticleController extends BaseController
{
    public function __construct(){
        parent::__construct();

    }
    public function article()
    {
        $seo = self::getSysSeo('ch_article_index');
        $this->assign('seo', $seo);
        $this->display($this->template . '/article_list');
    }


    /**
     * detail  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function articleDetail()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数丢失！');
        }
        $instance = Article::getInstance();

        $info = $instance::getArticleDetail($id);

        $previous = $instance::getPrevious($info['id']);
        $next = $instance::getNext($info['id']);

        $seo = [];
        if (empty($info['tags']) || empty($info['meta_keyword']) || empty($info['meta_description'])) {

            $tags = !empty($info['tags']) ? $seo['tags'] = $info['tags'] : 'tags';
            $meta_keyword = !empty($info['meta_keyword']) ? $seo['meta_keyword'] = $info['meta_keyword'] : 'meta_keyword';
            $meta_description = !empty($info['meta_description']) ? $seo['meta_description'] = $info['meta_description'] : 'meta_description';

            $data = [
                $tags, $meta_keyword, $meta_description
            ];
            $seo = $seo + self::getLevelSeo('ch_article_index', $info['cat_id'],$data);
        }
        $result = [
            'info' => $info,
            'previous' => $previous ? $previous : '没有了！',
            'next' => $next ? $next : '没有了！',
            'seo' => $seo,
        ];

        $this->assign($result);
        $this->display(C('wapTemplet') . '/article_detail');
    }

    public function getArticleList($index = 'list',$catId = 0,$page=1){
        if($index == 'list'){
            $article = \Plugins\Storage\Article\Article::getInstance();
            $articleList = $article::getArticleList($catId,$page);

            $articleList =  $articleList ? $articleList : [];
            echo statusCode($articleList,200000,'成功');
        }
    }

    /**
     * [loadArticleList 加载文章]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    public function loadArticleList(){
        if(IS_POST){
            $limit = PAGE_LIMIT;
            $catId = I('catId','','string');//直接分类id
            $sign = I('sign','list','string');
            $treeId = I('treeId','','string');//一级分类id
            $limitData = $this->limitStar . ',' . $limit;
            // dump($limitData);
            if( !empty($catId) ){
                $where['cat_id'] = $catId;
            }
            if( !empty($treeId) ){
                $where['tree_id'] = $treeId;
            }
            $articleModel = M('article');
            $list = $articleModel->where($where)->limit($limitData)->select();
            // dump($articleModel->getLastSql());
            $return['list'] = $list;
            echo statusCode($return,200000);
        }else{
            echo statusCode([],100001);
        }
    }
}