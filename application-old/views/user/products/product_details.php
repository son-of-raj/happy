<?php
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
$user_currency_code = 'USD';
$userId = $this->session->userdata('id');
$type = $this->session->userdata('usertype');
if ($type == 'user') {
    $user_currency = get_user_currency();
} else if ($type == 'provider') {
    $user_currency = get_provider_currency();
} else if ($type == 'freelancer') {
    $user_currency = get_provider_currency();
} 
if(!empty($user_currency['user_currency_code'])){
	$user_currency_code = $user_currency['user_currency_code'];
}

$price = get_gigs_currency($product['price'], $product['currency_code'], $user_currency_code);
$sale_price = get_gigs_currency($product['sale_price'], $product['currency_code'], $user_currency_code);
?>
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_product_details'])) ? $user_language[$user_selected]['lg_product_details'] : $default_language['en']['lg_product_details']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-right ml-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_product_details'])) ? $user_language[$user_selected]['lg_product_details'] : $default_language['en']['lg_product_details']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->
<!-- Product Section-->
<div class="content">
    <div class="container">
	
	
	
	
	
	                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-detail accordion-detail">
                            <div class="row mb-50 mt-30">
                                <div class="col-md-6 col-sm-12 col-xs-12 mb-md-0 mb-sm-5">
								                <div class="detail-gallery">
													<div class="product-image-slider product-img-slider-container">
														<div class="products-slider">
															<?php
															foreach ($pimages as $img) 
															{
															?>
															<div class="product-img">
																<img src="<?php echo base_url()?><?php echo $img['product_image']?>" alt=""/>
															</div>
															<?php
															} 
															?>
														</div>
														<div class="product-slider-nav">
															<?php
															foreach ($pimages as $img) 
															{
															?>
															<div class="product-nav-img">
																<img src="<?php echo base_url()?><?php echo $img['product_image']?>" alt=""/>
															</div>
															<?php
															} 
															?>
														</div>
													</div>
												</div>
												
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="detail-info pr-30 pl-30">
                                        <h2 class="title-detail"><?php echo $product['product_name']?></h2>
										
										<div class="product-by">
											<span>by <a href="#"><?php echo $product['shop_name']; ?></a></span>
										</div>
     
                    <div class="product-category-list">
                        <ul>
                            <li><?php echo (!empty($user_language[$user_selected]['lg_category'])) ? $user_language[$user_selected]['lg_category'] : $default_language['en']['lg_category']; ?>: <?php echo $product['category_name']?></li>
                            <li><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?>: <?php echo $product['subcategory_name']?></li>
                        </ul>
                    </div>
                                        <div class="clearfix product-price-cover">
                                            <div class="product-price primary-color float-left">
												<?php
												if ($product['price'] > $product['sale_price']) 
												{
													?>
													<span class="current-price text-brand"><?php echo currency_conversion($user_currency_code) . $sale_price; ?></span>
													<span>
														<span class="old-price font-md ml-15"><?php echo currency_conversion($user_currency_code) . $price; ?></span>
													</span>
													<?php 
												}
												else
												{
													?>
													<span class="current-price text-brand"><?php echo currency_conversion($user_currency_code) . $price; ?></span>
													<?php 
												}
												?>
                                            </div>
                                        </div>
                                        <div class="short-desc mb-30">
                                            <p><?php echo $product['short_description']?></p>
                                        </div>
										<h6><?php echo (!empty($user_language[$user_selected]['lg_quantity'])) ? $user_language[$user_selected]['lg_quantity'] : $default_language['en']['lg_quantity']; ?>: <span class="text-success">In Stock</span></h6>

										
										<?php
										if($this->session->userdata('usertype') != 'admin') {
											if ($product['unit_value']>0) { ?>
												<div class="cart_extra">
													<div class="cart-product-quantity">
														<div class="quantity">
															<input type="button" value="-" class="button-minus dec button minus"  id="subs">
															<input type="text" name="qty" value="1" title="Qty" class="qty" size="4" id="pqty">
															<input type="button" value="+" class="button-plus inc button plus" id="adds">
														</div>
													</div>
													<div class="cart_btn">
														<button type="button" class="btn btn-prod-addcart add_cart_btn" pid="<?php echo $product['id']?>"><?php echo (!empty($user_language[$user_selected]['lg_add_to_cart'])) ? $user_language[$user_selected]['lg_add_to_cart'] : $default_language['en']['lg_add_to_cart']; ?></button>
														<a href="#" class="btn btn-prod-buynow add_buy_btn" pid="<?php echo $product['id']?>"><?php echo (!empty($user_language[$user_selected]['lg_buy_now'])) ? $user_language[$user_selected]['lg_buy_now'] : $default_language['en']['lg_buy_now']; ?></a>
													</div>
												</div>
												
											<?php
											} 
										} ?>   
                                    </div>
                                    <!-- Detail Info -->
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="tab-style3">
                                    <ul class="nav nav-tabs text-uppercase">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Description-tab" data-bs-toggle="tab" href="#Description"><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?></a>
                                        </li>
                                    </ul>
                                    <div class="tab-content shop_info_tab entry-main-content">
                                        <div class="tab-pane fade show active" id="Description">
                                            <div class="">
                                                <?php echo $product['description']?>
                                            </div>
                                        </div>
										
                                    </div>
                                </div>
                            </div>
							
                        </div>
                    </div>
                </div>
	
	
    </div>
</div>
<!-- /Product Section-->
