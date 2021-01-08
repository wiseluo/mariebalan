/**
 * Created by Jia on 2017/04/17.
 */
WX_Platform_Load([
    "style!./css/module/cart/cart_order_detail"
],function(){
	function ht(title){
		var hn='<div class="motify"><div class="motify-inner"></div></div>';
		$('body').append(hn);
		var $Motify = $('.motify'), innerText = title;
		$Motify.find('.motify-inner').text(innerText);
		$Motify.show();
		setTimeout(function () {
			$Motify.fadeOut();
		}, 1000);
	}

	$(document).on('click','.confirm_address',function(){
		var tempCheck = $('input[name=address]:checked');
		if (tempCheck.val()) {
			$('#detail_address').find(".name").text(tempCheck.siblings(".name").text());
			$('#detail_address').find(".phone").text(tempCheck.siblings(".mobile").text());
			$('#detail_address').find(".address").text(tempCheck.siblings(".address").text());
			$("#my-actions").modal('close');
		}else{
			ht('请选择地址');
		}
	});
});