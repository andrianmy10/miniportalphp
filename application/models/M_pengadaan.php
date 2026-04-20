<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengadaan extends CI_Model {

    protected $db2;

    public function __construct() {
        parent::__construct();
        $this->db2 = $this->load->database('db_external', TRUE); 
    }

    public function get_sp3_data($tgl_awal = null, $tgl_akhir = null) {
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tahun_sekarang = date('Y');
            $tgl_awal = $tahun_sekarang . '-01-01';
            $tgl_akhir = $tahun_sekarang . '-12-31';
        }

        $this->db2->select('nosp3, unit, proyek, pemohon, pemohontanggal');
        $this->db2->from('sp3dataumum');
        $this->db2->where('pemohontanggal >=', $tgl_awal);
        $this->db2->where('pemohontanggal <=', $tgl_akhir);
        $this->db2->order_by('pemohontanggal', 'DESC');

        return $this->db2->get()->result();
    }

    public function count_sp3_tahun($tahun) {
        $this->db2->where('YEAR(pemohontanggal)', $tahun);
        return $this->db2->count_all_results('sp3dataumum');
    }

    public function count_sp3_bulan($bulan, $tahun) {
        $this->db2->where('MONTH(pemohontanggal)', $bulan);
        $this->db2->where('YEAR(pemohontanggal)', $tahun);
        return $this->db2->count_all_results('sp3dataumum');
    }

    public function count_sp3_hari($hari) {
        $this->db2->where('DATE(pemohontanggal)', $hari);
        return $this->db2->count_all_results('sp3dataumum');
    }

    public function get_recent_sp3($limit) {
        $this->db2->select('nosp3, unit, proyek, pemohon, pemohontanggal');
        $this->db2->from('sp3dataumum');
        $this->db2->order_by('pemohontanggal', 'DESC');
        $this->db2->limit($limit);
        return $this->db2->get()->result();
    }
}