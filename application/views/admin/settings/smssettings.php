<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">SMS Settings</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <form id="form_smssetting" action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <div class="row">
                <div class="col-xl-8 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5>Default OTP</h5>
                                    <p class="mb-0">You can use default otp <strong>1234</strong> for demo purpose</p>
                                </div>
                                <div class="col-auto">
                                    <div class="status-toggle">
                                        <?php if ($user_role == 1) { ?>
                                            <input  id="default_otp" class="check" type="checkbox" name="default_otp" <?php echo ($default_otp == 1) ? 'checked' : ''; ?>>
                                        <?php } else { ?>
                                            <input  id="default_otp" class="check" type="checkbox" name="default_otp" <?php echo ($default_otp == 1) ? 'checked' : ''; ?> disabled>
                                        <?php } ?>
                                        <label for="default_otp" class="checktoggle">checkbox</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs menu-tabs">
                                <li data-id="nexmo" class="nav-item active">
                                    <a href="javascript:void(0);" class="nav-link">Nexmo</a>
                                </li>
                                <li data-id="2factor" class="nav-item ">
                                   <a href="javascript:void(0);" class="nav-link">2Factor</a>
                                </li>
                                <li data-id="twilio" class="nav-item">
                                    <a href="javascript:void(0);" class="nav-link">Twilio</a>
                                </li>
                            </ul>
                            <div id="nexmo_div">
                                <div class="row align-items-center mb-4">
                                    <div class="col">
                                         <h4 class="mb-0">Nexmo</h4>
                                    </div>
                                    <div class="col-auto">
                                        <div class="status-toggle">
                                            <input type="checkbox" name="sms_option" class="check sms_option" id="nexmo" value="Nexmo" <?php if($sms_option == 'Nexmo') { echo 'checked'; } ?> >
                                            <label for="nexmo" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>API Key</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="nexmo_sms_key" value="<?php if (isset($nexmo_sms_key)) echo $nexmo_sms_key; ?>">
                                <?php } else {
                                    $nexmo_sms_key_length = strlen($nexmo_sms_key);
                                    $str = str_repeat("x", $nexmo_sms_key_length);
                                    $nexmo_sms_key = "". $str;
                                 ?>
                                    <input type="text" id="nexmo_sms_key" name="nexmo_sms_key" value="<?php if (!empty($nexmo_sms_key)) {echo $nexmo_sms_key;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>API Secret Key</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="nexmo_sms_secret_key" value="<?php if (isset($nexmo_sms_secret_key)) echo $nexmo_sms_secret_key; ?>">
                                <?php } else {
                                    $nexmo_sms_secret_key_length = strlen($nexmo_sms_secret_key);
                                    $str = str_repeat("x", $nexmo_sms_secret_key_length);
                                    $nexmo_sms_secret_key = "". $str;
                                 ?>
                                    <input type="text" id="nexmo_sms_secret_key" name="nexmo_sms_secret_key" value="<?php if (!empty($nexmo_sms_secret_key)) {echo $nexmo_sms_secret_key;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Sender ID</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="nexmo_sms_sender_id" value="<?php if (isset($nexmo_sms_sender_id)) echo $nexmo_sms_sender_id; ?>">
                                <?php } else {
                                    $nexmo_sms_sender_id_length = strlen($nexmo_sms_sender_id);
                                    $str = str_repeat("x", $nexmo_sms_sender_id_length);
                                    $nexmo_sms_sender_id = "". $str;
                                 ?>
                                    <input type="text" id="nexmo_sms_sender_id" name="nexmo_sms_sender_id" value="<?php if (!empty($nexmo_sms_sender_id)) {echo $nexmo_sms_sender_id;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="mt-4">
                                    <?php if ($user_role == 1) { ?>
                                        <button name="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>
                                    <?php } ?>

                                </div>
                            </div>
                          
                            <!-- 2Factor -->
                            <div id="2factor_div">
                                <div class="row align-items-center mb-4">
                                    <div class="col">
                                         <h4 class="mb-0">2Factor</h4>
                                    </div>
                                    <div class="col-auto">
                                        <div class="status-toggle">
                                            <input type="checkbox" name="sms_option" class="check sms_option" id="2Factor" value="2Factor" <?php if($sms_option == '2Factor') { echo 'checked'; } ?>>
                                            <label for="2Factor" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>API Key Sandbox</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="factor_sms_key" value="<?php if (isset($factor_sms_key)) echo $factor_sms_key; ?>">
                                <?php } else {
                                    $factor_sms_key_length = strlen($factor_sms_key);
                                    $str = str_repeat("x", $factor_sms_key_length);
                                    $factor_sms_key = "". $str;
                                 ?>
                                    <input type="text" id="factor_sms_key" name="factor_sms_key" value="<?php if (!empty($factor_sms_key)) {echo $factor_sms_key;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>API Key Live</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="factor_sms_livekey_1" value="<?php if (isset($factor_sms_livekey_1)) echo $factor_sms_livekey_1; ?>">
                                <?php } else {
                                    $factor_sms_livekey_1_length = strlen($factor_sms_livekey_1);
                                    $str = str_repeat("x", $factor_sms_livekey_1_length);
                                    $factor_sms_livekey_1 = "". $str;
                                 ?>
                                    <input type="text" id="factor_sms_livekey_1" name="factor_sms_livekey_1" value="<?php if (!empty($factor_sms_livekey_1)) {echo $factor_sms_livekey_1;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>API Secret Key</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="factor_sms_livekey_1" value="<?php if (isset($factor_sms_livekey_1)) echo $factor_sms_livekey_1; ?>">
                                <?php } else {
                                    $factor_sms_livekey_1_length = strlen($factor_sms_livekey_1);
                                    $str = str_repeat("x", $factor_sms_livekey_1_length);
                                    $factor_sms_livekey_1 = "". $str;
                                 ?>
                                    <input type="text" id="factor_sms_livekey_1" name="factor_sms_livekey_1" value="<?php if (!empty($factor_sms_livekey_1)) {echo $factor_sms_livekey_1;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Sender ID</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="factor_sms_sender_id_1" value="<?php if (isset($factor_sms_sender_id_1)) echo $factor_sms_sender_id_1; ?>">
                                <?php } else {
                                    $factor_sms_sender_id_1_length = strlen($factor_sms_sender_id_1);
                                    $str = str_repeat("x", $factor_sms_sender_id_1_length);
                                    $factor_sms_sender_id_1 = "". $str;
                                 ?>
                                    <input type="text" id="factor_sms_sender_id_1" name="factor_sms_sender_id_1" value="<?php if (!empty($factor_sms_sender_id_1)) {echo $factor_sms_sender_id_1;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="mt-4">
                                    <?php if ($user_role == 1) { ?>
                                        <button name="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>
                                    <?php } ?>

                                </div>
                            </div>
                                
                            <!-- Twilio -->
                            <div id="twilio_div">
                                <div class="row align-items-center mb-4">
                                    <div class="col">
                                         <h4 class="mb-0">Twilio</h4>
                                    </div>
                                    <div class="col-auto">
                                        <div class="status-toggle">
                                            <input type="checkbox" name="sms_option" class="check sms_option" id="twilio" value="Twilio" <?php if($sms_option == 'Twilio') { echo 'checked'; } ?>>
                                            <label for="twilio" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Sid</label>
                                    <input type="text" class="form-control" name="twilio_sms_key" value="<?php if (isset($twilio_sms_key)) echo $twilio_sms_key; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Token</label> 
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="twilio_sms_key" value="<?php if (isset($twilio_sms_key)) echo $twilio_sms_key; ?>">
                                <?php } else {
                                    $twilio_sms_key_length = strlen($twilio_sms_key);
                                    $str = str_repeat("x", $twilio_sms_key_length);
                                    $twilio_sms_key = "". $str;
                                 ?>
                                    <input type="text" id="twilio_sms_key" name="twilio_sms_key" value="<?php if (!empty($twilio_sms_key)) {echo $twilio_sms_key;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                <?php if ($this->session->userdata('role') == 1) { ?>
                                    <input type="text" class="form-control" name="twilio_sms_secret_key" value="<?php if (isset($twilio_sms_secret_key)) echo $twilio_sms_secret_key; ?>">
                                <?php } else {
                                    $twilio_sms_secret_key_length = strlen($twilio_sms_secret_key);
                                    $str = str_repeat("x", $twilio_sms_secret_key_length);
                                    $twilio_sms_secret_key = "". $str;
                                 ?>
                                    <input type="text" id="twilio_sms_secret_key" name="twilio_sms_secret_key" value="<?php if (!empty($twilio_sms_secret_key)) {echo $twilio_sms_secret_key;} ?>" class="form-control">
                                <?php } ?>
                                </div>
                                <div class="mt-4">
                                    <?php if ($user_role == 1) { ?>
                                        <button name="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>
                                    <?php } ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </form>
    </div>
</div>	