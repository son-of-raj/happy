<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->helper('form');
        $this->load->model('language_model', 'language');
        $this->load->model('language_web_model', 'web_language');
        $this->load->model('admin_model', 'admin');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'language';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role'] = $this->session->userdata('role');
    }

    public function index() {

        $this->data['page'] = 'language';
        $this->data['list'] = $this->admin->language_list();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function AddLanguages() {
        $this->data['page'] = 'add_languages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function InsertLanguage() {
        $postdata = $this->input->post();

        $data = [
            'language' => $postdata['language_name'],
            'language_value' => $postdata['language_value'],
            'tag' => $postdata['language_type'],
        ];
        $insert = $this->db->insert('language', $data);
        if (!empty($insert) && $insert != '') {
            $this->session->set_flashdata('success_message', 'Language added successfully');
            redirect(base_url() . "language");
        } else {
            $this->session->set_flashdata('error_message', 'Language added not successfully');
            redirect(base_url() . "add-language");
        }
    }

    public function Wep_Language() {
        $this->data['page'] = 'wep_language';
        $this->data['active_language'] = $this->language->active_language();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function AddWepKeyword($lang_key) {     
        if($this->input->post('form_submit') == true) {
            $postdata = $this->input->post();
            $data = [
                'lang_value' => $postdata['filed_name'],
                'lang_key' => str_replace(array(' ', '!', '&'), '_', 'lg_'.strtolower($postdata['key_name'])),
                'language' => $lang_key,
                'type' => 'web'
            ];
            $insert = $this->db->insert('language_management', $data);
            if (!empty($insert) && $insert != '') {
                $this->session->set_flashdata('success_message', 'Language keyword added successfully');
                redirect(base_url() . "web-languages/".$lang_key);
            } else {
                $this->session->set_flashdata('error_message', 'Language keyword added not successfully');
                redirect(base_url() . "add-wep-keyword".$lang_key);
            }
        }
        $this->data['page'] = 'add_wep_keyword';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function AppPageList() {
        $this->data['list'] = $this->language->page_list();
        $this->data['page'] = 'app_page_list';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function AddAPPKeyword() {

        $this->data['page'] = 'add_app_keyword';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function pages_language() {
        $lang = $this->uri->segment(3);
        $this->data['page'] = 'pages_language';
        $this->data['active_language'] = $this->db->get_where('language', array('status'=>1, 'language_value'=>$lang))->result_array();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    
    public function AllAPPKeyword() {
        
        

        $this->data['page'] = 'all_app_keyword';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function InsertWepKeyword() {
        $postdata = $this->input->post();
        $data = [
            'lang_value' => $postdata['filed_name'],
            'lang_key' => trim($postdata['key_name']),
            'language' => 'en'
        ];
        $insert = $this->db->insert('language_management', $data);
        if (!empty($insert) && $insert != '') {
            $this->session->set_flashdata('success_message', 'Language added successfully');
            redirect(base_url() . "wep-language");
        } else {
            $this->session->set_flashdata('error_message', 'Language added not successfully');
            redirect(base_url() . "add-wep-keyword");
        }
    }

    public function InsertAppKeyword() {
        $postdata = $this->input->post();
        $page_name = $postdata['page_name'];
        $lang_key = $postdata['lang_key'];
        $page_key = str_replace(array(' ', '!', '&'), '_', strtolower($page_name));
        $PageInsert = [
            'page_title' => $postdata['page_name'],
            'page_key' => $page_key,
            'status' => 1
        ];
        $insertPage = $this->db->insert('pages', $PageInsert);
        if (!empty($insertPage) && $insertPage != '') {
            $this->session->set_flashdata('success_message', 'Page added successfully');
            redirect(base_url() . "app-page-list/".$lang_key);
        } else {
            $this->session->set_flashdata('error_message', 'Page added not successfully');
            redirect(base_url() . "add-app-keyword");
        }
    }

    public function language_web_list() {
        $lists = $this->web_language->language_list();
        $data = array();
        $no = $_POST['start'];
        $active_language = $this->web_language->active_language();
        foreach ($lists as $keyword) {
            $row = array();
            if (!empty($active_language)) {
                foreach ($active_language as $rows) {
                    $lg_language_name = $keyword['lang_key'];
                    $language_key = $rows['language_value'];
                    $key = $keyword['language'];
                    $value = ($language_key == $key) ? $keyword['lang_value'] : '';
                    $key = $keyword['language'];
                    $currenct_page_key_value = $this->web_language->currenct_page_key_value($lists);
                    $name = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['name'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['name'] : '';
                    $lang_key = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['lang_key'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['lang_key'] : '';
                    $readonly = '';

                    $row[] = '<input type="text" class="form-control" placeholder="Name" name="' . $lg_language_name . '[' . $language_key . '][lang_value]" value="' . $name . '" ' . $readonly . ' ><br>
                            <input type="text" class="form-control" value="' . $lang_key . '" readonly >
                         ';
                }
            }

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->web_language->language_list_all(),
            "recordsFiltered" => $this->web_language->language_list_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function update_multi_web_language() {

        if ($this->input->post()) {
            if ($this->data['admin_id'] > 1) {
                $this->session->set_flashdata('error_message', '<p class="alert alert-danger">Permission Denied</p>');
                redirect(base_url() . 'wep-language');
            } else {
                $data = $this->input->post();

                foreach ($data as $row => $object) {
                    if (!empty($object)) {
                        foreach ($object as $key => $value) {
                            $this->db->where('language', $key);
                            $this->db->where('lang_key', $row);
                            $record = $this->db->count_all_results('language_management');
                            if ($record == 0) {
                                $array = array(
                                    'language' => $key,
                                    'lang_key' => $row,
                                    'lang_value' => $value['lang_value'],
                                );

                                $this->db->insert('language_management', $array);
                            } else {

                                $this->db->where('language', $key);
                                $this->db->where('lang_key', $row);


                                $array = array(
                                    'lang_value' => $value['lang_value'],
                                );

                                $this->db->update('language_management', $array);
                            }
                        }
                    }
                }
            }
        }
        redirect(base_url() . 'wep-language');
    }

    public function language_list() {
        $page_key = $this->input->post('page_key');
        $lang_key = $this->input->post('lang_key');
        
        $lists = $this->language->language_list($page_key, $lang_key);
        
        $data = array();
        $no = $_POST['start'];
        $active_language = $this->db->get_where('language', array('status'=>1, 'language_value'=>$lang_key))->result_array();;
        foreach ($lists as $keyword) {
            $row = array();
            if (!empty($active_language)) {
                foreach ($active_language as $rows) {

                    $lg_language_name = $keyword['lang_key'];
                    $language_key = $rows['language_value'];


                    $key = $keyword['language'];
                    $value = ($language_key == $key) ? $keyword['lang_value'] : '';
                    $key = $keyword['language'];
                    $currenct_page_key_value = $this->language->currenct_page_key_value($lists);

                    if(!empty($currenct_page_key_value)) {
                    $name = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['name'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['name'] : '';

                    $placeholder = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['placeholder'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['placeholder'] : '';
                    $validation1 = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['validation1'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['validation1'] : '';
                    $validation2 = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['validation2'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['validation2'] : '';
                    $validation3 = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['validation3'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['validation3'] : '';
                    $lang_key = (!empty($currenct_page_key_value[$lg_language_name][$language_key]['lang_key'])) ? $currenct_page_key_value[$lg_language_name][$language_key]['lang_key'] : '';


                    $type = $currenct_page_key_value[$lg_language_name]['en']['type'];
                    $langVal = str_replace('_', ' ', $lang_key); 
                    $langValues = ltrim($langVal, 'lbl_'); 
                    $langKeywords = ucfirst($langValues);
                    

                    $readonly = '';

                        $row[] = '<input type="text" class="form-control" placeholder="Name" name="' . $lg_language_name . '[' . $language_key . '][lang_value]" value="' . $name . '" ' . $readonly . ' ><br>
                          <input type="text" class="form-control" placeholder="Placeholder" name="' . $lg_language_name . '[' . $language_key . '][placeholder]" value="' . $placeholder . '" ' . $readonly . ' ><br>
                          <input type="text" class="form-control" placeholder="Validation 1" name="' . $lg_language_name . '[' . $language_key . '][validation1]" value="' . $validation1 . '" ' . $readonly . ' ><br>
                          <input type="text" class="form-control" placeholder="Validation 2" name="' . $lg_language_name . '[' . $language_key . '][validation2]" value="' . $validation2 . '" ' . $readonly . ' ><br>
                          <input type="text" class="form-control" placeholder="Validation 3" name="' . $lg_language_name . '[' . $language_key . '][validation3]" value="' . $validation3 . '" ' . $readonly . ' ><br>
                          <input type="text" class="form-control" value="' . $langKeywords . '" readonly >
                          <input type="hidden" class="form-control" name="' . $lg_language_name . '[' . $language_key . '][type]" value="' . $type . '" ' . $readonly . ' >';
                    }
                }
            }

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->language->language_list_all($page_key),
            "recordsFiltered" => $this->language->language_list_filtered($page_key),
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function update_language_status() {

        $id = $this->input->post('id');
        $status = $this->input->post('update_language');

        if ($status == 2) {
            $this->db->where('id', $id);
            $this->db->where('default_language', 1);
            $data = $this->db->get('language')->result_array();

            if (!empty($data)) {
                echo "0";
            } else {
                $this->db->query(" UPDATE `language` SET `status` = " . $status . " WHERE `id` = " . $id . " ");
                echo "1";
            }
        } else {
            $this->db->query(" UPDATE `language` SET `status` = " . $status . " WHERE `id` = " . $id . " ");
            echo "1";
        }
    }
    public function delete_language() {

        $id = $this->input->post('id');
		$this->db->where('language',$id)->delete('language_management');
    	$this->db->where('language',$id)->delete('app_language_management');
		$result = $this->db->where('language_value',$id)->delete('language');
		  if($result){
			echo "success";
		  }else{
			echo "error";
		  }
    }
    public function update_language_default() {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $data = $this->db->get('language')->row_array();
        if (!empty($data)) {
            $this->db->query("UPDATE language SET default_language = ''");
            $this->db->query(" UPDATE `language` SET `default_language` = 1 WHERE `id` = " . $id . " ");
            $this->db->query(" UPDATE `system_settings` SET `value` = '" .$data['language_value']. "' WHERE `key` = 'language' ");
            echo "1";
        } else {
            echo "0";
        }
    }

    public function change_language() {





        $language = (!empty($this->input->post('lg'))) ? $this->input->post('lg') : 'en';

        $tag = (!empty($this->input->post('tag'))) ? $this->input->post('tag') : 'ltr';



        $this->session->set_userdata(array('user_select_language' => $language));

        $this->session->set_userdata(array('tag' => $tag));
    }

    public function update_multi_language() {

        if ($this->input->post()) {
            

            $page_key = $this->input->post('page_key');
            $lang_key = $this->input->post('lang_key');
            

            if ($this->data['admin_id'] > 1) {
                $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
                redirect(base_url() . 'app-page-list/' . $page_key . '/'.$lang_key.'');
            } else {


                $data = $this->input->post();




                foreach ($data as $row => $object) {
                    if (!empty($object)) {

                        foreach ($object as $key => $value) {

                            $this->db->where('language', $key);
                            $this->db->where('lang_key', $row);
                            $this->db->where('type', $value['type']);
                            $this->db->where('page_key', $page_key);

                            $record = $this->db->count_all_results('app_language_management');
                            


                            if ($record == 0) {
                                $array = array(
                                    'language' => $key,
                                    'lang_key' => $row,
                                    'lang_value' => $value['lang_value'],
                                    'placeholder' => $value['placeholder'],
                                    'validation1' => $value['validation1'],
                                    'validation2' => $value['validation2'],
                                    'validation3' => $value['validation3'],
                                    'type' => $value['type'],
                                    'page_key' => $page_key,
                                );

                                $this->db->insert('app_language_management', $array);
                            } else {

                                $this->db->where('language', $key);
                                $this->db->where('lang_key', $row);
                                $this->db->where('type', $value['type']);
                                $this->db->where('page_key', $page_key);
                                $this->db->where('language', $lang_key);


                                $array = array(
                                    'lang_value' => $value['lang_value'],
                                    'placeholder' => $value['placeholder'],
                                    'validation1' => $value['validation1'],
                                    'validation2' => $value['validation2'],
                                    'validation3' => $value['validation3'],
                                    'type' => $value['type'],
                                    'page_key' => $page_key,
                                );

                                $this->db->update('app_language_management', $array);
                            }
                        }
                    }
                }
            }
        }
        redirect(base_url() . 'app-page-list/' . $page_key . '/'.$lang_key.'');
    }
    
    public function AppKeyword() {
        $postdata = $this->input->post();
        $page_key = $this->input->post('page_key');
        $lang_key = $this->input->post('lang_key');
        $keywords = str_replace(array(' ', '!', '&'), '_', strtolower($postdata['filed_name']));
        
        $data=[
            'page_key' => $page_key,
            'lang_key' => 'lbl_'.$keywords,
            'lang_value' => $postdata['name'],
            'placeholder' => $postdata['placeholder'],
            'validation1' => $postdata['valide_1'],
            'validation2' => $postdata['valide_2'],
            'validation3' =>$postdata['valide_3'],
            'type' => 'App',
            'language' => $lang_key,
        ];
        
        $insertPage = $this->db->insert('app_language_management', $data);
        if (!empty($insertPage) && $insertPage != '') {
            $this->session->set_flashdata('success_message', 'Keyword added successfully');
            redirect(base_url() . "app-page-list/".$page_key.'/'.$lang_key);
        } else {
            $this->session->set_flashdata('error_message', 'Keyword added not successfully');
            redirect(base_url() . "app-page-list/".$page_key.'/'.$lang_key);
        }
    }

    
    //Language Listing
    public function languages() {
        $this->data['language'] = $this->db->get_where('language', array('status!='=>0))->result();
        $this->data['page'] = 'languages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    //Add New Language
    public function addLanguage() {
        if($this->input->post('form_submit') == true) {
            $lang_data['language'] = $this->input->post('language');
            $lang_data['language_value'] = $this->input->post('language_value');
            $lang_data['status'] = $this->input->post('status');
            $lang_data['tag'] = $this->input->post('tag');
            if ($this->db->insert('language', $lang_data)) {
                $this->session->set_flashdata('success_message', 'Language added successfully');
                redirect(base_url() . "languages");
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "languages");
            }
        } 
        $this->data['page'] = 'add_language';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    //Update Existing Language Details
    public function editLanguages($lang_value) {
        if($this->input->post('form_submit') == true) {
            $lang_data['language'] = $this->input->post('language');
            $lang_data['language_value'] = $this->input->post('language_value');
            $lang_data['status'] = $this->input->post('status');
            $lang_data['tag'] = $this->input->post('tag');
            $id = $this->input->post('lang_id');
            $this->db->where('id', $id);
            if ($this->db->update('language', $lang_data)) {
                $this->session->set_flashdata('success_message', 'Language updated successfully');
                redirect(base_url() . "languages");
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "languages");
            }
        } 
        $this->data['lang_details'] = $this->db->get_where('language', array('language_value'=>$lang_value))->row();
        $this->data['page'] = 'edit_languages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    //Language Listing
    public function webLanguages() {
        $lang_val = $this->uri->segment(2);
        $this->data['lang_keywords'] =  $this->db->where(array('language'=>$lang_val, 'lang_key!=' => ''))->order_by('sno',"DESC")->get('language_management')->result();
        $this->data['langName'] = $this->db->select('')->get_where('language', array('language_value'=>$lang_val))->row()->language;
        $this->data['page'] = 'web_languages';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function update_language_keyword() {
        $data = $this->input->post();
        if($this->input->post()) {
            $this->db->where(array('sno'=>$data['id'], 'language'=>$data['lang_key']));
            $array = array(
                'lang_value' => $data['lang_value'],
            );
            $this->db->update('language_management', $array);
            if($this->db->affected_rows() > 0) {
                echo '1';
            } else {
                echo '2';
            }
        }
    }


    public function updateLangTag() {
        $data = $this->input->post();
        if($data) {
            $array_tag = array(
                'tag' => $data['tag'],
            );
            $this->db->where(array('id'=>$data['id']));
            if($this->db->update('language', $array_tag)) {
                echo '1';
            } else {
                echo '2';
            }
        }
    }

    //Update Language Status and update 
    public function updateLangStatus() {
        $data = $this->input->post();
        $lang_key = $this->db->get_where('language', array('id'=>$data['id']))->row()->language_value;
        if($data) {
            $array_tag = array(
                'status' => $data['status'],
            );
            $this->db->where(array('id'=>$data['id']));
            if($this->db->update('language', $array_tag)) {
                echo '1';
            } else {
                echo '2';
            }
        }
    }

    /** [Web Language Export] */
    public function exportlang() {
        $file_name = 'language'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$file_name"); 
        header("Content-Type: application/csv;");
       
        // get data 
        $language_data = $this->language->fetch_data();

        // file creation 
        $file = fopen('php://output', 'w');
     
        $header = array("key", "value", "language", "type"); 
        fputcsv($file, $header);
        foreach ($language_data->result_array() as $key => $value)
        { 
          fputcsv($file, $value); 
        }
        fclose($file); 
        exit; 
    }
    /** [Web Language Import] */
    public function importlang() {
        if ($_FILES) {
            $skip_rows = $this->input->post('skip_rows');
            $is_success = 0;
            
            if (!empty($_FILES['add_language'])) {
                $config['upload_path'] = 'uploads/attachments/csv/';

                $config['allowed_types'] = 'csv';
 
                $this->load->library('upload', $config);

                $random_hash = substr(str_shuffle(time()), 0, 3) . strrev(mt_rand(100000, 999999));
                
                $extension = pathinfo($_FILES['add_language']['name'], PATHINFO_EXTENSION);

                $new_file_name = 'language_' . $random_hash . '.' . $extension;

                $_FILES['add_language'] = array(
                    'name' => $new_file_name,
                    'type' => $_FILES['add_language']['type'],
                    'tmp_name' => $_FILES['add_language']['tmp_name'],
                    'error' => $_FILES['add_language']['error'],
                    'size' => $_FILES['add_language']['size']
                );

                $this->upload->initialize($config);
                


                $this->upload->do_upload('add_language');

                $upload_data = $this->upload->data();

                $file_name = $upload_data['file_name'];

                $file = 'uploads/attachments/csv/' . $file_name;
                // $file = base_url() . 'attachements/csv/sample_product.xlsx';
                $handle = fopen($file, 'r');

                $lineNum = 1;
                if ($file != NULL && $skip_rows > 0 || $lineNum == 1) {

                    $skipLines = $skip_rows;


                    if ($skipLines > 0 || $lineNum == 1) {

                        while (fgetcsv($handle)) {

                            if ($lineNum == $skipLines || $lineNum == 1) {

                                break;
                            }

                            $lineNum++;
                        }
                    }
                }
            
                $count = 1;
                if ($file != NULL) {
                    while ($row_data = fgetcsv($handle)) {
                        $lang_key  = $row_data[0];
                        $language  = $this->input->post('lang_code');
                    $lang_check = $this->language->check_language($lang_key,$language);
                    $update_lang = array(
                            'lang_key' => $row_data[0],
                            'lang_value' => $row_data[1],
                            'language' => $language,
                            'type' => $row_data[3],
                        );
                    if ($lang_check != 0){
                        $this->db->where('sno', $lang_check)->update('language_management', $update_lang);
                    } else {
                        $this->db->insert('language_management' ,$update_lang);
                    }
                    $update_lang = array();
                    }

                    $this->session->set_flashdata('success_message', 'Language updated successfully');
                    redirect(base_url() . "languages");
                }
            }
        }
    }

    /** [App Language Export] */
    public function exporapptlang() {
        $file_name = 'applanguage'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$file_name"); 
        header("Content-Type: application/csv;");

        // get data 
        $languageapp_data = $this->language->fetch_app_data();

        // file creation 
        $file = fopen('php://output', 'w');
     
        $header = array("page_key", "language_key", "value", "placeholder", "validation1", "validation2", "validation3", "language"); 
        fputcsv($file, $header);
        foreach ($languageapp_data->result_array() as $key => $value)
        { 
          fputcsv($file, $value); 
        }
        fclose($file); 
        exit; 
    }
    /** [App Language Import] */
    public function importapplang() {
        if ($_FILES) {
            $skip_rows = $this->input->post('skip_rows');
            $is_success = 0;
            
            if (!empty($_FILES['add_app_language'])) {
                $config['upload_path'] = 'uploads/attachments/csv/';

                $config['allowed_types'] = 'csv';
 
                $this->load->library('upload', $config);

                $random_hash = substr(str_shuffle(time()), 0, 3) . strrev(mt_rand(100000, 999999));
                
                $extension = pathinfo($_FILES['add_app_language']['name'], PATHINFO_EXTENSION);

                $new_file_name = 'language_' . $random_hash . '.' . $extension;

                $_FILES['add_app_language'] = array(
                    'name' => $new_file_name,
                    'type' => $_FILES['add_app_language']['type'],
                    'tmp_name' => $_FILES['add_app_language']['tmp_name'],
                    'error' => $_FILES['add_app_language']['error'],
                    'size' => $_FILES['add_app_language']['size']
                );

                $this->upload->initialize($config);
                


                $this->upload->do_upload('add_app_language');

                $upload_data = $this->upload->data();

                $file_name = $upload_data['file_name'];

                $file = 'uploads/attachments/csv/' . $file_name;
                // $file = base_url() . 'attachements/csv/sample_product.xlsx';
                $handle = fopen($file, 'r');

                $lineNum = 1;
                if ($file != NULL && $skip_rows > 0 || $lineNum == 1) {

                    $skipLines = $skip_rows;


                    if ($skipLines > 0 || $lineNum == 1) {

                        while (fgetcsv($handle)) {

                            if ($lineNum == $skipLines || $lineNum == 1) {

                                break;
                            }

                            $lineNum++;
                        }
                    }
                }
            
                $count = 1;
                if ($file != NULL) {
                    while ($row_data = fgetcsv($handle)) {
                        $page_key  = $row_data[0];
                        $lang_key  = $row_data[1];
                        $language = $this->input->post('lang_code');
                    $app_lang_check = $this->language->check_app_language($page_key,$lang_key,$language);
                    $update_lang = array(
                            'page_key' => $row_data[0],
                            'lang_key' => $row_data[1],
                            'lang_value' => $row_data[2],
                            'placeholder' => $row_data[3],
                            'validation1' => $row_data[4],
                            'validation2' => $row_data[5],
                            'validation3' => $row_data[6],
                            'language' => $language,
                            );

                    if ($app_lang_check != 0) {
                        $this->db->where('sno', $app_lang_check)->update('app_language_management', $update_lang);
                        
                    } else {
                        $this->db->insert('app_language_management' ,$update_lang);
                    }
                    $update_lang = array();
                    }
                    $this->session->set_flashdata('success_message', 'App Language updated successfully');
                    redirect(base_url() . "languages");
                }
            }
        }
    }

}
