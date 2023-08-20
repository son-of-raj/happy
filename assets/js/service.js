(function($) {
  "use strict";

  var base_url=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();
  var placeSearch, autocomplete;

  $( document ).ready(function() {
   
    $("#coupon_amt").hide();

     //location

   

     $('#category').on('change',function(){

      $("#subcategory").val('default');
      $("#subcategory").selectpicker("refresh");


      $.ajax({
        type: "POST",
        url: base_url+"user/service/get_subcategory",
        data:{id:$(this).val(),csrf_token_name:csrf_token}, 
        beforeSend :function(){
          $("#subcategory option:gt(0)").remove(); 
          $('#subcategory').selectpicker('refresh');
          $("#subcategory").selectpicker();
          $('#subcategory').find("option:eq(0)").html("Please wait..");
          $('#subcategory').selectpicker('refresh');
          $("#subcategory").selectpicker();
        },                         
        success: function (data) {   
          $('#subcategory').selectpicker('refresh'); 
          $("#subcategory").selectpicker();      
          $('#subcategory').find("option:eq(0)").html("Select SubCategory");
          $('#subcategory').selectpicker('refresh');
          var obj=jQuery.parseJSON(data);       
          $('#subcategory').selectpicker('refresh');
          $("#subcategory").selectpicker();
          $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#subcategory').append(option);
          });       
          $('#subcategory').selectpicker('refresh');
          $("#subcategory").selectpicker();
        }
      });

    }); 
	
		$('#subcategory').on('change',function(){

			$("#sub_subcategory").val('default');
			$("#sub_subcategory").selectpicker("refresh");
			
			var category = $("#category").val();


			$.ajax({
				type: "POST",
				url: base_url+"user/service/get_sub_subcategory",
				data:{id:$(this).val(),csrf_token_name:csrf_token,cid:category}, 
				beforeSend :function(){
				  $("#sub_subcategory option:gt(0)").remove(); 
				  $('#sub_subcategory').selectpicker('refresh');
				  $("#sub_subcategory").selectpicker();
				  $('#sub_subcategory').find("option:eq(0)").html("Please wait..");
				  $('#sub_subcategory').selectpicker('refresh');
				  $("#sub_subcategory").selectpicker();
				},                         
				success: function (data) {   
				  $('#sub_subcategory').selectpicker('refresh'); 
				  $("#sub_subcategory").selectpicker();      
				  $('#sub_subcategory').find("option:eq(0)").html("Select Sub Sub Category");
				  $('#sub_subcategory').selectpicker('refresh');
				  var obj=jQuery.parseJSON(data);       
				  $('#sub_subcategory').selectpicker('refresh');
				  $("#sub_subcategory").selectpicker();
				  $(obj).each(function(){
					var option = $('<option />');
					option.attr('value', this.value).text(this.label);           
					$('#sub_subcategory').append(option);
				  });       
				  $('#sub_subcategory').selectpicker('refresh');
				  $("#sub_subcategory").selectpicker();
				}
			});

		}); 
		
		$('#sub_subcategory').on('change',function(){
			if($('#shop_id').val() != '') {
				$('#shop_id').trigger("change");
			}
		}); 
		
		$('#shop_id').on('change',function(){
			$("#staff_id").val('default');
			
			var category = $("#service_category").val();
			var subcategory = $("#service_subcategory").val();
			var sub_subcategory = $("#service_sub_subcategory").val();
			if($(this).val() != '') {

				$.ajax({
					
					type: "POST",
					url: base_url+"user/service/staff_content",
					data:{id:$(this).val(),csrf_token_name:csrf_token,cid:category,sid:subcategory,ssid:sub_subcategory}, 
					beforeSend :function(){
					  $("#staff_id option:gt(0)").remove(); 
					  $('#staff_id').find("option:eq(0)").html("Please wait..").attr("data-subtext", '').attr('value', "")
					},  
					                     
					success: function (data) {   	
						$('#staff_id').empty(); 
					  var obj=jQuery.parseJSON(data);       
					  $(obj).each(function(){
						var option = $('<option />');
						option.attr('value', this.value).text(this.label);   
						option.attr("data-subtext", this.sublabel); 	
						$('#staff_id').append(option);
					  });       
					}
				});
			} else { 				
				}

		}); 

    $(".sids").on('change',function(){
        var sids_length = $('.sids').filter(':checked').length;
        if (sids_length > 0) {
          $("#muliple_offer_apply").css('display', 'block');
        }
        else {
          $("#muliple_offer_apply").css('display', 'none');
        }
    });
    $('#muliple_offer_apply').on("click", function(){	
      var sids = $('.sids').filter(':checked');
      var sid_arr = [];
      $('.sids:checked').each(function () {
           sid_arr.push($(this).val());
      });
      $("#hsids").val(sid_arr.toString());
    });

    $('#reward_type').on('change', function(){
    
    		var reward_type = $(this).val();
    		if (reward_type == 1) {
				$("#reward_discount").val('');
    			$("#reward_discount").prop('readonly', false);
    		}
    		else
    		{
    			$("#reward_discount").val('0');
    			$("#reward_discount").prop('readonly', true);
    		}
    });

    $('#submit_reward').on("click", function(){	
		var r = 0;
		$('.rewardcls').each(function(){	
			if($.trim($(this).val())=='') {
				r++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(r==0){
			$.ajax({
			  type: "POST",
			  url: base_url+"user/myservice/applyservicereward",
			  data:{reward_id:$("#h_reward_id").val(), user_id:$("#h_user_id").val(),service_id:$("#r_service_id").val(), reward_type:$("#reward_type").val(), reward_discount:$("#reward_discount").val(), total_visit_count:$("#h_total_visit_count").val(), description:$("#rdescription").val(), csrf_token_name:csrf_token},                         
			  success: function (data) {   
				window.location.reload();
			  }
			});
		}
    });

    $('#submit_offer').on("click", function(){	
		var so = 0;
		$('.offercls').each(function(){	
			if($.trim($(this).val())=='') {
				so++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(so==0){
			$.ajax({
			  type: "POST",
			  url: base_url+"user/myservice/applyserviceoffer",
			  data:{service_id:$("#hservice_id").val(),offer_percentage:$("#offer_percentage").val(), start_date:$("#start_date").val(), end_date:$("#end_date").val(), start_time:$("#start_time").val(), end_time:$("#end_time").val(), csrf_token_name:csrf_token},                         
			  success: function (data) {   
				var result = JSON.parse(data);
				if (result['error'] == true) {
				  $("#error_service").text(result['msg']);
				}
				else {
				  //reload the page
					$("#error_service").text('');
					$("#offer-modal").modal('hide');
					swal({
						title: "Success..!",
						text: "Offer Created Successfully....!",
						icon: "success",
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false
					}).then(function(){
			        	window.location.reload();
			       	});
				}
			  }
			});
		}
    });

    $('#start_time').on("change", function(){
    	$('#end_time').val('');	
    });


    $('#m_submit_offer').on("click", function(){	
		var mo = 0;
		$('.moffercls').each(function(){	
			if($.trim($(this).val())=='') {
				mo++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(mo==0){
			$.ajax({
			  type: "POST",
			  url: base_url+"user/myservice/applyserviceoffermultiple",
			  data:{sids:$("#hsids").val(),offer_percentage:$("#m_offer_percentage").val(), start_date:$("#m_start_date").val(), end_date:$("#m_end_date").val(), start_time:$("#m_start_time").val(), end_time:$("#m_end_time").val(), csrf_token_name:csrf_token},                         
			  success: function (data) {   
				window.location.reload();
			  }
			});
		}
    });
	$('.editServiceOffers').on('click',function(){
		var id = $(this).attr("data-id"); 
		var sid = $(this).attr("data-service_id"); 
		var oper = $(this).attr("data-offers_per"); 
		var sdate = $(this).attr("data-start_date"); 
		var edate = $(this).attr("data-end_date");
		var stime = $(this).attr("data-start-time"); 
		var etime = $(this).attr("data-end-time"); 
		$("#start_date").val(sdate);
		$("#end_date").val(edate);
		$("#start_time").val(stime);
		$("#end_time").val(etime);
		$("#offer_percentage").val(oper);
		$("#offerid").val(id);
		$("#hservice_id").val(sid);
		$("#edit_offer_modal").modal('show');  
	}); 
		$(".edit_reward").on("click", function(){			
			var reward_id = $(this).attr("reward_id");
			$.ajax({
			  type: "POST",
			  url: base_url+"user/myservice/edit_reward",
			  data:{reward_id:reward_id, csrf_token_name:csrf_token,id:$("#offerid").val()},                         
			  success: function (data) {   
				var result = JSON.parse(data);
				//open modal
				$("#edit-rewards-modal").modal('show');
				$("#r_service_id").val(result['service_id']);
				$("#h_reward_id").val(reward_id);
				$("#h_user_id").val(result['user_id']);
				$("#h_total_visit_count").val(result['total_visit_count']);
				$("#reward_type").val(result['reward_type']);
				$("#reward_discount").val(result['reward_discount']);
				$("#rdescription").val(result['description']);
				if(result['reward_type'] == 1){
					$("#reward_discount").prop('readonly', false);
				} else {
					$("#reward_discount").prop('readonly', true);
				}
			  }
			});			
		});
		$('#edit_submit_offer').on("click", function(){	
			var eo = 0;
		$('.eoffercls').each(function(){	
			if($.trim($(this).val())=='') {
				eo++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(eo==0){
			$.ajax({
			  type: "POST",
			  url: base_url+"user/myservice/edit_applyserviceoffer",
			  data:{service_id:$("#hservice_id").val(),offer_percentage:$("#offer_percentage").val(), start_date:$("#start_date").val(), end_date:$("#end_date").val(), start_time:$("#start_time").val(), end_time:$("#end_time").val(), csrf_token_name:csrf_token,id:$("#offerid").val()},                         
			  success: function (data) {   
				var result = JSON.parse(data);
				if (result['error'] == true) {
				  $("#error_service").text(result['msg']);
				}
				else {
				  //reload the page
				  $("#error_service").text('');
				  window.location.reload();
				}
			  }
			});
		}
    });
    $('#delete_reward').on("click", function(){
    
    		var reward_id = $("#d_reward_id").val();
    		$.ajax({
          type: "POST",
          url: base_url+"user/myservice/delete_reward",
          data:{reward_id:reward_id, csrf_token_name:csrf_token},                         
          success: function (data) {   
            window.location.reload();
          }
        });
    });
	$(document).on('click','.offers-delete',function() {	
		var id = $(this).attr("data-id");					
		$(document).on('click','.si_accept_confirm',function(){	
			$.ajax({
				url: base_url+"user/myservice/delete_serviceoffer",
				data:{id:id,csrf_token_name:csrf_token}, 
				type:"POST",
				beforeSend:function(){
					$('#offersdeleteConfirmModal').modal('toggle');
				},
				success: function(res){ 
					window.location.reload();
				}
			});
		});		
	});
	
	//Coupons
	$(".couponschkbox").on('change',function(){
        var chkcount = $('.couponschkbox').filter(':checked').length;
        if (chkcount > 0) {
			$(".addnewcoupon").removeAttr('disabled');
        } else {
			$(".addnewcoupon").attr('disabled','');
        }		
    });
	$(".addnewcoupon").on("click", function(){	
		var sid_arr = [];
		$('.couponschkbox:checked').each(function () {
		   sid_arr.push($(this).val());
		});
		$("#service_id").val(sid_arr.toString());
		$("#coupons-modal").modal('show');
	});
	$(".cpntype").on('change',function(){
        var ctype = $(this).val();
        if (ctype == 2) {
			$("#coupon_amt").show(); $("#coupon_price").addClass("inputcls");
			$("#coupon_per").hide(); $("#coupon_percent").removeClass("inputcls");
        } else {
			$("#coupon_per").show(); $("#coupon_percent").addClass("inputcls");
			$("#coupon_amt").hide(); $("#coupon_price").removeClass("inputcls");
        }
    });
	$('.number').keyup(function(e) {
		if (/\D/g.test(this.value)) {
			this.value = this.value.replace(/\D/g, '');
		}
	});
	$(document).on('click','.coupon_cancel, .close',function(){
		$(".couponschkbox").prop('checked', false);
		$(".addnewcoupon").attr('disabled','');
		$("#service_id").val('');
	});
	$('#submit_coupon').on("click", function(){	
		var n = 0;
		$('.inputcls').each(function(){	
			if($.trim($(this).val())=='') {
				n++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(n==0){
			var coupon_type = $('input[name="coupontype"]:checked').val();
			if(coupon_type == 1){
				var percentage = $("#coupon_percent").val();
				var price = 0;
			} else {
				var percentage = 0;
				var price = $("#coupon_price").val();
			}
			var desc = $("#cdescription").val(); 
			
			$.ajax({
				type: "POST",
				url: base_url+"user/myservice/add_servicecoupon",
				data:{service_id:$("#service_id").val(), coupon_name:$("#coupon_name").val(), coupon_type:coupon_type, percentage:percentage, price:price, start_date:$("#start_date").val(), valid_days:$("#valid_days").val(), user_limit:$("#user_limit").val(), description:$.trim(desc), csrf_token_name:csrf_token},                         
				success: function (data) { 
					var result = JSON.parse(data);
					if (result['error'] == true) {
					  $("#error_service").text(result['msg']);
					} else { 
						$("#error_service").text('');
						window.location.reload();
					}					
				}
			});
		}
    });
	$(".editServiceCoupon").on('click',function(){		
		var id = $(this).attr("data-id"); 
		var title = $(this).attr("data-service_title"); 
		var amt = $(this).attr("data-service_amount"); 
		$.ajax({
			type: "POST",
			url: base_url+"user/myservice/get_servicecoupon",
			data:{id:id, csrf_token_name:csrf_token},                         
			success: function (data) {  
				var obj = JSON.parse(data);
				$("#coupon_name").val(obj.name);
				$("#user_limit").val(obj.user_limit);
				$(".couponschkbox").prop('checked', false);
				if(obj.coupon_type == 1){
					$("#coupon_per").show(); $("#coupon_percent").addClass("inputcls");
					$("#coupon_amt").hide(); $("#coupon_price").removeClass("inputcls");	
					$("#percentage").prop("checked", true);	
				} else {
					$("#coupon_per").hide(); $("#coupon_percent").removeClass("inputcls");
					$("#coupon_amt").show(); $("#coupon_price").addClass("inputcls");
					$("#amount").prop("checked", true);
				}
				
				$("#coupon_percent").val(obj.percentage);
				$("#coupon_price").val(obj.price);
				$("#start_date").val(obj.start_date);
				$("#valid_days").val(obj.valid_days);
				$("#cdescription").val(obj.description);
				$("#service_id").val(obj.service_id);
				$("#coupon_id").val(id);
				
				$("#sname").text(title);
				$("#samount").text(amt);
				
				$("#edit_coupon_modal").modal('show'); 
			}
		});		
	}); 
	
	$('#edit_submit_coupon').on("click", function(){	
		var n = 0;
		$('.inputcls').each(function(){	
			if($.trim($(this).val())=='') {
				n++;
				$(this).css('border-color','red');
			} else {
				$(this).css('border-color','');					
			}			
		});
		if(n==0){
			var coupon_type = $('input[name="coupontype"]:checked').val();
			if(coupon_type == 1){
				var percentage = $("#coupon_percent").val();
				var price = 0;
			} else {
				var percentage = 0;
				var price = $("#coupon_price").val();
			}
			var desc = $("#cdescription").val();
			
			$.ajax({
				type: "POST",
				url: base_url+"user/myservice/update_servicecoupon",
				data:{id:$("#coupon_id").val(),service_id:$("#service_id").val(), coupon_name:$("#coupon_name").val(), coupon_type:coupon_type, percentage:percentage, price:price, start_date:$("#start_date").val(), valid_days:$("#valid_days").val(), user_limit:$("#user_limit").val(), description:$.trim(desc), csrf_token_name:csrf_token},                         
				success: function (data) {  
					var result = JSON.parse(data);
					if (result['error'] == true) {
					  $("#error_service").text(result['msg']);
					} else {
					  $("#error_service").text('');
					  window.location.reload();
					}
				}
			});
		}
    });
	$(document).on('click','.coupons-delete',function() {	
		var id = $(this).attr("data-id");					
		$(document).on('click','.si_coupon_confirm',function(){	
			$.ajax({
				url: base_url+"user/myservice/update_servicecoupon_status",
				data:{id:id,csrf_token_name:csrf_token,action:'delete'}, 
				type:"POST",
				beforeSend:function(){
					$('#coupondeleteConfirmModal').modal('toggle');
				},
				success: function(res){ 
					window.location.reload();
				}
			});
		});		
	});
	$(document).on('click','.coupon-service',function() {
	
		var id = $(this).attr("data-id"); 
		var action = $(this).attr("data-text");
		
		var title = action+" Coupon";
		var msg = "Are you sure want to "+action+" this coupon?";
		var ttext = "Coupon has been "+action;
		
		$('#statusConfirmModal').modal('toggle');
		$('#status_acc_title').html('<i>'+title+'</i>');
		$('#status_acc_msg').html(msg);
		
		$(document).on('click','.si_status_confirm',function(){	
			$.ajax({
				url: base_url+"user/myservice/update_servicecoupon_status",
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
	});
	
	$(".viewServiceCoupon").on('click',function(){		
		var id = $(this).attr("data-id"); 
		var title = $(this).attr("data-service_title"); 
		var amt = $(this).attr("data-service_amount"); 
		$.ajax({
			type: "POST",
			url: base_url+"user/myservice/get_servicecoupon",
			data:{id:id, csrf_token_name:csrf_token},                         
			success: function (data) {  
				var obj = JSON.parse(data);
							
				if(obj.coupon_type == 1){
					$("#coupon_vper").show(); 
					$("#coupon_vamt").hide(); 
					var vtype = 'Percentage';
				} else {
					$("#coupon_vper").hide(); 
					$("#coupon_vamt").show(); 
					var vtype ='Fixed Amount';
				}
				var atxt = '';
				if(obj.user_limit == 0) atxt = ' (Unlimited)';
				
				$("#view_name").text("PRO"+obj.name); $("#view_type").text(vtype);
				$("#view_per").text(obj.percentage); $("#view_amt").text(obj.price);
				$("#view_from").text(obj.start_date); $("#view_end").text(obj.end_date);
				$("#view_desc").text(obj.description); $("#view_until").text(obj.valid_days+ " days");
				$("#view_user").text(obj.user_limit+atxt); $("#view_num").text(obj.user_count);
				$("#view_statu").text(obj.user_status); $("#view_sname").text(title);
				$("#view_samount").text(amt);				
				$("#couponViewModal").modal('show'); 
			}
		});		
	}); 
	
	//Service
     $('#add_service').bootstrapValidator({
      fields: {
        service_title: {
          validators: {
           notEmpty: {
            message: 'Please Enter your service title'
          }
        }
      },
      category: {
        validators: {
          notEmpty: {
            message: 'Please select category...'
          }
        }
      },
      service_amount: {
        validators: {
          digits: {
            message: 'Please Enter valid service amount and not used in special characters...'
          },
          notEmpty: {
            message: 'Please Enter service amount...'
          }
        }
      },
      about: {
        validators: {
          notEmpty: {
            message: 'Please Enter About Informations...'
          }
        }
      },
	  duration: {
        validators: {
		  digits: {
            message: 'Please Enter valid service duration and use only numbers...'
          },	
          notEmpty: {
            message: 'Please Enter Service Duration...'
          }
        }
      },
	   shop_id: {
        validators: {		  
          notEmpty: {
            message: 'Please Select Shop...'
          }
        }
      },
      'staff_id[]': {
        validators: {		  
          notEmpty: {
            message: 'Please Select Staff...'
          }
        }
      },
      'images[]': {
        validators: {
          file: {
            extension: 'jpeg,png,jpg',
            type: 'image/jpeg,image/png,image/jpg',
            message: 'The selected file is not valid. Only allowed jpeg,png files'
          },
          notEmpty:               {
            message: 'Please upload service image...'
          }
        }
      }                     
    }
  }).on('success.form.bv', function(e) {
	  
	  var divCount = $('.additional-cont').length; 	
		if(divCount > 0){
			var n = 0;
			$('.addicls').each(function(){	
				if($.trim($(this).val())=='') {
					n++;
					$(this).css('border-color','red');
				} else {
					$(this).css('border-color','');					
				}			
			});
			
			if(n == 0){
				return true;
			} else{				
				$('html, body').animate({scrollTop: jQuery("#addiservice_div").offset().top}, 0);
				return false;
			}
		} else {
			return true;
		}
 });    
 
 $('#update_service').bootstrapValidator({
      fields: {
        service_title: {
          validators: {
           notEmpty: {
            message: 'Please Enter your service title'
          }
        }
      },
      service_sub_title: {
        validators: {
          notEmpty: {
            message: 'Please Enter service sub title'
          }
        }
      },
      category: {
        validators: {
          notEmpty: {
            message: 'Please select category...'
          }
        }
      },
      /*subcategory: {
        validators: {
          notEmpty: {
            message: 'Please select subcategory...'
          }
        }
      },
      service_location: {
        validators: {
          notEmpty: {
            message: 'Please Enter service location...'
          }
        }
      },*/
      service_amount: {
        validators: {
          digits: {
            message: 'Please Enter valid service amount and not used in special characters...'
          },
          notEmpty: {
            message: 'Please Enter service amount...'
          }
        }
      },
      'service_offered[]': {
        validators: {
          notEmpty: {
            message: 'Please Enter service offered'
          }
        }
      }, 
      about: {
        validators: {
          notEmpty: {
            message: 'Please Enter About Informations...'
          }
        }
      },
	  duration: {
        validators: {
		  digits: {
            message: 'Please Enter valid service duration and use only numbers...'
          },	
          notEmpty: {
            message: 'Please Enter Service Duration...'
          }
        }
      },
	   'shop_id[]': {
        validators: {		  
          notEmpty: {
            message: 'Please Select Shop...'
          }
        }
      }                    
    }
  }).on('success.form.bv', function(e) {

		var divCount = $('.additional-cont').length; 	
		if(divCount > 0){
			var n = 0;
			$('.addicls').each(function(){	
				if($.trim($(this).val())=='') {
					n++;
					$(this).css('border-color','red');
				} else {
					$(this).css('border-color','');					
				}			
			});
			if(n == 0){
				return true;
			} else{
				$('html, body').animate({scrollTop: jQuery("#addiservice_div").offset().top}, 0);
				return false;
			}
		} else {
			return true;
		}
 });    
	var curpage=$('#current_page').val();
	 var modules=$('#modules_page').val();
	if(modules == 'service' && curpage == "user"){
		if($("#service_location").val() == '') {
			$(".services_shop_id").trigger('change');
		}
	}

 });
//document end
$(".services_shop_id").on("change", function(){	
	var val = $(this).val();
	var loc = 	$('#shop_id option[value="'+val+'"]').attr("data-location");
	var lat = 	$('#shop_id option[value="'+val+'"]').attr("data-latitude");
	var lng = 	$('#shop_id option[value="'+val+'"]').attr("data-longitude");
	$("#service_location").val(loc); 
	$("#service_latitude").val(lat); 
	$("#service_longitude").val(lng);
});
$(".autoschedule_checkbox").on("change", function(){	
	if(this.checked) {		
		$(".autoschedule_div .inputsche").removeAttr("disabled")
	} else {		
		$(".autoschedule_div .inputsche").attr("disabled","disabled");
	}
});
$("#service_for").on("change", function(){	
	var btntxt = $(".txtBtn").val().split("__");
	
	if($(this).val() == 2){
		$("#divChatUserId").removeClass("d-none");
		$(".submit-btn").text(btntxt[1]);
		$("#chat_userid").attr('disabled',false);
		$('#chat_userid').selectpicker('refresh');
	} else {
		$("#divChatUserId").addClass("d-none");
		$(".submit-btn").text(btntxt[0]);
		$("#chat_userid").attr('disabled',true);
		$('#chat_userid').selectpicker('refresh');
	}
});
//Rewards
$(document).ready(function(){
	$(".couponschkbox").prop('checked', false);
	$('.rewardsradio').prop("checked", false);
});
$(".rewardsradio").on('change',function(){
    var chkcount = $('.rewardsradio').filter(':checked').length;
    if (chkcount > 0) 
    {
			$(".addrewards").removeAttr('disabled');
    } 
    else 
    {
			$(".addrewards").attr('disabled','');
    }
});

$(document).on('click','.reward_cancel, .close',function(){
	$(".rewardsradio").prop('checked', false);
	$(".addrewards").attr('disabled','');
	$("#user_id").val('');
});


$(document).on('click','#cancel_offer, .close',function(){
	$(".offercls").val('');
});


$(document).on('click','#m_cancel_offer, .close',function(){
	$(".moffercls").val('');
});


  

      /* Image Upload */

      if($('#add_service, #update_service').length > 0 ){
        document.addEventListener("DOMContentLoaded", init, false);

        //To save an array of attachments 
        var AttachmentArray = [];

        //counter for attachment array
        var arrCounter = 0;

        //to make sure the error message for number of files will be shown only one time.
        var filesCounterAlertStatus = false;

        //un ordered list to keep attachments thumbnails
        var ul = document.createElement('ul');
        ul.className = ("upload-wrap");
        ul.id = "imgList";

        function init() {
            //add javascript handlers for the file upload event
            document.querySelector('#images').addEventListener('change', handleFileSelect, false);
          }

        //the handler for file upload event
        function handleFileSelect(e) {
            //to make sure the user select file/files
            if (!e.target.files) return;

            //To obtaine a File reference
            var files = e.target.files;

            // Loop through the FileList and then to render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                //instantiate a FileReader object to read its contents into memory
                var fileReader = new FileReader();

                // Closure to capture the file information and apply validation.
                fileReader.onload = (function (readerEvt) {
                  return function (e) {

                        //Apply the validation rules for attachments upload
                        ApplyFileValidationRules(readerEvt)

                        //Render attachments thumbnails.
                        RenderThumbnail(e, readerEvt);

                        //Fill the array of attachment
                        FillAttachmentArray(e, readerEvt)
                      };
                    })(f);

                // Read in the image file as a data URL.
                // readAsDataURL: The result property will contain the file/blob's data encoded as a data URL.
                // More info about Data URI scheme https://en.wikipedia.org/wiki/Data_URI_scheme
                fileReader.readAsDataURL(f);
              }
              document.getElementById('images').addEventListener('change', handleFileSelect, false);
            }

        //To remove attachment once user click on x button
        jQuery(function ($) {
          $('div').on('click', '.upload-images .file_close', function () {
            var id = $(this).closest('.upload-images').find('img').data('id');

                //to remove the deleted item from array
                var elementPos = AttachmentArray.map(function (x) { return x.FileName; }).indexOf(id);
                if (elementPos !== -1) {
                  AttachmentArray.splice(elementPos, 1);
                }

                //to remove image tag
                $(this).parent().find('img').not().remove();

                //to remove div tag that contain the image
                $(this).parent().find('div').not().remove();

                //to remove div tag that contain caption name
                $(this).parent().parent().find('div').not().remove();

                //to remove li tag
                var lis = document.querySelectorAll('#imgList li');
                for (var i = 0; li = lis[i]; i++) {
                  if (li.innerHTML == "") {
                    li.parentNode.removeChild(li);
                  }
                }

              });
        }
        )

        //Apply the validation rules for attachments upload
        function ApplyFileValidationRules(readerEvt)
        {
            //To check file type according to upload conditions
            if (CheckFileType(readerEvt.type) == false) {
              alert("The file (" + readerEvt.name + ") does not match the upload conditions, You can only upload jpg/png/gif files");
              e.preventDefault();
              return;
            }

            //To check files count according to upload conditions
            if (CheckFilesCount(AttachmentArray) == false) {
              if (!filesCounterAlertStatus) {
                filesCounterAlertStatus = true;
                alert("You have added more than 10 files. According to upload conditions you can upload 10 files maximum");
              }
              e.preventDefault();
              return;
            }
          }

        //To check file type according to upload conditions
        function CheckFileType(fileType) {
          if (fileType == "image/jpeg") {
            return true;
          }
          else if (fileType == "image/png") {
            return true;
          }
          else if (fileType == "image/gif") {
            return true;
          }
          else {
            return false;
          }
          return true;
        }

        //To check file Size according to upload conditions
        function CheckFileSize(fileSize) {
          if (fileSize < 300000) {
            return true;
          }
          else {
            return false;
          }
          return true;
        }

        //To check files count according to upload conditions
        function CheckFilesCount(AttachmentArray) {
            //Since AttachmentArray.length return the next available index in the array, 
            //I have used the loop to get the real length
            var len = 0;
            for (var i = 0; i < AttachmentArray.length; i++) {
              if (AttachmentArray[i] !== undefined) {
                len++;
              }
            }
            //To check the length does not exceed 10 files maximum
            if (len > 9) {
              return false;
            }
            else
            {
              return true;
            }
          }

        //Render attachments thumbnails.
        function RenderThumbnail(e, readerEvt)
        {
          var li = document.createElement('li');
          ul.appendChild(li);
          li.innerHTML = ['<div class=" upload-images"> ' +
          '<a style="display:block;" href="javascript:void(0);" class="file_close btn btn-icon btn-danger btn-sm">X</a><img class="thumb" src="', e.target.result, '" title="', escape(readerEvt.name), '" data-id="',
          readerEvt.name, '"/>' + '</div>'].join('');

          var div = document.createElement('div');
          div.className = "FileNameCaptionStyle d-none";
          li.appendChild(div);
          div.innerHTML = [readerEvt.name].join('');
          document.getElementById('uploadPreview').insertBefore(ul, null);
        }

        //Fill the array of attachment
        function FillAttachmentArray(e, readerEvt)
        {
          AttachmentArray[arrCounter] =
          {
            AttachmentType: 1,
            ObjectType: 1,
            FileName: readerEvt.name,
            FileDescription: "Attachment",
            NoteText: "",
            MimeType: readerEvt.type,
            Content: e.target.result.split("base64,")[1],
            FileSizeInBytes: readerEvt.size,
          };
          arrCounter = arrCounter + 1;
        }
      }

      function initialize() {
    // Create the autocomplete object, restricting the search
    // to geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */
      (document.getElementById('service_location')), {
        types: ['geocode']
      });
	  
	 

    google.maps.event.addDomListener(document.getElementById('service_location'), 'focus', geolocate);
    autocomplete.addListener('place_changed', get_latitude_longitude);
	
  }

  function get_latitude_longitude() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();
		 var key = $("#map_key").val();
        $.get('https://maps.googleapis.com/maps/api/geocode/json',{address:place.formatted_address,key:key},function(data, status){

          $(data.results).each(function(key,value){

            $('#service_address').val(place.formatted_address);
            $('#service_latitude').val(value.geometry.location.lat);
            $('#service_longitude').val(value.geometry.location.lng);


          });    
        });
      }

      function geolocate() {

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {

            var geolocation = new google.maps.LatLng(
              position.coords.latitude, position.coords.longitude);
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());

          });
        }
      }

      initialize();

    })(jQuery);
	
function check_offer(service_id) {
  $("#hservice_id").val(service_id);
}
function checkreward(user_id, total_visit_count)
{
		$("#h_user_id").val(user_id);
		$("#h_total_visit_count").val(total_visit_count);
}
function checkdelete(reward_id) {
	$("#d_reward_id").val(reward_id);
}
function submitoffer() {
    	$.ajax({
        type: "POST",
        url: base_url+"user/myservice/applyserviceoffer",
        data:{service_id:$("#hservice_id").val(),offer_percentage:$("#offer_percentage").val(), start_date:$("#start_date").val(), end_date:$("#end_date").val(), csrf_token_name:csrf_token},                         
        success: function (data) {   
          console.log(data);
        }
      });
}