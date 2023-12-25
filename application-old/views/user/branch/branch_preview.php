<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);
$avg_rating = 0;

if (!empty($branch['provider_id'])) {
    $provider_online = $this->db->where('id', $branch['provider_id'])->from('providers')->get()->row_array();
    $datetime1 = new DateTime();
    $datetime2 = new DateTime($provider_online['last_logout']);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->format('%a');
    $hours = $interval->format('%h');
    $minutes = $interval->format('%i');
    $seconds = $interval->format('%s');
} else {
    $days = $hours = $minutes = $seconds = 0;
}

$sname = $this->db->select('shop_name')->where('id', $branch['shop_id'])->get('shops')->row_array();
?>
<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <div class="service-view">
                    <div class="service-header">
                        <h1 title="Branch Title"><?php echo ucfirst($branch['branch_name']); ?></h1>
						<h5 title="Shop Title"><span class="ser-location text-left"><i class="fas fa-shopping-bag"></i>&nbsp;<span class="text-info small">
							<a href="<?php echo base_url() . 'shop-preview/' . str_replace(' ', '-', strtolower($sname['shop_name'])) . '?sid=' . md5($srows['shop_id']); ?>"><?php echo ucfirst($sname['shop_name']);?></a>
						</span></span></h5>
                        <address class="service-location"><i class="fas fa-location-arrow"></i> <?php echo ucfirst($branch['branch_location']); ?></address>
                        <div class="rating">
                            <?php
                            for ($x = 1; $x <= $avg_rating; $x++) {
                                echo '<i class="fas fa-star filled"></i>';
                            }
                            if (strpos($avg_rating, '.')) {
                                echo '<i class="fas fa-star"></i>';
                                $x++;
                            }
                            while ($x <= 5) {
                                echo '<i class="fas fa-star"></i>';
                                $x++;
                            }
                            ?>	
                            <span class="d-inline-block average-rating">(<?php echo $avg_rating; ?>)</span>
                        </div>                       
                    </div>

                    <div class="service-images service-carousel">
					 <?php if(count($branch_image) > 1) { ?>
                        <div class="images-carousel owl-carousel owl-theme">
                            <?php
                            if (!empty($branch_image)) {
                                for ($i = 0; $i < count($branch_image); $i++) {
									if (!empty($branch_image[$i]['branch_image']) && (@getimagesize(base_url().$branch_image[$i]['branch_image']))) {
										echo'<div class="item"><img src="' . base_url() . $branch_image[$i]['branch_image'] . '" alt="" class="img-fluid"></div>';
									}else{
										echo'<div class="item"><img src="'.base_url().'assets/img/placeholder_shop.png" alt="" class="img-fluid"></div>';
									}
                                }
                            }
                            ?>
                        </div>
						<?php } else  { 
							$src = base_url().'assets/img/placeholder_shop.png';
							if(file_exists($branch_image[0]['branch_image'])){
								$src = base_url().$branch_image[0]['branch_image'];
							}
							echo '<div class="item">
									<img src="'.$src.'" alt="Service Image">
								 </div>';
						} ?>
                    </div>

                    <div class="service-details">
                        <ul class="nav nav-pills service-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Overview'])) ? $user_language[$user_selected]['lg_Overview'] : $default_language['en']['lg_Overview']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo (!empty($user_language[$user_selected]['lg_Services_Offered'])) ? $user_language[$user_selected]['lg_Services_Offered'] : $default_language['en']['lg_Services_Offered']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-book-tab" data-toggle="pill" href="#pills-book" role="tab" aria-controls="pills-book" aria-selected="false"><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="card service-description">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Branch_Details'])) ? $user_language[$user_selected]['lg_Branch_Details'] : $default_language['en']['lg_Branch_Details']; ?></h5>
                                        <p class="mb-0"><?php echo $branch['description']; ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Services_Offered'])) ? $user_language[$user_selected]['lg_Services_Offered'] : $default_language['en']['lg_Services_Offered']; ?></h5>
                                       
										<div class="service-offer">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Service Offered</th>
														<th>Service Name</th>
														<th class="text-center">Staff</th>
														<th class="text-center">Duration</th>
														<th class="text-center">Charge</th>
														<?php if ($type == 'user') { ?>
														<th class="selectcls">Select</th>
														<?php } ?>
													</tr>
												</thead>
												<tbody>
												<?php $uid = $branch['provider_id'];
													$serv_lists = $this->db->where('s.delete_status',0)->where('s.branch_id',$branch['id'])->where('s.provider_id',$uid)->from('branch_services_list as s')->join('shop_service_offered as f','f.id=s.service_offer_id','LEFT')->where('f.status',1)->select('s.*,s.id as sl_id,f.*')->get()->result_array();
													
													if (count($serv_lists) > 0) {
														foreach ($serv_lists as $key => $value) {
															$stf = $this->db->select('first_name,designation')->from('employee_basic_details')->where('provider_id', $uid)->where('id IN ('.$value['staff_id'].')',NULL, false)->get()->result_array();
															
												?>
										
													<tr>
														<td><p><?php echo $value['service_offered']; ?></p></td>
														<td class="data_service" data-serviceoffer="<?php echo $value['service_offer_name'];?>">
															<?php echo($value['service_offer_name']);?><br/>
															<i class="text-info small"><?php echo $value['remarks'];?></i>
														</td>
														<td> <?php if(count($stf) >1){ 
																	foreach($stf as $k => $stfval){
																		echo $stfval['first_name']." <span class='small'>(".$stfval['designation'].")</span>";
																		if((count($stf)-1) != $k) echo ", ";
																	}
																} else {
																	echo $stf[0]['first_name']." <span class='small'>(".$stf[0]['designation'].")</span>";  
															}?>
														</td>
														<td class="text-center data_duration">
															<?php echo $value['duration']."<span class='small'>".$value['duration_in']."</span>"; ?>
														</td>
														<td align="center">
														<?php echo $value['labour_charge']; ?>	
														</td>
														<?php if ($type == 'user') { ?>
														<td align="center">
															<label class="switch">
																	<input 
																	type="radio" 
																	class="primary service_offered_price_1" 
																	name="service_offered_price[]" 
																	value="<?php echo $value['labour_charge'];?>"
																	data-offerid="<?php echo $value['sl_id'];?>" data-staffid="<?php echo $value['staff_id'];?>">
															</label>
														</td>
														<?php } ?>
													</tr>
												<?php } } ?>
												</tbody>
											</table>	
										</div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="pills-book" role="tabpanel" aria-labelledby="pills-book-tab">
                                <div class="card review-box">
                                    <div class="card-body">
										<span><?php echo (!empty($user_language[$user_selected]['lg_No_reviews'])) ? $user_language[$user_selected]['lg_No_reviews'] : $default_language['en']['lg_No_reviews']; ?></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>     

				<!-- Related Branchs -->
				
				<?php 
                if ($type == 'user') { ?>
                <h4 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Related_Branches'])) ? $user_language[$user_selected]['lg_Related_Branches'] : $default_language['en']['lg_Related_Branches']; ?></h4>
                <div class="service-carousel">
                    <div class="popular-slider owl-carousel owl-theme">
                        <?php
                        foreach ($popular_branchs as $key => $branchsv) {

                            $this->db->select("branch_image");
                            $this->db->from('branch_images');
                            $this->db->where("branch_id", $branchsv['id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->row_array();

							$provimg = $this->db->select('profile_img')->from('providers')->where('id', $branchsv['provider_id'])-> get()->row_array();
							
							if (!empty($provimg['profile_img'])) {
								$pimage = base_url() . $provimg['profile_img'];
							} else {
								$pimage = base_url() . 'assets/img/user.jpg';
							}
                                      
                            ?>

                            <div class="service-widget">
                                <div class="service-img">
                                    <a href="<?php echo base_url() . 'branch-preview/' . str_replace(' ', '-', strtolower($serv['service_title'])) . '?sid=' . md5($serv['id']); ?>">
									<?php
									$branchimg_url = $image['branch_image'];					
									if(!empty($branchimg_url) && 	file_exists($branchimg_url)) {
										$branch_imgurl = base_url().$branchimg_url;
									}else{
										$branch_imgurl = base_url().'assets/img/placeholder_shop.png';
									}
									?>
                                        <img class="img-fluid serv-img" alt="Branch Image" src="<?php echo $branch_imgurl; ?>">
                                    </a>
                                    <div class="item-info">
                                        <div class="service-user">
                                            <a href="#">
                                                <img src="<?php echo $pimage; ?>" alt="">
                                            </a>                                            
                                        </div>                                       
                                    </div>
                                </div>
                                <div class="service-content">
                                    <h3 class="title">
                                        <a href="<?php echo base_url() . 'branch-preview/' . str_replace(' ', '-', $branchsv['branch_name']) . '?sid=' . md5($branchsv['id']); ?>"><?php echo  $branchsv['branch_name']; ?></a>
                                    </h3>
                                    <div class="rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <span class="d-inline-block average-rating">(0)</span>
                                    </div>
                                    <div class="user-info">
                                        <div class="row">
                                            <span class="col ser-contact"><i class="fas fa-phone mr-1"></i> <span>xxxxxxxx<?php echo  rand(00, 99) ?></span></span>
                                            <span class="col ser-location" title="Address"><span><?php echo (!empty($branchsv['branch_location'])) ? $branchsv['branch_location'] : $default_language['en']['lg_address']; ?></span> <i class="fas fa-map-marker-alt ml-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
				<?php } ?>
				
				<!-- Related Branchs -->
					
            </div>
            
            <div class="col-lg-4 theiaStickySidebar">
                <div class="sidebar-widget widget">
					<div class="service-book">
					<?php                      
                        $userId = $this->session->userdata('id');
                        $usertype = $this->session->userdata('usertype');
                        $token = $this->session->userdata('chat_token');

                        if (!empty($userId)) {
                            if (!empty($usertype) && $usertype == 'user') {
                                $where = ['token' => $token];
                                $wallet_info = $this->db->select('*')->from('wallet_table')->where($where)->get()->row();
                                if (isset($wallet_info->wallet_amt)) {
					?>
								<form method="post" enctype="multipart/form-data" autocomplete="off" id="bookserviceform">
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
									<input type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
									<input type="hidden" id="service_amt" name="service_amt" value="">
									<input type="hidden" id="serviceoffer_id" name="serviceoffer_id" value="">
									<input type="hidden" name="provider_id" id="provider_id" value="<?php echo $branch['provider_id'] ?>">
									<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $branch['shop_id'] ?>">
									<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch['id'] ?>">
									<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $branch['id'] ?>">
								</form>

                     <?php  	}
                            }
                        } else {	}						
                     ?>
						<?php if (!empty($this->session->userdata('id'))) {
								if ($branch['provider_id'] == $this->session->userdata('id')) {
									if ($this->session->userdata('usertype') != 'user') {
										?>
										<a href="<?php echo base_url() . 'edit-branch/' . $branch['id']; ?>" class="btn btn-primary" > <?php echo (!empty($user_language[$user_selected]['lg_Edit_Branch'])) ? $user_language[$user_selected]['lg_Edit_Branch'] : $default_language['en']['lg_Edit_Branch']; ?> </a>
										<?php
									}
								}
							}
							?>
					</div>
				</div>

                <div class="card provider-widget clearfix">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Service_Provider'])) ? $user_language[$user_selected]['lg_Service_Provider'] : $default_language['en']['lg_Service_Provider']; ?></h5>
                        <?php
                        if (!empty($branch['provider_id'])) {
                            $provider = $this->db->select('*')->
                                            from('providers')->
                                            where('id', $branch['provider_id'])->
                                            get()->row_array();
                            ?>

                            <div class="about-author">
                                <div class="about-provider-img">
                                    <div class="provider-img-wrap">
                                        <?php
                                        if (!empty($provider['profile_img'])) {
                                            $image = base_url() . $provider['profile_img'];
                                        } else {
                                            $image = base_url() . 'assets/img/user.jpg';
                                        }
                                        ?>
                                        <a href="javascript:void(0);"><img class="img-fluid rounded-circle" alt="" src="<?php echo $image; ?>"></a>
                                    </div>
                                </div>

                                <div class="provider-details">
                                    <a href="javascript:void(0);" class="ser-provider-name"><?php echo  !empty($provider['name']) ? $provider['name'] : '-'; ?></a>
                                    <p class="last-seen"> 
                                        <?php if ($provider_online['is_online'] == 2) { ?>
                                            <i class="fas fa-circle"></i> Last seen: &nbsp;
                                            <?php echo  (!empty($days)) ? $days . ' days' : ''; ?> 
                                            <?php if ($days == 0) { ?>
                                                <?php echo  (!empty($hours)) ? $hours . ' hours' : ''; ?>
                                            <?php } ?>
                                            <?php if ($days == 0 && $hours == 0) { ?>
                                                <?php echo  (!empty($minutes)) ? $minutes . ' min' : ''; ?>
                                            <?php } ?>
                                            ago
                                        </p>
                                    <?php } elseif ($provider_online['is_online'] == 1) { ?>
                                        <i class="fas fa-circle online"></i> Online</p>
                                    <?php } ?>
                                    <p class="text-muted mb-1"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo  date('M Y', strtotime($provider['created_at'])); ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="provider-info">
							<?php if ($this->session->userdata('id')) { ?>
                                <p class="mb-1"><i class="far fa-envelope"></i> <?php echo  $provider['email'] ?></p>
							<?php } else {?>
								 <p class="mb-1"><i class="far fa-envelope"></i> <?php echo $this->branch->hideEmailAddress($provider['email']); ?></p>
							<?php } ?>
                                <p class="mb-0"><i class="fas fa-phone-alt"></i>
                                    <?php
                                    if ($this->session->userdata('id')) {
                                        echo $provider['country_code'].' - '.$provider['mobileno'];
                                    } else {
                                        ?>
                                        xxxxxxxx<?php echo  rand(00, 99); ?>
                                    <?php } ?>

                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="card available-widget">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Branch_Availability'])) ? $user_language[$user_selected]['lg_Branch_Availability'] : $default_language['en']['lg_Branch_Availability']; ?></h5>
                        <ul>
                            <?php $availability_details = json_decode($branch['availability'], true);
                            if (!empty($availability_details)) {
                                foreach ($availability_details as $availability) {

                                    $day = $availability['day'];
                                    $from_time = $availability['from_time'];
                                    $to_time = $availability['to_time'];

                                    if ($day == '1') {
                                        $weekday = 'Monday';
                                    } elseif ($day == '2') {
                                        $weekday = 'Tuesday';
                                    } elseif ($day == '3') {
                                        $weekday = 'Wednesday';
                                    } elseif ($day == '4') {
                                        $weekday = 'Thursday';
                                    } elseif ($day == '5') {
                                        $weekday = 'Friday';
                                    } elseif ($day == '6') {
                                        $weekday = 'Saturday';
                                    } elseif ($day == '7') {
                                        $weekday = 'Sunday';
                                    } elseif ($day == '0') {
                                        $weekday = 'Sunday';
                                    }

                                    echo '<li><span>' . $weekday . '</span>' . $from_time . ' - ' . $to_time . '</li>';
                                }
                            } else {
                                echo '<li class="text-center">No Details found</li>';
                            }
                            ?>
                        </ul>
                    </div>				
                </div>				
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="staffSelectConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title">
					<?php echo '<span class="service-pricesymbol">'.$defaultcurrencysymbol.'</span>'?>
					<span  id="acc_title" class="service-pricesymbol"></span>
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-12"><p id="acc_msg"></p></div>
					<div class="col-lg-12"><p id="acc_extramsg"></p></div>
					<div class="col-lg-6 slotdetails">
					   <div class="form-group">
							<label>Date <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="book_date" id="book_date" />
						</div>
					</div>

					<div class="col-lg-6 slotdetails">
						<div class="form-group">
							<label>Time slot <span class="text-danger">*</span></label>
							<select class="form-control from_time" name="from_time" id="from_time" required>
							</select>

						</div>
					</div>
				</div>				
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_accept_confirm">Confirm Booking</a>
				<button type="button" class="btn btn-danger si_accept_cancel" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
