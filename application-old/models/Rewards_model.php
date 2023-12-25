<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class Rewards_model extends CI_Model{ 
     
    function __construct() { 
        // Set table name 
        $this->table = 'service_rewards'; 
    } 
     
    /* 
     * Fetch records from the database 
     * @param array filter data based on the passed parameters 
     */ 
    
    
    /* Rewards */
    public function booking_count($user_id){
        
        $this->db->select("COUNT(S.user_id) AS total_count");
        $this->db->from('book_service S');          
        $this->db->where('S.user_id',$user_id);
        $this->db->where('S.status', 6);        
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function get_rewards_details($user_id)
    {
        $this->db->select("R.*");
        $this->db->from('service_rewards R');  
		$this->db->where('R.user_id',$user_id);
		$this->db->where('R.status != ',0);
        $query = $this->db->get();  
        return $query->result_array();
    }

	/* Rewards Admin Panel */
	public function read_servicerewards(){
		$this->db->select("R.user_id, COUNT(R.id) AS total_rewards, U.name as user_name");
        $this->db->from('service_rewards R');          
		$this->db->join('users U', 'U.id = R.user_id', 'LEFT');   	
		$this->db->where('R.status != ',0);	
        $this->db->group_by('R.user_id');
        $query = $this->db->get();  
        return $query->result_array();
	}
	public function rewards_filter($username,$from,$to){
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

        $this->db->select("R.user_id, COUNT(R.id) AS total_rewards, U.name as user_name");
        $this->db->from('service_rewards R');          
		$this->db->join('users U', 'U.id = R.user_id', 'LEFT');   
          
        
      if(!empty($username)){
        $this->db->where('R.user_id',$username);
      }
      
      
        if(!empty($from_date)){
        $this->db->where('R.created_at >=',$from_date);
      }
      if(!empty($to_date)){
        $this->db->where('R.created_at <=',$to_date);
      }
		$this->db->where('R.status != ',0);
        $this->db->group_by('R.user_id');
      
      return $this->db->get()->result_array();
        }
}
