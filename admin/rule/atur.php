<?php
$pageTitle = 'Atur Relasi Gejala';
$activePage = 'rule';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$error = '';

$penyakit_id = (int) ($_GET['penyakit_id'] ?? $_POST['penyakit_id'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM penyakit WHERE id = :id");
$stmt->bindParam(':id', $penyakit_id);
$stmt->execute();
$penyakit = $stmt->fetch();

if (!$penyakit) {
    flash('rule_msg', 'Data penyakit tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gejala_ids = $_POST['gejala_id'] ?? [];
    $bobot = $_POST['bobot'] ?? [];

    // Hapus semua rule lama untuk penyakit ini, lalu insert ulang sesuai input terbaru
    $stmt = $conn->prepare("DELETE FROM rule WHERE penyakit_id = :pid");
    $stmt->bindParam(':pid', $penyakit_id);
    $stmt->execute();

    foreach ($gejala_ids as $idx => $gejala_id) {
        $nilai = isset($bobot[$gejala_id]) ? ((float) $bobot[$gejala_id]/100) : 0;
        if ($nilai <= 0) continue; // skip jika bobot tidak diisi/0

        $stmt = $conn->prepare("INSERT INTO rule (penyakit_id, gejala_id, nilai_probabilitas) VALUES (:pid, :gid, :nilai)");
        $stmt->bindParam(':pid', $penyakit_id);
        $stmt->bindParam(':gid', $gejala_id);
        $stmt->bindParam(':nilai', $nilai);
        $stmt->execute();
    }

    flash('rule_msg', 'Relasi gejala untuk penyakit "' . $penyakit['nama_penyakit'] . '" berhasil disimpan.');
    redirect('index.php');
}

// Ambil semua gejala
$semuaGejala = $conn->query("SELECT * FROM gejala ORDER BY kode ASC")->fetchAll();

// Ambil rule yang sudah ada untuk penyakit ini (untuk pre-fill form)
$stmt = $conn->prepare("SELECT gejala_id, nilai_probabilitas FROM rule WHERE penyakit_id = :pid");
$stmt->bindParam(':pid', $penyakit_id);
$stmt->execute();
$ruleAda = [];
foreach ($stmt->fetchAll() as $r) {
    $ruleAda[$r['gejala_id']] = $r['nilai_probabilitas'];
}
?>

<div class="admin-card" style="max-width: 750px;">
    <h6 class="fw-bold mb-1">Atur Relasi Gejala</h6>
    <p class="text-muted small mb-4">
        Penyakit: <span class="badge bg-primary"><?= htmlspecialchars($penyakit['kode']) ?></span>
        <strong><?= htmlspecialchars($penyakit['nama_penyakit']) ?></strong>
    </p>

    <?php if (count($semuaGejala) === 0): ?>
        <div class="alert alert-warning">Belum ada data gejala. Tambahkan data gejala terlebih dahulu di menu Data Gejala.</div>
    <?php else: ?>
        <p class="text-muted small">Centang gejala yang berkaitan dengan penyakit ini, lalu isi nilai probabilitas P(gejala|penyakit) dalam bentuk persen (1-100). Gejala yang tidak dicentang/bernilai 0 tidak akan disimpan sebagai relasi.</p>

        <form method="POST">
            <input type="hidden" name="penyakit_id" value="<?= $penyakit_id ?>">

            <div class="table-responsive">
                <table class="table table-admin align-middle">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th>Gejala</th>
                            <th width="160">Probabilitas (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semuaGejala as $g): ?>
                        <?php $sudahAda = isset($ruleAda[$g['id']]); ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input chk-gejala" name="gejala_id[]" value="<?= $g['id'] ?>" data-target="bobot_<?= $g['id'] ?>" <?= $sudahAda ? 'checked' : '' ?>>
                            </td>
                            <td><span class="badge bg-light text-dark me-1"><?= htmlspecialchars($g['kode']) ?></span> <?= htmlspecialchars($g['nama_gejala']) ?></td>
                            <td>
                                <input type="number" min="1" max="100" step="1" class="form-control form-control-sm"
                                       id="bobot_<?= $g['id'] ?>"
                                       name="bobot[<?= $g['id'] ?>]"
                                       value="<?= $sudahAda ? number_format($ruleAda[$g['id']] * 100, 0) : '' ?>"
                                       <?= $sudahAda ? '' : 'disabled' ?>>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-gradient mt-2">Simpan Relasi</button>
            <a href="index.php" class="btn btn-light">Batal</a>
        </form>
    <?php endif; ?>
</div>

<script>
// Aktifkan/nonaktifkan input bobot sesuai checkbox, dan konversi % ke desimal saat submit
document.querySelectorAll('.chk-gejala').forEach(function (chk) {
    chk.addEventListener('change', function () {
        const target = document.getElementById(this.dataset.target);
        target.disabled = !this.checked;
        if (!this.checked) target.value = '';
    });
});

document.querySelector('form').addEventListener('submit', function () {
    document.querySelectorAll('.chk-gejala:checked').forEach(function (chk) {
        const input = document.getElementById(chk.dataset.target);
        input.value = (parseFloat(input.value || 0) / 100).toFixed(3) * 100; // tetap simpan dalam bentuk persen, konversi dilakukan di PHP
    });
});
</script>

<?php require_once '../../includes/admin_footer.php'; ?>