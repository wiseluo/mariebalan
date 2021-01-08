/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    "cityPicker",
    "citydata",
    'parsley',
    "style!./css/module/index/newindex",
    "style!./css/module/index/com",
    "style!./css/module/cart/newcart",
    "style!./css/module/payMoney/payMoney",
], function() {
    // 新增的弹出窗
    function new_city($th){
        //模拟城市-联动/搜索
        $th.cityPicker({
            dataJson: cityData,
            renderMode: true,
            search: true,
            linkage: true
        });
    }
    //  增加当前的遮罩层
    $('<div class="m_mask"></div>').appendTo($('body'));
    //编辑地址 修改地址
    $('.add_for_go').click(function(){
        var selector = $('#city-picker-search').cityPicker({
            dataJson: cityData,
            renderMode: true,
            search: true,
            linkage: true
        });
        selector.setCityVal([{
            'id': '430000',
            'name': '湖南省'
        }, {
            'id': '130500',
            'name': '邢台市'
        }, {
            'id': '130323',
            'name': '抚宁县'
        }]);
        $('.ui-dialog').removeClass('hide');
        $('body').addClass('jin_over');
        $('.m_mask').show();
    });
    //删除地址
    $('.del-consignee').click(function(){
        //询问框
        var th_dom= $(this).parents('.ui-switchable-panel-selected')
        layer.confirm('您确定要删除该地址吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $(th_dom).remove();
            layer.msg('删除成功', {icon: 1});
        });

    });
    // 新增地址
    $('.add-btn').click(function(){
        new_city($('#city-picker-search'));
        $('.ui-dialog').removeClass('hide');
        $('body').addClass('jin_over');
        $('.m_mask').show();
    });
    //设为默认
    $('.setdefault-consignee').click(function(){
        $('.consignee-item').removeClass('item-selected');
        $('.setdefault-consignee').removeClass('hide');
        $('.addr-default').addClass('hide');
        $('.del-consignee').removeClass('hide');
        $(this).next().next().addClass('hide');
        $(this).addClass('hide');
        $(this).parent().prev().prev().addClass('item-selected');
        $(this).parent().prev().find('.addr-default').removeClass('hide');
        // 地址 复制 到 订单详情
        var addr_info=$(this).parents('.ui-switchable-panel').find('.addr-info');

        var addr_name =$(this).parents('.ui-switchable-panel').find('.addr-name');

        var addr_tel =$(this).parents('.ui-switchable-panel').find('.addr-tel');

        var dz=$(' #sendAddr .dz');
        var name=$(' #sendMobile .name');
        var phone=$(' #sendMobile .phone');
        dz.html(addr_info.html());
        name.html(addr_name.html());
        phone.html(addr_tel.html());
        // **
        //     var eq=$(this).parents('.easebuy-m');
        //     var ge=$('.aB-content').find('.easebuy-m:eq(0)');
        //     $(eq).insertBefore( ge );
        //     $('body').animate( {scrollTop: 0}, 500);

    })

    //关闭弹出窗
    $('.clear_for_X').click(function(){
        clear_for_X();
    })
    function clear_for_X(){
        $('.ui-dialog').addClass('hide');
        $('body').removeClass('jin_over');
        $('.m_mask').hide();

    }

    //  使用优惠
    $('#virtualdiv>.step-tit').click(function(){
        $(this).next().toggle(1000);
        $(this).toggleClass('add_off');

    });
    // 展示全部地址
    $('#consigneeItemAllClick').click(function(){
        show_ConsigneeAll()
    });
    //隐藏全部地址
    $('#consigneeItemHideClick').click(function(){
        hide_ConsigneeAll()
    });
    function show_ConsigneeAll(){
        $('.consignee-scroll .consignee-cont.consignee-off').css('height','auto');
        $('.switch-off').removeClass('hide');
        $('.switch-on').addClass('hide')
    }
    function hide_ConsigneeAll(){
        $('.consignee-scroll .consignee-cont.consignee-off').css('height','42px');
        $('.switch-on').removeClass('hide');
        $('.switch-off').addClass('hide')
        var eq=  $(' .item-selected').parent();
        var gg=$('.ui-switchable-panel:eq(0)');
        $(eq).insertBefore( gg );
    }
    /**
     * 退换无忧浮层
     */
    $(".J-mode-infor-tips").hover(
        function() {
            $(this).find(".mode-infor-tips").show();
        },
        function() {
            $(this).find(".mode-infor-tips").hide();
        }
    );

});
