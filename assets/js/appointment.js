(function($) {
	"use strict";
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();

	
	var service_id ='';
	var provider_id ='';
	var final_gig_amount1 ='';
	var booking_date ='';
	var booking_time ='';
	var service_location ='';
	var service_latitude ='';
	var service_longitude ='';
	var notes='';
	var service_offerid ='';
	var cod='';

	var daysCount = '';


  $('#book_services').bootstrapValidator({
	fields: {

	  bookingdate: {
		validators: {
		  notEmpty: {
			message: 'Please Enter Date'
		  }
		}
	  },
	  from_time: {
		validators: {
		  notEmpty: {
			message: 'Please select Time Slot...'
		  }
		}
	  },
	  
	   cod: {
		validators: {
		  notEmpty: {
			message: 'Please select the payment...'
		  }
		}
	  },
	  shop_id: {
		validators: {
		  notEmpty: {
			message: 'Please select the shop...'
		  }
		}
	  },
	  
	   staff_id: {
		validators: {
		  notEmpty: {
			message: 'Please select the staff...'
		  }
		}
	  },
	  
	  service_location: {
		validators: {
		  notEmpty: {
			message: 'Please Enter service location...'
		  }
		}
	  }
	  
	}
  }).on('success.form.bv', function(e) {
	var estTime = $("#estTime").val();
	var d = new Date().getTime() / 1000;
	d = parseInt(d);
	if(d > estTime){	
		var stit = $("#booktxt").val();
		var smsg = $("#expiretxt").val();
		swal({
			title: stit,
			text: smsg,
			icon: "error",
			button: "okay",
			closeOnEsc: false,
			closeOnClickOutside: false				 
		}).then(function(){
			location.reload();		
		}); 
		
	} else {
		
		$("#service_location").removeAttr("disabled");
		service_id = $('.submit_service_book').attr('data-id');
		provider_id = $('.submit_service_book').attr('data-provider');
		final_gig_amount1 = $('.submit_service_book').attr('data-amount');
		
		booking_date = $("#bookingdate").val();
		booking_time = $("#from_time").val(); 
		service_location = $("#service_location").val();
		service_latitude = $("#service_latitude").val();
		service_longitude = $("#service_longitude").val();
		service_offerid = $('#service_offerid').val();
		notes = $("#notes").val();
		cod = 0;
		
		var issave = 0; 
		$("#from_time").removeAttr('style');
		if(booking_time == ''){
			issave = 1;
			$("#from_time").attr('style','border-color:red').val('');;
		} 
		
		var staff_id = $("#staff_id").val();
		var shop_id = $("#shop_id").val();
		var duration = $("#duration").val();
		var dur_in = $("#dur_in").val();
		var service_at = $('input[name="service_at"]:checked').val();
		var isauto = $("#isauto").val();
		var procate = $("#procate").val();
		
		var offersid = $("#offersid").val();
		var couponid = $("#couponid").val();
		var rewardid = $("#rewardid").val();
		var rtype = $("#rewardtype").val();
		
		var book_id = $("#book_id").val();	
		if(book_id > 0){
			cod = $('#codval').val();
		}
		
		var total_amt = $("#total_amt").val();
		
		var addiser=[];  var addiamt=[];
		$('input[name="additional_services[]"]:checked').each(function(){
			addiser.push($(this).val());
			addiamt.push($(this).attr("data-amountval"));
		});
		if(issave == 0) {			
		$.ajax({
			 url: base_url+'user/appointment/book_staffservice/',
			 data: {service_id:service_id,final_amount:final_gig_amount1,provider_id:provider_id,tokenid:'old flow',booking_time:booking_time,service_location:service_location,service_latitude:service_latitude,service_longitude:service_longitude,notes:notes,service_offerid:service_offerid,booking_date:booking_date,cod:cod,csrf_token_name:csrf_token, shop_id:shop_id, staff_id:staff_id, dur_in:dur_in, duration:duration,service_at:service_at,isauto:isauto, book_id:book_id,offersid:offersid, couponid:couponid,procate:procate,rewardid:rewardid,rtype:rtype,total_amt:total_amt,addiser:addiser, addiamt:addiamt},			 
			 type: 'POST',
			 dataType: 'JSON',
			 beforeSend: function() {
			   button_loading();
			  },
			  success: function(response){ 
				button_unloading();
				$(".pay-submit-btn").removeAttr("disabled");
				var status = response.status;
				if(status == 1){
				  swal({
					 title: response.title,
					 text: response.msg,
					 icon: "success",
					 button: "okay",
					 closeOnEsc: false,
					 closeOnClickOutside: false
					 
					}).then(function(){

					 window.location.href = base_url+'user-bookings';

					}); 
				} else if(status == 6){
				   swal({
						 title: response.title,
						 text: response.msg,
						 icon: "error",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						 
					}).then(function(){
						location.reload();			 
					}); 
				
				} else if(status == 4){
					swal({
						title: response.title,
						text: response.msg,
						icon: "success",
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false					 
					}).then(function(){
						$("#book_id").val(response.bookid);			

						$('.nav-tabs li.nav-item').removeClass("active").removeClass("noclick"); 
						$(".tab-pane").removeClass("active");
						$("#bookings-tab").removeClass("active").addClass("noclick");
						$("#guests-tab").addClass("active");
						$("#guests-tab").parent("li").addClass("active");
						$("#bookings").removeClass("active");
						$("#guests").addClass("active").addClass("show");
						
										
						$('html, body').animate({scrollTop: jQuery("body").offset().top}, 100);
						
					});
				} else{ 
					 swal({
					 title: response.title,
					 text: response.msg,
					 icon: "error",
					 button: "okay",
					 closeOnEsc: false,
					 closeOnClickOutside: false
					 
					}).then(function(){
					 location.reload();

					});  
				}
			 },
			error: function(response){ 
				button_unloading();
				var status = response.status;
				if(status == 1){
					swal({
					 title: response.title,
					 text: response.msg,
					 icon: "success",
					 button: "okay",
					 closeOnEsc: false,
					 closeOnClickOutside: false
					 
					}).then(function(){
						 window.location.href = base_url+'user-bookings';
					}); 
				} else{
					 swal({
					 title: response.title,
						 text: response.msg,
						 icon: "error",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false				 
						}).then(function(){
						 location.reload();

					});  
				}
			}
		}); 
		}
	}
  
  });    
$(".addiservice").on("change", function(){
	var val = $(this).val();
	var dur = $(this).attr("data-durationval");
	
	var duration = $("#duration").val();
	if(this.checked) {	
		var newdur = parseInt(duration) + parseInt(dur);
	} else {
		var newdur = parseInt(duration) - parseInt(dur);
	}
	
	$("#duration").val(newdur);
	var date = $("#bookingdate").val();
	var provider_id = $("#provider_id").val(); 
    var service_id = $("#service_offerid").val();
	var staff_id = $("#staff_id").val();
    var shop_id = $("#shop_id").val();
	
    var dur_in = $("#dur_in").val();
	var procate = $("#procate").val();

    $('#from_time').empty();   
	$('#book_services').bootstrapValidator('revalidateField', 'bookingdate');
	   
    if(date!="" && date!=undefined){
      load_staff_availability(date, provider_id, service_id,staff_id, shop_id, dur_in, newdur, procate, 'from_time');      
    }
});
          
function button_loading(){
	var $this = $('.submit-btn');
	var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
	if ($this.html() !== loadingText) {
		$this.data('original-text', $this.html());
		$this.html(loadingText).prop('disabled','true').bind('click', false);
	}
}
function button_unloading(){
	var $this = $('.submit-btn');
	$this.html($this.data('original-text')).prop('disabled','false');
}
function paybutton_loading(){
	var $this = $('.pay-submit-btn');
	var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
	$(".cancelappt").removeAttr("style");
	if ($this.html() !== loadingText) {
		$this.data('original-text', $this.html());
		$this.html(loadingText).prop('disabled','true').bind('click', false);
		$(".cancelappt").attr("style", "pointer-events: none;cursor: default;");
	}
}
function paybutton_unloading(){
	var $this = $('.pay-submit-btn');
	$this.html($this.data('original-text')).prop('disabled','false');
	$(".cancelappt").removeAttr("style");
}


function unavailable(date) {	
	daysCount = $("#daysCount").val();
	if(daysCount != '') { 
		var unavailableDates = daysCount.split(",");
		if(Array.isArray(unavailableDates)) {
			if ($.inArray(date.getDay().toString(), unavailableDates) == -1) {
				return [false, ""];
			} else {
				return [true, "", "Unavailable"];
			}
		} else {
			if (date.getDay().toString() == daysCount) {
				return [false, ""];
			} else {
				return [true, "", "Unavailable"];
			}
		}
	} else {
		return [true, ""];
	}
}

$('.bookingdate').datepicker({
  dateFormat: 'dd-mm-yy',
  minDate: new Date(), 
  beforeShowDay: unavailable,
  icons: {
    up: "fas fa-angle-up",
    down: "fas fa-angle-down",
    next: 'fas fa-angle-right',
    previous: 'fas fa-angle-left'
  }, onSelect: function(dateText) {
    var date = dateText; 
    var dataString="date="+date;  
    var provider_id = $("#provider_id").val(); 
    var service_id = $("#service_offerid").val();
	var staff_id = $("#staff_id").val();
    var shop_id = $("#shop_id").val();
	var duration = $("#duration").val();
    var dur_in = $("#dur_in").val();
	var procate = $("#procate").val();

    $('#from_time').empty();   
	$('#book_services').bootstrapValidator('revalidateField', 'bookingdate');
	   
    if(date!="" && date!=undefined){
      load_staff_availability(date, provider_id, service_id,staff_id, shop_id, dur_in, duration, procate, 'from_time')
      
    }
  }

});

	function load_staff_availability(date, provider_id, service_id,staff_id, shop_id, dur_in, duration, procate, id){
		
		$.ajax({    
        url: base_url+"user/appointment/staff_availability/",
        data : {date:date,provider_id:provider_id, service_id:service_id,staff_id:staff_id, shop_id:shop_id, csrf_token_name:csrf_token, dur_in:dur_in, duration:duration, procate:procate, offers:'1'},
        type: "POST",

        success: function(response){      
          if(response!=''){
            var obj=jQuery.parseJSON(response);   
			$('#'+id).empty(); 	
			var seltmetxt = $("#selecttimetxt").val();
			var msg = 'Select time slot';
			msg = seltmetxt;
			var option = $('<option />'); option.attr('value', "").text(msg);
			$('#'+id).append(option);
			if(obj.time != '')
            {  
				$.each(obj.time, function (idx, val) {
					console.log(val);
					var option = $('<option />');
						option.attr('value', this.start_time+ '-' +this.end_time).text(this.start_time+ ' - ' +this.end_time); 
						if(this.disable_val){
							option.prop('disabled', this.disable_val); 
						}
						$('#'+id).append(option);
				});				
		    }
			else if(obj.time == '')
            {
			  $('#'+id).empty(); 	
              var msg = 'Availability not found';
              var option = $('<option />'); option.attr('value', "").text(msg);
			  $('#'+id).append(option);
            } 
			
			if(obj != '' && obj.offers != '') {	
            	console.log(obj); 			
				if(obj.offers.id == 0){
					swal({
						title: obj.offers.title,
						text: obj.offers.msg,
						icon: "warning",
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false				 
						}).then(function(){
					});  
					
				}
			} 
            
          }
        }

      });
	}

	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9]/g,'');
	});	 
	
	
	
	$(".cancelappt").on("click", function(){
		var book_id = $("#book_id").val();		
		cancel_appointment(book_id)
	});
	function cancel_appointment(book_id){
		$.ajax({    
			url: base_url+"user/appointment/cancel_appointment/",
			data : {book_id:book_id, csrf_token_name:csrf_token},
			type: "POST",
			success: function(response){ 
				window.location.href = base_url+'all-services';
			}
		});
	}
	
	$(document).on("click", ".nav-tabs a", function(){
		var tab = $(this).attr("aria-controls"); 
		activaTab(tab)
	});
	function activaTab(tab){	
		if(tab == 'bookings') {
			$("#next").show();
			$('#previous').trigger('click'); 
		} else {
			$('#next').trigger('click'); 
		}
	};
	$('#next').on("click",function(){	
		$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
		$("#bookings-tab").removeClass("active");
		$("#guests-tab").addClass("active");
		$("#guests-tab").parent("li").addClass("active");
		$("#bookings").removeClass("active");
		$("#guests").addClass("active");
		$("#guests").addClass("show");	
		$("#next").hide();
		scrolllTop();	
	});
	$('#previous').on("click",function(){	
		$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
		$("#guests-tab").removeClass("active");
		$("#bookings-tab").addClass("active");
		$("#bookings-tab").parent("li").addClass("active");
		$("#guests").removeClass("active");
		$("#bookings").addClass("active");
		$("#bookings").addClass("show");
		$("#next").show();
		scrolllTop();
	});
	function scrolllTop(){
		$('html, body').animate({
			scrollTop: jQuery("body").offset().top
		}, 100);
	}	

