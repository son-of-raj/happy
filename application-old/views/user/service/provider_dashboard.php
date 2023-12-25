<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php 
$booking_count = $this->service->booking_count($this->session->userdata('id'));
$services_count = $this->service->services_count($this->session->userdata('id'));
$my_subscribe = $this->home->get_my_subscription(); 
if(!empty($my_subscribe)){
	$subscription_name=$this->db->where('id',$my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
}else{
	$subscription_name['subscription_name']='';
}
?>
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu');?>
			
			<div class="col-xl-9 col-md-8">
				<div class="row">
					<div class="col-lg-4">
						<a href="<?php echo base_url()?>provider-bookings" class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="avatar">
											<i class="far fa-calendar-alt avatar-title rounded-circle"></i>
										</div>
									</div>
									<div class="col-6">
										<div class="text-end">
											<h3><?php echo $booking_count?></h3>
											<p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_bookings'])) ? $user_language[$user_selected]['lg_bookings'] : $default_language['en']['lg_bookings']; ?></p>
										</div>
									</div>
								</div>
								
							</div>
						</a>
					</div>
					<div class="col-lg-4">
						<a href="<?php echo base_url()?>my-services" class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="avatar">
											<i class="fas fa-laptop-house avatar-title rounded-circle"></i>
										</div>
									</div>
									<div class="col-6">
										<div class="text-end">
											<h3><?php echo $services_count?></h3>
											<p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_services'])) ? $user_language[$user_selected]['lg_services'] : $default_language['en']['lg_services']; ?></p>
										</div>
									</div>
								</div>
								
							</div>
						</a>
					</div>
					<?php
					if(!empty($this->session->userdata('chat_token'))){
						$ses_token=$this->session->userdata('chat_token');
					}else{
						$ses_token='';
					}
  
					if(!empty($ses_token)){

						$ret=$this->db->select('*')->
						from('notification_table')->
						where('receiver',$ses_token)->
						where('status',1)->
						order_by('notification_id','DESC')->
						get()->result_array();
						
						$notification=[];
						if(!empty($ret)){ 
							foreach ($ret as $key => $value) {
								$user_table=$this->db->select('id,name,profile_img,token,type')->
								from('users')->
								where('token',$value['sender'])->
								get()->row();
								$provider_table=$this->db->select('id,name,profile_img,token,type')->
								from('providers')->
								where('token',$value['sender'])->
								get()->row();
								if(!empty($user_table)){
									$user_info= $user_table;
								}else{
									$user_info= $provider_table;
								}  
								$notification[$key]['name']= !empty($user_info->name)?$user_info->name:'';
								$notification[$key]['message']= !empty($value['message'])?$value['message']:'';
								$notification[$key]['profile_img']= !empty($user_info->profile_img)?$user_info->profile_img:'';
								$notification[$key]['utc_date_time']= !empty($value['utc_date_time'])?$value['utc_date_time']:'';
							}
						}
						$n_count=count($notification);
					}else{
						$n_count=0;
						$notification=[];
					}
					?>
					<div class="col-lg-4">
						<a href="<?php echo base_url()?>notification-list" class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="avatar">
											<i class="far fa-bell avatar-title rounded-circle"></i>
										</div>
									</div>
									<div class="col-6">
										<div class="text-end">
											<h3><?php echo $n_count;?></h3>
											<p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_Notifications'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?></p>
										</div>
									</div>
								</div>
								
							</div>
						</a>
					</div>
				</div>
				<?php if(!empty($my_subscribe)){?>
				<div class="card mb-0">
					<div class="row no-gutters">
						<div class="col-lg-8">
							<div class="card-body">
								<h6 class="title"><?php echo (!empty($user_language[$user_selected]['lg_plan_details'])) ? $user_language[$user_selected]['lg_plan_details'] : $default_language['en']['lg_plan_details']; ?></h6>
								<div class="sp-plan-name">
									<h6 class="title">
										<?php if(!empty($subscription_name['subscription_name'])){ ?>
										<?php echo $subscription_name['subscription_name'];?> <span class="badge badge-success badge-pill bg-success"><?php echo (!empty($user_language[$user_selected]['lg_active'])) ? $user_language[$user_selected]['lg_active'] : $default_language['en']['lg_active']; ?></span>
									<?php }else{?>
										Eterprice Plan <span class="badge badge-success badge-pill bg-success">Expired</span>
									<?php }?>
									</h6>
									<p><?php echo (!empty($user_language[$user_selected]['lg_subscription_id'])) ? $user_language[$user_selected]['lg_subscription_id'] : $default_language['en']['lg_subscription_id']; ?>: <span class="text-base">100394949</span></p>
								</div>
								<ul class="row">
									<li class="col-6 col-lg-6">
										<p><?php echo (!empty($user_language[$user_selected]['lg_started_on'])) ? $user_language[$user_selected]['lg_started_on'] : $default_language['en']['lg_started_on']; ?> <?php if(!empty($my_subscribe['subscription_date'])){echo date('d M, Y',strtotime($my_subscribe['subscription_date']));}?></p>
									</li>
									<?php  $user_currency = get_provider_currency();
										   $user_currency_code = $user_currency['user_currency_code'];                          
						        	?>
						 			<li class="col-6 col-lg-6">
										<p><?php echo (!empty($user_language[$user_selected]['lg_price'])) ? $user_language[$user_selected]['lg_price'] : $default_language['en']['lg_price']; ?> <?php if(!empty($subscription_name['fee'])){ echo currency_conversion($user_currency_code) . get_gigs_currency($subscription_name['fee'], $subscription_name['currency_code'], $user_currency_code); }
													?></p>
									</li>
								</ul>
								<h6 class="title"><?php echo (!empty($user_language[$user_selected]['lg_last_payment'])) ? $user_language[$user_selected]['lg_last_payment'] : $default_language['en']['lg_last_payment']; ?></h6>
								<ul class="row">
									<li class="col-sm-6">
										<p><?php echo (!empty($user_language[$user_selected]['lg_paid_at'])) ? $user_language[$user_selected]['lg_paid_at'] : $default_language['en']['lg_paid_at']; ?>  <?php if(!empty($my_subscribe['subscription_date'])){echo date('d M Y',strtotime($my_subscribe['subscription_date'])); }?></p>
									</li>
									<li class="col-sm-6">
										<p><span class="text-success"><?php echo (!empty($user_language[$user_selected]['lg_paid'])) ? $user_language[$user_selected]['lg_paid'] : $default_language['en']['lg_paid']; ?></span> <span class="amount"><?php if(!empty($subscription_name['fee'])){ echo currency_conversion($user_currency_code) . get_gigs_currency($subscription_name['fee'], $subscription_name['currency_code'], $user_currency_code);}?></span></p>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="sp-plan-action card-body">
								<div class="mb-2">
									<a href="<?php echo base_url().'provider-subscription'?>" class="btn btn-primary"><span><?php echo (!empty($user_language[$user_selected]['lg_change_plan'])) ? $user_language[$user_selected]['lg_change_plan'] : $default_language['en']['lg_change_plan']; ?></span></a>
								</div>
								<div class="next-billing">
									<p><?php echo (!empty($user_language[$user_selected]['lg_next_billing_on'])) ? $user_language[$user_selected]['lg_next_billing_on'] : $default_language['en']['lg_next_billing_on']; ?> <span><?php if(!empty($my_subscribe['subscription_date'])){ echo date('d M, Y',strtotime($my_subscribe['expiry_date_time']));}?></span></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php }?>
			</div>
        </div>
    </div>
</div>