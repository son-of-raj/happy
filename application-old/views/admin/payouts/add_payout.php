<?php 
$admin_settings = $language_content['language'];

$query = $this->db->query("select * from language WHERE status = '1'");
$lang_test = $query->result();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-12">
					<h3 class="page-title"><?php echo(!empty($admin_settings['lg_add_payout']))?($admin_settings['lg_add_payout']) : 'Add Payout';  ?></h3>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<form class="form-horizontal" id="add_payout" action="<?php echo base_url('admin/add-payouts'); ?>"  method="POST" enctype="multipart/form-data" >
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				<div class="row">
					<div class=" col-lg-6 col-sm-12 col-12">
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title"><?php echo(!empty($admin_settings['lg_payout_details']))?($admin_settings['lg_payout_details']) : 'Payout Details';  ?></h4>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label><?php echo(!empty($admin_settings['lg_provider_name']))?($admin_settings['lg_provider_name']) : 'Provider Name';  ?></label>
									<select class="form-control select2" name="provider_id" id="provider_id">
										<option value="">Select any provider</option>
										<?php foreach ($providers as $provider) { 
											$amount = get_gigs_currency($provider['wallet_amt'], $provider['currency_code'], settingValue('currency_option'));
											?>
                                            <option value="<?php echo $provider['id']; ?>"><?php echo $provider['name'].' - '.settingValue('currency_symbol').$amount; ?></option>
                                        <?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label><?php echo(!empty($admin_settings['lg_withdrawal_method']))?($admin_settings['lg_withdrawal_method']) : 'Withdrawal Method';  ?></label>
									<select class="form-control select2" name="payout_method" id="payout_method">
										<option value="" selected>Select any payment</option>
				                        <option value="stripe">Stripe</option>
				                        <option value="paypal">Paypal</option>
				                        <option value="moyasar">Moyasar</option>
									</select>
								</div>
								<div class="form-group">
									<label><?php echo(!empty($admin_settings['lg_withdrawal_amount']))?($admin_settings['lg_withdrawal_amount']) : 'Withdrawal Amount';  ?></label>
									<input type="text" name="payout_amount" id="payout_amount" class="form-control" placeholder="0.00">
								</div>
									<div class="form-group">
										<label><?php echo(!empty($admin_settings['lg_status']))?($admin_settings['lg_status']) : 'Status';  ?></label>
                                        <select name="payout_status" id="payout_status" class="form-control withdrawal_staus">
                                        	<option value="">Select Status</option>
                                            <option value="0">Pending</option>
                                            <option value="1">Completed</option>
                                        </select>
									</div>
								
								<?php if($this->session->userdata('role') == 1) { ?>
									<div class="form-groupbtn">
										<button name="form_submit" type="submit" class="btn btn-primary" value="true"><?php echo(!empty($admin_settings['lg_submit']))?($admin_settings['lg_submit']) : 'Submit';  ?></button>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
		</form>
	</div>
</div>