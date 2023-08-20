<?php 
$subid = $this->uri->segment('2');
$sub_details = $this->service->subscriptionlist($subid);;
?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-1">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-title">Update Subscription Duration</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                        <form id="updateSubscription" method="post" action="<?php echo base_url().'update-subscriptions/'.$subid; ?>">                        	
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label class="d-block">Subscription For</label>                                
									<div class="text-info"><h5><?php echo ($sub_details[0]->type == 1)?'Provider':'Freelancer';?></h5></div>   
								</div>
								<div class="form-group col-lg-6">
									<label class="d-block">Provider Name</label>                                
									<div class="text-info"><h5><?php echo $sub_details[0]->name;?></h5></div>  
									<input type="hidden" name="subid" value="<?php echo $subid; ?>" />
									<input type="hidden" name="proid" value="<?php echo $sub_details[0]->subscriber_id; ?>" />
								</div>
							</div>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label class="d-block">Subscription Name</label>                                
									<div class="text-info"><h5><?php echo $sub_details[0]->subscription_name; ?></h5></div>
								</div>
								<div class="form-group col-lg-6">
									<label class="d-block">Subscription Duration</label>                                
									<div class="text-info"><h5><?php echo $sub_details[0]->fee_description;?></h5></div>
								</div>
							</div>
							<?php 
								$full_date =date('Y-m-d H:i:s', strtotime($sub_details[0]->subscription_date));
										$date=date(settingValue('date_format'), strtotime($full_date));
										$date_f=date(settingValue('date_format'), strtotime($full_date));
										$yes_date=date(settingValue('date_format'),(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ));
										$time=date('H:i',strtotime($full_date));
										$session = date('h:i A', strtotime($time));
										if($date == date('Y-m-d')){
											$timeBase ="Today ".$session;
										}elseif($date == $yes_date){
											$timeBase ="Yester day ".$session;
										}else{
											$timeBase =$date_f." ".$session;
										}
										
										$full_dates =date('Y-m-d H:i:s', strtotime($sub_details[0]->expiry_date_time));
										$dates=date(settingValue('date_format'), strtotime($full_dates));
										$date_fs=date(settingValue('date_format'), strtotime($full_dates));
										$yes_dates=date(settingValue('date_format'),(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ));
										$times=date('H:i',strtotime($full_dates));
										$sessions = date('h:i A', strtotime($times));
										if($dates == date('Y-m-d')){
											$timeBases ="Today ".$sessions;
										}elseif($dates == $yes_dates){
											$timeBases ="Yester day ".$sessions;
										}else{
											$timeBases =$date_fs." ".$sessions;
										}

							 ?>
							<div class="row col-lg-12">
								<div class="form-group col-lg-6">
									<label class="d-block">Subscription Start Date</label>                                
									<div class="text-info"><h5><?php echo $timeBase; ?></h5></div>
								</div>
								<div class="form-group col-lg-6">
									<label class="d-block">Subscription End Date</label>                                
									<div class="text-info"><h5><?php echo $timeBases;?></h5></div>
								</div>
							</div>
                            <div class="form-group col-lg-8">
                                <label>Add Extra Subscription Duration (in days)</label>
								<input class="form-control" type="number" min="1" max="720" name="duration" id="duration" value="" required>						
                            </div>
                            <div class="mt-4 ml-3">
								<?php if($user_role==1){?>
                                <button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
								<?php }?>
									
                                <a href="<?php echo $base_url; ?>subscriptions-lists" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>