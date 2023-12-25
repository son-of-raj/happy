<div class="page-wrapper">
			<div class="content container-fluid">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-12">
							<h3 class="page-title">System Settings</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="row">
					<div class=" col-lg-6 col-sm-12 col-12">
						<form accept-charset="utf-8" id="map_settings" action="" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
							<div class="card">
								<div class="card-header">
									<div class="card-heads">
										<h4 class="card-title">Google Map API Key</h4>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label>Google Map API Key</label>
										<input type="text" name="map_key" id="map_key" class="form-control" value="<?php echo ($map_key)?$map_key:''; ?>">
									</div>
									<div class="form-group">
										<div class="form-links">
											<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">How to create google map API key?</a>
										</div>
									</div>
									<?php if($this->session->userdata('role') == 1) { ?>
										<div class="form-groupbtn">
											<button name="form_submit" type="submit" class="btn btn-primary me-2" value="true">Update</button>
										</div>
									<?php } ?>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>