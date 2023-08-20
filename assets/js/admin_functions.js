(function($) {
	"use strict";
	
	var csrf_token=$('#admin_csrf').val();
	var base_url=$('#base_url').val();
	var page=$('#page').val();
	var provider_list_url=$('#provider_list_url').val();
	var requests_list_url=$('#requests_list_url').val();
	var user_list_url=$('#user_list_url').val();
	var adminuser_list_url=$('#adminuser_list_url').val();
	
	var staff_list_url=$('#staff_list_url').val();
	
	$('.example1').checkboxall();
	
	$( document ).ready(function() {

	$('#img_upload_error').hide();
	$('#img_upload_errors').hide();
	setTimeout(function(){ $('#flash_success_message').hide(); }, 5000);
	setTimeout(function(){ $('#flash_succ_message').hide(); }, 5000);
	setTimeout(function(){ $('#flash_error_message').hide(); }, 5000);
	$('.change_Status_Service').on('click',function(){
		var id=$(this).attr('data-id');
      change_Status_Service(id);
	});
     $('.delete_review_comment').on('click',function(){
		var id=$(this).attr('data-id');
      delete_review_comment(id);
    }); 
	

    $('.change_Status_rating').on('click',function(){
		var id=$(this).attr('data-id');
      change_Status_rating(id);
    }); $('.delete_service_provider').on('click',function(){
		var id=$(this).attr('data-id');
      delete_service_provider(id);
    }); 

    $(document).on("change",".language_tag",function(e){
		var id=$(this).attr('data-id');
      	change_language_tag(id);
	});

	$(document).on("change",".language_status",function(e){
		var id=$(this).attr('data-id');
      	update_lang_status(id);
	});

    $('.update_language').on('click',function(){
		var lang_key=$(this).attr('data-lang-key');
		var lang=$(this).attr('data-lang');
		var page=$(this).attr('data-page');
      update_language(lang_key,lang,page);
    }); 
     $('#reject_payment_submit').on('submit',function(){
     var result=reject_payment_submit();
     return result;
    }); 
     
    $('.changebooking').on('change', function()
    {
  		var statusId = $(this).val();
  		if(statusId) 
  		{
  			var b_rowid = $(this).attr('data-id');
  			var b_userid = $(this).attr('data-userid');
  			var b_providerid = $(this).attr('data-providerid');
  			var b_serviceid = $(this).attr('data-serviceid');
  			swal({
 				title: "Are you sure about this process ?",
 				text: "",
 				icon: "warning",
 				buttons: true,
 				dangerMode: true,
			}).then((willDelete) => {
			if (willDelete) {
  				var url = base_url + 'admin/booking/change_status_byAdmin';
			  	var data = {
			  			status: statusId,
		  				id: b_rowid,
						user_id: b_userid,
						provider_id: b_providerid,
						service_id: b_serviceid,
						csrf_token_name:csrf_token
				};
				$.ajax({
					url: url,
					data: data,
					type: "POST",
					dataType: 'json',
					success: function (response) 
					{
						if(response=='3') { // session expiry
				             swal({
				               title: "Session was Expired... !",
				               text: "Session Was Expired ..",
				               icon: "error",
				               button: "okay",
				               closeOnEsc: false,
				               closeOnClickOutside: false
				             }).then(function(){
				               window.location.reload();
				             });
           				}
           				if(response=='2'){ //not updated
							swal({
								title: "Somethings wrong !",
								text: "Somethings wents to wrongs",
								icon: "error",
								button: "okay",
								closeOnEsc: false,
								closeOnClickOutside: false
							}).then(function(){
								window.location.reload();
							});
						}
       
						if(response=='1'){ //updated
							swal({
								title: "Updated the booking status !",
								text: "Service is Updated successfully...",
								icon: "success",
								button: "okay",
								closeOnEsc: false,
								closeOnClickOutside: false
							}).then(function(){
								window.location.href=base_url+'admin/total-report';
							});
						}  
					}
				});
			} 
			else 
			{
				return false;
			}
			});

  		}
  	});
	
	 $(document).on("click",".delete_subcategories",function() {
		var id = $(this).attr('data-id');
		delete_subcategories(id);
	});
	$(document).on("click",".delete_sub_subcategories",function() {
		var id = $(this).attr('data-id');
		delete_sub_subcategories(id);
	});	
    $('.reply_contact').on('click', function () {
	  var id = $(this).attr('data-id');
	  var umail = $(this).attr('data-mail');
	  var uname = $(this).attr('data-uname');
	  reply_contact(id,uname,umail);
	});
	$(document).on("click",".delete_categories",function() {
	  var id = $(this).attr('data-id');
	  delete_categories(id);
	});
    $(document).on('click', '.change_Status_user2', function() {
        var id = $(this).attr('data-id');
        change_Status_user2(id);
    });
    $(document).on('click', '.delete_country_code_config', function() {
      var id = $(this).attr('data-id');
      delete_country_code_config(id);
    });
	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9]/g,'');
	});	
	$('.state-lists table').on('click','.delete_state_code_config', function () {		
	  var id = $(this).attr('data-id');
	  delete_state_code_config(id);
	});	
	$('.city-lists table').on('click','.delete_city_code_config', function () {		
	  var id = $(this).attr('data-id');
	  delete_city_code_config(id);
	}); 
	$(".subscriptions-info").on('click','.trash', function () {
		var trCount = $('#append tr.singlerow').length;
		if(trCount > 1){
			$(this).closest('.singlerow').remove();
		} else {
			alert("Please Provide Atleast One Subscriptions Details");
		}
		 return false;
	});

	$(document).on("click",".add-subscriptions",function () {
	    var len = $('.singlerow').length + 1;
	    if(len <= 5) {
	       var subscriptioncontent = 
			  '<tr class="singlerow">'+
				'<td><input type="text" class="form-control" name="subscriptionsdetails[]" value="" required></td>'+			
				'<td><a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a></td>'+
			  '</tr>';	
			$("#append > tbody:last-child").append(subscriptioncontent);
			return false;
	    } else {
	        $('.add-subscriptions').show();
	        alert('Allow 5 Subscriptions Details only');
	    }
	});

	$(document).on("change",".category_change",function() {
		var id = $(this).val();
		load_subcategories(id); 
	});
	$(document).on("change",".subcategory_change",function() {
		var sid = $(this).val();
		var cid = $(".category_change").val();
		load_subsubcategories(cid,sid); 
	});	
    $('.delete_footer_menu').on('click', function () {
		var id = $(this).attr('data-id');
		delete_footer_menu(id);
	 });
	$('.delete_footer_submenu').on('click', function () {   
		var id = $(this).attr('data-id');
		delete_footer_submenu(id);
	});
	$('#menu_status').on('click',function(){
		$('#sub_menu').attr('required', 'required');
		$('.sub_menu').show();
	});           
	$('#menu_status_one').on('click',function(){
		$('#sub_menu').removeAttr('required', 'required');
		$('.sub_menu').hide();
	});

    $('#language_web_table').DataTable({
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		
		"ajax": {
			"url": base_url + 'language-web-list',
			"type": "POST",
			"data":function(data)
			{
			data.csrf_token_name =$('#web_csrf').val();
			}
        },
        "columnDefs": [
        {
            "targets": [  ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });

    $('#language_app_table').DataTable({
		"processing": true, //Feature control the processing indicator.
		"serverSide": true, //Feature control DataTables' server-side processing mode.
		"order": [], //Initial no order.
		"ajax": {
			"url": base_url + 'admin/language/language_list',
			"type": "POST",
			
			"data":function(data)
			{
			 data.csrf_token_name = $('#app_csrf').val();
			 data.page_key = $('#lan_page_id').val(),
			 data.lang_key = $('#lan_lang_id').val()		 
			}
                
        },
        "columnDefs": [
        {
            "targets": [  ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],

    });
	$('.verify_provider_commercial').on('click', function () {
		var id = $(this).attr('data-id');
		verify_provider_commercial(id);
	});
	
    var country_key = $('#country_code_key').val();
    var mobileno = $('#mobileno').val();
	if($("#mobileno, #user_mobile, #userMobile").length >0 ) {
		if(mobileno != '') {
			$("#mobileno, #user_mobile, #userMobile").intlTelInput({
				separateDialCode: true,
				initialCountry : country_key,
				}).on('countrychange', function (e, countryData) {
					var cc = $("#mobileno, #user_mobile, #userMobile").intlTelInput("getSelectedCountryData").dialCode;
					$('#country_code').val(cc);
				}).on('done', function () {
				  $("#mobileno, #user_mobile, #userMobile").intlTelInput("setNumber", mobileno);
				})
		} else {
			$("#mobileno, #user_mobile, #userMobile").intlTelInput({
				separateDialCode: true,
				nationalMode: true,
				initialCountry : country_key,
				utilsScript: "//cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js"
			}).on('countrychange', function (e, countryData) {
				var cc = $("#mobileno, #user_mobile, #userMobile").intlTelInput("getSelectedCountryData").dialCode;
				$('#country_code').val(cc);
			});
		}
	}
    $(".check_key_name").keypress(function(event){
		var inputValue = event.which;
		if((!(inputValue >= 65 && inputValue <= 90) && !(inputValue >= 97 && inputValue <= 120) ) && inputValue != 95) { 
			event.preventDefault(); 
		}
	});
});

	function verify_provider_commercial(val) {
		bootbox.confirm("Do you want to approve? ", function (result) {
			if (result == true) {
			  var url = base_url +'admin/service/verify_provider_commercial';
			  var data = { 
				user_id: val,
				csrf_token_name:csrf_token
			  };
			  $.ajax({
				url: url,
				data: data,
				type: "POST",
				success: function (res) {
				  if (res == 1) {
					$("#flash_success_message").show();
					window.location = base_url + 'freelancer-details/'+val;
				  } else {
					window.location = base_url + 'freelancer-details/'+val;
				  }
				}
			  });
			}
		  });
	}

    function delete_footer_submenu(val) {
		bootbox.confirm("Are you sure want to Delete ? ", function (result) {
		  if (result == true) {
			var url = base_url + 'admin/footer_submenu/delete_footer_submenu';
			var tbl_id = val;
			var data = { 
				tbl_id: tbl_id,
				csrf_token_name:csrf_token
			};
			$.ajax({
			  url: url,
			  data: data,
			  type: "POST",
			  success: function (res) {
				if (res == 1) {
				  window.location = base_url + 'admin/footer_submenu';
				} else {
				  window.location = base_url + 'admin/footer_submenu';
				}
			  }
			});
		  }
		});
	}

    function delete_footer_menu(val) {
		bootbox.confirm("Are you sure want to Delete ? ", function (result) {
		  if (result == true) {
			var url = base_url + 'admin/footer_menu/delete_footer_menu';
			var tbl_id = val;
			var data = { 
				tbl_id: tbl_id,
				csrf_token_name:csrf_token
			};
			$.ajax({
			  url: url,
			  data: data,
			  type: "POST",
			  success: function (res) {
				if (res == 1) {
				  window.location = base_url + 'admin/footer_menu';
				} else {
				  window.location = base_url + 'admin/footer_menu';
				}
			  }
			});
		  }
		});
    }
	function load_subcategories(val){
		var url = base_url + 'admin/categories/load_subcategory';
		var category_id = val;
		var data = { 
			category_id: category_id,
			csrf_token_name:csrf_token
		};

		  $.ajax({
			url: url,
			data: data,
			type: "POST",					
			success: function (res) { 	
				$(".subcategory_change").empty();
				$(".subcategory_change").append(res);
				$(".subsubcategory_change").empty();
				$(".subsubcategory_change").append('<option value="">Select Sub Sub Category</option>');	
			}
		  });
		  
	}
	function load_subsubcategories(val1, val2){
		var url = base_url + 'admin/categories/load_subsubcategory';
		var category_id = val1;
		var subcategory_id = val2;
		var data = { 
			category_id : category_id,
			subcategory_id: subcategory_id,
			csrf_token_name:csrf_token
		};
		  $.ajax({
			url: url,
			data: data,
			type: "POST",					
			success: function (res) { 	
				$(".subsubcategory_change").empty();
				$(".subsubcategory_change").append(res);					 
			}
		  });
		
		  
	}

	function delete_city_code_config(val) {
	  bootbox.confirm("Are you sure want to Delete ? ", function (result) {
		if (result == true) {
		  var url = base_url + 'admin/city_code_config/delete_city_code_config';
		  var tbl_id = val;
		  var data = { 
			  tbl_id: tbl_id,
			  csrf_token_name:csrf_token
		};

		  $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				window.location = base_url + 'admin/city_code_config';
			  } else {
				window.location = base_url + 'admin/city_code_config';
			  }
			}
		  });
		}
	  });
	}
	function delete_state_code_config(val) {
		  bootbox.confirm("Are you sure want to Delete ? ", function (result) {
			if (result == true) {
			  var url = base_url + 'admin/state_code_config/delete_state_code_config';
			  var tbl_id = val;
			  var data = { 
				  tbl_id: tbl_id,
				  csrf_token_name:csrf_token
			};

			  $.ajax({
				url: url,
				data: data,
				type: "POST",
				success: function (res) {
				  if (res == 1) {
					window.location = base_url + 'admin/state_code_config';
				  } else {
					window.location = base_url + 'admin/state_code_config';
				  }
				}
			  });
			}
		  });
	}
    function delete_country_code_config(val) {
	  bootbox.confirm("Are you sure want to Delete ? ", function (result) {
		if (result == true) {
		  var url = base_url + 'admin/country_code_config/delete_country_code_config';
		  var tbl_id = val;
		  var data = { 
			  tbl_id: tbl_id,
			  csrf_token_name:csrf_token
			};

		  $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				window.location = base_url + 'admin/country_code_config';
			  } else {
				window.location = base_url + 'admin/country_code_config';
			  }
			}
		  });
		}
	  });
	}
    function change_Status_user2(id){
		var stat= $('#status_'+id).prop('checked');
		if(stat==true) {
			var status=1;
		}
		else {
			var status=0;
		}
		var url = base_url+ 'admin/categories/update_categories';
		var category_id = id;
		var is_featured = status;
		var data = { 
		  user_id: category_id,
		  is_featured: is_featured,
		  csrf_token_name:csrf_token
		}
		$.ajax({
		  url: url,
		  data: data,
		  type: "POST",
		  success: function (data) {
				if(data=="success"){
					 swal({
					 title: "Categories",
					 text: "Is Featured Change SuccessFully....!",
					 icon: "success",
					 button: "okay",
					 closeOnEsc: false,
					 closeOnClickOutside: false
				   });
				}
		  }
		});
    };
	function delete_categories(val) {
	  bootbox.confirm("Deleting category will also delete its sub-categories, sub sub-categories and Services!! ", function (result) {
		if (result == true) {
		  var url = base_url + 'admin/categories/delete_category';
		  var category_id = val;
		  var data = { 
			category_id: category_id,
			csrf_token_name:csrf_token
			};
		  $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				$("#flash_success_message").show();
				window.location = base_url + 'admin/categories';
			  } else {
				window.location = base_url + 'admin/categories';
			  }
			}
		  });
		}
	  });
	}
	function reply_contact(val,uname,umail) {
				 
	  bootbox.confirm("<h4>REPLY CONTACT</h4><br><textarea id='replycont' class='form-control' placeholder='REPLY...' rows='10'></textarea> ", function (result) {
		if (result == true) {
			var replycont=$("#replycont").val();
			var url = base_url + 'admin/contact/reply_contact';

			var contact_id = val;
			var name = uname;
			var email = umail;

			var data = { 
			contact_id: contact_id,
			umail:umail,
			uname:uname,
			replycont:replycont,
			csrf_token_name:csrf_token			
			};

		  $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				$("#flash_success_message").show();
				window.location = base_url + 'contact-details/'+contact_id;
			  } else {
				window.location = base_url + 'contact-details/'+contact_id;
			  }
			}
		  });
		}
	  });
    }

	function delete_sub_subcategories(val) {
		bootbox.confirm("Deleting Sub Sub-Category will also delete its Services!! ", function (result) {
			if (result == true) {
				var url = base_url + 'admin/categories/delete_sub_subcategory';
				var subcategory_id = val;
				var data = {subcategory_id: subcategory_id , csrf_token_name:csrf_token	};
				  $.ajax({
					url: url,
					data: data,
					type: "POST",
					success: function (res) {
					  if (res == 1) {
						$("#flash_success_message").show();
						window.location = base_url + 'sub_subcategories';
					  } else {
						window.location = base_url + 'sub_subcategories';
					  }
					}
				  });
			}
		});
	}
	 function delete_subcategories(val) {
	  bootbox.confirm("Deleting sub-category will also delete its Services!! ", function (result) {
		if (result == true) {
		  var url = base_url + 'admin/categories/delete_subcategory';
		  var category_id = val;
		  var data = { category_id: category_id , csrf_token_name:csrf_token};
		  $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				$("#flash_success_message").show();
				window.location = base_url + 'subcategories';
			  } else {
				window.location = base_url + 'subcategories';
			  }
			}
		  });
		}
	  });
	}


	var account_holder_name = '';
	var account_number = '';
	var account_iban = '';
	var bank_name = '';
	var bank_address = '';
	var sort_code = '';
	var routing_number = '';
	var account_ifsc = '';
	var transaction_id = '';
	var booking_id = '';


