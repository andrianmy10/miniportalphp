<link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Master Data Kode Barang</h4>
            <!-- Tombol Refresh Buat Ngehapus Cache -->
            <button id="btn-refresh" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm">
                <i class="fa-solid fa-rotate me-1"></i> Refresh Data
            </button>
        </div>
    </div>
</div>

<!-- Tambah overflow: hidden !important; di sini biar progress bar nyangkut mulus -->
<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 1; min-height: 400px; overflow: hidden !important;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableKodeBarang" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>KODE BARANG</th>
                    <th>NAMA BARANG</th>
                    <th>SPESIFIKASI</th>
                    <th>SATUAN</th>
                    <th>UKURAN</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#tableKodeBarang').DataTable({
            "processing": true, // <--- KUNCI PROGRESS BAR
            "serverSide": false,
            "scrollX": true,
            "order": [[ 1, "asc" ]], // Default sort NAMA BARANG
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { "data": 3 },
                { "data": 4 },
                { "data": 5 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: 'Data_Kode_Barang_' + new Date().toISOString().slice(0,10), 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { orthogonal: 'export' }
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: 'Data_Kode_Barang_' + new Date().toISOString().slice(0,10), 
                    orientation: 'landscape', 
                    pageSize: 'A4', 
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: { orthogonal: 'export' },
                    customize: function (doc) {
                        doc.content.splice(0, 1, { text: 'MASTER DATA KODE BARANG', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 15] });
                        doc.styles.tableHeader.fillColor = '#1e3a8a';
                        doc.styles.tableHeader.color = '#ffffff';
                        doc.styles.tableHeader.alignment = 'center';
                        
                        let colCount = doc.content[1].table.body[0].length;
                        doc.content[1].table.widths = Array(colCount).fill('*');
                    }
                }
            ],
            "language": { 
                "search": "", 
                "searchPlaceholder": "Cari Kode, Nama, Spek...", // User friendly placeholder
                "processing": "" // Sembunyiin teks "Processing" default DataTables
            },
            
            // MAGIC CACHE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let cacheKey = 'cache_kodebarang_master';
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data Kode Barang dari Cache Browser");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data Kode Barang dari Server");
                    $.ajax({
                        url: "<?= base_url('pengadaan/ajax_kode_barang') ?>",
                        type: "POST",
                        success: function(response) {
                            let parsedResponse = JSON.parse(response);
                            sessionStorage.setItem(cacheKey, JSON.stringify(parsedResponse));
                            callback(parsedResponse);
                        }
                    });
                }
            }
        });

        // Hapus class bawaan tombol DataTable
        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);

        // FUNGSI TOMBOL REFRESH DATA
        $('#btn-refresh').click(function() {
            let btn = $(this);
            // Ganti icon jadi muter pas di-klik (Progress bar akan otomatis muncul karena datatable reload)
            btn.html('<i class="fa-solid fa-rotate fa-spin me-1"></i> Memuat...');
            
            // Hapus cache-nya
            sessionStorage.removeItem('cache_kodebarang_master');
            
            // Reload datatable
            table.ajax.reload(function() {
                // Balikin text tombol kalau udah selesai
                btn.html('<i class="fa-solid fa-rotate me-1"></i> Refresh Data');
            });
        });
    });
</script>