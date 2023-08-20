<div class="page-wrapper">
			<div class="content container-fluid">
					
                <!-- Blog List -->
                <div class="row">
                    <div class="col-md-9">
                        <ul class="list-links mb-4">
                            <li class="<?php echo ($type == 1)?"active":""; ?>"><a href="<?php echo $base_url; ?>blogs">Active Blog</a></li>
                            <li class="<?php echo ($type == 2)?"active":""; ?>"><a href="<?php echo $base_url; ?>blogs-pending">Pending Blog</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <a href="<?php echo $base_url; ?>add-blog" class="btn btn-primary btn-blog mb-3" ><i class="feather-plus-circle me-1"></i> Add New</a>
                    </div>
                </div>
                
                <div class="row">
                    <?php if($posts){
                        foreach($posts as $post){ ?>
                            <!-- Blog Post -->
                            <div class="col-md-6 col-xl-4 col-sm-12 page_contant">
                                <div class="blog grid-blog flex-fill">
                                    <div class="blog-image">
                                        <?php if ($post['image_default'] != '' && (@getimagesize(base_url().$post['image_default']))) { ?>
                                           <a href="<?php echo $base_url; ?>blog-details/<?php echo $post['id']; ?>"><img class="img-fluid" src="<?php echo $post['image_default']; ?>" alt="Post Image"></a>
                                            <?php } else { ?>
                                            <a href="<?php echo $base_url; ?>blog-details/<?php echo $post['id']; ?>">
                                                <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                            </a>
                                            <?php } ?>
                                        
                                       <!--  <a href="<?php echo $base_url; ?>blog-details/<?php echo $post['id']; ?>"><img class="img-fluid" src="<?php echo $post['image_default']; ?>" alt="Post Image"></a> -->
                                        <div class="blog-views">
                                            <i class="feather-eye me-1"></i> <?php echo $post['total_views']; ?>
                                        </div>
                                        <div class="blog-catagories">
                                            <p><?php echo $post['cat_name']; ?></p>
                                        </div>
                                    </div>
                                    <div class="blog-content">
                                        <ul class="entry-meta meta-item">
                                            <li>
                                                <div class="post-author">
                                                    <a href="<?php echo $base_url; ?>admin-profile">
                                                    <?php if ($post['profile_img'] != '') { ?>
                                                        <img src="<?php echo $post['profile_img']; ?>" alt="Post Author"> 
                                                    <?php } else { ?>
                                                            <img src="<?php echo base_url(); ?>assets/img/user-placeholder.jpg">
                                                    <?php } ?>
                                                        <span>
                                                            <span class="post-title"><?php echo $post['full_name']; ?></span>
                                                            <span class="post-date"><i class="far fa-clock"></i>  <?php echo date('d-M-Y',strtotime($post['createdAt'])); ?></span>
                                                        </span>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                        <h3 class="blog-title"><a href="<?php echo $base_url; ?>blog-details/<?php echo $post['id']; ?>"><?php echo $post['title']; ?> </a></h3>
                                        <p><?php echo substr($post['content'], 0, 180);echo (strlen($post['content'])>180)?".........":""; ?></p>
                                    </div>
									<div class="edit-options">
										<div class= "edit-delete-btn">
											<a href="<?php echo $base_url; ?>edit-blog/<?php echo $post['id']; ?>" class="text-success"  ><i class="feather-edit-3 me-1"></i> Edit</a>
											<a href="#" data-id="<?php echo $post['id']; ?>" class="text-danger delete_blog"><i class="feather-trash-2 me-1"></i> Delete</a>
										</div>
										<div class="text-end inactive-style">
											<a href="javascript:void(0);" class="<?php echo ($type == 1)?"text-danger":"text-success"; ?> <?php echo ($type == 1)?"blog_inactive":"blog_active"; ?> "  data-id="<?php echo $post['id']; ?>" ><i class="feather-eye-off me-1"></i> <?php echo ($type == 1)?"Inactive":"Active"; ?></a>
										</div>
									</div>
                                </div>										
                            </div>
                            <!-- /Blog Post -->
                        <?php } 
                    } ?>
                    
            
                    
                </div>
                <!-- Pagination -->
					<div class="pagination ">
						
					</div>
					<!-- /Pagination -->
                    <!-- Modal -->
		<div class="modal fade contentmodal" id="deleteModal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content doctor-profile">
					<div class="modal-header pb-0 border-bottom-0  justify-content-end">
						<button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i class="feather-x-circle"></i></button>
					</div>
					<div class="modal-body">
						<div class="delete-wrap text-center">
							<div class="del-icon"><i class="feather-x-circle"></i></div>
							<h2>Sure you want to delete</h2>
							<div class="submit-section">
								<a href="<?php echo $base_url; ?>blogs" class="btn btn-success me-2">Yes</a>
								<a href="#" class="btn btn-danger" data-bs-dismiss="modal">No</a>
							</div>								
						</div>
					</div>
				</div>
			</div> 
		</div>
		<!-- /Modal -->
             </div>
		</div> 