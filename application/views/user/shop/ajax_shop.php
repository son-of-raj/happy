
						
						<?php
							$this->session->flashdata('success_message');
							if(!empty($shops)){
								foreach ($shops as $srows) {
									
									                                $this->db->select("shop_image");
								$this->db->from('shops_images');
								$this->db->where("shop_id", $srows['id']);
								$this->db->where("status", 1);
								$image = $this->db->get()->row_array();
								$shopimages = $image['shop_image'];
								$provider_details = $this->db->where('id', $srows['provider_id'])->get('providers')->row_array();
								
								$sertot=$this->db->where(array('shop_id' => $srows['id'], 'user_id' => $srows['provider_id'], 'status' => 1))->from('services')->count_all_results();
								$service_availability = $sertot; 
								
								$stftot=$this->db->where(array('shop_id' => $srows['id'], 'provider_id' => $srows['provider_id'], 'status' => 1))->from('employee_basic_details')->count_all_results();
								$shop_availability = $stftot; 	
																
								$shoptitle = preg_replace('/[^\w\s]+/u',' ',$srows['shop_name']);
								$shoptitle = str_replace(' ', '-', $shoptitle);
								$shoptitle = trim(preg_replace('/-+/', '-', $shoptitle), '-');
								$shopurls = base_url() . 'shop-preview/' . strtolower($shoptitle) . '?sid=' . md5($srows['id']);

                                $category = $this->db->get_where('categories', array('id'=>$srows['category']))->row()->category_name;
                                $service_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('services');

                                $product_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('products');
                                
                                ?>
                                <div class="col-lg-4 col-md-6">
									<div class="shop-widget">
										<div class="shop-wrap">
											<div class="shop-img">
												<a href="<?php echo $shopurls; ?>">
													<?php if (!empty($shopimages) && file_exists($shopimages)) { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
													<?php } else { ?>
														<img class="categorie-img" alt="Service Image" src="<?php echo base_url().settingValue('service_placeholder_image');?>">
													<?php } ?>
												</a>											</a>
											</div> 
											<div class="shop-det">
												<h3><a href="<?php echo $shopurls; ?>"><?php echo ucfirst($srows['shop_name']); ?></a></h3>
												<div class="shop-cate"><?php echo $category; ?></div>
													<?php if($srows['category_name'] != '') { ?>
													<a href="<?php echo base_url().'search/'.str_replace(' ', '-', $srows['category_slug']);?>"><?php echo $srows['category_name'];?></a>
													<?php } ?>
												</div>
												<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo ucfirst($srows['shop_location']); ?></div>

										<div class="shop-info-det">
											<ul>
												<li><?php echo $service_count; ?> Services</li>
												<li><?php echo $product_count; ?> Products</li>
											</ul>
										</div>
										<div class="visit-store">
											<a href="<?php echo $shopurls; ?>">Visit Store <i class="feather-arrow-right"></i></a>
										</div>
										<div class="service-action">
                                            <div class="row">
                                                <div class="col text-left"><a href="<?php echo base_url() ?>edit-shop/<?php echo $srows['id'] ?>" class="text-success"><i class="far fa-edit"></i> Edit</a></div>
                                                <?php if($service_availability==0 && $shop_availability==0){?>
                                                    <div class="col text-end"><a href="javascript:void(0);" class="si-inactive-shop text-danger" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Inactive</a></div>
                                                <?php }else{?>
                                                    <div class="col text-end"><a href="javascript:void(0);" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Inactive</a></div>
                                                <?php }?>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col mt-2">
                                                    <a href="<?php echo base_url()?>my-products/<?php echo $srows['id']; ?>" class="btn btn-primary btn-block">View Products</a>
                                                </div>
                                            </div>
                                        </div>
											</div>
										</div>
									</div>
                                <?php } }
									else {
							$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
                            echo '<div class="col-lg-12">
									<p class="mb-0 text-center">'.$norecord.'</p>
								</div>';
                        }

                        echo $this->ajax_pagination->create_links();
						?>
						<script src="<?php echo base_url();?>assets/js/functions.js"></script>