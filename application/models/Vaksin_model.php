<?php
class Vaksin_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
        $this->db_vaksin = $this->load->database('vaksin', TRUE);
    }
    
    var $table_vaksin = 'transaksi_vaksin';
    var $column_order = array('','tv_nama','mkab_name');
    var $column_search = array('tv_nama','mkab_name');
    var $order = array('tv_id'=>'desc', 'tv_nama'=>'asc', 'mkab_name'=>'asc');
    
    var $table_vaksin_tmp = 'tmp_vaksin';
    var $column_order_tmp = array('','nama');
    var $column_search_tmp = array('nama');
    var $order_tmp = array('nama'=>'desc');
    
    private function _get_datatables_query($mc_id) {
        $this->db_vaksin->select("tv.*, mc.mc_name,
            mkab.mkab_name")
            ->from("transaksi_vaksin tv")
            ->join('master_company mc', 'mc.mc_id = tv.tv_mc_id')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = tv.tv_mkab_id','left')
            ->where('mc.mc_id',$mc_id);
        $i = 0;
        foreach ($this->column_search as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_vaksin->group_start();
                    $this->db_vaksin->like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_vaksin->or_like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                }
                
                if(count($this->column_search) - 1 == $i) {
                    $this->db_vaksin->group_end();
                }
            }
            $i++;
        }
        $this->db_vaksin->order_by('tv_id','desc');
    }
    
    function get_datatables($mc_id)  {
        $this->_get_datatables_query($mc_id);
        
        if($_POST['length'] != -1)
            $this->db_vaksin->limit($_POST['length'], $_POST['start']);
            $query = $this->db_vaksin->get();
            return $query->result();
    }
    
    function count_filtered($mc_id) {
        $this->_get_datatables_query($mc_id);
        $query = $this->db_vaksin->get();
        return $query->num_rows();
    }
    
    public function count_all($mc_id) {
        $this->db_vaksin->select("tv.*, mc.mc_name,
            mkab.mkab_name")
            ->from("transaksi_vaksin tv")
            ->join('master_company mc', 'mc.mc_id = tv.tv_mc_id')
            ->join('master_kabupaten mkab', 'mkab.mkab_id = tv.tv_mkab_id','left')
            ->where('mc.mc_id',$mc_id);
        return $this->db_vaksin->count_all_results();
    }
    
    public function delete_byid($id) {
        $this->db_vaksin->or_where('tv_id', $id);
        return $this->db_vaksin->delete($this->table_vaksin);
    }
    
    public function save($data) {
        $this->db_vaksin->insert($this->table_vaksin, $data);
        return $this->db_vaksin->affected_rows();
    }
    
    public function update($where, $data) {
        $this->db_vaksin->update($this->table_vaksin, $data, $where);
        return $this->db_vaksin->affected_rows();
    }
    
//     public function get_byid($id)  {
//         $this->db_vaksin->select("tv.*, mc.mc_name, msp.msp_name,
//         mkab.mkab_name")
//         ->from("transaksi_vaksin tv")
//         ->join('master_company mc', 'mc.mc_id = tv.tv_mc_id')
//         ->join('master_status_pegawai msp', 'msp.msp_id = tv.tv_msp_id','left')
//         ->join('master_kabupaten mkab', 'mkab.mkab_id = tv.tv_mkab_id','left')
//         ->where('tv_id',$id);
//         $query = $this->db_vaksin->get();
        
//         return $query->row();
//     }
    
    public function get_byid($id) {
        $sql="select tv.*, mc.mc_name, msp.msp_name, mkab.mkab_name,
                case when tv_date3 is not null then 3
                when tv_date3 is null and tv_date2 is not null then 2
                else 1 end as sts_vaksin,
                case
                when (tv_nik_anak5 !='' or tv_nik_anak5 is not null) then 5
                when (tv_nik_anak5 ='' or tv_nik_anak5 is null) and
                	(tv_nik_anak4 is not null or tv_nik_anak5 !='') then 4
                when (tv_nik_anak4 ='' or tv_nik_anak4 is null) and
                	(tv_nik_anak3 is not null or tv_nik_anak4 !='') then 3
                when (tv_nik_anak3 ='' or tv_nik_anak3 is null) and
                	(tv_nik_anak2 is not null or tv_nik_anak2 !='') then 2
                else 1 end as sts_anak
                from transaksi_vaksin tv
                inner join master_company mc on mc.mc_id = tv.tv_mc_id
                left join master_status_pegawai msp  on msp.msp_id = tv.tv_msp_id
                left join master_kabupaten mkab  on mkab.mkab_id = tv.tv_mkab_id
                where tv_id=$id";
        $result = $this->db_vaksin->query($sql);
        return $result->row();
    }
    
    public function get_bynik($nik) {
        $sql="select tv.*, mc.mc_name, msp.msp_name, mkab.mkab_name,
                case when tv_date3 is not null then 3
                when tv_date3 is null and tv_date2 is not null then 2
                else 1 end as sts_vaksin,
                case
                when (tv_nik_anak5 !='' or tv_nik_anak5 is not null) then 5
                when (tv_nik_anak5 ='' or tv_nik_anak5 is null) and
                	(tv_nik_anak4 is not null or tv_nik_anak5 !='') then 4
                when (tv_nik_anak4 ='' or tv_nik_anak4 is null) and
                	(tv_nik_anak3 is not null or tv_nik_anak4 !='') then 3
                when (tv_nik_anak3 ='' or tv_nik_anak3 is null) and
                	(tv_nik_anak2 is not null or tv_nik_anak2 !='') then 2
                else 1 end as sts_anak
                from transaksi_vaksin tv
                inner join master_company mc on mc.mc_id = tv.tv_mc_id
                left join master_status_pegawai msp  on msp.msp_id = tv.tv_msp_id
                left join master_kabupaten mkab  on mkab.mkab_id = tv.tv_mkab_id
                where tv_nik='$nik'";
        $result = $this->db_vaksin->query($sql);
        return $result->row();
    }
    
    public function getexcel_bymcid($mc_id) {
        $this->db_vaksin->from('table_vaksin_excel');
        $this->db_vaksin->where('tvx_mc_id',$mc_id);
        $query = $this->db_vaksin->get();
        return $query->row();
    }
    
    public function deleteexcel_bymcid($mc_id) {
        $this->db_vaksin->where('tvx_mc_id', $mc_id);
        $this->db_vaksin->delete('table_vaksin_excel');
    }
    
    public function saveexcel($data) {
        $this->db_vaksin->insert('table_vaksin_excel', $data);
        return $this->db_vaksin->insert_id();
    }
    
    public function count_vaksin_bymc_id($mc_id) {
        $sql="SELECT * FROM vaksin_summary_bymcid('$mc_id') ";
        $result = $this->db_vaksin->query($sql);
        return $result;
    }
    
    public function count_nik($nik) {
        $sql= "SELECT * FROM transaksi_vaksin WHERE tv_nik='$nik'";
        $result = $this->db_vaksin->query($sql);
        return $result->result();
    }

    private function _get_datatables_query_tmp($mc_id) {
        $this->db_vaksin->select('*');
        $this->db_vaksin->from("(SELECT *,
                CASE WHEN status=0 THEN 'Progress'
                WHEN status=1 THEN 'Berhasil Parsing'
                ELSE 'Gagal Parsing' END AS sts
                FROM  tmp_vaksin tmp
                INNER JOIN 
                (SELECT kd_perusahaan, file 
                FROM tmp_vaksin WHERE kd_perusahaan='$mc_id' 
                ORDER BY id DESC LIMIT 1) b 
                ON b.kd_perusahaan=tmp.kd_perusahaan 
                AND b.file=tmp.file)x ")
        ;
        $i = 0;
        foreach ($this->column_search_tmp as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_vaksin->group_start();
                    $this->db_vaksin->like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_vaksin->or_like('LOWER('.$item.')', strtolower($_POST['search']['value']));
                }
                
                if(count($this->column_search_tmp) - 1 == $i) {
                    $this->db_vaksin->group_end();
                }
            }
            $i++;
        }
        $this->db_vaksin->order_by('nama','desc');
    }
    
    function get_datatables_tmp($mc_id)  {
        $this->_get_datatables_query_tmp($mc_id);
        
        if($_POST['length'] != -1)
            $this->db_vaksin->limit($_POST['length'], $_POST['start']);
            $query = $this->db_vaksin->get();
            return $query->result();
    }
    
    function count_filtered_tmp($mc_id) {
        $this->_get_datatables_query_tmp($mc_id);
        $query = $this->db_vaksin->get();
        return $query->num_rows();
    }
    
    public function count_all_tmp($mc_id) {
        $this->db_vaksin->select('*');
        $this->db_vaksin->from("(SELECT *,
                CASE WHEN status=0 THEN 'Progress'
                WHEN status=1 THEN 'Berhasil Parsing'
                ELSE 'Gagal Parsing' END AS sts
                FROM  tmp_vaksin tmp
                INNER JOIN 
                (SELECT kd_perusahaan, file 
                FROM tmp_vaksin WHERE kd_perusahaan='$mc_id' 
                ORDER BY id DESC LIMIT 1) b 
                ON b.kd_perusahaan=tmp.kd_perusahaan 
                AND b.file=tmp.file)x  ")
            ;
        return $this->db_vaksin->count_all_results();
    }
    
    public function lokasi1_vaksin($cari) {
        $sql="SELECT DISTINCT(tv_lokasi1)
              FROM transaksi_vaksin
              WHERE LOWER(tv_lokasi1) LIKE LOWER('%$cari%')";
        $result = $this->db_vaksin->query($sql);
        return $result;
    }
    
}