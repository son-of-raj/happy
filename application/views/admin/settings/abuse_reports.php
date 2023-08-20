<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Abuse Reports</h3>
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
										<th>Provider</th>
										<th>Reported By User</th>
										<th>description</th>
										<th>Created At</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
										$i=1;
										
										foreach ($list as $row) {
										$provider_name=$this->db->where('id',$row['pro_id'])->from('providers')->get()->row_array();
										$user_name=$this->db->where('id',$row['report_user_id'])->from('users')->get()->row_array();
									?>
									<tr>
										<td> <?php echo $i++; ?></td>
										<td> <?php echo $provider_name['name']; ?></td>
										<td> <?php echo $user_name['name']; ?></td>
										<td> <?php echo $row['description']; ?></td>
										<td> <?php echo $row['created_at']; ?></td>

										<td> <a href="<?php echo base_url().'abuse-details/' . $row['id']; ?>" class="btn btn-sm bg-info-light">
										<i class="far fa-eye mr-1"></i> View
									</a></td>
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

<div class="modal" id="abuse_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Are you confirm to Delete.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" id="abuse_desc"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_abuse_sub" data-userid="<?php echo $this->session->userdata('id'); ?>" data-id="" class="btn btn-primary">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>