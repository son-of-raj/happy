<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}
  public function is_valid_login($username,$password)
  {
    $password = md5($password);
    $this->db->select('user_id, profile_img,token,role');
    $this->db->from('administrators');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		//$this->db->where_in('role',[1,2]);
	  $result = $this->db->get()->row_array();
    return $result;
  }
  
  public function update_data($table, $data, $where = []) {
        if (count($where) > 0) {
            $this->db->where($where);
            $status = $this->db->update($table, $data);
            return $status;
        } else {
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }
    }
	public function get_cod()
	{
		$this->db->select('bs.id,s.service_title,p.name as providername,u.name as username,cod.amount,cod.status, cod.amount_to_pay');
		$this->db->from('book_service_cod AS cod');
		$this->db->join('book_service AS bs', 'bs.id = cod.book_id', 'left');
		$this->db->join('users AS u', 'u.id = bs.user_id', 'left');
		$this->db->join('providers AS p', 'p.id = bs.provider_id', 'left');
		$this->db->join('services AS s', 's.id = bs.service_id', 'left');
		$this->db->order_by('bs.id', 'DESC');
		$result = $this->db->get()->result_array();
		return $result;		
	}
 
   public function getSingleData($table,$where=array()) {

        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $result = $query->row();
        return $result;
    }

    public function admin_details($user_id)
	{
		$results = array();
		$results = $this->db->get_where('administrators',array('user_id'=>$user_id))->row_array();
		return $results;
	}
	public function GetBannerDet()
	{
		$results = array();
		$results = $this->db->get_where('bgimage',array('bgimg_id !='=>0))->result_array();
		return $results;
	}
	public function GetBannerDetId($id)
	{
		$results = array();
		$results = $this->db->get_where('bgimage',array('bgimg_id'=>$id))->row_array();
		return $results;
	}

	public function update_profile($data)
	  {
			$user_id = $this->session->userdata('admin_id');
	    $results = $this->db->update('administrators', $data, array('user_id'=>$user_id));
	    return $results;
	  }

		public function change_password($user_id,$confirm_password,$current_password)
		{

	        $current_password = md5($current_password);
	        $this->db->where('user_id', $user_id);
	        $this->db->where(array('password'=>$current_password));
	        $record = $this->db->count_all_results('administrators');

	        if($record > 0){

	          $confirm_password = md5($confirm_password);
	          $this->db->where('user_id', $user_id);
	          return $this->db->update('administrators',array('password'=>$confirm_password));
	        }else{
	          return 2;
	        }
		}

		public function get_setting_list() {
        $data = array();
        $stmt = "SELECT a.*"
                . " FROM system_settings AS a"
                . " ORDER BY a.`id` ASC";
        $query = $this->db->query($stmt);
        if ($query->num_rows()) {
            $data = $query->result_array();
        }
        return $data;
    }

    public function edit_payment_gateway($id)
    {
        $query = $this->db->query(" SELECT * FROM `payment_gateways` WHERE `id` = $id ");
        $result = $query->row_array();
        return $result;
    }
	
	public function edit_razor_payment_gateway($id)
    {
        $query = $this->db->query(" SELECT * FROM `razorpay_gateway` WHERE `id` = $id ");
        $result = $query->row_array();
        return $result;
    }
	
	public function edit_paypal_payment_gateway($id)
    {
        $query = $this->db->query(" SELECT * FROM `paypal_payment_gateways` WHERE `id` = $id ");
        $result = $query->row_array();
        return $result;
    }
	
	public function edit_paytab_payment_gateway()
    {
        $query = $this->db->query(" SELECT * FROM `paytabs_details`");
        $result = $query->row_array();
        return $result;
    }
	public function edit_moyaser_payment_gateway($id)
    {
        $query = $this->db->query(" SELECT * FROM `moyaser_payment_gateway`  WHERE `id` = $id ");
        $result = $query->row_array();
        return $result;
    }
	
	
	

     public function all_payment_gateway()
    {
      $this->db->select('*');
        $this->db->from('payment_gateways');
        $query = $this->db->get();
        return $query->result_array();         
    }

        public function categories_list()
		{
			$query = $this->db->query(" SELECT * FROM `categories` WHERE `status` = 1 ")->result_array();
			return $query;
		}

		public function categories_list_filter($category,$from_date,$to_date){

			        if(!empty($from_date)) {
					$from_date=date("Y-m-d", strtotime($from_date));
					}else{
					$from_date='';
					}
					if(!empty($to_date)) {
					$to_date=date("Y-m-d", strtotime($to_date));
					}else{
					$to_date='';
					}
					$this->db->select('*');
					$this->db->from('categories');
					if(!empty($from_date)){
						$this->db->where('date(created_at) >=',$from_date);
					}
					if(!empty($to_date)){
						$this->db->where('date(created_at) <=',$to_date);
					}
					if(!empty($category)){
					$this->db->where('id',$category);
					}
					$this->db->where('status',1);
					return $this->db->get()->result_array();

		}

		/*subcategory filter*/
		public function subcategory_filter($category,$subcategory,$from,$to){
				
					if(!empty($from)) {
					$from_date=date("Y-m-d", strtotime($from));
					}else{
					$from_date='';
					}
					if(!empty($to)) {
					$to_date=date("Y-m-d", strtotime($to));
					}else{
					$to_date='';
					}

			        $this->db->select('s.*,c.category_name');
					$this->db->from('subcategories s');
					$this->db->join('categories c', 'c.id = s.category', 'left');
					if(!empty($from_date)){
						$this->db->where('date(s.created_at) >=',$from_date);
					}
					if(!empty($to_date)){
						$this->db->where('date(s.created_at) <=',$to_date);
					}
					if(!empty($category)){
						$this->db->where('s.category',$category);
					}
					if(!empty($subcategory)){
						$this->db->where('s.id',$subcategory);
					}
					$this->db->where('s.status',1);
					return $this->db->get()->result_array();

		}

		public function subcategories_list()
		{
					$this->db->select('s.*,c.category_name');
					$this->db->from('subcategories s');
					$this->db->join('categories c', 'c.id = s.category', 'left');
					$this->db->where('s.status',1);
			return $this->db->get()->result_array();
		}
		public function search_catsuball($category,$subcategory)
		{
			$this->db->select('s.*,c.category_name');
			$this->db->from('subcategories s');
			$this->db->join('categories c', 'c.id = s.category', 'left');
			return $this->db->where(array('s.id'=>$category,'c.id'=>$subcategory,'s.status'=>1))->get()->result_array();
		}

		public function search_subcategory($subcategory)
		{
			$this->db->select('s.*,c.category_name');
			$this->db->from('subcategories s');
			$this->db->join('categories c', 'c.id = s.category', 'left');
			return $this->db->where(array('c.id'=>$subcategory,'s.status'=>1))->get()->result_array();
		}

		public function search_category($category)
		{
			$this->db->select('s.*,c.category_name');
			$this->db->from('subcategories s');
			$this->db->join('categories c', 'c.id = s.category', 'left');
			return $this->db->where(array('c.id'=>$category,'s.status'=>1))->get()->result_array();
		}
		
        public function categories_details($id)
		{
			return $this->db->get_where('categories',array('id'=>$id))->row_array();
		}

		public function subcategories_details($id)
		{
			return $this->db->get_where('subcategories',array('id'=>$id))->row_array();
		}
                
                public function language_list()
		{
			return $this->db->get('language')->result_array();
		}
		public function revenue_details($revenue_id) {
			$this->db->select('revenue.date, revenue.amount, revenue.currency_code, revenue.commission, revenue.vat, revenue.offersid, revenue.couponid, revenue.rewardid, revenue.service_id, revenue.booking_id, revenue.created_at, users.name as user_name, providers.name as provider_name, user_address.address as uaddress, user_address.pincode as upincode, uc.name as ucity, us.name ustate, provider_address.address as paddress, provider_address.pincode as ppincode, pc.name as pcity, ps.name pstate');
			$this->db->join('users', 'revenue.user = users.id', 'left');
			$this->db->join('user_address', 'revenue.user = user_address.user_id', 'left');
			$this->db->join('city uc', 'user_address.city_id = uc.id', 'left');
			$this->db->join('state us', 'user_address.state_id = us.id', 'left');
			$this->db->join('providers', 'revenue.provider = providers.id', 'left');
			$this->db->join('provider_address', 'revenue.provider = provider_address.provider_id', 'left');
			$this->db->join('city pc', 'provider_address.city_id = pc.id', 'left');
			$this->db->join('state ps', 'provider_address.state_id = ps.id', 'left');
			$this->db->where(['revenue.id'=>$revenue_id]);
			$query = $this->db->get('revenue')->row_array();
			return $query;
		}
        public function Revenue_list($provider='', $date='') {
            if($provider != '') {
         		$this->db->where('ren.provider', $provider);
         	}
         	if($date != '') {
         		$this->db->where('ren.date', date('Y-m-d', strtotime($date)));
         	}
        
            $this->db->select('ren.date,ren.currency_code,ren.amount,ren.commission,ren.vat,ren.offersid,ren.couponid,ren.rewardid, ren.service_id, ren.booking_id, ur.name as user,pro.name as provider, ren.id');
            $this->db->from('revenue ren');
            $this->db->join('users ur', 'ur.id = ren.user', 'left');
            $this->db->join('providers pro', 'pro.id = ren.provider', 'left');
			$this->db->order_by('ren.id',"DESC");
            $query=$this->db->get();
            $result=$query->result_array();
            return $result;
		}
                public function ColorList()
		{
			return $this->db->where('status', 1)->get('theme_color_change')->result_array();
		}
		public function contact_list()
		{
			return $this->db->get('contact_form_details')->result_array();
		}

		public function contact_list_filter($name,$email){
					$this->db->select('*');
					$this->db->from('contact_form_details');
					if(!empty($name)){
						$this->db->where('name like ',$name);
					}
					if(!empty($email)){
						$this->db->where('email like',$email);
					}					
					return $this->db->get()->result_array();

		}
		public function footercount()
    {
        $query  = $this->db->query("SELECT id FROM  `footer_menu` WHERE STATUS =1");
        $result = $query->num_rows();
        return $result;
	}
	public function is_valid_menu_name($menu_name)
    {
        $query  = $this->db->query("SELECT * FROM `footer_menu` WHERE `title` =  '$menu_name';");
        $result = $query->num_rows();
        return $result;
	}
	public function is_valid_submenu($menu_name)
    {
        $query  = $this->db->query("SELECT * FROM `footer_submenu` WHERE `title` =  '$menu_name';");
        $result = $query->num_rows();
        return $result;
    }
    public function edit_footer_menu($id)
    {
        $query  = $this->db->query("SELECT * FROM `footer_menu` WHERE `id` =  $id;");
        $result = $query->result_array();
        return $result;
	}
	public function get_footer_menu($end, $start)
    {
        $query  = $this->db->query("SELECT * FROM  `footer_menu` LIMIT $start , $end ");
        $result = $query->result_array();
        return $result;
    }
    public function get_footer_submenu()
    {
        $query  = $this->db->query("SELECT footer_submenu.*,footer_menu.title FROM `footer_submenu`
                                    INNER JOIN footer_menu ON footer_menu.id = footer_submenu.`footer_menu`");
        $result = $query->result_array();
        return $result;
    }
    public function get_all_footer_menu()
    {
        $query  = $this->db->query("SELECT * FROM  `footer_menu` ");
        $result = $query->result_array();
        return $result;
    }
    public function get_all_footer_submenu()
    {
        $query  = $this->db->query("SELECT footer_submenu.*,footer_menu.title FROM `footer_submenu`
                                    INNER JOIN footer_menu ON footer_menu.id = footer_submenu.`footer_menu` ");
        $result = $query->num_rows();
        return $result;
	}
	public function edit_submenu($id)
    {
        $query  = $this->db->query("SELECT footer_submenu . * , footer_menu.title
                                    FROM  `footer_submenu` 
                                    INNER JOIN footer_menu ON footer_menu.id = footer_submenu.`footer_menu` 
                                    WHERE footer_submenu.id = $id ");
        $result = $query->result_array();
        return $result;
	}
	public function edit_country_code_config($id)
    {
        $query  = $this->db->query("SELECT * FROM `country_table` WHERE `id` =  $id;");
        $result = $query->result_array();
        return $result;
	}
	public function get_country_code_config()
    {
        $query  = $this->db->query("SELECT * FROM  `country_table`");
        $result = $query->result_array();
        return $result;
    }
	
	public function contactreply_list($id)
    {
        $query  = $this->db->query("SELECT cr.*,c.email,c.message FROM  `contact_reply` as cr left join contact_form_details as c on cr.contact_id = c.id where cr.contact_id = $id");
        $result = $query->result_array();
        return $result;
    }
	
	
	public function check_admin_email($email)
	  {
		$this->db->select('*');
		$this->db->from('administrators');
			$this->db->where('email',$email);
			$this->db->where_in('role',[1,2]);
		  $result = $this->db->get()->row_array();
		return $result;
	  }
	  
	public function check_admin_emailbyid($email,$admin_id)
	  {
		$this->db->select('*');
		$this->db->from('administrators');
			$this->db->where('email',$email);
			$this->db->where('user_id !=',$admin_id);
			$this->db->where_in('role',[1,2]);
		  $result = $this->db->get()->row_array();
		return $result;
	  }
	  
	  
	  
	public function save_pwdlink_data($user_data)
   {
      $result  = $this->db->insert('forget_password_det',$user_data);
      $insert_id = $this->db->insert_id();
      return $insert_id;
   }
   
   
    public function update_pwdlink_data($data, $id) {
		$this->db->where('user_id',$id);
		$status = $this->db->update('forget_password_det', $data);
		return $status;
   
    }
	
	public function update_res_pwd($data, $id) {
	$this->db->where('user_id',$id);
	$status = $this->db->update('administrators', $data);
	return $status;

	}
	
	public function update_forpwd_status($data, $id) {
		$this->db->where('user_id',$id);
		$status = $this->db->update('forget_password_det', $data);
		return $status;
   
    }
	
	public function pn_list()
	{
		$query = $this->db->query(" SELECT * FROM `push_notification` WHERE `status` = 1 ")->result_array();
		return $query;
	}
	
    /*sub subcategory filter*/
	public function get_subcategories_list()
	{
		$query = $this->db->query(" SELECT * FROM `subcategories` WHERE `status` = 1 ")->result_array();
		return $query;
	}
	public function sub_subcategory_filter($category,$subcategory,$sub_subcategory,$from,$to){				
		if(!empty($from)) {
			$from_date=date("Y-m-d", strtotime($from));
		}else{
			$from_date='';
		}
		if(!empty($to)) {
			$to_date=date("Y-m-d", strtotime($to));
		}else{
			$to_date='';
		}

		$this->db->select('ss.*,c.category_name,s.subcategory_name');
		$this->db->from('sub_subcategories ss');
		$this->db->join('categories c', 'c.id = ss.category', 'left');
		$this->db->join('subcategories s', 's.id = ss.subcategory', 'left');
		if(!empty($from_date)){
			$this->db->where('date(s.created_at) >=',$from_date);
		}
		if(!empty($to_date)){
			$this->db->where('date(s.created_at) <=',$to_date);
		}
		if(!empty($category)){
			$this->db->where('ss.category',$category);
		}
		if(!empty($subcategory)){
			$this->db->where('ss.subcategory',$subcategory);
		}
		if(!empty($sub_subcategory)){
			$this->db->where('ss.id',$sub_subcategory);
		}
		$this->db->where('ss.status',1);
		$this->db->order_by('ss.id',"DESC");
		return $this->db->get()->result_array();

		}

		public function sub_subcategory_list()
		{
			$this->db->select('ss.*,c.category_name,s.subcategory_name');
			$this->db->from('sub_subcategories ss');
			$this->db->join('categories c', 'c.id = ss.category', 'left');
			$this->db->join('subcategories s', 's.id = ss.subcategory', 'left');
			$this->db->where('ss.status',1);
			$this->db->order_by('ss.id',"DESC");
			return $this->db->get()->result_array();
		}
		public function sub_subcategories_details($id)
		{
			return $this->db->get_where('sub_subcategories',array('id'=>$id))->row_array();
		}
		/* State and City Code Config */
		public function get_state_code_config()
		{
			$this->db->select("S.*, C.id as cid, C.country_id as cty_codeid, C.country_code as ccode, C.country_name as cname");
			$this->db->from('state S');
			$this->db->join('country_table C', 'C.id = S.country_id', 'left');	
			$this->db->where("C.country_id != ''");
 			$query = $this->db->get()->result_array(); 
			return $query;
		}
		public function edit_state_code_config($id)
		{
			$query  = $this->db->query("SELECT * FROM `state` WHERE `id` =  $id;");
			$result = $query->result_array();
			return $result;
		}
		public function get_city_code_config()
		{
			$this->db->select("C.*, S.id as sid, S.name as state_name, CO.country_id as countryid, CO.country_name as cname");
			$this->db->from('city C');
			$this->db->join('state S', 'S.id = C.state_id', 'left');
			$this->db->join('country_table CO', 'CO.id = S.country_id', 'left');	
			$this->db->where("CO.country_id != ''");
			$query = $this->db->get()->result_array(); 
			return $query;
		}
		public function edit_city_code_config($id)
		{
			$query  = $this->db->query("SELECT * FROM `city` WHERE `id` =  $id;");
			$result = $query->result_array();
			return $result;
		}
		public function get_state_lists()
    	{
        $query  = $this->db->query("SELECT * FROM  `state`");
        $result = $query->result_array();
        return $result;
    	}
    	public function getting_pages_list($id)
	    {
	      $query  = $this->db->query("SELECT * FROM  `page_content` WHERE id = $id")->result();
	        return $query;         
	    }
	    public function getting_faq_list()
	    {
	      $query  = $this->db->get('faq')->result();
	  return $query;         
	    }
	    public function GetBannersettings()
	{
		$results = array();
		$results = $this->db->get_where('bgimage',array('bgimg_id'=> 1))->result_array();
		return $results;
	}

	public function Getpopularsettings()
	{
		$results = array();
		$results = $this->db->get('language_management')->result_array();
		return $results;
	}
	
	
	/** [Get Currencies] */
    public function get_currency_config()
    {
        $query  = $this->db->query("SELECT `id`, `currency_name`, `currency_symbol`, `currency_code`, `rate`, `status` FROM  `currency_rate` WHERE `delete_status` =  1 ORDER BY id DESC");
        $result = $query->result_array();
        return $result;
    }

    /** [Edit Currency] */
    public function edit_currency_config($id)
    {
        $query  = $this->db->query("SELECT `id`, `currency_name`, `currency_symbol`, `currency_code`, `rate`, `status` FROM `currency_rate` WHERE `id` =  $id;");
        $result = $query->row_array();
        return $result;
	}

	public function abuse_reports()
	{
		$results = array();
		$results = $this->db->order_by('id', 'DESC')->where('status', 1)->get('abuse_reports')->result_array();
		return $results;
	}

	public function abuse_reports_list($id)
    {
        $results = array();
		$results = $this->db->query("SELECT * FROM `abuse_reports` WHERE `id` =  $id;")->result_array();
		return $results;
    }

    public function pages_details($id)
	{
		return $this->db->get_where('pages_list',array('id'=>$id))->row_array();
	}

	public function result_getall() {
		$this->db->select('SP.*,U.name,S.subscription_name,SD.expiry_date_time,SD.paid_status');
		$this->db->from('subscription_payment SP');
		$this->db->join('subscription_details SD','SD.subscription_id=SP.subscription_id','left'); 
		$this->db->join('subscription_fee S','S.id=SP.subscription_id','left'); 
		$this->db->join('providers U','U.id=SP.subscriber_id','left');
		$this->db->where(array('SP.tokenid'=> 'Offline Payment'));
		$this->db->group_by('SP.id');
		$this->db->order_by('SP.id', 'DESC');
		$query = $this->db->get();
		return $query->result_array();

	}
	
	/** Get Roles Name Details */
	public function get_roles_permissions($id=null) {
		if(!empty($id)) {
			$this->db->where('id', $id);
		}
		return $this->db->order_by('id', 'DESC')->get_where('roles_permissions', array('status'=>1))->result_array();
	}

	//Add Role Name
    public function add_role_permissions($role_id)
    {
    	$query = $this->db->query("select * from language WHERE status = '1'");
		$languages = $query->result();
        foreach ($languages as $language) {
            $data = array(
                'role_id' => $role_id,
                'lang_type' => $language->language_value,
                'role_name' => $this->input->post('role_name_' . $language->id, true)
            );
            $this->db->insert('roles_permissions_lang', $data);
        }
       	return 1;
    }

    //Update Role Name
    public function update_roles_permissions($role_id)
    {
    	$query = $this->db->query("select * from language WHERE status = '1'");
		$languages = $query->result();
        foreach ($languages as $language) {
            $data = array(
                'role_id' => $role_id,
                'lang_type' => $language->language_value,
                'role_name' => $this->input->post('role_name_' . $language->id, true)
            );
            //check role name exists
            $this->db->where('role_id', $role_id);
            $this->db->where('lang_type', $language->language_value);
            $row = $this->db->get('roles_permissions_lang')->row();
            if (empty($row)) {
                $this->db->insert('roles_permissions_lang', $data);
            } else {
                $this->db->where('role_id', $role_id);
                $this->db->where('lang_type', $language->language_value);
                $this->db->update('roles_permissions_lang', $data);
            }
            return 1;
        }
    }

    public function get_posts_all($status = '',$id = '') {
		$this->db->select('blog_categories.name as cat_name,language.language,blog_posts.*,administrators.full_name,administrators.profile_img');
		$this->db->from('blog_posts');
		$this->db->join('blog_categories','blog_categories.id=blog_posts.category_id');
		$this->db->join('administrators','administrators.user_id=blog_posts.createdBy');
		$this->db->join('language','language.id=blog_categories.lang_id');
		if($status) {
			$this->db->where('blog_posts.status',$status);
		}
		if($id) {
			$this->db->where('blog_posts.id',$id);
		}
		$this->db->order_by('blog_posts.createdAt','desc');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return array();
    }

    /* Blog Category List */
	public function blog_categories_list() {
		$lang_id = $this->db->where('language_value',settingValue('language'))->get('language')->row()->id;
		$this->db->select('blog_categories.*,language.language');
		$this->db->from('blog_categories');
		$this->db->join('language','language.id=blog_categories.lang_id');
		$this->db->where('blog_categories.status',1);
		$this->db->where('blog_categories.lang_id', $lang_id);
		$this->db->order_by('blog_categories.id','desc');

		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}
		return array();
	}

	public function blog_categories_details($id) {
		return $this->db->get_where('blog_categories',array('id'=>$id))->row_array();
	}

	//get categories by lang
    public function get_blog_categories_by_lang($lang_id) {
        $this->db->where('blog_categories.lang_id', $lang_id);
        $this->db->order_by('category_order');
        $query = $this->db->get('blog_categories');
        return $query->result_array();
    }

    /** Get Active and Inactive comments details */
    public function get_all_comments() {
		return $this->db->order_by('id', 'DESC')->get_where('blog_comments', array('status!='=>2))->result_array();
	}
	
	//Get Providers name and wallet amount
    public function providersData() {
    	$provider_data = $this->db->select('p.id,p.name,p.currency_code,wt.wallet_amt')
    			->from('providers p')
    			->join('wallet_table wt', 'wt.user_provider_id = p.id')
    			->where(array('p.status'=>1, 'wt.type'=>1))
    			->get()
    			->result_array();

    	return $provider_data;
    }

    //Update Providers wallet's amount
    public function reduce_user_balance($user_id, $amount) {
    	$wallet_amt = $this->db->select('*')->from('wallet_table')->where(array('user_provider_id'=>$user_id, 'type'=>1))->get()->row();
    	$token = $this->db->get_where('providers', array('id'=>$user_id))->row();
    	
    	/* wallet infos */
		$history_pay['token'] = $token->token;
		$history_pay['currency_code']=$token->currency_code;
		$history_pay['user_provider_id'] = $user_id;
		$history_pay['type'] = '1';
		$history_pay['tokenid'] = 'payout'.$token->token;
		$history_pay['payment_detail'] = 'Payout Request';
		$history_pay['charge_id'] = '';
		$history_pay['transaction_id'] = '';
		$history_pay['exchange_rate'] = '';
		$history_pay['paid_status'] = 'pass';
		$history_pay['cust_id'] = 'Self';
		$history_pay['card_id'] = 'Self';
		$history_pay['total_amt'] = $amount;
		$history_pay['fee_amt'] = 0;
		$history_pay['net_amt'] = 0;
		$history_pay['amount_refund'] = 0;
		$history_pay['current_wallet'] = $wallet_amt->wallet_amt;
		$history_pay['credit_wallet'] = $amount; //(($pay_info->balance_transaction->net) / 100);
		$history_pay['debit_wallet'] = 0;
		$history_pay['avail_wallet'] = $wallet_amt->wallet_amt - $amount; //
		$history_pay['reason'] = 'Refund Amount';
		$history_pay['created_at'] = date('Y-m-d H:i:s');
		if ($this->db->insert('wallet_transaction_history', $history_pay)) {
			/* update wallet table */
			$wallet_dat['currency_code']=$wallet_amt->currency_code;
			$wallet_dat['wallet_amt'] = $history_pay['avail_wallet']; 
			$wallet_dat['updated_on'] = date('Y-m-d H:i:s');
			$where = array('token' => $token->token);
			$this->db->set($wallet_dat);
	        $this->db->where($where);
	        $this->db->update('wallet_table');

	        return $this->db->affected_rows() != 0 ? true : false;
		}
    }

    public function getPayoutRequest() {
    	$request_data = $this->db->get_where('payouts', array('status'=>0))->result_array();
    	
    	return $request_data;
    }

    public function getCompletedPayouts() {
    	$completed_data = $this->db->get_where('payouts', array('status'=>1))->result_array();
    	
    	return $completed_data;
    }

    
}
?>
