<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">Email Settings </h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class="col-xl-8 col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <form id="form_emailsetting" action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            <?php
                            if (!isset($mail_config)) {
                                $mail_config = "phpmail";
                            }
                            ?>
                            <input type="hidden" id="mail_config" value="<?php echo  (isset($mail_config)) ? $mail_config : "phpmail"; ?>">
                            <input type="radio" name="mail_config" value="phpmail" <?php echo  ($mail_config == "phpmail") ? "checked" : '' ?> id="phpmail">
                            <label for="male">PHP Mail</label>
                            <input type="radio" name="mail_config" value="smtp" <?php echo  ($mail_config == "smtp") ? "checked" : '' ?> id="smtpmail">
                            <label for="female">SMTP</label><br>
                            <div class="phpMail">
                                <div class="form-group">
                                    <label >Email From Address</label>
                                    <input type="email" id="website_name" name="email_address" value="<?php if (isset($email_address)) echo $email_address; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label >Email Password</label>
                                    <input type="password" id="email_password" name="email_password" value="<?php if (isset($email_password)) echo $email_password; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Emails From Name</label>
                                    <input type="text" id="email_tittle" name="email_tittle" value="<?php if (isset($email_tittle)) echo $email_tittle; ?>" class="form-control only_alphabets">
                                </div>
                            </div>
                            <div class="smtpMail">
                                <div class="form-group">
                                    <label >Email From Address</label>
                                    <input type="email"  name="smtp_email_address" value="<?php if (isset($smtp_email_address)) echo $smtp_email_address; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label >Email Password</label>
                                    <input type="password" id="smtp_email_password" name="smtp_email_password" value="<?php if (isset($smtp_email_password)) echo $smtp_email_password; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Email Host</label>
                                    <input type="text" id="smtp_email_host" name="smtp_email_host" value="<?php if (isset($smtp_email_host)) echo $smtp_email_host; ?>" class="form-control ">
                                </div>
                                <div class="form-group">
                                    <label>Email Port</label>
                                    <input type="text" id="smtp_email_port" name="smtp_email_port" value="<?php if (isset($smtp_email_port)) echo $smtp_email_port; ?>" class="form-control ">
                                </div>
                            </div>
                            <div class="mt-4">
                                <?php if ($user_role == 1) { ?>
                                    <button name="form_submit" type="submit" class="btn btn-primary center-block" value="true">Save Changes</button>
<?php } ?>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	
