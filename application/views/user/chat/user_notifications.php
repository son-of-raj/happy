<div class="breadcrumb-bar">
    <div class="container">
		<div class="row justify-content-center">
            <div class="col-lg-10">
				<div class="row">
					<div class="col">
						<div class="breadcrumb-title">
							<h2><?php echo (!empty($user_language[$user_selected]['lg_Notifications'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?></h2>
						</div>
					</div>
					<div class="col-auto float-end ms-auto breadcrumb-menu d-flex">
						<nav aria-label="breadcrumb" class="page-breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_chats'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?>
								</li>
							</ol>
						</nav>
						<a id="not_del_all"data-id="" class='btn btn-sm bg-danger-light' style="margin-left:20px;"><i class='far fa-trash-alt mr-1' ></i> <?php echo (!empty($user_language[$user_selected]['lg_delete_all'])) ? $user_language[$user_selected]['lg_delete_all'] : $default_language['en']['lg_delete_all']; ?></a>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
<div class="content">
	<div class="container">
		<div class="row justify-content-center">
            <div class="col-lg-10">
				<div class="dashboradsec">
					<div class="notcenter" id="dataList">
					<?php
					if(!empty($notification_list)){
						
						foreach ($notification_list as $key => $value) {
							$datef = explode(' ', $value["created_at"]);
							if(settingValue('time_format') == '12 Hours') {
                                $time = date('G:ia', strtotime($datef[1]));
                            } elseif(settingValue('time_format') == '24 Hours') {
                               $time = date('H:i:s', strtotime($datef[1]));
                            } else {
                                $time = date('G:ia', strtotime($datef[1]));
                            }
                            $date = date(settingValue('date_format'), strtotime($datef[0]));
                            $timeBase = $date.' '.$time;
               
               				
						?>
						<div class="notificationlist">
							<div class="inner-content-blk position-relative">
								<div class="d-flex text-dark">
									<?php
									if(!empty($value['profile_img'])){
										$image=base_url().$value['profile_img'];
									}else{
										$image=base_url().'assets/img/user.jpg';
									}
									?>
									<img class="rounded-circle" src="<?php echo $image;?>" width="50" alt="">
									<div class="noti-contents">
										<h3><?php echo $value['message'];?></h3>
										<span><?php echo $timeBase;?></span>
									</div>
									<a class='btn btn-sm bg-danger-light' id="not_del"data-id="<?php  echo $value['notification_id']; ?>"><i class='far fa-trash-alt'></i> </a>
								</div>

							</div>
						</div>

					<?php } }else{ ?>
					<div class="notificationlist">
						<p class="text-center text-danger mt-3"><?php echo (!empty($user_language[$user_selected]['lg_notification_empty'])) ? $user_language[$user_selected]['lg_notification_empty'] : $default_language['en']['lg_notification_empty']; ?></p>
					</div>
				   <?php } ?>
				   <?php 
					if(!empty($notification_list)){
						echo $this->ajax_pagination->create_links();
					} ?>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>


<div class="modal" id="not_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5><?php echo(!empty($user_language[$user_selected]['lg_admin_delete_confirmation']))?($user_language[$user_selected]['lg_admin_delete_confirmation']) : 'Are you confirm to Delete.';  ?></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo(!empty($user_language[$user_selected]['lg_are_confirm_delete']))?($user_language[$user_selected]['lg_are_confirm_delete']) : 'Are you confirm to Delete.';  ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_delete_sub" data-id="" class="btn btn-primary"><?php echo(!empty($user_language[$user_selected]['lg_admin_confirm']))?($user_language[$user_selected]['lg_admin_confirm']) : 'Confirm';  ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo(!empty($user_language[$user_selected]['lg_admin_cancel']))?($user_language[$user_selected]['lg_admin_cancel']) : 'Cancel';  ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="notall_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5><?php echo(!empty($user_language[$user_selected]['lg_admin_delete_confirmation']))?($user_language[$user_selected]['lg_admin_delete_confirmation']) : 'Are you confirm to Delete.';  ?></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><?php echo(!empty($user_language[$user_selected]['lg_confrim_delete_all']))?($user_language[$user_selected]['lg_confrim_delete_all']) : 'Are you confirm to Delete all.';  ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_deleteall_sub" data-id="" class="btn btn-primary"><?php echo(!empty($user_language[$user_selected]['lg_admin_confirm']))?($user_language[$user_selected]['lg_admin_confirm']) : 'Confirm';  ?></button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo(!empty($user_language[$user_selected]['lg_admin_cancel']))?($user_language[$user_selected]['lg_admin_cancel']) : 'Cancel';  ?></button>
      </div>
    </div>
  </div>
</div>