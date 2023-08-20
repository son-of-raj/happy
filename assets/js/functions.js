(function($) {
	"use strict";

  var base_url=$('#base_url').val();
  var BASE_URL=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();
  var user_type=$('#user_type').val();
  var modules=$('#modules_page').val();
  var cookies_content=$('#cookies_showhide').val();
  var cookies_text=$('#cookies_content_text').val();

  $( document ).ready(function() {
	  
	var hash = window.location.hash;
	   if(hash != ''){
		  //$("a[href='"+url+"']").click();
	   }

	  
    $('#flash_succ_message2').hide();
    $('#flash_error_message1').hide();
    $('#otp_final_div').hide();
    $("#reason_div").hide();

    $('.error_rating').hide();
    $('.error_review').hide();
    $('.error_type').hide();

    $('.error_cancel').hide();
    $('.header-content-blk').hide();
    $('#contact_form').bootstrapValidator({
      fields: {
        name:           {
       validators: {		 
         notEmpty: {
         message: 'Please enter your name'
         }
       }
       },
         email:           {
       validators: {		 
         notEmpty: {
         message: 'Please enter your email'
         },
         emailAddress: {
                message: 'The value is not a valid email address'
             }
       }
       },
       message:           {
       validators: {		 
         notEmpty: {
         message: 'Please enter your message'
         }
       }
       }
     }
     }).on('success.form.bv', function(e) {
       var name = $('#name').val();
       var email = $('#email').val();
       var message = $('#message').val();
       $.ajax({
       type:'POST',
       url: base_url+'user/contact/insert_contact',
       data : {name:name,email:email,csrf_token_name:csrf_token,message:message},
       success:function(response)
       {
         if(response==1)
         {
            swal({
            title: "Message Send !",
            text: "Message Send Successfully....!",
            icon: "success",
            button: "okay",
            closeOnEsc: false,
            closeOnClickOutside: false
            }).then(function(){
           window.location.href = base_url+'contact';
         });
         
         } else {
         $("#flash_error_message1").show();
         $('#flash_error_message1').append('Wrong Credentials');
         return false;
         }
       }
       });
       return false;
     });  
     $(document).ready(function () {  
         $(".covid").prop("checked", true);              
      });  
    $('#re_send_otp_user').on('click',function(){
      re_send_otp_user();
    }); 
    $('.isNumber').on('keypress',function(){
      var id=$(this).val();
      isNumber(id);
    });  
    $('.chat_clear_all').on('click',function(){
      var id=$(this).attr('data-token');
      chat_clear_all(id);
    });
    $('.noty_clear').on('click',function(){
      var id=$(this).attr('data-token');
      noty_clear(id);
    }); 
    $('#rate_booking').on('click',function(){
      rate_booking();
    }); $('#cancel_booking').on('click',function(){
      cancel_booking();
    });  $('#provider_cancel_booking').on('click',function(){
      provider_cancel_booking();
    }); 
    $('#go_user_settings').on('click',function(){
      window.location=base_url+"user-settings/";
    }); 
    $('#go_book_service').on('click',function(){
      var service_id=$(this).attr('data-id');
	  window.location=base_url+'book-appointment/'+service_id;
    }); 
    $('#add_wallet_money').on('click',function(){
      add_wallet_money();
    });
    $('.reason_modal').on('click',function(){
      var id=$(this).attr('data-id');
      reason_modal(id);
    });
    $('.update_user_booking_status').on('click',function(){
      var id=$(this).attr('data-id');
      var status=$(this).attr('data-status');
      var rowid=$(this).attr('data-rowid');
      var review=$(this).attr('data-review');
      update_user_booking_status(id,status,rowid,review);
    }); 
    $('.update_pro_booking_status').on('click',function(){
      var id=$(this).attr('data-id');
      var status=$(this).attr('data-status');
      var rowid=$(this).attr('data-rowid');
      var review=$(this).attr('data-review');
      update_pro_booking_status(id,status,rowid,review);
    });  
    $('.go_provider_availability').on('click',function(){
      window.location=base_url+"provider-availability";
    });   
    $('#re_send_otp_provider').on('click',function(){
      re_send_otp_provider();
    });   
    $('.get_pro_subscription').on('click',function(){
		var tit=$(this).attr('data-title');
		var subtit=$(this).attr('data-subtitle');
		get_pro_subscription(tit,subtit);
    }); 
    $('.get_admin_approval').on('click', function() {
      get_admin_approval();
    });
    $('.get_pro_availabilty').on('click',function(){
		var tit=$(this).attr('data-title');
		var subtit=$(this).attr('data-subtitle');
       get_pro_availabilty(tit,subtit);
    });
    $('.get_pro_availabilty').on('click',function(){
      var tit=$(this).attr('data-title');
		var subtit=$(this).attr('data-subtitle');
       get_pro_availabilty(tit,subtit);
    }); 
	$('.get_pro_addshop').on('click',function(){
		var tit=$(this).attr('data-title');
		var subtit=$(this).attr('data-subtitle');
        get_pro_addshop(tit,subtit);
    }); 
	$('.get_pro_addstaff').on('click',function(){
		var tit=$(this).attr('data-title');
		var subtit=$(this).attr('data-subtitle');
        get_pro_addstaff(tit,subtit);
    }); 
    $('.search_service').on('click',function(){
      $('#search_service').submit();
    }); $('.check_user_reason').on('submit',function(){
      var result=check_user_reason();
      return result;
    }); 
    $('.user_update_status').on('click',function(){
      user_update_status(this);
    }); 
    $('.no_only').on('keyup',function(e){
     $(this).val($(this).val().replace(/[^\d].+/, ""));
     if ((event.which < 48 || event.which > 57)) {
      event.preventDefault();
    }
  }); 
    $('.pagination_no').on('click',function(){
		var id=$(this).attr('data-id');
		var searchfor = $("#searchfor").val();
		if(searchfor!=''&&searchfor!=undefined&&searchfor=='service'){
			getDataService(id);
		} else if(searchfor!=''&&searchfor!=undefined&&searchfor=='shop'){
			getDataShop(id);
		} else {
			getData(id);
		}
    }); 


    $(".user_mobile").on('keyup keypress blur change', function(e) {
    //return false if not 0-9
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
     return false;
   }else{
        //limit length but allow backspace so that you can still delete the numbers.
        if( $(this).val().length >= parseInt($(this).attr('maxlength')) && (e.which != 8 && e.which != 0)){
          return false;
        }
      }

    });

    
    $('#step1_footer').prop("disabled", true); 
    $.ajax({
     type: "GET",
     url: base_url+"user/service/get_category",
     data:{id:$(this).val(),csrf_token_name:csrf_token}, 
     beforeSend :function(){

       $('#categorys').find("option:eq(0)").html("Please wait..");

     },                         
     success: function (data) { 

       $('#categorys').find("option:eq(0)").html("Select Category");

       var obj=jQuery.parseJSON(data);       

       $(obj).each(function(){
         var option = $('<option />');
         option.attr('value', this.value).text(this.label);           
         $('#categorys').append(option);
       });       

     }
   });

    $('#category').on('change',function(){
       $('#subcategory').html('<option value="">Select subcategory</option>');
        if($(this).val()!='') {
            $.ajax({
                type: "POST",
                url: base_url+"user/service/get_subcategory",
                data:{id:$(this).val(),'csrf_token_name':csrf_token},                        
                success: function (data) {   
                    $('#subcategory').find("option:eq(0)").html("Select SubCategory");
                    var obj=jQuery.parseJSON(data); 
                    if(obj) {
                        $(obj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.value).text(this.label);           
                            $('#subcategory').append(option);
                        });
                    } else {
                       $('#subcategory').find("option:eq(0)").html("Select SubCategoryss"); 
                    }  
                           
                }
            });
        }
    });

    $('#categories').on('change',function(){
       $('#subcategories').html('<option value="">Select subcategory</option>');
        if($(this).val()!='') {
            $.ajax({
                type: "POST",
                url: base_url+"user/service/get_subcategory",
                data:{id:$(this).val(),'csrf_token_name':csrf_token}, 
                beforeSend :function(){
                    $('#subcategories').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {   
                    $('#subcategories').find("option:eq(0)").html("Select SubCategory");
                    var obj=jQuery.parseJSON(data);    
                    $(obj).each(function(){
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);           
                        $('#subcategories').append(option);
                    });       
                }
            });
        }
    });

    $('#subcategories').on('change',function(){
        var category = $('#categories').val();
       $('#sub_subcategories').html('<option value="">Select subcategory</option>');
        if($(this).val()!='') {
            $.ajax({
                type: "POST",
                url: base_url+"user/service/get_subsubcategory",
                data:{category_id: category, subcategory_id:$(this).val(),'csrf_token_name':csrf_token}, 
                beforeSend :function(){
                    $('#sub_subcategories').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {   
                    $('#sub_subcategories').find("option:eq(0)").html("Select SubCategory");
                    var obj=jQuery.parseJSON(data);
                    if(obj) {    
                        $(obj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.value).text(this.label);           
                            $('#sub_subcategories').append(option);
                        });
                    } else {
                        $('#sub_subcategories').find("option:eq(0)").html("Details not found");
                    }    
                }
            });
        }
    });

    $('#service_category').on('change',function(){
       $('#service_subcategory').html('<option value="">Select subcategory</option>');
        if($(this).val()!='') {
            $.ajax({
                type: "POST",
                url: base_url+"user/service/get_subcategory",
                data:{id:$(this).val(),'csrf_token_name':csrf_token}, 
                beforeSend :function(){
                    $('#service_subcategory').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {   
                    $('#service_subcategory').find("option:eq(0)").html("Select SubCategory");
                    var obj=jQuery.parseJSON(data);    
                    $(obj).each(function(){
                        var option = $('<option />');
                        option.attr('value', this.value).text(this.label);           
                        $('#service_subcategory').append(option);
                    });       
                }
            });
        }
    });

    $('#service_subcategory').on('change',function(){
        var category = $('#service_category').val();
       $('#service_sub_subcategories').html('<option value="">Select subcategory</option>');
        if($(this).val()!='') {
            $.ajax({
                type: "POST",
                url: base_url+"user/service/get_subsubcategory",
                data:{category_id: category, subcategory_id:$(this).val(),'csrf_token_name':csrf_token}, 
                beforeSend :function(){
                    $('#service_sub_subcategory').find("option:eq(0)").html("Please wait..");
                },                         
                success: function (data) {   
                    $('#service_sub_subcategory').find("option:eq(0)").html("Select SubCategory");
                    var obj=jQuery.parseJSON(data);
                    if(obj) {    
                        $(obj).each(function(){
                            var option = $('<option />');
                            option.attr('value', this.value).text(this.label);           
                            $('#service_sub_subcategory').append(option);
                        });
                    } else {
                        $('#service_sub_subcategory').find("option:eq(0)").html("Details not found");
                    }    
                }
            });
        }
    });

    $( ".staffmobile" ).on('change',function(){
      var code = $("#mobileno").intlTelInput("getSelectedCountryData").dialCode;
      $('#country_code').val(code);
    });

    $( ".umobileno").on('change',function(){
      var code = $("#mobileno").intlTelInput("getSelectedCountryData").dialCode;
      $('#country_code').val(code);
    });


    $('#subcategorys').on('change',function(){
     if($(this).val()){
       $('#step3_footer').prop("disabled", false);
     }
     else {$('#step3_footer').prop("disabled", true);}


   });

    $('#new_fourth_page').bootstrapValidator({
     fields: {
       otp_number: {
         validators: {
           notEmpty: {
             message: 'Please enter OTP'
           }
         }
       },
     }

   }).on('success.form.bv', function(e) {

     var otp =         $('#otp_number').val();
     var userMobile =  $('#userMobile').val();

     var categorys=    $('#categorys').val();
     var subcategorys= $('#subcategorys').val();
     var userName=     $('#userName').val();
     var userEmail=    $('#userEmail').val();
     var country_code=$("#userMobile").intlTelInput("getSelectedCountryData").dialCode;
     var is_agree=   $('#agree_checkbox').val();

     $.ajax({
       type: "POST",
       url: base_url+"user/login/check_otp",
       data:{  
         otp:otp,
         mobileno:userMobile,
         country_code:country_code,
         category:categorys,
         subcategory:subcategorys,
         name:userName,
         is_agree:is_agree,
         email:userEmail,
         csrf_token_name:csrf_token,

       }, 

       success: function (data) { 



         var data=jQuery.parseJSON(data);
         if(data.response=='ok')
         {
          window.location.href = base_url + 'provider-settings';
         }
         else if(data.response=='error')
         {
           $('#otp_error_msg').show();
           $('#otp_error_msg').text(data.msg);
           if(data.result=='otp_expired')
           {
             $('#registration_resend').show();
             $('#registration_final').addClass('invisible');

             $('#registration_resend').removeClass('invisible');


           }
         } 
       }
     });
     return false;
   });

   $('#registration_resend').on('click', function() {
     sendEvent('#modal-wizard', 3);
     $('#otp_error_msg').text('');
     $('#registration_submit').prop('disabled',false);
     $('#otp_number').val('');
     $('#registration_resend').addClass('invisible');
     $('#registration_final').removeClass('invisible');
   });


   $('#new_third_pagelogin').bootstrapValidator({

    fields: {
      userName: {
        validators: {
          notEmpty: {
            message: 'Please enter your service title'
          }
        }
      },
      userEmail: {
        validators: {
         remote: {
          url: base_url + 'user/login/email_chk',
          data: function(validator) {
            return {
              userEmail: validator.getFieldElements('userEmail').val(),
              csrf_token_name:csrf_token,
            };
          },
          message: 'This email is already exist',
          type: 'POST'
        },
        notEmpty: {
          message: 'Please enter email address'
        },


      }
    },
    userMobile: {
      validators: {

       remote: {
        url: base_url + 'user/login/mobileno_chk',
        data: function(validator) {
          return {
            userMobile: validator.getFieldElements('userMobile').val(),
            countryCode: validator.getFieldElements('countryCode').val(),
            csrf_token_name:csrf_token
          };
        },
        message: 'This mobile number is already exist',
        type: 'POST'
      },
      notEmpty: {
        message: 'Please enter mobile'
      },
      regexp: {
        regexp: /^\d{10}$/,
        message: 'Please supply a valid phone number'
      }
    }
  },

}
}).on('success.form.bv', function(e) {

 var categorys=    $('#categorys').val();
 var subcategorys= $('#subcategorys').val();
 var userName=     $('#userName').val();
 var userEmail=    $('#userEmail').val();
 var userMobile=   $('#userMobile').val();
 var countryCode=   $("#userMobile").intlTelInput("getSelectedCountryData").dialCode;

 $.ajax({
  type: "POST",
  url: base_url+"user/login/login",
  data:{  'category':categorys,
  'subcategory':subcategorys,
  'username':userName,
  'email':userEmail,
  'countryCode':countryCode,
  'csrf_token_name':csrf_token,
  'mobileno':userMobile
},
success: function (data) { 


  var obj = JSON.parse(data);


  if(obj.response=='ok')
  { 
    sendEvent('#modal-wizard', 4);
  }
  else
  {
    $('#registration_submit').prop("disabled", false); 
  }
}
} );
 return false;

});


$('#new_third_page1').bootstrapValidator({
  fields: {
    userName: {
      validators: {
        notEmpty: {
          message: 'Please Enter your service title'
        }
      }
    },
    userEmail: {
      validators: {
       remote: {
        url: base_url + 'user/login/email_chk',
        data: function(validator) {
          return {

            userEmail: validator.getFieldElements('userEmail').val(),
            csrf_token_name:csrf_token
          };
        },
        message: 'This email is already exist',
        type: 'POST'
      },
      notEmpty: {
        message: 'Please enter email address'
      },


    }
  },
  userMobile: {
    validators: {

     remote: {
      url: base_url + 'user/login/mobileno_chk',
      data: function(validator) {
        return {
          userMobile: validator.getFieldElements('userMobile').val(),
          csrf_token_name:csrf_token,
          countryCode: validator.getFieldElements('countryCode').val()
        };
      },
      message: 'This Mobile Number is already exist so Try Another Mobile No..!',
      type: 'POST'
    },
    notEmpty: {
      message: 'Please enter mobile No ...!'
    },
    regexp: {
      regexp: /^\d{10}$/,
      message: 'Please supply a valid Phone Number'
    }
  }
},

}
}).on('success.form.bv', function(e) {


 var userName=     $('#userName').val();
 var userEmail=    $('#userEmail').val();
 var userMobile=   $('#userMobile').val();
 var countryCode=   $("#userMobile").intlTelInput("getSelectedCountryData").dialCode;

 $.ajax({
  type: "POST",
  url: base_url+"user/login/send_otp_request",
  data:{  
    'username':userName,
    'email':userEmail,
    'countryCode':countryCode,
    'mobileno':userMobile,
    'csrf_token_name':csrf_token
  },
  success: function (data) { 


    var obj = JSON.parse(data);


    if(obj.response=='ok')
    { 
      sendEvent('#modal-wizard1', 2);
    }
    else
    {
      $('#registration_submit').prop("disabled", false); 
    }
  }
} );
 return false;

});
$('#booking_date').datepicker({
  dateFormat: 'dd-mm-yy',
  minDate: new Date(),  
  icons: {
    up: "fas fa-angle-up",
    down: "fas fa-angle-down",
    next: 'fas fa-angle-right',
    previous: 'fas fa-angle-left'
  }, onSelect: function(dateText) {
    var date = dateText;
    var dataString="date="+date;  
    var provider_id = $("#provider_id").val(); 
    var service_id = $("#service_id").val();
    $('#from_time').empty();
    $('#book_services').bootstrapValidator('revalidateField', 'booking_date');
   
    if(date!="" && date!=undefined){
      
      $.ajax({    
        url: base_url+"user/service/service_availability/",
        data : {date:date,provider_id:provider_id, service_id:service_id,csrf_token_name:csrf_token},
        type: "POST",

        success: function(response){      
          $('#from_time').find("option:eq(0)").html("Select time slot");
          if(response!=''){
            var obj=jQuery.parseJSON(response);   

            if(obj != '')
            {        
              $(obj).each(function(){
                var option = $('<option />');
                option.attr('value', this.start_time+ '-' +this.end_time).text(this.start_time+ '-' +this.end_time);           
                $('#from_time').append(option);
              });
            }
            else if(obj == '')
            {
              var msg = 'Availability not found';
              $('#from_time').append(msg);
            } 

            $('#to_time').find("option:eq(0)").html("Select end time");
            var obj=jQuery.parseJSON(response);   


            $(obj).each(function(){
              var option = $('<option />');
              option.attr('value', this.end_time).text(this.end_time);           
              $('#to_time').append(option);
            }); 
          }
        }

      });
    }
  }

});
$('.close').on('click', function() {
 $(".user_mobile").val('');
 $(".countryCode").val('');
})
$('#order-summary').DataTable();
//
if($('#user_type').val() != '' && $('#user_type').val() != undefined && $('#user_type').val() == 'user' ){
	function cartlist()
	{ 
	  //get 
	  $.getJSON(BASE_URL+"user/products/getcartlist", function(result){
		$(".cart_count").text(result.totalcnt);
	  });
	}
	setInterval(cartlist,3000);
}


if($('.days_check').is(':checked') == true){

 $('.eachdays').removeAttr('style');
 $('.eachdayfromtime').removeAttr('style');
 $('.eachdaytotime').removeAttr('style');

 if($('.daysfromtime_check').val()==''){
   $('.daysfromtime_check').attr('style','border-color:red');
   error = 1;
 }else{
   $('.daysfromtime_check').removeAttr('style');
 }
 if($('.daystotime_check').val()==''){
   error = 1;
   $('.daystotime_check').attr('style','border-color:red');
   
 }else{
   $('.daystotime_check').removeAttr('style');
 }

}else{
 var oneday = 0;
 $('.daysfromtime_check').removeAttr('style');
 $('.daystotime_check').removeAttr('style');

 $('.eachdays').each(function(){
   if($(this).is(':checked') == true){
    oneday = 1;
  }
});
 if(oneday == 1){
   $('.eachdays').removeAttr('style');
   $('.eachdayfromtime').removeAttr('style');
   $('.eachdaytotime').removeAttr('style');
 }

 $('.eachdays').each(function(){

   if($(this).is(':checked') == true){


     var val = $(this).val();
     val = parseInt(val);

     if($('.eachdayfromtime'+val).val() ==''){
       error = 1;

       $('.eachdayfromtime'+val).attr('style','border-color:red');
     }else{
       $('.eachdayfromtime'+val).removeAttr('style');
     }

     if($('.eachdaytotime'+val).val() ==''){
       error = 1;
       $('.eachdaytotime'+val).attr('style','border-color:red');
     }else{
       $('.eachdaytotime'+val).removeAttr('style');
     }

   }
   
 });
 if(oneday == 0){
   $('.eachdays').attr('style','opacity:unset;position:unset;');
   $('.eachdayfromtime').attr('style','border-color:red');
   $('.eachdaytotime').attr('style','border-color:red');
   var error = 1;
 }else{
 }

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

 $('.daysfromtime_check').val(from_time);
 $('.daystotime_check').val(to_time);
 $('.daysfromtime_check').removeAttr('style');
 $('.daystotime_check').removeAttr('style');
}

});



