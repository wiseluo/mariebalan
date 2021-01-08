/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "form",
    WX_Platform.file("style!./css/module/login/login"),
    WX_Platform.file("style!./css/module/index/global")
], function () {
    /*表单验证*/

    $(document).ready(function () {
        // if ($.cookie("rmbUser") == "true") {
        // $("#ck_rmbUser").attr("checked", true);
        // $("#txt_username").val($.cookie("username"));
        // $("#txt_password").val($.cookie("password"));
        // }
    });

    //记住用户名密码
    function Save() {
        if ($("#ck_rmbUser").attr("checked")) {
            var str_username = $("#login_user_name").val();
            var str_password = $("#login_password").val();
            $.cookie("rmbUser", "true", { expires: 7 }); //存储一个带7天期限的cookie
            $.cookie("username", str_username, { expires: 7 });
            $.cookie("password", str_password, { expires: 7 });
        }
        else {
            $.cookie("rmbUser", "false", { expire: -1 });
            $.cookie("username", "", { expires: -1 });
            $.cookie("password", "", { expires: -1 });
        }
    };

    $(".putoup").click(function(){
        $('.this_user_name').show();
        $('.this_user_password').show();
        $('.this_signup_phone').hide();
        $('.this_signup_yzm').hide();
        $('.signup').show();
        $('.putoup').hide();
        $('.treaty_title').hide();
    });
    $(".signup").click(function(){
        $('.this_user_name').hide();
        $('.this_user_password').hide();
        $('.this_signup_phone').show();
        $('.treaty_title').show();
        $('.this_signup_yzm').show();
        $('.signup').hide();
        $('.putoup').show();
        $('#phone').focus();
    });
// 记录时间
    var countdown=60;
    $('#yzm_button').click(function(){
        settime($(this));
    })
    function settime(val) {
        if(! $('.this_signup_phone .input').hasClass('right'))
        {
            layer.msg('请先填写正确的手机号');
            return false;
        }
        if (countdown == 0) {
            val.removeAttribute("disabled");
            val.value="点击重新发送";
            countdown = 60;
            return false;
        } else {
            val.setAttribute("disabled", true);
            val.value="重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function() {
            settime(val)
        },1000)
    }
    $("#password").keyup(function(){
        var length=this.value.length;
        if(length>18){
            $('.baner_anquan_1').css('background-color','#5bab3c')
            $('.baner_anquan_2').css('background-color','#5bab3c')
            $('.baner_anquan_3').css('background-color','#5bab3c')
        }
        else if(length>12){
            $('.baner_anquan_1').css('background-color','#FF8900')
            $('.baner_anquan_2').css('background-color','#FF8900')
            $('.baner_anquan_3').css('background-color','#EEE')
        }
        else if(length>4){
            $('.baner_anquan_1').css('background-color','#f76120')
            $('.baner_anquan_2').css('background-color','#eee')
            $('.baner_anquan_3').css('background-color','#EEE')
        }else{
            $('.baner_anquan_2').css('background-color','#eee')
            $('.baner_anquan_1').css('background-color','#eee')
            $('.baner_anquan_3').css('background-color','#EEE')
        }
    });

});
