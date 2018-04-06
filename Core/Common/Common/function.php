<?php
/**
 * [checkActionAuth 检查方法权限]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function checkActionAuth($controllerAction)
{
    static $authListCache;
    if (empty($authListCache)) {
        $authListCache = session('authList');
    }

    if (is_array($controllerAction)) {
        foreach ($controllerAction as $value) {
            if (in_array(strtolower($value), $authListCache)) {
                return true;
            }
        }
    } else {
        if (in_array(strtolower($controllerAction), $authListCache)) {
            return true;
        }
    }

    return false;
}

function curl($url, $data = '', $requestType = 'post')
{
    //初始化curl
    $ch = curl_init();
    //设置超时
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    if (strtolower($requestType) == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

/**
 * [checkControllerAuth 检查全控制器权限]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function checkControllerAuth($controllerAction)
{
    static $authListCache;
    if (empty($authListCache)) {
        $temp = session('authList');

        foreach ($temp as $key => $tvalue) {
            $temp[$key] = substr($tvalue, 0, strpos($tvalue, '-'));
        }

        $temp = array_unique($temp);
        $authListCache = $temp;
    }

    if (is_array($controllerAction)) {
        // 或条件
        foreach ($controllerAction as $value) {
            if (in_array(strtolower($value), $authListCache)) {
                return true;
            }
        }
    } else {
        if (in_array(strtolower($controllerAction), $authListCache)) {
            return true;
        }
    }
}

/**
 * [getAuthUrl 得到权限地址]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getAuthUrl($controllerAction)
{
    static $authListCache;
    if (empty($authListCache)) {
        $authListCache = session('authList');
    }

    if (is_array($controllerAction)) {
        foreach ($controllerAction as $value) {
            if (in_array(strtolower($value), $authListCache)) {
                $url = str_replace('-', '/', $value);
                return U($url);
            }
        }
    } else {
        if (in_array(strtolower($controllerAction), $authListCache)) {
            $url = str_replace('-', '/', $controllerAction);
            return U($url);
        }
        return false;
    }
}

/**
 * [encrypt 系统标准加密]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function encrypt($string, $length = '')
{
    $crypt = md5($string);

    if (!empty($length)) {
        return substr($crypt, 0, $length);
    } else {
        return $crypt;
    }
}

/**
 * [time_format 标准时间格式化]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function time_format($time)
{
    return (!empty($time)) ? date('Y/m/d H:i:s', $time) : '-';
}

/**
 * [recursionGetCate 递归获取分类数组]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function recursionGetCate($cid, $cate_arr)
{
    $cate = M('goods_category')->where(array('id' => $cid))->find();

    array_push($cate_arr, $cid);
    if ($cate['level'] == 1 || empty($cate)) {
        return $cate_arr;
    } else {
        return recursionGetCate($cate['pid'], $cate_arr);
    }
}

/**
 * [array_column 取数组某列]
 * @version array_column 需要 (PHP 5 >= 5.5.0)
 */
if (!function_exists('array_column')) {
    function array_column($input, $columnKey, $indexKey = NULL)
    {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? TRUE : FALSE;
        $indexKeyIsNull = (is_null($indexKey)) ? TRUE : FALSE;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? TRUE : FALSE;
        $result = array();

        foreach ((array)$input AS $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : NULL;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : NULL;
            }

            if (!$indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key)) ? current($key) : NULL;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }
}

/**
 * [array_column_sort 根据数组指定列排序]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function array_column_sort($original_arr, $column = '', $order = 'SORT_DESC')
{
    $sort = array(
        'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
        'field' => $column,       //排序字段
    );

    $arrSort = array();
    foreach ($original_arr AS $uniqid => $row) {
        foreach ($row AS $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }

    if ($sort['direction']) {
        array_multisort($arrSort[$sort['field']], constant($sort['direction']), $original_arr);
    }

    return $original_arr;
}

/**
 * [fileUpload 公共文件上传]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)           2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $savePath     [description]
 * @param     [type]        $callable     [description]
 * @param     array $parameters [description]
 * @param     boolean $customUpload [是否使用自定义的$savePath]
 * @param     boolean $createNewFileName [是否使用新文件名]
 * @return    [type]                      [description]
 */
function fileUpload($savePath, $callable, $parameters = array(), $customUpload = false, $createNewFileName = true)
{
    $uploadPath = $customUpload === true ? '' : C('UPLOAD_PATH');
    $savePath = $uploadPath . $savePath;
    if (!file_exists($savePath)) {
        mkdir($savePath, 0700, true);
    }

    $upload = new \Think\Upload(array(), '', null, $customUpload, $createNewFileName);
    $upload->maxSize = 209715200;
    $upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'zip', 'rar', 'ico', 'txt', 'pfx', 'cer');
    $upload->rootPath = $savePath;
    $info = $upload->upload();

    if (!$info) {
        echo $upload->getError();
    } elseif (is_callable($callable)) {
        $keys = array_keys($info);
        $key = $keys[0];
        $one = $info[$key];

        if (empty($parameters)) {
            $one['filePath'] = $savePath . $one['savepath'] . $one['savename'];
            // 解压zip、rar文件
            switch ($one['ext']) {
                case 'zip':
                    extractZip($one['filePath'], $savePath . $one['savepath']);
                    break;
                case 'rar':
                    // extractRar($one['filePath'], $savePath . $one['savepath']);
                    break;
                default:

                    break;
            }
        } else {
            // file input的id
            // $parameters['id'] = $parameters['id'] ? $parameters['id'] : 'fileToUpload';
            $parameters['id'] = $key ? $key : 'fileToUpload';
            $photoNamenoExt = str_replace('.' . $info[$parameters['id']]['ext'], '', $info[$parameters['id']]['savename']);
            $image = new \Think\Image();
            $image->open($savePath . $one['savepath'] . $one['savename']);

            if ($parameters['multi']) {
                // 是否需要生成多种尺寸
                foreach ($parameters['size'] as $key => $value) {
                    $widPath = "{$savePath}{$one['savepath']}{$value['width']}x{$value['height']}/";
                    if (!is_file($widPath)) {
                        mkdir($widPath, 0700, TRUE);
                    }
                    $image->thumb($value['width'], $value['height'], 3)->save("{$savePath}{$one['savepath']}{$value['width']}x{$value['height']}/{$photoNamenoExt}_{$value['width']}x{$value['height']}.{$info[$parameters['id']]['ext']}");
                }
                $one['filePath'] = "{$savePath}{$one['savepath']}{$parameters['size'][0]['width']}x{$parameters['size'][0]['height']}/{$photoNamenoExt}_{$parameters['size'][0]['width']}x{$parameters['size'][0]['height']}.{$info[$parameters['id']]['ext']}";
            } else {
                $widPath = "{$savePath}{$one['savepath']}{$parameters['width']}x{$parameters['height']}/";
                if (!is_file($widPath)) {
                    mkdir($widPath, 0700, TRUE);
                }
                $image->thumb($parameters['width'], $parameters['height'], 3)->save("{$savePath}{$one['savepath']}{$parameters['width']}x{$parameters['height']}/{$photoNamenoExt}_{$parameters['width']}x{$parameters['height']}.{$info[$parameters['id']]['ext']}");
                $one['filePath'] = "{$savePath}{$one['savepath']}{$parameters['width']}x{$parameters['height']}/{$photoNamenoExt}_{$parameters['width']}x{$parameters['height']}.{$info[$parameters['id']]['ext']}";
            }
        }
        $callable($one);
    }
}

