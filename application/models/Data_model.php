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
    
    function insert_data_sampling($table, $data)
    {
        $this->db->flush_cache();
        $this->db->empty_table($table);
        $this->db->flush_cache();
        foreach ($data as $data_pasien)
        {
            $data_sampling = array(
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
            );
            $this->db->replace($table, $data_sampling);
        }
    }
    
    function update_centroid($table, $centroid)
    {
        $this->db->flush_cache();
        $this->db->empty_table($table);
        $this->db->flush_cache();
        foreach ($centroid as $data_pasien)
        {
            $kmeans_centroid = array(
                'ID_CENTROID' => $data_pasien[0],
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
            );
            $this->db->replace($table, $kmeans_centroid);
        }
    }
    
    function update_kluster($data)
    {
        $this->db->replace('kmeans_kluster', $data);
    }
    
    function delete_data($table)
    {
        $this->db->empty_table($table);
    }
}