<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class User_booking extends CI_Model{ 
     
    function __construct() { 
        // Set table name 
        $this->table = 'book_service'; 
    } 
     
    /* 
     * Fetch records from the database 
     * @param array filter data based on the passed parameters 
     */ 

    function getRows($params = array()){ 
    $this->db->select("b.*,s.service_title,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$this->session->userdata('id'));
		$this->db->where("b.status != 8"); //Hide Onhold booking
		$this->db->where("b.guest_parent_bookid = 0");
		
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

    function user_invoices($limit, $end, $where, $search, $type)
    {
        if ($type == 'count') 
        {
            $this->db->select('COUNT(book_service.id) as cnt', false);
        }
        else
        {
            $this->db->select('book_service.id, book_service.service_id, book_service.provider_id, book_service.user_id, book_service.shop_id, book_service.staff_id, book_service.location, book_service.service_date, book_service.amount, book_service.currency_code, book_service.from_time, book_service.to_time, book_service.cod, book_service.home_service, book_service.request_date, book_service.request_time,  book_service.updated_on, services.service_title, services.service_amount, services_image.service_image, providers.name as provider_name, providers.profile_img, users.name as user_name, users.profile_img as user_profile_img','cod');
        }
        $this->db->join('services','book_service.service_id=services.id','left');
        $this->db->join('services_image','book_service.service_id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
        $this->db->join('providers','book_service.provider_id=providers.id','LEFT');
        $this->db->join('users','book_service.user_id=users.id','LEFT');
        $this->db->where($where);
        if (isset($search) && !empty($search)) 
        {
            foreach ($search as $key => $val) 
            {
                $this->db->like($key, $val);
            }
        }
        if ($type == 'count')  
        {
            $query =  $this->db->get('book_service')->row_array();
        }
        else
        {
            if (isset($limit) && !empty($limit)) {
                $this->db->limit($limit,$end);
            }
            $this->db->order_by('book_service.id','DESC');
            $query =  $this->db->get('book_service')->result_array();
        }
        return $query;
    }

    public function service_details($where_data)
    {
        $this->db->select('services.id, services.service_title, services.service_sub_title, services.service_amount, services.currency_code, categories.category_name, subcategories.subcategory_name, sub_subcategories.sub_subcategory_name');
        $this->db->join('categories', 'services.category = categories.id', 'left');
        $this->db->join('subcategories', 'services.subcategory = subcategories.id', 'left');
        $this->db->join('sub_subcategories', 'services.sub_subcategory = sub_subcategories.id', 'left');
        $this->db->where($where_data);
        $query = $this->db->get("services")->row_array();
        return $query;
    }
    public function user_details($where_data)
    {
        $this->db->select('users.id, users.name, users.mobileno, users.email, users.gender, user_address.address, user_address.pincode, country_table.country_name, state.name as state_name, city.name as city_name');
        $this->db->join('user_address', 'users.id = user_address.user_id', 'left');
        $this->db->join('country_table', 'user_address.country_id = country_table.id', 'left');
        $this->db->join('state', 'user_address.state_id = state.id', 'left');
        $this->db->join('city', 'user_address.city_id = city.id', 'left');
        $this->db->where($where_data);
        $query = $this->db->get("users")->row_array();
        return $query;
    }

    public function provider_details($where_data)
    {
        $this->db->select('providers.id, providers.name, providers.mobileno, providers.email, provider_address.address, provider_address.pincode, country_table.country_name, state.name as state_name, city.name as city_name');
        $this->db->join('provider_address', 'providers.id = provider_address.provider_id', 'left');
        $this->db->join('country_table', 'provider_address.country_id = country_table.id', 'left');
        $this->db->join('state', 'provider_address.state_id = state.id', 'left');
        $this->db->join('city', 'provider_address.city_id = city.id', 'left');
        $this->db->where($where_data);
        $query = $this->db->get("providers")->row_array();
        return $query;
    }
}