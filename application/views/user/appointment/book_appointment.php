<?php

$service_id = $this->uri->segment('2');

$service_details = $this->db->where('id',$service_id)->from('services')->get()->row_array();

$category = $this->db->select('category')->where('id',$service_details['user_id'])->get('providers')->row()->category;
$procate = $this->db->select('category_type')->where('id',$service_details['category'])->get('categories')->row()->category_type;

$location = $service_details['service_location'];

$user_currency_code = '';
if (!empty($this->session->userdata('id'))) {
	$service_amount = $service_details['service_amount'];
	$type = $this->session->userdata('usertype');
	if ($type == 'user') {
		$user_currency = get_user_currency();
	} else if ($type == 'provider') {
		$user_currency = get_provider_currency();
	} else if ($type == 'freelancer') {
		$user_currency = get_provider_currency();
	}
	$user_currency_code = $user_currency['user_currency_code'];
	$service_amount = get_gigs_currency($service_details['service_amount'], $service_details['currency_code'], $user_currency_code);
} else {
	$user_currency_code = settings('currency');
	$service_amount = get_gigs_currency($service_details['service_amount'], $service_details['currency_code'], $user_currency_code);
}

//Offer Calculation
$actual_serviceamt = $service_amount;

$new_serviceamt = $service_amount;

$current_time = date('H:i:s');
$offers = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where( "'$current_time' BETWEEN start_time AND end_time",NULL, FALSE)->get("service_offers")->row_array();  

$offerPrice = ''; $offersid = 0;
if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
	$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100 ;	
	if(is_nan($offerPrice)) $offerPrice = 0;	
	$offerPrice = number_format($offerPrice,2);		
	$offersid = $offers['id'];
} 

//Reward Calculation
$reward = $this->db->where("status",1)->where("service_id",$service_id)->where('user_id',$this->session->userdata('id'))->get("service_rewards")->row_array(); 

$rewardPrice = ''; $rewardid = 0; $rtype = '';
if (!empty($reward['reward_type']) && $reward['reward_type'] == 1 && $reward['id'] > 0) {
	$rewardPrice = ($new_serviceamt) * $reward['reward_discount'] / 100 ; 	
	if(is_nan($rewardPrice)) $rewardPrice = 0;	
	$rewardPrice = number_format($rewardPrice,2);	
	$rewardid = $reward['id'];	
	
	$new_serviceamt  = $new_serviceamt - $rewardPrice;
	$new_serviceamt  = number_format($new_serviceamt,2); 
	
} else if ($reward['reward_type'] == 0 && $reward['id'] > 0) { 
	$rewardPrice  = 0;	
	$rewardid = $reward['id'];
	$rtype = '0';
	$new_serviceamt = 0;
}


$couponPrice = ''; $couponid = 0; 

//Total Calculation
$total_payable = $new_serviceamt ;

$card_service_amount = ($total_payable) * 100;


// Shop and Staff Details
$stfarr = explode(",",$service_details['staff_id']);
$stfres = $this->db->select('id, first_name AS name, home_service, designation, shop_service, home_service_area')->where_in('id',$stfarr)->where('provider_id',$service_details['user_id'])->where('status',1)->where('delete_status',0)->from('employee_basic_details')->order_by('id','DESC')->limit(1)->get()->result_array();

$shparr = explode(",",$service_details['shop_id']);
$shpres = $this->db->select('id, shop_name, shop_location')->where_in('id',$shparr)->where('provider_id',$service_details['user_id'])->where('status',1)->from('shops')->get()->result_array();

//Availability
if(!empty($shop_id) && $shop_id > 0 && $procate != 4){ 
	$shophours = $this->appointment->shop_hours($shop_id,$service_details['user_id']);
} else { 
	$shophours = $this->db->where('provider_id',$service_details['user_id'])->get('business_hours')->row_array();	
}

$arr = ''; 
if($shophours['all_days'] == 0){ 
	$daysarr = json_decode($shophours['availability'],true);
	foreach($daysarr as $val){
		if($val['day'] == 7) $val['day'] = 0;
		$arr .=  $val['day'].",";
	}
	$arr = rtrim($arr,',');
}

