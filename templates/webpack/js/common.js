/*! =========================================================
 * WX_Platform-common.js
 *
 * ========================================================= */

var contextPath;
if (window.basePath) {
    contextPath = window.basePath + "";
} else {
    contextPath = "../";
}

var WX_Platform = {
    version: "1.0.0",
    company: "",
    base: contextPath,
    debug: true, //是否为开发者模式,false使用.min文件
    file: function (path) {
        return path + (this.debug ? "" : ".min");
    },
    effect: 400
};
WX_Platform_Require.config({
    baseUrl: WX_Platform.base, //加载模型基本路径 ../assets
    map: {
        '*': {
            'style': WX_Platform.file("js/require-css")	//添加加载CSS插件
        }
    },
    paths: {// js文件路径
        "jquery": WX_Platform.file("plugins/jQuery/jquery-2.1.4/jquery"),
        "jQuery-simplepage": WX_Platform.file("plugins/jQuery-simplepage/js/jQuery-simplepage"),
        "jquery-weui-build": WX_Platform.file("plugins/jquery-weui-build/js/jquery-weui"),
        "jquery-lazyload": WX_Platform.file("plugins/jquery-lazyload/jquery.lazyload"),
        "jquery-anoslide": WX_Platform.file("plugins/jquery-anoslide/jquery.anoslide"),
        "jquery-zoom": WX_Platform.file("plugins/jquery-zoom/jquery.zoom"),
        "layer": WX_Platform.file("plugins/layer/layer/layer"),
        "laydate": WX_Platform.file("plugins/laydate/laydate"),
        "form": WX_Platform.file("js/module/login/login"),
        "parert": WX_Platform.file("plugins/parert"),
        'aminart':WX_Platform.file("plugins/aminart"),
        'location':WX_Platform.file("plugins/select2/location"),
        'select2':WX_Platform.file("plugins/select2/select2"),
        'select2_locale_zh-CN':WX_Platform.file("plugins/select2/select2_locale_zh-CN"),
        'citydata':WX_Platform.file("plugins/cityPicker/citydata"),
        'cityPicker':WX_Platform.file("plugins/cityPicker/cityPicker-1.0.0"),
        "canvas-echars": WX_Platform.file("plugins/canvas-echars/echarts"),
        "chosen-master": WX_Platform.file("plugins/chosen-master/amazeui.chosen"),
        "fileupload": WX_Platform.file("plugins/bootstrap-fileinput-master/js/fileinput"),
        "bootstrap": WX_Platform.file("plugins/bootstrap/js/bootstrap"),
        "dropload": WX_Platform.file("plugins/dropload/dropload1"),
        "amazeui": WX_Platform.file("plugins/amazeui/assets/js/amazeui"),
         "LiftEffect": WX_Platform.file("plugins/LiftEffect"),
         "location": WX_Platform.file("plugins/location/location"),
         "parsley": WX_Platform.file("plugins/parsley/parsley"),
    },
    shim: {// shim 依赖关系  css依赖    “style!xxx/xxx/A”
        "fileupload": [WX_Platform.file("style!plugins/bootstrap-fileinput-master/css/fileinput"), WX_Platform.file("style!plugins/bootstrap/css/bootstrap")],
        "layer": [WX_Platform.file("style!plugins/layer/layer/mobile/need/layer"), WX_Platform.file("style!plugins/layer/layer/theme/default/layer")],
        "parsley": WX_Platform.file("style!plugins/parsley/parsley"),
        "bootstrap": WX_Platform.file("style!plugins/bootstrap/css/bootstrap.min"),
        "select2": WX_Platform.file("style!plugins/select2/css/select2"),
        "jQuery-simplepage": WX_Platform.file("style!plugins/jQuery-simplepage/css/jQuery-simplepage"),
        "jquery-weui-build": [WX_Platform.file("style!plugins/jquery-weui-build/css/jquery-weui")],
        "weui": [WX_Platform.file("style!plugins/jquery-weui-build/css/weui")],
        "chosen-master": [WX_Platform.file("style!plugins/chosen-master/amazeui.chosen")],
        "dropload": [WX_Platform.file("style!plugins/dropload/css/dropload")],
        "cityPicker": [WX_Platform.file("style!plugins/cityPicker/city-picker")],
        "location": [WX_Platform.file("style!plugins/location/b_location")],
    }
});

