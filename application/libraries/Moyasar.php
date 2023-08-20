<?php
Class Moyasar 
{
	public function __construct($config = array()) {
		$this->config = $config;
		$this->validation();
	}
	public function moyasar_fetch($id){
		$init = array();
		$init['url']= "https://api.moyasar.com/v1/payments/$id";			
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $init['url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_USERPWD, $this->secret_key . ":" . "");
		
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = array();
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$result = curl_error($ch);
		}
		curl_close ($ch);
		return $result;
	}
	
	public function moyasar_refund($id,$input){
		$init = array();
		$init['url']= "https://api.moyasar.com/v1/payments/$id/refund";	
		$post_query = http_build_query($input, '', '&');
		$init['post_data'] = $post_query; 
		return $this->curl_init($init);
	}
	
	public function curl_init($option){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $option['url']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS,$option['post_data']);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_USERPWD, $this->secret_key . ":" . "");
		
		$headers = array();
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$result = array();
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			$result = curl_error($ch);
		}
		curl_close ($ch);
		return $result;
	}
	
	public function validation(){
		$this->publishable_key = (!empty($this->config['publishable_key'])?$this->config['publishable_key']:'');
		$this->secret_key      = (!empty($this->config['secret_key'])?$this->config['secret_key']:'');
		
		$error_message = array();
		if(empty($this->publishable_key)){
			$error_message['publishable_error'] = "Publishable key is missing";
		}
		if(empty($this->secret_key)){
			$error_message['secret_error'] = "The secret key is missing";
		}
		if(count($error_message) > 0){
			return json_encode($error_message);
		}
	}
}
?>