$("#loginsubmit").on("click", function(){
 $("#userSignIn").submit();
});

$('#userSignIn').bootstrapValidator({
 fields: {
   user_mobile:           {
    validators: {
      digits: {
        message: 'Please enter valid Number'
      },
      notEmpty: {
        message: 'Please enter your mobile number'
      }
    }
  }
}
}).on('success.form.bv', function(e) {
  var country_code = $('#direct_log_country_code').val();
  var mobile = $('#direct_log_mobile_no').val();
  $.ajax({
    type:'POST',
    url: base_url+'user/login/login',
    data : {mobile:mobile,country_code:country_code,csrf_token_name:csrf_token},
    success:function(response)
    {
      if(response==1)
      {
        window.location.reload();
      }
      else if(response==2)
      {
        window.location.reload();
      }
      else {
        $("#flash_error_message1").show();
        $('#flash_error_message1').append('Wrong Credentials');

        return false;
      }
    }
  });
  return false;
});  


$("#user_submit").on("click", function(){
 $("#reg_user").submit();
});

$('#reg_user').bootstrapValidator({
 fields: {
   userName:   {
     validators:          {
       notEmpty:              {
         message: 'Please enter your Username'
       }
     }
   },
   userEmail:           {
     validators:           {
       notEmpty:               {
         message: 'Please enter your email'
       }
     }
   },
   userMobile:           {
     validators:           {
       notEmpty:               {
         message: 'Please enter your mobile number'
       }
     }
   },
   countryCode:           {
     validators:           {
       notEmpty:               {
         message: 'Please select your countryCode'
       }
     }
   }
 }
}).on('success.form.bv', function(e) {

  var userName = $('#user_Name').val();
  var userEmail = $('#user_Email').val();
  var userMobile = $('#user_Mobile').val();
  var country_code = $("#user_mobile").intlTelInput("getSelectedCountryData").dialCode;
  $.ajax({
    type:'POST',
    url: base_url+'user/login/insert_user',
    data : {username:userName,email:userEmail,mobile:userMobile,country_code:country_code,csrf_token_name:csrf_token},
    success:function(response)
    {
      if(response==1)
      {

        $("#flash_succ_message").show(1000);
        $("#flash_error_message").hide();
        $('#flash_succ_message').append('Registered Successfully');
      }

      else {
        $("#flash_succ_message").hide();
        $("#flash_error_message").show(1000);
        $('#flash_error_message').append('Email id or mobileno already exists');

        return false;
      }
    }
  });
  return false;
});  


