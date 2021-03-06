<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\AD;
use App\Models\XJ;
use App\Models\TWStep;
use App\Models\XJType;
use Qiniu\Auth;

class XJManager
{
	/*
	 * 根据id获取宣教信息
	 *
	 * By TerryQi
	 *
	 * 2017-11-27
	 *
	 */
	public static function getXJById($id)
	{
		$xj = XJ::where('id', '=', $id)->first();
		return $xj;
	}
	
	/*
	 * 根据宣教和level获取宣教信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-24
	 */
	public static function getXJInfoById($id, $level)
	{
		$xj = self::getXJById($id);
		if ($xj) {
			$xj = self::getXJInfoByLevel($xj, $level);
		}
		return $xj;
	}
	
	/*
	 * 根据条件获取宣教信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-04
	 *
	 * type为类型，可以组合传入
	 *
	 */
	public static function getXJListByCon($hpos_arr)
	{
		
		$xjs = XJ::orderby('id', 'desc')->where('status', '=', '1');
		if ($hpos_arr != [])
			for ($i = 0; $i < count($hpos_arr); $i++) {
				if ($i == 0) {
					$xjs = $xjs->where('hpos_ids', 'like', '%' . $hpos_arr[$i] . '%');
				} else {
					$xjs = $xjs->orwhere('hpos_ids', 'like', '%' . $hpos_arr[$i] . '%');
				}
			}
		$xjs = $xjs->orderby('created_at', 'desc')->paginate(Utils::PAGE_SIZE);
		return $xjs;
	}
	
	/*
	 * 获取宣教信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-20
	 */
	public static function getIndexXJs()
	{
		$xjs = XJ::orderby('id', 'desc')->paginate(Utils::PAGE_SIZE);
		return $xjs;
	}
	
	
	/*
	 * 获取全部生效宣教信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-14
	 *
	 * status，状态 0：失效 1：生效 all：全部
	 *
	 */
	public static function getAllXJs($status)
	{
		$xjs = XJ::orderby('id', 'desc');
		if ($status == "all") {
		
		} else {
			$xjs = $xjs->where('status', '=', $status);
		}
		return $xjs->get();
		return $xjs;
	}
	
	
	/*
	 * 获取宣教基本信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-06
	 *
	 * 根据level级别不同获取宣教样式
	 * 0:最简级别，只带宣教基本信息
	 * 1:基本级别，带录入人员信息
	 * 2:中级级别，待患处位置信息
	 * 3:高级级别，带录入医师信息、类型信息、图文步骤信息
	 *
	 */
	public static function getXJInfoByLevel($xj, $level)
	{
		if ($level >= 1) {
			$xj->doctor = DoctorManager::getDoctorById($xj->doctor_id);
		}
		if ($level >= 2) {
			$hpos_ids = $xj->hpos_ids;
			$hpos_id_arr = explode(",", $hpos_ids);
			$hposs_collection = collect(['data' => collect()]);
			foreach ($hpos_id_arr as $hpos_id) {
				$hpos = HposManager::getHPosById($hpos_id);
				$hposs_collection['data']->push($hpos);
			}
			$xj->hposs = $hposs_collection;
		}
		if ($level >= 3) {
			$xj->steps = self::getStepsByXJId($xj->id);
			self::addShowNum($xj->id);
		}
		return $xj;
	}
	
	/*
	 * 根据宣教id获取宣教步骤信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-07
	 *
	 */
	public static function getStepsByXJId($xj_id)
	{
		$steps = TWStep::where('f_table', '=', 'xj')->where('f_id', '=', $xj_id)->orderby('seq', 'asc')->get();
		return $steps;
	}
	
	
	/*
	 * 根据f_id和f_table获取宣教步骤信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-23
	 *
	 */
	public static function getStepsByFidAndFtable($f_id, $f_table)
	{
		$steps = TWStep::where('f_table', '=', $f_table)->where('f_id', '=', $f_id)->get();
		return $steps;
	}
	
	
	/*
	 * 根据f_id和f_table删除全部宣教步骤
	 *
	 * By TerryQi
	 *
	 * 2107-12-23
	 */
	public static function deleteStepsByFidAndFtable($f_id, $f_table)
	{
		$steps = TWStep::where('f_table', '=', $f_table)->where('f_id', '=', $f_id)->delete();
	}
	
	/*
	 * 宣教展示数加1
	 *
	 * By TerryQi
	 *
	 * 2017-12-07
	 */
	public static function addShowNum($xj_id)
	{
		$xj = self::getXJById($xj_id);
		$xj->show_num = $xj->show_num + 1;
		$xj->save();
	}
	
	
	/*
	 * 根据宣教步骤id获取步骤信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-07
	 *
	 */
	public static function getStepById($id)
	{
		$tw_step = TWStep::where('id', '=', $id)->first();
		return $tw_step;
	}
	
	/*
	 * 设置宣教相关信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-6
	 *
	 */
	public static function setXJ($xj, $data)
	{
		if (array_key_exists('title', $data)) {
			$xj->title = array_get($data, 'title');
		}
		if (array_key_exists('desc', $data)) {
			$xj->desc = array_get($data, 'desc');
		}
		if (array_key_exists('doctor_id', $data)) {
			$xj->doctor_id = array_get($data, 'doctor_id');
		}
		if (array_key_exists('author', $data)) {
			$xj->author = array_get($data, 'author');
		}
		if (array_key_exists('img', $data)) {
			$xj->img = array_get($data, 'img');
		}
		if (array_key_exists('seq', $data)) {
			$xj->seq = array_get($data, 'seq');
		}
		if (array_key_exists('status', $data)) {
			$xj->status = array_get($data, 'status');
		}
		if (array_key_exists('hpos_ids', $data) && !Utils::isObjNull($data['hpos_ids'])) {
			$xj->hpos_ids = array_get($data, 'hpos_ids');
		}
		if (array_key_exists('show_num', $data)) {
			$xj->show_num = array_get($data, 'show_num');
		}
		return $xj;
	}
	
	/*
	 * 设置宣教类别
	 *
	 * By TerryQi
	 *
	 * 2017-12-11
	 *
	 */
	public static function setXJType($xjType, $data)
	{
		if (array_key_exists('name', $data)) {
			$xjType->name = array_get($data, 'name');
		}
		if (array_key_exists('desc', $data)) {
			$xjType->desc = array_get($data, 'desc');
		}
		if (array_key_exists('num', $data)) {
			$xjType->num = array_get($data, 'num');
		}
		if (array_key_exists('doctor_id', $data)) {
			$xjType->doctor_id = array_get($data, 'doctor_id');
		}
		if (array_key_exists('seq', $data)) {
			$xjType->seq = array_get($data, 'seq');
		}
		return $xjType;
	}
	
	/*
	 * 设置宣教步骤信息
	 *
	 * By TerryQi
	 *
	 * 2017-12-07
	 *
	 */
	public static function setTWStep($tw_step, $data)
	{
		
		if (array_key_exists('f_id', $data)) {
			$tw_step->f_id = array_get($data, 'f_id');
		}
		if (array_key_exists('f_table', $data)) {
			$tw_step->f_table = array_get($data, 'f_table');
		}
		if (array_key_exists('img', $data)) {
			$tw_step->img = array_get($data, 'img');
		}
		if (array_key_exists('video', $data)) {
			$tw_step->video = array_get($data, 'video');
		}
		if (array_key_exists('text', $data)) {
			$tw_step->text = array_get($data, 'text');
		}
		if (array_key_exists('seq', $data)) {
			$tw_step->seq = array_get($data, 'seq');
		}
		return $tw_step;
	}
	
}