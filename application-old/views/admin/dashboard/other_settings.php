<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title">Other Settings</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class=" col-lg-6 col-sm-12 col-12">
				<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="google_analytics" action="<?php echo base_url('admin/settings/analytics'); ?>">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
				<div class="card">
					<div class="card-header">
						<div class="card-heads">
							<h4 class="card-title">Enable Google Analytics</h4>
							<div class="col-auto">
								<div class="status-toggle mr-3">
                                    <input  id="analytics_showhide" class="check" type="checkbox" name="analytics_showhide"<?php echo settingValue('analytics_showhide')?'checked':'';?>>
                                    <label for="analytics_showhide" class="checktoggle">checkbox</label>
                        		</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label>Google Analytics</label>
							<textarea class="form-control" placeholder="Google Analytics" name="google_analytics"><?php echo settingValue('google_analytics'); ?></textarea>
						</div>
						<?php if($this->session->userdata('role') == 1) { ?>
							<div class="form-groupbtn">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
							</div>
						<?php } ?>
					</div>
				</div>
			</form>
			</div>
			<div class=" col-lg-6 col-sm-12 col-12 d-flex">
				<div class="card flex-fill">
					<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="cookies" action="<?php echo base_url('admin/settings/cookies'); ?>">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
					<div class="card-header">
						<div class="card-heads">
							<h4 class="card-title">Cookies Agreement</h4>
							<div class="col-auto">
								<div class="status-toggle mr-3">
                                    <input  id="cookies_showhide" class="check" type="checkbox" name="cookies_showhide"<?php echo settingValue('cookies_showhide')?'checked':'';?>>
                                    <label for="cookies_showhide" class="checktoggle">checkbox</label>
                        		</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label>Google Adsense Code</label>
							<textarea class="form-control summernote" placeholder="Cookies" name="cookies"><?php echo settingValue('cookies'); ?></textarea>
							<div id="editor"></div>
						</div>
						<?php if($this->session->userdata('role') == 1) { ?>
							<div class="form-groupbtn">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
							</div>
						<?php } ?>
					</div>
					</form>
				</div>
			
			</div>
		</div>
	</div>
</div>