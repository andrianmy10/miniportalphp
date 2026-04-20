<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    public function check_login($username, $password) {
        $this->db->select('tb_users.*, tb_groups.nama_groups, tb_pegawai.nama, tb_pegawai.jk');
        $this->db->from('tb_users');
        $this->db->join('tb_groups', 'tb_users.id_group = tb_groups.id');
        $this->db->join('tb_pegawai', 'tb_users.id = tb_pegawai.id_user', 'left'); 
        $this->db->where('tb_users.username', $username);
        $this->db->where('tb_users.password', md5($password)); 
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}