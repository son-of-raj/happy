
						
						<?php
							$this->session->flashdata('success_message');
							if(!empty($services)){
								foreach ($services as $srows) {
									$mobile_image=explode(',', $srows['mobile_image']);
									$this->db->select("service_image");
									$this->db->from('services_image');
									$this->db->where("service_id",$srows['id']);
									$this->db->where("status",1);
									$this->db->order_by('is_default','DESC');
									$image = $this->db->get()->row_array(); 
									if(!empty($image['service_image']) && file_exists($image['service_image'])){
										$serviceimage = base_url().$image['service_image'];
									}else{
										$serviceimage = "https://via.placeholder.com/360x220.png?text=Service%20Image";
									} 
									$this->db->select('AVG(rating)');
									$this->db->where(array('service_id'=>$srows['id'],'status'=>1));
									$this->db->from('rating_review');
									$rating = $this->db->get()->row_array();
									$avg_rating = round($rating['AVG(rating)'],1);    
									$provider_details = $this->db->where('id',$srows['user_id'])->get('providers')->row_array();
									$service_availability=$this->db->where('service_id',$srows['id'])->where('status!=',6)->where('status!=',7)->from('book_service')->count_all_results();
									
									$user_currency_code = '';
									$userId = $this->session->userdata('id');
									If (!empty($userId)) {
										$service_amount = $srows['service_amount'];
										$user_currency = get_provider_currency();
										$user_currency_code = $user_currency['user_currency_code'];

										$service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
									} else {
										$user_currency_code = settings('currency');
										$service_amount = $srows['service_amount'];
									}
									$datecondt = "start_date <= curdate() and end_date >= curdate()";
									$soffer = $this->db->select('id, end_date, start_time, end_time, offer_percentage')->where("service_id",$srows['id'])->where("provider_id",$userId)->where("df",0)->where($datecondt)->get("service_offers")->row_array();
									
									$servicetitle = preg_replace('/[^\w\s]+/u',' ',$srows['service_title']);
									$servicetitle = str_replace(' ', '-', $servicetitle);
									$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
									$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($srows['id']);

                                ?>
								
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-shop mb-4 w-100">
                                    <div class="feature-img">
										<a href="<?php echo $service_url; ?>">
                                            <img class="categorie-img" alt="Service Image" src="<?php echo $serviceimage; ?>">
                                        </a> 
                                        <div class="feature-bottom">
                                            <a href="javascript:void(0);">
                                                <?php if ($provider_details['profile_img'] == '') { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url().settingValue('profile_placeholder_image') ?>">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
                                                <?php } ?>
                                            </a>
                                             <p>
                                                <a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_slug'])); ?>"><?php echo $srows['category_name']; ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="featute-info featute-info1">
                                        <h4>
                                           <a href="<?php echo $service_url; ?>"><?php echo $srows['service_title']; ?></a> 
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
                                      <h6><?php echo currency_conversion($user_currency_code) . $service_amount; ?></h6>
                                      <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo ($srows['service_location'])?ucfirst($srows['service_location']):$shplocation; ?></p>
                                   
                                    <div class="service-content service-content1">
                                        <div class="user-info">
                                            <div class="service-action">
                                                <div class="row">
                                                    <div class="col"><a href="<?php echo base_url() ?>user/service/edit_service/<?php echo $srows['id'] ?>" class="text-success"><i class="far fa-edit"></i> Edit</a></div>
                                                    <?php if ($service_availability == 0) { ?>

                                                        <div class="col text-end"><a href="javascript:void(0);" class="si-delete-service text-danger" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Inactive</a></div>
                                                    <?php } else { ?>
                                                        <div class="col text-end"><a href="javascript:void(0);" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Inactive</a></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row mt-2">
                                                    <?php
                                                    if ($soffer['id']!='' && ($soffer['end_date'] >= date('Y-m-d')) && ($cur_time >= $soffer['start_time']) && ($cur_time <= $soffer['end_time'])) {
                                                        ?>
                                                        <div class="col">
                                                            Current Offer: <?php echo $soffer['offer_percentage']?>%
                                                        </div>
                                                        <?php 
                                                    }
                                                    else {
                                                    ?>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-info btn-sm" onclick="check_offer('<?php echo $srows['id']; ?>')" data-bs-toggle="modal" data-bs-target="#offer-modal">Apply Offer</button>

                                                        <label class="checkbox-inline float-end">
                                                          <input type="checkbox" class="sids" value="<?php echo $srows['id']; ?>">&nbsp;Select
                                                        </label>
                                                    </div>
                                                    <?php 
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>											
                                    </div>
									</div>
                                </div>								
                            </div>
									<?php } }
									else{										
										$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
										echo '<div class="col-xl-12 col-lg-12">
											<p class="mb-0 text-center">'.$norecord.'</p>
										</div>';
									} ?>
                                  
					<!-- Pagination Links -->
					<?php 
					if(!empty($services)){
						echo $this->ajax_pagination->create_links();
					} ?>
					<script src="<?php echo base_url();?>assets/js/functions.js"></script>