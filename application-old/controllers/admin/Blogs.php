<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blogs extends CI_Controller {

    public $data;

    public function __construct() {
        parent::__construct();
        error_reporting(0);
        $this->load->model('admin_model', 'admin');
        $this->load->model('blogs_model', 'blogs');
		$this->load->model('common_model','common_model');
        $this->data['theme'] = 'admin';
        $this->data['model'] = 'settings';
        $this->data['model'] = 'blogs';
        $this->data['base_url'] = base_url();
        $this->load->helper('user_timezone_helper');
        $this->data['user_role'] = $this->session->userdata('role');
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $lang = !empty($this->session->userdata('lang'))?$this->session->userdata('lang'):'en';
        $this->data['language_content'] = get_admin_languages($lang);

        $this->load->helper('ckeditor');
		$this->data['ckeditor_editor1'] = array(
			'id'   => 'ck_editor_textarea_id',
			'path' => 'assets/js/ckeditor',
			'config' => array(
				'toolbar' 					=> "Full",
				'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
				'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
				'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
				'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
				'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
				'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			)
        );
        $this->data['ckeditor_editor2'] = array(
			'id'   => 'ck_editor_textarea_id1',
			'path' => 'assets/js/ckeditor',
			'config' => array(
				'toolbar' 					=> "Full",
				'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
				'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
				'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
				'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
				'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
				'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			)
        );
        $this->data['ckeditor_editor3'] = array(
			'id'   => 'ck_editor_textarea_id2',
			'path' => 'assets/js/ckeditor',
			'config' => array(
				'toolbar' 					=> "Full",
				'filebrowserBrowseUrl'      => base_url() . 'assets/js/ckfinder/ckfinder.html',
				'filebrowserImageBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Images',
				'filebrowserFlashBrowseUrl' => base_url() . 'assets/js/ckfinder/ckfinder.html?Type=Flash',
				'filebrowserUploadUrl'      => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
				'filebrowserImageUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
				'filebrowserFlashUploadUrl' => base_url() . 'assets/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
			)
		);
    }

    public function index() {
		$this->data['posts'] = $this->admin->get_posts_all(1);
		$this->data['type'] = 1;
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function pending() {
		$this->data['posts'] = $this->admin->get_posts_all(2);
		$this->data['type'] = 2;
        $this->data['page'] = 'index';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

	/* Add Blog Post Start */
	public function add_blog(){
		$this->data['languages'] = $this->admin->language_list();
		$this->data['categories'] = $this->admin->get_blog_categories_by_lang($this->data['languages'][0]['id']);

		if($this->input->post()){

			$input = $this->input->post();
			if (isset($_FILES) && isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                if(!is_dir('uploads/blogs')) {
                    mkdir('./uploads/blogs/', 0777, TRUE);
                }
                $uploaded_file_name = $_FILES['image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = $uploaded_file_name;
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/blogs/', 'image', time() . $filename);
				
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];

                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/blogs/' . $uploaded_file_name;
                        $input['image_small'] = $image_url;
                        $input['image_default'] = $image_url;
                        // $input['image_small'] = $this->image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name);
                        // $input['image_default'] = $this->image_resize(381, 286, $image_url, $uploaded_file_name);
                    }
                }
            }
            $output = preg_replace ('/[^\p{L}\p{N}]/u', ' ', $this->input->post('title'));
            $blog_url = str_replace(" ","-",trim($output));
            $input['url'] = strtolower($blog_url);
			$input['storage'] = 'local';
			$input['status'] = 1;
            $input['createdBy'] = $this->session->userdata('admin_id');
            $this->db->insert('blog_posts', $input);
            $ret_id = $this->db->insert_id();
            if (!empty($ret_id)) {
                $this->session->set_flashdata('success_message', 'Blog added successfully');
                redirect(base_url() . "blogs");
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "add-blog");
            }
		}
		$this->data['page'] = 'add_blog';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');

	}
	/* Add Blog Post End */

	/* Edit Blog Post Start */
	public function edit_blog($id){
		$this->data['languages'] = $this->admin->language_list();
        $this->data['posts'] = $posts = $this->admin->get_posts_all("",$id);
		$this->data['categories'] = $this->admin->get_blog_categories_by_lang($posts[0]['lang_id']);

		if($this->input->post()){

			$input = $this->input->post();
			$id = $this->input->post('id');
			if (isset($_FILES) && isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                if(!is_dir('uploads/blogs')) {
                    mkdir('./uploads/blogs/', 0777, TRUE);
                }
                $uploaded_file_name = $_FILES['image']['name'];
                $uploaded_file_name_arr = explode('.', $uploaded_file_name);
                $filename = $uploaded_file_name;
                $this->load->library('common');
                $upload_sts = $this->common->global_file_upload('uploads/blogs/', 'image', time() . $filename);
				
                if (isset($upload_sts['success']) && $upload_sts['success'] == 'y') {
                    $uploaded_file_name = $upload_sts['data']['file_name'];

                    if (!empty($uploaded_file_name)) {
                        $image_url = 'uploads/blogs/' . $uploaded_file_name;
                        $input['image_small'] = $image_url;
                        $input['image_default'] = $image_url;
                        // $input['image_small'] = $this->image_resize(50, 50, $image_url, 'thu_' . $uploaded_file_name);
                        // $input['image_default'] = $this->image_resize(381, 286, $image_url, $uploaded_file_name);
                    }
                }
            }
            $output = preg_replace ('/[^\p{L}\p{N}]/u', ' ', $this->input->post('title'));
            $blog_url = str_replace(" ","-",trim($output));
            $input['url'] = strtolower($blog_url);
            $input['updatedBy'] = $this->session->userdata('admin_id');
            $input['updatedAt'] = date('Y-m-d H:i:s');
            $this->db->where('id',$id);
            $this->db->update('blog_posts', $input);
            if (!empty($id)) {
                $this->session->set_flashdata('success_message', 'Blog Updated successfully');
                redirect(base_url() . "blogs");
            } else {
                $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
                redirect(base_url() . "edit-blog/".$id);
            }
		}
		$this->data['page'] = 'edit_blog';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');

	}

	/* Edit Blog Post End */

    /* Blog Details Post Start */
	public function blog_details($id){
        $this->data['posts'] = $this->admin->get_posts_all("",$id);
        $this->data['page'] = 'blog_details';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');

    }
    /* Blog Details Post End */

    /* Blog Delete Start*/
    public function delete_blogs() {
		$this->common_model->checkAdminUserPermission(2);
        $id = $this->input->post('post_id');
        $table_data['status'] = 0;
            $this->db->where('id', $id);
            if ($this->db->update('blog_posts', $table_data)) {
				
                $this->session->set_flashdata('success_message', 'Blog deleted successfully'); 
                echo 1;
        } else {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
           echo 1;
        }
    }
    /* Blog Delete End*/

    /* Blog Status Update Start*/
    public function update_blog_status() {
		$this->common_model->checkAdminUserPermission(2);
        $id = $this->input->post('post_id');
        $status = $this->input->post('status');
        $table_data['status'] = $status;
            $this->db->where('id', $id);
            if ($this->db->update('blog_posts', $table_data)) {
				
                $this->session->set_flashdata('success_message', 'Blog status updated successfully'); 
                echo 1;
        } else {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
           echo 1;
        }
    }
    /* Blog Status Update End*/


	//get categories by language
    public function get_blog_categories_by_lang()
    {
        $lang_id = $this->input->post('lang_id', true);
        if (!empty($lang_id)):
            $categories = $this->admin->get_blog_categories_by_lang($lang_id);
            foreach ($categories as $item) {
                echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
            }
        endif;
    }

	public function image_resize($width = 0, $height = 0, $image_url, $filename) {

        $source_path = base_url() . $image_url;
        list($source_width, $source_height, $source_type) = getimagesize($source_path);
        switch ($source_type) {
            case IMAGETYPE_GIF:
                $source_gdim = imagecreatefromgif($source_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gdim = imagecreatefromjpeg($source_path);
                break;
            case IMAGETYPE_PNG:
                $source_gdim = imagecreatefrompng($source_path);
                break;
        }

        $source_aspect_ratio = $source_width / $source_height;
        $desired_aspect_ratio = $width / $height;

        if ($source_aspect_ratio > $desired_aspect_ratio) {
            /*
             * Triggered when source image is wider
             */
            $temp_height = $height;
            $temp_width = (int) ($height * $source_aspect_ratio);
        } else {
            /*
             * Triggered otherwise (i.e. source image is similar or taller)
             */
            $temp_width = $width;
            $temp_height = (int) ($width / $source_aspect_ratio);
        }

        /*
         * Resize the image into a temporary GD image
         */

        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled(
                $temp_gdim, $source_gdim, 0, 0, 0, 0, $temp_width, $temp_height, $source_width, $source_height
        );

        /*
         * Copy cropped region from temporary image into the desired GD image
         */

        $x0 = ($temp_width - $width) / 2;
        $y0 = ($temp_height - $height) / 2;
        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy(
                $desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $width, $height
        );

        /*
         * Render the image
         * Alternatively, you can save the image in file-system or database
         */
        $filename_without_extension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $image_url = "uploads/blogs/" . $filename_without_extension . "_" . $width . "_" . $height . "." . $extension;

        imagepng($desired_gdim, $image_url);

        return $image_url;

        /*
         * Add clean-up code here
         */
    }

    public function comments() {
        $this->data['comments'] = $this->admin->get_all_comments();
        $this->data['type'] = 1;
        $this->data['page'] = 'comments';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }

    public function changeCommentStatus() {
        $id = $this->input->post('comments_id');
        $table_data['status'] = $this->input->post('status');
        $this->db->where('id', $id);
        if ($this->db->update('blog_comments', $table_data)) {          
            $this->session->set_flashdata('success_message', 'Comments status changed successfully');
            echo 1;
        } else {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            echo 2;
        }
    }
    

}