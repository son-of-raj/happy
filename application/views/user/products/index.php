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
} 
$user_currency_code = $user_currency['user_currency_code'];
?>

<input type="hidden" id="hshop_id" value="<?php echo $shop_id?>">
<div class="content">
    <div class="container">
        <div class="row">

            <?php 
            if($this->session->userdata('usertype') != 'admin') {
                $this->load->view('user/home/provider_sidemenu');
            } ?>
               
            
            <div class="col-xl-9 col-md-8">
				<h4 class="widget-title"><?php echo (!empty($user_language[$user_selected]['lg_My_Products'])) ? $user_language[$user_selected]['lg_My_Products'] : $default_language['en']['lg_My_Products']; ?></h4>
				
                <?php if($this->session->userdata('usertype') != 'admin') { ?>
                    <div class="addnewdiv">
    					<h6><a class="shopfee btn btn-primary text-white" href="<?php echo base_url()?>add-product/<?php echo $shop_id?>"><i class="fas fa-plus"></i><?php echo (!empty($user_language[$user_selected]['lg_add_product'])) ? $user_language[$user_selected]['lg_add_product'] : $default_language['en']['lg_add_product']; ?></a></h4>
    				</div>
				<?php } ?>
				<div>&nbsp;</div>
				<div class="shop-list-wrapper row" id="mplist">
                    <?php
                    if (!empty($products)) 
                    {
                        foreach ($products as $val) 
                        {
                            $price = get_gigs_currency($val['price'], $val['currency_code'], $user_currency_code);
                            $sale_price = get_gigs_currency($val['sale_price'], $val['currency_code'], $user_currency_code);
                            $discount = get_gigs_currency($val['discount'], $val['currency_code'], $user_currency_code);
                            ?>
                            <!-- Product Item-->
                            <div class="col-lg-4 col-12 col-sm-6" id="prow_<?php echo $val['id']?>">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
										<div class="product-img">
											<a href="product-details" tabindex="0">
                                                <?php if ($val['product_image'] != '') { ?>
												<img class="default-img" alt="Product Image" src="<?php echo base_url()?><?php echo $val['product_image']?>">
                                                <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                                <?php } ?>
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
                                            <a href="#"><?php echo $val['category_name']; ?></a>
                                        </div>
                                        <h2>
                                            <a href="#" tabindex="0"><?php echo $val['product_name']?></a>
                                        </h2>                                           
                                        <div class="product-card-bottom">
											<div class="product-price">

											</div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <?php echo (!empty($user_language[$user_selected]['lg_quantity'])) ? $user_language[$user_selected]['lg_quantity'] : $default_language['en']['lg_quantity']; ?> : 
                                                    <?php
                                                    if ($val['unit_value']>0) {
                                                        ?>
                                                        <span class="text-info"><?php echo $val['unit_value']?> <?php echo $val['unit_name']?></span>
                                                        <?php 
                                                    }
                                                    else
                                                    {
                                                        ?>
                                                        <span class="text-danger">Out of Stock</span>
                                                        <?php 
                                                    }
                                                    ?>
                                                
                                            </div>
                                        </div>
                                        <?php if($this->session->userdata('usertype') != 'admin') { ?>
                                            <div class="add-cart mt-3">
                                                <a href="<?php echo base_url()?>edit-product/<?php echo $shop_id?>/<?php echo $val['id']?>" class="cart-btn">Edit</a>
                                                <a href="#" onclick="check_product_delete('<?php echo $val['id']?>');" class="buy-btn">Delete</a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- /Product Item-->
                            <?php 
                        }
                    } else{
						$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
						echo '<div class="col-lg-12">
								<p class="mb-0 text-center">'.$norecord.'</p>
							</div>';
					}
                    echo $this->ajax_pagination_new->create_links();
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
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure to delete this Product?
                <input type="hidden" id="hp_did" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="confirm_product_delete();">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

