 <?php  
 if($this->session->userdata('usertype') != 'user') { 
	redirect(base_url());
 }
 ?>
<div class="col-xl-3 col-md-4">
				<div class="panel-style">
		   		<?php $user=$this->db->where('id',$this->session->userdata('id'))->get('users')->row();
		   		if(!empty($user->profile_img)){
		   			$profile_img=$user->profile_img;
		   		}else{
		   			$profile_img="assets/img/user.jpg";
		   		}
		   		?>
				<div class="mb-0">
					<div class="d-sm-flex flex-row text-center text-sm-start align-items-center sidebar-sets">
						<img alt="profile image"  src="<?php echo base_url().$profile_img; ?>"  class="avatar-lg rounded-circle">
						<div class="ms-sm-3 ms-md-0 ms-lg-3 mt-2 mt-sm-0 mt-md-2 mt-lg-0 info-blk-style">
							<h6 class="mb-0"><?php echo $this->session->userdata('name'); ?></h6>
							<p class="text-muted mb-0"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo date('M Y',strtotime($user->created_at));?></p>
						</div>
					</div>
				</div>
				<div class="widget settings-menu sidebar-setlinks">
					<ul role="tablist">	
						<li class="nav-item current">
							<a href="<?php echo base_url()?>user-dashboard" class="nav-link <?php echo  ($this->uri->segment(1)=="user-dashboard")?'active':'';?>">
								<i class="fas fa-desktop"></i>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></span>
							</a>
						</li>
						<li class="nav-item current">
							<a href="<?php echo base_url()?>user-bookings" class="nav-link <?php echo  ($this->uri->segment(1)=="user-bookings")?'active':'';?>">
								<i class="fas fa-calendar"></i>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_List'])) ? $user_language[$user_selected]['lg_Booking_List'] : $default_language['en']['lg_Booking_List']; ?></span>
							</a>
						</li>					
						<li class="nav-item">
							<a href="<?php echo base_url()?>user-settings" class="nav-link <?php echo  ($this->uri->segment(1)=="user-settings")?'active':'';?>">
								<i class="far fa-user"></i>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url()?>user-reviews" class="nav-link <?php echo  ($this->uri->segment(1)=="user-reviews")?'active':'';?>">
								<i class="far fa-star"></i>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url()?>user-payment" class="nav-link <?php echo  ($this->uri->segment(1)=="user-payment")?'active':'';?>">
								<i class="fas fa-dollar-sign"></i>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Payment'])) ? $user_language[$user_selected]['lg_Payment'] : $default_language['en']['lg_Payment']; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url()?>user-orders" class="nav-link <?php echo  ($this->uri->segment(1)=="user-orders")?'active':'';?>">
								<i class="fas fa-hashtag"></i>
								<span>
<?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : $default_language['en']['lg_my_orders']; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url()?>user-invoices" class="nav-link <?php echo  ($this->uri->segment(1)=="user-invoices")?'active':'';?>">
								<i class="far fa-calendar-alt"></i>
								<span>Invoices</span>
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
							<a href="<?php echo base_url()?>change-password" class="nav-link <?php echo  ($this->uri->segment(1)=="change-password")?'active':'';?>">
								<i class="fas fa-key"></i>
								<span>Change Password</span>
							</a>
						</li>
						
							<?php } ?>
					</ul>
				</div>
			</div>
			</div>