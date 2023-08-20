
						
						<?php
							$this->session->flashdata('success_message');
							if(!empty($branchs)){
								foreach ($branchs as $srows) {
									
									$this->db->select("branch_image");
									$this->db->from('branch_images');
									$this->db->where("branch_id",$srows['id']);
									$this->db->where("status",1);
									$image = $this->db->get()->row_array(); 
									$shopimages = $image['branch_image'];
									$avg_rating = 0;	
									
									$provider_details = $this->db->where('id',$srows['provider_id'])->get('providers')->row_array();
									$sname = $this->db->select('shop_name')->where('id', $srows['shop_id'])->get('shops')->row_array();
									$service_availability = 0;

                                ?>
								
									<div class="col-lg-4 col-md-6">
										<div class="service-widget">
											<div class="service-img">
												<a href="<?php echo base_url().'branch-preview/'.str_replace(' ', '-', $srows['shop_name']).'?sid='.md5($srows['id']);?>">
													<?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
                                                    <img class="img-fluid serv-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
                                                <?php } else { ?>
                                                    <img class="img-fluid serv-img" alt="Shop Image" src="<?php echo base_url().'assets/img/placeholder_shop.png';?>">
                                                <?php } ?>
												</a>
												<div class="item-info">
													<div class="service-user">
														<a href="javascript:void(0);">
															<?php if($provider_details['profile_img'] != '' && file_exists($provider_details['profile_img'])) { ?>
															<img src="<?php echo base_url() . $provider_details['profile_img'] ?>">
															<?php } else { ?>
															<img src="<?php echo base_url(); ?>assets/img/user.jpg">
															<?php } ?>
														</a>
														<span class="service-price"><?php echo currency_conversion(settings('currency')).$srows['service_amount'];?></span>
													</div>
													<div class="cate-list">
														<a class="bg-yellow" href="<?php echo base_url().'search/'.str_replace(' ', '-', strtolower($srows['category_slug']));?>"><?php echo $srows['category_name'];?></a>
													</div>
												</div>
											</div>
											<div class="service-content">
												<h3 class="title" title="Branch Title">
													<a href="<?php echo base_url().'branch-preview/'.str_replace(' ', '-', $srows['branch_name']).'?sid='.md5($srows['id']);?>"><?php echo $srows['branch_name'];?></a>
												</h3>
												<h6 title="Shop Title"><span class="ser-location text-left"><span class="text-info small">
												<a href="<?php echo base_url() . 'shop-preview/' . str_replace(' ', '-', strtolower($sname['shop_name'])) . '?sid=' . md5($srows['shop_id']); ?>"><?php echo ucfirst($sname['shop_name']);?></a>
											</span></span></h6>
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
												<div class="user-info">
													<div class="row">
														<span class="col ser-contact"><i class="fas fa-phone mr-1"></i> <span>xxxxxxxx<?php echo rand(00,99)?></span></span>
															<span class="col ser-location"><span><?php echo $srows['service_location'];?></span> <i class="fas fa-map-marker-alt ml-1"></i></span>
													</div>
													<div class="service-action">
														<div class="row">
															<div class="col"><a href="<?php echo base_url()?>edit-shop/<?php echo $srows['id']?>" class="text-success"><i class="far fa-edit"></i> Edit</a></div>
															<?php if($service_availability==0){?>
															
															<div class="col text-right"><a href="javascript:void(0);" class="si-inactive-branch text-danger" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Inactive</a></div>
														<?php }else{?>
															<div class="col text-right"><a href="javascript:void(0);" class="text-danger" data-toggle="modal" data-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Inactive</a></div>
														<?php }?>
														</div>
													</div>
												</div>											
											</div>
										</div>								
									</div>
									<?php } }
									else{
										echo '<div class="col-xl-12 col-lg-12">No Branches Found</div>';
									} ?>
                                  
					<!-- Pagination Links -->
					<?php 
					if(!empty($branchs)){
						echo $this->ajax_pagination->create_links();
					} ?>