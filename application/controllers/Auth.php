<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            if ($this->session->userdata('nama_group') == 'Pengadaan') {
                redirect('pengadaan/dashboard'); 
            } else {
                redirect('keuangan'); 
            }
        }
        $this->load->view('v_login');
    }

    public function login_process() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->M_auth->check_login($username, $password);

        if ($user) {
            $prefix = '';
            if (strtolower($user->jk) == 'l') {
                $prefix = 'Tn. ';
            } elseif (strtolower($user->jk) == 'p') {
                $prefix = 'Ny. ';
            }

            $nama_asli = !empty($user->nama) ? $user->nama : $user->username;
            $nama_lengkap = !empty($user->nama) ? $prefix . $user->nama : $user->username;

            $session_data = array(
                'id_user'      => $user->id,
                'username'     => $user->username,
                'nama_lengkap' => $nama_lengkap, 
                'nama_asli'    => $nama_asli,    
                'id_group'     => $user->id_group,
                'nama_group'   => $user->nama_groups,
                'logged_in'    => TRUE
            );
            $this->session->set_userdata($session_data);
            
            if ($user->nama_groups == 'Pengadaan') {
                redirect('pengadaan/dashboard'); 
            } else {
                redirect('keuangan/dashboard'); 
            }
        } else {
            $this->session->set_flashdata('error', 'Username atau Password salah!');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}