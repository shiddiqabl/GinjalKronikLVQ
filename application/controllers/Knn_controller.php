<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Knn_controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    function index()
    {
        $data['judul'] = 'Kelola Data Pengisian Nila Kosong';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_knn');
        $this->load->view('templates/header', $data);
        $this->load->view('data_knn_view', $data);
        $this->load->view('templates/footer');
    }
    
    function knn_norm()
    {
        $data_pasien = $this->data_model->get_data_raw('data_pasien_min_max'); //Data minmax raw
        $data_pasien_alias = $this->data_model->get_data_alias('data_pasien_min_max')->result_array(); //Data minmax dengan kolom numerik
        $data_lengkap = array(); //Variabel untuk menyimpan data pasien lengkap
        $data_tdk_lengkap = array(); //Variabel untuk menyimpan data pasien tidak lengkap
        $data_baru = array(); //Variabel untuk menyimpan data hasil normalisasi KNN
        
        $kolom = $data_pasien->num_fields(); //jumlah kolom
        $baris = $data_pasien->num_rows(); //jumlah baris
        
        $bar = array(); //Variabel untuk menyimpan satu baris
        
        //Memisahkan data lengkap dengan data tidak lengkap
        for($i = 0; $i < $baris; $i++) //Mengulang sebanyak jumlah baris
        {
            $bar = $data_pasien_alias[$i]; //Mengambil baris ke-i
            if(in_array("?", $bar)) //Mengecek apakah terdapat nilai kosong pada baris ke-i
            {
                $data_tdk_lengkap[] = $bar; //Jika terdapat nilai kosong masik ke array data_tdk_lengkap
            }
            else
            {
                $data_lengkap[] = $bar; //Jika baris lengkap, masuk ke array data_lengkap
            }
        }
              
        $jml_lengkap = count($data_lengkap); //Menghitung baris data lengkap
        $jml_tdk_lengkap = count($data_tdk_lengkap); //Menghitung baris data tidak lengkap
        $arr_jarak = array(); //Variabel array untuk menyimpan nilai jarak
        $data_terisi = array();//Variabel untuk menyimpan data yang sudah diisi
   
        //Mengisi nilai kosong pada tabel tak lengkap
        for($n = 0; $n < $jml_tdk_lengkap; $n++) //Mengulang sebanyak data tak lengkap
        {
            $bar_tdk_lengkap[0] = $data_tdk_lengkap[$n]; //Mengambil satu baris dari tabel data tidak lengkap
           
            for ($l = 0; $l < $jml_lengkap; $l++) //Mengulang sebanyak data lengkap
            {
                $bar_lengkap = array($data_lengkap[$l]); //Mengambil satu baris dari tabel data lengkap
                //$sum = 0; //Variabel untuk menyimpan hasil penjumlahan
                $sum = array();
                
                //Mencari nilai euclidean distance antara 2 data
                for($j = 1; $j < ($kolom - 1); $j++) //Mengulang sebanyak jumlah kolom kecuali kolom ID dan kelas
                {
                    if(!($bar_tdk_lengkap[0][$j] == "?")) //Jika kolom pada tabel tidak lengkap berisi angka
                    {
                        //Hitung kolom ke-j pada data tidak lengkap dengan kolom ke-j pada data lengkap                        
                        $hasil_kurang = $bar_lengkap[0][$j] - $bar_tdk_lengkap[0][$j]; //Mengurangi data tidak lengkap dengan data lengkap
                        $hasil_kuadarat = pow($hasil_kurang, 2); //Mengkuadratkan hasil pengurangan                        
                        $sum[] = $hasil_kuadarat; //Menghitung total nilai kuadrat
                    }
                }
                $nilai_sum[$l] = array_sum($sum); //Memasukkan masing-masing nilai jumlah ke variabel nilai_sum
                $arr_jarak[$l] = array($bar_lengkap[0][0], sqrt($nilai_sum[$l])); //Memasukkan hasil penghitungan ke dalam variabel arr_jarak
            }
            
            //Mencari 5 nilai euclidean terbesar            
            $jarak_terbesar = array(); //Variabel untuk menyimpan 5 ID dan nilai dengan jarak terbesar            
            
            usort($arr_jarak, function($a, $b) //Menyortir variabel arr_jarak dari yang terbesar hingga terkecil
            {
                return $b[1] > $a[1];
            });
            
            $jarak_terbesar = array_slice($arr_jarak, 0, 5); //"Memotong" array hingga tersisa 5 terbesar
            
            //Mengambil 5 array dengan nilai terbesar
            $arr_terbesar = array(); //Menyimpan semua value 5 array dengan jarak terbesar
            $kol_arr_jarak = array_column($data_lengkap, 0); //Mengambil kolom ID dari tabel data lengkap
            
            for ($o = 0; $o < 5; $o++)
            {                
                $key = array_search($arr_jarak[$o][0], $kol_arr_jarak); //Mencari letak array terbesar dalam tabel data lengkap
                $arr_terbesar[] = $data_lengkap[$key]; //Memasukkan data dari tabel data lengkap ke variabel arr_terbesar
            }
            
            //Mengisi kolom kosong
            $temp_kolom = array(); //Variabel untuk menampung kolom terbesar
            
            for ($k = 1; $k < ($kolom - 1); $k++) //Memeriksa tiap kolom kecuali kolom ID dan kelas
            {
                if($bar_tdk_lengkap[0][$k] == "?") //Memeriksa apakah kolom tersebut berisi nilai kosong
                {
                    //Jika TRUE, ambil nilai kolom dari daftar tabel jarak terbesar
                    for($m = 0; $m < 5; $m++) //Mengulang sebanyak jumlah data pada arr_terbesar
                    {
                        //Mengambil nilai kolom dari arr_terbesar pada baris ke-m dan kolom ke-k
                        $temp_kolom[$m] = $arr_terbesar[$m][$k]; 
                    }
                    
                    //Memeriksa kolom ke berapa
                    if ($k == 1 or $k == 2 or ($k > 9 and $k < 19))
                    {
                        //Jika nilai variabel numerikal
                        $sum_kolom = array_sum($temp_kolom); //Menghitung total nilai pada variabel temp_kolom
                        $avg_kolom = $sum_kolom / 5; //Mencari nilai rata-rata
                        $bar_tdk_lengkap[0][$k] = $avg_kolom; //Mengisi nilai rata-rata di variabel yg kosong
                    }
                    else 
                    {
                        //Jika nilai variabel nominal 
                        $count = array_count_values($temp_kolom); //Menghitung berapa banyak sebuah nilai muncul
                        arsort($count); //Menyortir dari yang paling besar
                        $most_frequent = array_keys($count);
                        //Mengisi nilai paling sering muncul di variabel yg kosong
                        $bar_tdk_lengkap[0][$k] = $most_frequent[0]; 
                    }                    
                }
            }
            //Memasukkan hasil pengisian ke variabel data_terisi
            $data_terisi[$n] = $bar_tdk_lengkap[0]; 
        }
        
        //Menggabungkan tabel data_lengkap dengan tabel data_terisi        
        $kol_lengkap = array_column($data_lengkap, 0); //Mengambil kolom ID dari tabel data_lengkap
        $kol_terisi = array_column($data_terisi, 0); //Mengambil kolom ID dari tabel data_terisi
        $data_gabungan = array(); //Variabel untuk menyimpan gabungan 2 tabel tersebut
        
        for ($p = 0; $p <= $baris; $p++) //Mengulang sebanyak jumlah data
        {
            if (in_array($p, $kol_lengkap)) //Memeriksa apakah ID ke-p terdapat di tabel data_lengkap
            {
                //Jika TRUE maka cari letak data tersebut di tabel data_lengkap
                $kunci = array_search($p, $kol_lengkap);
                //Kemudian masukkan data ke tabel data_gabungan
                $data_gabungan[$p] = $data_lengkap[$kunci];
            }
            elseif (in_array($p, $kol_terisi)) //Memeriksa apakah ID ke-p terdapat di tabel data_terisi
            {
                //Jika TRUE maka cari letak data tersebut di tabel data_terisi
                $kunci = array_search($p, $kol_terisi);
                //Kemudian masukkan data ke tabel data_gabungan
                $data_gabungan[$p] = $data_terisi[$kunci];
            }
        }
        
        //Memasukkan data ke database
        foreach ($data_gabungan as $data_pasien)
        {
            $pasien_knn = array(
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
            $this->data_model->insert_data('data_pasien_knn',$pasien_knn);
        }
        
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert"> Data berhasil ditambahkan</div>');
        redirect('Knn_controller/index');
        
        /*
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert"> Hasil Euclidean baris satu adalah = '.$baris.'</div>');
        $data['judul'] = 'Kelola Data Pengisian Nila Kosong';
        $data['data_pasien'] = $data_terisi;
        $this->load->view('templates/header', $data);
        $this->load->view('data_knn_view', $data);
        $this->load->view('templates/footer');*/
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
        redirect('Knn_controller/index');
    }
}