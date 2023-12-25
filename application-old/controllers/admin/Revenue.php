<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Revenue extends CI_Controller {

    public $data;

    public function __construct() {

        parent::__construct();
        $this->load->helper('form');
        
        $this->load->model('admin_model', 'admin');
		$this->load->model('common_model','common_model');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'revenue';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role'] = $this->session->userdata('role');
    }

    public function index() {
		
		$this->common_model->checkAdminUserPermission(11);

        $this->data['page'] = 'revenue_list';
        if($this->input->post('form_submit')) {
            $provider_name = $this->input->post('provider_name');
            $date = $this->input->post('date');
            
            $this->data['list'] = $this->admin->Revenue_list($provider_name, $date);

        } else { 
            $this->data['list'] = $this->admin->Revenue_list();
        }
        $this->data['provider_list'] = $this->db->select('id,name')->get('providers')->result_array();
       
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function invoice_revenue($revenue_id) {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $data['invoice'] = $this->admin->revenue_details($revenue_id);
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('admin/revenue/invoice_revenue', $data, true);
        $mpdf->writeHTML($html);
        $mpdf->Output();
    }
    

}
