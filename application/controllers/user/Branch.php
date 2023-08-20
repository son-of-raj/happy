<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {

	public $data;

   public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme']     = 'user';
        $this->data['module']    = 'branch';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model','home');
		
		$this->load->model('branch_model','branch');

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
		$conditions['returnType'] = 'count'; 
        $totalRec = $this->branch->getActiveBranch($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['link_func']      = 'getData';
        $config['loading']='<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/branch/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        );

         $this->data['page'] = 'index';
	     $this->data['branchs']=$this->branch->getActiveBranch($conditions);
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
        $totalRec = $this->branch->getActiveBranch($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/branch/ajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['branchs'] = $this->branch->getActiveShops($conditions); 
        
        // Load the data list view 
        $this->load->view('user/branch/ajax_branch', $this->data, false); 
    }
	public function inactive_branch()
    { 
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $data = array(); 
          
        // Get record count 
        $conditions['returnType'] = 'count'; 

        $totalRec = $this->branch->getInactiveBranch($conditions); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/branch/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'limit' => $this->perPage 
        ); 
        $this->data['branchs'] = $this->branch->getInactiveBranch($conditions); 
        
        $this->data['page'] = 'my_branch_inactive';
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
        $totalRec = $this->branch->getInactiveBranch($conditions); 
         
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/branch/inactiveajaxPaginationData'); 
        $config['total_rows']  = $totalRec; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination->initialize($config); 
         
        // Get records 
        $conditions = array( 
            'start' => $offset, 
            'limit' => $this->perPage 
        ); 
        $this->data['branchs'] = $this->branch->getInactiveShops($conditions); 
        
        // Load the data list view 
        $this->load->view('user/branch/ajax_inactive_branch', $this->data, false); 
    }
	public function add_branch() {
        if (empty($this->session->userdata('id'))) {
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
           
            $config["upload_path"] = './uploads/branch/';
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
						$image_url = 'uploads/branch/' . $data["file_name"];
						$upload_url = 'uploads/branch/';
						$service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
						$thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
					}
				}
			}
            
			
			$user_id = $this->session->userdata('id');
           
            $inputs['provider_id'] = $this->session->userdata('id');
			$inputs['shop_id'] = $this->input->post('shopid');
			$inputs['branch_name'] = $this->input->post('branch_title');	
			$inputs['description'] = $this->input->post('about');	
			$inputs['country_code'] = $this->input->post('countryCode');
			$inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');			
			$inputs['branch_location'] = $this->input->post('service_location');
			$inputs['branch_latitude'] = $this->input->post('service_latitude');
			$inputs['branch_longitude'] = $this->input->post('service_longitude');
			
			$inputs['address'] = $this->input->post('address');			
            $inputs['country'] = $this->input->post('country_id');
            $inputs['state'] = $this->input->post('state_id');
            $inputs['city'] = $this->input->post('city_id');
            $inputs['postal_code'] = $this->input->post('pincode');   
				
			$inputs['status'] = 1;
			$inputs['created_by'] = $this->session->userdata('id');
			$inputs['created_at'] = date('Y-m-d H:i:s');
			$inputs['updated_at'] = date('Y-m-d H:i:s');
            
			
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
			
			
			$result = $this->branch->create_branch($inputs); 
			
		
		
			if (count($service_image) > 0) { 
				$temp = count($service_image);				
				for ($i = 0; $i < $temp; $i++) { 
					$image = array(
						'branch_id' => $result,						
						'branch_image' => $service_image[$i],
						'branch_details_image' => $service_details_image[$i],
						'thumb_image' => $thumb_image[$i],
						'mobile_image' => $mobile_image[$i]
					);				
					$branchpimage = $this->branch->insert_branchimage($image);
				
				}
			}
						
			if (!empty($result) && $result > 0) {	
				$post = $this->input->post(); 
				foreach ($post['name'] as $key => $value) {					
					$insert = array(
						'branch_id'        => $result,
						'provider_id'  	   => $user_id,	
						'shop_id' 		   => $this->input->post('shopid'),	
						'staff_id'  	   => implode(",", $post['selectstaffs'][$key]),
						'service_offer_id' => implode("", $post['serviceoffers'][$key]),
						'service_offer_name'  => implode("", $post['name'][$key]),
						'labour_charge'    => implode("", $post['price'][$key]),
						'duration'         => implode("", $post['duration'][$key]),
						'duration_in'	   => implode("", $post['duration_in'][$key]),
						'remarks'          => implode("", $post['desc'][$key]),
					); 
					$this->db->insert('branch_services_list',$insert); 
				}
				$this->session->set_flashdata('success_message', 'Branch created successfully');
				redirect(base_url() . "branch");
			} else {
                $this->session->set_flashdata('error_message', 'Branch created failed');
                redirect(base_url() . "branch");
            }			
        }
		
		$uid = $this->session->userdata('id');
        
        $this->data['country_list']=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
		$this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
		$this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
		$this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();
		
		$this->data['shop_lists']=$this->db->select('id,shop_name')->from('shops')->where('status', 1)->where('provider_id',$uid)->order_by('shop_name',"ASC")->get()->result_array();
		
		$this->data['branch_servicelist']=$this->db->where('status',1)->order_by('service_offered',"ASC")->get('shop_service_offered')->result_array();
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs();			
		if(!empty($assign)) {
			$this->data['branch_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->where_not_in('id', $assign)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array(); 
		} else {
			$this->data['branch_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->order_by('first_name',"ASC")->get('employee_basic_details')->result_array(); 
		}
		 
		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'add_branch';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
	public function readStaffs($branchid=''){
		$uid = $this->session->userdata('id');
		$condt = '';
		if($branchid != '') {
			$condt = " and `branch_id` != ".$branchid;
		}
		/* Read Staff from Shop & Branch Table */
		$sqlqry = "SELECT GROUP_CONCAT(DISTINCT `staff_id`) as staffs_assigned FROM `branch_services_list` WHERE `staff_id` != '' and `provider_id` = ".$uid." and `delete_status` = 0 ".$condt." UNION ALL  SELECT GROUP_CONCAT(DISTINCT `staff_id`) as staffs_assigned FROM `shop_services_list` WHERE `staff_id` != '' and `provider_id` = ".$uid." and `delete_status` = 0";
		$shpqry = $this->db->query($sqlqry); 		
		$shpres = $shpqry->result_array();	
		
		$assign = []; $inputs = ''; $a1='';$a2='';
		if($shpres[0]['staffs_assigned'] != '' && $shpres[0]['staffs_assigned'] != NULL){
			$a1 = $shpres[0]['staffs_assigned'];
		}
		if($shpres[1]['staffs_assigned'] != '' && $shpres[1]['staffs_assigned'] != NULL){
			$a2 = $shpres[1]['staffs_assigned'];
		}
		if(!empty($a1) && !empty($a2)){
			$inputs = $a1.",".$a2;
		} else if(!empty($a1) && empty($a2)){
			$inputs = $a1;
		} else if(empty($a1) && !empty($a2)){
			$inputs = $a2;
		} 
		if($inputs != ''){ 
			$assign = array_values(array_unique(explode(",", $inputs))); 
		}
		/* Read Staff from Shop & Branch Table */
		
		return $assign;
	}
	public function edit_branch() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }

        $branch_id = $this->uri->segment('2');
		$provider_id = $this->session->userdata('id');
        $this->data['page'] = 'edit_branch';
        $this->data['model'] = 'branch';
		$this->data['branch_id'] = $branch_id;
        $this->data['branch_details'] = $this->branch->get_single_branch($branch_id,$provider_id);  

		$this->data['country_list']=$this->db->where('status',1)->order_by('country_name',"ASC")->get('country_table')->result_array();
		$this->data['country']=$this->db->select('id,country_name')->from('country_table')->order_by('country_name','asc')->get()->result_array();
		$this->data['city']=$this->db->select('id,name')->from('city')->get()->result_array();
		$this->data['state']=$this->db->select('id,name')->from('state')->get()->result_array();	

		$uid = $this->session->userdata('id');
		
		$this->data['shop_lists']=$this->db->select('id,shop_name')->from('shops')->where('status', 1)->where('provider_id',$uid)->order_by('shop_name',"ASC")->get()->result_array();
		
		$this->data['branch_servicelist']=$this->db->where('status',1)->order_by('service_offered',"ASC")->get('shop_service_offered')->result_array();		
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs($branch_id);			
		if(!empty($assign)) {
			$this->data['branch_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->where_not_in('id', $assign)->order_by("first_name","ASC")->get('employee_basic_details')->result_array();
		} else {
			$this->data['branch_stafflist']=$this->db->select('id,first_name, designation')->where('status',1)->where('provider_id',$uid)->order_by("first_name","ASC")->get('employee_basic_details')->result_array();
		}
		
		$this->data['serv_lists'] = $this->db->where('delete_status',0)->where('branch_id',$branch_id)->where('provider_id',$provider_id)->get('branch_services_list')->result_array();
	
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
	public function update_branch() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		
        if ($this->input->post('form_submit')) { 
            $inputs = array();

            removeTag($this->input->post());
           
            $config["upload_path"] = './uploads/branch/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $service_image = array();
            $thumb_image = array();
            $mobile_image = array();
			if ($_FILES["images"]["name"] != '') {
				for ($count = 0; $count < count($_FILES["images"]["name"]); $count++) {
					$profile_count = $this->db->where('branch_id', $this->input->post('branch_id'))->from('branch_images')->count_all_results();
					if ($profile_count < 10) {
					$_FILES["file"]["name"] = 'full_' . time() . $_FILES["images"]["name"][$count];
					$_FILES["file"]["type"] = $_FILES["images"]["type"][$count];
					$_FILES["file"]["tmp_name"] = $_FILES["images"]["tmp_name"][$count];
					$_FILES["file"]["error"] = $_FILES["images"]["error"][$count];
					$_FILES["file"]["size"] = $_FILES["images"]["size"][$count];
					if ($this->upload->do_upload('file')) {
						$data = $this->upload->data();
						$image_url = 'uploads/branch/' . $data["file_name"];
						$upload_url = 'uploads/branch/';
						$service_image[] = $this->image_resize(360, 220, $image_url, 'se_' . $data["file_name"], $upload_url);
						$service_details_image[] = $this->image_resize(820, 440, $image_url, 'de_' . $data["file_name"], $upload_url);
						$thumb_image[] = $this->image_resize(60, 60, $image_url, 'th_' . $data["file_name"], $upload_url);
						$mobile_image[] = $this->image_resize(280, 160, $image_url, 'mo_' . $data["file_name"], $upload_url);
					}
					}
				}
			}
            
			
			$user_id = $this->session->userdata('id');
            $branch_id = $this->input->post('branch_id');
			
            $inputs['provider_id'] = $user_id;
			$inputs['shop_id']     = $this->input->post('shopid');
			$inputs['branch_name'] = $this->input->post('branch_title');	
			$inputs['description'] = $this->input->post('about');
			$inputs['country_code'] = $this->input->post('countryCode');
			$inputs['contact_no'] = $this->input->post('mobileno');
            $inputs['email'] = $this->input->post('email');			
			$inputs['branch_location'] = $this->input->post('service_location');
			$inputs['branch_latitude'] = $this->input->post('service_latitude');
			$inputs['branch_longitude'] = $this->input->post('service_longitude');	

			$inputs['address'] = $this->input->post('address');			
            $inputs['country'] = $this->input->post('country_id');
            $inputs['state'] = $this->input->post('state_id');
            $inputs['city'] = $this->input->post('city_id');
            $inputs['postal_code'] = $this->input->post('pincode');    

			$inputs['updated_at'] = date('Y-m-d H:i:s');
            			
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
			
			
			$result = $this->branch->update_branch($inputs,$branch_id,$user_id); 
			
			if (count($service_image) > 0) { 
				$temp = count($service_image);				
				for ($i = 0; $i < $temp; $i++) { 
					$image = array(
						'branch_id' => $branch_id,						
						'branch_image' => $service_image[$i],
						'branch_details_image' => $service_details_image[$i],
						'thumb_image' => $thumb_image[$i],
						'mobile_image' => $mobile_image[$i]
					);				
					$branchpimage = $this->branch->insert_branchimage($image);
				}
			}
			
			if (!empty($result) && $result > 0) {	
				$post = $this->input->post();
				$this->db->delete('branch_services_list', array('branch_id' => $branch_id, 'provider_id' => $user_id ));
				foreach ($post['name'] as $key => $value) {					
					$insert = array(
						'branch_id'        => $branch_id,
						'provider_id'  	   => $user_id,		
						'shop_id' 		   => $this->input->post('shopid'),
						'staff_id'  	   => implode(",", $post['selectstaffs'][$key]),
						'service_offer_id' => implode("", $post['serviceoffers'][$key]),
						'service_offer_name'  => implode("", $post['name'][$key]),
						'labour_charge'    => implode("", $post['price'][$key]),
						'duration'         => implode("", $post['duration'][$key]),
						'duration_in'	   => implode("", $post['duration_in'][$key]),
						'remarks'          => implode("", $post['desc'][$key]),
					); 
					$this->db->insert('branch_services_list',$insert);
					$sr_id = $this->db->insert_id();
					if($atype[1] == 'update'){					
						$table_data['id'] = $atype[0];
						$this->db->update('branch_services_list', $table_data, "id = " . $sr_id);
					}
				
				}				
				$this->session->set_flashdata('success_message', 'Shop Updated successfully');
				redirect(base_url() . "branch");
			} else {
                $this->session->set_flashdata('error_message', 'Shop Updated failed');
                redirect(base_url() . "branch");
            }			
        }
    }
	
	public function branch_preview() {

        if (isset($_GET['sid']) && !empty($_GET['sid'])) {
            extract($_GET);
           
            $s_id     = $_GET['sid']; 
			$user_id  = $this->session->userdata('id');
			$user_type = $this->session->userdata('usertype');
            $this->data['module'] = 'branch';
            $this->data['page'] = 'branch_preview';
			$this->data['model'] = 'service';
			
            $this->data['branch'] = $branch = $this->branch->get_branchinfo($s_id);
            $this->load->model('branch_model', 'branch');
            $this->data['branch_image'] = $this->branch->branch_image($branch['id']);
			
			$this->data['popular_branchs'] = $this->branch->popular_branchs();
         
            if (!empty($branch['id'])) {
                $this->views($this->data['branch']);
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
		$this->db->update('branch');        
    }   
	
	public function delete_inactive_branch() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '2';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('branch_id' => $s_id);
        $result = $this->branch->update_branch_status($inputs, $WHERE,$IMGWHERE);
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

    public function delete_branch() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '0';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('branch_id' => $s_id);
        $result = $this->branch->update_branch_status($inputs, $WHERE,$IMGWHERE);
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

    public function delete_active_branch() {
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
        $s_id = $this->input->post('s_id');

        $inputs['status'] = '1';
        $WHERE = array('id' => $s_id);
		$IMGWHERE = array('branch_id' => $s_id);
        $result = $this->branch->update_branch_status($inputs, $WHERE,$IMGWHERE);
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
	
	
	public function branch_check_emailid()
	{
	  $inputs = $_POST['userEmail'];		 
	  $this->db->like('email', $inputs, 'match');
	   if(!empty($_POST['sid'])) {
		  $this->db->where('id !=', $_POST['sid']);
	  }
      $count = $this->db->count_all_results('branch');
	  echo json_encode(['data' => $count ]);
	}
	public function branch_check_mobile()
	{
	  $inputs   = $_POST['userMobile'];	
	  $ctrycode = $_POST['mobileCode']; 	
	  
	  
	  $this->db->where(array('country_code'=>$ctrycode))->like('contact_no',$inputs,'match','after');
	  if(!empty($_POST['sid'])) {
		  $this->db->where('id !=', $_POST['sid']);
	  }
      $count = $this->db->count_all_results('branch');
	  echo json_encode(['data' => $count]);
	}
	public function get_service_staff($branch_id=''){
		$uid  = $this->session->userdata('id');
		$branch_id = $_POST['branch_id']; 
		
		/* Read Staff from Shop & Branch Table */		
		$assign = $this->readStaffs($branch_id);			
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
	
	
	
	
	
}
