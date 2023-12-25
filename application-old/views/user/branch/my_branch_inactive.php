
<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu'); ?>
            <div class="col-xl-9 col-md-8">
                <h4 class="widget-title">My Branches</h4>
				<div class="addnewdiv">
					<h6><a href="<?php echo base_url()?>add-branch"><i class="fas fa-plus"></i>Add New Branch</a></h4>
				</div>
				<div>&nbsp;</div>
                <ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item ">
                        <a class="nav-link" href="<?php echo base_url() ?>branch">Active Branch</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url() ?>my-branch-inactive">Inactive Branch</a>
                    </li>
                </ul>
                <div class="row" id="dataList">


                    <?php
                    $this->session->flashdata('success_message');
                    if (!empty($branchs)) {

                            foreach ($branchs as $srows) {                                

                                $this->db->select("branch_image");
								$this->db->from('branch_images');
								$this->db->where("branch_id", $srows['id']);
								$this->db->where("status", 2);
								$image = $this->db->get()->row_array();
								$shopimages = $image['branch_image'];
								$provider_details = $this->db->where('id', $srows['provider_id'])->get('providers')->row_array();
								$sname = $this->db->select('shop_name')->where('id', $srows['shop_id'])->get('shops')->row_array();
                            ?>

                            <div class="col-lg-4 col-md-6 inactive-service">
                                <div class="service-widget">
                                    <div class="service-img">
                                        <a href="javascript:;">
                                            <?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
												<img class="img-fluid serv-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
											<?php } else { ?>
												<img class="img-fluid serv-img" alt="Shop Image" src="<?php echo base_url().'assets/img/placeholder_shop.png';?>">
											<?php } ?>
                                        </a>
                                        <div class="item-info">
                                            <div class="service-user">
                                                <a href="javascript:void(0);">
                                                    <?php if($provider_details['profile_img'] != '' && file_exists($provider_details['profile_img'])) { ?>
														<img src="<?php echo base_url() . $provider_details['profile_img'] ?>">
													<?php } else { ?>
														<img src="<?php echo base_url(); ?>assets/img/user.jpg">
													<?php } ?>
                                                </a>
                                               
                                            </div>                                           
                                        </div>
                                    </div>
                                    <div class="service-content">
                                        <h3 class="title" title="Branch Title">
                                            <a href="javascript:;"><?php echo ucfirst($srows['branch_name']); ?></a>
                                        </h3>
										<h6 title="Shop Title"><span class="ser-location text-left"><span class="text-info small">
												<?php echo $sname['shop_name'];?>
											</span></span></h6>
                                        <div class="rating">

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
                                            <span class="d-inline-block average-rating">(<?php echo $avg_rating ?>)</span>
                                        </div>
                                        <div class="user-info">
                                            <div class="row">
                                                <span class="col ser-contact"><i class="fas fa-phone mr-1"></i> <span>xxxxxxxx<?php echo  rand(00, 99) ?></span></span>
                                                <span class="col ser-location"><span><?php echo $srows['branch_location']; ?></span> <i class="fas fa-map-marker-alt ml-1"></i></span>
                                            </div>
                                            <div class="service-action">
                                                <div class="row">
                                                    <div class="col"><a href="javascript:void(0)" class="si-delete-inactive-branch text-danger" data-id="<?php echo $srows['id']; ?>"><i class="far fa-trash-alt"></i> Delete</a></div>
                                                    <div class="col text-right"><a href="javascript:void(0)" class="si-active-branch text-success" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Active</a></div>

                                                </div>
                                            </div>
                                        </div>											
                                    </div>
                                </div>								
                            </div>
                        <?php
                        }
                    } else {
                        echo '<div class="col-xl-12 col-lg-12">No Branches Found</div>';
                    }
                    ?>

                    <!-- Pagination Links -->
                    <?php
                    if (!empty($branchs)) {
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
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success si_accept_confirm">Yes</a>
                <button type="button" class="btn btn-danger si_accept_cancel" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteNotConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acc_title">Delete Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Shop is Booked and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
