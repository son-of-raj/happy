<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : $default_language['en']['lg_my_orders']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : $default_language['en']['lg_my_orders']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<?php
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
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/provider_sidemenu'); ?>
			<div class="col-xl-9 col-md-8">
				<div class="row align-items-center mb-4">
					<div class="col">
                        <h4 class="widget-title mb-0"><?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : $default_language['en']['lg_my_orders']; ?></h4>
                    </div>
                    <div class="col-auto">
                    	<div class="sort-by">
                    		<div class="sort-select">
	                    		<select class="form-control-sm custom-select searchFilter cf select" id="delivery_status" onchange="filterdeliverystatus();">
	                    			<option value=''>All</option>
	                    			<option value="1">Order Placed</option>
	                    			<option value="2">Order Confirmed</option>
	                    			<option value="3">Shipped</option>
	                    			<option value="4">Out for Delivery</option>
	                    			<option value="5">Delivered</option>
	                    			<option value="6">Cancelled By User</option>
	                    			<option value="7">Cancelled By Provider</option>
	                    		</select>
	                    	</div>
                    	</div>
                    </div>
				</div>
				<div class="row mb-4">
					<div class="col-md-3">
						<input type="text" id="order_code" value="" class="form-control cf" placeholder="Order ID">
					</div>
					<div class="col-md-3">
						<input type="text" id="user_name" value="" class="form-control cf" placeholder="User Name">
					</div>
					<div class="col-md-3">
						<input type="text" id="product_name" value="" class="form-control cf" placeholder="Product">
					</div>
					<div class="col-md-3">
						<button type="button" class="btn btn-info" onclick="filter_porder('s');"><i class="fa fa-search"></i></button>
						<button type="button" class="btn btn-danger" onclick="filter_porder('c');"><i class="fa fa-times"></i></button>
					</div>
				</div>
				<div id="orderList">
					<?php
					if (!empty($orders)) 
					{
						$ds_arr = array('1' => 'Order Placed', '2' => 'Order Confirmed', '3' => 'Shipped', '4' => 'Out for Delivery', '5' => 'Delivered', '6'=>'Cancelled By User', '7'=>'Cancelled By Provider');
					 	$dsc = array('1' => 'warning', '2' => 'dark', '3' => 'primary', '4' => 'info', '5' => 'success', '6'=>'danger', '7'=>'danger');
					 	foreach ($orders as $val) 
					 	{
					 		$product_price = get_gigs_currency($val['product_price'], $val['product_currency'], $user_currency_code);
					 		$product_total = get_gigs_currency($val['product_total'], $val['product_currency'], $user_currency_code);
					 		?>
					 		<div class="bookings" id="ol_<?php echo $val['id']?>">
					 			<div class="booking-list">
					 				<div class="booking-widget">
					 					<a href="#" class="booking-img">
					 						<?php if ($val['product_image']) { ?>
                                            	<img src="<?php echo base_url()?><?php echo $val['product_image']?>" alt="Product Image">
                                            <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                            <?php } ?>
                                        </a>
                                        <div class="booking-det-info">
                                        	<h3 class="mb-2">
                                                <a href="#"><?php echo wordwrap($val['product_name'], 30, '<br />', true); ?></a>
                                                <span class="badge badge-pill badge-prof bg-<?php echo $dsc[$val['delivery_status']]?>"><?php echo $ds_arr[$val['delivery_status']]?></span>
                                            </h3>
                                            <span class="badge badge-pill bg-warning-light mb-2"><?php echo $val['name']?> : <?php echo $val['mobileno']?></span>
                                            <ul class="booking-details">
                                            	<li>
                                            		<span>Order ID :</span> <?php echo strtoupper($val['order_code'])?>                          
                                            	</li>
                                            	<li>
                                            		<span>Order Date :</span> <?php echo  date('d M Y h:i A', strtotime($val['created_at'])); ?>
                                            	</li>
                                            	<li>
                                            		<span>Product Price :</span> <?php echo currency_conversion($user_currency_code) . $product_price; ?>
                                            	</li>
                                            	<li>
                                            		<span>Qty :</span> <?php echo $val['qty']?>
                                            	</li>
                                            	<li>
                                            		<span>Amount :</span> <?php echo currency_conversion($user_currency_code) . $product_total; ?>
                                            	</li>
                                            	<?php
                                            	if ($val['delivery_status'] == 6 || $val['delivery_status'] == 7) 
                                            	{
                                            	 	?>
                                            	 	<li>
	                                            		<span>Reason :</span> <?php echo $val['cancel_reason']?>
	                                            	</li>
                                            	 	<?php 
                                            	} 
                                            	?>
                                            </ul>
                                        </div>
					 				</div>
					 				
					 				<div class="booking-action">
					 					<a href="javascript:void(0);" style="display:<?php echo ($val['delivery_status'] == 1 ? 'block':'none')?>" class="btn btn-sm bg-warning-light" onclick="changedeliverystatus('<?php echo $val['id']?>', '<?php echo $val['delivery_status']?>', '<?php echo $pc?>');">Accept Order</a>

					 					<a href="javascript:void(0);" style="display:<?php echo ($val['delivery_status'] == 2 ? 'block':'none')?>" class="btn btn-sm bg-default-light" onclick="changedeliverystatus('<?php echo $val['id']?>', '<?php echo $val['delivery_status']?>', '<?php echo $pc?>');">Change to Shipping</a>

					 					<a href="javascript:void(0);" style="display:<?php echo ($val['delivery_status'] == 3 ? 'block':'none')?>" class="btn btn-sm bg-primary-light" onclick="changedeliverystatus('<?php echo $val['id']?>', '<?php echo $val['delivery_status']?>', '<?php echo $pc?>');">Out For Delivery</a>

					 					<a href="javascript:void(0);" style="display:<?php echo ($val['delivery_status'] == 4 ? 'block':'none')?>" class="btn btn-sm bg-success-light" onclick="changedeliverystatus('<?php echo $val['id']?>', '<?php echo $val['delivery_status']?>', '<?php echo $pc?>');">Delivered</a>

					 					<?php
						 				if ($val['delivery_status'] == 1) 
						 				{
						 				?>
					 					<a href="javascript:void(0);" class="btn btn-sm bg-danger-light" onclick="checkordercancel('<?php echo $val['id']?>', '<?php echo $val['order_id']?>');">Cancel Order</a>
					 					<?php
						 				} 
						 				?>
										<a href="javascript:void(0);" class="btn btn-sm bg-success-light" onclick="view_provider_order('<?php echo $val['id']?>', '<?php echo $val['order_id']?>');" role="button">View Order</a>
					 				</div>
					 				
					 			</div>
					 		</div>
					 		<?php 
					 	}
					}
					else
					{
						?>
						<p><?php echo (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou']; ?></p>
						<?php 
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

<!-- Modal -->
<div class="modal fade" id="view_order" tabindex="-1"  role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
<div class="card order-success-detail">
					<ul>
						<li><p>Order Number:</p> <span id="d_order_code"></span></li>
						<li><p>Total Items:</p> <span id="d_total_qty"></span></li>
						<li><p>Total:</p> <span id="d_pro_total_amt"></span></li>
						<li><p>Payment Method:</p> <span id="d_payment_type"></span></li>
					</ul>
				</div>
				<div class="table-responsive cart-table">
					<table class="table mb-4">
						<thead>
							<tr>
								<th class="product-thumbnail">&nbsp;</th>
								<th class="product-name">Product</th>
								<th class="product-price">Price</th>
								<th class="product-quantity">Quantity</th>
								<th class="product-subtotal">Total</th>
							</tr>
						</thead>
						<tbody>
														
							<tr>
								<td class="product-thumbnail">
									<img id="p_product_img" src="" alt="" class="img-fluid">
								</td>
								<td class="product-name" id="p_product_name">
									
								</td>
								<td class="product-price" id="p_pros_total_amt"></td>
								<td class="product-quantity">
									<div class="quantity" id="p_total_qty"></div>
								</td>
								<td class="product-subtotal" id="p_sub_total_amt">$</td>
							</tr>
													</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="order-delivery">
							<h4>Deliver To</h4>
							<p class="mb-0">
								<b id="c_delivery_address"></b>
							</p>
						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>
      </div>
    </div>
  </div>
</div>

<div id="order_cancel_pop" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <b>Cancel Reason?</b>
                <input type="hidden" id="hc_id" value="">
                <input type="hidden" id="ho_id" value="">
                <textarea id="cancel_reason" class="form-control" placeholder="Cancel Reason" rows="4" cols="20"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="cancel_order();">Yes</button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>