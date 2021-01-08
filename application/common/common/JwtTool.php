<?php
namespace app\common\common;

use \Firebase\JWT\JWT;
use think\Request;
use think\facade\Cache;

class JwtTool
{
	private $key = "mariebalan2020";

    public function setAccessToken($user)
    {
        $token = [
            "iat" => time(),  #token发布时间
            "exp" => time() + 86400*30, #token过期时间 一月
            "id" => $user['id'],
            'phone' => $user['phone'],
            'nickname' => $user['nickname'],
            'sex' => $user['sex'],
            'headimgurl' => $user['headimgurl']
        ];
        $jwt = JWT::encode($token, $this->key);
        $md5 = md5($jwt);
        Cache::store('file')->set($md5, $jwt);
        return $md5;
    }

    public function validateToken($token)
    {
        if (!$token) {
            return false;
        }
        $jwt = Cache::store('file')->get($token);
        if (!$jwt){
            return false;
        }
        return JWT::decode($jwt, $this->key, ['HS256']);
    }

    public function deleteToken($token)
    {
        if (!$token) {
            return false;
        }
        Cache::store('file')->rm($token);
        return true;
    }
}