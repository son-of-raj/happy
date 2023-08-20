<div class="breadcrumb-bar">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="breadcrumb-title">
                    <h2><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></h2>
                </div>
            </div>
            <div class="col-auto float-end ms-auto breadcrumb-menu">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="content">
	<div class="container">
		<div class="row">
		
			 <?php $this->load->view('user/home/user_sidemenu');?>
				
			<div class="col-xl-9 col-md-8">
						<div class="row">
							<div class="col-lg-4">
								<?php 
								$this->db->where('user_id',$this->session->userdata('id'));
								$booking_count = $this->db->count_all_results('book_service'); 

								$this->db->where('user_id',$this->session->userdata('id'));
								$reviews_count = $this->db->count_all_results('rating_review'); 

								$this->db->where(array('user_id'=>$this->session->userdata('id'),'status'=>6));
								$completed_count = $this->db->count_all_results('book_service');

								$this->db->where(array('user_id'=>$this->session->userdata('id'),'status'=>2));
								$inprogress_count = $this->db->count_all_results('book_service');

								$this->db->where(array('user_id'=>$this->session->userdata('id'),'status'=>5));
								$cancelled_count = $this->db->count_all_results('book_service');
								?>
								<a href="<?php echo base_url()?>user-bookings" class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar">
                                                    <i class="far fa-calendar-alt avatar-title rounded-circle"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-end">
                                                    <h3><?php echo $booking_count?></h3>
                                                    <p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_bookings'])) ? $user_language[$user_selected]['lg_bookings'] : $default_language['en']['lg_bookings']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </a>
							</div>
							<div class="col-lg-4">
								<a href="<?php echo base_url()?>user-reviews" class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar">
                                                    <i class="far fa-star avatar-title rounded-circle"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-end">
                                                    <h3><?php echo $reviews_count?></h3>
                                                    <p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_Reviews'])) ? $user_language[$user_selected]['lg_Reviews'] : $default_language['en']['lg_Reviews']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </a>
							</div>
							<?php
							if(!empty($this->session->userdata('chat_token'))){
								$ses_token=$this->session->userdata('chat_token');
							}else{
								$ses_token='';
							}

							if(!empty($ses_token)){
								$ret=$this->db->select('*')->
								from('notification_table')->
								where('receiver',$ses_token)->
								where('status',1)->
								order_by('notification_id','DESC')->
								get()->result_array();
								$notification=[];
								if(!empty($ret)){ 
									foreach ($ret as $key => $value) {
										$user_table=$this->db->select('id,name,profile_img,token,type')->
										from('users')->
										where('token',$value['sender'])->
										get()->row();
										$provider_table=$this->db->select('id,name,profile_img,token,type')->
										from('providers')->
										where('token',$value['sender'])->
										get()->row();
										if(!empty($user_table)){
											$user_info= $user_table;
										}else{
											$user_info= $provider_table;
										}  
										$notification[$key]['name']= !empty($user_info->name)?$user_info->name:'';
										$notification[$key]['message']= !empty($value['message'])?$value['message']:'';
										$notification[$key]['profile_img']= !empty($user_info->profile_img)?$user_info->profile_img:'';
										$notification[$key]['utc_date_time']= !empty($value['utc_date_time'])?$value['utc_date_time']:'';
									}
								}
								$n_count=count($notification);
							}else{
								$n_count=0;
								$notification=[];
							}
							?>
							<div class="col-lg-4">
								<a href="<?php echo base_url()?>notification-list" class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="avatar">
                                                    <i class="far fa-bell avatar-title rounded-circle"></i>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-end">
                                                    <h3><?php echo $n_count;?></h3>
                                                    <p class="text-muted mb-0 text-truncate"><?php echo (!empty($user_language[$user_selected]['lg_Notifications'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </a>
							</div>
						</div>
						
			</div>
		</div>
	</div>
</div>