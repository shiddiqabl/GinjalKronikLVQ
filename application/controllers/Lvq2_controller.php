<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Lvq2_controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('data_model');
    }
    
    function index()
    {
        $data['judul'] = 'Klasifikasi Menggunakan Learning Vector Quantization 2';
        $data['data_pengujian'] = $this->data_model->get_data('hasil_pengujian_avg');
        $this->load->view('templates/header', $data);
        $this->load->view('data_lvq2_view', $data);
        $this->load->view('templates/footer');
    }
    
    function create_fold()
    {
        $k = 10;
        $data_pasien = $this->data_model->get_data('data_pasien_sampling');
        $jml_data = count(array_column($data_pasien, 'ID'));
        $jml_per_fold = ($jml_data / $k) / 2;
        $counter_0 = 0;
        $counter_1 = 150;
        //Inisialisasi array fold
        $fold = array();
        
        //Membagi data ke dalam 10 partisi
        for ($i = 0; $i < $k; $i++)
        {
            for ($j = 0; $j < $jml_per_fold; $j++)
            {
                $fold[$i][] = $data_pasien[$counter_0 + $j];
                $fold[$i][] = $data_pasien[$counter_1 + $j];
            }
            $counter_0 += $jml_per_fold;
            $counter_1 += $jml_per_fold;
        }
        
        //Membagi partisi berdasarkan iterasi
        $iterasi_k = array();
        for ($m = 0; $m < $k; $m++)
        {
            $temp_array = array();
            for ($n = 0; $n < $k; $n++)
            {
                if ($n != $m)
                {
                    $temp_array[] = $fold[$n];
                }
                else 
                {
                    //Membuat data pengujian fold ke K
                    $iterasi_k[$m]['pengujian'] = $fold[$n];
                }
            }
            //Membuat data pelatihan fold ke-K
            $iterasi_k[$m]['pelatihan'] = array_merge($temp_array[0], $temp_array[1], $temp_array[2], $temp_array[3], 
                $temp_array[4], $temp_array[5], $temp_array[6], $temp_array[7], $temp_array[8]);
        }
        
        //echo 'jumlah per fold :'.count($fold[1]).'<br>';
        //echo 'jumlah data :'.$jml_data;
        
        return $iterasi_k;
        
        /*$data['judul'] = 'Membuat Fold';
        $data['data_pasien'] = $iterasi_k[0]['pelatihan'];
        $this->load->view('templates/header', $data);
        $this->load->view('data_lvq2_view', $data);
        $this->load->view('templates/footer');*/
    }
    
    function input_lvq()
    {
        $data['judul'] = 'Masukkan parameter uji';        
        $this->load->view('templates/header', $data);
        $this->load->view('input_lvq_view');
        $this->load->view('templates/footer');
    }
    
    function lvq2()
    {
        $start = microtime(true);
        //Bagi data menjadi 10 fold
        $fold = $this->create_fold();
        
        //Ambil parameter dari form
        $alpha = $this->input->post('ALPHA');
        $epsilon = $this->input->post('EPSILON');
        $max_epoch = $this->input->post('MAX_EPOCH');
        
        //Melakukan pelatihan dan pengujian sebanyak fold
        for ($i = 0; $i < 10; $i++)
        {
            //Masukan id fold, alpha awal, epsilon, epoch maksimal, data latih dan alpha
            echo 'Proses Fold ke-'.$i.'<br>';
            $loop = $this->pelatihan_lvq($i, $alpha, $epsilon, $max_epoch, $fold[$i], $alpha);
            echo $loop;
        }      
        
        $time = microtime(true)-$start;        
        echo 'waktu eksekusi : '.$time.'s';
        
       //Menghitung hasil rata-rata pengujian
       $hasil_pengujian_avg = $this->pengujian_avg($alpha, $epsilon, $max_epoch,$time);
        
        $data['judul'] = 'Hasil Pengujian LVQ2';
        $data['hasil_uji_avg'] = $hasil_pengujian_avg;
        $data['hasil_uji_fold'] = $this->data_model->get_data('hasil_pengujian_fold');
        //$data['row'] = $loop;
        $this->load->view('templates/header', $data);
        $this->load->view('lvq2_result', $data);
        $this->load->view('templates/footer');        
    }
    
    function pelatihan_lvq($id_fold, $alpha_awal, $epsilon, $max_epoch, $fold, $alpha, $w1 = null, $w2 = null, $epoch = 0)
    {
        //$jml_fold_uji = count(array_column($fold[0]['pengujian'], 'ID'));
        $jml_fold_latih = count(array_column($fold['pelatihan'], 'ID'));
        $key_1 = null;
        $key_2 = null;
        //$alpha = 0.1;
        $window = 0.3;
        //echo 'pelatihan epoch ke'.$epoch.' <br>';
        //Periksa apabila variabel w1 dan w2 null
        if ((is_null($w1) AND is_null($w2)) == TRUE)
        {            
            //Isi variabel W1 dengan data dengan penyakit ginjal (CLASS = 1)
            $key_1 = array_search(1, array_column($fold['pelatihan'], 'CLASS'));
            $w1 = $fold['pelatihan'][$key_1];
            //echo 'W1 ID ke '.$w1['ID'].'<br>';
            //Isi variabel W2 dengan data dengan tanpa penyakit ginjal (CLASS = 0)
            $key_2 = array_search(0, array_column($fold['pelatihan'], 'CLASS'));
            $w2 = $fold['pelatihan'][$key_2];
            //echo 'W2 ID ke '.$w2['ID'].'<br>';
        }        
        
        for ($i = 0; $i < $jml_fold_latih; $i++)
        {            
            //echo ' i ke - '.$i.' happened <br>';
            //echo 'W1 AGE = '.$w1['BP'].'<br>';
            //echo 'W2 AGE = '.$w2['BP'].'<br>';
            $w_champ = array();
            $w_runn = array();           
            //Data yang dijadikan W1 dan W2 pertama tidak digunakan lagi            
            if (($i === $key_1) || ($i === $key_2))
            {
                //echo 'continue happened<br>';
                continue;                
            }
            else 
            {
                //Menghitung jarak euclidean W1 dan W2 dengan data latih
                $jarak_w1 = $this->euclidean_dist($w1, $fold['pelatihan'][$i]);
                //echo 'jarak W1 dengan data latih ke-'.$i.'adalah :'.$jarak_w1.'<br>';
                $jarak_w2 = $this->euclidean_dist($w2, $fold['pelatihan'][$i]);
                //echo 'jarak W2 dengan data latih ke-'.$i.'adalah :'.$jarak_w2.'<br>';
                if ($jarak_w1 < $jarak_w2)
                {
                    //echo 'W1 Pemenang <br>';
                    //Jika W1 menjadi W pemenang
                    $champ = 1;
                    $w_champ = $w1;
                    $w_runn = $w2;
                    //Tentukan jarak data latih ke pemenang dan runner-up
                    $d_champ = $jarak_w1;
                    $d_runn = $jarak_w2;
                }
                else
                {
                    //echo 'W2 Pemenang <br>';
                    //Jika W2 menjadi pemenang
                    $champ = 2;
                    $w_champ = $w2;
                    $w_runn = $w1;
                    //Tentukan jarak data latih ke pemenang dan runner-up
                    $d_champ = $jarak_w2;
                    $d_runn = $jarak_w1;
                }
                
                //Memeriksa CLASS W Pemenang dan CLASS data latih
                if ($w_champ['CLASS'] == $fold['pelatihan'][$i]['CLASS'])
                {
                    //Jika kelas sama, maka W champ update tambah
                    //echo 'Kelas data latih dan W sama <br>';
                    $w_champ = $this->update_w_tambah($alpha, $fold['pelatihan'][$i], $w_champ);                    
                }
                //Jika tidak, cek kondisi WINDOW
                elseif ($this->cek_window($window, $d_champ, $d_runn) == TRUE) 
                {
                    //Jika kondisi WINDOW terpenuhi
                    //echo 'Window terpenuhi <br>';
                    /*$dc_dr = $d_champ / $d_runn;
                    $dr_dc = $d_runn / $d_champ;
                    $window_min = 1 - $window;
                    $window_plus = 1 + $window;
                    echo 'dc / dr = '.$dc_dr.'> 1 - window = '.$window_min.'<br>';
                    echo ' dr / dc ='.$dr_dc.'< 1 + window = '.$window_plus.'<br>';*/
                    $w_champ = $this->update_w_kurang($alpha, $fold['pelatihan'][$i], $w_champ);
                    $w_runn = $this->update_w_tambah($alpha, $fold['pelatihan'][$i], $w_runn);
                }
                else 
                {
                    //Jika kondisi WINDOW tidak terpenuhi
                    //echo 'Window tak terpenuhi <br>';                    
                    $w_champ = $this->update_w_kurang($alpha, $fold['pelatihan'][$i], $w_champ);
                }
            }
            
            //Memperbarui nilai W1 dan W2
            if ($champ == 1)
            {
                //Jika W pemenang adalah W1
                $w1 = $w_champ;
                $w2 = $w_runn;
            }
            else 
            {
                //Jika W pemenang adalah W2
                $w1 = $w_runn;
                $w2 = $w_champ;
            }
        }
        
        //Cek apakah pelatihan memenuhi syarat berhenti
        if (($epoch >= $max_epoch) || ($alpha < $epsilon))
        {
            //Jika kondisi berhenti terpenuhi
            //echo 'Proses pelatihan berhenti <br>';
            //Masukkan detail pelatihan
            $detail['alpha_awal'] = $alpha_awal;
            $detail['alpha_akhir'] = $alpha;
            $detail['epoch_max'] = $max_epoch;
            $detail['epoch_akhir'] = $epoch;
            $detail['epsilon'] = $epsilon;
            //Mulai pengujian LVQ
            $this->pengujian_lvq($id_fold, $w1, $w2, $fold, $detail);
        }
        else 
        {
            //Jika kondisi tak terpenuhi, ulang perhitungan
            $epoch++;
            $new_alpha = $alpha - (0.1 * $alpha);
            $this->pelatihan_lvq($id_fold, $alpha_awal, $epsilon, $max_epoch, $fold, $new_alpha, $w1, $w2, $epoch);
        }
    }
    
    function pengujian_lvq($id_fold, $w1, $w2, $fold, $detail)
    {
        $jml_fold_uji = count(array_column($fold['pengujian'], 'ID'));
        $true_neg = 0;
        $false_neg = 0;
        $true_pos = 0;
        $false_pos = 0;
        
        for ($i = 0; $i < $jml_fold_uji; $i++)
        {
            $jarak_w1 = $this->euclidean_dist($w1, $fold['pengujian'][$i]);
            $jarak_w2 = $this->euclidean_dist($w2, $fold['pengujian'][$i]);
            
            if ($jarak_w1 < $jarak_w2)
            {
                //Jika pemenang W1 dan kelas uji = 1
                if ($w1['CLASS'] == $fold['pengujian'][$i]['CLASS'])
                {
                    //echo 'Pemenang W1 dan kelas uji = 1, true_pos++<br>';
                    $true_pos++;
                }
                else
                //Jika pemenang W1 dan kelas uji = 0
                {
                    //echo 'Pemenang W1 tapi kelas uji = 0, false_pos++ <br>';
                    $false_pos++;
                }
            }
            else
            {
                if ($w2['CLASS'] == $fold['pengujian'][$i]['CLASS'])
                {
                    //echo 'pemenang W2 dan kelas uji = 0, true_neg++ <br>';
                    $true_neg++;
                }
                else
                {
                    //echo 'pemenang W2 tapi kelas uji = 1, false_neg++ <br>';
                    $false_neg++;
                }
            }
        }
        
        //Menghitung akurasi, error, sensitifitas dan spesifisitas
        $akurasi = ($true_pos + $true_neg) / $jml_fold_uji;
        $error = ($false_pos + $false_neg) / $jml_fold_uji;
        $sensitifitas = $true_pos / ($true_pos + $false_neg);
        $spesifisitas = $true_neg / ($true_neg + $false_pos);
        
        //echo 'True Positive = '.$true_pos.' dan False Positive = '.$false_pos.'<br>';
        //echo 'True Negative = '.$true_neg.' dan False Negative = '.$false_neg.'<br>';
        //echo 'Akurasi = '.$akurasi.' dan Error Rate = '.$error.'<br>';
        //echo 'Sensitifitas = '.$sensitifitas.' dan Spesifisitas = '.$spesifisitas.'<br>';
        //echo 'Alpha awal = '.$detail['alpha_awal'].' Alpha akhir '.$detail['alpha_akhir'].'<br>';
        
        //Update hasil pengujian fold
        $hasil_uji['true_pos'] = $true_pos;
        $hasil_uji['false_pos'] = $false_pos;
        $hasil_uji['true_neg'] = $true_neg;
        $hasil_uji['false_neg'] = $false_neg;
        $hasil_uji['akurasi'] = $akurasi;
        $hasil_uji['error'] = $error;
        $hasil_uji['sensitifitas'] = $sensitifitas;
        $hasil_uji['spesifisitas'] = $spesifisitas;
        $this->data_model->update_hsl_uji($id_fold, $detail, $w1, $w2, $hasil_uji);
    }
    
    function pengujian_avg($alpha, $epsilon, $max_epoch, $runtime)
    {
        //echo 'Pengujian AVG dimulai <br>';
        //Ambil data hasil uji tiap fold
        $hasil_uji_fold = $this->data_model->get_data('hasil_pengujian_fold');
        $jml_uji_fold = count(array_column($hasil_uji_fold, 'ID_FOLD'));
        //echo 'Jumlah hasil uji fold = '.$jml_uji_fold.'<br>';
        
        //Hitung data uji dan hasil uji rata-rata
        $hasil_uji['akurasi_avg'] = array_sum(array_column($hasil_uji_fold, 'AKURASI')) / $jml_uji_fold;
        $hasil_uji['error_avg'] = array_sum(array_column($hasil_uji_fold, 'ERROR')) / $jml_uji_fold;
        $hasil_uji['sensitifitas_avg'] =  array_sum(array_column($hasil_uji_fold, 'SENSITIFITAS')) / $jml_uji_fold;
        $hasil_uji['spesifisitas_avg'] =  array_sum(array_column($hasil_uji_fold, 'SPESIFISITAS')) / $jml_uji_fold;
        $hasil_uji['alpha_awal'] = $alpha;
        $hasil_uji['epsilon'] = $epsilon;
        $hasil_uji['max_epoch'] = $max_epoch;
        
        
        //echo 'Rata-rata Akurasi = '.$hasil_uji['akurasi_avg'].' Rata-rata Error = '.$hasil_uji['error_avg'].'<br>';
        //echo 'Rata-rata sensitifitas = '.$hasil_uji['sensitifitas_avg'].' Rata-rata spesifisitas = '.$hasil_uji['spesifisitas_avg'].'<br>';
        //echo 'Waktu pengerjaan'.$runtime.'<br>';
        $hasil_uji['runtime'] = $runtime;
        
        //Memasukkan data ke database
        $hasil_uji_avg = array(
            'ALPHA_AWAL' => $alpha,
            'EPSILON' => $epsilon,
            'EPOCH_MAX' => $max_epoch,
            'AKURASI' => $hasil_uji['akurasi_avg'],
            'ERROR' => $hasil_uji['error_avg'],
            'SENSITIFITAS' => $hasil_uji['sensitifitas_avg'],
            'SPESIFISITAS' => $hasil_uji['spesifisitas_avg'],
            'RUNTIME' => $runtime
        );
        $this->data_model->insert_data('hasil_pengujian_avg',$hasil_uji_avg);
        
        return $hasil_uji;
    }
    
    function euclidean_dist($x1, $x2)
    {
        //Mengurangi masing-masing kolom kemudian mengkuadratkannya
        $kol1 = pow(($x1['AGE'] - $x2['AGE']), 2);
        $kol2 = pow(($x1['BP'] - $x2['BP']), 2);
        $kol3 = pow(($x1['SG'] - $x2['SG']), 2);
        $kol4 = pow(($x1['AL'] - $x2['AL']), 2);
        $kol5 = pow(($x1['SU'] - $x2['SU']), 2);
        $kol6 = pow(($x1['RBC'] - $x2['RBC']), 2);
        $kol7 = pow(($x1['PC'] - $x2['PC']), 2);
        $kol8 = pow(($x1['PCC'] - $x2['PCC']), 2);
        $kol9 = pow(($x1['BA'] - $x2['BA']), 2);
        $kol10 = pow(($x1['BGR'] - $x2['BGR']), 2);
        $kol11 = pow(($x1['BU'] - $x2['BU']), 2);
        $kol12 = pow(($x1['SC'] - $x2['SC']), 2);
        $kol13 = pow(($x1['SOD'] - $x2['SOD']), 2);
        $kol14 = pow(($x1['POT'] - $x2['POT']), 2);
        $kol15 = pow(($x1['HEMO'] - $x2['HEMO']), 2);
        $kol16 = pow(($x1['PCV'] - $x2['PCV']), 2);
        $kol17 = pow(($x1['WBCC'] - $x2['WBCC']), 2);
        $kol18 = pow(($x1['RBCC'] - $x2['RBCC']), 2);
        $kol19 = pow(($x1['HTN'] - $x2['HTN']), 2);
        $kol20 = pow(($x1['DM'] - $x2['DM']), 2);
        $kol21 = pow(($x1['CAD'] - $x2['CAD']), 2);
        $kol22 = pow(($x1['APPET'] - $x2['APPET']), 2);
        $kol23 = pow(($x1['PE'] - $x2['PE']), 2);
        $kol24 = pow(($x1['ANE'] - $x2['ANE']), 2);
        //Menjumlahkan semua kolom kemudian mengakarnya
        $jarak = sqrt($kol1 + $kol2 + $kol3 + $kol4 + $kol5 + $kol6 + $kol7 + $kol8 + $kol9 + $kol10
            + $kol11 + $kol12 + $kol13 + $kol14 + $kol15 + $kol16 + $kol17 + $kol18 + $kol19 + $kol20
            + $kol21 + $kol22 + $kol23 + $kol24);
        return $jarak;
    }
    
    function update_w_tambah($alpha, $data_latih, $w_bobot)
    {
        $temp_kurang_array = $this->kurang_array($data_latih, $w_bobot);
        $temp_kali_array = $this->kali_array($alpha, $temp_kurang_array);
        $w_baru = $this->tambah_array($w_bobot, $temp_kali_array);
        return $w_baru;
    }
    
    function update_w_kurang($alpha, $data_latih, $w_bobot)
    {
        $temp_kurang_array = $this->kurang_array($data_latih, $w_bobot);
        $temp_kali_array = $this->kali_array($alpha, $temp_kurang_array);
        $w_baru = $this->kurang_array($w_bobot, $temp_kali_array);
        $w_baru['ID'] = $w_bobot['ID'];
        return $w_baru;
    }
    
    function cek_window($window, $d_champ, $d_runn)
    {
        $dc_dr = $d_champ / $d_runn;
        $dr_dc = $d_runn / $d_champ;
        $window_min = 1 - $window;
        $window_plus = 1 + $window;
        if (($dc_dr > $window_min) && ($dr_dc < $window_plus))
        {
            return TRUE;
        }
        else 
        {
            return FALSE;
        }
    }
    
    function kurang_array($data_latih, $w_bobot)
    {
        $hasil['ID'] = $w_bobot['ID'];
        $hasil['AGE'] = $data_latih['AGE'] - $w_bobot['AGE'];
        $hasil['BP'] = $data_latih['BP'] - $w_bobot['BP'];
        $hasil['SG'] = $data_latih['SG'] - $w_bobot['SG'];
        $hasil['AL'] = $data_latih['AL'] - $w_bobot['AL'];
        $hasil['SU'] = $data_latih['SU'] - $w_bobot['SU'];
        $hasil['RBC'] = $data_latih['RBC'] - $w_bobot['RBC'];
        $hasil['PC'] = $data_latih['PC'] - $w_bobot['PC'];
        $hasil['PCC'] = $data_latih['PCC'] - $w_bobot['PCC'];
        $hasil['BA'] = $data_latih['BA'] - $w_bobot['BA'];
        $hasil['BGR'] = $data_latih['BGR'] - $w_bobot['BGR'];
        $hasil['BU'] = $data_latih['BU'] - $w_bobot['BU'];
        $hasil['SC'] = $data_latih['SC'] - $w_bobot['SC'];
        $hasil['SOD'] = $data_latih['SOD'] - $w_bobot['SOD'];
        $hasil['POT'] = $data_latih['POT'] - $w_bobot['POT'];
        $hasil['HEMO'] = $data_latih['HEMO'] - $w_bobot['HEMO'];
        $hasil['PCV'] = $data_latih['PCV'] - $w_bobot['PCV'];
        $hasil['WBCC'] = $data_latih['WBCC'] - $w_bobot['WBCC'];
        $hasil['RBCC'] = $data_latih['RBCC'] - $w_bobot['RBCC'];
        $hasil['HTN'] = $data_latih['HTN'] - $w_bobot['HTN'];
        $hasil['DM'] = $data_latih['DM'] - $w_bobot['DM'];
        $hasil['CAD'] = $data_latih['CAD'] - $w_bobot['CAD'];
        $hasil['APPET'] = $data_latih['APPET'] - $w_bobot['APPET'];
        $hasil['PE'] = $data_latih['PE'] - $w_bobot['PE'];
        $hasil['ANE'] = $data_latih['ANE'] - $w_bobot['ANE'];
        $hasil['CLASS'] = $w_bobot['CLASS'];
        return $hasil;
    }
    
    function kali_array($nominal, $array)
    {
        $hasil['ID'] = $array['ID'];
        $hasil['AGE'] = $nominal * $array['AGE'];
        $hasil['BP'] = $nominal * $array['BP'];
        $hasil['SG'] = $nominal * $array['SG'];
        $hasil['AL'] = $nominal * $array['AL'];
        $hasil['SU'] = $nominal * $array['SU'];
        $hasil['RBC'] = $nominal * $array['RBC'];
        $hasil['PC'] = $nominal * $array['PC'];
        $hasil['PCC'] = $nominal * $array['PCC'];
        $hasil['BA'] = $nominal * $array['BA'];
        $hasil['BGR'] = $nominal * $array['BGR'];
        $hasil['BU'] = $nominal * $array['BU'];
        $hasil['SC'] = $nominal * $array['SC'];
        $hasil['SOD'] = $nominal * $array['SOD'];
        $hasil['POT'] = $nominal * $array['POT'];
        $hasil['HEMO'] = $nominal * $array['HEMO'];
        $hasil['PCV'] = $nominal * $array['PCV'];
        $hasil['WBCC'] = $nominal * $array['WBCC'];
        $hasil['RBCC'] = $nominal * $array['RBCC'];
        $hasil['HTN'] = $nominal * $array['HTN'];
        $hasil['DM'] = $nominal * $array['DM'];
        $hasil['CAD'] = $nominal * $array['CAD'];
        $hasil['APPET'] = $nominal * $array['APPET'];
        $hasil['PE'] = $nominal * $array['PE'];
        $hasil['ANE'] = $nominal * $array['ANE'];
        $hasil['CLASS'] = $array['CLASS'];
        return $hasil;        
    }
    
    function tambah_array($array1, $array2)
    {
        $hasil['ID'] = $array1['ID'];
        $hasil['AGE'] = $array1['AGE'] + $array2['AGE'];
        $hasil['BP'] = $array1['BP'] + $array2['BP'];
        $hasil['SG'] = $array1['SG'] + $array2['SG'];
        $hasil['AL'] = $array1['AL'] + $array2['AL'];
        $hasil['SU'] = $array1['SU'] + $array2['SU'];
        $hasil['RBC'] = $array1['RBC'] + $array2['RBC'];
        $hasil['PC'] = $array1['PC'] + $array2['PC'];
        $hasil['PCC'] = $array1['PCC'] + $array2['PCC'];
        $hasil['BA'] = $array1['BA'] + $array2['BA'];
        $hasil['BGR'] = $array1['BGR'] + $array2['BGR'];
        $hasil['BU'] = $array1['BU'] + $array2['BU'];
        $hasil['SC'] = $array1['SC'] + $array2['SC'];
        $hasil['SOD'] = $array1['SOD'] + $array2['SOD'];
        $hasil['POT'] = $array1['POT'] + $array2['POT'];
        $hasil['HEMO'] = $array1['HEMO'] + $array2['HEMO'];
        $hasil['PCV'] = $array1['PCV'] + $array2['PCV'];
        $hasil['WBCC'] = $array1['WBCC'] + $array2['WBCC'];
        $hasil['RBCC'] = $array1['RBCC'] + $array2['RBCC'];
        $hasil['HTN'] = $array1['HTN'] + $array2['HTN'];
        $hasil['DM'] = $array1['DM'] + $array2['DM'];
        $hasil['CAD'] = $array1['CAD'] + $array2['CAD'];
        $hasil['APPET'] = $array1['APPET'] + $array2['APPET'];
        $hasil['PE'] = $array1['PE'] + $array2['PE'];
        $hasil['ANE'] = $array1['ANE'] + $array2['ANE'];
        $hasil['CLASS'] = $array1['CLASS'];
        return $hasil;
    }
    
    function hapus_data($table)
    {
        $this->data_model->delete_data($table);
        $this->session->set_flashdata('message','<div class="alert alert-success" role="alert">Data berhasil dihapus</div>');
        redirect('Lvq2_controller/index');
    }
    
}