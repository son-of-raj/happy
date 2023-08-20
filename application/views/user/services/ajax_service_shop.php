
						
						<?php							
							if(!empty($shops)){
								foreach ($shops as $srows) {
									$this->db->select("shop_image");
									$this->db->from('shops_images');
									$this->db->where("shop_id",$srows['id']);
									$this->db->where("status",1);
									$image = $this->db->get()->row_array(); 
									$shopimages = $image['shop_image'];

									$avg_rating = 0;

									$category = $this->db->get_where('categories', array('id'=>$srows['category']))->row()->category_name;
                                	$service_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('services');
                                	$product_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('products');
                                	
									$provider_details = $this->db->where('id',$srows['provider_id'])->get('providers')->row_array();
								
									$shoptitle = preg_replace('/[^\w\s]+/u',' ',$srows['shop_name']);
									$shoptitle = str_replace(' ', '-', $shoptitle);
									$shoptitle = trim(preg_replace('/-+/', '-', $shoptitle), '-');
									$shopurls = base_url() . 'shop-preview/' . strtolower($shoptitle) . '?sid=' . md5($srows['id']);

									$country = $this->db->get_where('countries', array('id'=>$srows['country']))->row()->country_name;
	                                $state = $this->db->get_where('state', array('id'=>$srows['state']))->row()->name;
	                                $city = $this->db->get_where('city', array('id'=>$srows['city']))->row()->name;
	                                $location = $city .', '.$state;

                                ?>
								
									<div class="col-lg-3 col-md-6">
										<div class="shop-widget">
											<div class="shop-wrap">
												<div class="shop-img">
													<a href="<?php echo $shopurls;?>">
														<?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
													<?php } else { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url().settingValue('service_placeholder_image');?>">
													<?php } ?>
													</a>											</a>
												</div>
												<div class="shop-det">
													<h3><a href="<?php echo $shopurls;?>"><?php echo $srows['shop_name'];?></a></h3>
													<div class="shop-cate">
													</div>
													<div class="shop-cate"><?php if($srows['category_name'] != '') { ?>
                                                            <?php echo $srows['category_name'];?>
                                                        <?php } ?></div>
													<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo $location; ?></div>

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
									</div>
									<?php } }
									else{
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo '<div class="col-lg-12">
												<p class="mb-0">'.$norecord.'</p>
											</div>';
									} ?>
                                  
					<!-- Pagination Links -->
					<?php 
					if(!empty($shops)){
						echo $this->ajax_pagination->create_links();
					} ?>
					<script src="<?php echo base_url();?>assets/js/functions.js"></script>