<?php
   $user_details = $this->db->where('status != ',0)->get('providers')->result_array();
?>
<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Subscriber Details</h3>
				</div>
				<div class="col-auto text-end">
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		<ul class="nav nav-tabs menu-tabs">
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url().'subscriptions'; ?>">Provider Subscriptions</a>
			</li>
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url().'subscriptions-lists'; ?>">Subscriber Details</a>
			</li>
		</ul>
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>subscriptions-lists" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    

			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
					
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Provider Name</label>
								<select class="form-control select" name="username">
									<option value="">Select provider name</option>
									<?php foreach ($user_details as $user) { ?>
									<option value="<?php echo $user['name']?>"><?php echo $user['name']?></option>
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
                        <div class="table-responsive subscriptions-lists">
                            <table class="table custom-table mb-0 w-100 payment_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Provider Name</th>
                                        <th>Subscription</th>                       
                                        <th>Amount</th>                       
										<th>Duration</th>
										<th>Start Date</th>
										<th>End Date</th>
                                    	<th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									if(!empty($lists)) {
									$i=1;
									foreach ($lists as $rows) {
										$profile_img = $rows->profile_img;
										if(empty($profile_img)){
											$profile_img ='assets/img/user.jpg';
										}
										if($rows->type == 1){
											$urll = 'provider-details';
										} else {
											$urll = 'freelancer-details';
										} 
										$full_date =date('Y-m-d H:i:s', strtotime($rows->subscription_date));
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
										
										$full_dates =date('Y-m-d H:i:s', strtotime($rows->expiry_date_time));
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
										
										//Currency Convertion Based
										$currency_code = settingValue('currency_option'); 
										$currency_code_old = $rows->currency_code;
										$subscription_amount = get_gigs_currency($rows->fee, $currency_code_old, $currency_code);

										echo'<tr>
											<td>'.$i++.'</td>
											<td><h2 class="table-avatar"><a href="#" class="avatar avatar-sm me-2"> <img class="avatar-img rounded-circle" src="'.base_url().$profile_img.'"></a>
											<a href="'.base_url().$urll.'/'.$rows->id.'">'.str_replace('-', ' ', $rows->name).'</a></h2></td>
											
											
											<td>'.$rows->subscription_name.'</td>
											<td>'.$subscription_amount.'</td>
											<td>'.$rows->fee_description.'</td>
											<td>'.$timeBase.'</td>
                                             <td>'.$timeBases.'</td>';
											if(strtotime($rows->expiry_date_time) >= strtotime(date('Y-m-d H:i:s'))){
										
                                            echo '<td>
											<a href="'.base_url().'update-subscriptions/'.$rows->id.'" class="btn btn-sm bg-success-light me-3"><i class="far fa-edit me-1"></i> Update</a>
											
											</td>';
											} else {
												echo '<td>-</td>';
											}
											
										echo '</tr>';
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