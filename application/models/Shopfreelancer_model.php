<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Shopfreelancer_model extends CI_Model
{

	 function __construct() { 
        // Set table name 
        $this->table = 'shops'; 
    } 
     
    
    function getActiveShops($params = array()){ 
        $this->db->select('s.*');
		$this->db->from('shops s');
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
	function getInactiveShops($params = array()){ 
		$this->db->select('s.*');
		$this->db->from('shops s');
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
	public function get_single_shop($shop_id,$provider_id){
		$this->db->select('*');   
		$this->db->from('shops');       
		$this->db->where(array('provider_id'=> $provider_id,'id'=> $shop_id));	
		return $this->db->get()->row_array();
	}
	public function get_shop_info($s_id, $user_id){	
         $this->db->select("s.*");
	     $this->db->from('shops s');	      
	     $this->db->where("s.status = 1 AND s.provider_id ='".$user_id."' AND md5(s.id)='".$s_id."'"); 
		  
	      $result = $this->db->get()->row_array();
          return $result;
    }
	public function get_shopinfo($s_id) {	
         $this->db->select("s.*");
	     $this->db->from('shops s');	      
	     $this->db->where("s.status = 1 AND md5(s.id)='".$s_id."'"); 
		  
	      $result = $this->db->get()->row_array();
          return $result;
    }
	public function create_shop($inputs)
	{
		$this->db->insert('shops',$inputs);
		return $this->db->insert_id(); 
	}
	public function update_shop($inputs,$shop_id,$provider_id) {        
        $this->db->where(array('provider_id'=> $provider_id,'id'=> $shop_id));
        $this->db->update('shops', $inputs); 
        return $this->db->affected_rows() != 0 ? true : false;
    }
	public function insert_shopimage($image)
	{
		$this->db->insert('shops_images',$image);		
		return $this->db->affected_rows() != 0 ? true : false; 
	}
	public function shop_image($shop_id)
	{
		$this->db->select("id,shop_image");
		$this->db->from('shops_images');
		$this->db->where("shop_id",$shop_id);
		$this->db->where("status",1);
		$this->db->order_by('id','ASC');
		return $this->db->get()->result_array();

	}
	public function update_shop_status($inputs, $where,$imgwhere){
		$this->db->where($where);
        $this->db->update('shops', $inputs); 		
		
        $this->db->where($imgwhere)->update('shops_images', $inputs); 
		$this->db->where($imgwhere)->update('shop_services_list', $inputs); 
        return true;
        
	}
	 public function popular_shops(){ 
      
        $this->db->select("s.*");
	    $this->db->from('shops s');	
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
	
	 /*All Shops Lists and Shops Filter*/
	public function shop_lists(){
		$this->db->select('*');
		$this->db->from('shops');       
		$this->db->where('status != ', 0);
		$this->db->order_by('id','desc');
		return $this->db->get()->result_array();
	}	
	
	 public function shop_filter($shop_name,$email,$from,$to){
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
          $this->db->from('shops S');
		  $this->db->where('S.status != ', 0);
         
          if(!empty($shop_name)){
			  $exparr = explode(" ",$shop_name); 
			$this->db->where(array('shop_name'=>$shop_name));
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
