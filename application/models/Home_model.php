<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
    	 date_default_timezone_set('Asia/kolkata');

	}
         
    public function get_category($category = '')
	  {
		// $category = '';
		// if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
		// 	$user_id = $this->session->userdata('id');
		// 	$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
		// }
		// if ($this->session->userdata('usertype') == 'user') {
		// 	$userdet = $this->db->where('id', $this->session->userdata('id'))->get('users')->row_array();
		// }
			
	   $this->db->select('c.id,c.category_name,c.category_image, (SELECT COUNT(s.id) FROM services AS s LEFT JOIN `subscription_details` as `sd` ON `sd`.`subscriber_id`=`s`.`user_id` WHERE s.category=c.id AND s.status=1 AND sd.expiry_date_time >="'.date('Y-m-d').'" ) AS category_count');
	   $this->db->from('categories c');
	   $this->db->where('c.status',1);
	   if($category != ''){
		   $this->db->where('c.id',$category);
	   }
	  //   if ($userdet['gender']!='') {
		// 	$this->db->where("FIND_IN_SET('".$userdet['gender']."', c.gender_type)");
		// }
	   $this->db->order_by('category_count','DESC');
	   $this->db->limit(6);
	   $result = $this->db->get()->result_array();
	   return $result;

		
	}

  //palani

  public function get_categry($category = '')
  {
    $this->db->select('c.id,c.category_name,c.category_image, (SELECT COUNT(s.id) FROM services AS s LEFT JOIN `subscription_details` as `sd` ON `sd`.`subscriber_id`=`s`.`user_id` WHERE s.category=c.id AND s.status=1 AND sd.expiry_date_time >="'.date('Y-m-d').'" ) AS category_count');
	   $this->db->from('categories c');

     if($category != ''){
      $this->db->where('c.id',$category);
    }
    $this->db->order_by('category_count','DESC');
    $this->db->limit(6);
    $result = $this->db->get()->result_array();
    
    return $result;
    
  }
    public function get_subcategory($id)
	{
			
	   $this->db->select('c.id,c.subcategory_name,c.subcategory_image, (SELECT COUNT(s.id) FROM services AS s LEFT JOIN `subscription_details` as `sd` ON `sd`.`subscriber_id`=`s`.`user_id` WHERE s.subcategory=c.id AND s.status=1 AND sd.expiry_date_time >="'.date('Y-m-d').'" ) AS category_count');
	   $this->db->from('subcategories c');
	   $this->db->where('c.status',1);
	   $this->db->where('c.category',$id);
	   $this->db->order_by('category_count','DESC');
	   $result = $this->db->get()->result_array();
	   return $result;

		
	}
	public function get_subcategoryy()
	{
		$subcategory = ''; $category = '';
		if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
			$subcategory = $this->db->select('subcategory')->where('id',$user_id)->get('providers')->row()->subcategory;
		} 
			
	   $this->db->select('c.id,c.subcategory_name,c.subcategory_image, (SELECT COUNT(s.id) FROM services AS s LEFT JOIN `subscription_details` as `sd` ON `sd`.`subscriber_id`=`s`.`user_id` WHERE s.subcategory=c.id AND s.status=1 AND sd.expiry_date_time >="'.date('Y-m-d').'" ) AS category_count');
	   $this->db->from('subcategories c');
	   $this->db->where('c.status',1);			   
	   if($subcategory != ''){
		   $this->db->where('c.category',$category);
		   $this->db->where('c.id',$subcategory);
	   }
	   $this->db->order_by('category_count','DESC');
	   $result = $this->db->get()->result_array();
	   return $result;

		
	}
	public function get_category_by_gender($gender_type)
	{
		$category = '';
		if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
		}
			
	   $this->db->select('c.id,c.category_name,c.category_image, (SELECT COUNT(s.id) FROM services AS s LEFT JOIN `subscription_details` as `sd` ON `sd`.`subscriber_id`=`s`.`user_id` WHERE s.category=c.id AND s.status=1 AND sd.expiry_date_time >="'.date('Y-m-d').'" ) AS category_count');
	   $this->db->from('categories c');
	   $this->db->where('c.status',1);
	   if($category != ''){
		   $this->db->where('c.id',$category);
	   }
	   if ($gender_type!='') {
            $this->db->where("FIND_IN_SET('".$gender_type."', c.gender_type)");
       }
	   $this->db->order_by('category_count','DESC');
	   $this->db->limit(6);
	   $result = $this->db->get()->result_array();
  
	   return $result;	
	}
	public function get_category_name($id)
	{
		return $this->db->select('category_name')->where('id',$id)->get('categories')->row()->category_name;
	}
	public function get_category_id($category_name)
	{
		return $this->db->select('id')->where('category_name',rawurldecode(utf8_decode($category_name)))->get('categories')->row()->id;
	}
	public function get_category_slug($category_name)
	{
		return $this->db->select('id')->where('category_slug',rawurldecode(utf8_decode($category_name)))->get('categories')->row()->id;
	}
	public function get_subcategory_id($subcategory_name)
	{
		return $this->db->select('id')->where('subcategory_name',rawurldecode(utf8_decode($subcategory_name)))->get('subcategories')->row()->id;
	}
	public function get_subcategory_slug($subcategory_name)
	{
		return $this->db->select('id')->where('subcategory_slug',rawurldecode(utf8_decode($subcategory_name)))->get('subcategories')->row()->id;
	}	
	public function get_subsubcategory_id($sub_subcategory_name)
	{
		return $this->db->select('id')->where('sub_subcategory_name',rawurldecode(utf8_decode($sub_subcategory_name)))->get('sub_subcategories')->row()->id;
	}
	public function get_subsubcategory($category = ''){
		if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$category = $this->db->select('category')->where('id',$user_id)->get('providers')->row()->category;
			$subcategory = $this->db->select('subcategory')->where('id',$user_id)->get('providers')->row()->subcategory;
			return $this->db->where('category', $category)->where('subcategory', $subcategory)->order_by("sub_subcategory_name","ASC")->get('sub_subcategories')->result_array();
		} else {
			if($category != '')  $this->db->where('category', $category);
			return $this->db->where('status', 1)->order_by("sub_subcategory_name","ASC")->get('sub_subcategories')->result_array();
		}
	}
	
	public function get_service()
	{
	
        $radius = (settingValue('radius'))?settingValue('radius'):'0';

        $this->db->select("s.id, s.user_id,s.shop_id, s.service_title, s.service_amount, s.service_location, s.service_image, c.category_name, s.currency_code, s.shop_id");
		
		/* Distance Query */
		$longitude = $this->session->userdata('longitude') ?? '';
		$latitude = $this->session->userdata('latitude') ?? '';
		
		if($latitude != '' && $longitude != ''){
			$this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - s.service_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(s.service_latitude * pi()/180) * POWER(SIN(($longitude  - s.service_longitude) * pi()/180 / 2), 2) )) AS distance");
			$this->db->having('distance <=',$radius);
		}
		/* Distance Query */  
		  
	      $this->db->from('services s');
	      $this->db->join('categories c', 'c.id = s.category', 'LEFT');
	      $this->db->where("s.status = 1");
          $this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id','LEFT');
          $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
		  if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$this->db->where("s.user_id", $user_id);
		  }
		  
        if(!empty($this->session->userdata('user_address'))){
          
           $this->db->like("s.service_location",$this->session->userdata('user_address'));
           
        }
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like("s.service_location",$this->session->userdata('current_location'));
        }
		
     
	      $this->db->order_by('s.total_views','DESC');
	      $this->db->limit(10);
	      $query = $this->db->get(); $result = array(); if($query !== FALSE && $query->num_rows() > 0){ $result = $query->result_array(); }

        if(count($result)==0){
            $this->db->select("s.id,s.user_id,s.shop_id,s.service_title,s.service_amount,s.service_location,s.service_image,c.category_name,s.currency_code");
          $this->db->from('services s');
          $this->db->join('categories c', 'c.id = s.category', 'LEFT');
          $this->db->where("s.status = 1");
          $this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id','LEFT');
          $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
          if(!empty($this->session->userdata('current_location'))){
            $this->db->like("s.service_location",$this->session->userdata('current_location'));
          }
		  
		  if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$this->db->where("s.user_id", $user_id);
		  }
       
          $this->db->order_by('s.total_views','DESC');
          $this->db->limit(10);
          $result = $this->db->get()->result_array();
          return $result;
        }else{
          return $result;
        }
          
          
    }

    public function get_service_details($inputs)
	{
	
        $this->db->select("s.*,c.category_name");
	      $this->db->from('services s');
	      $this->db->join('categories c', 'c.id = s.category', 'LEFT');
	      $this->db->where("s.status = 1 AND md5(s.id)='".$inputs['id']."'");
	      $result = $this->db->get()->row_array();
          return $result;
    }
	public function get_servicedetails($inputs)
	{
		
		if (empty($this->session->userdata('id')) && empty($this->session->userdata('admin_id'))) { 
            echo '<h1 align="center">Permissions Denied - Sorry You are not allowed to access this service feature</h1>';die;
        }
		$user_type = $this->session->userdata('usertype'); 
		if($user_type == 'user') { 
			$this->db->select('*');           
            $this->db->where('md5(id) =', $inputs['uid']);
			$this->db->where('id =', $this->session->userdata('id'));
            $this->db->from('users');
            $result = $this->db->get()->num_rows();			
			if($result == 0){
				echo '<h1 align="center">Permissions Denied - Sorry You are not allowed to access this service feature</h1>';die;
			}
		}
		if($user_type=="provider" || $user_type=="freelancer"){ 
			$this->db->select('*');           
            $this->db->where('md5(service_for_userid) =', $inputs['uid']);
			$this->db->where('user_id =', $this->session->userdata('id'));
            $this->db->from('services');
            $result = $this->db->get()->num_rows();			
			if($result == 0){
				echo '<h1 align="center">Permissions Denied - Sorry You are not allowed to access this service feature</h1>';die;
			}
		}
        $this->db->select("s.*,c.category_name");
	    $this->db->from('services s');
	    $this->db->join('categories c', 'c.id = s.category', 'LEFT');
	    $this->db->where("s.status = 3 AND md5(s.id)='".$inputs['id']."'");
		$result = $this->db->get()->row_array();
		if($result['id'] > 0){
			return $result;
		} else {
			echo '<h1 align="center">Service Link Expired !!!!</h1>';die;
		}        
    }

    function get_all_service($params = array(),$inputs=array()){
      
    	$radius = (settingValue('radius'))?settingValue('radius'):'0';

      // echo '<pre>'; print_r($params); print_r($inputs); exit;

		$this->db->select("s.id,s.user_id,s.shop_id,s.service_title,s.service_amount,s.service_location,s.service_image,c.category_name,s.currency_code,shop_id");
      
		if($inputs['service_latitude'] == '') {
			$latitude = '';
		} else {
			$latitude = $inputs['service_latitude'];
		}
		if($inputs['service_longitude'] == '') {
			$longitude = '';
		} else {
			$longitude = $inputs['service_longitude'];
		}

		if($latitude != '' && $longitude != ''){
			$this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - s.service_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(s.service_latitude * pi()/180) * POWER(SIN(($longitude  - s.service_longitude) * pi()/180 / 2), 2) )) AS distance");
			$this->db->having('distance <=',$radius);
		}
		/* Distance Query */
		
		$this->db->from('services s');
		$this->db->join('categories c', 'c.id = s.category', 'LEFT');
		$this->db->join('sub_subcategories ssc', 'ssc.id = s.sub_subcategory', 'LEFT');		
		$this->db->where("s.status = 1");
		$this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id','LEFT');
		$this->db->where('sd.expiry_date_time>=',date('Y-m-d')); 
		
  
		// 	$this->db->where('s.user_id =',$this->session->userdata('id')); 
		// }
		
		if(isset($inputs['min_price']) && !empty($inputs['min_price']) && isset($inputs['max_price']) && !empty($inputs['max_price']))
		{
			$this->db->where("(s.service_amount BETWEEN " . $inputs['min_price'] . " AND " . $inputs['max_price'] . ")");
		}
		
		if(isset($inputs['sub_subcategories']) && !empty($inputs['sub_subcategories']))
		{
			$this->db->where_in('s.sub_subcategory',$inputs['sub_subcategories']);
		}

	    if(isset($inputs['common_search']) && !empty($inputs['common_search']))
	    {  
	     	$this->db->group_start();
            $this->db->like('s.service_title', $inputs['common_search'],'match');
            $this->db->group_end();
	    }
		if((isset($inputs['user_address']) && !empty($inputs['user_address'])) && $latitude=='')
        {  
          $this->db->like('s.service_location', $inputs['user_address']);
           
        }
	    if(empty($inputs['user_address']) && $longitude == '' && $latitude == ''){ 
		  }
      
	     if(isset($inputs['categories']) && !empty($inputs['categories']))
	     {
	     	$this->db->where('s.category',$inputs['categories']); 
	     }
   
	     if(isset($inputs['subcategories']) && !empty($inputs['subcategories']))
	     {
	     	$this->db->where('s.subcategory',$inputs['subcategories']);
	     } 
		 
	     if(isset($inputs['sort_by']) && !empty($inputs['sort_by']))
	     {
	     	if($inputs['sort_by']==1)
	     	{
	     		$this->db->order_by('s.service_amount','ASC');
	     	}
	     	if($inputs['sort_by']==2)
	     	{
	     		$this->db->order_by('s.service_amount','DESC');
	     	}
	     	if($inputs['sort_by']==3)
	     	{
	     		$this->db->order_by('s.id','DESC');
	     	}

	     }
	     else
	     {
	     	if(isset($latitude) && !empty($latitude)){
				 $this->db->order_by('distance','ASC');
			} else {
				$this->db->order_by('s.service_latitude','ASC');
			}
	     }
            
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
		   $query = $this->db->get(); 
		   $result = ($query)?$query->num_rows():FALSE; 
        }else{ 
              
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
                 
                $query = $this->db->get(); 
               $result = ($query)?$query->result_array():FALSE;  
            
        } 

        // Return fetched data 
      //  echo '<pre>'; print_r($result);
      //   exit;
        return $result; 
       
    } 
	
	/* New Search - Get All Shops */
	
	
	 function get_allshops($params = array(),$inputs=array()){ 

	 	$radius = (settingValue('radius'))?settingValue('radius'):'0';
	 	
		$this->db->select("s.id,s.provider_id ,s.shop_name ,s.shop_location ,c.category_name,s.shop_latitude, s.shop_longitude, s.country,s.city,s.state");
		
		/* Distance Query */
		if($inputs['service_latitude'] == '') {
			$latitude = $this->session->userdata('latitude');
		} else {
			$latitude = $inputs['service_latitude'];
		}
		if($inputs['service_longitude'] == '') {
			$longitude = $this->session->userdata('longitude');
		} else {
			$longitude = $inputs['service_longitude'];
		}

		if($latitude != '' && $longitude != ''){
			$this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - s.shop_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(s.shop_latitude * pi()/180) * POWER(SIN(($longitude  - s.shop_longitude) * pi()/180 / 2), 2) )) AS distance");
			$this->db->having('distance <=',$radius);
		}
		/* Distance Query */
		
		$this->db->from('shops s');
		$this->db->join('categories c', 'c.id = s.category', 'LEFT');
		$this->db->join('sub_subcategories ssc', 'ssc.id = s.sub_subcategory', 'LEFT');		
		$this->db->where("s.status = 1");
		$this->db->join('subscription_details as sd','sd.subscriber_id=s.provider_id','LEFT');
		$this->db->where('sd.expiry_date_time>=',date('Y-m-d')); 
		
		if($this->session->userdata('usertype')=="provider" || $this->session->userdata('usertype')=="freelancer"){
			$this->db->where('s.provider_id =',$this->session->userdata('id')); 
		}

	    if(isset($inputs['common_search']) && !empty($inputs['common_search']))
	    {  
	     	$this->db->group_start();
            $this->db->like('s.shop_name', $inputs['common_search'],'match');            
            $this->db->group_end();
	    }

		if(isset($inputs['user_address']) && !empty($inputs['user_address']))
        {    
          $this->db->like('s.shop_location', $inputs['user_address']);           
        }
	    if(empty($inputs['user_address']) && $longitude == '' && $latitude == ''){ 			
			$this->db->like('s.shop_location', $this->session->userdata('current_location'));
		}
	    if(isset($inputs['categories']) && !empty($inputs['categories']))
	    {
	     	$this->db->where('s.category',$inputs['categories']);
	    }
	    if(isset($inputs['subcategories']) && !empty($inputs['subcategories']))
	    {
	     	$this->db->where('s.subcategory',$inputs['subcategories']);
	    } 
		
		 
		 
	    if(isset($inputs['sort_by']) && !empty($inputs['sort_by']))
	    {
	     	if($inputs['sort_by']==1 || $inputs['sort_by']==2)
	     	{
	     		$this->db->order_by('s.shop_latitude','ASC');
	     	}
	     	if($inputs['sort_by']==3)
	     	{
	     		$this->db->order_by('s.id','DESC');
	     	}
	    }
	    else
	    {
	     	if(isset($latitude) && !empty($latitude)){
				 $this->db->order_by('distance','ASC');
			} else {
				$this->db->order_by('s.shop_latitude','ASC');
			}
	    }
         
        
         
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
		   $query = $this->db->get(); 
		   $result = ($query)?$query->num_rows():FALSE; 
        }else{ 
              
                if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit'],$params['start']); 
                }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                    $this->db->limit($params['limit']); 
                } 
                 
                $query = $this->db->get();
               $result = ($query)?$query->result_array():FALSE;  
            
        } 
        return $result; 
    } 
	
	/* New Search - Get All Shops */
	
       public function get_pending_bookinglist($provider_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.provider_id",$provider_id);
        $this->db->where("b.status",1);
        $this->db->order_by("b.id","DESC");
       
        $result = $this->db->get()->result_array();
        return $result;

     }


      public function get_reject_bookinglist($provider_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.provider_id",$provider_id);
        $this->db->where("b.status",5);
        $this->db->order_by("b.id","DESC");
       
        $result = $this->db->get()->result_array();
        return $result;

     }



	 public function get_bookinglist($provider_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.provider_id",$provider_id);
        $this->db->order_by("b.id","DESC");
       
        $result = $this->db->get()->result_array();
        return $result;

     }

      public function completed_bookinglist($provider_id)
     {
        $this->db->select("b.*,s.service_title,s.service_image,s.shop_id,s.shop_id,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.provider_id",$provider_id);
        $this->db->where("b.status",6);
        $this->db->order_by("b.id","DESC");
        $result = $this->db->get()->result_array();
        return $result;

     }
     public function inprogress_bookinglist($provider_id)
     {
        $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =2) order by b.id DESC");
        $result = $query->result_array();
        return $result;

     }
      public function cancelled_bookinglist($provider_id)
     {
        $this->db->select("b.*,s.service_title,s.service_image,s.shop_id,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.provider_id",$provider_id);
        $this->db->where("b.status",7);
         $this->db->order_by("b.id","DESC");
        $result = $this->db->get()->result_array();
        return $result;

     }

      public function create_availability($inputs)
    {



        $new_details = array();

      $user_id = $this->session->userdata('id');
     
      $this->db->where('provider_id', $user_id);
      $count = $this->db->count_all_results('business_hours');
      if($count == 0){
      	$array = array();

      	if(!empty($inputs['availability'][0]['day'])){
      		$from = $inputs['availability'][0]['from_time'];
      		$to = $inputs['availability'][0]['to_time'];
      		for ($i=1; $i <= 7; $i++) {
      			$array[$i] = array('day'=>$i,'from_time'=>$from,'to_time'=>$to);
      		}

      	}else{
      		if(!empty($inputs['availability'][0])){
      			unset($inputs['availability'][0]);
      		}
      		$array = array_map('array_filter', $inputs['availability']);
			$array = array_filter($array);
      	}
      	if(!empty($array)){
      		$array = array_values($array);
      	}

      $new_details['provider_id'] = $user_id;
      if(empty($inputs['availability'][0]['from_time'])&&empty($inputs['availability'][0]['to_time'])){
        $new_details['all_days'] = 0;
      }else{
        $new_details['all_days']=1;
      }
      $new_details['availability'] = json_encode($array);
      

      return   $this->db->insert('business_hours', $new_details);
      }else{
        return 2; // Already Exists
      }
    }
     public function get_availability($user_id)
     { 
        return $this->db->where('provider_id',$user_id)->get('business_hours')->row_array();

     }

      public function get_subscription()
     {
        $user_type = $this->session->userdata('usertype'); 
		if($user_type == 'provider') {
			return $this->db->where('status',1)->where('type',1)->get('subscription_fee')->result_array();
		} else if($user_type == 'user') {
			return $this->db->where('status',1)->where('type',2)->get('subscription_fee')->result_array();
		} else if($user_type == 'freelancer') {
			return $this->db->where('status',1)->where('type',3)->get('subscription_fee')->result_array();
		} else {
			 return $this->db->where('status',1)->get('subscription_fee')->result_array();
		}

     }

      public function popular_service($service=NULL)
     { 
      if($this->session->userdata('usertype')=="provider" || $this->session->userdata('usertype')=="freelancer"){

        $user=$this->db->where('provider_id',$this->session->userdata('id'))->from('provider_address as p')->join('city as c','c.id=p.city_id')->select('c.name as city_name')->get()->row_array();
      }else{  
          $user=$this->db->where('user_id',$this->session->userdata('id'))->from('user_address as p')->join('city as c','c.id=p.city_id')->select('c.name as city_name')->get()->row_array();
      }
       if(isset($user)&&!empty($user)){
        $city_name=$user['city_name'];  
      }else{
        $city_name='';
      }
       $this->db->select("s.id,s.user_id,s.service_title,s.shop_id,s.service_amount,s.mobile_image,s.about,c.category_name,c.category_image,r.rating,sc.subcategory_name,s.currency_code");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('rating_review r', 'r.service_id = s.id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id');
        $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));

        if(!empty($service['category'])){
          $this->db->where('s.id!=',$service['id']);
          $this->db->where('s.category=',$service['category'])->or_where('s.subcategory=',$service['subcategory']);
          $this->db->where("s.status = 1");
        }
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.service_location',$this->session->userdata('current_location'),'after');
        }
        $this->db->group_by('s.id');
        $this->db->order_by('s.total_views','DESC');
        $this->db->limit(10);
        $query = $this->db->get(); $data = array(); if($query !== FALSE && $query->num_rows() > 0){ $data = $query->result_array(); } return $data;


        
     }
	 	 
	 /* New Function for Popular Service in Service Detail Page */
	 public function popular_service_list($service=NULL)
     { 
      if($this->session->userdata('usertype')=="provider" || $this->session->userdata('usertype')=="freelancer"){

        $user=$this->db->where('provider_id',$this->session->userdata('id'))->from('provider_address as p')->join('city as c','c.id=p.city_id')->select('c.name as city_name')->get()->row_array();
      }else{  
          $user=$this->db->where('user_id',$this->session->userdata('id'))->from('user_address as p')->join('city as c','c.id=p.city_id')->select('c.name as city_name')->get()->row_array();
      }
       if(isset($user)&&!empty($user)){
        $city_name=$user['city_name'];  
      }else{
        $city_name='';
      }
       $this->db->select("s.id,s.user_id,s.shop_id,s.service_title,s.service_amount,s.mobile_image,s.about,c.category_name,c.category_image,s.currency_code, s.service_location");
        $this->db->from('services s');
        $this->db->join('subscription_details as sd','sd.subscriber_id=s.user_id');
		$this->db->join('categories c', 'c.id = s.category');  
        
		$this->db->where("s.status = 1");
		$this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
		
        if(!empty($service['category'])){
          $this->db->where('s.id!=',$service['id']);
          $this->db->where('s.category=',$service['category']);		  
          $this->db->where("s.user_id = ",$service['user_id']);
        }
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.service_location',$this->session->userdata('current_location'),'match');
        }
        $this->db->group_by('s.id');
        $this->db->order_by('s.total_views','DESC');
        $this->db->limit(10);
        $query = $this->db->get(); $data = array(); if($query !== FALSE && $query->num_rows() > 0){ $data = $query->result_array(); } 
		return $data;
       
     }
	  /* New Function for Popular Service in Service Detail Page */
	 
	 

      public function completed_bookinglist_user($user_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$user_id);
        $this->db->where("b.status",3);
        $this->db->order_by("b.id",'DESC');
        $result = $this->db->get()->result_array();
        return $result;

     }

      public function accepted_bookinglist_user($user_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$user_id);
        $this->db->where("b.status",6);
        $this->db->order_by("b.id",'DESC');
        $result = $this->db->get()->result_array();
        return $result;

     }
     public function inprogress_bookinglist_user($user_id)
     {
      
        
        $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =2 OR  `b`.`status` =1) order by b.id DESC");
        $result = $query->result_array();
        return $result;

     }
      public function cancelled_bookinglist_user($user_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$user_id);
        $this->db->where("b.status",7);
        $this->db->order_by("b.id",'DESC');
        $result = $this->db->get()->result_array();
        return $result;

     }

      public function rejected_bookinglist_user($user_id)
     {
        $this->db->select("b.*,s.service_title,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$user_id);
        $this->db->where("b.status",5);
        $this->db->order_by("b.id",'DESC');
        $result = $this->db->get()->result_array();
        return $result;

     }

     public function get_bookinglist_user($user_id)
     {
        $this->db->select("b.*,s.service_title,s.shop_id,s.service_image,s.service_amount,s.rating,s.service_image,c.category_name,sc.subcategory_name,p.name,p.profile_img,p.mobileno,p.country_code");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users p', 'b.user_id = p.id', 'LEFT');
        $this->db->where("b.user_id",$user_id);
        $this->db->order_by("b.id",'DESC');

       
        $result = $this->db->get()->result_array();
        return $result;

     }

    public function update_profile($data)
    {
      $user_id = $this->session->userdata('id');
      $results = $this->db->update('users', $data, array('user_id'=>$user_id));
      return $results;
    }
    public function get_my_subscription()
    {
      $user_id = $this->session->userdata('id');  
	  $usertype = $this->session->userdata('usertype'); 	 
	 if($usertype == 'provider') {
		 $typeval = 1;
	 }else if($usertype == 'user') {
		 $typeval = 2;
	 } else if($usertype == 'freelancer'){
		 $typeval = 3;
	 }
      return $this->db->order_by('id','desc')->get_where('subscription_details',array('subscriber_id'=>$user_id,'type'=>$typeval))->row_array();
    }
    public function get_my_subscription_list()
    {
     $user_id = $this->session->userdata('id');  
	 
	 $usertype = $this->session->userdata('usertype'); 	 
	 if($usertype == 'provider') {
		 $type = 1;
	 }else if($usertype == 'user') {
		 $type = 2;
	 } else if($usertype == 'freelancer'){
		 $type = 3;
	 }

      return $this->db->from('subscription_details_history')->join('subscription_fee','subscription_fee.id=subscription_details_history.subscription_id')->where('subscription_details_history.subscriber_id',$user_id)->where('subscription_details_history.type',$type)->get()->result_array();
    }
    public function update_user($data)
    {
      $user_id = $this->session->userdata('id');
      $results = $this->db->update('users', $data, array('id'=>$user_id));
      return $results;
    }

     public function provider_hours($user_id)
     {
        return $this->db->where('provider_id',$user_id)->get('business_hours')->row_array();

     }

     public function update_availability($input)
    {
      

      $new_details = array();

      $user_id = $this->session->userdata('id');
     
     
      $this->db->where('provider_id', $user_id);
      $count = $this->db->count_all_results('business_hours');
      if($count == 1){
        $array = array();

        if(!empty($input['availability'][0]['day'])){
          $from = $input['availability'][0]['from_time'];
          $to = $input['availability'][0]['to_time'];

          for ($i=1; $i <= 7; $i++) {
            $array[$i] = array('day'=>$i,'from_time'=>$from,'to_time'=>$to);
          }
        
        }else{
          if(!empty($input['availability'][0])){
            unset($input['availability'][0]);
          }
          $array = array_map('array_filter', $input['availability']);
      $array = array_filter($array);
        }
        if(!empty($array)){
          $array = array_values($array);
        }
      $new_details['provider_id'] = $user_id;
      if(empty($input['availability'][0]['from_time'])&&empty($input['availability'][0]['to_time'])){
        $new_details['all_days'] = 0;
      }else{
        $new_details['all_days']=1;
      }
      $new_details['availability'] = json_encode($array);
      
      
      return   $this->db->update('business_hours', $new_details, array('provider_id' => $user_id));
      }else{
        return 2; // Already Exists
      }
    }

     public function check_booking_status($user_data)
     {
        return $this->db->where(array('id'=> $user_data,'status'=>6))->get('book_service')->row_array();

     }
      
     public function rate_review_list($inputs)

     {


        $this->db->select("r.*,u.*");
        $this->db->from('rating_review r');
        $this->db->join('users u', 'r.user_id = u.id', 'LEFT');
        $this->db->where("r.service_id",$inputs);
        $result = $this->db->get()->result_array();
        return $result;

         
     }  

      public function rate_review_for_service($inputs)

   {

        $get_provider = $this->db->where('id',$inputs['service_id'])->get('services')->row_array();

        $new_details = array();

        $user_id = $inputs['user_id'];

        $new_details['user_id'] = $user_id;

        $new_details['service_id'] = $inputs['service_id'];

        $new_details['booking_id'] = $inputs['booking_id'];

        $new_details['provider_id'] = $get_provider['user_id'];

        $new_details['rating'] = $inputs['rating'];

        $new_details['review'] = $inputs['review'];

        $new_details['type'] = $inputs['type'];

        $new_details['created'] =  date('Y-m-d H:i:s');
         
        $this->db->where('status',1);

        $this->db->where('booking_id',$inputs['booking_id']);

        $this->db->where('user_id', $user_id);
        
        $count = $this->db->count_all_results('rating_review');

        if($count == 0)

        {

            return   $this->db->insert('rating_review', $new_details);
        }

        else

        {

          return $result = 2;

        }

    }
	
	public function get_shops()
	{
		$user_type = $this->session->userdata('usertype');
		$user_id   = $this->session->userdata('id');
        $this->db->select("s.*");
	    $this->db->from('shops s');	
		$this->db->where('status',1);
		if($user_type == 'provider' || $user_type == 'freelancer'){
			$this->db->where('s.provider_id', $user_id);
		}
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.shop_location',$this->session->userdata('current_location'));
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
	public function get_featured_shops()
	{
		$user_type = $this->session->userdata('usertype');
		$user_id   = $this->session->userdata('id');
		$subid = $this->db->select('id')->where('subscription_type',3)->where('status',1)->get('subscription_fee')->row()->id;
		
        $this->db->select("s.*");
	    $this->db->from('shops s');	
		$this->db->where('status',1);
		$this->db->join('subscription_details as sd','sd.subscriber_id=s.provider_id','LEFT');
        $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));		
        $this->db->where('sd.subscription_id =',$subid);
		  
		if($user_type == 'provider' || $user_type == 'freelancer'){
			$this->db->where('s.provider_id', $user_id);
		}
        if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.shop_location',$this->session->userdata('current_location'));
        }        
        $this->db->order_by('s.id','DESC');
        $this->db->limit(10);
        $query = $this->db->get(); $data = array(); 
		if($query !== FALSE && $query->num_rows() > 0){ 
			$data = $query->result_array(); 
		} 
		if(count($data)==0){
			$this->db->select("s.*");
			$this->db->from('shops s');	
			$this->db->where('status',1);
			$this->db->join('subscription_details as sd','sd.subscriber_id=s.provider_id','LEFT');
			$this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
			if(!empty($this->session->userdata('current_location'))){
				$this->db->like("s.shop_location",$this->session->userdata('current_location'));
			}		  
			if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
				$user_id = $this->session->userdata('id');
				$this->db->where("s.provider_id", $user_id);
			}       
			$this->db->order_by('s.id','ASC');
			$this->db->limit(3); 
			$result = $this->db->get()->result_array();
			return $result;		  
		} else {
			return $data;
		}
		
		
    }
	public function get_nearest_shops()
	{
		$user_type = $this->session->userdata('usertype');
		$user_id   = $this->session->userdata('id');
		
		$radius = (settingValue('radius'))?settingValue('radius'):'0';

        $this->db->select("s.*");
		/* Distance Query */
		$longitude = $this->session->userdata('longitude')?$this->session->userdata('longitude'):$this->session->userdata('user_longitude');
		$latitude = $this->session->userdata('latitude')?$this->session->userdata('latitude'):$this->session->userdata('user_latitude');

		if($latitude != '' && $longitude != ''){
			$this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - s.shop_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(s.shop_latitude * pi()/180) * POWER(SIN(($longitude  - s.shop_longitude) * pi()/180 / 2), 2) )) AS distance");
			$this->db->having('distance <=',$radius);
		}
		/* Distance Query */  
	    $this->db->from('shops s');	
		$this->db->where('status',1);
		$this->db->join('subscription_details as sd','sd.subscriber_id=s.provider_id','LEFT');
        $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));		
       
		if($user_type == 'provider' || $user_type == 'freelancer'){
			$this->db->where('s.provider_id', $user_id);
		}
	        
		if(!empty($this->session->userdata('current_location'))){
          $this->db->like('s.shop_location',$this->session->userdata('current_location'));		 
        } 
		if($latitude != '' && $longitude != ''){
	        $this->db->order_by('distance','ASC');
	    }
        $this->db->limit(10);
        $query = $this->db->get(); $data = array(); 
		if($query !== FALSE && $query->num_rows() > 0){ 
			$data = $query->result_array(); 
		} 				
		if(count($data)==0){
          $this->db->select("s.*, (RAND()*(10-5)+5) as distance");
          $this->db->from('shops s');
          $this->db->join('categories c', 'c.id = s.category', 'LEFT');
          $this->db->where("s.status = 1");
          $this->db->join('subscription_details as sd','sd.subscriber_id=s.provider_id','LEFT');
          $this->db->where('sd.expiry_date_time>=',date('Y-m-d'));
          if(!empty($this->session->userdata('current_location'))){
            $this->db->like("s.shop_location",$this->session->userdata('current_location'));
          }		  
		  if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
			$user_id = $this->session->userdata('id');
			$this->db->where("s.provider_id", $user_id);
		  }       
          $this->db->order_by('id','DESC');
          $this->db->limit(3);
          $result = $this->db->get()->result_array();
          return $result;		  
		} else {
			return $data;
		}
    }

    public function user_city_list($user_id)
    {
    	$this->db->select("city.id as city_id, city.name as city_name");
    	$this->db->join('user_address','city.state_id=user_address.state_id','INNER');
    	$this->db->where(['user_address.user_id'=>$user_id]);
    	$this->db->order_by('city.name','ASC');
    	$query = $this->db->get('city')->result_array();
    	return $query;
    }

    public function get_offered_services()
    {
    	$cur_date = date("Y-m-d");
    	$current_time = date("H:i");

    	$this->db->select("service_offers.service_id, service_offers.offer_percentage, services.service_title,services.shop_id, services.currency_code, services.service_amount, services.service_location, providers.name as provider_name, providers.profile_img, categories.category_name, services_image.service_image, services.shop_id");
    	$this->db->join('services','service_offers.service_id=services.id','LEFT');
    	$this->db->join('providers','service_offers.provider_id=providers.id','LEFT');
		$this->db->join('categories','services.category=categories.id','LEFT');
		$this->db->join('services_image','service_offers.service_id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
		$this->db->where("'$cur_date' BETWEEN service_offers.start_date AND service_offers.end_date");
		$this->db->where("'$current_time' BETWEEN service_offers.start_time AND service_offers.end_time");
		$query = $this->db->get('service_offers')->result_array();
		
		$i=0;
		foreach ($query as $val) 
		{
			$this->db->select('AVG(rating) as avg_rating');
            $this->db->where(array('service_id' => $val['service_id'], 'status' => 1));
            $query[$i]['rating'] = $this->db->get('rating_review')->row_array()['avg_rating'];
            $i++;
		}
		if(count($query)==0){
          $this->db->select("service_offers.service_id, service_offers.offer_percentage, services.service_title, services.currency_code, services.service_amount, services.service_location, providers.name as provider_name, providers.profile_img, categories.category_name, services_image.service_image");
    	  $this->db->join('services','service_offers.service_id=services.id','LEFT');
    	  $this->db->join('providers','service_offers.provider_id=providers.id','LEFT');
		  $this->db->join('categories','services.category=categories.id','LEFT');
		  $this->db->join('services_image','service_offers.service_id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
		  $this->db->limit(3);
		  $result = $this->db->get('service_offers')->result_array();
          return $result;		  
		} else {
			return $query;
		}
    	
    }
    public function get_all_subcategories()
    {
    	$this->db->select("subcategories.id, subcategories.subcategory_name, subcategories.subcategory_image, categories.category_name");
    	$this->db->join('categories','subcategories.category=categories.id','LEFT');
    	$this->db->where(['subcategories.status'=>1]);
    	$query = $this->db->get('subcategories')->result_array();
    	return $query;
    }

    public function get_all_categories()
    {
    	$this->db->where('status', 1);
    	$this->db->where('is_featured', 1);
    	$query = $this->db->get('categories')->result_array();
    	return $query;
    }

    public function provider_bookings($where_data, $whrIn)
    {
    	//get provider id with bookings
    	$this->db->select("services.service_title,services.shop_id, book_service.currency_code, book_service.total_amount, users.name as user_name, users.mobileno as user_mobile, users.country_code as user_country_code, book_service.service_date, book_service.cod, book_service.status, book_service.admin_reject_comment, book_service.reason, rating_review.rating, rating_review.review");
    	$this->db->join('services','book_service.service_id=services.id','LEFT');
    	$this->db->join('users','book_service.user_id=users.id','LEFT');
    	$this->db->join('rating_review','book_service.id=rating_review.booking_id','LEFT');
    	$this->db->join('shops','book_service.shop_id=shops.id','LEFT');
    	$this->db->where($where_data);
    	$this->db->where_in($whrIn['column_name'], $whrIn['value']);
    	$this->db->order_by("book_service.provider_id", "asc");
    	$query = $this->db->get('book_service')->result_array();
    	return $query;
    }

    public function provider_orders($where_data, $whrIn)
    {
    	$this->db->select('products.product_name, product_cart.qty, product_units.unit_name, product_cart.product_currency, product_cart.product_total,  users.name as user_name, users.mobileno as user_mobile, users.country_code as user_country_code, product_order.created_on, product_order.payment_type, product_cart.delivery_status');
    	$this->db->join('product_order','product_cart.order_id=product_order.id','left');
			$this->db->join('products','product_cart.product_id=products.id','left');
			$this->db->join('shops','product_cart.shop_id=shops.id','left');
			$this->db->join('product_units','products.unit=product_units.id','left');
			$this->db->join('users','product_cart.user_id=users.id','left');
			$this->db->where($where_data);
    	$this->db->where_in($whrIn['column_name'], $whrIn['value']);
    	$query = $this->db->get('product_cart')->result_array();
    	return $query;
    }

    public function fetch_data($service_title) {
    	$userdata = $this->db->where('status',1)->like('service_title', $service_title)->get('services')->result_array();
      	$data = json_encode($userdata);
      	return $data;
    }

    public function get_pages_details($slug) {
        $lang_val = ($this->session->userdata('lang'))?$this->session->userdata('lang'):'en';
        $this->db->where('language_value', $lang_val);
        $lang_name = $this->db->get('language')->row_array();
        $this->db->from('pages_list');
        $this->db->where('slug', $slug);
        $this->db->where('lang_id', $lang_name['id']);
        $pages_details = $this->db->get()->row_array();
        return $pages_details;
    }
    public function get_all_blogs($lang_id = null,$limit = null,$url = null){
        $this->db->select('blog_categories.name as cat_name,language.language,blog_posts.*,administrators.full_name,administrators.profile_img');
        $this->db->from('blog_posts');
        $this->db->join('blog_categories','blog_categories.id=blog_posts.category_id');
        $this->db->join('administrators','administrators.user_id=blog_posts.createdBy');
        $this->db->join('language','language.id=blog_categories.lang_id');
        
          $this->db->where('blog_posts.status',1);
        
        if($lang_id){
          $this->db->where('blog_posts.lang_id',$lang_id);
        }
        if($url){
          $this->db->where('blog_posts.url',$url);
        }
        $this->db->order_by('blog_posts.createdAt','desc');
        if($limit){
          $this->db->limit($limit);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0){
          return $query->result_array();
        }
        return array(); 

    }

    public function getComments($blog_id) {
        $comments = $this->db->order_by('id','DESC')->get_where('blog_comments', array('post_id'=>$blog_id, 'status'=>1))->result_array();

        return $comments;
    }
}
?>