/**
 * [extractZip 解压zip文件]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)       2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $filename [description]
 * @param     [type]        $path     [description]
 * @return    [type]                  [description]
 */
function extractZip($filename, $path)
{
    //先判断待解压的文件是否存在  
    if (!file_exists($filename)) {
        die("文件 $filename 不存在！");
    }
    $starttime = explode(' ', microtime()); //解压开始的时间   
    //打开压缩包  
    $resource = zip_open($filename);
    // $i = 1;  
    //遍历读取压缩包里面的一个个文件  
    while ($dir_resource = zip_read($resource)) {
        //如果能打开则继续  
        if (zip_entry_open($resource, $dir_resource)) {
            //获取当前项目的名称,即压缩包里面当前对应的文件名  
            $file_name = $path . zip_entry_name($dir_resource);
            // asciitog($file_name);  // 中文转码
            //以最后一个“/”分割,再用字符串截取出路径部分  
            $file_path = substr($file_name, 0, strrpos($file_name, "/"));
            //如果路径不存在，则创建一个目录，true表示可以创建多级目录  
            if (!is_dir($file_path)) {
                mkdir($file_path, 0777, true);
            }
            //如果不是目录，则写入文件  
            if (!is_dir($file_name)) {
                //读取这个文件  
                $file_size = zip_entry_filesize($dir_resource);
                //最大读取6M，如果文件过大，跳过解压，继续下一个  
                if ($file_size < (1024 * 1024 * 6)) {
                    $file_content = zip_entry_read($dir_resource, $file_size);
                    file_put_contents($file_name, $file_content);
                } else {
                    // echo "<p> ".$i++." 此文件已被跳过，原因：文件过大， -> ".iconv("gb2312", "utf-8", $file_name)." </p>";  
                }
            }
            //关闭当前  
            zip_entry_close($dir_resource);
        }
    }
    //关闭压缩包  
    zip_close($resource);
    // $endtime = explode(' ', microtime()); //解压结束的时间  
    // $thistime = $endtime[0] + $endtime[1] - ($starttime[0] + $starttime[1]);  
    // $thistime = round($thistime, 3); //保留3为小数  
    // echo "<p>解压完毕！，本次解压花费：$thistime 秒。</p>演示地址：/".$path;  
}

/**
 * [asciitog 中文转码]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)    2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $brand [description]
 * @return    [type]               [description]
 */
function asciitog($brand)
{
    $char = mb_detect_encoding($brand);
    if ($char == 'UTF-8') {
        $brand2 = iconv('UTF-8', "GB2312", $brand);
    }

    if (!empty($brand2)) {
        $char2 = mb_detect_encoding($brand2);
        if ($char2 != 'ASCII') {
            $brand = $brand2;
        }
    }
    return $brand;
}

/**
 * [extractRar 解压rar文件]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
 * @return    [type]        [description]
 */
function extractRar($filename, $path)
{
    if ($obj = new com("wscript.shell")) {
        $obj->run('winrar x ' . $filename . ' ' . $path, 0, true);
        return true;
    } else {
        return false;
    }
}

// 过滤javascript代码防止xss攻击
function remove_xss($val)
{
    $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        $val = preg_replace('/(&#[xX]0{0,8}' . dechex(ord($search[$i])) . ';?)/i', $search[$i], $val);
        $val = preg_replace('/(�{0,8}' . ord($search[$i]) . ';?)/', $search[$i], $val);
    }

    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);
    $found = true;
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(�{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2) . '<x>' . substr($ra[$i], 2);
            $val = preg_replace($pattern, $replacement, $val);
            if ($val_before == $val) {
                $found = false;
            }
        }
    }
    return $val;
}

/**
 * [getAdBox 得到广告位信息]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getAdBox($sign, $isJson = false)
{
    $adGroupInfo = M('ad_box')->where(array('sign' => $sign))->find();
    dump($adGroupInfo);
    die;
    $adInfo = M('ad')->field('url, image, target, ad_name, content')
        ->where(array('box_id' => $adGroupInfo['id'], 'end_time' => array('gt', time())))
        ->order('start_time DESC')
        ->find();

    $array = array(
        'width' => $adGroupInfo['width'],
        'height' => $adGroupInfo['height'],
    );

    if (empty($adInfo)) {
        $array['url'] = C('webSite');
        $array['target'] = '_blank';
        $array['ad_name'] = 'okshop';
    } else {
        $array['url'] = $adInfo['url'];
        $array['image'] = $adInfo['image'];
        $array['target'] = $adInfo['target'];
        $array['ad_name'] = $adInfo['ad_name'];
        $array['content'] = $adInfo['content'];
        $array['type'] = $adGroupInfo['type'];
    }

    if ($isJson) {
        exit(json_encode($array));
    } else {
        return $array;
    }
}

/**
 * [getAdGroup 得到分组广告信息]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getAdGroup($sign, $isJson = false)
{
    $adGroupInfo = M('ad_group')->where(array('group_sign' => $sign))->find();
    $adBox = M('ad_box')->where(array('group_id' => $adGroupInfo['id']))->field('`width`,`height`,`id`')->select();

    $adData = array();
    $now = time();
    foreach ($adBox as $value) {
        $tmpAd = M('ad')->field('url, image, target, ad_name, content')
            ->where(array('box_id' => $value['id'], 'end_time' => array('gt', $now)))
            ->order('start_time DESC')
            ->find();

        if (!empty($tmpAd)) {
            $tmpAd['width'] = $value['width'];
            $tmpAd['height'] = $value['height'];
            $adData[] = $tmpAd;
            //$tmpAd['url']     = C('webSite');
            //$tmpAd['target']  = '_blank';
            //$tmpAd['ad_name']  = 'okshop';
        }

    }

    if ($isJson) {
        exit(json_encode($adData));
    } else {
        return $adData;
    }
}

/**
 * [getIds 根据广告标识获取广告商品数据]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getIds($signName)
{
    $whereArr = array(
        'sign' => $signName
    );
    $return = M('ad_goods')->where($whereArr)->getField('value');
    return $return;
}

/**
 * [getArticleGroupName 获取文章分类名称]
 * @author Fu <[418382595@qq.com]>
 */
