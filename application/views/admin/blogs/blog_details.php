
<div class="page-wrapper">
			<div class="content container-fluid">			
                <div class="row justify-content-center">			
                    <div class="col-lg-10 col-xl-9">			
                
                        <!-- Blog Details-->
                        <div class="blog-view">
                            <div class="blog-single-post">
                                <a href="<?php echo $base_url; ?>blogs" class="back-btn"><i class="feather-chevron-left"></i> Back</a>
                                <div class="blog-image">
                                    <a href="javascript:void(0);"><img alt="" src="assets/img/category/blog-detail.png" class="img-fluid"></a>
                                </div>
                                <h3 class="blog-title"><?php echo $posts[0]['title']; ?></h3>
                                <div class="blog-info">
                                    <div class="post-list">
                                        <ul>
                                            <li>
                                                <div class="post-author">
                                                    <a href="<?php echo $base_url; ?>admin-profile"><img src="<?php echo  $base_url."/".$posts[0]['profile_img']; ?>" alt="<?php echo $posts[0]['full_name']; ?>"> <span>by <?php echo $posts[0]['full_name']; ?> </span></a>
                                                </div>
                                            </li>
                                            <li><i class="feather-clock"></i><?php echo date('M d,Y',strtotime($posts[0]['createdAt'])); ?></li>
                                            <!-- <li><i class="feather-message-square"></i> 40 Comments</li> -->
                                            <li><i class="feather-grid"></i> <?php echo $posts[0]['cat_name']; ?></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="blog-content">
                                <?php echo $posts[0]['content']; ?>
                                </div>
                            </div>
                            
                            <!-- About Author -->
                            <div class="card author-widget clearfix">
                                <div class="card-header">
                                    <h4 class="card-title">About Author</h4>
                                </div>
                                <div class="card-body">
                                    <div class="about-author">
                                        <div class="about-author-img">
                                            <div class="author-img-wrap">
                                                <a href="<?php echo $base_url; ?>admin-profile"><img class="img-fluid" alt="" src="<?php echo  $base_url."/".$posts[0]['profile_img']; ?>"></a>
                                            </div>
                                        </div>
                                        <div class="author-details">
                                            <a href="<?php echo $base_url; ?>admin-profile" class="blog-author-name"><?php echo $posts[0]['full_name']; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /About Author -->
                            
                            <!-- Comments -->
                            <!--<div class="card blog-comments">
                                <div class="card-header">
                                    <h4 class="card-title">Comments (5)</h4>
                                </div>
                                <div class="card-body pb-0">
                                    <ul class="comments-list">
                                        <li>
                                            <div class="comment">
                                                <div class="comment-author">
                                                    <img class="avatar" alt="" src="assets/img/provider/aut-2.png">
                                                </div>
                                                <div class="comment-block">
                                                    <div class="comment-by">
                                                        <h5 class="blog-author-name">Michelle Fairfax <span class="blog-date"> <i class="feather-clock me-1"></i>Dec 6, 2017</span></h5>
                                                    </div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam viverra euismod odio, gravida pellentesque urna varius vitae, gravida pellentesque urna varius vitae. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>											
                                                    <a class="comment-btn" href="#">
                                                        <i class="fa fa-reply me-2"></i> Reply
                                                    </a>
                                                </div>
                                            </div>
                                            <ul class="comments-list reply">
                                                <li>
                                                    <div class="comment">
                                                        <div class="comment-author">
                                                            <img class="avatar" alt="" src="assets/img/provider/aut-3.png">
                                                        </div>
                                                        <div class="comment-block">
                                                            <div class="comment-by">
                                                                <h5 class="blog-author-name">Gina Moore <span class="blog-date"> <i class="feather-clock me-1"></i> 6 Dec 2022</span></h5>
                                                            </div>
                                                            <p>gravida pellentesque urna varius vitae. Lorem ipsum dolor sit amet, consectetur</p>
                                                            <a class="comment-btn" href="#">
                                                                <i class="fa fa-reply me-2"></i> Reply
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="comment">
                                                        <div class="comment-author">
                                                            <img class="avatar" alt="" src="assets/img/provider/aut-4.png">
                                                        </div>
                                                        <div class="comment-block">
                                                            <div class="comment-by">
                                                                <h5 class="blog-author-name">Carl Kelly <span class="blog-date"> <i class="feather-clock me-1"></i> 7 Dec 2022</span></h5>
                                                            </div>
                                                            <p> pellentesque urna varius vitae, gravida pellentesque urna  consectetur adipiscing elit. Nam viverra euismod.</p>
                                                            <a class="comment-btn" href="#">
                                                                <i class="fa fa-reply me-2"></i> Reply
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <div class="comment">
                                                <div class="comment-author">
                                                    <img class="avatar" alt="" src="assets/img/provider/aut-5.png">
                                                </div>
                                                <div class="comment-block">
                                                    <div class="comment-by">
                                                        <h5 class="blog-author-name">Elsie Gilley <span class="blog-date"> <i class="feather-clock me-1"></i> 7 Dec 2022</span></h5>
                                                    </div>
                                                    <p>sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="comment">
                                                <div class="comment-author">
                                                    <img class="avatar" alt="" src="assets/img/provider/aut-6.png">
                                                </div>
                                                <div class="comment-block">
                                                    <div class="comment-by">
                                                        <h5 class="blog-author-name">Joan Gardner <span class="blog-date"> <i class="feather-clock me-1"></i>  12 Dec 2022</span></h5>
                                                    </div>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                                    <a class="comment-btn" href="#">
                                                        <i class="fa fa-reply me-2"></i> Reply
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>-->
                            <!-- /Comments -->
                            
                            <!-- Leave Comment -->
                            <!--<div class="card new-comment clearfix">
                                <div class="card-header">
                                    <h4 class="card-title">Leave Comment</h4>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" id="floatingInput" placeholder="Enter your name">
                                            <label for="floatingInput">Name<span class="text-danger">*</span></label>
                                          </div>
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                            <label for="floatingInput">Your Email address<span class="text-danger">*</span></label>
                                          </div>
                                        <div class="form-group">
                                            <textarea rows="4" class="form-control bg-grey" placeholder="Comments"></textarea>
                                        </div>
                                        <div class="submit-section">
                                            <button class="submit-btn" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>-->
                            <!-- /Leave Comment -->
                            
                            <!--<div class="card blog-share clearfix">
                                <div class="card-header">
                                    <h4 class="card-title">Share the post</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="social-share">
                                        <li><a href="#"><i class="feather-twitter"></i></a></li>
                                        <li><a href="#"><i class="feather-facebook"></i></a></li>
                                        <li><a href="#"><i class="feather-linkedin"></i></a></li>
                                        <li><a href="#"><i class="feather-instagram"></i></a></li>
                                        <li><a href="#"><i class="feather-youtube"></i></a></li>
                                    </ul>
                                </div>
                            </div>-->
                    
                        </div>
                    </div>
                </div>
                <!-- /Blog Details-->
        
            </div>
		</div> 