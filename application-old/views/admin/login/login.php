<?php
    $query = $this->db->query("select * from system_settings WHERE status = 1");
    $result = $query->result_array();
    $this->website_name = '';
    $website_logo_front ='assets/img/logo.png';
    
    if(!empty($result)) {
		foreach($result as $data){
			if($data['key'] == 'website_name'){
				$this->website_name = $data['value'];
			}
			if($data['key'] == 'logo_front'){
				$website_logo_front =  $data['value'];
			}
		}
    }
?>
<header class="account-header">
	<nav class="navbar navbar-expand-lg header-nav">
		<div class="navbar-header">
			<a id="mobile_btn" href="javascript:void(0);">
				<span class="bar-icon">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</a>
			<a href="<?php echo $base_url."admin"; ?>" class="navbar-brand logo">
				<img src="<?php echo $base_url.$website_logo_front; ?>" class="img-fluid" alt="Logo">
			</a>
		</div>
	</nav>
</header>
<div class="login-page">
	<div class="login-body container">
		<div class="loginbox">
			<div class="login-right-wrap">
				<div class="login-header">
					<h3><?php echo $this->website_name;?> Account Login</h3>
					<p class="text-muted">Welcome back! Log In with your Email or Username</p>
				</div>

				<?php if($this->session->flashdata('error_message')) {  ?>
				<div class="alert alert-danger text-center " id="flash_error_message"><?php echo $this->session->flashdata('error_message');?></div>
				<?php $this->session->unset_userdata('error_message');
				} ?>
				<?php if($this->session->flashdata('success_message')) {  ?>
				<div class="alert alert-success text-center" id="flash_succ_message"><?php echo $this->session->flashdata('success_message');?></div>
				<?php $this->session->unset_userdata('success_message');
				} ?>
				<form id="adminSignIn" action="<?php echo $base_url; ?>" method="POST">
                   <input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
					<div class="form-group">
						<label class="control-label">Username</label>
						<input class="form-control" type="text" name="username" id="username">
					</div>
					<div class="form-group mb-4">
						<label class="control-label">Password</label>
						<input class="form-control" type="password" name="password" id="password">
					</div>
					<div class="form-group text-center">
						<button class="btn btn-primary btn-block account-btn" id="loginSubmit" type="button">Log In</button>
					</div>
					<div class="account-link">
						<a href="<?php echo $base_url; ?>forgot-password">Forgot Password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>