<?php
$category = $this->service->get_category();
$subcategory = $this->service->get_subcategory();
$service_image = $this->service->service_image($services['id']);
$service_id = $services['id'];


$user_currency_code = '';
$userId = $this->session->userdata('id');
If (!empty($userId)) {
    $service_amount = $services['service_amount'];
    $type = $this->session->userdata('usertype');
    if ($type == 'user') {
        $user_currency = get_user_currency();
    } else if ($type == 'provider') {
        $user_currency = get_provider_currency();
    } else if ($type == 'freelancer') {
        $user_currency = get_provider_currency();
    }
    $user_currency_code = $user_currency['user_currency_code'];

    $service_amount = get_gigs_currency($services['service_amount'], $services['currency_code'], $user_currency_code);
} else {
    $user_currency_code = settings('currency');
    $service_amount = $services['service_amount'];
}

$get_details = $this->db->select('category, subcategory')->where('id',$this->session->userdata('id'))->get('providers')->row_array();
$category = $this->service->readCategory();
$subcategory = $this->service->get_subcategory();
$subsubcategory = $this->service->get_subsubcategory();

$shop_details = $this->db->select('id, shop_name,shop_code, shop_location, shop_latitude, shop_longitude')->where('status',1)->where('provider_id',$this->session->userdata('id'))->order_by('id','DESC')->get('shops')->result_array();

$staff_details = $this->db->select('e.id, e.first_name, e.last_name, e.designation,s.shop_code')->join('shops s', 's.id = e.shop_id', 'LEFT')->where(array('e.status'=> 1,'e.delete_status' => 0))->where('e.provider_id', $userId)->order_by('e.shop_id','DESC')->get('employee_basic_details e')->result_array();

