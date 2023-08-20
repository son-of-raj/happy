<?php
class Roles extends CI_Controller
{
    public $data;
    
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->data['theme']  = 'admin';
        $this->data['model'] = 'roles';
        $this->load->model('admin_model', 'admin');
		$this->load->model('common_model','common_model');
        $this->data['base_url'] = base_url();
        $this->data['admin_id'] = $this->session->userdata('id');
        $this->user_role        = !empty($this->session->userdata('user_role')) ? $this->session->userdata('user_role') : 0;
        $this->load->helper('custom_language');
        $lang = !empty($this->session->userdata('lang'))?$this->session->userdata('lang'):'en';
        //$this->data['language_content'] = get_admin_languages($lang);
        
        $this->data['user_role']=$this->session->userdata('role');
    }

    public function index($offset = 0)
    {
		$this->common_model->checkAdminUserPermission(18);
        $this->data['page'] = 'index';
        $this->data['roles'] = $this->admin->get_roles_permissions();
        //print_r($this->data['roles']); exit;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

     public function add_roles_permissions() {
        $this->common_model->checkAdminUserPermission(2);
        if ($this->input->post('form_submit')) {

            $role_permissions = array_values($this->input->post('accesscheck'));
            $permissions = array();
            foreach($role_permissions as $key => $value) {
                $permissions[] = $value;
            }

            $rolePermission = implode(',', $permissions);
            //echo 'ss<pre>'; print_r($this->input->post('role_id')); exit;
            removeTag($this->input->post());
            $languages = $this->db->get_where('language', array('status'=>1))->result();
            $table_data['status'] = 1;
            //$table_data['lang_type'] = $language->language_value;
            $table_data['permission_modules'] = $rolePermission;
            $table_data['created_datetime'] = date('Y-m-d H:i:s');
            if($this->input->post('role_id') == '') {
                $this->db->insert('roles_permissions', $table_data);
                $last_id = $this->db->insert_id();
                $result = $this->admin->add_role_permissions($last_id);
            } else {
                $this->db->where('id',$this->input->post('role_id'));
                $this->db->update('roles_permissions',$table_data);
                $result = $this->admin->update_roles_permissions($this->input->post('role_id'));
            }

            if ($result > 0) { 
                $this->session->set_flashdata('success_message', 'Roles details updated successfully');
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            }

            redirect(base_url() . "admin/roles");
        }


        $this->data['page'] = 'add_roles';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    
    public function edit_roles_permissions($id=null) {
        $this->data['roles'] = $this->admin->get_roles_permissions($id);
        $this->data['page'] = 'edit_roles';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function deleteRoles() {
        //$this->common_model->checkAdminUserPermission(2);
        $id = $this->input->post('role_id');
        $table_data['status'] = 0;
        $this->db->where('id', $id);
        if ($this->db->update('roles_permissions', $table_data)) {
            $this->session->set_flashdata('success_message', 'Roles deleted successfully');
            echo 1;
        } else {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            echo 1;
        }
    }
}
