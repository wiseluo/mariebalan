/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    "jquery-anoslide",
    "parert",
    "LiftEffect",
    WX_Platform.file("style!./css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!./css/module/login/login"),
    WX_Platform.file("style!./css/module/index/global"),
    WX_Platform.file("style!./css/module/help/help"),
], function() {
    /*表单验证*/
    function alertTips(className,errorText){
        var innerText = '';
        $(className).focus();
        if (errorText) innerText = errorText;
        layer.msg(innerText);
        return false;
    }
    // 投诉页面的界面
    $('#btnSubmit').click(function(){
        var isnum=/^1[34578]\d{9}$/,
            reg=/[a-zA-Z0-9]{1,10}@[a-zA-Z0-9]{1,5}\.[a-zA-Z0-9]{1,5}/;
        var a = $('#comp_title').val(),
            b = $('#targetComplaint').val(),
            c = $('#orderCode').val(),
            f = $('#comp_content').val();
        if (!a){
            return alertTips('#comp_title', '请填写投诉主题');
        }
        if (!b){
            return alertTips('#targetComplaint', '请填写投诉对像');
        }
        if (!c){
            return alertTips('#orderCode', '请填写涉及订单的编号');
        }

        if (!f){
            return alertTips('#comp_content', '请填写投诉内容');
        }
        var reg = {};

        reg['comp_title'] = a;
        reg['targetComplaint'] = b;
        reg['orderCode'] = c;
        reg['comp_content'] = f;

        $.ajax({
            type:'post',
            url:"/ucenter/register/register",
            data:reg,
            success:function(data){
                console.log(data);
                if(data.code =="200")
                {
                    layer.msg(data.msg,{time:1000},function(){
                        window.location.href="/ucenter/login"
                    });


                }
            }

        });
    });


});
