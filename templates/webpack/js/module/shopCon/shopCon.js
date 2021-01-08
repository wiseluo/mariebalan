/**
 * Created by Jia on 2017/11/2.
 */
WX_Platform_Load([
    "form",
    "jquery-anoslide",
    "jquery-zoom",
    "parert",

    WX_Platform.file("style!./css/module/login/login"),
    WX_Platform.file("style!./css/module/index/global"),
    WX_Platform.file("style!./css/module/index/com"),
    WX_Platform.file("style!./css/module/shopCon/shopCon"),
], function () {

    $('.in_title_head li').click(function(){
        var mTop = $('#detail')[0].offsetTop;
        $("body").animate({ scrollTop: mTop }, 500);
        var th_index= $(this).index();
        var all_con=$('.tab-con .pa_con')
        $(this).addClass('current').siblings().removeClass('current');
        //$('.tab-con .pa_con').addClass('hide').eq(th_index).removeClass('hide');
        if(th_index==0){
            all_con.removeClass('hide').eq(1).addClass('hide')
        }
        else if(th_index==1){
            all_con.removeClass('hide').eq(0).addClass('hide')
        }
        else if(th_index==2){
            all_con.addClass('hide').eq(2).removeClass('hide').eq(1).removeClass('hide')
        }
        else if(th_index==3){
            all_con.addClass('hide').eq(3).removeClass('hide');
        }

    });
//    <!--右侧导航的 按钮效果 -->
    //1： 回去总的高度

//购物选中的内容
    $('#choose-attr-3 .selected').click(function(){
        $('#choose-attr-3 .selected a').removeClass('active');
        $(this).find('a').addClass('active');
    });

    $('#choose-attr-1 .selected').click(function(){
        $('#choose-attr-1 .selected a').removeClass('active');
        $(this).find('a').addClass('active');
    });
    $('#choose-attrs .dd').click(function(){
        var ad_val=$('#choose-attr-1 .selected a.active').find('i').html();
        var cd_val=$('#choose-attr-3 .selected a.active').html();
        var th_html='';
        if(ad_val!=undefined&&ad_val!=''){
            $('#choose-results .dt').html('已选择')
            $('#choose-results .chooss_1').html(ad_val)
        }

        if(cd_val!=undefined&&cd_val!=''){
            $('#choose-results .dt').html('已选择')
            $('#choose-results .chooss_2').html(cd_val)
        }
    });
//    分类
    $('.show_banner').hover(function(){
        $('#drop-down-menu').show();
        $(this).find('.iconfont').removeClass('icon-xia-copy').addClass('icon-shang')
    },function(){
        $('#drop-down-menu').hide();
        $(this).find('.iconfont').removeClass('icon-shang').addClass('icon-xia-copy');
    });


    function reduce(){
        var th_val=$('#buy-num').val();
        th_val--;
        if(th_val<1){
            th_val=1;
        }
        if(th_val>=2){
            $('.btn-reduce').removeClass('disabled')
        }else{
            $('.btn-reduce').addClass('disabled')
        }
        $('#buy-num').val(th_val);
    }
    function add(){
        var th_val=$('#buy-num').val();
        th_val++;
        if(th_val>100){
            layer.msg('您最多可购买100'+'件')
            th_val=100;
        }
        if(th_val>=2){
            $('.btn-reduce').removeClass('disabled')
        }else{
            $('.btn-reduce').addClass('disabled')
        }
        $('#buy-num').val(th_val);
    }
    $(document).ready(function(){
        var winHeight = $(window).scrollTop();
        $(window).scroll(function() {
            var scrollY = $(document).scrollTop();
            //如果没有滚动到底部，不添加显示类，否添加
            //console.log(scrollY-690>winHeight)
            if(scrollY-888>winHeight){
                $('.tab-main.large ').css({
                    'position':'fixed',
                    'top':'0px'
                })
            }
            else {
                $('.tab-main.large').css('position','relative')
            }
        });
    });


    //  图片放大

    $('.layer-img').zoom();


    //右侧滚轮图片
    var th_aa=0;
    var th_he= parseInt($('.track-con ul').css('height'));
    var ad=Math.round(th_he/480);
    var ge_s=Math.round($('.track-con ul li').length);
    var c_he=(th_he/ge_s).toFixed(2);
    function sprite_down(){
        th_aa++;
        if(th_aa<=ad){
            var all= '-'+(th_aa*480)+'px';
            $('.track-con ul').css('top',all)
        }else{
            $('.track-con ul').css('top','0px');
            th_aa=0;
        }
    }
    function sprite_up(){
        th_aa--;
        if(th_aa<0){
            th_aa=6;
        }
        if(th_aa<=ad){
            var all= '-'+(th_aa*480)+'px';
            $('.track-con ul').css('top',all)
        }else{
            $('.track-con ul').css('top','0px');
            th_aa=0;
        }
    }

});
