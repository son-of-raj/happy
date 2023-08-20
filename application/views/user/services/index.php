<?php
$this->db->select_min('service_amount');
$this->db->from('services');
$min_price = $this->db->get()->row_array();

$this->db->select_max('service_amount');
$this->db->from('services');
$max_price = $this->db->get()->row_array();

$currency = currency_conversion(settings('currency'));

$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
    }
}

?>

<div class="content">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="sorting-main">
                <div class="sorting">
                    <ul class="nav nav-tabs menu-tabs">
                        <li class="nav-item active">
                            <a class="nav-link" id="shops-tab" data-bs-toggle="tab" href="<?php ?>" role="tab" aria-controls="shops" aria-selected="true"><img class="me-2" src="<?php echo base_url();?>assets/img/icon12.png" alt="img"><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link test" id="services-tab" data-bs-toggle="tab" href="#services" role="tab" aria-controls="services" aria-selected="true"><img class="me-2" src="<?php echo base_url();?>assets/img/icon13.png" alt="img"><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></a>
                        </li>
                    </ul>
                </div>
                <div class="sort-by">
                    <div class="sort-btn">
                        <a class="btn filter-btn" href="javascript:void(0);" id="filter_search"><img class="me-2" src="<?php echo base_url();?>assets/img/filter-icon.png" alt="img"></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Filter -->
        <div class="filter-card" id="filter_inputs">
            <form id="search_form">
                <div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></label>
								<input class="form-control location" type="text" id="service_location" value="<?php if (isset($_POST["user_address"]) && !empty($_POST["user_address"])) echo $_POST["user_address"]; ?>" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_Search_Location'])) ? $user_language[$user_selected]['lg_Search_Location'] : $default_language['en']['lg_Search_Location']; ?>" name="user_address" >
								<input type="hidden" value="<?php if (isset($_POST["user_latitude"]) && !empty($_POST["user_latitude"])) echo $_POST["user_latitude"]; ?>" id="service_latitude">
								<input type="hidden" value="<?php if (isset($_POST["user_longitude"]) && !empty($_POST["user_longitude"])) echo $_POST["user_longitude"]; ?>" id="service_longitude">
							</div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
							<div class="form-group">
								<label class="filter-title"><?php echo (!empty($user_language[$user_selected]['lg_Sort_By'])) ? $user_language[$user_selected]['lg_Sort_By'] : $default_language['en']['lg_Sort_By']; ?></label>
								<select id="sort_by" class="form-control selectbox select">
									<option value=""><?php echo (!empty($user_language[$user_selected]['lg_Sort_By'])) ? $user_language[$user_selected]['lg_Sort_By'] : $default_language['en']['lg_Sort_By']; ?></option>
									<option value="1"><?php echo (!empty($user_language[$user_selected]['lg_Price_Low_High'])) ? $user_language[$user_selected]['lg_Price_Low_High'] : $default_language['en']['lg_Price_Low_High']; ?></option>
									<option value="2"><?php echo (!empty($user_language[$user_selected]['lg_Price_High_Low'])) ? $user_language[$user_selected]['lg_Price_High_Low'] : $default_language['en']['lg_Price_High_Low']; ?></option>
									<option value="3"><?php echo (!empty($user_language[$user_selected]['lg_Newest'])) ? $user_language[$user_selected]['lg_Newest'] : $default_language['en']['lg_Newest']; ?></option>
								</select>
							</div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
							<div class="form-group">
								<label class="filter-title"><?php echo (!empty($user_language[$user_selected]['lg_Keyword'])) ? $user_language[$user_selected]['lg_Keyword'] : $default_language['en']['lg_Keyword']; ?></label>
								<input type="text" id="common_search" value="<?php if (isset($_POST["common_search"]) && !empty($_POST["common_search"])) echo $_POST["common_search"]; ?>" class="form-control common_search location" placeholder="<?php echo (!empty($user_language[$user_selected]['lg_looking_for'])) ? $user_language[$user_selected]['lg_looking_for'] : $default_language['en']['lg_looking_for']; ?>" />
							</div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
							<div class="form-group">
								<label class="filter-title"><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?></label>
								<select id="categories" class="form-control form-control selectbox select">
									<option value=""><?php echo (!empty($user_language[$user_selected]['lg_all_categories'])) ? $user_language[$user_selected]['lg_all_categories'] : $default_language['en']['lg_all_categories']; ?></option>
									<?php
									foreach ($category as $crows) {
										$selected = '';
										if (isset($category_id) && !empty($category_id)) {
											if ($crows['id'] == $category_id) {
												$selected = 'selected';
											}
										}
										echo'<option value="' . $crows['id'] . '" ' . $selected . '>' . $crows['category_name'] . '</option>';
									}
									?>
								</select>
							</div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
							<div class="form-group">
								<label class="filter-title"><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?></label>
								<select id="subcategories" class="form-control form-control selectbox select">
									<option value=""><?php echo (!empty($user_language[$user_selected]['lg_Choose_the_Sub_Category'])) ? $user_language[$user_selected]['lg_Choose_the_Sub_Category'] : $default_language['en']['lg_Choose_the_Sub_Category']; ?></option>
									<?php
									foreach ($subcategory as $crows) {
										$selected = '';
										if (isset($subcategory_id) && !empty($subcategory_id)) {
											if ($crows['id'] == $subcategory_id) {
												$selected = 'selected';
											}
										}
										echo'<option value="' . $crows['id'] . '" ' . $selected . '>' . $crows['subcategory_name'] . '</option>';
									}
									?>
								</select>
							</div>
                        </div>
                            <div class="col-lg-4 col-sm-6 col-12">
								<div class="form-group">
									<label class="filter-title"><?php echo (!empty($user_language[$user_selected]['lg_Price_Range'])) ? $user_language[$user_selected]['lg_Price_Range'] : $default_language['en']['lg_Price_Range']; ?></label>
									<div class="price-ranges">
										<?php echo currency_conversion(settings('currency')); ?><span class="from d-inline-block" id="min_price"><?php echo $min_price['service_amount'] ?></span> -
										<?php echo currency_conversion(settings('currency')); ?><span class="to d-inline-block" id="max_price"><?php echo $max_price['service_amount'] ?></span>
									</div>  
									<div class="range-slider price-range"></div>                                        
								</div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="hidden-xs">&nbsp;</label>
                                    <button class="btn btn btn-search btn-block get_services" type="button"><?php echo (!empty($user_language[$user_selected]['lg_search'])) ? $user_language[$user_selected]['lg_search'] : $default_language['en']['lg_search']; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /Search Filter -->

        <div class="container">
            <div class="row">
                 <div class="tab-content">
                    <div class="tab-pane fade show active" id="shops" role="tabpanel" aria-labelledby="shops-tab">  
                        <div class="row align-items-center mb-4">
                            <div class="counts">
                                <h3><span id="shop_count"><?php echo 0; ?></span> <?php echo (!empty($user_language[$user_selected]['lg_Shop_Found'])) ? $user_language[$user_selected]['lg_Shop_Found'] : $default_language['en']['lg_Shop_Found']; ?></h3>
                            </div>
                          </div>
                        <div>
                        <div class="row" id="dataListShop">
                                <?php                           
                              
                            if(!empty($shops)){
                                foreach ($shops as $srows) {

                                    $category = $this->db->get_where('categories', array('id'=>$srows['category']))->row()->category_name;
                                	$service_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('services');
                                	$product_count = $this->db->where('shop_id', $srows['id'])->where('status',1)->count_all_results('products');

                                    $this->db->select("shop_image");
                                    $this->db->from('shops_images');
                                    $this->db->where("shop_id",$srows['id']);
                                    $this->db->where("status",1);
                                    $image = $this->db->get()->row_array(); 
                                    $shopimages = $image['shop_image'];
                                    $avg_rating = 0;    
                                    
                                    $provider_details = $this->db->where('id',$srows['provider_id'])->get('providers')->row_array();
                                
                                    $shoptitle = preg_replace('/[^\w\s]+/u',' ',$srows['shop_name']);
                                    $shoptitle = str_replace(' ', '-', $shoptitle);
                                    $shoptitle = trim(preg_replace('/-+/', '-', $shoptitle), '-');
                                    $shopurls = base_url() . 'shop-preview/' . strtolower($shoptitle) . '?sid=' . md5($srows['id']);

                                ?>
                                
                                    <div class="col-lg-3 col-md-6">
										<div class="shop-widget">
											<div class="shop-wrap">
												<div class="shop-img">
													<a href="<?php echo $shopurls;?>">
														<?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
													<?php } else { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url().'assets/img/placeholder_shop.png';?>">
													<?php } ?>
													</a>											</a>
												</div>
												<div class="shop-det">
													<h3><a href="<?php echo $shopurls;?>"><?php echo $srows['shop_name'];?></a></h3>
													<div class="shop-cate"><?php echo $category; ?></div>
													<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo $srows['shop_location']; ?></div>
														<?php if($srows['category_name'] != '') { ?>
                                                        <a href="<?php echo base_url().'search/'.str_replace(' ', '-', $srows['category_slug']);?>"><?php echo $srows['category_name'];?></a>
                                                        <?php } ?>
													</div>
													<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i>Bristol,Virginia</div>
													
												</div>
											<div class="shop-info-det">
												<ul>
													<li><?php echo $service_count; ?> Services</li>
													<li><?php echo $product_count; ?> Products</li>
												</ul>
											</div>
											<div class="visit-store">
												<a href="<?php echo $shopurls;?>">Visit Store <i class="feather-arrow-right"></i></a>
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


                            </div>
                        </div>
                    
                    
                    </div> <!-- Shop Tab -->
                    
                    <div class="tab-pane fade " id="services" role="tabpanel" aria-labelledby="services-tab">
                        <div class="row align-items-center mb-4">
                            <div class="counts">
                                <h3><span id="service_count"><?php echo $count; ?></span> <?php echo (!empty($user_language[$user_selected]['lg_Services_Found'])) ? $user_language[$user_selected]['lg_Services_Found'] : $default_language['en']['lg_Services_Found']; ?></h3>
                            </div>
                        </div>
                        <div>
						<div class="row" id="dataList">



						</div>
                    </div>
                    </div> <!-- Service Tab -->
                
                    
                
                
                </div> <!-- Tab-Content-->
                <input type="hidden" value="service" id="searchfor">
            </div>                  
        </div>
    </div>
    </div>
</div>


