/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "form",
    WX_Platform.file("style!./css/module/login/login"),
    WX_Platform.file("style!./css/module/index/global")
], function () {
    // 记录时间
    var countdown=60;

    function settime(val) {
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


    //page4 密码验证
    $('#password').blur(function(){
        var new_password = $('#password').val();
        if(new_password.length < 6){
            $('.alter_password_con_1 .hint').css('visibility','visible')
            return false;
        }else{
            $('.alter_password_con_1 .hint').css('visibility','hidden')
        }
    });
    function editPassword() {
        var new_password = $('#password').val();
        var confirm_password = $('#repassword').val();
        if (new_password != confirm_password) {
            $('.alter_password_con_2 .hint2').css('visibility','visible')
            return false;

        }else{
            $('.alter_password_con_2 .hint2').css('visibility','hidden')
            return true;
        }
    }

    function all_title(){
        $('.triangle-left').show();
    }
    $('.triangle-left_clear').click(function(){
        $('.triangle-left').hide();
    });
});
