<?php 
    // OTOMATIS: Tanggal 1 Januari di tahun berjalan
    $default_awal = date('Y-01-01');
    // OTOMATIS: Tanggal hari ini
    $default_akhir = date('Y-m-d');
?>

<link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Dokumen Eval & BAKP</h4>
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
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan
                </button>
                <button id="btn-reset" class="btn btn-light btn-custom border flex-grow-1">
                    <i class="fa-solid fa-rotate-right me-2"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Overflow hidden !important ditambahin di sini -->
<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px; overflow: hidden !important;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableEval" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>NO EVALUASI</th>
                    <th>NO BAKP</th>
                    <th>NO SP3</th>
                    <th>DEPARTEMEN</th>
                    <th>PEKERJAAN</th>
                    <th>NAMA PROYEK</th>
                    <th>TGL EVALUASI</th>
                    <th>KETERANGAN</th>
                    <th>PENGUSUL</th>
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

        function getFileName() { return 'Dokumen_Eval_BAKP_' + $('#tgl_awal').val() + '_sd_' + $('#tgl_akhir').val(); }

        var table = $('#tableEval').DataTable({
            "processing": true, // Kunci mancing CSS Progress Bar
            "serverSide": false,
            "scrollX": true,
            "order": [[ 6, "desc" ]], // Default sort by Tgl Evaluasi Terbaru
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { 
                    "data": 0, 
                    "render": function(data, type, row) {
                        if (type === 'display' && data) {
                            // row[1] itu isinya data dari kolom NO BAKP
                            let noEvaluasi = encodeURIComponent(data);
                            let noBakp = row[1] ? encodeURIComponent(row[1]) : ''; // Jaga-jaga kalau BAKP-nya kosong
                            
                            // URL BIRT sesuai yang lu kasih
                            let urlBirt = `https://birt.asihputera.or.id:9999/birt/frameset?__report=PengadaanEVALDAN.rptdesign&Group=manajemen&Departemen=MI%20ASIH%20PUTERA&NoEvaluasi=${noEvaluasi}&NoBAKP=${noBakp}`;
                            
                            return `<a href="${urlBirt}" target="_blank" class="fw-bold text-primary text-decoration-none" title="Buka Report Evaluasi & BAKP"><i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i> ${data}</a>`;
                        }
                        // Buat export Excel tetep text biasa
                        return data;
                    }
                },
                { "data": 1 },
                { "data": 2 },
                { "data": 3 },
                { "data": 4 },
                { "data": 5 },
                { "data": 6 }, // Tanggal Evaluasi
                { "data": 7 },
                { "data": 8 }
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
                        doc.content.splice(0, 1, { text: 'DOKUMEN EVALUASI & BAKP', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Periode: ' + $('#tgl_awal').val() + ' s.d ' + $('#tgl_akhir').val(), fontSize: 10, alignment: 'center', margin: [0, 0, 0, 20] });
                        
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
            "language": { 
                "search": "", 
                "searchPlaceholder": "Cari No Eval, Proyek...",
                "processing": "" // Sembunyiin teks "Processing..." default DataTables
            },
            
            // MAGIC CACHE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let tgl_awal = $('#tgl_awal').val();
                let tgl_akhir = $('#tgl_akhir').val();
                
                // Cache key unik pakai kombinasi tanggal
                let cacheKey = 'cache_dokevalbakp_' + tgl_awal + '_' + tgl_akhir;
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data Dok Eval dari Cache Browser");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data Dok Eval dari Server");
                    $.ajax({
                        url: "<?= base_url('pengadaan/ajax_dok_eval_bakp') ?>",
                        type: "POST",
                        data: {
                            tgl_awal: tgl_awal,
                            tgl_akhir: tgl_akhir
                        },
                        success: function(response) {
                            let parsedResponse = JSON.parse(response);
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
            sessionStorage.removeItem('cache_dokevalbakp_' + tgl_awal + '_' + tgl_akhir);
            table.ajax.reload(); 
        });

        // ACTION TOMBOL RESET
        $('#btn-reset').click(function(){
            Object.keys(sessionStorage).forEach(function(key){
               if(key.startsWith('cache_dokevalbakp_')) {
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