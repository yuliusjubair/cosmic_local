<?php
class Kasus_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table_kasus = 'transaksi_kasus';
    var $column_order = array('','tk_nama', 'msp_name', 'mpro_name', 'mkab_name',
        'msk_name', 'msk_name2', 'tk_tempat_perawatan');
    var $column_search = array('tk_nama', 'msp_name', 'mpro_name', 'mkab_name',
        'msk_name', 'msk_name2','tk_tempat_perawatan');
    var $order = array('tk_id'=>'desc', 'tk_nama'=>'asc', 'msp_name'=>'asc', 'mpro_name'=>'asc', 
        'mkab_name'=>'asc', 'msk_name2'=>'asc', 'tk_tempat_perawatan'=>'asc');
    
    private function _get_datatables_query($mc_id) {
        $this->db_read->select("tk.*, mc.mc_name, msp.msp_name,
            mpro.mpro_name, mkab.mkab_name, msk.msk_name, msk.msk_name2")
            ->from("transaksi_kasus tk")
            ->join('master_company mc', 'mc.mc_id = tk.tk_mc_id')
            ->join('master_status_pegawai msp', 'msp.msp_id = tk.tk_msp_id','left')
            ->join('master_provinsi mpro', 'mpro.mpro_id = tk.tk_mpro_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = tk.tk_mkab_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
            ->join('master_status_kasus msk','msk.msk_id = tk.tk_msk_id','left')
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
        
        $this->db_read->order_by('tk_id','desc');
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
        $this->db_read->select("tk.*, mc.mc_name, msp.msp_name,
            mpro.mpro_name, mkab.mkab_name, msk.msk_name, msk.msk_name2")
            ->from("transaksi_kasus tk")
            ->join('master_company mc', 'mc.mc_id = tk.tk_mc_id')
            ->join('master_status_pegawai msp', 'msp.msp_id = tk.tk_msp_id','left')
            ->join('master_provinsi mpro', 'mpro.mpro_id = tk.tk_mpro_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = tk.tk_mkab_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
            ->join('master_status_kasus msk','msk.msk_id = tk.tk_msk_id','left')
            ->where('mc.mc_id',$mc_id);
        return $this->db_read->count_all_results();
    }
    
    public function get_byid($id)  {
        $this->db_read->select("tk.*, tk.tk_nama, mc.mc_name, msp.msp_name,
            mpro.mpro_name, mkab.mkab_name, msk.msk_name, msk.msk_name2")
            ->from("transaksi_kasus tk")
            ->join('master_company mc', 'mc.mc_id = tk.tk_mc_id')
            ->join('master_status_pegawai msp', 'msp.msp_id = tk.tk_msp_id','left')
            ->join('master_provinsi mpro', 'mpro.mpro_id = tk.tk_mpro_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = tk.tk_mkab_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
            ->join('master_status_kasus msk','msk.msk_id = tk.tk_msk_id','left')
            ->where('tk_id',$id);
        $query = $this->db_read->get();
        
        return $query->row();
    }
    
    public function delete_byid($id) {
        $this->db_cud->where('tk_id', $id);
        return $this->db_cud->delete($this->table_kasus);
    }
    
    public function save($data) {
        $this->db_cud->insert($this->table_kasus, $data);
        return $this->db_cud->affected_rows();
    }
    
    public function update($where, $data) {
        $this->db_cud->update($this->table_kasus, $data, $where);
        return $this->db_cud->affected_rows();
    }
    
    public function getexcel_bymcid($mc_id) {
        $this->db_read->from('table_kasus_excel');
        $this->db_read->where('tkx_mc_id',$mc_id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function deleteexcel_bymcid($mc_id) {
        $this->db_cud->where('tkx_mc_id', $mc_id);
        $this->db_cud->delete('table_kasus_excel');
    }
    
    public function saveexcel($data) {
        $this->db_cud->insert('table_kasus_excel', $data);
        return $this->db_cud->insert_id();
    }
    
    public function getcompany_byname3($name3) {
        $this->db_read->select("mc_id, mc_code, mc_name, mc_name3")
            ->from("master_company mc")
            ->where('mc_name3',$name3);
        $query = $this->db_read->get();  
        return $query->row();
    }
    
    public function getkasus_byname($name) {
        $this->db_read->select("msk_id, msk_name, msk_name2")
        ->from("master_status_kasus mc")
        ->where('msk_name',$name);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function getkasus_bymcidname($company, $name) {
        $this->db_read->select("tk_id")
        ->from("transaksi_kasus")
        ->where('tk_mc_id',$company)
        ->where('tk_nama',$name);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function reset_bymcid($kd_perusahaan) {
        $this->db_cud->where('tk_mc_id', $kd_perusahaan);
        return $this->db_cud->delete($this->table_kasus);
    }
}