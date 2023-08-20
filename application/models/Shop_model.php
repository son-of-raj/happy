<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Shop_model extends CI_Model
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
		unset($inputs['status']);
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
	public function readCategory()
	{
	    return $this->db->get('categories')->result_array();
	}

	public function readSubcategory()
	{
		return $this->db->get_where('subcategories', array('status'=>'1'))->result_array();
	}
	public function readSubSubcategory()
	{
		$user_id = $this->session->userdata('id');
		$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
		$subcategory = $this->db->select('subcategory')->where('id',$user_id)->get('providers')->row()->subcategory;
	    return $this->db->where('category', $category)->where('subcategory', $subcategory)->get('sub_subcategories')->result_array();
	}
	public function fetchservices($category, $subcategory)
    {
        $uid  = $this->session->userdata('id');
		
		/* Read Services from Service Table */	
		$WHERE = array('category' => $category,'subcategory' => $subcategory,'user_id' => $uid, 'status' => 1);
		
        $service_offered = $this->db->select('id, user_id, service_title, currency_code, service_amount, category, subcategory, sub_subcategory, duration, duration_in, service_location, status')->where($WHERE)->order_by('sub_subcategory','ASC')->get('services')->result_array();
		
		
		return $service_offered;
	}
	public function edit_fetchservice($category, $subcategory,$shop_id)
	{
		$uid  = $this->session->userdata('id');
		$WHERE = array('ser.category' => $category,'ser.subcategory' => $subcategory,'ser.user_id' => $uid, 'ser.status' => 1);
		
		$this->db->select('ser.id, ser.user_id, ser.service_title, ser.currency_code, ser.service_amount, ser.category, ser.subcategory, ser.sub_subcategory, ser.duration, ser.duration_in, ser.service_location, ser.status, list.id as listid,  list.service_offer_id, list.staff_id, list.shop_id as list_shopid'); 
		$this->db->from('services AS ser');
		$this->db->join('shop_services_list AS list', 'list.service_offer_id = ser.id AND list.delete_status = 0 AND list.shop_id = '.$shop_id, 'left');
		$this->db->where($WHERE);
		$service_offered = $this->db->get()->result_array();
		return $service_offered;
	}
	public function shopservices($category, $subcategory,$shop_id)
	{
		$uid  = $this->session->userdata('id');
		$WHERE = array('ser.category' => $category,'ser.subcategory' => $subcategory,'ser.user_id' => $uid, 'ser.status' => 1);
		
		$this->db->select('ser.id, ser.user_id, ser.service_title, ser.currency_code, ser.service_amount, ser.category, ser.subcategory, ser.sub_subcategory, ser.duration, ser.duration_in, ser.service_location, ser.status'); 
		$this->db->from('services AS ser');		
		$this->db->where("FIND_IN_SET('".$shop_id."', ser.shop_id)");
		$this->db->where($WHERE);
		$service_offered = $this->db->get()->result_array();
		return $service_offered;
	}
	public function fetchstaffs($sub_subcategory, $shop_id = '')
    {	
		/* Read Staff from Shop Table */
		$uid = $this->session->userdata('id');
       	$assign = $this->allStaffs($shop_id);		

		$category = $this->db->select('category')->where('id',$uid)->get('providers')->row()->category;
		$subcategory = $this->db->select('subcategory')->where('id',$uid)->get('providers')->row()->subcategory;
		
		$WHERE = array('category' => $category,'subcategory' => $subcategory,'sub_subcategory' => $sub_subcategory,'provider_id' => $uid, 'status' => 1);
		
		if(!empty($assign)) {
			$stafflist=$this->db->select('id, CONCAT(first_name, " ", last_name) AS name, designation')->where($WHERE)->where_not_in('id', $assign)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		} else {
			$stafflist=$this->db->select('id,CONCAT(first_name, " ", last_name) AS name, designation')->where($WHERE)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		}
		
		//Output
		return $stafflist;
		
		
    }
	public function allStaffs($shopid=''){
		$uid = $this->session->userdata('id');
		$condt = '';
		if($shopid != '') {
			$condt = " and `shop_id` != ".$shopid;
		}
				
		/* Read Staff from Shop Table */
		$sqlqry = "SELECT GROUP_CONCAT(DISTINCT `staff_id`) as staffs_assigned FROM `shop_services_list` WHERE `staff_id` != '' and `provider_id` = ".$uid." and `delete_status` = 0 ".$condt;
		$shpqry = $this->db->query($sqlqry); 		
		$shpres = $shpqry->result_array();	
		
		$assign = []; $inputs = ''; $a1='';
		if($shpres[0]['staffs_assigned'] != '' && $shpres[0]['staffs_assigned'] != NULL){
			$a1 = $shpres[0]['staffs_assigned'];
		}		
		if(!empty($a1)){
			$inputs = $a1;
		} 
		if($inputs != ''){ 
			$assign = array_values(array_unique(explode(",", $inputs))); 
		}
		/* Read Staff from Shop Table */		
		
		
		return $assign;
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
