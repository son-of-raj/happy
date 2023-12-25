<?php
$self_info=$this->session->userdata('chat_token');
foreach ($chat_history as $key => $msg_value) {
		$full_date =date('Y-m-d H:i:s', strtotime($msg_value->created_at));
	$date=date('Y-m-d',strtotime($full_date));
	$date_f=date('d-m-Y',strtotime($full_date));
	$yes_date=date('Y-m-d',(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ));
	$time=date('H:i',strtotime($full_date));
	$session = date('h:i A', strtotime($time));
	$date = (settingValue('date_foramt'))?(date(settingValue('date_format'), strtotime($msg_value->created_at))):date('Y-m-d', strtotime($msg_value->created_at));
	if($self_info != $msg_value->sender_token){
?>
<div class="d-flex justify-content-start mb-4">
	<div class="img_cont_msg">
	</div>
	<div class="msg_cotainer">
		<?php echo $msg_value->message;?>
		<span class="msg_time"> <?php echo $date;?></span>
	</div>
</div>
<?php
}else{ ?>
<div class="d-flex justify-content-end mb-4">
	<div class="msg_cotainer_send">
		<?php echo $msg_value->message;?>
		<span class="msg_time_send"> <?php echo $date;?></span>
	</div>
	<div class="img_cont_msg">
	</div>
</div>
<?php } } ?>