<?php
$currency = currency_conversion(settings('currency'));

$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
    }
}
$user_currency_code = '';
$userId = $this->session->userdata('id');
$type = $this->session->userdata('usertype');
if ($type == 'user') {
    $user_currency = get_user_currency();
} else if ($type == 'provider') {
    $user_currency = get_provider_currency();
} else if ($type == 'freelancer') {
    $user_currency = get_provider_currency();
} 
$user_currency_code = $user_currency['user_currency_code'];
$total_amt = get_gigs_currency($cartlist[0]['product_total'], $cartlist[0]['product_currency'], $user_currency_code);
$razorpay_amt = get_gigs_currency($cartlist[0]['product_total'], $cartlist[0]['product_currency'], 'INR');
//cod check
$cod = settingValue('cod_option');

//echo 'car<pre>'; print_r($total_amt); exit;
?>

<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>Payment</h2>
                </div>
            </div>
            <div class="col-auto float-right ml-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Home <?php echo $cod?></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<section class="site-map">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="checkout-payment-wrapper">
                    <div class="checkout-payment-header">
                        <h3>PAYMENT METHOD</h3>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12 col-lg-8">
                            <div class="payment-method-wrapper">
                                <div class="row">
                                    <div class="col-md-12 col-lg-3">
                                        <div class="payment-method-nav">
                                            <div class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                                <a class="nav-link active" id="v-pills-moyasar-tab" data-toggle="pill" href="#v-pills-moyasar" role="tab" aria-controls="v-pills-moyasar" aria-selected="false">Card</a>
                                                <?php
                                                if ($cod == 1) {
                                                ?>
                                                <a class="nav-link" id="v-pills-cod-tab" data-toggle="pill" href="#v-pills-cod" role="tab" aria-controls="v-pills-cod" aria-selected="false">COD</a>
                                                <?php
                                                } 
                                                ?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="row mt-2" id="payment-types">
                                        <h6>Select Payment Type</h6>
                                        <?php  if(!empty($paypal_option)) { ?>
                                        <div class="col-4">
                                            <input class="form-check-input" type="radio" name="order_payment_type" id="paypal" value="paypal">
                                            <img src="<?php echo base_url() . "assets/img/paypal.png"; ?>">
                                        </div>
                                        <?php } if(!empty($stripe_option)) { ?>
                                        <div class="col-4">
                                            <input class="form-check-input" type="radio" name="order_payment_type" id="stripe"  value="stripe">
                                            <img src="<?php echo base_url() . "assets/img/stripe.png"; ?>">
                                        </div>
                                        <?php } if(!empty($razor_option)) { ?>
                                        <div class="col-4">
                                            <input class="form-check-input" type="radio" name="order_payment_type" id="razorpay"  value="razorpay">
                                            <img src="<?php echo base_url() . "assets/img/razorpay.png"; ?>">
                                        </div>
                                        <?php }  ?>
                                        
                                        <?php if(!empty($moyaser_option)) { ?>
                                        <div class="col-4">
                                            <input class="form-check-input" type="radio" name="order_payment_type" id="moyasarpay"  value="moyasarpay"><label><?php echo (!empty($user_language[$user_selected]['lg_Moyasar'])) ? $user_language[$user_selected]['lg_Moyasar'] : $default_language['en']['lg_Moyasar']; ?></label>
                                        </div>
                                        <?php } ?>
                                        
                                    </div>

                                    <div class="col-md-12 col-lg-9" id="moyasarpay_form" style="display: none;">
                                        <div class="tab-content" id="v-pills-tabContent">
                                            <!--Paypal-->
                                            <div class="tab-pane" id="v-pills-paypal" role="tabpanel" aria-labelledby="v-pills-paypal">
                                                <div class="payment-form">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Card number</label>
                                                                    <input type="text" class="form-control" id="p_card_number">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Card Name</label>
                                                                    <input type="text" class="form-control" id="p_card_name">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Expiry Month</label>
                                                                    <input type="text" class="form-control" id="p_expiry_month" maxlength="2">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Expiry Year</label>
                                                                    <input type="text" class="form-control" id="p_expiry_year" maxlength="2">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>CVV</label>
                                                                    <input type="text" class="form-control" id="p_cvv" maxlength="4">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="payment-btn">
                                                                    <a href="#" class="btn btn-default make_payment" pgw="paypal" idg="p" rtype="url">MAKE PAYMENT</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade active show" id="v-pills-moyasar" role="tabpanel" aria-labelledby="v-pills-moyasar-tab">
                                                <div class="payment-form">
                                                    <div class="form">
														<div class="row">
															<div class="order-mysr-form"></div>
															
															<input type="hidden" id="order_callbackurl" value="<?php echo base_url()?>user/products/moyasar_redirect/<?php echo $order_id?>/<?php echo $cout['address_type']?>" />

															<input type="hidden" id="publishable_apikey" value="<?php echo $moyaser_apikey?>"/>

															<input type="hidden" id="amount" value="<?php echo $total_amt?>" />

															<input type="hidden" id="usercurrency" value="<?php echo $user_currency_code?>" />
														</div>
														
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-stripe" role="tabpanel" aria-labelledby="v-pills-stripe-tab">
                                                <div class="payment-form">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Card number</label>
                                                                    <input type="text" class="form-control" id="s_card_number">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Card Name</label>
                                                                    <input type="text" class="form-control" id="s_card_name">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Expiry Month</label>
                                                                    <input type="text" class="form-control" id="s_expiry_month" maxlength="2">
                                                                </fieldset>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>Expiry Year</label>
                                                                    <input type="text" class="form-control" id="s_expiry_year" maxlength="2">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <fieldset>
                                                                    <label>CVV</label>
                                                                    <input type="text" class="form-control" id="s_cvv" maxlength="4">
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="payment-btn">
                                                                    <a href="#" class="btn btn-default make_payment" pgw="stripe" idg="s" rtype="url">MAKE PAYMENT</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="v-pills-razorpay" role="tabpanel" aria-labelledby="v-pills-razorpay-tab">
                                                <div class="payment-form">
                                                    <div class="form">
                                                        <input type="hidden" id="razorpay_apikey" value="<?php echo $razorpay_apikey?>">
                                                        <input type="hidden" id="razorpay_amt" value="<?php echo $razorpay_amt?>">
                                                        <input type="hidden" id="razorpay_name" value="<?php echo $cout['order_code']?>">
                                                        <input type="hidden" id="razorpay_redirect" value="<?php echo $url_v1?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="payment-btn">
                                                                    <a href="#" class="btn btn-default make_payment" pgw="razorpay" idg="r" rtype="url">MAKE PAYMENT</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-wallet" role="tabpanel" aria-labelledby="v-pills-wallet-tab">
                                                <div class="payment-form">
                                                    <div class="form">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <p class="wal-bal-info">Wallent Balance <span><?php echo currency_conversion($user_currency_code)?> <?php echo $wallet_amt?></span></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="payment-btn">
                                                                    <a href="#" class="btn btn-default make_payment" pgw="wallet" idg="r" rtype="url">MAKE PAYMENT</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="v-pills-cod" role="tabpanel" aria-labelledby="v-pills-cod-tab">
                                                <div class="payment-form">
                                                    <div class="form">
                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="payment-btn">
                                                                    <a href="#" class="btn btn-default make_payment" pgw="cod" idg="c" rtype="url">PAY ON DELIVERY</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-lg-4">
                             <div class="cart-price-wrapper">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="price-det-hdr">Price Details</h2>
                                        <ul>
                                            <li>
                                                <label class="price-split-lbl">Total</label>
                                                <label class="price-split-fig"><?php echo currency_conversion($user_currency_code) . $total_amt; ?></label>
                                            </li>
                                        </ul>
                                        <input type="hidden" id="order_id" value="<?php echo $order_id?>">
                                        <div class="pt-delivery-address pt-3">
                                            <h4>DELIVER TO</h4>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <ul class="address-nick-tag-list">
                                                            <li class="p-0 pr-2">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="address_type" checked="checked" value="home" class="change_at"> Home
                                                                </label>
                                                            </li>
                                                            <li class="p-0 pr-2">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="address_type" value="office" class="change_at"> Office
                                                                </label>
                                                            </li>
                                                            <li class="p-0 pr-2">
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="address_type" value="others" class="change_at"> Others
                                                                </label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <p>
                                                <?php echo $billing[0]['full_name']?><br>
                                                <?php echo $billing[0]['phone_no']?><br>
                                                <?php echo $billing[0]['email_id']?><br>
                                                <?php echo $billing[0]['address']?><br>
                                                <?php echo $billing[0]['city_name']?>, <?php echo $billing[0]['state_name']?><br>
                                                <?php echo $billing[0]['country_name']?> - <?php echo $billing[0]['zipcode']?><br>
                                            </p>
                                            <a data-toggle="modal" data-target="#address_list" class="pay-chadr-link">Change Address</a>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Paypal Details -->
                        <?php $user_details = $this->db->get_where('users', array('id'=>$this->session->userdata('id')))->row_array();
                    $form_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; ?>
                    <form name="order_paypal_detail" id="order_paypal_detail" target="_blank" action="<?php echo$form_url?>" method="POST">
                                        
                        <input type='hidden' name='business' value="<?php echo $this->session->userdata('email'); ?>"> 
                        <input type='hidden' name='item_number' value="123456"> 
                        <input type='hidden' name='amount' value='<?php echo $total_amt; ?>'> 
                        <input type='hidden' name='currency_code' value='USD'>
                        <input type='hidden' name='return' id="paypal_return_url" value="<?php echo base_url() ?>user/products/paypal_order_payment/<?php echo $total_amt; ?>?orderid=<?php echo $order_id;?>&address_type=<?php echo $cout['address_type']; ?>&urlv1=<?php echo $url_v1; ?>">
                        <input type="hidden" name="cmd" value="_xclick">  
                        <input type="hidden" id="paypal_gateway" value="<?php echo $paypal_gateway; ?>">
                        <input type="hidden" id="braintree_key" value="<?php echo $braintree_key; ?>">
                                
                        <input type="hidden" id="razorpay_apikey" value="<?php echo $razorpay_apikey; ?>">

                        <input type="hidden" id="username" value="<?php echo $user_details['name']; ?>">
                        <input type="hidden" id="mobileno1" value="<?php echo $user_details['mobileno']; ?>">


                        <input type="hidden" id="state" value="<?php echo (!empty($state)) ? $state : "IL"; ?>">
                        <input type="hidden" id="country" value="<?php echo (!empty($country)) ? $country : "US"; ?>">
                        <input type="hidden" id="pincode" value="<?php echo (!empty($user_details['pincode'])) ? $user_details['pincode'] : "60652"; ?>">
                        <input type="hidden" id="address" value="<?php echo (!empty($user_details['address'])) ? $user_details['address'] : "1234 Main St."; ?>">
                        <input type="hidden" id="city" value="<?php echo (!empty($city)) ? $city : "Chicago"; ?>">
                    </form> 
                    <!-- Paypal Details -->

                        <!-- Stripe Details -->
                        <input type="hidden" id="stripe_key" value="<?php echo $stripe_apikey; ?>">
                        <input type="hidden" id="logo_front" value="<?php echo $web_logo; ?>">
                        <input type="hidden" id="total_amt" value="<?php echo $total_amt; ?>">
                        <input type="hidden" id="user_currency" value="<?php echo $user_currency_code; ?>">
                        <button id="order_stripe_payment" style="display: none;">Purchase</button>
                        <!-- Stripe Details -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="address_list" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Billing Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($address)) 
                            {
                                $n=1;
                                foreach ($address as $b) 
                                {
                                    ?>
                                    <tr>
                                        <td><input type="radio" name="select_billing" class="select_billing" value="<?php echo $b['id']?>"></td>
                                        <td>
                                            <?php echo $b['full_name']?><br>
                                            <?php echo $b['phone_no']?><br>
                                            <?php echo $b['email_id']?><br>
                                            <?php echo $b['address']?><br>
                                            <?php echo $b['city_name']?>, <?php echo $b['state_name']?><br>
                                            <?php echo $b['country_name']?> - <?php echo $b['zipcode']?><br>
                                        </td>
                                    </tr>
                                    <?php
                                    $n++;
                                }
                            } 
                            else
                            {
                                ?>
                                <tr>
                                    <td colspan="2">No Records Found</td>
                                </tr>
                                <?php 
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="update_order">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>