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
<div class="content">
	<div class="container">
		<div class="row">
			<?php $this->load->view('user/home/user_sidemenu'); ?>
			<div class="col-xl-9 col-md-8">
				<div class="row align-items-center mb-4">
					<div class="col">
                        <h4 class="widget-title mb-0"><?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : $default_language['en']['lg_my_orders']; ?></h4>
                    </div>
                    <div class="col-auto">
                    	<div class="sort-by">
                    		<select class="form-control-sm custom-select searchFilter cf select" id="delivery_status">
                    			<option value=''>All</option>
                    			<option value="1">Order Placed</option>
                    			<option value="2">Order Confirmed</option>
                    			<option value="3">Shipped</option>
                    			<option value="4">Out for Delivery</option>
                    			<option value="5">Delivered</option>
                    			<option value="6">Cancelled</option>
                    		</select>
                    	</div>
                    </div>
				</div>
				<div class="row mb-4">
					<div class="col-md-3">
						<input type="text" id="order_code" value="" class="form-control cf" placeholder="Order ID">
					</div>
					<div class="col-md-3">
						<input type="text" id="shop_name" value="" class="form-control cf" placeholder="Shop">
					</div>
					<div class="col-md-3">
						<input type="text" id="product_name" value="" class="form-control cf" placeholder="Product">
					</div>
					<div class="col-md-3 d-flex">
						<button type="button" class="btn btn-primary filter_order custom-btn-search me-2" stype="s"><i class="fa fa-search"></i></button>
						<button type="button" class="btn btn-danger filter_order custom-btn-search" stype="c"><i class="fa fa-times"></i></button>
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
					 					<a href="<?php echo base_url();?>products" class="booking-img">
					 						<?php if ($val['product_image']) { ?>
                                            	<img src="<?php echo base_url()?><?php echo $val['product_image']?>" alt="Product Image">
                                            <?php } else { ?>
                                                <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                            <?php } ?>
                                        </a>
                                        <div class="booking-det-info">
                                        	<h3 class="mb-2" style="word-wrap: break-word;">
                                                <a href="<?php echo base_url();?>products"><?php echo wordwrap($val['product_name'], 60, '<br />', true); ?></a>
                                                <span class="badge badge-pill badge-prof bg-<?php echo $dsc[$val['delivery_status']]?>"><?php echo $ds_arr[$val['delivery_status']]?></span>
                                            </h3>
                                            <span class="badge badge-pill bg-warning-light mb-3">From : <?php echo $val['shop_name']?></span>
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
                                            	<li><a href="javascript:void(0)" class="btn btn-link p-0 more-link-order" onclick="view_order('<?php echo $val['id']?>', '<?php echo $val['order_id']?>');">More Details</a></li>
                                            </ul>
                                        </div>
					 				</div>
					 				<?php
					 				if ($val['delivery_status'] == 1) 
					 				{
					 				?>
					 				<div class="booking-action">
					 					<a href="javascript:void(0)" class="btn btn-sm bg-danger-light" onclick="checkordercancel('<?php echo $val['id']?>', '<?php echo $val['order_id']?>');">Cancel Order</a>
					 				</div>
					 				<?php
					 				} 
					 				?>
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
<div id="order_cancel_pop" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <b>Cancel Reason</b>
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

<div id="order_details_pop" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                	<table class="table table-bordered">
                		<tr>
                			<td>Order ID : <b id="d_order_code"></b></td>
                			<td>Payment Type : <b id="d_payment_type"></b></td>                		               			
                			<td>Order Deliver To : <b id="d_address_type"></b></td>
                		</tr>
                		<tr>
                			<td>Address:</td>
                			<td colspan="2"><b id="c_delivery_address"></b></td>
                		</tr>
                	</table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>