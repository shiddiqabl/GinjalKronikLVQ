<?php
class Test_csv_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
        
    }
    
    function get_data() {
        $query = $this->db->get('test_csv');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return FALSE;
        }
    }
    
    function insert_csv($data) {
        $this->db->insert('test_csv', $data);
    }
    function insert_record($data)
    {
        $this->db->insert('test_csv', $data);        
    }
}