$('.rates').on('click', function() {
  $("#myInput").val($("input[name='rates']:checked").val());
})
$('.myReview').on('click', function() {
  $('#booking_id').val('');
  $('#provider_id').val('');
  $('#user_id').val('');
  $('#service_id').val('');
  var booking_id = $(this).attr("data-id");
  var provider_id = $(this).attr("data-providerid");
  var user_id = $(this).attr("data-userid");
  var service_id = $(this).attr("data-serviceid");

  $("#booking_id").val(function() {
    return this.value + booking_id;
  });
  $("#provider_id").val(function() {
    return this.value + provider_id;
  });
  $("#user_id").val(function() {
    return this.value + user_id;
  });
  $("#service_id").val(function() {
    return this.value + service_id;
  });



});


$('.myCancel').on('click', function() { 
  $('#cancel_review').val('');
  $('#booking_id').val('');
  $('#provider_id').val('');
  $('#user_id').val('');
  $('#service_id').val('');
  var booking_id = $(this).attr("data-id");
  var provider_id = $(this).attr("data-providerid");
  var user_id = $(this).attr("data-userid");
  var service_id = $(this).attr("data-serviceid");

  $("#cancel_booking_id").val(function() {
    return this.value + booking_id;
  });
  $("#cancel_provider_id").val(function() {
    return this.value + provider_id;
  });
  $("#cancel_user_id").val(function() {
    return this.value + user_id;
  });
  $("#cancel_service_id").val(function() {
    return this.value + service_id;
  });
});


