<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Add Additional Services</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
					
				<div class="card">
					<div class="card-body">
                        <form id="add_additional_services" method="post" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
                            <div class="form-group">
                                <label>Additional Service For</label>
                                <select class="form-control select" name="services_for" id="services_for">
                                    <option value="">Select Services</option>
                                    <?php foreach ($service_list as $rows) { ?>
                                    <option value="<?php echo $rows['id'];?>"><?php echo $rows['service_title'];?></option>
                                   <?php } ?>
                                </select>
                            </div>
							
                            <div class="form-group">
                                <label>Additional Service Name</label>
                                <input class="form-control" type="text"  name="service_title" id="service_title">
                            </div>
                            <div class="form-group">
                                <label>Additional Service Amount</label>
                                <input class="form-control" type="text"  name="amount" id="amount" >
                            </div>
							<div class="form-group">
                                <label>Additional Service Duration</label>
                                <div class="input-group">
								  <input type="text" class="form-control spldetail " name="duration" value="">
								  <div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">min(s)</span>
								  </div>								 
								</div>
                            </div>
							<div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="5"> </textarea>
                            </div>
							<div class="form-group">
                                <label class="d-block">Additional Service Status</label>
                                <div class="form-check form-radio form-check-inline">
                                    <input type="radio" id="status1" name="status" class="form-check-input"  value="1" checked="checked" >
                                    <label class="form-check-label" for="status1">Active</label>
                                </div>
                                <div class="form-check form-radio form-check-inline">
                                    <input type="radio" id="status2" name="status" class="form-check-input" value="2">
                                    <label class="form-check-label" for="status2">Inactive</label>
                                </div>
                            </div>
							<div class="form-group">
                                <label>Additional Service Image</label>
                                <input class="form-control" type="file"  name="addiservice_image" id="addiservice_image" >
                            </div>
                            <div class="mt-4">
                                <?php if($user_role==1){?>
                                <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Add Additional Service</button>
                                <?php }?>
                                     
								<a href="<?php echo $base_url; ?>additional-services" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>