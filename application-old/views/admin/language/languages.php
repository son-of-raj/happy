<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Languages</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white add-button" href="<?php echo base_url()?>add-languages">
						<i class="fas fa-plus"></i>
					</a>
					<a class="btn btn-primary" href="<?php echo base_url(). 'exportlang'?>">
						<i class="fas fa-download me-1"></i>Web Export
					</a>
					<a class="btn btn-primary" href="<?php echo base_url(). 'exportapplang'?>">
						<i class="fas fa-download me-1"></i>App Export
				  </a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<?php  ?>
							<table class="table custom-table mb-0 datatable">
								<thead>
									<tr>
										<th>#</th>
										<th>Language</th>
										<th>Code</th>
										<th>RTL</th>
										<th>Default Language</th>
										<th>Status</th>
										<th class="text-center">Action</th>
										<th class="text-center">Import</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; foreach ($language as $lang) { 
										if($lang->language_value == 'en') { 
                                			$attr = 'disabled';
                                		} else {
                                			$attr = '';
                                		} ?>
									<tr>
										<td><?php echo $i; ?></td>
										<td><?php echo $lang->language; ?></td>
										<td><?php echo $lang->language_value; ?></td>
										<td>
											<div>
												<div class="status-toggle">
													<input  id="tag_<?php echo $lang->id; ?>" class="check language_tag" data-id="<?php echo $lang->id; ?>" type="checkbox" <?php if($lang->tag == 'rtl') { echo 'checked'; } ?> >
													<label for="tag_<?php echo $lang->id; ?>" class="checktoggle">checkbox</label>
												</div>
											</div> 
		                                </td>
		                                <td>
											<div>
												<div class="status-toggle">
													<input  id="default_<?php echo $lang->id; ?>" class="check default_lang" data-id="<?php echo $lang->id; ?>" data-status="<?php echo $lang->default_language; ?>" type="checkbox" <?php if($lang->default_language == 1) { echo 'checked'; } ?>>
													<label for="default_<?php echo $lang->id; ?>" class="checktoggle">checkbox</label>
												</div>
											</div> 
		                                </td>
		                                <td>
											<div>
												<div class="status-toggle" disabled>
													<input <?php echo $attr; ?> id="status_<?php echo $lang->id; ?>" class="check language_status" data-id="<?php echo $lang->id; ?>" type="checkbox" <?php if($lang->status == 1) { echo 'checked'; } ?>>
													<label for="status_<?php echo $lang->id; ?>" class="checktoggle" disabled>checkbox</label>
												</div>
											</div> 
		                                </td>
										<td class="text-end">
											<a class="btn btn-sm bg-info-light me-2" href="<?php echo base_url().'web-languages/'.$lang->language_value;?>" title="Web Translation">
												<i class="fas fa-language me-1"></i>Web
											</a>
											<a class="btn btn-sm bg-warning-light me-2" href="<?php echo base_url().'app-page-list/'.$lang->language_value;?>" title="App Translation">
												<i class="fas fa-language me-1"></i>App
											</a>
											<a href="<?php echo base_url().'edit-languages/'.$lang->language_value;?>" class="btn btn-sm bg-success-light me-2" title="Edit">
												<i class="far fa-edit me-1"></i>Edit
											</a>
											<?php if($lang->language_value != 'en') { ?>
												<a href="#" class="btn btn-sm bg-danger-light me-2 delete_language" data-id="<?php echo $lang->language_value; ?>">
													<i class="far fa-trash-alt me-1"></i> Delete
												</a>
											<?php } ?>
											<!-- <a class="btn btn-primary lang_code" data-bs-toggle="modal" data-bs-target="#importmodal" data-lang="<?php echo $lang->language_value; ?>" >
											<i class="fas fa-cloud-upload-alt me-1"></i>Import</a> -->
										</td>
										<td>
											<a class="btn btn-primary btn-sm lang_code" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#importmodal" data-lang="<?php echo $lang->language_value; ?>" >
											<i class="fas fa-cloud-upload-alt me-2"></i>Web</a>
											<a class="btn btn-primary btn-sm lang_app_code" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#importappmodal" data-lang="<?php echo $lang->language_value; ?>" >
											<i class="fas fa-cloud-upload-alt me-2"></i>App</a>
										</td>
									</tr>
									<?php $i++; } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal" id="importmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Web Language File Upload</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(). 'admin/language/importlang'?>" method="post" enctype="multipart/form-data">
        	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        	<input type="file" id="add_language" name="add_language" placeholder="Select file" required accept=".csv">
        	<input type="hidden" name="lang_code" id="code_value">
      </div>
      <div class="modal-footer">
        <button type="submit" id="confirm_delete_subs" data-bs-id="" class="btn btn-primary">Confirm</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="importappmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5><?php echo(!empty($admin_settings['lg_amdin_app_language_upload']))?($admin_settings['lg_amdin_app_language_upload']) : 'App Language File Upload';  ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="<?php echo base_url(). 'admin/language/importapplang'?>" method="post" enctype="multipart/form-data">
        	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
        	<input type="file" id="add_app_language" name="add_app_language" placeholder="Select file" required>
        	<input type="hidden" name="lang_code" id="code_app_value">
      </div>
      <div class="modal-footer">
        <button type="submit" id="confirm_delete_sub" data-id="" class="btn btn-primary"><?php echo(!empty($admin_settings['lg_admin_confirm']))?($admin_settings['lg_admin_confirm']) : 'Confirm';  ?></button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo(!empty($admin_settings['lg_admin_cancel']))?($admin_settings['lg_admin_cancel']) : 'Cancel';  ?></button>
        </form>
      </div>
    </div>
  </div>
</div>