
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
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/stripe-payment-gateway'; ?>">Stripe</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="<?php echo base_url() . 'admin/paypal-payment-gateway'; ?>">PayPal</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/offlinepayment'; ?>">Bank Transfer</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/cod-payment-gateway'; ?>">COD</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">	
                    <div class="card-body">
                        <form action="" method="post">
                            <h4 class="text-primary">Cash On Delivery</h4>
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="outerDivFull" >
							<div class="switchToggle">
								<input name="cod_show" type="checkbox"  value="1" id="switch" <?php if($list['status']== 1) { ?>checked <?php } ?>>
								<label for="switch">Toggle</label>
							</div>
							</div>

    
                            <div class="mt-4">
<?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Submit</button>
<?php } ?>

                                <a href="<?php echo base_url() . 'admin/cod-payment-gateway' ?>" class="btn btn-danger m-s-5">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
