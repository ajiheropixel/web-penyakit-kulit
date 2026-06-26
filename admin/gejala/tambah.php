<?php
$pageTitle = 'Tambah Gejala';
$activePage = 'gejala';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = sanitize($_POST['kode']);
    $nama_gejala = sanitize($_POST['nama_gejala']);

    $stmt = $conn->prepare("SELECT id FROM gejala WHERE kode = :kode");
    $stmt->bindParam(':kode', $kode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Kode gejala sudah digunakan, gunakan kode lain.';
    } else {
        $stmt = $conn->prepare("INSERT INTO gejala (kode, nama_gejala) VALUES (:kode, :nama_gejala)");
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama_gejala', $nama_gejala);
        $stmt->execute();
        flash('gejala_msg', 'Data gejala berhasil ditambahkan.');
        redirect('index.php');
    }
}
?>

<div class="admin-card" style="max-width: 600px;">
    <h6 class="fw-bold mb-4">Tambah Data Gejala</h6>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Gejala</label>
            <input type="text" name="kode" class="form-control" placeholder="Contoh: G01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Gejala</label>
            <textarea name="nama_gejala" class="form-control" rows="2" placeholder="Contoh: Muncul bercak merah pada kulit" required></textarea>
        </div>
        <button type="submit" class="btn btn-gradient">Simpan</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>