<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Products_model extends CI_Model
{

	 function __construct() { 
        // Set table name 
        $this->table = 'branch'; 
    }
	function sub_category_list($search, $where)
	{
		$this->db->select('product_categories.category_name, product_subcategories.id, product_subcategories.category, product_subcategories.subcategory_name, product_subcategories.thumb_image, product_subcategories.status, product_subcategories.created_on');
		$this->db->join('product_categories','product_subcategories.category=product_categories.id','left');
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		$this->db->where($where);
		$query =  $this->db->get('product_subcategories')->result_array();
        // Return fetched data 
        return $query; 
	}
	function my_products_list($limit, $end, $search, $where, $type) 
	{
		if ($type == 'count') 
		{
			$this->db->select('COUNT(products.id) as cnt', false);
		}
		else
		{
			$this->db->select('products.id, products.user_id, products.shop_id, products.product_name, products.slug, products.unit_value, products.unit, products.price, products.sale_price, products.discount, products.short_description, products.currency_code, product_categories.category_name, product_subcategories.subcategory_name, product_images.product_image, product_units.unit_name');
		}
		$this->db->join('product_categories','products.category=product_categories.id','left');
		$this->db->join('product_subcategories','products.subcategory=product_subcategories.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','products.id=product_images.product_id and primary_img=1','left');
		$this->db->where($where);
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		if ($type == 'count')  
		{
			$query =  $this->db->get('products')->row_array(); 
		}
		else
		{
			if (isset($limit) && !empty($limit)) {
				$this->db->limit($limit,$end);
			}
			$this->db->order_by('products.id','DESC');
        	$query =  $this->db->get('products')->result_array();
		}
		return $query;
	}
	function get_product_details($where)
	{
		$this->db->select('id, user_id, shop_id, product_name, category, subcategory, unit_value, slug, unit, price, discount, sale_price, description, short_description, manufactured_by');
		$this->db->where($where);
		$query =  $this->db->get('products')->row_array(); 
        // Return fetched data 
        return $query;
	}

	function get_product_images($where)
	{
		$this->db->select('id, product_id, product_image, thumb_image, primary_img');
		$this->db->where($where);
		$query =  $this->db->get('product_images')->result_array(); 
        // Return fetched data 
        return $query;
	}
	function productlist($limit, $end, $search, $where, $type)
	{
		if ($type == 'count') 
		{
			$this->db->select('COUNT(products.id) as cnt', false);
		}
		else
		{
			$this->db->select('products.id, products.user_id, products.shop_id, products.product_name, products.slug, products.unit_value, products.unit, products.price, products.sale_price, products.discount, products.short_description, products.currency_code, products.created_date, product_categories.category_name, product_subcategories.subcategory_name, product_images.product_image, shops.shop_name');
		}
		$this->db->join('shops','products.shop_id=shops.id','left');
		$this->db->join('product_categories','products.category=product_categories.id','left');
		$this->db->join('product_subcategories','products.subcategory=product_subcategories.id','left');
		$this->db->join('product_images','products.id=product_images.product_id and primary_img=1','left');
		$this->db->where($where);
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		if ($type == 'count')  
		{
			$query =  $this->db->get('products')->row_array();
		}
		else
		{
			if (isset($limit) && !empty($limit)) {
				$this->db->limit($limit,$end);
			}
			$this->db->order_by('products.id','DESC');
        	$query =  $this->db->get('products')->result_array();
		}
		//echo $this->db->last_query(); 
		return $query;
	}
	function view_product_details($where_data)
	{
		$this->db->select('products.id, products.user_id, products.shop_id, products.product_name, products.slug, products.unit_value, products.unit, products.price, products.sale_price, products.discount, products.short_description, products.description, products.currency_code, product_categories.category_name, product_subcategories.subcategory_name, product_images.product_image, shops.shop_name');
		$this->db->join('shops','products.shop_id=shops.id','left');
		$this->db->join('product_categories','products.category=product_categories.id','left');
		$this->db->join('product_subcategories','products.subcategory=product_subcategories.id','left');
		$this->db->join('product_images','products.id=product_images.product_id and primary_img=1','left');
		$this->db->where($where_data);
		$query =  $this->db->get('products')->row_array();
		return $query;
	}
	function product_cart_list($where_data) 
	{
		$this->db->select('product_cart.id, product_cart.order_id, product_cart.user_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.status, product_cart.delivery_status, product_cart.admin_change_status, product_cart.cancel_reason, products.product_name, product_images.product_image, shops.provider_id, shops.shop_name, users.name as user_name, users.email, users.token as user_token, providers.token as provider_token');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->join('providers','shops.provider_id=providers.id','left');
		$this->db->where($where_data);
		$this->db->order_by('product_cart.id','DESC');
		$query =  $this->db->get('product_cart')->result_array();
		return $query;
	}
	function product_cart_total($where_data)
	{
		$this->db->select('SUM(product_total) as total, COUNT(id) as cart_count, product_currency', false);
		$this->db->where($where_data);
		$query =  $this->db->get('product_cart')->row_array();
		return $query;
	}
	function user_billing_details($where_data)
	{
		$this->db->select('user_billing_details.id, user_billing_details.user_id, user_billing_details.full_name, user_billing_details.phone_no, user_billing_details.email_id, user_billing_details.address, user_billing_details.country_id, user_billing_details.state_id, user_billing_details.city_id, country_table.country_name, city.name as city_name, state.name as state_name, user_billing_details.default_address, user_billing_details.zipcode,user_billing_details.address_type');
		$this->db->join('country_table','user_billing_details.country_id=country_table.id','left');
		$this->db->join('state','user_billing_details.state_id=state.id','left');
		$this->db->join('city','user_billing_details.city_id=city.id','left');
		$this->db->where($where_data);
		$this->db->order_by('user_billing_details.id','DESC');
		$query =  $this->db->get('user_billing_details')->result_array();
		return $query;
	}
	function user_orders_list($limit, $end, $search, $where, $type)
	{
		if ($type == 'count') 
		{
			$this->db->select('COUNT(product_cart.id) as cnt', false);
		}
		else
		{
			$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, product_cart.cancel_reason, product_cart.admin_change_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code');
		}
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->where($where);
		$this->db->order_by('product_cart.created_at','desc');

		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		if ($type == 'count')  
		{
			$query =  $this->db->get('product_cart')->row_array();
		}
		else
		{
			if (isset($limit) && !empty($limit)) {
				$this->db->limit($limit,$end);
			}
			$this->db->order_by('product_cart.id','desc');
        	$query =  $this->db->get('product_cart')->result_array();
		}
		return $query;
	}
	function provider_orders_list($limit, $end, $search, $where, $type)
	{
		if ($type == 'count') 
		{
			$this->db->select('COUNT(product_cart.id) as cnt', false);
		}
		else
		{
			$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, product_cart.cancel_reason, product_cart.admin_change_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno');
		}
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->where($where);
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		if ($type == 'count')  
		{
			$query =  $this->db->get('product_cart')->row_array();
		}
		else
		{
			if (isset($limit) && !empty($limit)) {
				$this->db->limit($limit,$end);
			}
			$this->db->order_by('product_cart.id','DESC');
        	$query =  $this->db->get('product_cart')->result_array();
		}
		return $query;
	}

	function orders_list($limit, $end, $search, $where, $where_in, $type)
	{
		if ($type == 'count') 
		{
			$this->db->select('COUNT(product_cart.id) as cnt', false);
		}
		else
		{
			$this->db->select('product_cart.id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, product_cart.cancel_reason, product_cart.admin_change_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.profile_img, users.mobileno, providers.name as provider_name, providers.mobileno as provider_mobile, providers.profile_img as provider_profile_img');
		}
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->join('providers','shops.provider_id=providers.id','left');
		$this->db->where($where);

		if (!empty($where_in) && !empty($where_in['column_name']) && !empty($where_in['value'])) {
			$this->db->where_in($where_in['column_name'],$where_in['value']);
		}
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		if ($type == 'count')  
		{
			$query =  $this->db->get('product_cart')->row_array();
		}
		else
		{
			/*if (isset($limit) && !empty($limit)) {
				$this->db->limit($limit,$end);
			}*/
			$query = '';
			//$this->db->order_by('product_cart.id','DESC');
			$details = $this->db->get('product_cart');
			if($details !== FALSE && $details->num_rows() > 0){
        		$query =  $details->result_array();
        	}
		}
		return $query;
	}
	function delivery_status_count($where, $search)
	{
		$this->db->select('COUNT(product_cart.id) as total, IFNULL(SUM(IF(product_cart.delivery_status=1, 1, 0)),0) AS placed, 
			IFNULL(SUM(IF(product_cart.delivery_status=2, 1, 0)),0) AS confirmed, IFNULL(SUM(IF(product_cart.delivery_status=3, 1, 0)),0) AS shipped, IFNULL(SUM(IF(product_cart.delivery_status=4, 1, 0)),0) AS out_delivery, IFNULL(SUM(IF(product_cart.delivery_status=5, 1, 0)),0) AS delivered, IFNULL(SUM(IF(product_cart.delivery_status=6 || product_cart.delivery_status=7, 1, 0)),0) AS cancelled', false);
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->join('providers','shops.provider_id=providers.id','left');
		$this->db->where($where);
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		$query = $this->db->get('product_cart')->row_array();
		return $query;
	}
	//Default Quries
	function getsingletabledata($table_name, $where_data, $search, $primary_id, $order_by,$type)
	{
		$this->db->select('*');
		$this->db->where($where_data);
		if (isset($search) && !empty($search)) 
		{
			foreach ($search as $key => $val) 
			{
				$this->db->like($key, $val);
			}
		}
		$this->db->order_by($primary_id,$order_by);
		if ($type == 'single') {
			$query =  $this->db->get($table_name)->row_array();
		}
		else
		{
			$query =  $this->db->get($table_name)->result_array();
		}
		return $query;
	}
	function get_field_data($table_name)
	{
		$result = $this->db->list_fields($table_name);
		$data=array();
		foreach($result as $field)
		{
			$data[$field] = '';
		}
		return $data;
	}
	function getCountRows($table_name,$where_data,$column_name, $search='', $whrIn = array()){
		$this->db->select('COUNT('.$column_name.') as cnt',false);
		$this->db->where($where_data);
		if(isset($whrIn) && !empty($whrIn)){
			if($whrIn['type'] == 'In'){
				$this->db->where_in($whrIn['column_name'],$whrIn['value']);
			}
			else{
				$this->db->where_not_in($whrIn['column_name'],$whrIn['value']);
			}
		}
		if(isset($search) && !empty($search))
		{
			foreach($search as $key=>$val)
			{
				if ($val!='') {
					$this->db->like($key,$val);
				}
			}
		}
		$query = $this->db->get($table_name);
		return $query->row_array();
	}
	function getlastid($table_name,$primary_id)
	{
		$this->db->select($primary_id);
		$this->db->order_by($primary_id,"desc");
		$this->db->limit(1);
		$query = $this->db->get($table_name)->row_array();
		if(empty($query))
		{
			$data=1;
		}
		else
		{
			$data=$query[$primary_id]+1;
		}
		return $data;
	}
	function delete_data($table_name,$where)
	{
		$this->db->where($where);
		$this->db->delete($table_name);
		return true;
	}
	function gettablelastdata($table_name,$where,$primary_id)
	{
		$this->db->select("*");
		$this->db->where($where);
		$this->db->order_by($primary_id,"desc");
		$this->db->limit(1);
		$query = $this->db->get($table_name)->row_array();
		return $query;
	}
}
?>
