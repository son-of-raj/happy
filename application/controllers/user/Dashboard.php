<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public $data;

  public function __construct() {

    parent::__construct();
    error_reporting(0);
    if(empty($this->session->userdata('id'))){
      redirect(base_url());
    }
    $this->data['theme']     = 'user';
    $this->data['module']    = 'home';
    $this->data['page']     = '';
    $this->data['base_url'] = base_url();
    $this->load->helper('user_timezone_helper');
    $this->load->model('service_model','service');
    $this->load->model('home_model','home');
    $this->load->model('Api_model','api');
	 $this->load->model('Stripe_model');

          // Load pagination library 
		   $this->load->library('paypal_lib');
    $this->load->library('ajax_pagination'); 
	$this->load->helper('form');
        $this->data['csrf'] = array(
	    'name' => $this->security->get_csrf_token_name(),
	    'hash' => $this->security->get_csrf_hash()
	    );

        // Load post model 
    $this->load->model('booking'); 
    $this->load->model('User_booking','userbooking'); 

        // Per page limit 
    $this->perPage = 6; 
    
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
		
		$this->successMsg = (!empty($this->user_language[$this->user_selected]['lg_Availability_Msg'])) ? $this->user_language[$this->user_selected]['lg_Availability_Msg'] : $this->default_language['en']['lg_Availability_Msg'];
		
			
  }

  
  public function index()
  {
   $this->data['page'] = 'index';
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');
 }


 public function user_settings()
 {
   $this->data['page'] = 'user_settings';
   $this->data['country']=$this->db->select('id,country_name')->from('country_table')->get()->result_array();
   $this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
   $this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
   $this->data['user_address']=$this->db->where('user_id',$this->session->userdata('id'))->get('user_address')->row_array();
   $this->data['profile']=$this->service->get_profile($this->session->userdata('id'));
   $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
   $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 
  public function userchangepassword()
 {
   $this->data['page'] = 'user_change_password';
   $this->data['profile']=$this->service->get_profile($this->session->userdata('id'));
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 
	public function prochangepassword()
 {
   $this->data['page'] = 'provider_change_password';
   $user_id       = $this->session->userdata('id');
   $this->data['profile']=$this->db->where('id', $user_id)->get('providers')->row_array();
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 
 
public function checkuserpwd()
 {
	 
		$user_id       = $this->session->userdata('id');
		$user          = $this->db->where('id', $user_id)->where('password', md5($this->input->post('current_password')))->get('users')->row_array();
		if(!empty($user))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
 }
 
 public function checkproviderpwd()
 {
	 
		$user_id       = $this->session->userdata('id');
		$user          = $this->db->where('id', $user_id)->where('password', md5($this->input->post('current_password')))->get('providers')->row_array();
		if(!empty($user))
		{

			echo 1;
		}
		else
		{
			echo 0;
		}
 }
 
 public function update_user_password() {
        if ($this->input->post()) {
            removeTag($this->input->post());
            $user_id = $this->session->userdata('id');
            $confirm_password = $this->input->post('confirm_password');
			$table_data=array("password"=>md5($confirm_password));
			$this->db->where('id',$user_id);                
if($this->db->update('users', $table_data))
{
	$this->session->set_flashdata('success_message','Password changed successfully');    
    redirect(base_url()."change-password");   
 
    
}
else
{
	$this->session->set_flashdata('error_message','Something wrong, Please try again');
    redirect(base_url()."change-password");   
}

            
        }
    }
	
	
	 
 public function update_provider_password() {
        if ($this->input->post()) {
            removeTag($this->input->post());
            $user_id = $this->session->userdata('id');
            $confirm_password = $this->input->post('confirm_password');
			$table_data=array("password"=>md5($confirm_password));
			$this->db->where('id',$user_id);                
if($this->db->update('providers', $table_data))
{
	$this->session->set_flashdata('success_message','Password changed successfully');    
    redirect(base_url()."provider-change-password");   
 
    
}
else
{
	$this->session->set_flashdata('error_message','Something wrong, Please try again');
    redirect(base_url()."provider-change-password");   
}

            
        }
    }
	
	
 
 
 public function user_wallet()
 {
   $this->data['page'] = 'user_wallet';
  $user_id=$this->session->userdata('id');
   $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
   $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
   $this->data['user_details']=$user=$this->db->where('users.id',$user_id)->join('user_address','user_address.user_id=users.id','left')->get('users')->row_array();
   $this->data['paypal_gateway']=settingValue('paypal_gateway');
   $this->data['braintree_key']=settingValue('braintree_key');
   $razor_option=settingValue('razor_option');

   $stripe_option_status = $this->db->where('id',settingValue('stripe_option'))->get('payment_gateways')->row()->status;
   $this->data['stripe_option_status']=($stripe_option_status)?$stripe_option_status:0;
   $paypal_option_status = $this->db->where('id',settingValue('paypal_option'))->get('paypal_payment_gateways')->row()->status;
   $this->data['paypal_option_status']=($paypal_option_status)?$paypal_option_status:0;
   $razor_option_status = $this->db->where('id',settingValue('razor_option'))->get('razorpay_gateway')->row()->status;
   $this->data['razor_option_status']=($razor_option_status)?$razor_option_status:0;
   
   
   	if($razor_option == 1){
		
		 $this->data['razorpay_apikey']=settingValue('razorpay_apikey');
   $this->data['razorpay_apisecret']=settingValue('razorpay_apisecret');
	}else if($razor_option == 2){
		 $this->data['razorpay_apikey']=settingValue('live_razorpay_apikey');
   $this->data['razorpay_apisecret']=settingValue('live_razorpay_secret_key');
	}
   
  
   
    if(!empty($user['state_id'])){
      $this->data['states']=$this->db->where('id',$user['state_id'])->get('state')->row()->name;
    }
    if(!empty($user['state_id'])){
      $this->data['state']=$this->db->where('id',$user['state_id'])->get('state')->row()->name;
    }
    if(!empty($user['country_id'])){
      $this->data['country']=$this->db->where('id',$user['country_id'])->get('country_table')->row()->country_code;
    }
    if(!empty($user['city_id'])){
      $this->data['city']=$this->db->where('id',$user['city_id'])->get('city')->row()->name;
    }
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 
 
  public function paytab_payment()
    {
        removeTag($this->input->get());
        $params = $this->input->get();
        if (!empty($params)) {
            $amount        = $params['wallet_amt'];
            $user_id       = $this->session->userdata('id');
            $user          = $this->db->where('id', $user_id)->get('users')->row_array();
            $user_name     = $user['name'];
            $user_token    = $user['token'];
            $currency_type = $user['currency_code'];
			$this->paytabs_payments($amount, $user_id, $user_name, $currency_type, $user_token);
          
        }
    }
	
	public function paytabs_payments($amount, $user_id, $g_name, $currency_type, $user_token)
    {
		
		$paytab_option = settingValue('paytab_option');
			if($paytab_option == 1){
		
		 $paytabemail=settingValue('sandbox_email');
   $paytabsecret=settingValue('sandbox_secretkey');
	}else if($paytab_option == 2){
		 $paytabemail=settingValue('email');
   $paytabsecret=settingValue('secretkey');
	}
	
   
        $ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $USERID      = $this->session->userdata('id');
        $userdetails = $this->db->query('select m.email,m.name,m.mobileno,a.address,a.country_id,a.state_id,a.city_id,a.pincode,ci.name as city ,c.country_code,c.country_name,s.name as state_name from users as m 
     LEFT JOIN user_address as a on a.user_id=m.id
	 LEFT JOIN city as ci on ci.id=a.city_id
     LEFT JOIN country_table as c on c.id=a.country_id
     LEFT JOIN state as s on s.id=a.state_id
     WHERE m.id=' . $USERID . '')->row_array();
	 
	
        $details     = array(
            "merchant_email" => $paytabemail,
            "secret_key" => $paytabsecret,
            "site_url" => base_url($this->data['theme']),
            "return_url" => base_url($this->data['theme'] . '/dashboard/paytabs_success/'),
            "title" => $g_name,
            "cc_first_name" => $userdetails['name'],
            "cc_last_name" => "Not Mentioned",
            "cc_phone_number" => !empty($userdetails['mobileno']) ? $userdetails['mobileno'] : '0000',
            "phone_number" => !empty($userdetails['mobileno']) ? $userdetails['mobileno'] : '0000',
            "email" => $userdetails['email'],
            "products_per_title" => $g_name,
            "unit_price" => $amount,
            "quantity" => "1",
            "other_charges" => "0",
            "amount" => $amount,
            "discount" => "0",
            "currency" => $currency_type,
            "reference_no" => $USERID,
            "ip_customer" => $ip,
            "ip_merchant" => $ip,
            "csrf_token_name" => $this->security->get_csrf_hash(),
            "billing_address" => !empty($userdetails['address']) ? $userdetails['address'] : 'Not Mentioned',
            "city" => !empty($userdetails['city']) ? $userdetails['city'] : 'Not Mentioned',
            "state" => !empty($userdetails['state_name']) ? $userdetails['state_name'] : 'Not Mentioned',
            "postal_code" => !empty($userdetails['pincode']) ? $userdetails['pincode'] : 'Not Mentioned',
            "country" => !empty($userdetails['country_code']) ? $userdetails['country_code'] : 'IND',
            "shipping_first_name" => $userdetails['name'],
            "shipping_last_name" => "Not Mentioned",
            "address_shipping" => !empty($userdetails['address']) ? $userdetails['address'] : 'Not Mentioned',
            "state_shipping" => !empty($userdetails['state_name']) ? $userdetails['state_name'] : 'Not Mentioned',
            "city_shipping" => !empty($userdetails['city']) ? $userdetails['city'] : 'Not Mentioned',
            "postal_code_shipping" => !empty($userdetails['pincode']) ? $userdetails['pincode'] : 'Not Mentioned',
            "country_shipping" => !empty($userdetails['country_code']) ? $userdetails['country_code'] : 'IND',
            "msg_lang" => "English",
            "cms_with_version" => "CodeIgniter 3.1.9"
        );
		
        $ch          = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.paytabs.com/apiv2/create_pay_page");
        curl_setopt($ch, CURLOPT_POST, 1);
        // In real life you should use something like:
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($details));
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info     = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        $pay_tabs_response = json_decode($response);
        if (!empty($pay_tabs_response->payment_url)) {
            redirect(urldecode($pay_tabs_response->payment_url));
        } else {
            $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
            $this->session->set_flashdata('msg_error', $message);
            redirect('user-wallet');
        }
    }
    public function paytabs_success()
    {
		
		
		$paytab_option = settingValue('paytab_option');
			if($paytab_option == 1){
		
		 $paytabemail=settingValue('sandbox_email');
   $paytabsecret=settingValue('sandbox_secretkey');
	}else if($paytab_option == 2){
		 $paytabemail=settingValue('email');
   $paytabsecret=settingValue('secretkey');
	}
        $paytabInfo = $this->input->post();
        if (!empty($paytabInfo)) {
            $details = array(
                "merchant_email" => $paytabemail,
                "secret_key" => $paytabsecret,
                "payment_reference" => $paytabInfo['payment_reference']
            );
			
            $ch      = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.paytabs.com/apiv2/verify_payment");
            curl_setopt($ch, CURLOPT_POST, 1);
            // In real life you should use something like:
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($details));
            // Receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $info     = curl_getinfo($ch);
            curl_close($ch);
            $pay_tabs_response = json_decode($response);
			
            if ($pay_tabs_response->response_code == '100') {
                if (!empty($pay_tabs_response->reference_no)) {
                    $user    = $this->Stripe_model->get_user_info($pay_tabs_response->reference_no);
                    $user_id = $pay_tabs_response->reference_no;
                    $txn_id  = $pay_tabs_response->transaction_id;
                    $amt     = $pay_tabs_response->amount;
                    $result  = $this->Stripe_model->user_wallet_history_flow($pay_tabs_response->reference_no, $txn_id, $amt);
                    if ($result == true) {
                        $message = (!empty($this->user_language[$this->user_selected]['lg_wallet_amount_add_wallet'])) ? $this->user_language[$this->user_selected]['lg_wallet_amount_add_wallet'] : $this->default_language['en']['lg_wallet_amount_add_wallet'];
                        $this->session->set_flashdata('msg_success', $message);
                        redirect('user-wallet');
                    } else {
                        $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
                        $this->session->set_flashdata('msg_error', $message);
                        redirect('user-wallet');
                    }
                }
            } else {
                $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
                $this->session->set_flashdata('msg_error', $message);
                redirect('user-wallet');
            }
        }
    }
 
 
 public function user_payment()
 { 
   $this->data['page'] = 'user_payment';

   $this->data['services'] = $this->db->where('b.user_id',$this->session->userdata('id'))->where_in('b.status',[5,6,7])->from('book_service as b')->join('users as u','u.id=b.user_id')->join('services s','s.id=b.service_id')->select('b.*,b.currency_code as currency_code1,u.*,s.service_title,s.service_image,b.status as booking_status')->order_by('b.id','desc')->get()->result_array();

   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 public function user_accountdetails()
 {
   $this->data['page'] = 'user_accountdetails';

   $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
   $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
   $this->load->vars($this->data);
   $this->load->view($this->data['theme'].'/template');  
 }
 /*cropping*/
 function prf_crop($av_src,$av_data,$av_file,$req_height,$req_width,$table_name,$redirect) {  


  $directoryName ='uploads/profile_img/';
          //Check if the directory already exists.
  if(!is_dir($directoryName)){
          //Directory does not exist, so lets create it.
    mkdir($directoryName, 0755);
  }

  if(!empty($av_src) && !empty($av_data) && !empty($av_file) )
  {
    $av_src          = $av_src;
    $av_data         = $av_data;
    $av_file         = $av_file;   
    $av_file['name']=str_replace(' ','-',$av_file['name']);
    $src             = $directoryName.$av_file['name'];
    $imageFileType   = pathinfo($src,PATHINFO_EXTENSION);
    $info = pathinfo($src);
    $file_name =  basename($src,'.'.$info['extension']);
    $src2            = $directoryName.$av_file['name'];
    move_uploaded_file($av_file['tmp_name'],$src2);
    $image_name      = str_replace(' ','-',$av_file['name']);
    $new_name1       = time().'.'.$imageFileType; 
    $image1          = $this->prf_crop_call($image_name,$av_data,$new_name1,$directoryName,500,250);
    $cropfliename     = $new_name1;
    $data['success'] = 'Y';
  }else{
   $new_name1       ='';
   $imageFileType='';
   $info='';
   $cropfliename='';
   $data['success'] = 'n';
 }
 $data['full_fliename'] =$new_name1;
 $data['image_extension'] =$imageFileType ;
 $data['image_info'] =$info ;
 $data['Date'] =date('d/m/y') ;
 $data['cropped_fliepath'] ='uploads/profile_img/'.$cropfliename;
 $table_data['profile_img'] ='uploads/profile_img/'.$cropfliename;

 $id=$this->session->userdata('id');

 $this->db->where('id',$id);                
 if($this->db->update($table_name, $table_data)){

 }else{

 }
 return $data;  

}

function prf_crop_call($image_name,$av_data,$new_name,$directoryName,$t_width,$t_height) {  
  $w                 = $av_data['width'];
  $h                 = $av_data['height'];
  $x1                = $av_data['x'];
  $y1                = $av_data['y'];
  list($imagewidth, $imageheight, $imageType) = getimagesize($directoryName.$image_name);
  $imageType                                  = image_type_to_mime_type($imageType);
  $ratio             = ($t_width/$w); 
  $nw                = ceil($w * $ratio);
  $nh                = ceil($h * $ratio);  
  $newImage          = imagecreatetruecolor($nw,$nh);
  switch($imageType) {
    case "image/gif"  : $source = imagecreatefromgif($directoryName.$image_name); 
    break;
    case "image/pjpeg":
    case "image/jpeg" :
    case "image/jpg"  : $source = imagecreatefromjpeg($directoryName.$image_name); 
    break;
    case "image/png"  :
    case "image/x-png": $source = imagecreatefrompng($directoryName.$image_name); 
    break;
  } 
  imagecopyresampled($newImage,$source,0,0,$x1,$y1,$nw,$nh,$w,$h);
  switch($imageType) {
    case "image/gif"  : imagegif($newImage,$directoryName.$new_name); 
    break;
    case "image/pjpeg":
    case "image/jpeg" :
    case "image/jpg"  : imagejpeg($newImage,$directoryName.$new_name,100); 
    break;
    case "image/png"  :
    case "image/x-png": imagepng($newImage,$directoryName.$new_name);  
    break;
  }

}


public function profile_cropping(){ 
  extract($_POST);
  if(!empty($_FILES['profile_img'])){
    $av_data         = json_decode($_POST['avatar_data'],true);
    $av_file         = $_FILES['profile_img'];   
    $av_src          = $av_file['name'];
    $req_height    = 250;

    $req_width       = 250;
    $output  = $this->prf_crop($av_src,$av_data,$av_file,$req_height,$req_width,$table_name,$redirect);

    echo json_encode($output); 
    die();

  }
}

public function update_user()
{
  if (!empty($_POST)) {  
   removeTag($this->input->post());
   $uploaded_file_name = '';
   if (isset($_FILES) && isset($_FILES['profile_img']['name']) && !empty($_FILES['profile_img']['name'])) {
    $uploaded_file_name = $_FILES['profile_img']['name'];
    $uploaded_file_name_arr = explode('.', $uploaded_file_name);
    $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
    $this->load->library('common');
    $upload_sts = $this->common->global_file_upload('uploads/profile_img/', 'profile_img', time().$filename);    
    if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
      $uploaded_file_name = $upload_sts['data']['file_name'];
      if (!empty($uploaded_file_name)) {             
       $image_url='uploads/profile_img/'.$uploaded_file_name;    
       $table_data['profile_img'] = $this->image_resize(100,100,$image_url,$filename);
     }
   }
 }
 $id=$this->session->userdata('id');
 $table_data['gender'] = $this->input->post('gender');
 $table_data['mobileno'] =$this->input->post('mobileno');
 $table_data['country_code'] =$this->input->post('country_code');
 if(!empty($this->input->post('dob'))){
   $table_data['dob'] =date('Y-m-d',strtotime($this->input->post('dob')));
 }else{
  $table_data['dob'] =NULL;
}


$this->db->where('id',$id);                
if($this->db->update('users', $table_data))
{
  $table_datas['address']=$_POST['address'];
  if(!empty($_POST['state_id'])){
    $table_datas['state_id']=$_POST['state_id'];
  }
  if(!empty($_POST['city_id'])){
    $table_datas['city_id']=$_POST['city_id'];
  }
  if(!empty($_POST['country_id'])){
    $table_datas['country_id']=$_POST['country_id'];
  } if(!empty($_POST['pincode'])){
    $table_datas['pincode']=$_POST['pincode'];
  }

  $user_count=$this->db->where('user_id', $id)->count_all_results('user_address');

  if(count($table_datas)>0){
    if($user_count==1){
      $this->db->where('user_id',$id);
      $this->db->update('user_address', $table_datas);
    }else{ 
      $table_datas['user_id']=$id;
      $this->db->insert('user_address', $table_datas);
    }
    $this->session->set_flashdata('success_message','Profile updated successfully');    
    redirect(base_url()."user-settings");   
  }
  else
  {
    $this->session->set_flashdata('error_message','Something wrong, Please try again');
    redirect(base_url()."user-settings");   

  } 


}

}

}

public function update_account()
{


  $id=$this->session->userdata('id');
  $table_data['account_holder_name'] = $this->input->post('account_holder_name');
  $table_data['account_number'] = $this->input->post('account_number');
  $table_data['account_iban'] = $this->input->post('account_iban');
  $table_data['bank_name'] = $this->input->post('bank_name');
  $table_data['bank_address'] = $this->input->post('bank_address');
  $table_data['sort_code'] = $this->input->post('sort_code');
  $table_data['routing_number'] = $this->input->post('routing_number');
  $table_data['account_ifsc'] = $this->input->post('account_ifsc');



  $this->db->where('id',$id);
  $result = $this->db->update('users', $table_data);                
  if($result)
  {
   $this->session->set_flashdata('success_message','Account details updated successfully');    

 }
 else
 {
  $this->session->set_flashdata('error_message','Something wrong, Please try again');


} 

echo json_encode($result);




}

public function update_account_provider()
{


  $id=$this->session->userdata('id');
  $table_data['account_holder_name'] = $this->input->post('account_holder_name');
  $table_data['account_number'] = $this->input->post('account_number');
  $table_data['account_iban'] = $this->input->post('account_iban');
  $table_data['bank_name'] = $this->input->post('bank_name');
  $table_data['bank_address'] = $this->input->post('bank_address');
  $table_data['sort_code'] = $this->input->post('sort_code');
  $table_data['routing_number'] = $this->input->post('routing_number');
  $table_data['account_ifsc'] = $this->input->post('account_ifsc');

  $this->db->where('id',$id);
  $result = $this->db->update('providers', $table_data);                
  if($result)
  {   
   $data=array('tab_ctrl'=>4,'success_message'=>'Account details updated successfully');
   $this->session->set_flashdata($data);   

 }
 else
 {    
  $data=array('tab_ctrl'=>4,'success_message'=>'Something wrong, Please try again');

  $this->session->set_flashdata($data);


} 

echo json_encode($result);




}

public function update_provider()
{
  $uploaded_file_name = '';
  $id=$this->session->userdata('id');
  removeTag($this->input->post());
  $table_data['category'] = $this->input->post('categorys');
  $table_data['subcategory'] = $this->input->post('subcategorys');
  $table_data['mobileno'] = $this->input->post('mobileno');  
  if(!empty($this->input->post('dob'))){
     $table_data['dob']=date('Y-m-d',strtotime($this->input->post('dob')));
   }else{
     $table_data['dob']=NULL;
   }
	if(!empty($this->input->post('homeservice_fee'))){
		$table_data['homeservice_fee'] = $this->input->post('homeservice_fee');  
	}
	if(!empty($this->input->post('homeservice_arrival'))){
		$table_data['homeservice_arrival'] = $this->input->post('homeservice_arrival');  
	} else {
		$table_data['homeservice_arrival'] = 40;
	}
	if(!empty($this->input->post('allowrewards')) && $this->input->post('allowrewards') == 1){
		$table_data['allow_rewards'] = 1;
		$table_data['booking_reward_count'] = $this->input->post('reward_count');  
	} else {
		$table_data['allow_rewards'] = 0;
		$table_data['booking_reward_count'] = 0;
	}
  //commercial register image
  $uploaded_file_name = '';
  if (isset($_FILES) && isset($_FILES['commercial_reg_image']['name']) && !empty($_FILES['commercial_reg_image']['name'])) {
     
        if (!is_dir('uploads/commercial_reg_image')) {
            mkdir('./uploads/commercial_reg_image', 0777, TRUE);
        }

      $uploaded_file_name = $_FILES['commercial_reg_image']['name'];
      $uploaded_file_name_arr = explode('.', $uploaded_file_name);
      $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
      $this->load->library('common');
      $upload_sts = $this->common->global_file_upload('uploads/commercial_reg_image/', 'commercial_reg_image', time() . $filename);
      
      if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
          $uploaded_file_name = $upload_sts['data']['file_name'];
          if (!empty($uploaded_file_name)) {
              $image_url = 'uploads/commercial_reg_image/' . $uploaded_file_name;
              $table_data['commercial_reg_image'] = $image_url;
              //send notification
              $provider_data = $this->db->where('id',$id)->from('providers')->get()->row_array();
              if($provider_data['commercial_verify'] == 1) {
                $admin=$this->db->where('user_id',1)->from('administrators')->get()->row_array();
                if(empty($admin['token'])){
                $sender=$this->api->getToken(14,$admin['user_id']);
                }else{
                $sender=$admin['token'];
                }
                $receiver_token=$this->session->userdata('chat_token');
                $msg=strtolower('We will process your retraction in 24 hours');
                $this->api->insert_notification($sender,$receiver_token,$msg);
              }
          }
      }
  }
  $this->db->where('id',$id);                
  if($this->db->update('providers', $table_data))
  {
   $table_datas['address']=$_POST['address'];
   if(!empty($_POST['state_id'])){
    $table_datas['state_id']=$_POST['state_id'];
  }
  if(!empty($_POST['city_id'])){
    $table_datas['city_id']=$_POST['city_id'];
  }
  if(!empty($_POST['country_id'])){
    $table_datas['country_id']=$_POST['country_id'];
  } if(!empty($_POST['pincode'])){
    $table_datas['pincode']=$_POST['pincode'];
  }

  $provider_count=$this->db->where('provider_id', $id)->count_all_results('provider_address');

  if(count($table_datas)>0){
    if($provider_count==1){
      $this->db->where('provider_id',$id);
      $this->db->update('provider_address', $table_datas);
    }else{ 
      $table_datas['provider_id']=$id;
      $this->db->insert('provider_address', $table_datas);
    }
  }
  
  
  //bank details
	if(!empty($_POST['account_holder_name'])){
		$bank_details['account_holder_name'] = $_POST['account_holder_name'];
	}
	if(!empty($_POST['account_number'])){
		$bank_details['account_number'] = $_POST['account_number'];
	}
	 if(!empty($_POST['account_iban'])){
		$bank_details['account_iban'] = $_POST['account_iban'];
	}
	if(!empty($_POST['bank_name'])){
		$bank_details['bank_name'] = $_POST['bank_name'];
	}
	if(!empty($_POST['bank_address'])){
		$bank_details['bank_address'] = $_POST['bank_address'];
	}
	if(!empty($_POST['sort_code'])){
		$bank_details['sort_code'] = $_POST['sort_code'];
	}
	if(!empty($_POST['routing_number'])){
		$bank_details['routing_number'] = $_POST['routing_number'];
	}
	 if(!empty($_POST['account_ifsc'])){
		$bank_details['account_ifsc'] = $_POST['account_ifsc'];
	}
	$this->db->where('id',$id);  
	$this->db->update('providers', $bank_details);
	
  
  
  
  

  $data=array('tab_ctrl'=>1,'success_message'=>'Profile updated successfully');
  $this->session->set_flashdata($data);
  redirect(base_url()."provider-settings");   
}
else
{
 $data=array('tab_ctrl'=>1,'error_message'=>'Something wrong, Please try again');
 $this->session->set_flashdata($data);
 redirect(base_url()."provider-settings");   

} 



}

public function user_reviews()
{
 $this->data['page'] = 'user_reviews';
$this->db->select("r.*,u.profile_img,u.name,s.service_image,s.service_title");
$this->db->from('rating_review r');
$this->db->join('users u', 'u.id = r.user_id', 'LEFT');
$this->db->join('services s', 's.id = r.service_id', 'LEFT');
$this->db->where(array('r.user_id' => $this->session->userdata('id'),'r.status'=>1,'r.delete_status'=>0))->order_by('r.id','desc');
$this->data['reviews'] = $this->db->get()->result_array();
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');  
}
public function provider_reviews()
{
 $this->data['page'] = 'provider_reviews';
 $this->db->select("r.*,u.profile_img,u.name,s.service_image,s.service_title, s.service_for_userid,service_for");
$this->db->from('rating_review r');
$this->db->join('users u', 'u.id = r.user_id', 'LEFT');
$this->db->join('services s', 's.id = r.service_id', 'LEFT');
$this->db->where(array('r.provider_id' => $this->session->userdata['id'],'r.status'=>1))->order_by('r.id','desc');
$this->data['reviews'] = $this->db->get()->result_array();
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');  
}
public function provider_settings()
{
 $this->data['page'] = 'provider_settings';
 $this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
 $this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
 $this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
 $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
 $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');
}
public function provider_wallet()
{
 $this->data['page'] = 'provider_wallet';
 $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
 $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');
}
public function provider_payment()
{
 $this->data['page'] = 'provider_payment';
 $this->data['services'] = $this->db->where('b.provider_id',$this->session->userdata('id'))->where_in('b.status',[5,6,7])->from('book_service as b')->join('users as u','u.id=b.user_id')->join('services s','s.id=b.service_id')->order_by('b.id','desc')->select('b.*,u.*,s.service_title,s.service_image,b.status as payment_status')->get()->result_array();

 $this->load->vars($this->data);

 $this->load->view($this->data['theme'].'/template');
}  
public function provider_subscription()
{
	$user_id = $this->session->userdata('id');
 $this->data['page'] = 'provider_subscription';
  $this->data['user_details']=$user=$this->db->where('providers.id',$user_id)->join('provider_address','provider_address.provider_id=providers.id','left')->get('providers')->row_array();
   $this->data['paypal_gateway']=settingValue('paypal_gateway');
   $this->data['braintree_key']=settingValue('braintree_key');
 $razor_option=settingValue('razor_option');
  
   $stripe_option_status = $this->db->where('id',settingValue('stripe_option'))->get('payment_gateways')->row()->status;
   $this->data['stripe_option_status']=($stripe_option_status)?$stripe_option_status:0;
   $paypal_option_status = $this->db->where('id',settingValue('paypal_option'))->get('paypal_payment_gateways')->row()->status;
   $this->data['paypal_option_status']=($paypal_option_status)?$paypal_option_status:0;
   $razor_option_status = $this->db->where('id',settingValue('razor_option'))->get('razorpay_gateway')->row()->status;
   $this->data['razor_option_status']=($razor_option_status)?$razor_option_status:0; 
   
   	if($razor_option == 1){
		
		 $this->data['razorpay_apikey']=settingValue('razorpay_apikey');
   $this->data['razorpay_apisecret']=settingValue('razorpay_apisecret');
	}else if($razor_option == 2){
		 $this->data['razorpay_apikey']=settingValue('live_razorpay_apikey');
   $this->data['razorpay_apisecret']=settingValue('live_razorpay_secret_key');
	}
	
	/* Moyaser Payment */
	$moyaser_option=settingValue('moyaser_option');
	if($moyaser_option == 1){		
		$this->data['moyaser_apikey']=settingValue('moyaser_apikey');
		$this->data['moyaser_secret_key']=settingValue('moyaser_secret_key');
	}else if($moyaser_option == 2){
		 $this->data['moyaser_apikey']=settingValue('live_moyaser_apikey');
		 $this->data['moyaser_secret_key']=settingValue('live_moyaser_secret_key');
	}
    $moyaser_option_status = $this->db->where('id',settingValue('moyaser_option'))->get('moyaser_payment_gateway')->row()->status;
	$this->data['moyasar_option_status']=($moyaser_option_status)?$moyaser_option_status:0; 
	
 $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
 $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
 $this->load->vars($this->data);

 $this->load->view($this->data['theme'].'/template');
}
public function provider_availability()
{
 $this->data['page'] = 'provider_availability';
 $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
 $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');
}public function provider_accountdetails()
{
 $this->data['page'] = 'provider_accountdetails';
 $this->data['wallet']=$this->api->get_wallet($this->session->userdata('chat_token'));
 $this->data['wallet_history']=$this->api->get_wallet_history_info($this->session->userdata('chat_token'));
 $this->load->vars($this->data);
 $this->load->view($this->data['theme'].'/template');
}
public function provider_bookings()
{ 
  $data = array(); 

  $this->data['page'] = 'provider_bookings';
  $provider_id = $this->session->userdata('id');
  $status = $this->input->post('status'); 
  $sortBy = $this->input->post('sortBy'); 

  if(!empty($status)){ 
    $conditions['where']['b.status']=$status;
  } 
  $conditions['returnType'] = 'count'; 
  $totalRec = $this->booking->getRows($conditions); 

        // Pagination configuration 
  $config['target']      = '#dataList'; 
  $config['base_url']    = base_url('user/dashboard/ajaxPaginationData'); 
  $config['total_rows']  = $totalRec; 
  $config['per_page']    = $this->perPage; 

        // Initialize pagination library 
  $this->ajax_pagination->initialize($config); 

        // Get records 
  $conditions = array( 
    'limit' => $this->perPage 
  ); 
  $this->data['all_bookings'] = $this->booking->getRows($conditions); 

        // Load the list page view 
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


        // Set conditions for search and filter 
  $status = $this->input->post('status'); 
  $sortBy = $this->input->post('sortBy'); 

  if(!empty($status)){ 
    $conditions['where']['b.status']=$status;
  } 

        // Get record count 
  $conditions['returnType'] = 'count'; 

  $totalRec = $this->booking->getRows($conditions); 

        // Pagination configuration 
  $config['target']      = '#dataList'; 
  $config['base_url']    =  base_url('user/dashboard/ajaxPaginationData'); 
  $config['total_rows']  = $totalRec; 
  $config['per_page']    = $this->perPage; 

        // Initialize pagination library 
  $this->ajax_pagination->initialize($config); 

        // Get records 
  $conditions = array( 
    'start' => $offset, 
    'limit' => $this->perPage 
  ); 
  if(!empty($status)){ 
    $conditions['where']['b.status']=$status;
  } 

  $this->data['all_bookings'] = $this->booking->getRows($conditions); 

        // Load the data list view 
  $this->load->view('user/home/ajax-data', $this->data, false); 
} 

public function rate_review_post()
{
  $review_data = $this->input->post();
  $check_service_status = $this->home->check_booking_status($this->input->post('booking_id'));

  if($check_service_status != '')

  {

    $result = $this->home->rate_review_for_service($review_data);

    if($result == 1)
    {

     $this->session->set_flashdata('success_message','Thank you for your review');   
     $token=$this->session->userdata('chat_token');

     $this->send_push_notification($token,$this->input->post('booking_id'),1,' Have Review The Service'); 

   }
   elseif($result == 2)
   {
    $this->session->set_flashdata('error_message','You have already reviewed this service');


  } 
  else{
    $this->session->set_flashdata('error_message','Booking not completed');
  }
  echo json_encode($result);

}
}

/*push notification*/

public function send_push_notification($token,$service_id,$type,$msg=''){


  $data=$this->api->get_book_info($service_id);

  if(!empty($data)){
    if($type==1){
     $device_tokens=$this->api->get_device_info_multiple($data['provider_id'],1); 
   }else{
     $device_tokens=$this->api->get_device_info_multiple($data['user_id'],2); 
   }

   if($type==2){
    $user_info=$this->api->get_user_info($data['user_id'],$type);

    $name=$this->api->get_user_info($data['provider_id'],1);

  }else{
    $name=$this->api->get_user_info($data['user_id'],2);

    $user_info=$this->api->get_user_info($data['provider_id'],$type);
  }

  

  /*insert notification*/

  $msg=ucfirst($name['name']).' '.strtolower($msg);

  if(!empty($user_info['token'])){
    $this->api->insert_notification($token,$user_info['token'],$msg);
  }

  $title=$data['service_title'];


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
public function user_bookings()
{
    $this->data['page'] = 'user_bookings';
    $user_id = $this->session->userdata('id');
    $status = $this->input->post('status'); 
    $sortBy = $this->input->post('sortBy'); 

    if(!empty($status)){ 
        $conditions['where']['b.status']=$status;
    } 
    $conditions['returnType'] = 'count'; 
    $totalRec = $this->userbooking->getRows($conditions); 

    // Pagination configuration 
    $config['target']      = '#dataList'; 
    $config['base_url']    = base_url('user/dashboard/userajaxPaginationData'); 
    $config['total_rows']  = $totalRec; 
    $config['per_page']    = $this->perPage; 

    // Initialize pagination library 
    $this->ajax_pagination->initialize($config); 

    // Get records 
    $conditions = array( 
      'limit' => $this->perPage 
    ); 

    $this->data['all_bookings'] = $this->userbooking->getRows($conditions); 
    $this->data['placholder_img'] = $this->db->get_where('system_settings', array('key'=>'service_placeholder_image'))->row()->value;
    // Load the list page view 
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
}

function userajaxPaginationData(){ 
        // Define offset 
  $page = $this->input->post('page'); 
  if(!$page){ 
    $offset = 0; 
  }else{ 
    $offset = $page; 
  } 


        // Set conditions for search and filter 
  $status = $this->input->post('status'); 
  $sortBy = $this->input->post('sortBy'); 

  if(!empty($status)){ 
    $conditions['where']['b.status']=$status;
  } 

        // Get record count 
  $conditions['returnType'] = 'count'; 

  $totalRec = $this->userbooking->getRows($conditions); 

        // Pagination configuration 
  $config['target']      = '#dataList'; 
  $config['base_url']    =  base_url('user/dashboard/userajaxPaginationData'); 
  $config['total_rows']  = $totalRec; 
  $config['per_page']    = $this->perPage; 

        // Initialize pagination library 
  $this->ajax_pagination->initialize($config); 

        // Get records 
  $conditions = array( 
    'start' => $offset, 
    'limit' => $this->perPage 
  ); 
  if(!empty($status)){ 
    $conditions['where']['b.status']=$status;
  } 

  $this->data['all_bookings'] = $this->userbooking->getRows($conditions); 

        // Load the data list view 
  $this->load->view('user/home/user-ajax-data', $this->data, false); 
} 
public function create_availability()
{
  $data['tab_ctrl']=3;
  extract($_POST);
  if($this->input->post()){
    $check_availability=8;
   
    foreach($_POST['availability'] as $row){
      if(empty($row['from_time'])){
        $check_availability--;
      }
    }
   if($check_availability==0){
    $this->session->set_flashdata('error_message','Kindly Select min  one day..');    
      redirect(base_url().'provider-availability',$data);
   }
    $params = $this->input->post();

    $check_provider = $this->home->provider_hours($this->session->userdata('id'));

    if(empty($check_provider))
    {

      $result = $this->home->create_availability($params);
    } 
    elseif(!empty($check_provider))
    {

      $result=$this->home->update_availability($params);
    }

    if($result)
    { 

      $data=array('tab_ctrl'=>3,'success_message'=>$this->successMsg);
      $this->session->set_flashdata($data);
      $this->session->set_flashdata('success_message',$this->successMsg);    
      redirect(base_url().'provider-availability',$data);
    }




  }
}


public function image_resize($width=0,$height=0,$image_url,$filename){          

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
    $temp_width = ( int ) ($height * $source_aspect_ratio);
  } else {
    /*
     * Triggered otherwise (i.e. source image is similar or taller)
     */
    $temp_width = $width;
    $temp_height = ( int ) ($width / $source_aspect_ratio);
  }

/*
 * Resize the image into a temporary GD image
 */

$temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
imagecopyresampled(
  $temp_gdim,
  $source_gdim,
  0, 0,
  0, 0,
  $temp_width, $temp_height,
  $source_width, $source_height
);

/*
 * Copy cropped region from temporary image into the desired GD image
 */

$x0 = ($temp_width - $width) / 2;
$y0 = ($temp_height - $height) / 2;
$desired_gdim = imagecreatetruecolor($width, $height);
imagecopy(
  $desired_gdim,
  $temp_gdim,
  0, 0,
  $x0, $y0,
  $width, $height
);

/*
 * Render the image
 * Alternatively, you can save the image in file-system or database
 */
$filename_without_extension =  preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
$image_url =  "uploads/profile_img/".$filename_without_extension."_".$width."_".$height.".jpg";    
imagejpeg($desired_gdim,$image_url);

return $image_url;

/*
 * Add clean-up code here
 */
} 

//paramesh
	public function razor_payment_success()
    { 
		$user_id       = $this->session->userdata('id');
		$user          = $this->db->where('id', $user_id)->get('users')->row_array();
		$params = $this->input->get();
		
			$token=$this->session->userdata('chat_token');
		$user_name     = $user['name'];
		$user_token    = $user['token'];
		$currency_type = $user['currency_code'];	
		
		$amt        = $params['totalAmount'];
		
		$wallet = $this->db->where('user_provider_id', $user_id)->get('wallet_table')->row_array();
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
		$history_pay['tokenid']=$token;
		$history_pay['payment_detail']="Razorpay";
		$history_pay['charge_id']=1;
		$history_pay['exchange_rate']=0;
		$history_pay['paid_status']="1";
		$history_pay['cust_id']="self";
		$history_pay['card_id']="self";
		$history_pay['total_amt']=$amt;
		$history_pay['fee_amt']=0;
		$history_pay['net_amt']=$amt;
		$history_pay['amount_refund']=0;
		$history_pay['current_wallet']=$current_wallet;
		$history_pay['credit_wallet']=$amt;
		$history_pay['debit_wallet']=0;
		$history_pay['avail_wallet']=$amt + $wallet_amt;
		$history_pay['reason']=TOPUP;
		$history_pay['created_at']=date('Y-m-d H:i:s');
		
		
		
			if($this->db->insert('wallet_transaction_history',$history_pay)){
				
				$this->db->where('user_provider_id', $user_id)->update('wallet_table', array(
					'currency_code' => $currency_type,
					'wallet_amt' => $amt+$current_wallet
				));
				echo 0;                
			}else{
				echo 1;                
			}					
		
    }
	public function razorthankyou(){
		$result = $_REQUEST['res'];
		if ($result == 0) {
			$message = (!empty($this->user_language[$this->user_selected]['lg_wallet_amount_add_wallet'])) ? $this->user_language[$this->user_selected]['lg_wallet_amount_add_wallet'] : $this->default_language['en']['lg_wallet_amount_add_wallet'];
			$this->session->set_flashdata('msg_success', $message);
			redirect('user-wallet');
		} else {
			$message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
			$this->session->set_flashdata('message', $message);
			redirect('user-wallet');
		}
	}
	
	public function razorpay_details()
    {
        removeTag($this->input->post());
        $params        = $this->input->post();
        $user_id       = $this->session->userdata('id');
		
		  $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if(!empty($result))
        { 
            foreach($result as $data1){

    
                if($data1['key'] == 'razorpay_apikey'){
                  $apikey = $data1['value'];
                }
    
                if($data1['key'] == 'razorpay_secret_key'){
                  $apisecret = $data1['value'];
                }

    
                if($data1['key'] == 'live_razorpay_apikey'){
                  $apikey = $data1['value'];
                }
    
                if($data1['key'] == 'live_razorpay_secret_key'){
                  $apisecret = $data1['value'];
                }
		   
           }
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
				'rp_contactid' => ($results)?$results->id:'',
				'entity' => ($results)?$results->entity:'',
				'name' => ($results)?$results->name:'',
				'contact' => ($results)?$results->contact:'',
				'email' => ($results)?$results->email:'',
				'type' => ($results)?$results->type:'',
				'reference_id' => ($results)?$results->reference_id:'',
				'batch_id' => ($results)?$results->batch_id:'',
				'active' => ($results)?$results->active:'',
				'accountnumber' => $params['accountnumber'],
				'mode' => $params['mode'],
				'purpose' => $params['purpose'],
				'notes' => $serializedcnotes,
				'created_at' => ($results)?$results->created_at:''
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
					'fund_account_id' => ($faresults)?$faresults->id:'',
					'entity' => ($faresults)?$faresults->entity:'',
					'contact_id' => ($faresults)?$faresults->contact_id:'',
					'account_type' => ($faresults)?$faresults->account_type:'',
					'ifsc' => ($faresults)?$faresults->bank_account->ifsc:'',
					'bank_name' => ($faresults)?$faresults->bank_account->bank_name:'',
					'name' => ($faresults)?$faresults->bank_account->name:'',
					'account_number' => ($faresults)?$faresults->bank_account->account_number:'',
					'active' => ($faresults)?$faresults->active:'',
					'batch_id' => ($faresults)?$faresults->batch_id:'',
					'created_at' => ($faresults)?$faresults->created_at:''
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
						'payout_id' => ($presults)?$presults->id:'',
						'entity' => ($presults)?$presults->entity:'',
						'fund_account_id' => ($presults)?$presults->fund_account_id:'',
						'amount' => ($presults)?$presults->amount:'',
						'currency' => ($presults)?$presults->currency:'',
						'fees' => ($presults)?$presults->fees:'',
						'tax' => ($presults)?$presults->tax:'',
						'status' => ($presults)?$presults->status:'',
						'utr' => ($presults)?$presults->utr:'',
						'mode' => ($presults)?$presults->mode:'',
						'purpose' => ($presults)?$presults->purpose:'',						
						'reference_id' => ($presults)?$presults->reference_id:'',
						'narration' => ($presults)?$presults->narration:'',
						'batch_id' => ($presults)?$presults->batch_id:'',
						'failure_reason' => ($presults)?$presults->failure_reason:'',
						'created_at' => ($presults)?$presults->created_at:''
					);
					$payouts = $this->db->insert('razorpay_payouts', $pydata);
					if($payouts){
						$wdata = array(
							'user_id' => $user_id,
							'amount' => ($presults)?$presults->amount:'',
							'currency_code' => ($presults)?$presults->currency:'',
							'transaction_status' => 1,
							'transaction_date' => date('Y-m-d'),
							'request_payment' => 'RazorPay',
							'status' => 1,
							'created_by' => $user_id,
							'created_at' => ($presults)?$presults->created_at:''
						);
						
						
						$payoutins = $this->db->insert('wallet_withdraw', $wdata);
						if($payoutins){
							$amount        = $presults->amount;
							$user_id       = $this->session->userdata('id');
							$user          = $this->db->where('id', $user_id)->get('providers')->row_array();
							$user_name     = $user['name'];
							$user_token    = $user['token'];
							$currency_type = $user['currency_code'];
							$wallet = $this->db->where('user_provider_id', $user_id)->where('type', 1)->get('wallet_table')->row_array();
							$wallet_amt = $wallet['wallet_amt'];
							$history_pay['token']=$user_token;
							$history_pay['user_provider_id']=$user_id;
							$history_pay['currency_code']='INR';
							$history_pay['credit_wallet'] = 0;
							$history_pay['debit_wallet'] = $amount;
							
							$history_pay['transaction_id']=$presults->id;
							$history_pay['paid_status']='1';
							$history_pay['total_amt']=$presults->amount;
							if($wallet_amt){
								$current_wallet = $wallet_amt-$amount;
							}else{
								$current_wallet = $amount;
							}
							$history_pay['current_wallet']=$wallet_amt;
							$history_pay['avail_wallet'] = $current_wallet;
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
	
	
	
	
	public function bank_details()
    {
        removeTag($this->input->post());
        $params        = $this->input->post();
        $user_id       = $this->session->userdata('id');
        $user_currency = 'INR';
        if (!empty($params)) {
            $check_bank = $this->db->where('user_id', $user_id)->get('bank_account')->num_rows();
            $user_det   = $this->db->where('id', $user_id)->get('providers')->row_array();
            $data       = array(
                'user_id' => $user_id,
                'account_number' => $params['account_no'],
                'account_holder_name' => $user_det['name'],
                'bank_name' => $params['bank_name'],
                'bank_address' => $params['bank_address'],
                'sort_code' => $params['sort_code'],
                'routing_number' => $params['routing_number'],
                'account_ifsc' => $params['ifsc_code'],
                'pancard_no' => $params['pancard_no'],
                'paypal_account' => $params['paypal_id'],
                'paypal_email_id' => $params['paypal_email_id']
            );
            if ($check_bank > 0) {
                $result = $this->db->where('user_id', $user_id)->update('stripe_bank_details', $data);
            } else {
                $result = $this->db->insert('stripe_bank_details', $data);
            }
            if ($result == true) {
                $wallet_data = array(
                    'user_id' => $user_id,
                    'amount' => $params['amount'],
                    'currency_code' => $user_currency,
                    'status' => 1,
                    'transaction_status' => 0,
                    'request_payment' => $params['payment_type'],
                    'created_by' => $user_id,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $amount      = $this->db->insert('wallet_withdraw', $wallet_data);
				
                if ($amount == true) {
                    $amount_withdraw = $this->Stripe_model->wallet_withdraw_flow($params['amount'], $user_currency, $user_id,1);
                }
                $message ='Amount Withdrawn Successfully';
                echo json_encode(array(
                    'status' => true,
                    'msg' => $message
                ));
            } else {
                $message = (!empty($this->user_language[$this->user_selected]['lg_something_went_wrong'])) ? $this->user_language[$this->user_selected]['lg_something_went_wrong'] : $this->default_language['en']['lg_something_went_wrong'];
                echo json_encode(array(
                    'status' => false,
                    'msg' => $message
                ));
            }
        }
    }

	
public function service_map_list(){

  $this->db->select('tab_2.name,tab_1.service_latitude,tab_1.service_longitude,tab_1.service_title')->from('services tab_1');
  $val=$this->db->join('providers tab_2','tab_2.id=tab_1.user_id','LEFT')->get()->result_array();

  if(!empty($val)){

    $result_json = [];

    foreach ($val as $key => $value) {
      $temp = $temp2 = [];
      $temp2[] = $value["service_latitude"];
      $temp2[] = $value["service_longitude"];

      $temp['latLng'] = $temp2;
      $temp['name'] = $value['name'];

      $result_json[] = $temp;

    }

  }

  $data=json_encode($result_json);
  print($data);
}
    
    public function export_invoice($booking_id)
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');

        if (!$this->session->userdata('id')) {
           redirect('user');
        }
        $this->load->model('products_model');
        if(!empty($booking_id)) {
            $where = array('status'=>6, 'id'=>$booking_id);
        }
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
        $mpdf->writeHTML($html);
        $mpdf->Output();
    }
    public function export_muliple_invoice($from_date, $to_date)
    {
        require_once(APPPATH . 'libraries/mpdf/vendor/autoload.php');

        if (!$this->session->userdata('id')) {
           redirect('user');
        }
        $this->load->model('products_model');
        $user_id = $this->session->userdata('id');
        $type = $this->session->userdata('usertype');
        $search = array();
        if ($type == 'user') {
            $where = array('book_service.status'=>6, 'book_service.user_id'=>$user_id);
        }
        else
        {
            $where = array('book_service.status'=>6, 'book_service.provider_id'=>$user_id);
        }
        if ($from_date!='') 
        {
            $where['book_service.service_date >='] = date("Y-m-d", strtotime($from_date));
        }
        if ($to_date!='') 
        {
            $where['book_service.service_date <='] = date("Y-m-d", strtotime($to_date));
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
                $mpdf->AddPage();
                $html = $this->load->view('user/home/invoice', $data, true);
                $mpdf->writeHTML($html);
                
            }
            $mpdf->Output();
        }
    }
    public function user_invoices()
    {
        $this->data['page'] = 'user_invoices';
        $user_id = $this->session->userdata('id');
        $where = array('book_service.status'=>6, 'book_service.user_id'=>$user_id);
        $search = array();
        $totalRec = $this->userbooking->user_invoices('','',$where,$search,'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/dashboard/ajaxuserinvoice'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 

        $this->data['bookings'] = $this->userbooking->user_invoices($this->perPage, 0, $where, $search, 'list'); 
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function ajaxuserinvoice()
    {
        $inp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $user_id = $this->session->userdata('id');
        $search = array();
        $where = array('book_service.status'=>6, 'book_service.user_id'=>$user_id);
        if ($inp['from_date']!='') 
        {
            $where['book_service.service_date >='] = date("Y-m-d", strtotime($inp['from_date']));
        }
        if ($inp['to_date']!='') 
        {
            $where['book_service.service_date <='] = date("Y-m-d", strtotime($inp['to_date']));
        }
        $totalRec = $this->userbooking->user_invoices('','',$where,$search,'count');
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/dashboard/ajaxuserinvoice'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        $this->data['bookings'] = $this->userbooking->user_invoices($this->perPage, $offset, $where, $search, 'list');
        // Load the data list view 

        $this->load->view('user/home/ajaxuserinvoice', $this->data, false);
    }

    public function provider_invoices()
    {
        $this->data['page'] = 'provider_invoices';
        $provider_id = $this->session->userdata('id');
        $where = array('book_service.status'=>6, 'book_service.provider_id'=>$provider_id);
        $search = array();
        $totalRec = $this->userbooking->user_invoices('','',$where,$search,'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/dashboard/ajaxproviderinvoice'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 

        $this->data['bookings'] = $this->userbooking->user_invoices($this->perPage, 0, $where, $search, 'list'); 
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    public function ajaxproviderinvoice()
    {
        $inp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        $provider_id = $this->session->userdata('id');
        $where = array('book_service.status'=>6, 'book_service.provider_id'=>$provider_id);
        if ($inp['from_date']!='') 
        {
            $where['book_service.service_date >='] = date("Y-m-d", strtotime($inp['from_date']));
        }
        if ($inp['to_date']!='') 
        {
            $where['book_service.service_date <='] = date("Y-m-d", strtotime($inp['to_date']));
        }
        $totalRec = $this->userbooking->user_invoices('','',$where,$search,'count');
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/dashboard/ajaxuserinvoice'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 

        // Initialize pagination library 
        $this->ajax_pagination->initialize($config);
        $this->data['bookings'] = $this->userbooking->user_invoices($this->perPage, $offset, $where, $search, 'list');
        // Load the data list view 
        $this->load->view('user/home/ajaxproviderinvoice', $this->data, false);
    }
	public function provider_deposit_history()
	{
		$this->data['page'] = 'provider_deposit_history';
		$this->data['deposit'] = $this->db->where('provider_id',$this->session->userdata('id'))->order_by('id','desc')->get('deposit_details')->result_array();
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	} 
	public function product_payment()
	{
		$this->data['page'] = 'product_payment';
		 
		$order_where = array('product_cart.status'=>1, 'shops.provider_id'=>$this->session->userdata('id'));
		$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno, product_order.payment_type');
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->where_in('product_cart.delivery_status', [5,6] );	
		$this->db->where($order_where);										
		$this->data['product'] = $this->db->get('product_cart')->result_array();
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	}  
	public function user_order_payment()
	{
		$this->data['page'] = 'user_order_payment';
		 
		$order_where = array('product_cart.status'=>1, 'product_cart.user_id'=>$this->session->userdata('id'));
		$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno, product_order.payment_type, shops.provider_id as providerid');
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->where_in('product_cart.delivery_status', [5,6,7] );	
		$this->db->where($order_where);										
		$this->data['product'] = $this->db->get('product_cart')->result_array();
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	}

    public function offlinepayment($sub_id)
  {
   $params = $this->input->post();

    if ($this->input->post('form_submit')) {
      $pro_id       = $this->session->userdata('id');
      $user          = $this->db->where('id', $pro_id)->get('providers')->row_array();

      $uploaded_file_name = '';
      if(!is_dir('uploads/offline')) {
        mkdir('./uploads/offline/', 0777, TRUE);
      }
      if (isset($_FILES) && isset($_FILES['offline_doc']['name']) && !empty($_FILES['offline_doc']['name'])) {
        $uploaded_file_name = $_FILES['offline_doc']['name'];
        $uploaded_file_name_arr = explode('.', $uploaded_file_name);
        $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
        $this->load->library('common');
        $upload_sts = $this->common->global_file_upload('uploads/offline/', 'offline_doc', time() . $filename);

        if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
      
          $uploaded_file_name = $upload_sts['data']['file_name'];

          if (!empty($uploaded_file_name)) {
              $image_url = 'uploads/offline/' . $uploaded_file_name;   
         }
        }
      }
     

     $details = array(
          'bank_name'=>$this->input->post('bank_name'), 
          'holder_name'=>$this->input->post('holder_name'),
          'account_num' => $this->input->post('account_num'),
          'ifsc_code' => $this->input->post('ifsc_code'),
          'branch_name'=>$this->input->post('branch_name'),
          'upi_id'=>$this->input->post('upi_id'),
          );
       
    $result = json_encode($details,true);
    $table_data = $image_url;
    $history_pay['subscriber_id']  = $pro_id;
    $history_pay['subscription_date'] = date('Y-m-d H:i:s');
    $history_pay['tokenid'] = 'Offline Payment';
    $history_pay['payment_details'] = $result;
    $history_pay['upload_doc'] = $image_url;
    $history_pay['subscription_id'] =  $sub_id;
    $history_pay['status'] =  1;

   //subscription_details insert 
   $this->db->select('duration');
   $feerecord = $this->db->get_where('subscription_fee',array('id'=>$sub_id))->row_array();
   $duration = $feerecord['duration']*30;

   $subscription_date = date('Y-m-d H:i:s');
   $expiry_date_time =  date('Y-m-d H:i:s',strtotime(date("Y-m-d  H:i:s", strtotime($subscription_date)) ." +".$duration."days"));
   $new_details['subscriber_id']  = $pro_id;
   $new_details['subscription_id']  = $sub_id;
   $new_details['subscription_date'] = $subscription_date;
   $new_details['type'] = 1;
   $new_details['expiry_date_time'] = $expiry_date_time;
   $new_details['paid_status'] = 0;

  $this->db->where('subscriber_id', $pro_id);
      $count = $this->db->count_all_results('subscription_details');
      if($count == 0){
        $this->db->insert('subscription_details', $new_details);
    $history_pay['sub_id'] = $this->db->insert_id();
      }else{

        $this->db->where('subscriber_id', $pro_id);
        $this->db->update('subscription_details', $new_details);
    $this->db->where('subscriber_id', $pro_id);
        $details_sub = $this->db->get('subscription_details')->row_array();
    $history_pay['sub_id'] = $details_sub['id'];
      }

    //subscription_payment insert
    $result=$this->db->insert('subscription_payment',$history_pay);
    if($result){
      $message='Waiting for admin approval';
      $this->session->set_flashdata('success_message',$message);
          }else{
      $message=$this->web_validation_array['lg12_sorry'].', '.$this->web_validation_array['lg12_something_went_'];
      $this->session->set_flashdata('error_message',$message);
    }
      
    redirect(base_url('provider-subscription'));
    }
    $this->data['page'] = 'offline_payment';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }

  public function delete_account() {
        $inputs['status'] = '0';
        $WHERE = array('id' => $this->input->post('id'));
        $this->db->set($inputs);
        $this->db->where($WHERE);

        if($this->input->post('type') == 'user') {
            $this->db->update('users');
        } else {
            $this->db->update('providers');
        }

        if($this->db->affected_rows() > 0) {
            $this->session->sess_destroy();
            echo '1';
        } else {
            echo '2';
        }
                
    } 
}
