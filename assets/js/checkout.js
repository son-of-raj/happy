
(function($) {
	"use strict";
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	var booking_id=$('#booking_id').val();
	var service_id=$('#service_id').val();
	$('#cod_payment').hide();
	$("#bankdetails").hide();

$(".cod").on("change", function(){
	var val = $(this).val();
	if(val != ''){
		if(val == 1) {			
		   	$("#bankdetails").hide();	
			$(".pay-submit-btn").removeClass("d-none");
			$(".book-mysr-form").addClass("d-none");
			$(".cancelappt").removeAttr("style");
		} else {			
			$("#bankdetails").show();	
			$(".pay-submit-btn").addClass("d-none");
			$(".book-mysr-form").removeClass("d-none");
		}
	} else { 
		$("#submit_servicebook").prop('disabled','true');		
	}
});

$(".applycode").on("click", function(){
	var val = $("#codeval").val();
	var book_id = $("#bookid").val();	
	var service_id = $("#service_id").val();
	
	if(val != ''){
		$("#codeval").removeAttr("style");	
		$.ajax({
			url: base_url+'user/appointment/get_coupon_details/',
			data: {cid:val, bid: book_id, sid: service_id, csrf_token_name:csrf_token},
			type: 'POST',	
			dataType:'json',
			success: function(response){ 
				console.log(response);
				var status = response.status;
				if(status == 1){
					$('#coupon_used').val('1');
					$("#promocode").text(''); $(".errmessages").text('');
					$("#promocode").append(response.content);
					$("#promocode").removeAttr('style');
					$("#total_pay").text(response.price);
					$("#booking_amount").val(response.price);
					$("#totalamt").val(response.price);
					$("#couponid").val(response.coupon);
					$("#codeval").val('');
					$("#bamountval").val(response.price*100);
					var url = base_url+'user/appointment/book_moyaser_payment/'+book_id+'/'+response.coupon;
					$("#bcallbackurl").val(url);
					call_payment();
				} else{
					$(".errmessages").text(response.msg);
					$("#couponid").val(response.coupon);
					$("#codeval").attr("style","border-color:red");	
				}
			}
		});
	} else { 
		$("#codeval").removeAttr('style');
		$(".errmessages").text('');
	}
});

$(document).on("click", ".removeCoupon", function(){		
		
		var book_id = $("#bookid").val();
		var amt = $("#total_pay").text();		
		var cpn_price = $("#cpn_price").text(); 
		if (isNaN(cpn_price)) {
			cpn_price = 0;

		}
		
		var finalamt =  parseFloat (amt) + parseFloat (cpn_price) ; 		 	
		finalamt = finalamt.toFixed(2);		
		
		var card_finalamt = finalamt * 100;
		$("#bamountval").val(card_finalamt);
		
		$("#promocode").text();
		$("#promocode").attr('style','display:none !important');
				
		$("#totalamt").val(finalamt);
		
		$("#total_pay").text(finalamt);
		$("#coupon_amount").text(finalamt);
		$("#booking_amount").val(finalamt);
					
		$("#couponid").val('0');
		
		var url = base_url+'user/appointment/book_moyaser_payment/'+book_id;
		$("#bcallbackurl").val(url);
		call_payment();
			
	});


$('input[name="payment_type"]').on('click', function() {
	var payment_type = $(this).val();

	if(payment_type == 'paypal') {
		$('#payments-tab').hide();
		$('#cod_payment').hide();
		if($('#totalamt').val()) {
			var coupon_amount = $('#totalamt').val();
        	$('#coupon_amount').val(coupon_amount);
		}
		
		document.getElementById("frm_paypal_detail").submit();
	} else if(payment_type == 'stripe') {
		$('#payments-tab').hide();
		$('#my_stripe_payyment').click();
		$('#cod_payment').hide();
	} else if(payment_type == 'moyasarpay') {
		$('#payments-tab').show();
		$('#cod_payment').hide();
	} else if(payment_type == 'cod') {
		$(".pay-submit-btn").removeClass("d-none");
		$(".cancelappt").removeClass("d-none");
	}
});

function cod_payment(bookingId, bookingAmount) {
	
	return false;
	//var booking_amt = $('#booking_amount').val();
	//var booking_amount = (booking_amt * 100);
	$.ajax({
		url: base_url+'user/appointment/codPayment',
		data: {booking_id:bookingId, couponid: $('#couponid').val(),amount:bookingAmount, id:'1234569870', status:'unpaid', csrf_token_name:csrf_token},
		type: 'POST',
		dataType: 'JSON',
		success: function(response){
			console.log(response); return false;
			if(response.success == true) {
				swal({
				title: 'success',
				text: 'Payment Success',
				icon: "success",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false
			 
			}).then(function(){
				window.location.href = base_url+'user-bookings';
			}); 
			} else {
				window.location.reload();
			}
		},
		error: function(error){
			console.log(error);
		}
	});
}
var handler = StripeCheckout.configure({
		key: $('#stripe_key').val(),
		image: $('#logo_front').val(),
		locale: 'auto',
		token: function(token,args) {
		// You can access the token ID with `token.id`.
		$('#access_token').val(token.id);
		var tokenid = token.id;
		var booking_amt = $('#booking_amount').val();
		var booking_amount = (booking_amt * 100);
		$.ajax({
			url: base_url+'user/appointment/book_stripe_payment',
			data: {booking_id:booking_id, couponid: $('#couponid').val(),amount:booking_amount, id:'1234569870', status:'paid', csrf_token_name:csrf_token},
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				if(response.success == true) {
					swal({
					title: 'success',
					text: 'Payment Success',
					icon: "success",
					button: "okay",
					closeOnEsc: false,
					closeOnClickOutside: false
				 
				}).then(function(){
					window.location.href = base_url+'user-bookings';
				}); 
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

$('#my_stripe_payyment').on('click', function(e) {
	var booking_amt = $('#booking_amount').val();
	var currency = $('#booking_currency').val();
	var booking_amount = (booking_amt * 100); //  dollar to cent	
	// Open Checkout with further options:
	handler.open({
		name: base_url,
		description: 'Service Booking',
		amount: booking_amount,
		currency:currency
	});
	e.preventDefault();
});


	// Capture the form submit button
	$("#submit_button_id").on("click", function(event){
		event.preventDefault();
		var estTime = $("#estTime").val();
		var d = new Date().getTime() / 1000;
		d = parseInt(d);
		
		if(d > estTime){
			var stit = $("#booktxt").val();
			var smsg = $("#expiretxt").val();
			var book_id = $("#bookid").val();	
			swal({
				title: stit,
				text: smsg,
				icon: "error",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false				 
			}).then(function(){
				cancel_appointment(book_id);
			}); 
			
		} else {		
		
			var cod = $('input[name="cod"]:checked').val();
			var book_id = $("#cod_booking_id").val();
			$("input.inputfld:text").val("");
			var cid = $("#couponid").val();
			var totalamt = $("#total_pay").text();
			if(cod == 1){ 
				$.ajax({
				 url: base_url+'user/appointment/book_moyaser_payment/',
				 data: {type:"cod", cid:cid, bookid: book_id, totalamt: totalamt, csrf_token_name:csrf_token},
				 type: 'POST',	
				 beforeSend: function() {
				   paybtn_loading();
				  },	
				 success: function(response){ 
					paybtn_unloading();
					if(response!=''){
						var obj=jQuery.parseJSON(response);   
						if(obj.status == 1){
							swal({
								title: obj.title,
								text: obj.msg,
								icon: "success",
								button: "okay",
								closeOnEsc: false,
								closeOnClickOutside: false
							 
							}).then(function(){
								window.location.href = base_url+'user-bookings';
							}); 
						} else {
							swal({
								title: obj.title,
								text: obj.msg,
								icon: "error",
								button: "okay",
								closeOnEsc: false,
								closeOnClickOutside: false
								 
							}).then(function(){
								location.reload();

							});  
						}
					}
					 
				 }
				});
			} else {
				$.ajax({
					url: base_url+'cod-payment',
					data: {type:"cod", cid:cid, bookid: book_id, totalamt: totalamt, csrf_token_name:csrf_token},
					type: 'POST',	
					beforeSend: function() {
					   paybtn_loading();
					},	
				 	success: function(response){ 
						paybtn_unloading();
						if(response!=''){
							var obj=jQuery.parseJSON(response);   
							if(obj.status == 1){
								swal({
									title: obj.title,
									text: obj.msg,
									icon: "success",
									button: "okay",
									closeOnEsc: false,
									closeOnClickOutside: false
								 
								}).then(function(){
									window.location.href = base_url+'user-bookings';
								}); 
							} else {
								swal({
									title: obj.title,
									text: obj.msg,
									icon: "error",
									button: "okay",
									closeOnEsc: false,
									closeOnClickOutside: false
									 
								}).then(function(){
									location.reload();

								});  
							}
						} 
				 	}
				});
			}
		}
	});
	
	function paybtn_loading(){
	var $this = $('.pay-submit-btn');
		var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
		$(".cancelappt").removeAttr("style");
		if ($this.html() !== loadingText) {
			$this.data('original-text', $this.html());
			$this.html(loadingText).prop('disabled','true').bind('click', false);
			$(".cancelappt").attr("style", "pointer-events: none;cursor: default;");
		}
	}
	function paybtn_unloading(){
		var $this = $('.pay-submit-btn');
		$this.html($this.data('original-text')).prop('disabled','false');
		$(".cancelappt").removeAttr("style");
	}
	
	$(".cancelappt").on("click", function(){ 
		var book_id = $("#bookid").val();	
		cancel_appointment(book_id); 
	});
	function cancel_appointment(book_id){
		book_id = $.trim(book_id);		
		$.ajax({    
			url: base_url+"user/appointment/cancel_appointment/",
			data : {book_id:book_id, csrf_token_name:csrf_token},
			type: "POST",
			success: function(response){ console.log(response);
				window.location.href = base_url+'all-services';
			}
		});
	}
	
	call_payment();
})(jQuery);

function call_payment(){ 
		if($("#language_option").val() != 'ar'){
			var lanval = 'en';
		} else {
			var lanval = $("#language_option").val()
		} 
		 Moyasar.init({
				// Required
				// Specify where to render the form
				// Can be a valid CSS selector and a reference to a DOM element
				element: '.book-mysr-form',
				
				  language: lanval,

				// Required
				// Amount in the smallest currency unit
				// For example:
				// 10 SAR = 10 * 100 Halalas
				// 10 KWD = 10 * 1000 Fils
				// 10 JPY = 10 JPY (Japanese Yen does not have fractions)
				amount: $("#bamountval").val(),

				// Required
				// Currency of the payment transation
				currency: $("#bcurrencyval").val(),

				// Required
				// A small description of the current payment process
				description: $("#bdescription").val(),

				// Required
				publishable_api_key: $("#moyasar_apikey").val(),

				// Required
				// This URL is used to redirect the user when payment process has completed
				// Payment can be either a success or a failure, which you need to verify on you system (We will show this in a couple of lines)
				callback_url: $("#bcallbackurl").val(),

				// Optional
				// Required payments methods
				// Default: ['creditcard', 'applepay', 'stcpay']
				methods: [
					'creditcard',
				],
				on_initiating: function () {
					$(".cancelappt").attr("style", "pointer-events: none;cursor: default;");
					return {};
				},
				
		});
	}