function getArticleGroupName($ids)
{
    $where = array('id' => $ids);
    $group_id = M('article')->where($where)
        ->field('group_id')
        ->find();
    $where = array('id' => $group_id['group_id']);
    $result = M('article_group')->where($where)
        ->field('group_name')
        ->find();
    return $result['group_name'];
}

function getArtSignName($sign, $agentId)
{
    // return $agentId;
    if ($sign == '') {
        return '普通标识';
    }
    $config = M('config')->where(array('agent_id' => $agentId))->getField('config');
    $config = json_decode($config, true);
    foreach ($config as $key => $value) {
        if ($value['config_sign'] == 'articleType') {
            $artSign = $value['config_value'];
        }
    }
    $artSign = explode(',', $artSign);
    foreach ($artSign as $key => $value) {
        $artSign[$key] = explode(':', $value);
        if ($artSign[$key][0] == $sign) {
            if ($artSign[$key][0] == 'aboutGene') $artSign[$key][1] = '(pc)关于' . C('systemName');
            return $artSign[$key][1];
        }
    }
}


/**
 * [getGoodsCategoryName description]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)  2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $ids [description]
 * @return    [type]             [description]
 */
function getGoodsCategoryName($id)
{
    $name = M('goods_category')->where(array('id'=> $id))->getField('category_name');
    $name = !empty($name) ? $name : '';
    return $name;
}

/**
 * [getRegionName 获取地区名称]
 * @author Fu <[418382595@qq.com]>
 */
function getRegionName($ids)
{
    $where = array('id' => $ids);

    $result = M('region')->where($where)->getField('region_name');
    return $result;
}

/**
 * [getShopName 获取店铺名称]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c) 2017          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $id [description]
 * @return    [type]            [description]
 */
function getShopName($id)
{
    $name = M('shop')->where(array('id' => $id))->getField('shop');
    return $name;
}

/**
 * [getTypeName 获取分类名称]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c) 2017          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $id [description]
 * @return    [type]            [description]
 */
function getTypeName($id)
{
    $name = M('type')->where(array('id' => $id))->getField('type');
    return $name;
}

/**
 * [getBrandName 获取品牌名称]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getBrandName($id)
{
    $result = M('brand')->where(array('id' => $id))->getField('brand_name');
    return $result;
}

/**
 * [getNickName 获取用户昵称]
 * @author Fu <[418382595@qq.com]>
 */
function getNickName($userId)
{
    $nickName = M('user')->where(array('id' => $userId))->getField('nickname');
    return $nickName;
}

/**
 * [getReturnType 获取申请类型]
 * @author Fu <[418382595@qq.com]>
 */
function getReturnType($typeId)
{
    switch ($typeId) {
        case 0:
            return '退货';
            break;
        case 1:
            return '换货';
            break;
        case 2:
            return '退款';
            break;
    }
}

/**
 * [mySubstr 截取字符串(中文截取)]
 * @author Fu <[418382595@qq.com]>
 */
function mySubstr($str, $len = 20, $suffix = '...', $charset = 'UTF-8')
{
    $substr = mb_substr($str, 0, $len, $charset);
    if ($substr != $str) $substr .= $suffix;
    return $substr;
}

/**
 * [getGoodsList description]
 * @author NicFung <13502462404@qq.com>
 * @copyright Copyright (c)  2015          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $ids [description]
 * @return    [type]             [description]
 */
function getGoodsList($sign, $isJson = false)
{
    $ids = getIds($sign);
    if (!empty($ids)) {
        $where = array('id' => array('in', $ids));
        $result = M('goods')->where($where)->select();
        if ($isJson) {
            $result = json_encode($result);
        }
        return $result;
    }
    return false;
}

/**
 * [getLinkList 得到链接列表]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getLinkList($sign, $isJson = false)
{
    $where = array('sign' => $sign);
    $result = M('ad_goods')->where($where)->getField('value');
    if (!$isJson) {
        $result = json_decode($result, true);
    }
    return $result;
}

/**
 * [getGoodsTag description]
 * @author NicFung <13502462404@qq.com>
 * @copyright Copyright (c)  2015          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $ids [description]
 * @return    [type]             [description]
 */
function getGoodsTag($sign)
{
    $where = array('sign' => $sign);
    $result = M('ad_goods')->where($where)->getField('tag');
    return $result;
}

/**
 * [getBrandList 得到广告位品牌列表]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getBrandList($ids)
{
    $where = array('id' => array('in', $ids));
    $result = M('brand')->where($where)->select();
    return $reuslt;
}

/**
 * [getUserLevelName 得到会员等级名称]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)    2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $level [description]
 * @return    [type]               [description]
 */
function getUserLevelName($level, $agentId = '1')
{
    $userGrade = M('user_grade');
    $config = $userGrade->where(array('agent_id' => $agentId))->getField('rules');
    if (!empty($config)) {
        $config = json_decode($config, true);
        foreach ($config as $key => $value) {
            if ($value['grade_level'] == $level) {
                $level_name = $value['grade_name'];
            }
        }
    } else {
        $level_name = '普通用户';
    }
    return $level_name;
}

/**
 * [getLevelGrow 得到等级对应的成长值]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getLevelGrow($level)
{
    $userGrow = explode(',', C('userGrow'));
    return $userGrow[$level];
}

/**
 * [getBreadcrumb 得到商品面包屑导航数据]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function getGoodsBreadcrumb($categoryId)
{
    $where = array(
        'is_on_sale' => '1',
    );
    $result = M('goods_category')->where($where)->field('id, pid, category_name')->where(array('id' => $categoryId))->find();

    if ($result['pid'] <= 0) {
        return array($result);
    } else {
        $categoryList = getGoodsBreadcrumb($result['pid']);
        array_push($categoryList, $result);
        return $categoryList;
    }
}

/**
 * [getMessage 用户登录获取系统消息]
 * @author Fu <[418382595@qq.com]>
 */
function getMessage($userId)
{
    $messageModel = M('message');
    $relavanceModel = M('message_relavance');
    $is_temp = session('is_temp');

    if ($is_temp == 1) {
        return;
    }

    $relavanceWhere = array(
        'receiver_id' => $userId,
        'type' => 0,
    );
    $count = $relavanceModel->where($relavanceWhere)->count();
    //处理用户第一次登录
    if ($count == 0) {
        $allMessage = $messageModel->where(array('type' => '0'))->field('id')->order('id DESC')->select();
        foreach ($allMessage as $key => $value) {
            $relavanceData = array(
                'message_id' => $value['id'],
                'receiver_id' => $userId,
                'is_read' => 0,
                'add_time' => time(),
                'type' => 0,
            );
            $result = $relavanceModel->data($relavanceData)->add();
        }
    } else {
        //处理二次登陆
        $relavanceTime = $relavanceModel->where($relavanceWhere)->order('id DESC')->find();
        // dump($relavanceTime);die;
        $messageWhere = array(
            'type' => '0',
            'add_time' => array('gt', $relavanceTime['add_time']),
        );
        $messageList = $messageModel->where($messageWhere)->field('id,type,add_time')->select();

        if (!empty($messageList)) {
            foreach ($messageList as $key => $value) {
                $relavanceData = array(
                    'message_id' => $value['id'],
                    'receiver_id' => $userId,
                    'is_read' => 0,
                    'add_time' => time(),
                    'type' => 0,
                );
                $result = $relavanceModel->data($relavanceData)->add();
            }
        }
    }
    return $result;
}

