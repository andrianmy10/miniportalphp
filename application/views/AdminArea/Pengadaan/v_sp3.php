<?php 
    $tahun_sekarang = date('Y');
    $default_awal = $tahun_sekarang . '-01-01';
    $default_akhir = date('Y-m-d');
?>

<style>
    .card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 25px; overflow: visible !important; }
    
    .filter-wrapper { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-start; }
    .input-group { display: flex; flex-direction: column; gap: 6px; flex: 1; min-width: 180px; }
    .input-group-dropdown { position: relative; z-index: 30; } 
    .input-group label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    
    /* ALIGNMENT FIX: Height 42px fix! */
    .modern-input {
        height: 42px !important;
        padding: 0 14px !important; border: 2px solid #cbd5e1 !important; border-radius: 8px !important;
        font-family: inherit; color: #1e293b; transition: all 0.3s; background: white; outline: none; font-size: 13px;
        box-shadow: none !important;
    }
    .modern-input:focus { border-color: #2563eb !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important; }

    /* Override Choices.js */
    .choices { margin-bottom: 0 !important; height: 42px !important; }
    .choices__inner { 
        height: 42px !important; min-height: 42px !important;
        padding: 0 14px !important; border: 2px solid #cbd5e1 !important; border-radius: 8px !important; 
        background: white !important; font-size: 13px !important; display: flex; align-items: center;
    }
    .choices[data-type*="select-one"]::after { border-color: #64748b transparent transparent transparent !important; right: 15px !important; margin-top: -2.5px !important; }
    .choices.is-focused .choices__inner { border-color: #2563eb !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important; }
    .choices__list--dropdown { border-radius: 8px !important; box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important; border: 1px solid #cbd5e1 !important; z-index: 999 !important; }

    /* DT Override - FONT SIZE PAS */
    table.dataTable thead th { font-size: 12px !important; padding: 14px 15px !important; letter-spacing: 0.5px; background: #f8fafc; border-bottom: 2px solid #e2e8f0 !important; }
    table.dataTable tbody td { font-size: 14px !important; padding: 12px 15px !important; vertical-align: middle; border-bottom: 1px solid #f1f5f9 !important; }
    table.dataTable tbody tr:hover { background-color: #f8fafc !important; }
    .dataTables_wrapper .dataTables_filter input { border: 2px solid #cbd5e1; height: 38px; padding: 0 16px; border-radius: 8px; outline: none; width: 100%; max-width: 250px; font-size: 13px; }
    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }

    /* Style Link No SP3 */
    .btn-link-sp3 { color: var(--primary); font-weight: 600; text-decoration: none; transition: 0.3s; }
    .btn-link-sp3:hover { color: var(--primary-hover); text-decoration: underline; }

    /* MODAL STYLING */
    .modal-backdrop {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 200;
        align-items: center; justify-content: center; padding: 20px;
    }
    .modal-backdrop.active { display: flex; }
    .modal-content {
        background: white; width: 100%; max-width: 1100px; height: 85vh;
        border-radius: 16px; display: flex; flex-direction: column; overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-header { padding: 15px 25px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
    .iframe-container { flex: 1; width: 100%; }
    .iframe-container iframe { width: 100%; height: 100%; border: none; }
</style>

<div id="modalSp3" class="modal-backdrop">
    <div class="modal-content animate__animated animate__zoomIn animate__faster">
        <div class="modal-header">
            <h3 class="font-bold text-slate-700" id="modalTitle">Detail Laporan SP3</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-circle-xmark fa-xl"></i>
            </button>
        </div>
        <div class="iframe-container">
            <iframe id="iframeBirt" src="about:blank"></iframe>
        </div>
    </div>
</div>

<div class="card card-filter animate__animated animate__fadeInDown">
    <div class="filter-wrapper">
        <div class="input-group">
            <label><i class="fa-regular fa-calendar-days mr-1"></i> Dari Tanggal</label>
            <input type="text" id="tgl_awal" class="modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>
        
        <div class="input-group">
            <label><i class="fa-regular fa-calendar-check mr-1"></i> Sampai Tanggal</label>
            <input type="text" id="tgl_akhir" class="modern-input" autocomplete="off" placeholder="Pilih Tanggal">
        </div>

        <div class="input-group input-group-dropdown">
            <label><i class="fa-solid fa-building mr-1"></i> Unit Kerja</label>
            <select id="filter_unit">
                <option value="">Semua Unit</option>
            </select>
        </div>
        
        <div class="input-group" style="flex: 0 0 auto;">
            <label style="visibility: hidden;">Aksi</label>
            <div style="display: flex; gap: 10px;">
                <button id="btn-filter" style="height: 42px;" class="inline-flex justify-center items-center px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-all shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-magnifying-glass mr-2"></i> Filter
                </button>
                <button id="btn-reset" style="height: 42px;" class="inline-flex justify-center items-center px-4 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg transition-all">
                    <i class="fa-solid fa-rotate-right mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card card-table animate__animated animate__fadeInUp animate__delay-1s">
    <div class="table-responsive">
        <table id="tableSp3" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>NO SP3</th>
                    <th>UNIT KERJA</th>
                    <th>NAMA PROYEK</th>
                    <th>NAMA PEMOHON</th>
                    <th>TANGGAL</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
    function openModal(noSp3) {
        const modal = document.getElementById('modalSp3');
        const iframe = document.getElementById('iframeBirt');
        const title = document.getElementById('modalTitle');
        const encodedNo = encodeURIComponent(noSp3);
        const url = `https://birt.asihputera.or.id:9999/birt/frameset?__report=PengadaanSP3.rptdesign&Group=manajemen&Departemen=MI%20ASIH%20PUTERA&NoSP3=${encodedNo}`;
        
        title.innerText = `Laporan SP3: ${noSp3}`;
        iframe.src = url;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modalSp3').classList.remove('active');
        document.getElementById('iframeBirt').src = 'about:blank'; 
        document.body.style.overflow = 'auto';
    }

    document.addEventListener("DOMContentLoaded", function() {
        
        // Modal Overlay Click close
        document.getElementById('modalSp3').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
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
        const unitSelect = new Choices('#filter_unit', { searchEnabled: true, itemSelectText: '', shouldSort: false });

        function getExportFileName() { return 'Daftar SP3 ' + $('#tgl_awal').val() + ' sd ' + $('#tgl_akhir').val(); }

        var table = $('#tableSp3').DataTable({
            "processing": true,
            "serverSide": false,
            "scrollX": true,
            "order": [[ 4, "asc" ]], // DEFAULT SORT TANGGAL ASCENDING
            "dom": '<"flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4"Bf>rt<"flex flex-col md:flex-row justify-between items-center mt-6"ip>',
            "columns": [
                { 
                    "data": 0, 
                    "render": function(data, type, row) {
                        return `<a href="javascript:void(0)" onclick="openModal('${data}')" class="btn-link-sp3">${data}</a>`;
                    }
                },
                { "data": 1 }, { "data": 2 }, { "data": 3 }, { "data": 4 }
            ],
            "buttons": [
                { 
                    extend: 'excelHtml5', 
                    filename: getExportFileName, 
                    text: '<i class="fa-solid fa-file-excel mr-2"></i> Excel', 
                    className: 'inline-flex items-center justify-center h-[38px] px-4 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-lg shadow-emerald-500/20' 
                },
                { 
                    extend: 'pdfHtml5', 
                    filename: getExportFileName, 
                    orientation: 'landscape', 
                    pageSize: 'A4', 
                    text: '<i class="fa-solid fa-file-pdf mr-2"></i> PDF', 
                    className: 'inline-flex items-center justify-center h-[38px] px-4 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg shadow-lg shadow-rose-500/20 ml-2',
                    customize: function (doc) {
                        doc.content.splice(0, 1, { text: 'LAPORAN DAFTAR SP3', fontSize: 16, bold: true, alignment: 'center', color: '#1e3a8a', margin: [0, 0, 0, 5] });
                        doc.content.splice(1, 0, { text: 'Periode: ' + $('#tgl_awal').val() + ' s/d ' + $('#tgl_akhir').val(), fontSize: 10, alignment: 'center', color: '#64748b', margin: [0, 0, 0, 20] });
                        doc.styles.tableHeader.fillColor = '#2563eb';
                        doc.styles.tableHeader.color = '#ffffff';
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableHeader.bold = true;
                        doc.styles.tableHeader.fontSize = 10;
                        doc.content[2].table.widths = ['12%', '20%', '33%', '20%', '15%'];
                        for (i = 1; i < doc.content[2].table.body.length; i++) {
                            doc.content[2].table.body[i][0].alignment = 'center'; 
                            doc.content[2].table.body[i][1].alignment = 'center'; 
                            doc.content[2].table.body[i][4].alignment = 'center'; 
                        }
                        doc.defaultStyle.fontSize = 9;
                    }
                }
            ],
            "language": { "search": "", "searchPlaceholder": "Pencarian cepat..." },
            "ajax": {
                "url": "<?= base_url('pengadaan/ajax_list_sp3') ?>",
                "type": "POST",
                "data": function ( d ) { d.tgl_awal = $('#tgl_awal').val(); d.tgl_akhir = $('#tgl_akhir').val(); }
            },
            "initComplete": function(settings, json) {
                this.api().column(1).every(function() {
                    var uniqueData = this.data().unique().sort().toArray();
                    var choiceOptions = [{ value: '', label: 'Semua Unit', selected: true }];
                    uniqueData.forEach(function(item) { choiceOptions.push({ value: item, label: item }); });
                    unitSelect.clearChoices();
                    unitSelect.setChoices(choiceOptions, 'value', 'label', true);
                });
            }
        });

        setTimeout(function() { $('.dt-button').removeClass('dt-button'); }, 100);

        document.getElementById('filter_unit').addEventListener('change', function(e) {
            var val = $.fn.dataTable.util.escapeRegex(e.target.value);
            table.column(1).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        $('#btn-filter').click(function(){ 
            table.ajax.reload(function() {
                table.column(1).every(function() {
                    var uniqueData = this.data().unique().sort().toArray();
                    var choiceOptions = [{ value: '', label: 'Semua Unit', selected: true }];
                    uniqueData.forEach(function(item) { choiceOptions.push({ value: item, label: item }); });
                    unitSelect.clearChoices();
                    unitSelect.setChoices(choiceOptions, 'value', 'label', true);
                });
            }); 
        });

        $('#btn-reset').click(function(){
            dpAwal.selectDate("<?= $default_awal ?>");
            dpAkhir.selectDate("<?= $default_akhir ?>");
            unitSelect.setChoiceByValue('');
            table.column(1).search('').ajax.reload();
        });
    });
</script>