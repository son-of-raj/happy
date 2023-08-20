<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Product Category</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form action="<?php echo $base_url?>manage-product-category/<?php echo $cat_id?>" method="post" autocomplete="off" enctype="multipart/form-data">
							<div class="form-group">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
								<input type="hidden" name="cat_id" value="<?php echo $cat_id?>"/>
								<label>Category Name</label>
								<input class="form-control" type="text"  name="category_name" id="category_name" value="<?php echo $cat['category_name']?>" required>
							</div>
							
							<div class="form-group">
								<label>Category Image</label>
								<input class="form-control" type="file"  name="category_image" id="category_image">
							</div>
							<?php
							if ($cat['thumb_image']!='') 
							{
							?>
							<div class="form-group">
								<div class="avatar">
									<img class="avatar-img rounded" alt="" src="<?php echo base_url().$cat['thumb_image']?>">
								</div>
                            </div>
                            <?php 
                        	}
                            ?>
							<div class="mt-4">
							<?php if($this->session->userdata('role') == 1) { ?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Save</button>
							<?php } ?>
								<a href="<?php echo $base_url; ?>product-categories"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

