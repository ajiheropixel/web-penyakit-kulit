<?php
$pageTitle = 'Data Gejala';
$activePage = 'gejala';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Hapus
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM gejala WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    flash('gejala_msg', 'Data gejala berhasil dihapus.');
    redirect('index.php');
}

$gejala = $conn->query("SELECT * FROM gejala ORDER BY kode ASC")->fetchAll();
?>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">Daftar Gejala</h6>
        <a href="tambah.php" class="btn btn-gradient btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Gejala</a>
    </div>

    <?php flash('gejala_msg'); ?>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Gejala</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($gejala) > 0): ?>
                    <?php foreach ($gejala as $g): ?>
                    <tr>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($g['kode']) ?></span></td>
                        <td><?= htmlspecialchars($g['nama_gejala']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $g['id'] ?>" class="badge-action bg-warning-subtle text-warning"><i class="fa-solid fa-pen"></i></a>
                            <a href="index.php?hapus=<?= $g['id'] ?>" class="badge-action bg-danger-subtle text-danger" onclick="return confirm('Hapus gejala ini? Rule yang terkait juga akan terhapus.')"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data gejala.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>