<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title">General Settings</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class=" col-lg-6 col-sm-12 col-12">
				<form accept-charset="utf-8" id="general_settings" action="" method="POST" enctype="multipart/form-data">
				<div class="card">
					<div class="card-header">
						<div class="card-heads">
							<h4 class="card-title">Website Basic Details</h4>
						</div>
					</div>
					<div class="card-body">
						<input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
						<div class="form-group">
							<label>Website Name<span class="manidory">*</span></label>
							<input type="text" class="form-control" name="website_name" id="website_name" placeholder="Enter Website Name" value="<?php echo $website_name; ?>">
						</div>
						<div class="form-group">
							<label>Contact Details <span class="manidory">*</span></label>
							<input type="text" class="form-control" name="contact_details" id="contact_details"  placeholder="Enter contact details" value="<?php echo $contact_details; ?>">
						</div>
						<div class="form-group">
							<label>Mobile Number <span class="manidory">*</span></label>
							<input type="text" class="form-control" name="mobile_number" id="mobile_number" placeholder="Enter Mobile Number" value="<?php echo $mobile_number; ?>">
						</div>

						<div class="form-group">
							<label>Language<span class="manidory">*</span></label>
							 <select class="form-control select" name="language" id="language" required>
							 	<option value="">Select Language</option>
                                <?php foreach ($languages as $lang) { ?>
                                    <option value="<?php echo $lang['language_value']; ?>" <?php if ($lang['language_value'] == $language) echo 'selected'; ?>><?php echo $lang['language']; ?>
                                    </option>
                                <?php } ?>
                            </select>
						</div>
						<div class="form-group">
							<label>Commision Percentage <span class="manidory">*</span></label>
							<input type="text" class="form-control" name="commission" id="commission" placeholder="Enter Commission" value="<?php echo $commission; ?>">
						</div>
						<div class="form-group">
							<label>Vat Percentage <span class="manidory">*</span></label>
							<input type="text" class="form-control" name="vat" id="vat" placeholder="Enter Vat Percentage" value="<?php echo $vat; ?>">
						</div>
						<div class="form-group mb-5">
							<label>Service Location Radius</label>
							<div class="loan-set text-center my-2">
								<h3 ><span id="currencys"></span> km</h3>
							</div>
							<input type="range" min="1" max="50" value="<?php echo $radius; ?>" class="slider" id="myRange">
							<input type="hidden" name="radius" id="radius" class="form-control" value="<?php echo $radius; ?>">
						</div>
						<div class="form-group">
							<label class="d-flex">
								<span>Service Location Type</span>
							</label>
							<div class="form-group">
								<div class="row">
								<div class="col-md-3 custom-control custom-radios custom-control-inline">
									<input class="custom-control-input" id="manual" type="radio"  name="location_type" value="manual" <?php if($location_type == 'manual') { echo 'checked'; } ?> >
									<label class="custom-control-label" for="manual"><?php echo(!empty($admin_settings['lg_manual']))?($admin_settings['lg_manual']) : 'Manual';  ?></label>
								</div>
								<div class="col-md-3 custom-control custom-radios custom-control-inline">
									<input class="custom-control-input" id="live" type="radio"  name="location_type" value="live"  <?php if($location_type == 'live') { echo 'checked'; } ?> >
									<label class="custom-control-label" for="live"><?php echo(!empty($admin_settings['lg_live']))?($admin_settings['lg_live']) : 'Live';  ?></label>
								</div>
							</div>
							</div>
						</div>
						<?php if($this->session->userdata('role') == 1) { ?>
							<div class="form-groupbtn">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
							</div>
						<?php } ?>
					</div>
				</div>
			</form>
				<form accept-charset="utf-8" id="placeholder_settings" action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
					<div class="card">
						<div class="card-header">
							<div class="card-heads">
								<h4 class="card-title">Placeholder Image Settings</h4>
							</div>
						</div>
						<div class="card-body">
							<div class="form-group ">
								<p class="settings-label">Service Placeholder <span class="manidory">*</span></p>
									<input type="file" accept="image/*" name="service_placeholder_image" id="service_placeholder_image" class="form-control">
								<h6 class="settings-size">Recommended image size is <span>800px x 600px</span></h6>
								<div class="upload-images">
									<?php if (!empty($service_placeholder_image)) { ?><img src="<?php echo base_url() . $service_placeholder_image ?>" class="site-logo"><?php } else { ?><img src="<?php echo base_url() . 'assets/img/service-placeholder.jpg'; ?>" class="site-logo"> <?php } ?>
								</div>
							</div>
							<div class="form-group mb-0">
								<p class="settings-label">Profile Placeholder<span class="manidory">*</span></p>
									<input type="file" accept="image/*" name="profile_placeholder_image" id="profile_placeholder_image" class="form-control">
								<h6 class="settings-size">Recommended image size is <span>300px x 300px</span></h6>
								<div class="upload-images upload-imagesprofile">
									<?php if (!empty($profile_placeholder_image)) { ?><img src="<?php echo base_url() . $profile_placeholder_image ?>" class="site-logo"><?php } else { ?><img src="<?php echo base_url() .'assets/img/user-placeholder.jpg'; ?>" class="site-logo"> <?php } ?>
								</div>
							</div><br>
							<?php if($this->session->userdata('role') == 1) { ?>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
								</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
			<div class=" col-lg-6 col-sm-12 col-12">
				<form accept-charset="utf-8" id="image_settings" action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
					<div class="card">
						<div class="card-header">
							<div class="card-heads">
								<h4 class="card-title">Image Settings</h4>
							</div>
						</div>
						<div class="card-body">
							<div class="form-group">
								<p class="settings-label">Logo <span class="manidory">*</span></p>
									<input type="file" accept="image/*" name="logo_front" id="logo_front" class="form-control">
								<h6 class="settings-size">Recommended image size is <span>280px x 36px</span></h6>
								<div class="upload-images ">
									<?php if (!empty($logo_front)) { ?><img src="<?php echo base_url() . $logo_front ?>" class="site-logo"><?php } ?>
								</div>
							</div>
							<div class="form-group">
								<p class="settings-label">Favicon <span class="manidory">*</span></p>
									<input type="file" accept="image/*" name="favicon" id="favicon"  class="form-control">
								<h6 class="settings-size">Recommended image size is <span>16px x 16px or 32px x 32px</span></h6>
								<h6 class="settings-size"> Accepted formats: only png and icon</h6>
								<div class="upload-images upload-imagesprofile">
									<?php if (!empty($favicon)) { ?><img src="<?php echo base_url() .'uploads/logo/'.$favicon ?>" class="fav-icon"><?php } ?>
								</div>
							</div>
							<div class="form-group">
								<p class="settings-label">Icon <span class="manidory">*</span></p>
									<input type="file" accept="image/*" name="header_icon" id="header_icon" class="form-control">
								<h6 class="settings-size">Recommended image size is <span>100px x 100px </span></h6>
								<div class="upload-images upload-imagesprofile">
									<?php if (!empty($header_icon)) { ?><img src="<?php echo base_url() . $header_icon ?>" class="fav-icon"><?php } ?>
								</div>
							</div>
							<?php if($this->session->userdata('role') == 1) { ?>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
								</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>