if (!function_exists('Sendmessage')) {
    /**
     * [Sendmessage 发送消息记录]
     * @author Fu <418382595@qq.com>
     * @license   [license]
     * @version   [version]
     * @param     [type]        $receiverId     [收信人id]
     * @param     [type]        $order_sn       [订单号]
     * @param     [type]        $type           [信息内容类别选择]
     * @param     [type]        $messageType    [信息类型 0 公告 1 订单]
     * @return    [type]
     */
    function Sendmessage($receiverId, $order_sn, $type)
    {
        switch ($type) {
            case '1':
                //下单未付款
                $title = "下单未支付提醒";
                $content = "【{$order_sn}】订单未支付";
                break;
            case '2':
                // 下单已付款
                $title = "下单已支付提醒";
                $content = "【{$order_sn}】订单已支付";
            case '3':
                $title = "检测报告发送提醒";
                $content = "您好，您的基因检测报告已发出请到【我的检测】界面点击下载；也可登录网址，微信扫一扫登录商城，点击头像进入个人中心，在【我的检测结果】下载个人基因报告,并使用PDF阅读器查看阅读";
                break;
        }

        $messageModel = M('message');
        $messageData = array(
            'title' => $title,
            'content' => $content,
            'type' => 1,   //指定用户
            'message_type' => $type,  //消息类型
            'is_delete' => 0,
            'add_time' => time(),
        );
        $messageId = $messageModel->data($messageData)->add();

        $relavanceModel = M('message_relavance');
        $relavanceData = array(
            'message_id' => $messageId,
            'receiver_id' => $receiverId,
            'is_read' => 0,
            'add_time' => time(),
            'type' => 0,
        );
        $addResult = $relavanceModel->data($relavanceData)->add();
        return $addResult;
    }
}

/**
 * 发送模板短信
 * @param to 手机号码集合,用英文逗号分开
 * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
 * @param $tempId 模板Id
 */
function sendTemplateSMS($mobiles, $text)
{
    Vendor('Ztsms.Ztsms');
    $sms = new Ztsms(C('SMS_URL'), C('SMS_USERNAME'), C('SMS_PWD'), C('SMS_PRODUCT'));
    $result = $sms->sendSMS($mobiles, $text);
    return $result;
}

if (!function_exists('random_string')) {
    /**
     * [random_string 随机字符串]
     * @param  string $type [规则]
     * alnum  含有大小写字母以及数字。
     * numeric  数字字符串。
     * nozero  不含零的数字字符串。
     * alpha  含有大小写字母
     * unique 用 MD5 和 uniqid()加密的字符串。注意：第二个长度参数在这种类型无效。均返回一个32位长度的字符串。
     * md5
     * @param  integer $len [字符数]
     * @return [type]        [description]
     */
    function random_string($type = 'alnum', $len = 8)
    {
        switch ($type) {
            case 'basic'  :
                return mt_rand();
                break;
            case 'alnum'  :
            case 'numeric'  :
            case 'nozero' :
            case 'alpha'  :
                switch ($type) {
                    case 'alpha'  :
                        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'alnum'  :
                        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric'  :
                        $pool = '0123456789';
                        break;
                    case 'nozero' :
                        $pool = '123456789';
                        break;
                }

                $str = '';
                for ($i = 0; $i < $len; $i++) {
                    $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
                }
                return $str;
                break;
            case 'unique' :
            case 'md5'    :
                return md5(uniqid(mt_rand()));
                break;
        }
    }
}

/**
 * [sendMail 发送邮件]
 * @author StanleyYuen <[350204080@qq.com]>
 */
function sendMail($to, $title, $content)
{
    import("Plugins.Mailer.PHPMailerAutoload");
    PHPMailerAutoload('phpmailer');
    PHPMailerAutoload('smtp');

    $mail = new PHPMailer();       //得到一个PHPMailer实例
    $mail->CharSet = "utf-8";                //设置采用utf-8中文编码(内容不会乱码)
    $mail->IsSMTP();                          //设置采用SMTP方式发送邮件 
    $mail->Host = "smtp.qq.com";        //设置邮件服务器的地址(若为163邮箱，则是smtp.163.com)
    $mail->Port = 25;                     //设置邮件服务器的端口，默认为25
    $mail->From = C('mail');             //设置发件人的邮箱地址
    //$mail->From     = '961085397@qq.com';
    $mail->FromName = C('systemName');           //设置发件人的姓名(可随意) 
    $mail->SMTPAuth = true;                   //设置SMTP是否需要密码验证，true表示需要 
    $mail->SMTPSecure = false;

    $mail->Username = C('mail');               //(后面有解释说明为何设置为发件人)
    //$mail->Username   = '961085397@qq.com';
    $mail->Password = C('mailPassword');
    //$mail->Password = 'SHU1202.LU';
    $mail->Subject = $title;                 //主题
    $mail->AltBody = "text/html";

    $contents = $content;

    $mail->Body = $contents;            //内容   
    $mail->IsHTML(true);
    $mail->WordWrap = 50;                    //设置每行的字符数
    $mail->AddReplyTo(C('mail'), "from");    //设置回复的收件人的地址(from可随意) 
    $mail->AddAddress($to, "to");        //设置收件的地址(to可随意) 
    $result = $mail->Send();

    if ($result) {
        $returnData['err'] = 0;
        $returnData['msg'] = 'send success';
    } else {
        $returnData['err'] = 1;
        $returnData['msg'] = "{$mail->ErrorInfo}";
    }

    return $returnData;
}


/**
 * [isPhone 验证手机号码格式]
 * @author Fu <[418382595@qq.com]>
 */
function isPhone($phone)
{
    $chars = "/^13[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/";
    if (preg_match($chars, $phone)) {
        return true;
    }
    return false;
}

/**
 * [isEmail 验证邮箱格式]
 * @author Fu <[418382595@qq.com]>
 */
