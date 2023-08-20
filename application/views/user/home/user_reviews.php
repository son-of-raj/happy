<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/user_sidemenu');?>
			<div class="col-xl-9 col-md-8">
					<div class="card review-card">
					<div class="card-body">
					<?php if(!empty($reviews)) { 

						foreach ($reviews as $review_list) {
							$datef = explode(' ', $review_list["created"]);
                        	$date = date(settingValue('date_format'), strtotime($datef[0]));
						 if(settingValue('time_format') == '12 Hours') {
                            $time = date('G:ia', strtotime($datef[1]));
                        } elseif(settingValue('time_format') == '24 Hours') {
                           $time = date('H:i:s', strtotime($datef[1]));
                        } else {
                            $time = date('G:ia', strtotime($datef[1]));
                        }
                        
                        $wall_time = $date.' '.$time;
						$avg_rating=round($review_list['rating'],1);  
						$service_img='';
						 $service_image=$this->db->where('service_id',$review_list['service_id'])->get('services_image')->row_array();
						  if(!empty($service_image['service_image'])){
						  	 $service_img=$service_image['service_image'];
						  	}else{
						  		$service_img="";
						  	}
						 
						  
					?>
						<div class="review-list">
							<div class="review-img"> 
								<?php if(!empty($service_img)) { ?>
									<img class="rounded img-fluid" src="<?php echo base_url().$service_img;?>" alt="">
									
									<?php } else {?>
									 <img class="avatar-xs rounded-circle" src="<?php echo base_url();?>assets/img/user.jpg" alt="">  
									<?php } ?>
								
							</div>
							<div class="review-info">
								<h5><a href=""><?php echo $review_list['service_title']?></a></h5>
								<div class="review-user">
									<?php if($review_list['profile_img'] == '') { ?>
										<img class="avatar-xs rounded-circle me-2" src="<?php echo base_url();?>assets/img/user.jpg" alt="">  
									<?php } else {?>
										<img class="avatar-xs rounded-circle" src="<?php echo base_url().$review_list['profile_img']?>" alt=""> 
									<?php } ?>
									<?php echo $review_list['name']?>
								</div>
								<div class="review-date"><?php echo  $wall_time;?></div>
								<p><?php echo $review_list['review']?></p>
							</div>
							<div class="review-count">
								<div class="rating">
									<?php 
													for($x=1;$x<=$avg_rating;$x++) {
														echo '<i class="fas fa-star filled"></i>';
													}
													if (strpos($avg_rating,'.')) {
														echo '<i class="fas fa-star"></i>';
														$x++;
													}
													while ($x<=5) {
														echo '<i class="fas fa-star"></i>';
														$x++;
													}
													?>
													<span class="d-inline-block average-rating">(<?php echo $avg_rating?>)</span>
								</div>
							</div>
						</div>
					
						<?php } } else { ?>
						<span><?php echo (!empty($user_language[$user_selected]['lg_No_reviews'])) ? $user_language[$user_selected]['lg_No_reviews'] : $default_language['en']['lg_No_reviews']; ?></span>
						<?php } ?>
					</div>
					</div>
			</div>
		</div>
	</div>
</div>