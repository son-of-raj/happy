<?php
$ds = array(1=>['text'=>'Order Placed', 'color'=>'success'], 2=>['text'=>'Order Confirmed', 'color'=>'info'], 3=>['text'=>'Shipped', 'color'=>'primary'], 4=>['text'=>'Out of Delivery', 'color'=>'muted'], 5=>['text'=>'Delivered', 'color'=>'success'], 6=>['text'=>'Cancelled By User', 'color'=>'danger'], 7=>['text'=>'Cancelled By Provider', 'color'=>'danger']);
?>
<div class="table-responsive">
	<table class="table table-hover table-center mb-0 service_table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Order Code</th>
                <th>User</th>
                <th>Provider</th>
                <th>Shop</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(!empty($list)) 
            {
            	$i=1;
                foreach ($list as $rows) 
                {
					$user_image = $rows['profile_img'];
					if(empty($user_image)){
						$user_image ='assets/img/user.jpg';
					}
					$provider_image = $rows['provider_profile_img'];
					if(empty($provider_image)){
						$provider_image ='assets/img/user.jpg';
					}
				/* time */
				$full_date=date(settingValue('date_format'), strtotime($rows['created_at']));
					
					?>
			<tr>
				<td><?php echo $i?></td>
				<td><?php echo $full_date?></td>
				<td><?php echo $rows['order_code']?></td>
				<td>
					<h2 class="table-avatar">
						<a href="#" class="avatar avatar-sm me-2">
							<img class="avatar-img rounded-circle" alt="" src="<?php echo base_url().$user_image?>">
						</a>
						<a href="javascript:void(0);"><?php echo $rows['name']?></a>
					</h2>
				</td>
				<td>
					<h2 class="table-avatar">
						<a href="#" class="avatar avatar-sm me-2">
							<img class="avatar-img rounded-circle" alt="" src="<?php echo base_url().$provider_image?>">
						</a>
						<a href="javascript:void(0);"><?php echo $rows['provider_name']?></a>
					</h2>
				</td>
				<td><?php echo $rows['shop_name']?></td>

				<td><?php echo wordwrap($rows['product_name'], 60, '<br />', true); ?> <?php echo $rows['qty']?><?php echo $rows['unit_name']?></td>
				<td><?php echo currency_conversion($rows['product_currency']).$rows['product_total']?></td>
				<td>
					<label class="badge badge-<?php echo $ds[$rows['delivery_status']]['color']?>"><?php echo $ds[$rows['delivery_status']]['text']?></label>
				</td>
			</tr>
			<?php 
				$i++;
				} 
			} 
			
			?>
        </tbody>
    </table>
</div>
<?php
echo $this->ajax_pagination_new->create_links();
?>
					
