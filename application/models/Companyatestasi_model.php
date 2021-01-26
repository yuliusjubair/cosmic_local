<?php
class Companyatestasi_model extends CI_Model {
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
        /*$this->db->select('master_company.mc_name, master_company.mc_id, tbpa_date_insert, mc_nama_pic');
        $this->db_read->from('master_company');
        $this->db_read->join('table_pengajuan_atestasi','table_pengajuan_atestasi.tbpa_mc_id = master_company.mc_id');
        $this->db_read->join('app_users as user', 'table_pengajuan_atestasi.tbpa_user_update = user.id','left');*/
        $this->db_read->select('master_company.mc_name, master_company.mc_id, mc_user_update_date, 
            mc_nama_pic, mc_status_atestasi, mc_update_date_atestasi, mc_nama_pic_atestasi, 
            tps.tbpa_status, tps.tbpa_date_insert, tps.tbpa_nama_pj, tps.tbpa_id, 
            tps.tbpa_date_update');
        $this->db_read->from('master_company');
        $this->db_read->join('table_pengajuan_atestasi as tps', 'master_company.mc_id = tps.tbpa_mc_id');
        if($status=="1"){
            //disetujui
            $this->db_read->where("tbpa_status", "1");
        }elseif ($status=="2") {
            $this->db_read->where("tbpa_status", "0");
            $this->db_read->or_where("tbpa_status", null);
        }elseif($status=="3"){
            //dalamproses
            $this->db_read->where("tbpa_status", "3");
        }elseif($status=="4"){
            //ditolak
            $this->db_read->where("tbpa_status", "4");
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

        // $this->db_read->group_by('yp_master(domain, map)r_company.mc_name, master_company.mc_id, tbpa_date_insert, mc_nama_pic');
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
    
    function count_filtered() {
        $this->_get_company_query();
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all() {
        $this->db_read->from($this->table);
        return $this->db_read->count_all_results();
    }
    public function getlistcard_riwayatpengajuan_bymcid($mc_id){
        $sql = "
           (SELECT 1 id, 'Total Pengajuan Layanan' judul,
            COUNT(*) jml
            FROM view_riwayat_pengajuan
            WHERE mc_id='$mc_id')
            UNION ALL
            (SELECT 2 id, 'Pengajuan diproses' judul,
            COUNT(*) jml
            FROM view_riwayat_pengajuan
            WHERE mc_id='$mc_id' AND status_id = 3)
            UNION ALL
            (SELECT 3 id, 'Pengajuan ditolak' judul,
            COUNT(*) jml
            FROM view_riwayat_pengajuan
            WHERE mc_id='$mc_id' AND status_id = 4)
            UNION ALL
            (SELECT 4 id, 'Pengajuan disetujui' judul,
            COUNT(*) jml
            FROM view_riwayat_pengajuan
            WHERE mc_id='$mc_id' AND status_id = 1)
            ";
        $row = $this->db_read->query($sql);
        return $row;
    }
    
    var $column_order_hist = array('','mlp_name','mlp_by','status');
    var $column_search_hist = array('mlp_name','mlp_by','status');
    var $order_hist = array('id'=>'desc');
    
    private function _get_datatables_query_hist($mc_id) {
            $this->db_read->select("*")
            ->from("view_riwayat_pengajuan")
            ->where('mc_id',$mc_id);
            $i = 0;
            foreach ($this->column_search_hist as $item) {
                if($_POST['search']['value']) {
                    if($i===0) {
                        $this->db_read->group_start();
                        $this->db_read->like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                    } else {
                        $this->db_read->or_like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                    }
                    
                    if(count($this->column_search_hist) - 1 == $i) {
                        $this->db_read->group_end();
                    }
                }
                $i++;
            }
            
            $this->db_read->order_by('id','desc');
    }
    
    function get_datatables_hist($mc_id)  {
        $this->_get_datatables_query_hist($mc_id);
        
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }
    
    function count_filtered_hist($mc_id) {
        $this->_get_datatables_query_hist($mc_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all_hist($mc_id) {
        $this->db_read->select("*")
            ->from("view_riwayat_pengajuan")
            ->where('mc_id',$mc_id);
        return $this->db_read->count_all_results();
    }
    
}