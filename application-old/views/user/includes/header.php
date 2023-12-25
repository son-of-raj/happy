<!DOCTYPE html>
<html>
    <?php
   $query = $this->db->query("select * from system_settings WHERE status = 1");
    $result = $query->result_array();
    $this->website_name = '';
    $this->website_logo_front = 'assets/img/logo.png';
    $fav = base_url() . 'assets/img/favicon.png';
    if (!empty($result)) {
        foreach ($result as $data) {
            if ($data['key'] == 'website_name') {
                $this->website_name = $data['value'];
            }
            if ($data['key'] == 'favicon') {
                $favicon = $data['value'];
            }
            if ($data['key'] == 'logo_front') {
                $this->website_logo_front = $data['value'];
            }
            if($data['key'] == 'meta_title'){
                $this->meta_title =  $data['value'];
            }
            if($data['key'] == 'meta_desc'){
                $this->meta_description =  $data['value'];
            }
            if($data['key'] == 'meta_keyword'){
                $this->meta_keywords =  $data['value'];
            }
        }
    }
    if (!empty($favicon)) {
        $fav = base_url() . 'uploads/logo/' . $favicon;
    }
    $lang = (!empty($this->session->userdata('lang'))) ? $this->session->userdata('lang') : 'en';

    $ColorList = $this->db->get('theme_color_change')->result_array();

    $Orgcolor = $ColorList[0]['status'];
    $Bluecolor = $ColorList[1]['status'];
    $Redcolor = $ColorList[2]['status'];
    $Greencolor = $ColorList[3]['status'];
    $Defcolor = $ColorList[4]['status'];
    ?>

    <head>
       <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $this->meta_title;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="<?php echo $this->meta_description; ?>">
        <meta name="keywords" content="<?php echo $this->meta_keywords; ?>">
		
        <meta name="author" content="Dreamguy's Technologies">
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $fav; ?>">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/datatables.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/fontawesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/feather.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/cropper.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/avatar.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/owlcarousel/owl.carousel.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/owlcarousel/owl.theme.default.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/intlTelInput.css"> 
        
        <?php if ($module == 'home' || $module == 'services') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.css">
        <?php } ?>

        <?php if ($module == 'service' || $module == 'shop' || $module == 'branch' || $module == 'appointment' || $module == 'products') { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css">
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/tagsinput.css">
        <?php } ?>    

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/toaster/toastr.min.css">

        <?php if (!empty($Defcolor) && $Defcolor == 1 || !empty($Orgcolor) && $Orgcolor == 1 || !empty($Bluecolor) && $Bluecolor == 1 || !empty($Redcolor) && $Redcolor == 1 || !empty($Greencolor) && $Greencolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <?php } ?>
        
        <?php /* if (!empty($Orgcolor) && $Orgcolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style_org.css">
        <?php } else if (!empty($Bluecolor) && $Bluecolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style_blue.css">
        <?php } else if (!empty($Redcolor) && $Redcolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style_red.css">
        <?php } else if (!empty($Greencolor) && $Greencolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style_green.css">
        <?php } else if (!empty($Defcolor) && $Defcolor == 1 || !empty($Orgcolor) && $Orgcolor == 1 || !empty($Bluecolor) && $Bluecolor == 1 || !empty($Redcolor) && $Redcolor == 1 || !empty($Greencolor) && $Greencolor == 1) { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <?php } */ ?>

        <?php if ($this->uri->segment(1) == "book-service" || $this->uri->segment(1) == "book-appointment" || $this->uri->segment(1) == "edit-appointment") { ?>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.css">
        <?php } ?>
		<?php if($this->uri->segment(1)=="add-staff" || $this->uri->segment(1)=="edit-staff" || $this->uri->segment(1)=="shop-preview"){ ?>
		    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/jquery-ui/jquery-ui.min.css">
        <?php } ?>

        <script src="<?php echo $base_url; ?>assets/js/jquery-3.6.0.min.js"></script>
        
			<script src="https://checkout.stripe.com/checkout.js"></script>
			<script src="https://js.stripe.com/v3/"></script>
		
		<?php if($this->uri->segment(1)=="provider-subscription" || $this->uri->segment(1)=="shop" || $this->uri->segment(1)=="my-shop-inactive" || $this->uri->segment(1)=="service-checkout" || $this->uri->segment(1)=="checkout" || $this->uri->segment(1) == "order-payment"){ ?>
			<!-- Moyasar Styles -->
			<link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.2.0/moyasar.css">
			<!-- Moyasar Scripts -->
			<script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
			<script src="https://cdn.moyasar.com/mpf/1.2.0/moyasar.js"></script>
		<?php } ?>
				
    </head>