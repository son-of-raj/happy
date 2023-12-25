<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-lg-8 m-auto">
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-12">
							<h3 class="page-title">FAQ</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="row">
					<div class=" col-lg-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-body">
								<form class="form-horizontal"  method="POST" enctype="multipart/form-data" id="faq" >
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"id="active_csrf"/>
									<div class="faq">
										<?php if(!empty($pages)) { 
											$i =1;
				                        	foreach ($pages as $key => $value) { ?>
												<input type="hidden" name="faq_id[]" value="<?php echo $value->id ?>">
												<div class="row counts-list" id="faq_<?php echo $value->id; ?>">
													<div class="col-md-11">
														<div class="cards">
														<div class="form-group">
							                                <label>Page Title </label>
							                                <input type="text" class="form-control" name="page_title[]" value="<?php echo ($value->page_title)?$value->page_title:''; ?>" required>
								                        </div>
														 <div class="form-group">
							                                <label>Page Content</label>
														    <textarea class='form-control content-textarea' id='ck_editor_textarea_id' rows='6' name='page_content[]'><?php echo ($value->page_content) ? $value->page_content:''; ?></textarea>
														   
							                                    
							                              </div>
														</div>
													</div>
													<div class="col-md-1">
														<a href="javascript:;" class="faq_delete on-default btn btn-sm bg-danger-light mr-2 delete_faq_content" id="delete_faq" data-id="<?php echo $value->id; ?>">
															<i class="far fa-trash-alt "></i> 
														</a>
													</div>
												</div>
									 <?php } 
									 	} else { ?>
											<div class="row counts-list" id="faq_content">
												<div class="col-md-11">
													<div class="cards">
														<div class="form-group">
							                                <label>Page Title </label>
							                                <input type="text" class="form-control" name="page_title[]" value="" required>
								                        </div>
														 <div class="form-group">
							                                <label>Page Content</label>
														    <textarea class='form-control content-textarea delete_faq contentz' id='ck_editor_textarea_id' rows='6' name='page_content[]' data-id=""></textarea>
														  
							                              </div>
													</div>
												</div>
												<div class="col-md-1">
													<a href="javascript:;" class="on-default btn btn-sm bg-danger-light mr-2 delete_faq" id="delete_faq">
														<i class="far fa-trash-alt "></i> 
													</a>
												</div>
											</div>
									 <?php } ?>
									</div>
									<div class="form-group">
										<a class="btn  btn-success addfaq"><i class="fa fa-plus me-2"></i>Add New</a>
									</div>
										<div class="form-groupbtn">
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
	</div>
</div>
