<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/6/006
 * Time: 11:59
 */

namespace Plugins\Storage\Article;

interface InterfaceArticle
{

    /**
     * getArticleList  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getArticleList($catId);


    /**
     * getArticleDetail  [description]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getArticleDetail($id);


    /**
     * getPrevious  [上一条]
     * @param $treeid
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getPrevious($id);


    /**
     * getNext  [下一条]
     * @param $id
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return mixed
     */
    static public function getNext($id);
}