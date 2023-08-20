<div class="page-wrapper">
	<div class="content container-fluid">
	
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
	            <div class="col-md-6">
	            	<h4 class="page-title">Notification List</h4>
	          	</div>
	          	<div class="col-md-6 text-end">
	          		<?php if (count($admin_notification)>0) { ?>
	              	<a id="not_del_all" data-id="" class='btn btn-sm bg-danger-light'><i class='far fa-trash-alt mr-1' ></i> Delete All</a>
	             <?php } ?>
	          	</div>
	      	</div>
		</div>
		
		<!-- /Page Header -->
		<div class="admin-noti-wrapper">
			<?php
			if (count($admin_notification)>0) {
				foreach ($admin_notification as $key => $value) {
		            $full_date =date('Y-m-d H:i:s', strtotime($value['utc_date_time']));
					$date=date('Y-m-d',strtotime($full_date));
					$date_f=date('d-m-Y',strtotime($full_date));
					$yes_date=date('Y-m-d',(strtotime ( '-1 day' , strtotime (date('Y-m-d')) ) ));
					$time=date('H:i',strtotime($full_date));
					$session = date('h:i A', strtotime($time));
					if($date == date('Y-m-d')){
					$timeBase ="Today ".$session;
					}elseif($date == $yes_date){
					$timeBase ="Yester day ".$session;
					}else{
					$timeBase =$date_f." ".$session;
					}
			?>
			<div class="noti-list">
				<div class="noti-avatar">
					<?php
					if(!empty($value['profile_img'])){
						$image=base_url().$value['profile_img'];
					}else{
						$image=base_url().'assets/img/user.jpg';
					}
					?>
					<img src="<?php echo $image;?>" alt="">
				</div>
				<div class="noti-contents">
					<h3><?php echo $value['message'];?></h3>
					<span><?php echo $timeBase;?></span>
				</div>
				<div class="text-end">
						<a class='btn btn-sm bg-danger-light' id="not_del"data-id="<?php  echo $value['notification_id']; ?>"><i class='far fa-trash-alt mr-1' ></i> </a>
					</div>
			</div>

			<?php } }else{ ?>
			<div class="notificationlist">
				<p class="text-danger mt-3">Notification Empty</p>
			</div>
		   <?php } ?>
	   </div>
	</div>
</div>

<div class="modal" id="not_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confiramtion</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you confirm to Delete.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_delete_sub" data-id="" class="btn btn-primary">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="notall_delete_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5>Delete Confiramtion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you confirm to Delete all.</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="confirm_deleteall_sub" data-id="" class="btn btn-primary">Confirm</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>