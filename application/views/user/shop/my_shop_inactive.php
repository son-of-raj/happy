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

$shoppay = $this->db->where('user_provider_id',$this->session->userdata('id'))->where("reason LIKE 'Add Shop'")->get('moyasar_table')->num_rows();
$getShop = $this->db->where('provider_id', $this->session->userdata('id'))->get('shops')->num_rows();
$getShop = $getShop - 1;
?>

<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu'); ?>
            <div class="col-xl-9 col-md-8">

            	<div class="row align-items-center mb-4">
            		<div class="col">
		                <h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_My_Shops'])) ? $user_language[$user_selected]['lg_My_Shops'] : $default_language['en']['lg_My_Shops']; ?></h4>
		            </div>
		        	<div class="col-auto"> 
						<div class="addnewdiv">
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
				</div>
			</div>
				
                <ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url() ?>shop"><?php echo (!empty($user_language[$user_selected]['lg_Active_Shops'])) ? $user_language[$user_selected]['lg_Active_Shops'] : $default_language['en']['lg_Active_Shops']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url() ?>my-shop-inactive"><?php echo (!empty($user_language[$user_selected]['lg_Inactive_Shops'])) ? $user_language[$user_selected]['lg_Inactive_Shops'] : $default_language['en']['lg_Inactive_Shops']; ?></a>
                    </li>
                </ul>
                <div class="row" id="dataList">


                    <?php
                    $this->session->flashdata('success_message');
                    if (!empty($shops)) {

                            foreach ($shops as $srows) {                                

                                $this->db->select("shop_image");
								$this->db->from('shops_images');
								$this->db->where("shop_id", $srows['id']);
								$this->db->where("status", 2);
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
                                $service_count = $this->db->where('shop_id', $srows['id'])->count_all_results('services');

                                $product_count = $this->db->where('shop_id', $srows['id'])->count_all_results('products');
                            ?>

                            <div class="col-lg-4 col-md-6 inactive-service">
							
									<div class="shop-widget">
										<div class="shop-wrap">
											<div class="shop-img">
												<a href="<?php echo $shopurls; ?>">
													<?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
													<?php } else { ?>
														<img class="categorie-img" alt="Shop Image" src="<?php echo base_url().'assets/img/placeholder_shop.png';?>">
													<?php } ?>
												</a>													</a>											
											</div>
											<div class="shop-det">
												<h3><a href="<?php echo $shopurls; ?>"><?php echo $srows['shop_name']; ?></a></h3>
												<div class="shop-cate"><?php echo $category; ?></div>
													<?php if($srows['category_name'] != '') { ?>
													<a href="<?php echo base_url().'search/'.str_replace(' ', '-', $srows['category_slug']);?>"><?php echo $srows['category_name'];?></a>
													<?php } ?>
												</div>
												<div class="shop-location"><i class="fas fa-map-marker-alt me-1"></i><?php echo $srows['shop_location']; ?></div>

											
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
												<?php if($service_availability==0 && $shop_availability==0){?>
													<div class="col text-left"><a href="javascript:void(0)" class="si-delete-inactive-shop text-danger" data-id="<?php echo $srows['id']; ?>"><i class="far fa-trash-alt"></i> Delete</a></div>
												<?php } else {  ?>
													<div class="col"><a href="javascript:void(0)" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Delete</a></div>
												<?php } ?>
												<div class="col text-end"><a href="javascript:void(0)" class="si-active-shop text-success" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Active</a></div>

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
                    ?>

                    <!-- Pagination Links -->
                    <?php
                    if (!empty($shops)) {
                        echo $this->ajax_pagination->create_links();
                    }
                    ?>
                </div>
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
                <a href="javascript:;" class="btn btn-success si_active_confirm">Yes</a>
                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Delete Shop</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Staffs/Services are Active/Booked and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>