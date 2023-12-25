<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="page-title">Frontend Settings</h3>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                
                <div class="row">
                    <div class=" col-lg-12 col-sm-12 col-12">
                        <div class="card">
                            <form id="header_settings" action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                            <input type="hidden" name="header_id" id="header_id" class="form-control" value="<?php echo $frontend_data->id; ?>">
                            <input type="hidden" name="form_name" id="form_name" class="form-control" value="headers">
                                <div class="card-header">
                                    <div class="card-heads">
                                        <h4 class="card-title">Header Setting</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-5 col-12">
                                                <div class="card-heads mb-3">
                                                    <h4 class="card-title f-14 m-0">Language </h4>
                                                    <div class="col-auto">
                                                        <div class="status-toggle">
                                                            <input id="language" class="check" type="checkbox" name="language_option" id="language_option" <?php if($frontend_data->language_option == 1) { echo 'checked'; } ?>>
                                                            <label for="language" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-heads mb-3">
                                                    <h4 class="card-title f-14">Currency </h4>
                                                    <div class="col-auto">
                                                        <div class="status-toggle">
                                                            <input id="currency" class="check" type="checkbox"  name="currency_option" id="currency_option" <?php if($frontend_data->currency_option == 1) { echo 'checked'; } ?>>
                                                            <label for="currency" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-heads mb-3">
                                                    <h4 class="card-title f-14">Stikcy header</h4>
                                                    <div class="col-auto">
                                                        <div class="status-toggle">
                                                            <input id="header" class="check" type="checkbox" name="sticky_header" <?php if($frontend_data->sticky_header == 1) { echo 'checked'; } ?>>
                                                            <label for="header" class="checktoggle">checkbox</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if($this->session->userdata('role') == 1) { ?>
                                        <div class="form-groupbtn">
                                            <button name="form_submit" type="submit" class="btn btn-primary me-2" value="true">Update</button>
                                        </div>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                         <form id="header_menu_settings" action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                            <input type="hidden" name="header_id" id="header_id" class="form-control" value="<?php echo $frontend_data->id; ?>">
                            <input type="hidden" name="form_name" id="form_name" class="form-control" value="header_menus">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-heads">
                                            <h4 class="card-title">Header Menu</h4>
                                            <div class="col-auto">
                                                <div class="status-toggle">
                                                    <input id="menu" class="check" type="checkbox" name="menus_option" id="menus_option" <?php if($frontend_data->header_menu_option == 1) { echo 'checked'; } ?>>
                                                    <label for="menu" class="checktoggle">checkbox</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="card-heads">
                                                <h4 class="card-title f-14">Nav Menus</h4>
                                                <div class="col-auto">
                                                    <a href="javascript:void(0);" class="btn btn-primary" id="reset_menu">Reset</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="settings-form">
                                            <?php if(!empty($frontend_data->header_menus) && $frontend_data->header_menus != 'null') {
                                                    $headerMenus = json_decode($frontend_data->header_menus);
                                                    foreach($headerMenus as $menus) { ?>
                                                <div class="form-group links-cont" id="menu_<?php echo $menus->id; ?>">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-3 col-12">
                                                            <input type="text" class="form-control" name="menu_title[]" id="menu_title" value="<?php echo $menus->label; ?>">
                                                        </div>
                                                        <div class="col-lg-8 col-12">
                                                            <input type="text" class="form-control" name="menu_links[]" id="menu_links" value="<?php echo $menus->link; ?>">
                                                        </div>
                                                        <div class="col-lg-1 col-12">
                                                            <a href="#" class="btn btn-sm bg-danger-light  delete_menus" data-id="<?php echo $menus->id; ?>">
                                                                <i class="far fa-trash-alt "></i> 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } }  else { ?>
                                                <div class="form-group links-cont">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-3 col-12">
                                                            <input type="text" class="form-control" name="menu_title[]" id="menu_title" placeholder="Title" value="Categories">
                                                        </div>
                                                        <div class="col-lg-8 col-12">
                                                            <input type="text" class="form-control" name="menu_links[]" id="menu_links" placeholder="Links" value="<?php echo base_url();?>categories" readonly>
                                                        </div>
                                                        <div class="col-lg-1 col-12">
                                                            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
                                                                <i class="far fa-trash-alt "></i> 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group links-cont">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-3 col-12">
                                                            <input type="text" class="form-control" name="menu_title[]" id="menu_title" placeholder="Title" value="About Us">
                                                        </div>
                                                        <div class="col-lg-8 col-12">
                                                            <input type="text" class="form-control" name="menu_links[]" id="menu_links" placeholder="Links" value="<?php echo base_url();?>about-us" readonly>
                                                        </div>
                                                        <div class="col-lg-1 col-12">
                                                            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
                                                                <i class="far fa-trash-alt "></i> 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group links-cont">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-3 col-12">
                                                            <input type="text" class="form-control" name="menu_title[]" id="menu_title" placeholder="Title" value="Contact">
                                                        </div>
                                                        <div class="col-lg-8 col-12">
                                                            <input type="text" class="form-control" name="menu_links[]" id="menu_links" placeholder="Links" value="<?php echo base_url();?>contact" readonly>
                                                        </div>
                                                        <div class="col-lg-1 col-12">
                                                            <a href="#" class="btn btn-sm bg-danger-light  delete_menu">
                                                                <i class="far fa-trash-alt "></i> 
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <a class="btn  btn-success addlinks"><i class="fa fa-plus me-2"></i>Add New</a>
                                        </div>
                                        <?php if($this->session->userdata('role') == 1) { ?>
                                            <div class="form-groupbtn">
                                                <button name="form_submit" type="submit" class="btn btn-primary me-2" value="true">Update</button>
                                            </div>
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