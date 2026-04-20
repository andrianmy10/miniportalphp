<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengadaan extends CI_Model {

    protected $db2;

    public function __construct() {
        parent::__construct();
        // Load database eksternal
        $this->db2 = $this->load->database('db_external', TRUE); 
    }

    public function get_sp3_data($tgl_awal = null, $tgl_akhir = null) {
        // LOGIC TAHUN BERJALAN: 
        // Kalau tgl_awal dan tgl_akhir kosong (saat pertama kali halaman diload)
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tahun_sekarang = date('Y'); // Bakal otomatis berubah tiap tahun
            $tgl_awal = $tahun_sekarang . '-01-01';
            $tgl_akhir = $tahun_sekarang . '-12-31';
        }

        $this->db2->select('nosp3, unit, proyek, pemohon, pemohontanggal');
        $this->db2->from('sp3dataumum');

        // Filter berdasarkan pemohontanggal
        $this->db2->where('pemohontanggal >=', $tgl_awal);
        $this->db2->where('pemohontanggal <=', $tgl_akhir);
        
        // Opsional: Urutkan dari yang terbaru
        $this->db2->order_by('pemohontanggal', 'DESC');

        return $this->db2->get()->result();
    }
}