<?php
class Main extends CI_Controller{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    public function index()
    {
        $data['data_pasien'] = $this->data_model->get_data('data_pasien');
        $data['judul']= 'Home';
        $this->load->view('templates/header', $data);
        $this->load->view('main_view', $data);
        $this->load->view('templates/footer');
    }
    
}