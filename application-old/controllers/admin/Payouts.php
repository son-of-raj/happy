<?php
class Payouts extends CI_Controller
{
    public $data;
    
    public function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->data['theme']  = 'admin';
        $this->data['model'] = 'payouts';
        $this->load->model('admin_model');
		$this->load->model('common_model','common_model');
        $this->data['base_url'] = base_url();
        $this->data['admin_id'] = $this->session->userdata('id');
        $this->user_role        = !empty($this->session->userdata('user_role')) ? $this->session->userdata('user_role') : 0;
        $this->load->helper('custom_language');
        $lang = !empty($this->session->userdata('lang'))?$this->session->userdata('lang'):'en';
        $this->data['language_content'] = get_admin_languages($lang);
        
    }

    public function addPayouts()
    {
		//$this->common_model->checkAdminUserPermission(18);
        if ($this->input->post('form_submit')) {
            $payout_data = array(
                'user_id' => $this->input->post('provider_id'),
                'payout_method' => $this->input->post('payout_method'),
                'amount' => $this->input->post('payout_amount'),
                'currency' => settingValue('currency_code'),
                'status' => $this->input->post('payout_status'),
                'created_datetime' => date('Y-m-d H:i:s')
            );

            if ($this->input->post('status') == '1') {
                if ($this->db->insert('payouts', $payout_data)) {
                    $result = $this->admin_model->reduce_user_balance($this->input->post('provider_id'), $this->input->post('payout_amount'));
                }
            } else {
                $this->db->insert('payouts', $payout_data);

            }

            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success_message', 'Payouts added successfully');
                redirect(base_url() . "admin/add-payouts");
            } else {
                $this->session->set_flashdata('error_message', 'Something went wrong, Try again!!');
                redirect(base_url() . "admin/add-payouts");
            }
        } else {
            $this->data['page'] = 'add_payout';
            $this->data['providers'] = $this->admin_model->providersData();
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        }
    }

    //Check wallet amount 
    public function walletAmtCheck() {
        $payout_amt = $this->input->post('payout_amount');
        $id = $this->input->post('provider_id');

        $getAmt = $this->db->get_where('wallet_table', array('user_provider_id'=>$id, 'type'=>1, 'wallet_amt>='=>$payout_amt))->num_rows();
        if(!empty($id)) {
            if ($getAmt > 0) {
                $isAvailable = TRUE;
            } else {
                $isAvailable = FALSE;
            }
        } else {
          $isAvailable = FALSE;
        }
        echo json_encode(
            array(
              'valid' => $isAvailable
            ));
    }

    public function payoutRequest(){
        $this->data['page'] = 'payout_request';
        $this->data['requests_data'] = $this->admin_model->getPayoutRequest();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function completedPayouts(){
        $this->data['page'] = 'completed_payout';
        $this->data['completed_data'] = $this->admin_model->getCompletedPayouts();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function updatePayoutStatus() {
        $postdata = $this->input->post();

        $this->db->where('id', $postdata['id']);
        $this->db->update('payouts', array('status'=>1));
        $amount = $this->db->get_where('payouts', array('id'=>$postdata['id']))->row()->amount;
        
        if($this->db->affected_rows() > 0) {
            $results = $this->admin_model->reduce_user_balance($postdata['user_id'], $amount);
            if($results > 0) {
                $this->session->set_flashdata('success_message', 'Payouts Approved successfully');
                redirect(base_url() . "admin/completed-payouts");
            } else {
                $this->session->set_flashdata('error_message', 'Something went wrong, Try again!!');
                redirect(base_url() . "admin/completed-payouts");
            }
        } else {
            $this->session->set_flashdata('error_message', 'Something went wrong, Try again!!');
            redirect(base_url() . "admin/completed-payouts");
        }
        
    }
    
}
