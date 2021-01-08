<?php


namespace app\index\controller;


use app\common\controller\Base;
use think\Db;
class Wxpay extends Base
{
    protected $mchid = '1409930802';          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
    protected $appid = 'wx019a19e6bec08035';  //公众号APPID 通过微信支付商户资料审核后邮件发送
    protected $apiKey = '7599fdee847d6b47adc12ec5647d528d';   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
    protected $notifyUrl = 'http://112.13.199.14:202/index/wxpay/notify';     //付款成功后的回调地址(不要有问号)

    public function index()
    {
        header('Content-type:text/html; Charset=utf-8');
        header('Content-Type: image/png');
        ob_clean();
        $id = input('get.id');
        $orderlist = Db::table('yw_orders')->where('id',$id)->find();
        $outTradeNo = trim($orderlist['number']);     //你自己的商品订单号
        $payAmount = trim($orderlist['payment']);          //付款金额，单位:元
        $orderName = '跨境洋货商城商品支付';    //订单标题
        $payTime = time();      //付款时间
        $arr = $this->createJsBizPackage($payAmount, $outTradeNo, $orderName, $this->notifyUrl, $payTime);
// 2.生成二维码链接
        include_once EXTEND_PATH.'/phpqrcode/phpqrcode.php';
        $errorCorrectionLevel = "L";
        $matrixPointSize = "7";
        \Qrcode::png($arr['code_url'],false, $errorCorrectionLevel, $matrixPointSize);exit;
    }
    /**
     * 发起订单
     * @param float $totalFee 收款总费用 单位元
     * @param string $outTradeNo 唯一的订单号
     * @param string $orderName 订单名称
     * @param string $notifyUrl 支付结果通知url 不要有问号
     * @param string $timestamp 订单发起时间
     * @return array
     */
    public function createJsBizPackage($totalFee, $outTradeNo, $orderName, $notifyUrl, $timestamp)
    {
        $config = array(
            'mch_id' => $this->mchid,
            'appid' => $this->appid,
            'key' => $this->apiKey,
        );
        //$orderName = iconv('GBK','UTF-8',$orderName);
        $unified = array(
            'appid' => $config['appid'],
            'attach' => 'pay',             //商家数据包，原样返回，如果填写中文，请注意转换为utf-8
            'body' => $orderName,
            'mch_id' => $config['mch_id'],
            'nonce_str' => self::createNonceStr(),
            'notify_url' => $notifyUrl,
            'out_trade_no' => $outTradeNo,
            'spbill_create_ip' => '127.0.0.1',
            'total_fee' => intval($totalFee * 100),       //单位 转为分
            'trade_type' => 'NATIVE',
        );
        $unified['sign'] = self::getSign($unified, $config['key']);
        $responseXml = self::curlPost('https://api.mch.weixin.qq.com/pay/unifiedorder', self::arrayToXml($unified));
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $unifiedOrder = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($unifiedOrder === false) {
            die('parse xml error');
        }
        if ($unifiedOrder->return_code != 'SUCCESS') {
            die($unifiedOrder->return_msg);
        }
        if ($unifiedOrder->result_code != 'SUCCESS') {
            die($unifiedOrder->err_code);
        }
        $codeUrl = (array)($unifiedOrder->code_url);
        if (!$codeUrl[0]) exit('get code_url error');
        $arr = array(
            "appId" => $config['appid'],
            "timeStamp" => $timestamp,
            "nonceStr" => self::createNonceStr(),
            "package" => "prepay_id=" . $unifiedOrder->prepay_id,
            "signType" => 'MD5',
            "code_url" => $codeUrl[0],
        );
        $arr['paySign'] = self::getSign($arr, $config['key']);
        return $arr;
    }
    public function notify(){
        //执行业务逻辑改变订单状态等操作//查询创建订单表 where("out_trade_no='".$out_trade_no."' and status=1") status为1表示待支付状态 1 待支付//查询出来有该订单 就改变支付状态 status=2 2表示支付成功
        $testxml  = file_get_contents("php://input");
        $jsonxml = json_encode(simplexml_load_string($testxml, 'SimpleXMLElement', LIBXML_NOCDATA));
        $result = json_decode($jsonxml, true);//转成数组，
        if($result){
            //如果成功返回了
            $out_trade_no = $result['out_trade_no'];
            if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
                Db::table('yw_orders')->where('number',$out_trade_no)->update(array('paytype'=>2,'paytime'=>$result['time_end'],'payno'=>$result['transaction_id'],'state'=>2));
                //操作数据库 修改状态
                echo "SUCCESS";
            }
        }
    }
    /**
     * curl get
     *
     * @param string $url
     * @param array $options
     * @return mixed
     */
    public static function curlGet($url = '', $options = array())
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 获取签名
     */
    public static function getSign($params, $key)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params, false);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . $key));
        return $signStr;
    }
    protected static function formatQueryParaMap($paraMap, $urlEncode = false)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if (null != $v && "null" != $v) {
                if ($urlEncode) {
                    $v = urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }
    public function wxpaytmp()
    {
        $id = input('post.id');
        $orderlist = Db::table('yw_orders')->where('id',$id)->find();
//        var_dump($orderlist);die;
        $this->assign('orderlist',$orderlist);
        return $this->fetch();
    }
    public function wxpay_status()
    {
        $state = Db::table('yw_orders')->where('number',input('number'))->value('state');
        $data = ['code'=>$state];
        return json($data);
    }
}