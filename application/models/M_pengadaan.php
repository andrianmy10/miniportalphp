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

    public function get_kode_barang() {
        $this->db2->select('idbarang, nama_barang, spesifikasi, satuan, ukuran, keterangan');
        $this->db2->from('tb_kodebarang');
        $this->db2->order_by('nama_barang', 'ASC'); // Urutin abjad biar rapi
        return $this->db2->get()->result();
    }

    public function get_stok_gudang() {
        $this->db2->select('kodebarang, namabarang, spesifikasi, SUM(jumlah) AS jumlah, satuan, unitkerja');
        $this->db2->from('transaksigudang');
        $this->db2->group_by(['kodebarang', 'unitkerja']);
        $this->db2->order_by('namabarang', 'ASC');
        return $this->db2->get()->result();
    }

    public function get_dataumum_spb() {
        // FORMAT(grandtotal) diilangin biar Excel dapet angka mentah
        $this->db2->select('nospb, nosp3, vendornama, grandtotal');
        $this->db2->from('spb_dataumum');
        $this->db2->order_by('nospb', 'DESC'); // Sortir dari SPB terbaru
        return $this->db2->get()->result();
    }

    // =====================================
    // DATA DOKUMEN EVAL & BAKP
    // =====================================
    public function get_dok_eval_bakp($tgl_awal, $tgl_akhir) {
        $this->db2->select('a.noevaluasi, b.nokeputusan, a.nosp3, a.departemen, a.pekerjaan, a.proyek, a.tglevaluasi, a.catatan, a.diusulkan');
        $this->db2->from('p2b_bakp b');
        $this->db2->join('p2b_evaldataumum a', 'a.noevaluasi = b.noevaluasi', 'left');
        
        $this->db2->where('a.tglevaluasi >=', $tgl_awal);
        $this->db2->where('a.tglevaluasi <=', $tgl_akhir);
        
        $this->db2->order_by('a.tglevaluasi', 'DESC'); // Sortir berdasarkan yang terbaru
        return $this->db2->get()->result();
    }

    // =====================================
    // DATA BAPB
    // =====================================
    public function get_data_bapb($tgl_awal, $tgl_akhir) {
        $this->db2->select('nobapb, tanggalbapb, nosjalan, vendor, cek, terima, mengetahui');
        $this->db2->from('bapb_dataumum');
        
        $this->db2->where('tanggalbapb >=', $tgl_awal);
        $this->db2->where('tanggalbapb <=', $tgl_akhir);
        
        $this->db2->order_by('tanggalbapb', 'DESC'); // Sortir dari tanggal terbaru
        return $this->db2->get()->result();
    }

    // =====================================
    // DATA BPM
    // =====================================
    public function get_data_bpm($tgl_awal, $tgl_akhir) {
        $this->db2->select('nobpm, unit, proyek, nospbspk, catatan, pemohon, pemohontanggal, dikeluarkantanggal, diterimatanggal, dibukukantanggal');
        $this->db2->from('bpm_dataumum');
        
        // Filter berdasarkan tanggal permohonan
        $this->db2->where('pemohontanggal >=', $tgl_awal);
        $this->db2->where('pemohontanggal <=', $tgl_akhir);
        
        $this->db2->order_by('pemohontanggal', 'DESC'); 
        return $this->db2->get()->result();
    }
}