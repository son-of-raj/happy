<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Products</h3>
				</div>
				<div class="col-auto text-end">
					<a href="<?php echo $base_url; ?>admin-product-list" class="btn btn-white add-button"><i class="fas fa-sync"></i></a>
					<a class="btn btn-white filter-btn me-3" href="javascript:void(0);" id="filter_search">
						<i class="fas fa-filter"></i>
					</a>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Search Filter -->
		<form action="<?php echo base_url()?>admin-product-list" method="post" id="filter_inputs">
			<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
    
			<div class="card filter-card">
				<div class="card-body pb-0">
					<div class="row filter-row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Category</label>
								<select id="product_category" name="where[products-category]" class="form-control select">
									<option value="">All</option>
									<?php
									foreach($cat_list as $c)
									{ 
									?>
									<option value="<?php echo $c['id']?>"><?php echo $c['category_name']?></option>
									<?php 
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Sub Category</label>
								<select id="product_subcategory" name="where[products-subcategory]" class="form-control">
									<option value="">All</option>
									<?php
									foreach($sub_cat_list as $s)
									{ 
									?>
									<option value="<?php echo $s['id']?>"><?php echo $s['subcategory_name']?></option>
									<?php 
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label>Product Name</label>
								<input type="text" class="form-control" name="search[products-product_name]" value="">
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
		<?php 	if($this->session->userdata('role') == 1) { 
					$display_data = '';
				} else {
					$display_data = 'd-none';
				} ?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-center mb-0 categories_table" >
								<thead>
									<tr>
										<th>#</th>
										<th>Shops</th>
										<th>Category</th>
										<th>Sub Categry Name</th>
										<th>Product Name</th>
										<th>Image</th>
										<th>Date</th>
										<th class="<?php echo $display_data; ?>">Action</th>
									  
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($list)) 
									{
										$i=1;
										foreach ($list as $val) {
											$date=date(settingValue('date_format'). ' h:i:A', strtotime($val['created_date'])); ?>
											<tr id="row_<?php echo $val['id']?>">
												<td><?php echo $i?></td>
												<td><?php echo $val['shop_name']?></td>
												<td><?php echo $val['category_name']?></td>
												<td><?php echo $val['subcategory_name']?></td>
												<td><?php echo wordwrap($val['product_name'], 40, '<br />', true); ?></td>
												<td><img class="avatar-sm rounded me-1" src="<?php echo base_url().$val['product_image']?>" alt="Category Image"></td>
												<td><?php echo  $date; ?></td>
												<td>
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
        <p>Are you sure want to delete this Product?.</p>
        <input type="hidden" id="hcat_id" value="">
        <input type="hidden" id="htable" value="products">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirm_dpcat">Yes</button>
        <button type="button" class="btn btn-default" data-bs-dismiss="modal">No</button>
      </div>
    </div>

  </div>
</div>