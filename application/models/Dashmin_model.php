<?php
class Dashmin_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $column_order_bykategori = array('v_judul','v_jml');
    var $column_search_bykategori = array('v_judul');
    var $order_bykategori = array('v_judul'=>'asc');

    var $column_order_byprovinsi = array('v_judul','v_jml');
    var $column_search_byprovinsi = array('v_judul');
    var $order_byprovinsi = array('v_judul'=>'asc');

    var $column_order_bycosmicindex= array('v_judul','v_jml');
    var $column_search_bycosmicindex = array('v_judul');
    var $order_bycosmicindex = array('v_judul'=>'asc');

    var $column_order_byperusahaanperimeter = array('mpml_name','pic','fo');
    var $column_search_byperusahaanperimeter = array('mpml_name','pic','fo');
    var $order_byperusahaanperimeter = array('mpml_name'=>'asc');

    private function _get_datatables_query_bykategori() {
        $this->db_read->from("dashboard_perimeter_bykategori()");
        $i = 0;
        foreach ($this->column_search_bykategori as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_bykategori) - 1 == $i) {
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
            $this->db_read->order_by('v_judul','asc');
        }
    }

    function get_datatables_bykategori()  {
        $this->_get_datatables_query_bykategori();

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_bykategori() {
        $this->_get_datatables_query_bykategori();
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_bykategori() {
        $this->db_read->from("dashboard_perimeter_bykategori()");
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_byprovinsi() {
        $this->db_read->from("dashboard_perimeter_byprovinsi()");
        $i = 0;
        foreach ($this->column_search_byprovinsi as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_byprovinsi) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_byprovinsi[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_byprovinsi)) {
            $this->db_read->order_by('v_judul','asc');
        }
    }

    function get_datatables_byprovinsi()  {
        $this->_get_datatables_query_byprovinsi();

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_byprovinsi() {
        $this->_get_datatables_query_byprovinsi();
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_byprovinsi() {
        $this->db_read->from("dashboard_perimeter_byprovinsi()");
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_bycosmicindex() {
        $this->db_read->from("dashboard_perimeter_bycosmicindex()");
        $i = 0;
        foreach ($this->column_search_bycosmicindex as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_bycosmicindex) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_bycosmicindex[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_bycosmicindex)) {
            $this->db_read->order_by('v_judul','asc');
        }
    }

    function get_datatables_bycosmicindex()  {
        $this->_get_datatables_query_bycosmicindex();

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_bycosmicindex() {
        $this->_get_datatables_query_bycosmicindex();
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_bycosmicindex() {
        $this->db_read->from("dashboard_perimeter_bycosmicindex()");
        return $this->db_read->count_all_results();
    }

    public function mv_rangkuman_all(){
        $sql="SELECT ms_name, mc_name, cnt_mpm, cosmic_index,
                cosmic_index_min1, positif, suspek, kontakerat, selesai,
                meninggal, persen_dokumen, belum_dokumen, sosialisasi_akhir
                FROM mv_rangkuman_all" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }
    
    public function mv_rangkuman_all_semua(){
        $sql="SELECT ms_name, mc_name, cnt_mpm, cosmic_index,
                cosmic_index_min1, positif, suspek, kontakerat, selesai,
                meninggal, persen_dokumen, belum_dokumen, sosialisasi_akhir
                FROM mv_rangkuman_all_semua" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }
    
    public function mv_rangkuman_all_nonbumn(){
        $sql="SELECT ms_name, mc_name, cnt_mpm, cosmic_index,
                cosmic_index_min1, positif, suspek, kontakerat, selesai,
                meninggal, persen_dokumen, belum_dokumen, sosialisasi_akhir
                FROM mv_rangkuman_all_nonbumn" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }

    public function mv_cluster_rangkuman_all($msc_id){
        $sql="SELECT ms_name, mc_name, cnt_mpm, cosmic_index,
                cosmic_index_min1, positif, suspek, kontakerat, selesai,
                meninggal, persen_dokumen, belum_dokumen, sosialisasi_akhir
                FROM mv_rangkuman_all
                WHERE ms_id='$msc_id'" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }

    //sprint16
    private function _get_datatables_query_byperusahaanperimeter($mpm_id) {
        $this->db_read->select("mpml.mpml_id, mpml.mpml_name, mpml.mpml_pic_nik, mpml.mpml_me_nik,mpml_mpm_id,
                mpml.mpml_ket, userpic.first_name as pic, userfo.first_name as fo ")
        ->from("master_perimeter_level mpml")
        ->join('app_users as userpic', 'mpml.mpml_pic_nik = userpic.username','left')
        ->join('app_users as userfo', 'mpml.mpml_me_nik = userfo.username','left')
        ->where('mpml.mpml_mpm_id',$mpm_id);

        $i = 0;
        foreach ($this->column_search_byperusahaanperimeter as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_byperusahaanperimeter) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_byperusahaanperimeter[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_byperusahaanperimeter)) {
            $this->db_read->order_by('mpml_name','asc');
        }
    }

    function get_datatables_byperusahaanperimeter($mpm_id)  {
        $this->_get_datatables_query_byperusahaanperimeter($mpm_id);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    public function detail_tabel_perimeter($id){
        return $this->db_read->query("SELECT mcr_name,tpmd_order,tpmd.tpmd_id, mcr.mcr_id
                        FROM table_perimeter_detail tpmd
                        INNER JOIN master_cluster_ruangan mcr ON mcr.mcr_id=tpmd.tpmd_mcr_id
                        WHERE tpmd_mpml_id='$id'
                        AND tpmd_cek='t';  ");
    }

    public function get_status_perimeter_detail($tpmd_id, $mcr_id){
        $sql1 = $this->db_read->query("SELECT count(*) as jml FROM konfigurasi_car INNER JOIN master_car ON master_car.mcar_id = konfigurasi_car.kcar_mcar_id where konfigurasi_car.kcar_ag_id=4 and konfigurasi_car.kcar_mcr_id='$mcr_id' and master_car.mcar_active=true")->row();

        $aktifitas = $sql1->jml;
        $sql = $this->db_read->query("select count(*) as jml from transaksi_aktifitas ta
        join table_perimeter_detail tpd on tpd.tpmd_id = ta.ta_tpmd_id and tpd.tpmd_cek = true
        join master_perimeter_level mpl on mpl.mpml_id = tpd.tpmd_mpml_id
        join konfigurasi_car kc on kc.kcar_id = ta.ta_kcar_id
        where  ta.ta_status = 1 and  tpd.tpmd_id = '$tpmd_id' and ta.ta_date IN (
                        SELECT
                        CAST(date_trunc('week', CURRENT_DATE) AS DATE) + i
                        FROM generate_series(0,4) i
                        ) and kc.kcar_ag_id = 4
                        and kc.kcar_ag_id = 4
        group by tpd.tpmd_id, tpd.tpmd_mpml_id, tpd.tpmd_mcr_id, ta.ta_kcar_id order by max(ta.ta_date_update) desc")->row();
        if(isset($sql->jml)){
            $clustertrans = $sql->jml;
            if ( $aktifitas <= $clustertrans) {
                $sts_mnt = 'Terverifikasi';
            } else {
                $sts_mnt = 'Belum Terverifikasi';
            }
        }else{
            $sts_mnt = 'Belum Terverifikasi';
        }
        return $sts_mnt;
    }

    public function get_percent($mpl_id, $mpml_id){
        if($mpml_id==""){
            $cluster=0;
        }else{
            $cluster_sql = $this->db_read->query("SELECT count(*) as jml FROM table_perimeter_detail WHERE tpmd_mpml_id='$mpml_id' and tpmd_cek='true'")->row();
            $cluster = $cluster_sql->jml;
        }

        $sql = $this->db_read->query("select count(*) as jml from transaksi_aktifitas ta
        join table_perimeter_detail tpd on tpd.tpmd_id = ta.ta_tpmd_id and tpd.tpmd_cek = true
        join master_perimeter_level mpl on mpl.mpml_id = tpd.tpmd_mpml_id
        join konfigurasi_car kc on kc.kcar_id = ta.ta_kcar_id
        where ta.ta_status = 1 and mpl.mpml_mpm_id = '$mpl_id'
        and ta.ta_date IN (
                        SELECT
                        CAST(date_trunc('week', CURRENT_DATE) AS DATE) + i
                        FROM generate_series(0,4) i
                        ) and kc.kcar_ag_id = 4
        group by tpd.tpmd_id, tpd.tpmd_mpml_id, tpd.tpmd_mcr_id ")->row();
        $clustertrans = isset($sql->jml)?$sql->jml:0;

         if ($cluster <> 0){
            if ($cluster <= $clustertrans) {
                //return true;
                return array(
                    "status" => true,
                    "percentage" => 1);
            } else {
                //return false;
                return array(
                    "status" => false,
                    "percentage" => round($clustertrans/$cluster,2));
            }
        } else {
            //return false;
            return array(
                "status" => false,
                "percentage" => 0);
        }
    }

    public function get_percent_by_mpl_id($mpl_id){
        $m = $this->db->query("select * from master_perimeter_level mpl where mpl.mpml_mpm_id = '$mpl_id'")->row();
        // print_r($m->mpml_id);die;
        $mpml_id = isset($m->mpml_id)?$m->mpml_id:'0'; 
        if(!empty($mpml_id)){

            $cluster_sql = $this->db_read->query("SELECT count(*) as jml FROM table_perimeter_detail WHERE tpmd_mpml_id='$mpml_id' and tpmd_cek='true'")->row();
            $cluster = $cluster_sql->jml;
        }else{
            $cluster = 0;
        }
        

        $sql = $this->db_read->query("select count(*) as jml from transaksi_aktifitas ta
        join table_perimeter_detail tpd on tpd.tpmd_id = ta.ta_tpmd_id and tpd.tpmd_cek = true
        join master_perimeter_level mpl on mpl.mpml_id = tpd.tpmd_mpml_id
        join konfigurasi_car kc on kc.kcar_id = ta.ta_kcar_id
        where ta.ta_status = 1 and mpl.mpml_mpm_id = '$mpl_id'
        and ta.ta_date IN (
                        SELECT
                        CAST(date_trunc('week', CURRENT_DATE) AS DATE) + i
                        FROM generate_series(0,4) i
                        ) and kc.kcar_ag_id = 4
        group by tpd.tpmd_id, tpd.tpmd_mpml_id, tpd.tpmd_mcr_id ")->row();
        $clustertrans = isset($sql->jml)?$sql->jml:0;

         if ($cluster <> 0){
            if ($cluster <= $clustertrans) {
                //return true;
                return array(
                    "status" => true,
                    "percentage" => 1);
            } else {
                //return false;
                return array(
                    "status" => false,
                    "percentage" => round($clustertrans/$cluster,2));
            }
        } else {
            //return false;
            return array(
                "status" => false,
                "percentage" => 0);
        }
    }

    function getprotokol_terbaru($group_company){
      // 3 untuk Semua
      if($group_company==1 || $group_company==2){
        $string = "
            SELECT mpt_id, mpt_name, b.mc_name, tbpt_id, tbpt_filename, tbpt_date_insert, tbpt_date_update
                FROM master_protokol mpt
                 JOIN
                    (SELECT tbpt.*, mc_name
                        FROM table_protokol tbpt
                         join master_company mc ON mc_id=tbpt_mc_id and mc_flag=".$group_company.") b  ON b.tbpt_mpt_id=mpt.mpt_id
                WHERE mpt_active=true AND mpt_type='1' FETCH FIRST 15 ROWS ONLY;
        ";
      } else {
        $string = "
            SELECT mpt_id, mpt_name, b.mc_name, tbpt_id, tbpt_filename, tbpt_date_insert, tbpt_date_update
                FROM master_protokol mpt
                 JOIN
                    (SELECT tbpt.*, mc_name
                        FROM table_protokol tbpt
                         join master_company mc ON mc_id=tbpt_mc_id ) b  ON b.tbpt_mpt_id=mpt.mpt_id
                WHERE mpt_active=true AND mpt_type='1' FETCH FIRST 15 ROWS ONLY;
        ";
      }

        $responsex = $this->db_read->query($string);
        return $responsex;
    }

    function getkegiatan_terbaru($group_company){
      // 3 untuk Semua
        if($group_company==1 || $group_company==2){

        $responsex = $this->db_read->select("ts.*, mc.mc_name, mslk.mslk_name")
            ->from("transaksi_sosialisasi ts")
            ->join('master_company mc', 'mc.mc_id = ts.ts_mc_id')
            ->join('master_sosialisasi_kategori mslk', 'mslk.mslk_id = ts.ts_mslk_id')
            ->where('mc.mc_flag ='.$group_company)
            ->limit('0,10');
        } else {
          $responsex = $this->db_read->select("ts.*, mc.mc_name, mslk.mslk_name")
              ->from("transaksi_sosialisasi ts")
              ->join('master_company mc', 'mc.mc_id = ts.ts_mc_id')
              ->join('master_sosialisasi_kategori mslk', 'mslk.mslk_id = ts.ts_mslk_id')
              ->limit('0,10');
        }
        $query = $this->db_read->get();
        return $query;
    }

    function getkasus_terbaru($group_company){
      // 3 untuk Semua
      if($group_company==1 || $group_company==2){
        $string = "
            SELECT msk.msk_id, msk.msk_name2, mc.mc_name, tk.tk_date_insert
                    FROM master_status_kasus msk
                    LEFT JOIN transaksi_kasus tk on tk.tk_msk_id=msk.msk_id
                    INNER join master_company mc ON mc.mc_id=tk.tk_mc_id and mc.mc_flag = ".$group_company."
                    FETCH FIRST 15 ROWS ONLY;
        ";
      } else {
        $string = "
            SELECT msk.msk_id, msk.msk_name2, mc.mc_name, tk.tk_date_insert
                    FROM master_status_kasus msk
                    LEFT JOIN transaksi_kasus tk on tk.tk_msk_id=msk.msk_id
                    INNER join master_company mc ON mc.mc_id=tk.tk_mc_id
                    FETCH FIRST 15 ROWS ONLY;
        ";
      }

        $responsex = $this->db_read->query($string);
        return $responsex;
    }

    public function dashvaksin_rangkuman(){
        $sql="SELECT * FROM vaksin_rangkuman()" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }

    public function getlist_card_partner(){
        $sql = "
        select * from (
        SELECT 1 id, 'Perusahaan Mendaftar' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbpa_mc_id from table_pengajuan_atestasi)
                ) jml
                UNION
                SELECT 2 id, 'Perusahaan Disetujui' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbpa_mc_id from table_pengajuan_atestasi where tbpa_status=1)) jml
                union
                SELECT 3 id, 'Perusahaan Di Proses' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbpa_mc_id from table_pengajuan_atestasi where tbpa_status=0)) jml
                union
                SELECT 4 id, 'Perusahaan Batal' judul,
                (SELECT CAST(COUNT(*) AS VARCHAR)
                FROM master_company mc
                WHERE mc.mc_id in (select tbpa_mc_id from table_pengajuan_atestasi where tbpa_status=4)) jml
                ) row order by id";
        $row = $this->db_read->query($sql);
        return $row;
    }

    
}
