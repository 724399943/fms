<?php

namespace Admin\Controller;

use Think\Page;

class ModuleController extends BaseController
{

    /**
     * 模块 Model
     * @var \Model|\Think\Model
     */
    private $moduleModel;


    /**
     * 栏目 Model
     * @var \Model|\Think\Model
     */
    private $categoryModel;


    public function __construct()
    {
        parent::__construct();
        $this->moduleModel = M('module');
        $this->categoryModel = M('category');
        $firstCat = getFirstCat();
        $this->assign('firstCat', $firstCat);

    }

    /**
     * Setmodule  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function setModule()
    {

        $where = [
//            'root_id' => 0
        ];
        $dbPrefix = C('DB_PREFIX');
        $selectInfo = $this->categoryModel->alias(' AS ca ')
            ->join('' . $dbPrefix . 'module AS m ON ca.module_id = m.id ', 'LEFT')
            ->where($where)
            ->field('ca.id,ca.modalias,ca.cat_name,ca.tree_id,ca.root_id,ca.depth,ca.orders,m.mod_name,m.color,ca.status,m.id AS module_id,ca.dir_name')
            ->select();

        $categoryInfo = recursiveCategory('0', $selectInfo);

//        dump($categoryInfo[0]['childCategory']);
        $result = [
            'list' => $categoryInfo
        ];
        $this->assign($result);
        $this->display();
    }


    /**
     * addCategory  [添加分类]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @modifu cdd <2042536829@qq.com>
     */
    public function addCategory()
    {
        if (IS_POST) {
            $postData = I('post.');

            if (empty($postData['cat_name']) || empty($postData['dir_name'])) {
                $this->error('不能为空！');
            }

            $superior = $this->categoryModel->where(['id' => $postData['root_id']])->find();

            $aliasName = $this->moduleModel->where(['id' => $superior['module_id']])->getField('alias');

            if ($superior['tree_id'] != 0) {
                $treeId = $superior['tree_id'];
            } else {
                $treeId = $superior['id'];
            }
            $catId = $superior['cat_path'];

            $data = [
                'module_id' => $postData['mod_id'],
                'modalias' => $aliasName,
                'dir_name' => $postData['dir_name'],
                'cat_name' => $postData['cat_name'],
                'root_id' => $postData['root_id'],
                'depth' => $superior['depth'] + 1,
                'tree_id' => $treeId,
                'images' => $postData['imgs'],
                'cat_path' => $catId,
                'orders' => $postData['orders'],
//                'tags' => $postData['tags'],
//                'meta_keyword' => $postData['meta_keyword'],
//                'meta_description' => $postData['meta_description'],
                'tpl_detail' => $postData['tpl_detail'],
            ];
            if (!$postData['mod_id'] == 6) {
                $data['tags'] = $postData['tags'];
                $data['meta_keyword'] = $postData['meta_keyword'];
                $data['meta_description'] = $postData['meta_description'];
                $data['tpl_index'] = $postData['tpl_index'];
                $data['tpl_list'] = $postData['tpl_list'];
            }
            $i = $this->categoryModel->add($data);
            if ($i) {
                if($postData['mod_id'] == 6){
                    //单页模型添加到about表当中
                    $aboutData = [
                        'modalias' => $aliasName,
                        'tree_id' => $treeId,
                        'cat_id' => $i,
                        'title' => $postData['cat_name'],
                    ];
                    $result = M('about')->data($aboutData)->add();
                    ($result) ? $this->success('成功！', U('Module/setModule')) : $this->error('失败！');
                }else{
                    $this->success('成功！', U('Module/setModule'));
                }

            } else {
                $this->error('失败！');
            }

        } else {
            $pid = I('get.pid');
            $mod_id = I('get.mod_id');


            $module = $this->moduleModel->where(['id' => $mod_id])->field('mod_name,id')->find();

            $superiorId = $this->categoryModel->where(['id' => $pid])->field('tree_id,id')->find();
            if ($superiorId['tree_id'] != 0) {
                $supId = $superiorId['tree_id'];
            } else {
                $supId = $superiorId['id'];
            }

            $superior = $this->categoryModel->where(" ( tree_id = {$supId} OR  id = {$supId} ) AND module_id = {$mod_id}   ")
                ->field('id,cat_name,module_id,root_id,depth')
                ->select();

            $superior = recursiveCategory('0', $superior);

            $dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
            $result = [
                'name' => $module['mod_name'],
                'id' => $module['id'],
                'pid' => $pid,
                'list' => $superior,
                'dir' => $dir
            ];
            $this->assign($result);
            $this->display();
        }
    }


