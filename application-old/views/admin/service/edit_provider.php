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
						<form id="edit_provider" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" class="form-control"  name="user_id" value="<?php echo (!empty($providers['id']))?$providers['id']:''?>" id="user_id">
							<input type="hidden" class="form-control"  name="type" value="<?php echo (!empty($providers['type']))?$providers['type']:''?>" id="type">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
    
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text"  name="name" id="name" value="<?php echo (!empty($providers['name']))?$providers['name']:''?>">
							</div>
							
							
							<div class="form-group">
								<?php $mob_no = '+'.$providers['country_code'].$providers['mobileno']; ?>

								<label>Mobile Number</label><br>
								<input type="hidden" name="country_code" id="country_code" value="<?php echo (!empty($providers['country_code']))?$providers['country_code']:''?>">
								<input class="form-control no_only mobileno" type="text" name="mobileno" id="mobileno" value="<?php echo (!empty($providers['mobileno']))?$mob_no:''?>">
							</div>
							
							<div class="form-group">
								<label>Email</label>
								<input class="form-control" type="text"  name="email" id="email" value="<?php echo (!empty($providers['email']))?$providers['email']:''?>">
							</div>
							<div class="form-group">
								<label>Status</label>
								 <label><input type="radio" name="status" value="1" <?php echo (!empty($providers['status'])&&$providers['status']==1)?'checked':'';?>>Active</label>
								 <label><input type="radio" name="status" <?php echo (!empty($providers['status'])&&$providers['status']==2)?'checked':'';?> value="2">InActive</label>
							</div>
							<div class="mt-4">
								<?php if($user_role==1){?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Submit</button>
							<?php }?>
								<?php
								if($providers['type'] == 1){
									$url = 'service-providers';
								} else {
									$url = 'freelances-providers';
								}
								?>
								<a href="<?php echo $base_url; ?><?php echo $url; ?>"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

