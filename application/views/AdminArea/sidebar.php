<div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="brand">Mini<span>Portal</span></div>
        <span class="menu-label">Menu Utama</span>

        <?php if($this->session->userdata('nama_group') == 'Pengadaan'): ?>
            <a href="<?= base_url('pengadaan') ?>" class="active">
                <i class="fa-solid fa-file-signature"></i> Daftar SP3
            </a>
        <?php endif; ?>

        <?php if($this->session->userdata('nama_group') == 'Keuangan'): ?>
            <a href="#">
                <i class="fa-solid fa-book"></i> Jurnal Umum
            </a>
            <a href="#">
                <i class="fa-solid fa-wallet"></i> Jurnal Pendapatan
            </a>
            <a href="#">
                <i class="fa-solid fa-file-invoice-dollar"></i> Tunggakan
            </a>
        <?php endif; ?>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="hamburger" id="hamburgerBtn"><i class="fa-solid fa-bars"></i></button>
                <h2 class="page-title animate__animated animate__fadeInDown"><?= isset($title) ? $title : 'Dashboard' ?></h2>
            </div>
            
            <div class="user-menu animate__animated animate__fadeInDown">
                <div class="user-info text-right">
                    <span class="user-name"><?= $this->session->userdata('username'); ?></span>
                    <span class="user-role"><?= $this->session->userdata('nama_group'); ?></span>
                </div>
                <div class="user-icon"><?= strtoupper(substr($this->session->userdata('username'), 0, 1)); ?></div>
                <div class="dropdown">
                    <a href="<?= base_url('auth/logout') ?>"><i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar</a>
                </div>
            </div>
        </div>
        <div class="content-area">