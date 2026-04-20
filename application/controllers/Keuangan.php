<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Wajib login & cek role khusus Keuangan
        if(!$this->session->userdata('logged_in') || $this->session->userdata('nama_group') != 'Keuangan') {
            redirect('auth');
        }
        $this->load->model('M_keuangan');
    }

    // Nanti diisi buat view dashboard keuangan
    public function viewDashboard() {
        $data['title'] = "Mini Portal - Dashboard Keuangan";
        
        // Tarik data hitungan jurnal umum bulan ini
        $data['total_jurnal_bulan'] = $this->M_keuangan->count_jurnal_bulan(date('m'), date('Y'));

        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_dashboard', $data); // Passing datanya ke sini
        $this->load->view('AdminArea/footer');
    }

    // ==========================================
    // MENU JURNAL UMUM
    // ==========================================
    public function jurnalUmum() {
        $data['title'] = "Mini Portal - Jurnal Umum";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_jurnalumum');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_jurnal_umum() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_keuangan->get_jurnal_umum($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nobukti;
            $val[] = $row->tanggal; 
            $val[] = $row->kdperkir;
            $val[] = $row->uraian;
            // KEMBALIKAN KE ANGKA RAW (Tanpa Rp dan tanpa number_format)
            $val[] = $row->debet;  
            $val[] = $row->kredit; 
            $val[] = $row->noref;
            $val[] = $row->nofaktur;
            $val[] = $row->kdbarang;
            $val[] = $row->kdnasabah;
            $val[] = $row->kdproyek;
            $val[] = $row->keterangan;
            $data[] = $val;
        }

        $output = array("data" => $data);
        echo json_encode($output);
    }

    public function jurnalPendapatan() {
        $data['title'] = "Jurnal Pendapatan";
        $data['siswa'] = $this->M_keuangan->get_siswa_list(); // Tarik list siswa

        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_jurnalpendapatan', $data);
        $this->load->view('AdminArea/footer');
    }

    public function ajax_jurnal_pendapatan() {
        $nis = $this->input->post('nis');
        $list = $this->M_keuangan->get_jurnal_pendapatan($nis);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->noinvoice;
            $val[] = $row->tanggal;
            $val[] = $row->uraian;
            $val[] = $row->kdperkir;
            $val[] = $row->nis;
            $val[] = $row->nama;
            $val[] = $row->tagihan; // Kirim angka mentah
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    public function tunggakan() {
        $data['title'] = "Mini Portal - Laporan Tunggakan";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_tunggakan');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_tunggakan() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_keuangan->get_tunggakan($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nis;
            $val[] = $row->nama;
            $val[] = $row->kelas;
            $val[] = $row->tahunajaran;
            $val[] = $row->noinvoice;
            $val[] = $row->uraian;
            $val[] = $row->tagihan; // Raw Number
            $val[] = $row->status_siswa;
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }
}