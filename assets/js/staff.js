(function($) {
	"use strict";
	
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	var amtxt = $("#staff_amtxtval").val();
	var pmtxt = $("#staff_pmtxtval").val();
	var seltxt = $("#sltxtval").val();
	
	$( document ).ready(function() {
		
		$('.select_from_time').on('change',function(){
			var id=$(this).attr('data-id');
			select_from_time(id);
		}); 
		$('.validate_time').on('click',function(){
			var id=$(this).attr('data-id');
			validate_time(id);
		}); 
		$('.days_check').on('click', function(){
			if ($(".eachdays").prop('checked')==false){
				$('.eachdayfromtime[value=""]').attr('selected', 'selected');
				$('.eachdayfromtime').val("").trigger("change");
			}
		});
		$('#shop_id').on('change',function(){
			var id=$(this).val();
			set_shop_location(id);
		});
		
		if($("#staff_id_value").val() > 0)	{
			set_shop_location($("#shop_id").val());
		}

		$("#addstaff").submit(function(e){
	       
	        $('#previous').trigger('click'); 
			var n=0;	
			var staff_name = $('#firstname').val();
			var mobileno = $('#mobileno').val();
			var email = $('#email').val();
			var dob = $('#dob').val();
			var about = $('#about').val();
			staff_name = $.trim(staff_name);
			if(staff_name.length == 0){
				$(".firstname").text('Please Enter Staff Name');
				n=1;		
			}else{
				$(".firstname").text('');
			}
			mobileno = $.trim(mobileno);
			if(mobileno.length == 0){
				$(".errexistno").text('Please Enter Mobileno');
				n=1;
			}else{
				$(".errexistno").text('');
			}
			dob = $.trim(dob);
			var existsno = $("#exists_no").val();
		    if (dob.length == 0) {
		        $("#errdob").text('Please Enter DOB');
		        n = 1;
		    } else {
		        $("#errdob").text('');
		    }
			
		    if (email.length == 0) {
		       $("#errexistmail").text('Please Enter Email Address');
		        n = 1;
		    } else {
				if (/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(email)){			
					$("#errexistmail").text('');
				} else {
					$("#errexistmail").text('Please Enter Valid Email Address');
					n = 1;
				}
		    }	
			
		    if (about.length == 0) {
		        $("#errabout").text('Please Enter Description');
		        n = 1;
		    } else {
		        $("#errabout").text('');
		    }

			if (n == 0) {
				$(".available_title").text('');
		        var daycount = 0;
		        if (!$('.err_check').is(':checked')) {
		            daycount++;
		        }
		        if (daycount != 0) {
		            $('.daysfromtime_check').attr('style', 'border-color:red');
		            $('.daystotime_check').attr('style', 'border-color:red');
		            $('.eachdayfromtime').attr('style', 'border-color:red');
		            $('.eachdaytotime').attr('style', 'border-color:red');
					$(".available_title").text('Please Select Day and Relevant From & To Time');
					 e.preventDefault();
		            return false;
		        }
				
		        if (!subCheckAvailable()) {
					$(".available_title").text('Please Select Day and Relevant From & To Time');
					 e.preventDefault();
		            return false;
		        }
		        return true;

		    } else {
				 e.preventDefault();
		        return false;
		    }
	    });
	});
	
	
	function set_shop_location(id){
		if(id!=''){
			$.ajax({
				type: "POST",
				url: base_url+"user/service/get_shop_location",
				data:{id:id,csrf_token_name:csrf_token}, 
				dataType:'json',				
				success: function (data) {	
					if(data!=''){
						$("#shp_loc").val(data.loc);
						$("#shp_lat").val(data.lat);
						$("#shp_lng").val(data.lng);
						initialize()
					}
				}
			});
		}
	}
	
function subCheckAvailable() {
    var test = true;
    if ($(".days_check").prop('checked') == true) {
        var all_from = $(".daysfromtime_check").val();
        var all_to = $(".daystotime_check").val();

        if (all_from == '' || all_to == '') {
            $('.daysfromtime_check').attr('style', 'border-color:red');
            $('.daystotime_check').attr('style', 'border-color:red');
            test = false;
        }

    } else {
        var row = 1;
        $('.eachdays').each(function() {
            if ($(".eachdays" + row).prop('checked') == true) {
                var from_time = $('.eachdayfromtime' + row).val();
                var to_time = $('.eachdaytotime' + row).val();
                if (from_time == '' || to_time == '') {
                    $('.eachdayfromtime' + row).attr('style', 'border-color:red');
                    $('.eachdaytotime' + row).attr('style', 'border-color:red');

                    test = false;
                } else {
                    $('.eachdayfromtime' + row).removeAttr("style");
                    $('.eachdaytotime' + row).removeAttr("style");
                }
            }

            /*from time validate*/
            if ($('.eachdayfromtime' + row).val() != '') {
                var to_time = $('.eachdaytotime' + row).val();
                if ($(".eachdays" + row).prop('checked') == false || to_time == '') {
                    $('.eachdaytotime' + row).attr('style', 'border-color:red');
                    $('.eachdayfromtime' + row).removeAttr("style");
                    test = false;
                }
            }

            /*to time Validate*/
            if ($('.eachdaytotime' + row).val() != '') {
                var from_time = $('.eachdaytotime' + row).val();
                if ($(".eachdays" + row).prop('checked') == false || from_time == '') {
                    $('.eachdayfromtime' + row).attr('style', 'border-color:red');
                    $('.eachdaytotime' + row).removeAttr("style");
                    test = false;
                }
            }
            row = row + 1;
        })

    }

    return test;

}	
	/* Availability Day & Time */
	function select_from_time(id){
		var time = $(".eachdayfromtime"+id).val();
		var time_digit = parseInt(time);
		var select_html  = '<option value="">'+seltxt+'</option>';

		for(var i=1; i<=23; i++){
			var nexttime =  parseInt(i);
			if(nexttime.toString().length < 2){
				nexttime = '0'+ parseInt(nexttime);
			}

			var timeval = nexttime+':00:00';
			var timeString = nexttime+':00:00';
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = H < 12 ? " "+amtxt : " "+pmtxt;
			timeString = h + timeString.substr(2, 3) + ampm;

			if(time_digit != i && time_digit < i){
				select_html += '<option value="'+timeString+'">'+timeString+'</option>';
			}
		}
		select_html += '<option value="12:00 "'+amtxt+'>12:00 '+amtxt+'</option>';
		$('.eachdaytotime'+id).html(select_html);
	}
	
	function validate_time(id){
		if($('.eachdays'+id).prop('checked')==true){
			$('.eachdayfromtime'+id).val('');
			$('.eachdaytotime'+id).val('');

			var t_val=0;
			$(".err_check").each(function(){
				if ($(this).prop('checked')==true){ 
					t_val+=Number($(this).val());
					$('.eachdayfromtime'+id).val('');
					$('.eachdaytotime'+id).val('');
				}

				if(t_val==0){
					$("#time_submit").attr("disabled", true);
				}else{
					$("#time_submit").removeAttr("disabled");
				}
			})
		}else{ 
			$(".err_check").each(function(){
				if ($(this).prop('checked')==false){  
					t_val+=Number($(this).val());
					$('.eachdayfromtime'+id+'[value=""]').attr('selected', 'selected');
					$('.eachdayfromtime'+id).val("").trigger("change");
					select_from_time(id);
				}


			})
		}
	}
	$(document).on('change','.daysfromtime_check',function(){
		var time = $(this).val();
		var time_digit = parseInt(time);

		var select_html  = '<option value="">'+seltxt+'</option>';

		for(var i=1; i<=23; i++){

			var nexttime =  parseInt(i);
			if(nexttime.toString().length < 2){
				nexttime = '0'+ parseInt(nexttime);
			}

			var timeval = nexttime+':00:00';
			var timeString = nexttime+':00:00';
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = H < 12 ? " "+amtxt : " "+pmtxt;
			timeString = h + timeString.substr(2, 3) + ampm;

			if(time_digit != i && time_digit < i){
				select_html += '<option value="'+timeString+'">'+timeString+'</option>';
			}
		}
		select_html += '<option value="12:00 "'+amtxt+'>12:00 '+amtxt+'</option>';
		$('.daystotime_check').html(select_html);
	});
	/* Availability Day & Time */

	$(function() {
		$(function() {
			$( ".staff_datepicker" ).datepicker({ 
				dateFormat: 'dd-mm-yy', 
				changeMonth: true, 
				changeYear: true,
				yearRange: "-122:-18",
				maxDate: "-18Y",
				minDate: "-122Y"
			}); 
		}); 
	});
	
$(document).on("click", ".nav-tabs a", function(){
	var tab = $(this).attr("aria-controls"); 
	activaTab(tab)
});
function activaTab(tab){	
	if(tab == 'personal') {
		$("#next").show();
		$('#previous').trigger('click'); 
	} else {
		$('#next').trigger('click'); 
	}
};
$('#next').on("click", function(){	
	$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
	$("#personal-tab").removeClass("active");
	$("#service-tab").addClass("active");
	$("#service-tab").parent("li").addClass("active");
	$("#personal").removeClass("active");
	$("#service").addClass("active");
	$("#service").addClass("show");	
	$("#next").hide();
    scrolTop();	
});
$('#previous').on("click", function(){	
	$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
	$("#service-tab").removeClass("active");
	$("#personal-tab").addClass("active");
	$("#personal-tab").parent("li").addClass("active");
	$("#service").removeClass("active");
	$("#personal").addClass("active");
	$("#personal").addClass("show");
	$("#next").show();
	scrolTop();
});
function scrolTop(){
	$('html, body').animate({
		scrollTop: jQuery("body").offset().top
	}, 100);
}	
$(document).on('click','.days_check',function(){
   var from_time = '';
   var to_time = '';
   if($('.daysfromtime_check').val()){
	   var from_time = $('.daysfromtime_check').val();
   }
   if($('.daystotime_check').val()){
	   var to_time = $('.daystotime_check').val();
   }
 if($(this).is(':checked') == true){
	 if(from_time == '') from_time = '09:00 AM';
	 if(to_time == '') to_time = '06:00 PM';
  $('.daysfromtime_check').val(from_time);
  $('.daystotime_check').val(to_time);
  $('.eachdays').attr('disabled','disabled');
  $('.eachdayfromtime').attr('disabled','disabled');
  $('.eachdaytotime').attr('disabled','disabled');
  $('.eachdayfromtime').val('');
  $('.eachdaytotime').val('');
  $('.eachdays').prop('checked', false);
  $('.eachdays').removeAttr('style');
  $('.eachdayfromtime').removeAttr('style');
  $('.eachdaytotime').removeAttr('style');

}else{
 $('.eachdays').removeAttr('disabled');
 $('.eachdayfromtime').removeAttr('disabled');
 $('.eachdaytotime').removeAttr('disabled');

 $('.daysfromtime_check').val('');
 $('.daystotime_check').val('');
 $('.daysfromtime_check').removeAttr('style');
 $('.daystotime_check').removeAttr('style');
}

});

$(document).on('click','.staff-service',function() {
	
	var id = $(this).attr("data-id");
	var action = $(this).attr("data-text");
	
	var delete_title = action+" Staff";
	var delete_msg = "Are you sure want to "+action+" this staff?";
	var delete_text = "Staff has been "+action;
	
	$('#statusConfirmModal').modal('toggle');
	$('#acc_title').html('<span>'+delete_title+'</span>');
	$('#acc_msg').html(delete_msg);
	
	$(document).on('click','.si_accept_confirm',function(){	
		$.ajax({
			url: base_url+"user/service/change_user_status",
			data:{id:id,action:action,csrf_token_name:csrf_token}, 
			type:"POST",
			beforeSend:function(){
				$('#statusConfirmModal').modal('toggle');
			},
			success: function(res){ 
				window.location.reload();
			}
		});
	});
	$(document).on('click','.si_accept_cancel',function(){
	});
});
$(document).on('click','.staff-delete',function() {
	
	var id = $(this).attr("data-id");	
	
	var delete_title = "Delete Staff";
	var delete_msg = "Are you sure want to delete staff?";
	var delete_text = "Staff Details has been deleted";
	
	$('#staffdeleteConfirmModal').modal('toggle');
	$('#acc_title').html('<span>'+delete_title+'</span>');
	$('#acc_msg').html(delete_msg);
	
	$(document).on('click','.si_accept_confirm',function(){	
		$.ajax({
			url: base_url+"user/service/delete_staff",
			data:{id:id,csrf_token_name:csrf_token}, 
			type:"POST",
			beforeSend:function(){
				$('#staffdeleteConfirmModal').modal('toggle');
			},
			success: function(res){ 
				window.location.reload();
			}
		});
	});
	$(document).on('click','.si_accept_cancel',function(){
	});
});
$('.staffmobile').on('keyup blur',function(){
      var mobile=$(this).val();
	  var c_code = $("#country_code").val();
	  var sid = $("#staff_id_value").val();
      check_mobileexists(mobile,sid,c_code);
 });
function check_mobileexists(mobile,sid,c_code){      
    if(mobile.length > 0){ 
		$("#mobileno").removeAttr('style');	
       $.ajax({
         type: "POST",
         url: base_url + 'user/service/staff_check_mobile',
         data:{ 
           'userMobile':mobile,           
           'csrf_token_name':csrf_token,
		   'sid':sid,
		   'mobileCode':c_code
         },         
         success: function (res) { 
          var res=jQuery.parseJSON(res);		
          if(res.data > 0){
            $('#errexistno').html("Existing Contact No");
			$("#mobileno").attr('style', 'border-color:red');
            $('#addstaff_submit').attr('disabled',true);
			$("#existsno").val(res.data);
         }else{  
			if(mobile.length < 10){ 
				$('#errexistno').html("Enter Valid Contact No");
				$("#mobileno").attr('style', 'border-color:red');
				$('#addstaff_submit').attr('disabled',true);
				$("#existsno").val('1');
			} else {
			  $('#errexistno').html('');      
			  $("#mobileno").removeAttr('style').removeClass('error_red');		  
			  $('#addstaff_submit').attr('disabled',false);
			  $("#existsno").val(res.data);
			}
        }

      }
	 })//ajax
    }  else {
		$("#mobileno").attr('style', 'border-color:red');
		$('#errexistno').html('');      
	}		
 }
$('.staffemail').on('keyup blur',function(){
      var email=$(this).val();
	  var sid = $("#staff_id_value").val();
      check_emailexists(email,sid);
 });
function check_emailexists(email,sid){   
	email = $.trim(email);
    if(email.length > 0){ 
		$("#email").removeAttr('style');
       $.ajax({
         type: "POST",
         url: base_url + 'user/service/staff_check_emailid',
         data:{ 
           'userEmail':email,           
           'csrf_token_name':csrf_token,
		   'sid':sid
         },         
         success: function (res) { 
          var res=jQuery.parseJSON(res);		
          if(res.data > 0){
            $('#errexistmail').html("Existing Email ID");
			$("#email").attr('style', 'border-color:red');
            $('#addstaff_submit').attr('disabled',true);
			$("#existsmail").val(res.data);
         }else{  
		  $('#errexistmail').html('');      
		  $("#email").removeAttr('style').removeClass('error_red');		  
          $('#addstaff_submit').attr('disabled',false);
		  $("#existsmail").val(res.data);
        }

      }
	 })//ajax
    }  else {
		$("#email").attr('style', 'border-color:red');
		$('#errexistmail').html(''); 
	}	
	
 }
	
})(jQuery);