<?php $get_details = $this->db->where('id',$this->session->userdata('id'))->get('providers')->row_array(); ?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></h2>
                </div>
               
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
	$provider_address=$this->db->where('provider_id',$this->session->userdata('id'))->get('provider_address')->row_array();

	$category = $this->db->where('id',$get_details['category'])->get('categories')->result_array();
	$subcategory = $this->service->get_subcategory();
?>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">
				<?php if($get_details['commercial_verify'] == 1 && $get_details['commercial_reg_image'] == '') { ?>
				<div class="alert alert-warning" role="alert">
					<i class="fas fa-exclamation-triangle mr-2"></i> Please upload the Commercial Registration Document.
				</div>
				<?php } 
            	if($get_details['commercial_verify'] == 1 && $get_details['commercial_reg_image'] != '') { ?>
				<div class="alert alert-warning" role="alert">
					<i class="fas fa-exclamation-triangle mr-2"></i> Admin will verify the commercial document and then, You will be able to Post a Service
				</div>
            	<?php }

            	?>
            	<div class="col-xl-12">
					<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Basic_Information'])) ? $user_language[$user_selected]['lg_Basic_Information'] : $default_language['en']['lg_Basic_Information']; ?></h5>
					<a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light me-2 delete_account"  data-id="<?php echo $this->session->userdata('id');?>" data-type="<?php echo $this->session->userdata('usertype');?>"><i class="far fa-trash-alt me-1"></i>Delete Account</a>
				</div>
				<form id="update_provider" action="<?php echo base_url()?>user/dashboard/update_provider" method="POST" enctype="multipart/form-data">
					<div class="widget mb-0">
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
						<div class="row">
							<div class="form-group col-xl-12">
								<div class="media align-items-center mb-3">
									<?php if($get_details['profile_img'] != '' && file_exists($get_details['profile_img'])) { ?>
									<img class="user-image" src="<?php echo base_url().$get_details['profile_img']?>" alt="">
									<?php } else { ?>
									<img class="user-image" src="<?php echo base_url();?>assets/img/user.jpg" alt="">
									<?php } ?>
									<div class="media-body">
										<h5 class="mb-0"><?php echo $get_details['name']?></h5>
										<p>Max file size is 20mb</p>
										<div class="jstinput"><a id="openfile" href="javascript:void(0);"  class="browsephoto"><?php echo (!empty($user_language[$user_selected]['lg_Browse'])) ? $user_language[$user_selected]['lg_Browse'] : $default_language['en']['lg_Browse']; ?></a></div> 
										<input type="hidden" id="crop_prof_img" name="profile_img">
									</div>
								</div>
							</div>
						</div>
					</div>
						<div class="row">
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></label>
								<input class="form-control" type="text" value="<?php echo $get_details['name']?>" readonly>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?></label>
								<input class="form-control" type="email" value="<?php echo $get_details['email']?>" readonly>
							</div>
							<div class="form-group col-xl-6">
								<?php $mob_no = '+'.$get_details['country_code'].$get_details['mobileno']; ?>

								<label>Mobile Number</label><br>
								<input type="hidden" name="country_code" id="country_code" value="<?php echo (!empty($get_details['country_code']))?$get_details['country_code']:''?>">
								<input class="form-control no_only umobileno" type="text" name="mobileno" id="mobileno" value="<?php echo (!empty($get_details['mobileno']))?$mob_no:''?>">
							</div>
							<div class="form-group col-xl-6">
								<label class="mr-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Date_birth'])) ? $user_language[$user_selected]['lg_Date_birth'] : $default_language['en']['lg_Date_birth']; ?></label>
								<?php if(!empty($get_details['dob'])){
				                             $date=date(settingValue('date_format'), strtotime($get_details['dob']));
				                            }else{
				                                $date='-';                                
				                            } ?>
								<input type="text" class="form-control provider_datepicker" autocomplete="off" name="dob" value="<?php echo $date;?>">
							</div>
							
							<div class="col-xl-12">
								<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Address_details'])) ? $user_language[$user_selected]['lg_Address_details'] : $default_language['en']['lg_Address_details']; ?></h5>
							</div>
							<div class="form-group col-xl-12">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Address'])) ? $user_language[$user_selected]['lg_Address'] : $default_language['en']['lg_Address']; ?></label>
								<input type="text" class="form-control" name="address" value="<?php if(!empty($provider_address['address'])){ echo $provider_address['address']; }?>">
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Country'])) ? $user_language[$user_selected]['lg_Country'] : $default_language['en']['lg_Country']; ?></label>
								<select class="form-control" id="country_id" name="country_id" >
									<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_Country'])) ? $user_language[$user_selected]['lg_Select_Country'] : $default_language['en']['lg_Select_Country']; ?></option>
									<?php foreach($country as $row){?>
									<option value='<?php echo $row['id'];?>' <?php if(!empty($provider_address['country_id'])){ echo ($row['id']==$provider_address['country_id'])?'selected':'';}?>><?php echo $row['country_name'];?></option> 
								<?php } ?>
								</select>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_State'])) ? $user_language[$user_selected]['lg_State'] : $default_language['en']['lg_State']; ?></label>
								<select class="form-control" name="state_id" id="state_id" >
								</select>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_City'])) ? $user_language[$user_selected]['lg_City'] : $default_language['en']['lg_City']; ?></label>
								<select class="form-control " name="city_id" id="city_id">
								</select>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Postal_Code'])) ? $user_language[$user_selected]['lg_Postal_Code'] : $default_language['en']['lg_Postal_Code']; ?></label>
								<input type="text" class="form-control number" name="pincode" value="<?php if(!empty($provider_address['pincode'])){echo $provider_address['pincode'];} ?>" >
							</div>
							
							
							<div class="col-xl-12">
								<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Home_Service'])) ? $user_language[$user_selected]['lg_Home_Service'] : $default_language['en']['lg_Home_Service']; ?></h5>
							</div>
							
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Home_Service_Charges'])) ? $user_language[$user_selected]['lg_Home_Service_Charges'] : $default_language['en']['lg_Home_Service_Charges']; ?></label>
								<input type="text" class="form-control number" name="homeservice_fee" value="<?php if(!empty($get_details['homeservice_fee'])){echo $get_details['homeservice_fee'];} ?>" >
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Home_Service_Arrival'])) ? $user_language[$user_selected]['lg_Home_Service_Arrival'] : $default_language['en']['lg_Home_Service_Arrival']; ?></label>
								<div class="input-group mb-3">
								  <input type="text" class="form-control number" name="homeservice_arrival" value="<?php if(!empty($get_details['homeservice_arrival'])){echo $get_details['homeservice_arrival'];} else echo 40; ?>" >
								  <div class="input-group-append">
									<span class="input-group-text" id="basic-addon2"><?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?></span>
								  </div>								 
								</div>
								
							</div>
							<div class="col-xl-12">
							<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_commercial_register'])) ? $user_language[$user_selected]['lg_commercial_register'] : $default_language['en']['lg_commercial_register']; ?></h5>
							
							</div>
							<?php
								if (file_exists($get_details['commercial_reg_image'])) {
									?>
								<div class="form-group col-xl-4 upload-file">
								<?php }  else { ?>
								<div class="form-group col-xl-6 upload-file">
								<input type="hidden" class="com_reg_val" value=""> 
								<?php } ?>
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_commercial_register'])) ? $user_language[$user_selected]['lg_commercial_register'] : $default_language['en']['lg_commercial_register']; ?><span class="error">*<span></label>
								<input type="file" accept="image/x-png,image/gif,image/jpeg,application/pdf, .doc, .xlsx"  class="form-control commercial_reg" autocomplete="off" id="commercial_reg" name="commercial_reg_image" value="<?php echo (!empty($get_details['commercial_reg_image'])) ? $get_details['commercial_reg_image'] : ''; ?>" <?php if(!empty($get_details['commercial_reg_image'])) { echo 'disabled'; } ?>>
								<span class="text-danger" id="errcommercial_image"></span>
							</div>
							<?php
                                    if (file_exists($get_details['commercial_reg_image'])) {
                                        $tmp = explode('.', $get_details['commercial_reg_image']);
										$extension = end($tmp);
                                        ?>
                                    <div class="form-group col-xl-2 upload-file">
                                        <input type="hidden" class="com_reg_val" value="<?php echo $get_details['commercial_reg_image']; ?>"> 
                                        <div class="media align-items-center mb-3">
										<a class="text-info" href="<?php echo (!empty($get_details['commercial_reg_image'])) ? base_url() . $get_details['commercial_reg_image'] : ''; ?>" download>
                                        <?php if($extension == 'pdf') { ?>
										<span><i class="fa fa-file-pdf" aria-hidden="true"> Download PDF</i></span></a>
										<?php } else { ?>
                                        <img class="user-image" src="<?php echo  base_url() .  $get_details['commercial_reg_image']; ?>"/></a>
                                        <?php } ?>
                                        </div>
                                    </div>
                                        <?php }
                                    ?>
							<div class="col-xl-12">
								<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Reward_System'])) ? $user_language[$user_selected]['lg_Reward_System'] : $default_language['en']['lg_Reward_System']; ?></h5>
							</div>
							
							<div class="form-group col-xl-12">
								<input name="allowrewards" id="allowrewards" type="checkbox"  value="1" <?php if($get_details['allow_rewards']== 1) { echo "checked";  } ?>>
								<label for="switch"><?php echo (!empty($user_language[$user_selected]['lg_Enable_Rewards'])) ? $user_language[$user_selected]['lg_Enable_Rewards'] : $default_language['en']['lg_Enable_Rewards']; ?></label>
							</div>
							<div class="form-group col-xl-6">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Rewards_Txt'])) ? $user_language[$user_selected]['lg_Rewards_Txt'] : $default_language['en']['lg_Rewards_Txt']; ?></label>
								<input class="form-control" type="number" min="1" name="reward_count" id="reward_count" value="<?php echo $get_details['booking_reward_count']; ?>" <?php echo ($get_details['allow_rewards']== 0)? "disabled":''; ?>>
								
							</div>
							
							<div class="col-xl-12">
								<h5 class="form-title"><?php echo (!empty($user_language[$user_selected]['lg_Account_Info'])) ? $user_language[$user_selected]['lg_Account_Info'] : $default_language['en']['lg_Account_Info']; ?></h5>
							</div>
							
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Account_Name'])) ? $user_language[$user_selected]['lg_Account_Name'] : $default_language['en']['lg_Account_Name']; ?><span class="error">*<span></label>
								<input type="text" class="form-control" id="account_holder_name" name="account_holder_name" value="<?php if(!empty($get_details['account_holder_name'])){echo $get_details['account_holder_name'];} ?>" >
							</div>
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Account_Number'])) ? $user_language[$user_selected]['lg_Account_Number'] : $default_language['en']['lg_Account_Number']; ?><span class="error">*<span></label>
								<input class="form-control number" type="text" id="account_number" name="account_number" id="account_number" value="<?php if(!empty($get_details['account_number'])){echo $get_details['account_number'];} ?>" >
							</div>	
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Account_IBAN'])) ? $user_language[$user_selected]['lg_Account_IBAN'] : $default_language['en']['lg_Account_IBAN']; ?><span class="error">*<span></label>
								<input type="text" class="form-control" id="account_iban" name="account_iban" value="<?php if(!empty($get_details['account_iban'])){echo $get_details['account_iban'];} ?>" >
							</div>
							
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Bank_Name'])) ? $user_language[$user_selected]['lg_Bank_Name'] : $default_language['en']['lg_Bank_Name']; ?></label>
								<input type="text" class="form-control" name="bank_name" value="<?php if(!empty($get_details['bank_name'])){echo $get_details['bank_name'];} ?>" >
							</div>
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Bank_Address'])) ? $user_language[$user_selected]['lg_Bank_Address'] : $default_language['en']['lg_Bank_Address']; ?></label>
								<input type="text" class="form-control" name="bank_address" value="<?php if(!empty($get_details['bank_address'])){echo $get_details['bank_address'];} ?>" >
							</div>			
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_IFSC_Code'])) ? $user_language[$user_selected]['lg_IFSC_Code'] : $default_language['en']['lg_IFSC_Code']; ?></label>
								<input type="text" class="form-control" name="account_ifsc" value="<?php if(!empty($get_details['account_ifsc'])){echo $get_details['account_ifsc'];} ?>" >
							</div>

							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Sort_Code'])) ? $user_language[$user_selected]['lg_Sort_Code'] : $default_language['en']['lg_Sort_Code']; ?></label>
								<input type="text" class="form-control" name="sort_code" value="<?php if(!empty($get_details['sort_code'])){echo $get_details['sort_code'];} ?>" >
							</div>
							<div class="form-group col-xl-4">
								<label class="me-sm-2"><?php echo (!empty($user_language[$user_selected]['lg_Routing_No'])) ? $user_language[$user_selected]['lg_Routing_No'] : $default_language['en']['lg_Routing_No']; ?></label>
								<input type="text" class="form-control number" name="routing_number" value="<?php if(!empty($get_details['routing_number'])){echo $get_details['routing_number'];} ?>" >
							</div>
							
							
							<div class="form-group col-xl-12 mt-2">
								<button name="form_submit" id="form_submit" class="btn btn-primary ps-5 pe-5" type="button"><?php echo (!empty($user_language[$user_selected]['lg_update'])) ? $user_language[$user_selected]['lg_update'] : $default_language['en']['lg_update']; ?></button>
							</div>
							<input type="hidden" id="country_id_value" value="<?php echo  isset($provider_address['country_id'])?$provider_address['country_id']:'';?>">
						<input type="hidden" id="state_id_value" value="<?php echo  $provider_address['state_id'];?>">
						<input type="hidden" id="city_id_value" value="<?php echo  $provider_address['city_id'];?>">
						</div>
					</form>
				</div>
			</div>
		</div>
   </div>
