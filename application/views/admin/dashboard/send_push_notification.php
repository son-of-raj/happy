<?php
   $module_details = $this->db->where('status',1)->get('admin_modules')->result_array();
   $packages = $this->db->select('id, subscription_name')->where('status',1)->where('type',1)->get('subscription_fee')->result_array();
 
   $city_qry = $this->db->query("SELECT * FROM `city` WHERE `state_id` IN (SELECT `id` FROM `state` WHERE `country_id` = (SELECT `id` FROM `country_table` WHERE `country_code` LIKE '%SA%')) ORDER BY `name` ASC");
   $city_res = $city_qry->result_array(); 
?>
<style>
select.form-control{display:inline-block;width:auto}
.sentto_div label{margin-bottom:0}
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-12 offset-xl-12">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title"><?php echo $title;?></h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form method="post" autocomplete="off" enctype="multipart/form-data" action="<?php echo $base_url?>admin/send-push-notification" method="post">
							<input type="hidden" name="id" value="<?php echo (!empty($user['user_id']))?$user['user_id']:''?>" id="user_id">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
							
							<div class="form-group">
								<label>Subject</label>
								<input type="text" name="subject" class="form-control" />
							</div>
							
							<div class="form-group">
								<label>Message</label>
								<textarea class="form-control" rows='10' name="message" id="message" ></textarea>
							</div>
							<div class="form-group sentto_div">
								<label>Send To</label>
								<div class="example1">
									<div class="mb-2"><input type="checkbox" name="selectall1" id="selectall1" class="all" value="1"> <label for="selectall1"><strong>Select all</strong></label></div>
									
									<div>
										<input type="checkbox" name="accesscheck[]" id="check_1" value="1"> <label for="check_1">User</label>
										<div class="col-lg-12">
											<label>Choose City</label>
											<select id="user_city" name="user_city" class="form-control select">
												<option value="">City</option>	
												<?php foreach($city_res as $cr){?>
													<option value="<?php echo $cr['id']; ?>"><?php echo $cr['name']; ?></option>
												<?php }?>	
											</select>
											<label>Is user booked service ever?</label>
											<select id="user_service" name="user_service" class="form-control select">
												<option value="all">All</option>							
												<option value="none">None</option>				
											</select>
											<label>The user who is visit the provider</label>
											<select id="user_visit" name="user_visit" class="form-control select">
												<option value="all">Any</option>								
												<option value="none">None</option>			
											</select>
											<label>at least one</label>
										</div>
									</div>								
									<div>
										<input type="checkbox" name="accesscheck[]" id="check_2" value="2"> <label for="check_2">Provider</label>
										<div class="col-lg-12">
											<label>Choose City</label>
											<select id="provider_city" name="provider_city" class="form-control select">
												<option value="">City</option>	
												<?php foreach($city_res as $pcr){?>
													<option value="<?php echo $pcr['id']; ?>"><?php echo $pcr['name']; ?></option>
												<?php }?>	
											</select>
											<label>Package Type</label>
											<select id="provider_package" name="provider_package" class="form-control select">
												<option value="">Package</option>	
												<?php foreach($packages as $pk){?>
													<option value="<?php echo $pk['id']; ?>"><?php echo $pk['subscription_name']; ?></option>
												<?php }?>
											</select>
											<label>Active or Not</label>
											<select id="provider_active" name="provider_active" class="form-control select">
												<option value="all">All</option>		
												<option value="none">None</option>							
											</select>
										</div>
									</div>	
									<div>
										<input type="checkbox" name="accesscheck[]" id="check_3" value="3"> <label for="check_3">Freelancer</label>
										<div class="col-lg-12">
											<label>Choose City</label>
											<select id="freelancer_city" name="freelancer_city" class="form-control select">
												<option value="">City</option>	
												<?php foreach($city_res as $fcr){?>
													<option value="<?php echo $fcr['id']; ?>"><?php echo $fcr['name']; ?></option>
												<?php }?>		
											</select>
											<label>Active or Not</label>
											<select id="provider_active" name="provider_active" class="form-control select">
												<option value="all">All</option>								
												<option value="none">None</option>	
											</select>
										</div>
									</div>	
								</div>
							</div>
							<div class="mt-4">
								<?php if($user_role==1){?>
								<button class="btn btn-primary " name="form_submit1" value="submit" type="submit">Submit</button>
							<?php }?>

								<a href="<?php echo $base_url; ?>admin/SendPushNotification"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

