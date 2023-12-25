<div class="page-wrapper">
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-12">
                    <h3 class="page-title">Theme Color Change</h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-lg-6 col-sm-12 col-12">
                <?php if ($this->session->flashdata('error_message1')) { ?>
                    <div class="alert alert-danger text-center" id="flash_error_message"><?php echo $this->session->flashdata('error_message1'); ?></div>
                    <?php
                    $this->session->unset_userdata('error_message1');
                }
                ?>
                <?php if ($this->session->flashdata('success_message1')) { ?>
                    <div class="alert alert-success text-center" id="flash_succ_message"><?php echo $this->session->flashdata('success_message1'); ?></div>
                    <?php
                    $this->session->unset_userdata('success_message1');
                }
                ?>
                <div class="card">
                    <div class="card-body">

                            <form action='<?php echo $base_url; ?>Change-color' method="POST" name='DatabaseForm' novalidate>
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                                    <?php
                                    foreach ($Colorlist as $list) {

                                        $status = $list['status'];
                                        if ($status == 1) {
                                            $checked = 'checked';
                                        } else {
                                            $checked = '';
                                        }
                                        ?>
                                            
                                            <div class="form-group mb-3">
                                                <div class="custom-control custom-radios custom-control-inline color-buttons">
                                                    <input class="custom-control-input" id="<?php echo $list['id']; ?>" type="radio" name="color" value="<?php echo $list['id']; ?>" <?php echo $checked; ?>>
                                                    <label class="custom-control-label d-flex align-item-center colors" for="<?php echo $list['id']; ?>"><span  class="color-set clr-<?php echo strtolower($list['color_name']); ?>"></span><?php echo $list['color_name']; ?></label>
                                                </div>
                                            </div>

                                    <?php } ?>

                                <?php if($user_role == 1) { ?>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary" id="submitForm">Color Change</button>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
