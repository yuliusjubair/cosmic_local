<?php
class Product_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }
    var $table_product = 'master_layanan_produk';
    public function get_all() {
        $this->db_read->select("mp.*")
        ->from("master_layanan_produk mp")
        ->where("mp.mlp_active", "t")
        ->order_by("mp.mlp_id");
        $query = $this->db_read->get();
        return $query->result();
    }

    public function save($data) {
        $this->db_cud->insert($this->table_product, $data);
        return $this->db_cud->insert_id();
    }

    public function delete_byid($id) {
        $this->db_cud->where('mlp_id', $id);
        return $this->db_cud->delete($this->table_product);
    }

    public function get_byid($id)  {
        $this->db_read->select("mp.*")
            ->from("master_layanan_produk mp")
            ->where('mlp_id',$id);
        $query = $this->db_read->get();

        return $query->row();
    }

    public function update($where, $data) {
        $this->db_cud->update($this->table_product, $data, $where);
        return $this->db_cud->affected_rows();
    }

    public function get_data_bygroupId($group_id) {
        if($group_id==1){
            $this->db_read->select("mp.*")
            ->from("master_layanan_produk mp")
            ->order_by("mp.mlp_id");
        }else{    
            $this->db_read->select("mp.*")
            ->from("master_layanan_produk mp")
            ->where("mp.mlp_group_id", $group_id)
            ->order_by("mp.mlp_id");
        }
        $query = $this->db_read->get();
        return $query->result();
    }

}
