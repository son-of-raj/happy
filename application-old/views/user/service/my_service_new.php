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

<?php
$cur_time = date('H:i:s');
?>
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu'); ?>
            <div class="col-xl-9 col-md-8">
                <ul class="nav nav-tabs menu-tabs mb-4">
                    <li class="nav-item ">
                        <a class="nav-link active" href="<?php echo base_url() ?>my-services"><?php echo (!empty($user_language[$user_selected]['lg_Active_Services'])) ? $user_language[$user_selected]['lg_Active_Services'] : $default_language['en']['lg_Active_Services']; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url() ?>my-services-inactive"><?php echo (!empty($user_language[$user_selected]['lg_Inactive_Services'])) ? $user_language[$user_selected]['lg_Inactive_Services'] : $default_language['en']['lg_Inactive_Services']; ?></a>
                    </li>
					<button type="button" class="btn btn-info btn-sm float-right " id="muliple_offer_apply" style="display: none;" data-bs-toggle="modal" data-bs-target="#multi_offer-modal">Apply Offer for selected services</button>
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
								$serviceimage = settingValue('service_placeholder_image');
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
							$datecondt = "start_date <= curdate() and end_date >= curdate()";
							$soffer = $this->db->select('id, end_date, start_time, end_time, offer_percentage')->where("service_id",$srows['id'])->where("provider_id",$userId)->where("df",0)->where($datecondt)->get("service_offers")->row_array();
							
							$servicetitle = preg_replace('/[^\w\s]+/u',' ',$srows['service_title']);
							$servicetitle = str_replace(' ', '-', $servicetitle);
							$servicetitle = trim(preg_replace('/-+/', '-', $servicetitle), '-');
							$service_url = base_url() . 'service-preview/' . $servicetitle . '?sid=' . md5($srows['id']);
							
							$shplocation = $this->db->select('shop_location')->where('id',$srows['shop_id'])->get('shops')->row()->shop_location;
							$offerPrice = '';
            
                            if (!empty($soffer['offer_percentage']) && $soffer['offer_percentage'] > 0) {
                                $offerPrice = $service_amount * ($soffer['offer_percentage'] / 100 );
                                $offerPrice = $service_amount - $offerPrice;
                                $offerPrice = number_format($offerPrice,2);
                            } 
                            ?>

                            <div class="col-lg-4 col-md-6 d-flex">
                                <div class="feature-shop mb-4 w-100">
                                    <div class="feature-img">
										<a href="<?php echo $service_url; ?>">
                                            <img class="categorie-img" alt="Service Image" src="<?php echo $serviceimage; ?>">
                                        </a> 
                                        <div class="feature-bottom">
                                            <a href="javascript:void(0);">
                                                <?php if ($provider_details['profile_img'] == '') { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url().settingValue('profile_placeholder_image') ?>">
                                                <?php } else { ?>
                                                    <img class="rounded-circle" src="<?php echo base_url() . $provider_details['profile_img'] ?>">
                                                <?php } ?>
                                            </a>
                                             <p>
                                                <a href="<?php echo base_url() . 'search/' . str_replace(' ', '-', strtolower($srows['category_slug'])); ?>"><?php echo $srows['category_name']; ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="featute-info featute-info1">
                                        <h4>
                                           <a href="<?php echo $service_url; ?>"><?php echo $srows['service_title']; ?></a> 
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
                                      <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo ($srows['service_location'])?ucfirst($srows['service_location']):$shplocation; ?></p>
                                   
                                    <div class="service-content service-content1">
                                        <div class="user-info">
                                            <div class="service-action">
                                                <div class="row">
                                                    <div class="col"><a href="<?php echo base_url() ?>user/service/edit_service/<?php echo $srows['id'] ?>" class="text-success"><i class="far fa-edit"></i> Edit</a></div>
                                                    <?php if ($service_availability == 0) { ?>

                                                        <div class="col text-end"><a href="javascript:void(0);" class="si-delete-service text-danger" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Inactive</a></div>
                                                    <?php } else { ?>
                                                        <div class="col text-end"><a href="javascript:void(0);" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Inactive</a></div>
                                                    <?php } ?>
                                                </div>
                                                <div class="row mt-2">
                                                    <?php
                                                    if ($soffer['id']!='' && ($soffer['end_date'] >= date('Y-m-d')) && ($cur_time >= $soffer['start_time']) && ($cur_time <= $soffer['end_time'])) {
                                                        ?>
                                                        <div class="col">
                                                            Current Offer: <?php echo $soffer['offer_percentage']?>%
                                                        </div>
                                                        <?php 
                                                    }
                                                    else {
                                                    ?>
                                                    <div class="col">
                                                        <button type="button" class="btn btn-info btn-sm" onclick="check_offer('<?php echo $srows['id']; ?>')" data-bs-toggle="modal" data-bs-target="#offer-modal">Apply Offer</button>

                                                        <label class="checkbox-inline float-end">
                                                          <input type="checkbox" class="sids" value="<?php echo $srows['id']; ?>">&nbsp;Select
                                                        </label>
                                                    </div>
                                                    <?php 
                                                    }
                                                    ?>
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
                <h4 class="modal-title" id="acc_title"></h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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

