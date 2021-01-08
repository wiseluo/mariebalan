<?php

//Route::resource('goods', 'api/Goods');
//Route::resource('orders', 'api/Orders');
//Route::resource('favorite', 'api/Favorite');
//Route::resource('config', 'api/Config');
//Route::resource('history', 'api/History');
//Route::resource('search', 'api/Search');
//Route::resource('refund', 'api/Refund');
//Route::resource('register', 'api/Register');
//Route::resource('commodity', 'api/Commodity');

//Route::resource('turnoverstatistics', 'api/TurnoverStatistics');

// Route::get('admin/product_spread_statistics', 'api/admin/product_spread_statistics'); // 商品推广统计
// Route::get('admin/member_spread_statistics', 'api/admin/member_spread_statistics'); // 业务员推广统计

//登录注册
Route::post('user/login', 'user/LoginController/login');
Route::post('wechat/applet_login','user/LoginController/wechatAppletLogin'); //微信小程序授权登录
Route::post('wechat/app_login','user/LoginController/wechatAppLogin'); //微信app授权登录
Route::post('qq/login','user/LoginController/qqLogin'); //QQ授权登录
Route::post('user/register', 'user/RegisterController/register'); //用户注册
Route::post('user/wechat_register', 'user/RegisterController/wechatRegister'); //微信绑定注册
Route::post('user/qq_register', 'user/RegisterController/qqRegister'); //qq绑定注册
Route::post('user/logout', 'user/UserController/logout');
Route::post('user/forgot_password', 'user/LoginController/forgotPassword');
//Route::get('user/referee_qrcode', 'user/RefereeController/qrcode'); //用户推广二维码
Route::get('sms_code', 'user/SmsController/smsCode'); //获取短信验证码

//支付
Route::post('user/recharge_alipay', 'user/AlipayController/rechargePay'); //支付宝充值
Route::post('user/order_alipay', 'user/AlipayController/orderPay'); //支付宝支付订单
Route::post('alipay/recharge_notify', 'user/AlipayNotifyController/rechargeNotify'); //支付宝支付充值通知
Route::post('alipay/order_notify', 'user/AlipayNotifyController/orderNotify'); //支付宝支付订单通知
Route::post('user/recharge_wechat_pay', 'user/WechatController/rechargePay'); //微信充值
Route::post('user/order_wechat_pay', 'user/WechatController/orderPay'); //微信支付订单
Route::post('wechat/recharge_notify', 'user/WechatNotifyController/rechargeNotify'); //微信支付充值通知
Route::post('wechat/order_notify', 'user/WechatNotifyController/orderNotify'); //微信支付订单通知
Route::post('user/order_account_pay', 'user/UserController/orderAccountPay'); //用户账户余额支付订单

//订单
Route::resource('api/goods', 'api/GoodsController');
Route::resource('api/shopcart', 'api/ShopcartController'); //购物车
Route::post('api/shopcart/batch_delete', 'api/ShopcartController/batchDelete'); //购物车批量删除
Route::resource('api/order', 'api/OrderController'); //订单
Route::get('api/order/order_now_prejudge', 'api/OrderController/orderNowPrejudge'); //立即购买预判断
Route::get('api/order/order_now', 'api/OrderController/orderNow'); //立即购买
Route::get('api/order/shopcart_settlement_prejudge', 'api/OrderController/shopcartSettlementPrejudge'); //购物车结算预判断
Route::get('api/order/shopcart_settlement', 'api/OrderController/shopcartSettlement'); //购物车结算
Route::post('api/order/remind_deliver/:id', 'api/OrderController/orderRemindDeliver'); //提醒发货
Route::post('api/order/cofirm_receipt/:id', 'api/OrderController/orderConfirmReceipt'); //确认收货
Route::post('api/order/cancel/:id', 'api/OrderController/orderCancel'); //待付款取消订单
Route::get('api/order/express/:id', 'api/OrderController/orderExpress'); //获取订单物流
Route::get('api/order/uncommented_list', 'api/OrderController/uncommentedList'); //获取待评价订单产品列表

Route::get('api/comment_goods', 'api/CommentGoodsController/commentGoods'); //商品评论列表
Route::resource('api/comment', 'api/CommentController'); //评论
Route::post('api/comment_reply', 'api/CommentController/commentReply'); //回复评论
Route::get('api/user_commented', 'api/CommentController/userCommented'); //获取用户自己已评价产品列表

//用户
Route::get('user/userinfo', 'user/UserController/userinfo');
Route::post('user/headimgurl', 'api/UserController/updateHeadimgurl'); //修改用户头像地址
Route::get('user/income_list', 'user/IncomeController/logList'); //收益明细
Route::resource('user/coupon', 'user/UserCouponController'); //用户优惠券列表

//用户地址
Route::resource('api/address', 'api/AddressController');
Route::get('api/user_default_address', 'api/AddressController/userDefaultAddress');

//通用
Route::post('upload_img', 'api/UploadController/uploadImage'); //上传图片

Route::get('citys', 'api/CitysController/cityList'); //获取城市列表
Route::get('index/video', 'api/IndexController/video'); //首页视频
Route::get('api/category', 'api/CategoryController/index'); //获取商品品类列表
Route::get('api/ads_slide', 'api/AdsController/adsSlide'); //首页获取广告幻灯片
Route::get('api/forum', 'api/ForumController/index'); //获取版块列表
Route::get('api/material', 'api/MaterialController/index'); //获取材质列表
