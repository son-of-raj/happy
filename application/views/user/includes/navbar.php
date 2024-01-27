<?php
$type = $this->session->userdata('usertype');
$userId = $this->session->userdata('id');
$default_language = default_language();
$active_language = active_language();
$header_settings = $this->db->get('header_settings')->row();
$google_analytics_showhide = $this->db->get_where('system_settings', array('key' => 'analytics_showhide'))->row()->value;
$google_analytics_code = $this->db->get_where('system_settings', array('key' => 'google_analytics'))->row()->value;



if ($this->session->userdata('user_select_language') == '') {

    $lang = $default_language['language_value'];
} else {
    $lang = $this->session->userdata('user_select_language');
}
?>
<?php
$default_language_select = default_language();

if ($this->session->userdata('user_select_language') == '') {

    if ($default_language_select['tag'] == 'ltr' || $default_language_select['tag'] == '') {
    } elseif ($default_language_select['tag'] == 'rtl') {
        echo '<link href="' . base_url() . 'assets/css/bootstrap-rtl.min.css" media="screen" rel="stylesheet" type="text/css" />';
        echo '<link href="' . base_url() . 'assets/css/app-rtl.css" rel="stylesheet" />';
    }
} else {
    if ($this->session->userdata('tag') == 'ltr' || $this->session->userdata('tag') == '') {
    } elseif ($this->session->userdata('tag') == 'rtl') {

        echo '<link href="' . base_url() . 'assets/css/bootstrap-rtl.min.css" media="screen" rel="stylesheet" type="text/css" />';
        echo '<link href="' . base_url() . 'assets/css/app-rtl.css" rel="stylesheet" />';
    }
}
//Cart
$CI = &get_instance();
$CI->load->model('products_model');
if ($type == 'user') {
    $userdet = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();
}
?>

