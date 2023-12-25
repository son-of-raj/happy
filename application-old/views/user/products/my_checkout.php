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
} else {
    $user_currency['user_currency_code'] = settingValue('currency_option');
}
$user_currency_code = $user_currency['user_currency_code'];

if(!empty($cartlist)) {
    foreach ($cartlist as $cart) {
        $product_price = get_gigs_currency($cart['product_price'], $cart['product_currency'], $user_currency_code);

        $total = $total+$cart['product_total'];
        $cc = ($cart['product_currency'])?$cart['product_currency']:$user_currency_code;
        $product_total_amt = get_gigs_currency($total, $cc, $user_currency_code);
        $sub_product_total = $sub_product_total+$product_total;
    }
} else {
    $product_total_amt = '0';
}
//$product_total_amt = get_gigs_currency($cartlist[0]['product_total'], $cartlist[0]['product_currency'], $user_currency_code);

?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>Checkout</h2>
                </div>
            </div>
            <div class="col-auto float-right ml-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php //echo '<pre>'; print_r($cartlist); exit; ?>
<input type="hidden" id="url_v1" value="<?php echo $url_v1?>">
<input type="hidden" id="payment_order_id" value="<?php echo $order_id; ?>">
<!-- /Breadcrumb -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="billing-box">
                    <div class="box-header">
						<div class="row">
							<div class="col">
								<h3 class="card-title">Billing Details</h3>
							</div>
							<div class="col-auto">
								<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#billing_pop" class="btn-right btn btn-sm btn-primary">
									Add Address
								</a>
							</div>
						</div>
                    </div>
                    <div class="box-content" id="billing_list">
					
                        <div class="my-checkout-col">
                            <div class="row">
                                <?php
                                if (!empty($billing)) 
                                {
                                $n=1;
                                foreach ($billing as $b) 
                                {
                                    ?>
                                <div class="col-12 col-md-4 d-flex">
                                    <div class="inner-checkout-col d-flex w-100">
                                        <div class="card w-100 cart-container">
                                            <input type="radio" name="select_billing" class="select_billing" value="<?php echo $b['id']?>" <?php echo ($b['id']==$cout['billing_details_id'] ? 'checked':'')?>>
                                            <input type="hidden" name="address_type" class="form-control" id="address_type" value="<?php echo ($b['address_type'])?$b['address_type']:'home'; ?>">
                                            <div class="card-body">
                                                <h4>Deliver To</h4>
                                                <p><strong><?php echo $b['full_name']?></strong></p>
                                                <p>Address Type: <?php echo($b['address_type'])?$b['address_type']:'home'; ?></p>
                                                <p><?php echo $b['phone_no']?></p>
                                                <p><?php echo $b['email_id']?></p>
                                                <p><?php echo $b['address']?></p>
                                                <p><?php echo $b['city_name']?>, <?php echo $b['state_name']?></p>
                                                <p><?php echo $b['country_name']?> - <?php echo $b['zipcode']?></p>
                                                <div class="mt-3">
                                                    <button type="button" bid="<?php echo $b['id']?>" class="edit_billing">Edit</button>
                                                    <button type="button" bid="<?php echo $b['id']?>" class="delete_billing">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                        $n++;
                                    }
                                } 
                                else
                                {
                                    ?>
                                <div class="col-12 col-md-6 d-flex">
                                    <div class="inner-checkout-col d-flex w-100">
                                        <div class="card w-100">
                                            <div class="card-body">
                                               <p> No Records Found</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                }
                                ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="checkout-payment-header">
                    <h3 class="mb-3">Payment Option</h3>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="payment-method-wrapper">
						
						<div class="mt-2" id="payment-types">
							<h6>Select Payment Type</h6>
							
						   <?php  if(!empty($paypal_option)) { ?>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="order_payment_type" id="paypal" value="paypal">
								<label class="form-check-label" for="paypal">Paypal</label>
							</div> 
							<?php } if(!empty($stripe_option)) { ?>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="order_payment_type" id="stripe"  value="stripe">
								<label class="form-check-label" for="stripe">Stripe</label>
							</div>
							<?php } if(!empty($razor_option)) { ?>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="order_payment_type" id="razorpay"  value="razorpay">
								<label class="form-check-label" for="razorpay">Razorpay</label>
							</div>
							<?php }  ?>
							
							<?php if(!empty($moyaser_option)) { ?>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="order_payment_type" id="moyasarpay"  value="moyasarpay">
								<label class="form-check-label" for="moyasarpay">
									<?php echo (!empty($user_language[$user_selected]['lg_Moyasar'])) ? $user_language[$user_selected]['lg_Moyasar'] : $default_language['en']['lg_Moyasar']; ?>
								</label>
							</div>
							<?php } ?>
						</div>

                            <div id="moyasarpay_form" style="display: none;">
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

                                    <div class="" id="v-pills-moyasar" role="tabpanel" aria-labelledby="v-pills-moyasar-tab">
                                        <div class="payment-form">
                                            <div class="form">
                                                <div>
                                                    <div class="order-mysr-form"></div>
                                                    
                                                    <input type="hidden" id="order_callbackurl" value="<?php echo base_url()?>user/products/moyasar_redirect/<?php echo $order_id?>/<?php echo $cout['address_type']?>" />

                                                    <input type="hidden" id="publishable_apikey" value="<?php echo $moyaser_apikey?>"/>

                                                    <input type="hidden" id="amount" value="<?php echo $product_total_amt?>" />

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

            <!-- Stripe Details -->
            <input type="hidden" id="stripe_key" value="<?php echo $stripe_apikey; ?>">
            <input type="hidden" id="logo_front" value="<?php echo $web_logo; ?>">
            <input type="hidden" id="total_amt" value="<?php echo $product_total_amt; ?>">
            <input type="hidden" id="user_currency" value="<?php echo $user_currency_code; ?>">
            <button id="order_stripe_payment" style="display: none;">Purchase</button>
            <!-- Stripe Details -->
            <div class="col-md-4">
                <div class="cart-price-wrapper">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3">Your Order</h3>
							
							

