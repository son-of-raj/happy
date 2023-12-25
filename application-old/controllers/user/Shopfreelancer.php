<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shopfreelancer extends CI_Controller {

	public $data;

   public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme']     = 'user';
        $this->data['module']    = 'shopfreelancer';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model','home');
		$this->load->model('shopfreelancer_model','shop');

         $this->user_latitude=(!empty($this->session->userdata('user_latitude')))?$this->session->userdata('user_latitude'):'';
         $this->user_longitude=(!empty($this->session->userdata('user_longitude')))?$this->session->userdata('user_longitude'):'';

         $this->currency= settings('currency');

         $this->load->library('ajax_pagination'); 
         $this->perPage = 12; 
         
         $default_language_select = default_language();

        if ($this->session->userdata('user_select_language') == '') {
            $this->data['user_selected'] = $default_language_select['language_value'];
        } else {
            $this->data['user_selected'] = $this->session->userdata('user_select_language');
        }

        $this->data['active_language'] = $active_lang = active_language();

        $lg = custom_language($this->data['user_selected']);

        $this->data['default_language'] = $lg['default_lang'];

        $this->data['user_language'] = $lg['user_lang'];

        $this->user_selected = (!empty($this->data['user_selected'])) ? $this->data['user_selected'] : 'en';

        $this->default_language = (!empty($this->data['default_language'])) ? $this->data['default_language'] : '';

        $this->user_language = (!empty($this->data['user_language'])) ? $this->data['user_language'] : '';
        
        
    }

	
	public function index()
	{
		if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') != 'freelancer') {
            redirect(base_url());
        }
		$conditions['returnType'] = 'count'; 
        $totalRec = $this->shop->getActiveShops($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['link_func']      = 'getData';
        $config['loading']='<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/shopfreelancer/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        );

         $this->data['page'] = 'index';
	     $this->data['shops']=$this->shop->getActiveShops($conditions);
	     $this->load->vars($this->data);
		 $this->load->view($this->data['theme'].'/template');
	}
	
	function ajaxPaginationData(){ 
        // Define offset 
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        } 
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->shop->getActiveShops($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/shopfreelancer/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['shops'] = $this->shop->getActiveShops($conditions); 
        
        // Load the data list view 
        $this->load->view('user/shop/ajax_shop', $this->data, false); 
    }
	public function inactive_shop()
    { 
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->shop->getInactiveShops($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/shopfreelancer/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['shops'] = $this->shop->getInactiveShops($conditions); 
        
        $this->data['page'] = 'my_shop_inactive';
        // Load the list page view 
       $this->load->vars($this->data);
     $this->load->view($this->data['theme'].'/template');
    } 
      function inactiveajaxPaginationData(){ 
        // Define offset 
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        } 
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->shop->getInactiveShops($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/shopfreelancer/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['shops'] = $this->shop->getInactiveShops($conditions); 
        
        // Load the data list view 
        $this->load->view('user/shop/ajax_inactive_shop', $this->data, false); 
    }
	public function add_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') != 'freelancer') {
            redirect(base_url());
        }
		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		if (!empty($result)) {
			foreach ($result as $data) {
				if ($data['key'] == 'currency_option') {
					$currency_option = $data['value'];
				}
				 if ($data['key'] == 'map_key') {
					$map_key = $data['map_key'];
				}
			}
		}
		
        if ($this->input->post('form_submit')) { 
            $inputs = array();

            removeTag($this->input->post());
           
            $config["upload_path"] = './uploads/shops/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();
			if ($_FILES["images"]["name"] != '') {
				for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
					$_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
					$_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
					$_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
					$_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
					$_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
					if ($this->upload->do_upload('file')) {
						$data = $this->upload->data();
						$image_url = 'uploads/shops/' . $data["file_name"];
						$upload_url = 'uploads/shops/';
						$service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
						$thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
					}
				}
			}
            
			
			$user_id = $this->session->userdata('id');
           
            $inputs['provider_id'] = $this->session->userdata('id');
			$inputs['shop_name'] = $this->input->post('shop_title');	
			$inputs['description'] = $this->input->post('about');	
			$inputs['country_code'] = $this->input->post('countryCode');
			$inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');			
			$inputs['shop_location'] = $this->input->post('shop_location');
			$inputs['shop_latitude'] = $this->input->post('shop_latitude');
			$inputs['shop_longitude'] = $this->input->post('shop_longitude');
			
			$inputs['address'] = $this->input->post('address');			
            $inputs['country'] = $this->input->post('country_id');
            $inputs['state'] = $this->input->post('state_id');
            $inputs['city'] = $this->input->post('city_id');
            $inputs['postal_code'] = $this->input->post('pincode');   
				
			$inputs['status'] = 1;
			$inputs['created_by'] = $this->session->userdata('id');
			$inputs['created_at'] = date('Y-m-d H:i:s');
			$inputs['updated_at'] = date('Y-m-d H:i:s');
			
			$inputs['category']  = $this->input->post('category');
			$inputs['subcategory']  = $this->input->post('subcategory');
			
			$inputs['shop_code'] = 'SHOP'.$this->api->getToken(5, $user_id);
                        
			
			$array = array();
			$inputs['availability'] = $this->input->post('availability');
			
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
			if(empty($inputs['availability'][0]['from_time'])&&empty($inputs['availability'][0]['to_time'])){
				$inputs['all_days'] = 0;
			}else{
				$inputs['all_days']=1;
			}				
			$inputs['availability'] = json_encode($array);	
			
			
			$result = $this->shop->create_shop($inputs); 
			
			
			if (count($service_image) > 0) { 
				$temp = count($service_image);				
				for ($i = 0; $i < $temp; $i++) { 
					$image = array(
						'shop_id' => $result,
						'shop_image' => $service_image[$i],
						'shop_details_image' => $service_details_image[$i],
						'thumb_image' => $thumb_image[$i],
						'mobile_image' => $mobile_image[$i]
					);				
					$shoppimage = $this->shop->insert_shopimage($image);
				
				}
			}
						
			if (!empty($result) && $result > 0) {	
			
				$this->session->set_flashdata('success_message', 'Shop created successfully');
				redirect(base_url() . "freelances/shop");
			} else {
                $this->session->set_flashdata('error_message', 'Shop created failed');
                redirect(base_url() . "freelances/shop");
            }			
        }
        
        $this->data['country_list']=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
		$this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
		$this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
		$this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
		
		$uid = $this->session->userdata('id');
		
		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'add_shop';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
	public function readStaffs($shopid=''){
		$uid = $this->session->userdata('id');
		$condt = '';
		if($shopid != '') {
			$condt = " and `shop_id` != ".$shopid;
		}
	
		/* Read Staff from Shop & Branch Table */
		
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
	public function edit_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') != 'freelancer') {
            redirect(base_url());
        }

        $shop_id = $this->uri->segment('3');
		$provider_id = $this->session->userdata('id');
        $this->data['page'] = 'edit_shop';
        $this->data['model'] = 'shop';
		$this->data['shop_id'] = $shop_id;
        $this->data['shop_details'] = $this->shop->get_single_shop($shop_id,$provider_id);  

		$this->data['country_list']=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
		$this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
		$this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
		$this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();	

		$uid = $this->session->userdata('id');
	
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
	public function update_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		
        if ($this->input->post('form_submit')) { 
            $inputs = array();

            removeTag($this->input->post());
           
            $config["upload_path"] = './uploads/shops/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();
			if ($_FILES["images"]["name"] != '') {
				for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
					$profile_count = $this->db->where('shop_id', $this->input->post('shop_id'))->from('shops_images')->count_all_results();
					if ($profile_count < 10) {
					$_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
					$_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
					$_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
					$_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
					$_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
					if ($this->upload->do_upload('file')) {
						$data = $this->upload->data();
						$image_url = 'uploads/shops/' . $data["file_name"];
						$upload_url = 'uploads/shops/';
						$service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
						$thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
					}
					}
				}
			}
            
			
			$user_id = $this->session->userdata('id');
            $shop_id = $this->input->post('shop_id');
			
            $inputs['provider_id'] = $user_id;
			$inputs['shop_name'] = $this->input->post('shop_title');	
			$inputs['description'] = $this->input->post('about');
			$inputs['country_code'] = $this->input->post('countryCode');
			$inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');			
			$inputs['shop_location'] = $this->input->post('shop_location');
			$inputs['shop_latitude'] = $this->input->post('shop_latitude');
			$inputs['shop_longitude'] = $this->input->post('shop_longitude');	

			$inputs['address'] = $this->input->post('address');			
            $inputs['country'] = $this->input->post('country_id');
            $inputs['state'] = $this->input->post('state_id');
            $inputs['city'] = $this->input->post('city_id');
            $inputs['postal_code'] = $this->input->post('pincode');    

			$inputs['updated_at'] = date('Y-m-d H:i:s');
			
			$inputs['category']  = $this->input->post('category');
			$inputs['subcategory']  = $this->input->post('subcategory');
            			
			$array = array();
			$inputs['availability'] = $this->input->post('availability');
					
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
			if(empty($inputs['availability'][0]['from_time'])&&empty($inputs['availability'][0]['to_time'])){
				$inputs['all_days'] = 0;
			}else{
				$inputs['all_days']=1;
			}				
			$inputs['availability'] = json_encode($array);	
			
			
			$result = $this->shop->update_shop($inputs,$shop_id,$user_id); 
			
			if (count($service_image) > 0) { 
				$temp = count($service_image);				
				for ($i = 0; $i < $temp; $i++) { 
					$image = array(
						'shop_id' => $shop_id,
						'shop_image' => $service_image[$i],
						'shop_details_image' => $service_details_image[$i],
						'thumb_image' => $thumb_image[$i],
						'mobile_image' => $mobile_image[$i]
					);				
					$shoppimage = $this->shop->insert_shopimage($image);
				}
			}
			
			if (!empty($result) && $result > 0) {	
			
				$this->session->set_flashdata('success_message', 'Shop Updated successfully');
				redirect(base_url() . "freelances/shop");
			} else {
                $this->session->set_flashdata('error_message', 'Shop Updated failed');
                redirect(base_url() . "freelances/shop");
            }			
        }
    }
	
	public function shop_preview() {

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            extract($_GET);
           
            $s_id     = $_GET['sid']; 
			$user_id  = $this->session->userdata('id');
			$user_type = $this->session->userdata('usertype');
            $this->data['module'] = 'shopfreelancer';
            $this->data['page'] = 'shop_preview';
			$this->data['model'] = 'service';
			
            $this->data['shop'] = $shop = $this->shop->get_shopinfo($s_id);
            $this->load->model('shop_model', 'shop');
            $this->data['shop_image'] = $this->shop->shop_image($shop['id']);
			
			$this->data['popular_shops'] = $this->shop->popular_shops();
         
            if (!empty($shop['id'])) {
                $this->views($this->data['shop']);
            }
            $this->load->vars($this->data);
            $this->load->view($this->data['theme'] . '/template');
        } else {
            redirect(base_url());
        }
    }
	
	private function views($inputs) {

		$this->db->set('total_views', 'total_views+1', FALSE);
		$this->db->where('id', $inputs['id']);
		$this->db->update('shops');        
    }   
	
	public function delete_inactive_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '2';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('shop_id' => $s_id);
        $result = $this->shop->update_shop_status($inputs, $WHERE,$IMGWHERE);
        if ($result) {
            $message = 'Shop InActivate successfully';
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = 'Something went wrong.Please try again later.';
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }

    public function delete_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '0';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('shop_id' => $s_id);
        $result = $this->shop->update_shop_status($inputs, $WHERE,$IMGWHERE);
        if ($result) {
            $message = 'Shop deleted successfully';
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = 'Something went wrong.Please try again later.';
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }

    public function delete_active_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '1';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('shop_id' => $s_id);
        $result = $this->shop->update_shop_status($inputs, $WHERE,$IMGWHERE);
        if ($result) {
            $message = 'Shop Activate successfully';
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = 'Something went wrong.Please try again later.';
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        }
    }
	
	
	public function shop_check_emailid()
	{
	  $inputs = $_POST['userEmail'];		 
	  $this->db->like('email', $inputs, 'match');
	   if(!empty($_POST['sid'])) {
		  $this->db->where('id !=', $_POST['sid']);
	  }
      $count = $this->db->count_all_results('shops');
	  echo json_encode(['data' => $count ]);
	}
	public function shop_check_mobile()
	{
	  $inputs   = $_POST['userMobile'];	
	  $ctrycode = $_POST['mobileCode']; 	
	  
	  
	  $this->db->where(array('country_code'=>$ctrycode))->like('contact_no',$inputs,'match','after');
	  if(!empty($_POST['sid'])) {
		  $this->db->where('id !=', $_POST['sid']);
	  }
      $count = $this->db->count_all_results('shops');
	  echo json_encode(['data' => $count]);
	}
	public function get_service_staff($shop_id=''){
		$uid  = $this->session->userdata('id');
		$shop_id = $_POST['shop_id']; 
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs($shop_id);			
		if(!empty($assign)) {
			$data['stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->where_not_in('id', $assign)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		} else {
			$data['stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		}
		
		$data['serviceoffer']=$this->db->select("id,service_offered")->where('status',1)->order_by('service_offered',"ASC")->get('shop_service_offered')->result_array();
		
        $json[] = $data;
		echo json_encode($data);
	}
	
    public function image_resize($width = 0, $height = 0, $image_url, $filename, $upload_url) {

        $source_path = base_url() . $image_url;
        list($source_width, $source_height, $source_type) = getimagesize($source_path);
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $height;
            $temp_width = (int) ($height * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $width;
            $temp_height = (int) ($width / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
                $temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
                $desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $width, $height
        );

        /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */

        $image_url = $upload_url . $filename;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }
	
	public function staff_availability() {

        $booking_date = $this->input->post('date');
        $provider_id  = $this->input->post('provider_id');        
		$staff_id     = $this->input->post('staff_id');
		$service_id   = $this->input->post('service_id');
        
		$timestamp = strtotime($booking_date);
        $day = date('D', $timestamp);
        $staff_details = $this->shop->provider_hours($staff_id,$provider_id);
        $availability_details = json_decode($staff_details['availability'], true);

        $alldays = false;
        foreach ($availability_details as $details) {

            if (isset($details['day']) && $details['day'] == 0) {

                if (isset($details['from_time']) && !empty($details['from_time'])) {

                    if (isset($details['to_time']) && !empty($details['to_time'])) {
                        $from_time = $details['from_time'];
                        $to_time = $details['to_time'];
                        $alldays = true;
                        break;
                    }
                }
            }
        }

        if ($alldays == false) {


            if ($day == 'Mon') {
                $weekday = '1';
            } elseif ($day == 'Tue') {
                $weekday = '2';
            } elseif ($day == 'Wed') {
                $weekday = '3';
            } elseif ($day == 'Thu') {
                $weekday = '4';
            } elseif ($day == 'Fri') {
                $weekday = '5';
            } elseif ($day == 'Sat') {
                $weekday = '6';
            } elseif ($day == 'Sun') {
                $weekday = '7';
            } elseif ($day == '0') {
                $weekday = '0';
            }


            foreach ($availability_details as $availability) {

                if ($weekday == $availability['day'] && $availability['day'] != 0) {

                    $availability_day = $availability['day'];
                    $from_time = $availability['from_time'];
                    $to_time = $availability['to_time'];
                }
            }
        }

        if (!empty($from_time)) {
            $temp_start_time = $from_time;
            $temp_end_time = $to_time;
        } else {
            $temp_start_time = '';
            $temp_end_time = '';
        }


        $start_time_array = '';
        $end_time_array = '';


        $timestamp_start = strtotime($temp_start_time);
        $timestamp_end = strtotime($temp_end_time);

        $timing_array = array();

        $counter = 1;

        $from_time_railwayhrs = date('G:i:s', ($timestamp_start));
        $to_time_railwayhrs = date('G:i:s', ($timestamp_end));

        $timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
        $timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);


        $i = 1;
        while ($timestamp_start_railwayhrs < $timestamp_end_railwayhrs) {

            $temp_start_time_ampm = date('G:i:s', ($timestamp_start_railwayhrs));
            $temp_end_time_ampm = date('G:i:s', (($timestamp_start_railwayhrs) + 60 * 60 * 1));

            $timestamp_start_railwayhrs = strtotime($temp_end_time_ampm);

            $timing_array[] = array('id' => $i, 'start_time' => $temp_start_time_ampm, 'end_time' => $temp_end_time_ampm);

            if ($counter > 24) {
                break;
            }

            $counter += 1;
            $i++;
        }


        // Booking availability


        $service_date = $booking_date;



        $booking_count = $this->shop->get_bookings($service_date, $service_id);



        $new_timingarray = array();

        if (is_array($booking_count) && empty($booking_count)) {
            $new_timingarray = $timing_array;
        } elseif (is_array($booking_count) && $booking_count != '') {
            foreach ($timing_array as $timing) {
                $match_found = false;

                $explode_st_time = explode(':', $timing['start_time']);
                $explode_value = $explode_st_time[0];

                $explode_endtime = explode(':', $timing['end_time']);
                $explode_endval = $explode_endtime[0];


                if (strlen($explode_value) == 1) {
                    $timing['start_time'] = "0" . $explode_st_time[0] . ":" . $explode_st_time[1] . ":" . $explode_st_time[2];
                }

                if (strlen($explode_endval) == 1) {
                    $timing['end_time'] = "0" . $explode_endtime[0] . ":" . $explode_endtime[1] . ":" . $explode_endtime[2];
                }

                foreach ($booking_count as $bookings) {


                    if ($timing['start_time'] == $bookings['from_time'] && $timing['end_time'] == $bookings['to_time']) {


                        $match_found = true;
                        break;
                    }
                }

                if ($match_found == false) {
                    $new_timingarray[] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
                }
            }
        }

        $new_timingarray = array_filter($new_timingarray);

        if (!empty($new_timingarray)) {
            $i = 1;
            foreach ($new_timingarray as $booked_time) {

                $re = strtotime($booked_time['start_time']);
                $re1 = strtotime($booked_time['end_time']);
                date_default_timezone_set('Asia/Kolkata');
                if (date('Y-m-d', strtotime($_POST['date'])) == date('Y-m-d')) {
                    $current_time = strtotime(date('H:i:s'));
                    if (strtotime($booked_time['start_time']) > $current_time) {

                        $st_time = date('h:i A', strtotime($booked_time['start_time']));
                        $end_time = date('h:i A', strtotime($booked_time['end_time']));
                    } else {
                        $st_time = '';
                        $end_time = '';
                    }
                } else {

                    $st_time = date('h:i A', strtotime($booked_time['start_time']));
                    $end_time = date('h:i A', strtotime($booked_time['end_time']));
                }


                if (!empty($st_time)) {
                    $time['start_time'] = $st_time;
                    $time['end_time'] = $end_time;
                    $service_availability[] = $time;
                    $i++;
                }
            }
        } else {
            $service_availability = '';
        }
        if (!isset($service_availability)) {
            $service_availability = '';
        }

        echo json_encode($service_availability);
    }
	
	public function book_staffservice() {

        $user_currency = get_user_currency();
        $user_currency_code = $user_currency['user_currency_code'];


        removeTag($this->input->post());
		$final_amount = $this->input->post('final_amount');
        $time = $this->input->post('booking_time');
        $booking_time = explode('-', $time);
        $start_time = strtotime($booking_time[0]);
        $end_time = strtotime($booking_time[1]);
        $from_time = date('G:i:s', ($start_time));
        $to_time = date('G:i:s', ($end_time));

        $inputs = array();
        $service_id = $this->input->post('service_id'); // Package ID        
        $inputs['service_id']    = $service_id;
        $inputs['provider_id']   = $this->input->post('provider_id');
        $inputs['user_id']       = $this->session->userdata('id');
        $inputs['provider_id']   = $this->input->post('provider_id');
		$inputs['shop_id']       = $this->input->post('shop_id');
		$inputs['staff_id']      = $this->input->post('staff_id');
        
		$inputs['service_date'] = date('Y-m-d', strtotime($this->input->post('booking_date')));        
        $inputs['from_time'] = $from_time;
        $inputs['to_time'] = $to_time;

        $inputs['tokenid'] = 'old type'; 	
        $inputs['currency_code'] = $user_currency_code;		
		$inputs['amount'] = get_gigs_currency($final_amount, $user_currency_code, $user_currency_code);
		
		$inputs['created_by']  = $this->session->userdata('id');
		$inputs['created_at']  = (date('Y-m-d H:i:s'));
		$inputs['updated_on']  = (date('Y-m-d H:i:s'));		
		
		//check server side validation while booking
		$timestamp = strtotime($inputs['service_date']);
		$day = date('D', $timestamp);


		$staff_details = $this->shop->provider_hours($inputs['staff_id'],$inputs['provider_id']);
		$availability_details = json_decode($staff_details['availability'], true);

		$alldays = false;
		foreach ($availability_details as $details) {
			if (isset($details['day']) && $details['day'] == 0) {
				if (isset($details['from_time']) && !empty($details['from_time'])) {
					if (isset($details['to_time']) && !empty($details['to_time'])) {
						$from_time1 = $details['from_time'];
						$to_time1 = $details['to_time'];
						$alldays = true;
						break;
					}
				}
			}
		}

		if ($alldays == false) {
			if ($day == 'Mon') {
				$weekday = '1';
			} elseif ($day == 'Tue') {
				$weekday = '2';
			} elseif ($day == 'Wed') {
				$weekday = '3';
			} elseif ($day == 'Thu') {
				$weekday = '4';
			} elseif ($day == 'Fri') {
				$weekday = '5';
			} elseif ($day == 'Sat') {
				$weekday = '6';
			} elseif ($day == 'Sun') {
				$weekday = '7';
			} elseif ($day == '0') {
				$weekday = '0';
			}

			foreach ($availability_details as $availability) {
				if ($weekday == $availability['day'] && $availability['day'] != 0) {
					$availability_day = $availability['day'];
					$from_time1 = $availability['from_time'];
					$to_time1 = $availability['to_time'];
				}
			}
		}

		if (!empty($from_time1)) {
			$temp_start_time = $from_time1;
			$temp_end_time = $to_time1;
		} else {                       
			$message = 'Booking not available';
			$this->session->set_flashdata('error_message', $message);
			echo json_encode(['success' => false, 'msg' => $message, 'status' => 3]);exit;
		}



		$start_time_array = '';
		$end_time_array = '';


		$timestamp_start = strtotime($temp_start_time);
		$timestamp_end = strtotime($temp_end_time);

		$timing_array = array();

		$counter = 1;

		$from_time_railwayhrs = date('H:i:s', ($timestamp_start));
		$to_time_railwayhrs = date('H:i:s', ($timestamp_end));

		$timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
		$timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);


		$i = 1;
		while ($timestamp_start_railwayhrs < $timestamp_end_railwayhrs) {

			$temp_start_time_ampm = date('H:i:s', ($timestamp_start_railwayhrs));
			$temp_end_time_ampm = date('H:i:s', (($timestamp_start_railwayhrs) + 60 * 60 * 1));

			$timestamp_start_railwayhrs = strtotime($temp_end_time_ampm);

			$timing_array[] = array('id' => $i, 'start_time' => $temp_start_time_ampm, 'end_time' => $temp_end_time_ampm);

			if ($counter > 24) {
				break;
			}

			$counter += 1;
			$i++;
		}
		


		// Booking availability


		$booking_from_time = $booking_time[0];
		$booking_end_time = $booking_time[1];

		$timestamp_from = strtotime($booking_from_time);
		$timestamp_to = strtotime($booking_end_time);

		 $from_time_railwayhrs = date('H:i:s', ($timestamp_from));
		 $to_time_railwayhrs = date('H:i:s', ($timestamp_to));

		$service_date = $inputs['service_date'];
		$service_id = $inputs['service_id'];


		$booking_count = $this->shop->get_bookings($service_date, $service_id);
		


		$new_timingarray = array();

		if (is_array($booking_count) && empty($booking_count)) {
			$new_timingarray = $timing_array;
		} elseif (is_array($booking_count) && $booking_count != '') {
			foreach ($timing_array as $timing) {
				$match_found = false;

				$explode_st_time = explode(':', $timing['start_time']);
				$explode_value = $explode_st_time[0];

				$explode_endtime = explode(':', $timing['end_time']);
				$explode_endval = $explode_endtime[0];


				if (strlen($explode_value) == 1) {
					$timing['start_time'] = "0" . $explode_st_time[0] . ":" . $explode_st_time[1] . ":" . $explode_st_time[2];
				}

				if (strlen($explode_endval) == 1) {
					$timing['end_time'] = "0" . $explode_endtime[0] . ":" . $explode_endtime[1] . ":" . $explode_endtime[2];
				}

				foreach ($booking_count as $bookings) {


					if ($timing['start_time'] == $bookings['from_time'] && $timing['end_time'] == $bookings['to_time']) {

						$match_found = true;
						break;
					}
				}

				if ($match_found == false) {
					$new_timingarray[] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
				}
			}
		}

		$new_timingarray = array_filter($new_timingarray);


		$booking = false;
		foreach ($new_timingarray as $booked_time) {

			if ($booked_time['start_time'] == $from_time_railwayhrs && $booked_time['end_time'] == $to_time_railwayhrs) {
				$booking = true;
			}
		}

		if ($booking == false) {

			$message = 'Booking not available';
			$this->session->set_flashdata('error_message', $message);
			echo json_encode(['success' => false, 'msg' => $message, 'status' => 3]);
			exit;
		}
		
		$user_booking_count = $this->shop->user_get_bookings($service_date, $from_time_railwayhrs, $to_time_railwayhrs);
		if(count($user_booking_count) > 0){
			$message = 'You have another booking at this time slot';
			$this->session->set_flashdata('error_message', $message);
			echo json_encode(['success' => false, 'msg' => $message, 'status' => 3]);
			exit;
		}

		//check server side         
		$result = $this->shop->booking_success($inputs);
		if ($result != '') {
			$message = 'You have booked successfully';
			$this->session->set_flashdata('success_message', $message);
			echo json_encode(['success' => true, 'msg' => $message, 'status' => 1]);
        } else {
            $message = 'Sorry, something went wrong';
            $this->session->set_flashdata('error_message', $message);
			 echo json_encode(['success' => true, 'msg' => $message, 'status' => 3]);
        }
       
        exit;
    }*/
}
