<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
class UserController extends Controller
{
    public function regdo(Request $request){
        $post=request()->except('_token');
        $post['reg_time']=time();
        $reg="/^[0-9a-zA-Z]{4,}$/";
        if(!preg_match($reg,$post['user_name'])){
            $response=[
                'errno'=>50002,
                'msg'=>"账号为数字字母至少四位"
            ];
            return $response;
        }
        $len=strlen($post['password']);
        if ($len!=6){
            $response=[
                'errno'=>50001,
                'msg'=>"密码长度必须为六位"
            ];
            return $response;
        }
        $where=[
            ['user_name','=',$post['user_name']]
        ];
        $userInfo=User::where($where)->first();
        if(!empty($userInfo)){
            $response=[
                'errno'=>50003,
                'msg'=>"账号已存在"
            ];
            return $response;
        }
        $res=User::insert($post);
        if($res){
            $response=[
                'errno'=>50000,
                'msg'=>"注册成功"
            ];
            return $response;
        }
    }
    public function logindo(){
        $post=request()->except('_token');
        $where=[
            ['user_name','=',$post['user_name']]
        ];
        $userInfo=User::where($where)->first();
        if(!empty($userInfo)){
            if($post['password']!=$userInfo['password']){
                $response=[
                    'errno'=>50004,
                    'msg'=>"密码错误"
                ];
                return $response;
            }
            User::where($where)->update(['last_login'=>time()]);
            $token=substr(md5(time().$post['password']),5,4).substr(md5(time().$post['password']),6,14);
            $response=[
                'errno'=>50000,
                'msg'=>"登陆成功",
                'token'=>$token
            ];
            return $response;
        }else{
            $response=[
                'errno'=>50005,
                'msg'=>"没有此账号"
            ];
            return $response;
        }

    }
}
