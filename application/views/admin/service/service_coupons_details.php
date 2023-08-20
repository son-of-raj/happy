<?php 
$user_id = $couponslist[0]['provider_id'];

$user_details = $this->db->where('id',$user_id)->get('providers')->row_array();
$category = '';
$subcategory = '';
if(!empty($user_details['category'])){
$category = $this->db->select('category_name')->where('id',$user_details['category'])->get('categories')->row()->category_name;
}
if(!empty($user_details['subcategory'])){
$subcategory = $this->db->select('subcategory_name')->where('id',$user_details['subcategory'])->get('subcategories')->row()->subcategory_name;
}

$cname = $couponslist[0]['coupon_name'];
if($couponslist[0]['coupon_type'] == 1){
	$offer = $couponslist[0]['coupon_percentage']."%";;
	$type  = 'Percentage';
} else {
	$offer = currency_conversion($couponslist[0]['currency_code']).$couponslist[0]['coupon_amount'];
	$type  = 'Fixed Amount';
}
$sdate = date('d-m-Y', strtotime($couponslist[0]['start_date']));
$edate = date('d-m-Y', strtotime($couponslist[0]['end_date']));

$this->db->select("C.*, S.service_title, S.currency_code, S.service_amount");
$this->db->from('service_coupons C'); 
$this->db->join('services S', 'C.service_id = S.id', 'LEFT');
$this->db->where('C.status != ', 0);
$this->db->where('C.coupon_name', $cname);
$query = $this->db->get();  
$lists  = $query->result_array();

?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Service Coupon Details</h3>
				</div>
				<div class="col-auto">
					<a href="<?php echo base_url()?>service-coupons" class="btn btn-primary float-end">Back</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-lg-6">
				<div class="card">					
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Coupon Details</span>
						</h5>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3 text-nowrap">Coupon Name</p>
							<p class="col-sm-9"><?php echo $cname; ?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Coupon Type</p>
							<p class="col-sm-9"><?php echo $type; ?></p>
						</div>
						<?php if($couponslist[0]['coupon_type'] == 1) { ?>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Percentage</p>
							<p class="col-sm-9"><?php echo $offer;?></p>
						</div>
						<?php } else { ?>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Amount</p>
							<p class="col-sm-9"><?php echo $offer;?></p>
						</div>
						<?php } ?>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">Start Date</p>
							<p class="col-sm-9"><?php echo $sdate; ?></p>
						</div>
						<div class="row">
							<p class="col-sm-3 text-muted text-sm-end mb-0 mb-sm-3">End Date</p>
							<p class="col-sm-9"><?php echo $edate; ?></p>
						</div>
						
					</div>				
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Provider Details</span>
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
							<p class="col-sm-9"><?php echo $user_details['country_code']?>-<?php echo $user_details['mobileno']?></p>
						</div>
						
					</div>
				</div> 
			</div>
			<div class="col-lg-12">				
				<div class="card">
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Coupon Applies to Service Details</span>
						</h5>
						
								
								<table class="table mb-0" >
							
								<thead>
									<tr>
										<th>S.No</th>										
										<th>Service</th>										
										<th class="text-center">Coupon Valid For</th>
										<th class="text-center">Coupon User Limits</th>
										<th class="text-center">Coupon Used Count<span data-bs-toggle="tooltip" title="No of Times Coupon Used By the User"><i class="fas fa-info-circle me-1"><i></span></th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($lists)){ 
										$sno = 1;
										foreach ($lists as $val) {
											
										
											$edate = date('d-m-Y', strtotime($val['end_date']));
											if($val['coupon_type'] == 1){
												$offer = $val['coupon_percentage']."%";;
											} else {
												$offer = currency_conversion($val['currency_code']).$val['coupon_amount'];
											}
											
											if($val['user_limit'] == 0){
												$lmt_txt = 'No Limits';
											} else {
												$lmt_txt = $val['user_limit'];												
											}
									?>
											<tr>
												<td><?php echo $sno++; ?></td>												
												<td><?php echo $val['service_title'];?></td>
												<td class="text-center"><?php echo $val['valid_days']?> days</td>
												<td class="text-center"><?php echo $lmt_txt?></td>
												<td class="text-center"><?php echo $val['user_limit_count']?></span></td>
												<td>
												<?php if($val['status'] == 3) { ?>
													<a class="btn btn-sm bg-danger-light me-2 " title="Coupon Expired">Expired</a>
												<?php } else if($val['status'] == 1) { ?>
													<a href="javascript:void(0)" class="btn btn-sm bg-success-light me-2" title="Active Coupon">Active</a>
												<?php } else { ?>
													<a class="btn btn-sm bg-warning-light me-2" title="Inactive Coupon">Inactive</a>
												<?php } ?>
												</td>
												
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