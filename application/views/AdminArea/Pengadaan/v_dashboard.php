<div class="row fade-in-up">
    <div class="col-sm-12">
        <hr class="mt-0 mb-3 custom-hr">
        <div class="pb-2 mb-3">
            <h4 class="mb-0 fw-bold" style="font-size: 18px;">Dashboard Overview</h4>
        </div>
    </div>
</div>

<div class="row fade-in-up delay-1">
    <div class="col-md-4 mb-4">
        <div class="card-sp3 border-0" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="d-flex justify-content-between align-items-start text-white">
                <div>
                    <p class="opacity-75 small fw-bold mb-1">TOTAL SP3 TAHUN INI</p>
                    <h2 class="fw-bold mb-0"><?= number_format($total_tahun); ?></h2>
                </div>
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                    <i class="mdi mdi-calendar-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card-sp3 border-0" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);">
            <div class="d-flex justify-content-between align-items-start text-white">
                <div>
                    <p class="opacity-75 small fw-bold mb-1">TOTAL SP3 BULAN INI</p>
                    <h2 class="fw-bold mb-0"><?= number_format($total_bulan); ?></h2>
                </div>
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                    <i class="mdi mdi-chart-bar fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card-sp3 border-0" style="background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);">
            <div class="d-flex justify-content-between align-items-start text-white">
                <div>
                    <p class="opacity-75 small fw-bold mb-1">TOTAL SP3 HARI INI</p>
                    <h2 class="fw-bold mb-0"><?= number_format($total_hari); ?></h2>
                </div>
                <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                    <i class="mdi mdi-clock-fast fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row fade-in-up delay-2">
    <div class="col-md-12">
        <div class="card-sp3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0" style="color: inherit;"><i class="mdi mdi-history me-2 text-primary"></i> 10 Pengajuan SP3 Terbaru</h5>
                <a href="<?= base_url('pengadaan/sp3'); ?>" class="btn btn-primary btn-sm fw-bold px-3">
                    Lihat Semua <i class="mdi mdi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-center">
                            <th class="fw-bold border-0">NOMOR SP3</th>
                            <th class="fw-bold border-0">UNIT KERJA</th>
                            <th class="fw-bold border-0">NAMA PROYEK</th>
                            <th class="fw-bold border-0">TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($recent_sp3)): foreach($recent_sp3 as $row): 
                            $encodedNo = urlencode($row->nosp3);
                            $urlBirt = "https://birt.asihputera.or.id:9999/birt/frameset?__report=PengadaanSP3.rptdesign&Group=manajemen&Departemen=MI%20ASIH%20PUTERA&NoSP3=" . $encodedNo;
                        ?>
                        <tr>
                            <td class="fw-bold">
                                <a href="<?= $urlBirt; ?>" target="_blank" class="btn-link-sp3" title="Buka Laporan">
                                    <?= $row->nosp3; ?> <i class="fa-solid fa-arrow-up-right-from-square fa-xs ms-1"></i>
                                </a>
                            </td>
                            <td><?= $row->unit; ?></td>
                            <td><?= $row->proyek; ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($row->pemohontanggal)); ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data pengajuan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>