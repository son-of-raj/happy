(function($) {
	"use strict";

  var base_url=$('#base_url').val();
  var BASE_URL=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();
  var user_type=$('#user_type').val();
  var modules=$('#modules_page').val();
  
    $(document).on('click','.vaccine_confirm',function(e){	
		e.preventDefault();
		var cov_vac = $('input[name="covid"]:checked').val();
		$.ajax({
			url: base_url+'user/service/check_covidvaccine/',
			data: {cov_vac:cov_vac, csrf_token_name:csrf_token},
			type: 'POST',
			dataType: 'JSON',
			 beforeSend: function() {
				$("#corcontrolModal").modal("toggle");;
			  },
			success: function(res){
				console.log(res);
				if(res.status==1){
					swal({
					   title: "COVID-19 Vaccine",
					   text: res.msg,
					   icon: "success",
					   button: "okay",
					   closeOnEsc: false,
					   closeOnClickOutside: false
					}).then(function(){
					  location.reload();
					});					
				}else if(res.status==2){
					swal({
					   title: "COVID-19 Vaccine",
					   text: res.msg,
					   icon: "error",
					   button: "okay",
					   closeOnEsc: false,
					   closeOnClickOutside: false
					}).then(function(){
						window.location = base_url;
					});
				}
			}
		});
    });
	$(document).on('click','.vaccine_cancel, .close',function(){
		$(".covid").prop('checked', false);
	});
	$(".vaccination_status").on("click", function(){	
		swal({
		   title: "COVID-19 Vaccine",
		   text: "you're not allow to book any service for now due to local authority",
		   icon: "error",
		   button: "okay",
		   closeOnEsc: false,
		   closeOnClickOutside: false
		}).then(function(){
		   window.location = base_url;
		});
	});
  
 })(jQuery);