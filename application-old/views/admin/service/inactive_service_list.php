<?php
	$category = $this->db->where('status',1)->get('categories')->result_array();
	$subcategory = $this->db->where('status',1)->get('subcategories')->result_array();
	$services = $this->db->where('status',0)->get('services')->result_array();

?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Inactive Service</h3>
				</div>
				<div class="col-auto text-right">
					<a href="<?php echo $base_url; ?>inactive-service-list" class="btn btn-primary add-button"><i class="fas fa-sync"></i></a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
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
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!empty($list)) {
                                    	//echo "<pre>";print_r($list);exit;
									$i=1;
									foreach ($list as $rows) {
									$ser_image='';
									$service_img=$this->db->where('service_id',$rows['id'])->get('services_image')->row();
									if(!empty($service_img->service_image)){
										$ser_image=$service_img->service_image;
									}
									
									if(!empty($rows['created_at'])){
										$date=date(settingValue('date_format'), strtotime($rows['created_at']));
									}else{
										$date='-';
									}

									if($user_role==1){
									echo'<tr>
                                        <td>'.$i++.'</td>
                                        <td><a href="'.base_url().'inactive-service-details/'.$rows['id'].'"><img class="rounded service-img mr-1" src="'.base_url().$ser_image.'" alt=""> '.$rows['service_title'].'</a></td>                                       
                                        <td>'.$rows['category_name'].'</td>
                                        <td>'.$rows['subcategory_name'].'</td>
                                        <td>'.currency_code_sign(settings('currency')).''.$rows['service_amount'].'</td>
                                        <td>'.$date.'</td>
										<td> 
											<a href="'.base_url().'inactive-service-details/'.$rows['id'].'" class="btn btn-sm bg-info-light">
												<i class="far fa-eye mr-1"></i> '.'View'.'
											</a>
										</td>
									</tr>';
								}else{
										echo'<tr>
                                        <td>'.$i++.'</td>
                                        <td><a href="'.base_url().'inactive-service-details/'.$rows['id'].'"><img class="rounded service-img mr-1" src="'.base_url().$ser_image.'" alt=""> '.$rows['service_title'].'</a></td>                                       
                                        <td>'.$rows['category_name'].'</td>
                                        <td>'.$rows['subcategory_name'].'</td>
                                        <td>$'.$rows['service_amount'].'</td>
                                        <td>'.$date.'</td>
										<td> 
											<a href="'.base_url().'inactive-service-details/'.$rows['id'].'" class="btn btn-sm bg-info-light">
												<i class="far fa-eye mr-1"></i> View
											</a>
										</td>
									</tr>';
								}
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