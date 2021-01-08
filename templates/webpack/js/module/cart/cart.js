/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "style!./css/module/cart/111",
    "style!./css/module/registe/perfect_information"
], function () {
     $(document).on("click", "#checkAll", function (e) {
        $('input[name="subBox[]"]').attr("checked", this.checked);
        if (this.checked) {
            $('input[name="subBox[]"]').uCheck('check');
        } else {
            $('input[name="subBox[]"]').uCheck('uncheck');
        }
        total_price();
//        return false;
    });
    //页面加载的时候全选
    $("#checkAll").trigger("click");
    var $subBox = $("input[name='subBox[]']");
    $subBox.click(function () {
        if ($subBox.length == $("input[name='subBox[]']:checked").length) {
            $("#checkAll").uCheck('check');
            $("#checkAll").attr("checked", true);
        } else {
            $("#checkAll").uCheck('uncheck');
            $("#checkAll").attr("checked", false);
        }
    });
    /**
     * 减法
     */
    $(".minus_btn").click(function (e) {
        var current = parseInt($(this).next().val());
        var t = $(this).next(),
                a = parseInt(t.val());
        if (current > 1) {
            current--;
            $(this).addClass('am-axtive');
        }

        $(this).next().val(current);

//        return false;
        var goods_id = $(this).siblings("input[name='goods_id[]']").val();
        var sub_id = $(this).siblings("input[name='sub_id[]']").val();
        var std_goods_id = $(this).siblings("input[name='std_goods_id[]']").val();
        var sale_price = $(this).siblings("input[name='sale_price[]']").val();
        var barcode = $(this).siblings("input[name='barcode[]']").val();
        var seller_owner_id = $(this).siblings("input[name='seller_owner_id[]']").val();
        change_guige(t, current, goods_id, sub_id, std_goods_id,sale_price,barcode,seller_owner_id);
        !1;
    });
    /**
     * 加法
     */
   //$(document).on('click', '.minus', function(){
   $('.minus').click(function(){
        var t = $(this).prev(), a = parseInt(t.val());
        if (a > 0){
            a++;
            $(this).prev().prev().removeClass('am-axtive');
        }
       t.val(a);
        var goods_id = $(this).siblings("input[name='goods_id[]']").val();
        var sub_id = $(this).siblings("input[name='sub_id[]']").val();
        var std_goods_id =$(this).siblings("input[name='std_goods_id[]']").val();
        var sale_price = $(this).siblings("input[name='sale_price[]']").val();
        var barcode = $(this).siblings("input[name='barcode[]']").val();
        var seller_owner_id = $(this).siblings("input[name='seller_owner_id[]']").val();
        change_guige(t, a, goods_id, sub_id, std_goods_id,sale_price,barcode,seller_owner_id);
        !1;
    });
   
      //购物车商品列表选择
    $(".sub_id").on('click', function () {
        var obj_parent = $(this).parents(".plat-border_bottom");
        if ($(this).is(":checked") == true) {
            var single_amount = parseFloat($(obj_parent).find(".single_ammount").attr("amount"));
            var single_weights = parseFloat($(obj_parent).find(".single_weights").text());
            var total_account = parseFloat($("#total_account").text()) + single_amount;

            $("#total_account").html(total_account.toFixed(2));
            $("#goods_count").html(parseInt($("#goods_count").text()) + 1);
            $("#total_weight").html(parseInt($("#total_weight").text()) + single_weights);
        } else {
            var single_amount = parseFloat($(obj_parent).find(".single_ammount").attr("amount"));
            var single_weights = parseFloat($(obj_parent).find(".single_weights").text());
            var total_account = parseFloat($("#total_account").text()) - single_amount;

            $("#total_account").html(total_account.toFixed(2));
            $("#goods_count").html(parseInt($("#goods_count").text()) - 1);
            $("#total_weight").html(parseInt($("#total_weight").text()) - single_weights);
        }
    });
    var th='';
    function ht(title){
        var hn='<div class="motify"><div class="motify-inner"></div></div>';
        $('body').append(hn);
        var $Motify = $('.motify'), innerText = title;
        $Motify.find('.motify-inner').text(innerText);
        $Motify.show();
        setTimeout(function () {
            $Motify.fadeOut();
        }, 1000);
    }
    /*移除购物车*/
    $(".delete_item").click(function(){
//    })
//    $(".fnb_remove_from_cart").on('click',function(){alert(33);
        var obj_parent = $(this).parents("article.fnb_product");
        var obj_parent_that = $(this).parents(".list_item");
        var goods_id = $(this).attr("goods_id");
        var std_goods_id = $(this).attr("std_id");

        layer('请问您是否要删除','取消','确定');

        $(document).on('click', '.layui-m-layerbtn span', function (event) {
            th= $(this).attr('data-type');
            if(th==1){
               // ajax处理将信息从数据库中删除
                $.ajax({
                    type:'POST',
                    url: '/cart/detale_to_cart.html',
                    data:{
                        goods_id:goods_id,
                        std_goods_id:std_goods_id
                    },
                    datatype:'json',
                    success:function(data){
                        if(data.status==1) {
                            $(obj_parent).remove();
                            $(obj_parent_that).remove();
                            $("#total_account").html(data.count_price);
                            $(".mini_cart_quantity").html(data.cart_count);
                            $(".cartcount").html(data.cart_count);
                            $('.layui-m-con').remove();
                            th=0;
                        }else{
                            alert(data.info);
                        }
                    },
                    error:function(){
                        ht("链接异常，请检查参数或者联系系统管理员");
                    },compelete:function(){}
                });
            }else{
                $('.layui-m-con').remove();
                return false;
            }
        })


    });
    /*生成订单*/
    $(".fnb_submit ,.fnb_btn").on('click',function(){
        var len = $(".products_list input[type='checkbox']:checked").length;
        if($("#checkAll").is(":checked")==true){
//            len = len-1;
        }
        if (len==0) {
            ht("请选择您要购买的商品");
            return false;
        }
        var len_r = $("input[type='radio']:checked").length;
        if (len_r==0) {
//            alert("请选择地址");
            $('.an_addredd_content ').css({
                'width':document.documentElement.clientWidth,
                'height':document.documentElement.clientHeight
            });
            $('.page_1').show();
            jinzhi=1;
            return false;
        }
//        $("#formBag").submit();
    });

    /*加载进行报价*/
    total_price();
    //算取购物车商品总价格
    function total_price() {
        //价格
        var total_price = 0,total_num=0;
        $(".single_ammount").each(function(){
            var line = $(this).attr('line');
            if($('input.checked_'+line).is(":checked")==true){
                total_price+= parseFloat($(this).attr("amount"));
            }
        });
        $("#total_account").html(total_price.toFixed(2));

        $(".goods-num").each(function(){
    //            total_num+= parseFloat($(this).val());
                total_num+= 1;
        });    
        $(".cartcount").html(total_num);
    }
    ;
    //ajax计算数量
    function change_guige(arg, a, b, c, d,sale_price,barcode,seller_owner_id){
        
        $.ajax({
            type: 'post',
            url : '/cart/updata_to_cart.html',
            data:{
                order_qty: a,
                goods_id: b,
                sub_id: c,
                std_goods_id:d,
                sale_price:sale_price,
                barcode:barcode,
                seller_owner_id:seller_owner_id
            },
            datatype: 'json',
            success:function(data){
                if(data.code=='0'){
                    arg.val(a);
                    var new_sing_price = parseFloat(data.sale_price)*parseInt(a);
                    //单个商品总价的变化                    
                    arg.parents(".plat-border_bottom").find(".single_ammount").attr("amount",new_sing_price);
                    //计算总价
                    total_price();
                }else{
                    ht(data.message);
                }
            },
            error:function(){
                ht('链接异常，请检查参数或者联系系统管理员');
            },
            complete:function(){}
        });
    }

    // 地址框输入信息
    var jinzhi=0;
    document.addEventListener("touchmove",function(e){
        if(jinzhi==1){
            e.preventDefault();
            e.stopPropagation();
        }
    },false);
    $(function(){
        $('.page_1 .fa-close').click(function () {
            $(".page_1 input").attr("value","");
            $('.page_1').hide();
            jinzhi=0;
        });
        $('.page_2 .fa-close').click(function () {

            window.location.href="d/promotion_order_detail"
        });
        //上一步
        $('.clear_btn').click(function(){
            $('.page_1').hide();
            jinzhi=0;
        });
    //  付款
        $('.an_sub_btn').click(function(){
            if ($("input[type=radio]").length > 0) {//是否有已存在的客户，选择
                if ($("input[type=radio]:checked").length > 0) {
                    var customer_id = $("input[type=radio]:checked").attr("customer_id");
                    var html = "<input type='hidden' name='customer_id' value='" + $("input[type=radio]:checked").attr("customer_id") + "'/>";
                    $("#am_add_fo").append(html);
                } else {
                    return alertTips('.js-txt-truename', '请选择客户！');
                }
            } else {
            var a = $('#doc-ipt-name').val(),
                b = $('#doc-ipt-phone').val(),
                c = $('#address').val(),
                f = $('#doc_tipinput').val(),
                e = $('#doc_youfonput').val();
                if (!a)
                    return alertTips('#doc-ipt-name', '请填写姓名');
                if (!IsTel(b))
                    return alertTips('#doc-ipt-phone', '请填写正确手机号');
                if (!c)
                    return alertTips('#address', '请选择地址');
                if (!f)
                    return alertTips('#doc_tipinput', '请填写详细地址');
                if (!e)
                    return alertTips('#doc_youfonput', '请填写邮编');
            }
            console.log($("#formBag").serialize());
            $.ajax({
                type: 'POST',
                url: '/order/tourists_order.html',
                data: $("#formBag").serialize() + "&" + $("#am_add_fo").serialize(),
                dataType: 'json',
                success: function (response) {
                    if(response.status==1){
            
                        //返回成功，展示品牌经理人的微信名片，进行线下付款
                        $('.page_1').hide();
                        $('.page_2').show();
                        jinzhi=1;
            
                    }else{
                        //alert(response.info);
                        ht(response.info)
                    }
                },
                error: function () {}
            });

        });
    });
    $(document).on("click","#go_back_btn",function () {
//        $("#delivery_modal").hide();
        $("#delivery_modal").children().remove();
        $("#receipt_form").show();
    });
    $(document).on("click","#add_reciept_btn",function () {
//        $("#delivery_modal").hide();
        $("#delivery_modal").children().remove();
        $("#receipt_form").show();
        $("#form-with-tooltip")[0].reset();
    });
    /**
     * 输入手机号获取已存在的地址
     */
    $("#doc-ipt-phone").on('blur', function () {
        var phone = $(this).val();
        var flag = $(this).attr('flag');
        if (flag == 1)
            return false;//这个是添加收货地址不需要搜索
        var bValidate = RegExp(/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/).test(phone);
        if (phone == "" || phone == null || bValidate != true) {
            //请填写联系电话
            return false;
        }
        $.ajax({
            type: 'POST',
            url: '/Order/get_receipt_by_phone.html',
            data: {phone: phone},
            success: function (response) {
                if (response.status == 1) {
                    if ($("#address_list") != null) {
                        $("#address_list").remove();
                        //console.log(response.htmlData);
                        $("#delivery_modal").append(response.htmlData);
                        $("#delivery_modal").show();
//                    del_receipt();//在receipt中
                        $("#receipt_form").hide();
                        $(".page_1_head").text("请选择收货地址");
                    }
                }
            }
        });

    });
    / *表单验证*/
    function IsTel(Tel){
        var re=new RegExp(/^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)$/);
        var retu=Tel.match(re);
        if(retu){
            return true;
        }else{
            return false;
        }
    }
    function alertTips(className,errorText){
        var $Motify = $('.motify'), innerText = '';
        $(className).focus();
        if (errorText) innerText = errorText;
        $Motify.find('.motify-inner').text(innerText);
        $Motify.show();
        setTimeout(function(){
            $Motify.fadeOut();
        },2000);
        return false;
    }
    //弹窗
    function layer(title,no,yes){
        var lae=
            '<div class="layui-m-con">' +
                '<div class="layui-m-layershade"></div>' +
                ' <div class="layui-m-layermain">' +
                ' <div class="layui-m-layersection">' +
                '<div class="layui-m-layerchild">' +
                ' <div class="layui-m-layercont">' +
                 title +
                '</div>' +
                ' <div class="layui-m-layerbtn">' +
                ' <span no data-type="0">'+no+'</span>' +
                '<span yes data-type="1">'+yes+'</span>' +
                ' </div>' +
                '</div>' +
                ' </div>' +
                ' </div>' +
            ' </div>';
        $('body').append(lae);

    };

    //function layui_btn(){
    //    $('.layui-m-layerbtn span').click(function(){
    //        th=$(this).attr('data-type');
    //        if(th==1){
    //            $('.layui-m-con').remove();
    //            return true;
    //        }else{
    //            $('.layui-m-con').remove();
    //            return false;
    //        }
    //        alert(th)
    //    })
    //}

    //生成订单
