<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Staffs_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}	
	
	 /*All Staff Lists and Staff Filter*/
	public function staff_lists(){
		$this->db->select('*');
		$this->db->from('employee_basic_details');       
		$this->db->where(array('delete_status'=>0));
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}	
    public function staffs_filter($username,$email,$from,$to){
          if(!empty($from)) {
          $from_date=date("Y-m-d 00:00:00", strtotime($from));
          }else{
          $from_date='';
          }
          if(!empty($to)) {
          $to_date=date("Y-m-d 23:59:00", strtotime($to));
          }else{
          $to_date='';
          }

          $this->db->select('S.*');
          $this->db->from('employee_basic_details S');
		  $this->db->where(array('delete_status'=>0));
         
          if(!empty($username)){
			  $exparr = explode(" ",$username); print_r($exparr);
			$this->db->where(array('first_name'=>$exparr[0], 'last_name' => $exparr[1]));
          }   
	
		 if(!empty($email)){
			$this->db->where('email',$email);
          } 
          
          if(!empty($from_date)){
			$this->db->where('S.created_at >=',$from_date);
          }
          if(!empty($to_date)){
			$this->db->where('S.created_at <=',$to_date);
          }
          return $this->db->get()->result_array();

    }
	public function staff_editdetails($id){
		return $this->db->get_where('employee_basic_details',array('id'=>$id))->row_array();
	}
	public function update_staff_status($data,$where){		
		$results = $this->db->update('employee_basic_details', $data, $where);
	    return $results;
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
		$sqlqry = "SELECT id,staff_id FROM `shop_services_list` WHERE FIND_IN_SET('".$staff_id."', staff_id)";
		$shpqry = $this->db->query($sqlqry); 		
		$shpres = $shpqry->result_array();			
		$assign = []; $inputs = ''; $a1='';
		if(count($shpres) > 0){
			return "1";
		} else {
			return "0";
		}		
	}
	public function readCategory(){		
	    return $this->db->get('categories')->result_array();
	}
	public function readSubcategory($category){		
	    return $this->db->where('category', $category)->get('subcategories')->result_array();
	}
	public function readSubSubcategory($category,$subcategory){		
	    return $this->db->where('category', $category)->where('subcategory', $subcategory)->get('sub_subcategories')->result_array();
	}
	public function countrycode_details()
    {
        $query  = $this->db->query("SELECT `id`, `country_id`, `country_name` FROM `country_table` WHERE `status` = 1 and `country_id` != 0 ORDER BY `country_name` ASC ");
        $result = $query->result_array();
        return $result;
    }

}
    
?>