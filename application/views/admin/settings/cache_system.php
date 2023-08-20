<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title">Cache Settings</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<div class="row">
			<div class=" col-lg-6 col-sm-12 col-12">
				<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="product_cache" action="<?php echo base_url('admin/settings/cache_settings'); ?>">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
					<div class="card">
						<div class="card-header">
							<div class="card-heads">
								<h4 class="card-title">Service Cache System</h4>
								<div class="col-auto">
									<div class="status-toggle mr-3">
	                                    <input  id="pro_cache_status" class="check" type="checkbox" name="pro_cache_status"<?=settingValue('pro_cache_status')?'checked':'';?>>
	                                    <label for="pro_cache_status" class="checktoggle">checkbox</label>
	                        		</div>
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="form-group">
								<label>Refresh Cache Files When Database Changes</label><br>
								 <label><input type="radio" name="refresh_cache" value="1" <?=(!empty(settingValue('pro_cache_status'))&&settingValue('pro_cache_status')==1)?'checked':'';?>> Yes </label>&nbsp
								 <label><input type="radio" name="refresh_cache" value="0" <?=(settingValue('pro_cache_status')==0)?'checked':'';?>> No</label>
							</div>
			                <div class="form-group">
	                            <label>Cache Refresh Time (Minute)</label>
	                            <small>(After this time, your cache files will be refreshed.)</small>
	                            <input type="number" class="form-control" name="cache_refresh_time" value="<?php echo settingValue('cache_refresh_time'); ?>">
			                </div>
			                <?php if($this->session->userdata('role') == 1) { ?>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>

									<a href="<?php echo base_url(); ?>admin/settings/clear_all_cache"  class="btn btn-cancel">Reset</a>
								</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>

			<div class=" col-lg-6 col-sm-12 col-12 d-flex">
				<div class="card flex-fill">
					<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="chat" action="<?php echo base_url('admin/settings/static_cache_system'); ?>">
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
						<div class="card-header">
							<div class="card-heads">
								<h4 class="card-title">Static Content Cache System</h4>
							</div>
						</div>
						<div class="card-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
		                            	<label>Status</label>
		                            </div>
		                            <div class="col-sm-6">
			                            <div class="status-toggle mr-3">
			                                    <input  id="static_content_cache_system" class="check" type="checkbox" name="static_content_cache_system"<?=settingValue('static_content_cache_system')?'checked':'';?>>
			                                    <label for="static_content_cache_system" class="checktoggle">checkbox</label>
			                        	</div>
			                        </div>
	                        	</div>
			                </div>
			                <?php if($this->session->userdata('role') == 1) { ?>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>

									<a href="<?php echo base_url(); ?>admin/settings/clear_all_cache"  class="btn btn-cancel">Reset</a>
								</div>
							<?php } ?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>