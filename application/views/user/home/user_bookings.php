<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Bookings'])) ? $user_language[$user_selected]['lg_My_Bookings'] : $default_language['en']['lg_My_Bookings']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_My_Bookings'])) ? $user_language[$user_selected]['lg_My_Bookings'] : $default_language['en']['lg_My_Bookings']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container">
        <div class="row">

            <?php $this->load->view('user/home/user_sidemenu'); ?>

            <div class="col-xl-9 col-md-8">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h4 class="widget-title mb-0"><?php echo (!empty($user_language[$user_selected]['lg_My_Bookings'])) ? $user_language[$user_selected]['lg_My_Bookings'] : $default_language['en']['lg_My_Bookings']; ?></h4>
                    </div>
                    <div class="col-auto">
                        <div class="sort-by">
                            <select class="form-control-sm custom-select searchFilter" id="status">
                                <option value=''><?php echo (!empty($user_language[$user_selected]['lg_All'])) ? $user_language[$user_selected]['lg_All'] : $default_language['en']['lg_All']; ?></option>
                                <option value="1"><?php echo (!empty($user_language[$user_selected]['lg_Pending'])) ? $user_language[$user_selected]['lg_Pending'] : $default_language['en']['lg_Pending']; ?></option>
                                <option value="2"><?php echo (!empty($user_language[$user_selected]['lg_Inprogress'])) ? $user_language[$user_selected]['lg_Inprogress'] : $default_language['en']['lg_Inprogress']; ?></option>
                                <option value="3"><?php echo (!empty($user_language[$user_selected]['lg_Complete_Request'])) ? $user_language[$user_selected]['lg_Complete_Request'] : $default_language['en']['lg_Complete_Request']; ?></option>
                                <option value="5"><?php echo (!empty($user_language[$user_selected]['lg_Rejected'])) ? $user_language[$user_selected]['lg_Rejected'] : $default_language['en']['lg_Rejected']; ?> </option>
                                <option value="7"><?php echo (!empty($user_language[$user_selected]['lg_Cancelled'])) ? $user_language[$user_selected]['lg_Cancelled'] : $default_language['en']['lg_Cancelled']; ?></option>
                                <option value="6"><?php echo (!empty($user_language[$user_selected]['lg_Completed'])) ? $user_language[$user_selected]['lg_Completed'] : $default_language['en']['lg_Completed']; ?></option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="dataList">
                    <?php
                    if (!empty($all_bookings)) {
                        foreach ($all_bookings as $bookings) {
                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $bookings['service_id']);
                            $this->db->where("status", 1);
                            $this->db->order_by('is_default', 'DESC');
                            $image = $this->db->get()->result_array();
                            $serv_image = array();
                            foreach ($image as $key => $i) {
                                $serv_image[] = $i['service_image'];
                            }
                            if (!empty($serv_image[0]) && file_exists($serv_image[0])) {
                                $serviceimage = base_url() . $serv_image[0];
                            } else {
                                $serviceimage = "https://via.placeholder.com/360x220.png?text=Service%20Image";
                            }
                            $rating = $this->db->where('user_id', $this->session->userdata('id'))->where('booking_id', $bookings['id'])->get('rating_review')->row_array();

                            $this->db->select("service_for, service_for_userid");
                            $this->db->from('services');
                            $this->db->where("id", $bookings['service_id']);
                            $serdet = $this->db->get()->row_array();
                            $usr_url = '';
                            if ($serdet['service_for'] == 2) {
                                $usr_url = '&uid=' . md5($serdet['service_for_userid']);
                            }
                            $servicetitle = preg_replace('/[^\w\s]+/u', ' ', $bookings['service_title']);
                            $servicetitle = str_replace(' ', '-', $servicetitle);
                            $servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
                            $service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($bookings['service_id']);

                            $user_currency_code = '';
                            $userId = $this->session->userdata('id');
                            $user_details = $this->db->where('id', $userId)->get('users')->row_array();
                            $service['service_amount'] = $bookings['service_amount'];
                            if (!empty($userId)) {
                                $service_amount = $bookings['service_amount'];
                                $type = $this->session->userdata('usertype');
                                if ($type == 'user') {
                                    $user_currency = get_user_currency();
                                } else if ($type == 'provider') {
                                    $user_currency = get_provider_currency();
                                } else if ($type == 'freelancer') {
                                    $user_currency = get_provider_currency();
                                }
                                $user_currency_code = $user_currency['user_currency_code'];

                                $service_amount = get_gigs_currency($bookings['service_amount'], $bookings['currency_code'], $user_currency_code);
                            } else {
                                $user_currency_code = settings('currency');
                                $service_currency_code = $service['currency_code'];
                                $service_amount = get_gigs_currency($bookings['service_amount'], $bookings['currency_code'], $user_currency_code);
                            }
                            $current_time = date('H:i:s');
                            $where_time = $current_time . ' BETWEEN start_time AND end_time';
                            $offers1 = $this->db->where("status", 0)->where("df", 0)->where("service_id", $bookings['service_id'])->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where("'$current_time' BETWEEN start_time AND end_time", NULL, FALSE)->get("service_offers")->row_array();
                            $offerPrice = '';

                            if (!empty($offers1['offer_percentage']) && $offers1['offer_percentage'] > 0) {
                                $offerPrice = $service_amount * ($offers1['offer_percentage'] / 100);
                                $offerPrice = $service_amount - $offerPrice;
                                $offerPrice = number_format($offerPrice, 2);
                            } ?>

                            <div class="bookings">
                                <div class="booking-list">
                                    <div class="booking-widget">
                                        <a href="<?php echo $service_url . $usr_url; ?>" class="booking-img">
                                            <img src="<?php echo $serviceimage; ?>" alt="Service Image">
                                        </a>
                                        <div class="booking-det-info">

                                            <?php
                                            $badge = '';
                                            $class = '';
                                            if ($bookings['status'] == 1) {
                                                $badge = 'Pending';
                                                $class = 'bg-warning';
                                            }
                                            if ($bookings['status'] == 2) {
                                                $badge = 'Inprogress';
                                                $class = 'bg-primary';
                                            }
                                            if ($bookings['status'] == 3) {
                                                $badge = 'Complete Request sent by Provider';
                                                $class = 'bg-success';
                                            }
                                            if ($bookings['status'] == 4) {
                                                $badge = 'Accepted';
                                                $class = 'bg-success';
                                            }
                                            if ($bookings['status'] == 5) {
                                                $badge = 'Rejected by User';
                                                $class = 'bg-danger';
                                            }
                                            if ($bookings['status'] == 6) {
                                                $badge = 'Completed Accepted';
                                                $class = 'bg-success';
                                            }
                                            if ($bookings['status'] == 7) {
                                                $badge = 'Cancelled by Provider/Manager';
                                                $class = 'bg-danger';
                                            }
                                            $rewardStatus = '';
                                            if ($bookings['rewardid'] > 0) {
                                                $rewardid = $bookings['rewardid'];
                                                $reward = $this->db->where("id", $rewardid)->get("service_rewards")->row_array();
                                                if ($reward['reward_type'] == 1) $rewardStatus = 'Reward: ' . $reward['reward_discount'] . '%';
                                                else if ($reward['reward_type'] == 0) $rewardStatus = 'Free Service';
                                            }
                                            $offerStatus = '';
                                            if ($bookings['offersid'] > 0) {
                                                $offersid = $bookings['offersid'];
                                                $offers = $this->db->where("id", $offersid)->get("service_offers")->row_array();
                                                $offerStatus = 'Offer: ' . $offers1['offer_percentage'] . '%';
                                            }
                                            $couponStatus = '';
                                            if ($bookings['couponid'] > 0) {
                                                $couponid = $bookings['couponid'];
                                                $coupon = $this->db->where("id", $couponid)->get("service_coupons")->row_array();
                                                if ($coupon['coupon_percentage'] != 0) {
                                                    $couponStatus = $coupon['coupon_name'] . ':' . $coupon['coupon_percentage'] . '%';
                                                    $couponPrice = $service_amount * ($coupon['coupon_percentage'] / 100);
                                                    $couponPrice = $service_amount - $couponPrice;
                                                    $couponPrice = number_format($couponPrice, 2);
                                                } else if ($coupon['coupon_amount'] != 0) {
                                                    $couponStatus = $coupon['coupon_name'] . ': ' . currency_conversion($user_currency_code) . $coupon['coupon_amount'];
                                                    $couponPrice = $service_amount - $coupon['coupon_amount'];
                                                    $couponPrice = number_format($couponPrice, 2);
                                                } else {
                                                    $couponStatus = $coupon['coupon_name'];
                                                }
                                            }
                                            $sessiontxt = '';
                                            if ($bookings['autoschedule_session_no'] > 0) {
                                                $sessiontxt = "<br><small><i> session(" . $bookings['autoschedule_session_no'] . ")</i></small>";
                                            }
                                            ?>
                                            <h3 class="mb-2">
                                                <a href="<?php echo $service_url . $usr_url; ?>">
                                                    <?php echo wordwrap($bookings['service_title'], 70, '<br />', true) . $sessiontxt; ?>
                                                </a>
                                                <span class="badge badge-pill badge-prof <?php echo $class; ?>"><?php echo  $badge; ?></span>
                                            </h3>
                                            <?php if ($rewardStatus != '' && $reward['reward_type'] == 0) { ?>
                                                <span class="badge badge-pill bg-warning-light mb-2"><?php echo $rewardStatus ?></span>
                                            <?php } else { ?>
                                                <?php if ($rewardStatus != '') { ?>
                                                    <span class="badge badge-pill bg-warning-light mb-2"><?php echo $rewardStatus ?></span>
                                                <?php } ?>
                                                <?php if ($offerStatus != '') { ?>
                                                    <span class="badge badge-pill bg-warning-light mb-2 ddddd"><?php echo $offerStatus ?></span>
                                                <?php } ?>
                                                <?php if ($couponStatus != '') { ?>
                                                    <span class="badge badge-pill bg-warning-light mb-2"><?php echo $couponStatus ?></span>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php
                                            if (!empty($bookings['user_id'])) {
                                                $provider_info = $this->db->select('*')->from('providers')->where('id', (int) $bookings['provider_id'])->get()->row_array();
                                            }
                                            if (!empty($provider_info['profile_img']) && file_exists($provider_info['profile_img'])) {
                                                $image = base_url() . $provider_info['profile_img'];
                                            } else {
                                                $image = base_url() . 'assets/img/user.jpg';
                                            }



                                            $user_currency_code = '';
                                            $userId = $this->session->userdata('id');
                                            if (!empty($userId)) {
                                                $service_amount1 = $bookings['total_amount'];

                                                $user_currency = get_user_currency();
                                                $user_currency_code = $user_currency['user_currency_code'];


                                                $service_amount1 = get_gigs_currency($bookings['total_amount'], $bookings['currency_code'], $user_currency_code);
                                            } else {
                                                $user_currency_code = settings('currency');
                                                $service_amount1 = $bookings['total_amount'];
                                            }
                                            $qrimg = '';
                                            if (!empty($bookings['qr_img_url']) && file_exists($bookings['qr_img_url'])) {
                                                $qrimg = base_url() . $bookings['qr_img_url'];
                                            }

                                            $serloct = $bookings['location'];

                                            $stffname = $this->db->select('CONCAT(first_name, " ", last_name) AS name')->where_in('id', $bookings['staff_id'])->where('status', 1)->where('delete_status', 0)->from('employee_basic_details')->get()->row()->name;

                                            ?>
                                            <ul class="booking-details">
                                                <li>
                                                    <?php
                                                    $slots = json_decode($bookings['slots']);
                                                    ?>
                                                    <span>Booking Date & Time</span>
                                                    <div class="slots">
                                                        <?php
                                                        if (!empty($slots)) {
                                                            foreach ($slots as $slot) {
                                                                echo '<div class="badge bg-success">' . $slot . '</div>';
                                                            }
                                                        } else {
                                                            echo "None";
                                                        }

                                                        ?>
                                                    </div>
                                                </li>
                                                <li>
                                                    <span><?php echo (!empty($user_language[$user_selected]['lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?></span>
                                                    <?php

                                                    $final_amount = $this->db->select('final_amount,currency_code')
                                                        ->where('id', $bookings['id'])
                                                        ->get('book_service')->row_array();

                                                    $finalAmount = $final_amount['final_amount'];
                                                    $currencyCode =  $final_amount['currency_code'];

                                                    $finalAmount = get_gigs_currency($finalAmount, $currencyCode, $user_currency_code);

                                                    //  if($offerPrice != '' && $bookings['offersid'] > 0) {   
                                                    //     echo currency_conversion($user_currency_code) . $offerPrice;

                                                    if ($offerPrice != '' && $bookings['offersid'] > 0) {

                                                        echo currency_conversion($user_currency_code) . ' ' . $finalAmount;

                                                    ?>


                                                        <del><?php echo currency_conversion($user_currency_code) . ' ' . $service_amount1; ?></del>
                                                    <?php } else {
                                                        echo currency_conversion($user_currency_code) . ' ' . $service_amount1;
                                                    }

                                                    /*if($couponPrice != '') { 
                                                        echo currency_conversion($user_currency_code) .' '. $couponPrice; ?><del><?php echo currency_conversion($user_currency_code) .' '. $service_amount1; ?></del>
                                                     <?php } else { 
                                                        echo currency_conversion($user_currency_code) .' '. $service_amount1;
                                                     } */ ?>
                                                </li>
                                                <li>
                                                    <span>Booking Amount</span> <?php echo currency_conversion($user_currency_code) . ' ' . $bookings['booking_amnt']; ?>
                                                </li>
                                                <li><span><?php echo (!empty($user_language[$user_selected]['lg_Location'])) ? $user_language[$user_selected]['lg_Location'] : $default_language['en']['lg_Location']; ?></span> <?php echo $bookings['location'] ?></li>
                                                <li><span>Manager</span> <?php echo ($stffname) ? $stffname : '-'; ?></li>
                                                <li><span><?php echo (!empty($user_language[$user_selected]['lg_Phone'])) ? $user_language[$user_selected]['lg_Phone'] : $default_language['en']['lg_Phone']; ?></span> <?php echo $provider_info['mobileno'] ?></li>
                                                <li>
                                                <li><span><?php echo (!empty($user_language[$user_selected]['lg_paytype'])) ? $user_language[$user_selected]['lg_paytype'] : $default_language['en']['lg_paytype']; ?></span> <?php echo $bookings['paytype']; ?></li>
                                                <li>
                                                    <a class="direction_map" target="_blank" href="http://maps.google.com/maps?q=<?php echo $serloct; ?>/booking=<?php echo $bookings['id']; ?>">
                                                        <small><i class="fas fa-map-marker-alt me-2"></i><?php echo (!empty($user_language[$user_selected]['lg_GoogleMap_Link'])) ? $user_language[$user_selected]['lg_GoogleMap_Link'] : $default_language['en']['lg_GoogleMap_Link']; ?></small></a>
                                                </li>
                                                <li>
                                                    <span><?php echo (!empty($user_language[$user_selected]['lg_Provider'])) ? $user_language[$user_selected]['lg_Provider'] : $default_language['en']['lg_Provider']; ?></span>
                                                    <div class="avatar avatar-xs me-2">
                                                        <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo $image; ?>">
                                                    </div> <?php echo  !empty($provider_info['name']) ? $provider_info['name'] : '-'; ?>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>

                                    <div class="booking-action">
                                        <?php $pending = 0;
                                        $rating_count = $this->db->where('user_id', $this->session->userdata('id'))->where('booking_id', $bookings['id'])->get('rating_review')->row();

                                        $curtime = date('Y-m-d H:i:s');
                                        $curdate = date('Y-m-d');
                                        $reqdata = $bookings['request_date'] . " " . $bookings['request_time'];

                                        /* Booking Edit Until 1Hour */
                                        $my_book_time = date('Y-m-d H:i:s', strtotime($reqdata . ' +60 minutes'));
                                        $allowedit = '0';
                                        if (strtotime($curtime) <= strtotime($my_book_time)) {
                                            $allowedit = '1';
                                        }
                                        /* Booking Edit Until 1Hour */

                                        /* Booking Cancel 24Hour */
                                        $allowcancel = '';
                                        $mybook_time = date('Y-m-d H:i:s', strtotime($reqdata . ' +3600 minutes'));
                                        if (strtotime($curtime) > strtotime($mybook_time)) {
                                            $allowcancel = 'd-none';
                                        }
                                        /* Booking Cancel Until 24Hour */

                                        ?>
                                        <?php if ($bookings['status'] == 1 || $bookings['status'] == 2) {
                                            if ($allowedit == '1') {
                                        ?>
                                                <a href="<?php echo base_url() ?>edit-appointment/<?php echo $bookings['id'] ?>" class="btn btn-sm bg-primary-light">
                                                    <i class="far fa-edit"></i> <?php echo (!empty($user_language[$user_selected]['lg_Edit'])) ? $user_language[$user_selected]['lg_Edit'] : $default_language['en']['lg_Edit']; ?>
                                                </a>
                                        <?php }
                                        } ?>
                                        <?php if ($bookings['status'] == 2) { ?>
                                            <a href="<?php echo base_url() ?>user-chat/booking-new-chat?book_id=<?php echo $bookings['id'] ?>" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?>
                                            </a>

                                            <a href="javascript:;" class="btn btn-sm bg-danger-light myCancel <?php echo $allowcancel; ?>" data-bs-toggle="modal" data-bs-target="#myCancel" data-id="<?php echo $bookings['id'] ?>" data-providerid="<?php echo $bookings['provider_id'] ?>" data-userid="<?php echo $bookings['user_id'] ?>" data-serviceid="<?php echo $bookings['service_id'] ?>">
                                                <i class="fas fa-times"></i> <?php echo (!empty($user_language[$user_selected]['lg_cancel_service'])) ? $user_language[$user_selected]['lg_cancel_service'] : $default_language['en']['lg_cancel_service']; ?>
                                            </a>

                                        <?php } elseif ($bookings['status'] == 1) { ?>
                                            <a href="javascript:;" class="btn btn-sm bg-danger-light myCancel <?php echo $allowcancel; ?>" data-bs-toggle="modal" data-bs-target="#myCancel" data-id="<?php echo $bookings['id'] ?>" data-providerid="<?php echo $bookings['provider_id'] ?>" data-userid="<?php echo $bookings['user_id'] ?>" data-serviceid="<?php echo $bookings['service_id'] ?>">
                                                <i class="fas fa-times"></i> <?php echo (!empty($user_language[$user_selected]['lg_cancel_service'])) ? $user_language[$user_selected]['lg_cancel_service'] : $default_language['en']['lg_cancel_service']; ?>
                                            </a>
                                        <?php } elseif ($bookings['status'] == 3) { ?>
                                            <a href="<?php echo base_url() ?>user-chat/booking-new-chat?book_id=<?php echo $bookings['id'] ?>" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?>
                                            </a>
                                            <a href="javascript:;" class="btn btn-sm bg-success-light update_user_booking_status" data-id="<?php echo  $bookings['id']; ?>" data-status="6" data-rowid="<?php echo  $pending; ?>" data-review="2">
                                                <i class="fas fa-check"></i><?php echo (!empty($user_language[$user_selected]['lg_Compete_Request_Accept'])) ? $user_language[$user_selected]['lg_Compete_Request_Accept'] : $default_language['en']['lg_Compete_Request_Accept']; ?>
                                            </a>
                                        <?php } ?>

                                        <?php if ($bookings['status'] == 6) { ?>
                                            <a href="<?php echo base_url() ?>user-chat/booking-new-chat?book_id=<?php echo $bookings['id'] ?>" class="btn btn-sm bg-info-light">
                                                <i class="far fa-eye"></i> <?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?>
                                            </a>
                                            <?php if (empty($rating_count)  && settingValue('review_showhide') == 1) { ?>
                                                <a href="javascript:void(0);" class="btn btn-sm bg-success-light myReview" data-bs-toggle="modal" data-bs-target="#myReview" data-id="<?php echo $bookings['id'] ?>" data-providerid="<?php echo $bookings['provider_id'] ?>" data-userid="<?php echo $bookings['user_id'] ?>" data-serviceid="<?php echo $bookings['service_id'] ?>">
                                                    <i class="fas fa-plus"></i> <?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?>
                                                </a>
                                        <?php }
                                        } ?>

                                        <?php if ($bookings['status'] == 7 || $bookings['status'] == 5) { ?>
                                            <button type="button" data-id="<?php echo $bookings['id'] ?>" class="btn btn-sm bg-default-light reason_modal">
                                                <i class="fas fa-info-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_reason'])) ? $user_language[$user_selected]['lg_reason'] : $default_language['en']['lg_reason']; ?>
                                            </button>
                                            <input type="hidden" id="reason_<?php echo  $bookings['id']; ?>" value="<?php echo  $bookings['reason']; ?>">
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <p><?php echo (!empty($user_language[$user_selected]['lg_no_record_fou'])) ? $user_language[$user_selected]['lg_no_record_fou'] : $default_language['en']['lg_no_record_fou']; ?></p>
                    <?php } ?>
                    <?php
                    echo $this->ajax_pagination->create_links();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>