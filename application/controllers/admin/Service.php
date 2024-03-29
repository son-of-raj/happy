<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends CI_Controller {

 public $data;

 public function __construct() {

  parent::__construct();
  $this->load->model('service_model','service');
  $this->load->model('common_model','common_model');
  $this->load->model('Api_model','api');
  $this->data['theme'] = 'admin';
  $this->data['model'] = 'service';
  $this->data['base_url'] = base_url();
  $this->session->keep_flashdata('error_message');
  $this->session->keep_flashdata('success_message');
  $this->load->helper('user_timezone_helper');
  $this->data['user_role']=$this->session->userdata('role');

  $this->load->helper('ckeditor');
        // Array with the settings for this instance of CKEditor (you can have more than one)
        $this->data['ckeditor_editor4'] = array(
            //id of the textarea being replaced by CKEditor
            'id' => 'ck_editor_textarea_id4',
            // CKEditor path from the folder on the root folder of CodeIgniter
            'path' => 'assets/js/ckeditor',
            // optional settings
            'config' => array(
                'toolbar' => "Full",
                'filebrowserBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
            )
        );
}

public function index()
{
  redirect(base_url('subscriptions'));
}
public function subscriptions()
{
	$this->common_model->checkAdminUserPermission(9);
  if($this->session->userdata('admin_id'))
  {
    $this->data['page'] = 'subscriptions';
    $this->data['model'] = 'service';
    $this->data['currency_code'] = settings('currency');
    $this->data['list'] = $this->service->subscription_list();
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }
  else {
   redirect(base_url()."admin");
 }
}
public function delete_subsciption() {
  $inp = $this->input->post();
  $this->db->where('id', $inp['id']);
  $this->db->update('subscription_fee', ['status'=>0]);
  echo json_encode("success");
}
public function add_subscription()
{
	$this->common_model->checkAdminUserPermission(9);
	
  if($this->session->userdata('admin_id'))
  {
    $this->data['page'] = 'add_subscription';
    $this->data['model'] = 'service';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }
  else {
   redirect(base_url()."admin");
 }

}

public function check_subscription_name()
{
  $subscription_name = $this->input->post('subscription_name');
  $id = $this->input->post('subscription_id');
  if(!empty($id))
  {
    $this->db->select('*');
    $this->db->where('subscription_name', $subscription_name);
    $this->db->where('id !=', $id);
    $this->db->from('subscription_fee');
    $result = $this->db->get()->num_rows();
  }
  else
  {
    $this->db->select('*');
    $this->db->where('subscription_name', $subscription_name);
    $this->db->from('subscription_fee');
    $result = $this->db->get()->num_rows();
  }
  if ($result > 0) {
    $isAvailable = FALSE;
  } else {
    $isAvailable = TRUE;
  }
  echo json_encode(
    array(
      'valid' => $isAvailable
    ));
}

public function save_subscription()
{
$this->common_model->checkAdminUserPermission(9);
	removeTag($this->input->post());
  $data['subscription_name'] = $this->input->post('subscription_name');
  $data['fee'] = $this->input->post('subscription_amount');
  $data['currency_code'] = settings('currency');
  $data['duration'] = $this->input->post('subscription_duration');
  $data['fee_description'] = $this->input->post('fee_description');
  $data['status'] = $this->input->post('status');
  $data['type'] = $this->input->post('subfor');
  $data['subscription_content'] = $this->input->post('subdetais');
  $data['subscription_type'] = $this->input->post('subscription_type');
  $result = $this->db->insert('subscription_fee', $data);
  if(!empty($result))
  {
   $this->session->set_flashdata('success_message','Subscription added successfully');
   echo 1;
 }
 else
 {
  $this->session->set_flashdata('error_message','Something wrong, Please try again');
  echo 2;
}
}

public function edit_subscription($id)
{
	$this->common_model->checkAdminUserPermission(9);
  if($this->session->userdata('admin_id'))
  {
    $this->data['page'] = 'edit_subscription';
    $this->data['model'] = 'service';
    $this->data['subscription'] = $this->service->subscription_details($id);
    $this->data['currency_code'] = settings('currency');
    //Currency Convertion 
    $currency_code_old = $this->data['subscription']['currency_code'];
    $subscription_amount = get_gigs_currency($this->data['subscription']['fee'], $this->data['subscription']['currency_code'], $this->data['currency_code']);
    $this->data['subscription_amt'] = $subscription_amount;
	
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }
  else {
   redirect(base_url()."admin");
 }

}

public function update_subscription()
{ 
$this->common_model->checkAdminUserPermission(9);
removeTag($this->input->post());
  $where['id'] = $this->input->post('subscription_id');
  $data['subscription_name'] = $this->input->post('subscription_name');
  $data['fee'] = $this->input->post('subscription_amount');
  $data['currency_code'] = settings('currency');
  $data['duration'] = $this->input->post('subscription_duration');
  $data['fee_description'] = $this->input->post('fee_description');
  $data['status'] = $this->input->post('status');
  $data['type'] = $this->input->post('subfor');
  $data['subscription_content'] = $this->input->post('subdetais');
  $data['subscription_type'] = $this->input->post('subscription_type');
  $result = $this->db->update('subscription_fee', $data, $where);
  if(!empty($result))
  {
   $this->session->set_flashdata('success_message','Subscription updated successfully');
   echo 1;
 }
 else
 {
  $this->session->set_flashdata('error_message','Something wrong, Please try again');
  echo 2;
}
}

public function service_providers()
{
  $this->common_model->checkAdminUserPermission(12);

   //palani
    if($this->input->post('form_submit'))
    {
      $username = $this->input->post('username');
      $email = $this->input->post('email'); 
      $from = $this->input->post('from');
      $to = $this->input->post('to');
      $subcategory=$this->input->post('subcategory');
      $this->data['lists'] = $this->service->provider_filter($username,$email,$from,$to,$subcategory);
    
    } else {
      $lists = $this->data['lists'] = $this->service->provider_list();
    }

  $this->data['page'] = 'service_providers';
  // echo '<pre>'; print_r($this->data['lists']); exit;
  $this->data['subcategory']=$this->service->get_subcategory();
  $this->load->vars($this->data);
  // echo '<pre>'; print_r($this->load->vars($this->data)); exit;
  $this->load->view($this->data['theme'].'/template');

}

public function provider_details($value='')
{
	 $this->common_model->checkAdminUserPermission(12);
  $this->data['page'] = 'provider_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}

public function provider_list()
{
	 $this->common_model->checkAdminUserPermission(12);
  extract($_POST);
  if($this->input->post('form_submit'))
  {

    $this->data['page'] = 'service_providers';
    $username = $this->input->post('username');
    $email = $this->input->post('email'); 
    $from = $this->input->post('from');
    $to = $this->input->post('to');
    $subcategory=$this->input->post('subcategory');
    $this->data['lists'] = $this->service->provider_filter($username,$email,$from,$to,$subcategory);
    $this->data['subcategory']=$this->service->get_subcategory();
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');

  }
  else
  {
   $lists = $this->service->provider_list();

   $data = array();
   $no = $_POST['start'];
   foreach ($lists as $template) {

     $no++;
     $row    = array();
     $row[]  = $no;
     $profile_img = $template->profile_img;
     if(empty($profile_img)){
      $profile_img = 'assets/img/user.jpg';
    }

    if(!empty($template->country_code)) {
        $mobileNumber = '+'.$template->country_code.'-'.$template->mobileno;
    } else {
        $mobileNumber = $template->mobileno;
    }
    $row[]  = '<h2 class="table-avatar"><a href="#" class="avatar avatar-sm mr-2"> <img class="avatar-img rounded-circle" alt="" src="'.$profile_img.'"></a><a href="'.base_url().'provider-details/'.$template->id.'">'.$template->name.'</a></h2>';
    $row[]  = $mobileNumber;
    $row[]  = $template->email;
    $created_at='-';
    if (isset($template->created_at)) {
     if (!empty($template->created_at) && $template->created_at != "0000-00-00 00:00:00") {
      $date_time = $template->created_at;
      $date_time = utc_date_conversion($date_time);
      $created_at =date(settingValue('date_format'), strtotime($date_time));
    }
  }
  $row[]  = $created_at;
  $row[]  = $template->subscription_name;
  $val = '';
  
  $status = $template->status;
  $delete_status = $template->status;
  if($status == 2)
  {
    $val = '';
  }
  elseif($status == 1)
  {
    $val = 'checked';
  }

    if($this->session->userdata('role') == 1) { 
        $display_data = '';
        $display_status = '';
    } else {
        $display_data = 'd-none';
        $display_status = 'disabled';
    }
  $row[] ='<div class="status-toggle mb-2"><input id="status_'.$template->id.'" class="check change_Status_provider1" data-id="'.$template->id.'" type="checkbox" '.$val.' '.$display_status.'><label for="status_'.$template->id.'" class="checktoggle">checkbox</label></div>';
  $row[] ='<a href="'.base_url().'edit-provider/'.$template->id.'/1" class="btn btn-sm bg-success-light mr-3"><i class="far fa-edit mr-1"></i> Edit</a><a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_provider_data '.$display_data.'" id="Onremove_'.$template->id.'" data-id="'.$template->id.'"><i class="far fa-trash-alt mr-1"></i> Delete</a>';

  $data[] = $row;
}

$output = array(
  "draw" => $_POST['draw'],
  "recordsTotal" => $this->service->provider_list_all(),
  "recordsFiltered" => $this->service->provider_list_filtered(),
  "data" => $data,
);
echo json_encode($output);
}


}

public function check_pro_emailid()
	{
	  $inputs = $this->input->post('userEmail');
	  $id = $this->input->post('userid');
		  
	  $this->db->where('status != ',0);
	  $this->db->like('email', $inputs, 'match');
	   if(!empty($id)) {
		  $this->db->where('id !=', $id);
	  }
      $this->db->from('providers');
      $result = $this->db->get()->num_rows();
	  if ($result > 0) {
		$isAvailable = FALSE;
	  } else {
		$isAvailable = TRUE;
	  }
	  echo json_encode(
		array(
		  'valid' => $isAvailable
		));
	}
	public function check_pro_mobile()
	{
	  $inputs = $this->input->post('userMobile');
	  $ctrycode =$this->input->post('mobileCode');
	  $id = $this->input->post('userid');
	  
	  $this->db->where('status != ',0);
	  $this->db->where(array('country_code'=>$ctrycode))->like('mobileno',$inputs,'match','after');
	  if(!empty($id)) {
		  $this->db->where('id !=', $id);
	  }
      $this->db->from('providers');
      $result = $this->db->get()->num_rows(); 
	  
	  if ($result > 0) {
		$isAvailable = FALSE;
	  } else {
		$isAvailable = TRUE;
	  }
	  echo json_encode(
		array(
		  'valid' => $isAvailable
		));
	}


	public function edit_provider($id)
	{
		$this->common_model->checkAdminUserPermission(2);
		if($this->session->userdata('admin_id'))
		{
			if ($this->input->post('form_submit')) {
				removeTag($this->input->post());
							
				$id = $this->input->post('user_id');
				$type = $this->input->post('type');
				
				$inputs['name'] = $this->input->post('name');
				$inputs['country_code'] = $this->input->post('country_code');
				$inputs['mobileno'] = $this->input->post('mobileno'); 
				$inputs['email'] = $this->input->post('email');       
        $inputs['status'] = $this->input->post('status');			
				$inputs['updated_at'] = date('Y-m-d H:i:s');
				$cname = '';
				if(!empty($inputs['category'])){
					$cname = $this->db->select('category_type')->where('id',$inputs['category'])->get('categories')->row()->category_type;
				}
				if($cname == '4'){
					$inputs['type'] = 3;
					$type=3;
				} else {
					$inputs['type'] = 1;
					$type=1;
				}
				
				if($type == 1){
					$url = 'service-providers';
				} else {
					$url = 'freelances-providers';
				}
				if ($this->db->update('providers', $inputs, array("id" => $id))) { 
					$this->session->set_flashdata('success_message', 'Providers Updated successfully');
					redirect(base_url() . $url);
				} else { 				
					$this->session->set_flashdata('error_message', 'Please try again later....');
					redirect(base_url() . $url);
				}
			} 
		  
			$this->data['page'] = 'edit_provider';
			$this->data['model'] = 'service';
			$this->data['title'] = 'Edit Provider';
			
			$this->data['providers'] = $this->service->get_provider_details($id);
			$this->data['category'] = $this->service->get_category(); 
					
			$this->load->vars($this->data);
			$this->load->view($this->data['theme'].'/template');
		} else {
			redirect(base_url()."admin");
		}

	}

public function service_list()
{
	$this->common_model->checkAdminUserPermission(4);
 extract($_POST);
 $this->data['currency_code'] = (settings('currency'))?settings('currency'):'USD';
 $this->data['page'] = 'service_list';

 if ($this->input->post('form_submit')) 
 {  
   $service_title = $this->input->post('service_title');
   $category = $this->input->post('category');
   $subcategory = $this->input->post('subcategory');
   $from = $this->input->post('from');
   $to = $this->input->post('to');
   $this->data['list'] =$this->service->service_filter($service_title,$category,$subcategory,$from,$to);
 }
 else 
 {
  $this->data['list'] = $this->service->service_list();
}
$this->load->vars($this->data);
$this->load->view($this->data['theme'].'/template');

}

public function service_details($value='')
{
	$this->common_model->checkAdminUserPermission(4);
  $this->data['page'] = 'service_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}

/*change service list */
public function change_Status_service_list(){
  $id=$this->input->post('id');
  $status=$this->input->post('status');

  if($status==0){
    $avail=$this->service->check_booking_list($id);
    if($avail==0){
      $this->db->where('id',$id);
      if($this->db->update('services',array('status' =>$status))){
        echo "success";
      }else{
        echo "error";
      }
    }else{
      echo "1";
    }
  }else{
    $this->db->where('id',$id);
    if($this->db->update('services',array('status' =>$status))){
      echo "success";
    }else{
      echo "error";
    }
  }
}
public function change_Status()
{
  $id=$this->input->post('id');
  $status=$this->input->post('status');

  $this->db->where('id',$id);
  $this->db->update('providers',array('status' =>$status));
}
public function delete_provider()
{
  $id=$this->input->post('id');
  $data=array('delete_status'=>1);
  $this->db->where('id',$id);
  
  if($this->db->update('providers',$data))
  {
    echo 1;
  }
}
public function service_requests()
{
  if($this->session->userdata('admin_id'))
  {
    $this->data['page'] = 'service_requests';
    $this->data['model'] = 'service';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');		
  }
  else {
   redirect(base_url()."admin");
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
  $row[]  = '<span class="service-date">'.date(settingValue('date_format'), strtotime($template['request_date'])).'<span class="service-time">'.date("H.i A", strtotime($template['request_time'])).'</span></span>';
  $row[]  = date(settingValue('date_format'), strtotime($template['created']));
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

public function freelancer_subscriptions()
{
	$this->common_model->checkAdminUserPermission(9);
  if($this->session->userdata('admin_id'))
  {
    $this->data['page'] = 'freelancer_subscriptions';
    $this->data['model'] = 'service';
    $this->data['currency_code'] = settings('currency');
    $this->data['list'] = $this->service->freelancer_subscription_list();
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }
  else {
   redirect(base_url()."admin");
 }
}

public function freelance_providers()
{
   $this->common_model->checkAdminUserPermission(12);
	 extract($_POST);	 
	 
	 if ($this->input->post('form_submit')) 
	 {  
	   $username = $this->input->post('username');
	   $email = $this->input->post('email');  
	   $from = $this->input->post('from');
	   $to = $this->input->post('to');
	   $subcategory=$this->input->post('subcategory');
	   $this->data['lists'] =$this->service->freelancer_filter($username,$email,$from,$to,$subcategory);
	 }
	 else
	 {
		  
	  $this->data['lists'] = $this->service->freelancer_list();
	}
  $this->data['page'] = 'freelance_providers';
  $this->data['subcategory']=$this->service->get_subcategory();
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
  

}

public function freelancer_details($value='')
{
	 $this->common_model->checkAdminUserPermission(12);
  $this->data['page'] = 'freelancer_details';
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');
}

public function additional_services()
{
   $this->common_model->checkAdminUserPermission(4);
	 extract($_POST);	 
	 
	 if ($this->input->post('form_submit')) 
	 {  
	   $service_title = $this->input->post('service_title');
	   $service_id = $this->input->post('service_id');
	   $from = $this->input->post('from');
	   $to = $this->input->post('to');
   $this->data['list'] =$this->service->additional_services_filter($service_id,$service_title,$from,$to);
	 }
	 else
	 {
		  
	  $this->data['list'] = $this->service->additional_services_list();
	}
  $this->data['page'] = 'additional_service_list';
  
  $this->load->vars($this->data);
  $this->load->view($this->data['theme'].'/template');  

}
public function add_additional_services() {
	 $this->common_model->checkAdminUserPermission(2);
	 if($this->session->userdata('admin_id'))
  {

	if ($this->input->post('form_submit')) {

		removeTag($this->input->post());
	    $uploaded_file_name = '';;
		if (isset($_FILES) && isset($_FILES['addiservice_image']['name']) && !empty($_FILES['addiservice_image']['name'])) {
			$uploaded_file_name = $_FILES['addiservice_image']['name'];
			$uploaded_file_name_arr = explode('.', $uploaded_file_name);
			$filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';echo $filename;
			$this->load->library('common');
			$upload_sts = $this->common->global_file_upload('uploads/additional_services/', 'addiservice_image', time() . $filename);
			if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
				$uploaded_file_name = $upload_sts['data']['file_name'];
				if (!empty($uploaded_file_name)) {
					$image_url = 'uploads/additional_services/' . $uploaded_file_name;
					$table_data['additional_service_image'] = $this->image_resize(360, 220, $image_url, $uploaded_file_name);
				}
			}
		}
		$table_data['service_id'] = $this->input->post('services_for');
		$table_data['service_name'] = $this->input->post('service_title');
		$table_data['amount'] = $this->input->post('amount');
		$table_data['duration'] = $this->input->post('duration');
		$table_data['notes'] = $this->input->post('notes');
		$table_data['duration_in'] = 'min(s)';
		$table_data['created_at'] = date('Y-m-d H:i:s');
		$table_data['status'] = $this->input->post('status');
		if ($this->db->insert('additional_services', $table_data)) {
			$this->session->set_flashdata('success_message', 'Additional Services added successfully');
			redirect(base_url() . "additional-services");
		} else { 
			$this->session->set_flashdata('error_message', 'Please try again later....');
			redirect(base_url() . "add-additional-services");
		}
	}


	$this->data['page'] = 'add_additional_services';        
	$this->data['service_list'] = $this->service->get_service_list();		
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'] . '/template');
	
	}
  else {
   redirect(base_url()."admin");
 }
}
public function edit_additional_services($id)
{
	$this->common_model->checkAdminUserPermission(2);
	
	if($this->session->userdata('admin_id'))
  {

        if ($this->input->post('form_submit')) {
            removeTag($this->input->post());
			
			$uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['addiservice_image']['name']) && !empty($_FILES['addiservice_image']['name'])) {

                $uploaded_file_name = $_FILES['addiservice_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/additional_services/', 'addiservice_image', time() . $filename);

                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/additional_services/' . $uploaded_file_name;
						$upload_url = 'uploads/additional_services/';
                        $table_data['additional_service_image'] = $this->image_resize(360, 220, $image_url, $uploaded_file_name,$upload_url);
                    }
                }
            }

            $table_data['service_id'] = $this->input->post('services_for');
			$table_data['service_name'] = $this->input->post('service_title');
			$table_data['amount'] = $this->input->post('amount');
			$table_data['duration'] = $this->input->post('duration');
			$table_data['notes'] = $this->input->post('notes');
			$table_data['duration_in'] = 'min(s)';
			$table_data['updated_at'] = date('Y-m-d H:i:s');
			$table_data['status'] = $this->input->post('status');
            $this->db->where('id', $id);
            if ($this->db->update('additional_services', $table_data)) {
                $this->session->set_flashdata('success_message', 'Additional Services updated successfully');
                redirect(base_url() . "additional-services");
            } else {
                $this->session->set_flashdata('error_message', 'Please try again later....');
                redirect(base_url() . "additional-services");
            }
        }


        $this->data['page'] = 'edit_additional_services';
		$this->data['service_list'] = $this->service->get_service_list();
        $this->data['services'] = $this->service->additional_service_details($id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
	}
  else {
   redirect(base_url()."admin");
 }

}

public function check_additional_servicename()
{
  $service_title = $this->input->post('service_title');
  $id = $this->input->post('service_id');
  if(!empty($id))
  {
    $this->db->select('*');
    $this->db->where('service_name', $service_title);
    $this->db->where('id !=', $id);
    $this->db->from('additional_services');
    $result = $this->db->get()->num_rows();
  }
  else
  {
    $this->db->select('*');
    $this->db->where('service_name', $service_title);
    $this->db->from('additional_services');
    $result = $this->db->get()->num_rows();
  }
  if ($result > 0) {
    $isAvailable = FALSE;
  } else {
    $isAvailable = TRUE;
  }
  echo json_encode(
    array(
      'valid' => $isAvailable
    ));
}
public function change_status_additionalservice()
{
  $id=$this->input->post('id');
  $status=$this->input->post('status');
  $this->db->where('id',$id);
  if($this->db->update('additional_services',array('status' =>$status))){
	echo "success";
  }else{
	echo "error";
  }
}
public function delete_additional_service()
{
  $id=$this->input->post('id');
  $data=array('status'=>0);
  $this->db->where('id',$id);  
  if($this->db->update('additional_services',$data)){  
	echo "success";
  }else{
	echo "error";
  }
}

public function edit_service($id)
{
	$this->common_model->checkAdminUserPermission(2);
    if($this->session->userdata('admin_id'))
    {
		if ($this->input->post('form_submit')) {
			removeTag($this->input->post());
			$serviceimage = array(); $thumbimage = array(); $mobileimage = array(); $detailsimage = array();
			
			$config["upload_path"] = './uploads/services/';
			$config["allowed_types"] = '*';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			 if ($_FILES["images"]["name"] != '') {
        if (!is_dir('uploads/services')) {
            mkdir('./uploads/services', 0777, TRUE);
        }
        for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
          $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
          $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
          $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
          $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
          $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
          if ($this->upload->do_upload('file')) {
              $data = $this->upload->data();

              $image_url = 'uploads/services/' . $data["file_name"];
              $upload_url = 'uploads/services/';
              $serviceimage[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
              $detailsimage[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
              $thumbimage[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
              $mobileimage[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
          }
        }
      } 

      $inputs['user_id'] = $this->session->userdata('provider_id');
      $inputs['service_title'] = $this->input->post('service_title');
      $inputs['currency_code'] = $this->input->post('currency_code');;
      $inputs['service_sub_title'] = $this->input->post('service_sub_title');
      $inputs['category'] = $this->input->post('category');
      $inputs['subcategory'] = ($this->input->post('subcategory'))?$this->input->post('subcategory'):'';
      $inputs['service_location'] = $this->input->post('service_location');
      $inputs['service_latitude'] = $this->input->post('service_latitude');
      $inputs['service_longitude'] = $this->input->post('service_longitude');
      $inputs['service_amount'] = $this->input->post('service_amount');
      $inputs['about'] = $this->input->post('about');
      $inputs['staff_id'] = ($this->input->post('staff_id'))?implode(',',$this->input->post('staff_id')):'';
      $inputs['shop_id'] = $this->input->post('shop_id');
      $inputs['duration'] = $this->input->post('duration');
      $inputs['duration_in'] = $this->input->post('duration_in');
      $inputs['created_by'] = 'admin';
			$inputs['service_image'] = implode(',', $serviceimage);
			$inputs['service_details_image'] = implode(',', $detailsimage);
			$inputs['thumb_image'] = implode(',', $thumbimage);
			$inputs['mobile_image'] = implode(',', $mobileimage);
			if($this->input->post('autoschedule')){
				$inputs['autoschedule'] = $this->input->post('autoschedule');
				$inputs['autoschedule_days']  = $this->input->post('autoschedule_days');
				$inputs['autoschedule_session']  = $this->input->post('autoschedule_session');
			} else {
				$inputs['autoschedule'] = 0;
				$inputs['autoschedule_days']  = 0;
				$inputs['autoschedule_session']  = 0;
			}
			$inputs['updated_at'] = date('Y-m-d H:i:s');
			if ($this->db->update('services', $inputs, array("id" => $id))) { 
        if (is_countable($_POST['addi_servicename']) && count($_POST['addi_servicename']) > 0) {
        $post = $this->input->post();
        foreach ($post['addi_servicename'] as $key => $value) {               
          $addi_service_data = array(
                      'service_id' => $result,
                      'provider_id' => $this->session->userdata('id'),
                      'service_name' => $post['addi_servicename'][$key],
                      'amount' => $post['addi_serviceamnt'][$key],
                      'duration' => $post['addi_servicedura'][$key],
                      'created_at' => date('Y-m-d H:i:s')
          );
          $this->db->insert('additional_services', $addi_service_data);
        }
      }

      				
				$temp = count($serviceimage);
				if($temp > 0) { 						
					for ($i = 0; $i < $temp; $i++) {
						$image = array(
							'service_id' => $id,
							'service_image' => $serviceimage[$i],
							'service_details_image' => $detailsimage[$i],
							'thumb_image' => $thumbimage[$i],
							'mobile_image' => $mobileimage[$i]
						); 
						$serviceimage_insert = $this->service->insert_serviceimage($image);
					}
				} 
				$this->session->set_flashdata('success_message', 'Services Updated successfully');
				redirect(base_url() . "service-list");
			} else { 
				$this->session->set_flashdata('error_message', 'Please try again later....');
				redirect(base_url() . "service-list");
			}
		} 
	  
		$this->data['page'] = 'edit_service';
		$this->data['model'] = 'service';
		$this->data['title'] = 'Edit Service';
    $this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
    $this->data['map_key'] = settingValue('map_key');
    $this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
    $this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
    $this->data['provider_list'] = $this->service->provider_list();
		$this->data['services'] = $this->service->get_service_id($id);
		$this->data['serv_offered'] = $this->db->from('service_offered')->where('service_id', $id)->get()->result_array();
		$this->data['service_image'] = $this->service->service_image($id);
		$this->data['currency_code'] = settings('currency');
		
		$this->data['category'] = $this->service->get_category(); 
		
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	} else {
		redirect(base_url()."admin");
	}

}

public function service_offers()
{
	$this->common_model->checkAdminUserPermission(12);
	$this->load->model('post','post');
	if ($this->input->post('form_submit')) 
	{  
		$username = $this->input->post('username');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->data['list'] =$this->post->offers_filter($username,$from,$to);
	}
	else
	{
		$this->data['list'] = $this->post->read_serviceoffers();
	}
		
	$this->data['page'] = 'service_offers';
	$this->data['model'] = 'service';
	$this->data['currency_code'] = settings('currency');
	
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
	
}
public function service_offers_details($value='')
{
	$this->common_model->checkAdminUserPermission(12);
	$this->load->model('post','post');
	$this->data['page'] = 'service_offers_details';
	$user_id = $this->uri->segment('2');
	$this->data['offerslist'] = $this->post->get_offers_details($user_id);
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
}

public function service_coupons()
{
	$this->common_model->checkAdminUserPermission(12);
	$this->load->model('post','post');
	if ($this->input->post('form_submit')) 
	{  
		$username = $this->input->post('username');
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		$this->data['list'] =$this->post->coupons_filter($username,$from,$to);
	}
	else
	{
		$this->data['list'] = $this->post->read_servicecoupons();
	}
	
	$this->data['page'] = 'service_coupons';
	$this->data['model'] = 'service';
	$this->data['currency_code'] = settings('currency');

	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
		 
	
}
public function service_coupons_details($value='')
{
	$this->common_model->checkAdminUserPermission(12);
	$this->load->model('post','post');
	$this->data['page'] = 'service_coupons_details';
	$coupon_id = $this->uri->segment('2');
	$this->data['couponslist'] = $this->post->get_coupons_details($coupon_id);
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
}

public function subscriptions_lists()
{
	$this->common_model->checkAdminUserPermission(12);
	extract($_POST);	 

	if ($this->input->post('form_submit')) 
	{  
		$username = $this->input->post('username');	  
		$from = $this->input->post('from');
		$to = $this->input->post('to');	  
		$this->data['lists'] =$this->service->subscriptionlist_filter($username,$from,$to);
	}
	else
	{	  
		$this->data['lists'] = $this->service->subscriptionlist();
	}
	$this->data['page'] = 'subscriptions_lists';
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
}
public function update_subscriptions($value='')
{
	$this->common_model->checkAdminUserPermission(12);
	if ($this->input->post('form_submit')) 
	{ 
		$days = $_POST['duration'];
		$sid  = $_POST['subid'];
		$uid  = $_POST['proid'];
		$details = $this->db->get_where('subscription_details',array('subscriber_id'=>$uid))->row_array();
		$expiry_date = $details['expiry_date_time'];
		$expiry_date_time =  date('Y-m-d H:i:s',strtotime(date("Y-m-d  H:i:s", strtotime($expiry_date)) ." +".$days."days"));


		$new_details['subscriber_id'] = $uid;
		$new_details['subscription_id'] = $details['subscription_id'];
		$new_details['subscription_date'] =$details['subscription_date'];
		$new_details['expiry_date_time'] = $expiry_date_time;
		$new_details['type']=$details['type'];
		$this->db->where('subscriber_id', $uid);
		$this->db->update('subscription_details', $new_details);
		$this->db->insert('subscription_details_history', $new_details);
		$this->session->set_flashdata('success_message', 'Subscription Duration Increased Successfully...');
		
		
		$phpmail_config=settingValue('mail_config');
		if(isset($phpmail_config)&&!empty($phpmail_config)){
			if($phpmail_config=="phpmail"){
			  $from_email=settingValue('email_address');
			}else{
			  $from_email=settingValue('smtp_email_address');
			}
		}
		$tomailid = $this->db->select('email')->where('id',$uid)->get('providers')->row()->email;		
		$body = '<h5>Hi,</h5><p>Admin has extended your subscription.</p><p>For more information login to our site:</p><p><a href="'.base_url().'" target="_blank">View</a></p>';

		$this->load->library('email');
		if(!empty($from_email)&&isset($from_email)){
		$mail = $this->email
			->from($from_email)
			->to($tomailid)
			->subject('Subscription Extends')
			->message($body)
			->send();
		}
		
		
		redirect(base_url() . "subscriptions-lists");
		
	}
	$this->data['page'] = 'update_subscriptions';
	$this->load->vars($this->data);
	$this->load->view($this->data['theme'].'/template');
}


public function image_resize($width = 0, $height = 0, $image_url, $filename,$upload_url) {

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
        $filename_without_extension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_url = $upload_url. $filename_without_extension . "_" . $width . "_" . $height . "." . $extension;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }

    public function verify_provider_commercial() {
      $id = $this->input->post('user_id');
      $table_data['commercial_verify'] = 2;
      $this->db->where('id', $id);
      if ($this->db->update('providers', $table_data)) {
        $this->session->set_flashdata('success_message', 'Verified Successfully');

        //send notification
        $provider=$this->db->where('id',$id)->from('providers')->get()->row_array();
        $sender=$this->session->userdata('chat_token');
        $msg=strtolower('congratulations your account is approved, you can add your services now');
        $this->api->insert_notification($sender,$provider['token'],$msg);

        echo 1;
      } else {
        $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
        echo 0;
      }
    }

    public function add_provider()
  {
    $this->common_model->checkAdminUserPermission(2);
    if($this->session->userdata('admin_id'))
    {
      if ($this->input->post('form_submit')) {
        removeTag($this->input->post());
        $id = $this->input->post('user_id');
        $type = $this->input->post('type');
        
        $inputs['name'] = $this->input->post('name');
        $inputs['country_code'] = $this->input->post('country_code');
        $inputs['mobileno'] = $this->input->post('mobileno'); 
        $inputs['email'] = $this->input->post('email');       
        $inputs['created_at'] = date('Y-m-d H:i:s');

        $cname = $this->db->select('category_type')->where('id',$inputs['category'])->get('categories')->row()->category_type;
        if($cname == '4'){
          $inputs['type'] = 3;
          $type=3;
        } else {
          $inputs['type'] = 1;
          $type=1;
        }
        
        if($type == 1){
          $url = 'service-providers';
        } else {
          $url = 'freelances-providers';
        }
        if ($this->db->insert('providers', $inputs, array("id" => $id))) { 
          $this->session->set_flashdata('success_message', 'Providers Updated successfully');
          redirect(base_url() . $url);
        } else {        
          $this->session->set_flashdata('error_message', 'Please try again later....');
          redirect(base_url() . $url);
        }
      } 
      
      $this->data['page'] = 'add_provider';
      $this->data['model'] = 'service';
      $this->data['title'] = 'Add Vendor';
      
      $this->data['country'] = $this->service->country_code_details();
      $this->data['category'] = $this->service->get_category(); 
          
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    } else {
      redirect(base_url()."admin");
    }

  }

  //Get Pending Services List
    public function pendingServiceList() {
        extract($_POST);
 
        if ($this->input->post('form_submit')) {  
            $service_title = $this->input->post('service_title');
            $provider = $this->input->post('providers');
            $users = $this->input->post('users');
            $from = $this->input->post('from');
            $to = $this->input->post('to');
            $this->data['list'] =$this->service->pending_service_list($service_title,$provider,$users,$from,$to);
        } else {
            $this->data['list'] = $this->service->pending_service_list();
        }
        $this->data['page'] = 'pending_service_list';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

  //Get Inactive Service List
    public function inactive_service_list()
    {
      $this->data['page'] = 'inactive_service_list';
      $this->data['list'] = $this->service->inactive_service_list();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

  //Inactive Service Detail View
    public function inactive_service_details($value='')
    {
      $this->data['page'] = 'inactive_service_details';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

  //Deleted Service List
    public function deleted_service_list()
    {
      $this->data['page'] = 'deleted_service_list';
      $this->data['list'] = $this->service->deleted_service_list();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }

  //Deleted Service Detail View
    public function deleted_service_details($value='')
    {
      $this->data['page'] = 'deleted_service_details';
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'].'/template');
    }


    public function add_service() {

    $this->common_model->checkAdminUserPermission(4);
    $this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
    $this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
    $this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
    $this->data['page'] = 'add_service';
    $this->data['map_key'] = settingValue('map_key');
    $this->data['provider_list'] = $this->service->provider_list();
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }

    public function add_service_ajax() {
      if ($this->input->post('form_submit')) {
      $inputs = array();
      removeTag($this->input->post());
     
      $config["upload_path"] = './uploads/services/';
      $config["allowed_types"] = '*';
      $this->load->library('upload', $config);
      $this->upload->initialize($config);

      $service_image = array();
      $thumb_image = array();
      $mobile_image = array();

      if ($_FILES["images"]["name"] != '') {
        if (!is_dir('uploads/services')) {
            mkdir('./uploads/services', 0777, TRUE);
        }
        for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
          $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
          $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
          $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
          $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
          $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
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
      }
      $inputs['user_id'] = $this->session->userdata('provider_id');
      $inputs['service_title'] = $this->input->post('service_title');
      $inputs['currency_code'] = $this->input->post('currency_code');;
      $inputs['service_sub_title'] = $this->input->post('service_sub_title');
      $inputs['category'] = $this->input->post('category');
      $inputs['subcategory'] = ($this->input->post('subcategory'))?$this->input->post('subcategory'):'';
      $inputs['service_location'] = $this->input->post('service_location');
      $inputs['service_latitude'] = $this->input->post('service_latitude');
      $inputs['service_longitude'] = $this->input->post('service_longitude');
      $inputs['service_amount'] = $this->input->post('service_amount');
      $inputs['about'] = $this->input->post('about');
      $inputs['service_image'] = ($service_image)?implode(',', $service_image):'';
      $inputs['service_details_image'] = ($service_details_image)?implode(',', $service_details_image):'';
      $inputs['thumb_image'] = ($thumb_image)?implode(',', $thumb_image):'';
      $inputs['mobile_image'] = ($mobile_image)?implode(',', $mobile_image):'';
      $inputs['created_at'] = date('Y-m-d H:i:s');
      $inputs['staff_id'] = ($this->input->post('staff_id'))?implode(',',$this->input->post('staff_id')):'';
      $inputs['shop_id'] = $this->input->post('shop_id');
      $inputs['updated_at'] = date('Y-m-d H:i:s');
      $inputs['duration'] = $this->input->post('duration');
      $inputs['duration_in'] = $this->input->post('duration_in');
      $inputs['created_by'] = 'admin';

      if($this->input->post('autoschedule')){
        $inputs['autoschedule'] = $this->input->post('autoschedule');
        $inputs['autoschedule_days']  = $this->input->post('autoschedule_days');
        $inputs['autoschedule_session']  = $this->input->post('autoschedule_session');
      } else {
        $inputs['autoschedule'] = 0;
        $inputs['autoschedule_days']  = 0;
        $inputs['autoschedule_session']  = 0;
      }
      $service_for = $this->input->post('service_for');
      if($service_for == 2){ // Status As Draft. This Service only for selected user
        $inputs['status']  = 3;
        $inputs['service_for']  = $service_for;
        $inputs['service_for_userid']  = $this->input->post('chatuserid');
      }
      $result = $this->service->create_service($inputs);
      $chatval = '';
      if($service_for == 2){
        $userid = $this->input->post('chatuserid');
        $user_token = $this->db->select('token')->from('users')-> where('id', $userid)->get()->row()->token;
        $provider_token = $this->db->select('token')->from('providers')->where('id', $inputs['user_id'])->get()->row()->token;
        date_default_timezone_set('UTC');
        $date_time = date('Y-m-d H:i:s');
        date_default_timezone_set('Asia/Kolkata');
        $content = "Requested New Service :- ". $inputs['service_title']."<br><br>";
        $url = base_url() . 'service-preview/' . str_replace(' ', '-', $inputs['service_title']) . '?sid=' . md5($result).'&uid='.md5($userid);
        $content .= "Click the link for service details <a href='".$url."' target='_blank'>".$inputs['service_title']."</a>";
        
        $chatdata=array(
          "sender_token"=>$provider_token,
          "receiver_token"=>$user_token,
          "message"=>$content,
          "status"=>1,
          "read_status"=>0,
          "utc_date_time"=>$date_time,
          "created_at"=>date('Y-m-d H:i:s'),
        ); 
        $chatval=$this->db->insert("chat_table",$chatdata);
      }

      if (is_countable($_POST['addi_servicename']) && count($_POST['addi_servicename']) > 0) {
        $post = $this->input->post();
        foreach ($post['addi_servicename'] as $key => $value) {               
          $addi_service_data = array(
                      'service_id' => $result,
                      'provider_id' => $this->session->userdata('id'),
                      'service_name' => $post['addi_servicename'][$key],
                      'amount' => $post['addi_serviceamnt'][$key],
                      'duration' => $post['addi_servicedura'][$key],
                      'created_at' => date('Y-m-d H:i:s')
          );
          $this->db->insert('additional_services', $addi_service_data);
        }
      }

      $temp = count($service_image); //counting number of row's
      $service_image = $service_image;
      $service_details_image = $service_details_image;
      $thumb_image = $thumb_image;
      $mobile_image = $mobile_image;
      $service_id = $result;

      for ($i = 0; $i < $temp; $i++) {
          $image = array(
              'service_id' => $service_id,
              'service_image' => $service_image[$i],
              'service_details_image' => $service_details_image[$i],
              'thumb_image' => $thumb_image[$i],
              'mobile_image' => $mobile_image[$i]
          );
          $serviceimage = $this->service->insert_serviceimage($image);
      }

      if ($serviceimage == true) {
      if($chatval){
        redirect(base_url() . "user-chat");
      } else {
        $this->session->set_flashdata('success_message', $this->ser_addmsg);
        redirect(base_url() . "service-list");
      }
      } else {
        $this->session->set_flashdata('error_message', $this->ser_adderrmsg);
        redirect(base_url() . "admin/add-service");
      }
    }
  }

  public function service_edit($id)
  {
    $this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
    $this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
    $this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
    $this->data['provider_list'] = $this->admin->provider_list();
    $this->data['services'] = $this->admin->edit_service_list($id);
    $this->data['serv_offered'] = $this->db->from('service_offered')->where('service_id', $id)->get()->result_array();
    $this->common_model->checkAdminUserPermission(4);
    $this->data['page'] = 'edit_service';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'].'/template');
  }

  public function update_service() {
    $description = $this->input->post('about');
        removeTag($this->input->post());
        $service_offered = json_encode($this->input->post('service_offered'));
        $inputs = array();

       $config["upload_path"] = './uploads/services/';
    $config["allowed_types"] = '*';
    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    $service_image = array();
    $service_details_image = array();
    $thumb_image = array();
    $mobile_image = array();
    
    if ($_FILES["images"]["name"] != '') {
        if(!is_dir('uploads/blogs')) {
            mkdir('./uploads/services/', 0777, TRUE);
        }
        for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
            $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
            $_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
            $_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
            $_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
            $_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
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
        for ($count = 0; $count < count($_FILES["images2"]["name"]); $count++) {
          $_FILES["file"]["name"] = 'full_' . time() . $_FILES["images2"]["name"][$count];
          $_FILES["file"]["type"] = $_FILES["images2"]["type"][$count];
          $_FILES["file"]["tmp_name"] = $_FILES["images2"]["tmp_name"][$count];
          $_FILES["file"]["error"] = $_FILES["images2"]["error"][$count];
          $_FILES["file"]["size"] = $_FILES["images2"]["size"][$count];
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
    }
        $country_exp = $this->db->select('id, country_name')->get_where('country_table', array('id'=>$this->input->post('country_id')))->row_array();
        $country_name = explode ("(", $country_exp['country_name']);
        $country_name = !empty($country_name[0]) ? $country_name[0] : '';

        $state_name = $this->db->select('id, name')->get_where('state', array('id'=>$this->input->post('state_id')))->row_array();
        $city_name = $this->db->select('id, name')->get_where('city', array('id'=>$this->input->post('city_id')))->row_array();

        $location = $country_name.','.$state_name['name'].','.$city_name['name'];
        $inputs['user_id'] = $_POST['username'];
        $inputs['service_image'] = implode(',', $service_image);
        $inputs['service_details_image'] = implode(',', $service_details_image);
        $inputs['thumb_image'] = implode(',', $thumb_image);
        $inputs['mobile_image'] = implode(',', $mobile_image);

        $inputs['service_title'] = $this->input->post('service_title_28');
        $inputs['service_sub_title'] = $this->input->post('service_sub_title');
        $inputs['category'] = $this->input->post('category');
        $inputs['subcategory'] = $this->input->post('subcategory');
        $inputs['service_location'] = ($this->input->post('service_location'))?$this->input->post('service_location'):$location;
        $inputs['service_latitude'] = ($this->input->post('service_latitude'))?$this->input->post('service_latitude'):'';
        $inputs['service_longitude'] = ($this->input->post('service_longitude'))?$this->input->post('service_longitude'):'';
        $inputs['service_amount'] = $this->input->post('service_amount');

        $inputs['about'] = $description;
        $inputs['service_offered'] = $service_offered;
        $inputs['currency_code'] = $this->input->post('currency_code');
        $inputs['service_country'] = ($country_exp['id'])?$country_exp['id']:'';
        $inputs['service_state'] = ($state_name['id'])?$state_name['id']:'';
        $inputs['service_city'] = ($city_name['id'])?$city_name['id']:'';


        $inputs['updated_at'] = date('Y-m-d H:i:s');
        $RemoveSpecialChar = $this->RemoveSpecialChar($this->input->post('service_title_28'));
        // $output = preg_replace('!\s+!', ' ', $RemoveSpecialChar);
        $output = preg_replace ('/[^\p{L}\p{N}]/u', ' ', $RemoveSpecialChar);
        $service_url = str_replace(" ","-",trim($output));
        $inputs['url'] = strtolower($service_url);
        $service_image = implode(',', $service_image);
        $service_details_image = implode(',', $service_details_image);
        $thumb_image = implode(',', $thumb_image);
        $mobile_image = implode(',', $mobile_image);
        $input_data = array(
            'user_id' => $_POST['username'],
            'service_image' => $service_image,
            'service_details_image' => $service_details_image,
            'thumb_image' => $thumb_image,
            'mobile_image' => $mobile_image,
            'service_title' => $this->input->post('service_title_28'),
            'service_sub_title' => $this->input->post('service_sub_title'),
            'currency_code' => $this->input->post('currency_code'),
            'category' => $this->input->post('category'),
            'subcategory' => $this->input->post('subcategory'),
            'service_location' => $this->input->post('service_location'),
            'service_latitude' => $this->input->post('service_latitude'),
            'service_longitude' => $this->input->post('service_longitude'),
            'service_amount' => $this->input->post('service_amount'),
            'service_offered' => $service_offered,
            'about' => $description,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_by' => 'admin',            
        );
        $where = array('id' => $_POST['service_id']);
        $id = $this->input->post('service_id'); 
        $this->db->set($inputs);
        $this->db->where($where);
        $result = $this->db->update('services');
        $this->service->update_service_name($id);

        if (!empty($_POST['service_offered']) && count($_POST['service_offered']) > 0) {
            $get_service = $this->db->where(array('service_id' => $_POST['service_id']))->count_all_results('service_offered');
            if($get_service > 0) {
                $offered_data = array('service_offered'=>$service_offered); 
                $this->db->set($offered_data);
                $this->db->where(array('service_id' => $_POST['service_id']));
                $this->db->update('service_offered');
            } else {
                $offered_data = array('service_offered'=>$service_offered, 'service_id' => $_POST['service_id']);
                $this->db->insert('service_offered', $offered_data);
            }
            /*$this->db->where('service_id', $this->input->post('service_id'))->delete('service_offered');
            foreach ($_POST['service_offered'] as $key => $value) {
                $service_data = array(
                    'service_id' => $this->input->post('service_id'),
                    'service_offered' => $value);
                $this->db->insert('service_offered', $service_data);
            }*/
        }else{
            $this->db->where('service_id', $this->input->post('service_id'))->delete('service_offered');
        }

        if (!empty($service_image)) {
            $temp = count(explode(',', $service_image));
            $service_image = explode(',', $service_image);
            $service_details_image = explode(',', $service_details_image);
            $thumb_image = explode(',', $thumb_image);
            $mobile_image = explode(',', $mobile_image);
            $service_id = $this->input->post('service_id');



            for ($i = 0; $i < $temp; $i++) {
                $image = array(
                    'service_id' => $service_id,
                    'service_image' => $service_image[$i],
                    'service_details_image' => $service_details_image[$i],
                    'thumb_image' => $thumb_image[$i],
                    'mobile_image' => $mobile_image[$i]
                );
                $serviceimage = $this->service->insert_serviceimage($image);
            }
        }
        if ($result) {
            $this->session->set_flashdata('success_message', 'Service Updated successfully');
            redirect(base_url() . "service-list");
        } else {
            $this->session->set_flashdata('error_message', 'Something Wents to Wrong...!');
            redirect(base_url() . "service-list");
        }
  }

  

}
