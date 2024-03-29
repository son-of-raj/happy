<?php 
$user_id = $this->uri->segment('2');
$user_details = $this->db->where('id',$user_id)->get('providers')->row_array();


?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Vendor Details</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-lg-4">
				<div class="card">
					<div class="card-body text-center">
						<?php if($user_details['profile_img'] != '' && file_exists($user_details['profile_img']))
						{?>
						<img class="rounded-circle img-fluid mb-3" alt="User Image" src="<?php echo $base_url.$user_details['profile_img'] ?>">
						<?php } else { ?>
						<img class="rounded-circle img-fluid mb-3" alt="User Image" src="<?php echo $base_url?>assets/img/user.jpg">
						<?php } ?>
						<h5 class="card-title text-center">
							<span>Account Status</span>
						</h5>
						<?php
						if($user_details['status']==1) {
							$val='checked';
						}
						else {
							$val='';
						}
						?>
						<?php if($user_details['status'] == 1) { ?>
						<button class="btn btn-success" type="button"><i class="fas fa-user-check"></i> Active</button>
						<?php } else { ?>
						<button class="btn btn-danger" type="button"><i class="fas fa-user-check"></i> Inactive</button>
						<?php } ?>
					</div>
				</div>
				<div class="card">
                    <div class="card-body">
                        <h5 class="card-title d-flex justify-content-between">
                            <span>Commercial Register Document</span>
                        </h5>
                        <div class="row text-center">
							<?php
							if(file_exists($user_details['commercial_reg_image'])) {
								$tmp = explode('.', $user_details['commercial_reg_image']);
								$extension = end($tmp);
								if($extension == 'pdf') { ?>
								<p class="col-sm-12"><a href="<?php echo  base_url() .  $user_details['commercial_reg_image']; ?>" download=""><span><i class="fa fa-file-pdf" aria-hidden="true"> Download PDF</i></span></a></p>
							<?php } else { ?> 
							<p class="col-sm-12"><a href="<?php echo  base_url() .  $user_details['commercial_reg_image']; ?>" download=""><img width="80%" height="120px" src="<?php echo  base_url() .  $user_details['commercial_reg_image']; ?>"></a></p>
						   <?php } } else {  ?>
							<p class="col-sm-12"><img width="80%" height="120px" src="<?php echo  base_url() .  'uploads/commercial_reg_image/commercial_register_placeholder.png'; ?>"></p>
							<?php } ?>
						</div>
						<div class="row text-center">
                            <div class="col-12">
								<?php
								if(file_exists($user_details['commercial_reg_image'])) {
									$disable_status = '';
								} else {
									$disable_status = 'disabled';
								}
								?>
                                <?php if ($user_details['commercial_verify'] == 2) { ?>
                                    <button class="btn btn-success" type="button"><i class="fas fa-check"></i> Verified</button>
                                <?php } else { ?>
                                    <a href="javascript:void(0);" class="verify_provider_commercial" data-id="<?php echo $user_details['id']; ?>"><button class="btn btn-danger" <?php echo $disable_status; ?> type="button"><i class="fas fa-user-check"></i> Verify</button></a>
								<?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="col-lg-8">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Personal Details</span>
						</h5>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
							<p class="col-sm-9"><?php echo $user_details['name']?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
							<p class="col-sm-9"><?php echo $user_details['email']?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
							<p class="col-sm-9"><?php echo $user_details['country_code']?>-<?php echo $user_details['mobileno']?></p>
						</div>
					</div>
				</div> 
				
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Account Details</span>
						</h5>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Account holder name</p>
							<p class="col-sm-9"><?php echo (isset($user_details['account_holder_name']))?$user_details['account_holder_name']:'-'?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Account Number</p>
							<p class="col-sm-9"><?php echo (isset($user_details['account_number']))?$user_details['account_number']:'-'?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">IBAN Number</p>
							<p class="col-sm-9"><?php echo (isset($user_details['account_iban']))?$user_details['account_iban']:'-';?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Bank Name</p>
							<p class="col-sm-9 mb-0"><?php echo (isset($user_details['bank_name']))?$user_details['bank_name']:'-';?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Bank Address</p>
							<p class="col-sm-9 mb-0"><?php echo (isset($user_details['bank_address']))?$user_details['bank_address']:'-';?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Sort Code</p>
							<p class="col-sm-9 mb-0"><?php echo (isset($user_details['sort_code']))?$user_details['sort_code']:'-';?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Swift Code</p>
							<p class="col-sm-9 mb-0"><?php echo (isset($user_details['routing_number']))?$user_details['routing_number']:'-';?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">IFSC Code</p>
							<p class="col-sm-9 mb-0"><?php echo (isset($user_details['account_ifsc']))?$user_details['account_ifsc']:'-';?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>