<?php

$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);


if (empty($bookings['id']) || $bookings['status'] != 8) {
	redirect(base_url());
}
$this->session->unset_userdata('amount');
$user_currency_codeval = $bookings['currency_code'];
$total_amt = $bookings['final_amount']; // Subtotal
$booking_amount = $bookings['booking_amnt'];
$total_amt = get_gigs_currency($total_amt, $user_currency_codeval, $user_currency_code); //Subtotal amount conversion


// $user_currency_code = settingValue('currency_option');

$service_id = $bookings['service_id'];
$records = $this->appointment->get_service($service_id);
$payment_desc = "Book Service - " . $records['service_title'];

$convertedTime = strtotime(date('Y-m-d H:i:s', strtotime('+15 minutes')));
if ($bookings['home_service'] == 1) {
	$homeqry = $this->db->select('homeservice_fee, homeservice_arrival')->where('id', $bookings['provider_id'])->get('providers')->row_array();
	$homefee = $homeqry['homeservice_fee'];
	$mins = (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins'];
	$arrivaltime = $homeqry['homeservice_arrival'] . ' ' . $mins;
} else {
	$homefee = 0;
	$arrivaltime = '';
}
$homefee = get_gigs_currency($homefee, $user_currency_codeval, $user_currency_code); //homefee conversion
if ($this->session->userdata('coupon_amount')) {
	$total_payable = $this->session->userdata('coupon_amount') + $homefee + 1;
} else {
	$total_payable = $this->session->userdata('amount') + $homefee;
}
//  else {
// 	$total_payable = $total_amt + $homefee; // Total Payable
// }

$card_total_amount = ($total_payable) * 100;

$bservices = $this->db->select('id, service_id, currency_code, amount,service_for, guest, guest_parent_bookid, guest_name, service_date, from_time, to_time, shop_id, total_amount, final_amount, additional_services, offersid, rewardid, guest_parent_bookid')->where('id = ' . $bookings['id'] . ' or guest_parent_bookid = ' . $bookings['id'])->get('book_service')->result_array();

$boktxt = (!empty($this->user_language[$this->user_selected]['lg_Booking'])) ? $this->user_language[$this->user_selected]['lg_Booking'] : $this->default_language['en']['lg_Booking'];
$exptxt = (!empty($this->user_language[$this->user_selected]['lg_Session_Expired'])) ? $this->user_language[$this->user_selected]['lg_Session_Expired'] : $this->default_language['en']['lg_Session_Expired'];
$data = '';
$get_coupon_code = $this->db->where('status', 1)
	->where('service_id', $service_id)
	->where("used_user_id NOT IN (" . $this->session->userdata('id') . ")", NULL, false)
	->where('user_limit != user_limit_count')
	->where('start_date <=', date('Y-m-d'))
	->where('end_date >=', date('Y-m-d'))
	->get('service_coupons')->row();
?>
<div class="breadcrumb-bar">
	<div class="container">
		<div class="row">
			<div class="col">
				<div class="breadcrumb-title">
					<h2><?php echo (!empty($user_language[$user_selected]['lg_Checkout'])) ? $user_language[$user_selected]['lg_Checkout'] : $default_language['en']['lg_Checkout']; ?></h2>
				</div>
			</div>
			<div class="col-auto float-right ml-auto breadcrumb-menu">
				<nav aria-label="breadcrumb" class="page-breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>

						<li class="breadcrumb-item"><a href="<?php echo base_url() . "user-bookings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Booking'])) ? $user_language[$user_selected]['lg_Booking'] : $default_language['en']['lg_Booking']; ?></a></li>

						<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Checkout'])) ? $user_language[$user_selected]['lg_Checkout'] : $default_language['en']['lg_Checkout']; ?></li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h3 class="mb-3">Payment Option</h3>
				<h6>Select Payment Type</h6>
				<div class="mt-3">
					<?php if (!empty($paypal_option_status)) { ?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment_type" id="paypal" value="paypal">
							<label class="form-check-label" for="paypal">Paypal</label>
						</div>
					<?php }
					if (!empty($stripe_option_status)) { ?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment_type" id="stripe" value="stripe">
							<label class="form-check-label" for="stripe">Stripe</label>
						</div>
					<?php }
					if (!empty($razor_option_status)) { ?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment_type" id="razorpay" value="razorpay">
							<label class="form-check-label" for="razorpay">Razorpay</label>
						</div>
					<?php }
					if (!empty($cod_option_status)) { ?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment_type" id="cod" value="cod">
							<label class="form-check-label" for="cod">COD</label>
						</div>
					<?php }  ?>

					<?php if (!empty($moyasar_option_status)) { ?>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="payment_type" id="moyasarpay" value="moyasarpay">
							<label class="form-check-label" for="moyasarpay"><?php echo (!empty($user_language[$user_selected]['lg_Moyasar'])) ? $user_language[$user_selected]['lg_Moyasar'] : $default_language['en']['lg_Moyasar']; ?></label>

						</div>
					<?php } ?>
					<div class="mt-3" style="display:none;">
						<button type="submit" class="form-control" id="cod_payment" onclick="cod_payment('<?php echo $bookings['id']; ?>', '<?php echo $bookings['amount']; ?>')">Proceed to Payment</button>
					</div>
				</div>
				<div class="user-payments" id="payments-tab">

					<div class="row" style="display:none;">
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="col-lg-12">
							<div class="form-group">
								<h5><?php echo (!empty($user_language[$user_selected]['lg_Payment_Method'])) ? $user_language[$user_selected]['lg_Payment_Method'] : $default_language['en']['lg_Payment_Method']; ?></h5>
								<div>
									<?php /* if(!empty($moyasar_option_status)) { ?>
								<label class="radio-inline"><input class="cod"  type="radio" name="cod" value="2" checked> <?php echo (!empty($user_language[$user_selected]['lg_Payment_Txt'])) ? $user_language[$user_selected]['lg_Payment_Txt'] : $default_language['en']['lg_Payment_Txt']; ?></label>
								<?php } */ ?>
									<?php
									if ($cod_option_status == 1) {
									?>
										<label class="radio-inline"><input class="cod" type="radio" name="cod" value="1"> <?php echo (!empty($user_language[$user_selected]['lg_Cash_Text'])) ? $user_language[$user_selected]['lg_Cash_Text'] : $default_language['en']['lg_Cash_Text']; ?> </label>
									<?php
									}
									?>
								</div>
							</div>
						</div>
						<div class="book-mysr-form"></div>
						<input type="hidden" id="bcallbackurl" value="<?php echo base_url() . "user/appointment/book_moyaser_payment/" . $bookings['id']; ?>" />
						<input type="hidden" id="moyasar_apikey" value="<?php echo $moyaser_apikey; ?>" />
						<input type="hidden" id="bamountval" value="<?php echo $card_total_amount; ?>" />
						<input type="hidden" id="bcurrencyval" value="<?php echo $user_currency_code; ?>" />
						<input type="hidden" id="bdescription" value="<?php echo $payment_desc;  ?>" />

					</div>

					<div class="col-lg-12">

						<button class="btn btn-primary pay-submit-btn submit_servicebook appoint-btncls d-none" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Payment" type="submit" id="submit_button_id"><?php echo (!empty($user_language[$user_selected]['lg_Confirm_Booking'])) ? $user_language[$user_selected]['lg_Confirm_Booking'] : $default_language['en']['lg_Confirm_Booking']; ?></button>

						<a class="btn btn-danger cancelappt appoint-btncls d-none" href="javascript:void(0)"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></a>
					</div>
				</div>



			</div>
			<div class="col-md-4">
				<h3><?php echo (!empty($user_language[$user_selected]['lg_Payment_Summary'])) ? $user_language[$user_selected]['lg_Payment_Summary'] : $default_language['en']['lg_Payment_Summary']; ?></h3>
				<?php if ($bookings['home_service'] == 1) { ?>
					<small>*** <?php echo (!empty($user_language[$user_selected]['lg_Time_Taken_Txt'])) ? $user_language[$user_selected]['lg_Time_Taken_Txt'] : $default_language['en']['lg_Time_Taken_Txt']; ?> - <?php echo $arrivaltime; ?></small>
				<?php } ?>
				<ul class="list-group mt-3 mb-4">

					<?php

					foreach ($bservices as $b) {
						$sname = $this->db->select('service_title')->where('id', $b['service_id'])->get('services')->row()->service_title;
						$shname = $this->db->select('shop_name')->where('id', $b['shop_id'])->get('shops')->row()->shop_name;

						if ($b['additional_services'] != '') {
							if ($b['total_amount'] == $b['final_amount'] && ($b['offersid'] == 0 && $b['rewardid'] == 0)) {
								$sval = 0;
								$amtval = $b['final_amount'];
							} else if ($b['total_amount'] != $b['final_amount'] && ($b['offersid'] == 0 && $b['rewardid'] == 0)) {
								$sval = 0;
								$amtval = $b['total_amount'];
							} else if ($b['total_amount'] == $b['final_amount'] && ($b['offersid'] > 0 || $b['rewardid'] > 0)) {
								$sval = 1;
								$amtval = $b['total_amount'];
							} else {
								$sval = 1;
								$amtval = $b['total_amount'];
							}
						} else {
							if ($b['total_amount'] == $b['amount']) {
								$sval = 0;
								$amtval = $b['total_amount'];
							} else {
								$sval = 1;
								$amtval = $b['total_amount'];
							}
						}
						$amtval = get_gigs_currency($amtval, $b['currency_code'], $user_currency_code);
						$sftit = '';
						$sfor = '';
						if ($b['service_for'] != 0) {
							$sftit = (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For'];
							if ($b['service_for'] == 1) {
								$sfor = (!empty($user_language[$user_selected]['lg_Myself'])) ? $user_language[$user_selected]['lg_Myself'] : $default_language['en']['lg_Myself'];
							} else {
								$sfor = (!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests'];
							}
						}
						$aiarr = explode(",", $b['additional_services']);
						$addiser = $this->db->select('service_name,amount')->where_in('id', $aiarr)->get('additional_services')->result_array();
						$aimat = 0;
						$litxtt = '';
						if (count($addiser) > 0) {
							$aitxtt = (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services'];
							foreach ($addiser as $ai) {
								$aimat1 += get_gigs_currency($ai['amount'], $b['currency_code'], $user_currency_code);
								$aimat += $ai['amount'];
								$litxtt .= '<h6 class="my-0 mt-2">' . $ai['service_name'] . '</h6>
								<small class="text-muted">' . $aitxtt . '</small>';
							}
						}
					?>
						<li class="list-group-item d-flex justify-content-between lh-condensed" title="<?php echo ($b['service_for'] != 0) ? $sftit . '-' . $sfor : ''; ?>">
							<div>
								<h6 class="my-0"><?php echo $sname; ?> </h6>
								<?php

								if ($b['service_for'] == 0) { ?>
									<small class="text-muted"><?php echo ($shname) ? $shname : ''; ?></small>
									<?php echo $litxtt; ?>
								<?php } else { ?>
									<small class="text-muted"><?php echo '(' . $sfor . ')'; ?></small>
								<?php } ?>
							</div>
							<span class="text-muted"><?php echo currency_conversion($user_currency_code) . $booking_amount; ?>
							</span>
						</li>

					<?php } ?>

					<?php if ($bookings['home_service'] == 1) { ?>
						<li class="list-group-item d-flex justify-content-between lh-condensed">
							<div class="text-danger">
								<h6 class="my-0"><?php echo (!empty($user_language[$user_selected]['lg_Home_Service_Fee'])) ? $user_language[$user_selected]['lg_Home_Service_Fee'] : $default_language['en']['lg_Home_Service_Fee']; ?></h6>

							</div>
							<span class="text-danger">+<?php echo currency_conversion($user_currency_code) . $homefee; ?></span>
						</li>
					<?php } ?>

					<li class="list-group-item d-flex justify-content-between">
						<span><?php echo (!empty($user_language[$user_selected]['lg_Subtotal'])) ? $user_language[$user_selected]['lg_Subtotal'] : $default_language['en']['lg_Subtotal']; ?> </span>
						<strong><span><?php echo currency_conversion($user_currency_code); ?></span><span><?php echo $booking_amount; ?></span></strong>
					</li>

					<li class="list-group-item d-flex justify-content-between bg-light" id="promocode" style="display: none !important;">

					</li>
					<li class="list-group-item d-flex justify-content-between">
						<span><?php


								echo (!empty($user_language[$user_selected]['lg_Total_Payable'])) ? $user_language[$user_selected]['lg_Total_Payable'] : $default_language['en']['lg_Total_Payable']; ?> </span>
						<strong><span><?php echo currency_conversion($user_currency_code); ?></span>
							<span id="total_pay"><?php echo $booking_amount; ?></span></strong>
					</li>


				</ul>
				<?php if (!empty($get_coupon_code)) { ?>
					<div class="book-price-wrapper">
						<div class="card">
							<div class="card-body">
								<div class="coupon-code">Coupon Code : <?php echo $get_coupon_code->coupon_name; ?></div>
								<div class="payment-option payment-additional-code">
									<div class="payment-option-title field choice">
										<h5 id="block-code-heading">
											<span><?php echo (!empty($user_language[$user_selected]['lg_Apply_Code'])) ? $user_language[$user_selected]['lg_Apply_Code'] : $default_language['en']['lg_Apply_Code']; ?></span>
										</h5>
									</div>
									<div class="payment-option-content">
										<div class="form form-discount" id="discount-form">
											<div class="payment-option-inner">
												<div class="input-group">
													<input type="text" class="form-control" name="codeval" id="codeval" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Enter_Code'])) ? $user_language[$user_selected]['lg_Enter_Code'] : $default_language['en']['lg_Enter_Code']; ?>...">
													<div class="input-group-append">
														<a href="javascript:void(0)" class="btn btn-primary applycode"><i class="fa fa-arrow-right"></i></a>
													</div>
												</div>
											</div>
											<div class="errmessages"></div>
										</div>
									</div>
								</div>

							</div>
						</div>

						<input type="hidden" id="estTime" value="<?php echo $convertedTime; ?>" />
						<input type="hidden" id="bookid" name="bookid" value="<?php echo $bookings['id']; ?>" />
						<input type="hidden" id="totalamt" name="totalamt" value="<?php echo $total_payable; ?>" />
						<input type="hidden" id="couponid" name="couponid" value="0" />
						<input type="hidden" id="service_id" name="service_id" value="<?php echo $service_id; ?>" />

						<input type="hidden" id="booktxt" value="<?php echo $boktxt; ?>" />
						<input type="hidden" id="expiretxt" value="<?php echo $exptxt; ?>" />
						<input type="hidden" id="coupon_used" value="" />


					</div>
				<?php } ?>


				<input type='hidden' name='cod_booking_id' id="cod_booking_id" value="<?php echo $bookings['id']; ?>">
				<input type='hidden' name='cod_booking_id' id="cod_booking_id" value="<?php echo $bookings['id']; ?>">
				<?php $user_details = $this->db->get_where('users', array('id' => $this->session->userdata('id')))->row_array();

				$form_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; ?>
				<form name="frm_paypal_detail" id="frm_paypal_detail" target="_blank" action="<?php echo $form_url ?>" method="POST">

					<input type='hidden' name='business' value="<?php echo $this->session->userdata('email'); ?>">
					<input type='hidden' name='item_number' value="123456">
					<input type='hidden' name='amount' id="coupon_amount" value='<?php echo $total_payable; ?>'>
					<input type='hidden' name='currency_code' value='USD'>
					<input type='hidden' name='return' value="<?php echo base_url() ?>user/appointment/paypal_payment/<?php echo $bookings['id'] . '/' . $total_payable; ?>">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" id="paypal_gateway" value="<?php echo $paypal_gateway; ?>">
					<input type="hidden" id="braintree_key" value="<?php echo $braintree_key; ?>">

					<input type="hidden" id="razorpay_apikey" value="<?php echo $razorpay_apikey; ?>">

					<input type="hidden" id="username" value="<?php echo $user_details['name']; ?>">
					<input type="hidden" id="mobileno1" value="<?php echo $user_details['mobileno']; ?>">


					<input type="hidden" id="state" value="<?php echo (!empty($state)) ? $state : "IL"; ?>">
					<input type="hidden" id="country" value="<?php echo (!empty($country)) ? $country : "US"; ?>">
					<input type="hidden" id="pincode" value="<?php echo (!empty($user_details['pincode'])) ? $user_details['pincode'] : "60652"; ?>">
					<input type="hidden" id="address" value="<?php echo (!empty($user_details['address'])) ? $user_details['address'] : "1234 Main St."; ?>"><input type="hidden" id="city" value="<?php echo (!empty($city)) ? $city : "Chicago"; ?>">
				</form>

				<!-- Stripe Details -->
				<input type="hidden" id="stripe_key" value="<?php echo $stripe_key; ?>">
				<input type="hidden" id="logo_front" value="<?php echo $web_log; ?>">
				<input type="hidden" id="booking_amount" value="<?php echo $total_payable; ?>">
				<input type="hidden" id="booking_currency" value="<?php echo $user_currency_codeval; ?>">
				<input type="hidden" id="booking_id" value="<?php echo $bookings['id']; ?>">
				<button id="my_stripe_payyment" style="display: none;">Purchase</button>
				<!-- Stripe Details -->

			</div>
		</div>
	</div>
</div>