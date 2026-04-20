<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Data Jurnal Pendapatan Siswa</h4>
        </div>
    </div>
</div>

<div class="card-sp3 fade-in-up delay-1" style="position: relative; z-index: 99; overflow: visible !important;">
    <div class="row align-items-end g-3">
        <div class="col-lg-8 col-md-12">
            <label class="fw-bold small mb-1"><i class="fa-solid fa-user-graduating me-1"></i> Pilih Siswa (Nama atau NIS)</label>
            <select id="filter_nis" class="form-control">
                <option value="">-- Cari Nama / NIS Siswa --</option>
                <?php foreach($siswa as $s): ?>
                    <option value="<?= $s->VALUE ?>"><?= $s->LABEL ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-lg-4 col-md-12">
            <div class="d-flex gap-2 w-100">
                <button id="btn-filter" class="btn btn-primary btn-custom flex-grow-1">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan Data
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
                    <th>NO. INVOICE</th>
                    <th>TANGGAL</th>
                    <th>URAIAN</th>
                    <th>KD. PERKIR</th>
                    <th>NIS</th>
                    <th>NAMA</th>
                    <th>TAGIHAN</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        const siswaSelect = new Choices('#filter_nis', { 
            searchEnabled: true, 
            itemSelectText: '', 
            shouldSort: false,
            placeholderValue: 'Cari Nama Siswa...'
        });

        function getFileName() { 
            let label = $('#filter_nis option:selected').text() || 'Jurnal Pendapatan';
            return label; 
        }

        var table = $('#tablePendapatan').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 1, "asc" ]],
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
                { "data": 4 },
                { "data": 5 },
                { 
                    "data": 6, // Render Rp di Web
                    "render": function(data, type, row) {
                        if (type === 'display') {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(data);
                        }
                        return data; // Angka bersih buat export
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
                        doc.content.splice(0, 1, { text: 'LAPORAN JURNAL PENDAPATAN', fontSize: 14, bold: true, alignment: 'center', color: '#1e3a8a' });
                        doc.content.splice(1, 0, { text: 'Siswa: ' + $('#filter_nis option:selected').text(), fontSize: 10, alignment: 'center', margin: [0, 0, 0, 20] });
                        doc.styles.tableHeader.fillColor = '#1e3a8a';
                        doc.styles.tableHeader.color = '#ffffff';
                    }
                }
            ],
            "language": { "search": "", "searchPlaceholder": "Cari di tabel..." },
            "ajax": {
                "url": "<?= base_url('keuangan/ajax_jurnal_pendapatan') ?>",
                "type": "POST",
                "data": function ( d ) { d.nis = $('#filter_nis').val(); }
            }
        });

        $('#btn-filter').click(function(){ table.ajax.reload(); });
        $('#btn-reset').click(function(){
            siswaSelect.setChoiceByValue('');
            table.ajax.reload();
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);
    });
</script>