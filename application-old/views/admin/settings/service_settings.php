<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title">Service Settings</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class=" col-lg-8 col-sm-12 col-12">
				<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="reviews" action="<?php echo base_url('admin/service-settings'); ?>">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
					<div class="card">
						<div class="card-header">
							<div class="card-heads">
								<h4 class="card-title">Review Details</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="review_showhide" class="check" type="checkbox" name="review_showhide"<?=settingValue('review_showhide')?'checked':'';?>>
	                                    <label for="review_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Booking Service</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="booking_showhide" class="check" type="checkbox" name="booking_showhide"<?=settingValue('booking_showhide')?'checked':'';?>>
	                                    <label for="booking_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Additional Services</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="service_offered_showhide" class="check" type="checkbox" name="service_offered_showhide"<?=settingValue('service_offered_showhide')?'checked':'';?>>
	                                    <label for="service_offered_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Service Availability</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="service_availability_showhide" class="check" type="checkbox" name="service_availability_showhide"<?=settingValue('service_availability_showhide')?'checked':'';?>>
	                                    <label for="service_availability_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Provider Email</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="provider_email_showhide" class="check" type="checkbox" name="provider_email_showhide"<?=settingValue('provider_email_showhide')?'checked':'';?>>
	                                    <label for="provider_email_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Provider Mobile Number</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="provider_mobileno_showhide" class="check" type="checkbox" name="provider_mobileno_showhide"<?=settingValue('provider_mobileno_showhide')?'checked':'';?>>
	                                    <label for="provider_mobileno_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
							<br>
							<div class="card-heads">
								<h4 class="card-title">Provider Status</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="provider_status_showhide" class="check" type="checkbox" name="provider_status_showhide"<?=settingValue('provider_status_showhide')?'checked':'';?>>
	                                    <label for="provider_status_showhide" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
						</div>
						<div class="card-body">
			                <?php if($this->session->userdata('role') == 1) { ?>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
								</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>