    /**
     * addListColumn  [添加一级列表栏目]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function addListColumn()
    {
        if (IS_POST) {
            $postData = I('post.');

            if (empty($postData['mod_id'])) {
                $this->error('不能为空！');
            } else {

                $moduleAliasName = $this->moduleModel->where(['id' => $postData['mod_id']])->getField('alias');

                $data = [
                    'module_id' => $postData['mod_id'],
                    'modalias' => $moduleAliasName,
                    'cat_name' => $postData['cat_name'],
                    'dir_name' => $postData['dir_name'],
                    'images' => $postData['imgs'],
                    'tags' => $postData['tags'],
                    'meta_keyword' => $postData['meta_keyword'],
                    'meta_description' => $postData['meta_description'],
                    'status' => $postData['status'],
                    'orders' => $postData['orders'],
                    'tpl_index' => $postData['tpl_index'],
                    'tpl_list' => $postData['tpl_list'],
                    'tpl_detail' => $postData['tpl_detail'],
                    'limit' => $postData['limit'],
                    'sort' => $postData['sort'],
                    'add_time' => time(),
                ];
                $mysql = M();
                $mysql->startTrans();
                $i = $this->categoryModel->add($data);
                if ($i) {
                    $this->categoryModel->where(['id' => $i])->save(['cat_path' => $i]);
                    $mysql->commit();
                    $this->success('成功！', U('Module/setModule'));
                } else {
                    $mysql->rollback();
                    $this->error('失败！');
                }

            }


        } else {
            $moduleList = $this->moduleModel->field('id,mod_name')->select();

            $dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
            $result = [
                'moduleList' => $moduleList,
                'dir' => $dir
            ];

            $this->assign($result);
            $this->display();
        }
    }


    /**
     * [setModule 设置模板类型]
     * @author cdd <2042536829@qq.com>
     * @copyright Copyright (c) 2017 Xcrozz (http://www.xcrozz.com)
     */
    public function setModuleType()
    {
        $moduleModel = M('module');
        if (IS_POST) {

            $postData = I('post.');
            $count = $moduleModel->count();

            $saveData = array();
            $is_end = true;
            for ($i = 0; $i < $count; $i++) {
                $data = array();
                foreach ($postData as $key => $value) {
                    $data[$key] = $value[$i];
                }
                $saveData = $moduleModel->create($data, 2);
                if ($moduleModel->data($saveData)->save() === false) {
                    $is_end = false;
                    break;
                }
            }
            (!$is_end) ? $this->error('更新模型失败') : $this->success('更新模型成功', U('Module/setModule'));
        } else {
            //获得模块类型数量
            $count = $moduleModel->count();
            $list = $moduleModel->select();
            $this->assign('count',$count);
            $this->assign('list', $list);
            $this->display();
        }
    }


    /**
     * addSingleModule  [添加单页模型]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     * @modify cdd <2042536829@qq.com>
     */
    public function addSingleModule()
    {
        if (IS_POST) {
            $postData = I('post.');

            if (empty($postData['cat_name']) || empty($postData['dir_name'])) {
                $this->error('不能为空！');
            } else {
                $moduleAliasName = $this->moduleModel->where(['id' => $postData['mod_id']])->getField('alias');
                $data = [
                    'module_id' => $postData['mod_id'],
                    'modalias' => $moduleAliasName,
                    'cat_name' => $postData['cat_name'],
                    'dir_name' => $postData['dir_name'],
                    'images' => $postData['imgs'],
//                    'tags' => $postData['tags'],
//                    'meta_keyword' => $postData['meta_keyword'],
//                    'meta_description' => $postData['meta_description'],
                    'status' => $postData['status'],
                    'orders' => $postData['orders'],
                    'link_type' => $postData['link_type'],
                    'out_url' => $postData['out_url'],
                    'tpl_detail' => $postData['tpl_detail'],
                    'add_time' => time(),
                ];
                $addID = $this->categoryModel->add($data);
                if( $addID ){
                    //添加到about表中
                    $aboutData = [
                    'modalias' => $moduleAliasName,
                    'cat_id' => $addID,
                    'tree_id' => $addID,
                    'title' => $postData['cat_name'],
                    ];
                    $result = M('about')->data( $aboutData )->add();
                    if( $result ){
                        $this->success('成功！', U('Module/setModule'));
                    }else{
                        $this->error('失败！');
                    }
                }
                // if ($this->categoryModel->add($data)) {
                //     //添加到aboutbia
                //     $this->success('成功！', U('Module/setModule'));
                // } else {
                //     $this->error('失败！');
                // }

            }
        } else {
            $dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));

