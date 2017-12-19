<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:30
 */

namespace App\Components;

use App\Models\User;
use App\Models\UserCase;
use App\Models\Vertify;
use GuzzleHttp\Psr7\Request;

class UserManager
{

    /*
     * 根据id获取用户信息，带token
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserInfoByIdWithToken($id)
    {
        $user = User::where('id', '=', $id)->first();
        return $user;
    }

    /*
     * 根据id获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserInfoById($id)
    {
        $user = self::getUserInfoByIdWithToken($id);
        if ($user) {
            $user->token = null;
        }
        return $user;
    }

    /*
     * 根据user_code和token校验合法性，全部插入、更新、删除类操作需要使用中间件
     *
     * By TerryQi
     *
     * 2017-09-14
     *
     * 返回值
     *
     */
    public static function ckeckToken($id, $token)
    {
        //根据id、token获取用户信息
        $count = User::where('id', '=', $id)->where('token', '=', $token)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 用户登录
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function login($data)
    {
        //获取account_type，后续进行登录类型判断
        $account_type = $data['account_type'];
        // 判断小程序，按照类型查询
        if ($account_type === 'xcx') {
            $user = self::getUserByXCXOpenId($data['xcx_openid']);
            //存在用户即返回用户信息
            if ($user != null) {
                return $user;
            }
        }
        //不存在即新建用户
        return self::register($data);
    }

    /*
     * 配置用户信息，用于更新用户信息和新建用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function setUser($user, $data)
    {
        if (array_key_exists('real_name', $data)) {
            $user->real_name = array_get($data, 'real_name');
        }
        if (array_key_exists('nick_name', $data)) {
            $user->nick_name = array_get($data, 'nick_name');
        }
        if (array_key_exists('birthday', $data)) {
            $user->birthday = array_get($data, 'birthday');
        }
        if (array_key_exists('avatar', $data)) {
            $user->avatar = array_get($data, 'avatar');
        }
        if (array_key_exists('phonenum', $data)) {
            $user->phonenum = array_get($data, 'phonenum');
        }
        if (array_key_exists('xcx_openid', $data)) {
            $user->xcx_openid = array_get($data, 'xcx_openid');
        }
        if (array_key_exists('unionid', $data)) {
            $user->unionid = array_get($data, 'unionid');
        }
        if (array_key_exists('gender', $data)) {
            $user->gender = array_get($data, 'gender');
        }
        if (array_key_exists('status', $data)) {
            $user->status = array_get($data, 'status');
        }
        if (array_key_exists('type', $data)) {
            $user->type = array_get($data, 'type');
        }
        if (array_key_exists('province', $data)) {
            $user->province = array_get($data, 'province');
        }
        if (array_key_exists('city', $data)) {
            $user->city = array_get($data, 'city');
        }
        return $user;
    }

    /*
     * 配置用户病例信息
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public static function setUserCase($userCase, $data)
    {
        if (array_key_exists('zz_doctor_id', $data)) {
            $userCase->zz_doctor_id = array_get($data, 'zz_doctor_id');
        }
        if (array_key_exists('kf_doctor_id', $data)) {
            $userCase->kf_doctor_id = array_get($data, 'kf_doctor_id');
        }
        if (array_key_exists('ss_time', $data)) {
            $userCase->ss_time = array_get($data, 'ss_time');
        }
        if (array_key_exists('wt_time', $data)) {
            $userCase->wt_time = array_get($data, 'wt_time');
        }
        if (array_key_exists('xj_type', $data)) {
            $userCase->xj_type = array_get($data, 'xj_type');
        }
        return $userCase;
    }

    /*
     * 注册用户
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function register($data)
    {
        //创建用户信息
        $user = new User;
        $user = self::setUser($user, $data);
        $user->token = self::getGUID();
        $user->save();
        $user = self::getUserInfoByIdWithToken($user->id);
        return $user;
    }

    /*
     * 更新用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     *
     */
    public static function updateUser($data)
    {
        //配置用户信息
        $user = self::getUserInfoByIdWithToken($data['user_id']);
        $user = self::setUser($user, $data);
        $user->save();
        return $user;
    }


    /*
     * 根据用户openid获取用户信息
     *
     * By TerryQi
     *
     * 2017-09-28
     */
    public static function getUserByXCXOpenId($openid)
    {
        $user = User::where('xcx_openid', '=', $openid)->first();
        return $user;
    }


    // 生成guid
    /*
     * 生成uuid全部用户相同，uuid即为token
     *
     */
    public static function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));

            $uuid = substr($charid, 0, 8)
                . substr($charid, 8, 4)
                . substr($charid, 12, 4)
                . substr($charid, 16, 4)
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /*
     * 生成验证码
     *
     * By TerryQi
     */
    public static function sendVertify($phonenum)
    {
        $vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
        $vertify = new Vertify;
        $vertify->phonenum = $phonenum;
        $vertify->code = $vertify_code;
        $vertify->save();
        /*
         * 预留，需要触发短信端口进行验证码下发
         */
        if ($vertify) {
            return true;
        }
    }

    /*
     * 校验验证码
     *
     * By TerryQi
     *
     * 2017-11-28
     */
    public static function judgeVertifyCode($phonenum, $vertify_code)
    {
        $vertify = Vertify::where('phonenum', '=', $phonenum)
            ->where('code', '=', $vertify_code)->where('status', '=', '0')->first();
        if ($vertify) {
            //验证码置为失效
            $vertify->status = '1';
            $vertify->save();
            return true;
        } else {
            return false;
        }
    }


    /*
     * 获取全部用户信息
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public static function getAllUsers()
    {
        $users = User::orderby('id', 'desc')->paginate(10);
        return $users;
    }

    /*
     * 根据级别不同获取用户的具体病例信息
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     * level：获取级别，暂无
     *
     */
    public static function getUserCaseByLevel($level, $user_id)
    {
        $userCase = UserCase::where('user_id', '=', $user_id)->orderby('id', 'desc')->first();
        if ($userCase) {
            $userCase->zz_doctor = DoctorManager::getDoctorById($userCase->zz_doctor_id);
            $userCase->kf_doctor = DoctorManager::getDoctorById($userCase->kf_doctor_id);
            $userCase->kfmb = KFMBManager::getKFMBById($userCase->kfmb_id);
        }
        return $userCase;
    }

    /*
     * 根据用户id获取病例，即获取该用户的全部病例
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public static function getUserCaseByUserId($user_id)
    {
        $userCases = UserCase::where('user_id', '=', $user_id)->get();
        return $userCases;
    }

    /*
     * 获取用户的最新的病例信息
     *
     * By TerryQi
     *
     * 2017-12-19
     */
    public static function getTopUserCaseByUserId($user_id)
    {
        $userCase = UserCase::where('user_id', '=', $user_id)->orderby('id', 'desc')->first();
        return $userCase;
    }


    /*
     * 根据id获取用户病例
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public static function getUserCaseById($userCase_id)
    {
        $userCase = UserCase::where('id', '=', $userCase_id)->first();
        return $userCase;
    }

    /*
     * 搜索用户信息
     *
     * By TerryQi
     *
     * 2017-12-19
     *
     */
    public static function searchUser($search_word)
    {
        $users = User::where('real_name', 'like', '%' . $search_word . '%')
            ->orwhere('phonenum', 'like', '%' . $search_word . '%')->orderby('id', 'desc')->paginate(10);
        return $users;
    }


}