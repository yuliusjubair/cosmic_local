<?php
class Companysertifikasi_model extends CI_Model {
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
        $this->db_read->select('master_company.mc_id as mc_idnya, mc_name, mc_user_update_date, mc_status_sertifikasi, username, mc_nama_pic_sertifikasi, tps.tbps_date_insert, tps.tbps_id, tps.tbps_date_verifikasi, tps.tbps_nama_verifikasi, tps.tbps_status');
        $this->db_read->from('master_company');
        $this->db_read->join('master_provinsi','master_provinsi.mpro_id = master_company.mc_prov_id', 'LEFT');
        $this->db_read->join('app_users as user', 'master_company.mc_user_update_status = user.id','left');
        $this->db_read->join('table_pengajuan_sertifikasi as tps', 'master_company.mc_id = tps.tbps_mc_id');
        //$this->db_read->where('master_company.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi)');
         if($status=="1"){
            //disetujui
            $this->db_read->where("mc_status_sertifikasi", "1");
        }elseif ($status=="2") {
            $this->db_read->where("(mc_status_sertifikasi = 0 or mc_status_sertifikasi is null)");
            // $this->db_read->where("mc_status_sertifikasi", null);
        }elseif($status=="3"){
            //dalamproses
            $this->db_read->where("mc_status_sertifikasi", "3");
        }elseif($status=="4"){
            //ditolak
            $this->db_read->where("mc_status_sertifikasi", "4");
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
        //$query = $this->db_read->get();
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
    
    public function count_all($status=NULL) {
        $this->db_read->from($this->table);
        $this->db_read->where('master_company.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi)');
         if($status=="1"){
            //disetujui
            $this->db_read->where("mc_status_sertifikasi", "1");
        }elseif ($status=="2") {
            $this->db_read->where("mc_status_sertifikasi", "0");
            $this->db_read->or_where("mc_status_sertifikasi", null);
        }elseif($status=="3"){
            //dalamproses
            $this->db_read->where("mc_status_sertifikasi", "3");
        }elseif($status=="4"){
            //ditolak
            $this->db_read->where("mc_status_sertifikasi", "4");
        }
        return $this->db_read->count_all_results();
    }

     public function get_byid($id, $tbps_id) {
        $this->db_read->select("a.*, mc.ms_name jenis, mpro.mpro_name provinsi, mkab.mkab_name kota, mss.status, tps.tbps_nama_pj, tps.tbps_no_tlp_pj, tps.tbps_email_pj, tps.tbps_id, tps.tbps_status, tps.tbps_estimasi")
        ->from("master_company a")
        ->join('master_sektor mc', 'mc.ms_id = a.mc_ms_id', 'left')
         ->join('master_provinsi mpro', 'mpro.mpro_id = a.mc_prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = a.mc_kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
        ->join('table_pengajuan_sertifikasi as tps', 'a.mc_id = tps.tbps_mc_id')  
        ->join('master_status_sertifikasi mss','mss.id = tps.tbps_status_pengajuan_id','left')  
        ->where('tps.tbps_id',$tbps_id);
        $query = $this->db_read->get();
        
        return $query->row();
    }

     public function get_byid_tbps($tbps_id) {
        $this->db_read->select("a.*, mc.ms_name jenis, mpro.mpro_name provinsi, mkab.mkab_name kota, mss.status, tps.tbps_nama_pj, tps.tbps_no_tlp_pj, tps.tbps_email_pj, tps.tbps_id, tps.tbps_status")
        ->from("master_company a")
        ->join('master_sektor mc', 'mc.ms_id = a.mc_ms_id', 'left')
         ->join('master_provinsi mpro', 'mpro.mpro_id = a.mc_prov_id','left')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = a.mc_kota_id and mkab.mkab_mpro_id=mpro.mpro_id','left')
        ->join('table_pengajuan_sertifikasi as tps', 'a.mc_id = tps.tbps_mc_id')  
        ->join('master_status_sertifikasi mss','mss.id = tps.tbps_status_pengajuan_id','left')  
        ->where('tbps_id',$tbps_id);
        $query = $this->db_read->get();
        
        return $query->row();
    }

     public function getlist_card_sertifikasi(){
        $sql = "
        select * from (
        SELECT 1 id, 'Perusahaan Mendaftar' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi)
                ) jml
                UNION
                SELECT 2 id, 'Perusahaan Disetujui' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi where tbps_status=1)) jml
                union
                SELECT 3 id, 'Perusahaan Di Proses' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi where tbps_status=0)) jml
                union
                SELECT 4 id, 'Perusahaan Batal' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbps_mc_id from table_pengajuan_sertifikasi where tbps_status=4)) jml
                ) row order by id";
        $row = $this->db_read->query($sql);
        return $row;
    }
    
}