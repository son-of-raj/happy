(function($) {
	"use strict";

	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	
	var amtxt = $("#amtxtval").val();
	var pmtxt = $("#pmtxtval").val();
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
		$('#update_service').on('submit',function(){
			var result=subCheck();
			return result;
		}); 
		
	});
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
			
			var x = 30; //minutes interval
var times = []; // time array
var tt = 0; // start time
var ap = ['AM', 'PM']; // AM-PM

			var hh = Math.floor(time_digit/60); // getting hours of day in 0-24 format
			var mm = (time_digit%60); // getting minutes of the hour in 0-55 format
			times[i] = ("0" + (hh % 12)).slice(-2) + ':' + ("0" + mm).slice(-2) + ap[Math.floor(hh/12)]; // pushing data in array in [00:00 - 12:00 AM/PM format]
			tt = tt + x;
		}
		select_html += '<option value="12:00 "'+amtxt+'>12:00 '+amtxt+'</option>';
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

})(jQuery);