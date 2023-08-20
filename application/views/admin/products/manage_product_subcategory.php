<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Product Sub Category</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
						<form id="psub_category" action="<?php echo $base_url?>manage-product-subcategory/<?php echo $sub_cat_id?>" method="post" autocomplete="off" enctype="multipart/form-data">
							<div class="form-group">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
								<input type="hidden" name="sub_cat_id" value="<?php echo $sub_cat_id?>"/>
								<label>Category</label>
								<select name="category" class="form-control select">
									<?php
									foreach($cat_list as $c)
									{ 
									?>
									<option value="<?php echo $c['id']?>" <?php echo ($c['id']==$cat['category'] ? 'selected':'')?>><?php echo $c['category_name']?></option>
									<?php 
									}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Sub Category Name</label>
								<input class="form-control" type="text"  name="subcategory_name" id="subcategory_name" value="<?php echo $cat['subcategory_name']?>">
							</div>
							
							<div class="form-group">
								<label>Category Image</label>
								<input class="form-control" type="file"  name="subcat_image" id="subcat_image">
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
								<a href="<?php echo $base_url; ?>product-subcategories"  class="btn btn-danger">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

