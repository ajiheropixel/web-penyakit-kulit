<?php
$pageTitle = 'Edit Gejala';
$activePage = 'gejala';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$error = '';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM gejala WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$gejala = $stmt->fetch();

if (!$gejala) {
    flash('gejala_msg', 'Data gejala tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = sanitize($_POST['kode']);
    $nama_gejala = sanitize($_POST['nama_gejala']);

    $stmt = $conn->prepare("SELECT id FROM gejala WHERE kode = :kode AND id != :id");
    $stmt->bindParam(':kode', $kode);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Kode gejala sudah digunakan oleh data lain.';
    } else {
        $stmt = $conn->prepare("UPDATE gejala SET kode = :kode, nama_gejala = :nama_gejala WHERE id = :id");
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama_gejala', $nama_gejala);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        flash('gejala_msg', 'Data gejala berhasil diperbarui.');
        redirect('index.php');
    }
}
?>

<div class="admin-card" style="max-width: 600px;">
    <h6 class="fw-bold mb-4">Edit Data Gejala</h6>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Gejala</label>
            <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($gejala['kode']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Gejala</label>
            <textarea name="nama_gejala" class="form-control" rows="2" required><?= htmlspecialchars($gejala['nama_gejala']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-gradient">Update</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>