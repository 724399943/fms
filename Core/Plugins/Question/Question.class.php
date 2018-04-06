<?php

namespace Plugins\Question;

class Question
{

    private $services = [];

    private $parameters = [];

    /**
     * questionStart  [开始问答]
     * @copyright Copyright (c)
     */
    private function createQuestionStart()
    {
        $questionnaireModel = M('questionnaire');
        $where = [
            'id' => $this->parameters['id'],
            'is_open' => '1',
        ];
        $findQuestionnaire = $questionnaireModel->where($where)
            ->field('id,title,logo,type,background_image')
            ->find();
        return $findQuestionnaire;
    }

    /**
     * createProblem  [得到问题]
     * @copyright Copyright (c)
     */
    private function createProblem()
    {
        $problemModel = M('problem');
        $dbPrefix = C('DB_PREFIX');
        $problemList = $problemModel
            ->where(['questionnaire_id' => $this->parameters['questionnaire_id']])
            ->order('step ASC, sort ASC')
            ->select();
        $sql = " SELECT step FROM {$dbPrefix}problem 
                WHERE questionnaire_id = {$this->parameters['questionnaire_id']}
                GROUP BY step";
        $step = M()->query($sql);
        $data = [];
        foreach ($problemList AS $item => $value) {
            foreach ($step AS $it => $val) {
                if ($val['step'] == $value['step']) {
                    $value['options'] = json_decode($value['options'], true);
                    $data[$it][] = $value;
                }
            }
        }
        return $data;
    }

    /**
     * createMakeProblemLogs  [description]
     * @copyright Copyright (c)
     */
    private function createMakeProblemLogs()
    {
        $problemLogModel = M('problem_log');
        $insertId = $problemLogModel->data($this->parameters)->add();
        return $insertId;
    }


    /**
     * getCreateFun  [description]
     * @param $name
     * @param array $parameters
     * @copyright Copyright (c)
     * @return mixed
     */
    public function getCreateFun($name, array $parameters)
    {
        $this->parameters = $parameters;
        if (!isset($this->services[$name])) {
            $method = 'create' . $name;
            $this->services[$name] = $this->$method();
        }
        return $this->services[$name];
    }

}