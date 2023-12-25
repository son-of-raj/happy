<?php 
$user_details = $this->db->where('id',$userid)->get('users')->row_array();

?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Rewards Details</h3>
				</div>
				
				<div class="text-right mb-3">
					<a href="<?php echo base_url()?>reward-system" class="btn btn-primary float-end">Back</a>		
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
		
			<div class="col-lg-6">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>User Details</span>
						</h5>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Name</p>
							<p class="col-sm-9"><?php echo $user_details['name']?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Email ID</p>
							<p class="col-sm-9"><?php echo $user_details['email']?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Mobile</p>
							<p class="col-sm-9"><?php echo $user_details['mobileno']?></p>
						</div>					
						
					</div>
				</div> 
			</div>
			<div class="col-lg-12">				
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>User Rewards Details</span>
						</h5>
						
								
								<table class="table mb-0" >
							
								<thead>
									<tr>
										<th>S.No</th>	
										<th>Provider</th>	
										<th>Service</th>										
										<th class="text-center">Reward Type</th>
										<th class="text-center">Discount(%)</th>
										<th class="text-center">Reward Status</th>
										<th class="text-center">Created At</th>										
									</tr>
								</thead>
								<tbody>
									<?php $status_arr = ['Deleted','Active','Inactive','Used'];
									if(!empty($rewards)){ 
										$sno = 1;
										foreach ($rewards as $val) {										
										
											$cdate = date('d-m-Y', strtotime($val['created_at']));
											if($val['reward_type'] == 1){
												$rtype = "Discount";
												$rval = $val['reward_discount']."%";
											} else {
												$rtype = "Free Service";
												$rval = '-';
											}
											
											$provider_name = $this->db->select('name')->where('id',$val['provider_id'])->get('providers')->row()->name;
											$service_title = $this->db->select('service_title')->where('id',$val['service_id'])->get('services')->row()->service_title;
											
									?>
											<tr>
												<td><?php echo $sno++; ?></td>		
												<td><?php echo $provider_name;?></td>	
												<td><?php echo $service_title;?></td>
												
												<td class="text-center"><?php echo $rtype?></td>
												<td class="text-center"><?php echo $rval?></span></td>
												<td class="text-center"><?php echo $status_arr[$val['status']]?></td>
												
												<td class="text-center"><?php echo $cdate?></td>
												
											</tr>
											<?php 
										}
									} else {
										
										echo "<tr><td colspan='7' class='text-center'>No data found</td></tr>";
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
