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
    public function get_jurnal_pendapatan($tgl_awal = null, $tgl_akhir = null) {
        if (empty($tgl_awal) || empty($tgl_akhir)) {
            $tgl_awal = date('Y-m-01');
            $tgl_akhir = date('Y-m-d');
        }

        // Sesuai query lu yang baru
        $this->db2->select('a.replid, a.kdperkir, a.uraian, a.debet, a.kredit, a.kddepartemen, a.notransfer, a.nis, b.nama, a.noinvoice, a.nokwitansi, a.tanggal, a.nokas, a.keterangan, a.userinput, a.ts');
        $this->db2->from('jurnalpendapatan AS a'); // (Atau jbsfina.jurnalpendapatan kalau beda DB)
        $this->db2->join('jbsakad.siswa AS b', 'a.nis = b.nis', 'left');
        $this->db2->where('a.tanggal >=', $tgl_awal);
        $this->db2->where('a.tanggal <=', $tgl_akhir);
        
        $this->db2->order_by('a.nis', 'ASC');
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

    public function get_satminkal_list() {
        $this->db2->select('satminkal');
        $this->db2->from('jbssdm.pegawai');
        $this->db2->where('aktif', 1);
        $this->db2->where('satminkal !=', ''); // Biar yg kosong gak ikut
        $this->db2->group_by('satminkal');     // Ini pengganti DISTINCT di CI
        $this->db2->order_by('satminkal', 'ASC');
        return $this->db2->get()->result();
    }

    // 2. Fungsi get data BPJS dikasih filter satminkal
    public function get_data_bpjs($satminkal = null) {
        $this->db2->select('a.nip, b.nama, b.satminkal, a.pen_ketenagakerjaan, a.pot_ketenagakerjaan, a.pen_kesehatan, a.pot1_kesehatan, a.pot2_kesehatan, a.nama_tambahan, a.keterangan');
        $this->db2->from('jbssdm.databpjs a');
        $this->db2->join('jbssdm.pegawai b', 'a.nip = b.nip', 'left');
        $this->db2->where('b.aktif', 1);

        // Kalau usernya milih Satminkal, filter datanya!
        if (!empty($satminkal)) {
            $this->db2->where('b.satminkal', $satminkal);
        }

        $this->db2->order_by('b.satminkal', 'ASC');
        $this->db2->order_by('b.nama', 'ASC'); // Tambahan biar namanya ngurut abjad

        return $this->db2->get()->result();
    }
    public function get_jurnal_pendapatan_siswa($nis, $tgl_awal, $tgl_akhir) {
        // Kalau NIS kosong, gausah narik apa-apa
        if (empty($nis)) {
            return array();
        }

        $this->db2->select('kdperkir, uraian, debet, kredit, kddepartemen, nis, noinvoice, nokwitansi, tanggal');
        $this->db2->from('jurnalpendapatan'); 
        $this->db2->where('nis', $nis);
        $this->db2->where('tanggal >=', $tgl_awal);
        $this->db2->where('tanggal <=', $tgl_akhir . ' 23:59:59'); // Efeknya sama kek INTERVAL 23 HOUR
        
        $this->db2->order_by('tanggal', 'ASC');

        return $this->db2->get()->result();
    }

    public function get_data_bsi($tgl_tagihan, $tgl_berlaku, $tgl_awal, $tgl_akhir) {
        $sql = "SELECT
            (SELECT CONCAT(a.nis, '/', a.kdperkir, '/', toRoman(MONTH(?)), '/', YEAR(?))) AS nomor_invoice,
            a.nis AS nomor_pembayaran,
            a.nis AS id_pelanggan,
            b.nama AS nama,
            ? AS waktu_berlaku,
            ? AS waktu_jatuh_tempo,
            ? AS waktu_kedaluwarsa,
            b.kelas AS info_kelas,
            b.tahunajaran AS info_angkatan,
            IFNULL(SUM(CASE kdperkir WHEN 1221 THEN a.tagihan WHEN 1222 THEN a.tagihan WHEN 1223 THEN a.tagihan WHEN 1224 THEN a.tagihan WHEN 1225 THEN a.tagihan WHEN 1226 THEN a.tagihan END), 0) AS rincian_tagihan_spp,    
            IFNULL(SUM(CASE kdperkir WHEN 1291 THEN a.tagihan WHEN 1292 THEN a.tagihan WHEN 1293 THEN a.tagihan WHEN 1294 THEN a.tagihan WHEN 1295 THEN a.tagihan WHEN 1296 THEN a.tagihan END), 0) AS rincian_tagihan_katering,
            IFNULL(SUM(CASE kdperkir WHEN 1801 THEN a.tagihan WHEN 1802 THEN a.tagihan WHEN 1803 THEN a.tagihan WHEN 1804 THEN a.tagihan WHEN 1805 THEN a.tagihan END), 0) AS rincian_tagihan_kegiatan,
            SUM(a.tagihan) AS total_nominal,
            c.emailtagihan AS email,
            c.hportu AS nomor_hp,
            '' AS catatan,
            'Menunggu Pembayaran' AS status_tagihan,
            'Terlambat Bayar' AS action_tagihan_jatuh_tempo,
            'Kadaluwarsa' AS action_tagihan_kedaluwarsa
        FROM tunggakansiswa_rev1 a
        LEFT JOIN jbsakad.siswatahunajaran b ON a.nis = b.nis
        LEFT JOIN emailuntuktagihansiswa c ON a.nis = c.nis
        WHERE a.tanggal BETWEEN ? AND ?
        AND a.kdperkir IN (1221, 1222, 1223, 1224, 1225, 1226, 1291, 1292, 1293, 1294, 1295, 1296, 1801, 1802, 1803, 1804, 1805)
        GROUP BY a.nis
        ORDER BY b.nis";

        // Urutan parameter sesuai tanda tanya (?) di query atas:
        $binds = [
            $tgl_tagihan, $tgl_akhir, $tgl_tagihan, $tgl_berlaku, $tgl_berlaku, 
            $tgl_awal, $tgl_akhir
        ];

        return $this->db2->query($sql, $binds)->result();
    }
}