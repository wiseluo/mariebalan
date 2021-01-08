/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "style!./css/module/cart/add_address"
],function(){
    /**
     * 新增地址
     */
    $(".login_btn").click(function () {
        var a = $('.js-txt-truename').val(),
            b = $('.js-txt-telphone').val(),
            g = $('#tipinput').val();
        if (!a) return alertTips('.js-txt-truename','请填写姓名');
        if (!b) return alertTips('.js-txt-telphone','请填写手机号码');
        if (!/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/.test( b )) return alertTips('.js-txt-telphone','请填写正确格式的手机号码');
        if (!g) return alertTips('#tipinput','请填写详细地址');
        $("#form-with-tooltip").submit();
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