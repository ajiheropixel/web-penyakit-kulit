<?php
$pageTitle = 'Edit Penyakit';
$activePage = 'penyakit';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$error = '';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM penyakit WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$penyakit = $stmt->fetch();

if (!$penyakit) {
    flash('penyakit_msg', 'Data penyakit tidak ditemukan.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = sanitize($_POST['kode']);
    $nama_penyakit = sanitize($_POST['nama_penyakit']);
    $deskripsi = sanitize($_POST['deskripsi']);
    $solusi = sanitize($_POST['solusi']);

    $stmt = $conn->prepare("SELECT id FROM penyakit WHERE kode = :kode AND id != :id");
    $stmt->bindParam(':kode', $kode);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Kode penyakit sudah digunakan oleh data lain.';
    } else {
        $stmt = $conn->prepare("UPDATE penyakit SET kode = :kode, nama_penyakit = :nama, deskripsi = :deskripsi, solusi = :solusi WHERE id = :id");
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama', $nama_penyakit);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':solusi', $solusi);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        flash('penyakit_msg', 'Data penyakit berhasil diperbarui.');
        redirect('index.php');
    }
}
?>

<div class="admin-card" style="max-width: 700px;">
    <h6 class="fw-bold mb-4">Edit Data Penyakit</h6>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Penyakit</label>
            <input type="text" name="kode" class="form-control" value="<?= htmlspecialchars($penyakit['kode']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Penyakit</label>
            <input type="text" name="nama_penyakit" class="form-control" value="<?= htmlspecialchars($penyakit['nama_penyakit']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($penyakit['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Solusi / Penanganan</label>
            <textarea name="solusi" class="form-control" rows="4" required><?= htmlspecialchars($penyakit['solusi']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-gradient">Update</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>