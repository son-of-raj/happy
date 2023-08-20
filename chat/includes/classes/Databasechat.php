<?php
class Databasechat extends PDO
{
    public function __construct($username = '', $password = '', $host = '', $db = '')
    {   
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $db;
        $this->conn = $this->connect();
    }
    public function connect() {
        $mysqli = new mysqli($this->host,$this->username,$this->password,$this->dbname);
        return $mysqli;
    }
    public function disconnect() {
        mysqli_close($this->conn);
    }
    public function reconnect() {
        $this->disconnect();
        $this->conn = $this->connect();
    }
    public function insert($to_id = 0,$from_id = 0,$message = '',$date): void {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        date_default_timezone_set('Asia/Kolkata');
        $time = date("Y-m-d H:i:s");
        $statement = mysqli_query($this->conn,"INSERT INTO chat_table ( sender_token, receiver_token, message, 	 utc_date_time ) values ( '$from_id', '$to_id', '$message', '$time')");

    }
    public function fetch($to_id,$from_id) {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        $statement = mysqli_fetch(mysqli_query($this->conn,"SELECT sender_token as chat_from, receiver_token as chat_to, chat_type, date_time, chat_from_time, chat_to_time, chat_utc_time, timezone, content  FROM chats  WHERE  (sender_token = '$from_id' and receiver_token = '$to_id') OR  (receiver_token = '$from_id' and sender_token = '$to_id') " ));
        return $statement;
    }
    public function insertid($from_id,$to_id) {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        $statement = mysqli_fetch_array(mysqli_query($this->conn,"SELECT chat_id as id, utc_date_time as chattime  FROM chat_table  WHERE  sender_token = '$from_id' and receiver_token = '$to_id' order by chat_id desc limit 0 , 1" ),MYSQLI_ASSOC);
        return $statement;
    }
    public function update_pwd($from_id,$pwd,$usertype):void{
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        if($usertype == 'provider'){
			$statement = mysqli_query($this->conn,"UPDATE providers SET pwd = '$pwd' WHERE id='$from_id'");
		}else{
			$statement = mysqli_query($this->conn,"UPDATE users SET pwd = '$pwd' WHERE id='$from_id'");
		}
    }

    public function socketdetails() {
        $socket_data =  mysqli_fetch(mysqli_query($this->conn,"SELECT *  FROM system_settings  WHERE  ('key' = 'server_port')" ));
        return $socket_data;
    }

    public function fetchsettings() {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        $statement = mysqli_fetch_array(mysqli_query($this->conn,"select * from system_settings  where `key` = 'server_port'"),MYSQLI_ASSOC);
        return $statement;
    }

    public function getsocketip() {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        $serverip = mysqli_fetch_array(mysqli_query($this->conn,"select * from system_settings  where `key` = 'server_ip' "),MYSQLI_ASSOC);
        return $serverip;
    }
    public function getsocketdetails() {
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        $serverip = mysqli_fetch_array(mysqli_query($this->conn,"select * from system_settings  where `key` = 'socket_showhide' "),MYSQLI_ASSOC);
        return $serverip;
    }
    public function getpwd($to_id,$usertype){
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
		$statement = mysqli_fetch_array(mysqli_query($this->conn,"select pwd,name as username from users where token = '$to_id'"),MYSQLI_ASSOC);
		if(is_null($statement)){
			$statement = mysqli_fetch_array(mysqli_query($this->conn,"select pwd,name as username from providers where token = '$to_id'"),MYSQLI_ASSOC);
        }
		
        return $statement;
    }
    public function pushnotify($fr_id,$to_id,$msg,$insert_id,$usertype){
        if ($this->conn !== FALSE && $this->conn->ping() === FALSE){ 
            $this->reconnect(); 
        }
        
        $statement = mysqli_fetch_array(mysqli_query($this->conn,"select id as username,'2' as user from users where token = '$to_id'"),MYSQLI_ASSOC);
        if(is_null($statement)){
            $statement = mysqli_fetch_array(mysqli_query($this->conn,"select id as username,'1' as user from providers where token = '$to_id'"),MYSQLI_ASSOC);
        }

        $last['from_user_id'] = $fr_id;
        $last['to_user_id'] = $to_id;
        $usertype = $statement['user'];
        $username_id = $statement['username'];
        $player = mysqli_fetch_array(mysqli_query($this->conn,"SELECT device_id,device_type FROM device_details WHERE user_id = '$username_id' and type = '$usertype' order by id desc limit 0,1"),MYSQLI_ASSOC);
        if (!empty($player)) {
            if (!empty($player['device_type']) && !empty($player['device_id'])) {
                if (strtolower($player['device_type']) == 'android') {
                    $notify_structure = array(
                        'title' => 'You got a new message',
                        'message' => $msg,
                        'image' => 'test22',
                        'action' => 'test222',
                        'action_destination' => 'test222',
                    );
                    $val = mysqli_fetch_array(mysqli_query($this->conn,"SELECT value FROM system_settings WHERE `key` = 'firebase_server_key' and `status` = '1'"),MYSQLI_ASSOC);
                    $firebase_api = trim($val['value']);
                    $value[]=$player['device_id'];
                    $fields = array(
                        'registration_ids' => $value,
                        'data' => $notify_structure,
                    );
                    $url = 'https://fcm.googleapis.com/fcm/send';
                    $headers = array(
                      'Authorization: key=' . $firebase_api,
                      'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $result = curl_exec($ch);  
                    $response = $result;
                    if($result === FALSE){ 
                      die('Curl failed: ' . curl_error($ch));
                    }
                    curl_close($ch);
                }

                if (strtolower($player['device_type'] == 'ios')) {
                    $val = mysqli_fetch_array(mysqli_query($this->conn,"SELECT value FROM system_settings WHERE `key` = 'firebase_server_key' and `status` = '1'"),MYSQLI_ASSOC);
                    $SERVER_API_KEY = trim($val['value']);
                    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
        
                    $data['additional_data']['body']=$msg;
                    $data['additional_data']['title']='You got a new message';                  
                    
                    $aps['aps'] = [
                        'alert' => [
                            'title' => 'You got a new message',
                            'body' => $msg,
                        ],
                          'badge'      => 0,
                          'sound'      => 'default',
                          'title'      => 'You got a new message',
                          'body'       => $msg,
                          'my_value_1' =>   $data['additional_data'],
                    ];
                    $result = [
                        "registration_ids" => array($player['device_id']),
                        "notification" => $aps['aps'],  
                    ];

                    //Generating JSON encoded string form the above array.
                    
                     $json = json_encode($result);
                     //Setup headers:
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: key= '. $SERVER_API_KEY.''; // key here

                    //Setup curl, add headers and post parameters.
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);       

                    //Send the request
                    $response = curl_exec($ch);
                }
            }
        }
        return $response;
    }
}