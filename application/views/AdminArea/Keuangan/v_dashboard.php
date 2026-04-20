<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Dashboard Keuangan Overview</h4>
        </div>
    </div>
</div>

<div class="row fade-in-up delay-1">
    <div class="col-md-4 mb-4">
        <div class="card-sp3 border-0" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
            <div class="d-flex justify-content-between align-items-start text-white">
                <div>
                    <p class="opacity-75 small fw-bold mb-1">JURNAL UMUM (BULAN INI)</p>
                    <h2 class="fw-bold mb-0"><?= number_format($total_jurnal_bulan); ?> Data</h2>
                </div>
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                    <i class="mdi mdi-book-open-page-variant fs-3"></i>
                </div>
            </div>
            <p class="text-white mt-3 mb-0 small"><i class="mdi mdi-calendar-range"></i> Update: <?= date('F Y') ?></p>
        </div>
    </div>
    
    </div>