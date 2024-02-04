 <?php
	if ($this->session->userdata('usertype') == 'user') {
		redirect(base_url());
	}
	?>

 <div class="col-xl-3 col-md-4 theiaStickySidebar">

 	<div class="panel-style">
 		<?php $user = $this->db->where('id', $this->session->userdata('id'))->get('employee_basic_details')->row();
			if (!empty($user->profile_img) && file_exists($user->profile_img)) {
				$profile_img = $user->profile_img;
			} else {
				$profile_img = "assets/img/user.jpg";
			}
			?>
 		<div class="mb-0">
 			<div class="d-sm-flex flex-row text-center text-sm-start align-items-center sidebar-sets">
 				<img alt="profile image" src="<?php echo base_url() . $profile_img; ?>" class="avatar-lg rounded-circle">
 				<div class="ms-sm-3 ms-md-0 ms-lg-3 mt-2 mt-sm-0 mt-md-2 mt-lg-0 info-blk-style">
 					<h6 class="mb-0"><?php echo $user->first_name; ?></h6>
 					<p class="mb-0"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo date('M Y', strtotime($user->created_at)); ?></p>
 				</div>
 			</div>
 		</div>


 		<div class="widget settings-menu sidebar-setlinks">
 			<ul>

 				<li class="nav-item">
 					<a href="<?php echo base_url() ?>manager-dashboard" class="nav-link <?php echo ($this->uri->segment(1) == "manager-dashboard") ? 'active' : ''; ?>">
 						<i class="fas fa-desktop"></i>
 						<span><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></span>
 					</a>
 				</li>

 				<li class="nav-item">
 					<a href="<?php echo base_url() ?>manager-bookings" class="nav-link <?php echo ($this->uri->segment(1) == "manager-bookings") ? 'active' : ''; ?>">
 						<i class="fas fa-calendar"></i>
 						<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_List'])) ? $user_language[$user_selected]['lg_Booking_List'] : $default_language['en']['lg_Booking_List']; ?></span>
 					</a>
 				</li>
 			</ul>
 		</div>
 	</div>
 </div>