/**
 * Created by chen on 2017/11/6.
 */
WX_Platform_Load([
    WX_Platform.file("style!plugins/select2/css/select2"),
    WX_Platform.file("style!./css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!./css/module/collect/collect"),
    WX_Platform.file("style!./css/module/personalCenter/personalCenter"),
    WX_Platform.file("style!./css/module/personalCenter/usrs"),

], function() {
    $(".good").hover(function (){
        $(this).children('.img').children('.imgbarlst').toggleClass("z-hover");
        $(this).children('.img').siblings('.icon-delete').toggleClass('hide')
    });
});
