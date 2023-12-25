<?php
   $user_details = $this->db->get('administrators')->result_array();
  
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Push Notification List</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
					<a href="<?php echo base_url().'admin/SendPushNotification/';?>"><button class="btn btn-primary">Add</button></a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
	
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
                        <div class="table-responsive">
                            <table class="custom-table table table-hover table-center mb-0 w-100" id="pushnot_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Message</th>
										<th>Send To</th>
										<th>Date</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									if(!empty($list)) {
										$i=1;
										$user='';
										$provider='';
										foreach ($list as $rows) {
										if(!empty($rows['created_on'])){
											$date=date(settingValue('date_format'). ' H:i:s', strtotime($rows['created_on']));
										}else{
											$date='-';
										}

										if($rows['user_status']==1)
										{
											$user='Users';
										}
										if($rows['provider_status']==1)
										{
											$provider='Providers';
										}
										 $base_url=base_url()."adminusers/edit/".$rows['id'];

										$action="<a href='".$base_url."'' class='btn btn-sm bg-success-light me-2'>
      <i class='far fa-edit me-1'></i> Edit
      </a>
      <a class='btn btn-sm bg-info-light delete_show' data-id='".$rows['id']."'><i class='fa fa-trash' ></i> Delete</a>";
					echo'<tr>
					<td>'.$i++.'</td>
			
			<td>'.$rows['subject'].'</td>
			<td>'.$rows['message'].'</td>
				<td>'.$user.'/'.$provider.'</td>
				<td>'.$date.'</td>
										</tr>';
										}
                                    }
                                   
									?>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confirmation</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you confirm to Delete.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_btn_admin" data-id="" class="btn btn-primary">Confirm</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
