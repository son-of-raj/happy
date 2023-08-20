(function($) {
  "use strict";

  var base_url=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();
  var placeSearch, autocomplete;
  
  var country_id=$('#country_id_value').val();
  var state_id=$('#state_id_value').val();
  var city_id=$('#city_id_value').val();
  
  var amtxt = $("#shop_amtxtval").val();
  var pmtxt = $("#shop_pmtxtval").val();
  var seltxt = $("#sltxtval").val();

	$( document ).ready(function() {	
		
		country_changes(country_id);
		state_changes(state_id);
		$('#country_id').on('change',function(){
			var id=$(this).val();
			$('#country_id_value').val(id)
			country_changes(id);
		});
		$('#state_id').on('change',function(){
			var id=$(this).val();
			$('#state_id_value').val(id);
			state_changes(id);
		}); 
		
		$('.days_check').on('click', function(){
			if ($(".eachdays").prop('checked')==false){
				$('.eachdayfromtime[value=""]').attr('selected', 'selected');
				$('.eachdayfromtime').val("").trigger("change");
			}
		});
		
		$('.select_from_time').on('change',function(){
			var id=$(this).attr('data-id');
			select_from_time(id);
		}); 
		$('.validate_time').on('click',function(){
			var id=$(this).attr('data-id');
			validate_time(id);
		});

function submitDetailsForms() {
	$('#previous').trigger('click'); 
	var n=0;	
	var shop_title = $('#shop_title').val();
	var about = $('#about').val();
	var shop_location = $('#shop_location').val();
	var images = $('#images').val();
	shop_title = $.trim(shop_title);
	if(shop_title.length == 0){
		$(".business_title").text('Please Enter Shop Title');
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
	shop_location = $.trim(shop_location);
	if(shop_location.length == 0){
		$(".location").text('Please Select Shop Location');
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
	
	var tax = $('input[name="tax_allow"]:checked').val();
	if(tax == 1){
		var taxno = $("#tax_number").val();
		if (taxno == '') {
			$(".taxerr").text('Please Enter Tax Number');
			n = 1;
		} else {
			$(".taxerr").text('');
		}
	} else {
		$(".taxerr").text('');
	}
	
	var scity_id = $("#city_id").val();
    if (scity_id == '') {
        $(".cityerr").text('Please Select City');
        n = 1;
    } else {
        $(".cityerr").text('');
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
	
	var category = $("#category").val();
    if (category == '') {
        $('.dropdown button[data-id="category"]').css({
			"border": "1px solid red"				
		});
    } else {
        $('.dropdown button[data-id="category"]').css({
			"border": ""				
		});
    }
	var subcategory = $("#subcategory").val();
    if (subcategory == '') {
        $('.dropdown button[data-id="subcategory"]').css({
			"border": "1px solid red"				
		});
        n = 1;
    } else {
        $('.dropdown button[data-id="subcategory"]').css({
			"border": ""				
		});
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

		$('form[name="add_shops"]').submit(function(e){
	        e.preventDefault();
	        $('#previous').trigger('click'); 
			var n=0;	
			var shop_title = $('#shop_title').val();
			var about = $('#about').val();
			var shop_location = $('#shop_location').val();
			var images = $('#images').val();
			shop_title = $.trim(shop_title);
			if(shop_title.length == 0){
				$(".business_title").text('Please Enter Shop Title');
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
			shop_location = $.trim(shop_location);
			if(shop_location.length == 0){
				$(".location").text('Please Select Shop Location');
				n=1;
			}else{
				$(".location").text('');
			}
			var mobno = $("#mobileno").val();
			mobno = $.trim(mobno);
			var existsno = $("#exists_no").val();
		    if (mobno.length == 0) {
		        $(".sh_mobile").text('Please Enter Mobile Number');
		        n = 1;
		    } else {
		        $(".sh_mobile").text('');
		    }
			
		    var email = $("#email").val();
			var existsmail = $("#exists_mail").val();
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
			
			var category = $("#category").val();
		    if (category == '') {
		        $('.dropdown button[data-id="category"]').css({
					"border": "1px solid red"				
				});
		    } else {
		        $('.dropdown button[data-id="category"]').css({
					"border": ""				
				});
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
	    });
	});
	
$('.err_check').change(function() {
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
	
	
	function  country_changes(id) {
		var country_id=$('#country_id_value').val();
		var state_id=$('#state_id_value').val();
		var city_id=$('#city_id_value').val();
		if(id!=''){
			$.ajax({
				type: "POST",
				url: base_url+"user/service/get_state_details",
				data:{id:id,csrf_token_name:csrf_token}, 
				dataType:'json',
				beforeSend :function(){
					$('#state_id').find("option:eq(0)").html("Please wait..");
				}, 
				success: function (data) {
					$('#state_id option').remove();
					if(data!=''){
						var add='';
						add +='<option value="">Select State</option>';
						$(data).each(function( index,value ) {
							add +='<option value='+value.id+'>'+value.name+'</option>';
						});
						$('#state_id').append(add);
						if(state_id!=''){
							$('#state_id option[value='+state_id+']').attr('selected','selected');
						}
						
					}
				}
			});
		}
	}

	function  state_changes(id) { 
		var country_id=$('#country_id_value').val();
		var state_id=$('#state_id_value').val();
		var city_id=$('#city_id_value').val();
		if(id!=''){
			$.ajax({
				type: "POST",
				url: base_url+"user/service/get_city_details",
				data:{id:id,csrf_token_name:csrf_token}, 
				dataType:'json',
				beforeSend :function(){
					$('#city_id').find("option:eq(0)").html("Please wait..");
				}, 
				success: function (data) {
					$('#city_id option').remove();
					if(data!=''){
						var add='';
						add +='<option value="">Select City</option>';
						$(data).each(function( index,value ) {
							add +='<option value='+value.id+'>'+value.name+'</option>';
						});
						$('#city_id').append(add);
						if(city_id!=''){
							$('#city_id option[value='+city_id+']').attr('selected','selected');
						}
					}
				}
			});
		}
	}


$('.charonly').on('keypress', function(event) {
    var regex = new RegExp("^[a-zA-Z ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
});
$('.number').keyup(function(e) {
    if (/\D/g.test(this.value)) {
        this.value = this.value.replace(/\D/g, '');
    }
});



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

$('.shopmobile').on('keyup blur',function(){
      var mobile=$(this).val(); 
	  var c_code = $("#country_code").val();
	  var sid = $("#shop_id").val();
      shopcheck_mobile_exists(mobile,sid,c_code);
 });
function shopcheck_mobile_exists(mobile,sid,c_code){      
    if(mobile.length > 0){ 		
       $.ajax({
         type: "POST",
         url: base_url + 'user/shop/shop_check_mobile',
         data:{ 
           'userMobile':mobile,           
           'csrf_token_name':csrf_token,
		   'sid':sid,
		   'mobileCode':c_code
         },         
         success: function (res) { 
          var res=jQuery.parseJSON(res);		
          if(res.data > 0){
            $('#err_existno').html("Existing Contact No");			
            $('#shopsubmit').attr('disabled',true);
			$("#exists_no").val(res.data);
         }else{  
			if(mobile.length < 10){ 
				$('#err_existno').html("Enter Valid Contact No");				
				$('#shopsubmit').attr('disabled',true);
				$("#exists_no").val('1');
			} else {
				$('#err_existno').html('');      		 		  
				$('#shopsubmit').attr('disabled',false);
				$("#exists_no").val(res.data);
			}
        }

      }
	 });
    }  else {		
		$('#err_existno').html('Please Enter Mobile Number');      
	}		
 }
$('.shopemail').on('keyup blur',function(){
      var email=$(this).val(); 
	  var sid = $("#shop_id").val();
      shopcheck_email_exists(email,sid);
 });
function shopcheck_email_exists(email,sid){    
	email = $.trim(email);
    if(email.length > 0){ 
		
       $.ajax({
         type: "POST",
         url: base_url + 'user/shop/shop_check_emailid',
         data:{ 
           'userEmail':email,           
           'csrf_token_name':csrf_token,
		   'sid':sid
         },         
         success: function (res) { 
          var res=jQuery.parseJSON(res);		
          if(res.data > 0){
            $('#err_existmail').html("Existing Email ID");			
            $('#shopsubmit').attr('disabled',true);
			$("#exists_mail").val(res.data);
         }else{  
		  $('#err_existmail').html('');      		 	  
          $('#shopsubmit').attr('disabled',false);
		  $("#exists_mail").val(res.data);
        }

      }
	 })//ajax
    }  else {
		
		$('#err_existmail').html('Please Enter Email Address'); 
	}	
	
 }

$(document).on("click", ".nav-tabs a", function(){
	var tab = $(this).attr("aria-controls"); 
	activaTab(tab)
});
function activaTab(tab){	
	if(tab == 'basic') {
		$("#next").show();
		$('#previous').trigger('click'); 
	} else {
		$('#next').trigger('click'); 
	}
};
$('#next').on("click", function(){	
	$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
	$("#basic-tab").removeClass("active");
	$("#service-tab").addClass("active");
	$("#service-tab").parent("li").addClass("active");
	$("#basic").removeClass("active");
	$("#service").addClass("active");
	$("#service").addClass("show");	
	$("#next").hide();
    scrolllTop();	
});
$('#previous').on("click", function(){	
	$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
	$("#service-tab").removeClass("active");
	$("#basic-tab").addClass("active");
	$("#basic-tab").parent("li").addClass("active");
	$("#service").removeClass("active");
	$("#basic").addClass("active");
	$("#basic").addClass("show");
	$("#next").show();
	scrolllTop();
});
function scrolllTop(){
	$('html, body').animate({
		scrollTop: jQuery("body").offset().top
	}, 100);
}	

 /* Image Upload */

      if($('#add_shop, #update_shop').length > 0 ){

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
			var ul = document.createElement('ul');
			ul.className = ("upload-wrap");
			ul.id = "imgList";
			document.getElementById('uploadPreview').innerHTML = '';
          var li = document.createElement('li');
          ul.appendChild(li);
          li.innerHTML = ['<div class=" upload-images"> ' +
          '<a style="display:none;" href="javascript:void(0);" class="file_close btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a><img class="thumb" src="', e.target.result, '" title="', escape(readerEvt.name), '" data-id="',
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
	  
/* Image Upload */	  

})(jQuery);

	initMap();	initialize();
	
    var placeSearch, autocomplete, user_addr;
	
	function initMap() {
		var key = $('#google_map_api').val();
		var address = $('#shop_location').val();
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
             $('#shop_location').val(address);
             $('#shop_latitude').val(latitude);
             $('#shop_longitude').val(longitude);
             
        });
      });      
    }
   
	
    function initialize() {
      autocomplete = new google.maps.places.Autocomplete(
      (document.getElementById('shop_location')), {
          types: ['geocode']
      });
      google.maps.event.addDomListener(document.getElementById('shop_location'), 'focus', geolocate);
      google.maps.event.addDomListener(autocomplete, 'place_changed', initMap);
    }
    
    function load_msp_details(latitude, longitude) {
      var uluru = {
          lat: parseFloat(latitude),
          lng: parseFloat(longitude)
      };
      var geocoder = new google.maps.Geocoder();
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
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
                      $('#shop_location').val(address);
                      $('#shop_latitude').val(latitude);
                      $('#shop_longitude').val(longitude);
                  }
              }
          });
      });
    }
    function geolocate() {
        var key = $('#google_map_api').val();        
        var lat = $('#shop_latitude').val();
        var lng = $('#shop_longitude').val();
        var latLng = lat+', '+lng;
        var adrs = '';
		var language_option = $("#language_option").val();
        $.get('https://maps.googleapis.com/maps/api/geocode/json',{latlng:latLng,key:key,language:language_option},function(data, status){
            $('#shop_location').val(data.results[0].formatted_address);
            $('#shop_latitude').val(lat);
            $('#shop_longitude').val(lng);
        });     
    }
