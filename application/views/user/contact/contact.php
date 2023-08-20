<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2><?php echo (!empty($user_language[$user_selected]['lg_contact'])) ? $user_language[$user_selected]['lg_contact'] : $default_language['en']['lg_contact']; ?></h2>
				</div>
			</div>
			<div class="col-auto float-end ms-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_contact'])) ? $user_language[$user_selected]['lg_contact'] : $default_language['en']['lg_contact']; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<?php
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
$contact_details = "367 Hillcrest Lane, Irvine, California, United States";
$mobile_number = "+21 895 158 6545";
$smtp_email_address = "craftesty@example.com";

$contact_us = $this->db->select('address, phone, email, widget_showhide,page_title')->get_where('footer_submenu', array('widget_name'=>'contact-widget'))->row();

 if($contact_us->widget_showhide == 1) { 
	$contact_details = $contact_us->address;
	$mobile_number = $contact_us->phone;
	$smtp_email_address = $contact_us->email;
 }elseif (!empty($result)) {
	foreach ($result as $data) {
		if ($data['key'] == 'contact_details') {
			$contact_details = $data['value'];
		}
		if ($data['key'] == 'mobile_number') {
			$mobile_number = $data['value'];
		}
		if ($data['key'] == 'smtp_email_address') {
			$smtp_email_address = $data['value'];
		}
	}
}

?>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="title-set">
				<h2>Get in Touch!</h2>
				<h5> Contact us for a quote, Drop your Queries </h5>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-4 col-md-6">
				<div class="contact-set-path">
					<div class="contact-set-img">
						<span><i class="fas fa-map-marker-alt"></i></span>
					</div>
					<div class="contact-set-content">
						<h2>Address </h2>
						<h5><?php echo $contact_details;?></h5>
					</div>
				</div>
				<div class="contact-set-path">
					<div class="contact-set-img">
						<span><i class="fas fa-phone-alt"></i></span>
					</div>
					<div class="contact-set-content">
						<h2>Phone  </h2>
						<h5> <?php echo $mobile_number;?></h5>
					</div>
				</div>
				<div class="contact-set-path">
					<div class="contact-set-img ">
						<span><i class="fas fa-mail-bulk"></i></span>
					</div>
					<div class="contact-set-content">
						<h2>Email </h2>
						<h5><?php echo $smtp_email_address;?></h5>
					</div>
				</div>
			</div>
			<div class="col-lg-8 col-md-6">
				<div class="contactus">
					<div class="contact-blk-content">
						<form method="post" enctype="multipart/form-data" id="contact_form" >
		          			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		    
							<div class="row">						
								<div class="col-lg-6">
									<div class="form-group">
										<label>Name</label>
										<input class="form-control" type="text" name="name" id="name" >
									</div>
								</div>	
								<div class="col-lg-6">
									<div class="form-group">
										<label>Email</label>
										<input class="form-control" type="text" name="email" id="email">
									</div>
								</div>					

								<div class="col-lg-12">
									<div class="form-group">
										<div class="text-center">
											<div id="load_div"></div>
										</div>
										<label>Message</label>
										<textarea class="form-control text-set" name="message" id="message" rows="5"></textarea>
									</div>
								</div>
							</div>
							<div class="submit-section">
								<button class="btn btn-updates"  type="submit" id="submit">Submit</button>
							</div>
						</form>					
					</div>
				</div>
			</div>
		</div>


	</div>
</div>

