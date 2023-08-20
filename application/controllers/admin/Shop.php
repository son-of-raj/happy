<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

 public $data;

 public function __construct() {

  parent::__construct();
  $this->load->model('shop_model','shop');
  $this->load->model('common_model','common_model');
  $this->load->model('booking','book_service');
  $this->data['theme'] = 'admin';
  $this->data['model'] = 'shop';
  $this->data['base_url'] = base_url();
  $this->session->keep_flashdata('error_message');
  $this->session->keep_flashdata('success_message');
  $this->load->helper('user_timezone_helper');
  $this->data['user_role']=$this->session->userdata('role');
}

public function shop_lists()
{
	$this->common_model->checkAdminUserPermission(4);
 extract($_POST);
 
 $this->data['page'] = 'shop_lists';

 if ($this->input->post('form_submit')) 
 {  
   $shop_name = $this->input->post('shop_name');
   $email = $this->input->post('email');  
   $from = $this->input->post('from');
   $to = $this->input->post('to');
   $this->data['list'] =$this->shop->shop_filter($shop_name,$email,$from,$to);
 }
 else
 {
  $this->data['list'] = $this->shop->shop_lists();
}
$this->load->vars($this->data);
$this->load->view($this->data['theme'].'/template');

}

public function shop_details($value='')
{
	$this->common_model->checkAdminUserPermission(4);
  $this->data['page'] = 'shop_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}
public function shop_edit($value)
{
  $this->common_model->checkAdminUserPermission(4);
  if($this->session->userdata('admin_id'))
	{
		if ($this->input->post('form_submit')) {
			removeTag($this->input->post());
			
			$id = $this->input->post('user_id');
			$params['shop_name'] = $this->input->post('shop_name');			
			$params['country_code'] = $this->input->post('country_code');
			$params['contact_no'] = $this->input->post('contact_no'); 
			$params['email'] = $this->input->post('email');  			
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
			
			if ($this->db->update('shops', $params, array("id" => $id))) { 
				$this->session->set_flashdata('success_message', 'Shop Details Updated successfully');
				redirect(base_url() . "shop-lists");
			} else { 				
				$this->session->set_flashdata('error_message', 'Please try again later....');
				redirect(base_url() . "shop-lists");
			}
		} 
		  $this->data['page'] = 'shop_edit'; 
		  $this->data['country'] = $this->countrycode_details();
		  $this->load->vars($this->data);
		  $this->load->view($this->data['theme'].'/template');
	} else {
		redirect(base_url()."admin");
	}
}
public function countrycode_details()
{
	$query  = $this->db->query("SELECT `id`, `country_id`, `country_name` FROM `country_table` WHERE `status` = 1 and `country_id` != 0 ORDER BY `country_name` ASC ");
	$result = $query->result_array();
	return $result;
}
public function check_shop_emailid()
{
  $inputs = $this->input->post('userEmail');
  $id = $this->input->post('userid');
	  
  $this->db->where('status != ',0);
  $this->db->like('email', $inputs, 'match');
   if(!empty($id)) {
	  $this->db->where('id !=', $id);
  }
  $this->db->from('shops');
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
public function check_shop_mobile()
{
  $inputs = $this->input->post('userMobile');
  $ctrycode =$this->input->post('mobileCode');
  $id = $this->input->post('userid');
  
  $this->db->where('status != ',0);
  $this->db->where(array('country_code'=>$ctrycode))->like('contact_no',$inputs,'match','after');
  if(!empty($id)) {
	  $this->db->where('id !=', $id);
  }
  $this->db->from('shops');
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
public function change_shop_status() {       
	$s_id = $this->input->post('shop_id');
	//jaya
	
	$checkStatus = array('1','2','3','4','8');
	$this->db->where_in('status',$chaeckStatus);
	$this->db->where('shop_id',$s_id);
	$this->db->from('book_service');
	$result_status = $this->db->get()->num_rows();
	
	$status_val = $this->input->post('status_val');
	if($result_status <1) {
	
		$inputs['status'] = $status_val;
		$WHERE = array('id' => $s_id);
		$IMGWHERE = array('shop_id' => $s_id);
		$result = $this->shop->update_shop_status($inputs, $WHERE,$IMGWHERE);
		if ($result) {
			echo json_encode(['status'=>"success",'msg'=>"Shop Status Changed SuccessFully...."]);
		} else {
			echo json_encode(['status'=>"error",'msg'=>"Shop Status not changed... Try again later"]);
		}
	} else {
		
		echo json_encode(['status'=>"error",'msg'=>"Shop Status not changed...Have bookings... Try again later"]);
	}
}
public function delete_shop()
{	
    $s_id = $this->input->post('shop_id');
	$status_val = $this->input->post('status_val');
	
	$inputs['status'] = 0;
	$WHERE = array('id' => $s_id);
	$IMGWHERE = array('shop_id' => $s_id);
	if($status_val == 1){
		
		echo json_encode(['status'=>"error",'msg'=>"Error in deletion... Shop is Active...."]);
	} else {
		
		$result = $this->shop->update_shop_status($inputs, $WHERE,$IMGWHERE);
		if ($result) {
			echo json_encode(['status'=>"success",'msg'=>"Shop Details Deleted SuccessFully...."]);
		} else {
			echo json_encode(['status'=>"error",'msg'=>"Error in deletion... Try again later"]);
		}
	}
}


}
