<?php
class State_code_config extends CI_Controller
{
    public $data;
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->data['theme']  = 'admin';
        $this->data['model'] = 'state_code_config';
        $this->load->model('admin_model');
        $this->data['base_url'] = base_url();
        $this->data['admin_id'] = $this->session->userdata('id');
        $this->user_role        = !empty($this->session->userdata('user_role')) ? $this->session->userdata('user_role') : 0;
    }
    public function index($offset = 0)
    {
        $this->data['page']        = 'index';
        $this->data['lists']       = $this->admin_model->get_state_code_config();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function create()
    {
        if ($this->input->post('form_submit')) {
            if ($this->data['admin_id'] > 1) {
                $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
                redirect(base_url() . 'admin/state_code_config');
            } else {              
                $country_id               = $this->input->post('countryid');
                $state_name               = $this->input->post('state_name');
               
                $table_data['country_id'] = $country_id;
                $table_data['name'] = $state_name;
                $table_data['created_at'] = date("Y-m-d H:i:s");;
                if ($this->db->insert('state', $table_data)) {
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">State Details Added Successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                }
            }
        }
        $this->data['page'] = 'create';
		$this->data['country'] = $this->admin_model->get_country_code_config();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function edit($cls_id)
    {        
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/state_code_config');
        } else {
            if (!empty($cls_id)) {
                if ($this->input->post('form_submit')) {                    
                $country_id               = $this->input->post('countryid');
                $state_name               = $this->input->post('state_name');
                
                $table_data['country_id'] = $country_id;
                $table_data['name'] = $state_name;
                $table_data['updated_at'] = date("Y-m-d H:i:s");;
                    $this->db->update('state', $table_data, "id = " . $cls_id);
                    $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">State Details Updated Successfully. </div>';
                    $this->session->set_flashdata('message', $message);
                    redirect(base_url('admin/' . $this->data['model']));
                }
                $this->data['datalist'] = $this->admin_model->edit_state_code_config($cls_id);
				$this->data['country'] = $this->admin_model->get_country_code_config();
                $this->data['page']     = 'edit';
                $this->load->vars($this->data);
                $this->load->view($this->data['theme'] . '/template');
            } else {
                redirect(base_url('admin/' . $this->data['model']));
            }
        }
    }
    public function delete_state_code_config()
    {
        if ($this->data['admin_id'] > 1) {
            $this->session->set_flashdata('message', '<p class="alert alert-danger">Permission Denied</p>');
            redirect(base_url() . 'admin/state_code_config');
        } else {
            $id = $this->input->post('tbl_id');
            if (!empty($id)) {
                $this->db->delete('state', array(
                    'id' => $id
                ));
                $message = '<div class="alert alert-success text-center fade in" id="flash_succ_message">State details deleted successfully. </div>';
                echo 1;
            }
            $this->session->set_flashdata('message', $message);
        }
    }
    //jaya
    public function get_state_code($country_id)
    {   
       
        $where=array('country_id' =>$country_id);
        $result=$this->db->get_where('state',$where)->result_array();
        $json=array();
        foreach ($result as $rows) {
            $data['value']=$rows['id'];
            $data['label']=$rows['name'];
            $json[]=$data;
        }

        echo json_encode($json);
    }
}
