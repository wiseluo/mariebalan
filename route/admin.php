<?php

Route::post('admin/upload_img', 'admin/UploadController/uploadImage'); //上传图片
Route::post('admin/upload_video', 'admin/UploadController/uploadVideo'); //上传视频文件

Route::post('admin/login', 'admin/LoginController/login');
Route::post('admin/change_password', 'admin/SystemController/changePassword');
Route::get('admin/admininfo', 'admin/SystemController/adminInfo');
Route::post('admin/logout', 'admin/SystemController/logout');

Route::get('admin/left_module', 'admin/ModuleController/leftModule');
Route::resource('admin/module', 'admin/ModuleController');
Route::resource('admin/user', 'admin/UserController');
Route::resource('admin/ads', 'admin/AdsController');
Route::resource('admin/category', 'admin/CategoryController');
Route::resource('admin/material', 'admin/MaterialController');
Route::resource('admin/forum', 'admin/ForumController');
Route::resource('admin/goods', 'admin/GoodsController');
Route::post('admin/goods/shelf/:id', 'admin/GoodsController/shelf'); //上下架
Route::resource('admin/order', 'admin/OrderController');
Route::get('admin/to_deliver_order', 'admin/OrderController/toDeliverOrderList'); //待发货列表
Route::post('admin/deliver_order', 'admin/OrderController/deliverOrder'); //发货

Route::resource('admin/express', 'admin/ExpressController'); //快递公司
Route::resource('admin/comment', 'admin/CommentController'); //评论
Route::resource('admin/income', 'admin/IncomeController'); //收益
Route::resource('admin/coupon', 'admin/CouponController'); //优惠券
