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
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">Moyasar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/stripe-payment-gateway'; ?>">Stripe</a>
            </li>
            <li class="nav-item ">
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
                        <form action="<?php echo base_url() . 'admin/settings/moyaser_edit/' . $list['id']; ?>" method="post">
                            <h4 class="text-primary">Moyasar</h4>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="outerDivFull" >
							<div class="switchToggle">
								<input name="moyaser_show" type="checkbox"  value="1" id="switch" <?php if($list['status']== 1) { ?>checked <?php } ?>>
								<label for="switch">Toggle</label>
							</div>
							</div>

                            <div class="form-group">
                                <label>Moyasar Option</label>

                                <div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input moyaser_payment" id="sandbox" name="gateway_type" value="sandbox" type="radio" <?php echo  ($list['gateway_type'] == "sandbox") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="sandbox">Sandbox</label>
                                    </div>
                                    <div class="form-check form-radio form-check-inline">
                                        <input class="form-check-input moyaser_payment" id="livemoyaser" name="gateway_type" value="live" type="radio"  <?php echo  ($list['gateway_type'] == "live") ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="livemoyaser">Live</label>
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
                            <?php if ($this->session->userdata('role') == 1) { ?>
                                <input type="text" id="api_key" name="api_key" value="<?php if (!empty($list['api_key'])) {echo $list['api_key'];} ?>" class="form-control">
                            <?php } else {
                                $api_length = strlen($list['api_key']);
                                $str = str_repeat("x", $api_length-8);
                                $api_key = "pk_test_". $str;
                             ?>
                                <input type="text" id="api_key" name="api_key" value="<?php if (!empty($api_key)) {echo $api_key;} ?>" class="form-control">
                            <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Rest Key</label>
                             <?php if ($this->session->userdata('role') == 1) { ?>
                                <input type="text" id="value" name="value" value="<?php if (!empty($list['api_secret'])) {echo $list['api_secret'];} ?>" class="form-control">
                            <?php } else {
                                $value_length = strlen($list['api_secret']);
                                $strs = str_repeat("x", $value_length-8);
                                $value = "sk_test_". $strs;
                             ?>
                                <input type="text" id="value" name="value" value="<?php if (!empty($value)) {echo $value;} ?>" class="form-control">
                            <?php } ?>
                            </div>
                            <div class="mt-4">
<?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
<?php } ?>

                                <a href="<?php echo base_url() . 'admin/moyaser-payment-gateway' ?>" class="btn btn-danger m-l-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
