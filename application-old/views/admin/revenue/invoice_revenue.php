
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo base_url().settingValue('meta_title')?></title>
</head>

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
    		<h1 style="text-align: center;">Invoice</h1>
    	</td>
    </tr>
	<tr>
    	<td>
            <table align="center" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFFFFF">
                <tr>
                    <td align="left">
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Date : <?php echo date("d M Y",strtotime($invoice['date']))?></p>
						
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
						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:20px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['user_name'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['paddress'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['ucity'])?><?php echo ($invoice['upincode'] != '')?' - '.$invoice['upincode']:'';?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['ustate'])?></p>
					</td>
						<td align="right">						
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:20px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;">Billing Address:</p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:18px; font-weight:bold; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['provider_name'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['paddress'])?></p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['pcity'])?><?php echo ($invoice['ppincode'] != '')?' - '.$invoice['ppincode']:'';?>,</p>
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:5px 20px 5px 20px; padding:0;"><?php echo ucfirst($invoice['pstate'])?></p>						
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
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Amount</th>
					
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Offer</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Discount</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Rewards</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Commission</th>
					<th style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold; color:#817d7d; margin:0; padding:10px;">Grand Total</th>	
					
				</tr>
			</thead>
			<tbody>
				
				<?php $curency = settingValue('currency_symbol');
				$amount=$invoice['amount'];
                $comi=$invoice['commission'];
				$vat=($invoice['vat']=='' ? 0 : $invoice['vat']);
                $comAount=$amount*$comi/100;
				$vatAmount=$amount*$vat/100;
				
				if($invoice['offersid'] > 0) {
					$off=$this->db->where('id', $invoice['offersid'])->get('service_offers')->row_array();
					$offPer = $off['offer_percentage']."%";
					$offAmount=($amount) * ($off['offer_percentage']/100);
					$off_Amount=$curency." ".$offAmount;
				} else{
					$offAmount = 0; $offPer=''; $off_Amount = '-';
					$off_Amount = $curency." ".$offAmount;
				}
				
				if($invoice['couponid'] > 0) {
					$cpn=$this->db->where('id', $invoice['couponid'])->get('service_coupons')->row_array();
					if($cpn['coupon_type'] == 1){
						$cpnPer = $cpn['coupon_percentage']."%";
						$cpnAmount=($amount) * ($cpn['coupon_percentage']/100);
						$cpn_Amount=$curency." ".$cpnAmount;
					} else {
						$cpnPer = '';
						$cpnAmount = $cpn['coupon_amount'];
						$cpn_Amount = $curency." ".$cpn['coupon_amount'];
					}
				} else{
					$cpnAmount = 0;$cpnPer = ''; $cpn_Amount = '-';
					$cpn_Amount = $curency." ".$cpnAmount;
				}
				
				$rewardid = $invoice['rewardid'];
				$reward = $this->db->where("id",$rewardid)->get("service_rewards")->row_array(); 
				if($rewardid > 0) {
					if ($reward['reward_type'] == 1) {
						$rwdPer = $reward['reward_discount']."%";
						$rewardPrice = ($amount) * ($reward['reward_discount'] / 100) ; 	
						if(is_nan($rewardPrice)) $rewardPrice = 0;	
						$rwd_Amount = $curency." ".$rewardPrice;		
					} else if ($reward['reward_type'] == 0) {
						$rewardPrice  = 0;	$rwdPer = ''; $rwd_Amount ='Free Service';
						$rwd_Amount = $curency." ".$rewardPrice;;						
					}
				} else {
					$rewardPrice  = 0;	$rwdPer = ''; $rwd_Amount ='-';
					$rwd_Amount = $curency." ".$rewardPrice;
				}
				
				$grandTotal = ($amount - $offAmount) - $cpnAmount;
				$grandTotal = $grandTotal - $rewardPrice;
				
			
				$vatAmount = number_format($vatAmount,2);	
				
				$grandTotal = number_format($grandTotal,2);
				
				
				?>
                <tr>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;">1</p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo ($curency." ".$invoice['amount']); ?></p>
					</td>
					
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo $off_Amount."<br>".$offPer; ?></p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo $cpn_Amount."<br>".$cpnPer; ?></p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo $rwd_Amount."<br>".$rwdPer; ?></p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo ($curency." ".$comAount); ?></p>
					</td>
					<td align="center" border="1" style="border:1px solid #817d7d">
						<p style="font-family:Lato, Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; color:#817d7d; margin:0; padding:0;"><?php echo $curency." ".$grandTotal; ?></p>
					</td>					
                </tr>
			</tbody>
            </table> 
        </td>   	
    </tr>
</table>

</body>
</html>
	