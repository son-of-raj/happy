<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Terms and Conditions</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form class="form-horizontal"  method="POST" enctype="multipart/form-data" >
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
							 <div class="form-group">
                                                <label>Page Content</label>
                                                <?php
										if (!empty($terms)) {
											echo  "<textarea class='form-control content-textarea' id='ck_editor_textarea_id' rows='6' name='terms'>" . $terms . "</textarea>";
											echo display_ckeditor($ckeditor_editor1);
										} else {
											echo "<textarea class='form-control content-textarea' id='ck_editor_textarea_id' rows='6' name='terms'> </textarea>";
											echo display_ckeditor($ckeditor_editor1);
										}
										?>
                                    
                              </div>
							<div class="m-t-30 text-center">
								<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
							</div>
						</form>              
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
