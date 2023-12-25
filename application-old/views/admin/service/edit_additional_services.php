<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Edit Additional Services</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
					
				<div class="card">
					<div class="card-body">
                        <form id="edit_additional_services" method="post" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
                            <div class="form-group">
                                <label>Additional Service For</label>
                                <select class="form-control select" name="services_for" id="services_for">
                                    <option value="">Select Services</option>
                                    <?php foreach ($service_list as $rows) { ?>
                                    <option value="<?php echo $rows['id'];?>" <?php  echo ($services['service_id'] == $rows['id'])?'selected':''; ?>><?php echo $rows['service_title'];?></option>
                                   <?php } ?>
                                </select>
                            </div>
							
                            <div class="form-group">
                                <label>Additional Service Name</label>
                                <input class="form-control" type="text"  name="service_title" id="service_title" value="<?php echo $services['service_name'];?>">
								<input class="form-control" type="hidden" value="<?php echo $services['id'];?>"  name="serviceid" id="serviceid">
                            </div>
                            <div class="form-group">
                                <label>Additional Service Amount</label>
                                <input class="form-control" type="text"  name="amount" id="amount" value="<?php echo $services['amount'];?>">
                            </div>
							<div class="form-group">
                                <label>Additional Service Duration</label>
                                <div class="input-group">
								  <input type="text" class="form-control spldetail " name="duration" value="<?php echo $services['duration'];?>">
								  <div class="input-group-append">
									<span class="input-group-text" id="basic-addon2">min(s)</span>
								  </div>								 
								</div>
                            </div>
							<div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" name="notes" id="notes" rows="5"><?php echo $services['notes'];?></textarea>
                            </div>
							<div class="form-group">
                                <label class="d-block">Additional Service Status</label>
                                <div class="form-check form-radio form-check-inline">
                                    <input type="radio" id="status1" name="status" class="form-check-input"  value="1" <?php echo $services['status']==1 ? "checked":""; ?> >
                                    <label class="form-check-label" for="status1">Active</label>
                                </div>
                                <div class="form-check form-radio form-check-inline">
                                    <input type="radio" id="status2" name="status" class="form-check-input" value="2" <?php echo $services['status']==2 ? "checked":""; ?>>
                                    <label class="form-check-label" for="status2">Inactive</label>
                                </div>
                            </div>
							<div class="form-group">
								<label>Additional Service Image</label>
								<input class="form-control" type="file"  name="addiservice_image" id="addiservice_image">
							</div>
							<?php if(file_exists($services['additional_service_image'])) { ?>
							<div class="form-group">
								<div class="avatar">
									<img class="avatar-img rounded" alt="" src="<?php echo base_url().$services['additional_service_image'];?>">
								</div>								
							</div>
							<?php } ?>
                            <div class="mt-4">
                                <?php if($user_role==1){?>
                                <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Edit Additional Service</button>
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