/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    //图片懒加载
    "jquery-lazyload",
    "parert",
    // index 下的 global
    WX_Platform.file("style!./css/module/index/global"),
    // index 下的 newindex
    WX_Platform.file("style!./css/module/index/newindex"),
    WX_Platform.file("style!./css/module/index/index"),
    WX_Platform.file("style!./css/module/index/com"),
    WX_Platform.file("style!./css/module/classify/classify"),
], function() {

    // 商品 多选 更多
    $('.tp-more').on('click','a',function(){
        var $this=$(this).parent();
        // 多选
        if($(this).hasClass('f-check')){
            $this.hide();
            var thg=$this.prev().find('.v-btns');
            $(thg).css('display','block');
            $this.prev('.J-tp-value').find('ul').addClass('z-more')
            $this.prev('.tp-value').find('.v-lst li a').attr('href','javascript:void(0)');
        }
        // 更多
        else{
            if($(this).hasClass('f-more')){
                $(this).parents('.u-tp-item').toggleClass('show');
                $(this).removeClass('J_hide');
            }
        }
    });

    //列表的控制功能
    $('.v-lst ').on('click','li',function(){
        var th_a=$(this);
        var fa =$(this).parent()
        var ad=$(fa).find('.z-select').length;
        $(this).toggleClass('z-select');
        if(ad>=5){
            layer.msg('您最多选择五项');
            if($(th_a).hasClass('z-select')){
                $(th_a).removeClass('z-select');
            }
            return false;
        }
    });

    $('.tp-value').on('click','ul',function(){
        var fgh=$(this).find('.z-select').index();
        if(fgh==-1){
            $(this).next().find('.confirm ').addClass('z-disable');
        }
        else{
            $(this).next().find('.confirm ').removeClass('z-disable');
        }
    })
    $('.cancel ').click(function(){
        // 当前的a 标签链接改回去
        var th_href=$(this).parent().prev().find('li a').attr('data-href');
        $(this).parent().prev().find('li a').attr('href',th_href);
        // 修改掉样式
        $('.confirm ').addClass('z-disable');
        $('.tp-value li').removeClass('z-select');
        $(this).parent().prev().removeClass('z-more');
        $(this).parent().hide();
        $(this).parent().parent().next().css('display','block');

    })
});
