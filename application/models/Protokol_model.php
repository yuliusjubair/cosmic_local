<?php
class Protokol_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table = 'master_protokol';
    var $tbl_table = 'table_protokol'; 
    var $column_search = array('v_mpt_name');

    private function _get_datatables_query($mc_id) {
        $this->db_read->from("protokol_bymc('$mc_id')");
        $i = 0;       
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                
                if(count($this->column_search) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }
    }
    
    function get_datatables($mc_id)  {
        $this->_get_datatables_query($mc_id);
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
        return $query->result();
    }
    
    function count_filtered($mc_id) {
        $this->_get_datatables_query($mc_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all($mc_id) {
        $this->db_read->from("protokol_bymc('$mc_id')");
        return $this->db_read->count_all_results();
    }
    
    public function get_by_id($id) {
        $this->db_read->from($this->tbl_table);
        $this->db_read->where('tbpt_id',$id);
        $query = $this->db_read->get();
        
        return $query->row();
    }
    
    public function save($data) {
        $this->db_cud = $this->load->database('cud', TRUE);
        $this->db_cud->insert($this->tbl_table, $data);
        return $this->db_cud->insert_id();
    }
    
    public function update($where, $data) {
        $this->db_cud = $this->load->database('cud', TRUE);
        $this->db_cud->update($this->tbl_table, $data, $where);
        return $this->db_cud->affected_rows();
    }
    
    public function delete_by_id($id) {
        $this->db_cud = $this->load->database('cud', TRUE);
        $this->db_cud->where('tbpt_id', $id);
        $this->db_cud->delete($this->tbl_table);
    }
    
    public function delete_bymcmpt($mc_id, $mpt_id) {
        $this->db_cud->where('tbpt_mc_id', $mc_id);
        $this->db_cud->where('tbpt_mpt_id', $mpt_id);
        $this->db_cud->delete('table_protokol');
    }
}