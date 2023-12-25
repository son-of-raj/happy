<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Payment Settings</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

      <ul class="nav nav-tabs menu-tabs">
		<li class="nav-item">
			<a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">Moyasar</a>
		</li>
		<li class="nav-item ">
			<a class="nav-link" href="<?php echo base_url() . 'admin/stripe-payment-gateway'; ?>">Stripe</a>
		</li>
		<li class="nav-item active">
			<a class="nav-link" href="<?php echo base_url() . 'admin/paypal-payment-gateway'; ?>">PayPal</a>
		</li>
		<li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/offlinepayment'; ?>">Bank Transfer</a>
            </li>
		<li class="nav-item">
			<a class="nav-link" href="<?php echo base_url() . 'admin/cod-payment-gateway'; ?>">COD</a>
		</li>
	</ul>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url() . 'admin/settings/paypal_edit/' . $list['id']; ?>" method="post">
                            <h4 class="text-primary">Paypal</h4>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="outerDivFull" >
							<div class="switchToggle">
								<input name="paypal_show" type="checkbox"   value="1" id="switch" <?php if($list['status']== 1) { ?>checked <?php } ?>>
								<label for="switch">Toggle</label>
							</div>
							</div>
                            <div class="form-group">
									<label>Paypal Gateway</label>
								<input type="radio" required="" class="paypal_payment" name="paypal_gateway"  value="sandbox" <?php echo  ($list['gateway_type'] == "sandbox") ? 'checked' : '' ?>>
								Sandbox &nbsp;	
								<input type="radio" required="" class="paypal_payment" name="paypal_gateway"  value="production" <?php echo  ($list['gateway_type'] == "production") ? 'checked' : '' ?>>
								Production
							</div>
							<div class="form-group">
									<label>Braintree Tokenization key</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="braintree_key" id="braintree_key" value="<?php if (!empty($list['braintree_key'])) {echo $list['braintree_key'];} ?>" >
                            <?php } else {
                            	$account_length = strlen($list['braintree_key']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $braintree_key = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($braintree_key)) {echo $braintree_key;} ?>" class="form-control">
                            <?php } ?>
							</div>
							<div class="form-group">
									<label>Braintree Merchant ID</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="braintree_merchant" id="braintree_merchant" value="<?php if (!empty($list['braintree_merchant'])) {echo $list['braintree_merchant'];} ?>" >
                            <?php } else {
                            	$account_length = strlen($list['braintree_merchant']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $braintree_merchant = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($braintree_merchant)) {echo $braintree_merchant;} ?>" class="form-control">
                            <?php } ?>
							</div>
							<div class="form-group">
									<label>Braintree Public key</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="braintree_publickey" id="braintree_publickey" value="<?php if (!empty($list['braintree_publickey'])) {echo $list['braintree_publickey'];} ?>" >
                            <?php } else {
                            	$account_length = strlen($list['braintree_publickey']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $braintree_publickey = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($braintree_publickey)) {echo $braintree_publickey;} ?>" class="form-control">
                            <?php } ?>
							</div>
							<div class="form-group">
									<label>Braintree Private key</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="braintree_privatekey" id="braintree_privatekey" value="<?php if (!empty($list['braintree_privatekey'])) {echo $list['braintree_privatekey'];} ?>" >
                            <?php } else {
                            	$account_length = strlen($list['braintree_privatekey']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $braintree_privatekey = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($braintree_privatekey)) {echo $braintree_privatekey;} ?>" class="form-control">
                            <?php } ?>
							</div>
							<div class="form-group">
									<label>Paypal APP ID</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="paypal_appid" id="paypal_appid" value="<?php if (!empty($list['paypal_appid'])) {echo $list['paypal_appid'];} ?>">
                            <?php } else {
                            	$account_length = strlen($list['paypal_appid']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $paypal_appid = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($paypal_appid)) {echo $paypal_appid;} ?>" class="form-control">
                            <?php } ?>
							</div>
							<div class="form-group">
									<label>Paypal Secret Key</label>
							<?php if ($this->session->userdata('role') == 1) { ?>
                                <input class="form-control" type="text" name="paypal_appkey" id="paypal_appkey" value="<?php if (!empty($list['paypal_appkey'])) {echo $list['paypal_appkey'];} ?>" >
                            <?php } else {
                            	$account_length = strlen($list['paypal_appkey']);
			                    $strs = str_repeat("x", $account_length-1);
			                    $paypal_appkey = "". $strs;
                             ?>
                            	<input type="text" id="value" name="value" value="<?php if (!empty($paypal_appkey)) {echo $paypal_appkey;} ?>" class="form-control">
                            <?php } ?>
							</div>
                            <div class="mt-4">
<?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
<?php } ?>

                                <a href="<?php echo base_url() . 'admin/paypal-payment-gateway' ?>" class="btn btn-danger m-l-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
