<?php echo'<?xml version="1.0" encoding="UTF-8" ?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc><?php echo base_url();?></loc>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>

    </url>
 
 <?php //echo 'mapp<pre>'; print_r($lists); exit;?>
    <!-- Sitemap -->
    <?php foreach($lists as $list) { ?>
    <url>
        <loc><?php echo base_url().$list->id ?></loc>
        <priority>0.5</priority>
        <changefreq>daily</changefreq>
    </url>
    <?php } ?>
 
 
</urlset>