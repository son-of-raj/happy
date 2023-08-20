<?php 
$id = $data['loginid'];
$pk_key = $data['publishable_api_key'];
$lanval = $data['language'];

$amount = 0;
if($data['amount'] != '') {
	$amount = $data['amount'];
}

$currency_code = 'SAR';
if($data['currency_code'] != '') {
	$currency_code = $data['currency_code'];
}

$url = ''; $description = '';

$action = $data['action'];

$subscription_id = 0; 

$book_id = 0; $coupon_id = 0; $cod = 0;

if($action == 1){
	$description = 'Add Subsctiption';
	$subscription_id = $data['subscription_id'];
	$url = base_url()."api/payment/subscription_payment/".$id."/".$subscription_id;	
} else if($action == 2){
	$description = 'Add Shop';
	$url = base_url()."api/payment/newshop_payment/".$id;
} else if($action == 3){
	$description = 'Book Service';
	$book_id = $data['book_id'];
	$coupon_id = $data['couponid'];
	$cod = $data['cod'];
	if($cod == 1){
		$url = base_url()."api/payment/booking_payment/".$id."/".$book_id."/".$coupon_id."/".$cod."/".$amount;
		redirect($url);
	} 
	$url = base_url()."api/payment/booking_payment/".$id."/".$book_id."/".$coupon_id."/".$cod;
}
else
{
	$description = 'Product Order';
	$order_id = $data['order_id'];
	$url = base_url()."api/payment/order_payment/".$order_id;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Payments</title>
	<!-- Moyasar Styles -->
	<link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.2.0/moyasar.css">
	<!-- Moyasar Scripts -->
	<script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
	<script src="https://cdn.moyasar.com/mpf/1.2.0/moyasar.js"></script>
   
</head>
<body>
	<div id="moyasarfrm">
		<div class="moyasar_paymentform"></div>
	</div>
	
<script type="text/javascript">	
	var lanval = "<?php echo $lanval; ?>"; 
	var url = "<?php echo $url; ?>"; 
	var amt = "<?php echo $amount; ?>"; 
	var code = "<?php echo $currency_code; ?>"; 
	var description = "<?php echo $description; ?>"; 
	var pk_key = "<?php echo $pk_key; ?>";
	
	Moyasar.init({
			// Required
			// Specify where to render the form
			// Can be a valid CSS selector and a reference to a DOM element
			element: '.moyasar_paymentform',
			
			language: lanval,

			// Required
			// Amount in the smallest currency unit
			// For example:
			// 10 SAR = 10 * 100 Halalas
			// 10 KWD = 10 * 1000 Fils
			// 10 JPY = 10 JPY (Japanese Yen does not have fractions)
			amount: amt,

			// Required
			// Currency of the payment transation
			currency: code,

			// Required
			// A small description of the current payment process
			description: description,

			// Required
			publishable_api_key: pk_key,

			// Required
			// This URL is used to redirect the user when payment process has completed
			// Payment can be either a success or a failure, which you need to verify on you system (We will show this in a couple of lines)
			callback_url: url,

			// Optional
			// Required payments methods
			// Default: ['creditcard', 'applepay', 'stcpay']
			methods: [
				'creditcard',
			],
			
	});
</script>
﻿
</body>
</html>