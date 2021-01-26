<?php
class Sosialisasi_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $table_sosialisasi = 'transaksi_sosialisasi';
    var $column_order = array('','ts_nama_kegiatan', 'ts_deskripsi', 'mslk_name');
    var $column_search = array('ts_nama_kegiatan', 'ts_deskripsi', 'mslk_name');
    var $order = array('ts_id'=>'desc', 'ts_nama_kegiatan'=>'asc',
        'ts_deskripsi'=>'asc', 'mslk_name'=>'asc');

    private function _get_datatables_query($mc_id) {
        $this->db_read->select("ts.*, mc.mc_name, mslk.mslk_name")
            ->from("transaksi_sosialisasi ts")
            ->join('master_company mc', 'mc.mc_id = ts.ts_mc_id')
            ->join('master_sosialisasi_kategori mslk', 'mslk.mslk_id = ts.ts_mslk_id')
            ->where('mc.mc_id',$mc_id);
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
        $this->db_read->order_by('ts_id','desc');
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
        $this->db_read->select("ts.*, mc.mc_name, mslk.mslk_name")
        ->from("transaksi_sosialisasi ts")
        ->join('master_company mc', 'mc.mc_id = ts.ts_mc_id')
        ->join('master_sosialisasi_kategori mslk', 'mslk.mslk_id = ts.ts_mslk_id')
        ->where('mc.mc_id',$mc_id);
        return $this->db_read->count_all_results();
    }

    public function get_byid($id) {
        $this->db_read->select("ts.*, mc.mc_name, mslk.mslk_name")
        ->from("transaksi_sosialisasi ts")
        ->join('master_company mc', 'mc.mc_id = ts.ts_mc_id')
        ->join('master_sosialisasi_kategori mslk', 'mslk.mslk_id = ts.ts_mslk_id')
        ->where('ts.ts_id',$id);
        $query = $this->db_read->get();
        return $query->row();
    }

    public function save($data) {
        $this->db_cud->insert($this->table_sosialisasi, $data);
        return $this->db_cud->insert_id();
    }

    public function delete_byid($id) {
        $this->db_cud->where('ts_id', $id);
        return $this->db_cud->delete($this->table_sosialisasi);
    }

    public function save_tbrk($data) {
        $this->db_cud->insert('table_rencanakerja', $data);
        return $this->db_cud->insert_id();
    }

    public function delete_tbrk($mc_id) {
        $this->db_cud->where('tbrk_mc_id', $mc_id);
        $this->db_cud->delete('table_rencanakerja');
    }

    public function get_tbrk_byid($mc_id) {
        $this->db_read->select("tbrk_mc_id, tbrk_filename")
        ->from("table_rencanakerja")
        ->where('tbrk_mc_id',$mc_id);
        $query = $this->db_read->get();
        return $query->row();
    }
}
