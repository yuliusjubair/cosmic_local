<?php
class Pengumuman_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table = 'master_pengumuman';
    var $column_order = array('judul', 'description');
    var $column_search = array('judul', 'description');
    var $order = array('id'=>'desc');
    
    private function _get_datatables_query() {
        $this->db_read->select("mp.*")
            ->from("master_pengumuman mp");
        $i = 0;
        $this->db_read->order_by('id','desc');
    }
    
    function get_datatables()  {
        $this->_get_datatables_query();
        
        $query = $this->db_read->get();
        return $query->result();
    }
    
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all() {
        $this->db_read->select("ts.*")
        ->from("master_pengumuman ts");
        return $this->db_read->count_all_results();
    }
    
    public function get_byid($id) {
        $this->db_read->select("ts.*")
        ->from("master_pengumuman ts")
        ->where('ts.id',$id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function save($data) {
        $this->db_cud->insert('master_pengumuman', $data);
        return $this->db_cud->insert_id();
    }
    
    public function delete_byid($id) {
        $this->db_cud->where('id', $id);
        return $this->db_cud->delete('master_pengumuman');
    }
}