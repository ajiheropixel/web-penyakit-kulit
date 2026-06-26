<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/admin_auth_check.php';

// $activePage dikirim dari masing-masing halaman untuk menandai menu aktif
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - SkinDiag</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
    <a href="dashboard.php" class="sidebar-brand"><i class="fa-solid fa-stethoscope"></i> SkinDiag Admin</a>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link <?= $activePage == 'dashboard' ? 'active' : '' ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>

        <div class="nav-section-title">Data Master</div>
        <a href="gejala/index.php" class="nav-link <?= $activePage == 'gejala' ? 'active' : '' ?>"><i class="fa-solid fa-list-check me-2"></i> Data Gejala</a>
        <a href="penyakit/index.php" class="nav-link <?= $activePage == 'penyakit' ? 'active' : '' ?>"><i class="fa-solid fa-virus me-2"></i> Data Penyakit</a>
        <a href="rule/index.php" class="nav-link <?= $activePage == 'rule' ? 'active' : '' ?>"><i class="fa-solid fa-diagram-project me-2"></i> Data Relasi/Rule</a>

        <div class="nav-section-title">Laporan</div>
        <a href="riwayat/index.php" class="nav-link <?= $activePage == 'riwayat' ? 'active' : '' ?>"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Diagnosa</a>

        <div class="nav-section-title">Pengaturan</div>
        <a href="akun_admin/index.php" class="nav-link <?= $activePage == 'akun_admin' ? 'active' : '' ?>"><i class="fa-solid fa-user-shield me-2"></i> Data Admin</a>
        <a href="../auth/logout.php" class="nav-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar</a>
    </nav>
</aside>

<div class="admin-content">
    <div class="admin-topbar">
        <div>
            <button class="btn btn-sm btn-light d-lg-none me-2" onclick="document.getElementById('adminSidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="d-inline"><?= $pageTitle ?? '' ?></h5>
        </div>
        <div class="text-muted">
            <i class="fa-solid fa-user-shield me-1"></i> <?= htmlspecialchars($_SESSION['admin_nama']) ?>
        </div>
    </div>
    <div class="admin-body-content">