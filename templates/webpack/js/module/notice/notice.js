/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    "jquery-anoslide",
    "parert",
    "LiftEffect",
    WX_Platform.file("style!./css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!./css/module/index/global"),
    // index 下的 newindex
    WX_Platform.file("style!./css/module/index/newindex"),
    WX_Platform.file("style!./css/module/notice/notice"),

], function() {

    // 轮播
    $(' .oUlplay').anoSlide(
        {
            items: 1,
            speed: 500,
            prev: 'div.pre',
            next: 'div.next',
            lazy: true,
            auto: 5000,
            onConstruct: function(instance)
            {
                var paging = $('<div/>').addClass('paging').css(
                    {
                        position: 'absolute',
                        left:50 + '%',
                        width: instance.slides.length * 25,
                        marginLeft: -(instance.slides.length * 40)/3
                    })

                /* Build paging */
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
                }

                instance.element.parent().append(paging);
            },
            onStart: function(ui)
            {
                var paging = $('.paging');

                paging.find('a').eq(ui.instance.current).addClass('curr').addClass('curr').siblings().removeClass('curr');
            }
        });


});
