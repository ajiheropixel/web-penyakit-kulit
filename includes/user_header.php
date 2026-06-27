<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth_check.php';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'User' ?> - SkinDiag</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="userSidebar">
    <a href="dashboard.php" class="sidebar-brand"><i class="fa-solid fa-stethoscope"></i> SkinDiag</a>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link <?= $activePage == 'dashboard' ? 'active' : '' ?>"><i class="fa-solid fa-gauge me-2"></i> Dashboard</a>
        <a href="diagnosa.php" class="nav-link <?= $activePage == 'diagnosa' ? 'active' : '' ?>"><i class="fa-solid fa-stethoscope me-2"></i> Mulai Diagnosa</a>
        <a href="riwayat.php" class="nav-link <?= $activePage == 'riwayat' ? 'active' : '' ?>"><i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Diagnosa</a>
        <a href="../index.php" class="nav-link"><i class="fa-solid fa-house me-2"></i> Beranda</a>
        <a href="../auth/logout.php" class="nav-link text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Keluar</a>
    </nav>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleUserSidebar()"></div>

<div class="admin-content">
    <div class="admin-topbar">
        <div>
            <button class="btn btn-sm btn-light d-lg-none me-2" onclick="toggleUserSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="d-inline"><?= $pageTitle ?? '' ?></h5>
        </div>
        <div class="text-muted">
            <i class="fa-solid fa-user me-1"></i> <?= htmlspecialchars($_SESSION['user_nama']) ?>
        </div>
    </div>
    <div class="admin-body-content">