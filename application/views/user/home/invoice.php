
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo base_url().settingValue('meta_title')?></title>
</head>

<body bgcolor="#ffffff">
<?php
$now = time(); // or your date as well
$user_currency_code = settings('currency');
$service_amount = get_gigs_currency($booking['amount'], $booking['currency_code'], $user_currency_code);
?>
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
    		<h1 style="text-align: center;">Invoice</h1>
    	</td>
    </tr>
	<tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Date : <?php echo date("d M Y",strtotime($booking['updated_on']))?></p>
						
					</td>
						
                </tr>
				<tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Invoice ID : INV<?php echo sprintf("%07d", $booking['id'])?></p>
						
					</td>
						
                </tr>
				<tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Booking Date : <?php echo date("d M Y",strtotime($booking['service_date']))?></p>
						
					</td>
						
                </tr>
				<tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Booking Time : <?php echo date("h:i A",strtotime($booking['from_time']))?> - <?php echo date("h:i A",strtotime($booking['to_time']))?></p>
						
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
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:20px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($provider['name'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($provider['city_name'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo $provider['pincode']?></p>						
					</td>
						<td align="right">						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:20px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Billing Address:</p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($user['name'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($user['city_name'])?></p>						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($user['pincode'])?></p>						
					</td>
                </tr>
            </table>
    	</td>
    </tr>
	
	<tr bgcolor="#FFFFFF"><td>&nbsp;</td></tr>
	
	<?php
	
	if($invoice['booking_id'] > 0) { 
		$shopid = $this->db->select('shop_id')->where('id',$invoice['booking_id'])->get('book_service')->row()->shop_id;
	} ?>
   
   <tr>
    	<td>
            <table align="center" width="100%" cellpadding="10" cellspacing="0" border="1" bgcolor="#FFFFFF">
			<thead>
				<tr>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">SI No</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Item</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Grand Total</th>	
					
				</tr>
			</thead>
			<tbody>
                <tr>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;">1</p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo $service['service_title']?>, (<?php echo $service['category_name']?> - <?php echo $service['subcategory_name']?>)</p>
					</td>
					
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo currency_conversion($user_currency_code)?><?php echo $service_amount?></p>
					</td>								
                </tr>
			</tbody>
            </table> 
        </td>   	
    </tr>
</table>

</body>
</html>
	