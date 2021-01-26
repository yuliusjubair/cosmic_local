<?php
class Salah_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $column_order = array('','z_mc','z_mr','z_mpm','z_mpml',
        'z_mpml','z_pic','z_fo');
    var $column_search = array('z_mc','z_mr','z_mpm','z_mpml',
        'z_mpml','z_pic','z_fo');
    var $order = array(''=>'','z_mc'=>'asc','z_mr'=>'asc','z_mpm'=>'asc',
        'z_mpml'=>'asc','z_pic'=>'asc','z_fo'=>'asc');

    var $column_order_cluster = array('','z_mc','z_mr','z_mpm','z_mpml',
        'z_mpml','z_pic','z_fo');
    var $column_search_cluster = array('z_mc','z_mr','z_mpm','z_mpml',
        'z_mpml','z_pic','z_fo');
    var $order_cluster = array(''=>'','z_mc'=>'asc','z_mr'=>'asc','z_mpm'=>'asc',
        'z_mpml'=>'asc','z_pic'=>'asc','z_fo'=>'asc');

    private function _get_datatables_query($group_company) {
      if($group_company==1 ){
        $string = "listsalah_picfosama()";
      } else if ($group_company==2){
        $string = "listsalah_picfosama_nonbumn()";
      } else {
        $string = "listsalah_picfosama_semua()";
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
            $this->db_read->order_by('z_mc','asc');
        }
    }

    function get_datatables($group_company)  {
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
        $string = "listsalah_picfosama()";
      } else if ($group_company==2){
        $string = "listsalah_picfosama_nonbumn()";
      } else {
        $string = "listsalah_picfosama_semua()";
      }
        $this->db_read->from($string);
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_cluster($id) {
        $this->db_read->from("cluster_listsalah_picfosama('$id')");
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
        } else if(isset($this->order)) {
            $this->db_read->order_by('z_mc','asc');
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
        $this->_get_datatables_query($id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_cluster($id) {
        $this->db_read->from("cluster_listsalah_picfosama('$id')");
        return $this->db_read->count_all_results();
    }
}