</div>

<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Upload_Image'])) ? $user_language[$user_selected]['lg_Upload_Image'] : $default_language['en']['lg_Upload_Image']; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php $curprofile_img = (!empty($profile['profile_img']))?$profile['profile_img']:''; ?>
				<form class="avatar-form" action="<?php echo base_url()?>user/dashboard/profile_cropping" enctype="multipart/form-data" method="post">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
					<div class="avatar-body">
						<div class="avatar-upload">
							<input class="avatar-src" name="avatar_src" type="hidden">
							<input class="avatar-data" name="avatar_data" type="hidden">
							<label for="avatarInput"><?php echo (!empty($user_language[$user_selected]['lg_Select_Image'])) ? $user_language[$user_selected]['lg_Select_Image'] : $default_language['en']['lg_Select_Image']; ?></label>
							<input type="file"  accept="image/x-png,image/gif,image/jpeg,application/pdf, .doc, .xlsx" class="avatar-input ad_pd_file" id="avatarInput" name="profile_img" >
							
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="avatar-wrapper"></div>
							</div>
						</div>
						<div class="row avatar-btns">
							<div class="col-md-12">
								<input type="hidden" name="table_name" value="providers">
								<input type="hidden" name="redirect" value="provider-settings">
								<button class="btn btn-primary avatar-save pull-right" type="submit"><?php echo (!empty($user_language[$user_selected]['lg_Save_Changes'])) ? $user_language[$user_selected]['lg_Save_Changes'] : $default_language['en']['lg_Save_Changes']; ?></button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteProviderAccount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Delete Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="msg">Are you Sure Want to Delete Your Account? </p>
				<p id="user_id"></p>
				<p id="user_type"></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success delete_confirm">Yes</a>
				<button type="button" class="btn btn-danger delete_cancel" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