<table class="checkout-total">
	<thead class="checkout-total-header">
		<tr>
			<th>Product</th>
			<th><?php echo (!empty($user_language[$user_selected]['lg_total'])) ? $user_language[$user_selected]['lg_total'] : $default_language['en']['lg_total']; ?></th>
		</tr>
	</thead>
	<tbody class="checkout-total-products">
        <?php 
		
		$sub_product_total = 0;
		if (!empty($cartlist)) { 
            foreach ($cartlist as $cart) {
                $product_price = get_gigs_currency($cart['product_price'], $cart['product_currency'], $user_currency_code);

                $cc = ($user_currency_code)?$user_currency_code:$cart['product_currency'];
                $total = get_gigs_currency($cart['product_total'], $cart['product_currency'], $cc);
                $product_total = get_gigs_currency($total, $cc, $user_currency_code);
				$sub_product_total = $sub_product_total+$product_total;
            ?>
		<tr>
			<td><?php echo $cart['product_name']?> × <?php echo $cart['qty']; ?></td>
			<td><?php echo currency_conversion($cc).$total; ?></td>
		</tr>

        <?php } ?>
	</tbody>
	<tbody class="checkout-total-subtotals">
		<tr>
			<th>Subtotal</th>
			<td><?php echo currency_conversion($cc).$sub_product_total; ?></span></td>
		</tr>
	</tbody>
    <?php } ?>
	<tfoot class="checkout-total-footer">
		<tr>
			<th><?php echo (!empty($user_language[$user_selected]['lg_total'])) ? $user_language[$user_selected]['lg_total'] : $default_language['en']['lg_total']; ?></th>
			<td>
				<?php echo currency_conversion($cc)?><span id="total"><?php echo $sub_product_total?></span>
				<input type="hidden" id="order_id" value="<?php echo $order_id?>">
                <input type="hidden" id="product_tot_amt" value="<?php echo $sub_product_total; ?>">
			</td>
		</tr>
	</tfoot>
</table>



							<div class="mt-3">
								<a href="javascript:void(0);" class="btn addcart-btn cart-btn-1 w-100" id="proceed_payment">Place Order</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Paypal Details -->
            <?php $user_details = $this->db->get_where('users', array('id'=>$this->session->userdata('id')))->row_array();
            $form_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; ?>
            <form name="order_paypal_detail" id="order_paypal_detail"  action="<?php echo$form_url?>" method="POST">
                                
                <input type='hidden' name='business' value="<?php echo $this->session->userdata('email'); ?>"> 
                <input type='hidden' name='item_number' value="123456"> 
                <input type='hidden' name='amount' id="product_total_amt" value='<?php echo $sub_product_total; ?>'> 
                <input type='hidden' name='currency_code' value='USD'>
                <input type='hidden' name='return' id='paypal_return_url' value="">

                <input type="hidden" name="cmd" value="_xclick">  
                <input type="hidden" id="paypal_gateway" value="<?php echo $paypal_gateway; ?>">
                <input type="hidden" id="braintree_key" value="<?php echo $braintree_key; ?>">
                        
                <input type="hidden" id="razorpay_apikey" value="<?php echo $razorpay_apikey; ?>">

                <input type="hidden" id="username" value="<?php echo $user_details['name']; ?>">
                <input type="hidden" id="mobileno1" value="<?php echo $user_details['mobileno']; ?>">


                <input type="hidden" id="state" value="<?php echo (!empty($state)) ? $state : ""; ?>">
                <input type="hidden" id="country" value="<?php echo (!empty($country)) ? $country : ""; ?>">
                <input type="hidden" id="pincode" value="<?php echo (!empty($user_details['pincode'])) ? $user_details['pincode'] : ""; ?>">
                <input type="hidden" id="address" value="<?php echo (!empty($user_details['address'])) ? $user_details['address'] : ""; ?>">
                <input type="hidden" id="city" value="<?php echo (!empty($city)) ? $city : "Chicago"; ?>">
            </form> 
            <!-- Paypal Details -->
<!-- <div id="billing_pop" class="modal fade" role="dialog"> -->
<div class="modal fade" onload="empty_form()" id="billing_pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Billing Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="billing_details_form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="full_name" class="form-control nv" placeholder="Full Name">
                                <input type="hidden" id="bd_id" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone No 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="phone_no" class="form-control nv" placeholder="Phone No" maxlength="10">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email ID
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="email_id" class="form-control nv" placeholder="Email ID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="checkout_address" class="form-control nv" placeholder="Address">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="country_id" class="form-control nv">
                                    <option value="">Select</option>
                                    <?php
                                    if ($country) 
                                    {
                                        foreach ($country as $c)
                                        {
                                            ?>
                                            <option value="<?php echo $c['id']?>"><?php echo $c['country_name']?></option>
                                            <?php 
                                        }
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="state_id" class="form-control nv">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="city_id" class="form-control nv">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Zipcode
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="zipcode" class="form-control nv" placeholder="Zipcode">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address Type
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="address_types" class="form-control nv">
                                    <option value="">Select Address Type</option>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save_billing">Save</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div id="delete_pop" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <b>Are you sure want to delete?</b>
                <input type="hidden" id="hb_id" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm_dbd">Delete</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>