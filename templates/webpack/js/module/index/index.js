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
    // index 下的 newindex
    WX_Platform.file("style!./css/module/index/newindex"),
    WX_Platform.file("style!./css/module/index/index"),

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


    // 切换tab
        $('.noticetitle a').click(function(){
            if($(this).attr('id')=="one1"){
               setTab('one',1,2);
            }else{
               setTab('one',2,2)
            }
        })

        function setTab(name,cursel,n){
            for(i=1;i<=n;i++){
                var menu=document.getElementById(name+i);
                var con=document.getElementById("con_"+name+"_"+i);
                menu.className=i==cursel?"hover1":"";
                con.style.display=i==cursel?"block":"none";
            }
        }



        // 楼层滚动
      

    $(document).ready(function(){
        var idArray = [];
            $("div.ftl-coc-list").each(function(){
                idArray.push("#"+$(this).attr("id"));
            });
              LiftEffect({
                    "control1": "#box",                          //侧栏电梯的容器
                    "control2": ".t_con",                           //需要遍历的电梯的父元素
                    "target": idArray,//监听的内容，注意一定要从小到大输入
                    "current": "active"                          //选中的样式
             });
        // 图片懒加载
        // $("img").lazyload({
        //     placeholder : "../../../images/loading.gif",
        //     effect: "fadeIn", // 载入使用何种效果
        //     threshold: 200, // 提前开始加载
        //     // threshold,值为数字,代表页面高度.如设置为200,表示滚动条在离目标位置还有200的高度时就开始加载图片,可以做到不让用户察觉
        //     container: $(".ftl-main"),  // 对某容器中的图片实现效果
        //     //failurelimit : 10 // 图片排序混乱时
        // });

        $("#goTop").click(function(){
            $('body,html').animate({"scrollTop":0},500)
        })
    });
});