$(".checkTime").on("change", function(){
	var provider_id    = $("#provider_id").val();
	var service_id = $("#service_offerid").val();
	var book_id    = $("#book_id").val();
	var pptype     = $("#procate").val();
	var staff_id   = $("#staff_id").val();
	var date 	   = $("#bookingdate").val();
	var time 	   = $("#from_time").val();
	var shop_id    = $("#shop_id").val();
	checkbooking(provider_id, service_id, book_id, pptype, staff_id, date, time, shop_id, '1', 'from_time');
});

function checkbooking(provider_id, service_id, book_id, pptype, staff_id, date, time, shop_id, sfor,  id ){
	
	var base_url=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	$.ajax({
		url: base_url+'user/appointment/check_availability/',
		data: {service_id:service_id, provider_id:provider_id, service_date:date, staff_id:staff_id, book_id:book_id, pptype:pptype,csrf_token_name:csrf_token, time:time, shop_id:shop_id, offers:'1', servicefor:sfor,action:'add'},			 
		type: 'POST',
		dataType: 'JSON',			 
		success: function(response){ 
			if(response.status == 4){
				$("#offersid").val(response.id);
				if(response.id == 0) {
					swal({
						title: response.title,
						text: response.msg,
						icon: "warning",
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false				 
					}).then(function(){
						$("#"+id).val(time);	
						$(".submit_service_book").removeAttr("disabled");
					}); 
				}
			} else {
				swal({
					title: response.title,
					text: response.msg,
					icon: "error",
					button: "okay",
					closeOnEsc: false,
					closeOnClickOutside: false				 
				}).then(function(){
					$("#"+id).val('');	
					$(".submit_service_book").attr("disabled", "disabled");
				}); 
			}	
			
		}
	})	
}


