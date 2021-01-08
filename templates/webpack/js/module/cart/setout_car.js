WX_Platform_Load([
    "jquery-anoslide",
    "style!./css/module/index/newindex",
    "style!./css/module/index/com",
    "style!./css/module/cart/newcart",
],function(){
    $('#similar .s-panel-main').anoSlide(
        {
            items: 1,
            speed: 500,
            lazy: true,
            auto: 10000,
            onConstruct: function(instance)
            {
                var paging = $('<div/>').addClass('s-panel-nav ').css(
                    {
                        position: 'absolute',
                        left:50 + '%',
                        width: instance.slides.length * 100,
                        marginLeft: -(instance.slides.length * 100)/2
                    })

                for (i = 0, l = instance.slides.length; i < l; i++)
                {
                    var a = $('<a/>').data('index', i).appendTo(paging).on(
                        {
                            click: function()
                            {
                                instance.stop().go($(this).data('index'));
                            }
                        });

                    if (i == instance.current)
                    {
                        a.addClass('curr');
                    }
                    var html=i+1;
                    a.html(html);
                }
                instance.element.parent().append(paging);
            },
            onStart: function(ui)
            {
                var paging = $('.s-panel-nav');

                paging.find('a').eq(ui.instance.current).addClass('curr').addClass('curr').siblings().removeClass('curr');
            }
        });
    $('#promotion .s-panel-main').anoSlide(
        {
            items: 1,
            speed: 500,
            lazy: true,
            auto: 10000,
            onConstruct: function(instance)
            {
                var paging = $('<div/>').addClass('s-panel-nav-2 ').css(
                    {
                        position: 'absolute',
                        left:50 + '%',
                        width: instance.slides.length * 100,
                        marginLeft: -(instance.slides.length * 100)/2
                    })

                for (i = 0, l = instance.slides.length; i < l; i++)
                {
                    var a = $('<a/>').data('index', i).appendTo(paging).on(
                        {
                            click: function()
                            {
                                instance.stop().go($(this).data('index'));
                            }
                        });

                    if (i == instance.current)
                    {
                        a.addClass('curr');
                    }
                    var html=i+1;
                    a.html(html);
                }
                instance.element.parent().append(paging);
            },
            onStart: function(ui)
            {
                var paging = $('.s-panel-nav-2');

                paging.find('a').eq(ui.instance.current).addClass('curr').addClass('curr').siblings().removeClass('curr');
            }
        });

    /**
     * 新增地址
     */
//     $(".login_btn").click(function () {
//         var a = $('.js-txt-truename').val(),
//             b = $('.js-txt-telphone').val(),
//             g = $('#tipinput').val();
//         if (!a) return alertTips('.js-txt-truename','请填写姓名');
//         if (!b) return alertTips('.js-txt-telphone','请填写手机号码');
//         if (!/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|14[57])[0-9]{8}$/.test( b )) return alertTips('.js-txt-telphone','请填写正确格式的手机号码');
//         if (!g) return alertTips('#tipinput','请填写详细地址');
//         $("#form-with-tooltip").submit();
//     });
// });
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