function isEmail($value)
{
    $chars = "/^[0-9a-zA-Z]+(?:[\_\.\-][a-z0-9\-]+)*@[a-zA-Z0-9]+(?:[-.][a-zA-Z0-9]+)*\.[a-zA-Z]+$/i";
    if (preg_match($chars, $value)) {
        return true;
    }
    return false;
}

/**
 * [isZipCode 验证邮编格式]
 * @author Fu <[418382595@qq.com]>
 */
function isZipCode($value)
{
    $chars = "/^[1-9]\d{5}$/";
    if (preg_match($chars, $value)) {
        return true;
    }
    return false;
}


/**
 * [isFixLine 验证固话格式]
 * @author Fu <[418382595@qq.com]>
 */
function isFixLine($value)
{
    $chars = "/^0\d{2,3}-\d{7,8}$/";
    if (preg_match($chars, $value)) {
        return true;
    }
    return false;
}

/**
 * [makeClickableLinks 将网址字符串转换成超链接]
 * @author Fu <[418382595@qq.com]>
 */
function makeClickableLinks($text)
{
    $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)', '<a href="\1">\1</a>', $text);
    $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)', '\1<a href="http://\2">\2</a>', $text);
    $text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})', '<a href="mailto:\1">\1</a>', $text);
    return $text;
}

function delFileUnderDir($dirName = "Application/Runtime")
{
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    delFileUnderDir("$dirName/$item");
                } else {
                    unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
    }
}

/**
 * [getUserIntegrityDegree 获取用户资料完整度]
 * @author Fu <[418382595@qq.com]>
 */
// function getUserIntegrityDegree(){
//   $userDetail = session('userDetail');
//   $tempNum = 0;
//   foreach ($userDetail as $key => $value) {
//     if ($value == "") {
//       ++$tempNum;
//     }
//   }
//   $fieldNum = count($userDetail);
//   $userDegree = intval(($fieldNum - $tempNum) / $fieldNum  * 100).'%';
//   return $userDegree;
// }

function loginMove($user)
{
    // 取得未登录前的用户，以便获取未登录前的状态
    $tempUserId = session('userId');
    // 将未登录前的购物车信息加入到用户
    $shoppingCart = M('goods_shopping_cart');
    $temp_cart_goods = $shoppingCart->where(array('user_id' => $tempUserId, 'is_temp' => 1))->field('`id`, `goods_id`, `goods_number`')->select();
    $real_cart_goods = $shoppingCart->where(array('user_id' => $user['id']))->select();
    $real_goods_id = array();
    foreach ($real_cart_goods as $key => $value) {
        array_push($real_goods_id, $value['goods_id']);
    }
    foreach ($temp_cart_goods as $key => $value) {
        if (in_array($value['goods_id'], $real_goods_id)) {
            $shoppingCart->where(array('user_id' => $user['id'], 'goods_id' => $value['goods_id']))->setInc('goods_number', $value['goods_number']);
        } else {
            $shoppingCart->where(array('id' => $value['id']))->data(array('user_id' => $user['id'], 'is_temp' => '0'))->save();
        }
    }
    $shoppingCart->where(array('user_id' => $tempUserId, 'is_temp' => 1))->delete();

    // 将未登录前的浏览记录更新到用户
    $browsingHistory = M('browing_history');
    $temp_history = $browsingHistory->where(array('user_id' => $tempUserId, 'is_temp' => '1'))->select();
    foreach ($temp_history as $key => $value) {
        if ($browsingHistory->where(array('user_id' => $user['id'], 'goods_id' => $value['goods_id']))->count() == 0) {
            $browsingHistory->where(array('user_id' => $tempUserId, 'goods_id' => $value['goods_id']))->data(array('user_id' => $user['id'], 'is_temp' => '0'))->save();
        }
    }
    $browsingHistory->where(array('user_id' => $tempUserId, 'is_temp' => '1'))->delete();

    session('userId', $user['id']);
    session('userNickname', $user['nickname']);
    session('userInfo', $user);
    session('is_temp', 0);
}

/**
 * [freight 获取运费模板]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)       2016          Xcrozz (http://www.xcrozz.com)
 * @param     integer $agentId [代理商id]
 * @param     string $province [如果为空,则调用默认模板]
 * @param     string $city [市]
 * @param     string $county [区]
 * @return    [type]                  [description]
 */
// function freight($agentId = 1, $province = "", $city = "", $county= "") {
//     $goodsFreightTemplate = M('goods_freight_template');
//     if( !empty($province) ) {
//         $freightWhere = array(
//             'agent_id'  => $agentId,
//             'type'      => '快递', 
//             'province'  => $province, 
//             'city'      => $city, 
//             'zone'      => $county
//         );
//         $freightTemplete = $goodsFreightTemplate->where($freightWhere)->find();
//     }

//     if (empty($freightTemplete)) {
//         $freightTemplete = $goodsFreightTemplate->where(array('agent_id'=> $agentId, 'type'=>'快递', 'province'=>'', 'city'=>'', 'zone'=>''))->find();
//     }
//     return $freightTemplete;
// }
function freight($agentId = 1, $type = '', $province = '', $city = "", $county = "")
{
    $goodsFreightTemplate = M('goods_freight_template');

    if (!empty($type)) $freightWhere['type'] = $type;
    if (!empty($province)) {
        $freightWhere['agent_id'] = $agentId;
        $freightWhere['province'] = $province;
        $freightWhere['city'] = $city;
        $freightWhere['zone'] = $county;
        $freightTemplete = $goodsFreightTemplate->where($freightWhere)->select();
    }
    if (empty($freightTemplete)) {
        $freightWhere['agent_id'] = $agentId;
        $freightWhere['province'] = '';
        $freightWhere['city'] = '';
        $freightWhere['zone'] = '';
        $freightTemplete = $goodsFreightTemplate->where(array('agent_id' => $agentId, 'province' => '', 'city' => '', 'zone' => ''))->select();
    }
    return $freightTemplete;
}


/**
 * [getAgentName 获取平台名称
 * @author xu <565657400@qq.com>
 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
 * @return    [type]        [description]
 */
function getAgentName($id)
{
    $agent = M('agent');
    $name = $agent->where(array('id' => $id))->getField('agent_name');
    if (empty($name) || $id == '1') $name = '总平台';
    return $name;
}

/**
 * [getExpressName 获取物流名称]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c) 2016          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $id [description]
 * @return    [type]            [description]
 */
function getExpressName($id)
{
    $express = M('express');
    $name = $express->where(array('id' => $id))->getField('name');
    return $name;
}

/**
 * [getAgentName 获取平台名称
 * @author xu <565657400@qq.com>
 * @copyright Copyright (c)           2016 Xcrozz (http://www.xcrozz.com)
 * @return    [type]        [description]
 */
function getdetection($id)
{
    $detection = M('detection_items');
    $name = $detection->where(array('id' => $id))->getField('detection_item_name');
    return $name;
}

