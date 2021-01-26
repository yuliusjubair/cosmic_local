<?php
class Rumahsinggah_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table_sosialisasi = 'table_rumahsinggah';
    var $column_order = array('','alamat', 'prov_id', 'kota_id');
    var $column_search = array('alamat', 'prov_id', 'kota_id');
    var $order = array('id'=>'desc', 'prov_id'=>'asc');
    
    private function _get_datatables_query($mc_id) {
        if($mc_id==""){
            $this->db_read->select("ts.id, ts.nama_rumahsinggah, mc.mc_name, mpro.mpro_name, mkab.mkab_name, ts.membayar, ts.kapasitas")
            ->from("table_rumahsinggah ts")
            ->join('master_company mc', 'mc.mc_id = ts.mc_id')
            ->join('master_provinsi mpro', 'mpro.mpro_id = ts.prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = ts.kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left');
        }else{

            $this->db_read->select("ts.id, ts.nama_rumahsinggah, mc.mc_name, mpro.mpro_name, mkab.mkab_name, ts.membayar, ts.kapasitas")
            ->from("table_rumahsinggah ts")
            ->join('master_company mc', 'mc.mc_id = ts.mc_id')
            ->join('master_provinsi mpro', 'mpro.mpro_id = ts.prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = ts.kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
            ->where('mc.mc_id',$mc_id);
        }
        $i = 0;
        $this->db_read->order_by('id','desc');
    }
    
    function get_datatables($mc_id)  {
        $this->_get_datatables_query($mc_id);
        
        $query = $this->db_read->get();
        return $query->result();
    }
    
    function count_filtered($mc_id) {
        $this->_get_datatables_query($mc_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all($mc_id) {
        if($mc_id==""){
            $this->db_read->select("ts.*")
            ->from("table_rumahsinggah ts")
            ->join('master_company mc', 'mc.mc_id = ts.mc_id');
        }else{

            $this->db_read->select("ts.*")
            ->from("table_rumahsinggah ts")
            ->join('master_company mc', 'mc.mc_id = ts.mc_id')
            ->where('mc.mc_id',$mc_id);
        }
        return $this->db_read->count_all_results();
    }
    
    public function get_byid($id) {
        $this->db_read->select("ts.*, mc.mc_name")
        ->from("table_rumahsinggah ts")
        ->join('master_company mc', 'mc.mc_id = ts.mc_id')
        ->where('ts.id',$id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function save($data) {
        $this->db_cud->insert('table_rumahsinggah', $data);
        return $this->db_cud->insert_id();
    }
    
    public function delete_byid($id) {
        $this->db_cud->where('id', $id);
        return $this->db_cud->delete('table_rumahsinggah');
    }

    private function _get_datatables_query_filter($kota, $nama_bumn, $kapasitas) {
        // echo($kapasitas);die;
        $where_kota = "";
        if(!empty($kota)){
            $k = explode(",", $kota);
            $data1 = array();
            foreach ($k as $key => $value) {
                $data1[] = "'".$value."'";
            }
            $data_kota = implode(",", $data1);
            $where_kota = $this->db_read->where("mkab.mkab_name in ($data_kota)");
        }

        $where_bumn = "";
        if(!empty($nama_bumn)){
            $k = explode(",", $nama_bumn);
            $data1 = array();
            foreach ($k as $key => $value) {
                $data1[] = "'".$value."'";
            }
            $data_bumn = implode(",", $data1);
            $where_bumn = $this->db_read->where("mc.mc_name in ($data_bumn)");
        }

        $where_kapasitas="";
        if(!empty($kapasitas)){
            $k = explode(";", $kapasitas);
            $where_kapasitas = $this->db_read->where("ts.kapasitas between '".$k[0]."' and '".$k[1]."'");
        }

            $this->db_read->select("ts.id, ts.nama_rumahsinggah, mc.mc_name, mpro.mpro_name, mkab.mkab_name, ts.membayar, ts.kapasitas");
            $this->db_read->from("table_rumahsinggah ts");
            $this->db_read->join('master_company mc', 'mc.mc_id = ts.mc_id');
            $this->db_read->join('master_provinsi mpro', 'mpro.mpro_id = ts.prov_id','left');
            $this->db_read->join('master_kabupaten mkab', 'mkab.mkab_id = ts.kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left');
            $where_kota;
            $where_bumn;
            $where_kapasitas;
        
        $i = 0;
        $this->db_read->order_by('id','desc');
    }
    
    function get_datatables_filter($kota, $nama_bumn, $kapasitas)  {
        $this->_get_datatables_query_filter($kota, $nama_bumn, $kapasitas);
        
        $query = $this->db_read->get();
        
        return $query->result();
    }
}