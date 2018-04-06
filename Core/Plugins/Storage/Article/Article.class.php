<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6/006
 * Time: 12:02
 */

namespace Plugins\Storage\Article;

class Article implements InterfaceArticle
{


    /**
     * @var 单例
     */
    private static $instance;


    private static $DB;


    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function __construct()
    {
        self::$DB = M('article');
    }


    /**
     * getArticleList  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @modify cdd <2042536829@qq.com>
     * @return mixed
     */
    static public function getArticleList($catId = 0,$page = 1)
    {
        // TODO: Implement getArticleList() method.
        $catId = $catId ? $catId : I('get.cat_id');
        if (!empty($catId)) {
            $where['tree_id'] = $catId;
        }
        $count = self::$DB->where($where)->count();

        $limit = C('wapArticlePage');
        $pageObj = new \Think\Page($count, $limit);
        $page = $page - 1;
        $page = $page < 0 ? 0 : $page;
        $limitStart = $page * $limit;

        $result['list'] = self::$DB->where($where)->order('is_top DESC, add_time DESC ')
                         ->field('`id`,`tree_id`,`cat_id`,`image`,`title`,`summary`,`add_time`')
                          ->limit($limitStart, $pageObj->listRows)->select();

        foreach ($result['list'] as $key => $$value) {
            $value['content'] = htmlspecialchars_decode($value['content']);
        }
        // $result['page'] = $pageObj->show();
        return $result ? $result : [];
    }

    /**
     * getArticleDetail  [description]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getArticleDetail($id)
    {
        // TODO: Implement getArticleDetail() method.
        $id = intval($id);
        //添加文章浏览数
        self::$DB->where(['id'=>$id])->setInc('hits',1);
        $info = self::$DB->where(['id' => $id])->find();
        return $info;
    }

    /**
     * getPrevious  [上一条]
     * @param $treeid
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getPrevious($id)
    {
        // TODO: Implement getPrevious() method.
        $id = intval($id);

        $info = self::$DB->where(" id < {$id} ")
            ->field('id,title')
            ->limit(1)
            ->order(' id DESC ')
            ->find();
        return $info ? $info : [];
    }

    /**
     * getNext  [下一条]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getNext($id)
    {
        // TODO: Implement getNext() method.
        $id = intval($id);

        $info = self::$DB->where(" id > {$id} ")
            ->field('id,title')
            ->limit(1)
            ->order(' id ASC ')
            ->find();
        return $info ? $info : [];
    }
}