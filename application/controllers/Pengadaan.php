<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengadaan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged_in') || $this->session->userdata('nama_group') != 'Pengadaan') {
            redirect('auth');
        }
        $this->load->model('M_pengadaan');
    }

    public function viewDashboard() {
        $data['title'] = "Mini Portal - Dashboard Pengadaan";

        $data['total_tahun'] = $this->M_pengadaan->count_sp3_tahun(date('Y'));
        $data['total_bulan'] = $this->M_pengadaan->count_sp3_bulan(date('m'), date('Y'));
        $data['total_hari']  = $this->M_pengadaan->count_sp3_hari(date('Y-m-d'));
        $data['recent_sp3']  = $this->M_pengadaan->get_recent_sp3(10); // Ambil 10 data

        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_dashboard', $data); 
        $this->load->view('AdminArea/footer');
    }

    public function index() {
        $data['title'] = "Mini Portal - Daftar SP3";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_sp3');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_list_sp3() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_pengadaan->get_sp3_data($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $sp3) {
            $row = array();
            $row[] = $sp3->nosp3; 
            $row[] = $sp3->unit;
            $row[] = $sp3->proyek;
            $row[] = $sp3->pemohon;
            $row[] = $sp3->pemohontanggal;
            $data[] = $row;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }
}