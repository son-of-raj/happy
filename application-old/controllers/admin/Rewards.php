<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rewards extends CI_Controller {

 public $data;

 public function __construct() {

  parent::__construct();
  $this->load->model('Rewards_model','rewards');  
  $this->load->model('common_model','common_model');  
  $this->data['theme'] = 'admin';
  $this->data['model'] = 'rewards';
  $this->data['base_url'] = base_url();
  $this->session->keep_flashdata('error_message');
  $this->session->keep_flashdata('success_message');
  $this->load->helper('user_timezone_helper');
  $this->data['user_role']=$this->session->userdata('role');
}


public function reward_system()
{	
	$this->common_model->checkAdminUserPermission(12);	
	if ($this->input->post('form_submit')) 
	{  
		$username = $this->input->post('username');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->data['list'] =$this->rewards->rewards_filter($username,$from,$to);
	}
	else
	{
		$this->data['list'] = $this->rewards->read_servicerewards();
	}
	
	$this->data['page'] = 'reward_system';
	$this->data['model'] = 'rewards';
	$this->data['currency_code'] = settings('currency');

	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
		 
	
}
public function reward_system_details($value='')
{
	$this->common_model->checkAdminUserPermission(12);
	$this->data['page'] = 'reward_system_details';
	$userid = $this->uri->segment('2');
	$this->data['userid'] = $userid;
	$this->data['rewards'] = $this->rewards->get_rewards_details($userid);
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
}






}
