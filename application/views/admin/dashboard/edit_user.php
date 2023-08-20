<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title"><?php echo $title;?></h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->

				<div class="card">
					<div class="card-body">
						<form id="edit_user" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?php echo (!empty($user['id']))?$user['id']:''?>" id="user_id">
							<input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
    
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text"  name="name" id="name" value="<?php echo (!empty($user['name']))?$user['name']:''?>">
							</div>
							<div class="form-group">
								<?php $mob_no = '+'.$user['country_code'].$user['mobileno']; ?>

								<label>Mobile Number</label><br>
								<input type="hidden" name="country_code" id="country_code" value="<?php echo (!empty($user['country_code']))?$user['country_code']:''?>">
								<input class="form-control no_only umobileno" type="text" name="mobileno" id="mobileno" value="<?php echo (!empty($user['mobileno']))?$mob_no:''?>">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input class="form-control" type="text"  name="email" id="email" value="<?php echo (!empty($user['email']))?$user['email']:''?>">
							</div>
								<div class="form-group">
										<label>Profile Image</label>
										<div class="media align-items-center">
											<div class="media-left">
												<?php
												if (!empty($details['profile_img'])) { ?>
													<img class="rounded-circle" src="<?php echo base_url().$details['profile_img'];?>" width="100" height="100" class="profile-img avatar-view-img">
												<?php  } else {?>
													<img class="rounded-circle" src="<?php echo base_url('assets/img/user.jpg');?>" width="100" height="100" class="profile-img avatar-view-img">
													
												<?php }
												?>
											
											</div>
											<div class="media-body">
												<div class="uploader"><button class="btn btn-secondary btn-sm ml-2 avatar-view-btn">Change profile picture</button>
												<input type="hidden" id="crop_prof_img" name="profile_img">
												</div>
												<span id="image_error" class="text-danger" ></span>
											</div>
										</div>
									</div>
							<div class="form-group">
								<label>Status</label>
								 <label><input type="radio" name="status" value="1" <?php echo (!empty($user['status'])&&$user['status']==1)?'checked':'';?>>Active</label>
								 <label><input type="radio" name="status" <?php echo (!empty($user['status'])&&$user['status']==2)?'checked':'';?> value="2">InActive</label>
							</div>
							<div class="mt-4">
								<?php if($user_role==1){?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
							<?php }?>

								<a href="<?php echo $base_url; ?>users"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Profile Image</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<?php $curprofile_img = (!empty($profile['profile_img']))?$profile['profile_img']:''; ?>
				<form class="avatar-form" action="<?php echo base_url('admin/dashboard/crop_profile_img/'.$curprofile_img)?>" enctype="multipart/form-data" method="post">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
					<div class="avatar-body">
						<!-- Upload image and data -->
						<div class="avatar-upload">
							<input class="avatar-src" name="avatar_src" type="hidden">
							<input class="avatar-data" name="avatar_data" type="hidden">
							<label for="avatarInput">Select Image</label>
							<input class="avatar-input" id="avatarInput" name="avatar_file" type="file" accept="image/*">
							<span id="image_upload_error" class="error" ></span>
						</div>
						<!-- Crop and preview -->
						<div class="row">
							<div class="col-md-12">
								<div class="avatar-wrapper"></div>
							</div>
						</div>
						<div class="mt-4 text-center">
							<button class="btn btn-primary avatar-save upload_images" id="upload_images" type="submit" >Yes, Save Changes</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>