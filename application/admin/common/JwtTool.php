<?php
namespace app\admin\common;

use \Firebase\JWT\JWT;
use think\Request;
use think\facade\Cache;

class JwtTool
{
	private $key = "mariebalanadmin";

    public function setAccessToken($user)
    {
        $token = [
            "iat" => time(),  #token发布时间
            "exp" => time() + 86400*30, #token过期时间 一月
            "id" => $user['id'],
            'username' => $user['username'],
        ];
        $jwt = JWT::encode($token, $this->key);
        $md5 = md5($jwt);
        Cache::store('file')->set('admin'. $md5, $jwt);
        return $md5;
    }

    public function validateToken($token)
    {
        if (!$token) {
            return false;
        }
        $jwt = Cache::store('file')->get('admin'. $token);
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
        Cache::store('file')->rm('admin'. $token);
        return true;
    }
}