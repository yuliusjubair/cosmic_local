<?php


class Admin_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    
    var $column_order= array('first_name','username');
    var $column_search = array('first_name','username');
    var $order = array('first_name'=>'asc','username'=>'asc');
    
    var $column_order_groups = array('name','description');
    var $column_search_groups = array('name','description');
    var $order_groups = array('name'=>'asc','description'=>'asc');
    
    var $column_order_musers = array('v_first_name','v_username');
    var $column_search_musers = array('v_first_name','v_username');
    var $order_musers = array('v_first_name'=>'asc','v_username'=>'asc');
    
    private function _get_datatables_query($group_company) {
        $this->db_read->select("au.id, au.first_name, au.username")
        ->from("app_users au")
        ->join('app_users_groups aug', 'aug.user_id = au.id')
        ->join('master_company mc', 'mc.mc_id = au.mc_id')
        ->join('master_sektor ms', 'ms.ms_id = mc.mc_msc_id')
        ->where('aug.group_id',2)
        ->where('mc.mc_level',1);
        if($group_company==1){
            $this->db_read->where('mc.mc_flag', $group_company);
            $this->db_read->where('ms.ms_type', 'CCOVID');
        }else if($group_company==2){
            $this->db_read->where('mc.mc_flag', $group_company);
            $this->db_read->where('ms.ms_type', 'NONBUMN');
        }
        
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
            $this->db_read->order_by('first_name','asc');
        }
    }
    
    function get_datatables($group_company) {
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
        $this->db_read->select("au.id, au.first_name, au.username")
        ->from("app_users au")
        ->join('app_users_groups aug', 'aug.user_id = au.id')
        ->join('master_company mc', 'mc.mc_id = au.mc_id')
        ->join('master_sektor ms', 'ms.ms_id = mc.mc_msc_id')
        ->where('aug.group_id',2)
        ->where('mc.mc_level',1);
        if($group_company==1){
            $this->db_read->where('mc.mc_flag', $group_company);
            $this->db_read->where('ms.ms_type', 'CCOVID');
        }else if($group_company==2){
            $this->db_read->where('mc.mc_flag', $group_company);
            $this->db_read->where('ms.ms_type', 'NONBUMN');
        }
        return $this->db_read->count_all_results();
    }
    
    function data_users($cari){
        $sql="SELECT *, au.id as id_user  FROM app_users au
                inner join app_users_groups aug on aug.user_id=au.id
                inner join app_groups ag on ag.id=aug.group_id
              WHERE (au.nama) like UPPER('%$cari%') " ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }
    
    function data_one_users($id){
        $sql="SELECT * FROM app_users au
                inner join app_users_groups aug on aug.user_id=au.id
                inner join app_groups ag on ag.id=aug.group_id
              WHERE au.id= $id" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }
    
    function data_groups(){
        $sql="SELECT * FROM app_groups" ;
        $retdb=$this->db_read->query($sql);
        return $retdb;
    }
    
    public function reset_byid($where, $data) {
        $this->db->update('app_users', $data, $where);
        return $this->db->affected_rows();
    }
    
    private function _get_datatables_query_groups() {
        $this->db_read->select("name, description, c, r, u, d")
        ->from("app_groups");
        $i = 0;
        foreach ($this->column_search_groups as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_groups) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }
        
        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_groups)) {
            $this->db_read->order_by('name','asc');
        }
    }
    
    function get_datatables_groups()  {
        $this->_get_datatables_query_groups();
        
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db->get();
            return $query->result();
    }
    
    function count_filtered_groups() {
        $this->_get_datatables_query_groups();
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all_groups() {
        $this->db_read->select("name, description, c, r, u, d")
        ->from("app_groups");
        return $this->db_read->count_all_results();
    }
    
    private function _get_datatables_query_musers() {
        $this->db_read->select(" * FROM list_manage_user() ");
        $i = 0;
        foreach ($this->column_search_musers as $item) {
            if($_POST['search']['value']) {
                if($i===0) {
                    $this->db_read->group_start();
                    $this->db_read->like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                } else {
                    $this->db_read->or_like('LOWER(' .$item. ')', strtolower($_POST['search']['value']));
                }
                if(count($this->column_search_musers) - 1 == $i) {
                    $this->db_read->group_end();
                }
            }
            $i++;
        }
        
        if(isset($_POST['order'])) {
            $this->db_read->order_by(
                $this->column_order_musers[$_POST['order']['0']['column']],
                $_POST['order']['0']['dir']);
        } else if(isset($this->order_musers)) {
            $this->db_read->order_by('v_first_name','asc');
        }
    }
    
    function get_datatables_musers()  {
        $this->_get_datatables_query_musers();
        
        if($_POST['length'] != -1)
            $this->db_read->limit($_POST['length'], $_POST['start']);
            $query = $this->db_read->get();
            return $query->result();
    }
    
    function count_filtered_musers() {
        $this->_get_datatables_query_musers();
        $query = $this->db_read->get();
        return $query->num_rows();
    }
    
    public function count_all_musers() {
        $this->db_read->select(" * FROM list_manage_user()");
        return $this->db->count_all_results();
    }
    
    public function count_username($username) {
        $sql= "SELECT  * FROM app_users WHERE username='$username'";
        $result = $this->db_read->query($sql);
        return $result->result();
    }
    
    public function save_users($id, $username,
        $password, $name, $user_id, $mc_id, $ms_id, $groups){
        
        $this->db_cud->trans_start();
            if($password ==''){
                $sql_update_pass = '';
            }else{
                $password = password_hash($password,true);
                $sql_update_pass = "password = '$password', ";
            }
            
            if($id > 0){
                $insert_id = $id;
                $sql_users="UPDATE app_users 
                            SET 
                                username = '$username',
                                $sql_update_pass
                                first_name = '$name',
                                mc_id = '$mc_id',
                                msc_id = '$ms_id',
                                user_insert = $user_id,
                                date_insert = NOW()
                            WHERE id = $insert_id ";
                $this->db_cud->query($sql_users);   
            }else{
                $sql_users="INSERT INTO app_users(username, password, active,
                    first_name, user_insert, date_insert, mc_id, msc_id)
                VALUES  ('$username', '$password', 1::BIT,
                    '$name', $user_id, NOW(), '$mc_id', '$ms_id') ";
                $this->db_cud->query($sql_users);   
    
                $insert_id = $this->db_cud->insert_id('app_users_id_seq');
            }
        $this->db_cud->trans_complete();
        
        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return $insert_id;
        }
    }
    
    public function insert_users_groups($user_idx, $groups){
        $this->db_cud->trans_start();
       
        $sql_user_groups="INSERT INTO app_users_groups(user_id, group_id)
                VALUES ($user_idx, $groups)";
        $this->db_cud->query($sql_user_groups);
        
        $this->db_cud->trans_complete();
        
        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }
    
    public function update_users_groups($user_idx, $groups){
        $this->db_cud->trans_start();
        
        $sql_user_groups="UPDATE app_users_groups
                            SET group_id = $groups
                            WHERE user_id = $user_idx";
        $this->db_cud->query($sql_user_groups);
        
        $this->db_cud->trans_complete();
        
        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }
    
    public function getmanageusers_byid($id) {
        $sql="SELECT * FROM list_manage_user()
                WHERE v_user_id=$id" ;
        $retdb = $this->db_read->query($sql);
        return $retdb;
    }
}
