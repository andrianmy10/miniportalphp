<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan extends CI_Model {

    protected $db2;

    public function __construct() {
        parent::__construct();
        // Load database eksternal yang sama kayak pengadaan
        $this->db2 = $this->load->database('db_external', TRUE); 
    }

    public function get_jurnal_umum($tgl_awal = null, $tgl_akhir = null) {
        // Default: Tanggal 1 bulan ini s/d Hari ini
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tgl_awal = date('Y-m-01');
            $tgl_akhir = date('Y-m-d');
        }

        $this->db2->select('nobukti, tanggal, kdperkir, uraian, debet, kredit, noref, nofaktur, kdbarang, kdnasabah, kdproyek, keterangan');
        $this->db2->from('jurnalumum');
        $this->db2->where('tanggal >=', $tgl_awal);
        $this->db2->where('tanggal <=', $tgl_akhir);
        
        // Urutkan dari yang terbaru
        $this->db2->order_by('tanggal', 'ASC');

        return $this->db2->get()->result();
    }

    // Tambahin ini di bawah fungsi get_jurnal_umum()
    public function count_jurnal_bulan($bulan, $tahun) {
        $this->db2->where('MONTH(tanggal)', $bulan);
        $this->db2->where('YEAR(tanggal)', $tahun);
        return $this->db2->count_all_results('jurnalumum');
    }

    // 1. Ambil list siswa buat dropdown filter
    public function get_siswa_list() {
        $this->db2->select('nis AS VALUE, CONCAT(nama, " - ", nis) AS LABEL');
        $this->db2->from('jbsakad.siswatahunajaran');
        $this->db2->order_by('nama', 'ASC');
        return $this->db2->get()->result();
    }

    // 2. Ambil data Jurnal Pendapatan berdasarkan NIS
    public function get_jurnal_pendapatan($nis = null) {
        if (empty($nis)) return array();

        $this->db2->select('a.noinvoice, a.tanggal, a.uraian, a.kdperkir, a.nis, b.nama, Sum(a.debet-a.kredit) AS tagihan');
        $this->db2->from('jbsfina.jurnalpendapatan AS a');
        $this->db2->join('jbsakad.siswa AS b', 'a.nis = b.nis', 'left');
        
        $this->db2->where_in('a.kdperkir', [1211,1212,1213,1214,1215,1216,1221,1222,1223,1224,1225,1226,1271,1272,1273,1274,1275,1276,1293,1294,1295,1296,1801,1802,1803,1804,1805]);
        $this->db2->where('a.nis', $nis);
        
        $this->db2->group_by('a.noinvoice');
        $this->db2->having('Sum(a.debet-a.kredit) >', -100000000);
        $this->db2->order_by('a.tanggal', 'ASC');

        return $this->db2->get()->result();
    }

    public function get_tunggakan($tgl_awal = null, $tgl_akhir = null) {
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tgl_awal = date('Y-m-01');
            $tgl_akhir = date('Y-m-d');
        }

        $query = "
            SELECT
                b.nis, c.nama, a.kelas, d.tahunajaran, b.noinvoice, b.uraian, 
                SUM( b.debet - b.kredit ) AS tagihan, 
                IF(c.aktif = 1, 'Aktif', 'Tidak') AS status_siswa
            FROM
                tagihansiswadikurangdiskon x
                LEFT JOIN jurnalpendapatan AS b ON x.noinvoice = b.noinvoice
                LEFT JOIN jbsakad.siswa c ON b.nis = c.nis
                LEFT JOIN jbsakad.kelas a ON a.replid = c.idkelas
                LEFT JOIN jbsakad.tahunajaran d ON d.replid = a.idtahunajaran
            WHERE
                b.kdperkir IN (1221, 1222, 1223, 1224, 1225, 1226, 1211, 1212, 1213, 1214, 1215, 1216, 1217, 1286, 1291, 1292, 1293, 1294, 1295, 1296, 1801, 1802, 1803, 1804, 1805)
                AND x.tanggal BETWEEN ? AND ? 
                AND b.tanggal <= ?
            GROUP BY
                b.nis, b.noinvoice
            HAVING
                SUM( b.debet - b.kredit ) <> 0
            ORDER BY c.nama ASC
        ";

        return $this->db2->query($query, [$tgl_awal, $tgl_akhir, $tgl_akhir])->result();
    }
}