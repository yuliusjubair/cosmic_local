<?php
class Companynonbumn_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table = 'master_company';
    var $column_order = array(null, 'mc_name','mpro_name','mc_date_insert','mc_status');
    var $column_search = array('mc_name','mpro_name','mc_status');
    var $order = array('mc_date_insert' => 'desc');
    
    private function _get_company_query($status=NULL) {
        $this->db_read->from($this->table);
        $this->db_read->join('master_provinsi','master_provinsi.mpro_id = '.$this->table.'.mc_prov_id');
        $this->db_read->where("mc_flag", 2);
        if($status=="sudah"){
            $this->db_read->where("mc_status", "Verifikasi");
        }elseif($status=="belum"){
            $this->db_read->where("mc_status", "Belum terverifikasi");
        }
        
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
        $this->_get_company_query($status);
        if($_POST['length'] != -1){
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
        }
        return $query->result();
    }
    
    function count_filtered($status=NULL) {
        $this->_get_company_query($status);
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all($status) {
        $this->db_read->from($this->table);
        $this->db_read->join('master_provinsi','master_provinsi.mpro_id = '.$this->table.'.mc_prov_id');
        $this->db_read->where("mc_flag", 2);
        if($status=="sudah"){
            $this->db_read->where("mc_status", "Verifikasi");
        }elseif($status=="belum"){
            $this->db_read->where("mc_status", "Belum terverifikasi");
        }
        return $this->db_read->count_all_results();
    }

    public function get_company_pegawai() {
        $this->db_read->select('mc_jumlah_pegawai , COUNT(*) cnt ')
        ->from('master_company')
        ->where('mc_flag',2)
        ->group_by('mc_jumlah_pegawai');
        return $this->db_read->get();
    }

    public function get_byid($id, $tbpa_id) {
        $this->db_read->select("a.*, mc.ms_name jenis, mpro.mpro_name provinsi, mkab.mkab_name kota, tpa.tbpa_nama_pj, tbpa_no_tlp_pj, tbpa_email_pj")
        ->from("master_company a")
        ->join('master_sektor mc', 'mc.ms_id = a.mc_ms_id', 'left')
         ->join('master_provinsi mpro', 'mpro.mpro_id = a.mc_prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = a.mc_kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
            ->join('table_pengajuan_atestasi tpa', 'tbpa_mc_id = a.mc_id')
        ->where('tpa.tbpa_id',$tbpa_id);
        // ->order_by('tpa.tbpa_id desc');
        $query = $this->db_read->get();
        
        return $query->row();
    }

    function cosmic_index_minggu_ini($mc_id){
         $sql = $this->db->query("SELECT
                        a.v_mc_id,
                        a.v_mc_name,
                        a.v_ms_id,
                        a.v_ms_name,
                        a.v_cosmic_index,
                        a.v_pemenuhan_protokol,
                        a.v_pemenuhan_ceklist_monitoring,
                        a.v_pemenuhan_eviden
                        FROM mv_cosmic_index_report a
                        where a.v_mc_id='$mc_id'
                        ")->row();
        return isset($sql->v_cosmic_index)?$sql->v_cosmic_index:0;                 
    }

    function cosmic_index_minggu_lalu($mc_id){
        $sql="SELECT v_week
              FROM list_aktivitas_week()
              where v_rownum = (select max(v_rownum)-1 from list_aktivitas_week())";
        $result = $this->db_read->query($sql)->row();
        $week = $result->v_week;
        $query = $this->db->query("SELECT *
                        FROM report_cosmic_index rpi
                        WHERE rci_week = '$week'
                        and rci_mc_id='$mc_id'
                        ")->row();
        return isset($query->rci_cosmic_index)?$query->rci_cosmic_index:0;                 
    }
    
}