/**
 * [isMobile 是否手机访问]
 * @author StanleyYuen <350204080@qq.com>
 */
function isMobile()
{
    if (C('SHUT_MOBILE')) {
        return false;
    }

    //通过域名进行判断
    $serverName = $_SERVER['SERVER_NAME'];
    // if(strpos($serverName, 'm.vpai321.com') !== false) {
    //     return true;
    // }

    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }

    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }

    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 * [deleteCacheFile 删除缓存文件]
 * @author StanleyYuen <350204080@qq.com>
 */
function deleteCacheFile($template_name = "")
{
    $pc_temp_file = "./Static/Temp/PcGoodsTemp/{$template_name}.html";
    $mobile_temp_file = "./Static/Temp/MobileGoodsTemp/{$template_name}.html";
    $pc_sign = $mobile_sign = false;

    if (file_exists($pc_temp_file)) {
        $pc_sign = unlink($pc_temp_file);
    } else {
        $pc_sign = true;
    }

    if (file_exists($mobile_temp_file)) {
        $mobile_sign = unlink($mobile_temp_file);
    } else {
        $mobile_sign = true;
    }
    return $pc_sign && $mobile_sign;
}

// /**
//  * [redis 返回一个redis对象]
//  * @author StanleyYuen <350204080@qq.com>
//  */
// function redis() {
//     $redis = new \Redis();
//     $redis->connect(C('REDIS_ADDRESS'), C('REDIS_PORT'));
//     // $redis->auth(C('REDIS_PASSWORD'));
//     return $redis;
// }

if (!function_exists('statusCode')) {
    /**
     * [statusCode 接口返回结构]
     * @author Fu <418382595@qq.com>
     * @copyright Copyright (c)      2015          Xcrozz (http://www.xcrozz.com)
     * @license   [license]
     * @version   [version]
     * @param     array $data [数据,默认为只有sessionId]
     * @param     integer $status [状态码]
     * @param     string $message [消息内容]
     * @return    [type]                 [description]
     */
    function statusCode($data = array(), $status = 200000, $message = "")
    {
        if (empty($message)) {
            $message = M('errcode')->where(array('errCode' => $status))->getField('memo');
        }
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $data[$key] = '';
            }
        }
        $array = array(
            'status' => (string)$status,
            'message' => $message,
            'webSite' => C('webSite'),
            'data' => array(
                'session_id' => session_id(),
            )
        );

        if (NEED_PAGE && NEED_PAGE != 'NEED_PAGE') {
            $array['data']['page_limit'] = PAGE_LIMIT;
            $array['data']['page'] = I('request.page', 1, 'int');
        }
        $array['data'] = array_merge($array['data'], $data);
        return json_encode($array);
    }
}

/**
 * [saveUserLog 记录用户log信息]
 * @author Fu <418382595@qq.com>
 * @copyright Copyright (c)    2015          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $event [事件]
 * @return    [type]               [description]
 */
function saveUserLog($event, $remark = '')
{
    $ip = getIP();
    $userId = session('userId');
    $data = array(
        'user_id' => $userId,
        'ip' => $ip,
        'event' => $event,
        'remark' => $remark,
        'add_time' => time()
    );

    if (M('user_log')->add($data)) {
        return true;
    } else {
        return false;
    }
}

/**
 * [getIP 得到ip]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
 * @return    [type]        [description]
 */
function getIP()
{
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (!empty($_SERVER["REMOTE_ADDR"])) {
        $cip = $_SERVER["REMOTE_ADDR"];
    } else {
        $cip = '';
    }
    preg_match("/[\d\.]{7,15}/", $cip, $cips);
    $cip = isset($cips[0]) ? $cips[0] : 'unknown';
    unset($cips);

    return $cip;
}


/**
 * @param $expTitle 订单文件名称
 * @param $expCellName 订单标题名称
 * @param $expTableData 订单数据内容
 */
function exportExcel($expTitle, $expCellName, $expTableData)
{
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $fileName = $expTitle . date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    vendor("PHPExcel.PHPExcel");
    set_time_limit(0);
    $cacheMethod = \PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;
    \PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
    $objPHPExcel = new \PHPExcel();
    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

    //$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
    $objActSheet = $objPHPExcel->getActiveSheet();
    for ($i = 0; $i < $cellNum; $i++) {
        $objActSheet->setCellValue($cellName[$i] . '1', $expCellName[$i][1]);
    }

    for ($i = 0; $i < $dataNum; $i++) {
        for ($j = 0; $j < $cellNum; $j++) {
            // $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), $expTableData[$i][$expCellName[$j][0]]);
            $objActSheet->setCellValueExplicit($cellName[$j] . ($i + 2), $expTableData[$i][$expCellName[$j][0]], PHPExcel_Cell_DataType::TYPE_STRING);
        }
    }

    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

/**
 * [getArticleBySign 根据标识获取文章]
 * @author StanleyYuen <350204080@qq.com>
 * @copyright Copyright (c)           2015 Xcrozz (http://www.xcrozz.com)
 * @return    [type]        [description]
 */
function getArticleBySign($sign, $agentId = 1)
{
    $articleModel = M('article');
    $article_id = $articleModel->where(array('agent_id' => $agentId, 'art_sign' => $sign))->getField('id');
    return $article_id;
}

if (!function_exists('putFileFromUrlContent')) {
    /**
     * [putFileFromUrlContent 从url抓取信息保存到本地]
     * @author Fu <418382595@qq.com>
     * @copyright Copyright (c)       2015          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $url      [description]
     * @param     [type]        $saveName [description]
     * @param     [type]        $path     [description]
     * @return    [type]                  [description]
     */
    function putFileFromUrlContent($url, $filename)
    {
        // 设置运行时间为无限制
        set_time_limit(0);

        $url = trim($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 运行cURL，请求网页
        $file = curl_exec($curl);

        // 关闭URL请求
        curl_close($curl);
        // 将文件写入获得的数据

        $write = @fopen($filename, "w");
        if ($write == false) {
            return false;
        }
        if (fwrite($write, $file) == false) {
            return false;
        }
        if (fclose($write) == false) {
            return false;
        }
    }
}

if (!function_exists('arrayRecursive')) {
    /**
     *
     *  使用特定function对数组中所有元素做处理
     * @param  string &$array 要处理的字符串
     * @param  string $function 要执行的函数
     * @return boolean $apply_to_keys_also     是否也应用到key上
     * @access public
     *
     */
    function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        $recursive_counter--;
    }
}

if (!function_exists('url_encode')) {
    /**
     * [url_encode url编码]
     * @param  [type] $str [字符串或者数组]
     * @return [type]      [description]
     */
    function url_encode($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[urlencode($key)] = url_encode($value);
            }
        } else {
            $str = urlencode($str);
        }
        return $str;
    }
}
if (!function_exists('curlGet')) {
    /**
     * [curlGet 使用curl方式get数据]
     * @param  [type] $url [地址]
     * @return [type]      [description]
     */
    function curlGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // https
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //输出内容为字符串
        $result = curl_exec($ch);
        return $result;
    }
}

