<?php
	$category = $this->db->get('categories')->result_array();
	$subcategory = $this->db->get('subcategories')->result_array();
	$services = $this->db->select('id,service_title')->where('status != ', 0)->get('services')->result_array();
	$additionalservices = $this->db->select('id,service_name')->where('status != ', 0)->get('additional_services')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Additional Services</h3>
				</div>
				<div class="col-auto text-end">
					<a href="<?php echo $base_url; ?>additional-services" class="btn btn-white add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
					<a href="<?php echo $base_url; ?>add-additional-services" class="btn btn-white add-button">
						<i class="fas fa-plus"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>additional-services" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Service Title</label>
								<select class="form-control select" name="service_id" id="service_id">
									<option value="">Select Service</option>
									<?php foreach ($services as $pro) { ?>
									<option value="<?php echo $pro['id']?>"><?php echo $pro['service_title']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Additional Service Title</label>
								<select class="form-control select" name="service_title" id="service_title">
									<option value="">Select Additional Service</option>
									<?php foreach ($additionalservices as $ser) { ?>
									<option value="<?php echo $ser['id']?>"><?php echo $ser['service_name']?></option>
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
						<div class="table-responsive additionalservices-lists">
							<table class="table table-hover table-center mb-0 service_table">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th>Service For</th>   
										<th>Additional Service Title</th>
                                        <th>Amount</th>
                                        <th>Date</th>                                        
                                        <th>Status</th>                                  
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if(!empty($list)) {
									$i=1;

									foreach ($list as $rows) {
									
									$addi_ser_img = '';
									if(!empty($rows['additional_service_image']) && file_exists($rows['additional_service_image'])){
										$addi_ser_img='<img class="rounded service-img me-1" src="'.base_url().$rows['additional_service_image'].'" alt="Additional Service Image">';
									}
										
									$ser_image='';
									$service_img=$this->db->where('service_id',$rows['service_id'])->get('services_image')->row();
									if(!empty($service_img->service_image) && file_exists($service_img->service_image)){
										$ser_image='<img class="rounded service-img me-1" src="'.base_url().$service_img->service_image.'" alt="Service Image">';
									}
									
									$attr='';$tag='';
									
									if(!empty($rows['created_at'])){
                                        $date=date('d-m-Y',strtotime($rows['created_at']));
									}else{
                                        $date='-';
									}
									if($rows['status']==1) {
										$val='checked';
									}
									else {
										$val='';
									}
									
									echo'<tr>
                                        <td>'.$i++.'</td>
                                        <td><a href="'.base_url().'service-details/'.$rows['service_id'].'"> '.$ser_image.$rows['service_title'].'</a></td>                                       
                                        <td>'.$addi_ser_img.$rows['service_name'].'</td>                                        
                                        <td>$'.$rows['amount'].'</td>
                                        <td>'.$date.'</td>
                                        <td>
											<div '.$tag.'>
												<div class="status-toggle">
													<input '.$attr.' id="status_'.$rows['id'].'" class="check change_status_additionalservice" data-id="'.$rows['id'].'" type="checkbox" '.$val.'>
													<label for="status_'.$rows['id'].'" class="checktoggle">checkbox</label>
												</div>
											</div>
                                        </td>
										<td> 
											<a href="'.base_url().'edit-additional-services/'.$rows['id'].'" class="btn btn-sm bg-success-light me-2"><i class="far fa-edit me-1"></i> Edit</a>
											<a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light me-2 delete_additional_service" id="Onremove_'.$rows['id'].'" data-id="'.$rows['id'].'"><i class="far fa-trash-alt me-1"></i> Delete</a>
										</td>
									</tr>';
								
									} }  ?>
                                </tbody>
                            </table>
						</div> 
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>