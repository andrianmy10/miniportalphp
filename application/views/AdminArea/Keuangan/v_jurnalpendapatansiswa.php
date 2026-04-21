<?php 
    $default_awal = date('Y-m-01');
    $default_akhir = date('Y-m-d');
?>

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Jurnal Pendapatan Spesifik Siswa</h4>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <div class="col-lg-4 col-md-12">
            <label class="fw-bold small mb-1"><i class="fa-solid fa-user-graduating me-1"></i> Pilih Siswa (Wajib)</label>
            <select id="filter_nis" class="form-control">
                <option value="">-- Cari Nama / NIS Siswa --</option>
                <?php foreach($siswa as $s): ?>
                    <option value="<?= $s->VALUE ?>"><?= $s->LABEL ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-days me-1"></i> Tgl Awal</label>
            <input type="text" id="tgl_awal" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-check me-1"></i> Tgl Akhir</label>
            <input type="text" id="tgl_akhir" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="col-lg-2 col-md-12">
            <div class="d-flex gap-2 w-100">
                <button id="btn-filter" class="btn btn-primary btn-custom flex-grow-1" title="Cari Data">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tablePendapatanSiswa" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>KD. PERKIR</th>
                    <th>URAIAN</th>
                    <th>DEBET</th>
                    <th>KREDIT</th>
                    <th>KD. DEPT</th>
                    <th>NIS</th>
                    <th>NO. INVOICE</th>
                    <th>NO. KWITANSI</th>
                    <th>TANGGAL</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Init Dropdown
        const siswaSelect = new Choices('#filter_nis', { 
            searchEnabled: true, 
            itemSelectText: '', 
            shouldSort: false 
        });

        const localeId = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mi', 'Se', 'Sl', 'Ra', 'Ka', 'Ju', 'Sa'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari Ini', clear: 'Bersihkan', dateFormat: 'yyyy-MM-dd', firstDay: 1
        };

        const dpAwal = new AirDatepicker('#tgl_awal', { locale: localeId, selectedDates: ["<?= $default_awal ?>"], autoClose: true });
        const dpAkhir = new AirDatepicker('#tgl_akhir', { locale: localeId, selectedDates: ["<?= $default_akhir ?>"], autoClose: true });

        function getFileName() { 
            let label = $('#filter_nis option:selected').text() || 'Pendapatan Siswa';
            return label + ' - ' + $('#tgl_awal').val() + ' sd ' + $('#tgl_akhir').val(); 
        }

        var table = $('#tablePendapatanSiswa').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 8, "asc" ]], // Urut berdasarkan tanggal (index 8)
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { 
                    "data": 2, // Debet
                    "render": function(data, type) {
                        if (type === 'display') return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        return data;
                    }
                },
                { 
                    "data": 3, // Kredit
                    "render": function(data, type) {
                        if (type === 'display') return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        return data;
                    }
                },
                { "data": 4 },
                { "data": 5 },
                { "data": 6 },
                { "data": 7 },
                { 
                    "data": 8, // Tanggal
                    "render": function(data, type) {
                        if (type === 'display' && data) {
                            // Antisipasi kalau data dari DB bentuknya DATETIME (ada jamnya)
                            let dateOnly = data.split(' ')[0]; 
                            const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            let parts = dateOnly.split('-');
                            if (parts.length === 3) {
                                return parseInt(parts[2], 10) + ' ' + namaBulan[parseInt(parts[1], 10) - 1] + ' ' + parts[0];
                            }
                        }
                        return data; 
                    }
                }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: getFileName, 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { orthogonal: 'export' }
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: getFileName, 
                    orientation: 'landscape', 
                    pageSize: 'A4', 
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: { orthogonal: 'export' },
                    customize: function (doc) {
                        let namaSiswa = $('#filter_nis option:selected').text() || '-';
                        doc.content.splice(0, 1, { text: 'JURNAL PENDAPATAN SISWA', fontSize: 14, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Siswa: ' + namaSiswa + '\nPeriode: ' + $('#tgl_awal').val() + ' s.d ' + $('#tgl_akhir').val(), fontSize: 10, alignment: 'center', margin: [0, 0, 0, 20] });
                        doc.styles.tableHeader.fillColor = '#1e3a8a';
                        doc.styles.tableHeader.color = '#ffffff';
                    }
                }
            ],
            "language": { 
                "search": "", 
                "searchPlaceholder": "Cari data...",
                "emptyTable": "Silahkan pilih Siswa lalu klik tombol Cari untuk menampilkan data." // Pesan custom saat kosong
            },
            "ajax": {
                "url": "<?= base_url('keuangan/ajax_jurnal_siswa') ?>",
                "type": "POST",
                "data": function ( d ) { 
                    d.nis = $('#filter_nis').val();
                    d.tgl_awal = $('#tgl_awal').val();
                    d.tgl_akhir = $('#tgl_akhir').val();
                }
            }
        });

        $('#btn-filter').click(function(){ 
            if($('#filter_nis').val() == '') {
                alert('Pilih siswa terlebih dahulu!');
                return;
            }
            table.ajax.reload(); 
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);
    });
</script>