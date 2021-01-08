<?php


namespace app\index\controller;


use app\common\controller\Base;
use think\Db;
class Alipay extends Base
{
    public function index()
    {
        header("Content-type:text/html;charset=utf-8");
        $id = input('post.id');
        $orderlist = Db::table('yw_orders')->where('id',$id)->find();
        if ($orderlist['number']){
            include_once VENDOR_PATH . '/alipay/config.php';
            include_once VENDOR_PATH . '/alipay/pagepay/service/AlipayTradeService.php';
            include_once VENDOR_PATH . '/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = trim($orderlist['number']);

            //订单名称，必填
            $subject = '跨境洋货商城商品支付';

            //付款金额，必填
            $total_amount = trim($orderlist['payment']);

            //商品描述，可空
            $body = '';
            //构造参数
            $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setOutTradeNo($out_trade_no);

            $aop = new \AlipayTradeService($config);

            /**
             * pagePay 电脑网站支付请求
             * @param $builder 业务参数，使用buildmodel中的对象生成。
             * @param $return_url 同步跳转地址，公网可以访问
             * @param $notify_url 异步通知地址，公网可以访问
             * @return $response 支付宝返回的信息
             */
            $response = $aop->pagePay($payRequestBuilder, $config['return_url'], $config['notify_url']);

            //输出表单
            var_dump($response);
        }
    }

    public function notify()
    {
        $post = input();
        if ($post['trade_status'] == "TRADE_SUCCESS") {
            Db::table('yw_orders')->where('number',$post['out_trade_no'])->update(array('paytype'=>1,'paytime'=>$post['gmt_payment'],'payno'=>$post['trade_no'],'state'=>2));
            //操作数据库 修改状态
            echo "SUCCESS";
        }
////        写在文本里看一下参数
//        $data = json_encode($post);
//
//        fopen("testfile.txt", $data);

    }
    /**
     * 同步方法
     * @return [type] [description]
     */
    public function returnfy()
    {
        $this->redirect('/ucenter/order');
    }
}