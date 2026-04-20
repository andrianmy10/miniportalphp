<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('M_auth');
    }

    public function index() {
        // Kalau sudah login, langsung lempar ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard'); 
        }
        $this->load->view('v_login');
    }

    public function login_process() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->M_auth->check_login($username, $password);

        if ($user) {
            $session_data = array(
                'id_user'     => $user->id,
                'username'    => $user->username,
                'id_group'    => $user->id_group,
                'nama_group'  => $user->nama_groups,
                'logged_in'   => TRUE
            );
            $this->session->set_userdata($session_data);
            
            // Logic redirect berdasarkan group
            if ($user->nama_groups == 'Pengadaan') {
                redirect('sp3'); // Controller Daftar SP3
            } else {
                redirect('keuangan'); // Controller Jurnal
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