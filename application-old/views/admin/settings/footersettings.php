<?php
$category= $this->db->query("select * from footer_submenu WHERE widget_name = 'Categories-Widget'");
$category_result = $category->row_array();

$link= $this->db->query("select * from footer_submenu WHERE widget_name = 'Link-Widget'");
$link_result = $link->row_array();

$contact= $this->db->query("select * from footer_submenu WHERE widget_name = 'contact-widget'");
$contact_result = $contact->row_array();

$social= $this->db->query("select * from footer_submenu WHERE widget_name = 'social-widget'");
$social_result = $social->row_array();

$copyright= $this->db->query("select * from footer_submenu WHERE widget_name = 'copyright-widget'");
$copyright_result = $copyright->row_array();

?>

		<div class="page-wrapper">
			<div class="content container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<!-- Page Header -->
						<div class="page-header">
							<div class="row">
								<div class="col-12">
									<h3 class="page-title">Footer Settings</h3>
								</div>
							</div>
						</div>
						<!-- /Page Header -->
						
						<div class="row">
							<div class=" col-lg-6 col-sm-12 col-12">
								<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/Footer_submenu/category_widget'); ?>"  method="POST" enctype="multipart/form-data" >
							 	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="card">
									<div class="card-header">
										<div class="card-heads">
											<h4 class="card-title">Categories Widget</h4>
											<div>
												<div class="status-toggle">
												<input  id="categories_showhide" class="check" type="checkbox" name="categories_showhide" <?php echo $category_result['widget_showhide']==1?'checked':'';?>>
			                                    <label for="categories_showhide" class="checktoggle">checkbox</label>
                                				</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" name="category_title" id="category_title" value="<?php echo $category_result['page_title']; ?>">
										</div>
										<div class="form-group cate" id="">
										<div class="form-group">
											<label class="control-label">Category-view</label>
												<select class="form-control" name="category_view" id="category_view" >
													<option <?php if($category_result['category_view'] == 'Name') { echo 'selected'; } ?>>Name</option>
													<option <?php if($category_result['category_view'] == 'Orderby') { echo 'selected'; } ?>>Orderby(ASC)</option>
													<option <?php if($category_result['category_view'] == 'Popular category') { echo 'selected'; } ?>>Popular category</option>
													<option <?php if($category_result['category_view'] == 'Recent category') { echo 'selected'; } ?>>Recent category(Last 7 days)</option>
												</select>
										</div>
										<div class="form-group sub_menu">
											<label class="control-label">Category Count</label>
												<input type="text" class="form-control" name="category_count" attr="Category-count" id="category_count" value="<?php echo $category_result['category_count']; ?>">
										</div>
										</div>
										<?php if($this->session->userdata('role') == 1) { ?>
											<div class="form-groupbtn">
												<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
											</div>
										<?php } ?>
									</div>
								</div>
							</form>
								<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/Footer_submenu/contact_widget'); ?>"  method="POST" enctype="multipart/form-data" >
							 	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="card">
									<div class="card-header">
										<div class="card-heads">
											<h4 class="card-title">Contact Widget</h4>
											<div>
												<div class="status-toggle">
												<input  id="contact_showhide" class="check" type="checkbox" name="contact_showhide"<?php echo $contact_result['widget_showhide']==1?'checked':'';?>>
			                                    <label for="contact_showhide" class="checktoggle">checkbox</label>
                                				</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											
											<label>Title</label>
											<input type="text" class="form-control" name="contact_title" value="<?php echo $contact_result['page_title']; ?>">
										</div>
										<div class="form-group">
											<label>Address</label>
											<input type="text" class="form-control" name="address" attr="address" id="address" value="<?php echo $contact_result['address']; ?>">
										</div>
										<div class="form-group">
											<label>Phone</label>
											<input type="text" class="form-control" name="phone" attr="phone" id="phone" value="<?php echo $contact_result['phone']; ?>">
										</div>
										<div class="form-group">
											<label>Email</label>
											<input type="text" class="form-control" name="email" attr="email" id="email" value="<?php echo $contact_result['email']; ?>">
										</div>
										<?php if($this->session->userdata('role') == 1) { ?>
											<div class="form-groupbtn">
												<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
											</div>
										<?php } ?>
									</div>
								</div>
							</form>
							<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/Footer_submenu/copyright_widget'); ?>"  method="POST" enctype="multipart/form-data" >
							 	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="card">
									<div class="card-header">
										<div class="card-heads">
											<h4 class="card-title">Copyright</h4>
											<div>
												<div class="status-toggle">
												 <input  id="copyright_showhide" class="check" type="checkbox" name="copyright_showhide" <?php echo $copyright_result['widget_showhide']==1?'checked':'';?>>
			                                    <label for="copyright_showhide" class="checktoggle">checkbox</label>
                                				</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
							                                <label>Page Content</label>
														    <textarea class='form-control content-textarea ckeditor' id='ck_editor_textarea_id2' rows='6' name='copyright_title'>
														    	<?php echo $copyright_result['page_desc']; ?>
														    </textarea>
														    <?php echo display_ckeditor($ckeditor_editor3); ?>
							                              </div>
										<div class="form-group">
											<h6 class="form-heads mb-0">Links</h6>
										</div>
										<div class="form-group">
											<label class="form-head mb-0">Footer bottom links<span>( Max 3 only )</span></label>
										</div>
										<div class="settingset">
										<?php if(!empty($copyright_result['link'])&& $copyright_result['link'] != 'null') {

										$linked = json_decode($copyright_result['link']); 
											$i=1;
											foreach($linked as $label => $link) { ?>
												<div class="form-group links-conts copyright_content" id="link1_<?php echo $link->id; ?>">
													<div class="row align-items-center">
														<div class="col-lg-3 col-12">
															<input type="text" class="form-control" placeholder="Label" name="label1[]" value="<?php echo $link->name; ?>">
														</div>
														<div class="col-lg-8 col-12">
															<input type="text" class="form-control" placeholder="Link with http:// Or https://" name="link1[]" value="<?php echo ($link->url)?$link->url:base_url(); ?>">
														</div>
															<div class="col-lg-1 col-12">
																<a href="#" class="btn btn-sm bg-danger-light delete_copyright" data-id="<?php echo $link->id; ?>">
																	<i class="far fa-trash-alt "></i> 
																</a>
															</div>
														</div>
													</div>
											<?php $i++;} } else { ?>
													<div class="form-group links-conts copyright_content" id="link1">
													<div class="row align-items-center">
														<div class="col-lg-3 col-12">
															<input type="text" class="form-control" placeholder="Label" name="label1[]" value="Privacy">
														</div>
														<div class="col-lg-8 col-12">
															<input type="text" class="form-control" placeholder="Link with http:// Or https://" name="link1[]" value="<?php echo base_url(); ?>privacy">
														</div>
															<div class="col-lg-1 col-12">
																<a href="#" class="btn btn-sm bg-danger-light delete_copyright" data-id="1">
																	<i class="far fa-trash-alt "></i> 
																</a>
															</div>
														</div>
													</div>
													<div class="form-group links-conts copyright_content" id="link1">
													<div class="row align-items-center">
														<div class="col-lg-3 col-12">
															<input type="text" class="form-control" placeholder="Label" name="label1[]" value="Terms & Conditions">
														</div>
														<div class="col-lg-8 col-12">
															<input type="text" class="form-control" placeholder="Link with http:// Or https://" name="link1[]" value="<?php echo base_url(); ?>terms-conditions">
														</div>
															<div class="col-lg-1 col-12">
																<a href="#" class="btn btn-sm bg-danger-light delete_copyright" data-id="2">
																	<i class="far fa-trash-alt "></i> 
																</a>
															</div>
														</div>
													</div>
											<?php } ?>
										</div>
										 <?php if(!empty($linked) && is_countable($linked) && count($linked) < 3) { ?>
											<div class="form-group">
												<a class="btn  btn-success addnewlinks"><i class="fa fa-plus mr-1"></i>Add New</a>
											</div>
										<?php } ?>
										<?php if($this->session->userdata('role') == 1) { ?>
											<div class="form-groupbtn">
												<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
											</div>
										<?php } ?>
									</div>
								</div>
							</form>
							</div>
							<div class=" col-lg-6 col-sm-12 col-12">
							<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/Footer_submenu/link_widget'); ?>"  method="POST" enctype="multipart/form-data" >
							 	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="card">
									<div class="card-header">
										<div class="card-heads">
											<h4 class="card-title">Links Widget</h4>
											<div>
												<div class="status-toggle">
												 <input  id="link_showhide" class="check" type="checkbox" name="link_showhide" <?php echo $link_result['widget_showhide']==1?'checked':'';?>>
			                                    <label for="link_showhide" class="checktoggle">checkbox</label>
                                				</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" placeholder="" name="link_title" value="<?php echo $link_result['page_title']; ?>">
										</div>
										<div class="form-group">
											<label class="form-head mb-0">Links<span>( Max 6 only )</span></label>
										</div>
										<div class="links-forms">
										 <?php if(!empty($link_result['link'])&& $link_result['link'] != 'null') {
										 $links = json_decode($link_result['link']); 
										foreach($links as $key => $link) { ?>
											<div class="form-group links-cont" id="link_<?php echo $link->id; ?>">
												<div class="row align-items-center">
													<div class="col-lg-3 col-12">
														<input type="text" class="form-control" name="label[]" attr="label" id="label"  value="<?php echo $link->label; ?>">
													</div>
													<div class="col-lg-8 col-12">
														<input type="text" class="form-control" name="link[]" attr="link" id="link" value="<?php echo ($link->link)?$link->link:base_url(); ?>">
													</div>
													<div class="col-lg-1 col-12">
														<a href="#" class="btn btn-sm bg-danger-light delete_links" data-id="<?php echo $link->id; ?>">
															<i class="far fa-trash-alt "></i> 
														</a>
													</div>
												</div>
											</div>
										<?php } }  else { ?>
											<div class="form-group links-cont">
											    <div class="row align-items-center">
											        <div class="col-lg-3 col-12">
											            <input type="text" class="form-control" name="label[]" id="label" placeholder="Title" value="About Us">
											        </div>
											        <div class="col-lg-8 col-12">
											            <input type="text" class="form-control" name="link[]" id="link" placeholder="Links" value="<?php echo base_url();?>about-us">
											        </div>
											        <div class="col-lg-1 col-12">
											            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
											                <i class="far fa-trash-alt "></i> 
											            </a>
											        </div>
											    </div>
											    <div class="row align-items-center mt-4">
											        <div class="col-lg-3 col-12">
											            <input type="text" class="form-control" name="label[]" id="label" placeholder="Title" value="Contact Us">
											        </div>
											        <div class="col-lg-8 col-12">
											            <input type="text" class="form-control" name="link[]" id="link" placeholder="Links" value="<?php echo base_url();?>contact">
											        </div>
											        <div class="col-lg-1 col-12">
											            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
											                <i class="far fa-trash-alt "></i> 
											            </a>
											        </div>
											    </div>
											    <div class="row align-items-center mt-4">
											        <div class="col-lg-3 col-12">
											            <input type="text" class="form-control" name="label[]" id="label" placeholder="Title" value="Faq">
											        </div>
											        <div class="col-lg-8 col-12">
											            <input type="text" class="form-control" name="link[]" id="link" placeholder="Links" value="<?php echo base_url();?>faq">
											        </div>
											        <div class="col-lg-1 col-12">
											            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
											                <i class="far fa-trash-alt "></i> 
											            </a>
											        </div>
											    </div>	  
											</div>
										<?php } ?>
										</div>
										<?php if(!empty($links) && count($links) < 6) { ?>
											<div class="form-group">
												<a class="btn  btn-success addlinknew"><i class="fas fa-plus mr-1"></i>Add New</a>
											</div>
										<?php } else{ ?>
											<div class="form-group">
												<a class="btn  btn-success addlinknew"><i class="fas fa-plus mr-1"></i>Add New</a>
											</div>
										<?php } ?>
										<?php if($this->session->userdata('role') == 1) { ?>
											<div class="form-groupbtn">
												<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
											</div>
										<?php } ?>
									</div>
								</div>
							</form>
							<form class="form-horizontal" id="banner_settings" action="<?php echo base_url('admin/Footer_submenu/social_widget'); ?>"  method="POST" enctype="multipart/form-data" >
							 	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								<div class="card">
									<div class="card-header">
										<div class="card-heads">
											<h4 class="card-title">Social Widget</h4>
											<div>
												<div class="status-toggle">
												 <input  id="social_showhide" class="check" type="checkbox" name="social_showhide" <?php echo ($social_result['widget_showhide'] == 1)?'checked':'';?>>
			                                    <label for="social_showhide" class="checktoggle">checkbox</label>
                                				</div>
											</div>
										</div>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label>Title</label>
											<input type="text" class="form-control" name="socail_title" value="<?php echo $social_result['page_title']; ?>"/>
										</div>
										<div class="setings" id="link_<?php echo $i; ?>">
										<?php $social = json_decode($social_result['followus_link']);  ?>
									 
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-facebook-f"></i>
																	</span>
																	
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="facebook" attr="facebook" id="facebook" value="<?php echo ($social->facebook)?$social->facebook:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-twitter me-2"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="twitter" attr="twitter" id="twitter" value="<?php echo ($social->twitter)?$social->twitter:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-youtube me-2"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="youtube" attr="youtube" id="youtube" value="<?php echo ($social->youtube)?$social->youtube:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-linkedin me-2"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="linkedin" attr="linkedin" id="linkedin" value="<?php echo ($social->linkedin)?$social->linkedin:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-github me-2"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="github" attr="github" id="github" value="<?php echo ($social->github)?$social->github:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-instagram"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="instagram" attr="instagram" id="instagram" value="<?php echo ($social->instagram)?$social->instagram:''; ?>">
													</div>
												</div>
											</div>
											<div class="form-group countset">
												<div class="row align-items-center">
													<div class="col-lg-2 col-12">
														<div class="socail-links-set">
															<ul>
																<li class="main-drop">
																	<span class="social-icon">
																		<i class="fab fa-google"></i>
																	</span>
																</li>
															</ul>
														</div>
													</div>
													<div class="col-lg-9 col-12">
														<input type="text" class="form-control" name="gplus" attr="gplus" id="gplus" value="<?php echo ($social->gplus)?$social->gplus:''; ?>">
													</div>
												</div>
											</div>
										</div>
										<?php if($this->session->userdata('role') == 1) { ?>
											<div class="form-groupbtn">
												<button name="form_submit" type="submit" class="btn btn-primary" value="true">Save</button>
											</div>
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