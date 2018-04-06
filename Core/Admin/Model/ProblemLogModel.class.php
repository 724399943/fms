<?php

namespace Admin\Model;

use Think\Model;

class ProblemLogModel extends Model
{
    private $problemModel;
    private $questionnaireModel;
    private $dbPrefix;

    public function __construct()
    {
        parent::__construct();
        $this->problemModel = M('problem');
        $this->questionnaireModel = M('questionnaire');
        $this->dbPrefix = C('DB_PREFIX');
    }

    public function getProblemLog($limit, $requestData, $adminId)
    {
        $where = ['`q`.`agent_id`'=> $adminId];
        if (!empty($requestData['questionnaire_id'])) {
            $where['`pl`.`questionnaire_id`'] = $requestData['questionnaire_id'];
        }
        if (!empty($requestData['title'])) {
            $where['`q`.`title`'] = ['LIKE', "%{$requestData['title']}%"];
        }
        if (!empty($requestData['user_id'])) {
            $where['`pl`.`user_id`'] = $requestData['user_id'];
        }
        $count = $this->alias('pl')
            ->join("LEFT JOIN `{$this->dbPrefix}questionnaire` AS `q` ON `pl`.`questionnaire_id` = `q`.`id`")
            ->where($where)
            ->count();
        $page = new \Think\Page($count, $limit);    //实例化分页类 传入总记录数和每页显示的记录数(15)
        $show = $page->show();    //分页显示输出
        $list = $this->alias('pl')
            ->join("LEFT JOIN `{$this->dbPrefix}questionnaire` AS `q` ON `pl`.`questionnaire_id` = `q`.`id`")
            ->where($where)
            ->field('`pl`.`id`, `pl`.`user_id`, `pl`.`add_time`, `q`.`title`, `q`.`type`')
            ->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->processData($list);
        $returnData = [
            'list' => $list,
            'show' => $show,
        ];
        return $returnData;
    }

    private function processData(&$list)
    {
        foreach ($list as $key => &$value) {
            $value['nickname'] = getNickName($value['user_id']);
            // $value['title'] = $this->questionnaireModel->where(['id'=>$value['questionnaire_id']])->getField('title');
        }
    }

    public function getProblemLogDetail($id)
    {
        $data = $this->where(['id' => $id])->find();
        $data['questionnaireData'] = $this->questionnaireModel->where(['id' => $data['questionnaire_id']])->field('`title`,`type`')->find();
        $data['problem'] = json_decode($data['problem'], true);
        $data['answers'] = json_decode($data['answers'], true);
        $data['result'] = json_decode($data['result'], true);
        $ids = implode(',', $data['problem']);
        $list = $this->problemModel->where(['id' => ['IN', $ids]])->order('sort ASC, id ASC')->select();
        foreach ($list AS $item => &$value) {
            foreach ($data['problem'] AS $datum => $ite) {
                if ($value['id'] == $ite) {
                    $value['cmp'] = $datum;
                }
            }
            $value['options'] = json_decode($value['options'], true);
        }
        
        $array_sort = function ($array, $row, $type) {
            $array_temp = array();
            foreach ($array as $v) {
                $array_temp[$v[$row]] = $v;
            }
            if ($type == 'asc') {
                ksort($array_temp);
            } elseif ($type = 'desc') {
                krsort($array_temp);
            } else {
                ksort($array_temp);
            }
            return $array_temp;
        };
        // $list = $array_sort($list, 'cmp', 'asc');
        
        $returnData = [
            'data' => $data,
            'list' => $list,
        ];
        return $returnData;
    }
}
