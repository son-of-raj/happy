(function($) {
	"use strict";
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();

	$('.select').selectpicker();
	
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
	e.preventDefault();
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
		
		var provider_id = $("#provider_id").val(); 
		var service_offerid = $("#service_offerid").val();
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
		
		$.ajax({
			 url: base_url+'user/appointment/book_staffservice/',
			 data: {service_id:service_id,final_amount:final_gig_amount1,provider_id:provider_id,tokenid:'old flow',booking_time:booking_time,service_location:service_location,service_latitude:service_latitude,service_longitude:service_longitude,notes:notes,service_offerid:service_offerid,booking_date:booking_date,cod:cod,csrf_token_name:csrf_token, shop_id:shop_id, staff_id:staff_id, dur_in:dur_in, duration:duration,service_at:service_at,isauto:isauto, book_id:book_id,offersid:offersid, couponid:couponid,procate:procate,rewardid:rewardid,rtype:rtype},
			 type: 'POST',
			 dataType: 'JSON',
			 beforeSend: function() {
			   button_loading();
			  },
			  success: function(response){
				button_unloading();
				var status = response.status;
			    if(status == 6){
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
				} else if(status == 5){
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
	if ($this.html() !== loadingText) {
		$this.data('original-text', $this.html());
		$this.html(loadingText).prop('disabled','true').bind('click', false);
	}
}
function paybutton_unloading(){
	var $this = $('.pay-submit-btn');
	$this.html($this.data('original-text')).prop('disabled','false');
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

	
$('.booking_date').datepicker({
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
       
    if(date!="" && date!=undefined){      
		load_timeslot(date);
    }
  }

});

function isLater(date1,date2){ 
	var p1 = date1.split("-");
	var s1 = p1[2]+"-"+p1[1]+"-"+p1[0];
	var p2 = date2.split("-");
	var s2 = p2[2]+"-"+p2[1]+"-"+p2[0];
	var d1 = Date.parse(s1);
    var d2 = Date.parse(s2);
	if (d1 == d2) { return true;}
	else if (d1 <= d2) { return true; }
	else if (d1 > d2) { return false; }
}
function load_timeslot(date){ 
	
	var provider_id = $("#provider_id").val(); 
    var service_id = $("#service_offerid").val();
	var staff_id = $("#staff_id").val();
    var shop_id = $("#shop_id").val();
	var duration = $("#duration").val();
    var dur_in = $("#dur_in").val();
	var procate = $("#procate").val();
	
	var book_id = $("#book_id").val();
	var bdate = $("#bookeddate" ).val(); 
	var session = $("#sessionno" ).val(); 
	
	var sesserr = 0;	
	if(session > 1) {		
		if(!isLater(bdate,date)){
			sesserr = 1; 
			$("#bookingdate").val(bdate);
			var stit = $("#booktxt").val();
			var dmsg = $("#dateerrtxt").val();
			swal({
				 title: stit,
				 text: dmsg,
				 icon: "error",
				 button: "okay",
				 closeOnEsc: false,
				 closeOnClickOutside: false
				 
			}).then(function(){
				 return false;
			}); 
		} 
	}	
    
	$('#book_services').bootstrapValidator('revalidateField', 'bookingdate');
	
	if(sesserr == 0) {
		$('#from_time').empty();  
		$.ajax({    
			url: base_url+"user/appointment/staff_availability/",
			data : {date:date,provider_id:provider_id, service_id:service_id,staff_id:staff_id, shop_id:shop_id, csrf_token_name:csrf_token, dur_in:dur_in, duration:duration, book_id:book_id,procate:procate, offers:'0'},
			type: "POST",

			success: function(response){      
			  console.log(response);
			  if(response!=''){
				var obj=jQuery.parseJSON(response);   
				$('#from_time').empty(); 
				var seltmetxt = $("#selecttimetxt").val();				
				var msg = 'Select time slot';
				msg = seltmetxt;
				var option = $('<option />'); option.attr('value', "").text(msg);
				$('#from_time').append(option);
				if(obj.time != '')
				{      
					$.each(obj.time, function (idx, val) {
						var option = $('<option />');
							option.attr('value', this.start_time+ '-' +this.end_time).text(this.start_time+ ' - ' +this.end_time); 
							if(this.disable_val){
								option.prop('disabled', this.disable_val); 
							}
							if(book_id > 0) {
								var btime = $("#book_time").val();								
								if(bdate == date) { 
									if(btime != '' && btime == (this.start_time+ '-' +this.end_time)){
										option.prop('selected', "selected");
									}
								}
							}
							$('#from_time').append(option);
					});					
				}
				else if(obj.time == '')
				{
				  $('#from_time').empty(); 	
				  var msg = 'Availability not found';
				  var option = $('<option />'); option.attr('value', "").text(msg);
				  $('#from_time').append(option);
				} 
				
			  }
			}

		
		  });
	}
}

	$(document).ready(function() {
		var book_id = $("#book_id").val();
		if(book_id > 0){
			$('.select').selectpicker();
			var val = $('input[name="service_at"]:checked').val(); 
			if(val == 1){				
				$("#map").removeClass("d-none"); 
				$("#service_location").val($("#service_address").val());
				var val = $('#staff_id').val();
				$('#staff_id option[data-homeservice="0"]').hide().parent().selectpicker('refresh');;
				$('#staff_id option[data-homeservice="1"]').show().parent().selectpicker('refresh');;
				var selarea = $('#staff_id option[value="'+val+'"]').attr("data-homeservicearea");
				if(selarea != '' && selarea != undefined){
					SelArea = selarea;					
				}
				initMap();
			} else {				
				$("#map").addClass("d-none"); 
				$("#service_location").val($("#shoplocation").val());
				$('#staff_id option[data-homeservice="0"]').show().parent().selectpicker('refresh');;
				$('#staff_id option[data-homeservice="1"]').hide().parent().selectpicker('refresh');;
			}			
			$("select#staff_id").val($("#staffid").val());	
			load_timeslot($("#bookeddate" ).val());
			
		}
	});

	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9]/g,'');
	});	 
	
	$(document).on("click", ".nav-tabs a", function(){
		var tab = $(this).attr("aria-controls"); 
		activaTab(tab)
	});
	function activaTab(tab){	
		$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
		if(tab == 'bookings') {
			$("#guests-tab").removeClass("active");
			$("#bookings-tab").addClass("active");
			$("#bookings-tab").parent("li").addClass("active");
			$("#guests").removeClass("active");
			$("#bookings").addClass("active");
			$("#bookings").addClass("show");
			$("#next").show();
			scrolllTop();
		} else {
			$("#bookings-tab").removeClass("active");
			$("#guests-tab").addClass("active");
			$("#guests-tab").parent("li").addClass("active");
			$("#bookings").removeClass("active");
			$("#guests").addClass("active");
			$("#guests").addClass("show");	
			$("#next").hide();
			scrolllTop();
		}
	};
	
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
		checkbooking(provider_id, service_id, book_id, pptype, staff_id, date, time, shop_id, 'from_time');
	});

	function checkbooking(provider_id, service_id, book_id, pptype, staff_id, date, time, shop_id, id ){
		
		var base_url=$('#base_url').val();
		var csrf_token=$('#csrf_token').val();
		$.ajax({
			url: base_url+'user/appointment/check_availability/',
			data: {service_id:service_id, provider_id:provider_id, service_date:date, staff_id:staff_id, book_id:book_id, pptype:pptype,csrf_token_name:csrf_token, time:time, shop_id:shop_id,offers:'0',action:'edit'},			 
			type: 'POST',
			dataType: 'JSON',			 
			success: function(response){ 
				if(response.status != ''){
					swal({
						title: response.title,
						text: response.msg,
						icon: "error",
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false				 
					}).then(function(){
						$("#"+id).val(time);	
					}); 
				}
			}
		})
	}

	
})(jQuery);
$(document).ready(function(){
	$('#staff_id option[data-homeservice="1"]').hide().parent().selectpicker('refresh');;
});
var SelArea = '';

