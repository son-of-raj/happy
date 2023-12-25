<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Employee_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_staffs($provider_id){
		$this->db->select('*');
		$this->db->from('employee_basic_details');       
		$this->db->where(array('provider_id'=> $provider_id,'delete_status'=>0));
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}
	public function create_staff($inputs) {        
         if ($this->db->insert('employee_basic_details', $inputs)) {
			return $this->db->insert_id();
		}
    }
	public function create_staff_service($inputs) {        
         if ($this->db->insert('employee_services_list', $inputs)) {		
			return $this->db->insert_id();
		}
    }
	public function get_single_staff($provider_id,$emp_id){
		$this->db->from('employee_basic_details');       
		$this->db->where(array('provider_id'=> $provider_id,'id'=> $emp_id));		
		return $this->db->get()->result_array();
	}
	public function single_staff_Service($provider_id,$emp_id){
		$this->db->from('employee_services_list');       
		$this->db->where(array('provider_id'=> $provider_id,'emp_id'=> $emp_id));		
		return $this->db->get()->result_array();
	}
	public function update_staff($inputs,$emp_id,$provider_id) {        
        $this->db->where(array('provider_id'=> $provider_id,'id'=> $emp_id));
        $this->db->update('employee_basic_details', $inputs); 
        return $this->db->affected_rows() != 0 ? true : false;
    }
	public function update_staff_service($inputs,$emp_id,$provider_id) {        
        $this->db->where(array('provider_id'=> $provider_id,'emp_id'=> $emp_id));
        $this->db->update('employee_services_list', $inputs);
        return $this->db->affected_rows() != 0 ? true : false;
    }
	public function delete_staff($where,$empwhere) {  		
		$inputs['status'] = '2';
		$inputs['delete_status'] = '1';
        $this->db->where($where);
        $this->db->update('employee_basic_details', $inputs);
		
		$this->db->set('delete_status', 1);
        $this->db->where($empwhere);
        $this->db->update('employee_services_list');
        return true;
    }
	public function read_staffs($staff_id){
		$uid = $this->session->userdata('id');
		
		/* Read Staff from Shop Table */
		$sqlqry = "SELECT id,staff_id FROM `shop_services_list` WHERE FIND_IN_SET('".$staff_id."', staff_id) and `provider_id` = ".$uid;
		$shpqry = $this->db->query($sqlqry); 		
		$shpres = $shpqry->result_array();			
		$assign = []; $inputs = ''; $a1='';
		if(count($shpres) > 0){
			return "1";
		} else {
			return "0";
		}	
	}

}
    
?>