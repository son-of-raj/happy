<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	public $data;

 	public function __construct() 
 	{
	  	parent::__construct();
	  	error_reporting(-1);
	  	$this->load->model('products_model','products_model');
	  	$this->load->model('common_model','common_model');
	  	$this->data['theme'] = 'admin';
	  	$this->data['model'] = 'products';
	  	$this->data['base_url'] = base_url();
	  	$this->session->keep_flashdata('error_message');
	  	$this->session->keep_flashdata('success_message');
	  	$this->perPage = 10;
	}

	public function product_categories()
	{
	  	$this->data['page'] = 'category_list';

	  	$where = array('status'=>0);
	  	$search = array();
	  	if ($this->input->post('form_submit')) 
	  	{ 
	  		$user_inp = $this->input->post();
	  		if (isset($user_inp['search']) && !empty($user_inp['search'])) {
	  			$search = $user_inp['search'];
	  		}
	  		if (isset($user_inp['from_date']) && !empty($user_inp['from_date'])) {
	  			$where["DATE_FORMAT(created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['from_date']));
	  		}
	  		if (isset($user_inp['to_date']) && !empty($user_inp['to_date'])) {
	  			$where["DATE_FORMAT(created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['to_date']));
	  		}
	  	}
	  	$this->data['list'] = $this->products_model->getsingletabledata('product_categories', $where, $search, 'id', 'desc', 'multiple');
	  	$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}

	public function manage_product_category($cat_id)
	{
		if ($this->input->post()) {
			$user_inp = $this->input->post();
			$cat_data = array('category_name'=>$user_inp['category_name'], 'status'=>0, 'updated_on'=>date('Y-m-d H:i:s'), 'slug'=>str_slug($user_inp['category_name']));
			//Image
			$uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['category_image']['name']) && !empty($_FILES['category_image']['name'])) 
            {
                if(!is_dir('uploads/products/category')) {
                	mkdir('./uploads/products/category', 0777, TRUE);
                }
                $uploaded_file_name = $_FILES['category_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/products/category/', 'category_image', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/products/category/' . $uploaded_file_name;
                        $cat_data['thumb_image'] = $this->image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name, 'uploads/products/category/');
                        $cat_data['category_image'] = $this->image_resize(381, 286, $image_url, $uploaded_file_name, 'uploads/products/category/');
                    }
                }
            }

			if ($cat_id == 0) 
			{
				$cat_data['created_on'] = date('Y-m-d H:i:s');
				$this->db->insert('product_categories', $cat_data);
				$ret_id = $this->db->insert_id();
	            if (!empty($ret_id)) {
	                $this->session->set_flashdata('success_message', 'Category added successfully');
	                redirect(base_url() . "product-categories");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "manage-product-category/".$cat_id);
	            }
			}
			else
			{
				$this->db->where('id', $cat_id);
				if ($this->db->update('product_categories', $cat_data)) {
	                $this->session->set_flashdata('success_message', 'Category updated successfully');
	                redirect(base_url() . "product-categories");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "product-categories");
	            }
			}
		}
		$this->data['page'] = 'manage_product_category';
		if ($cat_id == 0) 
		{
			$this->data['cat'] = array('category_name'=>'', 'thumb_image'=>'');
		}
		else
		{
			$this->data['cat'] = $this->products_model->getsingletabledata('product_categories', ['id'=>$cat_id], '', 'id', 'desc', 'single');
		}
		$this->data['cat_id'] = $cat_id;
		$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function delete_category()
	{
		$user_inp = $this->input->post();
		//delete cat
		$this->db->where('id', $user_inp['cat_id']);
		$this->db->update($user_inp['table'], ['status'=>1]);
		$result = array("error"=>false, "msg"=>'Success');
		echo json_encode($result);
	}
	public function product_subcategories()
	{
	  	$this->data['page'] = 'sub_category_list';

	  	$where = array('product_subcategories.status'=>0);
	  	$search = array();
	  	if ($this->input->post('form_submit')) 
	  	{ 
	  		$user_inp = $this->input->post();
	  		if (isset($user_inp['search']) && !empty($user_inp['search'])) 
	  		{
	  			$user_inp['search'] = replacehyphen($user_inp['search']);
	  			$search = $user_inp['search'];
	  		}
	  		if (isset($user_inp['from_date']) && !empty($user_inp['from_date'])) {
	  			$where["DATE_FORMAT(product_subcategories.created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['from_date']));
	  		}
	  		if (isset($user_inp['to_date']) && !empty($user_inp['to_date'])) {
	  			$where["DATE_FORMAT(product_subcategories.created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['to_date']));
	  		}
	  		if(isset($user_inp['where']) && !empty($user_inp['where']))
			{
				$user_inp['where'] = replacehyphen($user_inp['where']);
				$chkempty = RemoveEmpty($user_inp['where']);
				if(!empty($chkempty))
				{
					$where = array_merge($where,$chkempty);
				}
			}
	  	}

	  	$this->data['list'] = $this->products_model->sub_category_list($search, $where);
	  	$this->data['cat_list'] = $this->products_model->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'desc', 'multiple');
	  	$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function manage_product_subcategory($sub_cat_id)
	{
		if ($this->input->post()) {
			$user_inp = $this->input->post();
			$cat_data = array('category'=>$user_inp['category'], 'subcategory_name'=>$user_inp['subcategory_name'], 'status'=>0, 'updated_on'=>date('Y-m-d H:i:s'), 'slug'=>str_slug($user_inp['subcategory_name']));
			//Image
			$uploaded_file_name = '';
            if (isset($_FILES) && isset($_FILES['subcat_image']['name']) && !empty($_FILES['subcat_image']['name'])) 
            {
            	if(!is_dir('uploads/products/subcategory')) {
                	mkdir('./uploads/products/subcategory', 0777, TRUE);
                }
                $uploaded_file_name = $_FILES['subcat_image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/products/subcategory/', 'subcat_image', time() . $filename);
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];
                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/products/subcategory/' . $uploaded_file_name;
                        $cat_data['thumb_image'] = $this->image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name, 'uploads/products/subcategory/');
                        $cat_data['subcat_image'] = $this->image_resize(381, 286, $image_url, $uploaded_file_name, 'uploads/products/subcategory/');
                    }
                }
            }

			if ($sub_cat_id == 0) 
			{
				$cat_data['created_on'] = date('Y-m-d H:i:s');
				$this->db->insert('product_subcategories', $cat_data);
				$ret_id = $this->db->insert_id();
	            if (!empty($ret_id)) {
	                $this->session->set_flashdata('success_message', 'Category added successfully');
	                redirect(base_url() . "product-subcategories");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "manage-product-category/".$sub_cat_id);
	            }
			}
			else
			{
				$this->db->where('id', $sub_cat_id);
				if ($this->db->update('product_subcategories', $cat_data)) {
	                $this->session->set_flashdata('success_message', 'Category updated successfully');
	                redirect(base_url() . "product-subcategories");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "product-subcategories");
	            }
			}
		}
		$this->data['page'] = 'manage_product_subcategory';
		if ($sub_cat_id == 0) 
		{
			$this->data['cat'] = array('category'=>'','subcategory_name'=>'', 'thumb_image'=>'');
		}
		else
		{
			$this->data['cat'] = $this->products_model->getsingletabledata('product_subcategories', ['id'=>$sub_cat_id], '', 'id', 'desc', 'single');
		}
		$this->data['sub_cat_id'] = $sub_cat_id;
		$this->data['cat_list'] = $this->products_model->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'desc', 'multiple');
		$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function product_units()
	{
		$this->data['page'] = 'unit_list';

	  	$where = array('status'=>0);
	  	$search = array();
	  	if ($this->input->post('form_submit')) 
	  	{ 
	  		$user_inp = $this->input->post();
	  		if (isset($user_inp['search']) && !empty($user_inp['search'])) {
	  			$search = $user_inp['search'];
	  		}
	  		if (isset($user_inp['from_date']) && !empty($user_inp['from_date'])) {
	  			$where["DATE_FORMAT(created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['from_date']));
	  		}
	  		if (isset($user_inp['to_date']) && !empty($user_inp['to_date'])) {
	  			$where["DATE_FORMAT(created_on, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['to_date']));
	  		}
	  	}
	  	$this->data['list'] = $this->products_model->getsingletabledata('product_units', $where, $search, 'id', 'desc', 'multiple');
	  	$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function manage_product_unit($unit_id)
	{
		if ($this->input->post()) {
			$user_inp = $this->input->post();
			$cat_data = array('unit_name'=>$user_inp['unit_name'], 'status'=>0, 'updated_on'=>date('Y-m-d H:i:s'));
			if ($unit_id == 0) 
			{
				$cat_data['created_on'] = date('Y-m-d H:i:s');
				$this->db->insert('product_units', $cat_data);
				$ret_id = $this->db->insert_id();
	            if (!empty($ret_id)) {
	                $this->session->set_flashdata('success_message', 'Products Units updated successfully');
	                redirect(base_url() . "product_units");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "manage-product-unit/".$unit_id);
	            }
			}
			else
			{
				$this->db->where('id', $unit_id);
				if ($this->db->update('product_units', $cat_data)) {
	                $this->session->set_flashdata('success_message', 'Category updated successfully');
	                redirect(base_url() . "product_units");
	            } else {
	                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
	                redirect(base_url() . "product_units");
	            }
			}
		}
		$this->data['page'] = 'manage_product_unit';
		if ($unit_id == 0) 
		{
			$this->data['cat'] = array('unit_name'=>'');
		}
		else
		{
			$this->data['cat'] = $this->products_model->getsingletabledata('product_units', ['id'=>$unit_id], '', 'id', 'desc', 'single');
		}
		$this->data['unit_id'] = $unit_id;
		$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function admin_product_list()
	{
		$this->data['page'] = 'product_list';

	  	$where = array('products.status'=>0);
	  	$search = array();
	  	if ($this->input->post('form_submit')) 
	  	{ 
	  		$user_inp = $this->input->post();
	  		if (isset($user_inp['search']) && !empty($user_inp['search'])) 
	  		{
	  			$user_inp['search'] = replacehyphen($user_inp['search']);
	  			$search = $user_inp['search'];
	  		}

	  		if (isset($user_inp['from_date']) && !empty($user_inp['from_date'])) {
	  			$where["DATE_FORMAT(products.created_date, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['from_date']));
	  		}
	  		if (isset($user_inp['to_date']) && !empty($user_inp['to_date'])) {
	  			$where["DATE_FORMAT(products.created_date, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($user_inp['to_date']));
	  		}

	  		if(isset($user_inp['where']) && !empty($user_inp['where']))
			{
				$user_inp['where'] = replacehyphen($user_inp['where']);
				$chkempty = RemoveEmpty($user_inp['where']);
				if(!empty($chkempty))
				{
					$where = array_merge($where,$chkempty);
				}
			}
	  	}
	  	$this->data['list'] = $this->products_model->productlist('','', $search, $where, 'list');
	  	$this->data['cat_list'] = $this->products_model->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'desc', 'multiple');
	  	$this->data['sub_cat_list'] = $this->products_model->getsingletabledata('product_subcategories', ['status'=>0], '', 'id', 'desc', 'multiple');
	  	$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function get_subcategory()
	{
		$category_id = $this->input->post('category_id');
		$where = array('status'=>0);
		if ($category_id!='') {
			$where['category'] = $category_id;
		}
		$subcategory = $this->products_model->getsingletabledata('product_subcategories', $where, '', 'id', 'desc', 'multiple');
		echo json_encode($subcategory);
	}
	public function product_orders()
	{
		$this->load->library('ajax_pagination_new');
		$search = array();
		$where = array('product_cart.status'=>1); 
		$where_in = [];
        $totalRec = $this->products_model->orders_list('','',$search, $where, $where_in, 'count'); 
        // Pagination configuration 
        $config['target']      = '#mplist';
        $config['link_func']   = 'orders_list';
        $config['loading'] = '<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/products/ajaxorders'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 
	    $this->data['list']=$this->products_model->orders_list($this->perPage, 0, $search, $where, $where_in, 'list');
	    //count of each status
		// echo '<pre>'; print_r($this->data['list']);
		// exit;
	    $this->data['status_count']=$this->products_model->delivery_status_count(['product_cart.status'=>1], '');
		$this->data['page'] = 'product_orders';
		$this->load->vars($this->data);
	  	$this->load->view($this->data['theme'].'/template');
	}
	public function ajaxorders()
	{
		$this->load->library('ajax_pagination_new');
		$inp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        $where = array('product_cart.status'=>1);
        $where_in = [];
        
        if ($inp['status']=='6' || $inp['status']=='7') {
        	$where_in['column_name'] = 'product_cart.delivery_status';
        	$where_in['value'] = ['6','7'];
        }
        else
        {
        	if ($inp['status']!='all') {
	        	$where['product_cart.delivery_status'] = $inp['status'];
	        }
        }
        if (isset($inp['where'])) 
        {
        	if ($inp['where']['shop_name']!='') {
        		$search['shops.shop_name'] = $inp['where']['shop_name'];
        	}
        	if ($inp['where']['product_name']!='') {
        		$search['products.product_name'] = $inp['where']['product_name'];
        	}
        	if ($inp['where']['user_name']!='') {
        		$search['users.name'] = $inp['where']['user_name'];
        	}
        	if ($inp['where']['provider_name']!='') {
        		$search['providers.name'] = $inp['where']['provider_name'];
        	}
        	if ($inp['where']['from_date']!='') {
        		$where["DATE_FORMAT(product_cart.created_at, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($inp['where']['from_date']));
        	}
        	if ($inp['where']['to_date']!='') {
        		$where["DATE_FORMAT(product_cart.created_at, ('%Y-%m-%d ')) <="]=date("Y-m-d", strtotime($inp['where']['to_date']));
        	}
        	
        }
        $totalRec = $this->products_model->orders_list('','',$search, $where, $where_in, 'count'); 
        // Pagination configuration 
        $config['target']      = '#mplist'; 
        $config['base_url']    = base_url('admin/products/ajaxorders'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage;
        $config['cur_page'] = $offset;
        $config['link_func']   = 'orders_list';
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 
        $this->data['list'] = $this->products_model->orders_list($this->perPage, $offset, $search, $where, $where_in, 'list');
        $this->data['selected_status'] = $inp['status'];
        // Load the data list view 
        $this->load->view('admin/products/ajaxorders', $this->data, false);
	}
	public function status_count()
	{
		$inp = $this->input->post();
		$search = array();
		$where = array('product_cart.status'=>1);
		if (isset($inp['where'])) 
        {
        	if ($inp['where']['shop_name']!='') {
        		$search['shops.shop_name'] = $inp['where']['shop_name'];
        	}
        	if ($inp['where']['product_name']!='') {
        		$search['products.product_name'] = $inp['where']['product_name'];
        	}
        	if ($inp['where']['user_name']!='') {
        		$search['users.name'] = $inp['where']['user_name'];
        	}
        	if ($inp['where']['provider_name']!='') {
        		$search['providers.name'] = $inp['where']['provider_name'];
        	}
        	if ($inp['where']['from_date']!='') {
        		$where["DATE_FORMAT(product_cart.created_at, ('%Y-%m-%d ')) >="]=date("Y-m-d", strtotime($inp['where']['from_date']));
        	}
        	if ($inp['where']['to_date']!='') {
        		$where["DATE_FORMAT(product_cart.created_at, ('%Y-%m-%d ')) <="]=date("Y-m-d", strtotime($inp['where']['to_date']));
        	}
        }
		$status = $this->products_model->delivery_status_count($where, $search);
		echo json_encode($status);
	}
	public function order_refund($cart_id)
	{
		//cart details
		$cart = $this->products_model->product_cart_list(['product_cart.id'=>$cart_id, 'product_cart.cancel_reason !='=>'']);
		if (!empty($cart)) 
		{
			$this->data['page'] = 'order_refund';
			$this->data['product'] = $cart[0];
			$this->load->vars($this->data);
		  	$this->load->view($this->data['theme'].'/template');
		}
		else
		{
			redirect(base_url().'admin/product_orders');
		}
	}
	public function update_refund()
	{
		$this->load->model('templates_model');
		$inp = $this->input->post();
		//get cart details
		$cart = $this->products_model->product_cart_list(['product_cart.id'=>$inp['cart_id'], 'product_cart.cancel_reason !='=>'']);
		if ($cart[0]['delivery_status']==6) 
		{
			//pay to provider
			$this->load->helper('push_notifications');
			$msg = "Admin has declined the refund of amount(".$cart[0]['product_total']." ".$cart[0]['product_currency'].") of this product '".$cart[0]['product_name']."' on ".date('Y-m-d').". ".$inp['pay_comment'];
			$not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$cart[0]['provider_token'], 'message'=>$msg, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
			$this->db->insert('notification_table', $not_data);

			$this->db->where('id', $inp['cart_id']);
			$this->db->update('product_cart', ['admin_change_status'=>1, 'admin_comments'=>$inp['pay_comment']]);
			redirect(base_url('admin/product_orders'));
		}
		else
		{

			//pay to user
			//order
			$order = $this->products_model->getsingletabledata('product_order', ['id'=>$cart[0]['order_id']], '', 'id', 'desc', 'single');
			//with payment gateway
			$transid = $order['transaction_id'];
			$paidamt = $cart[0]['product_total'];

			$moyaser_option = settingValue('moyaser_option');
			if($moyaser_option == 1)
			{		
				$moyaser_apikey = settingValue('moyaser_apikey');
				$moyaser_secret_key = settingValue('moyaser_secret_key');
			}
			else if($moyaser_option == 2)
			{
				 $moyaser_apikey = settingValue('live_moyaser_apikey');
				 $moyaser_secret_key = settingValue('live_moyaser_secret_key');
			}
			$config['publishable_key'] = $moyaser_apikey;
			$config['secret_key'] = $moyaser_secret_key;
			$this->load->library('moyasar', $config);
			//fetch user details
			$user_card = $this->moyasar->moyasar_fetch($transid);
			$card = json_decode($user_card,true);
			$input['amount'] = $paidamt * 100;			
			$type = 2;
			$reasons = "Order Rejected by Provider and refunded by Admin.";
			$refund_info = $this->moyasar->moyasar_refund($transid, $input);
			$result_array = json_decode($refund_info,true);
			if(isset($result_array['status']) && $result_array['status'] == 'refunded')
			{
				$total_amount = $result_array['refunded'] / 100;
				$mdata = array('token'=>$this->session->userdata('chat_token'), 'currency_code'=>$result_array['currency'] ,'user_provider_id'=>$cart[0]['user_id'], 'type'=>2, 'amount'=>$paidamt, 'order_id'=>$cart[0]['order_id'], 'total_amount'=>$total_amount, 'reason'=>$reasons, 'transaction_id'=>$result_array['id'], 'response'=>$refund_info, 'created_at'=>date("Y-m-d H:i:s"), 'updated_on'=>date("Y-m-d H:i:s"), 'status'=>1);
				$this->db->insert('moyasar_table', $mdata);
				//update in cart
				$this->db->where('id', $inp['cart_id']);
				$this->db->update('product_cart', ['admin_change_status'=>1, 'admin_comments'=>$inp['pay_comment'], 'updated_at'=>date("Y-m-d H:i:s")]);
				$this->session->set_flashdata('success_message','Payment refunded successfully');
				$phpmail_config=settingValue('mail_config');
				if(isset($phpmail_config)&&!empty($phpmail_config))
				{
					if($phpmail_config=="phpmail")
					{
					  $from_email=settingValue('email_address');
					}
					else
					{
					  $from_email=settingValue('smtp_email_address');
					}
				}

				$this->data['service_amount']= $paidamt;
				$this->data['service_date']= date("d-m-Y", strtotime($order['created_on']));
				$this->data['service_title']= $cart[0]['product_name'];
				$this->data['comments'] = 'Amount Refunded';
				$bodyid = 5;
				$tempbody_details= $this->templates_model->get_usertemplate_data($bodyid);
				$body = $tempbody_details['template_content'];
				$body = str_replace('{user_name}', $cart[0]['user_name'], $body);
				$body = str_replace('{service_amount}', $this->data['service_amount']." ".$result_array['currency'], $body);
				$body = str_replace('{service_title}', "'".$this->data['service_title']."'", $body);
				$body = str_replace('{service_date}', $this->data['service_date'], $body);
				$body = str_replace('{admin_comments}', $this->data['comments'], $body);
				$body = str_replace('{sitetitle}',settingValue('website_name'), $body);
				$preview_link = base_url();
				$body = str_replace('{preview_link}',$preview_link, $body);
				$this->load->library('email');
				if(!empty($from_email)&&isset($from_email)){
					$mail = $this->email
					->from($from_email)
					->to($cart[0]['email'])
					->subject('Product Order Refund')
					->message($body)
					->send();
				}

				$this->load->helper('push_notifications');
				$msg = "Admin has refunded the amount(".$this->data['service_amount']." ".$result_array['currency'].") of this order '".$this->data['service_title']."' on ".date('Y-m-d').". ".$inp['pay_comment'];
				$not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$cart[0]['user_token'], 'message'=>$msg, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
				$this->db->insert('notification_table', $not_data);
				redirect(base_url('admin/product_orders'));
			}
			else
			{
				$this->session->set_flashdata('error_message',ucfirst($result_array['message']));
				redirect(base_url('admin/product_orders'));
			}
		}
	}
	public function image_resize($width = 0, $height = 0, $image_url, $filename, $folder_url) {

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

        $image_url = $folder_url . $filename_without_extension . "_" . $width . "_" . $height . "." . $extension;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }
}
