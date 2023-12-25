<?php
$newuser = '';
$newuser = $_GET['uid'];

$business_hours = $this->db->where('provider_id', $service['user_id'])->get('business_hours')->row_array();
$availability_details = json_decode($business_hours['availability'], true);
$this->db->select('AVG(rating)');
$this->db->where(array('service_id' => $service['id'], 'status' => 1));
$this->db->from('rating_review');
$rating = $this->db->get()->row_array();
$avg_rating = round($rating['AVG(rating)'], 2);

$this->db->select("r.*,u.profile_img,u.name");
$this->db->from('rating_review r');
$this->db->join('users u', 'u.id = r.user_id', 'LEFT');
$this->db->where(array('r.service_id' => $service['id'], 'r.status' => 1));
$reviews = $this->db->get()->result_array();
$get_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();

$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
    }
}
$service_amount = $service['service_amount'];
if (!empty($service['user_id'])) {
    $provider_online = $this->db->where('id', $service['user_id'])->from('providers')->get()->row_array();
    $datetime1 = new DateTime();
    $datetime2 = new DateTime($provider_online['last_logout']);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->format('%a');
    $hours = $interval->format('%h');
    $minutes = $interval->format('%i');
    $seconds = $interval->format('%s');
} else {
    $days = $hours = $minutes = $seconds = 0;
}

$subcategory_name = $this->db->select('subcategory_name')->where('id',$service['subcategory'])->get('subcategories')->row()->subcategory_name;
$sub_subcategory_name = $this->db->select('sub_subcategory_name')->where('id',$service['sub_subcategory'])->get('sub_subcategories')->row()->sub_subcategory_name;


//Covid vaccine status
$covidControl = settingValue('corona_control');
$cvaccine = $get_details['covid_vaccine'];

$shplocation = $this->db->select('shop_location')->where('id',$service['shop_id'])->get('shops')->row()->shop_location;
?>

