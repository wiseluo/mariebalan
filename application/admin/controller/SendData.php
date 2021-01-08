<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-12-27
 * Time: 10:30
 */
namespace app\admin\controller;

use think\Controller;

class SendData extends Controller {

    /**
     * json字符串post请求方法
     * @param $url
     * @param $data_string
     * @return string
     */
    function http_post_data($url, $data_string) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 2);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return  $return_content;
    }

}