<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//Vendor 
require_once 'vendor/autoload.php';
require_once 'vendor/braintree/braintree_php/lib/Braintree.php';
require_once 'vendor/stripe/stripe-php/init.php';
//Vendor
class Products extends CI_Controller {

	public $data;

   public function __construct() {

        parent::__construct();
        error_reporting(0);
        $this->data['theme']     = 'user';
        $this->data['module']    = 'products';
        $this->data['page']     = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model','home');
		$this->load->model('products_model','products');
        $this->load->helper('user_timezone_helper');
        $this->currency= settings('currency');

        $this->load->library('ajax_pagination_new'); 
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

	
	public function my_products($shop_id)
	{
        if (empty($this->session->userdata('id'))) {
            redirect(base_url());
        }
		if ($this->session->userdata('usertype') == 'user') {
            redirect(base_url());
        }
		$search = array();
		$where = array('products.status'=>0, 'products.shop_id'=>$shop_id); 
        $totalRec = $this->products->my_products_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#mplist';
        $config['link_func']   = 'getData';
        $config['loading'] = '<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/products/ajaxmyproducts'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 

        $this->data['page'] = 'index';
	    $this->data['products']=$this->products->my_products_list($this->perPage, 0, $search, $where, 'list');
	    $this->data['shop_id'] = $shop_id;
	    $this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');
	}
	
	function ajaxmyproducts(){ 
        // Define offset 
        $shop_id = $this->input->post('shop_id');
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        $where = array('products.status'=>0, 'products.shop_id'=>$shop_id);
        // Get record count 
        $totalRec = $this->products->my_products_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#mplist'; 
        $config['base_url']    = base_url('user/products/ajaxmyproducts'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage;
        $config['cur_page'] = $offset;
        $config['link_func']   = 'getData'; 
        
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 
        $this->data['products']=$this->products->my_products_list($this->perPage, $offset, $search, $where, 'list');
        $this->data['shop_id'] = $shop_id;
        // Load the data list view 
        $this->load->view('user/products/ajaxmyproducts', $this->data, false); 
    }

    public function get_subcategory()
    {
    	$cat_id = $this->input->post('id');
    	$subcatlist = $this->products->sub_category_list('',['product_subcategories.status'=>0, 'product_subcategories.category'=>$cat_id]); 
    	echo json_encode($subcatlist);
    }
    public function add_product($shop_id)
    {
    	//get cat list
    	$this->data['catlist'] = $this->products->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'asc', 'multiple'); 
    	$this->data['unitlist'] = $this->products->getsingletabledata('product_units', ['status'=>0], '', 'id', 'asc', 'multiple'); 
    	$this->data['page'] = 'add_product';
    	$this->data['shop_id'] = $shop_id;
        $this->data['product_id'] = '';
        $this->data['product'] = $this->products->get_field_data('products');
        $this->data['product_images'] = [];
	    $this->load->vars($this->data);
		$this->load->view($this->data['theme'].'/template');

    }

    public function edit_product($shop_id, $product_id)
    {
        //get cat list
        $this->data['catlist'] = $this->products->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'asc', 'multiple'); 
        $this->data['unitlist'] = $this->products->getsingletabledata('product_units', ['status'=>0], '', 'id', 'asc', 'multiple');  

        $this->data['page'] = 'add_product';
        $this->data['shop_id'] = $shop_id;
        $this->data['product_id'] = $product_id;
        $this->data['product'] = $this->products->get_product_details(['id'=>$product_id]);
        //get sub cat list
        $this->data['sublist'] = $this->products->sub_category_list('',['product_subcategories.category'=>$this->data['product']['category'],'product_subcategories.status'=>0]);
        //get images
        $this->data['product_images'] = $this->products->get_product_images(['product_id'=>$product_id, 'status'=>0]);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function save_my_product()
    {
    	$user_inp = $this->input->post();
        //insert product
        $user_inp['products']['user_id'] = $this->session->userdata('id');
        $user_inp['products']['shop_id'] = $user_inp['shop_id'];
        $user_inp['products']['currency_code'] = $user_inp['currency_code'];
        $user_inp['products']['slug'] = str_slug($user_inp['products']['product_name']);
        if ($user_inp['hproduct_id']!='') 
        {
            //update
            $this->db->where('id', $user_inp['hproduct_id']);
            $this->db->update('products', $user_inp['products']);
            $product_id = $user_inp['hproduct_id'];
        }
        else
        {
            //insert
            $user_inp['products']['created_date'] = date('Y-m-d H:i:s');
            $ins = $this->db->insert('products', $user_inp['products']);
            $product_id = $this->db->insert_id();
        }

        

        $product_image = array();
        $thumb_image = array();

        if ($_FILES["images"]["name"] != '') 
        {
            if(!is_dir('uploads/products/product')) {
                mkdir('./uploads/products/product', 0777, TRUE);
            }
            $config["upload_path"] = './uploads/products/product/';
            $config["allowed_types"] = '*';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $img_data = [];
            $i=0;
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
                    $product_image = $this->image_resize(600, 600, $image_url, 'se_' . $data["file_name"], $upload_url);
                    $thumb_image = $this->image_resize(300, 300, $image_url, 'th_' . $data["file_name"], $upload_url);
                    $app_image = $this->image_resize(100, 100, $image_url, 'th_' . $data["file_name"], $upload_url);
                    $primary_img = 0;
                    if ($i==0) 
                    {
                        $primary_img = 1;
                    }
                    $img_data = ['product_id'=>$product_id, 'product_image'=>$product_image, 'thumb_image'=>$thumb_image, 'primary_img'=>$primary_img, 'app_image'=>$app_image, 'created_on'=>date('Y-m-d H:i:s')];
                }
                $i++;
            }
            //insert images
            if (!empty($img_data)) {
                $get_img = $this->db->get_where('product_images', array('product_id'=>$product_id))->result_array();
                if($get_img) {
                    $this->db->where('product_id', $product_id);
                    $this->db->update('product_images', $img_data);
                } else {
                    $this->db->insert('product_images', $img_data);
                }
                
            }
        }

