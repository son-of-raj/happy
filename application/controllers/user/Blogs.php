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
        $this->data['theme'] = 'user';
        $this->data['module'] = 'blogs';
        $this->data['page'] = '';
        $this->data['base_url'] = base_url();
        $this->load->model('home_model', 'home');
        $this->load->helper('common');
        $this->load->helper('user_timezone_helper');
        $this->data['user_role'] = $this->session->userdata('role');
        $this->data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
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
		
        
    }

    public function index() {
		
		$this->data['type'] = 1;
        $this->data['page'] = 'index';
        $lang_id = $this->db->get_where('language', array('language_value'=>$this->user_selected))->row()->id;
        // $this->data['blogs'] = $this->home->get_all_blogs($lang_id,3);
        $this->data['posts'] = $this->home->get_all_blogs($lang_id);
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');
    }
    public function blog_details($url){
        $url = rawurldecode($url);
        $this->data['posts'] = $this->home->get_all_blogs("","",$url);
        $blog_id = $this->data['posts'][0]['id'];
        $this->data['comments'] = $this->home->getComments($blog_id);
        $this->data['page'] = 'blog_details';
        $this->load->vars($this->data);
        $this->load->view($this->data['theme'] . '/template');

    }

    public function blogComments() {
        if($this->input->post()) {
            $comments = array(
                'post_id' => $this->input->post('post_id'),
                'user_id' => $this->session->userdata('id'),
                'name' => $this->session->userdata('name'),
                'email' => $this->session->userdata('email'),
                'comment' => $this->input->post('blog_comments'),
                'status' => 0,
                'created_at' => date('Y-m-d H:i:s')
            );

            $save_data = $this->db->insert('blog_comments', $comments);
            if ($save_data) {
                $this->session->set_flashdata('success_message', ' Your comment has been sent. It will be published after being reviewed by the site management');
                redirect(base_url());
            } else {
                $this->session->set_flashdata('error_message', 'Comments created failed');
                redirect(base_url());
            }
        }
    }

    public function deleteComments() {
        $id = $this->input->post('comments_id');
        $table_data['status'] = 2;
        $this->db->where('id', $id);
        if ($this->db->update('blog_comments', $table_data)) {           
            $this->session->set_flashdata('success_message', 'Comments deleted successfully');
            echo 1;
        } else {
            $this->session->set_flashdata('error_message', 'Something wrong, Please try again');
            echo 2;
        }
    }
}