if (!function_exists('curlPost')) {
    /**
     * [curlPost 使用curl方式post无参数数据]
     * @param  [type] $url  [地址]
     * @param  [type] $data [post的数据]
     * @return [type]       [description]
     */
    function curlPost($url, $data)
    {
        $ch = curl_init();
        $header[] = "Content-Type: text/xml; charset=utf-8";
        //设置URL,POST方式提交以及POST数据
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // https
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //输出内容为字符串
        $result = curl_exec($ch);
        return $result;
    }

    function curlPost1($url, $data)
    {
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        return $tmpInfo;
    }
}

if (!function_exists('objectToArray')) {
    /**
     * [objectToArray 对象转换数组]
     * @param  [type] $obj [对象]
     * @return [type]      [数组]
     */
    function objectToArray($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val)) || is_object($val) ? objectToArray($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
}


/**
 * [encrypt 可逆加密算法]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)   2015          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $data [description]
 * @param     [type]        $key  [description]
 * @return    [type]              [description]
 */
function _encrypt($data, $key = '888')
{
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

/**
 * [Decrypt 可逆解密算法]
 * @author kofu <418382595@qq.com>
 * @copyright Copyright (c)   2015          Xcrozz (http://www.xcrozz.com)
 * @param     [type]        $data [description]
 * @param     [type]        $key  [description]
 */
function _decrypt($data, $key = '888')
{
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}

function build_url_parameter()
{
    $parameters = !empty($_GET) ? $_GET : array();
    if (!empty($parameters)) {
        $url = '/' . MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME . '/p/zz/';
        foreach ($parameters as $key => $value) {
            if ($value != '' && $key != 'p') {
                $value = urldecode($value);
                $url .= $key . '/' . $value . '/';
            }
        }
    }
    return rtrim($url, '/');
}

if (!function_exists('determineBrowser')) {
    /**
     * [determineBrowser 获得访问者浏览器]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    function determineBrowser()
    {
        $ua = addslashes($_SERVER['HTTP_USER_AGENT']);
        $browseragent = "";
        $browserversion = "";
        if (preg_match("/MicroMessenger\/(\d+\.\d+\.\d+\.\d+)/", $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "MicroMessenger";
        } elseif (preg_match("/MQQBrowser\/(\d+\.\d+\.\d+)/", $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "MQQBrowser";
        } elseif (preg_match("/UCBrowser\/(\d+\.\d+\.\d+\.\d+)/", $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "UCBrowser";
        } elseif (preg_match('/msie (\d+\.\d+)/i', $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "Internet Explorer";
        } elseif (preg_match('/opera(\/| )(\d+(\.\d+)?)(.+?(version\/(\d+(\.\d+)?)))?/i', $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "Opera";
        } elseif (preg_match('/firefox\/(\d+\.\d+)/i', $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "Firefox";
        } elseif (preg_match('/chrome\/(\d+\.\d+\.\d+\.\d+)/i', $ua, $version)) {
            $browserversion = $version[1];
            $browseragent = "Chrome";
        } elseif (preg_match('/(\d+\.\d)?(?:\.\d)?\s+safari\/?(\d+\.\d+)?/i', $ua, $version)) {
            $browseragent = "Safari";
            $browserversion = "";
        } else {
            $browserversion = "";
            $browseragent = "Other";
        }
        return $browseragent . "(" . $browserversion . ")";
    }
}

if (!function_exists('determineOs')) {
    /**
     * [determineOs 获得访客操作系统]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    function determineOs()
    {
        $ua = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($ua, 'MicroMessenger') != false || strpos($ua, 'Windows Phone') != false || strpos($ua, 'Mobile') != false) {
            if (preg_match("/(?<=Android )[\d\.]{1,}/", $ua, $version)) {
                $os = "Android";
                // $browserversion=$version[0];
            } elseif (preg_match("/(?<=CPU iPhone OS )[\d\_]{1,}/", $ua, $version)) {
                $os = "iPhone";
                // $browserversion=str_replace('_', '.', $version[0]);
            } elseif (preg_match("/(?<=CPU OS )[\d\_]{1,}/", $ua, $version)) {
                $os = "iPad";
                // $browserversion=str_replace('_', '.', $version[0]);
            }
        } else {
            if (preg_match('/win/i', $ua)) {
                $os = 'Windows';
            } else if (preg_match('/mac/i', $ua)) {
                $os = 'MAC';
            } else if (preg_match('/linux/i', $ua)) {
                $os = 'Linux';
            } else if (preg_match('/unix/i', $ua)) {
                $os = 'Unix';
            } else if (preg_match('/bsd/i', $ua)) {
                $os = 'BSD';
            } else {
                $os = 'Other';
            }
        }
        return $os;
    }
}

if (!function_exists('determineLanguage')) {
    /**
     * [determineLanguage 获得访问者浏览器语言]
     * @author kofu <418382595@qq.com>
     * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
     * @return    [type]        [description]
     */
    function determineLanguage()
    {
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $lang = substr($lang, 0, 5);
        if (preg_match('/zh-cn/i', $lang)) {
            $lang = '简体中文';
        } else if (preg_match('/zh/i', $lang)) {
            $lang = '繁体中文';
        } else {
            $lang = 'English';
        }
        return $lang;
    }
}

if (!function_exists('strCut')) {
    function strCut($str, $length)
    {
        $str = trim($str);
        $string = "";
        if (strlen($str) > $length) {
            for ($i = 0; $i < $length; $i++) {
                if (ord($str) > 127) {
                    $string .= $str[$i] . $str[$i + 1] . $str[$i + 2];
                    $i = $i + 2;
                } else {
                    $string .= $str[$i];
                }
            }
            $string .= "...";
            return $string;
        }
        return $str;
    }
}

if (!function_exists('createToken')) {
    function createToken($length = 16)
    {
        $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }

        return $string;
    }
}


if (!function_exists('recursiveCategory')) {
    function recursiveCategory($pid, $list = [])
    {
        static $categoryList;
        if (!empty($list)) {
            $categoryList = $list;
        }

        $childList = array();
        foreach ($categoryList as $key => $value) {
            if ($value['root_id'] == $pid) {
                $childList[] = $value;
                unset($categoryList[$key]);
            }
        }

        if (empty($childList)) {
            return false;
        } else {
            $result = array();
            foreach ($childList as $cvalue) {
                $tempResult = recursiveCategory($cvalue['id']);
                if (!empty($tempResult)) {
                    foreach ($tempResult as &$value) {
                        $value['path'] = $pid . '-' . $value['root_id'];
                    }
                    $cvalue['childCategory'] = $tempResult;
                }
                $result[] = $cvalue;
            }

            return $result;
        }
    }
}

