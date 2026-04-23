<?php 
    $tahun_sekarang = date('Y');
    $default_awal = $tahun_sekarang . '-01-01';
    $default_akhir = date('Y-m-d');
?>

<!-- Panggil CSS sakti dengan anti-cache -->
<link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Daftar Laporan SP3</h4>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-days me-1"></i> Dari Tanggal</label>
            <input type="text" id="tgl_awal" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-regular fa-calendar-check me-1"></i> Sampai Tanggal</label>
            <input type="text" id="tgl_akhir" class="form-control modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>

        <div class="col-lg-3 col-md-6">
            <label class="fw-bold small mb-1"><i class="fa-solid fa-building me-1"></i> Unit Kerja</label>
            <select id="filter_unit" class="form-control">
                <option value="">Semua Unit</option>
            </select>
        </div>
        
        <div class="col-lg-3 col-md-6">
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

<!-- Tambahin overflow: hidden !important; biar Progress Bar-nya nempel mulus di plafon card -->
<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px; overflow: hidden !important;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableSp3" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th width="15%">NO SP3</th>
                    <th width="20%">UNIT KERJA</th>
                    <th width="30%">NAMA PROYEK</th>
                    <th width="20%">NAMA PEMOHON</th>
                    <th width="15%">TANGGAL</th>
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
        const unitSelect = new Choices('#filter_unit', { searchEnabled: true, itemSelectText: '', shouldSort: false });

        function getExportFileName() { return 'Daftar SP3 ' + $('#tgl_awal').val() + ' sd ' + $('#tgl_akhir').val(); }

        var table = $('#tableSp3').DataTable({
            "processing": true, // Kunci mancing CSS Progress Bar
            "serverSide": false,
            "scrollX": true,
            "order": [[ 4, "asc" ]], 
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { 
                    "data": 0, 
                    "render": function(data, type, row) {
                        if (type === 'display' && data) {
                            let encodedNo = encodeURIComponent(data);
                            let urlBirt = `https://birt.asihputera.or.id:9999/birt/frameset?__report=PengadaanSP3.rptdesign&Group=manajemen&Departemen=MI%20ASIH%20PUTERA&NoSP3=${encodedNo}`;
                            return `<a href="${urlBirt}" target="_blank" class="btn-link-sp3" title="Buka Laporan">${data} <i class="fa-solid fa-arrow-up-right-from-square fa-xs ms-1"></i></a>`;
                        }
                        return data;
                    }
                },
                { "data": 1 }, { "data": 2 }, { "data": 3 }, { "data": 4 }
            ],
            "buttons": [
                { extend: 'excelHtml5', filename: getExportFileName, text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', className: 'btn btn-sm btn-success fw-bold me-2' },
                { extend: 'pdfHtml5', filename: getExportFileName, orientation: 'landscape', pageSize: 'A4', text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', className: 'btn btn-sm btn-danger fw-bold' }
            ],
            "language": { 
                "search": "", 
                "searchPlaceholder": "Pencarian cepat...",
                "processing": "" // Kosongin biar meler doang garisnya tanpa teks
            },
            
            // HACK AJAX PAKE SESSION STORAGE CACHE
            "ajax": function (data, callback, settings) {
                let tgl_awal = $('#tgl_awal').val();
                let tgl_akhir = $('#tgl_akhir').val();
                let cacheKey = 'cache_datasp3_' + tgl_awal + '_' + tgl_akhir;
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data SP3 dari Cache Browser");
                    let parsedData = JSON.parse(cachedData);
                    callback(parsedData);
                    updateUnitFilter(parsedData.data);
                } else {
                    console.log("Ambil data SP3 dari Server");
                    $.ajax({
                        url: "<?= base_url('pengadaan/ajax_list_sp3') ?>",
                        type: "POST",
                        data: { tgl_awal: tgl_awal, tgl_akhir: tgl_akhir },
                        success: function(response) {
                            let parsedResponse = JSON.parse(response);
                            sessionStorage.setItem(cacheKey, JSON.stringify(parsedResponse));
                            callback(parsedResponse);
                            updateUnitFilter(parsedResponse.data);
                        }
                    });
                }
            }
        });

        // Fungsi buat update pilihan Unit Kerja di dropdown secara dinamis
        function updateUnitFilter(dataList) {
            if(!dataList) return;
            var uniqueUnits = [...new Set(dataList.map(item => item[1]))].sort();
            var choiceOptions = [{ value: '', label: 'Semua Unit', selected: true }];
            uniqueUnits.forEach(function(item) {
                if(item) choiceOptions.push({ value: item, label: item });
            });
            unitSelect.clearChoices();
            unitSelect.setChoices(choiceOptions, 'value', 'label', true);
        }

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);

        // Filter Dropdown Unit Kerja
        document.getElementById('filter_unit').addEventListener('change', function(e) {
            var val = $.fn.dataTable.util.escapeRegex(e.target.value);
            table.column(1).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        // Tombol Filter
        $('#btn-filter').click(function(){ 
            let tgl_awal = $('#tgl_awal').val();
            let tgl_akhir = $('#tgl_akhir').val();
            sessionStorage.removeItem('cache_datasp3_' + tgl_awal + '_' + tgl_akhir);
            table.ajax.reload(); 
        });

        // Tombol Reset
        $('#btn-reset').click(function(){
            Object.keys(sessionStorage).forEach(function(key){
               if(key.startsWith('cache_datasp3_')) {
                   sessionStorage.removeItem(key);
               }
            });
            dpAwal.selectDate("<?= $default_awal ?>");
            dpAkhir.selectDate("<?= $default_akhir ?>");
            unitSelect.setChoiceByValue('');
            table.column(1).search('').ajax.reload();
        });
    });
</script>