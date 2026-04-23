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

    // ==========================================
    // MENU DATA KODE BARANG
    // ==========================================
    public function kodeBarang() {
        $data['title'] = "Data Kode Barang";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_kodebarang');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_kode_barang() {
        $list = $this->M_pengadaan->get_kode_barang();
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->idbarang;     // 0
            $val[] = $row->nama_barang;  // 1
            $val[] = $row->spesifikasi;  // 2
            $val[] = $row->satuan;       // 3
            $val[] = $row->ukuran;       // 4
            $val[] = $row->keterangan;   // 5
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    // ==========================================
    // MENU STOK GUDANG
    // ==========================================
    public function stokGudang() {
        $data['title'] = "Data Stok Gudang";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_stokgudang');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_stok_gudang() {
        $list = $this->M_pengadaan->get_stok_gudang();
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->kodebarang;  // 0
            $val[] = $row->namabarang;  // 1
            $val[] = $row->spesifikasi; // 2
            
            // Format angka buat stok biar enak dibaca (opsional, tp berguna buat ribuan)
            // Di Excel nanti tetep raw data
            $val[] = $row->jumlah;      // 3
            
            $val[] = $row->satuan;      // 4
            $val[] = $row->unitkerja;   // 5
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    // ==========================================
    // MENU DATA UMUM SPB
    // ==========================================
    public function dataUmumSpb() {
        $data['title'] = "Data Umum SPB";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_dataumumspb');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_dataumum_spb() {
        $list = $this->M_pengadaan->get_dataumum_spb();
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nospb;       // 0 (Akan jadi Link Report)
            $val[] = $row->nosp3;       // 1
            $val[] = $row->vendornama;  // 2
            $val[] = $row->grandtotal;  // 3 (Raw data buat Excel)
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    // ==========================================
    // MENU DOKUMEN EVAL & BAKP
    // ==========================================
    public function dokEvalBakp() {
        $data['title'] = "Dokumen Eval & BAKP";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_dokevalbakp');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_dok_eval_bakp() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_pengadaan->get_dok_eval_bakp($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->noevaluasi;   // 0 (Buat Link BIRT)
            $val[] = $row->nokeputusan;  // 1 (No BAKP)
            $val[] = $row->nosp3;        // 2
            $val[] = $row->departemen;   // 3
            $val[] = $row->pekerjaan;    // 4
            $val[] = $row->proyek;       // 5
            $val[] = $row->tglevaluasi;  // 6
            $val[] = $row->catatan;      // 7
            $val[] = $row->diusulkan;    // 8
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    // ==========================================
    // MENU DATA BAPB
    // ==========================================
    public function dataBapb() {
        $data['title'] = "Data BAPB";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_databapb');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_data_bapb() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_pengadaan->get_data_bapb($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nobapb;      // 0 (Buat Link BIRT)
            $val[] = $row->tanggalbapb; // 1
            $val[] = $row->nosjalan;    // 2
            $val[] = $row->vendor;      // 3
            $val[] = $row->cek;         // 4
            $val[] = $row->terima;      // 5
            $val[] = $row->mengetahui;  // 6
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    // ==========================================
    // MENU DATA BPM
    // ==========================================
    public function dataBpm() {
        $data['title'] = "Data BPM";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Pengadaan/v_databpm');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_data_bpm() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_pengadaan->get_data_bpm($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nobpm;              // 0 (Link BIRT)
            $val[] = $row->unit;               // 1
            $val[] = $row->proyek;             // 2
            $val[] = $row->nospbspk;           // 3
            $val[] = $row->catatan;            // 4
            $val[] = $row->pemohon;            // 5
            $val[] = $row->pemohontanggal;     // 6
            $val[] = $row->dikeluarkantanggal; // 7
            $val[] = $row->diterimatanggal;    // 8
            $val[] = $row->dibukukantanggal;   // 9
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }
}