$(document).on('change', '.service_offer_guest', function() {
	var id = $(this).val(); 
	if(this.checked) {			
		$("#service_for"+id+", "+ "#guest_ser"+id+", "+ "#guest_serstf"+id+", "+  "#guest_sertime"+id).prop("disabled", false);
				
	} else {		
		$("#service_for"+id+", "+  "#guest_ser"+id+", "+ "#guest_serdur"+id+", "+ "#guest_serstf"+id+", "+  "#guest_seramt"+id+", "+"#guest_sertime"+id).prop("disabled", true);
		
		$("#guest_name"+id).attr("readonly", "readonly").val(" ");
		
		$("#guest_ser"+id+", "+ "#guest_serdur"+id+", "+ "#guest_seramt"+id).val("");
		$("#guest_serstf"+id+", "+  "#guest_sertime"+id).empty();
		$("#service_for"+id).val(1);
	}	
});

$(document).on('change', '.guestservice', function() {
	var id  = $(this).attr("data-id");
	var val = $(this).val(); 
	if(val != ''){
			
		var amt = $('#guest_ser'+id+' option[value="'+val+'"]').attr("data-seramount");
		var dur = $('#guest_ser'+id+' option[value="'+val+'"]').attr("data-serduration"); 
		var sign = $('#guest_ser'+id+' option[value="'+val+'"]').attr("data-currency_sign"); 
		$("#guest_serdur"+id).val(dur); 
		$("#guest_seramt"+id).val(amt+sign);
		
		var base_url=$('#base_url').val();		
		var csrf_token=$('#csrf_token').val();
		
		var shopid = $("#shop_id").val();
		var service_at = $('input[name="service_at"]:checked').val();
		var service_id = $("#service_offerid").val();
		
		var pptype  = $("#procate").val();
		if(pptype != 4){
			$.ajax({    
				url: base_url+"user/appointment/get_staff_data/",
				data : {shop_id:shopid, service_id:val, service_at:service_at, csrf_token_name:csrf_token},
				type: "POST",
				success: function(response){  
					if($.trim(response)!=''){
						var obj=jQuery.parseJSON(response);   
						$('#guest_serstf'+id).empty(); 
						$('#guest_sertime'+id).empty(); 
						var msg = 'Select Staff';
						var option = $('<option />'); option.attr('value', "").text(msg);
						$('#guest_serstf'+id).append(option);
						if(obj != '')
						{        
							$.each(obj, function (index, value) {														
								var option = $('<option />'); 
									option.attr('value', this.value).text(this.label); 
									option.attr('data-service_status',this.homeservice);
									$('#guest_serstf'+id).append(option);						
							});
						}
						else if(obj == '')
						{
						  $('#guest_serstf'+id).empty(); 	
						  var msg = 'Staff Details not found';
						  var option = $('<option />'); option.attr('value', "").text(msg);
						  $('#guest_serstf'+id).append(option);
						} 
						
					}
				}
			});	
		
		}
		if(pptype == 4){
			var provider_id = $("#provider_id").val();		
			var date 	    = $("#bookingdate").val();
			var service_id  = $('#guest_ser'+id).val();
			var staff_id    = $('#guest_serstf'+id).val();
			var duration    = $("#guest_serdur"+id).val();
			var idval = "guest_sertime"+id;
			$('#'+idval).empty();
			load_staff_availability(date, provider_id, service_id,staff_id, shopid, "min(s)", duration, pptype, idval)
		}
	} else {
		$('#guest_serstf'+id).empty();
		$("#guest_serdur"+id).val(""); 
		$("#guest_seramt"+id).val("");
		$('#guest_sertime'+id).empty();
	}
});