if (!function_exists('getCategoryList')) {


    function getCategoryProductList()
    {
        $categoryModel = M('category');


        $where['root_id'] = '0';
        $where['module_id'] = '2';

        $cateList = $categoryModel->where($where)->field('id,cat_name,tree_id,cat_path,root_id,depth')->select();
        return $cateList ? $cateList : [];
    }
}


if (!function_exists('getCategoryName')) {
    function getCategoryName($catId)
    {
        $categoryModel = M('category');
        return $categoryModel->where(['id' => $catId])->getField('cat_name');
    }
}

if (!function_exists('getGoodsCategoryName')) {
    /**
     * [getCategoryName 获得商品分类]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)    2017          Xcrozz (http://www.xcrozz.com)
     * @param     [type]        $catId [description]
     * @return    [type]               [description]
     */
    function getGoodsCategoryName($catId)
    {
        $categoryModel = M('goods_category');
        return $categoryModel->where(['id' => $catId])->getField('category_name');
    }
}

if (!function_exists('getCategory')) {

    function getCategory($moduleId = 0, $rootId = '')
    {


        if ($moduleId == 0) {
            $where['module_id'] = ['LT', 6];
        } else {
            switch (ACTION_NAME) {
                case 'Product':
                    $where['module_id'] = $moduleId;
                    break;
                case 'article':
                    $where['module_id'] = ['IN', '1,3,4,5'];
                    break;
            }

        }
        if (empty($rootId)) {
            $where['root_id'] = 0;
        }
        $categoryModel = M('category');
        $select = $categoryModel->where($where)
            ->field('id,cat_name,images,root_id')
            ->select();
        return $select ? $select : [];
    }

}


if (!function_exists('getProduct')) {
    function getProduct($index = 'index', $cid = 0, $page = 1)
    {

        $product = Plugins\Storage\Product\Product::getInstance();
        $goodsList = $product::getProductList($index,$cid);
        return $goodsList ? $goodsList : [];

    }
}


if (!function_exists('getProductCategory')) {
    function getProductCategory($index = 'index', $cid = 0)
    {
        $product = Plugins\Storage\Product\Product::getInstance();
        $productCategory = $product::getProductCategory($index,$cid);
        return $productCategory ? $productCategory : [];

    }
}

if (!function_exists('getInformation')) {
    function getInformation($index = 'index', $cid = 0)
    {
        $articleModel = M('article');

        if ($index == 'index') {
            $where = [];
            if($cid != 0){
                $where['tree_id'] = $cid;
            }
            $list = $articleModel->order('is_top DESC, add_time DESC ')
                ->field('id,tree_id,cat_id,image,summary,title,hits,add_time')
                ->where($where)
                ->limit(6)
                ->select();
        }
        return $list ? $list : [];
    }
}


if (!function_exists('getArticle')) {

    function getArticle($sign, $catId)
    {
        $articleStd = Plugins\Storage\Article\Article::getInstance();
        if($sign == 'list'){

            $list = $articleStd::getArticleList($catId);
        }else{
            $list = $articleStd::getArticleList();
        }
        return $list;
    }

}

if (!function_exists('getSwiper')) {
    //$group_id 1 web端 2 wap端
     function getSwiper($index = 'index', $group_id = 2)
    {
        $adModel = M('ad');
        $imageModel = M('goods_images');

        if ($index == 'index') {
            $data = $adModel->order(' add_time DESC ')->where(['group_id'=>$group_id])
                ->field('id,image,url,content')
                ->select();
            $counting = $adModel->where(['group_id'=>$group_id])->count();
            $list['imgCount'] = $counting ? $counting : 0;
            $list['list'] = $data;
        }elseif($index == 'images'){
            $list = $imageModel->where(['goods_id'=>$group_id])->select();
            $counting = $imageModel->where(['goods_id'=>$group_id])->count();
            $list['imgCount'] = $counting ? $counting : 0;
        }elseif($index = 'video'){
            $list = $adModel->where(['group_id'=>$group_id])->field('`content`')->find();
            $list = htmlspecialchars_decode($list['content']);
        }
        return $list ? $list : [];
    }

}

if (!function_exists('getAbout')) {
    /**
     * [getAbout 获得单页内容]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)    2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $index [description]
     * @return    [type]               [description]
     */
     function getAbout($index = 'about',$tree_id = '61',$cat_id = '62')
    {
        if($index == 'about'){
            //获得所有单页模型
            $aboutModel = M('about');
            $list = $aboutModel->where(['tree_id'=>$tree_id,'cat_id'=>$cat_id])->find();
            if($list)
                $list['content'] = htmlspecialchars_decode($list['content']);
        }
        return $list ? $list : [];
    }

}

if( !function_exists('getAdGroup')){


    /**
     * [getAdGroup 得到分组广告信息]
     * @author StanleyYuen <[350204080@qq.com]>
     */
    function getAdGroup($sign, $isJson=false) {
        $agentId = I('get.agent_id', 1, 'int');
        $adGroupInfo = M('ad_group')->where(array('group_sign'=>$sign))->find();
        $adBox = M('ad_box')->where(array('group_id'=>$adGroupInfo['id']))->field('`width`,`height`,`id`')->select();

        $adData = array();
        $now = time();
        foreach ($adBox as $value) {
          $tmpAd = M('ad')->field('url, image, target, ad_name, content')
                              ->where(array('box_id'=>$value['id'], 'end_time'=>array('gt' ,$now), 'agent_id'=>$agentId))
                              ->order('start_time DESC')
                              ->find();

          if (!empty($tmpAd)) {
            $tmpAd['width'] = $value['width'];
            $tmpAd['height'] = $value['height'];
            $adData[] = $tmpAd;
          }

        }

        if ($isJson) {
          exit(json_encode($adData));
        } else {
          return $adData;
        }
    }
}

if( !function_exists('getGoods')){
    /**
     * [getGoods 获得商品]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c)          2017          Xcrozz (http://www.xcrozz.com)
     * @param     string        $sign        [description]
     * @param     [type]        $category_id [description]
     * @return    [type]                     [description]
     */
    function getGoods($sign = 'index',$category_id){
        $goods = Plugins\Storage\Goods\Goods::getInstance();
        $parameters = [
            'sign'        => $sign,
            'category_id' => $category_id
        ];
        $goodsList = $goods::getGoodsList($parameters);
        return $goodsList ? $goodsList : [];
    }
}

if(!function_exists(('getGoodsCategoy'))){
    function getGoodsCategory($pid){
        $categoryModel = M('goods_category');
        $list = $categoryModel->where(['pid'=>$pid])->order('sort DESC')->select();
        return $list ? $list : [];
    }
}