$payment_desc = "Book Service - ". $service_details['service_title'];

$convertedTime = strtotime(date('Y-m-d H:i:s', strtotime('+30 minutes')));

$SelAreaMsp = (!empty($this->user_language[$this->user_selected]['lg_HomeService_Area_Txt'])) ? $this->user_language[$this->user_selected]['lg_HomeService_Area_Txt'] : $this->default_language['en']['lg_HomeService_Area_Txt'];	
$fromtxt = (!empty($this->user_language[$this->user_selected]['lg_From'])) ? $this->user_language[$this->user_selected]['lg_From'] : $this->default_language['en']['lg_From'];
$totxt = (!empty($this->user_language[$this->user_selected]['lg_To'])) ? $this->user_language[$this->user_selected]['lg_To'] : $this->default_language['en']['lg_To'];

$boktxt = (!empty($this->user_language[$this->user_selected]['lg_Booking'])) ? $this->user_language[$this->user_selected]['lg_Booking'] : $this->default_language['en']['lg_Booking'];	
$exptxt = (!empty($this->user_language[$this->user_selected]['lg_Session_Expired'])) ? $this->user_language[$this->user_selected]['lg_Session_Expired'] : $this->default_language['en']['lg_Session_Expired'];	
$seltmetxt = (!empty($this->user_language[$this->user_selected]['lg_Select_Time_Slot'])) ? $this->user_language[$this->user_selected]['lg_Select_Time_Slot'] : $this->default_language['en']['lg_Select_Time_Slot'];	

$offerqry = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->get("service_offers")->row_array(); 
$ofsdate = ($offers['start_date'])?$offers['start_date']:$offerqry['start_date'];
$ofedate = ($offers['end_date'])?$offers['end_date']:$offerqry['end_date'];
$ofstime = ($offers['start_time'])?$offers['start_time']:$offerqry['start_time'];
$ofetime = ($offers['end_time'])?$offers['end_time']:$offerqry['end_time'];

