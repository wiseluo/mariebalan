/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    "location",
    WX_Platform.file("style!./css/module/index/global"),
    WX_Platform.file("style!./css/module/shopCon/shopCart"),

], function () {
    $("#selectAll").click(function () {
        $("input:checkbox").attr("checked", "checked");
    });
    // 输入框判断事件
    $('.num').keyup(function () {
        if (this.value.length == 1) {
            this.value = this.value.replace(/[^1-9]/g, '')
        } else {
            this.value = this.value.replace(/\D/g, '')
        }
        if (this.value == "") {
            this.value = 1;
        }
    })
    //  购物车的数量
    $('.quantity-form').on('click', 'span', function () {

        var $th = $(this);
        var br = $th.parents('.gooditm').find('.jqcheckbox');
        if (!br.is(":checked")) {
            br.prop('checked', true);
        }
        var th_true = $th.hasClass('decrement');
        // 当前商品件数
        var new_num = $th.parent().find('input.num').val();
        var Num = parseInt(new_num);
        if (th_true) {
            if (Num > 1)Num = Num - 1;
            $th.addClass('disabled');
            $th.next().val(Num);
        } else {
            Num = Num + 1;
            $th.parent().find('.decrement').removeClass('disabled');
            $th.prev().val(Num);
        }
        toDCG($th, Num)
    });
    // 手动输入的时候
    $('.ipt.num').blur(function () {
        var $th = $(this);
        var br = $th.parents('.gooditm').find('.jqcheckbox');
        if (!br.is(":checked")) {
            br.prop('checked', true);
        }
        var Num = $th.val();
        toDCG($th, Num);
    });
    // 计算当前总件数
    function jian() {
        //商品中件数
        var con = $('.b_number');
        con.html(0);
        var jianshu = $('.gooditm').find("input[type='checkbox']:checked");
        var alljian = 0;
        jianshu.each(function () {
            var b = $(this).parents('.gooditm').find('.ipt.num').val();
            alljian += parseInt(b);
        })
        con.html(alljian);
    }

    // 封装当前计算方法
    function toDCG($th, $Num) {
        //$th 当前对象 $Num 当前件数
        // 单个商品的单价
        var dj = $th.parents('.col4').prev().find('.newprice span').text();
        // 单个商品的总额
        var all = $th.parents('.col4').next().find('.sumrow');
        var s = 0, d = 0;
        s += $Num * dj;
        jian()
        $(all).text(s.toFixed(2));
        //总价容器
        var thallsum = 0;
        //单件商品的总价格
        var thsumrow = $th.parents('.goods').find('.sumrow');
        //商店的总价格
        var cartinfo = $th.parents('.m-goods').next('.cartinfo').find('.totalnum');
        $(thsumrow).each(function () {
            thallsum += parseFloat($(this).html());
        });
        //同个店铺下总额数
        $(cartinfo).text(toDecimal(thallsum));
        //全部店铺下的商品总额
        var total = $th.parents('.shoppingbox').find('.w_sum');
        var tt = $('.shoppingbox').find('.totalnum');

        tt.each(function () {
            d += parseFloat($(this).html());
        })
        total.html(toDecimal(d))
    }

    // 取小数值
    function toDecimal(x) {
        var f = parseFloat(x);
        if (isNaN(f)) {
            return;
        }
        f = Math.round(x * 100) / 100;
        if (f % 1 === 0) {
            f += '.00';
        } else {
            f += '00';        //在字符串末尾补零
            f = f.match(/\d+\.\d{2}/)[0];
        }
        return f;
    }

    // 勾选 取消的操作
    $('.m-goods').on('click', 'li .jqcheckbox', function () {
        var $th = $(this);
        var all = $th.parents('.m-goods').find("input[type=checkbox]");
        var my = all.not("input:checked");
        var ahi = $th.parents('.m-goods').find("input[type='checkbox']:checked");
        if ($th.is(":checked")) {
            //没有选中的
            if (my.length == "0") {
                $th.parents('.goods').prev().find("input[type='checkbox']").prop('checked', true);
            }
        } else {

            if (ahi.length < all.length) {
                $th.parents('.goods').prev().find("input[type='checkbox']").prop('checked', false);
            }
        }
        count($th);
        //
    });
    // 判断商店选中
    $('.m-cart  .shop.cart-checkbox').find('.jqcheckbox').click(function () {
        if ($(this).is(":checked")) {
            setPrice();
            $(this).parents('.m-cart').find('.jqcheckbox').prop('checked', true);
        } else {
            $(this).parents('.goods').find('.totalnum').html("0.00");
            $(this).parents('.m-cart').find('.jqcheckbox').prop('checked', false);
            var jg = $(this).parents('.m-cart').find('.jqcheckbox');
            jg.each(function () {
                count($(this))
            })
        }
        jian()
    });
    // 计算所有选中产品总价
    function setPrice() {
        var total = 0;
        var checkTrue = $(".m-goods .jqcheckbox");
        for (var i = 0; i < checkTrue.length; i++) {
            total = parseFloat(total) +
                parseFloat(checkTrue.eq(i).parents('.gooditm').find('.sumrow').html());
            var $th = checkTrue.eq(i), Num = checkTrue.eq(i).parents('.gooditm').find('.ipt.num').val();

        }
        toDCG($th, Num);
        jian();
        $('.allmoney .w_sum').html(toDecimal(total));
    }

    // 单选计算总合计
    function count($th) {
        var total = $('.allmoney .w_sum').html();
        if ($th.is(":checked")) {
            // 商品总额
            total = parseInt(total) + parseFloat($th.parents('.gooditm').find('.sumrow').html());
            $('.allmoney .w_sum').html(toDecimal(total));
        } else {
            // 取消操作的时候的操作
            total = parseInt(total) - parseFloat($th.parents('.gooditm').find('.sumrow').html());
            $('.allmoney .w_sum').html(toDecimal(total));
        }
        jian();
    }

    // 页面加载后 执行一次商品全选
    $(document).ready(function () {
        var all = $('.m-goods').find("input[type='checkbox']:checked");
        all.each(function () {
            var Num = $(this).parents('.gooditm').find('.ipt.num').val();
            toDCG($(this), Num);
        })
    })
    // 商品的提交按钮
    $('.gobuy').click(function () {
        var le = $('.gooditm').find("input[type='checkbox']:checked");
        if (le.length == "0") {
            layer.alert('请至少选择一项商品');
            return false;
        } else {
            // ajax 提交
        }
    });
    //判断总选中
    $('.cart-thead .t-checkbox ,.m-total .u-chk').click(function () {
        if ($(this).is(":checked")) {
            setPrice();
            $(this).parents('.shoppingbox').find('.jqcheckbox').prop('checked', true);
            $('.t-checkbox ,.u-chk').prop('checked', true);
        } else {
            $(this).parents('.shoppingbox').find('.jqcheckbox').prop('checked', false);
            $('.t-checkbox ,.u-chk').prop('checked', false);
            $('.allmoney .w_sum').html('0.00')

        }
        jian();

    });

    //单独删除的操作
    $('.col6 .u-opt').click(function () {
        var $th = $(this);
        layer.confirm('您是否删除选中订单？', {
            btn: ['删除', '取消'] //按钮
        }, function () {
            del($th);
        }, function () {

        });

    });
    //底部总选中 删除
    $('.ttbar .opt').click(function () {
        layer.confirm('您是否删除选中订单？', {
            btn: ['删除', '取消'] //按钮
        }, function () {
            $('.m-goods .jqcheckbox').each(function () {
                var $th = $(this);
                if ($th.is(":checked")) {
                    del($th);
                }
            })
        }, function () {

        });

    });

    // 删除
    function del($th) {
        // 当前 的商店
        var thshop = $($th).parents('.m-cart');
        var le = $($th).parents('.m-goods').find('.gooditm').length;
        if (le == '1') {
            thshop.remove();
        }
        $($th).parents('.gooditm').remove();
        layer.msg('删除成功', {icon: 1});
    }


    $(".m-actlabel").mouseenter(function () {
        $(this).find(".layer").show();
        $(this).find('.icon-arrow-down').attr("class", 'icon-arrow-up');
        $(this).css({'background': '#c00', 'color': '#fff'})
    });
    $(".m-actlabel").bind('mouseleave', function () {
        $(this).find(".layer").hide();
        $(this).find('.icon-arrow-down').attr("class", 'icon-arrow-down');
        $(this).css({'background': '#fff', 'color': '#c00'})
    })
    $(".u-taxval").mouseenter(function () {

        $(this).find('.poptax').show();
    })
    $(".u-taxval").bind('mouseleave', function () {
        $(this).find('.poptax').hide();
    })
    // 切换tab
    $('.noticetitle a').click(function () {
        if ($(this).attr('id') == "one1") {
            setTab('one', 1, 2);
        } else {
            setTab('one', 2, 2)
        }
    })

    function setTab(name, cursel, n) {
        for (i = 1; i <= n; i++) {
            var menu = document.getElementById(name + i);
            var con = document.getElementById("con_" + name + "_" + i);
            menu.className = i == cursel ? "hover1" : "";
            con.style.display = i == cursel ? "block" : "none";
        }
    }

});