<body>
    <?php if ($google_analytics_showhide == 1 && $google_analytics_code != '') { ?>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', '<?php echo $google_analytics_code; ?>', 'auto');
            ga('send', 'pageview');
        </script>
    <?php } ?>

    <div class="main-wrapper">

        <header class="header sticktop">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </a>
                    <a href="<?php echo base_url(); ?>" class="navbar-brand logo">
                        <?php if (!empty($this->website_logo_front)) { ?>
                            <img src="<?php echo base_url() . $this->website_logo_front; ?>" class="img-fluid" alt="Logo">
                        <?php } else { ?>
                            <img src="<?php echo base_url(); ?>assets/img/logo-icon.png" class="img-fluid" alt="Logo">
                        <?php } ?>
                    </a>
                    <a href="<?php echo base_url(); ?>" class="navbar-brand logo-small">
                        <img src="<?php echo (settingValue('header_icon')) ? base_url() . settingValue('header_icon') : base_url() . 'assets/img/logo-icon.png'; ?>" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="<?php echo base_url(); ?>" class="menu-logo">
                            <img src="<?php echo base_url() . $this->website_logo_front; ?>" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <ul class="main-nav">
                        <li class="has-submenu">
                            <a href="<?php echo base_url(); ?>all-services">Shops<i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="<?php echo base_url(); ?>all-services">Shops & Services</a></li>
                                <li><a href="<?php echo base_url(); ?>products">Products</a></li>
                            </ul>
                        </li>
                        <?php if ($header_settings->header_menu_option == 1 && !empty($header_settings->header_menus)) {
                            $menus = json_decode($header_settings->header_menus);
                            foreach ($menus as $menu) {
                                if ($menu->label == 'Categories' && $menu->id == 1 && $menu->label != '' && $menu->link != '') { ?>
                                    <li class="has-submenu">
                                        <?php
                                        $this->db->select('*');
                                        $this->db->from('categories');
                                        $this->db->where('status', 1);
                                        $this->db->order_by('id', 'DESC');
                                        $result = $this->db->get()->result_array();

                                        ?>
                                        <a href="<?php echo $menu->link; ?>"><?php echo $menu->label; ?> <i class="fas fa-chevron-down"></i></a>
                                        <ul class="submenu">
                                            <?php foreach ($result as $res) { ?>
                                                <li><a href="<?php echo base_url(); ?>search/<?php echo str_replace(' ', '-', strtolower($res['category_slug'])); ?>"><?php echo ucfirst($res['category_name']); ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <?php } else {
                                    if ($menu->label != '' && $menu->link != '') {
                                    ?>
                                        <li><a href="<?php echo $menu->link; ?>"><?php echo $menu->label; ?></a></li>
                            <?php }
                                }
                            } ?>

                            <?php
                            $lang_val = ($this->session->userdata('user_select_language')) ? $this->session->userdata('user_select_language') : 'en';
                            $this->db->where('language_value', $lang_val);
                            $lang_name = $this->db->get('language')->row_array();
                            $this->db->where('lang_id', $lang_name['id']);
                            $this->db->where('delete_status', '1');
                            $this->db->where('location', '1');
                            $this->db->where('visibility', '1');
                            $topmenu_name = $this->db->get('pages_list')->result_array();
                            foreach ($topmenu_name as $top_menu) {
                            ?>
                                <li><a href="<?php echo $base_url; ?>pages-details/<?php echo $top_menu['slug']; ?>"><?php echo $top_menu['title']; ?></a></li>
                            <?php } ?>

                        <?php } else {

                            $this->db->select('*');
                            $this->db->from('categories');
                            $this->db->where('status', 1);
                            $this->db->order_by('id', 'DESC');
                            $result = $this->db->get()->result_array();
                        ?>
                            <li class="has-submenu">
                                <a href="<?php echo base_url(); ?>all-categories"><?php echo (!empty($user_language[$user_selected]['lg_category_name'])) ? $user_language[$user_selected]['lg_category_name'] : $default_language['en']['lg_category_name']; ?> <i class="fas fa-chevron-down"></i></a>
                                <ul class="submenu">
                                    <?php foreach ($result as $res) { ?>
                                        <li><a href="<?php echo base_url(); ?>search/<?php echo str_replace(' ', '-', strtolower($res['category_slug'])); ?>"><?php echo ucfirst($res['category_name']); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>

                            <li><a href="<?php echo base_url(); ?>about-us"><?php echo (!empty($user_language[$user_selected]['lg_about'])) ? $user_language[$user_selected]['lg_about'] : $default_language['en']['lg_about']; ?></a></li>
                            <li><a href="<?php echo base_url(); ?>contact"><?php echo (!empty($user_language[$user_selected]['lg_contact'])) ? $user_language[$user_selected]['lg_contact'] : $default_language['en']['lg_contact']; ?></a></li>


                            <?php
                            $lang_val = ($this->session->userdata('user_select_language')) ? $this->session->userdata('user_select_language') : 'en';
                            $this->db->where('language_value', $lang_val);
                            $lang_name = $this->db->get('language')->row_array();
                            $this->db->where('lang_id', $lang_name['id']);
                            $this->db->where('delete_status', '1');
                            $this->db->where('location', '1');
                            $this->db->where('visibility', '1');
                            $topmenu_name = $this->db->get('pages_list')->result_array();
                            foreach ($topmenu_name as $top_menu) {
                            ?>
                                <li><a href="<?php echo $base_url; ?>pages-details/<?php echo $top_menu['slug']; ?>"><?php echo $top_menu['title']; ?></a></li>
                        <?php }
                        } ?>
                        <?php if ($this->session->userdata('id') == '') { ?>
                            <li><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-wizard"><?php echo (!empty($user_language[$user_selected]['lg_become_prof'])) ? $user_language[$user_selected]['lg_become_prof'] : $default_language['en']['lg_become_prof']; ?></a></li>

                            <?php
                            ?>
                            <li><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modal-wizard1"><?php echo (!empty($user_language[$user_selected]['lg_become_user'])) ? $user_language[$user_selected]['lg_become_user'] : $default_language['en']['lg_become_user']; ?></a></li>

                        <?php } ?>
                        <?php if ($header_settings->language_option == 1) { ?>
                            <li class="has-submenu">
                                <a href="javascript:;"><?php echo $lang; ?><i class="fas fa-chevron-down"></i></a>
                                <ul class="submenu lang-blk">
                                    <?php foreach ($active_language as $active) { ?>
                                        <li>

                                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" id="csrf_lang" />

                                            <a href="javascript:;" id="change_language" lang_tag="<?php echo $active['tag']; ?>" lang="<?php echo $active['language_value']; ?>" <?php
                                                                                                                                                                                    if ($active['language_value'] == $lang) {
                                                                                                                                                                                        echo "selected";
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>>
                                                <?php echo ($active['language']); ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php
                        if ($userId != '') {
                            $get_currency = get_currency();
                            if ($type == 'user') {
                                $user_currency = get_user_currency();
                            } else if ($type == 'provider') {
                                $user_currency = get_provider_currency();
                            }
                            $user_currency_code = $user_currency['user_currency_code'];

                            if ($header_settings->currency_option == 1 && $this->session->userdata('usertype') != 'admin') { ?>
                                <li class="has-submenu">
                                    <span class="currency-blk">
                                        <select class="form-control-sm custom-select" id="user_currency">
                                            <?php foreach ($get_currency as $row) { ?>
                                                <option value="<?php echo $row['currency_code']; ?>" <?php echo ($row['currency_code'] == $user_currency_code) ? 'selected' : ''; ?>><?php echo $row['currency_code']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </li>

                        <?php }
                        } ?>
                        <?php if ($usertype == 'user') {
                            $CI->load->model('home_model');
                            $cities = $CI->home_model->user_city_list($userId);
                            $selected_city = $this->session->userdata('selected_city');
                            if (!empty($cities)) {
                        ?>
                                <li class="has-submenu">
                                    <span class="currency-blk language-select">
                                        <select class="form-control-sm" onchange="user_select_city(this.value)">
                                            <option value="">Select City</option>
                                            <?php foreach ($cities as $c) { ?>
                                                <option value="<?php echo $c['city_name'] ?>" <?php echo ($selected_city == $c['city_name'] ? 'selected' : '') ?>><?php echo $c['city_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </li>
                        <?php }
                        } ?>
                        <?php
                        if (($this->session->userdata('id') != '') && ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer')) {


                            $get_details = $this->db->where('id', $this->session->userdata('id'))->get('providers')->row_array();
                            $get_availability = $this->db->where('provider_id', $this->session->userdata('id'))->get('business_hours')->row_array();
                            if (!empty($get_availability['availability'])) {
                                $check_avail = strlen($get_availability['availability']);
                            } else {
                                $check_avail = 2;
                            }

                            $getShop = $this->db->where('provider_id', $this->session->userdata('id'))->where('status', 1)->get('shops')->result_array();

                            $getStaff = $this->db->where('provider_id', $this->session->userdata('id'))->where('status', 1)->where('delete_status', 0)->get('employee_basic_details')->result_array();
                            $staffCount = count($getStaff);

                            $shpcls = 'get_pro_addshop';
                            if ($this->session->userdata('usertype') == 'freelancer') {
                                $staffCount = 1;
                            }

                            $get_subscriptions = $this->db->select('*')->from('subscription_details')->where('subscriber_id', $this->session->userdata('id'))->where('expiry_date_time >=', date('Y-m-d 00:00:59'))->get()->row_array();
                            if (!isset($get_subscriptions)) {
                                $get_subscriptions['id'] = '';
                            }
                            if (!empty($get_availability) && !empty($get_subscriptions['id']) && $check_avail > 5 && count($getShop) > 0 && $staffCount > 0) {
                        ?>
                                <li class="mobile-list d-none">
                                    <a href="<?php echo base_url(); ?>add-service"><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></a>
                                </li>
                            <?php
                            } elseif ($get_subscriptions['id'] == '') {
                                $stitle = (!empty($user_language[$user_selected]['lg_Please_Subscripe'])) ? $user_language[$user_selected]['lg_Please_Subscripe'] : $default_language['en']['lg_Please_Subscripe'];
                                $sstile = (!empty($user_language[$user_selected]['lg_Choose_Subscribe_Plan'])) ? $user_language[$user_selected]['lg_Choose_Subscribe_Plan'] : $default_language['en']['lg_Choose_Subscribe_Plan'];
                            ?>
                                <li class="mobile-list d-none">
                                    <span class="post-service-blk">
                                        <a href="javascript:;" class="get_pro_subscription" data-title="<?php echo $stitle ?>" data-subtitle="<?php echo $sstile ?>"><i class="fas fa-plus-circle me-1"></i> <?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></a>
                                    </span>
                                </li>
                            <?php
                            } elseif ($get_availability == '' || $get_availability['availability'] == '' || $check_avail < 5) {
                                $atitle = (!empty($user_language[$user_selected]['lg_Please_Select_Availability'])) ? $user_language[$user_selected]['lg_Please_Select_Availability'] : $default_language['en']['lg_Please_Select_Availability'];
                                $astile = (!empty($user_language[$user_selected]['lg_Choose_Availability'])) ? $user_language[$user_selected]['lg_Choose_Availability'] : $default_language['en']['lg_Choose_Availability'];
                            ?>
                                <li class="mobile-list d-none">
                                    <a href="javascript:;" class="get_pro_availabilty" data-title="<?php echo $atitle ?>" data-subtitle="<?php echo $astile ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                            <?php
                            } elseif (count($getShop) == 0) {
                                $shoptit = (!empty($user_language[$user_selected]['lg_Please_Add_Shop'])) ? $user_language[$user_selected]['lg_Please_Add_Shop'] : $default_language['en']['lg_Please_Add_Shop'];
                                $shoptxt = (!empty($user_language[$user_selected]['lg_Add_Shop_Details'])) ? $user_language[$user_selected]['lg_Add_Shop_Details'] : $default_language['en']['lg_Add_Shop_Details'];
                            ?>
                                <li class="mobile-list d-none">
                                    <a href="javascript:;" class="<?php echo $shpcls; ?>" data-title="<?php echo $shoptit ?>" data-subtitle="<?php echo $shoptxt ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                            <?php
                            } elseif ($staffCount == 0 && $this->session->userdata('usertype') == 'provider') {
                                $stftit = (!empty($user_language[$user_selected]['lg_Please_Add_Staff'])) ? $user_language[$user_selected]['lg_Please_Add_Staff'] : $default_language['en']['lg_Please_Add_Staff'];
                                $stftxt = (!empty($user_language[$user_selected]['lg_Add_Staff_Details'])) ? $user_language[$user_selected]['lg_Add_Staff_Details'] : $default_language['en']['lg_Add_Staff_Details'];
                            ?>
                                <li class="mobile-list d-none">
                                    <a href="javascript:;" class="get_pro_addstaff" data-title="<?php echo $stftit ?>" data-subtitle="<?php echo $stftxt ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>

                </div>
                <ul class="nav header-navbar-rht">

                    <?php if ($this->session->userdata('id') == '') { ?>
                        <li class="nav-item">
                            <a class="btn btn-signin" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#tab_login_modal"><?php echo (!empty($user_language[$user_selected]['lg_login'])) ? $user_language[$user_selected]['lg_login'] : $default_language['en']['lg_login']; ?></a>
                        </li>

                    <?php
                    } ?>
                    <?php
                    $wallet = 0;
                    $token = '';
                    if ($this->session->userdata('id') != '') {
                        if (!empty($token = $this->session->userdata('chat_token'))) {
                            $wallet_sql = $this->db->select('*')->from('wallet_table')->where('token', $this->session->userdata('chat_token'))->get()->row();
                            if (!empty($wallet_sql)) {
                                $wallet = $wallet_sql->wallet_amt;
                                $user_currency_code = '';
                                if (!empty($userId)) {

                                    $wallet = $wallet_sql->wallet_amt;
                                    if ($type == 'user') {
                                        $user_currency = get_user_currency();
                                    } else if ($type == 'provider') {
                                        $user_currency = get_provider_currency();
                                    } else if ($type == 'freelancer') {
                                        $user_currency = get_provider_currency();
                                    }
                                    $user_currency_code = $user_currency['user_currency_code'];

                                    $wallet = get_gigs_currency($wallet_sql->wallet_amt, $wallet_sql->currency_code, $user_currency_code);
                                } else {
                                    $user_currency_code = settings('currency');
                                    $wallet = $wallet_sql->wallet_amt;
                                }
                            }
                        }
                    }
                    ?>
                    <?php
                    if (($this->session->userdata('id') != '') && ($this->session->userdata('usertype') == 'user')) {
                    ?>
                        <li class="nav-item logged-item">
                            <a class="nav-link link-head" href="<?php echo base_url() ?>cart-list" role="button">
                                <i class="feather-shopping-cart"></i>
                                <span class="badge badge-pill cart_count">0</span>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    <?php
                    if (($this->session->userdata('id') != '') && ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer')) {

                        $get_details = $this->db->where('id', $this->session->userdata('id'))->get('providers')->row_array();
                        $get_availability = $this->db->where('provider_id', $this->session->userdata('id'))->get('business_hours')->row_array();
                        if (!empty($get_availability['availability'])) {
                            $check_avail = strlen($get_availability['availability']);
                        } else {
                            $check_avail = 2;
                        }

                        $getShop = $this->db->where('provider_id', $this->session->userdata('id'))->where('status', 1)->get('shops')->result_array();

                        $getStaff = $this->db->where('provider_id', $this->session->userdata('id'))->where('status', 1)->where('delete_status', 0)->get('employee_basic_details')->result_array();
                        $staffCount = count($getStaff);

                        $shpcls = 'get_pro_addshop';
                        if ($this->session->userdata('usertype') == 'freelancer') {
                            $staffCount = 1;
                        }

                        $get_subscriptions = $this->db->select('*')->from('subscription_details')->where('subscriber_id', $this->session->userdata('id'))->where('expiry_date_time >=', date('Y-m-d 00:00:59'))->get()->row_array();
                        if (!isset($get_subscriptions)) {
                            $get_subscriptions['id'] = '';
                        }
                        if (!empty($get_availability) && !empty($get_subscriptions['id']) && $check_avail > 5 && count($getShop) > 0 &&  $staffCount > 0 && $get_subscriptions['paid_status'] != 0) {
                            if ($get_details['commercial_verify'] == 2) {
                    ?>
                                <li class="nav-item desc-list">
                                    <a href="<?php echo base_url(); ?>add-service" class="nav-link header-login"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                            <?php
                            }
                        } elseif ($get_subscriptions['id'] != '' && $get_subscriptions['paid_status'] == 0) {
                            ?>
                            <li class="nav-item desc-list">
                                <a href="javascript:;" class="nav-link header-login get_admin_approval"><i class="fas fa-plus-circle mr-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                            </li>
                            <?php
                        } elseif ($get_subscriptions['id'] == '' && $get_subscriptions['paid_status'] == 0) {
                            $stitle = (!empty($user_language[$user_selected]['lg_Please_Subscripe'])) ? $user_language[$user_selected]['lg_Please_Subscripe'] : $default_language['en']['lg_Please_Subscripe'];
                            $sstile = (!empty($user_language[$user_selected]['lg_Choose_Subscribe_Plan'])) ? $user_language[$user_selected]['lg_Choose_Subscribe_Plan'] : $default_language['en']['lg_Choose_Subscribe_Plan'];
                            if ($get_details['commercial_verify'] == 2) {
                            ?>
                                <li class="nav-item desc-list">
                                    <a href="javascript:;" class="nav-link header-login get_pro_subscription" data-title="<?php echo $stitle ?>" data-subtitle="<?php echo $sstile ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                            <?php }
                        } elseif ($get_availability == '' || $get_availability['availability'] == '' || $check_avail < 5) {
                            $atitle = (!empty($user_language[$user_selected]['lg_Please_Select_Availability'])) ? $user_language[$user_selected]['lg_Please_Select_Availability'] : $default_language['en']['lg_Please_Select_Availability'];
                            $astile = (!empty($user_language[$user_selected]['lg_Choose_Availability'])) ? $user_language[$user_selected]['lg_Choose_Availability'] : $default_language['en']['lg_Choose_Availability'];
                            if ($get_details['commercial_verify'] == 2) {
                            ?>
                                <li class="nav-item desc-list">
                                    <a href="javascript:;" class="nav-link header-login get_pro_availabilty" data-title="<?php echo $atitle ?>" data-subtitle="<?php echo $astile ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                                </li>
                            <?php
                            }
                        } elseif (count($getShop) == 0) {
                            $shoptit = (!empty($user_language[$user_selected]['lg_Please_Add_Shop'])) ? $user_language[$user_selected]['lg_Please_Add_Shop'] : $default_language['en']['lg_Please_Add_Shop'];
                            $shoptxt = (!empty($user_language[$user_selected]['lg_Add_Shop_Details'])) ? $user_language[$user_selected]['lg_Add_Shop_Details'] : $default_language['en']['lg_Add_Shop_Details'];
                            ?>
                            <li class="nav-item desc-list">
                                <a href="javascript:;" class="nav-link header-login <?php echo $shpcls; ?>" data-title="<?php echo $shoptit ?>" data-subtitle="<?php echo $shoptxt ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                            </li>
                        <?php
                        } elseif ($staffCount == 0 && $this->session->userdata('usertype') == 'provider') {
                            $stftit = (!empty($user_language[$user_selected]['lg_Please_Add_Staff'])) ? $user_language[$user_selected]['lg_Please_Add_Staff'] : $default_language['en']['lg_Please_Add_Staff'];
                            $stftxt = (!empty($user_language[$user_selected]['lg_Add_Staff_Details'])) ? $user_language[$user_selected]['lg_Add_Staff_Details'] : $default_language['en']['lg_Add_Staff_Details'];
                        ?>
                            <li class="nav-item desc-list">
                                <a href="javascript:;" class="nav-link header-login get_pro_addstaff" data-title="<?php echo $stftit ?>" data-subtitle="<?php echo $stftxt ?>"><i class="fas fa-plus-circle me-1"></i> <span><?php echo (!empty($user_language[$user_selected]['lg_post_service'])) ? $user_language[$user_selected]['lg_post_service'] : $default_language['en']['lg_post_service']; ?></span></a>
                            </li>
                    <?php
                        }
                    }
                    ?>

                    <?php
                    if ($this->session->userdata('id')) {
                        if ($this->session->userdata('usertype') == 'user') {
                            $user_details = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();
                        } elseif ($this->session->userdata('usertype') == 'provider') {
                            $user_details = $this->db->where('id', $this->session->userdata('id'))->get('providers')->row_array();
                        } elseif ($this->session->userdata('usertype') == 'freelancer') {
                            $user_details = $this->db->where('id', $this->session->userdata('id'))->get('providers')->row_array();
                        } else {
                            $user_details = $this->db->where('user_id', $this->session->userdata('id'))->get('administrators')->row_array();
                        }
                    ?>
                        <?php if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') { ?>
                            <!-- Notifications -->
                            <li class="nav-item dropdown logged-item">
                                <?php
                                if (!empty($this->session->userdata('chat_token'))) {
                                    $ses_token = $this->session->userdata('chat_token');
                                } else {
                                    $ses_token = '';
                                }

                                if (!empty($ses_token)) {
                                    $ret = $this->db->select('*')->from('notification_table')->where('receiver', $ses_token)->where('status', 1)->order_by('notification_id', 'DESC')->get()->result_array();

                                    $notification = [];
                                    if (!empty($ret)) {
                                        foreach ($ret as $key => $value) {

                                            $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $value['sender'])->get()->row();
                                            $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $value['sender'])->get()->row();
                                            if (!empty($user_table)) {
                                                $user_info = $user_table;
                                            } else {
                                                $user_info = $provider_table;
                                            }

                                            $notification[$key]['name'] = !empty($user_info->name) ? $user_info->name : '';
                                            $notification[$key]['message'] = !empty($value['message']) ? $value['message'] : '';
                                            $notification[$key]['profile_img'] = !empty($user_info->profile_img) ? $user_info->profile_img : '';
                                            $notification[$key]['utc_date_time'] = !empty($value['utc_date_time']) ? $value['utc_date_time'] : '';
                                        }
                                    }
                                    $n_count = count($notification);
                                } else {
                                    $n_count = 0;
                                    $notification = [];
                                }

                                /* Notification Count */
                                if (!empty($n_count) && $n_count != 0) {
                                    $notify = "<span class='badge badge-pill'>" . $n_count . "</span>";
                                } else {
                                    $notify = "";
                                }
                                ?>

                                <a href="#" class="dropdown-toggle nav-link link-head" data-bs-toggle="dropdown">
                                    <i class="feather-bell noti-icon"></i> <?php echo  $notify; ?>
                                </a>

                                <div class="dropdown-menu notify-blk dropdown-menu-end notifications">
                                    <div class="topnav-dropdown-header">
                                        <span class="notification-title"><?php echo (!empty($user_language[$user_selected]['lg_Notifications'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?></span>
                                        <a href="javascript:void(0)" class="clear-noti noty_clear" data-token="<?php echo $this->session->userdata('chat_token'); ?>"><?php echo (!empty($user_language[$user_selected]['lg_clear_all'])) ? $user_language[$user_selected]['lg_clear_all'] : $default_language['en']['lg_clear_all']; ?> </a>
                                    </div>
                                    <div class="noti-content">
                                        <ul class="notification-list">
                                            <?php
                                            if (!empty($notification)) {
                                                foreach ($notification as $key => $notify) {
                                                    if (settingValue('time_format') == '12 Hours') {
                                                        $time = date('G:ia', strtotime($datef[1]));
                                                    } elseif (settingValue('time_format') == '24 Hours') {
                                                        $time = date('H:i:s', strtotime($datef[1]));
                                                    } else {
                                                        $time = date('G:ia', strtotime($datef[1]));
                                                    }

                                                    $datef = explode(' ', $notify["utc_date_time"]);
                                                    $date = date(settingValue('date_format'), strtotime($datef[0]));

                                                    $timeBase = $date . ' ' . $time;

                                                    $profile_img = 'assets/img/user.jpg';
                                                    if (!empty($notify['profile_img']) && file_exists($notify['profile_img'])) {
                                                        $profile_img = $notify['profile_img'];
                                                    }

                                            ?>
                                                    <li class="notification-message">
                                                        <a href="<?php echo  base_url(); ?>notification-list">
                                                            <div class="media d-flex">
                                                                <span class="avatar avatar-sm flex-shrink-0">
                                                                    <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo  base_url() . $profile_img; ?>">
                                                                </span>
                                                                <div class="media-body flex-grow-1">
                                                                    <p class="noti-details"> <span class="noti-title"><?php echo  ucfirst($notify['message']); ?></span></p>
                                                                    <p class="noti-time"><span class="notification-time"><?php

                                                                                                                            echo  $timeBase; ?></span></p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <li class="notification-message">
                                                    <p class="text-center text-danger mt-3"><?php echo (!empty($user_language[$user_selected]['lg_notification_empty'])) ? $user_language[$user_selected]['lg_notification_empty'] : $default_language['en']['lg_notification_empty']; ?></p>
                                                </li>
                                            <?php } ?>

                                        </ul>
                                    </div>
                                    <div class="topnav-dropdown-footer">
                                        <a href="<?php echo  base_url(); ?>notification-list"><?php echo (!empty($user_language[$user_selected]['lg_view_notification'])) ? $user_language[$user_selected]['lg_view_notification'] : $default_language['en']['lg_view_notification']; ?></a>
                                    </div>
                                </div>
                            </li>
                            <!-- /Notifications -->

                            <?php if (!empty($this->session->userdata('id'))) { ?>
                                <!-- chat -->
                                <?php
                                $chat_token = $this->session->userdata('chat_token');

                                if (!empty($chat_token)) {

                                    $chat_detail = $this->db->where('receiver_token', $chat_token)->where('read_status=', 0)->get('chat_table')->result_array();
                                }

                                $chatCount =  sizeof($chat_detail);
                                ?>
                                <li class="nav-item dropdown logged-item">

                                    <a href="#" class="dropdown-toggle nav-link link-head" data-bs-toggle="dropdown">
                                        <i class="feather-message-circle noti-icon"></i> <span class='badge badge-pill'><?php echo $chatCount ?></span>
                                    </a>

                                    <div class="dropdown-menu comments-blk dropdown-menu-end notifications">
                                        <div class="topnav-dropdown-header">
                                            <span class="notification-title"><?php echo (!empty($user_language[$user_selected]['lg_chats'])) ? $user_language[$user_selected]['lg_chats'] : $default_language['en']['lg_chats']; ?></span>
                                            <a href="javascript:void(0)" class="clear-noti chat_clear_all" data-token="<?php echo $this->session->userdata('chat_token'); ?>"> <?php echo (!empty($user_language[$user_selected]['lg_clear_all'])) ? $user_language[$user_selected]['lg_clear_all'] : $default_language['en']['lg_clear_all']; ?> </a>
                                        </div>

                                        <div class="noti-content">
                                            <ul class="chat-list notification-list">
                                                <?php
                                                if (count($chat_detail) > 0) {
                                                    $sender = '';
                                                    foreach ($chat_detail as $row) {

                                                        $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $row['sender_token'])->get()->row();
                                                        $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $row['sender_token'])->get()->row();
                                                        if (!empty($user_table)) {
                                                            $user_info = $user_table;
                                                        } else {
                                                            $user_info = $provider_table;
                                                        }

                                                        if (settingValue('time_format') == '12 Hours') {
                                                            $time = date('G:ia', strtotime($datef[1]));
                                                        } elseif (settingValue('time_format') == '24 Hours') {
                                                            $time = date('H:i:s', strtotime($datef[1]));
                                                        } else {
                                                            $time = date('G:ia', strtotime($datef[1]));
                                                        }
                                                        // print_r("chat_date".var_dump($row));
                                                        $datef = explode(' ', $row["utc_date_time"]);
                                                        $date = date(settingValue('date_format'), strtotime($datef[0]));
                                                        $timeBase = $date . ' ' . $time;
                                                        // print_r("timechat".$timeBase);
                                                        $profile_img = 'assets/img/user.jpg';
                                                        if (!empty($user_info->profile_img) && file_exists($user_info->profile_img)) {
                                                            $profile_img = $user_info->profile_img;
                                                        }
                                                ?>

                                                        <li class="notification-message">
                                                            <a href="<?php echo  base_url(); ?>user-chat">
                                                                <div class="media d-flex">


                                                                    <span class="avatar avatar-sm flex-shrink-0">
                                                                        <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo  base_url() . $profile_img; ?>">
                                                                    </span>
                                                                    <div class="media-body flex-grow-1">
                                                                        <p class="noti-details"> <span class="noti-title"><?php echo  $user_info->name . " send a message as " . $row['message']; ?></span></p>
                                                                        <p class="noti-time"><span class="notification-time"><?php

                                                                                                                                // if(settingValue('time_format') == '12 Hours') {
                                                                                                                                //     $time = date('G:ia', strtotime($datef[1]));
                                                                                                                                // } elseif(settingValue('time_format') == '24 Hours') {
                                                                                                                                //    $time = date('H:i:s', strtotime($datef[1]));
                                                                                                                                // } else {
                                                                                                                                //     $time = date('G:ia', strtotime($datef[1]));
                                                                                                                                // }
                                                                                                                                // // print_r("chat_date".var_dump($row));
                                                                                                                                // $datef = explode(' ', $row["utc_date_time"]);
                                                                                                                                // $date = date(settingValue('date_format'), strtotime($datef[0]));
                                                                                                                                // $timeBase = $date.' '.$time;







                                                                                                                                echo  $timeBase; ?></span></p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                }
                                                if (count($chat_detail) == 0) {
                                                    ?>

                                                    <li class="notification-message">
                                                        <p class="text-center text-danger mt-3"><?php echo (!empty($user_language[$user_selected]['lg_Chat_Empty'])) ? $user_language[$user_selected]['lg_Chat_Empty'] : $default_language['en']['lg_Chat_Empty']; ?></p>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                        <div class="topnav-dropdown-footer">
                                            <a href="<?php echo  base_url(); ?>user-chat"><?php echo (!empty($user_language[$user_selected]['lg_View_Chat'])) ? $user_language[$user_selected]['lg_View_Chat'] : $default_language['en']['lg_View_Chat']; ?></a>
                                        </div>
                                    </div>
                                </li>
                                <!-- /chat -->
                            <?php } ?>

                            <!-- User Menu -->
                            <li class="nav-item dropdown has-arrow logged-item user-menu">
                                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                                    <span class="user-img">
                                        <?php if ($user_details['profile_img'] != '' && file_exists($user_details['profile_img'])) { ?>
                                            <img src="<?php echo $base_url . $user_details['profile_img'] ?>" width="31" alt="">
                                        <?php } else { ?>
                                            <img src="<?php echo $base_url ?>assets/img/user.jpg" alt="">
                                        <?php } ?>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="user-header">
                                        <div class="avatar avatar-sm">
                                            <?php if ($user_details['profile_img'] != '' && file_exists($user_details['profile_img'])) { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url . $user_details['profile_img'] ?>" alt="">
                                            <?php } else { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url ?>assets/img/user.jpg" alt="">
                                            <?php } ?>
                                        </div>
                                        <div class="user-text">
                                            <h6><?php echo $user_details['name']; ?></h6>
                                            <p class="text-muted mb-0"><?php echo (!empty($user_language[$user_selected]['lg_provider'])) ? $user_language[$user_selected]['lg_provider'] : $default_language['en']['lg_provider']; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                    $verify_status = $user_details['commercial_verify'];
                                    if ($verify_status == 1) {
                                    ?>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>provider-settings"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url() ?>provider-subscription"><?php echo (!empty($user_language[$user_selected]['lg_Subscription'])) ? $user_language[$user_selected]['lg_Subscription'] : $default_language['en']['lg_Subscription']; ?></a>
                                    <?php } else { ?>

                                        <a class="dropdown-item" href="<?php echo base_url(); ?>provider-dashboard"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>my-services"><?php echo (!empty($user_language[$user_selected]['lg_My_Services'])) ? $user_language[$user_selected]['lg_My_Services'] : $default_language['en']['lg_My_Services']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>provider-bookings"><?php echo (!empty($user_language[$user_selected]['lg_Booking_List'])) ? $user_language[$user_selected]['lg_Booking_List'] : $default_language['en']['lg_Booking_List']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>offline-bookings">Offline Booking</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>provider-orders"><?php echo (!empty($user_language[$user_selected]['lg_orders_list'])) ? $user_language[$user_selected]['lg_orders_list'] : $default_language['en']['lg_orders_list']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>provider-settings"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url() ?>provider-subscription"><?php echo (!empty($user_language[$user_selected]['lg_Subscription'])) ? $user_language[$user_selected]['lg_Subscription'] : $default_language['en']['lg_Subscription']; ?></a>
                                        <a class="dropdown-item" href="<?php echo base_url() ?>provider-availability"><?php echo (!empty($user_language[$user_selected]['lg_Availability'])) ? $user_language[$user_selected]['lg_Availability'] : $default_language['en']['lg_Availability']; ?></a>
                                    <?php } ?>
                                    <?php
                                    $query = $this->db->query("select * from system_settings WHERE status = 1");
                                    $result = $query->result_array();

                                    $login_type = '';
                                    foreach ($result as $res) {

                                        if ($res['key'] == 'login_type') {
                                            $login_type = $res['value'];
                                        }

                                        if ($res['key'] == 'login_type') {
                                            $login_type = $res['value'];
                                        }
                                    }
                                    if ($login_type == 'email') {
                                    ?>
                                        <a class="dropdown-item" href="<?php echo base_url() ?>provider-change-password"><?php echo (!empty($user_language[$user_selected]['lg_Change_Password'])) ? $user_language[$user_selected]['lg_Change_Password'] : $default_language['en']['lg_Change_Password']; ?></a>

                                    <?php } ?>
                                    <a class="dropdown-item" href="<?php echo base_url() ?>user-chat"><?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url() ?>logout"><?php echo (!empty($user_language[$user_selected]['lg_Logout'])) ? $user_language[$user_selected]['lg_Logout'] : $default_language['en']['lg_Logout']; ?></a>
                                </div>
                            </li>
                            <!-- /User Menu -->

                        <?php } elseif ($this->session->userdata('usertype') == 'user') { ?>
                            <!-- Notifications -->
                            <li class="nav-item dropdown logged-item">
                                <?php
                                if (!empty($this->session->userdata('chat_token'))) {
                                    $ses_token = $this->session->userdata('chat_token');
                                } else {
                                    $ses_token = '';
                                }
                                if (!empty($ses_token)) {
                                    $ret = $this->db->select('*')->from('notification_table')->where('receiver', $ses_token)->where('status', 1)->order_by('notification_id', 'DESC')->get()->result_array();
                                    $notification = [];

                                    if (!empty($ret)) {

                                        foreach ($ret as $key => $value) {
                                            $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $value['sender'])->get()->row();
                                            $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $value['sender'])->get()->row();
                                            if (!empty($user_table)) {
                                                $user_info = $user_table;
                                            } else {
                                                $user_info = $provider_table;
                                            }
                                            $notification[$key]['name'] = !empty($user_info->name) ? $user_info->name : '';
                                            $notification[$key]['message'] = !empty($value['message']) ? $value['message'] : '';
                                            $notification[$key]['profile_img'] = !empty($user_info->profile_img) ? $user_info->profile_img : '';
                                            $notification[$key]['utc_date_time'] = !empty($value['utc_date_time']) ? $value['utc_date_time'] : '';
                                        }
                                    }
                                    $n_count = count($notification);
                                } else {
                                    $n_count = 0;
                                    $notification = [];
                                }

                                /* notification Count */
                                if (!empty($n_count) && $n_count != 0) {
                                    $notify = "<span class='badge badge-pill'>" . $n_count . "</span>";
                                } else {
                                    $notify = "";
                                }
                                ?>

                                <a href="#" class="dropdown-toggle nav-link link-head" data-bs-toggle="dropdown">
                                    <i class="feather-bell noti-icon"></i> <?php echo  $notify; ?>
                                </a>

                                <div class="dropdown-menu notify-blk dropdown-menu-end notifications">
                                    <div class="topnav-dropdown-header">
                                        <span class="notification-title"><?php echo (!empty($user_language[$user_selected]['lg_Notifications'])) ? $user_language[$user_selected]['lg_Notifications'] : $default_language['en']['lg_Notifications']; ?></span>
                                        <a href="javascript:void(0)" class="clear-noti noty_clear" data-token="<?php echo $this->session->userdata('chat_token'); ?>"> <?php echo (!empty($user_language[$user_selected]['lg_clear_all'])) ? $user_language[$user_selected]['lg_clear_all'] : $default_language['en']['lg_clear_all']; ?> </a>
                                    </div>
                                    <div class="noti-content">
                                        <ul class="notification-list">
                                            <?php
                                            if (!empty($notification)) {
                                                foreach ($notification as $key => $notify) {
                                                    if (settingValue('time_format') == '12 Hours') {
                                                        $time = date('G:ia', strtotime($datef[1]));
                                                    } elseif (settingValue('time_format') == '24 Hours') {
                                                        $time = date('H:i:s', strtotime($datef[1]));
                                                    } else {
                                                        $time = date('G:ia', strtotime($datef[1]));
                                                    }
                                                    $datef = explode(' ', $notify["utc_date_time"]);
                                                    $date = date(settingValue('date_format'), strtotime($datef[0]));
                                                    $timeBase = $date . ' ' . $time;

                                                    $profile_img = 'assets/img/user.jpg';
                                                    if (!empty($notify['profile_img']) && file_exists($notify['profile_img'])) {
                                                        $profile_img = $notify['profile_img'];
                                                    }
                                            ?>

                                                    <li class="notification-message">
                                                        <a href="<?php echo  base_url(); ?>notification-list">
                                                            <div class="media d-flex">
                                                                <span class="avatar avatar-sm flex-shrink-0">
                                                                    <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo  base_url() . $profile_img; ?>">
                                                                </span>
                                                                <div class="media-body flex-grow-1">
                                                                    <p class="noti-details"> <span class="noti-title"><?php echo  ucfirst($notify['message']); ?></span></p>
                                                                    <p class="noti-time"><span class="notification-time"><?php echo  $timeBase; ?></span></p>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <li class="notification-message">
                                                    <p class="text-center text-danger mt-3"><?php echo (!empty($user_language[$user_selected]['lg_notification_empty'])) ? $user_language[$user_selected]['lg_notification_empty'] : $default_language['en']['lg_notification_empty']; ?></p>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="topnav-dropdown-footer">
                                        <a href="<?php echo  base_url(); ?>notification-list"><?php echo (!empty($user_language[$user_selected]['lg_view_notification'])) ? $user_language[$user_selected]['lg_view_notification'] : $default_language['en']['lg_view_notification']; ?></a>
                                    </div>
                                </div>
                            </li>
                            <!-- /Notifications -->

                            <?php if (!empty($this->session->userdata('id'))) { ?>
                                <!-- chat -->
                                <?php
                                $chat_token = $this->session->userdata('chat_token');
                                if (!empty($chat_token)) {
                                    $chat_detail = $this->db->where('receiver_token', $chat_token)->where('read_status=', 0)->get('chat_table')->result_array();
                                }
                                $chatCount =  sizeof($chat_detail);
                                ?>
                                <li class="nav-item dropdown logged-item">

                                    <a href="#" class="dropdown-toggle nav-link link-head" data-bs-toggle="dropdown">
                                        <i class="feather-message-circle noti-icon"></i>
                                        <span class='badge badge-pill'><?php echo $chatCount ?></span>
                                    </a>

                                    <div class="dropdown-menu comments-blk dropdown-menu-end notifications">
                                        <div class="topnav-dropdown-header">
                                            <span class="notification-title"><?php echo (!empty($user_language[$user_selected]['lg_chats'])) ? $user_language[$user_selected]['lg_chats'] : $default_language['en']['lg_chats']; ?></span>
                                            <a href="javascript:void(0)" class="clear-noti chat_clear_all" data-token="<?php echo $this->session->userdata('chat_token'); ?>"> <?php echo (!empty($user_language[$user_selected]['lg_clear_all'])) ? $user_language[$user_selected]['lg_clear_all'] : $default_language['en']['lg_clear_all']; ?> </a>
                                        </div>

                                        <div class="noti-content">
                                            <ul class="chat-list notification-list">
                                                <?php

                                                if (!empty($chat_detail) && count($chat_detail) > 0) {
                                                    $sender = '';
                                                    foreach ($chat_detail as $row) {

                                                        $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $row['sender_token'])->get()->row();
                                                        $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $row['sender_token'])->get()->row();
                                                        if (!empty($user_table)) {
                                                            $user_info = $user_table;
                                                        } else {
                                                            $user_info = $provider_table;
                                                        }

                                                        if (settingValue('time_format') == '12 Hours') {
                                                            $time = date('G:ia', strtotime($datef[1]));
                                                        } elseif (settingValue('time_format') == '24 Hours') {
                                                            $time = date('H:i:s', strtotime($datef[1]));
                                                        } else {
                                                            $time = date('G:ia', strtotime($datef[1]));
                                                        }
                                                        $datef = explode(' ', $row["utc_date_time"]);
                                                        $date = date(settingValue('date_format'), strtotime($datef[0]));
                                                        $timeBase = $date . ' ' . $time;
                                                        $profile_img = 'assets/img/user.jpg';
                                                        if (!empty($user_info->profile_img) && file_exists($user_info->profile_img)) {
                                                            $profile_img = $user_info->profile_img;
                                                        }

                                                ?>

                                                        <li class="notification-message">
                                                            <a href="<?php echo  base_url(); ?>user-chat">
                                                                <div class="media d-flex">
                                                                    <span class="avatar avatar-sm flex-shrink-0">
                                                                        <img class="avatar-img rounded-circle" alt="User Image" src="<?php echo  base_url() . $profile_img; ?>">
                                                                    </span>
                                                                    <div class="media-body flex-grow-1">
                                                                        <p class="noti-details"> <span class="noti-title"><?php echo  $user_info->name . " send a message as " . $row['message']; ?></span></p>
                                                                        <p class="noti-time"><span class="notification-time"><?php echo  $timeBase; ?></span></p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    <?php
                                                    }
                                                }
                                                if (!empty($chat_detail) && count($chat_detail) > 0) {
                                                    ?>

                                                    <li class="notification-message">
                                                        <p class="text-center text-danger mt-3"><?php echo (!empty($user_language[$user_selected]['lg_Chat_Empty'])) ? $user_language[$user_selected]['lg_Chat_Empty'] : $default_language['en']['lg_Chat_Empty']; ?></p>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                        <div class="topnav-dropdown-footer">
                                            <a href="<?php echo  base_url(); ?>user-chat"><?php echo (!empty($user_language[$user_selected]['lg_View_Chat'])) ? $user_language[$user_selected]['lg_View_Chat'] : $default_language['en']['lg_View_Chat']; ?></a>
                                        </div>
                                    </div>
                                </li>
                                <!-- /chat -->
                            <?php } ?>
                            <li class="nav-item dropdown has-arrow logged-item user-menu">
                                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                                    <span class="user-img">
                                        <?php if ($user_details['profile_img'] != '' && file_exists($user_details['profile_img'])) { ?>
                                            <img src="<?php echo $base_url . $user_details['profile_img'] ?>" alt="" width="31">
                                        <?php } else { ?>
                                            <img src="<?php echo $base_url ?>assets/img/user.jpg" alt="" width="31">
                                        <?php } ?>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="user-header">
                                        <div class="avatar avatar-sm">
                                            <?php if ($user_details['profile_img'] != '' && file_exists($user_details['profile_img'])) { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url . $user_details['profile_img'] ?>" alt="">
                                            <?php } else { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url ?>assets/img/user.jpg" alt="">
                                            <?php } ?>
                                        </div>
                                        <div class="user-text">
                                            <h6><?php echo $user_details['name']; ?></h6>
                                            <p class="text-muted mb-0"><?php echo (!empty($user_language[$user_selected]['lg_User'])) ? $user_language[$user_selected]['lg_User'] : $default_language['en']['lg_User']; ?></p>
                                        </div>
                                    </div>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>user-dashboard"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>user-bookings"><?php echo (!empty($user_language[$user_selected]['lg_My_Bookings'])) ? $user_language[$user_selected]['lg_My_Bookings'] : $default_language['en']['lg_My_Bookings']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?>user-settings"><?php echo (!empty($user_language[$user_selected]['lg_Profile_Settings'])) ? $user_language[$user_selected]['lg_Profile_Settings'] : $default_language['en']['lg_Profile_Settings']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url() ?>all-services"><?php echo (!empty($user_language[$user_selected]['lg_Book_Service'])) ? $user_language[$user_selected]['lg_Book_Service'] : $default_language['en']['lg_Book_Service']; ?></a>

                                    <?php
                                    $query = $this->db->query("select * from system_settings WHERE status = 1");
                                    $result = $query->result_array();

                                    $login_type = '';
                                    foreach ($result as $res) {

                                        if ($res['key'] == 'login_type') {
                                            $login_type = $res['value'];
                                        }

                                        if ($res['key'] == 'login_type') {
                                            $login_type = $res['value'];
                                        }
                                    }
                                    if ($login_type == 'email') {
                                    ?>
                                        <a class="dropdown-item" href="<?php echo base_url() ?>change-password"><?php echo (!empty($user_language[$user_selected]['lg_Change_Password'])) ? $user_language[$user_selected]['lg_Change_Password'] : $default_language['en']['lg_Change_Password']; ?></a>

                                    <?php } ?>

                                    <a class="dropdown-item" href="<?php echo base_url() ?>user-orders"><?php echo (!empty($user_language[$user_selected]['lg_my_orders'])) ? $user_language[$user_selected]['lg_my_orders'] : 'My Orders'; ?></a>

                                    <a class="dropdown-item" href="<?php echo base_url() ?>user-chat"><?php echo (!empty($user_language[$user_selected]['lg_chat'])) ? $user_language[$user_selected]['lg_chat'] : $default_language['en']['lg_chat']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url() ?>logout"><?php echo (!empty($user_language[$user_selected]['lg_Logout'])) ? $user_language[$user_selected]['lg_Logout'] : $default_language['en']['lg_Logout']; ?></a>
                                </div>
                            </li>
                        <?php
                        } else { ?>
                            <!-- User Menu -->
                            <li class="nav-item dropdown has-arrow logged-item">
                                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                                    <span class="user-img">
                                        <?php if (file_exists($user_details['profile_img'])) { ?>
                                            <img class="avatar-img rounded-circle" src="<?php echo $base_url . $user_details['profile_img'] ?>" width="31" alt="">
                                        <?php } else { ?>
                                            <img class="avatar-img rounded-circle" src="<?php echo base_url() . settingValue('profile_placeholder_image'); ?>" alt="">
                                        <?php } ?>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="user-header">
                                        <div class="avatar avatar-sm">
                                            <?php if (file_exists($user_details['profile_img'])) { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url . $user_details['profile_img'] ?>" alt="">
                                            <?php } else { ?>
                                                <img class="avatar-img rounded-circle" src="<?php echo $base_url ?>assets/img/user.jpg" alt="">
                                            <?php } ?>
                                        </div>
                                        <div class="user-text">
                                            <h6><?php echo $user_details['name']; ?></h6>
                                            <p class="text-muted mb-0">Admin</p>
                                        </div>
                                    </div>
                                    <a class="dropdown-item" href="<?php echo base_url(); ?><?php if ($this->session->userdata('userType') == 'manager') {
                                                                                                echo 'manager-dashboard';
                                                                                            } else {
                                                                                                echo 'dashboard';
                                                                                            } ?>"><?php echo (!empty($user_language[$user_selected]['lg_Dashboard'])) ? $user_language[$user_selected]['lg_Dashboard'] : $default_language['en']['lg_Dashboard']; ?></a>
                                    <a class="dropdown-item" href="<?php echo base_url() ?>logout"><?php echo (!empty($user_language[$user_selected]['lg_Logout'])) ? $user_language[$user_selected]['lg_Logout'] : $default_language['en']['lg_Logout']; ?></a>
                                </div>
                            </li>
                            <!-- /User Menu -->
                    <?php }
                    }
                    ?>
                </ul>
            </nav>
        </header>

        <?php if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') { ?>
            <!-- Common Moyasar Payment Modal for Subscriber & Shop -->
            <div id="paymentModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="pay-modal-title"></h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="mysr-form"></div>
                            <input type="hidden" class="paymodel-cls" id="publishable_api_key" value="" />
                            <input type="hidden" class="paymodel-cls" id="callbackurl" value="" />
                            <input type="hidden" class="paymodel-cls" id="amountval" value="" />
                            <input type="hidden" class="paymodel-cls" id="currencyval" value="" />
                            <input type="hidden" class="paymodel-cls" id="description" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- Common Moyasar Payment Modal for Subscriber & Shop -->
        <?php } ?>