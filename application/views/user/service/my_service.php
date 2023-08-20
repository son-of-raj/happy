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
		
			<?php $this->load->view('user/home/provider_sidemenu');?>
            <div class="col-xl-9 col-md-8">
				<div class="row">
					
					
					<?php
						$this->session->flashdata('success_message');
						if(!empty($services)){
						  foreach ($services as $srows) {
							$mobile_image=explode(',', $srows['mobile_image']);
							$this->db->select("service_image");
							$this->db->from('services_image');
							$this->db->where("service_id",$srows['id']);
							$this->db->where("status",1);
							$image = $this->db->get()->row_array(); 
						?>
					<div class="col-lg-4 col-md-6 d-flex">
						<div class="feature-shop w-100">
							<div class="feature-img">
								<a href="<?php echo base_url().'service-preview/'.str_replace(' ', '-', $srows['service_title']).'?sid='.md5($srows['id']);?>">
									<img class="categorie-img" alt="Service Image" src="<?php echo base_url().$image['service_image'];?>">
								</a>
								<div class="feature-bottom">
                                    <a href="#">
										<img class="rounded-circle" src="<?php echo base_url().settingValue('profile_placeholder_image');?>">
									</a>
                                     <p>
                                        <a href="<?php echo base_url().'search/'.str_replace(' ', '-', strtolower($srows['category_slug']));?>"><?php echo $srows['category_name'];?></a>
                                    </p>
                                </div>
							</div>
							<div class="featute-info">
                                <h4>
                                    <a href="<?php echo base_url().'service-preview/'.str_replace(' ', '-', $srows['service_title']).'?sid='.md5($srows['id']);?>"><?php echo $srows['service_title'];?></a>
                                </h4>
                                  <div class="star-rating">
                                     <?php if(!empty($srows['rating_count'])){?>
									<?php for($i=0;$i<$srows['rating_count'];$i++){ ?>
									<i class="fas fa-star filled"></i>
									<?php }?>
									<i class="fas fa-star"></i>
									<?php }else{ ?>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<i class="fas fa-star"></i>
									<?php }?>
                                     <span class="text-muted">(0)</span>
                                  </div>
                              <h6><?php echo $srows['service_amount'];?></h6>
                              <p><i class="fas fa-map-marker-alt me-2"></i> <?php echo $srows['service_location'];?></p>
                           </div>
							<div class="service-content">
								<div class="user-info">
									<div class="service-action">
										<div class="row mt-2">
											<div class="col"><a href="<?php echo base_url()?>user/service/edit_service/<?php echo $srows['id']?>"><i class="far fa-edit"></i> Edit</a></div>
											<div class="col text-end"><a href="javascript:void(0)" class="si-delete-service" data-id="<?php echo $srows['id']; ?>"><i class="far fa-trash-alt"></i> Delete</a></div>
										</div>
									</div>
								</div>											
							</div>
						</div>								
					</div>	
					<?php } }
					else{
						echo '<h3>No Services Found</h3>';
					} ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">×</button>
				<h5 class="modal-title" id="acc_title"></h5>
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
