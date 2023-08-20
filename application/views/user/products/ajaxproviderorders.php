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
        <div class="bookings">
            <div class="booking-list">
                <div class="booking-widget">
                    <a href="#" class="booking-img">
                        <img src="<?php echo base_url()?><?php echo $val['product_image']?>" alt="Product Image">
                    </a>
                    <div class="booking-det-info">
                        <h3 class="mb-2">
                            <a href="#"><?php echo $val['product_name']?></a>
                        </h3>
                        <span class="badge badge-pill bg-warning-light mb-2"><?php echo $val['name']?> : <?php echo $val['mobileno']?></span>
                        <ul class="booking-details">
                            <li>
                                <span>Order ID :</span> <?php echo strtoupper($val['order_code'])?>                          
                            </li>
                            <li>
                                <span>Order Date :</span> <?php echo  date('d M Y h:i A', strtotime($val['created_at'])); ?>
                                <span class="badge badge-pill badge-prof bg-<?php echo $dsc[$val['delivery_status']]?>"><?php echo $ds_arr[$val['delivery_status']]?></span>
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