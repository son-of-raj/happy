<?php
	$query = $this->db->query("select * from system_settings WHERE status = 1");
	$result = $query->result_array();
	if(!empty($result))
		{
		foreach($result as $data){
			if($data['key'] == 'currency_option'){
				$currency_option = $data['value'];
			}
		}
	}
	if(!empty($service)) {
		foreach ($service as $srows) {
			
			$provider_details = $this->db->where('id',$srows['user_id'])->get('providers')->row_array();
			$shop_name = $this->db->get_where('shops', array('id'=>$srows['shop_id']))->row()->shop_name;

			$serviceimage=explode(',', $srows['service_image']);

			$this->db->select('AVG(rating)');
			$this->db->where(array('service_id'=>$srows['id'],'status'=>1));
			$this->db->from('rating_review');
			$rating = $this->db->get()->row_array();
			$avg_rating = round($rating['AVG(rating)'],1);
			$userId = $this->session->userdata('id');
			$type = $this->session->userdata('usertype');
			if (empty($userId)) {
			$user_currency_code = settings('currency');
			$service_currency_code = $srows['currency_code'];
			$service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
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
			$serviceimages=$this->db->where('service_id',$srows['id'])->get('services_image')->row_array();
			$service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
			$servicetitle = preg_replace('/[^\w\s]+/u',' ',$srows['service_title']);
			$servicetitle = str_replace(' ', '-', $servicetitle);
			$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
			$service_url = base_url() . 'service-preview/' . strtolower($servicetitle) . '?sid=' . md5($srows['id']);

			$shopurl = base_url() . 'shop-preview/' . strtolower($shop_name) . '?sid=' . md5($srows['shop_id']);
			
			$cur_date = date("Y-m-d");
            $current_time = date("H:i");
            $this->db->where("'$cur_date' BETWEEN start_date AND end_date");
            $this->db->where("'$current_time' BETWEEN start_time AND end_time");
            $this->db->where('provider_id', $srows['user_id']);
            $this->db->where('service_id', $srows['id']);
            $service_offer = $this->db->get('service_offers')->row_array();
            $tagtxt = (!empty($user_language[$user_selected]['lg_Offer_Tag'])) ? $user_language[$user_selected]['lg_Offer_Tag'] : $default_language['en']['lg_Offer_Tag'];
            $tagcontent = $service_offer['offer_percentage']."% ".$tagtxt; ?>

	        <div class="col-lg-3 col-md-6">
	            <div class="feature-shop feature-box">
	                <div class="feature-img">
	                	<?php if(!empty($service_offer)) { ?>
	                        <div class="offer-tags">
	                            <span class="bg-red"><?php echo $tagcontent; ?></span>
	                        </div>
	                	<?php } ?>
	                    <a href="<?php echo $service_url; ?>">
	                        <?php if (!empty($serviceimages['service_image'])  && file_exists($serviceimages['service_image']) && (@getimagesize(base_url().$serviceimages['service_image']))) { ?>
	                            <img class="categorie-img" alt="Service Image" src="<?php echo base_url() . $serviceimages['service_image']; ?>">
	                        <?php } else { ?>
	                            <img class="categorie-img" alt="Service Image" src="https://via.placeholder.com/360x220.png?text=Service%20Image">
	                        <?php } ?>
	                    </a>
	                    <div class="feature-bottom">
	                        <a href="#">
	                            <?php  if ($provider_details['profile_img'] != ''  && file_exists($provider_details['profile_img']) && (@getimagesize(base_url().$provider_details['profile_img']))) { ?>
	                                <img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
	                            <?php } else { ?>
	                                <img class="rounded-circle" src="<?php echo base_url().settingValue('profile_placeholder_image'); ?>">
	                            <?php } ?>
	                        </a>
	                        <p><a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_slug'])); ?>"><?php echo ucfirst($srows['category_name']); ?></a></p>
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
	                         <span class="text-muted">(<?php echo $avg_rating; ?>)</span>
	                      </div>
						  <div class="product-by">
							<span>by <a href="<?php echo $shopurl; ?>"><?php echo $shop_name; ?></a></span>
						</div>
	                  <h6><?php echo currency_conversion($user_currency_code) . $service_amount; ?></h6>
	                  <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo ucfirst($srows['service_location']); ?></p>
	               </div>
	            </div>

	        </div>
<?php   } 
	} else { 
	$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
	echo '<div class="col-lg-12">
			<p class="mb-0">'.$norecord.'</p>
		</div>';
	} 
	echo $this->ajax_pagination->create_links();
?>
<script src="<?php echo base_url();?>assets/js/functions.js"></script>