<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);

$shop_image = $this->shop->shop_image($shop_id); 

$get_details = $this->db->select('category, subcategory')->where('id',$this->session->userdata('id'))->get('providers')->row_array();
$category = $this->shop->readCategory();
$subcategory = $this->shop->readSubcategory();
$subsubcategory = $this->shop->readSubSubcategory();
$edit_fetchservice = $this->shop->shopservices($get_details['category'], $get_details['subcategory'],$shop_id);


?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Shops'])) ? $user_language[$user_selected]['lg_My_Shops'] : $default_language['en']['lg_My_Shops']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						
						<li class="breadcrumb-item"><a href="<?php echo base_url()."shop"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?></a></li>
						
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Edit_Shop'])) ? $user_language[$user_selected]['lg_Edit_Shop'] : $default_language['en']['lg_Edit_Shop']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
				<ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Service_Information'])) ? $user_language[$user_selected]['lg_Service_Information'] : $default_language['en']['lg_Service_Information']; ?></a>
                    </li>
                </ul>		

                <form id="update_shop" onSubmit='return submitDetailsForm()' action="<?php echo base_url()?>user/shop/update_shop" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				
				 <div class="tab-content" id="myTabContent">
					
						<div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
						
					<div class="service-fields mb-3">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></h5>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Shop_Title'])) ? $user_language[$user_selected]['lg_Shop_Title'] : $default_language['en']['lg_Shop_Title']; ?> <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="shop_title" id="shop_title" value="<?php echo $shop_details['shop_name'];?>">
									<span class="error business_title"></span>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?>  <span class="text-danger">*</span></label>
									<textarea id="about" class="form-control service-desc" name="about" ><?php echo $shop_details['description'];?></textarea>
									<span class="error description"></span>
								</div>
							</div>							
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								 <label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?> <span class="text-danger">*</span></label>
								<input class="form-control number shopmobile" type="text" name="mobileno" id="mobileno" maxlength="10"  value="<?php echo $shop_details['contact_no'];?>">
								<span class="error sh_mobile" id="err_existno"></span>
								<input type="hidden" value="0" id="exists_no" name="exists_no"/>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?>  <span class="text-danger">*</span></label>
								<input class="form-control shopemail" type="email" name="email" id="email" value="<?php echo $shop_details['email'];?>">
								<span class="error sh_mail" id="err_existmail"></span>
								<input type="hidden" value="0" id="exists_mail" name="exists_mail"/>
							</div>
						</div>
					</div>
					<div class="service-fields mb-3">
                   <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?></h5>
                        <div class="row">
                      
							<div class="form-group col-xl-12">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?><span class="text-danger">*</span></label>
								<input type="text" class="form-control " name="address" value="<?php echo $shop_details['address'];?>" id="address">
								
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Country'])) ? $user_language[$user_selected]['lg_Country'] : $default_language['en']['lg_Country']; ?><span class="text-danger">*</span></label>
								<select class="form-control select" id="country_id" name="country_id" >
									<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_Country'])) ? $user_language[$user_selected]['lg_Select_Country'] : $default_language['en']['lg_Select_Country']; ?></option>
									<?php foreach($country as $row){?>
									<option value='<?php echo $row['id'];?>' <?php if(!empty($shop_details['country'])){ echo ($row['id']==$shop_details['country'])?'selected':'';}?>><?php echo $row['country_name'];?></option> 
								<?php } ?>
								</select>
								<span class="error countryerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_State'])) ? $user_language[$user_selected]['lg_State'] : $default_language['en']['lg_State']; ?><span class="text-danger">*</span></label>
								<select class="form-control select" name="state_id" id="state_id" >
								</select>
								<span class="error staterr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_City'])) ? $user_language[$user_selected]['lg_City'] : $default_language['en']['lg_City']; ?><span class="text-danger">*</span></label>
								<select class="form-control select" name="city_id" id="city_id">
								</select>
								<span class="error cityerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Postal_Code'])) ? $user_language[$user_selected]['lg_Postal_Code'] : $default_language['en']['lg_Postal_Code']; ?><span class="text-danger"></span></label>
								<input type="text" class="form-control number " name="pincode" id="pincode" value="<?php echo $shop_details['postal_code'];?>" maxlength="10">
								<span class="error postalerr"></span>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Shop_Location'])) ? $user_language[$user_selected]['lg_Shop_Location'] : $default_language['en']['lg_Shop_Location']; ?> <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="shop_location" id="shop_location" value="<?php echo $shop_details['shop_location'];?>" >									
									<input type="hidden" name="shop_latitude" id="shop_latitude" value="<?php echo $shop_details['shop_latitude'];?>">
									<input type="hidden" name="shop_longitude" id="shop_longitude" value="<?php echo $shop_details['shop_longitude'];?>">
									<span class="error"></span>
								</div>
								<div id="map"></div>
							</div>
                        </div>
                    </div>
					<div class="service-fields mb-3">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Gallery'])) ? $user_language[$user_selected]['lg_Shop_Gallery'] : $default_language['en']['lg_Shop_Gallery']; ?></h5>	
						<div class="row">
							<div class="col-lg-12">
								<div class="service-upload">
									<i class="fas fa-cloud-upload-alt"></i>
									<span><?php echo (!empty($user_language[$user_selected]['lg_Upload_Image'])) ? $user_language[$user_selected]['lg_Upload_Image'] : $default_language['en']['lg_Upload_Image']; ?> *</span>
									<input type="file" name="images[]" id="images" accept="image/jpeg, image/png, image/gif,">
								</div>
								<span class="error images"></span>
								<div id="uploadPreview">
									  <ul class="upload-wrap">
										<?php for ($i = 0; $i < count($shop_image); $i++) {
											?>
                                            <li>
                                                <div class=" upload-images">

                                                    <img alt="Service Image" src="<?php echo base_url() . $shop_image[$i]['shop_image']; ?>">
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
								</div>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="next" name="next" value="next"><?php echo (!empty($user_language[$user_selected]['lg_Next'])) ? $user_language[$user_selected]['lg_Next'] : $default_language['en']['lg_Next']; ?></button>
					</div>
				</div>
				<div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">				
					<div class="service-fields mb-3">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Service_Details'])) ? $user_language[$user_selected]['lg_Service_Details'] : $default_language['en']['lg_Service_Details']; ?></h5>
						
												<div class="service-fields mb-3">								
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?> <span class="text-danger">*</span></label>
										<select class="form-control select" title="Category" name="category" id="category">
											<?php foreach ($category as $cat) { ?>
											<option value="<?php echo $cat['id']?>"  <?php 
											if ($cat['id'] == $shop_details['category']) { ?> selected = "selected" <?php }   ?>><?php echo $cat['category_name']?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?></label>
										<select class="form-control select" title="Sub Category" name="subcategory" id="subcategory"  >
										<?php foreach ($subcategory as $sub_category) {
										if($shop_details['category']==$sub_category['category']){ ?>
											<option value="<?php echo $sub_category['id']?>"  <?php 
										if ($sub_category['id'] == $shop_details['subcategory']) { ?> selected = "selected" <?php } else { echo 'selected'; }   ?>><?php echo $sub_category['subcategory_name']?>
										</option>
										<?php }  }?>
										</select>
									</div>
								</div>
								
							</div>
						</div>
					</div>
						
					
					
					<div class="table-responsive mb-4">
					<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Availability'])) ? $user_language[$user_selected]['lg_Shop_Availability'] : $default_language['en']['lg_Shop_Availability']; ?></h5>
					<?php $this->load->view('user/shop/shop_availability'); ?>
					<span class="error mb-4 available_title"></span>
					</div>
					<div class="submit-section">	
						<button class="btn btn-primary submit-btn" type="button" id="previous" name="previous" value="previous"><?php echo (!empty($user_language[$user_selected]['lg_Previous'])) ? $user_language[$user_selected]['lg_Previous'] : $default_language['en']['lg_Previous']; ?></button>
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" id="shopsubmit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>						
                    </div>
					<input type="hidden" id="country_id_value" value="<?php echo $shop_details['country']; ?>">
					<input type="hidden" id="state_id_value" value="<?php echo $shop_details['state']; ?>">
					<input type="hidden" id="city_id_value" value="<?php echo $shop_details['city']; ?>">
					<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $shop_id; ?>">
					<input type="hidden" name="selected_offerid" id="selected_offerid" value="">
				</div>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php /* function submitDetailsForm()
{
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
}
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

           //from time validate
            if ($('.eachdayfromtime' + row).val() != '') {
                var to_time = $('.eachdaytotime' + row).val();
                if ($(".eachdays" + row).prop('checked') == false || to_time == '') {
                    $('.eachdaytotime' + row).attr('style', 'border-color:red');
                    $('.eachdayfromtime' + row).removeAttr("style");
                    test = false;
                }
            }

            //to time Validate
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

} */ ?>
</script>