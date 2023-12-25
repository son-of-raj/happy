<div class="page-wrapper">
	<div class="content container-fluid">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="page-title m-b-20 m-t-0">Currency Create</h4>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url().'admin/settings/create_currency/'; ?>" method="POST" enctype="multipart/form-data" id="currency_add">
                            <div class="form-group">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                            <label>Currency Name</label>
                            <input type="text" class="form-control" name="currency_name" id="currency_name">
                        </div>
                        <div class="form-group">
                            <label>Currency Symbol</label>
                            <input type="text" class="form-control" name="currency_symbol" id="currency_symbol">
                        </div>
                        <div class="form-group">
                            <label>Currency Code</label>
                            <input type="text" class="form-control" name="currency_code" id="currency_code">
                        </div>
                        <div class="form-group">
                            <label>Currency Rate</label>
                            <input type="number" step="0.01" min="0" class="form-control cur-rate" name="rate" id="rate">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            &nbsp;
                            <label><input type="radio" name="status" value="1" checked>Active</label>
                            &nbsp;
                             <label><input type="radio" name="status" value="2">Inactive</label>
                        </div>
                            <div class="mt-4">
                                <?php if($user_role==1) { ?>
                                <button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
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