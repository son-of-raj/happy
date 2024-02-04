<?php
$sticky_header = $this->db->get('header_settings')->row()->sticky_header;
$categories = $this->db->get_where('footer_submenu', array('widget_name' => 'Categories-Widget'))->row();
$follow_us = $this->db->select('widget_showhide,page_title,followus_link')->get_where('footer_submenu', array('widget_name' => 'social-widget'))->row();
$contact_us = $this->db->select('address, phone, email, widget_showhide,page_title')->get_where('footer_submenu', array('widget_name' => 'contact-widget'))->row();
$quick_link = $this->db->get_where('footer_submenu', array('id' => 2))->row();
$copyright = $this->db->select('link, widget_showhide,page_title,page_desc')->get_where('footer_submenu', array('widget_name' => 'copyright-widget'))->row();


$country_list = $this->db->where('status', 1)->order_by('country_name', "ASC")->get('country_table')->result_array();
$query1 = $this->db->query(" SELECT * FROM `footer_menu` WHERE `status` =  1 ");
if ($query1) {
	$footer_main_menu = $query1->result_array();
} else {
	$footer_main_menu = array();
}
$query2 = $this->db->query(" SELECT * FROM `footer_submenu` WHERE `status` =  1 ");
if ($query2) {
	$footer_sub_menu = $query2->result_array();
} else {
	$footer_sub_menu = array();
}

$sticky_header = $this->db->get('header_settings')->row()->sticky_header;

?>

<?php
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
$stripe_option = '1';
$publishable_key = '';
$live_publishable_key = '';
$logo_front = '';
$login_type = '';
foreach ($result as $res) {
	if ($res['key'] == 'stripe_option') {
		$stripe_option = $res['value'];
	}
	if ($res['key'] == 'publishable_key') {
		$publishable_key = $res['value'];
	}
	if ($res['key'] == 'live_publishable_key') {
		$live_publishable_key = $res['value'];
	}

	if ($res['key'] == 'login_type') {
		$login_type = $res['value'];
	}

	if ($res['key'] == 'login_type') {
		$login_type = $res['value'];
	}
	if ($res['key'] == 'map_key') {
		$map_key = $res['value'];
	}
}

if ($stripe_option == 1) {
	$stripe_key = $publishable_key;
} else {
	$stripe_key = $live_publishable_key;
}

if (!empty($logo_front)) {
	$web_log = base_url() . $logo_front;
} else {
	$web_log = base_url() . 'assets/img/logo.png';
}
?>
<?php
$type = $this->session->userdata('usertype');
$userId = $this->session->userdata('id');
$default_language = default_language();
$active_language = active_language();

if ($this->session->userdata('user_select_language') == '') {
	$lang = $default_language['language_value'];
} else {
	$lang = $this->session->userdata('user_select_language');
}
?>
<?php
$default_language_select = default_language();

if ($this->session->userdata('user_select_language') == '') {

	if ($default_language_select['tag'] == 'rtl') {

		echo '<script src="' . base_url() . 'assets/js/app-rtl.js"></script>';
	}
} else {
	if ($this->session->userdata('tag') == 'rtl') {

		echo '<script src="' . base_url() . 'assets/js/app-rtl.js"></script>';
	}
}
?>
<!-- Review Modal -->
<div class="modal fade" id="myReview">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Write_review'])) ? $user_language[$user_selected]['lg_Write_review'] : $default_language['en']['lg_Write_review']; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></label>
					<div class="star-rating rate">
						<input class="rates" id="star5" type="radio" name="rates" value="5">
						<label for="star5" title="5 stars">
							<i class="active fas fa-star"></i>
						</label>
						<input class="rates" id="star4" type="radio" name="rates" value="4">
						<label for="star4" title="4 stars">
							<i class="active fas fa-star"></i>
						</label>
						<input class="rates" id="star3" type="radio" name="rates" value="3">
						<label for="star3" title="3 stars">
							<i class="active fas fa-star"></i>
						</label>
						<input class="rates" id="star2" type="radio" name="rates" value="2">
						<label for="star2" title="2 stars">
							<i class="active fas fa-star"></i>
						</label>
						<input class="rates" id="star1" type="radio" name="rates" value="1">
						<label for="star1" title="1 star">
							<i class="active fas fa-star"></i>
						</label>
					</div>
				</div>
				<p class="error_rating error"><?php echo (!empty($user_language[$user_selected]['lg_Rating_required'])) ? $user_language[$user_selected]['lg_Rating_required'] : $default_language['en']['lg_Rating_required']; ?></p>
				<input type="hidden" id="myInput">
				<input type="hidden" id="booking_id">
				<input type="hidden" id="provider_id">
				<input type="hidden" id="user_id">
				<input type="hidden" id="service_id">

				<?php $rating_type = $this->db->where('status', 1)->get('rating_type')->result_array(); ?>
				<div class="form-group">
					<label><?php echo (!empty($user_language[$user_selected]['lg_title_of_review'])) ? $user_language[$user_selected]['lg_title_of_review'] : $default_language['en']['lg_title_of_review']; ?></label>
					<select name="type" id="type" class="form-control">
						<?php foreach ($rating_type as $type) {
						?>
							<option value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
						<?php } ?>
					</select>
					<p class="error_type error"><?php echo (!empty($user_language[$user_selected]['lg_Rating_type_required'])) ? $user_language[$user_selected]['lg_Rating_type_required'] : $default_language['en']['lg_Rating_type_required']; ?></p>
				</div>
				<div class="form-group">
					<label><?php echo (!empty($user_language[$user_selected]['lg_Your_review'])) ? $user_language[$user_selected]['lg_Your_review'] : $default_language['en']['lg_Your_review']; ?></label>
					<textarea class="form-control" rows="4" id="review"></textarea>
					<p class="error_review error"><?php echo (!empty($user_language[$user_selected]['lg_Review_required'])) ? $user_language[$user_selected]['lg_Review_required'] : $default_language['en']['lg_Review_required']; ?></p>
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-theme py-2 px-4 text-white mx-auto" id="rate_booking"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Review Modal -->

