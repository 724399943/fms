<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8/008
 * Time: 15:48
 */

namespace Home\Controller;

trait SeoController
{

    private static $params = [
        'tags',
        'meta_keyword',
        'meta_description'
    ];

    private static $seoData = [];

    static public function getSysSeo($chName = 'ch_index', $params = [])
    {
        $params = implode(',', $params);
        $params = $params ? $params : 'tags,meta_description,meta_keyword';
        $seoData = M('seo')->where(['id_mark' => $chName])->field($params)->find();
        return $seoData;
    }

    static public function getLevelSeo($plate, $treeId, array $params)
    {
        $model = M('category');
        if (count($params) != count($params, 1)) {
            $params = $params[0];
        }
        $params = array_intersect($params, self::$params);
        $stepOne = $model->where(['id' => $treeId])->field('id,root_id,cat_path,tags,meta_description,meta_keyword')->find();
        if (!empty($stepOne)) {

            foreach ($params AS $value => $item) {
                if (empty($stepOne[$item])) {
                    $stepOne = self::getLevelSeo($plate, $stepOne['root_id'], $params);
                } else {
                    self::$seoData[$item] = $stepOne[$item];
                    unset($params[$value]);
                }
            }
        } else {
            self::$seoData = self::$seoData + self::getSysSeo($plate, $params);
        }
        return self::$seoData;
    }

    /**
     * array_diff  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    static private function isDifference(array $data)
    {
        return array_diff($data, self::$params);
    }
}