$(document).on('change', '.gstaff', function() { 
	var id  = $(this).attr("data-id"); 
	var val = $(this).val(); 
	var idval = "guest_sertime"+id;
	if(val != ''){
		var provider_id = $("#provider_id").val();		
		var book_id     = $("#book_id").val();
		var pptype      = $("#procate").val();
		var date 	    = $("#bookingdate").val();
		var shop_id	    = $("#shop_id").val();
		var service_id  = $('#guest_ser'+id).val();
		var staff_id    = $('#guest_serstf'+id).val();
		var duration    = $("#guest_serdur"+id).val();
		$('#'+idval).empty();
		load_staff_availability(date, provider_id, service_id,staff_id, shop_id, "min(s)", duration, pptype, idval)
	} else{
		$('#'+idval).empty();
		$('#guest_sertime'+id).empty();
	}

});

$(document).on('change', '.checkGuestTime', function() {
	var id  = $(this).attr("data-id");
	var val = $(this).val(); 
	var idval = "guest_sertime"+id;
	if(val != ''){
		var provider_id = $("#provider_id").val();		
		var book_id     = $("#book_id").val();
		var pptype      = $("#procate").val();
		var date 	    = $("#bookingdate").val();
		var shop_id    = $("#shop_id").val();
		var service_id  = $('#guest_ser'+id).val();
		var staff_id    = $('#guest_serstf'+id).val();
		var time    	= $("#guest_sertime"+id).val();	
		var sfor        = $("#service_for"+id).val();
		checkbooking(provider_id, service_id, book_id, pptype, staff_id, date, time, shop_id, sfor, idval);
	} else{
		$('#'+idval).empty();
	}
	
});
	
