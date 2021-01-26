<?php
class Company_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table = 'master_company';
    var $column_order = array(null, 'mc_name','mc_name2');
    var $column_search = array('mc_name','mc_name2');
    var $order = array('mc_name' => 'asc');
    
    private function _get_company_query($status=NULL) {
        $this->db_read->from('master_company');
        $this->db_read->join('master_provinsi','master_provinsi.mpro_id = master_company.mc_prov_id', 'LEFT');
        $i = 0;
        
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0)  {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like($item, $_POST['search']['value']);
                }
                
                if(count($this->column_search) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }
        
        if(isset($_POST['order'])) {
            $this->db_read->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db_read->order_by(key($order), $order[key($order)]);
        }
    }
    
    function get_company($status=NULL) {
        $this->_get_company_query();
        //$query = $this->db_read->get();
        if($_POST['length'] != -1){
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
        }
        return $query->result();
    }
    
    function count_filtered() {
        $this->_get_company_query();
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all() {
        $this->db_read->from($this->table);
        return $this->db_read->count_all_results();
    }

     public function get_byid($id) {
        $this->db_read->select("a.*, mc.ms_name jenis, mpro.mpro_name provinsi, mkab.mkab_name kota")
        ->from("master_company a")
        ->join('master_sektor mc', 'mc.ms_id = a.mc_ms_id', 'left')
         ->join('master_provinsi mpro', 'mpro.mpro_id = a.mc_prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = a.mc_kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
        ->where('a.mc_id',$id);
        $query = $this->db_read->get();
        
        return $query->row();
    }
    
}