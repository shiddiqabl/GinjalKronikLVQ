<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Data_controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    function index()
    {
        $data['judul'] = 'Kelola Data Pasien';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien');
        $this->load->view('templates/header', $data);
        $this->load->view('data_view', $data);
        $this->load->view('templates/footer');
    }
    
    function data_baru()
    {
        $data['judul'] = 'Tambah Data Pasien Baru';
        $this->load->view('templates/header', $data);
        $this->load->view('data_baru_view');
        $this->load->view('templates/footer');
    }  
    
    function importcsv($table)
    {
        $data['data_pasien'] = $this->data_model->get_data($table);
        $data['error'] = '';    //initialize image upload error array to empty
        
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
        
        $this->load->library('upload', $config);
        
        
        // If upload failed, display error
        if (!$this->upload->do_upload('data_pasien'))
        {
            $error_message = $this->upload->display_errors();
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">'.$error_message.'</div>');
            redirect('data_controller/index');
            /*
            $data['error'] = $this->upload->display_errors();
            
            $this->load->view('data_view', $data);*/
        } else
        {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            $filename = $file_data['file_name'];
            
            //Reading file
            if(!fopen($file_path, 'r'))
            {
                //If opening file failed
                $data['error'] = "Error occured";
                $this->load->view('data_view', $data);
            }
            else
            {
                //If opening file success
                $file = fopen($file_path, 'r');
                $i = 0;
                
                $import_data_arr = array();
                
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE)
                {
                    $num = count($filedata);
                    
                    for ($c=0; $c < $num; $c++)
                    {
                        $import_data_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);
                
                $skip = 0;
                
                //Insert Import data
                foreach($import_data_arr as $data_pasien)
                {
                    if($skip != 0)
                    {
                        $pasien_baru = array(
                            'ID' => $data_pasien[0],
                            'AGE' => $data_pasien[1],
                            'BP' => $data_pasien[2],
                            'SG' => $data_pasien[3],
                            'AL' => $data_pasien[4],
                            'SU' => $data_pasien[5],
                            'RBC' => $data_pasien[6],
                            'PC' => $data_pasien[7],
                            'PCC' => $data_pasien[8],
                            'BA' => $data_pasien[9],
                            'BGR' => $data_pasien[10],
                            'BU' => $data_pasien[11],
                            'SC' => $data_pasien[12],
                            'SOD' => $data_pasien[13],
                            'POT' => $data_pasien[14],
                            'HEMO' => $data_pasien[15],
                            'PCV' => $data_pasien[16],
                            'WBCC' => $data_pasien[17],
                            'RBCC' => $data_pasien[18],
                            'HTN' => $data_pasien[19],
                            'DM' => $data_pasien[20],
                            'CAD' => $data_pasien[21],
                            'APPET' => $data_pasien[22],
                            'PE' => $data_pasien[23],
                            'ANE' => $data_pasien[24],
                            'CLASS' => $data_pasien[25],
                        );
                        $this->data_model->insert_data('data_pasien',$pasien_baru);
                    }
                    $skip ++;
                }
                $this->session->set_flashdata('success', 'Data pasien baru berhasil dimasukkan');
                redirect(base_url().'data_controller/index');
            }
        }
    }
    
    function exportcsv($table)
    {
        //Membuat nama file dan mempersiapkan header HTTP
        $filename = $table.".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename = $filename");
        header("Content-Type: application/csv; ");
        
        //Ambil data dari database
        $data_pasien = $this->data_model->get_data($table);
        
        //Membuat file
        $file = fopen('php://output', 'w');
        
        foreach ($data_pasien as $data_export){
            fputcsv($file, $data_export);
        }
        fclose($file);
        exit; 
    }
    
    function hapus_data($table)
    {
        $this->data_model->delete_data($table);
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Data berhasil dihapus</div>');
        redirect('data_controller/index');        
    }    
}