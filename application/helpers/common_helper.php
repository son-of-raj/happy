<?php
  if(!function_exists('settings')){
  
  function settings($val){
      $ci =& get_instance();

      $settings=array();
      $query = $ci->db->get_where('system_settings', array('status' => 1));
        if ($query->num_rows()) {
            $settings = $query->result_array();
        }

        
        if(!empty($settings)){
          foreach($settings as $datas){
            if($datas['key']=='currency_option'){
             $result['currency'] = $datas['value'];
            }
         }
        }

        if(!empty($result[$val]))
		  {
		     $results= $result[$val];
		  }
		else
		  {
		     $results= 'INR';
		  }

    return $results;

     }
function settingValue($key){
  if(!empty($key)){
     $ci =& get_instance();
     $settings = $ci->db->where('key=',$key)->get('system_settings')->row_array();
     if(!empty($settings)){
        return $settings['value'];
     }else{
        return "";
     }
  }
}
  
function currencyConverter($from, $to) {


    $url = 'https://free.currconv.com/api/v7/convert?q=' . $from . '_' . $to . ',' . $to . '_' . $from . '&compact=ultra&apiKey=de2f3dcf8b88d2d760d4';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
print_r($result);
}
}
function removeTag($data){
  
    foreach ($data as $key => $value) {
      if(!is_array($value)){
        $_POST[$key]=strip_tags($value);
      }
    }
   return $_POST;
}
if (!function_exists('str_slug')) {
    function str_slug($string_name, $separator = 'dash', $lowercase = TRUE)
    {
        $rand_no = 200;
       
    $username_parts = array_filter(explode(" ", mb_strtolower($string_name,'UTF-8'))); //explode and lowercase name
        $username_parts = array_slice($username_parts, 0, 2); //return only first two arry part

        $part1 = (!empty($username_parts[0]))?mb_substr($username_parts[0], 0,8,'utf-8'):""; //cut first name to 8 letters
        $part2 = (!empty($username_parts[1]))?mb_substr($username_parts[1], 0,5,'utf-8'):""; //cut second name to 5 letters
        $part3 = ($rand_no)?rand(0, $rand_no):"";
        $username = $part1. $part2. $part3; //str_shuffle to randomly shuffle all characters
        return $username;
    }
}
function encrypt_url($value,$key)
{
    $CI =& get_instance();
    $enc = urlencode(base64_encode(base64_encode(base64_encode(base64_encode($value.$key)))));
    return clean($enc);
}
function decrypt_url($value,$key)
{
     $CI =& get_instance();
     $decrypted_id_raw = base64_decode(base64_decode(base64_decode(base64_decode(urldecode($value)))));
     $submit_id = preg_replace(sprintf('/%s/', $key), '', $decrypted_id_raw);
     return $submit_id;
}
function clean($string) 
{
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

function RemoveEmpty($array)
{
    foreach ($array as $key => &$value) {
      if (is_array($value)) {
        $value = RemoveEmpty($value);
      }
      if (empty($value)) {
        unset($array[$key]);
      }
    }
    return $array;
}

function replacehyphen($array)
{
    foreach ($array as $key=>$value) 
    {
        
        $newkey = str_replace("-", ".", $key);
        $array[$newkey] = $array[$key];
        unset($array[$key]);
    }
    return $array;
}
?>