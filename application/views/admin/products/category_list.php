<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Categories</h3>
				</div>
				<div class="col-auto text-end">
					<a href="<?php echo $base_url; ?>product-categories" class="btn btn-white add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
					
					<a href="<?php echo $base_url; ?>manage-product-category/0" class="btn btn-white add-button"><i class="fas fa-plus"></i></a>
				
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>product-categories" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Category</label>
								<input type="text" class="form-control" name="search[category_name]" value="">
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>From Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="from_date">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>To Date</label>
								<div class="cal-icon">
									<input class="form-control datetimepicker" type="text" name="to_date">
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<button class="btn btn-primary btn-block" name="form_submit" value="submit" type="submit">Submit</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
		<!-- /Search Filter -->
				
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 categories_table" >
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Image</th>
										<th>Date</th>
										<th>Action</th>
									  
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($list)) 
									{
										$i=1;
										foreach ($list as $val) 
										{
											$date=date(settingValue('date_format').' '.'h:i:A', strtotime($val['created_on']));

											if($this->session->userdata('role') == 1) { 
												$display_data = '';
											} else {
												$display_data = 'd-none';
											}
											?>
											<tr id="row_<?php echo $val['id']?>">
												<td><?php echo $i?></td>
												<td><?php echo $val['category_name']?></td>
												<td><img class="avatar-sm rounded me-1" src="<?php echo base_url().$val['thumb_image']?>" alt="Category Image"></td>
												<td><?php echo  $date; ?></td>
												<td>
													<a href="<?php echo base_url()?>manage-product-category/<?php echo $val['id']?>" class="btn btn-sm bg-success-light me-2"><i class="far fa-edit me-1"></i> Edit</a>
													<a href="javascript:;" cat_id="<?php echo $val['id']?>" class="on-default remove-row btn btn-sm bg-danger-light me-2 delete_pcat <?php echo $display_data; ?>"><i class="far fa-trash-alt me-1"></i> Delete</a>
												</td>
											</tr>
											<?php
											$i++;
										}
									}
									?>
								</tbody>
							</table>
						</div> 
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>

<div id="modal_dpcat" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure want to delete this category?.</p>
        <input type="hidden" id="hcat_id" value="">
        <input type="hidden" id="htable" value="product_categories">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirm_dpcat">Yes</button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">No</button>
      </div>
    </div>

  </div>
</div>