(function($) {
	"use strict";
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	
	$( document ).ready(function() {
		$(".service_offered_price_select").prop('checked', false);
	});
	$(document).on('change','.service_offered_price_select',function(){
		var total = 0;
		var valueArray = [];
		  $('input:radio:checked').each(function(){
				valueArray.push($(this).data("offerid"));
				total += isNaN(parseInt($(this).val())) ? 0 : parseInt($(this).val());					
		  });
		var offerid = valueArray.join("");						
		var url = base_url+"book-appointment/"+offerid;
		$("#shop_book_appoint").attr("href",url);
	});
	$("#shop_book_appoint").on("click", function(){
		$('html, body').animate({
			scrollTop: $("#pills-profile").offset().top
		}, 1000);
	});
	
	$(document).on('click','#prod_modal',function() {	
		$('#tab_login_modal').modal('show');
	});
	
})(jQuery);