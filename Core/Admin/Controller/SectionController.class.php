<?php
namespace Admin\Controller;

use Think\Controller;
use Plugins\Question\Section;

class SectionController extends BaseController
{
	/**
     * [Uploadfile 上传视频]
     * @author xu <[565657400@qq.com]>
     */
    public function Uploadfile(){
    	$uploadPath = C('UPLOAD_PATH');
        $savePath = $uploadPath . 'videofile/';
	    if ( !file_exists($savePath) ) {
	        mkdir($savePath, 0700, true);
	    }

	    $upload            = new \Think\Upload();
	    $upload->maxSize   = 90145728 ;
	    $upload->exts      = array('mp4','rm','avi','mov','mpeg4','rmvb','mkv','mp3','wma','aac','wav','flac','ogg','ape' );
	    $upload->rootPath  = $savePath; 
	    $info              = $upload->upload();
	    if ( !$info ) {
           echo $upload->getError();
        }else{
           $keys = array_keys($info);
           $key  = $keys[0];
           $one  = $info[$key];
           $src  = $savePath . $one['savepath'] . $one['savename'];
           $one['src']  = trim($src,'.');
           echo json_encode($one);
        }
    }

	/**
	 * [questionList 关卡列表]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function questionList(){
    	$where = array();
    	$link_parameter = '';
        $name = I('name');
        $title = I('title');
        $section_id = I('section_id');
        $question_id = I('question_id');
        $type = I('type');
        $is_locking = I('is_locking', -1);
        if ($is_locking != '-1') {
            $where['is_locking'] = $is_locking;
            $link_parameter .= '/is_locking/' .$is_locking;
        }
        if (!empty($name)) {
            $where['name'] = array('LIKE', "%{$name}%");
            $link_parameter .= '/name/' .$name;
        }
        if (!empty($title)) {
            $where['title'] = array('LIKE', "%{$title}%");
            $link_parameter .= '/title/' .$title;
        }
        if (!empty($type)) {
            $where['type'] = $type;
            $link_parameter .= '/type/' .$type;
        }
        if (!empty($section_id)) {
            $where['section_id'] = $section_id;
            $link_parameter .= '/section_id/' .$section_id;
			$questionData = M('questionnaire')->where(array('section_id'=>$section_id))->order('pre_id')->select();
        } else {
        	$questionData = array();
        }
        if (!empty($question_id)) {
            $where['id'] = $question_id;
            $link_parameter .= '/question_id/' .$question_id;
        }
		$QuestionViewModel = D('Questionnaire');
		$count = $QuestionViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/Section/questionList/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出
		$list = $QuestionViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->select();	//分页查询
		$return = array();
        $return['is_locking'] = $is_locking;
        $return['name'] = $name;
        $return['title'] = $title;
        $return['section_id'] = $section_id;
        $return['question_id'] = $question_id;

		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);
		$this->assign('list', $list);
		$this->assign('questionData', $questionData);
		$this->assign('sectionArr', $sectionArr);
		$this->display();
	}

	public function addQuestion() {
		if(IS_POST){
			$Question = D('Question');
			$data = $Question->create();
			$data['add_time'] = time();
			$data['content'] = htmlspecialchars_decode($data['content']);
			if($Question->add($data)){
				$this->success('新建成功', U('Section/questionList')); 
			}else{
				$this->error('新建失败', U('Section/questionList')); 
			}
		}else{
			$sectionData = M('section')->select();
			$this->assign('sectionData', $sectionData);
			$this->display(); 
		}
	}

	public function editQuestion() {
		$Question = D('Question');
		if(IS_POST){
			$data = $Question->create();
			if ( !empty( $data ) ) {
				$data['problem_number'] = M('problem')->where(array('question_id'=>$data['id']))->count();
			}
			$data['content'] = htmlspecialchars_decode($data['content']);
			if($Question->save($data) !== false) {
				$this->success('编辑成功', U('Section/questionList'));
			} else {
				$this->error('编辑失败', U('Section/questionList'));
			}
		} else {
			$id = I('id');
			$vo = $Question->find($id);
			$vo['content'] = htmlspecialchars($vo['content']);
			$sectionData = M('section')->select();
			$questionData = M('questionnaire')->where(array('section_id'=>$vo['section_id']))->select();
			$userTypeData = M('question_type')->where(array('section_id'=>$vo['section_id']))->select();
			// $data = M('questionnaire')->where(array('section_id'=>$vo['section_id']))->select();
			$this->assign('sectionData', $sectionData);
			$this->assign('questionData', $questionData);
			$this->assign('userTypeData', $userTypeData);
			$this->assign('vo', $vo);
			$this->display();
		}
	}

	public function deleteQuestion() {
		$Question = D('Question');
		$id = I('id'); 
		if($Question->delete($id)){
			$this->success('删除成功', U('Section/questionList'));
		}else{
			$this->error('删除失败', U('Section/questionList'));
		}
	}

	/**
	 * [problemList 问题列表]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
    public function problemList(){
    	$where = array();
    	$link_parameter = '';
        $question = I('question');
        $question_id = I('question_id');
        $section_id = I('section_id');
        $id = I('id');
        if (!empty($question)) {
            $where['question'] = array('LIKE', "%{$question}%");
            $link_parameter .= '/question/' .$question;
        }
        if (!empty($section_id)) {
            $where['section_id'] = $section_id;
            $link_parameter .= '/section_id/' .$section_id;
			$questionData = M('questionnaire')->where(array('section_id'=>$section_id))->select();
        } else {
        	$questionData = array();
        }
        if (!empty($question_id)) {
            $where['question_id'] = $question_id;
            $link_parameter .= '/question_id/' .$question_id;
        }
        if (!empty($id)) {
            $where['id'] = $id;
            $link_parameter .= '/id/' .$id;
        }
		$ProblemViewModel = D('ProblemView');
		$count = $ProblemViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/Section/problemList/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出
		$list = $ProblemViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->order('question_id DESC, id DESC')->select();	//分页查询
		// echo $ProblemViewModel->getlastsql();
		$return = array();
        $return['question'] = $question;
        $return['question_id'] = $question_id;
        $return['section_id'] = $section_id;
        $return['id'] = $id;

        $sectionData = M('section')->field('`id`, `section_name`')->select();
        $sectionArr = array_column($sectionData, 'section_name', 'id');
		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);	
		$this->assign('list', $list);
		$this->assign('sectionData', $sectionData);
		$this->assign('sectionArr', $sectionArr);
		$this->assign('questionData', $questionData);
		$this->display();
	}

	public function addProblem() {
		$Problem = D('Problem');
		if(IS_POST){
			$data = $Problem->create();
			if ( !empty( $data['question_id'] ) ) {
				$data['add_time'] = time();
				if(count($data['answers'])>1){
					$data['type'] = 1;
				}
				$data['options'] = json_encode($data['options']);
				$data['answers'] = json_encode($data['answers']);
			}else{
				$this->error('关卡不能为空', U('Section/addQuestion'));
			}
			if($Problem->add($data)){
				$questnionData['problem_number'] = $Problem->where(array('question_id'=>$data['question_id']))->count();
				$questnionData['mandatory_number'] = $Problem->where(array('question_id'=>$data['question_id'], 'must_load'=>1))->count();
				M('questionnaire')->where(array('id'=>$data['question_id']))->data($questnionData)->save();
				$this->success('新建成功', U('Section/problemList'));
			}else{
				$this->error('新建失败', U('Section/problemList'));
			}
		}else{
			$section_id = I('get.section_id');
			$question_id = I('get.question_id');
			if ( !empty( $section_id ) ) {
				$whereArr = array(
					'section_id' => $section_id,
				);
				$questionModel = M('questionnaire');
				$questionData = $questionModel->where($whereArr)->field('id, title')->select();
				$this->assign('questionData', $questionData);
			}
			$sectionModel = M('section');
			$sectionData = $sectionModel->select();
			$this->assign('sectionData', $sectionData);
			$this->display();
		}
	}

	public function editProblem() {
		$Problem = D('Problem');
		if(IS_POST){
			$data = $Problem->create();
			if ( !empty( $data ) ) {
				$data['options'] = $_POST['options'];
				$data['answers'] = $_POST['answers'];
				if(count($data['answers'])>1){
					$data['type'] =1;
				}
				$data['options'] = json_encode($data['options']);
				$data['answers'] = json_encode($data['answers']);
				$old_question_id = $_POST['old_question_id'];

			}
			if($Problem->save($data) !== false) {
				$questnionData['problem_number'] = $Problem->where(array('question_id'=>$data['question_id']))->count();
				$questnionData['mandatory_number'] = $Problem->where(array('question_id'=>$data['question_id'], 'must_load'=>1))->count();
				M('questionnaire')->where(array('id'=>$data['question_id']))->data($questnionData)->save();
				if ( $old_question_id != $data['question_id'] && !empty( $old_question_id ) ) {
					$oldQuestnionData['problem_number'] = $Problem->where(array('question_id'=>$old_question_id))->count();
					$oldQuestnionData['mandatory_number'] = $Problem->where(array('question_id'=>$old_question_id, 'must_load'=>1))->count();
					M('questionnaire')->where(array('id'=>$old_question_id))->data($oldQuestnionData)->save();
				}
				$this->success('编辑成功', U('Section/problemList')); 
			}else{
				$this->error('编辑失败', U('Section/problemList')); 
			}
		}else{
			$id = I('id'); 
			$dbPrefix = C('DB_PREFIX');
			$sectionModel = M('section');
			// $questionModel = M('questionnaire');
			$vo = $Problem->find($id);

			$sectionData = $sectionModel->select();
			// $questionData = $questionModel->where()->select();
			$sql = "SELECT * FROM `bt_question` WHERE `section_id` = (
						SELECT `section_id` FROM `bt_question` WHERE `id` = '{$vo['question_id']}'
					)";
			$questionData = $sectionModel->query($sql);
			$options = json_decode($vo['options'],true);
			$answers = json_decode($vo['answers'],true);
			// dump($options);
			// exit;
			// foreach ($options as $key => $value) {
			// 	$temp = array(
			// 		'options' => $value,
			// 	);
			// 	if ( in_array($key, $answers) ) {
			// 		$temp['answer'] = 1;
			// 	}
			// 	$options[$key] = $temp;
			// }
			$this->assign('vo', $vo);
			$this->assign('sectionData', $sectionData);
			$this->assign('questionData', $questionData);
			$this->assign('options', $options);
			$this->assign('answers', $answers);
			$this->display();
		}
	}

	public function getQuestion() {
		$questionModel = M('questionnaire');
		$section_id = I('post.section_id');
		if ( empty( $section_id ) ) {
			$this->error('参数丢失');
			echo statusCode('', 400016);
		}
		$whereArr = array(
			'section_id' => $section_id,
		);
		$questionData = $questionModel->where($whereArr)->field('`id`, `title`')->select();
		echo statusCode(array('questionData'=>$questionData));
	}

	public function deleteProblem() {
		$Problem = D('Problem');
		$id = I('id'); 
		if($Problem->delete($id)){
			$this->success('删除成功', U('Section/problemList'));
		}else{
			$this->error('删除失败', U('Section/problemList'));
		}
	}

	public function updateQuestion() {
		$questionModel = M('questionnaire');
		$problemModel = M('problem');
		$questionData = $questionModel->select();
		foreach ($questionData as $key => $value) {
			$saveData['problem_number'] = $problemModel->where(array('question_id'=>$value['id']))->count();
			$saveData['mandatory_number'] = $problemModel->where(array('question_id'=>$value['id'], 'must_load'=>1))->count();
			$questionModel->where(array('id'=>$value['id']))->data($saveData)->save();
		}
	}

	/**
	 * [problemAnswerStatistics 错题情况]
	 * @author NicFung <13502462404@qq.com>
	 * @copyright Copyright (c)           2017 Xcrozz (http://www.xcrozz.com)
	 * @return    [type]        [description]
	 */
	public function problemAnswerStatistics() {
		$model = M();
		$dbPrefix = C("DB_PREFIX");
		$return['section_id'] = $section_id = I('section_id');
		$return['question_id'] = $question_id = I('question_id');
		$return['problem_id'] = $problem_id = I('problem_id');
		$return['question'] = $question = I('question');
		$whereStr = "";
		if ( ! empty( $section_id ) && ! empty( $question_id ) ) {
			$sql0 = "SELECT `a`.`id`
					FROM `bt_problem` AS `a`
					LEFT JOIN `bt_question` AS `b` ON `a`.`question_id` = `b`.`id` AND `b`.`section_id` = '{$section_id}'
					WHERE `a`.`question_id` = '1'";
			$problemArr = $model->query($sql0);
			$problemIds = "";
			foreach ($problemArr as $key => $value) {
				$problemIds .= "'" . $value['id'] . "',";
			}
			if ( !empty( $problemIds ) ) {
				$problemIds = trim($problemIds, ',');
				$whereStr .= " AND `a`.`problem_id` IN ({$problemIds})";
			}
			$questionData = M('questionnaire')->where(array('section_id'=>$section_id))->select();
		} else {
			if ( ! empty( $section_id ) && empty( $question_id ) ) {
				$questionArr = M('questionnaire')->where(array('section_id'=>$section_id))->getField('id', true);
				// $questionIds = "'" . implode($questionArr, "','") . "'";
				// $tempSql = "SELECT * 
				// 			FROM `{$dbPrefix}problem`
				// 			WHERE `question_id` IN ({$questionIds})";
				// $questionData = M()->query($tempSql);
				$questionData = M('problem')->where(array('question_id'=>array('IN', $questionArr)))->getField('id', true);
				if ( !empty( $questionData ) ) {
					$problemIds = "'" . implode($questionData, "','") . "'";
					$whereStr .= " AND `a`.`problem_id` IN ({$problemIds})";
				} else {
					$whereStr .= " AND `a`.`problem_id` IN (0)";
				}
			} elseif ( ! empty( $question_id ) ) {
				$problemArr = M('problem_id')->where(array('question_id'=>$question_id))->getField('id', true);
				if ( !empty( $problemArr ) ) {
					$problemIds = "'" . implode($problemArr, "','") . "'";
					$whereStr .= " AND `a`.`problem_id` IN ({$problemIds})";
				} else {
					$whereStr .= " AND `a`.`problem_id` IN (0)";
				}
			}
		}
		if ( ! empty( $problem_id ) ) {
			$whereStr .= " AND `a`.`problem_id` LIKE '%{$problem_id}%'";
		}
		if ( ! empty( $question ) ) {
			$whereStr .= " AND `a`.`question` LIKE '%{$question}%'";
		}

		$countSql1 = "SELECT COUNT(DISTINCT(`problem_id`)) AS `count`
				FROM `{$dbPrefix}user_problem_log` AS `a`
				WHERE 1 {$whereStr}";
		$answersCount = $model->query($countSql1);// 答题数
		$page = new \Think\Page($answersCount['0']['count'], 15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/Section/problemAnswerStatistics/p/zz' . $link_parameter);
		$show = $page->show();	//分页显示输出

		if ( $answersCount['0']['count'] > 0 ) {
			$sql1 = "SELECT `a`.`problem_id`, count(`a`.`id`) AS `count`,
					`c`.`question`, `c`.`type`, `c`.`must_load`, 
					`b`.`section_id`, `b`.`title`
					FROM `{$dbPrefix}user_problem_log` AS `a`
					LEFT JOIN `{$dbPrefix}problem` AS `c` ON `a`.`problem_id` = `c`.`id`
					LEFT JOIN `{$dbPrefix}question` AS `b` ON `c`.`question_id` = `b`.`id`
					WHERE 1 {$whereStr}
					GROUP BY `problem_id`
					LIMIT {$page->firstRow}, {$page->listRows}";
			$answersArr = $model->query($sql1);// 答题数

			$newProblemIds = "";
			foreach ($answersArr as $key => $value) {
				$newProblemIds .= "'" . $value['problem_id'] . "',";
			}
			$newProblemIds = trim($newProblemIds, ',');
			$newWhereStr .= " AND `problem_id` IN ({$newProblemIds})";

			$sql2 = "SELECT `problem_id`, count(`id`) AS `incorrectly` 
					FROM `{$dbPrefix}user_problem_log`
					WHERE `right_key` != `answer` {$newWhereStr}
					GROUP BY `problem_id`";
			$incorrectlyArr = $model->query($sql2);// 错题数数组
			foreach ($answersArr as $key => $value) {
				$answersArr[$key]['incorrectly'] = 0; //错题数
				$answersArr[$key]['ratio'] = '100';
				foreach ($incorrectlyArr as $key1 => $value1) {
					if ( $value['problem_id'] == $value1['problem_id'] ) {
						$answersArr[$key]['incorrectly'] = $value1['incorrectly'];
						$answersArr[$key]['ratio'] = ( 1 - round($value1['incorrectly'] / $value['count'], 2) ) * 100;
					}
				}
			}
		}

        $sectionData = M('section')->field('`id`, `section_name`')->select();
        $sectionArr = array_column($sectionData, 'section_name', 'id');

		$this->assign('sectionArr', $sectionArr);
		$this->assign('sectionData', $sectionData);
		$this->assign('questionData', $questionData);
		$this->assign('return', $return);
		$this->assign('answersArr', $answersArr);
		$this->assign('show', $show);
		$this->display();
	}

	public function checkProblemAnswer() {
		$problem_id = I('request.problem_id');
		$optionsArr = array(); //答案汇总
		if ( empty($problem_id) ) {
			$this->error('关键参数丢失');
		}
		$userProblemLogModel = M('user_problem_log');
		$problemModel = M('problem');
		$whereArr = array(
			'problem_id' => $problem_id,
		);
		$problemInfo = $problemModel->where($whereArr)->find();
		$userProblemLogData = $userProblemLogModel->where($whereArr)->select();
		$total = count($userProblemLogData); //总回复次数

		foreach ($userProblemLogData as $key => $value) {
			$right_key = json_decode($value['right_key'], true);
			$answer = json_decode($value['answer'], true);
			if ( empty( $optionsArr ) ) {
				$optionsArr = json_decode($value['options'], true);
				foreach ($optionsArr as $key1 => $value1) {
					$tempArr = array(
						'value' => $value1,
						'chose' => 0
					);
					foreach ($right_key as $key2 => $value2) {
						if ( $value2 == $key1 ) {
							$tempArr['boolean'] = 1;
						} else {
							$tempArr['boolean'] = 0;
						}
					}
					$optionsArr[$key1] = $tempArr;
				}
			}

			foreach ($answer as $key => $value) {
				$optionsArr[$value]['chose'] += 1;
				$optionsArr[$value]['proportion'] = round($optionsArr[$value]['chose'] / $total, 2) * 100;
			}

			// if ( $value['answer'] != $value['right_key'] ) {
				// 题目更改情况考虑
				// $temp = json_decode($value['option'], true);
				// foreach ($temp as $key => $value) {
				// 	if ( ! in_array($value, $optionsArr) ) {
				// 		$optionsArr[] = $value;
				// 	}
				// }
			// }
		}

		$this->assign('problemInfo', $problemInfo);
		// $this->assign('userProblemLogData', $userProblemLogData);
		$this->assign('optionsArr', $optionsArr);
		$this->assign('total', $total); //回答人数
		$this->display();
	}

	public function userList() {
		$UserViewModel = D('UserView');
		$dbPrefix = C('DB_PREFIX');
    	$where = array();
    	$link_parameter = '';
        $section_id = I('section_id'); //版块id
        if (!empty($section_id)) {
            $whereData['section_id'] = $section_id;
            $link_parameter .= '/section_id/' .$section_id;
            $questionIdsArr = M("question")->where($whereData)->getField('id', true); //关卡列表
            // $questionCount = count($questionIdsArr);
            if ( ! empty( $questionIdsArr ) ) {
				$questionIdsStr = "'" . implode($questionIdsArr, "','") . "'";
				$whereStr = " AND `question_id` IN ({$questionIdsStr})";
				// 用户id,和答题数
				$sql = "SELECT `user_id`, COUNT(`id`) AS `count`, SUM(`star`) AS `sum`
						FROM `{$dbPrefix}user_question`
						WHERE `star` != '0' {$whereStr}
						GROUP BY `user_id`
						ORDER BY SUM(`star`) DESC";
				$variableData = M()->query($sql);
				
				$sectionClass = new Section();
				$nextSectionInfo = $sectionClass->findNextSection($section_id);
				$jiesuoData = M('user_section')->where(array('section_id'=>$nextSectionInfo['id']))->getField('user_id', true);
				// dump($sql);
				if ( ! empty( $variableData ) ) {
					// $userIdsArr = array_column($variableData, 'user_id');
					// $userRatio = array_column($variableData, 'count', 'user_id');
					foreach ($variableData as $key => $value) {
						$userIdsArr[] = $value['user_id'];
						$userRatio[$value['user_id']] = array(
							'sum' => $value['sum'],
							'count' => $value['count'],
						);
					}
					// $orderStr = implode("','", $userIdsArr);
					// $where[] = "FIND_IN_SET(id,'{$orderStr}')";
					// $orderStr = "field(id, '{$orderStr}')";
					// echo $orderStr;
				} else {
					$userIdsArr = array(0);
					$userRatio = array();
				}
				$where['id'] = array(
					'IN', 
					$userIdsArr,
				);
            } else {
				$userIdsArr = array(0);
				$userRatio = array();
				$where['id'] = array(
					'IN', 
					$userIdsArr,
				);
            }
        }
        // dump($where);
        $id = I('id');
        if (!empty($id)) {
        	if ( isset( $where['id'] ) ) {
	        	$where['id'] = array(
					$where['id'],
					array('LIKE', "%{$id}%"),
				);
        	}
            $link_parameter .= '/id/' .$id;
        }
        $pid = I('pid');
        if (!empty($pid)) {
            $where['pid'] = array('LIKE', "%{$pid}%");
            $link_parameter .= '/pid/' .$pid;
        	// $masterInfo = $UserViewModel->where(array('id'=>$pid))->find();
        }
        $username = I('username');
        if (!empty($username)) {
            $where['username'] = array('LIKE', "%{$username}%");
            $link_parameter .= '/username/' .$username;
        }
        $user_code = I('user_code');
        if (!empty($user_code)) {
            $where['user_code'] = array('LIKE', "%{$user_code}%");
            $link_parameter .= '/user_code/' .$user_code;
        }
        $store_id = I('store_id');
        if (!empty($store_id)) {
            // $where['store_id'] = array('LIKE', "%{$store_id}%");
            $link_parameter .= '/store_id/' .$store_id;
            $tempWhereData = array(
            	'dept_id' => $store_id
            );
            $userIdData = M('user_department_station')->where($tempWhereData)->getField('user_id', true);
            if ( !empty( $userIdData ) ) {
	            $where['id'] = array('IN', $userIdData);
            } else {
            	$where['id'] = array('eq', '0');
            }
        }

        $gang_id = I('gang_id');
		if ( !empty($gang_id) ) {
			$whereData = array(
				'gang_id' => $gang_id,
			);
			$userIds = M('user_gang')->where($whereData)->getField('user_id', true);
			if ( isset( $where['id'] ) ) {
				$where['id'] = array(
					$where['id'],
					array('IN', $userIds),
				);
			} else {
				$where['id'] = array('IN', $userIds);
			}
		}
        $fenduo_id = I('fenduo_id');
		if ( !empty($fenduo_id) ) {
			$whereData = array(
				'fenduo_id' => $fenduo_id,
			);
			$temp = M('user_fenduo')->where($whereData)->getField('user_id', true);
			if ( !empty( $userIds ) ) {
				$userIds = array_intersect($temp, $userIds);
			} else {
				$userIds = $temp;
			}
			if ( isset( $where['id'] ) ) {
				$where['id'] = array(
					$where['id'],
					array('IN', $userIds),
				);
			} else {
				$where['id'] = array('IN', $userIds);
			}
		}
		// dump($where);
		$count = $UserViewModel->where($where)->count();
		$page = new \Think\Page($count,15);	//实例化分页类 传入总记录数和每页显示的记录数(15)
		$page->setConfig('link', '/Admin/User/index/p/zz' . $link_parameter);
		$show = $page->show(); //分页显示输出
		$list = $UserViewModel->where($where)->limit($page->firstRow.','.$page->listRows)->order('id DESC')->select();//分页查询
		// $dbPrefix = C('DB_PREFIX');
		// $sql = "SELECT * 
		// 		FROM `{$dbPrefix}` AS `a`
		// 		LEFT JOIN  `` AS `b` ON `a`.`` = `b`.``
		// 		WHERE 
		// 		ORDER BY field(`id`, {$orderStr})
		// 		";
		// echo $UserViewModel->getLastSql();
		// $return = array();
        $return = I();

		$this->assign('show', $show);	//赋值分页输出
		$this->assign('return', $return);	
		$this->assign('jiesuoData', $jiesuoData);	
		$this->assign('list', $list);
		$this->assign('userRatio', $userRatio);
		$this->display();
	}
}
