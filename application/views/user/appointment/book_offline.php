<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<?php
$services = $this->db->get('services')->result();

?>
<div class="breadcrumb-bar">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="row">
					<div class="col">
						<div class="breadcrumb-title">
							<h2>Offline Appointment</h2>
						</div>
					</div>
					<div class="col-auto float-end ms-auto breadcrumb-menu">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>

								<li class="breadcrumb-item"><a href="<?php echo base_url() . "all-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></a></li>

								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Book_Appointment'])) ? $user_language[$user_selected]['lg_Book_Appointment'] : $default_language['en']['lg_Book_Appointment']; ?></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="content">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-10">
				<div class="user-bookings">

					<ul class="nav nav-tabs menu-tabs">
						<li class="nav-item">
							<a class="nav-link active" id="bookings-tab" data-bs-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="true">
								<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?>
							</a>
						</li>
					</ul>

					<div class="tab-content">

						<div class="tab-pane active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
							<form method="post" enctype="multipart/form-data" autocomplete="off" id="offline_book_services" action="<?= base_url('user/appointment/offline_book_checkout'); ?>">

								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="row">

									<div class="col-lg-10">
										<div class="form-group">
											<label>User</label>
											<input class="form-control" type="email" name="user_email" id="user_email" placeholder="type email for finding or creating a user" required>
											<div id="find-res" class="mt-1"></div>
										</div>
									</div>


									<div class="col-lg-12">
										<div class="form-group">
											<label>Service<span class="text-danger">*</span></label><br>
											<div>
												<select id="offline_service_id" name="service_id" onchange="set_values_by_service(this.value)" class="form-control select addshopcls" required>
													<?php foreach ($services as $service) { ?>
														<option value="<?php echo $service->id; ?>"><?php echo $service->service_title; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?><span class="text-danger">*</span></label><br>
											<div>
												<select id="shop_id" name="shop_id" class="form-control select addshopcls" required>
													<?php foreach ($shpres as $sval) { ?>
														<option value="<?php echo $sval['id']; ?>"><?php echo $sval['shop_name']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>

								</div>


								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><span class="locationtitle"><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></span> <span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="service_location" id="service_location" required disabled>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?> (<?php echo currency_conversion($user_currency_code); ?>)</label>
											<input class="form-control" type="text" name="service_amount" id="service_amount" value="<?php echo $service_details['service_amount']; ?>" readonly="" required>
										</div>
									</div>

									<div class="col-lg-6">
										<div class="form-group">
											<label>Booking Amount (<?php echo currency_conversion($user_currency_code); ?>)</label>
											<input class="form-control" type="text" name="booking_amount" id="booking_amount" value="<?php echo $service_details['booking_amount']; ?>" readonly="">
										</div>
									</div>

									<div class="col-lg-6">
										<div class="form-group">
											<label><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?> <span class="text-danger">*</span></label>
											<input class="form-control bookingdate" type="text" name="bookingdate" id="offlineBookingdate" required />
										</div>
									</div>

									<div class="col-lg-6">
										<div class="form-group">
											<label><?php echo (!empty($user_language[$user_selected]['lg_Time_Slot'])) ? $user_language[$user_selected]['lg_Time_Slot'] : $default_language['en']['lg_Time_Slot']; ?> <span class="text-danger">*</span></label>
											<select class="form-control from_time checkTime select" multiple name="from_time" id="from_time" onchange="set_book_amount()" required>
											</select>

										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<div class="text-center">
												<div id="load_div"></div>
											</div>
											<label><?php echo (!empty($user_language[$user_selected]['lg_Notes'])) ? $user_language[$user_selected]['lg_Notes'] : $default_language['en']['lg_Notes']; ?></label>
											<textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
										</div>
									</div>

								</div>
								<input type="hidden" id="service_rate" value="">
								<input type="hidden" id="booking_rate" value="">


								<div class="submit-section">

									<button class="btn btn-primary submit-btn submit_service_book" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" type="submit" id="submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Confirm_Booking'])) ? $user_language[$user_selected]['lg_Confirm_Booking'] : $default_language['en']['lg_Confirm_Booking']; ?></button>

									<a class="btn btn-danger appoint-btncls" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_Cancel_Booking'])) ? $user_language[$user_selected]['lg_Cancel_Booking'] : $default_language['en']['lg_Cancel_Booking']; ?></a>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Create a user</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
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
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		// Delay function to limit AJAX calls while typing
		var delayTimer;

		$('#user_email').focusout(function() {
			clearTimeout(delayTimer);
			var email = $(this).val().trim();
			const baseUrl = '<?= base_url(); ?>';
			const csrfToken = $("#csrf_token").val();

			if (email !== '') {
				delayTimer = setTimeout(function() {
					// Perform AJAX call
					$.ajax({
						url: `${baseUrl}user/appointment/check_user_availability`,
						type: "POST",
						data: {
							email: email,
							csrf_token_name: csrfToken,
						},
						dataType: 'json',
						success: function(response) {
							if (response.status) {
								console.log(response.data);
								$('#find-res').html('<span class="text-success">A user selected -> Name: ' + response.data.name + ', ID: ' + response.data.id + '</span>');
							} else {
								$('#find-res').html('<span class="text-danger">User no found with this email.</span> <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Create Now</button>');
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX request failed: ' + error);
						}
					});
				}, 500); // Set your desired delay in milliseconds (e.g., 500ms)
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		$("#offline_book_services").validate({
			rules: {
				service_id: "required",
				shop_id: "required",
				service_location: "required",
				bookingdate: "required",
				from_time: "required"
				// Add more validation rules as needed for other fields
			},
			messages: {
				service_id: "Please select a service",
				shop_id: "Please select a shop",
				service_location: "Please enter a service location",
				bookingdate: "Please select a booking date",
				from_time: "Please select a time slot"
				// Add more messages as needed for other fields
			},
			submitHandler: function(form) {
				const baseUrl = '<?= base_url(); ?>';
				var service_id = $('#offline_service_id').val();
				var shop_id = $('#shop_id').val();
				var service_location = $('#service_location').val();
				var bookingdate = $('#offlineBookingdate').val();
				var booking_time = $('#from_time').val();
				var booking_amount = $('#booking_amount').val();
				var service_amount = $('#service_amount').val();
				const csrfToken = $("#csrf_token").val();
				const notes = $("#notes").val();
				var service_at = 2;
				// Perform AJAX submission
				$.ajax({
					type: "POST",
					url: $(form).attr('action'), // Form action URL
					data: {
						service_id: service_id,
						shop_id: shop_id,
						service_location: service_location,
						bookingdate: bookingdate,
						booking_time: booking_time,
						final_amount: booking_amount,
						total_amt: service_amount,
						notes: notes,
						service_at: service_at,
						csrf_token_name: csrfToken,

					},
					dataType: "JSON",
					beforeSend: function() {
						$('#load_div').html('<i class="fa fa-spinner fa-spin"></i> Processing Order...');
					},
					success: function(response) {
						console.log("Form submitted successfully: " + response);
						swal({
							title: response.title,
							text: response.msg,
							icon: "success",
							button: "okay",
							closeOnEsc: false,
							closeOnClickOutside: false,
						}).then(function() {
							window.location.href = baseUrl + "provider-bookings";
						});
					},
					error: function(xhr, status, error) {
						// Handle errors if the submission fails
						console.error("Form submission failed: " + error);
						// Display an error message or perform any necessary actions
					}
				});
				return false; // Prevent default form submission
			}
		});
	});
</script>



<script>
	$("#from_time").select2({
		width: '100%',
		height: 'auto',
	});

	function set_book_amount(val) {
		var selectElement = document.getElementById('from_time');
		var selectedOptions = Array.from(selectElement.selectedOptions);
		var numberOfSelectedSlots = selectedOptions.length;

		var service_amount = $('#service_rate').val();
		var booking_amount = $('#booking_rate').val();

		var newServiceAmount = numberOfSelectedSlots * service_amount;
		var newBookingAmount = numberOfSelectedSlots * booking_amount;
		document.getElementById('service_amount').value = newServiceAmount;
		document.getElementById('booking_amount').value = newBookingAmount;
	}

	function set_values_by_service(service_id) {
		const baseUrl = '<?= base_url(); ?>';
		const csrfToken = $("#csrf_token").val();
		const requestData = {
			service_id: service_id,
			csrf_token_name: csrfToken,
		};

		$.ajax({
			url: `${baseUrl}user/appointment/get_service_details_by_id`,
			data: requestData,
			type: "POST",
			dataType: "json",
			success: function(response) {
				var shopId = response.shop_id;
				var shopName = response.shop_name;
				var newOption = $('<option></option>').attr('value', shopId).text(shopName);
				$('#shop_id').html(newOption);
				$('#service_location').val(response.service_location);
				$('#service_rate').val(response.service_amount);
				$('#booking_rate').val(response.booking_amount);
				$('#service_amount').val(response.service_amount);
				$('#booking_amount').val(response.booking_amount);
			},
			error: function(xhr, status, error) {
				console.log(xhr, status, error);
			}
		});
	}
</script>