<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">
				<div class="service-view-header">

					<div class="ser-detail-title">
						<h2><?php echo ucfirst($service['service_title']); ?></span></h2>
						<div class="service-breadcrumb-menu">
							<nav aria-label="breadcrumb" class="page-breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
									<?php if($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer'){ ?>
									<li class="breadcrumb-item"><a href="<?php echo base_url()."my-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></a></li>
									<?php } else { ?>
									<li class="breadcrumb-item"><a href="<?php echo base_url()."all-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_search'])) ? $user_language[$user_selected]['lg_search'] : $default_language['en']['lg_search']; ?></a></li>
									<?php } ?>
									<li class="breadcrumb-item active" aria-current="page"><?php echo ucfirst($service['service_title']); ?></li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<?php 
				$cur_date = date("Y-m-d");
				$current_time = date("H:i");
				$this->db->where("'$cur_date' BETWEEN start_date AND end_date");
				$this->db->where("'$current_time' BETWEEN start_time AND end_time");
				$this->db->where('provider_id', $service['user_id']);
				$this->db->where('service_id', $service['id']);
				$service_offer = $this->db->get('service_offers')->row_array();
				$tagtxt = (!empty($user_language[$user_selected]['lg_Offer_Tag'])) ? $user_language[$user_selected]['lg_Offer_Tag'] : $default_language['en']['lg_Offer_Tag'];
				$tagcontent = $service_offer['offer_percentage']."% ".$tagtxt; 
				?>
				<?php if(!empty($service_offer)) { ?>
					<div class="category-lists">
						<a href="#"><?php echo $tagcontent; ?></a>
					</div>
				<?php } ?>
				<div class="ser-detail-rating">
				   <div class="star-rating">
					  <?php
						for ($x = 1; $x <= $avg_rating; $x++) {
							echo '<i class="fas fa-star filled"></i>';
						}
						if (strpos($avg_rating, '.')) {
							echo '<i class="fas fa-star"></i>';
							$x++;
						}
						while ($x <= 5) {
							echo '<i class="fas fa-star"></i>';
							$x++;
						}
						?>
					  <span>(<?php echo $avg_rating; ?>)</span>
				   </div>
					<p><i class="fas fa-location-arrow"></i> <?php echo ($service['service_location'])?ucfirst($service['service_location']):$shplocation; ?></p>
				</div>
                <div class="service-view">
                    <div class="service-images service-carousel pb-0 service-images1">
                        <div class="images-carousel owl-carousel owl-theme shopdeatils-img">
                            <?php
                            if (!empty($service_image)) {
                                for ($i = 0; $i < count($service_image); $i++) {
									if (!empty($service_image[$i]['service_image']) && (@getimagesize(base_url().$service_image[$i]['service_image']))) {
										echo'<div class="item"><img src="' . base_url() . $service_image[$i]['service_image'] . '" alt="" class="img-fluid"></div>';
									}else{
										echo'<div class="item"><img src="https://via.placeholder.com/360x220.png?text=Service%20Image" alt="" class="img-fluid"></div>';
									}
                                }
                            } else { ?>
                            	<div class="item"><img src="<?php echo base_url().settingValue('service_placeholder_image'); ?>" alt="" class="img-fluid"></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="service-details">
 
                            <div class="category-lists">
								<a href="<?php echo base_url(); ?>search/<?php echo str_replace(' ', '-', $service['category_slug']); ?>"><?php echo ucfirst($service['category_name']); ?></a>
								<?php if(!empty($subcategory_name)) { ?>
								<a title="Sub Category" href="<?php echo $subcateurl; ?>" target="_blank"><?php echo ucfirst($subcategory_name); ?></a>
								<?php } ?>
		                     </div>

                        <div class="service-view">
                        <div class="p-0">
							<div class="service-card">
								<h3><?php echo (!empty($user_language[$user_selected]['lg_Service_Details'])) ? $user_language[$user_selected]['lg_Service_Details'] : $default_language['en']['lg_Service_Details']; ?></h3>
								 <p class="mb-0"><?php echo $service['about']; ?></p>
							</div>
							<?php if (settingValue('service_offered_showhide') == 1) { ?>
							<div class="service-card">									
									<!-- Additional Service -->
									<?php 
									$addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$service['id'])->get('additional_services')->result_array();
									$addicnt = count($addi_ser); 
									$addi_tot = 0;
									?>
									<?php if ($addicnt > 0) { 
										$userId = $this->session->userdata('id');
										If (!empty($userId)) {												
											$type = $this->session->userdata('usertype');
											if ($type == 'user') {
												$user_currency = get_user_currency();
											} else if ($type == 'provider') {
												$user_currency = get_provider_currency();
											} else if ($type == 'freelancer') {
												$user_currency = get_provider_currency();
											}
											$user_currency_code = $user_currency['user_currency_code']; 

											$service_amount28 = get_gigs_currency($service['service_amount'], $service['currency_code'], $user_currency_code);
										} else {
											$user_currency_code = settings('currency');
											$service_currency_code = $service['currency_code']; 
											$service_amount28 = get_gigs_currency($service['service_amount'], $service['currency_code'], $user_currency_code);
										}
									?>
									<h5 class="card-title mt-3 d-none"><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?> : <?php echo currency_conversion($user_currency_code) . $service_amount28; ?></h5>
									<?php } ?>
									
									 <h3><?php echo (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services']; ?></h3>
                                    <div class="service-offer">
									
										<?php if ($addicnt > 0) { ?>
										
										
                                            <?php                                                
                                                foreach ($addi_ser as $a => $val) {
													$addi_tot += $val['amount']; 
													if (!empty($this->session->userdata('id'))) {
														$seramt= $val['amount'];
														$type = $this->session->userdata('usertype');
														if ($type == 'user') {
															$user_currency = get_user_currency();
														} else if ($type == 'provider') {
															$user_currency = get_provider_currency();
														} else if ($type == 'freelancer') {
															$user_currency = get_provider_currency();
														}
														$user_currency_code = $user_currency['user_currency_code'];
														$seramt = get_gigs_currency($val['amount'], $service['currency_code'], $user_currency_code);
													} else {
														$user_currency_code = settings('currency');
														$seramt = get_gigs_currency($val['amount'], $service['currency_code'], $user_currency_code);
													}
											?>
												<div class="additional-service">
													<div class="additional-content">
														<h6><?php echo $val['service_name']; ?></h6>
														<p><?php echo $val['duration']."<span>".$val['duration_in']."</span>"; ?></p>
													</div>
													<div class="additional-price">
														<?php echo currency_conversion($user_currency_code) . $seramt; ?>
													</div>
												</div>
                                                    
                                            <?php  } ?>
                                         <?php  } else {
												$norecord = (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou'];
                                                echo $norecord;
                                            }
                                            ?>
                                    </div>
									<!-- Additional Service Ends -->
									</div>
								<?php } ?>
                            <div class="service-card">
								<h3>Reviews</h3>
                                <div class="card review-box">
                                    <div class="card-body">
                                        <?php
                                        if (!empty($reviews)) {
                                            foreach ($reviews as $review) {
                                                $datetime = new DateTime($review['created']);
                                                $avg_ratings = round($review['rating'], 2);
                                                ?>
                                                <div class="review-list">
                                                    <div class="review-img">
                                                        <?php if ($review['profile_img'] == '') { ?>
                                                            <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/user.jpg" alt="">
                                                        <?php } else { ?>
                                                            <img class="rounded-circle" src="<?php echo base_url() . $review['profile_img'] ?>" alt="">
                                                        <?php } ?>
                                                    </div>
                                                    <div class="review-info">
                                                        <h5><?php echo $review['name'] ?></h5>
                                                        <div class="review-date"><?php echo $datetime->format('F d, Y H:i a'); ?></div>
                                                        <p class="mb-0"><?php echo $review['review'] ?></p>
                                                    </div>
                                                    <div class="review-count">
                                                        <div class="rating">
                                                            <?php
                                                            for ($x = 1; $x <= $avg_ratings; $x++) {
                                                                echo '<i class="fas fa-star filled"></i>';
                                                            }
                                                            if (strpos($avg_ratings, '.')) {
                                                                echo '<i class="fas fa-star"></i>';
                                                                $x++;
                                                            }
                                                            while ($x <= 5) {
                                                                echo '<i class="fas fa-star"></i>';
                                                                $x++;
                                                            }
                                                            ?>	
                                                            <span class="d-inline-block average-rating">(<?php echo $review['rating'] ?>)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <span><?php echo (!empty($user_language[$user_selected]['lg_No_reviews'])) ? $user_language[$user_selected]['lg_No_reviews'] : $default_language['en']['lg_No_reviews']; ?></span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <?php if (!empty($popular_service)) { ?>
                <h4 class="card-title service-related"><?php echo (!empty($user_language[$user_selected]['lg_Related_Services'])) ? $user_language[$user_selected]['lg_Related_Services'] : $default_language['en']['lg_Related_Services']; ?></h4>
                <div class="slider-service slider-services">
                    <div class="owl-carousel owl-theme owl-carousel sliders-related">
                        <?php 
                        foreach ($popular_service as $key => $serv) {
                            $mobile_image = explode(',', $serv['mobile_image']);
                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $serv['id']);
                            $this->db->where("status", 1);
							$this->db->order_by('is_default','DESC');
                            $image = $this->db->get()->row_array();

							$provider_details = $this->db->where('id', $serv['user_id'])->get('providers')->row_array();
                            $user_currency_code = '';
                            $userId = $this->session->userdata('id');
							
                            If (!empty($userId)) {
                                $service_amount12 = $serv['service_amount'];
                                $type = $this->session->userdata('usertype');
                                if ($type == 'user') {
                                    $user_currency = get_user_currency();
                                } else if ($type == 'provider') {
                                    $user_currency = get_provider_currency();
                                } else if ($type == 'freelancer') {
                                    $user_currency = get_provider_currency();
                                }
                                $user_currency_code = $user_currency['user_currency_code'];
                                $service_amount12 = get_gigs_currency($serv['service_amount'], $serv['currency_code'], $user_currency_code);
                            } else {
                                $user_currency_code = settings('currency');
                                $service_amount12 = get_gigs_currency($serv['service_amount'], $serv['currency_code'], $user_currency_code);
                            }
							
							$servicetitle = preg_replace('/[^\w\s]+/u',' ',$serv['service_title']);
							$servicetitle = str_replace(' ', '-', $servicetitle);
							$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
							$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($serv['id']);
							$shop_name = $this->db->get_where('shops', array('id'=>$serv['shop_id']))->row()->shop_name;
                            ?>

                            <div class="feature-shop">
                                <div class="feature-img">
                                    <a href="<?php echo $service_url; ?>">
									
										<?php if (!empty($image['service_image']) && (@getimagesize(base_url().$image['service_image']))) { ?>
											<img class="categorie-img" alt="Service Image" src="<?php echo base_url() . $image['service_image']; ?>">
										<?php } else { ?>
											<img class="categorie-img" alt="Service Image" src="https://via.placeholder.com/360x220.png?text=Service%20Image">
										<?php } ?>
                                    </a>
                                    <div class="feature-bottom">
	                                    <a href="#">
                                           <?php if ($provider_details['profile_img'] != '' && (@getimagesize(base_url().$provider_details['profile_img']))) { ?>
												<img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
											<?php } else { ?>
												<img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/user.jpg">
												
											<?php } ?>
                                        </a>
	                                    <p><a href="<?php echo base_url(); ?>search/<?php echo str_replace(' ', '-', $serv['category_slug']); ?>"><?php echo  ucfirst($serv['category_name']); ?></a></p>
	                                </div>
                                </div>
                                <div class="featute-info">
	                                <h4><a href="<?php echo $service_url; ?>"><?php echo  $serv['service_title']; ?></a></h4>
	                                <div class="star-rating">
	                                    <?php
			                            for ($x = 1; $x <= $avg_rating; $x++) {
			                                echo '<i class="fas fa-star filled"></i>';
			                            }
			                            if (strpos($avg_rating, '.')) {
			                                echo '<i class="fas fa-star"></i>';
			                                $x++;
			                            }
			                            while ($x <= 5) {
			                                echo '<i class="fas fa-star"></i>';
			                                $x++;
			                            }
			                        ?>	
									<label>(<?php echo $avg_rating; ?>)</label>
	                                </div>
									<div class="product-by">
										<span>by <a href="#"><?php echo $shop_name; ?></a></span>
									</div>
	                                <h6><?php echo currency_conversion($user_currency_code) . $service_amount12; ?></h6>
	                                <p><i class="fas fa-map-marker-alt ms-1 me-1"></i><?php echo $serv['service_location']; ?></p>
	                            </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
           <?php } ?>
            </div>
            <?php
            $user_currency_code = '';
            $userId = $this->session->userdata('id');
            $user_details = $this->db->where('id', $userId)->get('users')->row_array();
			$service['service_amount'] = $service['service_amount'];
            If (!empty($userId)) {
                $service_amount = $service['service_amount'];
                $type = $this->session->userdata('usertype');
                if ($type == 'user') {
                    $user_currency = get_user_currency();
                } else if ($type == 'provider') {
                    $user_currency = get_provider_currency();
                } else if ($type == 'freelancer') {
                    $user_currency = get_provider_currency();
                }
                $user_currency_code = $user_currency['user_currency_code']; 

                $service_amount = get_gigs_currency($service['service_amount'], $service['currency_code'], $user_currency_code);
            } else {
                $user_currency_code = settings('currency');
                $service_currency_code = $service['currency_code']; 
                $service_amount = get_gigs_currency($service['service_amount'], $service['currency_code'], $user_currency_code);
            }
			$current_time = date('H:i:s');
			$where_time = $current_time.' BETWEEN start_time AND end_time';
			$offers = $this->db->where("status",0)->where("df",0)->where("service_id",$service['id'])->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where( "'$current_time' BETWEEN start_time AND end_time",NULL, FALSE)->get("service_offers")->row_array();
			$offerPrice = '';
			
			if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0) {
				$offerPrice = $service_amount * ($offers['offer_percentage'] / 100 );
				$offerPrice = $service_amount - $offerPrice;
				$offerPrice = number_format($offerPrice,2);
			} 			
            ?>
            <div class="col-lg-4 theiaStickySidebar">
                <div class="sidebar-widget widget">
                    <div class="service-book mb-3">
                        <?php
                        $val = $this->db->select('*')->from('book_service')->where('service_id', $service['id'])->where('user_id', $this->session->userdata('id'))->order_by('id', 'DESC')->get()->row();
                        $userId = $this->session->userdata('id');
                        $usertype = $this->session->userdata('usertype');
                        $token = $this->session->userdata('chat_token');

                        if (!empty($userId)) {
                            if (!empty($usertype) && $usertype == 'user') {
                                ?>
								<?php if($covidControl == 1 && $cvaccine == 0){ ?>
									<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#corcontrolModal" id="cvaccine_modal" name="cvaccine_modal" class="btn btn-appoitment" ><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?></a>
								<?php } else if($covidControl == 1 && $cvaccine == 4){ ?>
									<a href="javascript:void(0);" class="btn btn-appoitment vaccination_status"><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?></a>
								<?php } else { ?>
								<?php if(settingValue('booking_showhide') == 1) { ?>
								<button class="btn btn-appoitment" type="button" id="go_book_service" data-id="<?php echo $service['id'] ?>" ><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?> </button>

                                <?php } }
                            }
                        } else {
                            ?>
                            <a href="javascript:void(0);" class="btn btn-appoitment" data-bs-toggle="modal" data-bs-target="#tab_login_modal"> <?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?> </a>
                        <?php } ?>
                        <?php
                        if (!empty($this->session->userdata('id'))) {
                            if ($service['user_id'] == $this->session->userdata('id')) {
                                if ($this->session->userdata('usertype') == 'provider') {
									if(empty($newuser)){
                                    ?>
                                    <a href="<?php echo base_url() . 'user/service/edit_service/' . $service['id'] ?>" class="btn btn-primary btn-lg btn-block" > <?php echo (!empty($user_language[$user_selected]['lg_Edit_Service'])) ? $user_language[$user_selected]['lg_Edit_Service'] : $default_language['en']['lg_Edit_Service']; ?> </a>
                                    <?php
									}
								}
                            }
                        }
                        ?>
                    </div>
                    <div class="amount-shop">
						<?php if($offerPrice != '') { ?>
							<p class="label-amount"><?php echo currency_conversion($user_currency_code) . $offerPrice; ?><span class="actualprice ms-2"><del><?php echo currency_conversion($user_currency_code) . $service_amount; ?></del></span></p>
							
						<?php } else { ?>
							<p class="label-amount"><?php echo currency_conversion($user_currency_code) . $service_amount; ?></p>
						<?php } ?>
                    </div>
                </div>


                <div class="card provider-widget clearfix">
                    <div class="card-body">
                        <h5 class="card-title service-title"><?php echo (!empty($user_language[$user_selected]['lg_Service_Provider'])) ? $user_language[$user_selected]['lg_Service_Provider'] : $default_language['en']['lg_Service_Provider']; ?></h5>
                        <?php
                        if (!empty($service['user_id'])) {
                            $provider = $this->db->select('*')->
                                            from('providers')->
                                            where('id', $service['user_id'])->
                                            get()->row_array();
							
							if($service['shop_id'] > 0) {
								$shopsv = $this->db->select('id,shop_name')->where('id',$service['shop_id'])->get('shops')->row_array();
								$shoptitle = preg_replace('/[^\w\s]+/u',' ',$shopsv['shop_name']);
								$shoptitle = str_replace(' ', '-', $shoptitle);
								$shoptitle = trim(preg_replace('/-+/', '-', $shoptitle), '-');
								$shopurls = base_url() . 'shop-preview/' . strtolower($shoptitle) . '?sid=' . md5($shopsv['id']);
							} else {
								$shopurls = 'javascript:void(0)';
							}
                            ?>

                            <div class="about-author">
                                <div class="about-provider-img">
                                    <div class="provider-img-wrap">
                                        <?php
                                        if (!empty($provider['profile_img']) && file_exists($provider['profile_img'])) {
                                            $image = base_url() . $provider['profile_img'];
                                        } else {
                                            $image = base_url() . 'assets/img/user.jpg';
                                        }
                                        ?>
                                        <a href="<?php echo $shopurls; ?>"><img class="img-fluid rounded-circle" alt="" src="<?php echo $image; ?>"></a>
                                    </div>
                                </div>

                                <div class="provider-details">
                                    <a href="<?php echo $shopurls; ?>" class="ser-provider-name"><?php echo  !empty($provider['name']) ? $provider['name'] : '-'; ?></a>
                                    <p class="last-seen"> 
                                        <?php
                                        if(settingValue('provider_status_showhide') == 1) {
                                         if ($provider_online['is_online'] == 2) { ?>
                                            <i class="fas fa-circle"></i> Last seen: &nbsp;
                                            <?php echo  (!empty($days)) ? $days . ' days' : ''; ?> 
                                            <?php if ($days == 0) { ?>
                                                <?php echo  (!empty($hours)) ? $hours . ' hours' : ''; ?>
                                            <?php } ?>
                                            <?php if ($days == 0 && $hours == 0) { ?>
                                                <?php echo  (!empty($minutes)) ? $minutes . ' min' : ''; ?>
                                            <?php } ?>
                                            ago
                                        </p>
                                    <?php } elseif ($provider_online['is_online'] == 1) { ?>
                                        <i class="fas fa-circle online"></i> <?php echo (!empty($user_language[$user_selected]['lg_Online'])) ? $user_language[$user_selected]['lg_Online'] : $default_language['en']['lg_Online']; ?></p>
                                    <?php } } ?>
                                    <p class="mb-1"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo  date('M Y', strtotime($provider['created_at'])); ?></p>
                                </div>
                            </div>
                            <?php if(settingValue('provider_email_showhide') == 1 || settingValue('provider_mobileno_showhide') == 1) { ?>
                            <div class="provider-info">
                            	<?php if(settingValue('provider_email_showhide') == 1) { ?>
                                <p class="mb-0"><i class="far fa-envelope me-1"></i> <?php echo  $provider['email'] ?></p>
                                <?php } 
                                    if(settingValue('provider_mobileno_showhide') == 1) { ?>
                                <p class="mb-0"><i class="fas fa-phone-alt me-1"></i>
                                    <?php
                                    if ($this->session->userdata('id')) {
                                        echo $provider['country_code'].' - '.$provider['mobileno'];
                                    } else {
                                        ?>
                                        xxxxxxxx<?php echo  rand(00, 99); ?>
                                    <?php } ?>

                                </p>
                                <?php  } ?>
                            </div>
                        <?php } }?>
                    </div>
                </div>
                <?php if (!empty($this->session->userdata('id')) && $this->session->userdata('usertype') == 'user') { ?>
                <div class="report">
                    <a id="abuse_report" data-id="<?php echo $service['user_id']; ?>" class='btn btn-sm bg-danger-light'><i class="fas fa-bug" aria-hidden="true"></i> Report this provider</a>
                </div>
                <?php } ?>
                <br>
                <?php if(settingValue('service_availability_showhide') == 1) { ?>
                <div class="card available-widget">
                    <div class="card-body">
                        <h5 class="card-title service-title"><?php echo (!empty($user_language[$user_selected]['lg_Service_Availability'])) ? $user_language[$user_selected]['lg_Service_Availability'] : $default_language['en']['lg_Service_Availability']; ?></h5>
                        <ul>
                            <?php
                            if (!empty($availability_details)) {
                                foreach ($availability_details as $availability) {

                                    $day = $availability['day'];
                                    $from_time = $availability['from_time'];
                                    $to_time = $availability['to_time'];

                                    if ($day == '1') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Monday'])) ? $user_language[$user_selected]['lg_Monday'] : $default_language['en']['lg_Monday'];
                                    } elseif ($day == '2') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Tuesday'])) ? $user_language[$user_selected]['lg_Tuesday'] : $default_language['en']['lg_Tuesday'];
                                    } elseif ($day == '3') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Wednesday'])) ? $user_language[$user_selected]['lg_Wednesday'] : $default_language['en']['lg_Wednesday'];
                                    } elseif ($day == '4') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Thursday'])) ? $user_language[$user_selected]['lg_Thursday'] : $default_language['en']['lg_Thursday'];
                                    } elseif ($day == '5') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Friday'])) ? $user_language[$user_selected]['lg_Friday'] : $default_language['en']['lg_Friday'];
                                    } elseif ($day == '6') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Saturday'])) ? $user_language[$user_selected]['lg_Saturday'] : $default_language['en']['lg_Saturday'];
                                    } elseif ($day == '7') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Sunday'])) ? $user_language[$user_selected]['lg_Sunday'] : $default_language['en']['lg_Sunday'];
                                    } elseif ($day == '0') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Sunday'])) ? $user_language[$user_selected]['lg_Sunday'] : $default_language['en']['lg_Sunday'];
                                    }


                                    echo '<li><span>' . $weekday . '</span>' . $from_time . ' - ' . $to_time . '</li>';
                                }
                            } else {
                                echo '<li class="text-center">No Details found</li>';
                            }
                            ?>
                        </ul>
                    </div>				
                </div>	
				<?php } ?>
				<?php if ($this->session->userdata('usertype') == 'provider') { ?>
						<div class="card available-widget">
						<div class="card-body">
						<h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Service_Gallery'])) ? $user_language[$user_selected]['lg_Service_Gallery'] : $default_language['en']['lg_Service_Gallery']; ?></h5>
						<input type="hidden" name="service_id" id="service_id" value="<?php echo $service['id'];?>">
						<table class="table table-hover" id="sergallery">
							<thead>
							  <tr>
								<th>No.</th>
								<th>Image</th>
								<th class="text-nowrap">Set Default</th>
								<th>Actions</th>
							  </tr>
							</thead>
							<tbody>
							<?php
							$l = 1;
							if(count($service_image) > 0) {
							foreach($service_image as $img){
								$defsrc = base_url().'assets/img/placeholder.jpg';
								 if(file_exists($img['service_image'])){
									$defsrc = base_url().$img['service_image'];
								} else {
									$defsrc = "https://via.placeholder.com/360x220.png?text=Service%20Image";
								}
							?>
							  <tr class="imgrows">
								<td><?php echo $l;?></td>
								<td><img src="<?php echo $defsrc;?>" alt="" class="img-fluid"></td>
								<td class="text-center"><input type="radio" name="set_default_image" class="set_default_image" value="<?php echo $img['id'];?>" <?php echo ($img['is_default'] == 1) ? 'checked' : ''; ?>></td>
								<td><a href="javascript:void(0);" class="on-default remove-row btn btn-sm bg-danger-light me-2 deleteserviceimage" data-id="<?php echo $img['id'];?>"><i class="fa fa-trash"></i></a></td>
							  </tr>
							<?php
							$l++;
							} 
							} else {
								$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
								echo "<tr><td colspan='4'>".$norecord."</td></tr>";
							}
							?>	
							</tbody>
						  </table>
                    </div>				
                </div>	
					<?php
					}
					?>				
				
					
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteServiceImageConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content modal-dialog-centered">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
				<h5 class="modal-title" id="acc_title"></h5>
			</div>
			<div class="modal-body">
				<p id="acc_msg"></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success serviceimg_delete_confirm">Yes</a>
				<button type="button" class="btn btn-danger serviceimg_delete_cancel" data-bs-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<?php if ($this->session->userdata('usertype') == 'user') { ?>
<div class="modal fade" id="corcontrolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo (!empty($user_language[$user_selected]['covid19_vaccine'])) ? $user_language[$user_selected]['covid19_vaccine'] : $default_language['en']['covid19_vaccine'] ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form accept-charset="UTF-8" id="covid_form" method="POST">						
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="radio-inline"><?php echo (!empty($user_language[$user_selected]['have_you_got_covid_vaccine'])) ? $user_language[$user_selected]['have_you_got_covid_vaccine'] : $default_language['en']['have_you_got_covid_vaccine'] ?></label>
								</div>
							</div>
							<div class="col-lg-12 me-3 ms-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid me-1 ms-1"  type="radio" name="covid" value="1"><?php echo (!empty($user_language[$user_selected]['yes_one_injection'])) ? $user_language[$user_selected]['yes_one_injection'] : $default_language['en']['yes_one_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 me-3 ms-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid me-1 ms-1"  type="radio" name="covid"  value="2"><?php echo (!empty($user_language[$user_selected]['yes_two_injection'])) ? $user_language[$user_selected]['yes_two_injection'] : $default_language['en']['yes_two_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 me-3 ms-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid me-1 ms-1"  type="radio" name="covid" value="3"><?php echo (!empty($user_language[$user_selected]['no_im_under_18'])) ? $user_language[$user_selected]['no_im_under_18'] : $default_language['en']['no_im_under_18'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 me-3 ms-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid me-1 ms-1"  type="radio" name="covid" value="4"><?php echo (!empty($user_language[$user_selected]['lg_NO'])) ? $user_language[$user_selected]['lg_NO'] : $default_language['en']['lg_NO'] ?></label>
								</div>
							</div>	
							<br><br>							
							<div class="ms-4 me-4"><small><i>***<?php echo (!empty($user_language[$user_selected]['you_will_be_responsible'])) ? $user_language[$user_selected]['you_will_be_responsible'] : $default_language['en']['you_will_be_responsible']; ?></i></small></div>
						</div>
					</form>
				</div>				
			</div>
			<div class="modal-footer">
				<a href="javascript:" class="btn btn-success vaccine_confirm"><?php echo (!empty($user_language[$user_selected]['update_status'])) ? $user_language[$user_selected]['update_status'] : $default_language['en']['update_status']; ?></a>
				<button type="button" class="btn btn-danger vaccine_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
			</div>
		</div>
	</div>
</div>
<?php } ?>


<div class="modal" id="abuse_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Report This Provider Reason</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" id="abuse_desc" required></textarea>
            <p class="repo_reason_error error" >Reason Is Required</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_abuse_sub" data-userid="<?php echo $this->session->userdata('id'); ?>" data-id="" class="btn btn-primary"><?php echo(!empty($user_language[$user_selected]['lg_admin_confirm']))?($user_language[$user_selected]['lg_admin_confirm']) : 'Confirm';  ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo(!empty($user_language[$user_selected]['lg_admin_cancel']))?($user_language[$user_selected]['lg_admin_cancel']) : 'Cancel';  ?></button>
      </div>
    </div>
  </div>
</div>
