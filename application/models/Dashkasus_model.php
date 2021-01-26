<?php
class Dashkasus_model extends CI_Model {
	function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    public function data_chart_kasus() {
        $sql="SELECT * FROM kasus_tanggal_dashboard()";
        $result = $this->db_read->query($sql);
        return $result;
    }
    
    public function data_chart_clusterkasus($id) {
        $sql="SELECT * FROM cluster_kasus_tanggal_dashboard('$id')";
        $result = $this->db_read->query($sql);
        return $result;
    }
    
    public function data_chart_kasus_pertgl() {
        $sql="SELECT * FROM kasus_tanggal_dashboard_pertgl()";
        $result = $this->db_read->query($sql);
        return $result;
    }
    
    public function data_chart_clusterkasus_pertgl($id) {
        $sql="SELECT * FROM cluster_kasus_tanggal_dashboard_pertgl('$id')";
        $result = $this->db_read->query($sql);
        return $result;
    }
}