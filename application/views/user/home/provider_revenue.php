<?php
$now = time(); // or your date as well
$user_currency_code = settings('currency');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo base_url().settingValue('meta_title')?></title>
</head>
<style type="text/css">
table {page-break-inside: avoid;}
</style>
<body bgcolor="#ffffff">

<table align="center" width="1200" cellpadding="0" cellspacing="0" border="0" bgcolor="#fffff">
	
    <tr>
    	<td>
            <table align="center" width="100%" cellpadding="15" cellspacing="0" border="0" bgcolor="#fff" style="margin-top:0;">
                <tr>
                    <td align="left"><a href="#" target="_blank"><img src="<?php echo base_url().settingValue('logo_front')?>" style="height: 46px;" /></a>
					
                    </td>
                </tr>
            </table> 
        </td>   	
    </tr>
    <tr bgcolor="#FFFFFF">
    	<td>
    		<h1 style="text-align: center;">Revenue</h1>
    	</td>
    </tr>
	<tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Date : <?php echo date("d M Y")?></p>
						
					</td>
						
                </tr>
            </table> 
        </td>   	
    </tr>
    <tr>
    	<td>
    		<table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:20px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['name'])?></p>
					</td>
                </tr>
            </table>
    	</td>
    </tr>
	
	<tr bgcolor="#FFFFFF"><td>&nbsp;</td></tr>

    <tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#000; margin:5px 20px 5px 20px; padding:0;">Bookings : </p>
						
					</td>
						
                </tr>
				
            </table> 
        </td>   	
    </tr>
	<tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#000; margin:5px 20px 5px 20px; padding:0;">&nbsp;</p>
						
					</td>
						
                </tr>
				
            </table> 
        </td>   	
    </tr>
	
	
   
   <tr>
    	<td>
            <table align="center" width="100%" cellpadding="10" cellspacing="0" border="1" bgcolor="#FFFFFF">
			<thead>
				<tr>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">SI No</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Service</th>
					
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">User</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Date</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Payment Type</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Status</th>
					
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Ratings</th>	
					
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$bstatus_arr = ['5'=>'Rejected By User', '6'=>'Completed', '7'=>'Cancelled By Provider'];
				if (!empty($invoice['bookings'])) 
				{
					$i=1;
					$total_cod = 0;
					$total_online = 0;
					$grand_total = 0;
					foreach ($invoice['bookings'] as $val) 
					{
						$total_amount = get_gigs_currency($val['total_amount'], $val['currency_code'], $user_currency_code);
						if ($val['cod'] == 1) {
							$total_cod += $total_amount;
						}
						else
						{
							$total_online += $total_amount;
						}
						$grand_total += $total_amount;
						?>
						<tr>
							<td><?php echo $i?></td>
							<td><?php echo $val['service_title']?></td>
							<td>
								<?php echo $val['user_name']?>
								<br>
								<?php echo $val['user_country_code']?> - <?php echo $val['user_mobile']?>
							</td>
							<td><?php echo $val['service_date']?></td>
							<td><?php echo ($val['cod']==1 ? 'COD':'Online')?></td>
							<td>
								<?php echo $bstatus_arr[$val['status']]?>
								<br>
								<?php
								if ($val['reason']!='') {
								 	echo " - ".$val['reason'];
								} 
								?>
								<br>
								<?php
								if ($val['admin_reject_comment']!='') {
								 	echo " - ".$val['admin_reject_comment'];
								} 
								?>
							</td>
							<td>
								<?php echo $val['rating']?>/5
								<br>
								<?php echo $val['review']?>	
							</td>
							<td><?php echo currency_conversion($user_currency_code)?><?php echo $total_amount?></td>
						</tr>
						<?php
						$i++;
					}
					?>
					<tr>
						<td colspan="4"></td>
						<td>Total COD : <?php echo currency_conversion($user_currency_code)?><?php echo $total_cod?></td>
						<td>Total Online : <?php echo currency_conversion($user_currency_code)?><?php echo $total_online?></td>
						<td colspan="2"></td>
						<td>Total : <?php echo currency_conversion($user_currency_code)?><?php echo $grand_total?></td>
					</tr>
					<?php 
				}
				?>
				
			</tbody>
            </table> 
        </td>   	
    </tr>
    <tr bgcolor="#FFFFFF"><td>&nbsp;</td></tr>

    <tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#000; margin:5px 20px 5px 20px; padding:0;">Orders : </p>
						
					</td>
						
                </tr>
				
            </table> 
        </td>   	
    </tr>
    <tr>
    	<td>
            <table align="center" width="100%" cellpadding="10" cellspacing="0" border="1" bgcolor="#FFFFFF">
			<thead>
				<tr>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">SI No</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Product</th>
					
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">User</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Date</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Payment Type</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Status</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Total</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$dstatus_arr = ['5'=>'Delivered', '6'=>'Cancelled'];
				if (!empty($invoice['orders'])) 
				{
					$i=1;
					$ototal_cod = 0;
					$ototal_online = 0;
					$ogrand_total = 0;
					foreach ($invoice['orders'] as $val) 
					{
						$product_total = get_gigs_currency($val['product_total'], $val['product_currency'], $user_currency_code);
						if ($val['payment_type'] == 'cod') {
							$ototal_cod += $product_total;
						}
						else
						{
							$ototal_online += $product_total;
						}
						$ogrand_total += $product_total;
						?>
						<tr>
							<td><?php echo $i?></td>
							<td>
								<?php echo $val['product_name']?><br>
								<?php echo $val['qty']?><?php echo $val['unit_name']?>	
							</td>
							<td>
								<?php echo $val['user_name']?>
								<br>
								<?php echo $val['user_country_code']?> - <?php echo $val['user_mobile']?>
							</td>
							<td><?php echo date("d-m-Y",strtotime($val['created_on']))?></td>
							<td><?php echo $val['payment_type']?></td>
							<td>
								<?php echo $dstatus_arr[$val['status']]?>
							</td>
							<td><?php echo currency_conversion($user_currency_code)?><?php echo $product_total?></td>
						</tr>
						<?php
						$i++;
					}
					?>
					<tr>
						<td colspan="4"></td>
						<td>Total COD : <?php echo currency_conversion($user_currency_code)?><?php echo $ototal_cod?></td>
						<td>Total Online : <?php echo currency_conversion($user_currency_code)?><?php echo $ototal_online?></td>
						<td>Total : <?php echo currency_conversion($user_currency_code)?><?php echo $ogrand_total?></td>
					</tr>
					<?php 
				}
				?>
				
			</tbody>
            </table> 
        </td>   	
    </tr>
</table>

</body>
</html>
	