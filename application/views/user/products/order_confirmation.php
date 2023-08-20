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

<div class="content">
    <div class="container">
			
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center order-complete">
					<span class="order-icon">
						<i class="fas fa-check"></i>
					</span>
					<h3>Thank you</h3>
                  	<p>Your order has been received</p>
                </div>
				<div class="card order-success-detail">
					<ul>
						<li><p>Order Number:</p> <span>#<?php echo $order['order_code']?></span></li>
						<li><p>Total Items:</p> <span><?php echo $order['total_products']?></span></li>
						<li><p>Total:</p> <span><?php echo currency_conversion($user_currency_code).$order['total_amt'];?></span></li>
						<li><p>Payment Method:</p> <span><?php echo ucwords($order['payment_type'])?></span></li>
					</ul>
				</div>
            </div>
        </div>
		
        <div class="row justify-content-center">
            <div class="col-md-8">
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
							<?php
							if ($cartlist) 
							{
								foreach ($cartlist as $cart) 
								{
									$product_price = get_gigs_currency($cart['product_price'], $cart['product_currency'], $user_currency_code);
									$product_total = get_gigs_currency($cart['product_total'], $cart['product_currency'], $user_currency_code);
							?>
							
							<tr>
								<td class="product-thumbnail">
									<?php if ($cart['product_image'] != ''){ ?>
										<img src="<?php echo base_url()?><?php echo $cart['product_image']?>" alt="" class="img-fluid">
									<?php } else { ?>
                                        <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                                    <?php } ?>
								</td>
								<td class="product-name">
									<a href="javascript:void(0);"><?php echo $cart['product_name']?></a>
								</td>
								<td class="product-price">
									<?php echo currency_conversion($user_currency_code).$product_price?>
								</td>
								<td class="product-quantity">
									<div class="quantity">
										<?php echo $cart['qty']?>
									</div>
								</td>
								<td class="product-subtotal"><?php echo currency_conversion($user_currency_code).$product_total?></td>
							</tr>
							<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				<?php
				$total_amt = get_gigs_currency($order['total_amt'], $order['currency_code'], $user_currency_code); 
				?>
				<div class="row">
					<div class="col-md-6">
						<div class="order-delivery">
							<h4>Deliver To</h4>
							<p class="mb-0">
								<?php echo ucwords($order['address_type'])?><br>
								<?php echo $billing[0]['full_name']?><br>
								<?php echo $billing[0]['phone_no']?><br>
								<?php echo $billing[0]['email_id']?><br>
								<?php echo $billing[0]['address']?><br>
								<?php echo $billing[0]['city_name']?>, <?php echo $billing[0]['state_name']?><br>
								<?php echo $billing[0]['country_name']?> - <?php echo $billing[0]['zipcode']?><br>
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="text-right">
							<a class="btn btn-primary" href="<?php echo base_url()?>products">CONTINUE SHOPPING</a>
							<a class="btn btn-primary" href="<?php echo base_url()?>user-orders">GO TO ORDER LIST</a>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>