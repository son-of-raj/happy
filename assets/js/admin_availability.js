(function($) {
	"use strict";

	var csrf_token=$('#admin_csrf').val();
	var base_url=$('#base_url').val();
	
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
	});
		
		
	$('#staff_edit').bootstrapValidator({
		fields: {
		  contact_no:   {
			validators: {
			  remote: {
			  notEmpty: {
				message: 'Please Enter Mobile Number'

			  }
			}
		  },
		email:   {
			validators: {
			  remote: {
			  notEmpty: {
				message: 'Please Enter Mobile Number'

			  }
			}
		  },
		first_name:           {
			validators:           {
			  notEmpty:               {
				message: 'Please Enter First Name'
			  }
			}
		  },
		  last_name:           {
			validators:           {
			  notEmpty:               {
				message: 'Please Enter Last Name'
			  }
			}
		  },
		country_code:           {
			validators:           {
			  notEmpty:               {
				message: 'Please Select Country Code'
			  }
			}
		  }
		}
	}).on('success.form.bv', function(e) {	  
		var result=subCheck();
		return result;
	}); 
		
	$('#shop_edit').bootstrapValidator({
		fields: {
		  contact_no:   {
			validators: {
			  remote: {
				url: base_url + 'shop/check-shop-mobile',
				data: function(validator) {
				  return {
					userMobile: validator.getFieldElements('contact_no').val(),
					mobileCode:validator.getFieldElements('country_code').val(),
					userid:validator.getFieldElements('user_id').val(),
					csrf_token_name:csrf_token
				  };
				},
				message: 'This Mobile Number is already exist',
				type: 'POST'
			  },
			  notEmpty: {
				message: 'Please Enter Mobile Number'

			  }
			}
		  },
		email:   {
			validators: {
			  remote: {
				url: base_url + 'shop/check-shop-emailid',
				data: function(validator) {
				  return {
					userEmail: validator.getFieldElements('email').val(),
					userid:validator.getFieldElements('user_id').val(),
					csrf_token_name:csrf_token
				  };
				},
				message: 'This Email Address is already exist',
				type: 'POST'
			  },
			  notEmpty: {
				message: 'Please Enter Mobile Number'

			  }
			}
		  },
		shop_name:           {
			validators:           {
			  notEmpty:               {
				message: 'Please Enter Shop Name'
			  }
			}
		  },		  
		country_code:           {
			validators:           {
			  notEmpty:               {
				message: 'Please Select Country Code'
			  }
			}
		  }
		}
	}).on('success.form.bv', function(e) {	  
		var result=subCheck();
		return result;
	}); 

		
		
		
		
	$(document).on('change','.daysfromtime_check',function(){
		var time = $(this).val();
		var time_digit = parseInt(time);

		var select_html  = '<option value="">Select Time</option>';

		for(var i=1; i<=23; i++){

			var nexttime =  parseInt(i);
			if(nexttime.toString().length < 2){
				nexttime = '0'+ parseInt(nexttime);
			}

			var timeval = nexttime+':00:00';
			var timeString = nexttime+':00:00';
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = H < 12 ? " AM" : " PM";
			timeString = h + timeString.substr(2, 3) + ampm;

			if(time_digit != i && time_digit < i){
				select_html += '<option value="'+timeString+'">'+timeString+'</option>';
			}
		}
		select_html += '<option value="12:00 AM">12:00 AM</option>';
		$('.daystotime_check').html(select_html);
	});


	function select_from_time(id){
		var time = $(".eachdayfromtime"+id).val();
		var time_digit = parseInt(time);
		var select_html  = '<option value="">Select Time</option>';

		for(var i=1; i<=23; i++){
			var nexttime =  parseInt(i);
			if(nexttime.toString().length < 2){
				nexttime = '0'+ parseInt(nexttime);
			}

			var timeval = nexttime+':00:00';
			var timeString = nexttime+':00:00';
			var H = +timeString.substr(0, 2);
			var h = H % 12 || 12;
			var ampm = H < 12 ? " AM" : " PM";
			timeString = h + timeString.substr(2, 3) + ampm;

			if(time_digit != i && time_digit < i){
				select_html += '<option value="'+timeString+'">'+timeString+'</option>';
			}
		}
		select_html += '<option value="12:00 AM">12:00 AM</option>';
		$('.eachdaytotime'+id).html(select_html);
	}
       
	function subCheck() {
		var test =true; 
		if ($(".days_check").prop('checked')==true){
			var all_from=$(".daysfromtime_check").val();
			var all_to=$(".daystotime_check").val();

			if(all_from=='' || all_to==''){
				swal({
					title: "Wrong Selection !",
					text: "Please Select Day Relevant From & To Time....!",
					icon: "error",
					button: "okay",
				});
				test=false;

			}

		}else{
			var row=1;
			$('.eachdays').each(function(){
				if ($(".eachdays"+row).prop('checked')==true){
					var from_time=$('.eachdayfromtime'+row).val();
					var to_time=$('.eachdaytotime'+row).val();
					if(from_time=='' || to_time==''){
						swal({
							title: "Wrong Selection...!",
							text: "Please Select Day Relevant From & To Time....!",
							icon: "error",
							button: "okay",
						});

						test=false;
					}

				}

				/*from time validate*/

				if($('.eachdayfromtime'+row).val() !=''){


					var to_time=$('.eachdaytotime'+row).val();

					if($(".eachdays"+row).prop('checked')==false || to_time ==''){
						swal({
							title: "Wrong Selection...!",
							text: "Please Select All Day Relevant From & To Time....!",
							icon: "error",
							button: "okay",
						});

						test=false;
					}

				}

				/*to time Validate*/

				if($('.eachdaytotime'+row).val()!=''){
					var from_time=$('.eachdaytotime'+row).val();
					if($(".eachdays"+row).prop('checked')==false || from_time ==''){
						swal({
							title: "Wrong Selection...!",
							text: "Please Select Day Relevant From & To Time....!",
							icon: "error",
							button: "okay",
						});

						test=false;
					}

				}
				row=row+1;   
			})

		}

		return test;

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
	
	$(document).on('click','.days_check',function(){	  
		if($(this).is(':checked') == false){ 
			$('.daysfromtime_check').val('');
			$('.daystotime_check').val('');		 
		}
	});

})(jQuery);