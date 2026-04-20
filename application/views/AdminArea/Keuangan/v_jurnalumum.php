<?php 
    // Default Tanggal (Tanggal 1 bulan ini s/d Hari ini)
    $default_awal = date('Y-m-01');
    $default_akhir = date('Y-m-d');
?>

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Data Jurnal Umum</h4>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <div class="col-lg-4 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-days me-1"></i> Dari Tanggal</label>
            <input type="text" id="tgl_awal" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="col-lg-4 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-check me-1"></i> Sampai Tanggal</label>
            <input type="text" id="tgl_akhir" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="d-flex gap-2 w-100">
                <button id="btn-filter" class="btn btn-primary btn-custom flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Filter
                </button>
                <button id="btn-reset" class="btn btn-light btn-custom border flex-grow-1">
                    <i class="fa-solid fa-rotate-right me-2"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableJurnal" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>NO. BUKTI</th>
                    <th>TANGGAL</th>
                    <th>KD. PERKIR</th>
                    <th>URAIAN</th>
                    <th>DEBET</th>
                    <th>KREDIT</th>
                    <th>NO. REFERENSI</th>
                    <th>NO. FAKTUR</th>
                    <th>KD. BARANG</th>
                    <th>KD. NASABAH</th>
                    <th>KD. PROYEK</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        
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

        function getExportFileName() { return 'Jurnal Umum ' + $('#tgl_awal').val() + ' sd ' + $('#tgl_akhir').val(); }

        var table = $('#tableJurnal').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 1, "asc" ]], // Default sort ASC (tanggal 1 ke atas)
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 },
                { 
                    "data": 1, // Render Tanggal Indonesia (1 September 2025)
                    "render": function(data, type, row) {
                        if (type === 'display' && data) {
                            const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            let parts = data.split('-');
                            if (parts.length === 3) {
                                let hari = parseInt(parts[2], 10);
                                let bulan = namaBulan[parseInt(parts[1], 10) - 1];
                                return hari + ' ' + bulan + ' ' + parts[0];
                            }
                        }
                        return data; // Balikin YYYY-MM-DD buat export & sorting
                    }
                },
                { "data": 2 },
                { "data": 3 },
                { 
                    "data": 4, // Kolom Debet
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        }
                        return data; // Raw buat export
                    }
                },
                { 
                    "data": 5, // Kolom Kredit
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        }
                        return data; // Raw buat export
                    }
                },
                { "data": 6 },
                { "data": 7 },
                { "data": 8 },
                { "data": 9 },
                { "data": 10 },
                { "data": 11 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: getExportFileName, 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: {
                        orthogonal: 'export' // Kunci Export Tanpa Rp
                    }
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: getExportFileName, 
                    orientation: 'landscape', 
                    pageSize: 'LEGAL', // Legal biar 12 kolom ga sesak
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: {
                        orthogonal: 'export' // Kunci PDF Tanpa Rp (jika diperlukan)
                    },
                    customize: function (doc) {
                        doc.content.splice(0, 1, { text: 'LAPORAN JURNAL UMUM', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Periode: ' + $('#tgl_awal').val() + ' s/d ' + $('#tgl_akhir').val(), fontSize: 10, alignment: 'center', color: '#64748b', margin: [0, 0, 0, 20] });
                        
                        doc.styles.tableHeader.fillColor = '#1e3a8a';
                        doc.styles.tableHeader.color = '#ffffff';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableHeader.fontSize = 8;
                        doc.defaultStyle.fontSize = 7;
                        
                        let colCount = doc.content[2].table.body[0].length;
                        doc.content[2].table.widths = Array(colCount).fill('*');
                    }
                }
            ],
            "language": { "search": "", "searchPlaceholder": "Pencarian cepat..." },
            "ajax": {
                "url": "<?= base_url('keuangan/ajax_jurnal_umum') ?>",
                "type": "POST",
                "data": function ( d ) { 
                    d.tgl_awal = $('#tgl_awal').val(); 
                    d.tgl_akhir = $('#tgl_akhir').val(); 
                }
            }
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);

        $('#btn-filter').click(function(){ 
            table.ajax.reload(); 
        });

        $('#btn-reset').click(function(){
            dpAwal.selectDate("<?= $default_awal ?>");
            dpAkhir.selectDate("<?= $default_akhir ?>");
            table.search('').ajax.reload();
        });
    });
</script>