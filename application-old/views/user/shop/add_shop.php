<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);

$get_details = $this->db->select('category, subcategory')->where('id',$this->session->userdata('id'))->get('providers')->row_array();
$category = $this->shop->readCategory();
$subcategory = $this->shop->readSubcategory();
$subsubcategory = $this->shop->readSubSubcategory();

$fetchservices = $this->shop->fetchservices($get_details['category'], $get_details['subcategory']);


?>
<div class="breadcrumb-bar">
    <div class="container">
		<div class="row justify-content-center">
            <div class="col-lg-10">
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
								
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Add_Shop'])) ? $user_language[$user_selected]['lg_Add_Shop'] : $default_language['en']['lg_Add_Shop']; ?></li>
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
                        <a class="nav-link" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="service-tab" data-bs-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Information'])) ? $user_language[$user_selected]['lg_Shop_Information'] : $default_language['en']['lg_Shop_Information']; ?></a>
                    </li>
                </ul>		

                <form id="add_shop" method="POST" onSubmit='return submitDetailsForms()'action="<?php echo base_url()?>add-shop" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				 <div class="tab-content" id="myTabContent">
					
						<div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
						
					<div class="service-fields mb-3">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></h5>
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Shop_Title'])) ? $user_language[$user_selected]['lg_Shop_Title'] : $default_language['en']['lg_Shop_Title']; ?> <span class="text-danger">*</span></label>
									<input class="form-control " type="text" name="shop_title" id="shop_title" >
									<span class="error business_title"></span>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?> <span class="text-danger">*</span></label>
									<textarea id="about" class="form-control service-desc" name="about" ></textarea>
									<span class="error description"></span>
								</div>
							</div>							
						</div>
						<div class="row">
						<div class="col-lg-6">																		
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control number staffmobile" type="text" name="mobileno" id="mobileno" maxlength="10">
									<span class="text-danger" id="errexistno"></span>
									<input type="hidden" value="0" id="existsno" name="existsno"/>                
									<input type="hidden" value="" id="country_code" name="country_code"/>                
                                </div>
                            </div>						
						<div class="col-lg-6">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?> <span class="text-danger">*</span></label>
								<input class="form-control shopemail" type="text" name="email" id="email" >
								<span class="error sh_mail" id="err_existmail"></span>
								<input type="hidden" value="0" id="exists_mail" name="exists_mail"/>
							</div>
						</div>

					</div>
					</div>
					<div class="service-fields mb-3">
                   <h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?></h5>
                        <div class="row">
                      
							<div class="form-group col-xl-12">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?><span class="text-danger">*</span></label>
								<input type="text" class="form-control " name="address" value="" id="address">
								
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Country'])) ? $user_language[$user_selected]['lg_Country'] : $default_language['en']['lg_Country']; ?><span class="text-danger">*</span></label>
								<select class="form-control"  id="country_id" name="country_id" >
									<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_Country'])) ? $user_language[$user_selected]['lg_Select_Country'] : $default_language['en']['lg_Select_Country']; ?></option>
									<?php foreach($country as $row){?>
									<option value='<?php echo $row['id'];?>' ><?php echo $row['country_name'];?></option> 
								<?php } ?>
								</select>
								<span class="error countryerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_State'])) ? $user_language[$user_selected]['lg_State'] : $default_language['en']['lg_State']; ?><span class="text-danger">*</span></label>
								<select class="form-control"  name="state_id" id="state_id" >
								</select>
								<span class="error staterr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_City'])) ? $user_language[$user_selected]['lg_City'] : $default_language['en']['lg_City']; ?><span class="text-danger">*</span></label>
								<select class="form-control"  name="city_id" id="city_id">
								</select>
								<span class="error cityerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Postal_Code'])) ? $user_language[$user_selected]['lg_Postal_Code'] : $default_language['en']['lg_Postal_Code']; ?><span class="text-danger"></span></label>
								<input type="text" class="form-control number " name="pincode" id="pincode" value="" maxlength="10">
								<span class="error postalerr"></span>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Shop_Location'])) ? $user_language[$user_selected]['lg_Shop_Location'] : $default_language['en']['lg_Shop_Location']; ?> <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="shop_location" id="shop_location" value="<?php echo $this->session->userdata('current_location'); ?>" >
									<input type="hidden" name="shop_latitude" id="shop_latitude">
									<input type="hidden" name="shop_longitude" id="shop_longitude">
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
									<input type="file" name="images[]" id="images" multiple accept="image/jpeg, image/png, image/gif,">
								</div>
								<span class="error images"></span>
								<div id="uploadPreview"></div>
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
										<select class="form-control"  title="Category" name="category" id="category">
											<option>Select Category</option>
											<?php foreach ($category as $cat) { ?>
											<option value="<?=$cat['id']?>"  <?php 
											if ($cat['id'] == $get_details['category']) { ?> selected = "selected" <?php }   ?>><?php echo $cat['category_name']?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?> <span class="text-danger">*</span></label>
										<select class="form-control"  title="Sub Category" name="subcategory" id="subcategory"  >
										<?php foreach ($subcategory as $sub_category) {
										if($get_details['category']==$sub_category['category']){ ?>
											<option value="<?=$sub_category['id']?>"  <?php 
										if ($sub_category['id'] == $get_details['subcategory']) { ?> selected = "selected" <?php }   ?>><?php echo $sub_category['subcategory_name']?>
										</option>
										<?php } }?>
										</select>
									</div>
								</div>
								<div class="col-lg-4 d-none">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Sub_Category'] : $default_language['en']['lg_Sub_Sub_Category']; ?> <span class="text-danger">*</span></label>
										<select class="form-control"  title="Sub Sub Category" name="sub_subcategory" id="sub_subcategory">
										<?php foreach ($subsubcategory as $sscategory) { ?>
											<option value="<?=$sscategory['id']?>" ><?php echo $sscategory['sub_subcategory_name']?>
										</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="service-fields mb-3 d-none" id="serviceslistdata">
							<label><?php echo (!empty($user_language[$user_selected]['lg_Services_Offered_Details'])) ? $user_language[$user_selected]['lg_Services_Offered_Details'] : $default_language['en']['lg_Services_Offered_Details']; ?> <span class="text-danger">*</span></label>&nbsp;<span id="erroffer"></span>
							<div class="form-group" id="allServiceListsDiv">
								<table class="table table-bordered" id="allServiceLists">
									<thead>
										<tr>
											<th><?php echo (!empty($user_language[$user_selected]['lg_Sub_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Sub_Category'] : $default_language['en']['lg_Sub_Sub_Category']; ?></th>
											<th><?php echo (!empty($user_language[$user_selected]['lg_Service_Name'])) ? $user_language[$user_selected]['lg_Service_Name'] : $default_language['en']['lg_Service_Name']; ?></th>
											<th class="thamountcls"><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
											<th class="thdurationcls"><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?></th>
											
											<th class="thselectcls"><?php echo (!empty($user_language[$user_selected]['lg_Select'])) ? $user_language[$user_selected]['lg_Select'] : $default_language['en']['lg_Select']; ?></th>
										</tr>
									</thead>
									<tbody>

									<?php
									 if(count($fetchservices) > 0) { 
											foreach($fetchservices as $ks => $vs){
												$sscname = $this->db->select('sub_subcategory_name')->where('id',$vs['sub_subcategory'])->get('sub_subcategories')->row()->sub_subcategory_name;
												$user_currency_code = '';
												if (!empty($this->session->userdata('id'))) {
													$service_amount = $vs['service_amount'];
													$type = $this->session->userdata('usertype');
													if ($type == 'user') {
														$user_currency = get_user_currency();
													} else if ($type == 'provider') {
														$user_currency = get_provider_currency();
													} else if ($type == 'freelancer') {
														$user_currency = get_provider_currency();
													}
													$user_currency_code = $user_currency['user_currency_code'];
													$service_amount = get_gigs_currency($vs['service_amount'], $vs['currency_code'], $user_currency_code);
												} else {
													$user_currency_code = settings('currency');
													$service_amount = get_gigs_currency($vs['service_amount'], $vs['currency_code'], $user_currency_code);
												}
									?>
										<tr class="servicerow">
											<td class="text-info text-bold"><?php echo $sscname; ?></td>
											<td><?php echo $vs['service_title']; ?></td>
											<td><?php  echo currency_conversion($user_currency_code) . $service_amount; ?></td>
											<td><?php echo $vs['duration'].'<span class="small">'.$vs['duration_in'].'</span>'; ?></td>
											
											<td>
											<input type="checkbox"  value="<?php echo $vs['id']; ?>"id="chkbox_<?php echo $vs['id']; ?>" data-offerid="<?php echo $vs['id']; ?>"class="form-control service_offer checkboxcls" name="serviceoffer_id[<?php echo $ks; ?>][]" onchange="enableService(<?php echo $vs['id']; ?>);">									
											
											<input type="hidden" name="subsubcateid[<?php echo $ks; ?>][]" id="subsubcateid<?php echo $vs['id']; ?>" value="<?php echo $vs['sub_subcategory']; ?>" disabled/>
											
											<input type="hidden" name="stitle[<?php echo $ks; ?>][]" id="stitle<?php echo $vs['id']; ?>" value="<?php echo $vs['service_title']; ?>" disabled/>
											<input type="hidden" name="samount[<?php echo $ks; ?>][]" id="samount<?php echo $vs['id']; ?>" value="<?php echo $vs['service_amount']; ?>" disabled/>
											<input type="hidden" name="sduration[<?php echo $ks; ?>][]" id="sduration<?php echo $vs['id']; ?>" value="<?php echo $vs['duration']; ?>" disabled/>
											
											
											
											</td>
										</tr>
										
									<?php } } else { 
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found']; 
									?>
										<tr><td colspan="5" class="text-center"><?php echo $norecord; ?></td>
									<?php } ?>	
									</tbody>
									</thead>
								</table>
							
							</div>
							
							
						</div>
						
					</div>
					
					<div class="table-responsive mb-4">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Availability'])) ? $user_language[$user_selected]['lg_Shop_Availability'] : $default_language['en']['lg_Shop_Availability']; ?><span class="text-danger">*</span></h5>
						<?php $this->load->view('user/shop/shop_availability'); ?>
						<span class="error mb-4 available_title"></span>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="previous" name="previous" value="previous"><?php echo (!empty($user_language[$user_selected]['lg_Previous'])) ? $user_language[$user_selected]['lg_Previous'] : $default_language['en']['lg_Previous']; ?></button>
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" id="shopsubmit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>						
                    </div>
				</div>
					<input type="hidden" id="country_id_value" value="<?= set_value('country_id');?>">
					<input type="hidden" id="state_id_value" value="<?= set_value('state_id');?>">
					<input type="hidden" id="city_id_value" value="<?= set_value('city_id');?>">
					<input type="hidden" id="shop_id" name="shop_id" value="">
					<input type="hidden" name="selected_offerid" id="selected_offerid" value="">
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
