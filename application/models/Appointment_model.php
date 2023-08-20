<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Appointment_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
                
	}
 
	public function get_shop_service($inputs){
      return $this->db->where('service_offer_id',$inputs)->get('shop_services_list')->row_array();
    }
	public function shop_hours($shop_id,$provider_id){
        return $this->db->select("all_days,availability")->where('provider_id',$provider_id)->where('id',$shop_id)->get('shops')->row_array(); 
    }
	public function provider_hours($staff_id, $provider_id){
        return $this->db->select("all_days,availability")->where('provider_id',$provider_id)->where('id',$staff_id)->get('employee_basic_details')->row_array(); 
    }
	public function get_bookings($service_date,$service_id,$staff_id){
        $service_date = date('Y-m-d',strtotime($service_date));
        if($staff_id != 0) {
			return $this->db->select('id, service_id, provider_id, user_id, shop_id, staff_id, service_date, amount, from_time, to_time, updated_on')->where(array('service_date'=>$service_date,'service_id'=>$service_id,'staff_id'=>$staff_id))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
		} else {
			return $this->db->select('id, service_id, provider_id, user_id, shop_id, staff_id, service_date, amount, from_time, to_time, updated_on')->where(array('service_date'=>$service_date,'service_id'=>$service_id))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
		}
    } 
	
	
	public function get_bookings_date($service_date,$service_id,$from,$to,$staff_id,$book_id){
        $service_date = date('Y-m-d',strtotime($service_date));
		if($book_id > 0){
			$this->db->where("id != ", $book_id);
		}
		return $this->db->where(array('service_date'=>$service_date,'service_id'=>$service_id,'from_time'=>$from,'to_time'=>$to,'staff_id'=>$staff_id))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	
	
	public function user_get_bookings($service_date,$from,$to,$book_id){
		$service_date = date('Y-m-d',strtotime($service_date));
		$user_id = $this->session->userdata('id');
		if($book_id > 0){
			$this->db->where("id != ", $book_id);
		}
        return $this->db->where(array('user_id'=>$user_id,'service_date'=>$service_date,'from_time'=>$from,'to_time'=>$to))->where_not_in('status',[5,6,7])->get('book_service')->result_array();
    } 
	public function user_time_bookings($service_date,$book_id,$action){
		$service_date = date('Y-m-d',strtotime($service_date));
		$user_id = $this->session->userdata('id');
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
	
	/* get book service info */
    public function get_book_info($book_service_id,$type='') {
        $ret = $this->db->select('tab_1.provider_id, tab_1.user_id, tab_1.status, tab_1.currency_code, tab_1.amount, tab_2.service_offer_name as service_title')->
				from('book_service as tab_1')->
				join('shop_services_list as tab_2', 'tab_2.id=tab_1.service_id', 'LEFT')->
				join('shops as tab_3', 'tab_3.id=tab_1.shop_id', 'LEFT')->
				where('tab_1.id', $book_service_id)->limit(1)->
				order_by('tab_1.id', 'DESC')->
				get()->row_array();
        return $ret;
    }

    
	function read_time_slots($first,$last,$duration){		
		$start_time = $first;
		$end_time = $last;
		$slot = strtotime(date('Y-m-d H:i:s',$start_time) . ' +'.$duration.' minutes');
		
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$data = [];
		$j=1;
		echo 'slot<pre>'; print_r($slot);  
		echo 'end_time<pre>'; print_r($end_time);  
		exit;
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
		echo 'mod<pre>'; print_r($data);    
		exit;
		return $data;
	}
	
	function readAvailability($staff_id,$provider_id,$booking_date){ 
		$staff_details  = $this->provider_hours($staff_id,$provider_id);
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
	public function get_service($id)
	{
		return $this->db->get_where('services',array('status'=>1,'id'=>$id))->row_array();
	}
	
}	
	 
?>	