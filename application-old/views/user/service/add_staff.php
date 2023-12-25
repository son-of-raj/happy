<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
$user_currency = get_user_currency();
} else if ($type == 'provider') {
$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code']; 

$get_details = $this->db->select('category, subcategory')->where('id',$this->session->userdata('id'))->get('providers')->row_array();
$category = $this->service->readCategory();
$subcategory = $this->service->readSubcategory();
$subsubcategory = $this->service->readSubSubcategory();

$mintitle = (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins'];

$shops = $this->db->select('id, shop_name')->where('status',1)->where('provider_id',$this->session->userdata('id'))->get('shops')->result_array();

$shptit = (!empty($user_language[$user_selected]['lg_Select_Shop'])) ? $user_language[$user_selected]['lg_Select_Shop'] : $default_language['en']['lg_Select_Shop'];

$StffMsg = (!empty($this->user_language[$this->user_selected]['lg_HomeService_Option'])) ? $this->user_language[$this->user_selected]['lg_HomeService_Option'] : $this->default_language['en']['lg_HomeService_Option'];	
$SelShopMsp = (!empty($this->user_language[$this->user_selected]['lg_Please_Select_Shop'])) ? $this->user_language[$this->user_selected]['lg_Please_Select_Shop'] : $this->default_language['en']['lg_Please_Select_Shop'];	

$ShopLocMsp = (!empty($this->user_language[$this->user_selected]['lg_Shop_Location'])) ? $this->user_language[$this->user_selected]['lg_Shop_Location'] : $this->default_language['en']['lg_Shop_Location'];	
$SelLocMsp = (!empty($this->user_language[$this->user_selected]['lg_Selected_Area'])) ? $this->user_language[$this->user_selected]['lg_Selected_Area'] : $this->default_language['en']['lg_Selected_Area'];	

?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
				<div class="row">
					<div class="col">
						<div class="breadcrumb-title">
							<h2><?php echo (!empty($user_language[$user_selected]['lg_My_Staffs'])) ? $user_language[$user_selected]['lg_My_Staffs'] : $default_language['en']['lg_My_Staffs']; ?></h2>
						</div>
					</div>
					<div class="col-auto float-end ms-auto breadcrumb-menu">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
								
								<li class="breadcrumb-item"><a href="<?php echo base_url()."staff-settings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Staff_Settings'])) ? $user_language[$user_selected]['lg_Staff_Settings'] : $default_language['en']['lg_Staff_Settings']; ?></a></li>
								
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Add_Staff'])) ? $user_language[$user_selected]['lg_Add_Staff'] : $default_language['en']['lg_Add_Staff']; ?></li>
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

				<ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Personal_Information'])) ? $user_language[$user_selected]['lg_Personal_Information'] : $default_language['en']['lg_Personal_Information']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Information'])) ? $user_language[$user_selected]['lg_Profile_Information'] : $default_language['en']['lg_Profile_Information']; ?></a>
                    </li>
                </ul>
				
                <form id="addstaff" action="<?php echo base_url()?>add-staff" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
					
					 <div class="tab-content" id="myTabContent">
					
						<div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
						
						<div class="service-fields mb-3">
							 <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></h5>
								
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label><?php echo (!empty($user_language[$user_selected]['lg_Assigned_to_Shop'])) ? $user_language[$user_selected]['lg_Assigned_to_Shop'] : $default_language['en']['lg_Assigned_to_Shop']; ?> <span class="text-danger">*</span></label>
											<select class="form-control select" title="<?php echo $shptit; ?>" name="shop_id" id="shop_id">
												<?php foreach ($shops as $shp) { ?>
												<option value="<?php echo $shp['id']?>" ><?php echo $shp['shop_name']?>
												</option>
												<?php } ?>
											</select>
										</div>
										<input type="hidden" name="shp_loc" id="shp_loc" value="">
										<input type="hidden" name="shp_lat" id="shp_lat" value="">
										<input type="hidden" name="shp_lng" id="shp_lng" value="">
									</div>
								</div>
							</div>
					
                    <div class="service-fields mb-3">
                       
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?> <span class="text-danger">*</span></label>
									<input type="hidden" class="form-control" id="map_key" value="<?php echo $map_key?>" >
                                    <input class="form-control" type="text" name="firstname" id="firstname" >
									<span class="error firstname"></span>
                                </div>
                            </div>
                            
							<div class="col-lg-6">																		
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control number staffmobile" type="text" name="mobileno" id="mobileno" maxlength="10">
									<span class="text-danger" id="errexistno" class="errexistno"></span>
									<input type="hidden" value="0" id="existsno" name="existsno"/>
									<input type="hidden" value="" id="country_code" name="country_code"/>                            
                                </div>
                            </div>
						
							
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control staffemail" type="text" name="email" id="email" >
									<span class="text-danger" id="errexistmail"></span>
									<input type="hidden" value="0" id="existsmail" name="existsmail"/>
                                </div>
                            </div>
							<div class="form-group col-xl-3">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Date_birth'])) ? $user_language[$user_selected]['lg_Date_birth'] : $default_language['en']['lg_Date_birth']; ?><span class="text-danger">*</span></label>
								<input type="text" class="form-control  staff_datepicker" autocomplete="off" name="dob" value="<?php ?>" id="dob" readonly>
								<span class="text-danger" id="errdob"></span>
							</div>
							<div class="form-group col-xl-3">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_gender'])) ? $user_language[$user_selected]['lg_gender'] : $default_language['en']['lg_gender']; ?><span class="text-danger">*</span></label>
								<br>
								<label class="radio-inline">
								  <input type="radio" name="gender" value="Male" checked/>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Male'])) ? $user_language[$user_selected]['lg_Male'] : $default_language['en']['lg_Male']; ?>
								</label>
								<label class="radio-inline">
								  <input type="radio" name="gender" value="Female" />&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Female'])) ? $user_language[$user_selected]['lg_Female'] : $default_language['en']['lg_Female']; ?>
								</label>								
							</div>
                        </div>
                    </div>
					<div class="service-fields mb-3"> 
						<label class="me-sm-2 float-left"> <?php echo (!empty($user_language[$user_selected]['lg_Staff_Work_Option'])) ? $user_language[$user_selected]['lg_Staff_Work_Option'] : $default_language['en']['lg_Staff_Work_Option']; ?>  <span class="text-danger">*</span> </label>
						
						<div class="custom-control custom-control-xs custom-checkbox float-left  me-1 ml-1" >						
							
							<input type="checkbox" class="custom-control-input servchkbox" name="home_service_shop" id="home_service_shop" value="1" checked>
							<label class="custom-control-label" for="home_service_shop" > <?php echo (!empty($user_language[$user_selected]['lg_At_Shop'])) ? $user_language[$user_selected]['lg_At_Shop'] : $default_language['en']['lg_At_Shop']; ?></label>
						</div>
						<div class="custom-control custom-control-xs custom-checkbox float-left me-3 ml-3" >
							<input type="checkbox" class="custom-control-input servchkbox" name="home_service_home" id="home_service_home" value="2">
							<label class="custom-control-label" for="home_service_home"> <?php echo (!empty($user_language[$user_selected]['lg_At_Home'])) ? $user_language[$user_selected]['lg_At_Home'] : $default_language['en']['lg_At_Home']; ?></label>
							
						</div>						
					</div>
					
					<div id="homeservice_err" class="d-none error"><?php echo (!empty($user_language[$user_selected]['lg_Home_Service_Err'])) ? $user_language[$user_selected]['lg_Home_Service_Err'] : $default_language['en']['lg_Home_Service_Err']; ?></div>

					<div class="service-fields mb-3" id="homeservicemap">
						<div class="form-group">
							<input class="form-control" type="hidden" id="sel_location" value="<?php echo $this->session->userdata('current_location'); ?>" >
							<input type="hidden" id="sel_latitude" value="<?php echo $this->session->userdata('user_latitude'); ?>">
							<input type="hidden" id="sel_longitude"  value="<?php echo $this->session->userdata('user_longitude'); ?>">
							<input type="hidden" name="selected_area" id="selected_area" value="">
							<div id="homeservice_map" class="map-frame"></div>
						</div>
					</div>

					<div id="homeservice_hint" class="d-none"><small>*** <?php echo (!empty($user_language[$user_selected]['lg_Home_Service_Txt'])) ? $user_language[$user_selected]['lg_Home_Service_Txt'] : $default_language['en']['lg_Home_Service_Txt']; ?></small></div>

					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="next" name="next" value="next"><?php echo (!empty($user_language[$user_selected]['lg_Next'])) ? $user_language[$user_selected]['lg_Next'] : $default_language['en']['lg_Next']; ?></button>
					</div>
				</div>
				<div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">
                   <div class="service-fields mb-3">
                        <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Details'])) ? $user_language[$user_selected]['lg_Profile_Details'] : $default_language['en']['lg_Profile_Details']; ?></h5>
						
                    </div>
                    <div class="service-fields mb-3">
						<div class="service-fields mb-3"><div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Profile_Description'])) ? $user_language[$user_selected]['lg_Profile_Description'] : $default_language['en']['lg_Profile_Description']; ?> <span class="text-danger">*</span></label>
								<textarea id="about" class="form-control service-desc" name="about" id="about"><?php echo $staff_details[0]['about_emp'] ?></textarea>
								<span class="text-danger" id="errabout"></span>
							</div>
						</div>
					</div></div>
						
                    </div>
					<div class="table-responsive mb-4">
					<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Staff_Availability'])) ? $user_language[$user_selected]['lg_Staff_Availability'] : $default_language['en']['lg_Staff_Availability']; ?>  <span class="text-danger">*</span></h5>
					<?php
					$this->load->view('user/service/service_availability');
					?>
					<span class="error mb-4 available_title"></span>
					</div>
					 <div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="previous" name="previous" value="previous"><?php echo (!empty($user_language[$user_selected]['lg_Previous'])) ? $user_language[$user_selected]['lg_Previous'] : $default_language['en']['lg_Previous']; ?></button>
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" id="addstaff_submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
                    </div>					
				</div>
				
				</div>
						<input type="hidden" id="staff_id_value" name="staff_id" value="">
						<input type="hidden" id="ShopLocMsp" value="<?php echo $ShopLocMsp; ?>" />
						<input type="hidden" id="SelLocMsp" value="<?php echo $SelLocMsp; ?>" />
                </form>

            </div>
        </div>
    </div>
</div>