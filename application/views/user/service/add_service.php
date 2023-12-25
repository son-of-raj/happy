<?php
$type = $this->session->userdata('usertype');
if ($type == 'user') {
    $user_currency = get_user_currency();
} else if ($type == 'provider') {
    $user_currency = get_provider_currency();
} else if ($type == 'freelancer') {
    $user_currency = get_provider_currency();
}
$user_currency_code = $user_currency['user_currency_code'];

$get_details = $this->db->select('token, category, subcategory')->where('id', $this->session->userdata('id'))->get('providers')->row_array();
$protoken = $get_details['token'];
$category = $this->service->readCategory();
$subcategory = $this->service->readSubcategory();
$subsubcategory = $this->service->readSubSubcategory();

$shop_details = $this->db->select('id, shop_name, shop_code, shop_location, shop_latitude, shop_longitude')->where('status', 1)->where('provider_id', $this->session->userdata('id'))->order_by('id', 'DESC')->get('shops')->result_array();

$btntxt = (!empty($user_language[$user_selected]['lg_Submit'])) ? $user_language[$user_selected]['lg_Submit'] : $default_language['en']['lg_Submit'];
$usrbtntxt = (!empty($user_language[$user_selected]['lg_Send_To_User'])) ? $user_language[$user_selected]['lg_Send_To_User'] : $default_language['en']['lg_Send_To_User'];