/*service list Active And De Active*/
	function change_Status_Service(service_id){
		var stat= $('#status_'+service_id).prop('checked');
		if(stat==true) {
			var status=1;
		}
		else {
			var status=2;
		}
		$.post(base_url+'admin/service/change_Status_service_list',{
			id:service_id,
			status:status,
			csrf_token_name:csrf_token
		},function(data){
			if(data==1){
				alert("Sorry That Service Was Booked Some One\n So You Can't Inactive The Service");
				$(".check_status").attr('checked', $(this).attr('checked'));
				$('#status_'+service_id).attr('data-on',"Active");
				$('.check_status').addClass('toggle-on');
			}
			console.log(data);
			if(data=="success"){
				swal({
		         title: "Service",
		         text: "Something went wrong, Try again....!",
		         icon: "failure",
		         button: "okay",
		         closeOnEsc: false,
		         closeOnClickOutside: false
		       });
			} else {
				
		          swal({
		         title: "Service",
		         text: "Service Status Change SuccessFully....!",
		         icon: "success",
		         button: "okay",
		         closeOnEsc: false,
		         closeOnClickOutside: false
		       });
			}
			
		});
	}	
	$('#admin_payment').bootstrapValidator({
		fields: {
			transaction_id: {
				validators: {
					
					notEmpty: {
						message: 'Please enter transaction ID'
					}
				}
			}            
		}
	}).on('success.form.bv', function(e) {
		e.preventDefault();
		var	account_holder_name = $("#account_holder_name").val();
		var	account_number = $("#account_number").val();
		var	account_iban = $("#account_iban").val();
		var	bank_name = $("#bank_name").val();
		var	bank_address = $("#bank_address").val();
		var	sort_code = $("#sort_code").val();
		var	routing_number = $("#routing_number").val();
		var	account_ifsc = $("#account_ifsc").val();
		var	transaction_id = $("#transaction_id").val();
		var	booking_id = $("#booking_id").val();
		$.ajax({
			url: base_url+'admin/payments/add_payment/',
			data: {csrf_token_name:csrf_token,account_holder_name:account_holder_name,account_number:account_number,account_iban:account_iban,bank_name:bank_name,bank_address:bank_address,sort_code:sort_code,routing_number:routing_number,account_ifsc:account_ifsc,transaction_id:transaction_id,booking_id:booking_id},
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				window.location.href = base_url+'payment_list';
			},
			error: function(error){
				console.log(error);
			}
		});
	});    
