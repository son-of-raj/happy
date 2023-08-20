<?php
$book_id = $this->uri->segment('2');

$book_details = $this->db->where('id',$book_id)->from('book_service')->get()->row_array();

$service_id = $book_details['service_id'];

$shop_id = $book_details['shop_id'];

$staffid = $book_details['staff_id'];

$home_service = $book_details['home_service'];

$offersid = $book_details['offersid'];

$couponid = $book_details['couponid'];

$rewardid = $book_details['rewardid'];

$book_date = date('d-m-Y',strtotime($book_details['service_date']));

$ftime = date('h:i A',strtotime($book_details['from_time']));
$ttime = date('h:i A',strtotime($book_details['to_time']));
$btime = $ftime."-".$ttime;

$service_details = $this->db->where('id',$service_id)->from('services')->get()->row_array();

$category = $this->db->select('category')->where('id',$service_details['user_id'])->get('providers')->row()->category;
$procate = $this->db->select('category_type')->where('id',$category)->get('categories')->row()->category_type;

$location  = $book_details['location'];
if($home_service == 1){	
	$home_location = $book_details['location'];
} else {	
	$home_location = $this->session->userdata('user_address');;
}

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

$service_amount = $book_details['amount'];

$offers = $this->db->where("id",$offersid)->get("service_offers")->row_array(); 
$offerPrice = ''; 
if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0) {
	$offerPrice = ($actual_serviceamt) * $offers['offer_percentage'] / 100 ;		
	$offerPrice = number_format($offerPrice,2);		
} 

//Coupon Calculation
$coupon = $this->db->where("id",$couponid)->get("service_coupons")->row_array(); 
$couponPrice = '';  
if (!empty($coupon['coupon_type']) && $coupon['coupon_type'] == 1) {
	$couponPrice = ($actual_serviceamt) * $coupon['coupon_percentage'] / 100 ; 
	$couponPrice = number_format($couponPrice,2);	
} else if (!empty($coupon['coupon_type']) && $coupon['coupon_type'] == 2) {
	$couponPrice  = number_format($coupon['coupon_amount'],2);		
}

//Reward Calculation
$reward = $this->db->where("id",$rewardid)->get("service_rewards")->row_array(); 

$rewardPrice = ''; $rtype = '';
if (!empty($reward['reward_type']) && $reward['reward_type'] == 1) {
	$rewardPrice = ($actual_serviceamt) * $reward['reward_discount'] / 100 ; 	
	if(is_nan($rewardPrice)) $rewardPrice = 0;	
	$rewardPrice = number_format($rewardPrice,2);		
} else if (!empty($reward['reward_type']) && $reward['reward_type'] == 0) {
	$rewardPrice  = 0;	
	$rtype = '0';
}

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

// Shop and Staff Details
$stfarr = explode(",",$service_details['staff_id']);
$stfres = $this->db->select('id, first_name AS name, home_service, designation, shop_service, home_service_area')->where_in('id',$stfarr)->where('provider_id',$service_details['user_id'])->where('status',1)->where('delete_status',0)->from('employee_basic_details')->order_by('first_name','ASC')->get()->result_array();

$shparr = explode(",",$service_details['shop_id']);
$shpres = $this->db->select('id, shop_name')->where_in('id',$shparr)->where('status',1)->where('provider_id',$service_details['user_id'])->from('shops')->get()->result_array();

$convertedTime = strtotime(date('Y-m-d H:i:s', strtotime('+15 minutes')));

$SelAreaMsp = (!empty($this->user_language[$this->user_selected]['lg_HomeService_Area_Txt'])) ? $this->user_language[$this->user_selected]['lg_HomeService_Area_Txt'] : $this->default_language['en']['lg_HomeService_Area_Txt'];	



$bservices = $this->db->select('id, service_id, currency_code, amount,service_for, guest, guest_parent_bookid, guest_name, service_date, from_time, to_time, shop_id, staff_id')->where('guest_parent_bookid = '.$book_details['id'])->get('book_service')->result_array(); 


