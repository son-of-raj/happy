(function($) {
  "use strict";
	var base_url=$('#base_url').val();
	var BASE_URL=$('#base_url').val();
	var csrf_token=$('#csrf_token').val();
	var csrfName=$('#csrfName').val();
	var csrfHash=$('#csrfHash').val();
	var modules=$('#modules_page').val();
  
	$( document ).ready(function() {
	    get_shop_services();
		$('.get_services').on('click',function(){
		  get_shop_services();
		}); 
		
		
		var myString = window.location.href.substring(window.location.href.lastIndexOf('#') + 1);
		$(".menu-tabs .nav-item").removeClass("active");
		var stng =  new RegExp( "^#(" + myString + ")" );
		$(stng).addClass("active");
		
		$('a[href="#'+ myString +'"]').tab('show');
		
		if (myString == 'shops') {
			$("#shops-tab").parent().addClass("active");
			jQuery('#myTabContent').tabs({ active: 0 });
		}
		if (myString == 'services') {
			$("#services-tab").parent().addClass("active");
			jQuery('#myTabContent').tabs({ active: 1 });
			get_services();	
		}
		
	});
function get_services() { 
	
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
   
   $('#dataList').html('<div class="page-loading">'+
	'<div class="preloader-inner">'+
		'<div class="preloader-square-swapping">'+
			'<div class="cssload-square-part cssload-square-green"></div>'+
			'<div class="cssload-square-part cssload-square-pink"></div>'+
			'<div class="cssload-square-blend"></div>'+
		'</div>'+
	'</div>'+
	
'</div>');
$('#dataList').empty();
   $.post(base_url+'home/all_services',{min_price:min_price,max_price:max_price,sort_by:sort_by,common_search:common_search,categories:categories,subcategories:subcategories,service_latitude:service_latitude,service_longitude:service_longitude,csrf_token_name:csrf_token,user_address:user_address,sub_subcategories:sub_subcategories},function(data){
			var obj=jQuery.parseJSON(data);
			$('#service_count').html(obj.count);
			$('#dataList').html(obj.service_details);
			$('html, body').animate({scrollTop: jQuery("body").offset().top}, 100);
   })
}

$(document).on("click", ".nav-tabs a", function(){
	var tab = $(this).attr("aria-controls"); 
	activaTab(tab);
});
function activaTab(tab){	
	if(tab == 'services') {
		$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
		$("#shops-tab").removeClass("active");
		$("#services-tab").addClass("active");
		$("#services-tab").parent("li").addClass("active");
		$("#shops").removeClass("active");
		$("#services").addClass("active");
		$("#services").addClass("show");
		$("#searchfor").val('service');
		get_services();	
	} else {
		$('.nav-tabs li.nav-item').removeClass("active"); $(".tab-pane").removeClass("active");
		$("#services-tab").removeClass("active");
		$("#shops-tab").addClass("active");
		$("#shops-tab").parent("li").addClass("active");
		$("#services").removeClass("active");
		$("#shops").addClass("active");
		$("#shops").addClass("show");	
		$("#searchfor").val('shop');
		$('.page_nos_0').trigger("click");;
	}
};


function get_shop_services() { 
	activaTab('shop');
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
   
   $('#dataListShop').html('<div class="page-loading">'+
	'<div class="preloader-inner">'+
		'<div class="preloader-square-swapping">'+
			'<div class="cssload-square-part cssload-square-green"></div>'+
			'<div class="cssload-square-part cssload-square-pink"></div>'+
			'<div class="cssload-square-blend"></div>'+
		'</div>'+
	'</div>'+
	
'</div>');

$('#dataListShop').empty();
   $.post(base_url+'home/all_services_shop',{min_price:min_price,max_price:max_price,sort_by:sort_by,common_search:common_search,categories:categories,subcategories:subcategories,service_latitude:service_latitude,service_longitude:service_longitude,csrf_token_name:csrf_token,user_address:user_address,sub_subcategories:sub_subcategories},function(data){ 
			var obj=jQuery.parseJSON(data);
			if(obj.count == false) obj.count=0;
		    $('#shop_count').html(obj.count);			
		    $('#dataListShop').html(obj.shop_details);
		    $('html, body').animate({scrollTop: jQuery("body").offset().top}, 0);
   })

}


var $priceFrom = $('.price-ranges .from'),
$priceTo = $('.price-ranges .to');
var min_price = $('#min_price').html();
var max_price = $('#max_price').html();

$(".price-range").slider({
	range: true,
	min: 1,
	max: 100000,
	values: [min_price, max_price],
	slide: function (event, ui) {
		$priceFrom.text(ui.values[0]);
		$priceTo.text(ui.values[1]);
	}
});
})(jQuery);