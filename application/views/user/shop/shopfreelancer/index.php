<?php
$currency = currency_conversion(settings('currency'));

$query = $this->db->query("select * from system_settings WHERE status = 1");
$result = $query->result_array();
if (!empty($result)) {
    foreach ($result as $data) {
        if ($data['key'] == 'currency_option') {
            $currency_option = $data['value'];
        }
    }
}
?>

<div class="content">
    <div class="container">
        <div class="row">
            <?php $this->load->view('user/home/provider_sidemenu');?>
               
            
            <div class="col-xl-9 col-md-8">
				<h4 class="widget-title">My Shops</h4>
                <div class="addnewdiv">
					<h6><a href="<?php echo base_url()?>freelances/add-shop"><i class="fas fa-plus"></i>Add New Shop</a></h4>
				</div>
				<div>&nbsp;</div>
				<ul class="nav nav-tabs menu-tabs">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo base_url() ?>freelances/shop">Active Shops</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url() ?>freelances/my-shop-inactive">Inactive Shops</a>
                    </li>
                </ul>
                <div>
                    <div class="row" id="dataList">

                        <?php 
                        if (!empty($shops)) {

                            foreach ($shops as $srows) {                                

                                $this->db->select("shop_image");
								$this->db->from('shops_images');
								$this->db->where("shop_id", $srows['id']);
								$this->db->where("status", 1);
								$image = $this->db->get()->row_array();
								$shopimages = $image['shop_image'];
								$provider_details = $this->db->where('id', $srows['provider_id'])->get('providers')->row_array();
								$service_availability = 0;

                                
                                ?>
                                <div class="col-lg-3 col-md-6">
                                    <div class="service-widget">
                                        <div class="service-img">
                                            <a href="<?php echo base_url() . 'freelances/shop-preview/' . str_replace(' ', '-', strtolower($srows['shop_name'])) . '?sid=' . md5($srows['id']); ?>">
                                                <?php if (!empty($shopimages) && file_exists($shopimages) && (@getimagesize(base_url().$shopimages))) { ?>
                                                    <img class="img-fluid serv-img" alt="Shop Image" src="<?php echo base_url() . $shopimages; ?>">
                                                <?php } else { ?>
                                                    <img class="img-fluid serv-img" alt="Service Image" src="<?php echo base_url().'assets/img/placeholder_shop.png';?>">
                                                <?php } ?>
                                            </a>   
											<div class="item-info">
                                            <div class="service-user">
                                                <a href="javascript:void(0);">
                                                    <?php if ($provider_details['profile_img'] == '') { ?>
                                                        <img src="<?php echo base_url(); ?>assets/img/user.jpg">
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url() . $provider_details['profile_img'] ?>">
                                                    <?php } ?>
                                                </a>
                                               
                                            </div>                                           
                                        </div>	
                                        </div>
                                        <div class="service-content">
                                            <h3 class="title">
                                                <a href="<?php echo base_url() . 'freelances/shop-preview/' . str_replace(' ', '-', strtolower($srows['shop_name'])) . '?sid=' . md5($srows['id']); ?>"><?php echo ucfirst($srows['shop_name']); ?></a>
                                            </h3>
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
                                                    <?php if ($this->session->userdata('id') != '') {
                                                        ?>
                                                        <span class="col ser-contact"><i class="fas fa-phone mr-1"></i> <span>xxxxxxxx<?php echo  rand(00, 99) ?></span></span>
                                                    <?php } else { ?>
                                                        <span class="col ser-contact"><i class="fas fa-phone mr-1"></i> <span>xxxxxxxx<?php echo  rand(00, 99) ?></span></span>
                                                    <?php } ?>

                                                    <span class="col ser-location"><span><?php echo ucfirst($srows['shop_location']); ?></span> <i class="fas fa-map-marker-alt ml-1"></i></span>
                                                </div>
												
												<div class="service-action">
                                                <div class="row">
                                                    <div class="col"><a href="<?php echo base_url() ?>freelances/edit-shop/<?php echo $srows['id'] ?>" class="text-success"><i class="far fa-edit"></i> Edit</a></div>
													<?php if($service_availability==0){?>
														<div class="col text-right"><a href="javascript:void(0);" class="si-inactive-shop text-danger" data-id="<?php echo $srows['id']; ?>"><i class="fas fa-info-circle"></i> Inactive</a></div>
													<?php }else{?>
														<div class="col text-right"><a href="javascript:void(0);" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteNotConfirmModal"><i class="far fa-trash-alt"></i> Inactive</a></div>
													<?php }?>
                                                    
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <?php
                            }
                        } else {

                            echo '<div class="col-lg-12">
									<p class="mb-0">
										No Shops Found
									</p>
								</div>';
                        }

                        echo $this->ajax_pagination->create_links();
                        ?>



                    </div>
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
                <h5 class="modal-title" id="acc_title">Inactive Shop?</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="acc_msg">Shop is Booked and Inprogress..</p>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-danger si_accept_cancel" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

