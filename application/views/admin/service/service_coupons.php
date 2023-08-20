<?php
	$user_details = $this->db->where('status != ',0)->get('providers')->result_array()
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Service Coupons</h3>
				</div>
				<div class="col-auto text-end">
					<a href="<?php echo $base_url; ?>service-coupons" class="btn btn-white add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>service-coupons" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider Name</label>
								<select class="form-control select" name="username">
									<option value="">Select provider name</option>
									<?php foreach ($user_details as $user) { ?>
									<option value="<?php echo $user['id']?>"><?php echo $user['name']?></option>
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
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 service_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        
                                        <th>Provider</th>
                                        
										<th>Coupon Name</th>
										
                                        <th class="text-center">Total Services</th>
                                  
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
									if(!empty($list)) {
									$i=1;
									foreach ($list as $rows) {
										
										echo'<tr>
											<td>'.$i++.'</td>
											
											<td>'.$rows['provider_name'].'</td>
											<td>'.$rows['coupon_name'].'</td>
											<td class="text-center"><span class="badge badge-pill btn-primary">'.$rows['total_service'].'</span></td>											
                                            <td>
											<a href="'.base_url().'coupons-details/'.$rows['id'].'" class="btn btn-sm bg-info-light me-3"><i class="far fa-eye me-1"></i>View</a>											
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