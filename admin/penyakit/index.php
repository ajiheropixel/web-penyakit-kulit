<?php
$pageTitle = 'Data Penyakit';
$activePage = 'penyakit';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM penyakit WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    flash('penyakit_msg', 'Data penyakit berhasil dihapus.');
    redirect('index.php');
}

$penyakit = $conn->query("SELECT * FROM penyakit ORDER BY kode ASC")->fetchAll();
?>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">Daftar Penyakit</h6>
        <a href="tambah.php" class="btn btn-gradient btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Penyakit</a>
    </div>

    <?php flash('penyakit_msg'); ?>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Penyakit</th>
                    <th>Deskripsi</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($penyakit) > 0): ?>
                    <?php foreach ($penyakit as $p): ?>
                    <tr>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($p['kode']) ?></span></td>
                        <td class="fw-semibold"><?= htmlspecialchars($p['nama_penyakit']) ?></td>
                        <td class="text-muted small"><?= htmlspecialchars(mb_strimwidth($p['deskripsi'], 0, 80, '...')) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $p['id'] ?>" class="badge-action bg-warning-subtle text-warning"><i class="fa-solid fa-pen"></i></a>
                            <a href="index.php?hapus=<?= $p['id'] ?>" class="badge-action bg-danger-subtle text-danger" onclick="return confirm('Hapus data penyakit ini? Rule yang terkait juga akan terhapus.')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data penyakit.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>