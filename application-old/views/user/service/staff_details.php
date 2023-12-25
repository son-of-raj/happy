<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
	$user_currency = get_user_currency();
} else if ($type == 'provider') {
	$user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];
$defaultcurrencysymbol = currency_code_sign($user_currency_code);

$staff = $staff_id; 
$provider_id = $staffdetail[0]['provider_id'];

$availability_details = json_decode($staffdetail[0]['availability'],true);
$avg_rating = 0;

if (!empty($provider_id)) {
    $provider_online = $this->db->where('id', $provider_id)->from('providers')->get()->row_array();
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

$city_qry = $this->db->select('name')->where('id', $staffdetail[0]['city'])->from('city')->get()->row_array();
$cityname = $city_qry['name'];
$stat_qry = $this->db->select('name')->where('id', $staffdetail[0]['state'])->from('state')->get()->row_array();
$statname = $stat_qry['name']; 

$shop_assign = $this->db->where('staff_id', $staff_id)->where_not_in('status',[5,6,7])->from('book_service')->count_all_results();
?>
<style>
.service-view .service-header{border: 1px solid #f0f0f0; padding: 10px;border-radius: 10px;}
</style>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo ucfirst($staffdetail[0]['first_name']." ".$staffdetail[0]['last_name']); ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
						
						<li class="breadcrumb-item"><a href="<?php echo base_url()."staff-settings"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_My_Staffs'])) ? $user_language[$user_selected]['lg_My_Staffs'] : $default_language['en']['lg_My_Staffs']; ?></a></li>
						
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container">
	<div class="row justify-content-center">
           
            <div class="col-lg-8">
				<?php 
					 if(!empty($staffdetail[0]['profile_img']) && file_exists($staffdetail[0]['profile_img'])){
						$profile_img=base_url().$staffdetail[0]['profile_img'];
					}else{
						$profile_img=base_url()."assets/img/user.jpg";
					}
				?>
				<div class="service-view">
                    <div class="mb-4">
						<div class="row">
							<div class="col-lg-8">													
								<h1><?php echo ucfirst($staffdetail[0]['first_name']." ".$staffdetail[0]['last_name']); ?></h1>
							</div>
							<div class="col-lg-4 text-end">	
								<?php if($staffdetail[0]['status'] == 1) { 
										if($shop_assign > 0){
								?>
									<div class="col text-end"><a href="javascript:void(0);" title="Click here to Deactive the Staff" class="btn btn-sm bg-danger-light me-2 " data-bs-toggle="modal" data-bs-target="#statusNotConfirmModal"><i class="fas fa-info-circle"></i> Inactive</a></div>
								<?php } else { ?>
									<a href="javascript:" class="btn btn-sm bg-danger-light me-2 staff-service" title="Click here to Deactive the Staff" data-id="<?php echo $staff_id; ?>" data-text="Inactive" data-bs-toggle="modal" data-bs-target="#statusConfirmModal"><i class="fas fa-info-circle me-1"></i>Inactive</a>
								<?php } }else { ?>
									<a href="javascript:" class="btn btn-sm bg-success-light me-2 staff-service" title="Click here to Active the Staff" data-id="<?php echo $staff_id; ?>" data-text="Activate" data-bs-toggle="modal" data-bs-target="#statusConfirmModal"><i class="fas fa-info-circle me-1"></i>Active</a>
								<?php } 
								?>							
							</div>
						</div>
						<div class="rating about-staff-author">
							<div class="about-staff-img">
								<div class="provider-img-wrap mb-3">
									<a href="javascript:void(0);"><img class="img-fluid " alt="Profile Image" src="<?php echo $profile_img; ?>"></a>
								</div>
							</div>
                    
                            <div class="staff-provider-details">
                                <div class="row mt-4">
                                    <div class="col-4"><p class="text-muted mb-2"><?php echo (!empty($user_language[$user_selected]['lg_gender'])) ? $user_language[$user_selected]['lg_gender'] : $default_language['en']['lg_gender']; ?></p></div>
                                    <div class="col-8"><p class="text-muted mb-2">:&nbsp;&nbsp;<?php echo $staffdetail[0]['gender'];?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="text-muted mb-2 mbno-rtl"><?php echo (!empty($user_language[$user_selected]['lg_Mobile_Number'])) ? $user_language[$user_selected]['lg_Mobile_Number'] : $default_language['en']['lg_Mobile_Number']; ?></p></div>
                                    <div class="col-8"><p class="text-muted mb-2">:&nbsp;&nbsp;<?php echo $staffdetail[0]['country_code'].' - '. $staffdetail[0]['contact_no'];?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-4"><p class="text-muted mb-2 mbno-rtl"><?php echo (!empty($user_language[$user_selected]['lg_Email'])) ? $user_language[$user_selected]['lg_Email'] : $default_language['en']['lg_Email']; ?></p></div>
                                    <div class="col-8"><p class="text-muted mb-2">:&nbsp;&nbsp;<?php echo $staffdetail[0]['email']; ?></p></div>
                                </div>
                                 <div class="row">
                                    <div class="col-4"><p class="text-muted mb-2"><?php echo (!empty($user_language[$user_selected]['lg_Ratings'])) ? $user_language[$user_selected]['lg_Ratings'] : $default_language['en']['lg_Ratings']; ?></p></div>
                                    <div class="col-8">
                                        <div class="rating">:&nbsp;&nbsp;
											<?php $avg_rating = 0;
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
                                </div>
                            
                            </div>
                      
						 </div>
						
					</div>
				</div>
				
				<div class="service-details">
					<ul class="nav nav-pills service-tabs" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo (!empty($user_language[$user_selected]['lg_Overview'])) ? $user_language[$user_selected]['lg_Overview'] : $default_language['en']['lg_Overview']; ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="pills-book-tab" data-bs-toggle="pill" href="#pills-book" role="tab" aria-controls="pills-book" aria-selected="false"><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></a>
						</li>
					</ul>
					 <div class="tab-content">
					 <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
						<div class="card service-description">
							<div class="card-body">
								<h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Staff_Details'])) ? $user_language[$user_selected]['lg_Staff_Details'] : $default_language['en']['lg_Staff_Details']; ?></h5>
								<h6 class="card-title"><?php echo $staffdetail[0]['designation']; ?></h6>
								<p class="mb-0"><?php echo $staffdetail[0]['about_emp']; ?></p>
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
				
			<div class="col-lg-4 theiaStickySidebar">
				<div class="sidebar-widget widget">
					<div class="service-book">
						<?php if (!empty($this->session->userdata('id'))) {
								if ($provider_id == $this->session->userdata('id')) {
									if ($this->session->userdata('usertype') != 'user') {
										?>
										<a href="<?php echo base_url() . 'edit-staff/' . $staffdetail[0]['id'] ?>" class="btn btn-appoitment" > <?php echo (!empty($user_language[$user_selected]['lg_Edit_Staff'])) ? $user_language[$user_selected]['lg_Edit_Staff'] : $default_language['en']['lg_Edit_Staff']; ?> </a>
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
                        if (!empty($provider_id)) {
                            $provider = $this->db->select('*')->
                                            from('providers')->
                                            where('id', $provider_id)->
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
                                <p class="mb-1"><i class="far fa-envelope"></i> <?php echo  $provider['email'] ?></p>
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
                        <h5 class="card-title"><?php echo (!empty($user_language[$user_selected]['lg_Staff_Availability'])) ? $user_language[$user_selected]['lg_Staff_Availability'] : $default_language['en']['lg_Staff_Availability']; ?></h5>
						
                        <ul>
                            <?php
							$norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found']; 
                            if (!empty($availability_details)) {
                                foreach ($availability_details as $availability) {

                                    $day = $availability['day'];
                                    $from_time = $availability['from_time'];
                                    $to_time = $availability['to_time'];

                                    if ($day == '1') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Monday'])) ? $user_language[$user_selected]['lg_Monday'] : $default_language['en']['lg_Monday'];
                                    } elseif ($day == '2') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Tuesday'])) ? $user_language[$user_selected]['lg_Tuesday'] : $default_language['en']['lg_Tuesday'];
                                    } elseif ($day == '3') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Wednesday'])) ? $user_language[$user_selected]['lg_Wednesday'] : $default_language['en']['lg_Wednesday'];
                                    } elseif ($day == '4') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Thursday'])) ? $user_language[$user_selected]['lg_Thursday'] : $default_language['en']['lg_Thursday'];
                                    } elseif ($day == '5') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Friday'])) ? $user_language[$user_selected]['lg_Friday'] : $default_language['en']['lg_Friday'];
                                    } elseif ($day == '6') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Saturday'])) ? $user_language[$user_selected]['lg_Saturday'] : $default_language['en']['lg_Saturday'];
                                    } elseif ($day == '7') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Sunday'])) ? $user_language[$user_selected]['lg_Sunday'] : $default_language['en']['lg_Sunday'];
                                    } elseif ($day == '0') {
                                        $weekday = (!empty($user_language[$user_selected]['lg_Sunday'])) ? $user_language[$user_selected]['lg_Sunday'] : $default_language['en']['lg_Sunday'];
                                    }

                                    echo '<li><span>' . $weekday . '</span>' . $from_time . ' - ' . $to_time . '</li>';
                                }
                            } else {
                                echo '<li class="text-center">'.$norecord.'</li>';
                            }
                            ?>
                        </ul>
                    </div>				
                </div>	
			</div>
		</div>

	</div>
</div>
<div class="modal fade" id="statusConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				
				<h5 class="modal-title" id="acc_title"></h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<p id="acc_msg"></p>
			</div>
			<div class="modal-footer">
				<a href="javascript:;" class="btn btn-success si_accept_confirm">Yes</a>
				<button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="statusNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Inactive Staff?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Staff is assigned to the service provided by the shop and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>


