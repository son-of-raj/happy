<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Pages List</h3>
				</div>
				<div class="col-auto text-right">
					<a href="<?php echo $base_url; ?>admin/pages-list" class="btn btn-primary add-button"><i class="fas fa-sync"></i></a>
					<a href="<?php echo $base_url; ?>admin/add-pages" class="btn btn-primary add-button"><i class="fas fa-plus"></i></a>
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table custom-table mb-0 datatable" id="pages_status">
								<thead>
									<tr>
										<th># </th>
										<th>Title</th>
										<th>Language</th>
										<th>Location</th>
										<th>Visibility</th>
										<th>Date</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i = 1;
										foreach ($pages as $page) {
										$this->db->where('id', $page['lang_id']);
							            $lang_name = $this->db->get('language')->row_array();

							            if ($page['location'] == 1) {
							            	$location = 'Top Menu';
							            } else {
							            	$location = 'Quick Links';
							            }

							           if(!empty($page['created_at'])){
											$date=date(settingValue('date_format'), strtotime($page['created_at']));
								  		}else{
											$date='-';
										}
										if($page['visibility']==1) {
										$val='checked';
									}
									else {
										$val=''; 
									}
									 ?>
									<tr>
										<td><?php echo $i++;?></td>
										<td><?php echo(!empty($page['title']))?($page['title']) : 'No Records Found'?></td>
										<td><?php echo(!empty($lang_name['language']))?($lang_name['language']) : 'No Records Found'?></td>
										<td><?php echo(!empty($location))?($location) : 'No Records Found'?></td>
										<td>
											<div class="status-toggle">
											<input id="pages_list_status<?php echo $page['id'] ?>" class="pages_list_status check" data-id="<?php echo $page['id'] ?>" type="checkbox" <?php echo $val ?>>
											<label for="pages_list_status<?php echo $page['id'] ?>" class="checktoggle">checkbox</label>
										</div>
										</td>
										<td><?php echo(!empty($date))?($date) : 'No Records Found'?></td>
										<td >
											<a href="<?php echo $base_url; ?>admin/edit-pages/<?php echo $page['id']; ?>" class="btn btn-sm bg-success-light me-2">
												<i class="far fa-edit me-1"></i>Edit
											</a>

											<a class='btn btn-sm bg-danger-light delete_show' id="pages_del" data-id="<?php  echo $page['id']; ?>"><i class="far fa-trash-alt"></i>Delete</a>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="pages_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you confirm to delete this page?</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_delete_pages" data-id="" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>