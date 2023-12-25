<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);

$avg_rating = 0;

if (!empty($shop['provider_id'])) {
    $provider_online = $this->db->where('id', $shop['provider_id'])->from('providers')->get()->row_array();
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

$uid = $shop['provider_id'];									
$WHERE = array('ser.user_id' => $uid, 'ser.status' => 1);
$this->db->select('ser.id, ser.user_id, ser.service_title, ser.currency_code, ser.service_amount, ser.category, ser.subcategory, ser.sub_subcategory, ser.duration, ser.duration_in, ser.status, ser.service_location'); 
$this->db->from('services AS ser');		
$this->db->where("FIND_IN_SET('".$shop['id']."', ser.shop_id)");
$this->db->where($WHERE);
$serv_lists = $this->db->get()->result_array();
 
$get_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();

$category_name = $this->db->select('category_name')->where('id',$shop['category'])->get('categories')->row()->category_name;
$subcategory_name = $this->db->select('subcategory_name')->where('id',$shop['subcategory'])->get('subcategories')->row()->subcategory_name;
$sub_subcategory_name = $this->db->select('sub_subcategory_name')->where('id',$shop['sub_subcategory'])->get('sub_subcategories')->row()->sub_subcategory_name;

//Get Shop Image
$shop_image = $this->db->where('shop_id',$shop['id'])->get('shops_images')->row()->shop_image;

//Covid vaccine status
$covidControl = settingValue('corona_control');
$cvaccine = $get_details['covid_vaccine'];

$service_count = $this->db->where('shop_id', $shop['id'])->where('status', 1)->count_all_results('services');
$product_count = $this->db->where('shop_id', $shop['id'])->where('status', 1)->count_all_results('products');

?>

<div class="content">
    <div class="container">
				<div class="shop-header mt-30 mb-80">
                    <div class="shop-header-inner">
                        <div class="shop-logo">
                            <img src="<?php echo ($shop_image)?base_url().$shop_image:base_url().'assets/img/shop-logo.png'; ?>" alt="">
                        </div>
                        <div class="shop-content">
                            <div class="product-category">
                                <span class="text-muted"><?php echo $category_name; ?></span>
                            </div>
                            <h3 class="mb-3"><?php echo ucfirst($shop['shop_name']); ?></h3>
							<div class="shop-info">
								<ul>
									<li><i class="fas fa-map-marker-alt"></i><strong>Address: </strong> <span><?php echo ucfirst($shop['shop_location']); ?></span></li>
									<li><i class="fas fa-phone-alt"></i><strong>Call Us:</strong> <span>(+<?php echo $shop['country_code']; ?>) - <?php echo $shop['contact_no']; ?></span></li>
									<li><i class="fas fa-envelope-open-text"></i><strong>Email:</strong> <span><?php echo $shop['email']; ?></span></li>
								</ul>
							</div>
                        </div>

						<?php $type = $this->session->userdata('usertype');
					
						
						?>
                        <div class="shop-details">
							<!-- <a class="btn btn-secondary" id="service_view" href="<?php echo base_url() . 'all-services/' ?>"><?php echo $service_count; ?> Services</a> -->
							<!-- <a class="btn btn-secondary" href="<?php echo base_url() . 'my-products/'.$shop['id']; ?>"><?php echo $product_count; ?> Products</a> -->
							<a class="btn btn-secondary" id="service_view" href="#services_lists"><?php echo $service_count; ?> Services</a>
							<?php if($type == "") {?>
								<br>
								<button class="btn btn-secondary" id="prod_modal" onclick="product_submit_modal($type)"><?php echo $product_count; ?> Products</button>
							<?php }else{?>
								<a class="btn btn-secondary" href="<?php echo base_url() . 'my-products/'.$shop['id']; ?>"><?php echo $product_count; ?> Products</a>
							<?php } ?>
							
                        </div>
                    </div>
                </div>

        <div class="row" >
            <div class="col-lg-8 col-sm-12 col-12">
				<div class="inner-tab-shop">
					<h2><?php echo (!empty($user_language[$user_selected]['lg_Shop_Details'])) ? $user_language[$user_selected]['lg_Shop_Details'] : $default_language['en']['lg_Shop_Details']; ?></h2>
					<p><?php echo $shop['description']; ?></p>
			   </div>
				<div class="inner-tab-shop">
					<h2>Services</h2>
			   </div>
			   <div class="row" id="services_lists">
					<?php if (!empty($serv_lists)) { 
							foreach($serv_lists as $srows) {
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
								$service_url = base_url() . 'service-preview/' . strtolower($servicetitle) . '?sid=' . md5($srows['id']);
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
						<div class="col-lg-4 col-md-6">
							<div class="feature-shop feature-box">
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
										<p><a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_slug'])); ?>"><?php echo $srows['category_name']; ?></a></p>
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
										<span>by <a href="#"><?php echo $shop['shop_name']; ?></a></span>
									</div>
								  <h6><?php echo currency_conversion($user_currency_code) . $service_amount; ?></h6>
								  <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo ucfirst($srows['service_location']); ?></p>
							   </div>
							</div>
						</div>
						<?php } } else { ?>
							<div>	
								<p class="">
									<?php echo 'Service Not Found'; ?>
								</p>
							</div>
			            <?php } ?>
            	</div>
            </div>
            
            <div class="col-lg-4 col-sm-12 col-12 theiaStickySidebar">
                <div class="sidebar-widget widget">
					<div class="service-book">
					<?php                      
                        $userId = $this->session->userdata('id');
                        $usertype = $this->session->userdata('usertype');
                        $token = $this->session->userdata('chat_token');

                        if (!empty($this->session->userdata('id'))) {
							if ($shop['provider_id'] == $this->session->userdata('id')) {
								if ($this->session->userdata('usertype') == 'provider') {
									?>
									<a href="<?php echo base_url() . 'edit-shop/' . $shop['id']; ?>" class="btn btn-appoitment" > <?php echo (!empty($user_language[$user_selected]['lg_Edit_Shop'])) ? $user_language[$user_selected]['lg_Edit_Shop'] : $default_language['en']['lg_Edit_Shop']; ?> </a>
									<?php
								}
							}
						} ?>
					</div>
				</div>

                <div class="card available-widget">
                    <div class="card-body">
                        <h5 class="card-title service-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Availability'])) ? $user_language[$user_selected]['lg_Shop_Availability'] : $default_language['en']['lg_Shop_Availability']; ?></h5>
                        <ul>
                            <?php $availability_details = json_decode($shop['availability'], true);
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staffSelectConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title">
					<?php echo '<span class="service-pricesymbol">'.$defaultcurrencysymbol.'</span>'?>
					<span  id="acc_title" class="service-pricesymbol"></span>
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12"><p id="acc_msg"></p></div>
					<div class="col-lg-12"><p id="acc_extramsg"></p></div>
					<div class="col-lg-6 slotdetails">
					   <div class="form-group">
							<label>Date <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="book_date" id="book_date" />
						</div>
					</div>

					<div class="col-lg-6 slotdetails">
						<div class="form-group">
							<label>Time slot <span class="text-danger">*</span></label>
							<select class="form-control from_time" name="from_time" id="from_time" required>
							</select>

						</div>
					</div>
				</div>				
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_accept_confirm">Confirm Booking</a>
				<button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
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
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="1"><?php echo (!empty($user_language[$user_selected]['yes_one_injection'])) ? $user_language[$user_selected]['yes_one_injection'] : $default_language['en']['yes_one_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="2"><?php echo (!empty($user_language[$user_selected]['yes_two_injection'])) ? $user_language[$user_selected]['yes_two_injection'] : $default_language['en']['yes_two_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="3"><?php echo (!empty($user_language[$user_selected]['no_im_under_18'])) ? $user_language[$user_selected]['no_im_under_18'] : $default_language['en']['no_im_under_18'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="4"><?php echo (!empty($user_language[$user_selected]['lg_NO'])) ? $user_language[$user_selected]['lg_NO'] : $default_language['en']['lg_NO'] ?></label>
								</div>
							</div>	
							<br><br>							
							<div class="ml-4 mr-4"><small><i>***<?php echo (!empty($user_language[$user_selected]['you_will_be_responsible'])) ? $user_language[$user_selected]['you_will_be_responsible'] : $default_language['en']['you_will_be_responsible']; ?></i></small></div>
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