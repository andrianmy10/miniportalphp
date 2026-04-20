<?php
// Logic Sapaan Waktu Dinamis
date_default_timezone_set('Asia/Jakarta');
$jam = date('H');
if ($jam >= 18 || $jam < 4) { $sapaan = 'Selamat Malam'; }
elseif ($jam >= 4 && $jam < 11) { $sapaan = 'Selamat Pagi'; }
elseif ($jam >= 11 && $jam < 15) { $sapaan = 'Selamat Siang'; }
else { $sapaan = 'Selamat Sore'; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($title) ? $title : 'Mini Portal - Yayasan Asih Putera' ?></title>
    
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminarea/template/vendors/feather/feather.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminarea/template/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminarea/template/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminarea/template/css/vertical-layout-light/style.css') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        body { background-color: #f4f5f7; transition: background-color 0.3s ease; }
        
        /* Warna tulisan hitam tegas untuk sapaan di mode terang */
        .welcome-text, .welcome-text span { color: #000000 !important; }

        /* Animasi CSS Murni */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        .card-sp3 { border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); padding: 20px; margin-bottom: 20px; background: white; border: 1px solid #f1f5f9; transition: all 0.3s; }
        
        .modern-input, .choices__inner { height: 38px !important; min-height: 38px !important; border: 1px solid #e2e8f0 !important; border-radius: 8px !important; background: #f8fafc !important; font-size: 13px !important; transition: 0.3s; }
        
        /* LIGHT MODE: Hover/Focus Form */
        .modern-input:focus, .choices.is-focused .choices__inner { background: white !important; border-color: #1F3BB3 !important; box-shadow: 0 0 0 3px rgba(31, 59, 179, 0.1) !important; }
        
        .choices[data-type*="select-one"]::after { right: 15px !important; margin-top: -2.5px !important; }
        .btn-custom { height: 38px; border-radius: 8px; font-size: 13px; font-weight: 600; padding: 0 16px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; }
        .btn-link-sp3 { color: #1F3BB3; font-weight: 600; text-decoration: none; transition: 0.3s; }
        .btn-link-sp3:hover { color: #152b82; text-decoration: underline; }
        
        table.dataTable thead th { font-size: 12.5px !important; background: white; border-bottom: 1px solid #cbd5e1 !important; text-align: center !important; text-transform: uppercase; padding: 12px 15px !important; color: #64748b; font-weight: 700; transition: 0.3s; }
        table.dataTable tbody td { font-size: 14px !important; vertical-align: middle; padding: 14px 15px !important; color: #334155; border-bottom: 1px solid #f1f5f9 !important; transition: 0.3s; }
        table.dataTable tbody tr:hover { background-color: #f8fafc !important; }
        .dataTables_wrapper .dataTables_filter input { border: 1px solid #cbd5e1; height: 34px; padding: 0 12px; border-radius: 6px; outline: none; font-size: 13px; transition: 0.3s; }
        .custom-hr { border: 0; border-top: 2px solid #e2e8f0; transition: 0.3s; }

        /* ================= DARK MODE OVERRIDES ================= */
        body.dark-mode { background-color: #0b1120 !important; color: #ffffff !important; }
        body.dark-mode .container-fluid, body.dark-mode .main-panel, body.dark-mode .content-wrapper { background-color: #0b1120 !important; }
        
        /* Header & Sidebar Full Dark Blue */
        body.dark-mode .navbar, 
        body.dark-mode .navbar-menu-wrapper, 
        body.dark-mode .sidebar, 
        body.dark-mode .footer,
        body.dark-mode .navbar .navbar-brand-wrapper { background-color: #1e3a8a !important; border-color: #172a68 !important; box-shadow: none !important; }

        body.dark-mode .navbar *, body.dark-mode .sidebar *, body.dark-mode .footer * { color: #ffffff !important; }
        body.dark-mode .sidebar .nav .nav-item.active > .nav-link, body.dark-mode .sidebar .nav .nav-item > .nav-link:hover { background-color: rgba(255, 255, 255, 0.15) !important; color: #ffffff !important; }
        
        body.dark-mode .clock-wrapper { background: rgba(0, 0, 0, 0.25) !important; border: 1px solid rgba(255,255,255,0.2) !important; }
        body.dark-mode .btn-light { background: rgba(255, 255, 255, 0.15) !important; border: none !important; color: #ffffff !important; }
        body.dark-mode .btn-light:hover { background: rgba(255, 255, 255, 0.25) !important; }

        body.dark-mode .dropdown-menu { background-color: #1e3a8a !important; border: 1px solid #172a68 !important; }
        body.dark-mode .dropdown-item:hover { background-color: rgba(255, 255, 255, 0.15) !important; color: #ffffff !important;}
        body.dark-mode .dropdown-header { border-bottom: 1px solid rgba(255, 255, 255, 0.1); }

        body.dark-mode .card-sp3 { background-color: #1e293b !important; border-color: #334155 !important; }
        body.dark-mode .welcome-text, body.dark-mode .welcome-text span, body.dark-mode h4, body.dark-mode label, body.dark-mode .dataTables_info { color: #f8fafc !important; }
        body.dark-mode .custom-hr { border-color: #334155 !important; }

        body.dark-mode .btn-link-sp3 { color: #ffffff !important; }
        body.dark-mode .btn-link-sp3:hover { color: #93c5fd !important; }

        /* Input & Tabel Dark Mode Default */
        body.dark-mode .modern-input, body.dark-mode .choices__inner, body.dark-mode .dataTables_wrapper .dataTables_filter input { background-color: #0f172a !important; border-color: #334155 !important; color: #f8fafc !important; }
        body.dark-mode table.dataTable thead th { background: #1e293b !important; border-bottom: 2px solid #334155 !important; color: #f8fafc !important; }
        body.dark-mode table.dataTable tbody td { background: #1e293b !important; border-bottom: 1px solid #334155 !important; color: #e2e8f0 !important;}
        body.dark-mode table.dataTable tbody tr:hover td { background-color: #334155 !important; }
        
        /* FIX: Dropdown Hover Background & Text Color di Dark Mode */
        body.dark-mode .choices__list--dropdown { background-color: #1e293b !important; border-color: #334155 !important; color: white !important;}
        body.dark-mode .choices__item--choice { color: #cbd5e1 !important;}
        body.dark-mode .choices__list--dropdown .choices__item--selectable.is-highlighted { background-color: rgba(147, 197, 253, 0.1) !important; color: #93c5fd !important; }

        /* FIX: Anti Bocor Putih Pas di Klik (Focus/Open) di Dark Mode */
        body.dark-mode .modern-input:focus, 
        body.dark-mode .choices.is-focused .choices__inner, 
        body.dark-mode .choices.is-open .choices__inner,
        body.dark-mode .dataTables_wrapper .dataTables_filter input:focus { 
            background-color: #0f172a !important; /* Tetap birdong gelap */
            border-color: #93c5fd !important; /* Nyala border biru muda */
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.1) !important; 
            color: #f8fafc !important; 
        }
        body.dark-mode .choices__input, body.dark-mode .choices__input--cloned { background-color: #1e293b !important; color: #f8fafc !important; }
    </style>
</head>
<body>
<script>if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-mode');</script>

<div class="container-scroller">
    
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row" style="box-shadow: 0 2px 10px rgba(0,0,0,0.02); transition: 0.3s;">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
            <div class="me-3">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
            </div>
            <div>
                <a class="navbar-brand brand-logo" href="#">
                    <h3 class="fw-bold text-primary m-0" style="font-family: 'Poppins', sans-serif;">MiniPortal</h3>
                </a>
            </div>
        </div>
        
        <div class="navbar-menu-wrapper d-flex align-items-top"> 
            <ul class="navbar-nav">
                <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                    <h1 class="welcome-text" style="font-size:20px; margin-top: 10px;">
                        <?= $sapaan ?>, <span class="fw-bold"><?= $this->session->userdata('username'); ?></span>
                    </h1>
                </li>
            </ul>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item d-none d-lg-block me-3">
                    <button id="theme-toggle" class="btn btn-sm btn-light d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; border-radius: 8px; border: 1px solid #e2e8f0; background: white; transition: 0.3s;">
                        <i class="fa-solid fa-moon text-primary" id="theme-icon" style="font-size: 16px;"></i>
                    </button>
                </li>

                <li class="nav-item d-none d-lg-block me-4">
                    <div class="clock-wrapper d-flex align-items-center rounded px-3 py-1" style="background: #eef2ff; border: 1px solid #c7d2fe; transition: 0.3s;">
                        <i class="fa-regular fa-clock me-2 text-primary" style="font-size: 16px;"></i>
                        <span id="realtime-clock" class="fw-bold text-primary" style="font-size: 14px; letter-spacing: 0.5px;">00.00.00 | Memuat...</span>
                    </div>
                </li>
                
                <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                    <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="img-xs rounded-circle profile-avatar" src="https://ui-avatars.com/api/?name=<?= urlencode($this->session->userdata('username')); ?>&background=1e3a8a&color=fff&bold=true" alt="Profile image"> 
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">
                            <img class="img-md rounded-circle profile-avatar" src="https://ui-avatars.com/api/?name=<?= urlencode($this->session->userdata('username')); ?>&background=1e3a8a&color=fff&bold=true" alt="Profile image">
                            <p class="mb-1 mt-2 font-weight-semibold"><?= $this->session->userdata('username'); ?></p>
                            <p class="fw-light mb-0"><?= $this->session->userdata('nama_group'); ?></p>
                        </div>
                        <a href="<?= base_url('auth/logout'); ?>" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power me-2"></i>Sign Out</a>
                    </div>
                </li>
            </ul>
            
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid page-body-wrapper">