
var alldata=[];
//  input匹配
function reminder($this){
    // 获取当前对象的一些样式
    //  input 框
    var input = $this;
    //  input 框的下拉选择列表
    if(!input.next().hasClass('search_suggest')){
        input.parent().css('position','relative').append("<div class='search_suggest' ><ul></ul> </div> ");
        // 接口api
        var Api=input.data('api');
        $.get(Api,{},function(data){
            alldata =data;
        });
    }
    // 宽高
    input.next().css({
        top:input.parent().height(),
        width:input.parent().width()
    });
    //定义当前下拉选择列表
    var suggestWrap =input.next('.search_suggest');
    function oSearchSuggest(searchFuc){
        var key = "";
        var init = function(){
            input.bind('keyup',sendKeyWord);
            input.parent().bind('mouseleave',function(){setTimeout(hideSuggest,100);})

        };
        var hideSuggest = function(){
            suggestWrap.hide();
        };

        //发送请求，根据关键字到后台查询
        var sendKeyWord = function(event){

            //键盘选择下拉项
            if(suggestWrap.css('display')=='block'&&event.keyCode == 38||event.keyCode == 40){
                var current = suggestWrap.find('li.hover');
                if(event.keyCode == 38){
                    if(current.length>0){
                        var prevLi = current.removeClass('hover').prev();
                        if(prevLi.length>0){
                            prevLi.addClass('hover');
                            input.val(prevLi.html());
                        }
                    }else{
                        var last = suggestWrap.find('li:last');
                        last.addClass('hover');
                        input.val(last.html());
                    }

                }else if(event.keyCode == 40){
                    if(current.length>0){
                        var nextLi = current.removeClass('hover').next();
                        if(nextLi.length>0){
                            nextLi.addClass('hover');
                            input.val(nextLi.html());
                        }
                    }else{
                        var first = suggestWrap.find('li:first');
                        first.addClass('hover');
                        input.val(first.html());
                    }
                }

                //输入字符
            }else{
                var valText = $.trim(input.val());
                if(valText ==''||valText==key){
                    return;
                }
                searchFuc(valText);
                key = valText;
            }

        }
        //请求返回后，执行数据展示
        this.dataDisplay = function(data){
            if(data.length<=0){
                suggestWrap.hide();
                return;
            }

            //往搜索框下拉建议显示栏中添加条目并显示

            var tmpFrag = '';
            suggestWrap.find('ul').html('');
            for(var i=0; i<data.length; i++){

                tmpFrag+="<li class='Li_data'>"+data[i]+"</li> ";

            }
            suggestWrap.find('ul').append(tmpFrag);
            suggestWrap.show();

            //为下拉选项绑定鼠标事件
            suggestWrap.find('li').hover(function(){
                suggestWrap.find('li').removeClass('hover');
                $(this).addClass('hover');

            },function(){
                $(this).removeClass('hover');
            }).bind('click',function(){
                input.val(this.innerHTML);
                suggestWrap.hide();
            })
        }
        init();
    };

    //实例化输入提示的JS,参数为进行查询操作时要调用的函数名
    var searchSuggest =  new oSearchSuggest(sendKeyWordToBack);
    //参数为一个字符串，是搜索输入框中当前的内容
    function sendKeyWordToBack(keyword){
        // 根据接口获取当前全部的数据，然后做匹配。
        var aData = [];
        $.each(alldata,function(i,n){
            var len = keyword.length;
            for(var i=0;i<len;i++){
                if(n.indexOf(keyword)>=0){
                    aData.push(n);
                }
            }
        });
        searchSuggest.dataDisplay(aData);

    }
}
