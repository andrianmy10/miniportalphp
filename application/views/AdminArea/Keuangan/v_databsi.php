<?php 
    // Default Tanggal sesuai request
    $default_awal    = '2022-01-01'; 
    $default_akhir   = date('Y-m-t'); // Akhir bulan ini
    $default_tagihan = date('Y-m-01'); // Awal bulan ini
    $default_berlaku = date('Y-m-t'); // Akhir bulan ini
?>

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Upload Data Tagihan BSI</h4>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <!-- Filter Data Database -->
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-days me-1"></i> Data Tgl Awal</label>
            <input type="text" id="tgl_awal" class="form-control modern-input" autocomplete="off">
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-check me-1"></i> Data Tgl Akhir</label>
            <input type="text" id="tgl_akhir" class="form-control modern-input" autocomplete="off">
        </div>

        <!-- Filter Parameter Bank -->
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1 text-primary"><i class="fa-solid fa-file-invoice me-1"></i> Set Tgl Tagihan</label>
            <input type="text" id="tgl_tagihan" class="form-control modern-input border-primary" autocomplete="off">
        </div>
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1 text-danger"><i class="fa-solid fa-calendar-xmark me-1"></i> Set Tgl Jatuh Tempo</label>
            <input type="text" id="tgl_berlaku" class="form-control modern-input border-danger" autocomplete="off">
        </div>
        
        <div class="col-lg-12 col-md-12 mt-3">
            <div class="d-flex gap-2 w-100 justify-content-end">
                <button id="btn-reset" class="btn btn-light btn-custom border px-4">
                    <i class="fa-solid fa-rotate-right me-2"></i> Reset
                </button>
                <button id="btn-filter" class="btn btn-primary btn-custom px-5">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableBsi" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>No. Pembayaran</th>
                    <th>ID Pelanggan</th>
                    <th>Nama Siswa</th>
                    <th>Waktu Berlaku</th>
                    <th>Jatuh Tempo</th>
                    <th>Kedaluwarsa</th>
                    <th>Kelas</th>
                    <th>Angkatan</th>
                    <th>Tagihan SPP</th>
                    <th>Tagihan Katering</th>
                    <th>Tagihan Kegiatan</th>
                    <th>Total Nominal</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Catatan</th>
                    <th>Status Tagihan</th>
                    <th>Action Jatuh Tempo</th>
                    <th>Action Kedaluwarsa</th>
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
        const dpTagihan = new AirDatepicker('#tgl_tagihan', { locale: localeId, selectedDates: ["<?= $default_tagihan ?>"], autoClose: true });
        const dpBerlaku = new AirDatepicker('#tgl_berlaku', { locale: localeId, selectedDates: ["<?= $default_berlaku ?>"], autoClose: true });

        // Helper render uang
        function renderRp(data, type) {
            if (type === 'display') return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data || 0);
            return data;
        }

        // KUMPULAN HEADER BANK BSI (BUAT EXCEL)
        const bsiHeaders = [
            "nomor_invoice", "nomor_pembayaran", "id_pelanggan", "nama", "waktu_berlaku", 
            "waktu_jatuh_tempo", "waktu_kedaluwarsa", "info_kelas", "info_angkatan", 
            "rincian_tagihan_spp", "rincian_tagihan_katering", "rincian_tagihan_kegiatan", 
            "total_nominal", "email", "nomor_hp", "catatan", "status_tagihan", 
            "action_tagihan_jatuh_tempo", "action_tagihan_kedaluwarsa"
        ];

        var table = $('#tableBsi').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 3, "asc" ]], // Sort berdasarkan Nama
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 }, { "data": 1 }, { "data": 2 }, { "data": 3 },
                { "data": 4 }, { "data": 5 }, { "data": 6 }, { "data": 7 }, { "data": 8 },
                { "data": 9, "render": renderRp },
                { "data": 10, "render": renderRp },
                { "data": 11, "render": renderRp },
                { "data": 12, "render": renderRp },
                { "data": 13 }, { "data": 14 }, { "data": 15 }, { "data": 16 }, { "data": 17 }, { "data": 18 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: 'Format_Upload_BSI_' + new Date().toISOString().slice(0,10), 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Download CSV/Excel BSI', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { 
                        orthogonal: 'export',
                        // INI KUNCI UTAMA UBAH HEADER EXCEL!
                        format: {
                            header: function ( data, columnIdx ) {
                                return bsiHeaders[columnIdx]; // Ganti sama array header BSI
                            }
                        }
                    }
                }
            ],
            "language": { "search": "", "searchPlaceholder": "Cari data..." },
            // HACK AJAX PAKE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let tgl_awal    = $('#tgl_awal').val();
                let tgl_akhir   = $('#tgl_akhir').val();
                let tgl_tagihan = $('#tgl_tagihan').val();
                let tgl_berlaku = $('#tgl_berlaku').val();
                
                // Bikin kunci unik berdasarkan 4 tanggal yang dipisah pake underscore
                let cacheKey = 'cache_bsi_' + tgl_awal + '_' + tgl_akhir + '_' + tgl_tagihan + '_' + tgl_berlaku;

                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data dari Cache Browser (BSI)");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data dari Server Database (BSI)");
                    $.ajax({
                        url: "<?= base_url('keuangan/ajax_data_bsi') ?>",
                        type: "POST",
                        data: {
                            tgl_awal: tgl_awal,
                            tgl_akhir: tgl_akhir,
                            tgl_tagihan: tgl_tagihan,
                            tgl_berlaku: tgl_berlaku
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

        $('#btn-filter').click(function(){ 
            let tgl_awal    = $('#tgl_awal').val();
            let tgl_akhir   = $('#tgl_akhir').val();
            let tgl_tagihan = $('#tgl_tagihan').val();
            let tgl_berlaku = $('#tgl_berlaku').val();
            
            // Bersihin cache yang spesifik untuk parameter ini
            let cacheKey = 'cache_bsi_' + tgl_awal + '_' + tgl_akhir + '_' + tgl_tagihan + '_' + tgl_berlaku;
            sessionStorage.removeItem(cacheKey);

            table.ajax.reload(); 
        });

        $('#btn-reset').click(function(){
            // Bersihin SEMUA cache BSI
            Object.keys(sessionStorage).forEach(function(key){
               if(key.startsWith('cache_bsi_')) {
                   sessionStorage.removeItem(key);
               }
            });

            dpAwal.selectDate("<?= $default_awal ?>");
            dpAkhir.selectDate("<?= $default_akhir ?>");
            dpTagihan.selectDate("<?= $default_tagihan ?>");
            dpBerlaku.selectDate("<?= $default_berlaku ?>");
            table.ajax.reload();
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);
    });
</script>