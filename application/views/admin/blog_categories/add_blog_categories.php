<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-xl-8 offset-xl-2">
			
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Add Blog Category</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				<div class="card">
					<div class="card-body">
						<form id="add_blog_category" method="post" autocomplete="off" enctype="multipart/form-data">
							<div class="form-group">
								<label>Language</label>
								<select name="lang_id" class="form-control">

									<?php foreach ($languages as $language): ?>
										<option value="<?php echo $language['id']; ?>" ><?php echo $language['language']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
    

								<label>Category Name</label>
								<input class="form-control" type="text"  name="name" id="category_name">
							</div>
							<div class="form-group">
								<label class="control-label">Slug
									<small>(If you leave it empty, it will be generated automatically.)</small>
								</label>
								<input type="text" class="form-control" name="slug" placeholder="Slug"
									value="" >
							</div>

							<div class="form-group">
								<label class="control-label">Description (Meta Tag)</label>
								<input type="text" class="form-control" name="description"
									placeholder="Description (Meta Tag)" value="" >
							</div>

							<div class="form-group">
								<label class="control-label">Keywords (Meta Tag)</label>
								<input type="text" class="form-control" name="keywords"
									placeholder="Keywords (Meta Tag)" value="" >
							</div>

							<div class="form-group d-none">
								<label>Order</label>
								<input type="number" class="form-control" name="category_order" placeholder="Order"
									value="1" min="1" required>
							</div>
							<div class="mt-4">
								<?php if($user_role==1){ ?>
								<button class="btn btn-primary " name="form_submit" value="submit" type="submit">Add Category</button>
							<?php }?>

								<a href="<?php echo $base_url; ?>blog-categories"  class="btn btn-cancel">Cancel</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

