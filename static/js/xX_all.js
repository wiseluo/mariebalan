/**
 * Created by Administrator on 2017/3/31.
 */
$(function(){
   $('.extra .sort-select').hover(function(){
       $(this).find('.others').show();
   },function(){
       $(this).find('.others').hide();
   });

    // 点赞的效果 请求ajax
    $('.J-nice').click(function(){
        $('.j-m-zan').addClass('hide');
        $('.y-m-zan').removeClass('hide')
    });

    // 图片的放大效果
    $('.comment-column').on('click','.pic-list a',function(){
        var all_a=$(this).length;
        var th_eq=$(this).index();
        var th_src=$(this).find('img').attr('src');
        $(this).addClass('current').siblings().removeClass('current');
        $(this).parent().next().removeClass('hide');
        $(this).parent().next().find('.pic-view img').attr('src',th_src);
    });
    //左右转动
        var th=1;
        var dan=0;
    //$('.pic-op a:nth-child(1)').click(function(){
    //    th--;
    //    if(th<0){
    //        th=3;
    //    }
    //    th= Math.abs(th);
    //    var rotate='rotate'+'('+th*90+'deg'+')';
    //    $('.pic-view img').css({
    //        transform:rotate
    //    })
    //    alert(th);
    //});
    //$('.pic-op a:nth-child(2)').click(function(){
    //    th++;
    //    if(th>=4){
    //        th=0;
    //    }
    //    var rotate='rotate'+'('+th*90+'deg'+')';
    //    $('.pic-view img').css({
    //        transform:rotate
    //    })
    //});
    $('.pic-op a').click(function(){
        var th_fasle=$(this).hasClass('turn-left');
        var rotate='rotate'+'('+th*90+'deg'+')';
        var this_img=$(this).parent().next();
        var th_parent=$(this).parent().parent();
        var wId=parseInt($(this_img).css('width'));
        var hEt= parseInt($(this_img).css('height'));
        var maR=parseInt(wId-hEt);
        var a_mar=Math.round(maR/2)+'px';
        if(th_fasle==true){
            th--;
            if(th<0){
                th=3;
            }
            th= Math.abs(th);
            $(this_img).css({
                transform:rotate
            })
        }else{
            th++;
            if(th>=4){
                th=0;
            }
            $(this_img).css({
                transform:rotate
            });
        }
        if(dan==0){
            dan++;
            $(th_parent).css({
                width:hEt,
                height:wId
            });
            $(this_img).css({
                'margin-left':'-'+a_mar,
                'margin-top':a_mar,
            })
        }else{
            dan--;
             $(th_parent).css({
                width:wId,
                height:hEt
            });
            $(this_img).css({
                'margin-left':'0px',
                'margin-top':'0px'
            })
        }
    });
    //上一张下一张以及放大缩小
    $('.J-sprite-next').click(function(){
        // 图片个数
        var th_index=$(this).parent().parent().prev().find('a').length;
        var ge_A=$(this).parent().parent().prev().find('a');
        var cur=$(this).parent().parent().prev().find('.current').index();
        var at_false=$(this).hasClass('cursor-prev');
        if(at_false==true){
            if(cur==0){
                //$(this).parent().find('.cursor-prev').hide()
                //$(this).parent().find('.cursor-next').show();
            }else{
                $(ge_A).eq(cur-1).trigger('click')
                //$(this).parent().find('.cursor-prev').show();
                //$(this).parent().find('.cursor-next').show();
            }
        }else{
            if(cur+1>=th_index){
                //$(this).parent().find('.cursor-next').hide()
                //$(this).parent().find('.cursor-prev').show();
            }else{
                $(ge_A).eq(cur+1).trigger('click')
                //$(this).parent().find('.cursor-next').show();
                //$(this).parent().find('.cursor-prev').show();
            }
        }
    })
    // 关闭
    $('.cursor-small').click(function(){
        $(this).parent().parent().addClass('hide');
    });
    // .on('click','li',function(){
    //     $(this).addClass('img-hover').siblings().removeClass('img-hover');
    //     var th_src=$(this).find('img').attr('data-img');
    //     var jqimg=$(this).find('img').attr('jqimg');
    //     $(this).parents('.spec-list').prev().find('img').attr('src',th_src);
    //     $(this).parents('.spec-list').prev().find('img').attr('jqimg',jqimg);
    //
    // })

    //  商品大图hover 以及 点击的 判断
        $('.spec-items').on('mouseenter','li',function(){
            $(this).addClass('img-hover').siblings().removeClass('img-hover');
            var th_src=$(this).find('img').attr('data-img');
            var jqimg=$(this).find('img').attr('jqimg');
            $(this).parents('.spec-list').prev().find('.getimg').attr('src',th_src).attr('jqimg',jqimg);

        });
    //
    //$('.ad_go_ward').click(function(){
    //    // 所有的img的数量
    //    var th_is=$('.spec-items .lh li');
    //    var all_li=$('.spec-items .lh li').length;
    //    var th_eq=$('.img-hover').index();
    //    var go_width=parseInt($('.spec-items').css('width')+4);
    //    go_width=go_width+'px;'
    //    var th_true=$(this).hasClass('arrow-prev');
    //    if(all_li>5){
    //    // 当前 个数超过5个img
    //        if(th_true){
    //            $(this).addClass('disabled')
    //            $('.arrow-next').removeClass('disabled')
    //            $('.spec-items .lh').css({
    //                position: 'relative',
    //                left:'0px',
    //            })
    //            $(th_is).eq(4).trigger('click')
    //        }else{
    //            $(this).addClass('disabled');
    //            $(th_is).eq(5).trigger('click')
    //            $('.arrow-prev').removeClass('disabled')
    //            $('.spec-items .lh').css({
    //                position: 'relative',
    //                left:'-294px',
    //            })
    //        }
    //    }
    //    else{
    //        $('.ad_go_ward').addClass('disabled')
    //    }
    //
    //})
    // 图片上下滚动

    var count = $(".lh li").length - 5; /* 显示 6 个 li标签内容 */

    var interval = $(".img-hover").width();

    var curIndex = 0;

    $('.ad_go_ward').click(function(){

        if( $(this).hasClass('disabled') ) return false;



        if ($(this).hasClass('arrow-prev')) --curIndex;

        else ++curIndex;



        $('.ad_go_ward').removeClass('disabled');

        if (curIndex == 0) $('.arrow-prev').addClass('disabled');

        if (curIndex == count-1) $('.arrow-next').addClass('disabled');

        $(".spec-items .lh").stop(false, true).animate({"marginLeft" : -curIndex*interval + "px"}, 100);

    });
    //function preview(img){
    //    $("#preview .jqzoom img").attr("src",$('.lh img').attr("src"));
    //    $("#preview .jqzoom img").attr("jqimg",$('.lh img').attr("bimg"));
    //}

    //$(function(){
    //    $(".jqzoom").jqueryzoom({xzoom:540,yzoom:540});
    //});


    $('.p-num').on('click','.act a',function(){
        var val= $(this).parent().prev().val();
        var th_tr=$(this).hasClass('add');
        if(th_tr){
            val++;
            $(this).next().removeClass('z-disable');
        }else{
            val--;
            if(val<=1){val=1;
            $(this).addClass('z-disable');
            }
        }
        $(this).parent().prev().val(val);
    })
    // 输入框判断事件
    $('.num').keyup(function(){
        if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}
        if(this.value==""){this.value=1;}
    })
    /* 积分商城 */
    $('.first-slider .slider-main').anoSlide(
        {
            items: 1,
            speed: 500,
            prev: 'a.slider-prev',
            next: 'a.slider-next',
            lazy: true,
            auto: 10000,
            onConstruct: function(instance)
            {
                var paging = $('<div/>').addClass('slider-trigger').css(
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
                var paging = $('.slider-trigger');

                paging.find('a').eq(ui.instance.current).addClass('curr').addClass('curr').siblings().removeClass('curr');
            }
        });
        //当前的图片点击  放大镜
        $('.jqzoom.main-img').click(function(){
            $('.ks-overlay-mask').show();
            $('.ui-dialog').css('display','block');
        }).mousemove(function(a){
            var evt = a || window.event
            $('.the-big').css('display','block').children('img').attr('src',$(this).find('.getimg').attr('jqimg'));
            var ot = evt.clientY-($(this).offset().top- $(document).scrollTop())-87;
            var ol = evt.clientX-($(this).offset().left- $(document).scrollLeft())-87;
            if(ol<=0){
                ol = 0;
            }
            if(ot<=0){
                ot = 0;
            }
            if(ol>=175){
                ol=175
            }
            if(ot>=175){
                ot=175
            }
            $(this).find('span').css({'left':ol,'top':ot})
            var ott = ot/350*800
            var oll = ol/350*800
            $('.the-big img').css({'left':-oll,'top':-ott})
        }).mouseout(function(){
            $('.the-big').hide();
        })

    //  商品主页的input效果
        $('.clear_all').click(function(){
            $('#ui-dialog').css('display','none');
            $('.ks-overlay-mask').hide();
        })
    //  商品大图hover 以及 点击的 判断
    $('#side-list ul').on('click','li',function(){
        $(this).addClass('img-hover').siblings().removeClass('img-hover');
        var th_src=$(this).find('img').attr('data-img');
        var big_src=$(this).find('img').attr('data-big-img');
        $(this).parents('.layer-side').prev().find('#popup-big-img').attr('src',th_src);
        $(this).parents('.layer-side').prev().find('.zoomImg').attr('src',big_src);
        //获取当前的最大的图

    }).on('mouseenter','li',function(){
        $(this).addClass('img-hover').siblings().removeClass('img-hover');
        var th_src=$(this).find('img').attr('data-img');
        var big_src=$(this).find('img').attr('data-big-img');
        $(this).parents('.layer-side').prev().find('#popup-big-img').attr('src',th_src);
        $(this).parents('.layer-side').prev().find('.zoomImg').attr('src',big_src);

    });
    //如果有上下页
    var ddd=0;
    $('.list-page a').click(function(){

        if($(this).hasClass('disabled')){ return false;}
        var eeq= $('#side-list ul li ').length;
        var eq_x=Math.round(eeq/6);

        var arue=$(this).hasClass('next');
       if(eeq>6){
           if(arue){

               ddd++;
              var Top= '-'+ddd*230;
               $('#side-list').children('ul').css('transform','translateY'+'('+Top+'px'+')');
               if(ddd==(eq_x-1)){
                   $(this).addClass('disabled');
                   $('.list-page .prev').removeClass('disabled');
                   ddd=eq_x-1;
                   return false;
               }
           }else{
               ddd--;
               if(ddd<0){return false;}
               var Bottom=ddd*230;
               $('#side-list').children('ul').css('transform','translateY'+'('+Bottom+'px'+')');
               if(ddd==0){
                   $(this).addClass('disabled');
                   $('.list-page .next').removeClass('disabled');
                   return false;
               }
           }
       }
    })
});