$(".add-guest").on("click", function(){
	var provider_id  = $("#provider_id").val();		
	var shop_id  	 = $("#shop_id").val();
	var pptype      = $("#procate").val();
	var cnt = $("#rowcount").val();
	var newcnt = parseInt(cnt) + 1;
	$.ajax({    
		url: base_url+"user/appointment/load_more_guests/",
		data : {shop_id:shop_id, provider_id:provider_id,count:newcnt, csrf_token_name:csrf_token,procate:pptype},
		type: "POST",
		success: function(response){  
			if($.trim(response)!=''){
				var obj=jQuery.parseJSON(response);
				$("#guestdiv").append(obj.more_guests);
				$("#rowcount").val(newcnt);
				
			}
		}
	});	
});
$(document).on('click', '.remove_guest', function() {
	$(this).closest('.guest_details').remove();
    return false;
});	

$("#submit_button_id").on("click", function(){
		
	var estTime = $("#estTime").val();
	var d = new Date().getTime() / 1000;
	d = parseInt(d);
	if(d > estTime){
		var stit = $("#booktxt").val();
		var smsg = $("#expiretxt").val();
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
		var e=0;
		if ($("input[type='checkbox'][name='service_offered_guest[]']:checked").length > 0){
			$('.ginput:not(:disabled):not([readonly]').each(function(){		
				if($.trim($(this).val())=='') {
					e++;
					$(this).css('border-color','red');
				} else {
					$(this).css('border-color','');					
				}			
			});
		}
		if(e==0) {
			var locations = $("#service_location").val();
			var latitude = $("#service_latitude").val();
			var longitude = $("#service_longitude").val();
			var service_at = $('input[name="service_at"]:checked').val();
			var date = $("#bookingdate").val();
			var bid = $("#book_id").val();
			
			var append="&service_location="+locations+"&service_latitude="+latitude+"&service_longitude="+longitude+"&service_at="+service_at+"&date="+date+"&book_id="+bid;

			$.ajax({
				 url: base_url+'user/appointment/book_guestservice/',			 
				 data:$("#guest_book_services").serialize()+append ,
				 type: 'POST',
				 dataType: 'JSON',
				 beforeSend: function() {
				   paybutton_loading();
				 },
				 success: function(response){ 
					paybutton_unloading();				
					var status = response.status;
					if(status == 1){
						swal({
							title: response.title,
							text: response.msg,
							icon: "success",
							button: "okay",
							closeOnEsc: false,
							closeOnClickOutside: false					 
						}).then(function(){						
							window.location.href = base_url+'service-checkout/'+response.url;
						});
					} else {
						swal({
							title: response.title,
							 text: response.msg,
							 icon: "error",
							 button: "okay",
							 closeOnEsc: false,
							 closeOnClickOutside: false				 
							}).then(function(){
							 location.reload();
						});  
					}
				  }
			});
		}
	}
});

