<?php


class Master_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->db_read = $this->load->database('read', TRUE);
        $this->db_cud = $this->load->database('cud', TRUE);
    }

    public function menus($group) {
        $where='';
        if ($group>1 && $group<=10){
            if($group==2){
                $mc_id = $this->ion_auth->user()->row()->mc_id;
                $sql="SELECT mc_flag::int4
                    FROM master_company
                    WHERE mc_id='$mc_id'";
                $mc_flag = (int)$this->db_read->query($sql)->row()->mc_flag;

                $where.=" AND a.menu_id NOT IN (
                        SELECT agnm_menu_id FROM app_group_notmenus
                        WHERE agnm_group_id=$group
                        AND agnm_flag=$mc_flag) ";

            }
            $where.=" AND a.menu_id in (
                        SELECT agm_menu_id
                        FROM app_groups_menus
                        WHERE agm_groups_id=$group
                    ) ";

        }

        $sql="SELECT a.menu_id, a.menu_name, b.nameref, b.imgref, a.menu_url, a.menu_image
              FROM app_menus a
              INNER JOIN
                (SELECT menu_id as idref,menu_name as nameref,menu_image as imgref
                 FROM app_menus
                 WHERE coalesce(menu_id_ref,0)=0) b on a.menu_id_ref=b.idref
        	  WHERE a.menu_id IS NOT NULL
              $where
        	  ORDER BY b.idref, a.menu_id";

              $menu=$this->db_read->query($sql);
              return $menu;
    }

    public function menus_monitoring_usage($group) {
        $where='';
        if ($group>1 && $group<=10){
            if($group==2){
                $mc_id = $this->ion_auth->user()->row()->mc_id;
                $sql="SELECT mc_flag::int4
                    FROM master_company
                    WHERE mc_id='$mc_id'";
                $mc_flag = (int)$this->db_read->query($sql)->row()->mc_flag;

                $where.=" AND a.menu_id NOT IN (
                        SELECT agnm_menu_id FROM app_group_notmenus
                        WHERE agnm_group_id=$group
                        AND agnm_flag=$mc_flag) ";

            }
            $where.=" AND a.menu_id in (
                        SELECT agm_menu_id
                        FROM app_groups_menus
                        WHERE agm_groups_id=$group
                    ) ";

        }

        $sql="SELECT a.menu_id, a.menu_name, b.nameref, b.imgref, a.menu_url, a.menu_image_baru as menu_image
              FROM app_menus a
              INNER JOIN
                (SELECT menu_id as idref,menu_name as nameref,menu_image_baru as imgref
                 FROM app_menus
                 WHERE coalesce(menu_id_ref,0)=0) b on a.menu_id_ref=b.idref
        	  WHERE a.menu_id IS NOT NULL
              $where
        	  ORDER BY b.idref, a.menu_id";

              $menu=$this->db_read->query($sql);
              return $menu;
    }

    public function mst_groups($cari) {
        $sql="SELECT * FROM app_groups
              WHERE UPPER(name) LIKE UPPER('%$cari%')
               ORDER BY id DESC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_group($cari) {
        $sql="SELECT * FROM app_groups
              WHERE UPPER(name) LIKE UPPER('%$cari%')
              AND id!=1
              ORDER BY id DESC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function onegroups($id) {
        $sql="SELECT * FROM app_groups WHERE id=$id";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function save_groups($id, $name, $user) {
        $this->db_cud->trans_start();

        if($id > 0){
            $sql=" UPDATE app_groups
                   SET description='$name',
                    user_update='$user',
                    tgl_update=NOW()
                   WHERE id=$id";
            $result = $this->db_cud->query($sql);
        }else{
            $sql="INSERT INTO app_groups (description, user_insert, tgl_insert)
                VALUES ('$name','$user',NOW())";
            $result = $this->db_cud->query($sql);
        }

        $this->db_cud->trans_complete();

        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return $id;
        }
    }

    public function del_groups($id) {
        $this->db_cud->trans_start();

        $sql="DELETE FROM app_groups WHERE id=$id";
        $result = $this->db_cud->query($sql);

        $this->db_cud->trans_complete();

        if ($this->db_cud->trans_status() === FALSE) {
            $this->db_cud->trans_rollback();
            return "error";
        } else {
            $this->db_cud->trans_commit();
            return "success";
        }
    }

    public function mst_users_group() {
        $sql="SELECT * FROM app_groups
              WHERE id NOT IN (3,4)
              ORDER BY id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

     public function mst_users_group_pic_fo() {
        $sql="SELECT * FROM app_groups
              WHERE id IN (3,4)
              ORDER BY id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_company($cari) {
        $sql="SELECT * FROM master_company
              WHERE UPPER(mc_name) LIKE UPPER('%$cari%')
              AND mc_level=1
              ORDER BY mc_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function one_company($id) {
        $sql="SELECT * FROM master_company WHERE mc_id='$id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function company($group, $sektor, $mc_id) {
        if($group> 1 && $group < 6){
            if($group==5){
                $sektor=" AND ms_id='$sektor'";
            }else{
                $sektor=" AND mc_id='$mc_id'";
            }
        }else{
            $sektor="";
        }
        $sql= "SELECT mc_id, mc_code, mc_name, ms_id, ms_name
              FROM master_company mc
              LEFT JOIN master_sektor ms ON ms.ms_id=mc.mc_msc_id
		      /*WHERE ms_type='CCOVID'*/ WHERE 1=1 ";
        $sql.= $sektor;
        $sql.= " ORDER BY mc_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function company_bygroupcompany($group_company) {
        $str_groupcompany="";
        if(isset($group_company)){
            if($group_company==1){
                $str_groupcompany=" AND mc_flag=1";
            }else if($group_company==2){
                $str_groupcompany=" AND mc_flag=2";
            }
        }
        $sql= "SELECT mc_id, mc_code, mc_name, ms_id, ms_name
              FROM master_company mc
              LEFT JOIN master_sektor ms ON ms.ms_id=mc.mc_msc_id
		      /*WHERE ms_type='CCOVID'*/ WHERE 1=1 ";
        $sql.= $str_groupcompany;
        $sql.= " ORDER BY mc_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_status_pegawai($cari) {
        $sql="SELECT * FROM master_status_pegawai
              WHERE UPPER(msp_name) LIKE UPPER('%$cari%')
              ORDER BY msp_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function one_status_pegawai($id) {
        $sql="SELECT * FROM master_status_pegawai WHERE msp_id='$id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_status_kasus($cari) {
        $sql="SELECT * FROM master_status_kasus
              WHERE UPPER(msk_name) LIKE UPPER('%$cari%')
              ORDER BY msk_id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

     public function mst_kriteria_orang($cari) {
        $sql="SELECT * FROM master_kriteria_orang
              WHERE UPPER(jenis) LIKE UPPER('%$cari%')
              ORDER BY id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function one_status_kasus($id) {
        $sql="SELECT * FROM master_status_kasus WHERE msk_id='$id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_provinsi($cari) {
        $sql="SELECT * FROM master_provinsi
              WHERE UPPER(mpro_name) LIKE UPPER('%$cari%')
              ORDER BY mpro_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function one_provinsi($id) {
        $sql="SELECT * FROM master_provinsi WHERE mpro_id='$id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_kabupaten($cari) {
        $sql="SELECT * FROM master_kabupaten
              WHERE UPPER(mkab_name) LIKE UPPER('%$cari%')
              ORDER BY mkab_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function one_kabupaten($id) {
        $sql="SELECT * FROM master_kabupaten WHERE mkab_id='$id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function kabupaten($provinsi_id) {
        $sql="SELECT * FROM master_kabupaten
              WHERE mkab_mpro_id=$provinsi_id
              ORDER BY mkab_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_perimeter_kategori($cari) {
        $sql="SELECT * FROM master_perimeter_kategori
              WHERE UPPER(mpmk_name) LIKE UPPER('%$cari%')
              ORDER BY mpmk_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function search_region($id_company,$cari) {
        $sql="SELECT * FROM master_region
              WHERE UPPER(mr_name) LIKE UPPER('%$cari%') and mr_mc_id = '$id_company'
              ORDER BY mr_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function list_week() {
        $sql="SELECT * , CONCAT(v_awal,' s/d ', v_akhir) tgl
              FROM list_aktivitas_week()
              ORDER BY v_rownum DESC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function master_sosialisasi_kategori($cari) {
        $sql="SELECT * FROM master_sosialisasi_kategori
              WHERE UPPER(mslk_name) LIKE UPPER('%$cari%')
              ORDER BY mslk_name ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function cluster_ruangan() {
        $sql="SELECT * FROM master_cluster_ruangan
              ORDER BY mcr_id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function mst_perimeter($mpm_id) {
        $sql="SELECT * FROM master_perimeter
              WHERE mpm_id = $mpm_id
              ORDER BY mpm_id ASC limit 1";
        $result = $this->db_read->query($sql);
        return $result;
    }



    public function one_groups($group) {
        $sql="SELECT * FROM app_groups
              WHERE id=$group";
        $result = $this->db_read->query($sql);
        return $result->row();
    }

    public function mst_fasilitas_rumah() {
        $result = $this->db_cud->get('master_fasilitas_rumah');
        return $result;
    }

    public function mst_sektor($cari) {
        $sql="SELECT * FROM master_sektor
              WHERE UPPER(ms_name) LIKE UPPER('%$cari%')
              AND ms_type='CCOVID'
              ORDER BY ms_id DESC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    //sprint18
    public function mst_jenis_industri($cari) {
        $sql="SELECT * FROM master_sektor
              WHERE UPPER(ms_name) LIKE UPPER('%$cari%')
              AND ms_type='NONBUMN'
              ORDER BY ms_name";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function count_images()
    {
        $now = date('Y-m-d');
        $this->db->select('*');
        $this->db->where('status','Aktif');
        $this->db->where("end_date >= '$now'");
        $q=$this->db->get('master_pengumuman');

        if($q)
        {
            return $q->num_rows();
        }
        else
        {
            return false;
        }
    }


    //FUNCTION TO GET IMAGES FROM DATABASE
    public function gallery()
    {
        $now = date('Y-m-d');
        $this->db->select('*');
        $this->db->where('status','Aktif');
        $this->db->where("end_date >= '$now'");
        $q= $this->db->get('master_pengumuman');


        if($q->num_rows()>0)
        {
            return $q->result();
        }
        else
        {
            return false;
        }
    }

    public function max_id_company(){
        $this->db->select_max('mc_id');
        $q = $this->db->get('master_company');
        return $q->row()->mc_id;
    }

    public function mst_perimeter_byid_mcid($mc_id, $mpm_id) {
        $sql="SELECT *, COALESCE(master_perimeter_kategori.mpmk_id ,0) v_jml FROM master_perimeter
            LEFT JOIN master_company on master_company.mc_id = master_perimeter.mpm_mc_id
            LEFT JOIN master_perimeter_kategori on master_perimeter_kategori.mpmk_id = master_perimeter.mpm_mpmk_id
              WHERE mpm_id = $mpm_id and mpm_mc_id='$mc_id'
              ORDER BY mpm_id ASC limit 1";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function get_tbpsa_by_id($id){
        $this->db->select('*');
        $this->db->where('tbspa_id',$id);
        $this->db->join('table_pengajuan_atestasi', 'tbpa_id = tbspa_tbpa_id');
        $q = $this->db->get('table_status_pengajuan_atestasi');
        return $q;
    }

    public function master_status_sertifikasi($cari) {
        $sql="SELECT * FROM master_status_sertifikasi
              WHERE UPPER(status) LIKE UPPER('%$cari%')
              ORDER BY id ASC";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function get_picfo_bymcid($mc_id){
        $sql="SELECT first_name FROM app_users au
                INNER JOIN app_users_groups aug ON aug.user_id=au.id
                WHERE aug.group_id in (3,4)
                AND au.mc_id='$mc_id'";
        $result = $this->db_read->query($sql);
        return $result;
    }    

    public function perimeter_bymcid($mc_id) {
        $sql="SELECT mr_id, mr_id, mpm_id, mpm_name FROM master_region mr
                INNER JOIN master_perimeter mpm ON mpm.mpm_mr_id=mr.mr_id
                WHERE mpm.mpm_id IN (
                SELECT mpml_mpm_id FROM master_perimeter_level
                )
                AND mpm.mpm_mc_id='$mc_id'";
        $result = $this->db_read->query($sql);
        return $result;
    }

    public function get_total_user_login(){
        $sql="SELECT count(*) as count FROM app_users WHERE flag_login=1";
        $result = $this->db_read->query($sql);
        return $result->row()->count;
    }

    public function get_last_login($id){
        $sql="SELECT id FROM app_users WHERE id='$id'";
        $result = $this->db_read->query($sql);
        return $result->row()->count;
    }

	public function get_bumn($id){
        $sql="select count(mc_id) as count from app_users WHERE mc_id='$id' group by mc_id";
        $result = $this->db_read->query($sql);
        return $result->row()->count;
    }   

    public function get_fo(){
    	$sql="select count(a.id) as count from app_users a inner join app_users_groups b ON a.id = b.user_id inner join app_groups c ON b.group_id = c.id WHERE b.group_id='4' AND a.flag_login = 1"; 
        $result = $this->db_read->query($sql);
        return $result->row()->count;
    } 

    public function get_pic(){
    	$sql="select count(a.id) as count from app_users a inner join app_users_groups b ON a.id = b.user_id inner join app_groups c ON b.group_id = c.id WHERE b.group_id='3' AND a.flag_login = 1"; 
        $result = $this->db_read->query($sql);
        return $result->row()->count;
    } 

    public function get_chart_pic($opt = 3){
    	$sql="SELECT a.tgl, count(c.group_id) FROM grafik_monitoring a INNER JOIN app_users b ON a.user_id = b.id INNER JOIN app_users_groups c ON a.user_id = c.user_id INNER JOIN app_groups d ON c.group_id =  d.id WHERE a.tgl > ('now'::date - '14 days'::interval)::date AND c.group_id = $opt GROUP BY a.tgl, c.group_id";
        $result = $this->db_read->query($sql);
        return json_encode($result->result());
    } 

    public function set_log_login($id){
    	$now = date('Y-m-d');
    	$sql="select count(user_id) from grafik_monitoring WHERE user_id='$id' and tgl='$now'"; 
        $result = $this->db_read->query($sql);
      	$count = $result->row()->count;

      	if ($count == 0){
	    	$sql="INSERT INTO grafik_monitoring (tgl, user_id) VALUES ('$now', '$id')";
	        $this->db_cud->query($sql);      	  
      	}
    }
}