?>
<div class="breadcrumb-bar">
    <div class="container">
		<div class="row justify-content-center">
            <div class="col-lg-10">
				<div class="row">
					<div class="col">
						<div class="breadcrumb-title">
							<h2><?php echo (!empty($user_language[$user_selected]['lg_Appointment'])) ? $user_language[$user_selected]['lg_Appointment'] : $default_language['en']['lg_Appointment']; ?></h2>
						</div>
					</div>
					<div class="col-auto float-end ms-auto breadcrumb-menu">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
								
								<li class="breadcrumb-item"><a href="<?php echo base_url()."all-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></a></li>
								
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Book_Appointment'])) ? $user_language[$user_selected]['lg_Book_Appointment'] : $default_language['en']['lg_Book_Appointment']; ?></li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="user-bookings">
				
					<ul class="nav nav-tabs menu-tabs">
						<li class="nav-item">
							<a class="nav-link active" id="bookings-tab" data-bs-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="true">
								<i class="fa fa-tag" aria-hidden="true"></i>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link noclick" id="guests-tab" data-bs-toggle="tab" href="#guests" role="tab" aria-controls="payments" aria-selected="true">
								<i class="fa fa-users" aria-hidden="true"></i>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests']; ?>
							</a>
						</li>
					</ul>
					
					 <div class="tab-content">						
					  
						<div class="tab-pane active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
						<form method="post" enctype="multipart/form-data" autocomplete="off" id="book_services">
							<div class="provider_info mb-4 clearfix">					  
								<label><?php echo (!empty($user_language[$user_selected]['lg_Service_Details'])) ? $user_language[$user_selected]['lg_Service_Details'] : $default_language['en']['lg_Service_Details']; ?> </label>
								<div class="card_body">					
									<table class="table table-bordered provider-info mb-0">							
										<tr>
											<td><span title="Service Name"><?php echo $service_details['service_title']; ?></span></td>
											<td><span><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> : <?php echo $service_details['duration']."<span class='small'>".$service_details['duration_in']."</span>"; ?></span> </td>
											
											<?php if($service_details['autoschedule'] == 1) { ?>
											<td><span><?php echo (!empty($user_language[$user_selected]['lg_No_of_Sessions'])) ? $user_language[$user_selected]['lg_No_of_Sessions'] : $default_language['en']['lg_No_of_Sessions']; ?> : <?php echo $service_details['autoschedule_session']; ?></span></td>
											<?php } ?>
										</tr>
										
										
										<?php if($rewardid > 0 && $reward['reward_type'] == 0) { ?>
										<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Rewards'])) ? $this->user_language[$this->user_selected]['lg_Rewards'] : $this->default_language['en']['lg_Rewards']; ?> </td><td colspan="2"><Strong><?php echo (!empty($this->user_language[$this->user_selected]['lg_Free_Service'])) ? $this->user_language[$this->user_selected]['lg_Free_Service'] : $this->default_language['en']['lg_Free_Service']; ?></strong></td></tr>
										<?php }  else { ?>
											<?php if($offerPrice != '' || $offerqry['id'] > 0) { ?>
											<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Offers'])) ? $this->user_language[$this->user_selected]['lg_Offers'] : $this->default_language['en']['lg_Offers']; ?> </td><td colspan="2"><Strong><?php echo ($offers['offer_percentage'])?$offers['offer_percentage']:$offerqry['offer_percentage']; ?>%  <?php echo (!empty($this->user_language[$this->user_selected]['lg_Offers_On_Txt'])) ? $this->user_language[$this->user_selected]['lg_Offers_On_Txt'] : $this->default_language['en']['lg_Offers_On_Txt']; ?> </strong><br><small>(<?php echo date("d M", strtotime($ofsdate)).' '.$totxt.' '.date("d M, Y", strtotime($ofedate)).' '.$fromtxt.' '.date("G:i A", strtotime($ofstime)).' - '.date("G:i A", strtotime($ofetime));?>)</small></td></tr>
											<?php } ?>
											
											<?php if($rewardid > 0 && $reward['reward_type'] == 1) { ?>
											<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Rewards'])) ? $this->user_language[$this->user_selected]['lg_Rewards'] : $this->default_language['en']['lg_Rewards']; ?> </td><td colspan="2"><Strong><?php echo $reward['reward_discount']; ?>% <?php echo (!empty($this->user_language[$this->user_selected]['lg_offer'])) ? $this->user_language[$this->user_selected]['lg_offer'] : $this->default_language['en']['lg_offer']; ?></strong> <i class="text-info small">(<?php echo $reward['description']; ?>)</i></td></tr>
											<?php } ?>
											
											
											<?php if($couponPrice != '') { ?>
											<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Coupon'])) ? $this->user_language[$this->user_selected]['lg_Coupon'] : $this->default_language['en']['lg_Coupon'] ?> </td><td colspan="2"><Strong><?php echo $coupon['coupon_name']; ?></strong> <i class="text-info small">(<?php echo $coupon['description']; ?>)</i></td></tr>
											<?php } ?>
										<?php } ?>
										
									</table>
									<?php if($service_details['autoschedule'] == 1) { ?>
									<span class="text-info small">*** <?php echo (!empty($this->user_language[$this->user_selected]['lg_Auto_Session_Txt'])) ? $this->user_language[$this->user_selected]['lg_Auto_Session_Txt'] : $this->default_language['en']['lg_Auto_Session_Txt'] ?> - <?php echo $service_details['autoschedule_days']; ?> <?php echo (!empty($this->user_language[$this->user_selected]['lg_Days'])) ? $this->user_language[$this->user_selected]['lg_Days'] : $this->default_language['en']['lg_Days'] ?>.</span>
									<?php } ?>
														 
								</div>
							</div>
					
				   
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input type="hidden" name="currency_code" id="currency_code" value="<?php echo $user_currency_code; ?>">
							
														
							<div class="row">	
								<div class="col-lg-6">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?><span class="text-danger">*</span></label><br>
										<div>
											<select id="shop_id" name="shop_id" class="form-control select addshopcls">
												<?php foreach($shpres as $sval) { ?>
													<option value="<?php echo $sval['id']; ?>"><?php echo $sval['shop_name']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label><span class="locationtitle"><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></span> <span class="text-danger">*</span></label>

										<?php if($location=="") { ?>
											<input class="form-control" type="text" name="service_location" id="service_location" required value="<?php echo $location; ?>" >
										<?php } else { ?>
											<input class="form-control" type="text" name="service_location" id="service_location" required value="<?php echo $location; ?>" disabled>
										<?php } ?>

										<input type="hidden" class="form-control" id="map_key" value="<?php echo $map_key?>" >
										<input type="hidden" name="service_latitude" id="service_latitude" value="<?php echo $service_details['service_latitude'];?>">
										<input type="hidden" name="service_longitude" id="service_longitude" value="<?php echo $service_details['service_longitude']; ?>">
										<input type="hidden" name="service_address" id="service_address" value="<?php echo $this->session->userdata('user_address');?>">
									</div>
									<div id="map" class="d-none"></div>                           
								</div>
								<input class="serviceat"  type="radio" name="service_at" value="2" hidden checked>
								
							</div>
							
							
							<div class="row">
								
								<input type="hidden" id="staff_id" name="staff_id" value= "<?php echo $stfres[0]['id']; ?>">
								<div class="col-lg-6">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?></label>
										<input class="form-control" type="text" name="service_amount" id="service_amount" value="<?php echo currency_conversion($user_currency_code) . $service_amount; ?>" readonly="">
									</div>
								</div>
								
								
								<?php  $addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$service_id)->get('additional_services')->result_array();

									if(count($addi_ser) > 0) {  
								?>
								<div class="col-lg-12">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services']; ?></label>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="add-ser-list">
										<?php foreach($addi_ser as $a => $ai) { ?>
										<div class="additional-service">
											<div class="additional-content">
												<div class="form-check">
													<input type="checkbox" class="form-check-input addiservice" name="additional_services[]" value="<?php echo $ai['id']; ?>" id="addichkbox_<?php echo $a; ?>" data-durationval="<?php echo $ai['duration']; ?>" data-amountval="<?php echo get_gigs_currency($ai['amount'], $service_details['currency_code'], $user_currency_code); ?>">
													<label class="form-check-label mb-0" for="addichkbox_<?php echo $a; ?>"><?php echo $ai['service_name']; ?>
													<p><?php echo $ai['duration']."<span class='small'>".$ai['duration_in']."</span>"; ?></span> </p></label>
												</div>
											</div>
											<div class="additional-price">
												<?php echo  currency_conversion($user_currency_code) . get_gigs_currency($ai['amount'], $service_details['currency_code'], $user_currency_code);; ?></span>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
								<?php } ?>
								
								<div class="col-lg-6">
								   <div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?> <span class="text-danger">*</span></label>
										<input class="form-control bookingdate" type="text" name="bookingdate" id="bookingdate" />

										
									</div>
								</div>

								<div class="col-lg-6">
									<div class="form-group">
										<label><?php echo (!empty($user_language[$user_selected]['lg_Time_Slot'])) ? $user_language[$user_selected]['lg_Time_Slot'] : $default_language['en']['lg_Time_Slot']; ?> <span class="text-danger">*</span></label>
										<select class="form-control from_time checkTime" name="" id="from_time" >
										</select>

									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<div class="text-center">
											<div id="load_div"></div>
										</div>
										<label><?php echo (!empty($user_language[$user_selected]['lg_Notes'])) ? $user_language[$user_selected]['lg_Notes'] : $default_language['en']['lg_Notes']; ?></label>
										<textarea class="form-control" name="notes" id="notes" rows="5"></textarea>
									</div>
								</div>
							
							</div>
							
												
							<input type="hidden" id="service_offerid" name="service_offerid" value="<?php echo $service_id; ?>">
							<input type="hidden" name="provider_id" id="provider_id" value="<?php echo $service_details['user_id'] ?>">
														
							<input type="hidden" name="duration" id="duration" value="<?php echo $service_details['duration'] ?>">
							<input type="hidden" name="dur_in" id="dur_in" value="<?php echo $service_details['duration_in'] ?>">
							
							<input type="hidden" name="daysCount" id="daysCount" value="<?php echo $arr; ?>" />
							<input type="hidden" name="shoplocation" id="shoplocation" value="<?php echo $location; ?>">
							<input type="hidden" name="isauto" id="isauto" value="<?php echo $service_details['autoschedule']; ?>" />
							<input type="hidden" name="procate" id="procate" value="<?php echo $procate; ?>" />
							
							<input type="hidden" id="book_id" name="book_id" value="0" />
							<input type="hidden" id="offersid" name="offersid" value="<?php echo $offersid; ?>" />
							<input type="hidden" id="couponid" name="couponid" value="0" />
							<input type="hidden" id="rewardid" name="rewardid" value="<?php echo $rewardid; ?>"/>
							<input type="hidden" id="rewardtype" name="rewardtype" value="<?php echo $rtype; ?>"/>
							
							<input type="hidden" id="estTime" value="<?php echo $convertedTime; ?>" />
							<input type="hidden" id="SelAreaMsp" value="<?php echo $SelAreaMsp; ?>" />
							<input type="hidden" id="total_amt" name="total_amt" value="<?php echo $total_payable; ?>">
							
							
							<div class="submit-section">
								
								<button class="btn btn-primary submit-btn submit_service_book" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" data-id="<?php echo $service_id; ?>" data-provider="<?php echo $service_details['user_id'] ?>" data-amount="<?php echo $service_amount; ?>"  type="submit" id="submit" value="submit"><?php echo (!empty($user_language[$user_selected]['lg_Confirm_Booking'])) ? $user_language[$user_selected]['lg_Confirm_Booking'] : $default_language['en']['lg_Confirm_Booking']; ?></button>
								
								<a class="btn btn-danger appoint-btncls" href="<?php echo base_url(); ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_Cancel_Booking'])) ? $user_language[$user_selected]['lg_Cancel_Booking'] : $default_language['en']['lg_Cancel_Booking']; ?></a>
								
							</div>						
						    </form>
						</div>
							
						<div class="tab-pane fade" id="guests" role="tabpanel" aria-labelledby="guests-tab">
							<form method="post" enctype="multipart/form-data" autocomplete="off" id="guest_book_services">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
						   <div class="row">
								<span class="text-info small mb-3">*<?php echo (!empty($user_language[$user_selected]['lg_More_Service_Guest_Txt'])) ? $user_language[$user_selected]['lg_More_Service_Guest_Txt'] : $default_language['en']['lg_More_Service_Guest_Txt']; ?></span>
								<div id="guestdiv">
									<?php 
										$addiqry = $this->db->select('GROUP_CONCAT( DISTINCT service_id) as addi_service')->where('status',1)->where('provider_id',$service_details['user_id'])->get('additional_services')->row_array();
																												
										$this->db->select('id, service_title, staff_id, service_amount, duration, currency_code');
										$this->db->where('user_id',$service_details['user_id']);
										$this->db->where('shop_id',$service_details['shop_id']);
										$this->db->where('status',1);
										if(!empty($addiqry['addi_service'])) {
											$addiarr = explode(",", $addiqry['addi_service']);
										}
										$this->db->order_by('service_title','ASC');
										$serqry = $this->db->get('services')->result_array();
										
									?>
									<div class="guest_details">
										<div class="guest-check">
											<div class="form-check">
											<input type="checkbox" class="form-check-input service_offer_guest" name="service_offered_guest[]" value="1" id="chkbox_1">
											</div>
										</div>
										<div class="guest-input">
											<div class="row">
												<div class="col-lg-3">
													<div class="form-group">
														<label><?php echo (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For']; ?> </label>
														<select class="form-control servicefor" id="service_for1" name="service_for[]" disabled data-id="1">
															<option value="1"><?php echo (!empty($user_language[$user_selected]['lg_Myself'])) ? $user_language[$user_selected]['lg_Myself'] : $default_language['en']['lg_Myself']; ?></option>
															<option value="2"><?php echo (!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests']; ?></option>
													   </select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label><?php echo (!empty($user_language[$user_selected]['lg_Guest_Name'])) ? $user_language[$user_selected]['lg_Guest_Name'] : $default_language['en']['lg_Guest_Name']; ?> </label>
														<input type="text" name="guest_name[]" id="guest_name1" class="form-control ginput" value=" " readonly="" />
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label><?php echo (!empty($user_language[$user_selected]['lg_Services'])) ? $user_language[$user_selected]['lg_Services'] : $default_language['en']['lg_Services']; ?> </label>
														<select class="form-control guestservice ginput" id="guest_ser1" name="guest_ser[]"  class="form-control" disabled data-id="1">
															<option value="">Services</option>
															<?php foreach($serqry as $sr) { 
																$service_amount = get_gigs_currency($sr['service_amount'], $sr['currency_code'], $user_currency_code);
															?>
															<option value="<?php echo $sr['id']; ?>" data-staffid="<?php echo $sr['staff_id'];?>" data-seramount="<?php echo $service_amount;?>" data-serduration="<?php echo $sr['duration'];?>" data-currency_sign="<?php echo currency_conversion($user_currency_code); ?>" ><?php echo $sr['service_title']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?> </label>
														<input type="text" name="guest_seramt[]" id="guest_seramt1" class="form-control" value="" disabled />
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> </label>
														<div class="input-group">
															<input type="text" name="guest_serdur[]" id="guest_serdur1" class="form-control" value="" disabled />
															<div class="input-group-append">
																<span class="input-group-text" id="basic-addon2"><?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?></span>
															</div>
															<input type="hidden" class="form-control" name="duration_in" id="duration_in" value="hr(s)">
														</div>
													</div>
												</div>
												
												<div class="col-lg-3">
													<div class="form-group">	
														<label><?php echo (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; ?> </label>
														<select class="form-control gstaff ginput" id="guest_serstf1" name="guest_serstf[]"  class="form-control" disabled data-id="1">
															
														</select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">	
														<label><?php echo (!empty($user_language[$user_selected]['lg_Time_Slot'])) ? $user_language[$user_selected]['lg_Time_Slot'] : $default_language['en']['lg_Time_Slot']; ?> </label>
														<select class="form-control checkGuestTime ginput" id="guest_sertime1" name="guest_sertime[]"  class="form-control" disabled data-id="1">
																								
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="add-more-guest form-group">
									<a href="javascript:void(0);" class="add-guest"><i class="fas fa-plus-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_Add_More'])) ? $user_language[$user_selected]['lg_Add_More'] : $default_language['en']['lg_Add_More']; ?></a>
								</div>
								<input id="rowcount" value="1" type="hidden">
							</div>						
						
						
							<div class="submit-section mt-4">
								<button class="btn btn-primary pay-submit-btn submit_servicebook appoint-btncls" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Proceed to Payment" type="button" id="submit_button_id"><?php echo (!empty($user_language[$user_selected]['lg_Proceed_To_Payment'])) ? $user_language[$user_selected]['lg_Proceed_To_Payment'] : $default_language['en']['lg_Proceed_To_Payment']; ?></button>
								<a class="btn btn-danger cancelappt appoint-btncls" href="javascript:void(0)"><?php echo (!empty($user_language[$user_selected]['lg_Cancel_Booking'])) ? $user_language[$user_selected]['lg_Cancel_Booking'] : $default_language['en']['lg_Cancel_Booking']; ?></a>								
								
							</div>
							</form>
							</div>
						
					</div>
					
					<input type="hidden" id="booktxt" value="<?php echo $boktxt; ?>">
					<input type="hidden" id="expiretxt" value="<?php echo $exptxt; ?>">
					<input type="hidden" id="selecttimetxt" value="<?php echo $seltmetxt; ?>">
				</div>
            </div>
        </div>
    </div>
</div>

