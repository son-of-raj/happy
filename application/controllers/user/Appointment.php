<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Appointment extends CI_Controller
{

	public $data;

	public function __construct()
	{

		parent::__construct();
		error_reporting(0);
		date_default_timezone_set('Asia/Kolkata');

		$this->data['theme'] = 'user';
		$this->data['module'] = 'appointment';
		$this->data['page'] = '';

		$this->site_name = 'Happy Ceremonies';
		$this->data['base_url'] = base_url();

		$this->session->keep_flashdata('error_message');
		$this->session->keep_flashdata('success_message');

		$this->load->helper('custom_language');
		$this->load->helper('user_timezone_helper');
		$this->load->helper('push_notifications');

		$this->load->model('templates_model');
		$this->load->model('api_model', 'api');
		$this->load->model('appointment_model', 'appointment');

		$this->load->helper('user_timezone_helper');
		$user_id = $this->session->userdata('id');
		$this->data['user_id'] = $user_id;
		$this->load->helper('subscription_helper');


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

		$bookmsg = (!empty($this->user_language[$this->user_selected]['lg_Book_Success'])) ? $this->user_language[$this->user_selected]['lg_Book_Success'] : $this->default_language['en']['lg_Book_Success'];

		$nobookmsg = (!empty($this->user_language[$this->user_selected]['lg_NoBooking'])) ? $this->user_language[$this->user_selected]['lg_NoBooking'] : $this->default_language['en']['lg_NoBooking'];

		$btimemsg = (!empty($this->user_language[$this->user_selected]['lg_Book_Timeslot'])) ? $this->user_language[$this->user_selected]['lg_Book_Timeslot'] : $this->default_language['en']['lg_Book_Timeslot'];

		$anothermsg = (!empty($this->user_language[$this->user_selected]['lg_Another_Book'])) ? $this->user_language[$this->user_selected]['lg_Another_Book'] : $this->default_language['en']['lg_Another_Book'];

		$bstaffmsg = (!empty($this->user_language[$this->user_selected]['lg_Book_Staff'])) ? $this->user_language[$this->user_selected]['lg_Book_Staff'] : $this->default_language['en']['lg_Book_Staff'];

		$errmsg = (!empty($this->user_language[$this->user_selected]['lg_Common_Error'])) ? $this->user_language[$this->user_selected]['lg_Common_Error'] : $this->default_language['en']['lg_Common_Error'];

		$edtmsg = (!empty($this->user_language[$this->user_selected]['lg_Booking'])) ? $this->user_language[$this->user_selected]['lg_Booking'] : $this->default_language['en']['lg_Booking'];
		$edtMsg = (!empty($this->user_language[$this->user_selected]['lg_Edit_Msg'])) ? $this->user_language[$this->user_selected]['lg_Edit_Msg'] : $this->default_language['en']['lg_Edit_Msg'];

		$BMsg = (!empty($this->user_language[$this->user_selected]['lg_Booking'])) ? $this->user_language[$this->user_selected]['lg_Booking'] : $this->default_language['en']['lg_Booking'];
		$PTPMsg = (!empty($this->user_language[$this->user_selected]['lg_Proceed_To_Payment'])) ? $this->user_language[$this->user_selected]['lg_Proceed_To_Payment'] : $this->default_language['en']['lg_Proceed_To_Payment'];
		$BAMsg = (!empty($this->user_language[$this->user_selected]['lg_Booking_Available'])) ? $this->user_language[$this->user_selected]['lg_Booking_Available'] : $this->default_language['en']['lg_Booking_Available'];



		$this->bookmsg = $bookmsg;
		$this->nobookmsg = $nobookmsg;
		$this->btimemsg = $btimemsg;
		$this->anothermsg = $anothermsg;
		$this->bstaffmsg = $bstaffmsg;
		$this->book_editmsg = $edtmsg . " " . $edtMsg;
		$this->errmsg = $errmsg;

		$this->BMsg = $BMsg;
		$this->PTPMsg = $PTPMsg;
		$this->BAMsg = $BAMsg;

		/*Delete Onhold Service */
		//$this->db->query('DELETE FROM `book_service` WHERE `status` = 8 AND `request_time` < CURRENT_TIME - INTERVAL 45 MINUTE');

	}
	public function get_staff_data()
	{
		$user_id = $this->session->userdata('id');

		$shop_id = $_POST['shop_id'];
		$service_id = $_POST['service_id'];
		$service_at = $_POST['service_at'];

		$qry = $this->db->query("SELECT `staff_id` FROM `services` WHERE id = '" . $service_id . "' AND   FIND_IN_SET('" . $shop_id . "', shop_id) ");
		$res = $qry->row_array();
		$stff = $res['staff_id'];
		$stffarr = explode(",", $stff);

		$this->db->select("e.id, e.first_name, e.last_name, e.designation, e.shop_service, e.home_service, e.home_service_area, CASE WHEN home_service = 2 AND shop_service = 1 THEN '2' WHEN home_service = 2 AND shop_service = 0 THEN '1' ELSE '0' END AS service_status");
		$this->db->where('e.status', 1);
		$this->db->where('e.delete_status', 0);
		//$this->db->where('e.shop_id', $shop_id); 		
		$this->db->where_in('e.id', $stffarr);

		$this->db->order_by('e.shop_id', 'DESC');
		$query = $this->db->get('employee_basic_details e');
		$result = $query->result();

		$data = array();
		if (!empty($result)) {
			foreach ($result as $r) {
				if ($service_at == 1 && $r->service_status != 0) {
					$data['value'] = $r->id;
					$data['label'] = $r->first_name;
					$data['homeservice'] = $r->service_status;
					$json[] = $data;
				}
				if ($service_at == 2 && $r->service_status != 1) {
					$data['value'] = $r->id;
					$data['label'] = $r->first_name;
					$data['homeservice'] = $r->service_status;
					$json[] = $data;
				}
			}
		}
		echo json_encode($json);
	}
	public function edit_appointment()
	{
		if (empty($this->session->userdata('id'))) {
			redirect(base_url());
		}
		if ($this->session->userdata('usertype') != 'user') {
		}

		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		if (!empty($result)) {
			foreach ($result as $data) {
				if ($data['key'] == 'map_key') {
					$map_key = $data['map_key'];
				}
			}
		}

		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'edit_appointment';
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'] . '/template');
	}

	public function offline_bookings()
	{
		if (empty($this->session->userdata('id'))) {
			redirect(base_url());
		}
		if ($this->session->userdata('usertype') != 'provider') {
			redirect(base_url());
		}

		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		if (!empty($result)) {
			foreach ($result as $data) {
				if ($data['key'] == 'map_key') {
					$map_key = $data['map_key'];
				}
			}
		}

		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'book_offline';
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'] . '/template');
	}

	public function offline_book_checkout()
	{

		if (empty($this->session->userdata('id'))) {
			redirect(base_url());
		}
		if ($this->session->userdata('usertype') != 'provider') {
			redirect(base_url());
		}

		$user_currency = get_user_currency();
		$user_currency_code = $user_currency['user_currency_code'];

		removeTag($this->input->post());
		$final_amount = $this->input->post('final_amount');

		$time = $this->input->post('booking_time');
		$slots = json_encode($time);
		$start_time = '';
		$end_time = '';
		$from_time =  '';
		$to_time =  '';

		$inputs = array();
		$service_id = $this->input->post('service_id'); // Package ID  		
		$records = $this->appointment->get_service($service_id);
		$cal_seramt = $this->input->post('total_amt');

		$inputs['service_id']    = $service_id;
		$inputs['provider_id']   = $records['user_id'];
		$inputs['user_id']       = $this->input->post('user_id');
		$inputs['slots']     = $slots;

		$inputs['booking_amnt'] = $final_amount;

		$inputs['shop_id']       = $records['shop_id'];
		$inputs['staff_id']      = $records['staff_id'];

		$inputs['location'] = $this->input->post('service_location');
		$inputs['latitude'] = $records['service_latitude'];
		$inputs['longitude'] = $records['service_longitude'];

		$inputs['service_date'] = date('Y-m-d');
		$inputs['from_time'] = $from_time;
		$inputs['to_time'] = $to_time;
		$inputs['notes']      = $this->input->post('notes');

		$inputs['tokenid'] = 'old type';
		$inputs['cod'] = 0;



		$inputs['currency_code'] = $records['currency_code'];
		$inputs['amount'] = $records['service_amount'];


		$inputs['updated_on']  = (date('Y-m-d H:i:s'));
		$inputs['home_service'] = $this->input->post('service_at');
		$inputs['autoschedule_session_no'] = $records['autoschedule'];

		$inputs['offersid'] = $this->appointment->get_booking_offers_by_service($service_id)['offer_id'];
		$inputs['couponid'] = 0;
		$inputs['rewardid'] = 0;
		$rtype = '';

		$inputs['request_date'] = date('Y-m-d');
		$inputs['request_time'] = date('H:i:s', time());
		$inputs['status'] = 2;

		$book_id = 0;

		if ($book_id > 0) {
			$qry = $this->db->select('total_amount, final_amount')->where('id', $book_id)->get('book_service')->row_array();
			$tot = $qry['total_amount'];
			$inputs['total_amount'] = $tot;
			$inputs['final_amount'] = $qry['final_amount'];
		} else {
			$addiamt = 0;
			$totalamt = $cal_seramt;

			//Total Calculation 
			$total_amt_val = $totalamt + $addiamt;
			if ($total_amt_val <= 0) $total_amt_val = 0;
			$inputs['total_amount'] = $total_amt_val;
			$inputs['final_amount'] = $total_amt_val;



			// If Offers available
			if ($inputs['offersid'] > 0) {
				$offers = $this->db->where("id", $inputs['offersid'])->get("service_offers")->row_array();

				$new_serviceamt = $inputs['total_amount'];
				$offerPrice = '';
				$offersid = 0;
				if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
					$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100;
					if (is_nan($offerPrice)) $offerPrice = 0;
					$offerPrice = number_format($offerPrice, 2);
					$offersid = $offers['id'];

					$new_serviceamt  = $new_serviceamt - $offerPrice;
					$new_serviceamt  = number_format($new_serviceamt, 2);
					if ($new_serviceamt <= 0) $new_serviceamt = 0;
					$inputs['total_amount'] = $new_serviceamt;
					$inputs['final_amount'] = $new_serviceamt;
					$inputs['offersid'] = $offersid;
				}
			}

			// If Rewards available
			if ($inputs['rewardid'] > 0) {
				$reward = $this->db->where("id", $inputs['rewardid'])->get("service_rewards")->row_array();
				$re_serviceamt = $inputs['total_amount'];
				$rewardPrice = '';
				$rewardid = 0;
				if ($rtype == '') {
					if (!empty($reward['reward_type']) && $reward['reward_type'] == 1 && $reward['id'] > 0) {
						$rewardPrice = ($re_serviceamt) * $reward['reward_discount'] / 100;
						if (is_nan($rewardPrice)) $rewardPrice = 0;
						$rewardPrice = number_format($rewardPrice, 2);
						$rewardid = $reward['id'];

						$re_serviceamt  = $re_serviceamt - $rewardPrice;
						$re_serviceamt  = number_format($re_serviceamt, 2);
						if ($re_serviceamt <= 0) $re_serviceamt = 0;
						$inputs['total_amount'] = $re_serviceamt;
						$inputs['final_amount'] = $re_serviceamt;
						$inputs['rewardid'] = $rewardid;
					}
				}
			}
		}




		//Booking					
		if ($book_id == 0) {
			if ($rtype != '' && $rtype == '0') { // Free Service
				$inputs['cod'] = 0;
				$inputs['request_date'] = date('Y-m-d');
				$inputs['request_time'] = date('H:i:s', time());
				$bookres = $this->appointment->booking_success($inputs);
				if ($bookres > 0) {
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $inputs['rewardid'] . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
					$message = 'You have booked appointment successfully';
					$this->session->set_flashdata('success_message', $this->bookmsg);
					echo json_encode(['success' => true, 'msg' => $this->bookmsg, 'status' => 1, 'title' => $this->BMsg, 'bookid' => $bookres]);
				} else {
					$this->session->set_flashdata('error_message', $this->errmsg);
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				}
			} else {
				$result = $this->appointment->booking_success($inputs);
				if ($result != '' && $result > 0) {
					if ($inputs['autoschedule_session_no']  == 1) {
						$myservice = $this->db->select('service_title,autoschedule, autoschedule_days, autoschedule_session')->where('id', $service_id)->from('services')->get()->row_array();
						if ($myservice['autoschedule_session'] != 0 && $myservice['autoschedule_days'] != 0) {
							$days = 0;
							$d = 2;
							for ($s = 1; $s < intval($myservice['autoschedule_session']); $s++) {
								$days = intval($myservice['autoschedule_days']);
								$newdate = date("Y-m-d", strtotime("+" . $days . " day", strtotime($inputs['service_date'])));
								$session_no = $d++;
								$inputs['amount'] = 0;
								$inputs['service_date'] = $newdate;
								$inputs['notes']      = $myservice['service_title'] . "  - Session(" . $session_no . ")";
								$inputs['autoschedule_session_no'] = $session_no;
								$inputs['parent_bookid'] = $result;

								$this->appointment->booking_success($inputs);
							}
						}
					}
					$message = $this->BAMsg;
					$this->session->set_flashdata('success_message', $message);

					echo json_encode(['success' => true, 'msg' => $message,  'title' => $this->BMsg, 'status' => 4, 'bookid' => $result]);
					exit;
				} else {

					$message = 'Sorry, Try again later...';
					$this->session->set_flashdata('error_message', $this->errmsg);
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				}
			}
		}

		// Updation
		if ($book_id > 0) {
			$inputs['request_date'] = date('Y-m-d');
			$inputs['request_time'] = date('H:i:s', time());
			$inputs['status'] = 2;

			if ($this->db->update('book_service', $inputs, array("id" => $book_id))) {

				$token = $this->session->userdata('chat_token');
				$data = $this->api->get_book_info($book_id);
				$user_name = $this->api->get_user_info($data['user_id'], 2);
				$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
				$text = $user_name['name'] . " has edited the Booked Service '" . $service['service_title'] . "'";

				$ptype = $this->db->select('type')->where('id', $inputs['provider_id'])->get('providers')->row()->type;
				$this->send_push_notification($token, $book_id, $ptype, $msg = $text);

				$this->session->set_flashdata('success_message', $this->book_editmsg);
				echo json_encode(['success' => true, 'msg' => $this->book_editmsg,  'title' => $this->BMsg, 'status' => 5]);
			} else {
				$this->session->set_flashdata('error_message', $this->errmsg);
				echo json_encode(['success' => false, 'msg' => $this->errmsg,  'title' => $this->BMsg, 'status' => 2]);
			}
		}

		exit;
	}

	public function book_appointment()
	{
		if (empty($this->session->userdata('id'))) {
			redirect(base_url());
		}
		if ($this->session->userdata('usertype') != 'user') {
			redirect(base_url());
		}

		$service_id = $this->uri->segment('2');
		$records = $this->appointment->get_service($service_id);
		if (empty($records)) {
			redirect(base_url() . 'all-services');
		}
		if ($this->session->userdata('usertype') == 'user') {
			//Covid vaccine status
			$covidControl = settingValue('corona_control');
			$getdetails = $this->db->select('covid_vaccine')->where('id', $this->session->userdata('id'))->get('users')->row_array();
			$cvaccine = $getdetails['covid_vaccine'];
			if ($covidControl == 1 && $cvaccine == 0) {
				$service_id = $this->uri->segment('2');
				$serinp = $this->db->select('id,service_title')->where('id', $service_id)->from('services')->get()->row_array();
				$url = base_url() . 'service-preview/' . str_replace(' ', '-', $serinp['service_title']) . '?sid=' . md5($service_id);
				$content = "<br><h4 align='center'>Click the link for service details <a href='" . $url . "'>" . $serinp['service_title'] . "</a></h4>";
				echo '<h1 align="center">Permissions Denied - Please Update the Covid Vaccination Status of Yours for Booking</h1>' . $content;
				die;
			}
		}

		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		if (!empty($result)) {
			foreach ($result as $data) {
				if ($data['key'] == 'map_key') {
					$map_key = $data['map_key'];
				}
			}
		}

		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'book_appointment';
		$this->load->vars($this->data);
		$this->load->view($this->data['theme'] . '/template');
	}
	public function checkout()
	{

		$book_id = decrypt_url($this->uri->segment('2'), $this->config->item('encryption_key'));

		if (empty($this->session->userdata('id'))) {
			redirect(base_url());
		}
		if ($this->session->userdata('usertype') != 'user') {
			redirect(base_url());
		}

		$query = $this->db->query("select * from system_settings WHERE status = 1");
		$result = $query->result_array();
		if (!empty($result)) {
			foreach ($result as $data) {
				if ($data['key'] == 'map_key') {
					$map_key = $data['map_key'];
				}
			}
		}

		/* Moyaser Payment */
		$moyaser_option = settingValue('moyaser_option');
		if ($moyaser_option == 1) {
			$this->data['moyaser_apikey'] = settingValue('moyaser_apikey');
			$this->data['moyaser_secret_key'] = settingValue('moyaser_secret_key');
		} else if ($moyaser_option == 2) {
			$this->data['moyaser_apikey'] = settingValue('live_moyaser_apikey');
			$this->data['moyaser_secret_key'] = settingValue('live_moyaser_secret_key');
		}
		$moyaser_option_status = $this->db->where('id', settingValue('moyaser_option'))->get('moyaser_payment_gateway')->row()->status;
		$this->data['moyasar_option_status'] = ($moyaser_option_status) ? $moyaser_option_status : 0;
		//Cod
		$this->data['cod_option_status'] = settingValue('cod_option');
		$this->data['paypal_option_status'] = settingValue('paypal_option');
		$this->data['stripe_option_status'] = settingValue('stripe_option');
		$this->data['razor_option_status'] = settingValue('razor_option');
		$this->data['cod_option_status'] = settingValue('cod_option');

		if ($this->data['stripe_option_status'] == 1) {
			$this->data['stripe_key'] = settingValue('publishable_key');
		} else {
			$this->data['stripe_key'] = settingValue('live_publishable_key');
		}
		$this->data['web_log'] = (settingValue('logo_front')) ? base_url() . settingValue('logo_front') : base_url() . 'assets/img/logo.png';

		$this->data['map_key'] = $map_key;
		$this->data['page'] = 'checkout';

		$this->data['bookings'] = $this->db->where('id', $book_id)->from('book_service')->get()->row_array();

		$this->load->vars($this->data);
		$this->load->view($this->data['theme'] . '/template');
	}
	public function book_moyaser_payment($bookid = '', $couponid = '')
	{

		$amt = $_GET['amount'] / 100;
		$paid_token = $_GET['id'];
		if ($this->input->post('bookid') != '' && $bookid == '') {
			$bookid = $this->input->post('bookid');
		}
		$servicess = $this->db->select('*')->where('id', $bookid)->get('book_service')->row_array();

		$service_id = $servicess['service_id'];


		$inputs = array();

		$inputs['cod'] = 2; // Online Payment

		if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
			$inputs['cod'] = 1;
			$couponid = $this->input->post('cid');
			$amt = $this->input->post('totalamt');
			$paid_token = '';
		}


		if ((isset($_GET['status']) && $_GET['status'] == 'paid')  || $inputs['cod'] == 1) {


			if (!empty($couponid) && $couponid > 0) {
				$cinputs['couponid'] = $couponid;
			}

			$result = $bookid;

			if ($result != '') {
				$inputs['status'] = 2;
				$inputs['reason'] = '';
				$inputs['final_amount'] = $amt;
				$inputs['paid_tokenid'] = $paid_token;
				$inputs['paytype'] = 'moyasarpay';

				$this->db->update('book_service', $inputs, array("id" => $result));

				$this->db->update('book_service', $cinputs, array("id" => $result));

				$ginputs['status'] = 2;
				$this->db->update('book_service', $ginputs, array("id" => $result));


				$this->load->library('ciqrcode');
				$qr_image = rand() . '.png';
				$params['data'] = $result;
				$params['level'] = 'H';
				$params['size'] = 4;
				$params['savename'] = FCPATH . "uploads/qr_image/" . $qr_image;
				$qr_message = '';
				if ($this->ciqrcode->generate($params)) {
					$qr_img_url = $qr_image;
					$qr_details['qr_img_url'] = "uploads/qr_image/" . $qr_img_url;

					if ($this->db->update('book_service', $qr_details, array("id" => $result))) {
						$qrpaths = base_url() . 'uploads/qr_image/' . $qr_img_url;
						$qr_message .= '<div style="float:left; width:100%;">';
						$qr_message .= '<p style="font-weight: bold;color: coral;">QR CODE FOR YOUR APPOINTMENT</p>';
						$qr_message .= '<img src="' . $qrpaths . '" alt="QR CODE" />';
						$qr_message .= '</div>';
					}
				}



				$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
				$this->data['service'] = $service;

				if ($servicess['autoschedule_session_no']  == 1) {
					$inputs['total_amount'] = 0;
					$inputs['final_amount'] = 0;
					$this->db->update('book_service', $inputs, array("parent_bookid" => $result));
				}


				// Coupon Count Update
				if (!empty($couponid) && $couponid > 0) {
					$couponqry = $this->db->select('user_limit, user_limit_count, used_user_id')->where('id', $couponid)->get('service_coupons')->row_array();
					$used_coupon = 	$couponqry['used_user_id'];
					if (!empty($used_coupon)) {
						$userids = $used_coupon . ',' . $this->session->userdata('id');
					} else {
						$userids = $this->session->userdata('id');
					}

					$cno = intval($couponqry['user_limit_count']) + 1;
					$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '" . $cno . "', `used_user_id` = '" . $userids . "' WHERE `id` = '" . $couponid . "'");
					if ($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno) {
						$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '" . $couponid . "'");
					}
				}

				// Reward Update
				$rewardid = $servicess['rewardid'];
				if (!empty($rewardid) && $rewardid > 0) {
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $rewardid . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
				}

				// Update Revenue
				$query = $this->db->query('select * from admin_commission where admin_id=1');
				$amount = $query->row();
				$pertage = $amount->commission;
				$vatper = settingValue('vat');

				if ($inputs['cod'] == 2) {
					$revenueInsert = [
						'date' => date('Y-m-d'),
						'provider' => $servicess['provider_id'],
						'service_id' => $service_id,
						'booking_id' => $result,
						'user' => $this->session->userdata('id'),
						'currency_code' => $servicess['currency_code'],
						'amount' => $service['service_amount'],
						'commission' => $pertage,
						'vat' => $vatper,
						'offersid' => $servicess['offersid'],
						'couponid' => $servicess['couponid'],
						'rewardid' => $servicess['rewardid'],
						'revenue_for' => 'Service Booking'
					];
					$revInsert = $this->db->insert('revenue', $revenueInsert);
				}

				$provider_data = $this->db->where('id', $servicess['provider_id'])->from('providers')->get()->row_array();
				$user_data = $this->db->where('id', $this->session->userdata('id'))->from('users')->get()->row_array();
				$this->data['provider'] = $provider_data;

				$preview_link = base_url();
				$time = $servicess['from_time'] . "-" . $servicess['to_time'];


				$bodyid = 3;
				$tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
				$providerbody = $tempbody_details['template_content'];
				$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
				$providerbody = str_replace('{sitetitle}', $this->site_name, $providerbody);
				$providerbody = str_replace('{user_person}', $this->session->userdata('name'), $providerbody);
				$providerbody = str_replace('{service_title}', $service['service_title'], $providerbody);
				$providerbody = str_replace('{service_date}', $servicess['service_date'], $providerbody);
				$providerbody = str_replace('{service_time}', $time, $providerbody);
				$providerbody = str_replace('{location_user}', $servicess['location'], $providerbody);
				$providerbody = str_replace('{preview_link}', $preview_link, $providerbody);
				$providerbody .= $qr_message;

				//Send mail to provider
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');

				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($provider_data['email'])
						->subject('Service Booking')
						->message($providerbody)
						->send();
				}
				//Send mail to user
				$body = $this->load->view('user/email/service_email', $this->data, true);
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');
				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($this->session->userdata('email'))
						->subject('Service Booking')
						->message($body)
						->send();
				}

				$token = $this->session->userdata('chat_token');

				/* moyasar payment history entry */
				if ($inputs['cod'] == 2) { // not cod					
					$totamt = $_GET['amount'] / 100;
					$details['service_subscription_id'] = $service_id;
					$details['token'] = $this->session->userdata('chat_token');
					$details['user_provider_id'] = $this->session->userdata('id');
					$details['currency_code'] = $servicess['currency_code'];
					$details['amount'] = $servicess['amount'];
					$details['reason'] = "Book Service";
					$details['transaction_id'] = $_GET['id'];
					$details['created_at'] = date('Y-m-d H:i:s');
					$details['book_id'] = $result;
					$details['total_amount'] = $totamt;
					$details['type'] = $provider_data['type'];
					$details['paytype'] = 'moyasarpay';
					$this->db->insert('moyasar_table', $details);
				}

				// Cash On Delivery
				if ($inputs['cod'] == 1) {
					$codData['book_id'] = $result;
					$codData['user_id'] = $this->session->userdata('id');
					$codData['provider_id'] = $servicess['provider_id'];
					$codData['amount'] = $servicess['amount'];
					$codData['amount_to_pay'] = $amt;
					$codData['created_on'] = date('Y-m-d');
					$this->db->insert('book_service_cod', $codData);
				}

				/* history entry */
				$data = $this->api->get_book_info($result);

				$ptype = $this->db->select('type')->where('id', $data['provider_id'])->get('providers')->row()->type;

				$device_token = $this->api->get_device_info_multiple($data['provider_id'], $ptype);

				$user_name = $this->api->get_user_info($data['user_id'], 2);

				$provider_token = $this->api->get_user_info($data['provider_id'], $ptype);

				$protoken = $provider_token['token'];
				$pname = $this->db->select('name')->where('id', $data['provider_id'])->get('providers')->row()->name;
				$spname = $this->db->select('shop_name')->where('id', $service['shop_id'])->get('shops')->row()->shop_name;

				$text = $user_name['name'] . " has booked the Service '" . $service['service_title'] . "'";



				/*Notifification */
				$notimsg = $user_name['name'] . " has booked the service '" . $service['service_title'] . "' at " . $pname . "'s shop '" . $spname . "' and for the amount of " . $amt . $servicess['currency_code'];

				$receiver = '';
				$admin = $this->db->where('user_id', 1)->from('administrators')->get()->row_array();
				if (empty($admin['token'])) {
					$receiver = $this->getToken(14, $admin['user_id']);
					$receiver_token_update = $this->db->where('role', 1)->update('administrators', ['token' => $receiver]);
				} else {
					$receiver = $admin['token'];
				}


				$paynotimsg = $user_name['name'] . " paid the amount of " . $amt . $servicess['currency_code'] . " to " . $pname . " for the service '" . $service['service_title'] . "'";

				if (!empty($receiver)) {
					$this->bookingnotification($protoken, $receiver, $notimsg);
					$this->bookingnotification($protoken, $receiver, $paynotimsg);
				}
				$this->bookingnotification($protoken, $token, $notimsg);
				$this->bookingnotification($protoken, $token, $paynotimsg);

				$this->send_push_notification($token, $result, $ptype, $msg = $notimsg);
				$this->send_push_notification($token, $result, $ptype, $msg = $paynotimsg);



				$message = 'You have booked appointment successfully';
				$this->session->set_flashdata('success_message', $this->bookmsg);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => true, 'msg' => $this->bookmsg,  'title' => $this->BMsg, 'status' => 1]);
				} else {
					redirect(base_url('user-bookings'));
				}
			} else {
				$message = 'Sorry, Try again later...';
				$this->session->set_flashdata('error_message', $this->errmsg . " " . $_GET['message']);
				$s_id = $service_id;
				$this->cancel_appointment($bookid);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				} else {
					redirect(base_url('book-appointment/' . $s_id));
				}
			}
		} else {
			$message = 'Sorry, Try again later...';
			$s_id = $service_id;
			$this->cancel_appointment($bookid);

			$inputs = array();
			$data = array('error_message' => $this->errmsg . " " . $_GET['message']);
			$this->session->set_flashdata($data);
			if (!empty($this->input->get('type')) && $this->input->get('type') == 'cod') {
				echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
			} else {
				redirect(base_url('book-appointment/' . $s_id));
			}
		}
		exit;
	}

	public function book_stripe_payment()
	{

		$postdata = $this->input->post();

		if (settingValue('stripe_option') == 1) {
			$config['publishable_key'] = settingValue('publishable_key');
		} else {
			$config['live_publishable_key'] = settingValue('live_publishable_key');
		}
		$amt = $postdata['amount'] / 100;
		$paid_token = $postdata['id'];

		if ($this->input->post('booking_id') != '' && $bookid == '') {
			$bookid = $this->input->post('booking_id');
		}

		$servicess = $this->db->select('*')->where('id', $bookid)->get('book_service')->row_array();

		$service_id = $servicess['service_id'];


		$inputs = array();

		$inputs['cod'] = 2; // Online Payment
		$couponid = $this->input->post('couponid');
		if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
			$inputs['cod'] = 1;
			$amt = $this->input->post('totalamt');
			$paid_token = '';
		}

		if ((isset($postdata['status']) && $postdata['status'] == 'paid')  || $inputs['cod'] == 1) {


			if (!empty($couponid) && $couponid > 0) {
				$cinputs['couponid'] = $couponid;
			}

			$result = $bookid;

			if ($result != '') {
				$inputs['status'] = 2;
				$inputs['reason'] = '';
				$inputs['final_amount'] = $amt;
				$inputs['paid_tokenid'] = $paid_token;
				$inputs['paytype'] = 'stripe';

				$this->db->update('book_service', $inputs, array("id" => $result));

				$this->db->update('book_service', $cinputs, array("id" => $result));

				$ginputs['status'] = 2;
				$this->db->update('book_service', $ginputs, array("id" => $result));

				$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
				$this->data['service'] = $service;

				if ($servicess['autoschedule_session_no']  == 1) {
					$inputs['total_amount'] = 0;
					$inputs['final_amount'] = 0;
					$this->db->update('book_service', $inputs, array("parent_bookid" => $result));
				}


				// Coupon Count Update
				if (!empty($couponid) && $couponid > 0) {
					$couponqry = $this->db->select('user_limit, user_limit_count, used_user_id')->where('id', $couponid)->get('service_coupons')->row_array();
					$used_coupon = 	$couponqry['used_user_id'];
					if (!empty($used_coupon)) {
						$userids = $used_coupon . ',' . $this->session->userdata('id');
					} else {
						$userids = $this->session->userdata('id');
					}

					$cno = intval($couponqry['user_limit_count']) + 1;
					$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '" . $cno . "', `used_user_id` = '" . $userids . "' WHERE `id` = '" . $couponid . "'");
					if ($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno) {
						$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '" . $couponid . "'");
					}
				}

				// Reward Update
				$rewardid = $servicess['rewardid'];
				if (!empty($rewardid) && $rewardid > 0) {
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $rewardid . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
				}

				// Update Revenue
				$query = $this->db->query('select * from admin_commission where admin_id=1');
				$amount = $query->row();
				$pertage = $amount->commission;
				$vatper = settingValue('vat');

				if ($inputs['cod'] == 2) {
					$revenueInsert = [
						'date' => date('Y-m-d'),
						'provider' => $servicess['provider_id'],
						'service_id' => $service_id,
						'booking_id' => $result,
						'user' => $this->session->userdata('id'),
						'currency_code' => $servicess['currency_code'],
						'amount' => $service['service_amount'],
						'commission' => $pertage,
						'vat' => $vatper,
						'offersid' => $servicess['offersid'],
						'couponid' => $servicess['couponid'],
						'rewardid' => $servicess['rewardid'],
						'revenue_for' => 'Service Booking'
					];
					$revInsert = $this->db->insert('revenue', $revenueInsert);
				}

				$provider_data = $this->db->where('id', $servicess['provider_id'])->from('providers')->get()->row_array();
				$user_data = $this->db->where('id', $this->session->userdata('id'))->from('users')->get()->row_array();
				$this->data['provider'] = $provider_data;

				$preview_link = base_url();
				$time = $servicess['from_time'] . "-" . $servicess['to_time'];


				$bodyid = 3;
				$tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
				$providerbody = $tempbody_details['template_content'];
				$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
				$providerbody = str_replace('{sitetitle}', $this->site_name, $providerbody);
				$providerbody = str_replace('{user_person}', $this->session->userdata('name'), $providerbody);
				$providerbody = str_replace('{service_title}', $service['service_title'], $providerbody);
				$providerbody = str_replace('{service_date}', $servicess['service_date'], $providerbody);
				$providerbody = str_replace('{service_time}', $time, $providerbody);
				$providerbody = str_replace('{location_user}', $servicess['location'], $providerbody);
				$providerbody = str_replace('{preview_link}', $preview_link, $providerbody);
				$providerbody .= $qr_message;

				//Send mail to provider
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');

				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($provider_data['email'])
						->subject('Service Booking')
						->message($providerbody)
						->send();
				}
				//Send mail to user
				$body = $this->load->view('user/email/service_email', $this->data, true);
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');
				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($this->session->userdata('email'))
						->subject('Service Booking')
						->message($body)
						->send();
				}

				$token = $this->session->userdata('chat_token');

				$charges_array = array();
				$amount = (!empty($servicess['amount'])) ? $servicess['amount'] : 2;
				$amount = ($amount * 100);
				$charges_array['amount'] = $amount;
				$charges_array['currency'] = settings('currency');
				$charges_array['description'] = (!empty($servicess['amount'])) ? $servicess['amount'] : 'Booking';
				$charges_array['source'] = 'tok_visa';
				$this->load->library('stripe', $config);


				$results = $this->stripe->stripe_charges($charges_array);

				$results = json_decode($results, true);
				if (empty($result['error'])) {
					/* moyasar payment history entry */
					if ($inputs['cod'] == 2) { // not cod					
						$totamt = $postdata['amount'] / 100;
						$details['service_subscription_id'] = $service_id;
						$details['token'] = $this->session->userdata('chat_token');
						$details['user_provider_id'] = $this->session->userdata('id');
						$details['currency_code'] = $servicess['currency_code'];
						$details['amount'] = $servicess['amount'];
						$details['reason'] = "Book Service";
						$details['transaction_id'] = $postdata['id'];
						$details['created_at'] = date('Y-m-d H:i:s');
						$details['book_id'] = $result;
						$details['total_amount'] = $totamt;
						$details['type'] = $provider_data['type'];
						$details['paytype'] = 'stripe';
						$this->db->insert('moyasar_table', $details);
					}
				} else {
					$inputs['token'] = 'Issue - token_already_used';
					$message = 'Sorry, something went wrong';
					$this->session->set_flashdata('error_message', $message);
				}

				/* history entry */
				$data = $this->api->get_book_info($result);

				$ptype = $this->db->select('type')->where('id', $data['provider_id'])->get('providers')->row()->type;

				$device_token = $this->api->get_device_info_multiple($data['provider_id'], $ptype);

				$user_name = $this->api->get_user_info($data['user_id'], 2);

				$provider_token = $this->api->get_user_info($data['provider_id'], $ptype);

				$protoken = $provider_token['token'];
				$pname = $this->db->select('name')->where('id', $data['provider_id'])->get('providers')->row()->name;
				$spname = $this->db->select('shop_name')->where('id', $service['shop_id'])->get('shops')->row()->shop_name;

				$text = $user_name['name'] . " has booked the Service '" . $service['service_title'] . "'";



				/*Notifification */
				$notimsg = $user_name['name'] . " has booked the service '" . $service['service_title'] . "' at " . $pname . "'s shop '" . $spname . "' and for the amount of " . $amt . $servicess['currency_code'];

				$receiver = '';
				$admin = $this->db->where('user_id', 1)->from('administrators')->get()->row_array();
				if (empty($admin['token'])) {
					$receiver = $this->getToken(14, $admin['user_id']);
					$receiver_token_update = $this->db->where('role', 1)->update('administrators', ['token' => $receiver]);
				} else {
					$receiver = $admin['token'];
				}


				$paynotimsg = $user_name['name'] . " paid the amount of " . $amt . $servicess['currency_code'] . " to " . $pname . " for the service '" . $service['service_title'] . "'";

				if (!empty($receiver)) {
					$this->bookingnotification($protoken, $receiver, $notimsg);
					$this->bookingnotification($protoken, $receiver, $paynotimsg);
				}
				$this->bookingnotification($protoken, $token, $notimsg);
				$this->bookingnotification($protoken, $token, $paynotimsg);

				$this->send_push_notification($token, $result, $ptype, $msg = $notimsg);
				$this->send_push_notification($token, $result, $ptype, $msg = $paynotimsg);



				$message = 'You have booked appointment successfully';
				$this->session->set_flashdata('success_message', $this->bookmsg);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => true, 'msg' => $this->bookmsg,  'title' => $this->BMsg, 'status' => 1]);
				} else {
					echo json_encode(['success' => true]);
				}
			} else {
				$message = 'Sorry, Try again later...';
				$this->session->set_flashdata('error_message', $this->errmsg . " " . $_GET['message']);
				$s_id = $service_id;
				$this->cancel_appointment($bookid);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				} else {
					echo json_encode(['success' => false]);
				}
			}
		} else {
			$message = 'Sorry, Try again later...';
			$s_id = $service_id;
			$this->cancel_appointment($bookid);

			$inputs = array();
			$data = array('error_message' => $this->errmsg . " " . $_GET['message']);
			$this->session->set_flashdata($data);
			if (!empty($this->input->get('type')) && $this->input->get('type') == 'cod') {
				echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
			} else {
				echo json_encode(['success' => false]);
			}
		}
		exit;
	}

	public function paypal_payment($bookingid, $amount)
	{

		$token = $_GET['PayerID'];
		$couponid = $this->session->userdata('couponid');
		$amt = ($this->session->userdata('amount')) ? $this->session->userdata('amount') : $amount;

		$servicess = $this->db->select('*')->where('id', $bookingid)->get('book_service')->row_array();

		$service_id = $servicess['service_id'];

		if (!empty($couponid) && $couponid > 0) {
			$cinputs['couponid'] = $couponid;
		}
		$inputs = array();

		$inputs['cod'] = 2; // Online Payment

		$result = $bookingid;
		if ($result != '') {
			$inputs['status'] = 2;
			$inputs['reason'] = '';
			$inputs['final_amount'] = $amt;
			$inputs['paid_tokenid'] = $paid_token;
			$inputs['paytype'] = 'paypal';

			$this->db->update('book_service', $inputs, array("id" => $result));

			$this->db->update('book_service', $cinputs, array("id" => $result));

			$ginputs['status'] = 2;
			$this->db->update('book_service', $ginputs, array("id" => $result));


			$this->load->library('ciqrcode');
			$qr_image = rand() . '.png';
			$params['data'] = $result;
			$params['level'] = 'H';
			$params['size'] = 4;
			$params['savename'] = FCPATH . "uploads/qr_image/" . $qr_image;
			$qr_message = '';
			if ($this->ciqrcode->generate($params)) {
				$qr_img_url = $qr_image;
				$qr_details['qr_img_url'] = "uploads/qr_image/" . $qr_img_url;

				if ($this->db->update('book_service', $qr_details, array("id" => $result))) {
					$qrpaths = base_url() . 'uploads/qr_image/' . $qr_img_url;
					$qr_message .= '<div style="float:left; width:100%;">';
					$qr_message .= '<p style="font-weight: bold;color: coral;">QR CODE FOR YOUR APPOINTMENT</p>';
					$qr_message .= '<img src="' . $qrpaths . '" alt="QR CODE" />';
					$qr_message .= '</div>';
				}
			}



			$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
			$this->data['service'] = $service;

			if ($servicess['autoschedule_session_no']  == 1) {
				$inputs['total_amount'] = 0;
				$inputs['final_amount'] = 0;
				$this->db->update('book_service', $inputs, array("parent_bookid" => $result));
			}


			// Coupon Count Update
			if (!empty($couponid) && $couponid > 0) {
				$couponqry = $this->db->select('user_limit, user_limit_count, used_user_id')->where('id', $couponid)->get('service_coupons')->row_array();
				$used_coupon = 	$couponqry['used_user_id'];
				if (!empty($used_coupon)) {
					$userids = $used_coupon . ',' . $this->session->userdata('id');
				} else {
					$userids = $this->session->userdata('id');
				}

				$cno = intval($couponqry['user_limit_count']) + 1;
				$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '" . $cno . "', `used_user_id` = '" . $userids . "' WHERE `id` = '" . $couponid . "'");
				if ($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno) {
					$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '" . $couponid . "'");
				}
			}

			// Reward Update
			$rewardid = $servicess['rewardid'];
			if (!empty($rewardid) && $rewardid > 0) {
				$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $rewardid . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
			}

			// Update Revenue
			$query = $this->db->query('select * from admin_commission where admin_id=1');
			$amount = $query->row();
			$pertage = $amount->commission;
			$vatper = settingValue('vat');

			if ($inputs['cod'] == 2) {
				$revenueInsert = [
					'date' => date('Y-m-d'),
					'provider' => $servicess['provider_id'],
					'service_id' => $service_id,
					'booking_id' => $result,
					'user' => $this->session->userdata('id'),
					'currency_code' => $servicess['currency_code'],
					'amount' => $service['service_amount'],
					'commission' => $pertage,
					'vat' => $vatper,
					'offersid' => $servicess['offersid'],
					'couponid' => $servicess['couponid'],
					'rewardid' => $servicess['rewardid'],
					'revenue_for' => 'Service Booking'
				];
				$revInsert = $this->db->insert('revenue', $revenueInsert);
			}

			$provider_data = $this->db->where('id', $servicess['provider_id'])->from('providers')->get()->row_array();
			$user_data = $this->db->where('id', $this->session->userdata('id'))->from('users')->get()->row_array();
			$this->data['provider'] = $provider_data;

			$preview_link = base_url();
			$time = $servicess['from_time'] . "-" . $servicess['to_time'];


			$bodyid = 3;
			$tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
			$providerbody = $tempbody_details['template_content'];
			$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
			$providerbody = str_replace('{sitetitle}', $this->site_name, $providerbody);
			$providerbody = str_replace('{user_person}', $this->session->userdata('name'), $providerbody);
			$providerbody = str_replace('{service_title}', $service['service_title'], $providerbody);
			$providerbody = str_replace('{service_date}', $servicess['service_date'], $providerbody);
			$providerbody = str_replace('{service_time}', $time, $providerbody);
			$providerbody = str_replace('{location_user}', $servicess['location'], $providerbody);
			$providerbody = str_replace('{preview_link}', $preview_link, $providerbody);
			$providerbody .= $qr_message;

			//Send mail to provider
			$phpmail_config = settingValue('mail_config');
			if (isset($phpmail_config) && !empty($phpmail_config)) {
				if ($phpmail_config == "phpmail") {
					$from_email = settingValue('email_address');
				} else {
					$from_email = settingValue('smtp_email_address');
				}
			}
			$this->load->library('email');

			if (!empty($from_email) && isset($from_email)) {
				$mail = $this->email
					->from($from_email)
					->to($provider_data['email'])
					->subject('Service Booking')
					->message($providerbody)
					->send();
			}
			//Send mail to user
			$body = $this->load->view('user/email/service_email', $this->data, true);
			$phpmail_config = settingValue('mail_config');
			if (isset($phpmail_config) && !empty($phpmail_config)) {
				if ($phpmail_config == "phpmail") {
					$from_email = settingValue('email_address');
				} else {
					$from_email = settingValue('smtp_email_address');
				}
			}
			$this->load->library('email');
			if (!empty($from_email) && isset($from_email)) {
				$mail = $this->email
					->from($from_email)
					->to($this->session->userdata('email'))
					->subject('Service Booking')
					->message($body)
					->send();
			}

			$token = $this->session->userdata('chat_token');

			/* moyasar payment history entry */
			if ($inputs['cod'] == 2) { // not cod					
				$totamt = $postdata['amount'] / 100;
				$details['service_subscription_id'] = $service_id;
				$details['token'] = $this->session->userdata('chat_token');
				$details['user_provider_id'] = $this->session->userdata('id');
				$details['currency_code'] = $servicess['currency_code'];
				$details['amount'] = $servicess['amount'];
				$details['reason'] = "Book Service";
				$details['transaction_id'] = $token;
				$details['created_at'] = date('Y-m-d H:i:s');
				$details['book_id'] = $result;
				$details['total_amount'] = $totamt;
				$details['type'] = $provider_data['type'];
				$details['paytype'] = 'paypal';
				$this->db->insert('moyasar_table', $details);
			}

			/* history entry */
			$data = $this->api->get_book_info($result);

			$ptype = $this->db->select('type')->where('id', $data['provider_id'])->get('providers')->row()->type;

			$device_token = $this->api->get_device_info_multiple($data['provider_id'], $ptype);

			$user_name = $this->api->get_user_info($data['user_id'], 2);

			$provider_token = $this->api->get_user_info($data['provider_id'], $ptype);

			$protoken = $provider_token['token'];
			$pname = $this->db->select('name')->where('id', $data['provider_id'])->get('providers')->row()->name;
			$spname = $this->db->select('shop_name')->where('id', $service['shop_id'])->get('shops')->row()->shop_name;

			$text = $user_name['name'] . " has booked the Service '" . $service['service_title'] . "'";



			/*Notifification */
			$notimsg = $user_name['name'] . " has booked the service '" . $service['service_title'] . "' at " . $pname . "'s shop '" . $spname . "' and for the amount of " . $amt . $servicess['currency_code'];

			$receiver = '';
			$admin = $this->db->where('user_id', 1)->from('administrators')->get()->row_array();
			if (empty($admin['token'])) {
				$receiver = $this->getToken(14, $admin['user_id']);
				$receiver_token_update = $this->db->where('role', 1)->update('administrators', ['token' => $receiver]);
			} else {
				$receiver = $admin['token'];
			}


			$paynotimsg = $user_name['name'] . " paid the amount of " . $amt . $servicess['currency_code'] . " to " . $pname . " for the service '" . $service['service_title'] . "'";

			if (!empty($receiver)) {
				$this->bookingnotification($protoken, $receiver, $notimsg);
				$this->bookingnotification($protoken, $receiver, $paynotimsg);
			}
			$this->bookingnotification($protoken, $token, $notimsg);
			$this->bookingnotification($protoken, $token, $paynotimsg);

			$this->send_push_notification($token, $result, $ptype, $msg = $notimsg);
			$this->send_push_notification($token, $result, $ptype, $msg = $paynotimsg);

			if ($this->session->userdata('couponid') && $this->session->userdata('amount')) {
				$this->session->unset_userdata('couponid');
				$this->session->unset_userdata('amount');
			}

			$message = 'You have booked appointment successfully';
			$this->session->set_flashdata('success_message', $this->bookmsg);

			$inputs = array();
			if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
				echo json_encode(['success' => true, 'msg' => $this->bookmsg,  'title' => $this->BMsg, 'status' => 1]);
			} else {
				redirect(base_url('user-bookings'));
			}
		} else {
			$message = 'Sorry, Try again later...';
			$this->session->set_flashdata('error_message', 'Something went wrong, Try again later!!');
			$s_id = $service_id;
			$this->cancel_appointment($bookid);

			$inputs = array();
			if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
				echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
			} else {
				redirect(base_url('book-appointment/' . $s_id));
			}
		}
	}
	public function bookingnotification($sender, $receiver, $message)
	{
		$data = array(
			'sender' => $sender,
			'receiver' => $receiver,
			'message' => $message,
			'status' => 1,
			'utc_date_time' => (date('Y-m-d H:i:s')),
			'created_at' => date('Y-m-d H:i:s')
		);

		$ret = $this->db->insert('notification_table', $data);
	}

	public function staff_availability()
	{

		$booking_date = $this->input->post('date');
		$provider_id  = $this->input->post('provider_id');
		$staff_id     = $this->input->post('staff_id');
		$service_id   = $this->input->post('service_id');
		$book_id	  = $this->input->post('book_id');
		$pptype       = $this->input->post('procate');

		$shop_id      = $this->input->post('shop_id');
		$duration     = $this->input->post('duration');
		$dur_in   = $this->input->post('dur_in');

		if ($dur_in == 'hr(s)') {
			$duration = $duration * 60;
		}

		$timestamp = strtotime($booking_date);
		$day = date('D', $timestamp);

		if (strpos($staff_id, ",") !== false) {
			$stfarr = explode(",", $staff_id);
			$time_array = array();
			foreach ($stfarr as $sarr) {
				$read_available = $this->appointment->readAvailability($sarr, $provider_id, $booking_date);
				$tmearr = explode("-", $read_available);

				$t_start = strtotime($tmearr[0]);
				$t_end = strtotime($tmearr[1]);
				$f_railwayhrs = date('H:i:s', ($t_start));
				$t_railwayhrs = date('H:i:s', ($t_end));
				$s_railwayhrs = strtotime($f_railwayhrs);
				$e_railwayhrs = strtotime($t_railwayhrs);

				$time_array[] = $this->appointment->get_time_slots($s_railwayhrs, $e_railwayhrs, $duration);
			}

			$newtime_arr = array();
			$firstarr = $time_array[0];
			for ($i = 1; $i < count($time_array); $i++) {
				$newtime_arr = array_intersect($firstarr, $time_array[$i]);
				$firstarr = $newtime_arr;
			}
			array_values($newtime_arr);
			$from_time = reset($newtime_arr);
			$to_time = end($newtime_arr);
			if (!empty($from_time)) {
				$temp_start_time = $from_time;
				$temp_end_time = $to_time;
			} else {
				$temp_start_time = '';
				$temp_end_time = '';
			}
		} else {
			if ($pptype != 4) { // Not a Freelancer
				$staff_details = $this->appointment->provider_hours($staff_id, $provider_id);
			} else {
				$staff_details = $this->appointment->shop_hours($shop_id, $provider_id);
			}
			$availability_details = json_decode($staff_details['availability'], true);

			/* Single Staff Business Hours */
			$alldays = false;
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
			/* Single Staff Business Hours */
		}

		if (!empty($from_time)) {
			$temp_start_time = $from_time;
			$temp_end_time = $to_time;
		} else {
			$temp_start_time = '';
			$temp_end_time = '';
		}

		$replace = array('AM', 'PM');
		$temp_start_time = $temp_start_time;
		$start_time_array = '';
		$end_time_array = '';

		$timestamp_start = strtotime($temp_start_time);
		$timestamp_end = strtotime($temp_end_time);

		$timing_array = array();

		$counter = 1;

		$from_time_railwayhrs = date('G:i:s', ($timestamp_start));
		$to_time_railwayhrs = date('G:i:s', ($timestamp_end));

		$timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
		$timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);

		$i = 1;
		while ($timestamp_start_railwayhrs < $timestamp_end_railwayhrs) {

			$temp_start_time_ampm = date('G:i:s', ($timestamp_start_railwayhrs));
			$temp_end_time_ampm = date('G:i:s', (($timestamp_start_railwayhrs) + 60 * 60 * 1));

			$timestamp_start_railwayhrs = strtotime($temp_end_time_ampm);

			$timing_array[] = array('id' => $i, 'start_time' => $temp_start_time_ampm, 'end_time' => $temp_end_time_ampm);

			if ($counter > 24) {
				break;
			}

			$counter += 1;
			$i++;
		}



		// Booking availability

		$service_date = $booking_date;
		$booking_count = $this->appointment->get_bookings($service_date, $service_id, $staff_id);
		$new_timingarray = array();
		$mat_timingarray = array();

		if (is_array($booking_count) && empty($booking_count)) {
			$new_timingarray = $timing_array;
		} elseif (is_array($booking_count) && $booking_count != '') {
			foreach ($timing_array as $t => $timing) {
				$match_found = false;

				$explode_st_time = explode(':', $timing['start_time']);
				$explode_value = $explode_st_time[0];

				$explode_endtime = explode(':', $timing['end_time']);
				$explode_endval = $explode_endtime[0];


				if (strlen($explode_value) == 1) {
					$timing['start_time'] = "0" . $explode_st_time[0] . ":" . $explode_st_time[1] . ":" . $explode_st_time[2];
				}

				if (strlen($explode_endval) == 1) {
					$timing['end_time'] = "0" . $explode_endtime[0] . ":" . $explode_endtime[1] . ":" . $explode_endtime[2];
				}

				foreach ($booking_count as $bookings) {
					if ($bookings['id'] != $book_id) {
						if (strtotime($timing['start_time']) == strtotime($bookings['from_time']) && strtotime($timing['end_time']) == strtotime($bookings['to_time'])) {
							$mat_timingarray[$t] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
							$match_found = true;
							break;
						}
					}
				}

				if ($match_found == false) {
					$new_timingarray[] = array('start_time' => $timing['start_time'], 'end_time' => $timing['end_time']);
				}
			}
		}
		$new_timingarray = array_filter($new_timingarray);

		if (!empty($new_timingarray)) {
			$starttime1 = $date . ' ' . $from_time_railwayhrs;  //start time as string
			$endtime1   = $date . ' ' . $to_time_railwayhrs; //end time as string

			$start_time = strtotime($starttime1);
			$end_time = strtotime($endtime1);
			$slot = strtotime(date('Y-m-d H:i:s', $start_time) . ' +' . $duration . ' minutes');

			$current_time = strtotime(date('Y-m-d H:i:s'));
			$data = [];
			for ($i = 0; $slot <= $end_time; $i++) {
				if ($book_id > 0) {
					$disable_val = false;
				} else {
					if (date('Y-m-d', strtotime($booking_date)) == date('Y-m-d')) {
						if ($start_time <= $current_time && $current_time >= $slot) {
							$disable_val = true;
						} else {
							$disable_val = false;
						}
					} else {
						$disable_val = false;
					}
				}


				if ((strtotime($mat_timingarray[$i]['start_time']) != $start_time) && (strtotime($mat_timingarray[$i]['end_time']) != $slot)) {
					if (!$disable_val) {
						$data[$i] = [
							'start_time' => date('h:i A', $start_time),
							'end_time' => date('h:i A', $slot),
							'disable_val' => $disable_val
						];
					}
				}
				$start_time = $slot;
				$slot = strtotime(date('Y-m-d H:i:s', $start_time) . ' +' . $duration . ' minutes');
			}

			if (!empty($data)) {
				$serviceavailability['time'] = $data;
			} else {
				$serviceavailability['time'] = '';
			}
		} else {
			$serviceavailability = '';
		}
		$isoffers = $this->input->post('offers');
		if ($isoffers == 1) {

			$bkdate = date('Y-m-d', strtotime($booking_date));
			$offerqry = $this->db->where("status", 0)->where("df", 0)->where("service_id", $service_id)->get("service_offers")->row_array();
			if ($offerqry['id'] > 0) {
				$date_offers = $this->db->where("status", 0)->where("df", 0)->where("service_id", $service_id)->where('start_date <=', $bkdate)->where('end_date >=', $bkdate)->get("service_offers")->row_array();


				$OfrMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Alert'])) ? $this->user_language[$this->user_selected]['lg_Offers_Alert'] : $this->default_language['en']['lg_Offers_Alert'];
				$OfrDateMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Warning_Date'])) ? $this->user_language[$this->user_selected]['lg_Offers_Warning_Date'] : $this->default_language['en']['lg_Offers_Warning_Date'];
				$OfrWarnMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Warning_txt'])) ? $this->user_language[$this->user_selected]['lg_Offers_Warning_txt'] : $this->default_language['en']['lg_Offers_Warning_txt'];

				$fromtxt = (!empty($this->user_language[$this->user_selected]['lg_From'])) ? $this->user_language[$this->user_selected]['lg_From'] : $this->default_language['en']['lg_From'];
				$totxt = (!empty($this->user_language[$this->user_selected]['lg_To'])) ? $this->user_language[$this->user_selected]['lg_To'] : $this->default_language['en']['lg_To'];

				$wtxt = $OfrDateMsg . ' ' . $OfrWarnMsg . ' - ' . date("d M", strtotime($offerqry['start_date'])) . ' ' . $totxt . ' ' . date("d M, Y", strtotime($offerqry['end_date'])) . ' ' . $fromtxt . ' ' . date("G:i A", strtotime($offerqry['start_time'])) . ' - ' . date("G:i A", strtotime($offerqry['end_time']));

				if ($date_offers['id'] > 0) {
					$serviceavailability['offers'] = array("title" => $OfrMsg, "msg" => "success", "id" => $date_offers['id']);
				} else {
					$serviceavailability['offers'] = array("title" => $OfrMsg, "msg" => $wtxt, "id" => "0");
				}
			} else {
				if ($serviceavailability) {
					$serviceavailability['offers'] = '';
				}
			}
		} else {
			$serviceavailability['offers'] = '';
		}

		if (!isset($serviceavailability)) {
			$serviceavailability = '';
		}
		echo json_encode($serviceavailability);
	}

	public function book_staffservice()
	{

		$user_currency = get_user_currency();
		$user_currency_code = $user_currency['user_currency_code'];


		removeTag($this->input->post());
		$final_amount = $this->input->post('final_amount');

		$time = $this->input->post('booking_time');
		$slots = json_encode($time);
		$start_time = '';
		$end_time = '';
		$from_time =  '';
		$to_time =  '';

		$inputs = array();
		$service_id = $this->input->post('service_id'); // Package ID  		
		$records = $this->appointment->get_service($service_id);
		$cal_seramt = $this->input->post('total_amt');

		$inputs['service_id']    = $service_id;
		$inputs['provider_id']   = $this->input->post('provider_id');
		$inputs['user_id']       = $this->session->userdata('id');
		$inputs['slots']     = $slots;

		$inputs['booking_amnt'] = $final_amount;

		$inputs['shop_id']       = $this->input->post('shop_id');
		$inputs['staff_id']      = get_staff_id_by_service($service_id);

		$inputs['location'] = $this->input->post('service_location');
		$inputs['latitude'] = $this->input->post('service_latitude');
		$inputs['longitude'] = $this->input->post('service_longitude');

		$inputs['service_date'] = date('Y-m-d');
		$inputs['from_time'] = $from_time;
		$inputs['to_time'] = $to_time;
		$inputs['notes']      = $this->input->post('notes');

		$inputs['tokenid'] = 'old type';
		$inputs['cod'] = 0;



		$inputs['currency_code'] = $records['currency_code'];
		$inputs['amount'] = $records['service_amount'];


		$inputs['updated_on']  = (date('Y-m-d H:i:s'));
		$inputs['home_service'] = $this->input->post('service_at');
		$inputs['autoschedule_session_no'] = $this->input->post('isauto');

		$inputs['offersid'] = $this->input->post('offersid');
		$inputs['couponid'] = $this->input->post('couponid');
		$inputs['rewardid'] = $this->input->post('rewardid');
		$rtype = $this->input->post('rtype');

		$inputs['request_date'] = date('Y-m-d');
		$inputs['request_time'] = date('H:i:s', time());
		$inputs['status'] = 8; // On-Hold Status

		$book_id = $this->input->post('book_id');
		$pptype   = $this->input->post('procate');

		if ($book_id > 0) {
			$qry = $this->db->select('total_amount, final_amount')->where('id', $book_id)->get('book_service')->row_array();
			$tot = $qry['total_amount'];
			$inputs['total_amount'] = $tot;
			$inputs['final_amount'] = $qry['final_amount'];
		} else {
			$addiamt = 0;
			if (!empty($this->input->post('addiser'))) {
				$inputs['additional_services'] = implode(',', $this->input->post('addiser'));
				$addiamt = array_sum($this->input->post('addiamt'));
			}

			$totalamt = $cal_seramt;

			//Total Calculation 
			$total_amt_val = $totalamt + $addiamt;
			if ($total_amt_val <= 0) $total_amt_val = 0;
			$inputs['total_amount'] = $total_amt_val;
			$inputs['final_amount'] = $total_amt_val;



			// If Offers available
			if ($inputs['offersid'] > 0) {
				$offers = $this->db->where("id", $inputs['offersid'])->get("service_offers")->row_array();

				$new_serviceamt = $inputs['total_amount'];
				$offerPrice = '';
				$offersid = 0;
				if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
					$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100;
					if (is_nan($offerPrice)) $offerPrice = 0;
					$offerPrice = number_format($offerPrice, 2);
					$offersid = $offers['id'];

					$new_serviceamt  = $new_serviceamt - $offerPrice;
					$new_serviceamt  = number_format($new_serviceamt, 2);
					if ($new_serviceamt <= 0) $new_serviceamt = 0;
					$inputs['total_amount'] = $new_serviceamt;
					$inputs['final_amount'] = $new_serviceamt;
					$inputs['offersid'] = $offersid;
				}
			}

			// If Rewards available
			if ($inputs['rewardid'] > 0) {
				$reward = $this->db->where("id", $inputs['rewardid'])->get("service_rewards")->row_array();
				$re_serviceamt = $inputs['total_amount'];
				$rewardPrice = '';
				$rewardid = 0;
				if ($rtype == '') {
					if (!empty($reward['reward_type']) && $reward['reward_type'] == 1 && $reward['id'] > 0) {
						$rewardPrice = ($re_serviceamt) * $reward['reward_discount'] / 100;
						if (is_nan($rewardPrice)) $rewardPrice = 0;
						$rewardPrice = number_format($rewardPrice, 2);
						$rewardid = $reward['id'];

						$re_serviceamt  = $re_serviceamt - $rewardPrice;
						$re_serviceamt  = number_format($re_serviceamt, 2);
						if ($re_serviceamt <= 0) $re_serviceamt = 0;
						$inputs['total_amount'] = $re_serviceamt;
						$inputs['final_amount'] = $re_serviceamt;
						$inputs['rewardid'] = $rewardid;
					}
				}
			}
		}




		//Booking					
		if ($book_id == 0) {
			if ($rtype != '' && $rtype == '0') { // Free Service
				$inputs['cod'] = 0;
				$inputs['request_date'] = date('Y-m-d');
				$inputs['request_time'] = date('H:i:s', time());
				$bookres = $this->appointment->booking_success($inputs);
				if ($bookres > 0) {
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $inputs['rewardid'] . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
					$message = 'You have booked appointment successfully';
					$this->session->set_flashdata('success_message', $this->bookmsg);
					echo json_encode(['success' => true, 'msg' => $this->bookmsg, 'status' => 1, 'title' => $this->BMsg, 'bookid' => $bookres]);
				} else {
					$this->session->set_flashdata('error_message', $this->errmsg);
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				}
			} else {
				$result = $this->appointment->booking_success($inputs);
				if ($result != '' && $result > 0) {
					if ($inputs['autoschedule_session_no']  == 1) {
						$myservice = $this->db->select('service_title,autoschedule, autoschedule_days, autoschedule_session')->where('id', $service_id)->from('services')->get()->row_array();
						if ($myservice['autoschedule_session'] != 0 && $myservice['autoschedule_days'] != 0) {
							$days = 0;
							$d = 2;
							for ($s = 1; $s < intval($myservice['autoschedule_session']); $s++) {
								$days = intval($myservice['autoschedule_days']);
								$newdate = date("Y-m-d", strtotime("+" . $days . " day", strtotime($inputs['service_date'])));
								$session_no = $d++;
								$inputs['amount'] = 0;
								$inputs['service_date'] = $newdate;
								$inputs['notes']      = $myservice['service_title'] . "  - Session(" . $session_no . ")";
								$inputs['autoschedule_session_no'] = $session_no;
								$inputs['parent_bookid'] = $result;

								$this->appointment->booking_success($inputs);
							}
						}
					}
					$message = $this->BAMsg;
					$this->session->set_flashdata('success_message', $message);

					echo json_encode(['success' => true, 'msg' => $message,  'title' => $this->BMsg, 'status' => 4, 'bookid' => $result]);
					exit;
				} else {

					$message = 'Sorry, Try again later...';
					$this->session->set_flashdata('error_message', $this->errmsg);
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				}
			}
		}

		// Updation
		if ($book_id > 0) {
			$inputs['request_date'] = date('Y-m-d');
			$inputs['request_time'] = date('H:i:s', time());
			$inputs['status'] = 2;

			if ($this->db->update('book_service', $inputs, array("id" => $book_id))) {

				$token = $this->session->userdata('chat_token');
				$data = $this->api->get_book_info($book_id);
				$user_name = $this->api->get_user_info($data['user_id'], 2);
				$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
				$text = $user_name['name'] . " has edited the Booked Service '" . $service['service_title'] . "'";

				$ptype = $this->db->select('type')->where('id', $inputs['provider_id'])->get('providers')->row()->type;
				$this->send_push_notification($token, $book_id, $ptype, $msg = $text);

				$this->session->set_flashdata('success_message', $this->book_editmsg);
				echo json_encode(['success' => true, 'msg' => $this->book_editmsg,  'title' => $this->BMsg, 'status' => 5]);
			} else {
				$this->session->set_flashdata('error_message', $this->errmsg);
				echo json_encode(['success' => false, 'msg' => $this->errmsg,  'title' => $this->BMsg, 'status' => 2]);
			}
		}

		exit;
	}


	function checkIfExist($fromTime, $toTime, $input)
	{
		$fromDateTime = DateTime::createFromFormat("!H:i", $fromTime);
		$toDateTime = DateTime::createFromFormat('!H:i', $toTime);
		$inputDateTime = DateTime::createFromFormat('!H:i', $input);
		if ($fromDateTime > $toDateTime) $toDateTime->modify('+1 day');
		return ($fromDateTime <= $inputDateTime && $inputDateTime < $toDateTime) || ($fromDateTime <= $inputDateTime->modify('+1 day') && $inputDateTime <= $toDateTime);
	}

	function check_availability()
	{
		$action = $_POST['action'];
		$service_date = $_POST['service_date'];
		$service_id = $_POST['service_id'];
		$book_id  = $_POST['book_id'];
		$staff_id = $_POST['staff_id'];
		$shop_id = $_POST['shop_id'];
		$pptype = $_POST['pptype'];
		$provider_id = $_POST['provider_id'];
		$time = $_POST['time'];
		$booking_time = explode('-', $time);

		$timestamp = strtotime($service_date);
		$day = date('D', $timestamp);

		if ($pptype != 4) { // Not a Freelancer
			$staff_details = $this->appointment->provider_hours($staff_id, $provider_id);
		} else {
			$staff_details = $this->appointment->shop_hours($shop_id, $provider_id);
		}
		$availability_details = json_decode($staff_details['availability'], true);

		$alldays = false;
		foreach ($availability_details as $details) {
			if (isset($details['day']) && $details['day'] == 0) {
				if (isset($details['from_time']) && !empty($details['from_time'])) {
					if (isset($details['to_time']) && !empty($details['to_time'])) {
						$from_time1 = $details['from_time'];
						$to_time1 = $details['to_time'];
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
					$from_time1 = $availability['from_time'];
					$to_time1 = $availability['to_time'];
				}
			}
		}

		if (!empty($from_time1)) {
			$temp_start_time = $from_time1;
			$temp_end_time = $to_time1;
		} else {
			$message = 'Booking not available';
			$this->session->set_flashdata('error_message', $this->nobookmsg);
			echo json_encode(['success' => false, 'msg' => $this->nobookmsg,  'title' => $this->BMsg, 'status' => 3]);
			exit;
		}



		$start_time_array = '';
		$end_time_array = '';


		$timestamp_start = strtotime($temp_start_time);
		$timestamp_end = strtotime($temp_end_time);

		$timing_array = array();

		$counter = 1;

		$from_time_railwayhrs = date('H:i:s', ($timestamp_start));
		$to_time_railwayhrs = date('H:i:s', ($timestamp_end));

		$timestamp_start_railwayhrs = strtotime($from_time_railwayhrs);
		$timestamp_end_railwayhrs = strtotime($to_time_railwayhrs);


		// Booking availability


		$booking_from_time = $booking_time[0];
		$booking_end_time = $booking_time[1];

		$timestamp_from = strtotime($booking_from_time);
		$timestamp_to = strtotime($booking_end_time);

		$from_time_railwayhrs = date('H:i:s', ($timestamp_from));
		$to_time_railwayhrs = date('H:i:s', ($timestamp_to));



		$from_rh = date('H:i', strtotime($from_time_railwayhrs));
		$to_rh   = date('H:i', strtotime($to_time_railwayhrs));

		/* Booking Available */
		$bookingcount = $this->appointment->get_bookings_date($service_date, $service_id, $from_time_railwayhrs, $to_time_railwayhrs, $inputs['staff_id'], $book_id);
		$book_noslot = '';
		if (count($bookingcount) > 0) {
			$book_noslot = 1;
			$message = 'Booking is not available for the selected time slot...';
			$this->session->set_flashdata('error_message', $this->nobookmsg);
			echo json_encode(['success' => false, 'msg' => $this->nobookmsg,  'title' => $this->BMsg, 'status' => 3]);
			exit;
		}
		/* Booking Available */

		if ($_POST['servicefor'] == 1) {
			/* User Booking & Time Slot Checking */
			$user_noslot = '';
			$user_booking_count = $this->appointment->user_get_bookings($service_date, $from_time_railwayhrs, $to_time_railwayhrs, $book_id);
			if (count($user_booking_count) > 0) {
				$user_noslot = 1;
				$message = 'You already booked the selected time slot';
				$this->session->set_flashdata('error_message', $this->btimemsg);
				echo json_encode(['success' => false, 'msg' => $this->btimemsg,  'title' => $this->BMsg, 'status' => 3]);
				exit;
			}
			$usertime_booking_count = $this->appointment->user_time_bookings($service_date, $book_id, $action);

			$user_noslot = '';
			if (count($usertime_booking_count) > 0) {
				foreach ($usertime_booking_count as  $b => $bookedtime) {
					$ufromTime = date('H:i', strtotime($bookedtime['from_time']));
					$utoTime   = date('H:i', strtotime($bookedtime['to_time']));
					$user_noslot   = $this->checkIfExist($ufromTime, $utoTime, $from_rh);
					if ($user_noslot != 1) {
						$user_noslot = $this->checkIfExist($ufromTime, $utoTime, $to_rh);
						if ($user_noslot == 1) break;
					} else {
						break;
					}
				}
			}
			if ($user_noslot == 1) {
				$message = 'You have another booking at the selected  time slot';
				$this->session->set_flashdata('error_message', $this->anothermsg);
				echo json_encode(['success' => false, 'msg' => $this->anothermsg, 'title' => $this->BMsg, 'status' => 3]);
				exit;
			}
			/*  User Booking & Time Slot Checking */
		}

		/* Staff Available */
		$staff_id = $this->input->post('staff_id');
		$staff_book_count = $this->appointment->get_bookings_staff($service_date, $staff_id, $book_id, $action);

		$noslot = '';
		if (count($staff_book_count) > 0) {
			foreach ($staff_book_count as  $b => $booked_time) {
				$fromTime = date('H:i', strtotime($booked_time['from_time']));
				$toTime   = date('H:i', strtotime($booked_time['to_time']));
				$noslot   = $this->checkIfExist($fromTime, $toTime, $from_rh);
				if ($noslot != 1) {
					$noslot = $this->checkIfExist($fromTime, $toTime, $to_rh);
					if ($noslot == 1) break;
				} else {
					break;
				}
			}
		}
		if ($noslot == 1) {
			$message = 'Staff is not available for the selected time slot...';
			$this->session->set_flashdata('error_message', $this->bstaffmsg);
			echo json_encode(['success' => false, 'msg' => $this->bstaffmsg, 'title' => $this->BMsg, 'status' => 3]);
			exit;
		}
		/* Staff Available */

		$isoffers = $this->input->post('offers');
		if ($isoffers == 1) {
			$offerqry = $this->db->where("status", 0)->where("df", 0)->where("service_id", $service_id)->get("service_offers")->row_array();

			if ($offerqry['id'] > 0) {
				$bkdate = date('Y-m-d', strtotime($service_date));
				$current_time = date('H:i:s');

				$time_offers = $this->db->where("status", 0)->where("df", 0)->where("service_id", $service_id)->where('start_date <=', $bkdate)->where('end_date >=', $bkdate)->where("'$from_time_railwayhrs' BETWEEN start_time AND end_time", NULL, FALSE)->where("'$to_time_railwayhrs' BETWEEN start_time AND end_time", NULL, FALSE)->get("service_offers")->row_array();


				$OfrMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Alert'])) ? $this->user_language[$this->user_selected]['lg_Offers_Alert'] : $this->default_language['en']['lg_Offers_Alert'];
				$OfrDateMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Warning_Time'])) ? $this->user_language[$this->user_selected]['lg_Offers_Warning_Time'] : $this->default_language['en']['lg_Offers_Warning_Time'];
				$OfrWarnMsg = (!empty($this->user_language[$this->user_selected]['lg_Offers_Warning_txt'])) ? $this->user_language[$this->user_selected]['lg_Offers_Warning_txt'] : $this->default_language['en']['lg_Offers_Warning_txt'];

				$wtxt = $OfrDateMsg . ' ' . $OfrWarnMsg . ' - ' . date("d M", strtotime($offerqry['start_date'])) . ' to ' . date("d M, Y", strtotime($offerqry['end_date'])) . ' from ' . date("G:i A", strtotime($offerqry['start_time'])) . ' - ' . date("G:i A", strtotime($offerqry['end_time']));

				$fromtxt = (!empty($this->user_language[$this->user_selected]['lg_From'])) ? $this->user_language[$this->user_selected]['lg_From'] : $this->default_language['en']['lg_From'];
				$totxt = (!empty($this->user_language[$this->user_selected]['lg_To'])) ? $this->user_language[$this->user_selected]['lg_To'] : $this->default_language['en']['lg_To'];

				if ($time_offers['id'] > 0) {
					echo json_encode(['success' => true, 'title' => $OfrMsg, 'msg' => "success", 'status' => 4, "id" => $time_offers['id']]);
				} else {
					echo json_encode(['success' => true, 'msg' => $wtxt, 'title' => $OfrMsg, 'status' => 4, "id" => "0"]);
				}
			}
		}
	}

	public function load_more_guests()
	{
		$shop_id = $this->input->post('shop_id');
		$user_id = $this->input->post('provider_id');
		$this->data['shop_id'] = $shop_id;
		$this->data['user_id'] = $user_id;
		$this->data['count'] = $this->input->post('count');
		$this->data['procate'] = $this->input->post('procate');
		$this->data['module'] = 'appointment';
		$this->data['page'] = 'ajax_appointment';
		$result['more_guests'] = $this->load->view($this->data['theme'] . '/' . $this->data['module'] . '/' . $this->data['page'], $this->data, TRUE);
		echo json_encode($result);
	}


	public function get_available_data()
	{
		$shop_id = $this->input->post('shop_id');
		$user_id = $this->input->post('user_id');
		if (!empty($shop_id) && $shop_id > 0) {
			$shophours = $this->appointment->shop_hours($shop_id, $user_id);
			$shplocate = $this->db->select('shop_location')->where('id', $shop_id)->get('shops')->row()->shop_location;
		} else {
			$shophours = $this->db->where('provider_id', $user_id)->get('business_hours')->row_array();
			$shplocate = '';
		}

		$arr = '';
		if ($shophours['all_days'] == 0) {
			$daysarr = json_decode($shophours['availability'], true);
			foreach ($daysarr as $val) {
				if ($val['day'] == 7) $val['day'] = 0;
				$arr .=  $val['day'] . ",";
			}
			$arr = rtrim($arr, ',');
		}
		$data['shop_location'] = $shplocate;
		$data['shop_hours'] = $arr;

		echo json_encode($data);
	}

	/* push notification */

	public function send_push_notification($token, $service_id, $type, $msg)
	{

		$data = $this->api->get_book_info($service_id);
		if (!empty($data)) {
			if ($type == 1) {
				$device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 1);
			} else if ($type == 3) {
				$device_tokens = $this->api->get_device_info_multiple($data['provider_id'], 3);
			} else {
				$device_tokens = $this->api->get_device_info_multiple($data['user_id'], 2);
			}
			if ($type == 2) {
				$user_info = $this->api->get_user_info($data['user_id'], $type);
			} else {
				$user_info = $this->api->get_user_info($data['provider_id'], $type);
			}



			/* insert notification */

			if (!empty($user_info['token'])) {
				$this->bookingnotification($token, $user_info['token'], $msg);
			}

			$title = $data['service_title'];


			if (!empty($device_tokens)) {
				foreach ($device_tokens as $key => $device) {
					if (!empty($device['device_type']) && !empty($device['device_id'])) {

						if (strtolower($device['device_type']) == 'android') {

							$notify_structure = array(
								'title' => $title,
								'message' => $msg,
								'image' => 'test22',
								'action' => 'test222',
								'action_destination' => 'test222',
							);

							sendFCMMessage($notify_structure, $device['device_id']);
						}

						if (strtolower($device['device_type'] == 'ios')) {
							$notify_structure = array(
								'alert' => $msg,
								'sound' => 'default',
								'badge' => 0,
							);


							sendApnsMessage($notify_structure, $device['device_id']);
						}
					}
				}
			}


			/* apns push notification */
		} else {
			$this->token_error();
		}
	}

	function cancel_appointment($bid = '')
	{
		if ($bid != '') {
			$bookid = $bid;
		} else {
			$bookid = $this->input->post('book_id');
		}
		$bookid = trim($bookid);

		$this->db->where(array('id' => $bookid));
		$this->db->delete('book_service');

		$this->db->where(array('parent_bookid' => $bookid));
		$this->db->delete('book_service');

		$this->db->where(array('guest_parent_bookid' => $bookid));
		$this->db->delete('book_service');

		$this->session->set_flashdata('error_message', "Booking Cancelled.");
	}

	function get_coupon_details()
	{
		$cid = $_POST['cid'];
		$bid = $_POST['bid'];
		$sid = $_POST['sid'];

		$coupon = $this->db->where("status", 1)->where("coupon_name", $cid)->where('start_date <=', date('Y-m-d'))->where('end_date >=', date('Y-m-d'))->where('service_id', $sid)->get("service_coupons")->row_array();

		if ($coupon['id'] > 0) {
			$rec = $this->db->select('currency_code, total_amount, final_amount')->where('id', $bid)->get('book_service')->row_array();

			$totamt = $rec['final_amount'];
			$couponid = $coupon['id'];
			if (!empty($coupon['coupon_type']) && $coupon['coupon_type'] == 1 && $coupon['id'] > 0) {
				$couponPrice = ($totamt) * $coupon['coupon_percentage'] / 100;
				if (is_nan($couponPrice)) $couponPrice = 0;
				$couponPrice = number_format($couponPrice, 2);
			} else if (!empty($coupon['coupon_type']) && $coupon['coupon_type'] == 2 && $coupon['id'] > 0) {
				$couponPrice  = number_format($coupon['coupon_amount'], 2);
			}

			$t_amt = $totamt - $couponPrice;

			$CMsg = (!empty($this->user_language[$this->user_selected]['lg_Coupon'])) ? $this->user_language[$this->user_selected]['lg_Coupon'] : $this->default_language['en']['lg_Coupon'];

			$RMsg = (!empty($this->user_language[$this->user_selected]['lg_Remove_Code'])) ? $this->user_language[$this->user_selected]['lg_Remove_Code'] : $this->default_language['en']['lg_Remove_Code'];

			//Set Coupon in session for paypal paymentbook_appointment
			$this->session->set_userdata('couponid', $couponid);
			$this->session->set_userdata('amount', $t_amt);

			$type = $this->session->userdata('usertype');
			if ($type == 'user') {
				$user_currency = get_user_currency();
			} else if ($type == 'provider') {
				$user_currency = get_provider_currency();
			}
			$userCurrencyCode = $user_currency['user_currency_code'];
			//get user currency code

			$t_amt = get_gigs_currency($t_amt, $rec['currency_code'], $userCurrencyCode);
			$couponPrice = get_gigs_currency($couponPrice, $rec['currency_code'], $userCurrencyCode);
			$licontent = '<div class="text-success" id="cpnli">
                <h6 class="my-0">' . $CMsg . '</h6>
                <small><a  href="javascript:void()" class="removeCoupon" id="delcoupon">' . $RMsg . '</a></small>
              </div>
              <span class="text-success">-' . currency_conversion($userCurrencyCode) . '<span id="cpn_price">' . $couponPrice . '</span></span>';

			echo json_encode(['status' => 1, 'msg' => 'Coupon Added', 'coupon' => $couponid, 'price' => $t_amt, 'content' => $licontent]);
		} else {
			$ICMsg = (!empty($this->user_language[$this->user_selected]['lg_Invalid_Code'])) ? $this->user_language[$this->user_selected]['lg_Invalid_Code'] : $this->default_language['en']['lg_Invalid_Code'];
			echo json_encode(['status' => 2, 'msg' => $ICMsg, 'coupon' => 0]);
		}
	}
	public function book_guestservice()
	{
		$bookid = $this->input->post('book_id');
		if (!empty($_POST['service_for']) && count($_POST['service_for']) > 0) {
			$post = $this->input->post();
			$user_currency = get_user_currency();
			$user_currency_code = $user_currency['user_currency_code'];
			$alltotal = 0;
			foreach ($post['service_for'] as $key => $value) {

				$time = $post['guest_sertime'][$key];

				$booking_time = explode('-', $time);
				$start_time = strtotime($booking_time[0]);
				$end_time = strtotime($booking_time[1]);
				$from_time = date('G:i:s', ($start_time));
				$to_time = date('G:i:s', ($end_time));

				$inputs = array();
				$inputs['user_id']       = $this->session->userdata('id');
				$service_id = $post['guest_ser'][$key]; // Package ID  

				$records = $this->appointment->get_service($service_id);
				$cal_seramt = $records['service_amount'];

				$inputs['service_id']    = $service_id;
				$inputs['provider_id']   = $records['user_id'];

				$inputs['shop_id']       = $records['shop_id'];
				$inputs['staff_id']      = $post['guest_serstf'][$key];

				$inputs['location'] = $this->input->post('service_location');
				$inputs['latitude'] = $this->input->post('service_latitude');
				$inputs['longitude'] = $this->input->post('service_longitude');
				$inputs['service_date'] = date('Y-m-d', strtotime($this->input->post('date')));
				$inputs['from_time'] = $from_time;
				$inputs['to_time'] = $to_time;


				$inputs['tokenid'] = 'old type';

				$inputs['currency_code'] = $records['currency_code'];
				$inputs['amount'] = $records['service_amount'];

				$inputs['cod'] = 0;

				$inputs['updated_on']  = (date('Y-m-d H:i:s'));
				$inputs['home_service'] = $post['service_at'];

				$inputs['request_date'] = date('Y-m-d');
				$inputs['request_time'] = date('H:i:s', time());
				$inputs['status'] = 8; // On-Hold Status

				$current_time = date('H:i:s');
				$booking_from_time = $booking_time[0];
				$booking_end_time = $booking_time[1];

				$timestamp_from = strtotime($booking_from_time);
				$timestamp_to = strtotime($booking_end_time);

				$from_time_railwayhrs = date('H:i:s', ($timestamp_from));
				$to_time_railwayhrs = date('H:i:s', ($timestamp_to));


				$sdate = date('Y-m-d', strtotime($this->input->post('date')));
				$offers = $this->db->where("status", 0)->where("df", 0)->where("service_id", $service_id)->where('start_date <=', $sdate)->where('end_date >=', $sdate)->where("'$from_time_railwayhrs' BETWEEN start_time AND end_time", NULL, FALSE)->where("'$to_time_railwayhrs' BETWEEN start_time AND end_time", NULL, FALSE)->get("service_offers")->row_array();

				$new_serviceamt_val = $inputs['amount'];
				$new_serviceamt = $cal_seramt;
				$offerPrice = '';
				$offersid = 0;
				if (!empty($offers['offer_percentage']) && $offers['offer_percentage'] > 0 && $offers['id'] > 0) {
					$offerPrice = ($new_serviceamt) * $offers['offer_percentage'] / 100;
					if (is_nan($offerPrice)) $offerPrice = 0;
					$offerPrice = number_format($offerPrice, 2);
					$offersid = $offers['id'];

					$new_serviceamt  = $new_serviceamt - $offerPrice;
					$new_serviceamt  = number_format($new_serviceamt, 2);
					if ($new_serviceamt <= 0) $new_serviceamt = 0;
				}


				$inputs['offersid'] = $offersid;
				$inputs['total_amount'] = $new_serviceamt; // On-Hold Status

				$inputs['autoschedule_session_no'] = $records['autoschedule'];



				$inputs['service_for'] = $post['service_for'][$key];
				if ($inputs['service_for'] == 1) { //Myself
					$inputs['guest'] = 0;
					$inputs['guest_name'] = '';
				} else {
					$inputs['guest'] = 1;
					$inputs['guest_name'] = $post['guest_name'][$key];
				}
				$inputs['guest_parent_bookid'] = $post['book_id'];

				$alltotal += $new_serviceamt;

				$result = $this->appointment->booking_success($inputs);

				if ($result != '' && $result > 0) {
					if ($inputs['autoschedule_session_no']  == 1) {
						$myservice = $this->db->select('service_title,autoschedule, autoschedule_days, autoschedule_session')->where('id', $service_id)->from('services')->get()->row_array();
						if ($myservice['autoschedule_session'] != 0 && $myservice['autoschedule_days'] != 0) {
							$days = 0;
							$d = 2;
							for ($s = 1; $s < intval($myservice['autoschedule_session']); $s++) {
								$days = intval($myservice['autoschedule_days']);
								$newdate = date("Y-m-d", strtotime("+" . $days . " day", strtotime($inputs['service_date'])));
								$session_no = $d++;
								$inputs['amount'] = 0;
								$inputs['service_date'] = $newdate;
								$inputs['notes']      = $myservice['service_title'] . "  - Session(" . $session_no . ")";
								$inputs['autoschedule_session_no'] = $session_no;
								$inputs['parent_bookid'] = $result;
								$this->appointment->booking_success($inputs);
							}
						}
					}
				}
			}
			$qry = $this->db->select('total_amount, final_amount')->where('id', $bookid)->get('book_service')->row_array();
			$tot = $qry['final_amount'];
			$inp['final_amount'] = $tot + $alltotal;


			$this->db->update('book_service', $inp, array("id" => $bookid));

			$message = "Proceed To Payment";
			$message = $this->PTPMsg;
			$this->session->set_flashdata('success_message', $message);

			echo json_encode(['success' => true, 'title' => $this->BMsg, 'msg' => $message, 'status' => 1, 'url' => encrypt_url($bookid, $this->config->item('encryption_key'))]);
		} else {
			$message = 'Proceed To Payment';
			$message = $this->PTPMsg;
			$this->session->set_flashdata('success_message', $message);
			echo json_encode(['success' => true, 'title' => $this->BMsg, 'msg' => $message, 'status' => 1, 'url' => encrypt_url($bookid, $this->config->item('encryption_key'))]);
		}
	}

	public function codPayment()
	{
		if ($this->input->post('bookid') != '' && $bookid == '') {
			$bookid = $this->input->post('bookid');
		}
		$servicess = $this->db->select('*')->where('id', $bookid)->get('book_service')->row_array();

		$service_id = $servicess['service_id'];

		$inputs = array();

		if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
			$inputs['cod'] = 1;
			$couponid = $this->input->post('cid');
			$amt = $this->input->post('totalamt');
			$paid_token = '';
		}


		if ($inputs['cod'] == 1) {


			if (!empty($couponid) && $couponid > 0) {
				$cinputs['couponid'] = $couponid;
			}

			$result = $bookid;

			if ($result != '') {
				$inputs['status'] = 2;
				$inputs['reason'] = '';
				$inputs['final_amount'] = $amt;
				$inputs['paid_tokenid'] = $paid_token;
				$inputs['paytype'] = 'cod';

				$this->db->update('book_service', $inputs, array("id" => $result));

				$this->db->update('book_service', $cinputs, array("id" => $result));

				$ginputs['status'] = 2;
				$this->db->update('book_service', $ginputs, array("id" => $result));


				/* $this->load->library('ciqrcode');
				$qr_image=rand().'.png';
				$params['data'] = $result;
				$params['level'] = 'H';
				$params['size'] = 4;
				$params['savename'] =FCPATH."uploads/qr_image/".$qr_image;
				$qr_message = '';
				if($this->ciqrcode->generate($params)) {
					$qr_img_url=$qr_image; 
					$qr_details['qr_img_url'] = "uploads/qr_image/".$qr_img_url;
					
					if($this->db->update('book_service', $qr_details, array("id" => $result))){
						$qrpaths = base_url().'uploads/qr_image/'.$qr_img_url;
						$qr_message .= '<div style="float:left; width:100%;">';
						$qr_message .= '<p style="font-weight: bold;color: coral;">QR CODE FOR YOUR APPOINTMENT</p>';
						$qr_message .= '<img src="'.$qrpaths.'" alt="QR CODE" />';
						$qr_message .= '</div>';
					}
				} */

				$service = $this->db->where('id', $service_id)->from('services')->get()->row_array();
				$this->data['service'] = $service;

				if ($servicess['autoschedule_session_no']  == 1) {
					$inputs['total_amount'] = 0;
					$inputs['final_amount'] = 0;
					$this->db->update('book_service', $inputs, array("parent_bookid" => $result));
				}


				// Coupon Count Update
				if (!empty($couponid) && $couponid > 0) {
					$couponqry = $this->db->select('user_limit, user_limit_count, used_user_id')->where('id', $couponid)->get('service_coupons')->row_array();
					$used_coupon = 	$couponqry['used_user_id'];
					if (!empty($used_coupon)) {
						$userids = $used_coupon . ',' . $this->session->userdata('id');
					} else {
						$userids = $this->session->userdata('id');
					}

					$cno = intval($couponqry['user_limit_count']) + 1;
					$this->db->query("UPDATE `service_coupons` SET `user_limit_count` = '" . $cno . "', `used_user_id` = '" . $userids . "' WHERE `id` = '" . $couponid . "'");
					if ($couponqry['user_limit'] != 0 && $couponqry['user_limit'] == $cno) {
						$this->db->query("UPDATE `service_coupons` SET `status` = 3 WHERE `id` = '" . $couponid . "'");
					}
				}

				// Reward Update
				$rewardid = $servicess['rewardid'];
				if (!empty($rewardid) && $rewardid > 0) {
					$this->db->query("UPDATE `service_rewards` SET `status` = 3 WHERE `id` = '" . $rewardid . "' and user_id = " . $this->session->userdata('id') . " and service_id = " . $service_id);
				}

				$provider_data = $this->db->where('id', $servicess['provider_id'])->from('providers')->get()->row_array();
				$user_data = $this->db->where('id', $this->session->userdata('id'))->from('users')->get()->row_array();
				$this->data['provider'] = $provider_data;

				$preview_link = base_url();
				$time = $servicess['from_time'] . "-" . $servicess['to_time'];


				$bodyid = 3;
				$tempbody_details = $this->templates_model->get_usertemplate_data($bodyid);
				$providerbody = $tempbody_details['template_content'];
				$providerbody = str_replace('{user_name}', $provider_data['name'], $providerbody);
				$providerbody = str_replace('{sitetitle}', $this->site_name, $providerbody);
				$providerbody = str_replace('{user_person}', $this->session->userdata('name'), $providerbody);
				$providerbody = str_replace('{service_title}', $service['service_title'], $providerbody);
				$providerbody = str_replace('{service_date}', $servicess['service_date'], $providerbody);
				$providerbody = str_replace('{service_time}', $time, $providerbody);
				$providerbody = str_replace('{location_user}', $servicess['location'], $providerbody);
				$providerbody = str_replace('{preview_link}', $preview_link, $providerbody);
				$providerbody .= $qr_message;

				//Send mail to provider
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');

				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($provider_data['email'])
						->subject('Service Booking')
						->message($providerbody)
						->send();
				}
				//Send mail to user
				$body = $this->load->view('user/email/service_email', $this->data, true);
				$phpmail_config = settingValue('mail_config');
				if (isset($phpmail_config) && !empty($phpmail_config)) {
					if ($phpmail_config == "phpmail") {
						$from_email = settingValue('email_address');
					} else {
						$from_email = settingValue('smtp_email_address');
					}
				}
				$this->load->library('email');
				if (!empty($from_email) && isset($from_email)) {
					$mail = $this->email
						->from($from_email)
						->to($this->session->userdata('email'))
						->subject('Service Booking')
						->message($body)
						->send();
				}

				$token = $this->session->userdata('chat_token');

				// Cash On Delivery
				if ($inputs['cod'] == 1) {
					$codData['book_id'] = $result;
					$codData['user_id'] = $this->session->userdata('id');
					$codData['provider_id'] = $servicess['provider_id'];
					$codData['amount'] = $servicess['amount'];
					$codData['amount_to_pay'] = $amt;
					$codData['created_on'] = date('Y-m-d');
					$this->db->insert('book_service_cod', $codData);
				}

				/* history entry */
				$data = $this->api->get_book_info($result);

				$ptype = $this->db->select('type')->where('id', $data['provider_id'])->get('providers')->row()->type;

				$device_token = $this->api->get_device_info_multiple($data['provider_id'], $ptype);

				$user_name = $this->api->get_user_info($data['user_id'], 2);

				$provider_token = $this->api->get_user_info($data['provider_id'], $ptype);

				$protoken = $provider_token['token'];
				$pname = $this->db->select('name')->where('id', $data['provider_id'])->get('providers')->row()->name;
				$spname = $this->db->select('shop_name')->where('id', $service['shop_id'])->get('shops')->row()->shop_name;

				$text = $user_name['name'] . " has booked the Service '" . $service['service_title'] . "'";



				/*Notifification */
				$notimsg = $user_name['name'] . " has booked the service '" . $service['service_title'] . "' at " . $pname . "'s shop '" . $spname . "' and for the amount of " . $amt . $servicess['currency_code'];

				$receiver = '';
				$admin = $this->db->where('user_id', 1)->from('administrators')->get()->row_array();
				if (empty($admin['token'])) {
					$receiver = $this->getToken(14, $admin['user_id']);
					$receiver_token_update = $this->db->where('role', 1)->update('administrators', ['token' => $receiver]);
				} else {
					$receiver = $admin['token'];
				}


				$paynotimsg = $user_name['name'] . " paid the amount of " . $amt . $servicess['currency_code'] . " to " . $pname . " for the service '" . $service['service_title'] . "'";

				if (!empty($receiver)) {
					$this->bookingnotification($protoken, $receiver, $notimsg);
					$this->bookingnotification($protoken, $receiver, $paynotimsg);
				}
				$this->bookingnotification($protoken, $token, $notimsg);
				$this->bookingnotification($protoken, $token, $paynotimsg);

				$this->send_push_notification($token, $result, $ptype, $msg = $notimsg);
				$this->send_push_notification($token, $result, $ptype, $msg = $paynotimsg);



				$message = 'You have booked appointment successfully';
				$this->session->set_flashdata('success_message', $this->bookmsg);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => true, 'msg' => $this->bookmsg,  'title' => $this->BMsg, 'status' => 1]);
				} else {
					redirect(base_url('user-bookings'));
				}
			} else {
				$message = 'Sorry, Try again later...';
				$this->session->set_flashdata('error_message', $this->errmsg . " " . $_GET['message']);
				$s_id = $service_id;
				$this->cancel_appointment($bookid);

				$inputs = array();
				if (!empty($this->input->post('type')) && $this->input->post('type') == 'cod') {
					echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
				} else {
					redirect(base_url('book-appointment/' . $s_id));
				}
			}
		} else {
			$message = 'Sorry, Try again later...';
			$s_id = $service_id;
			$this->cancel_appointment($bookid);

			$inputs = array();
			$data = array('error_message' => $this->errmsg . " " . $_GET['message']);
			$this->session->set_flashdata($data);
			if (!empty($this->input->get('type')) && $this->input->get('type') == 'cod') {
				echo json_encode(['success' => false, 'msg' => $this->errmsg, 'title' => $this->BMsg, 'status' => 2]);
			} else {
				redirect(base_url('book-appointment/' . $s_id));
			}
		}
		exit;
	}

	public function get_service_details_by_id()
	{
		$this->load->model('Service_model');
		$serviceId = $this->input->post('service_id');

		if ($serviceId) {
			$res = $this->Service_model->get_service_by_id($serviceId);
			header('Content-Type: application/json'); // Set response content type
			echo json_encode($res);
		} else {
			// Handle invalid or missing service ID
			http_response_code(400); // Bad Request
			echo json_encode(['error' => 'Invalid service ID']);
		}
	}

	public function check_user_availability()
	{
		$email = $this->input->post('email');
		$res = [];

		if ($email) {
			$query = $this->db->get_where('users', array('email' => $email));

			if ($query->num_rows() > 0) {
				$user_data = $query->row_array();
				$res['data'] = $user_data;
				$res['status'] = true;
			} else {
				$res['status'] = false;
			}

			header('Content-Type: application/json');
			echo json_encode($res);
		} else {
			// Handle invalid or missing email
			http_response_code(400); // Bad Request
			echo json_encode(['error' => 'Invalid email']);
		}
	}

	public function create_user_offline()
	{

		$this->load->model('user_login_model', 'user_login');
		$password = '123456';
		$user_details['mobileno'] = $this->input->post("userMobile");
		$user_details['email'] = $this->input->post("userEmail");
		$user_details['name'] = $this->input->post("userName");
		$user_details['country_code'] = $this->input->post("countryCode");
		$user_details['password'] = md5($password);
		$user_details['currency_code'] = "INR";

		$is_available = $this->user_login->check_user_emailid($user_details['email']);
		$is_available_mobileno = $this->user_login->check_user_mobileno($user_details['mobileno']);
		$is_available_provider = $this->user_login->check_provider_email($user_details['email']);
		$is_available_mobile_provider = $this->user_login->check_mobile_no($user_details['mobileno']);



		if ($is_available == 0 && $is_available_mobileno == 0 && $is_available_provider == 0 && $is_available_mobile_provider == 0) {
			$result = $this->user_login->user_signup($user_details);
			if (!empty($result)) {
				$res = array(
					'status' => 200,
					'data' => $result
				);
			} else {
				$res = array(
					'error' => 'Something Went wrong',
					'status' => 401
				);
			}

			header('Content-Type: application/json');
			echo json_encode($res);
		} else {
			http_response_code(401);
			echo json_encode(['error' => 'Something Went wrong', 'status' => 401]);
		}
	}
}
