<?php
$pageTitle = 'Riwayat Diagnosa';
$activePage = 'riwayat';
require_once '../../includes/admin_header.php';
require_once '../../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Filter sederhana berdasarkan nama penyakit (opsional)
$keyword = sanitize($_GET['cari'] ?? '');

$sql = "
    SELECT rd.*, p.nama_penyakit, u.nama AS nama_user, u.email
    FROM riwayat_diagnosa rd
    JOIN penyakit p ON rd.penyakit_id = p.id
    JOIN users u ON rd.user_id = u.id
";
if ($keyword !== '') {
    $sql .= " WHERE u.nama LIKE :kw OR p.nama_penyakit LIKE :kw ";
}
$sql .= " ORDER BY rd.created_at DESC";

$stmt = $conn->prepare($sql);
if ($keyword !== '') {
    $kw = '%' . $keyword . '%';
    $stmt->bindParam(':kw', $kw);
}
$stmt->execute();
$riwayat = $stmt->fetchAll();
?>

<div class="admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Riwayat Diagnosa Seluruh Pengguna</h6>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari nama user / penyakit..." value="<?= htmlspecialchars($keyword) ?>">
            <button class="btn btn-sm btn-gradient">Cari</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-admin align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Pengguna</th>
                    <th>Hasil Diagnosa</th>
                    <th>Keyakinan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($riwayat) > 0): ?>
                    <?php foreach ($riwayat as $r): ?>
                    <tr>
                        <td><?= date('d M Y, H:i', strtotime($r['created_at'])) ?></td>
                        <td>
                            <?= htmlspecialchars($r['nama_user']) ?>
                            <div class="text-muted small"><?= htmlspecialchars($r['email']) ?></div>
                        </td>
                        <td class="fw-semibold"><?= htmlspecialchars($r['nama_penyakit']) ?></td>
                        <td><span class="badge bg-primary"><?= $r['persentase'] ?>%</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                   <tr><td colspan="3">
    <div class="empty-state">
        <i class="fa-solid fa-inbox"></i>
        Belum ada data gejala. Klik "Tambah Gejala" untuk menambahkan.
    </div>
</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../../includes/admin_footer.php'; ?>