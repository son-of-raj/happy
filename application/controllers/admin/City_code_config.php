<?php
class City_code_config extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->data['theme']  = 'admin';
        $this->data['model'] = 'city_code_config';
        $this->load->model('admin_model');
        $this->data['base_url'] = base_url();
        $this->data['admin_id'] = $this->session->userdata('id');
        $this->user_role        = !empty($this->session->userdata('user_role')) ? $this->session->userdata('user_role') : 0;
    }
    public function index($offset = 0)
    {
        $this->data['page']        = 'index';
        $this->data['lists']       = $this->admin_model->get_city_code_config();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function create()
    {
        if ($this->input->post('form_submit')) {
            if ($this->data['admin_id'] > 1) {
                $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
                redirect(base_url() . 'admin/city_code_config');
            } else {
               
                $state_id               = $this->input->post('state_id');
                $city_name              = $this->input->post('city_name');
                $table_data['state_id'] = $state_id;
                $table_data['name'] = $city_name;
				
                if ($this->db->insert('city', $table_data)) { 
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">City Details added successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                } 
            }
        }
        $this->data['page'] = 'create';
		$this->data['state'] = $this->admin_model->get_state_code_config();
        $this->data['country'] = $this->admin_model->get_country_code_config();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function edit($cls_id)
    {
        
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/city_code_config');
        } else {
            if (!empty($cls_id)) {
                if ($this->input->post('form_submit')) {
                    
					$state_id               = $this->input->post('state_id');
					$city_name              = $this->input->post('city_name');
				   
					$table_data['state_id'] = $state_id;
					$table_data['name'] = $city_name;
               
                    $this->db->update('city', $table_data, "id = " . $cls_id);
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">City Details Updated successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                }
                $this->data['datalist'] = $this->admin_model->edit_city_code_config($cls_id);
				$this->data['state'] = $this->admin_model->get_state_code_config(); 	
                $this->data['page']     = 'edit';
                $this->load->vars($this->data);
                $this->load->view($this->data['theme'] . '/template');
            } else {
                redirect(base_url('admin/' . $this->data['model']));
            }
        }
    }
    public function delete_city_code_config()
    {
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/city_code_config');
        } else {
            $id = $this->input->post('tbl_id');
            if (!empty($id)) {
                $this->db->delete('city', array(
                    'id' => $id
                ));
                $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">City Details deleted successfully. </div>';
                echo 1;
            }
            $this->session->set_flashdata('message', $message);
        }
    }
}