$(document).on('change', '.servicefor', function() {
	var id  = $(this).attr("data-id");
	var val = $(this).val(); 
	var idval = "guest_name"+id;
	if(val == 2){
		$("#"+idval).removeAttr("readonly");		
	} else {
		$("#"+idval).attr("readonly", "readonly").val(' ');
	}
	$("#guest_sertime"+id).val('');
});
	
})(jQuery);
	
$(document).ready(function(){
	$('#staff_id option[data-homeservice="1"]').hide().parent().selectpicker('refresh');;
});
var SelArea = '';

$(".serviceat").on("change", function(){
	var val = $(this).val();
	$('#staff_id').val('').selectpicker('refresh');
	
	if(val == 1){		
		$("#map").removeClass("d-none"); 
		$("#service_location").removeAttr("disabled");
		$("#service_location").val($("#service_address").val());
		$('#staff_id option[data-homeservice="0"]').hide().parent().selectpicker('refresh');;
		$('#staff_id option[data-homeservice="1"]').show().parent().selectpicker('refresh');;
		SelArea = ''; initMap();	initialize();			
	} else {		
		$("#map").addClass("d-none"); 
		$("#service_location").attr("disabled", "disabled");
		$("#service_location").val($("#shoplocation").val());
		$('#staff_id option[data-homeservice="0"]').show().parent().selectpicker('refresh');;
		$('#staff_id option[data-homeservice="1"]').hide().parent().selectpicker('refresh');;
	}		
	
});
$("#staff_id").on("change", function(){
	$("#bookingdate").val('');$("#from_time").empty();	
	var val = $(this).val();
	var service_latitude = $("#service_latitude").val();
	var service_longitude = $("#service_longitude").val();
	var selarea = $('#staff_id option[value="'+val+'"]').attr("data-homeservicearea");
	if(selarea != '' && selarea != undefined)
	{
			SelArea = selarea;
			initMap();	initialize();
	}
});
	
  var placeSearch, autocomplete, user_addr;
	
	function initMap() 
	{
		var key = $('#google_map_api').val();
		var address = $('#service_location').val();
		var language_option = $("#language_option").val();
		$.get('https://maps.googleapis.com/maps/api/geocode/json',{address:address,key:key,language:language_option},function(data, status){
        $(data.results).each(function(key,value){
            address   = value.formatted_address;
            latitude  = value.geometry.location.lat;
            longitude = value.geometry.location.lng;
            $(value.address_components).each(function(key1,value1){
              if(value1.types.includes('country')){
                $('#user_country').val(value1.long_name);
              }
            })
            load_msp_details(latitude, longitude)
             $('#service_location').val(address);
             $('#service_latitude').val(latitude);
             $('#service_longitude').val(longitude);
			 if($('input[name="service_at"]:checked').val() == 1){
				$('#service_address').val(address);
			 }
             
        });
      });      
   }
   
	
    function initialize() {
      autocomplete = new google.maps.places.Autocomplete(
      (document.getElementById('service_location')), {		 
          types: ['geocode']
      });
      google.maps.event.addDomListener(document.getElementById('service_location'), 'focus', geolocate);
      google.maps.event.addDomListener(autocomplete, 'place_changed', initMap);
    }
    
    function load_msp_details(latitude, longitude) 
    {
      var uluru = {
          lat: parseFloat(latitude),
          lng: parseFloat(longitude)
      };
      var geocoder = new google.maps.Geocoder();
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: uluru,
          style:{background:"url('http://ressio.github.io/lazy-load-xt/dist/loading.gif') center center no-repeat" }
      });
      var marker = new google.maps.Marker({
          position: uluru,
          map: map,
          draggable: true
      });
	  	var language_option = $("#language_option").val();
			google.maps.event.addListener(marker, 'dragend', function() 
			{
			  geocoder.geocode({
				  'latLng': marker.getPosition(),
							language:language_option
			  }, function(results, status) {
				  if (status == google.maps.GeocoderStatus.OK) 
				  {	
					  if (results[0]) 
					  {
						  address = results[0].formatted_address;
						  latitude = marker.getPosition().lat();
						  longitude = marker.getPosition().lng();
						  $('#service_location').val(address);
						  $('#service_latitude').val(latitude);
						  $('#service_longitude').val(longitude);
						  if($('input[name="service_at"]:checked').val() == 1){
								$('#service_address').val(address);
							}
							load_msp_details(latitude, longitude);
					  }
				  }
			  });
			});

			if(SelArea != '') {
				var sptarr = SelArea.split(",");
				var nt = parseFloat(sptarr[0]);	  
				var et = parseFloat(sptarr[1]);
				var st = parseFloat(sptarr[2]);
				var wt = parseFloat(sptarr[3]);
				var rectangle = new google.maps.Rectangle({
					strokeColor: "#FF0000",
					strokeOpacity: 0.9,
					strokeWeight: 2,
					fillColor: "#FF0000",
					fillOpacity: 0.25,
					map,
					bounds: {
					  north: nt,
					  south: st,
					  east:  et,
					  west:  wt,
					},
				});
				attachRectangeInfoWindow(rectangle)
				var serviceat = $('input[name="service_at"]:checked').val();
				if (serviceat == 1) {
					var inorout = check_is_in_or_out(marker);
					if (inorout == false) 
					{
						swal({
							title: "Alert",
							text: "Your location is too far from Staff area. Please select a nearby location",
							icon: "error",
							button: "okay",
							closeOnEsc: false,
							closeOnClickOutside: false
						 }).then(function(){
							$('#staff_id').val('').selectpicker('refresh');
						});
					}
				}
				function attachRectangeInfoWindow(rectangle) {
					var infoWindow = new google.maps.InfoWindow();
					var SelAreaMsp = $("#SelAreaMsp").val();
					infoWindow.setContent(SelAreaMsp);
					google.maps.event.addListener(rectangle, 'mouseover', function (e) {				
						var latLng = e.latLng;
						infoWindow.setPosition(latLng);
						infoWindow.open(map);
					});				
				}

				function check_is_in_or_out(marker) {
				  var insideRectangle = false;
				  if (rectangle && rectangle.getBounds && marker && marker.getPosition())
				    insideRectangle = rectangle.getBounds().contains(marker.getPosition());

				  return insideRectangle;
				}
			}
    }
	
    function geolocate() {
        var key = $('#google_map_api').val();        
        var lat = $('#service_latitude').val();
        var lng = $('#service_longitude').val();
        var latLng = lat+', '+lng;
        var adrs = '';
		var language_option = $("#language_option").val();
        $.get('https://maps.googleapis.com/maps/api/geocode/json',{latlng:latLng,key:key,language:language_option},function(data, status){
            $('#service_location').val(data.results[0].formatted_address);
            $('#service_latitude').val(lat);
            $('#service_longitude').val(lng);
        });     
    }
	
	
	