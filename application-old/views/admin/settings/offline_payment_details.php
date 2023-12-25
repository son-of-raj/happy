<?php 
$servie_staus = array(
array("id"=>0,'value'=>'Pending'),
array("id"=>1,'value'=>'approved'),
);
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Offline Payment List</h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 categories_table" id="categories_table">
								<thead>
									<tr>
										<th>#</th>
                                        <th>User</th>
                                        <th>Subscription Plan</th>
                                        <th>Payment Document</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Action</th>	  
									</tr>
								</thead>
								<tbody>
								<?php
                                    $i=1;
                                   
                                   foreach ($list as $rows) 
                                    {
                                    	//echo "<pre>";print_r($rows);exit;
                                    	 $badge='';
                                    	 $disabled= '';
									if ($rows['paid_status']==0) {
										$badge='Pending';
										$color='dark';
									}
									if ($rows['paid_status']==1) {
										$badge='approval';
										$color='info';
										$disabled = "disabled";
									}
									
                                        echo'<tr>
                                        <td>'.$i++.'</td>
                                        <td>'.$rows['name'].'</td>
                                        <td>'.$rows['subscription_name'].'</td>
                                        <td><a href="'.base_url().$rows['upload_doc'].'" class="btn btn-primary btn-sm" download="Offline Payment Document"><i class="fas fa-download"></i></a></td>
                                        <td>'.$rows['expiry_date_time'].'</td>
                                        <td><label class="badge badge-'.$color.'">'.ucfirst($badge).'</lable></td>
                                        <td><select class="form-control chngstatus" name="ser_status" data-id="'.$rows['sub_id'].'" data-userid="'.$rows['subscriber_id'].'"'.$disabled.'> 
												<option value="">Select Status</option>';
												foreach ($servie_staus as $pro) { 
												echo '<option value="'.$pro['id'].'">'.$pro['value'].'</option>';
												} 
											echo '</select></td>
                                          </tr>';
                                       
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