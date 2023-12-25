<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);



?>
<div class="content">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="section-header text-center">
                    <h2>Add Branch</h2>
                </div>
				
				<ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Basic Information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="service-tab" data-toggle="tab" href="#service" role="tab" aria-controls="service" aria-selected="true">Service Information</a>
                    </li>
                </ul>		

                <form id="add_branch" onSubmit='return submitDetailsForm()' action="<?php echo base_url()?>add-branch" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
				 <div class="tab-content" id="myTabContent">
					
						<div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
						
					<div class="service-fields mb-3">
						<h5 class="form-title">Basic Details</h5>
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label>Branch For <span class="text-danger">*</span></label>
									<select name="shopid" id="shopid" class="form-control select">
										<option value="">Select Shop</option>
										<?php foreach ($shop_lists as $key => $slist) { ?>
											<option value="<?php echo $slist['id'];?>"><?php echo $slist['shop_name'];?></option>
										<?php } ?>
									</select>   
									<span class="error business_select"></span>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label>Branch Title <span class="text-danger">*</span></label>
									<input class="form-control charonly" type="text" name="branch_title" id="branch_title" >
									<span class="error business_title"></span>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Descriptions <span class="text-danger">*</span></label>
									<textarea id="about" class="form-control service-desc" name="about" ></textarea>
									<span class="error description"></span>
								</div>
							</div>							
						</div>
						<div class="row">
						<div class="col-lg-2">																		
                                <div class="form-group">
                                    <label>Mobile <span class="text-danger">*</span></label>
									<select name="countryCode" id="country_code" class="form-control countryCode final_country_code">
										<?php
										foreach ($country_list as $key => $countryy) { 
											if($countryy['country_id']=='91'){$select='selected';}else{ $select='';} ?>
											<option <?php echo $select;?> data-countryCode="<?php echo $countryy['country_code'];?>" value="<?php echo $countryy['country_id'];?>"><?php echo $countryy['country_name'];?></option>
										<?php } ?>
									</select>                                    
                                </div>
                            </div>
						<div class="col-lg-4">
							<div class="form-group">
								<label>&nbsp;</label>
								<input class="form-control number branchmobile" type="text" name="mobileno" id="mobileno" maxlength="10">
								<span class="error sh_mobile" id="err_existno"></span>
								<input type="hidden" value="0" id="exists_no" name="exists_no"/>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="form-group">
								<label>Email <span class="text-danger">*</span></label>
								<input class="form-control branchemail" type="text" name="email" id="email" >
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
								<select class="form-control " id="country_id" name="country_id" >
									<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_Country'])) ? $user_language[$user_selected]['lg_Select_Country'] : $default_language['en']['lg_Select_Country']; ?></option>
									<?php foreach($country as $row){?>
									<option value='<?php echo $row['id'];?>' ><?php echo $row['country_name'];?></option> 
								<?php } ?>
								</select>
								<span class="error countryerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_State'])) ? $user_language[$user_selected]['lg_State'] : $default_language['en']['lg_State']; ?><span class="text-danger">*</span></label>
								<select class="form-control " name="state_id" id="state_id" >
								</select>
								<span class="error staterr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_City'])) ? $user_language[$user_selected]['lg_City'] : $default_language['en']['lg_City']; ?><span class="text-danger">*</span></label>
								<select class="form-control " name="city_id" id="city_id">
								</select>
								<span class="error cityerr"></span>
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Postal_Code'])) ? $user_language[$user_selected]['lg_Postal_Code'] : $default_language['en']['lg_Postal_Code']; ?><span class="text-danger">*</span></label>
								<input type="text" class="form-control number " name="pincode" id="pincode" value="" maxlength="10">
								<span class="error postalerr"></span>
							</div>
							<div class="col-lg-12">
								<div class="form-group">
									<label>Branch Location <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="service_location" id="service_location" >
									<span class="error location"></span>
									<input type="hidden" name="service_latitude" id="service_latitude">
									<input type="hidden" name="service_longitude" id="service_longitude">
								</div>
							</div>
                        </div>
                    </div>
					
					<div class="service-fields mb-3">
						<h5 class="form-title">Branch Gallery</h5>						
						<div class="row">
							<div class="col-lg-12">
								<div class="service-upload">
									<i class="fas fa-cloud-upload-alt"></i>
									<span>Upload Branch Images *</span>
									<input type="file" name="images[]" id="images" multiple accept="image/jpeg, image/png, image/gif,">
								</div>
								<span class="error images"></span>
								<div id="uploadPreview"></div>
							</div>
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="next" name="next" value="next">Next</button>
					</div>
				</div>
				<div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="service-tab">					
					<div class="service-fields mb-3">
						<h5 class="form-title">Service Details</h5>
                        <div class="branch-membership-info">
							<div class="row form-row membership-cont">
								<div class="col-lg-12">
									<label>Services Offered Details<span class="text-danger">*</span></label>
									<table class="table table-bordered" id="append">
										<thead>
											<tr>
												<th class="text-center">Service Offered</th>
												<th class="text-center">Service Name</th>	
												<th class="text-center">Description</th>
												<th class="text-center">Staff</th>
												<th colspan="2"  class="text-center">Duration</th>
												<th class="text-center">Price</th>
												<th></th>
											</tr>
										</thead>
										<tbody>											
											<tr class="singlerow">
												<td>
												<select class="form-control select selectdrop" title="Service Offered" name="serviceoffers[0][]" id="serviceoffer0">
													<?php foreach($branch_servicelist as $key => $value){?>
														<option value='<?php echo $value['id'];?>'>
															<?php echo $value['service_offered'];?>
														</option> 
													<?php } ?>
												</select>
												</td>
												<td><input type="text" class="form-control charonly shp_spldetail" name="name[0][]" value="" ></td>
												<td><input type="text" class="form-control shp_spldetail" name="desc[0][]" value="" >
												</td>
												<td>
												<select class="form-control select selectdrop" multiple title="<?php echo (count($branch_stafflist) >0)?'Staffs':'Staffs Not Available'; ?>" name="selectstaffs[0][]" id="selectstaff0">	
													<?php for($sr=0;$sr<count($branch_stafflist);$sr++){?>
														<option value='<?php echo $branch_stafflist[$sr]['id'];?>'>
															<?php echo $branch_stafflist[$sr]['first_name']."&nbsp;(".$branch_stafflist[$sr]['designation'].")";?>
														</option> 
													<?php } ?>
												</select>
												</td>
												<td colspan="2"><input type="text" class="form-control shp_spldetail dur1" name="duration[0][]" value="" onkeypress="return validatenumerics(event);">
												<select class="form-control select dur2"  name="duration_in[0][]"><option value="hr(s)">Hour(s)</option><option value="min(s)">Minutes(s)</option></select>
												</td>
												<td><input type="text" class="form-control shp_spldetail" name="price[0][]" value="" onkeypress="return validatenumerics(event);"> &nbsp;<span class="errmsgprice"></span>		
											<input type="hidden" class="form-control" name="actiontype[0][]" value="0_insert" >	  	</td>
												<td>
												<a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						
					</div>
						
					<div class="add-more form-group">
						<input type="hidden" value="0" name="rowCount" id="row_count">
						<a href="javascript:void(0);" class="addbranchspecial"><i class="fas fa-plus-circle"></i> Add More Services </a>
					</div>
					
					<div class="table-responsive">
						<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Branch_Availability'])) ? $user_language[$user_selected]['lg_Branch_Availability'] : $default_language['en']['lg_Branch_Availability']; ?></h5>
						<?php $this->load->view('user/branch/branch_availability'); ?>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" type="button" id="previous" name="previous" value="previous">Previous</button>
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" id="branchsubmit" value="submit">Submit</button>						
                    </div>
				</div>
					<input type="hidden" id="country_id_value" value="<?php echo  set_value('country_id');?>">
					<input type="hidden" id="state_id_value" value="<?php echo  set_value('state_id');?>">
					<input type="hidden" id="city_id_value" value="<?php echo  set_value('city_id');?>">
					<input type="hidden" id="shop_id" name="shop_id" value="">
					<input type="hidden" id="branch_id" name="branch_id" value="">
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
