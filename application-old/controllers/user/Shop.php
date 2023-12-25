<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

	public $data;

   public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme']     = 'user';
        $this->data['module']    = 'shop';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model','home');
		$this->load->model('shop_model','shop');
		$this->load->model('api_model', 'api');

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
        
		/* Arabic Language Message */
		$shopMsg = (!empty($this->user_language[$this->user_selected]['lg_Shop_Details'])) ? $this->user_language[$this->user_selected]['lg_Shop_Details'] : $this->default_language['en']['lg_Shop_Details'];	
		$addMsg = (!empty($this->user_language[$this->user_selected]['lg_Add_Msg'])) ? $this->user_language[$this->user_selected]['lg_Add_Msg'] : $this->default_language['en']['lg_Add_Msg'];	
		$editMsg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Msg'])) ? $this->user_language[$this->user_selected]['lg_Edit_Msg'] : $this->default_language['en']['lg_Edit_Msg'];	
		$delMsg = (!empty($this->user_language[$this->user_selected]['lg_Delete_Msg'])) ? $this->user_language[$this->user_selected]['lg_Delete_Msg'] : $this->default_language['en']['lg_Delete_Msg'];	
		$actMsg = (!empty($this->user_language[$this->user_selected]['lg_Active_Msg'])) ? $this->user_language[$this->user_selected]['lg_Active_Msg'] : $this->default_language['en']['lg_Active_Msg'];	
		$inactMsg = (!empty($this->user_language[$this->user_selected]['lg_Inactive_Msg'])) ? $this->user_language[$this->user_selected]['lg_Inactive_Msg'] : $this->default_language['en']['lg_Inactive_Msg'];	
		$adderrMsg = (!empty($this->user_language[$this->user_selected]['lg_Add_Err'])) ? $this->user_language[$this->user_selected]['lg_Add_Err'] : $this->default_language['en']['lg_Add_Err'];	
		$editerrMsg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Err'])) ? $this->user_language[$this->user_selected]['lg_Edit_Err'] : $this->default_language['en']['lg_Edit_Err'];	
		$errMsg = (!empty($this->user_language[$this->user_selected]['lg_Common_Error'])) ? $this->user_language[$this->user_selected]['lg_Common_Error'] : $this->default_language['en']['lg_Common_Error'];	
		
		$this->addmsg = $shopMsg." ".$addMsg;
		$this->adderrmsg = $shopMsg." ".$adderrMsg;
		
		$this->editmsg = $shopMsg." ".$editMsg;
		$this->editerrmsg = $shopMsg." ".$editerrMsg;
		
		$this->delmsg = $shopMsg." ".$delMsg;
		$this->actmsg = $shopMsg." ".$actMsg;
		$this->inactmsg = $shopMsg." ".$inactMsg;
		$this->errmsg = $errMsg;
		        
		$this->feemsg = (!empty($this->user_language[$this->user_selected]['lg_Shopfee_Msg'])) ? $this->user_language[$this->user_selected]['lg_Shopfee_Msg'] : $this->default_language['en']['lg_Shopfee_Msg'];
    }

	
	public function index()
	{
		if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
		$conditions['returnType'] = 'count'; 
        $totalRec = $this->shop->getActiveShops($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['link_func']      = 'getData';
        $config['loading']='<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/shop/ajaxPaginationData'); 
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
		/* Moyaser Payment and Shop Fee*/
		$moyaser_option=settingValue('moyaser_option');
		if($moyaser_option == 1){		
			$this->data['moyaser_apikey']=settingValue('moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('moyaser_secret_key');
		}else if($moyaser_option == 2){
			$this->data['moyaser_apikey']=settingValue('live_moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('live_moyaser_secret_key');
		}
		
		$this->data['paypal_option_status']=settingValue('paypal_option');
		$this->data['stripe_option_status']=settingValue('stripe_option');
		$this->data['razor_option_status']=settingValue('razor_option');

		if($this->data['stripe_option_status'] == 1) {
			$this->data['stripe_key']=settingValue('publishable_key');
		} else {
			$this->data['stripe_key']=settingValue('live_publishable_key');
		}

		$this->data['shop_fee']=settingValue('shop_fee');
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
        $config['base_url']    =  base_url('user/shop/ajaxPaginationData'); 
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
		/* Moyaser Payment and Shop Fee */
		$moyaser_option=settingValue('moyaser_option');
		if($moyaser_option == 1){		
			$this->data['moyaser_apikey']=settingValue('moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('moyaser_secret_key');
		}else if($moyaser_option == 2){
			 $this->data['moyaser_apikey']=settingValue('live_moyaser_apikey');
			 $this->data['moyaser_secret_key']=settingValue('live_moyaser_secret_key');
		}
		$this->data['shop_fee']=settingValue('shop_fee');
        
        // Load the data list view 
        $this->load->view('user/shop/ajax_shop', $this->data, false); 
    }
	public function inactive_shop()
    { 
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->shop->getInactiveShops($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/shop/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['shops'] = $this->shop->getInactiveShops($conditions); 
		/* Moyaser Payment and Shop Fee*/
		$moyaser_option=settingValue('moyaser_option');
		if($moyaser_option == 1){		
			$this->data['moyaser_apikey']=settingValue('moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('moyaser_secret_key');
		}else if($moyaser_option == 2){
			$this->data['moyaser_apikey']=settingValue('live_moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('live_moyaser_secret_key');
		}
		$this->data['shop_fee']=settingValue('shop_fee');
        
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
        $config['base_url']    =  base_url('user/shop/inactiveajaxPaginationData'); 
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
		/* Moyaser Payment and Shop Fee*/
		$moyaser_option=settingValue('moyaser_option');
		if($moyaser_option == 1){		
			$this->data['moyaser_apikey']=settingValue('moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('moyaser_secret_key');
		}else if($moyaser_option == 2){
			$this->data['moyaser_apikey']=settingValue('live_moyaser_apikey');
			$this->data['moyaser_secret_key']=settingValue('live_moyaser_secret_key');
		}
		$this->data['shop_fee']=settingValue('shop_fee');
        
        // Load the data list view 
        $this->load->view('user/shop/ajax_inactive_shop', $this->data, false); 
    }
	public function add_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
		
		$get_subscriptions = $this->db->select('*')->from('subscription_details')->where('subscriber_id', $this->session->userdata('id'))->where('expiry_date_time >=', date('Y-m-d 00:00:59'))->get()->row_array();
		if (!isset($get_subscriptions)) {
			$get_subscriptions['id'] = '';
		}
		if ($get_subscriptions['id'] == '') { 
			redirect(base_url(). "provider-subscription");
		} else {
			$get_availability = $this->db->where('provider_id', $this->session->userdata('id'))->get('business_hours')->row_array();
			if (!empty($get_availability['availability'])) {
				$check_avail = strlen($get_availability['availability']);
			} else {
				$check_avail = 2;
			}
			if ($get_availability == '' || $get_availability['availability'] == '' || $check_avail < 5) {
				redirect(base_url(). "provider-availability");
			}
		}
		$shoppay = $this->db->where('user_provider_id',$this->session->userdata('id'))->where("reason LIKE 'Add Shop'")->get('moyasar_table')->num_rows();
		$shopfee = settingValue('shop_fee');
		$getShop = $this->db->where('provider_id', $this->session->userdata('id'))->get('shops')->num_rows();
		$getShop = $getShop - 1;
		if($shopfee > 0 && $getShop == $shoppay) {
			redirect(base_url(). "shop");
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
            //echo '<pre>'; print_r($this->input->post()); exit;
            removeTag($this->input->post());
           
            $config["upload_path"] = './uploads/shops/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();
			if ($_FILES["images"]["name"] != '') {
				if(!is_dir('uploads/shops')) {
					mkdir('./uploads/shops', 0777, TRUE);
				}
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
						$service_image[] = $this->image_resize(150, 150, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = '';
						$thumb_image[] = $this->image_resize(50, 50, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = '';
					}
				}
			}
			$user_id = $this->session->userdata('id');
           
            $inputs['provider_id'] = $this->session->userdata('id');
			$inputs['shop_name'] = $this->input->post('shop_title');	
			$inputs['description'] = $this->input->post('about');	
			$inputs['country_code'] = $this->input->post('country_code');
			$inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');	
			$inputs['shop_location'] = !empty($this->input->post('shop_location')) ? $this->input->post('shop_location') : '';
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
						'shop_details_image' => ($service_details_image[$i])?$service_details_image[$i]:'',
						'thumb_image' => $thumb_image[$i],
						'mobile_image' => ($mobile_image[$i])?$mobile_image[$i]:''
					);
					
					$get_img = $this->db->get_where('shops_images', array('shop_id'=>$shop_id))->result_array();
					if($get_img) {
						$this->db->where('shop_id', $shop_id);
						$this->db->update('shops_images', $image);
					} else {
						$this->db->insert('shops_images', $image);
					}
				}
			}
						
			if (!empty($result) && $result > 0) {	
				$post = $this->input->post(); 
				if(!empty($post['stitle'])){
				foreach ($post['stitle'] as $key => $value) {					
					$insert = array(
						'shop_id'          => $result,
						'provider_id'  	   => $user_id,		
						'staff_id'		   => 0,
						'service_offer_id' => implode("", $post['serviceoffer_id'][$key]),
						'service_offer_name'  => implode("", $post['stitle'][$key]),
						'labour_charge'    => implode("", $post['samount'][$key]),
						'duration'         => implode("", $post['sduration'][$key]),
						'duration_in'	   => 'min(s)',
					); 
					$offerid[] = implode("", $post['subsubcateid'][$key]);
					$this->db->insert('shop_services_list',$insert); 
				}
				
				$offered_id = implode(",",array_unique($offerid));
				
				$offquery  = "update shops set sub_subcategory='" . $offered_id . "' where id='" . $result . "'";
				$offresult = $this->db->query($offquery);
				}
				$this->session->set_flashdata('success_message', $this->addmsg);
				redirect(base_url() . "shop");
			} else {
                $this->session->set_flashdata('error_message', $this->adderrmsg);
                redirect(base_url() . "shop");
            }			
        }
        
        $this->data['country_list']=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
		$this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
		$this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
		$this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
		
		$uid = $this->session->userdata('id');
		$this->data['shop_servicelist']=$this->db->where('status',1)->order_by('service_offered',"ASC")->get('shop_service_offered')->result_array();
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs();			
		if(!empty($assign)) {
			$this->data['shop_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->where_not_in('id', $assign)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		} else {
			$this->data['shop_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array();
		}
		 
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
		if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }

        $shop_id = $this->uri->segment('2');
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
		$this->data['shop_servicelist']=$this->db->where('status',1)->order_by('service_offered',"ASC")->get('shop_service_offered')->result_array();		
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs($shop_id);			
		if(!empty($assign)) {

			$this->data['shop_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->where_not_in('id', $assign)->order_by("first_name","ASC")->get('employee_basic_details')->result_array();
		} else {
			$this->data['shop_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->order_by("first_name","ASC")->get('employee_basic_details')->result_array();
		}
		
	
	
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
	public function update_shop() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') == 'user') {
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
						$service_image[] = $this->image_resize(150, 150, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = $this->image_resize(150, 150, $image_url, 'de_' . $data["file_name"], $upload_url);
						$thumb_image[] = $this->image_resize(50, 50, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = $this->image_resize(150, 150, $image_url, 'mo_' . $data["file_name"], $upload_url);
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
					$get_img = $this->db->get_where('shops_images', array('shop_id'=>$shop_id))->result_array();
					if($get_img) {
						$this->db->where('shop_id', $shop_id);
						$this->db->update('shops_images', $image);
					} else {
						$this->db->insert('shops_images', $image);
					}
				}
			}
			
			if (!empty($result) && $result > 0) {	
				$post = $this->input->post();	
				$this->db->delete('shop_services_list', array('shop_id' => $shop_id, 'provider_id' => $user_id ));
				foreach ($post['stitle'] as $key => $value) {	
					$insert = array(
						'shop_id'          => $shop_id,
						'provider_id'  	   => $user_id,		
						'staff_id'		   => 0,
						'service_offer_id' => implode("", $post['serviceoffer_id'][$key]),
						'service_offer_name'  => implode("", $post['stitle'][$key]),
						'labour_charge'    => implode("", $post['samount'][$key]),
						'duration'         => implode("", $post['sduration'][$key]),
						'duration_in'	   => 'min(s)',
					); 	
					
					$this->db->insert('shop_services_list',$insert);
					$sr_id = $this->db->insert_id();
					$atype = explode("_",implode("", $post['actiontype'][$key])); 
					if($atype[1] == 'update'){					
						$table_data['id'] = $atype[0];
						$this->db->update('shop_services_list', $table_data, array('id'=>$sr_id));
					} 
					
					
					$offerid[] = implode("", $post['subsubcateid'][$key]);
				}
				$offered_id = '';
				if(!empty($offerid)){
				$offered_id = implode(",",array_unique($offerid));
				}
				
				$offquery  = "update shops set sub_subcategory='" . $offered_id . "' where id='" . $shop_id . "'";
				$offresult = $this->db->query($offquery);				
				
				$this->session->set_flashdata('success_message', $this->editmsg);
				redirect(base_url() . "shop");
			} else {
                $this->session->set_flashdata('error_message', $this->editerrmsg);
                redirect(base_url() . "shop");
            }			
        }
    }
	
	public function shop_preview() {

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
			
			if ($this->session->userdata('usertype') == 'provider' || $this->session->userdata('usertype') == 'freelancer') {
				$MyShop = $this->db->where('md5(id)', $_GET['sid'])->where('provider_id', $this->session->userdata('id'))->get('shops')->num_rows();
				if($MyShop == 0){
					redirect(base_url() . "shop");
				}
			}
			
			
            extract($_GET);
           
            $s_id     = $_GET['sid']; 
			$user_id  = $this->session->userdata('id');
			$user_type = $this->session->userdata('usertype');
            $this->data['module'] = 'shop';
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
            $message = $this->inactmsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
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
            $message = $this->delmsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
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
            $message = $this->actmsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
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
	
	//Moyasarpay
	public function shop_payment() { 
		if($_GET['status'] == 'paid') {	
			$user_currency = get_provider_currency();
			$user_currency_code = $user_currency['user_currency_code'];
			if($this->session->userdata('usertype') == 'freelancer'){
			   $details['type']=3;  
			} else {
				$details['type']=1;  
			}
			$token=$this->session->userdata('chat_token');
			
			$totamt = $_GET['amount']/100;
					
			$details['service_subscription_id'] = 0; 
			$details['token'] = $token;
			$details['user_provider_id'] = $this->session->userdata('id');
			$details['currency_code'] = $user_currency_code;
			$details['amount'] = $totamt;
			$details['reason'] = "Add Shop";
			$details['transaction_id'] = $_GET['id'];
			$details['created_at'] = date('Y-m-d H:i:s'); 
			$details['book_id'] = 0;
			$details['total_amount'] = $totamt;
			$details['paytype'] = 'moyasarpay';
			$this->db->insert('moyasar_table', $details); 
			
			$this->session->set_flashdata('success_message', $this->feemsg);
			
			$this->send_push_notification($token,$this->session->userdata('id'),1,' - New Shop Fee Paid Successfully');
	    } else{
			$data=array('error_message'=>$_GET['message']);
			$this->session->set_flashdata($data);
		}      
		
		redirect(base_url('shop'));
	}

	//Stripe
	public function stripe_shop_payment(){ 	
		if($this->input->post('status') == 'paid') {	
			$user_currency = get_provider_currency();
			$user_currency_code = $user_currency['user_currency_code'];
			if($this->session->userdata('usertype') == 'freelancer'){
			   $details['type']=3;  
			} else {
				$details['type']=1;  
			}
			$token=$this->session->userdata('chat_token');	
			$totamt = $this->input->post('amount')/100;
					
			$details['service_subscription_id'] = 0; 
			$details['token'] = $token;
			$details['user_provider_id'] = $this->session->userdata('id');
			$details['currency_code'] = $user_currency_code;
			$details['amount'] = $totamt;
			$details['reason'] = "Add Shop";
			$details['transaction_id'] = $this->input->post('id');
			$details['created_at'] = date('Y-m-d H:i:s'); 
			$details['book_id'] = 0;
			$details['total_amount'] = $totamt;
			$details['paytype'] = 'stripe';
			$this->db->insert('moyasar_table', $details); 
			
			$this->session->set_flashdata('success_message', $this->feemsg);
			
			$this->send_push_notification($token,$this->session->userdata('id'),1,' - New Shop Fee Paid Successfully');
			echo json_encode(array('success'=>true));
	    } else{
	    	echo json_encode(array('success'=>false));
			$data=array('error_message'=>$_GET['message']);
			$this->session->set_flashdata($data);
		}      
		
		
	}

	//Paypal
	public function paypal_shop_payment($shop_amt){ 
		if($_GET['PayerID'] != '') {	
			$user_currency = get_provider_currency();
			$user_currency_code = ($user_currency['user_currency_code'])?$user_currency['user_currency_code']:settingValue('currency_option');
	
			if($this->session->userdata('usertype') == 'freelancer'){
			   $details['type']=3;  
			} else {
				$details['type']=1;  
			}
			$token=$this->session->userdata('chat_token');
			
			$totamt = $shop_amt;
					
			$details['service_subscription_id'] = 0; 
			$details['token'] = $token;
			$details['user_provider_id'] = $this->session->userdata('id');
			$details['currency_code'] = $user_currency_code;
			$details['amount'] = $totamt;
			$details['reason'] = "Add Shop";
			$details['transaction_id'] = $_GET['PayerID'];
			$details['created_at'] = date('Y-m-d H:i:s'); 
			$details['book_id'] = 0;
			$details['total_amount'] = $totamt;
			$details['paytype'] = 'paypal';
			$this->db->insert('moyasar_table', $details); 
			$this->session->set_flashdata('success_message', $this->feemsg);
			
			$this->send_push_notification($token,$this->session->userdata('id'),1,' - New Shop Fee Paid Successfully');
	    } else{
			$data=array('error_message'=>$_GET['message']);
			$this->session->set_flashdata($data);
		}      
		
		redirect(base_url('shop'));
	}
	/*push notification*/

	public function send_push_notification($token,$provider_id,$type,$msg=''){

		$data=array();
		$data['provider_id']=$this->session->userdata('id');      
		if(!empty($data)){
		if($type==1){
		   $device_tokens=$this->api->get_device_info_multiple($data['provider_id'],1); 
		 }
		/*insert notification*/
		$msg=ucfirst($this->session->userdata('name')).' '.strtolower($msg);

		$receiver='';
		$admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
		if(empty($admin['token'])){
		 
		  $receiver=$this->getToken(14,$admin['user_id']);
		  $receiver_token_update=$this->db->where('role',1)->update('administrators',['token'=>$receiver]);
		}else{
		  $receiver=$admin['token'];
		}

		 if(!empty($receiver)){
			  $this->api->insert_notification($token,$receiver,$msg);
		 }
		 

		$title='Subscription';
	   

		if (!empty($device_tokens)) {
		  foreach ($device_tokens as $key => $device) {
					  if(!empty($device['device_type']) && !empty($device['device_id'])){
					  
					  if(strtolower($device['device_type'])=='android'){
					  
					  $notify_structure=array(
					  'title' => $title,
					  'message' => $msg,
					  'image' => 'test22',
					  'action' => 'test222',
					  'action_destination' => 'test222',
					  );
					  
					  sendFCMMessage($notify_structure,$device['device_id']);  
					  
					  }
					  
					  if(strtolower($device['device_type']=='ios')){
					  $notify_structure= array(
					  'alert' => $msg,
					  'sound' => 'default',
					  'badge' => 0,
					  );
					  
					  
					  sendApnsMessage($notify_structure,$device['device_id']);  
					  
					  }
					  }
		  }
		 
		}


/*apns push notification*/
		}else{
		 $this->token_error();
		}
	}
    public function image_resize($width = 0, $height = 0, $image_url, $filename, $upload_url) {

        $source_path = base_url().$image_url;
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
	
	
}