var timeout = 3000; // in miliseconds (3*1000)
$('#flash_succ_message').delay(timeout).fadeOut(500);
$('#flash_error_message').delay(timeout).fadeOut(500);



if($('#world-map-markers').length > 0 ){
	var map_list=[];
	$.ajax({
		url: base_url+'map-lists',
		data: {'tets':'test','csrf_token_name':csrf_token},
		type: 'POST',
		dataType: 'JSON',
		success: function(response){
			map_list=response;
			var center = new google.maps.LatLng(37.9643, -91.8318);

			var map = new google.maps.Map(document.getElementById('map'), {
			  zoom: 3,
			  center: center,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			
			var markers = [];
			if(map_list != null) {
				for (var i = 0; i < map_list.length; i++) {
					var lat_long_val = map_list[i].latLng;
					var lat_long_val_spilt = lat_long_val.toString().split(',');
				  var latLngval = new google.maps.LatLng(lat_long_val_spilt[0],lat_long_val_spilt[1]);
				  var marker = new google.maps.Marker({
					position: latLngval
				  });
				  markers.push(marker);
				}
			}
			var markerCluster = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
			
		}
	})
}


if(page == 'service-providers' ||page == 'stripe_payment_gateway' || page == 'service-list' ||page == 'users'||page == 'provider_list' ||page == 'provider-details'){ 
	$('#providers_table').DataTable();
	// var providers_table = $('#providers_table').on('init.dt', function () {
	// } ).DataTable({
	// 		"processing": true, //Feature control the processing indicator.
	// 		"serverSide": true, //Feature control DataTables' server-side processing mode.
	// 		"order": [], //Initial no order.
	// 		"ordering": false,
	// 		"ajax": {
	// 			"url":provider_list_url,
	// 			"type": "POST",
	// 			"data": {csrf_token_name:csrf_token},
	// 		},
	// 		"columnDefs": [
	// 		{
    //         "targets": [ 0 ], //first column / numbering column
    //         "orderable": false, //set not orderable
    //     },
    //     ]
	// });
	
	$('#providers_table').on('click','.delete_provider_data', function () {
		console.log("3");
	  var id = $(this).attr('data-id');
	  delete_provider_data(id);
	});	
	
	$('#providers_table').on('click','.change_Status_provider1', function () {
	var id = $(this).attr('data-id');
	change_Status_provider1(id);
	});
}

$('.service_table').on('click','.change_Status_provider', function () {
	var id = $(this).attr('data-id');
	change_Status_provider1(id);
	});

if(page == 'service-requests'){
	
		requests_table = $('#requests_table').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"ajax": {
				"url":requests_list_url,
				"type": "POST",
				"data": function ( data ) {}
			},
			"columnDefs": [
			{
			"targets": [ 7 ], //first column / numbering column
			"orderable": false, //set not orderable
		},
		],
	});

}

if(page == 'users'){
	
		var users_table = $('#users_table').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"ordering": false,
			"ajax": {
				"url":user_list_url,
				"type": "POST",

				"data":{csrf_token_name:csrf_token}
			},
			"columnDefs": [
			{
			"targets": [ 0 ], //first column / numbering column
			"orderable": false, //set not orderable
		},
		]
	});
	$('#users_table').on('click','.change_Status_user1', function () {
		console.log("3");
  var id = $(this).attr('data-id');
  change_Status_user1(id);
});

	$('#users_table').on('click','.delete_user_data', function () {
		console.log("3");
	  var id = $(this).attr('data-id');
	  delete_user_data(id);
	});	

	
}

