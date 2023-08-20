<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api_model extends CI_Model {

    public function __construct() { 
        parent::__construct();
        $this->load->database();
        $this->load->helper('user_timezone');
        date_default_timezone_set('Asia/kolkata');
        $this->date = date('Y-m-d');
        $this->date = utc_date_conversion($this->date);
        $this->date = date('Y-m-d', strtotime($this->date));
        $this->base_url = base_url();
    }

    public function languages_list() {
        $this->db->select('language,language_value,tag');
        $this->db->from('language');
        $this->db->where('status', '1');
        $records = $this->db->get()->result_array();
        return $records;
    }

    public function language_list($key) {
        $this->db->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
        $this->db->from('app_language_management');
        $this->db->where('language', 'en');
        $this->db->where('type', 'App');
        $records = $this->db->get()->result_array();


        $language = array();
        if (!empty($records)) {
            foreach ($records as $record) {
                $this->db->select('lang_key,lang_value,language,placeholder,validation1,validation2,validation3,type,page_key');
                $this->db->from('app_language_management');
                $this->db->where('language', $key);
                $this->db->where('type', 'App');
                $this->db->where('page_key', $record['page_key']);
                $this->db->where('lang_key', $record['lang_key']);
                $eng_records = $this->db->get()->row_array();
                if (!empty($eng_records['lang_value'])) {

                    $language['language'][$record['page_key']][$record['lang_key']]['name'] = $eng_records['lang_value'];
                    $language['language'][$record['page_key']][$record['lang_key']]['placeholder'] = $eng_records['placeholder'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation1'] = $eng_records['validation1'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation2'] = $eng_records['validation2'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation3'] = $eng_records['validation3'];
                } else {
                    $language['language'][$record['page_key']][$record['lang_key']]['name'] = $record['lang_value'];
                    $language['language'][$record['page_key']][$record['lang_key']]['placeholder'] = $record['placeholder'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation1'] = $record['validation1'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation2'] = $record['validation2'];
                    $language['language'][$record['page_key']][$record['lang_key']]['validation3'] = $record['validation3'];
                }
            }
        }
        return $language;
    }

    public function get_user_id_using_token($token) {
        if ($token != '') {
            $this->db->select('*');
            $records = $this->db->get_where('providers', array('token' => $token))->row_array();
            if (!empty($records)) {
                return $records['id'];
            } else {
                return 0;
            }
        }
        return 0;
    }

    public function get_users_id_using_token($token) {
        if ($token != '') {
            $this->db->select('*');
            $records = $this->db->get_where('users', array('token' => $token))->row_array();
            if (!empty($records)) {
                return $records['id'];
            } else {
                return 0;
            }
        }
        return 0;
    }

    public function get_category() {
        $this->db->select('c.id,c.category_name,c.category_image, (SELECT COUNT(s.id) FROM services AS s WHERE s.category=c.id AND s.status=1 ) AS category_count');
        $this->db->from('categories c');
        $this->db->where('c.status', 1);
        $this->db->join('subcategories s', 'c.id = s.category', 'INNER');
        $this->db->group_by('c.id');
        $this->db->order_by('category_count', 'DESC');

        $this->db->limit(6);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_categories() {
        $this->db->select('c.id,c.category_name,c.category_image');
        $this->db->from('categories c');
        $this->db->join('subcategories s', 'c.id = s.category', 'INNER');
        $this->db->where('c.status', 1);

        $this->db->group_by('c.id');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_subcategories($category) {
        $this->db->select('id,subcategory_name,subcategory_image');
        $this->db->from('subcategories');
        $this->db->where('status', 1);
        $this->db->where('category', $category);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function check_email($inputs = '') {
        $email = $inputs['email'];
        $this->db->where('email', $email);
        return $this->db->count_all_results('providers');
    }

    public function check_mobile_no($inputs = '') {

        $mobileno = $inputs['mobileno'];
        $this->db->where(array('country_code' => $inputs['country_code'], 'mobileno' => $mobileno));
        return $this->db->count_all_results('providers');
    }
    //jaya
    public function check_delete_users($inputs = '') {
      
        $mobileno = $inputs['mobileno'];
        $this->db->where(array('country_code' => $inputs['country_code'], 'mobileno' => $mobileno, 'status'=> 2));
        return $this->db->count_all_results('users');
    }

    //jaya
    public function check_delete_providers($inputs = '') {
      
        $mobileno = $inputs['mobileno'];
        $this->db->where(array('country_code' => $inputs['country_code'], 'mobileno' => $mobileno, 'status'=> 2));
        return $this->db->count_all_results('providers');
    }





    public function check_user_email($inputs = '') {
        $email = $inputs['email'];
        $this->db->where('email', $email);
        return $this->db->count_all_results('users');
    }

    public function check_user_mobileno($inputs = '') {

        $mobileno = $inputs['mobileno'];
        $this->db->where(array('country_code' => $inputs['country_code'], 'mobileno' => $mobileno));
        return $this->db->count_all_results('users');
    }
	public function qrcode_generate($qr_data,$id){
		$this->load->library('ciqrcode');
		$qr_image=rand().'.png';
		$params['data'] = $qr_data;
		$params['level'] = 'H';
		$params['size'] = 4;
		$params['savename'] =FCPATH."uploads/qr_image/".$qr_image;
		$qr_message = '';
		if($this->ciqrcode->generate($params)) {
			$qr_img_url=$qr_image; 
			$qr_details['qrcode_path'] = "uploads/qr_image/".$qr_img_url;
			$this->db->where('id',$id);
			$qr_status = $this->db->where('id',$id)->update('providers', $qr_details);	
			return $qr_status;
		}
	}
    public function provider_signup($user_details, $device_data) {

        $user_details['created_at'] = date('Y-m-d H:i:s');
		$cname = $this->db->select('category_type')->where('id',$user_details['category'])->get('categories')->row()->category_type;
		if($cname == '4'){
			$user_details['type'] = 3;
		}
        $result = $this->db->insert('providers', $user_details);
        $records = array();
        if ($result) {
            $user_id = $this->db->insert_id();
            $token = $this->getToken(14, $user_id);
            /* insert wallet */
            $data = array(
                "token" => $token,
                'currency_code' => settings('currency'),
                "user_provider_id" => $user_id,
                "type" => 1,
                "wallet_amt" => 0,
                "created_at" => utc_date_conversion(date('Y-m-d H:i:s'))
            );
			if($cname == '4'){
				$data['type'] = 3;
			}
            $wallet_result = $this->db->insert('wallet_table', $data);
            /* insert wallet */

            $this->db->where('id', $user_id);
            $this->db->update('providers', array('token' => $token));
            $profile_img = base_url() . 'assets/img/professional.png';


            $device_type = $device_data['device_type'];
            $device_id = $device_data['device_id'];
            $date = date('Y-m-d H:i:s');
            $devicetype = strtolower($device_type);

            $deviceid = $device_id;
            $type = '1';

            $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $devicetype, 'device_id' => $deviceid, 'created' => $date, 'type' => $type));
			
			/*QR CODE*/
			if($cname != '4'){
				$qr_res = $this->qrcode_generate($user_details['mobileno'],$user_id);
			}
			
            $this->db->select('name,email,country_code,mobileno,category,subcategory,IF(profile_img IS NULL or profile_img = "", "' . $profile_img . '", profile_img) as profile_img,token', 'type', 'user_type');
            $this->db->where('id', $user_id);
            $records = $this->db->get('providers')->row_array();
        }
        return $records;
    }

    public function provider_update($inputs, $where) {
        $inputs['updated_at'] = date('Y-m-d H:i:s');
        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('providers');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function user_update($inputs, $where) {
        $inputs['updated_at'] = date('Y-m-d H:i:s');
        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('users');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function user_signup($user_details, $device_data) {
        $user_details['created_at'] = date('Y-m-d H:i:s');
        $result = $this->db->insert('users', $user_details);
        $records = array();
        if ($result) {
            $user_id = $this->db->insert_id();
            $token = $this->getToken(14, $user_id);

            /* insert wallet */
            $data = array(
                "token" => $token,
                'currency_code' => settings('currency'),
                "user_provider_id" => $user_id,
                "type" => 2,
                "wallet_amt" => 0,
                "created_at" => utc_date_conversion(date('Y-m-d H:i:s'))
            );
            $wallet_result = $this->db->insert('wallet_table', $data);
            /* insert wallet */
            $this->db->where('id', $user_id);
            $this->db->update('users', array('token' => $token));

            $device_type = $device_data['device_type'];

            $device_id = $device_data['device_id'];
            $date = date('Y-m-d H:i:s');
            $devicetype = strtolower($device_type);

            $deviceid = $device_id;
            $type = '2';

            $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $devicetype, 'device_id' => $deviceid, 'created' => $date, 'type' => $type));

            $this->db->select('*');
            $this->db->where('id', $user_id);
            $records = $this->db->get('users')->row_array();
        }
        return $records;
    }

    public function get_service($type, $inputs) {


        $latitude = $inputs['latitude'];

        $longitude = $inputs['longitude'];

        $radius = (settingValue('radius'))?settingValue('radius'):'0';


        $longitude_min = $longitude - 100 / abs(cos(deg2rad($longitude)) * 69);

        $longitude_max = $longitude + 100 / abs(cos(deg2rad($longitude)) * 69);

        $latitude_min = $latitude - (100 / 69);

        $latitude_max = $latitude + (100 / 69);



        $this->db->select("u.id as prd,s.id,s.currency_code,s.service_title,s.currency_code,s.service_amount,s.service_location,s.service_image,s.service_latitude,s.service_longitude,c.category_name,u.profile_img,1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN((" . $latitude . " - s.service_latitude) *  pi()/180 / 2), 2) +COS(" . $latitude . " * pi()/180) * COS(s.service_latitude * pi()/180) * POWER(SIN((" . $longitude . " - s.service_longitude) * pi()/180 / 2), 2) )) AS distance");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('providers u', 'u.id = s.user_id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->having('distance <=', $radius);


        if ($type == '1') {
            $this->db->order_by('s.total_views', 'DESC');
        } elseif ($type == '2') {
            $this->db->order_by('s.id', 'DESC');
        }

        $this->db->limit(10);
        $query = $this->db->get();

        if ($query) {
            $result = $query->result_array();
        }



        if (count($result) > 0) {

            $response = array();
            $data = array();
            foreach ($result as $r) {

                
                $rating_count = $this->db->where(array("service_id" => $r['id'], 'status' => 1))->count_all_results('rating_review');


                $this->db->select('AVG(rating)');
                $this->db->where(array('service_id' => $r['id'], 'status' => 1));
                $this->db->from('rating_review');

                $rating = $this->db->get()->row_array();

                $avg_rating = round($rating['AVG(rating)'], 2);

                if($inputs['user_id']){
					$data_currency_info = get_api_provider_currency($inputs['user_id']);
					$data_currency = $data_currency_info['user_currency_code'];
				}else if($inputs['users_id']){
					$data_currency_info = get_api_user_currency($inputs['users_id']);
					$data_currency = $data_currency_info['user_currency_code'];
				}else{
					$data_currency = settings('currency');
				}


                $serviceimage = explode(',', $r['service_image']);
                $data['service_id'] = $r['id'];
                $data['service_title'] = $r['service_title'];
                $data['service_amount'] = get_gigs_currency($r['service_amount'], $r['currency_code'], $data_currency);
				$data['currency_code'] = $data_currency; 
                $data['service_image'] = $serviceimage[0];
                $data['category_name'] = $r['category_name'];
                $data['ratings'] = "$avg_rating";
                $data['rating_count'] = "$rating_count";
                if (is_null($r['profile_img'])) {
                    $data['user_image'] = "";
                } else {
                    $data['user_image'] = $r['profile_img'];
                }

                $data['currency'] =  currency_code_sign($data_currency);
                $response[] = $data;
            }

            return $response;
        } else {

            return array();
        }
    }

    public function get_demo_service($type, $inputs) {



        $this->db->select("s.id,s.service_title,s.service_amount,s.currency_code,s.service_location,s.service_image,s.service_latitude,s.service_longitude,c.category_name,u.profile_img");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('providers u', 'u.id = s.user_id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->where('u.name=', 'demo');


        if ($type == '1') {
            $this->db->order_by('s.total_views', 'DESC');
        } elseif ($type == '2') {
            $this->db->order_by('s.id', 'DESC');
        }

        $this->db->limit(10);
        $result = $this->db->get()->result_array();

        if (count($result) > 0) {

            $response = array();
            $data = array();
            foreach ($result as $r) {

                $rating_count = $this->db->where(array("service_id" => $r['id'], 'status' => 1))->count_all_results('rating_review');


                $this->db->select('AVG(rating)');
                $this->db->where(array('service_id' => $r['id'], 'status' => 1));
                $this->db->from('rating_review');

                $rating = $this->db->get()->row_array();

                $avg_rating = round($rating['AVG(rating)'], 2);
				if($inputs['user_id']){
					$data_currency_info = get_api_provider_currency($inputs['user_id']);
					$data_currency = $data_currency_info['user_currency_code'];
				}else if($inputs['users_id']){
					$data_currency_info = get_api_user_currency($inputs['users_id']);
					$data_currency = $data_currency_info['user_currency_code'];
				}else{
					$data_currency = settings('currency');
				}

                $serviceimage = explode(',', $r['service_image']);
                $data['service_id'] = $r['id'];
                $data['service_title'] = $r['service_title'];
                $data['service_amount'] = get_gigs_currency($r['service_amount'], $r['currency_code'], $data_currency);
                $data['currency_code'] = $data_currency;
                $data['service_image'] = $serviceimage[0];
                $data['category_name'] = $r['category_name'];
                $data['ratings'] = "$avg_rating";
                $data['rating_count'] = "$rating_count";
                $data['user_image'] = $r['profile_img'];
                $data['currency'] = currency_code_sign($data_currency);
                $response[] = $data;
            }

            return $response;
        } else {

            return array();
        }
    }

    public function get_my_service($inputs) {
        $user_id = $inputs['user_id'] ? $inputs['user_id'] : $this->get_user_id_using_token($inputs['token']);
        $this->db->select("s.id,s.service_title,s.service_amount,s.service_location,s.service_image,c.category_name,s.status,s.currency_code");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->where("s.status != 0");
        $this->db->where("s.user_id", $user_id);

        $this->db->order_by('s.id', 'DESC');
        if (!empty($inputs['type']) && isset($inputs['type'])) {
            $this->db->where('s.status=', $inputs['type']);
        }


        $query = $this->db->get();

        if ($query) {
            $result = $query->result_array();
        }



        if (count($result) > 0) {

            $provider_currency = get_api_provider_currency($user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];

            $response = array();
            $data = array();
            foreach ($result as $r) {

                $rating_count = $this->db->where(array("service_id" => $r['id'], 'status' => 1))->count_all_results('rating_review');


                $this->db->select('AVG(rating)');
                $this->db->where(array('service_id' => $r['id'], 'status' => 1));
                $this->db->from('rating_review');
                $rating = $this->db->get()->row_array();
                $avg_rating = round($rating['AVG(rating)'], 2);

                $ServiceAmount = (!empty($ProviderCurrency) && $r['currency_code'] != '') ? get_gigs_currency($r['service_amount'], $r['currency_code'], $ProviderCurrency) : $r['service_amount'];
                $serviceimage = explode(',', $r['service_image']);
                //offer check
                $offer = $this->check_current_offer(['service_id'=>$r['id'], 'status'=>0, 'df'=>0]);
                $data['service_id'] = $r['id'];
                $data['service_title'] = $r['service_title'];
                $data['service_location'] = $r['service_location'];
                $data['finalAmount'] = (string) $ServiceAmount;
                $data['service_image'] = $serviceimage[0];
                $data['category_name'] = $r['category_name'];
                $data['ratings'] = "$avg_rating";
                $data['rating_count'] = "$rating_count";
                $data['user_image'] = $serviceimage[0];
                $data['currency_code'] = $r['currency_code'];
                if ($r['status'] == 1) {
                    $data['is_active'] = $r['status'];
                } else if ($r['status'] == 2) {
                    $data['is_active'] = "0";
                }

                $data['currency'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                if (!empty($offer)) {
                    $offrAmt = $ServiceAmount * ($offer['offer_percentage']/100);
                    $data['service_amount'] = $ServiceAmount - $offrAmt;
                    $data['current_offer'] = $offer['offer_percentage'];
                    $data['end_time'] = date("h:i A", strtotime($offer['end_time']));
                }
                else
                {
                    $data['service_amount'] = $ServiceAmount;
                    $data['current_offer'] = '';
                    $data['end_time'] = '';
                }
                $response[] = $data;
            }

            return $response;
        } else {

            return array();
        }
    }

    public function get_service_details($inputs, $user_id, $type = '') {

        $this->db->select("s.*,c.category_name");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->where("s.status = 1 AND s.id='" . $inputs['id'] . "'");
        $result = $this->db->get()->row_array();

        $rating_count = $this->db->where(array("service_id" => $inputs['id'], 'status' => 1))->count_all_results('rating_review');


        $this->db->select('AVG(rating)');
        $this->db->where(array('service_id' => $inputs['id'], 'status' => 1));
        $this->db->from('rating_review');
        $rating = $this->db->get()->row_array();
        $avg_rating = round($rating['AVG(rating)'], 2);

        $this->db->select("service_image");
        $this->db->from('services_image');
        $this->db->where("service_id", $inputs['id']);
        $this->db->where("status", 1);
        $services_image = $this->db->get()->result_array();

        $service_img = array();
        foreach ($services_image as $key => $i) {
            $service_img[] = $i['service_image'];
        }
       $ProviderCurrency = settings('currency');
        if ($type == 1) {
            $provider_currency = get_api_provider_currency($user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
        } else {
            $provider_currency = get_api_user_currency($user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
        }
    if(empty($ProviderCurrency)){
    $ProviderCurrency = settings('currency');
    }
        
        if (!empty($result)) {
            $service_amt=get_gigs_currency($result['service_amount'], $result['currency_code'], $ProviderCurrency);
            $service['service_id'] = $result['id'];
            $service['service_title'] = $result['service_title'];
            $service['service_amount'] = (string) $service_amt;
            $service['service_image'] = $service_img;
            $service['category_name'] = $result['category_name'];
            $service['service_offered'] = $result['service_offered'];
            $service['service_latitude'] = $result['service_latitude'];
            $service['service_longitude'] = $result['service_longitude'];
            $service['about'] = $result['about'];
            $service['ratings'] = "$avg_rating";
            $service['rating_count'] = "$rating_count";
            $service['views'] = $result['total_views'];
            $service['currency'] = currency_code_sign($ProviderCurrency);




            $seller_overview = $this->db->where('id', $result['user_id'])->get('providers')->row_array();

            $this->db->select("s.*,c.category_name");
            $this->db->from('services s');
            $this->db->join('categories c', 'c.id = s.category', 'LEFT');
            $this->db->where('s.user_id', $seller_overview['id']);
            $this->db->where('s.status', 1);
            $this->db->where_not_in('s.id', $inputs['id']);
            $get_services = $this->db->get()->result_array();

            $get_bookings = $this->db->where("user_id", $user_id)->count_all_results('book_service');


            $seller['name'] = $seller_overview['name'];
            $seller['email'] = $seller_overview['email'];
            if ($get_bookings > 0) {
                $seller['mobileno'] = $seller_overview['mobileno'];
            } else {
                $seller['mobileno'] = "xxxxxxxxxx";
            }
            $seller['profile_img'] = $seller_overview['profile_img'];
            $seller['location'] = $result['service_location'];
            $seller['latitude'] = $result['service_latitude'];
            $seller['longitude'] = $result['service_longitude'];
            $seller['location'] = $result['service_location'];
            $seller['country_code'] = $seller_overview['country_code'];



            if (is_array($get_services) && !empty($get_services)) {
                foreach ($get_services as $key => $c) {


                    $this->db->select("service_image");
                    $this->db->from('services_image');
                    $this->db->where("service_id", $c['id']);
                    $this->db->where("status", 1);
                    $image = $this->db->get()->result_array();

                    $this->db->select("*");
                    $this->db->from('providers');
                    $this->db->where("id", $c['user_id']);
                    $provider_details = $this->db->get()->row_array();


                    $serv_image = array();
                    foreach ($image as $key => $i) {
                        $serv_image = $i['service_image'];
                    }

                    $rating_count = $this->db->where("service_id", $c['id'])->count_all_results('rating_review');

                    $this->db->select('AVG(rating)');
                    $this->db->where(array('service_id' => $c['id'], 'status' => 1));
                    $this->db->from('rating_review');
                    $rating = $this->db->get()->row_array();
                    $avg_rating = round($rating['AVG(rating)'], 2);

                    $c_amt=get_gigs_currency($c['service_amount'], $c['currency_code'], $ProviderCurrency);
                    $seller_services['service_id'] = $c['id'];
                    $seller_services['service_title'] = $c['service_title'];
                    $seller_services['service_amount'] = (string) $c_amt;
                    $seller_services['service_image'] = $serv_image;
                    $seller_services['name'] = $provider_details['name'];
                    $seller_services['profile_img'] = $provider_details['profile_img'];
                    $seller_services['category'] = $c['category_name'];
                    $seller_services['service_offered'] = $c['service_offered'];
                    $seller_services['service_latitude'] = $c['service_latitude'];
                    $seller_services['service_longitude'] = $c['service_longitude'];
                    $seller_services['about'] = $c['about'];
                    $seller_services['ratings'] = "$avg_rating";
                    $seller_services['rating_count'] = "$rating_count";
                    $seller_services['views'] = $c['total_views'];
                    $seller_services['currency'] = currency_code_sign($ProviderCurrency);
                    $service_details[] = $seller_services;




                    $response['service_overview'] = $service;
                    $response['seller_overview'] = $seller;
                    $response['seller_services'] = $service_details;
                }


                return $response;
            } elseif (is_array($get_services) && empty($get_services)) {
                $response['service_overview'] = $service;
                $response['seller_overview'] = $seller;
                $response['seller_services'] = [];

                return $response;
            }
        } else {
            $response['service_overview'] = [];
            $response['seller_overview'] = [];
            $response['seller_services'] = '';

            return array();
        }
    }

    public function get_service_info($inputs) {
        $this->db->select("s.*,c.category_name,sc.subcategory_name");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->where("s.status = 1 AND s.id='" . $inputs['id'] . "'");
        $result = $this->db->get()->row_array();

        $provider_currency = get_api_provider_currency($inputs['user_id']);
        $ProviderCurrency = $provider_currency['user_currency_code'];
        
        $res_amt=get_gigs_currency($result['service_amount'], $result['currency_code'], $ProviderCurrency);
        $service['service_id'] = $result['id'];
        $service['service_title'] = $result['service_title'];
        $service['service_amount'] = (string) $res_amt;
        $service['category'] = $result['category'];
        $service['subcategory'] = $result['subcategory'];
        $service['service_offered'] = $result['service_offered'];
        $service['service_location'] = $result['service_location'];
        $service['service_latitude'] = $result['service_latitude'];
        $service['service_longitude'] = $result['service_longitude'];
        $service['category_name'] = $result['category_name'];
        $service['subcategory_name'] = $result['subcategory_name'];
        $service['about'] = $result['about'];
        $service['ratings'] = '0';
        $service['views'] = $result['total_views'];
        $service['currency'] = currency_code_sign($ProviderCurrency);
		
		$service['autoschedule'] = $result['autoschedule'];
		$service['autoschedule_days'] = $result['autoschedule_days'];
		$service['autoschedule_session'] = $result['autoschedule_session'];
		$service['duration'] = $result['duration'];
		$service['duration_in'] = $result['duration_in'];
		$service['service_for'] = $result['service_for'];
		$service['service_for_userid'] = $result['service_for_userid'];
		
		$sscname = $this->db->select('sub_subcategory_name')->where('id',$result['sub_subcategory'])->get('sub_subcategories')->row()->sub_subcategory_name;
		$service['sub_subcategory'] = $result['sub_subcategory'];
		$service['sub_subcategory_name'] = $sscname;
		
		$sname = $this->db->select('shop_name')->where('id',$result['shop_id'])->get('shops')->row()->shop_name;
		$service['shop_id'] = $result['shop_id'];	
		$service['shop_name'] = $sname;
		
		$stfarr = explode(",", $result['staff_id']);
		$stfres = $this->db->select('id,first_name as name')->where_in('id',$stfarr)->get('employee_basic_details')->result_array();
		$service['staff_id'] = $result['staff_id'];
		$service['staff_list'] = $stfres;
		
		$addi_ser = $this->db->select('id, service_id,service_name, amount,duration,duration_in')->where('status',1)->where('service_id',$inputs['id'])->get('additional_services')->result_array();
		$service['additional_services'] = $addi_ser;		

        $image_details = $this->db->where(array('service_id' => $result['id'], 'status' => 1))->get('services_image')->result_array();



        foreach ($image_details as $r) {

            $data['id'] = $r['id'];
            $data['service_image'] = $r['service_image'];
            $data['service_details_image'] = $r['service_details_image'];
            $data['thumb_image'] = $r['thumb_image'];
            $data['mobile_image'] = $r['mobile_image'];
            $data['is_url'] = $r['is_url'];
            $service_image[] = $data;

            $response['service_overview'] = (object) $service;
            $response['service_image'] = $service_image;
        }
        return $response;
    }

    public function all_services($inputs,$user_id='') {



        $count = $this->db->where("status", 1)->count_all_results('services');


        $latitude = $inputs['latitude'];

        $longitude = $inputs['longitude'];

        $radius = (settingValue('radius'))?settingValue('radius'):'0';


        $longitude_min = $longitude - 100 / abs(cos(deg2rad($longitude)) * 69);

        $longitude_max = $longitude + 100 / abs(cos(deg2rad($longitude)) * 69);

        $latitude_min = $latitude - (100 / 69);

        $latitude_max = $latitude + (100 / 69);


        $this->db->select("s.currency_code,u.id as pro,s.id,s.service_title,s.service_amount,s.service_location,s.service_image,s.service_latitude,s.service_longitude,c.category_name,u.profile_img,r.rating,1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN((" . $latitude . " - s.service_latitude) *  pi()/180 / 2), 2) +COS(" . $latitude . " * pi()/180) * COS(s.service_latitude * pi()/180) * POWER(SIN((" . $longitude . " - s.service_longitude) * pi()/180 / 2), 2) )) AS distance");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('rating_review r', 'r.service_id = s.id', 'LEFT');
        $this->db->join('providers u', 's.user_id = u.id', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->having('distance <=', $radius);

        if ($inputs['type'] == 'Popular') {
            $this->db->order_by('s.total_views', 'DESC');
        } else if ($inputs['type'] == 'Feature') {
            $this->db->order_by('r.rating', 'DESC');
        } else {
            $this->db->order_by('s.id', 'DESC');
        }



        $query = $this->db->get();

        if ($query) {
            $result = $query->result_array();
        }


        if (count($result) > 0) {

            if($user_id){
            $provider_currency = get_api_user_currency($user_id);
            $ProviderCurrency = $provider_currency['user_currency_code'];
            }else{
                $ProviderCurrency='';
            }
            
           

            $details = array();
            $new_details = array();
            $data = array();
            foreach ($result as $r) {



                $rating_count = $this->db->where(array('service_id' => $r['id'], 'status' => 1))->count_all_results('rating_review');
                $r_amt=(!empty($ProviderCurrency) && $r['currency_code'] != '') ? get_gigs_currency($r['service_amount'], $r['currency_code'], $ProviderCurrency) : $r['service_amount'];
                $serviceimage = explode(',', $r['service_image']);
                $data['service_id'] = $r['id'];
                $data['service_title'] = $r['service_title'];
                $data['service_amount'] = (string) $r_amt;
                $data['service_latitude'] = $r['service_latitude'];
                $data['service_longitude'] = $r['service_longitude'];
                $data['service_image'] = $serviceimage[0];
                $data['category_name'] = $r['category_name'];
                if (!empty($r['rating'])) {
                    $data['ratings'] = $r['rating'];
                } else {
                    $data['ratings'] = '';
                }
                $data['rating_count'] = "$rating_count";
                $data['user_image'] = $r['profile_img'];
                $data['currency'] = (!empty($ProviderCurrency)) ? currency_code_sign($ProviderCurrency) : currency_code_sign(settings('currency'));
                $details[] = $data;
            }


            if (!empty($details)) {
                $new_details['service_list'] = $details;
            } else {

                $new_details['service_list'] = array();
            }


            return $new_details;
        } else {
            $new_details = array();
            $new_details['service_list'] = array();
            return $new_details;
        }
    }

    public function getToken($length, $user_id) {
        $token = $user_id;
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
        }
        return $token;
    }

    function crypto_rand_secure($min, $max) {

        $range = $max - $min;
        if ($range < 0)
            return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function subcategory_services($inputs) {
        $offset = ($inputs['page'] > 1) ? (($inputs['page'] - 1) * 10) : 0;

        $count = $this->db->where("status", 1)->count_all_results('services');

        $this->db->select
                ("s.id,s.service_title,s.service_amount,s.service_location,s.service_image,c.subcategory_name");
        $this->db->from('services s');
        $this->db->join('subcategories c', 'c.id = s.subcategory', 'LEFT');
        $this->db->where("s.status = 1");
        $this->db->where("c.id ", $inputs['category']);
        $this->db->order_by('s.id', 'DESC');


        $this->db->limit(10, $offset);
        $result = $this->db->get()->result_array();

        if (count($result) > 0) {

            $details = array();
            $new_details = array();
            $data = array();
            foreach ($result as $r) {
                $serviceimage = explode(',', $r['service_image']);
                $data['service_id'] = $r['id'];
                $data['service_title'] = $r['service_title'];
                $data['service_amount'] = $r['service_amount'];
                $data['service_image'] = $serviceimage[0];
                $data['subcategory_name'] = $r['subcategory_name'];
                $data['ratings'] = '0';
                $data['rating_count'] = '0';
                $data['user_image'] = $serviceimage[0];
                $data['currency'] = currency_code_sign(settings('currency'));
                $details[] = $data;
            }

            $total_pages = 0;
            $next_page = -1;
            $page = $inputs['page'];

            if ($count > 0 && $page > 0) {
                $total_pages = ceil($count / 10);
                $page = $inputs['page'];
                if ($page < $total_pages) {
                    $next_page = ($page + 1);
                } else {
                    $next_page = -1;
                }
            }

            $new_details['next_page'] = $next_page;
            $new_details['current_page'] = $page;
            $new_details['total_pages'] = $total_pages;
            $new_details['service_list'] = $details;

            return $new_details;
        } else {

            return array();
        }
    }

    public function subscription() {
        return $this->db->where('status', 1)->get('subscription_fee')->result_array();
    }
	public function provider_subscription($id) {
		$type = $this->db->select('type')->where('id',$id)->get('providers')->row()->type;
        return $this->db->where('type',$type)->where('status', 1)->get('subscription_fee')->result_array();
    }
    public function subscription_success($inputs) {



        $new_details = array();



        $user_id = $this->get_user_id_using_token($inputs['token']);



        $subscription_id = $inputs['subscription_id'];

        $transaction_id = $inputs['transaction_id'];

        $payment_details = !empty($inputs['payment_details']) ? $inputs['payment_details'] : '';
        $this->db->select('duration');
        $record = $this->db->get_where('subscription_fee', array('id' => $subscription_id))->row_array();

        if (!empty($record)) {
            $duration = $record['duration'];
            $days = 30;
            switch ($duration) {
                case 1:
                    $days = 30;
                    break;
                case 2:
                    $days = 60;
                    break;
                case 3:
                    $days = 90;
                    break;
                case 6:
                    $days = 180;
                    break;
                case 12:
                    $days = 365;
                    break;
                case 24:
                    $days = 730;
                    break;

                default:
                    $days = 30;
                    break;
            }

            $subscription_date = date('Y-m-d H:i:s');
            $expiry_date_time = date('Y-m-d H:i:s', strtotime(date("Y-m-d  H:i:s", strtotime($subscription_date)) . " +" . $days . "days"));

            $new_details['subscriber_id'] = $user_id;
            $new_details['subscription_id'] = $subscription_id;
            $new_details['subscription_date'] = $subscription_date;
            $new_details['expiry_date_time'] = $expiry_date_time;

            $this->db->where('subscriber_id', $user_id);
            $count = $this->db->count_all_results('subscription_details');
            if ($count == 0) {
                $new_details['type'] = 1;
                $this->db->insert('subscription_details', $new_details);
                $id = $this->db->insert_id();
            } else {
                $this->db->where('subscriber_id', $user_id);
                $this->db->update('subscription_details', $new_details);
                $details = $this->db->get('subscription_details', array('subscriber_id' => $user_id))->row_array();

                $id = $details['id'];
            }


            $array['sub_id'] = $id;

            $array['subscription_id'] = $subscription_id;

            $array['subscriber_id'] = $user_id;

            $array['subscription_date'] = date('Y-m-d');


            $array['tokenid'] = $transaction_id;

            $array['payment_details'] = $payment_details;

            $this->db->insert('subscription_payment', $array);



            $this->db->where('subscriber_id', $user_id);

            return $this->db->get('subscription_details')->row_array();
        } else {



            return false;
        }
    }

    public function profile($inputs) {

        $user_id = $inputs['user_id'] ? $inputs['user_id'] : $this->get_user_id_using_token($inputs['token']);
        $results = array();


        $this->db->select("p.*,c.category_name,sc.subcategory_name, c.category_type");
        $this->db->from('providers p');
        $this->db->join('categories c', 'c.id = p.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = p.subcategory', 'LEFT');
        $this->db->where(array('p.id' => $user_id));
        $results = $this->db->get()->row_array();
        $results['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);

        $results['stripe_details'] = $this->Stripe_Details($user_id);
        ;
        $data['id'] = $results['id'];
        $data['name'] = $results['name'];
        $data['email'] = $results['email'];
        $data['country_code'] = $results['country_code'];
        $data['currency_code'] = $results['currency_code'];
        $data['mobileno'] = $results['mobileno'];
        $data['category'] = $results['category'];
        $data['subcategory'] = $results['subcategory'];
        $data['category_name'] = $results['category_name'];
        $data['subcategory_name'] = $results['subcategory_name'];
        $data['profile_img'] = base_url().$results['profile_img'];
        $data['created_at'] = $results['created_at'];
        $data['updated_at'] = $results['updated_at'];
        $data['status'] = $results['status'];
        $data['subscription_details'] = $results['subscription_details'];
        $data['stripe_details'] = $results['stripe_details'];
		
		$data['dob'] = ($results['dob'])?$results['dob']:'';
		
		$data['homeservice_fee'] = $results['homeservice_fee'];
		$data['homeservice_arrival'] = $results['homeservice_arrival'];
		$data['allow_rewards'] = $results['allow_rewards'];
		$data['booking_reward_count'] = $results['booking_reward_count'];
		$data['commercial_verify'] = $results['commercial_verify'];
		$data['commercial_reg_image'] = base_url().$results['commercial_reg_image'];
		$data['qrcode_path'] = base_url().$results['qrcode_path'];
		
		
		$data['account_holder_name'] = $results['account_holder_name'];	
		$data['account_number'] = $results['account_number'];	
		$data['account_iban'] = $results['account_iban'];	
		$data['bank_name'] = $results['bank_name'];
		$data['bank_address'] = $results['bank_address'];	
		$data['sort_code'] = $results['sort_code'];	
		$data['routing_number'] = $results['routing_number'];	
		$data['account_ifsc'] = $results['account_ifsc'];	
		
		if($results['category_type']=="1") $category_type_txt = "Men";
		else if($results['category_type']=="2") $category_type_txt = "Ladies";
		else if($results['category_type']=="3") $category_type_txt = "Both";
		else if($results['category_type']=="4") $category_type_txt = "Freelancer";
		$data['category_type'] = $results['category_type'];
		$data['category_type_txt'] = $category_type_txt;	
	
				
		$address = $this->db->where("provider_id",$results['id'])->get("provider_address")->row_array();
		$data['address'] = $address['address'];
		$data['country_id'] = $address['country_id'];
		$data['country_name'] = $this->db->select('country_name')->get_where('country_table', array('id' => $data['country_id']))->row()->country_name;
		$data['state_id'] = $address['state_id'];
		$data['state_name'] = $this->db->select('name')->get_where('state', array('id' => $data['state_id']))->row()->name;
		$data['city_id'] = $address['city_id'];
		$data['city_name'] = $this->db->select('name')->get_where('city', array('id' => $data['city_id']))->row()->name;
		$data['pincode'] = $address['pincode'];

        return $data;
    }

    public function Stripe_Details($user_id) {
        $this->db->select('*');
        $this->db->from('stripe_bank_details');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return $result;
        } else {
            return (object) array();
        }
    }

    public function user_profile($inputs) {

        $user_id = $inputs['user_id'] ? $inputs['user_id'] : $this->get_users_id_using_token($inputs['token']);
        $results = array();


        $this->db->select("*");
        $this->db->from('users');
        $this->db->where(array('id' => $user_id));
        $results = $this->db->get()->row_array();
        $results['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);

        $data['id'] = $results['id'];
        $data['name'] = $results['name'];
        $data['email'] = $results['email'];
        $data['country_code'] = $results['country_code'];
        $data['currency_code'] = $results['currency_code'];
        $data['mobileno'] = $results['mobileno'];
        $data['profile_img'] = $results['profile_img'];
        $data['created_at'] = $results['created_at'];
        $data['updated_at'] = $results['updated_at'];
        $data['status'] = $results['status'];
        $data['type'] = $results['type'];
        $data['subscription_details'] = $results['subscription_details'];
		
		$data['dob'] = ($results['dob'])?$results['dob']:'';
		
		$data['gender'] = $results['gender'];
		
		$address = $this->db->where("user_id",$results['id'])->get("user_address")->row_array();
		$data['address'] = $address['address'];
		$data['country_id'] = $address['country_id'];
		$data['country_name'] = $this->db->select('country_name')->get_where('country_table', array('id' => $data['country_id']))->row()->country_name;
		$data['state_id'] = $address['state_id'];
		$data['state_name'] = $this->db->select('name')->get_where('state', array('id' => $data['state_id']))->row()->name;
		$data['city_id'] = $address['city_id'];
		$data['city_name'] = $this->db->select('name')->get_where('city', array('id' => $data['city_id']))->row()->name;
		$data['pincode'] = $address['pincode'];

        return $data;
    }

    public function profile_update($inputs) {

        $user_id = $inputs['token'];
        $id = $this->get_user_id_using_token($inputs['token']);
        $results = array();


        $this->db->select("p.*,c.category_name,sc.subcategory_name");
        $this->db->from('providers p');
        $this->db->join('categories c', 'c.id = p.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = p.subcategory', 'LEFT');
        $this->db->where(array('p.token' => $user_id));
        $results = $this->db->get()->row_array();
        $results['subscription_details'] = $this->get_subscription_details_using_user_id($id);

        $data['id'] = $results['id'];
        $data['name'] = $results['name'];
        $data['email'] = $results['email'];
        $data['country_code'] = $results['country_code'];
        $data['mobileno'] = $results['mobileno'];
        $data['category'] = $results['category'];
        $data['subcategory'] = $results['subcategory'];
        $data['category_name'] = $results['category_name'];
        $data['subcategory_name'] = $results['subcategory_name'];
        $data['profile_img'] = $results['profile_img'];
        $data['created_at'] = $results['created_at'];
        $data['updated_at'] = $results['updated_at'];
        $data['status'] = $results['status'];
        $data['subscription_details'] = $results['subscription_details'];

        return $data;
    }

    public function get_subscription_details_using_user_id($id = '') {
        $records = array();
        if (!empty($id)) {
            $this->db->select('SD.expiry_date_time, SF.subscription_name');
            $this->db->from('subscription_details SD');
            $this->db->join('subscription_fee SF', 'SF.id = SD.subscription_id', 'left');
            $this->db->where('subscriber_id', $id);
            $records = $this->db->get()->row_array();
            if (!empty($records)) {
                $records['expiry_date_time'] = utc_date_conversion($records['expiry_date_time']);
            } else {
                $records = (object) array();
            }
        }
        return $records;
    }

    public function create_service($inputs) {
        $this->db->insert('services', $inputs);
        return $this->db->insert_id();
    }

    public function insert_serviceimage($image) {
        $this->db->insert('services_image', $image);
        $this->db->where(array('service_id' => $image['service_id']));
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function update_service($inputs, $where) {

        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('services');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function delete_service($inputs, $where) {

        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('services_image');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function get_provider_details($mobile_number, $user_data) {

        $this->db->select('providers.*, categories.category_name, subcategories.subcategory_name, categories.category_type');
        $this->db->join('categories', 'providers.category = categories.id', 'left');
        $this->db->join('subcategories', 'providers.subcategory = subcategories.id', 'left');
        $this->db->where('providers.mobileno', $mobile_number);
        $record = $this->db->get('providers')->row_array();
        $records = array();
        

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '1';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where('user_id', $user_id);

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }


            $this->db->select('subscription_id');

            $this->db->where('subscriber_id', $user_id);

            $subscription = $this->db->get('subscription_details')->row_array();


            if (!empty($subscription)) {

                $id = $subscription['subscription_id'];

                $this->db->select('id,subscription_name');

                $this->db->where('id', $id);

                $subscription = $this->db->get('subscription_fee')->row_array();

                $subscribed_user = 1;

                $subscribed_msg = $subscription['subscription_name'];
            } else {

                $subscribed_user = 0;

                $subscribed_msg = 'Free';
            }
            $records = array('id' => $record['id'], 'token' => $record['token'], 'name' => $record['name'], 'mobileno' => $record['mobileno'], 'country_code' => $record['country_code'], 'currency_code' => $record['currency_code'], 'share_code' => $record['share_code'], 'otp' => $record['otp'], 'email' => $record['email'], 'profile_img' => $record['profile_img'], 'status' => $record['status'], 'created_at' => $record['created_at'], 'updated_at' => $record['updated_at'], 'type' => $record['type']);

            $records['id'] = $record['id'];
            $records['name'] = $record['name'];
            $records['mobileno'] = $record['mobileno'];
            $records['country_code'] = $record['country_code'];
            $records['otp'] = $record['otp'];
            $records['profile_img'] = base_url().$record['profile_img'];
            $records['status'] = $record['status'];
            $records['created_at'] = $record['created_at'];
            $records['updated_at'] = $record['updated_at'];
            $records['type'] = $record['type'];
            $records['is_subscribed'] = "$subscribed_user";
            $records['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);
            $records['share_code'] = $record['share_code'];
			
			$records['dob'] = ($record['dob'])?$record['dob']:'';
			
			$records['homeservice_fee'] = $record['homeservice_fee'];
			$records['homeservice_arrival'] = $record['homeservice_arrival'];
			$records['allow_rewards'] = $record['allow_rewards'];
			$records['booking_reward_count'] = $record['booking_reward_count'];
			$records['commercial_verify'] = $record['commercial_verify'];
			$records['commercial_reg_image'] = base_url().$record['commercial_reg_image'];
			$records['qrcode_path'] = base_url().$record['qrcode_path'];
			
            $records['category'] = $record['category'];
            $records['category_name'] = $record['category_name'];
            $records['subcategory'] = $record['subcategory'];
			$records['subcategory_name'] = $record['subcategory_name'];
			
			
			if($record['category_type']=="1") $category_type_txt = "Men";
			else if($record['category_type']=="2") $category_type_txt = "Ladies";
			else if($record['category_type']=="3") $category_type_txt = "Both";
			else if($record['category_type']=="4") $category_type_txt = "Freelancer";
			$records['category_type'] = $record['category_type'];
			$records['category_type_txt'] = $category_type_txt;
			
			$records['account_holder_name'] = $record['account_holder_name'];	
			$records['account_number'] = $record['account_number'];	
			$records['account_iban'] = $record['account_iban'];	
			$records['bank_name'] = $record['bank_name'];
			$records['bank_address'] = $record['bank_address'];	
			$records['sort_code'] = $record['sort_code'];	
			$records['routing_number'] = $record['routing_number'];	
			$records['account_ifsc'] = $record['account_ifsc'];
        }

        return $records;
    }

    public function get_service_id($inputs) {
        return $this->db->where('id', $inputs)->get('services')->row_array();
    }

    public function check_otp($check_data) {
        $this->db->select('id,mobile_number,otp,endtime');
        $this->db->from('mobile_otp');
        $this->db->where($check_data);
        $this->db->where('status', 1);
        $query = $this->db->get();
        $time_count = $query->num_rows();
        if ($time_count == 1) {

            $this->db->where($check_data);
            $this->db->update('mobile_otp', array('status' => 0));

            $this->db->select('*');
            $this->db->where($check_data);
            return $this->db->get('mobile_otp')->row_array();
        }
    }

    public function get_serviceimg($service_id) {

        $this->db->select('*');
        $this->db->where('service_id', $service_id);
        $this->db->where('status', 1);
        $query = $this->db->get('services_image');
        return $time_count = $query->num_rows();
    }

    public function insert_businesshours($user_data) {
        $this->db->insert('business_hours', $user_data);
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function update_availability($user_data, $where) {

        $this->db->set($user_data);
        $this->db->where($where);
        $this->db->update('business_hours');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function get_availability($provider_id) {
        return $this->db->where('provider_id', $provider_id)->get('business_hours')->row_array();
    }

    public function save_otp($user_data) {
        $result = $this->db->insert('mobile_otp', $user_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function get_user_details($mobile_number, $user_data) {
        $record = $this->db->where('mobileno', $mobile_number)->get('users')->row_array();

        $records = array();

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '2';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where('user_id', $user_id);

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }




            $subscribed_user = 1;

            $records = array('id' => $record['id'], 'token' => $record['token'], 'name' => $record['name'], 'mobileno' => $record['mobileno'], 'country_code' => $record['country_code'], 'otp' => $record['otp'], 'email' => $record['email'], 'profile_img' => $record['profile_img'], 'status' => $record['status'], 'created_at' => $record['created_at'], 'updated_at' => $record['updated_at'], 'type' => $record['type']);

            $records['id'] = $record['id'];
            $records['name'] = $record['name'];
            $records['mobileno'] = $record['mobileno'];
            $records['profile_img'] = $record['profile_img'];
            $records['country_code'] = $record['country_code'];
            $records['otp'] = $record['otp'];
            $records['status'] = $record['status'];
            $records['created_at'] = $record['created_at'];
            $records['updated_at'] = $record['updated_at'];
            $records['type'] = $record['type'];
            $records['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);
            $records['share_code'] = $record['share_code'];
        }


        return $records;
    }

    public function logout_provider($token = '', $device_type, $device_id) {

        $result = 0;


        $device_id = $device_id;

        $device_type = strtolower($device_type);

        $user_id = $this->get_user_id_using_token($token);

        $this->db->where(array('device_id' => $device_id, 'device_type' => $device_type, 'user_id' => $user_id));

        $this->db->delete('device_details');



        if (!empty($token)) {

            $last_login = date('Y-m-d H:i:s');

            $this->db->where('token', $token);

            $this->db->update('providers', array('last_login' => $last_login));

            $result = $this->db->affected_rows();
        }



        return $result;
    }

    public function provider_hours($user_id) {
        return $this->db->where('provider_id', $user_id)->get('business_hours')->row_array();
    }

    public function token_is_valid($token) {


        $where = array();

        $where['token'] = $token;

        $this->db->where($where);

        $count = $this->db->count_all_results('users');

        return $count;
    }

    public function get_bookings($service_date, $service_id) {
        return $this->db->where(array('service_date' => $service_date, 'service_id' => $service_id))->get('book_service')->result_array();
    }

    public function insert_booking($user_post_data) {

        $this->db->insert('book_service', $user_post_data);
        return $this->db->insert_id();
    }

    public function search_services($text) {
        $this->db->select("s.*,c.category_name,sc.subcategory_name,u.profile_img");
        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users u', 'u.id = s.user_id', 'LEFT');
        $this->db->like('s.service_title', $text);
        $this->db->or_like('c.category_name', $text);
        $this->db->or_like('sc.subcategory_name', $text);

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_bookinglist($provider_id, $status) {

        if (!empty($status)) {

            if ((int) $status == 1) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` ,`p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id  AND (`b`.`status` = 1  OR  `b`.`status` = 2  OR  `b`.`status` =3 OR `b`.`status` =5 OR `b`.`status` =6 OR `b`.`status` =7 ) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC ");
                $result = $query->result_array();
            }
			if ((int) $status == 2) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token`,`p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =2)  AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC ");
                $result = $query->result_array();
            }
            if ((int) $status == 3) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` , `p`.`token`, `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =6) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 4) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,`p`.`token`,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =7) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 5) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,`p`.`token`,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =1) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 6) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,`p`.`token`,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =3) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 7) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,`p`.`token`,  `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` FROM  `book_service`  `b` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`provider_id` =  $provider_id AND (`b`.`status` =5) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
        }

        return $result;
    }

    public function get_requestlist($provider_id) {
        $this->db->select("b.*,s.service_title,s.service_image,s.service_amount,s.rating,s.service_image,
        c.category_name,sc.subcategory_name,p.profile_img");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.status", 1);
        $this->db->where("b.provider_id", $provider_id);
        $this->db->order_by("b.id", 'DESC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_bookinglist_user($user_id, $status) {

        if (!empty($status)) {

            if ((int) $status == 1) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` , `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile`  FROM  `book_service`  `b`  LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id`  LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` = 2 OR  `b`.`status` =3 OR `b`.`status` =5 OR `b`.`status` =6 OR `b`.`status` =7 ) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }

			if ((int) $status == 2) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =2 ) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 3) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,   `p`.`token` ,`p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =6) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 4) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =7) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 5) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =1) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 6) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =3) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            if ((int) $status == 7) {
                $query = $this->db->query("SELECT  `b` . * ,  `s`.`service_title` ,  `s`.`service_image` ,  `s`.`service_amount` ,  `s`.`rating` ,  `s`.`service_image` ,  `c`.`category_name` ,  `sc`.`subcategory_name` ,  `p`.`token` , `p`.`profile_img` ,  `p`.`mobileno` ,  `p`.`country_code` ,`pro`.`profile_img` as `provider_profile` FROM  `book_service`  `b` LEFT JOIN  `providers`  `pro` ON  `b`.`provider_id` =  `pro`.`id` LEFT JOIN  `services`  `s` ON  `b`.`service_id` =  `s`.`id` LEFT JOIN  `categories`  `c` ON  `c`.`id` =  `s`.`category` LEFT JOIN  `subcategories`  `sc` ON  `sc`.`id` =  `s`.`subcategory` LEFT JOIN  `users`  `p` ON  `b`.`user_id` =  `p`.`id` WHERE  `b`.`user_id` =  $user_id AND (`b`.`status` =5) AND b.guest_parent_bookid = 0 ORDER BY `b`.`id` DESC");
                $result = $query->result_array();
            }
            
        }

        return $result;
    }

    public function bookingdetail_user($user_id, $booking_id) {


        $this->db->select("b.*,s.id,s.service_title,s.service_amount,s.about,s.service_offered,s.service_location,s.service_latitude,s.service_longitude,s.total_views,p.name,p.mobileno,p.country_code,p.email,p.profile_img,p.token,c.category_name,sc.subcategory_name");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'b.provider_id = p.id', 'LEFT');
        $this->db->where("b.user_id", $user_id);
        $this->db->where("b.id", $booking_id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_bookingdetails($provider_id, $booking_id) {

        $this->db->select("b.*,s.id,s.service_title,s.service_amount,s.about,s.service_offered,s.service_location,s.service_latitude,s.service_longitude,s.total_views,u.name,u.mobileno,u.country_code,u.email,u.profile_img,u.token,c.category_name,sc.subcategory_name");
        $this->db->from('book_service b');
        $this->db->join('services s', 'b.service_id = s.id', 'LEFT');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users u', 'b.user_id = u.id', 'LEFT');
        $this->db->where("b.provider_id", $provider_id);
        $this->db->where("b.id", $booking_id);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_service_bookingdetails($provider_id, $service_id) {
        $result = $this->db->where('service_id', $service_id)
                        ->where_not_in('status', [7, 5, 6])->from('book_service')->count_all_results();
        return $result;
    }

    public function update_bookingstatus($book_details, $where) {

        $this->db->set($book_details);
        $this->db->where($where);
        $this->db->update('book_service');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function service_statususer($user_data, $where) {

        $this->db->set($user_data);
        $this->db->where($where);
        $this->db->update('book_service');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function booking_status($user_data) {
        return $this->db->where('id', $user_data)->get('book_service')->row_array();
    }

    public function token_is_valid_provider($token) {


        $where = array();

        $where['token'] = $token;

        $this->db->where($where);

        $count = $this->db->count_all_results('providers');

        return $count;
    }

    public function rate_review_for_service($inputs) {


        $get_provider = $this->db->where('id', $inputs['service_id'])->get('services')->row_array();

        $new_details = array();

        $user_id = $this->users_id;

        $new_details['user_id'] = $user_id;

        $new_details['service_id'] = $inputs['service_id'];

        $new_details['booking_id'] = $inputs['booking_id'];

        $new_details['provider_id'] = $get_provider['user_id'];

        $new_details['rating'] = $inputs['rating'];

        $new_details['review'] = $inputs['review'];

        $new_details['type'] = $inputs['type'];

        $new_details['created'] = date('Y-m-d H:i:s');

        $this->db->where('status', 1);

        $this->db->where('booking_id', $inputs['booking_id']);

        $this->db->where('user_id', $user_id);

        $count = $this->db->count_all_results('rating_review');

        if ($count == 0) {

            return $this->db->insert('rating_review', $new_details);
        } else {

            return $result = 2;
        }
    }

    public function check_booking_status($user_data) {
        return $this->db->where(array('id' => $user_data, 'status' => 6))->get('book_service')->row_array();
    }

    public function rate_review_list($inputs) {


        $this->db->select("r.*,u.*");
        $this->db->from('rating_review r');
        $this->db->join('users u', 'r.user_id = u.id', 'LEFT');
        $this->db->where("r.service_id", $inputs);
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function delete_account_provider($user_data, $where) {

        $this->db->set('status', 2);
        $this->db->where($where);
        $this->db->update('providers');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function delete_account_user($user_data, $where) {

        $this->db->set('status', 2);
        $this->db->where($where);
        $this->db->update('users');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    //get_services_from_sub_service_id
    public function get_services_from_sub_service_id($inputs) {


        $latitude = $inputs['latitude'];

        $longitude = $inputs['longitude'];

        $radius = (settingValue('radius'))?settingValue('radius'):'0';


        $longitude_min = $longitude - 100 / abs(cos(deg2rad($longitude)) * 69);

        $longitude_max = $longitude + 100 / abs(cos(deg2rad($longitude)) * 69);

        $latitude_min = $latitude - (100 / 69);

        $latitude_max = $latitude + (100 / 69);



        $this->db->select("s.*,c.category_name,sc.subcategory_name,u.profile_img,1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN((" . $latitude . " - s.service_latitude) *  pi()/180 / 2), 2) +COS(" . $latitude . " * pi()/180) * COS(s.service_latitude * pi()/180) * POWER(SIN((" . $longitude . " - s.service_longitude) * pi()/180 / 2), 2) )) AS distance");

        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users u', 'u.id = s.user_id', 'LEFT');
        $this->db->where('s.subcategory', $inputs['subcategory_id']);
        $this->db->where('s.status', 1);

        $this->db->having('distance <=', $radius);

        $result = $this->db->get()->result_array();



        return $result;
    }

    //common get service image
    public function get_common_service_image($id, $type) {

        $this->db->select("service_image");
        $this->db->from('services_image');
        $this->db->where("service_id", $id);
        $this->db->where("status", 1);
        if ($type == '1' || $type == 1) {
            $val = $this->db->get()->row_array();
        } else {
            $val = $this->db->get()->result();
        }
        return $val;
    }

    //get get_provider_dashboard_count

    public function get_provider_dashboard_count($provider_id) {

        $count = [];

        $service_count = $this->db->query(" SELECT * FROM `services` WHERE `user_id` =                            $provider_id AND (`status` =1)");
        $serv_count = $service_count->result_array();

        $pending_booking_count = $this->db->query(" SELECT * FROM `book_service` WHERE `provider_id` =                            $provider_id AND (`status` =1)");
        $pending_service_count = $pending_booking_count->result_array();

        $inprogress_booking_count = $this->db->query(" SELECT * FROM `book_service` WHERE `provider_id` =                            $provider_id AND (`status` =2)");
        $inprogress_service_count = $inprogress_booking_count->result_array();

        $completed_booking_count = $this->db->query(" SELECT * FROM `book_service` WHERE `provider_id` =                           $provider_id AND (`status` =6)");
        $complete_service_count = $completed_booking_count->result_array();

        $p = count($pending_service_count);
        $i = count($inprogress_service_count);
        $c = count($complete_service_count);
        $s = count($serv_count);

        $count['service_count'] = "$s";
        $count['pending_service_count'] = "$p";
        $count['inprogress_service_count'] = "$i";
        $count['complete_service_count'] = "$c";

        return $count;
    }

    public function search_request_list($inputs) {


        $search_title = (!empty($inputs['text'])) ? $inputs['text'] : '';

        $new_details = array();

        $latitude = $inputs['latitude'];

        $longitude = $inputs['longitude'];

        $radius = (settingValue('radius'))?settingValue('radius'):'0';


        $longitude_min = $longitude - 100 / abs(cos(deg2rad($longitude)) * 69);

        $longitude_max = $longitude + 100 / abs(cos(deg2rad($longitude)) * 69);

        $latitude_min = $latitude - (100 / 69);

        $latitude_max = $latitude + (100 / 69);



        $this->db->select("s.*,c.category_name,sc.subcategory_name,u.profile_img,1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN((" . $latitude . " - 

s.service_latitude) *  pi()/180 / 2), 2) +COS(" . $latitude . " * pi()/180) * COS

(s.service_latitude * pi()/180) * POWER(SIN((" . $longitude . " - s.service_longitude) * pi()/180 

/ 2), 2) )) AS distance");

        $this->db->from('services s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('users u', 'u.id = s.user_id', 'LEFT');
        //$this->db->where("(`s`.`service_title` LIKE '%".$inputs['text']."%' OR `c`.`category_name` LIKE '%".$inputs['text']."%' OR `sc`.`subcategory_name` LIKE '%".$inputs['text']."%')", NULL, FALSE);
        $this->db->like('s.service_title', $inputs['text']);
        $this->db->or_like('c.category_name', $inputs['text']);
        $this->db->or_like('sc.subcategory_name', $inputs['text']);
        $this->db->having('distance <=', $radius);
        

        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();  exit;
        return $result;
    }
	
	public function shop_request_list($inputs) {


        $search_title = (!empty($inputs['text'])) ? $inputs['text'] : '';

        $new_details = array();

        $latitude = $inputs['latitude'];

        $longitude = $inputs['longitude'];

        $radius = (settingValue('radius'))?settingValue('radius'):'0';

        $this->db->select("s.id, s.shop_code, s.shop_name, s.country_code, s.contact_no, s.email, s.address, s.shop_location, s.shop_latitude,s.shop_longitude,si.shop_image, c.category_name, sc.subcategory_name, p.profile_img, 1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN((" . $latitude . " - 

s.shop_latitude) *  pi()/180 / 2), 2) +COS(" . $latitude . " * pi()/180) * COS

(s.shop_latitude * pi()/180) * POWER(SIN((" . $longitude . " - s.shop_longitude) * pi()/180 

/ 2), 2) )) AS distance");

        $this->db->from('shops s');
        $this->db->join('categories c', 'c.id = s.category', 'LEFT');
        $this->db->join('subcategories sc', 'sc.id = s.subcategory', 'LEFT');
        $this->db->join('providers p', 'p.id = s.provider_id', 'LEFT');
		$this->db->join('shops_images si', 'si.shop_id = s.id', 'LEFT');
		$this->db->where('s.status', 1);
        $this->db->like('s.shop_name', $inputs['text']);        
        $this->db->having('distance <=', $radius);


        $result = $this->db->get()->result_array(); 
        return $result;
    }
	

    public function get_rating_type() {
        return $this->db->get('rating_type')->result_array();
    }

    public function accdetails_provider($detail) {
        return $this->db->where('id', $detail['id'])->get('providers')->row_array();
    }

    public function accdetails_user($detail) {
        return $this->db->where('id', $detail['id'])->get('users')->row_array();
    }

    // Chat queries
    public function chat_conversation($array) {
        $this->db->insert('chat_table', $array);

        $id = $this->db->insert_id();

        $this->db->select('C.*');

        $this->db->from('chat_table C');

        $this->db->where(array('chat_id' => $id));

        return $this->db->get()->row_array();
    }

    public function username($id) {
        $this->db->select('*');
        $users = $this->db->get_where('users', array('token' => $id))->row_array();

        $this->db->select('*');
        $providers = $this->db->get_where('providers', array('token' => $id))->row_array();
        if (!empty($users)) {
            $users['type'] = 2;
            return $users;
        } else {
            $users['type'] = 1;
            return $providers;
        }
    }

    /* insert msg */

    public function insert_msg($data) {
        $val = $this->db->insert("chat_table", $data);
        if ($val) {
            return true;
        } else {
            return false;
        }
    }

    /* get chat list */
    /* get information base on token */

    public function get_chat_list_info($token) {

        $user_table = $this->db->select('id,name,profile_img,token')->
                        from('users')->
                        where('token', $token)->
                        get()->row_array();
        $provider_table = $this->db->select('id,name,profile_img,token')->
                        from('providers')->
                        where('token', $token)->
                        get()->row_array();
        if (!empty($user_table)) {
            $user = $this->get_last_msg($user_table['token']);

            $user_table['message'] = $user['message'];
            $user_table['datetime'] = $user['created_at'];
            $user_table['date'] = date('d-m-Y', strtotime($user['created_at']));
            $user_table['time'] = date('H:i a', strtotime($user['created_at']));
            return $user_table;
        } else {
            $provider = $this->get_last_msg($provider_table['token']);
            $provider_table['message'] = $provider['message'];
            $provider_table['datetime'] = $provider['created_at'];
            $provider_table['date'] = date('d-m-Y', strtotime($provider['created_at']));
            $provider_table['time'] = date('H:i a', strtotime($provider['created_at']));

            return $provider_table;
        }
    }

    /* get last msg get token based */

    public function get_last_msg($token) {
        $val = $this->db->select('message,created_at')->
                        from('chat_table')->
                        where('sender_token', $token)->
                        or_where('receiver_token', $token)->
                        order_by('chat_id', 'DESC')->
                        limit(1)->get()->row_array();
        return $val;
    }

    /* get information base on token */

    public function get_token_info($token) {

        $user_table = $this->db->select('id,name,profile_img,token,type,email')->
                        from('users')->
                        where('token', $token)->
                        get()->row();
        $provider_table = $this->db->select('id,name,profile_img,token,type,email')->
                        from('providers')->
                        where('token', $token)->
                        get()->row();
        if (!empty($user_table)) {
            return $user_table;
        } else {
            return $provider_table;
        }
    }

    /* history */

    public function get_conversation_info($self_token, $partner_token) {
        $return = $this->db->select('chat_id,sender_token,receiver_token,message,status,read_status,utc_date_time,created_at')->
                        from('chat_table')->
                        where("(`sender_token` = '" . $self_token . "' AND `receiver_token` = '" . $partner_token . "') OR (`sender_token` = '" . $partner_token . "' AND `receiver_token` = '" . $self_token . "')")->
                        group_by('chat_id')->
                        order_by('chat_id', 'ASC')->
                        get()->result();
        return $return;
    }

    /* get book service ingo */

    public function get_book_info($book_service_id) {


        $ret = $this->db->select('tab_1.provider_id,tab_1.user_id,tab_1.status,tab_2.service_title')->
                        from('book_service as tab_1')->
                        join('services as tab_2', 'tab_2.id=tab_1.service_id', 'LEFT')->
                        where('tab_1.id', $book_service_id)->limit(1)->
                        order_by('tab_1.id', 'DESC')->
                        get()->row_array();
        return $ret;
    }

    public function get_book_info_b($book_service_id) {


        $ret = $this->db->select('tab_1.provider_id,tab_1.user_id,tab_1.status,tab_1.currency_code,tab_1.amount,tab_2.service_title')->
                        from('book_service as tab_1')->
                        join('services as tab_2', 'tab_2.id=tab_1.service_id', 'LEFT')->
                        where('tab_1.id', $book_service_id)->limit(1)->
                        order_by('tab_1.id', 'DESC')->
                        get()->row_array();
        return $ret;
    }

    /* get device info */

    public function get_device_info($user_id, $user_type) {

        $val = $this->db->select('*')->from('device_details')->where('user_id', $user_id)->where('type', $user_type)->get()->row_array();
        return $val;
    }

    public function get_device_info_multiple($user_id, $user_type) {
        $val = $this->db->select('*')->from('device_details')->where('user_id', $user_id)->where('type', $user_type)->get()->result_array();
        return $val;
    }

    /* get user infermation */

    public function get_user_info($user_id, $user_type) {

        if ($user_type == 2) {
            $val = $this->db->select('*')->from('users')->where('id', $user_id)->where('type', $user_type)->get()->row_array();
        } else {
            $val = $this->db->select('*')->from('providers')->where('id', $user_id)->where('type', $user_type)->get()->row_array();
        }

        return $val;
    }

    /* insert Notification infos */

    public function insert_notification($sender, $receiver, $message) {
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

    public function get_notification_info($token) {
        $ret = $this->db->select('*')->
                        from('notification_table')->
                        where('receiver', $token)->
                        where('status', 1)->
                        order_by('notification_id', 'DESC')->
                        get()->result_array();
        $user_info = $this->get_token_info($token);
        $notification = [];
        if (!empty($ret)) {
            foreach ($ret as $key => $value) {
                $notification[$key]['name'] = !empty($user_info->name) ? $user_info->name : '';
                $notification[$key]['message'] = !empty($value['message']) ? $value['message'] : '';
                $notification[$key]['profile_img'] = !empty($user_info->profile_img) ? $user_info->profile_img : '';
                $notification[$key]['utc_date_time'] = !empty($value['utc_date_time']) ? $value['utc_date_time'] : '';
            }
        }
        return $notification;
    }

    /* check device token */

    public function is_check_divesToken($device_token) {
        $ret = $this->db->select('*')->
                        from('device_details')->
                        where('device_id', $device_token)->
                        get()->row();

        if (empty($ret)) {
            return true;
        } else {
            return false;
        }
    }

    /* get user type and insert */

    public function insert_device_info($data) {

        $user_info = $this->get_token_info($data['user_token']);

        $data = array(
            'user_id' => $user_info->id,
            'device_type' => $data['device_type'],
            'device_id' => $data['device_token'],
            'created' => date('Y-m-d H:i:s'),
            'type' => $user_info->type
        );
        $val = $this->db->insert('device_details', $data);
        return $val;
    }

    public function update_device_details($user_data) {


        $record = $this->db->where('mobileno', $user_data['mobileno'])->get('providers')->row_array();

        $records = array();

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $this->db->where('type', 1);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '1';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where(array('user_id' => $user_id, 'type' => 1));

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }
        }
    }

    public function update_device_user($user_data) {


        $record = $this->db->where('mobileno', $user_data['mobileno'])->get('users')->row_array();

        $records = array();

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $this->db->where('type', 2);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '2';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where(array('user_id' => $user_id, 'type' => 2));

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }
        }
    }

    public function logout($token = '', $device_type, $deviceid) {

        $result = 0;


        if (!empty($token)) {

            $device_id = $deviceid;

            $device_type = strtolower($device_type);

            $user_id = $this->get_user_id_using_token($token);



            $this->db->where(array('device_id' => $device_id, 'device_type' => $device_type));

            $this->db->delete('device_details');

            $last_login = date('Y-m-d H:i:s');

            $this->db->where('token', $token);

            $this->db->update('users', array('last_login' => $last_login));
            $this->db->update('providers', array('last_login' => $last_login));
            $result = $this->db->affected_rows();
        }




        return $result;
    }

    /* wallet information */

    public function get_wallet($token) {
        $val = $this->db->select('*')->from('wallet_table')->where('token', $token)->get()->row();
        $wallet = [];
        $setting_currency = '';
        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if (!empty($result)) {
            foreach ($result as $data) {
                if ($data['key'] == 'currency_option') {
                    $setting_currency = $data['value'];
                }
            }
        }
    

        /* sum of totAL wallet */
            if($val->type == '1'){
            $provider_currency = get_api_provider_currency($val->user_provider_id);
            $UserCurrency = $provider_currency['user_currency_code'];
            }else{
            $provider_currency = get_api_user_currency($val->user_provider_id);
            $UserCurrency = $provider_currency['user_currency_code'];
            }
    

        $wallet_tot = $this->db->select('sum(credit_wallet)as total_credit,sum(debit_wallet)as total_debit')->from('wallet_transaction_history')->
                        where('token', $token)->order_by('id', 'DESC')->
                        get()->row_array();
    

        if (!empty($val)) {

            $wallet['id'] = $val->id;
            $wallet['token'] = $val->token;
            $wallet['type'] = $val->type;
            $wallet['wallet_amt'] = strval(abs($val->wallet_amt));
            $wallet['currency'] = currency_code_sign($UserCurrency);
            $wallet['currency_code'] = $val->currency_code;
            $wallet['total_credit'] = (!empty($wallet_tot['total_credit'])) ? strval($wallet_tot['total_credit']) : 0;
            $wallet['total_debit'] = (!empty($wallet_tot['total_debit'])) ? strval($wallet_tot['total_debit']) : 0;
        }
        if (!empty($wallet)) {
            return $wallet;
        } else {
            $wallet['id'] = '';
            $wallet['token'] = '';
            $wallet['type'] = '';
            $wallet['wallet_amt'] = '';
            $wallet['currency'] = '';
            $wallet['currency_code'] = '';
            $wallet['total_credit'] = '';
            $wallet['total_debit'] = '';
            return $wallet;
        }
    }

    /* wallet update */

    public function update_wallet($inputs, $where) {

        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('wallet_table');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    public function update_customer_card($inputs, $where) {

        $this->db->set($inputs);
        $this->db->where($where);
        $this->db->update('stripe_customer_card_details');
        return $this->db->affected_rows() != 0 ? true : false;
    }

    /* get wallet history */

    public function get_wallet_history_info($token) {
        $wallet = $this->db->select('id,token,payment_detail,user_provider_id,type,current_wallet,currency_code,credit_wallet,debit_wallet,avail_wallet,total_amt,fee_amt,reason,created_at')->from('wallet_transaction_history')->
                        where('token', $token)->order_by('id', 'DESC')->
                        get()->result_array();
        return $wallet;
    }

    /* get customer based savedcard */

    public function get_customer_based_card_list($token) {

        return $this->db->get_where('stripe_customer_card_details', array('status' => 1, 'user_token' => $token))->result_array();
    }

    /* get provider based savedcard */

    public function get_provider_based_card_list($token) {

        return $this->db->get_where('stripe_provider_card_details', array('status' => 1, 'user_token' => $token))->result_array();
    }

    /* history updated */

    public function booking_wallet_history_flow($booking_id, $token) {
        if (!empty($booking_id)) {
            $booking = $this->get_book_info_b($booking_id);

            if (!empty($booking)) {

                $user_info = $this->api->get_token_info($token);

                $wallet = $this->api->get_wallet($token);

                $curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $booking['currency_code']);

                /* wallet infos */

                $history_pay['token'] = $token;
				$history_pay['currency_code']=$booking['currency_code'];
                $history_pay['user_provider_id'] = $user_info->id;
                $history_pay['type'] = $user_info->type;
                $history_pay['tokenid'] = $booking_id;
                $history_pay['payment_detail'] = json_encode($booking); //response
                $history_pay['charge_id'] = $booking['provider_id'];
                $history_pay['transaction_id'] = $booking_id;
                $history_pay['exchange_rate'] = 0;
                $history_pay['paid_status'] = 'pass';
                $history_pay['cust_id'] = 'Self';
                $history_pay['card_id'] = 'Self';
                $history_pay['total_amt'] = $booking['amount'] * 100;
                $history_pay['fee_amt'] = 0;
                $history_pay['net_amt'] = $booking['amount'] * 100;
                $history_pay['amount_refund'] = 0;
                $history_pay['current_wallet'] = $curren_wallet;
                $history_pay['credit_wallet'] = 0;
                $history_pay['debit_wallet'] = ($booking['amount']);
                $history_pay['avail_wallet'] = $curren_wallet - ($booking['amount']);
                $history_pay['reason'] = BOOKED;
                $history_pay['created_at'] = date('Y-m-d H:i:s');

                if ($this->db->insert('wallet_transaction_history', $history_pay)) {
                    /* update wallet table */
					$wallet_data['wallet_amt'] = get_gigs_currency($history_pay['avail_wallet'], $history_pay['currency_code'], $wallet['currency_code']);
                    $wallet_data['updated_on'] = date('Y-m-d H:i:s');
                    $WHERE = array('token' => $token);
                    $result = $this->api->update_wallet($wallet_data, $WHERE);
                    /* payment on stripe */
                }
            }
        }
    }

    /* User accept flow and history */

    public function user_accept_history_flow($booking_id) {
        if (!empty($booking_id)) {
            $booking = $this->get_book_info_b($booking_id);

            if (!empty($booking)) {
                $provider = $this->get_user_info($booking['provider_id'], 1);

                $wallet = $this->api->get_wallet($provider['token']);

                $curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $booking['currency_code']);

                $query = $this->db->query('select * from admin_commission where admin_id=1');
                $amount = $query->row();
				if(!empty($amount)){
					$pertage = $amount->commission;
				}else{
					$pertage = 0;
				}
                
				if($pertage > 0) {
					$commission = ($booking['amount']) * $pertage / 100;
				} else {
					$commission = $pertage;
				}
                $ComAmount = $booking['amount'] - $commission;
				

                /* wallet infos */

                $history_pay['token'] = $provider['token'];
				$history_pay['currency_code']=$booking['currency_code'];
                $history_pay['user_provider_id'] = $provider['id'];
                $history_pay['type'] = $provider['type'];
                $history_pay['tokenid'] = $booking_id;
                $history_pay['payment_detail'] = json_encode($booking); //response
                $history_pay['charge_id'] = $booking['provider_id'];
                $history_pay['transaction_id'] = $booking_id;
                $history_pay['exchange_rate'] = 0;
                $history_pay['paid_status'] = 'pass';
                $history_pay['cust_id'] = 'Self';
                $history_pay['card_id'] = 'Self';
                $history_pay['total_amt'] = $booking['amount'] * 100;
                $history_pay['fee_amt'] = 0;
                $history_pay['net_amt'] = $booking['amount'] * 100;
                $history_pay['amount_refund'] = 0;
                $history_pay['current_wallet'] = $curren_wallet;
                $history_pay['credit_wallet'] = $ComAmount;
                $history_pay['debit_wallet'] = 0;
                $history_pay['avail_wallet'] = ($ComAmount) + $curren_wallet;
                $history_pay['reason'] = COMPLETE_PROVIDER;
                $history_pay['created_at'] = date('Y-m-d H:i:s');

                $walletHistory = $this->db->insert('wallet_transaction_history', $history_pay);

                if ($walletHistory) {
                    /* update wallet table */
                    $wallet_data['wallet_amt'] = get_gigs_currency($history_pay['avail_wallet'], $history_pay['currency_code'], $wallet['currency_code']);					
                    $wallet_data['updated_on'] = date('Y-m-d H:i:s');
                    $WHERE = array('token' => $provider['token']);
                    $result = $this->api->update_wallet($wallet_data, $WHERE);
					$vatper = 0;

                    /* payment on stripe */

                    $commissionInsert = [
                        'date' => date('Y:m:d'),
                        'provider' => $booking['provider_id'],
                        'user' => $booking['user_id'],
						'currency_code' => $booking['currency_code'],
                        'amount' => $booking['amount'],
                        'commission' => $pertage,
						'vat' => $vatper,
                    ];


                    return $result;
                }
            }
        }
    }

    /* provider reject */

    public function provider_reject_history_flow($booking_id) {
        if (!empty($booking_id)) {
            $booking = $this->get_book_info_b($booking_id);

            if (!empty($booking)) {
                $user = $this->get_user_info($booking['user_id'], 2);

                $wallet = $this->api->get_wallet($user['token']);

                $curren_wallet = get_gigs_currency($wallet['wallet_amt'], $wallet['currency_code'], $booking['currency_code']);

                /* wallet infos */

                $history_pay['token'] = $user['token'];
				$history_pay['currency_code'] = $booking['currency_code'];
                $history_pay['user_provider_id'] = $user['id'];
                $history_pay['type'] = $user['type'];
                $history_pay['tokenid'] = $booking_id;
                $history_pay['payment_detail'] = json_encode($booking); //response
                $history_pay['charge_id'] = $booking['provider_id'];
                $history_pay['transaction_id'] = $booking_id;
                $history_pay['exchange_rate'] = 0;
                $history_pay['paid_status'] = 'pass';
                $history_pay['cust_id'] = 'Self';
                $history_pay['card_id'] = 'Self';
                $history_pay['total_amt'] = $booking['amount'] * 100;
                $history_pay['fee_amt'] = 0;
                $history_pay['net_amt'] = $booking['amount'] * 100;
                $history_pay['amount_refund'] = 0;
                $history_pay['current_wallet'] = $curren_wallet;
                $history_pay['credit_wallet'] = ($booking['amount']);
                $history_pay['debit_wallet'] = 0;
                $history_pay['avail_wallet'] = ($booking['amount']) + $curren_wallet;
                $history_pay['reason'] = PROVIDER_REJECT;
                $history_pay['created_at'] = date('Y-m-d H:i:s');

                if ($this->db->insert('wallet_transaction_history', $history_pay)) {
                    /* update wallet table */
                    $wallet_data['wallet_amt'] = get_gigs_currency($history_pay['avail_wallet'], $history_pay['currency_code'], $wallet['currency_code']);
					
                    $wallet_data['updated_on'] = date('Y-m-d H:i:s');
                    $WHERE = array('token' => $user['token']);
                    $result = $this->api->update_wallet($wallet_data, $WHERE);
                    /* payment on stripe */
                    return $result;
                }
            }
        }
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

    public function UserShareCode($share_code) {
        if ($share_code) {

            $where = [
                'share_code' => $share_code
            ];
            $GetUsers = $this->db->where($where)->get('users')->row_array();

            if ($GetUsers) {
                $whr = [
                    'token' => $GetUsers['token'],
                    'user_provider_id' => $GetUsers['id']
                ];
                $GetWallet = $this->db->where($whr)->get('wallet_table')->row_array();
                $AddAmt = $GetWallet['wallet_amt'] + 1;

                $amtup = [
                    'wallet_amt' => $AddAmt,
                    'currency_code' => settings('currency')
                ];

                $updateAmount = $this->update_data('wallet_table', $amtup, $whr);
                if ($updateAmount) {

                    return $updateAmount;
                }
            } else {

                return false;
            }
        } else {
            $empty = 'Empty code';
            return $empty;
        }
    }

    public function ProviderShareCode($share_code) {
        if ($share_code) {

            $where = [
                'share_code' => $share_code
            ];
            $GetUsers = $this->db->where($where)->get('providers')->row_array();

            if ($GetUsers) {
                $whr = [
                    'token' => $GetUsers['token'],
                    'user_provider_id' => $GetUsers['id']
                ];
                $GetWallet = $this->db->where($whr)->get('wallet_table')->row_array();
                $AddAmt = $GetWallet['wallet_amt'] + 1;

                $amtup = [
                    'wallet_amt' => $AddAmt,
                    'currency_code' => settings('currency')
                ];

                $updateAmount = $this->update_data('wallet_table', $amtup, $whr);
                if ($updateAmount) {

                    return $updateAmount;
                }
            } else {

                return false;
            }
        } else {
            $empty = 'Empty code';
            return $empty;
        }
    }

    public function CountRows($table, $where = []) {
        $this->db->select('count(*) as count');
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return $result->count;
        } else {
            return FALSE;
        }
    }

    public function getSingleData($table, $select = [], $where = []) {
        $this->db->select($select);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $result = $query->row();
        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }
    public function paypal_payout($email,$amount){
		$token_request = $this->get_paypal_access_token();
		$query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if(!empty($result))
        { 
            foreach($result as $data1){
                if($data1['key'] == 'paypal_gateway'){
                 $environment = $data1['value'];
               }
		   
           }
        }
	   
        if($environment == "sandbox"){
            $payout_url = "https://api.sandbox.paypal.com/v1/payments/payouts";
        }else{
            $payout_url = "https://api.paypal.com/v1/payments/payouts";
	    }
		 
		$headers = $data = [];
		//--- Headers for payout request
		$headers[] = "Content-Type: application/json";
		$headers[] = "Authorization: Bearer $token_request->access_token";

		$time = time();
		//--- Prepare sender batch header
		$sender_batch_header["sender_batch_id"] = $time;
		$sender_batch_header["email_subject"]   = "Payout Received";
		$sender_batch_header["email_message"]   = "You have received a payout, Thank you for using our services";

		//--- First receiver
		$receiver["recipient_type"] = "EMAIL";
		$receiver["note"] = "Thank you for your services";
		$receiver["sender_item_id"] = $time++;
		$receiver["receiver"] = $email;
		$receiver["amount"]["value"] = $amount;
		$receiver["amount"]["currency"] = "USD";
		$items[] = $receiver;
		
		$data["sender_batch_header"] = $sender_batch_header;
		$data["items"] = $items;

		//--- Send payout request
		$payout = $this->paypal_curl_request($payout_url, "POST", $headers, json_encode($data));
		return $payout;
    } 
    
    public function paypal_curl_request($url, $method, $headers = [], $data = [], $curl_options = []){

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		//--- If any headers set add them to curl request
		if(!empty($headers)){
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		}

		//--- Set the request type , GET, POST, PUT or DELETE
		switch($method){
			case "POST":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			break;
		case "DELETE":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		}

		//--- If any data is supposed to be send along with request add it to curl request
		if($data){
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		//--- Any extra curl options to add in curl object
		if($curl_options){
			foreach($curl_options as $option_key => $option_value){
				curl_setopt($curl, $option_key, $option_value);
			}
		}

		$response = curl_exec($curl);
		$error = curl_error($curl);
		curl_close($curl);

		//--- If curl request returned any error return the error
		if ($error) {
		}
		//--- Return response received from call
		return $response;
    }
    public function get_paypal_access_token() {
        $query = $this->db->query("select * from system_settings WHERE status = 1");
        $result = $query->result_array();
        if(!empty($result))
        { 
            foreach($result as $data1){

    
                if($data1['key'] == 'paypal_appid'){
                  $paypal_appid = $data1['value'];
                }
    
                if($data1['key'] == 'paypal_appkey'){
                  $paypal_appkey = $data1['value'];
                }

    
                if($data1['key'] == 'live_paypal_appid'){
                  $live_paypal_appid = $data1['value'];
                }
    
                if($data1['key'] == 'live_paypal_appkey'){
                  $live_paypal_appkey = $data1['value'];
                }
                if($data1['key'] == 'paypal_gateway'){
                 $environment = $data1['value'];
               }
		   
           }
        }
	   
        if($environment == "sandbox"){
    		$clientId = $paypal_appid;
    		$clientSecret = $paypal_appkey;
    		$token_url = "https://api.sandbox.paypal.com/v1/oauth2/token";
        }else{
    		$clientId = $live_paypal_appid;
    		$clientSecret = $live_paypal_appkey;
    		$token_url = "https://api.paypal.com/v1/oauth2/token";
	    }
		
		//--- Headers for our token request
		$headers[] = "Accept: application/json";
		$headers[] = "Content-Type: application/x-www-form-urlencoded";

		//--- Data field for our token request
		$data = "grant_type=client_credentials";

		//--- Pass client id & client secrent for authorization
		$curl_options[CURLOPT_USERPWD] = $clientId . ":" . $clientSecret;

		$token_request = $this->paypal_curl_request($token_url, "POST", $headers, $data, $curl_options);
		$token_request1 = json_decode($token_request);
		if(isset($token_request1->error)){
			die("Paypal Token Error: ". $token_request1->error_description);
		}
		return $token_request1;
	}
	
	
	
	public function checkuserpwd($user_id,$current_password) {
        $this->db->where('id', $user_id);
        $this->db->where('password', $current_password);
        return $this->db->count_all_results('users');
    }
	
	public function checkpropwd($user_id,$current_password) {
	$this->db->where('id', $user_id);
	$this->db->where('password', $current_password);
	return $this->db->count_all_results('providers');
	}
	
	
	public function get_user_detailsby_email($email,$password,$user_data) {
        $record = $this->db->where('email', $email)->where('password', $password)->get('users')->row_array();

        $records = array();

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '2';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where('user_id', $user_id);

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }




            $subscribed_user = 1;

            $records = array('id' => $record['id'], 'token' => $record['token'], 'name' => $record['name'], 'mobileno' => $record['mobileno'], 'country_code' => $record['country_code'], 'otp' => $record['otp'], 'email' => $record['email'], 'profile_img' => $record['profile_img'], 'status' => $record['status'], 'created_at' => $record['created_at'], 'updated_at' => $record['updated_at'], 'type' => $record['type']);

            $records['id'] = $record['id'];
            $records['name'] = $record['name'];
            $records['mobileno'] = $record['mobileno'];
            $records['profile_img'] = $record['profile_img'];
            $records['country_code'] = $record['country_code'];
            $records['otp'] = $record['otp'];
            $records['status'] = $record['status'];
            $records['created_at'] = $record['created_at'];
            $records['updated_at'] = $record['updated_at'];
            $records['type'] = $record['type'];
            $records['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);
            $records['share_code'] = $record['share_code'];
        }


        return $records;
    }
	
	
	public function get_provider_details_by_email($email,$password,$user_data) {


        $this->db->select('providers.*, categories.category_name, subcategories.subcategory_name, categories.category_type');
        $this->db->join('categories', 'providers.category = categories.id', 'left');
        $this->db->join('subcategories', 'providers.subcategory = subcategories.id', 'left');
        $this->db->where(['providers.email'=>$email, 'providers.password'=>$password]);
        $record = $this->db->get('providers')->row_array();
        $records = array();

        if (!empty($record)) {

            $user_id = $record['id'];

            $count = 0;

            if (!empty($user_data['device_id'])) {

                $device_id = $user_data['device_id'];

                $this->db->where('user_id', $user_id);

                $this->db->where('device_id', $device_id);

                $count = $this->db->count_all_results('device_details');
            }



            if (!empty($user_data['device_type']) && !empty($user_data['device_id'])) {

                $device_type = strtolower($user_data['device_type']);

                $device_id = $user_data['device_id'];

                $date = date('Y-m-d H:i:s');
                $type = '1';


                if ($count == 0) {

                    $this->db->insert('device_details', array('user_id' => $user_id, 'device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                } else {

                    $this->db->where('user_id', $user_id);

                    $this->db->update('device_details', array('device_type' => $device_type, 'device_id' => $device_id, 'created' => $date, 'type' => $type));
                }
            }


            $this->db->select('subscription_id');

            $this->db->where('subscriber_id', $user_id);

            $subscription = $this->db->get('subscription_details')->row_array();


            if (!empty($subscription)) {

                $id = $subscription['subscription_id'];

                $this->db->select('id,subscription_name');

                $this->db->where('id', $id);

                $subscription = $this->db->get('subscription_fee')->row_array();

                $subscribed_user = 1;

                $subscribed_msg = $subscription['subscription_name'];
            } else {

                $subscribed_user = 0;

                $subscribed_msg = 'Free';
            }




            $records = array('id' => $record['id'], 'token' => $record['token'], 'name' => $record['name'], 'mobileno' => $record['mobileno'], 'country_code' => $record['country_code'], 'currency_code' => $record['currency_code'], 'share_code' => $record['share_code'], 'otp' => $record['otp'], 'email' => $record['email'], 'profile_img' => $record['profile_img'], 'status' => $record['status'], 'created_at' => $record['created_at'], 'updated_at' => $record['updated_at'], 'type' => $record['type']);

            $records['id'] = $record['id'];
            $records['name'] = $record['name'];
            $records['mobileno'] = $record['mobileno'];
            $records['country_code'] = $record['country_code'];
            $records['otp'] = $record['otp'];
            $records['profile_img'] = $record['profile_img'];
            $records['status'] = $record['status'];
            $records['created_at'] = $record['created_at'];
            $records['updated_at'] = $record['updated_at'];
            $records['type'] = $record['type'];
            $records['is_subscribed'] = "$subscribed_user";
            $records['subscription_details'] = $this->get_subscription_details_using_user_id($user_id);
            $records['share_code'] = $record['share_code'];
            $records['category'] = $record['category'];
            $records['category_name'] = $record['category_name'];
            $records['subcategory'] = $record['subcategory'];
            $records['subcategory_name'] = $record['subcategory_name'];
			
			$records['dob'] = ($record['dob'])?$record['dob']:'';
			
			if($record['category_type']=="1") $category_type_txt = "Men";
			else if($record['category_type']=="2") $category_type_txt = "Ladies";
			else if($record['category_type']=="3") $category_type_txt = "Both";
			else if($record['category_type']=="4") $category_type_txt = "Freelancer";
			$records['category_type'] = $record['category_type'];
			$records['category_type_txt'] = $category_type_txt;			
			
			$records['homeservice_fee'] = $record['homeservice_fee'];
			$records['homeservice_arrival'] = $record['homeservice_arrival'];
			$records['allow_rewards'] = $record['allow_rewards'];
			$records['booking_reward_count'] = $record['booking_reward_count'];
			$records['commercial_verify'] = $record['commercial_verify'];
			$records['commercial_reg_image'] = base_url().$record['commercial_reg_image'];
			$records['qrcode_path'] = base_url().$record['qrcode_path'];
            
						
			$records['account_holder_name'] = $record['account_holder_name'];	
			$records['account_number'] = $record['account_number'];	
			$records['account_iban'] = $record['account_iban'];	
			$records['bank_name'] = $record['bank_name'];
			$records['bank_address'] = $record['bank_address'];	
			$records['sort_code'] = $record['sort_code'];	
			$records['routing_number'] = $record['routing_number'];	
			$records['account_ifsc'] = $record['account_ifsc'];
        }

        return $records;
    }
	//Rintu
    public function service_offer_history($limit, $end, $where)
    {
        $this->db->select('service_offers.id, service_offers.start_date, service_offers.end_date, service_offers.start_time, service_offers.end_time, service_offers.offer_percentage,service_offers.created_at as createdDate, services.service_title, services.currency_code, services.service_amount');
        $this->db->join('services','service_offers.service_id=services.id','left');
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $records = $this->db->get('service_offers')->result_array();
        return $records;
    }
    public function my_shop_list($limit, $end, $where)
    {
        $this->db->select("shops.id, shops.shop_code, shops.shop_name, shops.country_code, shops.tax_allow,shops.tax_number,shops.contact_no, shops.email, shops.address, country_table.country_name, state.name as state_name, city.name as city_name, shops.postal_code, shops.shop_location, shops.shop_latitude, shops.shop_longitude, categories.category_name, subcategories.subcategory_name, sub_subcategories.sub_subcategory_name, IF(shops.all_days=1,'Yes','No') as all_days, shops.availability, CASE
        WHEN shops.status = 1 THEN 'Active' WHEN shops.status = 2 THEN 'Inactive' 
        ELSE 'Deleted'
    END AS status, shops.category, shops.subcategory, shops.sub_subcategory, providers.currency_code,shops.country, shops.state, shops.city, shops.description, providers.name as provider_name, CONCAT('".base_url()."', providers.profile_img) as profile_img,shops.total_views", false);
        $this->db->join('providers','shops.provider_id=providers.id','left');
        $this->db->join('country_table','shops.country=country_table.id','left');
        $this->db->join('state','shops.state=state.id','left');
        $this->db->join('city','shops.city=city.id','left');
        $this->db->join('categories','shops.category=categories.id','left');
        $this->db->join('subcategories','shops.subcategory=subcategories.id','left');
        $this->db->join('sub_subcategories','shops.sub_subcategory=sub_subcategories.id','left');
        $this->db->where($where);
        $this->db->where('shops.status!=',0);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $records = $this->db->get('shops')->result_array();
        $i=0;
        $currency_symbol = currency_code_symbol();
        foreach ($records as $val) 
        {
            $records[$i]['currency_symbol'] = $currency_symbol[$val['currency_code']];
            $this->db->select("CONCAT('".base_url()."', shop_image) as shop_image, CONCAT('".base_url()."', shop_details_image) as shop_details_image, CONCAT('".base_url()."', thumb_image) as thumb_image, CONCAT('".base_url()."', mobile_image) as mobile_image", false);
            $this->db->where(['shop_id'=>$val['id'], 'status'=>1]);
            $records[$i]['gallery'] = $this->db->get('shops_images')->result_array();
            $services_lists = $this->db->select('id, user_id, service_title, currency_code, service_amount, duration, duration_in')->where('status', 1)->where("FIND_IN_SET('".$val['id']."', shop_id)")->get('services')->result_array();
            $product_lists = $this->db->select('id')->where("shop_id",$val['id'])->get('products')->result_array();
            $records[$i]['tax_allow'] = ($val['tax_allow']==1)?"Yes":"No";
            $records[$i]['services_lists'] = $services_lists;
            $records[$i]['servicesCount'] = count($services_lists);
            $records[$i]['productCount'] = count($product_lists);
            $i++;
        }
        return $records;
    }
    public function my_staff_list($limit, $end, $where)
    {
        $this->db->select("id, provider_id, first_name, last_name, country_code, contact_no, email, dob, gender, CONCAT('".base_url()."', profile_img) as profile_img, designation, experience as exp_year, exp_month, IF(status=1,'Active','Inactive') as status", false);
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $records = $this->db->get('employee_basic_details')->result_array();
        return $records;
    }
    public function staff_details($where)
    {
        $this->db->select("employee_basic_details.id, employee_basic_details.first_name, employee_basic_details.last_name, employee_basic_details.country_code, employee_basic_details.contact_no, employee_basic_details.email, employee_basic_details.dob, employee_basic_details.gender, CONCAT('".base_url()."', employee_basic_details.profile_img) as profile_img, employee_basic_details.address, employee_basic_details.country, employee_basic_details.state, employee_basic_details.city, employee_basic_details.postal_code, employee_basic_details.emp_token, employee_basic_details.shop_id, employee_basic_details.category, employee_basic_details.subcategory, employee_basic_details.sub_subcategory, employee_basic_details.designation, employee_basic_details.experience as exp_year, employee_basic_details.exp_month, employee_basic_details.about_emp, IF(employee_basic_details.all_days=1,'Yes','No') as all_days, employee_basic_details.availability, IF(employee_basic_details.shop_service=1,'Yes','No') as shop_service, IF(employee_basic_details.home_service=1,'Yes','No') as home_service, employee_basic_details.home_service_area, country_table.country_name, state.name as state_name, city.name as city_name, categories.category_name, subcategories.subcategory_name, shops.shop_name", false);
        $this->db->join('country_table','employee_basic_details.country=country_table.id','left');
        $this->db->join('state','employee_basic_details.state=state.id','left');
        $this->db->join('city','employee_basic_details.city=city.id','left');
        $this->db->join('categories','employee_basic_details.category=categories.id','left');
        $this->db->join('subcategories','employee_basic_details.subcategory=subcategories.id','left');
        $this->db->join('shops','employee_basic_details.shop_id=shops.id','left');
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $records = $this->db->get('employee_basic_details')->row_array();
        $ssc_arr = explode(',',$records['sub_subcategory']);
        $i=0;
        foreach ($ssc_arr as $val) {
           $this->db->select("id, sub_subcategory_name");
            $this->db->where('id', $val);
            $records['subsubcategory_list'][$i] = $this->db->get('sub_subcategories')->row_array();
            $i++;
        }
		$records['services_list'] = $this->db->where('emp_id',$records['id'])->get('employee_services_list')->result_array();
        return $records;
    }
    public function service_coupon_history($limit, $end, $where)
    {
        $this->db->select("service_coupons.id, service_coupons.service_id, service_coupons.coupon_name, service_coupons.coupon_type, IF(service_coupons.coupon_type=1,'Percentage','Fixed Amount') as coupon_type_text, service_coupons.coupon_percentage, service_coupons.coupon_amount, service_coupons.start_date, service_coupons.end_date, service_coupons.valid_days, service_coupons.user_limit, service_coupons.user_limit_count, service_coupons.description, services.service_title, CASE
        WHEN service_coupons.status = 1 THEN 'Active' WHEN service_coupons.status = 2 THEN 'Inactive' WHEN service_coupons.status = 3 THEN 'Expired' 
        ELSE 'Deleted'
    END AS status", false);
        $this->db->join('services','service_coupons.service_id=services.id','left');
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $records = $this->db->get('service_coupons')->result_array();
        return $records;
    }
    public function product_cart_list($where_data, $UserCurrency, $currency_sign) 
    {
        $this->db->select('product_cart.id, product_cart.order_id, product_cart.user_id, product_cart.product_id, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, product_cart.product_price, product_cart.qty, product_cart.product_total, IF(product_cart.status=0, "Active", "Inactive") as status, products.product_name, CONCAT("'.base_url().'", product_images.product_image) as product_image, shops.provider_id, shops.shop_name, shops.provider_id');
        $this->db->join('products','product_cart.product_id=products.id','left');
        $this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
        $this->db->join('shops','product_cart.shop_id=shops.id','left');
        $this->db->where($where_data);
        $this->db->order_by('product_cart.id','DESC');
        $query =  $this->db->get('product_cart')->result_array();
        return $query;
    }
    public function billing_address_list($where_data)
    {
        $this->db->select('user_billing_details.id, user_billing_details.user_id, user_billing_details.full_name, user_billing_details.phone_no, user_billing_details.email_id, user_billing_details.address, user_billing_details.country_id, user_billing_details.state_id, user_billing_details.city_id, country_table.country_name, city.name as city_name, state.name as state_name, user_billing_details.default_address, user_billing_details.zipcode');
        $this->db->join('country_table','user_billing_details.country_id=country_table.id','left');
        $this->db->join('state','user_billing_details.state_id=state.id','left');
        $this->db->join('city','user_billing_details.city_id=city.id','left');
        $this->db->where($where_data);
        $this->db->order_by('user_billing_details.id','DESC');
        $query =  $this->db->get('user_billing_details')->result_array();
        return $query;
    }
    public function product_order_summary($where_data, $UserCurrency, $currency_sign)
    {
        $this->db->select('id, order_code, total_products, total_qty, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, address_type, billing_details_id, created_on, total_amt');
        $this->db->where($where_data);
        $query =  $this->db->get('product_order')->row_array();
        //$query['total_amt'] = get_gigs_currency($query['tot_amount'], $query['currency_code'], $UserCurrency);
        return $query;
    }
    public function user_orders_list($limit, $end, $search, $where, $type, $UserCurrency, $currency_sign)
    {
        if ($type == 'count') 
        {
            $this->db->select('COUNT(product_cart.id) as cnt', false);
        }
        else
        {
            $this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, "'.$currency_sign.'" as currency, products.currency_code, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, CASE
        WHEN product_cart.delivery_status = 1 THEN "Order Placed" WHEN product_cart.delivery_status = 2 THEN "Order Confirmed" WHEN product_cart.delivery_status = 3 THEN "Shipped" WHEN product_cart.delivery_status = 4 THEN "Out of Delivery" WHEN product_cart.delivery_status = 5 THEN "Delivered" WHEN
            product_cart.delivery_status = 6 THEN "Cancelled By User" 
        ELSE "Cancelled By Provider"
    END AS delivery_status_text, product_cart.cancel_reason, products.product_name, CONCAT("'.base_url().'", product_images.product_image) as product_image, product_units.unit_name, shops.shop_name, product_order.order_code,product_order.payment_type', false);
        }
        $this->db->select('user_billing_details.address,product_order.address_type as orderDeliverTo');
        $this->db->join('product_order','product_cart.order_id=product_order.id','left');
        $this->db->join('user_billing_details','product_order.billing_details_id=user_billing_details.id','left');
        $this->db->join('products','product_cart.product_id=products.id','left');
        $this->db->join('shops','product_cart.shop_id=shops.id','left');
        $this->db->join('product_units','products.unit=product_units.id','left');
        $this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
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

            //$query['product_price'] = (string) get_gigs_currency($query['prices'], $query['currency_code'],$UserCurrency);
        //$query['product_total'] = (string) get_gigs_currency($query['total'], $query['currency_code'],$UserCurrency);
        }

        return $query;
    }
    public function provider_orders_list($limit, $end, $search, $where, $type, $UserCurrency, $currency_sign)
    {
        if ($type == 'count') 
        {
            $this->db->select('COUNT(product_cart.id) as cnt', false);
        }
        else
        {
            $this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, CASE
        WHEN product_cart.delivery_status = 1 THEN "Order Placed" WHEN product_cart.delivery_status = 2 THEN "Order Confirmed" WHEN product_cart.delivery_status = 3 THEN "Shipped" WHEN product_cart.delivery_status = 4 THEN "Out of Delivery" WHEN product_cart.delivery_status = 5 THEN "Delivered" WHEN
            product_cart.delivery_status = 6 THEN "Cancelled By User" 
        ELSE "Cancelled By Provider"
    END AS delivery_status_text, product_cart.cancel_reason, products.product_name, CONCAT("'.base_url().'", product_images.product_image) as product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno', false);
        }
        $this->db->select('user_billing_details.address,product_order.address_type as orderDeliverTo,product_order.payment_type');
        //get_gigs_currency(product_cart.product_price, product_cart.product_currency, "'.$UserCurrency.'") as
        //get_gigs_currency(product_cart.product_total, product_cart.product_currency, "'.$UserCurrency.'") as 
        $this->db->join('product_order','product_cart.order_id=product_order.id','left');
        $this->db->join('user_billing_details','product_order.billing_details_id=user_billing_details.id','left');
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
    public function product_cart_details($where)
    {
        $this->db->select('product_cart.id, product_cart.order_id, product_cart.user_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.status, product_cart.delivery_status, product_cart.cancel_reason, product_cart.admin_change_status, product_cart.admin_comments, shops.provider_id, shops.shop_name, products.product_name');
        $this->db->join('products','product_cart.product_id=products.id','left');
        $this->db->join('shops','product_cart.shop_id=shops.id','left');
        $this->db->where($where);
        $query =  $this->db->get('product_cart')->row_array();
        return $query;
    }
    public function featured_category_list($limit, $end, $wcat)
    {
        $this->db->select('c.id, c.category_name, CONCAT("'.base_url().'", c.category_image) as category_image, (SELECT COUNT(s.id) FROM services AS s WHERE s.category=c.id AND s.status=1 ) AS service_count');
        $this->db->where($wcat);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $query =  $this->db->get('categories as c')->result_array();
        if($query){
            foreach($query as $key=>$val){
                $cat_services = $this->db->where('category', $val['id'])->count_all_results('services');
                $query[$key]['service_count'] =  $cat_services;
            }
        }
        return $query;
    }
    public function sub_category_list($limit, $end, $where, $type)
    {
        if ($type == 'count') 
        {
            $this->db->select('COUNT(subcategories.id) as cnt', false);
        }
        else
        {
            $this->db->select('subcategories.id, subcategories.subcategory_name, CONCAT("'.base_url().'", subcategories.subcategory_image) as subcategory_image, categories.category_name, (SELECT COUNT(s.id) FROM services AS s WHERE s.subcategory=subcategories.id AND s.status=1 ) AS service_count');
        }
        $this->db->join('categories','subcategories.category=categories.id','LEFT');
        $this->db->where($where);
        if ($type == 'count')  
        {
            $query =  $this->db->get('subcategories')->row_array();
        }
        else
        {
            if (isset($limit) && !empty($limit)) {
                $this->db->limit($limit,$end);
            }
            $this->db->order_by('subcategories.id','DESC');
            $query =  $this->db->get('subcategories')->result_array();
        }
        return $query;
    }
    public function popular_services_list($limit, $page, $where, $search, $whrIn, $latitude, $longitude, $UserCurrency, $currency_sign, $type, $order_by)
    {
        $radius = (settingValue('radius'))?settingValue('radius'):'0';
        $this->db->select('categories.id as category_id,shops.shop_name,services.id, services.service_title, providers.name as provider_name,services.about as description, services.service_location, services.total_views, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code,services.service_amount, categories.category_name, CONCAT("'.base_url().'", services_image.service_image) as service_image, CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img,services.service_latitude,services.service_longitude');
        if ($latitude!='' && $longitude!='') {
            $this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - services.service_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(services.service_latitude * pi()/180) * POWER(SIN(($longitude  - services.service_longitude) * pi()/180 / 2), 2) )) AS distance");
        }
        
        $this->db->join('providers','services.user_id=providers.id','left');
        $this->db->join('shops','services.shop_id=shops.id','left');
        $this->db->join('categories','services.category=categories.id','left');
        $this->db->join('services_image','services.id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
        $this->db->where($where);
        if(isset($whrIn) && !empty($whrIn))
        {
            foreach ($whrIn as $win) {
                if ($win['type'] == 'In') {
                    $this->db->where_in($win['column_name'],$win['value']);
                }
                else
                {
                    $this->db->where_not_in($win['column_name'],$win['value']);
                }
            }
        }
        if (isset($search) && !empty($search)) 
        {
            foreach ($search as $key => $val) 
            {
                $this->db->like($key, $val);
            }
        }

        if($page>=1){
            $page = $page - 1 ;
            }
            $page =  ($page * $limit);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$page);
        }
        //order by conditions
        if ($type == 1) //featured
        {
            $this->db->order_by('services.total_views','DESC');
        }
        if ($type == 3) //new
        {
            $this->db->order_by('services.id','DESC');
        }
        if ($order_by == 1) { //price low to high
            $this->db->order_by('services.service_amount','ASC');
        }
        else if ($order_by == 2) { //price high to low
            $this->db->order_by('services.service_amount','DESC');
        }
        if ($latitude!='' && $longitude!='') {
            $this->db->having('distance <=',$radius);
        }
        $query =  $this->db->get('services')->result_array();
        $i=0;
        foreach ($query as $val) 
        {
            $this->db->select('COALESCE(AVG(rating),0) as avg_rating');
            $this->db->where(array('service_id' => $val['id'], 'status' => 1));
            $rating = $this->db->get('rating_review')->row_array()['avg_rating'];
            $query[$i]['rating'] = number_format($rating,1);
            $favo = 0;
            $query[$i]['service_favorite'] = $favo;
            $i++;
        }
        
        return $query;
    }
    public function featured_shops_list($limit, $page, $where, $search, $whrIn, $latitude, $longitude, $order_by)
    {
        $radius = (settingValue('radius'))?settingValue('radius'):'0';
        $this->db->select('categories.category_name,categories.id as category_id,shops.id, shops.shop_name, shops.shop_location, shops.shop_latitude, shops.shop_longitude, CONCAT("'.base_url().'", shops_images.shop_image) as shop_image, providers.name as provider_name, CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img, shops.shop_latitude,shops.shop_longitude');
        $this->db->select('(SELECT COUNT(s.id) FROM services AS s WHERE s.shop_id=shops.id AND s.status=1 ) AS service_count');
        $this->db->select('(SELECT COUNT(p.id) FROM products AS p WHERE p.shop_id=shops.id AND p.status=1 ) AS product_count');
        $this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - shops.shop_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(shops.shop_latitude * pi()/180) * POWER(SIN(($longitude  - shops.shop_longitude) * pi()/180 / 2), 2) )) AS distance");
        $this->db->join('subscription_details','shops.provider_id=subscription_details.subscriber_id','left');
        $this->db->join('providers','shops.provider_id=providers.id','left');
        $this->db->join('categories','shops.category=categories.id','left');
        $this->db->join('shops_images','shops.id=shops_images.shop_id && shops_images.id=(select id from shops_images as si where si.shop_id = shops_images.shop_id ORDER BY si.id DESC LIMIT 1)','LEFT');
        $this->db->where($where);
        if($page>=1){
            $page = $page - 1 ;
            }
            $page =  ($page * $limit);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$page);
        }
        $this->db->order_by($order_by['column_name'], $order_by['order']);
        $this->db->having('distance <=',$radius);
        $query =  $this->db->get('shops')->result_array();
        //echo $this->db->last_query(); exit;
        if($query){
            foreach($query as $key => $val){
                $query[$key]['distance'] = number_format($val['distance'],2);
            }
        }
        return $query;
    }
    public function offered_services_list($limit, $end ,$where, $search, $whrIn,$latitude, $longitude, $UserCurrency, $currency_sign, $order_by)
    {
        $cur_date = date("Y-m-d");
        $current_time = date("H:i");
        $radius = (settingValue('radius'))?settingValue('radius'):'0';
        $this->db->select('service_offers.service_id as id, service_offers.offer_percentage, services.service_title, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, services.service_location, providers.name as provider_name, CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img, categories.category_name, services.service_amount, CONCAT("'.base_url().'", services_image.service_image) as service_image, services.service_latitude,services.service_longitude');
        //, get_gigs_currency(services.service_amount, services.currency_code, "'.$UserCurrency.'") as service_amount
        $this->db->select("1.609344 * 3956 * 2 * ASIN(SQRT( POWER(SIN(($latitude  - services.service_latitude) *  pi()/180 / 2), 2) +COS( $latitude  * pi()/180) * COS(services.service_latitude * pi()/180) * POWER(SIN(($longitude  - services.service_longitude) * pi()/180 / 2), 2) )) AS distance");
        $this->db->select('shops.shop_name');
        $this->db->join('services','service_offers.service_id=services.id','LEFT');
        $this->db->join('shops','shops.id=services.shop_id','LEFT');
        $this->db->join('providers','service_offers.provider_id=providers.id','LEFT');
        $this->db->join('categories','services.category=categories.id','LEFT');
        $this->db->join('services_image','service_offers.service_id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
        $this->db->where($where);
        $this->db->where("'$cur_date' BETWEEN service_offers.start_date AND service_offers.end_date");
        $this->db->where("'$current_time' BETWEEN service_offers.start_time AND service_offers.end_time");
        if(isset($whrIn) && !empty($whrIn))
        {
            foreach ($whrIn as $win) {
                if ($win['type'] == 'In') {
                    $this->db->where_in($win['column_name'],$win['value']);
                }
                else
                {
                    $this->db->where_not_in($win['column_name'],$win['value']);
                }
            }
        }
        if (isset($search) && !empty($search)) 
        {
            foreach ($search as $key => $val) 
            {
                $this->db->like($key, $val);
            }
        }
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        //order by conditions
        if ($order_by == 1) { //price low to high
            $this->db->order_by('services.service_amount','ASC');
        }
        else if ($order_by == 2) { //price high to low
            $this->db->order_by('services.service_amount','DESC');
        }
        else
        {
            $this->db->order_by('services.id','DESC');
        }
        $this->db->having('distance <=',$radius);
        $query = $this->db->get('service_offers')->result_array();
        $i=0;
        foreach ($query as $val) 
        {
            $this->db->select('COALESCE(AVG(rating),0) as avg_rating');
            $this->db->where(array('service_id' => $val['id'], 'status' => 1));
            $rating = $this->db->get('rating_review')->row_array()['avg_rating'];
            $query[$i]['rating'] = number_format($rating,1);	

            
            $favo = 0;
            $query[$i]['service_favorite'] = $favo;
            $i++;
        }
        return $query;
    }
    public function service_details($where_data, $UserCurrency, $currency_sign)
    {
        $this->db->select('shops.contact_no as mobileNumber,services.id, services.user_id,services.about as description, services.service_title, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, services.service_amount as amt, services.service_sub_title, services.service_location, services.service_latitude, services.service_longitude, providers.name as provider_name, CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img, providers.email as provider_email, categories.category_name, subcategories.subcategory_name, sub_subcategories.sub_subcategory_name'); 
        $this->db->select('(select COUNT(rating) from rating_review where rating_review.service_id=services.id) as rating_count');
        $this->db->join('shops','services.shop_id=shops.id','LEFT');
        $this->db->join('providers','services.user_id=providers.id','LEFT');
        $this->db->join('categories','services.category=categories.id','LEFT');
        $this->db->join('subcategories','services.subcategory=subcategories.id','LEFT');
        $this->db->join('sub_subcategories','services.sub_subcategory=sub_subcategories.id','LEFT');
        $this->db->where($where_data);
        $query = $this->db->get('services')->row_array();
        $query['service_amount'] = get_gigs_currency($query['amt'], $query['currency_code'], $UserCurrency);
        if (!empty($query)) {
            $this->db->select('COALESCE(AVG(rating),0) as avg_rating');
            $this->db->where(array('service_id' => $query['id'], 'status' => 1));
            $query['rating'] = $this->db->get('rating_review')->row_array()['avg_rating'];
            $this->db->select('CONCAT("'.base_url().'", service_image) as service_image');
            $this->db->where(array('service_id' => $query['id'], 'status' => 1));
            $query['service_gallery'] = $this->db->get('services_image')->result_array();
            //Additional Services
            $this->db->select('id, service_name, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, amount, duration, duration_in, notes as description, CONCAT("'.base_url().'", additional_service_image) as additional_service_image');
            $this->db->where(array('service_id' => $query['id'], 'status' => 1));
            $query['additional_services'] = $this->db->get('additional_services')->result_array();
        }
        return $query;
    }
    public function check_current_offer($where_data)
    {
        $cur_date = date("Y-m-d");
        $current_time = date("H:i");
        $this->db->select('offer_percentage, end_time');
        $this->db->where($where_data);
        $this->db->where("'$cur_date' BETWEEN start_date AND end_date");
        $this->db->where("'$current_time' BETWEEN start_time AND end_time");
        $query = $this->db->get('service_offers')->row_array();
        return $query;
    }
    public function rating_review_list($where_data)
    {
        $this->db->select('rating_review.id, rating_review.rating, rating_review.review, users.name as user_name, DATE_FORMAT(rating_review.created, "%d-%m-%Y %h:%i %p") as created, CONCAT("'.base_url().'", users.profile_img) as profile_img');
        $this->db->join('users','rating_review.user_id=users.id','LEFT');
        $this->db->where($where_data);
        $query = $this->db->get('rating_review')->result_array();
        return $query;
    }
    public function invoice_list($limit, $end, $where, $UserCurrency, $currency_sign)
    {
        $this->db->select('book_service.id, book_service.service_id, book_service.provider_id, book_service.user_id, book_service.shop_id, book_service.staff_id, book_service.location, book_service.service_date, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, book_service.amount, book_service.from_time, book_service.to_time, book_service.cod, book_service.home_service, book_service.request_date, book_service.request_time,  book_service.updated_on, services.service_title, services.service_amount as amt, CONCAT("'.base_url().'", services_image.service_image) as service_image, providers.name as provider_name, CONCAT("'.base_url().'", providers.profile_img) as provider_profile_img, users.name as user_name, CONCAT("'.base_url().'", users.profile_img) as user_profile_img');
        $this->db->join('services','book_service.service_id=services.id','left');
        $this->db->join('services_image','book_service.service_id=services_image.service_id && services_image.id=(select id from services_image as si where si.service_id = services_image.service_id ORDER BY si.id DESC LIMIT 1)','LEFT');
        $this->db->join('providers','book_service.provider_id=providers.id','LEFT');
        $this->db->join('users','book_service.user_id=users.id','LEFT');
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $this->db->order_by('book_service.id','DESC');
        $query =  $this->db->get('book_service')->result_array();
        //$query['booking_amount'] = get_gigs_currency($query['amount'], $query['currency_code'], $UserCurrency);
        //$query['service_amount'] = get_gigs_currency($query['amt'], $query['currency_code'], $UserCurrency);
        return $query;
    }
    function checkexistoffer($service_id, $start_date, $end_date) {
        $this->db->select("id");
        $this->db->where('service_id',$service_id);
        $this->db->where("df",0);
        $this->db->where('((start_date BETWEEN "'.$start_date. '" and "'.$end_date.'") or (end_date BETWEEN "'.$start_date. '" and "'.$end_date.'"))');
        $query = $this->db->get('service_offers')->row_array();
        return $query;
    }
    //Default
    public function getsingletabledata($table_name, $where_data, $search, $primary_id, $order_by,$type)
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
    public function getCountRows($table_name,$where_data,$column_name, $search='', $whrIn = array()){
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
    public function delete_data($table_name,$where)
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
	
	public function my_products_list($limit, $end, $where, $ProviderCurrency, $currency_sign)
    {
        //echo '<pre>'; print_r($where); exit;
        $this->db->select('products.id, products.product_name, "'.$currency_sign.'" as currency, "'.$ProviderCurrency.'" as currency_code, products.currency_code as product_currency, products.price as product_price,  products.sale_price as sale_price,  products.discount as product_discount, products.short_description, product_categories.category_name, product_subcategories.subcategory_name, product_images.product_image');
        $this->db->join('product_categories','products.category=product_categories.id','left');
        $this->db->join('product_subcategories','products.subcategory=product_subcategories.id','left');
        $this->db->join('product_images','products.id=product_images.product_id and primary_img=1','left');
        $this->db->where($where);
        if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $this->db->order_by('products.id','DESC');
        $this->db->group_by('product_images.product_id');
        $query =  $this->db->get('products')->result_array();
        
        //$query['price'] = get_gigs_currency($query['product_price'], $query['product_currency'], $ProviderCurrency);
        //$query['sale_price'] = get_gigs_currency($query['sale_price'], $query['product_currency'], $ProviderCurrency);
        //$query['discount'] = get_gigs_currency($query['product_discount'], $query['product_currency'], $ProviderCurrency);
        //echo '<pre>'; print_r($query); exit;
        return $query;
    }
    public function view_product_details($where_data, $ProviderCurrency, $currency_sign)
    {
        $product_id = $where_data['products.id'];
        $this->db->select('products.id, products.user_id,products.manufactured_by, products.shop_id, products.category, products.subcategory, products.product_name, products.unit, product_units.unit_name, products.unit_value, "'.$currency_sign.'" as currency, products.currency_code, products.price as prices, products.sale_price as sales_price, products.discount as product_discount, products.short_description, products.description, product_categories.category_name, product_subcategories.subcategory_name, shops.shop_name');
        $this->db->join('shops','products.shop_id=shops.id','left');
        $this->db->join('product_categories','products.category=product_categories.id','left');
        $this->db->join('product_subcategories','products.subcategory=product_subcategories.id','left');
        $this->db->join('product_units','products.unit=product_units.id','left');
        // $this->db->join('product_images','products.id=product_images.product_id and primary_img=1','left');
        $this->db->where($where_data);
        $query =  $this->db->get('products')->row_array();

        $query['price'] = (string) get_gigs_currency($query['prices'], $query['currency_code'],$ProviderCurrency);
        $query['sale_price'] = (string) get_gigs_currency($query['sales_price'], $query['currency_code'],$ProviderCurrency);
        $query['discount'] = (string) get_gigs_currency($query['product_discount'], $query['currency_code'],$ProviderCurrency);
        //images
        $this->db->select('CONCAT("'.base_url().'", product_image) as product_image, CONCAT("'.base_url().'", thumb_image) as thumb_image');
        $this->db->where(['product_id'=>$query['id'], 'status'=>1]);
        $query['images'] =  $this->db->get('product_images')->result_array();
        $where = array();

        $where['user_id'] = $this->users_id;

        $this->db->where($where);
        $this->db->where('status',0);
        $this->db->group_by('product_id');

        $count = $this->db->count_all_results('product_cart');
        $where = array();
        $where['user_id'] = $this->users_id;
        $where['product_id'] = $product_id;
        $this->db->select('qty as productQty,id');
        $this->db->where($where);
        $this->db->where('status',0);
        $this->db->group_by('product_id');
        $productCount = $this->db->get('product_cart')->result_array();
        $query['cartCount'] = $count;
        if($productCount){
            $query['productCount'] = (int) $productCount[0]['productQty'];
            $query['cartId'] = (string) $productCount[0]['id'];
        }else{
            $query['productCount'] = 0;
            $query['cartId'] = '';
        }


        return $query;
    }
    public function user_product_list($limit, $end, $where, $search, $type, $UserCurrency, $currency_sign)
    {
        if ($type == 'count') 
        {
            $this->db->select('COUNT(products.id) as cnt', false);
        }
        else
        {
            $this->db->select('products.id, products.user_id, products.shop_id, products.product_name, products.unit_value, products.unit, "'.$currency_sign.'" as currency, "'.$UserCurrency.'" as currency_code, products.price, products.sale_price, products.discount, products.short_description, product_categories.category_name, product_subcategories.subcategory_name, CONCAT("'.base_url().'", product_images.product_image) as product_image, shops.shop_name');
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
        return $query;
    }
	
	
	
	
	//Shenbagam
	
	/* Rewards */
	public function reward_details($provider_id)
    {
        $this->db->select("S.provider_id, S.user_id, COUNT(S.user_id) AS total_count, U.name AS user_name, CONCAT('".base_url()."', U.profile_img) as profile_img, IF(COUNT(S.user_id)>=4,'YES','NO') as reward_option");
        $this->db->from('book_service S');  
        $this->db->join('users U', 'U.id = S.user_id', 'LEFT'); 
        $this->db->where('S.provider_id',$provider_id);
        $this->db->where('S.status', 6);
        $this->db->where('U.status', 1);
        $this->db->group_by('S.user_id');		
        $records = $this->db->get()->result_array();
        return $records;
    }
	
	public function reward_user_details($provider_id, $limit='', $end='', $returntype='')
    {
		$det = $this->db->select('allow_rewards, booking_reward_count')->where('id',$provider_id)->where('status != ',0)->get('providers')->row_array();
		$rcount = $det['booking_reward_count'];
        $this->db->select("S.provider_id, S.user_id, COUNT(S.user_id) AS total_count, U.name AS user_name, U.profile_img as profile_img, IF(COUNT(S.user_id)>=$rcount,'YES','NO') as reward_option");
        $this->db->from('book_service S');  
        $this->db->join('users U', 'U.id = S.user_id', 'LEFT'); 
        $this->db->where('S.provider_id',$provider_id);
        $this->db->where('S.status', 6);
        $this->db->where('U.status', 1);
        $this->db->group_by('S.user_id');	
		if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
		if($returntype == 'count'){
			$query = $this->db->get(); 
		    $records = ($query)?$query->num_rows():FALSE; 
		} else {
			$records = $this->db->get()->result_array();					
		}
		return $records;
    }
	
	public function service_rewards_history($provider_id, $limit, $end) {
        $this->db->select("service_rewards.id, service_rewards.service_id, service_rewards.reward_type, service_rewards.status, service_rewards.reward_discount, service_rewards.description, service_rewards.created_at, service_rewards.total_visit_count, services.service_title, services.service_amount, services.currency_code, users.id as user_id, users.name as user_name, users.mobileno as user_mobile, CASE WHEN service_rewards.status = 1 THEN 'Active' WHEN service_rewards.status = 2 THEN 'Inactive' WHEN service_rewards.status = 3 THEN 'USED'  ELSE 'Deleted' END AS reward_status_text, IF(service_rewards.reward_type=1,'Discount','Free Service') as reward_type_text");
        $this->db->join('services', 'service_rewards.service_id = services.id', 'LEFT');
        $this->db->join('users', 'service_rewards.user_id = users.id', 'LEFT');
		$this->db->where('service_rewards.provider_id',$provider_id);
        $this->db->order_by('service_rewards.id', 'DESC');
		if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        $query = $this->db->get('service_rewards')->result_array();
        return $query;
    }
	/* Rewards End */
	
	/* Coupons */
	public function service_coupons_info($provider_id, $limit='', $end='', $returntype='') {
        		
		$this->db->select("S.id, S.service_title,S.currency_code, S.service_amount");
        $this->db->from('services S');              
        $this->db->where("S.user_id", $provider_id);
        $this->db->where('S.status', 1);  
		$this->db->order_by('S.id', 'DESC');  		
        
		if (isset($limit) && !empty($limit)) {
            // $this->db->limit($limit,$end);
        }
       if($returntype == 'count'){
			$query = $this->db->get(); 
		    $records = ($query)?$query->num_rows():FALSE; 
		} else {
			$result = $this->db->get()->result_array();		
			$details = array();
			$new_details = array();
			$data = array();

			 if (count($result) > 0) {				
				foreach ($result as $r) {
					$cname = $this->db->select('coupon_name')->where('provider_id',$provider_id)->where('service_id',$r['id'])->where('status', 1)->get('service_coupons')->row()->coupon_name;
					
					$data['service_id'] = $r['id'];
					$data['service_title'] = $r['service_title'];					
					$data['currency_code'] = $r['currency_code'];				
					$data['service_amount'] = $r['service_amount'];
					$data['coupon_name'] = ($cname != '')?$cname:'';					
					$details[] = $data;
				}
			 }
			 
			if (!empty($details)) {
                $records = $details;
            } else {

                $records= array();
            }
			
			
		}
		return $records;
    }
	/* Coupons End */
	
	/*Paymen list */
	public function provider_payment_info($provider_id, $user_id, $limit='', $end='', $returntype='') {
		$this->db->select('b.*,u.*,s.service_title,s.service_image,b.status as payment_status')	;
		if($provider_id != '') {
			$this->db->where('b.provider_id',$provider_id);
		} else {
			$this->db->where('b.user_id',$user_id);
		}
		$this->db->where_in('b.status',[5,6,7]);
		$this->db->from('book_service as b');
		$this->db->join('users as u','u.id=b.user_id');
		$this->db->join('services s','s.id=b.service_id');
		$this->db->order_by('b.id','desc');
		
		if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
       if($returntype == 'count'){
			$query = $this->db->get(); 
		    $records = ($query)?$query->num_rows():FALSE; 
		} else {
			$result = $this->db->get()->result_array();	
			
			$details = array();
			$new_details = array();
			$data = array();

			 if (count($result) > 0) {				
				foreach ($result as $r) {
					$statusval=''; 
					
					if(!empty($r['reject_paid_token'])){
						if($r['admin_reject_comment']=="This service amount favour for User"){
							$statusval="Amount refund to User";
						}else{
						  $statusval="Amount refund to Provider";
						}
					} 
					if($r['payment_status']==6){
						$statusval = "Payment Completed";
					}
					if($r['payment_status']==5&&empty($r['reject_paid_token'])){
						$statusval = "User Rejected";
					}
					if($r['payment_status']==7&&empty($r['reject_paid_token'])){
						$statusval = "Provider Rejected";
					}
					$serviceimage = explode(',', $r['service_image']);
                    $currency_symbol = currency_code_symbol();
                    $data['currency_symbol'] = $currency_symbol[$r['currency_code']];
					$data['service_id'] = $r['id'];
					$data['service_title'] = $r['service_title'];
					$data['amount'] = $r['amount'];               
					$data['currency_code'] = $r['currency_code'];
					$data['date'] = date('d M Y',strtotime($r['service_date']));
					$data['service_image'] = ($serviceimage[0]!='')?base_url().$serviceimage[0]:'https://via.placeholder.com/360x220.png?text=Service%20Image';; 
					$data['user_name'] = $r['name'];
					$data['user_image'] = ($r['profile_img']!='')?base_url().$r['profile_img']:base_url().'assets/img/user.jpg';
					$data['status'] = $statusval;
					$details[] = $data;
				}
			 }
			 
			if (!empty($details)) {
                $records = $details;
            } else {

                $records= array();
            }

			
		}
		return $records;
	}
	/* Payment Lists End */
	
	
	/* Reviews list */
	public function review_info($provider_id, $user_id, $limit='', $end='', $returntype='') {
				
		$this->db->select("r.*,u.profile_img,u.name,s.id as service_id, s.service_image,s.service_title");
		$this->db->from('rating_review r');
		$this->db->join('users u', 'u.id = r.user_id', 'LEFT');
		$this->db->join('services s', 's.id = r.service_id', 'LEFT');

	
		if($provider_id != '') {
			$this->db->where('r.provider_id',$provider_id);
		} else {
			$this->db->where('r.user_id',$user_id);
		}
		$this->db->where('r.status',1);		
		$this->db->order_by('r.id','desc');
		
		if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
       if($returntype == 'count'){
			$query = $this->db->get(); 
		    $records = ($query)?$query->num_rows():FALSE; 
		} else {
			$result = $this->db->get()->result_array();	
			
			$details = array();
			$new_details = array();
			$data = array();

			 if (count($result) > 0) {				
				foreach ($result as $r) {
					$datetime = new DateTime($r['created']); 
					$avg_rating=round($r['rating'],1);
					$serviceimage = explode(',', $r['service_image']);
					$data['service_id'] = $r['service_id'];
					$data['service_title'] = $r['service_title'];					
					$data['date'] = $datetime->format('F d, Y H:i a');
					$data['service_image'] = ($serviceimage[0]!='')?base_url().$serviceimage[0]:'https://via.placeholder.com/360x220.png?text=Service%20Image';; 
					$data['review'] = $r['review'];
					$data['avg_rating'] = $avg_rating;
					$data['name'] = $r['name'];
					$data['user_image'] = ($r['profile_img']!='')?base_url().$r['profile_img']:base_url().'assets/img/user.jpg';
					$details[] = $data;
				}
			 }
			 
			if (!empty($details)) {
                $records = $details;
            } else {

                $records= array();
            }

			
		}
		return $records;
	}
	/* Reviews list End */
	
	/* Appointment */
	public function get_shop_service($inputs){
      return $this->db->where('service_offer_id',$inputs)->get('shop_services_list')->row_array();
    }
	public function shop_hours($shop_id,$provider_id){
        return $this->db->select("all_days,availability")->where('provider_id',$provider_id)->where('id',$shop_id)->get('shops')->row_array(); 
    }
	public function providerhours($staff_id, $provider_id){
        return $this->db->select("all_days,availability")->where('provider_id',$provider_id)->where('id',$staff_id)->get('employee_basic_details')->row_array(); 
    }
	public function getbookings($service_date,$service_id,$staff_id){
        $service_date = date('Y-m-d',strtotime($service_date));
		return $this->db->select('id, service_id, provider_id, user_id, shop_id, staff_id, service_date, amount, from_time, to_time, updated_on')->where(array('service_date'=>$service_date,'service_id'=>$service_id,'staff_id'=>$staff_id))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	
	
	public function get_bookings_date($service_date,$service_id,$from,$to,$staff_id,$book_id){
        $service_date = date('Y-m-d',strtotime($service_date));
		if($book_id > 0){
			$this->db->where("id != ", $book_id);
		}
		return $this->db->where(array('service_date'=>$service_date,'service_id'=>$service_id,'from_time'=>$from,'to_time'=>$to,'staff_id'=>$staff_id))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	
	
	public function user_get_bookings($service_date,$from,$to,$book_id,$user_id){		
		$service_date = date('Y-m-d',strtotime($service_date));
		if($book_id > 0){
			$this->db->where("id != ", $book_id);
		}
        return $this->db->where(array('user_id'=>$user_id,'service_date'=>$service_date,'from_time'=>$from,'to_time'=>$to))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	public function user_time_bookings($service_date,$book_id,$user_id,$action){
		$service_date = date('Y-m-d',strtotime($service_date));		
		if($book_id > 0 && $action == 'edit'){
			$this->db->where("id != ", $book_id);
		}
        return $this->db->select(' `from_time`, `to_time` ')->where(array('user_id'=>$user_id,'service_date'=>$service_date))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	
	
	public function get_bookings_staff($service_date, $staff_id,$book_id,$action){
		$service_date = date('Y-m-d',strtotime($service_date));
		$where = '';
		if($book_id > 0 && $action == 'edit'){
			$where = " and id != ". $book_id;
		}
		$query = $this->db->query("SELECT `from_time`, `to_time` FROM `book_service` WHERE status not in (5,6,7) and service_date = '".$service_date."' AND   FIND_IN_SET('".$staff_id."', staff_id) ".$where);
        $result = $query->result_array();
		return $result;
    } 
	
	
	/*Insert Service - Booking Appointment */
	public function booking_success($inputs){
		$this->db->insert('book_service', $inputs);
		return $this->db->insert_id();
    }
	

    
	function read_time_slots($first,$last,$duration){		
		$start_time = $first;
		$end_time = $last;
		$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
		
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$data = [];
		$j=1;
		for ($i=0; $slot <= $end_time; $i++) { 
			if($start_time < $current_time && $current_time > $slot){
				$disabled = True;
			} else {
				$disabled = FALSE;
			}

			$data[$i] = [ 
				'id'  => $j,
				'start_time' => date('H:i:s ', $start_time),
				'end_time' => date('H:i:s', $slot),				
			];
			$start_time = $slot;
			$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
			$j++;
		}
		return $data;
	}
	
	function readAvailability($staff_id,$provider_id,$booking_date){ 
		$staff_details  = $this->providerhours($staff_id,$provider_id);
		$availability_details = json_decode($staff_details['availability'], true);
		
        $alldays = false;
		$timestamp = strtotime($booking_date);
        $day = date('D', $timestamp);
        foreach ($availability_details as $details) {

            if (isset($details['day']) && $details['day'] == 0) {

                if (isset($details['from_time']) && !empty($details['from_time'])) {

                    if (isset($details['to_time']) && !empty($details['to_time'])) {
                        $from_time = $details['from_time'];
                        $to_time = $details['to_time'];
                        $alldays = true;
                        break;
                    }
                }
            }
        }

        if ($alldays == false) {

            if ($day == 'Mon') {
                $weekday = '1';
            } elseif ($day == 'Tue') {
                $weekday = '2';
            } elseif ($day == 'Wed') {
                $weekday = '3';
            } elseif ($day == 'Thu') {
                $weekday = '4';
            } elseif ($day == 'Fri') {
                $weekday = '5';
            } elseif ($day == 'Sat') {
                $weekday = '6';
            } elseif ($day == 'Sun') {
                $weekday = '7';
            } elseif ($day == '0') {
                $weekday = '0';
            }

            foreach ($availability_details as $availability) {

                if ($weekday == $availability['day'] && $availability['day'] != 0) {

                    $availability_day = $availability['day'];
                    $from_time = $availability['from_time'];
                    $to_time = $availability['to_time'];
                }
            }
        }
		
        if (!empty($from_time)) {
            $temp_start_time = $from_time;
            $temp_end_time = $to_time;
        } else {
            $temp_start_time = '';
            $temp_end_time = '';
        }   
		$alltime = $temp_start_time."-".$temp_end_time; 
		return $alltime;
		
	}
	
	function get_time_slots($first,$last,$duration){		
		$start_time = $first;
		$end_time = $last;
		$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
		
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$data = [];
		$j=1;
		for ($i=0; $slot <= $end_time; $i++) { 
			if($start_time < $current_time && $current_time > $slot){
				$disabled = True;
			} else {
				$disabled = FALSE;
			}			
			$data[] = date('H:i:s ', $start_time);
			$data[] = date('H:i:s ', $slot);
			$start_time = $slot;
			$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
			$j++;
		}
		return array_values(array_unique($data));
	}
	
	public function get_service_by_id($id)
	{
		return $this->db->get_where('services',array('status'=>1,'id'=>$id))->row_array();
	}
	
	/* Appointment End */
	
	
	
	/*Order Payment list */
	public function provider_orderpayment_info($provider_id, $user_id, $limit='', $end='', $returntype='') {
		
		if($provider_id != '') {
			$order_where = array('product_cart.status'=>1, 'shops.provider_id'=>$provider_id);
		} else {
			$order_where = array('product_cart.status'=>1, 'product_cart.user_id'=>$user_id);
		}
		$this->db->select('product_cart.id,product_cart.user_id,users.profile_img,users.currency_code, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno, product_order.payment_type, shops.provider_id as providerid, CASE
        WHEN product_cart.delivery_status = 5 THEN "Delivered" WHEN
            product_cart.delivery_status = 6 THEN "Cancelled By User" 
        ELSE "Cancelled By Provider"
    END AS delivery_status_text', false);
		$this->db->join('product_order','product_cart.order_id=product_order.id','left');
		$this->db->join('products','product_cart.product_id=products.id','left');
		$this->db->join('shops','product_cart.shop_id=shops.id','left');
		$this->db->join('product_units','products.unit=product_units.id','left');
		$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
		$this->db->join('users','product_cart.user_id=users.id','left');
		$this->db->where_in('product_cart.delivery_status', [5,6,7] );	
		$this->db->where($order_where);									
		
		if (isset($limit) && !empty($limit)) {
            $this->db->limit($limit,$end);
        }
        if($returntype == 'count')
        {
			$query = $this->db->get('product_cart'); 
		    $records = $query->num_rows();
		} 
        else 
        {
			$result = $this->db->get('product_cart')->result_array();	
            $records = $result;
            if (count($records) > 0) {				
				foreach ($records as $key=>$r) {
                    $currency_symbol = currency_code_symbol();
                    $records[$key]['currency_symbol'] = $currency_symbol[$r['currency_code']];
                    $records[$key]['user_image'] = ($r['profile_img']!='')?base_url().$r['profile_img']:base_url().'assets/img/user.jpg';

                }
            }
		}	
		return $records;
	}
	/* Order Payment Lists End */
    function insertserviceoffer($data) {
        $this->db->insert('service_offers',$data);
        return $this->db->insert_id();
    }
	
	
    /* END */
    public function booking_count($provider_id){

    $this->db->where('provider_id',$provider_id);
        return $this->db->count_all_results('book_service');
        
    }

    public function services_count($user_id){

        $this->db->where(array('user_id'=>$user_id, 'status!='=>'0'));
        return $this->db->count_all_results('services');
        
    }

    public function get_my_subscription($user_id,$usertype)
    {   	 
	 if($usertype == 'provider') {
		 $typeval = 1;
	 }else if($usertype == 'user') {
		 $typeval = 2;
	 } else if($usertype == 'freelancer'){
		 $typeval = 3;
	 }
      return $this->db->order_by('id','desc')->get_where('subscription_details',array('subscriber_id'=>$user_id,'type'=>$typeval))->row_array();
    }

    public function get_my_subscription_list($user_id,$usertype)
    {
	 if($usertype == 'provider') {
		 $type = 1;
	 }else if($usertype == 'user') {
		 $type = 2;
	 } else if($usertype == 'freelancer'){
		 $type = 3;
	 }

      return $this->db->from('subscription_details_history')->join('subscription_fee','subscription_fee.id=subscription_details_history.subscription_id')->where('subscription_details_history.subscriber_id',$user_id)->where('subscription_details_history.type',$type)->get()->result_array();
    }

    public function notification_count($ses_token){
        $this->db->select('*')->
						from('notification_table')->
						where('receiver',$ses_token)->
						where('status',1);
                        return $this->db->count_all_results();
    }

    //jaya
    //delete images for id
    public function delete_images($pid) {
       
      $this->db->where('product_id', $pid);
      $this->db->update('product_images', array('status' => 0));
        return $this->db->count_all_results('product_images');

    }
    public function delete_images_shop($sid) {
       
        $this->db->where('shop_id', $sid);
        $this->db->update('shops_images', array('status' => 0));
        return $this->db->count_all_results('shops_images');
    }

}
?>

