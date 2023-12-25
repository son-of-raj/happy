
				<?php 

				if(!empty($all_bookings)) {
				foreach ($all_bookings as $bookings) { 
				$this->db->select("service_image");
				$this->db->from('services_image');
				$this->db->where("service_id",$bookings['service_id']);
				$this->db->where("status",1);
				$this->db->order_by('is_default','DESC');
				$image = $this->db->get()->result_array();
				$serv_image = array();
				foreach ($image as $key => $i) {
				  $serv_image[] = $i['service_image'];
				}
				if(!empty($serv_image[0]) && file_exists($serv_image[0])){
					$serviceimage = base_url().$serv_image[0];
				}else{
					$serviceimage = "https://via.placeholder.com/360x220.png?text=Service%20Image";
				} 
				$this->db->select("service_for, service_for_userid");
				$this->db->from('services');
				$this->db->where("id",$bookings['service_id']);
				$serdet = $this->db->get()->row_array();
				$usr_url = '';
				if($serdet['service_for'] == 2){
					$usr_url = '&uid='.md5($serdet['service_for_userid']);
				}
				$servicetitle = preg_replace('/[^\w\s]+/u',' ',$bookings['service_title']);
				$servicetitle = str_replace(' ', '-', $servicetitle);
				$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
				$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($bookings['service_id']);
				?>
								 
				<div class="bookings">
					<div class="booking-list">
						<div class="booking-widget">
							<a href="<?php echo $service_url.$usr_url;?>" class="booking-img">
								<img src="<?php echo $serviceimage; ?>" alt="User Image">
							</a>
							<div class="booking-det-info">

								<?php
								$badge='';
								$class='';
								if ($bookings['status']==1) {
									$badge='Pending';
									$class='bg-warning';
								}
								if ($bookings['status']==2) {
									$badge='Inprogress';
									$class='bg-primary';	
								}
								if ($bookings['status']==3) {
									$badge='Complete Request sent to User';
									$class='bg-success';
								}
								if ($bookings['status']==4) {
									$badge='Accepted';
									$class='bg-success';
								}
								if ($bookings['status']==5) {
									$badge='Rejected by User';
									$class='bg-danger';
								} 
								if ($bookings['status']==6) {
									$badge='Completed Accepted';
									$class='bg-success';
								}
								if ($bookings['status']==7) {
									$badge='Cancelled by Provider';
									$class='bg-danger';
								}
								$rewardStatus = '';  
								if($bookings['rewardid'] > 0){
									$rewardid = $bookings['rewardid'];												
									$reward = $this->db->where("id",$rewardid)->get("service_rewards")->row_array(); 
									if($reward['reward_type'] == 1) $rewardStatus = 'Reward: '.$reward['reward_discount'].'%';
									else if($reward['reward_type'] == 0) $rewardStatus = 'Free Service';
								}
								$offerStatus = '';  
								if($bookings['offersid'] > 0){
									$offersid = $bookings['offersid'];												
									$offers = $this->db->where("id",$offersid)->get("service_offers")->row_array(); 
									$offerStatus = 'Offer: '.$offers['offer_percentage'].'%';

								}
								$couponStatus = '';  
								if($bookings['couponid'] > 0){
									$couponid = $bookings['couponid'];												
									$coupon = $this->db->where("id",$couponid)->get("service_coupons")->row_array(); 
									$couponStatus = $coupon['coupon_name'];
								}
								$sessiontxt='';
								if($bookings['autoschedule_session_no'] > 0) {
									$sessiontxt = "<br><small><i> session(".$bookings['autoschedule_session_no'].")</i></small>";
								}
								$stffname = $this->db->select('CONCAT(first_name, " ", last_name) AS name')->where_in('id',$bookings['staff_id'])->where('status',1)->where('delete_status',0)->from('employee_basic_details')->get()->row()->name; 
								
								?>
								<h3 class="mb-2">
									<a href="<?php echo $service_url.$usr_url;?>">
										<?php echo $bookings['service_title'].$sessiontxt;?>
									</a>
								</h3>
								<?php if($rewardStatus != '' && $reward['reward_type'] == 0) { ?>
								<span class="badge badge-pill bg-warning-light mb-2"><?php echo $rewardStatus?></span>
								<?php } else { ?>
									<?php if($rewardStatus != '') { ?>
									<span class="badge badge-pill bg-warning-light mb-2"><?php echo $rewardStatus?></span>
									<?php } ?>
									<?php if($offerStatus != '') { ?>
									<span class="badge badge-pill bg-warning-light mb-2"><?php echo $offerStatus?></span>
									<?php } ?>
									<?php if($couponStatus != '') { ?>
									<span class="badge badge-pill bg-warning-light mb-2"><?php echo $couponStatus?></span>
									<?php } ?>
								<?php } ?>
								<?php
								if(!empty($bookings['user_id'])){
								$user_info=$this->db->select('*')->
								from('users')->
								where('id',(int)$bookings['user_id'])->
								get()->row_array();
								}
								   
								if(!empty($user_info['profile_img']) && file_exists($user_info['profile_img'])){
									$image=base_url().$user_info['profile_img'];
								}else{
									$image=base_url().'assets/img/user.jpg';
								}
								$user_currency_code = '';
								$userId = $this->session->userdata('id');
								If (!empty($userId)) {
									$service_amount = $bookings['amount'];
									$type = $this->session->userdata('usertype');
									if ($type == 'user') {
										$user_currency = get_user_currency();
									} else if ($type == 'provider') {
										$user_currency = get_provider_currency();
									} else if ($type == 'freelancer') {
										$user_currency = get_provider_currency();
									}
									$user_currency_code = $user_currency['user_currency_code'];

									$service_amount = get_gigs_currency($bookings['amount'], $bookings['currency_code'], $user_currency_code);
								} else {
									$user_currency_code = settings('currency');
									$service_amount = $bookings['amount'];
								}
								 $current_time = date('H:i:s');
                            $where_time = $current_time.' BETWEEN start_time AND end_time';
                             $offers1 = $this->db->where("status",0)->where("df",0)->where("service_id",$bookings['service_id'])->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where( "'$current_time' BETWEEN start_time AND end_time",NULL, FALSE)->get("service_offers")->row_array();
                            $offerPrice = '';

                            if (!empty($offers1['offer_percentage']) && $offers1['offer_percentage'] > 0) {
                                $offerPrice = $service_amount * ($offers1['offer_percentage'] / 100 );
                                $offerPrice = $service_amount - $offerPrice;
                                $offerPrice = number_format($offerPrice,2);
                            }
								$qrimg = '';
								if (!empty($bookings['qr_img_url']) && file_exists($bookings['qr_img_url'])) {
									$qrimg = base_url() . $bookings['qr_img_url'];
								} 
								?>
								<ul class="booking-details">
									<li>
										<span><?php echo (!empty($user_language[$user_selected]['lg_Booking_Date'])) ? $user_language[$user_selected]['lg_Booking_Date'] : $default_language['en']['lg_Booking_Date']; ?></span> <?php echo  date('d M Y', strtotime($bookings['service_date'])); ?> 
										<span class='badge badge-pill badge-prof <?php echo $class; ?>'><?php echo  $badge; ?></span>
									</li>
									<li><span><?php echo (!empty($user_language[$user_selected]['lg_Booking_time'])) ? $user_language[$user_selected]['lg_Booking_time'] : $default_language['en']['lg_Booking_time']; ?></span> <?php echo  $bookings['from_time'] ?> - <?php echo  $bookings['to_time'] ?></li>
									<li><span><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></span>  <?php if($offerPrice != '' && $bookings['offersid'] > 0) {   
                                                        echo currency_conversion($user_currency_code) . $offerPrice; ?><del><?php echo currency_conversion($user_currency_code) . $service_amount; ?></del>
                                                    <?php } else {
                                                            echo currency_conversion($user_currency_code) . $service_amount;
                                                        }

                                                     /*if($couponPrice != '') { 
                                                        echo currency_conversion($user_currency_code) . $couponPrice; ?><del><?php echo currency_conversion($user_currency_code) . $service_amount1; ?></del>
                                                     <?php } else { 
                                                        echo currency_conversion($user_currency_code) . $service_amount1;
                                                     } */?></li>
									<li><span><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></span> <?php echo $bookings['location'] ?></li>
									<li><span><?php echo (!empty($user_language[$user_selected]['lg_Phone'])) ? $user_language[$user_selected]['lg_Phone'] : $default_language['en']['lg_Phone']; ?></span> <?php echo $user_info['mobileno'] ?></li>
									<li><span><?php echo (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff']; ?></span> <?php echo ($stffname)?$stffname:'-'; ?></li>
									<li>
										<span><?php echo (!empty($user_language[$user_selected]['lg_User'])) ? $user_language[$user_selected]['lg_User'] : $default_language['en']['lg_User']; ?></span>
										<div class="avatar avatar-xs mr-1">
											<img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $image; ?>">
										</div> <?php echo  !empty($user_info['name']) ? $user_info['name'] : '-'; ?>
									</li>
								</ul>
							</div>
						</div>
						<?php
						$allowcancel='';
						?>
						<div class="booking-action">
							<?php if($qrimg != '') { ?>
								<div class="col-lg-12">
									<img src="<?php echo $qrimg; ?>" alt="QR CODE"> 
								</div>
							<?php } ?>
							<?php if ($bookings['status']==2) {$pending=0;?>
								<a href="<?php echo base_url()?>user-chat/booking-new-chat?book_id=<?php echo $bookings['id']?>" class="btn btn-sm bg-info-light">
									<i class="far fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?>
								</a> 
								<a href="javascript:;" class="btn btn-sm bg-danger-light myCancel <?php echo $allowcancel;?>" data-bs-toggle="modal" data-bs-target="#myCancel" data-id="<?php echo $bookings['id']?>" data-providerid="<?php echo $bookings['provider_id']?>" data-userid="<?php echo $bookings['user_id']?>" data-serviceid="<?php echo $bookings['service_id']?>"> 	
											<i class="fas fa-times"></i> <?php echo (!empty($user_language[$user_selected]['lg_cancel_service'])) ? $user_language[$user_selected]['lg_cancel_service'] : $default_language['en']['lg_cancel_service']; ?>
										</a>	
								
								<a href="javascript:;" class="btn btn-sm bg-success-light update_pro_booking_status"  data-id="<?php echo $bookings['id'];?>" data-status="3" data-rowid="<?php echo $pending;?>" data-review="2">
									<i class="fas fa-check"></i> <?php echo (!empty($user_language[$user_selected]['lg_complete_res_user'])) ? $user_language[$user_selected]['lg_complete_res_user'] : $default_language['en']['lg_complete_res_user']; ?>
								</a>
								<?php }elseif($bookings['status']==1){ 
									$pending=$bookings['status'];?>
									<a href="javascript:;" class="btn btn-sm bg-success-light update_pro_booking_status"  data-id="<?php echo $bookings['id'];?>" data-status="2" data-rowid="<?php echo $pending;?>" data-review="2" >
									<i class="fas fa-check"></i> <?php echo (!empty($user_language[$user_selected]['lg_user_res_accept'])) ? $user_language[$user_selected]['lg_user_res_accept'] : $default_language['en']['lg_user_res_accept']; ?>
								</a>
							<a href="javascript:;" class="btn btn-sm bg-danger-light myCancel <?php echo $allowcancel;?>" data-bs-toggle="modal" data-bs-target="#myCancel" data-id="<?php echo $bookings['id']?>" data-providerid="<?php echo $bookings['provider_id']?>" data-userid="<?php echo $bookings['user_id']?>" data-serviceid="<?php echo $bookings['service_id']?>"> 	
											<i class="fas fa-times"></i> <?php echo (!empty($user_language[$user_selected]['lg_cancel_service'])) ? $user_language[$user_selected]['lg_cancel_service'] : $default_language['en']['lg_cancel_service']; ?>
										</a>
							<?php }elseif($bookings['status']==3){
									$pending=0;?>
							<?php }?>	

									<?php if ($bookings['status']==7 || $bookings['status']==5  ) {?>
									<button type="button" data-id="<?php echo $bookings['id']?>"  class="btn btn-sm bg-default-light reason_modal">
											<i class="fas fa-info-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_reason'])) ? $user_language[$user_selected]['lg_reason'] : $default_language['en']['lg_reason']; ?>
										</button>
										<input type="hidden" id="reason_<?php echo $bookings['id'];?>" value="<?php echo $bookings['reason'];?>">
									<?php }?>
							</div>
					
					</div>
				</div>
				<?php } } else { ?>
				<p><?php echo (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou']; ?></p>
				<?php } ?>
				<?php 
				
						echo $this->ajax_pagination->create_links();
					?>
					<script src="<?php echo base_url();?>assets/js/functions.js"></script>