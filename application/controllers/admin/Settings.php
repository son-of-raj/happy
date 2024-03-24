<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{

    public $data;

    public function __construct()
    {

        parent::__construct();
        error_reporting(0);
        $this->load->model('admin_model', 'admin');
        $this->load->model('common_model', 'common_model');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'settings';
        $this->data['base_url'] = base_url();
        $this->load->helper('user_timezone');
        $this->data['user_role'] = $this->session->userdata('role');
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->helper('ckeditor');
        $this->data['ckeditor_editor1'] = array(
            'id'   => 'ck_editor_textarea_id',
            'path' => 'assets/js/ckeditor',
            'config' => array(
                'toolbar'                     => "Full",
                'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
        $this->data['ckeditor_editor2'] = array(
            'id'   => 'ck_editor_textarea_id1',
            'path' => 'assets/js/ckeditor',
            'config' => array(
                'toolbar'                     => "Full",
                'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
        $this->data['ckeditor_editor3'] = array(
            'id'   => 'ck_editor_textarea_id2',
            'path' => 'assets/js/ckeditor',
            'config' => array(
                'toolbar'                     => "Full",
                'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
    }
    public function termconditions()
    {
        $this->common_model->checkAdminUserPermission(25);
        if ($this->input->post('form_submit')) {
            $this->load->library('upload');
            $data = $this->input->post();
            foreach ($data as $key => $val) {

                if ($key != 'form_submit') {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }
            }
        }
        $results = $this->admin->get_setting_list();

        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'termconditions';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function privacypolicy()
    {
        $this->common_model->checkAdminUserPermission(26);
        if ($this->input->post('form_submit')) {
            $this->load->library('upload');
            $data = $this->input->post();
            foreach ($data as $key => $val) {

                if ($key != 'form_submit') {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }
            }
        }
        $results = $this->admin->get_setting_list();

        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'privacypolicy';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function banner_image()
    {
        $this->data['page'] = 'banner_image';


        if ($this->input->post('form_submit')) {
            extract($_POST);
            $category = $this->input->post('category');
            $from_date = $this->input->post('from');
            $to_date = $this->input->post('to');
            $this->data['list'] = $this->admin->categories_list_filter($category, $from_date, $to_date);
        } else {
            $wr = array('id !' => '');
            $this->data['list'] = $this->admin->GetBannerDet();
        }


        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function edit_banner($id)
    {
        $this->data['page'] = 'edit_banner';


        if ($this->input->post()) {
            $inp = $this->input->post();

            extract($_POST);
            if ($this->input->post('bgimg_for') == 'banner' || $this->input->post('bgimg_for') == 'bottom_image1' || $this->input->post('bgimg_for') == 'bottom_image2' || $this->input->post('bgimg_for') == 'bottom_image3') {
                $data['banner_content'] = $this->input->post('banner_content');
                $data['banner_sub_content'] = $this->input->post('banner_sub_content');

                $this->load->library('common');
                $this->db->where('bgimg_id', $id);
                if ($this->db->update('bgimage', $data)) {
                    $message = "<div class='alert alert-success text-center fade in' id='flash_succ_message'>Category Successfully updated.</div>";
                }
                $insert_id = $id;
                if ($this->input->post('bgimg_for') == 'banner' || $this->input->post('bgimg_for') == 'bottom_image1' || $this->input->post('bgimg_for') == 'bottom_image2' || $this->input->post('bgimg_for') == 'bottom_image3') {
                    if (isset($_FILES) && !empty($_FILES['upload_image']['name'])) {
                        $av_file       = $_FILES['upload_image'];
                        $src           = 'uploads/banners/' . $av_file['name'];
                        $imageFileType = pathinfo($src, PATHINFO_EXTENSION);
                        $image_name    = time() . '.' . $imageFileType;
                        $src2          = 'uploads/banners/' . $image_name;
                        $src3          = 'uploads/banners/' . $image_name;
                        move_uploaded_file($av_file['tmp_name'], $src2);
                        $this->db->query("UPDATE `bgimage` SET `upload_image` = '" . $src2 . "',`cropped_img` ='" . $src2 . "' WHERE `bgimg_id` = '" . $insert_id . "' ");
                    }
                }
                if ($this->input->post('bgimg_for') == 'banner') {
                    $tile = 'Banner';
                }
                if ($this->input->post('bgimg_for') == 'bottom_image1') {
                    $tile = 'Bottom Image-1';
                }
                if ($this->input->post('bgimg_for') == 'bottom_image2') {
                    $tile = 'Bottom Image-2';
                }
                if ($this->input->post('bgimg_for') == 'bottom_image3') {
                    $tile = 'Bottom Image-3';
                }
                $this->session->set_flashdata('success_message', $tile . ' Updated successfully');
            }

            redirect(base_url('admin/edit-banner/' . $id . ''));
        } else {
            $wr = array('id !' => '');
            $this->data['list'] = $this->admin->GetBannerDetId($id);
        }
        $this->data['id'] = $id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function index()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            $this->load->library('upload');
            $data = $this->input->post();

            /*
             *  commision insert start vasanth
             */

            $admin_id = $this->session->userdata('admin_id');
            $commission = $this->input->post('commission');
            $CommInsert = [
                'admin_id' => $admin_id,
                'commission' => $commission,
            ];
            $where = [
                'status' => 1,
                'admin_id' => $admin_id,
            ];

            $AdminData = $this->admin->getSingleData('admin_commission', $where);

            if ($admin_id === $AdminData->admin_id) {

                $where = ['admin_id' => $admin_id];
                $this->admin->update_data('admin_commission', $CommInsert, $where);
            } else {
                $this->admin->update_data('admin_commission', $CommInsert);
            }

            /*
             *  commision insert end vasanth
             */

            if ($_FILES['site_logo']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['site_logo']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('site_logo')) {
                    $img_uploadurl = 'uploads/logo' . $_FILES['site_logo']['name'];
                    $key = 'logo_front';
                    $val = 'uploads/logo/' . $image_name;
                    $this->db->where('key', $key);
                }
                $this->db->delete('system_settings');
                $table_data1['key'] = $key;
                $table_data1['value'] = $val;
                $table_data1['system'] = 1;
                $table_data1['groups'] = 'config';
                $table_data1['update_date'] = date('Y-m-d');
                $table_data1['status'] = 1;
                $this->db->insert('system_settings', $table_data1);
            }
            if ($_FILES['favicon']['name']) {
                $img_uploadurl1 = '';
                $table_data2 = '';
                $table_data = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'png|ico';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['favicon']['name'];
                $configfile['file_name'] = $file_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('favicon')) {

                    $img_uploadurl1 = $_FILES['favicon']['name'];
                    $key = 'favicon';
                    $val = $img_uploadurl1;
                    $select_fav_icon = $this->db->query("SELECT * FROM `system_settings` WHERE `key` = '$key' ");
                    $fav_icon_result = $select_fav_icon->row_array();

                    if (count($fav_icon_result) > 0) {
                        $this->db->where('key', $key);
                        $this->db->update('system_settings', array('value' => $val));
                    } else {
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $this->db->insert('system_settings', $table_data);
                    }
                    $error = '';
                } else {
                    $error = $this->upload->display_errors();
                }
            }
            if ($data) {
                $table_data = array();

                # stripe_option // 1 SandBox, 2 Live 
                # stripe_allow  // 1 Active, 2 Inactive  

                $live_publishable_key = $live_secret_key = $secret_key = $publishable_key = '';

                $query = $this->db->query("SELECT * FROM payment_gateways WHERE status = 1");
                $stripe_details = $query->result_array();
                if (!empty($stripe_details)) {
                    foreach ($stripe_details as $details) {
                        if (strtolower($details['gateway_name']) == 'stripe') {
                            if (strtolower($details['gateway_type']) == 'sandbox') {

                                $publishable_key = $details['api_key'];
                                $secret_key = $details['value'];
                            }
                            if (strtolower($details['gateway_type']) == 'live') {
                                $live_publishable_key = $details['api_key'];
                                $live_secret_key = $details['value'];
                            }
                        }
                    }
                }

                $braintree_merchant = $braintree_key = $braintree_publickey = $braintree_privatekey = $paypal_appid = $paypal_appkey = '';
                $live_braintree_merchant = $live_braintree_key = $live_braintree_publickey = $live_braintree_privatekey = $live_paypal_appid = $live_paypal_appkey = '';
                $pdata['braintree_key'] = $this->input->post('braintree_key');
                $pdata['braintree_merchant'] = $this->input->post('braintree_merchant');
                $pdata['braintree_publickey'] = $this->input->post('braintree_publickey');
                $pdata['braintree_privatekey'] = $this->input->post('braintree_privatekey');
                $pdata['paypal_appid'] = $this->input->post('paypal_appid');
                $pdata['paypal_appkey'] = $this->input->post('paypal_appkey');
                $pdata['gateway_type'] = $this->input->post('paypal_gateway');
                if ($_POST['paypal_gateway'] == "sandbox") {
                    $pid = 1;
                } else {
                    $pid = 2;
                }
                $this->db->where('id', $pid);
                $this->db->update('paypal_payment_gateways', $pdata);

                $query = $this->db->query("SELECT * FROM paypal_payment_gateways");
                $paypal_details = $query->result_array();
                if (!empty($paypal_details)) {
                    foreach ($paypal_details as $details) {
                        if (strtolower($details['gateway_type']) == 'sandbox') {
                            $braintree_key = $details['braintree_key'];
                            $braintree_merchant = $details['braintree_merchant'];
                            $braintree_publickey = $details['braintree_publickey'];
                            $braintree_privatekey = $details['braintree_privatekey'];
                            $paypal_appid = $details['paypal_appid'];
                            $paypal_appkey = $details['paypal_appkey'];
                        } else {
                            $live_braintree_key = $details['braintree_key'];
                            $live_braintree_merchant = $details['braintree_merchant'];
                            $live_braintree_publickey = $details['braintree_publickey'];
                            $live_braintree_privatekey = $details['braintree_privatekey'];
                            $live_paypal_appid = $details['paypal_appid'];
                            $live_paypal_appkey = $details['paypal_appkey'];
                        }
                    }
                }
                $data['braintree_key']    = $braintree_key;
                $data['braintree_merchant']       = $braintree_merchant;
                $data['braintree_publickey'] = $braintree_publickey;
                $data['braintree_privatekey']    = $braintree_privatekey;
                $data['paypal_appid'] = $paypal_appid;
                $data['paypal_appkey']    = $paypal_appkey;

                $data['live_braintree_key']    = $live_braintree_key;
                $data['live_braintree_merchant']       = $live_braintree_merchant;
                $data['live_braintree_publickey'] = $live_braintree_publickey;
                $data['live_braintree_privatekey']    = $live_braintree_privatekey;
                $data['live_paypal_appid'] = $live_paypal_appid;
                $data['live_paypal_appkey']    = $live_paypal_appkey;

                $data['publishable_key'] = $publishable_key;
                $data['secret_key'] = $secret_key;
                $data['live_publishable_key'] = $live_publishable_key;
                $data['live_secret_key'] = $live_secret_key;

                foreach ($data as $key => $val) {

                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }
            $message = '';
            if (!empty($error)) {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            } else {
                $this->session->set_flashdata('success_message', 'Settings updated successfully');
            }
            redirect(base_url('admin/settings'));
        }

        $results = $this->admin->get_setting_list();

        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }



        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function emailsettings()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {


            $this->load->library('upload');
            $data = $this->input->post();
            if ($data) {
                $table_data = array();
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }


            $message = 'Settings saved successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url('admin/emailsettings'));
        }

        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'emailsettings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function googleplus_social_media()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {


            $this->load->library('upload');
            $data = $this->input->post();
            if ($data) {
                $table_data = array();
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }


            $message = 'Settings saved successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url('admin/googleplus_social_media'));
        }

        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'googleplus_social_media';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }


    public function twit_social_media()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {


            $this->load->library('upload');
            $data = $this->input->post();
            if ($data) {
                $table_data = array();
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }


            $message = 'Settings saved successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url('admin/twit-social-media'));
        }

        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'twit_social_media';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }


    public function fb_social_media()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {


            $this->load->library('upload');
            $data = $this->input->post();
            if ($data) {
                $table_data = array();
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }


            $message = 'Settings saved successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url('admin/fb-social-media'));
        }

        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'fb_social_media';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function smssettings()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            $this->load->library('upload');
            $data = $this->input->post();
            if ($data) {
                $table_data = array();
                if (isset($_POST['default_otp'])) {
                    $data['default_otp'] = 1;
                } else {
                    $data['default_otp'] = 0;
                }

                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
            }


            $message = 'Settings saved successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url('admin/sms-settings'));
        }
        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }
        if (empty($this->data['default_otp'])) {
            $this->data['default_otp'] = '';
        }
        $this->data['page'] = 'smssettings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function cod_payment_gateway()
    {
        $this->common_model->checkAdminUserPermission(14);
        $id = settingValue('cod_option');

        if ($this->input->post('form_submit')) {
            $this->db->where('key', 'cod_option');
            $this->db->delete('system_settings');
            $table_data['key'] = 'cod_option';
            $table_data['value'] = !empty($this->input->post('cod_show')) ? $this->input->post('cod_show') : 0;;
            $table_data['system'] = 1;
            $table_data['groups'] = 'config';
            $table_data['update_date'] = date('Y-m-d');
            $table_data['status'] = 1;
            $this->db->insert('system_settings', $table_data);
            $message = 'COD status updated successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/cod-payment-gateway');
        }
        if (!empty($id)) {
            $this->data['list']['status'] = 1;
        } else {
            $this->data['list']['status'] = 0;
        }

        $this->data['page'] = 'cod_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function stripe_payment_gateway()
    {
        $this->common_model->checkAdminUserPermission(14);
        $id = settingValue('stripe_option');
        if (!empty($id)) {
            $this->data['list'] = $this->admin->edit_payment_gateway($id);
        } else {
            $this->data['list'] = [];
            $this->data['list']['id'] = '';
            $this->data['list']['gateway_type'] = '';
            $this->data['gateway_type'] = '';
        }
        $this->data['page'] = 'stripe_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function razorpay_payment_gateway()
    {
        $this->common_model->checkAdminUserPermission(14);
        $id = settingValue('razor_option');



        if (!empty($id)) {
            $this->data['list'] = $this->admin->edit_razor_payment_gateway($id);
        } else {
            $this->data['list'] = [];
            $this->data['list']['id'] = '';
            $this->data['list']['gateway_type'] = '';
            $this->data['gateway_type'] = '';
        }
        $this->data['page'] = 'razorpay_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function paypal_payment_gateway()
    {
        $id = settingValue('paypal_option');

        if (!empty($id)) {
            $this->data['list'] = $this->admin->edit_paypal_payment_gateway($id);
        } else {
            $this->data['list'] = [];
            $this->data['list']['id'] = '';
            $this->data['list']['gateway_type'] = '';
            $this->data['gateway_type'] = '';
        }

        $this->data['page'] = 'paypal_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function paytabs_payment_gateway()
    {

        $this->data['list'] = $this->admin->edit_paytab_payment_gateway();
        $this->data['page'] = 'paytab_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function payment_type()
    {
        if (!empty($_POST['type'])) {
            $result = $this->db->where('gateway_type=', $_POST['type'])->get('payment_gateways')->row_array();
            if ($this->session->userdata('role') == 1) {
                echo json_encode($result);
                exit;
            } else {
                if ($result['gateway_type'] == 'live') {
                    $api_length = strlen($result['api_key']);
                    $str = str_repeat("x", $api_length - 8);
                    $api_key = "pk_live_" . $str;

                    $value_length = strlen($result['value']);
                    $strs = str_repeat("x", $value_length - 8);
                    $value = "sk_live_" . $strs;
                } else {
                    $api_length = strlen($result['api_key']);
                    $str = str_repeat("x", $api_length - 8);
                    $api_key = "pk_test_" . $str;

                    $value_length = strlen($result['value']);
                    $strs = str_repeat("x", $value_length - 8);
                    $value = "sk_test_" . $strs;
                }
                $data = array(
                    'gateway_name' => $result['gateway_name'],
                    'api_key' => $api_key,
                    'value' => $value
                );
                echo json_encode($data);
                exit;
            }
        }
    }

    public function razor_payment_type()
    {
        if (!empty($_POST['type'])) {
            $result = $this->db->where('gateway_type=', $_POST['type'])->get('razorpay_gateway')->row_array();
            echo json_encode($result);
            exit;
        }
    }

    public function moyaser_payment_type()
    {
        if (!empty($_POST['type'])) {
            $result = $this->db->where('gateway_type=', $_POST['type'])->get('moyaser_payment_gateway')->row_array();
            if ($this->session->userdata('role') == 1) {
                echo json_encode($result);
                exit;
            } else {
                if ($result['gateway_type'] == 'live') {
                    $api_length = strlen($result['api_key']);
                    $str = str_repeat("x", $api_length - 8);
                    $api_key = "pk_live_" . $str;

                    $api_secret_length = strlen($result['api_secret']);
                    $strs = str_repeat("x", $api_secret_length - 8);
                    $api_secret = "sk_live_" . $strs;
                } else {
                    $api_length = strlen($result['api_key']);
                    $str = str_repeat("x", $api_length - 8);
                    $api_key = "pk_test_" . $str;

                    $api_secret_length = strlen($result['api_secret']);
                    $strs = str_repeat("x", $api_secret_length - 8);
                    $api_secret = "sk_test_" . $strs;
                }
                $data = array(
                    'gateway_name' => $result['gateway_name'],
                    'api_key' => $api_key,
                    'api_secret' => $api_secret
                );
                echo json_encode($data);
                exit;
            }
        }
    }


    public function paypal_payment_type()
    {
        if (!empty($_POST['type'])) {
            $result = $this->db->where('gateway_type=', $_POST['type'])->get('paypal_payment_gateways')->row_array();
            if ($this->session->userdata('role') == 1) {
                echo json_encode($result);
                exit;
            } else {
                if ($result['gateway_type'] == 'live') {
                    $braintree_key_length = strlen($result['braintree_key']);
                    $braintree_key_str = str_repeat("x", $braintree_key_length);
                    $braintree_key = "" . $braintree_key_str;

                    $braintree_merchant_length = strlen($result['braintree_merchant']);
                    $braintree_merchantstrs = str_repeat("x", $braintree_merchant_length);
                    $braintree_merchant = "" . $braintree_merchantstrs;

                    $braintree_privatekey_length = strlen($list['braintree_privatekey']);
                    $braintree_privatekey_strs = str_repeat("x", $braintree_privatekey_length);
                    $braintree_privatekey = "" . $braintree_privatekey_strs;

                    $paypal_appid_length = strlen($list['paypal_appid']);
                    $paypal_appid_strs = str_repeat("x", $paypal_appid_length);
                    $paypal_appid = "" . $paypal_appid_strs;

                    $paypal_appkey_length = strlen($list['paypal_appkey']);
                    $paypal_appkey_strs = str_repeat("x", $paypal_appkey_length);
                    $paypal_appkey = "" . $paypal_appkey_strs;
                } else {
                    $braintree_key_length = strlen($result['braintree_key']);
                    $braintree_key_str = str_repeat("x", $braintree_key_length);
                    $braintree_key = "" . $braintree_key_str;

                    $braintree_merchant_length = strlen($result['braintree_merchant']);
                    $braintree_merchantstrs = str_repeat("x", $braintree_merchant_length);
                    $braintree_merchant = "" . $braintree_merchantstrs;

                    $braintree_privatekey_length = strlen($list['braintree_privatekey']);
                    $braintree_privatekey_strs = str_repeat("x", $braintree_privatekey_length);
                    $braintree_privatekey = "" . $braintree_privatekey_strs;

                    $paypal_appid_length = strlen($list['paypal_appid']);
                    $paypal_appid_strs = str_repeat("x", $paypal_appid_length);
                    $paypal_appid = "" . $paypal_appid_strs;

                    $paypal_appkey_length = strlen($list['paypal_appkey']);
                    $paypal_appkey_strs = str_repeat("x", $paypal_appkey_length);
                    $paypal_appkey = "" . $paypal_appkey_strs;
                }
                $data = array(
                    'gateway_name' => $result['gateway_name'],
                    'braintree_key' => $braintree_key,
                    'braintree_merchant' => $braintree_merchant,
                    'braintree_privatekey' => $braintree_privatekey,
                    'paypal_appid' => $paypal_appid,
                    'paypal_appkey' => $paypal_appkey
                );
                echo json_encode($data);
                exit;
            }
        }
    }
    public function edit($id = NULL)
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            if ($_POST['gateway_type'] == "sandbox") {
                $id = 1;
            } else {
                $id = 2;
            }

            $show = !empty($this->input->post('stripe_show')) ? $id : 0;

            $data['gateway_name'] = $this->input->post('gateway_name');
            $data['gateway_type'] = $this->input->post('gateway_type');
            $data['api_key'] = $this->input->post('api_key');
            $data['value'] = $this->input->post('value');
            $data['status'] = !empty($this->input->post('stripe_show')) ? $this->input->post('stripe_show') : 0;
            $this->db->where('id', $id);
            if ($this->db->update('payment_gateways', $data)) {
                if ($this->input->post('gateway_type') == 'sandbox') {
                    $datass['publishable_key'] = $this->input->post('api_key');
                    $datass['secret_key'] = $this->input->post('value');
                } else {
                    $datass['live_publishable_key'] = $this->input->post('api_key');
                    $datass['live_secret_key'] = $this->input->post('value');
                }
                $stripe_option = settingValue('stripe_option');
                if (isset($stripe_option)) {
                    $this->db->where('key', 'stripe_option')->update('system_settings', ['value' => $show]);
                } else {
                    $this->db->insert('system_settings', ['key' => 'stripe_option', 'value' => $show]);
                }

                foreach ($datass as $key => $val) {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }
                $message = 'Payment gateway edit successfully';
            }
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/stripe-payment-gateway');
        }

        $this->data['list'] = $this->admin->edit_payment_gateway($id);
        $this->data['page'] = 'stripe_payment_gateway_edit';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }


    public function razor_edit($id = NULL)
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            if ($_POST['gateway_type'] == "sandbox") {
                $id = 1;
            } else {
                $id = 2;
            }
            $show = !empty($this->input->post('razor_show')) ? $id : 0;
            $data['gateway_name'] = $this->input->post('gateway_name');
            $data['gateway_type'] = $this->input->post('gateway_type');
            $data['api_key'] = $this->input->post('api_key');
            $data['api_secret'] = $this->input->post('value');
            $data['status'] = !empty($this->input->post('razor_show')) ? $this->input->post('razor_show') : 0;
            $this->db->where('id', $id);
            if ($this->db->update('razorpay_gateway', $data)) {
                if ($this->input->post('gateway_type') == 'sandbox') {
                    $datass['razorpay_apikey'] = $this->input->post('api_key');
                    $datass['razorpay_secret_key'] = $this->input->post('value');
                } else {
                    $datass['live_razorpay_apikey'] = $this->input->post('api_key');
                    $datass['live_razorpay_secret_key'] = $this->input->post('value');
                }
                $razor_option = settingValue('razor_option');

                if (isset($razor_option)) {
                    $this->db->where('key', 'razor_option')->update('system_settings', ['value' => $show]);
                } else {
                    $this->db->insert('system_settings', ['key' => 'razor_option', 'value' => $show]);
                }

                foreach ($datass as $key => $val) {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }

                $message = 'Payment gateway edit successfully';
            }
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/razorpay-payment-gateway');
        }

        $this->data['list'] = $this->admin->edit_payment_gateway($id);
        $this->data['page'] = 'razorpay_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function paypal_edit($id = NULL)
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            if ($_POST['paypal_gateway'] == "sandbox") {
                $id = 1;
            } else {
                $id = 2;
            }
            $show = !empty($this->input->post('paypal_show')) ? $id : 0;
            $data['braintree_key'] = $this->input->post('braintree_key');
            $data['gateway_type'] = $this->input->post('paypal_gateway');
            $data['braintree_merchant'] = $this->input->post('braintree_merchant');
            $data['braintree_publickey'] = $this->input->post('braintree_publickey');
            $data['braintree_privatekey'] = $this->input->post('braintree_privatekey');
            $data['paypal_appid'] = $this->input->post('paypal_appid');
            $data['paypal_appkey'] = $this->input->post('paypal_appkey');
            $data['status'] = !empty($this->input->post('paypal_show')) ? $this->input->post('paypal_show') : 0;
            $this->db->where('id', $id);
            if ($this->db->update('paypal_payment_gateways', $data)) {
                if ($this->input->post('paypal_gateway') == 'sandbox') {
                    $datass['braintree_key'] = $this->input->post('braintree_key');
                    $datass['gateway_type'] = $this->input->post('paypal_gateway');
                    $datass['braintree_merchant'] = $this->input->post('braintree_merchant');
                    $datass['braintree_publickey'] = $this->input->post('braintree_publickey');
                    $datass['braintree_privatekey'] = $this->input->post('braintree_privatekey');
                    $datass['paypal_appid'] = $this->input->post('paypal_appid');
                    $datass['paypal_appkey'] = $this->input->post('paypal_appkey');
                } else {
                    $datass['braintree_key'] = $this->input->post('braintree_key');
                    $datass['gateway_type'] = $this->input->post('paypal_gateway');
                    $datass['braintree_merchant'] = $this->input->post('braintree_merchant');
                    $datass['braintree_publickey'] = $this->input->post('braintree_publickey');
                    $datass['braintree_privatekey'] = $this->input->post('braintree_privatekey');
                    $datass['paypal_appid'] = $this->input->post('paypal_appid');
                    $datass['paypal_appkey'] = $this->input->post('paypal_appkey');
                }
                $paypal_option = settingValue('paypal_option');

                if (isset($paypal_option)) {
                    $this->db->where('key', 'paypal_option')->update('system_settings', ['value' => $show]);
                } else {
                    $this->db->insert('system_settings', ['key' => 'paypal_option', 'value' => $show]);
                }

                foreach ($datass as $key => $val) {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }

                $message = 'Payment gateway edit successfully';
            }
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/paypal-payment-gateway');
        }

        $this->data['list'] = $this->admin->edit_payment_gateway($id);
        $this->data['page'] = 'paypal_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function paytab_edit($id = NULL)
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {

            $id = 1;
            $show = !empty($this->input->post('paytab_show')) ? $id : 0;
            $data['sandbox_email'] = $this->input->post('sandbox_email');
            $data['sandbox_secretkey'] = $this->input->post('sandbox_secretkey');
            $data['email'] = $this->input->post('email');
            $data['secretkey'] = $this->input->post('secretkey');
            $this->db->where('id', $id);
            if ($this->db->update('paytabs_details', $data)) {

                $datass['sandbox_email'] = $this->input->post('sandbox_email');
                $datass['sandbox_secretkey'] = $this->input->post('sandbox_secretkey');
                $datass['email'] = $this->input->post('email');
                $datass['secretkey'] = $this->input->post('secretkey');

                $paytab_option = settingValue('paytab_option');

                if (isset($paytab_option)) {
                    $this->db->where('key', 'paytab_option')->update('system_settings', ['value' => $show]);
                } else {
                    $this->db->insert('system_settings', ['key' => 'paytab_option', 'value' => $show]);
                }

                foreach ($datass as $key => $val) {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }

                $message = 'Payment gateway edit successfully';
            }
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/paytabs-payment-gateway');
        }

        $this->data['list'] = $this->admin->edit_payment_gateway($id);
        $this->data['page'] = 'paytab_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }


    /*Moyaser Payment Gateway, HomeService Payment*/
    public function moyaser_payment_gateway()
    {
        $this->common_model->checkAdminUserPermission(14);
        $id = settingValue('moyaser_option');
        if (!empty($id)) {
            $this->data['list'] = $this->admin->edit_moyaser_payment_gateway($id);
        } else {
            $this->data['list'] = [];
            $this->data['list']['id'] = '';
            $this->data['list']['gateway_type'] = '';
            $this->data['gateway_type'] = '';
        }
        $this->data['page'] = 'moyaser_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function moyaser_edit($id = NULL)
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            if ($_POST['gateway_type'] == "sandbox") {
                $id = 1;
            } else {
                $id = 2;
            }
            $show = !empty($this->input->post('moyaser_show')) ? $id : 0;

            $data['gateway_name'] = $this->input->post('gateway_name');
            $data['gateway_type'] = $this->input->post('gateway_type');
            $data['api_key'] = $this->input->post('api_key');
            $data['api_secret'] = $this->input->post('value');
            $data['status'] = !empty($this->input->post('moyaser_show')) ? $this->input->post('moyaser_show') : 0;
            $this->db->where('id', $id);
            if ($this->db->update('moyaser_payment_gateway', $data)) {
                if ($this->input->post('gateway_type') == 'sandbox') {
                    $datass['moyaser_apikey'] = $this->input->post('api_key');
                    $datass['moyaser_secret_key'] = $this->input->post('value');
                } else {
                    $datass['live_moyaser_apikey'] = $this->input->post('api_key');
                    $datass['live_moyaser_secret_key'] = $this->input->post('value');
                }
                $moyaser_option = settingValue('moyaser_option');
                $datass['moyasor_option_show'] = $data['status'];
                if (isset($moyaser_option)) {
                    $this->db->where('key', 'moyaser_option')->update('system_settings', ['value' => $show]);
                } else {
                    $this->db->insert('system_settings', ['key' => 'moyaser_option', 'value' => $show]);
                }

                foreach ($datass as $key => $val) {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }

                $message = 'Payment gateway edit successfully';
            }
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/moyaser-payment-gateway');
        }

        $this->data['list'] = $this->admin->edit_moyaser_payment_gateway($id);
        $this->data['page'] = 'moyaser_payment_gateway';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function homeservice_settings()
    {
        $this->common_model->checkAdminUserPermission(14);

        $data['shop_fee'] = !empty($this->input->post('shopfee_val')) ? $this->input->post('shopfee_val') : 0;
        $data['corona_control'] = !empty($this->input->post('coronacontrol')) ? $this->input->post('coronacontrol') : 0;


        if ($this->input->post('form_submit')) {
            foreach ($data as $key => $val) {
                if ($key != 'form_submit') {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }
            }
            $message = 'Details Updated Successfully';
            $this->session->set_flashdata('success_message', $message);
            redirect(base_url() . 'admin/homeservice-settings');
        }
        $results = $this->admin->get_setting_list();

        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }

        $this->data['page'] = 'homeservice_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function systemSetting()
    {
        if ($this->input->post('form_submit') == true) {
            $map_details = $this->db->get_where('system_settings', array('key' => 'map_key'))->row();
            $apikey_details = $this->db->get_where('system_settings', array('key' => 'firebase_server_key'))->row();

            if ($this->input->post('map_key')) {
                if (empty($map_details)) {
                    $table_data['key'] = 'map_key';
                    $table_data['value'] = $this->input->post('map_key');
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                } else {
                    $where = array('key' => 'map_key');
                    $table_data['key'] = 'map_key';
                    $table_data['value'] = $this->input->post('map_key');
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->admin->update_data('system_settings', $table_data, $where);
                }
            }
            if ($this->input->post('firebase_server_key')) {
                if (empty($apikey_details)) {
                    $table_data['key'] = 'firebase_server_key';
                    $table_data['value'] = $this->input->post('firebase_server_key');
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                } else {
                    $where = array('key' => 'firebase_server_key');
                    $table_data['key'] = 'firebase_server_key';
                    $table_data['value'] = $this->input->post('firebase_server_key');
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->admin->update_data('system_settings', $table_data, $where);
                }
            }
            $this->session->set_flashdata('success_message', 'Details updated successfully');
            redirect(base_url() . 'admin/system-settings');
        }
        $this->data['firebase_server_key'] = settingValue('firebase_server_key');
        $this->data['map_key'] = settingValue('map_key');
        $this->data['page'] = 'system_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function socialSetting()
    {
        if ($this->input->post('form_submit') == true) {
            $data = $this->input->post();
            $table_data = array();
            foreach ($data as $key => $val) {
                if ($key != 'form_submit') {
                    $data_details = $this->db->get_where('system_settings', array('key' => $key))->row();
                    $table_data = array(
                        'key' => $key,
                        'value' => $val,
                        'system' => 1,
                        'groups' => 'config',
                        'update_date' => date('Y-m-d'),
                        'status' => 1
                    );
                    if (empty($data_details)) {
                        $this->db->insert('system_settings', $table_data);
                    } else {
                        $where = array('key' => $key);
                        $this->db->update('system_settings', $table_data, $where);
                    }
                }
            }
            $this->session->set_flashdata('success_message', 'Details updated successfully');
            redirect(base_url() . 'admin/social-settings');
        }
        $this->data['login_type'] = settingValue('login_type');
        $this->data['otp_by'] = settingValue('otp_by');
        $this->data['page'] = 'social_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function seoSetting()
    {
        if ($this->input->post("form_submit") == true) {
            $data = $this->input->post();
            $table_data = array();
            foreach ($data as $key => $val) {
                if ($key != 'form_submit') {
                    $data_details = $this->db->get_where('system_settings', array('key' => $key))->row();
                    $table_data = array(
                        'key' => $key,
                        'value' => $val,
                        'system' => 1,
                        'groups' => 'config',
                        'update_date' => date('Y-m-d'),
                        'status' => 1
                    );
                    if (empty($data_details)) {
                        $this->db->insert('system_settings', $table_data);
                    } else {
                        $where = array('key' => $key);
                        $this->db->update('system_settings', $table_data, $where);
                    }
                }
            }

            $this->session->set_flashdata('success_message', 'SEO Details updated successfully');
            redirect(base_url() . 'admin/seo-settings');
        }
        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }
        $this->data['page'] = 'seo_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function ThemeColorChange()
    {

        $this->data['page'] = 'theme_color';
        $this->data['Colorlist'] = $this->admin->ColorList();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function ChangeColor()
    {
        $Postdata = $this->input->post();
        $ChangeColor = $Postdata['color'];

        if ($ChangeColor) {

            $whr = [
                'id' => $ChangeColor
            ];
            $color = [
                'status' => 1
            ];
            $query = $this->db->query("UPDATE theme_color_change SET status='0'");
            $updateColor = $this->admin->update_data('theme_color_change', $color, $whr);

            if ($updateColor) {
                $this->session->set_flashdata('success_message1', 'Color Change Suceessfully');
                redirect(base_url() . 'admin/theme-color');
            }
        } else {
            $this->session->set_flashdata('error_message1', 'Choose the Color');
            redirect(base_url() . 'admin/theme-color');
        }
    }

    public function analytics()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['analytics_showhide'])) {
                    $data['analytics_showhide'] = '1';
                } else {
                    $data['analytics_showhide'] = '0';
                }
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Google Analytics Details updated successfully');
                redirect(base_url() . 'admin/other-settings');
            }
        }
    }

    public function cookies()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['cookies_showhide'])) {
                    $data['cookies_showhide'] = '1';
                } else {
                    $data['cookies_showhide'] = '0';
                }
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Cookies Agreement Details updated successfully');
                redirect(base_url() . 'admin/other-settings');
            }
        }
    }

    public function localization()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            $data['country_code_key'] = strtolower($data['country_code_key']);
            // echo '<pre>'; print_r($data); exit;
            foreach ($data as $key => $val) {
                if ($key != 'form_submit') {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $this->db->insert('system_settings', $table_data);
                }
            }
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Localization updated successfully');
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }
        $this->data['currency_symbol'] = currency_code_symbol();
        $this->data['page'] = 'localization';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function get_currnecy_symbol()
    {
        $code = $this->input->post('id');
        $result = currency_code_sign($code);
        echo $result;
    }
    public function pages()
    {
        $this->data['pages'] = $this->db->get('page_content')->result();
        $this->data['page'] = 'pages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function aboutus($id)
    {

        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('page_title');
        if ($this->input->post("form_submit") == true) {
            $page_title = $this->db->get_where('page_content', array('id' => 1))->row();

            if (empty($page_title)) {

                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['status'] = 1;
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('page_content', $table_data);
                echo $this->db->last_query();
                exit;
            } else {
                $where = array('id' => 1);
                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('page_content', $table_data, $where);
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'About Us updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }
        $this->data['pages'] = $this->admin->getting_pages_list($id);
        $this->data['page'] = 'about-us';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function cookie_policy($id)
    {
        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('page_title');
        if ($this->input->post("form_submit") == true) {
            $page_title = $this->db->get_where('page_content', array('id' => 19))->row();

            if (empty($page_title)) {

                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['status'] = 1;
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('page_content', $table_data);
            } else {
                $where = array('id' => 19);
                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('page_content', $table_data, $where);
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Cookie Policy updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }
        $this->data['pages'] = $this->admin->getting_pages_list($id);
        $this->data['page'] = 'cookie_policy';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function faq_delete()
    {
        $id = $this->input->post('id');
        $this->db->where('id', $id)->delete('faq');
        $result = $this->db->where('id', $id)->delete('faq');
        if ($result) {
            $this->session->set_flashdata('success_message', 'FAQ deleted successfully');
            redirect(base_url() . 'admin/pages');
        } else {
            $this->session->set_flashdata('error_message', 'Something went wront, Try again');
            redirect(base_url() . 'admin/pages');
        }
    }
    public function faq($id)
    {
        $this->common_model->checkAdminUserPermission(24);
        $titles = $this->input->post('page_title');
        $cont = $this->input->post('page_content');
        $faq_id = $this->input->post('faq_id');
        if ($this->input->post("form_submit") == true) {
            foreach ($titles as $key => $value) {
                $data = array(
                    'page_title'     => $value,
                    'page_content'  => $cont[$key],
                    'status'   => 1,
                    'created_datetime' => date('Y-m-d H:i:s')
                );

                if (empty($faq_id[$key])) {

                    $this->db->insert('faq', $data);
                } else {
                    $where = array('id' => $faq_id[$key]);
                    $this->db->update('faq', $data, $where);
                }
            }
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'FAQ updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }

        $this->data['pages'] = $this->admin->getting_faq_list();
        $this->data['page'] = 'faq';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function help($id)
    {
        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('page_title');
        if ($this->input->post("form_submit") == true) {
            $page_title = $this->db->get_where('page_content', array('id' => 14))->row();

            if (empty($page_title)) {

                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['status'] = 1;
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('page_content', $table_data);
            } else {
                $where = array('id' => 14);
                $table_data['page_title'] = $this->input->post('page_title');
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('page_content', $table_data, $where);
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Help updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }
        $this->data['pages'] = $this->admin->getting_pages_list($id);
        $this->data['page'] = 'help';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function privacy_policy($id)
    {
        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('page_title');
        if ($this->input->post("form_submit") == true) {
            $page_title = $this->db->get_where('page_content', array('id' => 15))->row();

            if (empty($page_title)) {

                $table_data['page_title'] = $this->input->post('page_title');
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['status'] = 1;
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('page_content', $table_data);
            } else {
                $where = array('id' => 15);
                $table_data['page_title'] = $this->input->post('page_title');
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('page_content', $table_data, $where);
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Privacy Policy updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }
        $this->data['pages'] = $this->admin->getting_pages_list($id);
        $this->data['page'] = 'privacy_policy';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function terms_of_services($id)
    {
        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('page_title');
        if ($this->input->post("form_submit") == true) {
            $page_title = $this->db->get_where('page_content', array('id' => 16))->row();

            if (empty($page_title)) {

                $table_data['page_title'] = $this->input->post('page_title');
                $terms_slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['page_title']);
                $table_data['page_slug'] = strtolower($terms_slug);
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['status'] = 1;
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('page_content', $table_data);
            } else {
                $where = array('id' => 16);
                $table_data['page_title'] = $this->input->post('page_title');
                $table_data['page_content'] = $this->input->post('page_content');
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('page_content', $table_data, $where);
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Terms Of Services updated successfully');
                redirect(base_url() . 'admin/pages');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url() . 'admin/pages');
            }
        }
        $this->data['pages'] = $this->admin->getting_pages_list($id);
        $this->data['page'] = 'terms_of_services';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function home_page()
    {
        $this->data['featured'] = $this->db->get_where('categories', array('is_featured' => 1, 'status' => 1))->result_array();
        $this->data['list'] = $this->admin->GetBannersettings();
        $this->data['service'] = $this->admin->Getpopularsettings();
        $this->data['page'] = 'home_page';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function bannersettings()
    {
        $this->common_model->checkAdminUserPermission(24);
        $title = $this->input->post('bgimg_for');
        if ($this->input->post("form_submit") == true) {
            $banner_title = $this->db->get_where('bgimage', array('bgimg_id' => 1))->row();
            $post_data = $this->input->post();
            $uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['upload_image']['name']) && !empty($_FILES['upload_image']['name'])) {
                $uploaded_file_name = $_FILES['upload_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/banners/', 'upload_image', time() . $filename);

                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];

                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/banners/' . $uploaded_file_name;
                    }
                }
            } else {
                $image_url = $banner_title->upload_image;
            }
            $table_data = array(
                'banner_content' => $post_data['banner_content'],
                'banner_sub_content' => $post_data['banner_sub_content'],
                'banner_settings' => ($post_data['banner_showhide']) ? '1' : '0',
                'main_search' => ($post_data['main_showhide']) ? '1' : '0',
                'popular_search' => ($post_data['popular_showhide']) ? '1' : '0',
                'upload_image' => $image_url,
                'popular_label' => $post_data['popular_label']
            );
            if (empty($banner_title)) {
                $table_data['created_datetime'] = date('Y-m-d H:i:s');
                $this->db->insert('bgimage', $table_data);
            } else {
                $where = array('bgimg_id' => 1);
                $table_data['updated_datetime'] = date('Y-m-d H:i:s');
                $this->admin->update_data('bgimage', $table_data, $where);
            }
            $this->session->set_flashdata('success_message', 'Bannersettings Details updated successfully');
            redirect(base_url() . 'admin/pages');
        }
    }

    public function howitworks()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['how_showhide'])) {
                    $data['how_showhide'] = '1';
                } else {
                    $data['how_showhide'] = '0';
                }

                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['how_title_img_1']['name']) && !empty($_FILES['how_title_img_1']['name'])) {
                    $uploaded_file_name = $_FILES['how_title_img_1']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'how_title_img_1', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $image_url_1 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $image_url_1 = settingValue('how_title_img_1');
                }

                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['how_title_img_2']['name']) && !empty($_FILES['how_title_img_2']['name'])) {
                    $uploaded_file_name = $_FILES['how_title_img_2']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'how_title_img_2', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $image_url_2 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $image_url_2 = settingValue('how_title_img_2');
                }


                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['how_title_img_3']['name']) && !empty($_FILES['how_title_img_3']['name'])) {
                    $uploaded_file_name = $_FILES['how_title_img_3']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';

                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'how_title_img_3', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $image_url_3 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $image_url_3 = settingValue('how_title_img_3');
                }
                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['how_title_img_4']['name']) && !empty($_FILES['how_title_img_4']['name'])) {
                    $uploaded_file_name = $_FILES['how_title_img_4']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';

                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'how_title_img_4', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $image_url_4 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $image_url_4 = settingValue('how_title_img_4');
                }
                $data['how_title_img_1'] = $image_url_1;
                $data['how_title_img_2'] = $image_url_2;
                $data['how_title_img_3'] = $image_url_3;
                $data['how_title_img_4'] = $image_url_4;
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'How It Works Details updated successfully');
                redirect(base_url() . 'admin/pages');
            }
        }
    }

    public function popularservices()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['popular_ser_showhide'])) {
                    $data['popular_ser_showhide'] = '1';
                } else {
                    $data['popular_ser_showhide'] = '0';
                }
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Popular Services Details updated successfully');
                redirect(base_url() . 'admin/pages');
            }
        }
    }

    public function blogcontents()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['blogs_showhide'])) {
                    $data['blogs_showhide'] = '1';
                } else {
                    $data['blogs_showhide'] = '0';
                }
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Blogs Details updated successfully');
                redirect(base_url() . 'admin/pages');
            }
        }
    }

    public function download_sec()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {


            if ($data) {
                if (isset($data['download_showhide'])) {
                    $data['download_showhide'] = '1';
                } else {
                    $data['download_showhide'] = '0';
                }
                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['app_store_img']['name']) && !empty($_FILES['app_store_img']['name'])) {
                    $uploaded_file_name = $_FILES['app_store_img']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';

                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'app_store_img', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $app_store_img1 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $app_store_img1 = settingValue('app_store_img');
                }
                $uploaded_file_name = '';
                if (isset($_FILES) && isset($_FILES['play_store_img']['name']) && !empty($_FILES['play_store_img']['name'])) {
                    $uploaded_file_name = $_FILES['play_store_img']['name'];
                    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';

                    $this->load->library('common');
                    $upload_sts = $this->common->global_file_upload('uploads/banners/', 'play_store_img', time() . $filename);

                    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                        $uploaded_file_name = $upload_sts['data']['file_name'];

                        if (!empty($uploaded_file_name)) {
                            $play_store_img1 = 'uploads/banners/' . $uploaded_file_name;
                        }
                    }
                } else {
                    $play_store_img1 = settingValue('play_store_img');
                }
                $data['play_store_img'] = $play_store_img1;
                $data['app_store_img'] = $app_store_img1;
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Download Section Details updated successfully');
                redirect(base_url() . 'admin/pages');
            }
        }
    }

    public function featured_categories()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['featured_showhide'])) {
                    $data['featured_showhide'] = '1';
                } else {
                    $data['featured_showhide'] = '0';
                }
                $featured_categories = '';
                if (!empty($data['selected_categories'])) {
                    $featured_categories = implode(',', $data['selected_categories']);
                }
                $datas = array(
                    'featured_showhide' => $data['featured_showhide'],
                    'featured_title' => $data['featured_title'],
                    'featured_content' => $data['featured_content'],
                    'featured_categories' => $featured_categories
                );
                foreach ($datas as $key => $val) {
                    $getdata = $this->db->get_where('system_settings', array('key' => $key))->row();
                    $table_data = array(
                        'key' => $key,
                        'value' => $val,
                        'system' => 1,
                        'groups' => 'config',
                        'update_date' => date('Y-m-d'),
                        'status' => 1
                    );
                    if (empty($getdata)) {
                        $this->db->insert('system_settings', $table_data);
                    } else {
                        $this->db->where('key', $key);
                        $this->db->update('system_settings', $table_data);
                    }
                }

                $this->session->set_flashdata('success_message', 'Featured Categories Details updated successfully');
                redirect(base_url() . 'admin/pages');
            }
        }
    }

    public function page_status()
    {
        $id = $this->input->post('status_id');
        $table_data['status'] = $this->input->post('status');
        $this->db->where('id', $id);
        if ($this->db->update('page_content', $table_data)) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function generalSetting()
    {
        if ($this->input->post('form_submit') == true) {
            $this->load->library('upload');
            $data = $this->input->post();
            if (!is_dir('uploads/logo')) {
                mkdir('./uploads/logo', 0777, TRUE);
            }

            if (!is_dir('uploads/placeholder_img')) {
                mkdir('./uploads/placeholder_img', 0777, TRUE);
            }
            if ($_FILES['logo_front']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['logo_front']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('logo_front')) {
                    $img_uploadurl = 'uploads/logo' . $_FILES['logo_front']['name'];
                    $key = 'logo_front';
                    $val = 'uploads/logo/' . $image_name;
                    $data['logo_front'] = $val;
                }
            }

            if ($_FILES['favicon']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['favicon']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('favicon')) {
                    $img_uploadurl = 'uploads/logo' . $_FILES['favicon']['name'];
                    $key = 'favicon';
                    $val = $image_name;
                    $data['favicon'] = $val;
                }
            }
            if ($_FILES['header_icon']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['header_icon']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('header_icon')) {
                    $img_uploadurl = 'uploads/logo' . $_FILES['header_icon']['name'];
                    $key = 'header_icon';
                    $val = 'uploads/logo/' . $image_name;
                    $data['header_icon'] = $val;
                }
            }

            if ($_FILES['service_placeholder_image']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/placeholder_img';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['service_placeholder_image']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('service_placeholder_image')) {
                    $img_uploadurl = 'uploads/placeholder_img' . $_FILES['service_placeholder_image']['name'];
                    $key = 'service_placeholder_image';
                    $val = 'uploads/placeholder_img/' . $image_name;
                    $data['service_placeholder_image'] = $val;
                }
            }

            if ($_FILES['profile_placeholder_image']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/placeholder_img';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['profile_placeholder_image']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/placeholder/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('profile_placeholder_image')) {
                    $img_uploadurl = 'uploads/placeholder_img' . $_FILES['profile_placeholder_image']['name'];
                    $key = 'profile_placeholder_image';
                    $val = 'uploads/placeholder_img/' . $image_name;
                    $data['profile_placeholder_image'] = $val;
                }
            }

            if ($data) {
                $table_data = array();
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $data_details = $this->db->get_where('system_settings', array('key' => $key))->row();
                        if (empty($data_details)) {
                            $table_data['key'] = $key;
                            $table_data['value'] = $val;
                            $table_data['system'] = 1;
                            $table_data['groups'] = 'config';
                            $table_data['update_date'] = date('Y-m-d');
                            $table_data['status'] = 1;
                            $this->db->insert('system_settings', $table_data);
                        } else {
                            $where = array('key' => $key);
                            $table_data['key'] = $key;
                            $table_data['value'] = $val;
                            $table_data['system'] = 1;
                            $table_data['groups'] = 'config';
                            $table_data['update_date'] = date('Y-m-d');
                            $table_data['status'] = 1;
                            $this->db->update('system_settings', $table_data, $where);
                        }
                    }
                }
                if (!empty($data['language'])) {
                    $this->db->where('language_value', $data['language']);
                    $this->db->update('language', array('default_language' => 1));
                    $this->db->where('language_value!=', $data['language']);
                    $this->db->update('language', array('default_language' => 2));
                }


                $admin_id = $this->session->userdata('admin_id');
                $commission = $this->input->post('commission');
                if (!empty($commission)) {
                    $CommInsert = [
                        'admin_id' => $admin_id,
                        'commission' => $commission,
                    ];
                    $where = [
                        'status' => 1,
                        'admin_id' => $admin_id,
                    ];

                    $AdminData = $this->admin->getSingleData('admin_commission', $where);

                    if ($admin_id === $AdminData->admin_id) {

                        $where = ['admin_id' => $admin_id];
                        $this->admin->update_data('admin_commission', $CommInsert, $where);
                    } else {
                        $this->admin->update_data('admin_commission', $CommInsert);
                    }
                }

                $this->session->set_flashdata('success_message', 'Setting details updated successfully');
                redirect(base_url() . 'admin/general-settings');
            }
        }

        $results = $this->admin->get_setting_list();
        foreach ($results as $config) {
            $this->data[$config['key']] = $config['value'];
        }
        $this->data['currencies'] = $this->db->get('currency')->result_array();
        $this->data['languages'] = $this->db->get('language')->result_array();
        $this->data['page'] = 'general-settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function footerSetting()
    {
        $this->data['page'] = 'footersettings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }


    /** [Currency Settings] */
    public function currencySettings()
    {
        $this->data['page'] = 'currency_settings';
        $this->data['lists'] = $this->admin->get_currency_config();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    /** [Currency Create] */
    public function create_currency()
    {
        if ($this->input->post('form_submit')) {
            $table_data['currency_name'] = $this->input->post('currency_name');
            $table_data['currency_symbol'] = $this->input->post('currency_symbol');
            $table_data['currency_code'] = $this->input->post('currency_code');
            $table_data['rate'] = $this->input->post('rate');
            $table_data['status'] = $this->input->post('status');
            $table_data['delete_status'] = 1;
            $table_data['created_at'] = date('Y-m-d');
            if ($this->db->insert('currency_rate', $table_data)) {
                $this->session->set_flashdata('success_message', 'Currency Added successfully');
                redirect(base_url('admin/' . 'currency-settings'));
            }
        }
        $this->data['page'] = 'create_currency';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    /** [Currency Edit] */
    public function currency_edit($cur_id)
    {
        if ($this->input->post('form_submit')) {
            $table_data['currency_name'] = $this->input->post('currency_name');
            $table_data['currency_symbol'] = $this->input->post('currency_symbol');
            $table_data['currency_code'] = $this->input->post('currency_code');
            $table_data['rate'] = $this->input->post('rate');
            $table_data['status'] = $this->input->post('status');
            $table_data['updated_at'] = date('Y-m-d');
            $this->db->update('currency_rate', $table_data, "id = " . $cur_id);
            $this->session->set_flashdata('success_message', 'Currency Updated successfully');
            redirect(base_url('admin/' . 'currency-settings'));
        }
        $this->data['currencylist'] = $this->admin->edit_currency_config($cur_id);
        $this->data['page'] = 'currency_edit';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function cur_delete()
    {
        $inp = $this->input->post();
        $this->db->where('id', $inp['id']);
        $this->db->set('delete_status', 0);
        $this->db->update('currency_rate');
        echo json_encode("success");
    }

    public function cache_settings()
    {
        $this->common_model->checkAdminUserPermission(38);
        $data = $this->input->post();

        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['pro_cache_status'])) {
                    $data['pro_cache_status'] = '1';
                } else {
                    $data['pro_cache_status'] = '0';
                }
                if ($data['pro_cache_status'] == 1) {

                    $service_data = $this->db->query("SELECT * FROM `services` WHERE `status` =  1;")->result();
                    $data['service_data'] = $service_data;
                    $response = array();
                    $posts = array();
                    foreach ($service_data as $service) {
                        $posts[] = array(
                            "id"                 =>  $service->id,
                            "user_id"                  =>  $service->user_id,
                            "service_title"            =>  $service->service_title,
                            "currency_code" =>  $service->currency_code,
                            "service_sub_title"                  =>  $service->service_sub_title,
                            "service_amount"                 =>  $service->service_amount,
                            "category"                  =>  $service->category,
                            "subcategory"            =>  $service->subcategory,
                            "about" =>  $service->about,
                            "service_offered"                  =>  $service->service_offered,
                            "service_location"                 =>  $service->service_location,
                            "service_latitude"                  =>  $service->service_latitude,
                            "service_longitude"            =>  $service->service_longitude,
                            "service_image" =>  $service->service_image,
                            "service_details_image"                  =>  $service->service_details_image,
                            "thumb_image"                 =>  $service->thumb_image,
                            "mobile_image"                  =>  $service->mobile_image,
                            "url"            =>  $service->url,
                            "status" =>  $service->status,
                            "total_views"                  =>  $service->total_views,
                            "rating"                 =>  $service->rating,
                            "rating_count"                  =>  $service->rating_count,
                            "admin_verification"            =>  $service->admin_verification,
                            "created_at" =>  $service->created_at,
                            "updated_at"                  =>  $service->updated_at,
                            "deleted_reason" =>  $service->deleted_reason,
                            "created_by"                  =>  $service->created_by
                        );
                    }
                    $response['posts'] = $posts;

                    echo json_encode($response, TRUE);
                    if (!is_dir('application/cache')) {
                        mkdir('./application/cache', 0777, TRUE);
                    }
                    $fp = fopen('./application/cache/service_array.json', 'w');
                    fwrite($fp, json_encode($response));

                    fclose($fp);
                }
                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Service Cache Details updated successfully');
                redirect(base_url() . 'admin/cache-settings');
            }
        }

        $this->data['page'] = 'cache_system';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function static_cache_system()
    {
        $this->common_model->checkAdminUserPermission(38);
        $data = $this->input->post();

        if ($this->input->post('form_submit')) {
            if ($data) {
                if (isset($data['static_content_cache_system'])) {
                    $data['static_content_cache_system'] = '1';
                } else {
                    $data['static_content_cache_system'] = '0';
                }

                if ($data['static_content_cache_system'] == 1) {

                    $language_data = $this->db->query("SELECT * FROM `language_management` WHERE `language` =  'en';")->result();
                    $data['language_data'] = $language_data;
                    $response = array();
                    $posts = array();
                    foreach ($language_data as $language) {
                        $posts[] = array(
                            "id"                 =>  $language->sno,
                            "lang_key"                  =>  $language->lang_key,
                            "lang_value"            =>  $language->lang_value,
                            "language" =>  $language->language,
                            "type"                  =>  $language->type
                        );
                    }
                    $response['posts'] = $posts;

                    echo json_encode($response, TRUE);
                    if (!is_dir('application/cache')) {
                        mkdir('./application/cache', 0777, TRUE);
                    }
                    $fp = fopen('./application/cache/lang_array.json', 'w');
                    fwrite($fp, json_encode($response));

                    fclose($fp);


                    $app_language_data = $this->db->query("SELECT * FROM `app_language_management` WHERE `language` =  'en';")->result();
                    $data['app_language_data'] = $app_language_data;
                    $response = array();
                    $posts = array();
                    foreach ($app_language_data as $app_language) {
                        $posts[] = array(
                            "id"                 =>  $app_language->sno,
                            "page_key"                  =>  $app_language->page_key,
                            "lang_key"            =>  $app_language->lang_key,
                            "lang_value" =>  $app_language->lang_value,
                            "placeholder"                  =>  $app_language->placeholder,
                            "validation1"                 =>  $app_language->validation1,
                            "validation2"                  =>  $app_language->validation2,
                            "validation3"            =>  $app_language->validation3,
                            "type" =>  $app_language->type,
                            "language"                  =>  $app_language->language
                        );
                    }
                    $response['posts'] = $posts;

                    echo json_encode($response, TRUE);
                    if (!is_dir('application/cache')) {
                        mkdir('./application/cache', 0777, TRUE);
                    }
                    $fp = fopen('./application/cache/applang_array.json', 'w');
                    fwrite($fp, json_encode($response));

                    fclose($fp);
                }

                foreach ($data as $key => $val) {
                    if ($key != 'form_submit') {
                        $this->db->where('key', $key);
                        $this->db->delete('system_settings');
                        $table_data['key'] = $key;
                        $table_data['value'] = $val;
                        $table_data['system'] = 1;
                        $table_data['groups'] = 'config';
                        $table_data['update_date'] = date('Y-m-d');
                        $table_data['status'] = 1;
                        $this->db->insert('system_settings', $table_data);
                    }
                }
                $this->session->set_flashdata('success_message', 'Product Cache Details updated successfully');
                redirect(base_url() . 'admin/cache-settings');
            }
        }
    }


    public function clear_all_cache()
    {
        $CI = &get_instance();
        $path = $CI->config->item('cache_path');

        $cache_path = ($path == '') ? APPPATH . 'cache/' : $path;

        $handle = opendir($cache_path);
        while (($file = readdir($handle)) !== FALSE) {
            //Leave the directory protection alone
            if ($file != '.htaccess' && $file != 'index.html') {
                @unlink($cache_path . '/' . $file);
            }
        }
        closedir($handle);
        $this->session->set_flashdata('success_message', 'Caches Cleared successfully');
        redirect(base_url() . 'admin/cache-settings');
    }

    public function serviceSettings()
    {
        $data = $this->input->post();
        if ($this->input->post('form_submit')) {
            if (isset($data['review_showhide'])) {
                $data['review_showhide'] = 1;
            } else {
                $reviews = $this->db->get_where('system_settings', array('key' => 'review_showhide'))->row()->value;
                $data['review_showhide'] = (isset($data['review_showhide'])) ? $reviews : 0;
            }

            if (isset($data['booking_showhide'])) {
                $data['booking_showhide'] = 1;
            } else {
                $booking = $this->db->get_where('system_settings', array('key' => 'booking_showhide'))->row()->value;
                $data['booking_showhide'] = (isset($data['booking_showhide'])) ? $booking : 0;
            }

            if (isset($data['service_offered_showhide'])) {
                $data['service_offered_showhide'] = 1;
            } else {
                $service_offered = $this->db->get_where('system_settings', array('key' => 'service_offered_showhide'))->row()->value;
                $data['service_offered_showhide'] = (isset($data['service_offered_showhide'])) ? $service_offered : 0;
            }

            if (isset($data['service_availability_showhide'])) {
                $data['service_availability_showhide'] = 1;
            } else {
                $service_availability = $this->db->get_where('system_settings', array('key' => 'service_availability_showhide'))->row()->value;
                $data['service_availability_showhide'] = (isset($data['service_availability_showhide'])) ? $service_availability : 0;
            }

            if (isset($data['provider_email_showhide'])) {
                $data['provider_email_showhide'] = 1;
            } else {
                $provider_email = $this->db->get_where('system_settings', array('key' => 'provider_email_showhide'))->row()->value;
                $data['provider_email_showhide'] = (isset($data['provider_email_showhide'])) ? $provider_email : 0;
            }

            if (isset($data['provider_mobileno_showhide'])) {
                $data['provider_mobileno_showhide'] = 1;
            } else {
                $provider_mobileno = $this->db->get_where('system_settings', array('key' => 'provider_mobileno_showhide'))->row()->value;
                $data['provider_mobileno_showhide'] = (isset($data['provider_mobileno_showhide'])) ? $provider_mobileno : 0;
            }

            if (isset($data['provider_status_showhide'])) {
                $data['provider_status_showhide'] = 1;
            } else {
                $provider_status = $this->db->get_where('system_settings', array('key' => 'provider_status_showhide'))->row()->value;
                $data['provider_status_showhide'] = (isset($data['provider_status_showhide'])) ? $provider_status : 0;
            }

            if ($data['service_offered_showhide'] == 1) {
                $data['service_offered_showhide'] = 1;
            } else {
                $service_offered = $this->db->get_where('system_settings', array('key' => 'service_offered_showhide'))->row()->value;
                if ($data['service_offered_showhide'] == 0) {
                    $data['service_offered_showhide'] = 0;
                }
                $data['service_offered_showhide'] = (isset($data['service_offered_showhide'])) ? $data['service_offered_showhide'] : $service_offered;
            }
            //echo '<pre>'; print_r($data); exit;
            foreach ($data as $key => $val) {
                if ($key != 'form_submit') {
                    $this->db->where('key', $key);
                    $this->db->delete('system_settings');
                    $table_data['key'] = $key;
                    $table_data['value'] = $val;
                    $table_data['system'] = 1;
                    $table_data['groups'] = 'config';
                    $table_data['update_date'] = date('Y-m-d');
                    $table_data['status'] = 1;
                    $exists_data = $this->db->get_where('system_settings', array('key' => $key))->row();

                    if (!$exists_data) {
                        $this->db->insert('system_settings', $table_data);
                    } else {
                        $this->db->where('key', $key);
                        $this->db->update('system_settings', $table_data);
                    }
                }
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Service details updated successfully');
                redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect($_SERVER["HTTP_REFERER"]);
            }
        } else {
            $this->data['page'] = 'service_settings';
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        }
    }

    public function abuse_reports()
    {
        $this->data['list'] = $this->admin->abuse_reports();
        $this->data['page'] = 'abuse_reports';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function abuse_details($id)
    {
        $this->data['page'] = 'abuse_details';
        $this->data['list'] = $this->admin->abuse_reports_list($id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function pageslist()
    {
        $lang_id = $this->db->where('default_language', 1)->get('language')->row()->id;
        $this->data['pages'] = $this->db->order_by('id', 'DESC')->where(array('delete_status' => 1, 'lang_id' => $lang_id))->get('pages_list')->result_array();
        $this->data['page'] = 'pages_list';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function page_list_status()
    {
        $id = $this->input->post('status_id');
        $table_data['visibility'] = $this->input->post('status');
        $this->db->where('id', $id);
        if ($this->db->update('pages_list', $table_data)) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function add_pages()
    {
        if ($this->input->post("form_submit") == true) {
            $table_data['title'] = $this->input->post('title');
            if (empty($this->input->post('pages_slug'))) {
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['title']);
                $table_data['slug'] = strtolower($slug);
            } else {
                $table_data['slug'] = $this->input->post('pages_slug');
            }
            $table_data['description'] = $this->input->post('pages_desc');
            $table_data['keywords'] = $this->input->post('pages_key');
            $table_data['lang_id'] = $this->input->post('pages_lang');
            $table_data['location'] = $this->input->post('pages_loc');
            $table_data['visibility'] = $this->input->post('pages_visibility');
            $table_data['page_content'] = $this->input->post('content');
            $table_data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('pages_list', $table_data);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Pages Added successfully');
                redirect(base_url('admin/' . 'pages-list'));
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url('admin/' . 'pages-list'));
            }
        }

        $this->data['page'] = 'add_pages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function edit_pages($id)
    {
        if ($this->input->post("form_submit") == true) {
            $table_data['title'] = $this->input->post('title');
            if (empty($this->input->post('pages_slug'))) {
                $slug = preg_replace('/[^A-Za-z0-9\-]/', '-', $table_data['title']);
                $table_data['slug'] = strtolower($slug);
            } else {
                $table_data['slug'] = $this->input->post('pages_slug');
            }
            $table_data['description'] = $this->input->post('pages_desc');
            $table_data['keywords'] = $this->input->post('pages_key');
            $table_data['lang_id'] = $this->input->post('pages_lang');
            $table_data['location'] = $this->input->post('pages_loc');
            $table_data['visibility'] = $this->input->post('pages_visibility');
            $table_data['page_content'] = $this->input->post('content');
            $table_data['created_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            $this->db->update('pages_list', $table_data);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Pages Updated successfully');
                redirect(base_url('admin/' . 'pages-list'));
            } else {
                $this->session->set_flashdata('error_message', 'Something went wront, Try again');
                redirect(base_url('admin/' . 'pages-list'));
            }
        }

        $this->data['page'] = 'edit_pages';
        $this->data['pages_val'] = $this->admin->pages_details($id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function pages_delete()
    {
        $inp = $this->input->post();
        $this->db->where('id', $inp['id']);
        $this->db->set('delete_status', 0);
        $this->db->update('pages_list');
        echo json_encode("success");
    }

    public function offline_payment()
    {
        $this->common_model->checkAdminUserPermission(14);
        if ($this->input->post('form_submit')) {
            $data['bank_name']    = $this->input->post('bank_name');
            $data['holder_name'] = $this->input->post('holder_name');
            $data['account_num']            = $this->input->post('account_num');
            $data['ifsc_code']         = $this->input->post('ifsc_code');
            $data['branch_name']            = $this->input->post('branch_name');
            $data['upi_id']         = $this->input->post('upi_id');
            $data['status'] = !empty($this->input->post('offline_show')) ? $this->input->post('offline_show') : 0;
            $data['created_datetime'] = date('Y-m-d H:i:s');
            $data['updated_datetime'] = date('Y-m-d H:i:s');
            $query                    = $this->db->query("SELECT * FROM offline_payment");
            $results                   = $query->row_array();
            if (!empty($results)) {
                $this->db->where('id', '1');
                $this->db->update('offline_payment', $data);
            } else {
                $this->db->insert('offline_payment', $data);
            }
            $this->session->set_flashdata('success_message', 'Offline Payment edited successfully');
            redirect(base_url() . 'admin/offlinepayment/');
        }
        $this->data['page'] = 'offline_payment';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function offlinepaymentdetails()
    {
        $this->data['list'] = $this->admin->result_getall();
        $this->data['page'] = 'offline_payment_details';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function offline_status()
    {
        $id = $this->input->post('status_id');
        $table_data['paid_status'] = $this->input->post('status');
        $this->db->where('id', $id);
        if ($this->db->update('subscription_details', $table_data)) {
            echo "success";
        } else {
            echo "error";
        }
    }

    public function add_provider_session()
    {
        $id = $this->input->post('id');
        $shopid = $this->input->post('shopid');
        $shop_details = $this->db->select('id, shop_name, shop_code, shop_location, shop_latitude, shop_longitude')->where('status', 1)->where('provider_id', $id)->order_by('id', 'DESC')->get('shops')->result_array();
        $this->session->set_userdata('provider_id', $id);
        if (count($shop_details) > 0) {
            $output = '<option value="">Select Shop</option>';

            foreach ($shop_details as $shop) {
                if ($shop['id'] == $shopid) {
                    $selected = 'selected';
                }
                $output .= '<option value="' . $shop['id'] . '" data-location="' . $shop['shop_location'] . '"data-latitude="' . $shop['shop_latitude'] . '"data-longitude="' . $shop['shop_longitude'] . '" "' . $selected . '">' . $shop['shop_name'] . '</option>';
            }
        } else {
            $output = "<option>No Shops available</option>";
        }
        echo $output;
    }
}
