<?php
//获得拓展字段
if( !function_exists('getExtendFields')){
    function getExtendFields($tree_id){
        //获得扩展字段
        $moduleAttrModel = M('module_attr');
        $module_id = M('category')->where(['id'=>$tree_id])->getField('`module_id`');
        $where = [
            'module_id' => $module_id,
            'tree_id' => ['IN',$tree_id.',0']
        ];
        $extendFieldList = $moduleAttrModel->where($where)->select();
        foreach ($extendFieldList as $key => &$value) {
            if(in_array($value['input_type'],['checkbox','radio','select'])){
                //处理属性值
                $value['attr_value'] = explode("\r\n", trim($value['attr_value']));
            }
        }
        return $extendFieldList;
    }
}
//获得一级分类
if (!function_exists('getFirstCat')) {
    function getFirstCat()
    {
        $catModel = M('category');
        $where = array(
            'tree_id' => 0,
        );
        $firstCat = $catModel->where($where)->select();
        return $firstCat;
    }
}
if (!function_exists('getCateName')) {
    function getCateName($type)
    {
        $questionnaireCateModel = M('questionnaire_cate');
        $name = $questionnaireCateModel->where(['type' => $type])->getField('`name`');
        return $name;
    }
}
if (!function_exists('analyze')) {
    function analyze($list)
    {
        $langSeriesData = [0, 0, 0];
        $osSeriesData = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        $browserSeriesData = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        foreach ($list as $value) {
            // 语言分布
            switch ($value['language']) {
                case '简体中文':
                    ++$langSeriesData[0];
                    break;
                case '繁体中文':
                    ++$langSeriesData[1];
                    break;
                case 'English':
                    ++$langSeriesData[2];
                    break;
            }

            // 终端分布
            switch ($value['os']) {
                case 'Android':
                    ++$osSeriesData[0];
                    break;
                case 'iPhone':
                    ++$osSeriesData[1];
                    break;
                case 'iPad':
                    ++$osSeriesData[2];
                    break;
                case 'Windows':
                    ++$osSeriesData[3];
                    break;
                case 'MAC':
                    ++$osSeriesData[4];
                    break;
                case 'Linux':
                    ++$osSeriesData[5];
                    break;
                case 'Unix':
                    ++$osSeriesData[6];
                    break;
                case 'BSD':
                    ++$osSeriesData[7];
                    break;
                case 'Other':
                    ++$osSeriesData[8];
                    break;
            }

            // 浏览器分布
            if (strpos($value['browser'], 'MicroMessenger') !== false) {
                ++$browserSeriesData[0];
            } elseif (strpos($value['browser'], 'MQQBrowser') !== false) {
                ++$browserSeriesData[1];
            } elseif (strpos($value['browser'], 'UCBrowser') !== false) {
                ++$browserSeriesData[2];
            } elseif (strpos($value['browser'], 'Internet Explorer') !== false) {
                ++$browserSeriesData[3];
            } elseif (strpos($value['browser'], 'Opera') !== false) {
                ++$browserSeriesData[4];
            } elseif (strpos($value['browser'], 'Firefox') !== false) {
                ++$browserSeriesData[5];
            } elseif (strpos($value['browser'], 'Chrome') !== false) {
                ++$browserSeriesData[6];
            } elseif (strpos($value['browser'], 'Safari') !== false) {
                ++$browserSeriesData[7];
            } elseif (strpos($value['browser'], 'Other') !== false) {
                ++$browserSeriesData[8];
            }
        }

        foreach ($langSeriesData as &$value) {
            $value = json_encode([$value]);
        }

        foreach ($osSeriesData as &$value) {
            $value = json_encode([$value]);
        }

        foreach ($browserSeriesData as &$value) {
            $value = json_encode([$value]);
        }
        return [
            'langSeriesData' => $langSeriesData,
            'osSeriesData' => $osSeriesData,
            'browserSeriesData' => $browserSeriesData,
        ];
    }
}


if (!function_exists('recursionDirFiles')) {
    /**
     * recursionDirFiles  [递归目录和文件]
     * @param $dir
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function recursionDirFiles($dir)
    {
        $data = [];
        if (is_dir($dir)) {
            //是目录的话，先增当前目录进去
            $files = array_diff(scandir($dir), ['.', '..']);
            $data[] = $dir;
            foreach ($files as $file) {
                if (in_array(end(explode('.', $file)), ['tpl', 'html'])) {
                    $data = array_merge($data, recursionDirFiles($dir . '/' . $file));
                }
            }
        } else {
            $data[] = $dir;
        }
        return $data;
    }
}


if (!function_exists('getFileSize')) {
    /**
     * getFileSize  [description]
     * @param $size
     * @param string $format
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @return string
     */
    function getFileSize($file)
    {
        $size = filesize($file);

        $dw = " Byte";

        if ($size >= pow(2, 40)) {
            $size = round($size / pow(2, 40), 3);
            $dw = " TB";
        } else if ($size >= pow(2, 30)) {
            $size = round($size / pow(2, 30), 3);
            $dw = " GB";
        } else if ($size >= pow(2, 20)) {
            $size = round($size / pow(2, 20), 3);
            $dw = " MB";
        } else if ($size >= pow(2, 10)) {
            $size = round($size / pow(2, 10), 3);
            $dw = " KB";
        } else {
            $dw = " Bytes";
        }
        return $size . $dw;

    }
}

if (!function_exists('getSingles')) {
    /**
     * getSingles  [获得单页模型的子类]
     * @param $dir
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    function getSingles($tree_id, $module_id = '6')
    {
        $categoryModel = M('category');
        $singleList = $categoryModel->where(['module_id'=>$module_id,'tree_id'=>$tree_id])->select();
        (!$singleList) ? '' : $singleList;
        return $singleList;
    }
}

function getUserNameById($id) {
  $userName = M('user')->where(array('id'=>$id))->getField('nickname');
  return $userName;
}

function getModuelNameById($id) {
  $moduelName = M('bbs_module')->where(array('id'=>$id))->getField('module_name');
  return $moduelName;
}

function getArticleNameById($id) {
  $articleName = M('bbs_article')->where(array('id'=>$id))->getField('article_name');
  return $articleName;
}

if (!function_exists('getModuleId')) {
   
    function getModuleId($id)
    {
        $categoryModel = M('category');
        $module_id = $categoryModel->where(['id'=>$id])->getField('`module_id`');
        return $module_id;
    }
}

//根据id获得属性值
if (!function_exists('getAttrValue')) {
   
    function getAttrValue($id)
    {
        $attrValueModel = M('goods_attr_value');
        $attr_value = $attrValueModel->where(['id'=>$id])->getField('`attr_value`');
        return $attr_value;
    }
}

if (!function_exists('getAttrName')) {
   
    function getAttrName($id,$field)
    {
        $moduleAttrModel = M('module_attr');
        $attr_name = $moduleAttrModel->where(['id'=>$id])->getField($field);
        return $attr_name;
    }
}
