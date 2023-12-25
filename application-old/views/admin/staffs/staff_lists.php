<?php
   $staff_details = $this->db->where('delete_status', 0)->get('employee_basic_details')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Staffs</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>staff-lists" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
					
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Staff Name</label>
								<select class="form-control select" name="username">
									<option value="">Select Staff name</option>
									<?php foreach ($staff_details as $user) { ?>
									<option value="<?php echo $user['first_name']." ".$user['last_name']?>"><?php echo $user['first_name']." ".$user['last_name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Email</label>
								<select class="form-control select" name="email">
									<option value="">Select email</option>
									<?php foreach ($staff_details as $user) { ?>
									<option value="<?php echo $user['email']?>"><?php echo $user['email']?></option>
									<?php } ?>
								</select>
							</div>
						</div>						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="from">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="to">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
                        <div class="table-responsive staff-lists">
                            <table class="table custom-table mb-0 w-100 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
										<th>Provider Name</th>
                                        <th>Staff Name</th>
										<th>Status</th>
                                    	<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php 
									if(!empty($list)) {
									$i=1;
									foreach ($list as $k => $rows) { 
										if($rows['status']==1) {
											$val='checked';
											$tag='data-toggle="tooltip" title="Click to Deactivate Staff ..!"';
										} else {
											$val='';
											$tag='data-toggle="tooltip" title="Click to Activate Staff ..!"';
										}
										
										$attr='';
										
										$profile_img ='assets/img/user.jpg';
										if(!empty($rows['profile_img']) && file_exists($rows['profile_img'])){
											$profile_img = $rows['profile_img'];
										}
										if($rows['experience'] == '30+') {
											$exp = $rows['experience'].'Yrs'; 
										} else {
											if($rows['exp_month'] != '0') { 
												$exp = $rows['experience']. '.'.$rows['exp_month'].'Yrs'; 
											} else {
												$exp = $rows['experience'].'Yrs'; 
											}
										}
										
										$user_details = $this->db->select('name')->where('id',$rows['provider_id'])->get('providers')->row_array();  
										
										if($this->session->userdata('admin_id') == 1) { 
											$display_data = '';
											$display_status = '';
										} else {
											$display_data = 'd-none';
											$display_status = 'disabled';
										}

										echo'<tr>
											<td>'.$i++.'</td>
											<td>'.$user_details['name'].'</td>
											<td><h2 class="table-avatar"><a href="#" class="avatar avatar-sm me-2"> <img class="avatar-img rounded-circle" src="'.base_url().$profile_img.'"></a>
											<a href="'.base_url().'view-staff-details/'.$rows['id'].'">'.str_replace('-', ' ', $rows['first_name']." ".$rows['last_name']).'</a></h2></td>
											<td>
											<div '.$tag.'>
												<div class="status-toggle mb-2">
													<input '.$attr.' id="status_'.$rows['id'].'" class="check change_Status_Staff" data-id="'.$rows['id'].'" data-provider_id="'.$rows['provider_id'].'" type="checkbox" '.$val.' '.$display_status.'>
													<label for="status_'.$rows['id'].'" class="checktoggle">checkbox</label>
												</div>
											</div>
                                            </td>
                                            <td>
											<a href="'.base_url().'staff-edit/'.$rows['id'].'" class="btn btn-sm bg-success-light me-2"><i class="far fa-edit me-1"></i> Edit</a>
											<a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light me-2 delete_staff_data '.$display_data.'" id="Onremove_'.$rows['id'].'" data-id="'.$rows['id'].'" data-provider_id="'.$rows['provider_id'].'" ><i class="far fa-trash-alt me-1"></i> Delete</a>
											</td>
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