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
						<form id="add_user" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="id" value="" id="user_id">
							<input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
    
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text"  name="name" id="name" value="">
							</div>
							<div class="form-group">
								<label>Mobile Number</label><br>
								<input type="hidden" name="country_code" id="country_code" value="<?php echo (!empty($user['country_code']))?$user['country_code']:''?>">
								<input class="form-control no_only mobileno" type="text" name="mobileno" id="mobileno">
							</div>
							<div class="form-group">
								<label>Email</label>
								<input class="form-control" type="email"  name="email" id="email" value="">
							</div>
							<div class="form-group">
								<label>Status</label>
								 <label><input type="radio" name="status" value="1" checked>Active</label>
								 <label><input type="radio" name="status"value="2">InActive</label>
							</div>
							<div class="mt-4">
								<?php if($user_role == 1) { ?>
									<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
								<?php } ?>
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