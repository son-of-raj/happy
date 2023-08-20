<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Rewards'])) ? $user_language[$user_selected]['lg_Rewards'] : $default_language['en']['lg_Rewards']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Rewards'])) ? $user_language[$user_selected]['lg_Rewards'] : $default_language['en']['lg_Rewards']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$user_id = $this->session->userdata('id');
$count = $this->db->where('provider_id',$user_id)->where('status != ',0)->get('service_rewards')->num_rows(); // Rewards Count

$type = $this->session->userdata('usertype');
if ($type == 'user') {
   $user_currency = get_user_currency();
} else if ($type == 'provider') {
   $user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];

$det = $this->db->where('id',$user_id)->where('status != ',0)->get('providers')->row_array();
?>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			<div class="col-xl-9 col-md-8">
				
				<?php if($det['allow_rewards']== 0) { ?>			
				
				<div class="">
					<div class="pricing-alert flex-wrap flex-md-nowrap">
						<div class="alert-desc">
							<p class="mb-0"><?php echo (!empty($user_language[$user_selected]['lg_Rewards_Enable_Txt'])) ? $user_language[$user_selected]['lg_Rewards_Enable_Txt'] : $default_language['en']['lg_Rewards_Enable_Txt']; ?> <a href="<?php echo base_url()."provider-settings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></a></p>							
						</div>						
					</div>
				</div>
				
				<?php } ?>
				<?php if($det['allow_rewards']== 1) { ?>
				
				<div class="">
					<div class="pricing-alert flex-wrap flex-md-nowrap">
						<div class="alert-desc">
							<p class="mb-0">* <?php echo (!empty($user_language[$user_selected]['lg_Rewards_Update_Txt'])) ? $user_language[$user_selected]['lg_Rewards_Update_Txt'] : $default_language['en']['lg_Rewards_Update_Txt']; ?> <a href="<?php echo base_url()."provider-settings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></a></p>
							
						</div>						
					</div>
				</div>
							
				
				<div class="alert alert-warning mt-3">
					<div class="pricing-alert flex-wrap flex-md-nowrap">
						<div class="alert-desc">
							<p class="mb-0"><?php echo (!empty($user_language[$user_selected]['lg_rewards_depends'])) ? $user_language[$user_selected]['lg_rewards_depends'] : $default_language['en']['lg_rewards_depends']; ?> (max <?php echo $det['booking_reward_count'];?>-times).</p>
						</div>						
					</div>
				</div>
				
				
				<div class="col-lg-12 float-end mb-3">
					
					<?php if($count > 0) { ?>
						<a href="<?php echo base_url()?>reward-details" target="_blank" class="btn btn-sm bg-warning-light viewcoupon me-2 ms-2 float-end"><i class="fas fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_view_rewards_history'])) ? $user_language[$user_selected]['lg_view_rewards_history'] : $default_language['en']['lg_view_rewards_history']; ?></a>
					<?php } ?>
				</div>
				
				<div class="card transaction-table mb-0">
					<div class="card-body" id="dataList">
						<div class="table-responsive">
							<?php if(!empty($lists)){ ?>
							<table class="table mb-0">
							<?php }else{?>
								<table class="table mb-0" >
							<?php }?>
								<thead>
									<tr>
										<th>S.No</th>
										<th><?php echo (!empty($user_language[$user_selected]['lg_User'])) ? $user_language[$user_selected]['lg_User'] : $default_language['en']['lg_User']; ?></th>
										<th class="text-center"><?php echo (!empty($user_language[$user_selected]['lg_total_booking'])) ? $user_language[$user_selected]['lg_total_booking'] : $default_language['en']['lg_total_booking']; ?></th>	
										<th class="text-center"><?php echo (!empty($user_language[$user_selected]['lg_Rewards'])) ? $user_language[$user_selected]['lg_Rewards'] : $default_language['en']['lg_Rewards']; ?></th>
										<th class="text-center"><?php echo (!empty($user_language[$user_selected]['lg_Action'])) ? $user_language[$user_selected]['lg_Action'] : $default_language['en']['lg_Action']; ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if(!empty($lists)){ 
										$sno = 1;
										foreach ($lists as $val) {
											$isreward = $this->db->where('user_id',$val['user_id'])->where('provider_id',$val['provider_id'])->where('status', 1)->get('service_rewards')->num_rows();											
											if($isreward > 0){
												$bg ='success'; 
												$textbg = 'YES';												 
											} else {
												$bg='';
												$textbg = '-';												
											}
											if (!empty($val['profile_img'])) {
                                                $image = base_url() . $val['profile_img'];
                                            } else {
                                                $image = base_url() . 'assets/img/user.jpg';
                                            }
											$rcount = $det['booking_reward_count'];
											if($val['total_count'] >= $rcount){ 
												$disabled = 'disabled';
											} else{
												$disabled = '';
											}
									?>
										<tr>
											<td><?php echo $sno++;?></td>
											<td><div class="avatar avatar-xs me-1">
												<img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $image; ?>">
											</div> <?php echo  !empty($val['user_name']) ? $val['user_name'] : '-'; ?></td>	
											<td class="text-center"><?php echo $val['total_count']?></td>
											<td class="text-center"><span class="badge bg-<?php echo $bg; ?>-light"><?php echo $textbg; ?></span></td>
											<td class="text-center">
												<?php if($val['total_count'] >= $rcount && $isreward == 0){ ?>
													<a href="javascript:void(0)" class="btn btn-sm bg-success-light float-end addrewards" disabled title="Select User To Add Rewards" data-bs-toggle="modal" data-bs-target="#rewards-modal" onclick="checkreward('<?php echo $val['user_id']?>', '<?php echo $val['total_count']?>');">Add Rewards</a>
												<?php } ?>
											</td>
										</tr>
									<?php 
										}
									} else {
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo "<tr><td colspan='5' class='text-center'>".$norecord."</td></tr>";
									}
									?>
									</tbody>
								</table>
							</div>
							
						</div>
						
					</div>	
				<?php } ?>
				</div>
			</div>

		</div>
	</div>
