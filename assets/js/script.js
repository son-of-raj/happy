(function($) {
	"use strict";
	var current_page=$('#current_page').val();
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	var modules=$('#modules_page').val();
	var sticky_header =$('#sticky_header').val();
	
	if($(window).width() > 767) {
		if($('.theiaStickySidebar').length>0) {
			$('.theiaStickySidebar').theiaStickySidebar({additionalMarginTop:100});
		}
	}

	$(window).on('scroll', function(){
		if ( $(window).scrollTop() > 100 ) {
			if(sticky_header == 1) {
				$('.sticktop').addClass('navbar-fixed');
			}
		} else {
			$('.sticktop').removeClass('navbar-fixed');
		}
	});	
	
	// Sidebar
	
	if($(window).width() <= 991) {
		var Sidemenu = function() {
			this.$menuItem = $('.main-nav a');
		};

		function init() {
			var $this = Sidemenu;
			$('.main-nav a').on('click', function(e) {
				if($(this).parent().hasClass('has-submenu')) {
					e.preventDefault();
				}
				if(!$(this).hasClass('submenu')) {
					$('ul', $(this).parents('ul:first')).slideUp(350);
					$('a', $(this).parents('ul:first')).removeClass('submenu');
					$(this).next('ul').slideDown(350);
					$(this).addClass('submenu');
				} else if($(this).hasClass('submenu')) {
					$(this).removeClass('submenu');
					$(this).next('ul').slideUp(350);
				}
			});
		}

	// Sidebar Initiate
	init();
	}

	// Mobile menu sidebar overlay
	
	$('body').append('<div class="sidebar-overlay"></div>');
	$(document).on('click', '#mobile_btn', function() {
		$('main-wrapper').toggleClass('slide-nav');
		$('.sidebar-overlay').toggleClass('opened');
		$('html').addClass('menu-opened');
		$('.header').removeClass('navbar-fixed');
		return false;
	});
	
	$(document).on('click', '.sidebar-overlay', function() {
		$('html').removeClass('menu-opened');
		$(this).removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});
	
	$(document).on('click', '#menu_close', function() {
		$('html').removeClass('menu-opened');
		$('.sidebar-overlay').removeClass('opened');
		$('main-wrapper').removeClass('slide-nav');
	});
	
	$(window).on('scroll', function(){
		if ( $(window).scrollTop() > 100 ) {
			if(sticky_header == 1) {
				$('.sticktop').addClass('navbar-fixed');
			}
		} else {
			$('.sticktop').removeClass('navbar-fixed');
		}
	});	
	
	// Select 2
	
	if($('.select').length > 0) {
		$('.select').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	}
	
	// Content div min height set
	
	function resizeInnerDiv() {
		var height = $(window).height();	
		var header_height = $(".header").height();
		var footer_height = $(".footer").height();
		var breadcrumb_height = $(".breadcrumb-bar").height();
		var setheight = height - header_height;
		var trueheight = setheight - footer_height;
		var trueheight2 = trueheight - breadcrumb_height;
		$(".content").css("min-height", trueheight2);
	}
	
	if($('.content').length > 0 ){
		resizeInnerDiv();
	}

	$(window).resize(function(){
		if($('.content').length > 0 ){
			resizeInnerDiv();
		}
	});
	
	// Owl Carousel
	
	if($('#category-slider').length > 0 ){
		var owl = $('#category-slider');
			owl.owlCarousel({
			margin: 20,
			dots: true,
			nav: false,
			responsive: {
				0: {
					items: 1
				},
				768 : {
					items: 2
				},
				1170: {
					items: 6
				}
			}
		});
	}
	
	if($('.sliders-shops').length > 0 ){
		var owl = $('.sliders-shops');
			owl.owlCarousel({
			margin: 30,
			dots : true,
			nav: false,
			responsive: {
				0: {
					items: 1
				},
				768 : {
					items: 2
				},
				1170: {
					items: 4
				}
			}
		});
	}

	if($('.sliders-related').length > 0 ){
		var owl = $('.sliders-related');
			owl.owlCarousel({
			margin: 30,
			dots : true,
			nav: false,
			responsive: {
				0: {
					items: 1
				},
				768 : {
					items: 2
				},
				1170: {
					items: 3
				}
			}
		});
	}
	
	if($('.images-carousel').length > 0 ){
		$('.images-carousel').owlCarousel({
			center: true,
			margin: 30,
			responsiveClass: true,
			responsive: {
				0: {
					items: 1
				},
				768: {
					items: 1
				},
				1170: {
					items: 1,
				}
			}
		})
	}
	
	// Slick Slider
	
	if ($('.products-slider').length > 0) {
	 $('.products-slider').slick({
		  slidesToShow: 1,
		  slidesToScroll: 1,
		  arrows: false,
		  fade: true,
		  asNavFor: '.product-slider-nav'
		});
	}
	
	if ($('.product-slider-nav').length > 0) {
		$('.product-slider-nav').slick({
		  slidesToShow: 4,
		  slidesToScroll: 1,
		  asNavFor: '.products-slider',
		  dots: false,
		  arrows:false,
		  centerMode: false,
		  variableWidth: false,
		  focusOnSelect: true
		});
	}
	

	
	// Chat

	var chatAppTarget = $('.chat-window');
	(function() {
		if ($(window).width() > 991)
			chatAppTarget.removeClass('chat-slide');
		
		$(document).on("click",".chat-window .chat-users-list a.media",function () {
			if ($(window).width() <= 991) {
				chatAppTarget.addClass('chat-slide');
			}
			return false;
		});
		$(document).on("click","#back_user_list",function () {
			if ($(window).width() <= 991) {
				chatAppTarget.removeClass('chat-slide');
			}	
			return false;
		});
	})();
	
	
	// Image Upload
	
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").on('change',function(){
        readURL(this);
    });
	
	// Range slider
	
	if(document.getElementById("myRange")!=null){
		var slider = document.getElementById("myRange");
		var output = document.getElementById("currency");
		output.innerHTML = slider.value;
		
		slider.oninput = function() {
			output.innerHTML = this.value;
		}
	}

	// Feather Icon
	
	if($('data-feather').length > 0) {
		feather.replace();
	}
	
	// Filter Toggle

	$(document).on('click', '#filter_search', function() {
		$('#filter_inputs').slideToggle("slow");
	});
	
	 // Additional Service Add More
	 
	 function chknum(){
		$('.onlynumber').keyup(function(e) {
			if (/\D/g.test(this.value)) {
				this.value = this.value.replace(/\D/g, '');
			}
		});
	}
	
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
    	'<div class="input-group">	' +									 
		'<input type="text" class="form-control addicls onlynumber" name="addi_servicedura[]" id="addi_dura" />' +
		  '<div class="input-group-append">' +
			'<span class="input-group-text" id="basic-addon2">'+durintxt+'</span>' +
		  '</div>			' +						  
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

    $('.datetimepicker').datetimepicker({
    	format: 'DD-MM-YYYY',
    	minDate: new Date(),

    	icons: {
    		up: "fas fa-angle-up",
    		down: "fas fa-angle-down",
    		next: 'fas fa-angle-right',
    		previous: 'fas fa-angle-left'

    	}

    });

    $('.datepicker').datepicker({
    	dateFormat: 'dd-mm-yy',
    	minDate: new Date(),	
    	icons: {
    		up: "fas fa-angle-up",
    		down: "fas fa-angle-down",
    		next: 'fas fa-angle-right',
    		previous: 'fas fa-angle-left'
    	}, onSelect: function(dateText) {
    	}

    });
    $('.datepicker_report').datepicker({
    	dateFormat: 'dd-mm-yy',	
    	icons: {
    		up: "fas fa-angle-up",
    		down: "fas fa-angle-down",
    		next: 'fas fa-angle-right',
    		previous: 'fas fa-angle-left'
    	}, onSelect: function(dateText) {
    	}

    });

    $('.datepicker').on('change', function(e){
    	$(e.target).valid();
    });
	
	
	
   $('.datetimepicker-start, .datetimepicker-ms').datetimepicker({
		format: 'DD-MM-YYYY',
		minDate: new Date(),
		icons: {
			up: "fas fa-angle-up",
			down: "fas fa-angle-down",
			next: 'fas fa-angle-right',
			previous: 'fas fa-angle-left'
		}
   });
   $('.datetimepicker-end, .datetimepicker-me').datetimepicker({
	   format: 'DD-MM-YYYY',
		minDate: new Date(),
		icons: {
			up: "fas fa-angle-up",
			down: "fas fa-angle-down",
			next: 'fas fa-angle-right',
			previous: 'fas fa-angle-left'
		}		
   });
   $(".datetimepicker-start").on("dp.change", function (e) {
	   $('.datetimepicker-end').data("DateTimePicker").minDate(e.date);
   });
   $(".datetimepicker-end").on("dp.change", function (e) {
   });
   $(".datetimepicker-s").on("dp.change", function (e) {
	   $('.datetimepicker-e').data("DateTimePicker").minDate(e.date);
   });
   $(".datetimepicker-e").on("dp.change", function (e) {
   });
    
	
    $('#book_service').bootstrapValidator('revalidateField', 'booking_date');
	
	// Delete service
	if(current_page=="my-services"){
		var delete_title = "Inactive Service";
		var delete_msg = "Are you sure want to inactive this service?";
		var delete_text = "Your service has been Inactive";

	}
	if(current_page=="my-services-inactive"){
		var delete_title = "Delete Service";
		var delete_msg = "Are you sure want to delete this service?";
		var delete_text = "Your service has been deleted";
		var delete_active_title = "Active Service";
		var delete_active_msg = "Are you sure want to Active this service?";
		var delete_active_text = "Your service has been Actived";
	}
	if(current_page=="featured-services"){
		var delete_title = "Delete Service";
		var delete_msg = "Are you sure want to delete this service?";
		var delete_text = "Your service has been deleted";
	}
	$(document).on('click','.si-delete-service',function() {
		var s_id = $(this).attr("data-id");
		$('#deleteConfirmModal').modal('toggle');
		$('#acc_title').html('<span>'+delete_title+'</span>');
		$('#acc_msg').html(delete_msg);
		
		$(document).on('click','.si_accept_confirm',function(){
			var dataString="s_id="+s_id+"&csrf_token_name="+csrf_token;
			var url = base_url+'user/service/delete_service';
			$.ajax({
				url:url,
				data:{s_id:s_id,csrf_token_name:csrf_token},
				type:"POST",
				beforeSend:function(){
					$('#deleteConfirmModal').modal('toggle');
				},
				success: function(res){
					if(res==1) {
						window.location = base_url+'my-services';
					}else if(res==2){
						window.location = base_url+'my-services';
					}
				}
			});
		});
		$(document).on('click','.si_accept_cancel',function(){
		});
	});

	$(document).on('click','.si-delete-inactive-service',function() {
		var s_id = $(this).attr("data-id");
		$('#deleteConfirmModal').modal('toggle');
		$('#acc_title').html('<i>'+delete_title+'</i>');
		$('#acc_msg').html(delete_msg);
		 $('.del_reason_error').hide();
		$(document).on('click','#delete_reason',function(){
			var dataString="s_id="+s_id;
					var reason = $('#del_reason').val();
					 if (reason != '') {
                $('.del_reason_error').hide();
            } else if (reason == '') {
            	$('.del_reason_error').show();
            	 return false;
            }
			var url = base_url+'user/service/delete_inactive_service';
			$.ajax({
				url:url,
				data:{s_id:s_id,reason:reason,csrf_token_name:csrf_token},
				type:"POST",
				beforeSend:function(){
					$('#deleteConfirmModal').modal('toggle');
				},
				success: function(res){
					if(res==1) {
						window.location = base_url+'my-services-inactive';
					}else if(res==2){
						window.location = base_url+'my-services-inactive';
					}
				}
			});
		});
		$(document).on('click','.si_accept_cancel',function(){
		});
	});
	
	$(document).on('click','.si-delete-active-service',function() {
		var s_id = $(this).attr("data-id");
		$('#activeConfirmModal').modal('toggle');
		$('#acc_title').html('<i>'+delete_active_title+'</i>');
		$('#acc_msg').html(delete_active_msg);
		
		$(document).on('click','.si_accept_confirm',function(){
			var dataString="s_id="+s_id;
			var url    =  base_url+'user/service/delete_active_service';
			$.ajax({
				url:url,
				data:{s_id:s_id,csrf_token_name:csrf_token},
				type:"POST",
				beforeSend:function(){
					$('#activeConfirmModal').modal('toggle');
				},
				success: function(res){
					if(res==1) {
						window.location = base_url+'my-services-inactive';
					}else if(res==2){
						window.location = base_url+'my-services-inactive';
					}
				}
			});
		});
	});
	
	$(document).on('click','.set_default_image',function(){
		var id=$(this).val();
		var service_id = $('#service_id').val();
		var url = base_url+'user/service/set_default_image';
		$.ajax({
			url:url,
			data:{id:id,service_id:service_id,csrf_token_name:csrf_token},
			type:"POST",
			success: function(res){
				if(res == 1){
					swal({
						   title: "Service Image",
						   text: "Updated Successfully",
						   icon: "success",
						   button: "okay",
						   closeOnEsc: false,
						   closeOnClickOutside: false
						 }).then(function(){
						  location.reload();
						});
					
				}else{
					swal({
						   title: "Service Image",
						   text: "Error Occurs.. Try again...",
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
	});
	$(document).on('click','.deleteserviceimage',function() { 
		var trCount = $('#sergallery tr.imgrows').length;
		if(trCount > 1){	
			var id = $(this).attr("data-id");
			var delete_title = "Delete Service Image";
			var delete_msg = "Are you sure want to delete this service image?";
			var delete_text = "Your Service image has been deleted";
			$('#deleteServiceImageConfirmModal').modal('toggle');
			$('#acc_title').html('<span>'+delete_title+'</span>');
			$('#acc_msg').html(delete_msg);
			
			$(document).on('click','.serviceimg_delete_confirm',function(){			
				var dataString="user_id="+user_id+"&csrf_token_name="+csrf_token;
				var url = base_url+'user/service/delete_service_image';
				$.ajax({
					url:url,
					data:{id:id,csrf_token_name:csrf_token},
					type:"POST",
					beforeSend:function(){
						$('#deleteServiceImageConfirmModal').modal('toggle');
					},
					success: function(res){
						if(res==1) {
							swal({
							   title: "Service Image",
							   text: "Service Image Deleted Successfully",
							   icon: "success",
							   button: "okay",
							   closeOnEsc: false,
							   closeOnClickOutside: false
							 }).then(function(){
							  location.reload();
							});
							
						}else if(res==2){
							swal({
							   title: "Service Image",
							   text: "Error in Deletion",
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
			});
		} else {
			swal({
			   title: "Service Image",
			   text: "Please Provide Atleast One Service Image Details",
			   icon: "error",
			   button: "okay",
			   closeOnEsc: false,
			   closeOnClickOutside: false
			 }).then(function(){
				return false;
			});
		}
	});

	$(window).on('load',function(){
		$('.page-loading').fadeOut();
	});

	var url = base_url+'user/dashboard/checkproviderpwd';

	$('#update_user_pwd').bootstrapValidator({
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
	    url: base_url+'user/dashboard/update_provider_password',
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
      confirm_delete_subcription(id);
  	});
  	function confirm_delete_subcription(id) {
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
    var id=$(this).attr('data-id');
    alldelete_modal_show(id);
	});
	function alldelete_modal_show(id) {
      $('#notall_delete_modal').modal('show');
      $('#confirm_deleteall_sub').attr('data-id',id);
  	}
  	$('#confirm_deleteall_sub').on('click',function(){
      var id=$(this).attr('data-id');
      confirm_deleteall_subcription(id);
  	});
  	function confirm_deleteall_subcription(id) {
      if(id ==''){
            $('#notall_delete_modal').modal('hide');
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

  $(document).on("click", "#abuse_report", function () {
    var id=$(this).attr('data-id');
    abuse_modal_show(id);
  });
  function abuse_modal_show(id) {
      $('#abuse_modal').modal('show');
      $('#confirm_abuse_sub').attr('data-id',id);
      $('.repo_reason_error').hide();
    }
  $('#confirm_abuse_sub').on('click',function(){
    var id=$(this).attr('data-id');
    var user_id=$(this).attr('data-userid');
    var desc=$('#abuse_desc').val();
    
    if (desc != '') {
        $('.repo_reason_error').hide();
      } else if (desc == '') {
        $('.repo_reason_error').show();
         return false;
      }
    confirm_abuse_reports(id,user_id);
  });
  function confirm_abuse_reports(id,user_id) {
    if(id !=''){
    	  var desc=$('#abuse_desc').val();
          $('#abuse_modal').modal('hide');
           $.ajax({
                 type:'POST',
                 url: base_url+'user/service/abuse_report_post',
                 data : {id:id,desc:desc,user_id:user_id,csrf_token_name:csrf_token},
                 dataType:'json',
                 success:function(response)
                 {
                    swal({
                      title: "Success..!",
                      text: "Reported SuccessFully",
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
	
})(jQuery);