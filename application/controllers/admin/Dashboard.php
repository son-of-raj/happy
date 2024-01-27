<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Dashboard extends CI_Controller
{

  public $data;

  public function __construct()
  {

    parent::__construct();
    $this->data['theme'] = 'admin';
    $this->data['model'] = 'dashboard';
    $this->load->model('dashboard_model', 'dashboard');
    $this->load->model('user_login_model', 'user');
    $this->load->model('common_model', 'common_model');
    $this->load->model('admin_model', 'admin');
    $this->load->model('api_model', 'api');
    $this->data['base_url'] = base_url();
    $this->load->helper('user_timezone');
    $this->data['user_role'] = $this->session->userdata('role');
  }

  public function index()
  {
    $this->data['page'] = 'index';
    $this->data['payment'] = $this->dashboard->get_payment_info();

    $this->data['currency_code'] = (settings('currency')) ? settings('currency') : 'USD';
    //Currency Convertion 
    $currency_code_old = ($this->data['payment'][0]['currency_code']) ? $this->data['payment'][0]['currency_code'] : 'USD';
    $subscription_amount = get_gigs_currency($this->data['payment'][0]['paid_amt'], $currency_code_old, $this->data['currency_code']);
    $this->data['payment_amount'] = $subscription_amount;

    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }
  public function admin_notification($value = '')
  {
    $this->data['page'] = 'admin_notification';

    $this->data['admin_notification'] = $this->db->where('n.receiver', $this->session->userdata('chat_token'))->from('notification_table as n')->join('providers as p ', 'p.token=n.sender')->select('n.notification_id, n.message, n.created_at, p.name, p.profile_img, n.utc_date_time')->where('n.delete_status', 0)->order_by("n.created_at", "desc")->get()->result_array();
    $notificationupd = $this->db->where('receiver', $this->session->userdata('chat_token'))->update('notification_table', ['status' => 0]);
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function crop_profile_img($prev_img = '')
  {

    ini_set('max_execution_time', 3000);

    ini_set('memory_limit', '-1');

    if (!empty($prev_img)) {

      $file_path = FCPATH . $prev_img;

      if (!file_exists($file_path)) {

        unlink(FCPATH . $prev_img);
      }
    }

    $error_msg = '';

    $av_src = $this->input->post('avatar_src');

    $av_data = json_decode($this->input->post('avatar_data'), true);

    $av_file = $_FILES['avatar_file'];

    $src = 'uploads/profile_img/' . $av_file['name'];

    $imageFileType = pathinfo($src, PATHINFO_EXTENSION);

    $image_name = time() . '.' . $imageFileType;

    $src2 = 'uploads/profile_img/temp/' . $image_name;

    move_uploaded_file($av_file['tmp_name'], $src2);
    $admin_updates = $this->db->where('user_id=', $this->session->userdata('admin_id'))->update('administrators', ['profile_img' => $src2]);

    $ref_path = '/uploads/profile_img/temp/';

    $image1 = $this->crop_images($image_name, $av_data, 200, 200, "/uploads/profile_img/", $ref_path);



    $rand = rand(100, 999);

    $response = array(
      'state' => 200,
      'message' => $error_msg,
      'result' => 'uploads/profile_img/' . $image_name,
      'success' => 'Y',
      'img_name1' => $image_name
    );

    echo json_encode($response);
  }

  public function crop_images($image_name, $av_data, $t_width, $t_height, $path, $ref_path)
  {

    $w = $av_data['width'];

    $h = $av_data['height'];

    $x1 = $av_data['x'];

    $y1 = $av_data['y'];

    list($imagewidth, $imageheight, $imageType) = getimagesize(FCPATH . $ref_path . $image_name);

    $imageType = image_type_to_mime_type($imageType);

    $ratio = ($t_width / $w);

    $nw = ceil($w * $ratio);

    $nh = ceil($h * $ratio);

    $newImage = imagecreatetruecolor($nw, $nh);



    $backgroundColor = imagecolorallocate($newImage, 0, 0, 0);

    imagefill($newImage, 0, 0, $backgroundColor);

    $black = imagecolorallocate($newImage, 0, 0, 0);



    // Make the background transparent

    imagecolortransparent($newImage, $black);







    switch ($imageType) {

      case "image/gif":
        $source = imagecreatefromgif(FCPATH . $ref_path . $image_name);

        break;

      case "image/pjpeg":

      case "image/jpeg":

      case "image/jpg":
        $source = imagecreatefromjpeg(FCPATH . $ref_path . $image_name);

        break;

      case "image/png":

      case "image/x-png":
        $source = imagecreatefrompng(FCPATH . $ref_path . $image_name);

        break;
    }

    imagecopyresampled($newImage, $source, 0, 0, $x1, $y1, $nw, $nh, $w, $h);

    switch ($imageType) {

      case "image/gif":
        imagegif($newImage, FCPATH . $path . $image_name);

        break;

      case "image/pjpeg":

      case "image/jpeg":

      case "image/jpg":
        imagejpeg($newImage, FCPATH . $path . $image_name, 100);

        break;

      case "image/png":

      case "image/x-png":
        imagepng($newImage, FCPATH . $path . $image_name);

        break;
    }
  }


  public function map_list()
  {
    $this->data['page'] = 'map_list';
    $this->data['map'] = $this->dashboard->get_payments_info();
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function service_map_list()
  {

    $this->db->select('tab_2.name,tab_1.service_latitude,tab_1.service_longitude,tab_1.service_title')->from('services tab_1');
    $val = $this->db->join('providers tab_2', 'tab_2.id=tab_1.user_id', 'LEFT')->get()->result_array();

    if (!empty($val)) {

      $result_json = [];

      foreach ($val as $key => $value) {
        $temp = $temp2 = [];
        $temp2[] = $value["service_latitude"];
        $temp2[] = $value["service_longitude"];

        $temp['latLng'] = $temp2;
        $temp['name'] = $value['name'];

        $result_json[] = $temp;
      }
    }

    $data = json_encode($result_json);
    print($data);
  }

  public function users($value = '')
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $this->data['page'] = 'users';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function user_details($value = '')
  {
    $this->common_model->checkAdminUserPermission(13);
    $this->data['page'] = 'user_details';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }


  public function users_list($value = '')
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    extract($_POST);

    if ($this->input->post('form_submit')) {
      $this->data['page'] = 'users';
      $username = $this->input->post('username');
      $email = $this->input->post('email');
      $from = $this->input->post('from');
      $to = $this->input->post('to');
      $this->data['lists'] = $this->dashboard->user_filter($username, $email, $from, $to);
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      $lists = $this->dashboard->users_list();

      $data = array();
      $no = $_POST['start'];
      foreach ($lists as $template) {
        $no++;
        $row    = array();
        $row[]  = $no;
        $profile_img = $template->profile_img;
        if (empty($profile_img)) {
          $profile_img = 'assets/img/user.jpg';
        }

        if (!empty($template->country_code)) {
          $mobileNumber = '+' . $template->country_code . '-' . $template->mobileno;
        } else {
          $mobileNumber = $template->mobileno;
        }
        $row[]  = '<h2 class="table-avatar"><a href="#" class="avatar avatar-sm mr-2"> <img class="avatar-img rounded-circle" alt="" src="' . $profile_img . '"></a>
                        <a href="' . base_url() . 'user-details/' . $template->id . '">' . str_replace('-', ' ', $template->name) . '</a></h2>';

        $row[]  = $template->email;
        $row[]  = $mobileNumber;
        $created_date = '-';
        if (isset($template->last_login)) {
          if (!empty($template->last_login) && $template->last_login != "0000-00-00 00:00:00") {
            $date_time = $template->last_login;
            $date_time = ($date_time);
            $created_date = date(settingValue('date_format'), strtotime($date_time));
          }
        }
        $created_at = '-';
        if (isset($template->created_at)) {
          if (!empty($template->created_at) && $template->created_at != "0000-00-00 00:00:00") {
            $date_time = $template->created_at;
            $date_time = ($date_time);
            $created_at = date(settingValue('date_format'), strtotime($date_time));
          }
        }

        if ($this->session->userdata('role') == 1) {
          $display_data = '';
          $display_status = '';
        } else {
          $display_data = 'd-none';
          $display_status = 'disabled';
        }
        $row[]  = $created_at;
        $row[]  = $created_date;
        $vaccine = $template->covid_vaccine;
        if ($vaccine == 1) {
          $covid_vaccine_txt = '<span style="color:limegreen">Yes, one injection</span>';
        } else if ($vaccine == 2) {
          $covid_vaccine_txt = '<span style="color:darkgreen">Yes, two injection</span>';
        } else if ($vaccine == 3) {
          $covid_vaccine_txt = '<span style="color:grey">No, I am under 18</span>';
        } else if ($vaccine == 4) {
          $covid_vaccine_txt = '<span style="color:red">No</span>';
        } else {
          $covid_vaccine_txt = "-";
        }
        $row[]  = $covid_vaccine_txt;

        if ($template->status == 1) {
          $val = 'checked';
        } else {
          $val = '';
        }

        if ($template->type == 1) {
          $row[] = '';
        } else {
          $row[] = '<div class="status-toggle mb-2"><input id="status_' . $template->id . '" class="check change_Status_user1" data-id="' . $template->id . '" type="checkbox" ' . $val . ' ' . $display_status . '><label for="status_' . $template->id . '" class="checktoggle">checkbox</label></div>';
          if ($this->session->userdata('role') == 1) {
            $row[] = '<a href="' . base_url() . 'edit-user/' . $template->id . '" class="btn btn-sm bg-success-light mr-3"><i class="far fa-edit mr-1"></i>Edit</a><a href="javascript:;" class="on-default remove-row btn btn-sm bg-danger-light mr-2 delete_user_data" id="Onremove_' . $template->id . '" data-id="' . $template->id . '"><i class="far fa-trash-alt mr-1"></i> Delete</a>';
          } else {
            $row[] = '<a href="' . base_url() . 'edit-user/' . $template->id . '" class="btn btn-sm bg-success-light mr-3"><i class="far fa-edit mr-1"></i>Edit</a>';
          }
        }



        $data[] = $row;
      }

      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->dashboard->users_list_all(),
        "recordsFiltered" => $this->dashboard->users_list_filtered(),
        "data" => $data,
      );
      echo json_encode($output);
    }
  }

  public function change_rating()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id', $id);
    $this->db->update('rating_type', array('status' => $status));
  }

  public function change_subcategory()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id', $id);
    $this->db->update('subcategories', array('status' => $status));
  }

  public function change_category()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id', $id);
    $this->db->update('categories', array('status' => $status));
  }

  public function change_Status()
  {
    $id = $this->input->post('id');
    $status = $this->input->post('status');

    $this->db->where('id', $id);
    $this->db->update('users', array('status' => $status));
  }

  /*change delete_users */
  public function delete_users()
  {
    $id = $this->input->post('user_id');
    $status = $this->input->post('status');
    $table_data['status'] = $status;
    if ($status == 1) {
      $get_details = $this->db->where('id', $id)->get('users')->row_array();
      $cvaccine = $get_details['covid_vaccine'];
      if ($cvaccine == 4) $table_data['covid_vaccine'] = 0;
    }
    $this->db->where('id', $id);
    if ($this->db->update('users', $table_data)) {
      echo "success";
    } else {
      echo "error";
    }
  }
  /*change delete_user_data */
  public function delete_user_data()
  {
    $id = $this->input->post('user_id');
    $this->db->where('user_id', $id)->delete('book_service');
    $this->db->where('user_id', $id)->delete('book_service_cod');
    $this->db->where('user_id', $id)->delete('payments');
    $this->db->where('user_id', $id)->delete('paypal_transaction');
    $this->db->where('user_id', $id)->delete('rating_review');
    $this->db->where('user_id', $id)->delete('user_address');
    $this->db->where('user_provider_id', $id)->where('type', 2)->delete('wallet_table');
    $this->db->where('user_provider_id', $id)->where('type', 2)->delete('wallet_transaction_history');
    $result = $this->db->where('id', $id)->delete('users');
    if ($result) {
      echo "success";
    } else {
      echo "error";
    }
  }
  /*change delete_provider_data */
  public function delete_provider_data()
  {
    $id = $this->input->post('user_id');
    $this->db->where('provider_id', $id)->delete('book_service');
    $this->db->where('provider_id', $id)->delete('book_service_cod');
    $this->db->where('provider_id', $id)->delete('business_hours');
    $this->db->where('user_id', $id)->delete('services');
    $this->db->where('subscriber_id', $id)->delete('subscription_details');
    $this->db->where('subscriber_id', $id)->delete('subscription_details_history');
    $this->db->where('provider_id', $id)->delete('rating_review');
    $this->db->where('provider_id', $id)->delete('provider_address');
    $this->db->where('user_provider_id', $id)->where('type', 1)->delete('wallet_table');
    $this->db->where('user_provider_id', $id)->where('type', 1)->delete('wallet_transaction_history');
    $result = $this->db->where('id', $id)->delete('providers');
    if ($result) {
      echo "success";
    } else {
      echo "error";
    }
  }
  /*change delete_users */
  public function delete_provider()
  {
    $id = $this->input->post('provider_id');
    $status = $this->input->post('status');
    $table_data['status'] = $status;
    $this->db->where('id', $id);
    if ($this->db->update('providers', $table_data)) {
      echo "success";
    } else {
      echo "error";
    }
  }

  /*change delete_freelancer_data */
  public function delete_freelancer_data()
  {
    $id = $this->input->post('user_id');
    $this->db->where('provider_id', $id)->delete('book_service');
    $this->db->where('provider_id', $id)->delete('book_service_cod');
    $this->db->where('provider_id', $id)->delete('business_hours');
    $this->db->where('user_id', $id)->delete('services');
    $this->db->where('subscriber_id', $id)->delete('subscription_details');
    $this->db->where('subscriber_id', $id)->delete('subscription_details_history');
    $this->db->where('provider_id', $id)->delete('rating_review');
    $this->db->where('provider_id', $id)->delete('provider_address');
    $this->db->where('user_provider_id', $id)->where('type', 3)->delete('wallet_table');
    $this->db->where('user_provider_id', $id)->where('type', 3)->delete('wallet_transaction_history');
    $result = $this->db->where('id', $id)->delete('providers');
    if ($result) {
      echo "success";
    } else {
      echo "error";
    }
  }

  public function adminusers($value = '')
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $this->data['page'] = 'adminusers';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function adminuser_details($value = '')
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $this->data['page'] = 'adminuser_details';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function adminusers_list($value = '')
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    extract($_POST);

    if ($this->input->post('form_submit')) {
      $this->session->set_userdata('user_filter', $this->input->post());
      $this->data['page'] = 'adminusers';
      $username = $this->input->post('username');
      $this->data['lists'] = $this->dashboard->adminuser_filter($username);
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      $this->session->unset_userdata('user_filter');
      $lists = $this->dashboard->adminusers_list();
      $data = array();
      $no = $_POST['start'];
      foreach ($lists as $template) {
        $no++;
        $row    = array();
        $row[]  = $no;
        $profile_img = $template->profile_img;
        if (empty($profile_img)) {
          $profile_img = 'assets/img/user.jpg';
        }

        if ($this->session->userdata('role') == 1) {
          $display_data = '';
          $display_status = '';
        } else {
          $display_data = 'd-none';
          $display_status = 'disabled';
        }
        $row[]  = '<h2 class="table-avatar"><a href="#" class="avatar avatar-sm mr-2"> <img class="avatar-img rounded-circle" alt="" src="' . $profile_img . '"></a>
      <a href="' . base_url() . 'adminuser-details/' . $template->user_id . '">' . str_replace('-', ' ', $template->full_name) . '</a></h2>';

        $row[]  = $template->username;
        $row[]  = $template->email;
        $base_url = base_url() . "adminusers/edit/" . $template->user_id;
        if ($template->user_id != 1) {
          $row[] = "<a href='" . $base_url . "'' class='btn btn-sm bg-success-light mr-2'>
	  <i class='far fa-edit mr-1'></i> Edit
	  </a>
	  <a class='btn btn-sm bg-danger-light delete_show " . $display_data . " data-id='" . $template->user_id . "'><i class='far fa-trash-alt mr-1' ></i> Delete</a>";
        } else {
          $row[] = "";
        }

        $data[] = $row;
      }

      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->dashboard->adminusers_list_all(),
        "recordsFiltered" => $this->dashboard->adminusers_list_filtered(),
        "data" => $data,
      );
      echo json_encode($output);
    }
  }


  public function edit_adminusers($id = NULL)
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    if (!empty($id)) {
      $this->data['user'] = $this->dashboard->get_adminuser_details($id);
      $this->data['title'] = "Edit Admin User";
    } else {
      $this->data['user'] = array();
      $this->data['title'] = "Add Admin User";
    }

    //print_r($this->data['user']); exit;
    $this->data['roles'] = $this->db->select('RPL.*')
      ->from('roles_permissions RP')
      ->join('roles_permissions_lang RPL', 'RP.id=RPL.role_id', 'left')
      ->where('RP.status', 1)
      ->get()
      ->result_array();
    $this->data['page'] = "edit_adminuser";
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }
  public function update_adminuser()
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $params = $this->input->post();
    $user_id = '';
    $uploaded_file_name = '';
    //echo '<pre>'; print_r($params); exit;
    if (isset($_FILES) && isset($_FILES['profile_img']['name']) && !empty($_FILES['profile_img']['name'])) {
      $uploaded_file_name = $_FILES['profile_img']['name'];
      $uploaded_file_name_arr = explode('.', $uploaded_file_name);
      $filename = isset($uploaded_file_name_arr[0]) ? $uploaded_file_name_arr[0] : '';
      $this->load->library('common');
      $upload_sts = $this->common->global_file_upload('uploads/profile_img/', 'profile_img', time() . $filename);
      if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
        $uploaded_file_name = $upload_sts['data']['file_name'];
      }
    }

    if (!empty($uploaded_file_name)) {
      $params['profile_img'] = "uploads/profile_img/" . $uploaded_file_name;
    }
    //$params['role']=2;

    $accesscheck = $params['accesscheck'];
    if (!empty($params['id'])) {
      $user_id = $params['id'];
      unset($params['id']);
      unset($params['accesscheck']);
      unset($params['selectall1']);
      $result = $this->db->where('user_id', $user_id)->update('administrators', $params);
    } else {
      $params['password'] = md5($params['password']);
      unset($params['id']);
      unset($params['accesscheck']);
      unset($params['selectall1']);
      $result = $this->db->insert('administrators', $params);
      $user_id = $this->db->insert_id();
      $token = $this->user->getToken(14, $user_id);
      $this->db->where('user_id', $user_id);
      $this->db->update('administrators', array('token' => $token));
    }
    //$module_result = $this->db->where('status',1)->select('id')->get('admin_modules')->result_array();
    $permission_modules = $this->db->where(array('status' => 1, 'id' => $params['role']))->get('roles_permissions')->row()->permission_modules;
    $module_result = explode(',', $permission_modules);
    foreach ($module_result as $key => $module) {
      $adminparams['admin_id'] = $user_id;
      $adminparams['module_id'] = $module;
      $access_result = $this->db->where('admin_id', $user_id)->where('module_id', $module[$key])->select('id,module_id')->get('admin_access')->row();
      if (empty($access_result)) {
        $adminparams['access'] = 1;
      } else {
        if ($module[$key] == $access_result->module_id) {
          $adminparams['access'] = 1;
        } else {
          $adminparams['access'] = 0;
        }
      }
      if (!empty($access_result)) {
        $adminparams1['access'] = 0;
        $this->db->where('admin_id', $user_id)->update('admin_access', $adminparams1);
        $result = $this->db->where('id', $access_result->id)->update('admin_access', $adminparams);
      } else {
        $result = $this->db->insert('admin_access', $adminparams);
      }
    }
    if ($result == true) {
      if (empty($user_id)) {
        echo json_encode(['status' => true, 'msg' => "Admin Userdetails Added SuccesFully..."]);
      } else {
        echo json_encode(['status' => true, 'msg' => "Admin Userdetails Updated SuccesFully..."]);
      }
    } else {
      echo json_encode(['status' => false, 'msg' => "Someting Went in Server end..."]);
    }
  }

  public function check_adminuser_name()
  {
    $name = $this->input->post('name');
    $id = $this->input->post('id');
    if (!empty($id)) {
      $this->db->select('*');
      $this->db->where('username', $name);
      $this->db->where('user_id !=', $id);
      $this->db->from('administrators');
      $result = $this->db->get()->num_rows();
    } else {
      $this->db->select('*');
      $this->db->where('username', $name);
      $this->db->from('administrators');
      $result = $this->db->get()->num_rows();
    }
    if ($result > 0) {
      $isAvailable = FALSE;
    } else {
      $isAvailable = TRUE;
    }
    echo json_encode(
      array(
        'valid' => $isAvailable
      )
    );
  }

  public function check_adminuser_email()
  {

    $email = $this->input->post('email');
    $id = $this->input->post('id');

    if ($id) {
      $this->db->select('*');
      $this->db->where('email', $email);
      $this->db->where('user_id !=', $id);
      $this->db->from('administrators');
      $result = $this->db->get();
      $result = $result ? $result->num_rows() : 0;
    } else {
      $this->db->select('*');
      $this->db->where('email', $email);
      $this->db->from('administrators');
      $result = $this->db->get();
      $result = $result ? $result->num_rows() : 0;
    }

    if ($result > 0) {
      $isAvailable = FALSE;
    } else {
      $isAvailable = TRUE;
    }
    echo json_encode(
      array(
        'valid' => $isAvailable
      )
    );
  }


  public function adminuser_delete()
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $params = $this->input->post();
    if (!empty($params['id'])) {
      $result = $this->db->where('user_id', $params['id'])->delete('administrators');
      if ($result == true) {
        echo json_encode(['status' => true, 'msg' => "Admin User Deleted SuccessFully..."]);
      } else {
        echo json_encode(['status' => false, 'msg' => "Something Went in server end..."]);
      }
    }
  }

  //Export Excel
  public function adminusers_export()
  {
    $this->common_model->checkAdminUserPermission($this->data['user_role']);
    $style = array(
      'borders' => array(
        'allborders' => array(
          'style' => Border::BORDER_MEDIUM,
          'color' => array('argb' => '006200'),
        ),
      ),
      'fill' => array(
        'type' => Fill::FILL_SOLID,
        'color' => array('rgb' => '006200')
      ),
      'font'  => array(
        'bold'  =>  true
      )
    );
    $fileName = 'users.xlsx';
    $service_filter = $this->session->userdata('user_filter');

    if ($service_filter['form_submit'] == "submit") {

      $username = $service_filter['username'];

      $list = $this->dashboard->get_adminusers_filter($username);
    } else {
      $list = $this->dashboard->get_adminusers_list();
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'User Name');
    $sheet->setCellValue('C1', 'Full Name');


    $sheet->getStyle('A1:H1')->applyFromArray($style);
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);

    $rows = 2;

    foreach ($list as $val) {

      $sheet->setCellValue('A' . $rows, $val['user_id']);
      $sheet->setCellValue('B' . $rows, $val['username']);
      $sheet->setCellValue('C' . $rows, $val['full_name']);
      $rows++;
    }
    $writer = new Xlsx($spreadsheet);
    $writer->save("uploads/service_excel/" . $fileName);
    header("Content-Type: application/vnd.ms-excel");
    redirect(base_url() . "/uploads/service_excel/" . $fileName);
  }

  //USERS EDIT and EMAIL & PHONENO CHECK

  public function check_usr_emailid()
  {
    $inputs = $this->input->post('userEmail');
    $id = $this->input->post('userid');

    $this->db->where('status != ', 0);
    $this->db->like('email', $inputs, 'match');
    if (!empty($id)) {
      $this->db->where('id !=', $id);
    }
    $this->db->from('users');
    $result = $this->db->get()->num_rows();
    if ($result > 0) {
      $isAvailable = FALSE;
    } else {
      $isAvailable = TRUE;
    }
    echo json_encode(
      array(
        'valid' => $isAvailable
      )
    );
  }
  public function check_usr_mobile()
  {
    $inputs = $this->input->post('userMobile');
    $ctrycode = $this->input->post('mobileCode');
    $id = $this->input->post('userid');

    $this->db->where('status != ', 0);
    $this->db->where(array('country_code' => $ctrycode))->like('mobileno', $inputs, 'match', 'after');
    if (!empty($id)) {
      $this->db->where('id !=', $id);
    }
    $this->db->from('users');
    $result = $this->db->get()->num_rows();

    if ($result > 0) {
      $isAvailable = FALSE;
    } else {
      $isAvailable = TRUE;
    }
    echo json_encode(
      array(
        'valid' => $isAvailable
      )
    );
  }

  public function edit_user($id)
  {
    $this->common_model->checkAdminUserPermission(2);
    if ($this->session->userdata('admin_id')) {
      if ($this->input->post('form_submit')) {
        removeTag($this->input->post());
        $id = $this->input->post('id');
        $type = $this->input->post('type');

        $inputs['name'] = $this->input->post('name');
        $inputs['country_code'] = $this->input->post('country_code');
        $inputs['mobileno'] = $this->input->post('mobileno');
        $inputs['email'] = $this->input->post('email');
        $inputs['status'] = $this->input->post('status');
        $inputs['updated_at'] = date('Y-m-d H:i:s');


        if ($this->db->update('users', $inputs, array("id" => $id))) {
          $this->session->set_flashdata('success_message', 'User Updated successfully');
          redirect(base_url() . "users");
        } else {
          $this->session->set_flashdata('error_message', 'Please try again later....');
          redirect(base_url() . "users");
        }
      }

      $this->data['page'] = 'edit_user';
      $this->data['model'] = 'dashboard';
      $this->data['title'] = 'Edit User';

      $this->data['user'] = $this->dashboard->get_users_details($id);
      $this->data['countrycode'] = $this->admin->get_country_code_config();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . "admin");
    }
  }

  public function add_user()
  {
    $this->common_model->checkAdminUserPermission(2);
    if ($this->session->userdata('admin_id')) {
      if ($this->input->post('form_submit')) {
        removeTag($this->input->post());

        $inputs['name'] = $this->input->post('name');
        $inputs['country_code'] = $this->input->post('country_code');
        $inputs['mobileno'] = $this->input->post('mobileno');
        $inputs['email'] = $this->input->post('email');
        $inputs['created_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('users', $inputs)) {
          $this->session->set_flashdata('success_message', 'User Updated successfully');
          redirect(base_url() . "users");
        } else {
          $this->session->set_flashdata('error_message', 'Please try again later....');
          redirect(base_url() . "users");
        }
      }

      $this->data['page'] = 'add_user';
      $this->data['model'] = 'dashboard';
      $this->data['title'] = 'Add User';

      $this->data['countrycode'] = $this->admin->get_country_code_config();
      $this->load->vars($this->data);
      $this->load->view($this->data['theme'] . '/template');
    } else {
      redirect(base_url() . "admin");
    }
  }


  //Push Notification

  public function SendPushNotification()
  {
    $this->common_model->checkAdminUserPermission(19);

    if ($this->input->post()) {
      $admin_id = $this->session->userdata('admin_id');

      $input = $this->input->post();
      $user_access = 0;
      $provider_access = 0;
      if (isset($input['accesscheck']) && !empty($input['accesscheck'])) {
        foreach ($input['accesscheck'] as $val) {
          if ($val == 1) {
            $user_access = 1;
          }
          if ($val == 2) {
            $provider_access = 1;
          }
        }

        if ($user_access == 1) {
          $records = $this->db->select('*')->from('users')->where('status', 1)->get()->result_array();
          $admin_det = $this->db->select('*')->from('administrators')->where('user_id', $admin_id)->get()->result_array();

          if (isset($records) && !empty($records)) {
            foreach ($records as $rec) {
              if ($rec['email'] != '') {
                $user_info = $this->api->get_user_info($rec['id'], 2);
                $tomailid = $rec['email'];
                $phpmail_config = settingValue('mail_config');
                if (isset($phpmail_config) && !empty($phpmail_config)) {
                  if ($phpmail_config == "phpmail") {
                    $from_email = settingValue('email_address');
                  } else {
                    $from_email = settingValue('smtp_email_address');
                  }
                }

                $body = $input['message'];

                $this->load->library('email');
                //Send mail to provider
                if (!empty($from_email) && isset($from_email)) {
                  $mail = $this->email
                    ->from($from_email)
                    ->to($tomailid)
                    ->subject($input['subject'])
                    ->message($body)
                    ->send();
                  if ($mail) {
                    /* insert notification */
                    $msg = ucfirst(strtolower($body));
                    if (!empty($user_info['token'])) {
                      $this->api->insert_notification($admin_det[0]['token'], $user_info['token'], $msg);
                    }
                  }
                }
              }
            }
          }
        }


        if ($provider_access == 1) {
          $providerrecords = $this->db->select('*')->from('providers')->where('status', 1)->where('delete_status', 0)->get()->result_array();
          if (isset($providerrecords) && !empty($providerrecords)) {
            foreach ($providerrecords as $prec) {
              if ($prec['email'] != '') {
                $provider_info = $this->api->get_user_info($prec['id'], 1);
                $ptomailid = $prec['email'];
                $phpmail_config = settingValue('mail_config');
                if (isset($phpmail_config) && !empty($phpmail_config)) {
                  if ($phpmail_config == "phpmail") {
                    $from_email = settingValue('email_address');
                  } else {
                    $from_email = settingValue('smtp_email_address');
                  }
                }

                $body = $input['message'];

                $this->load->library('email');
                if (!empty($from_email) && isset($from_email)) {
                  $mail = $this->email
                    ->from($from_email)
                    ->to($ptomailid)
                    ->subject($input['subject'])
                    ->message($body)
                    ->send();
                  if ($mail) {
                    /* insert notification */
                    $msg = ucfirst(strtolower($body));
                    if (!empty($provider_info['token'])) {
                      $this->api->insert_notification($admin_det[0]['token'], $provider_info['token'], $msg);
                    }
                  }
                }
              }
            }
          }
        }




        $data = array(
          'subject' => $input['subject'],
          'message' => $input['message'],
          'user_status' => $user_access,
          'provider_status' => $provider_access,
          'created_on' => date('Y-m-d H:i:s')
        );

        $ret = $this->db->insert('push_notification', $data);
      }
      $this->session->set_flashdata('success_message', 'Email Template Details updated successfully');
      redirect(base_url() . "/admin/SendPushNotificationList");
    }

    $this->data['page'] = 'send_push_notification';
    $this->data['title'] = "Send Push Notification";
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function send_push_notification()
  {
    $user_inp = $this->input->post();
    $android_users = [];
    if (in_array(1, $user_inp['accesscheck'])) { //user
      $users = $this->dashboard->get_users_device($user_inp['user_city']);
      $android_users = $users['android'];
      $ios_users = $users['ios'];
    }
    if (in_array(2, $user_inp['accesscheck'])) { //provider
      $users = $this->dashboard->get_provider_device($user_inp['user_city'], 1, $user_inp['provider_package']);
      $android_providers = $users['android'];
      $ios_providers = $users['ios'];
    }
    if (in_array(3, $user_inp['accesscheck'])) { //freelancer
      $users = $this->dashboard->get_freelancers_device($user_inp['user_city'], 2);
      $android_freelancers = $users['android'];
      $ios_freelancers = $users['ios'];
    }
    $android = array_merge($android_users, $android_providers, $android_freelancers);
    $ios = array_merge($ios_users, $ios_providers, $ios_freelancers);
    $android = array_column($android, 'device_id');
    $ios = array_column($ios, 'device_id');
    //send notification
    $pnkey = settingValue('firebase_server_key');

    $newmsg = array('title' => $user_inp['subject'], 'message' => $user_inp['message'], 'image' => '');
    $headers = array('Authorization: key=' . $pnkey, 'Content-Type: application/json');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //Android
    $and_registrationIds = array_unique($android);
    $and_SplRegID = array_chunk($and_registrationIds, 1000);
    foreach ($and_SplRegID as $k => $v) {
      $fields = array('registration_ids' => $v, 'data' => $newmsg);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
      }
    }

    //IOS
    $registrationIds = array_unique($ios);
    $SplRegID = array_chunk($registrationIds, 1000);

    foreach ($SplRegID as $k => $v) {
      $fields = array('registration_ids' => $v, 'notification' => $newmsg);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
      }
    }
    //Insert in to db
    $ins_data = array('subject' => $user_inp['subject'], 'message' => $user_inp['message'], 'created_on' => date('Y-m-d H:i:s'));
    $this->db->insert('push_notification', $ins_data);
    //redirect
    $this->session->set_flashdata('success_message', 'Push Notification List Added Successfully');
    redirect(base_url() . "/admin/SendPushNotificationList");
  }
  public function SendPushNotificationList()
  {
    $this->common_model->checkAdminUserPermission(19);
    $this->data['page'] = 'push_notification_list';

    $this->data['list_filter'] = $this->admin->pn_list();

    if ($this->input->post('form_submit')) {
      extract($_POST);
      $this->data['list'] = $this->admin->pn_list();
    } else {
      $this->data['list'] = $this->admin->pn_list();
    }


    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function otherSettings()
  {
    $this->data['page'] = 'other_settings';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }
  public function chatSettings()
  {
    $data = $this->input->post();
    if ($this->input->post('form_submit')) {
      if ($data) {
        if (isset($data['chat_showhide'])) {
          $data['chat_showhide'] = '1';
        } else {
          $data['chat_showhide'] = '0';
        }
        foreach ($data as $key => $val) {
          if ($key != 'form_submit') {
            $this->db->where('key', $key);
            $this->db->delete('system_settings');
            $table_data['key'] = $key;
            $table_data['value'] = $val;
            $table_data['system'] = 1;
            $table_data['groups'] = 'config';
            $table_data['update_date'] = date('Y-m-d');
            $table_data['status'] = 1;
            $this->db->insert('system_settings', $table_data);
          }
        }
        $this->session->set_flashdata('success_message', 'Chat Details updated successfully');
        redirect(base_url() . 'admin/chat-settings');
      }
    }
    $this->data['page'] = 'chat_settings';
    $this->load->vars($this->data);
    $this->load->view($this->data['theme'] . '/template');
  }

  public function socket()
  {
    $data = $this->input->post();
    if ($this->input->post('form_submit')) {
      if ($data) {
        if (isset($data['socket_showhide'])) {
          $data['socket_showhide'] = '1';
        } else {
          $data['socket_showhide'] = '0';
        }
        foreach ($data as $key => $val) {
          if ($key != 'form_submit') {
            $this->db->where('key', $key);
            $this->db->delete('system_settings');
            $table_data['key'] = $key;
            $table_data['value'] = $val;
            $table_data['system'] = 1;
            $table_data['groups'] = 'config';
            $table_data['update_date'] = date('Y-m-d');
            $table_data['status'] = 1;
            $this->db->insert('system_settings', $table_data);
          }
        }
        $this->session->set_flashdata('success_message', 'Socket Details updated successfully');
        redirect(base_url() . 'admin/chat-settings');
      }
    }
  }
}