$boktxt = (!empty($this->user_language[$this->user_selected]['lg_Booking'])) ? $this->user_language[$this->user_selected]['lg_Booking'] : $this->default_language['en']['lg_Booking'];	
$exptxt = (!empty($this->user_language[$this->user_selected]['lg_Session_Expired'])) ? $this->user_language[$this->user_selected]['lg_Session_Expired'] : $this->default_language['en']['lg_Session_Expired'];	
$seltmetxt = (!empty($this->user_language[$this->user_selected]['lg_Select_Time_Slot'])) ? $this->user_language[$this->user_selected]['lg_Select_Time_Slot'] : $this->default_language['en']['lg_Select_Time_Slot'];	
$sesdatetxt = (!empty($this->user_language[$this->user_selected]['lg_Session_Date_Err'])) ? $this->user_language[$this->user_selected]['lg_Session_Date_Err'] : $this->default_language['en']['lg_Session_Date_Err'];	
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
					<div class="col-auto float-right ml-auto breadcrumb-menu">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
								
								<li class="breadcrumb-item"><a href="<?php echo base_url()."user-bookings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Booking'])) ? $user_language[$user_selected]['lg_Booking'] : $default_language['en']['lg_Booking']; ?></a></li>
								
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Edit_Appointment'])) ? $user_language[$user_selected]['lg_Edit_Appointment'] : $default_language['en']['lg_Edit_Appointment']; ?></li>
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
						<?php if(count($bservices) > 0) { ?>
						<ul class="nav nav-tabs menu-tabs mt-4 ">
					  <li class="nav-item active">
                        <a class="nav-link" id="bookings-tab" data-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="true"><i class="fa fa-tag" aria-hidden="true"></i>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Booking'])) ? $user_language[$user_selected]['lg_Booking'] : $default_language['en']['lg_Booking']; ?>
</a>
                    </li>
					
                    <li class="nav-item">
                        <a class="nav-link noclick" id="guests-tab" data-toggle="tab" href="#guests" role="tab" aria-controls="payments" aria-selected="true"><i class="fa fa-users" aria-hidden="true"></i>&nbsp;<?php echo (!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests']; ?>
						</a>
                    </li>
					
					</ul>
					<?php } ?>
					
					<form method="post" enctype="multipart/form-data" autocomplete="off" id="book_services" >
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
					
					<div class="tab-content" id="myTabContent">						
					  
						<div class="tab-pane fade show active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
						
				<div class="provider_info mb-4 clearfix">
					<label><?php echo (!empty($user_language[$user_selected]['lg_Service_Details'])) ? $user_language[$user_selected]['lg_Service_Details'] : $default_language['en']['lg_Service_Details']; ?> </label>
                    <div class="card_body">					
						<table class="table table-bordered provider-info mb-0">
							<tr>
								<td><span title="Service Name"><?php echo $service_details['service_title']; ?></span></td>
								<td><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?>: <?php echo currency_conversion($user_currency_code) . $actual_serviceamt; ?></span></td>
								<td><span><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> : <?php echo $service_details['duration']."<span class='small'>".$service_details['duration_in']."</span>"; ?></span></td>
								
								<?php if($service_details['autoschedule'] == 1) { ?>
								<td><span><?php echo (!empty($user_language[$user_selected]['lg_No_of_Sessions'])) ? $user_language[$user_selected]['lg_No_of_Sessions'] : $default_language['en']['lg_No_of_Sessions']; ?> :  <?php echo $service_details['autoschedule_session']; ?></span></td>
								<?php } ?>
								
								<?php if($rewardid > 0 && $reward['reward_type'] == 0) { ?>
								<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Rewards'])) ? $this->user_language[$this->user_selected]['lg_Rewards'] : $this->default_language['en']['lg_Rewards']; ?> </td><td colspan="2"><Strong><?php echo (!empty($this->user_language[$this->user_selected]['lg_Free_Service'])) ? $this->user_language[$this->user_selected]['lg_Free_Service'] : $this->default_language['en']['lg_Free_Service']; ?></strong></td></tr>
								<?php } else { ?>
								
									<?php if($offersid > 0) { ?>
									<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Offer_Applied'])) ? $this->user_language[$this->user_selected]['lg_Offer_Applied'] : $this->default_language['en']['lg_Offer_Applied']; ?> </td><td colspan="3"><?php echo $offers['offer_percentage']; ?>%  <?php echo (!empty($this->user_language[$this->user_selected]['lg_Offers_On_Txt'])) ? $this->user_language[$this->user_selected]['lg_Offers_On_Txt'] : $this->default_language['en']['lg_Offers_On_Txt']; ?></td></tr>
									<?php } ?>
									<?php if($rewardid > 0 && $reward['reward_type'] == 1) { ?>
									<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Rewards'])) ? $this->user_language[$this->user_selected]['lg_Rewards'] : $this->default_language['en']['lg_Rewards']; ?></td><td colspan="3"><?php echo $reward['reward_discount']; ?>% <?php echo (!empty($this->user_language[$this->user_selected]['lg_offer'])) ? $this->user_language[$this->user_selected]['lg_offer'] : $this->default_language['en']['lg_offer']; ?> <i class="text-info small">(<?php echo $reward['description']; ?>)</i></td></tr>
									<?php } ?>
									
									<?php if($couponid > 0) { ?>
									<tr><td><?php echo (!empty($this->user_language[$this->user_selected]['lg_Coupon_Applied'])) ? $this->user_language[$this->user_selected]['lg_Coupon_Applied'] : $this->default_language['en']['lg_Coupon_Applied']; ?> </td><td colspan="3"><?php echo $coupon['coupon_name']; ?><i class="text-info small">(<?php echo $coupon['description']; ?>)</i></td></tr>
									<?php } ?>
								<?php } ?>
							</tr>
						</table>
						<?php if($service_details['autoschedule'] == 1) { ?>
						<span class="text-info small">*** <?php echo (!empty($this->user_language[$this->user_selected]['lg_Auto_Session_Txt'])) ? $this->user_language[$this->user_selected]['lg_Auto_Session_Txt'] : $this->default_language['en']['lg_Auto_Session_Txt'] ?> - <?php echo $service_details['autoschedule_days']; ?> <?php echo (!empty($this->user_language[$this->user_selected]['lg_Days'])) ? $this->user_language[$this->user_selected]['lg_Days'] : $this->default_language['en']['lg_Days'] ?>.</span>
						<?php } ?>
						
                        
                    </div>
                </div>

                
					
					<div class="row">	
						<div class="col-lg-6">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop']; ?><span class="text-danger">*</span></label><br>
								<div>
									<select id="shop_id" name="shop_id" class="form-control select editshopcls">
										<?php foreach($shpres as $sval) { ?>
											<option value="<?php echo $sval['id']; ?>" <?php echo ($shop_id == $sval['id'])?'selected = "selected"':''; ?>><?php echo ucwords($sval['shop_name']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
                        <div class="col-lg-6">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Do_You_Want_the_Service'])) ? $user_language[$user_selected]['lg_Do_You_Want_the_Service'] : $default_language['en']['lg_Do_You_Want_the_Service']; ?> <span class="text-danger">*</span></label><br>
								<div>									
									<label class="radio-inline"><input class="serviceat"  type="radio" name="service_at" value="2" <?php echo ($home_service == 2)?'checked':'disabled'; ?>> <?php echo (!empty($user_language[$user_selected]['lg_At_Shop'])) ? $user_language[$user_selected]['lg_At_Shop'] : $default_language['en']['lg_At_Shop']; ?> </label>
									<label class="radio-inline"><input class="serviceat"  type="radio" name="service_at" value="1" <?php echo ($home_service == 1)?'checked':'disabled'; ?>> <?php echo (!empty($user_language[$user_selected]['lg_At_Home'])) ? $user_language[$user_selected]['lg_At_Home'] : $default_language['en']['lg_At_Home']; ?></label>
								</div>
							</div>
						</div>
						
					</div>
					
					
                    <div class="row">
                        <div class="col-lg-12">
						    <div class="form-group">
                                <label><span class="locationtitle"><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></span> <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="service_location" id="service_location" required value="<?php echo $location  ; ?>" readonly>
								<input type="hidden" class="form-control" id="map_key" value="<?php echo $map_key?>" >
                                <input type="hidden" name="service_latitude" id="service_latitude" value="<?php echo $book_details['latitude']; ?>">
                                <input type="hidden" name="service_longitude" id="service_longitude" value="<?php echo $book_details['longitude']; ?>">
								<input type="hidden" name="service_address" id="service_address" value="<?php echo $home_location; ?>">
                            </div> 
							<div id="map" class="d-none"></div>                          
                        </div>
						<?php if($procate == '4') { ?>
							<input type="hidden" title="Staffs" name="staff_id" id="staff_id" value="0" />
						<?php } else { ?>
						<div class="col-lg-6">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; ?><span class="text-danger">*</span></label><br>
								<div>									
									<select id="staff_id" name="staff_id" class="form-control select">
										<option value=""><?php echo (!empty($user_language[$user_selected]['lg_Select_Staff'])) ? $user_language[$user_selected]['lg_Select_Staff'] : $default_language['en']['lg_Select_Staff']; ?></option>
										<?php foreach($stfres as $val) { 
												if($val['shop_service'] == 1 && $val['home_service'] == 2){
													$homeservice = 2; // Shop & Home
												} else if($val['shop_service'] == 0 && $val['home_service'] == 2){
													$homeservice = 1; // Only Home
												} else {
													$homeservice = 0; // Only Shop
												}
										?>
											<option value="<?php echo $val['id']; ?>" data-homeservice="<?php echo $homeservice;?>" data-homeservicearea="<?php echo $val['home_service_area'];?>" <?php echo ($staffid == $val['id'])?'selected = "selected"':''; ?>><?php echo ucwords($val['name']); ?><i class='small'></i></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<?php } ?>
                        <div class="col-lg-6 ">
                            <div class="form-group">
                                <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount'])) ? $user_language[$user_selected]['lg_Service_Amount'] : $default_language['en']['lg_Service_Amount']; ?></label>
                                <input class="form-control" type="text" name="service_amount" id="service_amount" value="<?php echo currency_conversion($user_currency_code) . $service_amount; ?>" readonly="">
                            </div>
                        </div>

						<?php  $aiarr = explode(",", $book_details['additional_services']);
						$durval = 0;
						$addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$service_id)->get('additional_services')->result_array();
									if(count($addi_ser) > 0) {  
								?>
						<div class="col-lg-12">
							<div class="form-group">
								<label><?php echo (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services']; ?></label>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="add-ser-list">
								<?php foreach($addi_ser as $a => $ai) {
									if(in_array($ai['id'],$aiarr)) {
										$durval += $ai['duration'];
									}
								?>
								<div class="additional-service">
									<div class="additional-content">
										<div class="form-check">
											<input type="checkbox" class="form-check-input addiservice" name="additional_services[]" value="<?php echo $ai['id']; ?>" id="addichkbox_<?php echo $a; ?>" data-durationval="<?php echo $ai['duration']; ?>" data-amountval="<?php echo get_gigs_currency($ai['amount'], $service_details['currency_code'], $user_currency_code); ?>" disabled <?php echo (in_array($ai['id'],$aiarr))?'checked':'';?>>
											<label class="form-check-label mb-0" for="addichkbox_<?php echo $a; ?>"><span title="Service Name"><?php echo $ai['service_name']; ?>
												<p><?php echo $ai['duration']."<span class='small'>".$ai['duration_in']."</span>"; ?></p>
											</label>
										</div>
									</div>
									<div class="additional-price">
										<?php echo  currency_conversion($user_currency_code) . get_gigs_currency($ai['amount'], $service_details['currency_code'], $user_currency_code);; ?>
									</div>
								</div>
								<?php } } ?>
							</div>
						</div>

                        <div class="col-lg-6 ">
                           <div class="form-group">
                                <label><?php echo (!empty($user_language[$user_selected]['lg_Date'])) ? $user_language[$user_selected]['lg_Date'] : $default_language['en']['lg_Date']; ?> <span class="text-danger">*</span></label>
                                <input class="form-control booking_date" type="text" name="bookingdate" id="bookingdate" value="<?php  echo $book_date; ?>" />

                                
                            </div>
                        </div>

                        <div class="col-lg-6 ">
                            <div class="form-group">
                                <label><?php echo (!empty($user_language[$user_selected]['lg_Time_Slot'])) ? $user_language[$user_selected]['lg_Time_Slot'] : $default_language['en']['lg_Time_Slot']; ?> <span class="text-danger">*</span></label>
                                 <select class="form-control from_time" name="from_time" id="from_time" required>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="text-center">
                                    <div id="load_div"></div>
                                </div>
                                <label><?php echo (!empty($user_language[$user_selected]['lg_Notes'])) ? $user_language[$user_selected]['lg_Notes'] : $default_language['en']['lg_Notes']; ?></label>
                                <textarea class="form-control" name="notes" id="notes" rows="5"><?php  echo $book_details['notes']; ?></textarea>
                            </div>
                        </div>
					
                    </div>
					<input type="hidden" id="service_offerid" name="service_offerid" value="<?php echo $service_id; ?>">
					<input type="hidden" name="provider_id" id="provider_id" value="<?php echo $service_details['user_id'] ?>">
					<input type="hidden" name="staffid" id="staffid" value="<?php echo $staffid; ?>">
					
					<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $shop_id; ?>">				
					<input type="hidden" name="duration" id="duration" value="<?php echo $service_details['duration'] + $durval;  ?>">
					<input type="hidden" name="dur_in" id="dur_in" value="<?php echo $service_details['duration_in'] ?>">
					
					<input type="hidden" name="daysCount" id="daysCount" value="<?php echo $arr; ?>" />
					<input type="hidden" name="shoplocation" id="shoplocation" value="<?php echo $location; ?>">
					<input type="hidden" name="isauto" id="isauto" value="<?php echo $service_details['autoschedule']; ?>" />
					<input type="hidden" name="codval"  id="codval" value="<?php echo $book_details['cod'];?>"> 
					
					<input type="hidden" id="book_id" name="book_id" value="<?php echo $book_id;  ?>" />
					<input type="hidden" id="book_time" name="book_time" value="<?php echo $btime;  ?>" />
					<input type="hidden" id="bookeddate" name="bookeddate" value="<?php echo $book_date;  ?>" />
					<input type="hidden" id="sessionno" name="sessionno" value="<?php echo $book_details['autoschedule_session_no']; ?>" />
					
					<input type="hidden" id="offersid" name="offersid" value="<?php echo $offersid; ?>" />
					<input type="hidden" name="procate" id="procate" value="<?php echo $procate; ?>" />
					<input type="hidden" id="couponid" name="couponid" value="<?php echo $couponid; ?>"/>
					<input type="hidden" id="rewardid" name="rewardid" value="<?php echo $rewardid; ?>"/>
					<input type="hidden" id="rewardtype" name="rewardtype" value="<?php echo $rtype; ?>"/>
					
					<input type="hidden" id="estTime" value="<?php echo $convertedTime; ?>" />
					<input type="hidden" id="SelAreaMsp" value="<?php echo $SelAreaMsp; ?>" />
					
					
					</div>
					<?php if(count($bservices) > 0) { ?>
					<div class="tab-pane fade" id="guests" role="tabpanel" aria-labelledby="guests-tab">
						<label class="d-none"><?php echo (!empty($user_language[$user_selected]['lg_Guests_Booking_Details'])) ? $user_language[$user_selected]['lg_Guests_Booking_Details'] : $default_language['en']['lg_Guests_Booking_Details']; ?></label>
						<?php
							$g = 1;
							foreach($bservices as $b) { 
								$Sqry = $this->db->select('service_title, duration')->where('id',$b['service_id'])->get('services')->row_array();	
								$sname = $Sqry['service_title'];
								$duration = $Sqry['duration'];
								$stfnme = $this->db->select('first_name')->where('id',$b['staff_id'])->get('employee_basic_details')->row()->first_name;
								if($b['service_for'] == 1){
									$sfor = (!empty($user_language[$user_selected]['lg_Myself'])) ? $user_language[$user_selected]['lg_Myself'] : $default_language['en']['lg_Myself'];
								} else {
									$sfor =(!empty($user_language[$user_selected]['lg_Guests'])) ? $user_language[$user_selected]['lg_Guests'] : $default_language['en']['lg_Guests'];
								}
								
						?>

						<h6 class="title-for"><?php echo (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For']; ?> : <?php echo $sfor; ?></h6>
						<ul class="booking-details ml-3 mr-3 mb-3">
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></span><?php echo  $sname; ?> 								
							</li>
							<?php if($b['guest'] == 1){?>
								<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Guest_Name'])) ? $user_language[$user_selected]['lg_Guest_Name'] : $default_language['en']['lg_Guest_Name']; ?></span><?php echo  $b['guest_name']; ?> 								
							</li>
							<?php } ?>
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_Date'])) ? $user_language[$user_selected]['lg_Booking_Date'] : $default_language['en']['lg_Booking_Date']; ?></span><?php echo  date('d M Y', strtotime($b['service_date'])); ?> 								
							</li>
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_time'])) ? $user_language[$user_selected]['lg_Booking_time'] : $default_language['en']['lg_Booking_time']; ?></span><?php echo  $b['from_time'] ?> - <?php echo  $b['to_time'] ?> 								
							</li>
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?></span><?php echo  $duration." min(s)"; ?> 								
							</li>
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></span><?php echo  currency_conversion($user_currency_code) . $b['amount']; ?> 								
							</li>
							<li>
								<span><?php echo (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; ?></span><?php echo  ($stfnme)?$stfnme:'-'; ?> 								
							</li>
						</ul>
						<?php $g++; } ?>
					</div>
					<?php } ?>
					</div>
					
					 <div class="submit-section">
                        <button class="btn btn-primary submit-btn submit_service_book" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" data-id="<?php echo $service_id; ?>" data-provider="<?php echo $service_details['user_id'] ?>" data-amount="<?php echo $service_amount; ?>" type="submit" id="submit"><?php echo (!empty($user_language[$user_selected]['lg_Continue_Booking'])) ? $user_language[$user_selected]['lg_Continue_Booking'] : $default_language['en']['lg_Continue_Booking']; ?></button>
						<a class="btn btn-danger appoint-btncls" href="<?php echo base_url(); ?>user-bookings"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></a>
                    </div>
										
                </form>
				</div>
				
				
					<input type="hidden" id="booktxt" value="<?php echo $boktxt; ?>" />
					<input type="hidden" id="expiretxt" value="<?php echo $exptxt; ?>" />
					<input type="hidden" id="selecttimetxt" value="<?php echo $seltmetxt; ?>" />
					<input type="hidden" id="dateerrtxt" value="<?php echo $sesdatetxt; ?>" />
				
            </div>
        </div>
    </div>
</div>

