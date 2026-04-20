<!-- SIDEBAR -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item nav-category" style="margin-top: 10px;">Menu Utama</li>

                <?php if($this->session->userdata('nama_group') == 'Pengadaan'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('pengadaan') ?>">
                        <i class="menu-icon mdi mdi-file-document-outline"></i>
                        <span class="menu-title">Daftar SP3</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($this->session->userdata('nama_group') == 'Keuangan'): ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#keuangan-menu" aria-expanded="false" aria-controls="keuangan-menu">
                        <i class="menu-icon mdi mdi-cash-multiple"></i>
                        <span class="menu-title">Keuangan</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="keuangan-menu">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item"><a class="nav-link" href="#">Jurnal Umum</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Jurnal Pendapatan</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Tunggakan</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="main-panel">
            <div class="content-wrapper px-4 pt-4">