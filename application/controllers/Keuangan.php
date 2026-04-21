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
        
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_jurnalpendapatan');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_jurnal_pendapatan() {
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        $list = $this->M_keuangan->get_jurnal_pendapatan($tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->replid;       // 0
            $val[] = $row->kdperkir;     // 1
            $val[] = $row->uraian;       // 2
            $val[] = $row->debet;        // 3 (Raw data)
            $val[] = $row->kredit;       // 4 (Raw data)
            $val[] = $row->kddepartemen; // 5
            $val[] = $row->notransfer;   // 6
            $val[] = $row->nis;          // 7
            $val[] = $row->nama;         // 8
            $val[] = $row->noinvoice;    // 9
            $val[] = $row->nokwitansi;   // 10
            $val[] = $row->tanggal;      // 11 (Raw YYYY-MM-DD)
            $val[] = $row->nokas;        // 12
            $val[] = $row->keterangan;   // 13
            $val[] = $row->userinput;    // 14
            $val[] = $row->ts;           // 15
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

    // ==========================================
    // MENU DATA BPJS
    // ==========================================
    public function dataBpjs() {
        $data['title'] = "Data BPJS Pegawai";
        
        // Tarik master data Satminkal buat dropdown
        $data['satminkal'] = $this->M_keuangan->get_satminkal_list(); 
        
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_databpjs', $data);
        $this->load->view('AdminArea/footer');
    }

    public function ajax_data_bpjs() {
        // Tangkep filter dari view
        $satminkal = $this->input->post('satminkal'); 
        
        $list = $this->M_keuangan->get_data_bpjs($satminkal);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nip;                   // 0
            $val[] = $row->nama;                  // 1
            $val[] = $row->satminkal;             // 2
            $val[] = $row->pen_ketenagakerjaan;   // 3 
            $val[] = $row->pot_ketenagakerjaan;   // 4 
            $val[] = $row->pen_kesehatan;         // 5 
            $val[] = $row->pot1_kesehatan;        // 6 
            $val[] = $row->pot2_kesehatan;        // 7 
            $val[] = $row->nama_tambahan;         // 8
            $val[] = $row->keterangan;            // 9
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }

    public function jurnalSiswa() {
        $data['title'] = "Jurnal Pendapatan Siswa";
        $data['siswa'] = $this->M_keuangan->get_siswa_list(); 

        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_jurnalpendapatansiswa', $data); // Nama file view tetep
        $this->load->view('AdminArea/footer');
    }

    public function ajax_jurnal_siswa() {
        $nis = $this->input->post('nis');
        $tgl_awal = $this->input->post('tgl_awal');
        $tgl_akhir = $this->input->post('tgl_akhir');

        if(empty($nis)) {
            echo json_encode(["data" => []]);
            return;
        }

        // Fungsi get_jurnal_pendapatan_siswa di Model nggak perlu diubah
        $list = $this->M_keuangan->get_jurnal_pendapatan_siswa($nis, $tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->kdperkir;       
            $val[] = $row->uraian;         
            $val[] = $row->debet;          
            $val[] = $row->kredit;         
            $val[] = $row->kddepartemen;   
            $val[] = $row->nis;            
            $val[] = $row->noinvoice;      
            $val[] = $row->nokwitansi;     
            $val[] = $row->tanggal;        
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }
    public function dataBsi() {
        $data['title'] = "Data Tagihan BSI";
        $this->load->view('AdminArea/header', $data);
        $this->load->view('AdminArea/sidebar');
        $this->load->view('AdminArea/Keuangan/v_databsi');
        $this->load->view('AdminArea/footer');
    }

    public function ajax_data_bsi() {
        $tgl_awal    = $this->input->post('tgl_awal');
        $tgl_akhir   = $this->input->post('tgl_akhir');
        $tgl_tagihan = $this->input->post('tgl_tagihan');
        $tgl_berlaku = $this->input->post('tgl_berlaku');

        $list = $this->M_keuangan->get_data_bsi($tgl_tagihan, $tgl_berlaku, $tgl_awal, $tgl_akhir);
        
        $data = array();
        foreach ($list as $row) {
            $val = array();
            $val[] = $row->nomor_invoice;
            $val[] = $row->nomor_pembayaran;
            $val[] = $row->id_pelanggan;
            $val[] = $row->nama;
            $val[] = $row->waktu_berlaku;
            $val[] = $row->waktu_jatuh_tempo;
            $val[] = $row->waktu_kedaluwarsa;
            $val[] = $row->info_kelas;
            $val[] = $row->info_angkatan;
            $val[] = $row->rincian_tagihan_spp;       // Raw data utk Excel
            $val[] = $row->rincian_tagihan_katering;  // Raw data utk Excel
            $val[] = $row->rincian_tagihan_kegiatan;  // Raw data utk Excel
            $val[] = $row->total_nominal;             // Raw data utk Excel
            $val[] = $row->email;
            $val[] = $row->nomor_hp;
            $val[] = $row->catatan;
            $val[] = $row->status_tagihan;
            $val[] = $row->action_tagihan_jatuh_tempo;
            $val[] = $row->action_tagihan_kedaluwarsa;
            $data[] = $val;
        }

        echo json_encode(["data" => $data]);
    }
}