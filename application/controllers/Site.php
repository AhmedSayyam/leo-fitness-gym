<?php 

class Site extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Admin_Model');
    }

    public function index(){
        $this->load->view('home');
    }
}



?>