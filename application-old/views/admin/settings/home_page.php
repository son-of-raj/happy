<?php 
$bo1query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image1'");
$bo1result = $bo1query->result_array();

$bo2query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image2'");
$bo2result = $bo2query->result_array();

$bo3query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image3'");
$bo3result = $bo3query->result_array();

$howquery = $this->db->query("select * from language_management WHERE lang_value = 'how it works'");
$howresult = $howquery->result_array();

$how_content= $this->db->query("select * from language_management WHERE lang_value = 'Aliquam lorem ante, dapibus in, viverra quis'");
$how_con_result = $how_content->result_array();



?>
<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-lg-8 m-auto">
				<!-- Page Header -->
				<div class="page-header">
					<div class="row">
						<div class="col-12">
							<h3 class="page-title">Home Page</h3>
						</div>
					</div>
				</div>
				<!-- /Page Header -->
				
				<div class="row">
					<div class=" col-lg-12 col-sm-12 col-12">
						<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/settings/bannersettings'); ?>"  method="POST" enctype="multipart/form-data" >
							 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="card">
							<?php
									if (!empty($list)) {
									foreach ($list as $item) {
									?>
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">Banner Settings</h4>
									<div class="col-auto">
										<div class="status-toggle mr-3">
											 <input  id="banner_showhide" class="check" type="checkbox" name="banner_showhide"<?php echo ($item['banner_settings']==1)?'checked':'';?>>
		                                    <label for="banner_showhide" class="checktoggle">checkbox</label>
                                		</div>
									</div>
								</div>
							</div>
							<div class="card-body">
							
								<div class="form-group">
									<label>Title</label>
									<input type="text" name="banner_content" class="form-control" value="<?php echo ucwords($item['banner_content']); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" name="banner_sub_content" class="form-control" value="<?php echo ucwords($item['banner_sub_content']); ?>">
								</div>
								<div class="form-group">
									<p class="settings-label">Banner image</p>
									<div class="form-group">
										  <input class="form-control" type="file"  name="upload_image" id="upload_image">
									</div>
									<?php if(!empty($item['upload_image'])) { ?>
										<div class="upload-images d-block">
											<img class="thumbnail m-b-0 h-50 w-50" src="<?php echo base_url() . $item['upload_image']; ?>">
											<a href="javascript:void(0);" class="btn-icon logo-hide-btn">
												<i class="fa fa-times"></i>
											</a>
										</div>
									<?php } else { ?>
											<div class="upload-images d-block">
											<img class="thumbnail m-b-0" src="<?php echo base_url() . 'assets/img/banner.jpg'; ?>">
										</div>
									<?php } ?>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-lg-5 col-12">
											<div class="card-heads mb-3">
											<h4 class="card-title f-14">Main Search </h4>
											<div class="status-toggle mr-3">
			                                    <input  id="main_showhide" class="check" type="checkbox" name="main_showhide"<?php echo ($item['main_search']==1)?'checked':'';?>>
			                                    <label for="main_showhide" class="checktoggle">checkbox</label>
                                			</div>
											</div>
											<div class="card-heads mb-3">
												<h4 class="card-title f-14">Popular Searches </h4>
												<div class="status-toggle mr-3">
													 <input  id="popular_showhide" class="check" type="checkbox" name="popular_showhide"<?php echo ($item['popular_search']==1)?'checked':'';?>>
			                                    <label for="popular_showhide" class="checktoggle">checkbox</label>
                                			</div>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Popular Searches Label Name</label>
									<input type="text" class="form-control" name="popular_label" value="<?php echo ucwords($item['popular_label']); ?>">
								</div>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
							<?php }
						}
							 ?>
							</div>
						</div>
					</form>
					<form class="form-horizontal" id="featured_categories" action="<?php echo base_url('admin/settings/featured_categories'); ?>"  method="POST" enctype="multipart/form-data" >
							 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">Featured Shops</h4>
									<div class="col-auto">
										<div class="status-toggle mr-3">
		                                    <input  id="featured_showhide" class="check" type="checkbox" name="featured_showhide"<?php echo settingValue('featured_showhide')?'checked':'';?>>
		                                    <label for="featured_showhide" class="checktoggle">checkbox</label>
                                		</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="featured_title" value="<?php echo settingValue('featured_title'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="featured_content" value="<?php echo settingValue('featured_content'); ?>">
								</div>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
							</div>
						</div>
					</form>
						<form class="form-horizontal" id="popular_services" action="<?php echo base_url('admin/settings/popularservices'); ?>"  method="POST" enctype="multipart/form-data" >
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">Popular Services</h4>
									<div class="col-auto">
										<div class="status-toggle mr-3">
		                                    <input  id="popular_ser_showhide" class="check" type="checkbox" name="popular_ser_showhide"<?php echo settingValue('popular_ser_showhide')?'checked':'';?>>
		                                    <label for="popular_ser_showhide" class="checktoggle">checkbox</label>
                                		</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="title_services" value="<?php echo settingValue('title_services'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="content_services" value="<?php echo settingValue('content_services'); ?>">
								</div>
								<div class="form-group">
									<label class="form-head mb-0">Number of service<span>( Min 6 to Max 20 only )</span></label>
									<input type="number" min="6" max="20" class="form-control numeric" name="services_count" value="<?php echo settingValue('services_count'); ?>">
									<span class="error" style="display: none">* Input digits (0 - 9)</span>
								</div>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
							</div>
						</div>
						</form>

						<form class="form-horizontal" id="popular_services" action="<?php echo base_url('admin/settings/blogcontents'); ?>"  method="POST" enctype="multipart/form-data" >
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<div class="card">
								<div class="card-header">
									<div class="card-heads">
										<h4 class="card-title">Blogs</h4>
										<div class="col-auto">
											<div class="status-toggle mr-3">
			                                    <input  id="blogs_showhide" class="check" type="checkbox" name="blogs_showhide"<?php echo settingValue('blogs_showhide')?'checked':'';?>>
			                                    <label for="blogs_showhide" class="checktoggle">checkbox</label>
	                                		</div>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" name="title_blogs" value="<?php echo settingValue('title_blogs'); ?>">
									</div>
									<div class="form-group">
										<label>Content</label>
										<input type="text" class="form-control" name="content_blogs" value="<?php echo settingValue('content_blogs'); ?>">
									</div>
									<div class="form-groupbtn">
										<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
										<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
									</div>
								</div>
							</div>
						</form>

						<form class="form-horizontal" id="how_it_works" action="<?php echo base_url('admin/settings/howitworks'); ?>"  method="POST" enctype="multipart/form-data" >
							 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">How It Works</h4>
									<div class="col-auto">
										<div class="status-toggle mr-3">
		                                    <input  id="how_showhide" class="check" type="checkbox" name="how_showhide"<?php echo settingValue('how_showhide')?'checked':'';?>>
		                                    <label for="how_showhide" class="checktoggle">checkbox</label>
                                		</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="how_title"  value="<?php echo settingValue('how_title'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="how_content"  value="<?php echo settingValue('how_content'); ?>">
								</div>
								
								<div class="form-group">
									<h6 class="form-heads mb-0">Step 1</h6>
								</div>
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="how_title_1" value="<?php echo settingValue('how_title_1'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="how_content_1" value="<?php echo settingValue('how_content_1'); ?>">
								</div>
								
									<div class="form-group">
										<p class="settings-label">Image</p>
										 <input class="form-control" type="file"  name="how_title_img_1" id="upload_image">
										 <?php if(!empty(settingValue('how_title_img_1'))) { ?>
											<div class="upload-images ">
												<img class="thumbnail m-b-0" src="<?php echo base_url() . settingValue('how_title_img_1'); ?>">
											</div>
										<?php } else { ?>
											<img class="thumbnail m-b-0" src="<?php echo base_url() .'assets/img/icon-1.png'; ?>">
									<?php } ?>
									</div>
								
								<div class="form-group">
									<h6 class="form-heads mb-0">Step 2</h6>
								</div>
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="how_title_2" value="<?php echo settingValue('how_title_2'); ?>"
									>
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="how_content_2" value="<?php echo settingValue('how_content_2'); ?>">
								</div>
								<div class="form-group">
									<p class="settings-label">Image</p>
									 <input class="form-control" type="file"  name="how_title_img_2" id="upload_image">
									 <?php if(!empty(settingValue('how_title_img_2'))) { ?>
										<div class="upload-images ">
											<img class="thumbnail m-b-0" src="<?php echo base_url() . settingValue('how_title_img_2'); ?>">
										</div>
									<?php } else { ?>
											<img class="thumbnail m-b-0" src="<?php echo base_url() .'assets/img/icon-2.png'; ?>">
									<?php } ?>
								</div>
								<div class="form-group">
									<h6 class="form-heads mb-0">Step 3</h6>
								</div>
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="how_title_3" value="<?php echo settingValue('how_title_3'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="how_content_3" value="<?php echo settingValue('how_content_3'); ?>">
								</div>
								<div class="form-group">
									 <input class="form-control" type="file"  name="how_title_img_3" id="upload_image">
									<?php if(!empty(settingValue('how_title_img_3'))) { ?>
									<div class="upload-images ">
										<img class="thumbnail m-b-0" src="<?php echo (base_url() . settingValue('how_title_img_3'))?base_url() . settingValue('how_title_img_3'):base_url() .'assets/img/icon-3.png'; ?>">
									</div>
								<?php } else { ?>
									<div class="upload-images ">
										<img class="thumbnail m-b-0" src="<?php echo base_url() .'assets/img/icon-3.png'; ?>">
									</div>
								<?php } ?>
								</div>
								<div class="form-group">
									<h6 class="form-heads mb-0">Step 4</h6>
								</div>
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="how_title_4" value="<?php echo settingValue('how_title_4'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="how_content_4" value="<?php echo settingValue('how_content_4'); ?>">
								</div>
								<div class="form-group">
									 <input class="form-control" type="file"  name="how_title_img_4" id="upload_image">
									<?php if(!empty(settingValue('how_title_img_4'))) { ?>
									<div class="upload-images ">
										<img class="thumbnail m-b-0" src="<?php echo (base_url() . settingValue('how_title_img_4'))?base_url() . settingValue('how_title_img_4'):base_url() .'assets/img/icon-3.png'; ?>">
									</div>
								<?php } else { ?>
									<div class="upload-images ">
										<img class="thumbnail m-b-0" src="<?php echo base_url() .'assets/img/icon-3.png'; ?>">
									</div>
								<?php } ?>
								</div>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
							</div>
						</div>
					</form>
					<form class="form-horizontal d-none" id="download_sec" action="<?php echo base_url('admin/settings/download_sec'); ?>" method="POST" enctype="multipart/form-data" >
						<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
						<div class="card">
							<div class="card-header">
								<div class="card-heads">
									<h4 class="card-title">Download Section</h4>
									<div class="col-auto">
										<div class="status-toggle mr-3">
		                                    <input  id="download_showhide" class="check" type="checkbox" name="download_showhide"<?php echo settingValue('download_showhide')?'checked':'';?>>
		                                    <label for="download_showhide" class="checktoggle">checkbox</label>
                                		</div>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group">
									<label>Title</label>
									<input type="text" class="form-control" name="download_title"  value="<?php echo settingValue('download_title'); ?>">
								</div>
								<div class="form-group">
									<label>Content</label>
									<input type="text" class="form-control" name="download_content"  value="<?php echo settingValue('download_content'); ?>">
								</div>
								<div class="row">
									<div class="col-lg-6 col-12">
										<div class="form-group">
											<p class="settings-label">Google Play Store</p>
												<input class="form-control" type="file"  name="app_store_img" id="upload_image">
											<?php if(!empty(settingValue('app_store_img'))) { ?>
											<div class="upload-images ">
												<img class="thumbnail m-b-0" src="<?php echo base_url() . settingValue('app_store_img'); ?>">
												<a href="javascript:void(0);" class="btn-icon logo-hide-btn">
													<i class="fa fa-times"></i>
												</a>
											</div>
										<?php } else { ?>
											<img class="thumbnail m-b-0" src="<?php echo base_url() . 'assets/img/gp-02.jpg'; ?>">
										<?php } ?>
										</div>
									</div>
									<div class="col-lg-6 col-12">
										<div class="form-group">
											<label>App Link</label>
											<input type="text" class="form-control" name="app_store_link"  value="<?php echo settingValue('app_store_link'); ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-12">
										<div class="form-group">
											<p class="settings-label">App Store (iOs)</p>
												<input class="form-control" type="file"  name="play_store_img" id="upload_image">
											<?php if(!empty(settingValue('play_store_img'))) { ?>
											<div class="upload-images ">
												<img class="thumbnail m-b-0" src="<?php echo base_url() . settingValue('play_store_img'); ?>">
												<a href="javascript:void(0);" class="btn-icon logo-hide-btn">
													<i class="fa fa-times"></i>
												</a>
											</div>
											<?php } else { ?>
													<img class="thumbnail m-b-0" src="<?php echo base_url() . 'assets/img/gp-01.jpg'; ?>">
											<?php } ?>
										</div>
									</div>
									<div class="col-lg-6 col-12">
										<div class="form-group">
											<label>App Link</label>
											<input type="text" class="form-control" name="play_store_link"  value="<?php echo settingValue('play_store_link'); ?>">
										</div>
									</div>
								</div>
								<div class="form-groupbtn">
									<button name="form_submit" type="submit" class="btn btn-primary" value="true">Update</button>
									<a href="<?php echo base_url(); ?>admin/pages"  class="btn btn-cancel">Back</a>
								</div>
							</div>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>