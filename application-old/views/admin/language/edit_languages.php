<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Edit Language</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
					
						<!-- Form -->
						<form accept-charset="utf-8" id="language_settings" action="" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input type="hidden" name="lang_id" class="form-control" id="lang_id" value="<?php echo $lang_details->id; ?>">
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" name="language" id="language" placeholder="english" value="<?php echo ($lang_details)?$lang_details->language:''; ?>">
							</div>
							<div class="form-group">
								<label>Code</label>
								<input class="form-control" type="text" name="language_value" id="language_value" placeholder="en" value="<?php echo ($lang_details)?$lang_details->language_value:''; ?>">
							</div>
							<div class="form-group">
								<label class="d-block"> Status</label>
								<div class="form-check form-check-inline form-radio">
									<input class="form-check-input" type="radio" name="status" value="1" id="language_active" <?php if($lang_details->status == '1') { echo 'checked'; } ?>>
									<label class="form-check-label" for="language_active">
										Active
									</label>
								</div>
								<div class="form-check form-check-inline form-radio">
									<input class="form-check-input" type="radio" name="status" value="2" id="language_inactive" <?php if($lang_details->status == '2') { echo 'checked'; } ?>>
									<label class="form-check-label" for="language_inactive">
										Inactive
									</label>
								</div><br><br>

								<div class="form-group">
	                                <label>RTL or LTR (optional)</label>
	                                <select class="form-control select" name="tag" id="tag">
	                                    <option value="">Select Tag</option>
	                                    <option value="rtl" <?php if($lang_details->tag == 'rtl') { echo 'selected'; } ?>>RTL</option>
	                                    <option value="ltr" <?php if($lang_details->tag == 'ltr') { echo 'selected'; } ?>>LTR</option>
	                                    
	                                </select>
	                            </div>
							</div>
							<div class="mt-4">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Submit</button>
								<a href="<?php echo $base_url; ?>languages"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
						<!-- Form -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>