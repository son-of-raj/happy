<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Shops'])) ? $user_language[$user_selected]['lg_My_Shops'] : $default_language['en']['lg_My_Shops']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_My_Shops'])) ? $user_language[$user_selected]['lg_My_Shops'] : $default_language['en']['lg_My_Shops']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
$defcurrency = currency_conversion(settings('currency'));
$user_currency = get_provider_currency(); 
$user_currency_code = $user_currency['user_currency_code'];
$currency = $user_currency['user_currency_sign'];
						
$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
        if ($data['key'] == 'stripe_option') {
            $stripe_option = $data['value'];
        }
    }
}

if($stripe_option == 1) {
    $stripe_key=settingValue('publishable_key');
} else {
    $stripe_key=settingValue('live_publishable_key');
}
$web_logo = (settingValue('logo_front'))?base_url().settingValue('logo_front'):base_url() . 'assets/img/logo.png';
$shoppay = $this->db->where('user_provider_id',$this->session->userdata('id'))->where("reason LIKE 'Add Shop'")->get('moyasar_table')->num_rows();
$getShop = $this->db->where('provider_id', $this->session->userdata('id'))->get('shops')->num_rows();
$getShop = $getShop - 1;
?>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu');?>
               
            
            <div class="col-xl-9 col-md-8">
                <div class="row align-items-center mb-4">
                    <div class="col">
    				    <h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_My_Shops'])) ? $user_language[$user_selected]['lg_My_Shops'] : $default_language['en']['lg_My_Shops']; ?></h4>
    				</div>

                    <div class="col-auto">
                        <div class="addnewdiv text-end">
        					<?php if($shop_fee > 0 && $getShop == $shoppay) { ?>        					
        					<h6><a href="javascript:void(0);" id="shop_pay" name="shop_pay" class="shopfee btn btn-primary text-white"><i class="fas fa-plus me-2"></i><?php echo (!empty($user_language[$user_selected]['lg_Add_Shop'])) ? $user_language[$user_selected]['lg_Add_Shop'] : $default_language['en']['lg_Add_Shop']; ?></a></h6>
        					
        					<input type="hidden" id="callback_url" value="<?php echo base_url()."user/shop/shop_payment/";?>" />
        					<input type="hidden" id="moyasar_api_key" value="<?php echo  $moyaser_apikey; ?>"/>
        					<input type="hidden" id="amount"   value="<?php echo $shop_fee * 100; ?>" />
        					<input type="hidden" id="currency"  value="<?php echo $user_currency_code; ?>" />
        					<input type="hidden" value="<?php  echo (!empty($user_language[$user_selected]['lg_Shop_Fee'])) ? $user_language[$user_selected]['lg_Shop_Fee'] : $default_language['en']['lg_Shop_Fee']; ?>" id="paytitle" />

        					
        				<?php } else { ?>
        					<h6><a href="<?php echo base_url()?>add-shop" class="btn btn-primary text-white"><i class="fas fa-plus me-2"></i><?php echo (!empty($user_language[$user_selected]['lg_Add_Shop'])) ? $user_language[$user_selected]['lg_Add_Shop'] : $default_language['en']['lg_Add_Shop']; ?></a></h6>
        				<?php } ?>
        				</div>

                    
                    <div class="row mt-2" id="payment-types" style="display:none;">
                        <h6>Select Payment Type</h6>
                        <?php  if(!empty($paypal_option_status)) { ?>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="shop_payment_type" id="paypal" value="paypal">
                            <img src="<?php echo base_url() . "assets/img/paypal.png"; ?>">
                        </div>
                        <?php } if(!empty($stripe_option_status)) { ?>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="shop_payment_type" id="stripe"  value="stripe">
                            <img src="<?php echo base_url() . "assets/img/stripe.png"; ?>">
                        </div>
                        <?php } if(!empty($razor_option_status)) { ?>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="shop_payment_type" id="razorpay"  value="razorpay">
                            <img src="<?php echo base_url() . "assets/img/razorpay.png"; ?>">
                        </div>
                        <?php }  ?>
                        
                        <?php if(!empty($moyasar_option_status)) { ?>
                        <div class="col-4">
                            <input class="form-check-input" type="radio" name="shop_payment_type" id="moyasarpay"  value="moyasarpay"><label><?php echo (!empty($user_language[$user_selected]['lg_Moyasar'])) ? $user_language[$user_selected]['lg_Moyasar'] : $default_language['en']['lg_Moyasar']; ?></label>
                        </div>
                        <?php } ?>
                        
                    </div>
                </div>
                </div>
				
				<ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url() ?>shop"><?php echo (!empty($user_language[$user_selected]['lg_Active_Shops'])) ? $user_language[$user_selected]['lg_Active_Shops'] : $default_language['en']['lg_Active_Shops']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url() ?>my-shop-inactive"><?php echo (!empty($user_language[$user_selected]['lg_Inactive_Shops'])) ? $user_language[$user_selected]['lg_Inactive_Shops'] : $default_language['en']['lg_Inactive_Shops']; ?></a>
                    </li>
                </ul>
                <div>
                    <div class="row" id="dataList">

                        <?php 
                        if (!empty($shops)) {

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
                                <?php
                            }
                        } else {
							$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
                            echo '<div class="col-lg-12">
									<p class="mb-0 text-center">'.$norecord.'</p>
								</div>';
                        }

                        echo $this->ajax_pagination->create_links();
                        ?>



                    </div>
                </div>
                <!-- Paypal Details -->
                <?php $provider_details = $this->db->get_where('providers', array('id'=>$this->session->userdata('id')))->row_array();
            $form_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; ?>
            <form name="addshop_paypal_detail" id="addshop_paypal_detail" action="<?php echo$form_url?>" method="POST">
                                
                <input type='hidden' name='business' value="<?php echo $this->session->userdata('email'); ?>"> 
                <input type='hidden' name='item_number' value="123456"> 
                <input type='hidden' name='amount' value='<?php echo settingValue('shop_fee'); ?>'> 
            <input type='hidden' name='currency_code' value='USD'>
            <input type='hidden' name='return' value="<?php echo base_url() ?>user/shop/paypal_shop_payment/<?php echo settingValue('shop_fee'); ?>">
            <input type="hidden" name="cmd" value="_xclick">  
                    <input type="hidden" id="paypal_gateway" value="<?php echo $paypal_gateway; ?>">
                    <input type="hidden" id="braintree_key" value="<?php echo $braintree_key; ?>">
                    
                    <input type="hidden" id="razorpay_apikey" value="<?php echo $razorpay_apikey; ?>">

                    <input type="hidden" id="username" value="<?php echo $provider_details['name']; ?>">
                    <input type="hidden" id="mobileno1" value="<?php echo $provider_details['mobileno']; ?>">


                    <input type="hidden" id="state" value="<?php echo (!empty($state)) ? $state : "IL"; ?>">
                    <input type="hidden" id="country" value="<?php echo (!empty($country)) ? $country : "US"; ?>">
                    <input type="hidden" id="pincode" value="<?php echo (!empty($provider_details['pincode'])) ? $user_details['pincode'] : "60652"; ?>">
                    <input type="hidden" id="address" value="<?php echo (!empty($provider_details['address'])) ? $user_details['address'] : "1234 Main St."; ?>"><input type="hidden" id="city" value="<?php echo (!empty($city)) ? $city : "Chicago"; ?>">
                </form> 
                <!-- Paypal Details -->

                <!-- Stripe Details -->
                <input type="hidden" id="stripe_key" value="<?php echo $stripe_key; ?>">
                <input type="hidden" id="logo_front" value="<?php echo $web_logo; ?>">
                <input type="hidden" id="shop_fee" value="<?php echo settingValue('shop_fee'); ?>">
                <input type="hidden" id="shop_currency" value="<?php echo $user_currency_code; ?>">
                <button id="myshop_stripe_payment" style="display: none;">Purchase</button>
                <!-- Stripe Details -->

            </div>					
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg"></p>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success si_inactive_confirm">Yes</a>
                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Inactive Shop?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Service and staff related to this shop is active. Cannot deactivate the shop...</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>