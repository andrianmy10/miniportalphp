<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model {

    public function check_login($username, $password) {
        $this->db->select('tb_users.*, tb_groups.nama_groups');
        $this->db->from('tb_users');
        $this->db->join('tb_groups', 'tb_users.id_group = tb_groups.id');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password)); // Mengikuti request pakai MD5
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }
}