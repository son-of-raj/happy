<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-title">Edit Subscription</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                        <form id="editSubscription" method="post">
                        	
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
							<div class="form-group">
                                <label class="d-block">Subscription For</label>
                                
                                    <div class="text-info"><h5><?php echo ($subscription['type'] == 1)?'Provider':'Freelancer';?></h5></div>
                                
                               
                            </div>
							<div class="form-group">
								<label>Subscription Type</label>
								<select name="subscription_type" id="subscription_type" class="form-control select">
									<option value="1" <?php echo ($subscription['subscription_type'] == '1')?'selected':'';?>>Basic</option>
									<option value="2" <?php echo ($subscription['subscription_type'] == '2')?'selected':'';?>>Silver</option>
									<option value="3" <?php echo ($subscription['subscription_type'] == '3')?'selected':'';?>>Gold</option>
									<option value="4" <?php echo ($subscription['subscription_type'] == '4')?'selected':'';?>>Free</option>
								</select>
							</div>
                            <div class="form-group">
                                <label>Subscription Name</label>
                                <input class="form-control" type="text" value="<?php echo $subscription['subscription_name']; ?>" name="subscription_name" id="subscription_name">
                                <input class="form-control" type="hidden" value="<?php echo $subscription['id']; ?>" name="subscription_id" id="subscription_id">
                            </div>
                            <div class="form-group">
                                <label>Subscription Amount</label>
                                <input class="form-control sub-amount" type="number" step="0.01" min="0" value="<?php echo $subscription_amt; ?>" name="amount" id="amount">
                            </div>
                            <div class="form-group">
                                <label>Subscription Duration (in days)</label>
								<input class="form-control" type="number" min="0" max="720" name="duration" id="duration" value="<?php echo $subscription['duration']; ?>">
								
                                <input type="hidden" name="subscription_description" id="subscription_description" value="<?php echo $subscription['fee_description']; ?>">
                            </div>
                            <?php
								$value=$this->db->select('count(id) as counts')->from('subscription_details')->where('subscription_id',$subscription['id'])->get()->row();
                            ?>
                            <div class="form-group">
                                <label class="d-block">Subscription Status</label>
                                <label class="radio-inline">
                                    <input name="status" checked="checked" name="status" id="status1" value="1" type="radio" <?php echo $subscription['status']==1 ? "checked":""; ?>> Active
                                </label>
                                <?php
                                if ($value->counts==0 || $value->counts=='') { ?>
									<label class="radio-inline">
                                    <input name="status" type="radio" name="status" id="status2" value="0" <?php echo $subscription['status']==0 ? "checked":""; ?>> Inactive
                                </label> 
                                <?php } ?>
                            </div>
							
							<div class="form-group">
                                <label class="d-block">Subscription Details</label>
                                
								<div class="subscriptions-info">
									<div class="row form-row subscriptions-cont">
										<div class="col-lg-12">
											<table class="table table-bordered" id="append">	                                    	
												<tbody>
												<?php if($subscription['subscription_content'] != '') { 
												$sublists = explode(",", $subscription['subscription_content']);
												if (count($sublists) > 0) {
												foreach ($sublists as $k => $val ) { 													
											?>
													<tr class="singlerow">
														<td><input type="text" class="form-control" name="subscriptionsdetails[]" value="<?php echo $val; ?>" required></td>
														<td><a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a></td>
													</tr>
												<?php } } } else { ?>
													<tr class="singlerow">
														<td><input type="text" class="form-control" name="subscriptionsdetails[]" value="" required title="Subscription Details"></td>
														<td><a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a></td>
													</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="add-more form-group mt-4">
									<a href="javascript:void(0);" class="add-subscriptions"><i class="fas fa-plus-circle"></i> Add More</a>
								</div>
                            </div>
							<input name="subtype" id="subtype" type="hidden" value="<?php echo $subscription['type'];?>" />
							
                            <div class="mt-4">
								<?php if($user_role==1){?>
                                <button class="btn btn-primary" type="submit">Save Changes</button>
								<?php }?>
									
                                <a href="<?php echo $base_url; ?>subscriptions" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>