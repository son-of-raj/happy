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
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_cart'])) ? $user_language[$user_selected]['lg_cart'] : $default_language['en']['lg_cart']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-right ml-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_cart'])) ? $user_language[$user_selected]['lg_cart'] : $default_language['en']['lg_cart']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadcrumb -->
<!--Cart section-->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="table-responsive cart-table">
                	<table class="table">
                    	<thead>
                        	<tr>
                            	<th class="product-thumbnail">&nbsp;</th>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Total</th>
                                <th class="product-remove">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    $total = 0;
                    $cc = '';
                    if (!empty($cartlist)) 
                    {
                        foreach ($cartlist as $cart) 
                        {
                           $cart['product_currency'] = ($cart['product_currency'])?$cart['product_currency']:$user_currency_code;
                            $product_price = get_gigs_currency($cart['product_price'], $cart['product_currency'], $user_currency_code);

                            $total = $total+$cart['product_total'];
                            $cc = $cart['product_currency'];
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
							<td class="product-price" id="product_price_<?php echo $cart['id'];?>">
								<?php echo currency_conversion($user_currency_code) . $product_price; ?>
								<input type="hidden" value="<?php echo $product_price; ?>" id="product_price_original_<?php echo $cart['id'];?>" />
							</td>
							<td class="product-quantity">
								<div class="quantity">
									<input type="button" value="-" id="subs" cart_id="<?php echo $cart['id']?>" product_id="<?php echo $cart['product_id']?>" class="button-minus dec button minus">
									<input type="text" name="qty" id="qty_<?php echo $cart['id']?>" value="<?php echo $cart['qty']?>" class="quantity-field qty" readonly>
									<input type="button" value="+" id="adds" cart_id="<?php echo $cart['id']?>" product_id="<?php echo $cart['product_id']?>"  class="button-plus inc button plus">
								</div>
							</td>
							<td class="product-subtotal"><?php echo currency_conversion($user_currency_code); ?><span id="product_subtotal_<?php echo $cart['id']; ?>"><?php echo $product_price * $cart['qty']; ?></td>
							<td class="product-remove">
								<a href="<?php echo base_url()?>delete-cart?cart_id=<?php echo encrypt_url($cart['id'],$this->config->item('encryption_key'))?>&od=" ><i class="fas fa-trash-alt"></i></a>
							</td>
                    </tr>
                        <?php
                        } 
                        ?>
                    <?php
                    } 
                    ?>
                        </tbody>
                    </table>
                </div>
				
                    <div class="cart-action">
                        <span class="cart-action-cont"><a href="<?php echo base_url()?>products" class="btn cart-btn-1">Continue Shopping</a></span>
                    </div>
            </div>
            <?php
            $product_total = get_gigs_currency($total, $cc, $user_currency_code); 
            ?>
            <div class="col-lg-4 col-12">
                <div class="cart-price-wrapper">
                    <div class="card">
                        <div class="card-body">
                            <h3>Cart Totals</h3>
							

								<table class="total-table mt-2">
									<tbody>
										<tr>
											<td>Sub Total</td>
											<td class="text-right"><?php echo currency_conversion($user_currency_code); ?><span id="sub_total"><?php echo $product_total; ?></span></td>
										</tr>
										<tr class="total-row">
											<td><?php echo (!empty($user_language[$user_selected]['lg_total'])) ? $user_language[$user_selected]['lg_total'] : $default_language['en']['lg_total']; ?></td>
											<td class="final_total_td"><span id="final_total" class="toZero"><?php echo currency_conversion($user_currency_code)?><span id="total"><?php echo $product_total?></span></td>
										</tr>
									</tbody>
								</table>
								
                            <div class="mt-3">
                                <a href="#" id="checkout" class="btn addcart-btn cart-btn-1 w-100" tabindex="0"> <?php echo (!empty($user_language[$user_selected]['lg_proceed_to_chekout'])) ? $user_language[$user_selected]['lg_proceed_to_chekout'] : $default_language['en']['lg_proceed_to_chekout']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--cart section--> 