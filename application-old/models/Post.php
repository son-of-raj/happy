<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Post extends CI_Model{ 
     
    function __construct() { 
        // Set table name 
        $this->table = 'services'; 
    } 
     
    /* 
     * Fetch records from the database 
     * @param array filter data based on the passed parameters 
     */ 
    function getRows($params = array()){
		  $this->db->select("services.currency_code,services.id,services.user_id,services.service_title,services.service_amount,services.mobile_image,c.category_name,sc.subcategory_name,services.rating_count,services.service_location,services.shop_id"); 
        $this->db->from($this->table); 
           $this->db->join('categories c', 'c.id = services.category', 'LEFT');
          $this->db->join('subcategories sc', 'sc.id = services.subcategory', 'LEFT');
           $this->db->where("services.status = 1");
          $this->db->where('services.user_id',$this->session->userdata('id'));
		  
		  
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
     function getInactiveRows($params = array()){ 
        $this->db->select("services.currency_code,services.id,services.user_id,services.service_title,services.service_amount,services.mobile_image,c.category_name,sc.subcategory_name,services.rating_count,services.service_location,services.shop_id"); 
        $this->db->from($this->table); 
           $this->db->join('categories c', 'c.id = services.category', 'LEFT');
          $this->db->join('subcategories sc', 'sc.id = services.subcategory', 'LEFT');
           $this->db->where("services.status = 2");
          $this->db->where('services.user_id',$this->session->userdata('id'));
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

    function checkexistoffer($service_id, $start_date, $end_date) {
        $this->db->select("id");
        $this->db->where('service_id',$service_id);
		$this->db->where("df",0);
        $this->db->where('((start_date BETWEEN "'.$start_date. '" and "'.$end_date.'") or (end_date BETWEEN "'.$start_date. '" and "'.$end_date.'"))');
        $query = $this->db->get('service_offers')->row_array();
        return $query;
    }
	function checkexistoffer_update($service_id, $start_date, $end_date,$offerid) {
        $this->db->select("id");
        $this->db->where('service_id',$service_id);
        $this->db->where('((start_date BETWEEN "'.$start_date. '" and "'.$end_date.'") or (end_date BETWEEN "'.$start_date. '" and "'.$end_date.'"))');
        $this->db->where('id != ',$offerid);
        $query = $this->db->get('service_offers')->row_array();
        return $query;
    }

    function insertserviceoffer($data) {
        $this->db->insert('service_offers',$data);
        return true;
    }

    function offerrows($params = array()){ 
        $this->db->select("service_offers.id, service_offers.start_date, service_offers.end_date, service_offers.start_time, service_offers.end_time, service_offers.offer_percentage, service_offers.status, service_offers.created_at, service_offers.service_id, services.service_title, services.currency_code, services.service_amount");
        $this->db->from('service_offers'); 
        $this->db->join('services', 'service_offers.service_id = services.id', 'LEFT');
        $this->db->where(["services.user_id"=>$this->session->userdata('id'), 'service_offers.df'=>0]);
        if(array_key_exists("where", $params)){ 
            foreach($params['where'] as $key => $val){ 
                $this->db->where($key, $val); 
            } 
        } 
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }
        else
        { 
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
    public function deleteserviceoffers($where) {       
        $inputs['status'] = '1';
        $inputs['df'] = '1';
        $this->db->where($where);
        $this->db->update('service_offers', $inputs);
        
        return true;
    }
    /* Offers Admin Panel */
    public function read_serviceoffers(){
        $this->db->select("O.*, COUNT(O.id) AS total_offers, P.name AS provider_name");
        $this->db->from('service_offers O');  
        $this->db->join('providers P', 'P.id = O.provider_id', 'LEFT');     
        $this->db->where('O.df', 0);
        $this->db->group_by('O.provider_id');
        $query = $this->db->get();  
        return $query->result_array();
    }
    public function offers_filter($username,$from,$to){
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

        $this->db->select("O.*, COUNT(O.id) AS total_offers, P.name AS provider_name");
        $this->db->from('service_offers O');  
        $this->db->join('providers P', 'P.id = O.provider_id', 'LEFT');     
        
      if(!empty($username)){
        $this->db->where('O.provider_id',$username);
      }
      
      
        if(!empty($from_date)){
        $this->db->where('O.created_at >=',$from_date);
      }
      if(!empty($to_date)){
        $this->db->where('O.created_at <=',$to_date);
      }
      $this->db->where('O.df', 0);
        $this->db->group_by('O.provider_id');
      
      return $this->db->get()->result_array();
        }
    public function get_offers_details($provider_id){
        $this->db->select("O.id, O.start_date, O.end_date, O.offer_percentage, O.status, O.created_at, O.service_id, S.service_title, S.currency_code, S.service_amount, S.sub_subcategory");
        $this->db->from('service_offers O'); 
        $this->db->join('services S', 'O.service_id = S.id', 'LEFT');
        $this->db->where(array("S.user_id"=>$provider_id, 'O.df'=>0));
        $query = $this->db->get();  
        return $query->result_array();
    }
        
    /* Coupons */
    function readServices(){
        $this->db->select("S.id, S.service_title,S.currency_code, S.service_amount");
        $this->db->from('services S');              
        $this->db->where("S.user_id", $this->session->userdata('id'));
        $this->db->where('S.status', 1);  
		$this->db->order_by('S.id', 'DESC');  		
        $query = $this->db->get();  
        return $query->result_array();
    }
    
    function coupon_details($user_id){
        $this->db->select("C.*, S.service_title, S.currency_code,  S.service_amount");
        $this->db->from('service_coupons C'); 
        $this->db->join('services S', 'S.id = C.service_id', 'LEFT');
        $this->db->where('C.status !=', 0);
        $this->db->where('C.provider_id',$user_id);
        $this->db->where('S.user_id', $user_id);
        $this->db->order_by('C.id', 'DESC');
        $query = $this->db->get();  
        return $query->result_array();
    }
    public function update_coupon_status($inputs,$where) { 
        $this->db->where($where);
        $this->db->update('service_coupons', $inputs);      
        return true;
    }
    function checkcouponname($coupon_name,$id='') {
        $this->db->select("id");
        $this->db->where('coupon_name',$coupon_name);
        if($id!=''){
            $this->db->where('id!=', $id);
        }
        $query = $this->db->get('service_coupons')->row_array();       
        return $query;
    }
    /* Coupons Admin Panel */
    public function read_servicecoupons(){
        $this->db->select("C.*, COUNT(C.service_id) AS total_service, P.name AS provider_name");
        $this->db->from('service_coupons C');  
        $this->db->join('providers P', 'P.id = C.provider_id', 'LEFT');     
        $this->db->where('C.status !=', 0);
        $this->db->group_by('C.coupon_name');
        $query = $this->db->get();  
        return $query->result_array();
    }
    public function coupons_filter($username,$from,$to){
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

        $this->db->select("C.*, COUNT(C.service_id) AS total_service, P.name AS provider_name");
        $this->db->from('service_coupons C');  
        $this->db->join('providers P', 'P.id = C.provider_id', 'LEFT');     
        
      if(!empty($username)){
        $this->db->where('C.provider_id',$username);
      }
      
      
        if(!empty($from_date)){
        $this->db->where('C.created_at >=',$from_date);
      }
      if(!empty($to_date)){
        $this->db->where('C.created_at <=',$to_date);
      }
      $this->db->where('C.status !=', 0);
      
      return $this->db->get()->result_array();
    }
    public function get_coupons_details($coupon_id){
        $this->db->select("C.*, S.service_title, S.currency_code,  S.service_amount");
        $this->db->from('service_coupons C'); 
        $this->db->join('services S', 'S.id = C.service_id', 'LEFT');
        $this->db->where('C.status !=', 0);
        $this->db->where('C.id',$coupon_id);        
        $this->db->order_by('C.id', 'DESC');
        $query = $this->db->get();  
        return $query->result_array();
    }
    
    /* Rewards */
	function reward_details($provider_id) {
        $this->db->select("service_rewards.id, service_rewards.reward_type, service_rewards.reward_discount, service_rewards.description, service_rewards.status, service_rewards.created_at, service_rewards.total_visit_count, services.service_title, services.service_amount, services.currency_code, users.name as user_name, users.mobileno as user_mobile");
        $this->db->join('services', 'service_rewards.service_id = services.id', 'LEFT');
        $this->db->join('users', 'service_rewards.user_id = users.id', 'LEFT');
        $this->db->where('service_rewards.provider_id',$provider_id);
        $this->db->order_by('service_rewards.id', 'DESC');
        $query = $this->db->get('service_rewards')->result_array();
        return $query;
    }
	
    public function list_user_details(){
        
        $this->db->select("S.id, S.provider_id, S.user_id, COUNT(S.user_id) AS total_count, U.name AS user_name, U.profile_img");
        $this->db->from('book_service S');  
        $this->db->join('users U', 'U.id = S.user_id', 'LEFT'); 
        $this->db->where('S.provider_id',$this->session->userdata('id'));
        $this->db->where('S.status', 6);
        $this->db->where('U.status', 1);
        $this->db->group_by('S.user_id');
        $query = $this->db->get(); 
        return $query->result_array();
    }
	

    public function list_services($where_data)
    {
        $this->db->select("id, service_title, currency_code, service_amount");
        $this->db->where($where_data);
        $query = $this->db->get('services')->result_array();
        return $query;
    }

    function insertservicereward($data) {
        $this->db->insert('service_rewards',$data);
        return true;
    }
}
