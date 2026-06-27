<?php
$pageTitle = 'Data Admin';
$activePage = 'akun_admin';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$totalGejala = $conn->query("SELECT COUNT(*) AS jml FROM gejala")->fetch()['jml'];
$totalPenyakit = $conn->query("SELECT COUNT(*) AS jml FROM penyakit")->fetch()['jml'];
$totalUser = $conn->query("SELECT COUNT(*) AS jml FROM users")->fetch()['jml'];
$totalDiagnosa = $conn->query("SELECT COUNT(*) AS jml FROM riwayat_diagnosa")->fetch()['jml'];

// Data untuk grafik: jumlah diagnosa per penyakit
$dataPenyakit = $conn->query("
    SELECT p.nama_penyakit, COUNT(rd.id) AS jumlah
    FROM penyakit p
    LEFT JOIN riwayat_diagnosa rd ON rd.penyakit_id = p.id
    GROUP BY p.id
    ORDER BY jumlah DESC
")->fetchAll();

$labelPenyakit = array_column($dataPenyakit, 'nama_penyakit');
$jumlahPenyakit = array_column($dataPenyakit, 'jumlah');

// Data untuk grafik: tren diagnosa 6 bulan terakhir
$dataTren = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS bulan, COUNT(*) AS jumlah
    FROM riwayat_diagnosa
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY bulan
    ORDER BY bulan ASC
")->fetchAll();

$labelTren = array_column($dataTren, 'bulan');
$jumlahTren = array_column($dataTren, 'jumlah');
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

<div class="row g-4 mt-1">
    <div class="col-md-7">
        <div class="admin-card">
            <h6 class="fw-bold mb-3">Tren Diagnosa 6 Bulan Terakhir</h6>
            <?php if (count($dataTren) > 0): ?>
                <canvas id="chartTren" height="180"></canvas>
            <?php else: ?>
                <p class="text-muted text-center py-4 mb-0">Belum ada data diagnosa untuk ditampilkan.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-5">
        <div class="admin-card">
            <h6 class="fw-bold mb-3">Distribusi Diagnosa per Penyakit</h6>
            <?php if (count($dataPenyakit) > 0 && array_sum($jumlahPenyakit) > 0): ?>
                <canvas id="chartPenyakit" height="220"></canvas>
            <?php else: ?>
                <p class="text-muted text-center py-4 mb-0">Belum ada data diagnosa untuk ditampilkan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
<?php if (count($dataTren) > 0): ?>
new Chart(document.getElementById('chartTren'), {
    type: 'line',
    data: {
        labels: <?= json_encode($labelTren) ?>,
        datasets: [{
            label: 'Jumlah Diagnosa',
            data: <?= json_encode($jumlahTren) ?>,
            borderColor: '#2196f3',
            backgroundColor: 'rgba(33,150,243,0.15)',
            fill: true,
            tension: 0.35
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
<?php endif; ?>

<?php if (count($dataPenyakit) > 0 && array_sum($jumlahPenyakit) > 0): ?>
new Chart(document.getElementById('chartPenyakit'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labelPenyakit) ?>,
        datasets: [{
            data: <?= json_encode($jumlahPenyakit) ?>,
            backgroundColor: ['#1565c0', '#2196f3', '#42a5f5', '#90caf9', '#bbdefb', '#0d47a1', '#64b5f6']
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
});
<?php endif; ?>
</script>

<?php require_once '../../includes/admin_footer.php'; ?>