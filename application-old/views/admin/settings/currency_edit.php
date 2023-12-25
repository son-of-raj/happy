<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="page-title m-b-20 m-t-0">Currency Edit</h4>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url().'admin/settings/currency_edit/' . $currencylist['id']; ?>" method="POST" enctype="multipart/form-data" id="currency_edit">
                        <div class="form-group">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                <label>Currency Name</label>
                                <input type="text" class="form-control" name="currency_name" id="currency_name" value="<?php if ($currencylist['currency_name']) { echo $currencylist['currency_name']; } ?>">
                        </div>
                        <div class="form-group">
                            <label>Currency Symbol</label>
                            <input type="text" class="form-control" name="currency_symbol" id="currency_symbol" value="<?php if ($currencylist['currency_symbol']) { echo $currencylist['currency_symbol']; } ?>">
                        </div>
                        <div class="form-group">
                            <label>Currency Code</label>
                            <input type="text" class="form-control" name="currency_code" id="currency_code" value="<?php if ($currencylist['currency_code']) { echo $currencylist['currency_code']; } ?>">
                        </div>
                        <div class="form-group">
                            <label>Currency Rate</label>
                            <input type="number" step="0.01" min="0" class="form-control cur-rate" name="rate" id="rate" value="<?php if ($currencylist['rate']) { echo $currencylist['rate']; } ?>">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            &nbsp;
                            <label><input type="radio" name="status" value="1" <?php echo (!empty($currencylist['status'])&&$currencylist['status']==1)?'checked':'';?>>Active</label>
                            &nbsp;
                             <label><input type="radio" name="status" <?php echo (!empty($currencylist['status'])&&$currencylist['status']==2)?'checked':'';?> value="2">Inactive</label>
                        </div>
                        <div class="mt-4">
                            <?php if($user_role==1) { ?>
                            <button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
                            <?php } ?>
                            <a href="<?php echo $base_url; ?>admin/currency-settings"  class="btn btn-cancel">Cancel</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>