<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Service extends CI_Controller
{

    public $data;

    public function __construct()
    {

        parent::__construct();
        error_reporting(0);
        $this->data['theme'] = 'user';
        $this->data['module'] = 'service';
        $this->data['page'] = '';

        $this->load->model('templates_model');
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->load->helper('push_notifications');
        $this->load->model('api_model', 'api');

        $this->load->model('service_model', 'service');
        $this->load->model('home_model', 'home');
        $this->load->model('employee_model', 'employee');
        // Load pagination library 
        $this->load->library('ajax_pagination');
        // Per page limit 
        $this->perPage = 10;

        $default_language_select = default_language();

        if ($this->session->userdata('user_select_language') == '') {
            $this->data['user_selected'] = $default_language_select['language_value'];
        } else {
            $this->data['user_selected'] = $this->session->userdata('user_select_language');
        }

        $this->data['active_language'] = $active_lang = active_language();

        $lg = custom_language($this->data['user_selected']);

        $this->data['default_language'] = $lg['default_lang'];

        $this->data['user_language'] = $lg['user_lang'];

        $this->user_selected = (!empty($this->data['user_selected'])) ? $this->data['user_selected'] : 'en';

        $this->default_language = (!empty($this->data['default_language'])) ? $this->data['default_language'] : '';

        $this->user_language = (!empty($this->data['user_language'])) ? $this->data['user_language'] : '';


        /* Arabic Language Message */
        $serMsg = (!empty($this->user_language[$this->user_selected]['lg_Service'])) ? $this->user_language[$this->user_selected]['lg_Service'] : $this->default_language['en']['lg_Service'];
        $serimgMsg = (!empty($this->user_language[$this->user_selected]['lg_Service_Image'])) ? $this->user_language[$this->user_selected]['lg_Service_Image'] : $this->default_language['en']['lg_Service_Image'];
        $staffMsg = (!empty($this->user_language[$this->user_selected]['lg_Staff_Details'])) ? $this->user_language[$this->user_selected]['lg_Staff_Details'] : $this->default_language['en']['lg_Staff_Details'];

        $add_Msg = (!empty($this->user_language[$this->user_selected]['lg_Add_Msg'])) ? $this->user_language[$this->user_selected]['lg_Add_Msg'] : $this->default_language['en']['lg_Add_Msg'];
        $edit_Msg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Msg'])) ? $this->user_language[$this->user_selected]['lg_Edit_Msg'] : $this->default_language['en']['lg_Edit_Msg'];
        $del_Msg = (!empty($this->user_language[$this->user_selected]['lg_Delete_Msg'])) ? $this->user_language[$this->user_selected]['lg_Delete_Msg'] : $this->default_language['en']['lg_Delete_Msg'];
        $act_Msg = (!empty($this->user_language[$this->user_selected]['lg_Active_Msg'])) ? $this->user_language[$this->user_selected]['lg_Active_Msg'] : $this->default_language['en']['lg_Active_Msg'];
        $inact_Msg = (!empty($this->user_language[$this->user_selected]['lg_Inactive_Msg'])) ? $this->user_language[$this->user_selected]['lg_Inactive_Msg'] : $this->default_language['en']['lg_Inactive_Msg'];
        $adderrMsg = (!empty($this->user_language[$this->user_selected]['lg_Add_Err'])) ? $this->user_language[$this->user_selected]['lg_Add_Err'] : $this->default_language['en']['lg_Add_Err'];
        $editerrMsg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Err'])) ? $this->user_language[$this->user_selected]['lg_Edit_Err'] : $this->default_language['en']['lg_Edit_Err'];
        $err_Msg = (!empty($this->user_language[$this->user_selected]['lg_Common_Error'])) ? $this->user_language[$this->user_selected]['lg_Common_Error'] : $this->default_language['en']['lg_Common_Error'];

        $this->ser_addmsg = $serMsg . " " . $add_Msg;
        $this->ser_adderrmsg = $serMsg . " " . $adderrMsg;
        $this->ser_editmsg = $serMsg . " " . $edit_Msg;
        $this->ser_editerrmsg = $serMsg . " " . $editerrMsg;
        $this->ser_actmsg = $serMsg . " " . $act_Msg;
        $this->ser_inactmsg = $serMsg . " " . $inact_Msg;
        $this->ser_delmsg = $serMsg . " " . $del_Msg;
        $this->ser_imgdel = $serimgMsg . " " . $del_Msg;

        $this->staff_addmsg = $staffMsg . " " . $add_Msg;
        $this->staff_adderrmsg = $staffMsg . " " . $adderrMsg;
        $this->staff_editmsg = $staffMsg . " " . $edit_Msg;
        $this->staff_editerrmsg = $staffMsg . " " . $editerrMsg;
        $this->staff_delmsg = $staffMsg . " " . $del_Msg;
        $this->errmsg = $err_Msg;

        $this->load->helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor4'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id4',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
    }

    public function index()
    {
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function mail($value = '')
    {
        $this->load->library('email');
        $result = $this->email
            ->from('vignesh.s@dreamguys.co.in')
            ->reply_to('vignesh.s@dreamguys.co.in')    // Optional, an account where a human being reads.
            ->to('vignesh.s@dreamguys.co.in')
            ->subject('hai')
            ->message('asf')
            ->send();
    }

    public function add_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'map_key') {
                    $map_key = $data['map_key'];
                }
            }
        }
        if ($this->input->post('form_submit')) {
            $inputs = array();
            removeTag($this->input->post());

            $config["upload_path"] = './uploads/services/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();

            if ($_FILES["images"]["name"] != '') {
                if (!is_dir('uploads/services')) {
                    mkdir('./uploads/services', 0777, TRUE);
                }
                for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
                    $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
                    $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
                    $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
                    $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
                    $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
                    if ($this->upload->do_upload('file')) {
                        $data = $this->upload->data();
                        $image_url = 'uploads/services/' . $data["file_name"];
                        $upload_url = 'uploads/services/';
                        $service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
                        $service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
                        $thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
                        $mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
                    }
                }
            }



            $inputs['user_id'] = $this->session->userdata('id');
            $inputs['service_title'] = $this->input->post('service_title');
            $inputs['currency_code'] = $this->input->post('currency_code');;
            $inputs['service_sub_title'] = $this->input->post('service_sub_title');
            $inputs['category'] = $this->input->post('category');
            $inputs['subcategory'] = ($this->input->post('subcategory')) ? $this->input->post('subcategory') : '';
            $inputs['service_location'] = $this->input->post('service_location');
            $inputs['service_latitude'] = $this->input->post('service_latitude');
            $inputs['service_longitude'] = $this->input->post('service_longitude');
            $inputs['service_amount'] = $this->input->post('service_amount');
            $inputs['booking_amount'] = $this->input->post('booking_amount');
            $inputs['about'] = $this->input->post('about');
            $inputs['service_image'] = ($service_image) ? implode(',', $service_image) : '';
            $inputs['service_details_image'] = ($service_details_image) ? implode(',', $service_details_image) : '';
            $inputs['thumb_image'] = ($thumb_image) ? implode(',', $thumb_image) : '';
            $inputs['mobile_image'] = ($mobile_image) ? implode(',', $mobile_image) : '';
            $inputs['created_at'] = date('Y-m-d H:i:s');
            $inputs['staff_id'] = $this->input->post('staff_id') ? implode(',', $this->input->post('staff_id')) : '';
            $inputs['shop_id'] = $this->input->post('shop_id');
            $inputs['updated_at'] = date('Y-m-d H:i:s');
            $inputs['created_by'] = 'provider';



            if ($this->input->post('autoschedule')) {
                $inputs['autoschedule'] = $this->input->post('autoschedule');
                $inputs['autoschedule_days']  = $this->input->post('autoschedule_days');
                $inputs['autoschedule_session']  = $this->input->post('autoschedule_session');
            } else {
                $inputs['autoschedule'] = 0;
                $inputs['autoschedule_days']  = 0;
                $inputs['autoschedule_session']  = 0;
            }
            $service_for = $this->input->post('service_for');
            if ($service_for == 2) { // Status As Draft. This Service only for selected user
                $inputs['status']  = 3;
                $inputs['service_for']  = $service_for;
                $inputs['service_for_userid']  = $this->input->post('chatuserid');
            }
            $result = $this->service->create_service($inputs);
            $chatval = '';
            if ($service_for == 2) {
                $userid = $this->input->post('chatuserid');
                $user_token = $this->db->select('token')->from('users')->where('id', $userid)->get()->row()->token;
                $provider_token = $this->db->select('token')->from('providers')->where('id', $inputs['user_id'])->get()->row()->token;
                date_default_timezone_set('UTC');
                $date_time = date('Y-m-d H:i:s');
                date_default_timezone_set('Asia/Kolkata');
                $content = "Requested New Service :- " . $inputs['service_title'] . "<br><br>";
                $url = base_url() . 'service-preview/' . str_replace(' ', '-', $inputs['service_title']) . '?sid=' . md5($result) . '&uid=' . md5($userid);
                $content .= "Click the link for service details <a href='" . $url . "' target='_blank'>" . $inputs['service_title'] . "</a>";

                $chatdata = array(
                    "sender_token" => $provider_token,
                    "receiver_token" => $user_token,
                    "message" => $content,
                    "status" => 1,
                    "read_status" => 0,
                    "utc_date_time" => $date_time,
                    "created_at" => date('Y-m-d H:i:s'),
                );
                $chatval = $this->db->insert("chat_table", $chatdata);
            }

            if (is_countable($_POST['addi_servicename']) && count($_POST['addi_servicename']) > 0) {
                $post = $this->input->post();
                foreach ($post['addi_servicename'] as $key => $value) {
                    $addi_service_data = array(
                        'service_id' => $result,
                        'provider_id' => $this->session->userdata('id'),
                        'service_name' => $post['addi_servicename'][$key],
                        'amount' => $post['addi_serviceamnt'][$key],
                        'duration' => $post['addi_servicedura'][$key],
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('additional_services', $addi_service_data);
                }
            }

            $temp = count($service_image); //counting number of row's
            $service_image = $service_image;
            $service_details_image = $service_details_image;
            $thumb_image = $thumb_image;
            $mobile_image = $mobile_image;
            $service_id = $result;



            for ($i = 0; $i < $temp; $i++) {
                $image = array(
                    'service_id' => $service_id,
                    'service_image' => $service_image[$i],
                    'service_details_image' => $service_details_image[$i],
                    'thumb_image' => $thumb_image[$i],
                    'mobile_image' => $mobile_image[$i]
                );
                $serviceimage = $this->service->insert_serviceimage($image);
            }

            if ($serviceimage == true) {
                if ($chatval) {
                    redirect(base_url() . "user-chat");
                } else {
                    $this->session->set_flashdata('success_message', $this->ser_addmsg);
                    redirect(base_url() . "my-services");
                }
            } else {
                $this->session->set_flashdata('error_message', $this->ser_adderrmsg);
                redirect(base_url() . "add-service");
            }
        }

        $this->data['map_key'] = $map_key;
        $this->data['page'] = 'add_service';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function edit_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }

        $service_id = $this->uri->segment('4');
        $this->data['page'] = 'edit_service';
        $this->data['model'] = 'service';
        $this->data['services'] = $services = $this->service->get_service_id($service_id);
        $this->data['serv_offered'] = $this->db->from('service_offered')->where('service_id', $services['id'])->get()->result_array();

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function notification_view()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $data = array();

        // Get record count 
        $conditions['returnType'] = 'count';

        $totalRec = $this->service->get_full_notification($conditions);
        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('user/service/notificaitonAjaxPagination');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 
        $conditions = array(
            'limit' => $this->perPage
        );
        $this->data['notification_list'] = $notification_list = $this->service->get_full_notification($conditions);
        $this->data['page'] = 'user_notifications';
        $this->data['module'] = 'chat';
        $values = array();
        foreach ($notification_list as $key => $value) {
            $values[$key] = $value;
            $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $value['sender'])->get()->row();

            $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $value['sender'])->get()->row();

            if (!empty($user_table)) {
                $user_info = $user_table;
            } else {
                $user_info = $provider_table;
            }
            if (!empty($user_info) && isset($user_info)) {
                $values[$key]['profile_img'] = $user_info->profile_img;
            }
        }
        $token = $this->session->userdata('chat_token');
        $this->db->where_in('receiver', $token);
        $this->db->set('status', 0);
        $this->db->update('notification_table');
        $this->data['notification_list'] = $values;
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function notificaitonAjaxPagination()
    {
        $page = $this->input->post('page');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }

        // Get record count 
        $conditions['returnType'] = 'count';
        $totalRec = $this->service->get_full_notification($conditions);

        // Pagination configuration 
        $config['target'] = '#dataList';
        $config['base_url'] = base_url('user/service/notificaitonAjaxPagination');
        $config['total_rows'] = $totalRec;
        $config['per_page'] = $this->perPage;

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);

        // Get records 
        $conditions = array(
            'start' => $offset,
            'limit' => $this->perPage
        );
        $this->data['notification_list'] = $notification_list = $this->service->get_full_notification($conditions);
        $values = array();
        foreach ($notification_list as $key => $value) {
            $values[$key] = $value;
            $user_table = $this->db->select('id,name,profile_img,token,type')->from('users')->where('token', $value['sender'])->get()->row();

            $provider_table = $this->db->select('id,name,profile_img,token,type')->from('providers')->where('token', $value['sender'])->get()->row();

            if (!empty($user_table)) {
                $user_info = $user_table;
            } else {
                $user_info = $provider_table;
            }
            if (!empty($user_info->profile_img)) {
                $values[$key]['profile_img'] = $user_info->profile_img;
            } else {
                $values[$key]['profile_img'] = '';
            }
        }
        $this->data['notification_list'] = $values;

        // Load the data list view 
        $this->load->view('user/chat/ajax-data', $this->data, false);
    }

    public function update_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        removeTag($this->input->post());
        $service = ($this->input->post('service_offered')) ? implode(',', $this->input->post('service_offered')) : '';
        $service_offered = json_encode(array($service));

        $inputs = array();

        $config["upload_path"] = './uploads/services/';
        $config["allowed_types"] = '*';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $service_image = array();
        $thumb_image = array();
        $mobile_image = array();

        if (isset($_FILES["images"]) && !empty($_FILES["images"]['name'][0])) {
            $count = count($_FILES["images"]);
            $fcount = count($_FILES["images"]["name"]);
            $profile_count = $this->db->where('service_id', $this->input->post('service_id'))->from('services_image')->count_all_results();
            $total_count = $profile_count + $fcount;
            for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {


                if ($total_count < 10) {
                    $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
                    $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
                    $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
                    $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
                    $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
                    if ($this->upload->do_upload('file')) {
                        $data = $this->upload->data();
                        $image_url = 'uploads/services/' . $data["file_name"];
                        $upload_url = 'uploads/services/';
                        $service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
                        $service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
                        $thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
                        $mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
                    }
                }
            }
        }

        $inputs['service_image'] = ($service_image) ? implode(',', $service_image) : '';
        $inputs['service_details_image'] = ($service_details_image) ? implode(',', $service_details_image) : '';
        $inputs['thumb_image'] = ($thumb_image) ? implode(',', $thumb_image) : '';
        $inputs['mobile_image'] = ($mobile_image) ? implode(',', $mobile_image) : '';

        $inputs['service_title'] = $this->input->post('service_title');
        $inputs['service_sub_title'] = $this->input->post('service_sub_title');
        $inputs['category'] = $this->input->post('category');
        $inputs['subcategory'] = ($this->input->post('subcategory')) ? $this->input->post('subcategory') : '';
        $inputs['service_location'] = $this->input->post('service_location');
        $inputs['service_latitude'] = $this->input->post('service_latitude');
        $inputs['service_longitude'] = $this->input->post('service_longitude');
        $inputs['service_amount'] = $this->input->post('service_amount');

        $inputs['about'] = $this->input->post('about');
        $inputs['currency_code'] = $this->input->post('currency_code');
        $inputs['duration'] = $this->input->post('duration');
        $inputs['duration_in'] = $this->input->post('duration_in');

        $inputs['updated_at'] = date('Y-m-d H:i:s');

        $inputs['staff_id'] = ($this->input->post('staff_id')) ? implode(',', $this->input->post('staff_id')) : '';
        $inputs['shop_id'] = $this->input->post('shop_id');

        $staffId = ($this->input->post('staff_id')) ? implode(',', $this->input->post('staff_id')) : '';
        $shopId  = $this->input->post('shop_id');


        if ($this->input->post('autoschedule')) {
            $autoschedule = $this->input->post('autoschedule');
            $autoschedule_days  = $this->input->post('autoschedule_days');
            $autoschedule_session  = $this->input->post('autoschedule_session');
        } else {
            $autoschedule = 0;
            $autoschedule_days  = 0;
            $autoschedule_session  = 0;
        }

        $service_image = ($service_image) ? implode(',', $service_image) : '';
        $service_details_image = ($service_details_image) ? implode(',', $service_details_image) : '';
        $thumb_image = ($thumb_image) ? implode(',', $thumb_image) : '';
        $mobile_image = ($mobile_image) ? implode(',', $mobile_image) : '';

        $sql = "update services set service_image='" . $service_image . "',service_details_image='" . $service_details_image . "',thumb_image='" . $thumb_image . "',mobile_image='" . $mobile_image . "', service_sub_title='" . $this->input->post('service_sub_title') . "',currency_code='" . $this->input->post('currency_code') . "',category='" . $this->input->post('category') . "',subcategory='" . $this->input->post('subcategory') . "',service_amount='" . $this->input->post('service_amount') . "',service_offered= '" . $service_offered . "',about='" . $this->input->post('about') . "',updated_at='" . date('Y-m-d H:i:s') . "', sub_subcategory='" . $this->input->post('sub_subcategory') . "', duration='" . $this->input->post('duration') . "', duration_in='" . $this->input->post('duration_in') . "', autoschedule ='" . $autoschedule . "', autoschedule_days = '" . $autoschedule_days . "', autoschedule_session = '" . $autoschedule_session . "', staff_id='" . $staffId . "',shop_id='" . $shopId . "' , service_location='" . $this->input->post('service_location') . "',service_latitude='" . $this->input->post('service_latitude') . "',service_longitude='" . $this->input->post('service_longitude') . "' where id='" . $_POST['service_id'] . "'";



        $inputss['service_title'] = $this->input->post('service_title');
        $this->db->update('services', $inputss, array("id" => $_POST['service_id']));

        $result = $this->db->query($sql);
        if (is_countable($_POST['addi_servicename']) && count($_POST['addi_servicename']) > 0) {
            $this->db->where('service_id', $this->input->post('service_id'))->delete('additional_services');
            $post = $this->input->post();
            foreach ($post['addi_servicename'] as $key => $value) {
                $addi_service_data = array(
                    'service_id' => $this->input->post('service_id'),
                    'provider_id' => $this->session->userdata('id'),
                    'service_name' => $post['addi_servicename'][$key],
                    'amount' => $post['addi_serviceamnt'][$key],
                    'duration' => $post['addi_servicedura'][$key],
                    'created_at' => date('Y-m-d H:i:s')
                );
                $this->db->insert('additional_services', $addi_service_data);
                $inserid = $this->db->insert_id();
                if ($post['addiserid'][$key] > 0) {
                    $idupdate['id'] = $post['addiserid'][$key];
                    $idupdate['updated_at'] = date('Y-m-d H:i:s');
                    $this->db->where('id', $inserid);
                    $this->db->update('additional_services', $idupdate);
                }
            }
        }

        if (!empty($service_image)) {
            $temp = count(explode(',', $service_image));
            $service_image = explode(',', $service_image);
            $service_details_image = explode(',', $service_details_image);
            $thumb_image = explode(',', $thumb_image);
            $mobile_image = explode(',', $mobile_image);
            $service_id = $this->input->post('service_id');



            for ($i = 0; $i < $temp; $i++) {
                $image = array(
                    'service_id' => $service_id,
                    'service_image' => $service_image[$i],
                    'service_details_image' => $service_details_image[$i],
                    'thumb_image' => $thumb_image[$i],
                    'mobile_image' => $mobile_image[$i]
                );
                $serviceimage = $this->service->insert_serviceimage($image);
            }
        }

        if ($result) {
            $this->session->set_flashdata('success_message', $this->ser_editmsg);
            redirect(base_url() . 'my-services');
        } else {
            $this->session->set_flashdata('error_message', $this->ser_editerrmsg);
            redirect(base_url() . 'my-services');
        }
    }

    public function sevice_images($value = '')
    {
        if (!empty($_FILES)) {

            $config["upload_path"] = './uploads/services_dummy/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();

            if (isset($_FILES["images0"]) && !empty($_FILES["images0"]['name'][0])) {
                $count = count($_FILES);
                $i = 0;
                for ($count = 0; $count < count($_FILES); $count++) {
                    $j = $i++;
                    $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images" . $j]["name"][$count];
                    $_FILES["file"]["type"] = $_FILES["images" . $j]["type"][$count];
                    $_FILES["file"]["tmp_name"] = $_FILES["images" . $j]["tmp_name"][$count];
                    $_FILES["file"]["error"] = $_FILES["images" . $j]["error"][$count];
                    $_FILES["file"]["size"] = $_FILES["images" . $j]["size"][$count];
                    if ($this->upload->do_upload('file')) {
                        $data = array(
                            'service_id' => $_POST['service_id'],
                            'user_id' => $this->session->userdata('id'),
                            'image_url' => $config["upload_path"] . $_FILES["file"]
                        );
                        $this->db->insert('service_dummy_images', $data);
                    }
                }
            }
        }
    }

    public function delete_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '2';
        $WHERE = array('id' => $s_id);
        $result = $this->service->update_service($inputs, $WHERE);
        if ($result) {
            $message = $this->ser_inactmsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }

    public function delete_inactive_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');
        $inputs['status'] = '0';
        $inputs['deleted_reason'] = $this->input->post('reason');
        $WHERE = array('id' => $s_id);
        $result = $this->service->update_service($inputs, $WHERE);
        if ($result) {
            $message = 'Service deleted successfully';
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = 'Something went wrong.Please try again later.';
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }

    public function delete_active_service()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '1';
        $WHERE = array('id' => $s_id);
        $result = $this->service->update_service($inputs, $WHERE);
        if ($result) {
            $message = 'Service Activate successfully';
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = 'Something went wrong.Please try again later.';
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }

    public function my_services()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $this->data['page'] = 'my_service';
        $this->data['services'] = $this->service->get_service();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function featured_services()
    {
        $this->data['page'] = 'featured_services';
        $this->data['services'] = $this->service->featured_service();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function popular_services()
    {
        $this->data['page'] = 'popular_services';
        $this->data['services'] = $this->service->popular_service();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function update_booking()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $this->data['page'] = 'update_booking';
        $this->data['services'] = $this->service->get_service();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function user_bookingstatus()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $this->data['page'] = 'user_bookingstatus';
        $this->data['services'] = $this->service->get_service();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function update_bookingstatus()
    {
        extract($_POST);
        if (empty($this->session->userdata('id'))) {
            echo "3";
            return false;
        }
        if (!empty($_POST['review'])) {
            $book_details['reason'] = $_POST['review'];
        }
        $book_details['status'] = $this->input->post('status');
        $book_details['id'] = $this->input->post('booking_id');
        $book_details['updated_on'] = (date('Y-m-d H:i:s'));
        if (!empty($this->input->post('booking_id'))) {
            $old_booking_status = $this->db->where('id', $this->input->post('booking_id'))->get('book_service')->row();
            if ($old_booking_status->status == 5 || $old_booking_status->status == 7) {
                $message = 'Something went wrong.User Cancel the Service.';
                echo "2";
                exit;
            }
        }
        $WHERE = array('id' => $this->input->post('booking_id'));
        $result = $this->service->update_bookingstatus($book_details, $WHERE);
        $service_data = $this->db->where('id', $old_booking_status->service_id)->from('services')->get()->row_array();
        $user_data = $this->db->where('id', $old_booking_status->user_id)->from('users')->get()->row_array();
        if ($result) {
            $message = 'Booking updated successfully';

            if ($book_details['status'] == 2) {

                $token = $this->session->userdata('chat_token');


                $this->send_push_notification($token, $book_details['id'], 2, ' Have Inprogress The Service - ' . $service_data['service_title']);
                $success_message = "" . $this->session->userdata('name') . " have Accepted The Service - ( " . $service_data['service_title'] . " ).";
            }

            if ($book_details['status'] == 3) {
                $token = $this->session->userdata('chat_token');
                $this->send_push_notification($token, $book_details['id'], 2, ' Have Completed The Service - ' . $service_data['service_title']);
                $success_message = "" . $this->session->userdata('name') . " have Completed The Service - ( " . $service_data['service_title'] . " )";
                $ginputs['status'] = 3;
                $ginputs['reason'] = '';
                $GPARENT_WHERE = array('guest_parent_bookid' => $this->input->post('booking_id'));
                $cpfresult = $this->service->update_bookingstatus($ginputs, $GPARENT_WHERE);
            }


            if ($book_details['status'] == 7) {
                $token = $this->session->userdata('chat_token');
                $sesstxt = '';
                if ($old_booking_status->autoschedule_session_no > 1) {
                    $sesstxt = ' (Session - ' . $old_booking_status->autoschedule_session_no . ')"';
                }
                $this->send_push_notification($token, $book_details['id'], 2, ' Have Cancelled The Service - ' . $service_data['service_title'] . $sesstxt);
                $success_message = "Sorry to Say! " . $this->session->userdata('name') . " have Cancelled The Service - ( " . $service_data['service_title'] . " )<br>Reason : " . $this->input->post('review') . ". <br> Note : Admin will check and update the status of this booking request";
                if ($old_booking_status->autoschedule_session_no == 1) {
                    $parent_book_details['status'] = 7;
                    $parent_book_details['reason'] = $this->input->post('review');
                    $PARENT_WHERE = array('parent_bookid' => $this->input->post('booking_id'));
                    $autoschedule_result = $this->service->update_bookingstatus($parent_book_details, $PARENT_WHERE);
                    $success_message .= $this->session->userdata('name') . " have Cancelled The Related Session Service - ( " . $service_data['service_title'] . " )";
                    $this->send_push_notification($token, $book_details['id'], 2, ' Have Cancelled The Related Session Services  - ' . $service_data['service_title']);
                }

                /* Cancel More Than One Services Booking for Myself/Guests, If Exists */
                $ginputs['status'] = 7;
                $ginputs['reason'] = $this->input->post('review');
                $GPARENT_WHERE = array('guest_parent_bookid' => $this->input->post('booking_id'));
                $sfresult = $this->service->update_bookingstatus($ginputs, $GPARENT_WHERE);
                if ($sfresult) {
                    $success_message .= $this->session->userdata('name') . " have Cancelled The Related Services of Booked Service - ( " . $service_data['service_title'] . " )";
                    $this->send_push_notification($token, $book_details['id'], 2, ' Have Cancelled The Related Servies of Booked Service  - ' . $service_data['service_title']);
                }
                /* Cancel More Than One Services Booking for Myself/Guests, If Exists */
            }


            //Sending mail after changing booking status
            $this->data['uname'] = $user_data['name'];
            $this->data['success_message'] = $success_message;
            $bodyid = 4;
            $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
            $body = $tempbody_details['template_content'];
            $body = str_replace('{user_name}', $user_data['name'], $body);
            $body = str_replace('{success_message}', $success_message, $body);
            $body = str_replace('{sitetitle}', $this->site_name, $body);
            $preview_link = base_url();
            $body = str_replace('{preview_link}', $preview_link, $body);
            $body .= $qr_message;
            $phpmail_config = settingValue('mail_config');
            if (isset($phpmail_config) && !empty($phpmail_config)) {
                if ($phpmail_config == "phpmail") {
                    $from_email = settingValue('email_address');
                } else {
                    $from_email = settingValue('smtp_email_address');
                }
            }
            $this->load->library('email');
            if (!empty($from_email)) {
                $mail = $this->email
                    ->from($from_email)
                    ->to($user_data['email'])
                    ->subject('Booking Status')
                    ->message($body)
                    ->send();
            }

            echo "1";
        } else {
            $message = 'Something went wrong.Please try again later.';
            echo "2";
        }
    }

    public function update_status_user()
    {
        extract($_POST);
        if (empty($this->session->userdata('id'))) {
            echo "3";
        }

        $book_details['reason'] = $this->input->post('review');
        $book_details['status'] = $this->input->post('status');
        $book_details['id'] = $this->input->post('booking_id');
        $book_details['updated_on'] = (date('Y-m-d H:i:s'));

        if (!empty($this->input->post('booking_id'))) {
            $old_booking_status = $this->db->where('id', $this->input->post('booking_id'))->get('book_service')->row();

            if ($old_booking_status->status == 5 || $old_booking_status->status == 7) {
                echo '2';
                exit;
            }
        }

        $WHERE = array('id' => $this->input->post('booking_id'));

        $result = $this->service->update_bookingstatus($book_details, $WHERE);
        $service_data = $this->db->where('id', $old_booking_status->service_id)->from('services')->get()->row_array();
        $provider_data = $this->db->where('id', $old_booking_status->provider_id)->from('providers')->get()->row_array();
        if ($result) {
            $message = 'Booking updated successfully';

            if ($book_details['status'] == 6) {

                $token = $this->session->userdata('chat_token');


                //COD changes
                $coddata['status'] = 1;
                $this->db->where('book_id', $this->input->post('booking_id'));
                $this->db->update('book_service_cod', $coddata);

                $this->send_push_notification($token, $book_details['id'], $provider_data['type'], ' Have Accepted Your Completed Request For This  Service - ' . $service_data['service_title']);
                $success_message = "" . $this->session->userdata('name') . " have Accepted Your Completed Request For This  Service - ( " . $service_data['service_title'] . " ).Please Check your wallet the amount was credited !";

                /*Reward Notification*/
                $det = $this->db->select('allow_rewards, booking_reward_count')->where('id', $provider_data['id'])->where('status != ', 0)->get('providers')->row_array();
                if ($det['allow_rewards'] == 1) {
                    $rwdcnt = $det['booking_reward_count'];
                    $rwdcnt1 = $rwdcnt + 1;
                    $totbook = $this->db->select("COUNT(S.id) AS total_count")->from('book_service S')->where('S.user_id', $this->session->userdata('id'))->where('S.provider_id', $provider_data['id'])->where('S.status', 6)->get()->row_array();


                    if ($totbook['total_count'] != 0 && $totbook['total_count'] < $rwdcnt1) {
                        if ($totbook['total_count'] == $rwdcnt) {
                            $pushmsg = $provider_data['name'] . ' will give rewards for next booking';
                        } else {
                            $tcnt = intval($rwdcnt) - intval($totbook['total_count']);
                            $pushmsg = $this->session->userdata('name') . ', you have ' . $tcnt . ' booking(s) left to get rewards from ' . $provider_data['name'];
                        }

                        $notifydata = array('receiver' => $this->session->userdata('chat_token'), 'sender' => $provider_data['token'], 'message' => $pushmsg, 'status' => 1, 'created_at' => date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
                        $this->db->insert('notification_table', $notifydata);
                    }
                }
                /* Completed More Than One Services Booking for Myself/Guests, If Exists */
                $ginputs['status'] = 6;
                $GPARENT_WHERE = array('guest_parent_bookid' => $this->input->post('booking_id'));
                $cfresult = $this->service->update_bookingstatus($ginputs, $GPARENT_WHERE);

                /* Completed More Than One Services Booking for Myself/Guests, If Exists */
            }

            if ($book_details['status'] == 5) {

                $token = $this->session->userdata('chat_token');
                $sesstxt = '';
                if ($old_booking_status->autoschedule_session_no > 1) {
                    $sesstxt = ' (Session - ' . $old_booking_status->autoschedule_session_no . ')"';
                }
                $this->send_push_notification($token, $book_details['id'], $provider_data['type'], ' Have Rejected The Service - ' . $service_data['service_title'] . $sesstxt);
                $success_message = "Sorry to Say! " . $this->session->userdata('name') . " have Rejected The Service - ( " . $service_data['service_title'] . " )<br>Reason : " . $this->input->post('review') . " <br> Note : Admin will check and update the status of this booking request";

                if ($old_booking_status->autoschedule_session_no == 1) {
                    $parent_book_details['status'] = 5;
                    $parent_book_details['reason'] = $this->input->post('review');
                    $PARENT_WHERE = array('parent_bookid' => $this->input->post('booking_id'));
                    $autoschedule_result = $this->service->update_bookingstatus($parent_book_details, $PARENT_WHERE);
                    $success_message .= $this->session->userdata('name') . " have Rejected The Related Session Service - ( " . $service_data['service_title'] . " )";
                    $this->send_push_notification($token, $book_details['id'], $provider_data['type'], ' Have Rejected The Related Session Services - ' . $service_data['service_title']);
                }
                /* Cancel More Than One Services Booking for Myself/Guests, If Exists */
                $ginputs['status'] = 5;
                $ginputs['reason'] = $this->input->post('review');
                $GPARENT_WHERE = array('guest_parent_bookid' => $this->input->post('booking_id'));
                $rfresult = $this->service->update_bookingstatus($ginputs, $GPARENT_WHERE);
                if ($rfresult) {
                    $success_message .= $this->session->userdata('name') . " have Rejected The Related Servies of Booked Service - ( " . $service_data['service_title'] . " )";
                    $this->send_push_notification($token, $book_details['id'], $provider_data['type'], ' Have Rejected The Related Services of Booked Service - ' . $service_data['service_title']);
                }
                /* Cancel More Than One Services Booking for Myself/Guests, If Exists */
            }

            //Sending mail after changing booking status
            $this->data['uname'] = $provider_data['name'];
            $this->data['success_message'] = $success_message;
            $bodyid = 4;
            $tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
            $body = $tempbody_details['template_content'];
            $body = str_replace('{user_name}', $provider_data['name'], $body);
            $body = str_replace('{success_message}', $success_message, $body);
            $body = str_replace('{sitetitle}', $this->site_name, $body);
            $preview_link = base_url();
            $body = str_replace('{preview_link}', $preview_link, $body);

            $phpmail_config = settingValue('mail_config');
            if (isset($phpmail_config) && !empty($phpmail_config)) {
                if ($phpmail_config == "phpmail") {
                    $from_email = settingValue('email_address');
                } else {
                    $from_email = settingValue('smtp_email_address');
                }
            }
            $this->load->library('email');
            if (!empty($from_email)) {
                $mail = $this->email
                    ->from($from_email)
                    ->to($provider_data['email'])
                    ->subject('Booking Status')
                    ->message($body)
                    ->send();
            }

            if ($book_details['status'] == 7) {

                $token = $this->session->userdata('chat_token');

                $this->send_push_notification($token, $book_details['id'], $provider_data['type'], ' Has Rejected The Service');
            }

            echo "1";
        } else {
            echo '2';
        }
    }

    public function book_service()
    {

        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'map_key') {
                    $map_key = $data['map_key'];
                }
            }
        }
        $this->data['map_key'] = $map_key;
        $this->data['page'] = 'book_service';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function service_availability()
    {
        $booking_dates = json_decode($this->input->post('dates'));
        $provider_id = $this->input->post('provider_id');
        $service_id = $this->input->post('service_id');

        $slots = [];
        $start_date = new DateTime($booking_dates[0]);
        $end_date = new DateTime(end($booking_dates));

        $interval = new DateInterval('P1D'); // 1 day interval
        $date_range = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // +1 day to include end date

        $booked_slots = $this->service->get_booked_slots($service_id, $provider_id);

        $bookedSlotsArray = [];

        foreach ($booked_slots as $booked_slot) {
            $slots_for_day = $booked_slot['slots'] != "" ? json_decode($booked_slot['slots'], true) : [];
            $bookedSlotsArray = array_merge($bookedSlotsArray, $slots_for_day);
        }

        foreach ($date_range as $date) {
            $slot1 = $date->format('Y-m-d') . ', 12:00 AM - 12:00 PM';
            $slot2 = $date->format('Y-m-d') . ', 12:00 PM - 12:00 AM';

            $slots[] = $slot1;
            $slots[] = $slot2;
        }

        // Remove booked slots from available slots
        $slots = array_diff($slots, $bookedSlotsArray);

        // Return slots as JSON response
        echo json_encode(array_values($slots)); // Resetting array keys to start from 0
    }

    public function service_availabilityOld()
    {

        $booking_date = $this->input->post('date');
        $provider_id = $this->input->post('provider_id');
        $service_id = $this->input->post('service_id');

        $timestamp = strtotime($booking_date);
        $day = date('D', $timestamp);
        $provider_details = $this->service->provider_hours($provider_id);


        $availability_details = json_decode($provider_details['availability'], true);


        $alldays = false;
        foreach ($availability_details as $details) {

            if (isset($details['day']) && $details['day'] == 0) {

                if (isset($details['from_time']) && !empty($details['from_time'])) {

                    if (isset($details['to_time']) && !empty($details['to_time'])) {
                        $from_time = $details['from_time'];
                        $to_time = $details['to_time'];
                        $alldays = true;
                        break;
                    }
                }
            }
        }

        if ($alldays == false) {


            if ($day == 'Mon') {
                $weekday = '1';
            } elseif ($day == 'Tue') {
                $weekday = '2';
            } elseif ($day == 'Wed') {
                $weekday = '3';
            } elseif ($day == 'Thu') {
                $weekday = '4';
            } elseif ($day == 'Fri') {
                $weekday = '5';
            } elseif ($day == 'Sat') {
                $weekday = '6';
            } elseif ($day == 'Sun') {
                $weekday = '7';
            } elseif ($day == '0') {
                $weekday = '0';
            }


            foreach ($availability_details as $availability) {

                if ($weekday == $availability['day'] && $availability['day'] != 0) {

                    $availability_day = $availability['day'];
                    $from_time = $availability['from_time'];
                    $to_time = $availability['to_time'];
                }
            }
        }

        if (!empty($from_time)) {
            $temp_start_time = $from_time;
            $temp_end_time = $to_time;
        } else {
            $temp_start_time = '';
            $temp_end_time = '';
        }


        $start_time_array = '';
        $end_time_array = '';


        $timestamp_start = strtotime($temp_start_time);
        $timestamp_end = strtotime($temp_end_time);

        $timing_array = array();

        $counter = 1;

        $from_time_railwayhrs = date('G:i:s', ($timestamp_start));
        $to_time_railwayhrs = date('G:i:s', ($timestamp_end));

        $timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
        $timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);


        $i = 1;
        while ($timestamp_start_railwayhrs < $timestamp_end_railwayhrs) {

            $temp_start_time_ampm = date('G:i:s', ($timestamp_start_railwayhrs));
            $temp_end_time_ampm = date('G:i:s', (($timestamp_start_railwayhrs) + 60 * 60 * 1));

            $timestamp_start_railwayhrs = strtotime($temp_end_time_ampm);

            $timing_array[] = array('id' => $i, 'start_time' => $temp_start_time_ampm, 'end_time' => $temp_end_time_ampm);

            if ($counter > 24) {
                break;
            }

            $counter += 1;
            $i++;
        }


        // Booking availability


        $service_date = $booking_date;



        $booking_count = $this->service->get_bookings($service_date, $service_id);



        $new_timingarray = array();

        if (is_array($booking_count) && empty($booking_count)) {
            $new_timingarray = $timing_array;
        } elseif (is_array($booking_count) && $booking_count != '') {
            foreach ($timing_array as $timing) {
                $match_found = false;

                $explode_st_time = explode(':', $timing['start_time']);
                $explode_value = $explode_st_time[0];

                $explode_endtime = explode(':', $timing['end_time']);
                $explode_endval = $explode_endtime[0];


                if (strlen($explode_value) == 1) {
                    $timing['start_time'] = "0" . $explode_st_time[0] . ":" . $explode_st_time[1] . ":" . $explode_st_time[2];
                }

                if (strlen($explode_endval) == 1) {
                    $timing['end_time'] = "0" . $explode_endtime[0] . ":" . $explode_endtime[1] . ":" . $explode_endtime[2];
                }

                foreach ($booking_count as $bookings) {


                    if ($timing['start_time'] == $bookings['from_time'] && $timing['end_time'] == $bookings['to_time']) {


                        $match_found = true;
                        break;
                    }
                }

                if ($match_found == false) {
                    $new_timingarray[] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
                }
            }
        }

        $new_timingarray = array_filter($new_timingarray);

        if (!empty($new_timingarray)) {
            $i = 1;
            foreach ($new_timingarray as $booked_time) {

                $re = strtotime($booked_time['start_time']);
                $re1 = strtotime($booked_time['end_time']);
                date_default_timezone_set('Asia/Kolkata');
                if (date('Y-m-d', strtotime($_POST['date'])) == date('Y-m-d')) {
                    $current_time = strtotime(date('H:i:s'));
                    if (strtotime($booked_time['start_time']) > $current_time) {

                        $st_time = date('h:i A', strtotime($booked_time['start_time']));
                        $end_time = date('h:i A', strtotime($booked_time['end_time']));
                    } else {
                        $st_time = '';
                        $end_time = '';
                    }
                } else {

                    $st_time = date('h:i A', strtotime($booked_time['start_time']));
                    $end_time = date('h:i A', strtotime($booked_time['end_time']));
                }


                if (!empty($st_time)) {
                    $time['start_time'] = $st_time;
                    $time['end_time'] = $end_time;
                    $service_availability[] = $time;
                    $i++;
                }
            }
        } else {
            $service_availability = '';
        }
        if (!isset($service_availability)) {
            $service_availability = '';
        }

        echo json_encode($service_availability);
    }

    public function booking()
    {
        removeTag($this->input->post());
        $timestamp_from = strtotime($this->input->post('from_time'));
        $timestamp_to = strtotime($this->input->post('to_time'));

        $charges_array = array();

        $amount = $this->input->post['service_amount'];
        $amount = ($amount * 100);
        $charges_array['amount'] = $amount;
        $charges_array['currency'] = 'USD';
        $charges_array['description'] = $this->input->post['notes'];



        $user_post_data['currency_code'] = $this->input->post('currency_code');;
        $user_post_data['service_id'] = $this->input->post('service_id');
        $user_post_data['provider_id'] = $this->input->post('provider_id');
        $user_post_data['service_date'] = date('Y-m-d', strtotime($this->input->post('booking_date')));
        $user_post_data['user_id'] = $this->session->userdata('id');
        $user_post_data['amount'] = $this->input->post('service_amount');
        $user_post_data['request_date'] = date('Y-m-d H:i:s');
        $user_post_data['request_time'] = time();
        $user_post_data['from_time'] = date('G:i:s', ($timestamp_from));
        $user_post_data['to_time'] = date('G:i:s', ($timestamp_to));
        $user_post_data['location'] = $this->input->post('service_location');
        $user_post_data['latitude'] = $this->input->post('service_latitude');
        $user_post_data['longitude'] = $this->input->post('service_longitude');
        $user_post_data['notes'] = $this->input->post('notes');



        $insert_booking = $this->service->insert_booking($user_post_data);
    }

    public function user_dashboard()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }

        $user_id = $this->session->userdata('id');
        $this->data['all_bookings'] = $this->home->get_bookinglist_user($user_id);
        $this->data['completed_bookings'] = $this->home->completed_bookinglist_user($user_id);
        $this->data['inprogress_bookings'] = $this->home->inprogress_bookinglist_user($user_id);
        $this->data['accepted_bookings'] = $this->home->accepted_bookinglist_user($user_id);
        $this->data['cancelled_bookings'] = $this->home->cancelled_bookinglist_user($user_id);
        $this->data['cancelled_bookings'] = $this->home->cancelled_bookinglist_user($user_id);
        $this->data['rejected_bookings'] = $this->home->rejected_bookinglist_user($user_id);
        $this->data['page'] = 'user_dashboard';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function provider_dashboard()
    {

        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }

        $this->data['page'] = 'provider_dashboard';
        $provider_id = $this->session->userdata('id');
        $this->data['all_bookings'] = $this->home->get_bookinglist($provider_id);
        $this->data['completed_bookings'] = $this->home->completed_bookinglist($provider_id);
        $this->data['inprogress_bookings'] = $this->home->inprogress_bookinglist($provider_id);
        $this->data['cancelled_bookings'] = $this->home->cancelled_bookinglist($provider_id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function booking_details()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $this->data['page'] = 'booking_details';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function booking_details_user()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }

        $this->data['page'] = 'booking_details_user';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function get_category()
    {
        $this->db->where('status', 1);
        $query = $this->db->get('categories');
        $result = $query->result();
        $data = array();
        foreach ($result as $r) {
            $data['value'] = $r->id;
            $data['label'] = $r->category_name;
            $json[] = $data;
        }
        echo json_encode($json);
    }

    public function get_subcategory()
    {
        $this->db->where('status', 1);
        $this->db->where('category', $_POST['id']);
        $query = $this->db->get('subcategories');
        $result = $query->result();
        $data = array();
        if (!empty($result)) {
            foreach ($result as $r) {
                $data['value'] = $r->id;
                $data['label'] = $r->subcategory_name;
                $json[] = $data;
            }
        } else {
            /*$this->db->insert('subcategories', ['category' => $_POST['id'], 'subcategory_name' => "Others", 'status' => 1]);
            $data['value'] = $this->db->insert_id();
            $data['label'] = 'Others';*/
            $json = [];
        }
        echo json_encode($json);
    }

    public function get_subsubcategory()
    {
        $this->db->where('status', 1);
        $this->db->where('category', $_POST['category_id']);
        $this->db->where('subcategory', $_POST['subcategory_id']);
        $query = $this->db->get('sub_subcategories');
        $result = $query->result();
        $data = array();
        if (!empty($result)) {
            foreach ($result as $r) {
                $data['value'] = $r->id;
                $data['label'] = $r->sub_subcategory_name;
                $json[] = $data;
            }
        }
        echo json_encode($json);
    }

    public function image_resize($width = 0, $height = 0, $image_url, $filename, $upload_url)
    {

        $source_path = base_url() . $image_url;
        list($source_width, $source_height, $source_type) = getimagesize($source_path);
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $height;
            $temp_width = (int) ($height * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $width;
            $temp_height = (int) ($width / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
            $temp_gdim,
            $source_gdim,
            0,
            0,
            0,
            0,
            $temp_width,
            $temp_height,
            $source_width,
            $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
            $desired_gdim,
            $temp_gdim,
            0,
            0,
            $x0,
            $y0,
            $width,
            $height
        );

        /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */

        $image_url = $upload_url . $filename;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }

    /* push notification */

    public function send_push_notification($token, $service_id, $type, $msg = '')
    {


        $data = $this->api->get_book_info($service_id);
        if (!empty($data)) {
            if ($type == 1) {
                $device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 1);
            } else if ($type == 3) {
                $device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 3);
            } else {
                $device_tokens = $this->api->get_device_info_multiple($data['user_id'], 2);
            }

            if ($type == 2) {
                $user_info = $this->api->get_user_info($data['user_id'], $type);
                $ptype = $this->db->select('type')->where('id', $data['provider_id'])->get('providers')->row()->type;
                $name = $this->api->get_user_info($data['provider_id'], $ptype);
            } else {
                $name = $this->api->get_user_info($data['user_id'], 2);

                $user_info = $this->api->get_user_info($data['provider_id'], $type);
            }




            $msg = $name['name'] . ' ' . $msg;
            if (!empty($user_info['token'])) {
                $this->api->insert_notification($token, $user_info['token'], $msg);
            }

            $title = $data['service_title'];


            if (!empty($device_tokens)) {
                foreach ($device_tokens as $key => $device) {
                    if (!empty($device['device_type']) && !empty($device['device_id'])) {

                        if (strtolower($device['device_type']) == 'android') {

                            $notify_structure = array(
                                'title' => $title,
                                'message' => $msg,
                                'image' => 'test22',
                                'action' => 'test222',
                                'action_destination' => 'test222',
                            );

                            sendFCMMessage($notify_structure, $device['device_id']);
                        }

                        if (strtolower($device['device_type'] == 'ios')) {
                            $notify_structure = array(
                                'alert' => $msg,
                                'sound' => 'default',
                                'badge' => 0,
                            );


                            sendApnsMessage($notify_structure, $device['device_id']);
                        }
                    }
                }
            }


            /* apns push notification */
        } else {
            $this->token_error();
        }
    }

    public function get_state_details()
    {
        if (!empty($_POST['id'])) {
            $state = $this->db->where('country_id', $_POST['id'])->from('state')->get()->result_array();
            if (!empty($state)) {
                echo json_encode($state);
                exit;
            } else {
                $state = [];
                echo json_encode($state);
                exit;
            }
        }
    }

    public function get_city_details()
    {
        if (!empty($_POST['id'])) {
            $state = $this->db->where('state_id', $_POST['id'])->from('city')->get()->result_array();
            if (!empty($state)) {
                echo json_encode($state);
                exit;
            } else {
                $state = [];
                echo json_encode($state);
                exit;
            }
        }
    }

    public function check_service_title()
    {
        $user_data = $this->input->post();
        $input['service_title'] = $user_data['service_title'];
        if (!empty($_POST['sid'])) {
            $this->db->where('id !=', $_POST['sid']);
        }
        $service_count = $this->db->where('service_title', $input['service_title'])->count_all_results('services');
        if ($service_count > 0) {
            $isAvailable = FALSE;
        } else {
            $isAvailable = TRUE;
        }
        echo json_encode(
            array(
                'valid' => $isAvailable
            )
        );
    }

    /* Staff Starts Here */
    public function get_selected_category()
    {
        $user_id = $this->session->userdata('id');
        $category = $this->db->select('category')->where('id', $user_id)->get('providers')->row()->category;
        $this->db->where('status', 1);
        $this->db->where('id', $category);
        $query = $this->db->get('categories');
        $result = $query->result();
        $data = array();
        foreach ($result as $r) {
            $data['value'] = $r->id;
            $data['label'] = $r->category_name;
            $json[] = $data;
        }
        echo json_encode($json);
    }

    public function get_selected_subcategory()
    {
        $user_id = $this->session->userdata('id');
        if ($_POST['id'] != '') {
            $category = $_POST['id'];
        } else {
            $category = $this->db->select('category')->where('id', $user_id)->get('providers')->row()->category;
        }
        $this->db->where('status', 1);
        $this->db->where('category', $category);
        $query = $this->db->get('subcategories');
        $result = $query->result();
        $data = array();
        if (!empty($result)) {
            foreach ($result as $r) {
                $data['value'] = $r->id;
                $data['label'] = $r->subcategory_name;
                $json[] = $data;
            }
        } else {
            $this->db->insert('subcategories', ['category' => $category, 'subcategory_name' => "Others", 'status' => 1]);
            $data['value'] = $this->db->insert_id();
            $data['label'] = 'Others';
            $json[] = $data;
        }
        echo json_encode($json);
    }
    public function get_sub_subcategory()
    {
        $user_id = $this->session->userdata('id');
        if ($_POST['id'] != '') {
            $category = $_POST['cid'];
            $subcategory = $_POST['id'];
        } else {
            $category = $this->db->select('category')->where('id', $user_id)->get('providers')->row()->category;
            $subcategory = $this->db->select('subcategory')->where('id', $user_id)->get('providers')->row()->subcategory;
        }

        $this->db->where('status', 1);
        $this->db->where('category', $category);
        $this->db->where('subcategory', $subcategory);
        $query = $this->db->get('sub_subcategories');
        $result = $query->result();
        $data = array();
        if (!empty($result)) {
            foreach ($result as $r) {
                $data['value'] = $r->id;
                $data['label'] = $r->sub_subcategory_name;
                $json[] = $data;
            }
        } else {
            $this->db->insert('sub_subcategories', ['category' => $category, 'subcategory' => $subcategory, 'sub_subcategory_name' => "Others", 'status' => 1]);
            $data['value'] = $this->db->insert_id();
            $data['label'] = 'Others';
            $json[] = $data;
        }
        echo json_encode($json);
    }
    public function staff_content()
    {

        // if ($this->session->userdata('role') == 1){

        //   $user_id = $this->session->userdata('provider_id');
        // } else {
        //   $user_id = $this->session->userdata('id');
        // }
        $user_id = $this->session->userdata('id');

        $category = $_POST['cid'];
        $subcategory = $_POST['sid'];
        $ssubcategory = $_POST['ssid'];
        $this->db->select('e.id, e.first_name, e.last_name, e.designation, s.shop_code');
        $this->db->join('shops s', 's.id = e.shop_id', 'LEFT');
        $this->db->where('e.status', 1);
        $this->db->where('e.delete_status', 0);
        //$this->db->where('e.category', $category);
        //$this->db->where('e.subcategory', $subcategory);
        //$this->db->where("FIND_IN_SET('".$ssubcategory."', e.sub_subcategory)");

        // $this->db->where_in('e.shop_id', $_POST['id']);
        $this->db->where('e.shop_id', $_POST['id']);
        $this->db->where('e.provider_id', $user_id);
        $this->db->order_by('e.shop_id', 'DESC');
        $query = $this->db->get('employee_basic_details e');

        $result = $query->result();

        $data = array();

        if (!empty($result)) {
            foreach ($result as $r) {
                $data['value'] = $r->id;
                $data['label'] = $r->first_name;
                $data['sublabel'] = $r->shop_code;
                $json[] = $data;
            }
        }
        // print_r( json_encode($json));
        // exit;
        echo json_encode($json);
    }
    public function staff_settings()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $this->data['page'] = 'staff_settings';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function staff_check_emailid()
    {
        $inputs = $_POST['userEmail'];
        $this->db->like('email', $inputs, 'match');
        if (!empty($_POST['sid'])) {
            $this->db->where('id !=', $_POST['sid']);
        }
        $count = $this->db->count_all_results('employee_basic_details');
        echo json_encode(['data' => $count]);
    }
    public function staff_check_mobile()
    {
        $inputs = $_POST['userMobile'];
        $ctrycode = $_POST['mobileCode'];


        $this->db->where(array('country_code' => $ctrycode))->like('contact_no', $inputs, 'match', 'after');
        if (!empty($_POST['sid'])) {
            $this->db->where('id !=', $_POST['sid']);
        }
        $count = $this->db->count_all_results('employee_basic_details');
        echo json_encode(['data' => $count]);
    }

    public function add_staff()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }

        $getShop = $this->db->where('provider_id', $this->session->userdata('id'))->where('status', 1)->get('shops')->result_array();
        if (count($getShop) == 0) {
            redirect(base_url() . 'shop');
        }

        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'currency_option') {
                    $currency_option = $data['value'];
                }
                if ($data['key'] == 'map_key') {
                    $map_key = $data['map_key'];
                }
            }
        }

        if ($this->input->post('form_submit')) {
            $inputs = array();

            removeTag($this->input->post());



            $user_id = $this->session->userdata('id');
            $inputs['provider_id'] = $user_id;
            $inputs['first_name'] = $this->input->post('firstname');


            $inputs['country_code'] = $this->input->post('country_code');
            $inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');
            $inputs['gender'] = $this->input->post('gender');



            $inputs['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));
            $inputs['emp_token'] = $this->api->getToken(14, $user_id);
            $inputs['status'] = 1;
            if ($this->input->post('home_service_shop')) {
                $inputs['shop_service'] = $this->input->post('home_service_shop');
            } else {
                $inputs['shop_service'] = 0;
            }
            if ($this->input->post('home_service_home')) {
                $inputs['home_service'] = $this->input->post('home_service_home');
                $inputs['home_service_area'] = $this->input->post('selected_area');
            } else {
                $inputs['home_service'] = 0;
                $inputs['home_service_area'] = '';
            }

            /*$inputs['category']  = $this->input->post('category');
			$inputs['subcategory']  = $this->input->post('subcategory');
			$inputs['sub_subcategory']  = implode(",",$this->input->post('sub_subcategory'));*/
            $inputs['about_emp']  = $this->input->post('about');
            $inputs['created_at'] = date('Y-m-d H:i:s');
            $inputs['created_by'] = $this->session->userdata('id');
            $inputs['updated_at'] = date('Y-m-d H:i:s');
            $inputs['shop_id']  = $this->input->post('shop_id');
            $array = array();
            $inputs['availability'] = $this->input->post('availability');

            if (!empty($inputs['availability'][0]['day'])) {
                $from = $inputs['availability'][0]['from_time'];
                $to = $inputs['availability'][0]['to_time'];
                for ($i = 1; $i <= 7; $i++) {
                    $array[$i] = array('day' => $i, 'from_time' => $from, 'to_time' => $to);
                }
            } else {
                if (!empty($inputs['availability'][0])) {
                    unset($inputs['availability'][0]);
                }
                $array = array_map('array_filter', $inputs['availability']);
                $array = array_filter($array);
            }
            if (!empty($array)) {
                $array = array_values($array);
            }
            if (empty($inputs['availability'][0]['from_time']) && empty($inputs['availability'][0]['to_time'])) {
                $inputs['all_days'] = 0;
            } else {
                $inputs['all_days'] = 1;
            }
            $inputs['availability'] = json_encode($array);


            $result = $this->employee->create_staff($inputs);

            if (!empty($result) && $result > 0) {
                $this->session->set_flashdata('success_message', $this->staff_addmsg);
                redirect(base_url() . "staff-settings");
            } else {
                $this->session->set_flashdata('error_message', $this->staff_adderrmsg);
                redirect(base_url() . "staff-settings");
            }
        }

        $this->data['country_list'] = $this->db->where('status', 1)->order_by('country_name', "ASC")->get('country_table')->result_array();

        $this->data['map_key'] = $map_key;
        $this->data['page'] = 'add_staff';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function edit_staff()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $staff_id = $this->uri->segment('2');
        $provider_id = $this->session->userdata('id');
        $this->data['page'] = 'edit_staff';
        $this->data['model'] = 'service';
        $this->data['staff_id'] = $staff_id;
        $this->data['staff_details'] = $this->employee->get_single_staff($provider_id, $staff_id);
        $this->data['serv_details'] = $this->employee->single_staff_Service($provider_id, $staff_id);
        $this->data['country_list'] = $this->db->where('status', 1)->order_by('country_name', "ASC")->get('country_table')->result_array();
        $this->data['country'] = $this->db->select('id,country_name')->from('country_table')->order_by('country_name', 'asc')->get()->result_array();
        $this->data['city'] = $this->db->select('id,name')->from('city')->get()->result_array();
        $this->data['state'] = $this->db->select('id,name')->from('state')->get()->result_array();

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function update_staff()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'currency_option') {
                    $currency_option = $data['value'];
                }
                if ($data['key'] == 'map_key') {
                    $map_key = $data['map_key'];
                }
            }
        }

        $inputs = array();

        removeTag($this->input->post());


        $user_id = $this->session->userdata('id');
        $staff_id = $_POST['staff_id'];
        $inputs['provider_id'] = $user_id;
        $inputs['first_name'] = $this->input->post('firstname');
        $inputs['country_code']  = $this->input->post('countryCode');
        $inputs['contact_no'] = $this->input->post('mobileno');
        $inputs['email'] = $this->input->post('email');
        $inputs['gender'] = $this->input->post('gender');
        $inputs['dob'] = date('Y-m-d', strtotime($this->input->post('dob')));

        if ($this->input->post('home_service_shop')) {
            $inputs['shop_service'] = $this->input->post('home_service_shop');
        } else {
            $inputs['shop_service'] = 0;
        }
        if ($this->input->post('home_service_home')) {
            $inputs['home_service'] = $this->input->post('home_service_home');
            $inputs['home_service_area'] = $this->input->post('selected_area');
        } else {
            $inputs['home_service'] = 0;
            $inputs['home_service_area'] = '';
        }

        /*$inputs['category']  = $this->input->post('category');
		$inputs['subcategory']  = $this->input->post('subcategory');
		$inputs['sub_subcategory']  = implode(",",$this->input->post('sub_subcategory'));*/
        $inputs['about_emp']  = $this->input->post('about');
        $inputs['updated_at'] = date('Y-m-d H:i:s');
        $inputs['shop_id']  = $this->input->post('shop_id');
        $array = array();
        $inputs['availability'] = $this->input->post('availability');

        if (!empty($inputs['availability'][0]['day'])) {
            $from = $inputs['availability'][0]['from_time'];
            $to = $inputs['availability'][0]['to_time'];
            for ($i = 1; $i <= 7; $i++) {
                $array[$i] = array('day' => $i, 'from_time' => $from, 'to_time' => $to);
            }
        } else {
            if (!empty($inputs['availability'][0])) {
                unset($inputs['availability'][0]);
            }
            $array = array_map('array_filter', $inputs['availability']);
            $array = array_filter($array);
        }
        if (!empty($array)) {
            $array = array_values($array);
        }
        if (empty($inputs['availability'][0]['from_time']) && empty($inputs['availability'][0]['to_time'])) {
            $inputs['all_days'] = 0;
        } else {
            $inputs['all_days'] = 1;
        }
        $inputs['availability'] = json_encode($array);



        $result = $this->employee->update_staff($inputs, $staff_id, $user_id);



        if ($result) {

            $this->session->set_flashdata('success_message', $this->staff_editmsg);
            redirect(base_url() . "staff-settings");
        } else {
            $this->session->set_flashdata('error_message', $this->staff_editerrmsg);
            redirect(base_url() . "staff-settings");
        }
    }
    public function staff_details()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $staff_id = $this->uri->segment('2');
        $provider_id = $this->session->userdata('id');
        $this->data['page'] = 'staff_details';
        $this->data['model'] = 'service';
        $this->data['staff_id'] = $staff_id;
        $this->data['staffdetail'] = $this->employee->get_single_staff($provider_id, $staff_id);
        $this->data['serv_detail'] = $this->employee->single_staff_Service($provider_id, $staff_id);

        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function change_user_status()
    {
        if ($_POST['action'] == 'Inactive') {
            $data['status'] = 2;
        } else {
            $data['status'] = 1;
        }
        $results = $this->db->update('employee_basic_details', $data, array('id' => $_POST['id']));
        return $results;
    }
    public function delete_staff()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('id');
        $u_id = $this->session->userdata('id');

        $WHERE = array('id' => $s_id, 'provider_id' => $u_id);
        $EMPWHERE = array('emp_id' => $s_id, 'provider_id' => $u_id);
        $result = $this->employee->delete_staff($WHERE, $EMPWHERE);
        if ($result) {
            $message = $this->staff_delmsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        };
    }
    public function set_default_image()
    {
        $id = $this->input->post('id');
        $service_id = $this->input->post('service_id');
        $data['is_default'] = '0';
        $this->db->where('service_id', $service_id);
        $result = $this->db->update('services_image', $data);
        $data['is_default'] = '1';
        $this->db->where('id', $id);
        $this->db->where('service_id', $service_id);
        $qresult = $this->db->update('services_image', $data);
        if ($qresult) {
            echo 1;
        } else {
            echo 0;
        }
    }
    public function delete_service_image()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $id = $this->input->post('id');
        $WHERE = array('id' => $id);
        $img = $this->db->where('id', $user_id)->get('services_image')->row_array();
        $service_image = $img['service_image'];
        $service_details_image = $img['service_details_image'];
        $thumb_image = $img['thumb_image'];
        $mobile_image = $img['mobile_image'];
        unlink(FCPATH . $service_image);
        unlink(FCPATH . $service_details_image);
        unlink(FCPATH . $thumb_image);
        unlink(FCPATH . $mobile_image);
        $result = $this->db->delete('services_image', array(
            'id' => $id
        ));

        if ($result) {
            $message = $this->ser_imgdel;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }
    public function check_covidvaccine()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $id = $this->session->userdata('id');
        $stat = $this->input->post('cov_vac');
        $data['covid_vaccine'] = $stat;
        $this->db->where('id', $id);

        if ($stat != 4) {
            $result = $this->db->update('users', $data);
            $msg = "Status Updated";
            echo json_encode(['success' => true, 'msg' => $msg, 'status' => 1]);
        } else {
            $data['status'] = 2;
            $data['last_login'] = date('Y-m-d H:i:s');
            $result = $this->db->update('users', $data);
            $this->session->unset_userdata('id');
            $msg = "you're not allow to book any service for now due to local authority";
            echo json_encode(['success' => false, 'msg' => $msg, 'status' => 2]);
        }
    }
    public function get_shop_location()
    {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $id = $this->input->post('id');
        $shp = $this->db->select('shop_location,shop_latitude,shop_longitude')->from('shops')->where('id', $id)->get()->row_array();
        echo json_encode(['loc' => $shp['shop_location'], 'lat' => $shp['shop_latitude'], 'lng' => $shp['shop_longitude']]);
    }

    public function delete_service_img()
    {
        $sevice_img_id = $this->input->post('img_id');
        $delete_img = $this->db->where('id', $this->input->post('img_id'))->delete('services_image');

        if ($this->db->affected_rows() > 0) {
            $delete_img = TRUE;
        } else {
            $delete_img = FALSE;
        }

        echo json_encode($delete_img);
    }

    /** [Delete and Delete all Function] */
    public function pro_not_del()
    {
        $ses_token = $this->session->userdata('chat_token');
        $inp = $this->input->post();
        if (!empty($inp['id']))
            $this->db->where('notification_id', $inp['id']);
        $this->db->where('receiver', $ses_token);
        $this->db->set('delete_status', 1);
        $this->db->update('notification_table');
        //echo $this->db->last_query();exit;
        echo json_encode("success");
    }

    public function abuse_report_post()
    {
        $inp = $this->input->post();
        $inputs['description'] = $this->input->post('desc');
        $inputs['pro_id'] = $this->input->post('id');
        $inputs['report_user_id'] = $this->input->post('user_id');
        $inputs['status'] = 1;

        $this->db->insert('abuse_reports', $inputs);
        echo json_encode("success");
    }
}
