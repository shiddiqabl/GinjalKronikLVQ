<?php
class Data_model extends CI_Model {
    
    function __construct() 
    {
        parent::__construct();
        
    }
    
    function get_data($table) 
    {
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) 
        {
            return $query->result_array();
        } 
        else 
        {
            return FALSE;
        }
    }
    
    function get_data_raw($table)
    {
        $query = $this->db->get($table);
        if ($query->num_rows() > 0)
        {
            return $query;
        }
        else
        {
            return FALSE;
        }
    }
    
    function get_data_alias($table)
    {
        $this->db->select("ID as '0', AGE as '1', BP as '2', SG as '3', AL as '4', SU as '5', RBC as '6', PC as '7', PCC as '8', 
                            BA as '9', BGR as '10', BU as '11', SC as '12', SOD as '13', POT as '14', HEMO as '15', PCV as '16', 
                            WBCC as '17', RBCC as '18', HTN as '19', DM as '20', CAD as '21', APPET as '22', PE as '23', ANE as '24', 
                            CLASS as '25'");
        $this->db->from($table);
        $query = $this->db->get();
        return $query;
    }
    
    function insert_data($table, $data) 
    {
        $this->db->insert($table, $data);
    }
    
    function delete_data($table)
    {
        $this->db->empty_table($table);
    }
}