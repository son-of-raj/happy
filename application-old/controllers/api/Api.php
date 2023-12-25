<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

    public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->load->helper('push_notifications');
        $this->load->helper('user_timezone');
        $this->load->model('api_model', 'api');
        $this->load->model('admin_model', 'admin');
        $this->load->model('Stripe_model');
        $this->load->model('user_login_model', 'user_login');
        $this->load->model('templates_model');
        $this->load->model('products_model', 'products');

        $header = getallheaders(); // Get Header Data
        $token = (!empty($header['token'])) ? $header['token'] : '';
        if (empty($token)) {
            $token = (!empty($header['Token'])) ? $header['Token'] : '';
        }
        $this->default_token = md5('Dreams99');
        $this->api_token = $token;
        $this->user_id = $this->api->get_user_id_using_token($token); /* provider */
        $this->users_id = $this->api->get_users_id_using_token($token); /* user */

        /* language API */

        $lang = (!empty($header['language'])) ? $header['language'] : '';
        if (empty($lang)) {
            $lang = (!empty($header['Language'])) ? $header['Language'] : 'en';
        }
        $language = get_languages($lang);
        $language = (!empty($language['language']['api'])) ? $language['language']['api'] : '';
        $this->language_content = $language;


        $this->website_name = '';
        $this->data['secret_key'] = '';
        $this->data['publishable_key'] = '';
        $this->data['website_logo_front'] = 'assets/img/logo.png';

        $publishable_key = '';
        $secret_key = '';
        $live_publishable_key = '';
        $live_secret_key = '';
        $stripe_option = '';
        $login_type = '';



        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'website_name') {
                    $this->website_name = $data['value'];
                }
                if ($data['key'] == 'secret_key') {
                    $secret_key = $data['value'];
                }

                if ($data['key'] == 'publishable_key') {
                    $publishable_key = $data['value'];
                }

                if ($data['key'] == 'live_secret_key') {
                    $live_secret_key = $data['value'];
                }

                if ($data['key'] == 'live_publishable_key') {
                    $live_publishable_key = $data['value'];
                }

                if ($data['key'] == 'stripe_option') {
                    $stripe_option = $data['value'];
                }
				
				if ($data['key'] == 'login_type') {
                    $login_type = $data['value'];
                }
            }
        }



        if (@$stripe_option == 1) {
            $this->data['publishable_key'] = $publishable_key;
            $this->data['secret_key'] = $secret_key;
        }

        if (@$stripe_option == 2) {
            $this->data['publishable_key'] = $live_publishable_key;
            $this->data['secret_key'] = $live_secret_key;
        }


        $config['publishable_key'] = $this->data['publishable_key'];
        $config['secret_key'] = $this->data['secret_key'];

        $this->load->library('stripe', $config);
    }
	
	
	public function getlogin_type_get(){
		$loginquery = $this->db->query("select * from system_settings WHERE status = 1");
			   $loginresult = $loginquery->result_array();
			   if (!empty($loginresult)) {
				   foreach ($loginresult as $logindata) {
		if($logindata['key'] == 'login_type'){
		$data['login_type'] = $logindata['value'];
		}
		}
		}
		$response_code = 200;
			   $response_message = "Get Login type";
		$result = $this->data_format($response_code, $response_message, $data);
			   $this->response($result, REST_Controller::HTTP_OK);
		}
		
		
		public function check_provider_email_post(){
			
			$user_data = array();
			$user_data = $this->post();
			$is_available_email = $this->api->check_email($user_data);
			if($is_available_email > 0)
			{
				$response_code = '200';
				$response_message = "Email ID Exist";
			}
			else
			{
				$response_code = '200';
				$response_message = "Email ID Not Exist";
			}
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		
		public function check_user_emailid_post(){
			
			$user_data = array();
			$user_data = $this->post();
			$is_available_email = $this->api->check_user_email($user_data);
			if($is_available_email > 0)
			{
				$response_code = '200';
				$response_message = "Email ID Exist";
			}
			else
			{
				$response_code = '200';
				$response_message = "Email ID Not Exist";
			}
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		
		public function forget_password_post(){
			
		
			
			$user_data = $this->post();
			$email=$user_data['email'];
			$mode=$user_data['mode'];
			
			if($mode==2)
			{
				$result = $this->user_login->check_user_emaildet($email);
				$user_type='user';
			}
			else
			{
				$result = $this->user_login->check_provider_emaildet($email);
				$user_type='provider';
			}
			
			if(!empty($result))
			{
				$token=rand(1000,9999);
				$pwdlink=base_url()."user/login/userchangepwd/".base64_encode($result['id'])."/".base64_encode($token)."/".base64_encode($mode);
				$chk_forpawd=$this->db->where('user_id',$result['id'])->where('user_type',$user_type)->where('status','1')->select('*')->get('forget_password_det')->result_array(); 
				if(empty($chk_forpawd))
				{
					$pwdlink_data=array(
					  'endtime'=>time()+300,
					  'token'=> $token,
					  'user_id'=>$result['id'],
					  'email'=>$result['email'],
					  'pwdlink'=>$pwdlink,
					  'user_type'=>$user_type,
					  'created_at'=>date('Y-m-d H:i:s')
					);
					$save_forpwd = $this->admin->save_pwdlink_data($pwdlink_data);
				}
				else
				{
					$pwdlink_data=array(
				  'endtime'=>time()+300,
				  'token'=>$token ,
				  'user_id'=>$result['id'],
				  'email'=>$result['email'],
				  'pwdlink'=>$pwdlink,
				  'user_type'=>$user_type,
				  'updated_on'=>date('Y-m-d H:i:s')
					);
					$save_forpwd = $this->admin->update_pwdlink_data($pwdlink_data,$result['user_id']);
				}
				
				$message='Reset Link  '.$pwdlink.''; 
				$body = 'Hi '.$result["name"].',<br> '.$message;
				
				$phpmail_config = settingValue('mail_config');
				
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				
				$this->load->library('email');
				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
							->from($from_email)
							->to($result["email"])
							->subject('User Forget Password Link')
							->message($body)
							->send();
							
				}
				
				$response_code = '200';
				$response_message = "Reset link sent to your Email";
				
			}
		 else
			{
				
				$response_code = '201';
				$response_message = "Email ID Not Exist";
			}
			
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		
		public function check_pro_newpassword_post(){
			
			$user_data = array();
			$user_data = $this->post();
			
			$user_id       = $user_data['user_id'];
			$user_type       = $user_data['user_type'];
			if($user_type=='provider')
			{
				$user = $this->db->where('id', $user_id)->where('password', md5($user_data('current_password')))->get('providers')->row_array();
			}
			else
			{
				$user = $this->db->where('id', $user_id)->where('password', md5($user_data('current_password')))->get('users')->row_array();
			}
			
			if(!empty($user))
			{
				$response_code = '200';
				$response_message = "Password Matched";
			}
			else
			{
				$response_code = '200';
				$response_message = "Password Mismatched";
			}
			
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		
		
		public function userchangepassword_post(){
			
			$user_data = array();
			$user_data = $this->post();
			
			$user_id       = $user_data['user_id'];
			$user_type       = $user_data['user_type'];
			$current_password       = md5($user_data['current_password']);
			$confirm_password       = $user_data['confirm_password'];
			
			$table_data=array("password"=>md5($confirm_password));
			$this->db->where('id',$user_id);
			if($user_type=='provider')
			{
			
				$prodet = $this->api->checkpropwd($user_id,$current_password);
				if($prodet > 0)
				{
					$this->db->where('id',$user_id);  
					if($this->db->update('providers', $table_data))
					{
						$response_code = '200';
						$response_message = "Password updated successfully";
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
					}
				}
				else
				{
					$response_code = '201';
					$response_message = "Current Password Mismatched";
				}
				
				
			}
			else
			{
			
				$user = $this->api->checkuserpwd($user_id,$current_password);
				if($user > 0)
				{
					$this->db->where('id',$user_id);  
					if($this->db->update('users', $table_data))
					{
						$response_code = '200';
						$response_message = "User password updated successfully";
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
					}
				}
				else
				{
					$response_code = '201';
					$response_message = "Current Password Mismatched";
				}
			}	
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		}
		
		
	public function home_post()
	{
		if($this->api_token)
		{
			$data = new stdClass();
			$user_data = $this->post();
			//check inputs
			$this->verifyRequiredParams(['latitude', 'longitude'],$user_data);
			//get user id
			$users_id = $this->api->get_users_id_using_token($this->api_token);
			if ($users_id!=0) 
			{
				$user = $this->api->getsingletabledata('users', ['id'=>$users_id], '', 'id', 'asc', 'single');
				//get currency
				$user_currency = get_api_user_currency($users_id);
        		$UserCurrency = $user_currency['user_currency_code'];
        		$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
        		if ($user['gender']!='') 
        		{
        			$gender = $user['gender'];
        		}
        		else
        		{
        			$gender = '';
        		}
        		
			}
			else
			{
				//get currency from settings
				$UserCurrency = settings('currency');
				$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
				$gender = '';
			
			}
			//get featured categories
			$wcat = array('c.status'=>1);
			// if ($gender!='') 
			// {
			// 	$wcat['find_in_set("'.$gender.'", c.gender_type) <>'] = 0;
			// }
			$featured_category = $this->api->featured_category_list(4,0,$wcat);
			//sub categories
			$wsub = array('subcategories.status'=>1);
			// if ($gender!='') 
			// {
			// 	$wsub['find_in_set("'.$gender.'", categories.gender_type) <>'] = 0;
			// }
            
			$subcategory = $this->api->sub_category_list(4,0,$wsub, 'list');
			//get popular services based on views
			$wpservices = array('services.status'=>1);
			$search = array();
			$popular_services = $this->api->popular_services_list(4,0,$wpservices, $search, '', $user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, 3, 3);
			//popular shops
			$wpshop = array('shops.status'=>1);
			$popular_shops = $this->api->featured_shops_list(4,0,$wpshop,'','',$user_data['latitude'], $user_data['longitude'], ['column_name'=>'shops.total_views', 'order'=>'desc']);
			//get featured shops
			//sub crib id
			$sub = $this->api->getsingletabledata('subscription_fee', ['subscription_type'=>3, 'status'=>1], '', 'id', 'asc', 'single');
			$wfshop = array('subscription_details.subscription_id'=>$sub['id'],'subscription_details.expiry_date_time >='=>date('Y-m-d'), 'shops.status'=>1);
			$featured_shops = $this->api->featured_shops_list(4,0,$wfshop,'','',$user_data['latitude'], $user_data['longitude'], ['column_name'=>'shops.id', 'order'=>'desc']);
			//nearest shops
			$wshop = array('shops.status'=>1);
			$nearest_shops = $this->api->featured_shops_list(4,0,$wshop,'','',$user_data['latitude'], $user_data['longitude'], ['column_name'=>'shops.id', 'order'=>'desc']);
			//Offer
			$cur_date = date("Y-m-d");
    		$current_time = date("H:i");
			$woffer = array('service_offers.status'=>0, 'service_offers.df'=>0);
			$offers = $this->api->offered_services_list(4,0,$woffer,'','',$user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, 3);
			//result
            $where = array();

            $where['user_id'] = $users_id;

            $this->db->where($where);
            $this->db->where('status',0);
            $this->db->group_by('product_id');

            $count = $this->db->count_all_results('product_cart');
			if (!empty($featured_category)) 
			{
				$response_code = '200';
                $response_message = "Home Page";
                $res['featured_category'] = $featured_category;
                $res['subcategory'] = $subcategory;
                $res['popular_services'] = $popular_services;
                $res['popular_shops'] = $popular_shops;
                $res['featured_shops'] = $featured_shops;
                $res['nearest_shops'] = $nearest_shops;
                $res['offers'] = $offers;
                $res['cartCount'] = $count;
                $data = $res;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $res['featured_category'] = [];
                $res['subcategory'] = [];
                $res['popular_services'] = [];
                $res['popular_shops'] = [];
                $res['featured_shops'] = [];
                $res['nearest_shops'] = [];
                $res['offers'] = [];
                $res['cartCount'] = 0;
                $data = $res;
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
        	$this->token_error();
        }
	}
	public function all_featured_categories_post()
	{
		if($this->api_token)
		{
			$user_data = $this->post();
			//check inputs
			$this->verifyRequiredParams(['per_page', 'page_no'],$user_data);
			$end = ($user_data['page_no']-1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			//get user id
			$users_id = $this->api->get_users_id_using_token($this->api_token);
			if ($users_id!=0) 
			{
				$user = $this->api->getsingletabledata('users', ['id'=>$users_id], '', 'id', 'asc', 'single');
        		if ($user['gender']!='') 
        		{
        			$gender = $user['gender'];
        		}
        		else
        		{
        			$gender = '';
        		}
			}
			else
			{
				$gender = '';
			}
			//get featured categories
			$wcat = array('status'=>1);
			if ($gender!='') 
			{
				$wcat['find_in_set("'.$gender.'", gender_type) <>'] = 0;
			}
			//total
			$total = $this->api->getCountRows('categories',$wcat,'id', '', '');
			$featured_category = $this->api->featured_category_list($user_data['per_page'], $end, $wcat);
			if (!empty($featured_category)) 
			{
                
                $response_code = '200';
	            $response_message = 'Featured Category Listed Successfully';
	            $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['list'] = $featured_category;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $data = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
        	$this->token_error();
        }
	}
	public function all_subcategories_post()
	{
		if($this->api_token)
		{
			$user_data = $this->post();
			//check inputs
			$this->verifyRequiredParams(['per_page', 'page_no'],$user_data);
			$end = ($user_data['page_no']-1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			//get user id
			$users_id = $this->api->get_users_id_using_token($this->api_token);
			if ($users_id!=0) 
			{
				$user = $this->api->getsingletabledata('users', ['id'=>$users_id], '', 'id', 'asc', 'single');
        		if ($user['gender']!='') 
        		{
        			$gender = $user['gender'];
        		}
        		else
        		{
        			$gender = '';
        		}
			}
			else
			{
				$gender = '';
			}
			//get featured categories
			$wsub = array('subcategories.status'=>1);
			if ($gender!='') 
			{
				$wsub['find_in_set("'.$gender.'", categories.gender_type) <>'] = 0;
			}
			$total = $this->api->sub_category_list($user_data['per_page'],$end,$wsub,'count');
			$subcategory = $this->api->sub_category_list($user_data['per_page'],$end,$wsub, 'list');
			if (!empty($subcategory)) 
			{
                
                $response_code = '200';
	            $response_message = 'Sub Category Listed Successfully';
	            $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['list'] = $subcategory;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $data = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
        	$this->token_error();
        }
	}
	public function all_popular_services_post()
	{
		if($this->api_token)
		{
			$user_data = $this->post();
			//check inputs
			$this->verifyneedparameters(['latitude', 'longitude', 'per_page', 'page_no', 'sortby', 'service_title', 'category', 'subcategory', 'sub_subcategory', 'price_range'],$user_data);
			$this->verifyRequiredParams(['latitude', 'longitude', 'per_page', 'page_no'],$user_data);
			//get user id
			$users_id = $this->api->get_users_id_using_token($this->api_token);
			if ($users_id!=0) 
			{
				//get currency
				$user_currency = get_api_user_currency($users_id);
        		$UserCurrency = $user_currency['user_currency_code'];
        		$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
        		
			}
			else
			{
				//get currency from settings
				$UserCurrency = settings('currency');
				$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
			}

			$wpservices = array('services.status'=>1);
			$search = array();
			$where_in = array();
			if ($user_data['service_title']!='') {
				$search['services.service_title'] = $user_data['service_title'];
			}
			if ($user_data['category']!='') {
				$wpservices['services.category'] = $user_data['category'];
			}
			if ($user_data['subcategory']!='') {
				$wpservices['services.subcategory'] = $user_data['subcategory'];
			}
			if ($user_data['sub_subcategory']!='') 
			{
				$sssub_arr = explode(",", $user_data['sub_subcategory']);
				$where_in[] = ['type'=>'In', 'column_name'=>'services.sub_subcategory', 'value'=>$sssub_arr];
				
			}
			//price range
			if ($user_data['price_range']!='any') 
	        {
	            //split range
	            $range = explode("-", $user_data['price_range']);
	            
	            if ($range[0]!='') 
	            {
	                $wpservices['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") >='] = (float)$range[0];
	            }
	            if ($range[1]!='') 
	            {
	                $wpservices['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") <='] = (float)$range[1];
	            }
	        }
			$total = $this->api->popular_services_list('','',$wpservices, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, 1, $user_data['sortby']);
			$popular_services = $this->api->popular_services_list($user_data['per_page'],$end,$wpservices, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, 1, $user_data['sortby']);
			if (!empty($popular_services)) 
			{
				$response_code = '200';
	            $response_message = 'Service Listed Successfully';
	            $data['total_rows'] = count($total);
                $total_pages = ceil(count($total)/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['list'] = $popular_services;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $data = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->token_error();
		}
	}
	
	public function view_all_services_post()
	{
		if($this->api_token)
		{
			$user_data = $this->post();
			//check inputs
			$this->verifyneedparameters(['type', 'latitude', 'longitude', 'per_page', 'page_no', 'sortby', 'service_title', 'category', 'subcategory', 'sub_subcategory', 'price_range'],$user_data);
			$this->verifyRequiredParams(['type','latitude', 'longitude', 'per_page', 'page_no'],$user_data);
			//get user id
			$users_id = $this->api->get_users_id_using_token($this->api_token);
			if ($users_id!=0) 
			{
				//get currency
				$user_currency = get_api_user_currency($users_id);
        		$UserCurrency = $user_currency['user_currency_code'];
        		$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
        		
			}
			else
			{
				//get currency from settings
				$UserCurrency = settings('currency');
				$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
			}
			if ($user_data['type']==1 || $user_data['type']==3) //Featured services
			{
				$wpservices = array('services.status'=>1);
				$search = array();
				$where_in = array();
				if ($user_data['service_title']!='') {
					$search['services.service_title'] = $user_data['service_title'];
				}
				if ($user_data['category']!='') {
					$wpservices['services.category'] = $user_data['category'];
				}
				if ($user_data['subcategory']!='') {
					$wpservices['services.subcategory'] = $user_data['subcategory'];
				}
				if ($user_data['sub_subcategory']!='') 
				{
					$sssub_arr = explode(",", $user_data['sub_subcategory']);
					$where_in[] = ['type'=>'In', 'column_name'=>'services.sub_subcategory', 'value'=>$sssub_arr];
					
				}
				//price range
				if ($user_data['price_range']!='any') 
		        {
		            //split range
		            $range = explode("-", $user_data['price_range']);
		            
		            if ($range[0]!='') 
		            {
		                $wpservices['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") >='] = (float)$range[0];
		            }
		            if ($range[1]!='') 
		            {
		                $wpservices['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") <='] = (float)$range[1];
		            }
		        }
				$total = $this->api->popular_services_list('','',$wpservices, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, $user_data['type'], $user_data['sortby']);
				$services = $this->api->popular_services_list($user_data['per_page'],$user_data['page_no'],$wpservices, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, $user_data['type'], $user_data['sortby']);

			}
			if ($user_data['type']==2)// Offers
			{
				$woffer = array('service_offers.status'=>0, 'service_offers.df'=>0);
				$search = array();
				$where_in = array();
				if ($user_data['service_title']!='') {
					$search['services.service_title'] = $user_data['service_title'];
				}
				if ($user_data['category']!='') {
					$woffer['services.category'] = $user_data['category'];
				}
				if ($user_data['subcategory']!='') {
					$woffer['services.subcategory'] = $user_data['subcategory'];
				}
				if ($user_data['sub_subcategory']!='') 
				{
					$sssub_arr = explode(",", $user_data['sub_subcategory']);
					$where_in[] = ['type'=>'In', 'column_name'=>'services.sub_subcategory', 'value'=>$sssub_arr];
					
				}
				//price range
				if ($user_data['price_range']!='any') 
		        {
		            //split range
		            $range = explode("-", $user_data['price_range']);
		            
		            if ($range[0]!='') 
		            {
		                $woffer['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") >='] = (float)$range[0];
		            }
		            if ($range[1]!='') 
		            {
		                $woffer['get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") <='] = (float)$range[1];
		            }
		        }
		        $total = $this->api->offered_services_list('','',$woffer,$search,$where_in,$user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, $user_data['sortby']);

				$services = $this->api->offered_services_list($user_data['per_page'],$end,$woffer,$search,$where_in,$user_data['latitude'], $user_data['longitude'], $UserCurrency, $currency_sign, $user_data['sortby']);
			}
			if (!empty($services)) 
			{
				$response_code = '200';
	            $response_message = 'Services Listed Successfully';
	            $data['total_rows'] = count($total);
                $total_pages = ceil(count($total)/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['list'] = $services;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $data = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->token_error();
		}
	}
	public function view_all_shops_post()
	{
		if($this->api_token)
		{
			$user_data = $this->post();
			//check inputs
			$this->verifyneedparameters(['type', 'latitude', 'longitude', 'per_page', 'page_no', 'shop_name', 'category', 'subcategory', 'sub_subcategory'],$user_data);
			$this->verifyRequiredParams(['type','latitude', 'longitude', 'per_page', 'page_no'],$user_data);
			$where = array('shops.status'=>1);
			$search = array();
			$where_in = array();
			$orderby = array();
			if ($user_data['shop_name']!='') {
				$search['shops.shop_name'] = $user_data['shop_name'];
			}
			if ($user_data['category']!='') {
				$where['shops.category'] = $user_data['category'];
			}
			if ($user_data['subcategory']!='') {
				$where['shops.subcategory'] = $user_data['subcategory'];
			}
			if ($user_data['sub_subcategory']!='') {
				$where['shops.sub_subcategory'] = $user_data['sub_subcategory'];
			}
			if ($user_data['type']==1) //popular shops
			{
				$orderby = array('column_name'=>'shops.total_views', 'order'=>'desc');
			}
			if ($user_data['type']==2) //featured shops
			{
				$sub = $this->api->getsingletabledata('subscription_fee', ['subscription_type'=>3, 'status'=>1], '', 'id', 'asc', 'single');
				$where['subscription_details.subscription_id'] = $sub['id'];
				$where['subscription_details.expiry_date_time >='] = date('Y-m-d');

				$orderby = array('column_name'=>'shops.id', 'order'=>'desc');
			}
			if ($user_data['type']==3) //nearest shops
			{
				$orderby = array('column_name'=>'shops.id', 'order'=>'desc');
			}
			$total = $this->api->featured_shops_list('','',$where, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $orderby);
			$shops = $this->api->featured_shops_list($user_data['per_page'], $user_data['page_no'], $where, $search, $where_in, $user_data['latitude'], $user_data['longitude'], $orderby);
            if (!empty($shops)) 
			{
				$response_code = '200';
	            $response_message = 'Shops Listed Successfully';
	            $data['total_rows'] = count($total);
                $total_pages = ceil(count($total)/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['list'] = $shops;
			}
			else
			{
				$response_code = '200';
                $response_message = "No Results found";
                $data = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->token_error();
		}
	}
	public function service_preview_get()
	{
		if($this->api_token)
		{
			if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
            	//get user id
				$users_id = $this->api->get_users_id_using_token($this->api_token);
				if ($users_id!=0) 
				{
					//get currency
					$user_currency = get_api_user_currency($users_id);
	        		$UserCurrency = $user_currency['user_currency_code'];
	        		$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
	        		
				}
				else
				{
					//get currency from settings
					$UserCurrency = settings('currency');
					$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
				}
                $where = array('services.id'=>$_GET['id'], 'services.status'=>1);
                $service_details = $this->api->service_details($where, $UserCurrency, $currency_sign);
                if (!empty($service_details)) 
                {
                	$service_details['service_gallery'] = array_column($service_details['service_gallery'], 'service_image');
	            	$soffered = $this->db->from('service_offered')->where('service_id', $_GET['id'])->get()->result_array();
	            	if (!empty($soffered)) {
	            		$service_details['service_offered'] = $soffered;
	            	}
	            	else
	            	{
	            		$service_details['service_offered'] = '';
	            	}
	            	//offer for service
	            	$offer = $this->api->check_current_offer(['service_id'=>$_GET['id'], 'status'=>0, 'df'=>0]);
	            	if (!empty($offer)) {
	            		$service_details['current_offer'] = $offer['offer_percentage'];
	            		$service_details['end_time'] = date("h:i A", strtotime($offer['end_time']));
	            	}
	            	else
	            	{
	            		$service_details['current_offer'] = '';
	            		$service_details['end_time'] = '';
	            	}
	            	//reviews
	            	$service_details['reviews'] = $this->api->rating_review_list(['rating_review.service_id'=>$_GET['id'], 'rating_review.status'=>1, 'rating_review.delete_status'=>0]);
	            	//Availability
	            	$service_details['availability'] = $this->api->getsingletabledata('business_hours', ['provider_id'=>$service_details['user_id']], '', 'id', 'asc', 'single');
					$wpservices = array('services.status'=>1);
					$search = array();
					$popular_services = $this->api->popular_services_list(4,0,$wpservices, $search, '', $service_details['service_latitude'], $service_details['service_longitude'], $UserCurrency, $currency_sign, 1, 3);
					if (!empty($popular_services)) {
						$service_details['related_services'] = $popular_services;
					}
					else
					{
						$service_details['related_services'] = [];
					}

					$response_code = '200';
                    $response_message = "Service Details";
                    $data['details'] = $service_details;
                }
                else
                {
                	$response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            	
            } 
            else 
            {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
            $result = $this->data_format($response_code, $response_message, $data);
        	$this->response($result, REST_Controller::HTTP_OK);
		}
		else
		{
			$this->token_error();
		}
	}
    public function home1_post() {
		if($this->api_token){
        $data = new stdClass();
        $user_data = array();
        $user_data = $this->post();
        if (!empty($user_data['latitude']) && !empty($user_data['longitude'])) {
			$user_data['user_id']=$this->api->get_users_id_using_token($this->api_token);
            $category_list = $this->api->get_category();
            $popular_services = $this->api->get_service(1, $user_data);
            $new_services = $this->api->get_service(2, $user_data);


            if (!empty($category_list) || !empty($popular_services) || !empty($new_services)) {
                $response_code = '200';
                $response_message = "Home Page";
                $res['category_list'] = $category_list;
                $res['popular_services'] = $popular_services;
                $res['new_services'] = $new_services;
                $data = $res;
            } else {
                $response_code = '200';
                $response_message = "No Results found";
                $res['category_list'] = [];
                $res['popular_services'] = [];
                $res['new_services'] = [];
                $data = $res;
            }
        } else {
            $response_code = '200';
            $response_message = "Input field missing";
            $res['category_list'] = [];
            $res['popular_services'] = [];
            $res['new_services'] = [];
            $data = $res;
        }


        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }else{
        $this->token_error();
        }
    }

    public function country_details_get() {
        $data = $this->db->select('country_code,country_id,country_name')->order_by('country_name', 'asc')->get('country_table')->result_array();
        $response_code = 200;
        $response_message = "Fetched Successfully...";
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function demo_home_post() {
       
        $data = new stdClass();
        $user_data = array();
        $user_data = $this->post();
		$user_data['user_id'] = $this->user_id;
		$user_data['users_id'] = $this->users_id;

        if (!empty($user_data['latitude']) && !empty($user_data['longitude'])) {

            $category_list = $this->api->get_category();
            $popular_services = $this->api->get_service(1, $user_data);

            $new_services = $this->api->get_service(2, $user_data);


            if (!empty($category_list) && !empty($popular_services) && !empty($new_services)) {
                $response_code = '200';
                $response_message = "Home Page";
                $res['category_list'] = $category_list;
                $res['popular_services'] = $popular_services;
                $res['new_services'] = $new_services;
                $data = $res;
            } else {
                $response_code = '200';
                $response_message = "Home Page";
                $res['category_list'] = $this->api->get_category();
                $res['popular_services'] = $this->api->get_demo_service(1, $user_data);
                $res['new_services'] = $this->api->get_demo_service(2, $user_data);
                $data = $res;
            }
        } else {
            $response_code = '200';
            $response_message = "Home Page";
            $res['category_list'] = $this->api->get_category();
            $res['popular_services'] = $this->api->get_demo_service(1, $user_data);
            $res['new_services'] = $this->api->get_demo_service(2, $user_data);
            $data = $res;
        }


        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function my_service_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $user_data['token'] = $this->api_token;
            $user_data['user_id'] = $this->user_id;

            if (!empty($this->post('type'))) {
                $user_data['type'] = $this->post('type');
            }

            $result = $this->api->get_my_service($user_data);

            if (!empty($result)) {
                $response_code = '200';
                $response_message = "Service list";
                $data = $result;
            } else {
                $response_code = '200';
                $response_message = "No Results found";
                $data = array();
            }


            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function service_details_get() {

        if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $token = $this->api_token;
                $user_id = $this->api->get_users_id_using_token($token);
                if($user_id == ''){
                    $user_id = $this->api->get_user_id_using_token($token);
                    $pro=$this->api->getSingleData('providers',['type'],['id' => $user_id]);
                    $type=$pro->type;
                }
                

                $inputs = array();
                $inputs['id'] = $this->get('id');
                $service_details = $this->api->get_service_details($inputs, $user_id, $type);
            	

                if (!empty($service_details)) {



                    $this->db->select("r.*,u.*");
                    $this->db->from('rating_review r');
                    $this->db->join('users u', 'r.user_id = u.id', 'LEFT');
                    $this->db->where("r.service_id", $inputs['id']);
                    $review_details = $this->db->get()->result_array();
                    $review_list = array();
                    foreach ($review_details as $review) {

                        $reviews['name'] = $review['name'];
                        $reviews['profile_img'] = $review['profile_img'];
                        $reviews['rating'] = $review['rating'];
                        $reviews['review'] = $review['review'];
                        $reviews['created'] = $review['created'];
                        $review_list[] = $reviews;
                    }

                    $response_code = '200';
                    $response_message = "Service Details";
                    $data['service_overview'] = $service_details['service_overview'];
                    $data['seller_overview'] = $service_details['seller_overview'];
                    $data['seller_overview']['services'] = $service_details['seller_services'];

                    $data['reviews'] = $review_list;
					
					$addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$inputs['id'])->get('additional_services')->result_array();
					$data['additional_services'] = $addi_ser;
					
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Service id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function all_services_post() {
        if($this->api_token){
        $user_data = array();
        $data = array();
        $user_data = $this->post();
        $inputs['page'] = (!empty($inputs['page'])) ? $inputs['page'] : 1;
        if (!empty($user_data['type']) && !empty($user_data['latitude']) && !empty($user_data['longitude'])) {
            
            $user_id = $this->api->get_users_id_using_token($this->api_token);
            $response = $this->api->all_services($user_data,$user_id);

            if (!empty($response['service_list'])) {

                $response_code = '200';
                $response_message = 'All Service';
                $data = $response;
            } else {
                $response_code = '200';
                $response_message = 'No Services Found';
                $data['service_list'] = array();
            }
        } else {
            $response_code = '200';
            $response_message = 'Input field missing';
            $data = (object) array();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    
    
    }else{
         $this->token_error();
        }
    }
    public function category_get() {



        $data = array();
        $category_list = $this->api->get_categories();
        if (!empty($category_list)) {
            $response_code = '200';
            $response_message = "Category List";
            $data['category_list'] = $category_list;
        } else {
            $response_code = '200';
            $response_message = "No Results found";
        }


        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function subcategory_post() {

        $data['subcategory_list'] = array();
        $user_post_data = $this->post();
        $subcategory_list = $this->api->get_subcategories($user_post_data['category']);
        if (!empty($subcategory_list)) {
            $response_code = '200';
            $response_message = "Subcategory List";
            $data['subcategory_list'] = $subcategory_list;
        } else {
            $response_code = '200';
            $response_message = "No Results found";
        }


        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

     public function provider_signin_post() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
           
            $user_data = array();
            $user_data = $this->post();


            if (!empty($user_data['mobileno']) && !empty($user_data['otp']) && !empty($user_data['country_code'])) {
                $is_available_mobile = $this->api->check_mobile_no($user_data);
                $is_available_user = $this->api->check_user_mobileno($user_data);
                $is_available_inactive = $this->api->check_delete_providers($user_data);

                if ($is_available_inactive == 0) {

                    if ($is_available_user == 0) {

                        if ($is_available_mobile == 1) {


                            $check_data['mobile_number'] = $user_data['mobileno'];
                            $check_data['otp'] = $user_data['otp'];
                            $check_data['country_code'] = $user_data['country_code'];

                            $check = $this->api->check_otp($check_data);
                           
                            if (is_array($check) && !empty($check)) {

                                $mobile_number = $user_data['mobileno'];
                                $user_details = $this->api->get_provider_details($mobile_number, $user_data);
                            }
                           
                            if (!empty($user_details)) {
                                $response_code = '200';
                                $response_message = 'LoggedIn Successfully';
                                $data['provider_details'] = $user_details;

                               
                            } else {
                                $response_code = '202';
                                $response_message = 'Login failed, Invalid OTP or mobile number';
                            }
                        } else {
                            $response_code = '500';
                            $response_message = 'Mobile number does not exits';
                        }
                    } else {
                        $response_code = '500';
                        $response_message = 'This number is already registered as User.';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'This number is not active.';
                }
            } else if(!empty($user_data['email']) && !empty($user_data['password'])){
				
                $is_available_email = $this->api->check_email($user_data);
				
				if ($is_available_email == 1) {
					$check = $this->api->check_email($user_data);
					
                        if ($check  == 1) {
                            $email = $user_data['email'];
							$password = md5($user_data['password']);
                            $user_details = $this->api->get_provider_details_by_email($email,$password,$user_data);
                           
                        }
                        if (!empty($user_details)) {
                            $response_code = '200';
                            $response_message = 'LoggedIn Successfully';
                            $data['provider_details'] = $user_details;
                        } else {
                            $response_code = '202';
                            $response_message = 'Login failed, Invalid Email Id';
                        }
				}else{
					$response_code = '500';
					$response_message = 'Email id does not exists';
				}
				
			} else {
                $response_code = '500';
                $response_message = 'Inputs field missing';
            }
           
            if(empty($data)) {
                $data =new stdClass();
            }
            $result = $this->data_format($response_code, $response_message, $data);
           
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function update_provider_post() {

        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '128M');

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();


            /*
             * Currency Update Start
             */

            if (!empty($this->post('user_currency'))) {
                $currency = $this->post('user_currency');
                $user_data['currency_code'] = $user_data['user_currency'];
                $user_id = $this->user_id;
                $token = $this->api_token;

                $user_wallet = $this->Stripe_model->get_wallet_pro($token);
                $user_info = $this->Stripe_model->get_provider($token);
                $wallet_history = $this->Stripe_model->get_wallet_history_info($token, $currency);
                $credit = $debit = 0;


                if (count($wallet_history) > 0) {
                    foreach ($wallet_history as $key => $value) {




                        if ($value['credit_wallet'] != 0) {

                            $credit_amt = get_gigs_currency($value['credit_wallet'], $value['currency_code'], $this->post('user_currency'));


                            $credit += round($credit_amt, 2);
                        }
                        if ($value['debit_wallet'] != 0) {
                            $debit_amt = get_gigs_currency($value['debit_wallet'], $value['currency_code'], $this->post('user_currency'));
                            $debit += round($debit_amt, 2);
                        }
                    }
                }

                $currency_rate = get_gigs_currency($user_wallet['wallet_amt'], $user_info->currency_code, $this->post('user_currency'));
            
                $this->db->where('token', $token)->update('wallet_table', ['currency_code' => $this->post('user_currency'), 'wallet_amt' => $currency_rate]);
            }

            /*
             * Currency Update End
             */



         
			
			$prodata = $this->db->where('id',$this->user_id)->from('providers')->get()->row_array();
			if($prodata['commercial_verify'] == 1) {
				$validate_commercial_verify = 1;
			} else {
				$validate_commercial_verify = 2;
			}
			
			if (!empty($user_data['subcategory']) && !empty($user_data['account_holder_name']) && !empty($user_data['account_number']) && !empty($user_data['account_iban'])) {

                if (!empty($_FILES['profile_img'])) {

                    $config['upload_path'] = FCPATH . 'uploads/profile_img';
                    $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
                    $new_name = time() . 'user';
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('profile_img')) {
                        $upload_data = $this->upload->display_errors();
                        $user_data['profile_img'] = '';
                        $profile_img = $upload_data;
                    } else {
                        $upload_data = $this->upload->data();
                        $upload_url = 'uploads/profile_img/';
                        $user_data['profile_img'] = 'uploads/profile_img/' . $upload_data['file_name'];
                        $this->image_resize(200, 200, $user_data['profile_img'], $upload_data['file_name'], $upload_url);
                    }
                } else {
                    
                }
				
				
				if($validate_commercial_verify == 1 && empty($user_data['commercial_reg_image'])) {
					$response_code = '500';
                    $response_message = 'Commercial Register field is required';
                    $data = new stdClass();
					$result = $this->data_format($response_code, $response_message, $data);
					$this->response($result, REST_Controller::HTTP_OK);
				}
								
				if (isset($_FILES) && isset($_FILES['commercial_reg_image']['name']) && !empty($_FILES['commercial_reg_image']['name'])) {

                    $config['upload_path'] = FCPATH . 'uploads/commercial_reg_image';
                    $config['allowed_types'] = 'jpeg|jpg|png|gif|pdf|JPEG|JPG|PNG|GIF|PDF';
                    $new_name = time() . '_commercial_reg';
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('commercial_reg_image')) { 
                        $upload_data = $this->upload->display_errors();
                        $user_data['commercial_reg_image'] = '';
                        $commercial_reg_image = $upload_data;
						$response_code = '500';
						$response_message = 'Commercial Register Input - Invalid File Format';
						$data = new stdClass();
						$result = $this->data_format($response_code, $response_message, $data);
						$this->response($result, REST_Controller::HTTP_OK);						
                    } else {
                        $upload_data = $this->upload->data();
                        $upload_url = 'uploads/commercial_reg_image/';
                        $user_data['commercial_reg_image'] = 'uploads/commercial_reg_image/' . $upload_data['file_name'];
					}
                } else {
                    
                }
				
				if(empty($user_data['profile_img'])) {
					$user_data['profile_img'] = ($prodata['profile_img'])?$prodata['profile_img']:'';
				}
				
				if($validate_commercial_verify == 2 && empty($user_data['commercial_reg_image']) && empty($_FILES['commercial_reg_image']['name'])) {
					$user_data['commercial_reg_image'] = $prodata['commercial_reg_image'];
				}
				
				if(!empty($user_data['allow_rewards']) && $user_data['allow_rewards'] == 1){
					$user_data['allow_rewards'] = 1;
					$user_data['booking_reward_count'] = $user_data['booking_reward_count'];  
				} else {
					$user_data['allow_rewards'] = 0;
					$user_data['booking_reward_count'] = 0;
				}
				
				if(!empty($user_data['dob'])){
					$user_data['dob']=date('Y-m-d',strtotime($user_data['dob']));
				}else{
					$user_data['dob']=NULL;
				}
				if(!empty($user_data['homeservice_fee'])){
					$user_data['homeservice_fee'] = $user_data['homeservice_fee'];  
				} else {
					$user_data['homeservice_fee'] = 0; 
				}
				if(!empty($user_data['homeservice_arrival'])){
					$user_data['homeservice_arrival'] = $user_data['homeservice_arrival'];  
				} else {
					$user_data['homeservice_arrival'] = 40;
				}
				$user_data['updated_at'] = date("Y-m-d H:i:s");
				
				
				
				if(!empty($user_data['account_holder_name'])){
					$user_data['account_holder_name'] = $user_data['account_holder_name'];
				}
				if(!empty($user_data['account_number'])){
					$user_data['account_number'] = $user_data['account_number'];
				}
				 if(!empty($user_data['account_iban'])){
					$user_data['account_iban'] = $user_data['account_iban'];
				}
				if(!empty($user_data['bank_name'])){
					$user_data['bank_name'] = $user_data['bank_name'];
				}
				if(!empty($user_data['bank_address'])){
					$user_data['bank_address'] = $user_data['bank_address'];
				}
				if(!empty($user_data['sort_code'])){
					$user_data['sort_code'] = $user_data['sort_code'];
				}
				if(!empty($user_data['routing_number'])){
					$user_data['routing_number'] = $user_data['routing_number'];
				}
				 if(!empty($user_data['account_ifsc'])){
					$user_data['account_ifsc'] = $user_data['account_ifsc'];
				}
				
                $WHERE = array('id' => $this->user_id);
				$pid = $this->user_id;
                unset($this->user_id);
                unset($user_data['user_currency']);
				
				if(!empty($user_data['address'])){
					$user_data['address']=$user_data['address'];
				}
				if(!empty($user_data['state_id'])){
					$user_data['state_id']=$user_data['state_id'];
				}
				if(!empty($user_data['city_id'])){
					$user_data['city_id']=$user_data['city_id'];
				}
				if(!empty($_POST['country_id'])){
					$user_data['country_id']=$user_data['country_id'];
				} 
				if(!empty($user_data['pincode'])){
					$user_data['pincode']=$user_data['pincode'];
				}
				$address_data = array('address'=>$user_data['address'], 'country_id'=>$user_data['country_id'], 'state_id'=>$user_data['state_id'], 'city_id'=>$user_data['city_id'], 'pincode'=>$user_data['pincode']);
				unset($user_data['address'], $user_data['country_id'], $user_data['state_id'], $user_data['city_id'], $user_data['pincode']);


                $result = $this->admin->update_data('providers', $user_data, $WHERE);
				if($result){
					//check address
					$provider_count=$this->db->where('provider_id', $pid)->count_all_results('provider_address');
					if (!empty($provider_count)) {	
						$address_data['updated_at'] = date("Y-m-d H:i:s");
						$this->admin->update_data('provider_address', $address_data, ['provider_id'=>$pid]);
					} else {
						$address_data['provider_id'] = $pid;
						$address_data['created_at'] = date("Y-m-d H:i:s");
						$address_data['updated_at'] = date("Y-m-d H:i:s");
						$this->db->insert('provider_address', $address_data);
					}
				}

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Profile updated successfully';
                    $data = $this->api->profile(array('user_id' => $WHERE['id']));
					
					//send notification
					$provider_data = $this->db->where('id',$pid)->from('providers')->get()->row_array();
					if($provider_data['commercial_verify'] == 1) {
						$admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
						if(empty($admin['token'])){
							$sender=$this->api->getToken(14,$admin['user_id']);
						}else{
							$sender=$admin['token'];
						}
						$receiver_token=$this->api_token;
						$msg=strtolower('we will process your retraction in less than 24 hours');
						$this->api->insert_notification($sender,$receiver_token,$msg);
					}		
										
					
                } else {
                    $response_code = '200';
                    $response_message = 'Provider service failed';
                    $data = new stdClass();
                }

                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '500';
                $response_message = 'Inputs field missing';
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function subcategory_services_post() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $inputs = array();
            $data = array();
            $inputs = $this->post();
            $inputs['page'] = (!empty($inputs['page'])) ? $inputs['page'] : 1;

            $response = $this->api->subcategory_services($inputs);

            if (!empty($response['total_pages'])) {

                $response_code = '200';
                $response_message = 'All Service';

                if ($response['total_pages'] < $response['current_page']) {
                    $response_code = '500';
                    $response_message = 'Invalid Page';
                }

                $data = $response;
            } else {

                $response_code = '200';
                $response_message = 'No Records found';
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function subscription_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $data['my_subscribe'] = $my_subscribe = $this->api->get_my_subscription($this->user_id,'provider');
            $result = $this->api->subscription();
            if (!empty($result)) {
                $provider_currency = get_api_provider_currency($this->user_id);
                $ProviderCurrency = $provider_currency['user_currency_code'];
                foreach ($result as $details) {
                    if($my_subscribe['subscription_id'] == $details['id']){
                        $res['subscription'] = 1;
                    }else{
                        $res['subscription'] = 0;
                    }
                
                $fee=(!empty($ProviderCurrency) && $details['currency_code'] != '') ? get_gigs_currency($details['fee'], $details['currency_code'], $ProviderCurrency) : $details['fee'];

                    $res['id'] = $details['id'];
                    $res['subscription_name'] = $details['subscription_name'];
                    $res['fee'] =  (string) $fee;
                    $res['currency_code'] = $ProviderCurrency;
                    $res['currency'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                    $res['duration'] = $details['duration'];
                    $res['fee_description'] = $details['fee_description'];
                    $res['status'] = $details['status'];
					
					$url = base_url()."api/payment/payments/";
					$data['url'] = $url;

                    $results[] = $res;
                }
            }

            if ($result) {
                $response_code = '200';
                $response_message = 'Subscription listed successfully';
                $data['subscription_list'] = $results;
                
                $data['my_subscribe_list'] = $this->api->get_my_subscription_list($this->user_id,'provider');
                if(!empty($my_subscribe)){
                    $subscription_fee=$this->db->where('id',$my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
                    $data['my_subscribe']['subscription_name'] = $subscription_fee['subscription_name'];
                    $data['my_subscribe']['fee'] = $subscription_fee['fee'];
                    $data['my_subscribe']['currency_code'] = currency_code_sign($subscription_fee['currency_code']);
                }else{
                    $data['my_subscribe']['subscription_name']='';
                    $data['my_subscribe']['fee']='';
                    $data['my_subscribe']['currency_code']='';
                }
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function subscription_success_post() {

        $user_data = array();
        $user_data = getallheaders(); // Get Header Data
        $user_post_data = $this->post();
        $user_data = array_merge($user_data, $user_post_data);

        $token = (!empty($user_data['token'])) ? $user_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_data['Token'])) ? $user_data['Token'] : '';
        }

        if (!empty($token)) {

            $result = $this->api->token_is_valid_provider($token);

            if ($result) {
            	if($user_data['paytype'] == 'stripe' || $user_data['paytype'] == 'razorpay' || $user_data['paytype'] == 'paypal' || $user_data['paytype'] == 'paystack') {
	                if (!empty($user_data['subscription_id']) && !empty($user_data['transaction_id'])) {
	                    $user_data['token'] = $token;
	                    $result = $this->api->subscription_success($user_data);

	                    $user_id = $this->api->get_user_id_using_token($token);
	                    $this->db->select('subscription_id');
	                    $this->db->where('subscriber_id', $user_id);
	                    $subscription = $this->db->get('subscription_details')->row_array();

	                    if (!empty($subscription)) {
	                        $id = $subscription['subscription_id'];
	                        $this->db->select('id,subscription_name');
	                        $this->db->where('id', $id);
	                        $subscription = $this->db->get('subscription_fee')->row_array();
	                        $subscribed_user = 1;
	                        $subscribed_msg = $subscription['subscription_name'];
	                    } else {
	                        $subscribed_user = 0;
	                        $subscribed_msg = 'Free';
	                    }

	                    if (!empty($result)) {

	                        $res['id'] = $result['id'];
	                        $res['subscription_id'] = $result['subscription_id'];
	                        $res['subscriber_id'] = $result['subscriber_id'];
	                        $res['subscription_date'] = $result['subscription_date'];
	                        $res['expiry_date_time'] = $result['expiry_date_time'];
	                        $res['type'] = $result['type'];
	                        $res['is_subscribed'] = "$subscribed_user";

	                        $response_code = '200';
	                        $response_message = 'Subscribed Successfully';
	                        $data = $res;
	                    } else {
	                        $response_code = '201';
	                        $response_message = 'Something went wrong. Please try again later.';
	                    }
	                } else {
	                    $response_code = '201';
	                    $response_message = 'Input field missing';
	                }
	            } elseif($user_data['paytype'] == '') {
		        	if (!empty($user_data['subscription_id']) && !empty($user_data['transaction_id'])) {
		                $user_data['token'] = $token;
	                    $result = $this->api->subscription_success($user_data);
	                    $user_id = $this->api->get_user_id_using_token($token);
	                    $this->db->select('subscription_id');
	                    $this->db->where('subscriber_id', $user_id);
	                    $subscription = $this->db->get('subscription_details')->row_array();

	                    if (!empty($subscription)) {
	                        $id = $subscription['subscription_id'];
	                        $this->db->select('id,subscription_name');
	                        $this->db->where('id', $id);
	                        $subscription = $this->db->get('subscription_fee')->row_array();
	                        $subscribed_user = 1;
	                        $subscribed_msg = $subscription['subscription_name'];
	                    } else {
	                        $subscribed_user = 0;
	                        $subscribed_msg = 'Free';
	                    }

	                    if (!empty($result)) {
	                        $res['id'] = $result['id'];
	                        $res['subscription_id'] = $result['subscription_id'];
	                        $res['subscriber_id'] = $result['subscriber_id'];
	                        $res['subscription_date'] = $result['subscription_date'];
	                        $res['expiry_date_time'] = $result['expiry_date_time'];
	                        $res['type'] = $result['type'];
	                        $res['is_subscribed'] = "$subscribed_user";

	                        $response_code = '200';
	                        $response_message = 'Subscribed Successfully';
	                        $data = $res;
	                    } else {
	                        $response_code = '201';
	                        $response_message = 'Something went wrong. Please try again later.';
	                    }
	                } else {
	                    $response_code = '201';
	                    $response_message = 'Input field missing';
	                }
		        } 
            } else {
                $response_code = '202';
                $response_message = 'Invalid user';
            }
        } else {
            $response_code = '201';
            $response_message = 'User token missing';
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function subscription_success_old_post() {

        $user_data = array();
        $user_data = getallheaders(); // Get Header Data
        $user_post_data = $this->post();
        $user_data = array_merge($user_data, $user_post_data);

        $token = (!empty($user_data['token'])) ? $user_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_data['Token'])) ? $user_data['Token'] : '';
        }

        $data = array();
        $response_code = '500';
        $response_message = 'Validation error';

        if (!empty($token)) {

            $result = $this->api->token_is_valid_provider($token);

            if ($result) {

                if (!empty($user_data['subscription_id']) && !empty($user_data['transaction_id'])) {
                    $user_data['token'] = $token;
                    $result = $this->api->subscription_success($user_data);

                    $user_id = $this->api->get_user_id_using_token($token);

                    $this->db->select('subscription_id');

                    $this->db->where('subscriber_id', $user_id);

                    $subscription = $this->db->get('subscription_details')->row_array();

                    if (!empty($subscription)) {

                        $id = $subscription['subscription_id'];

                        $this->db->select('id,subscription_name');

                        $this->db->where('id', $id);

                        $subscription = $this->db->get('subscription_fee')->row_array();

                        $subscribed_user = 1;

                        $subscribed_msg = $subscription['subscription_name'];
                    } else {

                        $subscribed_user = 0;

                        $subscribed_msg = 'Free';
                    }


                    if (!empty($result)) {

                        $res['id'] = $result['id'];
                        $res['subscription_id'] = $result['subscription_id'];
                        $res['subscriber_id'] = $result['subscriber_id'];
                        $res['subscription_date'] = $result['subscription_date'];
                        $res['expiry_date_time'] = $result['expiry_date_time'];
                        $res['type'] = $result['type'];
                        $res['is_subscribed'] = "$subscribed_user";




                        $response_code = '200';
                        $response_message = 'Subscribed Successfully';
                        $data = $res;
                    } else {
                        $response_code = '201';
                        $response_message = 'Something went wrong. Please try again later.';
                    }
                } else {

                    $response_code = '201';
                    $response_message = 'Input field missing';
                }
            } else {

                $response_code = '202';
                $response_message = 'Invalid user';
            }
        } else {

            $response_code = '201';
            $response_message = 'User token missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function profile_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $user_data['token'] = $this->api_token;
            $user_data['user_id'] = $this->user_id;
            $result = $this->api->profile($user_data);

            if ($result) {

                if (!empty($result['currency_code'])) {
                    $result['currency_id'] = $this->db->where('currency_code', $result['currency_code'])->get('currency_rate')->row()->id;
                } else {
                    $result['currency_id'] = "";
                }

                $response_code = '200';
                $response_message = 'Profile found';
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
            }
            $data = $result;
            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function add_service_post() {



        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '128M');


        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();
          
            if (!empty($user_data['shop_id']) && ($user_data['staff_id'] != '') && !empty($user_data['duration']) && !empty($user_data['service_title']) && !empty($user_data['category']) && !empty($user_data['subcategory']) && !empty($user_data['service_amount']) && !empty($user_data['service_offered']) && !empty($user_data['about'])) {

          

                $inputs = array();

                $config["upload_path"] = './uploads/services/';
                $config["allowed_types"] = '*';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                $service_image = array();
                $thumb_image = array();
                $mobile_image = array();



                if (isset($_FILES["images"]["name"])) {
                    $count = count($_FILES["images"]['name']);

					
                    if ($count >= 3) {					
                        for ($i = 0; $i < $count; $i++) {
                            $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$i];
                            $_FILES["file"]["type"] = $_FILES["images"]["type"][$i];
                            $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$i];
                            $_FILES["file"]["error"] = $_FILES["images"]["error"][$i];
                            $_FILES["file"]["size"] = $_FILES["images"]["size"][$i];
                            if ($this->upload->do_upload('file')) {
                                $data = $this->upload->data();
                                $image_url = 'uploads/services/' . $data["file_name"];
                                $upload_url = 'uploads/services/';
                                $service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
                                $service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
                                $thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
                                $mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
                            }
                        }
                    } else {
                        $data = new stdClass();
                        $response_code = '500';
                        $response_message = 'Minimum 3 images required';

                        $result = $this->data_format($response_code, $response_message, $data);
                        $this->response($result, REST_Controller::HTTP_OK);
                    }
                }
            
				$provider_currency = get_api_provider_currency($this->user_id);
				$ProviderCurrency = $provider_currency['user_currency_code'];
			
                $inputs['user_id'] = $this->user_id;

                $inputs['service_title'] = $this->post('service_title');
            	$inputs['currency_code'] = $ProviderCurrency;
                $inputs['category'] = $this->post('category');
                $inputs['subcategory'] = $this->post('subcategory');
                
                $inputs['service_amount'] = $this->post('service_amount');
                $inputs['service_image'] = (!empty($service_image)) ? $service_image[0] : '';
				
				$service = implode(',', $this->post('service_offered'));
				$serviceoffered = json_encode(array($service));
                $inputs['service_offered'] = $this->post('service_offered');
                $inputs['about'] = $this->post('about');

                $inputs['shop_id'] = $this->post('shop_id');
                $inputs['staff_id'] = $this->post('staff_id');
                $inputs['duration'] = $this->post('duration');
                $inputs['service_for'] = $this->post('service_for');
                $inputs['service_for_userid'] = $this->post('service_for_userid');

                $inputs['created_at'] = date('Y-m-d H:i:s');
                $inputs['updated_at'] = date('Y-m-d H:i:s');

				
				if($this->post('autoschedule') == 1){
					if($this->post('autoschedule_days') > 0 || $this->post('autoschedule_session') > 0){
						$inputs['autoschedule'] = $this->post('autoschedule');
						$inputs['autoschedule_days']  = $this->post('autoschedule_days');
						$inputs['autoschedule_session']  = $this->post('autoschedule_session');
					} else {
                        $data = new stdClass();
                        $response_code = '500';
                        $response_message = 'Incorrect Value of Autoschedule Days or Autoschedule Session. Value must be greater than 0';
                        $result = $this->data_format($response_code, $response_message, $data);
                        $this->response($result, REST_Controller::HTTP_OK);
                    }
					
				} else {
					$inputs['autoschedule'] = 0;
					$inputs['autoschedule_days']  = 0;
					$inputs['autoschedule_session']  = 0;
				}
				
				$shpqry = $this->db->select("shop_location,shop_latitude, shop_longitude")->where('provider_id',$this->user_id)->where('id',$inputs['shop_id'])->get('shops')->row_array(); 
				
				$inputs['service_location']  = $shpqry['shop_location'];
                $inputs['service_latitude']  = $shpqry['shop_latitude'];
                $inputs['service_longitude'] = $shpqry['shop_longitude'];
				
				

                $result = $this->api->create_service($inputs);
			

                $temp = count($service_image); //counting number of row's
                $service_image = $service_image;
                $service_details_image = (!empty($service_details_image)) ? $service_details_image : '';
                $thumb_image = $thumb_image;
                $mobile_image = $mobile_image;
                $service_id = $result;



                for ($j = 0; $j < $temp; $j++) {
                    $image = array(
                        'service_id' => $service_id,
                        'service_image' => $service_image[$j],
                        'service_details_image' => $service_details_image[$j],
                        'thumb_image' => $thumb_image[$j],
                        'mobile_image' => $mobile_image[$j]);


                    $serviceimage = $this->api->insert_serviceimage($image);
                }
				
				$seroffer= json_decode($user_data['service_offered'], true); 
				$addiserv= json_decode($user_data['additional_services'], true);
				unset($user_data['service_offered']);
				unset($user_data['additional_services']);
				
				if (count($seroffer) > 0) {
					foreach ($seroffer as $key => $value) {
						$service_data = array(
							'service_id' => $result,
							'service_offered' => $value);
						$this->db->insert('service_offered', $service_data);
					}
				}
				
				if($result){
					//Insert Additional Services
					if (!empty($addiserv)) 
					{						
						$ai_data = [];
						foreach ($addiserv as $val) 
						{
							$ai_data[] = ['service_id'=>$result, 'provider_id'=>$this->user_id, 'service_name'=>$val['name'], 'duration'=>$val['duration'], 'amount'=>$val['amount'], 'created_at' => date('Y-m-d H:i:s')];
						}
						if (!empty($ai_data)) 
						{
							//insert batch
							$this->db->insert_batch('additional_services', $ai_data);
						}
					}
				}



                if ($result) {
                    $response_code = '200';
                    $response_message = 'Service added successfully';
                } else {
                    $response_code = '500';
                    $response_message = 'Add service failed';
                }
                $data = new stdClass();
                $res = $this->data_format($response_code, $response_message, $data);

                $this->response($res, REST_Controller::HTTP_OK);
            } else {
                $response_code = '500';
                $response_message = 'Add service failed, required fields empty';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function update_service_post() {

        ini_set('post_max_size', '100M');
        ini_set('upload_max_filesize', '100M');
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '128M');

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
			 $provider_id = $this->user_id;
			 $provider_currency = get_api_provider_currency($provider_id);
			 $ProviderCurrency = $provider_currency['user_currency_code'];
            $user_data = $this->post();

            
			if (!empty($user_data['shop_id']) && ($user_data['staff_id'] != '') && !empty($user_data['duration']) && !empty($user_data['service_title']) && !empty($user_data['category']) && !empty($user_data['subcategory']) && !empty($user_data['service_amount']) && !empty($user_data['service_offered']) && !empty($user_data['about'])  && !empty($user_data['id'])) {

                $inputs = array();

                $config["upload_path"] = './uploads/services/';
                $config["allowed_types"] = '*';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                $service_image = array();
                $thumb_image = array();
                $mobile_image = array();
				$service_details_image = array();
				
                if (isset($_FILES["images"]["name"])) {
                    $count = count($_FILES["images"]['name']);

					
                 			
                        for ($i = 0; $i < $count; $i++) {
                            $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$i];
                            $_FILES["file"]["type"] = $_FILES["images"]["type"][$i];
                            $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$i];
                            $_FILES["file"]["error"] = $_FILES["images"]["error"][$i];
                            $_FILES["file"]["size"] = $_FILES["images"]["size"][$i];
							
                            if ($this->upload->do_upload('file')) { 
                                $data = $this->upload->data();
                                $image_url = 'uploads/services/' . $data["file_name"];
                                $upload_url = 'uploads/services/';
                                $service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
                                $service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
                                $thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
                                $mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
                            }
                        }
                        $inputs['service_image'] = implode(',', $service_image);
                        $inputs['service_details_image'] = implode(',', $service_details_image);
                        $inputs['thumb_image'] = implode(',', $thumb_image);
                        $inputs['mobile_image'] = implode(',', $mobile_image);
                  
                }
			
				
				$inputs['service_title'] = $this->post('service_title');
            	$inputs['currency_code'] = $ProviderCurrency;
                $inputs['category'] = $this->post('category');
                $inputs['subcategory'] = $this->post('subcategory');
                
                $inputs['service_amount'] = $this->post('service_amount');
                $inputs['service_image'] = (!empty($service_image)) ? $service_image[0] : '';
				
				$service = implode(',', $this->post('service_offered'));
				$serviceoffered = json_encode(array($service));
				$inputs['service_offered'] = $this->post('service_offered');
                $inputs['about'] = $this->post('about');

                $inputs['shop_id'] = $this->post('shop_id');
                $inputs['staff_id'] = $this->post('staff_id');
                $inputs['duration'] = $this->post('duration');
                $inputs['service_for'] = $this->post('service_for');
                $inputs['service_for_userid'] = $this->post('service_for_userid');
				
				
				if($this->post('autoschedule') == 1){
					if($this->post('autoschedule_days') > 0 || $this->post('autoschedule_session') > 0){
						$inputs['autoschedule'] = $this->post('autoschedule');
						$inputs['autoschedule_days']  = $this->post('autoschedule_days');
						$inputs['autoschedule_session']  = $this->post('autoschedule_session');
					} else {
                        $data = new stdClass();
                        $response_code = '500';
                        $response_message = 'Incorrect Value of Autoschedule Days or Autoschedule Session. Value must be greater than 0';
                        $result = $this->data_format($response_code, $response_message, $data);
                        $this->response($result, REST_Controller::HTTP_OK);
                    }
					
				} else {
					$inputs['autoschedule'] = 0;
					$inputs['autoschedule_days']  = 0;
					$inputs['autoschedule_session']  = 0;
				}
												

                $inputs['updated_at'] = date('Y-m-d H:i:s');
                $WHERE = array('id' => $user_data['id']);
				
				
				$shpqry = $this->db->select("shop_location,shop_latitude, shop_longitude")->where('provider_id',$this->user_id)->where('id',$inputs['shop_id'])->get('shops')->row_array(); 
				
				$inputs['service_location']  = $shpqry['shop_location'];
                $inputs['service_latitude']  = $shpqry['shop_latitude'];
                $inputs['service_longitude'] = $shpqry['shop_longitude'];
								
                $result = $this->api->update_service($inputs, $WHERE);
				
				$seroffer= json_decode($user_data['service_offered'], true); 
				$addiserv= json_decode($user_data['additional_services'], true);
				unset($user_data['service_offered']);
				unset($user_data['additional_services']);
				
				if (count($seroffer) > 0) {
					$this->db->where('service_id', $user_data['id'])->delete('service_offered');
					foreach ($seroffer as $key => $value) {
						$service_data = array(
							'service_id' =>$user_data['id'],
							'service_offered' => $value);
						$this->db->insert('service_offered', $service_data);
					}
				}
				
				if($result){
					//Insert Additional Services
					if (!empty($addiserv)) 
					{					
						$this->db->where('service_id', $user_data['id'])->delete('additional_services');
						$ai_data = [];
						foreach ($addiserv as $val) 
						{
							$ai_data = array('service_id'=>$user_data['id'], 'provider_id'=>$this->user_id, 'service_name'=>$val['name'], 'duration'=>$val['duration'], 'amount'=>$val['amount'], 'created_at' => date('Y-m-d H:i:s'));
												
							$this->db->insert('additional_services', $ai_data);							
							$inserid = $this->db->insert_id();
							if($val['addiservice_id'] > 0){
								$idupdate['id'] = $val['addiservice_id'];
								$idupdate['updated_at'] = date('Y-m-d H:i:s');
								$this->db->where('id', $inserid);
								$this->db->update('additional_services', $idupdate);
							} 
						}
						
					}
				}


                if (is_array($service_image) && count($service_image) > 0) {
                    $temp = count($service_image);
                    $service_image = $service_image;
                    $service_details_image = $service_details_image;
                    $thumb_image = $thumb_image;
                    $mobile_image = $mobile_image;
                    $service_id = $user_data['id'];



                    for ($i = 0; $i < $temp; $i++) {
                        $image = array(
                            'service_id' => $service_id,
                            'service_image' => $service_image[$i],
                            'service_details_image' => $service_details_image[$i],
                            'thumb_image' => $thumb_image[$i],
                            'mobile_image' => $mobile_image[$i]
                        );
                        $serviceimage = $this->api->insert_serviceimage($image);
                    }
                }

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Service updated successfully';
                } else {
                    $response_code = '200';
                    $response_message = 'Update service failed';
                }
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '200';
                $response_message = 'Update service failed, required fields value missing';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function delete_service_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();
            if (!empty($user_data['id'])) {
                $inputs['status'] = '0';
                $WHERE = array('id' => $user_data['id']);
                $result = $this->api->update_service($inputs, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Service deleted successfully';
                } else {
                    $response_code = '200';
                    $response_message = 'Service delete failed';
                }
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '200';
                $response_message = 'Service delete failed';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function delete_serviceimage_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();

            if (!empty($user_data['id']) && !empty($user_data['service_id'])) {
                $service_id = $user_data['service_id'];
                $get_serviceimg = $this->api->get_serviceimg($service_id);

                if ($get_serviceimg > 1) {
                    $inputs['status'] = '0';
                    $WHERE = array('id' => $user_data['id'], 'service_id' => $user_data['service_id']);
                    $result = $this->api->delete_service($inputs, $WHERE);

                    if ($result) {
                        $response_code = '200';
                        $response_message = 'Service image deleted successfully';
                    } else {
                        $response_code = '500';
                        $response_message = 'Service image deletion failed';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'You have only one service image, So you cant delete';
                }
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '500';
                $response_message = 'Input field missing';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
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

    public function subscription_payment_post() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $params = $this->post();
            if (!empty($params['amount']) && !empty($params['tokenid']) && !empty($params['description']) && !empty($params['subscription_id']) && $params['amount'] > 0) {
                $charges_array = array();
                $amount = $params['amount'];
                $amount = ($amount * 100);
                $charges_array['amount'] = $amount;
                $charges_array['currency'] = settings('currency');
                $charges_array['description'] = $params['description'];
                $charges_array['source'] = $params['tokenid'];

                $result = $this->stripe->stripe_charges($charges_array);

                $result = json_decode($result, true);

                if (empty($result['error'])) {
                    $data['transaction_id'] = $result['id'];
                    $data['payment_details'] = json_encode($result);

                    $response_code = '200';
                    $response_message = 'Stripe payment success';
                } else {
                    $response_code = '200';
                    $response_message = 'Stripe payment issue';
                    $data['error'] = $result['error'];
                }
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '200';
                $response_message = 'Stripe payment issue';
                $data['error'] = $result['error'];

                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function data_format($response_code, $response_message, $data) {
        $final_result = array();
        $response = array();
        $response['response_code'] = $response_code;
        $response['response_message'] = $response_message;

        if (!empty($data)) {

            $data = $data;
        } else {

            $data = $data;
        }

        $final_result['response'] = $response;
        $final_result['data'] = $data;

        return $final_result;
    }

    public function edit_service_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $inputs = array();
                $inputs['id'] = $this->get('id');
                $token=$this->api_token;
                $user_id = $this->api->get_user_id_using_token($token);
                $inputs['user_id']=$user_id;
                

                $service_id = $this->api->get_service_id($inputs['id']);

                if (!empty($service_id)) {
                    $service_details = $this->api->get_service_info($inputs);

                    if (!empty($service_details)) {
                        $response_code = '200';
                        $response_message = "Service Details";
                        $data = $service_details;
                    }
                } else {
                    $response_code = '200';
                    $response_message = "No Details found";

                    $data = [];
                }
            } else {
                $response_code = '500';
                $response_message = "Service id missing";
                $data = [];
            }



            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function add_availability_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();

            if (!empty($user_data['availability'])) {

                $user_data['provider_id'] = $this->user_id;
                $check_provider = $this->api->provider_hours($this->user_id);

                if (empty($check_provider)) {
                    $result = $this->api->insert_businesshours($user_data);

                    if ($result) {
                        $response_code = '200';
                        $response_message = 'Business hours added successfully';
                    } else {
                        $response_code = '200';
                        $response_message = 'Business hours added failed';
                    }
                    $data = new stdClass();
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                } else {
                    $response_code = '200';
                    $response_message = 'Business hours already exists';
                }
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '500';
                $response_message = 'Input field missing';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function update_availability_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();

            if (!empty($user_data['availability'])) {
                $provider_id = $this->user_id;
                $check_provider = $this->api->provider_hours($this->user_id);


                if (empty($check_provider)) {

                    $user_data['provider_id'] = $this->user_id;
                    $result = $this->api->insert_businesshours($user_data);

                    if ($result) {
                        $response_code = '200';
                        $response_message = 'Business hours added successfully';
                    } else {
                        $response_code = '200';
                        $response_message = 'Business hours added failed';
                    }
                    $data = new stdClass();
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                } else {

                    $provider_id = $this->user_id;

                    $WHERE = array('provider_id' => $provider_id);
                    $result = $this->api->update_availability($user_data, $WHERE);

                    if ($result) {
                        $response_code = '200';
                        $response_message = 'Availability updated successfully';
                    } else {
                        $response_code = '200';
                        $response_message = 'Availability updation failed';
                    }
                    $data = new stdClass();
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                }
            } else {
                $response_code = '200';
                $response_message = 'Input field missing';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function availability_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {


            $inputs = array();
            $provider_id = $this->user_id;
            $result = $this->api->get_availability($provider_id);

            if (!empty($result)) {

                $response_code = '200';
                $response_message = "Availability Details";
                $data = $result;
            } else {
                $res['id'] = "";
                $res['provider_id'] = "";
                $res['availability'] = "";

                $response_code = '200';
                $response_message = "No Details found";
                $data = $res;
            }




            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function logout_provider_post() {
        $user_data = array();
        $user_data = getallheaders();
        $user_post_data = $this->post();
        $user_data = array_merge($user_data, $user_post_data);
        $token = $this->api_token;

        if (empty($token)) {
            $token = (!empty($header['Token'])) ? $header['Token'] : '';
        }
        $user_data['token'] = $token;


        $data = array();
        $response_code = '-1';
        $response_message = 'validation error';

        if (!empty($user_data['token'])) {
            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {



                $result = $this->api->logout_provider($user_data['token'], $user_data['device_type'], $user_data['device_id']);

                if ($result) {

                    $response_code = '200';
                    $response_message = 'Logout successfully';
                } else {

                    $response_code = '202';
                    $response_message = 'Invalid user token';
                }
            } else {

                $response_code = '201';
                $response_message = 'Required input is missing';
            }
        } else {

            $response_code = '201';
            $response_message = 'user token is missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function user_signin_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();


            if (!empty($user_data['mobileno']) && !empty($user_data['otp']) && !empty($user_data['country_code'])) {


                $is_available_mobile = $this->api->check_user_mobileno($user_data);
                $is_available_provider = $this->api->check_mobile_no($user_data);
                $is_available_inactive = $this->api->check_delete_users($user_data);
               
                if($is_available_inactive == 0) {
                    if ($is_available_provider == 0) {
                        if ($is_available_mobile == 1) {

                            $check_data['mobile_number'] = $user_data['mobileno'];
                            $check_data['otp'] = $user_data['otp'];
                            $check_data['country_code'] = $user_data['country_code'];

                            $check = $this->api->check_otp($check_data);

                            if (is_array($check) && !empty($check)) {
                                $mobile_number = $user_data['mobileno'];
                                $user_details = $this->api->get_user_details($mobile_number, $user_data);
                            }

                            if (!empty($user_details)) {
                                $response_code = '200';
                                $response_message = 'LoggedIn Successfully';
                                $data['user_details'] = $user_details;
                            } else {
                                $response_code = '202';
                                $response_message = 'Login failed, Invalid OTP or mobile number';
                            }
                        } else {
                            $response_code = '500';
                            $response_message = 'Mobile number does not exits';
                        }
                    } else {
                        $response_code = '500';
                        $response_message = 'This number is already registered as Provider.';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'This number is not active.';
                }
                }else if (!empty($user_data['email']) && !empty($user_data['password'])) {
                    $is_available_email = $this->api->check_user_email($user_data);
				
				    if ($is_available_email == 1) {
					    $check = $this->api->check_user_email($user_data);
					
                        if ($check  == 1) {
                            $email = $user_data['email'];
                            $password = md5($user_data['password']);
                            $user_details = $this->api->get_user_detailsby_email($email,$password,$user_data);
							
                        }
                        if (!empty($user_details)) {
							
                            $response_code = '200';
                            $response_message = 'LoggedIn Successfully';
                            $data['user_details'] = $user_details;
                        } else {
                            $response_code = '202';
                            $response_message = 'Login failed, Invalid Email Id';
                        }
				}else{
					$response_code = '500';
					$response_message = 'Email id does not exists';
				}
            } else {
                $response_code = '500';
                $response_message = 'Inputs field missing';
            }

            if(empty($data)) {
                $data =new stdClass();
            }
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function generate_userotp_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();

            if ($user_data['usertype'] == 2) {
                if (!empty($user_data['mobileno']) && !empty($user_data['email']) && !empty($user_data['country_code']) && !empty($user_data['usertype'])) {

                    $is_available = $this->api->check_user_email($user_data);
                    $is_available_email = $this->api->check_email($user_data);
                    $is_available_mobile = $this->api->check_mobile_no($user_data);
                    $is_available_mobileno = $this->api->check_user_mobileno($user_data);


                    if ($is_available == 0 && $is_available_email == 0) {
                        if ($is_available_mobile == 0 && $is_available_mobileno == 0) {
                            $default_otp = settingValue('default_otp');
                            if ($default_otp == 1) {
                                $otp = '1234';
                            } else {
                                $otp = rand(1000, 9999);
                            }



                            $message = 'Your OTP for ' . $this->website_name . ' is ' . $otp . '';
                            $this->load->library('sms');
                            $result = $this->sms->send_message($user_data['country_code'] . $user_data['mobileno'], $message);
                            $otp_data = array(
                                'endtime' => time() + 300,
                                'mobile_number' => $user_data['mobileno'],
                                'country_code' => $user_data['country_code'],
                                'otp' => $otp
                            );
                            $save_otp = $this->api->save_otp($otp_data);
                            $response_code = '200';
                            $response_message = 'OTP send successfully';
                            $data['usertype'] = $user_data['usertype'];
                        } else {
                            $response_code = '500';
                            $response_message = 'Mobile no already exits';
                        }
                    } else {
                        $response_code = '500';
                        $response_message = 'Email id already exits';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'Inputs field missing';
                }
            } elseif ($user_data['usertype'] == 1) {
                if (!empty($user_data['mobileno']) && !empty($user_data['country_code'])) {

                    $is_available_mobile = $this->api->check_user_mobileno($user_data);


                    if ($is_available_mobile == 1) {
                        $default_otp = settingValue('default_otp');
                        if ($default_otp == 1) {
                            $otp = '1234';
                        } else {
                            $otp = rand(1000, 9999);
                        }



                        $message = 'Your OTP for ' . $this->website_name . ' is ' . $otp . '';
                        $this->load->library('sms');
                        $result = $this->sms->send_message($user_data['country_code'] . $user_data['mobileno'], $message);
                        $otp_data = array(
                            'endtime' => time() + 300,
                            'mobile_number' => $user_data['mobileno'],
                            'country_code' => $user_data['country_code'],
                            'otp' => $otp,
                            'status' => 1
                        );
                        $save_otp = $this->api->save_otp($otp_data);
                        $response_code = '200';
                        $response_message = 'OTP send successfully';
                        $data['usertype'] = $user_data['usertype'];
                    } elseif ($is_available_mobile == 0) {

                        $data['usertype'] = '2';
                        $response_code = '500';
                        $response_message = 'Mobile number does not exists';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'Inputs field missing';
                }
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function logout_post() {
        $user_data = array();
        $user_data = getallheaders();
        $user_post_data = $this->post();
        $user_data = array_merge($user_data, $user_post_data);
        $token = $this->api_token;

        if (empty($token)) {
            $token = (!empty($header['Token'])) ? $header['Token'] : '';
        }
        $user_data['token'] = $token;


        $data = new stdClass();
        $response_code = '-1';
        $response_message = 'validation error';

        if (!empty($user_data['token']) && !empty($user_data['device_type']) && !empty($user_data['deviceid'])) {


            $result = $this->api->logout($user_data['token'], $user_data['device_type'], $user_data['deviceid']);

            if ($result) {

                $response_code = '200';
                $response_message = 'Logout successfully';
            } else {

                $response_code = '202';
                $response_message = 'Invalid user token';
            }
        } else {

            $response_code = '201';
            $response_message = 'Input Field is missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function update_user_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//check need
    		$this->verifyneedparameters(['name', 'type', 'user_currency', 'email', 'country_code', 'mobileno', 'dob', 'gender', 'address', 'country_id', 'state_id', 'city_id', 'pincode'], $user_data);
    		$this->verifyRequiredParams(['name', 'type', 'email', 'country_code', 'mobileno', 'gender'], $user_data);
    		if ($user_data['user_currency']!='') 
    		{
    			$currency = $user_data['user_currency'];
                $user_data['currency_code'] = $user_data['user_currency'];
                $user_id = $this->user_id;
                $token = $this->api_token;

                $user_wallet = $this->Stripe_model->get_wallet_new($token);
                $user_info = $this->Stripe_model->get_user($token);
                $wallet_history = $this->Stripe_model->get_wallet_history_info($token, $currency);
                $credit = $debit = 0;
                if (count($wallet_history) > 0) 
                {
                    foreach ($wallet_history as $key => $value) {

                        if ($value['credit_wallet'] != 0) {

                            $credit_amt = get_gigs_currency($value['credit_wallet'], $value['currency_code'], $currency);

                            $credit += round($credit_amt, 2);
                        }
                        if ($value['debit_wallet'] != 0) {
                            $debit_amt = get_gigs_currency($value['debit_wallet'], $value['currency_code'], $currency);
                            $debit += round($debit_amt, 2);
                        }
                    }
                }
                $currency_rate = get_gigs_currency($user_wallet['wallet_amt'], $user_info->currency_code, $currency);
                $this->db->where('token', $token)->update('wallet_table', ['currency_code' => $currency, 'wallet_amt' => $currency_rate]);
    		}
    		//Profile img
    		if (!empty($_FILES['profile_img']['name'])) 
    		{
                $config['upload_path'] = FCPATH . 'uploads/profile_img';
                $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
                $new_name = time() . 'user';
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('profile_img')) 
                {
                    $upload_data = $this->upload->display_errors();
                    $user_data['profile_img'] = '';
                    $profile_img = $upload_data;
                } 
                else 
                {
                    $upload_data = $this->upload->data();
                    $upload_url = 'uploads/profile_img/';
                    $user_data['profile_img'] = 'uploads/profile_img/' . $upload_data['file_name'];
                    $this->image_resize(200, 200, $user_data['profile_img'], $upload_data['file_name'], $upload_url);
                }
            }
            if ($user_data['dob']!='') 
            {
            	$user_data['dob'] = date("Y-m-d", strtotime($user_data['dob']));
            }
            unset($user_data['user_currency']);
            $address_data = array('address'=>$user_data['address'], 'country_id'=>$user_data['country_id'], 'state_id'=>$user_data['state_id'], 'city_id'=>$user_data['city_id'], 'pincode'=>$user_data['pincode']);
        
            unset($user_data['address'], $user_data['country_id'], $user_data['state_id'], $user_data['city_id'], $user_data['pincode']);

            $result = $this->admin->update_data('users', $user_data, ['id'=>$this->users_id]);
            //check address
            $addr = $this->api->getsingletabledata('user_address', ['user_id'=>$this->users_id], '', 'id', 'asc', 'single');
            if (!empty($addr)) 
            {
            	//update
            	$this->admin->update_data('user_address', $address_data, ['user_id'=>$this->users_id]);
            }
            else
            {
            	$address_data['user_id'] = $this->users_id;
            	$address_data['created_at'] = date("Y-m-d H:i:s");
            	$address_data['updated_at'] = date("Y-m-d H:i:s");
            	$this->db->insert('user_address', $address_data);
            }
            if ($result) 
            {
                $response_code = '200';
                $response_message = 'Profile updated successfully';
                $data = $this->api->user_profile(array('user_id' => $this->users_id));
            } 
            else 
            {
                $response_code = '200';
                $response_message = 'Update failed';
                $data = new stdClass();
            }
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
    	}
    	else
    	{
    		$this->token_error();
    	}
    }

    public function update_user1_post() {

        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();

            /*
             * Currency Update Start
             */

            if (!empty($this->post('user_currency'))) {
                $currency = $this->post('user_currency');
                $user_data['currency_code'] = $user_data['user_currency'];
                $user_id = $this->user_id;
                $token = $this->api_token;

                $user_wallet = $this->Stripe_model->get_wallet_new($token);
                $user_info = $this->Stripe_model->get_user($token);
                $wallet_history = $this->Stripe_model->get_wallet_history_info($token, $currency);
                $credit = $debit = 0;


                if (count($wallet_history) > 0) {
                    foreach ($wallet_history as $key => $value) {

                        if ($value['credit_wallet'] != 0) {

                            $credit_amt = get_gigs_currency($value['credit_wallet'], $value['currency_code'], $currency);

                            $credit += round($credit_amt, 2);
                        }
                        if ($value['debit_wallet'] != 0) {
                            $debit_amt = get_gigs_currency($value['debit_wallet'], $value['currency_code'], $currency);
                            $debit += round($debit_amt, 2);
                        }
                    }
                }

                // $currency_rate = $credit - $debit;
                $currency_rate = get_gigs_currency($user_wallet['wallet_amt'], $user_info->currency_code, $currency);
                $this->db->where('token', $token)->update('wallet_table', ['currency_code' => $currency, 'wallet_amt' => $currency_rate]);
            }

            /*
             * Currency Update End
             */


            if (!empty($user_data['name']) || !empty($user_data['email']) || !empty($user_data['mobileno']) || !empty($user_data['country_code']) || !empty($_FILES['profile_img'])) {
                if (!empty($_FILES['profile_img'])) {



                    $config['upload_path'] = FCPATH . 'uploads/profile_img';
                    $config['allowed_types'] = 'jpeg|jpg|png|gif|JPEG|JPG|PNG|GIF';
                    $new_name = time() . 'user';
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);


                    if (!$this->upload->do_upload('profile_img')) {
                        $upload_data = $this->upload->display_errors();
                        $user_data['profile_img'] = '';
                        $profile_img = $upload_data;
                    } else {
                        $upload_data = $this->upload->data();
                        $upload_url = 'uploads/profile_img/';
                        $user_data['profile_img'] = 'uploads/profile_img/' . $upload_data['file_name'];
                        $this->image_resize(200, 200, $user_data['profile_img'], $upload_data['file_name'], $upload_url);
                    }
                } else {
                    
                }
                if (!empty($user_data['type'])) {
                    $WHERE = array('id' => $this->users_id);
                    unset($this->users_id);
                    unset($user_data['user_currency']);

                    $result = $this->admin->update_data('users', $user_data, $WHERE);
                    if ($result) {
                        $response_code = '200';
                        $response_message = 'Profile updated successfully';
                        $data = $this->api->user_profile(array('user_id' => $WHERE['id']));
                    } else {
                        $response_code = '200';
                        $response_message = 'Provider service failed';
                        $data = new stdClass();
                    }

                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                } else {
                    $response_code = '500';
                    $response_message = 'Inputs field missing';
                }
            } else {
                $response_code = '500';
                $response_message = 'Inputs field missing';
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function user_profile_get() {

        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $user_data['token'] = $this->api_token;
            $user_data['user_id'] = $this->users_id;
            $result = $this->api->user_profile($user_data);

            if ($result) {

                if (!empty($result['currency_code'])) {
                    $result['currency_id'] = $this->db->where('currency_code', $result['currency_code'])->get('currency_rate')->row()->id;
                } else {
                    $result['currency_id'] = "";
                }


                $response_code = '200';
                $response_message = 'Profile found';
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
            }
            $data = $result;
            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function service_availability_post() {


        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();

            if (!empty($user_data['date']) && !empty($user_data['service_id'])) {

                $timestamp = strtotime($user_data['date']);
                $day = date('D', $timestamp);


                $inputs = $user_data['service_id'];

                $result = $this->api->get_service_id($inputs);
                $provider_details = $this->api->provider_hours($result['user_id']);
                $availability_details = json_decode($provider_details['availability'], true);
                $alldays = false;
                if ($availability_details != '') {
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

                    if ($availability_details != '') {
                        foreach ($availability_details as $availability) {

                            if ($weekday == $availability['day'] && $availability['day'] != 0) {

                                $availability_day = $availability['day'];
                                $from_time = $availability['from_time'];
                                $to_time = $availability['to_time'];
                            }
                        }
                    }
                }

                if (!empty($from_time)) {
                    $temp_start_time = $from_time;
                    $temp_end_time = $to_time;
                } else {
                    $response_code = '500';
                    $response_message = 'Availability not found';
                    $data = new stdClass();
                    $res = $this->data_format($response_code, $response_message, $data);

                    $this->response($res, REST_Controller::HTTP_OK);
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


                $service_date = $user_data['date'];
                $service_id = $user_data['service_id'];


                $booking_count = $this->api->get_bookings($service_date, $service_id);


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
                        $st_time = date('g:i A', ($re));
                        $end_time = date('g:i A', ($re1));

                        $time['id'] = "$i";
                        $time['start_time'] = $st_time;
                        $time['end_time'] = $end_time;
                        $time['is_selected'] = '0';
                        $service_availability[] = $time;
                        $i++;
                    }
                } else {
                    $service_availability = '';
                }

                $data['service_availability'] = $service_availability;



                if ($service_availability != '') {
                    $response_code = '200';
                    $response_message = 'Availability details';
                } else {
                    $response_code = '200';
                    $response_message = 'Availability not found';
                    $data = new stdClass();
                }

                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '500';
                $response_message = 'Inputs field missing';
            }




            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function book_service_post() {

        $user_data = array();
        $user_data = getallheaders(); // Get Header Data
        $user_post_data = $this->post();


        $token = (!empty($user_data['token'])) ? $user_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_data['Token'])) ? $user_data['Token'] : '';
        }


        $data = array();
        $response_code = '-1';
        $response_message = 'Validation error';

        if (!empty($token)) {

            $result = $this->api->token_is_valid($token);
            $results = '';

            if ($result) {



                if (!empty($user_post_data['from_time']) && !empty($user_post_data['to_time']) && !empty($user_post_data['service_date']) && !empty($user_post_data['service_id']) && !empty($user_post_data['latitude']) && !empty($user_post_data['longitude']) && !empty('location') && !empty($user_post_data['notes']) && !empty($user_post_data['amount'])) {
                    $user_post_data['tokenid'] = 'flow changed';

                    $wallet = $this->api->get_wallet($token);

                    $curren_wallet = $wallet['wallet_amt'];

                    /* check wallet amount */

                    if ($user_post_data['amount'] > $curren_wallet) {

                        $response_code = '201';
                        $response_message = 'You do not have sufficient balance in your wallet account. Please Topup to book the service.';
                        $data = new stdClass();

                        $res = $this->data_format($response_code, $response_message, $data);

                        $this->response($res, REST_Controller::HTTP_OK);

                        return false;
                    }
                    /* check wallet amount */


                    $timestamp = strtotime($user_post_data['service_date']);
                    $day = date('D', $timestamp);


                    $inputs = $user_post_data['service_id'];

                    $result = $this->api->get_service_id($inputs);
                    $provider_details = $this->api->provider_hours($result['user_id']);
                    $availability_details = json_decode($provider_details['availability'], true);

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
                        $response_code = '500';
                        $response_message = 'Booking not available';
                        $data = new stdClass();
                        $res = $this->data_format($response_code, $response_message, $data);

                        $this->response($res, REST_Controller::HTTP_OK);
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

                    $data['availability'] = $timing_array;



                    // Booking availability


                    $booking_from_time = $user_post_data['from_time'];
                    $booking_end_time = $user_post_data['to_time'];

                    $timestamp_from = strtotime($booking_from_time);
                    $timestamp_to = strtotime($booking_end_time);

                    $from_time_railwayhrs = date('G:i:s', ($timestamp_from));
                    $to_time_railwayhrs = date('G:i:s', ($timestamp_to));

                    $service_date = $user_post_data['service_date'];
                    $service_id = $user_post_data['service_id'];


                    $booking_count = $this->api->get_bookings($service_date, $service_id);


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

                    // Booking code
                    $user_currency = get_api_user_currency($this->users_id);
                    $UserCurrency = $user_currency['user_currency_code'];
                    foreach ($new_timingarray as $booked_time) {

                        if ($booked_time['start_time'] == $from_time_railwayhrs && $booked_time['end_time'] == $to_time_railwayhrs) {
                            $booking = true;

                            $amt=(!empty($UserCurrency)) ? get_gigs_currency($user_post_data['amount'], $booked_time['currency_code'], $UserCurrency) : $user_post_data['amount'];
                            $charges_array = array();
                            $amount = (string) $amt;
                            $amount = $user_post_data['amount'];
                            $amount = ($amount * 100);
                            $charges_array['amount'] = $amount;
                            $charges_array['currency'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                            $charges_array['description'] = $user_post_data['notes'];
                            $charges_array['source'] = $user_post_data['tokenid'];
                            $charges_array['source'] = $user_post_data['tokenid'];
                            $provider_id = $result['user_id'];



                            $user_post_data['currency_code'] = $UserCurrency;
                            $user_post_data['provider_id'] = $provider_id;
                            $user_post_data['user_id'] = $this->users_id;
                            $user_post_data['request_date'] = date('Y-m-d H:i:s');
                            $user_post_data['request_time'] = time();
                            $user_post_data['from_time'] = date('G:i:s', ($timestamp_from));
                            $user_post_data['to_time'] = date('G:i:s', ($timestamp_to));
                            $user_post_data['updated_on'] = utc_date_conversion(date('Y-m-d H:i:s'));

                            $insert_booking = $this->api->insert_booking($user_post_data);

                            if ($insert_booking != '') {

                                /* create history */

                                $this->api->booking_wallet_history_flow($insert_booking, $token);
                                /* create history */

                                /* apns */
                                $data = $this->api->get_book_info_b($insert_booking);


                                $device_token = $this->api->get_device_info_multiple($data['provider_id'], 1);

                                $user_name = $this->api->get_user_info($data['user_id'], 2);

                                $provider_token = $this->api->get_user_info($user_post_data['provider_id'], 1);

                                if (!empty($user_name['name'])) {
                                    $u_name = $user_name['name'];
                                } else {
                                    $u_name = 'user';
                                }

                                $msg = $u_name . 'has booked your Service';

                                $this->api->insert_notification($token, $provider_token['token'], $msg);

                                if (!empty($device_token)) {

                                    foreach ($device_token as $key => $device) { /* loop */

                                        if (!empty($device['device_type']) && !empty($device['device_id'])) {

                                            if ($device['device_type'] == 'Android' || $device['device_type'] == 'android') {

                                                $notify_structure = array(
                                                    'title' => $data['service_title'],
                                                    'message' => $msg,
                                                    'image' => 'test22',
                                                    'action' => 'test222',
                                                    'action_destination' => 'test222',
                                                );


                                                sendFCMMessage($notify_structure, $device['device_id']);
                                            }
                                            if ($device['device_type'] == 'ios') {
                                                $notify_structure = array(
                                                    'alert' => $msg,
                                                    'sound' => 'default',
                                                    'badge' => 0,
                                                );

                                                sendApnsMessage($notify_structure, $device['device_id']);
                                            }
                                        }
                                    }/* loop */
                                }
                                /* apns */



                                $response_code = '200';
                                $response_message = 'Booked successfully';
                                $data = new stdClass();

                                $res = $this->data_format($response_code, $response_message, $data);

                                $this->response($res, REST_Controller::HTTP_OK);
                                break;
                            }
                        }
                    }

                    if ($booking == false) {

                        $response_code = '500';
                        $response_message = 'Booking not available';
                        $data = new stdClass();

                        $res = $this->data_format($response_code, $response_message, $data);

                        $this->response($res, REST_Controller::HTTP_OK);
                    }
                } else {
                    $response_code = '201';
                    $response_message = 'Input field missing';
                }
            } else {

                $response_code = '202';
                $response_message = 'Invalid user or token';
            }
        } else {

            $response_code = '200';
            $response_message = 'Token missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function search_services_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            $data = array();
            $user_data = $this->post();
            if (!empty($user_data['text']) && !empty($user_data['latitude']) && !empty($user_data['longitude'])) {

                $result = $this->api->search_request_list($user_data);

                if (is_array($result) && !empty($result)) {

                    $user_currency = get_api_user_currency($this->users_id);
                    $UserCurrency = $user_currency['user_currency_code'];

                    foreach ($result as $details) {


                        $this->db->select("service_image");
                        $this->db->from('services_image');
                        $this->db->where("service_id", $details['id']);
                        $this->db->where("status", 1);
                        $image = $this->db->get()->result_array();

                        $serv_image = '';
                        foreach ($image as $key => $i) {
                            $serv_image = $i['service_image'];
                        }


                        $serviceAmt = (!empty($UserCurrency && $details['currency_code'] != '')) ? get_gigs_currency($details['service_amount'], $details['currency_code'], $UserCurrency) : $details['service_amount'];
                        $res['service_id'] = $details['id'];
                        $res['service_title'] = $details['service_title'];
                        $res['service_amount'] = (string) $serviceAmt;
                        $res['service_location'] = $details['service_location'];
                        $res['service_image'] = $serv_image;
                        $res['category_name'] = $details['category_name'];
                        $res['subcategory_name'] = $details['subcategory_name'];
                        $res['rating'] = $details['rating'];
                        $res['rating_count'] = $details['rating_count'];
                        if ($details['profile_img'] == null) {
                            $res['profile_img'] = "";
                        } else {
                            $res['profile_img'] = $details['profile_img'];
                        }
                        $res['currency'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                        $response[] = $res;
                    }

                    $data = $response;
                    $response_code = '200';
                    $response_message = 'Service search result';
                } else {
                    $response_code = '200';
                    $response_message = 'No results found';
                    $data = array();
                }
            } else {
                $response_code = '500';
                $response_message = 'Input field missing';
            }

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function bookinglist_post() {

        $user_data = array();
        $user_data = $this->post(); // Get Header Data
        $user_post_data = getallheaders();


        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        $data = array();
        $response_code = '201';
        $response_message = 'Invalid token or token missing';



        if (!empty($user_data['type'] && !empty($user_data['status']))) {

            if ($user_data['type'] == 1) {

                $result = $this->api->token_is_valid_provider($token);
                $results = '';


                if ($result) {

                    $inputs = array();
                    $provider_id = $this->user_id;


                    $result = $this->api->get_bookinglist($provider_id, $user_data['status']);
	
                    if (!empty($result)) {
                        $provider_currency = get_api_provider_currency($provider_id);
                        $ProviderCurrency = $provider_currency['user_currency_code'];
                        foreach ($result as $details) {

                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $details['service_id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->result_array();

                            $this->db->select("*");
                            $this->db->from('users');
                            $this->db->where("id", $details['user_id']);

                            $user_mble = $this->db->get()->row_array();

                            $rating_count = $this->db->where(array("service_id" => $details['service_id'], 'status' => 1))->count_all_results('rating_review');


                            $this->db->select('AVG(rating)');
                            $this->db->where(array('service_id' => $details['service_id'], 'status' => 1));
                            $this->db->from('rating_review');
                            $rating = $this->db->get()->row_array();
                            $avg_rating = round($rating['AVG(rating)'], 2);


                            $serv_image = array();
                            foreach ($image as $key => $i) {
                                $serv_image[] = $i['service_image'];
                            }

                            $res['id'] = $details['id'];
                            $res['user_id'] = $details['user_id'];
                            $res['token'] = $user_mble['token'];
                            $res['name'] = $user_mble['name'];
                            $res['profile_img'] = $details['profile_img'];
                            $res['provider_id'] = $details['provider_id'];
                            $res['location'] = $details['location'];
                            $res['service_date'] = $details['service_date'];
                            if (!empty($user_mble['mobileno'])) {
                                $res['mobileno'] = $user_mble['mobileno'];
                            } else {
                                $res['mobileno'] = '';
                            }
                            $res['country_code'] = $details['country_code'];
							
							$res['service_id'] = $details['service_id'];

                            $service_amt = (!empty($ProviderCurrency) && $ProviderCurrency != '') ? get_gigs_currency($details['final_amount'], $details['currency_code'], $ProviderCurrency) : $details['final_amount'];
                            $res['service_title'] = $details['service_title'];
                            $res['service_amount'] = (string) $service_amt;
                            $res['category_name'] = $details['category_name'];
                            $res['subcategory_name'] = $details['subcategory_name'];


                            $res['service_image'] = $serv_image[0];
                            $res['rating_count'] = "$rating_count";
                            $res['rating'] = "$avg_rating";
                            $res['notes'] = $details['notes'];
                            $res['latitude'] = $details['latitude'];
                            $res['longitude'] = $details['longitude'];
                            $res['currency'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                            $res['status'] = $details['status'];
							
							$res['qr_img_url'] = ($details['qr_img_url'] !='')?base_url().$details['qr_img_url']:'';
							
							$stfres = $this->db->select('id,first_name as name')->where('id',$details['staff_id'])->get('employee_basic_details')->row_array();
							$res['staff_id'] = $details['staff_id'];
							$res['staff_name'] = ($stfres['name'])?$stfres['name']:'';
							
							$res['request_date'] = $details['request_date'];
							$res['request_time'] = $details['request_time'];
							$res['updated_on'] = $details['updated_on'];
							
                            $response[] = $res;
                        }

                        $data = $response;
                        $response_code = '200';
                        $response_message = "Booking list";
                    } else {
                        $response_code = '200';
                        $response_message = "No Records found";
                        $data = array();
                    }
                }
            } elseif ($user_data['type'] == 2) {

                $result = $this->api->token_is_valid($token);
                $results = '';

                if ($result) {


                    $inputs = array();
                    $user_id = $this->users_id;


                    $result = $this->api->get_bookinglist_user($user_id, $user_data['status']);
                   
                    if (!empty($result)) {

                        $user_currency = get_api_user_currency($user_id);
                        $UserCurrency = $user_currency['user_currency_code'];

                        foreach ($result as $details) {

                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $details['service_id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->result_array();

                            $this->db->select("*");
                            $this->db->from('providers');
                            $this->db->where("id", $details['provider_id']);

                            $provider_mble = $this->db->get()->row_array();



                            $rating_count = $this->db->where(array("service_id" => $details['service_id'], 'status' => 1))->count_all_results('rating_review');


                            $this->db->select('AVG(rating)');
                            $this->db->where(array('service_id' => $details['service_id'], 'status' => 1));
                            $this->db->from('rating_review');
                            $rating = $this->db->get()->row_array();
                            $avg_rating = round($rating['AVG(rating)'], 2);


                            $serv_image = array();
                            foreach ($image as $key => $i) {
                                $serv_image[] = $i['service_image'];
                            }


                            $res['id'] = $details['id'];
                            $res['user_id'] = $details['user_id'];
                            $res['token'] = $provider_mble['token'];
                            $res['name'] = $provider_mble['name'];
                            $res['profile_img'] = $details['provider_profile'];
                            $res['provider_id'] = $details['provider_id'];
                            $res['location'] = $details['location'];
                            $res['service_date'] = $details['service_date'];
                            $res['from_time'] = $details['from_time'];
                            $res['to_time'] = $details['to_time'];

                            $amt_detail= (!empty($UserCurrency)) ? get_gigs_currency($details['final_amount'], $details['currency_code'], $UserCurrency) : $details['final_amount'];
                            $res['service_title'] = $details['service_title'];
                            $res['service_amount'] = (string) $amt_detail;
                            $res['category_name'] = $details['category_name'];
                            $res['subcategory_name'] = $details['subcategory_name'];
							
							$res['service_id'] = $details['service_id'];

                            $res['service_title'] = $details['service_title'];
                            $res['service_image'] = $serv_image[0];
                            $res['rating_count'] = "$rating_count";
                            $res['rating'] = "$avg_rating";

                            if (!empty($provider_mble['mobileno'])) {
                                $res['mobileno'] = $provider_mble['mobileno'];
                            } else {
                                $res['mobileno'] = '';
                            }
                            $res['country_code'] = $details['country_code'];
                            $res['notes'] = $details['notes'];
                            $res['latitude'] = $details['latitude'];
                            $res['longitude'] = $details['longitude'];
                            $res['currency'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                            $res['status'] = $details['status'];
							
							$res['qr_img_url'] = ($details['qr_img_url'] !='')?base_url().$details['qr_img_url']:'';
							
							$stfres = $this->db->select('id,first_name as name')->where('id',$details['staff_id'])->get('employee_basic_details')->row_array();
							$res['staff_id'] = $details['staff_id'];
							$res['staff_name'] = $stfres['name'];
							
							$res['request_date'] = $details['request_date'];
							$res['request_time'] = $details['request_time'];
							$res['updated_on'] = $details['updated_on'];
							
							
                            $res['provider_profile'] = $details['provider_profile'];
//                            $res['amount'] = get_gigs_currency($details['amount'],$details['currency_code'],$user_currency['user_currency_code']);
                            $response[] = $res;
                        }

                        $data = $response;
                        $response_code = '200';
                        $response_message = "Booking service list";
                    } else {
                        $response_code = '200';
                        $response_message = "No Records found";
                        $data = array();
                    }
                }
            }
        } else {
            $response_code = '200';
            $response_message = "Input field missing";
            $data = array();
        }

        $result = $this->data_format($response_code, $response_message, $data);
        
        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function bookingdetail_post() {


        $user_data = array();
        $user_data = $this->post(); // Get Header Data
        $user_post_data = getallheaders();


        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        $data = array();
        $response_code = '201';
        $response_message = 'Invalid token or token missing';



        $data = array();
        $user_data = $this->post();
        $provider_id = $this->user_id;

        if (!empty($user_data['booking_id']) && !empty($user_data['type'])) {
            if ($user_data['type'] == 1) {


                $result = $this->api->token_is_valid_provider($token);
                $results = '';


                if ($result) {
                    $details = $this->api->get_bookingdetails($provider_id, $user_data['booking_id']);

                    if (!empty($details)) {
                        $provider_currency = get_api_provider_currency($provider_id);
                        $ProviderCurrency = $provider_currency['user_currency_code'];

                        foreach ($details as $result) {

                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $result['service_id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->result_array();

                            $rating_count = $this->db->where(array("service_id" => $result['service_id'], 'status' => 1))->count_all_results('rating_review');

                            $is_rated = $this->db->where(array("service_id" => $result['service_id'], 'user_id' => $result['user_id']))->count_all_results('rating_review');



                            $this->db->select('AVG(rating)');
                            $this->db->where(array('service_id' => $result['service_id'], 'status' => 1));
                            $this->db->from('rating_review');
                            $rating = $this->db->get()->row_array();
                            $avg_rating = round($rating['AVG(rating)'], 2);

                            $serv_image = array();
                            foreach ($image as $key => $i) {
                                $serv_image[] = $i['service_image'];
                            }

//                            print_r($result);exit;
                            $res_amt= (!empty($ProviderCurrency) && $ProviderCurrency != '') ? get_gigs_currency($result['final_amount'], $result['currency_code'], $ProviderCurrency) : $result['final_amount'];
                            $services['service_id'] = $result['service_id'];
                            $services['service_title'] = $result['service_title'];
                            $services['service_amount'] = (string) $res_amt;
                            ;
                            $services['about'] = $result['about'];
                            $services['service_offered'] = $result['service_offered'];
                            $services['service_location'] = $result['service_location'];
                            $services['service_latitude'] = $result['service_latitude'];
                            $services['service_longitude'] = $result['service_longitude'];
                            $services['category_name'] = $result['category_name'];
                            $services['subcategory_name'] = $result['subcategory_name'];
                            $services['currency_code'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                            $services['service_image'] = $serv_image;
                            $services['total_views'] = $result['total_views'];
                            $services['rating'] = "$avg_rating";
                            $services['rating_count'] = "$rating_count";
                            if ($is_rated != 0) {
                                $services['is_rated'] = "1";
                            } else {
                                $services['is_rated'] = "0";
                            }

                            $service_details = $services;

                            $res['booking_id'] = $user_data['booking_id'];
                            $res['user_id'] = $result['user_id'];
                            $res['provider_id'] = $result['provider_id'];
                            $res['service_date'] = $result['service_date'];
                            $res['from_time'] = date('g:i A', strtotime($result['from_time']));
                            $res['to_time'] = date('g:i A', strtotime($result['to_time']));
                            $res['currency_code'] = currency_code_sign(settings('currency'));
                            $res['notes'] = $result['notes'];
                            $res['user_rejected_reason'] = !empty($result['reason']) ? $result['reason'] : '';
                            $res['admin_comments'] = !empty($result['admin_reject_comment']) ? $result['admin_reject_comment'] : '';
                            $res['status'] = $result['status'];

                            $booking_details = $res;

                            $users['name'] = $result['name'];
                            $users['token'] = $result['token'];
                            $users['mobileno'] = $result['mobileno'];
                            $users['country_code'] = $result['country_code'];
                            $users['email'] = $result['email'];
                            $users['profile_img'] = $result['profile_img'];
                            $users['latitude'] = $result['latitude'];
                            $users['longitude'] = $result['longitude'];
                            $users['location'] = $result['location'];

                            $user_details = $users;



                            $response['booking_details'] = $booking_details;
                            $response['service_details'] = $service_details;
                            $response['personal_details'] = $user_details;
                        }
                        $data = $response;
                        $response_code = '200';
                        $response_message = "Service Booking list";
                    } else {
                        $response_code = '200';
                        $response_message = "No Records found";
                        $data = new stdClass();
                    }
                }
            } elseif ($user_data['type'] == 2) {

                $result = $this->api->token_is_valid($token);
                $results = '';

                if ($result) {
                    $data = array();
                    $user_data = $this->post();
                    $user_id = $this->users_id;

                    $details = $this->api->bookingdetail_user($user_id, $user_data['booking_id']);

                    if (!empty($details)) {
                        $user_currency = get_api_user_currency($user_id);
                        $UserCurrency = $user_currency['user_currency_code'];

                        foreach ($details as $result) {

                            


                            $this->db->select("service_image");
                            $this->db->from('services_image');
                            $this->db->where("service_id", $result['service_id']);
                            $this->db->where("status", 1);
                            $image = $this->db->get()->result_array();

                            $rating_count = $this->db->where(array("service_id" => $result['service_id'], 'status' => 1))->count_all_results('rating_review');

                            $is_rated = $this->db->where(array("service_id" => $result['service_id'], 'user_id' => $result['user_id']))->count_all_results('rating_review');

                            $this->db->select('AVG(rating)');
                            $this->db->where(array('service_id' => $result['service_id'], 'status' => 1));
                            $this->db->from('rating_review');
                            $rating = $this->db->get()->row_array();
                            $avg_rating = round($rating['AVG(rating)'], 2);


                            foreach ($image as $key => $i) {
                                $serv_image[] = $i['service_image'];
                            }
                            $rest_amt = (!empty($UserCurrency)) ? get_gigs_currency($result['final_amount'], $result['currency_code'], $UserCurrency) : $result['final_amount'];
                            $services['service_id'] = $result['service_id'];
                            $services['service_title'] = $result['service_title'];
                            $services['service_amount'] = (string) $rest_amt;
                            ;
                            $services['about'] = $result['about'];
                            $services['service_offered'] = $result['service_offered'];
                            $services['category_name'] = $result['category_name'];
                            $services['subcategory_name'] = $result['subcategory_name'];
                            $services['service_image'] = $serv_image;
                            $services['service_location'] = $result['service_location'];
                            $services['service_latitude'] = $result['service_latitude'];
                            $services['service_longitude'] = $result['service_longitude'];
                            $services['total_views'] = $result['total_views'];
                            $services['currency_code'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                            $services['rating'] = "$avg_rating";
                            $services['rating_count'] = "$rating_count";
                            if ($is_rated != 0) {
                                $services['is_rated'] = "1";
                            } else {
                                $services['is_rated'] = "0";
                            }


                            $service_details = $services;

                            $res['booking_id'] = $user_data['booking_id'];
                            $res['user_id'] = $result['user_id'];
                            $res['provider_id'] = $result['provider_id'];
                            $res['service_date'] = $result['service_date'];
                            $res['from_time'] = date('g:i A', strtotime($result['from_time']));
                            $res['to_time'] = date('g:i A', strtotime($result['to_time']));
                            $res['currency_code'] = currency_code_sign(settings('currency'));
                            $res['notes'] = $result['notes'];
                            $res['request_date'] = $result['request_date'];
                            $res['request_time'] = $result['request_time'];
                            $res['user_rejected_reason'] = !empty($result['reason']) ? $result['reason'] : '';
                            $res['admin_comments'] = !empty($result['admin_reject_comment']) ? $result['admin_reject_comment'] : '';
                            $res['status'] = $result['status'];

                            $booking_details = $res;

                            $provider['name'] = $result['name'];
                            $provider['token'] = $result['token'];
                            $provider['mobileno'] = $result['mobileno'];
                            $provider['country_code'] = $result['country_code'];
                            $provider['email'] = $result['email'];
                            $provider['profile_img'] = $result['profile_img'];
                            $provider['location'] = $result['location'];
                            $provider['latitude'] = $result['latitude'];
                            $provider['longitude'] = $result['longitude'];

                            $provider_details = $provider;



                            $response['booking_details'] = $booking_details;
                            $response['service_details'] = $service_details;
                            $response['personal_details'] = $provider_details;
                        }

                        $data = $response;
                        $response_code = '200';
                        $response_message = "Service Booking list";
                    } else {
                        $response_code = '200';
                        $response_message = "No Records found";
                        $data = new stdClass();
                    }
                }
            }
        } else {
            $response_code = '500';
            $response_message = 'Input field missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function requestlist_provider_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {


            $inputs = array();
            $provider_id = $this->user_id;


            $result = $this->api->get_requestlist($provider_id);

            if (!empty($result)) {

                $provider_currency = get_api_provider_currency($provider_id);
                $ProviderCurrency = $provider_currency['user_currency_code'];

                foreach ($result as $details) {

                    $this->db->select("service_image");
                    $this->db->from('services_image');
                    $this->db->where("service_id", $details['service_id']);
                    $this->db->where("status", 1);
                    $image = $this->db->get()->result_array();

                    $rating_count = $this->db->where(array("service_id" => $details['service_id'], 'status' => 1))->count_all_results('rating_review');


                    $this->db->select('AVG(rating)');
                    $this->db->where(array('service_id' => $details['service_id'], 'status' => 1));
                    $this->db->from('rating_review');
                    $rating = $this->db->get()->row_array();
                    $avg_rating = round($rating['AVG(rating)'], 2);

                    $serv_image = array();
                    foreach ($image as $key => $i) {
                        $serv_image[] = $i['service_image'];
                    }
                    $det_amt = (!empty($ProviderCurrency) && $ProviderCurrency != '') ? get_gigs_currency($details['service_amount'], $details['currency_code'], $ProviderCurrency) : $details['service_amount'];
                    $res['id'] = $details['id'];
                    $res['user_id'] = $details['user_id'];
                    $res['profile_img'] = $details['profile_img'];
                    $res['provider_id'] = $details['provider_id'];
                    $res['location'] = $details['location'];
                    $res['service_date'] = $details['service_date'];
                    $res['from_time'] = $details['from_time'];
                    $res['to_time'] = $details['to_time'];
                    $res['service_title'] = $details['service_title'];
                    $res['service_amount'] = (string) $det_amt;
                    $res['category_name'] = $details['category_name'];
                    $res['subcategory_name'] = $details['subcategory_name'];
                    $res['service_title'] = $details['service_title'];
                    $res['service_image'] = $serv_image[0];
                    $res['rating'] = "$avg_rating";
                    $res['rating_count'] = "$rating_count";
                    $res['notes'] = $details['notes'];
                    $res['latitude'] = $details['latitude'];
                    $res['longitude'] = $details['longitude'];
                    $res['currency_code'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                    $res['status'] = $details['status'];
                    $response[] = $res;
                }

                $data = $response;
                $response_code = '200';
                $response_message = "Request service list";
            } else {
                $response_code = '200';
                $response_message = "No Records found";
                $data = array();
            }




            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function bookinglist_users_get() {$this->users_id = 119;

        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {


            $inputs = array();
            $user_id = $this->users_id;


            $result = $this->api->get_bookinglist_user($user_id);

            if (!empty($result)) {

                foreach ($result as $details) {

                    $this->db->select("service_image");
                    $this->db->from('services_image');
                    $this->db->where("service_id", $details['service_id']);
                    $this->db->where("status", 1);
                    $image = $this->db->get()->result_array();

                    $serv_image = array();
                    foreach ($image as $key => $i) {
                        $serv_image[] = $i['service_image'];
                    }


                    $res['user_id'] = $details['user_id'];
                    $res['profile_img'] = $details['profile_img'];
                    $res['provider_id'] = $details['provider_id'];
                    $res['location'] = $details['location'];
                    $res['service_date'] = $details['service_date'];
                    $res['from_time'] = $details['from_time'];
                    $res['to_time'] = $details['to_time'];
                    $res['service_title'] = $details['service_title'];
                    $res['service_amount'] = $details['service_amount'];
                    $res['category_name'] = $details['category_name'];
                    $res['subcategory_name'] = $details['subcategory_name'];
                    $res['service_title'] = $details['service_title'];
                    $res['service_image'] = $serv_image[0];
                    $res['notes'] = $details['notes'];
                    $res['latitude'] = $details['latitude'];
                    $res['longitude'] = $details['longitude'];
                    $res['status'] = $details['status'];
                    $response[] = $res;
                }

                $data = $response;
                $response_code = '200';
                $response_message = "Booking service list";
            } else {
                $response_code = '200';
                $response_message = "No Records found";
                $data = new stdClass();
            }




            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function bookingdetail_user_post() {

        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            $data = array();
            $user_data = $this->post();
            $user_id = $this->users_id;

            if (!empty($user_data['booking_id'])) {

                $details = $this->api->bookingdetail_user($user_id, $user_data['booking_id']);

                if (!empty($details)) {


                    foreach ($details as $result) {


                        $this->db->select("service_image");
                        $this->db->from('services_image');
                        $this->db->where("service_id", $result['service_id']);
                        $this->db->where("status", 1);
                        $image = $this->db->get()->result_array();

                        foreach ($image as $key => $i) {
                            $serv_image[] = $i['service_image'];
                        }

                        $services['service_title'] = $result['service_title'];
                        $services['service_amount'] = $result['service_amount'];
                        $services['about'] = $result['about'];
                        $services['service_offered'] = $result['service_offered'];
                        $services['category_name'] = $result['category_name'];
                        $services['subcategory_name'] = $result['subcategory_name'];
                        $services['service_location'] = $result['service_location'];
                        $services['service_latitude'] = $result['service_latitude'];
                        $services['service_longitude'] = $result['service_longitude'];
                        $services['service_image'] = $serv_image[0];
                        $services['total_views'] = $result['total_views'];
                        $services['created_at'] = $result['created_at'];
                        $services['updated_at'] = $result['updated_at'];

                        $service_details[] = $services;

                        $res['booking_id'] = $result['id'];
                        $res['user_id'] = $result['user_id'];
                        $res['provider_id'] = $result['provider_id'];
                        $res['location'] = $result['location'];
                        $res['service_date'] = $result['service_date'];
                        $res['from_time'] = $result['from_time'];
                        $res['to_time'] = $result['to_time'];
                        $res['amount'] = $result['amount'];
                        $res['currency_code'] = $result['currency_code'];
                        $res['notes'] = $result['notes'];
                        $res['latitude'] = $result['latitude'];
                        $res['longitude'] = $result['longitude'];
                        $res['request_date'] = $result['request_date'];
                        $res['request_time'] = $result['request_time'];
                        $res['status'] = $result['status'];

                        $booking_details[] = $res;

                        $provider['name'] = $result['name'];
                        $provider['mobileno'] = $result['mobileno'];
                        $provider['email'] = $result['email'];
                        $provider['profile_img'] = $result['profile_img'];

                        $provider_details[] = $provider;



                        $response['booking_details'] = $booking_details;
                        $response['service_details'] = $service_details;
                        $response['provider_details'] = $provider_details;
                    }

                    $data = $response;
                    $response_code = '200';
                    $response_message = "Service Booking list";
                } else {
                    $response_code = '200';
                    $response_message = "No Records found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = 'Input field missing';
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function views_post() {


        $user_data = array();
        $user_data = getallheaders(); // Get Header Data
        $user_post_data = $this->post();


        $token = (!empty($user_data['token'])) ? $user_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_data['Token'])) ? $user_data['Token'] : '';
        }


        $data = array();
        $response_code = '-1';
        $response_message = 'validation error';

        if (!empty($token) && !empty($user_post_data['service_id'])) {

            $result = $this->api->token_is_valid($token);
            $results = '';
            $user_id = $this->users_id;

            if ($result) {

                $this->db->select('id');
                $this->db->from('views');
                $this->db->where(array('user_id' => $user_id, 'service_id' => $user_post_data['service_id']));
                $check_views = $this->db->count_all_results();


                if ($check_views == 0) {
                    $this->db->insert('views', array('user_id' => $user_id, 'service_id' => $user_post_data['service_id']));

                    $this->db->set('total_views', 'total_views+1', FALSE);
                    $this->db->where('id', $user_post_data['service_id']);
                    $results = $this->db->update('services');
                }

                if ($results == 1) {

                    $response_code = '200';
                    $response_message = 'Views added successfully';
                } else {

                    $response_code = '200';
                    $response_message = 'Views already added for this user';
                }
            } else {

                $response_code = '202';
                $response_message = 'Invalid user token';
            }
        } else {

            $response_code = '201';
            $response_message = 'User token is missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function update_bookingstatus_post() {


        $user_data = $this->post();

        if (!empty($user_data['id']) && !empty($user_data['status'])) {




            if ($user_data['status'] == '1') {
                $book_details['status'] = '2';
                $book_details['id'] = $user_data['id'];
            } elseif ($user_data['status'] == '2') {
                $book_details['status'] = '7';
                $book_details['id'] = $user_data['id'];
            } elseif ($user_data['status'] == '3') {
                $book_details['status'] = '3';
                $book_details['id'] = $user_data['id'];
            } elseif ($user_data['status'] == '6') {
                $book_details['status'] = '4';
                $book_details['id'] = $user_data['id'];
            } elseif ($user_data['status'] == '5') {
                $book_details['status'] = '5';
                $book_details['id'] = $user_data['id'];
            } elseif ($user_data['status'] == '6') {
                $book_details['status'] = '6';
                $book_details['id'] = $user_data['id'];
            }
			
			if($book_details['status'] == '3' || $book_details['status'] == 6) {
				$ginputs['status'] = $book_details['status'];		
				$ginputs['reason'] = '';					
				$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
				$cpfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
			}
			if($book_details['status'] == '7' || $book_details['status'] == '5') {
				$parent_book_details['status'] = $book_details['status'];
				$parent_book_details['reason'] = '';
				$PARENT_WHERE = array('parent_bookid' => $user_data['id']);
				$autoschedule_result=$this->api->update_bookingstatus($parent_book_details,$PARENT_WHERE);
				
				$ginputs['status'] = $book_details['status'];
				$ginputs['reason'] = '';
				$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
				$sfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
			}
			

            $booking_status = $this->api->booking_status($user_data['id']);

            if ($booking_status['status'] == '1' && $user_data['status'] == '1') {
                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Booking status updated successfully';
                }
            } elseif ($booking_status['status'] == '2' && $user_data['status'] == '3') {
                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Booking status updated successfully';
                }
            } elseif ($booking_status['status'] == '3' && $user_data['status'] == '4') {
                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Booking status updated successfully';
                }
            } elseif ($booking_status['status'] == '4' && $user_data['status'] == '5') {
                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Booking status updated successfully';
                }
            } elseif ($booking_status['status'] == '5' && $user_data['status'] == '6') {
                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Booking status updated successfully';
                }
            } else {
                $response_code = '200';
                $response_message = 'Booking status already updated';
            }
            $data = new stdClass();
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $response_code = '200';
            $response_message = 'Input field missing';
            $data = new stdClass();
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function service_statususer_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = $this->post();

            if (!empty($user_data['id']) && !empty($user_data['service_status'])) {

                $WHERE = array('id' => $user_data['id']);

                $result = $this->api->service_statususer($user_data, $WHERE);

                if ($result) {
                    $response_code = '200';
                    $response_message = 'Service status updated successfully';
                } else {
                    $response_code = '200';
                    $response_message = 'Service status updation failed';
                }
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '200';
                $response_message = 'Input field missing';
                $data = new stdClass();
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }

    public function update_booking_post() {


        $user_data = array();
        $user_data = $this->post(); // Get Header Data
        $user_post_data = getallheaders();


        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }

        if (!empty($user_data['id']) && !empty($user_data['status']) && !empty($user_data['type'])) {
            if ($user_data['type'] == 1) {


                $result = $this->api->token_is_valid_provider($token);


                $results = '';
                $user_id = $this->user_id;

                if ($result) {
                    if ($user_data['status'] == '1') {
                        $book_details['status'] = '2';
                        $book_details['id'] = $user_data['id'];
                    } elseif ($user_data['status'] == '2')/* cancel provider */ {
                        $book_details['status'] = '7';
                        $book_details['id'] = $user_data['id'];
                    } elseif ($user_data['status'] == '3')/* service completed */ {
                        $book_details['status'] = '3';
                        $book_details['id'] = $user_data['id'];
                    } elseif ($user_data['status'] == '4') {
                        $book_details['status'] = '6';
                        $book_details['id'] = $user_data['id'];
                    } elseif ($user_data['status'] == '5') {
                        $book_details['status'] = '5';
                        $book_details['id'] = $user_data['id'];
                    } elseif ($user_data['status'] == '6') {
                        $book_details['status'] = '6';
                        $book_details['id'] = $user_data['id'];
                    }
					
					if($book_details['status'] == '3' || $book_details['status'] == 6) {
						$ginputs['status'] = $book_details['status'];		
						$ginputs['reason'] = '';					
						$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
						$cpfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
					}
					if($book_details['status'] == '7' || $book_details['status'] == '5') {
						$parent_book_details['status'] = $book_details['status'];
						$parent_book_details['reason'] = 'Booking Cancelled/Rejected';
						$PARENT_WHERE = array('parent_bookid' => $user_data['id']);
						$autoschedule_result=$this->api->update_bookingstatus($parent_book_details,$PARENT_WHERE);
						
						$ginputs['status'] = $book_details['status'];
						$ginputs['reason'] = 'Booking Cancelled/Rejected';
						$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
						$sfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
					}

                    $booking_status = $this->api->booking_status($user_data['id']);

                    if ($booking_status['status'] == '1' && $user_data['status'] == '1') {

                        $WHERE = array('id' => $user_data['id']);

                        $result = $this->api->update_bookingstatus($book_details, $WHERE);

                        if ($result) {
                            /* provider accepted */
                            $this->send_push_notification($token, $user_data['id'], 2, ' Have Accepted The Service');

                            $response_code = '200';
                            $response_message = 'Booking status updated successfully';
                        }
                    } elseif ($booking_status['status'] == '1' && $user_data['status'] == '2') {
                        $WHERE = array('id' => $user_data['id']);

                        $result = $this->api->update_bookingstatus($book_details, $WHERE);

                        if ($result) {

                            /* wallet history */
                        
                            /* wallet history */

                            /* Provider Rejected */
                            $this->send_push_notification($token, $user_data['id'], 2, ' Has Rejected The Service');

                            $response_code = '200';
                            $response_message = 'Booking status updated successfully';
                        }
                    } elseif ($booking_status['status'] == '2' && $user_data['status'] == '3') {
                        $WHERE = array('id' => $user_data['id']);

                        $result = $this->api->update_bookingstatus($book_details, $WHERE);

                        if ($result) {
                            /* provider completed */
                            $this->send_push_notification($token, $user_data['id'], 2, ' Have Completed Ther Service');

                            $response_code = '200';
                            $response_message = 'Booking status updated successfully';
                        }
                    } elseif ($booking_status['status'] == '2' && $user_data['status'] == '2') {
                        $WHERE = array('id' => $user_data['id']);

                        $result = $this->api->update_bookingstatus($book_details, $WHERE);

                        if ($result) {
                            /* provider completed */
                            $this->send_push_notification($token, $user_data['id'], 2, ' Have Cancelled Ther Service');

                            $response_code = '200';
                            $response_message = 'Booking status updated successfully';
                        }

                        
                    }else{
                        $response_code = '200';
                        $response_message = 'Booking status already updated';
                    }
                    $data = new stdClass();
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                } else {
                    $response_code = "500";
                    $response_message = "Token is Invalid";
                    $data = [];
                }
            } elseif ($user_data['type'] == 2) {

                $result = $this->api->token_is_valid($token);
                $results = '';
                $users_id = $this->users_id;

                if ($result) {

                    if ($user_data['status'] == '4') {

                        if (!empty($user_data['id']) && !empty($user_data['status']) && !empty($user_data['type'])) {


                            $book_details['status'] = '6';
                            $book_details['id'] = $user_data['id'];
                            /* apns push notification */



                            $booking_status = $this->api->booking_status($user_data['id']);
							
							if($book_details['status'] == 6) {
								$ginputs['status'] = $book_details['status'];		
								$ginputs['reason'] = '';					
								$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
								$cpfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
							}

                            if ($booking_status['status'] == '3' && $user_data['status'] == '4') {
                                $WHERE = array('id' => $user_data['id']);

                                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                                if ($result) {
                                    /* wallet history */
                                   
									
									
									if($book_details['status'] == '3') {
										$ginputs['status'] = $book_details['status'];		
										$ginputs['reason'] = '';					
										$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
										$cpfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
									}

                                    /* completed user site */
                                    $this->send_push_notification($token, $user_data['id'], 1, ' Has Accepted The Completed Service');
                                    $response_code = '200';
                                    $response_message = 'Booking status updated successfully';
                                }
                            } else {
                                $response_code = '200';
                                $response_message = 'Booking status already updated';
                            }
                            $data = new stdClass();
                            $result = $this->data_format($response_code, $response_message, $data);
                            $this->response($result, REST_Controller::HTTP_OK);
                        } else {
                            $response_code = '200';
                            $response_message = 'Input field missing';
                        }
                    } else if ($user_data['status'] == '2') {

                        if (!empty($user_data['id']) && !empty($user_data['status']) && !empty($user_data['type'])) {


                            $book_details['status'] = '7';
                            $book_details['id'] = $user_data['id'];
                            /* apns push notification */



                            $booking_status = $this->api->booking_status($user_data['id']);
							
							
							if($book_details['status'] == '7') {
								$parent_book_details['status'] = $book_details['status'];
								$parent_book_details['reason'] = 'Booking Cancelled';
								$PARENT_WHERE = array('parent_bookid' => $user_data['id']);
								$autoschedule_result=$this->api->update_bookingstatus($parent_book_details,$PARENT_WHERE);
								
								$ginputs['status'] = $book_details['status'];
								$ginputs['reason'] = 'Booking Cancelled';
								$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
								$sfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
							}
									

                            if ($booking_status['status'] == '1' && $user_data['status'] == '2') {
                                $WHERE = array('id' => $user_data['id']);

                                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                                if ($result) {
                                    /* wallet history */
                             

                                    /* completed user site */
                                    $this->send_push_notification($token, $user_data['id'], 1, ' Has Cancelled The Service');
                                    $response_code = '200';
                                    $response_message = 'Booking status updated successfully';
                                }
                            } else {
                                $response_code = '200';
                                $response_message = 'Booking status already updated';
                            }
                            $data = new stdClass();
                            $result = $this->data_format($response_code, $response_message, $data);
                            $this->response($result, REST_Controller::HTTP_OK);
                        } else {
                            $response_code = '200';
                            $response_message = 'Input field missing';
                        }
                    } elseif ($user_data['status'] == '5') {

                        if (!empty($user_data['id']) && !empty($user_data['status']) && !empty($user_data['reason'])) {


                            $book_details['status'] = '5';
                            $book_details['id'] = $user_data['id'];
                            $book_details['reason'] = $user_data['reason'];


                            $booking_status = $this->api->booking_status($user_data['id']);
                            
							
							if($book_details['status'] == '5') {
								$parent_book_details['status'] = $book_details['status'];
								$parent_book_details['reason'] = $book_details['reason'];
								$PARENT_WHERE = array('parent_bookid' => $user_data['id']);
								$autoschedule_result=$this->api->update_bookingstatus($parent_book_details,$PARENT_WHERE);
								
								$ginputs['status'] = $book_details['status'];
								$ginputs['reason'] = $book_details['reason'];
								$GPARENT_WHERE = array('guest_parent_bookid' => $user_data['id']);
								$sfresult = $this->api->update_bookingstatus($ginputs,$GPARENT_WHERE);
							}


                            if ($booking_status['status'] == '3' && $user_data['status'] == '5') {
                                $WHERE = array('id' => $user_data['id']);

                                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                                if ($result) {
                                    /* user rejected */

                                    $this->send_push_notification($token, $user_data['id'], 1, ' Has Rejected The Completed Service');
                                    $response_code = '200';
                                    $response_message = 'Booking status updated successfully';
                                }
                            } else if($booking_status['status'] == '2' && $user_data['status'] == '5') {
                                
                                $WHERE = array('id' => $user_data['id']);
                                $booking_status['status'] = $user_data['status'];
                                $result = $this->api->update_bookingstatus($book_details, $WHERE);

                                if ($result) {
                                    $this->send_push_notification($token,$user_data['id'],2,' Have Rejected The Service - '.$booking_status['service_title']);
                                    $response_code = '200';
                                    $response_message = 'Booking status updated successfully';
                                }
                            } else {
                                $response_code = '200';
                                $response_message = 'Booking status already updated';
                            }
                            $data = new stdClass();
                            $result = $this->data_format($response_code, $response_message, $data);
                            $this->response($result, REST_Controller::HTTP_OK);
                        } else {
                            $response_code = '200';
                            $response_message = 'Input field missing';
                        }
                    }
                } else {
                    $response_code = "500";
                    $response_message = "Token is Invalid";
                    $data = [];
                }
            }
        } else {

            $response_code = '500';
            $response_message = 'Input field missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function rate_review_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = $this->post();

            if (!empty($user_data['rating']) && !empty($user_data['review']) && !empty($user_data['booking_id']) && !empty($user_data['service_id']) && !empty($user_data['type'])) {

                $check_service_status = $this->api->check_booking_status($user_data['booking_id']);

                if ($check_service_status != '') {

                    $result = $this->api->rate_review_for_service($user_data);

                    if ($result == 1) {
                        $response_code = '200';
                        $response_message = 'Thank you for your review';
                    } elseif ($result == 2) {

                        $response_code = '200';
                        $response_message = 'You have already reviwed this service';
                    }
                } else {
                    $response_code = '500';
                    $response_message = 'Service not completed';
                }
            } else {

                $response_code = '500';
                $response_message = 'Input field missing';
            }
        } else {
            $this->token_error();
        }

        $data = new stdClass();
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function delete_account_post() {


        $user_data = array();
        $user_data = $this->post(); // Get Header Data
        $user_post_data = getallheaders();


        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        $data = array();
        $response_code = '201';
        $response_message = 'Invalid token or token missing';

        if (!empty($user_data['type'])) {

            if ($user_data['type'] == 1) {
               
                $result = $this->api->token_is_valid_provider($token);
                $results = '';
                $user_id = $this->user_id;

                if ($result) {
                    $WHERE = array('id' => $user_id);
                    $details = $this->api->delete_account_provider($user_data, $WHERE);
                    if ($details) {
                        $response_code = '200';
                        $response_message = 'Account deleted successfully';
                    } else {
                        $response_code = '200';
                        $response_message = 'Something went wrong. Please try again later';
                    }
                }
            } elseif ($user_data['type'] == 2) {

                $result = $this->api->token_is_valid($token);
                $results = '';
                $user_id = $this->users_id;

                if ($result) {
                    $WHERE = array('id' => $user_id);
                    $details = $this->api->delete_account_user($user_data, $WHERE);
                    if ($details) {
                        $response_code = '200';
                        $response_message = 'Account deleted successfully';
                    } else {
                        $response_code = '200';
                        $response_message = 'Something went wrong. Please try again later';
                    }
                }
            }
        } else {
            $data = new stdClass();
            $response_code = '500';
            $response_message = 'Input field missing';
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    //get service belong to sub category id
    public function get_services_from_subid_post() {

        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            $user_data = array();
            $user_data = $this->post();

            if (!empty($user_data['latitude']) && !empty($user_data['longitude']) && !empty($user_data['subcategory_id'])) {
                $val = $this->api->get_services_from_sub_service_id($user_data);
                $data = [];

                if (!empty($val)) {
                    $response_code = '200';
                    $response_message = "Successfully fetched...!";

                    $user_currency = get_api_user_currency($this->users_id);
                    $UserCurrency = $user_currency['user_currency_code'];


                    foreach ($val as $key => $value) {

                        $service_image = $this->api->get_common_service_image($value['id'], 1);
                        $rating_count = $this->db->where(array("service_id" => $value['id'], 'status' => 1))->count_all_results
                                ('rating_review');

                        $this->db->select('AVG(rating)');
                        $this->db->where(array('service_id' => $value['id'], 'status' => 1));
                        $this->db->from('rating_review');
                        $rating = $this->db->get()->row_array();
                        $avg_rating = round($rating['AVG(rating)'], 2);

                        $val_amt= (!empty($UserCurrency) && $value['currency_code'] != '') ? get_gigs_currency($value['service_amount'], $value['currency_code'], $UserCurrency) : $value['service_amount'];
                        $data[$key]['id'] = $value['id'];
                        $data[$key]['service_title'] = $value['service_title'];
                        $data[$key]['service_amount'] = (string) $val_amt;
                        $data[$key]['service_location'] = $value['service_location'];
                        $data[$key]['rating'] = "$avg_rating";
                        $data[$key]['rating_count'] = "$rating_count";



                        if (!empty($value['profile_img'])) {
                            $data[$key]['profile_img'] = $value['profile_img'];
                        } else {
                            $data[$key]['profile_img'] = '';
                        }
                        $data[$key]['category_name'] = $value['category_name'];
                        $data[$key]['subcategory_name'] = $value['subcategory_name'];

                        if (!empty($service_image['service_image'])) {
                            $data[$key]['service_image'] = $service_image['service_image'];
                        } else {
                            $data[$key]['service_image'] = '';
                        }
                        $data[$key]['currency'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                    }
                } else {

                    $response_code = '200';
                    $response_message = "No Records found";
                    $data = [];
                }
            } else {
                $response_code = '200';
                $response_message = "Input field missing";
                $data = [];
            }


            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    //get dashboard counts

    public function get_provider_dashboard_infos_get() {

        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            $counts = $this->api->get_provider_dashboard_count($this->user_id);

            $response_code = "200";
            $response_message = "successfully fetched...!";
            $data = $counts;
            $data['booking_count'] = $this->api->booking_count($this->user_id);
            $data['services_count'] = $this->api->services_count($this->user_id);
            $data['my_subscribe'] = $my_subscribe = $this->api->get_my_subscription($this->user_id,'provider');
            $data['notification_count'] = $this->api->notification_count($this->api_token);
            if(!empty($my_subscribe)){
                $subscription_fee=$this->db->where('id',$my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
                $data['my_subscribe']['subscription_name'] = $subscription_fee['subscription_name'];
                $data['my_subscribe']['fee'] = $subscription_fee['fee'];
                $data['my_subscribe']['currency_code'] = currency_code_sign($subscription_fee['currency_code']);
            }else{
                $data['my_subscribe']['subscription_name']='';
                $data['my_subscribe']['fee']='';
                $data['my_subscribe']['currency_code']='';
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function generate_otp_provider_post() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
            $data = new stdClass();
            $user_data = array();
            $user_data = $this->post();
			
			$login_type = settingValue('login_type');
            //print_r($login_type);exit;
            

            if (!empty($user_data['mobileno']) && !empty($user_data['country_code']) && !empty($user_data['device_type'])) {

                $is_available_mobile = $this->api->check_mobile_no($user_data);
                $is_available_mobileno = $this->api->check_user_mobileno($user_data);
                $is_available_user = $this->api->check_user_mobileno($user_data);

                if ($is_available_user == 0) {
					
					$is_available_email = $this->api->check_email($user_data);
					if ($is_available_email == 0) {
						
						if ($is_available_mobile == 0 && $is_available_mobileno == 0) {


                        if (!empty($user_data['name']) && !empty($user_data['email']) && !empty($user_data['mobileno']) && !empty($user_data['country_code']) && !empty($user_data['device_type'])) {
                        	//&& !empty($user_data['category']) && !empty($user_data['subcategory'])
                            $user_details['name'] = $user_data['name'];
                            $user_details['email'] = $user_data['email'];
							
							if(!empty($user_data['password']))
							{
								$user_details['password'] =md5($user_data['password']);
							}
							else
							{
								$user_details['password']='';
							}
                            $user_details['mobileno'] = $user_data['mobileno'];
                            $user_details['country_code'] = $user_data['country_code'];
                            $user_details['currency_code'] = settings('currency');
                            //$user_details['category'] = $user_data['category'];
                            //$user_details['subcategory'] = $user_data['subcategory'];
                            $device_data['device_type'] = $user_data['device_type'];
                            $device_data['device_id'] = $user_data['device_id'];
                            $username = strlen($user_data['name']);
                            $user_details['share_code'] = $this->user_login->ShareCode(6, $username);

                            $share_code = $user_data['get_code'];

//                            if ($share_code) {
                                $updateAmount = $this->api->ProviderShareCode($share_code);
                                if ($updateAmount == 'Empty code') {
									
									
									if($login_type=='email' && empty($user_details['password']))
									{
										$response_code = '201';
										$response_message = 'Please enter the password';
										$result = $this->data_format($response_code, $response_message, $data);
										$this->response($result, REST_Controller::HTTP_OK);
										exit;
									}
                                    
									$result = $this->api->provider_signup($user_details, $device_data);
                                    if ($result != '') {
										
										if($login_type=='mobile' && empty($user_details['password']))
										{
											$default_otp = settingValue('default_otp');
											if ($default_otp == 1) {
												$otp = '1234';
											} else {
												$otp = rand(1000, 9999);
											}

											$message = 'Your OTP is ' . $otp . '';
											$user_data['otp'] = $otp;

											error_reporting(0);
											$key = settingValue('sms_key');
											$secret_key = settingValue('sms_secret_key');
											$sender_id = settingValue('sms_sender_id');
											require_once('vendor/nexmo/src/NexmoMessage.php');
											$nexmo_sms = new NexmoMessage($key, $secret_key);
											$result = $nexmo_sms->sendText($user_data['country_code'] . $user_data['mobileno'], $sender_id, $message);
											$this->session->set_tempdata('otp', '$user_data', 300);


											$otp_data = array(
												'endtime' => time() + 300,
												'mobile_number' => $user_data['mobileno'],
												'country_code' => $user_data['country_code'],
												'otp' => $otp
											);

											$ret = $this->db->select('*')->from('mobile_otp')->
													where('country_code', $user_data['country_code'])->
													where('mobile_number', $user_data['mobileno'])->
													where('status', 1)->
													count_all_results();
											if ($ret > 0) {
												/* update otp */
												$this->db->where('country_code', $country_code);
												$this->db->where('mobile_number', $mobile_no);
												$this->db->where('status', 1);
												$save_otp = $this->db->update('mobile_otp', array('endtime' => $otp_data['endtime'], 'otp' => $otp_data['otp'], 'updated_on' => utc_date_conversion(date('Y-m-d H:i:s'))));
											} else {
												$save_otp = $this->api->save_otp($otp_data);
											}


											$response_code = '200';
											$response_message = 'OTP send successfully';
										}
										else
										{
											$response_code = '200';
											$response_message = 'Provider registered successfully';
										}
                                        
                                    } else {
                                        $response_code = '200';
                                        $response_message = 'Something went wrong. Please try again later.';
                                    }
                                } else {
                                    $response_code = '201';
                                    $response_message = 'Share Code Invalid';
                                }
//                            }
                        } else {
                            $response_code = '201';
                            $response_message = 'Please enter the required fields to register';
                        }
                    } elseif ($is_available_mobile == 1) {
                        if (!empty($user_data['name']) && !empty($user_data['email'])) {
                            $response_code = '201';
                            $response_message = 'Mobile number already exists as user. Please use another mobile number';
                        } else {

                            $default_otp = settingValue('default_otp');
                            if ($default_otp == 1) {
                                $otp = '1234';
                            } else {
                                $otp = rand(1000, 9999);
                            }

                            $message = 'Your OTP is ' . $otp . '';
                            $user_data['otp'] = $otp;

                            error_reporting(0);
                            $key = settingValue('sms_key');
                            $secret_key = settingValue('sms_secret_key');
                            $sender_id = settingValue('sms_sender_id');
                            require_once('vendor/nexmo/src/NexmoMessage.php');
                            $nexmo_sms = new NexmoMessage($key, $secret_key);
                            $result = $nexmo_sms->sendText($user_data['country_code'] . $user_data['mobileno'], $sender_id, $message);
                            $this->session->set_tempdata('otp', '$user_data', 300);

                            $otp_data = array(
                                'endtime' => time() + 300,
                                'mobile_number' => $user_data['mobileno'],
                                'country_code' => $user_data['country_code'],
                                'otp' => $otp,
                                'status' => 1
                            );

                            $ret = $this->db->select('*')->from('mobile_otp')->
                                    where('country_code', $user_data['country_code'])->
                                    where('mobile_number', $user_data['mobileno'])->
                                    where('status', 1)->
                                    count_all_results();
                            if ($ret > 0) {
                                /* update otp */
                                $this->db->where('country_code', $country_code);
                                $this->db->where('mobile_number', $mobile_no);
                                $this->db->where('status', 1);
                                $save_otp = $this->db->update('mobile_otp', array('endtime' => $otp_data['endtime'], 'otp' => $otp_data['otp'], 'updated_on' => utc_date_conversion(date('Y-m-d H:i:s'))));
                            } else {
                                $save_otp = $this->api->save_otp($otp_data);
                            }




                            $update_check = $this->api->update_device_details($user_data);

                            $response_code = '200';
                            $response_message = 'OTP send successfully';
                        }
                    }
					else {
                        $response_code = '500';
                        $response_message = 'Please fillin the required fields.';
                    }
						
					}
					else {
                    $response_code = '500';
                    $response_message = 'This Email ID is already registered.';
					}

                    
                } else {
                    $response_code = '500';
                    $response_message = 'This number is already registered as User.';
                }
            }
			if (!empty($user_data['email']) && !empty($user_data['password']) && !empty($user_data['device_type']) && empty($user_data['mobileno'])) {
				
				$update_check = $this->api->update_device_details($user_data);
				$response_code = '200';
				$response_message = 'OTP send successfully ';
			}
				
			
			



            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function generate_otp_user_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
            $user_data = $this->post();
			$login_type = settingValue('login_type');
            
            if (!empty($user_data['mobileno']) && !empty($user_data['country_code']) && !empty($user_data['device_type'])) {
            	 //&& !empty($user_data['gender'])
                $is_available_mobile = $this->api->check_mobile_no($user_data);
                $is_available_mobileno = $this->api->check_user_mobileno($user_data);
                $is_available_provider = $this->api->check_mobile_no($user_data);
                if ($is_available_provider == 0) {
				 $is_available_email = $this->api->check_user_email($user_data);
				 if ($is_available_provider == 0) {
					 
					 if ($is_available_email == 0) {
							 if ($is_available_mobile == 0 && $is_available_mobileno == 0) {


							if (!empty($user_data['name']) && !empty($user_data['email']) && !empty($user_data['mobileno']) && !empty($user_data['country_code']) && !empty($user_data['device_type'])) {

								$user_details['name'] = $user_data['name'];
								$user_details['email'] = $user_data['email'];
								if(!empty($user_data['password']))
								{
									$user_details['password'] =md5($user_data['password']);
								}
								else
								{
									$user_details['password']='';
								}
								$user_details['mobileno'] = $user_data['mobileno'];
								$user_details['gender'] = $user_data['gender'];
								$user_details['country_code'] = $user_data['country_code'];
								$user_details['currency_code'] = settings('currency');
								$device_data['device_type'] = $user_data['device_type'];
								$device_data['device_id'] = $user_data['device_id'];
								$username = strlen($user_data['name']);
								$user_details['share_code'] = $this->user_login->ShareCode(6, $username);
								$share_code = $user_data['get_code'];

	//                            if ($share_code) {
									$updateAmount = $this->api->UserShareCode($share_code);
									if ($updateAmount =='Empty code') {
										
										if($login_type=='email' && empty($user_details['password']))
										{
											$response_code = '201';
											$response_message = 'Please enter the password';
											$result = $this->data_format($response_code, $response_message, $data);
											$this->response($result, REST_Controller::HTTP_OK);
											exit;
										}

										$result = $this->api->user_signup($user_details, $device_data);
										
										

										if ($result != '') {
												if($login_type=='mobile' && empty($user_details['password']))
												{
													$default_otp = settingValue('default_otp');
													if ($default_otp == 1) {
														$otp = '1234';
													} else {
														$otp = rand(1000, 9999);
													}

													$message = 'Your OTP is' . $otp . '';
													$user_data['otp'] = $otp;

													error_reporting(0);
													$key = settingValue('sms_key');
													$secret_key = settingValue('sms_secret_key');
													$sender_id = settingValue('sms_sender_id');
													require_once('vendor/nexmo/src/NexmoMessage.php');
													$nexmo_sms = new NexmoMessage($key, $secret_key);
													$result = $nexmo_sms->sendText($user_data['country_code'] . $user_data['mobileno'], $sender_id, $message);
													$this->session->set_tempdata('otp', '$user_data', 300);

													$otp_data = array(
														'endtime' => time() + 300,
														'mobile_number' => $user_data['mobileno'],
														'country_code' => $user_data['country_code'],
														'otp' => $otp
													);

													$ret = $this->db->select('*')->from('mobile_otp')->
															where('country_code', $user_data['country_code'])->
															where('mobile_number', $user_data['mobileno'])->
															where('status', 1)->
															count_all_results();
													if ($ret > 0) {
														/* update otp */
														$this->db->where('country_code', $country_code);
														$this->db->where('mobile_number', $mobile_no);
														$this->db->where('status', 1);
														$save_otp = $this->db->update('mobile_otp', array('endtime' => $otp_data['endtime'], 'otp' => $otp_data['otp'], 'updated_on' => utc_date_conversion(date('Y-m-d H:i:s'))));
													} else {
														$save_otp = $this->api->save_otp($otp_data);
													}



													$response_code = '200';
													$response_message = 'OTP send successfully';
													
												}
												else
												{
													$response_code = '200';
													$response_message = 'User registered successfully';
												}
											
										} else {
											$response_code = '200';
											$response_message = 'Something went wrong. Please try again later.';
										}
									} else {
										$response_code = '201';
										$response_message = 'Share Code Invalid';
									}
	//                            }
							} else {
								$response_code = '201';
								$response_message = 'Please enter the required fields to register';
							}
						} elseif ($is_available_mobileno == 1) {

							if (!empty($user_data['name']) && !empty($user_data['email'])) {
								$response_code = '201';
								$response_message = 'Mobile number already exists as provider. Please use another mobile number';
							} else {


								$default_otp = settingValue('default_otp');
								if ($default_otp == 1) {
									$otp = '1234';
								} else {
									$otp = rand(1000, 9999);
								}

								$message = 'Your OTP is ' . $otp . '';
								$user_data['otp'] = $otp;

								error_reporting(0);
								$key = settingValue('sms_key');
								$secret_key = settingValue('sms_secret_key');
								$sender_id = settingValue('sms_sender_id');
								require_once('vendor/nexmo/src/NexmoMessage.php');
								$nexmo_sms = new NexmoMessage($key, $secret_key);
								$result = $nexmo_sms->sendText($user_data['country_code'] . $user_data['mobileno'], $sender_id, $message);
								$this->session->set_tempdata('otp', '$user_data', 300);

								$otp_data = array(
									'endtime' => time() + 300,
									'mobile_number' => $user_data['mobileno'],
									'country_code' => $user_data['country_code'],
									'otp' => $otp,
									'status' => 1
								);

								$ret = $this->db->select('*')->from('mobile_otp')->
										where('country_code', $user_data['country_code'])->
										where('mobile_number', $user_data['mobileno'])->
										where('status', 1)->
										count_all_results();
								if ($ret > 0) {
									/* update otp */
									$this->db->where('country_code', $country_code);
									$this->db->where('mobile_number', $mobile_no);
									$this->db->where('status', 1);
									$save_otp = $this->db->update('mobile_otp', array('endtime' => $otp_data['endtime'], 'otp' => $otp_data['otp'], 'updated_on' => utc_date_conversion(date('Y-m-d H:i:s'))));
								} else {
									$save_otp = $this->api->save_otp($otp_data);
								}



								$update_device = $this->api->update_device_user($user_data);

								$response_code = '200';
								$response_message = 'OTP send successfully';
							}
						} else {
							$response_code = '500';
							$response_message = 'Mobile number does not exit';
						}
						 
					 }
					 else {
						$response_code = '500';
						$response_message = 'This Email ID is already registered.';
					}
				 }
				 else {
					$response_code = '500';
					$response_message = 'This Email ID is already registered';
                }

                } else {
                    $response_code = '500';
                    $response_message = 'This number is already registered as Provider.';
                }
            }
			if (!empty($user_data['email']) && !empty($user_data['password']) && !empty($user_data['device_type']) && empty($user_data['mobileno'])) {
				
				$update_check = $this->api->update_device_user($user_data);
				$response_code = '200';
				$response_message = 'OTP send successfully ';
			}



            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function review_type_get() {

        $data = array();

        $rating_type = $this->api->get_rating_type();

        if (!empty($rating_type)) {
            $response_code = '200';
            $response_message = "Review type List";
            $data['review_type'] = $rating_type;
        } else {
            $response_code = '200';
            $response_message = "No Results found";
        }


        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function details_get() {

        $data = new stdClass();

        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }

        $data = array();
        $response_code = '500';
        $response_message = 'Validation error';

        if (!empty($token)) {



            if (isset($_GET['type']) && !empty($_GET['type'])) {

                $type = $this->get('type');

                if ($type == 1) {
                    $user_id = $this->api->get_user_id_using_token($token);

                    $detail['id'] = $user_id;
                    $detail['type'] = $type;

                    $account_details = $this->api->accdetails_provider($detail);
                    $availability_details = $this->api->get_availability($detail['id']);


                    if ($account_details['account_number'] == '') {
                        $account_details = "0";
                    } elseif ($account_details['account_number'] != '') {
                        $account_details = "1";
                    }

                    if ($availability_details == '') {

                        $availability_details = "0";
                    } elseif ($availability_details != '') {

                        $availability_details = "1";
                    }



                    $response_code = '200';
                    $response_message = "Account status";
                    $data['account_details'] = $account_details;
                    $data['availability_details'] = $availability_details;
                } elseif ($type == 2) {

                    $user_id = $this->api->get_users_id_using_token($token);

                    $detail['id'] = $user_id;


                    $user_details = $this->api->accdetails_user($detail);
                    if ($user_details['account_number'] != '') {
                        $acc_detail = "1";
                    } else {
                        $acc_detail = "0";
                    }

                    $response_code = '200';
                    $response_message = "Account status";
                    $data['account_details'] = $acc_detail;
                }
            } else {
                $response_code = '200';
                $response_message = "Input field missing";
            }


            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function account_details_get() {

        $data = new stdClass();

        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }

        $data = array();
        $response_code = '500';
        $response_message = 'Validation error';

        if (!empty($token)) {



            if (isset($_GET['type']) && !empty($_GET['type'])) {

                $type = $this->get('type');

                if ($type == 1) {
                    $user_id = $this->api->get_user_id_using_token($token);

                    $detail['id'] = $user_id;
                    $detail['type'] = $type;

                    $account_details = $this->api->accdetails_provider($detail);
                    if ($account_details != '') {

                        $response_code = '200';
                        $response_message = "Account details";
                        $data = $account_details;
                    } else {
                        $response_code = '200';
                        $response_message = "No records found";
                        $data = new stdClass();
                    }
                } elseif ($type == 2) {

                    $user_id = $this->api->get_users_id_using_token($token);

                    $detail['id'] = $user_id;


                    $user_details = $this->api->accdetails_user($detail);
                    if ($user_details != '') {
                        $response_code = '200';
                        $response_message = "Account details";
                        $data = $user_details;
                    } else {
                        $response_code = '200';
                        $response_message = "No records found";
                        $data = new stdClass();
                    }
                }
            } else {
                $response_code = '200';
                $response_message = "Input field missing";
            }


            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function token_error() {
        $response_code = '498';
        $response_message = "Invalid token or token missing";
        $data = [];
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }

    /* chat stored */


    /* chat stored */

    public function chat_post() {
        $data = array();
        $history = array();
        $response_code = '-1';
        $response_message = '';

        $params = $this->post();
        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (!empty($token)) {
            if (!empty($params['to']) && !empty($params['content'])) {
                $user_data = array();


                $array = array();
                date_default_timezone_set('UTC');
                $date_time = date('Y-m-d H:i:s');
                $array['sender_token'] = $token;
                $array['receiver_token'] = $params['to'];
                $array['message'] = $params['content'];
                $array['utc_date_time'] = $date_time;
                $array['status'] = 1;
                $array['read_status'] = 0;
                date_default_timezone_set('Asia/Kolkata');
                $array['created_at'] = date('Y-m-d H:i:s');

                $history = $this->api->chat_conversation($array);
                $utctime = $history['utc_date_time'];
                $time = utc_date_conversion($history['utc_date_time']);
                $time = date('h:i A', strtotime($time));
                $history['date'] = date('Y-m-d', strtotime($time));
                $history['time'] = $time;

                $to_user_id = $history['receiver_token'];
                $from_user_id = $history['sender_token'];
                $message = $history['message'];

                $name = $this->api->username($from_user_id);
                $title = $name['name'];
                $from_userid = $name['id'];

                $name1 = $this->api->username($to_user_id);
                $to_username = $name1['name'];
                $to_userid = $name1['id'];

                $body = array(
                    'notification_type' => 'chat',
                    'title' => $title,
                    'message' => $message,
                    'from_username' => $title,
                    'to_username' => $to_username,
                    'from_userid' => $from_userid,
                    'to_userid' => $to_userid,
                    'date' => $history['created_at'],
                    'time' => $time,
                    'utctime' => $utctime
                );

                $is_provider = $this->api->get_user_id_using_token($params['to']);
                $is_user = $this->api->get_users_id_using_token($params['to']);
                if (!empty($is_user)) {
                    $device_tokens = $this->api->get_device_info_multiple($to_userid, 2);
                }
                if (!empty($is_provider)) {
                    $device_tokens = $this->api->get_device_info_multiple($to_userid, 1);
                }

                $deviceid = 0;


                $notificationdata = array();
                $notificationdata['body'] = $body;

                if (!empty($device_tokens)) {
                    foreach ($device_tokens as $key => $device) {
                        if (!empty($device['device_type']) && !empty($device['device_id'])) {

                            if (strtolower($device['device_type']) == 'android') {
                                $notify_structure = array(
                                    'title' => $title,
                                    'message' => $message,
                                    'image' => 'test22',
                                    'action' => 'test222',
                                    'action_destination' => 'test222',
                                );
                                sendFCMMessage($notify_structure, $device['device_id']);
                            }

                            if (strtolower($device['device_type'] == 'ios')) {

                                $notify_structure = array(
                                    'title' => $title,
                                    'alert' => $message,
                                    'badge' => 0,
                                    'sound' => 'default',
                                );
                                sendApnsMessage($notify_structure, $device['device_id']);
                            }
                        }
                    }
                }

                if (!empty($history)) {
                    $response_code = '200';
                    $response_message = 'Chats Fetched Successfully...';
                    $history = $history;
                } else {
                    $response_code = '500';
                    $response_message = 'Chats are Empty...';
                    $history = [];
                }
            } else {
                $response_code = '500';
                $response_message = 'Some Fields are Missing..';
                $history = [];
            }

            $result = $this->data_format($response_code, $response_message, $history);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function chat_details_post() {
        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        if (!empty($token)) {
            $data = array();
            $response_code = '-1';

            $params = $this->post();

            if (!empty($params['chat_id']) && !empty($params['page'])) {
                $user_data = array();
                $user_data['token'] = $token;

                $id = $params['chat_id'];
                $page = $params['page'];
                $user_id = $this->api->get_user_id_using_token($user_data['token']);
                $history = $this->api->conversations($id, $user_id, $page);

                if (!empty($history)) {
                    $response_code = '200';
                    $response_message = 'Successfully Fetched....';
                    $data = $history;
                }
            } else {
                $response_code = '500';
                $response_message = "Field is Missing...!";
                $data = [];
            }

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function insert_message_post() {
        extract($_POST);

        $data = array(
            "sender_token" => $fromToken,
            "receiver_token" => $toToken,
            "message" => $content,
            "status" => 1,
            "read_status" => 0,
            "created_at" => date('Y-m-d H:i:s'),
        );
        $val = $this->api->insert_msg($data);
        if ($val) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /* get chat list */

    public function get_chat_list_post() {

        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        if (!empty($token)) {
            $sent = [];
            $receive = [];
            $sent = $this->db->select('receiver_token as token')->
                            from('chat_table')->
                            where('sender_token', $token)->
                            get()->result_array();
            $receive = $this->db->select('sender_token as token')->
                            from('chat_table')->
                            where('receiver_token', $token)->
                            get()->result_array();

            $chat_tokens = (array_merge($sent, $receive));
            $test = [];
            foreach ($chat_tokens as $key => $value) {
                $test[] = $value['token'];
            }

            $token_detail = [];
            foreach (array_unique($test) as $key => $value) {

                $token_detail[] = $this->api->get_chat_list_info($value);
            }

            $response_code = '200';
            $response_message = "successfully fetched...!";
            $data = $token_detail;


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    /* get chat history */

    public function get_chat_history_post() {



        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }


        $user_data = $this->post();
        if (!empty($token) && !empty($user_data['to_token'])) {
            $data['chat_history'] = $this->api->get_conversation_info($token, $user_data['to_token']);

            $response_code = '200';
            $response_message = "successfully fetched...!";
            $data = $data;


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    /* get flash device token */

    public function flash_device_token_post() {
        $user_post_data = getallheaders();

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        $user_data = $this->post();
        if ($token != '8338d6ff4f0878b222f312494c1621a9') {
            if (!empty($token) && !empty($user_data['device_token']) && !empty($user_data['device_type'])) {
                $ret = $this->api->is_check_divesToken($user_data['device_token']);
                if ($ret) {
                    $user_id = $this->api->get_token_info($token)->id;

                    if (!empty($user_id) && !empty($user_data['device_token']) && !empty($user_data['device_type'])) {
                        $data = array('user_id' => $user_id, 'user_token' => $token, 'device_token' => $user_data['device_token'], 'device_type' => $user_data['device_type']);
                        $get_user_id = $this->api->insert_device_info($data); /* base on user_type */

                        $response_code = '200';
                        $response_message = "successfully fetched...!";
                        $data = [];
                    } else {
                        $response_code = '200';
                        $response_message = "User & Provider Empty...!";
                        $data = [];
                    }



                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                } else {
                    $response_code = '200';
                    $response_message = "already Inserted...!";
                    $data = [];
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
                }
            } else {
                $this->token_error();
            }
        } else {
            $response_code = '201';
            $response_message = "This is Static Key";
            $data = [];

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    /* get notification list */

    public function get_notification_list_get() {

        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }

        if (!empty($token)) {
            $data['notification_list'] = $this->api->get_notification_info($token);
            $response_code = '200';
            $response_message = "successfully fetched...!";


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function send_push_notification($token, $service_id, $type, $msg = '') {
        $data = $this->api->get_book_info($service_id);
        if (!empty($data)) {
            if ($type == 1) {
                $device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 1);
            } else {
                $device_tokens = $this->api->get_device_info_multiple($data['user_id'], 2);
            }
            if ($type == 2) {
                $user_info = $this->api->get_user_info($data['user_id'], $type);
            } else {
                $user_info = $this->api->get_user_info($data['provider_id'], $type);
            }

            /* insert notification */

            $msg = ucfirst($user_info['name']) . ' ' . strtolower($msg);
            if (!empty($user_info['token'])) {
                $this->api->insert_notification($token, $user_info['token'], $msg);
            }

            $title = $data['service_title'];


            if (!empty($device_tokens)) {
                foreach ($device_tokens as $key => $device) {
                    if (!empty($device['device_type']) && !empty($device['device_id'])) {

                        if (strtolower($device['device_type']) == 'android') {

                            $notify_structure = array(
                                'title' => $title,
                                'message' => $msg,
                                'image' => 'test22',
                                'action' => 'test222',
                                'action_destination' => 'test222',
                            );
                            sendFCMMessage($notify_structure, $device['device_id']);
                        }

                        if (strtolower($device['device_type'] == 'ios')) {
                            $notify_structure = array(
                                'alert' => $msg,
                                'sound' => 'default',
                                'badge' => 1
                            );


                            sendApnsMessage($notify_structure, $device['device_id']);
                        }
                    }
                }
            }


            /* apns push notification */
        } else {
            $this->token_error();
        }
    }

    /* get wallet amount */

    public function get_wallet_amt_post() {

        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (empty($token)) {
            $token = (!empty($_POST['token'])) ? $_POST['token'] : '';
        }

        /* get wallet */


        if (!empty($token)) {
            $data['wallet_info'] = $this->api->get_wallet($token);
            $response_code = '200';
            $response_message = "successfully fetched...!";


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    /* end with Wallet Info */



    /* push notification */

    public function send_push_notification_wallet($token, $type, $msg = '') {

        $data = $this->api->get_token_info($token);
        if (!empty($data)) {
            if ($type == 1) {
                $device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 1);
            } else {
                $device_tokens = $this->api->get_device_info_multiple($data['user_id'], 2);
            }
            if ($type == 2) {
                $user_info = $this->api->get_user_info($data['user_id'], $type);
            } else {
                $user_info = $this->api->get_user_info($data['provider_id'], $type);
            }

            /* insert notification */

            $msg = ucfirst($user_info['name']) . ' ' . strtolower($msg);
            if (!empty($user_info['token'])) {
                $this->api->insert_notification($token, $user_info['token'], $msg);
            }

            $title = $data['service_title'];


            if (!empty($device_tokens)) {
                foreach ($device_tokens as $key => $device) {
                    if (!empty($device['device_type']) && !empty($device['device_id'])) {

                        if (strtolower($device['device_type']) == 'android') {

                            $notify_structure = array(
                                'title' => $title,
                                'message' => $msg,
                                'image' => 'test22',
                                'action' => 'test222',
                                'action_destination' => 'test222',
                            );

                            sendFCMMessage($notify_structure, $device['device_id']);
                        }

                        if (strtolower($device['device_type'] == 'ios')) {
                            $notify_structure = array(
                                'alert' => $msg,
                                'sound' => 'default',
                                'badge' => 0,
                            );


                            sendApnsMessage($notify_structure, $device['device_id']);
                        }
                    }
                }
            }


            /* apns push notification */
        } else {
            $this->token_error();
        }
    }

    /* Wallet */

    /* get wallet history */

    public function wallet_history_post() {
        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (!empty($token)) {
            $params = $this->post();
            $history = $this->api->get_wallet_history_info($token);
            $user_id=$this->api->get_user_id_using_token($token);
        
            if($user_id){
                $stripe=$this->api->getSingleData('stripe_bank_details',['count(*) as id'],['user_id'=>$user_id]);
            }
        
       
            $his = [];
            if (!empty($history)) {
                $val = $this->db->select('*')->from('wallet_table')->where('token', $token)->get()->row();
				if($val->type == '1'){
				$provider_currency = get_api_provider_currency($val->user_provider_id);
				$UserCurrency = $provider_currency['user_currency_code'];
				}else{
				$provider_currency = get_api_user_currency($val->user_provider_id);
				$UserCurrency = $provider_currency['user_currency_code'];
				}
                foreach ($history as $key => $value) {

                    $his[$key]['id'] = $value['id'];
                    $his[$key]['token'] = $value['token'];
                    $his[$key]['user_provider_id'] = $value['user_provider_id'];
                    $his[$key]['type'] = $value['type'];
                    $his[$key]['currency'] = currency_code_sign($UserCurrency);
                    $his[$key]['current_wallet'] = get_gigs_currency($value['current_wallet'], $value['currency_code'], $UserCurrency);
                    $his[$key]['credit_wallet'] = get_gigs_currency($value['credit_wallet'], $value['currency_code'], $UserCurrency);
                    $his[$key]['debit_wallet'] = get_gigs_currency(strval(abs($value['debit_wallet'])), $value['currency_code'], $UserCurrency);
                    $his[$key]['avail_wallet'] = get_gigs_currency($value['avail_wallet'], $value['currency_code'], $UserCurrency);
					if($value['payment_detail'] == 'paypal' || $value['payment_detail'] == 'razorpay'){
						$his[$key]['total_amt'] = get_gigs_currency(strval(abs($value['total_amt'])), $value['currency_code'], $UserCurrency);
						$his[$key]['txt_amt'] = get_gigs_currency(strval(abs($value['fee_amt'])), $value['currency_code'], $UserCurrency);						
					}else{
						$his[$key]['total_amt'] = get_gigs_currency(strval(abs($value['total_amt'] / 100)), $value['currency_code'], $UserCurrency);
						$his[$key]['txt_amt'] = get_gigs_currency(strval(abs($value['fee_amt'] / 100)), $value['currency_code'], $UserCurrency);						
					}
                    $his[$key]['reason'] = $value['reason'];
                    $his[$key]['created_at'] = $value['created_at'];
                }
                $response_code = '200';
                $response_message = 'Fetched successfully...';
                $data['wallet_info'] = array();
                $data['wallet_info']['wallet'] = (object) $this->api->get_wallet($token);
                $data['wallet_info']['wallet_history'] = $his;
                $data['stripe_bank'] = (string) ($stripe->id) ? $stripe->id : "0";
            } else {
                $response_code = '200';
                $response_message = 'Fetched successfully...';
                $data['wallet_info'] = array();
                $data['wallet_info']['wallet'] = (object) $this->api->get_wallet($token);
                $data['wallet_info']['wallet_history'] = [];
            }

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    /* Add wallet amount */

    public function add_user_wallet_post() {

        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        $params = $this->post();
        if (empty($token)) {
            $token = (!empty($params['Token'])) ? $params['Token'] : '';
        }
        if (!empty($token)) {
            $params = $this->post();
			//
			
			
			if($params['paytype'] == 'stripe'){

				if (!empty($params['amount']) && !empty($params['tokenid']) && $params['amount'] > 0) {

					$check_card = $this->db->get_where('stripe_customer_table', array('user_token' => $token))->row();
					
					if (!empty($check_card->user_token) && $check_card->user_token == $token) {
						/* create card info based on customer */

						$cust_info = $this->stripe->retrieve_customer($check_card->cust_id, $this->data['secret_key']);
						
						if (!empty($cust_info)) {

							$data['source'] = $params['tokenid']; /* The type of payment source. Should be card. */
							//$data['name'] = $params['tokenid'];
							


							/* create customern in stripe */
							$create_cust = $this->stripe->create_card($data, $check_card->cust_id);
							
							
							$card_data = json_decode($create_cust);
							if (!empty($card_data) && !empty($card_data->id)) {
								$card_info['user_token'] = $token;
								$card_info['stripe_token'] = $params['tokenid'];
								$card_info['cust_id'] = $check_card->cust_id;
								$card_info['card_id'] = $card_data->id;
								$card_info['pay_type'] = $card_data->object;
								$card_info['brand'] = $card_data->brand;
								$card_info['cvc_check'] = $card_data->cvc_check;
								$card_info['card_number '] = $card_data->last4;
								$card_info['card_exp_month'] = $card_data->exp_month;
								$card_info['card_exp_year'] = $card_data->exp_year;
								$card_info['status'] = 1;
								$card_info['created_at'] = date('Y-m-d H:i:s');

								$vals = $this->db->insert('stripe_customer_card_details', $card_info);

								/* payment on stripe */
								// $charges_array = array();
								// $amount = $params['amount'];
								// $amount = ($amount * 100);
								// $charges_array['amount'] = $amount;
								// $charges_array['currency'] = $params['currency'];
								// $charges_array['customer'] = $card_info['cust_id'];
								// $charges_array['source'] = $card_info['card_id'];
								// $charges_array['expand'] = array('balance_transaction');
								
								
								$charges_array = array();
								$amount = $params['amount'];
								$amount = ($amount * 100);
								$charges_array['amount'] = $amount;
								//$charges_array['currency'] ='usd';
								$charges_array['currency'] =$params['currency'];
								$charges_array['customer'] = $card_info['cust_id'];
								$charges_array['source'] = $card_info['card_id'];
								$charges_array['expand'] = array('balance_transaction');
								//$charges_array['payment_method_types'] = array('card');

								$result = $this->stripe->stripe_charges($charges_array);
								

								$pay_info = json_decode($result);
								if ($vals) {
									$deleted = $this->stripe->delete_card($card_info['cust_id'], $card_info['card_id']);

									$delete_card = json_decode($deleted);

									if (empty($delete_card->error)) {
										$wallet_data['status'] = 0;
										$wallet_data['updated_on'] = date('Y-m-d H:i:s');
										$WHERE = array('cust_id' => $card_info['cust_id'], 'card_id' => $card_info['card_id']);
										$result = $this->api->update_customer_card($wallet_data, $WHERE);
									}
								}
								
								if (empty($pay_info->error)) {
									/* wallet infos */

									$user_info = $this->api->get_token_info($token);

									$wallet = $this->api->get_wallet($token);
									if(empty($wallet['wallet_amt']))
									{
										$wallet['wallet_amt']=0;
									}
									
									$curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], "USD");
									if($curren_wallet=='NAN')
									{
										$curren_wallet=0;
									}
									else
									{
										$curren_wallet=$curren_wallet;
									}

									/* wallet infos */


									$history_pay['token'] = $token;
									$history_pay['currency_code']="USD";
									$history_pay['user_provider_id'] = $user_info->id;
									$history_pay['type'] = $user_info->type;
									$history_pay['tokenid'] = $params['tokenid'];
									$history_pay['payment_detail'] = $result;
									$history_pay['charge_id'] = $pay_info->id;
									$history_pay['transaction_id'] = $pay_info->balance_transaction->id;
									$history_pay['exchange_rate'] = $pay_info->balance_transaction->exchange_rate;
									$history_pay['paid_status'] = $pay_info->paid;
									$history_pay['cust_id'] = $pay_info->source->customer;
									$history_pay['card_id'] = $pay_info->source->id;
									$history_pay['total_amt'] = $pay_info->balance_transaction->amount;
									$history_pay['fee_amt'] = $pay_info->balance_transaction->fee;
									$history_pay['net_amt'] = $pay_info->balance_transaction->net;
									$history_pay['amount_refund'] = $pay_info->amount_refunded;
									$history_pay['current_wallet'] = $curren_wallet;
									$history_pay['credit_wallet'] = (($pay_info->balance_transaction->net) / 100);
									$history_pay['debit_wallet'] = 0;
									$history_pay['avail_wallet'] = (($pay_info->balance_transaction->net) / 100) + $curren_wallet;
									$history_pay['reason'] = TOPUP;
									$history_pay['created_at'] = date('Y-m-d H:i:s');
									
									
									//echo "<pre>";print_r($history_pay);exit;

									if ($this->db->insert('wallet_transaction_history', $history_pay)) {
										/* update wallet table */
										$wallet_dat['currency_code']=$wallet['currency_code'];
										$wallet_dat['wallet_amt'] = get_gigs_currency(($curren_wallet + $history_pay['credit_wallet']),"USD",$wallet['currency_code']);
										$wallet_dat['updated_on'] = date('Y-m-d H:i:s');
										$WHERE = array('token' => $token);
										$result = $this->api->update_wallet($wallet_dat, $WHERE);
										/* payment on stripe */
										$response_code = '200';
										$response_message = 'Amount added to wallet successfully';
										$data['data'] = 'Successfully added to wallet...';
									} else {
										$response_code = '200';
										$response_message = 'Stripe payment issue';
										$data['data'] = 'history issues';
									}
								} else {
									$response_code = '200';
									$response_message = 'Stripe payment issue';
									$data['data'] = [];
								}
							} else {
								$response_code = '200';
								$response_message = 'card not created by customer...';
								$data['data'] = $card_data->error;
							}
						} else {
							$response_code = '200';
							$response_message = 'Stripe payment issue';
							$data['error'] = 'Not stored in card info';
						}

						$result = $this->data_format($response_code, $response_message, $data);
						$this->response($result, REST_Controller::HTTP_OK);
					} else {

						/* create new customer and card info */
						$user_info = $this->api->get_token_info($token);

						$data['email'] = $user_info->email;
						$data['source'] = $params['tokenid'];
						$create_cust = $this->stripe->customer_create($data);

						$cust = json_decode($create_cust);
						if (empty($cust->error)) {
							$cr_stripe_cust['cust_id'] = $cust->id;
							$cr_stripe_cust['user_token'] = $token;
							$cr_stripe_cust['created_at'] = date('Y-m-d H:i:s');

							if ($this->db->insert('stripe_customer_table', $cr_stripe_cust)) {
								if (!empty($cust->sources)) {
									foreach ($cust->sources->data as $key => $value) {
										$card_info['user_token'] = $token;
										$card_info['stripe_token'] = $params['tokenid'];
										$card_info['cust_id'] = $value->customer;
										$card_info['card_id'] = $value->id;
										$card_info['pay_type'] = $value->object;
										$card_info['brand'] = $value->brand;
										$card_info['cvc_check'] = $value->cvc_check;
										$card_info['card_number '] = $value->last4;
										$card_info['card_exp_month'] = $value->exp_month;
										$card_info['card_exp_year'] = $value->exp_year;
										$card_info['status'] = 1;
										$card_info['created_at'] = date('Y-m-d H:i:s');

										$vals = $this->db->insert('stripe_customer_card_details', $card_info);
									}
								}
							}
							/* create payment in stripe in stripe */

							/* payment on stripe */
							// $charges_array = array();
							// $amount = $params['amount'];
							// $amount = ($amount * 100);
							// $charges_array['amount'] = $amount;
							// $charges_array['currency'] = $params['currency'];
							// $charges_array['customer'] = $card_info['cust_id'];
							// $charges_array['source'] = $card_info['card_id'];
							// $charges_array['expand'] = array('balance_transaction');
							
							
							$charges_array = array();
							$amount = $params['amount'];
							$amount = ($amount * 100);
							$charges_array['amount'] = $amount;
							//$charges_array['currency'] ='usd';
							$charges_array['currency'] =$params['currency'];
							$charges_array['customer'] = $card_info['cust_id'];
							$charges_array['source'] = $card_info['card_id'];
							$charges_array['expand'] = array('balance_transaction');
							//$charges_array['payment_method_types'] = array('card');

							$result = $this->stripe->stripe_charges($charges_array);


							$pay_info = json_decode($result);//echo "<pre>";print_r($pay_info);exit;

							if ($vals) {
								/* delete card */
								$deleted = $this->stripe->delete_card($card_info['cust_id'], $card_info['card_id']); //remove card
								$delete_card = json_decode($deleted);

								if (empty($delete_card->error)) {
									$wallet_data['status'] = 0;
									$wallet_data['updated_on'] = date('Y-m-d H:i:s');
									$WHERE = array('cust_id' => $card_info['cust_id'], 'card_id' => $card_info['card_id']);
									$result = $this->api->update_customer_card($wallet_data, $WHERE);
								}
							}
							 if (empty($pay_info->error)) {
									/* wallet infos */

									$user_info = $this->api->get_token_info($token);

									$wallet = $this->api->get_wallet($token);
									if(empty($wallet['wallet_amt']))
									{
										$wallet['wallet_amt']=0;
									}
									

									$curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], "USD");
									if($curren_wallet=='NAN')
									{
										$curren_wallet=0;
									}
									else
									{
										$curren_wallet=$curren_wallet;
									}

									/* wallet infos */


									$history_pay['token'] = $token;
									$history_pay['currency_code']="USD";
									$history_pay['user_provider_id'] = $user_info->id;
									$history_pay['type'] = $user_info->type;
									$history_pay['tokenid'] = $params['tokenid'];
									$history_pay['payment_detail'] = $result;
									$history_pay['charge_id'] = $pay_info->id;
									$history_pay['transaction_id'] = $pay_info->balance_transaction->id;
									$history_pay['exchange_rate'] = $pay_info->balance_transaction->exchange_rate;
									$history_pay['paid_status'] = $pay_info->paid;
									$history_pay['cust_id'] = $pay_info->source->customer;
									$history_pay['card_id'] = $pay_info->source->id;
									$history_pay['total_amt'] = $pay_info->balance_transaction->amount;
									$history_pay['fee_amt'] = $pay_info->balance_transaction->fee;
									$history_pay['net_amt'] = $pay_info->balance_transaction->net;
									$history_pay['amount_refund'] = $pay_info->amount_refunded;
									$history_pay['current_wallet'] = $curren_wallet;
									$history_pay['credit_wallet'] = (($pay_info->balance_transaction->net) / 100);
									$history_pay['debit_wallet'] = 0;
									$history_pay['avail_wallet'] = (($pay_info->balance_transaction->net) / 100) + $curren_wallet;
									$history_pay['reason'] = TOPUP;
									$history_pay['created_at'] = date('Y-m-d H:i:s');

									if ($this->db->insert('wallet_transaction_history', $history_pay)) {
										/* update wallet table */
										$wallet_dat['currency_code']=$wallet['currency_code'];
										$wallet_dat['wallet_amt'] = get_gigs_currency(($curren_wallet + $history_pay['credit_wallet']),"USD",$wallet['currency_code']);
										$wallet_dat['updated_on'] = date('Y-m-d H:i:s');
										$WHERE = array('token' => $token);
										$result = $this->api->update_wallet($wallet_dat, $WHERE);
										/* payment on stripe */
										$response_code = '200';
										$response_message = 'Amount added to wallet successfully';
										$data['data'] = 'Successfully added to wallet...';
									} else {
										$response_code = '200';
										$response_message = 'Stripe payment issue';
										$data['data'] = 'history issues';
									}
								} else {
								$response_code = '200';
								$response_message = 'Stripe payment issue';
								$data['data'] = 'history issues';
							}
						} else {
							$response_code = '400';
							$response_message = 'This token Already Used...';
							$data['data'] = 'token already used...';
						}
					}


					$result = $this->data_format($response_code, $response_message, $data);
					$this->response($result, REST_Controller::HTTP_OK);
				} else {
					$response_code = '200';
					$response_message = 'Stripe payment issue';
					$data['error'] = $result['error'];

					$result = $this->data_format($response_code, $response_message, $data);
					$this->response($result, REST_Controller::HTTP_OK);
				}
			}
			else if($params['paytype'] == 'razorpay'){				
				$usertoken     = $params['token'];
				$user          = $this->db->where('token', $token)->get('users')->row_array();
				//print_r($params['amount']);
				//$params        = $this->input->get();
				$token         = $this->session->userdata('chat_token');
				$user_id       = $user['id'];
				$user_name     = $user['name'];
				$user_token    = $user['token'];
				$currency_type = $user['currency_code'];
				$amt = $params['amount'];
				$wallet = $this->db->where('user_provider_id', $user_id)->limit(1)->order_by('id',"DESC")->get('wallet_table')->row_array();
				
				$wallet_amt = $wallet['wallet_amt'];
				if($wallet_amt){
					$current_wallet = $wallet_amt;
				}else{
					$current_wallet = $amt;
				}	 				
				$history_pay['token']=$user_token;
				$history_pay['currency_code'] = $currency_type;
				$history_pay['user_provider_id']=$user_id;
				$history_pay['type']='2';
				$history_pay['tokenid']=$user_post_data['tokenid'];
				$history_pay['payment_detail']="Razorpay";
				$history_pay['charge_id']=1;
				$history_pay['exchange_rate']=0;
				$history_pay['paid_status']="pass";
				$history_pay['cust_id']="self";
				$history_pay['card_id']="self";
				$history_pay['total_amt']=$amt;
				$history_pay['fee_amt']=0;
				$history_pay['net_amt']=$amt*100;
				$history_pay['amount_refund']=0;
				$history_pay['current_wallet']=$current_wallet;
				$history_pay['credit_wallet']=$amt;
				$history_pay['debit_wallet']=0;
				$history_pay['avail_wallet']=$amt + $current_wallet;
				$history_pay['reason']=TOPUP;
				$history_pay['created_at']=date('Y-m-d H:i:s');
				if($this->db->insert('wallet_transaction_history',$history_pay)){
					
					$this->db->where('user_provider_id', $user_id)->update('wallet_table', array(
						'currency_code' => $currency_type,
						//'wallet_amt' => $amt+$current_wallet
						'wallet_amt' => $amt+$current_wallet
					));
					$response_code = '200';
					$response_message = 'Amount added to wallet successfully';
					$data['data'] = 'Successfully added to wallet...';               
				}else{
					$response_code = '200';
					$response_message = 'RazorPay payment issue';
					$data['data'] = 'history issues';                
				}
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
			}
        } else {
            $this->token_error();
        }
    }

    /* get card based on customer */

    public function get_customer_saved_card_post() {
        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (!empty($token)) { //main loop
            $ret_val = $this->api->get_customer_based_card_list($token);

            if (!empty($ret_val)) {
                $response_code = '200';
                $response_message = 'fetched successfully';
                $data['data'] = $ret_val;
            } else {
                $response_code = '200';
                $response_message = 'data was empty...';
                $data['data'] = [];
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    /*provider withdrawal*/

    public function provider_wallet_withdrawal_post() {
        $params = $this->post();
        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (empty($token)) {
            $token = (!empty($params['Token'])) ? $params['Token'] : '';
        }
        if (!empty($token)) { //main loop
            if (!empty($params['amount']) && !empty($params['tokenid']) && $params['amount'] > 0) {
            $user_info = $this->api->get_token_info($token);
             $amount= get_gigs_currency($params['amount'],$params['currency_val'],"USD");
            $paypal_response = $this->api->paypal_payout($user_info->email,$amount) ;
            $trans_info = json_decode($paypal_response);
    
            if (!empty($trans_info->batch_header->payout_batch_id)) {
                $user_info = $this->api->get_token_info($token);
                $wallet = $this->api->get_wallet($token);
                $curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'],$params['currency_val']);
                /*wallet infos*/
                $history_pay['token'] = $token;
                $history_pay['user_provider_id'] = $user_info->id;
                $history_pay['currency_code']=$params['currency_val'];
                $history_pay['type'] = $user_info->type;
                $history_pay['tokenid'] = $params['tokenid'];
                $history_pay['payment_detail'] = $paypal_response; //response
                $history_pay['charge_id'] = $trans_info->batch_header->payout_batch_id;
                $history_pay['transaction_id'] = 0;
                $history_pay['exchange_rate'] = 0;
                $history_pay['paid_status'] = "Completed";
                $history_pay['cust_id'] = $trans_info->batch_header->payout_batch_id;
                $history_pay['card_id'] = 0;
                $history_pay['total_amt'] = $params['amount'];
                $history_pay['fee_amt'] = 0;
                $history_pay['net_amt'] = $params['amount']*100;
                $history_pay['amount_refund'] = 0;
                $history_pay['current_wallet'] = $curren_wallet;
                $history_pay['credit_wallet'] = 0;
                $history_pay['debit_wallet'] = (($params['amount']));
                $history_pay['avail_wallet'] = $curren_wallet - $params['amount'] ;
                $history_pay['reason'] = WITHDRAW;
                $history_pay['created_at'] = date('Y-m-d H:i:s');
                if ($this->db->insert('wallet_transaction_history', $history_pay)) {
                    /*update wallet table*/
                    $wallet_data['currency_code']=$wallet['currency_code'];
                    $wallet_data['wallet_amt'] = get_gigs_currency(($curren_wallet - $history_pay['debit_wallet']),$params['currency_val'],$wallet['currency_code']);
                    $wallet_data['updated_on'] = date('Y-m-d H:i:s');
                    $WHERE = array('token' => $token);
                    $result = $this->api->update_wallet($wallet_data, $WHERE);
                    /*payment on stripe*/
                    $response_code = '200';
                    $response_message = 'Amount transfered  successfully';
                    $data['data'] = 'Successfully added to wallet...';
                } else {
                    $response_code = '400';
                    $response_message = 'Stripe payment issue';
                    $data['data'] = 'history issues';
                }
            } else {
                $response_code = '400';
                $response_message = 'Wallet transaction not succeed...!';
                $data['data'] = 'payout failed';
            }
            /*transfer account via card*/
                        
                    /*transfer amount via card*/
                    $result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
            } else {
                $response_code = '400';
                $response_message = 'Invalid Email or information...';
                $data['data'] = [];
                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $this->token_error();
        }
    }
    /* provider card info */

    public function provider_card_info_post() {
        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (!empty($token)) { //main loop
            $ret_val = $this->api->get_provider_based_card_list($token);

            if (!empty($ret_val)) {
                $response_code = '200';
                $response_message = 'fetched successfully';
                $data['data'] = $ret_val;
            } else {
                $response_code = '200';
                $response_message = 'data was empty...';
                $data['data'] = [];
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function stripe_details_get() {

        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        if (!empty($token)) {

            $result = $this->api->token_is_valid_provider($token);
            if (empty($result)) {
                $result = $this->api->token_is_valid($token);
            }

            if ($result) {

                $publishable_key = '';
                $secret_key = '';
                $live_publishable_key = '';
                $live_secret_key = '';
                $stripe_option = '';
				$razorpay_apikey = '';
				$razorpay_secret_key = '';
				$live_razorpay_apikey = '';
				$live_razorpay_secret_key = '';

                $query = $this->db->query("select * from system_settings WHERE status = 1");
                $result = $query->result_array();
                if (!empty($result)) {
                    foreach ($result as $datas) {


                        if ($datas['key'] == 'secret_key') {

                            $secret_key = $datas['value'];
                        }

                        if ($datas['key'] == 'publishable_key') {

                            $publishable_key = $datas['value'];
                        }

                        if ($datas['key'] == 'live_secret_key') {

                            $live_secret_key = $datas['value'];
                        }

                        if ($datas['key'] == 'live_publishable_key') {

                            $live_publishable_key = $datas['value'];
                        }

                        if ($datas['key'] == 'stripe_option') {

                            $stripe_option = $datas['value'];
                        }
                    
                        if ($datas['key'] == 'paypal_option') {

                            $paypal_option = $datas['value'];
                        }
                    
                        if ($datas['key'] == 'paytab_option') {

                            $paytab_option = $datas['value'];
                        }
						
						if ($datas['key'] == 'razor_option') {

                            $razor_option = $datas['value'];
                        }
						
						if($datas['key'] == 'razorpay_apikey'){
							$razorpay_apikey = $datas['value'];
						}
						
						if($datas['key'] == 'razorpay_secret_key'){
							$razorpay_secret_key = $datas['value'];
						}
						
						if($datas['key'] == 'live_razorpay_apikey'){
							$live_razorpay_apikey = $datas['value'];
						}
						if($datas['key'] == 'live_razorpay_secret_key'){
							$live_razorpay_secret_key = $datas['value'];
						}
                    }
                }
                //Stripe
                $stripedetails = array();
                if(!empty($stripe_option))
				{
					$stripe_option=1;
				}(string)

				$stripedetails['stripe_option'] = (string) $stripe_option;
                if (@$stripe_option == 1) {

                    $stripedetails['publishable_key'] = $publishable_key;
                    $stripedetails['secret_key'] = $secret_key;
                }

                if (@$stripe_option == 2) {
                    $stripedetails['publishable_key'] = $live_publishable_key;
                    $stripedetails['secret_key'] = $live_secret_key;
                }

				if(@$razor_option == 1){
					$stripedetails['razorpay_apikey'] = $razorpay_apikey;
					$stripedetails['razorpay_secret_key'] = $razorpay_secret_key;
				}
				
				if(@$razor_option == 2){
					$stripedetails['razorpay_apikey'] = $live_razorpay_apikey;
					$stripedetails['razorpay_secret_key'] = $live_razorpay_secret_key;
				}
				if(!empty($paypal_option))
				{
					$paypal_option="1";
				}
				
				/*if(!empty($razor_option))
				{
					$razor_option=1;
				}*/

				$moyaser_option = settingValue('moyasor_option_show');
				$moyasar_type = settingValue('moyaser_option');

				if($moyasar_type == 1) { //Sandbox
					$moyasar_api_key = settingValue('moyaser_apikey');
					$moyasar_secret_key = settingValue('moyaser_secret_key');
				} else { // Live
					$moyasar_api_key = settingValue('live_moyaser_apikey');
					$moyasar_secret_key = settingValue('live_moyaser_secret_key');
				}

               /* $stripedetails['paypal_option'] = $paypal_option;
                $stripedetails['stripe_option'] = $stripe_option;
                $stripedetails['razor_option'] = $razor_option;
				$stripedetails['braintree_key'] = settingValue('braintree_key');*/

				//Paypal
				$stripedetails['paypal_option'] = $paypal_option;
				$stripedetails['braintree_key'] = settingValue('braintree_key');


				//Razor
				//$stripedetails['razor_option'] = $razor_option;

				//Moyasar Pay
				$stripedetails['moyasor_option'] = (string) $moyaser_option;
				$stripedetails['moyasor_type'] = $moyasar_type;
				$stripedetails['moyasor_api_key'] = $moyasar_api_key;
				$stripedetails['moyasor_secret_key'] = $moyasar_secret_key;

                if (!empty($stripedetails)) {
                    $response_code = '200';
                    $response_message = 'Fetched successfully...';
                    $data = $stripedetails;
                } else {
                    $response_code = '200';
                    $response_message = 'Fetched successfully...';
                    $data = [];
                }
            } else {

                $response_code = "500";
                $response_message = "Token is Invalid";
                $data = [];
            }
        } else {
            $this->token_error();
            $data = [];
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function update_myservice_status_post() {
        $user_data = array();
        $user_data = $this->post();

        $user_post_data = getallheaders();
        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }
        //check status
        if ($user_data['status'] != 1 && $user_data['status'] != 2) {
            $response_code = "200";
            $response_message = "Service Status is Invalid.";
            $data = [];
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
        //end
        if (!empty($token)) {
            $result = $this->api->token_is_valid_provider($token);
            if ($result) {

                if (!empty($user_data['service_id']) && !empty($user_data['status'])) {

                    $provider = $this->db->where('token=', $token)->get('providers')->row_array();

                    $service_booking = $this->api->get_service_bookingdetails($provider['id'], $user_data['service_id']);
                    if ($service_booking == 0) {

                        $service_update = $this->db->where('id', $user_data['service_id'])->update('services', ['status' => $user_data['status']]);

                        if ($service_update == true) {
                            $response_code = "200";
                            $response_message = "Service Status Updated Successfully.";
                            $data = [];
                        } else {
                            $response_code = "500";
                            $response_message = "Service Status Not Update.";
                            $data = [];
                        }
                    } else {
                        $response_code = "500";
                        $response_message = "This Service is already Booked and status not changed.";
                        $data = [];
                    }
                } else {
                    $response_code = "500";
                    $response_message = "Some fields are Missing";
                    $data = [];
                }
            } else {
                $response_code = "500";
                $response_message = "Token is Invalid";
                $data = [];
            }
        } else {
            $this->token_error();
            $data = [];
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }

    public function language_list_get() {
        $user_data = array();
        $user_data = $this->post();

        $data = array();

        $result = $this->api->languages_list();

        if (!empty($result)) {

            $response_code = '200';
            $response_message = $this->language_content['lg_success'];
            $data = $result;

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $response_code = '404';
            $status = FALSE;
            $response_message = $this->language_content['lg_no_language_were_found'];
            $data = [];

            $result = $this->data_format($response_code, $status, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function language_post() {
        $user_data = array();
        $user_data = $this->post();


        $data = array();


        if (!empty($user_data['language'])) {

            $result = $this->api->language_list($user_data['language']);

            if (!empty($result)) {

                $response_code = '200';
                $response_message = $this->language_content['lg_success'];
                $data = $result;

                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } else {

                $response_code = '404';
                $response_message = $this->language_content['lg_no_language_were_found'];
                $data = [];

                $result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            }
        } else {
            $response_code = '404';
            $response_message = $this->language_content['lg_no_language_were_found'];
            $data = [];

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function currency_list_get() {

        $user_data = array();
        $user_data = $this->post();

        $data = array();

        $result = $this->db->where('status', 1)->select('id,currency_code')->get('currency_rate')->result_array();
        // print_r($results);exit;

        if (!empty($result)) {


            $response_code = '200';
            $response_message = $this->language_content['lg_success'];
            $data = $result;

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {

            $response_code = '404';
            $response_message = $this->language_content['lg_no_language_were_found'];
            $data = [];

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }

    public function stripe_account_details_post() {

        $data = new stdClass();
        $user_data = array();
        $user_data = $this->post();
        $user_post_data = getallheaders();

//        print_r($user_data);exit;

        $token = (!empty($user_post_data['token'])) ? $user_post_data['token'] : '';
        if (empty($token)) {
            $token = (!empty($user_post_data['Token'])) ? $user_post_data['Token'] : '';
        }

        $data = array();
        $response_code = '500';
        $response_message = 'Validation error';

        if (!empty($token)) {

            if (!empty($user_data['account_holder_name']) && !empty($user_data['account_number']) && !empty($user_data['account_iban']) && !empty($user_data['bank_name']) && !empty($user_data['bank_address']) && (!empty($user_data['sort_code']) || !empty($user_data['routing_number']) || !empty($user_data['account_ifsc']))) {

                $user_id = $this->api->get_user_id_using_token($token);
                $WHERE = array('id' => $user_id);
                $whr = array('user_id' => $user_id);

                $result = $this->api->update_data('providers', $user_data, $WHERE);
                $count = $this->api->CountRows('stripe_bank_details', $whr);
                if (!empty($count)) {
                    $stripe = $this->api->update_data('stripe_bank_details', $user_data, $whr);
                } else {
                    $user_data['user_id'] = $user_id;
                    $stripe = $this->api->update_data('stripe_bank_details', $user_data);
                }

                if ($result) {
                    $response_code = '200';
                    $response_message = "Account details updated Successfully";
                } else {
                    $response_code = '200';
                    $response_message = "No Results found";
                }
            } else {
                $response_code = '200';
                $response_message = "Input field missing";
            }


            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function wallet_withdraw_post() {
        if ($this->user_id != 0 || ($this->default_toke == $this->api_token)) {
            $user_data = $this->post();
            $user_details = $this->db->where('token', $this->api_token)->get('providers')->row_array();
            $user_currency = get_api_provider_currency($this->user_id);
            if (!empty($user_data['amount'])) {
                if (empty($user_data['currency_code'])) {
                    $user_data['currency_code'] = $user_details['currency_code'];
                }
                if (!empty($user_data['amount']) && !empty($user_data['currency_code'])) {
                    $wallet_data = array(
                        'user_id' => $this->user_id,
                        'amount' => $user_data['amount'],
                        'currency_code' => $user_details['currency_code'],
                        'status' => 1,
                        'transaction_status' => 0,
                        'request_payment' => 'stripe',
                        'created_by' => $this->user_id
                    );
                    $amount = $this->db->insert('wallet_withdraw', $wallet_data);
//                                    print_r($amount);exit;
                    if ($amount == true) {
                        $amount_withdraw = $this->Stripe_model->wallet_withdraw_flow($user_data['amount'], $user_currency['user_currency_code'], $this->user_id, '');
                    }
                    if ($amount == true) {
                        $response_code = 200;
                        $response_message = 'SUCCESS';
                        $data = 'Successfully withdraw in wallet...';
                    } else {
                        $response_code = 404;
                        $response_message = $this->language_content['lg_something_is_wrong_please_try_again_later'];
                        $data = [];
                    }
                }
            } else {
                $response_code = 404;
                $response_message = $this->language_content['lg_input_params_missing'];
                $data = [];
            }
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function BraintreePaypal_post() {
        if ($this->api_token !='') {
            $user_data = array();
            $user_data = $this->post();			
            $data = array();
            if (!empty($user_data['amount']) && !empty($user_data['payload_nonce']) && !empty($user_data['orderID'])) {

                $amount = $user_data['amount'];
                $orderId = $user_data['orderID'];
                $payload_nonce = $user_data['payload_nonce'];
                require_once 'vendor/autoload.php';	
                require_once 'vendor/braintree/braintree_php/lib/Braintree.php';
                $gateway = new Braintree\Gateway([
                    'environment' => 'sandbox',
                    'merchantId' => 'pd6gznv7zbrx9hb8',
                    'publicKey' => 'h8bydrz7gcjkp7d4',
                    'privateKey' => '47b83ae8fdcf23342f71b21c1a9a6223'
                ]);

                if ($gateway) {

                    $result = $gateway->transaction()->sale([
                        'amount' => $amount,
                        'paymentMethodNonce' => $payload_nonce,
                        'orderId' => $orderId,
                        'options' => [
                        'submitForSettlement' => True
                        ],
                    ]);

                    if ($result->success) {
                        $transaction_id = $result->transaction->id;

                        $res = $this->paypal_success($amount, $orderId, $transaction_id, $this->api_token);
                        if (!empty($res)) {

                            $response_code = '200';
                            $response_message = $this->language_content['lg_success'];
                            $data = $res;
                        } else {

                            $response_code = '404';
                            $response_message = $this->language_content['lg_no_language_were_found'];
                            $data = [];
                        }
                    } else {
                        $response_code = '404';
                        $response_message = $this->language_content['lg_no_language_were_found'];
                        $data = [];
                    }
                } else {
                    $response_code = '404';
                    $response_message = $this->language_content['lg_no_language_were_found'];
                    $data = [];
                }
            }
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }

    public function paypal_success($amt, $orderId, $transaction_id, $token_id) {
        $token = $token_id;

        $user_info = $this->api->get_token_info($token);
        $wallet = $this->api->get_wallet($token);
        $curren_wallet = $wallet['wallet_amt'];
        /* wallet infos */
        $pay_data = array(
            'transaction_id' => $transaction_id,
            'order_id' => $orderId,
            'amount' => $amt,
            'user_id' => $user_info->id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $paypal = $this->db->insert('paypal_transaction', $pay_data);
        $pay_transaction = $this->db->insert_id();
        $history_pay['token'] = $token;
		$history_pay['currency_code'] = $wallet['currency_code'];
        $history_pay['user_provider_id'] = $user_info->id;
        $history_pay['type'] = $user_info->type;
        $history_pay['tokenid'] = $token;
        $history_pay['payment_detail'] = "paypal";
        $history_pay['charge_id'] = 1;
        $history_pay['transaction_id'] = $pay_transaction;
        $history_pay['exchange_rate'] = 0;
        $history_pay['paid_status'] = "pass";
        $history_pay['cust_id'] = "self";
        $history_pay['card_id'] = "self";
        $history_pay['total_amt'] = $amt;
        $history_pay['fee_amt'] = 0;
        $history_pay['net_amt'] = $amt;
        $history_pay['amount_refund'] = 0;
        $history_pay['current_wallet'] = $curren_wallet;
        $history_pay['credit_wallet'] = $amt;
        $history_pay['debit_wallet'] = 0;
        $history_pay['avail_wallet'] = $amt + $curren_wallet;
        $history_pay['reason'] = 'TOPUP';
        $history_pay['created_at'] = date('Y-m-d H:i:s');
		//echo '<pre>';print_r($history_pay);exit;
        if ($this->db->insert('wallet_transaction_history', $history_pay)) {
            /* update wallet table */
            $wallet_amt = $curren_wallet + $amt;
            $amt_wallent=get_gigs_currency($wallet_amt,$history_pay['currency_code'],$wallet['currency_code']);
            $wallet_dat['wallet_amt']= $amt_wallent;
            $wallet_dat['updated_on'] = date('Y-m-d H:i:s');
            $WHERE = array('token' => $token);
            $result = $this->api->update_wallet($wallet_dat, $WHERE);

            /* payment on stripe */

            return true;
        }
    }
    
    
    public function braintreeKey_get() {
        
        $data = array();
        
        $braintree_key=settingValue('braintree_key');
        
        if (!empty($braintree_key)) {

            $response_code = '200';
            $response_message = $this->language_content['lg_success'];
            $data['braintree_key'] = $braintree_key;

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $response_code = '404';
            $status = FALSE;
            $response_message = $this->language_content['lg_no_language_were_found'];
            $data = [];

            $result = $this->data_format($response_code, $status, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }
    }
		
	public function razorpay_details()
    {
        removeTag($this->post());
        $params        = $this->post();
        $user_id       = $this->session->userdata('id');
		$razor_option = settingValue('razor_option');
		if($razorpay_option == 1){			
			$apikey = settingValue('razorpay_apikey');
			$apisecret = settingValue('razorpay_secret_key');
		}else if($razorpay_option == 2){
			$apikey = settingValue('live_razorpay_apikey');
			$apisecret = settingValue('live_razorpay_secret_key');
		}
        $user_currency = 'INR';
        if (!empty($params)) { 
			$url = "https://api.razorpay.com/v1/contacts";
			$unique = strtoupper(uniqid());
			$data   = ' {
			  "name":"'.$params['name'].'",
			  "email":"'.$params['email'].'",
			  "contact":"'.$params['contact'].'",
			  "type":"employee",
			  "reference_id":"'.$unique.'",
			  "notes":{}
			}';
			$ch     = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
			$headers = array(
				'Content-Type:application/json'
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$result = curl_exec($ch);
			
			if (curl_errno($ch)) {
				$result = 'Error:' . curl_error($ch);
				echo json_encode(array(
                    'status' => false,
                    'msg' => $result
                ));
			}
			$results = json_decode($result);
			$user_id       = $this->session->userdata('id');
			$cnotes = $results->notes;
			$serializedcnotes = serialize($cnotes);
			$contact_data = array(
				'user_id' => $user_id,
				'rp_contactid' => $results->id,
				'entity' => $results->entity,
				'name' => $results->name,
				'contact' => $results->contact,
				'email' => $results->email,
				'type' => $results->type,
				'reference_id' => $results->reference_id,
				'batch_id' => $results->batch_id,
				'active' => $results->active,
				'accountnumber' => $params['accountnumber'],
				'mode' => $params['mode'],
				'purpose' => $params['purpose'],
				'notes' => $serializedcnotes,
				'created_at' => $results->created_at
			);
			$createcontact = $this->db->insert('razorpay_contact', $contact_data);
			if(!empty($createcontact)){
				$faurl = "https://api.razorpay.com/v1/fund_accounts";
				$faunique = strtoupper(uniqid());
				$fadata   = ' {
				  "contact_id": "'.$results->id.'",
				  "account_type": "bank_account",
				  "bank_account": {
					"name": "'.$params['bank_name'].'",
					"ifsc": "'.$params['ifsc'].'",
					"account_number":"'.$params['accountnumber'].'"
				  }
				}';
								
				$fach     = curl_init();
				curl_setopt($fach, CURLOPT_URL, $faurl);
				curl_setopt($fach, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($fach, CURLOPT_POSTFIELDS, $fadata);
				curl_setopt($fach, CURLOPT_POST, 1);
				curl_setopt($fach, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
				$faheaders = array(
					'Content-Type:application/json'
				);
				curl_setopt($fach, CURLOPT_HTTPHEADER, $faheaders);
				$faresult = curl_exec($fach);
				
				if (curl_errno($fach)) {
					$faresult = 'Error:' . curl_error($fach);
					echo json_encode(array(
						'status' => false,
						'msg' => $faresult
					));
				}
				$faresults = json_decode($faresult);
				
				$fa_data = array(
					'fund_account_id' => $faresults->id,
					'entity' => $faresults->entity,
					'contact_id' => $faresults->contact_id,
					'account_type' => $faresults->account_type,
					'ifsc' => $faresults->bank_account->ifsc,
					'bank_name' => $faresults->bank_account->bank_name,
					'name' => $faresults->bank_account->name,
					'account_number' => $faresults->bank_account->account_number,
					'active' => $faresults->active,
					'batch_id' => $faresults->batch_id,
					'created_at' => $faresults->created_at
				);
				$facreatecontact = $this->db->insert('razorpay_fund_account', $fa_data);
				
				if($facreatecontact){
					$purl = "https://api.razorpay.com/v1/payouts";
					$punique = strtoupper(uniqid());
					$pdata   = ' {
					  "account_number": "2323230032510196",
					  "fund_account_id": "'.$faresults->id.'",
					  "amount": "'.$params['amount'].'",
					  "currency": "INR",
					  "mode": "'.$params['mode'].'",
					  "purpose": "'.$params['purpose'].'",
					  "queue_if_low_balance": true,
					  "reference_id": "'.$punique.'",
					  "narration": "",
					  "notes": {}
					}';
					
					$pch     = curl_init();
					curl_setopt($pch, CURLOPT_URL, $purl);
					curl_setopt($pch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($pch, CURLOPT_POSTFIELDS, $pdata);
					curl_setopt($pch, CURLOPT_POST, 1);
					curl_setopt($pch, CURLOPT_USERPWD, $apikey . ":" . $apisecret);
					$pheaders = array(
						'Content-Type:application/json'
					);
					curl_setopt($pch, CURLOPT_HTTPHEADER, $pheaders);
					$presult = curl_exec($pch);
					
					if (curl_errno($pch)) {
						$presult = 'Error:' . curl_error($pch);						
						echo json_encode(array(
							'status' => false,
							'msg' => $presult
						));
					}
					$presults = json_decode($presult);
					
					$pydata = array(
						'payout_id' => $presults->id,
						'entity' => $presults->entity,
						'fund_account_id' => $presults->fund_account_id,
						'amount' => $presults->amount,
						'currency' => $presults->currency,
						'fees' => $presults->fees,
						'tax' => $presults->tax,
						'status' => $presults->status,
						'utr' => $presults->utr,
						'mode' => $presults->mode,
						'purpose' => $presults->purpose,						
						'reference_id' => $presults->reference_id,
						'narration' => $presults->narration,
						'batch_id' => $presults->batch_id,
						'failure_reason' => $presults->failure_reason,
						'created_at' => $presults->created_at
					);
					$payouts = $this->db->insert('razorpay_payouts', $pydata);
					if($payouts){
						$wdata = array(
							'user_id' => $user_id,
							'amount' => $presults->amount,
							'currency_code' => $presults->currency,
							'transaction_status' => 1,
							'transaction_date' => date('Y-m-d'),
							'request_payment' => 'RazorPay',
							'status' => 1,
							'created_by' => $user_id,
							'created_at' => $presults->created_at
						);
						$payoutins = $this->db->insert('wallet_withdraw', $wdata);
						if($payoutins){
							$amount        = $presults->amount;
							$user_id       = $this->session->userdata('id');
							$user          = $this->db->where('id', $user_id)->get('providers')->row_array();
							$user_name     = $user['name'];
							$user_token    = $user['token'];
							$currency_type = $user['currency_code'];
							$wallet = $this->db->where('user_provider_id', $user_id)->get('wallet_table')->row_array();
							$wallet_amt = $wallet['wallet_amt'];
							$history_pay['token']=$user_token;
							$history_pay['user_provider_id']=$user_id;
							$history_pay['currency_code']='INR';
							$history_pay['transaction_id']=$presults->id;
							$history_pay['paid_status']='1';
							$history_pay['total_amt']=$presults->amount;
							if($wallet_amt){
								$current_wallet = $wallet_amt-$amount;
							}else{
								$current_wallet = $amount;
							}
							$history_pay['current_wallet']=$current_wallet;
							$history_pay['reason']='Withdrawn Wallet Amt';
							$history_pay['created_at']=date('Y-m-d H:i:s');
							if($this->db->insert('wallet_transaction_history',$history_pay)){								
								$this->db->where('user_provider_id', $user_id)->update('wallet_table', array(
									'currency_code' => 'INR',
									'wallet_amt' => $current_wallet
								));								               
							}
							$message = "Amount Withdrawn Successfully";
							echo json_encode(array(
								'status' => true,
								'msg' => $message
							));
						}else{
							$message = "Payout details not Inserted";
							echo json_encode(array(
								'status' => false,
								'msg' => $message
							));
						}
					}else{
						$message = "Payout details not Inserted";
						echo json_encode(array(
							'status' => false,
							'msg' => $message
						));
					}
				} 
			}
        }else{
			$message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
            echo json_encode(array(
                    'status' => false,
                    'msg' => $message
                ));
		}
    }

    //Rintu
    public function service_offer_history_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$total = $this->api->getCountRows('service_offers',['status'=>0, 'df'=>0, 'provider_id'=>$this->user_id],'id', '', array());
    		//echo "<pre>"; print_r($end); exit;
    		$where = array('service_offers.status'=>0, 'service_offers.df'=>0, 'service_offers.provider_id'=>$this->user_id);
            $result = $this->api->service_offer_history($user_data['per_page'], $end, $where);
         	//echo "<pre>"; print_r($result); exit;
            if (!empty($result)) 
            {
                $provider_currency = get_api_provider_currency($this->user_id);
                $ProviderCurrency = $provider_currency['user_currency_code'];
                foreach ($result as $details) 
                {
                
                	$service_amount=(!empty($ProviderCurrency) && $details['currency_code'] != '') ? get_gigs_currency($details['service_amount'], $details['currency_code'], $ProviderCurrency) : $details['service_amount'];
                    $offer = $this->api->check_current_offer(['service_id'=>$details['id'], 'status'=>0, 'df'=>0]);
                    $res['id'] = $details['id'];
                    $res['service_title'] = $details['service_title'];
                    $res['final_amount'] =  (string) $service_amount;
                    $res['currency_code'] = $ProviderCurrency;
                    $res['currency'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                    $res['start_date'] = date("d-m-Y", strtotime($details['start_date']));
                    $res['end_date'] = date("d-m-Y", strtotime($details['end_date']));
                    $res['start_time'] = date("h:i A", strtotime($details['start_time']));
                    $res['end_time'] = date("h:i A", strtotime($details['end_time']));
                    $res['offer_percentage'] = $details['offer_percentage'];
                    $res['createdDate'] = $details['createdDate'];
                    $offrAmt = $details['service_amount'] * ($details['offer_percentage']/100);
                    $res['service_amount'] = $details['service_amount'] - $offrAmt;
                    $res['current_offer'] = $details['offer_percentage'];
                    $res['end_time'] = date("h:i A", strtotime($details['end_time']));
                    
                    $results[] = $res;
                }
            }

            if ($result) {
                $response_code = '200';
                $response_message = 'Service Offer History Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['service_offer_list'] = $results;
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function service_offer_history_details_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('status'=>0, 'df'=>0, 'provider_id'=>$this->user_id, 'id'=>$_GET['id']);
                $service_details = $this->api->getsingletabledata('service_offers', $where, '', 'id', 'asc', 'single');
            	
                //echo "<pre>"; print_r($service_details); exit;
                if (!empty($service_details)) 
                {
                    $response_code = '200';
                    $response_message = "Service Offer Details";
                    $data['id'] = $service_details['id'];
                    $data['start_date'] = date("d-m-Y", strtotime($service_details['start_date']));
                    $data['end_date'] = date("d-m-Y", strtotime($service_details['end_date']));
                    $data['start_time'] = date("h:i A", strtotime($service_details['start_time']));
                    $data['end_time'] = date("h:i A", strtotime($service_details['end_time']));
                    $data['offer_percentage'] = $service_details['offer_percentage'];
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function add_service_offer_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['service_id', 'start_date', 'end_date', 'offer_percentage', 'start_time', 'end_time'],$user_data);
    		//check service id exist
    		$service = $this->api->getsingletabledata('services', ['user_id'=>$this->user_id, 'id'=>$user_data['service_id']], '', 'id', 'asc', 'single');
    		if (!empty($service)) 
    		{
    			$start_date = DateTime::createFromFormat('d-m-Y', $user_data['start_date'])->format('Y-m-d');
    			$end_date = DateTime::createFromFormat('d-m-Y', $user_data['end_date'])->format('Y-m-d');
		        $start_time = date("H:i:s", strtotime($user_data['start_time']));
		        $end_time = date("H:i:s", strtotime($user_data['end_time']));
    			$checkoffer = $this->api->checkexistoffer($user_data['service_id'], $start_date, $end_date);
    			if (!empty($checkoffer)) 
    			{
    				$response_code = '500';
	                $response_message = "The selected dates are already exist.";
	                $data = new stdClass();
    			}
    			else
    			{
    				$table_data = array('provider_id'=>$this->user_id, 'service_id'=>$user_data['service_id'],'start_date'=>$start_date, 'end_date'=>$end_date, 'offer_percentage'=>$user_data['offer_percentage'], 'start_time'=>$start_time, 'end_time'=>$end_time, 'status'=>0, 'updated_at'=>date("Y-m-d H:i:s"), 'created_at'=>date("Y-m-d H:i:s"));
    				$this->db->insert('service_offers', $table_data);
    				$response_code = '200';
					$response_message = "Offer Added successfully";
					$data = new stdClass();
    			}
    		}
    		else
    		{
    			$response_code = '500';
                $response_message = "Invalid Service ID";
                $data = new stdClass();
    		}
    	}
    	else
    	{
    		$this->token_error();
    	}
    	$result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function update_service_offer_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();

    		$this->verifyRequiredParams(['service_offer_id', 'start_date', 'end_date', 'offer_percentage', 'start_time', 'end_time'],$user_data);

    		//check start date and end date
    		$start_date = DateTime::createFromFormat('d-m-Y', $user_data['start_date'])->format('Y-m-d');
    		$end_date = DateTime::createFromFormat('d-m-Y', $user_data['end_date'])->format('Y-m-d');
    		//convert time
    		$start_time = date("H:i:s", strtotime($user_data['start_time']));
    		$end_time = date("H:i:s", strtotime($user_data['end_time']));
    		if ($start_date < $end_date) 
    		{
    			//check id is exist
    			$where = array('status'=>0, 'df'=>0, 'provider_id'=>$this->user_id, 'id'=>$user_data['service_offer_id']);
                $service_details = $this->api->getsingletabledata('service_offers', $where, '', 'id', 'asc', 'single');
                if (!empty($service_details)) 
                {
                	//update
                	$table_data = array('start_date'=>$start_date, 'end_date'=>$end_date, 'offer_percentage'=>$user_data['offer_percentage'], 'start_time'=>$start_time, 'end_time'=>$end_time);
                	$this->db->where('id',$user_data['service_offer_id']);  
					if($this->db->update('service_offers', $table_data))
					{
						$response_code = '200';
						$response_message = "Offer Updated successfully";
						$data = new stdClass();
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
						$data = new stdClass();
					}
                }
                else
                {
                	$response_code = '500';
	                $response_message = "Invalid ID";
	                $data = new stdClass();
                }
    		}
    		else
    		{
    			$response_code = '500';
                $response_message = "Start Date should be less than End Date";
                $data = new stdClass();
    		}
    		//echo "<pre>"; print_r($start_date); exit;
    	}
    	else 
    	{
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function delete_service_offer_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('status'=>0, 'df'=>0, 'provider_id'=>$this->user_id, 'id'=>$_GET['id']);
                $service_details = $this->api->getsingletabledata('service_offers', $where, '', 'id', 'asc', 'single');
            	
                //echo "<pre>"; print_r($service_details); exit;
                if (!empty($service_details)) 
                {
                    //update
                	$table_data = array('df'=>1);
                	$this->db->where('id',$_GET['id']);  
					if($this->db->update('service_offers', $table_data))
					{
						$response_code = '200';
						$response_message = "Offer Deleted successfully";
						$data = new stdClass();
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
						$data = new stdClass();
					}
                } 
                else 
                {
                    $response_code = '500';
                    $response_message = "Invalid ID";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    //shop list
    public function my_shop_list_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//echo "<pre>"; print_r($user_data); exit;
    		$this->verifyRequiredParams(['per_page', 'page_no', 'active'],$user_data);
    		$end = ($user_data['page_no']-1)*$user_data['per_page'];
    		//echo "<pre>"; print_r($end); exit;
    		if($end<0)
			{
				$end=0;
			}
			$wt = ['provider_id'=>$this->user_id];
			if ($user_data['active']!=3) {
				$wt['status'] = $user_data['active'];
			}
			$total = $this->api->getCountRows('shops',$wt,'id', '', array());
			//echo "<pre>"; print_r($end); exit;
    		$where = array('shops.provider_id'=>$this->user_id);
    		if ($user_data['active']!=3) {
				$where['shops.status'] = $user_data['active'];
			}
            
    		$shops = $this->api->my_shop_list($user_data['per_page'], $end, $where);
    		//echo "<pre>"; print_r($shops); exit;
    		if (!empty($shops)) 
    		{
    			$response_code = '200';
	            $response_message = 'Shops Listed Successfully';
	            $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['shop_list'] = $shops;
    		}
    		else
    		{
    			$response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
    		}
    		
    	}
    	else 
    	{
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function master_post()
    {
      	if($this->users_id !=0 ||  $this->user_id !=0 || ($this->default_token == $this->api_token)) 
      	{
		    $data=array();
		    $user_data = array();
		    $response=array();
		    $user_data = $this->post();
          	if($user_data['type']==1)
          	{
            	$country=$this->db->get('country_table')->result_array();
            	$country_data=array();
	            foreach ($country as $rows) {
	                $cdata['value']=$rows['id'];
	                $cdata['label']=$rows['country_name'];
	                $country_data[]=$cdata;
	            }
             	$response['list']=$country_data;
           }

           if($user_data['type']==2)
           {
            	$swhere=array('country_id' =>$user_data['id']);
            	$state=$this->db->get_where('state',$swhere)->result_array();
            	$state_data=array();
            	foreach ($state as $srows) {
	                $sdata['value']=$srows['id'];
	                $sdata['label']=$srows['name'];
	                $state_data[]=$sdata;
            	}
            	$response['list']=$state_data;
            }
            if($user_data['type']==3)
            {
           	    $cwhere=array('state_id' =>$user_data['id']);
                $city=$this->db->get_where('city',$cwhere)->result_array();
                $city_data=array();
                foreach ($city as $sprows) {
               		$spdata['value']=$sprows['id'];
                  	$spdata['label']=$sprows['name'];
                  	$city_data[]=$spdata;
                }
               $response['list']=$city_data;
            }

            if($user_data['type']==4) //category
            {
               	$cwhere=array('status'=>1);
                $category=$this->db->get_where('categories',$cwhere)->result_array();
                $cat_data=array();
                foreach ($category as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['category_name'];
					$spdata['category_type']=$sprows['category_type'];
                    $cat_data[]=$spdata;
                }
                $response['list']=$cat_data;
            }

            if($user_data['type']==5) //sub category
            {
               	$cwhere=array('category' =>$user_data['id'], 'status'=>1);
                $category=$this->db->get_where('subcategories',$cwhere)->result_array();
                $scat_data=array();
                foreach ($category as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['subcategory_name'];
                    $scat_data[]=$spdata;
                }
                $response['list']=$scat_data;
            }
            if($user_data['type']==6) //sub sub category
            {
               	$cwhere=array('subcategory'=>$user_data['id'], 'status'=>1);
                $area=$this->db->get_where('sub_subcategories',$cwhere)->result_array();
                $area_data=array();
                foreach ($area as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['sub_subcategory_name'];
                    $area_data[]=$spdata;
                }
                $response['list']=$area_data;
            }
            if($user_data['type']==7) //product category
            {
               	$cwhere=array('status'=>0);
                $area=$this->db->get_where('product_categories',$cwhere)->result_array();
                $area_data=array();
                foreach ($area as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['category_name'];
                    $area_data[]=$spdata;
                }
                $response['list']=$area_data;
            }
            if($user_data['type']==8) //product sub category
            {
               	$cwhere=array('category'=>$user_data['id'], 'status'=>0);
                $area=$this->db->get_where('product_subcategories',$cwhere)->result_array();
                $area_data=array();
                foreach ($area as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['subcategory_name'];
                    $area_data[]=$spdata;
                }
                $response['list']=$area_data;
            }
            if($user_data['type']==9) //product units
            {
               	$cwhere=array('status'=>0);
                $area=$this->db->get_where('product_units',$cwhere)->result_array();
                $area_data=array();
                foreach ($area as $sprows) {
                    $spdata['value']=$sprows['id'];
                    $spdata['label']=$sprows['unit_name'];
                    $area_data[]=$spdata;
                }
                $response['list']=$area_data;
            }
            $response_code='200';
            $response_message='Details Fetched Successfully';    
            $result = $this->data_format($response_code,$response_message,$response);
            $this->response($result, REST_Controller::HTTP_OK);
        }
       	else
        {
          $this->token_error();
        }           
    }
    public function shop_details_get()
    {
    	if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
				if($this->user_id != 0) $where = array('shops.provider_id'=>$this->user_id, 'shops.id'=>$_GET['id']);
				else $where = array('shops.id'=>$_GET['id']);
                $shop = $this->api->my_shop_list('', '', $where);
            	//echo "<pre>"; print_r($shop); exit;
                if (!empty($shop)) 
                {
                    $response_code = '200';
                    $response_message = "Shop Details";
                    $data = $shop[0];
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function manage_shop_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//validate shop
    		$this->verifyRequiredParams(['id','shop_name', 'description', 'country_code', 'contact_no', 'email', 'address', 'country', 'state', 'city', 'postal_code', 'shop_location', 'shop_latitude', 'shop_longitude', 'all_days', 'availability', 'category', 'subcategory'],$user_data);
    		//Set category
    		//get provider
    		$where = array('status'=>1, 'id'=>$this->user_id);
            $provider = $this->api->getsingletabledata('providers', $where, '', 'id', 'asc', 'single');
            $sid = $user_data['id'];
            //check sub category
            //$sub = $this->api->getsingletabledata('subcategories', ['id'=>$user_data['subcategory'], 'category'=>$provider['category'], 'status'=>1], '', 'id', 'asc', 'single');
            //if (!empty($sub))  {
            	unset($user_data['id']);
	            //echo "<pre>"; print_r($_FILES); exit();
	            $user_data['provider_id'] = $this->user_id;
	            $user_data['category'] = $user_data['category'];
	            $user_data['subcategory'] = $user_data['subcategory'];
	            $user_data['created_by'] = $this->user_id;
	    		if ($sid!=0) 
	    		{
	    			//update
	    			$user_data['updated_at'] = date("Y-m-d H:i:s");
	    			$this->db->where('id',$sid);  
					$this->db->update('shops', $user_data);
					$shop_id = $sid;
	    		}
	    		else
	    		{
	    			//insert
	    			$user_data['shop_code'] = 'SHOP'.$this->api->getToken(5, $this->user_id);
	    			$user_data['created_at'] = date("Y-m-d H:i:s");
	    			$this->db->insert('shops', $user_data);
					$shop_id = $this->db->insert_id();

	    		}
	    		$config["upload_path"] = './uploads/shops/';
	    		$config["allowed_types"] = '*';
	            $this->load->library('upload', $config);
	            $this->upload->initialize($config);

	            if ($sid==0) 
	            {
	            	if (empty($_FILES["images"])) 
	            	{
	            		$data = new stdClass();
		                $response_code = '500';
		                $response_message = 'Image is required';
		                $result = $this->data_format($response_code, $response_message, $data);
		                $this->response($result, REST_Controller::HTTP_OK);
	            	}
	            }
	            if (isset($_FILES["images"]["name"]) && !empty($_FILES["images"]["name"])) 
	            {

                     // delete previous images
                    $deleteImages = $this->api->delete_images_shop($sid);

					for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) 
					{
						$_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
						$_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
						$_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
						$_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
						$_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
						if ($this->upload->do_upload('file')) 
						{
							$data = $this->upload->data();
							$image_url = 'uploads/shops/' . $data["file_name"];
							$upload_url = 'uploads/shops/';
							$shop_image = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
							$shop_details_image = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
							$thumb_image = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
							$mobile_image = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
							//insert in shop image
							$img_data = array('shop_id'=>$shop_id, 'shop_image'=>$shop_image, 'shop_details_image'=>$shop_details_image, 'thumb_image'=>$thumb_image, 'mobile_image'=>$mobile_image, 'status'=>1);
							$this->db->insert('shops_images', $img_data);
						}
					}
				}
				$response_code = '200';
				$response_message = "Shop Updated successfully";
				$data = new stdClass();
            /*}
            else
            {
            	$response_code = '500';
				$response_message = "Invalid Subcategory ID";
				$data = new stdClass();
            }*/
            
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    /*public function change_shop_status_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['shop_id', 'active'],$user_data);
    		//
    		$where = array('provider_id'=>$this->user_id, 'id'=>$user_data['shop_id']);
            $shops = $this->api->getsingletabledata('shops', $where, '', 'id', 'asc', 'single');
            if (!empty($shops)) 
            {
            	$table_data = array('status'=>$user_data['active']);
            	$this->db->where('id',$user_data['shop_id']);  
				if($this->db->update('shops', $table_data))
				{
					$response_code = '200';
					$response_message = "Status Changed successfully";
					$data = new stdClass();
				}
				else
				{
					$response_code = '201';
					$response_message = "Something went wrong";
					$data = new stdClass();
				}
            }
            else 
            {
                $response_code = '500';
                $response_message = "Invalid Shop ID";
                $data = new stdClass();
            }
            
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    } */
	
	public function change_shop_status_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['shop_id', 'active'],$user_data);
    		//
    		$where = array('provider_id'=>$this->user_id, 'id'=>$user_data['shop_id']);
            $shops = $this->api->getsingletabledata('shops', $where, '', 'id', 'asc', 'single');
            if (!empty($shops)) 
            {
				$sertot=$this->db->where(array('shop_id' => $user_data['shop_id'], 'user_id' => $this->user_id, 'status' => 1))->from('services')->count_all_results();
				$service_availability = $sertot; 
				
				$stftot=$this->db->where(array('shop_id' => $user_data['shop_id'], 'provider_id' => $this->user_id, 'status' => 1))->from('employee_basic_details')->count_all_results();
				$shop_availability = $stftot;
				
				if($user_data['active'] == 1 || ($service_availability==0 && $shop_availability==0)){
					$table_data = array('status'=>$user_data['active']);
					$this->db->where('id',$user_data['shop_id']);  
					if($this->db->update('shops', $table_data))
					{
						$response_code = '200';
						$response_message = "Status Changed successfully";
						$data = new stdClass();
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
						$data = new stdClass();
					}
				} else {
					$response_code = '500';
					$response_message = "Staffs/Services are Active/Booked and Inprogress..";
					$data = new stdClass();
				}
            }
            else 
            {
                $response_code = '500';
                $response_message = "Invalid Shop ID";
                $data = new stdClass();
            }
            
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function my_staff_list_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//echo "<pre>"; print_r($user_data); exit;
    		$this->verifyRequiredParams(['per_page', 'page_no'],$user_data);
    		$end = ($user_data['page_no']-1)*$user_data['per_page'];
    		//echo "<pre>"; print_r($this->user_id); exit;
    		if($end<0)
			{
				$end=0;
			}
			$wt = ['provider_id'=>$this->user_id, 'delete_status'=>0];
			$total = $this->api->getCountRows('employee_basic_details',$wt,'id', '', array());
			//echo "<pre>"; print_r($end); exit;
    		$staffs = $this->api->my_staff_list($user_data['per_page'], $end, $wt);
    		//echo "<pre>"; print_r($staffs); exit;
    		if (!empty($staffs)) 
    		{
    			$response_code = '200';
	            $response_message = 'Staffs Listed Successfully';
	            $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
	            $data['staff_list'] = $staffs;
    		}
    		else
    		{
    			$response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
    		}
    		
    	}
    	else 
    	{
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function staff_details_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('employee_basic_details.provider_id'=>$this->user_id, 'employee_basic_details.id'=>$_GET['id'], 'employee_basic_details.delete_status'=>0);
                $staff_details = $this->api->staff_details($where);
            	//echo "<pre>"; print_r($staff_details); exit;
                if (!empty($staff_details)) 
                {
                    $response_code = '200';
                    $response_message = "Staff Details";
                    $data = $staff_details;
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function manage_staff_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();

			/*$this->verifyneedparameters(['id', 'shop_id', 'first_name', 'last_name', 'country_code', 'contact_no', 'email','dob', 'gender', 'address', 'country', 'state', 'city', 'postal_code', 'shop_service', 'home_service', 'home_service_area', 'sub_subcategory', 'designation', 'experience', 'exp_month', 'services', 'about_emp', 'all_days', 'availability'],$user_data); */
			
			$this->verifyneedparameters(['id', 'shop_id', 'first_name', 'country_code', 'contact_no', 'email','dob', 'gender', 'shop_service', 'home_service', 'home_service_area', 'about_emp', 'all_days', 'availability'],$user_data);

    		//validate staff
    		/*$validate_staff = ['id', 'shop_id', 'first_name', 'last_name', 'country_code', 'contact_no', 'email','dob', 'gender', 'address', 'country', 'state', 'city', 'postal_code', 'shop_service', 'home_service', 'sub_subcategory', 'designation', 'experience', 'exp_month', 'services', 'about_emp', 'all_days', 'availability'];*/
			
			$validate_staff = ['id', 'shop_id', 'first_name', 'country_code', 'contact_no', 'email','dob', 'gender', 'shop_service', 'home_service', 'about_emp', 'all_days', 'availability'];
    		if ($user_data['home_service'] == 1) {
    			$validate_staff[] = 'home_service_area';
    		}
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate_staff, $user_data);
    		$sid = $user_data['id'];
    		$services = json_decode($user_data['services'], true);
    		unset($user_data['id']);
    		unset($user_data['services']);
    		//Set category
    		//get provider
    		$where = array('status'=>1, 'id'=>$this->user_id);
            $provider = $this->api->getsingletabledata('providers', $where, '', 'id', 'asc', 'single');
            //echo "<pre>"; print_r($_FILES); exit();
            $user_data['provider_id'] = $this->user_id;
            $user_data['dob'] = DateTime::createFromFormat('Y-m-d', $user_data['dob'])->format('Y-m-d');;
            /*if ($sid==0) 
            {
            	if (empty($_FILES["profile_img"])) 
            	{
            		$data = new stdClass();
	                $response_code = '500';
	                $response_message = 'Image is required';
	                $result = $this->data_format($response_code, $response_message, $data);
	                $this->response($result, REST_Controller::HTTP_OK);
            	}
            }*/
    		if ($sid!=0) 
    		{
    			//update
    			$user_data['updated_at'] = date("Y-m-d H:i:s");
    			$this->db->where('id',$sid);  
				$this->db->update('employee_basic_details', $user_data);
				$emp_id = $sid;
    		}
    		else
    		{
    			//insert
    			$user_data['emp_token'] = $this->api->getToken(14, $this->user_id);
    			$user_data['created_at'] = date("Y-m-d H:i:s");
    			$this->db->insert('employee_basic_details', $user_data);
				$emp_id = $this->db->insert_id();

    		}
    		$config["upload_path"] = './uploads/profile_img/';
    		$config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            
            if (isset($_FILES["profile_img"]["name"]) && !empty($_FILES["profile_img"]["name"])) 
            {
				
				$_FILES["file"]["name"] = time() . $_FILES["profile_img"]["name"];
				$_FILES["file"]["type"] = $_FILES["profile_img"]["type"];
				$_FILES["file"]["tmp_name"] = $_FILES["profile_img"]["tmp_name"];
				$_FILES["file"]["error"] = $_FILES["profile_img"]["error"];
				$_FILES["file"]["size"] = $_FILES["profile_img"]["size"];
				if ($this->upload->do_upload('file')) 
				{
					$data = $this->upload->data();
					$image_url = 'uploads/profile_img/'.$data["file_name"];
					$upload_url = 'uploads/profile_img/';
					//insert in shop image
					$img_data = array('profile_img'=>$image_url);
	    			$this->db->where('id',$emp_id);  
					$this->db->update('employee_basic_details', $img_data);
				}
				
			}
			//Insert services
			if (!empty($services)) 
			{
				//delete already available
				$this->api->delete_data('employee_services_list', ['emp_id'=>$emp_id]);
				$esl_data = [];
				foreach ($services as $val) 
				{
					$esl_data[] = ['emp_id'=>$emp_id, 'provider_id'=>$this->user_id, 'service_offered'=>$val['service_offered'], 'duration'=>$val['duration'], 'remarks'=>$val['remarks']];
				}
				//echo "<pre>"; print_r($esl_data); exit();
				if (!empty($esl_data)) 
				{
					//insert batch
					$this->db->insert_batch('employee_services_list', $esl_data);
				}
			}
			$response_code = '200';
			$response_message = "Staff Details Updated successfully";
			$data = new stdClass();
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function service_coupon_history_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$total = $this->api->getCountRows('service_coupons',['status !='=>0, 'provider_id'=>$this->user_id],'id', '', array());
    		//echo "<pre>"; print_r($end); exit;
    		$where = array('service_coupons.status !='=>0, 'service_coupons.provider_id'=>$this->user_id);
            $result = $this->api->service_coupon_history($user_data['per_page'], $end, $where);
         	//echo "<pre>"; print_r($result); exit;
            if ($result) {
                $response_code = '200';
                $response_message = 'Service Coupon History Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['service_coupon_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function service_coupon_details_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('service_coupons.provider_id'=>$this->user_id, 'service_coupons.id'=>$_GET['id'], 'service_coupons.status !='=>0);
                $coupon_details = $this->api->service_coupon_history('', '', $where);
            	//echo "<pre>"; print_r($coupon_details); exit;
                if (!empty($coupon_details)) 
                {
                    $response_code = '200';
                    $response_message = "Coupon Details";
                    $data = $coupon_details[0];
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function manage_service_coupon_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();

    		$this->verifyneedparameters(['id', 'service_id', 'coupon_name', 'user_limit', 'coupon_type', 'coupon_percentage', 'coupon_amount','start_date', 'valid_days', 'description'],$user_data);

    		//validate staff
    		$validate_coupon = ['id', 'service_id', 'coupon_name', 'user_limit', 'coupon_type', 'start_date', 'valid_days', 'description'];
    		if ($user_data['coupon_type'] == 1) {
    			$validate_coupon[] = 'coupon_percentage';
    			$user_data['coupon_amount'] = 0;
    		}
    		else
    		{
    			$validate_coupon[] = 'coupon_amount';
    			$user_data['coupon_percentage'] = 0;
    		}
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate_coupon, $user_data);
    		//check service id is valid
    		$service = $this->api->getCountRows('services',['id'=>$user_data['service_id'], 'user_id'=>$this->user_id, 'status'=>1],'id', '', array());
    		if ($service['cnt']>0) 
    		{
    			$cid = $user_data['id'];
	    		unset($user_data['id']);
	            //echo "<pre>"; print_r($_FILES); exit();
	            $user_data['provider_id'] = $this->user_id;
	            $user_data['coupon_name'] = "PRO".$user_data['coupon_name'];
	            //calculate end date
	            $start_date = DateTime::createFromFormat('Y-m-d', $user_data['start_date'])->format('Y-m-d');

	            $end_date = date('Y-m-d', strtotime($start_date. ' + '.$user_data['valid_days'].' days'));
	            //Add days to
	            $user_data['start_date'] = $start_date;
	            $user_data['end_date'] = $end_date;
	    		if ($cid!=0) 
	    		{
	    			//update
	    			$user_data['updated_at'] = date("Y-m-d H:i:s");
	    			$this->db->where('id',$cid);  
					$this->db->update('service_coupons', $user_data);
	    		}
	    		else
	    		{
	    			//check already applied coupon 
	    			$coupon_chk = $this->api->getsingletabledata('service_coupons', ['provider_id'=>$this->user_id, 'service_id'=>$user_data['service_id']], '', 'id', 'asc', 'single');
	    			if (!empty($coupon_chk)) 
	    			{
	    				//update
		    			$user_data['updated_at'] = date("Y-m-d H:i:s");
                        $user_data['status'] = 1;
		    			$this->db->where('id',$coupon_chk['id']);  
						$this->db->update('service_coupons', $user_data);
	    			}
	    			else
	    			{
		    			//insert
		    			$user_data['created_at'] = date("Y-m-d H:i:s");
		    			$this->db->insert('service_coupons', $user_data);
		    		}
	    		}
				$response_code = '200';
				$response_message = "Coupon Details Updated successfully";
				$data = new stdClass();
    		}
    		else
    		{
    			$response_code = '500';
                $response_message = "Invalid service id";
                $data = new stdClass();
    		}
    		
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function change_coupon_status_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['coupon_id', 'status'],$user_data);
    		//
    		$where = array('provider_id'=>$this->user_id, 'id'=>$user_data['coupon_id']);
            $coupon = $this->api->getsingletabledata('service_coupons', $where, '', 'id', 'asc', 'single');
            if (!empty($coupon)) 
            {
            	$table_data = array('status'=>$user_data['status']);
            	$this->db->where('id',$user_data['coupon_id']);  
				if($this->db->update('service_coupons', $table_data))
				{
					$response_code = '200';
					$response_message = "Status Changed successfully";
					$data = new stdClass();
				}
				else
				{
					$response_code = '201';
					$response_message = "Something went wrong";
					$data = new stdClass();
				}
            }
            else 
            {
                $response_code = '500';
                $response_message = "Invalid Coupon ID";
                $data = new stdClass();
            }
            
        } else {
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function product_list_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$total = $this->api->getCountRows('products',['status'=>0, 'user_id'=>$this->user_id, 'shop_id'=>$user_data['shop_id']],'id', '', array());
    		//echo "<pre>"; print_r($end); exit;
    		$where = array('products.status'=>0, 'products.user_id'=>$this->user_id, 'products.shop_id'=>$user_data['shop_id']);
    		$provider_currency = get_api_provider_currency($this->user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
            $currency_sign = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
            $result = $this->api->my_products_list($user_data['per_page'], $end, $where, $ProviderCurrency, $currency_sign);
         	//echo "<pre>"; print_r($result); exit;
            if ($result) {
                $response_code = '200';
                $response_message = 'Products Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['product_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function product_details_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('products.user_id'=>$this->user_id, 'products.id'=>$_GET['id'], 'products.status'=>0);
                $provider_currency = get_api_provider_currency($this->user_id);
            	$ProviderCurrency = $provider_currency['user_currency_code'];
            	$currency_sign = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                $product = $this->api->view_product_details($where, $ProviderCurrency, $currency_sign);
                //Related Products
                /*$where = ['products.status'=>0, 'products.shop_id'=>$product['shop_id'], 'products.id !='=>$_GET['id']];
                $related_products = $this->api->user_product_list(4, 0, $where, '', 'list', $ProviderCurrency, $currency_sign);
                if (!empty($related_products)) {
                	$product['recommended_products'] = $related_products;
                }
                else
                {
                	$product['recommended_products'] = [];
                }*/
            	//echo "<pre>"; print_r($staff_details); exit;
                if (!empty($product)) 
                {
                    $response_code = '200';
                    $response_message = "Product Details";
                    $data = $product;
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function user_product_details_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $where = array('products.id'=>$_GET['id'], 'products.status'=>0);

                $user_currency = get_api_user_currency($this->users_id);
            	$UserCurrency = $user_currency['user_currency_code'];
            	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                $product = $this->api->view_product_details($where, $UserCurrency, $currency_sign);
                //Related Products
                $where = ['products.status'=>0, 'products.shop_id'=>$product['shop_id'], 'products.id !='=>$_GET['id']];
                $related_products = $this->api->user_product_list(4, 0, $where, '', 'list', $UserCurrency, $currency_sign);
                if (!empty($related_products)) {
                	$product['recommended_products'] = $related_products;
                }
                else
                {
                	$product['recommended_products'] = [];
                }
            	//echo "<pre>"; print_r($related_products); exit;
                if (!empty($product)) 
                {
                    $response_code = '200';
                    $response_message = "Product Details";
                    $data = $product;
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function manage_product_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();

    		//validate staff
    		$validate_product = ['id', 'shop_id', 'category', 'subcategory', 'product_name', 'unit_value', 'unit', 'price', 'discount', 'sale_price', 'short_description', 'description', 'manufactured_by'];
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate_product, $user_data);
    		$pid = $user_data['id'];
    		unset($user_data['id']);
            $provider_currency = get_api_provider_currency($this->user_id);
            //echo "<pre>"; print_r($_FILES); exit();
            $user_data['user_id'] = $this->user_id;
            $user_data['currency_code'] = $provider_currency['user_currency_code'];
            $user_data['slug'] = str_slug($user_data['product_name']);
            // echo $pid;die;
            if ($pid==0) 
            {
            	if (empty($_FILES["images"])) 
            	{
            		$data = new stdClass();
	                $response_code = '500';
	                $response_message = 'Image is required';
	                $result = $this->data_format($response_code, $response_message, $data);
	                $this->response($result, REST_Controller::HTTP_OK);
            	}
            }
    		if ($pid!=0) 
    		{
               
    			//update
    			$user_data['updated_on'] = date("Y-m-d H:i:s");
    			$this->db->where('id',$pid);  
				$this->db->update('products', $user_data);
				$product_id = $pid;
    		}
    		else
    		{
    			//echo "<pre>"; print_r($user_data); exit();
    			//insert
    			$user_data['created_date'] = date("Y-m-d H:i:s");
    			$this->db->insert('products', $user_data);
				$product_id = $this->db->insert_id();

    		}
    		$config["upload_path"] = './uploads/products/product';
    		$config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (isset($_FILES["images"]["name"]) && !empty($_FILES["images"]["name"])) 
            {
                // delete previous images
                $deleteImages = $this->api->delete_images($pid);
               
				for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) 
				{
					$_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
					$_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
					$_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
					$_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
					$_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
					if ($this->upload->do_upload('file')) 
					{
						$data = $this->upload->data();
						$image_url = 'uploads/products/product/' . $data["file_name"];
						$upload_url = 'uploads/products/product/';
						$product_image = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
						$thumb_image = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
						$primary_img = 0;
	                    if ($count==0) 
	                    {
	                        $primary_img = 1;
	                    }
						//insert in shop image
						$img_data = array('product_id'=>$product_id, 'product_image'=>$product_image, 'thumb_image'=>$thumb_image, 'primary_img'=>$primary_img, 'status'=>1);
						$this->db->insert('product_images', $img_data);
					}
				}
			}
			$response_code = '200';
			$response_message = "Product Details Updated successfully";
			$data = new stdClass();
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function delete_product_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
            	//check product
                $where = array('user_id'=>$this->user_id, 'id'=>$_GET['id'], 'status'=>0);
                $product = $this->api->getsingletabledata('products', $where, '', 'id', 'asc', 'single');
            	//echo "<pre>"; print_r($staff_details); exit;
                if (!empty($product)) 
                {
                	//delete cart
                	//update
                	$this->db->where('id',$_GET['id']);  
					$this->db->update('products', ['status'=>1]);
                    $response_code = '200';
                    $response_message = "Product Deleted Successfully";
                    $data = new stdClass();
                } 
                else 
                {
                    $response_code = '500';
                    $response_message = "Invalid Product ID";
                    $data = new stdClass();
                }
            } 
            else 
            {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function user_product_list_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$user_currency = get_api_user_currency($this->users_id);
            $UserCurrency = $user_currency['user_currency_code'];
            $currency_sign = (!empty($UserCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
    		$this->verifyneedparameters(['page_no', 'per_page', 'category', 'subcategory', 'price_range', 'product_name'],$user_data);

    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$where = ['products.status'=>0];
			$search = array();
			if ($user_data['category']!='') 
			{
				$where['products.category'] = $user_data['category'];
			}
			if ($user_data['subcategory']!='') 
			{
				$where['products.subcategory'] = $user_data['subcategory'];
			}
			if ($user_data['price_range']!='any') 
	        {
	            //split range
	            $range = explode("-", $user_data['price_range']);
	            
	            if ($range[0]!='') 
	            {
	                //echo $range[0];
	                $where['get_gigs_currency(products.sale_price, products.currency_code, "'.$UserCurrency.'") >='] = (float)$range[0];
	            }
	            if ($range[1]!='') 
	            {
	                $where['get_gigs_currency(products.sale_price, products.currency_code, "'.$UserCurrency.'") <='] = (float)$range[1];
	            }
	        }
	        if ($user_data['product_name']!='') 
	        {
	        	$search['products.product_name'] = $user_data['product_name'];
	        }
			$total = $this->api->user_product_list('','', $where, $search, 'count', $UserCurrency, $currency_sign);
    		
            $result = $this->api->user_product_list($user_data['per_page'], $end, $where, $search, 'list', $UserCurrency, $currency_sign);
         	//echo "<pre>"; print_r($result); exit;
            if ($result) {
                $response_code = '200';
                $response_message = 'Products Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['product_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function manage_product_cart_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$user_currency = get_api_user_currency($this->users_id);
            $UserCurrency = $user_currency['user_currency_code'];
            $currency_sign = (!empty($UserCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
    		$this->verifyneedparameters(['product_id', 'cart_id', 'qty'],$user_data);
    		$this->verifyRequiredParams(['product_id', 'qty'],$user_data);
    		//check product exist
    		$product = $this->api->getsingletabledata('products', ['id'=>$user_data['product_id'], 'status'=>0], '', 'id', 'asc', 'single');
			if (!empty($product)) 
			{
				//check cart id
				if ($user_data['cart_id']!='') 
				{
                    $cart_id = $user_data['cart_id'];
					//cart product exist
    				$cart = $this->api->getsingletabledata('product_cart', ['id'=>$user_data['cart_id'], 'status'=>0], '', 'id', 'asc', 'single');
    				if (!empty($cart)) 
    				{
    					//update cart
    					$product_price = get_gigs_currency($product['sale_price'], $product['currency_code'], $cart['product_currency']);
    					$product_total = $product_price*$user_data['qty'];
			            $this->db->where('id', $user_data['cart_id']);
			            $this->db->update('product_cart', ['qty'=>$user_data['qty'], 'product_total'=>$product_total]);
                        $where = array();

                        $where['user_id'] = $this->users_id;

                        $this->db->where($where);
                        $this->db->where('status',0);
                        $this->db->group_by('product_id');

                        $count = $this->db->count_all_results('product_cart');
                        $where = array();

                        $where['user_id'] = $this->users_id;
                        $where['product_id'] = $user_data['product_id'];
                        $this->db->select('qty as productQty');
                        $this->db->where($where);
                        $this->db->where('status',0);
                        $this->db->group_by('product_id');
                        $productCount = $this->db->get('product_cart')->result_array();
			            $response_code = '200';
		                $response_message = 'Product Updated to Cart Successfully';
		                $data = array('cartCount'=>$count,"productCount"=>(int) $productCount[0]['productQty'],"cartId"=>(string) $cart_id);
    				}
    				else
    				{
    					$response_code = '200';
		                $response_message = 'Invalid Cart ID';
		                $data = new stdClass();
		                $result = $this->data_format($response_code, $response_message, $data);

			            $this->response($result, REST_Controller::HTTP_OK);
			            exit;
    				}
				}
				else
				{
					//check cart
            		$cart = $this->api->getsingletabledata('product_cart', ['user_id'=>$this->users_id, 'product_id'=>$user_data['product_id'], 'status'=>0], '', 'id', 'asc', 'single');
            		//last cart
		            $last_cart = $this->api->gettablelastdata('product_cart',['user_id'=>$this->users_id, 'status'=>0], 'id');
		            $cart_id = $cart['id'];
		            if (!empty($last_cart)) 
		            {
		                //calculate product price
		                $product_price = get_gigs_currency($product['sale_price'], $product['currency_code'], $last_cart['product_currency']);
		                $product_currency = $last_cart['product_currency'];
		            }
		            else
		            {
		                $product_price = $product['sale_price'];
		                $product_currency = $product['currency_code'];
		            }
            		$cart_data = array('user_id'=>$this->users_id, 'shop_id'=>$product['shop_id'], 'product_id'=>$user_data['product_id'], 'product_currency'=>$product_currency, 'product_price'=>$product_price);
            		if (!empty($cart)) 
		            {
		                $cart_data['qty'] = $cart['qty']+1;
		                $cart_data['product_total'] = $cart['product_price']*$cart_data['qty'];
		                //update
		                $this->db->where('id', $cart['id']);
		                $this->db->update('product_cart', $cart_data);
		            }
		            else
		            {
		                $cart_data['qty'] = $user_data['qty'];
		                $cart_data['product_total'] = $product_price*$user_data['qty'];
		                $cart_data['created_at'] = date("Y-m-d H:i:s");
		                $this->db->insert('product_cart', $cart_data);
                        $cart_id = $this->db->insert_id();
		            }
                    $where = array();

                    $where['user_id'] = $this->users_id;

                    $this->db->where($where);
                    $this->db->where('status',0);
                    $this->db->group_by('product_id');
                    $count = $this->db->count_all_results('product_cart');
                    $where = array();

                    $where['user_id'] = $this->users_id;
                    $where['product_id'] = $user_data['product_id'];
                    $this->db->select('qty as productQty');
                    $this->db->where($where);
                    $this->db->where('status',0);
                    $this->db->group_by('product_id');
                    $productCount = $this->db->get('product_cart')->result_array();
		            $response_code = '200';
	                $response_message = 'Product Updated to Cart Successfully';
	                $data = array("cartCount"=>$count,"productCount"=>(int) $productCount[0]['productQty'],"cartId"=>(string) $cart_id);
				}
			}
			else
			{
				$response_code = '200';
                $response_message = 'Invalid Product ID';
                $data = new stdClass();
			}
            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function product_cart_list_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            $where = array('product_cart.user_id'=>$this->users_id, 'product_cart.status'=>0);
            $user_currency = get_api_user_currency($this->users_id);
        	$UserCurrency = $user_currency['user_currency_code'];
        	//echo $UserCurrency; exit;
        	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
            $carts = $this->api->product_cart_list($where, $UserCurrency, $currency_sign);
            $total = $this->api->getCountRows('product_cart',['status'=>0, 'user_id'=>$this->users_id],'id', '', array());
        	//echo "<pre>"; print_r($carts); exit;
            if (!empty($carts)) 
            {
                $response_code = '200';
                $response_message = "Product Cart Listed Successfully";
                $data['cart_count'] = $total['cnt'];
                $data['list'] = $carts;
            } else {
                $response_code = '500';
                $response_message = "No Details found";
                $data = new stdClass();
            }  
        } 
        else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function delete_product_cart_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
            	//check cart

                $where = array('user_id'=>$this->users_id, 'id'=>$_GET['id'], 'status'=>0);
                $cart = $this->api->getsingletabledata('product_cart', $where, '', 'id', 'asc', 'single');
            	//echo "<pre>"; print_r($staff_details); exit;
                if (!empty($cart)) 
                {
                	//delete cart
                	$this->api->delete_data('product_cart',$where);
                    $response_code = '200';
                    $response_message = "Cart Deleted Successfully";
                    $data = new stdClass();
                } 
                else 
                {
                    $response_code = '500';
                    $response_message = "Invalid Card ID";
                    $data = new stdClass();
                }
            } 
            else 
            {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function billing_address_list_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            $where = array('user_billing_details.user_id'=>$this->users_id, 'user_billing_details.status'=>0);
            $billing = $this->api->billing_address_list($where);
        	//echo "<pre>"; print_r($billing); exit;
            if (!empty($billing)) 
            {
                $response_code = '200';
                $response_message = "Billing Address List Successfully";
                $data['billing_list'] = $billing;
            } else {
                $response_code = '500';
                $response_message = "No Details found";
                $data = new stdClass();
            }  
        } 
        else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function manage_billing_address_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//validate staff
    		$validate = ['id', 'full_name', 'phone_no', 'email_id', 'address', 'country_id', 'state_id', 'city_id', 'zipcode'];
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate, $user_data);
    		$bid = $user_data['id'];
    		unset($user_data['id']);
            //echo "<pre>"; print_r($_FILES); exit();
            $user_data['user_id'] = $this->users_id;
    		if ($bid!=0) 
    		{
    			//update
    			$user_data['updated_on'] = date("Y-m-d H:i:s");
    			$this->db->where('id',$pid);  
				$this->db->update('user_billing_details', $user_data);
    		}
    		else
    		{
    			//insert
    			$user_data['created_on'] = date("Y-m-d H:i:s");
    			$this->db->insert('user_billing_details', $user_data);

    		}
			$response_code = '200';
			$response_message = "Billing Details Updated successfully";
			$data = new stdClass();
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function product_checkout_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		//validate staff
    		$validate = ['billing_details_id', 'address_type'];
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate, $user_data);
    		//check billing id valid
    		$billing = $this->api->getsingletabledata('user_billing_details', ['user_id'=>$this->users_id, 'id'=>$user_data['billing_details_id'], 'status'=>0], '', 'id', 'asc', 'single');
            if (!empty($billing)) 
            {
            	//create order
            	$cart = $this->api->getsingletabledata('product_cart', ['user_id'=>$this->users_id, 'status'=>0], '', 'id', 'asc', 'multiple');
            	//check order
        		$order = $this->api->getsingletabledata('product_order', ['user_id'=>$this->users_id, 'status'=>0], '', 'id', 'asc', 'single');

        		$order_data = array('user_id'=>$this->users_id, 'billing_details_id'=>$user_data['billing_details_id'], 'address_type'=>$user_data['address_type']);
        		//
        		if (!empty($cart)) 
		        {
		            $order_data['currency_code'] = $cart[0]['product_currency'];
		            $order_data['total_products'] = count($cart);
		            $order_data['total_qty'] = array_sum(array_map(function($item) { return $item['qty']; }, $cart));
		            $order_data['total_amt'] = round(array_sum(array_map(function($item) { return $item['product_total']; }, $cart)),2);

		            if (!empty($order)) 
		            {
		                // udpdate
		                $this->db->where('id', $order['id']);
		                $this->db->update('product_order', $order_data);
		                $order_id = $order['id'];
		            }
		            else
		            {
		                //Create Order Code
		                $w_ref = array('type_id'=>1);
		                $last_ref_id = $this->api->getsingletabledata('mas_increment', $w_ref, '', 'id', 'asc', 'single');
		                $new_cnt = $last_ref_id['auto_value']+1;
		                $auto_value = sprintf("%07d", $new_cnt);
		                $order_data['order_code'] = $last_ref_id['prefix'].$auto_value;
		                $order_data['created_on'] = date("Y-m-d H:i:s");
		                $this->db->insert('product_order', $order_data);
		                //$error = $this->db->error();
                		//echo json_encode($error);
		                $order_id = $this->db->insert_id();
		                //update code
		                $this->db->where($w_ref);
		                $this->db->update('mas_increment', ['auto_value'=>$auto_value]);
		            }
		            //update order id in cart
		            $this->db->where(['user_id'=>$this->users_id, 'status'=>0]);
		            $this->db->update('product_cart', ['order_id'=>$order_id]);
		 			
		 			//
	            	$response_code = '200';
					$response_message = "Order Updated successfully";
					$data['order_id'] =(string) $order_id;
		        }
		        else
		        {
		        	$response_code = '500';
	                $response_message = "Cart is empty";
	                $data = new stdClass();
		        }
        		
            }
            else
            {
            	$response_code = '500';
                $response_message = "Invalid Billing ID";
                $data = new stdClass();
            }
			
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function product_order_summary_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['order_id']) && !empty($_GET['order_id'])) 
            {
                $where = array('user_id'=>$this->users_id, 'id'=>$_GET['order_id'], 'status'=>0);
                $user_currency = get_api_user_currency($this->users_id);
            	$UserCurrency = $user_currency['user_currency_code'];
            	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
                $order = $this->api->product_order_summary($where, $UserCurrency, $currency_sign);
                //cart list
                $carts = $this->api->product_cart_list(['product_cart.user_id'=>$this->users_id, 'product_cart.order_id'=>$_GET['order_id'], 'product_cart.status'=>0], $UserCurrency, $currency_sign);
                //billing details
                $where = array('user_billing_details.user_id'=>$this->users_id, 'user_billing_details.status'=>0);
            	$billing = $this->api->billing_address_list(array('user_billing_details.user_id'=>$this->users_id, 'user_billing_details.status'=>0, 'user_billing_details.id'=>$order['billing_details_id']));
            	$order['cart_list'] = $carts;
            	$order['billing_details'] = $billing[0];
            	//echo "<pre>"; print_r($order); exit;
                if (!empty($order)) 
                {
                    $response_code = '200';
                    $response_message = "Order Summary";
                    $data['order_summary'] = $order;
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function confirm_order_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyneedparameters(['order_id', 'payment_type', 'payment_gway', 'transaction_id', 'payment_status'], $user_data);
    		//validate staff
    		$validate = ['order_id', 'payment_type', 'transaction_id', 'payment_status'];
    		if ($user_data['payment_type']=='card') 
    		{
    			$validate[] = 'payment_gway';
    		}
    		//echo "<pre>"; print_r($validate_staff); exit();
    		$this->verifyRequiredParams($validate, $user_data);
    		//user
    		$user = $this->api->getsingletabledata('users', ['id'=>$this->users_id], '', 'id', 'asc', 'single');
    		//Order
    		$where = array('user_id'=>$this->users_id, 'id'=>$user_data['order_id'], 'status'=>0);
            $user_currency = get_api_user_currency($this->users_id);
        	$UserCurrency = $user_currency['user_currency_code'];
        	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
            $order = $this->api->product_order_summary($where, $UserCurrency, $currency_sign);
            if (!empty($order)) 
            {
            	//
	    		if ($user_data['transaction_id']!='') 
	    		{
	    			//if wallet
	    			if ($user_data['payment_type']=='wallet') 
		    		{
		    			$wallet = $this->api->getsingletabledata('wallet_table', ['user_provider_id'=>$this->users_id, 'type'=>2], '', 'id', 'asc', 'single');
		    			//echo "<pre>"; print_r($wallet); exit();
		    			if (!empty($wallet)) 
		    			{
		    				$wallet_amt = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $UserCurrency);
		    				if ($wallet_amt >= $order['total_amt']) 
            				{
            					$bal_amt = $wallet_amt - $order['total_amt'];
            					$current_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $UserCurrency);
                				$debit_wallet = get_gigs_currency($order['total_amt'], $order['currency_code'], $UserCurrency);
            					$avail_wallet = $current_wallet - $debit_wallet;
            					//
            					$this->db->where('id', $wallet['id']);
                				$this->db->update('wallet_table', ['wallet_amt'=>$avail_wallet, 'currency_code'=>$UserCurrency]);
                				//insert into history
                				$trans_data = array('token'=>$user['token'], 'currency_code'=>$UserCurrency, 'user_provider_id'=>$this->users_id, 'type'=>2, 'tokenid'=>$user['token'], 'payment_detail'=>'product order', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$avail_wallet, 'net_amt'=>$avail_wallet, 'current_wallet'=>$current_wallet, 'debit_wallet'=>$debit_wallet, 'avail_wallet'=>$avail_wallet, 'reason'=>'Product Order', 'created_at'=>date('Y-m-d H:i:s'));
                				$this->db->insert('wallet_transaction_history',$trans_data);
                				//save order
            					$this->order_success($this->users_id, $user_data['order_id'], 'wallet', $user_data['transaction_id'], 'success');
            					$response_code = '200';
				                $response_message = "Order successfully placed";
				                $data = new stdClass();
            					//echo "<pre>"; print_r($avail_wallet); exit();
            				}
            				else
            				{
            					$response_code = '500';
				                $response_message = "Insufficient wallet amount";
				                $data = new stdClass();
				          //       $result = $this->data_format($response_code, $response_message, $data);
	        					// $this->response($result, REST_Controller::HTTP_OK);
	        					// exit;
            				}
		    			}
		    			else
		    			{
		    				$response_code = '500';
			                $response_message = "Wallet is empty";
			                $data = new stdClass();
			          //       $result = $this->data_format($response_code, $response_message, $data);
        					// $this->response($result, REST_Controller::HTTP_OK);
        					// exit;
		    			}
		    		}
		    		//card
		    		if ($user_data['payment_type']=='card') 
		    		{
		    			$this->order_success($this->users_id, $user_data['order_id'], $user_data['payment_gway'], $user_data['transaction_id'], 'success');
		    			$response_code = '200';
		                $response_message = "Order successfully placed";
		                $data = new stdClass();
		    		}
		    		//cod
		    		if ($user_data['payment_type']=='cod') 
		    		{
		    			$this->order_success($this->users_id, $user_data['order_id'], 'cod', $user_data['transaction_id'], 'success');
		    			$response_code = '200';
		                $response_message = "Order successfully placed";
		                $data = new stdClass();
		    		}

	    		}
	    		else
	    		{
	    			$response_code = '500';
	                $response_message = "Transaction ID should not be empty";
	                $data = new stdClass();
	    		}
	    		//
            }
            else
            {
            	$response_code = '500';
                $response_message = "Invalid Order ID";
                $data = new stdClass();
            }
    		
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function order_success($userId, $order_id, $gateway, $transaction_id, $message)
    {
        //echo $order_id; exit;
        //checkout details
        $order = $this->api->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        //update in product order
        $upt_order = ['payment_gway'=>$gateway, 'transaction_id'=>$transaction_id, 'payment_status'=>$message];
        if ($gateway == 'wallet') 
        {
           $upt_order['payment_type'] = 'wallet';
        }
        else if ($gateway == 'cod') 
        {
           $upt_order['payment_type'] = 'cod';
        }
        else
        {
            $upt_order['payment_type'] = 'card';
        }
        $upt_cart['status'] = 0;
        if ($transaction_id!='') 
        {
            $upt_order['status'] = 1;
            $upt_cart['status'] = 1;
        }
        $this->db->where('id', $order_id);
        $this->db->update('product_order', $upt_order);
        //update cart status
        $this->db->where('order_id', $order_id);
        $this->db->update('product_cart', $upt_cart);
        //insert log
        $ins_data = ['product_order_id'=>$order_id, 'user_id'=>$userId, 'payment_gway'=>$gateway, 'transaction_id'=>$transaction_id, 'payment_status'=>$message, 'created_on'=>date("Y-m-d H:i:s")];
        $this->db->insert('product_order_log', $ins_data);
        if ($gateway!='cod' && $transaction_id!='') 
        {
            $this->order_submit($userId, $order_id); //splitting to respective providers
        }
        if ($transaction_id!='') {
            $this->order_success_notification($userId, $order_id);
        }
        return "success";
    }
    public function order_success_notification($userId, $order_id)
    {
    	//user
		$user = $this->api->getsingletabledata('users', ['id'=>$userId], '', 'id', 'asc', 'single');
        //Notification to provider
        $$user_currency = get_api_user_currency($userId);
    	$UserCurrency = $user_currency['user_currency_code'];
    	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
        //cart list
        $cartlist = $this->api->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.order_id'=>$order_id], $UserCurrency, $currency_sign);
        //echo json_encode($cartlist); exit;
        if (!empty($cartlist)) 
        {
            foreach ($cartlist as $val) 
            {
                //echo json_encode($val['product_name']);
                $provider = $this->api->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
                $message = 'You have recieved an Order of '.$val['product_name'].' from '.$user['name'];
                $this->api->insert_notification($user['token'], $provider['token'], $message);
            }
        }
        return 'success';
    }
    public function order_submit($userId, $order_id)
    {
    	$user_currency = get_api_user_currency($userId);
    	$UserCurrency = $user_currency['user_currency_code'];
    	$currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
        //cart list
        $cartlist = $this->api->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.order_id'=>$order_id], $UserCurrency, $currency_sign);
        //echo "<pre>"; print_r($cartlist); exit();
        if (!empty($cartlist)) 
        {
            foreach ($cartlist as $val) 
            {
                //get wallet of provider
                $provider = $this->api->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
                $wallet = $this->api->getsingletabledata('wallet_table', ['user_provider_id'=>$val['provider_id'], 'type'=>1], '', 'id', 'asc', 'single');

                $wallet_amt = 0;
                $wallet_currency = $provider['currency_code'];
                $current_wallet = 0;
                //echo "<pre>"; print_r($wallet); exit();
                if (!empty($wallet)) 
                {
                    $wallet_amt = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $provider['currency_code']);
                    $wallet_currency = $wallet['currency_code'];
                    $current_wallet = get_gigs_currency($wallet_amt, $wallet_currency, $provider['currency_code']);
                }
                //echo "<pre>"; print_r($wallet_amt); exit();
                $credit_wallet = get_gigs_currency($val['product_total'], $val['currency_code'], $wallet_currency);

                $avail_wallet = $credit_wallet+$wallet_amt;
                //echo "<pre>"; print_r($avail_wallet); exit();
                //update in wallet
                if (!empty($wallet)) 
                {
                    $this->db->where('id', $wallet['id']);
                    $this->db->update('wallet_table', ['wallet_amt'=>$avail_wallet]);
                }
                else
                {
                    //
                    $wallet_data = array('token'=>$provider['token'], 'currency_code'=>$provider['currency_code'], 'user_provider_id'=>$val['provider_id'], 'type'=>1, 'wallet_amt'=>$avail_wallet, 'created_at'=>date("Y-m-d H:i:s"), 'updated_on'=>date("Y-m-d H:i:s"));
                    $this->db->insert('wallet_table',$wallet_data);
                }
                //history insert
                $his_data = array('token'=>$provider['token'], 'currency_code'=>$provider['currency_code'], 'user_provider_id'=>$val['provider_id'], 'type'=>1, 'tokenid'=>$provider['token'], 'payment_detail'=>'product order', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$avail_wallet, 'net_amt'=>$avail_wallet, 'current_wallet'=>$current_wallet, 'credit_wallet'=>$credit_wallet, 'avail_wallet'=>$avail_wallet, 'reason'=>'Product Order', 'created_at'=>date('Y-m-d H:i:s'));
                $this->db->insert('wallet_transaction_history',$his_data);
            }
        }
        return 'success';
    }
    public function user_order_list_post()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$user_currency = get_api_user_currency($this->users_id);
            $UserCurrency = $user_currency['user_currency_code'];
            $currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
    		$this->verifyneedparameters(['page_no', 'per_page', 'order_code', 'shop_name', 'product_name', 'delivery_status'],$user_data);

    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$where = array('product_cart.status'=>1, 'product_cart.user_id'=>$this->users_id);
			$search = array();
			if ($user_data['order_code']!='') 
	        {
	            $search['product_order.order_code'] = $user_data['order_code'];
	        }
	        if ($user_data['shop_name']!='') 
	        {
	            $search['shops.shop_name'] = $user_data['shop_name'];
	        }
	        if ($user_data['product_name']!='') 
	        {
	            $search['products.product_name'] = $user_data['product_name'];
	        }
	        if ($user_data['delivery_status']!='') 
	        {
	            $where['product_cart.delivery_status'] = $user_data['delivery_status'];
	        }
			$total = $this->api->user_orders_list('','', $where, $search, 'count', $UserCurrency, $currency_sign);
    		
            $result = $this->api->user_orders_list($user_data['per_page'], $end, $where, $search, 'list', $UserCurrency, $currency_sign);
            // echo "<pre>"; print_r($result); exit;
            if ($result) {
                $response_code = '200';
                $response_message = 'Orders Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['order_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function order_list_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$provider_currency = get_api_provider_currency($this->user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
            $currency_sign = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
    		$this->verifyneedparameters(['page_no', 'per_page', 'order_code', 'shop_name', 'product_name', 'delivery_status', 'user_name'],$user_data);

    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$where = array('product_cart.status'=>1, 'shops.provider_id'=>$this->user_id);
			$search = array();
			if ($user_data['order_code']!='') 
	        {
	            $search['product_order.order_code'] = $user_data['order_code'];
	        }
	        if ($user_data['user_name']!='') 
	        {
	            $search['users.name'] = $user_data['user_name'];
	        }
	        if ($user_data['shop_name']!='') 
	        {
	            $search['shops.shop_name'] = $user_data['shop_name'];
	        }
	        if ($user_data['product_name']!='') 
	        {
	            $search['products.product_name'] = $user_data['product_name'];
	        }
	        if ($user_data['delivery_status']!='') 
	        {
	            $where['product_cart.delivery_status'] = $user_data['delivery_status'];
	        }
			$total = $this->api->provider_orders_list('','', $where, $search, 'count', $UserCurrency, $currency_sign);
    		
            $result = $this->api->provider_orders_list($user_data['per_page'], $end, $where, $search, 'list', $ProviderCurrency, $currency_sign);
         	//echo "<pre>"; print_r($result); exit;
            if ($result) {
                $response_code = '200';
                $response_message = 'Orders Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['order_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function view_delivery_details_get()
    {
    	if ($this->users_id != 0 || $this->user_id || ($this->default_token == $this->api_token)) {

            if (isset($_GET['order_id']) && !empty($_GET['order_id'])) 
            {
            	$where = array('id'=>$_GET['order_id'], 'status'=>1);
            	if ($this->users_id!='') {
            		$where['user_id'] = $this->users_id;
            	}
                $order = $this->api->getsingletabledata('product_order', $where, '', 'id', 'asc', 'single');
                
            	//echo "<pre>"; print_r($order); exit;
                if (!empty($order)) 
                {
                	//billing details
	                $where = array('user_billing_details.id'=>$order['billing_details_id']);
	            	$billing = $this->api->billing_address_list($where);
	            	//details
	            	$result = ['order_code'=>$order['order_code'], 'payment_type'=>$order['payment_type'], 'payment_gway'=>$order['payment_gway'], 'transaction_id'=>$order['payment_gway'], 'address_type'=>$order['address_type'], 'delivery_address'=>$billing[0]];
                    $response_code = '200';
                    $response_message = "Delivery and Payment Details";
                    $data['delivery_details'] = $result;
                } else {
                    $response_code = '500';
                    $response_message = "No Details found";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function cancel_product_order($usertype, $user_provider_id, $cart_id)
    {
    	$cart = $this->api->product_cart_details(['product_cart.id'=>$cart_id, 'product_cart.status'=>1]);
    	if ($cart['delivery_status']==1) 
    	{
    		//Order
    		$order = $this->api->getsingletabledata('product_order', ['id'=>$cart['order_id'], 'status'=>1], '', 'id', 'asc', 'single');
    		if ($order['payment_type']!='cod') 
            {
                //Add amount to wallet user
                $cresult = $this->order_cancel_wallet($cart_id);
               // exit;
            }
            //update delivery status
            $this->db->where('id', $cart_id);
			$this->db->update('product_cart', ['delivery_status'=>6]);
            //notification
			$user = $this->api->getsingletabledata('users', ['id'=>$cart['user_id']], '', 'id', 'asc', 'single');
			$provider = $this->api->getsingletabledata('providers', ['id'=>$cart['provider_id']], '', 'id', 'asc', 'single');
			if ($usertype == 'user') 
            {
               $sender = $user['token'];
               $receiver = $provider['token'];
               $message = $user['name'].' have cancelled the order of '.$cart['product_name'];
            }
            else
            {
            	$sender = $provider['token'];
                $receiver = $user['token'];
                $message = $provider['name'].' have cancelled the order of '.$cart['product_name'];
            }
            $this->api->insert_notification($sender, $receiver, $message);
    	}
    	return 'success';
    }
    public function user_cancel_order_get()
    {
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $cart = $this->api->product_cart_details(['product_cart.user_id'=>$this->users_id, 'product_cart.id'=>$_GET['id'], 'product_cart.status'=>1]);
                
            	//echo "<pre>"; print_r($order); exit;
                if (!empty($cart)) 
                {
                	if ($cart['delivery_status']==1) 
                	{
                		$this->cancel_product_order('user', $this->users_id, $_GET['id']);
                		$response_code = '200';
	                    $response_message = "Order Cancelled Successfully";
	                    $data = new stdClass();
                	}
                	else
                	{
                		$response_code = '500';
	                    $response_message = "Cancellation is not possible";
	                    $data = new stdClass();
                	}
                    
                } 
                else 
                {
                    $response_code = '500';
                    $response_message = "Invalid ID";
                    $data = new stdClass();
                }
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function cancel_order_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
                $cart = $this->api->product_cart_details(['shops.provider_id'=>$this->user_id, 'product_cart.id'=>$_GET['id'], 'product_cart.status'=>1]);
            	//echo "<pre>"; print_r($order); exit;
                if (!empty($cart)) 
                {
                	if ($cart['delivery_status']==1) 
                	{
                		$this->cancel_product_order('provider', $this->user_id, $_GET['id']);
                		$response_code = '200';
	                    $response_message = "Order Cancelled Successfully";
	                    $data = new stdClass();
                	}
                	else
                	{
                		$response_code = '500';
	                    $response_message = "Cancellation is not possible";
	                    $data = new stdClass();
                	}
                    
                } 
                else 
                {
                    $response_code = '500';
                    $response_message = "Invalid ID";
                    $data = new stdClass();
                }
            } 
            else 
            {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } 
        else 
        {
            $this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    }
    public function change_delivery_status_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['cart_id', 'current_delivery_status'],$user_data);
			$where = array('product_cart.id'=>$user_data['cart_id'], 'product_cart.status'=>1, 'product_cart.delivery_status'=>$user_data['current_delivery_status'], 'shops.provider_id'=>$this->user_id);
			
			$cart = $this->api->product_cart_details($where);
         	//echo "<pre>"; print_r($cart); exit;
            if ($cart) 
            {
            	$next_status = $user_data['current_delivery_status']+1;
            	if ($next_status <= 5) 
            	{
	            	$user = $this->api->getsingletabledata('users', ['id'=>$cart['user_id']], '', 'id', 'asc', 'single');
	            	$provider = $this->api->getsingletabledata('providers', ['id'=>$cart['provider_id']], '', 'id', 'asc', 'single');
	            	if ($next_status == 2) //orde confirmed 
			        {
			            $message = 'Your order of '.$cart['product_name'].' is confirmed by the shop '.$cart['shop_name'];
			        }
			        else if ($next_status == 3) //shipped
			        {
			            $message = 'Your order of '.$cart['product_name'].' is shipped by the shop '.$cart['shop_name'];
			        }
			        else if ($next_status == 4) //out for deivery
			        {
			            $message = 'Your order of '.$cart['product_name'].' is now out for delivery from shop '.$cart['shop_name'];
			        }
			        else
			        {
			            $message = 'Your order of '.$cart['product_name'].' is delivered by the shop '.$cart['shop_name'];
			        }
			        $this->api->insert_notification($provider['token'], $user['token'], $message);
			        //update
			        $this->db->where('id', $user_data['cart_id']);
	        		$this->db->update('product_cart', ['delivery_status'=>$next_status]);
	        		$response_code = '200';
                	$response_message = 'Delivery Status Updated Successfully';
                	$data = new stdClass();
	        	}
	        	else
	        	{
	        		$response_code = '200';
	                $response_message = 'Sorry you cannot do this process';
	                $data = new stdClass();
	        	}
            } 
            else 
            {
                $response_code = '200';
                $response_message = 'Invalid Inputs';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function order_cancel_wallet($cart_id)
    {
        $cart = $this->api->product_cart_details(['product_cart.id'=>$cart_id]);
        //echo "<pre>"; print_r($cart); exit;
        //Add amount to user
        $user_wallet = $this->api->getsingletabledata('wallet_table', ['user_provider_id'=>$cart['user_id'], 'type'=>2], '', 'id', 'asc', 'single');
        $user = $this->api->getsingletabledata('users', ['id'=>$cart['user_id']], '', 'id', 'asc', 'single');
        $uwallet_amt = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $user['currency_code']);
        $order_amt = get_gigs_currency($cart['product_total'], $cart['product_currency'], $user['currency_code']);
        //echo $order_amt; exit;
        $bal_amt = $uwallet_amt + $order_amt;
        //echo $bal_amt; exit;
        $ucurrent_wallet = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $user['currency_code']);
        $ucredit_wallet = get_gigs_currency($cart['product_total'], $cart['product_currency'], $user['currency_code']);
        $uavail_wallet = $ucurrent_wallet+$ucredit_wallet;
        //echo $ucredit_wallet; exit;
        $this->db->where('id', $user_wallet['id']);
        $this->db->update('wallet_table', ['wallet_amt'=>$bal_amt, 'currency_code'=>$user['currency_code']]);
        // //trans history user
        $user_trans_data = array('token'=>$user['token'], 'currency_code'=>$user['currency_code'], 'user_provider_id'=>$cart['user_id'], 'type'=>2, 'tokenid'=>$user['token'], 'payment_detail'=>'product order cancel', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$bal_amt, 'net_amt'=>$bal_amt, 'current_wallet'=>$current_wallet, 'credit_wallet'=>$ucredit_wallet, 'avail_wallet'=>$uavail_wallet, 'reason'=>'Order Cancelled', 'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert('wallet_transaction_history',$user_trans_data);
        //Provider Wallet reduce amount
        $provider_wallet = $this->api->getsingletabledata('wallet_table', ['user_provider_id'=>$cart['provider_id'], 'type'=>1], '', 'id', 'asc', 'single');
        $provider = $this->api->getsingletabledata('providers', ['id'=>$cart['provider_id']], '', 'id', 'asc', 'single');
        $pwallet_amt = get_gigs_currency($provider_wallet['wallet_amt'], $provider_wallet['currency_code'], $provider['currency_code']);
        $porder_amt = get_gigs_currency($cart['product_total'], $cart['product_currency'], $provider['currency_code']);

        $pbal_amt = $uwallet_amt + $porder_amt;
        $pcurrent_wallet = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $provider['currency_code']);
        $pdebit_wallet = get_gigs_currency($cart['product_total'], $cart['product_currency'], $provider['currency_code']);
        $pavail_wallet = $pcurrent_wallet-$pdebit_wallet;
        $this->db->where('id', $provider_wallet['id']);
        $this->db->update('wallet_table', ['wallet_amt'=>$pbal_amt, 'currency_code'=>$provider['currency_code']]);
        //trans history user
        $pro_trans_data = array('token'=>$provider['token'], 'currency_code'=>$provider['currency_code'], 'user_provider_id'=>$cart['provider_id'], 'type'=>1, 'tokenid'=>$provider['token'], 'payment_detail'=>'product order cancel', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$pbal_amt, 'net_amt'=>$pbal_amt, 'current_wallet'=>$pcurrent_wallet, 'debit_wallet'=>$pdebit_wallet, 'avail_wallet'=>$pavail_wallet, 'reason'=>'Order Cancelled', 'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert('wallet_transaction_history',$pro_trans_data);
        return 'success';
    }
    public function invoice_list_post()
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('User_booking','userbooking');
        $this->load->model('products_model');
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyneedparameters(['page_no', 'per_page', 'from_date', 'to_date'],$user_data);
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$where = array('status'=>6, 'provider_id'=>$this->user_id);
			$where_book = array('book_service.status'=>6, 'book_service.provider_id'=>$this->user_id);
	        if ($user_data['from_date']!='') 
	        {
	            $where['service_date >='] = date("Y-m-d", strtotime($user_data['from_date']));
	            $where_book['book_service.service_date >='] = date("Y-m-d", strtotime($user_data['from_date']));
	        }
	        if ($user_data['to_date']!='') 
	        {
	            $where['service_date <='] = date("Y-m-d", strtotime($user_data['to_date']));
	            $where_book['book_service.service_date <='] = date("Y-m-d", strtotime($user_data['to_date']));
	        }
			$total = $this->api->getCountRows('book_service',$where,'id', '', array());
    		//echo "<pre>"; print_r($end); exit;
    		$provider_currency = get_api_provider_currency($this->user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
            $currency_sign = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));

            $result = $this->api->invoice_list($user_data['per_page'], $end, $where_book, $ProviderCurrency, $currency_sign);
         	//echo "<pre>"; print_r($result); exit;
             if($result){
                foreach ($result as $key=>$val){
                   $path = 'uploads/book_service_pdf';
                   if (!file_exists($path)) {
                       mkdir($path, 0777, true);
                   }
                   $filename = $path."/". $val['service_title'].'_'.date('Y-m-d',strtotime($val['updated_on'])).'_'.$val['id'].'_service_pdf.pdf';
                    if(!file_exists($filename)){
                       $where = array('status'=>6, 'id'=>$val['id']);
                       $data['booking'] = $this->products_model->getsingletabledata('book_service', $where, '', 'id', 'asc', 'single');
                       //Service Details
                       $data['service'] = $this->userbooking->service_details(['services.id'=>$data['booking']['service_id']]); 
                       //user details
                       $data['user'] = $this->userbooking->user_details(['users.id'=>$data['booking']['user_id']]);
                       //provider details
                       $data['provider'] = $this->userbooking->provider_details(['providers.id'=>$data['booking']['provider_id']]);
                       $mpdf = new \Mpdf\Mpdf();
                       $mpdf->autoScriptToLang = true;
                       $mpdf->autoLangToFont = true;
                       $html = $this->load->view('user/home/invoice', $data, true);
                       // echo $html;
                       $mpdf->writeHTML($html);
                       
                       $mpdf->Output($filename,'F');
                    }
                    $result[$key]['invoice_download'] = base_url().$filename;
                   
                }
            }
            if ($result) {
                $response_code = '200';
                $response_message = 'Invoice Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['invoice_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function user_invoice_list_post()
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('User_booking','userbooking');
        $this->load->model('products_model');
    	if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyneedparameters(['page_no', 'per_page', 'from_date', 'to_date'],$user_data);
    		$this->verifyRequiredParams(['page_no', 'per_page'], $user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0)
			{
				$end=0;
			}
			$where = array('status'=>6, 'user_id'=>$this->users_id);
			$where_book = array('book_service.status'=>6, 'book_service.user_id'=>$this->users_id);
	        if ($user_data['from_date']!='') 
	        {
	            $where['service_date >='] = date("Y-m-d", strtotime($user_data['from_date']));
	            $where_book['book_service.service_date >='] = date("Y-m-d", strtotime($user_data['from_date']));
	        }
	        if ($user_data['to_date']!='') 
	        {
	            $where['service_date <='] = date("Y-m-d", strtotime($user_data['to_date']));
	            $where_book['book_service.service_date <='] = date("Y-m-d", strtotime($user_data['to_date']));
	        }
			$total = $this->api->getCountRows('book_service',$where,'id', '', array());
    		//echo "<pre>"; print_r($total); exit;
    		$user_currency = get_api_user_currency($this->users_id);
            $UserCurrency = $user_currency['user_currency_code'];
            $currency_sign = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));

            $result = $this->api->invoice_list($user_data['per_page'], $end, $where_book, $UserCurrency, $currency_sign);
         	//echo "<pre>"; print_r($result); exit;
             if($result){
                 foreach ($result as $key=>$val){
                    $path = 'uploads/book_service_pdf';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $filename = $path."/". $val['service_title'].'_'.date('Y-m-d',strtotime($val['updated_on'])).'_'.$val['id'].'_service_pdf.pdf';
                    if(!file_exists($filename)){
                        $where = array('status'=>6, 'id'=>$val['id']);
                        $data_new['booking'] = $this->products_model->getsingletabledata('book_service', $where, '', 'id', 'asc', 'single');
                        //Service Details
                        $data_new['service'] = $this->userbooking->service_details(['services.id'=>$data['booking']['service_id']]); 
                        //user details
                        $data_new['user'] = $this->userbooking->user_details(['users.id'=>$data['booking']['user_id']]);
                        //provider details
                        $data_new['provider'] = $this->userbooking->provider_details(['providers.id'=>$data['booking']['provider_id']]);
                        $mpdf = new \Mpdf\Mpdf();
                        $mpdf->autoScriptToLang = true;
                        $mpdf->autoLangToFont = true;
                        $html = $this->load->view('user/home/invoice', $data_new, true);
                        // echo $html;
                        $mpdf->writeHTML($html);
                        
                        $mpdf->Output($filename,'F');
                     }
                     $result[$key]['invoice_download'] = base_url().$filename;
                    
                 }
             }
            if ($result) {
                $response_code = '200';
                $response_message = 'Invoice Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = ceil($total['cnt']/$user_data['per_page']);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['invoice_list'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
    public function verifyneedparameters($required_fields,$available_fields) 
	{
	    $error = false;
	    $error_fields = "";
	    $request_params = array();
	    $request_params = $available_fields;
		//echo "<pre>"; print_r($request_params); exit;
	    // Handling PUT request params
	    foreach ($required_fields as $field) 
		{
			//echo $request_params[$field];
			//echo "<pre>"; print_r($request_params);
	        if (!isset($request_params[$field])) 
			{
	            $error = true;
	            $error_fields .= $field . ', ';
	        }
	    }
		//echo "<pre>"; print_r($error_fields); exit;
	    if ($error) {
	        // Required field(s) are missing or empty
	        $response_code = '500';
            $response_message = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            $data = new stdClass();
	        $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
            exit;
	        //
	    }
	}
	public function verifyRequiredParams($required_fields,$available_fields) 
	{
	    $error = false;
	    $error_fields = "";
	    $request_params = array();
	    $request_params = $available_fields;
	    
	    foreach ($required_fields as $field) 
		{
	        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) 
			{
	            $error = true;
	            $error_fields .= $field . ', ';
	        }
	    }
		//echo "<pre>"; print_r($error_fields); exit;
	    if ($error) {
	        // Required field(s) are missing or empty
	        // echo error json and stop the app
	        $response_code = '500';
            $response_message = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
            $data = new stdClass();
	        $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
	        exit();
	    }
	}
	
	
	//Shenbagam	
	public function reward_info_get(){ 
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {			
			$reward_details = $this->api->reward_details($this->user_id);			
			if (!empty($reward_details)) 
			{
				$response_code = '200';
				$response_message = "User Details Listed Out for Rewards";
				$data = $reward_details;
			} else {
				$response_code = '500';
				$response_message = "No Details found";
				$data = new stdClass();
			}            
        } else {
			$this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
	}
	public function reward_userlist_post(){ 
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {	
			$user_data = $this->post();
			$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			
			$total = $this->api->reward_user_details($this->user_id,'','','count');
			
			$reward_details = $this->api->reward_user_details($this->user_id, $user_data['per_page'], $end,'');	
			
			if (!empty($reward_details)) 
			{
				$response_code = '200';
				$response_message = "User Details Listed Out for Rewards";
				
				$data['total_rows'] = $total;
                $total_pages = $total/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
				
								
				$det = $this->db->select('allow_rewards, booking_reward_count')->where('id',$this->user_id)->where('status != ',0)->get('providers')->row_array();
				$data['allow_rewards'] = $det['allow_rewards'];
				$data['booking_reward_count'] = $det['booking_reward_count'];
												
				foreach($reward_details as $r => $rec){
					$reward = $this->db->select('id, user_id')->where('user_id',$rec['user_id'])->where('provider_id',$rec['provider_id'])->where('status', 1)->get('service_rewards')->row_array();
					//echo $this->db->last_query();
					$reward_details[$r]['user_reward_exists'] = ($reward['id'])?'YES':'NO';
					$reward_details[$r]['reward_id'] = ($reward['id'])?$reward['id']:'0';
				}
				
				$data['rewards_userlist'] = $reward_details;

			} else {
				$response_code = '500';
				$response_message = "No Details found";
				$data = new stdClass();
			}            
        } else {
			$this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
	}
	public function manage_reward_post(){
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
			$user_data = $this->post();
    		//echo "<pre>"; print_r($user_data); exit();
    		//validate shop
    		$this->verifyRequiredParams(['id', 'user_id', 'service_id', 'description', 'reward_type', 'reward_discount'],$user_data);
    		
            $sid = $user_data['id'];
            unset($user_data['id']);

            $user_data['provider_id'] = $this->user_id;     

			$cnt = $this->db->select('COUNT(user_id) AS total_count')->where('user_id',$user_data['user_id'])->where('provider_id',$user_data['provider_id'])->where('status', 6)->get('book_service')->row()->total_count;
            
    		if ($sid > 0) 
    		{
    			//update
    			$user_data['updated_at'] = date("Y-m-d H:i:s");
				$user_data['total_visit_count'] = $cnt;	
    			$this->db->where('id',$sid);  
				$this->db->update('service_rewards', $user_data);
				$rewards = $sid;    		}
    		else
    		{
    			//insert    
				$user_data['created_at'] = date("Y-m-d H:i:s");	
				$user_data['status'] = 1;					
				$user_data['total_visit_count'] = $cnt;	
    			$this->db->insert('service_rewards', $user_data);
				$rewards = $this->db->insert_id();
    		}    		
			$response_code = '200';
			if ($sid > 0) $response_message = "Rewards Updated successfully";
			else $response_message = "Rewards Added successfully";
			$data = new stdClass();
    	}
    	else
        {
         	$this->token_error();
        }
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
	}
	
	public function service_rewards_history_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			$total = $this->api->getCountRows('service_rewards',['provider_id'=>$this->user_id],'id', '', array());
    		
    		$result = $this->api->service_rewards_history($this->user_id, $user_data['per_page'], $end);
         	
            if ($result) {
                $response_code = '200';
                $response_message = 'Service Rewards History Listed Successfully';
                $data['total_rows'] = $total['cnt'];
                $total_pages = $total['cnt']/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['service_rewards_history'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
	public function delete_service_rewards_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {

            if (isset($_GET['id']) && !empty($_GET['id'])) 
            {
				$table_data = array('status'=>0);
				$this->db->where('id',$_GET['id']);  
				if($this->db->update('service_rewards', $table_data))
				{
					$response_code = '200';
					$response_message = "Rewards Deleted successfully";
					$data = new stdClass();
				}
				else
				{
					$response_code = '500';
					$response_message = "Invalid ID";
					$data = new stdClass();
				}                                
            } else {
                $response_code = '500';
                $response_message = "Id missing";
                $data = new stdClass();
            }
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
	
	public function provider_service_list_get()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {			
			$records = $this->db->select('id, user_id, service_title, currency_code, service_amount, duration, duration_in, service_location')->where('status',1)->where('user_id', $this->user_id)->get('services')->result_array();
			
			if ($records) {			
				$response_code = '200';
				$response_message = "Provider Active Service Lists ";
				$data['provider_service_list'] = $records;
			} else {
				$response_code = '200';
				$response_message = "No Service Records Found";
				$data = new stdClass();
			}                               
            
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
	
	public function service_coupon_info_post()
	{
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			$total = $this->api->service_coupons_info($this->user_id, '', '', 'count');
    		
    		$result = $this->api->service_coupons_info($this->user_id, $user_data['per_page'], $end, '');
         	
            if ($result) {
                $response_code = '200';
                $response_message = 'Services Listed Out for Adding Coupons Successfully';
                $data['total_rows'] = $total;
                $total_pages = $total/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['service_coupons_info'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
		
	}
	/* Payment List - User & Provider */
	
	public function payment_lists_post()
	{
		if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			$total = $this->api->provider_payment_info($this->user_id, $this->users_id, '', '', 'count');
    		
    		$result = $this->api->provider_payment_info($this->user_id, $this->users_id, $user_data['per_page'], $end, '');
         	
            if ($result) {
                $response_code = '200';
				if($this->user_id != '') $response_message = 'Provider Payments Listed Out Successfully';
				else $response_message = 'User Payments Listed Out Successfully';
                $data['total_rows'] = $total;
                $total_pages = $total/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['payments_lists'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
		
	}
	
	/* Review List - User & Provider */
	public function review_lists_post()
	{
		if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			$total = $this->api->review_info($this->user_id, $this->users_id, '', '', 'count');
    		
    		$result = $this->api->review_info($this->user_id, $this->users_id, $user_data['per_page'], $end, '');
         	
            if ($result) {
                $response_code = '200';
                if($this->user_id != '') $response_message = 'Provider Reviews Listed Out Successfully';
				else $response_message = 'User Reviews Listed Out Successfully';
                $data['total_rows'] = $total;
                $total_pages = $total/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['review_lists'] = $result;
            } 
            else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
		
	}
	
	/* Staff - Change Status */
	public function change_staff_status_post()
    {
    	if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post(); 
    		$this->verifyRequiredParams(['staff_id', 'status'],$user_data);
    		
    		$where = array('provider_id'=>$this->user_id, 'id'=>$user_data['staff_id']);
            $staff = $this->api->getsingletabledata('employee_basic_details', $where, '', 'id', 'asc', 'single');
            if (!empty($staff)) 
            {            	
				$book_assign = $this->db->where('staff_id', $user_data['staff_id'])->where_not_in('status',[5,6,7])->from('book_service')->count_all_results(); 
				if(($user_data['status'] == 2 || $user_data['status'] == 0 ) && $book_assign > 0){
					$response_code = '201';
					$response_message = "Status Can't be Changed. Staff is assigned to the service provided by the shop and Inprogress..";
					$data = new stdClass();
				} else {
					$table_data['status'] = $user_data['status'];
					$table_data['delete_status'] = 0;
					if($user_data['status'] == 0){
						$table_data['status'] = 2;
						$table_data['delete_status'] = 1;
					}
					$this->db->where('id',$user_data['staff_id']);  
					if($this->db->update('employee_basic_details', $table_data))
					{						
						$tabledata['delete_status'] = ($user_data['status'] == 0)?1:0;
						$this->db->where('emp_ id',$user_data['staff_id']); 
						$this->db->update('employee_services_list', $tabledata);
						
						$response_code = '200';
						if($user_data['status'] == 1) $response_message = "Staff Status Changed To Active successfully";
						else if($user_data['status'] == 2) $response_message = "Staff Status Changed to Inactive successfully";
						else  $response_message = "Staff Details Deleted Successfully";
						$data = new stdClass();
					}
					else
					{
						$response_code = '201';
						$response_message = "Something went wrong";
						$data = new stdClass();
					}
				}
            }
            else 
            {
                $response_code = '500';
                $response_message = "Invalid Shop ID";
                $data = new stdClass();
            }
            
        } else {
            $this->token_error();
        }

        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
    }
	
	//Search Shops 
	public function search_shops_post() {
        if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {

            $data = array();
            $user_data = $this->post();
            if (!empty($user_data['text']) && !empty($user_data['latitude']) && !empty($user_data['longitude'])) {
                
				$shopresult = $this->api->shop_request_list($user_data);
				//print_r($shopresult);

				if (is_array($shopresult) && !empty($shopresult)) {                   
                    $response['shop_lists'] = $shopresult;
                    $data = $response;
                    $response_code = '200';
                    $response_message = 'Shop Search Result';
                } else {
                    $response_code = '200';
                    $response_message = 'No results found';
                    $data = array();
                }
            } else {
                $response_code = '500';
                $response_message = 'Input field missing';
            }

            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
    }
	
	// Staff Availability
	// Staff Availability
	public function staff_available_post() {
		
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
			
			$user_data = $this->post();
    		$this->verifyRequiredParams(['date', 'provider_id', 'staff_id', 'service_id', 'book_id', 'shop_id', 'duration', 'check_offers'],$user_data);
			
			$category = $this->db->select('category')->where('id',$user_data['provider_id'])->get('providers')->row()->category;
			$pptype = $this->db->select('category_type')->where('id',$category)->get('categories')->row()->category_type;
			
			if($pptype != 4) { // Not a Freelancer
				$query = $this->db->query("SELECT * FROM `services` WHERE id = '".$user_data['service_id']."' AND status =1 and shop_id = '".$user_data['shop_id']."' AND FIND_IN_SET('".$user_data['staff_id']."', staff_id) ");				
			} else { 							
				$query = $this->db->query("SELECT * FROM `services` WHERE id = '".$user_data['service_id']."' AND status =1 and shop_id = '".$user_data['shop_id']."'  ");				
			}
			
			//echo $this->db->last_query();
			/*$query = $this->db->query("SELECT * FROM `services` WHERE id = '".$user_data['service_id']."' AND status =1 and shop_id = '".$user_data['shop_id']."' AND FIND_IN_SET('".$user_data['staff_id']."', staff_id) "); */
			
			$ser = $query->result_array();
			
            if (!empty($ser)) {
				
				$booking_date = $user_data['date'];
				$provider_id  = $user_data['provider_id'];       
				$staff_id     = $user_data['staff_id'];
				$service_id   = $user_data['service_id'];
				$book_id	  = $user_data['book_id'];					
				$shop_id      = $user_data['shop_id'];
				$duration     = $user_data['duration'];
				
				$category = $this->db->select('category')->where('id',$provider_id)->get('providers')->row()->category;
				$pptype = $this->db->select('category_type')->where('id',$category)->get('categories')->row()->category_type;
						
				
				$timestamp = strtotime($booking_date);
				$day = date('D', $timestamp);
				
				if( strpos($staff_id, ",") !== false ) { 
					$stfarr = explode(",",$staff_id);
					$time_array = array();
					foreach($stfarr as $sarr){ 				
						$read_available = $this->api->readAvailability($sarr,$provider_id,$booking_date);				
						$tmearr = explode("-",$read_available);
						
						$t_start = strtotime($tmearr[0]); $t_end = strtotime($tmearr[1]);
						$f_railwayhrs = date('H:i:s', ($t_start)); $t_railwayhrs = date('H:i:s', ($t_end));
						$s_railwayhrs = strtotime($f_railwayhrs); $e_railwayhrs = strtotime($t_railwayhrs);			
						
						$time_array[] = $this->api->get_time_slots($s_railwayhrs,$e_railwayhrs,$duration);	
					}		
				
					$newtime_arr = array();
					$firstarr = $time_array[0];
					for($i=1; $i<count($time_array); $i++){
						$newtime_arr = array_intersect ($firstarr, $time_array[$i]);
						$firstarr = $newtime_arr;
					}
					array_values($newtime_arr);
					$from_time = reset($newtime_arr); $to_time = end($newtime_arr);
					if (!empty($from_time)) {
						$temp_start_time = $from_time;
						$temp_end_time = $to_time;
					} else {
						$temp_start_time = '';
						$temp_end_time = '';
					}
						
				} else {	
					if($pptype != 4) { // Not a Freelancer
						$staff_details = $this->api->providerhours($staff_id,$provider_id);
					} else { 							
						$staff_details = $this->api->shop_hours($shop_id,$provider_id);	
					}
					$availability_details = json_decode($staff_details['availability'], true);
							
					/* Single Staff Business Hours */
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
					/* Single Staff Business Hours */
					
				}
				
				if (!empty($from_time)) {
					$temp_start_time = $from_time;
					$temp_end_time = $to_time;
				} else {
					$temp_start_time = '';
					$temp_end_time = '';
				}

				//echo $temp_start_time." - ".$temp_end_time."\n";
				$search  = array('صباح','مساء');$replace = array('AM', 'PM');
				$temp_start_time = str_replace($search, $replace, $temp_start_time);
				$temp_end_time   = str_replace($search, $replace, $temp_end_time);
				//echo $temp_start_time." - ".$temp_end_time."\n";
				
				$start_time_array = '';
				$end_time_array = '';


				$timestamp_start = strtotime($temp_start_time);
				$timestamp_end = strtotime($temp_end_time);
				//echo $timestamp_start." - ".$timestamp_end."\n";

				$timing_array = array();

				$counter = 1;

				$from_time_railwayhrs = date('G:i:s', ($timestamp_start));
				$to_time_railwayhrs = date('G:i:s', ($timestamp_end));

				$timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
				$timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);


				/* New Timing Array */
				$timing_array = $this->api->read_time_slots($timestamp_start_railwayhrs,$timestamp_end_railwayhrs,$duration);
				/* New Timing Array */
				
				// Booking availability

				$service_date = $booking_date;
				$booking_count = $this->api->getbookings($service_date, $service_id, $staff_id);

				$new_timingarray = array();
				$mat_timingarray = array();

				if (is_array($booking_count) && empty($booking_count)) {
					$new_timingarray = $timing_array;
				} elseif (is_array($booking_count) && $booking_count != '') {
					foreach ($timing_array as $t => $timing) {
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
							if($bookings['id'] != $book_id){
								if (strtotime($timing['start_time']) == strtotime($bookings['from_time']) && strtotime($timing['end_time']) == strtotime($bookings['to_time'])) {
									$mat_timingarray[$t] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
									$match_found = true;
									break;
								}
							}
						}

						if ($match_found == false) {
							$new_timingarray[] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
						}
					}
				}
				
				//print_r($mat_timingarray); 
				$new_timingarray = array_filter($new_timingarray);
		
				if (!empty($new_timingarray)) {
					//$date = date("Y-m-d", strtotime($service_date));
					$starttime1 = $date.' '.$from_time_railwayhrs;  //start time as string
					$endtime1   = $date.' '.$to_time_railwayhrs; //end time as string
										
					$start_time = strtotime($starttime1);
					$end_time = strtotime($endtime1);
					$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
					
					$current_time = strtotime(date('Y-m-d H:i:s'));
					$data_arr = [];
					$d = 1;
					for ($i=0; $slot <= $end_time; $i++) { 
						if($book_id > 0){
							$disable_val = false;
						} else {
							if (date('Y-m-d', strtotime($booking_date)) == date('Y-m-d')) {
								if($start_time <= $current_time && $current_time >= $slot){
									$disable_val = true;
								} else {
									$disable_val = false;
								}
							} else {
								$disable_val = false;
							}
						}
						if( (strtotime($mat_timingarray[$i]['start_time']) != $start_time) && (strtotime($mat_timingarray[$i]['end_time']) != $slot) ){	
							if(!$disable_val){
								
								$data_arr['id'] = "$d";
								$data_arr['start_time'] = date('h:i A', $start_time);
								$data_arr['end_time'] = date('h:i A', $slot);
								$data_arr['is_selected'] = 0;
								$service_availability[] = $data_arr;
							}
						}
						$start_time = $slot;
						$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
						$d++;
					}
					
					$isoffers = $user_data['check_offers'];
					if($isoffers == 1){		
						
						$bkdate = date('Y-m-d', strtotime($booking_date));			
						$offerqry = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->get("service_offers")->row_array(); 
						if($offerqry['id'] > 0) { 
							$date_offers = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->where('start_date <=', $bkdate)->where('end_date >=', $bkdate)->get("service_offers")->row_array();  
																				
							$wtxt = "Offer is not available on this date. You will pay the regular price. Offer available for booking - ".date("d M", strtotime($offerqry['start_date'])).' to '.date("d M, Y", strtotime($offerqry['end_date']))." from ".date("G:i A", strtotime($offerqry['start_time'])).' - '.date("G:i A", strtotime($offerqry['end_time']));
							$OfrMsg = 'Offers Alert';
							
							if($date_offers['id'] > 0) {				
								$offers_availability = array("title" => $OfrMsg, "msg" => "success", "id" => $date_offers['id']);
							} else {				
								$offers_availability = array("title" => $OfrMsg, "msg" => $wtxt, "id" => "0");
							}
						} else {				
							$offers_availability = array("title" => 'Offers Alert', "msg" => 'No offers available', "id" => "0");
						}
					} else {
						$offers_availability = array("title" => 'Offers Alert', "msg" => 'No offers available', "id" => "0");
					}
					
					
					
					if(!empty($service_availability)){						
						$data['time'] = $service_availability;
						$data['offers'] = $offers_availability;
						$response_code = '200';
						$response_message = 'Time Slot Availability and Offers Availability details';
					} else {
						$response_code = '200';
						$response_message = 'Availability not found';
						$data = new stdClass();	
					}

				} else {
					$response_code = '200';
                    $response_message = 'Availability not found';
                    $data = new stdClass();			
				}
				
				$result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
				

			} else {
                $response_code = '500';
                $response_message = 'Invalid Service ID';
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
            } 
        } else {
            $this->token_error();
        }
		
    }
	
	// Booked Service - Content By Booking ID
	public function manage_appointment_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
			
			$user_data = $this->post();
    		$this->verifyRequiredParams(['service_id','id'],$user_data);
			$where = array('status'=>1, 'id'=>$user_data['service_id']);
            $ser = $this->api->getsingletabledata('services', $where, '', 'id', 'asc', 'single');
			//echo $this->db->last_query(); print_r($ser); //exit;
			
            if (!empty($ser)) {
				
				if($user_data['id'] > 0) { 
					$book_details = $this->db->where('id',$user_data['id'])->where('service_id',$ser['id'])->where_not_in('status', [5,6,7])->from('book_service')->get()->row_array();
					//echo $this->db->last_query(); print_r($book_details);
					
					if (empty($book_details)) { 					
						$response_code = '500';
						$response_message = 'Invalid ID or Service ID';
						$result = $this->data_format($response_code, $response_message, $data);
						$this->response($result, REST_Controller::HTTP_OK);
					}
					
					$service_id = $book_details['service_id']; 
					$shop_id = $book_details['shop_id'];
					$staffid = $book_details['staff_id'];
					
					$home_service = (int) $book_details['home_service'];
					$offersid = $book_details['offersid'];
					$couponid = $book_details['couponid'];
					$rewardid = $book_details['rewardid'];

				} else {										
					$service_id = $ser['id'];
					$shop_id = $ser['shop_id'];
					$staffid = $ser['staff_id'];
				}
				
				// Shop and Staff Details
				$stfarr = explode(",",$staffid);
				$stfres = $this->db->select('id, CONCAT(first_name, " ", last_name) AS name, designation, shop_service, home_service,  home_service_area')->where_in('id',$stfarr)->where('provider_id',$ser['user_id'])->where('status',1)->where('delete_status',0)->from('employee_basic_details')->order_by('first_name','ASC')->get()->result_array();

				$shparr = explode(",",$shop_id);
				$shpres = $this->db->select('id, shop_name, shop_location, shop_latitude, shop_longitude')->where_in('id',$shparr)->where('provider_id',$ser['user_id'])->where('status',1)->from('shops')->get()->result_array();
				
				if(isset($offersid) && !empty($offersid)){ $this->db->where('id', $offersid); }
				else { $this->db->where('service_id', $service_id); }
				$offers = $this->db->select('id, provider_id, service_id, offer_percentage')->where("status",0)->where("df",0)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->get("service_offers")->result_array();  
				
				
				if(isset($rewardid) && !empty($rewardid)){ $this->db->where('id', $rewardid); }
				else { $this->db->where('service_id', $service_id); }
				$reward = $this->db->select("id, provider_id, service_id, reward_type, reward_discount, description, IF(reward_type=1,'Discount','Free Service') as reward_type_text")->where("status",1)->where("service_id",$service_id)->where('user_id',$this->users_id)->get("service_rewards")->result_array(); 
				
				$coupon = [];
				if(isset($couponid) && !empty($couponid)){ 
                    $coupon = $this->db->select("id, provider_id, service_id, coupon_name, description, coupon_type, coupon_percentage, coupon_amount,IF(coupon_type=1,'Percentage','Fixed Amount') as coupon_type_text")->where("status",1)->where('id', $couponid)->where("service_id",$service_id)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->get("service_coupons")->result_array(); 
                } 
				
				$usercurrency = get_api_user_currency($this->users_id);
                $UserCurrency = $usercurrency['user_currency_code'];
											
				$data['provider_id'] = $ser['user_id'];
				$data['service_title'] = $ser['service_title'];
				$data['service_amount'] = $ser['service_amount'];
				$data['service_currency_code'] = $ser['currency_code'];
				$data['amount'] = (!empty($UserCurrency)) ? get_gigs_currency($ser['service_amount'], $ser['currency_code'], $UserCurrency) : $user_post_data['amount'];
				$data['currencycode'] = $UserCurrency;
				$data['currencysign'] = (!empty($UserCurrency)) ? currency_code_sign($UserCurrency) : currency_code_sign(settings('currency'));
				$data['duration'] = $ser['duration'];
				$data['duration_in'] = $ser['duration_in'];
				if($user_data['id'] > 0) { 
					$data['action'] = 'edit';
					$data['book_id'] = (int) $user_data['id'];
					$data['location'] = $book_details['location'];
					$data['latitude'] = $book_details['latitude']; 
					$data['longitude'] = $book_details['longitude']; 
					$data['home_service'] =(int) $book_details['home_service'];
					$data['home_service_text'] = ($book_details['home_service'] == 1)?'At Home':'At Shop';
					$data['date'] = date('d-m-Y',strtotime($book_details['service_date']));
					$ftime = date('h:i A',strtotime($book_details['from_time']));
					$ttime = date('h:i A',strtotime($book_details['to_time']));
					$data['time'] = $ftime."-".$ttime;
					$data['notes'] = $book_details['notes'];	
					$data['autoschedule_session_no'] = $book_details['autoschedule_session_no'];
                    $data['coupon'] = $coupon;
				} else{
					$data['action'] = 'add';
					$data['book_id'] = 0;
					$data['location'] = $shpres[0]['shop_location']; 
					$data['latitude'] = ($shpres[0]['shop_latitude'])?$shpres[0]['shop_latitude']:''; 
					$data['longitude'] = ($shpres[0]['shop_longitude'])?$shpres[0]['shop_longitude']:''; 
					$data['home_service'] = 1;
					$data['home_service_text'] = 'At Shop';
					$data['date'] = '';
					$data['time'] = '';
					$data['notes'] = '';
					$data['autoschedule_session_no'] = ($ser['autoschedule']==1)?'1':'0';
                    $data['coupon'] = [];
				}
				$data['autoschedule'] = $ser['autoschedule'];
				$data['autoschedule_txt'] = ($ser['autoschedule']==1)?'Yes':'No';
				$data['autoschedule_days'] = $ser['autoschedule_days'];
				$data['autoschedule_session'] = $ser['autoschedule_session'];
				
								
				$data['shops']  = $shpres;
				$data['staffs'] = $stfres;
				$data['offers'] = $offers;
				$data['reward'] = $reward;
				
				
				$addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$service_id)->get('additional_services')->result_array();				
				$addidata = array();
				if(count($addi_ser) > 0){
					foreach($addi_ser as $a => $ai) {
						$addidata[$a]['id'] = $ai['id'];
						$addidata[$a]['service_id'] = $ai['service_id'];
						$addidata[$a]['service_name'] = $ai['service_name'];
						$addidata[$a]['amount'] = $ai['amount'];
						$addidata[$a]['addi_amount'] = get_gigs_currency($ai['amount'], $ser['currency_code'], $UserCurrency);
						$addidata[$a]['duration'] = $ai['duration'];
						$addidata[$a]['duration_in'] = $ai['duration_in'];
					}
				}
				$data['additional_services'] = $addidata;
				
				$guest_data = $this->db->where('guest_parent_bookid > 0')->where('guest_parent_bookid',$user_data['id'])->get('book_service')->result_array();		
				$data['guest_data'] = $guest_data;
				
				$data['cod_option_status']=settingValue('cod_option');
				$data['moyasar_option_status']=settingValue('moyaser_option');
				
				$response_code = '200';
				if($user_data['id'] > 0) $response_message = 'Edit Booking details';
				else $response_message = 'Add Booking details';
				
				$result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
				
			} else {
                $response_code = '500';
                $response_message = 'Invalid Service ID';
				$result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);
            } 
        } else {
            $this->token_error();
        }
		
	}
	
	// Book Appointment - Content
	public function appointment_details_get(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
    		if($_GET['id'] > 0) { 
				$book_details = $this->db->where('id',$_GET['id'])->from('book_service')->get()->result_array();
				$data['booking'] = $book_details;
				$response_code = '200';
				$response_message = 'Booking details';
				$result = $this->data_format($response_code, $response_message, $data);
                $this->response($result, REST_Controller::HTTP_OK);				
			} else {
                $response_code = '500';
                $response_message = 'Invalid Booking ID';
            } 
        } else {
            $this->token_error();
        }
	}
	
	// Check Availability while booking and Update in the table
	public function update_appointment_post(){
		
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
			
			$user_data = $this->post();
    		$this->verifyRequiredParams(['date', 'time', 'staff_id', 'service_id', 'book_id', 'shop_id', 'location', 'latitude', 'longitude','notes', 'service_at', 'offersid', 'couponid', 'rewardid', 'total_amount'],$user_data);
			$where = array('status'=>1, 'id'=>$user_data['service_id']);
            $ser = $this->api->getsingletabledata('services', $where, '', 'id', 'asc', 'single');
			//print_r($ser);// exit;
            if (!empty($ser)) {
				$booking_date = $user_data['date'];
				$provider_id  = $user_data['provider_id'];       
				$staff_id     = $user_data['staff_id'];
				$service_id   = $user_data['service_id'];
				$book_id	  = $user_data['book_id'];					
				$shop_id      = $user_data['shop_id'];
				$duration     = $user_data['duration'];
				
				$category = $this->db->select('category')->where('id',$provider_id)->get('providers')->row()->category;
				$pptype = $this->db->select('category_type')->where('id',$category)->get('categories')->row()->category_type;
				
				$final_amount = $ser['service_amount'];
				$time = $user_data['time'];
				$booking_time = explode('-', $time);
				$start_time = strtotime($booking_time[0]);
				$end_time = strtotime($booking_time[1]);
				$from_time = date('G:i:s', ($start_time));
				$to_time = date('G:i:s', ($end_time));

				$inputs = array();
				$service_id = $user_data['service_id'];; // Package ID    
				$records = $this->api->get_service_by_id($service_id);
				$cal_seramt = $records['service_amount'];	
				
				$inputs['service_id']    = $service_id;
				$inputs['provider_id']   = $ser['user_id'];
				$inputs['user_id']       = $this->users_id;				
				$inputs['shop_id']       = $user_data['shop_id'];
				$inputs['staff_id']      = $user_data['staff_id'];
				
				$inputs['location'] = $user_data['location'];
				$inputs['latitude'] = $user_data['latitude'];
				$inputs['longitude'] = $user_data['longitude']; 
				$inputs['service_date'] = date('Y-m-d', strtotime($booking_date));        
				$inputs['from_time'] = $from_time;
				$inputs['to_time'] = $to_time;
				$inputs['notes']   = $user_data['notes'];

				$inputs['tokenid'] = 'old type'; 	
					
				$usercurrency = get_api_user_currency($this->users_id);
                $UserCurrency = $usercurrency['user_currency_code'];
				//$inputs['amount'] = get_gigs_currency($final_amount, $ser['currency_code'], $UserCurrency);				
				//$inputs['currency_code'] = $UserCurrency;
				
				$inputs['currency_code'] = $records['currency_code'];		
				$inputs['amount'] = $records['service_amount'];
				
				$inputs['updated_on']  = (date('Y-m-d H:i:s'));	
				$inputs['home_service'] = $user_data['service_at'];
				$inputs['autoschedule_session_no'] = $ser['autoschedule'];
				
				$inputs['offersid'] = $user_data['offersid'];	
				$inputs['rewardid'] = $user_data['rewardid'];
				$rtype = $user_data['reward_type'];
												
				$book_id = $user_data['book_id'];	
								
				$inputs['request_date'] = date('Y-m-d H:i:s');
				$inputs['request_time'] = date('H:i:s', time());
				// $inputs['status'] = 8; // On-Hold Status
								
				$addiamt = 0;
				if(!empty($user_data['addiservice_id'])){
					$addiserids = json_decode($user_data['addiservice_id'], true); 
					$addiseramt = json_decode($user_data['addiservice_amount'], true); 
					$inputs['additional_services'] = implode(',', $addiserids);
					$addiamt = array_sum($addiseramt);
				}
				
				if($book_id > 0){
					$inputs['couponid'] =$user_data['couponid'];
					$qry = $this->db->select('total_amount, final_amount')->where('id',$book_id)->get('book_service')->row_array();
					$tot = $qry['total_amount']; 
					$inputs['total_amount'] = $tot;
					$inputs['final_amount'] = $qry['final_amount']; 
				} else {
                    $inputs['status'] = 8;	
					$inputs['couponid'] = 0;
					
					$totalamt_value = $user_data['total_amount'];
					$totalamt = $cal_seramt;
					$total_amt_val = $totalamt + $addiamt;
					if($total_amt_val <= 0) $total_amt_val = 0;
					$inputs['total_amount'] = $total_amt_val; // On-Hold Status
					$inputs['final_amount'] = $total_amt_val;
					
					
					/*if($inputs['offersid'] == 0){
						$totalamt_value = $user_data['total_amount'];
						$totalamt = $cal_seramt;
						$total_amt_val = $totalamt + $addiamt;
						if($total_amt_val <= 0) $total_amt_val = 0;
						$inputs['total_amount'] = $total_amt_val; // On-Hold Status
						$inputs['final_amount'] = $total_amt_val;
					} else {		*/	
					
					// If Offers available
					if($inputs['offersid'] > 0){
						$offers = $this->db->where("id",$inputs['offersid'])->get("service_offers")->row_array();  			
						$new_serviceamt_val = $user_data['total_amount'] + $addiamt;
						/*$totalamt = $cal_seramt;
						$new_serviceamt = $totalamt + $addiamt;	*/
						
						$new_serviceamt = $inputs['total_amount'];
						$offerPrice = ''; $offersid = 0;
						if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
							$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100 ;	
							if(is_nan($offerPrice)) $offerPrice = 0;	
							$offerPrice = number_format($offerPrice,2);		
							$offersid = $offers['id'];
							
							$new_serviceamt  = $new_serviceamt - $offerPrice;
							$new_serviceamt  = number_format($new_serviceamt,2);
							if($new_serviceamt <= 0) $new_serviceamt = 0;
							$inputs['total_amount'] = $new_serviceamt; // On-Hold Status
							$inputs['final_amount'] = $new_serviceamt;
							$inputs['offersid'] = $offersid;
						} 
					}
					// If Rewards available
					if($inputs['rewardid'] > 0){
						$reward = $this->db->where("id",$inputs['rewardid'])->get("service_rewards")->row_array(); 
						$re_serviceamt = $inputs['total_amount'];
						$rewardPrice = ''; $rewardid = 0; 
						if($rtype == ''){				
							if (!empty($reward['reward_type']) && $reward['reward_type'] == 1 && $reward['id'] > 0) {
								$rewardPrice = ($re_serviceamt) * $reward['reward_discount'] / 100 ; 	
								if(is_nan($rewardPrice)) $rewardPrice = 0;	
								$rewardPrice = number_format($rewardPrice,2);	
								$rewardid = $reward['id'];	
								
								$re_serviceamt  = $re_serviceamt - $rewardPrice;
								$re_serviceamt  = number_format($re_serviceamt,2); 
								if($re_serviceamt <= 0) $re_serviceamt = 0;
								$inputs['total_amount'] = $re_serviceamt; 
								$inputs['final_amount'] = $re_serviceamt;
								$inputs['rewardid'] = $rewardid;
								
							} 
						}
					}
					
					
					
				}
				
				
				//Booking
				//echo "<pre>"; print_r($inputs); exit;
				if ($book_id == 0) {			
					if($rtype != '' && $rtype == '0'){ // Free Service
						$inputs['cod'] = 0;
						$inputs['request_date'] = date('Y-m-d H:i:s');
						$inputs['request_time'] = date('H:i:s', time());
						$bookres = $this->api->booking_success($inputs);
						if ($bookres > 0){
							if($inputs['rewardid'] > 0) {
								$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '".$inputs['rewardid']."' and user_id = ".$this->$user_id." and service_id = ".$service_id);
							}
							
							$response_code = '200';
							$response_message = 'Booked successfully';
							$data['book_id'] = $result;
							$res = $this->data_format($response_code, $response_message, $data);
							$this->response($res, REST_Controller::HTTP_OK);
							
								
						} else { 
							$response_code = '500';
							$response_message = 'Something went wrong. Please try again later';							
							$data = new stdClass();
							$res = $this->data_format($response_code, $response_message, $data);
							$this->response($res, REST_Controller::HTTP_OK);
						}
					} else {
						$result = $this->api->booking_success($inputs);
						//echo $this->db->last_query();
						if ($result != '' && $result > 0) {	
							if($inputs['autoschedule_session_no']  == 1){
								/*$myservice = $this->db->select('service_title,autoschedule, autoschedule_days, autoschedule_session')->where('id',$service_id)->from('services')->get()->row_array();		*/
								if($ser['autoschedule_session'] != 0 && $ser['autoschedule_days'] != 0){ 
									$days = 0;$d=2;
									for($s=1;$s<intval($ser['autoschedule_session']);$s++){ 
										$days = intval($ser['autoschedule_days']);					
										$newdate = date("Y-m-d",strtotime("+".$days." day", strtotime($inputs['service_date'])));					
										$session_no = $d++;
										$inputs['amount'] = 0;
										$inputs['service_date'] = $newdate;
										$inputs['notes']      = $ser['service_title']. "  - Session(".$session_no.")";
										$inputs['autoschedule_session_no'] = $session_no;						
										$inputs['parent_bookid'] = $result;
										$this->api->booking_success($inputs);
									}
								}
							}
							
							
							$response_code = '200';
							$response_message = 'Booking is available. Proceed to Payment.';
							$data['book_id'] = $result;
							$res = $this->data_format($response_code, $response_message, $data);
							$this->response($res, REST_Controller::HTTP_OK);
							//break;
						} else {
							$response_code = '500';
							$response_message = 'Something went wrong. Please try again later';						
							$data = new stdClass();
							$res = $this->data_format($response_code, $response_message, $data);
							$this->response($res, REST_Controller::HTTP_OK);
						}
					}
				}
				
				// Updation
				if($book_id > 0){	
					$inputs['request_date'] = date('Y-m-d');
					$inputs['request_time'] = date('H:i:s', time());
					// $inputs['status'] = 1;
					if ($this->db->update('book_service', $inputs, array("id" => $book_id))) { 
					
						$token = $this->session->userdata('chat_token');
						$data = $this->api->get_book_info($book_id);
						$user_name = $this->api->get_user_info($data['user_id'], 2);
						$service=$this->db->where('id',$service_id)->from('services')->get()->row_array();
						$text = $user_name['name'] . " has edited the Booked Service '".$service['service_title']."'";
						
						$ptype = $this->db->select('type')->where('id',$inputs['provider_id'])->get('providers')->row()->type;
						$this->send_push_notification($token, $book_id,$ptype, $msg = $text);
						
						$response_code = '200';
						$response_message = 'Booking Updated successfully';
						$data['book_id'] = $book_id;						
						$res = $this->data_format($response_code, $response_message, $data);
						$this->response($res, REST_Controller::HTTP_OK);
						//break;
					} else { 				
						$response_code = '201';
						$response_message = 'Something went wrong. Please try again later';						
						$data = new stdClass();
						$res = $this->data_format($response_code, $response_message, $data);
						$this->response($res, REST_Controller::HTTP_OK);
					}
				}
       		
				
			} else {
                $response_code = '500';
                $response_message = 'Invalid ID';
            } 
        } else {
            $this->token_error();
        }
	}
	
	function checkIfExist($fromTime, $toTime, $input) {    
		//echo $fromTime." - ".$toTime." - ".$input."\n";
		$fromDateTime = DateTime::createFromFormat("!H:i", $fromTime);    
		$toDateTime = DateTime::createFromFormat('!H:i', $toTime);
		$inputDateTime= DateTime::createFromFormat('!H:i', $input);
		if ($fromDateTime > $toDateTime) $toDateTime->modify('+1 day');
		return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime) || ($fromDateTime <= $inputDateTime->modify('+1 day') && $inputDateTime <= $toDateTime);		
	}

    public function chat_list_get() {
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
       {
               $sql = "SELECT id,name FROM users WHERE token IN (SELECT DISTINCT(sender_token) FROM chat_table WHERE status = 1 and receiver_token = '". $this->api_token."' UNION SELECT DISTINCT(receiver_token) FROM chat_table WHERE status = 1  and sender_token = '". $this->api_token."')"; 
               $result=$this->db->query($sql)->result_array();
               if($result) {
                   $response_code = 200;
                   $response_message = "Chats Fetched Successfully...";
                   $data = $result;
              } else {
               $response_code = 500;
               $response_message = "Chats are Empty...";
               $data = new stdClass();
              }
              $result = $this->data_format($response_code, $response_message, $data);
              $this->response($result, REST_Controller::HTTP_OK);
       } else {
           $this->token_error();
       }
   }
	
	public function staff_content_post() {
       
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
        $data_input = $this->post();
        $user_id = $this->user_id;
		$category = $data_input['category_id'];
		$subcategory = $data_input['subcategory_id'];
		// $sub_subcategory = $data_input['sub_subcategory_id'];
        $shop_id = $data_input['shop_id'];

        // if (!empty($shop_id) && !empty($category) || !empty($subcategory)) {
            if (!empty($shop_id)) {
            $this->db->select('e.id,e.first_name, s.shop_code');
            $this->db->join('shops s', 's.id = e.shop_id', 'LEFT');		
            $this->db->where('e.status', 1);
            $this->db->where('e.delete_status', 0);
            $this->db->where('e.category', $category);
            $this->db->where('e.subcategory', $subcategory);
            // $this->db->where("FIND_IN_SET('".$sub_subcategory."', e.sub_subcategory)");
            $this->db->where_in('e.shop_id', $shop_id);
            $this->db->where('e.provider_id', $user_id);
            $this->db->order_by('e.shop_id','DESC');
            $query = $this->db->get('employee_basic_details e');
            $result_data = $query->result_array(); 
            if($result_data) {
                $response_code = '200';
                $response_message = 'Employee Details';
                $data = $result_data;
               } else {
                $response_code = '500';
                $response_message = 'No Records found';
                $data = new stdClass();
               }
        } else {
            $response_code = '500';
            $response_message = 'Some Fields are Missing';
            $data = new stdClass();
        }
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
    } else {
        $this->token_error();
    }
    }
	
	/* New Shop Fee API */
	public function newshop_check_get(){
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
			$shoppay = $this->db->where('user_provider_id',$this->user_id)->where("reason LIKE 'Add Shop'")->get('moyasar_table')->num_rows();
			
			$shop_fee = settingValue('shop_fee');
			
			$getShop = $this->db->where('provider_id', $this->user_id)->get('shops')->num_rows();
			$getShop = $getShop - 1;
			
			if($shop_fee > 0 && $getShop == $shoppay) {
				$response_code = '500';
                $response_message = 'You need to pay for adding new shop';
                
				$provider_currency = get_api_provider_currency($this->user_id);
				$ProviderCurrency = $provider_currency['user_currency_code'];
				$shop_fee =settingValue('shop_fee');
				$currency_code = settingValue('currency_option');
				$feeval = get_gigs_currency($shop_fee, $currency_code, $ProviderCurrency);
				
				$response_code = '500';
                $response_message = 'You need to pay for adding new shop';
				$url = base_url().'api/payment/payments?loginid='.$this->user_id.'&amount='.$shop_fee.'&currency='.$currency_code.'&action=2';
                $data['url'] = $url;
                $data['shop_amount'] = settingValue('shop_fee');
			} else {
			  $response_code = '200';
              $response_message = 'New Shop Fee Paid. You Can Add New Shop';
              $data['url'] = '';
              $data['shop_amount'] = '';
			}
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->token_error();
		}
	}
	/* Check Availability Time Slot API */
	public function check_availability_post(){
		
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {
            $data = array();
            $user_data = array();
			
			$user_data = $this->post();
    		$this->verifyRequiredParams(['date', 'provider_id', 'staff_id', 'service_id', 'book_id', 'shop_id', 'time', 'check_offers', 'servicefor','action'],$user_data);
			
			$query = $this->db->query("SELECT * FROM `services` WHERE id = '".$user_data['service_id']."' AND status =1 and shop_id = '".$user_data['shop_id']."' AND   FIND_IN_SET('".$user_data['staff_id']."', staff_id) ");
			$ser = $query->result_array();
			
            if (!empty($ser)) {
				
				$service_date = $user_data['date'];
				$provider_id  = $user_data['provider_id'];       
				$staff_id     = $user_data['staff_id'];
				$service_id   = $user_data['service_id'];
				$book_id	  = $user_data['book_id'];					
				$shop_id      = $user_data['shop_id'];
				$action       = $user_data['action'];
				
				$category = $this->db->select('category')->where('id',$provider_id)->get('providers')->row()->category;
				$pptype = $this->db->select('category_type')->where('id',$category)->get('categories')->row()->category_type;
				$time = $user_data['time'];
				$booking_time = explode('-', $time);
		
				$timestamp = strtotime($service_date);
				$day = date('D', $timestamp);

				if($pptype != 4) { // Not a Freelancer
					$staff_details = $this->api->providerhours($staff_id,$provider_id);
				} else { 			
					$staff_details = $this->api->shop_hours($shop_id,$provider_id);
				}
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
					$data = new stdClass();
					$res = $this->data_format($response_code, $response_message, $data);
					$this->response($res, REST_Controller::HTTP_OK);
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

				
				// Booking availability


				$booking_from_time = $booking_time[0];
				$booking_end_time = $booking_time[1];

				$timestamp_from = strtotime($booking_from_time);
				$timestamp_to = strtotime($booking_end_time);

				$from_time_railwayhrs = date('H:i:s', ($timestamp_from));
				$to_time_railwayhrs = date('H:i:s', ($timestamp_to));

				
				
				$from_rh = date('H:i', strtotime($from_time_railwayhrs));
				$to_rh   = date('H:i', strtotime($to_time_railwayhrs));	
				
				/* Booking Available */
				$bookingcount = $this->api->get_bookings_date($service_date, $service_id, $from_time_railwayhrs, $to_time_railwayhrs, $inputs['staff_id'],$book_id); 
				
				$book_noslot = '';
				if (count($bookingcount) > 0) { 
					$book_noslot = 1;
					$message = 'Booking is not available for the selected time slot...';
					$response_code = '500';
					$response_message = $message;
					$data = new stdClass();
					$res = $this->data_format($response_code, $response_message, $data);
					$this->response($res, REST_Controller::HTTP_OK);		
				}
				/* Booking Available */
				
				if($_POST['servicefor'] == 1) { 
					/* User Booking & Time Slot Checking */
					$user_noslot = '';
					$user_booking_count = $this->api->user_get_bookings($service_date, $from_time_railwayhrs, $to_time_railwayhrs,$book_id,$this->users_id);
					if(count($user_booking_count) > 0){
						$user_noslot = 1;
						$message = 'You already booked the selected time slot';
						$response_code = '500';
						$response_message = $message;
						$data = new stdClass();
						$res = $this->data_format($response_code, $response_message, $data);
						$this->response($res, REST_Controller::HTTP_OK);
					}
					$usertime_booking_count = $this->api->user_time_bookings($service_date,$book_id,$this->users_id,$action);
					
					
					$usernoslot = '';
					if(count($usertime_booking_count) > 0){
						foreach ($usertime_booking_count as  $b => $bookedtime) {
							$ufromTime = date('H:i', strtotime($bookedtime['from_time']));
							$utoTime   = date('H:i', strtotime($bookedtime['to_time']));
							$usernoslot   = $this->checkIfExist($ufromTime, $utoTime, $from_rh);				
							if($usernoslot != 1){ 
								$usernoslot = $this->checkIfExist($ufromTime, $utoTime, $to_rh);	
								if($usernoslot == 1) break;	
							} else { 
								break;
							}
						}			
					}
					
					if ($usernoslot == 1) { 
						$message = 'You have another booking at the selected  time slot';
						$response_code = '500';
						$response_message = $message;
						$data = new stdClass();
						$res = $this->data_format($response_code, $response_message, $data);
						$this->response($res, REST_Controller::HTTP_OK);
					}		
					/*  User Booking & Time Slot Checking */
				
				}
				
				/* Staff Available */
				$staff_id = $this->post('staff_id');
				$staff_book_count = $this->api->get_bookings_staff($service_date,$staff_id,$book_id,$action);
				//print_r($staff_book_count); echo "\n";
					
				$noslot = '';
				if(count($staff_book_count) > 0){
					foreach ($staff_book_count as  $b => $booked_time) {
						$fromTime = date('H:i', strtotime($booked_time['from_time']));
						$toTime   = date('H:i', strtotime($booked_time['to_time']));
						$noslot   = $this->checkIfExist($fromTime, $toTime, $from_rh);				
						if($noslot != 1){ 
							$noslot = $this->checkIfExist($fromTime, $toTime, $to_rh);					
							if($noslot == 1) break;	
						} else { 
							break;
						}
					}			
				}
				//echo $noslot;exit;
				if ($noslot == 1) {
					$message = 'Staff is not available for the selected time slot...';					
					$response_code = '500';
					$response_message = $message;
					$data = new stdClass();
					$result = $this->data_format($response_code, $response_message, $data);
                    $this->response($result, REST_Controller::HTTP_OK);
				}		
				/* Staff Available */
				
				$isoffers = $user_data['check_offers'];
				if($isoffers == 1){		
					$offerqry = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->get("service_offers")->row_array(); 
					
					if($offerqry['id'] > 0) {
						$bkdate = date('Y-m-d', strtotime($service_date));
						$current_time = date('H:i:s');
						
						$time_offers = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->where('start_date <=', $bkdate)->where('end_date >=', $bkdate)->where( "'$from_time_railwayhrs' BETWEEN start_time AND end_time",NULL, FALSE)->where( "'$to_time_railwayhrs' BETWEEN start_time AND end_time",NULL, FALSE)->get("service_offers")->row_array();
										
												
						$wtxt = "Offer is not available on this time slot. You will pay the regular price. Offer available for booking - ".date("d M", strtotime($offerqry['start_date'])).' to '.date("d M, Y", strtotime($offerqry['end_date']))." from ".date("G:i A", strtotime($offerqry['start_time'])).' - '.date("G:i A", strtotime($offerqry['end_time']));
						$OfrMsg = 'Offers Alert';
						
						if($time_offers['id'] > 0) {										
								$offers_availability = array("title" => $OfrMsg, "msg" => "success", "id" => $date_offers['id']);
							} else {				
								$offers_availability = array("title" => $OfrMsg, "msg" => $wtxt, "id" => "0");
							}
						} else {				
							$offers_availability = '';
						}
					} else {
						$offers_availability = '';
					}
					
					if ($noslot == '' && $user_noslot == '' && $book_noslot == '' ) {						
						$responsemessage = 'Time Slot Available';
						if(!empty($offers_availability)){						
							$data['booking'] = $responsemessage;
							$data['offers'] = $offers_availability;
							$response_code = '200';
							$response_message = 'Time Slot and Offers Availability details';
							$result = $this->data_format($response_code, $response_message, $data);
							$this->response($result, REST_Controller::HTTP_OK);
						} else {
							$response_code = '200';							
							$data['booking'] = $responsemessage;
							$result = $this->data_format($response_code, $responsemessage, $data);
							$this->response($result, REST_Controller::HTTP_OK);
						}
					}
        
			} else {
                $response_code = '500';
                $response_message = 'Invalid ID';
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
            } 
        } else {
            $this->token_error();
        }
		
	}
	
	public function cancel_appointment_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {          
			$user_data = $this->post();
			$bookid=$user_data['book_id'];
					
			if ($bookid > 0) {
				$bookid = trim($bookid); 
				
				$this->db->where(array('id' => $bookid));
				$this->db->delete('book_service');
				
				$this->db->where(array('parent_bookid' => $bookid));  
				$this->db->delete('book_service');
				
				$this->db->where(array('guest_parent_bookid' => $bookid));  
				$this->db->delete('book_service');				
				
				$response_code = '200';
				$response_message = 'Booking Cancelled Successfully';
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
			} else {
				$response_code = '500';
				$response_message = 'Invalid ID';
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
			} 
		} else {
			$this->token_error();
		}
	}
	
	public function guest_booking_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) {          
			$post = $this->post();
			$bookid=$post['book_id'];			
			if ($bookid > 0) {
				
				$url = encrypt_url($bookid,$this->config->item('encryption_key'));
				
				$addiserv= json_decode($post['guest_details'], true);
				$user_currency = get_api_user_currency($this->users_id);        		
				$user_currency_code = $user_currency['user_currency_code'];
				
				if(!empty($addiserv)) 
				{	
					$alltotal = 0;
					foreach ($addiserv as $k => $val) 
					{
						//print_r($val);
						$time = $val['time'];
				
						$booking_time = explode('-', $time);
						$start_time = strtotime($booking_time[0]);
						$end_time = strtotime($booking_time[1]);
						$from_time = date('G:i:s', ($start_time));
						$to_time = date('G:i:s', ($end_time));

						$inputs = array();
						$inputs['user_id']       = $this->users_id;
						$service_id = $val['service_id']; // Package ID  
						
						$records = $this->api->get_service_by_id($service_id);
						$cal_seramt = $records['service_amount'];
						
						$bookings = $this->db->select('home_service, location, latitude, longitude, service_date
						')->where('id', $bookid)->get('book_service')->row_array();
						
						$inputs['service_id']    = $service_id;
						$inputs['provider_id']   = $records['user_id'];						
						$inputs['shop_id']       = $records['shop_id'];
																
						$inputs['location'] = $bookings['location'];
						$inputs['latitude'] = $bookings['latitude'];
						$inputs['longitude'] = $bookings['longitude']; 
						$inputs['home_service'] = $bookings['home_service'];
						$inputs['service_date'] = date('Y-m-d', strtotime($bookings['service_date']));  
						
						$inputs['staff_id']  = $val['staff_id']; 
						$inputs['from_time'] = $from_time;
						$inputs['to_time'] = $to_time;
						

						$inputs['tokenid'] = 'old type'; 	
						//$inputs['currency_code'] = $user_currency_code;		
						//$inputs['amount'] = get_gigs_currency($records['service_amount'], $records['currency_code'], $user_currency_code);
						
						$inputs['currency_code'] = $records['currency_code'];		
						$inputs['amount'] = $records['service_amount'];
						
						$inputs['cod'] = 0;					
						$inputs['updated_on']  = (date('Y-m-d H:i:s'));	
						$inputs['request_date'] = date('Y-m-d');
						$inputs['request_time'] = date('H:i:s', time());
						$inputs['status'] = 8; // On-Hold Status
						
						
						$current_time = date('H:i:s');
						$booking_from_time = $booking_time[0];
						$booking_end_time = $booking_time[1];

						$timestamp_from = strtotime($booking_from_time);
						$timestamp_to = strtotime($booking_end_time);

						$from_time_railwayhrs = date('H:i:s', ($timestamp_from));
						$to_time_railwayhrs = date('H:i:s', ($timestamp_to));

						$offers = $this->db->where("status",0)->where("df",0)->where("service_id",$service_id)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where( "'$from_time_railwayhrs' BETWEEN start_time AND end_time",NULL, FALSE)->where( "'$to_time_railwayhrs' BETWEEN start_time AND end_time",NULL, FALSE)->get("service_offers")->row_array();
						
						$new_serviceamt_val = $inputs['amount'];
						$new_serviceamt = $cal_seramt;
						$offerPrice = ''; $offersid = 0;
						if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
							$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100 ;	
							if(is_nan($offerPrice)) $offerPrice = 0;	
							$offerPrice = number_format($offerPrice,2);		
							$offersid = $offers['id'];
							
							$new_serviceamt  = $new_serviceamt - $offerPrice;
							$new_serviceamt  = number_format($new_serviceamt,2); 
							if($new_serviceamt <= 0) $new_serviceamt = 0;
						} 
						
						
						$inputs['offersid'] = $offersid;
						$inputs['total_amount'] = $new_serviceamt; 
						
						$inputs['autoschedule_session_no'] = $records['autoschedule'];
																		
						$inputs['service_for'] = $val['service_for'];
						if($inputs['service_for'] == 1){ //Myself
							$inputs['guest'] = 0;
							$inputs['guest_name'] = '';
						} else {
							$inputs['guest'] = 1;
							$inputs['guest_name'] = $post['guest_name'][$k];					
						}
						$inputs['guest_parent_bookid'] = $post['book_id'];
						
						$alltotal += $new_serviceamt; 
						
						//print_r($_POST);
						//print_r($inputs); //exit;
													
						$result = $this->api->booking_success($inputs);
						//echo $this->db->last_query();
						if ($result != '' && $result > 0) {	
							if($inputs['autoschedule_session_no']  == 1){
								$myservice = $this->db->select('service_title,autoschedule, autoschedule_days, autoschedule_session')->where('id',$service_id)->from('services')->get()->row_array();					
								if($myservice['autoschedule_session'] != 0 && $myservice['autoschedule_days'] != 0){ 
									$days = 0;$d=2;
									for($s=1;$s<intval($myservice['autoschedule_session']);$s++){ 
										$days = intval($myservice['autoschedule_days']);					
										$newdate = date("Y-m-d",strtotime("+".$days." day", strtotime($inputs['service_date'])));					
										$session_no = $d++;
										$inputs['amount'] = 0;
										$inputs['service_date'] = $newdate;
										$inputs['notes']      = $myservice['service_title']. "  - Session(".$session_no.")";
										$inputs['autoschedule_session_no'] = $session_no;						
										$inputs['parent_bookid'] = $result;
										$this->api->booking_success($inputs);
									}
								}
							}
						}
					} 
					
					$qry = $this->db->select('total_amount, final_amount')->where('id',$bookid)->get('book_service')->row_array();
					$tot = $qry['final_amount']; 
					$inp['final_amount'] = $tot + $alltotal;
					//print_r($inp);
					//exit;
					
					$this->db->update('book_service', $inp, array("id" => $bookid));	
						
						
					$response_code = '200';
					$response_message = 'Proceed To Payment';
					
					$data['id'] = $url;
					/*$urllink = base_url()."api/payment/payments?loginid=".$this->users_id."&action=3&bookid=".$bookid."&amount=".$inp['final_amount']."&currency=".$user_currency_code."&coupon_id=0&cod=2" ;*/
					
					$user_currencycode = settingValue('currency_option');
					
					$urllink = base_url()."api/payment/payments?loginid=".$this->users_id."&action=3&bookid=".$bookid."&amount=".
					$inp['final_amount']."&currency=".$user_currencycode."&coupon_id=0&cod=2" ;
					
					$data['url'] = $urllink;
					
					$result = $this->data_format($response_code, $response_message, $data);
					$this->response($result, REST_Controller::HTTP_OK);					
					
				} else {				
					$response_code = '200';
					$response_message = 'Proceed To Payment';
					$qry = $this->db->select('total_amount, final_amount')->where('id',$bookid)->get('book_service')->row_array();
					$tot = $qry['final_amount']; 
					
					$data['id'] = $url;
					
					/*$urllink = base_url()."api/payment/payments?loginid=".$this->users_id."&action=3&bookid=".$bookid."&amount=".$tot."&currency=".$user_currency_code."&coupon_id=0&cod=2";*/
					
					$user_currencycode = settingValue('currency_option');
					
					$urllink = base_url()."api/payment/payments?loginid=".$this->users_id."&action=3&bookid=".$bookid."&amount=".$tot."&currency=".$user_currencycode."&coupon_id=0&cod=2" ;
					
					$data['url'] = $urllink;
					
					$result = $this->data_format($response_code, $response_message, $data);
					$this->response($result, REST_Controller::HTTP_OK);
				}
			} else {
				$response_code = '500';
				$response_message = 'Invalid ID';
				$data = new stdClass();
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
			} 
		} else {
			$this->token_error();
		}
	}
	
	public function booking_services_list_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['provider_id', 'shop_id'],$user_data);
			$user_id = $user_data['provider_id'];
			$shop_id = $user_data['shop_id'];
			$this->db->select('id, service_title, user_id as provider_id, shop_id, staff_id, service_amount, currency_code, duration, duration_in');
			$this->db->where('user_id',$user_id);
			$this->db->where('shop_id',$shop_id);
			$this->db->where('status',1);
			
			$this->db->order_by('id','ASC');
			$res = $this->db->get('services')->result_array();
			
			if ($res) {
                $response_code = '200';				
				$response_message = 'Service Lists of the Shop';		
                $data['service_lists'] = $res;
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
			
		} else {
            $this->token_error();
        }
	}
	public function booking_services_stafflist_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['service_id'],$user_data);
			$id = $user_data['service_id'];
			$staff=$this->db->select('staff_id')->get_where('services', array('id' => $id))->row()->staff_id;
			$stfarr = explode(",", $staff);
			$this->db->select('id, first_name as name');		
			$this->db->where_in('id',$stfarr);
			$this->db->where('status', 1);
			$this->db->where('delete_status', 0); 
			
			$this->db->order_by('first_name','ASC');
			$res = $this->db->get('employee_basic_details')->result_array();
			
			if ($res) {
                $response_code = '200';	
				$response_message = 'Staff Lists for Services';				
                $data['staff_lists'] = $res;
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
			
		} else {
            $this->token_error();
        }
	}
	
	public function booking_summary_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['book_id'],$user_data);
			$id = $user_data['book_id'];
			//$id = 658;
			$bookings = $this->db->where('id',$id)->from('book_service')->where('status',8)->get()->row_array();
			
			if (!empty($bookings)) {	
				$bservices = $this->db->select('id, service_id, currency_code, amount, service_date, from_time, to_time, shop_id, additional_services, offersid, rewardid, service_for, guest, guest_parent_bookid, guest_name, total_amount, final_amount, guest_parent_bookid')->where('id = '.$id. ' or guest_parent_bookid = '.$bookings['id'])->get('book_service')->result_array();
				//print_r($bservices);
				foreach($bservices as $v => $b){
					//print_r($b);
					$datas['services'][$v] = $b;
					$sname = $this->db->select('service_title')->where('id',$b['service_id'])->get('services')->row()->service_title;
					$shname = $this->db->select('shop_name')->where('id',$b['shop_id'])->get('shops')->row()->shop_name;
					$datas['services'][$v]['service_title'] = $sname;
					$datas['services'][$v]['shop_title'] = $shname;
					$addiarr = explode(",", $b['additional_services']);
					$addi_ser = $this->db->select('id,service_name')->where_in('id',$addiarr)->get('additional_services')->result_array();
					$datas['services'][$v]['addiservices'] = $addi_ser;
                    $datas['services'][$v]['currency_code_symbol'] = currency_code_sign($b['currency_code']);
				}
				$response_code = '200';
                $response_message = 'Booking Summary';
                $data = $datas;
			} else {
				$response_code = '500';
                $response_message = 'Invalid Booking ID';
                $data = new stdClass();
			}
			
			$result = $this->data_format($response_code, $response_message, $data);			
            $this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	public function check_coupon_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['coupon_name', 'service_id'],$user_data);
			$cid = $user_data['coupon_name'];
			$sid = $user_data['service_id'];
			$coupon = $this->db->select('id, provider_id,service_id,coupon_name, coupon_type,coupon_percentage,coupon_amount')->where("status",1)->where("coupon_name",$cid)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where('service_id',$sid)->get("service_coupons")->row_array(); 
			//echo $this->db->last_query();
			if($coupon['id'] > 0) { 
				$response_code = '200';
                $response_message = 'Coupon Details';
               
				$coupon['coupon_type_txt'] = ($coupon['coupon_type'] == 1)?"Percentage":"Fixed Amount";
				$data['coupon'] = $coupon;

			} else {				
				$response_code = '500';
                $response_message = 'Invalid Coupon';
                $data['coupon'] = new stdClass();
			}
			$result = $this->data_format($response_code, $response_message, $data);			
            $this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	public function deposit_history_get(){
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
			//$this->user_id = 91;
            $this->db->select('deposit_details.*,CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img');
            $this->db->join('providers','providers.id=deposit_details.provider_id');
			$this->db->where('deposit_details.provider_id',$this->user_id);
            $this->db->order_by('deposit_details.id','desc');
            $dep = $this->db->get('deposit_details')->result_array();
			if(count($dep) > 0){
				$response_code = '200';
                $response_message = 'Deposit History Details';     
				$data['deposit'] = $dep;
				$data['deposited_by'] = "Admin";
				$data['currency_code'] = "SAR";
			} else{
				$response_code = '200';
                $response_message = 'No Records Found';
                $data['deposit'] = [];
                $data['deposited_by'] = "Admin";
				$data['currency_code'] = "SAR";
			}
			$result = $this->data_format($response_code, $response_message, $data);			
            $this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	/* Payment List - Provider & User Order */	
	public function order_payment_lists_post()
	{
		if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['page_no', 'per_page'],$user_data);
    		$end = ($user_data['page_no'] - 1)*$user_data['per_page'];
    		if($end<0){
				$end=0;
			}
			$total = $this->api->provider_orderpayment_info($this->user_id, $this->users_id, '', '', 'count');
    		
    		$result = $this->api->provider_orderpayment_info($this->user_id, $this->users_id, $user_data['per_page'], $end, '');
         	//echo "<pre>"; print_r($total); exit();
            if ($result) {
                $response_code = '200';
				if($this->user_id != '') $response_message = 'Provider Orders Payments Listed Out Successfully';
				else $response_message = 'User Orders Payments Listed Out Successfully';
                $data['total_rows'] = $total;
                $total_pages = $total/$user_data['per_page'];
				$total_pages = ceil($total_pages);
                $data['total_pages'] = ($total_pages<1 ? 1 : $total_pages);
                $data['order_payments_lists'] = $result;
            } else {
                $response_code = '200';
                $response_message = 'No Records found';
                $data = new stdClass();
            }
			
            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        } else {
            $this->token_error();
        }
		
	}
	
	
	/* Covid Vaccine Status - Update */
	public function covid_vaccine_update_post(){
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{
    		$user_data = $this->post();
    		$this->verifyRequiredParams(['covid_vaccine'],$user_data);
			$stat = $user_data['covid_vaccine'];
			$datas['covid_vaccine'] = $stat;
			$this->db->where('id', $this->users_id);
			
			$arr = array(0=>"status reset", 1=>"yes, one injection", 2=>"yes, two injection", 3=>"under 18", 4=>"no");
			
			if($stat != 4){		
				//$datas['status'] = 1;
				$result = $this->db->update('users', $datas);
				$msg = "Covid Vaccination Status for User Updated Successfully";
				
				$data['allow_user'] = 'Yes';
				$data['covid_vaccine'] = $stat;
				$data['covid_vaccine_txt'] =$arr[$stat];
				
				$response_code = '200';
                $response_message = $msg;
			} else {
				$datas['status'] = 2;
				$datas['last_login'] = date('Y-m-d H:i:s');
				$result = $this->db->update('users', $datas);				
				
                $data['allow_user'] = 'No';
				$data['covid_vaccine'] = $stat;	
				$data['covid_vaccine_txt'] =$arr[$stat];
				
				$msg = "you're not allow to book any service for now due to local authority";
				$response_code = '200';
                $response_message = $msg;
			}
			$result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
		} else {
            $this->token_error();
        }
	}
	
	//Get Country details from countries table
	public function countries_get() {
        $data = $this->db->select('name,dial_code,code')->order_by('name', 'asc')->get('countries')->result_array();
        $response_code = 200;
        $response_message = "Fetched Successfully...";
        $result = $data;
        $this->response($result, REST_Controller::HTTP_OK);
    }

    //Update booking service 
    public function booking_service_payment_post() {
    	if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{

    	$postdata = $this->post();
    	$this->verifyRequiredParams(['tokenid','booking_id','cod', 'type', 'amount', 'status', 'paytype'],$postdata);

		$amt = $postdata['amount'];
		$paid_token = $postdata['tokenid'];
		
		if($this->post('booking_id') != '' && $bookid ==''){
			$bookid = $this->post('booking_id');
		}
		
		$servicess = $this->db->select('*')->where('id',$bookid)->get('book_service')->row_array();
		
		$service_id = $servicess['service_id']; 
		
		$inputs = array();		
		
		$inputs['cod'] = 2; // Online Payment
		
		if(!empty($this->post('type')) && $this->post('type') == 'cod'){ 
			$inputs['cod'] = 1;	
			$couponid = $this->post('cid');
			$amt = $this->post('totalamt');
			$paid_token = '';
		}		
		
		if((isset($postdata['status']) && $postdata['status'] == 'paid')  || $inputs['cod'] == 1 ) {	
			
			
			if(!empty($couponid) && $couponid > 0){
				$cinputs['couponid'] = $couponid;	
			}
			
			$result = $bookid;
			
			if ($result != '') {	
				$inputs['status'] = 2; 
				$inputs['reason'] = '';
				$inputs['final_amount'] = $amt;
				$inputs['paid_tokenid'] = $paid_token;
				$inputs['paytype'] = $postdata['paytype'];
				
				$this->db->update('book_service', $inputs, array("id" => $result));
				
				$this->db->update('book_service', $cinputs, array("id" => $result));
				
				// $ginputs['status'] = 2;
				// $this->db->update('book_service', $ginputs, array("id" => $result));
								
				$service=$this->db->where('id',$service_id)->from('services')->get()->row_array();
				
				if($servicess['autoschedule_session_no']  == 1){	
					$inputs['total_amount'] = 0; $inputs['final_amount'] = 0;
					$this->db->update('book_service', $inputs, array("parent_bookid" => $result));					
				}
				
				// Coupon Count Update
				if(!empty($couponid) && $couponid > 0){
					$couponqry = $this->db->select('user_limit, user_limit_count, used_user_id')->where('id',$couponid)->get('service_coupons')->row_array();
					$used_coupon = 	$couponqry['used_user_id'];
					if(!empty($used_coupon)) {
						$userids = $used_coupon.','.$this->user_id;
					} else {
						$userids = $this->user_id;
					}

					$cno = intval($couponqry['user_limit_count']) + 1;
					$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '". $cno ."', `used_user_id` = '".$userids."' WHERE `id` = '".$couponid."'");
					if($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno){
						$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '".$couponid."'");
					}
				}

				// Reward Update
				$rewardid = $servicess['rewardid'];
				if(!empty($rewardid) && $rewardid > 0){
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '".$rewardid."' and user_id = ".$this->session->userdata('id')." and service_id = ".$service_id);
				}
				
				// Update Revenue
				$query = $this->db->query('select * from admin_commission where admin_id=1');
                $amount = $query->row();
                $pertage = $amount->commission;
				$vatper = settingValue('vat');
				
				if($inputs['cod'] == 2){
					$revenueInsert = [
						'date' => date('Y-m-d'),
						'provider' => $servicess['provider_id'],
						'service_id' => $service_id,
						'booking_id' => $result,
						'user' => $this->session->userdata('id'),
						'currency_code' => $servicess['currency_code'],
						'amount' => $service['service_amount'],
						'commission' => $pertage,
						'vat' => $vatper,
						'offersid' => $servicess['offersid'],
						'couponid' => $servicess['couponid'],
						'rewardid' => $servicess['rewardid'],
						'revenue_for' => 'Service Booking'
					];
					$revInsert = $this->db->insert('revenue', $revenueInsert);
				}
								
				$provider_data = $this->db->where('id',$servicess['provider_id'])->from('providers')->get()->row_array();
				$user_data = $this->db->where('id',$this->session->userdata('id'))->from('users')->get()->row_array();
				$this->data['provider'] = $provider_data;
				
				$preview_link = base_url();
				$time = $servicess['from_time']."-".$servicess['to_time'];
				
						
				$bodyid = 3;
				$tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
				$providerbody = $tempbody_details['template_content'];
				$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
				$providerbody = str_replace('{sitetitle}',$this->site_name, $providerbody);
				$providerbody = str_replace('{user_person}',$this->session->userdata('name'), $providerbody);
				$providerbody = str_replace('{service_title}',$service['service_title'], $providerbody);
				$providerbody = str_replace('{service_date}',$servicess['service_date'], $providerbody);
				$providerbody = str_replace('{service_time}',$time, $providerbody);
				$providerbody = str_replace('{location_user}',$servicess['location'], $providerbody);
				$providerbody = str_replace('{preview_link}',$preview_link, $providerbody);
				$providerbody .= $qr_message;
				
				 //Send mail to provider
				$phpmail_config=settingValue('mail_config');
					if(isset($phpmail_config)&&!empty($phpmail_config)){
						if($phpmail_config=="phpmail"){
						$from_email=settingValue('email_address');
					} else {
						$from_email=settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');				
				 
				if(!empty($from_email)&&isset($from_email)){
					$mail = $this->email
					->from($from_email)
					->to($provider_data['email'])
					->subject('Service Booking')
					->message($providerbody)
					->send();
				}			
				 //Send mail to user
				$body = $this->load->view('user/email/service_email', $this->data, true);
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');
				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
							->from($from_email)
							->to($this->session->userdata('email'))
							->subject('Service Booking')
							->message($body)
							->send();
				}
				
				$provider_data = $this->db->where('id',$servicess['provider_id'])->from('providers')->get()->row_array();

				/* moyasar payment history entry */
				if($inputs['cod'] == 2){ // not cod					
					$totamt = $postdata['amount'];
					$details['service_subscription_id'] = $service_id; 
					$details['token'] = $provider_data['chat_token'];
					$details['user_provider_id'] = $this->user_id;
					$details['currency_code'] = $servicess['currency_code'];
					$details['amount'] = $servicess['amount'];
					$details['reason'] = "Book Service";
					$details['transaction_id'] = $postdata['id'];
					$details['created_at'] = date('Y-m-d H:i:s'); 
					$details['book_id'] = $result;
					$details['total_amount'] = $totamt;
					$details['type']=$provider_data['type'];
					$details['paytype'] = $postdata['paytype'];  
					$this->db->insert('moyasar_table', $details); 
				}

				/* history entry */
				$data = $this->api->get_book_info($result);
				
				$ptype = $this->db->select('type')->where('id',$data['provider_id'])->get('providers')->row()->type;
				
				$device_token = $this->api->get_device_info_multiple($data['provider_id'], $ptype);

				$user_name = $this->api->get_user_info($data['user_id'], 2);

				$provider_token = $this->api->get_user_info($data['provider_id'], $ptype);
				
				$protoken = $provider_token['token'];
				$pname = $this->db->select('name')->where('id',$data['provider_id'])->get('providers')->row()->name;
				$spname = $this->db->select('shop_name')->where('id',$service['shop_id'])->get('shops')->row()->shop_name;
				
				$text = $user_name['name'] . " has booked the Service '".$service['service_title']."'";
				
				/*Notifification */
				$notimsg = $user_name['name']." has booked the service '".$service['service_title']."' at ".$pname."'s shop '".$spname."' and for the amount of ".$amt.$servicess['currency_code'];
				
				$receiver='';
				$admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
				if(empty($admin['token'])){				 
					$receiver=$this->getToken(14,$admin['user_id']);
					$receiver_token_update=$this->db->where('role',1)->update('administrators',['token'=>$receiver]);
				}else{
					$receiver=$admin['token'];
				}
				
				
				$paynotimsg = $user_name['name']." paid the amount of ".$amt.$servicess['currency_code']." to ".$pname." for the service '".$service['service_title']."'";
				
				if(!empty($receiver)){
					  $this->bookingnotification($protoken,$receiver,$notimsg);
					  $this->bookingnotification($protoken,$receiver,$paynotimsg);
				}
				$this->bookingnotification($protoken,$token,$notimsg);
				$this->bookingnotification($protoken,$token,$paynotimsg);
				
				$this->send_push_notification($token, $result, $ptype, $msg = $notimsg);
				$this->send_push_notification($token, $result, $ptype, $msg = $paynotimsg);
				
				
				$inputs = array();
				if(!empty($this->post('type')) && $this->post('type') == 'cod'){
					echo json_encode(['success' => true, 'msg' => $this->bookmsg,  'title' => $this->BMsg, 'status' => 1]);
				} else {
					$response_code = '200';
                	$response_message = 'Service Booked Successfully!';					
				}
			} else { 
				$message = 'Sorry, Try again later...';
				$response_code = '200';
                $response_message = $message;	
				$s_id = $service_id; 
				$this->cancel_appointment($bookid);
				
				$inputs = array();
				if(!empty($this->post('type')) && $this->post('type') == 'cod'){
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				} else {
					echo json_encode(['success' => false]);
					//redirect(base_url('book-appointment/'.$s_id));
				}		
			}
			
		} else{			
			$message = 'Sorry, Try again later...';
			$s_id = $service_id; 
			$this->cancel_appointment($bookid);
			
			$inputs = array();			
			$data=array('error_message'=>$this->errmsg." ".$_GET['message']);
			$this->session->set_flashdata($data);
			if(!empty($this->input->get('type')) && $this->input->get('type') == 'cod'){
				echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
			} else {
				echo json_encode(['success' => false]);
				//redirect(base_url('book-appointment/'.$s_id));
			}		
		}
		$data = array();
		$result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);	
	} else {
		 $this->token_error();
	}
    }

    function cancel_appointment($bid = ''){
		if($bid != '') {
			$bookid = $bid;
		} else {
			$bookid = $this->post('book_id');
		}
		$bookid = trim($bookid); 
		
		$this->db->where(array('id' => $bookid));
        $this->db->delete('book_service');
		
		$this->db->where(array('parent_bookid' => $bookid));  
		$this->db->delete('book_service');
		
		$this->db->where(array('guest_parent_bookid' => $bookid));  
		$this->db->delete('book_service');
		
		$response_code = '200';
        $response_message = 'Booking Cancelled';
        $data = array();

        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);	
	}

	public function bookingnotification($sender, $receiver, $message) {
        $data = array(
            'sender' => $sender,
            'receiver' => $receiver,
            'message' => $message,
            'status' => 1,
            'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')),
            'created_at' => date('Y-m-d H:i:s')
        );

        $ret = $this->db->insert('notification_table', $data);
    }

    //Get date,time & timezone
	public function datetimeformat_get() {
		if(settingValue('date_format') == 'd/m/Y') {
			$date_format = 'dd/MM/yyyy';
		} elseif (settingValue('date_format') == 'd-m-Y') {
			$date_format = 'dd-MM-yyyy';
		} elseif (settingValue('date_format') == 'm/d/Y') {
			$date_format = 'MM-dd-yyyy';
		} elseif (settingValue('date_format') == 'Y/m/d') {
			$date_format = 'yyyy/MM/dd';
		} elseif (settingValue('date_format') == 'Y-m-d') {
			$date_format = 'yyyy-MM-dd';
		} elseif (settingValue('date_format') == 'M d Y') {
			$date_format = 'MMM d yyyy';
		} elseif (settingValue('date_format') == 'd M Y') {
			$date_format = 'd MMM yyyy';
		} elseif (settingValue('date_format') == 'm-Y-d') {
			$date_format = 'MM-yyyy-dd';
		} else {
			$date_format = 'yyyy-MM-dd';
		}
		$data['date_format'] = $date_format;
		$data['timezone'] = (settingValue('timezone'))?settingValue('timezone'):'Asia/Kolkata';
		$data['time_format'] = (settingValue('time_format'))?settingValue('time_format'):'12hrs';
        $response_code = 200;
        $response_message = "Fetched Successfully...";
        $result = $this->data_format($response_code, $response_message, $data);
        $this->response($result, REST_Controller::HTTP_OK);
	}

	public function add_shop_payment_post() {
		if ($this->user_id != 0 || ($this->default_token == $this->api_token)) {
			$postdata = $this->post();
			
			$this->verifyRequiredParams(['transaction_id', 'amount', 'paytype'],$postdata);

			if($postdata['transaction_id'] != '' && $postdata['amount']) {	
				$user_currency = get_provider_currency();
				$user_currency_code = ($user_currency['user_currency_code'])?$user_currency['user_currency_code']:settingValue('currency_option');
				
				$provider_data = $this->db->get_where('providers', array('id'=>$this->user_id))->row_array();
				
				if($provider_data['usertype'] == 'freelancer'){
				   $details['type']=3;  
				} else {
					$details['type']=1;  
				}
				$token=$provider_data['token'];
				
				$totamt = $postdata['amount'];
						
				$details['service_subscription_id'] = 0; 
				$details['token'] = $token;
				$details['user_provider_id'] = $this->user_id;
				$details['currency_code'] = $user_currency_code;
				$details['amount'] = $totamt;
				$details['reason'] = "Add Shop";
				$details['transaction_id'] = $postdata['transaction_id'];
				$details['created_at'] = date('Y-m-d H:i:s'); 
				$details['book_id'] = 0;
				$details['total_amount'] = $totamt;
				$details['paytype'] = $postdata['paytype'];
				$this->db->insert('moyasar_table', $details);

				$this->add_shop_payment_push_notification($token,$this->user_id,1,' - New Shop Fee Paid Successfully');

				$message = "New Shop Fee Paid Successfully";
				$response_code = '200';
				$response_message = $message;
				$data = [];
				$result = $this->data_format($response_code, $response_message, $data);

				$this->response($result, REST_Controller::HTTP_OK);
		    } else{
		    	$response_code = '500';
				$response_message="Something went wrong, Try again later!";
				$data = [];
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);
			}
			exit;      
		} else {
			echo 'sdsds'; exit;
			$this->token_error();
		}
	}

	//Paypal, Stripe 
	public function order_booking_payment_post() {
		if ($this->users_id != 0 || ($this->default_token == $this->api_token)) 
    	{ 
			$postdata = $this->post();
			$this->verifyRequiredParams(['orderid', 'transaction_id', 'status', 'address_type', 'paytype'],$postdata);

			$order = $this->api->getsingletabledata('product_order', ['id'=>$postdata['orderid']], '', 'id', 'asc', 'single');

			//update in product order
        	$upt_order = ['payment_gway'=>$postdata['paytype'], 'transaction_id'=>$postdata['transaction_id'], 'payment_status'=>$postdata['status'], 'address_type'=>$postdata['address_type']];
        	if ($gateway == 'wallet') {
	           $upt_order['payment_type'] = 'wallet';
	        } else if ($gateway == 'cod')  {
	           $upt_order['payment_type'] = 'cod';
	        } else {
	            $upt_order['payment_type'] = 'card';
	        }
	        $upt_cart['status'] = 0;

	        if ($postdata['transaction_id']!='') {
	            $upt_order['status'] = 1;
	            $upt_cart['status'] = 1;
	        }

	        $this->db->where('id', $postdata['orderid']);
	        $this->db->update('product_order', $upt_order);
	        //update cart status
	        $this->db->where('order_id', $postdata['orderid']);
	        $this->db->update('product_cart', $upt_cart);

	        //insert log
        	$ins_data = ['product_order_id'=>$postdata['order_id'], 'user_id'=>$this->user_id, 'payment_gway'=>$postdata['paytype'], 'transaction_id'=>$postdata['transaction_id'], 'payment_status'=>$postdata['status'], 'created_on'=>date("Y-m-d H:i:s")];
        	$this->db->insert('product_order_log', $ins_data);

        	//Get user data
        	$userId = $this->users_id;
	        $users_data = $this->db->get_where('users', array('id'=>$userId))->row_array();
	        
        	if ($postdata['paytype']!='cod' && $postdata['transaction_id']!='') {
		        $order = $this->api->getsingletabledata('product_order', ['id'=>$postdata['orderid']], '', 'id', 'asc', 'single');
		        //check in moyasar table
		        $moyasar = $this->products->getsingletabledata('moyasar_table', ['order_id'=>$postdata['orderid'], 'user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
		        $mdata = array('token'=>$users_data['token'], 'currency_code'=>$order['currency_code'], 'user_provider_id'=>$userId, 'type'=>2, 'amount'=>$order['total_amt'], 'order_id'=>$postdata['orderid'], 'total_amount'=>$order['total_amt'], 'transaction_id'=>$postdata['transaction_id'], 'updated_on'=>date("Y-m-d H:i:s"));

		        if (!empty($moyasar))  {
		            $this->db->where('id', $moyasar['id']);
		            $this->db->update('moyasar_table', $mdata);
		        } else {
		            $mdata['created_at'] = date("Y-m-d H:i:s");
		            $this->db->insert('moyasar_table',$mdata);
		        }

		        if($this->db->affected_rows() > 0) {
		        	$response_message = "Order Booking Successfully!";
		        	$response_code = '200';
		        } else {
		        	$response_message = "Something went wrong, Please try again1";
		        	$response_code = '500';
		        }
	        }
	        if ($postdata['transaction_id']!='') {
	            $user_token = $users_data['token'];
		        $user_name = $users_data['name'];
		        $cartlist = $this->products->product_cart_list(['product_cart.order_id'=>$postdata['orderid']]);
		        if (!empty($cartlist)) 
		        {
		            foreach ($cartlist as $val) 
		            {
		                $provider = $this->products->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
		                $message = 'You have recieved an Order of '.$val['product_name'].' from '.$user_name;
		                $not_data = array('sender'=>$user_token, 'receiver'=>$provider['token'], 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
		                $this->db->insert('notification_table', $not_data);
		            }

		            if($this->db->affected_rows() > 0) {
			        	$response_message = "Order Booking Successfully!";
			        	$response_code = '200';
			        } else {
			        	$response_message = "Something went wrong, Please try again2";
			        	$response_code = '500';
			        }
		        }
	        }
			$data = array();
			$result = $this->data_format($response_code, $response_message, $data);
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->token_error();
		}
	}

	//Add New Shop Payment Push Notification
	public function add_shop_payment_push_notification($token,$provider_id,$type,$msg=''){
		$data=array();
		$data['provider_id']=$provider_id;      
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
    /* END */

    /* Apply offer API */
    public function apply_offer_post(){
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            $user_data = $this->post();
            $this->verifyRequiredParams(['service_id', 'start_date', 'end_date', 'offer_percentage', 'start_time', 'end_time'],$user_data);

            $start_date = date("Y-m-d", strtotime($user_data['start_date']));
            $end_date = date("Y-m-d", strtotime($user_data['end_date']));
            $start_time = date("H:i:s", strtotime($user_data['start_time']));
            $end_time = date("H:i:s", strtotime($user_data['end_time']));
            $checkoffer = $this->api->checkexistoffer($user_data['service_id'], $start_date, $end_date);
            $data = [];
            if (!empty($checkoffer)) {
                $response_code = '200';
                $response_message = 'The selected dates are already exists';
            }
            else {
                $user_id = $this->user_id;            
                $ins_data = array('service_id'=>$user_data['service_id'], 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_time'=>$start_time, 'end_time'=>$end_time,'offer_percentage'=>$user_data['offer_percentage'], 'created_at'=>date("Y-m-d H:i:s"));
                $ins_data['provider_id'] = $user_id;
                $ins_data['status'] = 0;
                $ins_data['df'] = 0;
                
                $id = $this->api->insertserviceoffer($ins_data);
                if($id){
                $response_code = '200';
                $response_message = 'Offers added successfully';
                }else{
                    $response_code = '200';
                    $response_message = 'Offers Not added successfully';
                }
            }

            $result = $this->data_format($response_code, $response_message, $data);

            $this->response($result, REST_Controller::HTTP_OK);
        }else{
		 $this->token_error();
		}
        
    
    }
    /* end */

    /*Provider Home */
    public function provider_home_get(){
        if ($this->user_id != 0 || ($this->default_token == $this->api_token)) 
    	{
            $data['booking_count'] = $this->api->booking_count($this->user_id);
            $data['services_count'] = $this->api->services_count($this->user_id);
            $data['my_subscribe'] = $my_subscribe = $this->api->get_my_subscription($this->user_id,'provider');
            $data['notification_count'] = $this->api->notification_count($this->api_token);
            if(!empty($my_subscribe)){
                $subscription_fee=$this->db->where('id',$my_subscribe['subscription_id'])->get('subscription_fee')->row_array();
                $data['my_subscribe']['subscription_name'] = $subscription_fee['subscription_name'];
            }else{
                $data['my_subscribe']['subscription_name']='';
            }
            $response_code = '200';
            $response_message = 'Data Fetched successfully';
            $result = $this->data_format($response_code, $response_message, $data);
            $this->response($result, REST_Controller::HTTP_OK);
        }else{
            $this->token_error();
           }
    }
    /*end*/

}



?>