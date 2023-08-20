<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Razorpay Gateway</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <ul class="nav nav-tabs menu-tabs">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/settings'; ?>">General Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/emailsettings'; ?>">Email Settings</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">Payment Gateway</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/sms-settings'; ?>">SMS Gateway</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/theme-color'; ?>">Theme Color Change</a>
            </li>
        </ul>



        <div class="row">
            <div class="col-lg-8">
                <div class="card">
				
                    <div class="card-body">
					
							<ul class="nav nav-tabs menu-tabs">
								<li class="nav-item">
									<a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">MOYASER PAYMENT</a>
								</li>
								<li class="nav-item ">
									<a class="nav-link" href="<?php echo base_url() . 'admin/stripe-payment-gateway'; ?>">Stripe</a>
								</li>
								<li class="nav-item active">
									<a class="nav-link" href="<?php echo base_url() . 'admin/razorpay-payment-gateway'; ?>">Razorpay </a>
								</li>
								<li class="nav-item ">
									<a class="nav-link" href="<?php echo base_url() . 'admin/paypal-payment-gateway'; ?>">PayPal</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="<?php echo base_url() . 'admin/cod-payment-gateway'; ?>">COD</a>
								</li>
							</ul>
							
							
                        <form action="<?php echo base_url() . 'admin/settings/razor_edit/' . $list['id']; ?>" method="post">
                            <h4 class="text-primary">Razorpay</h4>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="outerDivFull" >
							<div class="switchToggle">
								<input name="razor_show" type="checkbox"   value="1" id="switch" <?php if($list['status']== 1) { ?>checked <?php } ?>>
								<label for="switch">Toggle</label>
							</div>
							</div>
                            <div class="form-group">
                                <label>Razorpay Option</label>

                                <div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input razorpay_stripe_payment" id="sandbox" name="gateway_type" value="sandbox" type="radio" <?php echo  ($list['gateway_type'] == "sandbox") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="sandbox">Sandbox</label>
                                    </div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input razorpay_stripe_payment" id="livepaypal" name="gateway_type" value="live" type="radio"  <?php echo  ($list['gateway_type'] == "live") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="livepaypal">Live</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gateway Name</label>
                                <input  type="text" id="gateway_name" name="gateway_name"  value="<?php if (!empty($list['gateway_name'])) {
    echo $list['gateway_name'];
} ?>" required class="form-control" placeholder="Gateway Name">
                            </div>
                            <div class="form-group">
                                <label>API Key</label>
                                <input type="text" id="api_key" name="api_key" value="<?php if (!empty($list['api_key'])) {
    echo $list['api_key'];
} ?>" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Rest Key</label>
                                <input type="text" id="value" name="value" value="<?php if (!empty($list['api_secret'])) {
    echo $list['api_secret'];
} ?>" required class="form-control">
                            </div>
                            <div class="mt-4">
<?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
<?php } ?>

                                <a href="<?php echo base_url() . 'admin/razorpay-payment-gateway' ?>" class="btn btn-danger m-s-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
