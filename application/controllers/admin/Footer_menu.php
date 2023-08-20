<?php
class Footer_menu extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->data['theme']  = 'admin';
        $this->data['model'] = 'footer_menu';
        $this->load->model('admin_model');
        $this->data['base_url'] = base_url();
        $this->data['admin_id'] = $this->session->userdata('id');
        $this->user_role        = !empty($this->session->userdata('user_role')) ? $this->session->userdata('user_role') : 0;
        $this->load->helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor1'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id',
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
    public function index($offset = 0)
    {
        $this->data['page']        = 'index';
        $this->data['lists']       = $this->admin_model->get_all_footer_menu();
        $this->data['footercount'] = $this->admin_model->footercount();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function create()
    {
        if ($this->input->post('form_submit')) {
            if ($this->data['admin_id'] > 1) {
                $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
                redirect(base_url() . 'admin/footer_menu');
            } else {
                str_replace("world", "Peter", "Hello world!");
                $value               = $this->input->post('menu_name');
                $table_data['title'] = str_replace(' ', '_', $value);
                if ($this->db->insert('footer_menu', $table_data)) {
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">footer widget added successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                }
            }
        }
        $this->data['page'] = 'create';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function edit($cls_id)
    {
        $current_date = date('Y-m-d H:i:s');
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/footer_menu');
        } else {
            if (!empty($cls_id)) {
                if ($this->input->post('form_submit')) {
                    $value               = $this->input->post('menu_name');
                    $table_data['title'] = str_replace(' ', '_', $value);
                    $this->db->update('footer_menu', $table_data, "id = " . $cls_id);
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">footer widget edited successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                }
                $this->data['datalist'] = $this->admin_model->edit_footer_menu($cls_id);
                $this->data['page']     = 'edit';
                $this->load->vars($this->data);
                $this->load->view($this->data['theme'] . '/template');
            } else {
                redirect(base_url('admin/' . $this->data['model']));
            }
        }
    }
    public function delete_footer_menu()
    {
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/footer_menu');
        } else {
            $id = $this->input->post('tbl_id');
            if (!empty($id)) {
                $this->db->delete('footer_menu', array(
                    'id' => $id
                ));
                $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">footer widget delete successfully. </div>';
                echo 1;
            }
            $this->session->set_flashdata('message', $message);
        }
    }
    public function notification($pag_id)
    {
        $page_id = $pag_id;
        $this->db->set('notify_status', '1', FALSE);
        $this->db->where('page_id', $pag_id);
        $this->db->update('page');
        redirect(base_url("admin/page/edit/" . $page_id));
    }

    public function frontendSettings()
    {
        $this->load->library('upload');
        if($this->input->post('form_submit') == true) {
            $post_data = $this->input->post();
            $logo_img = $this->db->get_where('header_settings', array('id'=>$post_data['header_id']))->row()->header_logo;
            if (!is_dir('uploads/logo')) {
                mkdir('./uploads/logo', 0777, TRUE);
            }
            if ($_FILES['header_logo']['name']) {
                $table_data1 = [];
                $configfile['upload_path'] = FCPATH . 'uploads/logo';
                $configfile['allowed_types'] = 'gif|jpg|jpeg|png';
                $configfile['overwrite'] = FALSE;
                $configfile['remove_spaces'] = TRUE;
                $file_name = $_FILES['header_logo']['name'];
                $configfile['file_name'] = time() . '_' . $file_name;
                $image_name = $configfile['file_name'];
                $image_url = 'uploads/logo/' . $image_name;
                $this->upload->initialize($configfile);
                if ($this->upload->do_upload('header_logo')) {
                    $img_uploadurl = 'uploads/logo' . $_FILES['header_logo']['name'];
                    $key = 'header_logo';
                    $val = $image_name;
                    $logo = $val;
                }
            } else {
                $logo = $logo_img;
            }
            if($post_data['form_name'] == 'headers') {
                $data = array(
                    'header_logo' => $logo,
                    'language_option' => ($post_data['language_option'])?'1':'0',
                    'currency_option' => ($post_data['currency_option'])?'1':'0',
                    'sticky_header' => ($post_data['sticky_header'])?'1':'0',
                    'created_datetime' => date('Y-m-d H:i:s')
                );
            } else {
                $reset_menu = $this->db->get_where('header_settings', array('id'=>1))->row()->reset_menu;

                $menu_data = array();
                $menus = $this->input->post('menu_title');
                $links = $this->input->post('menu_links');
                $i = 1;
                foreach($menus as $key => $value) {
                    $menu = $value;
                    $link = $links[$key];
                    if(!empty($menu) && !empty($link)) {
                        $menu_data[] = array(
                            'id' => $i, 
                            'label' =>  $menu,  
                            'link'  => $link
                        );
                    }
                    $i++; 
                }
                $menu_details = json_encode($menu_data);
                $data = array(
                    'header_menu_option' => ($post_data['menus_option'])?'1':'0',
                    'header_menus' => ($menu_data)?$menu_details:''
                );
            }

            if($post_data['header_id'] == '') {
                 $data = array(
                    'reset_menu' => ($reset_menu)?$reset_menu:$menu_details
                );
                $this->db->insert('header_settings', $data);
            } else {
                $this->db->where('id',$post_data['header_id']);
                $this->db->update('header_settings', $data);
            }
            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Frontend Setting updated successfully');
                redirect(base_url() . 'admin/frontend-settings');
            } else {
                $this->session->set_flashdata('error_message', 'Something went wrong, Try again');
                redirect(base_url() . 'admin/frontend-settings');
            }
        }
        $this->data['page'] = 'frontend-settings';
        $this->data['frontend_data'] = $this->db->get_where('header_settings', array('id'=>1))->row();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function resetMenu() {
        
        $menus = $this->db->get_where('header_settings', array('id'=>1))->row()->reset_menu;
        $menu_data['header_menus'] = $menus;
        $this->db->where('id',1);
        $this->db->update('header_settings', $menu_data);
        if($this->db->affected_rows() > 0) {
            echo 1;
        } else {
            echo 0;
        }

    }
}
