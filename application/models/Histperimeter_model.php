<?php
use Carbon\Carbon;
class Histperimeter_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $column_order = array('','mpm_name', 'mpmk_name');
    var $column_search = array('mpm_name', 'mpmk_name');
    var $order = array(''=>'', 'mpm_name'=>'asc', 'mpmk_name'=>'asc');

    var $column_order_lvl = array('','v_mpm_name', 'v_mpml_name', 'v_mpmk_name','v_pic','v_fo');
    var $column_search_lvl = array('v_mpm_name', 'v_mpml_name', 'v_mpmk_name','v_pic','v_fo');
    var $order_lvl = array(''=>'', 'v_mpm_name'=>'asc', 'v_mpml_name'=>'asc', 'v_mpmk_name'=>'asc',
        'v_pic'=>'asc', 'v_fo'=>'asc');

    private function _get_datatables_query($mc_id, $tgl) {
        $this->db_read->select("v_mpm_id, mpm_name, mpmk.mpmk_name, persen_mpm")
        ->from("week_aktivitas_cnt_bymcid_weekmpm('$mc_id','$tgl') a")
        ->join('master_perimeter mpm','mpm.mpm_id=a.v_mpm_id')
        ->join('master_perimeter_kategori mpmk','mpmk.mpmk_id=mpm.mpm_mpmk_id');
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
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $this->db_read->order_by('mpm_name','asc');
        }
    }

    function get_datatables($mc_id, $tgl)  {
        $this->_get_datatables_query($mc_id, $tgl);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered($mc_id, $tgl) {
        $this->_get_datatables_query($mc_id, $tgl);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all($mc_id, $tgl) {
        $this->db_read->select("v_mpm_id, mpm_name, mpmk.mpmk_name, persen_mpm")
        ->from("week_aktivitas_cnt_bymcid_weekmpm('$mc_id','$tgl') a")
        ->join('master_perimeter mpm','mpm.mpm_id=a.v_mpm_id')
        ->join('master_perimeter_kategori mpmk','mpmk.mpmk_id=mpm.mpm_mpmk_id');
        return $this->db_read->count_all_results();
    }

    public function get_persenmonitoring($mc_id, $tgl) {
        $sql="SELECT * FROM pemenuhan_monitoring_bymcidweek('$mc_id','$tgl')";
        $result = $this->db_read->query($sql);
        return $result;
    }

    private function _get_datatables_query_lvl($mc_id, $tgl) {
      $strdate =  Carbon::parse($tgl);
      $startdate = $strdate->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
      $enddate = $strdate->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
      $week =   $startdate.'-'.$enddate;
      $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
    	//var_dump($crweek.$week. Carbon::now());
      //exit();
      if ($week == $crweek ){
        $this->db_read->select("a.v_mpml_id,a.v_mpm_name, a.v_mpml_name, a.v_mpmk_name, a.v_pic, a.v_fo,a.v_pic_nik, a.v_fo_nik,
        a.v_cek, a.v_persen_det, tpcl.tbpc_status as status_tutup, a.v_mr_name")
        ->from("week_historymonitoring_level('$mc_id','$tgl') a")
        ->join("table_perimeter_closed tpcl","tpcl.tbpc_mpml_id=a.v_mpml_id 
            AND tbpc_startdate='$startdate' and tbpc_enddate='$enddate' ", "LEFT");
      } else {
        $this->db_read->select("v_mpml_id,v_mpm_name, v_mpml_name,v_mpmk_name, v_pic,  v_fo ,v_pic_nik, v_fo_nik,
      v_cek, v_persen_det,  status_tutup, mr_name as v_mr_name")
        ->from("report_history_week_view")
        ->where("v_week",$week)
        ->where("v_mc_id",$mc_id);
      }

      //var_dump($_POST['search']['value']);
      //exit;
        $i = 0;
        foreach ($this->column_search_lvl as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }

                if(count($this->column_search_lvl) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

       /* if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_lvl[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_lvl)) {
            $this->db_read->order_by('v_mpm_name','asc');
        }*/
    }

    function get_datatables_lvl($mc_id, $tgl)  {
        $this->_get_datatables_query_lvl($mc_id, $tgl);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    private function _get_datatables_count_query($mc_id, $tgl) {
      $strdate =  Carbon::parse($tgl);
      $startdate = $strdate->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
      $enddate = $strdate->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
      $week =   $startdate.'-'.$enddate;
      $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        //var_dump($crweek.$week. Carbon::now());
      //exit();
      if ($week == $crweek ){
        $this->db_read->select("count(*) as count")
        ->from("week_historymonitoring_level('$mc_id','$tgl') a")
        ->join("table_perimeter_closed tpcl","tpcl.tbpc_mpml_id=a.v_mpml_id 
            AND tbpc_startdate='$startdate' and tbpc_enddate='$enddate' ", "LEFT");
      } else {
        $this->db_read->select("count(*) as count")
        ->from("report_history_week_view")
        ->where("v_week",$week)
        ->where("v_mc_id",$mc_id);
      }

      //var_dump($_POST['search']['value']);
      //exit;
        $i = 0;
        foreach ($this->column_search_lvl as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }

                if(count($this->column_search_lvl) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        /*if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_lvl[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_lvl)) {
            $this->db_read->order_by('v_mpm_name','asc');
        }*/
    }

    function count_filtered_lvl($mc_id, $tgl) {
        $this->_get_datatables_count_query($mc_id, $tgl);
        $query = $this->db_read->get();
        return $query->row()->count;
    }

    public function count_all_lvl($mc_id, $tgl) {
        $this->db_read->select(" a.v_mpml_id, a.v_mpm_name, a.v_mpml_name, a.v_mpmk_name,
        a.v_pic, a.v_fo, a.v_pic_nik, a.v_fo_nik,
        a.v_cek, a.v_persen_det, tpcl.tbpc_status as status")
        ->from("week_historymonitoring_level('$mc_id','$tgl') a");
        return $this->db_read->count_all_results();
    }

    public function get_mpmlbyId($id) {
        $tgl = date('Y-m-d');
        $strdate =  Carbon::parse($tgl);
        $startdate = $strdate->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $enddate = $strdate->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $week =   $startdate.'-'.$enddate;
        $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        
        $this->db_read->select("mc.mc_id, mc.mc_name, mpm.mpm_id, mpm.mpm_name,
            mpml.mpml_id, mpml.mpml_name, mpml.mpml_ket,
            tpcl.tbpc_status as status_tutup")
            ->from("master_company mc")
        ->join("master_region mr","mr.mr_mc_id=mc.mc_id")
        ->join("master_perimeter mpm","mpm.mpm_mr_id=mr.mr_id")
        ->join("master_perimeter_level mpml","mpml.mpml_mpm_id=mpm.mpm_id")
        ->join("table_perimeter_closed tpcl","tpcl.tbpc_mpml_id=mpml.mpml_id 
            AND tbpc_startdate='$startdate' and tbpc_enddate='$enddate' ", "LEFT")
        ->where("mpml.mpml_id",$id);
        $query = $this->db_read->get();
        return $query->row();
    }

    public function get_aktivitasbyId($id) {
        $tgl = date('Y-m-d');
        $strdate =  Carbon::parse($tgl);
        $startdate = $strdate->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $enddate = $strdate->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $week =   $startdate.'-'.$enddate;
        $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        
        $sql="select ta.ta_id, ta.ta_tpmd_id, ta.ta_kcar_id, ta.ta_week,
            taf.taf_date, taf.taf_file, taf_file_tumb, mc_id, mpm_id, mpml_id,
		mcar.mcar_name, mcr.mcr_name,
		(SELECT tbpc_status 
        FROM table_perimeter_closed tpcl 
		WHERE tpcl.tbpc_mpml_id=mpml.mpml_id  
        AND tbpc_startdate='$startdate' and tbpc_enddate='$enddate'
        ORDER BY tbpc_id desc limit 1) as status_tutup
        from transaksi_aktifitas ta
        inner join transaksi_aktifitas_file taf on taf.taf_ta_id=ta.ta_id
        inner join table_perimeter_detail tpmd on tpmd.tpmd_id=ta.ta_tpmd_id
        inner join master_perimeter_level mpml on mpml.mpml_id=tpmd.tpmd_mpml_id
        inner join master_perimeter mpm on mpm.mpm_id=mpml.mpml_mpm_id
        inner join master_company mc on mc.mc_id=mpm.mpm_mc_id
        inner join master_cluster_ruangan mcr on mcr.mcr_id=tpmd.tpmd_mcr_id
        inner join konfigurasi_car kcar on kcar.kcar_mcr_id=mcr.mcr_id
        inner join master_car mcar on mcar.mcar_id=kcar_mcar_id
        where ta.ta_kcar_id=kcar.kcar_id 
		and ta.ta_id=$id ";
        
        $result = $this->db_read->query($sql);
        return $result->result();
    }
}
