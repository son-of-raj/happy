<?php
$this->db->from('services');

$services_count = $this->db->count_all_results();
$this->db->from('services');
$this->db->where('status', 1);
$this->db->order_by('total_views', 'DESC');
$this->db->limit(3);
$popular = $this->db->get()->result_array();

$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
    }
}

$bgquery = $this->db->query("select * from bgimage WHERE bgimg_for = 'banner'");
$bgresult = $bgquery->result_array();
if(!empty($bgresult[0]['upload_image']))
{
	$bgimg=base_url().$bgresult[0]['upload_image'];
}
else
{
	$bgimg=base_url().'assets/img/banner.jpg';
}

if(!empty($bgresult[0]['banner_content']))
{
	$banner_content=$bgresult[0]['banner_content'];
}
else
{
	$banner_content="World's Largest Marketplace";
}

if(!empty($bgresult[0]['banner_sub_content']))
{
	$banner_sub_content=$bgresult[0]['banner_sub_content'];
}
else
{
	$banner_sub_content="Search From 0 Awesome Verified Ads!";
}

$banner_showhide = $this->db->get_where('bgimage',array('bgimg_id'=> 1))->row();
$howit_showhide = $this->db->get_where('system_settings',array('key'=> 'how_showhide'))->row();

$noservices = (!empty($user_language[$user_selected]['lg_No_Services_Found'])) ? $user_language[$user_selected]['lg_No_Services_Found'] : $default_language['en']['lg_No_Services_Found'];
$noshops = (!empty($user_language[$user_selected]['lg_No_Shops_Found'])) ? $user_language[$user_selected]['lg_No_Shops_Found'] : $default_language['en']['lg_No_Shops_Found'];
?>

