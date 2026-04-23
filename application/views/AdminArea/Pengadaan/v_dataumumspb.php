<link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=' . time()) ?>">

<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="d-flex justify-content-between align-items-center pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Master Data Umum SPB</h4>
            <button id="btn-refresh" class="btn btn-primary btn-sm fw-bold px-3 shadow-sm">
                <i class="fa-solid fa-rotate me-1"></i> Refresh Data
            </button>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 1; min-height: 400px; overflow: hidden !important;">
    <div class="table-responsive pt-2" style="overflow-x: visible;">
        <table id="tableSpb" class="table table-hover display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>NO. SPB</th>
                    <th>NO. SP3</th>
                    <th>NAMA VENDOR</th>
                    <th>GRAND TOTAL</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $('#tableSpb').DataTable({
            "processing": true, // <--- KUNCI MANCING PROGRESS BAR
            "serverSide": false,
            "scrollX": true,
            "order": [[ 0, "desc" ]], // Urut dari NO SPB paling baru
            "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3"Bf>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 text-muted small"ip>',
            "columns": [
                { 
                    "data": 0, // NO SPB (Link to BIRT Report)
                    "render": function(data, type, row) {
                        // Kalau buat di layar, bikin jadi link tombol biru
                        if (type === 'display' && data) {
                            let urlBirt = `https://birt.asihputera.or.id:9999/birt/frameset?__report=PengadaanSPB.rptdesign&Group=manajemen&Departemen=MI%20ASIH%20PUTERA&NoSPB=${encodeURIComponent(data)}`;
                            return `<a href="${urlBirt}" target="_blank" class="fw-bold text-primary text-decoration-none" title="Buka Report SPB"><i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i> ${data}</a>`;
                        }
                        // Kalau buat export Excel, balikin teks aslinya aja biar rapi
                        return data;
                    }
                },
                { "data": 1 },
                { "data": 2 },
                { 
                    "data": 3, // GRAND TOTAL
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data || 0);
                        }
                        return data; // Angka mentah buat Excel
                    }
                }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: 'Data_Umum_SPB_' + new Date().toISOString().slice(0,10), 
                    text: '<i class="fa-solid fa-file-excel me-2"></i> Excel', 
                    className: 'btn btn-sm btn-success fw-bold me-2',
                    exportOptions: { orthogonal: 'export' } 
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: 'Data_Umum_SPB_' + new Date().toISOString().slice(0,10), 
                    orientation: 'portrait', 
                    pageSize: 'A4', 
                    text: '<i class="fa-solid fa-file-pdf me-2"></i> PDF', 
                    className: 'btn btn-sm btn-danger fw-bold',
                    exportOptions: { orthogonal: 'export' },
                    customize: function (doc) {
                        doc.content.splice(0, 1, { text: 'DATA UMUM SPB', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 15] });
                        
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
                "searchPlaceholder": "Cari No SPB, Vendor...",
                "processing": "" // Sembunyiin teks "Processing" default DataTables
            },
            
            // MAGIC CACHE SESSION STORAGE
            "ajax": function (data, callback, settings) {
                let cacheKey = 'cache_dataumumspb_master';
                let cachedData = sessionStorage.getItem(cacheKey);

                if (cachedData) {
                    console.log("Ambil data SPB dari Cache Browser");
                    callback(JSON.parse(cachedData));
                } else {
                    console.log("Ambil data SPB dari Server");
                    $.ajax({
                        url: "<?= base_url('pengadaan/ajax_dataumum_spb') ?>",
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

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);

        // FUNGSI TOMBOL REFRESH DATA
        $('#btn-refresh').click(function() {
            let btn = $(this);
            btn.html('<i class="fa-solid fa-rotate fa-spin me-1"></i> Memuat...');
            
            // Hapus cache
            sessionStorage.removeItem('cache_dataumumspb_master');
            
            // Reload datatable
            table.ajax.reload(function() {
                btn.html('<i class="fa-solid fa-rotate me-1"></i> Refresh Data');
            });
        });
    });
</script>