<?php 
    $default_awal = date('Y-m-01');
    $default_akhir = date('Y-m-d');
?>

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Data Jurnal Pendapatan</h4>
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
        <table id="tablePendapatan" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>KD. PERKIR</th>
                    <th>URAIAN</th>
                    <th>DEBET</th>
                    <th>KREDIT</th>
                    <th>KD. DEPT</th>
                    <th>NO. TRANSFER</th>
                    <th>NIS</th>
                    <th>NAMA</th>
                    <th>NO. INVOICE</th>
                    <th>NO. KWITANSI</th>
                    <th>TANGGAL</th>
                    <th>NO. KAS</th>
                    <th>KETERANGAN</th>
                    <th>USER INPUT</th>
                    <th>TS</th>
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

        function getFileName() { return 'Jurnal Pendapatan ' + $('#tgl_awal').val() + ' sd ' + $('#tgl_akhir').val(); }

        var table = $('#tablePendapatan').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 11, "asc" ]], // Default sort berdasarkan Tanggal (index 11)
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { 
                    "data": 3, // Debet
                    "render": function(data, type, row) {
                        if (type === 'display') return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        return data;
                    }
                },
                { 
                    "data": 4, // Kredit
                    "render": function(data, type, row) {
                        if (type === 'display') return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        return data;
                    }
                },
                { "data": 5 },
                { "data": 6 },
                { "data": 7 },
                { "data": 8 },
                { "data": 9 },
                { "data": 10 },
                { 
                    "data": 11, // Tanggal
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
                        return data; 
                    }
                },
                { "data": 12 },
                { "data": 13 },
                { "data": 14 },
                { "data": 15 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: getFileName, 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { orthogonal: 'export' } // Kunci Anti-Rp
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: getFileName, 
                    orientation: 'landscape', 
                    pageSize: 'LEGAL', // Wajib LEGAL karena 16 kolom
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: { orthogonal: 'export' },
                    customize: function (doc) {
                        doc.content.splice(0, 1, { text: 'LAPORAN JURNAL PENDAPATAN', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Periode: ' + $('#tgl_awal').val() + ' s.d ' + $('#tgl_akhir').val(), fontSize: 10, alignment: 'center', margin: [0, 0, 0, 20] });
                        
                        doc.styles.tableHeader.fillColor = '#1e3a8a';
                        doc.styles.tableHeader.color = '#ffffff';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableHeader.fontSize = 7;
                        doc.defaultStyle.fontSize = 6; // Dikecilin lagi biar 16 kolom muat
                        
                        let colCount = doc.content[2].table.body[0].length;
                        doc.content[2].table.widths = Array(colCount).fill('*');
                    }
                }
            ],
            "language": { "search": "", "searchPlaceholder": "Cari data..." },
            
            // HACK AJAX PAKE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let tgl_awal = $('#tgl_awal').val();
                let tgl_akhir = $('#tgl_akhir').val();
                
                // Bikin kunci unik berdasarkan tanggal
                let cacheKey = 'cache_jurnalpendapatan_' + tgl_awal + '_' + tgl_akhir;

                // Cek data di memory browser
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data dari Cache Browser (Jurnal Pendapatan)");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data dari Server Database (Jurnal Pendapatan)");
                    $.ajax({
                        url: "<?= base_url('keuangan/ajax_jurnal_pendapatan') ?>",
                        type: "POST",
                        data: {
                            tgl_awal: tgl_awal,
                            tgl_akhir: tgl_akhir
                        },
                        success: function(response) {
                            let parsedResponse = JSON.parse(response);
                            // Simpan ke memory browser buat next time
                            sessionStorage.setItem(cacheKey, JSON.stringify(parsedResponse));
                            callback(parsedResponse);
                        }
                    });
                }
            }
        });

        // ACTION TOMBOL FILTER
        $('#btn-filter').click(function(){ 
            let tgl_awal = $('#tgl_awal').val();
            let tgl_akhir = $('#tgl_akhir').val();
            
            // Hapus cache untuk tanggal ini biar narik data paling fresh
            sessionStorage.removeItem('cache_jurnalpendapatan_' + tgl_awal + '_' + tgl_akhir);
            
            table.ajax.reload(); 
        });

        // ACTION TOMBOL RESET
        $('#btn-reset').click(function(){
            // Hapus SEMUA cache jurnal pendapatan yang ada di browser
            Object.keys(sessionStorage).forEach(function(key){
               if(key.startsWith('cache_jurnalpendapatan_')) {
                   sessionStorage.removeItem(key);
               }
            });

            dpAwal.selectDate("<?= $default_awal ?>");
            dpAkhir.selectDate("<?= $default_akhir ?>");
            table.ajax.reload();
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);
    });
</script>