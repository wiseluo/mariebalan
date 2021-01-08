
WX_Platform_Load([
    "cityPicker",
    "citydata",
    'parsley',
    WX_Platform.file("style!plugins/select2/css/select2"),
    WX_Platform.file("style!./css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!./css/module/personalCenter/personalCenter"),
    WX_Platform.file("style!./css/module/personalCenter/usrs"),
], function () {
    // 三级
    //模拟城市-联动/搜索
    $('#city-picker-search').cityPicker({
        dataJson: cityData,
        renderMode: true,
        search: true,
        linkage: true
    });

    // 验证提交
    // 提交判断
    $('.btn-9').click(function(){
        checkUser();
    })
    
    function checkUser(){
        $('.addForm').parsley();
        var result = document.getElementById("consignee_name").value;
        var password = document.getElementById("consignee_name").value;
        //
        // if(result == ""  ){
        //     alert("用户名不能为空");
        //     return false;
        // }
        // if(password == ""  ){
        //     alert("密码不能为空");
        //     return false;
        // }

        $('.ui-dialog').addClass('hide');
        $('.m_mask').hide();
        $('.addForm').submit();
    }

    $('<div class="m_mask"></div>').appendTo($('body'));
    var maskWidth = $(document).width();
    var maskHight = $(document).height();
    // 修改信息地址
    $('.add_for_go').click(function(){
        $('.ui-dialog').removeClass('hide');
        $('body').addClass('jin_over');
        $('.m_mask').show();
    // //    .css({
    //     'width':maskWidth,
    //         'height':maskHight,
    //         'opacity':0.3,
    });
    // 新增地址
    $('.add-btn').click(function(){
        $('.ui-dialog').removeClass('hide');
        $('body').addClass('jin_over');
        $('.m_mask').show();
    });

    //
    $('.clear_for_X').click(function(){
        clear_for_X();
    })
    function clear_for_X(){
        $('.ui-dialog').addClass('hide');
        $('body').removeClass('jin_over');
        $('.m_mask').hide();
    }

    $('.subside-mod .title').click(function(){
        var tt=$(this).next('.subside-cnt');
        tt.toggleClass("aa_show");
    });
    $('.show_banner ').hover(function(){
        $('#drop-down-menu').show();
        $(this).find('.iconfont').removeClass('icon-xia-copy').addClass('icon-shang')
    },function(){
        $('#drop-down-menu').hide();
        $(this).find('.iconfont').removeClass('icon-shang').addClass('icon-xia-copy');
    });
    $('.subside-cnt ul').on('click','.list-item',function(){
        $(this).addClass('current').siblings().removeClass('current');
    })

    //   设置默认
    $('.approve').click(function(){
        //
        $('.smt').find('.ftx-04').addClass('hide');
        $(this).parents('.smc').prev().find('.ftx-04.ml10 ').removeClass('hide');
        $('.approve').removeClass('hide');$(this).addClass('hide');
        var eq=$(this).parents('.easebuy-m');
        var ge=$('.aB-content').find('.easebuy-m:eq(0)');
        $(eq).insertBefore( ge );
        $('body').animate( {scrollTop: 0}, 500);
    });

    //删除按钮
    $('.smt > .extra').click(function(){
        //询问框
        var th_dom= $(this).parent().parent()

        layer.confirm('您确定要删除该地址吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $(th_dom).remove();
            layer.msg('删除成功', {icon: 1});
        });

    });


});
