<?php
class Test_csv extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('test_csv_model');
        $this->load->library('csvimport');
    }
    
    function index() {
        $data['data_csv'] = $this->test_csv_model->get_data();
        $this->load->view('csvindex', $data);
    }
    
    function importcsv() {
        $data['data_csv'] = $this->test_csv_model->get_data();
        $data['error'] = '';    //initialize image upload error array to empty
        
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
        
        $this->load->library('upload', $config);
        
        
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            
            $this->load->view('csvindex', $data);
        } else 
        {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            $filename = $file_data['file_name'];
            
            //Reading file
            if(!fopen($file_path, 'r'))
            {
                //If reading file
                $data['error'] = "Error occured";
                $this->load->view('csvindex', $data);
            }else 
            {
                $file = fopen($file_path, 'r');
                $i = 0;
                
                $importdata_arr = array();
                
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE)
                {
                    $num = count($filedata);
                    
                    for ($c=0; $c < $num; $c++) 
                    {
                        $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($file);
                
                $skip = 0;
                
                //Insert Import data
                foreach($importData_arr as $data_pasien)
                {
                    if($skip != 0){
                        $pasien_baru = array(
                            'ID' => $data_pasien[0],
                            'NAMA' => $data_pasien[1],
                            'DEPARTEMEN' => $data_pasien[2],
                            'ASAL' => $data_pasien[3],
                            'UMUR' => $data_pasien[4],
                            'HOBI'=> $data_pasien[5]
                        );
                        $this->test_csv_model->insert_record($pasien_baru);
                    }
                    $skip ++;
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'test_csv');
            }
           
            
            /*
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                        'ID'=>$row['ID'],
                        'NAMA'=>$row['NAMA'],
                        'DEPARTEMEN' => $row['DEPARTEMEN'],
                        'ASAL'=>$row['ASAL'],
                        'UMUR'=>$row['UMUR'],
                        'HOBI'=>$row['HOBI']
                    );
                    $this->test_csv_model->insert_csv($insert_data);
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'test_csv');
                //echo "<pre>"; print_r($insert_data);
            } else {
                $data['error'] = "Error occured";
                $this->load->view('csvindex', $data);
            }
            */
        }
        
    }
    
}
/*END OF FILE*/