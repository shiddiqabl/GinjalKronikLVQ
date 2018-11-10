<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Minmax_controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    function index()
    {
        $data['judul'] = 'Kelola Data Pasien Normalisasi Minmax';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_min_max');
        $this->load->view('templates/header', $data);
        $this->load->view('data_minmax_view', $data);
        $this->load->view('templates/footer');
    }    
    
    function norm_min_max()
    {
        $data['judul'] = 'Normalisasi Min Max';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_min_max');
        $this->load->view('templates/header', $data);
        $this->load->view('data_minmax_view', $data);
        $this->load->view('templates/footer');
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
        redirect('Minmax_controller/index');
    }
    
    function min_max()
    {
        $data_pasien = $this->data_model->get_data_raw('data_pasien'); //Data sebelum normalisasi raw
        $data_pasien_arr = $data_pasien->result_array(); //data pasien dalam bentuk array
        $data_pasien_alias = $this->data_model->get_data_alias('data_pasien')->result_array(); //Data pasien dengan kolom numerik
        $data_pasien_baru = array(); //Inisialisasi data sesudah normalisasi
        $max_kol = array(); //Variabel untuk menyimpan nilai maksimum tiap kolom
        $min_kol = array(); //Variabel untuk menyimpan nilai minimum tiap kolom
        
        $kolom = $data_pasien->num_fields(); //jumlah kolom
        $baris = $data_pasien->num_rows(); //jumlah baris
        
        $kol = array();
        //$kol = array_column($data_arr1, 6); //Mengambil hanya satu kolom dari sebuah array
        //$max_kol = max(array_filter($kol, 'is_numeric')); //Mencari nilai maks dari satu kolom dengan filter numerik
        //$min_kol = min(array_filter($kol, 'is_numeric')); //Mencari nilai min dari satu kolom dengan filter numerik
        
        //Mencari nilai maksimum dan minimum tiap kolom
        for($i = 0; $i < $kolom; $i++) //Mengulang sebanyak jumlah kolom
        {
            $kol = array_column($data_pasien_alias, $i); //Mengambil hanya satu kolom dari sebuah array
            $max_kol[$i] = max(array_filter($kol, 'is_numeric')); //Mencari nilai maks dari satu kolom dengan filter numerik
            $min_kol[$i] = min(array_filter($kol, 'is_numeric')); //Mencari nilai min dari satu kolom dengan filter numerik
        }
        
        //Menormalisasi min-max data pasien
        for($i = 0; $i < $baris; $i++) //Mengulang sebanyak jumlah baris
        {
            $data_pasien_baru[$i][0] = $data_pasien_alias[$i][0]; //ID tidak dinormalisasi
            for($j = 1; $j < ($kolom - 1); $j++) //Mengulang mulai dari kolom ke 1 hingga 24 (diluar kolom ID dan kelas)
            {
                if (is_numeric($data_pasien_alias[$i][$j]) == false) //Mengecek apakah sel berisi data numerik
                {
                    //Jika bukan numerik, data tidak dinormalisasi
                    $data_pasien_baru[$i][$j] = $data_pasien_alias[$i][$j];
                }else //Jika data dalam sel numerik
                {
                    //Rumus min max : x(normal) = (x(lama) - x(min)) / (x(maks) - x(min))
                    $data_pasien_baru[$i][$j] = ($data_pasien_alias[$i][$j] - $min_kol[$j]) / ($max_kol[$j] - $min_kol[$j]);
                }
            }
            $data_pasien_baru[$i][25] = $data_pasien_alias[$i][25]; //Kelas tidak dinormalisasi
        }
        
        foreach ($data_pasien_baru as $data_pasien)
        {
            $pasien_minmax = array(
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
            $this->data_model->insert_data('data_pasien_min_max',$pasien_minmax);
        }
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert"> Data berhasil ditambahkan</div>');
        redirect('Minmax_controller/index');
    }
    
    
}