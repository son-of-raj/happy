<?php 

$query = $this->db->query("SELECT * FROM offline_payment");
$bank_details = $query->row_array();
?>
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
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/offlinepayment'; ?>">Bank Transfer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/cod-payment-gateway'; ?>">COD</a>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="card">
                    <div class="card-body">
                        <form id="paypal_save" method="post" autocomplete="off" enctype="multipart/form-data">
                        	<div class="row">
								<div class="col">
									<h4 class="card-title">Bank Transfer</h4>
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
								</div>
								<div class="col-auto">
    								<div class="switchToggle">
                                    <input name="offline_show" type="checkbox"  value="1" id="switch" <?=$bank_details['status']?'checked':'';?>>
                                    <label for="switch">Toggle</label>
                                    </div>
								</div>

							</div>
                            
                                <div class="form-group">
                                    <label class="control-label">Bank Name</label>
                                    <input class="form-control" type="text" name="bank_name" value="<?php if (isset($bank_details['bank_name'])) echo $bank_details['bank_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Account Holder Name</label>
                                    <input class="form-control" type="text" name="holder_name" value="<?php if (isset($bank_details['holder_name'])) echo $bank_details['holder_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Account Number</label>
                                    <input class="form-control" type="number" name="account_num" value="<?php if (isset($bank_details['account_num'])) echo $bank_details['account_num']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">IFSC Code</label>
                                    <input class="form-control" type="text" name="ifsc_code" value="<?php if (isset($bank_details['ifsc_code'])) echo $bank_details['ifsc_code']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Branch Name</label>
                                    <input class="form-control" type="text" name="branch_name" value="<?php if (isset($bank_details['branch_name'])) echo $bank_details['branch_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label class="control-label">UPI Id</label>
                                    <input class="form-control" type="text" name="upi_id" value="<?php if (isset($bank_details['upi_id'])) echo $bank_details['upi_id']; ?>">
                                </div>
                                <div class="m-t-20 ">
                                <button class="btn btn-primary addCat" name="form_submit" value="submit" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
