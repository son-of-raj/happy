<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Payment extends REST_Controller {

    public function __construct() {

        parent::__construct();
        //error_reporting(0);
        $this->load->helper('push_notifications');
        $this->load->helper('user_timezone');
        $this->load->model('api_model', 'api');
        $this->load->model('admin_model', 'admin');  
		$this->load->model('templates_model');
		$this->load->model('subscription_model','subscription');
        $this->load->model('user_login_model', 'user_login');

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
		$query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'website_name') {
                    $this->website_name = $data['value'];
                }
			}
		}
		
		$this->data['theme'] = 'user';        
        

        $publishable_key = '';
        $secret_key = '';
        $live_publishable_key = '';
        $live_secret_key = '';
        

        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();


		/* Moyaser Payment and Shop Fee*/
		$moyaser_option=settingValue('moyaser_option');
		if($moyaser_option == 1){		
			$this->moyaser_apikey=settingValue('moyaser_apikey');
			$this->moyaser_secret_key=settingValue('moyaser_secret_key');
		}else if($moyaser_option == 2){
			$this->moyaser_apikey=settingValue('live_moyaser_apikey');
			$this->moyaser_secret_key=settingValue('live_moyaser_secret_key');
		}
		$this->shop_fee = settingValue('shop_fee');
		$this->langval  = $lang;
      
    }
	
	public function payments_get(){   
		$amtval = $_GET['amount'];
		$curval = $_GET['currency'];
		$actval = $_GET['action'];
		$loginid = $_GET['loginid'];
		
		//Subscription
		$sub_id = 0;
		if(isset($_GET['subscription_id'])&&!empty($_GET['subscription_id'])){
			$sub_id = $_GET['subscription_id'];
		}
		
		//Booking		
		$bookid = 0;
		if(isset($_GET['bookid'])&&!empty($_GET['bookid'])){
			$bookid = $_GET['bookid'];
		}
		$couponid = 0;
		if(isset($_GET['coupon_id'])&&!empty($_GET['coupon_id'])){
			$couponid = $_GET['coupon_id'];
		}
		$cod = 0;
		if(isset($_GET['cod'])&&!empty($_GET['cod'])){
			$cod = $_GET['cod'];
		}
		
		//product
		$order_id = 0;
		if(isset($_GET['order_id'])&&!empty($_GET['order_id'])){
			$order_id = $_GET['order_id'];
		}
		
		//echo "<pre>"; print_r($_GET); exit();
		//
		/*if ($this->user_id != 0 || $this->users_id != 0 || ($this->default_token == $this->api_token)) { 	*/		
			$amount = $amtval;
			$c_code = $curval;
			if($amount > 0 && $c_code != '' && $actval > 0) {
				if($actval == 1) {
					$checksubscription = $this->api->getsingletabledata('subscription_fee', ['id'=>$sub_id, 'status'=>1], '', 'id', 'asc', 'single');					
					if (!empty($checksubscription)) {						
						$subscription_id = $sub_id;
					} else {
						$subscription_id = 0;
						redirect('api/payment/invalidmsg');
					}
					
				} else {
					$subscription_id = 0;
				}
				if($actval == 3) {
					$checkbook = $this->db->where_not_in('status', [2,5,6,7])->where('id', $bookid)->get('book_service')->row_array();					
					if (!empty($checkbook)) {	
						$bookid = $bookid;
					} else {
						$bookid = 0;
						redirect('api/payment/invalidmsg');
					}
				} else {
					$bookid = 0;
				}
				//check order
				if ($actval == 4) {
					//
					$order = $this->api->getsingletabledata('product_order', ['id'=>$order_id, 'user_id'=>$loginid, 'status'=>0], '', 'id', 'asc', 'single');
					if (!empty($order)) 
					{
						$amount = $order['total_amt'];
						$c_code = $order['currency_code'];
						$order_id = $order_id;
					}
					else
					{
						/*$response_code = '500';
						$response_message = 'Invalid Order ID.';
						$data = new stdClass();
						$result = $this->data_format($response_code, $response_message, $data);
						$this->response($result, REST_Controller::HTTP_OK);*/
						$order_id = 0;
						redirect('api/payment/invalidmsg');
					}
				}
				else
				{
					$order_id = 0;
				}
				//print_r($_GET);
				$data['data'] = array(    
					'loginid'                  =>  $loginid,  
					'action'                   =>  $actval,            
					'publishable_api_key'      =>  $this->moyaser_apikey,
					'language'      		   =>  $this->langval,
					'amount'                   =>  ($amount * 100),
					'currency_code'			   =>  $c_code,
					'subscription_id'		   =>  $subscription_id,
					'book_id'		  		   =>  $bookid,
					'couponid'		  		   =>  $couponid,
					'cod'					   =>  $cod,
					'order_id'		  		   =>  $order_id

				);		
				//print_r($data);
				$viewfile = $this->data['theme'] . '/payments';		       
				$this->load->view($viewfile,$data);
			} else {
				/*$response_code = '500';
				$response_message = 'Invalid Inputs.';
				$data = new stdClass();
				$result = $this->data_format($response_code, $response_message, $data);
				$this->response($result, REST_Controller::HTTP_OK);*/
				redirect('api/payment/invalidmsg');
			} 
		/*} else {
			$this->token_error();
		}*/
    }
	
	public function subscription_payment_get(){ 
		
		$id = $this->uri->segment(4); // Provider ID
		$sub_id = $this->uri->segment(5); // Package ID
		
		//print_r($_GET);	echo $_GET['id']; exit;	
	
		$inputs = array();
	
		if($_GET['status'] == 'paid') {		
			$token=$this->db->select('token')->get_where('providers', array('id' => $id))->row()->token;
			$inputs['subscription_id'] = $sub_id;
			$inputs['user_id'] = $id;
			$inputs['token']=$token;
			$inputs['payment_details']='Moyasarpay'; 
			//print_r($inputs);//exit;
			$result = $this->subscription->moyasar_subscription_success($inputs);
			
			if($result){
				$provider_currency = get_api_provider_currency($id);
				$ProviderCurrency = $provider_currency['user_currency_code'];
				
				$type = $this->db->select('type')->where('id',$id)->get('providers')->row()->type;
				$details['type']=$type;  
				
				$feedet = $this->db->select('subscription_name, fee,currency_code')->where('id',$sub_id)->get('subscription_fee')->row_array();
				$subscribedamount = get_gigs_currency($feedet['fee'], $feedet['currency_code'], $ProviderCurrency);
				
				$totamt = $_GET['amount']/100;
				
				$bookid = $this->db->select('id')->where('subscriber_id',$id)->order_by('id', 'desc')->get('subscription_details')->row()->id;
				
				$details['service_subscription_id'] = $sub_id; 
				$details['token'] = $token;
				$details['user_provider_id'] = $id;
				$details['currency_code'] = $ProviderCurrency;
				$details['amount'] = $subscribedamount;
				$details['reason'] = "Add Subscription";
				$details['transaction_id'] = $_GET['id'];
				$details['created_at'] = date('Y-m-d H:i:s'); 
				$details['book_id'] = $bookid;
				$details['total_amount'] = $totamt;
				$this->db->insert('moyasar_table', $details); 
				
				//echo $this->db->last_query();
				//print_r($details); //exit;
								
				$receiver='';
				$admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
				if(empty($admin['token'])){				 
					$receiver=$this->getToken(14,$admin['user_id']);
					$receiver_token_update=$this->db->where('role',1)->update('administrators',['token'=>$receiver]);
				}else{
					$receiver=$admin['token'];
				}	
				
				$datas = $this->db->select('name,token')->where('id',$id)->get('providers')->row_array();
				$msg1 = $datas['name']." have been subscribed the subscription - ".$feedet['subscription_name'];
				$msg2 = "You have been subscribed the subscription - ".$feedet['subscription_name'];
				
				if(!empty($receiver)){
					$this->bookingnotification($datas['token'],$receiver,$msg1);
				}
				
				redirect('api/payment/success');
			}else{
				redirect('api/payment/failed');			
			} 
	    }else{
		    redirect('api/payment/failed');			
		}   
	}
	
	public function newshop_payment_get(){ 
		//print_r($_GET);	echo $_GET['id']; 
		//exit;	
		
		$id = $this->uri->segment(4); // Provider ID
		
		if($_GET['status'] == 'paid') {	
			$provider_currency = get_api_provider_currency($id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
			
			$type = $this->db->select('type')->where('id',$id)->get('providers')->row()->type;
			$details['type']=$type;  
			
			$totamt = $_GET['amount']/100;
			$token=$this->db->select('token')->get_where('providers', array('id' => $id))->row()->token;
			$details['service_subscription_id'] = 0; 
			$details['token'] = $token;
			$details['user_provider_id'] = $id;
			$details['currency_code'] = $ProviderCurrency;
			$details['amount'] = $totamt;
			$details['reason'] = "Add Shop";
			$details['transaction_id'] = $_GET['id'];
			$details['created_at'] = date('Y-m-d H:i:s'); 
			$details['book_id'] = 0;
			$details['total_amount'] = $totamt;
			$this->db->insert('moyasar_table', $details); 
			
			//echo $this->db->last_query();
			//print_r($details); exit;
			
			$receiver='';
			$admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
			if(empty($admin['token'])){				 
				$receiver=$this->getToken(14,$admin['user_id']);
				$receiver_token_update=$this->db->where('role',1)->update('administrators',['token'=>$receiver]);
			}else{
				$receiver=$admin['token'];
			}	
			
			$datas = $this->db->select('name,token')->where('id',$id)->get('providers')->row_array();
			$msg1 = "New Shop Fee Paid Successfully by ".$datas['name'];
			$msg2 = "You have Paid New Shop Fee Successfully";
			
			if(!empty($receiver)){
				$this->bookingnotification($datas['token'],$receiver,$msg1);
			}
			
			redirect('api/payment/success');
			
	    } else{
			redirect('api/payment/failed');			
		}      		
		
	}
	
	
	public function booking_payment_get(){ 
		
		$user_id = $this->uri->segment(4); // Provider ID
		$bookid = $this->uri->segment(5); // Package ID
		$couponid = $this->uri->segment(6); // Coupon ID
		//$cod = 2;
		$cod = $this->uri->segment(7); // Coupon ID
		
		$paid_token = ''; $amt = 0;
		if((isset($_GET['id']))  && $_GET['id'] != ''){
			$amt = $_GET['amount'] /100 ;
			$paid_token = $_GET['id'];
		}
		if($cod == 1){
			$val = $this->uri->segment(8);
			$amt = $val /100 ;
		}
				
		//print_r($_GET);	echo $_GET['id']; //exit;	
	
		$inputs = array();
		
		$servicess = $this->db->select('*')->where('id',$bookid)->get('book_service')->row_array();
		
		$service_id = $servicess['service_id']; 
		
		$inputs['cod'] = $cod; // Online Payment
		
		
		//print_r($_POST);exit;	
		
		if((isset($_GET['status']) && $_GET['status'] == 'paid') || $inputs['cod'] == 1) {
			
			$result = $bookid;			
			if ($result != '') {
			
				$inputs['status'] = 2;
				$inputs['reason'] = '';
				$inputs['final_amount'] = $amt;
				$inputs['paid_tokenid'] = $paid_token;
				//echo "<pre>"; print_r($inputs); exit;
				$this->db->update('book_service', $inputs, array("id" => $result));
				
				$cinputs['couponid'] = $couponid;
				$this->db->update('book_service', $cinputs, array("id" => $result));
					
				$ginputs['status'] = 2;
				$this->db->update('book_service', $ginputs, array("guest_parent_bookid" => $result));
				
				$this->load->library('ciqrcode');
				$qr_image=rand().'.png';
				$params['data'] = $result;
				$params['level'] = 'H';
				$params['size'] = 4;
				$params['savename'] =FCPATH."uploads/qr_image/".$qr_image;
				$qr_message = '';
				if($this->ciqrcode->generate($params)) {
					$qr_img_url=$qr_image; 
					$qr_details['qr_img_url'] = "uploads/qr_image/".$qr_img_url;
					
					if($this->db->update('book_service', $qr_details, array("id" => $result))){
						$qrpaths = base_url().'uploads/qr_image/'.$qr_img_url; //echo $qrpaths;
						$qr_message .= '<div style="float:left; width:100%;">';
						$qr_message .= '<p style="font-weight: bold;color: coral;">QR CODE FOR YOUR APPOINTMENT</p>';
						$qr_message .= '<img src="'.$qrpaths.'" alt="QR CODE" />';
						$qr_message .= '</div>';
					}
				}
				
				
				$service=$this->db->where('id',$service_id)->from('services')->get()->row_array();
				$this->data['service'] = $service;
				
				if($servicess['autoschedule_session_no']  == 1){	
					$inputs['total_amount'] = 0; $inputs['final_amount'] = 0;
					$this->db->update('book_service', $inputs, array("parent_bookid" => $result));					
				}
				
				/// Coupon Count Update
				if(!empty($couponid) && $couponid > 0){
					$couponqry = $this->db->select('user_limit, user_limit_count')->where('id',$couponid)->get('service_coupons')->row_array();	
					$cno = intval($couponqry['user_limit_count']) + 1;
					$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '". $cno ."' WHERE `id` = '".$couponid."'");
					if($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno){
						$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '".$couponid."'");
					}
				}
				
				// Reward Update
				$rewardid = $servicess['rewardid'];
				if(!empty($rewardid) && $rewardid > 0){
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '".$rewardid."' and user_id = ".$user_id." and service_id = ".$service_id);
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
						'user' => $user_id,
						'currency_code' => $servicess['currency_code'],
						'amount' => $service['service_amount'],
						'commission' => $pertage,
						'vat' => $vatper,
						'offersid' => $servicess['offersid'],
						'couponid' => $servicess['couponid'],
						'rewardid' => $servicess['rewardid'],
						'revenue_for' => 'Service Booking'
					];
					//print_r($revenueInsert);
					$revInsert = $this->db->insert('revenue', $revenueInsert);
				}
								
				// Mail Content
				$provider_data = $this->db->where('id',$servicess['provider_id'])->from('providers')->get()->row_array();
				$user_data = $this->db->where('id',$user_id)->from('users')->get()->row_array();
				$this->data['provider'] = $provider_data;
				
				$preview_link = base_url();
				$time = $servicess['from_time']."-".$servicess['to_time'];
				
						
				$bodyid = 3;
				$tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
				$providerbody = $tempbody_details['template_content'];
				$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
				$providerbody = str_replace('{sitetitle}',$this->website_name, $providerbody);
				$providerbody = str_replace('{user_person}',$user_data['name'], $providerbody);
				$providerbody = str_replace('{service_title}',$service['service_title'], $providerbody);
				$providerbody = str_replace('{service_date}',$servicess['service_date'], $providerbody);
				$providerbody = str_replace('{service_time}',$time, $providerbody);
				$providerbody = str_replace('{location_user}',$servicess['location'], $providerbody);
				$providerbody = str_replace('{preview_link}',$preview_link, $providerbody);
				$providerbody .= $qr_message;
				//echo $providerbody;
				
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
				
				$token=$this->db->select('token')->get_where('users', array('id' => $user_id))->row()->token;
				
				/* moyasar payment history entry */
				if($inputs['cod'] == 2){ // not cod					
					/*$totamt = $_GET['amount'] / 100;
					$details['transaction_id'] = $_GET['id'];*/
					
					$totamt = $amt;
					$details['transaction_id'] = $paid_token;
					$details['service_subscription_id'] = $service_id; 
					$details['token'] = $token;
					$details['user_provider_id'] = $user_id;
					$details['currency_code'] = $servicess['currency_code'];
					$details['amount'] = $servicess['amount'];
					$details['reason'] = "Book Service";
					
					$details['created_at'] = date('Y-m-d H:i:s'); 
					$details['book_id'] = $result;
					$details['total_amount'] = $totamt;
					$details['type']=$provider_data['type'];  
					//print_r($details);
					$this->db->insert('moyasar_table', $details); 
				}
				
				// Cash On Delivery
				if($inputs['cod'] == 1){	
					$codData['book_id'] = $result;
					$codData['user_id'] = $user_id;	
					$codData['provider_id'] = $servicess['provider_id'];	
					$codData['amount'] = $servicess['amount'];	
					$codData['amount_to_pay'] = $amt;	
					$codData['created_on'] = date('Y-m-d');	
					//print_r($codData);
					$this->db->insert('book_service_cod', $codData);	
				}
				
				/* Notification entry */
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
				if($inputs['cod'] == 1){
					$paynotimsg = $user_name['name']." will pay the amount of ".$amt.$servicess['currency_code']." to ".$pname." for the service '".$service['service_title']."' at the time of service taken";
				} else {
					$paynotimsg = $user_name['name']." paid the amount of ".$amt.$servicess['currency_code']." to ".$pname." for the service '".$service['service_title']."'";
				}
				
				if(!empty($receiver)){
					$this->bookingnotification($protoken,$receiver,$notimsg);
					$this->bookingnotification($protoken,$receiver,$paynotimsg);
				}
				$this->bookingnotification($protoken,$token,$notimsg);
				$this->bookingnotification($protoken,$token,$paynotimsg);
				
				$this->bookingnotification($token,$protoken,$notimsg);
				$this->bookingnotification($token,$protoken,$paynotimsg);
				
								
				/* Notification */
				if($inputs['cod'] == 1){
					redirect('api/payment/codsuccess');
				} else {
					redirect('api/payment/success');
				}
				
				//exit;
			} else {
				redirect('api/payment/failed');
			}
			
		} else {
			redirect('api/payment/failed');			
		}

	}
	//Order Redirect
	public function order_payment_get()
	{
		$order_id = $this->uri->segment(4); // Order ID
		if ($_GET['status'] == 'paid') 
        {
            $transaction_id = $_GET['id'];
            //order details
	        $order = $this->api->getsingletabledata('product_order', ['id'=>$order_id, 'status'=>0], '', 'id', 'asc', 'single');
		    $this->order_success($order['user_id'], $order_id, 'moyasar', $transaction_id, $_GET['message']);
			redirect('api/payment/success');
        }
        else
        {
            redirect('api/payment/failed');
        }
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
            //$this->order_submit($userId, $order_id); //splitting to respective providers
            $this->order_moyasar($userId, $order_id, $transaction_id);
        }
        if ($transaction_id!='') {
            $this->order_success_notification($userId, $order_id);
        }
        return "success";
    }
    public function order_moyasar($userId, $order_id, $transaction_id)
    {
        //user
		$user = $this->api->getsingletabledata('users', ['id'=>$userId], '', 'id', 'asc', 'single');
        $order = $this->api->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        //check in moyasar table
        $moyasar = $this->api->getsingletabledata('moyasar_table', ['order_id'=>$order_id, 'user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
        $mdata = array('token'=>$user['token'], 'currency_code'=>$order['currency_code'], 'user_provider_id'=>$userId, 'type'=>2, 'amount'=>$order['total_amt'], 'order_id'=>$order_id, 'total_amount'=>$order['total_amt'], 'transaction_id'=>$transaction_id, 'updated_on'=>date("Y-m-d H:i:s"));
        if (!empty($moyasar)) 
        {
            $this->db->where('id', $moyasar['id']);
            $this->db->update('moyasar_table', $mdata);
        }
        else
        {
            $mdata['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert('moyasar_table',$mdata);
        }
        return 'success';
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
    public function export_invoice_get()
    {
    	$inp = $this->input->get();
    	$this->verifyRequiredParams(['booking_id'],$inp);
    	$this->load->model('User_booking','userbooking');
    	require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('products_model');
        $where = array('status'=>6, 'id'=>$inp['booking_id']);
        $data['booking'] = $this->products_model->getsingletabledata('book_service', $where, '', 'id', 'asc', 'single');
        
		if (!empty($data['booking'])) 
        {
        	//Service Details
	        $data['service'] = $this->userbooking->service_details(['services.id'=>$data['booking']['service_id']]); 
	        //user details
	        $data['user'] = $this->userbooking->user_details(['users.id'=>$data['booking']['user_id']]);
	        //provider details
	        $data['provider'] = $this->userbooking->provider_details(['providers.id'=>$data['booking']['provider_id']]);
	        //echo "<pre>"; print_r($data['provider']); exit();
	        $mpdf = new \Mpdf\Mpdf();
	        $mpdf->autoScriptToLang = true;
	        $mpdf->autoLangToFont = true;
	        $html = $this->load->view('user/home/invoice', $data, true);
	        $mpdf->writeHTML($html);
	        $mpdf->Output('invoice.pdf', 'D');
        }
        else
        {
        	redirect('api/payment/invalidmsg');
        }
    }
    public function export_muliple_invoice_get()
    {
    	$inp = $this->input->get();
    	$this->verifyneedparameters(['type', 'loginid', 'from_date', 'to_date'],$inp);
		
    	//echo "<pre>"; print_r($inp); exit();
    	$this->load->model('User_booking','userbooking');
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');
        $this->load->model('products_model');
        $search = array();
        if ($inp['type'] == 'user') {
            $where = array('book_service.status'=>6, 'book_service.user_id'=>$inp['loginid']);
        }
        else
        {
            $where = array('book_service.status'=>6, 'book_service.provider_id'=>$inp['loginid']);
        }
        if ($inp['from_date']!='') 
        {
            $where['book_service.service_date >='] = date("Y-m-d", strtotime($inp['from_date']));
        }
        if ($inp['to_date']!='') 
        {
            $where['book_service.service_date <='] = date("Y-m-d", strtotime($inp['to_date']));
        }
        $booking = $this->userbooking->user_invoices('', '', $where, $search, 'list');
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        if (!empty($booking)) 
        {
            foreach ($booking as $val) 
            {
                
                $data['booking'] = $val;
                //Service Details
                $data['service'] = $this->userbooking->service_details(['services.id'=>$val['service_id']]); 
                //user details
                $data['user'] = $this->userbooking->user_details(['users.id'=>$val['user_id']]);
                //provider details
                $data['provider'] = $this->userbooking->provider_details(['providers.id'=>$val['provider_id']]);
                //echo "<pre>"; print_r($data['provider']); exit();
                $mpdf->AddPage();
                $html = $this->load->view('user/home/invoice', $data, true);
                $mpdf->writeHTML($html);
                
            }
            $mpdf->Output();
        }
        else
        {
        	redirect('api/payment/invalidmsg');
        }
    }
    //
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
	public function token_error() {
        $response_code = '498';
        $response_message = "Invalid token or token missing";
        $data = [];
        $result = $this->data_format($response_code, $response_message, $data);

        $this->response($result, REST_Controller::HTTP_OK);
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
	
	public function success_get(){
		echo "Payment Successful!!!";
	}
	public function failed_get(){
		echo "Sorry... Try Again Later.";
	}
	public function invalidmsg_get(){		
		echo "Invalid ID. Sorry... Try Again Later.";
	}
	public function codsuccess_get(){
		echo "Booking Confirmed. Pay at the time of service";
	}
}
?>