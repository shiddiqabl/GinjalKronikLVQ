<?php
class Data_model extends CI_Model {
    
    function __construct() 
    {
        parent::__construct();
        
    }
    
    function get_data() 
    {
        $query = $this->db->get('data_pasien');
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function insert_data($data) 
    {
        $this->db->insert('data_pasien', $data);
    }
    
    function delete_data()
    {
        $this->db->empty_table('data_pasien');
    }
}