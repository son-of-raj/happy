<div class="page-wrapper">
			<div class="content container-fluid">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-12">
							<h3 class="page-title">SEO Settings</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				<form accept-charset="utf-8" id="seo_settings" action="" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
					<div class="row">
						<div class=" col-lg-6 col-sm-12 col-12">
							<div class="card">
								<div class="card-body">
									<div class="form-group">
										<label>Meta Title <span class="manidory">*</span></label>
										<input type="text" class="form-control" name="meta_title" pattern="[A-Za-z0-9]+" id="meta_title" value="<?php if (isset($meta_title)) echo $meta_title; ?>" required>
									</div>
									<div class="form-group">
										<label>Meta Keywords <span class="manidory">*</span></label>
										<input type="text" data-role="tagsinput" class="input-tags form-control"  name="meta_keyword"  id="services" value="<?php if (isset($meta_keyword)) echo $meta_keyword; ?>" required>
									</div>
									<div class="form-group">
										<label>Meta Description  <span class="manidory">*</span></label>
										<textarea class="form-control" name="meta_desc" id="meta_desc" value="<?php if (isset($meta_desc ))  ?>" required><?php echo $meta_desc ;?></textarea>
									</div>
									<?php if($this->session->userdata('role') == 1) { ?>
										<div class="form-groupbtn">
											<button name="form_submit" type="submit" class="btn btn-primary me-2" value="true">Update</button>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>