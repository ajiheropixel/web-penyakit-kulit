<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../includes/admin_header.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$totalGejala = $conn->query("SELECT COUNT(*) AS jml FROM gejala")->fetch()['jml'];
$totalPenyakit = $conn->query("SELECT COUNT(*) AS jml FROM penyakit")->fetch()['jml'];
$totalUser = $conn->query("SELECT COUNT(*) AS jml FROM users")->fetch()['jml'];
$totalDiagnosa = $conn->query("SELECT COUNT(*) AS jml FROM riwayat_diagnosa")->fetch()['jml'];
?>

<div class="row g-4">
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="fa-solid fa-list-check fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold mb-0"><?= $totalGejala ?></h3>
            <p class="text-muted mb-0">Data Gejala</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="fa-solid fa-virus fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold mb-0"><?= $totalPenyakit ?></h3>
            <p class="text-muted mb-0">Data Penyakit</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="fa-solid fa-users fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold mb-0"><?= $totalUser ?></h3>
            <p class="text-muted mb-0">Total Pengguna</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="fa-solid fa-clock-rotate-left fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold mb-0"><?= $totalDiagnosa ?></h3>
            <p class="text-muted mb-0">Total Diagnosa</p>
        </div>
    </div>
</div>

<div class="admin-card mt-4">
    <p class="text-muted mb-0">Selamat datang di panel admin SkinDiag. Grafik statistik lengkap akan ditambahkan di sesi mendatang.</p>
</div>

<?php require_once '../includes/admin_footer.php'; ?>