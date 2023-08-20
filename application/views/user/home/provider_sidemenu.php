 <?php  
 if($this->session->userdata('usertype') == 'user') { 
	redirect(base_url());
 }
 ?>
 <div class="col-xl-3 col-md-4 theiaStickySidebar">
				
						<div class="panel-style">
						<?php $user=$this->db->where('id',$this->session->userdata('id'))->get('providers')->row();
if(!empty($user->profile_img) && file_exists($user->profile_img)){
		   			$profile_img=$user->profile_img;
		   		}else{
		   			$profile_img="assets/img/user.jpg";
		   		}
						?>
						<div class="mb-0">
							<div class="d-sm-flex flex-row text-center text-sm-start align-items-center sidebar-sets">
								<img alt="profile image" src="<?php echo base_url().$profile_img; ?>" class="avatar-lg rounded-circle">
								<div class="ms-sm-3 ms-md-0 ms-lg-3 mt-2 mt-sm-0 mt-md-2 mt-lg-0 info-blk-style">
									<h6 class="mb-0"><?php echo $user->name; ?></h6>
									<p class="mb-0"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo date('M Y',strtotime($user->created_at));?></p>
								</div>
							</div>
						</div>
                       
                   
						<div class="widget settings-menu sidebar-setlinks">
							<ul>
							<?php
                $verify_status = $user->commercial_verify;
                if ($verify_status == 1) {
                    ?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-settings" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-settings")?'active':'';?>" >
										<i class="far fa-user"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-subscription" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-subscription")?'active':'';?>" >
										<i class="far fa-calendar-alt"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Subscription'])) ? $user_language[$user_selected]['lg_Subscription'] : $default_language['en']['lg_Subscription']; ?></span>
									</a>
								</li>
                    <?php
                } else { ?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-dashboard" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-dashboard")?'active':'';?>">
										<i class="fas fa-desktop"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>my-services" class="nav-link <?php echo  ($this->uri->segment(1)=="my-services")?'active':'';?>">
										<i class="far fa-address-book"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>service-offer-history" class="nav-link <?php echo  ($this->uri->segment(1)=="service-offer-history")?'active':'';?>">
										<i class="fa fa-tag"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Service_Offer_History'])) ? $user_language[$user_selected]['lg_Service_Offer_History'] : $default_language['en']['lg_Service_Offer_History']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-bookings" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-bookings")?'active':'';?>">
										<i class="fas fa-calendar"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_List'])) ? $user_language[$user_selected]['lg_Booking_List'] : $default_language['en']['lg_Booking_List']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-orders" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-orders")?'active':'';?>">
										<i class="far fa-calendar-check"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_orders_list'])) ? $user_language[$user_selected]['lg_orders_list'] : $default_language['en']['lg_orders_list']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-settings" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-settings")?'active':'';?>" >
										<i class="far fa-user"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>shop" class="nav-link <?php echo  ($this->uri->segment(1)=="shop" || $this->uri->segment(1)=="my-shop-inactive")?'active':'';?>" >
										<i class="fas fa-shopping-bag"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?></span>
									</a>
								</li>
								<?php if ($this->session->userdata('usertype') == 'provider') {?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>staff-settings" class="nav-link <?php echo  ($this->uri->segment(1)=="staff-settings")?'active':'';?>" >
										<i class="fas fa-users"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Staff_Settings'])) ? $user_language[$user_selected]['lg_Staff_Settings'] : $default_language['en']['lg_Staff_Settings']; ?></span>
									</a>
								</li>
								<?php } ?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>coupons" class="nav-link <?php echo  ($this->uri->segment(1)=="coupons" || $this->uri->segment(1)=="coupon-details" )?'active':'';?>">
										<i class="fa fa-gift"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Coupons'])) ? $user_language[$user_selected]['lg_Coupons'] : $default_language['en']['lg_Coupons']; ?></span>
									</a>
								</li>
								<?php if($this->session->userdata('usertype') == 'provider') {?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>rewards" class="nav-link <?php echo  ($this->uri->segment(1)=="rewards" || $this->uri->segment(1)=='reward-details')?'active':'';?>" >
										<i class="fa fa-cogs"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Rewards'])) ? $user_language[$user_selected]['lg_Rewards'] : $default_language['en']['lg_Rewards']; ?></span>
									</a>
								</li>
								<?php } ?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-subscription" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-subscription")?'active':'';?>" >
										<i class="far fa-calendar-alt"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Subscription'])) ? $user_language[$user_selected]['lg_Subscription'] : $default_language['en']['lg_Subscription']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-availability"  class="nav-link <?php echo  ($this->uri->segment(1)=="provider-availability")?'active':'';?>" >
										<i class="far fa-clock"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Availability'])) ? $user_language[$user_selected]['lg_Availability'] : $default_language['en']['lg_Availability']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-reviews"  class="nav-link <?php echo  ($this->uri->segment(1)=="provider-reviews")?'active':'';?>" >
										<i class="far fa-star"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-payment"  class="nav-link <?php echo  ($this->uri->segment(1)=="provider-payment")?'active':'';?>" >
										<i class="fas fa-dollar-sign"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Payment'])) ? $user_language[$user_selected]['lg_Payment'] : $default_language['en']['lg_Payment']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-deposit-history"  class="nav-link <?php echo  ($this->uri->segment(1)=="provider-deposit-history")?'active':'';?>" >
										<i class="fas fa-money-bill-alt"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Deposit_History'])) ? $user_language[$user_selected]['lg_Deposit_History'] : $default_language['en']['lg_Deposit_History']; ?></span>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-invoices" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-invoices")?'active':'';?>">
										<i class="far fa-calendar-alt"></i>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Invoices'])) ? $user_language[$user_selected]['lg_Invoices'] : $default_language['en']['lg_Invoices']; ?></span>
									</a>
								</li>
								<?php 
						$query = $this->db->query("select * from system_settings WHERE status = 1");
						$result = $query->result_array();
						
						$login_type='';
						foreach ($result as $res) {
							
							if($res['key'] == 'login_type'){
								$login_type = $res['value'];
							}
							
							if($res['key'] == 'login_type'){
								$login_type = $res['value'];
							}

						}
							if($login_type=='email'){
							?>
								<li class="nav-item">
									<a href="<?php echo base_url()?>provider-change-password" class="nav-link <?php echo  ($this->uri->segment(1)=="provider-change-password")?'active':'';?>">
										<i class="fas fa-key"></i>
										<span>Change Password</span>
									</a>
								</li>
							<?php }
							} 
							?>
							</ul>
                        </div>
                    </div>
                         </div>