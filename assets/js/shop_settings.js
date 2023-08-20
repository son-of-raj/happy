(function($) {
  "use strict";

  var base_url=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();
  
  var module = $("#modules_page").val();
  var d_url = '';
  if(module == 'shopfreelancer'){
	d_url = 'freelances/';
  } 
 
$(document).on('click','.si-inactive-shop',function() { 
	var s_id = $(this).attr("data-id");
	var delete_title = "Inactive Shop";
	var delete_msg = "Are you sure want to inactive this shop?";
	var delete_text = "Shop has been inactivated.";
	$('#deleteConfirmModal').modal('toggle');
	$('#acc_title').html('<span>'+delete_title+'</span>');
	$('#acc_msg').html(delete_msg);
	
	$(document).on('click','.si_inactive_confirm',function(){
		
		var url = base_url+'user/shop/delete_inactive_shop';
		$.ajax({
			url:url,
			data:{s_id:s_id,csrf_token_name:csrf_token},
			type:"POST",
			beforeSend:function(){
				$('#deleteConfirmModal').modal('toggle');
			},
			success: function(res){
				if(res==1) {
					window.location = base_url+d_url+'shop';
				}else if(res==2){
					window.location = base_url+d_url+'shop';
				}
			}
		});
	});
	$(document).on('click','.si_accept_cancel',function(){
	});
});

$(document).on('click','.si-active-shop',function() { 
	var s_id = $(this).attr("data-id"); 
	var delete_title = "Active Shop";
	var delete_msg = "Are you sure want to active this shop?";
	var delete_text = "Shop has been Activated.";
	$('#deleteConfirmModal').modal('toggle');
	$('#acc_title').html('<span>'+delete_title+'</span>');
	$('#acc_msg').html(delete_msg);
	
	$(document).on('click','.si_active_confirm',function(){
		
		var url = base_url+'user/shop/delete_active_shop';
		$.ajax({
			url:url,
			data:{s_id:s_id,csrf_token_name:csrf_token},
			type:"POST",
			beforeSend:function(){
				$('#deleteConfirmModal').modal('toggle');
			},
			success: function(res){
				if(res==1) {
					window.location = base_url+d_url+'my-shop-inactive';
				}else if(res==2){
					window.location = base_url+d_url+'my-shop-inactive';
				}
			}
		});
	});
	$(document).on('click','.si_accept_cancel',function(){
	});
});

$(document).on('click','.si-delete-inactive-shop',function() { 
	var s_id = $(this).attr("data-id"); 
	var delete_title = "Delete Shop";
	var delete_msg = "Are you sure want to delete this shop?";
	var delete_text = "Shop has been Deleted.";
	$('#deleteConfirmModal').modal('toggle');
	$('#acc_title').html('<span>'+delete_title+'</span>');
	$('#acc_msg').html(delete_msg);
	
	$(document).on('click','.si_accept_confirm',function(){
		
		var url = base_url+'user/shop/delete_shop';
		$.ajax({
			url:url,
			data:{s_id:s_id,csrf_token_name:csrf_token},
			type:"POST",
			beforeSend:function(){
				$('#deleteConfirmModal').modal('toggle');
			},
			success: function(res){
				if(res==1) {
					window.location = base_url+d_url+'my-shop-inactive';
				}else if(res==2){
					window.location = base_url+d_url+'my-shop-inactive';
				}
			}
		});
	});
	$(document).on('click','.si_accept_cancel',function(){
	});
});

$("#shop_pay").on("click", function(){
	$('#payment-types').show();
});

$('input[name="shop_payment_type"]').on('click', function() {
	var payment_type = $(this).val();

	if(payment_type == 'paypal') {
		$('#payment-types').hide();
		document.getElementById("addshop_paypal_detail").submit();
	} else if(payment_type == 'stripe') {
		$('#myshop_stripe_payment').click();
	} else if(payment_type == 'moyasarpay') {
		$("#callbackurl").val($("#callback_url").val());
		$("#amountval").val($("#amount").val());
		$("#currencyval").val($("#currency").val());
		$("#description").val("Add Shop");
		$(".pay-modal-title").text($("#paytitle").val());
		$("#publishable_api_key").val($("#moyasar_api_key").val());
		$("#paymentModal").modal('show');   
		call_payment();
	}
});

var handler = StripeCheckout.configure({
		key: $('#stripe_key').val(),
		image: $('#logo_front').val(),
		locale: 'auto',
		token: function(token,args) {
		// You can access the token ID with `token.id`.
		$('#access_token').val(token.id);
		var tokenid = token.id;
		var shop_fee = $('#shop_fee').val();
		var shop_fees = (shop_fee * 100);
		$.ajax({
			url: base_url+'user/shop/stripe_shop_payment',
			data: {status:'paid', amount: shop_fees, id: tokenid, csrf_token_name:csrf_token},
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.success == true) {
					window.location.reload();
				} else {
					window.location.reload();
				}
			},
			error: function(error){
				console.log(error);
			}
		});
	}
});

$('#myshop_stripe_payment').on('click', function(e) {
	var shop_fee = $('#shop_fee').val();
	var currency = $('#shop_currency').val();
	var shop_fees = (shop_fee * 100); //  dollar to cent	
	// Open Checkout with further options:
	handler.open({
		name: base_url,
		description: 'New Shop Payment',
		amount: shop_fees,
		currency:currency
	});
	e.preventDefault();
});
})(jQuery);