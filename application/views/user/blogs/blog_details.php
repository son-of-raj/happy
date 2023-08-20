<div class="content container">			
    <div class="row justify-content-center">			
        <div class="col-lg-10 col-xl-9">			
            <!-- Blog Details-->
            <div class="blog-view">
                <div class="blog-single-post">                       
                    <div class="blog-image">
                        <?php if ($posts[0]['image_default'] != '') { ?>
                            <a href="javascript:void(0)">
                                <img class="img-fluid" src="<?php echo base_url().$posts[0]['image_default']; ?>" alt="posts Image">
                            </a>
                        <?php }?>    
                    </div>
                    <?php //echo '<pre>'; print_r($posts); exit; ?>
                    <h3 class="blog-title"><?php echo $posts[0]['title']; ?></h3>
                    <div class="blog-info">
                        <div class="post-list">
                            <ul>
                                <li>
                                    <div class="post-author1">
                                        <a href="#"><img src="<?php echo  $base_url."/".$posts[0]['profile_img']; ?>" alt="<?php echo $posts[0]['full_name']; ?>"> <span>by <?php echo $posts[0]['full_name']; ?> </span></a>
                                    </div>
                                </li>
                                <li><i class="feather-clock"></i><?php echo date('M d,Y',strtotime($posts[0]['createdAt'])); ?></li>
                                <li><i class="feather-grid"></i> <?php echo $posts[0]['cat_name']; ?> </li>
                            </ul>
                        </div>
                    </div>
                    <div class="blog-content p-0">
                        <?php echo $posts[0]['content']; ?>
					</div>
                </div>
                
                <!-- About Author -->
                <div class="card author-widget clearfix d-none">
                    <div class="card-header">
                        <h4 class="card-title">About Author</h4>
                    </div>
                    <div class="card-body">
                        <div class="about-author">
                            <div class="about-author-img">
                                <div class="author-img-wrap">
                                    <a href="#"><img class="img-fluid rounded-circle" alt="" src="<?php echo  $base_url."/".$posts[0]['profile_img']; ?>"></a>
                                </div>
                            </div>
                            <div class="author-details">
                                <a href="javascript:void(0);" class="blog-author-name"><?php echo $posts[0]['full_name']; ?> </a>
							</div>
                        </div>
                    </div>
                </div>
                <!-- /About Author -->

                <?php if(!empty($this->session->userdata('id'))) { ?>
                    <!-- Blog Comments -->
                    <div class="card author-widget clearfix">
                        <div class="card-header">
                            <h4 class="card-title">Blog Comments</h4>
                        </div>
                        <form method="post" enctype="multipart/form-data" autocomplete="off" id="blog_comments" action="<?php echo base_url() ?>blog-comments">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="post_id" value="<?php echo $posts[0]['id']; ?>">
                            <div class="card-body">
                                <div class="author-img-wrap form-group">
                                    <textarea class='form-control content-textarea' id='blog-comments' name='blog_comments' required></textarea>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn" type="submit" id="submit_blog_comments" name="form_submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit']; ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /Blog Comments -->

                    <!-- Blog Comments List -->
                    <?php if(!empty($comments)) { ?>
                        <div class="card blog-comments clearfix">
                            <div class="card-header">
                                <h4 class="card-title">Comments</h4>
                            </div>
                            <?php foreach ($comments as $comment): ?>
                                <div class="card-body pb-0">
                                    <ul class="comments-list">
                                        <li>
                                            <div class="comment">
                                                <div class="comment-author">
                                                    <img class="img-fluid avatar" alt="" src="<?php echo base_url().'assets/img/user.jpg'; ?>">
                                                </div>
                                                <div class="comment-block">
                                                    <span class="comment-by">
                                                        <a href="javascript:void(0);" class="blog-author-name"><?php echo $posts[0]['full_name']; ?> 
                                                        </a>
                                                    </span>
                                                    <p><?php echo $comment['comment']; ?></p>
                                                    <p class="blog-date"><?php echo $comment['created_at']; ?></p>
                                                    <a href="#" class="btn btn-danger delete_comments" id="delete_comments" data-id="<?php echo $comment['id'] ?>">
                                                        <i class="far fa-trash-alt"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            <?php endforeach ?>
                            
                        </div>
                    <?php } 
                } else { ?>
                   <!-- Post your Comments -->
                    <div class="card author-widget clearfix">
                        <div class="card-header">
                            <h4 class="card-title">Post your Comments</h4>
                        </div>
                        <div class="card-body">
                            <div class="submit-section">
                                    <a href="javascript:void(0);" class="btn btn-primary submit-btn-sm" data-bs-toggle="modal" data-bs-target="#tab_login_modal">Signin for Comments</a>
                                </div>
                        </div>
                    </div>
                    <!-- /Post your Comments -->
                <?php } ?>
                <!-- /Blog Comments List -->
            </div>
            <!-- /Blog Details-->
        </div>
    </div>
</div>