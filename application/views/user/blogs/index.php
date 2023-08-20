<div class="page-wrapper">
			<div class="content container">
					
                <!-- Blog List -->
                
                
                <div class="row">
                    <?php if($posts){
                        foreach($posts as $post){ ?>
                            <!-- Blog Post -->
                            <div class="col-md-6 col-xl-4 col-sm-12 contant">
                                    <div class="blog grid-blog flex-fill">
                                        <div class="blog-image">
                                        	<?php if ($post['image_default'] != '' && (@getimagesize(base_url().$post['image_default']))) { ?>
                                             <a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>"><img class="img-fluid" src="<?php echo $post['image_default']; ?>" alt="Post Image"></a>
											<?php } else { ?>
											<a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>">
												<img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
											</a>
											<?php } ?>           
                                        </div>
                                        <div class="blog-content">
											<h3 class="blog-title"><a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>"><?php echo $post['title']; ?></a></h3>
                                            <p><?php echo substr($post['content'], 0, 180);echo (strlen($post['content'])>180)?".........":""; ?></p> 
											<div class="blog-read d-flex justify-content-between align-items-center">
												<div class= "blog-date">
													<p><i class="far fa-calendar mr-2"></i><?php echo date('d-M-Y',strtotime($post['createdAt'])); ?></p>
												</div>
												<div class="blog-read-more">
													<a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>"> Read more<i class="fas fa-arrow-right ml-2"></i></a>
												</div>
											</div>
										</div>
                                    </div>										
                                </div>
                            <!-- /Blog Post -->
                        <?php } 
                    } ?>
                    
            
                    
                </div>
				<script src="<?php echo base_url();?>assets/js/user_pagination.js"></script>
                <!-- Pagination -->
					<div class="pagination"> 
						
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