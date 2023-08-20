<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Add Language</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
					
						<!-- Form -->
						<form accept-charset="utf-8" id="language_settings" action="" method="POST" enctype="multipart/form-data">
                            	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" type="text" placeholder="english" name="language" id="language">
							</div>
							<div class="form-group">
								<label>Code</label>
								<input class="form-control" type="text" placeholder="en" name="language_value" id="language_value">
							</div>
							<div class="form-group">
								<label class="d-block"> Status</label>
								<div class="form-check form-check-inline form-radio">
									<input class="form-check-input" type="radio" name="status" id="status" value="1" id="language_active" checked>
									<label class="form-check-label" for="language_active">
										Active
									</label>
								</div>
								<div class="form-check form-check-inline form-radio">
									<input class="form-check-input" type="radio" name="status" id="status" value="2" id="language_inactive" >
									<label class="form-check-label" for="language_inactive">
										Inactive
									</label>
								</div>
							</div>
							<div class="form-group">
                                <label>RTL or LTR (optional)</label>
                                <select class="form-control select" name="tag" id="tag">
                                    <option value="">Select Tag</option>
                                    <option value="rtl" selected>RTL</option>
                                    <option value="ltr">LTR</option>
                                    
                                </select>
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