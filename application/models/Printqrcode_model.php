<?php
class Printqrcode_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table_report = 'transaksi_report';
    var $column_order = array('','tr_no','mpm_name', 'mpml_name', 'status');
    var $column_search = array('tr_no','mpm_name', 'mpml_name', 'status');
    var $order = array(''=>'','tr_no'=>'asc','mpm_name'=>'asc', 'mpml_name'=>'asc', 
        'status'=>'asc');

    private function _get_datatables_query($mc_id) {
        $this->db_read->select("*")
        ->from("view_list_report_all")
        ->where('mc_id',$mc_id);
        $i = 0;
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                }
                
                if(count($this->column_search) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }
        if(isset($_POST['order']))  {
            $this->db_read->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($this->order))  {
            $order = $this->order;
            $this->db_read->order_by(key($order), $order[key($order)]);
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
        $this->db_read->select("*")
            ->from("view_list_report_all")
            ->where('mc_id',$mc_id);
        return $this->db_read->count_all_results();
    }
    
    public function get_byid($id)  {
        $this->db_read->select("*")
            ->from("view_list_report_all")
            ->where('tr_id',$id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function delete_byid($id) {
        $this->db_cud->where('tr_id', $id);
        return $this->db_cud->delete($this->table_report);
    }
    
    public function save($data) {
        $this->db_cud->insert($this->table_report, $data);
        return $this->db_cud->affected_rows();
    }
    
    public function update($where, $data) {
        $this->db_cud->update($this->table_report, $data, $where);
        return $this->db_cud->affected_rows();
    }
    
    public function printqrcode($mpm_id) {
        $sql="SELECT mc.mc_id, mc.mc_name, mpm.mpm_id, mpm.mpm_name, mpm.mpm_alamat,
                (SELECT COUNT(*) jml_lvl FROM master_perimeter_level mpml 
                WHERE mpml.mpml_mpm_id=mpm.mpm_id)
            FROM master_company mc 
            INNER JOIN master_perimeter mpm ON mpm.mpm_mc_id=mc.mc_id
            WHERE mpm.mpm_id=$mpm_id";
        $result = $this->db_read->query($sql);
        return $result->row();
    }
    
    public function mpml_bympm($mpm_id) {
        $sql="SELECT * FROM master_perimeter_level
            WHERE mpml_mpm_id=$mpm_id";
        $result = $this->db_read->query($sql);
        return $result->result();
    }
    
    public function save_report($data) {
        $this->db_cud->insert($this->table_report, $data);
        return $this->db_cud->affected_rows();
    }
    
    public function get_faktur($mc_id) {
        $sql="SELECT * from get_faktur('$mc_id')";
        $result = $this->db_read->query($sql);
        return $result->row();
    }
    
    public function mc_bympml($mpml_id) {
        $sql="SELECT mc_id from master_company mc
            INNER JOIN master_perimeter mpm ON mpm.mpm_mc_id=mc.mc_id
            INNER JOIN master_perimeter_level mpml ON mpml.mpml_mpm_id=mpm.mpm_id
            WHERE mpml.mpml_id=$mpml_id";
        $result = $this->db_read->query($sql);
        return $result->row();
    }
}