//    $(document).on('click', '#btnorder', function (event) {
//        var user_id = $("#user_id").val();
//        var _this = $(this);
//        var sale_price=$("#sale_price").attr("val");
//        //未登录操作
//        /*if (user_id == "" || user_id == null || user_id == 0) {
//           $("#true_cart_btn").attr("data-target", "#exampleModal");
//           tempModal.modal('show');
//           return false;
//        }*/
//        //0：加入购物车 1：立即购买
//        var buyer_status = $("#buyer_status").val();
//        var goods_id = $("#goods_id").val(), number = $(".goods-num").val(), spec_id = $("#spec_id").val(), goods_seller = $("#goods_seller").val();
//        if ($("#is_contain_std").val() == '1') {
//            if (spec_id == 0) {
//                ht("请先选择规格");
//                return false;
//            }
//        }
//        var ggid = goods_id + "_" + spec_id;
//        $.ajax({
//            type: 'POST',
//            url: '/order/add.html',
//            data: $("#cart_goods_detail").serialize()+"&type="+buyer_status+"&goods_id="+goods_id+"&std_goods_id="
//                    +spec_id+"&number="+number+"&user_id="+user_id+"&sale_price="+sale_price+"&goods_seller="+goods_seller
////                    {
////                type: buyer_status,
////                goods_id: goods_id,
////                std_goods_id: spec_id,
////                number: number,
////                user_id: user_id,
////                sale_price:sale_price,
////                form_data:$("#cart_goods_detail").serialize()
////            }
//                ,
//            dataType: 'json',
//            success: function (response) {
//                if (response.code == '-1') {
//                    $.layer({
//                        shade: [0.5, '#000'],
//                        area: ['auto', 'auto'],
//                        title: '提示',
//                        dialog: {
//                            msg: response.message,
//                            btns: 2,
//                            type: 5,
//                            btn: ['确定', '取消'],
//                            shift: 'bottom',
//                            yes: function () {
//                                window.location = "/login.html?url=" + encodeURIComponent(window.location.href);
//                            }, no: function () {
//                                $(".close").trigger('click');
//                            }
//                        }
//                    });
//                }
//                if (response.code == 1) {
//                    alert(response.message, 3, 8);
//                }
//                if (response.code == 0) {
//                    alert(response.message, 1, 1);
//                    $("#cart_count").html(response.count);
//                    $(".close").trigger('click');
//                }
//                if (response.code == 2) {
//                    if (_this.attr("btn_val") == 'buy_now') {
//                        window.location.href = "/cart.html";
//                    } else {
//                        // 滚动大小
////                        var scrollLeft = document.documentElement.scrollLeft || document.body.scrollLeft || 0,
////                        scrollTop = document.documentElement.scrollTop || document.body.scrollTop || 0;
////                        eleFlyElement.style.left = event.clientX + scrollLeft + "px";
////                        eleFlyElement.style.top = event.clientY + scrollTop + "px";
////                        eleFlyElement.style.visibility = "visible";
////                        numberItem = parseInt(document.querySelector(".fnb_fullinput").value) + parseInt(document.querySelector(".mini_cart_quantity").innerHTML);
////                        // 需要重定位
////                        myParabola.position().move();
//alert("添加成功");
//                    }
//                }
//            },
//            error: function () {}
//        });
//    });



});