?>
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col">
                        <div class="breadcrumb-title">
                            <h2><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></h2>
                        </div>
                    </div>
                    <div class="col-auto float-end ms-auto breadcrumb-menu">
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo (!empty($user_language[$user_selected]['lg_home'])) ? $user_language[$user_selected]['lg_home'] : $default_language['en']['lg_home']; ?></a></li>

                                <li class="breadcrumb-item"><a href="<?php echo base_url() . "my-services"; ?>"><?php echo (!empty($user_language[$user_selected]['lg_Service'])) ? $user_language[$user_selected]['lg_Service'] : $default_language['en']['lg_Service']; ?></a></li>

                                <li class="breadcrumb-item active" aria-current="page"><?php echo (!empty($user_language[$user_selected]['lg_Add_Service'])) ? $user_language[$user_selected]['lg_Add_Service'] : $default_language['en']['lg_Add_Service']; ?></li>
                            </ol>
                        </nav>
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

                <form method="post" enctype="multipart/form-data" autocomplete="off" id="add_service">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input class="form-control" type="hidden" name="currency_code" value="<?php echo $user_currency_code; ?>">

                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Category'])) ? $user_language[$user_selected]['lg_Service_Category'] : $default_language['en']['lg_Service_Category']; ?></h3>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select" title="Category" name="category" id="service_category" required>
                                        <option>Select Category</option>
                                        <?php foreach ($category as $cat) { ?>
                                            <option value="<?php echo $cat['id'] ?>" <?php
                                                                                        if ($cat['id'] == $get_details['category']) { ?> selected="selected" <?php }   ?>><?php echo $cat['category_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Sub_Category'])) ? $user_language[$user_selected]['lg_Sub_Category'] : $default_language['en']['lg_Sub_Category']; ?></label>
                                    <select class="form-control select" title="Sub Category" name="subcategory" id="service_subcategory">
                                        <?php foreach ($subcategory as $sub_category) {
                                            if ($get_details['category'] == $sub_category['category']) { ?>
                                                <option value="<?php echo $sub_category['id'] ?>" <?php
                                                                                                    if ($sub_category['id'] == $get_details['subcategory']) { ?> selected="selected" <?php }   ?>><?php echo $sub_category['subcategory_name'] ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Information'])) ? $user_language[$user_selected]['lg_Service_Information'] : $default_language['en']['lg_Service_Information']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Name'])) ? $user_language[$user_selected]['lg_Service_Name'] : $default_language['en']['lg_Service_Name']; ?> <span class="text-danger">*</span></label>
                                    <input type="hidden" class="form-control" id="map_key" value="<?php echo $map_key ?>">
                                    <input class="form-control" type="text" name="service_title" id="service_title" required>
                                </div>
                            </div>

                            <div class="col-lg-12 d-none">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Location'])) ? $user_language[$user_selected]['lg_Service_Location'] : $default_language['en']['lg_Service_Location']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_location" id="service_location" required>
                                    <input type="hidden" name="service_latitude" id="service_latitude">
                                    <input type="hidden" name="service_longitude" id="service_longitude">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php
                                    $shoptxt = (!empty($user_language[$user_selected]['lg_Shop'])) ? $user_language[$user_selected]['lg_Shop'] : $default_language['en']['lg_Shop'];
                                    ?>
                                    <label><?php echo $shoptxt; ?> <span class="text-danger">*</span></label>
                                    <select class="form-control select services_shop_id" title="<?php echo $shoptxt; ?>" name="shop_id" id="shop_id">
                                        <option value="">Select Shop</option>
                                        <?php foreach ($shop_details as $shop) { ?>
                                            <option value="<?php echo $shop['id'] ?>" data-subtext="<?php echo (!empty($shop['shop_code'])) ? $shop['shop_code'] : ''; ?>" data-location="<?php echo $shop['shop_location'] ?>" data-latitude="<?php echo $shop['shop_latitude'] ?>" data-longitude="<?php echo $shop['shop_longitude'] ?>"><?php echo $shop['shop_name'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?php
                                    $stafftxt = (!empty($user_language[$user_selected]['lg_Staff'])) ? $user_language[$user_selected]['lg_Staff'] : $default_language['en']['lg_Staff'];
                                    ?>
                                    <label>
                                        Manager
                                        <!-- <span class="text-danger">*</span> -->
                                        <a href="<?php echo base_url() ?>add-staff" class="badge badge-secondary"><i class="fas fa-plus me-2"></i>Add Manager</a>
                                    </label>
                                    <select class="form-control select" title="<?php echo $stafftxt; ?>" name="staff_id[]" id="staff_id" multiple>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Amount_VAT'])) ? $user_language[$user_selected]['lg_Service_Amount_VAT'] : $default_language['en']['lg_Service_Amount_VAT']; ?> <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="service_amount" id="service_amount" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Booking Amount <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="booking_amount" id="booking_amount" required>
                                </div>
                            </div>

                            <!-- <div class="col-lg-6">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Service_Duration'])) ? $user_language[$user_selected]['lg_Service_Duration'] : $default_language['en']['lg_Service_Duration']; ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="duration" id="duration" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2"><?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?></span>
                                        </div>
                                        <input type="hidden" class="form-control" name="duration_in" id="duration_in" value="min(s)">
                                    </div>

                                </div>
                            </div> -->
                        </div>
                    </div>
                    <?php if (settingValue('service_offered_showhide') == 1) { ?>
                        <!-- Additional Service Content -->
                        <div class="service-fields mb-3" id="addiservice_div">
                            <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Additional_Services'])) ? $user_language[$user_selected]['lg_Additional_Services'] : $default_language['en']['lg_Additional_Services']; ?></h3>

                            <div class="additional-info">
                                <div class="row form-row additional-cont-label d-none">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label><?php echo (!empty($user_language[$user_selected]['lg_Name'])) ? $user_language[$user_selected]['lg_Name'] : $default_language['en']['lg_Name']; ?> </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><?php echo (!empty($user_language[$user_selected]['lg_lg_Amount'])) ? $user_language[$user_selected]['lg_Amount'] : $default_language['en']['lg_Amount']; ?> </label>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label><?php echo (!empty($user_language[$user_selected]['lg_Duration'])) ? $user_language[$user_selected]['lg_Duration'] : $default_language['en']['lg_Duration']; ?> </label>
                                            <input type="hidden" id="durintxt" value="<?php echo (!empty($user_language[$user_selected]['lg_mins'])) ? $user_language[$user_selected]['lg_mins'] : $default_language['en']['lg_mins']; ?>" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="add-more-additional form-group">
                                <a href="javascript:void(0);" class="add-additional"><i class="fas fa-plus-circle"></i> <?php echo (!empty($user_language[$user_selected]['lg_Add_More_Additional_Services'])) ? $user_language[$user_selected]['lg_Add_More_Additional_Services'] : $default_language['en']['lg_Add_More_Additional_Services']; ?></a>
                            </div>
                        </div>
                        <!-- Additional Service Content Ends -->
                    <?php  }  ?>

                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Details_Information'])) ? $user_language[$user_selected]['lg_Details_Information'] : $default_language['en']['lg_Details_Information']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label><?php echo (!empty($user_language[$user_selected]['lg_Description'])) ? $user_language[$user_selected]['lg_Description'] : $default_language['en']['lg_Description']; ?> <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="about" id="ck_editor_textarea_id4"></textarea>
                                    <?php echo display_ckeditor($ckeditor_editor4);  ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_Gallery'])) ? $user_language[$user_selected]['lg_Service_Gallery'] : $default_language['en']['lg_Service_Gallery']; ?></h3>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="service-upload">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span><?php echo (!empty($user_language[$user_selected]['lg_Upload_Image'])) ? $user_language[$user_selected]['lg_Upload_Image'] : $default_language['en']['lg_Upload_Image']; ?> *</span>
                                    <input type="file" name="images[]" id="images" multiple accept="image/jpeg, image/png, image/gif,">
                                </div>
                                <div id="uploadPreview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="service-fields mb-3">
                        <h3 class="heading-2"><?php echo (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For']; ?></h3>
                        <div class="row">
                            <div class="col-lg-3">
                                <label> <?php echo (!empty($user_language[$user_selected]['lg_Service_For'])) ? $user_language[$user_selected]['lg_Service_For'] : $default_language['en']['lg_Service_For']; ?></label>
                                <select class="form-control select" id="service_for" name="service_for">
                                    <option value="1">Shop</option>
                                    <option value="2">User</option>
                                </select>
                            </div>
                            <div class="col-lg-4 d-none" id="divChatUserId">
                                <input type="hidden" class="txtBtn" name="txt_btn" value="<?php echo $btntxt . "__" . $usrbtntxt; ?>" />
                                <?php $sql = "SELECT id,name FROM users WHERE token IN (SELECT DISTINCT(sender_token) FROM chat_table WHERE status = 1 and receiver_token = '" . $protoken . "' UNION SELECT DISTINCT(receiver_token) FROM chat_table WHERE status = 1  and sender_token = '" . $protoken . "')";
                                $usr = $this->db->query($sql)->result_array();
                                ?>
                                <label> <?php echo (!empty($user_language[$user_selected]['lg_User_Details'])) ? $user_language[$user_selected]['lg_User_Details'] : $default_language['en']['lg_User_Details']; ?></label>
                                <select class="form-control select" title="User List" id="chat_userid" name="chatuserid" disabled required>
                                    <?php foreach ($usr as $uval) { ?> <option value="<?php echo $uval['id'] ?>"><?php echo $uval['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="form_submit" value="submit"><?php echo $btntxt; ?></button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>