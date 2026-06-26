<?php
$pageTitle = 'Data Relasi/Rule';
$activePage = 'rule';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM rule WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    flash('rule_msg', 'Relasi gejala-penyakit berhasil dihapus.');
    redirect('index.php');
}

// Ambil semua penyakit beserta jumlah gejala terkait & rangkuman rule
$penyakitList = $conn->query("SELECT * FROM penyakit ORDER BY kode ASC")->fetchAll();
?>

<?php flash('rule_msg'); ?>

<div class="row g-4">
    <?php if (count($penyakitList) === 0): ?>
        <div class="col-12">
            <div class="admin-card text-center text-muted py-5">
                Belum ada data penyakit. Silakan tambahkan data penyakit terlebih dahulu di menu <a href="../penyakit/index.php">Data Penyakit</a>.
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($penyakitList as $p): ?>
        <?php
        $stmt = $conn->prepare("SELECT r.*, g.kode AS kode_gejala, g.nama_gejala FROM rule r JOIN gejala g ON r.gejala_id = g.id WHERE r.penyakit_id = :pid ORDER BY g.kode ASC");
        $stmt->bindParam(':pid', $p['id']);
        $stmt->execute();
        $rules = $stmt->fetchAll();
        ?>
        <div class="col-md-6">
            <div class="admin-card h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge bg-primary mb-1"><?= htmlspecialchars($p['kode']) ?></span>
                        <h6 class="fw-bold mb-0"><?= htmlspecialchars($p['nama_penyakit']) ?></h6>
                    </div>
                    <a href="atur.php?penyakit_id=<?= $p['id'] ?>" class="btn btn-gradient btn-sm">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Atur Relasi
                    </a>
                </div>

                <?php if (count($rules) > 0): ?>
                    <ul class="list-unstyled mb-0 small">
                        <?php foreach ($rules as $r): ?>
                            <li class="d-flex justify-content-between border-bottom py-2">
                                <span><span class="badge bg-light text-dark me-1"><?= htmlspecialchars($r['kode_gejala']) ?></span> <?= htmlspecialchars($r['nama_gejala']) ?></span>
                                <span class="fw-semibold text-primary"><?= number_format($r['nilai_probabilitas'] * 100, 0) ?>%
                                    <a href="index.php?hapus=<?= $r['id'] ?>" class="text-danger ms-2" onclick="return confirm('Hapus relasi gejala ini?')"><i class="fa-solid fa-xmark"></i></a>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted small mb-0">Belum ada gejala yang dikaitkan dengan penyakit ini.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>