var WX_Platform_Load = function (array, call) {
    WX_Platform_Require([
        "jquery",
        'layer',
        WX_Platform.file("style!css/animate"),
        WX_Platform.file("style!plugins/font-awesome/css/font-awesome"),
        WX_Platform.file("style!css/iconfont"),
        WX_Platform.file("style!css/module/index/global")
    ], function () {

        // 调用过渡
        $('#loading').fadeOut(WX_Platform.effect, function () {
            $(this).remove();
            $(".wrapper-content").css('opacity', 1);
        });
        WX_Platform_Require(array, call);
    });
};


/**
 *
 * @param {type} aim 需求添加数据的容器
 * @param {type} url 异步请求地址
 * @param {type} p 当前页数
 * @param {type} inp 搜索内容
 * @returns {undefined}
 */
function pullRefresh(aim, url, inp) {
    WX_Platform_Require([
        "jquery-weui-build",
        WX_Platform.file("style!plugins/jquery-weui-build/css/weui")
    ], function () {
        var load = ' <div class="weui-loadmore"><i class="weui-loading"></i><span class="weui-loadmore__tips">正在加载</span></div>';
        var loading = false;  //状态标记
        var ab = '<input type="hidden" value="2" id="thad-fy">';
        for (var i = 1; i < 2; i++) {
            $(document.body).append(ab);
        }
        var wh = $(aim).height();
        var bh = $(window).height();
        if (wh > bh) {
            $(document.body).append(load);
        }
        $(document.body).infinite().on("infinite", function () {
            var tf = parseFloat($("#thad-fy").val());
            if (loading)
                return;
            loading = true;
            // setTimeout(function() { }, 1000);   //模拟延迟
            //$(aim).append("<p> 我是新加载的内容 </p>");
            //loading = false;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    p: tf, //thad-fy 分页
                    search: inp
                },
                success: function (data) {
                    if (data.msg == "1") {
                        $(aim).append(data.html);
                        $("#thad-fy").val(tf + 1);
                        loading = false;
                    } else {
                        $('.weui-infinite-scroll').remove();
                        $(document.body).destroyInfinite();
                        $(aim).append("<p style='text-align: center;font-size: 1.5rem;'> 已经到底咯 </p>");
                    }
                },
                error: function () {
                    //$.alert("异常！");
                    $(aim).append("<p style='text-align: center;font-size: 1.5rem;'> 已经到底咯 </p>");
                }
            });

        });
    });

}
//弹窗
function layer(title, no, yes) {
    var lae =
        '<div class="layui-m-con">' +
        '<div class="layui-m-layershade"></div>' +
        ' <div class="layui-m-layermain">' +
        ' <div class="layui-m-layersection">' +
        '<div class="layui-m-layerchild">' +
        ' <div class="layui-m-layercont">' +
        title +
        '</div>' +
        ' <div class="layui-m-layerbtn">' +
        ' <span no data-type="0">' + no + '</span>' +
        '<span yes data-type="1">' + yes + '</span>' +
        ' </div>' +
        '</div>' +
        ' </div>' +
        ' </div>' +
        ' </div>';
    $('body').append(lae);

}
function ht(title) {
    var hn = '<div class="motify"><div class="motify-inner"></div></div>';
    $('body').append(hn);
    var $Motify = $('.motify'), innerText = title;
    $Motify.find('.motify-inner').text(innerText);
    $Motify.show();
    setTimeout(function () {
        $Motify.fadeOut();
    }, 1000);
}