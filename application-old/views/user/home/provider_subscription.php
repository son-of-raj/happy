<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>Subscription</h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Subscription</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
$subscription = $this->home->get_subscription();
$my_subscribe = $this->home->get_my_subscription();
$my_subscribe_list = $this->home->get_my_subscription_list();
if (!empty($my_subscribe)) {
    $subscription_name = $this->db->where('id', $my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
} else {
    $subscription_name['subscription_name'] = '';
}
$user_type = $this->session->userdata('usertype'); 
?>
<style>.swal-text{line-height:unset}</style>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu'); ?>
            <div class="col-xl-9 col-md-8">
                <?php
                if (!empty($my_subscribe['expiry_date_time'])) {
                    if (date('Y-m-d', strtotime($my_subscribe['expiry_date_time'])) < date('Y-m-d')) {
                        ?>

                        <div class="alert alert-warning">
                            <div class="pricing-alert flex-wrap flex-md-nowrap">
                                <div class="alert-desc">
                                    <p class="mb-0">Your subscription has expired on <?php echo date('d-m-Y', strtotime($my_subscribe['expiry_date_time'])); ?> So Please Select Any Plan.</p>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>

                <?php
                if (!empty($my_subscribe['expiry_date_time'])) {
                    $before_days = date('Y-m-d', strtotime('-10 days', strtotime($my_subscribe['expiry_date_time'])));
                    $start = strtotime(date('Y-m-d'));
                    $end = strtotime($my_subscribe['expiry_date_time']);
                    $days = ceil(abs($end - $start) / 86400);

                    if (date('Y-m-d') >= $before_days && date('Y-m-d') <= $my_subscribe['expiry_date_time']) {
                        ?>
                        <div class="alert alert-info">
                            <?php if (!empty($days)) { ?> 
                                Your subscription expires in <?php echo  $days; ?> Days.
                            <?php } else { ?>
                                Your subscription expires Today.
                        <?php } ?>
                        </div>
                    <?php }
                }
                ?>

                <div class="row pricing-box">
                    <?php foreach ($subscription as $list) { ?>
                        <?php
                        if (!empty($my_subscribe['subscription_id'])) {
                            if ($list['id'] == $my_subscribe['subscription_id']) {
                                if (date('Y-m-d', strtotime($my_subscribe['expiry_date_time'])) >= date('Y-m-d')) {
                                    $class = "pricing-selected";
                                }
                            } else {
                                $class = '';
                            }
                        } else {
                            $class = '';
                        }
                        if (!isset($class)) {
                            $class = '';
                        }
                        
                            
                            $user_currency = get_provider_currency();
                            $user_currency_code = $user_currency['user_currency_code'];
                            $service_amount = get_gigs_currency($list['fee'], $list['currency_code'], $user_currency_code);
                        
                        ?>
                        <div class="col-xl-4 col-md-6 <?php echo $class; ?>">
                            <div class="card pricing-boxs">
                                <div class="card-body">
                                    <div class="pricing-header">
                                        <h2><?php echo $list['subscription_name'] ?></h2>
										<?php if($list['fee'] == 0) { ?>
											<p>Free</p>
										<?php } else { ?>
									 		<p>Price in Days</p>
										<?php } ?>
                                    </div>              
                                    <div class="pricing-card-price">
                                        <h3 class="heading2 price"><?php echo currency_conversion($user_currency_code).$service_amount; ?></h3>
                                        <p>Duration: <span><?php echo $list['duration'] ?> Days</span></p>
                                    </div>
                                    <ul class="pricing-options">
                                        
										<li><i class="fa fa-check"></i> <?php echo  $list['duration']; ?> <?php echo (!empty($user_language[$user_selected]['lg_Days_Expiration'])) ? $user_language[$user_selected]['lg_Days_Expiration'] : $default_language['en']['lg_Days_Expiration']; ?></li>
										<?php if($list['subscription_content'] != '') { 
										$sublists = explode(",", $list['subscription_content']); 
										if(count($sublists) > 0) {
											foreach($sublists as $val){ ?>
												<li><i class="fa fa-check"></i> <?php echo $val; ?> </li>
									<?php } } } ?>
                                    </ul>
									
									<?php
									if ($list['id'] != $my_subscribe['subscription_id'] && $list['fee']>0) {
									?>
									 <div class="row mt-2">
										<?php  if(!empty($paypal_option_status)) { ?>
										<div class="col-6">
											<input class="form-check-input" type="radio" name="payment_type" id="paypal" value="paypal">
											<img src="<?php echo  base_url() . "assets/img/paypal.png"; ?>">
										</div>
										<?php } if(!empty($stripe_option_status)) { ?>
										<div class="col-6">
											<input class="form-check-input" type="radio" name="payment_type" id="stripe"  value="stripe">
											<img src="<?php echo  base_url() . "assets/img/stripe.png"; ?>">
										</div>
										<?php } if(!empty($razor_option_status)) { ?>
										<div class="col-6">
											<input class="form-check-input" type="radio" name="payment_type" id="razorpay"  value="razorpay">
											<img src="<?php echo  base_url() . "assets/img/razorpay.png"; ?>">
										</div>
										<?php }  ?>
										
										<?php if(!empty($moyasar_option_status)) { ?>
										<div class="col-6 mb-4">
											<input class="form-check-input" type="radio" name="payment_type" id="moyasarpay"  value="moyasarpay"><label><?php echo (!empty($user_language[$user_selected]['lg_Moyasar'])) ? $user_language[$user_selected]['lg_Moyasar'] : $default_language['en']['lg_Moyasar']; ?></label>
										</div>
										<?php } ?>
                                         <?php  
                                        $query = $this->db->query("SELECT * FROM offline_payment");
                                        $offline_payment = $query->row_array();
                                        if ($offline_payment['status'] == 1) {
                                        ?>
                                            <div class="col-6 mb-4">
                                                <input class="form-check-input" type="radio" name="payment_type" id="offline_payment"  value="offline_payment">
                                                BankTransfer
                                            </div>
                                        <?php } ?>
										
									</div>
									<?php
									if($paypal_gateway=='sandbox')
									{
										$form_url='https://www.sandbox.paypal.com/cgi-bin/webscr';
									}
									else
									{
										$form_url='https://www.sandbox.paypal.com/cgi-bin/webscr';
									}
									?>
									
									
								<input type="hidden" id="razorpay_apikey" value="<?php echo  $razorpay_apikey; ?>">

                              
                                <form name="frm_paypal_detail" id="frm_paypal_detail_<?php echo $list['id']?>" target="_blank" action="<?php echo $form_url?>" method="POST">
								
								<input type='hidden' name='business' value="<?php echo  $user_details['email']; ?>">
								<input type='hidden' name='item_name' value='<?php echo $list['subscription_name'] ?>'> 
								<input type='hidden' name='item_number' value="123456"> 
								<input type='hidden' name='amount' value='<?php echo $service_amount?>'> 
							<input type='hidden' name='currency_code' value='USD'>
							<input type='hidden' name='return' value="<?php echo base_url() ?>user/subscription/paypal_payment/<?php echo $list["id"]?>">
							<input type="hidden" name="cmd" value="_xclick">  
							<input type="hidden" name="order" value="<?php echo $list['subscription_name'] ?>">
									<input type="hidden" id="paypal_gateway" value="<?php echo  $paypal_gateway; ?>">
									<input type="hidden" id="braintree_key" value="<?php echo  $braintree_key; ?>">
									
									<input type="hidden" id="razorpay_apikey" value="<?php echo  $razorpay_apikey; ?>">

									<input type="hidden" id="username" value="<?php echo  $user_details['name']; ?>">
									<input type="hidden" id="mobileno1" value="<?php echo  $user_details['mobileno']; ?>">


									<input type="hidden" id="state" value="<?php echo  (!empty($state)) ? $state : "IL"; ?>">
									<input type="hidden" id="country" value="<?php echo  (!empty($country)) ? $country : "US"; ?>">
									<input type="hidden" id="pincode" value="<?php echo  (!empty($user_details['pincode'])) ? $user_details['pincode'] : "60652"; ?>">
									<input type="hidden" id="address" value="<?php echo  (!empty($user_details['address'])) ? $user_details['address'] : "1234 Main St."; ?>"><input type="hidden" id="city" value="<?php echo  (!empty($city)) ? $city : "Chicago"; ?>">
								</form>
                                <span class="paypal_desc">Kindly click the Paypal button to pay</span>
                                <a id="pays">
                                    <div id="paypal-button"></div>
                                </a>
                                    <?php } if (empty($subscription_name['subscription_name'])) { ?>
                                        <a href="javascript:void(0);" class="btn btn-primary btn-block callStripe 0" data-id="<?php echo $list['id']; ?>" data-curcon="<?php echo $service_amount; ?>" data-amount="<?php echo $list['fee']; ?>" data-currency="<?php echo $list['currency_code']; ?>" ><?php echo (!empty($user_language[$user_selected]['lg_Select_Plan'])) ? $user_language[$user_selected]['lg_Select_Plan'] : $default_language['en']['lg_Select_Plan']; ?></a>
                                        <?php
                                    }
                                    if (!empty($my_subscribe['subscription_id'])) {

                                        if ($list['id'] == $my_subscribe['subscription_id'] && date('Y-m-d', strtotime($my_subscribe['expiry_date_time'])) > date('Y-m-d') && $my_subscribe['paid_status'] != 0) {
                                            ?>
                                            <a href="javascript:void(0);" class="btn btn-primary btn-block">Subscribed</a>
                                            <?php
                                        }  elseif ($list['id'] == $my_subscribe['subscription_id'] && date('Y-m-d', strtotime($my_subscribe['expiry_date_time'])) > date('Y-m-d') && $my_subscribe['paid_status'] == '0') { ?>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-block">pending</a>
                                       <?php } else {
                                            $subscription_fee = $this->db->where('id', $my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
                                            if (!empty($subscription_fee)) {
                                                if ((int) $list['fee'] > (int) $subscription_fee['fee']) {
                                                    ?>

                                                    <a href="javascript:void(0);" class="btn btn-primary btn-block callStripe" data-id="<?php echo $list['id']; ?>" data-currency="<?php echo $user_currency_code; ?>" data-curcon="<?php echo $service_amount; ?>" data-amount="<?php echo $service_amount; ?>" ><?php echo (!empty($user_language[$user_selected]['lg_Select_Plan'])) ? $user_language[$user_selected]['lg_Select_Plan'] : $default_language['en']['lg_Select_Plan']; ?></a>

                                                    <?php
                                                } else {
                                                    if (date($my_subscribe['expiry_date_time']) >= date('Y-m-d')) {
                                                        ?>
                                                        <a data-toggle="tooltip" title="Your Not Choose This Plan ..!" href="javascript:void(0);"  class="btn btn-primary btn-block plan_notification" ><?php echo (!empty($user_language[$user_selected]['lg_Select_Plan'])) ? $user_language[$user_selected]['lg_Select_Plan'] : $default_language['en']['lg_Select_Plan']; ?></a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0);" class="btn btn-primary btn-block callStripe" data-id="<?php echo $list['id']; ?>" data-currency="<?php echo $user_currency_code; ?>" data-curcon="<?php echo $service_amount; ?>" data-amount="<?php echo $service_amount; ?>" ><?php echo (!empty($user_language[$user_selected]['lg_Select_Plan'])) ? $user_language[$user_selected]['lg_Select_Plan'] : $default_language['en']['lg_Select_Plan']; ?></a>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                <?php } ?>
                </div>
<?php if (!empty($my_subscribe)) { 
$user_currency = get_provider_currency();
$user_currency_code = $user_currency['user_currency_code'];
$subscription_amount = get_gigs_currency($subscription_name['fee'], $subscription_name['currency_code'], $user_currency_code);

?>
                    <div class="card">
                        <div class="card-body">
                            <div class="plan-det">
                                <h6 class="title"><?php echo (!empty($user_language[$user_selected]['lg_plan_details'])) ? $user_language[$user_selected]['lg_plan_details'] : $default_language['en']['lg_plan_details']; ?></h6>
                                <ul class="row">
                                    <li class="col-sm-4">
                                         <?php if(!empty($my_subscribe['subscription_date'])){
                                                         $date=date(settingValue('date_format'), strtotime($my_subscribe['subscription_date']));
                                                     }else{
                                                            $date='-';                                
                                                     }
                                                    ?>
                                        <p><span class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_started_on'])) ? $user_language[$user_selected]['lg_started_on'] : $default_language['en']['lg_started_on']; ?></span> <?php echo  $date; ?></p>
                                    </li>
                                    <li class="col-sm-4">
                                        <p><span class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_price'])) ? $user_language[$user_selected]['lg_price'] : $default_language['en']['lg_price']; ?></span> <?php
    if (!empty($subscription_name['fee'])) {
        echo currency_conversion($user_currency_code).$subscription_amount;
    }
    ?></p>
                                    </li>
                                    <li class="col-sm-4">

                                         <?php if(!empty($my_subscribe['expiry_date_time'])){
                                                         $dates=date(settingValue('date_format'), strtotime($my_subscribe['subscription_date']));
                                                     }else{
                                                            $dates='-';                                
                                                     }

                                                     if(!empty($my_subscribe['expiry_date_time'])){
                                                         $exp_dates=date(settingValue('date_format'), strtotime($my_subscribe['expiry_date_time']));
                                                     }else{
                                                            $exp_dates='-';                                
                                                     }
                                                     ?>

                                        <p><span class="text-muted"><?php echo (!empty($user_language[$user_selected]['lg_expired_on'])) ? $user_language[$user_selected]['lg_expired_on'] : $default_language['en']['lg_expired_on']; ?></span><?php echo  $exp_dates; ?></p>
                                    </li>
                                </ul>
                                <h6 class="title"><?php echo (!empty($user_language[$user_selected]['lg_last_payment'])) ? $user_language[$user_selected]['lg_last_payment'] : $default_language['en']['lg_last_payment']; ?></h6>
                                <ul class="row">
                                    <li class="col-sm-4">
                                        <p><?php echo (!empty($user_language[$user_selected]['lg_paid_at'])) ? $user_language[$user_selected]['lg_paid_at'] : $default_language['en']['lg_paid_at']; ?> <?php echo  $dates; ?></p>
                                    </li>
                                    <li class="col-sm-4">
                                        <p><span class="amount"><?php
                                                if (!empty($subscription_name['fee'])) {
                                                     echo currency_conversion($user_currency_code).$subscription_amount;
                                                }
                                                ?> </span> <span class="badge bg-success-light"><?php echo (!empty($user_language[$user_selected]['lg_paid'])) ? $user_language[$user_selected]['lg_paid'] : $default_language['en']['lg_paid']; ?></span></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-4"><?php echo (!empty($user_language[$user_selected]['lg_subscribed_details'])) ? $user_language[$user_selected]['lg_subscribed_details'] : $default_language['en']['lg_subscribed_details']; ?></h5>		
                    <div class="card transaction-table mb-0">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-center mb-0 no-footer">
                                    <thead>
                                        <tr>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_plan'])) ? $user_language[$user_selected]['lg_plan'] : $default_language['en']['lg_plan']; ?></th>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_start_date'])) ? $user_language[$user_selected]['lg_start_date'] : $default_language['en']['lg_start_date']; ?></th>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_end_date'])) ? $user_language[$user_selected]['lg_end_date'] : $default_language['en']['lg_end_date']; ?></th>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></th>
                                            <th><?php echo (!empty($user_language[$user_selected]['lg_Status'])) ? $user_language[$user_selected]['lg_Status'] : $default_language['en']['lg_Status']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php foreach ($my_subscribe_list as $row) { ?>
                                         <?php
                                            $subscription_date = (settingValue('date_format'))?date(settingValue('date_format'), strtotime($row['subscription_date'])):date('d-m-Y', strtotime($row['subscription_date'])); 
                                            $expiry_date_time = (settingValue('date_format'))?date(settingValue('date_format'), strtotime($row['expiry_date_time'])):date('d-m-Y', strtotime($row['expiry_date_time'])); 

                                            ?>
                                            <tr role="row">
                                                <td><?php echo  $row['subscription_name']; ?></td>
                                                 <td><?php echo $subscription_date; ?></td>
                                                <td><?php echo $expiry_date_time; ?></td>
                                                <td><?php echo  currency_conversion($user_currency_code).get_gigs_currency($row['fee'], $row['currency_code'], $user_currency_code); ?></td>
                                                <td><span class="badge bg-success-light">Paid</span></td> 
                                            </tr> 
    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
$stripe_option = '1';
$publishable_key = '';
$live_publishable_key = '';
$logo_front = '';
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
    if ($res['key'] == 'logo_front') {
        $logo_front = $res['value'];
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
<input type="hidden" id="stripe_key" value="<?php echo  $stripe_key; ?>">
<input type="hidden" id="logo_front" value="<?php echo  $web_log; ?>">


<button id="my_stripe_payyment">Purchase</button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<!-- Moyasar Payment -->
<input type="hidden" value="<?php echo (!empty($user_language[$user_selected]['lg_Subscription'])) ? $user_language[$user_selected]['lg_Subscription'] : $default_language['en']['lg_Subscription']; ?>" id="paytitle" />
<input type="hidden" id="moyasar_api_key" value="<?php echo  $moyaser_apikey; ?>"/>
