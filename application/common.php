<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 打印数据
 * @param $param
 */
error_reporting(0);

function dd($param)
{
    var_dump($param);
    exit;
}

//获取当前域名
function common_func_domain()
{
    return request()->domain();
}

/**
  *给图片添加当前域名及前缀路径
  *$pic 图片路径（单图）
  *return array
**/
function common_func_pic_domain($pic, $path = "")
{
    return common_func_domain() . $path . $pic;
}

/**
  *给图片添加当前域名及前缀路径
  *$pics图片路径（多图，用|分隔）
  *return array
**/
function common_func_pics_domain($pics, $path = "")
{
    $arr = explode('|', $pics);
    $data = [];
    foreach ($arr as $k => $v) {
        $data[] = common_func_domain() . $path . $v;
    }
    return $data;
}

//匿名，只显示姓名首尾字符，隐藏中间字符并用*替换
function common_func_anonymous_nickname($nickname, $dot="*", $charset="UTF-8")
{
    $strlen = mb_strlen($nickname,$charset);
    if($strlen == 1) {
        return $dot;
    }
    $firstStr = mb_substr($nickname, 0, 1, $charset);
    if($strlen == 2) {
        return $firstStr . $dot;
    }
    $lastStr = mb_substr($nickname, -1, 1, $charset);
    return $firstStr . str_repeat($dot, $strlen - 2) . $lastStr;
}

/**
 * 递归获取整个树
 * @param $data array
 * @param $id int
 * @param $level int 分类级联层级
 * @return array 
 */
function getTree($data, $pId, $level = 0)
{
    $tree = [];
    foreach ($data as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['parent_id'] == $pId){
            //把这个节点从数组中移除,减少后续递归消耗
            unset($data[$key]);
            $tree[] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'pid' => $value['pid'],
                'level' => $level,
                'children' => getTree($data, $value['id'], $level+1), //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            ];
        }
    }
    return $tree;
}
