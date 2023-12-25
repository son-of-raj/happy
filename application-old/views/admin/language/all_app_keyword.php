<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <div class="col-xl-8 offset-xl-2">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Add App Keywords</h3>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <?php $page_key = $this->uri->segment(2); ?>
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo $base_url; ?>insertApp" id="language_app_keywords" method="post" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                            <input  type="hidden" id="page_key" name="page_key" value="<?php echo $page_key;?>" class="form-control" >
                            <input class="form-control" type="hidden"  name="lang_key" id="lang_key" value="<?php echo $this->uri->segment(3); ?>">
                            <div class="form-group">
                                <label>Filed Name</label>
                                <input class="form-control" type="text"  name="filed_name" id="category_name">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" type="text"  name="name" id="category_name">
                            </div>
                            <div class="form-group">
                                <label>Placeholder</label>
                                <input class="form-control" type="text"  name="placeholder" id="category_name">
                            </div>
                            <div class="form-group">
                                <label>Validation 1</label>
                                <input class="form-control" type="text"  name="valide_1" id="category_name">
                            </div>
                            <div class="form-group">
                                <label>Validation 2</label>
                                <input class="form-control" type="text"  name="valide_2" id="category_name">
                            </div>
                            <div class="form-group">
                                <label>Validation 3</label>
                                <input class="form-control" type="text"  name="valide_3" id="category_name">
                            </div>
                            
                            

                            <div class="mt-4">
                                <?php if ($user_role == 1) { ?>
                                    <button class="btn btn-primary " name="form_submit" value="submit" type="submit">Add Language</button>
                                <?php } ?>

                               <a href="<?php echo base_url().'app-page-list/'.$this->uri->segment(2).'/'.$this->uri->segment(3); ?>" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

