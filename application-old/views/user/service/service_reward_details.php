<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>Service Reward History</h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Service Reward History</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$user_id = $this->session->userdata('id');
$type = $this->session->userdata('usertype');
if ($type == 'user') {
   $user_currency = get_user_currency();
} else if ($type == 'provider') {
   $user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$st_arr = ['Deleted','Active','inactive','Used'];
?>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">

				<div class="card transaction-table mb-0">
					<div class="card-body" id="dataList">
						<div class="table-responsive">
							<?php 
									if(count($list)>0){?>
							<table class="table mb-0" id="order-summary">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th>Service</th>
										<th>Amount</th>
										<th>Discount</th>
										<th>User</th>
										<th>Mobile No</th>
										<th>Message</th>								
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($list)){ 										
										foreach ($list as $val) {
											$service_amount = get_gigs_currency($val['service_amount'], $val['currency_code'], $user_currency_code);
											$rt = ($val['reward_type']==0 ? 'Free' : $val['reward_discount'].'%');
											if($val['status'] == 0) {
												$clss = 'bg-danger-light';
											}else if($val['status'] == 1){
												$clss = 'bg-warning-light';
											}else if($val['status'] == 2){
												$clss = 'bg-purple-light';
											} else{
												$clss = 'bg-default-light';
											}
										?>									
											<tr>
												<td><?php echo $val['service_title'];?></td>
												<td><?php  echo currency_conversion($user_currency_code) . $service_amount; ?></td>
												<td><?php echo $rt?></td>
												<td><?php echo $val['user_name']?></td>
												<td><?php echo $val['user_mobile']?></td>
												<td><?php echo $val['description']?></td>
												<td><span class="btn btn-sm <?php echo $clss?>"><i class="fas fa-info-circle me-1 ms-1"></i><?php echo $st_arr[$val['status']]?></span></td>
												<td>
													<?php
													if ($val['status'] == 1) {
													?>
													<a href="javascript:void(0)" class="btn btn-sm bg-success-light edit_reward" reward_id="<?php echo $val['id']?>"><i class="far fa-edit"></i></a>

													<a href="javascript:void(0)" class="btn btn-sm bg-danger-light ms-2 me-2" data-bs-toggle="modal" data-bs-target="#checkdelete-modal" onclick="checkdelete('<?php echo $val['id']?>');"><i class="far fa-trash-alt"></i></a>
													<?php
													} 
													?>
												</td>
											</tr>
									<?php }
										} else {
											$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
											echo "<tr><td colspan='7' class='text-center'>".$norecord."</td></tr>";
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
<div class="modal fade" id="edit-rewards-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Add Rewards</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form accept-charset="UTF-8" id="reward_form" method="POST"  >
                    <div class="row">
                    	<div class="col-md-12">
                    		<label>Service</label>
                    		<select class="form-control select" id="r_service_id">
                    			<?php
                    			if (!empty($services)) 
                    			{
                    				foreach ($services as $s) 
                    				{
                    					//amount
                    					$service_amount = get_gigs_currency($s['service_amount'], $s['currency_code'], $user_currency_code);
                    					?>
                    					<option value="<?php echo $s['id']?>"><?php echo $s['service_title']?> - (<?php  echo currency_conversion($user_currency_code) . $service_amount; ?>)</option>
                    					<?php 
                    				}
                    			}
                    			?>
                    		</select>
                    		<input type="hidden" id="h_reward_id" value="">
                    		<input type="hidden" id="h_user_id" value="">
                    		<input type="hidden" id="h_total_visit_count" value="">
                    	</div>
                    	<div class="col-md-12">
                    		<label>Reward Type</label>
							<select class="form-control" id="reward_type">
  								<option value="0">Free</option>
  								<option value="1">Discount</option>								
							</select>

                    	</div>
                    	<div class="col-md-12">
                    		<label>Discount(%)</label>
							<input type="text" id="reward_discount" class="form-control rewardcls number" value="">
                    	</div>
                    	<div class="col-md-12">
                    		<label>Message</label>
                    		<textarea id="rdescription" class="form-control rewardcls" rows="2" cols="20"></textarea>
                    	</div>
                    </div>
                </form>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submit_reward">Update</button>
                <button type="button" class="btn btn-danger reward_cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="checkdelete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="acc_title">Delete Confirmation</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p>Are you sure want to delete the reward?</p>
				<input type="hidden" id="d_reward_id" value="">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="delete_reward">Yes</button>
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
			</div>
		</div>
	</div>
</div>