<div class="modal fade" id="rewards-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="acc_title"><?php echo (!empty($user_language[$user_selected]['lg_add_rewards'])) ? $user_language[$user_selected]['lg_add_rewards'] : $default_language['en']['lg_add_rewards']; ?></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form accept-charset="UTF-8" id="reward_form" method="POST">
					<div class="form-group">
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
					<div class="form-group">
						<label><?php echo (!empty($user_language[$user_selected]['lg_reward_type'])) ? $user_language[$user_selected]['lg_reward_type'] : $default_language['en']['lg_reward_type']; ?></label>
						<select class="form-control" id="reward_type">
							<option value="0">Free</option>
							<option value="1">Discount</option>
						</select>
					</div>
					<div class="form-group">
						<label><?php echo (!empty($user_language[$user_selected]['lg_discount'])) ? $user_language[$user_selected]['lg_discount'] : $default_language['en']['lg_discount']; ?>(%)</label>
						<input type="text" id="reward_discount" class="form-control rewardcls number" value="0" readonly="true">
					</div>
					<div class="form-group mb-0">
						<label><?php echo (!empty($user_language[$user_selected]['lg_messages'])) ? $user_language[$user_selected]['lg_messages'] : $default_language['en']['lg_messages']; ?></label>
						<textarea id="rdescription" class="form-control rewardcls" rows="2" cols="20"></textarea>
					</div>
                </form>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submit_reward"><?php echo (!empty($user_language[$user_selected]['lg_add'])) ? $user_language[$user_selected]['lg_add'] : $default_language['en']['lg_add']; ?></button>
                <button type="button" class="btn btn-danger reward_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
            </div>
        </div>
    </div>
</div>