            $this->assign('dir', $dir);
            $this->display();
        }
    }


    /**
     * addExternalModule  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function addExternalModule()
    {
        if (IS_POST) {
            $postData = I('post.');
            if (empty($postData['cat_name']) || empty($postData['dir_name'])) {
                $this->error('不能为空！');
            } else {
                $moduleAliasName = $this->moduleModel->where(['id' => $postData['mod_id']])->getField('alias');

                $data = [
                    'module_id' => $postData['mod_id'],
                    'modalias' => $moduleAliasName,
                    'cat_name' => $postData['cat_name'],
                    'dir_name' => $postData['dir_name'],
                    'images' => $postData['imgs'],
//                    'tags' => $postData['tags'],
//                    'meta_keyword' => $postData['meta_keyword'],
//                    'meta_description' => $postData['meta_description'],
                    'status' => $postData['status'],
                    'orders' => $postData['orders'],
                    'link_type' => $postData['link_type'],
                    'out_url' => $postData['out_url'],
                    'add_time' => time(),
                ];
                if ($this->categoryModel->add($data)) {
                    $this->success('成功！', U('Module/setModule'));
                } else {
                    $this->error('失败！');
                }
            }

        } else {
            $dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));

            $this->assign('dir', $dir);
            $this->display();
        }
    }

    /**
     * changeModuleStatus  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function changeModuleStatus()
    {
        $id = I('post.id');
        $status = I('post.status');

        if (!empty($id) && !empty($status)) {
            $i = $this->categoryModel->where(['id' => $id])->save(['status' => $status]);
            if ($i) {
                exit(json_encode(['success' => 1]));
            } else {
                exit(json_encode(['error' => 1]));
            }
        }
    }


    /**
     * editModule  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function editModule()
    {
        $id = I('get.id');
        if (IS_POST) {
            $postData = I('post.');
            if (empty($postData['id'])) {
                $this->error('参数丢失！');
            } else {
                unset($postData['up-pic']);
                if ($this->categoryModel->where(['id' => $postData['id']])->save($postData)) {
                    $this->success('成功！', U('Module/setModule'));
                } else {
                    $this->error('失败！');
                }
            }
        } else {
            $cateInfo = $this->categoryModel->where(['id' => $id])->find();
            $moduleInfo = $this->moduleModel->where(['id' => $cateInfo['module_id']])->field('id,mod_name')->find();

            $dir = recursionDirFiles(C('TEMPLET_PATH') . C('templet'));
            $result = [
                'cateInfo' => $cateInfo,
                'tags' => $cateInfo['tags'],
                'meta_keyword' => $cateInfo['meta_keyword'],
                'meta_description' => $cateInfo['meta_description'],
                'mod_name' => $moduleInfo['mod_name'],
                'dir' => $dir,
            ];
            $this->assign($result);
            $this->display();
        }
    }


    /**
     * delCategory  [description]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */
    public function delCategory()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数丢失！');
        }
        $cateCount = $this->categoryModel->where(['root_id' => $id])->count();
        if ($cateCount > 0) {
            $this->error('对不起，该栏目下有子栏目，请从子栏目删除。');
        } else {
            if ($this->categoryModel->where(['id' => $id])->delete()) {
                $this->success('成功！');
            } else {
                $this->error('失败！');
            }
        }

    }

    /**
     * uploadPhoto  [上传文件]
     * @copyright Copyright (c)
     * @author Wongzx <842687571@qq.com>
     */

    public function uploadPhoto()
    {
        // 图片保存路径
        fileUpload('Article/', function ($e) {
            echo json_encode(array('error' => '', 'src' => trim($e['filePath'], '.')));
        });
    }

}