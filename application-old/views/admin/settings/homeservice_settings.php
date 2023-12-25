<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Service Charges</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
		<ul class="nav nav-tabs menu-tabs">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/homeservice-settings'; ?>">Service Charges</a>
            </li>
		</ul>
		
        <div class="row">
			 <div class="col-xl-3 col-lg-4 col-md-4 settings-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="nav flex-column">
                            <a class="nav-link active" data-bs-toggle="tab" href="#shopfee">Shop Fee</a>
							<a class="nav-link " data-bs-toggle="tab" href="#covidcontrol">Corona Control</a>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-8">
                <div class="card">
				
                    <div class="card-body">
							
                        <form action="" method="post">
                            
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							
							 <div class="tab-content pt-0">
                                <!-- Shops Fee -->
                                <div id="shopfee" class="tab-pane active">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h4 class="card-title">New Shop Fee</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Fee for new shop</label>
                                                <input type="text" class="form-control numbersOnly" id="shopfee_val" name="shopfee_val" value="<?php if(!empty($shop_fee)){ echo $shop_fee; } ?>" placeholder="Enter the new shop fee charge">
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- Shops Fee -->
								
                                <!-- Corona Control -->
                                <div id="covidcontrol" class="tab-pane">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h4 class="card-title">Corona Control</h4>
											<div class="outerDivFull" >
												<div class="switchToggle">
													<input name="coronacontrol" type="checkbox"  value="1" id="switch" <?php if($corona_control== 1) { ?>checked <?php } ?>>
													<label for="switch">Toggle</label>
												</div>
											</div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Shops Fee -->
								<div class="card-body pt-0mt-4">
									<?php if ($user_role == 1) { ?>
										<button class="btn btn-primary" name="form_submit" value="submit" type="submit">Save Changes</button>
									<?php } ?>									
								</div>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
