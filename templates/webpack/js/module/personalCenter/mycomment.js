/**
 * Created by chen on 2017/11/15.
 */
WX_Platform_Load([
    "laydate",
    WX_Platform.file("style!/static/index/plugins/select2/css/select2"),
    WX_Platform.file("style!/static/index/css/module/index/com"),
    // index 下的 global
    WX_Platform.file("style!/static/index/css/module/personalCenter/mycomment"),
    WX_Platform.file("style!/static/index/css/module/personalCenter/personalCenter"),
    WX_Platform.file("style!/static/index/css/module/personalCenter/usrs"),

], function() {
    // file_con
    $('.file_con').change(function(){
        selectImage($(this));
    });

    // 提交操作
    $('.close_button').click(function(){
    });
    $(document).ready(function(){
        var $dialog = $("#dialog");
        var $mask = $("#mask");

        //自动居中对话框
        function autoCenter(el){
            var bodyW = $(window).width();
            var bodyH = $(window).height();
            var elW = el.width();
            var elH = el.height();
            $dialog.css({"left":(bodyW-elW)/2 + 'px',"top":(bodyH-elH)/2+ 'px'});
//            $mask.css({"top":(bodyH)/2 + 'px'})
        };

        //点击弹出对话框
        $(".btn-def").click(function(){
            $dialog.css("display","block");
            $mask.css("display","block");
            autoCenter($dialog);
        });
        //弹出框关闭
        $('#move_part .fr').click(function(){
            $dialog.css("display","none");
            $mask.css("display","none");
        })
        //禁止选中对话框内容
        if(document.attachEvent) {//ie的事件监听，拖拽div时禁止选中内容，firefox与chrome已在css中设置过-moz-user-select: none; -webkit-user-select: none;
            g('dialog').attachEvent('onselectstart', function() {
                return false;
            });
        }
        //声明需要用到的变量
        var mx = 0,my = 0;      //鼠标x、y轴坐标（相对于left，top）
        var dx = 0,dy = 0;      //对话框坐标（同上）
        var isDraging = false;      //不可拖动

        //鼠标按下
        $("#move_part").mousedown(function(e){
            e = e || window.event;
            mx = e.pageX;     //点击时鼠标X坐标
            my = e.pageY;     //点击时鼠标Y坐标
            dx = $dialog.offset().left;
            dy = $dialog.offset().top;
            isDraging = true;      //标记对话框可拖动
        });

        //鼠标移动更新窗口位置
        $(document).mousemove(function(e){
            var e = e || window.event;
            var x = e.pageX;      //移动时鼠标X坐标
            var y = e.pageY;      //移动时鼠标Y坐标
            if(isDraging){        //判断对话框能否拖动
                var moveX = dx + x - mx;      //移动后对话框新的left值
                var moveY = dy + y - my;      //移动后对话框新的top值
                //设置拖动范围
                var pageW = $(window).width();
                var pageH = $(window).height();
                var dialogW = $dialog.width();
                var dialogH = $dialog.height();
                var maxX = pageW - dialogW;       //X轴可拖动最大值
                var maxY = pageH - dialogH;       //Y轴可拖动最大值
                moveX = Math.min(Math.max(0,moveX),maxX);     //X轴可拖动范围
                moveY = Math.min(Math.max(0,moveY),maxY);     //Y轴可拖动范围
                //重新设置对话框的left、top
                $dialog.css({"left":moveX + 'px',"top":moveY + 'px'});
            };
        });

        //鼠标离开
        $("#move_part").mouseup(function(){
            isDraging = false;
        });

        //点击关闭对话框
        $("#close").click(function(){
            $dialog.css("display","none");
            $mask.css("display","none");
        });

        //窗口大小改变时，对话框始终居中
        window.onresize = function(){
            autoCenter($dialog);
        };

        //评分
        $('.star').click(function(){
            $(this).addClass('active').siblings().removeClass('active');
            var goal = 7-$(this).css('z-index');
            $('.commstar .star-info').addClass('highlight').html(goal+'分');
        })
//        $('.star').hover(function() {
//            var goal = 7-$(this).css('z-index');
//            $('.commstar .star-info').addClass('highlight').html(goal+'分');
//        })

        $('btn-upload').click(function(){
            $(this).css('margin-left',"60px")
        });


    });

    var image = '';
    var picId = 0;
    function selectImage(file){
        if(!file.files || !file.files[0]){
            return;
        }
        var reader = new FileReader();
        reader.onload = function(evt){

            picId++;
            $('.upload-num .only').html(picId);
            $('.upload-num .last').html(9-picId);
            console.log(picId);
            $('.image_container').append("<div class=\"img_box\"><img id=\"image" + picId + "\" style=\"height:50px;width:50px;border-width:0px;\" />"
                +"<div class=\"delImg\" onclick=\"delimg(this)\">&times;</div></div>")
            document.getElementById('image' + picId).src = evt.target.result;
            image = evt.target.result;
        }
        reader.readAsDataURL(file.files[0]);
    }

    //删除上传的图片
    function delimg(){
        $(this).parent().css("display","none");
        picId = $('#img_box').length;
        $('.upload-num .only').html(picId);
        $('.upload-num .last').html(9-picId);
    };

    // function uploadImage(){
    //
    //     $.ajax({
    //
    //         type:'POST',
    //
    //         url: 'ajax/uploadimage',
    //
    //         data: {image: image},
    //
    //         async: false,
    //
    //         dataType: 'json',
    //
    //         success: function(data){
    //
    //             if(data.success){
    //
    //                 alert('上传成功');
    //
    //             }else{
    //
    //                 alert('上传失败');
    //
    //             }
    //
    //         },
    //
    //         error: function(err){
    //
    //             alert('网络故障');
    //
    //         }
    //
    //     });
    //
    // }


});