var timeout = 3000; // in miliseconds (3*1000)
$('#flash_succ_message').delay(timeout).fadeOut(500);
$('#flash_error_message').delay(timeout).fadeOut(500);



var rating = '';
var review = '';
var booking_id = '';
var provider_id = '';
var user_id = '';
var service_id = '';
var type = '';

if(modules=="home"){
 $( ".common_search" ).autocomplete({
  source: "<?php echo site_url('home/get_common_search_value/?');?>"
});
}

function date_handler(e){

  var date = e.target.value;
  var dataString="date="+date;  
  var provider_id = $("#provider_id").val(); 
  var service_id = $("#service_id").val(); 



  $.ajax({    
   url: base_url+"user/service/service_availability/",
   data : {date:date,provider_id:provider_id, service_id:service_id,csrf_token_name:csrf_token},
   type: "POST",

   success: function(response){      
     $('#from_time').find("option:eq(0)").html("Select time slot");
     var obj=jQuery.parseJSON(response);   


     if(obj != '')
     {        
       $(obj).each(function(){
         var option = $('<option />');
         option.attr('value', this.start_time+ '-' +this.end_time).text(this.start_time+ '-' +this.end_time);           
         $('#from_time').append(option);
       });
     }
     else if(obj == '')
     {
       var msg = 'Availability not found';
       $('#from_time').append(msg);
     } 

     $('#to_time').find("option:eq(0)").html("Select end time");
     var obj=jQuery.parseJSON(response);   

     $(obj).each(function(){
       var option = $('<option />');
       option.attr('value', this.end_time).text(this.end_time);           
       $('#to_time').append(option);
     }); 
   }
   
 });

}


function re_send_otp_user(){
 var mobile_no=($('.user_final_no').val());
 var country_code=($('.final_country_code').val());


 $.ajax({

   url: base_url+"user/login/re_send_otp_user",
   data: {'mobile_no':mobile_no,'country_code':country_code,'csrf_token_name':csrf_token},
   type: 'POST',
   dataType: 'JSON',
   success: function(response){

     if(response==2 ){
       swal({
         title: "OTP Send !",
         text: "Some Things Went To Wrong....!",
         icon: "danger",
         button: "okay",
         closeOnEsc: false,
         closeOnClickOutside: false
       });
       location.reload();
     }else{
      swal({
       title: "OTP Send !",
       text: "Your OTP Send to Registered Mobile No.....",
       icon: "success",
       button: "okay",
       closeOnEsc: false,
       closeOnClickOutside: false
     });
    }

  }
})
}

function plan_notification(){

 swal({
   title: " Plan warning..!",
   text: "Already buyed high range so choose higher plan....!",
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 });
}

function re_send_otp_provider(){
 var mobile_no=($('.provider_final_no').val());
 var country_code=($('.final_provider_c_code').val());


 $.ajax({

   url: base_url+"user/login/re_send_otp_provider",
   data: {'mobile_no':mobile_no,'country_code':country_code,'csrf_token_name':csrf_token},
   type: 'POST',
   dataType: 'JSON',
   success: function(response){
     if(response==2){
      swal({
       title: "OTP Send !",
       text: "Some Things Went To Wrong....!",
       icon: "error",
       button: "okay",
       closeOnEsc: false,
       closeOnClickOutside: false
     });
      location.reload();
    }else{
     swal({
       title: "OTP Send !",
       text: "Your OTP Send to Registered Mobile No.....",
       icon: "success",
       button: "okay",
       closeOnEsc: false,
       closeOnClickOutside: false
     });
   }

 }

})
}





function withdraw_wallet_value(input){
  $("#wallet_withdraw_amt").val(input);
}   
function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}  


