<?php
$pageTitle = 'Tambah Penyakit';
$activePage = 'penyakit';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = sanitize($_POST['kode']);
    $nama_penyakit = sanitize($_POST['nama_penyakit']);
    $deskripsi = sanitize($_POST['deskripsi']);
    $solusi = sanitize($_POST['solusi']);

    $stmt = $conn->prepare("SELECT id FROM penyakit WHERE kode = :kode");
    $stmt->bindParam(':kode', $kode);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $error = 'Kode penyakit sudah digunakan, gunakan kode lain.';
    } else {
        $stmt = $conn->prepare("INSERT INTO penyakit (kode, nama_penyakit, deskripsi, solusi) VALUES (:kode, :nama, :deskripsi, :solusi)");
        $stmt->bindParam(':kode', $kode);
        $stmt->bindParam(':nama', $nama_penyakit);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':solusi', $solusi);
        $stmt->execute();
        flash('penyakit_msg', 'Data penyakit berhasil ditambahkan.');
        redirect('index.php');
    }
}
?>

<div class="admin-card" style="max-width: 700px;">
    <h6 class="fw-bold mb-4">Tambah Data Penyakit</h6>

    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kode Penyakit</label>
            <input type="text" name="kode" class="form-control" placeholder="Contoh: P01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Penyakit</label>
            <input type="text" name="nama_penyakit" class="form-control" placeholder="Contoh: Dermatitis Kontak" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai penyakit ini..." required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Solusi / Penanganan</label>
            <textarea name="solusi" class="form-control" rows="4" placeholder="Rekomendasi solusi dan penanganan awal..." required></textarea>
        </div>
        <button type="submit" class="btn btn-gradient">Simpan</button>
        <a href="index.php" class="btn btn-light">Batal</a>
    </form>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>