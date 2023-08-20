<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Branch_model extends CI_Model
{

	 function __construct() { 
        // Set table name 
        $this->table = 'branch'; 
    } 
     
    
    function getActiveBranch($params = array()){ 
        $this->db->select('s.*');
		$this->db->from('branch s');
		$this->db->where('s.status',1);
		$this->db->where('s.provider_id',$this->session->userdata('id'));
		$this->db->order_by('id','DESC');
         
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){ 
                if(!empty($params['id'])){ 
                    $this->db->where('id', $params['id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
               
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                }                  
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    }
	function getInactiveBranch($params = array()){ 
		$this->db->select('s.*');
		$this->db->from('branch s');
		$this->db->where('s.status',2);
		$this->db->where('s.provider_id',$this->session->userdata('id'));
		$this->db->order_by('id','DESC');
		
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
            if(array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')){ 
                if(!empty($params['id'])){ 
                    $this->db->where('id', $params['id']); 
                } 
                $query = $this->db->get(); 
                $result = $query->row_array(); 
            }else{ 
                $this->db->order_by('id', 'desc'); 
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
          
                $query = $this->db->get(); 
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
            } 
        } 
         
        // Return fetched data 
        return $result; 
    } 
	public function get_single_branch($branch_id,$provider_id){
		$this->db->select('*');   
		$this->db->from('branch');       
		$this->db->where(array('provider_id'=> $provider_id,'id'=> $branch_id));	
		return $this->db->get()->row_array();
	}
	public function get_branch_info($s_id, $user_id){	
         $this->db->select("s.*");
	     $this->db->from('branch s');	      
	     $this->db->where("s.status = 1 AND s.provider_id ='".$user_id."' AND md5(s.id)='".$s_id."'"); 
		  
	      $result = $this->db->get()->row_array();
          return $result;
    }
	public function get_branchinfo($s_id) {	
         $this->db->select("s.*");
	     $this->db->from('branch s');	      
	     $this->db->where("s.status = 1 AND md5(s.id)='".$s_id."'"); 
		  
	      $result = $this->db->get()->row_array();
          return $result;
    }
	public function create_branch($inputs)
	{
		$this->db->insert('branch',$inputs); 
		return $this->db->insert_id(); 
	}
	public function update_branch($inputs,$branch_id,$provider_id) {        
        $this->db->where(array('provider_id'=> $provider_id,'id'=> $branch_id));
        $this->db->update('branch', $inputs); 
        return $this->db->affected_rows() != 0 ? true : false;
    }
	public function insert_branchimage($image)
	{
		$this->db->insert('branch_images',$image);		
		return $this->db->affected_rows() != 0 ? true : false; 
	}
	public function branch_image($branch_id)
	{
		$this->db->select("id,branch_image");
		$this->db->from('branch_images');
		$this->db->where("branch_id",$branch_id);
		$this->db->where("status",1);
		$this->db->order_by('id','ASC');
		return $this->db->get()->result_array();

	}
	public function update_branch_status($inputs, $where,$imgwhere){
		$this->db->where($where);
        $this->db->update('branch', $inputs); 		
		
        $this->db->where($imgwhere)->update('branch_images', $inputs); 
		$this->db->where($imgwhere)->update('branch_services_list', $inputs); 
        return true;
        
	}
	 public function popular_branchs(){ 
      
        $this->db->select("s.*");
	    $this->db->from('branch s');	
		$this->db->where('s.status',1);
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.shop_location',$this->session->userdata('current_location'),'after');
        }
        $this->db->group_by('s.id');
        $this->db->order_by('s.total_views','DESC');
        $this->db->limit(10);
        $query = $this->db->get(); $data = array(); 
		if($query !== FALSE && $query->num_rows() > 0){ 
			$data = $query->result_array(); 
		} 
		return $data;
    }
	public function provider_hours($staff_id, $provider_id){
        return $this->db->select("all_days,availability")->where('provider_id',$provider_id)->where('id',$staff_id)->get('employee_basic_details')->row_array(); 
    }
	public function get_bookings($service_date,$service_id){
        return $this->db->where(array('service_date'=>$service_date,'service_id'=>$service_id))->get('book_staff_service')->result_array();
    } 
	public function user_get_bookings($service_date,$from,$to){
        return $this->db->where(array('service_date'=>$service_date,'from_time'=>$from,'to_time'=>$to))->get('book_staff_service')->result_array();
    } 
	public function booking_success($inputs){
		$this->db->insert('book_staff_service', $inputs);
		return $this->db->insert_id();
     }
	function hideEmailAddress($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			list($first, $last) = explode('@', $email);
			$first = str_replace(substr($first, '0'), str_repeat('*', strlen($first)-3), $first);
			$last = explode('.', $last);
			$last_domain = str_replace(substr($last['0'], '0'), str_repeat('*', strlen($last['0'])-1), $last['0']);
			$hideEmailAddress = $first.'@'.$last_domain.'.'.$last['1'];
			return $hideEmailAddress;
		}
	}
	
	 /*All Branch Lists and Branch Filter*/
	public function branch_lists(){
		$this->db->select('*');
		$this->db->from('branch');       
		$this->db->where('status != ', 0);
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}	
	
	 public function branch_filter($shop_id,$branch_name,$email,$from,$to){
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
          $this->db->from('branch S');
		  $this->db->where('S.status != ', 0);
		  
		  if(!empty($shop_id)){			  
			$this->db->where(array('shop_id'=>$shop_id));
          }   
	
         
          if(!empty($branch_name)){			  
			$this->db->where(array('branch_name'=>$branch_name));
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
		
}
?>