if(page == 'staff-lists'){	
	$('.staff-lists table').on('click','.delete_staff_data', function () {		
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		delete_staff_data(id, pid);
	});	
	
	$('.staff-lists table').on('click','.change_Status_Staff', function () {
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		change_Status_Staff(id, pid);
	});	
}

if(page == 'shop-lists'){	
	$('.shop-lists table').on('click','.delete_shop_data', function () {		
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		delete_shop_data(id, pid);
	});	
	
	$('.shop-lists table').on('click','.change_Status_Shop', function () {
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		change_Status_Shop(id, pid);
	});	
}

if(page == 'branch-lists'){	
	$('.branch-lists table').on('click','.delete_branch_data', function () {		
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		delete_branch_data(id, pid);
	});	
	
	$('.branch-lists table').on('click','.change_Status_Branch', function () {
		var id  = $(this).attr('data-id'); 
		var pid = $(this).attr('data-provider_id'); 
		change_Status_Branch(id, pid);
	});	
}
if(page=='freelances-providers'){
	$('.freelancers-lists table').on('click','.delete_freelancer_data', function () {		
		var id  = $(this).attr('data-id'); 		
		delete_freelancer_data(id);
	});	
	
	$('.freelancers-lists table').on('click','.change_Status_freelancer', function () {
		var id  = $(this).attr('data-id');
		change_Status_freelancer(id);
	});	
}
if(page=='additional-services'){
	$('.additionalservices-lists table').on('click','.delete_additional_service', function () {		
		var id  = $(this).attr('data-id'); 		
		delete_additional_service(id);
	});	
	
	$('.additionalservices-lists table').on('click','.change_status_additionalservice', function () {
		var id  = $(this).attr('data-id');
		change_status_additionalservice(id);
	});	
}
function reject_payment_submit(){
	var type=true;
	var r = confirm("Are you Sure About This process");
	if (r == true) {
		type=true;
	} else {
		type=false;
	}
	return type;
}

