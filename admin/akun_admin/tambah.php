<?php
$pageTitle = 'Data Admin';
$activePage = 'akun_admin';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if ($id == $_SESSION['admin_id']) {
        flash('admin_msg', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        redirect('index.php');
    }

    $stmt = $conn->prepare("DELETE FROM admin WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    flash('admin_msg', 'Akun admin berhasil dihapus.');
    redirect('index.php');
}

$adminList = $conn->query("SELECT * FROM admin ORDER BY created_at ASC")->fetchAll();
?>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="fw-bold mb-0">Daftar Akun Admin</h6>
        <a href="tambah.php" class="btn btn-gradient btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Admin</a>
    </div>

    <?php flash('admin_msg'); ?>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Terdaftar</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adminList as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['nama']) ?> <?= $a['id'] == $_SESSION['admin_id'] ? '<span class="badge bg-success-subtle text-success ms-1">Anda</span>' : '' ?></td>
                    <td><?= htmlspecialchars($a['username']) ?></td>
                    <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                    <td>
                        <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                        <a href="index.php?hapus=<?= $a['id'] ?>" class="badge-action bg-danger-subtle text-danger" onclick="return confirm('Hapus akun admin ini?')"><i class="fa-solid fa-trash"></i></a>
                        <?php else: ?>
                        <span class="text-muted small">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>