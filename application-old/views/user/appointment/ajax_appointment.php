<?php 

$this->db->select('id, service_title, staff_id, service_amount, duration, currency_code');
$this->db->where('user_id',$user_id);
$this->db->where('shop_id',$shop_id);
$this->db->where('status',1);
if($service_id > 0) {
}
$this->db->order_by('service_title','ASC');
$serqry = $this->db->get('services')->result_array();

$user_currency_code = '';
if (!empty($this->session->userdata('id'))) {
	$service_amount = $serqry['service_amount'];
	$type = $this->session->userdata('usertype');
	if ($type == 'user') {
		$user_currency = get_user_currency();
	} else if ($type == 'provider') {
		$user_currency = get_provider_currency();
	} else if ($type == 'freelancer') {
		$user_currency = get_provider_currency();
	}
	$user_currency_code = $user_currency['user_currency_code'];	
} else {
	$user_currency_code = settings('currency');
}

?>
<div class="guest_details">
	<div class="guest-check">
		<div class="form-check">
			<input type="checkbox" class="form-check-input service_offer_guest" name="service_offered_guest[]" value="<?php echo $count; ?>" id="chkbox_<?php echo $count; ?>">
		</div>
	</div>
<div class="guest-input">
<div class="row">
	<div class="col-lg-3"><div class="form-group">
		<label><?php echo (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For']; ?> </label>
		<select class="form-control servicefor" id="service_for<?php echo $count; ?>" name="service_for[]" disabled data-id="<?php echo $count; ?>">
			<option value="1"><?php echo (!empty($user_language[$user_selected]['lg_Myself'])) ? $user_language[$user_selected]['lg_Myself'] : $default_language['en']['lg_Myself']; ?></option>
			<option value="2"><?php echo (!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests']; ?></option>
	   </select>
	</div></div>
	<div class="col-lg-3"><div class="form-group">
		<label><?php echo (!empty($user_language[$user_selected]['lg_Guest_Name'])) ? $user_language[$user_selected]['lg_Guest_Name'] : $default_language['en']['lg_Guest_Name']; ?> </label>
		<input type="text" name="guest_name[]" id="guest_name<?php echo $count; ?>" class="form-control ginput" value=" " readonly="" />
	</div></div>
	<div class="col-lg-3"><div class="form-group">
		<label><?php echo (!empty($user_language[$user_selected]['lg_Services'])) ? $user_language[$user_selected]['lg_Services'] : $default_language['en']['lg_Services']; ?> </label>
		<select class="form-control guestservice" id="guest_ser<?php echo $count; ?>" name="guest_ser[]"  class="form-control ginput" disabled data-id="<?php echo $count; ?>">
			<option value="">Services</option>
			<?php foreach($serqry as $sr) { 
				$service_amount = get_gigs_currency($sr['service_amount'], $sr['currency_code'], $user_currency_code);
			?>
				<option value="<?php echo $sr['id']; ?>" data-staffid="<?php echo $sr['staff_id'];?>" data-seramount="<?php echo $service_amount;?>" data-serduration="<?php echo $sr['duration'];?>" data-currency_sign="<?php echo currency_conversion($user_currency_code); ?>" ><?php echo $sr['service_title']; ?></option>
			<?php } ?>
		</select>
	</div></div>	
		
	
	<div class="col-lg-3"><div class="form-group">
		<label><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?> </label>
		<input type="text" name="guest_seramt[]" id="guest_seramt<?php echo $count; ?>" class="form-control" value="" disabled  />
	</div></div>
		
	<div class="col-lg-3"><div class="form-group">
		<label><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> </label>
		
		 <div class="input-group">
		 <input type="text" name="guest_serdur[]" id="guest_serdur<?php echo $count; ?>" class="form-control" value="" disabled  />
		  <div class="input-group-append">
			<span class="input-group-text" id="basic-addon2"><?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?></span>
		  </div>
		  <input type="hidden" class="form-control" name="duration_in" id="duration_in" value="min(s)">
		</div>
		
		
		
	</div></div>
	
	
	<?php if($procate == '4') { ?>
		<input type="hidden" title="Staffs" name="guest_serstf[]" id="guest_serstf<?php echo $count; ?>" value="0" class="gstaff"/>
	<?php } else { ?>
	<div class="col-lg-3"><div class="form-group">	
		<label><?php echo (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; ?> </label>
		<select class="form-control gstaff" id="guest_serstf<?php echo $count; ?>" name="guest_serstf[]"  class="form-control ginput" disabled data-id="<?php echo $count; ?>">			
		</select>
	</div></div>
	<?php } ?>
	<div class="col-lg-3"><div class="form-group">	
		<label><?php echo (!empty($user_language[$user_selected]['lg_Time_Slot'])) ? $user_language[$user_selected]['lg_Time_Slot'] : $default_language['en']['lg_Time_Slot']; ?> </label>
		<select class="form-control checkGuestTime" id="guest_sertime<?php echo $count; ?>" name="guest_sertime[]"  class="form-control ginput" disabled data-id="<?php echo $count; ?>">
												
		</select>
	</div></div>
	<div class="col-lg-2"><div class="form-group"></div></div>
	<div class="col-lg-1"><div class="form-group"></div></div>
	<div class="remove-guest form-group col-lg-3 float-right">
		<a href="#" class="remove_guest"><i class="far fa-times-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_Remove_Services'])) ? $user_language[$user_selected]['lg_Remove_Services'] : $default_language['en']['lg_Remove_Services']; ?></a>
	</div>
</div>
</div>
</div>