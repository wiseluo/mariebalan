var slideTxt={
	thisBox:$('.promotion'),//这里的样式名要与内容区保持一致
	btnLeft:$('a.left_btn'),
	btnRight:$('a.right_btn'),
	thisEle:$('.promotion ul'),
	init:function(){
		slideTxt.thisBox.width(slideTxt.thisEle.length*203);//这个宽度等于内容区的宽度，是内容移出去的宽度
		slideTxt.slideAuto();
		slideTxt.btnLeft.click(slideTxt.slideLeft).hover(slideTxt.slideStop,slideTxt.slideAuto);
		slideTxt.btnRight.click(slideTxt.slideRight).hover(slideTxt.slideStop,slideTxt.slideAuto);
		slideTxt.thisBox.hover(slideTxt.slideStop,slideTxt.slideAuto);
		},
	slideLeft:function(){
		slideTxt.btnLeft.unbind('click',slideTxt.slideLeft);
		slideTxt.thisBox.find('ul:last').prependTo(slideTxt.thisBox);
		slideTxt.thisBox.css('marginLeft',-203);//这个宽度等于内容区的宽度，是内容移出去的宽度
		slideTxt.thisBox.animate({'marginLeft':0},350,function(){
			slideTxt.btnLeft.bind('click',slideTxt.slideLeft);
			});
		return false;
		},
	slideRight:function(){
		slideTxt.btnRight.unbind('click',slideTxt.slideRight);//如果想要鼠标经过时就滚动，可改成mouseover，此js里面的所有click都需要换成mouseover
		slideTxt.thisBox.animate({'marginLeft':-203},350,function(){
			slideTxt.thisBox.css('marginLeft','0');
			slideTxt.thisBox.find('ul:first').appendTo(slideTxt.thisBox);
			slideTxt.btnRight.bind('click',slideTxt.slideRight);
			});
		return false;
		},
	slideAuto:function(){
		slideTxt.intervalId=window.setInterval(slideTxt.slideRight,5000);//滚动间隔时间
		},
	slideStop:function(){
		window.clearInterval(slideTxt.intervalId);
		}
	}
$(function(){
	slideTxt.init();

});