$durintxt = (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins'];

?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
				<div class="row">
					<div class="col">
						<div class="breadcrumb-title">
							<h2><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></h2>
						</div>
					</div>
					<div class="col-auto float-end ms-auto breadcrumb-menu">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
								
								<li class="breadcrumb-item"><a href="<?php echo base_url()."my-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></a></li>
								
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Edit_Service'])) ? $user_language[$user_selected]['lg_Edit_Service'] : $default_language['en']['lg_Edit_Service']; ?></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form method="post" enctype="multipart/form-data" autocomplete="off" id="update_service" action="<?php echo base_url() ?>user/service/update_service">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
                    
					<div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Category'])) ? $user_language[$user_selected]['lg_Service_Category'] : $default_language['en']['lg_Service_Category']; ?></h3>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select" name="category" id="category" required> 
										<?php foreach ($category as $cat) { ?>
                                            <option value="<?php echo  $cat['id'] ?>"  <?php if ($cat['id'] == $services['category']) { ?> selected = "selected" <?php } ?>><?php echo $cat['category_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?></label>
                                    <select class="form-control select" name="subcategory" id="subcategory"> 
										<?php foreach ($subcategory as $sub_category) { ?>
                                            <option value="<?php echo  $sub_category['id'] ?>"  <?php if ($sub_category['id'] == $services['subcategory']) { ?> selected = "selected" <?php } ?>><?php echo $sub_category['subcategory_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
					
					<div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Information'])) ? $user_language[$user_selected]['lg_Service_Information'] : $default_language['en']['lg_Service_Information']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Name'])) ? $user_language[$user_selected]['lg_Service_Name'] : $default_language['en']['lg_Service_Name']; ?> <span class="text-danger">*</span></label>
                                    <input type="hidden" name="service_id" id="service_id" value="<?php echo $services['id']; ?>">
                                    <input class="form-control" type="text" name="service_title" id="service_title" value="<?php echo $services['service_title']; ?>" required>
                                </div>
                            </div>
							
							
                           
                            <div class="col-lg-12 d-none">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Location'])) ? $user_language[$user_selected]['lg_Service_Location'] : $default_language['en']['lg_Service_Location']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_location" id="service_location" value="<?php echo $services['service_location'] ?>" > 
                                    <input type="hidden" name="service_latitude" id="service_latitude" value="<?php echo $services['service_latitude'] ?>">
                                    <input type="hidden" name="service_longitude" id="service_longitude" value="<?php echo $services['service_longitude'] ?>">
                                </div>
                            </div>
							
							
							
							<div class="col-lg-6">
								<div class="form-group">
									<?php 
										$shoptxt = (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; 
									?>
									<label><?php echo $shoptxt; ?> <span class="text-danger">*</span></label>
									<select class="form-control select services_shop_id" title="<?php echo $shoptxt; ?>" name="shop_id" id="shop_id"  required>
										<?php foreach ($shop_details as $shop) { 
												$shparr = explode(",", $services['shop_id']);
										?>
											<option value="<?php echo $shop['id']?>" data-subtext="<?php echo (!empty($shop['shop_code']))?$shop['shop_code']:''; ?>"  <?php echo (in_array($shop['id'],$shparr))?'selected="selected"':'';?>   data-location="<?php echo $shop['shop_location']?>" data-latitude="<?php echo $shop['shop_latitude']?>" data-longitude="<?php echo $shop['shop_longitude']?>"><?php echo $shop['shop_name']?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<?php 
										$stafftxt = (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; 
									?>
									<label><?php echo $stafftxt; ?> <span class="text-danger">*</span><a href="<?php echo base_url()?>add-staff" class="badge badge-secondary"><i class="fas fa-plus me-2"></i><?php echo (!empty($user_language[$user_selected]['lg_Add_Staff'])) ? $user_language[$user_selected]['lg_Add_Staff'] : $default_language['en']['lg_Add_Staff']; ?></a></label>
									<select class="form-control select" title="<?php echo $stafftxt; ?>" name="staff_id[]" id="staff_id" multiple required>
										<?php foreach ($staff_details as $stff) { 

												$stfarr = explode(",", $services['staff_id']);
										?>
											<option value="<?php echo $stff['id']?>" data-subtext="<?php echo (!empty($stff['shop_code']))?$stff['shop_code']:''; ?>" <?php echo (in_array($stff['id'],$stfarr))?'selected="selected"':'';?> ><?php echo $stff['first_name']." ".$stff['last_name']; ?>
											</option>
										<?php } ?>
									</select>
								</div>
							</div>
							 <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount_VAT'])) ? $user_language[$user_selected]['lg_Service_Amount_VAT'] : $default_language['en']['lg_Service_Amount_VAT']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_amount" id="service_amount" value="<?php echo round($service_amount); ?>" required>
                                </div>
                            </div>
							
							<div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Duration'])) ? $user_language[$user_selected]['lg_Service_Duration'] : $default_language['en']['lg_Service_Duration']; ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
									  <input type="text" class="form-control" name="duration" id="duration" value="<?php echo $services['duration'] ?>"  required>
									  <div class="input-group-append">
										<span class="input-group-text" id="basic-addon2"><?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?></span>
									  </div>
									  <input type="hidden" class="form-control" name="duration_in" id="duration_in" value="min(s)">
									</div>
									
                                </div>
                            </div>
							
                        </div>
                    </div>
					<?php if (settingValue('service_offered_showhide') == 1) { ?>
					<!-- Additional Service Content -->
					<div class="service-fields mb-3" id="addiservice_div">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services']; ?></h3>
						<?php 
						$addi_ser = $this->db->select('id, service_id,service_name, amount,duration')->where('status',1)->where('service_id',$service_id)->get('additional_services')->result_array();
						$addicnt = count($addi_ser); 
						?>
						

                        <div class="additional-info">
                            <div class="row form-row additional-cont-label <?php echo ($addicnt == 0)?'d-none':''?> ">
                                <div class="col-lg-4">
                                    <div class="form-group">    
										 <label><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?> </label>
									</div> 
                                </div>
								<div class="col-lg-3">
                                    <div class="form-group">  
										 <label><?php echo (!empty($user_language[$user_selected]['lg_lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?> </label>
                                    </div> 
                                </div>
								
								<div class="col-lg-3">
                                    <div class="form-group">     
										<label><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> </label>
                                        <input type="hidden" id="durintxt" value="<?php echo $durintxt; ?>" />
                                    </div> 
                                </div>								
                            </div>
							<?php if($addicnt > 0) { 
									foreach($addi_ser as $a => $addi){ 
										$additot=$this->db->where("FIND_IN_SET('".$addi['id']."', additional_services)")->from('book_service')->count_all_results();
										$addialerttxt = ''; $addialertmsg = '';
										if($additot > 0){
											$addialerttxt = (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services'];
											$addialertmsg = (!empty($user_language[$user_selected]['lg_Additional_Services_Err'])) ? $user_language[$user_selected]['lg_Additional_Services_Err'] : $default_language['en']['lg_Additional_Services_Err']; 
										}
							?>
								<div class="row form-row additional-cont">
                                <div class="col-lg-4">
                                    <div class="form-group">    
										<input class="form-control addicls" type="text" name="addi_servicename[]" id="addi_name"  value="<?php echo $addi['service_name']; ?>" />
									</div> 
                                </div>
								<div class="col-lg-3">
                                    <div class="form-group">  
										<input class="form-control addicls number" type="text" name="addi_serviceamnt[]" id="addi_amnt"  value="<?php echo $addi['amount']; ?>" />
                                    </div> 
                                </div>
								
								<div class="col-lg-3">
                                    <div class="form-group">     
										<div class="input-group">								 
										  <input type="text" class="form-control addicls number" name="addi_servicedura[]" id="addi_dura" value="<?php echo $addi['duration']; ?>">
										  <div class="input-group-append">
											<span class="input-group-text" id="basic-addon2"><?php echo $durintxt; ?></span>
										  </div>							  
										</div>
                                    </div> 
                                </div>
								
								<div class="col-lg-2">
									<a href="#" class="btn btn-danger <?php echo ($additot==0)?'trash':'trash-alert'; ?>" data-addialerttxt="<?php echo $addialerttxt; ?>" data-addialertmsg="<?php echo $addialertmsg; ?>"><i class="far fa-times-circle"></i></a>
									<input type="hidden" class="form-control" name="addiserid[]" value="<?php echo $addi['id']; ?>" >
								</div>
								
								
                            </div>
								
							<?php } } ?>
							
                        </div>
                        <div class="add-more-additional form-group">
                            <a href="javascript:void(0);" class="add-additional"><i class="fas fa-plus-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_Add_More_Additional_Services'])) ? $user_language[$user_selected]['lg_Add_More_Additional_Services'] : $default_language['en']['lg_Add_More_Additional_Services']; ?></a>
                        </div>
                    </div>
                    <!-- Additional Service Content Ends -->
					<?php  }  ?>
					

                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Details_Information'])) ? $user_language[$user_selected]['lg_Details_Information'] : $default_language['en']['lg_Details_Information']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?> <span class="text-danger">*</span></label>
                                    <textarea id="about" class="form-control service-desc" name="about"><?php echo $services['about'] ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Gallery'])) ? $user_language[$user_selected]['lg_Service_Gallery'] : $default_language['en']['lg_Service_Gallery']; ?> </h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="service-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span><?php echo (!empty($user_language[$user_selected]['lg_Upload_Image'])) ? $user_language[$user_selected]['lg_Upload_Image'] : $default_language['en']['lg_Upload_Image']; ?> *</span>
                                    <input type="file"  name="images[]" id="images" multiple accept="image/jpeg, image/png, image/gif,">

                                </div>	
                                <div id="uploadPreview">
                                    <ul class="upload-wrap" id="imgList">
<?php
$service_img = array();
for ($i = 0; $i < count($service_image); $i++) {
    ?>
                                            <li id="service_img_<?php echo $service_image[$i]['id']; ?>">
                                                <div class=" upload-images">
                                                	<a href="javascript:void(0);" class="file_close1 btn btn-icon btn-danger btn-sm delete_img" data-img_id="<?php echo $service_image[$i]['id']; ?>">X</a>
                                                    <img alt="Service Image" src="<?php echo base_url() . $service_image[$i]['service_image']; ?>">
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

