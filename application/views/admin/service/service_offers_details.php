<?php 
$user_id = $this->uri->segment('2');
$user_details = $this->db->where('id',$user_id)->get('providers')->row_array();
$category = '';
$subcategory = '';
if(!empty($user_details['category'])){
$category = $this->db->select('category_name')->where('id',$user_details['category'])->get('categories')->row()->category_name;
}if(!empty($user_details['subcategory'])){
$subcategory = $this->db->select('subcategory_name')->where('id',$user_details['subcategory'])->get('subcategories')->row()->subcategory_name;
}


?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Service Offer Details</h3>
				</div>
				<div class="text-right mb-3">
					<a href="<?php echo base_url()?>service-offers" class="btn btn-primary float-end">Back</a>		
				</div>
				
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-lg-6">
				<div class="card">					
					<div class="card-body">
						<h5 class="card-title d-flex justify-content-between">
							<span>Provider Personal Details</span>
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
							<span>Offers On Service Details</span>
						</h5>
						
								
								<table class="table mb-0" id="offer_table">
							
								<thead>
									<tr>
										<th>S.No</th>
										<th>Service</th>
										<th>Amount</th>
										<th>Offer(%)</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Created Date</th>
										
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($offerslist)){ 
										$sno = 1;
										foreach ($offerslist as $val) {
											
											
									?>
											<tr>
												<td><?php echo $sno++; ?></td>												
												<td><?php echo $val['service_title']?></td>
												<td><?php echo currency_conversion($val['currency_code']).$val['service_amount'];?></td>
												<td><?php echo $val['offer_percentage']?>%</td>
												<td><?php echo date(settingValue('date_format'), strtotime($val['start_date']))?></td>
												<td><?php echo date(settingValue('date_format'), strtotime($val['end_date']))?></td>
												<td><?php echo date(settingValue('date_format'), strtotime($val['created_at']))?></td>
												
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