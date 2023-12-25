<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu'); ?>
            <div class="col-xl-9 col-md-8">
                <ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url() ?>my-services"><?php echo (!empty($user_language[$user_selected]['lg_Active_Services'])) ? $user_language[$user_selected]['lg_Active_Services'] : $default_language['en']['lg_Active_Services']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?php echo base_url() ?>my-services-inactive"><?php echo (!empty($user_language[$user_selected]['lg_Inactive_Services'])) ? $user_language[$user_selected]['lg_Inactive_Services'] : $default_language['en']['lg_Inactive_Services']; ?></a>
                    </li>
                </ul>
                <div class="row" id="dataList">


                    <?php
                    $this->session->flashdata('success_message');
                    if (!empty($services)) {
                        foreach ($services as $srows) {
                            
                            $mobile_image = explode(',', $srows['mobile_image']);
                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $srows['id']);
                            $this->db->where("status", 1);
							$this->db->order_by('is_default','DESC');
                            $image = $this->db->get()->row_array();
							if(!empty($image['service_image']) && file_exists($image['service_image'])){
								$serviceimage = base_url().$image['service_image'];
							}else{
								$serviceimage = base_url().settingValue('service_placeholder_image');
							} 
                            $this->db->select('AVG(rating)');
                            $this->db->where(array('service_id' => $srows['id'], 'status' => 1));
                            $this->db->from('rating_review');
                            $rating = $this->db->get()->row_array();
                            $avg_rating = round($rating['AVG(rating)'], 1);
                            $provider_details = $this->db->where('id', $srows['user_id'])->get('providers')->row_array();
                            $service_availability = $this->db->where('service_id', $srows['id'])->where('status!=', 6)->where('status!=', 7)->from('book_service')->count_all_results();
                            
                            $user_currency_code = '';
                            $userId = $this->session->userdata('id');
                            If (!empty($userId)) {
                                $service_amount = $srows['service_amount'];
                                $user_currency = get_provider_currency();
                                $user_currency_code = $user_currency['user_currency_code'];
                                

                                $service_amount = get_gigs_currency($srows['service_amount'], $srows['currency_code'], $user_currency_code);
                            } else {
                                $user_currency_code = settings('currency');
                                $service_amount = $srows['service_amount'];
                            }
							
							$shplocation = $this->db->select('shop_location')->where('id',$srows['shop_id'])->get('shops')->row()->shop_location;

                            $datecondt = "start_date <= curdate() and end_date >= curdate()";
                            $soffer = $this->db->select('id, end_date, start_time, end_time, offer_percentage')->where("service_id",$srows['id'])->where("provider_id",$userId)->where("df",0)->where($datecondt)->get("service_offers")->row_array();

                            $offerPrice = '';
            
                            if (!empty($soffer['offer_percentage']) && $soffer['offer_percentage'] > 0) {
                                $offerPrice = $service_amount * ($soffer['offer_percentage'] / 100 );
                                $offerPrice = $service_amount - $offerPrice;
                                $offerPrice = number_format($offerPrice,2);
                            } 
                            ?>

                            <div class="col-lg-4 col-md-6 inactive-service d-flex">
                                <div class="feature-shop mb-4 w-100">
                                    <div class="feature-img">
										<a href="javascript:void(0)">
											<img class="categorie-img" alt="Service Image" src="<?php echo $serviceimage;?>">
										</a>
                                        <div class="feature-bottom">
                                            <a href="javascript:void(0);">
                                                <?php if ($provider_details['profile_img'] == '') { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url().settingValue('profile_placeholder_image'); ?>">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
                                                <?php } ?>
                                            </a>
                                             <p>
                                                <a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_slug'])); ?>"><?php echo $srows['category_name']; ?></a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="featute-info">
                                        <h4>
                                            <a href="javascript:void(0)"><?php echo $srows['service_title']; ?></a> 
                                        </h4>
                                        <div class="star-rating">
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
                                             <span class="text-muted">(<?php echo $avg_rating ?>)</span>
                                          </div>
                                          <h6><?php if($offerPrice != '') { ?>
                                            <?php echo currency_conversion($user_currency_code) . $offerPrice; ?><span class="actualprice ms-2"><del><?php echo currency_conversion($user_currency_code) . $service_amount; ?></del></span>
                                            
                                        <?php } else { 
                                            echo currency_conversion($user_currency_code) . $service_amount; } ?>
                                        </h6>
                                             <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo ($srows['service_location'])?$srows['service_location']:$shplocation; ?></p>
                                        
                                    <div class="service-content service-content1">
                                        <div class="user-info">
                                            <div class="service-action">
                                                <div class="row">
                                                    <div class="col"><a href="javascript:void(0)" class="si-delete-inactive-service text-danger" data-id="<?php echo $srows['id']; ?>"><i class="far fa-trash-alt"></i> Delete</a></div>
                                                    <div class="col text-end"><a href="javascript:void(0)" class="si-delete-active-service text-success" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Active</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>	
                                    </div>	
										</div>									
                                </div>								
                            </div>
                        <?php
                        }
                    } else {
                        $norecord = (!empty($user_language[$user_selected]['lg_No_data_found'])) ? $user_language[$user_selected]['lg_No_data_found'] : $default_language['en']['lg_No_data_found'];
                        echo '<div class="col-xl-12 col-lg-12">
									<p class="mb-0 text-center">'.$norecord.'</p>
								</div>';
                    }
                    ?>

                    <!-- Pagination Links -->
                    <?php
                    if (!empty($services)) {
                        echo $this->ajax_pagination->create_links();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg"></p>
                 <label>Deleted Reason</label>
                 <input class="form-control" type="text" placeholder="Reason" name="delete_reason" id="del_reason" required>
                 <p class="del_reason_error error" >Reason Is Required</p>
            </div>
            <div class="form-group">
                               
                            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success si_accept_confirm" id="delete_reason">Yes</a>
                <button type="button" class="btn btn-danger si_accept_cancel" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activeConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Active Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg"></p>
                 <label>Are you sure to active this service?</label>
            </div>
            <div class="form-group">
                               
                            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success si_accept_confirm" id="delete_reason">Yes</a>
                <button type="button" class="btn btn-danger si_accept_cancel" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Delete Service</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Service is Booked and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
