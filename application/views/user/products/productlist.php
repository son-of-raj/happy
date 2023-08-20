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
$user_currency_code = '';
$userId = $this->session->userdata('id');
$type = $this->session->userdata('usertype');
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
?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2>
                        <?php echo (!empty($user_language[$user_selected]['lg_Product_List'])) ? $user_language[$user_selected]['lg_Product_List'] : $default_language['en']['lg_Product_List']; ?>
                    </h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url();?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item"><a href="<?php echo base_url()."user-dashboard"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></a></li>
                        
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Product_List'])) ? $user_language[$user_selected]['lg_Product_List'] : $default_language['en']['lg_Product_List']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Shop Section-->
<div class="content">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="filters-section">
                    <div class="filter-card">
                        <h5 class="mb-3"><?php echo (!empty($user_language[$user_selected]['lg_all_categoriess'])) ? $user_language[$user_selected]['lg_all_categories'] : $default_language['en']['lg_all_categories']; ?></h5>
                        <ul class="category-list">
                            <li><a href="#" id="all" class="catid">All</a></li>
                            <?php
                            if (!empty($catlist)) 
                            {
                                foreach ($catlist as $c) 
                                {
                                    ?>
                                    <li><a href="#" id="<?php echo $c['id']?>" class="catid"><?php echo $c['category_name']?></a></li>
                                    <?php 
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="filter-card">
                        <h5 class="mb-3"><?php echo (!empty($user_language[$user_selected]['lg_price'])) ? $user_language[$user_selected]['lg_price'] : $default_language['en']['lg_price']; ?> (<?php echo currency_conversion($user_currency_code)?>)</h5>
                        <div class="selection-filter">
                            <label class="custom-radio me-2"><?php echo (!empty($user_language[$user_selected]['lg_any_price'])) ? $user_language[$user_selected]['lg_any_price'] : $default_language['en']['lg_any_price']; ?>
                                <input type="radio" name="radio" value="any" class="pricefilter">
                                <span class="checkmark"></span>
                            </label>
                            <label class="custom-radio me-2"><?php echo (!empty($user_language[$user_selected]['lg_under'])) ? $user_language[$user_selected]['lg_under'] : $default_language['en']['lg_under']; ?> <?php echo currency_conversion($user_currency_code).get_gigs_currency(25, 'USD', $user_currency_code)?>
                                <input type="radio" name="radio" cc="<?php echo $user_currency_code?>" value="<?php echo get_gigs_currency(0, 'USD', $user_currency_code)?>-<?php echo get_gigs_currency(25, 'USD', $user_currency_code)?>" class="pricefilter">
                                <span class="checkmark"></span>
                            </label>
                            <label class="custom-radio me-2"><?php echo currency_conversion($user_currency_code).get_gigs_currency(25, 'USD', $user_currency_code)?> to <?php echo currency_conversion($user_currency_code).get_gigs_currency(50, 'USD', $user_currency_code)?>
                                <input type="radio" name="radio" cc="<?php echo $user_currency_code?>" value="<?php echo get_gigs_currency(25, 'USD', $user_currency_code)?>-<?php echo get_gigs_currency(50, 'USD', $user_currency_code)?>" class="pricefilter">
                                <span class="checkmark"></span>
                            </label>
                            <label class="custom-radio me-2"><?php echo currency_conversion($user_currency_code).get_gigs_currency(50, 'USD', $user_currency_code)?> to <?php echo currency_conversion($user_currency_code).get_gigs_currency(100, 'USD', $user_currency_code)?>
                                <input type="radio" name="radio" cc="<?php echo $user_currency_code?>" value="<?php echo get_gigs_currency(50, 'USD', $user_currency_code)?>-<?php echo get_gigs_currency(100, 'USD', $user_currency_code)?>" class="pricefilter">
                                <span class="checkmark"></span>
                            </label>
                            <label class="custom-radio me-2"><?php echo (!empty($user_language[$user_selected]['lg_over'])) ? $user_language[$user_selected]['lg_over'] : $default_language['en']['lg_over']; ?> <?php echo currency_conversion($user_currency_code).get_gigs_currency(100, 'USD', $user_currency_code)?>
                                <input type="radio" name="radio" cc="<?php echo $user_currency_code?>" value="<?php echo get_gigs_currency(100, 'USD', $user_currency_code)?>-<?php echo get_gigs_currency(0, 'USD', $user_currency_code)?>" class="pricefilter">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Sidebar -->
            <input type="hidden" id="f_cat_id" value="">
            <input type="hidden" id="f_pricerange" value="">
            <input type="hidden" id="f_cc" value="">
            <input type="hidden" id="pqty" value="1">
            <input type="hidden" id="orderby" value="">
            <input type="hidden" id="usertype" value="<?php echo $type; ?>">
            <div class="col-lg-9">	 
				
                <div class="shop-list-wrapper row" id="dataList">
                    
                    <?php
                    if (!empty($products)) 
                    {
                        foreach ($products as $val)
                        {
                            $val['currency_code'] = ($val['currency_code'])?$val['currency_code']:settingValue('currency_option');
                            $price = get_gigs_currency($val['price'], $val['currency_code'], $user_currency_code);
                            $sale_price = get_gigs_currency($val['sale_price'], $val['currency_code'], $user_currency_code);
                            $discount = get_gigs_currency($val['discount'], $val['currency_code'], $user_currency_code);

                            $category_name = $this->db->get_where('categories', array('id'=>$val['category']))->row()->category_name;
                            
                            $shop_name = $this->db->get_where('shops', array('id'=>$val['shop_id']))->row()->shop_name;

                            if($type == 'provider') {
                                $product_url = base_url().'edit-product/'.$val['shop_id'].'/'.$val['id'];
                            } else {
                                $product_url = base_url().'product-details/'.encrypt_url($val['id'],$this->config->item('encryption_key'));
                            }
                            ?>
                            <!-- Product Item-->
                            <div class="col-lg-4 col-12 col-sm-6">
								<div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img">
                                            <a href="<?php echo $product_url; ?>">
												<img class="default-img" alt="Product Image" src="<?php echo base_url()?><?php echo $val['product_image']?>">
												<?php
												if ($val['unit_value']==0 || $val['unit_value']<0) {
												?>
												<div class="out-stock-col">
													<img src="<?php echo base_url();?>assets/img/out-stock.png" alt="">
												</div>
												<?php 
												}
												?>
                                            </a>
                                        </div>
										<?php
										if ($val['discount']!=0) {
											?>
											<div class="product-badges product-badges-position product-badges-mrg">
												<span class="new"><?php echo currency_conversion($user_currency_code).$discount?> OFF</span>
											</div>
											<?php 
										}
										?>
                                    </div>
                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="#"><?php echo $category_name; ?></a>
                                        </div>
										<h2>
                                            <a href="<?php echo base_url()?>product-details/<?php echo encrypt_url($val['id'],$this->config->item('encryption_key'))?>" tabindex="0"><?php echo $val['product_name']?></a>
                                        </h2>
                                        <div class="product-by">
                                            <span class="font-small text-muted">By <a href="#"><?php echo $shop_name; ?></a></span>
                                        </div>
                                        <div class="product-card-bottom">
                                            <div class="product-price">
												
                                            <?php
                                            if ($val['price'] > $val['sale_price']) 
                                            {
                                                ?>
                                                <span><?php echo currency_conversion($user_currency_code) . $sale_price; ?></span>
                                                <span class="old-price"><?php echo currency_conversion($user_currency_code) . $price; ?></span>
                                                <?php 
                                            }
                                            else
                                            {
                                                ?>
                                                <span><?php echo currency_conversion($user_currency_code) . $price; ?></span>
                                                <?php 
                                            }
                                            ?>
                                            </div>
                                        </div>
										<?php
                                        if($this->session->userdata('usertype') != 'admin') {
                                            if ($val['unit_value']>0) {
                                                if($type == 'user' || $type == '') { ?>
                                                <div class="product-card-bottom">
    												<div class="add-cart">
                                                    <a href="javascript:void(0);" class="cart-btn add_cart_btn add" pid="<?php echo $val['id']?>">
    													<?php echo (!empty($user_language[$user_selected]['lg_add_to_cart'])) ? $user_language[$user_selected]['lg_add_to_cart'] : $default_language['en']['lg_add_to_cart']; ?></a>
                                                    </div>
    											</div>
                                            <?php } else { ?>
    											<div class="product-card-bottom">
    												<div class="add-cart">
    												<a class="add" href="<?php echo base_url()?>edit-product/<?php echo $val['shop_id'].'/'.$val['id']?>" class="cart-btn">Edit</a>
    												</div>
    											</div>
                                            <?php }
                                            } 
                                        }
                                        ?>
                                    </div>
                                </div>
							</div>
                            <!-- /Product Item-->
                            <?php 
                        }
                    } 
                    ?>
                
                <?php
                echo $this->ajax_pagination_new->create_links();
                ?>    
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- /Product Section--> 