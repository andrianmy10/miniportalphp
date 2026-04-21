<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Master Data BPJS Pegawai</h4>
        </div>
    </div>
</div>

<!-- KOTAK FILTER -->
<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <div class="col-lg-8 col-md-12">
            <label class="fw-bold small mb-1"><i class="fa-solid fa-building-user me-1"></i> Pilih Satminkal</label>
            <select id="filter_satminkal" class="form-control">
                <option value="">-- Semua Satminkal --</option>
                <?php foreach($satminkal as $s): ?>
                    <option value="<?= $s->satminkal ?>"><?= $s->satminkal ?></option>
                <?php endforeach; ?>
            </select>
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

<!-- TABEL DATA -->
<div class="card-sp3 fade-in-up delay-2" style="position: relative; z-index: 1; min-height: 400px;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableBpjs" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>NAMA</th>
                    <th>SATMINKAL</th>
                    <th>PEN. KETENAGAKERJAAN</th>
                    <th>POT. KETENAGAKERJAAN</th>
                    <th>PEN. KESEHATAN 1</th>
                    <th>POT. KESEHATAN 1</th>
                    <th>POT. KESEHATAN 2</th>
                    <th>NAMA TAMBAHAN</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        
        // Init Dropdown Searchable biar cakep
        const satminkalSelect = new Choices('#filter_satminkal', { 
            searchEnabled: true, 
            itemSelectText: '', 
            shouldSort: false 
        });

        // Fungsi helper buat render Rp khusus di Web
        function renderRupiah(data, type) {
            if (type === 'display') {
                return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data || 0);
            }
            return data || 0; // Return raw data buat Export Excel
        }

        // Bikin nama file export dinamis sesuai filter
        function getFileName() { 
            let label = $('#filter_satminkal').val() || 'Semua Satminkal';
            return 'Data_BPJS_Pegawai_' + label; 
        }

        var table = $('#tableBpjs').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 2, "asc" ]], // Urut by Satminkal
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { "data": 3, "render": renderRupiah },
                { "data": 4, "render": renderRupiah },
                { "data": 5, "render": renderRupiah },
                { "data": 6, "render": renderRupiah },
                { "data": 7, "render": renderRupiah },
                { "data": 8 },
                { "data": 9 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: getFileName, 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { orthogonal: 'export' } // Excel mulus angka doang
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: getFileName,  
                    orientation: 'landscape', 
                    pageSize: 'LEGAL', // Pake legal biar 10 kolom ga sempit
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: { orthogonal: 'export' },
                    customize: function (doc) {
                        let filterLabel = $('#filter_satminkal').val() || 'SEMUA SATMINKAL';
                        doc.content.splice(0, 1, { text: 'DATA BPJS PEGAWAI AKTIF', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Satminkal: ' + filterLabel, fontSize: 10, alignment: 'center', color: '#64748b', margin: [0, 0, 0, 20] });
                        
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
                "searchPlaceholder": "Cari NIP, Nama, dll..." 
            },
            // HACK AJAX PAKE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let satminkal = $('#filter_satminkal').val() || 'semua';
                
                // Bikin kunci unik
                let cacheKey = 'cache_bpjs_' + satminkal;

                // Cek data di memory browser
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data dari Cache Browser (BPJS)");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data dari Server Database (BPJS)");
                    $.ajax({
                        url: "<?= base_url('keuangan/ajax_data_bpjs') ?>",
                        type: "POST",
                        data: {
                            satminkal: $('#filter_satminkal').val() // Kirim filter asli (kosong jika semua)
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

        // Event listener klik tombol
        $('#btn-filter').click(function(){ 
            let satminkal = $('#filter_satminkal').val() || 'semua';
            sessionStorage.removeItem('cache_bpjs_' + satminkal); // Hapus cache spesifik ini
            table.ajax.reload(); 
        });
        
        $('#btn-reset').click(function(){
            // Bersihin SEMUA cache bpjs
            Object.keys(sessionStorage).forEach(function(key){
               if(key.startsWith('cache_bpjs_')) {
                   sessionStorage.removeItem(key);
               }
            });

            satminkalSelect.setChoiceByValue(''); // Kosongin pilihan Choices
            table.ajax.reload();
        });

        // Hapus class bawaan tombol DataTable
        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);
    });
</script>