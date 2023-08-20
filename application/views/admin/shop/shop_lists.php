<?php
  $shop_details = $this->db->where('status != ', 0)->get('shops')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Shops</h3>
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
		<form action="<?php echo base_url()?>shop-lists" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">					
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Shop Name</label>
								<select class="form-control select" name="shop_name">
									<option value="">Select Shop name</option>
									<?php foreach ($shop_details as $user) { ?>
									<option value="<?php echo $user['shop_name']?>"><?php echo $user['shop_name']?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Email</label>
								<select class="form-control select" name="email">
									<option value="">Select email</option>
									<?php foreach ($shop_details as $user) { ?>
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
                        <div class="table-responsive shop-lists">
                            <table class="table custom-table mb-0 w-100 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Provider Name</th>
										<th>Shop Name</th>
                                        <th>Contact No</th>
                                        <th>Email</th>      
										<th>Status</th>
                                    	<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									if(!empty($list)) {
									$i=1;
									foreach ($list as $rows) {
										if($rows['status']==1) {
											$val='checked';
											$tag='data-bs-toggle="tooltip" title="Click to Deactivate Shop ..!"';
										}
										else {
											$val='';
											$tag='data-bs-toggle="tooltip" title="Click to Activate Shop ..!"';
										}
										
										$attr = '';
										
										$user_details = $this->db->where('id',$rows['provider_id'])->get('providers')->result_array(); 
																	
										$simg = 'assets/img/placeholder_shop.png';
										$image = $this->db->select("*")->from('shops_images')->where("shop_id", $rows['id'])->get()->result_array(); 
										if(count($image) > 0) {
											$shopimages = $image[0]['shop_image'];										
											if (!empty($shopimages) && file_exists($shopimages)) {
												$simg = $shopimages;
											}
										}
										
										if($this->session->userdata('admin_id') == 1) { 
											$display_data = '';
											$display_status = '';
										} else {
											$display_data = 'd-none';
											$display_status = 'disabled';
										}
										$shopname = str_replace('-', ' ', $rows['shop_name']);
										$shopnames = wordwrap($shopname, 75, '<br />', true);
										echo'<tr>
											<td>'.$i++.'</td>
											<td>'.$user_details[0]['name'].'</td>
											<td><h2 class="table-avatar"><a href="#" class="avatar avatar-sm me-2"> <img class="avatar-img rounded-circle" src="'.base_url().$simg.'"></a>
											<a href="'.base_url().'shop-details/'.$rows['id'].'">'.
											$shopnames.'</a></h2></td>
											<td>'.$rows['country_code'].'-'.$rows['contact_no'].'</td>
											<td>'.$rows['email'].'</td>
											
											<td>
											<div '.$tag.'>
												<div class="status-toggle mb-2">
													<input '.$attr.' id="status_'.$rows['id'].'" class="check change_Status_Shop" data-id="'.$rows['id'].'" data-provider_id="'.$rows['provider_id'].'" type="checkbox" '.$val.' '.$display_status.'>
													<label for="status_'.$rows['id'].'" class="checktoggle">checkbox</label>
												</div>
											</div>
                                            </td>
                                            <td>
											<a href="'.base_url().'shop-edit/'.$rows['id'].'" class="btn btn-sm bg-success-light me-2 "><i class="far fa-edit me-1"></i> Edit</a>
											<a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light me-2 delete_shop_data '.$display_data.'" id="Onremove_'.$rows['id'].'" data-id="'.$rows['id'].'" data-provider_id="'.$rows['provider_id'].'"><i class="far fa-trash-alt me-1"></i> Delete</a>
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