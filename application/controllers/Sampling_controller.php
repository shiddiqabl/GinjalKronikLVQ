<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sampling_controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    function index()
    {
        $data['judul'] = 'Kelola Data Proses Sampling Data';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_sampling');
        $this->load->view('templates/header', $data);
        $this->load->view('data_sampling_view', $data);
        $this->load->view('templates/footer');
    }
    
    function cb_undersampling()
    {
        $start = microtime(true);
        //Tentukan rasio akhir data yg diinginkan
        $r = 1;
        $jml_centroid = 14;
        $data_kluster = $this->data_model->get_data('kmeans_kluster');
        $data_alias = $this->data_model->get_data_alias('data_pasien_knn')->result_array();
        $jml_data = count($data_kluster);
        $deskripsi_kluster = array();
        $tabel_minoritas = array();
        $mayoritas_per_kluster = array();
        $kluster_ma = array();
        
        //Inisialisasi tabel anggota mayoritas per kluster
        /*for ($o = 0; $o < $jml_centroid; $o++)
        {
            $kluster_ma[$o][] = $o; //ID kluster            
        }*/
        
        //Memasukkan data anggota per kluster 
        for ($p = 0; $p < $jml_data; $p++)
        {
            $data_ambil = $data_kluster[$p];
            for ($q = 0; $q < $jml_centroid; $q++)
            {
                if ($data_ambil['KLUSTER'] == $q && $data_ambil['CLASS'] == 1)
                {
                    $kluster_ma[$q][] = $data_ambil;
                }
            }            
        }
        
        //Inisialisasi tabel deskripsi kluster
        for($i = 0; $i < $jml_centroid; $i++)
        {
            $deskripsi_kluster[$i][0] = $i; //ID Kluster
            $deskripsi_kluster[$i][1] = 0; //Jumlah mayoritas di kluster ke-i
            $deskripsi_kluster[$i][2] = 0; //Jumlah minoritas di kluster ke-i           
        }
        
        for($j = 0; $j < $jml_data; $j++)
        {
            $id_pasien = $data_kluster[$j]['ID_PASIEN'];
            $key = array_search($id_pasien, array_column($data_alias, 0));
            $kluster = $data_kluster[$j]['KLUSTER'];
            if ($data_kluster[$j]['CLASS'] == 1)
            {
                //Menambah jumlah mayoritas
                $deskripsi_kluster[$kluster][1] += 1;
                $mayoritas_per_kluster[$kluster][] = $data_alias[$key][0];
            }
            else 
            {
                //Menambah jumlah minoritas
                $deskripsi_kluster[$kluster][2] += 1;
                $tabel_minoritas[] = $data_alias[$key]; 
            }
        }
        
        $jml_minoritas = count($tabel_minoritas);
        $perbandingan_total = 0;
        
        //Mencari perbandingan jumlah mayoritas dan minoritas
        for($k = 0; $k < $jml_centroid; $k++)
        {
            //Jika minoritas bernilai 0
            if ($deskripsi_kluster[$k][2] == 0)
            {
                $deskripsi_kluster[$k][3] = $deskripsi_kluster[$k][1];
                $perbandingan_total += $deskripsi_kluster[$k][1];
            }
            else 
            {
                $ma_per_mi = round($deskripsi_kluster[$k][1] / $deskripsi_kluster[$k][2]);
                $deskripsi_kluster[$k][3] = $ma_per_mi;
                $perbandingan_total += $ma_per_mi;
            }            
        }        
        
        //Menentukan berapa banyak sampel mayoritas diambil
        for ($l = 0; $l < $jml_centroid; $l++)
        {           
            
            $sampel_ma = $r * $jml_minoritas * ($deskripsi_kluster[$l][3] / $perbandingan_total);
            $deskripsi_kluster[$l][4] = round($sampel_ma);
        }        
        $jml_mayoritas = array_sum(array_column($deskripsi_kluster, 4));
        if ($jml_mayoritas != $jml_minoritas)
        {
            $selisih = abs($jml_mayoritas - $jml_minoritas);
            $kol_sampel = array_column($deskripsi_kluster, 4);
            $sampel_terbanyak = array_search(max($kol_sampel), $kol_sampel);
            $deskripsi_kluster[$sampel_terbanyak][4] = $deskripsi_kluster[$sampel_terbanyak][4] - 1;
        }
        
        //Mengambil sampel mayoritas dari masing-masing kluster
        $tabel_mayoritas = array();
        $tabel_temp = array();
        
        /*for ($m = 0; $m < $jml_centroid; $m++)
        {
            $id_sampel = array();
            $jml_sampel = $deskripsi_kluster[$m][4];
            if ($jml_sampel > 0)
            {
                $id_sampel = array_rand($mayoritas_per_kluster[$m], $jml_sampel);
                if ($jml_sampel > 1)
                {
                    for($n = 0; $n < $jml_sampel; $n++)
                    {
                        $id = array_search($mayoritas_per_kluster[$m][$id_sampel[$n]], array_column($data_alias, 0));
                        $tabel_mayoritas[] = $data_alias[$id];
                    }
                }
                else 
                {
                    $id = array_search($mayoritas_per_kluster[$m][$id_sampel], array_column($data_alias, 0));
                    $tabel_mayoritas[] = $data_alias[$id];
                }
            }            
        }*/
        
        for($n = 0; $n < $jml_centroid; $n++)        
        {
            $id_terdekat = array();
            $jml_sampel = $deskripsi_kluster[$n][4];
            echo 'Jumlah sampel ke-'.$n.' sebesar = '.$jml_sampel.'<br>';            
            usort($kluster_ma[$n], function($a, $b) //Menyortir tabel kluster_ma dari yang terdekat hingga terjauh
            {
                return $b['JARAK_KE_CENTROID'] < $a['JARAK_KE_CENTROID'];
            });                
            //Memotong data dengan jarak terdekat
            $tabel_terdekat[$n] = array_slice($kluster_ma[$n], 0, $jml_sampel);
            //Mengambil id data dengan jarak terdekat
            $id_terdekat = array_column($tabel_terdekat[$n], 'ID_PASIEN');
            //Mengambil data anggota terdekat 
            for($o = 0; $o < $jml_sampel; $o++)
            {
              $id = array_search($id_terdekat[$o], array_column($data_alias, 0));
              $tabel_mayoritas[] = $data_alias[$id];
            }            
        } 
        
        //Menggabungkan tabel mayoritas dengan tabel minoritas
        $tabel_gabungan = array_merge($tabel_mayoritas, $tabel_minoritas);      
        
        //Memasukkan data ke database
        //$this->data_model->insert_data_sampling('data_pasien_sampling', $tabel_gabungan);
        
        $time = microtime(true) - $start;
        echo 'waktu eksekusi : '.$time.'s <br>';
        echo 'jumlah minoritas : '.$jml_minoritas.'<br>';        
        echo 'total sampel yg diambil : '.$jml_mayoritas.'<br>';
        echo 'Total tabel mayoritas : '.count($tabel_mayoritas).'<br>';       
        echo 'perbandingan total :'.$perbandingan_total; 
        
        $data['judul'] = 'Kelola Data Pengisian Nila Kosong';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_sampling');        
        $this->load->view('templates/header', $data);
        $this->load->view('data_sampling_view', $data);
        $this->load->view('templates/footer');
    }  
    
    function kmeans()
    {
        //Menentukan nilai K 
        $k = 14;
        $start = microtime(true);
        //set timeout
        ini_set('max_execution_time', 300);        
        //ambil data sumber
        $data = $this->data_model->get_data('data_pasien_knn');
        $data_alias = $this->data_model->get_data_alias('data_pasien_knn')->result_array();
        $jml_data = count($data) - 1;
        for($i = 0; $i < $k; $i++){
            //set centroid awal, nilai random dari data sumber            
            $centroid[$i][0] = $i;
            $centroid[$i][1] = $data[mt_rand(0, $jml_data)]['AGE'];
            $centroid[$i][2] = $data[mt_rand(0, $jml_data)]['BP'];
            $centroid[$i][3] = $data[mt_rand(0, $jml_data)]['SG'];
            $centroid[$i][4] = $data[mt_rand(0, $jml_data)]['AL'];
            $centroid[$i][5] = $data[mt_rand(0, $jml_data)]['SU'];
            $centroid[$i][6] = $data[mt_rand(0, $jml_data)]['RBC'];
            $centroid[$i][7] = $data[mt_rand(0, $jml_data)]['PC'];
            $centroid[$i][8] = $data[mt_rand(0, $jml_data)]['PCC'];
            $centroid[$i][9] = $data[mt_rand(0, $jml_data)]['BA'];
            $centroid[$i][10] = $data[mt_rand(0, $jml_data)]['BGR'];
            $centroid[$i][11] = $data[mt_rand(0, $jml_data)]['BU'];
            $centroid[$i][12] = $data[mt_rand(0, $jml_data)]['SC'];
            $centroid[$i][13] = $data[mt_rand(0, $jml_data)]['SOD'];
            $centroid[$i][14] = $data[mt_rand(0, $jml_data)]['HEMO'];
            $centroid[$i][15] = $data[mt_rand(0, $jml_data)]['POT'];
            $centroid[$i][16] = $data[mt_rand(0, $jml_data)]['PCV'];
            $centroid[$i][17] = $data[mt_rand(0, $jml_data)]['WBCC'];
            $centroid[$i][18] = $data[mt_rand(0, $jml_data)]['RBCC'];
            $centroid[$i][19] = $data[mt_rand(0, $jml_data)]['HTN'];
            $centroid[$i][20] = $data[mt_rand(0, $jml_data)]['DM'];
            $centroid[$i][21] = $data[mt_rand(0, $jml_data)]['CAD'];
            $centroid[$i][22] = $data[mt_rand(0, $jml_data)]['APPET'];
            $centroid[$i][23] = $data[mt_rand(0, $jml_data)]['PE'];
            $centroid[$i][24] = $data[mt_rand(0, $jml_data)]['ANE'];            
        }
        $this->data_model->update_centroid('kmeans_centroid_awal',$centroid);
        //mulai rekursif kmeans
        $loop = $this->kmeansloop2($data_alias,$centroid);
        $time = microtime(true) - $start;
        echo 'waktu eksekusi : '.$time.'s';
        echo $loop;
        //echo $loop;        
        
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Proses K-Means Berhasil Dilakukan</div>');
        $data['judul'] = 'Kelola Data Proses Sampling Data';
        $data['data_pasien'] = $this->data_model->get_data('data_pasien_sampling');
        $this->load->view('templates/header', $data);
        $this->load->view('data_sampling_view', $data);
        $this->load->view('templates/footer');
    }
    
    //K-Means bikinan sendiri
    function kmeansloop2($data, $centroid, $loop = 0)
    {
        //Inisialisasi array anggota kluster 
        foreach ($centroid as $key => $value) {
            $anggota_kluster[$key]=[];            
        }
        
        //Nilai K
        $k = 14;
        $kolom = 26;
        $jml_data = count($data) - 1;
        $epsilon = 0.0000001;
        //Inisialisasi total SSE
        $sse = 0;
        
        /*for($p = 0;$p < $k; $p++)
        {
           
        }*/
        
        
        for($j = 0; $j <= $jml_data; $j++) 
        {
            $sum = array();
            for($i = 0; $i < $k; $i++) //Mengulang sebanyak jumlah centroid (K)
            {
                /*for($l = 1; $l < $kolom - 1; $l++) 
                {
                    $hasil_kurang = $data[$j][$l] - $centroid[$i][$l];
                    $hasil_kuadrat = pow($hasil_kurang, 2);
                    $sum[] = $hasil_kuadrat;
                }
                $nilai_sum[$i] = array_sum($sum);
                $jarak[$i] = sqrt($nilai_sum[$i]);*/
                $kol1 = pow(($data[$j][1] - $centroid[$i][1]),2);
                $kol2 = pow(($data[$j][2] - $centroid[$i][2]),2);
                $kol3 = pow(($data[$j][3] - $centroid[$i][3]),2);
                $kol4 = pow(($data[$j][4] - $centroid[$i][4]),2);
                $kol5 = pow(($data[$j][5] - $centroid[$i][5]),2);
                $kol6 = pow(($data[$j][6] - $centroid[$i][6]),2);
                $kol7 = pow(($data[$j][7] - $centroid[$i][7]),2);
                $kol8 = pow(($data[$j][8] - $centroid[$i][8]),2);
                $kol9 = pow(($data[$j][9] - $centroid[$i][9]),2);
                $kol10 = pow(($data[$j][10] - $centroid[$i][10]),2);
                $kol11 = pow(($data[$j][11] - $centroid[$i][11]),2);
                $kol12 = pow(($data[$j][12] - $centroid[$i][12]),2);
                $kol13 = pow(($data[$j][13] - $centroid[$i][13]),2);
                $kol14 = pow(($data[$j][14] - $centroid[$i][14]),2);
                $kol15 = pow(($data[$j][15] - $centroid[$i][15]),2);
                $kol16 = pow(($data[$j][16] - $centroid[$i][16]),2);
                $kol17 = pow(($data[$j][17] - $centroid[$i][17]),2);
                $kol18 = pow(($data[$j][18] - $centroid[$i][18]),2);
                $kol19 = pow(($data[$j][19] - $centroid[$i][19]),2);
                $kol20 = pow(($data[$j][20] - $centroid[$i][20]),2);
                $kol21 = pow(($data[$j][21] - $centroid[$i][21]),2);
                $kol22 = pow(($data[$j][22] - $centroid[$i][22]),2);
                $kol23 = pow(($data[$j][23] - $centroid[$i][23]),2);
                $kol24 = pow(($data[$j][24] - $centroid[$i][24]),2);
                $jarak[$i] = sqrt($kol1 + $kol2 + $kol3 + $kol4 + $kol5 + $kol6 + $kol7 + $kol8 + $kol9 + $kol10 
                                + $kol11 + $kol12 + $kol13 + $kol14 + $kol15 + $kol16 + $kol17 + $kol18 + $kol19 + $kol20
                                + $kol21 + $kol22 + $kol23 + $kol24);
            }
            $min_jarak = min($jarak);            
            $kluster_pilih = array_search($min_jarak, $jarak);
            //echo 'data ke - '.$j.' kluster terpilih : '.$kluster_pilih.'<br>';
            $kluster[$j] = array($data[$j][0], $min_jarak, $kluster_pilih, $data[$j][25]);            
            for ($m = 1; $m < ($kolom - 1); $m++)
            {
                $anggota_kluster[$kluster_pilih][$m][] = $data[$j][$m];
            }
            //$anggota_kluster[$kluster_pilih][0][] = $data[$j][0];           
            $sse += $min_jarak;            
        }
        $diff = 0;
        //Mengecek tiap ceontroid
        for($n = 0; $n < $k; $n++)
        {
            if (empty($anggota_kluster[$n]))
            //Jika Centroid tidak memiliki anggota
            {                
                echo 'empty centroid'.$n.'di loop ke '.$loop.'<br>';
                $new_centroid[$n][0] = $n;
                $new_centroid[$n][1] = $data[mt_rand(0, $jml_data)][1];
                $new_centroid[$n][2] = $data[mt_rand(0, $jml_data)][2];
                $new_centroid[$n][3] = $data[mt_rand(0, $jml_data)][3];
                $new_centroid[$n][4] = $data[mt_rand(0, $jml_data)][4];
                $new_centroid[$n][5] = $data[mt_rand(0, $jml_data)][5];
                $new_centroid[$n][6] = $data[mt_rand(0, $jml_data)][6];
                $new_centroid[$n][7] = $data[mt_rand(0, $jml_data)][7];
                $new_centroid[$n][8] = $data[mt_rand(0, $jml_data)][8];
                $new_centroid[$n][9] = $data[mt_rand(0, $jml_data)][9];
                $new_centroid[$n][10] = $data[mt_rand(0, $jml_data)][10];
                $new_centroid[$n][11] = $data[mt_rand(0, $jml_data)][11];
                $new_centroid[$n][12] = $data[mt_rand(0, $jml_data)][12];
                $new_centroid[$n][13] = $data[mt_rand(0, $jml_data)][13];
                $new_centroid[$n][14] = $data[mt_rand(0, $jml_data)][14];
                $new_centroid[$n][15] = $data[mt_rand(0, $jml_data)][15];
                $new_centroid[$n][16] = $data[mt_rand(0, $jml_data)][16];
                $new_centroid[$n][17] = $data[mt_rand(0, $jml_data)][17];
                $new_centroid[$n][18] = $data[mt_rand(0, $jml_data)][18];
                $new_centroid[$n][19] = $data[mt_rand(0, $jml_data)][19];
                $new_centroid[$n][20] = $data[mt_rand(0, $jml_data)][20];
                $new_centroid[$n][21] = $data[mt_rand(0, $jml_data)][21];
                $new_centroid[$n][22] = $data[mt_rand(0, $jml_data)][22];
                $new_centroid[$n][23] = $data[mt_rand(0, $jml_data)][23];
                $new_centroid[$n][24] = $data[mt_rand(0, $jml_data)][24];                
            }
            else
            {
                //Memeriksa apakah centroid berubah               
                $new_centroid[$n][0] = $n;
                $new_centroid[$n][1] = array_sum($anggota_kluster[$n][1]) / count($anggota_kluster[$n][1]);
                $new_centroid[$n][2] = array_sum($anggota_kluster[$n][2]) / count($anggota_kluster[$n][2]);
                $new_centroid[$n][3] = array_sum($anggota_kluster[$n][3]) / count($anggota_kluster[$n][3]);
                $new_centroid[$n][4] = array_sum($anggota_kluster[$n][4]) / count($anggota_kluster[$n][4]);
                $new_centroid[$n][5] = array_sum($anggota_kluster[$n][5]) / count($anggota_kluster[$n][5]);
                $new_centroid[$n][6] = array_sum($anggota_kluster[$n][6]) / count($anggota_kluster[$n][6]);
                $new_centroid[$n][7] = array_sum($anggota_kluster[$n][7]) / count($anggota_kluster[$n][7]);
                $new_centroid[$n][8] = array_sum($anggota_kluster[$n][8]) / count($anggota_kluster[$n][8]);
                $new_centroid[$n][9] = array_sum($anggota_kluster[$n][9]) / count($anggota_kluster[$n][9]);
                $new_centroid[$n][10] = array_sum($anggota_kluster[$n][10]) / count($anggota_kluster[$n][10]);
                $new_centroid[$n][11] = array_sum($anggota_kluster[$n][11]) / count($anggota_kluster[$n][11]);
                $new_centroid[$n][12] = array_sum($anggota_kluster[$n][12]) / count($anggota_kluster[$n][12]);
                $new_centroid[$n][13] = array_sum($anggota_kluster[$n][13]) / count($anggota_kluster[$n][13]);
                $new_centroid[$n][14] = array_sum($anggota_kluster[$n][14]) / count($anggota_kluster[$n][14]);
                $new_centroid[$n][15] = array_sum($anggota_kluster[$n][15]) / count($anggota_kluster[$n][15]);
                $new_centroid[$n][16] = array_sum($anggota_kluster[$n][16]) / count($anggota_kluster[$n][16]);
                $new_centroid[$n][17] = array_sum($anggota_kluster[$n][17]) / count($anggota_kluster[$n][17]);
                $new_centroid[$n][18] = array_sum($anggota_kluster[$n][18]) / count($anggota_kluster[$n][18]);
                $new_centroid[$n][19] = array_sum($anggota_kluster[$n][19]) / count($anggota_kluster[$n][19]);
                $new_centroid[$n][20] = array_sum($anggota_kluster[$n][20]) / count($anggota_kluster[$n][20]);
                $new_centroid[$n][21] = array_sum($anggota_kluster[$n][21]) / count($anggota_kluster[$n][21]);
                $new_centroid[$n][22] = array_sum($anggota_kluster[$n][22]) / count($anggota_kluster[$n][22]);
                $new_centroid[$n][23] = array_sum($anggota_kluster[$n][23]) / count($anggota_kluster[$n][23]);
                $new_centroid[$n][24] = array_sum($anggota_kluster[$n][24]) / count($anggota_kluster[$n][24]);                
                for ($p = 1; $p < ($kolom - 1); $p++)
                {
                    if (!(abs($new_centroid[$n][$p] - $centroid[$n][$p]) < $epsilon))
                    {
                        $diff++;
                        //echo 'centroid berubah di sentro '.$n.'kolom ke '.$p.'iterasi ke '.$loop.'<br>';
                    }
                }
            }                
        }
        
        //Jika terdapat perubahan centroid, ulang 
        if ($diff > 0)
        {
            $loop++;
            $this->kmeansloop2($data, $new_centroid, $loop);
        }
        else 
        {
            echo 'nilai sse : '.$sse.'<br>';
            echo 'loop '.$loop.'<br>';
            echo 'data : '.$data[0][1].'<br>';
            $this->data_model->update_centroid('kmeans_centroid_akhir', $new_centroid);
            foreach ($kluster as $data_kluster)
            {
                $kluster_pasien = array(
                    'ID_PASIEN' => $data_kluster[0],
                    'JARAK_KE_CENTROID' => $data_kluster[1],
                    'KLUSTER' => $data_kluster[2],
                    'CLASS' => $data_kluster[3]
                );
                $this->data_model->update_kluster($kluster_pasien);
            }
        }
        
    }    
   
    //K-Means Iman
    function kMeansLoop($data, $centroid, $loop=0){
        //k counter loop
        $k=0;
        
        //inisialisasi array anggota kluster
        foreach ($centroid as $key => $value) {
            $anggotaKluster[$key]=[];
            $k++;
        }
        
        // init total sse disini
        $sse=0;
        
        foreach ($data as $keyData => $valueData) {
            
            foreach ($centroid as $keyCentro => $valueCentro) {
                //hitung jarak antara data dengan tiap centroid
                $xVal = pow(($valueCentro['x']-$valueData['berat_normal']),2);
                $yVal=pow(($valueCentro['y']-$valueData['hperkg_normal']),2);
                $jarak[$keyCentro]=sqrt($xVal+$yVal);
            }
            //set data pada centroid terdekat
            $klusterPilih=array_search(min($jarak),$jarak);
            $data[$keyData]['kluster']= $klusterPilih;
            $anggotaKluster[$klusterPilih]['x'][]=$valueData['berat_normal'];
            $anggotaKluster[$klusterPilih]['y'][]=$valueData['hperkg_normal'];
            //kumulatifkan sse disini
            $sse+=min($jarak);
        }
        $diff=0;
        foreach ($centroid as $key => $value) {
            //kondisi jika centroid tidak dapat anggota, maka set centroid baru
            if(empty($anggotaKluster[$key])){
                echo 'empty'.$key.'<br>';
                echo $data[rand((count($data)-1),0)]['berat_normal'];
                echo $data[rand((count($data)-1),0)]['hperkg_normal'];
                $newCentro[$key]['x']=$data[rand((count($data)-1),0)]['berat_normal'];
                $newCentro[$key]['y']=$data[rand((count($data)-1),0)]['hperkg_normal'];
                $diff++;
                
            }else{
                //cek apakah centroid berubah
                $newCentro[$key]['x']=array_sum($anggotaKluster[$key]['x'])/count($anggotaKluster[$key]['x']);
                $newCentro[$key]['y']=array_sum($anggotaKluster[$key]['y'])/count($anggotaKluster[$key]['y']);
                if($newCentro[$key]['x']!=$value['x']||$newCentro[$key]['y']!=$value['y']){
                    $diff++;
                }
            }
        }
        //jika ada perubahan centroid, ulang
        if($diff>0){
            $loop++;
            $this->kMeansLoop($data,$newCentro,$loop);
        }else{
            //jika tidak ada perubahan centroid
            foreach ($data as $key => $value) {
                # code...
                $this->dataM->updateDataKluster($value['id'],$value['kluster']);
                //input sse dan k disini
            }
            //input sse dan k disini
            $this->dataM->updateDaftarSSE($k,$sse);
            $this->dataM->updateCentroid($newCentro);
            echo 'banyaknya perulangan : '.$loop.' kali<br>';
        }
        
    }
    
    function centroid_index()
    {
        $data['judul'] = 'Centroid K-Means Akhir Untuk Undersampling';
        $data['data_pasien'] = $this->data_model->get_data('kmeans_centroid_akhir');
        $this->load->view('templates/header', $data);
        $this->load->view('centroid_view', $data);
        $this->load->view('templates/footer');
    }
    
    function centroid_awal_index()
    {
        $data['judul'] = 'Centroid K-Means Awal Untuk Undersampling';
        $data['data_pasien'] = $this->data_model->get_data('kmeans_centroid_awal');
        $this->load->view('templates/header', $data);
        $this->load->view('centroid_awal_view', $data);
        $this->load->view('templates/footer');
    }
    
    function kluster_index()
    {
        $data['judul'] = 'Daftar Kluste K-Means';
        $data['data_pasien'] = $this->data_model->get_data('kmeans_kluster');
        $this->load->view('templates/header', $data);
        $this->load->view('kluster_view', $data);
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
        redirect('Sampling_controller/index');
    }
    
    //Fungsi berikut hanya untuk percobaan dalam membuat laporan
    function kmeans_test()
    {
        $centroid = $this->data_model->get_data_alias_centroid('kmeans_centroid_awal')->result_array();
        $data = $this->data_model->get_data_alias('data_pasien_knn')->result_array();
        
        //Inisialisasi array anggota kluster
        foreach ($centroid as $key => $value) {
            $anggota_kluster[$key]=[];
        }
        
        //Nilai K
        $k = 14;
        $kolom = 26;
        $jml_data = count($data) - 1;
        
        //Inisialisasi tabel jumlah anggota per kluster
        $tabel_jml_angg = array();
        for ($a = 0; $a < $k; $a++)
        {
            $tabel_jml_angg[$a][0] = $a;
            $tabel_jml_angg[$a][1] = 0;
        }
        
        for($j = 0; $j <= $jml_data; $j++)
        {
            $sum = array();
            for($i = 0; $i < $k; $i++) //Mengulang sebanyak jumlah centroid (K)
            {
                $kol1 = pow(($data[$j][1] - $centroid[$i][1]),2);
                $kol2 = pow(($data[$j][2] - $centroid[$i][2]),2);
                $kol3 = pow(($data[$j][3] - $centroid[$i][3]),2);
                $kol4 = pow(($data[$j][4] - $centroid[$i][4]),2);
                $kol5 = pow(($data[$j][5] - $centroid[$i][5]),2);
                $kol6 = pow(($data[$j][6] - $centroid[$i][6]),2);
                $kol7 = pow(($data[$j][7] - $centroid[$i][7]),2);
                $kol8 = pow(($data[$j][8] - $centroid[$i][8]),2);
                $kol9 = pow(($data[$j][9] - $centroid[$i][9]),2);
                $kol10 = pow(($data[$j][10] - $centroid[$i][10]),2);
                $kol11 = pow(($data[$j][11] - $centroid[$i][11]),2);
                $kol12 = pow(($data[$j][12] - $centroid[$i][12]),2);
                $kol13 = pow(($data[$j][13] - $centroid[$i][13]),2);
                $kol14 = pow(($data[$j][14] - $centroid[$i][14]),2);
                $kol15 = pow(($data[$j][15] - $centroid[$i][15]),2);
                $kol16 = pow(($data[$j][16] - $centroid[$i][16]),2);
                $kol17 = pow(($data[$j][17] - $centroid[$i][17]),2);
                $kol18 = pow(($data[$j][18] - $centroid[$i][18]),2);
                $kol19 = pow(($data[$j][19] - $centroid[$i][19]),2);
                $kol20 = pow(($data[$j][20] - $centroid[$i][20]),2);
                $kol21 = pow(($data[$j][21] - $centroid[$i][21]),2);
                $kol22 = pow(($data[$j][22] - $centroid[$i][22]),2);
                $kol23 = pow(($data[$j][23] - $centroid[$i][23]),2);
                $kol24 = pow(($data[$j][24] - $centroid[$i][24]),2);
                $jarak[$i] = sqrt($kol1 + $kol2 + $kol3 + $kol4 + $kol5 + $kol6 + $kol7 + $kol8 + $kol9 + $kol10
                    + $kol11 + $kol12 + $kol13 + $kol14 + $kol15 + $kol16 + $kol17 + $kol18 + $kol19 + $kol20
                    + $kol21 + $kol22 + $kol23 + $kol24);
            }
            $min_jarak = min($jarak);
            $kluster_pilih = array_search($min_jarak, $jarak);
            $tabel_jml_angg[$kluster_pilih][1] = $tabel_jml_angg[$kluster_pilih][1] + 1;
            //echo 'data ke - '.$j.' kluster terpilih : '.$kluster_pilih.'<br>';
            $kluster[$j] = array($data[$j][0], $min_jarak, $kluster_pilih, $data[$j][25]);
            for ($m = 1; $m < ($kolom - 1); $m++)
            {
                $anggota_kluster[$kluster_pilih][$m][] = $data[$j][$m];
            }
            //$anggota_kluster[$kluster_pilih][0][] = $data[$j][0];
        }
        
        //Membuat centroid baru
        $n = 6;
        $new_centroid[$n][0] = $n;
        $new_centroid[$n][1] = array_sum($anggota_kluster[$n][1]) / count($anggota_kluster[$n][1]);
        $new_centroid[$n][2] = array_sum($anggota_kluster[$n][2]) / count($anggota_kluster[$n][2]);
        $new_centroid[$n][3] = array_sum($anggota_kluster[$n][3]) / count($anggota_kluster[$n][3]);
        $new_centroid[$n][4] = array_sum($anggota_kluster[$n][4]) / count($anggota_kluster[$n][4]);
        $new_centroid[$n][5] = array_sum($anggota_kluster[$n][5]) / count($anggota_kluster[$n][5]);
        $new_centroid[$n][6] = array_sum($anggota_kluster[$n][6]) / count($anggota_kluster[$n][6]);
        $new_centroid[$n][7] = array_sum($anggota_kluster[$n][7]) / count($anggota_kluster[$n][7]);
        $new_centroid[$n][8] = array_sum($anggota_kluster[$n][8]) / count($anggota_kluster[$n][8]);
        $new_centroid[$n][9] = array_sum($anggota_kluster[$n][9]) / count($anggota_kluster[$n][9]);
        $new_centroid[$n][10] = array_sum($anggota_kluster[$n][10]) / count($anggota_kluster[$n][10]);
        $new_centroid[$n][11] = array_sum($anggota_kluster[$n][11]) / count($anggota_kluster[$n][11]);
        $new_centroid[$n][12] = array_sum($anggota_kluster[$n][12]) / count($anggota_kluster[$n][12]);
        $new_centroid[$n][13] = array_sum($anggota_kluster[$n][13]) / count($anggota_kluster[$n][13]);
        $new_centroid[$n][14] = array_sum($anggota_kluster[$n][14]) / count($anggota_kluster[$n][14]);
        $new_centroid[$n][15] = array_sum($anggota_kluster[$n][15]) / count($anggota_kluster[$n][15]);
        $new_centroid[$n][16] = array_sum($anggota_kluster[$n][16]) / count($anggota_kluster[$n][16]);
        $new_centroid[$n][17] = array_sum($anggota_kluster[$n][17]) / count($anggota_kluster[$n][17]);
        $new_centroid[$n][18] = array_sum($anggota_kluster[$n][18]) / count($anggota_kluster[$n][18]);
        $new_centroid[$n][19] = array_sum($anggota_kluster[$n][19]) / count($anggota_kluster[$n][19]);
        $new_centroid[$n][20] = array_sum($anggota_kluster[$n][20]) / count($anggota_kluster[$n][20]);
        $new_centroid[$n][21] = array_sum($anggota_kluster[$n][21]) / count($anggota_kluster[$n][21]);
        $new_centroid[$n][22] = array_sum($anggota_kluster[$n][22]) / count($anggota_kluster[$n][22]);
        $new_centroid[$n][23] = array_sum($anggota_kluster[$n][23]) / count($anggota_kluster[$n][23]);
        $new_centroid[$n][24] = array_sum($anggota_kluster[$n][24]) / count($anggota_kluster[$n][24]);
        
        //Cek jumlah mayoritas dan minoritas per kluster
        
        //Inisialisasi tabel deskripsi kluster
        for($c = 0; $c < 14; $c++)
        {
            $deskripsi_kluster[$c][0] = $c; //ID Kluster
            $deskripsi_kluster[$c][1] = 0; //Jumlah mayoritas di kluster ke-i
            $deskripsi_kluster[$c][2] = 0; //Jumlah minoritas di kluster ke-i
        }
        $data_kluster = $this->data_model->get_data('kmeans_kluster');
        $data_alias = $this->data_model->get_data_alias('data_pasien_knn')->result_array();
        $jumlah_data = count($data_kluster);
        
        for($b = 0; $b < $jumlah_data; $b++)
        {
            $id_pasien = $data_kluster[$b]['ID_PASIEN'];
            $key = array_search($id_pasien, array_column($data_alias, 0));
            $kluster = $data_kluster[$b]['KLUSTER'];
            if ($data_kluster[$b]['CLASS'] == 1)
            {
                //Menambah jumlah mayoritas
                $deskripsi_kluster[$kluster][1] += 1;
                $mayoritas_per_kluster[$kluster][] = $data_alias[$key][0];
            }
            else
            {
                //Menambah jumlah minoritas
                $deskripsi_kluster[$kluster][2] += 1;
                $tabel_minoritas[] = $data_alias[$key];
            }
        }
        
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert"> Hasil Euclidean baris satu adalah = </div>');
        $data['judul'] = 'Tes perhitungan sampling';
        $data['data_pasien'] = $deskripsi_kluster;
        $this->load->view('templates/header', $data);
        $this->load->view('data_sampling_view', $data);
        $this->load->view('templates/footer');
    }
}