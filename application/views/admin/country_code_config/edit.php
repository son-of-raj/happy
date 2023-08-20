<div class="page-wrapper">
	<div class="content container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h4 class="page-title m-b-20 m-t-0 mb-3">Country code Edit</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            foreach ($datalist as $value) {
                            ?>
                                <form action="<?php echo base_url().'admin/country_code_config/edit/' . $value['id']; ?>" method="POST" enctype="multipart/form-data" id="edit_country_code_config">
                                    <div class="form-group">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <label>Country code</label>
                                    <input type="text" class="form-control" name="country_code" id="country_code" required value="<?php if ($value['country_code']) { echo $value['country_code']; } ?>">
                                </div>
                                <div class="form-group">
                                    <label>Country id</label>
                                    <input type="text" class="form-control" name="country_id" id="country_id" required value="<?php if ($value['country_id']) { echo $value['country_id']; } ?>">
                                </div>
                                <div class="form-group">
                                    <label>Country name</label>
                                    <input type="text" class="form-control" name="country_name" id="country_name" required value="<?php if ($value['country_name']) { echo $value['country_name']; } ?>">
                                </div>
                                    <div class="m-t-30 text-center">
                                        <?php if($this->session->userdata('role') == 1) { ?>
                                            <button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
                                        <?php } ?>
                                        <a href="<?php echo $base_url; ?>admin/country_code_config"  class="btn btn-primary">Cancel</a>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>