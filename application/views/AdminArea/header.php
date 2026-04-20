<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Mini Portal' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.css">
    
    <style>
        :root {
            --primary: #2563eb; 
            --primary-hover: #1d4ed8;
            --bg-body: #f1f5f9; 
            --surface: #ffffff; 
            --text-main: #1e293b; 
            --text-muted: #64748b;
            --border: #cbd5e1;
        }

        body { 
            margin: 0; font-family: 'Poppins', sans-serif; display: flex; 
            background-color: var(--bg-body); color: var(--text-main); overflow-x: hidden;
        }

        /* RESPONSIVE SIDEBAR */
        .sidebar { 
            width: 270px; 
            background: linear-gradient(180deg, #1e3a8a 0%, #2563eb 100%); 
            min-height: 100vh; 
            padding: 24px 0; box-shadow: 4px 0 15px rgba(0,0,0,0.1); 
            z-index: 60; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @media (max-width: 768px) {
            .sidebar { position: fixed; transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .content-area { padding: 0 15px 20px 15px !important; }
            .topbar { padding: 15px 20px !important; }
        }

        .sidebar-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 55;
            opacity: 0; visibility: hidden; transition: 0.3s;
        }
        .sidebar-overlay.active { opacity: 1; visibility: visible; }

        .sidebar .brand { text-align: center; margin-bottom: 40px; font-family: 'Montserrat', sans-serif; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 1px; }
        .sidebar .brand span { color: #93c5fd; }
        .sidebar .menu-label { padding: 0 25px; font-size: 11px; font-weight: 700; color: #bfdbfe; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; display: block; opacity: 0.8; }
        .sidebar a { display: flex; align-items: center; gap: 15px; color: #e0e7ff; padding: 14px 25px; text-decoration: none; font-weight: 500; transition: all 0.3s ease; border-right: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255, 255, 255, 0.1); color: #ffffff; border-right-color: #ffffff; }

        .main-content { flex: 1; display: flex; flex-direction: column; height: 100vh; overflow-y: auto; width: 100%; }
        
        .topbar { background: transparent; padding: 25px 35px; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 100 !important; }
        
        .hamburger { display: none; background: white; border: none; font-size: 20px; padding: 10px 15px; border-radius: 8px; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.05); color: var(--text-main); }
        @media (max-width: 768px) { .hamburger { display: block; } .page-title { font-size: 18px; } }

        .user-menu { position: relative; cursor: pointer; display: flex; align-items: center; gap: 12px; z-index: 110; }
        .user-name { display: block; font-size: 14px; font-weight: 600; color: var(--text-main); }
        .user-role { display: block; font-size: 11px; color: var(--text-muted); }
        @media (max-width: 768px) { .user-info { display: none; } }
        .user-icon { width: 42px; height: 42px; background: white; color: var(--primary); border-radius: 12px; display: flex; justify-content: center; align-items: center; font-weight: 800; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .dropdown { visibility: hidden; opacity: 0; position: absolute; top: 60px; right: 0; background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 12px; min-width: 180px; z-index: 120; transform: translateY(10px); transition: all 0.3s; border: 1px solid var(--border); }
        .user-menu:hover .dropdown { visibility: visible; opacity: 1; transform: translateY(0); }
        .dropdown a { display: flex; align-items: center; gap: 10px; padding: 12px 20px; text-decoration: none; color: var(--text-main); font-size: 14px; transition: 0.2s; }
        .dropdown a:hover { background: #fef2f2; color: #ef4444; }

        .content-area { padding: 0 35px 35px 35px; flex: 1; }
        .card-filter { position: relative; z-index: 50 !important; }
        .card-table { position: relative; z-index: 1 !important; }
    </style>
</head>
<body>