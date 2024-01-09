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
											<input class="form-control bookingdate" type="text" name="bookingdate" id="bookingdate" required />
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

									<button class="btn btn-primary submit-btn submit_service_book" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" data-id="<?php echo $service_id; ?>" data-provider="<?php echo $service_details['user_id'] ?>" data-amount="<?php echo $service_amount; ?>" type="submit" id="submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Confirm_Booking'])) ? $user_language[$user_selected]['lg_Confirm_Booking'] : $default_language['en']['lg_Confirm_Booking']; ?></button>

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
				var bookingdate = $('#bookingdate').val();
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