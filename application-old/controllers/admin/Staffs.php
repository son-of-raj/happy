<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffs extends CI_Controller {

 public $data;

 public function __construct() {
	
  parent::__construct();
  $this->load->model('staffs_model','staffs');  
  $this->load->model('common_model','common_model');
  $this->data['theme'] = 'admin';
  $this->data['model'] = 'staffs';
  $this->data['base_url'] = base_url();
  $this->session->keep_flashdata('error_message');
  $this->session->keep_flashdata('success_message');
  $this->load->helper('user_timezone_helper');
  $this->data['user_role']=$this->session->userdata('role'); 
}


public function staff_lists(){ 
	
	$this->common_model->checkAdminUserPermission(4);
	 extract($_POST);
	 
	 $this->data['page'] = 'staff_lists';
	 $this->data['model'] = 'staffs';

	 if ($this->input->post('form_submit')) 
	 {  
	   $username = $this->input->post('username');
	   $email = $this->input->post('email');  
	   $from = $this->input->post('from');
	   $to = $this->input->post('to');
	   $this->data['list'] =$this->staffs->staffs_filter($username,$email,$from,$to);
	 }
	 else
	 {
		  
	  $this->data['list'] = $this->staffs->staff_lists();
	}

			 

	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');

}


public function staff_details($value='')
{
  $this->common_model->checkAdminUserPermission(4);
  $this->data['page'] = 'staff_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}
public function check_staff_emailid()
{
  $inputs = $this->input->post('userEmail');
  $id = $this->input->post('userid');
	  
  $this->db->where('status != ',0);
  $this->db->like('email', $inputs, 'match');
   if(!empty($id)) {
	  $this->db->where('id !=', $id);
  }
  $this->db->from('employee_basic_details');
  $result = $this->db->get()->num_rows();
  if ($result > 0) {
	$isAvailable = FALSE;
  } else {
	$isAvailable = TRUE;
  }
  echo json_encode(
	array(
	  'valid' => $isAvailable
	));
}
public function check_staff_mobile()
{
  $inputs = $this->input->post('userMobile');
  $ctrycode =$this->input->post('mobileCode');
  $id = $this->input->post('userid');
  
  $this->db->where('status != ',0);
  $this->db->where(array('country_code'=>$ctrycode))->like('contact_no',$inputs,'match','after');
  if(!empty($id)) {
	  $this->db->where('id !=', $id);
  }
  $this->db->from('employee_basic_details');
  $result = $this->db->get()->num_rows(); 
  
  if ($result > 0) {
	$isAvailable = FALSE;
  } else {
	$isAvailable = TRUE;
  }
  echo json_encode(
	array(
	  'valid' => $isAvailable
	));
}

public function staff_edit($value)
{
	$this->common_model->checkAdminUserPermission(4);
  
	if($this->session->userdata('admin_id'))
	{
		if ($this->input->post('form_submit')) {
			removeTag($this->input->post());
			
			$id = $this->input->post('user_id');
			$params['first_name'] = $this->input->post('first_name');
			$params['last_name'] = $this->input->post('last_name');
			$params['country_code'] = $this->input->post('country_code');
			$params['contact_no'] = $this->input->post('contact_no'); 
			$params['email'] = $this->input->post('email');  
			if(!empty($this->input->post('password'))){	
				$params['password'] = md5($this->input->post('password'));			
			} 
			$params['updated_at'] = date('Y-m-d H:i:s');
			$array = array();
			$params['availability'] = $this->input->post('availability');
			
			if(!empty($params['availability'][0]['day'])){
				$from = $params['availability'][0]['from_time'];
				$to = $params['availability'][0]['to_time'];
				for ($i=1; $i <= 7; $i++) {
					$array[$i] = array('day'=>$i,'from_time'=>$from,'to_time'=>$to);
				}

			}else{
				if(!empty($params['availability'][0])){
					unset($params['availability'][0]);
				}
				$array = array_map('array_filter', $params['availability']);
				$array = array_filter($array);
			}
			if(!empty($array)){
				$array = array_values($array);
			}			 
			if(empty($params['availability'][0]['from_time'])&&empty($params['availability'][0]['to_time'])){
				$params['all_days'] = 0;
			}else{
				$params['all_days']=1;
			}				
			$params['availability'] = json_encode($array);
			
			if ($this->db->update('employee_basic_details', $params, array("id" => $id))) { 
				$this->session->set_flashdata('success_message', 'Staffs Details Updated successfully');
				redirect(base_url() . "staff-lists");
			} else { 				
				$this->session->set_flashdata('error_message', 'Please try again later....');
				redirect(base_url() . "staff-lists");
			}
		} 
			
			$this->data['page'] = 'staff_edit';
		  
			$this->data['user'] = $this->staffs->staff_editdetails($value);
			$this->data['country'] = $this->staffs->countrycode_details();
		  
			$this->load->vars($this->data);
			$this->load->view($this->data['theme'].'/template');
	} else {
		redirect(base_url()."admin");
	}
}

public function change_staff_status(){
  $id = $this->input->post('staff_id');
  $status = $this->input->post('status_val');
  $provider_id = $this->input->post('provider_id');

  $inputs['status']= $status;
  $WHERE =array('id' => $id);
  
	$shop_assign = $this->db->where('staff_id', $id)->where_not_in('status',[5,6,7])->from('book_service')->count_all_results();
	if($shop_assign > 0){
	   echo json_encode(['status'=>"error",'msg'=>"Staff is assigned to the service provided by the shop.."]);	  
	} else {
		$result=$this->staffs->update_staff_status($inputs,$WHERE);
	 if($result){
		echo json_encode(['status'=>"success",'msg'=>"Staff Status Changed SuccessFully...."]);
	  }else{
		echo json_encode(['status'=>"error",'msg'=>"Staff Status not changed... Try again later"]);
	  }
		
	}
}

public function delete_staff(){
	
	$s_id = $this->input->post('staff_id');		
	$u_id = $this->input->post('provider_id');
	
	$shop_assign = $this->db->where('staff_id', $s_id)->where_not_in('status',[5,6,7])->from('book_service')->count_all_results();
	if($shop_assign > 0){
	   echo json_encode(['status'=>"error",'msg'=>"Staff is assigned to the service provided by the shop.."]);	  
	} else {		   
		$WHERE = array('id' => $s_id, 'provider_id' => $u_id);
		$EMPWHERE = array('emp_id' => $s_id, 'provider_id' => $u_id);
		$result = $this->staffs->delete_staff($WHERE, $EMPWHERE);
		if ($result) {
			echo json_encode(['status'=>"success",'msg'=>"Staff Details Deleted successfully"]);
		} else {
			echo json_encode(['status'=>"success",'msg'=>"Error in deletion...  Try again later"]);
		}
	}
}

}