<!-- Cancel Modal -->
<div class="modal fade" id="myCancel">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Reason_Cancel'])) ? $user_language[$user_selected]['lg_Reason_Cancel'] : $default_language['en']['lg_Reason_Cancel']; ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" id="cancel_myInput">
				<input type="hidden" id="cancel_booking_id">
				<input type="hidden" id="cancel_provider_id">
				<input type="hidden" id="cancel_user_id">
				<input type="hidden" id="cancel_service_id">

				<div class="form-group">
					<label>Reason</label>
					<textarea class="form-control" rows="5" id="cancel_review"></textarea>
					<p class="error_cancel error"><?php echo (!empty($user_language[$user_selected]['lg_Reason_required'])) ? $user_language[$user_selected]['lg_Reason_required'] : $default_language['en']['lg_Reason_required']; ?></p>
				</div>
				<div class="text-center">
					<?php if ($this->session->userdata('type') == "user") { ?>
						<button type="button" class="btn btn-theme py-2 px-4 text-white mx-auto btn-primary" id="cancel_booking"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
					<?php } else { ?>
						<button type="button" class="btn btn-theme py-2 px-4 text-white mx-auto btn-primary" id="provider_cancel_booking"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Cancel Modal -->

<!-- Alert Modal -->
<div class="modal" id="account_alert">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Alert'])) ? $user_language[$user_selected]['lg_Alert'] : $default_language['en']['lg_Alert']; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<p><?php echo (!empty($user_language[$user_selected]['lg_Please_Enter_Profile_informations'])) ? $user_language[$user_selected]['lg_Please_Enter_Profile_informations'] : $default_language['en']['lg_Please_Enter_Profile_informations']; ?></p>
					<button type="button" class="btn btn-primary" id="go_user_settings"><?php echo (!empty($user_language[$user_selected]['lg_Ok'])) ? $user_language[$user_selected]['lg_Ok'] : $default_language['en']['lg_Ok']; ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Alert Modal -->

<!-- Account Alert Modal -->
<div class="modal" id="account_alert_provider">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Alert'])) ? $user_language[$user_selected]['lg_Alert'] : $default_language['en']['lg_Alert']; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<p><?php echo (!empty($user_language[$user_selected]['lg_Please_Fill_Account_Informations'])) ? $user_language[$user_selected]['lg_Please_Fill_Account_Informations'] : $default_language['en']['lg_Please_Fill_Account_Informations']; ?></p>
					<button type="button" class="btn btn-primary go_provider_availability"><?php echo (!empty($user_language[$user_selected]['lg_Ok'])) ? $user_language[$user_selected]['lg_Ok'] : $default_language['en']['lg_Ok']; ?></button>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /Account Alert Modal -->

<!-- Account Alert Modal -->
<div class="modal" id="account_alert_provider_sub">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Alert'])) ? $user_language[$user_selected]['lg_Alert'] : $default_language['en']['lg_Alert']; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<p><?php echo (!empty($user_language[$user_selected]['lg_Please_Subscripe'])) ? $user_language[$user_selected]['lg_Please_Subscripe'] : $default_language['en']['lg_Please_Subscripe']; ?></p>
					<button type="button" class="btn btn-primary go_provider_availability"><?php echo (!empty($user_language[$user_selected]['lg_Ok'])) ? $user_language[$user_selected]['lg_Ok'] : $default_language['en']['lg_Ok']; ?></button>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- /Account Alert Modal -->

<div class="modal" id="account_alert_provider_avail">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php echo (!empty($user_language[$user_selected]['lg_Alert'])) ? $user_language[$user_selected]['lg_Alert'] : $default_language['en']['lg_Alert']; ?></h4>
				<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<p><?php echo (!empty($user_language[$user_selected]['lg_Please_Fill_Availability'])) ? $user_language[$user_selected]['lg_Please_Fill_Availability'] : $default_language['en']['lg_Please_Fill_Availability']; ?></p>

					<button type="button" class="btn btn-primary go_provider_availability"><?php echo (!empty($user_language[$user_selected]['lg_Ok'])) ? $user_language[$user_selected]['lg_Ok'] : $default_language['en']['lg_Ok']; ?></button>
				</div>
			</div>

		</div>
	</div>
</div>

<form class="modal account-modal fade multi-step" id="modal-wizard" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header d-block p-0 border-0 overflow-hidden">
				<button type="button" class="close close_login m-0 position-absolute r-0" data-bs-dismiss="modal">&times;</button>
			</div>

			<div class="modal-body step-11" data-step="11">
				<div class="account-content">
					<div class="account-box">
						<div class="login-right">
							<div class="popheader">
								<h3><?php echo (!empty($user_language[$user_selected]['lg_Join_Professional'])) ? $user_language[$user_selected]['lg_Join_Professional'] : $default_language['en']['lg_Join_Professional']; ?></h3>
								<h6 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_Registration_Provider'])) ? $user_language[$user_selected]['lg_Registration_Provider'] : $default_language['en']['lg_Registration_Provider']; ?></h6>
							</div>
						</div>
						<label><?php echo (!empty($user_language[$user_selected]['lg_What_Service_do_you_Provide'])) ? $user_language[$user_selected]['lg_What_Service_do_you_Provide'] : $default_language['en']['lg_What_Service_do_you_Provide']; ?></label>
						<select class="form-control form-select" title="Category" name="categorys" id="categorys">
							<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Select_your_service'])) ? $user_language[$user_selected]['lg_Select_your_service'] : $default_language['en']['lg_Select_your_service']; ?></option>
						</select>
						<span class="category_error"></span>
					</div>
				</div>
			</div>

			<div class="modal-body step-22" data-step="22">
				<div class="account-content">
					<div class="account-box">
						<div class="login-right">
							<div class="popheader">
								<h3><?php echo (!empty($user_language[$user_selected]['lg_Join_Professional'])) ? $user_language[$user_selected]['lg_Join_Professional'] : $default_language['en']['lg_Join_Professional']; ?></h3>
								<h6 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_Registration_Provider'])) ? $user_language[$user_selected]['lg_Registration_Provider'] : $default_language['en']['lg_Registration_Provider']; ?></h6>
							</div>
						</div>
						<form id="new_second_page">
							<label><?php echo (!empty($user_language[$user_selected]['lg_Choose_the_Sub_Category'])) ? $user_language[$user_selected]['lg_Choose_the_Sub_Category'] : $default_language['en']['lg_Choose_the_Sub_Category']; ?></label>
							<select class="form-control form-select" title="Sub Category" name="subcategorys" id="subcategorys">
								<option value=''><?php echo (!empty($user_language[$user_selected]['lg_Enter_your_sub_category'])) ? $user_language[$user_selected]['lg_Enter_your_sub_category'] : $default_language['en']['lg_Enter_your_sub_category']; ?></option>
							</select>
						</form>
					</div>
				</div>
			</div>

			<div class="modal-body step-1" data-step="1">
				<div class="account-content">
					<div class="account-box">
						<div class="login-right">
							<div class="popheader">
								<h3><?php echo (!empty($user_language[$user_selected]['lg_Join_Professional'])) ? $user_language[$user_selected]['lg_Join_Professional'] : $default_language['en']['lg_Join_Professional']; ?></h3>
								<h6 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_Registration_Provider'])) ? $user_language[$user_selected]['lg_Registration_Provider'] : $default_language['en']['lg_Registration_Provider']; ?></h6>
							</div>
						</div>
						<form action="" method='post' id="new_third_page">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></label>
								<input type="text" class="form-control" name="userName" id='userName'>
							</div>
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?></label>
								<input type="text" class="form-control" name="userEmail" id='userEmail'>
								<input type="hidden" class="form-control" name="userLogintype" id='user_logintype' value="<?php echo $login_type ?>">
							</div>
							<?php
							if ($login_type == 'email') {
							?>
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="userPassword" id='UserPassword'>
								</div>
							<?php } ?>
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?></label>
								<div class="row">
									<div class="col-12 pe-0">
										<input type="hidden" name="login_mode" id="login_mode" value="1">
										<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="login_csrf">
										<input class="form-control userMobile" type="text" name="userMobile" id="userMobile" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_enter_mob_no'])) ? $user_language[$user_selected]['lg_enter_mob_no'] : $default_language['en']['lg_enter_mob_no']; ?>">
										<span id="mobile_no_error2"></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="pop-inputcheck mb-3">
									<ul>
										<li>
											<div class="company-path checkworking">
												<input type="checkbox" class="custom-control-input" name="agreeCheckbox" id="agree_checkbox" value="1">
												<label class="custom-control-label" for="agree_checkbox"><?php echo (!empty($user_language[$user_selected]['lg_agree'])) ? $user_language[$user_selected]['lg_agree'] : $default_language['en']['lg_agree']; ?> <?php echo settingValue('website_name') ?></label> <a tabindex="-1" href="<?php echo base_url() . 'privacy'; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Privacy_Policy'])) ? $user_language[$user_selected]['lg_Privacy_Policy'] : $default_language['en']['lg_Privacy_Policy']; ?></a> &amp; <a tabindex="-1" href="<?php echo base_url() . 'terms-conditions'; ?>"> <?php echo (!empty($user_language[$user_selected]['lg_Terms'])) ? $user_language[$user_selected]['lg_Terms'] : $default_language['en']['lg_Terms']; ?>.</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="form-group">
								<button id="registration_submit" type="submit" class="btn btn-primary login-btn"><?php echo (!empty($user_language[$user_selected]['lg_Register'])) ? $user_language[$user_selected]['lg_Register'] : $default_language['en']['lg_Register']; ?></button>
							</div>
							<div class="account-footer text-center">
								<?php echo (!empty($user_language[$user_selected]['lg_Already_Account'])) ? $user_language[$user_selected]['lg_Already_Account'] : $default_language['en']['lg_Already_Account']; ?> <a href="javascript:void(0);" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#tab_login_modal"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></a>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="modal-body step-4" data-step="4">
				<div class="popheader">
					<h3><?php echo (!empty($user_language[$user_selected]['lg_OTP'])) ? $user_language[$user_selected]['lg_OTP'] : $default_language['en']['lg_OTP']; ?></h3>
					<h6 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_Verification_account'])) ? $user_language[$user_selected]['lg_Verification_account'] : $default_language['en']['lg_Verification_account']; ?></h6>
				</div>
				<form action="<?php echo base_url() ?>user/login/send_otp_request" method='post' id="new_fourth_page">
					<div class="form-group">
						<input type="hidden" class="form-control form-control-lg" placeholder="Mobile Number" name='enteredMobile' id='enteredMobile'>
					</div>
					<?php if (settingValue('default_otp') == 1) { ?>
						<div class="alert alert-danger text-center" role="alert">
							<?php echo (!empty($user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'])) ? $user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'] : $default_language['en']['lg_Default_OTP_Demo_Purpose']; ?><br> <strong><?php echo (!empty($user_language[$user_selected]['lg_Default_OTP'])) ? $user_language[$user_selected]['lg_Default_OTP'] : $default_language['en']['lg_Default_OTP']; ?></strong>
						</div>
					<?php } else { ?>
						<div class="alert alert-success text-center" role="alert">
							<strong><?php echo (!empty($user_language[$user_selected]['lg_We Have_OTP'])) ? $user_language[$user_selected]['lg_We Have_OTP'] : $default_language['en']['lg_We Have_OTP']; ?></strong>
							<strong><?php echo (!empty($user_language[$user_selected]['lg_Registration_Provider'])) ? $user_language[$user_selected]['lg_Registration_Provider'] : $default_language['en']['lg_Registration_Provider']; ?> </strong>
						</div>
					<?php } ?>
					<div class="form-group">
						<input type="text" class="form-control form-control-lg no_only" maxlength="4" autocomplete="off" minlength="4" placeholder=" <?php echo (!empty($user_language[$user_selected]['lg_Enter_OTP_Here'])) ? $user_language[$user_selected]['lg_Enter_OTP_Here'] : $default_language['en']['lg_Enter_OTP_Here']; ?>...." name="otp_number" id='otp_number'>
						<span for='otp_number' class="error" id='otp_error_msg'><?php echo (!empty($user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'])) ? $user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'] : $default_language['en']['lg_Please_Check_Your_Registered_Mobile']; ?></span>
					</div>
					<p class="resend-otp"><?php echo (!empty($user_language[$user_selected]['lg_Didnt_receive_the_OTP'])) ? $user_language[$user_selected]['lg_Didnt_receive_the_OTP'] : $default_language['en']['lg_Didnt_receive_the_OTP']; ?> <a href="#" id="re_send_otp_provider"> <?php echo (!empty($user_language[$user_selected]['lg_Resend_OTP'])) ? $user_language[$user_selected]['lg_Resend_OTP'] : $default_language['en']['lg_Resend_OTP']; ?></a></p>
					<div>
						<button id='registration_final_old' type="submit" class="login-btn mb-2"><?php echo (!empty($user_language[$user_selected]['lg_Finish'])) ? $user_language[$user_selected]['lg_Finish'] : $default_language['en']['lg_Finish']; ?></button>
					</div>
					<div>
						<button id='registration_resend_new' type="button" class="login-btn"><?php echo (!empty($user_language[$user_selected]['lg_Resend'])) ? $user_language[$user_selected]['lg_Resend'] : $default_language['en']['lg_Resend']; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</form>

<div class="modal account-modal fade multi-step" id="modal-wizard1" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-popupcus">
		<div class="modal-content">
			<div class="modal-header">
				<div class="popheader">
					<h3><?php echo (!empty($user_language[$user_selected]['lg_Join_User'])) ? $user_language[$user_selected]['lg_Join_User'] : $default_language['en']['lg_Join_User']; ?></h3>
					<h6><?php echo (!empty($user_language[$user_selected]['lg_Registration_Customer'])) ? $user_language[$user_selected]['lg_Registration_Customer'] : $default_language['en']['lg_Registration_Customer']; ?></h6>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
				</div>
			</div>
			<div class="header-content-blk text-center">
				<div class="alert alert-success text-center" id="flash_succ_message2"></div>
			</div>
			<div class="modal-body step-1" data-step="1">

				<div class="account-content">
					<div class="account-box">
						<div class="login-right">
							<form action="" method='post' id="new_third_page_user">
								<div class="pop-input form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?></label>
									<input type="text" class="form-control" name="userName" id='user_name'>
								</div>
								<div class="pop-input form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?></label>
									<input type="email" class="form-control" name="userEmail" id='user_email'>
									<input type="hidden" class="form-control" name="user_logintype" id='user_logintype' value="<?php echo $login_type ?>">
								</div>
								<?php
								if ($login_type == 'email') {
								?>
									<div class="pop-input form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Password'])) ? $user_language[$user_selected]['lg_Password'] : $default_language['en']['lg_Password']; ?></label>
										<input type="password" class="form-control" name="userPassword" id='user_password'>
									</div>
								<?php } ?>
								<div class="pop-input form-group">
									<label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?> <span class="manidatory">*</span></label>
									<div class="row">
										<div class="col-12 pe-0">
											<input type="hidden" name="login_mode" id="login_mode" value="1">
											<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="login_csrf">
											<input class="form-control userMobile" type="text" name="userMobile" id="user_mobile" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?>">
											<span id="mobile_no_error3"></span>
										</div>
									</div>
								</div>
								<div class="pop-inputradio form-group">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_gender'])) ? $user_language[$user_selected]['lg_gender'] : $default_language['en']['lg_gender']; ?></label>
										<ul>
											<li>
												<div class="custom-radio">
													<label>
														<input type="radio" value="1" name="gender" checked> <?php echo (!empty($user_language[$user_selected]['lg_Male'])) ? $user_language[$user_selected]['lg_Male'] : $default_language['en']['lg_Male']; ?>
														<span></span>
													</label>
												</div>
											</li>
											<li>
												<div class="custom-radio">
													<label>
														<input type="radio" name="gender" value="2"> <?php echo (!empty($user_language[$user_selected]['lg_Female'])) ? $user_language[$user_selected]['lg_Female'] : $default_language['en']['lg_Female']; ?>
														<span></span>
													</label>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<div class="pop-inputcheck form-group mb-3">
									<ul>
										<li>
											<div class="company-path checkworking">
												<input type="checkbox" class="custom-control-input" name="agree_checkbox_user" id="agree_checkbox_user" value="1">
												<label class="custom-control-label" for="agree_checkbox_user"><?php echo (!empty($user_language[$user_selected]['lg_agree'])) ? $user_language[$user_selected]['lg_agree'] : $default_language['en']['lg_agree']; ?> <?php echo settingValue('website_name') ?></label> <a tabindex="-1" href="<?php echo base_url() . 'privacy'; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Privacy_Policy'])) ? $user_language[$user_selected]['lg_Privacy_Policy'] : $default_language['en']['lg_Privacy_Policy']; ?></a> &amp; <a tabindex="-1" href="<?php echo base_url() . 'terms-conditions'; ?>"> <?php echo (!empty($user_language[$user_selected]['lg_Terms'])) ? $user_language[$user_selected]['lg_Terms'] : $default_language['en']['lg_Terms']; ?>.</a>
											</div>
										</li>
									</ul>
								</div>
								<div class="form-group">
									<button id="registration_submit_user" type="submit" class="btn btn-login"><?php echo (!empty($user_language[$user_selected]['lg_Register'])) ? $user_language[$user_selected]['lg_Register'] : $default_language['en']['lg_Register']; ?></button>
								</div>
								<div class="account-footer text-center">
									<?php echo (!empty($user_language[$user_selected]['lg_Already_Account'])) ? $user_language[$user_selected]['lg_Already_Account'] : $default_language['en']['lg_Already_Account']; ?> <a href="javascript:void(0);" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#tab_login_modal"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-body step-2" data-step="2">
				<div class="login-header">
					<h3><?php echo (!empty($user_language[$user_selected]['lg_OTP'])) ? $user_language[$user_selected]['lg_OTP'] : $default_language['en']['lg_OTP']; ?></h3>
					<p class="text-muted">
					<p class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_Verification_account'])) ? $user_language[$user_selected]['lg_Verification_account'] : $default_language['en']['lg_Verification_account']; ?></p>
					</p>
				</div>
				<form action="<?php echo base_url() ?>user/login/send_otp_request_user" method='post' id="new_fourth_page_user">
					<div class="form-group">
						<input type="hidden" class="form-control form-control-lg" placeholder="Mobile Number" name='enteredMobile' id='enteredMobiles'>
					</div>
					<div class="form-group">
						<?php if (settingValue('default_otp') != 1) { ?>
							<div class="alert alert-success text-center" role="alert">
								<strong><?php echo (!empty($user_language[$user_selected]['lg_We_Have_OTP'])) ? $user_language[$user_selected]['lg_We_Have_OTP'] : $default_language['en']['lg_We_Have_OTP']; ?>.</strong>
								<strong><?php echo (!empty($user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'])) ? $user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'] : $default_language['en']['lg_Please_Check_Your_Registered_Mobile']; ?> </strong>
							</div>
						<?php } else {  ?>
							<div class="alert alert-danger text-center" role="alert">
								<?php echo (!empty($user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'])) ? $user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'] : $default_language['en']['lg_Default_OTP_Demo_Purpose']; ?><br> <strong><?php echo (!empty($user_language[$user_selected]['lg_Default_OTP'])) ? $user_language[$user_selected]['lg_Default_OTP'] : $default_language['en']['lg_Default_OTP']; ?></strong>
							</div>
						<?php } ?>
						<input type="text" class="form-control form-control-lg no_only" autocomplete="off" maxlength="4" minlength="4" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Enter_OTP_Here'])) ? $user_language[$user_selected]['lg_Enter_OTP_Here'] : $default_language['en']['lg_Enter_OTP_Here']; ?>.." name="otp_number" id='otp_number_user'>
						<span for='otp_number' id='otp_error_msg'></span>
					</div>

					<p class="resend-otp"><?php echo (!empty($user_language[$user_selected]['lg_Didnt_receive_the_OTP'])) ? $user_language[$user_selected]['lg_Didnt_receive_the_OTP'] : $default_language['en']['lg_Didnt_receive_the_OTP']; ?> <a href="#" id="re_send_otp_user"> <?php echo (!empty($user_language[$user_selected]['lg_Resend_OTP'])) ? $user_language[$user_selected]['lg_Resend_OTP'] : $default_language['en']['lg_Resend_OTP']; ?></a></p>
					<div>
						<button id='registration_final' type="submit" class="login-btn mb-2"><?php echo (!empty($user_language[$user_selected]['lg_Enter'])) ? $user_language[$user_selected]['lg_Enter'] : $default_language['en']['lg_Enter']; ?></button>
					</div>
					<div>
						<button id='registration_resend' type="button" class="login-btn"><?php echo (!empty($user_language[$user_selected]['lg_Resend'])) ? $user_language[$user_selected]['lg_Resend'] : $default_language['en']['lg_Resend']; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal account-modal fade multi-step" id="modal-wizardreset" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header p-0 border-0">
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="header-content-blk text-center">
				<div class="alert alert-success text-center" id="flash_succ_message2"></div>
			</div>
			<div class="modal-body step-1" data-step="1">

				<div class="account-content">
					<div class="account-box">
						<div class="login-right">
							<div class="login-header">
								<h3>Forgot Password </h3>
								<p class="text-muted">Reset Email ID</p>
							</div>

							<form method='post' id="forgetpwddiv">
								<div class="form-group">
									<label>Email ID</label>
									<input type="text" name="login_mode1" id="login_mode1" value="1">
									<input type="hidden" class="form-control" name="userLogintype" id='user_logintype' value="<?php echo $login_type ?>">
									<input type="text" class="form-control" name="forgot_mailid" id='forgot_mailid'>
								</div>
								<div class="form-group">
									<button id="forgotpwdmail" type="submit" class="login-btn btn">Submit</button>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-body step-2" data-step="2">
				<div class="login-header">
					<h3><?php echo (!empty($user_language[$user_selected]['lg_OTP'])) ? $user_language[$user_selected]['lg_OTP'] : $default_language['en']['lg_OTP']; ?></h3>
					<p class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_Verification_account'])) ? $user_language[$user_selected]['lg_Verification_account'] : $default_language['en']['lg_Verification_account']; ?></p>
				</div>
				<form action="<?php echo base_url() ?>user/login/send_otp_request_user" method='post' id="new_fourth_page_user">
					<div class="form-group">
						<input type="hidden" class="form-control form-control-lg" placeholder="Mobile Number" name='enteredMobile' id='enteredMobiles'>
					</div>
					<div class="form-group">
						<div class="alert alert-success text-center" role="alert">
							<strong><?php echo (!empty($user_language[$user_selected]['lg_We_Have_OTP1'])) ? $user_language[$user_selected]['lg_We_Have_OTP1'] : $default_language['en']['lg_We_Have_OTP1']; ?>.</strong>
							<strong><?php echo (!empty($user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'])) ? $user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'] : $default_language['en']['lg_Please_Check_Your_Registered_Mobile']; ?> </strong>
						</div>
						<?php if (settingValue('default_otp') == 1) { ?>
							<div class="alert alert-danger text-center" role="alert">
								<?php echo (!empty($user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'])) ? $user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'] : $default_language['en']['lg_Default_OTP_Demo_Purpose']; ?><br> <strong><?php echo (!empty($user_language[$user_selected]['lg_Default_OTP'])) ? $user_language[$user_selected]['lg_Default_OTP'] : $default_language['en']['lg_Default_OTP']; ?></strong>
							</div>
						<?php } ?>
						<input type="text" class="form-control form-control-lg no_only" autocomplete="off" maxlength="4" minlength="4" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Enter_OTP_Here'])) ? $user_language[$user_selected]['lg_Enter_OTP_Here'] : $default_language['en']['lg_Enter_OTP_Here']; ?>.." name="otp_number" id='otp_number_user'>
						<span for='otp_number' id='otp_error_msg'></span>
					</div>

					<p class="resend-otp"><?php echo (!empty($user_language[$user_selected]['lg_Didnt_receive_the_OTP'])) ? $user_language[$user_selected]['lg_Didnt_receive_the_OTP'] : $default_language['en']['lg_Didnt_receive_the_OTP']; ?> <a href="#" id="re_send_otp_user"> <?php echo (!empty($user_language[$user_selected]['lg_Resend_OTP'])) ? $user_language[$user_selected]['lg_Resend_OTP'] : $default_language['en']['lg_Resend_OTP']; ?></a></p>
					<div>
						<button id='registration_final' type="submit" class="login-btn mb-2"><?php echo (!empty($user_language[$user_selected]['lg_Enter'])) ? $user_language[$user_selected]['lg_Enter'] : $default_language['en']['lg_Enter']; ?></button>
					</div>
					<div>
						<button id='registration_resend' type="button" class="login-btn"><?php echo (!empty($user_language[$user_selected]['lg_Resend'])) ? $user_language[$user_selected]['lg_Resend'] : $default_language['en']['lg_Resend']; ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<footer class="footer">


	<input type="hidden" id="stripe_key" value="<?php echo $stripe_key; ?>">
	<input type="hidden" id="logo_front" value="<?php echo $web_log; ?>">
	<?php
	$cat_view = $categories->category_view;
	$cat_count = $categories->category_count;
	if ($cat_view == 'Orderby') {
		$this->db->order_by('id', 'ASC');
	}
	if ($cat_view == 'Name') {
		$this->db->order_by('category_name', 'ASC');
	}
	if ($cat_view == 'Popular category') {
		$this->db->order_by('id', 'ASC');
	}
	if ($cat_view == 'Recent category') {
		$this->db->where('date(created_at) >= ( CURDATE() - INTERVAL 7 DAY )');
	}
	$this->db->select('*');
	$this->db->from('categories');
	$this->db->where(array('status' => 1));
	$this->db->limit($cat_count);
	$result = $this->db->get()->result_array();
	?>

	<!-- Footer Top -->
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<?php if ($categories->widget_showhide == 1) { ?>
					<div class="col-lg-3 col-md-6">
						<div class="footer-widget footer-menu">
							<h2 class="footer-title"><?php echo str_replace('_', ' ', $categories->page_title); ?></h2>
							<div class="footer-line">
								<span></span>
							</div>
							<div class="about">
								<div class="contactdet">
									<ul>

										<?php foreach ($result as $res) { ?>
											<li><a href="<?php echo base_url(); ?>search/<?php echo str_replace(' ', '-', strtolower($res['category_slug'])); ?>"><?php echo ucfirst($res['category_name']); ?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php }  ?>
				<?php if ($quick_link->link != 'null' && !empty($quick_link->link) && $quick_link->widget_showhide == 1) { ?>
					<div class="col-lg-3 col-md-6">
						<div class="footer-widget footer-menu">
							<h2 class="footer-title"><?php echo str_replace('_', ' ', $quick_link->page_title); ?></h2>
							<div class="about">
								<div class="contactdet">
									<ul>
										<?php
										$lang_val = ($this->session->userdata('lang')) ? $this->session->userdata('lang') : 'en';
										$this->db->where('language_value', $lang_val);
										$lang_name = $this->db->get('language')->row_array();
										$this->db->where('lang_id', $lang_name['id']);
										$this->db->where('location', '2');
										$this->db->where('delete_status', '1');
										$this->db->where('visibility', '1');
										$quick_name = $this->db->get('pages_list')->result_array();
										$quickLink = json_decode($quick_link->link);
										foreach ($quickLink as $key => $link) {
											if (!empty($link)) {
										?>
												<li><a href="<?php echo $link->link; ?>"><?php echo ucfirst($link->label); ?></a></li>
											<?php }
										}
										foreach ($quick_name as $quick) { ?>
											<li><a href="<?php echo $base_url; ?>pages-details/<?php echo $quick['slug']; ?>"><?php echo $quick['title']; ?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-lg-3 col-md-6">
						<div class="footer-widget footer-menu">
							<h2 class="footer-title"><?php echo str_replace('_', ' ', $quick_link->page_title); ?></h2>
							<div class="about">
								<div class="contactdet">
									<ul>
										<li><a href="<?php echo base_url(); ?>about-us">About Us</a></li>

										<li><a href="<?php echo base_url(); ?>contact">Contact Us</a></li>

										<li><a href="<?php echo base_url(); ?>faq">FAQ</a></li>
										<?php
										$lang_val = ($this->session->userdata('user_select_language')) ? $this->session->userdata('user_select_language') : 'en';
										$this->db->where('language_value', $lang_val);
										$lang_name = $this->db->get('language')->row_array();
										$this->db->where('lang_id', $lang_name['id']);
										$this->db->where('location', '2');
										$this->db->where('delete_status', '1');
										$this->db->where('visibility', '1');
										$quick_name = $this->db->get('pages_list')->result_array(); ?>
										<?php foreach ($quick_name as $quick) { ?>
											<li><a href="<?php echo $base_url; ?>pages-details/<?php echo $quick['slug']; ?>"><?php echo $quick['title']; ?></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if ($contact_us->widget_showhide == 1) { ?>
					<div class="col-lg-3 col-md-6">
						<div class="footer-widget footer-menu">
							<h2 class="footer-title"><?php echo str_replace('_', ' ', $contact_us->page_title); ?></h2>
							<div class="footer-text">
								<p><?php echo $contact_us->address; ?></p>
								<span>telephone: <a href="tel:<?php echo $contact_us->phone; ?>"><?php echo $contact_us->phone; ?></a></span><br>
								<span>mail: <a href="mailto:<?php echo $contact_us->email; ?>"><?php echo $contact_us->email; ?></a></span>
							</div>

						</div>
					</div>
				<?php } ?>
				<?php if ($follow_us->widget_showhide == 1) { ?>
					<div class="col-lg-3 col-md-6">
						<div class="footer-widget footer-menu">
							<h2 class="footer-title"><?php echo str_replace('_', ' ', $follow_us->page_title); ?></h2>
							<div class="about">
								<div class="contactdet">
									<div class="social-icon">
										<ul>
											<?php
											$followUs = json_decode($follow_us->followus_link);
											foreach ($followUs as $key => $value) {
												if (!empty($value)) {
													if ($key == 'facebook') {
											?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
													<?php
													}
													if ($key == 'twitter') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-twitter"></i></a></li>
													<?php
													}
													if ($key == 'youtube') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-youtube"></i></a></li>
													<?php
													}
													if ($key == 'linkedin') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-linkedin"></i></a></li>

													<?php
													}
													if ($key == 'github') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-github"></i></a></li>
													<?php
													}
													if ($key == 'instagram') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-instagram"></i></a></li>
													<?php
													}
													if ($key == 'gplus') {
													?>
														<li><a href="<?php echo $value; ?>" target="_blank"><i class="fab fa-google"></i></a></li>
													<?php
													}
													?>
											<?php }
											} ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php }	?>
			</div>
		</div>
	</div>
	<!-- /Footer Top -->

	<!-- Footer Bottom -->
	<?php if ($copyright->widget_showhide == 1) { ?>
		<!-- Footer Bottom -->
		<div class="footer-bottom">
			<div class="container">

				<!-- Copyright -->

				<div class="copyright">
					<div class="row">
						<div class="col-md-6 col-lg-6">
							<div class="copyright-text">
								<p class="mb-0"><?php echo $copyright->page_desc; ?></p>
							</div>
						</div>
						<div class="col-md-6 col-lg-6">

							<!-- Copyright Menu -->
							<?php if (!empty($copyright->link) && $copyright->link != 'null') {  ?>
								<div class="copyright-menu">
									<ul class="policy-menu">
										<?php $crLinks = json_decode($copyright->link);
										foreach ($crLinks as $key => $crlink) {
											if ($crlink->url != '' && $crlink->name != '') { ?>
												<li><a href="<?php echo $crlink->url; ?>"><?php echo $crlink->name; ?></a></li>
										<?php }
										} ?>

									</ul>
								</div>
							<?php } else { ?>
								<div class="copyright-menu">
									<ul class="policy-menu">
										<li><a href="<?php echo base_url(); ?>privacy">Privacy</a></li>

										<li><a href="<?php echo base_url(); ?>terms-conditions">Terms & Conditions</a></li>
									</ul>
								</div>

							<?php } ?>
							<!-- /Copyright Menu -->

						</div>
					</div>
				</div>

				<!-- /Copyright -->

			</div>
		</div>
		<!-- /Footer Bottom -->
	<?php } ?>

	<!-- /Footer Bottom -->
	<!-- Footer Navbar -->
	<?php
	if (($this->session->userdata('id') != '') && ($this->session->userdata('usertype') == 'user')) {
		$booking_url = base_url() . 'user-bookings';
	} else if (($this->session->userdata('id') != '') && ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer')) {
		$booking_url = base_url() . 'provider-bookings';
	}
	?>
	<?php if (($this->session->userdata('id') != '')) { ?>
		<div class="footer_ footermenu_mobile">
			<div class="container">
				<div class="bottom-content row">
					<div class="bottom-bar col p-0">
						<a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i></a>
						<div>Home</div>
					</div>
					<div class="bottom-bar col p-0">
						<a href="<?php echo $booking_url; ?>"><i class="far fa-calendar-check"></i></a>
						<div>Bookings</div>
					</div>
					<div class="bottom-bar col p-0">
						<a href="<?php echo base_url() . 'search' ?>"><i class="fa fa-search"></i></a>
						<div>Search</div>
					</div>
					<div class="bottom-bar col p-0">
						<a href="<?php echo base_url() . 'user-chat' ?>"><i class="fa fa-comments"></i></a>
						<div>Chat</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<!-- /Footer Navbar -->
	<div id="my_map" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<div class="popheader">
						<h3 class="mb-4">Map</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
					</div>
				</div>
				<div class="modal-body">
					<p class="mb-3">Drag and point to get latitude and longitude</p>
					<div class="row">
						<div class="col-md-8">
							<input type="text" id="autocomplete" class="form-control" name="address" value="<?php echo $this->session->userdata('user_address'); ?>">
							<div class="row">
								<div class="col-md-6"><input type="hidden" id="user_latitude" value="<?php echo $this->session->userdata('latitude'); ?>" class="form-control"></div>
								<div class="col-md-6"><input type="hidden" id="user_longitude" value="<?php echo $this->session->userdata('longitude'); ?>" class="form-control"></div>
							</div>
						</div>
						<?php if (settingValue('location_type') == 'live') { ?>
							<div class="col-md-4">
								<button onclick="setlocation()" class="setlocation btn btn-primary">Set Location</button>
								<button onclick="clearlocation()" class="setlocation btn btn-danger">Clear Location</button>
							</div>
						<?php } ?>
					</div>
					<input type="range" name="distance" id="distance" min="5" max="500" class="mt-4" value="<?php echo $this->session->userdata('distance'); ?>">
					<h5 class="mb-3">Distance : <span id="slider_value"><?php echo $this->session->userdata('distance'); ?></span></h5>
					<div id="map"></div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>

</footer>
</div>
<input type="hidden" name="sticky_header" id='sticky_header' value="<?php echo $sticky_header; ?>">
<input type="hidden" name="country_code_key" id='country_code_key' value="<?php echo settingValue('country_code_key'); ?>">
<input type="hidden" id="base_url" value="<?php echo $base_url; ?>">
<input type="hidden" id="site_name" value="<?php echo $this->website_name; ?>">
<input type="hidden" id="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden" id="csrfName" value="<?php echo $this->security->get_csrf_token_name(); ?>">
<input type="hidden" id="csrfHash" value="<?php echo $this->security->get_csrf_hash(); ?>">

<script src="<?php echo $base_url; ?>assets/js/moment.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/bootstrap-datetimepicker.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/intlTelInput.js"></script>

<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/datatables.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/cropper_profile_provider.js"></script>
<script src="<?php echo base_url(); ?>assets/js/cropper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/script_crop.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapValidator.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.min.js"></script>

<!-- Sticky Sidebar JS -->
<script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
<!-- Toaster -->
<script src="<?php echo base_url(); ?>assets/plugins/toaster/toastr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/owlcarousel/owl.carousel.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- Slick JS -->
<script src="<?php echo base_url() ?>assets/js/slick.js"></script>


<?php if ($this->uri->segment(1) == "add-staff" || $this->uri->segment(1) == "edit-staff" || $this->uri->segment(1) == "book-appointment" || $this->uri->segment(1) == "edit-appointment") { ?>
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<?php } ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key ?>&v=3.exp&libraries=places&language=<?php echo $lang; ?>"></script>
<input type="hidden" name="google_map_api" id="google_map_api" value="<?php echo $map_key ?>">

<input type="hidden" id="modules_page" value="<?php echo $module; ?>">
<input type="hidden" id="current_page" value="<?php echo $this->uri->segment(1); ?>">
<input type="hidden" id="cookies_showhide" value="<?php echo settingValue('cookies_showhide'); ?>">
<input type="hidden" id="cookies_content_text" value="<?php echo settingValue('cookies'); ?>">
<?php if (settingValue('cookies_showhide') == 1) { ?>
	<script src="<?php echo base_url() ?>assets/js/cookie.min.js"></script>
<?php } ?>

<?php
$edit_service = $this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3);
if ($this->uri->segment(1) != 'add-service' || $edit_service != 'user/service/edit_service' && $this->uri->segment(1) != 'update_booking' && $this->uri->segment(1) != 'update_booking') { ?>
	<input type="hidden" id="service_location" class="asf">
<?php
}
?>
<?php if ($module == 'service' || $module == 'chat' || $module == 'terms' || $module == 'privacy') { ?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/tagsinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/service.js"></script>
<?php } ?>
<?php
if ($module == 'products') {
?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/tagsinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/products.js"></script>
<?php
}
?>
<?php
if ($module == 'home') { ?>
	<?php if (!empty($this->uri->segment(1))) { ?>
		<input type='hidden' id='user_address'>
	<?php } ?>
	<input type="hidden" id="user_address_values" value="<?php echo $this->session->userdata('user_address'); ?>">
	<input type="hidden" id="user_latitude_values" value="<?php echo $this->session->userdata('user_latitude'); ?>">
	<input type="hidden" id="user_longitude_values" value="<?php echo $this->session->userdata('user_longitude'); ?>">
	<?php if (settingValue('location_type') == 'live') { ?>
		<script src="<?php echo base_url(); ?>assets/js/place.js"></script>
	<?php } ?>
<?php } ?>

<script src="<?php echo base_url(); ?>assets/js/multi-step-modal.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.css">
<script src="<?php echo base_url(); ?>assets/plugins/jquery-confirm/jquery-confirm.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/functions.js"></script>
<input type="hidden" id="user_type" value="<?php echo  $this->session->userdata('usertype'); ?>">
<input type="hidden" id="language_option" value="<?php echo  $lang; ?>">
<!-- login -->
<?php if (empty($this->session->userdata('id'))) { ?>
	<script src="<?php echo $base_url; ?>assets/js/login.js"></script>
<?php } ?>

<script src="<?php echo base_url(); ?>assets/js/feather.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/script.js"></script>

<!-- External js-->
<?php if ($this->uri->segment(1) == "user-chat") {
	if (settingValue('socket_showhide') == 1) {
		$port = settingValue('server_port');
		$ip = settingValue('server_ip');
	} else {
		$port = '8443';
		$ip = '107.1.1.1';
	}
	$user['id'] = $this->session->userdata('id');
	$user['name'] = $this->session->userdata('name');
	$user['usertype'] = $this->session->userdata('usertype');
?>

	<input type="hidden" id="socketHost" value="<?php echo $ip; ?>">
	<input type="hidden" id="socketPort" value="<?php echo  $port; ?>">
	<input type="hidden" id="WS" value="<?php print ($this->db->WS) ? $this->db->WS : 'WS'; ?>">
	<input type="hidden" id="chat_user" value='<?php print(json_encode($user)); ?>'>

	<input type="hidden" id="usertype" name="user_type" value="<?php echo $this->session->userdata('usertype'); ?>">
	<?php if (settingValue('chat_type') == 'websocket') { ?>
		<script src="<?php echo $base_url; ?>assets/js/websocket.js"></script>
		<script src="<?php echo $base_url; ?>assets/js/user_chats.js"></script>
	<?php } else { ?>
		<script src="<?php echo $base_url; ?>assets/js/chat.js"></script>
	<?php } ?>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-availability") { ?>
	<script src="<?php echo base_url(); ?>assets/js/provider_availability.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-bookings") { ?>
	<script src="<?php echo $base_url; ?>assets/js/provider_bookings.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-settings") { ?>
	<script src="<?php echo base_url(); ?>assets/js/provider_settings.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-subscription") { ?>
	<script src="<?php echo base_url(); ?>assets/js/provider_subscription.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-subscription" || $this->uri->segment(1) == "shop" || $this->uri->segment(1) == "my-shop-inactive") { ?>
	<script src="<?php echo base_url(); ?>assets/js/subscription.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "provider-wallet") { ?>
	<script src="<?php echo base_url(); ?>assets/js/provider_wallet.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "user-bookings") { ?>
	<script src="<?php echo $base_url; ?>assets/js/user_bookings.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "user-settings") { ?>
	<script src="<?php echo base_url(); ?>assets/js/user_settings.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "user-wallet" || $this->uri->segment(1) == "provider-subscription") { ?>
	<script src="<?php echo base_url(); ?>assets/js/user_wallet.js"></script>
	<script src="https://www.paypalobjects.com/api/checkout.js"></script>
	<!-- Load the client component. -->
	<script src="https://js.braintreegateway.com/web/3.60.0/js/client.min.js"></script>
	<!-- Load the PayPal Checkout component. -->
	<script src="https://js.braintreegateway.com/web/3.60.0/js/paypal-checkout.min.js"></script>

<?php } ?>
<?php if ($this->uri->segment(1) == "edit_service") { ?>
	<script src="<?php echo $base_url; ?>assets/js/edit_service.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "all-services" || $this->uri->segment(1) == "search") { ?>
	<script src="<?php echo base_url(); ?>assets/js/service_search.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "book-service") { ?>
	<script src="<?php echo base_url(); ?>assets/js/book_service.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "staff-settings" || $this->uri->segment(1) == "add-staff" || $this->uri->segment(1) == "edit-staff" || $this->uri->segment(1) == "staff-details") { ?>
	<script src="<?php echo base_url(); ?>assets/js/staff.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "shop" || $this->uri->segment(1) == "add-shop" || $this->uri->segment(1) == "edit-shop") { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop.js"></script>
<?php } ?>
<?php if ($module == 'shopfreelancer' && ($this->uri->segment(2) == "shop" || $this->uri->segment(2) == "add-shop" || $this->uri->segment(2) == "edit-shop")) { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "shop-preview") { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop_preview.js"></script>
<?php } ?>
<?php if ($module == 'shopfreelancer' && $this->uri->segment(2) == "shop-preview") { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop_preview.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "shop" || $this->uri->segment(1) == "my-shop-inactive") { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop_settings.js"></script>
<?php } ?>
<?php if ($module == 'shopfreelancer' && ($this->uri->segment(2) == "shop" || $this->uri->segment(2) == "my-shop-inactive")) { ?>
	<script src="<?php echo base_url(); ?>assets/js/shop_settings.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "branch" || $this->uri->segment(1) == "add-branch" || $this->uri->segment(1) == "edit-branch") { ?>
	<script src="<?php echo base_url(); ?>assets/js/branch.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "branch" || $this->uri->segment(1) == "my-branch-inactive") { ?>
	<script src="<?php echo base_url(); ?>assets/js/branch_settings.js"></script>
<?php } ?>

<?php if ($module == 'shop' || $module == 'branch' || $module == 'shopfreelancer' || $module == 'appointment') { ?>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/tagsinput.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "book-appointment") { ?>
	<script src="<?php echo base_url(); ?>assets/js/appointment.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "edit-appointment") { ?>
	<script src="<?php echo base_url(); ?>assets/js/edit_appointment.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "service-checkout") { ?>
	<script src="<?php echo base_url(); ?>assets/js/checkout.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == 'add-staff' || $this->uri->segment(1) == 'edit-staff') { ?>
	<script src="<?php echo base_url(); ?>assets/js/staff_googlemap.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "service-preview" || $this->uri->segment(1) == "shop-preview") { ?>
	<script src="<?php echo base_url(); ?>assets/js/services.js"></script>
<?php } ?>

<!---External js end-->



<?php
if ($login_type == 'email') {
?>


	<div class="modal account-modal fade" id="tab_login_modal2" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 border-0">
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="login_form_div2">
						<div class="account-content">
							<!-- <div class="alert alert-danger text-center" id="flash_error_message2"></div> -->
							<div class="account-box">
								<div class="login-right">
									<div class="login-header">
										<h3><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?> AS MANGER</h3>
										<p class="text-muted">Access to our <?php echo settingValue('website_name') ?></p>
									</div>
									<div class="form-group">
										<label>Email ID</label>
										<div class="row">
											<div class="col-12">
												<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="manager_login_csrf">
												<input class="form-control" type="email" name="manager_email" id="manager_email" placeholder="Enter your email">
												<span id="mailid_error2"></span>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label>Password</label>
										<div class="row">
											<div class="col-12">
												<input class="form-control " type="password" name="manager_pass" id="manager_pass" placeholder="Enter your password">
												<span id="pass_error"></span>
											</div>
										</div>
									</div>
									<span id="manager_error" class="mb-3"></span>
									<button class="login-btn" id="managerLoginBtn" type="submit"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($this->session->userdata('userType') == 'manager') { ?>
		<div class="modal account-modal fade" id="tab_change_mnager_pass" data-keyboard="false" data-backdrop="static">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header p-0 border-0">
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div id="managerPassCngForm">
							<div class="account-content">
								<!-- <div class="alert alert-danger text-center" id="flash_error_message2"></div> -->
								<div class="account-box">
									<div class="login-right">
										<div class="login-header">
											<h3>Reset Password</h3>
											<p class="text-muted"><?php echo $this->session->userdata('name'); ?></p>
										</div>
										<div class="form-group">
											<label>New Password</label>
											<div class="row">
												<div class="col-12">
													<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="manager_pass_cng_csrf">
													<input class="form-control" type="password" name="manager_cng_pass" id="manager_cng_pass" placeholder="Enter new password">
													<span id="change_pass1" class="text-danger"></span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Retype Password</label>
											<div class="row">
												<div class="col-12">
													<input class="form-control " type="password" name="manager_cng_pass" id="retype_cng_pass" placeholder="Retype the password">
													<span id="change_pass2" class="text-danger"></span>
												</div>
											</div>
										</div>
										<span id="manager_pass_cng_error" class="mb-2"></span>
										<button class="login-btn" id="managerPassCngBtn" type="submit">Reset</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<div class="modal account-modal fade" id="tab_login_modal" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 border-0">
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div id="login_form_div">
						<div class="account-content">
							<div class="alert alert-danger text-center" id="flash_error_message1"></div>
							<div class="account-box">
								<div class="login-right">
									<div class="login-header">
										<h3><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></h3>
										<p class="text-muted">Access to our <?php echo settingValue('website_name') ?></p>
									</div>
									<div class="form-group">
										<label>Email ID</label>
										<div class="row">
											<div class="col-12">
												<input type="hidden" name="login_mode" id="login_mode" value="1">
												<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="login_csrf">
												<input class="form-control login_email" type="text" name="login_email" id="login_email" placeholder="Enter EMail ID">
												<span id="mailid_error"></span>
											</div>
										</div>
									</div>
									<button class="login-btn" id="emaillogin_submit" type="submit"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></button>
									<div class="account-footer text-center">
										Login as a <a href="javascript:void(0);" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#tab_login_modal2" style="color: orangered;">Manager</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="step-2" data-step="2" id="otp_final_div">
						<div class="login-header">
							<h3><?php echo (!empty($user_language[$user_selected]['lg_Password'])) ? $user_language[$user_selected]['lg_Password'] : $default_language['en']['lg_Password']; ?></h3>
							<p class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_Verification_account'])) ? $user_language[$user_selected]['lg_Verification_account'] : $default_language['en']['lg_Verification_account']; ?></p>
						</div>
						<div class="form-group">
							<input type="hidden" name="" id="login_email_hide">
							<input type="hidden" name="" id="login_mode_hide">
							<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="fp_csrf">
						</div>
						<div class="form-group">

							<input type="password" class="form-control form-control-lg" autocomplete="off" placeholder="Enter Password Here.." name="login_password" id='login_password'>
							<span for='otp_number' id='otp_error_msg_login'></span>
						</div>
						<p class="user_forgot_pwd">Forgot Password ? <a href="#" id="user_forgot_pwd"> Click to Get Link</a></p>
						<span id="err_respwd"></span>
						<div>
							<button id='emailregistration_finals' type="button" class="login-btn">Enter</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php } else { ?>
	<div class="modal account-modal fade" id="tab_login_modal" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered modal-popupcus">
			<div class="modal-content">
				<div class="modal-header">
					<div class="popheader">
						<h3><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></h3>
						<h6>Access to our <?php echo settingValue('website_name') ?></h6>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
					</div>
				</div>
				<div class="modal-body pt-0 ps-0 pe-0 pb-0">
					<div id="login_form_div">
						<div class="account-content p-5 pb-4">
							<div class="alert alert-danger text-center" id="flash_error_message1"></div>
							<div class="account-box">
								<div class="login-right">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?></label>
										<div class="row">
											<div class="col-12">
												<input type="hidden" name="login_mode" id="login_mode" value="1">
												<input type="hidden" name="csrf_token_name" value="<?php echo $this->security->get_csrf_hash(); ?>" id="login_csrf">
												<input class="form-control login_mobile" type="text" name="login_mobile" id="login_mobile" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_enter_mob_no'])) ? $user_language[$user_selected]['lg_enter_mob_no'] : $default_language['en']['lg_enter_mob_no']; ?>" min="10" max="10">
												<span id="mobile_no_error1"></span>
											</div>
										</div>
									</div>

									<button class="login-btn" id="login_submit" type="submit"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></button>
									<div class="loginor">
										<label>Or</label>
									</div>
									<div class="d-flex justify-content-center">
										<a href="javascript:void(0);" onclick="open_freelancer_reg_form()" class="btn btn-continue me-2">
											Join as Provider
										</a>
										<a href="javascript:void(0);" onclick="open_user_reg_form()" class="btn btn-continue"><?php echo (!empty($user_language[$user_selected]['lg_Join_User'])) ? $user_language[$user_selected]['lg_Join_User'] : $default_language['en']['lg_Join_User']; ?>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="step-2 p-4" data-step="2" id="otp_final_div">
						<div class="login-header">
							<h3><?php echo (!empty($user_language[$user_selected]['lg_OTP'])) ? $user_language[$user_selected]['lg_OTP'] : $default_language['en']['lg_OTP']; ?></h3>
							<p class="text-muted">
							<p class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_Verification_account'])) ? $user_language[$user_selected]['lg_Verification_account'] : $default_language['en']['lg_Verification_account']; ?></p>
							</p>
						</div>
						<div class="form-group">
							<input type="hidden" name="" id="login_country_code_hide">
							<input type="hidden" name="" id="login_mobile_hide">
							<input type="hidden" name="" id="login_mode_hide">
						</div>
						<div class="form-group">
							<?php if (settingValue('default_otp') == 1) { ?>
								<div class="alert alert-danger text-center" role="alert">
									<?php echo (!empty($user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'])) ? $user_language[$user_selected]['lg_Default_OTP_Demo_Purpose'] : $default_language['en']['lg_Default_OTP_Demo_Purpose']; ?><br> <strong><?php echo (!empty($user_language[$user_selected]['lg_Default_OTP'])) ? $user_language[$user_selected]['lg_Default_OTP'] : $default_language['en']['lg_Default_OTP']; ?></strong>
								</div>
							<?php } else { ?>
								<div class="alert alert-success text-center" role="alert">
									<strong><?php echo (!empty($user_language[$user_selected]['lg_We_Have_OTP'])) ? $user_language[$user_selected]['lg_We_Have_OTP'] : $default_language['en']['lg_We_Have_OTP']; ?>.</strong>
									<strong><?php echo (!empty($user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'])) ? $user_language[$user_selected]['lg_Please_Check_Your_Registered_Mobile'] : $default_language['en']['lg_Please_Check_Your_Registered_Mobile']; ?> </strong>
								</div>
							<?php } ?>
							<input type="text" class="form-control form-control-lg no_only" autocomplete="off" maxlength="4" minlength="4" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Enter_OTP_Here'])) ? $user_language[$user_selected]['lg_Enter_OTP_Here'] : $default_language['en']['lg_Enter_OTP_Here']; ?>.." name="otp_numbers" id='login_otp'>
							<span for='otp_number' id='otp_error_msg_login'></span>
						</div>
						<p class="resend-otp"><?php echo (!empty($user_language[$user_selected]['lg_Didnt_receive_the_OTP'])) ? $user_language[$user_selected]['lg_Didnt_receive_the_OTP'] : $default_language['en']['lg_Didnt_receive_the_OTP']; ?> <a href="#" id="login_resend_otp"> <?php echo (!empty($user_language[$user_selected]['lg_Resend_OTP'])) ? $user_language[$user_selected]['lg_Resend_OTP'] : $default_language['en']['lg_Resend_OTP']; ?></a></p>
						<div>
							<button id='registration_finals' type="button" class="login-btn"><?php echo (!empty($user_language[$user_selected]['lg_Enter'])) ? $user_language[$user_selected]['lg_Enter'] : $default_language['en']['lg_Enter']; ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>

<script>
	$(document).ready(function() {
		// Your validation function
		function validateChangeMangerPassForm() {
			var newPassword = $('#manager_cng_pass').val();
			var retypePassword = $('#retype_cng_pass').val();
			$('#change_pass1').html('');
			$('#change_pass2').html('');

			// Add your validation logic here
			if (newPassword.length < 8) {
				$('#change_pass1').html('Password must be at least 8 characters long.');
				return false;
			}

			if (newPassword !== retypePassword) {
				$('#change_pass2').html('Passwords do not match.');
				return false;
			}

			return true;
		}

		// Your Ajax function
		function sendManagerPassChangeRequest() {
			// Serialize form data
			var pass = $('#retype_cng_pass').val();
			var csrf_token = $('#manager_pass_cng_csrf').val();
			alert(pass);

			// Perform Ajax request using jQuery.ajax
			$.ajax({
				type: 'POST',
				url: "<?php echo base_url('user/service/ManagerPassReset'); ?>",
				data: {
					pass: pass,
					csrf_token_name: csrf_token,
				},
				dataType: "json",
				success: function(response) {
					swal({
						title: response.status,
						text: response.message,
						icon: response.status,
						button: "okay",
						closeOnEsc: false,
						closeOnClickOutside: false,
					}).then(function() {
						window.location.reload();
					});
				},
				error: function(error) {
					console.error('Error:', error);
				}
			});
		}

		// Add onclick event to a button or element
		$('#managerPassCngBtn').click(function() {
			if (validateChangeMangerPassForm()) {
				// If validation passes, call the Ajax function
				sendManagerPassChangeRequest();
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#managerLoginBtn").on("click", function() {
			// Collect form data
			var email = $("#manager_email").val();
			var password = $("#manager_pass").val();
			var csrf_token = $('#manager_login_csrf').val();
			// Ajax request
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('user/service/ManagerLogin'); ?>",
				data: {
					login_email: email,
					login_pass: password,
					csrf_token_name: csrf_token,
				},
				dataType: "json",
				success: function(response) {
					if (response.status == "success") {
						$('#manager_error').html('<b class="text-success">' + response.message + '</b>');
						location.href = '<?php echo base_url('/manager-dashboard'); ?>';
					} else {
						$('#manager_error').html('<b class="text-danger">' + response.message + '</b>');
					}
				},
				error: function(err) {
					console.log("Error processing your request:", err);
				}
			});
		});
	});
</script>
<!-- Cancel Modal -->
<div id="cancelModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cancellation Reason</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="cancel_reason"></p>
			</div>
		</div>
	</div>
</div>
<!-- Cancel Modal -->
<span id="success_message"><?php echo $this->session->flashdata('success_message'); ?></span>
<span id="error_message"><?php echo $this->session->flashdata('error_message'); ?></span>
<?php
if (!empty($this->session->flashdata('success_message'))) { ?>
	<script src="<?php echo base_url(); ?>assets/js/success_toaster.js"></script>
<?php } ?>

<?php
if (!empty($this->session->flashdata('error_message'))) { ?>
	<script src="<?php echo base_url(); ?>assets/js/error_toaster.js"></script>
<?php }
$this->session->unset_userdata('error_message');
$this->session->unset_userdata('success_message');
?>
<script src="<?php echo base_url(); ?>assets/js/toaster_message.js"></script>
</body>

</html>