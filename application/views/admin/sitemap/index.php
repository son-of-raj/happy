<?php 
$admin_settings = $language_content;

$query = $this->db->query("select * from language WHERE status = '1'");
$lang_test = $query->result();
?>
<div class="page-wrapper">
    <div class="content container-fluid">
    
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-12">
                    <h3 class="page-title"><?php echo(!empty($admin_settings['lg_admin_sitemap']))?($admin_settings['lg_admin_sitemap']) : 'Sitemap';  ?></h3>
                </div>
            </div>
        </div>
        <!-- /Page Header -->
        <div class="row">
            <div class=" col-lg-6 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-heads">
                            <h4 class="card-title"><?php echo(!empty($admin_settings['lg_admin_sitemap']))?($admin_settings['lg_admin_sitemap']) : 'Sitemap';  ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="user_csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
                        <div class="form-group">
                            <label><?php echo(!empty($admin_settings['lg_admin_sitemap_url']))?($admin_settings['lg_admin_sitemap_url']) : 'Sitemap Url';  ?></label>
                            <input type="text" class="form-control" name="sitemap_url" id="sitemap_url" placeholder="Enter Website Name" value="<?php echo base_url().'sitemap.xml'; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label><?php echo(!empty($admin_settings['lg_admin_sitemap_file']))?($admin_settings['lg_admin_sitemap_file']) : 'Sitemap File';  ?></label>
                            <a target="_blank" href="<?php echo base_url().'sitemap.xml'; ?>" class="btn btn-success btn-sm" title="View Sitemap"><i class="fa fa-link" aria-hidden="true"></i> View Sitemap File</a>
                        </div>
                        <div class="form-group">
                            <label><?php echo(!empty($admin_settings['lg_admin_rebuild_sitemap']))?($admin_settings['lg_admin_rebuild_sitemap']) : 'Rebuild Your Sitemap';  ?></label>
                            <a href="#" class="btn btn-primary btn-sm" title="Rebuild Your Sitemap" id="rebuild_sitemap"><i class="fa fa-link" aria-hidden="true"></i> Rebuild Your Sitemap</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>