        $this->session->set_flashdata('success_message', 'Add Product successfully');
        redirect(base_url() . 'my-products/'.$user_inp['shop_id']);
    }
    public function delete_product()
    {
        $sinp = $this->input->post();

        $this->db->where('id', $sinp['product_id']);
        $this->db->update('products', ['status'=>1]);
        echo "success";
    }
    public function productlist()
    {
        $search = array();
        
        if($this->session->userdata('usertype') == 'provider') {
            $where = array('products.status'=>0, 'products.user_id'=>$this->session->userdata('id'));
        } else {
            $where = array('products.status'=>0);
        }

        $totalRec = $this->products->productlist('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['link_func']   = 'searchFilter';
        $config['loading'] = '<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/products/ajaxproductlist'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 
        $this->data['page'] = 'productlist';
        $this->data['products'] = $this->products->productlist($this->perPage, 0, $search, $where, 'list');
        //get cat list
        $this->data['catlist'] = $this->products->getsingletabledata('product_categories', ['status'=>0], '', 'id', 'asc', 'multiple');
        $this->data['shop_id'] = $shop_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function ajaxproductlist()
    {
        // Define offset 
        $sinp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        if($this->session->userdata('usertype') == 'provider') {
            $where = array('products.status'=>0, 'products.user_id'=>$this->session->userdata('id'));
        } else {
            $where = array('products.status'=>0);
        }

        //cat filter
        if ($sinp['f_cat_id']!='all' && $sinp['f_cat_id']!='') {
            $where['products.category'] = $sinp['f_cat_id'];
        }
        if ($sinp['f_pricerange']!='any') 
        {
            //split range
            $range = explode("-", $sinp['f_pricerange']);
            $user_currency = $sinp['f_cc'];
            if ($range[0]!='') 
            {
                //$where['get_gigs_currency(products.sale_price, products.currency_code, "'.$user_currency.'") >='] = (float)$range[0];
                $where['products.sale_price >='] = (float)$range[0];
            }
            if ($range[1]!='') 
            {
                //$where['get_gigs_currency(products.sale_price, products.currency_code, "'.$user_currency.'") <='] = (float)$range[1];
                $where['products.sale_price <='] = (float)$range[1];
            }
        }
        // Get record count
        $totalRec = $this->products->productlist('','',$search, $where, 'count'); 
      
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    =  base_url('user/products/ajaxproductlist/'); 
        $config['total_rows']  =  $totalRec['cnt'];
        $config['cur_page'] = $offset; 
        $config['per_page']    =  $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 
         
        $this->data['products']=$this->products->productlist($this->perPage, $offset, $search, $where, 'list');
        $this->data['shop_id'] = $shop_id;
        // Load the data list view 
        $this->load->view('user/products/ajaxproductlist', $this->data, false);
    }
    public function addtocart()
    {
        $pid = $this->input->post('pid');
        $userId = $this->session->userdata('id');
        $user_currency = get_user_currency();
        //Product Det
        $product = $this->products->getsingletabledata('products', ['id'=>$pid], '', 'id', 'asc', 'single');
        $product_price = get_gigs_currency($product['sale_price'], $product['currency_code'], $user_currency['user_currency_code']);
        //cart data
        $cart_data = array('user_id'=>$userId, 'shop_id'=>$product['shop_id'], 'product_id'=>$pid, 'product_currency'=>$user_currency['user_currency_code'], 'product_price'=>$product_price);
        //check cart
        $cart = $this->products->getsingletabledata('product_cart', ['order_id'=>0, 'user_id'=>$userId, 'product_id'=>$pid, 'status'=>0], '', 'id', 'asc', 'single');
        if (!empty($cart) && !empty($userId)) 
        {
            $cart_data['qty'] = $cart['qty']+1;
            //update
            $this->db->where('id', $cart['id']);
            $this->db->update('product_cart', $cart_data);
        }
        else
        {
            $cart_data['qty'] = 1;
            $cart_data['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert('product_cart', $cart_data);
          
        }
        echo 'success';
    }
    public function product_buy_now()
    {
        $userId = $this->session->userdata('id');
        $user_inp = $this->input->post();

        $err = false;
        //get user currency
        $user = $this->products->getsingletabledata('users', ['id'=>$userId], '', 'id', 'asc', 'single');
        //Product Det
        $product = $this->products->getsingletabledata('products', ['id'=>$user_inp['product_id']], '', 'id', 'asc', 'single');
        //check cart
        $cart = $this->products->getsingletabledata('product_cart', ['user_id'=>$userId, 'product_id'=>$user_inp['product_id'], 'status'=>0], '', 'id', 'asc', 'single');
        //last cart
        $last_cart = $this->products->gettablelastdata('product_cart',['user_id'=>$userId, 'status'=>0], 'id');

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

        $cart_data = array('user_id'=>$userId, 'shop_id'=>$product['shop_id'], 'product_id'=>$user_inp['product_id'], 'product_currency'=>$product_currency, 'product_price'=>$product_price);
        if (!empty($cart)) 
        {
            $new_qty = $cart['qty']+1;
            if ($product['unit_value']>$new_qty) {
                $cart_data['qty'] = $new_qty;
                $cart_data['product_total'] = $cart['product_price']*$cart_data['qty'];
                //update
                $this->db->where('id', $cart['id']);
                $this->db->update('product_cart', $cart_data);
                $err = false;
            }
            else
            {
                $err = true;
            }
        }
        else
        {
            $cart_data['qty'] = 1;
            $cart_data['product_total'] = $product_price;
            $cart_data['created_at'] = date("Y-m-d H:i:s");
            $this->db->insert('product_cart', $cart_data);
            $err = false;
        }
        //create order
        //
        $cartlist = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0]);
        //check order
        $order = $this->products->getsingletabledata('product_order', ['user_id'=>$userId, 'status'=>0], '', 'id', 'asc', 'single');
        $order_data = array('user_id'=>$userId);
        if (!empty($cartlist)) 
        {
            $order_data['currency_code'] = $cartlist[0]['product_currency'];
            $order_data['total_products'] = count($cartlist);
            $order_data['total_qty'] = array_sum(array_map(function($item) { return $item['qty']; }, $cartlist));
            $order_data['total_amt'] = round(array_sum(array_map(function($item) { return $item['product_total']; }, $cartlist)),2);

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
                $last_ref_id = $this->products->getsingletabledata('mas_increment', $w_ref, '', 'id', 'asc', 'single');

                $new_cnt = $last_ref_id['auto_value']+1;
                $auto_value = sprintf("%07d", $new_cnt);
                $order_data['order_code'] = $last_ref_id['prefix'].$auto_value;
                
                $order_data['address_type'] = 'home';
                $order_data['created_on'] = date("Y-m-d H:i:s");
                $this->db->insert('product_order', $order_data);
              
                $order_id = $this->db->insert_id();
                //update code
                if(empty($last_ref_id)) {
                    $mas_increment = array(
                        'type_id' => 1,
                        'type_name' => 'order',
                        'prefix' => 'TO',
                        'auto_value' => $auto_value,
                        'created_on' => date('Y-m-d-H:i:s'),
                        'updated_on' => date('Y-m-d-H:i:s')
                    );
                    $this->db->insert('mas_increment', $mas_increment);
                } else {
                    $this->db->where($w_ref);
                    $this->db->update('mas_increment', ['auto_value'=>$new_cnt]);
                }
                
            }
            //update order id in cart
            $this->db->where(['user_id'=>$userId, 'status'=>0]);
            $this->db->update('product_cart', ['order_id'=>$order_id]);
            $result = array('err'=>$err, 'order_id'=>encrypt_url($order_id,$this->config->item('encryption_key')));
        }
        else
        {
            $result = array('err'=>true, 'order_id'=>'');
        }
        echo json_encode($result);
    }
    public function managecart()
    {
        $userId = $this->session->userdata('id');
        $user_inp = $this->input->post();
        
        if($user_inp['id_type'] == '+') {
            $add_rem_type = 'add';
        } elseif($user_inp['id_type'] == '') {
            $add_rem_type = 'add';
            $add_rem_type = 'add';
        } else {
            $add_rem_type = 'remove';
        }
        
        //get user currency
        $user = $this->products->getsingletabledata('users', ['id'=>$userId], '', 'id', 'asc', 'single');
        //Product Det
        $product = $this->products->getsingletabledata('products', ['id'=>$user_inp['product_id']], '', 'id', 'asc', 'single');
        //check qty
        if ($product['unit_value'] >= $user_inp['qty']) 
        {           
            if ($user_inp['cart_id']!='') 
            {
                //Cart Det
                $cart = $this->products->getsingletabledata('product_cart', ['id'=>$user_inp['cart_id']], '', 'id', 'asc', 'single');
                //update
                $product_total = $cart['product_price']*$user_inp['qty'];
                $this->db->where('id', $user_inp['cart_id']);
                $this->db->update('product_cart', ['qty'=>$user_inp['qty'], 'product_total'=>$product_total]);
            }
            else if ($user_inp['product_id']!='') 
            {
                //check cart
                $cart = $this->products->getsingletabledata('product_cart', ['user_id'=>$userId, 'product_id'=>$user_inp['product_id'], 'status'=>0], '', 'id', 'asc', 'single');
                //last cart
                $last_cart = $this->products->gettablelastdata('product_cart',['user_id'=>$userId, 'status'=>0], 'id');
                
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
                

                $cart_data = array('user_id'=>$userId, 'shop_id'=>$product['shop_id'], 'product_id'=>$user_inp['product_id'], 'product_currency'=>$product_currency, 'product_price'=>$product_price);

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
                    $cart_data['qty'] = 1;
                    $cart_data['product_total'] = $product_price;
                    $cart_data['created_at'] = date("Y-m-d H:i:s");
                    $this->db->insert('product_cart', $cart_data);
                }
            }
            
            $err['err'] = FALSE;
        }
        else
        {
            $err['err'] = TRUE;
        }
        //cart total
        $ctotal = $this->products->product_cart_total(['user_id'=>$userId, 'status'=>0]);
        $ctotal['product_currency'] = ($ctotal['product_currency'])?$ctotal['product_currency']:$user['currency_code'];
        //convert to user selecred currency
        $rtotal = get_gigs_currency($ctotal['total'], $ctotal['product_currency'], $user['currency_code']);
        $result = array('err'=>$err['err'], 'total'=>$rtotal, 'count'=>$ctotal['cart_count'], 'add_rem_type'=>$add_rem_type);
        
        echo json_encode($result);
    }
    public function getcartlist()
    {
        $userId = $this->session->userdata('id');  
        //get cart
        $total = $this->products->getCountRows('product_cart',['user_id'=>$userId, 'status'=>0], 'id', $search='', $whrIn = array());
        //cart list
        $cart = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0]);

        $result = array('totalcnt'=>$total['cnt'], 'list'=>$cart);
        echo json_encode($result);
    }
    public function my_cart_list()
    {
        $userId = $this->session->userdata('id');
        $this->data['page'] = 'my_cart_list';
        $this->data['cartlist'] = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0]);
        if (empty($this->data['cartlist'])) 
        {
            redirect(base_url().'products');
        }
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }

    public function createorder()
    {
        $userId = $this->session->userdata('id');
        //cart list
        $cart = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0]);
        //check order
        $order = $this->products->getsingletabledata('product_order', ['user_id'=>$userId, 'status'=>0], '', 'id', 'asc', 'single');
        $order_data = array('user_id'=>$userId);
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
                $last_ref_id = $this->products->getsingletabledata('mas_increment', $w_ref, '', 'id', 'asc', 'single');
                $new_cnt = $last_ref_id['auto_value']+1;
                $auto_value = sprintf("%07d", $new_cnt);
                $order_data['order_code'] = $last_ref_id['prefix'].$auto_value;
                $order_data['address_type'] = 'home';
                $order_data['created_on'] = date("Y-m-d H:i:s");
                $this->db->insert('product_order', $order_data);
                $order_id = $this->db->insert_id();
                //update code
                $this->db->where($w_ref);
                $this->db->update('mas_increment', ['auto_value'=>$auto_value]);
            }
            //update order id in cart
            $this->db->where(['user_id'=>$userId, 'status'=>0]);
            $this->db->update('product_cart', ['order_id'=>$order_id]);
            echo encrypt_url($order_id,$this->config->item('encryption_key'));
        }
    }
    public function delete_cart()
    {
        $hash_cart_id = $this->input->get('cart_id');
        $od = $this->input->get('od');
        $cart_id = decrypt_url($hash_cart_id,$this->config->item('encryption_key'));
        //remove from cart
        $this->products->delete_data('product_cart', ['id'=>$cart_id]);
        if ($od!='') {
            redirect(base_url().'checkout/'.$od, 'refresh');
        }
        else
        {
            redirect(base_url().'cart-list', 'refresh');
        }
        
    }
    public function my_checkout($url_v1)
    {
        $userId = $this->session->userdata('id');
        $order_id = decrypt_url($url_v1,$this->config->item('encryption_key'));
        $this->data['page'] = 'my_checkout';
        $this->data['cartlist'] = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0, 'product_cart.order_id'=>$order_id]);
        if (empty($this->data['cartlist'])) 
        {
            redirect(base_url().'products');
        }
        //checkout details
        $this->data['cout'] = $this->products->getsingletabledata('product_order', ['id'=>$order_id, 'status'=>0], '', 'id', 'asc', 'single');
        $this->data['country'] = $this->products->getsingletabledata('country_table', ['status'=>1], '', 'id', 'asc', 'multiple');
        //Billing details
        $this->data['billing'] = $this->products->user_billing_details(['user_billing_details.user_id'=>$userId, 'user_billing_details.status'=>0]);

        $this->data['moyaser_option'] = settingValue('moyaser_option');
        if($this->data['moyaser_option'] == 1)
        {       
            $this->data['moyaser_apikey'] = settingValue('moyaser_apikey');
            $this->data['moyaser_secret_key'] = settingValue('moyaser_secret_key');
        }
        else if($moyaser_option == 2)
        {
             $this->data['moyaser_apikey'] = settingValue('live_moyaser_apikey');
             $this->data['moyaser_secret_key'] = settingValue('live_moyaser_secret_key');
        }
        $this->data['razor_option']=settingValue('razor_option');
        if($this->data['razor_option'] == 1)
        {
            $this->data['razorpay_apikey']=settingValue('razorpay_apikey');
            $this->data['razorpay_apisecret']=settingValue('razorpay_apisecret');
        }
        else 
        {
            $this->data['razorpay_apikey']=settingValue('live_razorpay_apikey');
            $this->data['razorpay_apisecret']=settingValue('live_razorpay_secret_key');
        }

        $this->data['stripe_option']=settingValue('stripe_option');
        if($this->data['stripe_option'] == 1)
        {
            $this->data['stripe_apikey']=settingValue('publishable_key');
            $this->data['stripe_apisecret']=settingValue('stripe_apisecret');
        }
        else 
        {
            $this->data['stripe_apikey']=settingValue('live_publishable_key');
            $this->data['stripe_apisecret']=settingValue('live_stripe_secret_key');
        }

        $this->data['paypal_option']=settingValue('paypal_option');
        if($this->data['paypal_option'] == 1)
        {
            $this->data['paypal_apikey']=settingValue('paypal_apikey');
            $this->data['paypal_apisecret']=settingValue('paypal_apisecret');
        }
        else 
        {
            $this->data['paypal_apikey']=settingValue('live_paypal_apikey');
            $this->data['paypal_apisecret']=settingValue('live_paypal_secret_key');
        }
        $this->data['web_logo']= base_url().settingValue('logo_front');
        
        $this->data['url_v1'] = $url_v1;
        $this->data['order_id'] = $order_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function save_billing_details()
    {
        $userId = $this->session->userdata('id');
        $user_inp = $this->input->post();

        //billing data
        $bdata = array('user_id'=>$userId, 'full_name'=>$user_inp['full_name'], 'phone_no'=>$user_inp['phone_no'], 'email_id'=>$user_inp['email_id'], 'address'=>$user_inp['address'], 'country_id'=>$user_inp['country_id'], 'state_id'=>$user_inp['state_id'], 'city_id'=>$user_inp['city_id'], 'zipcode'=>$user_inp['zipcode'], 'address_type'=>$user_inp['address_type']);
        if ($user_inp['id']!='') 
        {
            //update
            $this->db->where('id', $user_inp['id']);
            $this->db->update('user_billing_details', $bdata);
        }
        else
        {
            $bdata['created_on'] = date("Y-m-d H:i:s");
            $this->db->insert('user_billing_details', $bdata);
        }
        //get ajax view
        $this->data['billing'] = $this->products->user_billing_details(['user_billing_details.user_id'=>$userId, 'user_billing_details.status'=>0]);
        $this->load->view('user/products/ajaxbilling', $this->data, false);
    }
    public function billingdetails()
    {
        $userId = $this->session->userdata('id');
        $billing_id = $this->input->post('billing_id');
        $type = $this->input->post('type');
        if($type == 'edit')
        {
            $billing = $this->products->user_billing_details(['user_billing_details.id'=>$billing_id]);
            if (!empty($billing)) 
            {
                $result = array('id'=>$billing[0]['id'], 'full_name'=>$billing[0]['full_name'], 'phone_no'=>$billing[0]['phone_no'], 'email_id'=>$billing[0]['email_id'], 'address'=>$billing[0]['address'], 'country_id'=>$billing[0]['country_id'], 'state_id'=>$billing[0]['state_id'], 'city_id'=>$billing[0]['city_id'], 'zipcode'=>$billing[0]['zipcode'], 'address_type'=>$billing[0]['address_type']);
            }
            else
            {
                $result = array('id'=>'', 'full_name'=>'', 'phone_no'=>'', 'email_id'=>'', 'address'=>'', 'country_id'=>'', 'state_id'=>'', 'city_id'=>'', 'zipcode'=>'', 'address_type'=>'');
            }
            echo json_encode($result);
        }
        else
        {
            //update
            $this->db->where('id', $billing_id);
            $this->db->update('user_billing_details', ['status'=>1]);

            //get ajax view
            $this->data['billing'] = $this->products->user_billing_details(['user_billing_details.user_id'=>$userId, 'user_billing_details.status'=>0]);
            $this->load->view('user/products/ajaxbilling', $this->data, false);
        }
    }
    public function updatecheckout()
    {
        $userId = $this->session->userdata('id');
        $user_inp = $this->input->post();
        //update checkout
        $this->db->where('id', $user_inp['order_id']);
        $this->db->update('product_order', ['billing_details_id'=>$user_inp['billing_id']]);
        //update default address
        $this->db->where('user_id', $userId);
        $this->db->update('user_billing_details', ['default_address'=>0]);
        //update default address
        $this->db->where('id', $user_inp['billing_id']);
        $this->db->update('user_billing_details', ['default_address'=>1]);
        echo "success";
    }
    public function order_payment($url_v1)
    {
        $userId = $this->session->userdata('id');
        $order_id = decrypt_url($url_v1,$this->config->item('encryption_key'));

        $this->data['page'] = 'order_payment';
        $this->data['cartlist'] = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.status'=>0, 'product_cart.order_id'=>$order_id]);
        //checkout details
        $this->data['cout'] = $this->products->getsingletabledata('product_order', ['id'=>$order_id, 'status'=>0], '', 'id', 'asc', 'single');
        //Billing details
        $this->data['billing'] = $this->products->user_billing_details(['user_billing_details.id'=>$this->data['cout']['billing_details_id'], 'user_billing_details.status'=>0]);

        $this->data['address'] = $this->products->user_billing_details(['user_billing_details.user_id'=>$userId, 'user_billing_details.status'=>0]);
        $this->data['moyaser_option'] = settingValue('moyaser_option');
        if($this->data['moyaser_option'] == 1)
        {       
            $this->data['moyaser_apikey'] = settingValue('moyaser_apikey');
            $this->data['moyaser_secret_key'] = settingValue('moyaser_secret_key');
        }
        else if($moyaser_option == 2)
        {
             $this->data['moyaser_apikey'] = settingValue('live_moyaser_apikey');
             $this->data['moyaser_secret_key'] = settingValue('live_moyaser_secret_key');
        }
        $this->data['razor_option']=settingValue('razor_option');
        if($this->data['razor_option'] == 1)
        {
            $this->data['razorpay_apikey']=settingValue('razorpay_apikey');
            $this->data['razorpay_apisecret']=settingValue('razorpay_apisecret');
        }
        else 
        {
            $this->data['razorpay_apikey']=settingValue('live_razorpay_apikey');
            $this->data['razorpay_apisecret']=settingValue('live_razorpay_secret_key');
        }

        $this->data['stripe_option']=settingValue('stripe_option');
        if($this->data['stripe_option'] == 1)
        {
            $this->data['stripe_apikey']=settingValue('publishable_key');
            $this->data['stripe_apisecret']=settingValue('stripe_apisecret');
        }
        else 
        {
            $this->data['stripe_apikey']=settingValue('live_publishable_key');
            $this->data['stripe_apisecret']=settingValue('live_stripe_secret_key');
        }

        $this->data['paypal_option']=settingValue('paypal_option');
        if($this->data['paypal_option'] == 1)
        {
            $this->data['paypal_apikey']=settingValue('paypal_apikey');
            $this->data['paypal_apisecret']=settingValue('paypal_apisecret');
        }
        else 
        {
            $this->data['paypal_apikey']=settingValue('live_paypal_apikey');
            $this->data['paypal_apisecret']=settingValue('live_paypal_secret_key');
        }
        $this->data['web_logo']= base_url().settingValue('logo_front');

        //wallet
        $wallet = $this->products->getsingletabledata('wallet_table', ['user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
        $wallet_amt = 0;
        $user_currency = get_user_currency();
        if (!empty($wallet)) 
        {
            $wallet_amt = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $user_currency['user_currency_code']);
        }
        $this->data['wallet_amt'] = $wallet_amt;
        
        $this->data['url_v1'] = $url_v1;
        $this->data['order_id'] = $order_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function make_payment()
    {
       
        $userId = $this->session->userdata('id');
        $user_currency = get_user_currency();
        $user_inp = $this->input->post();

        //checkout details
        $order = $this->products->getsingletabledata('product_order', ['id'=>$user_inp['order_id'], 'status'=>0], '', 'id', 'asc', 'single');
        //order details
        if ($user_inp['gateway'] == 'paypal') 
        {
            $card_number=str_replace("+","",$user_inp['card_number']);
            $card_name=$user_inp['card_name'];
            $expiry_month=$user_inp['expiry_month'];
            $expiry_year=$user_inp['expiry_year'];
            $cvv=$user_inp['cvv'];
            $expirationDate=$expiry_month.'/'.$expiry_year;

            $id = settingValue('paypal_option');
            $paypal = $this->products->getsingletabledata('paypal_payment_gateways', ['id'=>$id, 'status'=>0], '', 'id', 'asc', 'single');
            $merchantId = $paypal['braintree_merchant'];
            $publicKey = $paypal['braintree_publickey'];
            $privateKey = $paypal['braintree_privatekey'];

            $gateway = new Braintree\Gateway([
                'environment' => $paypal['gateway_type'],
                'merchantId' => $merchantId,
                'publicKey' => $publicKey,
                'privateKey' => $privateKey
            ]);
            //convert amount to USD
            $amount = get_gigs_currency($order['total_amt'], $order['currency_code'], 'USD');
            $result = $gateway->transaction()->sale([
                'amount' => $amount,
                'orderId' => $order['order_code'],
                'creditCard' => array(
                'number' => $card_number,
                'cardholderName' => $card_name,
                'expirationDate' => $expirationDate,
                'cvv' => $cvv
                ),
                'options' => [ 'submitForSettlement' => true]
            ]);
            if ($result->success) 
            {
                $transaction_id = $result->transaction->id;
                $message = 'Payment success';
                $return = array('error'=>false, 'transaction_id'=>$transaction_id, 'message'=>$message, 'hash_orderid'=>encrypt_url($user_inp['order_id'],$this->config->item('encryption_key')));
            }
            else
            {
                $message = $result->message;
                $transaction_id = '';
                $return = array('error'=>true, 'transaction_id'=>'', 'message'=>$message, 'hash_orderid'=>'');
            }
            //save order
            $this->order_success($user_inp['order_id'], 'paypal', $transaction_id, $message, $user_inp['address_type']);
            echo json_encode($return); exit;
        }
        else if($user_inp['gateway'] == 'stripe')
        {
            //billing
            $billing = $this->products->getsingletabledata('user_billing_details', ['id'=>$order['billing_details_id']], '', 'id', 'asc', 'single');
            //set api key
            $soption = settingValue('stripe_option');
            if ($soption == 1) 
            {
                $stripe_key = settingValue('secret_key');
            } 
            else 
            {
                $stripe_key = settingValue('secret_key');
            }
            //echo $stripe_key; exit;
            \Stripe\Stripe::setApiKey($stripe_key);
            $card_data = array('card_number'=>$user_inp['card_number'], 'expiry_month'=>$user_inp['expiry_month'], 'expiry_year'=>$user_inp['expiry_year'], 'cvv'=>$user_inp['cvv'], 'name'=>$user_inp['card_name'], 'email'=>$billing['email_id'], 'description'=>'Product Order');
            $token_id = $this->create_card($card_data);
            if ($token_id!='') 
            {
                //create customer
                $card_data['token_id'] = $token_id;
                $str_cus_id = $this->create_customer($card_data);
                if ($str_cus_id!='') 
                {
                    //got to charge
                    $amount = get_gigs_currency($order['total_amt'], $order['currency_code'], 'USD');
                    $charge_inp = array('amount'=>$amount, 'currency'=>"usd", 'customer'=>$str_cus_id, 'description'=>'Product Order for '.$order['order_code']);
                    $charge = $this->create_charge($charge_inp);
                    if ($charge['charge_id']!='') 
                    {
                        $return = array('error'=>false, 'transaction_id'=>$charge['charge_id'], 'message'=>$charge['seller_msg'], 'hash_orderid'=>encrypt_url($user_inp['order_id'],$this->config->item('encryption_key')));

                    }
                    else
                    {
                        $return = array('error'=>true, 'transaction_id'=>'', 'message'=>$charge['seller_msg'], 'hash_orderid'=>'');
                    }
                    //save order
                    $this->order_success($user_inp['order_id'], 'stripe', $charge['charge_id'], $charge['seller_msg'], $user_inp['address_type']);
                }
                else
                {
                    $return = array('error'=>true, 'transaction_id'=>'', 'message'=>"Invalid Card Details", 'hash_orderid'=>'');
                }
            }
            else
            {
                $return = array('error'=>true, 'transaction_id'=>'', 'message'=>"Invalid Card Details", 'hash_orderid'=>'');
            }
            
            echo json_encode($return); exit;
        }
        else if($user_inp['gateway'] == 'wallet')
        {
            //check order amount and wallet amount
            $order_amount = get_gigs_currency($order['total_amt'], $order['currency_code'], 'USD');
            $wallet = $this->products->getsingletabledata('wallet_table', ['user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
            $wallet_amt = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], 'USD');
            
            if ($wallet_amt >= $order_amount) 
            {
                //proceed
                //reduce in wallet table
                $bal_amt = $wallet_amt - $order_amount;
                //update in User currency format
                $upt_amt = get_gigs_currency($bal_amt, 'USD', $user_currency['user_currency_code']);
                $current_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $user_currency['user_currency_code']);
                $debit_wallet = get_gigs_currency($order['total_amt'], $order['currency_code'], $user_currency['user_currency_code']);
                $avail_wallet = $current_wallet - $debit_wallet;
                $this->db->where('id', $wallet['id']);
                $this->db->update('wallet_table', ['wallet_amt'=>$upt_amt, 'currency_code'=>$user_currency['user_currency_code']]);
                //insert into history
                $trans_data = array('token'=>$this->session->userdata('chat_token'), 'currency_code'=>$user_currency['user_currency_code'], 'user_provider_id'=>$userId, 'type'=>2, 'tokenid'=>$this->session->userdata('chat_token'), 'payment_detail'=>'product order', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$upt_amt, 'net_amt'=>$upt_amt, 'current_wallet'=>$current_wallet, 'debit_wallet'=>$debit_wallet, 'avail_wallet'=>$avail_wallet, 'reason'=>'Product Order', 'created_at'=>date('Y-m-d H:i:s'));
                $this->db->insert('wallet_transaction_history',$trans_data);
                $transaction_id = $order['order_code'];
                $msg = "success";
                $return = array('error'=>false, 'transaction_id'=>$transaction_id, 'message'=>'success', 'hash_orderid'=>encrypt_url($user_inp['order_id'],$this->config->item('encryption_key')));
                
            }
            else
            {
                $return = array('error'=>true, 'transaction_id'=>'', 'message'=>"Wallet amount is not sufficient", 'hash_orderid'=>'');
                $transaction_id = '';
                $msg = "Wallet amount is not sufficient";
            }
            //save order
            $this->order_success($user_inp['order_id'], 'wallet', $transaction_id, $msg, $user_inp['address_type']);
            echo json_encode($return); exit;
        }
        else
        {
            $this->order_success($user_inp['order_id'], 'cod', $order['order_code'], 'success', $user_inp['address_type']);
            echo json_encode($return); exit;
        }
    }
    public function create_card($inp) // create token for card in strip
    {
        //set api key
        $soption = settingValue('stripe_option');
        if ($soption == 1) 
        {
            $stripe_key = settingValue('secret_key');
        } 
        else 
        {
            $stripe_key = settingValue('secret_key');
        }
        \Stripe\Stripe::setApiKey($stripe_key);
        $token='';
        try 
        {
            $cardtoken=\Stripe\Token::create([
            'card' => [
                'number' => $inp['card_number'],
                'exp_month' => $inp['expiry_month'],
                'exp_year' => $inp['expiry_year'],
                'cvc' => $inp['cvv'],
                ],
            ]);
            $token=$cardtoken->id;  

        }
        catch(Exception $e) 
        {
            $error=$e->getMessage();
        }
        return $token;
    }  
    public function create_customer($inp) // create customer based on the token in strip
    {  
        $soption = settingValue('stripe_option');
        if ($soption == 1) 
        {
            $stripe_key = settingValue('secret_key');
        } 
        else 
        {
            $stripe_key = settingValue('secret_key');
        }
        \Stripe\Stripe::setApiKey($stripe_key);          
        $str_cus_id = '';
        try 
        {   
            $customer=\Stripe\Customer::create([
            'name' => $inp['name'],
            'email' => $user_det['email'],
            'source' => $inp['token_id'],
            'description' => $inp['description']
            ]);
            $str_cus_id=$customer->id;
        }
        catch(Exception $e) 
        {
            $error=$e->getMessage();
        }
        return $str_cus_id;
    }

    public function create_charge($inp) // create charge for bill in strip
    {
        $soption = settingValue('stripe_option');
        if ($soption == 1) 
        {
            $stripe_key = settingValue('secret_key');
        } 
        else 
        {
            $stripe_key = settingValue('secret_key');
        }
        \Stripe\Stripe::setApiKey($stripe_key);
        $charg_res=array();
        try 
        {
            $charge=\Stripe\PaymentIntent::create([
                'amount' => $inp['amount']*100,  // need to send the amount in cents
                'currency' => 'usd',
                'customer' => $inp['customer'],
                'payment_method_types' => ["card"],
                'description' => $inp['description'],
                'confirmation_method' => 'manual',
                'confirm' => true
            ]);    
            $charg_res['charge_id']=$charge->id;                
            $charg_res['seller_msg']=$charge->status;
            
        }
        catch(Exception $e) 
        {
            $error=$e->getMessage();   
            $charg_res['charge_id']='';             
            $charg_res['seller_msg']=$error;
        }
        return $charg_res;
    } 
    public function moyasar_redirect($order_id, $address_type)
    {
        if ($_GET['status'] == 'paid') 
        {
            $transaction_id = $_GET['id'];
        }
        else
        {
            $transaction_id = '';
        }
        $this->order_success($order_id, 'moyasar', $transaction_id, $_GET['message'], $address_type);
        if ($transaction_id!='') 
        {
            //redirect to order confirmation
            redirect(base_url().'order-confirmation/'.encrypt_url($order_id,$this->config->item('encryption_key')), 'refresh');
        }
        else
        {
            //redirect to order payment
            redirect(base_url().'order-payment/'.encrypt_url($order_id,$this->config->item('encryption_key')).'?failed=payment failed', 'refresh');
        }
        
    }
    public function razorpay_payment()
    {
        $user_inp = $this->input->post();
        $this->order_success($user_inp['order_id'], 'razorpay', $user_inp['razorpay_payment_id'], 'success', $user_inp['address_type']);
        echo json_encode('success');
    }
    public function stripe_order_payment()
    {
        $user_inp = $this->input->post();
        $userId = $this->session->userdata('id');

        $this->db->where('id', $user_inp['order_id']);
        $this->db->update('product_order', ['billing_details_id'=>$user_inp['billing_id']]);
        //update default address
        $this->db->where('user_id', $userId);
        $this->db->update('user_billing_details', ['default_address'=>0]);
        //update default address
        $this->db->where('id', $user_inp['billing_id']);
        $this->db->update('user_billing_details', ['default_address'=>1]);

        $this->order_success($user_inp['order_id'], 'stripe', $user_inp['token'], 'success', $user_inp['address_type']);
        echo json_encode('success');
    }
    public function paypal_order_payment()
    {
        $user_inp = $_GET;
        $order_id = $_GET['orderid'];
        $gateway = 'paypal';
        $transaction_id = $_GET['PayerID'];
        $message = 'success';
        $address_type = $_GET['address_type'];
        $billing_id = $_GET['billing_id'];
        $userId = $this->session->userdata('id');

        $this->db->where('id', $order_id);
        $this->db->update('product_order', ['billing_details_id'=>$billing_id]);
        //update default address
        $this->db->where('user_id', $userId);
        $this->db->update('user_billing_details', ['default_address'=>0]);
        //update default address
        $this->db->where('id', $billing_id);
        $this->db->update('user_billing_details', ['default_address'=>1]);

        //checkout details
        $order = $this->products->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        //update in product order
        $upt_order = ['payment_gway'=>$gateway, 'transaction_id'=>$transaction_id, 'payment_status'=>$message, 'address_type'=>$address_type];
        if ($gateway == 'wallet') {
           $upt_order['payment_type'] = 'wallet';
        } else if ($gateway == 'cod')  {
           $upt_order['payment_type'] = 'cod';
        } else {
            $upt_order['payment_type'] = 'card';
        }

        $upt_cart['status'] = 0;
        if ($transaction_id!='')  {
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
            $order = $this->products->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
            //check in moyasar table
            $moyasar = $this->products->getsingletabledata('moyasar_table', ['order_id'=>$order_id, 'user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
            $mdata = array('token'=>$this->session->userdata('chat_token'), 'currency_code'=>$order['currency_code'], 'user_provider_id'=>$userId, 'type'=>2, 'amount'=>$order['total_amt'], 'order_id'=>$order_id, 'total_amount'=>$order['total_amt'], 'transaction_id'=>$transaction_id, 'updated_on'=>date("Y-m-d H:i:s"));
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
        }
        if ($transaction_id!='') {
            $user_token = $this->session->userdata('chat_token');
            $user_name = $this->session->userdata('name');
            $cartlist = $this->products->product_cart_list(['product_cart.order_id'=>$order_id]);
            if (!empty($cartlist)) 
            {
                foreach ($cartlist as $val) 
                {
                    $provider = $this->products->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
                    $message = 'You have recieved an Order of '.$val['product_name'].' from '.$user_name;
                    $not_data = array('sender'=>$user_token, 'receiver'=>$provider['token'], 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
                    $this->db->insert('notification_table', $not_data);
                }
            }
            redirect(base_url().'order-confirmation/'.encrypt_url($order_id,$this->config->item('encryption_key')), 'refresh');
        }
        //echo json_encode('success');
    }
    public function order_success($order_id, $gateway, $transaction_id, $message, $address_type)
    {
        $userId = $this->session->userdata('id');
        //checkout details
        $order = $this->products->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        //update in product order
        $upt_order = ['payment_gway'=>$gateway, 'transaction_id'=>$transaction_id, 'payment_status'=>$message, 'address_type'=>$address_type];
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
            $this->order_moyasar($order_id, $transaction_id);
        }
        if ($transaction_id!='') {
            $this->order_success_notification($order_id);
        }
       
        return "success";
    }
    public function order_confirmation($url_v1)
    {
        $userId = $this->session->userdata('id');
        $order_id = decrypt_url($url_v1,$this->config->item('encryption_key'));

        //checkout details
        $this->data['order'] = $this->products->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        $this->data['cartlist'] = $this->products->product_cart_list(['product_cart.user_id'=>$userId, 'product_cart.order_id'=>$order_id]);
        //billing
        $this->data['billing'] = $this->products->user_billing_details(['user_billing_details.id'=>$this->data['order']['billing_details_id']]);
        $this->data['page'] = 'order_confirmation';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function order_moyasar($order_id, $transaction_id)
    {
        $userId = $this->session->userdata('id');
        $order = $this->products->getsingletabledata('product_order', ['id'=>$order_id], '', 'id', 'asc', 'single');
        //check in moyasar table
        $moyasar = $this->products->getsingletabledata('moyasar_table', ['order_id'=>$order_id, 'user_provider_id'=>$userId, 'type'=>2], '', 'id', 'asc', 'single');
        $mdata = array('token'=>$this->session->userdata('chat_token'), 'currency_code'=>$order['currency_code'], 'user_provider_id'=>$userId, 'type'=>2, 'amount'=>$order['total_amt'], 'order_id'=>$order_id, 'total_amount'=>$order['total_amt'], 'transaction_id'=>$transaction_id, 'updated_on'=>date("Y-m-d H:i:s"));
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
    public function order_submit($order_id)
    {
        $cartlist = $this->products->product_cart_list(['product_cart.order_id'=>$order_id]);
        if (!empty($cartlist)) 
        {
            foreach ($cartlist as $val) 
            {
                //get wallet of provider
                $provider = $this->products->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
                $wallet = $this->products->getsingletabledata('wallet_table', ['user_provider_id'=>$val['provider_id'], 'type'=>1], '', 'id', 'asc', 'single');
                $wallet_amt = 0;
                $wallet_currency = $provider['currency_code'];
                $current_wallet = 0;

                if (!empty($wallet)) 
                {
                    $wallet_amt = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $provider['currency_code']);
                    $wallet_currency = $wallet['currency_code'];
                    $current_wallet = get_gigs_currency($wallet_amt, $wallet_currency, $provider['currency_code']);
                }
                $credit_wallet = get_gigs_currency($val['product_total'], $val['product_currency'], $wallet_currency);
                $avail_wallet = $credit_wallet+$wallet_amt;
                //update in wallet
                if (!empty($wallet)) 
                {
                    $this->db->where('id', $wallet['id']);
                    $this->db->update('wallet_table', ['wallet_amt'=>$avail_wallet]);
                }
                else
                {
                    
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
    public function order_success_notification($order_id)
    {
        //Notification to provider
        //user token
        $user_token = $this->session->userdata('chat_token');
        $user_name = $this->session->userdata('name');
        $cartlist = $this->products->product_cart_list(['product_cart.order_id'=>$order_id]);
        if (!empty($cartlist)) 
        {
            foreach ($cartlist as $val) 
            {
                $provider = $this->products->getsingletabledata('providers', ['id'=>$val['provider_id']], '', 'id', 'asc', 'single');
                $message = 'You have recieved an Order of '.$val['product_name'].' from '.$user_name;
                $not_data = array('sender'=>$user_token, 'receiver'=>$provider['token'], 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
                $this->db->insert('notification_table', $not_data);
            }
        }

        return 'success';
    }
    public function view_product_details($url_v1)
    {
        $product_id = decrypt_url($url_v1,$this->config->item('encryption_key'));
        //product details
        $this->data['product'] = $this->products->view_product_details(['products.id'=>$product_id]);
        //product images
        $this->data['pimages'] = $this->products->getsingletabledata('product_images', ['product_id'=>$product_id, 'status'=>0], '', 'id', 'asc', 'multiple');
        $this->data['page'] = 'product_details';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function user_orders()
    {
        $this->perPage = 5;
        $userId = $this->session->userdata('id');
        $search = array();
        $where = array('product_cart.status'=>1, 'product_cart.user_id'=>$userId); 
        $totalRec = $this->products->user_orders_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 
        $config['base_url']    = base_url('user/products/ajaxuserorders');
        $config['total_rows']  = $totalRec['cnt'];
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'orderFilter';
        
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 

        $this->data['page'] = 'user_orders';
        $this->data['orders']=$this->products->user_orders_list($this->perPage, 0, $search, $where, 'list');
        $this->data['shop_id'] = $shop_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function ajaxuserorders()
    {
        $this->perPage = 5;
        $userId = $this->session->userdata('id');
        $sinp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        $where = array('product_cart.status'=>1, 'product_cart.user_id'=>$userId);
        if ($sinp['order_code']!='') 
        {
            $search['product_order.order_code'] = $sinp['order_code'];
        }
        if ($sinp['shop_name']!='') 
        {
            $search['shops.shop_name'] = $sinp['shop_name'];
        }
        if ($sinp['product_name']!='') 
        {
            $search['products.product_name'] = $sinp['product_name'];
        }
        if ($sinp['delivery_status']!='') 
        {
            $where['product_cart.delivery_status'] = $sinp['delivery_status'];
        }
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->products->user_orders_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['base_url']    =  base_url('user/products/ajaxuserorders');
        $config['total_rows']  =  $totalRec['cnt'];
        $config['per_page']    =  $this->perPage;
        $config['cur_page'] = $offset;
        $config['link_func']   = 'orderFilter';
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config);
        $this->data['orders']=$this->products->user_orders_list($this->perPage, $offset, $search, $where, 'list');
        $this->load->view('user/products/ajaxuserorders', $this->data, false);
    }
    public function checkordercancel()
    {
        $get_val = $this->input->get();
        if ($get_val['submit_type'] == 'check') 
        {
            $cart = $this->products->getsingletabledata('product_cart', ['id'=>$get_val['cart_id'], 'status'=>1], '', 'id', 'asc', 'single');
            $order = $this->products->getsingletabledata('product_order', ['id'=>$get_val['order_id']], '', 'id', 'asc', 'single');
            if ($order['cancelled_status'] == 0) {
                if ($cart['delivery_status'] == 1) 
                {
                    $result = array('error'=>false, 'msg'=>'can cancel');
                }
                else
                {
                    $result = array('error'=>true, 'msg'=>'Sorry you cannot cancel the order, cause the order is confirmed.');
                }
            }
            else
            {
                $result = array('error'=>true, 'msg'=>'Sorry you cannot cancel, Contact the admin');
            }
            
        }
        else
        {
            $inp = $this->input->post();
            //check the order is cod
            $order = $this->products->getsingletabledata('product_order', ['id'=>$inp['order_id'], 'status'=>1], '', 'id', 'asc', 'single');
            $clist = $this->products->product_cart_list(['product_cart.id'=>$inp['cart_id']]);
            $user = $this->products->getsingletabledata('users', ['id'=>$clist[0]['user_id']], '', 'id', 'asc', 'single');
            $provider = $this->products->getsingletabledata('providers', ['id'=>$clist[0]['provider_id']], '', 'id', 'asc', 'single');
            $sender = $this->session->userdata('chat_token');
            if ($this->session->userdata('usertype') == 'user') 
            {
               $receiver = $provider['token'];
               $message = $user['name'].' have cancelled the order of '.$clist[0]['product_name'];
               $delivery_status = 6;
            }
            else
            {
                $receiver = $user['token'];
                $message = $provider['name'].' have cancelled the order of '.$clist[0]['product_name'];
                $delivery_status = 7;
            }
            //insert notification
            $not_data = array('sender'=>$sender, 'receiver'=>$receiver, 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
            $this->db->insert('notification_table', $not_data);
            //update delivery status
            $this->db->where('id', $inp['cart_id']);
            $this->db->update('product_cart', ['delivery_status'=>$delivery_status, 'cancel_reason'=>$inp['cancel_reason']]);
            $this->db->where('id', $inp['order_id']);
            $this->db->update('product_order', ['cancelled_status'=>1]);
            $result = array('error'=>false, 'msg'=>'success');
        }
        echo json_encode($result);
    }
    public function order_cancel_wallet($cart_id)
    {
        $cart = $this->products->product_cart_list(['product_cart.id'=>$cart_id]);
        //Add amount to user
        $user_wallet = $this->products->getsingletabledata('wallet_table', ['user_provider_id'=>$cart[0]['user_id'], 'type'=>2], '', 'id', 'asc', 'single');
        $user = $this->products->getsingletabledata('users', ['id'=>$cart[0]['user_id']], '', 'id', 'asc', 'single');
        $uwallet_amt = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $user['currency_code']);
        $order_amt = get_gigs_currency($cart[0]['product_total'], $cart[0]['product_currency'], $user['currency_code']);
        $bal_amt = $uwallet_amt + $order_amt;
        $ucurrent_wallet = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $user['currency_code']);
        $ucredit_wallet = get_gigs_currency($cart[0]['product_total'], $cart[0]['product_currency'], $user['currency_code']);
        $uavail_wallet = $ucurrent_wallet+$ucredit_wallet;
        $this->db->where('id', $user_wallet['id']);
        $this->db->update('wallet_table', ['wallet_amt'=>$bal_amt, 'currency_code'=>$user['currency_code']]);
        // //trans history user
        $user_trans_data = array('token'=>$user['token'], 'currency_code'=>$user['currency_code'], 'user_provider_id'=>$cart[0]['user_id'], 'type'=>2, 'tokenid'=>$user['token'], 'payment_detail'=>'product order cancel', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$bal_amt, 'net_amt'=>$bal_amt, 'current_wallet'=>$current_wallet, 'credit_wallet'=>$ucredit_wallet, 'avail_wallet'=>$uavail_wallet, 'reason'=>'Order Cancelled', 'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert('wallet_transaction_history',$user_trans_data);
        //Provider Wallet reduce amount
        $provider_wallet = $this->products->getsingletabledata('wallet_table', ['user_provider_id'=>$cart[0]['provider_id'], 'type'=>1], '', 'id', 'asc', 'single');
        $provider = $this->products->getsingletabledata('providers', ['id'=>$cart[0]['provider_id']], '', 'id', 'asc', 'single');
        $pwallet_amt = get_gigs_currency($provider_wallet['wallet_amt'], $provider_wallet['currency_code'], $provider['currency_code']);
        $porder_amt = get_gigs_currency($cart[0]['product_total'], $cart[0]['product_currency'], $provider['currency_code']);

        $pbal_amt = $uwallet_amt + $porder_amt;
        $pcurrent_wallet = get_gigs_currency($user_wallet['wallet_amt'], $user_wallet['currency_code'], $provider['currency_code']);
        $pdebit_wallet = get_gigs_currency($cart[0]['product_total'], $cart[0]['product_currency'], $provider['currency_code']);
        $pavail_wallet = $pcurrent_wallet-$pdebit_wallet;
        $this->db->where('id', $provider_wallet['id']);
        $this->db->update('wallet_table', ['wallet_amt'=>$pbal_amt, 'currency_code'=>$provider['currency_code']]);
        //trans history user
        $pro_trans_data = array('token'=>$provider['token'], 'currency_code'=>$provider['currency_code'], 'user_provider_id'=>$cart[0]['provider_id'], 'type'=>1, 'tokenid'=>$provider['token'], 'payment_detail'=>'product order cancel', 'charge_id'=>1, 'paid_status'=>1, 'cust_id'=>'self', 'card_id'=>'self', 'total_amt'=>$pbal_amt, 'net_amt'=>$pbal_amt, 'current_wallet'=>$pcurrent_wallet, 'debit_wallet'=>$pdebit_wallet, 'avail_wallet'=>$pavail_wallet, 'reason'=>'Order Cancelled', 'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert('wallet_transaction_history',$pro_trans_data);
        return 'success';
    }
    public function provider_orders()
    {
        $this->perPage = 5;
        $provider_id = $this->session->userdata('id');
        $search = array();
        $where = array('product_cart.status'=>1, 'shops.provider_id'=>$provider_id); 
        $totalRec = $this->products->provider_orders_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList'; 

        $config['link_func']   = 'porderFilter';
        $config['use_page_numbers'] = TRUE;
        $config['loading'] = '<img src="'.base_url().'assets/img/loader.gif" alt="" />';
        $config['base_url']    = base_url('user/products/ajaxproviderorders'); 
        $config['total_rows']  = $totalRec['cnt']; 
        $config['per_page']    = $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config); 

        $this->data['page'] = 'provider_orders';
        $this->data['orders']=$this->products->provider_orders_list($this->perPage, 0, $search, $where, 'list');
        $this->data['pc'] = 0;
        $this->data['shop_id'] = $shop_id;
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
    }
    public function ajaxproviderorders()
    {
        $this->perPage = 5;
        $provider_id = $this->session->userdata('id');
        $sinp = $this->input->post();
        $page = $this->input->post('page'); 
        if(!$page){ 
            $offset = 0; 
        }else{ 
            $offset = $page; 
        }
        $search = array();
        $where = array('product_cart.status'=>1, 'shops.provider_id'=>$provider_id); 
        if ($sinp['order_code']!='') 
        {
            $search['product_order.order_code'] = $sinp['order_code'];
        }
        if ($sinp['user_name']!='') 
        {
            $search['users.name'] = $sinp['user_name'];
        }
        if ($sinp['product_name']!='') 
        {
            $search['products.product_name'] = $sinp['product_name'];
        }
        if ($sinp['delivery_status']!='') 
        {
            $where['product_cart.delivery_status'] = $sinp['delivery_status'];
        }
        $conditions['returnType'] = 'count'; 
        $totalRec = $this->products->provider_orders_list('','',$search, $where, 'count'); 
        // Pagination configuration 
        $config['target']      = '#dataList';
        $config['link_func']   = 'porderFilter';
        $config['base_url']    =  base_url('user/products/ajaxproviderorders'); 
        $config['total_rows']  =  $totalRec['cnt'];
        $config['cur_page'] = $offset;
        $config['per_page']    =  $this->perPage; 
         
        // Initialize pagination library 
        $this->ajax_pagination_new->initialize($config);
        $this->data['orders']=$this->products->provider_orders_list($this->perPage, $offset, $search, $where, 'list');
        $this->data['pc'] = $offset;
        $this->load->view('user/products/ajaxproviderorders', $this->data, false);
    }
    public function change_delivery_status()
    {
        $user_inp = $this->input->post();
        $next_status = $user_inp['cds']+1;
        //Notification
        $clist = $this->products->product_cart_list(['product_cart.id'=>$user_inp['cart_id']]);
        $user = $this->products->getsingletabledata('users', ['id'=>$clist[0]['user_id']], '', 'id', 'asc', 'single');
        if ($next_status == 2) //orde confirmed 
        {
            $message = 'Your order of '.$clist[0]['product_name'].' is confirmed by the shop '.$clist[0]['shop_name'];
            //product details
            $product = $this->products->getsingletabledata('products', ['id'=>$clist[0]['product_id']], '', 'id', 'asc', 'single');
            $unit_value = $product['unit_value']-$clist[0]['qty'];
            //update stock
            if ($unit_value > -1) 
            {
                $this->db->where('id', $clist[0]['product_id']);
                $this->db->update('products', ['unit_value'=>$unit_value]);
            } 
        }
        else if ($next_status == 3) //shipped
        {
            $message = 'Your order of '.$clist[0]['product_name'].' is shipped by the shop '.$clist[0]['shop_name'];
        }
        else if ($next_status == 4) //out for deivery
        {
            $message = 'Your order of '.$clist[0]['product_name'].' is now out for delivery from shop '.$clist[0]['shop_name'];
        }
        else
        {
            $message = 'Your order of '.$clist[0]['product_name'].' is delivered by the shop '.$clist[0]['shop_name'];
        }
        $not_data = array('sender'=>$this->session->userdata('chat_token'), 'receiver'=>$user['token'], 'message'=>$message, 'status'=>1, 'created_at'=>date("Y-m-d H:i:s"), 'utc_date_time' => utc_date_conversion(date('Y-m-d H:i:s')));
        $this->db->insert('notification_table', $not_data);
        //update
        $this->db->where('id', $user_inp['cart_id']);
        $this->db->update('product_cart', ['delivery_status'=>$next_status]);
        echo "success";
    }
    public function order_details()
    {
        $user_inp = $this->input->post();
        //order details
        $order = $this->products->getsingletabledata('product_order', ['id'=>$user_inp['order_id']], '', 'id', 'asc', 'single');
        //cart
        $address = $this->products->user_billing_details(['user_billing_details.id'=>$order['billing_details_id']]);
        $delivery_address = $address[0]['full_name'].',<br/>'.$address[0]['phone_no'].',<br/>'.$address[0]['address'].', <br/>'.$address[0]['city_name'].', '.$address[0]['state_name'].', <br/>'.$address[0]['country_name'].' - '.$address[0]['zipcode'];
        $result = array('order'=>$order, 'delivery_address'=>$delivery_address);
        echo json_encode($result);
    }
    public function image_resize($width = 0, $height = 0, $image_url, $filename, $folder_url) 
    {

        $source_path = $image_url;
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

    public function provider_order_details()
    {
        $user_inp = $this->input->post();
        //order details
        $order = $this->products->getsingletabledata('product_order', ['id'=>$user_inp['order_id']], '', 'id', 'asc', 'single');
        //cart
        $address = $this->products->user_billing_details(['user_billing_details.id'=>$order['billing_details_id']]);
        $delivery_address = $address[0]['full_name'].',<br/>'.$address[0]['phone_no'].',<br/>'.$address[0]['address'].', <br/>'.$address[0]['city_name'].', '.$address[0]['state_name'].', <br/>'.$address[0]['country_name'].' - '.$address[0]['zipcode'];

        //Get Product Id
        $productId = $this->db->get_where('product_cart', array('id'=>$user_inp['cart_id']))->row()->product_id;

        //Product Name
        $product_name = $this->db->get_where('products', array('id'=>$productId))->row()->product_name;

        //Product Image
        $product_img = $this->db->get_where('product_images', array('product_id'=>$productId))->row()->product_image;

        $result = array('order'=>$order, 'delivery_address'=>$delivery_address, 'product_name'=>$product_name, 'product_img'=> base_url().$product_img);

        echo json_encode($result);
    }
}
