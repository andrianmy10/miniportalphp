<?php 
            // Ambil URL segment ke-2 (contoh: keuangan/jurnalumum -> ambil 'jurnalumum')
            $menu_aktif = $this->uri->segment(2); 
        ?>

        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                
                <li class="nav-item d-lg-none pt-3 pb-2 px-3 border-bottom mb-2">
                    <h3 class="fw-bold text-primary m-0" style="font-family: 'Poppins', sans-serif;">MiniPortal</h3>
                    <p class="text-muted small mt-1 mb-0">Hi, <?= $this->session->userdata('nama_lengkap'); ?></p>
                </li>
                
                <li class="nav-item <?= ($menu_aktif == 'dashboard') ? 'active' : '' ?>">
                    <?php 
                        $dash_url = ($this->session->userdata('nama_group') == 'Pengadaan') ? 'pengadaan/dashboard' : 'keuangan/dashboard';
                    ?>
                    <a class="nav-link" href="<?= base_url($dash_url) ?>">
                        <i class="mdi mdi-grid-large menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item nav-category" style="margin-top: 10px;">Menu Utama</li>

                <?php if($this->session->userdata('nama_group') == 'Pengadaan'): ?>

                <li class="nav-item <?= ($menu_aktif == 'kodebarang') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/kodebarang') ?>">
                        <i class="menu-icon mdi mdi-barcode-scan"></i>
                        <span class="menu-title">Data Kode Barang</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'stokgudang') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/stokgudang') ?>">
                        <i class="menu-icon mdi mdi-package-variant-closed"></i>
                        <span class="menu-title">Data Stok Gudang</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'dataumumspb') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/dataumumspb') ?>">
                        <i class="menu-icon mdi mdi-file-document-box-multiple"></i>
                        <span class="menu-title">Data Umum SPB</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'dokevalbakp') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/dokevalbakp') ?>">
                        <i class="menu-icon mdi mdi-clipboard-text"></i>
                        <span class="menu-title">Dokumen Eval & BAKP</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'databapb') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/databapb') ?>">
                        <i class="menu-icon mdi mdi-clipboard-check"></i>
                        <span class="menu-title">Data BAPB</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'databpm') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/databpm') ?>">
                        <i class="menu-icon mdi mdi-truck-delivery"></i>
                        <span class="menu-title">Data BPM</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'sp3') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('pengadaan/sp3') ?>">
                        <i class="menu-icon mdi mdi-file-document-outline"></i>
                        <span class="menu-title">Daftar SP3</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($this->session->userdata('nama_group') == 'Keuangan'): ?>
                <li class="nav-item <?= ($menu_aktif == 'jurnalumum') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('keuangan/jurnalumum') ?>">
                        <i class="menu-icon mdi mdi-book-open-page-variant"></i>
                        <span class="menu-title">Jurnal Umum</span>
                    </a>
                </li>
                
                <li class="nav-item <?= ($menu_aktif == 'jurnalpendapatan') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('keuangan/jurnalpendapatan') ?>">
                        <i class="menu-icon mdi mdi-cash-register"></i>
                        <span class="menu-title">Jurnal Pendapatan</span>
                    </a>
                </li>
                
                <li class="nav-item <?= ($menu_aktif == 'jurnalsiswa') ? 'active' : '' ?>">
                    <a class="nav-link d-flex align-items-center" href="<?= base_url('keuangan/jurnalsiswa') ?>">
                        <i class="menu-icon mdi mdi-wallet"></i>
                        <span class="menu-title" style="white-space: normal; line-height: 1.2; display: inline-block;">
                            Jurnal Pendapatan <br>Siswa
                        </span>
                    </a>
                </li>
                
                <li class="nav-item <?= ($menu_aktif == 'tunggakan') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('keuangan/tunggakan') ?>">
                        <i class="menu-icon mdi mdi-alert-circle-outline text-danger"></i>
                        <span class="menu-title">Tunggakan</span>
                    </a>
                </li>

                <li class="nav-item <?= ($menu_aktif == 'databpjs') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('keuangan/databpjs') ?>">
                        <i class="menu-icon mdi mdi-shield-account text-info"></i>
                        <span class="menu-title">Data BPJS Pegawai</span>
                    </a>
                </li>
                <li class="nav-item <?= ($menu_aktif == 'databsi') ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('keuangan/databsi') ?>">
                        <i class="menu-icon mdi mdi-bank text-success"></i>
                        <span class="menu-title">Data Tagihan BSI</span>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-item d-lg-none mt-4 pt-3 border-top w-100">
                    <div class="px-3">
                        <div class="clock-wrapper mb-3 p-2 rounded text-center" style="background: #eef2ff; border: 1px solid #c7d2fe;">
                            <i class="fa-regular fa-clock text-primary mb-1 d-block" style="font-size: 18px;"></i>
                            <span class="realtime-clock fw-bold text-primary" style="font-size: 11px;">Memuat Waktu...</span>
                        </div>
                        
                        <button class="btn btn-theme-light theme-toggle-btn w-100 mb-2 d-flex justify-content-center align-items-center shadow-sm" style="height: 40px; border: 1px solid #e2e8f0; background: white;">
                            <i class="fa-solid fa-moon text-primary theme-icon me-2"></i> Mode Layar
                        </button>
                        
                        <a href="<?= base_url('auth/logout'); ?>" class="btn btn-danger w-100 d-flex justify-content-center align-items-center shadow-sm" style="height: 40px;">
                            <i class="mdi mdi-power me-2"></i> Keluar
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
        
        <div class="main-panel">
            <div class="content-wrapper px-4 pt-4">