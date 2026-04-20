<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// ===================================
// CUSTOM ROUTES MINI PORTAL
// ===================================
$route['pengadaan/dashboard'] = 'Pengadaan/viewDashboard';
$route['pengadaan/sp3'] = 'Pengadaan/index';

// ===================================
// CUSTOM ROUTES KEUANGAN
// ===================================
$route['keuangan/dashboard']       = 'Keuangan/viewDashboard';
$route['keuangan/jurnalumum']      = 'Keuangan/jurnalUmum';
$route['keuangan/jurnalpendapatan']= 'Keuangan/jurnalPendapatan';
$route['keuangan/tunggakan']       = 'Keuangan/tunggakan';