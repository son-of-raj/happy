<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
			  <!-- Page Header -->
			  <div class="page-header">
					<div class="row">
						<div class="col-sm-12">
							<h3 class="page-title">Add Subscription</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                        <form id="addSubscription" method="post">
							<div class="form-group">
                                <label class="d-block">Subscription For</label>
                                <div class="form-check form-radio form-check-inline">
                                    <input type="radio"  id="subfor1" name="subfor" class="form-check-input"  value="1" checked="checked" >
                                    <label class="form-check-label" for="subfor1">Provider</label>
                                </div>
                            </div>
							<div class="form-group">
								<label>Subscription Type</label>
								<select name="subscription_type" id="subscription_type" class="form-control select">
									<option value="1">Basic</option>
									<option value="2">Silver</option>
									<option value="3">Gold</option>
									<option value="4">Free</option>
								</select>
							</div>
                            <div class="form-group">
                                <label>Subscription Name</label>
                                <input class="form-control" type="text" placeholder="Free Trial" name="subscription_name" id="subscription_name">
                            </div>
                            <div class="form-group">
                                <label>Subscription Amount</label>
                                <input class="form-control sub-amount" type="number" step="0.01" min="0" name="amount" id="amount">
                            </div>
                            <div class="form-group">
                                <label>Subscription Durations (in days)</label>
								<input class="form-control" type="number" min="0" max="720" name="duration" id="duration">
							
                                <input type="hidden" name="subscription_description" id="subscription_description" value="">
                            </div>
                            <div class="form-group">
                                <label class="d-block">Subscription Status</label>
                                <div class="form-check custom-radio form-check-inline">
                                    <input type="radio" id="status1" name="status" class="form-check-input"  value="1" checked="checked" >
                                    <label class="form-check-label" for="status1">Active</label>
                                </div>
                                <div class="form-check custom-radio form-check-inline">
                                    <input type="radio" id="status2" name="status" class="form-check-input" value="0">
                                    <label class="form-check-label" for="status2">Inactive</label>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label class="d-block">Subscription Details</label>
                                
								<div class="subscriptions-info">
									<div class="row form-row subscriptions-cont">
										<div class="col-lg-12">
											<table class="table table-bordered sub-details" id="append">	                                    	
												<tbody>
													<tr class="singlerow">
														<td><input type="text" class="form-control" name="subscriptionsdetails[]" value="" required></td>
														<td><a href="#" class="btn btn-danger trash"><i class="far fa-times-circle"></i></a></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="add-more form-group mt-4">
									<a href="javascript:void(0);" class="add-subscriptions"><i class="fas fa-plus-circle"></i> Add More</a>
								</div>
                            </div>
							
                            <div class="mt-4">
								<?php if($user_role==1){?>
                                <button class="btn btn-primary" type="submit">Submit</button>
								<?php }?>

								<a href="<?php echo $base_url; ?>subscriptions" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>