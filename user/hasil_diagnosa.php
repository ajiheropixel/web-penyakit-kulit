<?php
$pageTitle = 'Hasil Diagnosa';
$activePage = 'diagnosa';
require_once '../includes/user_header.php';
require_once '../config/database.php';
require_once '../libs/bayes_calculator.php';

$db = new Database();
$conn = $db->getConnection();

if (empty($_SESSION['gejala_terpilih'])) {
    flash('diagnosa_msg', 'Silakan pilih gejala terlebih dahulu sebelum melihat hasil.');
    redirect('diagnosa.php');
}

$gejalaIds = $_SESSION['gejala_terpilih'];

$bayes = new BayesCalculator($conn);
$hasil = $bayes->hitung($gejalaIds);

$diagnosaTeratas = $hasil[0] ?? null;
$riwayatId = null;

// Simpan ke riwayat (hanya sekali per submit, cek flag session agar tidak dobel saat reload)
if ($diagnosaTeratas && empty($_SESSION['sudah_disimpan'])) {
    $gejalaStr = implode(',', $gejalaIds);
    $stmt = $conn->prepare("INSERT INTO riwayat_diagnosa (user_id, penyakit_id, persentase, gejala_terpilih) VALUES (:uid, :pid, :persen, :gejala)");
    $stmt->bindParam(':uid', $_SESSION['user_id']);
    $stmt->bindParam(':pid', $diagnosaTeratas['penyakit']['id']);
    $stmt->bindParam(':persen', $diagnosaTeratas['persentase']);
    $stmt->bindParam(':gejala', $gejalaStr);
    $stmt->execute();
    $riwayatId = $conn->lastInsertId();
    $_SESSION['sudah_disimpan'] = true;
    $_SESSION['riwayat_id_terakhir'] = $riwayatId;
} else {
    $riwayatId = $_SESSION['riwayat_id_terakhir'] ?? null;
}
?>

<div class="admin-card">
    <h6 class="fw-bold mb-4"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>Hasil Diagnosa Anda</h6>

    <?php if (!$diagnosaTeratas): ?>
        <div class="alert alert-warning">
            Tidak ditemukan kecocokan penyakit berdasarkan gejala yang Anda pilih. Silakan konsultasikan langsung dengan dokter kulit untuk pemeriksaan lebih lanjut.
        </div>
        <a href="diagnosa.php" class="btn btn-light">Coba Diagnosa Ulang</a>
    <?php else: ?>
        <div class="p-4 rounded-3 mb-4" style="background: var(--gradient-blue); color: #fff;">
            <p class="mb-1 small" style="opacity:0.85">Kemungkinan Penyakit Tertinggi</p>
            <h3 class="fw-bold mb-2"><?= htmlspecialchars($diagnosaTeratas['penyakit']['nama_penyakit']) ?></h3>
            <div class="progress" style="height: 10px; background: rgba(255,255,255,0.25);">
                <div class="progress-bar bg-white" style="width: <?= $diagnosaTeratas['persentase'] ?>%"></div>
            </div>
            <p class="mt-2 mb-0 fw-semibold"><?= $diagnosaTeratas['persentase'] ?>% Tingkat Keyakinan</p>
        </div>

        <h6 class="fw-bold">Deskripsi</h6>
        <p class="text-muted"><?= nl2br(htmlspecialchars($diagnosaTeratas['penyakit']['deskripsi'])) ?></p>

        <h6 class="fw-bold mt-4">Solusi & Penanganan</h6>
        <p class="text-muted"><?= nl2br(htmlspecialchars($diagnosaTeratas['penyakit']['solusi'])) ?></p>

        <?php if (count($hasil) > 1): ?>
        <h6 class="fw-bold mt-4">Kemungkinan Lain</h6>
        <ul class="list-unstyled">
            <?php foreach (array_slice($hasil, 1, 3) as $h): ?>
            <li class="d-flex justify-content-between border-bottom py-2">
                <span><?= htmlspecialchars($h['penyakit']['nama_penyakit']) ?></span>
                <span class="fw-semibold text-primary"><?= $h['persentase'] ?>%</span>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div class="mt-4 d-flex gap-2">
            <?php if ($riwayatId): ?>
            <a href="cetak_pdf.php?id=<?= $riwayatId ?>" target="_blank" class="btn btn-gradient"><i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF</a>
            <?php endif; ?>
            <a href="diagnosa.php" class="btn btn-light" onclick="return resetDiagnosa()">Diagnosa Ulang</a>
        </div>

        <div class="alert alert-secondary mt-4 small mb-0">
            <i class="fa-solid fa-circle-info me-1"></i> Hasil ini bersifat estimasi berbasis sistem pakar dan <strong>bukan pengganti diagnosa medis resmi</strong>. Segera konsultasikan ke dokter kulit untuk kepastian dan penanganan lebih lanjut.
        </div>
    <?php endif; ?>
</div>

<script>
function resetDiagnosa() {
    // reset flag session via fetch kecil agar diagnosa baru tersimpan sebagai riwayat baru
    fetch('reset_session.php').then(() => window.location.href = 'diagnosa.php');
    return false;
}
</script>

<?php require_once '../includes/user_footer.php'; ?>