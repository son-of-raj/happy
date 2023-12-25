<div class="page-wrapper">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <h3 class="page-title">App Keywords</h3>
                </div>
                <div class="col-auto text-right">
                    <a href="<?php echo base_url().'add-app-keyword/'.$this->uri->segment(2); ?>" class="btn btn-white add-button"><i class="fas fa-plus"></i></a>
                </div>
                <div class="text-right mb-3">
                        <a href="<?php echo $base_url; ?>languages" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-center mb-0 language_table" id="language_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Page Title</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $i = 0;
                                  foreach ($list as $page) {
                                    $i++;
                                    
                                    ?>
                                  <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <div class="service-desc">
                                            
                                            <h2><?php echo $page['page_title']; ?></h2>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="<?php echo base_url().'app-page-list/'.$page['page_key'].'/'.$this->uri->segment(2); ?>" class="btn btn-sm bg-success-light mr-2">
                                        <i class="far fa-edit mr-1"></i> Edit</a>
                                    </td>
                                  </tr>
                                <?php }  ?>
                                </tbody>
                            </table>
                            
                            
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    function update_multi_lang()
    {
        
        
        $("#form_id").submit();
    }

</script>



