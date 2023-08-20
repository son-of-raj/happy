<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="admin_csrf" />
<input type="hidden" id="csrf_token" value="<?php echo $this->security->get_csrf_hash(); ?>">
<input type="hidden" id="base_url" value="<?php echo $base_url; ?>">
<input type="hidden" name="country_code_key" id='country_code_key' value="<?php echo settingValue('country_code_key'); ?>" >
<input type="hidden" id="lan_page_id" value="<?php echo $this->uri->segment(2); ?>">
<input type="hidden" id="lan_lang_id" value="<?php echo $this->uri->segment(3); ?>">
</div>
<script src="<?php echo $base_url; ?>assets/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/moment.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url();?>assets/plugins/owlcarousel/owl.carousel.min.js"></script>
<script src="<?php echo base_url();?>assets/js/tagsinput.js"></script>

<!-- Slimscroll JS -->
<script src="<?php echo $base_url; ?>assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<?php $page = $this->uri->segment(1); ?>
<script src="<?php echo $base_url; ?>assets/js/bootstrapValidator.min.js"></script>

<!-- Datatables JS -->
<script src="<?php echo $base_url; ?>assets/plugins/datatables/datatables.min.js"></script>

<!-- Jvector map JS -->

<script src="<?php echo $base_url; ?>assets/js/bootstrap-notify.min.js"></script>

<!-- Select2 JS -->
<script src="<?php echo $base_url; ?>assets/js/select2.min.js"></script>
<script src="<?php echo base_url();?>assets/js/sweetalert.min.js"></script>
<script  src="<?php echo $base_url; ?>assets/js/admin.js"></script>
<?php $page2 = $this->uri->segment(2);
    if(($page == 'users' && $page2 == 'add') || ($page == 'staff-edit') || ($page == 'shop-edit') || ($page == 'edit-user') || ($page == 'edit-provider') || ($page == 'add-provider')){ ?>
<script src="<?php echo base_url();?>assets/js/intlTelInput.js"></script>
<?php } ?>
<?php if($page =="edit-service" || $page2 =="add-service"){ ?>
<script src="<?php echo base_url(); ?>assets/js/service.js"></script>
<?php } ?>
<input type="hidden" id="page" value="<?php echo  $this->uri->segment(1);?>">
<input type="hidden" id="provider_list_url" value="<?php echo site_url('provider_list')?>">
<input type="hidden" id="requests_list_url" value="<?php echo site_url('request_list')?>">
<input type="hidden" id="user_list_url" value="<?php echo site_url('users-list')?>">
<input type="hidden" id="adminuser_list_url" value="<?php echo site_url('adminusers-list')?>">

<input type="hidden" id="staff_list_url" value="<?php echo site_url('staff-lists')?>">
<input type="hidden" id="shop_list_url" value="<?php echo site_url('shop-lists')?>">

<?php if($page == 'admin-profile'){ ?>
	<script src="<?php echo $base_url; ?>assets/js/cropper_profile.js"></script>
	<script src="<?php echo $base_url; ?>assets/js/cropper.min.js"></script>
<?php } ?>

<script src="<?php echo $base_url; ?>assets/js/jquery.checkboxall-1.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>
<script src="<?php echo $base_url; ?>assets/js/admin_functions.js"></script>

<!--External js Start-->
<?php if($this->uri->segment(1)=="reject-payment"){ ?>
	<script src="<?php echo base_url();?>assets/js/edit_reject_booking_view.js"></script>
<?php }?>
<?php if($this->uri->segment(2)=="emailsettings"){ ?>
	<script src="<?php echo base_url();?>assets/js/admin_emailsettings.js"></script>
<?php }?>
<?php if($this->uri->segment(2)=="stripe-payment-gateway" || $this->uri->segment(2)=="settings"){ ?>
  <script src="<?php echo base_url();?>assets/js/stripe_payment_gateway.js"></script>
<?php }?>
<?php if($this->uri->segment(1)=="staff-edit" || $this->uri->segment(1)=="shop-edit"){ ?>
<script src="<?php echo base_url();?>assets/js/admin_availability.js"></script>
<?php }?>
<!--External js end-->
 <?php 
if(settingValue('socket_showhide') == 1) { 
  $port = settingValue('server_port');
  $ip = settingValue('server_ip');
} else { 
  $port = '107.1.1.1';
  $ip = '8443';
} 
$user['id'] = '';
$user['name'] = 'Admin';
$user['usertype'] = 'user';
  ?>

<input type="hidden" id="socketHost" value="<?php echo $ip; ?>">
<input type="hidden" id="socketPort" value="<?php echo  $port; ?>">
<input type="hidden" id="WS" value="<?php echo $this->db->WS; ?>">
<input type="hidden" id="chat_user" value='<?php print addslashes(json_encode($user)); ?>'>

<input type="hidden" id="usertype" name="user_type" value="<?php echo $this->session->userdata('usertype'); ?>">
    <?php if (settingValue('chat_type') == 'websocket') { ?>
<script src="<?php echo $base_url; ?>assets/js/admin_chat.js"></script>
<script src="<?php echo $base_url; ?>assets/js/websocket.js"></script>
    <?php } else { ?>
<script src="<?php echo $base_url; ?>assets/js/admin_normal_chat.js"></script>
    <?php } ?>
<?php   if($page == 'add-subscription' || $page == 'edit-subscription'){ ?>
<script src="<?php echo base_url();?>assets/js/bootstrap-select.min.js"></script>
<?php }  ?>
</body>
</html>