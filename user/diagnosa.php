<?php
$pageTitle = 'Mulai Diagnosa';
$activePage = 'diagnosa';
require_once '../includes/user_header.php';
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$semuaGejala = $conn->query("SELECT * FROM gejala ORDER BY kode ASC")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gejalaTerpilih = $_POST['gejala_id'] ?? [];

    if (empty($gejalaTerpilih)) {
        $error = 'Silakan pilih minimal satu gejala yang Anda rasakan.';
    } else {
        // Simpan sementara di session, proses hitung dilakukan di hasil_diagnosa.php
        $_SESSION['gejala_terpilih'] = array_map('intval', $gejalaTerpilih);
        redirect('hasil_diagnosa.php');
    }
}
?>

<div class="admin-card">
    <h6 class="fw-bold mb-1">Pilih Gejala yang Anda Rasakan</h6>
    <p class="text-muted small mb-4">Centang semua gejala yang sesuai dengan kondisi kulit Anda saat ini, lalu klik "Lihat Hasil Diagnosa".</p>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <?php if (count($semuaGejala) === 0): ?>
        <div class="alert alert-warning">Data gejala belum tersedia. Silakan hubungi admin.</div>
    <?php else: ?>
        <form method="POST">
            <div class="row g-3">
                <?php foreach ($semuaGejala as $g): ?>
                <div class="col-md-6">
                    <div class="form-check border rounded-3 p-3">
                        <input class="form-check-input" type="checkbox" name="gejala_id[]" value="<?= $g['id'] ?>" id="gejala_<?= $g['id'] ?>">
                        <label class="form-check-label ms-1" for="gejala_<?= $g['id'] ?>">
                            <?= htmlspecialchars($g['nama_gejala']) ?>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-gradient mt-4"><i class="fa-solid fa-magnifying-glass-chart me-1"></i> Lihat Hasil Diagnosa</button>
        </form>
    <?php endif; ?>
</div>

<?php require_once '../includes/user_footer.php'; ?>