$(".serviceat").on("change", function(){
	var val = $(this).val();
	$('#staff_id').val('');
	
	if(val == 1){		
		$("#map").removeClass("d-none"); 
		$("#service_location").val($("#service_address").val());
		$('#staff_id option[data-homeservice="0"]').hide().parent().selectpicker('refresh');;
		$('#staff_id option[data-homeservice="1"]').show().parent().selectpicker('refresh');;
		SelArea = ''; initMap();	
		
	} else {		
		$("#map").addClass("d-none"); 
		$("#service_location").val($("#shoplocation").val());
		$('#staff_id option[data-homeservice="0"]').show().parent().selectpicker('refresh');;
		$('#staff_id option[data-homeservice="1"]').hide().parent().selectpicker('refresh');;
		
	}	
	
	
});

$("#staff_id").on("change", function(){
	$("#bookingdate").val('');$("#from_time").empty();	
	var val = $(this).val();
	var selarea = $('#staff_id option[value="'+val+'"]').attr("data-homeservicearea");
	if(selarea != '' && selarea != undefined){
		SelArea = selarea;
		initMap();
	}
});


	
    var placeSearch, autocomplete, user_addr;
	
	function initMap() {
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
    
    function load_msp_details(latitude, longitude) {
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
      google.maps.event.addListener(marker, 'dragend', function() {
          geocoder.geocode({
              'latLng': marker.getPosition(),
			  language:language_option
          }, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {	
                  if (results[0]) {
                      address = results[0].formatted_address;
                      latitude = marker.getPosition().lat();
                      longitude = marker.getPosition().lng();
                      $('#service_location').val(address);
                      $('#service_latitude').val(latitude);
                      $('#service_longitude').val(longitude);
					  if($('input[name="service_at"]:checked').val() == 1){
						$('#service_address').val(address);
					 }
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
	
	