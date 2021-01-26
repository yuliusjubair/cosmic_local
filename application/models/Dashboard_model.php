<?php
use Carbon\Carbon;

class Dashboard_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $column_order = array('','v_mc_name','v_persentase','v_belum_isi',
    'v_sektor');
    var $column_search = array('v_mc_name','v_belum_isi','v_sektor');
    var $order = array(''=>'','v_mc_name'=>'asc','v_persentase'=>'desc',
        'v_belum_isi'=>'asc','v_sektor'=>'asc');

    var $column_order_jml = array('v_judul','v_jml');
    var $column_search_jml = array('v_judul');
    var $order_jml = array('v_judul'=>'asc');

    var $column_order_mpm = array('','v_mc_name', 'v_ms_name');
    var $column_search_mpm = array('v_mc_name', 'v_ms_name');
    var $order_mpm = array(''=>'','v_mc_name'=>'asc', 'v_ms_name'=>'asc');

    var $column_order_cluster = array('','v_mc_name','v_persentase','v_belum_isi',
        'v_sektor');
    var $column_search_cluster = array('v_mc_name','v_belum_isi','v_sektor');
    var $order_cluster = array(''=>'','v_mc_name'=>'asc','v_persentase'=>'desc',
        'v_belum_isi'=>'asc','v_sektor'=>'asc');

    private function _get_datatables_query($group_company) {

      if($group_company==1 ){
        $string = "dashboard_protokol()";
      } else if ($group_company==2){
        $string = "dashboard_protokol_nonbumn()";
      } else {
        $string = "dashboard_protokol_semua()";
      }

        $this->db_read->from($string);

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
            $this->db_read->order_by('v_mc_name','asc');
        }
    }

    function get_datatables($group_company) {
      //var_dump($this->_get_datatables_query($group_company));
      //exit;
        $this->_get_datatables_query($group_company);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
        return $query->result();
    }

    function count_filtered($group_company) {
        $this->_get_datatables_query($group_company);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all($group_company) {

        if($group_company==1 ){
          $string = "dashboard_protokol()";
        } else if ($group_company==2){
          $string = "dashboard_protokol_nonbumn()";
        } else {
          $string = "dashboard_protokol_semua()";
        }


        $this->db->from($string);
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_jml() {
        $this->db_read->from("dashboard_jml()");
        $i = 0;
        foreach ($this->column_search_jml as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like($item, $_POST['search']['value']);
                } else {
                    $this->db_read->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search_jml) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by($this->column_order_jml[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order_jml)) {
            $this->db_read->order_by(key($this->order_jml), $order[key($this->order_jml)]);
        }
    }

    function get_datatables_jml() {
        $this->_get_datatables_query_jml();
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_jml() {
        $this->_get_datatables_query_jml();
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_jml() {
        $this->db_read->from("dashboard_jml()");
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_mpm() {
        $this->db_read->from("dashboard_perimeter()");
        $i = 0;
        foreach ($this->column_search_mpm as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }

                if(count($this->column_search_mpm) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_mpm[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_mpm)) {
            $this->db_read->order_by('v_mc_name','asc');
        }
    }

    function get_datatables_mpm() {
        $this->_get_datatables_query_mpm();

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_mpm() {
        $this->_get_datatables_query_mpm();
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_mpm() {
        $this->db_read->from("dashboard_perimeter()");
        return $this->db_read->count_all_results();
    }

    public function week_protokol($mc_id) {
        $this->db_read->select('v_cnt');
        $this->db_read->from("alertweek_protokol('$mc_id')");
        $query = $this->db_read->get();
        return $query->row();
    }

    public function week_sosialisasi($mc_id) {
        $db_read = $this->load->database('read', TRUE);
        $this->db_read->select('v_cnt');
        $this->db_read->from("alertweek_sosialisasi('$mc_id')");
        $query = $this->db_read->get();
        return $query->row();
    }

    public function week_kasus($mc_id) {
        $this->db_read->select('v_cnt');
        $this->db_read->from("alertweek_kasus('$mc_id')");
        $query = $this->db_read->get();
        return $query->row();
    }

    private function _get_datatables_query_cluster($id) {
        $this->db_read->from("cluster_dashboard_protokol('$id')");
        $i = 0;
        foreach ($this->column_search_cluster as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }

                if(count($this->column_search_cluster) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_cluster[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_cluster)) {
            $this->db_read->order_by('v_mc_name','asc');
        }
    }

    function get_datatables_cluster($id)  {
        $this->_get_datatables_query_cluster($id);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_cluster($id) {
        $this->_get_datatables_query_cluster($id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_cluster($id) {
        $this->db_read->from("cluster_dashboard_protokol('$id')");
        return $this->db_read->count_all_results();
    }

    public function perubahan_week_kasus($mc_id){
        $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $this->db_cud->trans_start();

        $sql="SELECT * FROM transaksi_status_perubahan
              WHERE tsp_mc_id='$mc_id' AND tsp_periode='$crweek'";
        $row_tsp=$this->db->query($sql)->num_rows();

        if($row_tsp > 0){
            $sql_u=" UPDATE transaksi_status_perubahan
                   SET tsp_kasus=1,
                       tsp_update_kasus=NOW()
                   WHERE tsp_mc_id='$mc_id'
                   AND tsp_periode='$crweek'";
            $result = $this->db_cud->query($sql_u);
        }else{
            $sql_i="INSERT INTO transaksi_status_perubahan
                    (tsp_mc_id, tsp_periode, tsp_kasus, tsp_update_kasus)
                    VALUES
                    ('$mc_id','$crweek', 1, NOW())";
            $result = $this->db_cud->query($sql_i);
        }

        $this->db_cud->trans_complete();

        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }

    public function perubahan_week_protokol($mc_id){
        $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $this->db_cud->trans_start();

        $sql="SELECT * FROM transaksi_status_perubahan
              WHERE tsp_mc_id='$mc_id' AND tsp_periode='$crweek'";
        $row_tsp=$this->db->query($sql)->num_rows();

        if($row_tsp > 0){
            $sql_u=" UPDATE transaksi_status_perubahan
                   SET tsp_protokol=1,
                       tsp_update_protokol=NOW()
                   WHERE tsp_mc_id='$mc_id'
                   AND tsp_periode='$crweek'";
            $result = $this->db_cud->query($sql_u);
        }else{
            $sql_i="INSERT INTO transaksi_status_perubahan
                    (tsp_mc_id, tsp_periode, tsp_protokol, tsp_update_protokol)
                    VALUES
                    ('$mc_id','$crweek', 1, NOW())";
            $result = $this->db_cud->query($sql_i);
        }

        $this->db_cud->trans_complete();

        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }

    public function perubahan_week_sosialisasi($mc_id){
        $crweek = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d').'-'.Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
        $this->db_cud->trans_start();

        $sql="SELECT * FROM transaksi_status_perubahan
              WHERE tsp_mc_id='$mc_id' AND tsp_periode='$crweek'";
        $row_tsp=$this->db->query($sql)->num_rows();

        if($row_tsp > 0){
            $sql_u=" UPDATE transaksi_status_perubahan
                   SET tsp_sosialisasi=1,
                       tsp_update_sosialisasi=NOW()
                   WHERE tsp_mc_id='$mc_id'
                   AND tsp_periode='$crweek'";
            $result = $this->db_cud->query($sql_u);
        }else{
            $sql_i="INSERT INTO transaksi_status_perubahan
                    (tsp_mc_id, tsp_periode, tsp_sosialisasi, tsp_update_sosialisasi)
                    VALUES
                    ('$mc_id','$crweek', 1, NOW())";
            $result = $this->db_cud->query($sql_i);
        }

        $this->db_cud->trans_complete();

        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }

}