var successClick = function(){
	$.notify({
		title: '<strong>Success</strong>',
		message: "<br>"+success_message,
		icon: 'glyphicon glyphicon-ok',
		target: '_blank'
	},{
		element: 'body',
		type: "success",
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated fadeInDown',
			exit: 'animated fadeOutRight'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var infoClick = function(){
	$.notify({
		title: '<strong>Info</strong>',
		message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
		icon: 'glyphicon glyphicon-info-sign',
	},{
		element: 'body',
		position: null,
		type: "info",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated bounceInDown',
			exit: 'animated bounceOutUp'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var warningClick = function(){
	$.notify({
		title: '<strong>Warning</strong>',
		message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
		icon: 'glyphicon glyphicon-warning-sign',
	},{
		element: 'body',
		position: null,
		type: "warning",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated bounceIn',
			exit: 'animated bounceOut'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var dangerClick = function(){
	$.notify({
		title: '<strong>Danger</strong>',
		message: "<br>"+error_message,
		icon: 'glyphicon glyphicon-remove-sign',
	},{
		element: 'body',
		position: null,
		type: "danger",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated flipInY',
			exit: 'animated flipOutX'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var primaryClick = function(){
	$.notify({
		title: '<strong>Primary</strong>',
		message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
		icon: 'glyphicon glyphicon-ruble',
	},{
		element: 'body',
		position: null,
		type: "success",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated lightSpeedIn',
			exit: 'animated lightSpeedOut'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var defaultClick = function(){
	$.notify({
		title: '<strong>Default</strong>',
		message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
		icon: 'glyphicon glyphicon-ok-circle',
	},{
		element: 'body',
		position: null,
		type: "warning",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated rollIn',
			exit: 'animated rollOut'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}

var linkClick = function(){
	$.notify({
		title: '<strong>Link</strong>',
		message: "<br>Lorem ipsum Reference site about Lorem Ipsum, giving information on its origins, as well as a random Lipsum.",
		icon: 'glyphicon glyphicon-search',
	},{
		element: 'body',
		position: null,
		type: "danger",
		allow_dismiss: true,
		newest_on_top: false,
		showProgressbar: false,
		placement: {
			from: "top",
			align: "right"
		},
		offset: 20,
		spacing: 10,
		z_index: 1031,
		delay: 3300,
		timer: 1000,
		mouse_over: null,
		animate: {
			enter: 'animated zoomInDown',
			exit: 'animated zoomOutUp'
		},
		onShow: null,
		onShown: null,
		onClose: null,
		onClosed: null,
		icon_type: 'class',
	});
}
function change_Status_rating(id) { 
	var stat= $('#status_'+id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=0;
	}
	$.post(base_url+'admin/dashboard/change_rating',{id:id,status:status,csrf_token_name:csrf_token},function(data){
		 swal({
         title: "Rating",
         text: "Rating Status Change SuccessFully....!",
         icon: "success",
         button: "okay",
         closeOnEsc: false,
         closeOnClickOutside: false
       });
	});
}
function change_Status_subcategory(id) {
	var stat= $('#status_'+id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
	$.post(base_url+'admin/dashboard/change_subcategory',{id:id,status:status},function(data){
		console.log(data);
	});
}

function change_Status_category(id) {
	var stat= $('#status_'+id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
	$.post(base_url+'admin/dashboard/change_category',{id:id,status:status},function(data){
		console.log(data);
	});
}

function change_Status_users(id) {
	var stat= $('#status_'+id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
	$.post(base_url+'admin/dashboard/change_Statuss',{id:id,status:status},function(data){
		console.log(data);
	});
}





function delete_review_comment(id) {
	if(confirm("Are you sure you want to delete this Comment...!?")){
		$.post(base_url+'admin/Ratingstype/delete_comment',{id:id,csrf_token_name:csrf_token},function(result){
			if(result) {
				window.location.reload();
			}
		});
	}   
}  




function delete_service_provider(id) {
	if(confirm("Are you sure you want to delete this provider?")){
		$.post(base_url+'admin/service/delete_provider',{id:id},function(result){
			if(result) {
				window.location.reload();
			}
		});
	}   
}  
function delete_service(id) {
	$('#delete_service').modal('show');
	$('#service_id').val(id);
}

function change_language_tag(lang_id) {
		var tag_val = $('#tag_'+lang_id).prop('checked');
		if(tag_val==true) {
			var tag = 'rtl';
		} else {
			var tag = 'ltr';
		}
	$.ajax({
        url: base_url+'admin/language/updateLangTag',
        data: {id: lang_id, tag: tag, csrf_token_name:csrf_token},
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response == 1) {
                window.location.reload();
            } else {
                window.location.reload();
            }
        }
    });
}

$(document).on("click",".default_lang",function() {
      var id = $(this).attr('data-id');
      var csrf_token=$('#admin_csrf').val();
                  $.ajax({
                url: base_url+ 'admin/language/update_language_default',
                type:'POST',
                data : {id:id, csrf_token_name:csrf_token},
                success:function(response)
                  {  
                     if(response==0)
                    {    
                      $('#default_language'+id).attr('checked',false);
                     window.location.href = base_url+'languages';
                    }
                    else
                    {
                      $('#default_language'+id).attr('checked',true);
                     window.location.href = base_url+'languages';
                    }
                  }                
            });
        
  });

function update_lang_status(lang_id) {
        var status = $('#status_'+lang_id).prop('checked');
        if(status==true) {
            var lang_status = 1;
        } else {
            var lang_status = 2;
        }
    $.ajax({
        url: base_url+'admin/language/updateLangStatus',
        data: {id: lang_id, status: lang_status, csrf_token_name:csrf_token},
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response == 1) {
                window.location.reload();
            } else {
                window.location.reload();
            }
        }
    });
}

function delete_user_data(val) {
   var r = confirm("Deleting user will also delete its related all datas!! ");
	if (r == true) {
	  var url = base_url + 'admin/dashboard/delete_user_data';
	  var user_id = val;
	  var data = { 
		user_id: user_id,
		csrf_token_name:csrf_token
		};
		 $.ajax({
			url: url,
			data: data,
			type: "POST",
			success: function (res) {
			  if (res == 1) {
				$("#flash_success_message").show();
				window.location = base_url + 'users';
			  } else {
				window.location = base_url + 'users';
			  }
			}
		});
	} else {
		return false;
	}
}
   function delete_provider_data(val) {
	   
	   var r = confirm("Deleting provider will also delete its related all datas!! ");
		if (r == true) {			
		  var url = base_url + 'admin/dashboard/delete_provider_data';
		  var user_id = val;
		  var ptype = 1;
		  var data = { 
			user_id: user_id,
			csrf_token_name:csrf_token,
			ptype:ptype
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				success: function (res) {
				  if (res == 1) {
					$("#flash_success_message").show();
					window.location = base_url + 'service-providers';
				  } else {
					window.location = base_url + 'service-providers';
				  }
				}
			});
		} else {
			return false;
		}
	}
function change_Status_user1(service_id){
	var stat= $('#status_'+service_id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
var url = base_url+ 'admin/dashboard/delete_users';
var category_id = service_id;
var data = { 
  user_id: category_id,
  status: status,
  csrf_token_name:csrf_token
};
$.ajax({
  url: url,
  data: data,
  type: "POST",
  success: function (data) {
	if(data==1){
			alert("Failed to change Status");
			$(".check_status").attr('checked', $(this).attr('checked'));
			$('#status_'+service_id).attr('data-on',"Active");
			$('.check_status').addClass('toggle-on');
		}
		console.log(data);
		if(data=="success"){
			 swal({
	 title: "User",
	 text: "User Status Change SuccessFully....!",
	 icon: "success",
	 button: "okay",
	 closeOnEsc: false,
	 closeOnClickOutside: false
   });
		}
  }
});
}

function change_Status_provider1(service_id){
	var stat= $('#status_'+service_id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
var url = base_url+ 'admin/dashboard/delete_provider';
var category_id = service_id;
var data = { 
  provider_id: category_id,
  status: status,
  csrf_token_name:csrf_token
};
$.ajax({
  url: url,
  data: data,
  type: "POST",
  success: function (data) {
	if(data==1){
			alert("Failed to change Status");
			$(".check_status").attr('checked', $(this).attr('checked'));
			$('#status_'+service_id).attr('data-on',"Active");
			$('.check_status').addClass('toggle-on');
		}
		console.log(data);
		if(data=="success"){
			 swal({
	 title: "Provider",
	 text: "Provider Status Change SuccessFully....!",
	 icon: "success",
	 button: "okay",
	 closeOnEsc: false,
	 closeOnClickOutside: false
   });
		}
  }
});
}


if(page == 'service-providers' || page== 'users' ||page == 'stripe_payment_gateway' || page == 'service-list' ||page == 'users'||page == 'provider_list' ||page == 'provider-details'){ 

	/*service list Active And De Active*/
	function change_Status_Service(service_id){
		var stat= $('#status_'+service_id).prop('checked');
		if(stat==true) {
			var status=1;
		}
		else {
			var status=0;
		}
		$.post(base_url+'admin/service/change_Status_service_list',{id:service_id,status:status,csrf_token_name:csrf_token},function(data){
			if(data==1){
				alert("Sorry That Service Was Booked Some One\n So You Can't Inactive The Service");
				$(".check_status").attr('checked', $(this).attr('checked'));
				$('#status_'+service_id).attr('data-on',"Active");
				$('.check_status').addClass('toggle-on');
			}
			console.log(data);
			if(data=="success"){
				 swal({
         title: "Service",
         text: "Service Status Change SuccessFully....!",
         icon: "success",
         button: "okay",
         closeOnEsc: false,
         closeOnClickOutside: false
       });
			}
			
		});
	}

	function change_Status(id) {
		var stat= $('#status_'+id).prop('checked');
		if(stat==true) {
			var status=1;
		}
		else {
			var status=0;
		}
		$.post(base_url+'admin/service/change_Status',{id:id,status:status},function(data){
		});
	}
}

function change_Status_Staff(staff_id, provider_id){ 
	var stat= $('#status_'+staff_id).prop('checked');
	if(stat==true) {
		var status_val=1;
	}
	else {
		var status_val=2;
	}
	var url = base_url+ 'admin/staffs/change_staff_status';
	var staff_id = staff_id;
	var data = { 
	  staff_id: staff_id,
	  status_val: status_val,
	  provider_id:provider_id,
	  csrf_token_name:csrf_token
	};
	$.ajax({
	  url: url,
	  data: data,
	  type: "POST",
	  dataType: 'JSON',
	  success: function (data) { 
		if(data.status=="error"){
			swal({
				title: "Error",
				text: data.msg,
				icon: "error",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false
			}).then(function(){ 
				location.reload();
			});
		}
		
		if(data.status=="success"){
			 swal({
			 title: "Staff",
			 text: "Staff Status Change SuccessFully....!",
			 icon: "success",
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

  function delete_staff_data(val,pid) {
	   
	   var r = confirm("Deleting Staff will also delete its related all datas!! ");
		if (r == true) {
		  var url = base_url + 'admin/staffs/delete_staff';
		  var staff_id = val;
		  var pid = pid;
		  var data = { 
			staff_id: staff_id,
			csrf_token_name:csrf_token,
			provider_id:pid
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				dataType: 'JSON',
				success: function (data) {
					console.log(data);
				    if(data.status=="success"){
						swal({
						 title: "Staff",
						 text: "Staff Details Deleted SuccessFully....!",
						 icon: "success",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						}).then(function(){ 
							location.reload();
					    });
					}
					if(data.status=="error"){
						swal({
							title: "Error",
							text: data.msg,
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
		} else {
			return false;
		}
	}

function change_Status_Shop(shop_id, provider_id){ 
	var stat= $('#status_'+shop_id).prop('checked');
	if(stat==true) {
		var status_val=1;
	}
	else {
		var status_val=2;
	}
	var url = base_url+ 'admin/shop/change_shop_status';
	var shop_id = shop_id;
	var data = { 
	  shop_id: shop_id,
	  status_val: status_val,
	  provider_id:provider_id,
	  csrf_token_name:csrf_token
	};
	$.ajax({
	  url: url,
	  data: data,
	  type: "POST",
	  dataType: 'JSON',
	  success: function (data) { 
		if(data.status=="error"){
			swal({
				title: "Error",
				text: data.msg,
				icon: "error",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false
			}).then(function(){ 
				location.reload();
			});
		}
		
		if(data.status=="success"){
			 swal({
			 title: "Shop",
			 text: "Shop Status Change SuccessFully....!",
			 icon: "success",
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

  function delete_shop_data(val,pid) {
	   
	   var r = confirm("Deleting Shop will also delete its related all datas!! ");
		if (r == true) {
			var stat= $('#status_'+val).prop('checked');
			if(stat==true) {
				var sval=1;
			}
			else {
				var sval=2;
			}
		
		  var url = base_url + 'admin/shop/delete_shop';
		  var shop_id = val;
		  var pid = pid;
		  var data = { 
			shop_id: shop_id,
			csrf_token_name:csrf_token,
			provider_id:pid,
			status_val:sval
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				dataType: 'JSON',
				success: function (data) {
				    if(data.status=="success"){
						swal({
						 title: "Shop",
						 text: "Shop Details Deleted SuccessFully....!",
						 icon: "success",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						}).then(function(){ 
							location.reload();
					    });
					}
					if(data.status=="error"){
						swal({
							title: "Error",
							text: data.msg,
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
		} else {
			return false;
		}
	}
	
	function change_Status_Branch(branch_id, provider_id){ 
	var stat= $('#status_'+branch_id).prop('checked');
	if(stat==true) {
		var status_val=1;
	}
	else {
		var status_val=2;
	}
	var url = base_url+ 'admin/branch/change_branch_status';
	var branch_id = branch_id;
	var data = { 
	  branch_id: branch_id,
	  status_val: status_val,
	  provider_id:provider_id,
	  csrf_token_name:csrf_token
	};
	$.ajax({
	  url: url,
	  data: data,
	  type: "POST",
	  dataType: 'JSON',
	  success: function (data) { 
		if(data.status=="error"){
			swal({
				title: "Error",
				text: data.msg,
				icon: "error",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false
			}).then(function(){ 
				location.reload();
			});
		}
		
		if(data.status=="success"){
			 swal({
			 title: "Branch",
			 text: "Branch Status Change SuccessFully....!",
			 icon: "success",
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

  function delete_branch_data(val,pid) {
	   
	   var r = confirm("Deleting Branch will also delete its related all datas!! ");
		if (r == true) {
			var stat= $('#status_'+val).prop('checked');
			if(stat==true) {
				var sval=1;
			}
			else {
				var sval=2;
			}
		  var url = base_url + 'admin/branch/delete_branch';
		  var branch_id = val;
		  var pid = pid;
		  var data = { 
			branch_id: branch_id,
			csrf_token_name:csrf_token,
			provider_id:pid,
			status_val:sval
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				dataType: 'JSON',
				success: function (data) {
				    if(data.status=="success"){
						swal({
						 title: "Branch",
						 text: "Branch Details Deleted SuccessFully....!",
						 icon: "success",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						}).then(function(){ 
							location.reload();
					    });
					}
					if(data.status=="error"){
						swal({
							title: "Error",
							text: data.msg,
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
		} else {
			return false;
		}
	}

function change_Status_freelancer(service_id){
	var stat= $('#status_'+service_id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
var url = base_url+ 'admin/dashboard/delete_provider';
var category_id = service_id;
var data = { 
  provider_id: category_id,
  status: status,
  csrf_token_name:csrf_token
};
$.ajax({
  url: url,
  data: data,
  type: "POST",
  success: function (data) {
		if($.trim(data)=="error"){
			alert("Failed to change Status");
			$(".check_status").attr('checked', $(this).attr('checked'));
			$('#status_'+service_id).attr('data-on',"Active");
			$('.check_status').addClass('toggle-on');
		}
		if($.trim(data)=="success"){
			swal({
				 title: "Freelances Provider",
				 text: "Provider Status Change SuccessFully....!",
				 icon: "success",
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

 function delete_freelancer_data(val) {
	   
	   var r = confirm("Deleting provider will also delete its related all datas!! ");
		if (r == true) {			
		  var url = base_url + 'admin/dashboard/delete_freelancer_data';
		  var user_id = val;
		  var data = { 
			user_id: user_id,
			csrf_token_name:csrf_token,			
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				success: function (res) {console.log(res);
					if($.trim(res)=="success"){
						swal({
						 title: "Freelances Provider",
						 text: "Provider Details Deleted SuccessFully....!",
						 icon: "success",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						}).then(function(){ 
							location.reload();
					    });
					}
					if($.trim(res)=="error"){
						swal({
							title: "Error",
							text: "Error in Deleting the Freelances Provider....!",
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
		} else {
			return false;
		}
	}
function change_status_additionalservice(service_id){
	var stat= $('#status_'+service_id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=2;
	}
var url = base_url+ 'admin/service/change_status_additionalservice';
var id = service_id;
var data = { 
  id: id,
  status: status,
  csrf_token_name:csrf_token
};
$.ajax({
  url: url,
  data: data,
  type: "POST",
  success: function (data) {
		if($.trim(data)=="error"){
			alert("Failed to change Status");
			$(".check_status").attr('checked', $(this).attr('checked'));
			$('#status_'+service_id).attr('data-on',"Active");
			$('.check_status').addClass('toggle-on');
		}
		if($.trim(data)=="success"){
			swal({
				 title: "Additional Service",
				 text: "Additional Service Status Change SuccessFully....!",
				 icon: "success",
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
 function delete_additional_service(val) {
	   
	   var r = confirm("Deleting additional-services will also delete its related all datas!! ");
		if (r == true) {			
		  var url = base_url + 'admin/service/delete_additional_service';
		  var id = val;
		  var data = { 
			id: id,
			csrf_token_name:csrf_token,			
			};
			 $.ajax({
				url: url,
				data: data,
				type: "POST",
				success: function (res) {console.log(res);
					if($.trim(res)=="success"){
						swal({
						 title: "Additional Service",
						 text: "Additional Service Details Deleted SuccessFully....!",
						 icon: "success",
						 button: "okay",
						 closeOnEsc: false,
						 closeOnClickOutside: false
						}).then(function(){ 
							location.reload();
					    });
					}
					if($.trim(res)=="error"){
						swal({
							title: "Error",
							text: "Error in Deleting the Additional Service....!",
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
		} else {
			return false;
		}
	}


 $( document ).ready(function() {
       $('.meta_google').hide();
       $('.meta_twitter').hide();

           
            $('input[type=radio][name=social_meta]').on('change',function() {
           var social_meta=$(this).val();
           if(social_meta=="meta_google"){
            $('.meta_google').show();
            $('.meta_fb').hide();
            $('.meta_twitter').hide();
           }else if(social_meta=='meta_twitter')
		   {
			    $('.meta_fb').hide();
                $('.meta_google').hide();
                $('.meta_twitter').show();
		   }
		   else{
                $('.meta_fb').show();
                $('.meta_google').hide();
                $('.meta_twitter').hide();
           }
        });
    });
	
	
	 $('input[type=radio][name=login_type]').on('change',function() {
           var login_type=$(this).val();
           if(login_type=="mobile"){
            $('#otpbydiv').show();
           }
		   else{
			$('#otpbydiv').hide();
			$('.otp_by').prop('checked', false);

           }
        });
		
	$( document ).ready(function() {
		$('.razorpay_stripe_payment').on('change',function(){
			var id=$(this).val();
		  razor_payment(id);
		});
	});
	function razor_payment(value) {
		if(value!=''){
			$.ajax({
				type: "post",
				url: base_url+"admin/settings/razor_payment_type",
				data:{type:value,'csrf_token_name':csrf_token}, 
				dataType:'json',
				success: function (data) {
					if(data!=''){
						$('#gateway_name').val(data.gateway_name);
						$('#api_key').val(data.api_key);
						$('#value').val(data.api_secret);
					}
				}
			});		
		}
	}

	$( document ).ready(function() {
		$('.moyaser_payment').on('change',function(){
			var id=$(this).val();
		  moyaser_payment(id);
		});
	});
	function moyaser_payment(value) {
		if(value!=''){
			$.ajax({
				type: "post",
				url: base_url+"admin/settings/moyaser_payment_type",
				data:{type:value,'csrf_token_name':csrf_token}, 
				dataType:'json',
				success: function (data) {
					if(data!=''){
						$('#gateway_name').val(data.gateway_name);
						$('#api_key').val(data.api_key);
						$('#value').val(data.api_secret);
					}
				}
			});		
		}
	}

$( document ).ready(function() {
	$('.paypal_payment').on('change',function(){
			var id=$(this).val();
		  paypal_payment(id);
		});
	});
	function paypal_payment(value) {
		if(value!=''){
			$.ajax({
				type: "post",
				url: base_url+"admin/settings/paypal_payment_type",
				data:{type:value,'csrf_token_name':csrf_token}, 
				dataType:'json',
				success: function (data) {
					if(data!=''){
						$('#braintree_key').val(data.braintree_key);
						$('#braintree_merchant').val(data.braintree_merchant);
						$('#braintree_publickey').val(data.braintree_publickey);
						$('#braintree_privatekey').val(data.braintree_privatekey);
						$('#paypal_appid').val(data.paypal_appid);
						$('#paypal_appkey').val(data.paypal_appkey);
					}
				}
			});		
		}
	}	
	
	if(page == 'adminusers'){
	
	var users_table = $('#adminusers_table').DataTable({
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"ordering": false,
			"ajax": {
				"url":adminuser_list_url,
				"type": "POST",

				"data":{csrf_token_name:csrf_token}
			},
			"columnDefs": [
			{
			"targets": [ 0 ], //first column / numbering column
			"orderable": false, //set not orderable
		},
		]
	});
	
}

//AdminUser form validation
$('#add_adminuser').bootstrapValidator({
	fields: {
		username:   {
			validators: {
				remote: {
					url: base_url + 'admin/dashboard/check_adminuser_name',
					data: function(validator) {
						return {
							name: validator.getFieldElements('username').val(),
							csrf_token_name:csrf_token,
							id:$('#user_id').val()
						};
					},
					message: 'User Name is already exist',
					type: 'POST'
				},
				notEmpty: {
					message: 'User Name is Required'

				}
			}
		},
		email:           {
			validators: {
				remote: {
					url: base_url + 'admin/dashboard/check_adminuser_email',
					data: function(validator) {
						return {
							name: validator.getFieldElements('email').val(),
							csrf_token_name:csrf_token,
							id:$('#user_id').val()
						};
					},
					message: 'Email is already exist',
					type: 'POST'
				},
				notEmpty: {
					message: 'Email is Required'

				}
			}
		},
		full_name:           {
			validators: {
				notEmpty: {
					message: 'Full Name is Required'

				}
			}
		},
		
		profile_img:           {
		   validators:           {
			file: {
			  extension: 'jpeg,png,jpg,gif',
			  type: 'image/jpeg,image/png,image/jpg,image/gif',
			  message: 'The selected file is not valid. Only allowed jpeg,jpg,png,gif files'
			}
		  }
		},
	}
}).on('success.form.bv', function(e) {
	var formData = new FormData(document.getElementById('add_adminuser'));
	$.ajax({
		type:'POST',
		url: base_url+'admin/dashboard/update_adminuser',
		enctype: 'multipart/form-data',
		data: formData,
		cache: false,						
		contentType: false,
		dataType: 'json',
		processData: false,
		success:function(response)
		{
			if(response.status)
			{
				swal({
					title: "Success",
					text: response.msg,
					icon: "success",
					button: "okay",
					closeOnEsc: false,
					closeOnClickOutside: false
				}).then(function(){ 
					window.location.href=base_url+'adminusers';
				});
			}
			else
			{
				swal({
					title: "Error",
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
	return false;
}); 

$('#chat').bootstrapValidator({
    fields: {
        chat_text:   {
            validators: {
                notEmpty: {
                    message: 'Please enter chat content message'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
  return true;
});

$('#socket').bootstrapValidator({
    fields: {
        server_ip:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Server IP'
                }
            }
        },
        server_port:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Port'
                }
            }
        },
    }
}).on('success.form.bv', function(e) {
  return true;
});

$('#service_category').on('change',function(){
	var category_id= $(this).val();
	get_category_data(category_id);		
});
$('#main_category').on('change',function(){
	var category_id= $(this).val();
	get_category_data(category_id);		
});

$('#service_subcategory').on('change',function(){
	var subcategory_id= $(this).val();
	var category_id= $('#service_category').val();
	get_subcategory_data(category_id, subcategory_id)
});

$('#product_category').on('change',function(){
	var category_id= $(this).val();
	get_product_subcategory(category_id);
});		

$('.delete_pcat').on('click',function(){
	$("#modal_dpcat").modal('show');
	var cat_id = $(this).attr('cat_id');
	$("#hcat_id").val(cat_id);
});
$('#confirm_dpcat').on('click',function(){
	var cat_id = $("#hcat_id").val();
	var table = $("#htable").val();
	$.ajax({
		type: "post",
		url: base_url+'admin/products/delete_category',
		data:{cat_id:cat_id, table:table, 'csrf_token_name':csrf_token}, 
		dataType:'json',
		success: function (data) {
			$("#modal_dpcat").modal('hide');
			$("#row_"+cat_id).fadeOut("normal", function() {
		        $(this).remove();
		    });
		}
	});	
});
    
function get_subcategory_data(category_id, subcategory_id)	{
	if(category_id !='' || subcategory_id != '') {
		$.ajax({
			type: "post",
			url: base_url+'admin/Categories/getServiceTitle',
			data:{category_id:category_id, subcategory_id:subcategory_id, 'csrf_token_name':csrf_token}, 
			dataType:'json',
			success: function (data) {
				if(data!=''){
					var fil_categories = data;
					
				$('#service_title').empty();
	            $('#service_title').append("<option value='' selected disabled='disabled'>Select Subcategory</option>");
	            for(var i=0; i<fil_categories.length; i++) {
	            	console.log(fil_categories[i].service_title);
	                $('#service_title').append("<option value="+fil_categories[i].id+">"+fil_categories[i].service_title+"</option>");                      
	            }
				}
			}
		});		
	} 
}
function get_product_subcategory(category_id)
{
	if(category_id !='') {
		$.ajax({
			type: "post",
			url: base_url+'admin/Products/get_subcategory',
			data:{category_id:category_id, 'csrf_token_name':csrf_token}, 
			dataType:'json',
			success: function (data) {
				if(data!=''){
					var fil_categories = data;
					$('#product_subcategory').empty();
	            	$('#product_subcategory').append("<option value='' selected disabled='disabled'>Select Subcategory</option>");
		            for(var i=0; i<fil_categories.length; i++) {
		                $('#product_subcategory').append("<option value="+fil_categories[i].id+">"+fil_categories[i].subcategory_name+"</option>");                      
		            }
				}
			}
		});		
	}
}
function get_category_data(category_id) 
{
	$.ajax({
		type: "post",
		url: base_url+'admin/Categories/get_subcategory',
		data:{category_id:category_id, 'csrf_token_name':csrf_token}, 
		dataType:'json',
		success: function (data) {
			if(data!=''){
				var fil_categories = data;
				$('#service_subcategory').empty();
            	$('#service_subcategory').append("<option value='' selected disabled='disabled'>Select Subcategory</option>");
	            for(var i=0; i<fil_categories.length; i++) {
	            	console.log(fil_categories[i].subcategory_name);
	                $('#service_subcategory').append("<option value="+fil_categories[i].id+">"+fil_categories[i].subcategory_name+"</option>");                      
	            }
			}
		}
	});
}		
function delete_pcat(id)
{
  $('#modal_dpcat').modal('show');
}		

//Order
var csrf_token=$('#admin_csrf').val();
var base_url=$('#base_url').val();

function filter_order(type)
{
	if (type == 'c') {
		$(".nv").val('');
	}
	orders_list(0);
	status_count();
}

$('.product_orders').on('click',function(){
    var id=$(this).attr('data-id');
    $(".ni").removeClass('active');
	$("#"+status+"_ni").addClass('active');
	$("#selected_status").val(id);
	orders_list(id);
});

function orders_list(page_num)
{
	page_num = page_num?page_num:0;
    var where = {};
	$(".nv").each(function() {
	    where[$(this).attr('id')] = $(this).val();
	});
    $.ajax({
        type: 'POST',
        url: base_url+'admin/products/ajaxorders/'+page_num,
        data: {page:page_num, csrf_token_name:csrf_token, status:$("#selected_status").val(), where:where},
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(html)
        {

            $('#mplist').html(html);
            $('.order_table').dataTable({
		        "paging": false,
				"order": [[ 2, 'ASC' ]]
		    });
        }
    });
}
function status_count()
{
	var where = {};
	$(".nv").each(function() {
	    where[$(this).attr('id')] = $(this).val();
	});
	$.ajax({
        type: 'POST',
        url: base_url+'admin/products/status_count',
        data: {csrf_token_name:csrf_token, where:where},
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(html)
        {
        	var data = $.parseJSON(html);
            $.each( data, function( key, value ) {
			  $("#"+key).text(value);
			});
        }
    });
}

$('#language_settings').bootstrapValidator({
    fields: {
        language:   {
            validators: {
                notEmpty: {
                    message: 'Please enter language'
                }
            }
        },
        language_value:   {
            validators: {
                notEmpty: {
                    message: 'Please enter language code'
                }
            }
        },
        status:   {
            validators: {
                notEmpty: {
                    message: 'Please select any one option'
                }
            }
        }
    }
}).on('success.form.bv', function(e) {
  return true;
});



$('.delete_language').on('click',function(){
    var id=$(this).attr('data-id');
    lang_delete(id);
}); 

//Delete Language
function lang_delete(id) {
    var r = confirm("Deleting language will also delete its related all datas!! ");
    if (r == true) {
        $.ajax({
            url: base_url+'admin/language/delete_language',
            data: {id: id, csrf_token_name: csrf_token},
            type: 'POST',
            success: function (response) {
                if (response == 'success') {
                    window.location.reload();
                } else{ 
                    $('#flash_lang_message').html(" <div class='alert alert-danger fade in' id=''>Something went wrong. <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
                }
            }
        });

    } else {
        return false;
    }
}
// Range slider
if(document.getElementById("myRange")!=null){
	var slider = document.getElementById("myRange");
	var output = document.getElementById("currencys");
	output.innerHTML = slider.value;
	var val = output.innerHTML;
	var value = (val-slider.min)/(slider.max-slider.min)*100
	$('#radius').val(val);
	slider.style.background = 'linear-gradient(to right, #393CC6 0%, #393CC6 ' + value + '%, #c4c4c4 ' + value + '%, #c4c4c4 100%)'
	$('#currencys').text(val);
	slider.oninput = function() {
	  output.innerHTML = this.value;
	}
}
if(document.getElementById("myRange")!=null){
	document.getElementById("myRange").oninput = function() {
		$('#radius').val(this.value);
		var value = (this.value-this.min)/(this.max-this.min)*100
		this.style.background = 'linear-gradient(to right, #393CC6 0%, #393CC6 ' + value + '%, #c4c4c4 ' + value + '%, #c4c4c4 100%)'
		$('#currencys').text(this.value);
	};
}

function getcurrencysymbol(currencies) { 
     var csrf_toiken=$('#admin_csrf').val();
    $.ajax({
        type: "POST",
        url:  base_url+"admin/settings/get_currnecy_symbol",
        data:{
          id:currencies,
         'csrf_token_name': csrf_token,
        }, 
                     
        success: function (data) {
            $('#currency_symbol').val(data); 
          
        }
    });
}
$(document).ready(function() {
	$(document).on("change",".currency_code",function() {
		var currencies = $('#currency_option option:selected').text();
		getcurrencysymbol(currencies);
	});

	//Get country code key value
	$(document).on("change",".countryCode",function() {
        var countryKey = $(this).find(':selected').attr('data-countrycode');
        $('#country_code_key').val(countryKey); 
    });
});


$(document).on("click",".addlinks",function () {
    var len = $('.links-cont').length + 1;
    if(len <= 5) {
        var navmenus = '<div class="form-group links-cont"><div class="row align-items-center"><div class="col-lg-3 col-12"><input type="text" class="form-control" name="menu_title[]" id="menu_title" placeholder="Title"></div><div class="col-lg-8 col-12"><input type="text" class="form-control" name="menu_links[]" id="menu_links" placeholder="Links" value="'+base_url+'"></div><div class="col-lg-1 col-12"><a href="#" class="btn btn-sm bg-danger-light delete_menu"><i class="far fa-trash-alt "></i> </a></div></div></div> ';
      $(".settings-form").append(navmenus);
      return false;
    } else {
        $('.addlinks').hide();
        alert('Allow 5 menus only');
    }
});

//Remove updated header menus
$(document).on("click",".delete_menus",function () {
    var id = $(this).attr('data-id');
    $('#menu_'+id).remove();
    return false;
});

//Remove new header menus
$(document).on("click",".delete_menu",function () {
    $(this).closest('.links-cont').remove();
    return false;
});
	
$(document).on("click","#reset_menu",function(e){
	
	$.ajax({
			url: base_url+'admin/footer_menu/resetMenu',
			data: {csrf_token_name:csrf_token},
			type: 'POST',
			dataType: 'JSON',
			success: function(response){
				window.location.href = base_url+'admin/frontend-settings';
			},
			error: function(error){
				console.log(error);
			}
		});
});

$('#pages_status').on('click','.pages_status', function () {
  			var id = $(this).attr('data-id');
  			pages_status(id);
		});
	function pages_status(id){
	var stat= $('#pages_status'+id).prop('checked');
	if(stat==true) {
		var status=1;
	}
	else {
		var status=0;
	}
	var url = base_url+ 'admin/settings/page_status';
	var status_id = id;
	var status = status;
	var data = { 
	  status_id: status_id,
	  status: status,
	  csrf_token_name:csrf_token
	};
	$.ajax({
	  url: url,
	  data: data,
	  type: "POST",
	  success: function (data) {
			console.log(data);
			if(data=="success"){
				 swal({
		 title: "Pages",
		 text: "Status Change SuccessFully....!",
		 icon: "success",
		 button: "okay",
		 closeOnEsc: false,
		 closeOnClickOutside: false
	   });
			}
	  }
	});
	}

//Update language keywords
$(document).on("change",".langKeyName",function(e) {
	var id=$(this).attr('data-id');
	var lang_value=$(this).val();
    var lang_key=$(this).attr('data-key');
	$.ajax({
        url: base_url+'admin/language/update_language_keyword',
        data: {id: id, lang_value: lang_value, lang_key: lang_key, csrf_token_name:csrf_token},
        type: "POST",
        dataType: 'json',
        success: function (response) {
            if (response == 1) {
                window.location.reload();
            } else {
                window.location.reload();
            }
        }
    });
});

$('#lang_keywords_settings').bootstrapValidator({
    fields: {
        filed_name:   {
            validators: {
                notEmpty: {
                    message: 'Please enter field name'
                }
            }
        },
        key_name:   {
            validators: {
                notEmpty: {
                    message: 'Please enter key name'
                }
            }
        },
    }
}).on('success.form.bv', function(e) {
  return true;
});
	
	$('#add_pages').bootstrapValidator({
    fields: {
      title:   {
        validators: {
          notEmpty: {
            message: 'Please enter Title name'

          }
        }
      },
      pages_desc:   {
        validators: {
          notEmpty: {
            message: 'Please enter Description'

          }
        }
      },
      pages_key:   {
        validators: {
          notEmpty: {
            message: 'Please enter Key'

          }
        }
      },
      pages_lang: {
        validators: {
          notEmpty:  {
            message: 'Please select Language'
          }
        }
      }, 
      pages_loc:   {
        validators: {
          notEmpty: {
            message: 'Location is required'

          }
        }
      },
      pages_visibility:   {
        validators: {
          notEmpty: {
            message: 'Visibility is required'

          }
        }
      },
      content:   {
        validators: {
          notEmpty: {
            message: 'Please enter content'

          }
        }
      },                  
  }
  }).on('success.form.bv', function(e) {
    return true;
  });

  $('#rebuild_sitemap').on('click',function(){
    	$.ajax({
			url: base_url+'admin/sitemap/view_map',
			data: {csrf_token_name:csrf_token},
			type: 'GET',
			success: function(response){
				window.location.reload();
			}
		});
    });  

})(jQuery);