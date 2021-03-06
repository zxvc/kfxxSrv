<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2017/12/14
 * Time: 17:45
 */


namespace App\Components;

use App\Models\LB;
use App\Models\LBAnswer;
use App\Models\LBQuestion;


class LBMannager
{
	//微信小程序使用，获取可用量表
	public static function getLBList()
	{
		$list = LB::where('status', '=', '1')->orderby('seq', 'desc')->get();
		return $list;
	}
	
	//管理后台使用，获取所有量表
	public static function lblist_all()
	{
		$list = Lb::orderBy('seq', 'desc')->orderBy('id', 'desc')->paginate(Utils::PAGE_SIZE);
		return $list;
	}
	
	//根据ID获取量表
	public static function getLBById($id)
	{
		$lb = LB::where('id', '=', $id)->first();
		
		return $lb;
	}
	//根据级别获取量表信息
//* 0:最简级别，只带量表基本信息
//* 1:普通级别，带录入人员信息
//* 2:高级级别,带录入人员信息和量表问题
	public static function getLBDetailByLevel($lb, $level)
	{
		if ($level >= 1) {
			$lb->doctor = DoctorManager::getDoctorById($lb->doctor_id);
		}
		if ($level >= 2) {
			$lb->questions = self::getQuestionsByLBId($lb->id);
			self::addShowNum($lb->id);
		}
		
		return $lb;
	}
	
	public static function setQuestions($questions)
	{
		foreach ($questions as $question) {
			if (array_key_exists('id', $question))
				$ques = self::getQuestionByQId($question['id']);
			else
				$ques = new LBQuestion();
			$ques = self::setQuestion($ques, $question);
			$ques->save();
		}
		return $questions;
		
	}
	
	//根据id获取问题
	public static function getQuestionsByLBId($id)
	{
		$questions = LBQuestion::where('lb_id', '=', $id)->get();
		return $questions;
	}
	
	public static function getQuestionByQId($id)
	{
		$question = LBQuestion::where('id', '=', $id)->first();
		return $question;
	}
	
	//设置量表
	public static function setLB($lb, $data)
	{
		if (array_key_exists('name', $data)) {
			$lb->name = array_get($data, 'name');
		}
		if (array_key_exists('type', $data)) {
			$lb->type = array_get($data, 'type');
		}
		if (array_key_exists('desc', $data)) {
			$lb->desc = array_get($data, 'desc');
		}
		if (array_key_exists('doctor_id', $data)) {
			$lb->doctor_id = array_get($data, 'doctor_id');
		}
		return $lb;
	}
	
	//展示次数增加
	public static function addShowNum($lb_id)
	{
		$lb = self::getLBById($lb_id);
		$lb->show_num = $lb->show_num + 1;
		$lb->save();
	}
	
	//设置问题
	public static function setQuestion($lb_question, $data)
	{
		if (array_key_exists('lb_id', $data)) {
			$lb_question->lb_id = array_get($data, 'lb_id');
		}
		if (array_key_exists('question', $data)) {
			$lb_question->question = array_get($data, 'question');
		}
		if (array_key_exists('type', $data)) {
			$lb_question->type = array_get($data, 'type');
		}
		if (array_key_exists('doctor_id', $data)) {
			$lb_question->doctor_id = array_get($data, 'doctor_id');
		}
		if (array_key_exists('answer', $data)) {
			$lb_question->answer = array_get($data, 'answer');
		}
		if (array_key_exists('seq', $data)) {
			$lb_question->seq = array_get($data, 'seq');
		}
		return $lb_question;
	}
	
	//答题
	public static function answerLB($data)
	{
		$ans = new LBAnswer();
		$ans->user_id = array_get($data, 'user_id');
		$ans->lb_id = array_get($data, 'lb_id');
		$results = array_get($data, 'result');
		$ans->result = $results;
		$ans->status = 0;
		
		
		$user = UserManager::getUserInfoById($data['user_id']);
		if ($user->gender) {
			$ans->gender = $user->gender;
		}
		if ($user->birthday) {
			$ans->birthday = $user->birthday;
		}
		if ($user->phonenum) {
			$ans->phonenum = $user->phonenum;
		}
		$bl = KFJHManager::getBLById($data['user_id']);
		if ($bl) {
			if ($bl->ss_time) {
				$ans->s_time = $bl->ss_time;
			}
			if ($bl->zz_doctor_id) {
				$ans->s_doctor = DoctorManager::getDoctorById($bl->zz_doctor_id)->name;
			}
		}
		$ans->save();
		return $ans;
	}
	
	//根据用户ID获取答题记录
	public static function getAnswersByID($u_id)
	{
		$ans = LBAnswer::where('user_id', '=', $u_id)->get();
		return $ans;
	}
	public static function getAnswersByAnsID($id)
	{
		$ans = LBAnswer::where('id', '=', $id)->first();
		return $ans;
	}
	
	public static function getAllAnswer(){
		$answers=LBAnswer::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();;
		return $answers;
	}
	public static function checkAnswer($ans,$data){
		$ans->score=array_get($data, 'score');
		$ans->doctor_id=array_get($data, 'doctor_id');
		$ans->status=1;
		$ans->save();
		return $ans;
	}
}