<?php
$category = $this->db->get('categories')->result_array();
$subcategory = $this->db->get('subcategories')->result_array();
$services = $this->db->get('services')->result_array();
$user_list = $this->db->select('id,name')->get('users')->result_array();
$provider_list = $this->db->select('id,name')->get('providers')->result_array();
$servie_staus = array(
// array("id"=>1,'value'=>'Pending'),
array("id"=>2,'value'=>'Inprogress'),
array("id"=>3,'value'=>'Provider complete'),
array("id"=>4,'value'=>'User accept'),
array("id"=>5,'value'=>'Reject'),
array("id"=>6,'value'=>'Complete'),
array("id"=>7,'value'=>'Cancel')
);
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Booking List</h3>
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
		<form action="<?php echo base_url();?>admin/total-report" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Service Title</label>
								<select class="form-control select" id="service_id_f" name="service_title">
									<option value="">Select service</option>
									<?php foreach ($services as $pro) {
									if(!empty($filter) && $filter['service_t']==$pro['id']){
										$select='selected';
									}else{
										$select='';
									}
									?>
									<option <?php echo $select;?> value="<?php echo $pro['id']?>"><?php echo $pro['service_title']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Status</label>
								<select class="form-control select" id="status_f" name="service_status">
									<option value="">Select Status</option>
									<?php foreach ($servie_staus as $pro) {
									if(!empty($filter) && $filter['service_s']==$pro['id']){
										$select='selected';
									}else{
										$select='';
									}
									?>
									<option <?php echo $select;?> value="<?php echo $pro['id']?>"><?php echo $pro['value']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">User</label>
								<select class="form-control select" id="user_id_f" name="user_id">
									<option value="">Select User</option>
									<?php foreach ($user_list as $pro) {
									if(!empty($filter) && $filter['user_i']==$pro['id']){
										$select='selected';
									}else{
										$select='';
									}
									?>
									<option <?php echo $select;?> value="<?php echo $pro['id']?>"><?php echo ucfirst($pro['name']);?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">Provider</label>
								<select class="form-control select" id="provider_id_f" name="provider_id">
									<option value="">Select Provider</option>
									<?php foreach ($provider_list as $pro) {
									if(!empty($filter) && $filter['provider_i']==$pro['id']){
										$select='selected';
									}else{
										$select='';
									}
									?>
									<option <?php echo $select;?> value="<?php echo $pro['id']?>"><?php echo ucfirst($pro['name']);?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">From Date</label>
								<div class="cal-icon">
									<?php
									if(!empty($filter['service_from'])){
										$fr_date=$filter['service_from'];
									}else{
										$fr_date='';
									}
									if(!empty($filter['service_to'])){
										$to_date=$filter['service_to'];
									}else{
										$to_date='';
									}
									?>
									<input class="form-control datetimepicker" type="text" id="from_f" name="from" value="<?php echo $fr_date;?>">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="col-form-label">To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id="to_f" name="to" value="<?php echo $to_date;?>">
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

		<ul class="nav nav-tabs menu-tabs">
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url().'admin/total-report'; ?>">All Booking <span class="badge badge-primary"><?php echo $all_booking;?></span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/pending-report'; ?>">Pending <span class="badge badge-primary"><?php echo $pending;?></span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/inprogress-report'; ?>">InProgress <span class="badge badge-primary"><?php echo $inprogress;?></span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/complete-report'; ?>">Completed <span class="badge badge-primary"><?php echo $completed;?></span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/reject-report'; ?>">Rejected <span class="badge badge-primary"><?php echo $rejected;?></span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'admin/cancel-report'; ?>">Canceled <span class="badge badge-primary"><?php echo $cancelled;?></span></a>
			</li>
		</ul>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 service_table" >
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>User</th>
                                        <th>Provider</th>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($list)) {
                                    $i=1;
                                    foreach ($list as $rows) {
									$user_image = $rows['user_profile_img'];
									if(empty($user_image)){
										$user_image ='assets/img/user.jpg';
									}
									$provider_image = $rows['provider_profile_img'];
									if(empty($provider_image)){
										$provider_image ='assets/img/user.jpg';
									}
									if(!empty($rows['service_date'])){
										$service_date=date(settingValue('date_format'), strtotime($rows['service_date']));
									}else{
										$service_date='-';
									}
									

									/* time */
									$full_date =date('Y-m-d H:i:s', strtotime($rows['updated_on']));
									$date=date(settingValue('date_format'),strtotime($full_date));
									$date_f=date(settingValue('date_format'),strtotime($full_date));
									$yes_date=date(settingValue('date_format'),(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ));
									$time=date('H:i',strtotime($full_date));
									$session = date('h:i A', strtotime($time));
									if($date == date('Y-m-d')){
										$timeBase ="Today ".$session;
									}elseif($date == $yes_date){
										$timeBase ="Yester day ".$session;
									}else{
										$timeBase =$date_f." ".$session;
									}

									$badge='';
									if ($rows['status']==1) {
										$badge='Pending';
										$color='warning';
									}
									if ($rows['status']==2) {
										$badge='Inprogress';
										$color='info';
									}
									if ($rows['status']==3) {
										$badge='Complete Request';
										$color='primary';
									}
									if ($rows['status']==4) {
										$badge='Accepted';
										$color='success';
									}
									if ($rows['status']==5) {
										$badge='Rejected by User';
										$color='warning';
									} 
									if ($rows['status']==6) {
										$badge='Completed';
										$color='success';
									}
									if ($rows['status']==7) {
										$badge='Cancelled by Provider';
										$color='danger';
									}
									if($rows['admin_change_status'] == 1) {
										$badge = $badge." by Admin";
									}
									//new code
									$statcheck = 0;
									
									if($rows['status'] == 5 || $rows['status'] == 6 ||  $rows['status'] == 7){
										$statcheck = 1;
									}
								
									echo '<tr>
										<td>'.$i++.'</td>
										<td>'.$service_date.'</td>
										<td>
											<h2 class="table-avatar">
												<a href="#" class="avatar avatar-sm me-2">
													<img class="avatar-img rounded-circle" alt="" src="'.base_url().$user_image.'">
												</a>
												<a href="javascript:void(0);">'.str_replace('-', ' ', $rows['user_name']).'</a>
											</h2>
										</td>
										<td>
											<h2 class="table-avatar">
												<a href="#" class="avatar avatar-sm me-2">
													<img class="avatar-img rounded-circle" alt="" src="'.base_url().$provider_image.'">
												</a>
												<a href="javascript:void(0);">'.str_replace('-', ' ', $rows['provider_name']).'</a>
											</h2>
										</td>
										<td>'.$rows['service_title'].'</td>
										<td>'.currency_conversion($rows['currency_code']).$rows['amount'].'</td>
										
										<td><label class="badge badge-'.$color.'">'.ucfirst($badge).'</lable></td>
										<td>'.$timeBase.'</td>';
									
										if($statcheck == 0) {
											
											echo '<td><select class="form-control changebooking" name="ser_status" data-id="'.$rows['id'].'" data-userid="'.$rows['userid'].'" data-providerid="'.$rows['providerid'].'" data-serviceid="'.$rows['serviceid'].'">
												<option value="">Select Status</option>';
												
											foreach ($servie_staus as $pro) { 
												
												if($rows['status']<$pro['id']) {
													echo '<option value="'.$pro['id'].'">'.$pro['value'].'</option>';
												}
											} 
											echo '</select></td>';
										}else {
											echo '<td class="text-center">-</td>';
										}
										echo '</tr>';
									} 
									}  ?>
                                </tbody>
                            </table>
						</div> 
					</div> 
				</div> 
			</div>
		</div>
	</div>
</div>