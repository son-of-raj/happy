<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Privacy Policy</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form id="privacy_policy" class="form-horizontal"  method="POST" enctype="multipart/form-data" >
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
							<div class="form-group">
								
                                <label>Page Title </label>
                                <input type="text" class="form-control" name="page_title" value="<?php echo ($pages[0]->page_title)?$pages[0]->page_title:''; ?>" required>
	                        </div>
	                        <div class="form-group">
                                <label>Page Slug</label>
                                <input type="text" class="form-control" name="page_slug" value="<?php echo ($pages[0]->page_slug)?$pages[0]->page_slug:''; ?>" required readonly>
	                        </div>
							 <div class="form-group">
                                <label>Page Content</label>
							    <textarea class='form-control content-textarea' id='ck_editor_textarea_id2' rows='6' name='page_content'><?php echo ($pages[0]->page_content) ? $pages[0]->page_content:''; ?></textarea>
							    <?php echo display_ckeditor($ckeditor_editor3); ?>
                                    
                              </div>
								<div class="m-t-30 text-center">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
						</form>                    
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

