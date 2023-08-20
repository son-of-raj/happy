<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

   public $data;

   public function __construct() {

        parent::__construct();
		
        $this->load->model('service_model','service');
        $this->load->model('Api_model','api');
        $this->load->model('wallet_model','wallet');
        $this->load->model('Booking_report_model','book');
		$this->load->model('common_model','common_model');
		$this->load->model('templates_model');
		$this->site_name ='Craftesty';

        $this->data['theme'] = 'admin';
        $this->data['model'] = 'bookings';
        $this->data['base_url'] = base_url();
        $this->session->keep_flashdata('error_message');
        $this->session->keep_flashdata('success_message');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role']=$this->session->userdata('role');

    }

	public function index()
	{

    if(!empty($this->input->post())){
      
    }else{
      $this->data['page'] = 'wallet_report_view';
      $this->data['model'] = 'wallet';
      $this->data['list'] = $this->wallet->get_wallet_info();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

		
	}

  public function total_bookings() {
	$this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'total_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_total_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  'service_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
      $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();

      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else
    {
      $this->data['filter']=array(
                                  'service_t'=>'',
                                  'service_s'=>'',
                                  'user_i'=>'',
                                  'provider_i'=>'',
                                  'service_from'=>'',
                                  'service_to'=>'',
                                );
      $this->data['page'] = 'total_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_total_bookings();
      $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }

  /*pending report*/
    public function pending_bookings() {
		$this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'pending_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_pending_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  'service_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else{
      $this->data['page'] = 'pending_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_pending_bookings();
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }
	
/*Inprogress*/

  public function inprogress_bookings() {
	  $this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'inprogress_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_inprogress_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  'service_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else{
      $this->data['page'] = 'inprogress_booking_view';
      $this->data['model'] = 'bookings';
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['list'] = $this->book->get_inprogress_bookings();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }

  /*Completed*/

    public function completed_bookings() {
		$this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'complete_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_complete_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  's bervice_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else{
      $this->data['page'] = 'complete_booking_view';
      $this->data['model'] = 'bookings';
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['list'] = $this->book->get_complete_bookings();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }

  /*Rejected*/

    public function rejected_bookings() { 
	$this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'reject_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_reject_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  'service_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else{
      $this->data['page'] = 'reject_booking_view';
      $this->data['model'] = 'bookings';
 $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['list'] = $this->book->get_reject_bookings();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }

/*Cancelled booking*/

  public function cancel_bookings() {
	  $this->common_model->checkAdminUserPermission(5);
    if(!empty($this->input->post())){
      extract($_POST);
         
          $service_id =$service_title;
          $status     =$service_status;
          $user_id    =$user_id;
          $provider_id=$provider_id;
          $from       =$from;
          $to         =$to;
      $this->data['page'] = 'cancel_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_filter_cancel_bookings($service_id,$status,$user_id,$provider_id,$from,$to);

      $this->data['filter']=array(
                                  'service_t'=>$service_title,
                                  'service_s'=>$service_status,
                                  'user_i'=>$user_id,
                                  'provider_i'=>$provider_id,
                                  'service_from'=>$from,
                                  'service_to'=>$to,
                                );
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');

    }else{
      $this->data['page'] = 'cancel_booking_view';
      $this->data['model'] = 'bookings';
      $this->data['list'] = $this->book->get_cancel_bookings();
       $this->data['all_booking']=$this->db->from('book_service')->where('status in (1,2,3,4,5,6,7,8)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['pending']=$this->db->from('book_service')->where('status',1)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['inprogress']=$this->db->from('book_service')->where('status in (2,3)')->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['completed']=$this->db->from('book_service')->where('status',6)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['rejected']=$this->db->from('book_service')->where('status',5)->where("guest_parent_bookid = 0")->count_all_results();
      $this->data['cancelled']=$this->db->from('book_service')->where('status',7)->where("guest_parent_bookid = 0")->count_all_results();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }
  }
  public function request_list()
	{
			$lists = $this->service->request_list();
	        $data = array();
	        $no = $_POST['start'];
	        foreach ($lists as $template) {
	            $no++;
	            $row    = array();
	            $row[]  = $no;
              $profile_img = $template['profile_img'];
              if(empty($profile_img)){
                $profile_img = 'assets/img/user.jpg';
              }
	            $row[]  = '<a href="#" class="avatar"> <img alt="" src="'.$profile_img.'"></a><h2><a href="#">'.$template['username'].'</a></h2>';
	            $row[]  = $template['contact_number'];
              $row[]  = $template['title'];
              $row[]  = '<p class="price-sup"><sup>RM</sup>'.$template['proposed_fee'].'</p>';
              $row[]  = '<span class="service-date">'.date("d M Y", strtotime($template['request_date'])).'<span class="service-time">'.date("H.i A", strtotime($template['request_time'])).'</span></span>';
              $row[]  = date("d M Y", strtotime($template['created']));
              $val = '';
              $status = $template['status'];
              if($status == -1)
              {
                $val = '<span class="label label-danger-border">Expired</span>';
              }
              if($status == 0)
              {
                $val = '<span class="label label-warning-border">Pending</span>';
              }
              elseif($status == 1)
              {
                $val = '<span class="label label-info-border">Accepted</span>';
              }
              elseif($status == 2)
              {
                $val = '<span class="label label-success-border">Completed</span>';
              }
              elseif($status == 3)
              {
                $val = '<span class="label label-danger-border">Declined</span>';
              }
              elseif($status == 4)
              {
                $val = '<span class="label label-danger-border">Deleted</span>';
              }
	            $row[]  = $val;
	            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->service->request_list_all(),
                        "recordsFiltered" => $this->service->request_list_filtered(),
                        "data" => $data,
                );

        //output to json format
        echo json_encode($output);

	}

   public function delete_service()
  {
    $id=$this->input->post('service_id');

    $inputs['status']= '0';
    $WHERE =array('id' => $id);
    $result=$this->service->update_service($inputs,$WHERE);


    if($result)
   {
           $this->session->set_flashdata('success_message','Service deleted successfully');    
           redirect(base_url()."service-list");   
    }
    else
    {
        $this->session->set_flashdata('error_message','Something wrong, Please try again');
        redirect(base_url()."service-list");   

     } 
  }



/* rejected payments */

public function reject_booking_payment(){
	$this->common_model->checkAdminUserPermission(5);
	$id=$this->uri->segment('2');

	if(!empty($this->uri->segment('2'))){	  
		$returns= $this->db->select('id, service_id,admin_change_status,status')->where('id',$id)->get('book_service')->row_array();
		if($returns['admin_change_status'] == 1){
			$this->session->set_flashdata('success_message','Payment Refund Details Already Updated');
			if($returns['status'] == 5) redirect(base_url('admin/reject-report'));
			else redirect(base_url('admin/cancel-report'));
		}
		$this->data['page'] = 'edit_reject_booking_view';
		$this->data['model'] = 'bookings';
		$this->data['list'] = $this->book->get_reject_bookings_by_id($this->uri->segment('2'));
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template'); 
	}else{
		redirect(base_url('admin/reject-report'));
	}


    
}

public function update_reject_payment(){
	
	$this->common_model->checkAdminUserPermission(5);
	if(!empty($_POST['booking_id'])) {	  
		$pay= $this->book->get_reject_bookings_by_id($_POST['booking_id']);
    }
	if(!empty($pay['id'])){
		$paid_token='';
		$tomailid = '';
		if($_POST['token']==$pay['user_token']){
			$paid_token=$pay['user_token'];
			$this->data['uname'] = $pay['user_name'];
			$tomailid = $pay['user_email'];			
		}
		if($_POST['token']==$pay['provider_token']){
			$paid_token=$pay['provider_token'];
			$this->data['uname'] = $pay['provider_name'];
			$tomailid = $pay['provider_email'];			
		}
		if($_POST['pay_for'] == 1){ // Declined Refund
			//Send Notification
			$this->load->helper('push_notifications');
			$msg = "Admin has declined the refund of amount(".$pay['amount']." ".$pay['currency_code'].") of this service '".$pay['service_title']."' on ".date('Y-m-d').". ".$_POST['pay_comment'].".";
			$not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$_POST['utoken'], 'message'=>$msg, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
			$this->db->insert('notification_table', $not_data);
			
			$book_h['admin_change_status']=1;
			$book_h['updated_on']=(date('Y-m-d H:i:s'));
			$book_h['reject_paid_token']=$paid_token;;
			$book_h['admin_reject_comment']=$_POST['favour_comment'];
			$b_where =array('id'=> $pay['id']);
			$results=$this->book->book_update_admin($book_h,$b_where);			
			$this->session->set_flashdata('success_message','Payment refunded successfully');
			if($pay['status'] == 5){
				redirect(base_url('admin/reject-report'));
			} else {
				redirect(base_url('admin/cancel-report'));
			}	
			exit;		
		} else {
			
			/* Refund Code */
			$book_id = $pay['id'];
			$service_id = $pay['service_id'];
			$payqry = $this->db->select('*')->where('book_id',$book_id)->get('moyasar_table')->row_array();		
			if(!empty($payqry)) {
				$transid = $payqry['transaction_id'];
				$paidamt = $payqry['total_amount'];
				
				$moyaser_option=settingValue('moyaser_option');
				if($moyaser_option == 1){		
					$moyaser_apikey=settingValue('moyaser_apikey');
					$moyaser_secret_key=settingValue('moyaser_secret_key');
				}else if($moyaser_option == 2){
					 $moyaser_apikey=settingValue('live_moyaser_apikey');
					 $moyaser_secret_key=settingValue('live_moyaser_secret_key');
				}

				$config['publishable_key'] = $moyaser_apikey;
				$config['secret_key'] = $moyaser_secret_key;

				$this->load->library('moyasar', $config);
						
				if($pay['status'] == 5){
					$finalamt = $paidamt - 0.5;
					$input['amount'] = $finalamt * 100;
					$type = $pay['user_type'];
					$reasons = "Booking Rejected by User and refunded by Admin.";
				} else{
					$input['amount'] = $paidamt * 100;			
					$type = $pay['provider_type'];
					$reasons = "Booking Rejected by Provider and refunded by Admin.";
				}
				$refund_info = $this->moyasar->moyasar_refund($transid,$input);
				$result_array = json_decode($refund_info,true);
				if(isset($result_array['status']) && $result_array['status'] == 'refunded'){	
					$ids=$this->db->select('user_id')->where('token',$this->session->userdata('chat_token'))->get('administrators')->row()->user_id;
					$arr['token'] = $this->session->userdata('chat_token');
					$arr['user_provider_id'] = $ids;
					$arr['service_subscription_id'] = $pay['service_id'];
					$arr['book_id'] = $book_id;
					$arr['type'] = 0;
					
					$arr['transaction_id'] = $result_array['id'];
					$arr['amount'] = $paidamt;
					$arr['currency_code'] =  $result_array['currency'];
					$arr['total_amount'] =  $result_array['refunded'] / 100;
					$arr['reason'] =  $reasons;
					$arr['created_at'] =  date("Y-m-d H:i:s");
					$arr['response'] =  $refund_info;
					$this->db->insert('moyasar_table', $arr); 
					
					$book_h['admin_change_status']=1;
					$book_h['updated_on']=(date('Y-m-d H:i:s'));
					$book_h['reject_paid_token']=$paid_token;;
					$book_h['admin_reject_comment']=$_POST['favour_comment'];
					$b_where =array('id'=> $pay['id']);
					$results=$this->book->book_update_admin($book_h,$b_where);
					
					//email					
					
					$this->session->set_flashdata('success_message','Payment refunded successfully');
					
 					$phpmail_config=settingValue('mail_config');
					if(isset($phpmail_config)&&!empty($phpmail_config)){
					if($phpmail_config=="phpmail"){
					  $from_email=settingValue('email_address');
					}else{
					  $from_email=settingValue('smtp_email_address');
					}
					}
					$this->data['service_amount']= $input['amount'] / 100;
					$this->data['service_date']= $pay['service_date'];
					$this->data['service_title']= $_POST['service_name'];
					$this->data['comments'] = $_POST['favour_comment'];
					
					$bodyid = 5;
					$tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
					$body = $tempbody_details['template_content'];
					$body = str_replace('{user_name}', $this->data['uname'], $body);
					$body = str_replace('{service_amount}', $this->data['service_amount']." ".$result_array['currency'], $body);
					$body = str_replace('{service_title}', "'".$pay['service_title']."'", $body);
					$body = str_replace('{service_date}', date('Y-m-d'), $body);
					$body = str_replace('{admin_comments}', $_POST['favour_comment'], $body);
					$body = str_replace('{sitetitle}',$this->site_name, $body);
					$preview_link = base_url();
					$body = str_replace('{preview_link}',$preview_link, $body);
					
					$this->load->library('email');
					
					//Send mail to provider
					if(!empty($from_email)&&isset($from_email)){
					$mail = $this->email
						->from($from_email)
						->to($tomailid)
						->subject('Service Booking Refund')
						->message($body)
						->send();
					}
					
					//Send Notification
					$this->load->helper('push_notifications');
					$msg = "Admin has refunded the amount(".$this->data['service_amount']." ".$result_array['currency'].") of this service '".$pay['service_title']."' on ".date('Y-m-d').". ".$_POST['pay_comment'].".";
					$not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$_POST['utoken'], 'message'=>$msg, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
					$this->db->insert('notification_table', $not_data);
					
					if($pay['status'] == 5){
						redirect(base_url('admin/reject-report'));
					} else {
						redirect(base_url('admin/cancel-report'));
					}			
					
				} else { 
					$this->session->set_flashdata('error_message',ucfirst($result_array['message']));
					redirect(base_url('admin/reject-report'));

				}
			} else {
				$this->session->set_flashdata('error_message','Please try again');
				redirect(base_url('admin/reject-report'));
			}
		}
	} else {
        $this->session->set_flashdata('error_message','Please try again');
		redirect(base_url('admin/reject-report')); 
	}
  
	
}
 //Booking status changed by Admin
  public function change_status_byAdmin()
  {
    $this->common_model->checkAdminUserPermission(5);
   

    $params=$this->input->post();
    if(!empty($params['status']) && !empty($params['id']) ) 
    {
      $status = $params['status'];
      $row_id = $params['id'];
      $user_id=$params['user_id'];
      $provider_id=$params['provider_id'];
      $service_id=$params['service_id'];
      $updated_on = date('Y-m-d H:i:s'); 

      $update_data['reason'] = "";
      if (!empty($params['review'])) {
          $update_data['reason'] = $params['review'];
      }

      $update_data['status'] = $status;
      $update_data['updated_on'] = $updated_on;
      $update_data['admin_change_status'] = 1;

      $WHERE = array('id'=>$row_id, 'user_id'=>$user_id, 'provider_id'=>$provider_id, 'service_id'=>$service_id);
      $result=$this->service->update_bookingstatus($update_data,$WHERE);
      $service_data = $this->db->where('id',$service_id)->from('services')->get()->row_array();

      $user_data = $this->db->where('id',$user_id)->from('users')->get()->row_array();
      
      $provider_data = $this->db->where('id',$provider_id)->from('providers')->get()->row_array();

      if($result) { 
        $message= 'Booking updated successfully';
        $token=$this->session->userdata('chat_token');

        if($status==1){
          $this->send_push_notification($token,$row_id,2,' Have Pending The Service');
          $success_message = "Admin have changed as <b> Pending The Service </b> - ( ".$service_data['service_title']." ).";
        }

        if($status==2){
          $this->send_push_notification($token,$row_id,2,' Have Inprogress The Service');
          $success_message = "Admin have changed as <b> Accepted The Service </b> - ( ".$service_data['service_title']." ).";
        }

        if($status==3){
          $this->send_push_notification($token,$row_id,2,' Have Completed Request The Service');
          $success_message = "Admin have changed as <b> Completed The Service </b> - ( ".$service_data['service_title']." )";
        }

        if($status==4){
          $this->send_push_notification($token,$row_id,2,' Have Accepted The Service');
          $success_message = "Admin have changed as <b> Accepted The Service By User </b> - ( ".$service_data['service_title']." )";
        }

        if($status==5){
          $this->send_push_notification($token,$row_id,2,' Have Rejected The Service');
          $success_message = "Sorry to Say! Admin have changed as <b> Rejected The Service </b> - ( ".$service_data['service_title']." )<br>Reason : ".$update_data['reason'].". "; 
        }

        if($status==6){
          $token=$this->session->userdata('chat_token');
          $this->api->user_accept_history_flow($row_id);

          //COD changes
          $coddata['status'] = 1;
          $this->db->where('book_id', $row_id);
          $this->db->update('book_service_cod', $coddata);

          $this->send_push_notification($token,$row_id,1,' Have Accepted Completed The Service');
          $success_message = "Admin have changed as <b> Accepted Your Completed Request For This Service </b> - ( ".$service_data['service_title']." ). Please check your wallet the amount was credited !";
        }

        if($status==7){
          $this->send_push_notification($token,$row_id,2,' Have Cancelled The Service');
          $success_message = "Sorry to Say! Admin have changed as <b> Cancelled The Service </b> - ( ".$service_data['service_title']." )<br>Reason : ".$update_data['reason'].". ";
        }

        //Sending mail after changing booking status
        $bodyid = 4;
        $tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
        $body = $tempbody_details['template_content'];
        $body = str_replace('{success_message}',$success_message, $body);
        $body = str_replace('{sitetitle}',$this->site_name, $body);
        $preview_link = base_url();
        $body = str_replace('{preview_link}',$preview_link, $body);

        $phpmail_config=settingValue('mail_config');
        if(isset($phpmail_config)&&!empty($phpmail_config)){
          if($phpmail_config=="phpmail"){
            $from_email=settingValue('email_address');
          }else{
            $from_email=settingValue('smtp_email_address');
          }
        }
        $this->load->library('email');
        $this->load->library('sms');

        //To User
        $this->data['uname']=$user_data['name'];
        $this->data['success_message']=$success_message;

        $body = str_replace('{user_name}', $user_data['name'], $body);
        //To Provider   
        $this->data['uname']=$provider_data['name'];
        $this->data['success_message']=$success_message;     

        $body = str_replace('{user_name}', $user_data['name'], $body);
        
        
        echo "1";
      } 
      else {
        $message = 'Something went wrong.Please try again later.';
        echo "2";
      }      
    } else {
      echo "3";
      return false;
    }
  }
  public function send_push_notification($token, $service_id, $type, $msg = '') 
  {
    $data = $this->api->get_book_info($service_id);
    if (!empty($data)) {
      //get userInfo        
      $user_info = $this->api->get_user_info($data['user_id'], 2);
      $provider_info = $this->api->get_user_info($data['provider_id'], 1);

      /* insert notification */
      $msg = 'Admin changed status as <b>' . strtolower($msg) . '</b>';

      if(!empty($user_info['token'])) 
      {
        $this->api->insert_notification($token, $user_info['token'], $msg);
      }
      if(!empty($provider_info['token'])) 
      {
        $this->api->insert_notification($token, $provider_info['token'], $msg);
      }

    } else {
        //$this->token_error();
    }
  }

public function update_reject_payment1(){
	$this->common_model->checkAdminUserPermission(5);
  if(!empty($_POST['booking_id']))
      
    $pay= $this->book->get_reject_bookings_by_id($_POST['booking_id']);

  if(!empty($pay['id'])){
            $paid_token='';
			$tomailid = '';
            if($_POST['token']==$pay['user_token']){
            $paid_token=$pay['user_token'];
			$this->data['uname'] = $pay['user_name'];
			$tomailid = $pay['user_email'];
            }
            if($_POST['token']==$pay['provider_token']){
            $paid_token=$pay['provider_token'];
			$this->data['uname'] = $pay['provider_name'];
			$tomailid = $pay['provider_email'];
            }

            $data['book_id']=$pay['id'];
            $data['service_title']=$_POST['service_name'];
            $data['amount']=$pay['amount'];
            $data['token']=$paid_token;
            $data['favour_comment']=$_POST['favour_comment'];

            $ret=$this->book->reject_pay_proccess($data);
            if($ret){
               $this->session->set_flashdata('success_message','Reject Payment added successfully');
			   
			   $phpmail_config=settingValue('mail_config');
              if(isset($phpmail_config)&&!empty($phpmail_config)){
                if($phpmail_config=="phpmail"){
                  $from_email=settingValue('email_address');
                }else{
                  $from_email=settingValue('smtp_email_address');
                }
              }
			  $this->data['service_amount']= $pay['amount'];
			  $this->data['service_date']= $pay['service_date'];
			  $this->data['service_title']= $data['service_title'];
              $this->data['comments'] = $data['favour_comment'];
			  $bodyid = 5;
			  $tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
			  $body = $tempbody_details['template_content'];
			  $body = str_replace('{user_name}', $this->data['uname'], $body);
			  $body = str_replace('{service_amount}', $pay['amount'], $body);
			  $body = str_replace('{service_title}', $pay['service_title'], $body);
			  $body = str_replace('{service_date}', $pay['service_date'], $body);
			  $body = str_replace('{admin_comments}', $data['favour_comment'], $body);
			  $body = str_replace('{sitetitle}',$this->site_name, $body);
			  $preview_link = base_url();
			  $body = str_replace('{preview_link}',$preview_link, $body);
			  
              $this->load->library('email');
              //Send mail to provider
              if(!empty($from_email)&&isset($from_email)){
	        	$mail = $this->email
	            	->from($from_email)
	            	->to($tomailid)
	            	->subject('Service Booking Refund')
                    ->message($body)
	            	->send();
	         }
			  //send sms to provider
			  
      redirect(base_url('admin/reject-report'));

            }else{
               $this->session->set_flashdata('error_message','Something wrong, Please try again');
      redirect(base_url('admin/reject-report'));

            }
          


  }else{
               $this->session->set_flashdata('error_message','Something wrong, Please try again');
      redirect(base_url('admin/reject-report'));


  
  }
}


  
}

?>
