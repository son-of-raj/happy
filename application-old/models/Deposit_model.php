<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit_model extends CI_Model {

	public function __construct() {

        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/Kolkata');
    }

		
	public function deposit_provider_list(){
		$this->db->select('P.*,S.subscription_name,SD.subscriber_id');
		$this->db->from('providers P');
		$this->db->join('subscription_details SD','SD.subscriber_id=P.id','left');
		$this->db->join('subscription_fee S','S.id=SD.subscription_id','left');	
		$this->db->where('P.status', 1);	
		$this->db->order_by('P.id', 'DESC');
		return $this->db->get()->result();
	}
	
	/*deposit filter*/

    public function deposit_filter($username,$email,$from,$to){
		if(!empty($from)) {
		$from_date=date("Y-m-d 00:00:00", strtotime($from));
		}else{
		$from_date='';
		}
		if(!empty($to)) {
		$to_date=date("Y-m-d 23:59:00", strtotime($to));
		}else{
		$to_date='';
		}

		$this->db->select('P.*,S.subscription_name,SD.subscriber_id');
		$this->db->from('providers P');
		$this->db->join('subscription_details SD','SD.subscriber_id=P.id','left');
		$this->db->join('subscription_fee S','S.id=SD.subscription_id','left');
		if(!empty($username)){
		$this->db->where('P.name',$username);
		}
		if(!empty($email)){
		$this->db->where('P.email',$email);
		}
		
		if(!empty($from_date)){
		$this->db->where('P.created_at >=',$from_date);
		}
		if(!empty($to_date)){
		$this->db->where('P.created_at <=',$to_date);
		}
		$this->db->where('P.status', 1);		
		return $this->db->get()->result();
    }

    function x_week_range($date) {
		$ts = strtotime($date);
		$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
		return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
	}
	
	function Start_End_Date_of_a_week($week, $year)
	{
		$time = strtotime("1 January $year", time());
		$day = date('w', $time);
		$time += ((7*$week)+1-$day)*24*3600;
		$dates[0] = date('Y-n-j', $time);
		$time += 6*24*3600;
		$dates[1] = date('Y-n-j', $time);
		return $dates;
	}




		
}

/* End of file Deposit_model.php */
/* Location: ./application/models/Deposit_model.php */
