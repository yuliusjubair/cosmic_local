<?php
class Profile_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $table = 'master_company';
    var $table_user = 'app_users';
    var $column_search = array('v_mc_name');
    var $column_search_user = array('v_first_name','v_username','v_group_name','v_mpm');
    var $column_order_user = array('v_first_name'); 
    
    private function _getbumn_datatables_query($mc_id) {
        $this->db_read->select('mc.mc_id, mc.mc_name, mc.mc_foto, au.id, au.username, au.first_name')
        ->from('master_company mc')
        ->join('app_users au', 'au.username = mc.mc_code AND au.mc_id=mc.mc_id')
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
    }
    
    function getbumn_datatables($mc_id)  {
        $this->_getbumn_datatables_query($mc_id);
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }
    
    function countbumn_filtered($mc_id) {
        $this->_getbumn_datatables_query($mc_id);
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function countbumn_all($mc_id) {
        $this->db_read->select('mc.mc_id, mc.mc_name, mc.mc_foto, au.id, au.username, au.first_name')
        ->from('master_company mc')
        ->join('app_users au', 'au.username = mc.mc_code AND au.mc_id=mc.mc_id')
        ->where('mc.mc_id',$mc_id);
        return $this->db_read->count_all_results();
    }
    
    public function getbumn_byid($mc_id) {
        $this->db_read->select('mc_id, mc_code, mc_name, mc_foto');
        $this->db_read->from($this->table);
        $this->db_read->where('mc_id',$mc_id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function userbumn_bymcid($mc_id) {
        $this->db_read->select('mc.mc_id, mc.mc_name, mc.mc_foto, au.id user_id, au.username, au.first_name, au.foto')
        ->from('master_company mc')
        ->join('app_users au', 'au.username = mc.mc_code AND au.mc_id=mc.mc_id')
        ->join('app_users_groups aug', 'aug.user_id = au.id')
        ->join('app_groups ag', 'ag.id = aug.group_id')
        ->where('mc.mc_id',$mc_id)
        ->where('ag.id',2);
        $query = $this->db_read->get();
        return $query->result();
    }
    
    public function bumnupdate($where, $data) {
        $this->db_cud->update($this->table, $data, $where);
        return $this->db_cud->affected_rows();
    }
    
    private function _get_datatables_query($mc_id) {
        $this->db_read->select('*');
        $this->db_read->from("user_bymcid('$mc_id')");
        $i = 0;
        foreach ($this->column_search_user as $item) {
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
        $this->db_read->order_by('v_group_id','asc');
        $this->db_read->order_by('v_first_name','asc');
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
        $this->db_read->select('*');
        $this->db_read->from("user_bymcid('$mc_id')");
        return $this->db_read->count_all_results();
    }
    
    public function get_byid($id) {
        $this->db_read->select('app_users.id, username, first_name, email, mc_id, no_hp, divisi, foto, group_id');
        $this->db_read->from($this->table_user);
        $this->db_read->join('app_users_groups', 'app_users_groups.user_id = app_users.id');
        $this->db_read->join('app_groups', 'app_groups.id = app_users_groups.group_id');
        $this->db_read->where('app_users.id',$id);
        $query = $this->db_read->get();
        return $query->row();
    }
    
    public function update($where, $data) {
        $this->db_cud->update($this->table_user, $data, $where);
        return $this->db_cud->affected_rows();
    }

    public function userbumn_bymcid_and_role($mc_id, $id_role) {
        $this->db_read->select('mc.mc_id, mc.mc_name, mc.mc_foto, au.id user_id, au.username, au.first_name, au.foto')
            ->from('master_company mc')
            ->join('app_users au', 'au.mc_id=mc.mc_id')
            ->join('app_users_groups aug', 'aug.user_id = au.id')
            ->join('app_groups ag', 'ag.id = aug.group_id')
            ->where('mc.mc_id',$mc_id)
            ->where('ag.id',$id_role);
        $query = $this->db_read->get();
        return $query->result();
    }

    public function deleteuser_byid($id) {
        $this->db_cud->where('id', $id);
        return $this->db_cud->delete('app_users');
    }
    
    public function deleteusergroup_byuserid($id) {
        $this->db_cud->where('user_id', $id);
        return $this->db_cud->delete('app_users_groups');
    }

    function get_perimeterbynik($nik){
        return $this->db_read->query("SELECT * FROM mpm_bynik('$nik') where v_mpm IS NOT NULL");
    }
}