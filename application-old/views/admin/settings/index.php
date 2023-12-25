<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-12">
                    <h3 class="page-title">General Settings</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <ul class="nav nav-tabs menu-tabs">
            <li class="nav-item active">
                <a class="nav-link" href="<?php echo base_url() . 'admin/settings'; ?>">General Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/emailsettings'; ?>">Email Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/moyaser-payment-gateway'; ?>">Payment Gateway</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/sms-settings'; ?>">SMS Gateway</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() . 'admin/theme-color'; ?>">Theme Color Change</a>
            </li>
        </ul>



        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-4 settings-tab">
                <div class="card">
                    <div class="card-body">
                        <div class="nav flex-column">
                            <a class="nav-link active" data-bs-toggle="tab"href="#general">General</a>
                            <a class="nav-link" data-bs-toggle="tab" href="#push_notification">Push Notification</a>
                            <a class="nav-link mb-0" data-bs-toggle="tab" href="#seo">SEO</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8 col-md-8">

                <div class="card">
                    <div class="card-body p-0">
                        <form accept-charset="utf-8" id="admin_settings" action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['hash']; ?>">
                            <div class="tab-content pt-0">

                                <!-- General Settings -->
                                <div id="general" class="tab-pane active">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h4 class="card-title">General Settings</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Website Name</label>
                                                <input type="text" required="" class="form-control" id="website_name" name="website_name" placeholder="Dreamguy's Technologies" value="<?php if (isset($website_name)) echo $website_name; ?>" pattern="^(?:[A-Za-z]+)(?:[A-Za-z0-9 _]*)$">
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Details</label>
                                                <input type="text" class="form-control" id="contact_details" name="contact_details" value="<?php if (isset($contact_details)) echo $contact_details; ?>" required="" >
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php if (isset($mobile_number)) echo $mobile_number; ?>" required="">
                                            </div>
                                            <div class="form-group">
                                                <label>Currency</label>
                                                <?php
                                                $currency_option = (!empty($currency_option)) ? $currency_option : 'USD';
                                                $currencies = $this->db->get('currency')->result_array();
                                                    ?>
                                                    <select class="form-control" name="currency_option" id="currency_option" required>
                                                        <?php foreach ($currencies as $crows) { ?>
                                                            <option value="<?php echo $crows['currency_code']; ?>" <?php if ($crows['currency_code'] == $currency_option) echo 'selected'; ?>><?php echo $crows['currency_name']; ?> (<?php echo $crows['currency_code']; ?>)
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                            </div> 

                                            <div class="form-group">
                                                <label>Website Logo</label>
                                                <div class="uploader"><input type="file" id="site_logo" multiple="true"  class="form-control" name="site_logo" placeholder="Select file"></div>
                                                <p class="form-text text-muted small mb-0">Recommended image size is <b>150px x 150px</b></p>
                                                <div id="img_upload_error" class="text-danger"  ><b>Please upload valid image file.</b></div>
                                                <?php if (!empty($logo_front)) { ?><img src="<?php echo base_url() . $logo_front ?>" class="site-logo"><?php } ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Favicon</label>
                                                <div class="uploader"><input type="file"  multiple="true"  class="form-control" id="favicon" name="favicon" placeholder="Select file"></div>
                                                <p class="form-text text-muted small mb-0">Recommended image size is <b>16px x 16px</b> or <b>32px x 32px</b></p>
                                                <p class="form-text text-muted small mb-1">Accepted formats: only png and ico</p>
                                                <div id="img_upload_errors" class="text-danger" >Please upload valid image file.</div>
                                                <?php if (!empty($favicon)) { ?><img src="<?php echo base_url() . 'uploads/logo/' . $favicon ?>" class="fav-icon"><?php } ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Commission Percentage</label>
                                                <input type="text" required="" class="form-control" id="commission" name="commission" placeholder="10" value="<?php if (isset($commission)) echo $commission; ?>" pattern="^[a-zA-Z0-9@]+$">
                                            </div>
											<div class="form-group">
                                                <label>VAT Percentage</label>
                                                <input type="text" required="" class="form-control numbersOnly" id="vat" name="vat" placeholder="10" value="<?php if (isset($vat)) echo $vat; ?>" pattern="^[1-9][0-9@]+$">
                                            </div>
											
											<div class="form-group">
                                                <label>Google Map API Key &nbsp; <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" class="btn btn-info btn-sm">How to create google map API key ?</a></label>
                                                <input type="text" required="" class="form-control" id="map_key" name="map_key" value="<?php if (isset($map_key)) echo $map_key; ?>" >
												
											</div>
											
											 <label>Login Settings(one time setup) :</label>
											<div class="form-group">
													<label>Login Type</label>
												<input type="radio" required=""  class="login_type" name="login_type"  value="mobile" <?php echo (!empty($login_type)&&$login_type=="mobile")?"checked":"";?>>
												Mobile No &nbsp;	
												<input type="radio" required="" class="login_type" name="login_type"  value="email" <?php echo (!empty($login_type)&&$login_type=="email")?"checked":"";?>>
												Email ID
											</div>
											<div class="form-group" id="otpbydiv">
													<label>OTP By</label>
												<input type="radio" required="" <?php echo $fdis?> class="otp_by" name="otp_by"  value="sms" <?php echo (!empty($otp_by)&&$otp_by=="sms")?"checked":"";?>>
												SMS &nbsp;	
												<input type="radio" required="" <?php echo $fdis?> class="otp_by" name="otp_by"  value="email" <?php echo (!empty($otp_by)&&$otp_by=="email")?"checked":"";?>>
												Mail
											</div>											
                                        </div>
                                    </div>
                                </div>
                                <!-- /General Settings -->

                                <!-- Push Notification -->
                                <div id="push_notification" class="tab-pane">
                                    <div class="card mb-0">
                                        <div class="card-header">
                                            <h4 class="card-title">Push Notification &nbsp; <a href="https://firebase.google.com/docs/android/setup" target="_blank" class="btn btn-info btn-sm">How to create firebase setup?</a></h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Firebase Server Key</label>
                                                <input type="text" class="form-control" id="firebase_server_key" name="firebase_server_key" value="<?php if (isset($firebase_server_key)) echo $firebase_server_key; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>APNS Key</label>
                                                <input type="text" class="form-control" id="apns_server_key" name="apns_server_key" value="<?php if (isset($apns_server_key)) echo $apns_server_key; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Push Notification -->


                                <!-- SEO -->
                                <div id="seo" class="tab-pane pt-0">
                                    <div class="card mb-0 shadow-none">
                                        <div class="card-header">
                                            <h4 class="card-title">SEO</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Meta title</label>
                                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?php if (isset($meta_title)) echo $meta_title; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta keywords</label>
                                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="<?php if (isset($meta_keywords)) echo $meta_keywords; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Meta description</label>
                                                <textarea class="form-control" rows="6" id="meta_description" name="meta_description"><?php if (isset($meta_description)) echo $meta_description; ?></textarea>
                                            </div>
											<div class="form-group">
                                                <label>Meta Viewport</label>
                                                <input type="text" class="form-control" id="meta_viewport" name="meta_viewport" value="<?php if (isset($meta_viewport)) echo $meta_viewport; ?>">
                                            </div>
											
											
											
											<div class="form-group">
                                                <label>Meta Robots</label>
                                                <input type="text" class="form-control" id="meta_robots" name="meta_robots" value="<?php if (isset($meta_robots)) echo $meta_robots; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Meta Googlebot</label>
                                                <input type="text" class="form-control" id="meta_googlebot" name="meta_googlebot" value="<?php if (isset($meta_googlebot)) echo $meta_googlebot; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Meta Language</label>
                                                <input type="text" class="form-control" id="meta_language" name="meta_language" value="<?php if (isset($meta_language)) echo $meta_language; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Geo Position</label>
                                                <input type="text" class="form-control" id="meta_geo_position" name="meta_geo_position"  value="<?php if (isset($meta_geo_position)) echo $meta_geo_position; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Geo Placename</label>
                                                <input type="text" class="form-control" id="meta_geo_placename" name="meta_geo_placename"  value="<?php if (isset($meta_geo_placename)) echo $meta_geo_placename; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Geo Region</label>
                                                <input type="text" class="form-control" id="meta_geo_region" name="meta_geo_region"  value="<?php if (isset($meta_geo_region)) echo $meta_geo_region; ?>">
                                            </div>
											
											<div class="form-group">
                                                <label>Social Meta Tags</label><br>
												
												<ul class="nav nav-tabs menu-tabs">
													<li class="nav-item active">
														<a class="nav-link" href="<?php echo base_url() . 'admin/fb-social-media'; ?>">Facebook</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" href="<?php echo base_url() . 'admin/googleplus-social-media'; ?>">Google +</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" href="<?php echo base_url() . 'admin/twit-social-media'; ?>">Twitter</a>
													</li>
												</ul>
											<br>
                            <div class="meta_fb">
                                <div class="form-group">
                                    <label >og:url</label>
                                    <input type="text"  name="fb_og_url" value="<?php if (isset($fb_og_url)) echo $fb_og_url; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label >og:type</label>
                                    <input type="text" id="fb_og_type" name="fb_og_type" value="<?php if (isset($fb_og_type)) echo $fb_og_type; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>og:title</label>
                                    <input type="text" id="fb_og_title" name="fb_og_title" value="<?php if (isset($fb_og_title)) echo $fb_og_title; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:description</label>
                                    <input type="text" id="fb_og_description" name="fb_og_description" value="<?php if (isset($fb_og_description)) echo $fb_og_description; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:image url</label>
                                    <input type="text" id="fb_og_img" name="fb_og_img" value="<?php if (isset($fb_og_img)) echo $fb_og_img; ?>" class="form-control">
                                </div>
                            </div>
                            <div class="meta_google">
                               <div class="form-group">
                                    <label >og:url</label>
                                    <input type="text"  name="google_og_url" value="<?php if (isset($google_og_url)) echo $google_og_url; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label >og:type</label>
                                    <input type="text" id="google_og_type" name="google_og_type" value="<?php if (isset($google_og_type)) echo $google_og_type; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>og:title</label>
                                    <input type="text" id="google_og_title" name="google_og_title" value="<?php if (isset($google_og_title)) echo $google_og_title; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:description</label>
                                    <input type="text" id="google_og_description" name="google_og_description" value="<?php if (isset($google_og_description)) echo $google_og_description; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:image url</label>
                                    <input type="text" id="google_og_img" name="google_og_img" value="<?php if (isset($google_og_img)) echo $google_og_img; ?>" class="form-control">
                                </div>
                            </div>
							
							<div class="meta_twitter">
                               <div class="form-group">
                                    <label >og:url</label>
                                    <input type="text"  name="twitter_og_url" value="<?php if (isset($twitter_og_url)) echo $twitter_og_url; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label >og:type</label>
                                    <input type="text" id="twitter_og_type" name="twitter_og_type" value="<?php if (isset($twitter_og_type)) echo $twitter_og_type; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>og:title</label>
                                    <input type="text" id="twitter_og_title" name="twitter_og_title" value="<?php if (isset($twitter_og_title)) echo $twitter_og_title; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:description</label>
                                    <input type="text" id="twitter_og_description" name="twitter_og_description" value="<?php if (isset($twitter_og_description)) echo $twitter_og_description; ?>" class="form-control">
                                </div>
								 <div class="form-group">
                                    <label>og:image url</label>
                                    <input type="text" id="twitter_og_img" name="twitter_og_img" value="<?php if (isset($twitter_og_img)) echo $twitter_og_img; ?>" class="form-control">
                                </div>
                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /SEO -->
                                <div class="card-body pt-0">
                                    <?php if ($user_role == 1) { ?>
                                        <button name="form_submit" type="submit" class="btn btn-primary" value="true">Save Changes</button>
                                    <?php } ?>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>