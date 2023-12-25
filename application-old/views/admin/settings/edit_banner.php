<div class="page-wrapper">
	<div class="content container-fluid">
	
		<div class="row">
			<div class="col-xl-8 offset-xl-2">

				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col">
							<h3 class="page-title">Edit Banner</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="card">
					<div class="card-body">
                        <form id="update_category" method="post" autocomplete="off" enctype="multipart/form-data">
                        	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input class="form-control" type="hidden" name="bgimg_for" value="<?php echo $list['bgimg_for']?>" id="bgimg_for">
							<?php
							if($list['bgimg_for']=='banner'){
								$tile = 'Banner';
								$imgnote='image size for banner should be 1400 x 500';
							}
							if($list['bgimg_for']=='bottom_image1'){
								$tile = 'Bottom Image-1';
								$imgnote='image size for bottom image-1 should be 120 x 120';
							}
							if($list['bgimg_for']=='bottom_image2'){
								$tile = 'Bottom Image-2';
								$imgnote='image size for bottom image-2 should be 120 x 120';
							}
							if($list['bgimg_for']=='bottom_image3'){
								$tile = 'Bottom Image-3';
								$imgnote='image size for bottom image-3 should be 120 x 120';
							}
								
								$label= $tile.' Image';
								
							?>
							<div class="form-group">
                                <label><?php echo $tile?> Content</label>
                                <input class="form-control" type="text" name="banner_content" value="<?php echo $list['banner_content']?>" id="banner_content">
                            </div>
							
							<div class="form-group">
                                <label><?php echo $tile?> Sub Content</label>
                                <input class="form-control" type="text" name="banner_sub_content" value="<?php echo $list['banner_sub_content']?>" id="banner_sub_content">
                            </div>
							
							
                            <div class="form-group">
                                <label><?php echo $label?></label>
                                <input class="form-control" type="file" name="upload_image" id="upload_image">
								<div>* <?php echo $imgnote?></div>
                            </div>
                            <div class="form-group">
								<div class="avatar">
									<img class="avatar-img rounded" alt="" src="<?php echo base_url().$list['upload_image'];?>">
								</div>
                            </div>
                            <div class="mt-4">
                            	<?php if($user_role==1){?>
                                <button class="btn btn-primary" name="form_submit" value="submit" type="submit">Save Changes</button>
                                <?php } ?>

								<a href="<?php echo $base_url; ?>admin/banner-image"  class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>