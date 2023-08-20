<div class="page-wrapper">
	<div class="content container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h4 class="page-title m-b-20 m-t-0 mb-3">Create New City </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="card">
                        <div class="card-body">
                            <form id="add_city_code_config" action="<?php echo base_url().'admin/city_code_config/create'; ?>" method="POST" enctype="multipart/form-data">
                                            
                            <div class="form-group">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <label>Country</label>
                                    <select name="country_id" id="country_id" class="form-control select" required>
    									<option value="">Select country</option>
    									<?php foreach($country as $c) {
                                        $str2 = explode("(", $c['country_name']);
                                        $countryName = $str2[0];?>                                          
    									<option value="<?php echo $c['id']; ?>"><?php 
                                        
                                        echo $countryName;?></option>
    									<?php } ?>
    								</select>
                                </div>
                            <!--                             
                                <div class="form-group">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <label>State</label>
                                    <select name="state_id" id="state_id" class="form-control" required>
    									<option value="">Select State</option>
    									<?php foreach($state as $s) {?>
    										<option value="<?php echo $s['id']; ?>"><?php echo $s['name'];?></option>
    									<?php } ?>
    								</select>
                                </div> -->

                                <div class="form-group">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <label>State</label>
                                    <select name="state_id" id="state_id" class="form-control select" required>
    									<option value="">Select State</option>
    								</select>
                                </div>
                               
                                <div class="form-group">
                                    <label>City name</label>
                                    <input type="text" class="form-control" name="city_name" id="city_name" required>
                                </div>
                                <div class="m-t-30 text-center">
                                    <?php if($this->session->userdata('role') == 1) { ?>
                                        <button name="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>
                                    <?php } ?>
                                    <a href="<?php echo $base_url; ?>admin/city_code_config"  class="btn btn-primary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>