<!-- Banner -->
<?php if($banner_showhide->banner_settings == 1)  { ?>
<section class="hero-section" style="background-image: url('<?php echo $bgimg;?>');">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-sm-12 col-12">
                <div class="banner-head">
				   <h3><?php echo $banner_content; ?></h3>
				   <h2><?php echo $banner_sub_content; ?></h2>						
                </div>
                <?php if($banner_showhide->main_search == 1)  { ?> 
                <form action="<?php echo base_url(); ?>search#services" id="search_service" method="post">
                    <div class="banner-input-set">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12 col-12">
                                <div class="banner-input">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                                    <input type="text" class="global" name="common_search" id="search-blk" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_looking_for'])) ? $user_language[$user_selected]['lg_looking_for'] : $default_language['en']['lg_looking_for']; ?>" >
                                    <i class="feather-globe"></i>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 col-12">
                                <div class="banner-input">
                                    <input type="text" class="form-control" value="<?php echo $this->session->userdata('user_address');?>" name="user_address" id="user_address" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_your_location'])) ? $user_language[$user_selected]['lg_your_location'] : $default_language['en']['lg_your_location']; ?>">
                                    <input type="hidden" value="" name="user_latitude" id="user_latitude">
                                    <input type="hidden" value="" name="user_longitude" id="user_longitude">
                                    <?php if(settingValue('location_type') == 'live') { ?>
                                     <a class="current-loc-icon current_location" data-id="1" href="javascript:void(0);" onclick="change_location()"><i class="feather-map-pin"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <ul id="searchResult"></ul>
                    </div>
                    <div class="banner-btn">
                        <button class="btn search_service btn-banner" name="search" value="search"  type="button"><?php echo (!empty($user_language[$user_selected]['lg_search'])) ? $user_language[$user_selected]['lg_search'] : $default_language['en']['lg_search']; ?></button>
                    </div>
                </form>
                 <?php } ?>
                  <?php if($banner_showhide->popular_search == 1)  { ?> 
                <div class="banner-list">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <ul class="search-cat">
                                 <i class="fas fa-circle"></i>
                            <span><?php echo (!empty($banner_showhide->popular_label)) ? $banner_showhide->popular_label : $default_language['en']['lg_popular_search']; ?></span>
                            <?php foreach ($popular as $popular_services) { 
                                $servicetitle = preg_replace('/[^\w\s]+/u',' ',$popular_services['service_title']);
                                $servicetitle = str_replace(' ', '-', $servicetitle);
                                $servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
                                $serviceurl = base_url() . 'service-preview/' . strtolower($servicetitle) . '?sid=' . md5($popular_services['id']);
                            ?>
                                <li><a href="<?php echo $serviceurl; ?>">
                                    <?php echo $popular_services['service_title']; ?>
                                </a></li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                 <?php } ?>
            </div>
        </div>
    </div>
</section>
 <?php } ?>
<!-- /Banner -->

<!-- Category Slider -->
<?php if (!empty($categories))  { ?>
<section class="category-section">
    <div class="container">
		<div class="slider-path">
			<div class="owl-carousel owl-theme owl-carousel" id="category-slider">
				<?php foreach ($categories as $category) {

                    $output =  preg_replace('/[^A-Za-z0-9-]+/', '-', $category['category_name']);

                    $cat_slug = str_replace(" ","-",trim($output));

                    $inputs['category_slug'] = strtolower($cat_slug);

                    $cat_slug = ($category['category_slug'])?$category['category_slug']:$inputs['category_slug'];


                    $data = array('category_slug'=>$cat_slug);

                    if(empty($category['category_slug'])) {

                        $this->db->update('categories', $data, array('id'=>$category['id']));
                    }
                    
					$catname = garbagereplace($category['category_name']);
					$catslug = garbagereplace($category['category_slug']);
					$cateurl = base_url()."search/".strtolower($catslug).'#services';
                    $this->db->from('services s');
                    $this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id','LEFT');
		            $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
                    $this->db->where('status', 1);
                    $this->db->where('category', $category['id']);
					// $cat_services = $this->db->where('category', $category['id'])->count_all_results('services');
                    $query = $this->db->get(); 
		            $cat_services = ($query)?$query->num_rows():FALSE;
		            $cat_services = ($query)?$query->num_rows():FALSE;
				?>
				<div class="card category-card">
				   <div class="card-body">
						<div class="cate-icon">
						   <a href="<?php echo $cateurl?>">
								<?php if ($category['category_image'] != '' && file_exists($category['category_image'])) { ?>
									<img alt="Service Image" src="<?php echo base_url() . $category['category_image']; ?>" alt="">
								<?php } else { ?>
									 <img alt="Service Image" src="<?php echo base_url(); ?>assets/img/professional.png">
								<?php } ?>
						   </a>
						</div>
						 <div class="cate-content">
							<a href="<?php echo $cateurl?>">
							   <span><?php echo $category['category_name']?></span>
							</a>
							<p><span><?php echo $cat_services; ?> Services</span></p>
						 </div>
				   </div>
				</div>
				<?php 
				} ?>
			</div>
		</div>
    </div>
</section>
<?php } ?>
<!-- /Category Slider -->

<!-- Featured Shops -->
<?php  if(settingValue('featured_showhide') == 1) { ?>
<section class="feature-shop-section">
    <div class="container">
        <div class="row">
           <div class="title-set">
               <h2><?php echo (settingValue('featured_title'))?settingValue('featured_title'):'Featured Categories'; ?></h2>
              <h5><?php echo (settingValue('featured_content'))?settingValue('featured_content'):'Test'; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-service">

                        <?php
                        if (!empty($featured_shops)) {?>
						<div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                            <?php foreach ($featured_shops as $frows) {
                                $category = $this->db->get_where('categories', array('id'=>$frows['category']))->row()->category_name;
                                $service_count = $this->db->where('shop_id', $frows['id'])->where('status',1)->count_all_results('services');

                                $product_count = $this->db->where('shop_id', $frows['id'])->where('status',1)->count_all_results('products');
                                
                                $country = $this->db->get_where('countries', array('id'=>$frows['country']))->row()->country_name;
                                $state = $this->db->get_where('state', array('id'=>$frows['state']))->row()->name;
                                $city = $this->db->get_where('city', array('id'=>$frows['city']))->row()->name;
                                $location = $city .', '.$state;

                                $this->db->select("shop_image");
                                $this->db->from('shops_images');
                                $this->db->where("shop_id", $frows['id']);
                                $this->db->where("status", 1);
                                $image = $this->db->get()->row_array();

                                $provider_details = $this->db->where('id', $frows['provider_id'])->get('providers')->row_array();
                                $avg_rating= 0;

                                $user_currency_code = '';
                                $userId = $this->session->userdata('id');
                                
                                $shptitle = preg_replace('/[^\w\s]+/u',' ',$frows['shop_name']);
                                $shptitle = str_replace(' ', '-', $shptitle);
                                $shptitle = trim(preg_replace('/-+/', '-', $shptitle), '-');
                                $shpurls = base_url() . 'shop-preview/' . strtolower($shptitle) . '?sid=' . md5($frows['id']);
                                ?>
								<div class="shop-widget">
									<div class="shop-wrap">
										<div class="shop-img">
											<a href="<?php echo $shpurls; ?>">                                      
												<?php if ($image['shop_image'] != '' && file_exists($image['shop_image']) && (@getimagesize(base_url().$image['shop_image']))) { ?>
													<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $image['shop_image']; ?>" alt="">
												<?php } else { ?>
													 <img class="categorie-img" alt="Shop Image" src="<?php echo base_url().settingValue('service_placeholder_image');?>">
												<?php } ?>
											</a>
										</div>
										<div class="shop-det">
											<h3><a href="<?php echo $shpurls; ?>"><?php echo ucfirst($frows['shop_name']); ?></a></h3>
											<div class="shop-cate"><?php echo $category; ?></div>
											<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo ucfirst($location); ?></div>

										</div>
									<div class="shop-info-det">
										<ul>
											<li><?php echo $service_count; ?> Services</li>
											<li><?php echo $product_count; ?> Products</li>
										</ul>
									</div>
									<div class="visit-store">
										<a href="<?php echo $shpurls; ?>">Visit Store <i class="feather-arrow-right"></i></a>
									</div>
									</div>
								</div>
								
                                <?php
                            } ?>
						</div> 
							<?php
                        } else {                            
                            echo '<div> 
                                    <p class="mb-0 no-content-col">
                                        '.$noshops.'
                                    </p>
                                </div>';
                        }
                        ?>
                </div>
            </div>
            <?php if (!empty($featured_shops)) { ?>
                <div class="btnviewall text-center">
                    <a class="btn btn-viewall" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php } ?>
<!-- Featured Shops -->

<!-- Popular Services -->
<?php  if(settingValue('popular_ser_showhide') == 1) { ?>
<section class="service-section">
    <div class="container">
        <div class="row">
           <div class="title-set">
                <h2><?php echo (settingValue('title_services'))?settingValue('title_services'):'Popular Services'; ?></h2>
                <h5><?php echo (settingValue('content_services'))?settingValue('content_services'):'Popular Service Contents'; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-service">

                        <?php
                        if (!empty($services)) {?>
						<div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                            <?php foreach ($services as $srows) {
                                $country = $this->db->get_where('countries', array('id'=>$srows['country']))->row()->country_name;
                                $state = $this->db->get_where('state', array('id'=>$srows['state']))->row()->name;
                                $city = $this->db->get_where('city', array('id'=>$srows['city']))->row()->name;
                                $location = $city .', '.$state;

                                $shop_name = $this->db->get_where('shops', array('id'=>$srows['shop_id']))->row()->shop_name;

                                $this->db->select("service_image");
                                $this->db->from('services_image');
                                $this->db->where("service_id", $srows['id']);
                                $this->db->where("status", 1);
								$this->db->order_by('is_default','DESC');
                                $image = $this->db->get()->row_array();

                                $provider_details = $this->db->where('id', $srows['user_id'])->get('providers')->row_array();


                                $this->db->select('AVG(rating)');
                                $this->db->where(array('service_id' => $srows['id'], 'status' => 1));
                                $this->db->from('rating_review');
                                $rating = $this->db->get()->row_array();
                                $avg_rating = round($rating['AVG(rating)'], 1);

                                $user_currency_code = '';
                                $userId = $this->session->userdata('id');
                                If (!empty($userId)) {
                                    $service_amount = $srows['service_amount'];

                                    $type = $this->session->userdata('usertype');
                                    if ($type == 'user') {
                                        $user_currency = get_user_currency();
                                    } else if ($type == 'provider') {
                                        $user_currency = get_provider_currency();
                                    }  else if ($type == 'freelancer') {
                                        $user_currency = get_provider_currency();
                                    } 
									$user_currency_code = $user_currency['user_currency_code'];

                                    $service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
                                } else {
                                    $user_currency_code = settings('currency');
                                    $service_currency_code = $srows['currency_code'];
                                    $service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
                                }
								
								$servicetitle = preg_replace('/[^\w\s]+/u',' ',$srows['service_title']);
								$servicetitle = str_replace(' ', '-', $servicetitle);
								$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
								$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($srows['id']);
                                $cur_date = date("Y-m-d");
                                $current_time = date("H:i");
                                $this->db->where("'$cur_date' BETWEEN start_date AND end_date");
                                $this->db->where("'$current_time' BETWEEN start_time AND end_time");
                                $this->db->where('provider_id', $srows['user_id']);
                                $this->db->where('service_id', $srows['id']);
                                $service_offer = $this->db->get('service_offers')->row_array();
                                $tagtxt = (!empty($user_language[$user_selected]['lg_Offer_Tag'])) ? $user_language[$user_selected]['lg_Offer_Tag'] : $default_language['en']['lg_Offer_Tag'];
                                $tagcontent = $service_offer['offer_percentage']."% ".$tagtxt;
                                ?>
                                <div class="feature-shop">
                                    <div class="feature-img">
                                        <?php if(!empty($service_offer)) { ?>
                                            <div class="offer-tags">
                                                <span class="bg-red"><?php echo $tagcontent; ?></span>
                                            </div>
                                        <?php } ?>
                                        <a href="<?php echo $service_url; ?>">
    										<?php if ($image['service_image'] != ''  && file_exists($image['service_image']) && (@getimagesize(base_url().$image['service_image']))) { ?>
                                                <img class="categorie-img" alt="Service Image" src="<?php echo base_url() . $image['service_image']; ?>" alt="">
    										<?php } else { ?>
    											 <img class="categorie-img" alt="Service Image" src="<?php echo base_url().settingValue('service_placeholder_image'); ?>">
    										<?php } ?>
                                        </a>
                                        <div class="feature-bottom">
                                            <a href="#">
                                                <?php  if ($provider_details['profile_img'] != ''  && file_exists($provider_details['profile_img']) && (@getimagesize(base_url().$provider_details['profile_img']))) { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/user.jpg">
                                                <?php } ?>
                                            </a>
                                            <p><a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_name'])); ?>"><?php echo ucfirst($srows['category_name']); ?></a></p>
                                        </div>
                                    </div>
                                    <div class="featute-info">
                                        <h4>
                                            <a href="<?php echo $service_url; ?>"><?php echo ucfirst($srows['service_title']); ?></a>
                                        </h4>
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
                                             <span class="text-muted">(<?php echo $avg_rating ?>)</span>
                                          </div>
									  <div class="product-by">
										<span>by <a href="#"><?php echo $shop_name; ?></a></span>
									</div>
                                      <h6><?php echo currency_conversion($user_currency_code) . $service_amount; ?></h6>
                                      <div class="service-location"><i class="fas fa-map-marker-alt me-2"></i> <?php echo ucfirst($srows['service_location']); ?></div>
                                   </div>
                                    
                                </div>
                                <?php
                            } ?>
                    </div> 
							<?php
                        } else {

                            echo '<div>	
									<p class="mb-0 no-content-col">
									'.$noservices.'
									</p>
								</div>';
                        }
                        ?>
                </div>
            </div>
            <?php if (!empty($services)) { ?>
                <div class="btnviewall text-center">
                    <a class="btn btn-viewall" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php  }  ?>
<!-- /Popular Services -->

<!-- Popular Shops -->
<section class="popular-bg pad-set">
    <div class="container">
        <div class="row">
           <div class="title-set">
              <h2><?php echo (!empty($user_language[$user_selected]['lg_Most_Popular_Shops'])) ? $user_language[$user_selected]['lg_Most_Popular_Shops'] : $default_language['en']['lg_Most_Popular_Shops']; ?></h2>
              <h5><?php echo (!empty($user_language[$user_selected]['lg_exlore_greates_shop'])) ? $user_language[$user_selected]['lg_exlore_greates_shop'] : $default_language['en']['lg_exlore_greates_shop']; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-service">

                        <?php
                        if (!empty($shops)) {?>
						<div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                            <?php foreach ($shops as $srows) {
                                $category = $this->db->get_where('categories', array('id'=>$srows['category']))->row()->category_name;
                                $service_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('services');

                                $product_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('products');

                                 $country = $this->db->get_where('countries', array('id'=>$srows['country']))->row()->country_name;
                                $state = $this->db->get_where('state', array('id'=>$srows['state']))->row()->name;
                                $city = $this->db->get_where('city', array('id'=>$srows['city']))->row()->name;
                                $location = $city .', '.$state;

                                $this->db->select("shop_image");
								$this->db->from('shops_images');
								$this->db->where("shop_id", $srows['id']);
								$this->db->where("status", 1);
								$image = $this->db->get()->row_array();

                                $provider_details = $this->db->where('id', $srows['provider_id'])->get('providers')->row_array();
								$avg_rating= 0;

                                $user_currency_code = '';
                                $userId = $this->session->userdata('id');
								$shoptitle = preg_replace('/[^\w\s]+/u',' ',$srows['shop_name']);
								$shoptitle = str_replace(' ', '-', $shoptitle);
								$shoptitle = trim(preg_replace('/-+/', '-', $shoptitle), '-');
								$shopurls = base_url() . 'shop-preview/' . strtolower($shoptitle) . '?sid=' . md5($srows['id']);
                                ?>
								
								
								<div class="shop-widget">
									<div class="shop-wrap">
										<div class="shop-img">
											<a href="<?php echo $shopurls; ?>">										
											<?php if ($image['shop_image'] != '' && file_exists($image['shop_image'])) { ?>
												<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $image['shop_image']; ?>" alt="">
											<?php } else { ?>
												 <img class="categorie-img" alt="Shop Image" src="<?php echo base_url().settingValue('service_placeholder_image');?>">
											<?php } ?>
											</a>											</a>
										</div>
										<div class="shop-det">
											<h3><a href="<?php echo $shopurls; ?>"><?php echo ucfirst($srows['shop_name']); ?></a></h3>
											<div class="shop-cate">
												<?php if($srows['category_name'] != '') { ?>
												<a href="<?php echo base_url().'search/'.str_replace(' ', '-', $srows['category_slug']);?>"><?php echo $srows['category_name'];?></a>
												<?php } ?>
											</div>
											<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo ucfirst($location); ?></div>

										</div>
									<div class="shop-info-det">
										<ul>
											<li><?php echo $service_count; ?> Services</li>
											<li><?php echo $product_count; ?> Products</li>
										</ul>
									</div>
									<div class="visit-store">
										<a href="<?php echo $shopurls; ?>">Visit Store <i class="feather-arrow-right"></i></a>
									</div>
									</div>
								</div>
                                <?php
								
                            } ?>
                    </div> 
							<?php
							
                        } else {

                            echo '<div>	
									<p class="mb-0 no-content-col">
										'.$noshops.'
									</p>
								</div>';
                        }
                        ?>
                </div>
            </div>
            <?php if (!empty($shops)) { ?>
                <div class="btnviewall text-center">
                    <a class="btn btn-viewall" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- Popular Shops -->

<!--Services With Offer-->
<section class="service-section">
    <div class="container">
        <div class="row">
           <div class="title-set">
              <h2><?php echo (!empty($user_language[$user_selected]['lg_Offers'])) ? $user_language[$user_selected]['lg_Offers'] : $default_language['en']['lg_Offers']; ?></h2>
              <h5><?php echo (!empty($user_language[$user_selected]['lg_Offers_Txt'])) ? $user_language[$user_selected]['lg_Offers_Txt'] : $default_language['en']['lg_Offers_Txt']; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-service">
					
                        <?php
                        if (!empty($offers)) {?>
						<div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                            <?php foreach ($offers as $os) {
                                $userId = $this->session->userdata('id');
                                $type = $this->session->userdata('usertype');
                                if (empty($userId)) {
                                $user_currency_code = settings('currency');
                                $service_currency_code = $os['currency_code'];
                                $service_amount = get_gigs_currency($os['service_amount'], $os['currency_code'], $user_currency_code);
                                } else {
                                    $user_currency_code = '';
                                    if ($type == 'user') {
                                        $user_currency = get_user_currency();
                                    } else if ($type == 'provider') {
                                        $user_currency = get_provider_currency();
                                    } else if ($type == 'freelancer') {
                                        $user_currency = get_provider_currency();
                                    } else {
                                        $user_currency['user_currency_code'] = settingValue('currency_option');
                                    }
                                    $user_currency_code = $user_currency['user_currency_code'];
                                }
                                //$user_currency_code = settings('currency');
                                $service_amount = get_gigs_currency($os['service_amount'], $os['currency_code'], $user_currency_code);

                                $servicetitle = preg_replace('/[^\w\s]+/u',' ',$os['service_title']);
                                $servicetitle = str_replace(' ', '-', $servicetitle);
                                $servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
                                $service_url = base_url() . 'service-preview/' . strtolower($servicetitle) . '?sid=' . md5($os['service_id']);

                                $avg_rating = round($os['rating'], 1);
                                
                                $tagtxt = (!empty($user_language[$user_selected]['lg_Offer_Tag'])) ? $user_language[$user_selected]['lg_Offer_Tag'] : $default_language['en']['lg_Offer_Tag'];
                                $tagcontent = $os['offer_percentage']."% ".$tagtxt;

                                $shop_name = $this->db->get_where('shops', array('id'=>$os['shop_id']))->row()->shop_name;
                                
                                ?>
                                <div class="feature-shop">
                                    <div class="feature-img">
										<div class="offer-tags">
                                            <span class="bg-red"><?php echo $tagcontent; ?></span>
                                        </div>
                                        <a href="<?php echo $service_url; ?>">
                                            <?php 
                                            if ($os['service_image'] != ''  && file_exists($os['service_image']) && (@getimagesize(base_url().$os['service_image']))) 
                                            { 
                                            ?>
                                                <img class="categorie-img" alt="Service Image" src="<?php echo base_url() . $os['service_image']; ?>" alt="">
                                            <?php 
                                            } 
                                            else 
                                            { 
                                            ?>
                                             <img class="categorie-img" alt="Service Image" src="<?php echo base_url().settingValue('profile_placeholder_image'); ?>">
                                            <?php
                                            } 
                                            ?>
                                        </a>
                                        <div class="feature-bottom">
                                            <a href="#">
                                                <?php  if ($os['profile_img'] != ''  && file_exists($os['profile_img']) && (@getimagesize(base_url().$os['profile_img']))) { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url() . $os['profile_img'] ?>">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url(); ?>assets/img/user.jpg">
                                                    
                                                <?php } ?>
                                            </a>
                                            <p><a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($os['category_slug'])); ?>"><?php echo ucfirst($os['category_name']); ?></a></p>
                                        </div>
                                    </div>
                                    <div class="featute-info">
                                      <h4><a href="<?php echo $service_url; ?>"><?php echo ucfirst($os['service_title']); ?></a></h4>
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
                                         <span class="text-muted">(<?php echo $avg_rating ?>)</span>
                                      </div>
									  <div class="product-by">
										<span>by <a href="#"><?php echo $shop_name; ?></a></span>
									</div>
                                      <h6><?php echo currency_conversion($user_currency_code) . $service_amount; ?></h6>
                                      <div class="service-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo ucfirst($os['service_location']); ?></div>
                                   </div>
                                </div>
                                <?php 
                            
                            } ?>
                    </div> 
							<?php
                        } else
                        {
                            echo '<div> 
                                    <p class="mb-0 no-content-col">
                                    '.$noservices.'
                                    </p>
                                </div>';
                        }
                        ?>
                </div>
            </div>
            <?php if (!empty($offers)) { ?>
                <div class="btnviewall text-center">
                    <a class="btn btn-viewall" href="<?php echo base_url(); ?>offered-services"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!--Services With Offer-->

<!-- Nearest Shops -->
<section class="pad-set popular-bg">
    <div class="container">
        <div class="row">
           <div class="title-set">
              <h2><?php echo (!empty($user_language[$user_selected]['lg_Nearest_Shops'])) ? $user_language[$user_selected]['lg_Nearest_Shops'] : $default_language['en']['lg_Nearest_Shops']; ?></h2>
              <h5><?php echo (!empty($user_language[$user_selected]['lg_exlore_greates_shop'])) ? $user_language[$user_selected]['lg_exlore_greates_shop'] : $default_language['en']['lg_exlore_greates_shop']; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-service">
                        <?php
                        if (!empty($nearest_shops)) {?>
						<div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                            <?php foreach ($nearest_shops as $nrows) {
                                $category = $this->db->get_where('categories', array('id'=>$nrows['category']))->row()->category_name;
                                $service_count = $this->db->where('shop_id', $nrows['id'])->where('status',1)->count_all_results('services');
                                $product_count = $this->db->where('shop_id', $nrows['id'])->where('status',1)->count_all_results('products');

                                $country = $this->db->get_where('countries', array('id'=>$nrows['country']))->row()->country_name;
                                $state = $this->db->get_where('state', array('id'=>$nrows['state']))->row()->name;
                                $city = $this->db->get_where('city', array('id'=>$nrows['city']))->row()->name;
                                $location = $city .', '.$state;

                                $this->db->select("shop_image");
								$this->db->from('shops_images');
								$this->db->where("shop_id", $nrows['id']);
								$this->db->where("status", 1);
								$image = $this->db->get()->row_array();

                                $provider_details = $this->db->where('id', $nrows['provider_id'])->get('providers')->row_array();
								$avg_rating= 0;

                                $user_currency_code = '';
                                $userId = $this->session->userdata('id');
                                
								$shptitle = preg_replace('/[^\w\s]+/u',' ',$nrows['shop_name']);
								$shptitle = str_replace(' ', '-', $shptitle);
								$shptitle = trim(preg_replace('/-+/', '-', $shptitle), '-');
								$shpurls = base_url() . 'shop-preview/' . strtolower($shptitle) . '?sid=' . md5($nrows['id']);
                                ?>
								<div class="shop-widget">
									<div class="shop-wrap">
										<div class="shop-img">
										  <?php if (isset($nrows['distance'])) { ?>
                                            <div class="distance-tag"> <span class="offer-percentage"><?php echo round($nrows['distance'],2)?> km</span></div>
                                        <?php } ?>
                                        <a href="<?php echo $shpurls; ?>">										
    										<?php if ($image['shop_image'] != '' && file_exists($image['shop_image']) && (@getimagesize(base_url().$image['shop_image']))) { ?>
                                                <img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $image['shop_image']; ?>" alt="">
    										<?php } else { ?>
    											 <img class="categorie-img" alt="Shop Image" src="<?php echo base_url().settingValue('service_placeholder_image'); ?>">
    										<?php } ?>
                                        </a>
										</div>
										<div class="shop-det">
											<h3><a href="<?php echo $shpurls; ?>"><?php echo ucfirst($nrows['shop_name']); ?></a></h3>
											<div class="shop-cate"><?php echo $category; ?></div>
											<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo ucfirst($location); ?></div>

										</div>
									<div class="shop-info-det">
										<ul>
											<li><?php echo $service_count; ?> Services</li>
											<li><?php echo $product_count; ?> Products</li>
										</ul>
									</div>
									<div class="visit-store">
										<a href="<?php echo $shpurls; ?>">Visit Store <i class="feather-arrow-right"></i></a>
									</div>
									</div>
								</div>
                                <?php
                            
							} ?>
                    </div> 
							<?php
                         } else {

                            echo '<div>	
									<p class="mb-0 no-content-col">
										'.$noshops.'
									</p>
								</div>';
                        }
                        ?>
                </div>
            </div>
            <?php if (!empty($nearest_shops)) { ?>
                <div class="btnviewall text-center">
                    <a class="btn btn-viewall" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- Nearest Shops -->


<!-- Blog -->
<?php if(settingValue('blogs_showhide') == 1) { ?>
    <section class="service-section">
        <div class="container">
            <div class="row">
               <div class="title-set">
                  <h2><?php echo(!empty($blog_language['title']))?($blog_language['title']) : 'Blogs';  ?></h2>
                  <h5><?php echo(!empty($blog_language['content']))?($blog_language['content']) : 'Latest From Our Blog';  ?></h5>
               </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider-service">
                            <?php
                            if (!empty($blogs)) {?>
                            <div class="owl-carousel owl-theme owl-carousel sliders-shops owl-loaded owl-drag">
                                <?php foreach ($blogs as $post) { ?>
                                    <div class="shop-widget">
                                        <div class="shop-wrap">
                                            <div class="shop-img">
                                            <a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>">                                        
                                                <?php if ($post['image_default'] != '' && file_exists($post['image_default']) && (@getimagesize(base_url().$post['image_default']))) { ?>
                                                    <img class="categorie-img" alt="POST Image" src="<?php echo $post['image_default']; ?>" alt="">
                                                <?php } else { ?>
                                                     <a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>">
                                                        <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                                    </a>
                                                <?php } ?>
                                            </a>
                                            </div>
                                            <div class="shop-det">
                                                <h3><a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>"><?php echo ucfirst($post['title']); ?></a></h3>
                                               <!--  <div class="shop-cate"><?php //echo $category; ?></div> -->
                                                <div class="shop-location"><i class="far fa-calendar me-1"></i><?php echo date('d-M-Y',strtotime($post['createdAt'])); ?></div>

                                            </div>
                                        
                                        <div class="visit-store">
                                            <a href="<?php echo $base_url; ?>user-blog-details/<?php echo $post['url']; ?>">Read More <i class="feather-arrow-right"></i></a>
                                        </div>
                                        </div>
                                    </div>
                                    <?php
                                
                                } ?>
                        </div> 
                                <?php
                             } else {

                                echo '<div> 
                                        <p class="mb-0 no-content-col">
                                            '.$noshops.'
                                        </p>
                                    </div>';
                            }
                            ?>
                    </div>
                </div>
                <?php if (!empty($blogs)) { ?>
                    <div class="btnviewall text-center">
                        <a class="btn btn-viewall" href="<?php echo base_url(); ?>all-blogs"><?php echo (!empty($user_language[$user_selected]['lg_View_All'])) ? $user_language[$user_selected]['lg_View_All'] : $default_language['en']['lg_View_All']; ?> <i class="feather-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
<!-- /Blog -->

<!-- Works -->
<?php  if(settingValue('how_showhide') == 1) { ?>
<section class="popular-bg pad-set">
    <div class="container">
        <div class="row">
           <div class="title-set">
              <h2><?php echo (settingValue('how_title'))?settingValue('how_title'):'How It Works'; ?></h2>
                <h5><?php echo (settingValue('how_content'))?settingValue('how_content'):'How It Works Content'; ?></h5>
           </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="work-item flex-fill">
                    <div class="work-set-img">
                        <h2>01</h2>
                    </div>
                    <div class="work-set-content">
                        <div class="howit-icon">
							<span>
								<?php
								$bo1query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image1'");
								$bo1result = $bo1query->result_array();
								if(!empty(settingValue('how_title_img_1'))){                                            
									echo '<img src="'.base_url().settingValue('how_title_img_1').'">';
								}else{
									
									echo '<img src="'.base_url().'assets/img/icon-1.png">';
								}
								?>
							</span>
                        </div>
                        <h2>
                            <?php echo (settingValue('how_title_1'))?settingValue('how_title_1'):'Choose What To Do'; ?>
                        </h2>
                        <h5>
                            <?php echo (settingValue('how_content_1'))?settingValue('how_content_1'):'Aliquam lorem ante, dapibus in, viverra quis, feugiat Phasellus viverra nulla ut metus varius laoreet.'; ?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="work-item flex-fill">
                    <div class="work-set-img work2">
                        <h2>02</h2>
                    </div>
                    <div class="work-set-content">
                        <div class="howit-icon">
							<span>
                            <?php
    							$bo2query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image2'");
    							$bo2result = $bo2query->result_array();
    							if(!empty(settingValue('how_title_img_2'))){                                         
                                    echo '<img src="'.base_url().settingValue('how_title_img_2').'">';
                                }else{
                                    
                                    echo '<img src="'.base_url().'assets/img/icon-2.png">';
                                }
    							?>
							</span>
                        </div>
                        <h2>
        					<?php echo (settingValue('how_title_2'))?settingValue('how_title_2'):'Find What You Want'; ?>
    					</h2>
                        <h5>
        					<?php echo (settingValue('how_content_2'))?settingValue('how_content_2'):'Aliquam lorem ante, dapibus in, viverra quis, feugiat Phasellus viverra nulla ut metus varius laoreet.'; ?>
    					</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="work-item flex-fill">
                    <div class="work-set-img">
                        <h2>03</h2>
                    </div>
                    <div class="work-set-content">
						<div class="howit-icon">
							<span>
							<?php
								$bo3query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image3'");
								$bo3result = $bo3query->result_array();
								if(!empty(settingValue('how_title_img_3'))){                                         
									echo '<img src="'.base_url().settingValue('how_title_img_3').'">';
								}else{
									
									echo '<img src="'.base_url().'assets/img/icon-3.png">';
								}
							?>
							</span>
						</div>
                       <h2>
        					<?php echo (settingValue('how_title_3'))?settingValue('how_title_3'):'Amazing Places'; ?>
    					</h2>
                        <h5>
        					<?php echo (settingValue('how_content_3'))?settingValue('how_content_3'):'Aliquam lorem ante, dapibus in, viverra quis, feugiat Phasellus viverra nulla ut metus varius laoreet.'; ?>
    					</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="work-item flex-fill">
                    <div class="work-set-img work2">
                        <h2>04</h2>
                    </div>
                    <div class="work-set-content">
                        <div class="howit-icon">
                            <span>
                            <?php
                                $bo2query = $this->db->query("select * from bgimage WHERE bgimg_for = 'bottom_image2'");
                                $bo2result = $bo2query->result_array();
                                if(!empty(settingValue('how_title_img_4'))){                                         
                                    echo '<img src="'.base_url().settingValue('how_title_img_4').'">';
                                }else{
                                    
                                    echo '<img src="'.base_url().'assets/img/icon-2.png">';
                                }
                                ?>
                            </span>
                        </div>
                        <h2>
                            <?php echo (settingValue('how_title_4'))?settingValue('how_title_4'):'Find What You Want'; ?>
                        </h2>
                        <h5>
                            <?php echo (settingValue('how_content_4'))?settingValue('how_content_4'):'Aliquam lorem ante, dapibus in, viverra quis, feugiat Phasellus viverra nulla ut metus varius laoreet.'; ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>
<!-- /Works -->
<?php
function garbagereplace($string) 
{
    $garbagearray = array('@','#','$','%','^','&','*','!',',');
    $garbagecount = count($garbagearray);
    for ($i=0; $i<$garbagecount; $i++) 
    {
        $string = str_replace(' ', '-', str_replace($garbagearray[$i], '-', $string));
    }
    return $string;
}
?>