<div class="modal fade" id="deleteNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="acc_title">Inactive Service?</h4>
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

<div class="modal fade" id="offer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="acc_title">Offer</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Offer %</label>
                                <input type="text" id="offer_percentage" class="form-control number offercls" value="">
                                <input type="hidden" id="hservice_id" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="text" id="start_date" class="form-control datetimepicker-start offercls" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="text" id="end_date" class="form-control datetimepicker-end offercls" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Time</label>
                                <select id="start_time" class="form-control form-select offercls">
                                    <option value="">Select</option>
                                    <?php
                                    $start_time = strtotime('9:00');
                                    while($start_time <= strtotime('21:00'))
                                    {
                                        $stime = date ("h:i A", $start_time);
                                        $add_mins  = 30 * 60;
                                        $start_time += $add_mins; // to check endtie=me
                                        ?>
                                        <option value="<?php echo $stime?>"><?php echo $stime?></option>
                                        <?php 
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Time</label>
                                <select id="end_time" class="form-control form-select offercls">
                                    <option value="">Select</option>
                                    <?php
                                    $end_time = strtotime('9:00');
                                    while($end_time <= strtotime('21:00'))
                                    {
                                        $etime = date ("h:i A", $end_time);
                                        $add_mins  = 30 * 60;
                                        $end_time += $add_mins; // to check endtie=me
                                        ?>
                                        <option value="<?php echo $etime?>"><?php echo $etime?></option>
                                        <?php 
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <b id="error_service" class="text-danger"></b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submit_offer">Save</button>
                <button type="button" class="btn btn-danger"  id="cancel_offer" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="multi_offer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="acc_title">Offer</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Offer %</label>
                                <input type="text" id="m_offer_percentage" class="form-control number moffercls" value="">
                                <input type="hidden" id="hsids" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>From</label>
                                <input type="text" id="m_start_date" class="form-control datetimepicker-ms moffercls" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>To</label>
                                <input type="text" id="m_end_date" class="form-control datetimepicker-me moffercls" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start Time</label>
                                <select id="m_start_time" class="form-control form-select">
                                    <option value="">Select</option>
                                    <?php
                                    $start_time = strtotime('9:00');
                                    while($start_time <= strtotime('21:00'))
                                    {
                                        $stime = date ("h:i A", $start_time);
                                        $add_mins  = 30 * 60;
                                        $start_time += $add_mins; // to check endtie=me
                                        ?>
                                        <option value="<?php echo $stime?>"><?php echo $stime?></option>
                                        <?php 
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>End Time</label>
                                <select id="m_end_time" class="form-control form-select">
                                    <option value="">Select</option>
                                    <?php
                                    $end_time = strtotime('9:00');
                                    while($end_time <= strtotime('21:00'))
                                    {
                                        $etime = date ("h:i A", $end_time);
                                        $add_mins  = 30 * 60;
                                        $end_time += $add_mins; // to check endtie=me
                                        ?>
                                        <option value="<?php echo $etime?>"><?php echo $etime?></option>
                                        <?php 
                                    } 
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="m_submit_offer">Save</button>
                <button type="button" class="btn btn-danger"  id="m_cancel_offer" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
