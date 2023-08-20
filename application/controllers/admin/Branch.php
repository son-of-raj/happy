<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

 public $data;

 public function __construct() {

  parent::__construct();
  $this->load->model('branch_model','branch');
  $this->load->model('common_model','common_model');
  $this->data['theme'] = 'admin';
  $this->data['model'] = 'branch';
  $this->data['base_url'] = base_url();
  $this->session->keep_flashdata('error_message');
  $this->session->keep_flashdata('success_message');
  $this->load->helper('user_timezone_helper');
  $this->data['user_role']=$this->session->userdata('role');
}

public function branch_lists()
{
	$this->common_model->checkAdminUserPermission(4);
 extract($_POST);
 
 $this->data['page'] = 'branch_lists';

 if ($this->input->post('form_submit')) 
 {  
   $shop_id = $this->input->post('shop_name');
   $branch_name = $this->input->post('branch_name');
   $email = $this->input->post('email');  
   $from = $this->input->post('from');
   $to = $this->input->post('to');
   $this->data['list'] =$this->branch->branch_filter($shop_id, $branch_name,$email,$from,$to);
 }
 else
 {
  $this->data['list'] = $this->branch->branch_lists();
}
$this->load->vars($this->data);
$this->load->view($this->data['theme'].'/template');

}

public function branch_details($value='')
{
	$this->common_model->checkAdminUserPermission(4);
  $this->data['page'] = 'branch_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}

public function change_branch_status() {       
	$s_id = $this->input->post('branch_id');
	$status_val = $this->input->post('status_val');

	$inputs['status'] = $status_val;
	$WHERE = array('id' => $s_id);
	$IMGWHERE = array('branch_id' => $s_id);
	$result = $this->branch->update_branch_status($inputs, $WHERE,$IMGWHERE);
	if ($result) {
		echo json_encode(['status'=>"success",'msg'=>"Branch Status Changed SuccessFully...."]);
	} else {
		echo json_encode(['status'=>"error",'msg'=>"Branch Status not changed... Try again later"]);
	}
}
public function delete_branch()
{
    $s_id = $this->input->post('branch_id');
	$status_val = $this->input->post('status_val');

	$inputs['status'] = 0;
	$WHERE = array('id' => $s_id);
	$IMGWHERE = array('branch_id' => $s_id);
	if($status_val == 1){
		echo json_encode(['status'=>"error",'msg'=>"Error in deletion... Branch is Active..."]);
	} else {
		$result = $this->branch->update_branch_status($inputs, $WHERE,$IMGWHERE);
		if ($result) {
			echo json_encode(['status'=>"success",'msg'=>"Branch Details Deleted SuccessFully...."]);
		} else {
			echo json_encode(['status'=>"error",'msg'=>"Error in deletion... Try again later"]);
		}
	}
}


}
