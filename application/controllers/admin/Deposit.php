<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Deposit extends CI_Controller {

   public $data;

   public function __construct() {

        parent::__construct();
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'deposit';
        $this->load->model('dashboard_model','dashboard');
		 $this->load->model('user_login_model','user');
		$this->load->model('common_model','common_model');
		$this->load->model('admin_model', 'admin');
		$this->load->model('api_model', 'api');
		$this->load->model('deposit_model', 'deposit');
        $this->data['base_url'] = base_url();
        $this->load->helper('user_timezone');
		$this->data['user_role']=$this->session->userdata('role');
    }

   

  

  public function deposit_list($value='')
  {
	  $this->common_model->checkAdminUserPermission($this->data['user_role']);
    extract($_POST);
    
      if($this->input->post('form_submit'))
      {
        $this->data['page'] = 'users';
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
				$this->data['page'] = 'deposit_provider_list';
        $this->data['lists'] = $this->deposit->deposit_filter($username,$email,$from,$to);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }
      else
      {
        $lists = $this->deposit->deposit_provider_list();
				$this->data['page'] = 'deposit_provider_list';
				$this->data['lists'] = $this->deposit->deposit_provider_list();
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'].'/template');
      }
  
  }
  
	public function update_deposit_modal(){
		$pid = $_POST['id'];
		$amt = $_POST['amt'];
		$bank_details=$this->db->where('id',$pid)->get('providers')->row_array();
		$content = '<div><b>Account Details</b></div>';
		$name = (isset($bank_details['account_holder_name']))?$bank_details['account_holder_name']:'-';
		$content .= '<div>Account Name : '.$name.'</div>';
		$ano = (isset($bank_details['account_number']))?$bank_details['account_number']:'-';
		$content .= '<div>Account Number : '.$ano.'</div>';
		$ibno = (isset($bank_details['account_iban']))?$bank_details['account_iban']:'-';
		$content .= '<div>IBAN : '.$ibno.'</div>';
		$content .= '<br>';
		$content .= '<div><b>Deposit Amount Details</b></div>';
		$content .= '<div> Amount : '.settingValue('currency_symbol').$amt.'</div>';
		echo $content;
	}
	
	public function confirm_deposit(){
		$pid = $_POST['id'];
		$amt = $_POST['amt'];
		$bid = $_POST['bid'];
		$cid = $_POST['cid'];
		
		$barr = explode(",", $bid); 
		$carr = explode(",", $cid);
		
		$inp['deposit_flag'] = 1;
		$this->db->where_in('id',$barr);		
		if($this->db->update('book_service', $inp)){
			$this->db->where_in('guest_parent_bookid',$barr);  
			$this->db->update('book_service', $inp);
			
			$inpt['cart_flag'] = 1;
			$this->db->where_in('id',$carr);	
			$this->db->update('product_cart', $inpt);
			
			$inputs['provider_id'] = $pid;
			$inputs['book_id'] = $bid;
			$inputs['order_id'] = $cid; 
			$inputs['amount'] = $amt;       
			$inputs['deposit_date'] = date('Y-m-d');
			$inputs['deposit_status'] = 1;
			$inputs['created_at'] = date('Y-m-d H:i:s');
			if ($this->db->insert('deposit_details', $inputs)) {
				echo json_encode(['status'=>true,'msg'=>"Deposit Process is in In-Progress Status."]);
			} else {
				$inp['deposit_flag'] = 0;
				$this->db->where_in('id',$barr);
				$this->db->update('book_service', $inp);	
				echo json_encode(['status'=>false,'msg'=>"Try again later"]);
			}
		}else{
			echo json_encode(['status'=>false,'msg'=>"Try again later"]);
		}
	}
	 
  
	public function change_deposit_status(){
		$did = $_POST['id'];
		$val = $_POST['val'];
		$inp['deposit_status'] = $val;
		$inp['deposit_completed_at'] = date('Y-m-d');
		$this->db->where('id',$did);		
		if($this->db->update('deposit_details', $inp)){
			$dep =  $this->db->where('id', $did)->get('deposit_details')->row_array();
			if(!empty($dep['provider_id'])){
				$rec =  $this->db->where('id', $dep['provider_id'])->get('providers')->row_array();
			}
			if(!empty($rec['email'] !=''))
			{				
				$tomailid=$rec['email'];
				$phpmail_config=settingValue('mail_config');
				if(isset($phpmail_config)&&!empty($phpmail_config)){
				if($phpmail_config=="phpmail"){
				  $from_email=settingValue('email_address');
				}else{
				  $from_email=settingValue('smtp_email_address');
				}
			  }
			  
			  $subject = 'Deposit History Details from Craftesty';
			  $content = '<div align="center" style="font-weight:bold;font-size:18px";>Deposit History</div><br>';
			  $content .= '<div><span style="font-weight:bold;">Name:&nbsp;</span>'.$rec['name'].'</div><br>';
			  $content .= '<div><span style="font-weight:bold;">Deposit Amount:&nbsp;</span>'.$dep['amount'].'SAR</div><br>';
			  $depcontent = $this->mailcontent($dep['book_id'], $dep['order_id']);
			  $footer = '<div style="float:left; margin-top:20px; width:100%"><p style="text-align:right">Cheers,</p>
						 <p style="text-align:right">Al Enayah Team</p></div>';

			  $body = $content.$depcontent.$footer;
			 			  			  
			  $this->load->library('email');
			  //Send mail to provider
			  if(!empty($from_email)&&isset($from_email)){
				$mail = $this->email
					->from($from_email)
					->to($tomailid)
					->subject($subject)
					->message($body)
					->send();					
				}
			}
			
			echo json_encode(['status'=>true,'msg'=>"Deposit Process Completed.", 'mail' => 'yes']);			
		}else{
			echo json_encode(['status'=>false,'msg'=>"Try again later"]);
		}
	}

	function mailcontent($book_id,$order_id){
		$content = '';
		$style='style="border: 1px solid white; border-collapse: collapse; background-color: #dddeee;"';
		if($book_id !='') {
			$barr = explode(",", $book_id);
			$bkqry = $this->db->where_in('id', $barr)->get('book_service')->result_array();
			$content .= '<div style="font-weight:bold;">Service Booked List:-</div><br>';
			$content .= '<table style="width:100%">';
			$content .= '<thead style="font-weight:bold">
			<th '.$style.'>#</th><th '.$style.'>Customer</th><th '.$style.'>Service</th><th '.$style.'>Amount</th><th '.$style.'>Booking Date</th><th '.$style.'>Booking Time</th><th '.$style.'>status</th>
			</thead>';
			$content .= '<tbody>';
			$i=1;
			foreach($bkqry as $bk){
				$usrname = $this->db->select('name')->where('id',$bk['user_id'])->get('users')->row()->name;
				$sername = $this->db->select('service_title')->where('id',$bk['service_id'])->get('services')->row()->service_title;
				$amount = $bk['final_amount'].$bk['currency_code'];
				$bkdate = $bk['service_date'];
				$bktime = $bk['from_time']."-".$bk['to_time'];
				if($bk['status'] == 6){
					$txt = "Completed";
				} else {
					if($bk['status'] == 5 || $bk['status'] == 7) {					
						if(!empty($bk['reject_paid_token'])){
							if($bk['admin_reject_comment']=="This service amount favour for User"){
								$badge="Amount refund to User";		
							}else{									 		
								$badge="Amount refund to Provider";							
							}
						} 
						if($bk['status'] == 5) {
							$txt = "Rejected By Customer";
						} else if($bk['status'] == 7) {
							$txt = "Cancelled By Provider";
						}
						
					}
				}
				
				$status = $txt;
				$content .= '<tr><td '.$style.'>'.$i++.'</td><td '.$style.'>'.$usrname.'</td><td '.$style.'>'.$sername.'</td><td '.$style.'>'.$amount.'</td><td '.$style.'>'.$bkdate.'</td><td '.$style.'>'.$bktime.'</td><td '.$style.'>'.$txt.'</td></tr>';
			}
			$content .= '<tbody>';
			$content .= '</table>';
		}
		if($order_id !='') {		
			$carr = explode(",", $order_id);
			$this->db->select('product_cart.id, product_cart.order_id, product_cart.shop_id, product_cart.product_id, product_cart.product_currency, product_cart.product_price, product_cart.qty, product_cart.product_total, product_cart.created_at, product_cart.delivery_status, products.product_name, product_images.product_image, product_units.unit_name, shops.shop_name, product_order.order_code, users.name, users.mobileno');
			$this->db->join('product_order','product_cart.order_id=product_order.id','left');
			$this->db->join('products','product_cart.product_id=products.id','left');
			$this->db->join('shops','product_cart.shop_id=shops.id','left');
			$this->db->join('product_units','products.unit=product_units.id','left');
			$this->db->join('product_images','product_cart.product_id=product_images.product_id and primary_img=1','left');
			$this->db->join('users','product_cart.user_id=users.id','left');
			$this->db->where_in('product_cart.id',$carr);										
			$ordqry =  $this->db->get('product_cart')->result_array();
			$content .= '<br><div style="font-weight:bold;">ordered List:-</div><br>';
			$content .= '<table style="width:100%">';
			$content .= '<thead style="font-weight:bold">
			<th '.$style.'>#</th><th '.$style.'>Customer</th><th '.$style.'>Product</th><th '.$style.'>Amount</th><th '.$style.'>Order Code</th><th '.$style.'>Date</th><th '.$style.'>status</th>
			</thead>';
			$content .= '<tbody>';
			$o=1;
			foreach($ordqry as $or){
				$pamount = $or['product_total'].$or['product_currency'];
				$content .= '<tr><td '.$style.'>'.$o++.'</td><td '.$style.'>'.$or['name'].'</td><td '.$style.'>'.$or['product_name'].'</td><td '.$style.'>'.$pamount.'</td><td '.$style.'>'.$or['order_code'].'</td><td '.$style.'>'.$or['created_at'].'</td><td '.$style.'>Delivered</td></tr>';
			}
			$content .= '<tbody>';
			$content .= '</table>';
		}
			
		return $content;
	}
	
	


}

?>
