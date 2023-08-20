<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myservice extends CI_Controller {
     function __construct() { 
        parent::__construct(); 
        error_reporting(0);
        

         $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->load->helper('push_notifications');
        $this->load->model('api_model','api');

        $this->load->model('service_model','service');
        $this->load->model('home_model','home');
        $this->load->model('dashboard_model','dashboard');
        // Load pagination library 
        $this->load->library('ajax_pagination'); 
         
        // Load post model 
        $this->load->model('post'); 
         
        // Per page limit 
        $this->perPage = 12; 
         $this->data['theme']     = 'user';
          $this->data['module']    = 'service';
          
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
		
		$errmsg = (!empty($this->user_language[$this->user_selected]['lg_Common_Error'])) ? $this->user_language[$this->user_selected]['lg_Common_Error'] : $this->default_language['en']['lg_Common_Error'];	
		$offersmsg = (!empty($this->user_language[$this->user_selected]['lg_Service_Offer'])) ? $this->user_language[$this->user_selected]['lg_Service_Offer'] : $this->default_language['en']['lg_Service_Offer'];	
		$couponmsg = (!empty($this->user_language[$this->user_selected]['lg_Coupons'])) ? $this->user_language[$this->user_selected]['lg_Coupons'] : $this->default_language['en']['lg_Coupons'];	
		$Delmsg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Msg'])) ? $this->user_language[$this->user_selected]['lg_Edit_Msg'] : $this->default_language['en']['lg_Edit_Msg'];	
		
		$this->OffersDelMsg = $offersmsg.' '.$Delmsg;
        $this->CouponDelMsg = $couponmsg.' '.$Delmsg;
		$this->errmsg = $errmsg;
    } 
     
    public function index(){ 
          if(empty($this->session->userdata('id'))){
          redirect(base_url());
          }
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->post->getRows($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/myservice/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['services'] = $this->post->getRows($conditions); 
        $this->data['page'] = 'my_service_new';
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
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->post->getRows($conditions); 
		
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/myservice/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['services'] = $this->post->getRows($conditions); 
        
        // Load the data list view 
        $this->load->view('user/service/ajax-data', $this->data, false); 
    }
    public function inactive_services()
    { 
         if(empty($this->session->userdata('id'))){
          redirect(base_url());
          }
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->post->getInactiveRows($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/myservice/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['services'] = $this->post->getInactiveRows($conditions); 
        
        $this->data['page'] = 'my_service_inactive';
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
        $totalRec = $this->post->getInactiveRows($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/myservice/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['services'] = $this->post->getInactiveRows($conditions); 
        
        // Load the data list view 
        $this->load->view('user/service/service-inactive-ajax-data', $this->data, false); 
    }

    function applyserviceoffer() {
        $user_inp = $this->input->post();

        //check offer running
        $start_date = date("Y-m-d", strtotime($user_inp['start_date']));
        $end_date = date("Y-m-d", strtotime($user_inp['end_date']));
        $start_time = date("H:i:s", strtotime($user_inp['start_time']));
        $end_time = date("H:i:s", strtotime($user_inp['end_time']));
        $checkoffer = $this->post->checkexistoffer($user_inp['service_id'], $start_date, $end_date); 
        if (!empty($checkoffer)) {
            $result = array('error'=>true, 'msg'=>'The selected dates are already exist.');
        }
        else {
            //insert
            $user_id = $this->session->userdata('id');            
            $ins_data = array('service_id'=>$user_inp['service_id'], 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_time'=>$start_time, 'end_time'=>$end_time,'offer_percentage'=>$user_inp['offer_percentage'], 'created_at'=>date("Y-m-d H:i:s"));
            $ins_data['provider_id'] = $user_id;
            
            $this->post->insertserviceoffer($ins_data);
            $result = array('error'=>false, 'msg'=>'Success');
        }
        echo json_encode($result);
    }

    function applyserviceoffermultiple() {
        $user_inp = $this->input->post();
        $start_date = date("Y-m-d", strtotime($user_inp['start_date']));
        $end_date = date("Y-m-d", strtotime($user_inp['end_date']));
        $start_time = date("H:i:s", strtotime($user_inp['start_time']));
        $end_time = date("H:i:s", strtotime($user_inp['end_time']));
        $sids_arr = explode(",", $user_inp['sids']);
        foreach ($sids_arr as $val) {
            
            $checkoffer = $this->post->checkexistoffer($val, $start_date, $end_date);
            if (empty($checkoffer)) {
                //insert
                $user_id = $this->session->userdata('id');
                $ins_data = array('service_id'=>$val, 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_time'=>$start_time, 'end_time'=>$end_time, 'offer_percentage'=>$user_inp['offer_percentage'], 'created_at'=>date("Y-m-d H:i:s"));
                $ins_data['provider_id'] = $user_id;
                
                $this->post->insertserviceoffer($ins_data);
            }
        }
        echo 'success';
    }

    function service_offer_history() {
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->post->offerrows($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/myservice/ajaxserviceoffer'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['offers'] = $this->post->offerrows($conditions); 
        $this->data['page'] = 'service_offer_history';
        // Load the list page view 
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    function ajaxserviceoffer(){ 
        // Define offset 
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        } 
         
        // Get record count 
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->post->offerrows($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/myservice/ajaxserviceoffer'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['offers'] = $this->post->offerrows($conditions); 
        
        // Load the data list view 
        $this->load->view('user/service/ajaxserviceoffer', $this->data, false); 
    }
    function edit_applyserviceoffer() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $user_inp = $this->input->post();
        //check offer running
        $start_date = date("Y-m-d", strtotime($user_inp['start_date']));
        $end_date = date("Y-m-d", strtotime($user_inp['end_date']));
        $start_time = date("H:i:s", strtotime($user_inp['start_time']));
        $end_time = date("H:i:s", strtotime($user_inp['end_time']));
        $checkoffer = $this->post->checkexistoffer_update($user_inp['service_id'], $start_date, $end_date,$user_inp['id']); 
        if (!empty($checkoffer)) {
            $result = array('error'=>true, 'msg'=>'The selected dates are already exist.');
        } else {
            //Update                     
            $ins_data = array('service_id'=>$user_inp['service_id'], 'start_date'=>$start_date, 'end_date'=>$end_date, 'start_time'=>$start_time, 'end_time'=>$end_time, 'offer_percentage'=>$user_inp['offer_percentage'], 'updated_at'=>date("Y-m-d H:i:s"));           
            
            if ($this->db->update('service_offers', $ins_data, array("id" => $user_inp['id']))) {           
                $result = array('error'=>false, 'msg'=>'Success');
            } else {
                $result = array('error'=>true, 'msg'=>'Error in Offers Updation...');
            }
        }
        echo json_encode($result);
    }
    
    function delete_serviceoffer(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $id = $this->input->post('id');     
       
        $WHERE = array('id' => $id);
        $result = $this->post->deleteserviceoffers($WHERE);
        if ($result) {
            $message = $this->OffersDelMsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        };
    }
    
    /* Coupon */
    function service_coupons(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        
        $this->data['lists'] = $this->post->readServices();      
        $this->data['page'] = 'service_coupons';        
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    function add_servicecoupon(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        
        $user_inp = $this->input->post();   
        $sids_arr = explode(",", $user_inp['service_id']);
        $user_id = $this->session->userdata('id');  
        
        $couponname = "PRO".$user_inp['coupon_name'];
        $checkname = $this->post->checkcouponname($couponname); 
        if (!empty($checkname)) {
            $result = array('error'=>true, 'msg'=>'The Coupon Name is already exist.');
        } else {        
            foreach ($sids_arr as $val) {   
            
                $start_date = date("Y-m-d", strtotime($user_inp['start_date']));
                $end_date = date("Y-m-d", strtotime("+".$user_inp['valid_days']." day", strtotime($start_date)));
                            
                $ins_data = array('service_id'=>$val, 'start_date'=>$start_date, 'end_date'=>$end_date, 'coupon_percentage'=>$user_inp['percentage'], 'coupon_amount'=>$user_inp['price'], 'coupon_name'=>$couponname, 'coupon_type'=>$user_inp['coupon_type'], 'valid_days'=>$user_inp['valid_days'],'user_limit'=>$user_inp['user_limit'], 'description'=>$user_inp['description'],  'created_at'=>date("Y-m-d H:i:s"), 'updated_at'=>date("Y-m-d H:i:s"));   
                $ins_data['provider_id'] = $user_id;    
                $this->db->insert('service_coupons',$ins_data);             
            }           
            $result = array('error'=>false, 'msg'=>'Success');
        }
        echo json_encode($result);
    }
    function service_coupons_details(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $user_id = $this->session->userdata('id');  
        // Load the list page view 
        $this->data['list'] = $this->post->coupon_details($user_id); 
        $this->data['page'] = 'service_coupons_details';
        
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    function get_servicecoupon(){
        $id = $_POST['id'];
        $coupon = $this->db->where('id',$id)->get('service_coupons')->row_array();
        $data = array();        
        $data['name'] = substr($coupon['coupon_name'], 3);
        $data['service_id'] = $coupon['service_id'];
        $data['start_date'] = date("d-m-Y", strtotime($coupon['start_date']));  
        $data['end_date'] = date("d-m-Y", strtotime($coupon['end_date']));          
        $data['coupon_type'] = $coupon['coupon_type'];
        $data['percentage'] = $coupon['coupon_percentage'];
        $data['price'] = $coupon['coupon_amount'];
        $data['valid_days'] = $coupon['valid_days'];
        $data['user_limit'] = $coupon['user_limit'];
        $data['user_count'] = $coupon['user_limit_count'];
        $data['description'] = $coupon['description'];
        if($coupon['status'] == 1) $status = 'Active'; 
        else if($coupon['status'] == 2) $status = 'Inactive'; 
        else if($coupon['status'] == 3) $status = 'Expired'; 
        $data['user_status'] = $status;
        echo json_encode($data);
    }
    function update_servicecoupon() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $user_inp = $this->input->post();
        $start_date = date("Y-m-d", strtotime($user_inp['start_date']));
        $end_date = date("Y-m-d", strtotime("+".$user_inp['valid_days']." day", strtotime($start_date)));
        
        $couponname = "PRO".$user_inp['coupon_name'];
        $checkname = $this->post->checkcouponname($couponname, $user_inp['id']); 
        if (!empty($checkname)) {
            $result = array('error'=>true, 'msg'=>'The Coupon Name is already exist.');
        } else {    
            $ins_data = array('service_id'=>$user_inp['service_id'], 'start_date'=>$start_date, 'end_date'=>$end_date, 'coupon_percentage'=>$user_inp['percentage'], 'coupon_amount'=>$user_inp['price'], 'coupon_name'=>$couponname, 'coupon_type'=>$user_inp['coupon_type'], 'valid_days'=>$user_inp['valid_days'],'user_limit'=>$user_inp['user_limit'], 'description'=>$user_inp['description'], 'updated_at'=>date("Y-m-d H:i:s"));    
                
            if ($this->db->update('service_coupons', $ins_data, array("id" => $user_inp['id']))) {          
                $result = array('error'=>false, 'msg'=>'Success');
            } else {
                $result = array('error'=>true, 'msg'=>'Error in Offers Updation...');
            }
        }
        echo json_encode($result);
    }
    function update_servicecoupon_status(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $id = $this->input->post('id'); 
        $action = $this->input->post('action');
        if($action == 'delete'){
            $status = 0;
        } else if($action == 'Inactive'){
            $status = 2;
        } else {
            $status = 1;
        }
        $WHERE = array('id' => $id);
        $inputs['status'] = $status;
        $result = $this->post->update_coupon_status($inputs, $WHERE);
        if ($result) {
            $message = $this->CouponDelMsg;
            $this->session->set_flashdata('success_message', $message);
            echo 1;
        } else {
            $message = $this->errmsg;
            $this->session->set_flashdata('error_message', $message);
            echo 2;
        };
    }
    /* Rewards */
    function service_rewards(){
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') != 'provider') {
            redirect(base_url());
        }
        $this->data['services'] = $this->post->list_services(['user_id'=>$this->session->userdata('id'), 'status'=>1]);
        $this->data['lists'] = $this->post->list_user_details();      
        $this->data['page'] = 'service_rewards';        
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    function applyservicereward()
    {
        $user_inp = $this->input->post();
        //check offer running
        $ins_data = array('provider_id'=>$this->session->userdata('id'), 'user_id'=>$user_inp['user_id'], 'service_id'=>$user_inp['service_id'], 'reward_type'=>$user_inp['reward_type'], 'reward_discount'=>$user_inp['reward_discount'], 'total_visit_count'=>$user_inp['total_visit_count'], 'status'=>1, 'description'=>$user_inp['description'], 'updated_at'=>date("Y-m-d H:i:s"));

        //Send a notificatio to user
        $service = $this->post->list_services(['id'=>$user_inp['service_id']]);
        //get user token
        $user = $this->dashboard->get_users_details($user_inp['user_id']);

        if ($user_inp['reward_id']!='') {
            //update
            $this->db->where('id', $user_inp['reward_id']);
            $this->db->update('service_rewards', $ins_data);

            $message = 'Your reward is edited for the service '.$service[0]['service_title'].'. Please check the reward and make use of it.';
        }
        else
        {
            $ins_data['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert('service_rewards', $ins_data);

            $message = 'You got a reward from '.$this->session->userdata('name').' for the service '.$service[0]['service_title'].'. Please check the reward and make use of it.';
        }
        //insert in notificatio
        $not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$user['token'], 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
        $this->db->insert('notification_table', $not_data);
        echo 'success';
    }

    function service_reward_details() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
        $user_id = $this->session->userdata('id');  
        // Load the list page view 
        $this->data['list'] = $this->post->reward_details($user_id);
        $this->data['page'] = 'service_reward_details';
        $this->data['services'] = $this->post->list_services(['user_id'=>$this->session->userdata('id'), 'status'=>1]);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    function edit_reward()
    {
        $reward_id = $this->input->post('reward_id');
        $reward = $this->db->where('id',$reward_id)->get('service_rewards')->row_array();
        echo json_encode($reward);
    }
    function delete_reward() {
        $reward_id = $this->input->post('reward_id');
        $this->db->where('id', $reward_id);
        $this->db->update('service_rewards', ['status'=>0]);
        echo 'success';
    }
}
