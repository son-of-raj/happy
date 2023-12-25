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

if (!empty($shop['provider_id'])) {
    $provider_online = $this->db->where('id', $shop['provider_id'])->from('providers')->get()->row_array();
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

$get_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();

$category_name = $this->db->select('category_name')->where('id',$shop['category'])->get('categories')->row()->category_name;
$subcategory_name = $this->db->select('subcategory_name')->where('id',$shop['subcategory'])->get('subcategories')->row()->subcategory_name;
$sub_subcategory_name = $this->db->select('sub_subcategory_name')->where('id',$shop['sub_subcategory'])->get('sub_subcategories')->row()->sub_subcategory_name;

//Covid vaccine status
$covidControl = settingValue('corona_control');
$cvaccine = $get_details['covid_vaccine'];
?>
<div class="content">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">

                <div class="service-view">
                    <div class="service-header">
                        <h1><?php echo ucfirst($shop['shop_name']); ?></h1>
                        <address class="service-location"><i class="fas fa-location-arrow"></i> <?php echo ucfirst($shop['shop_location']); ?></address>
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
						<?php
							$cateurl = base_url()."search/".str_replace(' ', '-', $category_name);
							$subcateurl = base_url()."search/".str_replace(' ', '-', $category_name)."/".str_replace(' ', '-', $subcategory_name);
							$sub_subcateurl = base_url()."search/".str_replace(' ', '-', $category_name)."/".str_replace(' ', '-', $subcategory_name)."/".str_replace(' ', '-', $sub_subcategory_name);
						?>
						<div class="service-cate">
                             <a title="Category" href="<?php echo $cateurl; ?>" target="_blank"><?php echo ucfirst($category_name); ?></a>
							<a title="Sub Category" href="<?php echo $subcateurl; ?>" target="_blank"><?php echo ucfirst($subcategory_name); ?></a>
							<a title="Sub Sub Category" class="d-none" href="<?php echo $sub_subcateurl; ?>" target="_blank"><?php echo ucfirst($sub_subcategory_name); ?></a>
                        </div>	
                    </div>

                    <div class="service-images service-carousel">
					 <?php if(count($shop_image) > 1) { ?>
                        <div class="images-carousel owl-carousel owl-theme">
                            <?php
                            if (!empty($shop_image)) {
                                for ($i = 0; $i < count($shop_image); $i++) {
									if (!empty($shop_image[$i]['shop_image']) && (@getimagesize(base_url().$shop_image[$i]['shop_image']))) {
										echo'<div class="item"><img src="' . base_url() . $shop_image[$i]['shop_image'] . '" alt="" class="img-fluid"></div>';
									}else{
										echo'<div class="item"><img src="'.base_url().'assets/img/placeholder_shop.png" alt="" class="img-fluid"></div>';
									}
                                }
                            }
                            ?>
                        </div>
						<?php } else  { 
							$src = base_url().'assets/img/placeholder_shop.png';
							if(file_exists($shop_image[0]['shop_image'])){
								$src = base_url().$shop_image[0]['shop_image'];
							}
							echo '<div class="item">
									<img src="'.$src.'" alt="Service Image" class="img-fluid">
								 </div>';
						} ?>
                    </div>

                    <div class="service-details" id="allServices">
                        <ul class="nav nav-pills service-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Overview'])) ? $user_language[$user_selected]['lg_Overview'] : $default_language['en']['lg_Overview']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo (!empty($user_language[$user_selected]['lg_Services_Offered'])) ? $user_language[$user_selected]['lg_Services_Offered'] : $default_language['en']['lg_Services_Offered']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-book-tab" data-bs-toggle="pill" href="#pills-book" role="tab" aria-controls="pills-book" aria-selected="false"><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="card service-description">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Details'])) ? $user_language[$user_selected]['lg_Shop_Details'] : $default_language['en']['lg_Shop_Details']; ?></h5>
                                        <p class="mb-0"><?php echo $shop['description']; ?></p>
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
												<?php $uid = $shop['provider_id'];
													$serv_lists = $this->db->where('s.delete_status',0)->where('s.shop_id',$shop['id'])->where('s.provider_id',$uid)->from('shop_services_list as s')->join('shop_service_offered as f','f.id=s.service_offer_id','LEFT')->where('f.status',1)->select('s.*,s.id as sl_id,f.*')->get()->result_array();
													
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
																	class="primary service_offered_price_select" 
																	name="service_offered_price[]" 
																	value="<?php echo $value['labour_charge'];?>"
																	data-offerid="<?php echo $value['sl_id'];?>" data-staffid="<?php echo $value['staff_id'];?>">
															</label>
														</td>
														<?php } ?>
													</tr>
												<?php } } else {?>
												   <?php if ($type == 'user') { ?>
													<tr><td colspan="6" align="center">No Services Found</td></tr>
												   <?php } else {  ?>
														<tr><td colspan="5" align="center">No Services Found</td></tr>
													<?php } ?>
												<?php } ?>
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

				<!-- Related Shops -->
				
				<?php 
                if ($type == 'user') { ?>
                <h4 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Related_Shops'])) ? $user_language[$user_selected]['lg_Related_Shops'] : $default_language['en']['lg_Related_Shops']; ?></h4>
                <div class="service-carousel">
                    <div class="popular-slider owl-carousel owl-theme">
                        <?php if(count($popular_shops) > 0) {
                        foreach ($popular_shops as $key => $shopsv) {

                            $this->db->select("shop_image");
                            $this->db->from('shops_images');
                            $this->db->where("shop_id", $shopsv['id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->row_array();

							$provimg = $this->db->select('profile_img')->from('providers')->where('id', $shopsv['provider_id'])-> get()->row_array();
							
							if (!empty($provimg['profile_img'])) {
								$pimage = base_url() . $provimg['profile_img'];
							} else {
								$pimage = base_url() . 'assets/img/user.jpg';
							}
                                      
                            ?>

                            <div class="service-widget">
                                <div class="service-img">
                                    <a href="<?php echo base_url() . 'shop-preview/' . str_replace(' ', '-', strtolower($serv['service_title'])) . '?sid=' . md5($serv['id']); ?>">
									<?php
									$shopimg_url = $image['shop_image'];					
									if(!empty($shopimg_url) && 	file_exists($shopimg_url)) {
										$shop_imgurl = base_url().$shopimg_url;
									}else{
										$shop_imgurl = base_url().'assets/img/placeholder_shop.png';
									}
									?>
                                        <img class="img-fluid serv-img" alt="Shop Image" src="<?php echo $shop_imgurl; ?>">
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
                                        <a href="<?php echo base_url() . 'shop-preview/' . str_replace(' ', '-', $shopsv['shop_name']) . '?sid=' . md5($shopsv['id']); ?>"><?php echo  $shopsv['shop_name']; ?></a>
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
                                            <span class="col ser-location" title="Address"><span><?php echo (!empty($shopsv['shop_location'])) ? $shopsv['shop_location'] : $default_language['en']['lg_address']; ?></span> <i class="fas fa-map-marker-alt ml-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } } else { ?>
								<div class="text-center">No shops found related to your location.</div>
						<?php } ?>
                    </div>
                </div>
				<?php } ?>
				
				<!-- Related Shops -->
					
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
								<form method="post" enctype="multipart/form-data" autocomplete="off" id="bookserviceform" action="<?php echo base_url();?>book-appointment/<?php echo $shop['id'];?>" onsubmit="return Validate()">
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
									<input type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">
									<input type="hidden" id="service_amt" name="service_amt" value="">
									<input type="hidden" id="serviceoffer_id" name="serviceoffer_id" value="">
									<input type="hidden" name="provider_id" id="provider_id" value="<?php echo $shop['provider_id'] ?>">
									<input type="hidden" name="shop_id" id="shop_id" value="<?php echo $shop['id'] ?>">
									<input type="hidden" name="staff_id" id="staff_id" value="<?php echo $shop['id'] ?>">
									
									<?php if($covidControl == 1 && $cvaccine == 0){ ?>
									<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#corcontrolModal" id="cvaccine_modal" name="cvaccine_modal" class="btn btn-primary" ><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?></a>
									<?php } else if($covidControl == 1 && $cvaccine == 4){ ?>
									<a href="javascript:void(0);" class="btn btn-primary vaccination_status"><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?></a>
									<?php } else { ?>
									
									<input class="btn btn-primary" type="submit" id="go_book_appoint" data-id="<?php echo $shop['id'] ?>" value="<?php echo (!empty($user_language[$user_selected]['lg_Book_Appointment'])) ? $user_language[$user_selected]['lg_Book_Appointment'] : $default_language['en']['lg_Book_Appointment']; ?> " >
									<?php } ?>
								</form>

                     <?php  	}
                            }
                        } else {	?>
						
						<a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-wizard1"> <?php echo (!empty($user_language[$user_selected]['lg_Book_Appointment'])) ? $user_language[$user_selected]['lg_Book_Appointment'] : $default_language['en']['lg_Book_Appointment']; ?> </a>
						
					<?php	}						
                     ?>
						<?php if (!empty($this->session->userdata('id'))) {
								if ($shop['provider_id'] == $this->session->userdata('id')) {
									if ($this->session->userdata('usertype') != 'user') {
										?>
										<a href="<?php echo base_url() . 'freelances/edit-shop/' . $shop['id']; ?>" class="btn btn-primary" > <?php echo (!empty($user_language[$user_selected]['lg_Edit_Shop'])) ? $user_language[$user_selected]['lg_Edit_Shop'] : $default_language['en']['lg_Edit_Shop']; ?> </a>
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
                        if (!empty($shop['provider_id'])) {
                            $provider = $this->db->select('*')->
                                            from('providers')->
                                            where('id', $shop['provider_id'])->
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
                                        <i class="fas fa-circle online"></i> <?php echo (!empty($user_language[$user_selected]['lg_Online'])) ? $user_language[$user_selected]['lg_Online'] : $default_language['en']['lg_Online']; ?></p>
                                    <?php } ?>
                                    <p class="text-muted mb-1"><?php echo (!empty($user_language[$user_selected]['lg_Member_Since'])) ? $user_language[$user_selected]['lg_Member_Since'] : $default_language['en']['lg_Member_Since']; ?> <?php echo  date('M Y', strtotime($provider['created_at'])); ?></p>
                                </div>
                            </div>
                            <hr>
                            <div class="provider-info">
							<?php if ($this->session->userdata('id')) { ?>
                                <p class="mb-1"><i class="far fa-envelope"></i> <?php echo  $provider['email'] ?></p>
							<?php } else {?>
								 <p class="mb-1"><i class="far fa-envelope"></i> <?php echo $this->shop->hideEmailAddress($provider['email']); ?></p>
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
                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Shop_Availability'])) ? $user_language[$user_selected]['lg_Shop_Availability'] : $default_language['en']['lg_Shop_Availability']; ?></h5>
                        <ul>
                            <?php $availability_details = json_decode($shop['availability'], true);
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
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
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
				<button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<?php if ($this->session->userdata('usertype') == 'user') { ?>
<div class="modal fade" id="corcontrolModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo (!empty($user_language[$user_selected]['covid19_vaccine'])) ? $user_language[$user_selected]['covid19_vaccine'] : $default_language['en']['covid19_vaccine'] ?></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<form accept-charset="UTF-8" id="covid_form" method="POST">						
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label class="radio-inline"><?php echo (!empty($user_language[$user_selected]['have_you_got_covid_vaccine'])) ? $user_language[$user_selected]['have_you_got_covid_vaccine'] : $default_language['en']['have_you_got_covid_vaccine'] ?></label>
								</div>
							</div>
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="1"><?php echo (!empty($user_language[$user_selected]['yes_one_injection'])) ? $user_language[$user_selected]['yes_one_injection'] : $default_language['en']['yes_one_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="2"><?php echo (!empty($user_language[$user_selected]['yes_two_injection'])) ? $user_language[$user_selected]['yes_two_injection'] : $default_language['en']['yes_two_injection'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="3"><?php echo (!empty($user_language[$user_selected]['no_im_under_18'])) ? $user_language[$user_selected]['no_im_under_18'] : $default_language['en']['no_im_under_18'] ?></label>
								</div>
							</div>	
							<div class="col-lg-12 mr-3 ml-3">
								<div class="form-group">
									<label class="radio-inline"><input class="covid mr-1 ml-1"  type="radio" name="covid" value="4"><?php echo (!empty($user_language[$user_selected]['lg_NO'])) ? $user_language[$user_selected]['lg_NO'] : $default_language['en']['lg_NO'] ?></label>
								</div>
							</div>	
							<br><br>							
							<div class="ml-4 mr-4"><small><i>***<?php echo (!empty($user_language[$user_selected]['you_will_be_responsible'])) ? $user_language[$user_selected]['you_will_be_responsible'] : $default_language['en']['you_will_be_responsible']; ?></i></small></div>
						</div>
					</form>
				</div>				
			</div>
			<div class="modal-footer">
				<a href="javascript:" class="btn btn-success vaccine_confirm"><?php echo (!empty($user_language[$user_selected]['update_status'])) ? $user_language[$user_selected]['update_status'] : $default_language['en']['update_status']; ?></a>
				<button type="button" class="btn btn-danger vaccine_cancel" data-bs-dismiss="modal"><?php echo (!empty($user_language[$user_selected]['lg_Cancel'])) ? $user_language[$user_selected]['lg_Cancel'] : $default_language['en']['lg_Cancel']; ?></button>
			</div>
		</div>
	</div>
</div>
<?php } ?>