function add_wallet_money(){
 swal({
   title: "Insufficient wallet amount !",
   text: "Please recharge your wallet after book this service....!",
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function() {
  window.location = base_url+'user-wallet';
});
}


function user_update_status(e){
 var user_status=$(e).val();
 if(user_status==5){
  $("#reason_div").show();
}else{
  $("#reason_div").hide();
}
}

function check_user_reason(){
  var sent=true;
  var status=$(".update_user_status").val();
  var reason=$("#reject_reason").val();
  if(status==5){

    if(reason ==''){
     swal({
       title: "Rejection reason.",
       text: "Please Enter Rejection Reason about this Service...",
       icon: "error",
       button: "okay",
       closeOnEsc: false,
       closeOnClickOutside: false
     }).then(function(){
      $("#reject_reason").focus();
    });

     sent=false;

   }

 }
 return sent;
}

//LOGIN

function get_admin_approval() {
    swal({
        title: "Please Wait For Admin Approval !",
        text: "Waiting For Admin Approval.....",
        icon: "error",
        button: "okay",
        closeOnEsc: false,
        closeOnClickOutside: false
    }).then(function() {
        window.location.href = base_url + 'provider-subscription';
    });
}
function get_pro_subscription(tit,txt){
 swal({
   title:tit,
   text:txt,
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function(){
  window.location.href = base_url+'provider-subscription';
});
}

function get_pro_availabilty(tit,txt){
 swal({
   title:tit,
   text:txt,
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function(){
  window.location.href = base_url+'provider-availability';
});
}

function get_pro_addshop(tit,txt){
 swal({
   title:tit,
   text:txt,
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function(){
  window.location.href = base_url+'shop';
});
}

function get_pro_addstaff(tit,txt){
 swal({
   title:tit,
   text:txt,
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function(){
  window.location.href = base_url+'add-staff';
});
}

function get_pro_account(){
 swal({
   title: "Please Fill Account info !",
   text: "Please Fill Your Account Information for Feature Upgradation.....",
   icon: "error",
   button: "okay",
   closeOnEsc: false,
   closeOnClickOutside: false
 }).then(function(){
  window.location.href = base_url+'provider-availability';
});
}

function  reason_modal(key){
	$('#cancelModal').modal('show');	
	var reason=$('#reason_'+key).val();
	$('.cancel_reason').text(reason);
}

//new


function rate_booking(e)
{


 rating = $("#myInput").val();
 review = $("#review").val();
 booking_id = $("#booking_id").val();
 provider_id = $("#provider_id").val();
 user_id = $("#user_id").val();
 service_id = $("#service_id").val();
 type = $("#type").val();


 if(rating == '')
 {
   $('.error_rating').show();
   return false;
 }
 else if(review == '')
 {
   $('.error_rating').hide();
   $('.error_review').show();
   return false;
 }
 else if(type == '')
 {
   $('.error_rating').hide();
   $('.error_review').hide();
   $('.error_type').show();
   return false;
 }



 $.ajax({

  url: base_url+'user/dashboard/rate_review_post/',
  data: {rating:rating,review:review,booking_id:booking_id,provider_id:provider_id,user_id:user_id,service_id:service_id,type:type,csrf_token_name:csrf_token},
  type: 'POST',
  dataType: 'JSON',
  success: function(response){ 
    swal({
      title: "Rating Updated..!",
      text: "Rating Updated SuccessFully..",
      icon: "success",
      button: "okay",
      closeOnEsc: false,
      closeOnClickOutside: false
    }).then(function(){
      window.location.href = base_url+'user-bookings';
    });
  },
  error: function(error){
    swal({
      title: "Rating Updated..!",
      text: "Rating Not Update..",
      icon: "error",
      button: "okay",
      closeOnEsc: false,
      closeOnClickOutside: false
    }).then(function(){
      window.location.href = base_url+'user-bookings';
    });

  }
});




}

function cancel_booking(e){
  review = $("#cancel_review").val();
  booking_id = $("#cancel_booking_id").val();
  provider_id = $("#cancel_provider_id").val();
  user_id = $("#cancel_user_id").val();
  service_id = $("#cancel_service_id").val();
  if(review == '')
  {
   $('.error_cancel').show();
   return false;
 }
 update_user_booking_status(booking_id,5,0,review);
} 
function provider_cancel_booking(e){
  review = $("#cancel_review").val();
  booking_id = $("#cancel_booking_id").val();
  provider_id = $("#cancel_provider_id").val();
  user_id = $("#cancel_user_id").val();
  service_id = $("#cancel_service_id").val();
  if(review == '')
  {
   $('.error_cancel').show();
   return false;
 }
 var user_type=$('#user_type').val();
 if(user_type=="provider" || user_type=="freelancer"){
  update_pro_cancel_booking_status(booking_id,7,0,review);
}else{
  update_user_cancel_booking_status(booking_id,5,0,review);
}

}


/*provider accept and reject scenarios*/

function update_pro_booking_status(bookid,status,rowid,category){

  $.confirm({
    title: 'Confirmations..!',
    content: 'Do you want continue on this process..',
    buttons: {
      confirm: function () {

       $.ajax({

         url: base_url+"update-bookingstatus",
         data: {'booking_id':bookid,'status':status,'csrf_token_name':csrf_token},
         type: 'POST',
         dataType: 'JSON',
         beforeSend: function(){
           $(".btn").removeAttr('onclick');    
           $(".btn").removeAttr('data-target');    
           $(".btn").removeAttr('href');    
         },
         success: function(response){
			 console.log(response);

                                if(response=='3'){ // session expiry
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
                                
                                if(response=='1'){ //not updated
                                  swal({
                                    title: "Updated the booking status !",
                                    text: "Service is Updated successfully...",
                                    icon: "success",
                                    button: "okay",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false
                                  }).then(function(){
                                   if(category==1){
                                     $('#update_pending_div'+rowid).hide();
                                     
                                   }
                                   if(category==2){
                                     $('#update_inprogress_div'+rowid).hide();
                                   }
                                   window.location.reload();
                                 });
                                }


                              }
                            })
     },cancel: function () {

     },
   }
 });
}


/*provider accept and reject scenarios*/

function update_pro_cancel_booking_status(bookid,status,rowid,review){

  $('#myCancel').modal('hide');

  $.ajax({

   url: base_url+"update-bookingstatus",
   data: {'booking_id':bookid,'status':status,'review':review,'csrf_token_name':csrf_token},
   type: 'POST',
   dataType: 'JSON',
   beforeSend: function(){
     $(".btn").removeAttr('onclick');    
     $(".btn").removeAttr('data-target');    
     $(".btn").removeAttr('href');    
   },
   success: function(response){

                                if(response=='3'){ // session expiry
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
                                
                                if(response=='1'){ //not updated
                                  swal({
                                    title: "Updated the booking status !",
                                    text: "Service is Updated successfully...",
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


/*user update the status*/

function update_user_booking_status(bookid,status,rowid,review){ 
  if(status==5 || status==7){
   $('#myCancel').modal('hide');
 }
 $.confirm({
  title: 'Confirmations..!',
  content: 'Do you want continue on this proccess..',
  buttons: {
    confirm: function () {
     $.ajax({
       url: base_url+"update-status-user",
       data: {'booking_id':bookid,'status':status,'review':review,'csrf_token_name':csrf_token},
       type: 'POST',
       dataType: 'JSON',
       success: function(response){

                                if(response=='3'){ // session expiry
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
                                
                                if(response=='1'){ //not updated
                                  swal({
                                    title: "Updated the booking status !",
                                    text: "Service is Updated successfully...",
                                    icon: "success",
                                    button: "okay",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false
                                  }).then(function(){
                                    $('#update_div'+rowid).hide();
                                    window.location.reload();	
                                  });
                                }


                              }
                            })
   },cancel: function () {

   },
 }
});
}

function update_user_cancel_booking_status(bookid,status,rowid,review){ 
  $('#myCancel').modal('hide');
  $.ajax({

   url: base_url+"update-status-user",
   data: {'booking_id':bookid,'status':status,'review':review,'csrf_token_name':csrf_token},
   type: 'POST',
   dataType: 'JSON',
   beforeSend: function(){
     button_loading();
   },
   success: function(response){
    button_unloading();
                                if(response=='3'){ // session expiry
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
                                
                                if(response=='1'){ //not updated
                                  swal({
                                    title: "Updated the booking status !",
                                    text: "Service is Updated successfully...",
                                    icon: "success",
                                    button: "okay",
                                    closeOnEsc: false,
                                    closeOnClickOutside: false
                                  }).then(function(){
                                    $('#update_div'+rowid).hide();
                                    window.location.reload();	
                                  });
                                }


                              }
                            });

}

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

function chat_clear_all(id){ 
  if(id!=''){
   $.ajax({
     type: "post",
     url: base_url+"home/clear_all_chat",
     data:{csrf_token_name: csrf_token,id:id}, 
     dataType:'json',
     success: function (data) {


       if(data.success){
        $('.chat-list li').remove();
        $('.chat-bg-yellow').text(0);
      }
    }

  });
 }
}



//location lat long
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
  }
}
function showPosition(position) {
  locations(position.coords.latitude,position.coords.longitude);
}
getLocation();
function locations(lat,lng){ 
	

 var geocoder = new google.maps.Geocoder;
 var latlng = new google.maps.LatLng(lat,lng);
 geocoder.geocode({'location': latlng}, function(results, status) {
	 
  if (status === 'OK') {
   if (results[3]) { 
    var location=results[3].formatted_address;

    $.ajax({
      type: "post",
      url: base_url+"home/current_location",
      data:{csrf_token_name: csrfHash,location:location,latitude:lat,longitude:lng}, 	
      dataType:'json',
      success: function (data) {
       if(data==2){
        if (results[5]) { 
         var location=results[5].formatted_address;
         $.ajax({
           type: "post",
           url: base_url+"home/current_location",
           data:{csrf_token_name: csrfHash,location:location,latitude:lat,longitude:lng}, 
           dataType:'json',
           success: function (data) {


           }

         });
       }
     }
   }

 });		
  }else{
    if (results[5]) { 
     var location=results[5].formatted_address;
     $.ajax({
       type: "post",
       url: base_url+"home/current_location",
       data:{csrf_token_name: csrfHash,location:location,latitude:lat,longitude:lng}, 
       dataType:'json',
       success: function (data) {


       }

     });
   }
 }

}
});
}
var modules=$('#modules_page').val(); 
if(modules=="services" || modules=="service"){

 var placeSearch, autocomplete;

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
    var key = $("#google_map_api").val();
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

}

if(modules=="home"){

  function search_service() {
    $('#search_service').submit();
  } 
  
}

function toaster_msg(status,msg){

  setTimeout(function () {
    Command: toastr[status](msg);

    toastr.options = {
     "closeButton": false,
     "debug": false,
     "newestOnTop": false,
     "progressBar": false,
     "positionClass": "toast-top-right",
     "preventDuplicates": false,
     "onclick": null,
     "showDuration": "3000",
     "hideDuration": "5000",
     "timeOut": "6000",
     "extendedTimeOut": "1000",
     "showEasing": "swing",
     "hideEasing": "linear",
     "showMethod": "fadeIn",
     "hideMethod": "fadeOut"
   }   
 }, 300);

  
}
function button_loading(){
 var $this = $('.btn');
 var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
 if ($this.html() !== loadingText) {
  $this.data('original-text', $this.html());
  $this.html(loadingText).prop('disabled','true').bind('click', false);
}
}
function button_unloading(){
 var $this = $('.btn');
 $this.html($this.data('original-text')).prop('disabled','false');
}
function getData(page){
  var status=$('#status').val();
  var pagination_page=$('#pagination_current_page').val();
  var target=$('#target').val();
  var csrf_token=$('#csrf_token').val();
	
  $.ajax({
    method: "POST",
    url: pagination_page+page,
    data: { page: page,csrf_token_name:csrf_token,status:status },

    success: function(data){
      $(target).html(data);
      $('.pagination ul li').removeClass('active');
      $('.page_nos_'+page).parent('li').addClass('active');
	  $('html, body').animate({scrollTop: jQuery("body").offset().top}, 0);
    }
  });
}

function getDataService(page){
  var status=$('#status').val();
  var pagination_page=$('#dataList #pagination_current_page').val();
  var target=$('#dataList #target').val();
  var csrf_token=$('#csrf_token').val();
  
  var price_range=$('#price_range').val();
   var min_price = $('#min_price').html();
   var max_price = $('#max_price').html();
   
   var sort_by=$('#sort_by').val();
   var common_search=$('#common_search').val();
   
   var categories = ''; var subcategories = '';
   categories=$('#categories').val();
   subcategories=$('#subcategories').val();
   
   var sub_subcategories=$('#sub_subcategories').val();
  
   
   
   var service_latitude=$('#service_latitude').val();
   var service_longitude=$('#service_longitude').val();
   var user_address=$('#service_location').val();
   if(user_address==''){
    var service_latitude='';
   var service_longitude='';
   }
	
  $.ajax({
    method: "POST",
    url: pagination_page+page,
    data: { page: page,csrf_token_name:csrf_token,status:status,min_price:min_price,max_price:max_price,sort_by:sort_by,common_search:common_search,categories:categories,subcategories:subcategories,service_latitude:service_latitude,service_longitude:service_longitude,csrf_token_name:csrf_token,user_address:user_address,sub_subcategories:sub_subcategories },

    success: function(data){
      $(target).html(data);
      $('.pagination ul li').removeClass('active');
      $('.page_nos_'+page).parent('li').addClass('active');
	  $('html, body').animate({scrollTop: jQuery("body").offset().top}, 0);

    }
  });
}
function getDataShop(page){
  var status=$('#status').val();
  var pagination_page=$('#dataListShop #pagination_current_page').val();
  var target=$('#dataListShop #target').val();
  var csrf_token=$('#csrf_token').val();
  
  var price_range=$('#price_range').val();
   var min_price = $('#min_price').html();
   var max_price = $('#max_price').html();
   
   var sort_by=$('#sort_by').val();
   var common_search=$('#common_search').val();
   
   var categories = ''; var subcategories = '';
   categories=$('#categories').val();
   subcategories=$('#subcategories').val();
   
   var sub_subcategories=$('#sub_subcategories').val();
   
    
   
   var service_latitude=$('#service_latitude').val();
   var service_longitude=$('#service_longitude').val();
   var user_address=$('#service_location').val();
   if(user_address==''){
    var service_latitude='';
   var service_longitude='';
   }
   
	
  $.ajax({
    method: "POST",
    url: pagination_page+page,
    data: { page: page,csrf_token_name:csrf_token,status:status,min_price:min_price,max_price:max_price,sort_by:sort_by,common_search:common_search,categories:categories,subcategories:subcategories,service_latitude:service_latitude,service_longitude:service_longitude,csrf_token_name:csrf_token,user_address:user_address,sub_subcategories:sub_subcategories},

    success: function(data){
      $(target).html(data);
      $('.pagination ul li').removeClass('active');
      $('.page_nos_'+page).parent('li').addClass('active');
	  $('html, body').animate({scrollTop: jQuery("body").offset().top}, 0);

    }
  });
}

function getService(page){  
 var pagination_page=$('#pagination_current_page').val();
 var target=$('#target').val();
 var price_range=$('#price_range').val();
 var sort_by=$('#sort_by').val();
 var common_search=$('#common_search').val();
 var categories=$('#categories').val();
 var service_latitude=$('#service_latitude').val();
 var service_longitude=$('#service_longitude').val();

 $.ajax({
  method: "POST",
  url: pagination_page+page,
  data: { page:page,price_range:price_range,sort_by:sort_by,common_search:common_search,categories:categories,service_latitude:service_latitude,service_longitude:service_longitude,csrf_token_name:csrf_token},

  success: function(data){

    var obj=jQuery.parseJSON(data);
    $('#service_count').html(obj.count);
    $(target).html(obj.service_details);
  }
});
}
});

//service auto-complete

 $(document).ready(function(){

    $("#search-blk").keyup(function(){
        var service_name = $(this).val();
        
        if(service_name != ""){

            $.ajax({
                url:  base_url +'home/ajaxSearch',
                type: 'post',
                data: {
                 service_title:service_name,
                 csrf_token_name:csrf_token
                 },
                dataType: 'json',
                success:function(response){

                    var len = JSON.parse(response.length);
                    $("#searchResult").empty();
                    for( var i = 0; i<len; i++){
                   
                        var id = response[i]['id'];
                        var name = response[i]['service_title'];

                        $("#searchResult").append("<li value='"+id+"'>"+name+"</li>");

                    }

                    // binding click event to li
                    $("#searchResult li").bind("click",function(){
                        setText(this);
                    });

                }
            });
        }

    });

});

// Set Text to search box and get details
function setText(element){

    var value = $(element).text();
    var userid = $(element).val();
      console.log(value);
    $("#search-blk").val(value);
    $("#searchResult").empty();
    
}

    $(document).ready(function(){
    var country_key = $('#country_code_key').val();
    $("#login_mobile, #user_mobile, #userMobile, #mobileno").intlTelInput({
        separateDialCode: true,
        nationalMode: true,
        initialCountry : country_key
    });
});

$(document).on('click', '#change_language', function() {
    var lang = $(this).attr('lang');
    var lang_tag = $(this).attr('lang_tag');
    change_language(lang, lang_tag);

});

function change_language(lang, lang_tag){
        var lg =  lang;
        var tag =  lang_tag;
        
        var csrf_token = $('#csrf_lang').val();
        
        
        $.post(
            base_url+'admin/language/change_language',
            {
                lg:lg,
                tag:tag,
                        csrf_token_name: csrf_token
            },
            function(res){
           location.reload();
        })    

    }
    
$(document).on('change', '#user_currency', function() {
    var currency = $(this).val();
    user_currency(currency);
});   

function user_currency(code){
            
      if(code!=""){
          
          var csrf_token = $('#csrf_lang').val();
        $.ajax({
           type:'POST',
           url: base_url+'ajax/add_user_currency',
           data :  {code:code,csrf_token_name: csrf_token},
           dataType:'json',
           success:function(response)
           {  
             if(response.success)
             {
                 
               location.reload();
           }
           else {
               
            location.reload();
        }
    }
});
    }
}
            $(document).on('shown.bs.modal', '.modal', function () {
                $('select.countryCode').val('966').trigger('change');
            });
            function user_select_city(city)
            {
                var csrf_token = $('#csrf_lang').val();
                $.ajax({
                    type:'POST',
                    url: '<?php echo base_url(); ?>ajax/user_select_city',
                    data :  {city:city,csrf_token_name: csrf_token},
                    dataType:'json',
                    success:function(response)
                    { 
                        if (response == 'success') 
                        {
                            window.location.href = '<?php echo base_url(); ?>search';
                        }
                        else
                        {
                            window.location.href = '<?php echo base_url(); ?>';
                        }
                    }
                });
            }

if(cookies_content == 1 && cookies_text!= '') {
    $(document).herbyCookie({
        btnText: "Accept",
        policyText: "Cookie policy",
        text: cookies_text,
        scroll: false,
        expireDays: 30,
        link: base_url+"cookie-policy"
    });
}

function submitDetailsForm()
{
  $('#previous').trigger('click'); 
  var n=0;  
  var branch_title = $('#branch_title').val();
  var about = $('#about').val();
  var branch_location = $('#service_location').val();
  var images = $('#images').val();
  var shopid = $("#shopid").val();
  if(shopid == ''){
    $(".business_select").text('Please Select Shop');
    n=1;    
  }else{
    $(".business_select").text('');
  }
  
  branch_title = $.trim(branch_title);
  if(branch_title.length == 0){
    $(".business_title").text('Please Enter Branch Title');
    n=1;    
  }else{
    $(".business_title").text('');
  }
  about = $.trim(about);
  if(about.length == 0){
    $(".description").text('Please Enter Description');
    n=1;
  }else{
    $(".description").text('');
  }
  branch_location = $.trim(branch_location);
  if(branch_location.length == 0){
    $(".location").text('Please Enter Branch Location');
    n=1;
  }else{
    $(".location").text('');
  }
  if(images == ''){
    $(".images").text('Please upload Images');
    n=1;
  }else{
    $(".images").text('');
  }
  var mobno = $("#mobileno").val(); 
    if (mobno.length == 0) {
        $(".sh_mobile").text('Please Enter Mobile Number');
        n = 1;
    } else {
        $(".sh_mobile").text('');
    }
  
    var email = $("#email").val();    
    if (email.length == 0) {
       $(".sh_mail").text('Please Enter Email Address');
        n = 1;
    } else {
    if (/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(email)){     
      $(".sh_mail").text('');
    } else {
      $(".sh_mail").text('Please Enter Valid Email Address');
      n = 1;
    }
    }
  var scity_id = $("#city_id").val();
    if (scity_id == '') {
        $(".cityerr").text('Please Select City');
        n = 1;
    } else {
        $(".cityerr").text('');
    }

    var pincode = $("#pincode").val();
    if (pincode == '') {
        $(".postalerr").text('Please Enter Pincode');
        n = 1;
    } else {
        $(".postalerr").text('');
    }

    var sstate_id = $("#state_id").val();
    if (sstate_id == '') {
        $(".staterr").text('Please Select State');
        n = 1;
    } else {
       $(".staterr").text('');
    }

    var scountry_id = $("#country_id").val();
    if (scountry_id == '') {
        $(".countryerr").text('Please Select Country');
        n = 1;
    } else {
        $(".countryerr").text('');
    }
  $("select.selectdrop").each(function() {
    var ids = $(this).attr("id");
    if ($.trim($("#"+ids).val()) == '') {
      n = 1;
      $('.dropdown button[data-id="'+ids+'"]').css({
        "border": "1px solid red"       
      });
    } else {
      $('.dropdown button[data-id="'+ids+'"]').css({
        "border": ""        
      });
    }
  });
  $('.shp_spldetail').each(function() {
    if ($.trim($(this).val()) == '') {
      n = 1;
      $(this).css({
        "border": "1px solid red"       
      });
    } else {
      $(this).css({
        "border": ""        
      });
    }
  });
  if (n == 0) {
        var daycount = 0;
        if (!$('.err_check').is(':checked')) {
            daycount++;
        }
        if (daycount != 0) {
            $('.daysfromtime_check').attr('style', 'border-color:red');
            $('.daystotime_check').attr('style', 'border-color:red');
            $('.eachdayfromtime').attr('style', 'border-color:red');
            $('.eachdaytotime').attr('style', 'border-color:red');
            return false;
        }
        if (!subCheckAvailable()) {
            return false;
        }
        return true;

    } else {
        return false;
    }
}
$('.err_check').on('change',function(){
    var alldays = 0;
  var ids = $(this).attr('data-id');
    if (this.checked) {
    if(ids == 0) {
      $('.daysfromtime_check').removeAttr('style');
      $('.daystotime_check').removeAttr('style');
    } else {
      if($('.eachdayfromtime' + ids).val != '') {
        $('.eachdayfromtime' + ids).attr('style', 'border-color:red');
      } else {
        $('.eachdayfromtime' + ids).removeAttr('style');
      }
      if($('.eachdaytotime' + ids).val != '') {
        $('.eachdaytotime' + ids).attr('style', 'border-color:red');
      } else {
        $('.eachdaytotime' + ids).removeAttr('style');
      }
    }
        alldays++;
    }
  if(!(this.checked)){
    $('.eachdayfromtime' + ids).val('');$('.eachdayfromtime' + ids).removeAttr('style');
        $('.eachdaytotime' + ids).val('');$('.eachdaytotime' + ids).removeAttr('style');
  }
    if (alldays > 0) {

    }
});
$('.eachdayfromtime').on('change', function() {
    var id = $(this).attr('data-id');
  $('.eachdayfromtime').removeAttr("style");
    $('.eachdayfromtime' + id).removeAttr("style");

});
$('.eachdaytotime').on('change', function() {
    var id = $(this).attr('data-id');
  $('.eachdaytotime').removeAttr("style");
    $('.eachdaytotime' + id).removeAttr("style");

});

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

function validatenumerics(key) {
   //getting key code of pressed key
   var keycode = (key.which) ? key.which : key.keyCode;
   //comparing pressed keycodes

   if (keycode > 31 && (keycode < 48 || keycode > 57)) {     
     return false;
   }
   else return true;
}

$(".branch-membership-info").on('click','.trash', function () {
  var trCount = $('#append tr.singlerow').length;
  if(trCount > 1){
    $(this).closest('.singlerow').remove();
  } else {
    alert("Please Provide Atleast One Service Details");
  }
  return false;
});

$(document).on('click', '.filter_invoice', function() {
    var type = $(this).attr('data-type');
    filter_invoice_provider(type);
});
$(document).on('click', '.user_filter_invoice', function() {
    var type = $(this).attr('data-type');
    filter_invoice(type);
});
function filter_invoice_provider(stype)
{
    var base_url=$('#base_url').val();
    var csrf_token=$('#csrf_token').val();
    var csrfName=$('#csrfName').val();
    var csrfHash=$('#csrfHash').val();
    if (stype == 'c') {
        $('.cf').val('');
    }
    var page_num = 0;
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();

    $.ajax({
        type: 'POST',
        url: base_url+'user/dashboard/ajaxproviderinvoice/'+page_num,
        data:'page='+page_num+'&from_date='+from_date+'&to_date='+to_date+'&csrf_token_name='+csrf_token,
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(html){
            $('#dataList').html(html);
            $('.loading').fadeOut("slow");
        }
    });
}

$(document).ready(function(){
    $('#form_submit').on("click", function(){ 
      chkbasicvalidation();
    });
    function chkbasicvalidation() {
    var j = 0;
    var com_reg_image  = $('#commercial_reg').val(); 
        var com_reg_val  = $('.com_reg_val').val(); 
        if(com_reg_image != ''){
      $("#errcommercial_image").html("");
        } else {
            if (com_reg_val == '')
            {
                $("#errcommercial_image").html("Required Field");
                j = 1;
            } else
            { 
        $("#errcommercial_image").html("");
            }
        }
    
    var accname = $("#account_holder_name").val();
    var accnumb = $("#account_number").val();
    var acciban = $("#account_iban").val();
    
    if($.trim(accname) == ''){ 
      $("#account_holder_name").attr("style","border-color:red");
      j = 1;
    } else{
      $("#account_holder_name").removeAttr("style");
    }
    if(accnumb == ''){
      $("#account_number").attr("style","border-color:red");
      j = 1;
    } else{
      $("#account_number").removeAttr("style");
    }
    if($.trim(acciban) == ''){
      $("#account_iban").attr("style","border-color:red");
      j = 1;
    } else{
      $("#account_iban").removeAttr("style");
    }

    if (j == 1)
        {
            return false;
        } else
        {
      $('#update_provider').submit();
            return true;
        }
  }
  });

  function filter_invoice(stype)
{
    var base_url=$('#base_url').val();
    var csrf_token=$('#csrf_token').val();
    var csrfName=$('#csrfName').val();
    var csrfHash=$('#csrfHash').val();
    if (stype == 'c') {
        $('.cf').val('');
    }
    var page_num = 0;
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();

    $.ajax({
        type: 'POST',
        url: base_url+'user/dashboard/ajaxuserinvoice/'+page_num,
        data:'page='+page_num+'&from_date='+from_date+'&to_date='+to_date+'&csrf_token_name='+csrf_token,
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(html){
            $('#dataList').html(html);
            $('.loading').fadeOut("slow");
        }
    });
}
$(document).on('click', '#export_invoice', function() {
    export_invoice();   
});

function export_invoice()
{
    var base_url=$('#base_url').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    if (from_date!='' && to_date!='') 
    {
        window.open(base_url+'user/dashboard/export_muliple_invoice/'+from_date+'/'+to_date, '_blank');
    } else {
        swal({
            title: "Failure..!",
            text: "Please select From Date & To Date",
            icon: "error",
            button: "okay",
            closeOnEsc: false,
            closeOnClickOutside: false
        }).then(function(){
            location.reload();
        });
    }
}



$('.number').keyup(function(e) {
    if (/\D/g.test(this.value)) {
        this.value = this.value.replace(/\D/g, '');
    }
});
 
function submitDetailsForm() {
  $('#previous').trigger('click'); 
  
    var n = 0;

    var name = $("#firstname").val();
    if (name.length == 0) {
        $("#firstname").attr('style', 'border-color:red');
        n = 1;
    } else {
        $("#firstname").removeAttr('style')
    }
    var mobno = $("#mobileno").val();
    if (mobno.length == 0) {
        $("#mobileno").attr('style', 'border-color:red!important');
    $("#mobileno").addClass("error_red");
        n = 1;
    } else {
        $("#mobileno").removeAttr('style');
    $("#mobileno").removeClass("error_red");
    }
  
    var email = $("#email").val();
    if (email.length == 0) {
        $("#email").attr('style', 'border-color:red!important');
    $("#email").addClass("error_red");
        n = 1;
    } else {
    if (/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(email)){     
      $("#email").removeAttr('style');
      $("#email").removeClass("error_red");
      $('#errexistmail').html("");
    } else {
      $("#email").attr('style', 'border-color:red!important');
      $("#email").addClass("error_red");
      $('#errexistmail').html("Enter valid Email ID");
      n = 1;
    }
    }
  
  
    var dob = $("#dob").val();
    if (dob.length == 0) {
        $("#dob").attr('style', 'border-color:red');
        n = 1;
    } else {
        $("#dob").removeAttr('style')
    }

    
  
  var checked = $(".servchkbox:checked").length;
  if(checked == 0){
    $(".servchkbox").siblings('label').addClass('error');
        n = 1;
  } else {
    $(".servchkbox").siblings('label').removeClass('error');
  }
    var sub_subcategory = $("#sub_subcategory").val();
    if (sub_subcategory == '') {
        $('.dropdown button[data-id="sub_subcategory"]').css({
      "border": "1px solid red"       
    });
        n = 1;
    } else {
        $('.dropdown button[data-id="sub_subcategory"]').css({
      "border": ""        
    });
    }
  var shop_id = $("#shop_id").val();
    if (shop_id == '') {
        $('.dropdown button[data-id="shop_id"]').css({
      "border": "1px solid red"       
    });
        n = 1;
    } else {
        $('.dropdown button[data-id="shop_id"]').css({
      "border": ""        
    });
    }
    var existsmail = $("#existsmail").val();
    if (existsmail > 0) {
        $("#email").attr('style', 'border-color:red');
        n = 1;
    } else {
        $("#email").removeAttr('style')
    }
  var existsno = $("#existsno").val();
    if (existsno > 0) {
        $("#mobileno").attr('style', 'border-color:red');
        n = 1;
    } else {
        $("#mobileno").removeAttr('style')
    } 
  var about = $("#about").val();
    if (about.length == 0) {
        $("#about").attr('style', 'border-color:red');
        n = 1;
    } else {
        $("#about").removeAttr('style')
    } 
  var athome = $('input[name="home_service_home"]:checked').val();
  if(athome == 2){
    if($("#selected_area").val() == ''){
      n = 1;
      $("#homeservice_err").removeClass("d-none");
    } else {
      $("#homeservice_err").addClass("d-none");
    }
  } else {
    $("#homeservice_err").addClass("d-none");
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
            return false;
        }
    
        if (!subCheckAvailable()) {
      $(".available_title").text('Please Select Day and Relevant From & To Time');
            return false;
        }
        return true;

    } else {
        return false;
    }


}
$('.err_check').on('change',function(){
    var alldays = 0;
  var ids = $(this).attr('data-id');
    if (this.checked) {
    if(ids == 0) {
      $('.daysfromtime_check').removeAttr('style');
      $('.daystotime_check').removeAttr('style');
    } else {
      if($('.eachdayfromtime' + ids).val != '') {
        $('.eachdayfromtime' + ids).attr('style', 'border-color:red');
      } else {
        $('.eachdayfromtime' + ids).removeAttr('style');
      }
      if($('.eachdaytotime' + ids).val != '') {
        $('.eachdaytotime' + ids).attr('style', 'border-color:red');
      } else {
        $('.eachdaytotime' + ids).removeAttr('style');
      }
    }
        alldays++;
    }
  if(!(this.checked)){
    $('.eachdayfromtime' + ids).val('');$('.eachdayfromtime' + ids).removeAttr('style');
        $('.eachdaytotime' + ids).val('');$('.eachdaytotime' + ids).removeAttr('style');
  }
    if (alldays > 0) {

    }
});
$('.eachdayfromtime').on('change', function() {
    var id = $(this).attr('data-id');
  $('.eachdayfromtime').removeAttr("style");
    $('.eachdayfromtime' + id).removeAttr("style");

});
$('.eachdaytotime').on('change', function() {
    var id = $(this).attr('data-id');
  $('.eachdaytotime').removeAttr("style");
    $('.eachdaytotime' + id).removeAttr("style");

});

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
$("#homeservicemap").hide();
$("#home_service_home").on('change', function () {
  $(".servchkbox").siblings('label').removeClass('error');
  if($("#shop_id").val() == ''){
    swal({
      title: "<?php echo $StffMsg; ?>",
      text: "<?php echo $SelShopMsp; ?>",
      icon: "error",
      button: "okay",
      closeOnEsc: false,
      closeOnClickOutside: false         
    }).then(function(){
      $("#home_service_home").prop('checked',false)
      $('#previous').trigger('click'); 
    }); 
    
  } else {
    if($(this).is(":checked")) {
      $("#homeservicemap").show();
      $("#homeservice_hint").removeClass("d-none");
    } else {
      $("#homeservicemap").hide();
      $("#homeservice_hint").addClass("d-none");
    }
  }
});

function Validate(){
   if(!validateForm()){
     $('html, body').animate({
      scrollTop: $("#allServices").offset().top
    }, 1000);
       return false;
   }else{
     document.getElementById('theForm').submit();
     return true;
   }
}
function validateForm()
{
    var c=document.getElementsByTagName('input');
    for (var i = 0; i<c.length; i++){
        if (c[i].type=='radio')
        {
            if (c[i].checked){return true}
        }
    }
    return false;
}

$('.delete_img').on('click',function(){
    var img_id=$(this).attr('data-img_id');
    delete_img(img_id);
});

function delete_img(img_id) {
    var csrf_token = $('#csrf_token').val();
    $('#service_img_'+img_id).remove();
    $.ajax({
        type: "POST",
        url: base_url+'user/service/delete_service_img',
        data:{img_id:img_id,csrf_token_name:csrf_token},                        
        success: function (data) {   
            //console.log(data); return false;    
        }
    });
}

$('.delete_account').on('click', function() {
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if(type=='provider') {
            $('#deleteProviderAccount').modal('toggle');
        } else {
            $('#deleteUserAccount').modal('toggle');
        }

        $(document).on('click','.delete_confirm',function(){
            var dataString="id="+id+"&csrf_token_name="+csrf_token+"&type="+type;
            var url = base_url+'delete-account';
            $.ajax({
                url:url,
                data: { id: id, csrf_token_name: csrf_token, type: type },
                type:"POST",
                beforeSend:function(){
                    if(type=='provider') {
                        $('#deleteProviderAccount').modal('toggle');
                    } else {
                        $('#deleteUserAccount').modal('toggle');
                    }
                },
                success: function(res){
                    if(res==1) {
                        window.location.reload();;
                    }else if(res==2){
                       window.location.reload();;
                    }
                }
            });
        });
        $(document).on('click','.delete_cancel',function(){
        });
    });

    $(document).on("click",".delete_comments",function() {
        var id = $(this).attr('data-id');
        delete_comments(id);
    });

    function delete_comments(id) {
        if(confirm("Are you sure you want to delete this Comment...!?")){
            $.post(base_url+'delete-comments',{comments_id:id,csrf_token_name:csrf_token},function(result){
                if(result) {
                    window.location.reload();
                }
            });
        }   
    }

})(jQuery);
