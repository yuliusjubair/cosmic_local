<?php
class Perimeter_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $tpe = 'table_perimeter_excel';
    var $table = 'tmp_perimeter';
    var $tbl_table = 'table_protokol';
    var $column_order = array('tmp.id');
    var $column_search = array('perimeter','level','pic','fo','nik_pic','nik_fo'
        ,'keterangan_error','provinsi','kota','sts'
    );
    
    private function _get_datatables_query($mc_id) {
        $this->db_read->from("(SELECT *,
                CASE WHEN status=0 THEN 'Progress'
                WHEN status=1 THEN 'Berhasil Parsing'
                ELSE 'Gagal Parsing' END AS sts
                FROM  tmp_perimeter tmp
                INNER JOIN (SELECT file FROM tmp_perimeter
                WHERE kd_perusahaan='$mc_id' ORDER BY id DESC LIMIT 1) b ON
                b.file=tmp.file)x ");
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
        
        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_bykategori[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_bykategori)) {
            $this->db_read->order_by('status','DESC');
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
        $this->db_read->from("(SELECT *,
                CASE WHEN status=0 THEN 'Progress'
                WHEN status=1 THEN 'Berhasil Parsing'
                ELSE 'Gagal Parsing' END AS sts
                FROM  tmp_perimeter tmp
                INNER JOIN (SELECT file FROM tmp_perimeter
                WHERE kd_perusahaan='$mc_id' ORDER BY id DESC LIMIT 1) b ON
                b.file=tmp.file)x");
        return $this->db_read->count_all_results();
    }
    
    public function get_by_id($id) {
        $this->db_read->from($this->tpe);
        $this->db_read->where('tbpmx_mc_id',$id);
        $query = $this->db_read->get();
        
        return $query->row();
    }
    
    public function save($data) {
        $this->db_cud->insert($this->tpe, $data);
        return $this->db_cud->insert_id();
    }
    
    public function update($where, $data) {
        $this->db_cud->update($this->tpe, $data, $where);
        return $this->db_cud->affected_rows();
    }
    
    public function delete_by_id($id) {
        $this->db_cud->where('tpe', $id);
        $this->db_cud->delete($this->tbl_table);
    }
    
    public function save_tbpt($data) {
        $this->db_cud->insert($this->tbl_table, $data);
        return $this->db_cud->insert_id();
    }
    
    public function delete_tbpt($mc_id, $mpt_id) {
        $this->db_cud->where('tbpt_mc_id', $mc_id);
        $this->db_cud->where('tbpt_mpt_id', $mpt_id);
        $this->db_cud->delete($this->tbl_table);
    }
}