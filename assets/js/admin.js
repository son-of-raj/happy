(function($) {
  "use strict";
  var csrf_token=$('#admin_csrf').val();
  var base_url=$('#base_url').val();


	// Variables declarations
	
	var $wrapper = $('.main-change_languagewrapper');
	var $wrapper1 = $('.main-wrapper');
	var $pageWrapper = $('.page-wrapper');
	var $slimScrolls = $('.slimscroll');
	$( document ).ready(function() {
   $('#save_profile_change').on('click',function(){
    changeAdminProfile();
  });
  
     $('#adminmail').on('blur',function(){
    
	 var email = $('#adminmail').val();
    $.ajax({
     type:'POST',
     url: base_url+'admin/profile/check_admin_mail',
     data :  {email:email,csrf_token_name:csrf_token},
     success:function(response)
     {
       if(response==1)
       {
         
		 
		 $("#email_error").html("Email ID already exist...!");
		 $("#save_profile_change").prop("disabled",true);
       }
       else {
		$("#email_error").html("");
		$("#save_profile_change").prop("disabled",false);
      }
    }
  });
  
  });
  
  
   $('#upload_images').on('click',function(){
    upload_images();
  }); 
 });
	// Sidebar
	
	var Sidemenu = function() {
		this.$menuItem = $('#sidebar-menu a');
	};
	
	function init() {
		var $this = Sidemenu;
		$('#sidebar-menu a').on('click', function(e) {
			if($(this).parent().hasClass('submenu')) {
				e.preventDefault();
			}
			if(!$(this).hasClass('subdrop')) {
				$('ul', $(this).parents('ul:first')).slideUp(350);
				$('a', $(this).parents('ul:first')).removeClass('subdrop');
				$(this).next('ul').slideDown(350);
				$(this).addClass('subdrop');
			} else if($(this).hasClass('subdrop')) {
				$(this).removeClass('subdrop');
				$(this).next('ul').slideUp(350);
			}
		});
		$('#sidebar-menu ul li.submenu a.active').parents('li:last').children('a:first').addClass('active').trigger('click');
	}
	
	// Sidebar Initiate
	init();
	
	// Mobile menu sidebar overlay
	
	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function() {
		$wrapper1.toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		return false;
	});
	
	// Sidebar overlay
	
	$(".sidebar-overlay").on("click", function () {
		$wrapper1.removeClass('slide-nav');
		$(".sidebar-overlay").removeClass("opened");
		$('html').removeClass('menu-opened');
	});	
	
	// Select 2
	
	if ($('.select').length > 0) {
		$('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}

	$(document).on('click', '#filter_search', function() {
		$('#filter_inputs').slideToggle("slow");
	});

	// Datetimepicker
  
  if($('.datetimepicker').length > 0 ){
    $('.datetimepicker').datetimepicker({
      format: 'DD-MM-YYYY',
      icons: {
        up: "fas fa-angle-up",
        down: "fas fa-angle-down",
        next: 'fas fa-angle-right',
        previous: 'fas fa-angle-left'
      }
    });
    $('.datetimepicker').on('dp.show',function() {
      $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
    }).on('dp.hide',function() {
      $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
    });
  }
  $('.start_date').datetimepicker({
    format: 'DD-MM-YYYY',
    icons: {
      up: "fas fa-angle-up",
      down: "fas fa-angle-down",
      next: 'fas fa-angle-right',
      previous: 'fas fa-angle-left'
    }
  });
  $('.start_date').on('dp.show',function() {
    $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
  }).on('dp.hide',function(e) {
    $('.end_date').data("DateTimePicker").minDate(e.date)
    $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
  });
  $('.end_date').datetimepicker({
    format: 'DD-MM-YYYY',
    icons: {
      up: "fas fa-angle-up",
      down: "fas fa-angle-down",
      next: 'fas fa-angle-right',
      previous: 'fas fa-angle-left'
    }
  });
  $('.end_date').on('dp.show',function() {
    $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
  }).on('dp.hide',function() {
    $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
  });


	// Tooltip
	
	/*if($('[data-bs-toggle="tooltip"]').length > 0 ){
		$('[data-bs-toggle="tooltip"]').tooltip();
	}
	*/
    // Datatable

    if ($('.datatable').length > 0) {
      $('.datatable').DataTable({
        "bFilter": false,
        "searching": true,
      });
    }
    $('.revenue_table').DataTable();
    $('.language_table').DataTable();
    $('.categories_table').DataTable();
    $('.contacts_det_table').DataTable();
    $('.contacts_table').DataTable();
    $('.ratingstype_table').DataTable();
    $('.service_table').DataTable();
    $('.payment_table').DataTable();
    $('.order_table').dataTable({
        "paging": false,
        "order": [[ 2, 'ASC' ]]
    });
    // Owl Carousel

    if ($('.images-carousel').length > 0) {
      $('.images-carousel').owlCarousel({
       loop: true,
       center: true,
       margin: 10,
       responsiveClass: true,
       responsive: {
        0: {
         items: 1
       },
       600: {
         items: 1
       },
       1000: {
         items: 1,
         loop: false,
         margin: 20
       }
     }
   });
    }

	// Sidebar Slimscroll

	if($slimScrolls.length > 0) {
		$slimScrolls.slimScroll({
			height: 'auto',
			width: '100%',
			position: 'right',
			size: '7px',
			color: '#ccc',
			allowPageScroll: false,
			wheelStep: 10,
			touchScrollStep: 100
		});
		var wHeight = $(window).height() - 60;
		$slimScrolls.height(wHeight);
		$('.sidebar .slimScrollDiv').height(wHeight);
		$(window).resize(function() {
			var rHeight = $(window).height() - 60;
			$slimScrolls.height(rHeight);
			$('.sidebar .slimScrollDiv').height(rHeight);
		});
	}
	
	// Small Sidebar

	$(document).on('click', '#toggle_btn', function() {
		if($('body').hasClass('mini-sidebar')) {
			$('body').removeClass('mini-sidebar');
			$('.subdrop + ul').slideDown();
		} else {
			$('body').addClass('mini-sidebar');
			$('.subdrop + ul').slideUp();
		}
		setTimeout(function(){ 
			mA.redraw();
			mL.redraw();
		}, 300);
		return false;
	});
	
	$(document).on('mouseover', function(e) {
		e.stopPropagation();
		if($('body').hasClass('mini-sidebar') && $('#toggle_btn').is(':visible')) {
			var targ = $(e.target).closest('.sidebar').length;
			if(targ) {
				$('body').addClass('expand-menu');
				$('.subdrop + ul').slideDown();
			} else {
				$('body').removeClass('expand-menu');
				$('.subdrop + ul').slideUp();
			}
			return false;
		}
		
		$(window).scroll(function() {
      if ($(window).scrollTop() >= 30) {
        $('.header').addClass('fixed-header');
      } else {
        $('.header').removeClass('fixed-header');
      }
    });
		
		$(document).on('click', '#loginSubmit', function() {
			$("#adminSignIn").submit();
		});
		
	});

  $('#adminSignIn').bootstrapValidator({
    fields: {
      username:   {
        validators:          {
          notEmpty:              {
            message: 'Please enter your Username'
          }
        }
      },
      password:           {
        validators:           {
          notEmpty:               {
            message: 'Please enter your Password'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {

    var username = $('#username').val();
    var password = $('#password').val();
    $.ajax({
     type:'POST',
     url: base_url+'admin/login/is_valid_login',
     data :  $('#adminSignIn').serialize(),
     success:function(response)
     {
       if(response==1)
       {
         window.location = base_url+'dashboard';
       }
       else {
        swal({
            title: "Wrong Credentials..!",
            text: "Invalid login credentials..",
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
    return false;
}); 


  $('#forgotpwdadmin').bootstrapValidator({
    fields: {
      email:   {
        validators:          {
          notEmpty:              {
            message: 'Please enter your Email ID'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {

    var email = $('#email').val();
    $.ajax({
     type:'POST',
     url: base_url+'admin/login/check_forgot_pwd',
     data :  $('#forgotpwdadmin').serialize(),
     success:function(response)
     {
       if(response==1)
       {
		 $("#err_frpwd").html("Reset link has been sent to your mail ID, Check your mail.").css("color","green");
       }
       else {
		$("#err_frpwd").html("Email ID Not Exist...!").css("color","red");
      }
    }
  });
    return false;
}); 



  $('#resetpwdadmin').bootstrapValidator({
    fields: {
      new_password:   {
        validators:          {
          notEmpty:              {
            message: 'Please enter your New Password'
          }
        }
      },
	  
	  confirm_password:   {
        validators:          {
          notEmpty:              {
            message: 'Please enter your Confirm Password'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {

    var new_password = $('#new_password').val();
    var confirm_password = $('#confirm_password').val();
	
	if(new_password == confirm_password)
	{
		$.ajax({
		 type:'POST',
		 url: base_url+'admin/login/save_reset_password',
		 data :  $('#resetpwdadmin').serialize(),
		 success:function(response)
		 {
		   if(response==1)
		   {
			 $("#err_respwd").html("Password Changed SuccessFully...!").css("color","green");
			 window.location = base_url+'admin';
		   }
		   else {
			$("#err_respwd").html("Something went wrong...!").css("color","red");
		  }
		}
	  });
	}
	else
	{
		$("#err_respwd").html("Password Mismatch...!").css("color","red");
		
	}
    
    return false;
}); 


  $('#addSubscription').bootstrapValidator({
    fields: {
      subscription_name:   {
        validators: {
          remote: {
            url: base_url + 'service/check-subscription-name',
            data: function(validator) {
              return {
                subscription_name: validator.getFieldElements('subscription_name').val(),
                csrf_token_name:csrf_token
              };
            },
            message: 'This subscription name is already exist',
            type: 'POST'
          },
          notEmpty: {
            message: 'Please enter subscription name'

          }
        }
      },
      amount:           {
        validators:           {
          notEmpty:               {
            message: 'Please enter subscription amount'
          }
        }
      },
      duration:           {
        validators:           {
          notEmpty:               {
            message: 'Please select subscription duration'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {

    var subscription_name = $('#subscription_name').val();
    var fee_description = $('#subscription_description').val();
    var amount = $('#amount').val();
    var duration = $('#duration').val();
    var status = $('input[name="status"]:checked').val();
	var subfor = $('input[name="subfor"]:checked').val();
	var values = $( 'input[name="subscriptionsdetails[]"]').map(function() { return this.value; }).get().join(",");
	var subctype = $('#subscription_type').val();
	
	  if(subfor == 3){
		  var d_url = 'freelancer-subscriptions';
	  } else {
		  var d_url = 'subscriptions';
	  }
	
    $.ajax({
     type:'POST',
     url: base_url+'service/save-subscription',
     data : {subscription_name:subscription_name,fee_description:fee_description,subscription_amount:amount,subscription_duration:duration,status:status,csrf_token_name:csrf_token,subdetais:values,subfor:subfor,subscription_type:subctype},
     success:function(response){
     console.log(response);
       if(response==1)
       {
         window.location = base_url+d_url;
       }
       else
       {
         window.location = base_url+d_url;
       }
     }
   });
    return false;
        }); 
        
  $('#add_app_keywords').bootstrapValidator({
    fields: {
      page_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },                
  }
}).on('success.form.bv', function(e) {


  return true;
});

 $('#psub_category').bootstrapValidator({
    fields: {
      subcategory_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter Sub category name'

          }
        }
      }                
  }
}).on('success.form.bv', function(e) {


  return true;
});

$('#units').bootstrapValidator({
    fields: {
      unit_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter unit name'

          }
        }
      },                
  }
}).on('success.form.bv', function(e) {
  return true;
});

  $('#add_language').bootstrapValidator({
    fields: {
      language_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter language name'

          }
        }
      },
      language_value:   {
        validators: {
          notEmpty: {
            message: 'Please enter language value'

          }
        }
      },
      language_type:   {
        validators: {
          notEmpty: {
            message: 'Please enter language type'

          }
        }
      },                  
  }
}).on('success.form.bv', function(e) {


  return true;
});

  $('#admin_settings').bootstrapValidator({
    fields: {
      website_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter website name'

          }
        }
      },
      contact_details:   {
        validators: {
          notEmpty: {
            message: 'Please enter contact details'

          }
        }
      },
      mobile_number:   {
        validators: {
          notEmpty: {
            message: 'Please enter mobile number'

          }
        }
      },
	currency_option:   {
        validators: {
          notEmpty: {
            message: 'Please select currency'

          }
        }
      },
	commission:   {
        validators: {
          notEmpty: {
            message: 'Please enter commission amount'

          }
        }
      },
	  
	  login_type:   {
        validators: {
          notEmpty: {
            message: 'Please select Login type'

          }
        }
      },
	paypal_gateway:   {
        validators: {
          notEmpty: {
            message: 'Please enter paypal gateway'

          }
        }
      },
	braintree_key:   {
        validators: {
          notEmpty: {
            message: 'Please enter braintree key'

          }
        }
      },
	site_logo:           {
		   validators:           {
			file: {
			  extension: 'jpeg,png,jpg',
			  type: 'image/jpeg,image/png,image/jpg',
			  message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
			}
		  }
		},
	favicon:           {
		   validators:           {
			file: {
			  extension: 'png,ico',
			  type: 'image/png,image/ico',
			  message: 'The selected file is not valid. Only allowed ico,png files'
			}
			
		  }
		},	
  }
}).on('success.form.bv', function(e) {


  return true;
});

  $('#add_category').bootstrapValidator({
    fields: {
      category_name:   {
        validators: {
          remote: {
            url: base_url + 'categories/check-category-name',
            data: function(validator) {
              return {
                category_name: validator.getFieldElements('category_name').val(),
                csrf_token_name:csrf_token
              };
            },
            message: 'This category name is already exist',
            type: 'POST'
          },
          notEmpty: {
            message: 'Please enter category name'

          }
        }
      },
      category_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        },
        notEmpty:               {
          message: 'Please upload category image'
        }
      }
    },
    category_mobile_icon:           {
      validators:           {
        file: {
          extension: 'jpeg,png',
          type: 'image/jpeg,image/png',
          message: 'The selected file is not valid. Only allowed jpeg,png files'
        },

        notEmpty:               {
          message: 'Please upload category mobile icon'
        }
      }
    }                    
  }
}).on('success.form.bv', function(e) {


  return true;
});  

$('#update_category').bootstrapValidator({
  fields: {
    category_name:   {
      validators: {
        remote: {
          url: base_url + 'categories/check-category-name',
          data: function(validator) {
            return {
              category_name: validator.getFieldElements('category_name').val(),
              csrf_token_name:csrf_token,
              category_id: validator.getFieldElements('category_id').val()
            };
          },
          message: 'This category name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter category name'

        }
      }
    },
     category_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        }
      }
    },

  }
}).on('success.form.bv', function(e) {


  return true;
        });   



$('#add_subcategory').bootstrapValidator({
  fields: {
    subcategory_name:   {
      validators: {
        remote: {
          url: base_url + 'categories/check-subcategory-name',
          data: function(validator) {
            return {
              category: validator.getFieldElements('category').val(),
              csrf_token_name:csrf_token,
              subcategory_name: validator.getFieldElements('subcategory_name').val()
            };
          },
          message: 'This sub category name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter sub category name'

        }
      }
    },

    subcategory_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        },
        notEmpty:               {
          message: 'Please upload category image'
        }
      }
    },
    category:           {
      validators:           {
        notEmpty:               {
          message: 'Please select category'
        }
      }
    }                  
  }
}).on('success.form.bv', function(e) {


  return true;
});  



$('#update_subcategory').bootstrapValidator({
  fields: {
    subcategory_name:   {
      validators: {
        remote: {
          url: base_url + 'categories/check-subcategory-name',
          data: function(validator) {
            return {
              category: validator.getFieldElements('category').val(),
              subcategory_name: validator.getFieldElements('subcategory_name').val(),
              csrf_token_name:csrf_token,
              subcategory_id: validator.getFieldElements('subcategory_id').val()
            };
          },
          message: 'This sub category name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter sub category name'

        }
      }
    },
     subcategory_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        }
      }
    },
    category:           {
      validators:           {
        notEmpty:               {
          message: 'Please select category'
        }
      }
    } 

  }
}).on('success.form.bv', function(e) {


  return true;
        });   


$('#add_sub_subcategory').bootstrapValidator({
  fields: {
    sub_subcategory_name:   {
      validators: {
        remote: {
          url: base_url + 'categories/check-subsubcategory-name',
          data: function(validator) {
            return {
              category: validator.getFieldElements('category').val(),
			  subcategory: validator.getFieldElements('subcategory').val(),
              csrf_token_name:csrf_token,
              sub_subcategory_name: validator.getFieldElements('sub_subcategory_name').val()
            };
          },
          message: 'This sub sub category name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter sub sub category name'

        }
      }
    },

    sub_subcategory_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        },
        notEmpty:               {
          message: 'Please upload category image'
        }
      }
    },
    category:           {
      validators:           {
        notEmpty:               {
          message: 'Please select category'
        }
      }
    }  ,
 subcategory:           {
      validators:           {
        notEmpty:               {
          message: 'Please select subcategory'
        }
      }
    }	
  }
}).on('success.form.bv', function(e) {


  return true;
});  


$('#add_ratingstype').bootstrapValidator({
  fields: {
    name:   {
      validators: {
        remote: {
          url: base_url + 'ratingstype/check-ratingstype-name',
          data: function(validator) {
            return {
              category_name: validator.getFieldElements('name').val(),
              csrf_token_name:csrf_token
            };
          },
          message: 'This Rating type name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter rating type name'

        }
      }
    },
  }
}).on('success.form.bv', function(e) {


  return true;
});

$('#update_sub_subcategory').bootstrapValidator({
  fields: {
    sub_subcategory_name:   {
      validators: {
        remote: {
          url: base_url + 'categories/check-subsubcategory-name',
          data: function(validator) {
            return {
              category: validator.getFieldElements('category').val(),
			  subcategory: validator.getFieldElements('subcategory').val(),
              csrf_token_name:csrf_token,
              sub_subcategory_name: validator.getFieldElements('sub_subcategory_name').val(),
			  sub_subcategory_id: validator.getFieldElements('sub_subcategory_id').val()
            };
          },
          message: 'This sub sub category name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter sub sub category name'

        }
      }
    },

    sub_subcategory_image:           {
       validators:           {
        file: {
          extension: 'jpeg,png,jpg',
          type: 'image/jpeg,image/png,image/jpg',
          message: 'The selected file is not valid. Only allowed jpeg,jpg,png files'
        },
        
      }
    },
    category:           {
      validators:           {
        notEmpty:               {
          message: 'Please select category'
        }
      }
    }  ,
 subcategory:           {
      validators:           {
        notEmpty:               {
          message: 'Please select subcategory'
        }
      }
    }	
  }
}).on('success.form.bv', function(e) {


  return true;
});  

$('#update_ratingstype').bootstrapValidator({
  fields: {
    name:   {
      validators: {
        remote: {
          url: base_url + 'ratingstype/check-ratingstype-name',
          data: function(validator) {
            return {
              name: validator.getFieldElements('name').val(),
              csrf_token_name:csrf_token,
              id: validator.getFieldElements('id').val()
            };
          },
          message: 'This rating type name is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter rating type name'

        }
      }
    },

  }
}).on('success.form.bv', function(e) {


  return true;
        });   




$("#duration").on("change", function(){
  var val = $(this).val();
  if(val == 1) var description = val+" Day";
  else var description = val+" Days";
  $("#subscription_description").val(description);
})

$('#editSubscription').bootstrapValidator({
  fields: {
    subscription_name:   {
      validators: {
        notEmpty: {
          message: 'Please enter subscription name'

        }
      }
    },
    amount:           {
      validators:           {
        notEmpty:               {
          message: 'Please enter subscription amount'
        }
      }
    },
    duration:           {
      validators:           {
        notEmpty:               {
          message: 'Please select subscription duration'
        }
      }
    }
  }
}).on('success.form.bv', function(e) {

  var subscription_id = $('#subscription_id').val();
  var subscription_name = $('#subscription_name').val();
  var fee_description = $('#subscription_description').val();
  var amount = $('#amount').val();
  var duration = $('#duration').val();
  var status = $('input[name="status"]:checked').val();  
  var values = $( 'input[name="subscriptionsdetails[]"]').map(function() { return this.value; }).get().join(",");
  var subctype = $('#subscription_type').val();
  
  var types = $("#subtype").val();
  if(types == 3){
	  var d_url = 'freelancer-subscriptions';
  } else {
	  var d_url = 'subscriptions';
  }
  
  $.ajax({
   type:'POST',
   url: base_url+'service/update-subscription',
   data : {subscription_id:subscription_id,subscription_name:subscription_name,fee_description:fee_description,subscription_amount:amount,subscription_duration:duration,status:status,csrf_token_name:csrf_token,subdetais:values,subfor:types,subscription_type:subctype},
   success:function(response)
   {
     if(response==1)
     {
       window.location = base_url+d_url;
     }
     else
     {
       window.location = base_url+d_url;
     }
   }
 });
  return false;
        }); 
$('#add_additional_services').bootstrapValidator({
    fields: {
      service_title:   {
        validators: {
          remote: {
            url: base_url + 'service/check-additional-servicename',
            data: function(validator) {
              return {
                service_title: validator.getFieldElements('service_title').val(),
                csrf_token_name:csrf_token
              };
            },
            message: 'This Service name is already exist',
            type: 'POST'
          },
          notEmpty: {
            message: 'Please enter Service name'

          }
        }
      },
	  services_for:    {
        validators:           {
          notEmpty:               {
            message: 'Please Select Service'
          }
        }
      },
      amount:           {
        validators:           {
          notEmpty:               {
            message: 'Please enter Service amount'
          },
		  regexp: {
			regexp: /^[1-9][0-9]{0,}$/,
			message: 'Please enter a valid number'
		  }
        }
      },
      duration:           {
        validators:           {
          notEmpty:               {
            message: 'Please select service duration'
          },
		  regexp: {
			regexp: /^[1-9][0-9]{0,}$/,
			message: 'Please enter duration value in number'
		  }
        }
      }
    }
  }).on('success.form.bv', function(e) {
		return true;
    }); 
	
	$('#edit_additional_services').bootstrapValidator({
    fields: {
      service_title:   {
        validators: {
          remote: {
            url: base_url + 'service/check-additional-servicename',
            data: function(validator) {
              return {
                service_title: validator.getFieldElements('service_title').val(),
                csrf_token_name:csrf_token,
				service_id: validator.getFieldElements('serviceid').val()
              };
            },
            message: 'This Service name is already exist',
            type: 'POST'
          },
          notEmpty: {
            message: 'Please enter Service name'

          }
        }
      },
	  services_for:    {
        validators:           {
          notEmpty:               {
            message: 'Please Select Service'
          }
        }
      },
      amount:           {
        validators:           {
          notEmpty:               {
            message: 'Please enter Service amount'
          },
		  regexp: {
			regexp: /^[1-9][0-9]{0,}$/,
			message: 'Please enter a valid number'
		  }
        }
      },
      duration:           {
        validators:           {
          notEmpty:               {
            message: 'Please select service duration'
          },
		  regexp: {
			regexp: /^[1-9][0-9]{0,}$/,
			message: 'Please enter duration value in number'
		  }
        }
      }
    }
  }).on('success.form.bv', function(e) {
		return true;
    }); 
	
	$('#add_provider').bootstrapValidator({
    fields: {
      mobileno:   {
        validators: {
          remote: {
            url: base_url + 'service/check-pro-mobile',
            data: function(validator) {
              return {
                userMobile: validator.getFieldElements('mobileno').val(),
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
            url: base_url + 'service/check-pro-emailid',
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
            message: 'Please Enter email'

          }
        }
      },
      name:           {
        validators:           {
          notEmpty:               {
            message: 'Please Enter Name'
          }
        }
      },
     category:           {
        validators:           {
          notEmpty:               {
            message: 'Please Select Category'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {    
      return true;
    }); 

	$('#edit_provider').bootstrapValidator({
    fields: {
      mobileno:   {
        validators: {
          remote: {
            url: base_url + 'service/check-pro-mobile',
            data: function(validator) {
              return {
                userMobile: validator.getFieldElements('mobileno').val(),
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
            url: base_url + 'service/check-pro-emailid',
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
      name:           {
        validators:           {
          notEmpty:               {
            message: 'Please Enter Name'
          }
        }
      },
	  country_code:           {
        validators:           {
          notEmpty:               {
            message: 'Please Select Country Code'
          }
        }
      },
      category:           {
        validators:           {
          notEmpty:               {
            message: 'Please Select Category'
          }
        }
      }
    }
  }).on('success.form.bv', function(e) {	  
	  	return true;
    }); 
	
	$('#add_user').bootstrapValidator({
    fields: {
      mobileno:   {
        validators: {
          remote: {
            url: base_url + 'dashboard/check-usr-mobile',
            data: function(validator) {
              return {
                userMobile: validator.getFieldElements('mobileno').val(),
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
            url: base_url + 'dashboard/check-usr-emailid',
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
            message: 'Please Enter email'

          }
        }
      },
      name:           {
        validators:           {
          notEmpty:               {
            message: 'Please Enter Name'
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
	  	return true;
    }); 

  $('#edit_user').bootstrapValidator({
    fields: {
      mobileno:   {
        validators: {
          notEmpty: {
            message: 'Please Enter Mobile Number'

          }
        }
      },
     email:   {
        validators: {
          notEmpty: {
            message: 'Please Enter Mobile Number'
          }
        }
      },
      name:           {
        validators:           {
          notEmpty:               {
            message: 'Please Enter Name'
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
      return true;
    }); 

  $('#shop_edit').bootstrapValidator({
    fields: {
      shop_name:   {
        validators: {
          notEmpty: {
            message: 'Please Enter Shop Name'

          }
        }
      },
     contact_no:   {
        validators: {
          notEmpty: {
            message: 'Please Enter Mobile Number'
          }
        }
      },
      email:           {
        validators:           {
          notEmpty:               {
            message: 'Please Enter E-mail'
          }
        }
      },
    }
  }).on('success.form.bv', function(e) {    
      return true;
    }); 
        
  $('#add_app_keywords').bootstrapValidator({
    fields: {
      page_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },                
  }
}).on('success.form.bv', function(e) {


  return true;
});


$('#addKeyword').bootstrapValidator({
  fields: {
    multiple_key:           {
      validators:           {
        notEmpty:               {
          message: 'Please enter keyword'
        }
      }
    }
  }
}).on('success.form.bv', function(e) {

  var page_key = $('#page_key').val();
  var multiple_key = $('#multiple_key').val();
  $.ajax({
   type:'POST',
   url: base_url+'admin/language/save_keywords',
   data : {page_key:page_key,multiple_key:multiple_key},
   success:function(response)
   {
     if(response==1)
     {
       window.location = base_url+'language/'+page_key;
     }
   }
 });
  return false;
        }); 

$('#image_upload_error').hide();
$('#image_error').hide();


var csrf_toiken=$('#admin_csrf').val();
var url = base_url+'admin/profile/check_password';

$('#change_password_form').bootstrapValidator({
  fields: {
    current_password: {
      validators: {
        remote: {
         url: url,
         data: function(validator) {
           return {
             current_password: validator.getFieldElements('current_password').val(),
             'csrf_token_name':csrf_token
           };
         },
         message: 'Current Password is Not Valid',
         type: 'POST'
       },
       notEmpty: {
        message: 'Please Enter Current Password'
      }
    }
  },

  new_password: {
    validators: {
     stringLength: {
      min: 4,
      message: 'The full name must be less than 4 characters'
    },
    different: {
      field: 'current_password',
      message: 'The Current password and New password cannot be the same as each other'
    },
    notEmpty: {
      message: 'Please Enter Password...'
    }
  }
},
confirm_password: {
  validators: {
   identical: {
    field: 'new_password',
    message: 'The password and its confirm are not the same'
  },
  notEmpty: {
    message: 'Please Enter Password...'
  }
}
}                    
}
}).on('success.form.bv', function(e) {
  e.preventDefault();
  $.ajax({
    url: base_url+'admin/profile/change_password',
    type: "post",
    data: $('#change_password_form').serialize(),
    success: function(response) {
      swal({
        title: "Password Updated..!",
        text: "Password Updated SuccessFully..",
        icon: "success",
        button: "okay",
        closeOnEsc: false,
        closeOnClickOutside: false
      }).then(function(){
        location.reload();
      });
    }
  });

});    

function update_language(lang_key, lang, page_key) {
	var cur_val = $('input[name="'+lang_key+'['+lang+']"]').val();
	var prev_val = $('input[name="prev_'+lang_key+'['+lang+']"]').val();

	$.post(base_url+'admin/language/update_language',{lang_key:lang_key, lang:lang, cur_val:cur_val, page_key:page_key},function(data){
		if(data == 1) {
			$("#flash_success_message").show();
		}
		else if(data == 0) {
			$('input[name="'+lang_key+'['+lang+']"]').val(prev_val);
			$("#flash_error_message").html('Sorry, This keyword already exist!');
			$("#flash_error_message").show();
		}
		else if(data == 2) {
			$('input[name="'+lang_key+'['+lang+']"]').val(prev_val);
			$("#flash_error_message").html('Sorry, This field should not be empty!');
			$("#flash_error_message").show();
		}
	});
}

function upload_images(){
	var img= $('.avatar-input').val();
	if(img!=''){
		$('#image_upload_error').hide();
		return true;
	}else{
		$('#image_upload_error').text('Please Upload an Image . ');
		$('#image_upload_error').show();
		return false;
	}
}

function changeAdminProfile(){
	$('#image_error').hide();
	var profile_img = $('#crop_prof_img').val();
	var adminmail = $('#adminmail').val();
	
	var error = 0;
	
	
	if(error==0){
		var url = base_url+'admin/profile/update_profile';
		//fetch file
		var formData = new FormData();
		formData.append('profile_img', profile_img);
		formData.append('adminmail', adminmail);
		formData.append('csrf_token_name', csrf_token);
		$.ajax({
			url: url,
			type: "POST",
			data: formData,
			cache: false,
			processData: false,
			contentType: false,
			context: this,
			success:function(res)
			{
       window.location.href=base_url+'admin-profile';
     }
   });
	}
}

function changeAdminProfile(){
  $('#image_error').hide();
  var profile_img = $('#crop_prof_img').val();
  var adminmail = $('#adminmail').val();
  
  var error = 0;
  
  
  if(error==0){
    var url = base_url+'admin/profile/update_profile';
    //fetch file
    var formData = new FormData();
    formData.append('profile_img', profile_img);
    formData.append('adminmail', adminmail);
    formData.append('csrf_token_name', csrf_token);
    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      context: this,
      success:function(res)
      {
       window.location.href=base_url+'admin-profile';
     }
   });
  }
}

function delete_category(id) {
	$('#delete_category').modal('show');
	$('#category_id').val(id);
}

function delete_subcategory(id) {
	$('#delete_subcategory').modal('show');
	$('#subcategory_id').val(id);
}

function delete_ratings_type(id) {
	$('#delete_ratings_type').modal('show');
	$('#id').val(id);
}


 $(document).on("click", ".delete_show", function () {
  alert();
    var id=$(this).attr('data-id');
    delete_modal_show(id);
  });
  
  $(document).on("click", "#chkdel_subcribe", function () {
    var id=$(this).attr('sid');
    subdelete_modal_show(id);
});

$(document).on("click", ".deposit_show", function () {
    var id  =$(this).attr('data-id');
    var bid =$(this).attr('data-book_ids');
	var cid =$(this).attr('data-cart_ids');
	var amt =$(this).attr('data-amount');
	deposit_modal_show(id, bid, cid, amt);
  });
  
  $(document).on("change", ".change_depstatus", function () {
    var id = $(this).attr('data-deposit_id');
	var val = $(this).val();
	change_deposit_status(id,val);
  });

    $(document).on("click",".delete_roles",function() {
      var id = $(this).attr('data-id');
      delete_roles(id);
    });

  function delete_roles(val) {
                bootbox.confirm("Are you sure want to delete this role? ", function (result) {
                if (result == true) {
                  var url = base_url + 'admin/delete-role';
                  var keyname="<?php echo $this->security->get_csrf_token_name(); ?>";
                  var keyvalue="<?php echo $this->security->get_csrf_hash(); ?>";
                  var role_id = val;
                  var data = { 
                    role_id: role_id,
                    csrf_token_name:csrf_token 
            };
            data[keyname] = keyvalue;
                  $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function (res) {
                      if (res == 1) {
                        $("#flash_success_message").show();
                        window.location = base_url + 'admin/roles';
                      } else {
                        window.location = base_url + 'admin/roles';
                      }
                    }
                  });
                }
              });
            }

function subdelete_modal_show(id) {
      $('#sub_delete_modal').modal('show');
      $('#confirm_delete_sub').attr('data-id',id);
  }
  $('#confirm_delete_sub').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_delete_subcription(id);
  });
  function confirm_delete_subcription(id) {
      if(id!=''){
            $('#sub_delete_modal').modal('hide');
             $.ajax({
                   type:'POST',
                   url: base_url+'admin/service/delete_subsciption',
                   data : {id:id,csrf_token_name:csrf_token},
                   dataType:'json',
                   success:function(response)
                   {
                      swal({
                        title: "Success..!",
                        text: "Deleted SuccessFully",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      }).then(function(){
                        location.reload();
                      });
                }
              });
            }
  }
  
  function delete_modal_show(id){
    $('#delete_modal').modal('show');
    $('#confirm_btn').attr('data-id',id);
    $('#confirm_delete_pro').attr('data-id',id);
	$('#confirm_btn_admin').attr('data-id',id);
  }
    $('#confirm_btn_admin').on('click',function(){ 
    var id=$(this).attr('data-id');
    var url=base_url+"admin/dashboard/adminuser_delete";
    delete_confirm(id,url);
  });
   function delete_confirm(id,url){
    if(id!=''){
      $('#delete_modal').modal('hide');
       $.ajax({
     type:'POST',
     url: url,
     data : {id:id,csrf_token_name:csrf_token},
     dataType:'json',
     success:function(response)
     {
       if(response.status)
       {
        swal({
          title: "Success..!",
          text: response.msg,
          icon: "success",
          button: "okay",
          closeOnEsc: false,
          closeOnClickOutside: false
        }).then(function(){
          location.reload();
        });

      }
      else {
       swal({
        title: "Error..!",
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
  
   function deposit_modal_show(pid, bid, cid, amt){	
	$('#confirm_btn_deposit').attr('data-id',pid);
	$("#deposit_form #pid").val(pid);
	$("#deposit_form #bid").val(bid);
	$("#deposit_form #cid").val(cid);
	$("#deposit_form #amt").val(amt);
	$.ajax({
	   type:'POST',
	   url: base_url+'admin/deposit/update_deposit_modal',
	   data : {id:pid, amt:amt, csrf_token_name:csrf_token},	   
	   success:function(response)
	   {		   
		  console.log(response);
		  $("#deposit_content").html(response);
		  $('#deposit_modal').modal('show');   
	   }
	});
  }
  
  
  $('#confirm_btn_deposit').on('click',function(){ 
    var id=$(this).attr('data-id');
    var url=base_url+"admin/deposit/confirm_deposit";
    confirm_deposit(id,url);
  });
   function confirm_deposit(id,url){
    if(id!=''){
      $('#deposit_modal').modal('hide');
	   
		var bid = $("#deposit_form #bid").val();
		var cid = $("#deposit_form #cid").val();
		var amt = $("#deposit_form #amt").val();
        $.ajax({
		 type:'POST',
		 url: url,
		 data : {id:id, bid:bid,cid:cid, amt:amt, csrf_token_name:csrf_token},
		 dataType:'json',
		 success:function(response)
		 {
		   if(response.status)
		   {
			swal({
			  title: "Success..!",
			  text: response.msg,
			  icon: "success",
			  button: "okay",
			  closeOnEsc: false,
			  closeOnClickOutside: false
			}).then(function(){
			  location.reload();
			});

		  }
		  else {
		   swal({
			title: "Error..!",
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
    } else {
	   swal({
		title: "Error..!",
		text: "Invalid Inputs",
		icon: "error",
		button: "okay",
		closeOnEsc: false,
		closeOnClickOutside: false
	  }).then(function(){
		location.reload();
	  });
	}
  }

  function change_deposit_status(id, val){
	  if(id!=''){
			 var url=base_url+"admin/deposit/change_deposit_status";
			$.ajax({
			 type:'POST',
			 url: url,
			 data : {id:id, val:val, csrf_token_name:csrf_token},
			 dataType:'json',
			 success:function(response)
			 {
			   if(response.status)
			   {
				swal({
				  title: "Success..!",
				  text: response.msg,
				  icon: "success",
				  button: "okay",
				  closeOnEsc: false,
				  closeOnClickOutside: false
				}).then(function(){
				  location.reload();
				});

			  }
			  else {
			   swal({
				title: "Error..!",
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
	}  else {
			   swal({
				title: "Error..!",
				text: "Invalid Inputs",
				icon: "error",
				button: "okay",
				closeOnEsc: false,
				closeOnClickOutside: false
			  }).then(function(){
				location.reload();
			  });
			}

  }

  $(document).on("click",".addfaq",function () {
  var experiencecontent = '<div class="row counts-list" id="faq_content">' +
  '<div class="col-md-11">' +
  '<div class="cards">' +
  '<div class="form-group">' +
  '<label>Title</label>' +
  '<input type="text" class="form-control" name="page_title[]" style="text-transform: capitalize;" required>' +
  '</div>' +
  '<div class="form-group mb-0">' +
  '<label>Page Content</label>' +
  ' <textarea class="form-control content-textarea" id="ck_editor_textarea_id"  name="page_content[]"></textarea>'+
  '</div>' +
  '</div>' +
  '</div>' +
  '<div class="col-md-1">' +
  '<a href="#" class="btn btn-sm bg-danger-light delete_faq">' +
  '<i class="far fa-trash-alt "></i> ' +
  '</a>' +
  '</div>' +
  '</div> ';
  
  $(".faq").append(experiencecontent);
  return false;
});

//Remove updated Faq content
$(document).on("click",".delete_faq_content",function () {
    var id = $(this).attr('data-id');
        $('#faq_'+id).remove();
    return false;
});

//Remove new Faq content
$(document).on("click",".delete_faq",function () {
    $(this).closest('#faq_content').remove();
    return false;
});
  
  function faq_delete(id)
  {
  var r = confirm("Deleting FAQ will also delete its related all datas!! ");
    if (r == true) {

      var csrf_token = $('#active_csrf').val();
      $.ajax({
        type: 'POST',
        url: base_url+"admin/settings/faq_delete",
        data: {
          id: id, 
          csrf_token_name: csrf_token
        },
        success: function (response)
        {

          if (response == 'success')
          {
            window.location = base_url+'admin/settings/faq_delete';
          }else{
            
            window.location = base_url+'admin/settings/faq_delete';
          }
        }
      });

    } else {
      return false;
    }
  

}
 $(document).ready(function() {
            $(document).on("click",".faq_delete",function() {
                var id = $(this).attr('data-id');
                faq_delete(id);
            });
       });



 $('#banner_settings').bootstrapValidator({
    fields: {

        category_title:   {
            validators: {
                regexp: {
                    regexp: /[a-zA-Z0-9]+$/,
                    message: 'This value not valid.'
                }
            }
        },
        banner_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        banner_sub_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
       
    popular_label:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Popular Searches Label Name'
                }
            }
        }
    }
            }).on('success.form.bv', function(e) {
  return true;
});

  $('#featured_categories').bootstrapValidator({
    fields: {
        featured_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        featured_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
       
    selected_categories:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Popular Searches Label Name'
                }
            }
        }
    }
            }).on('success.form.bv', function(e) {
  return true;
});

$('#popular_services').bootstrapValidator({
    fields: {
        title_services:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        content_services:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    services_count:   {
            validators: {
        
                notEmpty: {
                    message: 'Please enter Number of service'
                }
            }
        },
    }
}).on('success.form.bv', function(e) {
  return true;
});

$('#how_it_works').bootstrapValidator({
    fields: {
        how_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        how_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
        how_title_1:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        how_content_1:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
        how_title_2:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
       how_content_2:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
        how_title_3:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        how_content_3:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
     
    }
}).on('success.form.bv', function(e) {
  return true;
});

$('#download_sec').bootstrapValidator({
    fields: {
        download_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        download_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
        app_store_link:   {
            validators: {
                notEmpty: {
                    message: 'Please enter App Link'
                }
            }
        },
        play_store_link:   {
            validators: {
                notEmpty: {
                    message: 'Please enter App Link'
                }
            }
        },
    
    }
}).on('success.form.bv', function(e) {
  return true;
});

$('#about_us').bootstrapValidator({
    fields: {
        page_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        page_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    }
            }).on('success.form.bv', function(e) {
  return true;
});

$('#cookie_policy').bootstrapValidator({
    fields: {
        page_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        page_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    }
            }).on('success.form.bv', function(e) {
  return true;
});

$('#help').bootstrapValidator({
    fields: {
        page_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        page_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    }
            }).on('success.form.bv', function(e) {
  return true;
});

$('#privacy_policy').bootstrapValidator({
    fields: {
        page_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        page_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    }
            }).on('success.form.bv', function(e) {
  return true;
});

$('#terms_services').bootstrapValidator({
    fields: {
        page_title:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Title'
                }
            }
        },
        page_content:   {
            validators: {
                notEmpty: {
                    message: 'Please enter Content'
                }
            }
        },
    }
            }).on('success.form.bv', function(e) {
  return true;
});

/*sms gateway*/
    $(document).ready(function(){
        $("#2factor_div").css({"display": "none"});
        $("#twilio_div").css({"display": "none"});

        $("ul li").on("click",function(){
            if($(this).attr("data-id") == "nexmo") {
                $('ul li.active').removeClass('active');
                $(this).addClass("active");
                $("#nexmo_div").css({"display": ""});
                
                $("#2factor_div").css({"display": "none"});
                $("#twilio_div").css({"display": "none"});
            }
    
            if($(this).attr("data-id") == "2factor") {
                $('ul li.active').removeClass('active');
                $(this).addClass("active");
                $("#2factor_div").css({"display": ""});
                
                $("#twilio_div").css({"display": "none"});
                $("#nexmo_div").css({"display": "none"});
            }
    
            if($(this).attr("data-id") == "twilio") {
                $('ul li.active').removeClass('active');
                $(this).addClass("active");
                $("#twilio_div").css({"display": ""});
                
                $("#2factor_div").css({"display": "none"});
                $("#nexmo_div").css({"display": "none"});
            }
        });

        $(".sms_option").on("click", function(){
            var clickedByme = $(this).val();
      
            $('.sms_option').each(function () {
                if(clickedByme != this.value) {
                    $(this).prop('checked', false);
                }
            });
        });
    });

    $( ".mobileno" ).on('change',function(){
      var code = $("#mobileno").intlTelInput("getSelectedCountryData").dialCode;
      $('#country_code').val(code);
    });


    $(document).on("click",".addlinknew",function () {
    var len = $('.links-cont').length + 1;
    if(len <= 6) {
      var experiencecontent = '<div class="form-group links-cont">' +
      '<div class="row align-items-center">' +
      '<div class="col-lg-3 col-12">' +
      '<input type="text" class="form-control" name="label[]" attr="label" id="label" value="">' +
      '</div>' +
      '<div class="col-lg-8 col-12">' +
      '<input type="text" class="form-control" name="link[]" attr="link" id="link" value="'+base_url+'">' +
      '</div>' +
      '<div class="col-lg-1 col-12">' +
      '<a href="#" class="btn btn-sm bg-danger-light delete_links">' +
      '<i class="far fa-trash-alt "></i> ' +
      '</a>' +
      '</div>' +
      '</div>' +
      '</div>' ;
        $(".links-forms").append(experiencecontent);
    } else {
        $('.addlinknew').hide();
        alert('Allow 6 links only');
    }
  return false;
});

//Remove updated Links menus
$(document).on("click",".delete_links",function () {
    var id = $(this).attr('data-id');
    $('#link_'+id).remove();
    return false;
});

//Remove new Links menus
$(document).on("click",".delete_links",function () {
    $(this).closest('.links-cont').remove();
    return false;
});

$(document).on("click",".addsocail",function () {
  var experiencecontent = '<div class="form-group countset">' +
  '<div class="row align-items-center">' +
  '<div class="col-lg-2 col-12">' +
  '<div class="socail-links-set">' +
  '<ul>' +
  '<li class=" dropdown has-arrow main-drop">' +
  '<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" aria-expanded="false">' +
  '<span class="user-img">' +
  '<i class="fab fa-github me-2"></i>' +
  '</span>' +
  '</a>' +
  '<div class="dropdown-menu">' +
  '<a class="dropdown-item" href="#"><i class="fab fa-facebook-f me-2"></i>Facebook</a>' +
  '<a class="dropdown-item" href="#"><i class="fab fa-twitter me-2"></i>twitter</a>' +
  '<a class="dropdown-item" href="#"><i class="fab fa-youtube me-2"></i> Youtube</a>' +
  '</div>' +
  '</li>' +
  '</ul>' +
  '</div>' +
  '</div>' +
  '<div class="col-lg-9 col-12">' +
  '<input type="text" class="form-control" name="snapchat" attr="snapchat" id="facebook" value="">' +
  '</div>' +
  '<div class="col-lg-1 col-12">' +
  '<a href="#" class="btn btn-sm bg-danger-light  delete_review_comment">' +
  '<i class="far fa-trash-alt "></i> ' +
  '</a>' +
  '</div>' +
  '</div> ' +
  '</div> ';
  
  $(".setings").append(experiencecontent);
  return false;
});

$(".setings").on('click','.delete_review_comment', function () {
  $(this).closest('.countset').remove();
  return false;
});

$(document).on("click",".addnewlinks",function () {
    var len = $('.copyright_content').length + 1;
    if(len <= 3) {
    var experiencecontent = '<div class="form-group links-conts copyright_content">' +
      '<div class="row align-items-center">' +
      '<div class="col-lg-3 col-12">' +
      '<input type="text" class="form-control" value="" name="label1[]">' +
      '</div>' +
      '<div class="col-lg-8 col-12">' +
      '<input type="text" class="form-control" value="'+base_url+'" name="link1[]">' +
      '</div>' +
      '<div class="col-lg-1 col-12">' +
      '<a href="#" class="btn btn-sm bg-danger-light delete_copyright">' +
      '<i class="far fa-trash-alt "></i> ' +
      '</a>' +
      '</div>' +
      '</div>' +
      '</div>' ;
      $(".settingset").append(experiencecontent);
        return false;
    } else {
        $('.addnewlinks').hide();
        alert('Allow 3 links only');
    } 
  
});

//Remove updated copyright menus
$(document).on("click",".delete_copyright",function () {
    var id = $(this).attr('data-id');
    $('#link1_'+id).remove();
    return false;
});

//Remove new copyright menus
$(document).on("click",".delete_copyright",function () {
    $(this).closest('.links-conts').remove();
    return false;
});

 $(document).on("click", ".delete_show", function () {
    var id=$(this).attr('data-id');
    delete_modal_show(id);
  });
 $('.noty_clear').on('click',function(){
      var id=$(this).attr('data-token');
      noty_clear(id);
    });
 function noty_clear(id){
  if(id!=''){
   $.ajax({
     type: "post",
     url: base_url+"home/clear_all_noty",
     data:{csrf_token_name: csrf_token,id:id}, 
     dataType:'json',
     success: function (data) {


       if(data.success){
        $('.notification-list li').remove();
        $('.bg-yellow').text(0);
      }
    }

  });
 }
}
$('.lang_code').on('click',function(){
  var lang_code = $(this).attr('data-lang');
  $('#code_value').val(lang_code);
});

$(document).on("click", "#not_del", function () {
    var id=$(this).attr('data-id');
    delete_modal_show(id);
  });
  function delete_modal_show(id) {
      $('#not_delete_modal').modal('show');
      $('#confirm_delete_sub').attr('data-id',id);
    }
    $('#confirm_delete_sub').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_delete_not(id);
    });
    function confirm_delete_not(id) {
      if(id!=''){
            $('#not_delete_modal').modal('hide');
             $.ajax({
                   type:'POST',
                   url: base_url+'user/service/pro_not_del',
                   data : {id:id,csrf_token_name:csrf_token},
                   dataType:'json',
                   success:function(response)
                   {
                      swal({
                        title: "Success..!",
                        text: "Deleted SuccessFully",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      }).then(function(){
                        location.reload();
                      });
                }
              });
            }
    }

    $(document).on("click", "#not_del_all", function () {
   $('#notall_delete_modal').modal('show');
  });
    $('#confirm_deleteall_sub').on('click',function(){
      confirm_deleteall_subcription();
    });
    function confirm_deleteall_subcription() {
      $('#notall_delete_modal').modal('hide');
       $.ajax({
             type:'POST',
             url: base_url+'user/service/pro_not_del',
             data : {csrf_token_name:csrf_token},
             dataType:'json',
             success:function(response)
             {
                swal({
                  title: "Success..!",
                  text: "Deleted SuccessFully",
                  icon: "success",
                  button: "okay",
                  closeOnEsc: false,
                  closeOnClickOutside: false
                }).then(function(){
                  location.reload();
                });
          }
        });
    }

    $('#currency_add').bootstrapValidator({
    fields: {
      currency_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency name'

          }
        }
      },
      currency_symbol:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Symbol'

          }
        }
      },
      currency_code:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Code'

          }
        }
      },
      rate:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Rate'

          }
        }
      },                  
  }
  }).on('success.form.bv', function(e) {
    return true;
  });

    $('#currency_edit').bootstrapValidator({
    fields: {
      currency_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency name'

          }
        }
      },
      currency_symbol:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Symbol'

          }
        }
      },
      currency_code:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Code'

          }
        }
      },
      rate:   {
        validators: {
          notEmpty: {
            message: 'Please enter Currency Rate'

          }
        }
      },                  
  }
  }).on('success.form.bv', function(e) {
    return true;
  });

  $(document).on("click", "#cur_del", function () {
    var id=$(this).attr('data-id');
    curdelete_modal_show(id);
  });
  function curdelete_modal_show(id) {
      $('#cur_delete_modal').modal('show');
      $('#confirm_delete_cur').attr('data-id',id);
    }
    $('#confirm_delete_cur').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_delete_currency(id);
    });
    function confirm_delete_currency(id) {
      if(id !=''){
            $('#cur_delete_modal').modal('hide');
             $.ajax({
                   type:'POST',
                   url: base_url+'admin/settings/cur_delete',
                   data : {id:id,csrf_token_name:csrf_token},
                   dataType:'json',
                   success:function(response)
                   {
                      swal({
                        title: "Success..!",
                        text: "Deleted SuccessFully",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      }).then(function(){
                        location.reload();
                      });
                }
              });
            }
    }

    $(document).on("click", "#ear_del", function () {
    var id=$(this).attr('data-id');
    alldelete_modal_show(id);
  });
  function alldelete_modal_show(id) {
      $('#ear_delete_modal').modal('show');
      $('#confirm_delete_ear').attr('data-id',id);
    }
    $('#confirm_delete_ear').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_delete_earnings(id);
    });
    function confirm_delete_earnings(id) {
      if(id !=''){
            $('#ear_delete_modal').modal('hide');
             $.ajax({
                   type:'POST',
                   url: base_url+'admin/payments/ear_delete',
                   data : {id:id,csrf_token_name:csrf_token},
                   dataType:'json',
                   success:function(response)
                   {
                      swal({
                        title: "Success..!",
                        text: "Deleted SuccessFully",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      }).then(function(){
                        location.reload();
                      });
                }
              });
            }
    }

  $('.pages_list_status').on('click',function(){
  var id = $(this).attr('data-id');
  pages_list_status(id);
  });
  function pages_list_status(id){
  var stat= $('#pages_list_status'+id).prop('checked');
  if(stat==true) {
    var status=1;
  }
  else {
    var status=2;
  }
  var url = base_url+ 'admin/settings/page_list_status';
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
    /** Delete Blog Categories */
    $(document).on("click",".delete_blog_categories",function() {
        var id = $(this).attr('data-id');
        delete_blog_categories(id);
    });

    function delete_blog_categories(val) {
        bootbox.confirm("Deleting Blog category will also delete its Blog!! ", function (result) {
            if (result == true) {
                var url = BASE_URL + 'admin/blog_categories/delete_blog_category';
                var keyname="<?php echo $this->security->get_csrf_token_name(); ?>";
                var keyvalue="<?php echo $this->security->get_csrf_hash(); ?>";
                var category_id = val;
                var data = { 
                    category_id: category_id,
                    csrf_token_name:csrf_token
                };
                data[keyname] = keyvalue;
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function (res) {
                        if (res == 1) {
                            $("#flash_success_message").show();
                            window.location = base_url + 'blog-categories';
                        } else {
                            window.location = base_url + 'blog-categories';
                        }
                    }
                });
            }
        });
    }

    $(document).on("click",".delete_blog",function() {
        var id = $(this).attr('data-id');
        delete_blog(id);
    });

  $(document).on("click", "#pages_del", function () {
    var id=$(this).attr('data-id');
    pagesdelete_modal_show(id);
  });
  function pagesdelete_modal_show(id) {
      $('#pages_delete_modal').modal('show');
      $('#confirm_delete_pages').attr('data-id',id);
    }
    $('#confirm_delete_pages').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_delete_pages(id);
    });
    function confirm_delete_pages(id) {
      if(id !=''){
            $('#pages_delete_modal').modal('hide');
             $.ajax({
                   type:'POST',
                   url: base_url+'admin/settings/pages_delete',
                   data : {id:id,csrf_token_name:csrf_token},
                   dataType:'json',
                   success:function(response)
                   {
                      swal({
                        title: "Success..!",
                        text: "Deleted SuccessFully",
                        icon: "success",
                        button: "okay",
                        closeOnEsc: false,
                        closeOnClickOutside: false
                      }).then(function(){
                        location.reload();
                      });
                }
              });
            }
    }

    $('.chngstatus').on('change', function()
    {
      var id = $(this).attr('data-id');
      var statusId = $(this).val();
      if (statusId) {
        var url = base_url+ 'admin/settings/offline_status';
        var status_id = id;
        var status = status;
        var data = { 
          status_id: status_id,
          status: statusId,
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
           title: "offline Payment",
           text: "Status Change SuccessFully....!",
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
      else 
      {
        return false;
      }
    });

    function delete_blog(val) {
        bootbox.confirm("Deleting Blog", function(result) {
            if (result == true) {
                var url = base_url + 'admin/blogs/delete_blogs';
                var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
                var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
                var category_id = val;
                var data = {
                    post_id: category_id,
                    csrf_token_name:csrf_token
                };
                data[keyname] = keyvalue;
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function(res) {
                        if (res == 1) {
                            $("#flash_success_message").show();
                            window.location = base_url + 'blogs';
                        } else {
                            window.location = base_url + 'blogs';
                        }
                    }
                });
            }
        });
    }

    $(document).on("click",".blog_inactive",function() {
        var id = $(this).attr('data-id');
        blog_status_update(id,2);
    });

    function blog_status_update(val, status) {
        bootbox.confirm("Update Blog Status", function(result) {
            if (result == true) {
                var url = base_url + 'admin/blogs/update_blog_status';
                var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
                var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
                var category_id = val;
                var data = {
                    post_id: category_id,
                    status: status,
                    csrf_token_name:csrf_token
                };
                data[keyname] = keyvalue;
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function(res) {
                        if (res == 1) {
                            $("#flash_success_message").show();
                            window.location = base_url + 'blogs';
                        } else {
                            window.location = base_url + 'blogs';
                        }
                    }
                });
            }
        });
    }

    $(document).on("click",".blog_active",function() {
        var id = $(this).attr('data-id');
        blog_status_update(id,1);
    });

    function blog_status_update(val, status) {
        bootbox.confirm("Update Blog Status", function(result) {
            if (result == true) {
                var url = base_url + 'admin/blogs/update_blog_status';
                var keyname = "<?php echo $this->security->get_csrf_token_name(); ?>";
                var keyvalue = "<?php echo $this->security->get_csrf_hash(); ?>";
                var category_id = val;
                var data = {
                    post_id: category_id,
                    status: status,
                    csrf_token_name:csrf_token
                };
                data[keyname] = keyvalue;
                $.ajax({
                    url: url,
                    data: data,
                    type: "POST",
                    success: function(res) {
                        if (res == 1) {
                            $("#flash_success_message").show();
                            window.location = base_url + 'blogs';
                        } else {
                            window.location = base_url + 'blogs';
                        }
                    }
                });
            }
        });
      }
    $('.pro_change').on('change', function()
    {
      var id = $(this).val();
      var shopid = $(this).attr('data-shopid');
      if (id) {
        var url = base_url+ 'admin/add-provider-session';
        var data = { 
          id: id,
          shopid: shopid,
          csrf_token_name:csrf_token
        };
        $.ajax({
          url: url,
          data: data,
          type: "POST",
          success: function (data) {
            console.log(data);
            if(data){
             $("#shop_id").html(data);
            }
          }
        });
      }
      else 
      {
        return false;
      }
    });


     $('.delete_img').on('click', function() {
        var img_id = $(this).attr('data-img_id');
        delete_img(img_id);
    });

    function delete_img(img_id) {
        $('#service_img_' + img_id).remove();
        $.ajax({
            type: "POST",
            url: base_url + 'user/service/delete_service_img',
            data: { img_id: img_id, csrf_token_name:csrf_token },
            success: function(data) {}
        });
    }



    $(".add-additional").on('click', function () {
      $(".additional-cont-label").removeClass("d-none");
    var durintxt = $("#durintxt").val();
      var membershipcontent = '<div class="row form-row additional-cont">' +
      
    '<div class="col-12 col-md-10 col-lg-4">' +
      '<div class="form-group">' +
      '<input class="form-control addicls" type="text" name="addi_servicename[]" id="addi_name" />' +
      '</div>' +
      '</div>' +
    
    '<div class="col-12 col-md-10 col-lg-3">' +
      '<div class="form-group">' +
      '<input class="form-control addicls onlynumber" type="text" name="addi_serviceamnt[]" id="addi_amnt" />' +
      '</div>' +
      '</div>' +
    
    '<div class="col-12 col-md-10 col-lg-3">' +
      '<div class="form-group">' +
      '<div class="input-group">  ' +                  
    '<input type="text" class="form-control addicls onlynumber" name="addi_servicedura[]" id="addi_dura" />' +
      '<div class="input-group-append">' +
      '<span class="input-group-text" id="basic-addon2">'+durintxt+'</span>' +
      '</div>     ' +             
    '</div>' +
      '</div>' +
      '</div>' +
    
    '<div class="col-12 col-md-2 col-lg-2">' +
      '<a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a>' +
      '</div>' +
    
      '</div>';
      $(".additional-info").append(membershipcontent);
    chknum();
      return false;
    });
    $(".additional-info").on('click','.additional-cont .trash-alert', function () {
    var tit = $(this).attr('data-addialerttxt');
    var txt = $(this).attr('data-addialertmsg');
    swal({
       title: tit,
       text: txt,
       icon: "error",
       button: "okay",
       closeOnEsc: false,
       closeOnClickOutside: false
     }).then(function(){
      
    });
    });
  

    $(".additional-info").on('click','.additional-cont .trash', function () {     
    $(this).closest('.additional-cont').remove();
    var divCount = $('.additional-cont').length;  
    if(divCount == 0){
      $(".additional-cont-label").addClass("d-none");
    }     
      return false;
    });

    function change_comment_status(id, status) {
        if(confirm("Are you sure you want to change this Status...!?")){
            $.post(base_url+'admin/blogs/changeCommentStatus',{comments_id:id,status:status,csrf_token_name:csrf_token},function(result){
                if(result) {
                    window.location.reload();
                }
            });
        }   
    }

    $(document).on("change",".commentstatus",function(e){
        var id=$(this).attr('data-id');
        var status = $(this).val();
        change_comment_status(id, status);
    });    

    function change_comment_status(id, status) {
        if(confirm("Are you sure you want to change this Status...!?")){
            $.post(base_url+'admin/blogs/changeCommentStatus',{comments_id:id,status:status,csrf_token_name:csrf_token},function(result){
                if(result) {
                    window.location.reload();
                }
            });
        }   
    }        

    $('#add_country_code_config').bootstrapValidator({
    fields: {
      country_code:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      country_id:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      country_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    }); 

    $('#edit_country_code_config').bootstrapValidator({
    fields: {
      country_code:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      country_id:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      country_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    });    

    $('#add_city_code_config').bootstrapValidator({
    fields: {
      state_id:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      city_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    }); 
    //display state as per country selection
    $('#country_id').on('change', function(){
        var countryName = $(this).val();
        if(countryName){
            $.ajax({
                type:'GET',
                url: base_url+"admin/State_code_config/get_state_code/"+countryName,
                success:function(html){
                  var obj = jQuery.parseJSON(html);
                  $('#state_id').empty();
                  $(obj).each(function ()
                  {
                      var option = $('<option />');
                      option.attr('value', this.value).text(this.label);
                      $('#state_id').append(option);
                  });
                  $('#state_id').val(state);
                }
            }); 
        }else{
          $('#state_id').html('<option value="">Select country first</option>');
      }
  });














    $('#edit_city_code_config').bootstrapValidator({
    fields: {
      state_id:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      city_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    });

    $('#add_state_code_config').bootstrapValidator({
    fields: {
      countryid:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      state_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    });  

    $('#edit_state_code_config').bootstrapValidator({
    fields: {
      countryid:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },
      state_name:   {
        validators: {
          notEmpty: {
            message: 'Please enter page name'

          }
        }
      },               
    }
    }).on('success.form.bv', function(e) {
      return true;
    });  

     $('.lang_app_code').on('click',function(){
        var lang_code = $(this).attr('data-lang');
        $('#code_app_value').val(lang_code);
      }); 
    $(document).ready(function(){
        var chat_type = $('.chatype').val()
        if(chat_type == 'php_chat'){
        $("#websocket_details").hide();
        }
        $('#php_chat').on('change',function(){
          $("#websocket_details").hide();
        });
        $('#websocket').on('change',function(){
          $("#websocket_details").show();
        });
      });

})(jQuery);