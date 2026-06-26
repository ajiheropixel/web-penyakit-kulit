<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../includes/user_header.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT COUNT(*) AS jml FROM riwayat_diagnosa WHERE user_id = :uid");
$stmt->bindParam(':uid', $_SESSION['user_id']);
$stmt->execute();
$totalDiagnosaSaya = $stmt->fetch()['jml'];
?>

<div class="row g-4">
    <div class="col-md-6">
        <div class="admin-card">
            <h5 class="fw-bold">Halo, <?= htmlspecialchars($_SESSION['user_nama']) ?> 👋</h5>
            <p class="text-muted">Selamat datang di sistem diagnosa penyakit kulit. Mulai diagnosa kapan saja jika Anda merasakan gejala pada kulit Anda.</p>
            <a href="diagnosa.php" class="btn btn-gradient mt-2"><i class="fa-solid fa-stethoscope me-1"></i> Mulai Diagnosa Sekarang</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="admin-card text-center">
            <i class="fa-solid fa-clock-rotate-left fa-2x text-primary mb-2"></i>
            <h3 class="fw-bold mb-0"><?= $totalDiagnosaSaya ?></h3>
            <p class="text-muted mb-0">Total Diagnosa yang Pernah Dilakukan</p>
        </div>
    </div>
</div>

<?php require_once '../includes/user_footer.php'; ?>