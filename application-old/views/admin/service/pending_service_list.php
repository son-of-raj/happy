<?php
	$providers = $this->db->where('status',1)->get('providers')->result_array();
	$users = $this->db->where('status',1)->get('users')->result_array();
	$services = $this->db->where('status!=',0)->get('services')->result_array();
		//echo "<pre>";print_r($list);exit;

?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Pending Services</h3>
				</div>
				<div class="col-auto text-right">
					<a href="<?php echo $base_url; ?>admin/pending-service-list" class="btn btn-primary add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn mr-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin/pending-service-list" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Service Title</label>
								<select class="form-control" name="service_title" id="service_title">
									<option value="">Select Service</option>
									<?php foreach ($list as $row) { 
	                                    $this->db->where('id', $row['service_id']);
	                                    $service_name = $this->db->get('services')->row_array();
									?>
									<option value="<?=$row['id']?>"><?php echo $service_name['service_title']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider</label>
								<select class="form-control" name="providers" id="providers">
									<option value="">Select Provider</option>
									<?php foreach ($list as $row) { 
										$this->db->where('id', $row['provider_id']);
							            $pro_name = $this->db->get('providers')->row_array();

							            if(!empty($pro_name)) { ?>
										<option value="<?=$row['provider_id']?>"><?php echo $pro_name['name']?></option>
									<?php } 
									} ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Users</label>
								<select class="form-control" name="users" id="users">
									<option value="">Select User</option>
									<?php foreach ($list as $row) { 
										$this->db->where('id', $row['user_id']);
							            $user_name = $this->db->get('users')->row_array();

							            if(!empty($user_name)) { ?>
										<option value="<?=$row['user_id']?>"><?php echo $user_name['name']?></option>
									<?php } 
									} ?>
								</select>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control start_date" type="text" name="from">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control end_date" type="text" name="to">
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
		<div class="status-toggle mb-3 d-flex">
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 service_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Services</th>
                                        <th>Provider</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Booking Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($list)) {
									$i=1;

									foreach ($list as $rows) {
									$ser_image='';
									$service_img=$this->db->where('service_id',$rows['service_id'])->get('services_image')->row();
									if(!empty($service_img->service_image)){
										$ser_image=$service_img->service_image;
									}
									
									if(!empty($rows['service_date'])){
										$date=date(settingValue('date_format'), strtotime($rows['service_date']));
									}else{
										$date='-';
									}

									$this->db->where('id', $rows['provider_id']);
							        $pro_name = $this->db->get('providers')->row_array();

			                        $this->db->where('id', $rows['user_id']);
							        $user_name = $this->db->get('users')->row_array();	

							         $this->db->where('id', $rows['service_id']);
	                                 $service_name = $this->db->get('services')->row_array();
								
										echo'<tr>
	                                        <td>'.$i++.'</td>
	                                        <td><a href="'.base_url().'deleted-service-details/'.$rows['service_id'].'"><img class="rounded service-img mr-1" src="'.base_url().$ser_image.'" alt=""> '.$service_name['service_title'].'</a></td>                                       
	                                        <td>'.$pro_name['name'].'</td>
	                                        <td>'.$user_name['name'].'</td>
	                                        <td>'.currency_code_sign(settings('currency')).''.$rows['amount'].'</td>
	                                        <td>'.$date.'</td>
											<td> Pending </td>
										</tr>';
								
									} } else {
                                    ?>
									<tr>
										<td colspan="9">
											<div class="text-center text-muted">No Records Found</div>
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