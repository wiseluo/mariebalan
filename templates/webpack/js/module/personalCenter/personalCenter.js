/**
 * Created by chen on 2017/11/1.
 */
WX_Platform_Load([
    "laydate",
    "parert",
    WX_Platform.file("style!./css/module/index/index"),
    WX_Platform.file("style!./css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!./css/module/index/global"),
    // index 下的 newindex
    WX_Platform.file("style!./css/module/personalCenter/personalCenter")

], function() {
    //手风琴左侧栏
    var Accordion = function(el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

        // Variables privadas
        var links = this.el.find('.link');
        // Evento
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
    };

    Accordion.prototype.dropdown = function(e) {
        var $el = e.data.el;
        $this = $(this);
        $next = $this.next();

        $next.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
        }
    };

    var accordion = new Accordion($('#accordion'), false);
    $('.submenu li').click(function () {
        $(this).addClass('current').siblings('li').removeClass('current');
    });
    //点击上传头像
    $(".loadimg").click( function(){

        $(".file").click();
    })

});
