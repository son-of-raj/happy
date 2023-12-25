<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Manage Unit</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form id="units" action="<?php echo $base_url?>manage-product-unit/<?php echo $unit_id?>" method="post" autocomplete="off">
							<div class="form-group">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
								<input type="hidden" name="unit_id" value="<?php echo $unit_id?>"/>
								<label>Unit Name</label>
								<input class="form-control" type="text"  name="unit_name" id="unit_name" value="<?php echo $cat['unit_name']?>">
							</div>
							<div class="mt-4">
							<?php if($this->session->userdata('role') == 1) { ?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Save</button>
							<?php } ?>
								<a href="<?php echo $base_url; ?>product_units"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

