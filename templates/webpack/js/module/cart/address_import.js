/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "style!./css/module/cart/address_import"
], function () {

    /**
     * 下一步按钮
     */
    $("#btn-success").click(function () {
        var a = $('.js-txt-truename').val(),
            b = $('.js-txt-telphone').val(),
            g = $('#tipinput').val();
        if($("input[type=radio]").length>0){//是否有已存在的客户，选择
            if ($("input[type=radio]:checked").length>0) {
                var customer_id =$("input[type=radio]:checked").attr("customer_id");
                var html="<input type='hidden' name='customer_id' value='"+$("input[type=radio]:checked").attr("customer_id")+"'/>";
                $("#form-with-tooltip").append(html);
                $("#form-with-tooltip").submit();
                return false;
            }else{
                return alertTips('.js-txt-truename','请选择客户！');
            }
        }else{
            if (!a) return alertTips('.js-txt-truename','请填写姓名');
            if (!b) return alertTips('.js-txt-telphone','请填写手机号码');
            if (!/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[579])[0-9]{8}$/.test( b )) return alertTips('.js-txt-telphone','请填写正确格式的手机号码');
            if (!g) return alertTips('#tipinput','请填写详细地址');
            $("#form-with-tooltip").submit();
        }
        
    });

    $(document).on("click","#go_back_btn",function () {
        $("#delivery_modal").children().remove();
        $("#receipt_form").show();
    });

    $(document).on("click","#add_reciept_btn",function () {
        $("#delivery_modal").children().remove();
        $("#receipt_form").show();
        $("#form-with-tooltip")[0].reset();
    });

    /**
     * 输入手机号获取已存在的地址
     */
    $("#doc-mobile").on('blur', function () {        
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
                        $("#delivery_modal").append(response.htmlData);
                        $("#delivery_modal").show();
                        $("#receipt_form").hide();
                    }
                }
            }
        });

    });

    /**
     * 已存在的地址进行删除
     */
    $(".del_receipt_btn").on('click', function () {
        var address_id = $(this).attr('address_id');
        var p_obj = $(this).parent('li');
        var p_p_obj = $(this).parent('ul');
        $.ajax({
            type: 'POST',
            url: '/Order/del_receipt.html',
            data: {id: address_id},
            success: function (response) {
                if (response.status == 1) {
                    p_obj.remove();
                    if (p_p_obj.html() == "\n    ") {
                        $("#receipt_form").show();
                        p_p_obj.next().hide();
                    }
                }
            }
        });
    });

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
});