<?php 
$get_details = $this->db->where('id',$this->session->userdata('id'))->get('providers')->row_array();
?>
<div class="content">
	<div class="container">
		<div class="row">
		 	<?php

			if(!empty($_GET['tbs'])){
				$val=$_GET['tbs'];
			}else{
				$val=1;
			}
			?>
			<input type="hidden" name="tab_ctrl" id="tab_ctrl" value="<?php echo $val;?>">
			<?php $this->load->view('user/home/provider_sidemenu');?>
		 
            <div class="col-xl-9 col-md-8">
				<div class="tab-content pt-0">
					<div class="tab-pane show active" id="user_profile_settings" >
						<div class="widget">
							<h4 class="widget-title">Change Password</h4>
							<form id="update_user_pwd" action="<?php echo base_url()?>user/dashboard/update_provider_password" method="POST" onsubmit="return updatepassword();" enctype="multipart/form-data">
								<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
   
								<div class="row">
									<div class="form-group col-xl-12">
										<label class="mr-sm-2">Current Password</label>
										<input class="form-control" onBlur="checkcurpwd();" id="current_password" type="password" name="current_password">
										<span id="errchkcp"></span>
									</div>
									<div class="form-group col-xl-12">
										<label class="mr-sm-2">New Password</label>
										<input class="form-control" id="new_password" type="password"   name="new_password">
									</div>
									<div class="form-group col-xl-12">
										<label class="mr-sm-2">Confirm Password</label>
										<input class="form-control" id="confirm_password" type="password" name="confirm_password"  >
										
										<span id="errchk"></span>
									</div>
									<div class="form-group col-xl-12">
										<button name="form_submit" id="form_submit" class="btn btn-primary pl-5 pr-5" type="submit">Change</button>
									</div>
								
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
