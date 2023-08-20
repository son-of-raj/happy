(function($) {
  "use strict";

  var base_url=$('#base_url').val();
  var csrf_token=$('#csrf_token').val();
  var csrfName=$('#csrfName').val();
  var csrfHash=$('#csrfHash').val();

  

	$( document ).ready(function() {	
		
		$('#category').on('change',function(){

			$("#subcategory").val('default');
			$("#subcategory").selectpicker("refresh");


			$.ajax({
				type: "POST",
				url: base_url+"user/service/get_selected_subcategory",
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
				  $("#sub_subcategory option:eq(0)").remove(); 	
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
				  $("#sub_subcategory option:eq(0)").remove(); 	
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
		
		
	});
	
	

 
})(jQuery);

