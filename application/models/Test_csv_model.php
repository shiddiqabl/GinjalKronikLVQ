<?php
class Test_csv_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        
    }
    
    function get_data() {
        $query = $this->db->get('data_coba');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    function insert_csv($data) {
        $this->db->insert('data_coba', $data);
    }
    function insert_record($data)
    {
        $this->db->insert('data_coba', $data);        
    }
}