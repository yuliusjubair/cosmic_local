<?php
class Mperimeter_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    var $table_level = 'master_perimeter_level';
    var $column_order = array('','mr_name', 'mpm_name','mpro_name','mkab_name');
    var $column_search = array('mr_name', 'mpm_name','mpro_name','mkab_name');
    var $order = array(''=>'', 'mr_name'=>'asc', 'mpm_name'=>'asc'
        , 'mpro_name'=>'asc', 'mkab_name'=>'asc');

    var $column_order_lvl = array('','mpml_name', 'mpml_pic_nik','mpml_me_nik','mpml_ket',
        );
    var $column_search_lvl = array('mpml_name', 'mpml_pic_nik','mpml_me_nik','mpml_ket',
        );
    var $order_lvl = array(''=>'', 'mpml_name'=>'asc', 'mpml_pic_nik'=>'asc', 'mpml_me_nik'=>'asc',
        'mpml_ket'=>'asc');

    private function _get_datatables_query($mc_id) {
        $this->db_read->select("mc_name, mr_name, mpm_id, mpm_name, mpro.mpro_name, mkab.mkab_name")
        ->from("master_perimeter mpm")
        ->join('master_region mr', 'mr.mr_id = mpm.mpm_mr_id')
        ->join('master_company mc', 'mc.mc_id = mr.mr_mc_id')
        ->join('master_provinsi mpro', 'mpro.mpro_id = mpm.mpm_mpro_id', 'left')
        ->join('master_kabupaten mkab', 'mkab.mkab_id = mpm.mpm_mkab_id', 'left')
        ->join('master_perimeter_level mpml','mpml.mpml_mpm_id = mpm.mpm_id','left')
        ->join('master_perimeter_kategori mpk','mpk.mpmk_id = mpm.mpm_mpmk_id', 'left')
        ->where('mc.mc_id',$mc_id);
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
        $this->db_read->group_by('mc_name, mr_name, mpm_id, mpm_name, mpro.mpro_name, mkab.mkab_name');
        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $this->db_read->order_by('mpm_name','asc');
        }
    }

    function get_datatables($mc_id)  {
        $this->_get_datatables_query($mc_id);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered($mc_id) {
        $this->_get_datatables_query($mc_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all($mc_id) {
        $this->db_read->select("mc_name, mr_name, mpm_id, mpm_name, mpro.mpro_name, mkab.mkab_name")
        ->from("master_perimeter mpm")
        ->join('master_region mr', 'mr.mr_id = mpm.mpm_mr_id')
        ->join('master_company mc', 'mc.mc_id = mr.mr_mc_id')
        ->join('master_provinsi mpro', 'mpro.mpro_id = mpm.mpm_mpro_id', 'left')
        ->join('master_kabupaten mkab', 'mkab.mkab_id = mpm.mpm_mkab_id', 'left')
        ->join('master_perimeter_level mpml','mpml.mpml_mpm_id = mpm.mpm_id')
        ->join('master_perimeter_kategori mpk','mpk.mpmk_id = mpm.mpm_mpmk_id', 'left')
        ->where('mc.mc_id',$mc_id)
        ->group_by('mc_name, mr_name, mpm_id, mpm_name, mpro.mpro_name, mkab.mkab_name');
        return $this->db_read->count_all_results();
    }

    public function delete_mpml_byid($id) {
        $this->db_cud->where('mpml_id', $id);
        return  $this->db_cud->delete($this->table_level);
    }

    public function count_transaksi_mpml($mpml_id) {
        $this->db_read->select("mpml.mpml_id")
        ->from("master_perimeter_level mpml")
        ->join('table_perimeter_detail tpmd', 'tpmd.tpmd_mpml_id=mpml.mpml_id')
        ->join('transaksi_aktifitas ta', 'ta.ta_tpmd_id=tpmd.tpmd_id')
        ->where('mpml.mpml_id',$mpml_id);
        return $this->db_read->count_all_results();
    }

    private function _get_datatables_query_lvl($mpm_id) {
        $this->db_read->select("mpml.mpml_id, mpml.mpml_name, mpml.mpml_pic_nik, mpml.mpml_me_nik,
                mpml.mpml_ket, userpic.first_name as pic, userfo.first_name as fo,
                (SELECT * FROM cr_bympml(mpml.mpml_id)) v_cr  ")
        ->from("master_perimeter_level mpml")
        ->join('app_users as userpic', 'mpml.mpml_pic_nik = userpic.username','left')
        ->join('app_users as userfo', 'mpml.mpml_me_nik = userfo.username','left')
        ->where('mpml.mpml_mpm_id',$mpm_id);
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

        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_lvl[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_lvl)) {
            $this->db_read->order_by('mpml_name','asc');
        }
    }

    function get_datatables_lvl($mpm_id)  {
        $this->_get_datatables_query_lvl($mpm_id);

        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }

    function count_filtered_lvl($mpm_id) {
        $this->_get_datatables_query_lvl($mpm_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }

    public function count_all_lvl($mpm_id) {
        $this->db_read->select("mpml.mpml_id, mpml.mpml_name, mpml.mpml_pic_nik, mpml.mpml_me_nik,
                mpml.mpml_ket, userpic.first_name as pic, userfo.first_name as fo,
                (SELECT * FROM cr_bympml(mpml.mpml_id)) v_cr  ")
               ->from("master_perimeter_level mpml")
               ->join('app_users as userpic', 'mpml.mpml_pic_nik = userpic.username','left')
               ->join('app_users as userfo', 'mpml.mpml_me_nik = userfo.username','left')
               ->where('mpml.mpml_mpm_id',$mpm_id);
        return $this->db_read->count_all_results();
    }

    //sprint16
    public function delete_perimeter_byid($id) {
        $this->db_cud->where('mpm_id', $id);
        return $this->db_cud->delete('master_perimeter');
    }

    //sprint16
    public function get_perimeter_bymcid($id) {
      $this->db_read->select("mp.*")
      ->from("master_perimeter mp")->where("mp.mpm_mc_id",$id)->order_by("mp.mpm_name");
      $query = $this->db_read->get();
      return $query->result();
    }

    public function get_perimeter_bympmid($id) {
        $this->db_read->select("mp.*")
        ->from("master_perimeter mp")->where("mp.mpm_id",$id);
        $query = $this->db_read->get();
        return $query->row();
    }

    public function perimeterlevel_save($data) {
        $this->db_cud->insert($this->table_level, $data);
        return $this->db_cud->insert_id();
    }
}
